<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderInvoiceController extends Controller
{
    public function download(Order $order)
    {
        $order->load('items');

        $storeName    = Setting::get('site_name', 'CNRWOOD');
        $storeAddress = Setting::get('site_address', '');
        $storeTax     = Setting::get('site_tax_number', '');

        $pdf = Pdf::loadView('invoices.show', compact('order', 'storeName', 'storeAddress', 'storeTax'));

        return $pdf->download("siparis-{$order->order_number}.pdf");
    }
}
