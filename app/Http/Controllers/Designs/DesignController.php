<?php

namespace App\Http\Controllers\Designs;

use App\Http\Controllers\Controller;
use App\Http\Requests\UploadRequest;
use App\Http\Resources\DesignResource;
use App\Services\Interfaces\IDesignService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


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
        $design = $this->designService->update($request, $id);

        return new DesignResource($design);
    }

    public function delete(int $id)
    {
        $result = $this->designService->delete($id);

        if(!$result)
        {
            return response()->json(['error' => ['design' => 'Problem in deleting the design']], 400);
        }

        return response()->json(['message' => 'Design deleted successfully']);
    }
}
