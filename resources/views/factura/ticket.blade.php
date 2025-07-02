<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Factura CFDI #{{ $factura->folio }}</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 13px;
            margin: 0;
            padding: 0;
            background: #e8f2fc;
            color: #222;
        }
        .factura-container {
            background: #fff;
            width: 650px;
            margin: 30px auto;
            padding: 32px 38px;
            border-radius: 7px;
            box-shadow: 0 2px 10px #0002;
            border: 1px solid #b4d2f7;
        }
        .header {
            margin-bottom: 8px;
        }
        .business-name {
            font-size: 1.35em;
            font-weight: bold;
            color: #062855;
        }
        .business-info, .cfdi-label {
            font-size: 13px;
            color: #223;
        }
        .factura-title {
            font-size: 1.1em;
            font-weight: bold;
            color: #234ba3;
            margin: 16px 0 6px 0;
        }
        .divider {
            border-top: 2px solid #b4d2f7;
            margin: 15px 0 10px 0;
        }
        .ticket-info, .cliente-info {
            font-size: 13px;
            line-height: 1.6;
            margin-bottom: 8px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }
        .table thead th {
            background: #d5e8fa;
            color: #062855;
            border-bottom: 2px solid #b4d2f7;
            font-size: 13px;
            text-align: left;
            padding: 4px 6px;
        }
        .table tbody td {
            font-size: 13px;
            padding: 4px 6px;
            border-bottom: 1px solid #e8f2fc;
        }
        .totales {
            text-align: right;
            margin-top: 6px;
            margin-bottom: 10px;
        }
        .tot-row {
            font-size: 13px;
            margin-bottom: 2px;
        }
        .tot-row span:first-child {
            min-width: 90px;
            display: inline-block;
        }
        .cfdi {
            margin-top: 10px;
            font-size: 13px;
        }
        .footer {
            margin-top: 16px;
            font-size: 11px;
            color: #555;
            text-align: left;
        }
        .uuid {
            font-size: 12px;
            margin-bottom: 4px;
        }
        .sello {
            font-size: 11px;
            color: #888;
            margin-bottom: 2px;
        }
        .qr-section {
            float: right;
            width: 120px;
            text-align: center;
            margin-top: -50px;
        }
        .qr-section img {
            width: 100px;
            height: 100px;
        }
        .clearfix { clear: both; }
        .label {
            font-weight: bold;
        }
        @page { margin: 0; }
        html, body { width: 100%; }
    </style>
</head>
<body>
    <div class="factura-container">
        <div class="header">
            <div class="business-name">ABARROTES ADS S. A. DE C. V.</div>
            <div class="business-info">RFC: ADS250211X3B &nbsp; Régimen: 601 – General de Ley Personas Morales</div>
            <div class="business-info">Av. Juan de Dios Bátiz, Nueva Industrial Vallejo, Gustavo A. Madero, 07320, Ciudad de México, CDMX</div>
        </div>
        <div class="factura-title">Factura electrónica CFDI 4.0</div>
        <div class="divider"></div>
        <div class="ticket-info">
            <span class="label">Serie A Folio</span> {{ $factura->folio }}<br>
            <span class="label">Fecha de emisión:</span> {{ \Carbon\Carbon::parse($factura->fecha)->format('d/m/Y H:i') }}
            &nbsp;&nbsp; <span class="label">Lugar de expedición:</span> 07320
        </div>
        <div class="cliente-info">
            <span class="label">Cliente:</span> {{ $factura->razon_social }}<br>
            RFC: {{ $factura->rfc }}<br>
            Uso CFDI: {{ $factura->uso_cfdi }}
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>Cant</th>
                    <th>Clave SAT</th>
                    <th>Descripción</th>
                    <th>P. Unit</th>
                    <th>Importe</th>
                    <th>IVA 16 %</th>
                </tr>
            </thead>
            <tbody>
            @foreach($factura->venta->detalles as $detalle)
                <tr>
                    <td>{{ $detalle->cantidad }}</td>
                    <td>{{ $detalle->producto->clave_sat ?? '' }}</td>
                    <td>{{ $detalle->producto->producto ?? 'Producto eliminado' }}</td>
                    <td>${{ number_format($detalle->precio, 2) }}</td>
                    <td>${{ number_format($detalle->precio * $detalle->cantidad, 2) }}</td>
                    <td>${{ number_format($detalle->precio * $detalle->cantidad * 0.16, 2) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="totales">
            <div class="tot-row">
                <span>Subtotal:</span>
                <span>${{ number_format($factura->total, 2) }}</span>
            </div>
            <div class="tot-row">
                <span>IVA 16%:</span>
                <span>${{ number_format($factura->total * 0.16, 2) }}</span>
            </div>
            <div class="tot-row">
                <span>Total:</span>
                <span>${{ number_format($factura->total , 2) }}</span>
            </div>
        </div>
        <div class="cfdi">
            <b>Forma de pago:</b> 03 – Transferencia &nbsp;
            <b>Método:</b> PUE – Pago en una sola exhibición
        </div>
        <div class="qr-section">
        </div>
        <div class="clearfix"></div>
        <div class="divider"></div>
        <div class="footer">
            <div class="uuid">UUID: E2F3A4B5-6789-4ABC-DEF0-1234567890AB</div>
            Este documento es una representación impresa de un CFDI.<br>
            <div class="sello">Sello digital CFDI: ...TRUNCADO...</div>
            <div class="sello">Sello del SAT: ...TRUNCADO...</div>
            <div style="margin-top:2px;">{{ now()->format('d/m/Y H:i') }}</div>
        </div>
    </div>
</body>
</html>
