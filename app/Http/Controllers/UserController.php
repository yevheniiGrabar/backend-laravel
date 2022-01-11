<?php

namespace App\Http\Controllers;

use App\Enums\Permissions\Access;
use App\Http\Requests\UserListRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\MeResource;
use App\Http\Resources\UserMutateResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class UserController extends Controller
{
    /** @var int */
    public const DEFAULT_PER_PAGE = 50;

    /**
     * @param UserListRequest $request
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return array
     */
    public function index(UserListRequest $request): array
    {
        $this->authorize('canViewAll', User::class);

        $query = User::viewable();

        $roles = $request->get('roles', []);

        if (!empty($roles)) {
            $query->whereHas('roles', function ($query) use ($roles) {
                $query->whereIn('name', $roles);
            });
        }

        return UserResource::collection($query
            ->paginate($request->get('perPage', self::DEFAULT_PER_PAGE)))
            ->response()
            ->getData(true);
    }

    /**
     * @param Request $request
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return JsonResponse
     */
    public function show(Request $request): JsonResponse
    {
        $this->authorize('canViewSelfConfig', User::class);

        /** @var User $user */
        $user = $request->user();

        return response_json($user->load(['roles' => fn($q) => $q->select(['id', 'name'])]));
    }

    /**
     * @param int $id
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return JsonResponse
     */
    public function edit(int $id): JsonResponse //TODO is it used? Page for edit in api?
    {
        $this->authorize('canUpdate', User::class);

        $user = User::query()->find($id);

        return response_json(new UserResource($user));
    }

    /**
     * @param UserUpdateRequest $request
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return JsonResponse
     */
    public function update(UserUpdateRequest $request): JsonResponse
    {
        $this->authorize('canUpdateCurrentUser', User::class);

        $payload = $request->validated();
        $user = $request->user();

        return response_json($user->updateOrFail($payload));
    }

    /**
     * @param Request $request
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @return JsonResponse
     */
    public function uploadAvatar(Request $request): JsonResponse
    {
        $this->authorize('canUpdateCurrentUser', User::class);

        $request->validate(['avatar' => 'nullable|image']);
        $user = $request->user();
        $image = $request->file('avatar');
        //TODO need move to uploadService
        !$user->avatar ?: Storage::delete($user->avatar);

        $imageUploadPath = $image ? $image->store('users', 'public') : null;

        return response_json($user->updateOrFail(['avatar' => $imageUploadPath]));
    }

    /**
     * @param User $user
     * @return JsonResponse
     * @throws Throwable
     */
    public function destroy(User $user): JsonResponse
    {
        $this->authorize('canDelete', User::class);

        $user->deleteOrFail();

        return response_json([], Response::HTTP_NO_CONTENT);
    }
}
