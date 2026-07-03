<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Focus;
use Illuminate\View\View;

class FocusController extends Controller
{
    public function index(): View
    {
        $focuses = Focus::active()->latest()->paginate(12);

        return view('front.focuses.index', compact('focuses'));
    }

    public function show(Focus $focus): View
    {
        abort_unless($focus->is_active, 404);

        $focus->load(['news.category', 'news.user']);

        return view('front.focuses.show', compact('focus'));
    }
}