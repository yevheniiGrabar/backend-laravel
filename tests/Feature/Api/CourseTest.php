<?php

namespace Tests\Feature\Api;

use App\Http\Resources\CourseResource;
use App\Models\Company;
use App\Models\Course;
use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class CourseTest extends AbstractApiTest
{
    public function createModels(int $count)
    {
        $user = User::factory($count)->create()->first();
        $company = Company::factory($count)->create(['owner_id' => $user->id])->first();
        Department::factory()->create([
            'company_id' => $company->id
        ]);
        Course::factory($count)->create();
    }

    protected function getCreateFields(Model $model): array
    {
        $fields = parent::getCreateFields($model);

        $fields['title'] = $this->faker->company();

        return $fields;
    }

    public function getModel(): string
    {
        return Course::class;
    }

    public function getResource(): string
    {
        return CourseResource::class;
    }

    public function getResourcePath(): string
    {
        return 'api/courses';
    }

    public function getUpdateData(): array
    {
        return [
            'title' => 'Some test title',
            'description' => 'test',
            'status' => Course::STATUS_DISABLED
        ];
    }
}
