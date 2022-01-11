<?php

namespace App\Http\Controllers;

use App\Http\Requests\CalendarEventStoreRequest;
use App\Http\Requests\CalendarEventUpdateRequest;
use App\Models\CalendarEvent;
use App\Services\CalendarEventService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CalendarEventController extends Controller
{
    /**
     * CalendarEventController constructor.
     *
     * @param CalendarEventService $service
     */
    public function __construct(private CalendarEventService $service)
    {
    }

    /**
     * @param string $id
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        $this->authorize('canViewCurrent', CalendarEvent::class);

        return response_json($this->service->show($id));
    }

    /**
     * @param CalendarEventStoreRequest $request
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @return JsonResponse
     */
    public function store(CalendarEventStoreRequest $request): JsonResponse
    {
        $this->authorize('canCreate', CalendarEvent::class);

        return response_json($this->service->store($request->validated()), Response::HTTP_CREATED);
    }

    /**
     * @param CalendarEventUpdateRequest $request
     * @param string                     $id
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @return JsonResponse
     */
    public function update(CalendarEventUpdateRequest $request, string $id): JsonResponse
    {
        $this->authorize('canUpdate', CalendarEvent::class);

        return response_json($this->service->update($request->validated(), $id));
    }

    /**
     * @param string $id
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @return JsonResponse
     */
    public function unsubscribe(string $id): JsonResponse
    {
        $this->authorize('canUpdate', CalendarEvent::class);

        return response_json($this->service->unsubscribe($id));
    }

    /**
     * @param string $id
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @return JsonResponse
     */
    public function delete(string $id): JsonResponse
    {
        $this->authorize('canDelete', CalendarEvent::class);

        return response_json($this->service->delete($id));
    }
}
