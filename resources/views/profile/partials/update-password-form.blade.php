<section>
    <header>
        {{-- <h2 class="text-lg font-medium text-gray-900">
            {{ __('Update Password') }}
        </h2> --}}

        <p class="mt-1 text-sm text-gray-600 mb-16">
            Pastikan akun Anda menggunakan password acak yang panjang untuk keamanan maksimal.
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="vstack gap-16">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password" class="form-label text-13 text-uppercase text-gray-400">Kata Sandi Saat Ini</label>
            <div class="position-relative">
                <input id="update_password_current_password" name="current_password" type="password" class="form-control bg-main-50 border-0 mt-1 block w-full" autocomplete="current-password" />
                <span class="toggle-password position-absolute top-50 inset-inline-end-0 me-16 translate-middle-y ph ph-eye-slash" id="#update_password_current_password"></span>
            </div>
            @error('current_password', 'updatePassword')
                <div class="text-danger-600 text-13 mt-4">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="update_password_password" class="form-label text-13 text-uppercase text-gray-400">Kata Sandi Baru</label>
            <div class="position-relative">
                <input id="update_password_password" name="password" type="password" class="form-control bg-main-50 border-0 mt-1 block w-full" autocomplete="new-password" />
                <span class="toggle-password position-absolute top-50 inset-inline-end-0 me-16 translate-middle-y ph ph-eye-slash" id="#update_password_password"></span>
            </div>
            @error('password', 'updatePassword')
                <div class="text-danger-600 text-13 mt-4">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="update_password_password_confirmation" class="form-label text-13 text-uppercase text-gray-400">Konfirmasi Kata Sandi</label>
            <div class="position-relative">
                <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="form-control bg-main-50 border-0 mt-1 block w-full" autocomplete="new-password" />
                <span class="toggle-password position-absolute top-50 inset-inline-end-0 me-16 translate-middle-y ph ph-eye-slash" id="#update_password_password_confirmation"></span>
            </div>
            @error('password_confirmation', 'updatePassword')
                <div class="text-danger-600 text-13 mt-4">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex justify-content-end gap-12">
            <button type="submit" class="btn btn-main rounded-pill">Simpan Perubahan</button>

            {{-- @if (session('status') === 'password-updated')
                <p class="text-sm text-success-600">Tersimpan.</p>
            @endif --}}
        </div>
    </form>
</section>
