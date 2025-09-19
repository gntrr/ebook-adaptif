<x-app-layout>
    <div class="dashboard-main-wrapper">
        @include('layouts.topnav')

        <div class="dashboard-body">
            <div class="container-fluid">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-16 mb-24">
                    <div>
                        <h1 class="h3 mb-4">Users &amp; Klasifikasi</h1>
                        <p class="text-gray-400 mb-0">Monitor akun pengguna, peran, serta progres belajar.</p>
                    </div>
                </div>

                @include('admin.partials.flash')

                <div class="card mb-4">
                    <div class="card-body">
                        <form method="GET" action="{{ route('admin.users.index') }}" class="row g-3 align-items-end">
                            <div class="col-md-4">
                                <label class="form-label text-13 text-uppercase text-gray-400">Cari Nama atau Email</label>
                                <input type="text" name="q" class="form-control bg-main-50 border-0" value="{{ request('q') }}" placeholder="Masukkan kata kunci">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label text-13 text-uppercase text-gray-400">Peran</label>
                                <select name="role" class="form-select bg-main-50 border-0">
                                    <option value="">Semua</option>
                                    <option value="admin" @selected(request('role') === 'admin')>Admin</option>
                                    <option value="user" @selected(request('role') === 'user')>Pengguna</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-main rounded-pill">Terapkan Filter</button>
                                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary rounded-pill ms-2">Reset</a>
                            </div>
                        </form>
                    </div>
                </div>

                @php
                    $authId = auth()->id();
                @endphp

                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped align-middle" id="users-table">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Peran</th>
                                        <th>Progres</th>
                                        <th>Posisi</th>
                                        <th>Terakhir Aktif</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($users as $user)
                                        <tr>
                                            <td>
                                                <div class="fw-semibold">{{ $user->name }}</div>
                                            </td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                @if ($user->is_admin)
                                                    <span class="badge bg-main-100 text-main-600">Admin</span>
                                                @else
                                                    <span class="badge bg-gray-100 text-gray-600">User</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center gap-12">
                                                    <div class="progress flex-grow-1" style="height: 6px;">
                                                        <div class="progress-bar bg-main-600" role="progressbar"
                                                            style="width: {{ (float) ($user->progress ?? 0) }}%"></div>
                                                    </div>
                                                    <span class="text-13 text-gray-400">{{ number_format((float) ($user->progress ?? 0), 1) }}%</span>
                                                </div>
                                            </td>
                                            <td>
                                                Bab {{ $user->current_bab ?? '-' }} /
                                                Track {{ $user->current_track ?? '-' }} /
                                                Step {{ $user->current_step ?? '-' }}
                                            </td>
                                            <td>{{ $user->updated_at?->format('d M Y H:i') ?? '-' }}</td>
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center gap-8">
                                                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-outline-primary rounded-pill">Edit</a>
                                                    @if ($user->id !== $authId)
                                                        <form action="{{ route('admin.users.toggle-admin', $user) }}" method="POST">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="btn btn-sm btn-outline-secondary rounded-pill">
                                                                {{ $user->is_admin ? 'Cabut Admin' : 'Jadikan Admin' }}
                                                            </button>
                                                        </form>
                                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Hapus user ini?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill">Hapus</button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-gray-400 py-24">Belum ada data user sesuai filter.</td>
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
                    $('#users-table').DataTable({
                        paging: false,
                        info: false,
                        searching: false,
                        order: [[2, 'desc'], [0, 'asc']]
                    });
                }
            });
        </script>
    @endpush
</x-app-layout>
