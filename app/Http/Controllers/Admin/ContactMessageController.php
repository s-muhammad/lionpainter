<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{
    public function index(Request $request)
    {
        $messages = ContactMessage::query()
            ->when($request->filled('status'), fn ($query) => $query->where('is_read', $request->status === 'read'))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.messages.index', compact('messages'));
    }

    public function show(ContactMessage $message)
    {
        $message->update(['is_read' => true]);

        return view('admin.messages.show', compact('message'));
    }

    public function toggleRead(ContactMessage $message)
    {
        $message->update(['is_read' => ! $message->is_read]);

        return back()->with('status', $message->is_read ? 'پیام خوانده‌شده علامت زد.' : 'پیام به عنوان خوانده‌نشده علامت زد.');
    }

    public function destroy(ContactMessage $message)
    {
        $message->delete();

        return redirect()->route('admin.messages.index')->with('status', 'پیام حذف شد.');
    }
}
