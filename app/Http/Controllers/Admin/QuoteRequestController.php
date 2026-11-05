<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuoteRequest;
use Illuminate\Http\Request;

class QuoteRequestController extends Controller
{
    public function index(Request $request)
    {
        $quotes = QuoteRequest::query()
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->when($request->filled('q'), function ($query) use ($request) {
                $q = '%'.$request->q.'%';
                $query->where(fn ($query) => $query->where('name', 'like', $q)
                    ->orWhere('email', 'like', $q)
                    ->orWhere('phone', 'like', $q)
                    ->orWhere('suburb', 'like', $q));
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.quotes.index', [
            'quotes' => $quotes,
            'statuses' => QuoteRequest::STATUSES,
        ]);
    }

    public function show(QuoteRequest $quote)
    {
        return view('admin.quotes.show', [
            'quote' => $quote,
            'statuses' => QuoteRequest::STATUSES,
        ]);
    }

    public function update(Request $request, QuoteRequest $quote)
    {
        $data = $request->validate([
            'status' => ['required', 'in:'.implode(',', array_keys(QuoteRequest::STATUSES))],
            'admin_notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $quote->update($data);

        return back()->with('status', 'درخواست به‌روزرسانی شد.');
    }

    public function destroy(QuoteRequest $quote)
    {
        $quote->delete();

        return redirect()->route('admin.quotes.index')->with('status', 'درخواست حذف شد.');
    }
}
