<x-app-layout>
    <div class="dashboard-main-wrapper">
        @include('layouts.topnav')

        <div class="dashboard-body">
            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center gap-16 mb-24">
                    <div>
                        <h1 class="h3 mb-4">Edit Materi</h1>
                        <p class="text-gray-400 mb-0">Perbarui konten materi "{{ $materi->judul }}".</p>
                    </div>
                    <a href="{{ route('admin.materi.index') }}" class="btn btn-outline-gray rounded-pill">Kembali ke daftar</a>
                </div>

                @include('admin.partials.flash')

                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.materi.update', $materi) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            @include('admin.materi._form')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


