<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Materi;
use App\Models\Evaluasi;
use App\Models\HasilEvaluasi;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Ringkasan admin:
     * - Metrik umum (total user, admin, attempt, lulus-rate)
     * - Rekap per bab (attempts, avg skor, lulus)
     * - Top performer & paling butuh remedial
     * - Aktivitas terbaru
     *
     * Route: GET /admin (pakai middleware 'can:admin' / cek is_admin di constructor)
     */

    public function __construct()
    {
        $this->middleware(['auth', 'ensure.admin']);
    }

    public function index(Request $request)
    {
        // (opsional) jaga-jaga kalau belum pasang gate/middleware
        if (!Auth::user()?->is_admin) {
            abort(403);
        }

        // Filter waktu (default: 30 hari terakhir)
        $days     = (int) $request->integer('days', 30);
        $end      = CarbonImmutable::now();
        $start    = $end->subDays(max(1, $days));

        // ---------- 1) Metrik umum ----------
        $totalUsers    = User::count();
        $totalAdmins   = User::where('is_admin', true)->count();
        $totalMateri   = Materi::count();
        $totalEvaluasi = Evaluasi::count();

        $attemptsQ = HasilEvaluasi::query()
            ->whereBetween('created_at', [$start, $end]);

        $totalAttempts = (clone $attemptsQ)->count();

        $avgScore  = (clone $attemptsQ)->avg('skor');
        $avgScore  = $avgScore ? round((float) $avgScore, 2) : 0.00;

        $passCount = (clone $attemptsQ)->where('lulus', true)->count();
        $passRate  = $totalAttempts ? round(($passCount / $totalAttempts) * 100, 2) : 0.00;

        // ---------- 2) Rekap per bab ----------
        $rekapPerBab = HasilEvaluasi::query()
            ->select([
                'materi.bab',
                DB::raw('COUNT(*)::int AS attempts'),
                DB::raw('ROUND(AVG(hasil_evaluasi.skor), 2) AS avg_skor'),
                DB::raw('SUM(CASE WHEN hasil_evaluasi.lulus THEN 1 ELSE 0 END)::int AS lulus_count'),
                DB::raw('ROUND( (SUM(CASE WHEN hasil_evaluasi.lulus THEN 1 ELSE 0 END)::numeric / NULLIF(COUNT(*),0)) * 100, 2) AS lulus_rate'),
            ])
            ->join('evaluasi', 'evaluasi.id', '=', 'hasil_evaluasi.evaluasi_id')
            ->join('materi', 'materi.id', '=', 'evaluasi.materi_id')
            ->whereBetween('hasil_evaluasi.created_at', [$start, $end])
            ->groupBy('materi.bab')
            ->orderBy('materi.bab')
            ->get();

        // ---------- 3) Top performer (rata2 skor tertinggi) ----------
        $topPerformers = HasilEvaluasi::query()
            ->select([
                'users.id',
                'users.name',
                DB::raw('ROUND(AVG(hasil_evaluasi.skor), 2) AS avg_skor'),
                DB::raw('COUNT(*)::int AS attempts'),
            ])
            ->join('users', 'users.id', '=', 'hasil_evaluasi.user_id')
            ->whereBetween('hasil_evaluasi.created_at', [$start, $end])
            ->groupBy('users.id', 'users.name')
            ->havingRaw('COUNT(*) >= 3') // minimal 3 attempt biar fair (ubah sesuai kebutuhan)
            ->orderByDesc('avg_skor')
            ->limit(10)
            ->get();

        // ---------- 4) Paling butuh remedial (rate gagal tinggi) ----------
        $needsRemedial = HasilEvaluasi::query()
            ->select([
                'users.id',
                'users.name',
                DB::raw('COUNT(*)::int AS attempts'),
                DB::raw('SUM(CASE WHEN hasil_evaluasi.lulus THEN 1 ELSE 0 END)::int AS lulus_count'),
                DB::raw('ROUND( (1 - (SUM(CASE WHEN hasil_evaluasi.lulus THEN 1 ELSE 0 END)::numeric / NULLIF(COUNT(*),0))) * 100, 2) AS fail_rate'),
                DB::raw('ROUND(AVG(hasil_evaluasi.skor), 2) AS avg_skor'),
            ])
            ->join('users', 'users.id', '=', 'hasil_evaluasi.user_id')
            ->whereBetween('hasil_evaluasi.created_at', [$start, $end])
            ->groupBy('users.id', 'users.name')
            ->havingRaw('COUNT(*) >= 3')
            ->orderByDesc('fail_rate')
            ->limit(10)
            ->get();

        // ---------- 5) Aktivitas terbaru ----------
        $recentActivity = HasilEvaluasi::query()
            ->with([
                'evaluasi:id,materi_id',
                'evaluasi.materi:id,bab,track,step,tipe,judul',
                'user:id,name',
            ])
            ->latest()
            ->limit(15)
            ->get();

        // ---------- 6) Distribusi posisi user (heatmap bab/step) ----------
        //   Mengelompokkan posisi belajar saat ini untuk pantau "macet" di mana
        $posisiDistribusi = User::query()
            ->select([
                'current_bab',
                'current_track',
                'current_step',
                DB::raw('COUNT(*)::int AS users'),
            ])
            ->groupBy('current_bab', 'current_track', 'current_step')
            ->orderBy('current_bab')
            ->orderBy('current_track')
            ->orderBy('current_step')
            ->get();

        return view('admin.dashboard', [
            'filter'          => ['start' => $start->toDateTimeString(), 'end' => $end->toDateTimeString()],
            'metrics'         => [
                'total_users'     => $totalUsers,
                'total_admins'    => $totalAdmins,
                'total_materi'    => $totalMateri,
                'total_evaluasi'  => $totalEvaluasi,
                'avg_skor'        => $avgScore,
                'total_attempts'  => $totalAttempts,
                'pass_rate'       => $passRate,
            ],
            'rekapPerBab'     => $rekapPerBab,
            'topPerformers'   => $topPerformers,
            'needsRemedial'   => $needsRemedial,
            'recentActivity'  => $recentActivity,
            'posisiDistribusi'=> $posisiDistribusi,
        ]);
    }

    /**
     * Versi JSON (kalau mau dipakai untuk chart AJAX)
     * Route: GET /admin/dashboard.json?days=30
     */
    public function json(Request $request)
    {
        if (!Auth::user()?->is_admin) {
            abort(403);
        }

        $days  = (int) $request->integer('days', 30);
        $end   = CarbonImmutable::now();
        $start = $end->subDays(max(1, $days));

        $rekapPerBab = HasilEvaluasi::query()
            ->select([
                'materi.bab',
                DB::raw('COUNT(*)::int AS attempts'),
                DB::raw('ROUND(AVG(hasil_evaluasi.skor), 2) AS avg_skor'),
                DB::raw('SUM(CASE WHEN hasil_evaluasi.lulus THEN 1 ELSE 0 END)::int AS lulus_count'),
            ])
            ->join('evaluasi', 'evaluasi.id', '=', 'hasil_evaluasi.evaluasi_id')
            ->join('materi', 'materi.id', '=', 'evaluasi.materi_id')
            ->whereBetween('hasil_evaluasi.created_at', [$start, $end])
            ->groupBy('materi.bab')
            ->orderBy('materi.bab')
            ->get();

        return response()->json([
            'users' => [
                'total'  => User::count(),
                'admins' => User::where('is_admin', true)->count(),
            ],
            'rekapPerBab' => $rekapPerBab,
            'range'       => ['start' => $start->toDateTimeString(), 'end' => $end->toDateTimeString()],
        ]);
    }
}
