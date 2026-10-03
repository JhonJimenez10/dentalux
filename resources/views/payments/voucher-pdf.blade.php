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
            font-size: 12px;
            color: #1A1A2E;
            background: #fff;
        }

        .header {
            background: #1A1A2E;
            padding: 24px 32px;
            display: table;
            width: 100%;
        }

        .header-left {
            display: table-cell;
            vertical-align: middle;
        }

        .header-right {
            display: table-cell;
            vertical-align: middle;
            text-align: right;
        }

        .clinic-name {
            font-size: 20px;
            font-weight: bold;
            color: #fff;
            letter-spacing: 1px;
        }

        .clinic-sub {
            font-size: 9px;
            color: rgba(255, 255, 255, 0.45);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 2px;
        }

        .doc-title {
            font-size: 18px;
            font-weight: bold;
            color: #fff;
        }

        .doc-num {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.5);
            margin-top: 3px;
        }

        .amount-band {
            background: #C8395A;
            padding: 16px 32px;
            display: table;
            width: 100%;
        }

        .amount-band-left {
            display: table-cell;
            vertical-align: middle;
        }

        .amount-band-right {
            display: table-cell;
            vertical-align: middle;
            text-align: right;
        }

        .amount-value {
            font-size: 28px;
            font-weight: bold;
            color: #fff;
        }

        .amount-label {
            font-size: 10px;
            color: rgba(255, 255, 255, 0.7);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }

        .amount-method {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.8);
            margin-top: 3px;
            text-transform: uppercase;
        }

        .amount-date {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.8);
        }

        .body {
            padding: 28px 32px;
        }

        .section-title {
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #C8395A;
            border-bottom: 2px solid #F4D0D8;
            padding-bottom: 6px;
            margin-bottom: 10px;
            margin-top: 20px;
        }

        .section-title:first-child {
            margin-top: 0;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6px;
        }

        .info-table td {
            padding: 7px 10px;
            font-size: 12px;
            border-bottom: 1px solid #F3F4F6;
        }

        .info-table .label {
            color: #6B7280;
            width: 40%;
        }

        .info-table .value {
            font-weight: bold;
            color: #1A1A2E;
        }

        .info-table .value.green {
            color: #059669;
        }

        .info-table .value.red {
            color: #DC2626;
        }

        .info-table .value.rose {
            color: #C8395A;
        }

        .info-table tr:last-child td {
            border-bottom: none;
        }

        .two-col {
            display: table;
            width: 100%;
        }

        .col-left {
            display: table-cell;
            width: 50%;
            padding-right: 12px;
            vertical-align: top;
        }

        .col-right {
            display: table-cell;
            width: 50%;
            padding-left: 12px;
            vertical-align: top;
        }

        .prog-row {
            display: table;
            width: 100%;
            font-size: 11px;
            margin-bottom: 5px;
        }

        .prog-row .l {
            display: table-cell;
            color: #6B7280;
        }

        .prog-row .r {
            display: table-cell;
            text-align: right;
            font-weight: bold;
            color: #C8395A;
        }

        .prog-outer {
            width: 100%;
            height: 8px;
            background: #E5E7EB;
            border-radius: 4px;
        }

        .prog-inner {
            height: 8px;
            border-radius: 4px;
        }

        table.pays {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
            margin-top: 6px;
        }

        table.pays thead th {
            background: #1A1A2E;
            color: #fff;
            padding: 8px 10px;
            text-align: left;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        table.pays thead th.r {
            text-align: right;
        }

        table.pays tbody td {
            padding: 8px 10px;
            border-bottom: 1px solid #F3F4F6;
            font-size: 11px;
        }

        table.pays tbody tr.cur td {
            background: #FFF5F7;
            font-weight: bold;
        }

        table.pays tbody td.r {
            text-align: right;
        }

        table.pays tfoot td {
            padding: 8px 10px;
            border-top: 2px solid #1A1A2E;
            font-weight: bold;
            font-size: 12px;
        }

        table.pays tfoot td.r {
            text-align: right;
            color: #059669;
        }

        .saldo-box {
            border: 2px solid #E5E7EB;
            border-radius: 6px;
            padding: 12px 16px;
            text-align: center;
            margin-top: 10px;
        }

        .saldo-box.paid {
            border-color: #A7F3D0;
            background: #F0FDF4;
        }

        .saldo-box.pending {
            border-color: #FECACA;
            background: #FFF5F5;
        }

        .saldo-lbl {
            font-size: 10px;
            color: #6B7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }

        .saldo-val {
            font-size: 20px;
            font-weight: bold;
        }

        .saldo-val.green {
            color: #059669;
        }

        .saldo-val.red {
            color: #DC2626;
        }

        .footer {
            background: #F8F7F5;
            border-top: 1px solid #E5E7EB;
            padding: 14px 32px;
            display: table;
            width: 100%;
            margin-top: 28px;
        }

        .footer-left {
            display: table-cell;
            vertical-align: middle;
        }

        .footer-right {
            display: table-cell;
            vertical-align: middle;
            text-align: right;
        }

        .footer-clinic {
            font-size: 11px;
            font-weight: bold;
            color: #1A1A2E;
        }

        .footer-info {
            font-size: 10px;
            color: #9CA3AF;
            margin-top: 2px;
        }

        .footer-code {
            font-size: 10px;
            color: #9CA3AF;
            letter-spacing: 1px;
        }
    </style>
</head>

<body>

    <?php
    $totalPaid = $paymentControl->total_paid;
    $totalAmount = $paymentControl->treatment_amount;
    $balance = $paymentControl->balance;
    $pct = $totalAmount > 0 ? min(100, round(($totalPaid / $totalAmount) * 100)) : 0;
    $pctColor = $pct >= 100 ? '#059669' : '#C8395A';
    $payments = $paymentControl->payments->sortBy('payment_date');
    $isPaid = $balance <= 0;
    $payNum = str_pad($payment->id, 6, '0', STR_PAD_LEFT);
    $shortMethod = [
        'efectivo' => 'Efectivo',
        'transferencia' => 'Transferencia',
        'tarjeta_credito' => 'Tarjeta Credito',
        'tarjeta_debito' => 'Tarjeta Debito',
        'cheque' => 'Cheque',
    ];
    ?>

    <div class="header">
        <div class="header-left">
            <div class="clinic-name">DENTALUX</div>
            <div class="clinic-sub">Odontologia Familiar</div>
        </div>
        <div class="header-right">
            <div class="doc-title">COMPROBANTE DE PAGO</div>
            <div class="doc-num">PAY-<?php echo $payNum; ?> | <?php echo $payment->payment_date->format('d/m/Y'); ?></div>
        </div>
    </div>

    <div class="amount-band">
        <div class="amount-band-left">
            <div class="amount-label">Pago recibido</div>
            <div class="amount-value">$<?php echo number_format($payment->amount, 2); ?></div>
            <div class="amount-method"><?php echo isset($shortMethod[$payment->payment_method]) ? $shortMethod[$payment->payment_method] : $payment->payment_method; ?></div>
        </div>
        <div class="amount-band-right">
            <div class="amount-date"><?php echo $payment->payment_date->format('d/m/Y'); ?></div>
        </div>
    </div>

    <div class="body">

        <div class="two-col">
            <div class="col-left">
                <div class="section-title">Datos del Pago</div>
                <table class="info-table">
                    <tr>
                        <td class="label">N. Comprobante</td>
                        <td class="value rose">PAY-<?php echo $payNum; ?></td>
                    </tr>
                    <tr>
                        <td class="label">Fecha</td>
                        <td class="value"><?php echo $payment->payment_date->format('d/m/Y'); ?></td>
                    </tr>
                    <tr>
                        <td class="label">Hora</td>
                        <td class="value"><?php echo $payment->created_at->format('H:i'); ?></td>
                    </tr>
                    <tr>
                        <td class="label">Metodo</td>
                        <td class="value"><?php echo isset($shortMethod[$payment->payment_method]) ? $shortMethod[$payment->payment_method] : $payment->payment_method; ?></td>
                    </tr>
                    <?php if($payment->reference): ?>
                    <tr>
                        <td class="label">Referencia</td>
                        <td class="value"><?php echo $payment->reference; ?></td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                        <td class="label">Registrado por</td>
                        <td class="value"><?php echo $payment->registeredBy ? $payment->registeredBy->name : '-'; ?></td>
                    </tr>
                </table>
            </div>
            <div class="col-right">
                <div class="section-title">Paciente</div>
                <table class="info-table">
                    <tr>
                        <td class="label">Nombre</td>
                        <td class="value"><?php echo strtoupper($patient->full_name); ?></td>
                    </tr>
                    <?php if($patient->cedula): ?><tr>
                        <td class="label">Cedula</td>
                        <td class="value"><?php echo $patient->cedula; ?></td>
                    </tr><?php endif; ?>
                    <?php if($patient->phone): ?><tr>
                        <td class="label">Telefono</td>
                        <td class="value"><?php echo $patient->phone; ?></td>
                    </tr><?php endif; ?>
                    <?php if($patient->email): ?><tr>
                        <td class="label">Email</td>
                        <td class="value"><?php echo $patient->email; ?></td>
                    </tr><?php endif; ?>
                    <?php if($patient->city): ?><tr>
                        <td class="label">Ciudad</td>
                        <td class="value"><?php echo $patient->city; ?></td>
                    </tr><?php endif; ?>
                </table>
            </div>
        </div>

        <div class="section-title" style="margin-top:20px;">Resumen del Tratamiento</div>

        <div class="two-col">
            <div class="col-left">
                <table class="info-table">
                    <tr>
                        <td class="label">Total tratamiento</td>
                        <td class="value">$<?php echo number_format($totalAmount, 2); ?></td>
                    </tr>
                    <tr>
                        <td class="label">Este abono</td>
                        <td class="value green">+ $<?php echo number_format($payment->amount, 2); ?></td>
                    </tr>
                    <tr>
                        <td class="label">Total abonado</td>
                        <td class="value green">$<?php echo number_format($totalPaid, 2); ?></td>
                    </tr>
                </table>
                <div style="margin-top:10px;">
                    <div class="prog-row"><span class="l">Avance del tratamiento</span><span
                            class="r"><?php echo $pct; ?>%</span></div>
                    <div class="prog-outer">
                        <div class="prog-inner" style="width:<?php echo $pct; ?>%;background:<?php echo $pctColor; ?>;">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-right">
                <div class="saldo-box <?php echo $isPaid ? 'paid' : 'pending'; ?>">
                    <div class="saldo-lbl">Saldo Pendiente</div>
                    <div class="saldo-val <?php echo $isPaid ? 'green' : 'red'; ?>"><?php echo $isPaid ? 'SALDADO' : '$' . number_format($balance, 2); ?></div>
                    <div style="font-size:10px;margin-top:4px;color:<?php echo $isPaid ? '#059669' : '#DC2626'; ?>;">
                        <?php echo $isPaid ? 'Tratamiento completamente pagado' : $payments->count() . ' pago(s) registrado(s)'; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="section-title" style="margin-top:20px;">Historial de Abonos</div>
        <table class="pays">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Fecha</th>
                    <th>Metodo</th>
                    <th>Referencia</th>
                    <th class="r">Monto</th>
                    <th class="r">Saldo</th>
                </tr>
            </thead>
            <tbody>
                <?php $running = $totalAmount; $i = 1; foreach($payments as $p): $running -= $p->amount; ?>
                <tr class="<?php echo $p->id === $payment->id ? 'cur' : ''; ?>">
                    <td><?php echo $i; ?></td>
                    <td><?php echo $p->payment_date->format('d/m/Y'); ?></td>
                    <td><?php echo isset($shortMethod[$p->payment_method]) ? $shortMethod[$p->payment_method] : $p->payment_method; ?></td>
                    <td style="font-size:10px;color:#6B7280;"><?php echo $p->reference ?: '-'; ?></td>
                    <td class="r" style="color:#059669;font-weight:bold;">$<?php echo number_format($p->amount, 2); ?></td>
                    <td class="r" style="color:<?php echo $running <= 0 ? '#059669' : '#DC2626'; ?>;">$<?php echo number_format(max(0, $running), 2); ?></td>
                </tr>
                <?php $i++; endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4">TOTAL ABONADO</td>
                    <td class="r">$<?php echo number_format($totalPaid, 2); ?></td>
                    <td></td>
                </tr>
            </tfoot>
        </table>

        <?php if($payment->notes): ?>
        <div class="section-title" style="margin-top:20px;">Notas</div>
        <div
            style="background:#F9FAFB;border:1px solid #E5E7EB;border-radius:6px;padding:10px 14px;font-size:11px;color:#374151;">
            <?php echo $payment->notes; ?>
        </div>
        <?php endif; ?>

    </div>

    <div class="footer">
        <div class="footer-left">
            <div class="footer-clinic">Dentalux Odontologia Familiar</div>
            <div class="footer-info">Cuenca, Ecuador | Tel: (07) XXX-XXXX</div>
        </div>
        <div class="footer-right">
            <div class="footer-code">PAY-<?php echo $payNum; ?>-<?php echo $payment->payment_date->format('Ymd'); ?></div>
            <div class="footer-info">Generado el <?php echo now()->format('d/m/Y H:i'); ?></div>
        </div>
    </div>

</body>

</html>
