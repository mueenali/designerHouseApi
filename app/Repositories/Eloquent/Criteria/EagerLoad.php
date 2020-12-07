<?php


namespace App\Repositories\Eloquent\Criteria;


use App\Repositories\Criteria\ICriterion;

class EagerLoad implements ICriterion
{

    private array $relationships;
    public function __construct(array $relationships)
    {
        $this->relationships = $relationships;
    }

    public function apply($model)
    {
        return $model->with($this->relationships);
    }
}
