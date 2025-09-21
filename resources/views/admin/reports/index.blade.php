<x-app-layout>
    <div class="dashboard-main-wrapper">
        @include('layouts.topnav')

        <div class="dashboard-body">
            <div class="container-fluid">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-16 mb-24">
                    <div>
                        <h1 class="h3 mb-4">Laporan &amp; Ekspor</h1>
                        <p class="text-gray-400 mb-0">Analisis performa evaluasi, tingkat ketuntasan, dan ekspor data CSV.</p>
                    </div>
                    <div class="d-flex gap-8 flex-wrap">
                        <a href="{{ route('admin.reports.export', array_merge(request()->all(), ['type' => 'per_bab'])) }}" class="btn btn-outline-main rounded-pill">
                            <i class="ph ph-download-simple me-2"></i>Ekspor Per Bab
                        </a>
                        <a href="{{ route('admin.reports.export', array_merge(request()->all(), ['type' => 'per_user'])) }}" class="btn btn-outline-main rounded-pill">
                            <i class="ph ph-download-simple me-2"></i>Ekspor Per User
                        </a>
                        <a href="{{ route('admin.reports.export', array_merge(request()->all(), ['type' => 'detail'])) }}" class="btn btn-outline-main rounded-pill">
                            <i class="ph ph-download-simple me-2"></i>Ekspor Detail
                        </a>
                    </div>
                </div>

                @include('admin.partials.flash')

                <div class="card mb-4">
                    <div class="card-body">
                        <form method="GET" action="{{ route('admin.reports.index') }}" class="row g-3 align-items-end">
                            <div class="col-md-3">
                                <label class="form-label text-13 text-uppercase text-gray-400">Tanggal Mulai</label>
                                <input type="date" name="start" class="form-control bg-main-50 border-0" value="{{ request('start', $filter['start']) }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label text-13 text-uppercase text-gray-400">Tanggal Selesai</label>
                                <input type="date" name="end" class="form-control bg-main-50 border-0" value="{{ request('end', $filter['end']) }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label text-13 text-uppercase text-gray-400">Bab</label>
                                <input type="number" name="bab" class="form-control bg-main-50 border-0" min="1" max="99" value="{{ request('bab') }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label text-13 text-uppercase text-gray-400">Track</label>
                                <select name="track" class="form-select bg-main-50 border-0">
                                    <option value="">Semua</option>
                                    <option value="A" @selected(request('track') === 'A')>A</option>
                                    <option value="B" @selected(request('track') === 'B')>B</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label text-13 text-uppercase text-gray-400">Status</label>
                                <select name="status" class="form-select bg-main-50 border-0">
                                    <option value="">Semua</option>
                                    <option value="pass" @selected(request('status') === 'pass')>Lulus</option>
                                    <option value="fail" @selected(request('status') === 'fail')>Tidak Lulus</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label text-13 text-uppercase text-gray-400">User</label>
                                <select name="user_id" class="form-select bg-main-50 border-0">
                                    <option value="">Semua User</option>
                                    @foreach ($userOptions as $opt)
                                        <option value="{{ $opt->id }}" @selected((string) request('user_id') === (string) $opt->id)>{{ $opt->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 d-flex gap-12">
                                <button type="submit" class="btn btn-main rounded-pill">Terapkan Filter</button>
                                <a href="{{ route('admin.reports.index') }}" class="btn btn-outline-gray rounded-pill">Reset</a>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="row gy-4">
                    <div class="col-xxl-6">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="mb-16">Rekap Per Bab</h5>
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle" id="report-per-bab">
                                        <thead>
                                            <tr>
                                                <th>Bab</th>
                                                <th class="text-end">Attempts</th>
                                                <th class="text-end">Avg Skor</th>
                                                <th class="text-end">Lulus</th>
                                                <th class="text-end">% Lulus</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($perBab as $row)
                                                <tr>
                                                    <td>{{ $row->bab }}</td>
                                                    <td class="text-end">{{ number_format((int) $row->attempts) }}</td>
                                                    <td class="text-end">{{ number_format((float) $row->avg_skor, 2) }}</td>
                                                    <td class="text-end">{{ number_format((int) $row->lulus_count) }}</td>
                                                    <td class="text-end">{{ number_format((float) $row->lulus_rate, 2) }}%</td>
                                                </tr>
                                            @empty
                                                <tr data-empty="1">
                                                    <td colspan="5" class="text-center text-gray-400 py-24">Tidak ada data untuk filter ini.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xxl-6">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="mb-16">Rekap Per User</h5>
                                <div class="table-responsive">
                                    <table class="table table-striped align-middle" id="report-per-user">
                                        <thead>
                                            <tr>
                                                <th>User</th>
                                                <th class="text-end">Attempts</th>
                                                <th class="text-end">Avg Skor</th>
                                                <th class="text-end">% Lulus</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($perUser as $row)
                                                <tr>
                                                    <td>{{ $row->name }}</td>
                                                    <td class="text-end">{{ number_format((int) $row->attempts) }}</td>
                                                    <td class="text-end">{{ number_format((float) $row->avg_skor, 2) }}</td>
                                                    <td class="text-end">{{ number_format((float) $row->lulus_rate, 2) }}%</td>
                                                </tr>
                                            @empty
                                                <tr data-empty="1">
                                                    <td colspan="4" class="text-center text-gray-400 py-24">Tidak ada data per user.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <div class="d-flex justify-content-end mt-3">
                                    {{ $perUser->onEachSide(1)->links('pagination::bootstrap-5') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-body">
                        <h5 class="mb-16">Detail Attempt</h5>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle" id="report-detail">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Waktu</th>
                                        <th>User</th>
                                        <th>Bab/Track/Step</th>
                                        <th>Tipe</th>
                                        <th>Judul</th>
                                        <th class="text-end">Skor</th>
                                        <th class="text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($detail as $row)
                                        <tr>
                                            <td>#{{ $row->id }}</td>
                                            <td>{{ \Carbon\Carbon::parse($row->created_at)->format('d M Y H:i') }}</td>
                                            <td>{{ $row->user_name }}</td>
                                            <td>Bab {{ $row->bab }} / {{ $row->track ?? '-' }} / Step {{ $row->step }}</td>
                                            <td class="text-capitalize">{{ str_replace('_', ' ', $row->tipe) }}</td>
                                            <td>{{ $row->judul }}</td>
                                            <td class="text-end">{{ number_format((float) $row->skor, 2) }}</td>
                                            <td class="text-center">
                                                @if ($row->lulus)
                                                    <span class="badge bg-success-100 text-success-600">Lulus</span>
                                                @else
                                                    <span class="badge bg-danger-100 text-danger-600">Remedial</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr data-empty="1">
                                            <td colspan="8" class="text-center text-gray-400 py-24">Detail attempt tidak ditemukan.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-end mt-3">
                            {{ $detail->onEachSide(1)->links('pagination::bootstrap-5') }}
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
                    function initIfNotEmpty(sel, order){
                        const $t = $(sel);
                        const rows = $t.find('tbody tr');
                        const nonEmpty = rows.filter(function(){ return !this.hasAttribute('data-empty'); });
                        if(nonEmpty.length === 0){ return; }
                        $t.DataTable({
                            paging: false,
                            info: false,
                            searching: false,
                            order: order
                        });
                    }
                    initIfNotEmpty('#report-per-bab', [[0,'asc']]);
                    initIfNotEmpty('#report-per-user', [[1,'desc']]);
                    initIfNotEmpty('#report-detail', [[1,'desc']]);
                }
            });
        </script>
    @endpush
</x-app-layout>
