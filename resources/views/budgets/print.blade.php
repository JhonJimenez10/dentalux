<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Presupuesto #{{ $budget->id }} — {{ $patient->full_name }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&family=DM+Sans:wght@400;500&display=swap"
        rel="stylesheet">
    <style>
        *,
        *::before,
        *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: #fff;
            color: #1A1A2E;
            font-size: 13px;
            padding: 32px;
        }

        .no-print {
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 9px 18px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            border: none;
            text-decoration: none;
            font-family: 'DM Sans', sans-serif;
        }

        .btn-primary {
            background: #C8395A;
            color: #fff;
        }

        .btn-outline {
            background: transparent;
            color: #1A1A2E;
            border: 1px solid #E5E7EB;
        }

        .page {
            max-width: 720px;
            margin: 0 auto;
            border: 1px solid #E5E7EB;
            border-radius: 12px;
            overflow: hidden;
        }

        .print-header {
            background: #1A1A2E;
            padding: 24px 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .brand-icon {
            width: 40px;
            height: 40px;
            background: #C8395A;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .brand-name {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 18px;
            font-weight: 700;
            color: #fff;
        }

        .brand-sub {
            font-size: 10px;
            color: rgba(255, 255, 255, 0.4);
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }

        .doc-label {
            text-align: right;
        }

        .doc-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 20px;
            font-weight: 700;
            color: #fff;
        }

        .doc-num {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.45);
            margin-top: 2px;
        }

        .patient-section {
            padding: 20px 32px;
            background: #F8F7F5;
            border-bottom: 1px solid #E5E7EB;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
        }

        .info-item .label {
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #9CA3AF;
            margin-bottom: 3px;
        }

        .info-item .value {
            font-size: 13px;
            font-weight: 600;
            color: #1A1A2E;
        }

        .items-section {
            padding: 24px 32px;
        }

        .section-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 13px;
            font-weight: 700;
            color: #C8395A;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 2px solid #F4D0D8;
        }

        table.items {
            width: 100%;
            border-collapse: collapse;
        }

        table.items thead th {
            padding: 9px 12px;
            text-align: left;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            color: #fff;
            background: #C8395A;
        }

        table.items thead th:last-child,
        table.items thead th:nth-child(2),
        table.items thead th:nth-child(3) {
            text-align: right;
        }

        table.items tbody td {
            padding: 10px 12px;
            border-bottom: 1px solid #F3F4F6;
            font-size: 13px;
        }

        table.items tbody td:last-child,
        table.items tbody td:nth-child(2),
        table.items tbody td:nth-child(3) {
            text-align: right;
        }

        table.items tbody tr:last-child td {
            border-bottom: none;
        }

        .totals {
            display: flex;
            justify-content: flex-end;
            margin-top: 16px;
            padding-top: 16px;
            border-top: 2px solid #E5E7EB;
        }

        .totals-box {
            min-width: 240px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
            font-size: 13px;
        }

        .total-row .label {
            color: #6B7280;
        }

        .total-final {
            display: flex;
            justify-content: space-between;
            padding: 10px 0 0;
            margin-top: 6px;
            border-top: 2px solid #1A1A2E;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 17px;
            font-weight: 700;
        }

        .total-final .amount {
            color: #C8395A;
        }

        .plan-section {
            padding: 0 32px 24px;
        }

        .plan-box {
            background: #FDF4F6;
            border: 1px solid #F4D0D8;
            border-radius: 8px;
            padding: 14px 18px;
            display: flex;
            gap: 32px;
            align-items: center;
        }

        .plan-item .label {
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #9CA3AF;
            margin-bottom: 3px;
        }

        .plan-item .value {
            font-size: 20px;
            font-weight: 700;
            color: #C8395A;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        /* Odontograma */
        .odo-section {
            padding: 0 32px 24px;
        }

        .odo-row {
            display: flex;
            justify-content: center;
            gap: 2px;
            flex-wrap: nowrap;
            margin-bottom: 3px;
        }

        .odo-tooth {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1px;
        }

        .odo-tooth.reverse {
            flex-direction: column-reverse;
        }

        .odo-num {
            font-size: 7px;
            color: #9CA3AF;
            font-weight: 600;
            text-align: center;
        }

        .odo-divider {
            border: none;
            border-top: 1.5px dashed #E5E7EB;
            margin: 6px 0;
        }

        .odo-dashed {
            border: none;
            border-top: 1px dotted #D1D5DB;
            margin: 4px 0;
        }

        .odo-legend {
            display: flex;
            justify-content: center;
            gap: 16px;
            flex-wrap: wrap;
            margin-bottom: 8px;
            font-size: 10px;
            color: #6B7280;
        }

        .odo-legend-item {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .odo-legend-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            border: 1.5px solid;
        }

        .odo-section-label {
            font-size: 9px;
            font-weight: 700;
            color: #9CA3AF;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            text-align: center;
            margin: 4px 0 3px;
        }

        .odo-section-label.deciduo {
            color: #B45309;
            background: #FEF3C7;
            display: inline-block;
            padding: 1px 8px;
            border-radius: 100px;
            margin: 4px auto;
            display: block;
        }

        /* Firmas */
        .sig-section {
            padding: 0 32px 32px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 32px;
        }

        .sig-box {
            border-top: 1.5px solid #1A1A2E;
            padding-top: 8px;
            text-align: center;
        }

        .sig-label {
            font-size: 11px;
            color: #9CA3AF;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .sig-img {
            max-height: 60px;
            max-width: 180px;
            margin-bottom: 4px;
        }

        .print-footer {
            background: #F8F7F5;
            border-top: 1px solid #E5E7EB;
            padding: 14px 32px;
            text-align: center;
            font-size: 11px;
            color: #9CA3AF;
        }

        @media print {
            body {
                padding: 0;
            }

            .no-print {
                display: none !important;
            }

            .page {
                border: none;
                border-radius: 0;
            }
        }
    </style>
</head>

<body>

    <div class="no-print">
        <button onclick="window.print()" class="btn btn-primary">🖨️ Imprimir</button>
        <a href="{{ route('patients.budgets.show', [$patient, $budget]) }}" class="btn btn-outline">← Volver</a>
    </div>

    <div class="page">

        <div class="print-header">
            <div class="brand">
                <div class="brand-icon">🦷</div>
                <div>
                    <div class="brand-name">dentalux</div>
                    <div class="brand-sub">Odontología Familiar</div>
                </div>
            </div>
            <div class="doc-label">
                <div class="doc-title">PRESUPUESTO</div>
                <div class="doc-num">#{{ str_pad($budget->id, 4, '0', STR_PAD_LEFT) }} ·
                    {{ $budget->budget_date->format('d/m/Y') }}</div>
            </div>
        </div>

        <div class="patient-section">
            <div class="info-grid">
                <div class="info-item">
                    <div class="label">Paciente</div>
                    <div class="value">{{ $patient->full_name }}</div>
                </div>
                <div class="info-item">
                    <div class="label">Cédula</div>
                    <div class="value">{{ $patient->cedula ?? '—' }}</div>
                </div>
                <div class="info-item">
                    <div class="label">Teléfono</div>
                    <div class="value">{{ $patient->phone ?? '—' }}</div>
                </div>
                <div class="info-item">
                    <div class="label">Dentista</div>
                    <div class="value">{{ $budget->dentist->name }}</div>
                </div>
                <div class="info-item">
                    <div class="label">Estado</div>
                    <div class="value">{{ $budget->status_label }}</div>
                </div>
                <div class="info-item">
                    <div class="label">Ciudad</div>
                    <div class="value">{{ $patient->city ?? '—' }}</div>
                </div>
            </div>
        </div>

        <div class="items-section">
            <div class="section-title">Servicios y Tratamientos</div>
            <table class="items">
                <thead>
                    <tr>
                        <th>Descripción</th>
                        <th>Cant.</th>
                        <th>P. Unitario</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($budget->items as $item)
                        <tr>
                            <td style="font-weight:500;">{{ $item->description }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>${{ number_format($item->unit_price, 2) }}</td>
                            <td style="font-weight:600;">${{ number_format($item->total, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="totals">
                <div class="totals-box">
                    <div class="total-row"><span
                            class="label">Subtotal</span><span>${{ number_format($budget->subtotal, 2) }}</span></div>
                    @if ($budget->discount > 0)
                        <div class="total-row"><span class="label">Descuento</span><span style="color:#DC2626;">-
                                ${{ number_format($budget->discount, 2) }}</span></div>
                    @endif
                    <div class="total-final"><span>TOTAL</span><span
                            class="amount">${{ number_format($budget->total, 2) }}</span></div>
                </div>
            </div>
        </div>

        @if ($budget->initial_payment > 0 || $budget->monthly_payment > 0)
            <div class="plan-section">
                <div class="section-title">Plan de Pago</div>
                <div class="plan-box">
                    @if ($budget->initial_payment > 0)
                        <div class="plan-item">
                            <div class="label">Entrada</div>
                            <div class="value">${{ number_format($budget->initial_payment, 2) }}</div>
                        </div>
                    @endif
                    @if ($budget->monthly_payment > 0)
                        <div class="plan-item">
                            <div class="label">Mensualidad</div>
                            <div class="value">${{ number_format($budget->monthly_payment, 2) }}</div>
                        </div>
                    @endif
                    <div style="margin-left:auto;font-size:11px;color:#9CA3AF;font-style:italic;">* Validez 90 días
                    </div>
                </div>
            </div>
        @endif

        {{-- Odontograma --}}
        @if ($budget->odontogram)
            @php
                $rawOdo = $budget->odontogram;
                if (is_string($rawOdo)) {
                    $rawOdo = json_decode($rawOdo, true) ?? [];
                }
                if (!is_array($rawOdo)) {
                    $rawOdo = [];
                }

                $upperPerm = [18, 17, 16, 15, 14, 13, 12, 11, 21, 22, 23, 24, 25, 26, 27, 28];
                $lowerPerm = [48, 47, 46, 45, 44, 43, 42, 41, 31, 32, 33, 34, 35, 36, 37, 38];
                $upperDec = [55, 54, 53, 52, 51, 61, 62, 63, 64, 65];
                $lowerDec = [85, 84, 83, 82, 81, 71, 72, 73, 74, 75];
                $allTeeth = array_merge($upperPerm, $lowerPerm, $upperDec, $lowerDec);

                // Tamaños
                $SZ_PERM = 28;
                $SZ_DEC = 22;
                $SYMS = [
                    'sellante_n' => ['color' => 'red', 'shape' => 'bracket'],
                    'sellante_r' => ['color' => 'blue', 'shape' => 'bracket'],
                    'extraccion' => ['color' => 'red', 'shape' => 'x'],
                    'perdida_c' => ['color' => 'blue', 'shape' => 'x'],
                    'perdida_o' => ['color' => 'red', 'shape' => 'vline'],
                    'endodoncia' => ['color' => 'red', 'shape' => 'tri'],
                    'corona' => ['color' => 'red', 'shape' => 'dot'],
                    'obturado' => ['color' => 'blue', 'shape' => 'circ'],
                    'caries' => ['color' => 'red', 'shape' => 'circ'],
                    'protesis_f' => ['color' => 'red', 'shape' => 'dash'],
                    'protesis_r' => ['color' => 'red', 'shape' => 'paren'],
                    'protesis_t' => ['color' => 'red', 'shape' => 'dbl'],
                ];
                $SYM_COLORS = ['red' => '#DC2626', 'blue' => '#1D4ED8'];
                $SYM_FILLS = [
                    'red' => ['fill' => '#FEF2F2', 'stroke' => '#DC2626'],
                    'blue' => ['fill' => '#EFF6FF', 'stroke' => '#1D4ED8'],
                ];

                function _toRad2($d)
                {
                    return ($d * M_PI) / 180;
                }
                function _segPts2($cx, $cy, $ir, $or, $a1, $a2)
                {
                    $s = _toRad2($a1);
                    $e = _toRad2($a2);
                    $m = _toRad2(($a1 + $a2) / 2);
                    return implode(' ', [
                        round($cx + $ir * cos($s), 2) . ',' . round($cy + $ir * sin($s), 2),
                        round($cx + $or * cos($s), 2) . ',' . round($cy + $or * sin($s), 2),
                        round($cx + $or * cos($m), 2) . ',' . round($cy + $or * sin($m), 2),
                        round($cx + $or * cos($e), 2) . ',' . round($cy + $or * sin($e), 2),
                        round($cx + $ir * cos($e), 2) . ',' . round($cy + $ir * sin($e), 2),
                    ]);
                }
                function _segCenter2($cx, $cy, $ir, $or, $a1, $a2)
                {
                    $m = _toRad2(($a1 + $a2) / 2);
                    $rm = ($ir + $or) / 2;
                    return ['x' => $cx + $rm * cos($m), 'y' => $cy + $rm * sin($m)];
                }
                function _drawSym2($symId, $cx, $cy, $r, $SYMS, $SYM_COLORS)
                {
                    if (!isset($SYMS[$symId])) {
                        return '';
                    }
                    $s = $SYMS[$symId];
                    $color = $SYM_COLORS[$s['color']];
                    $out = '';
                    if ($s['shape'] === 'bracket') {
                        $out .=
                            "<line x1='" .
                            ($cx - $r * 0.4) .
                            "' y1='" .
                            ($cy - $r) .
                            "' x2='" .
                            ($cx - $r * 0.4) .
                            "' y2='" .
                            ($cy + $r) .
                            "' stroke='$color' stroke-width='1.6' stroke-linecap='round'/>";
                        $out .=
                            "<line x1='" .
                            ($cx + $r * 0.4) .
                            "' y1='" .
                            ($cy - $r) .
                            "' x2='" .
                            ($cx + $r * 0.4) .
                            "' y2='" .
                            ($cy + $r) .
                            "' stroke='$color' stroke-width='1.6' stroke-linecap='round'/>";
                    } elseif ($s['shape'] === 'x') {
                        $out .=
                            "<line x1='" .
                            ($cx - $r * 0.7) .
                            "' y1='" .
                            ($cy - $r * 0.7) .
                            "' x2='" .
                            ($cx + $r * 0.7) .
                            "' y2='" .
                            ($cy + $r * 0.7) .
                            "' stroke='$color' stroke-width='1.6' stroke-linecap='round'/>";
                        $out .=
                            "<line x1='" .
                            ($cx + $r * 0.7) .
                            "' y1='" .
                            ($cy - $r * 0.7) .
                            "' x2='" .
                            ($cx - $r * 0.7) .
                            "' y2='" .
                            ($cy + $r * 0.7) .
                            "' stroke='$color' stroke-width='1.6' stroke-linecap='round'/>";
                    } elseif ($s['shape'] === 'vline') {
                        $out .=
                            "<line x1='$cx' y1='" .
                            ($cy - $r) .
                            "' x2='$cx' y2='" .
                            ($cy + $r) .
                            "' stroke='$color' stroke-width='1.6' stroke-linecap='round'/>";
                    } elseif ($s['shape'] === 'tri') {
                        $pts =
                            $cx .
                            ',' .
                            ($cy - $r) .
                            ' ' .
                            ($cx + $r * 0.87) .
                            ',' .
                            ($cy + $r * 0.5) .
                            ' ' .
                            ($cx - $r * 0.87) .
                            ',' .
                            ($cy + $r * 0.5);
                        $out .= "<polygon points='$pts' stroke='$color' stroke-width='1.4' fill='none'/>";
                    } elseif ($s['shape'] === 'dot') {
                        $out .= "<circle cx='$cx' cy='$cy' r='" . $r * 0.72 . "' fill='$color'/>";
                    } elseif ($s['shape'] === 'circ') {
                        $out .=
                            "<circle cx='$cx' cy='$cy' r='" .
                            $r * 0.7 .
                            "' stroke='$color' stroke-width='1.6' fill='none'/>";
                    } elseif ($s['shape'] === 'dash') {
                        $out .=
                            "<line x1='" .
                            ($cx - $r * 0.7) .
                            "' y1='$cy' x2='" .
                            ($cx + $r * 0.7) .
                            "' y2='$cy' stroke='$color' stroke-width='1.4' stroke-dasharray='2 1.5'/>";
                    } elseif ($s['shape'] === 'paren') {
                        $out .=
                            "<line x1='" .
                            ($cx - $r * 0.3) .
                            "' y1='" .
                            ($cy - $r * 0.8) .
                            "' x2='" .
                            ($cx - $r * 0.3) .
                            "' y2='" .
                            ($cy + $r * 0.8) .
                            "' stroke='$color' stroke-width='1.3' stroke-linecap='round'/>";
                        $out .=
                            "<line x1='" .
                            ($cx + $r * 0.3) .
                            "' y1='" .
                            ($cy - $r * 0.8) .
                            "' x2='" .
                            ($cx + $r * 0.3) .
                            "' y2='" .
                            ($cy + $r * 0.8) .
                            "' stroke='$color' stroke-width='1.3' stroke-linecap='round'/>";
                    } elseif ($s['shape'] === 'dbl') {
                        $out .=
                            "<line x1='" .
                            ($cx - $r * 0.7) .
                            "' y1='" .
                            ($cy - $r * 0.3) .
                            "' x2='" .
                            ($cx + $r * 0.7) .
                            "' y2='" .
                            ($cy - $r * 0.3) .
                            "' stroke='$color' stroke-width='1.3' stroke-linecap='round'/>";
                        $out .=
                            "<line x1='" .
                            ($cx - $r * 0.7) .
                            "' y1='" .
                            ($cy + $r * 0.3) .
                            "' x2='" .
                            ($cx + $r * 0.7) .
                            "' y2='" .
                            ($cy + $r * 0.3) .
                            "' stroke='$color' stroke-width='1.3' stroke-linecap='round'/>";
                    }
                    return $out;
                }
                function _makeTooth2($num, $odoTooth, $sz, $SYMS, $SYM_COLORS, $SYM_FILLS)
                {
                    $c = $sz / 2;
                    $or = $c - 1.5;
                    $ir = $or * 0.36;
                    $segs = [
                        'V' => ['a1' => -157.5, 'a2' => -22.5],
                        'P' => ['a1' => 22.5, 'a2' => 157.5],
                        'M' => ['a1' => 112.5, 'a2' => 247.5],
                        'D' => ['a1' => -67.5, 'a2' => 67.5],
                    ];
                    $out = "<svg width='$sz' height='$sz' viewBox='0 0 $sz $sz' style='display:block;'>";
                    foreach ($segs as $part => $seg) {
                        $symId = $odoTooth[$part] ?? null;
                        $fill =
                            $symId && isset($SYMS[$symId])
                                ? $SYM_FILLS[$SYMS[$symId]['color']]
                                : ['fill' => '#FFFFFF', 'stroke' => '#D1D5DB'];
                        $pts = _segPts2($c, $c, $ir, $or, $seg['a1'], $seg['a2']);
                        $out .= "<polygon points='$pts' fill='{$fill['fill']}' stroke='{$fill['stroke']}' stroke-width='0.8' stroke-linejoin='round'/>";
                        if ($symId && isset($SYMS[$symId])) {
                            $ctr = _segCenter2($c, $c, $ir, $or, $seg['a1'], $seg['a2']);
                            $sr = ($or - $ir) * 0.5;
                            $out .= _drawSym2($symId, $ctr['x'], $ctr['y'], $sr, $SYMS, $SYM_COLORS);
                        }
                    }
                    $symO = $odoTooth['O'] ?? null;
                    $fillO =
                        $symO && isset($SYMS[$symO])
                            ? $SYM_FILLS[$SYMS[$symO]['color']]
                            : ['fill' => '#FFFFFF', 'stroke' => '#D1D5DB'];
                    $out .= "<circle cx='$c' cy='$c' r='$ir' fill='{$fillO['fill']}' stroke='{$fillO['stroke']}' stroke-width='0.8'/>";
                    if ($symO && isset($SYMS[$symO])) {
                        $sr = $ir * 0.7;
                        $out .= _drawSym2($symO, $c, $c, $sr, $SYMS, $SYM_COLORS);
                    }
                    $out .= '</svg>';
                    return $out;
                }

                $hasOdo = false;
                foreach ($allTeeth as $t) {
                    $d = $rawOdo[(string) $t] ?? null;
                    if ($d && is_array($d) && !empty($d)) {
                        $hasOdo = true;
                        break;
                    }
                }
            @endphp

            @if ($hasOdo)
                <div class="odo-section">
                    <div class="section-title">Odontograma</div>

                    <div class="odo-legend">
                        <div class="odo-legend-item">
                            <div class="odo-legend-dot" style="background:#FEF2F2;border-color:#DC2626;"></div>Rojo =
                            pendiente / patología
                        </div>
                        <div class="odo-legend-item">
                            <div class="odo-legend-dot" style="background:#EFF6FF;border-color:#1D4ED8;"></div>Azul = ya
                            realizado
                        </div>
                    </div>

                    {{-- Superior permanentes --}}
                    <div class="odo-section-label">Superior — Permanentes</div>
                    <div class="odo-row">
                        @foreach ($upperPerm as $t)
                            @php
                                $odoTooth = isset($rawOdo[(string) $t]) && is_array($rawOdo[(string) $t]) ? $rawOdo[(string) $t] : [];
                                $svg = _makeTooth2($t, $odoTooth, $SZ_PERM, $SYMS, $SYM_COLORS, $SYM_FILLS);
                            @endphp
                            <div class="odo-tooth">
                                <div class="odo-num">{{ $t }}</div>{!! $svg !!}
                            </div>
                        @endforeach
                    </div>

                    {{-- Superior deciduos --}}
                    <div class="odo-dashed"></div>
                    <div class="odo-section-label deciduo">Superior — Deciduos (Leche)</div>
                    <div class="odo-row">
                        @foreach ($upperDec as $t)
                            @php
                                $odoTooth = isset($rawOdo[(string) $t]) && is_array($rawOdo[(string) $t]) ? $rawOdo[(string) $t] : [];
                                $svg = _makeTooth2($t, $odoTooth, $SZ_DEC, $SYMS, $SYM_COLORS, $SYM_FILLS);
                            @endphp
                            <div class="odo-tooth">
                                <div class="odo-num">{{ $t }}</div>{!! $svg !!}
                            </div>
                        @endforeach
                    </div>

                    <hr class="odo-divider">

                    {{-- Inferior deciduos --}}
                    <div class="odo-section-label deciduo">Inferior — Deciduos (Leche)</div>
                    <div class="odo-row">
                        @foreach ($lowerDec as $t)
                            @php
                                $odoTooth = isset($rawOdo[(string) $t]) && is_array($rawOdo[(string) $t]) ? $rawOdo[(string) $t] : [];
                                $svg = _makeTooth2($t, $odoTooth, $SZ_DEC, $SYMS, $SYM_COLORS, $SYM_FILLS);
                            @endphp
                            <div class="odo-tooth reverse">
                                <div class="odo-num">{{ $t }}</div>{!! $svg !!}
                            </div>
                        @endforeach
                    </div>
                    <div class="odo-dashed"></div>

                    {{-- Inferior permanentes --}}
                    <div class="odo-section-label">Inferior — Permanentes</div>
                    <div class="odo-row">
                        @foreach ($lowerPerm as $t)
                            @php
                                $odoTooth = isset($rawOdo[(string) $t]) && is_array($rawOdo[(string) $t]) ? $rawOdo[(string) $t] : [];
                                $svg = _makeTooth2($t, $odoTooth, $SZ_PERM, $SYMS, $SYM_COLORS, $SYM_FILLS);
                            @endphp
                            <div class="odo-tooth reverse">
                                <div class="odo-num">{{ $t }}</div>{!! $svg !!}
                            </div>
                        @endforeach
                    </div>

                    <div
                        style="margin-top:10px;padding:8px 12px;background:#F9FAFB;border-radius:6px;font-size:10px;border:1px solid #E5E7EB;">
                        <strong>Referencias:</strong> CA=Caries · EX=Extracción · EN=Endodoncia · CO=Corona ·
                        OB=Obturado · SN=Sellante nec. · SR=Sellante real. · PC=Pérd. caries · PO=Pérd. otra · PF=Prót.
                        fija · PR=Prót. removible · PT=Prót. total
                    </div>
                </div>
            @endif
        @endif

        @if ($budget->notes)
            <div style="padding:0 32px 24px;">
                <div class="section-title">Notas</div>
                <div
                    style="background:#F9FAFB;border:1px solid #E5E7EB;border-radius:8px;padding:12px 16px;font-size:13px;color:#374151;line-height:1.6;">
                    {{ $budget->notes }}</div>
            </div>
        @endif

        <div class="sig-section">
            <div class="sig-box">
                <div class="sig-label">Firma del Dentista</div>
            </div>
            <div class="sig-box">
                @if ($budget->patient_signature)
                    <img src="{{ $budget->patient_signature }}" class="sig-img" alt="Firma">
                @endif
                <div class="sig-label">Firma del Paciente</div>
            </div>
        </div>

        <div class="print-footer">
            <strong>Dentalux Odontología Familiar</strong> · Dir. Luis Cordero y Héroes de Verdeloma · Telf:
            0962248526<br>
            Los valores de este presupuesto tienen validez por 90 días a partir de la fecha de emisión.
        </div>

    </div>
</body>

</html>
