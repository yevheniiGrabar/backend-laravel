<?php

namespace App\Listeners;

use App\Events\UpdateCalendarEventSubs;
use App\Models\CalendarEvent;
use App\Services\CalendarEventService;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateDataSubs implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param  UpdateCalendarEventSubs $event
     * @return void
     */
    public function handle(UpdateCalendarEventSubs $event): void
    {
        $payload = $event->getPayload();
        $service = new CalendarEventService();

        if (array_key_exists('subscribers', $payload)) {
            $this->insertNew($event, $service, $payload);
            $this->removeUnnecessary($event, $service, $payload['subscribers']);
        }

        CalendarEvent::query()
            ->where('parent_id', $event->getId())
            ->get()
            ->each(fn(CalendarEvent $calEvent) => $calEvent->update($event->getData()));
    }

    /**
     * @param UpdateCalendarEventSubs $event
     * @param CalendarEventService $service
     * @param array $payload
     */
    private function insertNew(UpdateCalendarEventSubs $event, CalendarEventService $service, array $payload): void
    {
        $new = [];
        $copyData = $event->getData();
        $payload['id'] = $event->getId();

        $service->prepareEvent($copyData, $payload);
        $service->fillSubscribers(
            $service->sliceExistSubscribersForCreate($event->getSubs(), $payload['subscribers']),
            $copyData,
            $new
        );

        !count($new) ?: CalendarEvent::query()->insert($new);
    }

    /**
     * @param UpdateCalendarEventSubs $event
     * @param CalendarEventService $service
     * @param array $subs
     */
    private function removeUnnecessary(UpdateCalendarEventSubs $event, CalendarEventService $service, array $subs): void
    {
        $subsCollection = $event->getSubs();
        $result = $service->sliceExistSubscribersForDelete($subsCollection, $subs);

        $ids = [];
        foreach ($result as $key => $item) {
            $ids[] = $subsCollection->offsetGet($key)->id;
        }

        CalendarEvent::query()->whereIn('id', $ids)->delete();
    }
}
