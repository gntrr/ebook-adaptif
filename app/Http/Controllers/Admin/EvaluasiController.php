<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Evaluasi;
use App\Models\Materi;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class EvaluasiController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'ensure.admin']);
    }

    public function index(Request $request)
    {
        $query = Evaluasi::query()->with('materi');

        if ($request->filled('q')) {
            $term = $request->string('q')->toString();
            $query->where(function ($q) use ($term) {
                $q->where('pertanyaan', 'like', "%{$term}%")
                  ->orWhereHas('materi', fn ($m) => $m->where('judul', 'like', "%{$term}%"));
            });
        }

        if ($request->filled('bab')) {
            $query->whereHas('materi', fn ($m) => $m->where('bab', (int) $request->integer('bab')));
        }

        if ($request->filled('track')) {
            $track = $request->string('track')->toString();
            if ($track === 'DEFAULT') {
                $query->whereHas('materi', fn ($m) => $m->whereNull('track'));
            } elseif (in_array(strtoupper($track), ['A','B'], true)) {
                $query->whereHas('materi', fn ($m) => $m->where('track', strtoupper($track)));
            }
        }

        if ($request->filled('step')) {
            $query->whereHas('materi', fn ($m) => $m->where('step', (int) $request->integer('step')));
        }

        if ($request->filled('tipe')) {
            $tipe = $request->string('tipe')->toString();
            $query->whereHas('materi', fn ($m) => $m->where('tipe', $tipe));
        }

        $evaluations = $query->orderByDesc('id')->get();

        $previewEvaluation = $evaluations->first();

        return view('admin.evaluasi.index', [
            'evaluations'       => $evaluations,
            'filters'           => $request->only(['q','bab','track','step','tipe']),
            'previewEvaluation' => $previewEvaluation,
            'previewOptions'    => $previewEvaluation?->opsi ?? [],
        ]);
    }

    public function create()
    {
        return view('admin.evaluasi.create', [
            'materiOptions' => $this->materiOptions(),
            'evaluation'    => new Evaluasi(),
            'config'        => ['palette' => [], 'solution' => [], 'labels' => []],
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        Evaluasi::create($data);

        return redirect()->route('admin.evaluasi.index')->with('status', 'Soal scratch dibuat.');
    }

    public function edit(Evaluasi $evaluasi)
    {
        return view('admin.evaluasi.edit', [
            'evaluation'    => $evaluasi,
            'materiOptions' => $this->materiOptions(),
            'config'        => $evaluasi->opsi ?? [],
        ]);
    }

    public function update(Request $request, Evaluasi $evaluasi)
    {
        $data = $this->validated($request);
        $evaluasi->update($data);

        return redirect()->route('admin.evaluasi.index')->with('status', 'Soal scratch diperbarui.');
    }

    public function destroy(Evaluasi $evaluasi)
    {
        $evaluasi->delete();

        return redirect()->route('admin.evaluasi.index')->with('status', 'Soal scratch dihapus.');
    }

    private function materiOptions()
    {
        return Materi::query()
            ->whereIn('tipe', ['evaluasi','evaluasi_bab'])
            ->ordered()
            ->get();
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'materi_id' => ['required','exists:materi,id'],
            'pertanyaan'=> ['required','string'],
            'palette'   => ['required','string'],
            'solution'  => ['required','string'],
            'labels'    => ['nullable','string'],
            'hint'      => ['nullable','string'],
            'bobot'     => ['nullable','integer','min:1','max:1000'],
        ], [], [
            'materi_id' => 'materi',
            'palette'   => 'daftar blok',
            'solution'  => 'urutan benar',
        ]);

        $palette = $this->parseList($data['palette']);
        $solution = $this->parseList($data['solution']);

        if (empty($palette) || empty($solution)) {
            throw ValidationException::withMessages([
                'palette' => 'Daftar blok dan urutan benar tidak boleh kosong.',
            ]);
        }

        $labels = $this->parseLabels($data['labels'] ?? '', $palette);

        $config = [
            'type'        => 'scratch',
            'palette'     => $palette,
            'solution'    => $solution,
            'labels'      => $labels,
            'distractors' => array_values(array_diff($palette, $solution)),
        ];

        if (! empty($data['hint'])) {
            $config['hint'] = $data['hint'];
        }

        return [
            'materi_id'     => $data['materi_id'],
            'pertanyaan'    => $data['pertanyaan'],
            'jawaban_benar' => 'scratch',
            'opsi'          => $config,
            'bobot'         => $data['bobot'] ?? 100,
        ];
    }    private function parseList(string $value): array
    {
        return collect(preg_split('/[\r\n,]+/', $value))
            ->map(fn ($item) => trim($item))
            ->filter()
            ->values()
            ->toArray();
    }

    private function parseLabels(string $value, array $palette): array
    {
        $labels = [];

        collect(preg_split('/[\r\n]+/', $value))
            ->map(fn ($line) => trim($line))
            ->filter()
            ->each(function ($line) use (&$labels) {
                if (str_contains($line, '|')) {
                    [$key, $label] = array_map('trim', explode('|', $line, 2));
                } elseif (str_contains($line, '=')) {
                    [$key, $label] = array_map('trim', explode('=', $line, 2));
                } else {
                    $key = trim($line);
                    $label = Str::headline(str_replace('_', ' ', $key));
                }

                if ($key !== '') {
                    $labels[$key] = $label;
                }
            });

        foreach ($palette as $token) {
            if (! isset($labels[$token])) {
                $labels[$token] = Str::headline(str_replace('_', ' ', $token));
            }
        }

        return $labels;
    }
}






