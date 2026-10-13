<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactMessageRequest;
use App\Services\ContactMessageService;

class ContactController extends Controller
{
    protected ContactMessageService $contactService;

    public function __construct(ContactMessageService $contactService)
    {
        $this->contactService = $contactService;
    }

    public function submit(ContactMessageRequest $request)
    {
        $this->contactService->storeMessage($request->validated());

        return redirect()->back()->with('contact_success', 'Thank you! Your message has been received. Our team will get back to you soon.');
    }
}
