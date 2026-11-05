<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\Service;

class PageController extends Controller
{
    public function about()
    {
        return view('pages.about');
    }

    public function serviceAreas()
    {
        return view('pages.service-areas');
    }

    public function contact()
    {
        return view('pages.contact', [
            'services' => Service::active()->ordered()->get(),
        ]);
    }

    public function quote()
    {
        return view('pages.quote', [
            'services' => Service::active()->ordered()->get(),
        ]);
    }

    public function faqs()
    {
        return view('pages.faqs', [
            'faqs' => Faq::published()->ordered()->get(),
        ]);
    }

    public function privacy()
    {
        return view('pages.legal', ['page' => 'privacy']);
    }

    public function terms()
    {
        return view('pages.legal', ['page' => 'terms']);
    }
}
