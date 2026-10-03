<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #000;
            background: #fff;
            width: 100%;
        }

        .dashed {
            border-top: 1px dashed #000;
            margin: 5px 0;
        }

        .solid {
            border-top: 1px solid #000;
            margin: 5px 0;
        }

        .double {
            border-top: 3px double #000;
            margin: 5px 0;
        }

        .header {
            text-align: center;
            padding-bottom: 5px;
        }

        .clinic-name {
            font-size: 14px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .clinic-sub {
            font-size: 9px;
            color: #555;
        }

        .clinic-info {
            font-size: 8px;
            color: #555;
            margin-top: 2px;
            line-height: 1.4;
        }

        .vtitle {
            font-size: 11px;
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 3px 0;
        }

        .vnum {
            font-size: 9px;
            text-align: center;
            color: #555;
            margin-bottom: 3px;
        }

        .slabel {
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #555;
            margin-bottom: 2px;
        }

        .drow {
            margin-bottom: 2px;
            font-size: 9px;
            line-height: 1.4;
        }

        .dlbl {
            color: #333;
        }

        .dval {
            font-weight: bold;
        }

        .amount-box {
            text-align: center;
            padding: 5px 4px;
            margin: 5px 0;
            border: 2px solid #000;
            border-radius: 3px;
        }

        .amount-lbl {
            font-size: 9px;
            text-transform: uppercase;
            color: #333;
        }

        .amount-val {
            font-size: 18px;
            font-weight: bold;
        }

        table.pay {
            width: 100%;
            border-collapse: collapse;
            font-size: 8px;
            margin-top: 3px;
        }

        table.pay th {
            text-align: left;
            font-weight: bold;
            border-bottom: 1px solid #000;
            padding: 2px 1px;
        }

        table.pay td {
            padding: 2px 1px;
            border-bottom: 1px dashed #ccc;
        }

        table.pay .r {
            text-align: right;
        }

        table.pay tr.cur td {
            font-weight: bold;
            background: #f0f0f0;
        }

        table.pay tfoot td {
            border-top: 1px solid #000;
            border-bottom: none;
            font-weight: bold;
            padding-top: 2px;
        }

        .prog-outer {
            width: 100%;
            height: 5px;
            background: #ddd;
            margin-top: 2px;
        }

        .prog-inner {
            height: 5px;
            background: #333;
        }

        .sig-table {
            width: 100%;
            margin-top: 24px;
            font-size: 8px;
            text-align: center;
        }

        .sig-line {
            border-top: 1px solid #000;
            margin-bottom: 2px;
        }

        .copy-lbl {
            text-align: right;
            font-size: 8px;
            font-style: italic;
            color: #777;
            margin-bottom: 2px;
        }

        .footer {
            text-align: center;
            font-size: 8px;
            color: #555;
            margin-top: 6px;
            line-height: 1.5;
        }

        .code {
            text-align: center;
            font-size: 8px;
            letter-spacing: 2px;
            color: #666;
            margin: 3px 0;
        }
    </style>
</head>

<body>

    <?php
    $totalPaid = $paymentControl->total_paid;
    $totalAmount = $paymentControl->treatment_amount;
    $balance = $paymentControl->balance;
    $pct = $totalAmount > 0 ? min(100, round(($totalPaid / $totalAmount) * 100)) : 0;
    $payments = $paymentControl->payments->sortBy('payment_date');
    $payNum = str_pad($payment->id, 6, '0', STR_PAD_LEFT);
    $shortMethod = [
        'efectivo' => 'Efec.',
        'transferencia' => 'Trans.',
        'tarjeta_credito' => 'T.Crd.',
        'tarjeta_debito' => 'T.Deb.',
        'cheque' => 'Cheq.',
    ];
    ?>

    <div class="copy-lbl">Ticket - Consultorio</div>

    <div class="header">
        <div class="clinic-name">DENTALUX</div>
        <div class="clinic-sub">Odontologia Familiar</div>
        <div class="clinic-info">Cuenca, Ecuador</div>
    </div>

    <div class="double"></div>

    <div class="vtitle">COMPROBANTE DE PAGO</div>
    <div class="vnum">N. PAY-<?php echo $payNum; ?></div>

    <div class="dashed"></div>

    <div class="slabel">Informacion del pago</div>
    <div class="drow"><span class="dlbl">Fecha: </span><span class="dval"><?php echo $payment->payment_date->format('d/m/Y'); ?></span></div>
    <div class="drow"><span class="dlbl">Hora: </span><span class="dval"><?php echo $payment->created_at->format('H:i'); ?></span></div>
    <div class="drow"><span class="dlbl">Metodo: </span><span class="dval"><?php echo isset($shortMethod[$payment->payment_method]) ? $shortMethod[$payment->payment_method] : $payment->payment_method; ?></span></div>
    <div class="drow"><span class="dlbl">Por: </span><span class="dval"><?php echo $payment->registeredBy ? $payment->registeredBy->name : '-'; ?></span></div>
    <?php if($payment->reference): ?>
    <div class="drow"><span class="dlbl">Ref: </span><span class="dval"><?php echo $payment->reference; ?></span></div>
    <?php endif; ?>

    <div class="dashed"></div>

    <div class="slabel">Paciente</div>
    <div class="drow"><span class="dval"><?php echo strtoupper($patient->full_name); ?></span></div>
    <?php if($patient->cedula): ?><div class="drow"><span class="dlbl">CI: </span><span
            class="dval"><?php echo $patient->cedula; ?></span></div><?php endif; ?>
    <?php if($patient->phone): ?><div class="drow"><span class="dlbl">Tel: </span><span
            class="dval"><?php echo $patient->phone; ?></span></div><?php endif; ?>

    <div class="dashed"></div>

    <div class="amount-box">
        <div class="amount-lbl">Valor del abono</div>
        <div class="amount-val">$<?php echo number_format($payment->amount, 2); ?></div>
        <div style="font-size:8px;color:#333;margin-top:1px;"><?php echo strtoupper(isset($shortMethod[$payment->payment_method]) ? $shortMethod[$payment->payment_method] : $payment->payment_method); ?></div>
    </div>

    <div class="dashed"></div>

    <div class="slabel">Estado del tratamiento</div>
    <div class="drow"><span class="dlbl">Total: </span><span class="dval">$<?php echo number_format($totalAmount, 2); ?></span></div>
    <div class="drow"><span class="dlbl">Abonado: </span><span class="dval">$<?php echo number_format($totalPaid, 2); ?></span></div>
    <div class="drow"><span class="dlbl">Saldo: </span><span class="dval"><?php echo $balance > 0 ? '$' . number_format($balance, 2) : 'SALDADO'; ?></span></div>

    <div style="margin:4px 0;">
        <div style="display:table;width:100%;font-size:8px;margin-bottom:2px;">
            <span style="display:table-cell;">Avance</span>
            <span style="display:table-cell;text-align:right;font-weight:bold;"><?php echo $pct; ?>%</span>
        </div>
        <div class="prog-outer">
            <div class="prog-inner" style="width:<?php echo $pct; ?>%;"></div>
        </div>
    </div>

    <div class="dashed"></div>

    <div class="slabel">Historial</div>
    <table class="pay">
        <thead>
            <tr>
                <th>#</th>
                <th>Fecha</th>
                <th>Met.</th>
                <th class="r">Monto</th>
            </tr>
        </thead>
        <tbody>
            <?php $i=1; foreach($payments as $p): ?>
            <tr class="<?php echo $p->id === $payment->id ? 'cur' : ''; ?>">
                <td><?php echo $i; ?></td>
                <td><?php echo $p->payment_date->format('d/m/y'); ?></td>
                <td><?php echo isset($shortMethod[$p->payment_method]) ? $shortMethod[$p->payment_method] : $p->payment_method; ?><?php echo $p->id === $payment->id ? ' *' : ''; ?></td>
                <td class="r">$<?php echo number_format($p->amount, 2); ?></td>
            </tr>
            <?php $i++; endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3">TOTAL</td>
                <td class="r">$<?php echo number_format($totalPaid, 2); ?></td>
            </tr>
        </tfoot>
    </table>

    <?php if($payment->notes): ?>
    <div class="dashed"></div>
    <div class="slabel">Notas</div>
    <div class="drow"><?php echo $payment->notes; ?></div>
    <?php endif; ?>

    <div class="solid"></div>

    {{-- Espacio para firmas --}}
    <table class="sig-table">
        <tr>
            <td style="width:50%;">
                <div style="height:36px;"></div>
                <div class="sig-line" style="width:85%;margin:0 auto 2px;"></div>
                <div>Odontologo / Recepcionista</div>
            </td>
            <td style="width:50%;">
                <div style="height:36px;"></div>
                <div class="sig-line" style="width:85%;margin:0 auto 2px;"></div>
                <div>Paciente</div>
            </td>
        </tr>
    </table>

    <div class="solid"></div>

    <div class="footer">
        <div style="font-weight:bold;">Gracias por su confianza!</div>
        <div>Conserve este comprobante.</div>
    </div>

    <div class="dashed"></div>
    <div class="code">PAY-<?php echo $payNum; ?>-<?php echo $payment->payment_date->format('Ymd'); ?></div>

</body>

</html>
