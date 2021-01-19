<?php


namespace App\Services;


use App\Helpers\DesignSearchParams;
use App\Jobs\DeleteImage;
use App\Jobs\UploadImage;
use App\Models\Design;
use App\Repositories\Eloquent\Criteria\EagerLoad;
use App\Repositories\Eloquent\Criteria\ForUser;
use App\Repositories\Eloquent\Criteria\IsLive;
use App\Repositories\Eloquent\Criteria\LatestFirst;
use App\Repositories\Eloquent\Criteria\SearchDesigns;
use App\Repositories\Interfaces\IDesignRepository;
use App\Services\Interfaces\IDesignService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DesignService implements IDesignService
{
    use AuthorizesRequests, DispatchesJobs;

    private IDesignRepository $designRepository;

    public function __construct(IDesignRepository $designRepository)
    {
        $this->designRepository = $designRepository;
    }

    public function upload($image): Design
    {
        $file_name = time()."_".preg_replace('/\s+/', '_',
                strtolower($image->getClientOriginalName()));

        $image->storeAs('uploads/original', $file_name, 'temp');

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

       $design = $this->designRepository->update($id,[
            'title' => $request->title,
            'description' => $request->description,
            'slug' => Str::slug($request->title),
            'is_live' => !$design->upload_successful? false : $request->is_live,
            'team_id' => $request->team
        ]);

        $this->designRepository->applyTags($id,$request->tags);

        return $design;
    }

    public function delete(int $id): bool
    {
        $design = $this->designRepository->find($id);
        $this->authorize('delete',$design);

        $this->dispatch(new DeleteImage($design->image, $design->disk));

        return $design->delete();
    }

    public function getAllDesigns(): Collection
    {
        return $this->designRepository->withCriteria(
            [
                new LatestFirst(),
                new IsLive(),
                new EagerLoad(['user', 'comments'])
            ])->all();
    }

    public function findDesign(int $id): Design
    {
       return $this->designRepository->withCriteria([new EagerLoad(['comments'])])->find($id);
    }

    public function likeDesign(int $id): bool
    {
        $design = $this->designRepository->find($id);

        if($design->isLikedByUser(auth()->id()))
        {
            $design->unLike();
            return false;
        }

        $design->like();
        return true;
    }

    public function isLikedByUser(int $id): bool
    {
        $design = $this->designRepository->find($id);
        return $design->isLikedByUser(auth()->id());
    }

    public function search(DesignSearchParams $params): Collection
    {
        return $this->designRepository
            ->withCriteria([new SearchDesigns($params)])
            ->all();
    }

    public function findBySlug(string $slug): Design
    {
        return $this->designRepository
            ->withCriteria([new IsLive()])
            ->findWhereFirst('slug', $slug);
    }

    public function getTeamDesigns(int $team_id): Collection
    {
        return $this->designRepository
            ->withCriteria([new IsLive()])
            ->findWhere('team_id', $team_id);
    }

    public function getUserDesigns(int $user_id): Collection
    {
       return $this->designRepository
           ->withCriteria([new IsLive(), new ForUser($user_id)])
           ->all();
    }
}
