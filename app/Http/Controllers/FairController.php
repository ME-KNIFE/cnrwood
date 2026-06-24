<?php

namespace App\Http\Controllers;

use App\Models\Fair;
use Illuminate\View\View;

class FairController extends Controller
{
    public function index(): View
    {
        $upcoming = Fair::published()->upcoming()->get();
        $past     = Fair::published()->past()->get();

        return view('public.fuarlar', compact('upcoming', 'past'));
    }

    public function show(string $slug): View
    {
        $fair = Fair::published()
            ->where('slug', $slug)
            ->firstOrFail();

        $others = Fair::published()
            ->where('id', '!=', $fair->id)
            ->orderByDesc('is_featured')
            ->orderByDesc('start_date')
            ->limit(4)
            ->get();

        return view('public.fuar-detay', compact('fair', 'others'));
    }
}
