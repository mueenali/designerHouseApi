<?php


namespace App\Repositories\Eloquent;


use App\Models\Chat;
use App\Repositories\Interfaces\IChatRepository;
use Illuminate\Database\Eloquent\Collection;

class ChatRepository extends BaseRepository implements IChatRepository
{
    public function model()
    {
        return Chat::class;
    }

    public function getAllUserChats(): Collection
    {
       return auth()->user()->chats->with(['messages', 'participant'])->get();
    }

}
