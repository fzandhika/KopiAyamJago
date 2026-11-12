<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use Illuminate\Contracts\View\View;

class PageController extends Controller
{
    public function story(): View
    {
        return view('pages.story', [
            'testimonials' => Testimonial::query()->active()->take(4)->get(),
        ]);
    }

    public function contact(): View
    {
        return view('pages.contact');
    }
}
