<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ödemeniz Onaylandı</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f5f0e8; margin: 0; padding: 0; }
        .wrapper { max-width: 600px; margin: 30px auto; background: #fff; border-radius: 8px; overflow: hidden; }
        .header { background: #2C5F2E; color: #fff; padding: 24px 32px; }
        .header h1 { margin: 0; font-size: 22px; }
        .header p { margin: 4px 0 0; font-size: 13px; color: #a8d5aa; }
        .body { padding: 28px 32px; color: #333; }
        .body p { font-size: 15px; line-height: 1.6; margin: 0 0 12px; }
        .section-title { font-size: 14px; font-weight: bold; color: #3E2006; border-bottom: 1px solid #e6dfd2; padding-bottom: 6px; margin: 20px 0 10px; }
        .summary-box { background: #f0faf0; border: 1px solid #a8d5aa; border-radius: 6px; padding: 14px 16px; font-size: 14px; }
        .footer { background: #f5f0e8; padding: 16px 32px; font-size: 12px; color: #888; text-align: center; }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="header">
        <h1>CNRWOOD</h1>
        <p>Ödeme Onayı</p>
    </div>
    <div class="body">
        <p>Sayın <strong>{{ $order->customer_name }}</strong>,</p>
        <p>Havale / EFT ödemeniz onaylanmıştır. Siparişiniz en kısa sürede hazırlanmaya başlanacaktır.</p>

        <div class="summary-box">
            <strong style="color:#2C5F2E;">&#10003; Ödeme Onaylandı</strong><br><br>
            <strong>Sipariş No:</strong> {{ $order->order_number }}<br>
            <strong>Tarih:</strong> {{ $order->created_at->format('d.m.Y') }}<br>
            <strong>Toplam:</strong> {{ number_format($order->total, 2, ',', '.') }} TL<br>
            <strong>Durum:</strong> İşleme Alındı
        </div>

        <p style="margin-top:20px; color:#555; font-size:13px;">Siparişinizin kargo takip bilgileri hazır olduğunda ayrıca bilgilendirileceksiniz.</p>
        <p style="color:#555; font-size:13px;">Herhangi bir sorunuz için bizimle iletişime geçebilirsiniz.</p>
    </div>
    <div class="footer">
        &copy; {{ date('Y') }} CNRWOOD &mdash; Bu e-posta otomatik olarak gönderilmiştir.
    </div>
</div>
</body>
</html>
