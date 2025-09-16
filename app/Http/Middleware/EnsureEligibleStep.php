<?php

namespace App\Http\Middleware;

use Closure;
use App\Helpers\RoleHelper;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureEligibleStep
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $u = $request->user();
        if (!$u) return $next($request); // biarkan guest ditangani oleh auth middleware

        // Ambil parameter dari route: /bab/{bab}/{track?}/{step}
        $bab   = (int) $request->route('bab');
        $track = $request->route('track');
        $track = ($track === 'null' || $track === '') ? null : ($track ? strtoupper($track) : null);
        $step  = (int) $request->route('step');

        if (RoleHelper::isAheadOfState($u->current_bab, $u->current_track, $u->current_step, $bab, $track, $step)) {
            // Redirect “halus” ke posisi user saat ini
            return redirect()->route('materi.show', [
                $u->current_bab,
                $u->current_track ?? null,
                $u->current_step,
            ])->with('status', 'Belum kebuka. Lanjutkan sesuai urutan ya ✨');
        }
        
        return $next($request);
    }
}
