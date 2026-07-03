<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Epaper;
use Illuminate\View\View;

class EpaperController extends Controller
{
    public function index(): View
    {
        $epapers = Epaper::published()->latest('edition_date')->paginate(12);

        $latest = $epapers->first();

        return view('front.epapers.index', compact('epapers', 'latest'));
    }
}