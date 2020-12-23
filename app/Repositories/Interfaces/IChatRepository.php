<?php


namespace App\Repositories\Interfaces;


use Illuminate\Database\Eloquent\Collection;

interface IChatRepository
{
    public function getAllUserChats(): Collection;
}
