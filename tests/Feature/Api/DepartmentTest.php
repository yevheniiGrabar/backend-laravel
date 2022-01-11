<?php

namespace Tests\Feature\Api;

use App\Enums\Users\RolesEnum;
use App\Http\Resources\DepartmentResource;
use App\Models\Affiliate;
use App\Models\Company;
use App\Models\Course;
use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class DepartmentTest extends AbstractApiTest
{
    public function createModels(int $count)
    {
        $user = User::factory($count)->create()->first();
        $company = Company::factory($count)->create(['owner_id' => $user->id])->first();
        return Department::factory($count)->create([
            'company_id' => $company->id
        ]);
    }

    protected function getCreateFields(Model $model): array
    {
        $fields = parent::getCreateFields($model);

        $fields['title'] = $this->faker->company();

        return $fields;
    }

    public function getModel(): string
    {
        return Department::class;
    }

    public function getResource(): string
    {
        return DepartmentResource::class;
    }

    public function getResourcePath(): string
    {
        return 'api/departments';
    }

    public function getUpdateData(): array
    {
        return [
            'title' => 'Some test title'
        ];
    }

    public function testAttachAffiliates()
    {
        $this->createModels(1);
        $department = Department::first();
        $url = $this->getResourcePath() . '/' . $department->id;

        Affiliate::factory()
            ->create([
                'company_id' => Company::first()->id
            ]);

        $this->authAsUser([$url], [RolesEnum::MODERATOR]);

        $this->put($url, [
            'affiliate_ids' => Affiliate::all()->pluck('id')->toArray()
        ]);

        $this->assertTrue($department->affiliates()->count() > 0);
    }

    public function testAttachCourse()
    {
        $this->createModels(1);
        $department = Department::first();
        $url = $this->getResourcePath() . '/' . $department->id;

        Course::factory(1)->create();

        $this->authAsUser([$url], [RolesEnum::MODERATOR]);

        $this->put($url, [
            'course_id' => Course::all()->pluck('id')->toArray()
        ]);

        $this->assertTrue($department->courses()->count() > 0);
    }
}
