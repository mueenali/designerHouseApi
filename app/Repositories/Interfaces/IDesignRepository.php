<?php


namespace App\Repositories\Interfaces;



interface IDesignRepository
{
    public function applyTags(int $id, array $data);
}
