<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class DeleteImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private string $image;
    private string $disk;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $image, string $disk)
    {
        $this->image = $image;
        $this->disk = $disk;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach(['large', 'original', 'thumbnail'] as $size)
        {
            if(Storage::disk($this->disk)->exists("uploads/designs/{$size}/".$this->image))
            {
                Storage::disk($this->disk)->delete("uploads/designs/{$size}/".$this->image);
            }
        }
    }
}
