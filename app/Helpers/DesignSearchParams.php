<?php


namespace App\Helpers;


class DesignSearchParams
{
    public bool $has_team;
    public bool $has_comments;
    public string $q;
    public string $orderBy;

    public function __construct(bool $has_team, bool $has_comments, string $q, string $orderBy)
    {
        $this->has_team = $has_team;
        $this->has_comments = $has_comments;
        $this->q = $q;
        $this->orderBy = $orderBy;
    }
}
