<?php

namespace App\Http\Controllers;

use App\Models\Service;

class ServiceController extends Controller
{
    public function index()
    {
        return view('pages.services.index', [
            'services' => Service::active()->ordered()->get(),
        ]);
    }

    public function show(Service $service)
    {
        abort_unless($service->is_active, 404);

        return view('pages.services.show', [
            'service' => $service,
            'related' => Service::active()->whereKeyNot($service->id)->ordered()->take(3)->get(),
        ]);
    }
}
