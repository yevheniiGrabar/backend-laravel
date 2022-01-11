<?php

namespace App\Http\Controllers;

use App\Enums\Files\FilesEnum;
use App\Http\Requests\FileStoreRequest;
use App\Http\Resources\FileResource;
use App\Http\Resources\FloarlaFileResource;
use App\Models\File;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class FileController extends Controller
{
    /**
     * @param Request $request
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return JsonResponse|array
     */
    public function index(Request $request): JsonResponse|array
    {
        $this->authorize('canViewAll', File::class);
        $query = File::viewable();

        if ($request->get('format', '') === 'floarla') {
            $data = FloarlaFileResource::collection($query->get())
                ->response()
                ->getData(true);

            return new JsonResponse($data['data'], Response::HTTP_OK);
        }

        return FileResource::collection($query
            ->paginate($request->perPage ?? self::DEFAULT_PER_PAGE))
            ->response()
            ->getData(true);
    }

    /**
     * @param FileStoreRequest $request
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @return JsonResponse
     */
    public function store(FileStoreRequest $request): JsonResponse
    {
        $this->authorize('canCreate', File::class);

        $payload = $request->validated();
        $payload['format'] = $request->get('format', FilesEnum::SELF);
        $fileName = sprintf('%s_%s', md5(time()), $payload['file']->getClientOriginalName());
        $filePath = sprintf(
            'storage/%s',
            Storage::disk('public')->putFileAs('uploads', $payload['file'], $fileName)
        );

        /** @var User $user */
        $user = Auth::user();

        /** @var File $fileModel */
        $fileModel = File::query()
            ->create([
                'owner_id' => $user->id,
                'company_id' => $user->companies()->first()?->id,
                'name' => $filePath,
                'original' => $payload['file']->getClientOriginalName()
            ]);

        if ($payload['format'] === FilesEnum::FLOARLA) {
            $resource = FloarlaFileResource::make($fileModel);

            return new JsonResponse($resource->toArray($request), Response::HTTP_OK);
        }

        return $this->show($fileModel);
    }

    /**
     * @param File $file
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @return JsonResponse
     */
    public function show(File $file): JsonResponse
    {
        $this->authorize('canViewAll', File::class);

        return response_json(new FileResource($file), Response::HTTP_OK);
    }

    /**
     * @param File $file
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @return JsonResponse
     */
    public function destroy(File $file): JsonResponse
    {
        $this->authorize('canDelete', File::class);

        @unlink(Storage::path('public/' . str_replace('/storage', '', $file->name)));

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }

    /**
     * @param Request $request
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @return JsonResponse|AccessDeniedHttpException
     */
    public function floarlaDelete(Request $request): JsonResponse|AccessDeniedHttpException
    {
        if ($request->has('data-id')) {
            return $this->destroy(File::findOrFail($request->get('data-id')));
        }

        return new AccessDeniedHttpException();
    }
}
