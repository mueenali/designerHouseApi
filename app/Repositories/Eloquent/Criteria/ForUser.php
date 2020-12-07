<?php


namespace App\Repositories\Eloquent\Criteria;


use App\Repositories\Criteria\ICriterion;

class ForUser implements ICriterion
{
    private int $userId;
    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }

    public function apply($model)
    {
        return $model->where('user_id', $this->userId);
    }
}
