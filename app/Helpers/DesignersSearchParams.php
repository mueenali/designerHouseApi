<?php


namespace App\Helpers;


use Grimzy\LaravelMysqlSpatial\Types\Point;

class DesignersSearchParams
{
    public bool $has_designs;
    public bool $available_to_hire;
    public float $lng;
    public float $lat;
    public float $dist;
    public string $unit;
    public bool $orderBy_latest;

    public function __construct(bool $has_designs, bool $available_to_hire, float $lng,
                                float $lat, int $dist, string $unit, bool $orderBy_latest)
    {
        $this->has_designs = $has_designs;
        $this->available_to_hire = $available_to_hire;
        $this->lng = $lng;
        $this->lat = $lat;
        $this->dist = $dist;
        $this->unit = $unit;
        $this->orderBy_latest = $orderBy_latest;
    }
}
