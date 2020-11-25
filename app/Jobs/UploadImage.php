<?php

namespace App\Jobs;

use App\Models\Design;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class UploadImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Design $design;

    /**
     * Create a new job instance.
     *
     * @param Design $design
     */
    public function __construct(Design $design)
    {
        $this->design = $design;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $disk = $this->design->disk;
        $file_name = $this->design->image;
        $original_file = storage_path().'/uploads/original/'.$file_name;

        try
        {
            $large_file = $this->imageResizing($original_file, 'uploads/large', 800, 600);
            $thumbnail_file = $this->imageResizing($original_file, 'uploads/thumbnail', 250, 200);

            $this->uploadToDisk('uploads/designs/original/' .$file_name, $original_file, $disk);
            $this->uploadToDisk('uploads/designs/large/' .$file_name, $large_file, $disk);
            $this->uploadToDisk('uploads/designs/thumbnail/' .$file_name, $thumbnail_file, $disk);

            $this->design->update(['upload_successful' => true]);

        }catch (Exception $exception)
        {
          Log::error($exception->getMessage());
        }
    }

    private function imageResizing(string $original_file, string $uploadPath, int $width, int $height): string
    {
          Image::make($original_file)
            ->fit($width, $height, fn($constraint) => $constraint->aspectRatio())
            ->save($file = storage_path($uploadPath.$this->design->image));

        return $file;
    }

    private function uploadToDisk(string $path, string $file, string $disk): void
    {
        if(Storage::disk($disk)->put($path, fopen($file, 'r+')))
        {
            File::delete($file);
        }
    }
}
