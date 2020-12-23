<?php


namespace App\Services;


use App\Models\Chat;
use App\Models\Message;
use App\Repositories\Eloquent\Criteria\WithTrashed;
use App\Repositories\Interfaces\IChatRepository;
use App\Repositories\Interfaces\IMessageRepository;
use App\Services\Interfaces\IChatService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ChatService implements IChatService
{
    use AuthorizesRequests;

    private IChatRepository $chatRepository;
    private IMessageRepository $messageRepository;

    public function __construct(IChatRepository $chatRepository, IMessageRepository $messageRepository)
    {
        $this->chatRepository = $chatRepository;
        $this->messageRepository = $messageRepository;
    }

    public function sendMessage(int $recipient, string $body): Message
    {
       $user = auth()->user();

       $chat = $user->getChatWithUser($recipient);

       if(!$chat)
       {
           $chat = $this->chatRepository->create([]);
           $chat->createParticipants([$user->id, $recipient]);
       }

        return $this->messageRepository->create([
            'user_id' => $user->id,
            'chat_id' => $chat->id,
            'body' => $body
        ]);
    }

    public function getUserChats(): Collection
    {
        return $this->chatRepository->getAllUserChats();
    }

    public function getChatMessages(int $id): Collection
    {
        return $this->messageRepository
            ->withCriteria([new WithTrashed()])
            ->findWhere('chat_id', $id);
    }

    public function markAsRead(int $id)
    {
        $chat = $this->chatRepository->find($id);
        $chat->markAsReadForUser(auth()->id());
    }

    public function deleteMessage(int $message_id): bool
    {
        $message = $this->messageRepository->find($message_id);
        $this->authorize('delete', $message);
        return $message->delete();
    }
}
