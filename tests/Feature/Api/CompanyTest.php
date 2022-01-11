<?php

namespace Tests\Feature\Api;

use App\Http\Resources\CompanyResource;
use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class CompanyTest extends AbstractApiTest
{
    public function createModels(int $count)
    {
        $user = User::factory($count)->create()->first();
        Company::factory($count)->create(['owner_id' => $user->id]);
    }

    protected function getCreateFields(Model $model): array
    {
        $fields = parent::getCreateFields($model);

        $fields['title'] = $this->faker->company();

        return $fields;
    }

    public function getModel(): string
    {
        return Company::class;
    }

    public function getResource(): string
    {
        return CompanyResource::class;
    }

    public function getResourcePath(): string
    {
        return 'api/companies';
    }

    public function getUpdateData(): array
    {
        return [
            'title' => 'Some new title'
        ];
    }
}
