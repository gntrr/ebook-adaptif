@php
    $isEdit = isset($materi);
    $kontenType = old('konten_type', $materi->konten_type ?? 'html');
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
    <div class="col-md-4">
        <label class="form-label">Jenis Konten <span class="text-danger">*</span></label>
        <select name="konten_type" class="form-select bg-main-50 border-0" required>
            <option value="html" @selected($kontenType === 'html')>HTML / Teks</option>
            <option value="image" @selected($kontenType === 'image')>Gambar (Slide/PPT)</option>
        </select>
        <small class="text-gray-400">Pilih "Gambar" bila materi berasal dari slide atau tangkapan layar PPT.</small>
    </div>
    <div class="col-md-8">
        <label class="form-label">Path Gambar</label>
        <input type="text" name="konten_image_path" class="form-control bg-main-50 border-0"
            value="{{ old('konten_image_path', $materi->konten_image_path ?? '') }}"
            placeholder="Contoh: edmate/assets/images/thumbs/slide-intro.png">
        <small class="text-gray-400">Gunakan path relatif ke folder public. Kosongkan jika konten berupa teks.</small>
    </div>
    <div class="col-12">
        <label class="form-label">Konten HTML</label>
        <textarea name="konten" class="form-control bg-main-50 border-0" rows="8"
            placeholder="Isi konten dalam format HTML/Markdown">{{ old('konten', $materi->konten ?? '') }}</textarea>
        <small class="text-gray-400">Isi bagian ini hanya bila jenis konten adalah HTML.</small>
    </div>
</div>

<div class="d-flex justify-content-between align-items-center mt-24">
    <a href="{{ route('admin.materi.index') }}" class="btn btn-outline-secondary rounded-pill">Batal</a>
    <button type="submit" class="btn btn-main rounded-pill">{{ $isEdit ? 'Simpan Perubahan' : 'Simpan Materi' }}</button>
</div>
