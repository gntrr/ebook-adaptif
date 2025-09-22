<x-app-layout>
    <div class="dashboard-main-wrapper">
        
        {{-- Top Navbar Start --}}
        @include('layouts.topnav')
        {{-- Top Navbar End --}}

        <div class="dashboard-body">
            <div class="row gy-4">
                <div class="col-xxl-8">
                    <div class="card h-100">
                        <div class="card-body grettings-box-two position-relative z-1 p-0">
                            <div class="row align-items-center h-100">
                                <div class="col-lg-6">
                                    <div class="grettings-box-two__content">
                                        <h2 class="fw-medium mb-0 flex-align gap-10">Hi, {{ Auth::user()->name }} 
                                            <img src="{{ asset('edmate/assets/images/icons/wave-hand.png') }}" alt=""> </h2>
                                        <h2 class="fw-medium mb-16">Apa yang ingin kamu pelajari hari ini?</h2>
                                        @php
                                            $trackLabel = $user->current_track ?: 'Default';
                                            $posisi = $user->current_bab && $user->current_step
                                                ? 'Bab '.$user->current_bab.' / Step '.$user->current_step
                                                : 'Belum mulai';
                                        @endphp
                                        <p class="text-15 text-gray-400 mb-0">Posisi saat ini: <span class="fw-semibold text-main-600">{{ $posisi }}</span> (Track {{ $trackLabel }})</p>
                                        @if ($user->learning_goal)
                                            <p class="text-13 text-gray-300 mt-8 mb-0">Tujuan: {{ $user->learning_goal }}</p>
                                        @endif
                                        <div class="d-flex flex-wrap gap-12 mt-32">
                                            @if($nextMateri)
                                                <a href="{{ $user->learningUrl() }}" class="btn btn-main rounded-pill">Lanjut Belajar</a>
                                            @else
                                                <a href="{{ $user->learningUrl() }}" class="btn btn-main rounded-pill">Mulai Belajar</a>
                                            @endif
                                            <a href="{{ route('profile.edit') }}" class="btn btn-outline-main rounded-pill">Lihat Profil</a>
                                            @if($lastAttempt)
                                                <a href="#last-eval" class="btn btn-outline-gray rounded-pill">Evaluasi Terakhir</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 d-md-block d-none mt-auto">
                                    <img src="{{ asset('images/greeting-assets.png') }}" alt="" style="max-width: 332px;">
                                </div>
                            </div>
                            <img src="{{ asset('edmate/assets/images/bg/star-shape.png') }}"
                                class="position-absolute start-0 top-0 w-100 h-100 z-n1 object-fit-contain"
                                alt="">
                        </div>
                    </div>
                </div>
                <div class="col-xxl-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="mb-16">Progress Keseluruhan</h5>
                            @php($progress = (int)($user->progress ?? 0))
                            <div class="d-flex align-items-center gap-16 mb-12">
                                <div class="flex-grow-1">
                                    <div class="progress bg-main-100 rounded-pill h-10" style="height:10px;">
                                        <div class="progress-bar bg-main-600 rounded-pill" style="width: {{ $progress }}%"></div>
                                    </div>
                                </div>
                                <span class="fw-semibold text-main-600">{{ $progress }}%</span>
                            </div>
                            <p class="text-13 text-gray-400 mb-0">Lengkapi materi & evaluasi untuk meningkatkan progres.</p>
                            @if($nextMateri)
                                <div class="mt-16">
                                    <span class="text-13 text-gray-400 d-block mb-4">Langkah Berikutnya</span>
                                    <div class="p-12 rounded-12 bg-main-50">
                                        <div class="fw-semibold mb-4">Step {{ $nextMateri->step }} • {{ ucwords(str_replace('_',' ', $nextMateri->tipe)) }}</div>
                                        <div class="text-13 text-gray-400">{{ $nextMateri->judul }}</div>
                                        <a href="{{ route('materi.show', [$nextMateri->bab, $nextMateri->track, $nextMateri->step]) }}" class="btn btn-sm btn-main rounded-pill mt-12">Buka</a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-24" id="last-eval">
                <div class="row gy-4">
                    <div class="col-xxl-6">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="mb-16">Evaluasi Terakhir</h5>
                                @if($lastAttempt)
                                    @php($attemptMateri = $lastAttempt->evaluasi?->materi)
                                    <div class="d-flex flex-column gap-8">
                                        <div class="d-flex flex-wrap gap-8 align-items-center">
                                            <span class="badge {{ $lastAttempt->lulus ? 'bg-success-100 text-success-600':'bg-danger-100 text-danger-600' }}">{{ $lastAttempt->lulus ? 'Lulus':'Remedial' }}</span>
                                            <span class="text-13 text-gray-400">Skor: <span class="fw-semibold text-main-600">{{ number_format((float)$lastAttempt->skor,2) }}</span></span>
                                            <span class="text-13 text-gray-300">{{ $lastAttempt->created_at?->format('d M Y H:i') }}</span>
                                        </div>
                                        <div>
                                            <div class="fw-semibold mb-4">{{ $attemptMateri?->judul ?? 'Materi tidak tersedia' }}</div>
                                            <div class="text-13 text-gray-400">Bab {{ $attemptMateri?->bab ?? '-' }} / Step {{ $attemptMateri?->step ?? '-' }} / {{ $attemptMateri?->track ?? 'Default' }}</div>
                                        </div>
                                        <div class="d-flex gap-10 mt-8">
                                            <a href="{{ $user->learningUrl() }}" class="btn btn-outline-main rounded-pill">Lanjut Belajar</a>
                                            @if(!$lastAttempt->lulus && $attemptMateri)
                                                <a href="{{ route('materi.show', [$attemptMateri->bab, $attemptMateri->track, $attemptMateri->step]) }}" class="btn btn-outline-danger rounded-pill">Ulangi Materi</a>
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <p class="text-gray-400 mb-0">Belum ada evaluasi yang dikerjakan.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-6">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="mb-16">Ringkasan Evaluasi</h5>
                                <div class="row g-3">
                                    <div class="col-sm-4">
                                        <div class="p-12 rounded-12 bg-main-50 h-100">
                                            <div class="text-13 text-gray-400">Total Attempt</div>
                                            <div class="fw-semibold h4 mb-0">{{ number_format((int) ($stats->total ?? 0)) }}</div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="p-12 rounded-12 bg-success-50 h-100">
                                            <div class="text-13 text-gray-400">Rata-rata Skor</div>
                                            <div class="fw-semibold h4 mb-0">{{ number_format((float) ($stats->avg_skor ?? 0),2) }}</div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="p-12 rounded-12 bg-warning-50 h-100">
                                            <div class="text-13 text-gray-400">Lulus</div>
                                            <div class="fw-semibold h4 mb-0">{{ number_format((int) ($stats->lulus_count ?? 0)) }}</div>
                                        </div>
                                    </div>
                                </div>
                                <p class="text-13 text-gray-300 mt-16 mb-0">Data dihitung dari seluruh attempt evaluasi yang pernah kamu jalankan.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="dashboard-footer">
            <div class="flex-between flex-wrap gap-16">
                <p class="text-gray-300 text-13 fw-normal"> &copy; Copyright Edmate 2024, All Right Reserverd</p>
                <div class="flex-align flex-wrap gap-16">
                    <a href="#"
                        class="text-gray-300 text-13 fw-normal hover-text-main-600 hover-text-decoration-underline">License</a>
                    <a href="#"
                        class="text-gray-300 text-13 fw-normal hover-text-main-600 hover-text-decoration-underline">More
                        Themes</a>
                    <a href="#"
                        class="text-gray-300 text-13 fw-normal hover-text-main-600 hover-text-decoration-underline">Documentation</a>
                    <a href="#"
                        class="text-gray-300 text-13 fw-normal hover-text-main-600 hover-text-decoration-underline">Support</a>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function createChart(chartId, chartColor) {

            let currentYear = new Date().getFullYear();

            var options = {
                series: [{
                    name: 'series1',
                    data: [18, 25, 22, 40, 34, 55, 50, 60, 55, 65],
                }, ],
                chart: {
                    type: 'area',
                    width: 80,
                    height: 42,
                    sparkline: {
                        enabled: true // Remove whitespace
                    },

                    toolbar: {
                        show: false
                    },
                    padding: {
                        left: 0,
                        right: 0,
                        top: 0,
                        bottom: 0
                    }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth',
                    width: 1,
                    colors: [chartColor],
                    lineCap: 'round'
                },
                grid: {
                    show: true,
                    borderColor: 'transparent',
                    strokeDashArray: 0,
                    position: 'back',
                    xaxis: {
                        lines: {
                            show: false
                        }
                    },
                    yaxis: {
                        lines: {
                            show: false
                        }
                    },
                    row: {
                        colors: undefined,
                        opacity: 0.5
                    },
                    column: {
                        colors: undefined,
                        opacity: 0.5
                    },
                    padding: {
                        top: 0,
                        right: 0,
                        bottom: 0,
                        left: 0
                    },
                },
                fill: {
                    type: 'gradient',
                    colors: [chartColor], // Set the starting color (top color) here
                    gradient: {
                        shade: 'light', // Gradient shading type
                        type: 'vertical', // Gradient direction (vertical)
                        shadeIntensity: 0.5, // Intensity of the gradient shading
                        gradientToColors: [`${chartColor}00`], // Bottom gradient color (with transparency)
                        inverseColors: false, // Do not invert colors
                        opacityFrom: .5, // Starting opacity
                        opacityTo: 0.3, // Ending opacity
                        stops: [0, 100],
                    },
                },
                // Customize the circle marker color on hover
                markers: {
                    colors: [chartColor],
                    strokeWidth: 2,
                    size: 0,
                    hover: {
                        size: 8
                    }
                },
                xaxis: {
                    labels: {
                        show: false
                    },
                    categories: [`Jan ${currentYear}`, `Feb ${currentYear}`, `Mar ${currentYear}`, `Apr ${currentYear}`,
                        `May ${currentYear}`, `Jun ${currentYear}`, `Jul ${currentYear}`, `Aug ${currentYear}`,
                        `Sep ${currentYear}`, `Oct ${currentYear}`, `Nov ${currentYear}`, `Dec ${currentYear}`
                    ],
                    tooltip: {
                        enabled: false,
                    },
                },
                yaxis: {
                    labels: {
                        show: false
                    }
                },
                tooltip: {
                    x: {
                        format: 'dd/MM/yy HH:mm'
                    },
                },
            };

            var chart = new ApexCharts(document.querySelector(`#${chartId}`), options);
            chart.render();
        }

        // Call the function for each chart with the desired ID and color
        createChart('complete-course', '#2FB2AB');
        createChart('earned-certificate', '#27CFA7');
        createChart('course-progress', '#6142FF');
        createChart('community-support', '#FA902F');


        // =========================== Double Line Chart Start ===============================
        function createLineChart(chartId, chartColor) {
            var options = {
                series: [{
                        name: 'Study',
                        data: [3.6, 1.8, 3.8, 0, 2.4, 0.6, 8, 1.2, 2.8, 2.3, 4, 2],
                    },
                    {
                        name: 'Test',
                        data: [0.2, 4, 0, 6, 0.6, 4, 4, 8, 2.1, 5.6, 1.8, 3.6],
                    },
                ],
                chart: {
                    type: 'line',
                    width: '100%',
                    height: 350,
                    sparkline: {
                        enabled: false // Remove whitespace
                    },
                    toolbar: {
                        show: false
                    },
                    padding: {
                        left: 0,
                        right: 0,
                        top: 0,
                        bottom: 0
                    }
                },
                colors: ['#3D7FF9', chartColor], // Set the color of the series
                dataLabels: {
                    enabled: false,
                },
                stroke: {
                    curve: 'smooth',
                    width: 2,
                    colors: ["#3D7FF9", chartColor],
                    lineCap: 'round',
                },
                grid: {
                    show: true,
                    borderColor: '#E6E6E6',
                    strokeDashArray: 3,
                    position: 'back',
                    xaxis: {
                        lines: {
                            show: false
                        }
                    },
                    yaxis: {
                        lines: {
                            show: true
                        }
                    },
                    row: {
                        colors: undefined,
                        opacity: 0.5
                    },
                    column: {
                        colors: undefined,
                        opacity: 0.5
                    },
                    padding: {
                        top: 0,
                        right: 0,
                        bottom: 0,
                        left: 0
                    },
                },
                // Customize the circle marker color on hover
                markers: {
                    colors: ["#3D7FF9", chartColor],
                    strokeWidth: 3,
                    size: 0,
                    hover: {
                        size: 8
                    }
                },
                xaxis: {
                    labels: {
                        show: false
                    },
                    categories: [`Jan`, `Feb`, `Mar`, `Apr`, `May`, `Jun`, `Jul`, `Aug`, `Sep`, `Oct`, `Nov`, `Dec`],
                    tooltip: {
                        enabled: false,
                    },
                    labels: {
                        formatter: function(value) {
                            return value;
                        },
                        style: {
                            fontSize: "14px"
                        }
                    },
                },
                yaxis: {
                    labels: {
                        formatter: function(value) {
                            return "$" + value + "Hr";
                        },
                        style: {
                            fontSize: "14px"
                        }
                    },
                },
                tooltip: {
                    x: {
                        format: 'dd/MM/yy HH:mm'
                    },
                },
                legend: {
                    show: false,
                    position: 'top',
                    horizontalAlign: 'right',
                    offsetX: -10,
                    offsetY: -0
                }
            };

            var chart = new ApexCharts(document.querySelector(`#${chartId}`), options);
            chart.render();
        }
        createLineChart('doubleLineChart', '#27CFA7');
        // =========================== Double Line Chart End ===============================

        // ============================ Donut Chart Start ==========================
        var options = {
            series: [65.2, 25, 9.8],
            chart: {
                height: 270,
                type: 'donut',
            },
            colors: ['#3D7FF9', '#27CFA7', '#EA5455'],
            enabled: true, // Enable data labels
            formatter: function(val, opts) {
                return opts.w.config.series[opts.seriesIndex] + '%';
            },
            dropShadow: {
                enabled: false
            },
            plotOptions: {
                pie: {
                    donut: {
                        size: '55%' // Fixed slice width
                    }
                }
            },
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        width: "100%"
                    },
                    legend: {
                        show: false
                    }
                }
            }],
            legend: {
                position: 'right',
                offsetY: 0,
                height: 230,
                show: false
            }
        };

        var chart = new ApexCharts(document.querySelector("#activityDonutChart"), options);
        chart.render();
        // ============================ Donut Chart End ==========================
    </script>
    @endpush
</x-app-layout>