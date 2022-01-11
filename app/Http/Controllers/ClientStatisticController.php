<?php

namespace App\Http\Controllers;

use App\Services\ClientStatisticService;
use Illuminate\Http\Request;

class ClientStatisticController extends Controller
{

    /** @var ClientStatisticService */
    protected $clientStatisticService;

    public function __construct(ClientStatisticService $statisticService)
    {
        $this->clientStatisticService = $statisticService;
    }

    public function index()
    {
        $activeUsers = $this->clientStatisticService->getAmountLoggedUsers();
        $inActiveUsers = $this->clientStatisticService->getAmountInactiveUsers();
        $courses = $this->clientStatisticService->getAmountUsersWhoFinishedCourses();
        $lessons = $this->clientStatisticService->getAmountUsersWhoInprogressCourses();
        dd($lessons);
    }
}
