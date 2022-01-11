<?php

namespace App\Http\Resources\Traits;

use Illuminate\Support\Facades\Auth;

trait ModeratorHelpFields
{
    protected function addModeratorFields(array $data)
    {
        $user = Auth::user();

        if ($user && $user->isModerator()) {
            $data['help'] = $this->help;
        }

        return $data;
    }
}
