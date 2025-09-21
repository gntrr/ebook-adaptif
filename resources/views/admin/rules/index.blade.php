<x-app-layout>
    <div class="dashboard-main-wrapper">
        @include('layouts.topnav')

        <div class="dashboard-body">
            <div class="container-fluid">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-16 mb-24">
                    <div>
                        <h1 class="h3 mb-4">Aturan Adaptif</h1>
                        <p class="text-gray-400 mb-0">Kelola threshold skor dan aturan loncatan step berbasis kebijakan pembelajaran.</p>
                    </div>
                    <div class="d-flex gap-8">
                        <button type="button" class="btn btn-outline-secondary rounded-pill">Reset</button>
                        <button type="button" class="btn btn-main rounded-pill">Simpan Draft</button>
                    </div>
                </div>

                @include('admin.partials.flash')

                <div class="row gy-4">
                    <div class="col-xxl-6">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-16">
                                    <h5 class="mb-0">Threshold Skor</h5>
                                    <span class="badge bg-main-100 text-main-600">Draft</span>
                                </div>
                                <p class="text-gray-400">Aturan di bawah bersifat dokumentasi. Sesuaikan dengan kebijakan terbaru sebelum go-live.</p>
                                <div class="row g-3">
                                    <div class="col-sm-4">
                                        <label class="form-label text-13 text-uppercase text-gray-400">Kategori A</label>
                                        <div class="input-group">
                                            <input type="number" class="form-control bg-main-50 border-0" value="90" min="0" max="100">
                                            <span class="input-group-text bg-main-600 text-white border-0">=</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="form-label text-13 text-uppercase text-gray-400">Kategori B</label>
                                        <div class="input-group">
                                            <input type="number" class="form-control bg-main-50 border-0" value="60" min="0" max="100">
                                            <span class="input-group-text bg-main-600 text-white border-0">60–89</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="form-label text-13 text-uppercase text-gray-400">Remedial</label>
                                        <div class="input-group">
                                            <input type="number" class="form-control bg-danger-50 border-0" value="59" min="0" max="100">
                                            <span class="input-group-text bg-danger-600 text-white border-0">&lt; 60</span>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label text-13 text-uppercase text-gray-400">Catatan</label>
                                        <textarea class="form-control bg-main-50 border-0" rows="3" placeholder="Catat keputusan komite kurikulum."></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-6">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="mb-16">Rule Khusus Bab 1</h5>
                                <p class="text-gray-400">Dokumentasikan kebijakan loncatan step atau pengecualian lain.</p>
                                <div class="border rounded-12 p-20 bg-main-50">
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" checked disabled>
                                        <label class="form-check-label">Izinkan loncat Step 3 ? Step 4 untuk murid remedial yang lulus dua kali berturut.</label>
                                    </div>
                                    <textarea class="form-control bg-white border-0" rows="4" placeholder="Contoh alasan perubahan">Aktifkan opsi ini saat uji coba adaptif menunjukkan kesiapan siswa lanjut ke materi praktik.</textarea>
                                </div>
                                <div class="alert alert-warning mt-20" role="alert">
                                    <i class="ph ph-warning me-2"></i>
                                    Fitur ini bersifat catatan internal. Implementasi aktual perlu koordinasi dengan tim backend/algoritma.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-body">
                        <h5 class="mb-16">Log Perubahan</h5>
                        <div class="table-responsive">
                            <table class="table table-sm align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Admin</th>
                                        <th>Perubahan</th>
                                        <th>Catatan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>12 Sep 2025</td>
                                        <td>Salsa Putri</td>
                                        <td>Perbarui threshold kategori A menjadi = 90</td>
                                        <td>Sinkron dengan standar kurikulum nasional.</td>
                                    </tr>
                                    <tr>
                                        <td>05 Sep 2025</td>
                                        <td>Aditya M.</td>
                                        <td>Aktifkan loncat Step 3 ? Step 4</td>
                                        <td>Percobaan adaptif menunjukkan tingkat kelulusan tinggi.</td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="text-center text-gray-400">Integrasikan dengan catatan resmi jika fitur approval disiapkan.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
