<x-app-layout>
    <div class="dashboard-main-wrapper">
        @include('layouts.topnav')

        <div class="dashboard-body">
            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center gap-16 mb-24">
                    <div>
                        <h1 class="h3 mb-4">Edit User</h1>
                        <p class="text-gray-400 mb-0">Perbarui profil dan status admin untuk {{ $user->name }}.</p>
                    </div>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary rounded-pill">Kembali</a>
                </div>

                @include('admin.partials.flash')

                <div class="row g-4">
                    <div class="col-xxl-7">
                        <div class="card h-100">
                            <div class="card-body">
                                <form method="POST" action="{{ route('admin.users.update', $user) }}">
                                    @csrf
                                    @method('PUT')

                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Nama</label>
                                            <input type="text" name="name" class="form-control bg-main-50 border-0" value="{{ old('name', $user->name) }}" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Email</label>
                                            <input type="email" name="email" class="form-control bg-main-50 border-0" value="{{ old('email', $user->email) }}" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Peran Admin</label>
                                            <select name="is_admin" class="form-select bg-main-50 border-0">
                                                <option value="0" @selected(!old('is_admin', $user->is_admin))>User</option>
                                                <option value="1" @selected(old('is_admin', $user->is_admin))>Admin</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Bab Saat Ini</label>
                                            <input type="number" name="current_bab" class="form-control bg-main-50 border-0" min="1" max="99"
                                                value="{{ old('current_bab', $user->current_bab) }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Track Saat Ini</label>
                                            <select name="current_track" class="form-select bg-main-50 border-0">
                                                <option value="" @selected(old('current_track', $user->current_track) === null)>Default</option>
                                                <option value="A" @selected(old('current_track', $user->current_track) === 'A')>A</option>
                                                <option value="B" @selected(old('current_track', $user->current_track) === 'B')>B</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Step Saat Ini</label>
                                            <input type="number" name="current_step" class="form-control bg-main-50 border-0" min="1" max="5"
                                                value="{{ old('current_step', $user->current_step) }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Progress (%)</label>
                                            <input type="number" name="progress" class="form-control bg-main-50 border-0" step="0.1" min="0" max="100"
                                                value="{{ old('progress', $user->progress) }}">
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-end gap-12 mt-24">
                                        <button type="submit" class="btn btn-main rounded-pill">Simpan Perubahan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-xxl-5">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="mb-16">Reset Password</h5>
                                <p class="text-gray-400">Gunakan fitur ini ketika user lupa password. Password baru akan langsung aktif.</p>
                                <form method="POST" action="{{ route('admin.users.reset-password', $user) }}">
                                    @csrf
                                    @method('PATCH')
                                    <div class="mb-12">
                                        <label class="form-label">Password Baru</label>
                                        <input type="password" name="password" class="form-control bg-main-50 border-0" required>
                                    </div>
                                    <div class="mb-12">
                                        <label class="form-label">Konfirmasi Password</label>
                                        <input type="password" name="password_confirmation" class="form-control bg-main-50 border-0" required>
                                    </div>
                                    <button type="submit" class="btn btn-danger rounded-pill">Reset Password</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
