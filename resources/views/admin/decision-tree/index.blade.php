<x-app-layout>
    <div class="dashboard-main-wrapper">
        @include('layouts.topnav')

        <div class="dashboard-body">
            <div class="container-fluid">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-16 mb-24">
                    <div>
                        <h1 class="h3 mb-4">Decision Tree</h1>
                        <p class="text-gray-400 mb-0">Visualisasi urutan slot materi per bab/track berdasarkan data kurikulum.</p>
                    </div>
                </div>

                @include('admin.partials.flash')

                <div class="row gy-4">
                    @forelse ($trees as $tree)
                        <div class="col-xl-6">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-16">
                                        <div>
                                            <h5 class="mb-4">Bab {{ $tree['bab'] }}</h5>
                                            <p class="text-gray-400 mb-0">{{ $tree['summary'] }}</p>
                                        </div>
                                        <span class="badge bg-main-100 text-main-600">{{ $tree['track'] ? 'Track '.$tree['track'] : 'Jalur utama' }}</span>
                                    </div>

                                    <div class="ps-20 border-start border-main-100">
                                        @foreach ($tree['nodes'] as $node)
                                            <div class="mb-16 position-relative">
                                                <span class="position-absolute top-0 start-0 translate-middle-y translate-middle-x w-12 h-12 rounded-circle bg-main-600"></span>
                                                <div class="ms-3">
                                                    <span class="badge bg-main-100 text-main-600 mb-4">{{ $node['label'] }}</span>
                                                    <p class="text-gray-400 mb-0">{{ $node['action'] }}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="alert alert-info d-flex align-items-start gap-10 mt-20" role="alert">
                                        <i class="ph ph-note"></i>
                                        <div>
                                            <span class="fw-semibold d-block mb-2">Catatan</span>
                                            <p class="text-gray-400 mb-0">{{ $tree['notes'] }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body text-center text-gray-400 py-40">
                                    Belum ada data materi yang dapat divisualisasikan.
                                </div>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
