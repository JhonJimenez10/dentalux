@extends('layouts.app')
@section('title', 'Imágenes — ' . $patient->full_name)

@section('topbar-actions')
    <a href="{{ route('patients.images.create', $patient) }}" class="btn btn-primary btn-sm">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        <span class="btn-hide-mobile">Subir Imágenes</span>
    </a>
@endsection

@section('content')

    @php
        $breadcrumbs = [
            ['label' => 'Pacientes', 'url' => route('patients.index')],
            ['label' => $patient->full_name, 'url' => route('patients.show', $patient)],
            ['label' => 'Imágenes', 'url' => '#'],
        ];

        $allImages = $images->flatten();

        $catColors = [
            'radiografia_periapical' => ['#EFF6FF', '#1D4ED8'],
            'radiografia_panoramica' => ['#EFF6FF', '#1D4ED8'],
            'radiografia_bitewing' => ['#EFF6FF', '#1D4ED8'],
            'fotografia_frontal' => ['#F0FDF4', '#059669'],
            'fotografia_lateral' => ['#F0FDF4', '#059669'],
            'fotografia_intraoral' => ['#F0FDF4', '#059669'],
            'fotografia_extraoral' => ['#F0FDF4', '#059669'],
            'modelo_estudio' => ['#FEF3C7', '#D97706'],
            'otro' => ['#F9FAFB', '#6B7280'],
        ];

        $catLabels = [
            'radiografia_periapical' => 'Radiografía Periapical',
            'radiografia_panoramica' => 'Radiografía Panorámica',
            'radiografia_bitewing' => 'Radiografía Bitewing',
            'fotografia_frontal' => 'Fotografía Frontal',
            'fotografia_lateral' => 'Fotografía Lateral',
            'fotografia_intraoral' => 'Fotografía Intraoral',
            'fotografia_extraoral' => 'Fotografía Extraoral',
            'modelo_estudio' => 'Modelo de Estudio',
            'otro' => 'Otro',
        ];

        $catIcons = [
            'radiografia_periapical' => '🦷',
            'radiografia_panoramica' => '🦷',
            'radiografia_bitewing' => '🦷',
            'fotografia_frontal' => '📷',
            'fotografia_lateral' => '📷',
            'fotografia_intraoral' => '📷',
            'fotografia_extraoral' => '📷',
            'modelo_estudio' => '🔬',
            'otro' => '📎',
        ];
    @endphp

    {{-- Cabecera paciente --}}
    <div
        style="background:var(--text);border-radius:var(--radius);padding:16px 18px;
            margin-bottom:20px;display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
        <div
            style="width:44px;height:44px;border-radius:50%;background:var(--primary);
                display:flex;align-items:center;justify-content:center;
                font-size:16px;font-weight:700;color:#fff;flex-shrink:0;">
            {{ $patient->initials }}
        </div>
        <div style="flex:1;min-width:0;">
            <div
                style="font-family:'Plus Jakarta Sans',sans-serif;font-size:17px;
                    font-weight:700;color:#fff;overflow:hidden;text-overflow:ellipsis;
                    white-space:nowrap;">
                {{ $patient->full_name }}
            </div>
            <div style="font-size:12px;color:rgba(255,255,255,0.45);margin-top:2px;">
                {{ $totalImages }} imagen(es) clínica(s) registrada(s)
            </div>
        </div>
        <a href="{{ route('patients.show', $patient) }}"
            style="display:inline-flex;align-items:center;gap:6px;padding:7px 12px;
              border-radius:var(--radius-sm);font-size:13px;font-weight:500;
              background:rgba(255,255,255,0.1);color:#fff;text-decoration:none;
              border:1px solid rgba(255,255,255,0.15);">
            ← Volver al paciente
        </a>
    </div>

    @if ($totalImages === 0)

        {{-- Estado vacío --}}
        <div class="card">
            <div style="padding:60px 20px;text-align:center;">
                <div
                    style="width:72px;height:72px;background:var(--bg);border-radius:50%;
                     display:flex;align-items:center;justify-content:center;
                     margin:0 auto 16px;">
                    <svg width="34" height="34" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        style="opacity:0.3;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0
                             012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0
                             00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <h3 style="font-size:16px;margin-bottom:8px;">Sin imágenes clínicas</h3>
                <p
                    style="font-size:13px;color:var(--text-muted);margin-bottom:20px;
                   max-width:360px;margin-left:auto;margin-right:auto;">
                    Sube radiografías, fotografías clínicas o modelos de estudio del paciente.
                </p>
                <a href="{{ route('patients.images.create', $patient) }}" class="btn btn-primary">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                    </svg>
                    Subir primera imagen
                </a>
            </div>
        </div>
    @else
        {{-- Filtro por categoría --}}
        <div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:16px;" id="filter-bar">
            <button class="filter-btn active" data-cat="all" onclick="filterCat('all',this)">
                Todas ({{ $totalImages }})
            </button>
            @foreach ($images as $cat => $catImages)
                @php $colors = $catColors[$cat] ?? ['#F9FAFB','#6B7280']; @endphp
                <button class="filter-btn" data-cat="{{ $cat }}" onclick="filterCat('{{ $cat }}',this)"
                    style="background:{{ $colors[0] }};color:{{ $colors[1] }};
                   border-color:{{ $colors[1] }}33;">
                    {{ $catLabels[$cat] ?? ucfirst($cat) }} ({{ $catImages->count() }})
                </button>
            @endforeach
        </div>

        {{-- Grid de imágenes por categoría --}}
        @foreach ($images as $cat => $catImages)
            @php $colors = $catColors[$cat] ?? ['#F9FAFB','#6B7280']; @endphp

            <div class="cat-section" data-cat="{{ $cat }}" style="margin-bottom:24px;">
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:12px;">
                    <div
                        style="height:2px;width:20px;background:{{ $colors[1] }};
                     border-radius:1px;">
                    </div>
                    <span
                        style="font-size:12px;font-weight:700;color:{{ $colors[1] }};
                      text-transform:uppercase;letter-spacing:0.5px;">
                        {{ $catLabels[$cat] ?? ucfirst($cat) }}
                    </span>
                    <span style="font-size:12px;color:var(--text-muted);">
                        · {{ $catImages->count() }} imagen(es)
                    </span>
                    <div style="flex:1;height:1px;background:var(--border);"></div>
                </div>

                <div
                    style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));
                 gap:14px;">
                    @foreach ($catImages as $img)
                        @php
                            $imgPath = storage_path('app/public/' . $img->file_path);
                            $imgBase64 = '';
                            $imgMime = 'image/jpeg';
                            if ($img->is_image && file_exists($imgPath)) {
                                $imgMime = mime_content_type($imgPath);
                                $imgBase64 =
                                    'data:' . $imgMime . ';base64,' . base64_encode(file_get_contents($imgPath));
                            }
                        @endphp

                        <div class="img-card" data-id="{{ $img->id }}" data-b64="{{ $imgBase64 }}"
                            data-title="{{ addslashes($img->title) }}"
                            data-category="{{ addslashes($catLabels[$cat] ?? ucfirst($cat)) }}"
                            data-date="{{ $img->taken_at?->format('d/m/Y') ?? '' }}"
                            data-is-image="{{ $img->is_image ? '1' : '0' }}"
                            data-delete-url="{{ route('patients.images.destroy', [$patient, $img]) }}"
                            style="background:var(--surface);border-radius:var(--radius);
                    border:1px solid var(--border);overflow:hidden;
                    transition:all 0.2s;cursor:pointer;"
                            onclick="openLightbox(this)"
                            onmouseover="this.style.transform='translateY(-2px)';
                          this.style.boxShadow='0 8px 24px rgba(0,0,0,0.12)'"
                            onmouseout="this.style.transform='translateY(0)';
                         this.style.boxShadow='none'">

                            {{-- Thumbnail --}}
                            <div style="height:140px;background:var(--bg);position:relative;overflow:hidden;">
                                @if ($img->is_image && $imgBase64)
                                    <img src="{{ $imgBase64 }}" alt="{{ $img->title }}"
                                        style="width:100%;height:100%;object-fit:cover;" loading="lazy">
                                @elseif($img->is_image && !$imgBase64)
                                    <div
                                        style="height:100%;display:flex;flex-direction:column;
                             align-items:center;justify-content:center;gap:6px;">
                                        <span style="font-size:32px;">🖼️</span>
                                        <span style="font-size:10px;color:var(--text-muted);">
                                            Sin previsualización
                                        </span>
                                    </div>
                                @else
                                    {{-- PDF --}}
                                    <div
                                        style="height:100%;display:flex;flex-direction:column;
                             align-items:center;justify-content:center;gap:8px;">
                                        <svg width="40" height="40" fill="none" stroke="#DC2626"
                                            viewBox="0 0 24 24" style="opacity:0.7;">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1
                                     1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                        <span style="font-size:11px;color:var(--text-muted);font-weight:600;">
                                            PDF
                                        </span>
                                    </div>
                                @endif

                                {{-- Badge categoría --}}
                                <div
                                    style="position:absolute;top:6px;left:6px;
                             background:{{ $colors[0] }};color:{{ $colors[1] }};
                             font-size:10px;font-weight:700;padding:2px 7px;
                             border-radius:100px;backdrop-filter:blur(4px);">
                                    {{ $catIcons[$cat] ?? '📎' }}
                                </div>

                                {{-- Overlay zoom --}}
                                <div class="img-overlay"
                                    style="position:absolute;inset:0;background:rgba(0,0,0,0);
                            display:flex;align-items:center;justify-content:center;
                            transition:background 0.2s;">
                                    <svg class="zoom-icon" width="28" height="28" fill="none" stroke="#fff"
                                        viewBox="0 0 24 24" style="opacity:0;transition:opacity 0.2s;">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                            </div>

                            {{-- Info --}}
                            <div style="padding:10px 12px;">
                                <div
                                    style="font-size:13px;font-weight:600;white-space:nowrap;
                             overflow:hidden;text-overflow:ellipsis;margin-bottom:3px;">
                                    {{ $img->title }}
                                </div>
                                <div
                                    style="font-size:11px;color:var(--text-muted);
                             display:flex;justify-content:space-between;align-items:center;">
                                    <span>{{ $img->taken_at?->format('d/m/Y') ?? 'Sin fecha' }}</span>
                                    <span>{{ $img->file_size_human }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach

    @endif

    {{-- ═══ LIGHTBOX ═══ --}}
    <div id="lightbox"
        style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.94);
            z-index:9999;flex-direction:column;align-items:center;
            justify-content:center;padding:0;">

        {{-- Barra superior --}}
        <div
            style="position:absolute;top:0;left:0;right:0;padding:16px 20px;
                 background:linear-gradient(to bottom,rgba(0,0,0,0.8),transparent);
                 display:flex;align-items:center;justify-content:space-between;
                 z-index:10;flex-wrap:wrap;gap:8px;">
            <div style="min-width:0;flex:1;">
                <div id="lb-title"
                    style="font-size:15px;font-weight:600;color:#fff;
                         white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                </div>
                <div id="lb-meta" style="font-size:12px;color:rgba(255,255,255,0.5);margin-top:2px;">
                </div>
            </div>
            <div style="display:flex;gap:8px;align-items:center;flex-shrink:0;">

                {{-- Descargar --}}
                <button onclick="downloadImage()"
                    style="display:inline-flex;align-items:center;gap:5px;padding:7px 12px;
                           border-radius:var(--radius-sm);background:rgba(255,255,255,0.12);
                           color:#fff;font-size:13px;border:1px solid rgba(255,255,255,0.15);
                           cursor:pointer;">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Descargar
                </button>

                {{-- Eliminar --}}
                <form id="lb-delete-form" method="POST" style="margin:0;">
                    @csrf @method('DELETE')
                    <button type="submit" onclick="return confirm('¿Eliminar esta imagen? No se puede deshacer.')"
                        style="display:inline-flex;align-items:center;gap:5px;
                               padding:7px 12px;border-radius:var(--radius-sm);
                               background:rgba(220,38,38,0.2);color:#FCA5A5;
                               font-size:13px;border:1px solid rgba(220,38,38,0.3);
                               cursor:pointer;">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0
                                     01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0
                                     00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Eliminar
                    </button>
                </form>

                {{-- Cerrar --}}
                <button onclick="closeLightbox()"
                    style="width:36px;height:36px;border-radius:50%;
                           background:rgba(255,255,255,0.12);color:#fff;
                           border:1px solid rgba(255,255,255,0.15);
                           cursor:pointer;font-size:18px;
                           display:flex;align-items:center;justify-content:center;">
                    ✕
                </button>
            </div>
        </div>

        {{-- Contenido imagen --}}
        <div style="flex:1;display:flex;align-items:center;justify-content:center;
                 width:100%;padding:70px 20px 20px;"
            id="lb-content">
            <img id="lb-image" src="" alt=""
                style="max-width:100%;max-height:calc(100vh - 160px);
                    object-fit:contain;border-radius:8px;display:none;">
            <div id="lb-pdf" style="display:none;text-align:center;color:#fff;">
                <svg width="64" height="64" fill="none" stroke="#DC2626" viewBox="0 0 24 24"
                    style="margin:0 auto 16px;display:block;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1
                             1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                </svg>
                <p style="font-size:15px;margin-bottom:12px;">Archivo PDF</p>
                <button onclick="downloadImage()"
                    style="padding:10px 20px;background:var(--primary);color:#fff;
                           border:none;border-radius:8px;cursor:pointer;font-size:14px;">
                    Descargar PDF
                </button>
            </div>
        </div>
    </div>

