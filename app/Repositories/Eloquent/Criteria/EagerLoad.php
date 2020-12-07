<?php


namespace App\Repositories\Eloquent\Criteria;


use App\Repositories\Criteria\ICriterion;

class EagerLoad implements ICriterion
{

    private string $relationships;
    public function __construct(string $relationships)
    {
        $this->relationships = $relationships;
    }

    public function apply($model)
    {
        return $model->with($this->relationships);
    }
}
