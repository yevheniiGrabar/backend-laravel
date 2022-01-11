<?php

namespace App\Services;

use App\Models\Calendar;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CalendarService
{
    /**
     * @param User $user
     *
     * @return Collection
     */
    public function show(User $user): Collection
    {
        return Calendar::query()
            ->where(['owner_id' => $user->id, 'owner_type' => User::class])
            ->with(['events' => function (HasMany $e) {
                $e->selectRaw('id, summary, calendar_id, color, parent_id, description, dt_start, dt_end')
                    ->with(['subscribers' => function (HasMany $s) {
                        $s->selectRaw('id, summary, calendar_id, parent_id')
                            ->with(['calendar' => function (BelongsTo $c) {
                                $c->selectRaw('id, owner_id, owner_type, timezone')
                                    ->with(['owner']);
                            }]);
                    }]);
            }])
            ->get();
    }

    /**
     * @param array $payload
     * @return Calendar|\Illuminate\Database\Eloquent\Model
     */
    public function store(array $payload): Calendar
    {
        return Calendar::query()->create($payload);
    }
}
