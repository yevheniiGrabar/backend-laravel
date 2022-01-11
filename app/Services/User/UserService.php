<?php

namespace App\Services\User;

use App\Enums\Users\UserStatuses;
use App\Models\User;

class UserService
{
    /**
     * @param array $payload
     *
     * @return User|\Illuminate\Database\Eloquent\Model
     */
    public function store(array $payload): User
    {
        if (!array_key_exists('status', $payload)) {
            $payload['status'] = UserStatuses::STATUS_ACTIVE;
        }

        return User::query()->create($payload);
    }
}
