<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    //
    protected $fillable = [
        'name',
        'owner_id',
        'slug'
    ];

    protected static function boot()
    {
        parent::boot();
        static::created(function (Team $team){
            $team->members()->attach(auth()->id());
        });

        static::deleting(function (Team $team){
            $team->members()->sync([]);
        });

    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function members()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    public function designs()
    {
        return $this->hasMany(Design::class);
    }

    public function hasUser(User $user)
    {
        return (bool)$this->members()->where('user_id', $user->id)->count();
    }


}
