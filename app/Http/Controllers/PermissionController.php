<?php

namespace App\Http\Controllers;

use App\Http\Requests\PermissionsRequest;
use App\Models\Permission;
use App\Models\User;
use App\Services\PermissionService;
use Illuminate\Http\JsonResponse;

class PermissionController extends Controller
{
    /**
     * CalendarController constructor.
     *
     * @param PermissionService $service
     */
    public function __construct(private PermissionService $service)
    {
    }

    /**
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $this->authorize('canViewAll', Permission::class);

        return response_json($this->service->index());
    }

    /**
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @return JsonResponse
     */
    public function selfPermissions(): JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();

        return response_json($this->service->authPermissions($user));
    }

    /**
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @return JsonResponse
     */
    public function syncRolePermission(PermissionsRequest $request): JsonResponse
    {
        $this->authorize('canUpdate', Permission::class);

        $validated = $request->validated();

        return response_json($this->service->syncRolePermission($validated['role_id'], $validated['permissions']));
    }
}
