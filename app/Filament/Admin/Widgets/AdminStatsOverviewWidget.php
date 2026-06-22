<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Order;
use App\Models\QuoteRequest;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdminStatsOverviewWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $todayOrders = Order::whereDate('created_at', today())->count();

        $pendingOrders = Order::where('status', 'beklemede')->count();

        $monthRevenue = (float) Order::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->whereNotIn('status', ['iptal_edildi', 'iade_edildi'])
            ->sum('total');

        $openQuotes = QuoteRequest::where('status', 'yeni')->count();

        return [
            Stat::make('Bugünkü Siparişler', $todayOrders)
                ->description('Bugün alınan siparişler')
                ->icon('heroicon-o-shopping-cart')
                ->color('primary'),

            Stat::make('Bekleyen Siparişler', $pendingOrders)
                ->description('İşlem bekleyen siparişler')
                ->icon('heroicon-o-clock')
                ->color('warning'),

            Stat::make('Bu Ayın Cirosu', '₺' . number_format($monthRevenue, 2, ',', '.'))
                ->description(now()->format('m/Y') . ' — iptal hariç')
                ->icon('heroicon-o-banknotes')
                ->color('success'),

            Stat::make('Açık Teklif Talepleri', $openQuotes)
                ->description('Yanıt bekleyen yeni talepler')
                ->icon('heroicon-o-document-text')
                ->color('info'),
        ];
    }
}
