<?php


namespace App\Repositories\Eloquent\Criteria;


use App\Helpers\DesignersSearchParams;
use App\Repositories\Criteria\ICriterion;
use Grimzy\LaravelMysqlSpatial\Types\Point;

class SearchDesigners implements ICriterion
{

    private DesignersSearchParams $params;

    public function __construct(DesignersSearchParams $params)
    {
        $this->params = $params;
    }

    public function apply($model)
    {
        if($this->params->has_designs)
        {
            $model->has('designs');
        }

        if($this->params->available_to_hire)
        {
            $model->where('available_to_hire', true);
        }

        if($this->params->lat && $this->params->lng)
        {
            $point = new Point($this->params->lat, $this->params->lng);
            $this->params->unit == 'km' ? $this->params->dist *=1000:
                $this->params->dist *=1609.34;

            $model->distanceSphereExcludingSelf('location', $point, $this->params->dist);
        }

        if($this->params->orderBy_latest)
        {
            $model->latest();
        }else {
            $model->oldest();
        }

        return $model;
    }
}
