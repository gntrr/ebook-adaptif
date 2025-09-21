<x-app-layout>
    <div class="dashboard-main-wrapper">
        @include('layouts.topnav')

        <div class="dashboard-body">
            <div class="container-fluid">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-16 mb-24">
                    <div>
                        <h1 class="h3 mb-4">Manajemen Materi</h1>
                        <p class="text-gray-400 mb-0">Kelola konten materi, praktek, dan evaluasi per bab.</p>
                    </div>
                    <a href="{{ route('admin.materi.create') }}" class="btn btn-main rounded-pill"><i class="ph ph-plus me-8"></i>Tambah Materi</a>
                </div>

                @include('admin.partials.flash')

                <div class="card mb-4">
                    <div class="card-body">
                        <form method="GET" action="{{ route('admin.materi.index') }}" class="row g-3 align-items-end">
                            <div class="col-md-3">
                                <label class="form-label text-13 text-uppercase text-gray-400">Cari Judul</label>
                                <input type="text" name="q" value="{{ request('q') }}" class="form-control bg-main-50 border-0" placeholder="Ketik kata kunci">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label text-13 text-uppercase text-gray-400">Bab</label>
                                <input type="number" name="bab" value="{{ request('bab') }}" class="form-control bg-main-50 border-0" min="1" max="99">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label text-13 text-uppercase text-gray-400">Track</label>
                                <select name="track" class="form-select bg-main-50 border-0">
                                    <option value="">Semua</option>
                                    <option value="A" @selected(request('track') === 'A')>A</option>
                                    <option value="B" @selected(request('track') === 'B')>B</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label text-13 text-uppercase text-gray-400">Step</label>
                                <input type="number" name="step" value="{{ request('step') }}" class="form-control bg-main-50 border-0" min="1" max="5">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label text-13 text-uppercase text-gray-400">Tipe</label>
                                <select name="tipe" class="form-select bg-main-50 border-0">
                                    <option value="">Semua tipe</option>
                                    @foreach ([
                                        'materi' => 'Materi',
                                        'praktek' => 'Praktek',
                                        'evaluasi' => 'Evaluasi Step',
                                        'evaluasi_bab' => 'Evaluasi Bab',
                                    ] as $value => $label)
                                        <option value="{{ $value }}" @selected(request('tipe') === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 d-flex gap-12">
                                <button type="submit" class="btn btn-main rounded-pill">Terapkan Filter</button>
                                <a href="{{ route('admin.materi.index') }}" class="btn btn-outline-gray rounded-pill">Reset</a>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle" id="materi-table">
                                <thead>
                                    <tr>
                                        <th>Bab</th>
                                        <th>Track</th>
                                        <th>Step</th>
                                        <th>Tipe</th>
                                        <th>Judul</th>
                                        <th>Diperbarui</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($materis as $materi)
                                        <tr>
                                            <td>{{ $materi->bab }}</td>
                                            <td>{{ $materi->track ?? '-' }}</td>
                                            <td>{{ $materi->step }}</td>
                                            <td class="text-capitalize">{{ str_replace('_', ' ', $materi->tipe) }}</td>
                                            <td>
                                                <div class="fw-semibold">{{ $materi->judul }}</div>
                                                @if ($materi->konten)
                                                    <div class="text-13 text-gray-300">{{ \Illuminate\Support\Str::limit(strip_tags($materi->konten), 80) }}</div>
                                                @endif
                                            </td>
                                            <td>{{ $materi->updated_at?->format('d M Y H:i') }}</td>
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center gap-8">
                                                    <a href="{{ route('admin.materi.edit', $materi) }}" class="btn btn-sm btn-outline-main rounded-pill">Edit</a>
                                                    <form action="{{ route('admin.materi.destroy', $materi) }}" method="POST" onsubmit="return confirm('Hapus materi ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill">Hapus</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr data-empty="1">
                                            <td colspan="7" class="text-center text-gray-400 py-24">Belum ada materi yang sesuai filter.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-12">
                            <span class="text-13 text-gray-300">Menampilkan {{ $materis->firstItem() ?? 0 }} - {{ $materis->lastItem() ?? 0 }} dari {{ $materis->total() }} data</span>
                            {{ $materis->onEachSide(1)->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                if (window.jQuery && $.fn.DataTable) {
                    const $tbl = $('#materi-table');
                    const bodyRows = $tbl.find('tbody tr');
                    const nonEmptyRows = bodyRows.filter(function(){ return !this.hasAttribute('data-empty'); });
                    if(nonEmptyRows.length === 0){
                        // Jangan init DataTables kalau hanya ada baris placeholder dengan colspan
                        return;
                    }
                    $tbl.DataTable({
                        paging: false,
                        info: false,
                        searching: false,
                        order: [[0, 'asc'], [1, 'asc'], [2, 'asc']]
                    });
                }
            });
        </script>
    @endpush
</x-app-layout>
