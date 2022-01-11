<?php

namespace Tests\Feature\Api;

use App\Enums\Users\RolesEnum;
use App\Http\Resources\LessonResource;
use App\Models\Company;
use App\Models\Course;
use App\Models\Department;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class LessonsTest extends AbstractApiTest
{
    public function createModels(int $count)
    {
        $user = User::factory($count)->create()->first();
        $company = Company::factory($count)->create(['owner_id' => $user->id])->first();
        Department::factory()->create([
            'company_id' => $company->id
        ]);
        Course::factory($count)->create();
        Lesson::factory($count)
            ->state(function (array $attributes) use ($company) {
                return [
                    'company_id' => $company->id
                ];
            })
            ->create();
    }

    public function getCreateFields(Model $model): array
    {
        $fields = parent::getCreateFields($model);

        $this->createModels(1);
        $fields['name'] = $this->faker->company();
        $fields['content'] = $this->faker->colorName();
        $fields['status'] = Lesson::STATUS_IN_PROGRESS;
        $fields['order'] = $this->faker->randomDigitNotZero();
        $fields['course_id'] = Course::first()->id;

        return $fields;
    }

    public function getModel(): string
    {
        return Lesson::class;
    }

    public function getResource(): string
    {
        return LessonResource::class;
    }

    public function getResourcePath(): string
    {
        return 'api/lessons';
    }

    public function getUpdateData(): array
    {
        return [
            'name' => 'testName',
            'content' => 'testContent',
            'status' => Lesson::STATUS_DISABLED,
            'order' => 1,
            'help' => 'testHelp'
        ];
    }

    public function testCloneMethod()
    {
        $modelClass = $this->getModel();
        $this->createModels(1);

        $model = $modelClass::first();

        $url = $this->getResourcePath() . '/clone';

        $cloneData = [
            'lesson_id' => $model->id
        ];

        $this->json('post', $url, $cloneData)
            ->assertUnauthorized();

        $this->authAsUser([$url], [RolesEnum::STUDENT]);

        $this->postJson($url, $cloneData)
            ->assertForbidden();

        $count = $modelClass::count();

        $this->authAsUser([$url], [RolesEnum::MODERATOR]);
        $this->postJson($url, $cloneData);

        $this->assertTrue($modelClass::count() > $count);
    }
}
