@extends('layouts.app')
@section('title', 'Nuevo Presupuesto')

@section('content')

    @php
        $breadcrumbs = [
            ['label' => 'Pacientes', 'url' => route('patients.index')],
            ['label' => $patient->full_name, 'url' => route('patients.show', $patient)],
            ['label' => 'Presupuesto', 'url' => '#'],
        ];
    @endphp

    <div class="page-header">
        <div>
            <h1>Nuevo Presupuesto</h1>
            <p>{{ $patient->full_name }}</p>
        </div>
        <a href="{{ route('patients.show', $patient) }}" class="btn btn-outline">Cancelar</a>
    </div>

    <form method="POST" action="{{ route('patients.budgets.store', $patient) }}" id="budget-form">
        @csrf

        <div class="budget-create-grid">

            {{-- ── Columna izquierda ── --}}
            <div class="budget-left-col">

                {{-- Info general --}}
                <div class="card" style="margin-bottom:16px;">
                    <div class="card-header">
                        <h3 class="card-title">
                            <span style="display:inline-flex;align-items:center;gap:8px;">
                                <span
                                    style="width:24px;height:24px;background:var(--primary-light);
                                     border-radius:6px;display:inline-flex;align-items:center;
                                     justify-content:center;flex-shrink:0;">
                                    <svg width="13" height="13" fill="none" stroke="var(--primary)"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </span>
                                Información General
                            </span>
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="form-grid cols-2">
                            <div class="form-group" style="margin-bottom:0;">
                                <label class="form-label">Fecha <span class="required">*</span></label>
                                <input type="date" name="budget_date" class="form-control"
                                    value="{{ old('budget_date', date('Y-m-d')) }}" required>
                            </div>
                            <div class="form-group" style="margin-bottom:0;">
                                <label class="form-label">Dentista <span class="required">*</span></label>
                                <select name="user_id" class="form-control" required>
                                    @foreach ($dentists as $d)
                                        <option value="{{ $d->id }}"
                                            {{ old('user_id', auth()->id()) == $d->id ? 'selected' : '' }}>
                                            {{ $d->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Odontograma --}}
                <div class="card" style="margin-bottom:16px;">
                    <div class="card-header" style="flex-wrap:wrap;gap:8px;">
                        <h3 class="card-title">Odontograma</h3>
                        <button type="button" class="btn btn-ghost btn-sm" onclick="clearOdontogram()">
                            Limpiar todo
                        </button>
                    </div>
                    <div class="card-body" style="padding:14px;">

                        {{-- Toolbar de símbolos --}}
                        <div
                            style="background:var(--bg);border-radius:var(--radius-sm);
                     padding:12px 14px;margin-bottom:12px;border:1px solid var(--border);">
                            <div
                                style="font-size:11px;font-weight:700;color:var(--text-muted);
                         text-transform:uppercase;letter-spacing:0.4px;margin-bottom:8px;">
                                Selecciona un símbolo y haz clic en la superficie del diente
                            </div>
                            <div id="symbol-toolbar" style="display:flex;flex-wrap:wrap;gap:5px;"></div>
                        </div>

                        {{-- Leyenda de colores --}}
                        <div
                            style="display:flex;gap:16px;margin-bottom:12px;font-size:11px;
                     color:var(--text-muted);flex-wrap:wrap;">
                            <div style="display:flex;align-items:center;gap:6px;">
                                <span
                                    style="width:12px;height:12px;border-radius:50%;background:#FEF2F2;
                              border:1.5px solid #DC2626;flex-shrink:0;display:inline-block;">
                                </span>
                                <strong style="color:#DC2626;">Rojo</strong> — pendiente / patología
                            </div>
                            <div style="display:flex;align-items:center;gap:6px;">
                                <span
                                    style="width:12px;height:12px;border-radius:50%;background:#EFF6FF;
                              border:1.5px solid #1D4ED8;flex-shrink:0;display:inline-block;">
                                </span>
                                <strong style="color:#1D4ED8;">Azul</strong> — ya realizado / otro doctor
                            </div>
                        </div>

                        {{-- Dientes --}}
                        <div style="overflow-x:auto;-webkit-overflow-scrolling:touch;padding-bottom:4px;">
                            <div id="odo-root" style="min-width:560px;padding:2px 4px;"></div>
                        </div>

                        <div
                            style="margin-top:10px;font-size:11px;color:var(--text-muted);
                     padding:8px 12px;background:var(--bg);border-radius:var(--radius-sm);
                     border:1px solid var(--border);">
                            💡 Toca de nuevo una superficie marcada para quitarla.
                        </div>

                        <input type="hidden" name="odontogram" id="odontogram-input">
                    </div>
                </div>

                {{-- Servicios --}}
                <div class="card" style="margin-bottom:16px;">
                    <div class="card-header">
                        <h3 class="card-title">Servicios / Tratamientos</h3>
                        <button type="button" class="btn btn-primary btn-sm" onclick="addItem()">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Agregar
                        </button>
                    </div>
                    <div class="card-body">
                        <div style="margin-bottom:14px;">
                            <div
                                style="font-size:11px;font-weight:700;color:var(--text-muted);
                                 text-transform:uppercase;letter-spacing:0.4px;
                                 margin-bottom:8px;">
                                Agregar rápido</div>
                            <div style="display:flex;flex-wrap:wrap;gap:5px;">
                                @foreach ([
            'Profilaxis' => 20,
            'Restauración' => 15,
            'Exodoncia' => 15,
            'Ortodoncia' => 1000,
            'Sellante' => 12,
            'Detartraje' => 25,
            'Endodoncia U.' => 120,
            'Endodoncia M.' => 180,
            'Corona' => 200,
            'Implante' => 800,
        ] as $name => $price)
                                    <button type="button" class="btn btn-outline btn-sm"
                                        onclick="addItem('{{ $name }}', 1, {{ $price }})">
                                        {{ $name }}
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        <div id="items-header"
                            style="display:none;grid-template-columns:1fr 56px 90px 30px;
                            gap:6px;margin-bottom:6px;">
                            <div
                                style="font-size:10px;font-weight:700;color:var(--text-muted);
                                 text-transform:uppercase;">
                                Descripción</div>
                            <div
                                style="font-size:10px;font-weight:700;color:var(--text-muted);
                                 text-transform:uppercase;text-align:center;">
                                Cant.</div>
                            <div
                                style="font-size:10px;font-weight:700;color:var(--text-muted);
                                 text-transform:uppercase;text-align:right;">
                                P. Unit.</div>
                            <div></div>
                        </div>

                        <div id="items-container"></div>

                        <div
                            style="border-top:2px solid var(--border);margin-top:12px;
                             padding-top:12px;">
                            <div
                                style="display:flex;justify-content:space-between;
                                 font-size:13px;margin-bottom:7px;">
                                <span class="text-muted">Subtotal</span>
                                <span id="display-subtotal" style="font-weight:600;">$0.00</span>
                            </div>
                            <div
                                style="display:flex;justify-content:space-between;
                                 align-items:center;margin-bottom:8px;">
                                <span style="font-size:13px;" class="text-muted">Descuento</span>
                                <div style="display:flex;align-items:center;gap:5px;">
                                    <span style="color:var(--text-muted);font-weight:600;">$</span>
                                    <input type="number" name="discount" id="discount-input" class="form-control"
                                        style="width:85px;text-align:right;" step="0.01" min="0"
                                        value="0" oninput="recalculate()">
                                </div>
                            </div>
                            <div
                                style="display:flex;justify-content:space-between;
                                 padding-top:10px;border-top:2px solid var(--border);">
                                <span style="font-size:16px;font-weight:700;">TOTAL</span>
                                <span id="display-total" style="font-size:22px;font-weight:700;color:var(--primary);">
                                    $0.00
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Notas --}}
                <div class="card" style="margin-bottom:16px;">
                    <div class="card-header">
                        <h3 class="card-title">Notas</h3>
                    </div>
                    <div class="card-body">
                        <textarea name="notes" class="form-control" rows="3"
                            placeholder="Condiciones del tratamiento, indicaciones…">{{ old('notes') }}</textarea>
                    </div>
                </div>

            </div>

            {{-- ── Columna derecha ── --}}
            <div class="budget-right-col">

                {{-- Plan de pago --}}
                <div class="card" style="margin-bottom:14px;">
                    <div class="card-header">
                        <h3 class="card-title">Plan de Pago</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label class="form-label">Entrada</label>
                            <div style="position:relative;">
                                <span
                                    style="position:absolute;left:12px;top:50%;
                                     transform:translateY(-50%);font-weight:700;
                                     color:var(--text-muted);">$</span>
                                <input type="number" name="initial_payment" class="form-control"
                                    style="padding-left:26px;" step="0.01" min="0"
                                    value="{{ old('initial_payment', 0) }}" placeholder="0.00">
                            </div>
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Mensualidad</label>
                            <div style="position:relative;">
                                <span
                                    style="position:absolute;left:12px;top:50%;
                                     transform:translateY(-50%);font-weight:700;
                                     color:var(--text-muted);">$</span>
                                <input type="number" name="monthly_payment" class="form-control"
                                    style="padding-left:26px;" step="0.01" min="0"
                                    value="{{ old('monthly_payment', 0) }}" placeholder="0.00">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Firma --}}
                <div class="card" style="margin-bottom:14px;">
                    <div class="card-header">
                        <h3 class="card-title">Firma del Paciente</h3>
                        <span
                            style="font-size:11px;color:var(--text-muted);background:var(--bg);
                              padding:3px 8px;border-radius:100px;">Opcional</span>
                    </div>
                    <div class="card-body" style="padding:14px;">
                        <div id="sig-container"
                            style="border:2px dashed var(--border);border-radius:var(--radius-sm);
                            background:#FAFAFA;cursor:crosshair;transition:border-color 0.2s;">
                            <canvas id="sig-canvas"
                                style="display:block;width:100%;height:160px;
                                   touch-action:none;border-radius:var(--radius-sm);">
                            </canvas>
                        </div>
                        <div style="display:flex;align-items:center;gap:8px;margin-top:8px;">
                            <button type="button" class="btn btn-ghost btn-sm" onclick="clearSignature()">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    style="width:13px;height:13px;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Limpiar
                            </button>
                            <span id="sig-status" style="font-size:12px;color:var(--text-muted);margin-left:auto;">
                                Sin firma
                            </span>
                        </div>
                        <input type="hidden" name="patient_signature" id="sig-input">
                    </div>
                </div>

                {{-- Nota validez --}}
                <div
                    style="background:var(--bg);border-radius:var(--radius-sm);padding:12px 14px;
                     font-size:12px;color:var(--text-muted);text-align:center;
                     line-height:1.5;margin-bottom:14px;">
                    Los valores tienen validez por 90 días a partir de la fecha del presupuesto.
                </div>

                {{-- Botón guardar — SIEMPRE al final después de la firma --}}
                <button type="submit" class="btn btn-primary w-full"
                    style="justify-content:center;padding:14px;font-size:15px;" onclick="prepareSubmit()">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Guardar Presupuesto
                </button>

            </div>
        </div>

    </form>
