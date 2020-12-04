<?php


namespace App\Repositories\Eloquent;


use App\Models\Design;
use App\Repositories\Interfaces\IDesignRepository;

class DesignRepository extends BaseRepository implements IDesignRepository
{
    public function model(): string
    {
        return Design::class;
    }

    public function applyTags(int $id,array $data)
    {
        $design = $this->find($id);
        $design->retag($data);
    }
}
