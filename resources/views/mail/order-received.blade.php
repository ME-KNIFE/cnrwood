<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Siparişiniz Alındı</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f5f0e8; margin: 0; padding: 0; }
        .wrapper { max-width: 600px; margin: 30px auto; background: #fff; border-radius: 8px; overflow: hidden; }
        .header { background: #3E2006; color: #fff; padding: 24px 32px; }
        .header h1 { margin: 0; font-size: 22px; }
        .header p { margin: 4px 0 0; font-size: 13px; color: #d9b99a; }
        .body { padding: 28px 32px; color: #333; }
        .body p { font-size: 15px; line-height: 1.6; margin: 0 0 12px; }
        .section-title { font-size: 14px; font-weight: bold; color: #3E2006; border-bottom: 1px solid #e6dfd2; padding-bottom: 6px; margin: 20px 0 10px; }
        table { width: 100%; border-collapse: collapse; font-size: 14px; }
        th { text-align: left; padding: 6px 8px; background: #f5f0e8; color: #555; font-weight: 600; }
        td { padding: 6px 8px; border-bottom: 1px solid #f0ece4; }
        .totals { margin-top: 10px; font-size: 14px; }
        .totals tr td:last-child { text-align: right; }
        .totals .grand-total td { font-weight: bold; font-size: 15px; color: #3E2006; border-top: 2px solid #3E2006; }
        .footer { background: #f5f0e8; padding: 16px 32px; font-size: 12px; color: #888; text-align: center; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 12px; font-size: 12px; background: #e6dfd2; color: #3E2006; }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="header">
        <h1>CNRWOOD</h1>
        <p>Sipariş Onayı</p>
    </div>
    <div class="body">
        <p>Sayın <strong>{{ $order->customer_name }}</strong>,</p>
        <p>Siparişiniz başarıyla alındı. En kısa sürede işleme alınacaktır.</p>

        <div class="section-title">Sipariş Bilgileri</div>
        <p>
            <strong>Sipariş No:</strong> {{ $order->order_number }}<br>
            <strong>Tarih:</strong> {{ $order->created_at->format('d.m.Y H:i') }}<br>
            <strong>Ödeme Yöntemi:</strong>
            @if ($order->payment_method === 'havale_eft') Havale / EFT @else {{ $order->payment_method }} @endif
        </p>

        <div class="section-title">Ürünler</div>
        <table>
            <thead>
                <tr>
                    <th>Ürün</th>
                    <th style="text-align:center">Adet</th>
                    <th style="text-align:right">Tutar</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->items as $item)
                <tr>
                    <td>
                        {{ $item->product_name }}
                        @if ($item->variant_name)
                            <br><small style="color:#888">{{ $item->variant_name }}</small>
                        @endif
                    </td>
                    <td style="text-align:center">{{ $item->quantity }}</td>
                    <td style="text-align:right">{{ number_format($item->total_price, 2, ',', '.') }} TL</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <table class="totals" style="margin-top:10px">
            <tr>
                <td style="color:#555">Ara Toplam</td>
                <td style="text-align:right">{{ number_format($order->subtotal, 2, ',', '.') }} TL</td>
            </tr>
            @if ($order->discount_amount > 0)
            <tr>
                <td style="color:#555">
                    İndirim@if ($order->coupon_code) ({{ $order->coupon_code }})@endif
                </td>
                <td style="text-align:right; color:#2C5F2E">−{{ number_format($order->discount_amount, 2, ',', '.') }} TL</td>
            </tr>
            @endif
            <tr>
                <td style="color:#555">Kargo</td>
                <td style="text-align:right">
                    @if ($order->shipping_cost > 0)
                        {{ number_format($order->shipping_cost, 2, ',', '.') }} TL
                    @else
                        Ücretsiz
                    @endif
                </td>
            </tr>
            <tr class="grand-total">
                <td>Toplam</td>
                <td style="text-align:right">{{ number_format($order->total, 2, ',', '.') }} TL</td>
            </tr>
        </table>

        <div class="section-title" style="margin-top:20px">Teslimat Adresi</div>
        <p>
            {{ $order->shipping_address['full_name'] ?? $order->customer_name }}<br>
            {{ $order->shipping_address['address_line1'] ?? '' }}
            @if (!empty($order->shipping_address['address_line2']))
                <br>{{ $order->shipping_address['address_line2'] }}
            @endif
            <br>
            {{ $order->shipping_address['district'] ?? '' }}
            {{ $order->shipping_address['city'] ?? '' }}
            @if (!empty($order->shipping_address['postal_code']))
                / {{ $order->shipping_address['postal_code'] }}
            @endif
            <br>{{ $order->shipping_address['country'] ?? 'Türkiye' }}
        </p>

        @if ($order->payment_method === 'havale_eft')
        <div style="background:#fff8e1; border:1px solid #f9c740; border-radius:6px; padding:14px 16px; margin-top:16px; font-size:14px;">
            <strong style="color:#856404;">Havale / EFT Bilgileri</strong><br>
            Lütfen sipariş numaranızı (<strong>{{ $order->order_number }}</strong>) açıklama olarak belirterek ödemenizi gerçekleştirin. Ödemeniz onaylandıktan sonra siparişiniz işleme alınacaktır.
        </div>
        @endif

        <p style="margin-top:20px; color:#555; font-size:13px;">Herhangi bir sorunuz için bizimle iletişime geçebilirsiniz.</p>
    </div>
    <div class="footer">
        &copy; {{ date('Y') }} CNRWOOD &mdash; Bu e-posta otomatik olarak gönderilmiştir.
    </div>
</div>
</body>
</html>
