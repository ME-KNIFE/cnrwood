<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function loginForm(): View
    {
        return view('account.auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $this->mergeGuestCart($request->session()->getId());
            $request->session()->regenerate();

            return redirect()->intended(route('account.dashboard'));
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => 'E-posta veya şifre hatalı.']);
    }

    public function registerForm(): View
    {
        return view('account.auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'                  => ['required', 'string', 'max:255'],
            'email'                 => ['required', 'email', 'max:255', 'unique:users'],
            'phone'                 => ['nullable', 'string', 'max:50'],
            'password'              => ['required', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'phone'    => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
        ]);

        Auth::login($user);
        $this->mergeGuestCart($request->session()->getId());
        $request->session()->regenerate();

        return redirect()->route('account.dashboard')
            ->with('success', 'Hesabınız oluşturuldu. Hoş geldiniz!');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    // ── Private ───────────────────────────────────────────────────────────────

    /**
     * Merge any items from the guest session cart into the authenticated user's cart.
     * Called before session regeneration so the old session ID is still valid.
     * Duplicate product+variant combos are merged by summing quantities (capped at 99).
     */
    private function mergeGuestCart(string $guestSessionId): void
    {
        $guestCart = Cart::where('session_id', $guestSessionId)
            ->with('items')
            ->first();

        if (! $guestCart || $guestCart->items->isEmpty()) {
            return;
        }

        $userCart = Cart::firstOrCreate(['user_id' => Auth::id()]);

        DB::transaction(function () use ($guestCart, $userCart): void {
            foreach ($guestCart->items as $guestItem) {
                $existing = CartItem::where('cart_id', $userCart->id)
                    ->where('product_id', $guestItem->product_id)
                    ->where('product_variant_id', $guestItem->product_variant_id)
                    ->first();

                if ($existing) {
                    $existing->quantity = min($existing->quantity + $guestItem->quantity, 99);
                    $existing->save();
                } else {
                    CartItem::create([
                        'cart_id'            => $userCart->id,
                        'product_id'         => $guestItem->product_id,
                        'product_variant_id' => $guestItem->product_variant_id,
                        'quantity'           => $guestItem->quantity,
                        'unit_price'         => $guestItem->unit_price,
                    ]);
                }
            }

            // Remove the guest cart after merging
            $guestCart->items()->delete();
            $guestCart->delete();
        });
    }
}
