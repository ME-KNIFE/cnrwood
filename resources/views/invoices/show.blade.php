<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Fatura — {{ $order->order_number }}</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; color: #222; margin: 0; padding: 24px; }
        .page { max-width: 750px; margin: 0 auto; }
        .header { display: flex; justify-content: space-between; border-bottom: 2px solid #3E2006; padding-bottom: 14px; margin-bottom: 20px; }
        .store-name { font-size: 22px; font-weight: bold; color: #3E2006; }
        .store-info { font-size: 11px; color: #555; margin-top: 4px; line-height: 1.5; }
        .invoice-meta { text-align: right; }
        .invoice-meta .label { font-size: 18px; font-weight: bold; color: #3E2006; }
        .invoice-meta .detail { font-size: 11px; color: #555; margin-top: 4px; line-height: 1.5; }
        .addresses { display: flex; gap: 20px; margin-bottom: 20px; }
        .address-box { flex: 1; background: #f5f0e8; border: 1px solid #e6dfd2; padding: 12px; border-radius: 4px; }
        .address-box h4 { margin: 0 0 6px; font-size: 11px; text-transform: uppercase; color: #8B5A2B; letter-spacing: 0.5px; }
        .address-box p { margin: 0; font-size: 12px; line-height: 1.6; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        thead th { background: #3E2006; color: #fff; padding: 8px 10px; text-align: left; font-size: 12px; }
        thead th:last-child, thead th:nth-child(3), thead th:nth-child(2) { text-align: right; }
        tbody td { padding: 7px 10px; border-bottom: 1px solid #e6dfd2; font-size: 12px; }
        tbody td:last-child, tbody td:nth-child(3), tbody td:nth-child(2) { text-align: right; }
        tbody tr:nth-child(even) td { background: #faf7f2; }
        .totals { width: 280px; margin-left: auto; }
        .totals td { padding: 5px 10px; font-size: 12px; }
        .totals td:last-child { text-align: right; font-weight: 600; }
        .totals .grand td { font-size: 14px; font-weight: bold; color: #3E2006; border-top: 2px solid #3E2006; padding-top: 8px; }
        .footer { margin-top: 30px; border-top: 1px solid #e6dfd2; padding-top: 12px; font-size: 10px; color: #999; text-align: center; }
        .status-badge { display: inline-block; padding: 2px 8px; border-radius: 10px; font-size: 10px; background: #e6dfd2; color: #3E2006; }
    </style>
</head>
<body>
<div class="page">

    <div class="header">
        <div>
            <div class="store-name">{{ $storeName }}</div>
            @if ($storeAddress)
            <div class="store-info">{{ $storeAddress }}</div>
            @endif
            @if ($storeTax)
            <div class="store-info">Vergi No: {{ $storeTax }}</div>
            @endif
        </div>
        <div class="invoice-meta">
            <div class="label">FATURA</div>
            <div class="detail">
                <strong>Sipariş No:</strong> {{ $order->order_number }}<br>
                <strong>Tarih:</strong> {{ $order->created_at->format('d.m.Y') }}<br>
                <strong>Ödeme:</strong>
                @if ($order->payment_method === 'havale_eft') Havale / EFT
                @elseif ($order->payment_method === 'kredi_karti') Kredi Kartı
                @else {{ $order->payment_method }}
                @endif
            </div>
        </div>
    </div>

    <div class="addresses">
        <div class="address-box">
            <h4>Müşteri</h4>
            <p>
                <strong>{{ $order->customer_name }}</strong><br>
                @if ($order->customer_email){{ $order->customer_email }}<br>@endif
                @if ($order->customer_phone){{ $order->customer_phone }}@endif
            </p>
        </div>
        <div class="address-box">
            <h4>Teslimat Adresi</h4>
            <p>
                {{ $order->shipping_address['full_name'] ?? $order->customer_name }}<br>
                {{ $order->shipping_address['address_line1'] ?? '' }}<br>
                @if (!empty($order->shipping_address['address_line2'])){{ $order->shipping_address['address_line2'] }}<br>@endif
                {{ $order->shipping_address['district'] ?? '' }}
                {{ $order->shipping_address['city'] ?? '' }}<br>
                {{ $order->shipping_address['country'] ?? 'Türkiye' }}
            </p>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Ürün</th>
                <th>SKU</th>
                <th>Adet</th>
                <th>Birim Fiyat</th>
                <th>Toplam</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->items as $item)
            <tr>
                <td>
                    {{ $item->product_name }}
                    @if ($item->variant_name)
                        <br><span style="color:#888; font-size:11px">{{ $item->variant_name }}</span>
                    @endif
                </td>
                <td>{{ $item->product_sku ?? '—' }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ number_format($item->unit_price, 2, ',', '.') }} TL</td>
                <td>{{ number_format($item->total_price, 2, ',', '.') }} TL</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <table class="totals">
        <tr>
            <td style="color:#555">Ara Toplam</td>
            <td>{{ number_format($order->subtotal, 2, ',', '.') }} TL</td>
        </tr>
        @if ($order->discount_amount > 0)
        <tr>
            <td style="color:#555">
                İndirim@if ($order->coupon_code) ({{ $order->coupon_code }})@endif
            </td>
            <td style="color:#2C5F2E">−{{ number_format($order->discount_amount, 2, ',', '.') }} TL</td>
        </tr>
        @endif
        <tr>
            <td style="color:#555">Kargo</td>
            <td>
                @if ($order->shipping_cost > 0)
                    {{ number_format($order->shipping_cost, 2, ',', '.') }} TL
                @else
                    Ücretsiz
                @endif
            </td>
        </tr>
        <tr class="grand">
            <td>Toplam (KDV Dahil)</td>
            <td>{{ number_format($order->total, 2, ',', '.') }} TL</td>
        </tr>
    </table>

    @if ($order->notes)
    <p style="font-size:11px; color:#666"><strong>Not:</strong> {{ $order->notes }}</p>
    @endif

    <div class="footer">
        {{ $storeName }} &mdash; Oluşturulma: {{ now()->format('d.m.Y H:i') }} &mdash; Bu belge bilgi amaçlıdır, yasal fatura değildir.
    </div>

</div>
</body>
</html>
