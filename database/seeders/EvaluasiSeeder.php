<?php

namespace Database\Seeders;

use App\Models\Evaluasi;
use App\Models\Materi;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EvaluasiSeeder extends Seeder
{
    public function run(): void
    {
        $materiTitles = [
            'latihan-gerak'  => 'Latihan 1: Menyusun Blok Gerak Dasar',
            'latihan-teks'   => 'Latihan 2: Membuat Sprite Berbicara',
            'latihan-kostum' => 'Latihan 3: Animasi Kostum',
            'evaluasi-bab'   => 'Evaluasi Bab 1: Dasar-dasar Scratch',
        ];

        $materiMap = Materi::whereIn('judul', array_values($materiTitles))
            ->get()
            ->keyBy('judul');

        $tasks = [
            'latihan-gerak' => [
                [
                    'pertanyaan' => 'Susun blok agar sprite bergerak 10 langkah ketika bendera hijau ditekan.',
                    'palette'    => ['when_green_flag_clicked','move_10_steps','say_hello','wait_1_second'],
                    'solution'   => ['when_green_flag_clicked','move_10_steps'],
                    'hint'       => 'Mulai dengan blok event, lanjutkan dengan blok gerakan.',
                    'labels'     => [
                        'when_green_flag_clicked' => 'When Green Flag Clicked',
                        'move_10_steps'           => 'Move 10 Steps',
                        'say_hello'               => 'Say "Hello!" for 2 seconds',
                        'wait_1_second'           => 'Wait 1 Second',
                    ],
                ],
                [
                    'pertanyaan' => 'Tambahkan jeda setelah sprite bergerak agar pergerakan terlihat jelas.',
                    'palette'    => ['when_green_flag_clicked','move_10_steps','wait_1_second'],
                    'solution'   => ['when_green_flag_clicked','move_10_steps','wait_1_second'],
                    'hint'       => 'Letakkan blok jeda setelah perintah gerakan.',
                ],
            ],
            'latihan-teks' => [
                [
                    'pertanyaan' => 'Susun blok agar sprite menyapa penonton.',
                    'palette'    => ['when_green_flag_clicked','say_hello','move_10_steps','wait_1_second'],
                    'solution'   => ['when_green_flag_clicked','say_hello'],
                    'hint'       => 'Setelah event, gunakan blok percakapan.',
                    'labels'     => [
                        'say_hello' => 'Say "Hello!" for 2 seconds',
                    ],
                ],
                [
                    'pertanyaan' => 'Buat sprite menyapa kemudian bergerak maju.',
                    'palette'    => ['when_green_flag_clicked','say_hello','move_10_steps','wait_1_second'],
                    'solution'   => ['when_green_flag_clicked','say_hello','move_10_steps'],
                ],
            ],
            'latihan-kostum' => [
                [
                    'pertanyaan' => 'Buat sprite berganti kostum terus menerus ketika bendera ditekan.',
                    'palette'    => ['when_green_flag_clicked','forever','next_costume','wait_1_second'],
                    'solution'   => ['when_green_flag_clicked','forever','next_costume'],
                    'hint'       => 'Letakkan blok kostum di dalam perulangan.',
                    'labels'     => [
                        'forever'       => 'Forever',
                        'next_costume'  => 'Next Costume',
                    ],
                ],
                [
                    'pertanyaan' => 'Tambahkan jeda pendek agar pergantian kostum tidak terlalu cepat.',
                    'palette'    => ['when_green_flag_clicked','forever','next_costume','wait_1_second'],
                    'solution'   => ['when_green_flag_clicked','forever','next_costume','wait_1_second'],
                ],
            ],
            'evaluasi-bab' => [
                [
                    'pertanyaan' => 'Susun blok agar sprite bergerak lalu menyapa.',
                    'palette'    => ['when_green_flag_clicked','move_10_steps','say_hello','wait_1_second','broadcast_cheer'],
                    'solution'   => ['when_green_flag_clicked','move_10_steps','say_hello'],
                ],
                [
                    'pertanyaan' => 'Buat sprite melakukan animasi sederhana setelah menyapa.',
                    'palette'    => ['when_green_flag_clicked','move_10_steps','say_hello','forever','next_costume','wait_1_second'],
                    'solution'   => ['when_green_flag_clicked','move_10_steps','say_hello','forever','next_costume','wait_1_second'],
                ],
                [
                    'pertanyaan' => 'Susun blok agar sprite menyapa dua kali dengan jeda.',
                    'palette'    => ['when_green_flag_clicked','say_hello','wait_1_second','say_bye','move_10_steps'],
                    'solution'   => ['when_green_flag_clicked','say_hello','wait_1_second','say_bye'],
                    'labels'     => [
                        'say_bye' => 'Say "Bye!" for 2 seconds',
                    ],
                ],
            ],
        ];

        foreach ($tasks as $key => $questions) {
            $judul = $materiTitles[$key] ?? null;
            if (! $judul || ! $materiMap->has($judul)) {
                continue;
            }

            $materi = $materiMap[$judul];

            foreach ($questions as $question) {
                $palette = $this->normaliseList($question['palette'] ?? []);
                $solution = $this->normaliseList($question['solution'] ?? []);

                if (empty($palette) || empty($solution)) {
                    continue;
                }

                $labels = $question['labels'] ?? [];
                foreach ($palette as $token) {
                    if (! isset($labels[$token])) {
                        $labels[$token] = Str::headline(str_replace('_', ' ', $token));
                    }
                }

                $config = [
                    'type'        => 'scratch',
                    'palette'     => $palette,
                    'solution'    => $solution,
                    'labels'      => $labels,
                    'distractors' => array_values(array_diff($palette, $solution)),
                ];

                if (! empty($question['hint'] ?? '')) {
                    $config['hint'] = $question['hint'];
                }

                Evaluasi::updateOrCreate(
                    [
                        'materi_id'  => $materi->id,
                        'pertanyaan' => $question['pertanyaan'],
                    ],
                    [
                        'opsi'          => $config,
                        'jawaban_benar' => 'scratch',
                        'bobot'         => $question['bobot'] ?? 100,
                    ]
                );
            }
        }
    }

    private function normaliseList(array $items): array
    {
        return collect($items)
            ->map(fn ($item) => trim((string) $item))
            ->filter()
            ->values()
            ->toArray();
    }
}
