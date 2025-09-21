<x-app-layout>
    <div class="dashboard-main-wrapper">
        @include('layouts.topnav')

        <div class="dashboard-body">
            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center gap-16 mb-24">
                    <div>
                        <h1 class="h3 mb-4">Tambah Soal Scratch</h1>
                        <p class="text-gray-400 mb-0">Hubungkan soal dengan materi evaluasi/step yang sudah tersedia.</p>
                    </div>
                </div>

                @include('admin.partials.flash')

                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.evaluasi.store') }}">
                            @csrf
                            @include('admin.evaluasi._form', ['evaluation' => $evaluation, 'config' => $config])
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
