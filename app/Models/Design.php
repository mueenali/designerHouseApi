<?php

namespace App\Models;
use Cviebrock\EloquentTaggable\Taggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Design extends Model
{
    //
    use Taggable;

    protected $fillable = [
        'user_id',
        'image',
        'title',
        'description',
        'slug',
        'close_to_comment',
        'is_live',
        'upload_successful',
        'disk'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getImagesAttribute(): array
    {
        return [
            'thumbnail' => $this->getImagePath('thumbnail'),
            'original' => $this->getImagePath('original'),
            'large' => $this->getImagePath('large')
        ];
    }

    private function getImagePath(string $size): string
    {
        return Storage::disk($this->disk)->url("uploads/designs/{$size}/".$this->image);
    }
}
