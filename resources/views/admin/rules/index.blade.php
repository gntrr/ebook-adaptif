<x-app-layout>
    <div class="dashboard-main-wrapper">
        @include('layouts.topnav')

        <div class="dashboard-body">
            <div class="container-fluid">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-16 mb-24">
                    <div>
                        <h1 class="h3 mb-4">Aturan Adaptif</h1>
                        <p class="text-gray-400 mb-0">Kelola threshold skor dan aturan loncatan step untuk jalur belajar adaptif.</p>
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
                                <p class="text-gray-400">Setiap perubahan akan memengaruhi rekomendasi bab dan remedial. Simpan aturan baru setelah ditinjau.</p>
                                <form action="#" onsubmit="return false;" class="row g-3 mt-2">
                                    <div class="col-sm-4">
                                        <label class="form-label text-13 text-uppercase text-gray-400">Kategori A</label>
                                        <div class="input-group">
                                            <input type="number" class="form-control bg-main-50 border-0" min="0" max="100" value="90">
                                            <span class="input-group-text bg-main-600 text-white border-0">>=</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="form-label text-13 text-uppercase text-gray-400">Kategori B</label>
                                        <div class="input-group">
                                            <input type="number" class="form-control bg-main-50 border-0" min="0" max="100" value="60">
                                            <span class="input-group-text bg-main-600 text-white border-0">60-89</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="form-label text-13 text-uppercase text-gray-400">Remedial</label>
                                        <div class="input-group">
                                            <input type="number" class="form-control bg-danger-50 border-0" min="0" max="100" value="59">
                                            <span class="input-group-text bg-danger-600 text-white border-0">&lt; 60</span>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label text-13 text-uppercase text-gray-400">Catatan</label>
                                        <textarea class="form-control bg-main-50 border-0" rows="3" placeholder="Tambahkan catatan perubahan untuk tim QA."></textarea>
                                    </div>
                                    <div class="col-12 d-flex justify-content-end gap-12">
                                        <button type="button" class="btn btn-outline-secondary rounded-pill">Reset</button>
                                        <button type="button" class="btn btn-main rounded-pill">Simpan Draft</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-xxl-6">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-16">
                                    <h5 class="mb-0">Rule Khusus Bab 1</h5>
                                    <span class="text-13 text-gray-300">Allow skip Step 3 ? Step 4</span>
                                </div>
                                <p class="text-gray-400">Aktifkan opsi ini jika peserta diperbolehkan melompati step remedial pada bab pengantar.</p>
                                <div class="border rounded-12 p-16 bg-main-50">
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" role="switch" id="allowSkipToggle" checked>
                                        <label class="form-check-label" for="allowSkipToggle">Izinkan loncat Step 3 ke Step 4 (Bab 1)</label>
                                    </div>
                                    <textarea class="form-control bg-white border-0" rows="4" placeholder="Contoh skenario: Peserta dengan skor &gt;= 80 pada pre-test">Peserta dengan skor uji diagnostik = 80 langsung diarahkan ke step praktik.</textarea>
                                </div>
                                <div class="alert alert-warning mt-20" role="alert">
                                    <i class="ph ph-warning me-2"></i>
                                    Integrasi penyimpanan aturan belum dihubungkan. Silakan koordinasikan dengan tim backend sebelum go-live.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-16">
                            <h5 class="mb-0">Log Perubahan Terakhir</h5>
                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill">Export CSV</button>
                        </div>
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
                                        <td>10 Sep 2025 09:30</td>
                                        <td>Salsa Putri</td>
                                        <td>Ubah threshold A menjadi = 90</td>
                                        <td>Sinkron dengan standar kurikulum baru.</td>
                                    </tr>
                                    <tr>
                                        <td>02 Sep 2025 14:12</td>
                                        <td>Adit Maulana</td>
                                        <td>Aktifkan skip Step 3 ? Step 4</td>
                                        <td>UAT menunjukkan tingkat kelulusan tinggi.</td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="text-center text-gray-400">Entri log lainnya akan tampil setelah integrasi database.</td>
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
