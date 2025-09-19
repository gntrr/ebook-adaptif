@php
    $isEdit = isset($materi);
@endphp

<div class="row g-4">
    <div class="col-md-3">
        <label class="form-label">Bab <span class="text-danger">*</span></label>
        <input type="number" name="bab" class="form-control bg-main-50 border-0" min="1" max="99"
            value="{{ old('bab', $materi->bab ?? '') }}" required>
    </div>
    <div class="col-md-3">
        <label class="form-label">Track</label>
        <select name="track" class="form-select bg-main-50 border-0">
            <option value="">Default</option>
            <option value="A" @selected(old('track', $materi->track ?? '') === 'A')>A</option>
            <option value="B" @selected(old('track', $materi->track ?? '') === 'B')>B</option>
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label">Step <span class="text-danger">*</span></label>
        <input type="number" name="step" class="form-control bg-main-50 border-0" min="1" max="5"
            value="{{ old('step', $materi->step ?? '') }}" required>
    </div>
    <div class="col-md-3">
        <label class="form-label">Tipe <span class="text-danger">*</span></label>
        <select name="tipe" class="form-select bg-main-50 border-0" required>
            @foreach ([
                'materi' => 'Materi Teori',
                'praktek' => 'Latihan/Praktek',
                'evaluasi' => 'Evaluasi Step',
                'evaluasi_bab' => 'Evaluasi Bab',
            ] as $value => $label)
                <option value="{{ $value }}" @selected(old('tipe', $materi->tipe ?? '') === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-12">
        <label class="form-label">Judul <span class="text-danger">*</span></label>
        <input type="text" name="judul" class="form-control bg-main-50 border-0"
            value="{{ old('judul', $materi->judul ?? '') }}" maxlength="120" required>
    </div>
    <div class="col-12">
        <label class="form-label">Konten</label>
        <textarea name="konten" class="form-control bg-main-50 border-0" rows="8" placeholder="Isi konten dalam format HTML/Markdown">{{ old('konten', $materi->konten ?? '') }}</textarea>
        <small class="text-gray-400">Konten mendukung HTML dasar. Gunakan editor favoritmu lalu tempelkan di sini.</small>
    </div>
</div>

<div class="d-flex justify-content-between align-items-center mt-24">
    <a href="{{ route('admin.materi.index') }}" class="btn btn-outline-secondary rounded-pill">Batal</a>
    <button type="submit" class="btn btn-main rounded-pill">{{ $isEdit ? 'Simpan Perubahan' : 'Simpan Materi' }}</button>
</div>