@endsection

@push('styles')
    <style>
        .filter-btn {
            padding: 6px 14px;
            border-radius: 100px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            border: 1px solid var(--border);
            background: var(--surface);
            color: var(--text-muted);
            transition: all 0.15s;
            font-family: inherit;
        }

        .filter-btn.active,
        .filter-btn:hover {
            background: var(--text);
            color: #fff;
            border-color: var(--text);
        }

        .img-card:hover .img-overlay {
            background: rgba(0, 0, 0, 0.35) !important;
        }

        .img-card:hover .zoom-icon {
            opacity: 1 !important;
        }

        @media (max-width: 480px) {
            div[style*="minmax(180px"] {
                grid-template-columns: repeat(2, 1fr) !important;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        const patientId = {{ $patient->id }};

        // Estado del lightbox
        let currentB64 = null;
        let currentImgId = null;
        let currentDeleteUrl = null;
        let currentIsImage = false;
        let currentFileName = null;

        // ── Filtro por categoría ──────────────────────────────────────
        function filterCat(cat, btn) {
            document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            document.querySelectorAll('.cat-section').forEach(s => {
                s.style.display = (cat === 'all' || s.dataset.cat === cat) ? 'block' : 'none';
            });
        }

        // ── Abrir lightbox ────────────────────────────────────────────
        function openLightbox(card) {
            currentImgId = card.dataset.id;
            currentB64 = card.dataset.b64;
            currentDeleteUrl = card.dataset.deleteUrl;
            currentIsImage = card.dataset.isImage === '1';
            currentFileName = 'imagen-clinica-' + currentImgId;

            const lb = document.getElementById('lightbox');
            lb.style.display = 'flex';
            document.body.style.overflow = 'hidden';

            document.getElementById('lb-title').textContent = card.dataset.title;
            document.getElementById('lb-meta').textContent =
                card.dataset.category + (card.dataset.date ? ' · ' + card.dataset.date : '');

            const img = document.getElementById('lb-image');
            const pdf = document.getElementById('lb-pdf');

            if (currentIsImage && currentB64) {
                img.src = currentB64;
                img.style.display = 'block';
                pdf.style.display = 'none';

                // Obtener extensión del mime
                const match = currentB64.match(/^data:image\/(\w+);base64,/);
                const ext = match ? match[1].replace('jpeg', 'jpg') : 'jpg';
                currentFileName = currentFileName + '.' + ext;
            } else {
                img.style.display = 'none';
                pdf.style.display = 'block';
                currentFileName = currentFileName + '.pdf';
            }

            // Configurar form de eliminar
            document.getElementById('lb-delete-form').action = currentDeleteUrl;
        }

        // ── Cerrar lightbox ───────────────────────────────────────────
        function closeLightbox() {
            document.getElementById('lightbox').style.display = 'none';
            document.getElementById('lb-image').src = '';
            document.body.style.overflow = '';
            currentB64 = null;
            currentImgId = null;
            currentDeleteUrl = null;
        }

        // ── Descargar imagen desde base64 ────────────────────────────
        function downloadImage() {
            if (!currentB64) return;

            const a = document.createElement('a');
            a.href = currentB64;
            a.download = currentFileName || 'imagen-clinica';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        }

        // ── Cerrar con ESC o clic en fondo ───────────────────────────
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') closeLightbox();
        });

        document.getElementById('lightbox').addEventListener('click', function(e) {
            if (e.target === this) closeLightbox();
        });
    </script>
@endpush
