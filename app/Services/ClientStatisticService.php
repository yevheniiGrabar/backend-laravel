<?php

namespace App\Services;

use App\Models\Lesson;
use App\Models\User;

class ClientStatisticService
{
    /**
     * @return int|mixed
     */
    public function getAmountLoggedUsers(): int
    {
        return User::query()
            ->where('status', '=', 'ACTIVE')
            ->count('id');
    }

    public function getAmountInactiveUsers()
    {
        return User::query()
            ->where('status', '=', 'INACTIVE')
            ->count('status');
    }

    public function getAmountUsersWhoFinishedCourses()
    {
        $user = User::query()->with('affiliate')->whereNotNull('affiliate_id')->get();
        return $user;
    }

    public function getAmountUsersWhoInprogressCourses()
    {
        return Lesson::query()->where('status', '=', 'IN_PROGRESS')
            ->count('id');
    }

    public function getAmountLessonsCompleted()
    {
        $lessons = Lesson::all();
        return $lessons;
    }

    public function getAmountOfVerifiedTasks()
    {
        //
    }
    public function getAmountOfUnverifiedTasks()
    {
        //
    }

    public function getAverageTimeToCheckTask()
    {
        //
    }
}
