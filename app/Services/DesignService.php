<?php


namespace App\Services;


use App\Jobs\UploadImage;
use App\Models\Design;
use App\Repositories\Interfaces\IDesignRepository;
use App\Services\Interfaces\IDesignService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DesignService implements IDesignService
{
    use ValidatesRequests, AuthorizesRequests, DispatchesJobs;

    private IDesignRepository $designRepository;

    public function __construct(IDesignRepository $designRepository)
    {
        $this->designRepository = $designRepository;
    }

    public function upload($image): Design
    {
        $image_path = $image->getPathname();
        $file_name = time()."_".preg_replace('/\s+/', '_',
                strtolower($image->getClientOriginalName()));

        $temp = $image->storeAs('uploads/original', $file_name, 'temp');

        $design = auth()->user()->designs()->create([
            'image' => $file_name,
            'disk' => config('site.upload_disk')
        ]);

        $this->dispatch(new UploadImage($design));

        return $design;
    }

    public function update(Request $request, int $id): Design
    {
        $design = $this->designRepository->find($id);
        $this->authorize('update', $design);

        $this->validate($request, [
            'title' => ['required', 'unique:designs,title,'.$id],
            'description' => ['required', 'string', 'min:20', 'max:140'],
            'tags' => ['required']
        ]);

       $design = $this->designRepository->update($id,[
            'title' => $request->title,
            'description' => $request->description,
            'slug' => Str::slug($request->title),
            'is_live' => !$design->upload_successful? false : $request->is_live
        ]);

        $this->designRepository->applyTags($id,$request->tags);

        return $design;
    }

    public function delete(int $id): bool
    {
        $design = $this->designRepository->find($id);
        $this->authorize('delete',$design);

        foreach(['large', 'original', 'thumbnail'] as $size)
        {
            if(Storage::disk($design->disk)->exists("uploads/designs/{$size}/".$design->image))
            {
                Storage::disk($design->disk)->delete("uploads/designs/{$size}/".$design->image);
            }
        }

         return $this->designRepository->delete($id);
    }

    public function getAllDesigns(): array
    {
        return $this->designRepository->all();
    }
}
