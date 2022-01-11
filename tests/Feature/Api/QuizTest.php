<?php

namespace Tests\Feature\Api;

use App\Enums\Users\RolesEnum;
use App\Http\Resources\QuizResource;
use App\Models\Company;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\QuizConfig;
use App\Models\QuizOption;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class QuizTest extends AbstractApiTest
{
    public function createModels(int $count)
    {
        $user = User::factory($count)->create()->first();
        $company = Company::factory($count)->create(['owner_id' => $user->id])->first();
        Lesson::factory()
            ->state(function (array $attributes) use ($company) {
                return [
                    'company_id' => $company->id
                ];
            })
            ->count($count)
            ->create();

        return $this->getModel()::factory([
            'lesson_id' => Lesson::first()->id
        ])->create();
    }

    public function getModel(): string
    {
        return Quiz::class;
    }

    public function getResource(): string
    {
        return QuizResource::class;
    }

    public function getResourcePath(): string
    {
        return 'api/quizzes';
    }

    public function getCreateFields(Model $model) : array
    {
        $fields = parent::getCreateFields($model);

        Lesson::factory(1)->create();
        $fields['lesson_id'] = Lesson::first()->id;

        return $fields;
    }

    public function getUpdateData() : array
    {
        return [
            'question' => 'test',
            'type' => Quiz::TYPE_TEXT,
            'order' => 1,
            'help' => 'test'
        ];
    }

    public function testQuizBulkCreate()
    {
        $modelClass = $this->getModel();
        $this->createModels(1);
        $url = $this->getResourcePath() . '/bulk';

        $createData = [];

        foreach ($modelClass::all() as $m) {
            $fields = $this->getCreateFields($m);

            $fields['options'] = [
                [
                    'answer' => 'test',
                    'help' => 'test',
                    'correct' => true
                ]
            ];
            $fields['configs'] = [
                [
                'code' => 'xxx',
                'group' => 'test',
                'enabled' => true,
                'value' => '1x1'
                ]
            ];
            $createData[] = $fields;
        }

        foreach ($modelClass::all() as $model) {
            $model->delete();
        }

        $this->json('post', $url, $createData)
            ->assertUnauthorized();

        $this->authAsUser([$url], [RolesEnum::MODERATOR]);
        $this->postJson($url, $createData);

        $this->assertSame(Quiz::count(), 1);
        $this->assertSame(QuizConfig::count(), 1);
        $this->assertSame(QuizOption::count(), 1);
    }

    public function testQuizBulkUpdate()
    {
        $modelClass = $this->getModel();
        $this->createModels(1);
        $url = $this->getResourcePath() . '/bulk';

        $createData = [];

        foreach ($modelClass::all() as $m) {
            $fields = $this->getCreateFields($m);

            $fields['id'] = Quiz::first()->id;
            $fields['options'] = [
                [
                    'answer' => 'test',
                    'help' => 'test',
                    'correct' => true
                ]
            ];
            $fields['configs'] = [
                [
                    'code' => 'xxx',
                    'group' => 'test',
                    'enabled' => true,
                    'value' => '1x1'
                ]
            ];
            $createData[] = $fields;
        }

        $this->json('put', $url, $createData)
            ->assertUnauthorized();

        $this->authAsUser([$url], [RolesEnum::MODERATOR]);
        $this->putJson($url, $createData);

        $this->assertSame(Quiz::count(), 1);
        $this->assertSame(QuizConfig::count(), 1);
        $this->assertSame(QuizOption::count(), 1);
    }

    public function testBulkDelete()
    {
        $this->createModels(1);
        /**
         * @var $quiz Quiz
         */
        $quiz = Quiz::first();

        $quiz->options()->save(new QuizOption([
            'answer' => 'test',
            'help' => 'test',
            'correct' => true
        ]));

        $quiz->configs()->save( new QuizConfig([
            'code' => 'xxx',
            'group' => 'test',
            'enabled' => true,
            'value' => '1x1'
        ]));

        $url = $this->getResourcePath() . '/bulk';

        $this->json('delete', $url)
            ->assertUnauthorized();

        $this->authAsUser([$url], [RolesEnum::MODERATOR]);

        $this->json('delete', $url, [
            'quiz-options' => [QuizOption::first()->id]
        ]);

        $this->assertSame(QuizOption::count(), 0);
        $this->assertSame(QuizConfig::count(), 1);
        $this->assertSame(Quiz::count(), 1);

        $this->json('delete', $url, [
            'quiz-configs' => [QuizConfig::first()->id]
        ]);

        $this->assertSame(QuizOption::count(), 0);
        $this->assertSame(QuizConfig::count(), 0);
        $this->assertSame(Quiz::count(), 1);

        $this->json('delete', $url, [
            'quizzes' => [Quiz::first()->id]
        ]);

        $this->assertSame(QuizOption::count(), 0);
        $this->assertSame(QuizConfig::count(), 0);
        $this->assertSame(Quiz::count(), 0);
    }
}
