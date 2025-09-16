<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Materi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MateriController extends Controller
{
    /**
     * Tampilkan kumpulan slot di sebuah "posisi" kurikulum:
     * - kombinasi (bab, track?, step)
     * - urut: materi -> praktek -> evaluasi -> evaluasi_bab
     *
     * Route contoh:
     *  GET /bab/{bab}/{track?}/{step}
     *  - track bisa null (bab jalur tunggal), atau 'A' / 'B' (Bab 2)
     */
    public function show(int $bab, ?string $track = null, int $step = 1)
    {
        // Normalisasi track dari URL (kadang orang suka kirim 'null' string)
        if ($track === 'null' || $track === '') {
            $track = null;
        }

        // Ambil slot materi untuk posisi ini
        $items = Materi::query()
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

        // (Opsional) Breadcrumb sederhana buat view
        $breadcrumb = [
            'bab'   => $bab,
            'track' => $track,  // null | 'A' | 'B'
            'step'  => $step,
        ];

        // (Opsional) Guard santai: cegah akses jauh di depan progress (kalau mau soft-redirect)
        // NOTE: aturan anti-skip sebaiknya dipindah ke middleware EnsureEligibleStep.
        $user = Auth::user();
        if ($user) {
            if ($this->isAheadOfCurrent($user->current_bab, $user->current_track, $user->current_step, $bab, $track, $step)) {
                // Soft redirect ke posisi sekarang (bukan abort), biar UX ramah
                return redirect()->route('materi.show', [
                    $user->current_bab,
                    $user->current_track ?? null,
                    $user->current_step,
                ])->with('status', 'Kamu belum membuka posisi itu—lanjutkan belajar sesuai urutan ya ✨');
            }
        }

        // Kirim ke view
        return view('materi.show', [
            'items'      => $items,      // koleksi slot: materi/praktek/evaluasi/evaluasi_bab
            'bab'        => $bab,
            'track'      => $track,
            'step'       => $step,
            'breadcrumb' => $breadcrumb,
        ]);
    }

    /**
     * Bandingkan (bab, track, step) request vs posisi user saat ini.
     * Sederhana: jika bab lebih besar -> ahead. Jika bab sama dan track bab 2,
     * urutan null < A < B (null dianggap sebelum A). Jika setara, cek step.
     */
    private function isAheadOfCurrent(?int $curBab, ?string $curTrack, ?int $curStep, int $reqBab, ?string $reqTrack, int $reqStep): bool
    {
        if ($curBab === null || $curStep === null) {
            return false; // kalau user belum ada state, jangan blok
        }

        // Urutan track untuk Bab bercabang (2): null(0) < A(1) < B(2)
        $rank = fn (?string $t) => $t === null ? 0 : ($t === 'A' ? 1 : 2);

        if ($reqBab > $curBab) {
            return true;
        }
        if ($reqBab < $curBab) {
            return false;
        }

        // reqBab == curBab
        if ($reqBab === 2) {
            if ($rank($reqTrack) > $rank($curTrack)) {
                return true;
            }
            if ($rank($reqTrack) < $rank($curTrack)) {
                return false;
            }
        }

        // track sama (atau bab bukan 2)
        return $reqStep > $curStep;
    }
}
