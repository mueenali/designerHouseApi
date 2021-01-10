<?php

namespace App\Http\Controllers\Designs;

use App\Helpers\DesignSearchParams;
use App\Http\Controllers\Controller;
use App\Http\Requests\UploadRequest;
use App\Http\Resources\DesignResource;
use App\Services\Interfaces\IDesignService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;


class DesignController extends Controller
{
    //
    private IDesignService $designService;

    public function __construct(IDesignService $designService)
    {
        $this->designService = $designService;
    }

    public function upload(UploadRequest $request): JsonResponse
    {
        $image = $request->file('image');
        $design = $this->designService->upload($image);

        return response()->json($design);
    }

    public function update(Request $request, int $id): DesignResource
    {
        $this->validate($request, [
            'title' => ['required', 'unique:designs,title,'.$id],
            'description' => ['required', 'string', 'min:20', 'max:140'],
            'tags' => ['required'],
            'team' => ['required_if:assign_to_team,true']
        ]);

        $design = $this->designService->update($request, $id);

        return new DesignResource($design);
    }

    public function delete(int $id): JsonResponse
    {
        $result = $this->designService->delete($id);

        if (!$result) {
            return response()->json(['error' => ['design' => 'Problem in deleting the design']], 400);
        }

        return response()->json(['message' => 'Design deleted successfully']);
    }

    public function index(): AnonymousResourceCollection
    {
        $designs = $this->designService->getAllDesigns();
        return DesignResource::collection($designs);
    }

    public function findDesign(int $id): DesignResource
    {
        $design = $this->designService->findDesign($id);
        return new DesignResource($design);
    }

    public function like(int $id)
    {
        $result = $this->designService->likeDesign($id);

        if(!$result) return response()->json(['message' => 'successfully unliked the design']);

        return response()->json(['message' => 'successfully liked the design']);
    }

    public function userHasLiked(int $id)
    {
        $isLiked = $this->designService->isLikedByUser($id);
        return response()->json(['liked' => $isLiked]);
    }

    public function search(Request $request)
    {
        $params = new DesignSearchParams($request->has_team, $request->has_comments,
            $request->q, $request->orderBy);

        $designs = $this->designService->search($params);
        return DesignResource::collection($designs);
    }

    public function findBySlug(string $slug)
    {
        $design = $this->designService->findBySlug($slug);
        return new DesignResource($design);
    }

    public function getTeamDesigns(int $team_id)
    {
        $designs = $this->designService->getTeamDesigns($team_id);
        return DesignResource::collection($designs);
    }

    public function getUserDesigns(int $user_id)
    {
        $designs = $this->designService->getUserDesigns($user_id);
        return DesignResource::collection($designs);
    }
}
