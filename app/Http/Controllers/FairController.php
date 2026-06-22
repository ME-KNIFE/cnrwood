<?php

namespace App\Http\Controllers;

use App\Models\Fair;
use Illuminate\View\View;

class FairController extends Controller
{
    public function index(): View
    {
        $upcoming = Fair::upcoming()->get();
        $past     = Fair::past()->get();

        return view('public.fuarlar', compact('upcoming', 'past'));
    }
}
