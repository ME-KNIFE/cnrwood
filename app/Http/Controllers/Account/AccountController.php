<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function dashboard(): View
    {
        $user = auth()->user();

        $latestOrders = $user->orders()
            ->with(['items'])
            ->latest()
            ->take(5)
            ->get();

        $totalOrders = $user->orders()->count();

        return view('account.dashboard', compact('user', 'latestOrders', 'totalOrders'));
    }

    public function orders(): View
    {
        $user = auth()->user();

        $orders = $user->orders()
            ->with(['items'])
            ->latest()
            ->paginate(15);

        return view('account.orders', compact('user', 'orders'));
    }

    public function orderDetail(Order $order): View
    {
        // IDOR protection: gate on authenticated user ownership
        abort_unless(auth()->id() === $order->user_id, 403);

        $order->load(['items.product', 'items.variant', 'payments', 'shipments']);

        return view('account.order-detail', compact('order'));
    }

    public function profile(): View
    {
        return view('account.profile', ['user' => auth()->user()]);
    }

    public function updateProfile(UpdateProfileRequest $request): RedirectResponse
    {
        auth()->user()->update($request->validated());

        return redirect()->route('account.profile')
            ->with('success', 'Profiliniz başarıyla güncellendi.');
    }
}
