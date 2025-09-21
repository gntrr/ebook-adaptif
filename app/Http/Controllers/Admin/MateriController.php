<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Materi;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class MateriController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'ensure.admin']);
    }

    /**
     * List + filter ringan.
     * Filter: ?bab=2&track=A&tipe=evaluasi&step=3&q=variabel
     */
    public function index(Request $request)
    {
        $q = Materi::query()
            ->when($request->filled('bab'), fn($x) => $x->where('bab', (int)$request->integer('bab')))
            ->when($request->filled('track'), fn($x) => $x->where('track', $this->normTrack($request->string('track')->toString())))
            ->when($request->filled('step'), fn($x) => $x->where('step', (int)$request->integer('step')))
            ->when($request->filled('tipe'), fn($x) => $x->where('tipe', $request->string('tipe')->toString()))
            ->when($request->filled('q'), fn($x) => $x->where('judul', 'ilike', '%'.$request->string('q')->toString().'%'))
            ->orderBy('bab')->orderBy('track')->orderBy('step')
            ->orderByRaw("CASE tipe WHEN 'materi' THEN 1 WHEN 'praktek' THEN 2 WHEN 'evaluasi' THEN 3 WHEN 'evaluasi_bab' THEN 4 ELSE 5 END");

        $materis = $q->paginate(15)->withQueryString();

        return view('admin.materi.index', compact('materis'));
    }

    public function create()
    {
        return view('admin.materi.create');
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        try {
            $m = Materi::create($data);
        } catch (QueryException $e) {
            // handle unique (bab,track,step,tipe)
            if ($this->isUniqueViolation($e)) {
                return back()->withInput()->withErrors([
                    'tipe' => 'Slot kombinasi bab/track/step/tipe sudah ada. Coba ubah tipe atau step.',
                ]);
            }
            throw $e;
        }

        return redirect()->route('admin.materi.edit', $m)->with('status', 'Materi dibuat ✅');
    }

    public function edit(Materi $materi)
    {
        return view('admin.materi.edit', compact('materi'));
    }

    public function update(Request $request, Materi $materi)
    {
        $data = $this->validated($request, $materi->id);

        try {
            $materi->update($data);
        } catch (QueryException $e) {
            if ($this->isUniqueViolation($e)) {
                return back()->withInput()->withErrors([
                    'tipe' => 'Slot kombinasi bab/track/step/tipe sudah ada. Coba ubah tipe atau step.',
                ]);
            }
            throw $e;
        }

        return redirect()->route('admin.materi.index')->with('status', 'Materi diupdate ✅');
    }

    public function destroy(Materi $materi)
    {
        $materi->delete();
        return redirect()->route('admin.materi.index')->with('status', 'Materi dihapus 🗑️');
    }

    /* =========================
       Helpers
       ========================= */

    /** Validasi request untuk create/update. */
    private function validated(Request $request, ?int $ignoreId = null): array
    {
        $trackRule = Rule::in(['A','B',null]);
        $tipeRule  = Rule::in(['materi','praktek','evaluasi','evaluasi_bab']);

        $validator = Validator::make($request->all(), [
            'bab'              => ['required','integer','min:1','max:99'],
            'track'            => ['nullable', $trackRule],
            'step'             => ['required','integer','min:1','max:5'],
            'tipe'             => ['required', $tipeRule],
            'judul'            => ['required','string','max:120'],
            'konten'           => ['nullable','string'],
            'konten_type'      => ['required', Rule::in(['html','image'])],
            'konten_image_path'=> ['nullable','string','max:255'],
        ]);

        $validator->sometimes('konten', ['required','string'], fn ($input) => $input->konten_type === 'html');
        $validator->sometimes('konten_image_path', ['required','string','max:255'], fn ($input) => $input->konten_type === 'image');

        $data = $validator->validate();

        $data['track'] = $this->normTrack($data['track'] ?? null);

        if ($data['konten_type'] === 'image') {
            $data['konten'] = null;
        } else {
            $data['konten_image_path'] = $data['konten_image_path'] ?: null;
        }

        $exists = Materi::query()
            ->when($ignoreId, fn($q) => $q->where('id','!=',$ignoreId))
            ->where('bab', $data['bab'])
            ->when($data['track'] === null, fn($q) => $q->whereNull('track'), fn($q) => $q->where('track', $data['track']))
            ->where('step', $data['step'])
            ->where('tipe', $data['tipe'])
            ->exists();

        if ($exists) {
            $request->validate(['tipe' => 'prohibited'], [
                'tipe.prohibited' => 'Slot kombinasi bab/track/step/tipe sudah ada.',
            ]);
        }

        return $data;
    }    /** Deteksi unique key violation dari DB (PostgreSQL). */
    private function isUniqueViolation(QueryException $e): bool
    {
        // Postgres unique_violation SQLSTATE 23505
        return (string) $e->getCode() === '23505';
    }

    private function normTrack(null|string $track): ?string
    {
        if ($track === null) return null;
        $t = strtoupper(trim((string)$track));
        return in_array($t, ['A','B'], true) ? $t : null;
    }
}



