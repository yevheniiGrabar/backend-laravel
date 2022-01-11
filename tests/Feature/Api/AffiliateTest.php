<?php

namespace Tests\Feature\Api;

use App\Enums\Users\RolesEnum;
use App\Http\Resources\AffiliateResource;
use App\Models\Affiliate;
use App\Models\Company;
use App\Models\Course;
use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class AffiliateTest extends AbstractApiTest
{
    public function createModels(int $count)
    {
        $user = User::factory($count)->create()->first();
        Company::factory($count)->create(['owner_id' => $user->id]);

        return $this->getModel()::factory()
            ->create([
                'company_id' => Company::first()->id
            ]);
    }

    public function getModel(): string
    {
        return Affiliate::class;
    }

    public function getResource(): string
    {
        return AffiliateResource::class;
    }

    public function getResourcePath(): string
    {
        return 'api/affiliates';
    }

    public function getCreateFields(Model $model): array
    {
        $fields = parent::getCreateFields($model);

        Affiliate::factory(1)->create([
            'company_id' => Company::first()->id
        ]);
        $fields['company_id'] = Company::first()->id;
        $fields['title'] = $this->faker->company();

        return $fields;
    }

    public function getUpdateData(): array
    {
        return [
            'title' => 'test'
        ];
    }

    public function testCreateWithAffiliates()
    {
        $modelClass = $this->getModel();
        $this->createModels(1);
        $url = $this->getResourcePath();

        $model = $modelClass::first();

        $createData = $this->getCreateFields($model);

        Department::factory()->create([
            'company_id' => Company::first()->id
        ]);
        Course::factory(5)->create();

        $createData['course_ids'] = Course::get()->take(1)->pluck('id')->toArray();

        foreach ($modelClass::all() as $m) {
            $m->delete();
        }

        $this->authAsUser([$url], [RolesEnum::MODERATOR]);
        $response = $this->postJson($url, $createData);

        $content = json_decode($response->getContent());

        $this->assertTrue(!empty($content->id));

        $newModel = Affiliate::find($content->id);

        $this->assertEquals(1, $newModel->courses()->count());
    }

    public function testUpdateAffiliates()
    {
        $modelClass = $this->getModel();
        $this->createModels(1);
        $url = $this->getResourcePath() . '/' . $modelClass::first()->id;

        $this->json('put', $url, [])
            ->assertUnauthorized();

        $this->authAsUser([$url], [RolesEnum::MODERATOR]);

        Department::factory()->create([
            'company_id' => Company::first()->id
        ]);
        Course::factory(5)->create();

        $response = $this->put($url, [
            'course_ids' => Course::get()->take(2)->pluck('id')->toArray()
        ]);

        $content = json_decode($response->getContent());

        $newModel = Affiliate::find($content->id);

        $this->assertEquals(2, $newModel->courses()->count());
    }
}
