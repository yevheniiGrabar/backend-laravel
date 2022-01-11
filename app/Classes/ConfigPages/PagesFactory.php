<?php

namespace App\Classes\ConfigPages;

use App\Contracts\IPage;
use App\Models\Company;
use App\Services\CompanyService;

class PagesFactory
{
    public static array $map = [
        Company::class => CompanyService::class
    ];

    /**
     * @param string $type
     *
     * @return IPage
     */
    public static function make(string $type): IPage
    {
        return isset(self::$map[$type])
            ? new self::$map[$type]()
            : throw new \LogicException(sprintf('Class for %s not found.', $type));
    }
}
