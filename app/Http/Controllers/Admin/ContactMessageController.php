<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Services\ContactMessageService;

class ContactMessageController extends Controller
{
    protected ContactMessageService $contactService;

    public function __construct(ContactMessageService $contactService)
    {
        $this->contactService = $contactService;
    }

    public function index()
    {
        $messages = $this->contactService->getAdminMessages(15);
        return view('admin.messages.index', compact('messages'));
    }

    public function show(ContactMessage $message)
    {
        $this->contactService->markAsRead($message);
        return view('admin.messages.show', compact('message'));
    }

    public function destroy(ContactMessage $message)
    {
        $this->contactService->deleteMessage($message);
        return redirect()->route('admin.messages.index')
            ->with('success', 'Message deleted successfully.');
    }
}
