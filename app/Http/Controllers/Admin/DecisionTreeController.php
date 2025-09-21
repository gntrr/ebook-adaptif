<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Materi;

class DecisionTreeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'ensure.admin']);
    }

    public function index()
    {
        $groups = Materi::query()
            ->ordered()
            ->get()
            ->groupBy(fn ($item) => $item->bab . '|' . ($item->track ?? 'default'));

        $trees = $groups->map(function ($items) {
            $first = $items->first();
            $trackLabel = $first->track ?? 'Default';

            return [
                'bab'     => $first->bab,
                'track'   => $first->track,
                'summary' => sprintf(
                    'Urutan slot yang tersedia pada bab %d%s.',
                    $first->bab,
                    $trackLabel === 'Default' ? '' : " (Track {$trackLabel})"
                ),
                'notes'   => 'Pohon ini dibentuk secara otomatis dari data materi. Update materi di CMS untuk mengubah cabang yang tampil.',
                'nodes'   => $items->map(function (Materi $materi) {
                    $tipeLabel = ucwords(str_replace('_', ' ', $materi->tipe));

                    return [
                        'label'  => sprintf('Step %d • %s', $materi->step, $tipeLabel),
                        'action' => $materi->judul,
                    ];
                })->values(),
            ];
        })->values();

        return view('admin.decision-tree.index', [
            'trees' => $trees,
        ]);
    }
}
