<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Course;
use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Collection;

class CourseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Course::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        /** @var Department $department */
        $department = Department::query()->get()->random();

        return [
            'company_id' => $department->company->id,
            'department_id' => $department->id,
            'title' => $this->faker->jobTitle() . '-' . $department->company->id,
            'logo' => (bool) rand(0, 1) ? storage_path("courses/testCourseImage.jpeg", '') : null,
            'description' => $this->faker->text(40),
            'status' => Course::STATUS_ENABLED
        ];
    }
}
