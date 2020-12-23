<?php


namespace App\Repositories\Eloquent;


use App\Models\Message;
use App\Repositories\Interfaces\IMessageRepository;

class MessageRepository extends BaseRepository implements IMessageRepository
{

    public function model()
    {
        return Message::class;
    }
}
