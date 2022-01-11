<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Resources\MeResource;
use App\Models\PasswordResetToken;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ChangePassword extends Controller
{
    /** @var string */
    public const DEFAULT_FAILED_MESSAGE = 'Failed to update password';
    /** @var string */
    public const DEFAULT_TOKEN_EXPIRED_MESSAGE = 'The token was expired';


    /**
     * @param ChangePasswordRequest $request
     * @return JsonResponse
     */
    public function index(ChangePasswordRequest $request): JsonResponse
    {
        try {
            /** @var User $user */

            if (Auth::user()) {
                $user = Auth::user();
                $user->updateOrFail(['password' => $request->new_password]);
                return new JsonResponse(['message' => 'Successfully']);
            }
            if (Auth::guest()) {
                $token = PasswordResetToken::query()
                    ->where('change_password_token', '=', $request->input('token'))
                    ->first();
                if (isset($token) && $token->active) {
                    $token->user()
                        ->updateOrFail(['password' => $request->input('password')]);
                    return new JsonResponse(new MeResource(Auth::loginUsingId($token->user->id)), Response::HTTP_OK);
                } else {
                    throw new Exception(self::DEFAULT_TOKEN_EXPIRED_MESSAGE, Response::HTTP_UNPROCESSABLE_ENTITY);
                }
            }
        } catch (Throwable) {
            return new JsonResponse(['message' => static::DEFAULT_FAILED_MESSAGE], Response::HTTP_BAD_REQUEST);
        }
    }
}
