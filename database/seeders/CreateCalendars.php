<?php

namespace Database\Seeders;

use App\Models\Calendar;
use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class CreateCalendars extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
//        $companies = Company::all();
        $users = User::all();
//        $this->createCalendars($companies, Company::class);
        $this->createCalendars($users, User::class);
    }

    /**
     * @param Collection $collection
     * @param string $class
     */
    private function createCalendars(Collection $collection, string $class)
    {
        $collection->each(function (Model $item) use ($class) {
            $payload = [
                Company::class => fn($item) => $this->fillCompany($item),
                User::class => fn($item) => $this->fillUser($item),
            ];

            Calendar::query()
                ->firstOrCreate(['owner_id' => $item->id, 'owner_type' => $class], $payload[$class]($item));
        });
    }

    /**
     * @param Company $item
     *
     * @return array
     */
    private function fillCompany(Company $item): array
    {
        return [
            'owner_id' => $item->id,
            'owner_type' => Company::class,
            'summary' => sprintf('%s календарь', $item->title)
        ];
    }

    /**
     * @param User $item
     *
     * @return array
     */
    private function fillUser(User $item): array
    {
        return [
            'id' => $item->email,
            'owner_id' => $item->id,
            'owner_type' => User::class,
            'summary' => sprintf('%s календарь', $item->fullname)
        ];
    }
}
