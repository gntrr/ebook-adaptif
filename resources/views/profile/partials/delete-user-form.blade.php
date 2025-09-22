<section class="space-y-6">
    <header>
        <p class="mt-1 text-sm text-gray-600 mb-16">
            Ketika akun Anda dihapus, semua data akan dihapus secara permanen. Silahkan lanjutkan jika Anda yakin.
        </p>
    </header>

    <!-- Trigger Modal (adopsi pola template create-quiz) -->
    <button type="button" class="btn btn-danger rounded-pill" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
        Hapus Akun
    </button>

    <!-- Modal Bootstrap -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="confirmDeleteModalLabel">Konfirmasi Hapus Akun</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-gray-500 mb-16">Tindakan ini tidak bisa dibatalkan. Masukkan password Anda untuk melanjutkan penghapusan.</p>
                    <form id="delete-account-form" method="POST" action="{{ route('profile.destroy') }}">
                        @csrf
                        @method('DELETE')
                        <div class="mb-16">
                            <label class="form-label text-13 text-uppercase text-gray-400">Password</label>
                            <input type="password" name="password" class="form-control bg-main-50 border-0" placeholder="Password" required>
                            @if ($errors->userDeletion->has('password'))
                                <div class="text-danger-600 text-13 mt-4">{{ $errors->userDeletion->first('password') }}</div>
                            @endif
                        </div>
                        <div class="mb-8 form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="confirmDelete" name="confirm_delete" required>
                            <label class="form-check-label text-gray-500" for="confirmDelete">
                                Saya memahami bahwa penghapusan akun bersifat permanen dan tidak dapat dibatalkan.
                            </label>
                            @if ($errors->userDeletion->has('confirm_delete'))
                                <div class="text-danger-600 text-13 mt-4">{{ $errors->userDeletion->first('confirm_delete') }}</div>
                            @endif
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-gray rounded-pill" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" form="delete-account-form" class="btn btn-danger rounded-pill">Hapus Permanen</button>
                </div>
            </div>
        </div>
    </div>

    @if ($errors->userDeletion->isNotEmpty())
        @push('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', function(){
                    if(window.bootstrap){
                        const modalEl = document.getElementById('confirmDeleteModal');
                        const modal = new bootstrap.Modal(modalEl);
                        modal.show();
                    }
                });
            </script>
        @endpush
    @endif
</section>
