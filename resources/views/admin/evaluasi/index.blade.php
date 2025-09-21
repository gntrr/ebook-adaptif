<x-app-layout>
    <div class="dashboard-main-wrapper">
        @include('layouts.topnav')

        <div class="dashboard-body">
            <div class="container-fluid">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-16 mb-24">
                    <div>
                        <h1 class="h3 mb-4">Bank Soal Scratch</h1>
                        <p class="text-gray-400 mb-0">Daftar ini tersinkron otomatis dengan data materi evaluasi. Gunakan tombol tambah untuk membuat soal drag-and-drop baru.</p>
                    </div>
                    <a href="{{ route('admin.evaluasi.create') }}" class="btn btn-main rounded-pill"><i class="ph ph-plus me-2"></i>Tambah Soal</a>
                </div>

                @include('admin.partials.flash')

                @php($trackFilter = $filters['track'] ?? '')
                @php($tipeFilter = $filters['tipe'] ?? '')

                <div class="card mb-4">
                    <div class="card-body">
                        <form method="GET" class="row g-3 align-items-end">
                            <div class="col-md-3">
                                <label class="form-label text-13 text-uppercase text-gray-400">Cari Pertanyaan</label>
                                <input type="text" name="q" value="{{ $filters['q'] ?? '' }}" class="form-control bg-main-50 border-0" placeholder="Ketik kata kunci">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label text-13 text-uppercase text-gray-400">Bab</label>
                                <input type="number" name="bab" value="{{ $filters['bab'] ?? '' }}" class="form-control bg-main-50 border-0" min="1" max="99">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label text-13 text-uppercase text-gray-400">Step</label>
                                <input type="number" name="step" value="{{ $filters['step'] ?? '' }}" class="form-control bg-main-50 border-0" min="1" max="5">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label text-13 text-uppercase text-gray-400">Track</label>
                                <select name="track" class="form-select bg-main-50 border-0">
                                    <option value="">Semua</option>
                                    <option value="DEFAULT" @selected($trackFilter === 'DEFAULT')>Jalur Utama</option>
                                    <option value="A" @selected($trackFilter === 'A')>Track A</option>
                                    <option value="B" @selected($trackFilter === 'B')>Track B</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label text-13 text-uppercase text-gray-400">Tipe Materi</label>
                                <select name="tipe" class="form-select bg-main-50 border-0">
                                    <option value="">Semua</option>
                                    <option value="evaluasi" @selected($tipeFilter === 'evaluasi')>Evaluasi Step</option>
                                    <option value="evaluasi_bab" @selected($tipeFilter === 'evaluasi_bab')>Evaluasi Bab</option>
                                </select>
                            </div>
                            <div class="col-md-3 d-flex gap-10">
                                <button type="submit" class="btn btn-main rounded-pill">Terapkan Filter</button>
                                <a href="{{ route('admin.evaluasi.index') }}" class="btn btn-outline-gray rounded-pill">Reset</a>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="row gy-4">
                    <div class="col-xxl-7">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-16">
                                    <h5 class="mb-0">Daftar Soal</h5>
                                    <span class="text-13 text-gray-300">Total: {{ $evaluations->count() }}</span>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-hover align-middle" id="evaluasi-table">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Materi</th>
                                                <th>Bab/Step</th>
                                                <th>Track</th>
                                                <th>Palette</th>
                                                <th>Solusi</th>
                                                <th>Diperbarui</th>
                                                <th class="text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($evaluations as $evaluation)
                                                @php($config = $evaluation->opsi ?? [])
                                                @php($palette = collect($config['palette'] ?? []))
                                                @php($solution = collect($config['solution'] ?? []))
                                                <tr>
                                                    <td>#{{ $evaluation->id }}</td>
                                                    <td>
                                                        <span class="fw-semibold">{{ $evaluation->materi->judul ?? '-' }}</span>
                                                        <div class="text-13 text-gray-400">{{ $evaluation->pertanyaan }}</div>
                                                    </td>
                                                    <td>Bab {{ $evaluation->materi->bab ?? '-' }} � Step {{ $evaluation->materi->step ?? '-' }}</td>
                                                    <td>{{ $evaluation->materi->track ?? 'Default' }}</td>
                                                    <td>
                                                        <div class="d-flex flex-wrap gap-6">
                                                            @foreach ($palette as $block)
                                                                <span class="badge bg-main-100 text-main-600">{{ $config['labels'][$block] ?? $block }}</span>
                                                            @endforeach
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex flex-wrap gap-6">
                                                            @foreach ($solution as $block)
                                                                <span class="badge bg-success-100 text-success-600">{{ $config['labels'][$block] ?? $block }}</span>
                                                            @endforeach
                                                        </div>
                                                    </td>
                                                    <td>{{ $evaluation->updated_at?->format('d M Y H:i') ?? 'baru' }}</td>
                                                    <td class="text-center">
                                                        <div class="d-flex justify-content-center gap-6">
                                                            <a href="{{ route('admin.evaluasi.edit', $evaluation) }}" class="btn btn-sm btn-outline-main rounded-pill">Edit</a>
                                                            <form action="{{ route('admin.evaluasi.destroy', $evaluation) }}" method="POST" onsubmit="return confirm('Hapus soal ini?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill">Hapus</button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="8" class="text-center text-gray-400 py-32">Belum ada soal scratch yang tersedia.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xxl-5">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="mb-16">Pratinjau</h5>
                                @if ($previewEvaluation)
                                    @php($previewOptions = $previewOptions ?? [])
                                    <div class="mb-16">
                                        <h6 class="fw-semibold mb-4">{{ $previewEvaluation->materi->judul ?? '-' }}</h6>
                                        <p class="text-gray-400 mb-0">Bab {{ $previewEvaluation->materi->bab ?? '-' }} � Step {{ $previewEvaluation->materi->step ?? '-' }} � {{ $previewEvaluation->materi->track ?? 'Default' }}</p>
                                    </div>
                                    <p class="fw-semibold mb-12">{{ $previewEvaluation->pertanyaan }}</p>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <h6 class="text-13 text-uppercase text-gray-400">Palette</h6>
                                            <ul class="list-unstyled d-flex flex-column gap-6 mb-0">
                                                @foreach ($previewOptions['palette'] ?? [] as $block)
                                                    <li><span class="badge bg-white text-main-600 border border-main-200">{{ $previewOptions['labels'][$block] ?? $block }}</span></li>
                                                @endforeach
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <h6 class="text-13 text-uppercase text-gray-400">Urutan Benar</h6>
                                            <ol class="mb-0 d-flex flex-column gap-4 ps-3">
                                                @foreach ($previewOptions['solution'] ?? [] as $block)
                                                    <li>{{ $previewOptions['labels'][$block] ?? $block }}</li>
                                                @endforeach
                                            </ol>
                                        </div>
                                    </div>
                                    @if (! empty($previewOptions['hint'] ?? ''))
                                        <div class="alert alert-info mt-20" role="alert">
                                            <i class="ph ph-lightbulb me-2"></i>{{ $previewOptions['hint'] }}
                                        </div>
                                    @endif
                                @else
                                    <p class="text-gray-400 mb-0">Belum ada soal untuk ditampilkan.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
</x-app-layout>


