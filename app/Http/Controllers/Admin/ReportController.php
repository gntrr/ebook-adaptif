<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HasilEvaluasi;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'ensure.admin']);
    }

    /**
     * Dashboard laporan dengan filter:
     * ?start=2025-09-01&end=2025-09-30&bab=2&track=A&user_id=123&status=pass|fail
     */
    public function index(Request $request)
    {
        [$start, $end] = $this->dateRange($request);

        $base = HasilEvaluasi::query()
            ->join('evaluasi', 'evaluasi.id', '=', 'hasil_evaluasi.evaluasi_id')
            ->join('materi', 'materi.id', '=', 'evaluasi.materi_id')
            ->join('users', 'users.id', '=', 'hasil_evaluasi.user_id')
            ->whereBetween('hasil_evaluasi.created_at', [$start, $end])
            ->when($request->filled('bab'), fn($q) => $q->where('materi.bab', (int)$request->integer('bab')))
            ->when($request->filled('track'), fn($q) => $q->where('materi.track', strtoupper($request->string('track')->toString())))
            ->when($request->filled('user_id'), fn($q) => $q->where('users.id', (int)$request->integer('user_id')))
            ->when($request->filled('status') && in_array($request->string('status')->toString(), ['pass','fail'], true),
                fn($q) => $q->where('hasil_evaluasi.lulus', $request->string('status')->toString() === 'pass'));

        // Rekap per bab
        $perBab = (clone $base)
            ->select([
                'materi.bab',
                DB::raw('COUNT(*)::int AS attempts'),
                DB::raw('ROUND(AVG(hasil_evaluasi.skor), 2) AS avg_skor'),
                DB::raw('SUM(CASE WHEN hasil_evaluasi.lulus THEN 1 ELSE 0 END)::int AS lulus_count'),
                DB::raw('ROUND( (SUM(CASE WHEN hasil_evaluasi.lulus THEN 1 ELSE 0 END)::numeric / NULLIF(COUNT(*),0)) * 100, 2) AS lulus_rate'),
            ])
            ->groupBy('materi.bab')
            ->orderBy('materi.bab')
            ->get();

        // Rekap per user
        $perUser = (clone $base)
            ->select([
                'users.id AS user_id',
                'users.name',
                DB::raw('COUNT(*)::int AS attempts'),
                DB::raw('ROUND(AVG(hasil_evaluasi.skor), 2) AS avg_skor'),
                DB::raw('ROUND( (SUM(CASE WHEN hasil_evaluasi.lulus THEN 1 ELSE 0 END)::numeric / NULLIF(COUNT(*),0)) * 100, 2) AS lulus_rate'),
            ])
            ->groupBy('users.id','users.name')
            ->orderBy('users.name')
            ->paginate(15)
            ->withQueryString();

        // Detail attempts
        $detail = (clone $base)
            ->select([
                'hasil_evaluasi.id',
                'users.name AS user_name',
                'materi.bab','materi.track','materi.step','materi.tipe','materi.judul',
                'hasil_evaluasi.skor','hasil_evaluasi.lulus','hasil_evaluasi.created_at',
            ])
            ->latest('hasil_evaluasi.created_at')
            ->paginate(15)
            ->withQueryString();

        // Dropdown helper: daftar user buat filter
        $userOptions = User::query()->select('id','name')->orderBy('name')->get();

        return view('admin.reports.index', [
            'filter'      => ['start' => $start->toDateString(), 'end' => $end->toDateString()],
            'perBab'      => $perBab,
            'perUser'     => $perUser,
            'detail'      => $detail,
            'userOptions' => $userOptions,
        ]);
    }

    /**
     * Export CSV:
     * /admin/reports/export?type=per_bab|per_user|detail&start=...&end=...&bab=...&track=...&user_id=...&status=pass|fail
     */
    public function exportCsv(Request $request): StreamedResponse
    {
        $type = $request->string('type')->toString();
        if (!in_array($type, ['per_bab','per_user','detail'], true)) {
            abort(400, 'type tidak valid');
        }

        [$start, $end] = $this->dateRange($request);

        $base = HasilEvaluasi::query()
            ->join('evaluasi', 'evaluasi.id', '=', 'hasil_evaluasi.evaluasi_id')
            ->join('materi', 'materi.id', '=', 'evaluasi.materi_id')
            ->join('users', 'users.id', '=', 'hasil_evaluasi.user_id')
            ->whereBetween('hasil_evaluasi.created_at', [$start, $end])
            ->when($request->filled('bab'), fn($q) => $q->where('materi.bab', (int)$request->integer('bab')))
            ->when($request->filled('track'), fn($q) => $q->where('materi.track', strtoupper($request->string('track')->toString())))
            ->when($request->filled('user_id'), fn($q) => $q->where('users.id', (int)$request->integer('user_id')))
            ->when($request->filled('status') && in_array($request->string('status')->toString(), ['pass','fail'], true),
                fn($q) => $q->where('hasil_evaluasi.lulus', $request->string('status')->toString() === 'pass'));

        $filename = "report_{$type}_" . now()->format('Ymd_His') . ".csv";

        return response()->streamDownload(function () use ($type, $base) {
            $out = fopen('php://output', 'w');

            if ($type === 'per_bab') {
                fputcsv($out, ['Bab','Attempts','Avg Skor','Lulus Count','Lulus Rate %']);
                $rows = (clone $base)
                    ->select([
                        'materi.bab',
                        DB::raw('COUNT(*)::int AS attempts'),
                        DB::raw('ROUND(AVG(hasil_evaluasi.skor), 2) AS avg_skor'),
                        DB::raw('SUM(CASE WHEN hasil_evaluasi.lulus THEN 1 ELSE 0 END)::int AS lulus_count'),
                        DB::raw('ROUND( (SUM(CASE WHEN hasil_evaluasi.lulus THEN 1 ELSE 0 END)::numeric / NULLIF(COUNT(*),0)) * 100, 2) AS lulus_rate'),
                    ])
                    ->groupBy('materi.bab')
                    ->orderBy('materi.bab')
                    ->cursor();

                foreach ($rows as $r) {
                    fputcsv($out, [$r->bab, $r->attempts, $r->avg_skor, $r->lulus_count, $r->lulus_rate]);
                }
            }

            if ($type === 'per_user') {
                fputcsv($out, ['User ID','Name','Attempts','Avg Skor','Lulus Rate %']);
                $rows = (clone $base)
                    ->select([
                        'users.id AS user_id',
                        'users.name',
                        DB::raw('COUNT(*)::int AS attempts'),
                        DB::raw('ROUND(AVG(hasil_evaluasi.skor), 2) AS avg_skor'),
                        DB::raw('ROUND( (SUM(CASE WHEN hasil_evaluasi.lulus THEN 1 ELSE 0 END)::numeric / NULLIF(COUNT(*),0)) * 100, 2) AS lulus_rate'),
                    ])
                    ->groupBy('users.id','users.name')
                    ->orderBy('users.name')
                    ->cursor();

                foreach ($rows as $r) {
                    fputcsv($out, [$r->user_id, $r->name, $r->attempts, $r->avg_skor, $r->lulus_rate]);
                }
            }

            if ($type === 'detail') {
                fputcsv($out, ['Attempt ID','User','Bab','Track','Step','Tipe','Judul','Skor','Lulus','Waktu']);
                $rows = (clone $base)
                    ->select([
                        'hasil_evaluasi.id',
                        'users.name AS user_name',
                        'materi.bab','materi.track','materi.step','materi.tipe','materi.judul',
                        'hasil_evaluasi.skor','hasil_evaluasi.lulus','hasil_evaluasi.created_at',
                    ])
                    ->orderBy('hasil_evaluasi.created_at')
                    ->cursor();

                foreach ($rows as $r) {
                    fputcsv($out, [
                        $r->id,
                        $r->user_name,
                        $r->bab,
                        $r->track,
                        $r->step,
                        $r->tipe,
                        $r->judul,
                        $r->skor,
                        $r->lulus ? '1' : '0',
                        $r->created_at,
                    ]);
                }
            }

            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    /* =========================
       Helpers
       ========================= */

    /** Ambil rentang tanggal; default 30 hari terakhir. */
    private function dateRange(Request $request): array
    {
        $end   = $request->filled('end')
            ? CarbonImmutable::parse($request->string('end')->toString())->endOfDay()
            : CarbonImmutable::now();
        $start = $request->filled('start')
            ? CarbonImmutable::parse($request->string('start')->toString())->startOfDay()
            : $end->subDays(29)->startOfDay();

        return [$start, $end];
    }
}
