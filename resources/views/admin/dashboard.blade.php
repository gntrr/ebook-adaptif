<x-app-layout>
    <div class="dashboard-main-wrapper">
        @include('layouts.topnav')

        <div class="dashboard-body">
            <div class="container-fluid">
                @php
                    $periodLabel = isset($filter['start'], $filter['end'])
                        ? \Carbon\Carbon::parse($filter['start'])->format('d M Y') . ' - ' . \Carbon\Carbon::parse($filter['end'])->format('d M Y')
                        : '30 hari terakhir';
                @endphp

                <div class="d-flex flex-wrap justify-content-between align-items-center gap-16 mb-24">
                    <div>
                        <h1 class="h3 mb-4">Dashboard Admin</h1>
                        <p class="text-gray-400 mb-0">Pantau metrik utama, performa belajar, dan aktivitas terbaru pengguna.</p>
                    </div>
                    <form method="GET" action="{{ route('admin.dashboard') }}" class="d-flex flex-wrap gap-12">
                        <div class="input-group">
                            <span class="input-group-text bg-main-50 border-0"><i class="ph ph-calendar"></i></span>
                            <input type="number" name="days" value="{{ request('days', 30) }}" min="1" max="180"
                                class="form-control border-0 bg-main-50" placeholder="Rentang hari">
                        </div>
                        <button type="submit" class="btn btn-main rounded-pill">Perbarui</button>
                    </form>
                </div>

                @include('admin.partials.flash')

                <div class="row gy-4 mb-4">
                    <div class="col-xl-2 col-sm-6">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between mb-16">
                                    <span class="flex-shrink-0 w-48 h-48 flex-center rounded-circle bg-main-600 text-white text-2xl">
                                        <i class="ph ph-users"></i>
                                    </span>
                                </div>
                                <h4 class="mb-8">{{ number_format((int) ($metrics['total_users'] ?? 0)) }}</h4>
                                <span class="text-gray-400">Total Pengguna</span>
                                <div class="text-13 text-gray-300 mt-8">Admin: {{ number_format((int) ($metrics['total_admins'] ?? 0)) }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-sm-6">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between mb-16">
                                    <span class="flex-shrink-0 w-48 h-48 flex-center rounded-circle bg-main-two-600 text-white text-2xl">
                                        <i class="ph ph-book"></i>
                                    </span>
                                </div>
                                <h4 class="mb-8">{{ number_format((int) ($metrics['total_materi'] ?? 0)) }}</h4>
                                <span class="text-gray-400">Total Materi</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-sm-6">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between mb-16">
                                    <span class="flex-shrink-0 w-48 h-48 flex-center rounded-circle bg-purple-600 text-white text-2xl">
                                        <i class="ph ph-clipboard-text"></i>
                                    </span>
                                </div>
                                <h4 class="mb-8">{{ number_format((int) ($metrics['total_evaluasi'] ?? 0)) }}</h4>
                                <span class="text-gray-400">Total Evaluasi</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-sm-6">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between mb-16">
                                    <span class="flex-shrink-0 w-48 h-48 flex-center rounded-circle bg-warning-600 text-white text-2xl">
                                        <i class="ph ph-chart-line-up"></i>
                                    </span>
                                </div>
                                <h4 class="mb-8">{{ number_format((float) ($metrics['avg_skor'] ?? 0), 2) }}</h4>
                                <span class="text-gray-400">Rata-rata Skor</span>
                                <div class="text-13 text-gray-300 mt-8">30 hari terakhir</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-sm-6">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between mb-16">
                                    <span class="flex-shrink-0 w-48 h-48 flex-center rounded-circle bg-success-600 text-white text-2xl">
                                        <i class="ph ph-check"></i>
                                    </span>
                                </div>
                                <h4 class="mb-8">{{ number_format((float) ($metrics['pass_rate'] ?? 0), 2) }}%</h4>
                                <span class="text-gray-400">% Ketuntasan</span>
                                <div class="text-13 text-gray-300 mt-8">{{ number_format((int) ($metrics['total_attempts'] ?? 0)) }} attempts</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row gy-4">
                    <div class="col-xxl-7">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-16">
                                    <h5 class="mb-0">Rekap Per Bab</h5>
                                    <span class="text-13 text-gray-300">Periode: {{ $periodLabel }}</span>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0" id="rekap-bab-table">
                                        <thead>
                                            <tr>
                                                <th>Bab</th>
                                                <th class="text-end">Attempts</th>
                                                <th class="text-end">Rata-rata</th>
                                                <th class="text-end">Lulus</th>
                                                <th class="text-end">% Lulus</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($rekapPerBab as $rekap)
                                                <tr>
                                                    <td class="fw-semibold">{{ $rekap->bab }}</td>
                                                    <td class="text-end">{{ number_format((int) $rekap->attempts) }}</td>
                                                    <td class="text-end">{{ number_format((float) $rekap->avg_skor, 2) }}</td>
                                                    <td class="text-end">{{ number_format((int) $rekap->lulus_count) }}</td>
                                                    <td class="text-end">{{ number_format((float) $rekap->lulus_rate, 2) }}%</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center text-gray-400 py-24">Belum ada data evaluasi pada periode ini.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xxl-5">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="mb-16">Top Performer</h5>
                                <div class="table-responsive">
                                    <table class="table table-striped mb-0" id="top-performers-table">
                                        <thead>
                                            <tr>
                                                <th>Nama</th>
                                                <th class="text-end">Attempts</th>
                                                <th class="text-end">Rata-rata Skor</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($topPerformers as $user)
                                                <tr>
                                                    <td>{{ $user->name }}</td>
                                                    <td class="text-end">{{ number_format((int) $user->attempts) }}</td>
                                                    <td class="text-end">{{ number_format((float) $user->avg_skor, 2) }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="3" class="text-center text-gray-400 py-24">Belum ada user memenuhi kriteria.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row gy-4 mt-0 mt-xxl-4">
                    <div class="col-xxl-7">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="mb-16">Perlu Remedial</h5>
                                <div class="table-responsive">
                                    <table class="table mb-0" id="needs-remedial-table">
                                        <thead>
                                            <tr>
                                                <th>Nama</th>
                                                <th class="text-end">Attempts</th>
                                                <th class="text-end">Rata-rata Skor</th>
                                                <th class="text-end">Fail Rate</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($needsRemedial as $user)
                                                <tr>
                                                    <td>{{ $user->name }}</td>
                                                    <td class="text-end">{{ number_format((int) $user->attempts) }}</td>
                                                    <td class="text-end">{{ number_format((float) $user->avg_skor, 2) }}</td>
                                                    <td class="text-end">{{ number_format((float) $user->fail_rate, 2) }}%</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-center text-gray-400 py-24">Belum ada user membutuhkan remedial pada periode ini.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-5">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="mb-16">Distribusi Posisi Belajar</h5>
                                <div class="table-responsive">
                                    <table class="table table-sm mb-0" id="posisi-table">
                                        <thead>
                                            <tr>
                                                <th>Bab</th>
                                                <th>Track</th>
                                                <th>Step</th>
                                                <th class="text-end">Jumlah User</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($posisiDistribusi as $row)
                                                <tr>
                                                    <td>{{ $row->current_bab ?? '-' }}</td>
                                                    <td>{{ $row->current_track ?? '-' }}</td>
                                                    <td>{{ $row->current_step ?? '-' }}</td>
                                                    <td class="text-end">{{ number_format((int) $row->users) }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-center text-gray-400 py-24">Belum ada data posisi belajar.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-body">
                        <h5 class="mb-16">Aktivitas Terbaru</h5>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0" id="recent-activity-table">
                                <thead>
                                    <tr>
                                        <th>Waktu</th>
                                        <th>Pengguna</th>
                                        <th>Bab/Track/Step</th>
                                        <th>Judul</th>
                                        <th class="text-end">Skor</th>
                                        <th class="text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($recentActivity as $attempt)
                                        <tr>
                                            <td>{{ $attempt->created_at?->format('d M Y H:i') }}</td>
                                            <td>{{ $attempt->user?->name ?? '-' }}</td>
                                            <td>
                                                @php
                                                    $materi = $attempt->evaluasi?->materi;
                                                @endphp
                                                {{ $materi?->bab ?? '-' }} /
                                                {{ $materi?->track ?? '-' }} /
                                                {{ $materi?->step ?? '-' }}
                                            </td>
                                            <td>{{ $attempt->evaluasi?->materi?->judul ?? '-' }}</td>
                                            <td class="text-end">{{ number_format((float) $attempt->skor, 2) }}</td>
                                            <td class="text-center">
                                                @if ($attempt->lulus)
                                                    <span class="badge bg-success-100 text-success-600">Lulus</span>
                                                @else
                                                    <span class="badge bg-danger-100 text-danger-600">Remedial</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-gray-400 py-24">Belum ada aktivitas evaluasi.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const simpleTable = {
                    paging: false,
                    searching: false,
                    info: false,
                    ordering: false
                };

                if (window.jQuery && $.fn.DataTable) {
                    $('#rekap-bab-table').DataTable(simpleTable);
                    $('#top-performers-table').DataTable(simpleTable);
                    $('#needs-remedial-table').DataTable(simpleTable);
                    $('#posisi-table').DataTable(simpleTable);
                    $('#recent-activity-table').DataTable({
                        paging: false,
                        searching: false,
                        info: false,
                        order: [[0, 'desc']]
                    });
                }
            });
        </script>
    @endpush
</x-app-layout>
