<?php

namespace Database\Seeders;

use App\Models\Affiliate;
use App\Models\Company;
use App\Models\Department;
use Database\Factories\DepartmentFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class AffiliateDepartmentsSeeder extends Seeder
{
    public function __construct(public DepartmentFactory $departmentFactory)
    {
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Company $company)
    {
        /** @var Collection<Affiliate> $affiliates */
        $affiliates = Affiliate::factory(2)->for($company)->create();

        $affiliates->each(function (Affiliate $affiliate) use ($company) {
            Department::factory()->create([
                'company_id' => $company->id,
            ]);
        });
    }

    protected function generateUniqueDepartmentName($companyId, &$departmentName)
    {
        if (Department::query()->where(['company_id' => $companyId, 'title' => $departmentName])->exists()) {
            $departmentName .= " #" . uniqid();
            $this->generateUniqueDepartmentName($companyId, $departmentName);
        }

        return $departmentName;
    }
}
