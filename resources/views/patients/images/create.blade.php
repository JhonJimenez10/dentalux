@extends('layouts.app')
@section('title', 'Subir Imágenes')

@section('content')

    @php
        $breadcrumbs = [
            ['label' => 'Pacientes', 'url' => route('patients.index')],
            ['label' => $patient->full_name, 'url' => route('patients.show', $patient)],
            ['label' => 'Imágenes', 'url' => route('patients.images.index', $patient)],
            ['label' => 'Subir', 'url' => '#'],
        ];
    @endphp

    <div class="page-header">
        <div>
            <h1>Subir Imágenes Clínicas</h1>
            <p>{{ $patient->full_name }}</p>
        </div>
        <a href="{{ route('patients.images.index', $patient) }}" class="btn btn-outline">
            Cancelar
        </a>
    </div>

    <div style="max-width:640px;">
        <form method="POST" action="{{ route('patients.images.store', $patient) }}" enctype="multipart/form-data"
            id="upload-form">
            @csrf

            {{-- Zona de drop --}}
            <div class="card" style="margin-bottom:18px;">
                <div class="card-header">
                    <h3 class="card-title">Seleccionar Archivos</h3>
                </div>
                <div class="card-body">

                    <div id="drop-zone"
                        style="border:2px dashed var(--border);border-radius:var(--radius-sm);
                         padding:36px 20px;text-align:center;cursor:pointer;
                         transition:all 0.2s;background:var(--bg);"
                        onclick="document.getElementById('file-input').click()" ondragover="handleDragOver(event)"
                        ondragleave="handleDragLeave(event)" ondrop="handleDrop(event)">

                        <svg width="48" height="48" fill="none" stroke="var(--primary)" viewBox="0 0 24 24"
                            style="margin:0 auto 12px;display:block;opacity:0.6;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                        </svg>
                        <div style="font-size:15px;font-weight:600;margin-bottom:6px;">
                            Arrastra archivos aquí
                        </div>
                        <div style="font-size:13px;color:var(--text-muted);margin-bottom:12px;">
                            o haz clic para seleccionar
                        </div>
                        <div style="font-size:12px;color:var(--text-muted);">
                            JPG, PNG, WebP o PDF · Máx. 10 MB por archivo · Hasta 10 archivos
                        </div>

                        <input type="file" name="images[]" id="file-input" accept="image/*,.pdf" multiple
                            style="display:none" onchange="previewFiles(this.files)">
                    </div>

                    {{-- Preview de archivos seleccionados --}}
                    <div id="preview-grid"
                        style="display:none;margin-top:14px;display:grid;
                         grid-template-columns:repeat(auto-fill,minmax(90px,1fr));
                         gap:8px;">
                    </div>

                </div>
            </div>

            {{-- Datos de las imágenes --}}
            <div class="card" style="margin-bottom:18px;">
                <div class="card-header">
                    <h3 class="card-title">Información</h3>
                </div>
                <div class="card-body">

                    <div class="form-grid cols-2">
                        <div class="form-group">
                            <label class="form-label">
                                Categoría <span class="required">*</span>
                            </label>
                            <select name="category" class="form-control" required>
                                <option value="">Seleccionar…</option>
                                <optgroup label="🦷 Radiografías">
                                    <option value="radiografia_periapical"
                                        {{ old('category') == 'radiografia_periapical' ? 'selected' : '' }}>
                                        Periapical
                                    </option>
                                    <option value="radiografia_panoramica"
                                        {{ old('category') == 'radiografia_panoramica' ? 'selected' : '' }}>
                                        Panorámica
                                    </option>
                                    <option value="radiografia_bitewing"
                                        {{ old('category') == 'radiografia_bitewing' ? 'selected' : '' }}>
                                        Bitewing (aleta de mordida)
                                    </option>
                                </optgroup>
                                <optgroup label="📷 Fotografías">
                                    <option value="fotografia_frontal"
                                        {{ old('category') == 'fotografia_frontal' ? 'selected' : '' }}>
                                        Frontal (cara completa)
                                    </option>
                                    <option value="fotografia_lateral"
                                        {{ old('category') == 'fotografia_lateral' ? 'selected' : '' }}>
                                        Lateral (perfil)
                                    </option>
                                    <option value="fotografia_intraoral"
                                        {{ old('category') == 'fotografia_intraoral' ? 'selected' : '' }}>
                                        Intraoral (boca abierta)
                                    </option>
                                    <option value="fotografia_extraoral"
                                        {{ old('category') == 'fotografia_extraoral' ? 'selected' : '' }}>
                                        Extraoral
                                    </option>
                                </optgroup>
                                <optgroup label="🔬 Otros">
                                    <option value="modelo_estudio" {{ old('category') == 'modelo_estudio' ? 'selected' : '' }}>
                                        Modelo de estudio
                                    </option>
                                    <option value="otro" {{ old('category') == 'otro' ? 'selected' : '' }}>
                                        Otro
                                    </option>
                                </optgroup>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Fecha de toma</label>
                            <input type="date" name="taken_at" class="form-control"
                                value="{{ old('taken_at', date('Y-m-d')) }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Título / Descripción</label>
                        <input type="text" name="title" class="form-control" value="{{ old('title') }}"
                            placeholder="Ej: Radiografía inicial, Control ortodoncia…">
                        <div class="form-hint">
                            Si dejas vacío se usará el nombre del archivo.
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Notas clínicas</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Observaciones sobre esta imagen…">{{ old('notes') }}</textarea>
                    </div>

                </div>
            </div>

            <div style="display:flex;gap:10px;justify-content:flex-end;">
                <a href="{{ route('patients.images.index', $patient) }}" class="btn btn-outline">Cancelar</a>
                <button type="submit" id="submit-btn" class="btn btn-primary" disabled>
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                    </svg>
                    <span id="submit-text">Subir Imágenes</span>
                </button>
            </div>

        </form>
    </div>

