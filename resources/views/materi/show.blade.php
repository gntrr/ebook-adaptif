<x-app-layout>
    <div class="dashboard-main-wrapper">
        @include('layouts.topnav')

        <div class="dashboard-body">
            <div class="container-fluid">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-16 mb-24">
                    <div>
                        <h1 class="h3 mb-8">Bab {{ $bab }} • Step {{ $step }}</h1>
                        <p class="text-gray-400 mb-0">Track: {{ $track ? 'Track ' . $track : 'Jalur umum' }}</p>
                    </div>
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary rounded-pill">Kembali ke Dashboard</a>
                </div>

                @if (session('status'))
                    <div class="alert alert-info d-flex align-items-center gap-10" role="alert">
                        <i class="ph ph-info"></i>
                        <span>{{ session('status') }}</span>
                    </div>
                @endif

                @foreach ($items as $item)
                    @php($opsi = $item->evaluasis)
                    <div class="card mb-24">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start gap-16 mb-16">
                                <div>
                                    <h2 class="h4 mb-4">{{ $item->judul }}</h2>
                                    <span class="badge bg-main-100 text-main-600 text-uppercase">{{ str_replace('_', ' ', $item->tipe) }}</span>
                                </div>
                                <span class="text-13 text-gray-300">Terakhir diperbarui {{ $item->updated_at?->format('d M Y H:i') ?? 'baru' }}</span>
                            </div>

                            @if ($item->konten_type === 'image' && $item->konten_image_path)
                                <div class="text-center">
                                    <img src="{{ asset($item->konten_image_path) }}" alt="{{ $item->judul }}" class="img-fluid rounded-4">
                                    @if (! empty($item->konten))
                                        <p class="text-gray-400 mt-12 mb-0">{!! $item->konten !!}</p>
                                    @endif
                                </div>
                            @else
                                <div class="lesson-content wysiwyg-content">
                                    {!! $item->konten !!}
                                </div>
                            @endif

                            @if ($opsi->isNotEmpty())
                                <div class="mt-24">
                                    <h3 class="h5 mb-12">Latihan Scratch</h3>
                                    <ol class="d-flex flex-column gap-16 ps-3">
                                        @foreach ($opsi as $question)
                                            @php($config = $question->opsi ?? [])
                                            <li>
                                                <p class="fw-semibold mb-12">{{ $question->pertanyaan }}</p>
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <h6 class="text-13 text-uppercase text-gray-400">Palette</h6>
                                                        <ul class="list-unstyled d-flex flex-column gap-6 mb-0">
                                                            @foreach (($config['palette'] ?? []) as $block)
                                                                <li><span class="badge bg-main-100 text-main-600">{{ $config['labels'][$block] ?? $block }}</span></li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <h6 class="text-13 text-uppercase text-gray-400">Urutan Benar</h6>
                                                        <ol class="d-flex flex-column gap-4 ps-3 mb-0">
                                                            @foreach (($config['solution'] ?? []) as $block)
                                                                <li>{{ $config['labels'][$block] ?? $block }}</li>
                                                            @endforeach
                                                        </ol>
                                                    </div>
                                                </div>
                                                @if (! empty($config['hint'] ?? ''))
                                                    <div class="alert alert-info d-flex align-items-start gap-8 mt-12" role="alert">
                                                        <i class="ph ph-lightbulb"></i>
                                                        <span>{{ $config['hint'] }}</span>
                                                    </div>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ol>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
