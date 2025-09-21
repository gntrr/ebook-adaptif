@php($config = $config ?? ['palette' => [], 'solution' => [], 'labels' => []])
@php($paletteText = old('palette', implode(\"\n\", $config['palette'] ?? [])))
@php($solutionText = old('solution', implode(\"\n\", $config['solution'] ?? [])))
@php($labelsText = old('labels'))
@if (is_null($labelsText) && ! empty($config['labels'] ?? []))
    @php($labelsText = collect($config['labels'])->map(fn ($label, $key) => $key.' = '.$label)->implode("\n"))
@endif

<div class="row g-4">
    <div class="col-md-6">
        <label class="form-label">Materi <span class="text-danger">*</span></label>
        <select name="materi_id" class="form-select bg-main-50 border-0" required>
            <option value="">Pilih Materi</option>
            @foreach ($materiOptions as $option)
                <option value="{{ $option->id }}" @selected(old('materi_id', $evaluation->materi_id ?? null) == $option->id)>
                    Bab {{ $option->bab }} • Step {{ $option->step }} • {{ $option->judul }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Bobot</label>
        <input type="number" name="bobot" class="form-control bg-main-50 border-0" min="1" max="1000"
            value="{{ old('bobot', $evaluation->bobot ?? 100) }}">
        <small class="text-gray-400">Nilai default 100. Ubah bila diperlukan.</small>
    </div>
    <div class="col-12">
        <label class="form-label">Instruksi / Pertanyaan <span class="text-danger">*</span></label>
        <textarea name="pertanyaan" class="form-control bg-main-50 border-0" rows="3" placeholder="Tuliskan instruksi pengerjaan scratch.">{{ old('pertanyaan', $evaluation->pertanyaan ?? '') }}</textarea>
    </div>
    <div class="col-lg-4">
        <label class="form-label">Daftar Blok (Palette) <span class="text-danger">*</span></label>
        <textarea name="palette" class="form-control bg-main-50 border-0" rows="8" placeholder="Satu blok per baris">{{ $paletteText }}</textarea>
        <small class="text-gray-400">Contoh: <code>when_green_flag_clicked</code>, <code>move_10_steps</code>.</small>
    </div>
    <div class="col-lg-4">
        <label class="form-label">Urutan Benar <span class="text-danger">*</span></label>
        <textarea name="solution" class="form-control bg-main-50 border-0" rows="8" placeholder="Satu blok per baris sesuai urutan benar">{{ $solutionText }}</textarea>
        <small class="text-gray-400">Gunakan token yang sama seperti daftar blok.</small>
    </div>
    <div class="col-lg-4">
        <label class="form-label">Label Tampilan</label>
        <textarea name="labels" class="form-control bg-main-50 border-0" rows="8" placeholder="token = Label tampilan">{{ $labelsText }}</textarea>
        <small class="text-gray-400">Opsional. Format: <code>move_10_steps = Move 10 Steps</code>. Jika kosong, label dibuat otomatis.</small>
    </div>
    <div class="col-12">
        <label class="form-label">Hint</label>
        <textarea name="hint" class="form-control bg-main-50 border-0" rows="3" placeholder="Petunjuk tambahan untuk peserta (opsional)">{{ old('hint', $config['hint'] ?? '') }}</textarea>
    </div>
</div>

<div class="d-flex justify-content-between align-items-center mt-24">
    <a href="{{ route('admin.evaluasi.index') }}" class="btn btn-outline-secondary rounded-pill">Batal</a>
    <button type="submit" class="btn btn-main rounded-pill">Simpan</button>
</div>