@endsection

@push('styles')
    <style>
        #drop-zone.drag-over {
            border-color: var(--primary);
            background: var(--primary-bg);
        }

        #preview-grid {
            display: grid !important;
        }

        .preview-item {
            position: relative;
            border-radius: 8px;
            overflow: hidden;
            aspect-ratio: 1;
            background: var(--bg);
            border: 1px solid var(--border);
        }

        .preview-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .preview-item .remove-btn {
            position: absolute;
            top: 3px;
            right: 3px;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: rgba(0, 0, 0, 0.6);
            color: #fff;
            border: none;
            cursor: pointer;
            font-size: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            line-height: 1;
        }
    </style>
@endpush

@push('scripts')
    <script>
        let selectedFiles = [];

        function previewFiles(files) {
            selectedFiles = Array.from(files);
            updatePreview();
        }

        function updatePreview() {
            const grid = document.getElementById('preview-grid');
            const btn = document.getElementById('submit-btn');
            const txt = document.getElementById('submit-text');
            grid.innerHTML = '';

            if (selectedFiles.length === 0) {
                btn.disabled = true;
                return;
            }

            btn.disabled = false;
            txt.textContent = selectedFiles.length > 1 ?
                `Subir ${selectedFiles.length} imágenes` :
                'Subir imagen';

            selectedFiles.forEach((file, idx) => {
                const div = document.createElement('div');
                div.className = 'preview-item';

                if (file.type.startsWith('image/')) {
                    const img = document.createElement('img');
                    img.src = URL.createObjectURL(file);
                    img.alt = file.name;
                    div.appendChild(img);
                } else {
                    div.innerHTML = `
                <div style="height:100%;display:flex;flex-direction:column;
                             align-items:center;justify-content:center;gap:4px;
                             padding:8px;">
                    <svg width="24" height="24" fill="none" stroke="#DC2626"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              stroke-width="1.5"
                              d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                    <span style="font-size:9px;color:var(--text-muted);
                                  text-align:center;overflow:hidden;
                                  text-overflow:ellipsis;white-space:nowrap;
                                  width:100%;">
                        ${file.name}
                    </span>
                </div>
            `;
                }

                // Botón quitar
                const rm = document.createElement('button');
                rm.type = 'button';
                rm.className = 'remove-btn';
                rm.textContent = '✕';
                rm.onclick = () => {
                    selectedFiles.splice(idx, 1);
                    updatePreview();
                    syncFileInput();
                };
                div.appendChild(rm);
                grid.appendChild(div);
            });

            syncFileInput();
        }

        function syncFileInput() {
            const dt = new DataTransfer();
            selectedFiles.forEach(f => dt.items.add(f));
            document.getElementById('file-input').files = dt.files;
        }

        // Drag and drop
        function handleDragOver(e) {
            e.preventDefault();
            document.getElementById('drop-zone').classList.add('drag-over');
        }

        function handleDragLeave(e) {
            document.getElementById('drop-zone').classList.remove('drag-over');
        }

        function handleDrop(e) {
            e.preventDefault();
            document.getElementById('drop-zone').classList.remove('drag-over');
            const files = e.dataTransfer.files;
            selectedFiles = Array.from(files);
            updatePreview();
        }

        // Progreso al subir
        document.getElementById('upload-form').addEventListener('submit', function() {
            const btn = document.getElementById('submit-btn');
            const txt = document.getElementById('submit-text');
            btn.disabled = true;
            txt.textContent = 'Subiendo…';
        });
    </script>
@endpush
