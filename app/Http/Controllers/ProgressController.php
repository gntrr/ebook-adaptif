<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\HasilEvaluasi;

class ProgressController extends Controller
{
    /**
     * Dashboard progres user:
     * - Posisi sekarang (bab/track/step) + progress %
     * - Rekap per bab: attempts, rata2 skor, jumlah lulus
     * - Riwayat evaluasi terbaru (paginate)
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // 1) Posisi sekarang (state dari tabel users)
        $state = [
            'current_bab'   => $user->current_bab,
            'current_track' => $user->current_track,     // null | 'A' | 'B'
            'current_step'  => $user->current_step,
            'progress'      => (float) $user->progress,  // %
            'continue_url'  => route('materi.show', [
                $user->current_bab,
                $user->current_track ?? null,
                $user->current_step,
            ]),
        ];

        // 2) Rekap per bab (pakai join ke evaluasis->materis buat ambil bab)
        //    - attempts: total submit
        //    - avg_skor: rata-rata skor
        //    - lulus_count: jumlah submit lulus
        $rekapPerBab = HasilEvaluasi::query()
            ->select([
                'materis.bab',
                DB::raw('COUNT(*)::int AS attempts'),
                DB::raw('ROUND(AVG(hasil_evaluasi.skor), 2) AS avg_skor'),
                DB::raw('SUM(CASE WHEN hasil_evaluasi.lulus THEN 1 ELSE 0 END)::int AS lulus_count'),
            ])
            ->join('evaluasi', 'evaluasi.id', '=', 'hasil_evaluasi.evaluasi_id')
            ->join('materi', 'materi.id', '=', 'evaluasi.materi_id')
            ->where('hasil_evaluasi.user_id', $user->id)
            ->groupBy('materi.bab')
            ->orderBy('materi.bab')
            ->get();

        // 3) Riwayat evaluasi terbaru (paginate)
        $history = HasilEvaluasi::query()
            ->with([
                'evaluasi:id,materi_id',
                'evaluasi.materi:id,bab,track,step,tipe,judul',
            ])
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(10)
            ->withQueryString();

        // 4) (Opsional) Perkiraan completion real berbasis data (distinct step yang lulus)
        //    Ini bisa menggantikan/menyempurnakan field users.progress kalau mau akurat.
        $completedDistinct = HasilEvaluasi::query()
            ->join('evaluasi', 'evaluasi.id', '=', 'hasil_evaluasi.evaluasi_id')
            ->join('materi', 'materi.id', '=', 'evaluasi.materi_id')
            ->where('hasil_evaluasi.user_id', $user->id)
            ->where('hasil_evaluasi.lulus', true)
            ->whereIn('materi.tipe', ['evaluasi', 'evaluasi_bab'])
            ->distinct()
            ->count(DB::raw("materi.bab || '-' || COALESCE(materi.track,'-') || '-' || materi.step"));

        // total slot evaluasi (untuk persentase), default 6 bab x 5 step = 30 (sesuaikan kalau kurikulum berubah)
        $totalEvaluativeSlots = 30;
        $completionFromData = round(($completedDistinct / max(1, $totalEvaluativeSlots)) * 100, 2);

        return view('progress.index', [
            'state'               => $state,
            'rekapPerBab'         => $rekapPerBab,
            'history'             => $history,
            'completionFromData'  => $completionFromData, // referensi jika ingin tampilkan "progres akurat"
        ]);
    }

    /**
     * (Opsional) Endpoint JSON kalau kamu butuh konsumsi via AJAX/chart.
     */
    public function summaryJson()
    {
        $userId = Auth::id();

        $rekap = HasilEvaluasi::query()
            ->select([
                'materi.bab',
                DB::raw('COUNT(*)::int AS attempts'),
                DB::raw('ROUND(AVG(hasil_evaluasi.skor), 2) AS avg_skor'),
                DB::raw('SUM(CASE WHEN hasil_evaluasi.lulus THEN 1 ELSE 0 END)::int AS lulus_count'),
            ])
            ->join('evaluasi', 'evaluasi.id', '=', 'hasil_evaluasi.evaluasi_id')
            ->join('materi', 'materi.id', '=', 'evaluasi.materi_id')
            ->where('hasil_evaluasi.user_id', $userId)
            ->groupBy('materi.bab')
            ->orderBy('materi.bab')
            ->get();

        return response()->json([
            'user'  => Auth::user()->only(['id','name','current_bab','current_track','current_step','progress']),
            'rekap' => $rekap,
        ]);
    }
}
