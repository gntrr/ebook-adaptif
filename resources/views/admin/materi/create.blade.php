<x-app-layout>
    <div class="dashboard-main-wrapper">
        @include('layouts.topnav')

        <div class="dashboard-body">
            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center gap-16 mb-24">
                    <div>
                        <h1 class="h3 mb-4">Tambah Materi</h1>
                        <p class="text-gray-400 mb-0">Isi informasi materi sesuai struktur bab, track, dan step.</p>
                    </div>
                </div>

                @include('admin.partials.flash')

                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.materi.store') }}">
                            @csrf
                            @include('admin.materi._form')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
