<?php

namespace App\Http\Controllers\Chats;

use App\Http\Controllers\Controller;
use App\Http\Requests\SendMessageRequest;
use App\Http\Resources\ChatResource;
use App\Http\Resources\MessageResource;
use App\Services\Interfaces\IChatService;

class ChatController extends Controller
{
    private IChatService $chatService;

    public function __construct(IChatService $chatService)
    {
        $this->chatService = $chatService;
    }

    public function sendMessage(SendMessageRequest $request): MessageResource
    {
        $message = $this->chatService->sendMessage($request->recipient, $request->body);

        return new MessageResource($message);
    }

    public function getUserChats()
    {
        $chats = $this->chatService->getUserChats();
        return ChatResource::collection($chats);
    }

    public function getChatMessages(int $id)
    {
        $messages = $this->chatService->getChatMessages($id);
        return MessageResource::collection($messages);
    }

    public function markAsRead(int $id)
    {
        $this->chatService->markAsRead($id);
        return response()->json(['message' => 'Messages marked as read']);
    }

    public function deleteMessage(int $id)
    {
        $result = $this->chatService->deleteMessage($id);
        if(!$result)
            return response()->json(['Errors' => ['message' => 'Problem in deleting the message']], 400);

        return response()->json(['Message' => 'Message deleted successfully']);
    }
}
