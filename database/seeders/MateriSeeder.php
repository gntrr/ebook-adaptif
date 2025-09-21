<?php

namespace Database\Seeders;

use App\Models\Materi;
use Illuminate\Database\Seeder;

class MateriSeeder extends Seeder
{
    public function run(): void
    {
        $slots = [
            [
                'bab'   => 1,
                'track' => null,
                'step'  => 1,
                'tipe'  => 'materi',
                'judul' => 'Pendahuluan: Perkenalan Scratch',
                'konten_type' => 'html',
                'konten_image_path' => null,
                'konten' => <<<'HTML'
<div class="mb-24">
    <h2 class="fw-semibold mb-12">Apa itu Scratch?</h2>
    <p>Scratch adalah platform visual yang membantu siswa memahami logika pemrograman dengan cara menyusun blok kode berwarna. Pada bagian pendahuluan ini kamu akan mengenal antarmuka Scratch, cara memilih sprite, serta bagaimana blok-blok disusun untuk membentuk sebuah aksi.</p>
</div>
<div class="mb-24">
    <h3 class="fw-semibold mb-8">Tujuan Pembelajaran</h3>
    <ul class="list-unstyled d-flex flex-column gap-6 ps-3">
        <li>Memahami area kerja Scratch (stage, sprites, dan block palette).</li>
        <li>Menjelaskan fungsi tombol <em>Green Flag</em> dan <em>Stop</em>.</li>
        <li>Mengenal tiga kategori blok utama: Motion, Looks, dan Control.</li>
    </ul>
</div>
<div class="mb-0">
    <h3 class="fw-semibold mb-8">Persiapan Sebelum Mulai</h3>
    <ol class="ps-3 d-flex flex-column gap-4">
        <li>Buka <a href="https://scratch.mit.edu" target="_blank" rel="noopener">https://scratch.mit.edu</a> dan masuk menggunakan akun siswa.</li>
        <li>Pilih menu <strong>Create</strong> untuk membuka proyek kosong.</li>
        <li>Kenali area blok di sisi kiri dan area script di tengah. Kita akan menyusun blok di area script tersebut.</li>
    </ol>
</div>
HTML,
            ],
            [
                'bab'   => 1,
                'track' => null,
                'step'  => 2,
                'tipe'  => 'materi',
                'judul' => 'Materi 1: Membuat Sprite Bergerak',
                'konten_type' => 'html',
                'konten_image_path' => null,
                'konten' => <<<'HTML'
<h2 class="fw-semibold mb-12">Langkah Menyusun Sprite Bergerak</h2>
<p>Gerakan paling dasar di Scratch dibuat dengan memadukan blok <em>Events</em> dan <em>Motion</em>. Susun kedua blok berikut agar sprite bergerak 10 langkah ketika bendera hijau ditekan:</p>
<ol class="ps-3 d-flex flex-column gap-4">
    <li><strong>When Green Flag Clicked</strong> (blok awal dari kategori Events).</li>
    <li><strong>Move 10 Steps</strong> (blok gerakan dari kategori Motion).</li>
</ol>
<p class="mb-0">Pastikan blok <em>When Green Flag Clicked</em> berada di paling atas sehingga Scratch tahu kapan skrip dijalankan.</p>
HTML,
            ],
            [
                'bab'   => 1,
                'track' => null,
                'step'  => 2,
                'tipe'  => 'evaluasi',
                'judul' => 'Latihan 1: Menyusun Blok Gerak Dasar',
                'konten_type' => 'html',
                'konten_image_path' => null,
                'konten' => <<<'HTML'
<p>Susun kembali blok-blok yang sudah kamu pelajari agar sprite dapat bergerak maju. Jawab pertanyaan berikut untuk memastikan pemahamanmu sebelum lanjut ke materi berikutnya.</p>
HTML,
            ],
            [
                'bab'   => 1,
                'track' => null,
                'step'  => 3,
                'tipe'  => 'materi',
                'judul' => 'Materi 2: Membuat Sprite Mengeluarkan Teks',
                'konten_type' => 'html',
                'konten_image_path' => null,
                'konten' => <<<'HTML'
<h2 class="fw-semibold mb-12">Membuat Sprite Berbicara</h2>
<p>Sprite dapat berinteraksi dengan pengguna melalui balon percakapan. Gunakan kombinasi blok berikut:</p>
<ol class="ps-3 d-flex flex-column gap-4">
    <li><strong>When Green Flag Clicked</strong> - memulai skrip.</li>
    <li><strong>Say "Hello!" for 2 seconds</strong> - membuat sprite menampilkan teks selama dua detik.</li>
</ol>
<p class="mb-0">Ubah teks pada blok <em>Say</em> agar sesuai kebutuhan, misalnya "Halo, mari kita belajar Scratch!".</p>
HTML,
            ],
            [
                'bab'   => 1,
                'track' => null,
                'step'  => 3,
                'tipe'  => 'evaluasi',
                'judul' => 'Latihan 2: Membuat Sprite Berbicara',
                'konten_type' => 'html',
                'konten_image_path' => null,
                'konten' => <<<'HTML'
<p>Evaluasi berikut berfokus pada kemampuan membuat sprite menampilkan pesan. Susun blok dengan benar sebelum berpindah ke materi berikutnya.</p>
HTML,
            ],
            [
                'bab'   => 1,
                'track' => null,
                'step'  => 4,
                'tipe'  => 'materi',
                'judul' => 'Materi 3: Membuat Sprite Berganti Kostum',
                'konten_type' => 'image',
                'konten_image_path' => 'edmate/assets/images/thumbs/gretting-thumb.png',
                'konten' => null,
            ],
            [
                'bab'   => 1,
                'track' => null,
                'step'  => 4,
                'tipe'  => 'evaluasi',
                'judul' => 'Latihan 3: Animasi Kostum',
                'konten_type' => 'html',
                'konten_image_path' => null,
                'konten' => <<<'HTML'
<p>Jawab pertanyaan berikut untuk memastikan kamu memahami cara membuat animasi sederhana dengan mengganti kostum.</p>
HTML,
            ],
            [
                'bab'   => 1,
                'track' => null,
                'step'  => 5,
                'tipe'  => 'evaluasi_bab',
                'judul' => 'Evaluasi Bab 1: Dasar-dasar Scratch',
                'konten_type' => 'html',
                'konten_image_path' => null,
                'konten' => <<<'HTML'
<p>Evaluasi akhir ini mencakup materi gerak dasar, percakapan sprite, dan animasi kostum. Kerjakan setiap soal dengan cermat untuk mengukur pemahamanmu sebelum naik ke bab berikutnya.</p>
HTML,
            ],
        ];

        foreach ($slots as $attributes) {
            Materi::updateOrCreate(
                [
                    'bab'   => $attributes['bab'],
                    'track' => $attributes['track'],
                    'step'  => $attributes['step'],
                    'tipe'  => $attributes['tipe'],
                ],
                [
                    'judul'             => $attributes['judul'],
                    'konten'            => $attributes['konten'],
                    'konten_type'       => $attributes['konten_type'],
                    'konten_image_path' => $attributes['konten_image_path'],
                ]
            );
        }
    }
}
