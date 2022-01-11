<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ResetPasswordIndexRequest;
use App\Http\Resources\StandardResponseResource;
use App\Models\User;
use App\Notifications\ResetPasswordNotification;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class ResetPasswordController extends Controller
{
    public const RESET_EMAIL_SUCCESS_MESSAGE = 'Instructions have been sent to your email';

    public function index(ResetPasswordIndexRequest $request): bool|JsonResponse
    {
        try {
            /** @var User $user */
            $user = User::query()
                ->where('email', '=', $request->input('email'))
                ->firstOrFail();

            $user->resetPasswordToken()
                ->updateOrCreate(
                    ['user_id' => $user->id],
                    ['user_id', $user->id, 'change_password_token' => Str::random(8)]
                );
            $user->notify(new ResetPasswordNotification($user->resetPasswordToken->change_password_token));

            return new JsonResponse(
                new StandardResponseResource(
                    self::RESET_EMAIL_SUCCESS_MESSAGE,
                    $user->resetPasswordToken->change_password_token
                ),
                Response::HTTP_OK
            );
        } catch (Exception $e) {
            return response_json_error($e->getFile(), $e->getMessage(), $e->getMessage(), $e->getCode());
        }
    }
}
