<?php


namespace App\Services\Interfaces;


use App\Models\Chat;
use App\Models\Message;
use Illuminate\Database\Eloquent\Collection;

interface IChatService
{
    public function sendMessage(int $recipient, string $body): Message;
    public function getUserChats(): Collection;
    public function getChatMessages(int $id): Collection;
    public function markAsRead(int $id);
    public function deleteMessage(int $message_id): bool;
}
