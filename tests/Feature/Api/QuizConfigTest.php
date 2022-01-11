<?php

namespace Tests\Feature\Api;

use App\Http\Resources\QuizConfigResource;
use App\Models\Company;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\QuizConfig;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class QuizConfigTest extends AbstractApiTest
{
    public function createModels(int $count)
    {
        $this->createQuiz($count);
        foreach (Quiz::all() as $quiz) {
            $this->getModel()::factory([
                'quiz_id' => $quiz->id
            ])->create();
        }
    }

    protected function createQuiz($count)
    {
        $user = User::factory($count)->create()->first();
        $company = Company::factory($count)->create(['owner_id' => $user->id])->first();
        Lesson::factory($count)
            ->state(function (array $attributes) use ($company) {
                return [
                    'company_id' => $company->id
                ];
            })
            ->create();
        Quiz::factory($count)
            ->create([
                'lesson_id' => Lesson::first()->id
            ]);
    }

    public function getModel(): string
    {
        return QuizConfig::class;
    }

    public function getResource(): string
    {
        return QuizConfigResource::class;
    }

    public function getResourcePath(): string
    {
        return 'api/quiz-configs';
    }

    public function getCreateFields(Model $model): array
    {
        $fields = parent::getCreateFields($model);

        $this->createQuiz(1);
        $fields['quiz_id'] = Quiz::first()->id;
        $fields['code'] = $this->faker->colorName();
        $fields['group'] = $this->faker->colorName();
        $fields['enabled'] = true;

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
