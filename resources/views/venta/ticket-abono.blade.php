<!DOCTYPE html>
<html>
<head>
    <title>Ticket de Abono</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Arial', sans-serif; }
        body { padding: 10px; }
        .ticket { width: 80mm; padding: 10px; font-size: 12px; }
        .header { text-align: center; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 1px dashed #333; }
        .logo { max-width: 80px; margin-bottom: 10px; }
        .business-name { font-size: 18px; font-weight: bold; margin-bottom: 5px; }
        .business-info { font-size: 12px; color: #555; margin-bottom: 5px; }
        .ticket-info { margin: 15px 0; font-size: 13px; }
        .ticket-info p { margin: 3px 0; }
        .divider { border-top: 1px dashed #333; margin: 10px 0; }
        .total-section { margin-top: 15px; font-size: 14px; }
        .total-row { display: flex; justify-content: space-between; margin: 5px 0; }
        .total-label, .total-value { font-weight: bold; }
        .footer { margin-top: 20px; text-align: center; font-size: 11px; color: #777; }
        .thank-you { margin-top: 15px; font-style: italic; text-align: center; }
        .payment-method { margin: 10px 0; padding: 8px; background: #f5f5f5; border-radius: 4px; font-size: 13px; }
    </style>
</head>
<body>
    <div class="ticket">
        <div class="header">
            @if($company && $company->logo)
                <img src="{{ asset('storage/img/Logo-Negro.jpeg') }}" class="logo" alt="Logo">
            @endif
            <div class="business-name">{{ $company->nombre }}</div>
            <div class="business-info">{{ $company->direccion }}</div>
            <div class="business-info">Tel: {{ $company->telefono }}</div>
            <div class="business-info"><strong>TICKET DE ABONO</strong></div>
        </div>

        <div class="ticket-info">
            <p><strong>Ticket:</strong> #{{ str_pad($venta->id, 6, '0', STR_PAD_LEFT) }}</p>
            <p><strong>Fecha:</strong> {{ now()->format('Y-m-d H:i') }}</p>
            <p><strong>Atendido por:</strong> {{ $venta->user->name ?? ($user->name ?? 'Sistema') }}</p>
        </div>

        <div class="payment-method">
            <strong>Método de pago:</strong> {{ strtoupper($venta->metodo_pago ?? 'EFECTIVO') }}
        </div>

        {{-- Sección financiera --}}
        <div class="total-section">
<div class="total-row">
    <span class="total-label">Deuda original:</span>
    <span class="total-value">${{ number_format($deuda_original, 2) }}</span>
</div>
            <div class="total-row">
                <span class="total-label">Deuda anterior:</span>
                <span class="total-value">${{ number_format($montoInicial, 2) }}</span>
            </div>
            <div class="total-row">
                <span class="total-label">Abono realizado:</span>
                <span class="total-value">${{ number_format($montoInicial - $cliente->total_compra, 2) }}</span>
            </div>
            <div class="total-row">
                <span class="total-label">Deuda restante:</span>
                <span class="total-value">${{ number_format($cliente->total_compra, 2) }}</span>
            </div>
<div class="total-row">
    <span class="total-label">Total en letras:</span>
    <span class="total-value">{{ ucfirst($totalEnLetras) }}</span>
</div>
        </div>

        <div class="divider"></div>

        <div class="thank-you">
            ¡Gracias por su pago!
        </div>

        <div class="footer">
            {{ $company->mensaje_ticket ?? 'Este ticket es su comprobante' }}<br>
            {{ now()->format('d/m/Y H:i:s') }}
        </div>
    </div>
</body>
</html>

@php
    header("Content-type: application/pdf");
@endphp
