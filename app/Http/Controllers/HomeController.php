<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Testimonial;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $featured = Product::query()
            ->active()
            ->featured()
            ->with(['category', 'variants'])
            ->latest()
            ->take(4)
            ->get();

        $newest = Product::query()
            ->active()
            ->with(['category', 'variants'])
            ->latest()
            ->take(6)
            ->get();

        $categories = Category::query()
            ->withCount(['products' => fn ($query) => $query->where('is_active', true)])
            ->orderBy('sort_order')
            ->get();

        $testimonials = Testimonial::query()
            ->active()
            ->latest()
            ->take(3)
            ->get();

        return view('home', [
            'featured' => $featured,
            'newest' => $newest,
            'categories' => $categories,
            'testimonials' => $testimonials,
        ]);
    }
}
