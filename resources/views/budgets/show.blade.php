@extends('layouts.app')
@section('title', 'Presupuesto #' . $budget->id)

@section('topbar-actions')
    <a href="{{ route('patients.budgets.print', [$patient, $budget]) }}" class="btn btn-outline btn-sm" target="_blank">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
        </svg>
        <span class="btn-hide-mobile">Imprimir</span>
    </a>
    <a href="{{ route('patients.budgets.edit', [$patient, $budget]) }}" class="btn btn-outline btn-sm">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
        </svg>
        <span class="btn-hide-mobile">Editar</span>
    </a>
@endsection

@section('content')

    @php
        $breadcrumbs = [
            ['label' => 'Pacientes', 'url' => route('patients.index')],
            ['label' => $patient->full_name, 'url' => route('patients.show', $patient)],
            ['label' => 'Presupuesto #' . $budget->id, 'url' => '#'],
        ];

        // Normalizar odontograma — compatible con nuevo y antiguo formato
        $odoRaw = [];
        if ($budget->odontogram) {
            $odoRaw = is_array($budget->odontogram)
                ? $budget->odontogram
                : json_decode($budget->odontogram ?? '{}', true) ?? [];
        }

        // Solo mostrar si tiene al menos un diente marcado con el nuevo formato
        $hasOdo = !empty($odoRaw) && collect($odoRaw)->contains(fn($v) => is_array($v) && isset($v['symbol']));
    @endphp

    {{-- Cabecera --}}
    <div
        style="background:var(--text);border-radius:var(--radius);padding:18px 20px;
            margin-bottom:20px;display:flex;align-items:center;gap:14px;flex-wrap:wrap;">
        <div
            style="width:46px;height:46px;border-radius:50%;background:var(--primary);
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
                Presupuesto #{{ $budget->id }}
                · {{ $budget->budget_date->format('d/m/Y') }}
                · {{ $budget->dentist->name }}
            </div>
        </div>
        <div style="display:flex;gap:16px;flex-shrink:0;flex-wrap:wrap;">
            <div style="text-align:right;">
                <div
                    style="font-size:9px;color:rgba(255,255,255,0.38);
                        text-transform:uppercase;letter-spacing:0.5px;margin-bottom:3px;">
                    Total
                </div>
                <div
                    style="font-size:20px;font-weight:700;color:#fff;
                        font-family:'Plus Jakarta Sans',sans-serif;">
                    ${{ number_format($budget->total, 2) }}
                </div>
            </div>
            <div style="text-align:right;">
                <div
                    style="font-size:9px;color:rgba(255,255,255,0.38);
                        text-transform:uppercase;letter-spacing:0.5px;margin-bottom:6px;">
                    Estado
                </div>
                <span class="badge {{ $budget->status_badge }}">{{ $budget->status_label }}</span>
            </div>
        </div>
    </div>

    <div style="display:grid;grid-template-columns:1fr 300px;gap:18px;align-items:start;" class="budget-show-grid">

        {{-- ── Columna izquierda ── --}}
        <div>

            {{-- Servicios --}}
            <div class="card" style="margin-bottom:18px;">
                <div class="card-header">
                    <h3 class="card-title">Servicios / Tratamientos</h3>
                </div>
                <div class="table-wrap">
                    <table class="dtable">
                        <thead>
                            <tr>
                                <th>Descripción</th>
                                <th style="text-align:center;width:70px;">Cant.</th>
                                <th style="text-align:right;width:110px;" class="hide-xs">
                                    P. Unitario
                                </th>
                                <th style="text-align:right;width:100px;">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($budget->items as $item)
                                <tr>
                                    <td style="font-weight:500;">{{ $item->description }}</td>
                                    <td style="text-align:center;color:var(--text-muted);">
                                        {{ $item->quantity }}
                                    </td>
                                    <td style="text-align:right;color:var(--text-muted);" class="hide-xs">
                                        ${{ number_format($item->unit_price, 2) }}
                                    </td>
                                    <td style="text-align:right;font-weight:700;">
                                        ${{ number_format($item->total, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{-- Totales --}}
                <div style="padding:14px 18px;border-top:1px solid var(--border);">
                    <div style="display:flex;justify-content:flex-end;">
                        <div style="min-width:220px;">
                            <div
                                style="display:flex;justify-content:space-between;
                                    padding:5px 0;font-size:13px;">
                                <span class="text-muted">Subtotal</span>
                                <span>${{ number_format($budget->subtotal, 2) }}</span>
                            </div>
                            @if ($budget->discount > 0)
                                <div
                                    style="display:flex;justify-content:space-between;
                                    padding:5px 0;font-size:13px;">
                                    <span class="text-muted">Descuento</span>
                                    <span style="color:var(--danger);">
                                        - ${{ number_format($budget->discount, 2) }}
                                    </span>
                                </div>
                            @endif
                            <div
                                style="display:flex;justify-content:space-between;
                                    padding:10px 0 0;margin-top:6px;
                                    border-top:2px solid var(--border);">
                                <span style="font-size:15px;font-weight:700;">TOTAL</span>
                                <span style="font-size:20px;font-weight:700;color:var(--primary);">
                                    ${{ number_format($budget->total, 2) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Odontograma --}}
            @if ($hasOdo)
                <div class="card">
                    <div class="card-header" style="flex-wrap:wrap;gap:10px;">
                        <h3 class="card-title">Odontograma</h3>
                        {{-- Leyenda --}}
                        <div
                            style="display:flex;gap:12px;flex-wrap:wrap;font-size:12px;
                             color:var(--text-muted);">
                            <div style="display:flex;align-items:center;gap:5px;">
                                <div
                                    style="width:10px;height:10px;border-radius:50%;
                                    background:#FEE2E2;border:1.5px solid #DC2626;">
                                </div>
                                Rojo — pendiente
                            </div>
                            <div style="display:flex;align-items:center;gap:5px;">
                                <div
                                    style="width:10px;height:10px;border-radius:50%;
                                    background:#DBEAFE;border:1.5px solid #1D4ED8;">
                                </div>
                                Azul — realizado
                            </div>
                        </div>
                    </div>

                    {{-- Leyenda de símbolos --}}
                    <div
                        style="padding:10px 18px;background:var(--bg);
                         border-bottom:1px solid var(--border);
                         display:grid;grid-template-columns:repeat(3,1fr);
                         gap:6px;font-size:11px;color:var(--text-muted);">
                        <div>✕ &nbsp;Caries / alteración</div>
                        <div>✳ &nbsp;Sellante</div>
                        <div>○ &nbsp;Corona</div>
                        <div>△ &nbsp;Endodoncia</div>
                        <div>┃┃ Pérdida de pieza</div>
                    </div>

                    <div class="card-body" style="overflow-x:auto;padding:14px;">
                        <div id="odo-show-root" style="min-width:560px;"></div>
                    </div>
                </div>
            @endif

        </div>

        {{-- ── Columna derecha ── --}}
        <div>

            {{-- Plan de pago --}}
            @if ($budget->initial_payment > 0 || $budget->monthly_payment > 0)
                <div class="card" style="margin-bottom:16px;">
                    <div class="card-header">
                        <h3 class="card-title">Plan de Pago</h3>
                    </div>
                    <div style="padding:0;">
                        @if ($budget->initial_payment > 0)
                            <div
                                style="display:flex;justify-content:space-between;
                             padding:13px 18px;border-bottom:1px solid var(--border);
                             font-size:14px;">
                                <span class="text-muted">Entrada</span>
                                <span style="font-weight:700;">
                                    ${{ number_format($budget->initial_payment, 2) }}
                                </span>
                            </div>
                        @endif
                        @if ($budget->monthly_payment > 0)
                            <div
                                style="display:flex;justify-content:space-between;
                             padding:13px 18px;font-size:14px;">
                                <span class="text-muted">Mensualidad</span>
                                <span style="font-weight:700;">
                                    ${{ number_format($budget->monthly_payment, 2) }}
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Notas --}}
            @if ($budget->notes)
                <div class="card" style="margin-bottom:16px;">
                    <div class="card-header">
                        <h3 class="card-title">Notas</h3>
                    </div>
                    <div class="card-body" style="font-size:14px;color:var(--text-muted);line-height:1.6;">
                        {{ $budget->notes }}
                    </div>
                </div>
            @endif

            {{-- Firma --}}
            @if ($budget->patient_signature)
                <div class="card" style="margin-bottom:16px;">
                    <div class="card-header">
                        <h3 class="card-title">Firma del Paciente</h3>
                    </div>
                    <div
                        style="padding:16px;text-align:center;background:#FAFAFA;
                        border-radius:0 0 var(--radius) var(--radius);">
                        <img src="{{ $budget->patient_signature }}" style="max-height:70px;max-width:100%;" alt="Firma">
                    </div>
                </div>
            @endif

            {{-- Acciones --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Acciones</h3>
                </div>
                <div style="padding:14px;display:flex;flex-direction:column;gap:8px;">
                    <a href="{{ route('patients.budgets.edit', [$patient, $budget]) }}" class="btn btn-outline w-full"
                        style="justify-content:center;">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Editar
                    </a>
                    <a href="{{ route('patients.budgets.print', [$patient, $budget]) }}" class="btn btn-primary w-full"
                        style="justify-content:center;" target="_blank">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        Imprimir
                    </a>
                    <form method="POST" action="{{ route('patients.budgets.destroy', [$patient, $budget]) }}"
                        onsubmit="return confirm('¿Eliminar este presupuesto?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger w-full" style="justify-content:center;">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Eliminar presupuesto
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>

@endsection

@push('styles')
    <style>
        @media (max-width: 768px) {
            .budget-show-grid {
                grid-template-columns: 1fr !important;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        (function() {
            const ODO_DATA = @json($odoRaw);
            const UPPER = [18, 17, 16, 15, 14, 13, 12, 11, 21, 22, 23, 24, 25, 26, 27, 28];
            const LOWER = [48, 47, 46, 45, 44, 43, 42, 41, 31, 32, 33, 34, 35, 36, 37, 38];

            // ── Renderizar símbolo clínico en el diente ──
            function renderSymbol(num, g) {
                g.innerHTML = '';
                const key = String(num);
                const d = ODO_DATA[key];
                if (!d || !d.symbol || d.symbol === 'none') return;

                const color = d.color === 'blue' ? '#1D4ED8' : '#DC2626';
                let inner = '';

                if (d.symbol === 'caries')
                    inner =
                    `<path d="M9 9L25 25M25 9L9 25" stroke="${color}" stroke-width="3" stroke-linecap="round"/>`;
                else if (d.symbol === 'sellante')
                    inner =
                    `<path d="M17 6v22M8 11l18 12M26 11L8 23" stroke="${color}" stroke-width="2.2" stroke-linecap="round"/>`;
                else if (d.symbol === 'corona')
                    inner = `<circle cx="17" cy="17" r="11" stroke="${color}" stroke-width="3" fill="none"/>`;
                else if (d.symbol === 'endodoncia')
                    inner = `<path d="M17 6L29 28H5Z" stroke="${color}" stroke-width="2.5" fill="none"/>`;
                else if (d.symbol === 'perdida')
                    inner = `<path d="M12 6v22M22 6v22" stroke="${color}" stroke-width="2.5" stroke-linecap="round"/>`;

                g.innerHTML = inner;
            }

            // ── Dibujar diente SVG (solo lectura) ──
            function drawTooth(num) {
                const SZ = 34,
                    NS = 'http://www.w3.org/2000/svg';
                const svg = document.createElementNS(NS, 'svg');
                svg.setAttribute('width', SZ);
                svg.setAttribute('height', SZ);
                svg.setAttribute('viewBox', `0 0 ${SZ} ${SZ}`);
                svg.style.display = 'block';

                const c = SZ / 2,
                    r = SZ / 2 - 2;

                // Fondo del diente
                const circle = document.createElementNS(NS, 'circle');
                circle.setAttribute('cx', c);
                circle.setAttribute('cy', c);
                circle.setAttribute('r', r);

                // Colorear fondo según estado
                const key = String(num);
                const d = ODO_DATA[key];
                let bgColor = '#FFFFFF';
                if (d && d.color) {
                    bgColor = d.color === 'blue' ? '#EFF6FF' : '#FFF5F5';
                }
                circle.setAttribute('fill', bgColor);
                circle.setAttribute('stroke', d ? (d.color === 'blue' ? '#BFDBFE' : '#FEE2E2') : '#D1D5DB');
                circle.setAttribute('stroke-width', '1');
                svg.appendChild(circle);

                // Grupo para el símbolo
                const g = document.createElementNS(NS, 'g');
                svg.appendChild(g);
                renderSymbol(num, g);

                return svg;
            }

            function makeBlock(num, above) {
                const w = document.createElement('div');
                w.style.cssText = 'display:flex;flex-direction:column;align-items:center;gap:2px;';

                const lbl = document.createElement('div');
                lbl.style.cssText = 'font-size:9px;color:#9CA3AF;font-weight:600;text-align:center;line-height:1;';
                lbl.textContent = num;

                const svg = drawTooth(num);
                if (above) {
                    w.appendChild(lbl);
                    w.appendChild(svg);
                } else {
                    w.appendChild(svg);
                    w.appendChild(lbl);
                }
                return w;
            }

            function makeRow(nums, above) {
                const r = document.createElement('div');
                r.style.cssText = 'display:flex;justify-content:center;gap:4px;';
                nums.forEach(n => r.appendChild(makeBlock(n, above)));
                return r;
            }

            function rowLabel(text, mt) {
                const d = document.createElement('div');
                d.style.cssText = 'font-size:10px;font-weight:700;color:#9CA3AF;' +
                    'text-transform:uppercase;letter-spacing:0.5px;' +
                    'text-align:center;margin:' + (mt || 6) + 'px 0 4px;';
                d.textContent = text;
                return d;
            }

            function build() {
                const root = document.getElementById('odo-show-root');
                if (!root) return;
                root.innerHTML = '';
                root.appendChild(rowLabel('Superior'));
                root.appendChild(makeRow(UPPER, true));
                root.appendChild(rowLabel('Inferior', 10));
                root.appendChild(makeRow(LOWER, false));
            }

            document.addEventListener('DOMContentLoaded', build);
        })();
    </script>
@endpush
