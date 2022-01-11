<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\CompanyResource;
use App\Http\Resources\MeResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends Controller
{
    /** @var string */
    public const DEFAULT_ERROR_MESSAGE = "Failed to authorize.";

    /**
     * @param LoginRequest $request
     *
     * @return JsonResponse
     */
    public function index(LoginRequest $request): JsonResponse
    {
        if (Auth::attempt($request->getCredentials())) {
            $response = Http::asJson()->post("http://nginx/oauth/token", $request->toArray());
            if ($response->status() === Response::HTTP_OK) {
                /** @var User $user */
                $user = Auth::user();

                return new JsonResponse(MeResource::make($user)
                    ->additional(
                        array_merge(
                            $response->json(),
                            [
                                'roles' => $user->roles()->select(['id', 'name', 'guard_name'])->get(),
                                'companies' => CompanyResource::collection(
                                    Auth::user()->companies()->with(['departments', 'affiliates'])->get(),
                                ),
                                'permissions' => $user->permissions()->select(['name'])
                                    ->get()
                                    ->makeHidden(['pivot']),
                            ]
                        )
                    ));
            }
        }

        return new JsonResponse(['message' => static::DEFAULT_ERROR_MESSAGE], Response::HTTP_UNAUTHORIZED);
    }
}
