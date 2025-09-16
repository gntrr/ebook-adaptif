<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Klasifikasi;
use App\Models\User;
use App\Models\HasilEvaluasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class KlasifikasiController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'ensure.admin']);
    }

    /**
     * Tabel klasifikasi user:
     * - Search by name/email
     * - Tampilkan kategori tersimpan + rekomendasi (berdasarkan avg skor)
     */
    public function index(Request $request)
    {
        $term = $request->string('q')->toString();

        // Ambil user + klasifikasi tersimpan
        $users = User::query()
            ->select('users.id','users.name','users.email',
                'users.current_bab','users.current_track','users.current_step','users.progress',
                DB::raw('k.kategori AS kategori_tersimpan'))
            ->leftJoin('klasifikasi as k', 'k.user_id', '=', 'users.id')
            ->when($term, function ($q) use ($term) {
                $q->where(function ($x) use ($term) {
                    $x->where('users.name', 'ilike', "%{$term}%")
                      ->orWhere('users.email', 'ilike', "%{$term}%");
                });
            })
            ->orderByDesc('k.kategori') // biar A-B-C ngumpul (opsional)
            ->orderBy('users.name')
            ->paginate(15)
            ->withQueryString();

        // Hitung rekomendasi avg skor untuk user dalam page ini (hemat query)
        $userIds = $users->getCollection()->pluck('id');

        $avgSkor = HasilEvaluasi::query()
            ->select('user_id', DB::raw('ROUND(AVG(skor), 2) AS avg'))
            ->whereIn('user_id', $userIds)
            ->groupBy('user_id')
            ->pluck('avg','user_id'); // [user_id => 87.50, ...]

        // Map rekomendasi kategori dari avg skor
        $recommend = [];
        foreach ($userIds as $uid) {
            $avg = (float) ($avgSkor[$uid] ?? 0);
            $recommend[$uid] = [
                'avg'      => $avg,
                'kategori' => $this->recommendCategory($avg),
            ];
        }

        return view('admin.klasifikasi.index', [
            'users'      => $users,
            'recommend'  => $recommend, // gunakan di tabel UI sebagai "saran"
        ]);
    }

    /**
     * Create/Update kategori untuk user tertentu.
     * Body: kategori in ['A','B','C']
     */
    public function storeOrUpdate(Request $request, User $user)
    {
        $data = $request->validate([
            'kategori' => ['required', Rule::in(['A','B','C'])],
        ]);

        Klasifikasi::updateOrCreate(
            ['user_id' => $user->id],
            ['kategori' => $data['kategori']]
        );

        return back()->with('status', "Kategori untuk {$user->name} diset ke {$data['kategori']} ✅");
    }

    /**
     * Hapus kategori tersimpan (kembali ke "tanpa kategori" → admin bisa pakai rekomendasi).
     */
    public function destroy(User $user)
    {
        Klasifikasi::where('user_id', $user->id)->delete();
        return back()->with('status', "Kategori untuk {$user->name} dihapus 🗑️");
    }

    /* =========================
       Helpers
       ========================= */

    /** Rekomendasi kategori dari rata-rata skor. */
    private function recommendCategory(float $avgSkor): string
    {
        if ($avgSkor >= 90) return 'A';
        if ($avgSkor >= 60) return 'B';
        return 'C';
    }
}