@endsection

@push('styles')
    <style>
        .budget-create-grid {
            display: grid;
            grid-template-columns: 1fr 300px;
            gap: 20px;
            align-items: start;
        }

        @media (max-width: 768px) {
            .budget-create-grid {
                display: flex !important;
                flex-direction: column !important;
                gap: 0 !important;
            }

            .budget-left-col {
                order: 1;
                width: 100%;
            }

            .budget-right-col {
                order: 2;
                width: 100%;
                margin-top: 16px;
            }
        }

        #sig-container:hover {
            border-color: var(--primary);
        }

        #sig-container.has-sig {
            border-color: var(--success);
            border-style: solid;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
    <script>
        // ══════════════════════════════════════════
        //  ODONTOGRAMA — símbolo por superficie
        //  Toggle: clic para poner, clic de nuevo para quitar
        // ══════════════════════════════════════════


        const SYMS = [{
                id: 'sellante_n',
                label: 'Sellante nec.',
                color: 'red',
                shape: 'bracket'
            },
            {
                id: 'sellante_r',
                label: 'Sellante real.',
                color: 'blue',
                shape: 'bracket'
            },
            {
                id: 'extraccion',
                label: 'Extracción',
                color: 'red',
                shape: 'x'
            },
            {
                id: 'perdida_c',
                label: 'Pérd. caries',
                color: 'blue',
                shape: 'x'
            },
            {
                id: 'perdida_o',
                label: 'Pérd. otra causa',
                color: 'red',
                shape: 'vline'
            },
            {
                id: 'endodoncia',
                label: 'Endodoncia',
                color: 'red',
                shape: 'tri'
            },
            {
                id: 'corona',
                label: 'Corona',
                color: 'red',
                shape: 'dot'
            },
            {
                id: 'obturado',
                label: 'Obturado',
                color: 'blue',
                shape: 'circ'
            },
            {
                id: 'caries',
                label: 'Caries',
                color: 'red',
                shape: 'circ'
            },
            {
                id: 'protesis_f',
                label: 'Prót. fija',
                color: 'red',
                shape: 'dash'
            },
            {
                id: 'protesis_r',
                label: 'Prót. removible',
                color: 'red',
                shape: 'paren'
            },
            {
                id: 'protesis_t',
                label: 'Prót. total',
                color: 'red',
                shape: 'dbl'
            },
        ];

        const SYM_COLORS = {
            red: '#DC2626',
            blue: '#1D4ED8'
        };

        let currentSym = 'caries';
        let odontogramData = {};

        // ── Dientes permanentes ──
        const UPPER = [18, 17, 16, 15, 14, 13, 12, 11, 21, 22, 23, 24, 25, 26, 27, 28];
        const LOWER = [48, 47, 46, 45, 44, 43, 42, 41, 31, 32, 33, 34, 35, 36, 37, 38];
        // ── Dientes deciduos (leche) ──
        const UPPER_D = [55, 54, 53, 52, 51, 61, 62, 63, 64, 65];
        const LOWER_D = [85, 84, 83, 82, 81, 71, 72, 73, 74, 75];
        // Todos
        const ALL = [...UPPER, ...LOWER, ...UPPER_D, ...LOWER_D];

        // ── SVG icono para toolbar ──
        function icoSvg(shape, color, sz) {
            sz = sz || 13;
            const a = `stroke="${color}" stroke-linecap="round" stroke-linejoin="round"`;
            if (shape === 'bracket')
                return `<svg width="${sz}" height="${sz}" viewBox="0 0 24 24"><path d="M8 4v16M16 4v16" ${a} stroke-width="3"/></svg>`;
            if (shape === 'x')
                return `<svg width="${sz}" height="${sz}" viewBox="0 0 24 24"><path d="M5 5L19 19M19 5L5 19" ${a} stroke-width="3"/></svg>`;
            if (shape === 'vline')
                return `<svg width="${sz}" height="${sz}" viewBox="0 0 24 24"><path d="M12 4v16" ${a} stroke-width="3"/></svg>`;
            if (shape === 'tri')
                return `<svg width="${sz}" height="${sz}" viewBox="0 0 24 24"><path d="M12 4L21 20H3Z" ${a} fill="none" stroke-width="2.5"/></svg>`;
            if (shape === 'dot')
                return `<svg width="${sz}" height="${sz}" viewBox="0 0 24 24"><circle cx="12" cy="12" r="7" fill="${color}"/></svg>`;
            if (shape === 'circ')
                return `<svg width="${sz}" height="${sz}" viewBox="0 0 24 24"><circle cx="12" cy="12" r="7" stroke="${color}" stroke-width="2.5" fill="none"/></svg>`;
            if (shape === 'dash')
                return `<svg width="${sz}" height="${sz}" viewBox="0 0 24 24"><rect x="3" y="8" width="4" height="8" stroke="${color}" fill="none" stroke-width="2"/><rect x="17" y="8" width="4" height="8" stroke="${color}" fill="none" stroke-width="2"/><path d="M7 12h10" stroke="${color}" stroke-width="2" stroke-dasharray="3 2"/></svg>`;
            if (shape === 'paren')
                return `<svg width="${sz}" height="${sz}" viewBox="0 0 24 24"><path d="M6 6Q2 12 6 18" stroke="${color}" fill="none" stroke-width="2.5"/><path d="M18 6Q22 12 18 18" stroke="${color}" fill="none" stroke-width="2.5"/><path d="M8 12h8" stroke="${color}" stroke-width="2" stroke-dasharray="3 2"/></svg>`;
            if (shape === 'dbl')
                return `<svg width="${sz}" height="${sz}" viewBox="0 0 24 24"><path d="M4 8h16M4 16h16" stroke="${color}" stroke-width="3" stroke-linecap="round"/></svg>`;
            return '';
        }

        function buildToolbar() {
            const tb = document.getElementById('symbol-toolbar');
            tb.innerHTML = '';
            SYMS.forEach(s => {
                const color = SYM_COLORS[s.color];
                const b = document.createElement('button');
                b.type = 'button';
                b.style.cssText =
                    'display:flex;align-items:center;gap:5px;padding:6px 10px;font-size:12px;border:1px solid var(--border);border-radius:8px;background:var(--surface);color:var(--text);cursor:pointer;transition:all 0.12s;font-family:inherit;white-space:nowrap;';
                b.innerHTML = icoSvg(s.shape, color) + s.label;
                b.dataset.sid = s.id;
                b.addEventListener('click', () => {
                    currentSym = s.id;
                    tb.querySelectorAll('button').forEach(x => {
                        x.style.background = 'var(--surface)';
                        x.style.color = 'var(--text)';
                        x.style.borderColor = 'var(--border)';
                    });
                    b.style.background = '#1A1A2E';
                    b.style.color = '#fff';
                    b.style.borderColor = '#1A1A2E';
                });
                if (s.id === currentSym) {
                    b.style.background = '#1A1A2E';
                    b.style.color = '#fff';
                    b.style.borderColor = '#1A1A2E';
                }
                tb.appendChild(b);
            });
        }

        function getFill(symId) {
            if (!symId) return {
                fill: '#FFFFFF',
                stroke: '#D1D5DB'
            };
            const s = SYMS.find(x => x.id === symId);
            return s && s.color === 'blue' ? {
                fill: '#EFF6FF',
                stroke: '#1D4ED8'
            } : {
                fill: '#FEF2F2',
                stroke: '#DC2626'
            };
        }

        function drawSymOnSurface(NS, symId, cx, cy, r) {
            const s = SYMS.find(x => x.id === symId);
            if (!s) return null;
            const color = SYM_COLORS[s.color];
            const g = document.createElementNS(NS, 'g');

            function ln(x1, y1, x2, y2, w) {
                const l = document.createElementNS(NS, 'line');
                l.setAttribute('x1', x1);
                l.setAttribute('y1', y1);
                l.setAttribute('x2', x2);
                l.setAttribute('y2', y2);
                l.setAttribute('stroke', color);
                l.setAttribute('stroke-width', w || 1.8);
                l.setAttribute('stroke-linecap', 'round');
                g.appendChild(l);
            }
            if (s.shape === 'bracket') {
                ln(cx - r * 0.4, cy - r, cx - r * 0.4, cy + r);
                ln(cx + r * 0.4, cy - r, cx + r * 0.4, cy + r);
            } else if (s.shape === 'x') {
                ln(cx - r * 0.7, cy - r * 0.7, cx + r * 0.7, cy + r * 0.7);
                ln(cx + r * 0.7, cy - r * 0.7, cx - r * 0.7, cy + r * 0.7);
            } else if (s.shape === 'vline') {
                ln(cx, cy - r, cx, cy + r);
            } else if (s.shape === 'tri') {
                const p = document.createElementNS(NS, 'polygon');
                p.setAttribute('points', `${cx},${cy-r} ${cx+r*0.87},${cy+r*0.5} ${cx-r*0.87},${cy+r*0.5}`);
                p.setAttribute('stroke', color);
                p.setAttribute('stroke-width', '1.6');
                p.setAttribute('fill', 'none');
                g.appendChild(p);
            } else if (s.shape === 'dot') {
                const ci = document.createElementNS(NS, 'circle');
                ci.setAttribute('cx', cx);
                ci.setAttribute('cy', cy);
                ci.setAttribute('r', r * 0.72);
                ci.setAttribute('fill', color);
                g.appendChild(ci);
            } else if (s.shape === 'circ') {
                const ci = document.createElementNS(NS, 'circle');
                ci.setAttribute('cx', cx);
                ci.setAttribute('cy', cy);
                ci.setAttribute('r', r * 0.7);
                ci.setAttribute('stroke', color);
                ci.setAttribute('stroke-width', '1.8');
                ci.setAttribute('fill', 'none');
                g.appendChild(ci);
            } else if (s.shape === 'dash') {
                const l = document.createElementNS(NS, 'line');
                l.setAttribute('x1', cx - r * 0.7);
                l.setAttribute('y1', cy);
                l.setAttribute('x2', cx + r * 0.7);
                l.setAttribute('y2', cy);
                l.setAttribute('stroke', color);
                l.setAttribute('stroke-width', '1.6');
                l.setAttribute('stroke-dasharray', '2 1.5');
                g.appendChild(l);
            } else if (s.shape === 'paren') {
                ln(cx - r * 0.3, cy - r * 0.8, cx - r * 0.3, cy + r * 0.8, 1.5);
                ln(cx + r * 0.3, cy - r * 0.8, cx + r * 0.3, cy + r * 0.8, 1.5);
            } else if (s.shape === 'dbl') {
                ln(cx - r * 0.7, cy - r * 0.3, cx + r * 0.7, cy - r * 0.3, 1.5);
                ln(cx - r * 0.7, cy + r * 0.3, cx + r * 0.7, cy + r * 0.3, 1.5);
            }
            return g;
        }

        function toRad(d) {
            return d * Math.PI / 180;
        }

        function segPts(cx, cy, ir, or, a1, a2) {
            const s = toRad(a1),
                e = toRad(a2),
                m = toRad((a1 + a2) / 2);
            return [`${cx+ir*Math.cos(s)},${cy+ir*Math.sin(s)}`, `${cx+or*Math.cos(s)},${cy+or*Math.sin(s)}`,
                `${cx+or*Math.cos(m)},${cy+or*Math.sin(m)}`, `${cx+or*Math.cos(e)},${cy+or*Math.sin(e)}`,
                `${cx+ir*Math.cos(e)},${cy+ir*Math.sin(e)}`
            ].join(' ');
        }

        function segCenter(cx, cy, ir, or, a1, a2) {
            const m = toRad((a1 + a2) / 2),
                rm = (ir + or) / 2;
            return {
                x: cx + rm * Math.cos(m),
                y: cy + rm * Math.sin(m)
            };
        }

        function redrawSurface(svg, num, part, geom) {
            const NS = 'http://www.w3.org/2000/svg';
            const symId = (odontogramData[String(num)] || {})[part] || null;
            const fc = getFill(symId);
            const shape = svg.getElementById(`sp-${num}-${part}`);
            if (shape) {
                shape.setAttribute('fill', fc.fill);
                shape.setAttribute('stroke', fc.stroke);
            }
            const old = svg.getElementById(`sg-${num}-${part}`);
            if (old) old.remove();
            if (symId) {
                let scx, scy, sr;
                if (geom.isCircle) {
                    scx = geom.cx;
                    scy = geom.cy;
                    sr = geom.ir * 0.7;
                } else {
                    const ctr = segCenter(geom.cx, geom.cy, geom.ir, geom.or, geom.a1, geom.a2);
                    scx = ctr.x;
                    scy = ctr.y;
                    sr = (geom.or - geom.ir) * 0.5;
                }
                const g = drawSymOnSurface(NS, symId, scx, scy, sr);
                if (g) {
                    g.setAttribute('id', `sg-${num}-${part}`);
                    g.style.pointerEvents = 'none';
                    svg.appendChild(g);
                }
            }
        }

        function clickSurface(svg, num, part, geom) {
            const key = String(num);
            if (!odontogramData[key]) odontogramData[key] = {};
            if (odontogramData[key][part] === currentSym) {
                delete odontogramData[key][part];
                if (!Object.keys(odontogramData[key]).length) delete odontogramData[key];
            } else {
                odontogramData[key][part] = currentSym;
            }
            redrawSurface(svg, num, part, geom);
            document.getElementById('odontogram-input').value = JSON.stringify(odontogramData);
        }

        // ── Dibujar diente SVG — acepta tamaño opcional ──
        function makeTooth(num, sz) {
            sz = sz || 34;
            const c = sz / 2,
                or = c - 1.5,
                ir = or * 0.36;
            const NS = 'http://www.w3.org/2000/svg';
            const svg = document.createElementNS(NS, 'svg');
            svg.setAttribute('width', sz);
            svg.setAttribute('height', sz);
            svg.setAttribute('viewBox', `0 0 ${sz} ${sz}`);
            svg.style.display = 'block';
            svg.style.cursor = 'pointer';
            const segs = [{
                id: 'V',
                a1: -157.5,
                a2: -22.5
            }, {
                id: 'P',
                a1: 22.5,
                a2: 157.5
            }, {
                id: 'M',
                a1: 112.5,
                a2: 247.5
            }, {
                id: 'D',
                a1: -67.5,
                a2: 67.5
            }];
            segs.forEach(seg => {
                const fc = getFill(null);
                const el = document.createElementNS(NS, 'polygon');
                el.setAttribute('id', `sp-${num}-${seg.id}`);
                el.setAttribute('points', segPts(c, c, ir, or, seg.a1, seg.a2));
                el.setAttribute('stroke-linejoin', 'round');
                el.setAttribute('fill', fc.fill);
                el.setAttribute('stroke', fc.stroke);
                el.setAttribute('stroke-width', '0.8');
                el.style.cursor = 'pointer';
                const geom = {
                    cx: c,
                    cy: c,
                    ir: ir,
                    or: or,
                    a1: seg.a1,
                    a2: seg.a2,
                    isCircle: false
                };
                el.addEventListener('click', e => {
                    e.stopPropagation();
                    clickSurface(svg, num, seg.id, geom);
                });
                el.addEventListener('mouseenter', () => {
                    el.style.opacity = '0.7';
                });
                el.addEventListener('mouseleave', () => {
                    el.style.opacity = '1';
                });
                svg.appendChild(el);
            });
            const fc = getFill(null);
            const ci = document.createElementNS(NS, 'circle');
            ci.setAttribute('id', `sp-${num}-O`);
            ci.setAttribute('cx', c);
            ci.setAttribute('cy', c);
            ci.setAttribute('r', ir);
            ci.setAttribute('fill', fc.fill);
            ci.setAttribute('stroke', fc.stroke);
            ci.setAttribute('stroke-width', '0.8');
            ci.style.cursor = 'pointer';
            const geomO = {
                cx: c,
                cy: c,
                ir: ir,
                or: or,
                a1: 0,
                a2: 0,
                isCircle: true
            };
            ci.addEventListener('click', e => {
                e.stopPropagation();
                clickSurface(svg, num, 'O', geomO);
            });
            ci.addEventListener('mouseenter', () => {
                ci.style.opacity = '0.7';
            });
            ci.addEventListener('mouseleave', () => {
                ci.style.opacity = '1';
            });
            svg.appendChild(ci);
            return svg;
        }

        // Símbolos que se aplican POR SUPERFICIE (nunca pintan todo el diente)
        const SURFACE_ONLY_SYMS = ['caries', 'obturado'];

        // ── Pintar TODO el diente desde el número ──
        function paintWholeTooth(num, sz) {
            // Solo aplica si el símbolo actual NO es caries ni obturado
            if (SURFACE_ONLY_SYMS.includes(currentSym)) return;

            const key = String(num);
            const c = sz / 2,
                or = c - 1.5,
                ir = or * 0.36;
            const NS = 'http://www.w3.org/2000/svg';
            const svg = document.querySelector(`[data-tooth="${num}"]`);
            if (!svg) return;

            const parts = ['V', 'P', 'M', 'D', 'O'];
            const allSame = odontogramData[key] &&
                parts.every(p => odontogramData[key][p] === currentSym);

            if (allSame) {
                // Toggle: si ya tiene todo el símbolo, limpiar todo
                delete odontogramData[key];
                parts.forEach(part => {
                    const el = svg.getElementById(`sp-${num}-${part}`);
                    if (el) {
                        el.setAttribute('fill', '#FFFFFF');
                        el.setAttribute('stroke', '#D1D5DB');
                    }
                    const g = svg.getElementById(`sg-${num}-${part}`);
                    if (g) g.remove();
                });
            } else {
                // Pintar todas las superficies
                if (!odontogramData[key]) odontogramData[key] = {};

                const segMap = {
                    V: {
                        a1: -157.5,
                        a2: -22.5,
                        isCircle: false
                    },
                    P: {
                        a1: 22.5,
                        a2: 157.5,
                        isCircle: false
                    },
                    M: {
                        a1: 112.5,
                        a2: 247.5,
                        isCircle: false
                    },
                    D: {
                        a1: -67.5,
                        a2: 67.5,
                        isCircle: false
                    },
                    O: {
                        isCircle: true
                    },
                };

                parts.forEach(part => {
                    odontogramData[key][part] = currentSym;
                    const geom = part === 'O' ?
                        {
                            cx: c,
                            cy: c,
                            ir: ir,
                            or: or,
                            a1: 0,
                            a2: 0,
                            isCircle: true
                        } :
                        {
                            cx: c,
                            cy: c,
                            ir: ir,
                            or: or,
                            a1: segMap[part].a1,
                            a2: segMap[part].a2,
                            isCircle: false
                        };
                    redrawSurface(svg, num, part, geom);
                });
            }

            document.getElementById('odontogram-input').value = JSON.stringify(odontogramData);
        }

        function makeBlock(num, above, sz) {
            const w = document.createElement('div');
            w.style.cssText = 'display:flex;flex-direction:column;align-items:center;gap:2px;';
            const lbl = document.createElement('div');
            lbl.style.cssText = 'font-size:9px;color:#9CA3AF;font-weight:600;text-align:center;' +
                'line-height:1;cursor:pointer;padding:2px 4px;border-radius:3px;' +
                'transition:background 0.12s,color 0.12s;user-select:none;';
            lbl.textContent = num;

            // Tooltip visual
            lbl.title = 'Clic: pintar todo el diente';

            // Hover en el número
            lbl.addEventListener('mouseenter', () => {
                if (!SURFACE_ONLY_SYMS.includes(currentSym)) {
                    lbl.style.background = '#1A1A2E';
                    lbl.style.color = '#fff';
                }
            });
            lbl.addEventListener('mouseleave', () => {
                lbl.style.background = '';
                lbl.style.color = '#9CA3AF';
            });

            // Clic en el número → pintar todo
            lbl.addEventListener('click', e => {
                e.stopPropagation();
                paintWholeTooth(num, sz);
            });

            const svg = makeTooth(num, sz);
            // Marcar el SVG con data-tooth para poder encontrarlo
            svg.setAttribute('data-tooth', num);

            if (above) {
                w.appendChild(lbl);
                w.appendChild(svg);
            } else {
                w.appendChild(svg);
                w.appendChild(lbl);
            }
            return w;
        }

        function makeRow(nums, above, sz) {
            const r = document.createElement('div');
            r.style.cssText = 'display:flex;justify-content:center;gap:3px;';
            nums.forEach(n => r.appendChild(makeBlock(n, above, sz)));
            return r;
        }

        function rowLabel(text, sub) {
            const d = document.createElement('div');
            d.style.cssText = 'font-size:10px;font-weight:700;color:#9CA3AF;text-transform:uppercase;' +
                'letter-spacing:0.5px;text-align:center;margin:6px 0 4px;';
            d.textContent = sub ? text + ' (Deciduos)' : text;
            return d;
        }

        function buildOdontogram() {
            const root = document.getElementById('odo-root');
            root.innerHTML = '';

            // ── Superior permanentes ──
            root.appendChild(rowLabel('Superior'));
            root.appendChild(makeRow(UPPER, true, 34));

            // ── Superior deciduos (leche) ──
            root.appendChild(rowLabel('Superior', true));
            root.appendChild(makeRow(UPPER_D, true, 26));

            // ── Separador ──
            const sep = document.createElement('div');
            sep.style.cssText = 'border-top:1.5px dashed #E5E7EB;margin:8px 0;';
            root.appendChild(sep);

            // ── Inferior deciduos (leche) ──
            root.appendChild(rowLabel('Inferior', true));
            root.appendChild(makeRow(LOWER_D, false, 26));

            // ── Inferior permanentes ──
            root.appendChild(rowLabel('Inferior'));
            root.appendChild(makeRow(LOWER, false, 34));
        }

        function clearOdontogram() {
            odontogramData = {};
            ALL.forEach(n => {
                const sp = document.getElementById(`sp-${n}-V`);
                if (!sp) return;
                const svg = sp.closest('svg');
                if (!svg) return;
                ['V', 'P', 'M', 'D', 'O'].forEach(pid => {
                    const el = svg.getElementById(`sp-${n}-${pid}`);
                    if (el) {
                        el.setAttribute('fill', '#FFFFFF');
                        el.setAttribute('stroke', '#D1D5DB');
                    }
                    const g = svg.getElementById(`sg-${n}-${pid}`);
                    if (g) g.remove();
                });
            });
            document.getElementById('odontogram-input').value = '';
        }


        // ══════════════════════════════════════════
        //  ITEMS
        // ══════════════════════════════════════════
        let itemIndex = 0;

        function addItem(desc = '', qty = 1, price = 0) {
            const idx = itemIndex++;
            document.getElementById('items-header').style.display = 'grid';
            const div = document.createElement('div');
            div.id = 'item-row-' + idx;
            div.style.cssText =
                'display:grid;grid-template-columns:1fr 56px 90px 30px;gap:6px;align-items:center;margin-bottom:7px;';
            div.innerHTML = `
        <input type="text" name="items[${idx}][description]" class="form-control"
               style="font-size:13px;" placeholder="Descripción" value="${desc}" required>
        <input type="number" name="items[${idx}][quantity]" class="form-control"
               style="text-align:center;font-size:13px;" min="1" value="${qty}"
               oninput="recalculate()" required>
        <div style="position:relative;">
            <span style="position:absolute;left:8px;top:50%;transform:translateY(-50%);
                         color:var(--text-muted);font-size:12px;font-weight:600;">$</span>
            <input type="number" name="items[${idx}][unit_price]" class="form-control"
                   style="padding-left:20px;text-align:right;font-size:13px;"
                   step="0.01" min="0" value="${price}" oninput="recalculate()" required>
        </div>
        <button type="button" onclick="removeItem(${idx})"
                style="width:30px;height:38px;border:1px solid #FECACA;
                       border-radius:var(--radius-sm);background:#FEF2F2;
                       color:#DC2626;cursor:pointer;font-size:15px;
                       display:flex;align-items:center;justify-content:center;
                       flex-shrink:0;">×</button>`;
            document.getElementById('items-container').appendChild(div);
            recalculate();
        }

        function removeItem(idx) {
            document.getElementById('item-row-' + idx)?.remove();
            recalculate();
            if (!document.getElementById('items-container').children.length)
                document.getElementById('items-header').style.display = 'none';
        }

        function recalculate() {
            let sub = 0;
            document.querySelectorAll('[name$="[unit_price]"]').forEach(p => {
                const row = p.closest('[id^="item-row-"]');
                const q = parseFloat(row?.querySelector('[name$="[quantity]"]')?.value || 0);
                sub += q * (parseFloat(p.value) || 0);
            });
            const disc = parseFloat(document.getElementById('discount-input').value) || 0;
            document.getElementById('display-subtotal').textContent = '$' + sub.toFixed(2);
            document.getElementById('display-total').textContent = '$' + Math.max(0, sub - disc).toFixed(2);
        }

        // ══════════════════════════════════════════
        //  INIT + FIRMA
        // ══════════════════════════════════════════
        document.addEventListener('DOMContentLoaded', function() {

            buildToolbar();
            buildOdontogram();

            const canvas = document.getElementById('sig-canvas');
            const container = document.getElementById('sig-container');

            const sigPad = new SignaturePad(canvas, {
                backgroundColor: 'rgba(255,255,255,0)',
                penColor: '#1A1A2E',
                minWidth: 1,
                maxWidth: 2.5,
            });

            function resizeCanvas() {
                const ratio = Math.max(window.devicePixelRatio || 1, 1);
                const w = container.offsetWidth;
                canvas.width = w * ratio;
                canvas.height = 160 * ratio;
                canvas.getContext('2d').scale(ratio, ratio);
                sigPad.clear();
            }

            resizeCanvas();
            window.addEventListener('resize', resizeCanvas);

            sigPad.addEventListener('endStroke', () => {
                document.getElementById('sig-status').textContent = '✓ Firma capturada';
                document.getElementById('sig-status').style.color = 'var(--success)';
                document.getElementById('sig-input').value = sigPad.toDataURL('image/png');
                container.classList.add('has-sig');
            });

            window.clearSignature = function() {
                sigPad.clear();
                document.getElementById('sig-input').value = '';
                document.getElementById('sig-status').textContent = 'Sin firma';
                document.getElementById('sig-status').style.color = 'var(--text-muted)';
                container.classList.remove('has-sig');
            };

            window.prepareSubmit = function() {
                if (!sigPad.isEmpty())
                    document.getElementById('sig-input').value = sigPad.toDataURL('image/png');
                document.getElementById('odontogram-input').value = JSON.stringify(odontogramData);
            };
        });
    </script>
@endpush
