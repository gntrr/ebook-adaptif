<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Materi;
use Illuminate\Support\Facades\Auth;

class StudentDashboardController extends Controller
{
    public function __invoke()
    {
        $user = Auth::user();

        // Current position
        $currentMateri = null;
        if ($user->current_bab && $user->current_step) {
            $currentMateri = Materi::query()
                ->slot($user->current_bab, $user->current_track, $user->current_step)
                ->first();
        }

        // Next materi (same bab/track higher step) else evaluasi_bab in same bab
        $nextMateri = null;
        if ($user->current_bab && $user->current_step) {
            $nextMateri = Materi::query()
                ->where('bab', $user->current_bab)
                ->when($user->current_track, fn($q)=>$q->where('track',$user->current_track), fn($q)=>$q->whereNull('track'))
                ->where('step', '>', $user->current_step)
                ->orderBy('step')
                ->orderByRaw("CASE tipe WHEN 'materi' THEN 1 WHEN 'praktek' THEN 2 WHEN 'evaluasi' THEN 3 WHEN 'evaluasi_bab' THEN 4 ELSE 5 END")
                ->first();

            if (!$nextMateri) {
                // fallback evaluasi bab
                $nextMateri = Materi::query()
                    ->where('bab', $user->current_bab)
                    ->when($user->current_track, fn($q)=>$q->where('track',$user->current_track), fn($q)=>$q->whereNull('track'))
                    ->where('tipe','evaluasi_bab')
                    ->first();
            }
        }

        // Last evaluation attempt
        $lastAttempt = $user->hasilEvaluasi()
            ->with(['evaluasi.materi'])
            ->latest('created_at')
            ->first();

        // Stats (simple)
        $stats = $user->hasilEvaluasi()
            ->selectRaw('COUNT(*) as total, AVG(skor) as avg_skor, SUM(CASE WHEN lulus THEN 1 END) as lulus_count')
            ->first();

        return view('dashboard', [
            'user' => $user,
            'currentMateri' => $currentMateri,
            'nextMateri' => $nextMateri,
            'lastAttempt' => $lastAttempt,
            'stats' => $stats,
        ]);
    }
}
