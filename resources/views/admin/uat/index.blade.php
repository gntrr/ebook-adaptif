<x-app-layout>
    <div class="dashboard-main-wrapper">
        @include('layouts.topnav')

        <div class="dashboard-body">
            <div class="container-fluid">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-16 mb-24">
                    <div>
                        <h1 class="h3 mb-4">User Acceptance Testing</h1>
                        <p class="text-gray-400 mb-0">Rekap hasil kuisioner Likert dan catat umpan balik pengguna untuk iterasi produk.</p>
                    </div>
                    <button type="button" class="btn btn-outline-primary rounded-pill"><i class="ph ph-download-simple me-2"></i>Export Rekap</button>
                </div>

                @include('admin.partials.flash')

                @php
                    $questions = [
                        'Antarmuka aplikasi mudah dipahami.',
                        'Navigasi antar bab dan step terasa intuitif.',
                        'Materi yang disajikan relevan dengan kebutuhan saya.',
                        'Latihan/kuis membantu saya memahami materi.',
                        'Penjelasan umpan balik setelah kuis sudah jelas.',
                        'Sistem adaptif memberikan level yang sesuai dengan kemampuan.',
                        'Waktu yang dibutuhkan untuk menyelesaikan satu bab terasa wajar.',
                        'Tampilan visual dan ilustrasi menarik.',
                        'Saya merasa percaya diri setelah mengikuti materi.',
                        'Saya bersedia merekomendasikan aplikasi ini ke teman.'
                    ];
                    $scaleLabels = [
                        1 => 'Sangat Tidak Setuju',
                        2 => 'Tidak Setuju',
                        3 => 'Netral',
                        4 => 'Setuju',
                        5 => 'Sangat Setuju',
                    ];
                @endphp

                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="mb-16">Form Kuisioner</h5>
                        <form action="#" onsubmit="return false;">
                            <div class="table-responsive-lg">
                                <table class="table align-middle table-bordered">
                                    <thead class="bg-main-50">
                                        <tr>
                                            <th class="w-40">Pernyataan</th>
                                            @foreach ($scaleLabels as $value => $label)
                                                <th class="text-center">{{ $value }}<br><span class="text-11 text-gray-400">{{ $label }}</span></th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($questions as $index => $text)
                                            <tr>
                                                <td class="fw-medium">{{ $index + 1 }}. {{ $text }}</td>
                                                @foreach ($scaleLabels as $value => $label)
                                                    <td class="text-center">
                                                        <input type="radio" name="answers[{{ $index }}]" class="form-check-input" value="{{ $value }}">
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-13 text-uppercase text-gray-400">Masukan tambahan</label>
                                <textarea class="form-control bg-main-50 border-0" rows="4" placeholder="Catat komentar kualitatif dari peserta."></textarea>
                            </div>
                            <div class="d-flex justify-content-end gap-12">
                                <button type="button" class="btn btn-outline-secondary rounded-pill">Reset</button>
                                <button type="button" class="btn btn-main rounded-pill">Simpan Umpan Balik</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="row gy-4">
                    <div class="col-xl-6">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="mb-16">Rekap Persentase Penerimaan</h5>
                                <div class="table-responsive">
                                    <table class="table table-sm align-middle mb-0">
                                        <thead>
                                            <tr>
                                                <th>Pernyataan</th>
                                                <th class="text-end">Rata-rata</th>
                                                <th class="text-end">% Setuju</th>
                                                <th class="text-end">% Tidak Setuju</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($questions as $index => $text)
                                                <tr>
                                                    <td class="text-truncate" style="max-width: 220px;" title="{{ $text }}">{{ $index + 1 }}. {{ $text }}</td>
                                                    <td class="text-end">4.2</td>
                                                    <td class="text-end text-success">82%</td>
                                                    <td class="text-end text-danger">6%</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="alert alert-info mt-3" role="alert">
                                    <i class="ph ph-info me-2"></i>
                                    Data di atas merupakan contoh tampilan. Hubungkan dengan agregasi hasil kuisioner untuk angka aktual.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="mb-16">Insight Cepat</h5>
                                <ul class="list-unstyled mb-0">
                                    <li class="d-flex gap-12 align-items-start mb-12">
                                        <span class="text-success"><i class="ph-fill ph-arrow-up"></i></span>
                                        <div>
                                            <span class="fw-semibold">Pengalaman belajar dianggap intuitif</span>
                                            <p class="text-gray-400 mb-0">92% peserta menyatakan navigasi antar step mudah dipahami.</p>
                                        </div>
                                    </li>
                                    <li class="d-flex gap-12 align-items-start mb-12">
                                        <span class="text-warning"><i class="ph-fill ph-warning"></i></span>
                                        <div>
                                            <span class="fw-semibold">Perlu perbaikan pada penjelasan remedial</span>
                                            <p class="text-gray-400 mb-0">Catatan kualitatif menunjukkan kebutuhan penjelasan lebih rinci setelah kuis.</p>
                                        </div>
                                    </li>
                                    <li class="d-flex gap-12 align-items-start">
                                        <span class="text-purple"><i class="ph-fill ph-lightbulb"></i></span>
                                        <div>
                                            <span class="fw-semibold">Ide pengembangan</span>
                                            <p class="text-gray-400 mb-0">Tambahkan badge pencapaian untuk meningkatkan motivasi setelah lulus evaluasi.</p>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
