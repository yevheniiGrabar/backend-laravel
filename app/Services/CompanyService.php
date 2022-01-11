<?php

namespace App\Services;

use App\Contracts\IPage;
use App\Enums\CompanyConfig\CountryEnum;
use App\Enums\CompanyConfig\LanguagesEnum;
use App\Models\Company;
use App\Models\CompanyPage;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class CompanyService implements IPage
{
    /** @var string[] $classList */
    private $classList = [
        Company::class => 'Company',
        CompanyPage::class => 'CompanyPage',
    ];

    /**
     * @param int $companyId
     *
     * @return Company
     */
    public function configs(int $companyId): Company
    {
        /** @var Company $configs */
        $configs = Company::query()
            ->whereKey($companyId)
            ->selectRaw('id, title, country, locale, logo, color, is_multi_country, buttons_config')
            ->first();

        if (!$configs) {
            throw (new ModelNotFoundException())->setModel('Companies', $companyId);
        }

        return $configs;
    }

    /**
     * @param int   $companyId
     * @param array $payload
     *
     * @return array
     */
    public function updateConfigs(int $companyId, array $payload): array
    {
        /** @var Company $configs */
        if (!$configs = Company::query()->whereKey($companyId)->first()) {
            throw (new ModelNotFoundException())->setModel('Companies', $companyId);
        }

        $configs->update($payload);

        return $configs
            ->refresh()
            ->only(['id', 'title', 'country', 'locale', 'color', 'is_multi_country', 'buttons_config']);
    }

    /**
     * @param array $payload
     *
     * @return CompanyPage|\Illuminate\Database\Eloquent\Model
     */
    public function createPageConfigs(array $payload): CompanyPage
    {
        if (array_key_exists('sidebar_icon', $payload)) {
            //TODO need to transferred to the fileloader class or somewhere
            $name = sprintf(
                '%s_%s',
                $payload['sidebar_name'],
                $payload['sidebar_icon']->getClientOriginalName()
            );

            $payload['sidebar_icon'] = Storage::disk('public')
                ->putFileAs('sidebar_icons', $payload['sidebar_icon'], $name);
        }

        return CompanyPage::query()->create($payload);
    }

    /**
     * @param array $payload
     * @param int   $id
     *
     * @return bool
     */
    public function updatePageConfigs(array $payload, int $id): bool
    {
        return $this->checkOnExist($id)->update($payload);
    }

    /**
     * @param UploadedFile $file
     * @param int          $id
     * @param string       $entityType
     *
     * @return bool
     */
    public function uploadIcon(UploadedFile $file, int $id, string $entityType): bool
    {
        if (!$entity = $entityType::query()->whereKey($id)->first()) {
            throw (new ModelNotFoundException())->setModel($this->classList[$entityType], $id);
        }

        $method = sprintf('upload%sFile', $this->classList[$entityType]);

        return $this->$method($entity, $file);
    }

    /**
     * @param CompanyPage  $config
     * @param UploadedFile $file
     *
     * @return bool
     */
    private function uploadCompanyPageFile(CompanyPage $config, UploadedFile $file): bool
    {
        //TODO need to transferred to the fileloader class or somewhere
        Storage::disk('public')->delete($config->sidebar_icon);

        $name = sprintf('%s_%s', $config->sidebar_name, $file->getClientOriginalName());

        $payload['sidebar_icon'] = Storage::disk('public')
            ->putFileAs('sidebar_icons', $file, $name);

        return $config->update($payload);
    }

    /**
     * @param Company      $company
     * @param UploadedFile $file
     *
     * @return bool
     */
    private function uploadCompanyFile(Company $company, UploadedFile $file): bool
    {
        //TODO need to transferred to the fileloader class or somewhere
        Storage::disk('public')->delete($company->logo);

        $name = sprintf('%s.%s', md5($company->title), $file->getClientOriginalExtension());

        $payload['logo'] = Storage::disk('public')
            ->putFileAs('sidebar_icons', $file, $name);

        return $company->update($payload);
    }


    /**
     * @param int $id
     *
     * @return bool
     */
    public function delete(int $id): bool
    {
        $config = $this->checkOnExist($id);

        //TODO need to transferred to the fileloader class or somewhere
        Storage::disk('public')->delete($config->sidebar_icon);

        return $config->delete();
    }

    /**
     * @throws \ReflectionException
     *
     * @return array
     */
    public function languages(): array
    {
        $countries = CountryEnum::getAll();
        $languages = LanguagesEnum::getAll();
        $configCountries = __('countries');
        $config = [];

        foreach ($countries as $country) {
            $langKey = strtoupper($configCountries[$country]['alpha3']);

            $config[$country] = [
                'title' => $configCountries[$country]['name'],
                'lang' => $languages[$langKey],
                'locale' => $configCountries[$country]['alpha2'],
                'country_tag' => $country
            ];
        }

        return $config;
    }

    /**
     * @param int $id
     *
     * @return CompanyPage|\Illuminate\Database\Eloquent\Model
     */
    private function checkOnExist(int $id): CompanyPage
    {
        if (!$config = CompanyPage::query()->whereKey($id)->first()) {
            throw (new ModelNotFoundException())->setModel('CompanyPage', $id);
        }

        return $config;
    }
}
