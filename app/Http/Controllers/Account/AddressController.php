<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAddressRequest;
use App\Models\Address;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AddressController extends Controller
{
    public function index(): View
    {
        $addresses = auth()->user()
            ->addresses()
            ->orderByDesc('is_default_shipping')
            ->orderBy('title')
            ->get();

        return view('account.addresses', compact('addresses'));
    }

    public function create(): View
    {
        return view('account.address-form', ['address' => null]);
    }

    public function store(StoreAddressRequest $request): RedirectResponse
    {
        $user = auth()->user();
        $data = $request->validated();
        $data['user_id'] = $user->id;
        $data['country'] = $data['country'] ?? 'Türkiye';

        $isFirstAddress = $user->addresses()->count() === 0;

        // First address automatically becomes the default for both roles
        if ($isFirstAddress) {
            $data['is_default_shipping'] = true;
            $data['is_default_billing']  = true;
        }

        $address = Address::create($data);

        // If the user explicitly requested defaults, enforce atomically
        if (! $isFirstAddress) {
            if ($request->boolean('is_default_shipping')) {
                $address->makeDefaultShipping();
            }
            if ($request->boolean('is_default_billing')) {
                $address->makeDefaultBilling();
            }
        }

        return redirect()->route('account.addresses')
            ->with('success', 'Adres başarıyla eklendi.');
    }

    public function edit(Address $address): View
    {
        abort_unless(auth()->id() === $address->user_id, 403);

        return view('account.address-form', compact('address'));
    }

    public function update(StoreAddressRequest $request, Address $address): RedirectResponse
    {
        abort_unless(auth()->id() === $address->user_id, 403);

        $data = $request->validated();
        unset($data['is_default_shipping'], $data['is_default_billing']);

        $address->update($data);

        if ($request->boolean('is_default_shipping')) {
            $address->makeDefaultShipping();
        }
        if ($request->boolean('is_default_billing')) {
            $address->makeDefaultBilling();
        }

        return redirect()->route('account.addresses')
            ->with('success', 'Adres güncellendi.');
    }

    public function destroy(Address $address): RedirectResponse
    {
        abort_unless(auth()->id() === $address->user_id, 403);

        if ($address->isDeletionBlocked()) {
            return redirect()->route('account.addresses')
                ->with('error', 'Bu adres varsayılan teslimat veya faturalama adresi olarak ayarlanmış. Silmeden önce başka bir adres varsayılan olarak atayın.');
        }

        $address->delete();

        return redirect()->route('account.addresses')
            ->with('success', 'Adres silindi.');
    }

    public function setDefaultShipping(Address $address): RedirectResponse
    {
        abort_unless(auth()->id() === $address->user_id, 403);
        $address->makeDefaultShipping();

        return redirect()->route('account.addresses')
            ->with('success', 'Varsayılan teslimat adresi güncellendi.');
    }

    public function setDefaultBilling(Address $address): RedirectResponse
    {
        abort_unless(auth()->id() === $address->user_id, 403);
        $address->makeDefaultBilling();

        return redirect()->route('account.addresses')
            ->with('success', 'Varsayılan faturalama adresi güncellendi.');
    }
}
