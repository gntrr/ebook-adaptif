<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;

/**
 * Menentukan langkah berikutnya setelah evaluasi step maupun evaluasi bab.
 * Aturan default (bisa kamu ganti nanti):
 * - skor >= 90  => "excellent" → boleh lompat step (configurable per bab/step)
 * - 60..89      => lulus normal → lanjut 1 step
 * - < 60        => remedial     → ulang / tahan di step yang sama atau mundur
 *
 * Khusus Bab 2 (bercabang):
 * - Lulus Bab 2A => lanjut ke 2B
 * - Lulus Bab 2B => naik ke Bab 3
 */
final class LearningPathService
{
    /** Threshold (bisa diubah ke config) */
    public const PASS_SCORE      = 60;
    public const EXCELLENT_SCORE = 90;

    /** Jumlah step per bab (sesuai skema draf) */
    public const MAX_STEP = 5;

    /**
     * Peta “loncat step” untuk skor excellent (>=90).
     * Format: [$bab][$trackOrNull][$stepSaatIni] => stepTujuan
     * - null = bab jalur tunggal
     * - Kalau tidak didefinisikan, defaultnya naik 2 step (tanpa melewati MAX_STEP)
     */
    private array $excellentJumpMap = [
        // Contoh: di Bab 1, dari Step 2 boleh lompat ke Step 4
        1 => [
            null => [
                2 => 4,
            ],
        ],
        // Contoh: di Bab 2A/2B, dari Step 2 bisa lompat ke Step 4
        2 => [
            'A' => [ 2 => 4 ],
            'B' => [ 2 => 4 ],
        ],
        // Bab lain belum diatur → fallback ke default +2
    ];

    /**
     * Tentukan next setelah evaluasi STEP.
     */
    public function nextAfterStepEval(User $user, int $bab, ?string $track, int $step, int $skor): array
    {
        // Remedial
        if ($skor < self::PASS_SCORE) {
            // Strategi remedial sederhana: tahan di step yang sama (kamu bisa ubah ke max($step-1,1))
            return $this->makeState($bab, $track, $step, remedial: true);
        }

        // Excellent → coba loncat
        if ($skor >= self::EXCELLENT_SCORE) {
            $jumpTo = $this->jumpStepFor($bab, $track, $step);
            $stepNext = min($jumpTo, self::MAX_STEP);
        } else {
            // Lulus normal
            $stepNext = min($step + 1, self::MAX_STEP);
        }

        // Kalau sudah mencapai (atau melewati) step 5, arahkan ke evaluasi_bab
        if ($stepNext >= self::MAX_STEP && $step >= self::MAX_STEP) {
            return $this->makeState($bab, $track, self::MAX_STEP, mode: 'evaluasi_bab');
        }

        return $this->makeState($bab, $track, $stepNext);
    }

    /**
     * Tentukan next setelah evaluasi BAB (checkpoint).
     */
    public function nextAfterBabEval(User $user, int $bab, ?string $track, int $skor): array
    {
        if ($skor < self::PASS_SCORE) {
            // Gagal evaluasi bab → ulang dari awal bab (step 1)
            return $this->makeState($bab, $track, 1, remedial: true);
        }

        // Khusus Bab 2 (bercabang)
        if ($bab === 2) {
            if ($track === 'A') {
                // Lulus 2A => lanjut 2B step 1
                return $this->makeState(2, 'B', 1);
            }
            if ($track === 'B') {
                // Lulus 2B => naik ke Bab 3 step 1
                return $this->makeState(3, null, 1);
            }
            // Jika track null (edge-case), mulai dari 2A
            return $this->makeState(2, 'A', 1);
        }

        // Bab jalur tunggal (1, 3, 4, 5, 6, ...)
        return $this->makeState($bab + 1, null, 1);
    }

    /**
     * Hitung “step loncat” untuk skor excellent berdasarkan peta.
     * Fallback default: +2 step.
     */
    private function jumpStepFor(int $bab, ?string $track, int $step): int
    {
        $trackKey = $track ?? null;

        if (isset($this->excellentJumpMap[$bab])) {
            $mapBab = $this->excellentJumpMap[$bab];

            // Prefer track tertentu jika ada
            if ($trackKey !== null && isset($mapBab[$trackKey][$step])) {
                return (int) $mapBab[$trackKey][$step];
            }

            // Cek map bab umum (track null)
            if (isset($mapBab[null][$step])) {
                return (int) $mapBab[null][$step];
            }
        }

        // Default loncat dua step
        return $step + 2;
    }

    /**
     * Utility untuk membangun array state next.
     */
    private function makeState(int $bab, ?string $track, int $step, bool $remedial = false, ?string $mode = null): array
    {
        return [
            'bab'      => $bab,
            'track'    => $track,                // null | 'A' | 'B'
            'step'     => max(1, min($step, self::MAX_STEP)),
            'remedial' => $remedial,
            'mode'     => $mode,                 // null | 'evaluasi_bab'
        ];
    }
}
