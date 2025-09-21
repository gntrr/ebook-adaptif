<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Materi;
use Illuminate\Support\Facades\Auth;

class MateriController extends Controller
{
    public function show(int $bab, ?string $track = null, int $step = 1)
    {
        if ($track === 'null' || trim((string) $track) === '') {
            $track = null;
        }

        $items = Materi::query()
            ->with(['evaluasis' => fn ($q) => $q->orderBy('id')])
            ->where('bab', $bab)
            ->when($track !== null, fn ($q) => $q->where('track', $track), fn ($q) => $q->whereNull('track'))
            ->where('step', $step)
            ->orderByRaw("
                CASE tipe
                    WHEN 'materi' THEN 1
                    WHEN 'praktek' THEN 2
                    WHEN 'evaluasi' THEN 3
                    WHEN 'evaluasi_bab' THEN 4
                    ELSE 5
                END
            ")
            ->get();

        abort_if($items->isEmpty(), 404, 'Materi tidak ditemukan untuk posisi ini.');

        $user = Auth::user();
        if ($user && $this->isAheadOfCurrent($user->current_bab, $user->current_track, $user->current_step, $bab, $track, $step)) {
            return redirect()->route('materi.show', [
                $user->current_bab,
                $user->current_track ?? null,
                $user->current_step,
            ])->with('status', 'Kamu belum membuka posisi itu. Lanjutkan belajar sesuai urutan ya!');
        }

        return view('materi.show', [
            'items' => $items,
            'bab'   => $bab,
            'track' => $track,
            'step'  => $step,
        ]);
    }

    private function isAheadOfCurrent(?int $curBab, ?string $curTrack, ?int $curStep, int $reqBab, ?string $reqTrack, int $reqStep): bool
    {
        if ($curBab === null || $curStep === null) {
            return false;
        }

        $rank = fn (?string $t) => $t === null ? 0 : ($t === 'A' ? 1 : 2);

        if ($reqBab > $curBab) {
            return true;
        }
        if ($reqBab < $curBab) {
            return false;
        }

        if ($reqBab === 2) {
            if ($rank($reqTrack) > $rank($curTrack)) {
                return true;
            }
            if ($rank($reqTrack) < $rank($curTrack)) {
                return false;
            }
        }

        return $reqStep > $curStep;
    }
}
