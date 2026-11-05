<?php

namespace App\Http\Controllers;

use App\Models\QuoteRequest;
use Illuminate\Http\Request;

class QuoteController extends Controller
{
    public function store(Request $request)
    {
        abort_if(filled($request->input('website')), 422, 'Spam detected.');

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:190'],
            'phone' => ['required', 'string', 'max:40'],
            'suburb' => ['required', 'string', 'max:120'],
            'service' => ['required', 'string', 'max:120'],
            'property_type' => ['nullable', 'string', 'max:60'],
            'bedrooms' => ['nullable', 'string', 'max:40'],
            'timeline' => ['nullable', 'string', 'max:60'],
            'details' => ['nullable', 'string', 'max:4000'],
        ]);

        QuoteRequest::create($data);

        return redirect()
            ->route('quote')
            ->with('quote_success', true)
            ->with('success', 'Your quote request is in. We’ll review the details and come back to you within one business day.');
    }
}
