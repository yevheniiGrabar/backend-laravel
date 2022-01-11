<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\MeResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Response;

class RegisterController extends Controller
{
    /** @var string */
    protected const DEFAULT_ERROR_MESSAGE = 'Something went wrong. Unable to register user';

    /**
     * @param RegisterRequest $request
     *
     * @return JsonResponse
     */
    public function index(RegisterRequest $request): JsonResponse
    {
        try {
            $user = User::query()->create($request->toArray());

            $response = Http::asJson()->post('http://nginx/oauth/token', [
                'grant_type' => 'password',
                'client_id' => env('PASSPORT_PASSWORD_GRANT_CLIENT_ID'),
                'client_secret' => env('PASSPORT_PASSWORD_GRANT_CLIENT_SECRET'),
                'username' => $request->get('email'),
                'password' => $request->get('password'),
                'scope' => '*',
            ]);

            return new JsonResponse(MeResource::make($user)
                ->additional($response->json()));
        } catch (\Exception $e) {
            Log::error(sprintf(
                'Error while registering user %d at %s Message: %s',
                $e->getLine(),
                $e->getFile(),
                $e->getMessage()
            ));
            return new JsonResponse(['message' => self::DEFAULT_ERROR_MESSAGE], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
