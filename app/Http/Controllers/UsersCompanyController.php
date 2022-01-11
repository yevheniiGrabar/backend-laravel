<?php

namespace App\Http\Controllers;

use App\Classes\Users\RolesFactory;
use App\Enums\Users\RolesEnum;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\User\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UsersCompanyController extends Controller
{
//    /**
//     * UsersCompanyController constructor.
//     *
//     * @param UserService $service
//     */
//    public function __construct(private UserService $service)
//    {
//    }

    /**
     * Display a listing of the resource.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return AnonymousResourceCollection
     */
    public function index(): JsonResponse
    {
        dd(Auth::user());
//        $this->authorize('canViewAll', User::class);

        $user = User::query()
            ->selectRaw('id, first_name, last_name, email, phone, status, country, city, affiliate_id')
            ->selectRaw('CONCAT(first_name, \' \', last_name) as title')
            ->paginate(self::DEFAULT_PER_PAGE);

        return response_json([
            'current_page' => $user->currentPage(),
            'last_page' => $user->lastPage(),
            'total' => $user->total(),
            'data' => $user->items()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UserStoreRequest $request
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return JsonResponse
     */
    public function store(UserStoreRequest $request): JsonResponse
    {
        $this->authorize('canCreate', User::class);

        $validated = $request->validated();
        $roles = $validated['roles'];

        $payload = in_array(RolesEnum::MANAGER, $roles, true)
            ? Arr::except($validated, ['roles', 'affiliate_id'])
            : Arr::except($validated, ['roles']);

        $user = $this->service->store($payload);

        $factory = RolesFactory::make($roles);
        $factory->setRolesAndPermissions($user);

        return response_json(new UserResource($user));
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @return JsonResponse
     */
    public function show(User $user): JsonResponse
    {
//        $this->authorize('canViewAll', User::class);

        return response_json(new UserResource($user));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UserUpdateRequest $request
     * @param User              $user
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @return JsonResponse
     */
    public function update(UserUpdateRequest $request, User $user): JsonResponse
    {
        $this->authorize('canUpdate', User::class);

        $user->update($request->validated());

        return $this->show($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     *
     * @throws \Throwable
     *
     * @return JsonResponse
     */
    public function destroy(User $user): JsonResponse
    {
        $this->authorize('canDelete', User::class);

        $user->deleteOrFail();

        return response_json([], Response::HTTP_NO_CONTENT);
    }
}
