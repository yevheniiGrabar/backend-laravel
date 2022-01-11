<?php

namespace App\Providers;

use App\Enums\Users\RolesEnum;
use App\Models\Affiliate;
use App\Models\Calendar;
use App\Models\CalendarEvent;
use App\Models\Company;
use App\Models\CompanyPage;
use App\Models\Course;
use App\Models\Department;
use App\Models\File;
use App\Models\Lesson;
use App\Models\LessonConfig;
use App\Models\Permission;
use App\Models\Quiz;
use App\Models\QuizConfig;
use App\Models\QuizOption;
use App\Models\User;
use App\Policies\AffiliatePolicy;
use App\Policies\CalendarEventPolicy;
use App\Policies\CalendarPolicy;
use App\Policies\CompanyPagePolicy;
use App\Policies\CompanyPolicy;
use App\Policies\CoursePolicy;
use App\Policies\DepartmentPolicy;
use App\Policies\FilePolicy;
use App\Policies\LessonConfigPolicy;
use App\Policies\LessonPolicy;
use App\Policies\PermissionPolicy;
use App\Policies\QuizConfigPolicy;
use App\Policies\QuizOptionPolicy;
use App\Policies\QuizPolicy;
use App\Policies\UserPolicy;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        User::class => UserPolicy::class, // +
        Company::class => CompanyPolicy::class, // +
        CompanyPage::class => CompanyPagePolicy::class, // +
        Course::class => CoursePolicy::class, // +
        File::class => FilePolicy::class, // +
        Affiliate::class => AffiliatePolicy::class, // +
        Department::class => DepartmentPolicy::class, // +
        Calendar::class => CalendarPolicy::class, // +
        CalendarEvent::class => CalendarEventPolicy::class, // +
        Permission::class => PermissionPolicy::class, // +
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot(Gate $gate)
    {
        $this->registerPolicies();

        if (!$this->app->routesAreCached()) {
            Passport::routes();
            Passport::tokensExpireIn(now()->addMinutes(env("TOKEN_EXPIRE_TIME", 30)));
            Passport::refreshTokensExpireIn(now()->addMinutes(env("REFRESH_TOKEN_EXPIRE_TIME", 90)));
        }

        // Check admin role before checking permissions
        $gate->before(function (User $user) {
            if ($user->hasRole(RolesEnum::SUPER_ADMIN)) {
                return true;
            }
        });
    }
}
