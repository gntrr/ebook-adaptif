@if (session('status'))
    <div class="alert alert-success d-flex align-items-center gap-2" role="alert">
        <i class="ph ph-check-circle"></i>
        <span>{{ session('status') }}</span>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger d-flex align-items-center gap-2" role="alert">
        <i class="ph ph-warning-circle"></i>
        <span>{{ session('error') }}</span>
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger" role="alert">
        <div class="fw-semibold mb-2">Terjadi kesalahan:</div>
        <ul class="mb-0 ps-3">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
