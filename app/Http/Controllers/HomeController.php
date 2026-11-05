<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\Post;
use App\Models\Project;
use App\Models\Service;
use App\Models\Testimonial;

class HomeController extends Controller
{
    public function __invoke()
    {
        return view('pages.home', [
            'services' => Service::active()->ordered()->get(),
            'projects' => Project::published()->ordered()->take(3)->get(),
            'testimonials' => Testimonial::published()->ordered()->get(),
            'posts' => Post::published()->latest('published_at')->take(3)->get(),
            'faqs' => Faq::published()->ordered()->take(3)->get(),
        ]);
    }
}
