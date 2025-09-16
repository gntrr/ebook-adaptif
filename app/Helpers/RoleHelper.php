<?php

namespace App\Helpers;

use App\Models\User;

final class RoleHelper
{
    /** True kalau user admin. */
    public static function isAdmin(?User $user): bool
    {
        return (bool) ($user?->is_admin ?? false);
    }

    /** Lempar 403 jika bukan admin. */
    public static function ensureAdmin(?User $user): void
    {
        if (!self::isAdmin($user)) {
            abort(403, 'Hanya admin yang boleh mengakses.');
        }
    }

    /**
     * Cek kelayakan akses posisi belajar.
     * Return true jika request “di depan” dari state user (butuh blok/redirect).
     */
    public static function isAheadOfState(
        ?int $curBab, ?string $curTrack, ?int $curStep,
        int $reqBab, ?string $reqTrack, int $reqStep
    ): bool {
        if ($curBab === null || $curStep === null) return false;

        $rank = fn (?string $t) => $t === null ? 0 : ($t === 'A' ? 1 : 2);

        if ($reqBab !== $curBab) return $reqBab > $curBab;

        // jika bab sama & bab bercabang (2): bandingkan track
        if ($reqBab === 2) {
            if ($rank($reqTrack) !== $rank($curTrack)) {
                return $rank($reqTrack) > $rank($curTrack);
            }
        }

        return $reqStep > $curStep;
    }
}
