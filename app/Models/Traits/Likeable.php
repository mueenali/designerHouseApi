<?php


namespace App\Models\Traits;


use App\Models\Like;

trait Likeable
{
    public function Likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function like()
    {
        if(!auth()->check()) return;

        if($this->isLikedByUser(auth()->id())) return;

        $this->likes()->create(['user_id' => auth()->id()]);
    }

    public function unLike()
    {
        if(!auth()->check()) return;

        if(!$this->isLikedByUser(auth()->id())) return;

        $this->likes()->where('user_id', auth()->id())->delete();
    }

    public function isLikedByUser(int $user_id): bool
    {
        return (bool)$this->likes()->where('user_id', $user_id)->count();
    }

    public static function bootLikeable()
    {
        static::deleting(function ($model) {
            $model->removeLikes();
        });
    }

    public function removeLikes()
    {
        if($this->likes()->count())
        {
            $this->likes()->delete();
        }
    }
}
