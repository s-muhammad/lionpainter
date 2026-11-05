<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\QuoteRequest;
use App\Services\DashboardMetrics;

class DashboardController extends Controller
{
    public function __invoke(DashboardMetrics $metrics)
    {
        return view('admin.dashboard', [
            'stats' => $metrics->summary(),
            'latestQuotes' => QuoteRequest::latest()->take(5)->get(),
            'latestMessages' => ContactMessage::latest()->take(4)->get(),
            'chart' => $metrics->weeklyQuotes(),
        ]);
    }
}
