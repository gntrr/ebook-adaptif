<x-app-layout>
    <div class="dashboard-main-wrapper">
        @include('layouts.topnav')

        <div class="dashboard-body">
            <div class="container-fluid">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-16 mb-24">
                    <div>
                        <h1 class="h3 mb-4">Klasifikasi &amp; Keputusan</h1>
                        <p class="text-gray-400 mb-0">Tinjau kategori user berdasarkan performa dan update keputusan adaptif.</p>
                    </div>
                </div>

                @include('admin.partials.flash')

                <div class="card mb-4">
                    <div class="card-body">
                        <form method="GET" action="{{ route('admin.klasifikasi.index') }}" class="row g-3 align-items-end">
                            <div class="col-md-6">
                                <label class="form-label text-13 text-uppercase text-gray-400">Cari Nama / Email</label>
                                <input type="text" name="q" value="{{ request('q') }}" class="form-control bg-main-50 border-0" placeholder="Misal: Nurul, user@email.com">
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-main rounded-pill">Cari</button>
                                <a href="{{ route('admin.klasifikasi.index') }}" class="btn btn-outline-gray rounded-pill ms-2">Reset</a>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle" id="klasifikasi-table">
                                <thead>
                                    <tr>
                                        <th>Pengguna</th>
                                        <th>Email</th>
                                        <th>Kategori Tersimpan</th>
                                        <th>Rekomendasi</th>
                                        <th>Posisi Saat Ini</th>
                                        <th>Progres</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($users as $user)
                                        @php
                                            $suggest = $recommend[$user->id] ?? ['avg' => null, 'kategori' => null];
                                            $defaultKategori = $user->kategori_tersimpan ?? ($suggest['kategori'] ?? null);
                                        @endphp
                                        <tr>
                                            <td>
                                                <div class="fw-semibold">{{ $user->name }}</div>
                                            </td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                @if ($user->kategori_tersimpan)
                                                    <span class="badge bg-main-100 text-main-600">{{ $user->kategori_tersimpan }}</span>
                                                @else
                                                    <span class="badge bg-gray-100 text-gray-400">Belum diset</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($suggest['kategori'])
                                                    <div class="d-flex flex-column">
                                                        <span class="badge bg-success-100 text-success-600">Saran: {{ $suggest['kategori'] }}</span>
                                                        <small class="text-gray-300">Rata-rata skor: {{ number_format((float) ($suggest['avg'] ?? 0), 2) }}</small>
                                                    </div>
                                                @else
                                                    <span class="text-gray-400">-</span>
                                                @endif
                                            </td>
                                            <td>Bab {{ $user->current_bab ?? '-' }} / Track {{ $user->current_track ?? '-' }} / Step {{ $user->current_step ?? '-' }}</td>
                                            <td>
                                                <div class="d-flex align-items-center gap-12">
                                                    <div class="progress flex-grow-1" style="height: 6px;">
                                                        <div class="progress-bar bg-main-600" role="progressbar" style="width: {{ (float) ($user->progress ?? 0) }}%"></div>
                                                    </div>
                                                    <span class="text-13 text-gray-400">{{ number_format((float) ($user->progress ?? 0), 1) }}%</span>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center gap-8">
                                                    <form action="{{ route('admin.klasifikasi.store', $user) }}" method="POST" class="d-flex gap-6 align-items-center">
                                                        @csrf
                                                        <select name="kategori" class="form-select form-select-sm bg-main-50 border-0" required>
                                                            <option value="" @selected(!$defaultKategori)>Pilih</option>
                                                            @foreach (['A','B','C'] as $kat)
                                                                <option value="{{ $kat }}" @selected($defaultKategori === $kat)>{{ $kat }}</option>
                                                            @endforeach
                                                        </select>
                                                        <button type="submit" class="btn btn-sm btn-main rounded-pill">Simpan</button>
                                                    </form>
                                                    @if ($user->kategori_tersimpan)
                                                        <form action="{{ route('admin.klasifikasi.destroy', $user) }}" method="POST" onsubmit="return confirm('Hapus kategori tersimpan untuk {{ $user->name }}?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill">Hapus</button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr data-empty="1">
                                            <td colspan="7" class="text-center text-gray-400 py-24">Tidak ada user ditemukan.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-12">
                            <span class="text-13 text-gray-300">Menampilkan {{ $users->firstItem() ?? 0 }} - {{ $users->lastItem() ?? 0 }} dari {{ $users->total() }} user</span>
                            {{ $users->onEachSide(1)->links('pagination::bootstrap-5') }}
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
                    const $tbl = $('#klasifikasi-table');
                    const bodyRows = $tbl.find('tbody tr');
                    const nonEmptyRows = bodyRows.filter(function(){ return !this.hasAttribute('data-empty'); });
                    if(nonEmptyRows.length === 0){
                        return;
                    }
                    $tbl.DataTable({
                        paging: false,
                        info: false,
                        searching: false,
                        order: [[0, 'asc']]
                    });
                }
            });
        </script>
    @endpush
</x-app-layout>
