<?php

namespace Tests\Feature\Api;

use App\Enums\Users\RolesEnum;
use App\Http\Resources\QuizOptionResource;
use App\Models\Company;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\QuizOption;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class QuizOptionTest extends AbstractApiTest
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
        return QuizOption::class;
    }

    public function getResource(): string
    {
        return QuizOptionResource::class;
    }

    public function getResourcePath(): string
    {
        return 'api/quiz-options';
    }

    public function getCreateFields(Model $model): array
    {
        $fields = parent::getCreateFields($model);

        $this->createQuiz(1);
        $fields['quiz_id'] = Quiz::first()->id;

        return $fields;
    }

    public function getUpdateData(): array
    {
        return [
            'correct' => true
        ];
    }

    public function testImportOptionsFromAnotherQuiz()
    {
        $this->createModels(1);
        $lessonId = Lesson::first()->id;

        $model = Quiz::factory()
            ->create([
                'lesson_id' => $lessonId
            ])
            ->first();

        $this->getModel()::factory([
            'quiz_id' => $model->id
        ])->create();

        $destination = Quiz::factory()
            ->create([
                'lesson_id' => $lessonId
            ])
            ->first();

        $url = $this->getResourcePath() . '/import';

        $cloneData = [
            'source_quiz_id' => $model->id,
            'target_quiz_id' => $destination->id
        ];

        $this->json('post', $url, $cloneData)
            ->assertUnauthorized();

        $sourceOptionsCount = $model->options()->count();

        $this->authAsUser([$url], [RolesEnum::MODERATOR]);
        $this->postJson($url, $cloneData);

        $this->assertSame($sourceOptionsCount, $destination->options()->count());
    }
}
