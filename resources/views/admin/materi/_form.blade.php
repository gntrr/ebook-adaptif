@php
    $isEdit = isset($materi);
    $kontenType = old('konten_type', $materi->konten_type ?? 'html');
    $initialHtml = old('konten', $materi->konten ?? '');
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
        <select name="konten_type" class="form-select bg-main-50 border-0 js-konten-type" required>
            <option value="html" @selected($kontenType === 'html')>HTML / Teks</option>
            <option value="image" @selected($kontenType === 'image')>Gambar (Slide/PPT)</option>
        </select>
        <small class="text-gray-400">Pilih "Gambar" bila materi berasal dari slide atau tangkapan layar PPT.</small>
    </div>
    <div class="col-md-8 js-image-field {{ $kontenType === 'image' ? '' : 'd-none' }}">
        <label class="form-label">Path Gambar</label>
        <input type="text" name="konten_image_path" class="form-control bg-main-50 border-0"
            value="{{ old('konten_image_path', $materi->konten_image_path ?? '') }}"
            placeholder="Contoh: storage/materi/slide-intro.png">
        <small class="text-gray-400">Isi path relatif atau unggah file di bawah ini.</small>
        <div class="mt-3">
            <label class="form-label">Upload Gambar</label>
            <input type="file" name="konten_image_file" class="form-control bg-main-50 border-0" accept="image/*">
            <small class="text-gray-400">Format: JPG/PNG, maks 2MB.</small>
        </div>
    </div>
    <div class="col-12 js-html-field {{ $kontenType === 'html' ? '' : 'd-none' }}">
        <label class="form-label">Konten HTML</label>
        <input type="hidden" name="konten" id="konten-input" value="{{ old('konten', $materi->konten ?? '') }}">
        <div id="konten-editor" class="bg-white border rounded-4 p-3 js-rich-editor">{!! $initialHtml !!}</div>
        <small class="text-gray-400">Gunakan editor ini untuk menulis materi berbasis teks.</small>
    </div>
</div>

<div class="d-flex justify-content-between align-items-center mt-24">
    <a href="{{ route('admin.materi.index') }}" class="btn btn-outline-gray rounded-pill">Batal</a>
    <button type="submit" class="btn btn-main rounded-pill">{{ $isEdit ? 'Simpan Perubahan' : 'Simpan Materi' }}</button>
</div>

@once
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const typeSelect = document.querySelector('.js-konten-type');
                const htmlField = document.querySelector('.js-html-field');
                const imageField = document.querySelector('.js-image-field');
                const toggleFields = () => {
                    if (!typeSelect) return;
                    const value = typeSelect.value;
                    if (value === 'image') {
                        imageField?.classList.remove('d-none');
                        htmlField?.classList.add('d-none');
                    } else {
                        htmlField?.classList.remove('d-none');
                        imageField?.classList.add('d-none');
                    }
                };
                typeSelect?.addEventListener('change', toggleFields);
                toggleFields();

                const editorContainer = document.querySelector('#konten-editor');
                const kontenInput = document.querySelector('#konten-input');
                if (window.Quill && editorContainer && kontenInput) {
                    const quill = new Quill(editorContainer, {
                        theme: 'snow',
                        placeholder: 'Tulis konten materi di sini...'
                    });
                    quill.root.innerHTML = kontenInput.value || '';
                    quill.on('text-change', function () {
                        kontenInput.value = quill.root.innerHTML.trim();
                    });
                } else if (editorContainer && kontenInput) {
                    editorContainer.setAttribute('contenteditable', 'true');
                    editorContainer.addEventListener('input', function () {
                        kontenInput.value = editorContainer.innerHTML.trim();
                    });
                }
            });
        </script>
    @endpush
@endonce

