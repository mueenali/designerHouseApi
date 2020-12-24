<?php


namespace App\Repositories\Eloquent\Criteria;


use App\Helpers\DesignSearchParams;
use App\Repositories\Criteria\ICriterion;

class SearchDesigns implements ICriterion
{

    private DesignSearchParams $params;

    public function __construct(DesignSearchParams $params)
    {
        $this->params = $params;
    }

    public function apply($model)
    {
        $model->where('is_live', true);

        if($this->params->has_team)
        {
            $model->has('comments');
        }

        if($this->params->has_team)
        {
            $model->has('team');
        }

        if($this->params->q)
        {
            $params = $this->params;
            $model->where(function ($q) use ($params) {
                $q->where('title', 'like', '%'.$params->q.'%')
                    ->orWhere('description', 'like', '%'.$params->q.'%');
            });
        }

        if($this->params->orderBy == 'likes')
        {
            $model->withCount('likes')->orderByDesc('likes_count');
        }else {
            $model->latest();
        }

        return $model;
    }
}
