<?php

namespace Tests\Feature\Api;

use App\Http\Resources\LessonConfigResource;
use App\Models\Company;
use App\Models\Lesson;
use App\Models\LessonConfig;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class LessonConfigTest extends AbstractApiTest
{
    public function createModels(int $count)
    {
        $user = User::factory($count)->create()->first();
        $company = Company::factory($count)->create(['owner_id' => $user->id])->first();
        Lesson::factory($count)
            ->state(function (array $attributes) use ($company) {
                return [
                    'company_id' => $company->id
                ];
            })
            ->has(LessonConfig::factory()->count(1), 'configs')
            ->create();
    }

    public function getModel(): string
    {
        return LessonConfig::class;
    }

    public function getResource(): string
    {
        return LessonConfigResource::class;
    }

    public function getResourcePath(): string
    {
        return 'api/lesson-configs';
    }

    public function getCreateFields(Model $model): array
    {
        $fields = parent::getCreateFields($model);

        $this->createModels(1);
        $fields['lesson_id'] = Lesson::first()->id;
        $fields['code'] = $this->faker->colorName();
        $fields['group'] = $this->faker->colorName();

        return $fields;
    }

    public function getUpdateData(): array
    {
        return [
            'code' => 'test_code',
            'group' => 'test_group',
            'enabled' => true
        ];
    }
}
