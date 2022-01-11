<?php

namespace Database\Seeders;

use App\Classes\Permissions\Accessor;
use App\Enums\Users\RolesEnum;
use App\Models\Affiliate;
use App\Models\Company;
use App\Models\Course;
use App\Models\Department;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\PermissionRegistrar;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        app()->make(PermissionRegistrar::class)->forgetCachedPermissions();

        $this->call([
            LanguageLineSeeder::class,
            RolesAndPermissionsSeeder::class,
            DefaultOauthSeeder::class,
        ]);

        $accessor = new Accessor();
        foreach (User::factory(2)->create() as $key => $user) {
            $user->companies()->create(['owner_id' => $user->id, 'title' => "Client company #$key"]);
            $user->assignRole(RolesEnum::CLIENT);
            $user->givePermissionTo($accessor->getModeratorAccess());
        }

        /**
         * @var $company Company
         */
        foreach (Company::all() as $company) {
            Affiliate::factory(2)->create();

            /** @var User $user */
            foreach (User::factory(3)->create() as $user) {
                $user->companies()->save($company);
                $user->assignRole(RolesEnum::MODERATOR);
                $user->givePermissionTo($accessor->getModeratorAccess());
            }

            foreach (User::factory(5)->create() as $user) {
                $user->companies()->save($company);
                $user->assignRole(RolesEnum::STUDENT);
                $user->givePermissionTo($accessor->getStudentAccess());
            }

            $this->callWith(AffiliateDepartmentsSeeder::class, [
                'company' => $company,
            ]);


//            /** @var Department $department */
//            foreach ($company->departments()->get() as $department) {
//                Course::factory(2)
//                    ->for($company)
//                    ->for($department)
//                    ->create();
//
//                /**
//                 * @var $course Course
//                 */
//                foreach ($department->courses()->get() as $course) {
//                    for ($i = 1; $i <= 2; $i++) {
//                        $this->callWith(LessonSeeder::class, [
//                            'department' => $department,
//                            'course' => $course
//                        ]);
//                    }
//
//                    foreach ($course->lessons()->get() as $lesson) {
//                        for ($i = 1; $i <= rand(2, 6); $i++) {
//                            $this->callWith(LessonSeeder::class, [
//                                'department' => $department,
//                                'course' => $course
//                            ]);
//
//                            $lesson = Lesson::where('course_id', $course->id)->orderBy('id', 'desc')->first();
//
//                            for ($i = 1; $i <= rand(2, 6); $i++) {
//                                $this->callWith(LessonSeeder::class, [
//                                    'department' => $department,
//                                    'course' => $course,
//                                    'parentLesson' => $lesson
//                                ]);
//
//                                $lesson = Lesson::where('course_id', $course->id)->orderBy('id', 'desc')->first();
//
//                                if (rand(0, 1) > 0) {
//                                    for ($i = 1; $i <= rand(2, 6); $i++) {
//                                        $this->callWith(LessonSeeder::class, [
//                                            'department' => $department,
//                                            'course' => $course,
//                                            'parentLesson' => $lesson
//                                        ]);
//                                    }
//                                }
//                            }
//                        }
//                    }
//                }
//            }
//        }

            $this->call([
                CreateCalendars::class,
                //TODO remove when not needed
                SyncRolesAndPermissionsOnUsers::class,
            ]);
        }
    }
}
