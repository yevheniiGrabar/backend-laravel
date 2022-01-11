<?php

namespace App\Http\Controllers;

use App\Models\Calendar;
use App\Models\User;
use App\Services\CalendarService;
use Illuminate\Http\JsonResponse;

class CalendarController extends Controller
{
    /** @var CalendarService $service */
    private $service;

    /**
     * CalendarController constructor.
     *
     * @param CalendarService $service
     */
    public function __construct(CalendarService $service)
    {
        $this->service = $service;
    }

    /**
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @return JsonResponse
     */
    public function show(): JsonResponse
    {
        $this->authorize('canViewCurrent', Calendar::class);

        /** @var User $user */
        $user = auth()->user();

        return response_json($this->service->show($user));
    }
}
