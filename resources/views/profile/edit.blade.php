<x-app-layout>
    <div class="dashboard-main-wrapper">
        @include('layouts.topnav')
        <div class="dashboard-body">
            <div class="container-fluid">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-16 mb-24">
                    <div>
                        <h1 class="h3 mb-4">Pengaturan Akun</h1>
                        <p class="text-gray-400 mb-0">Kelola informasi profil, kata sandi, dan preferensi belajar Anda.</p>
                    </div>
                </div>

                @include('admin.partials.flash')

                <div class="row gy-4">
                    <div class="col-xxl-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="mb-16">Informasi Dasar</h5>
                                <form method="POST" action="{{ route('profile.update') }}" class="vstack gap-16">
                                    @csrf
                                    @method('PATCH')
                                    <div>
                                        <label class="form-label text-13 text-uppercase text-gray-400">Nama</label>
                                        <input type="text" name="name" class="form-control bg-main-50 border-0" value="{{ old('name', $user->name) }}" required>
                                        @error('name')<div class="text-danger-600 text-13 mt-4">{{ $message }}</div>@enderror
                                    </div>
                                    <div>
                                        <label class="form-label text-13 text-uppercase text-gray-400">Email</label>
                                        <input type="email" name="email" class="form-control bg-main-50 border-0" value="{{ old('email', $user->email) }}" required>
                                        @error('email')<div class="text-danger-600 text-13 mt-4">{{ $message }}</div>@enderror
                                        @if ($user->hasVerifiedEmail())
                                            <small class="text-success-600 d-block mt-4">Email terverifikasi</small>
                                        @else
                                            <small class="text-warning-600 d-block mt-4">Belum terverifikasi</small>
                                        @endif
                                    </div>
                                    <div class="d-flex justify-content-end gap-12">
                                        <button type="submit" class="btn btn-main rounded-pill">Simpan Perubahan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-xxl-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="mb-8">Ubah Kata Sandi</h5>
                                @include('profile.partials.update-password-form')
                            </div>
                        </div>
                    </div>

                    <div class="col-xxl-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="mb-8">Hapus Akun</h5>
                                @include('profile.partials.delete-user-form')
                            </div>
                        </div>
                    </div>
                </div>

                {{-- <div class="row gy-4 mt-2">
                    <div class="col-xl-6">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="mb-16">Preferensi Belajar</h5>
                                <form method="POST" action="{{ route('profile.update') }}" class="row g-4">
                                    @csrf
                                    @method('PATCH')
                                    <div class="col-md-6">
                                        <label class="form-label text-13 text-uppercase text-gray-400">Track Target</label>
                                        <select name="goal_track" class="form-select bg-main-50 border-0">
                                            <option value="">Default</option>
                                            <option value="A" @selected(old('goal_track', $user->goal_track) === 'A')>Track A</option>
                                            <option value="B" @selected(old('goal_track', $user->goal_track) === 'B')>Track B</option>
                                            <option value="DEFAULT" @selected(old('goal_track', $user->goal_track) === 'DEFAULT')>Jalur Utama</option>
                                        </select>
                                        @error('goal_track')<div class="text-danger-600 text-13 mt-4">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label text-13 text-uppercase text-gray-400">Tujuan Belajar</label>
                                        <input type="text" name="learning_goal" class="form-control bg-main-50 border-0" value="{{ old('learning_goal', $user->learning_goal) }}" placeholder="Misal: Kuasai Scratch dasar">
                                        @error('learning_goal')<div class="text-danger-600 text-13 mt-4">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-12">
                                        <div class="alert alert-info d-flex gap-8 align-items-start" role="alert">
                                            <i class="ph ph-info"></i>
                                            <div class="text-gray-400">Preferensi ini membantu sistem adaptif menyarankan jalur materi yang sesuai.</div>
                                        </div>
                                    </div>
                                    <div class="col-12 d-flex justify-content-end gap-12">
                                        <button type="submit" class="btn btn-main rounded-pill">Simpan Preferensi</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>
    </div>
</x-app-layout>
