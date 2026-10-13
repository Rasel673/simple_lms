<?php

namespace App\Services;

use App\Models\ContactMessage;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ContactMessageService
{
    public function storeMessage(array $data): ContactMessage
    {
        return ContactMessage::create([
            'name' => strip_tags(trim($data['name'])),
            'email' => filter_var(trim($data['email']), FILTER_SANITIZE_EMAIL),
            'subject' => strip_tags(trim($data['subject'])),
            'message' => strip_tags(trim($data['message'])),
            'is_read' => false,
        ]);
    }

    public function getAdminMessages(int $perPage = 15): LengthAwarePaginator
    {
        return ContactMessage::latest()->paginate($perPage);
    }

    public function markAsRead(ContactMessage $message): ContactMessage
    {
        $message->update(['is_read' => true]);
        return $message;
    }

    public function deleteMessage(ContactMessage $message): bool
    {
        return $message->delete();
    }
}
