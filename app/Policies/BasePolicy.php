<?php

namespace App\Policies;

use App\Enums\Users\RolesEnum;
use App\Models\Company;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Spatie\Permission\PermissionRegistrar;

abstract class BasePolicy
{
    use HandlesAuthorization;

    /** @var array $existsMethods */
    protected array $existsMethods = [];

    /**
     * @param string $name
     * @param array  $arguments
     *
     * @return bool
     */
    public function __call(string $name, array $arguments)
    {
        if (isset($this->existsMethods[$name])) {
            $arguments[] = $this->existsMethods[$name];

            return $this->can(...$arguments);
        }

        throw new \BadMethodCallException(sprintf('Method %s not allowed', $name));
    }

    /**
     * BasePolicy constructor.
     */
    public function __construct()
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    /**
     * @param User     $user
     *
     * @return bool
     */
    protected function userAssignedToCompany(User $user): bool
    {
        if (!$company = $this->getCompanyAuthUser()) {
            return false;
        }

        return $this->containsIdCompany($user, $company->id) || $company->owner_id === $user->id;
    }

    /**
     * @return Company|null
     */
    protected function getCompanyAuthUser(): ?Company
    {
        return auth()->user()?->companies()->first();
    }

    /**
     * @param User $user
     *
     * @return Collection
     */
    protected function getCompaniesUser(User $user): Collection
    {
        return $user->companies;
    }

    /**
     * @param User     $user
     * @param callable $callback
     *
     * @return bool
     */
    public function permissionAndAssign(User $user, callable $callback): bool
    {
        return $callback() && $this->userAssignedToCompany($user);
    }

    /**
     * @param User  $user
     * @param Model $model
     *
     * @return bool
     */
    protected function containsCompanies(User $user, Model $model): bool
    {
        $companies = $this->getCompaniesUser($user);

        return $companies->contains('id', '=', $model->company_id);
    }

    /**
     * @param User $user
     * @param int  $companyId
     *
     * @return bool
     */
    protected function containsIdCompany(User $user, int $companyId): bool
    {
        $companies = $this->getCompaniesUser($user);

        return $companies->contains('id', '=', $companyId);
    }

    /**
     * @param User   $user
     * @param string $access
     *
     * @return bool
     */
    protected function can(User $user, string $access): bool
    {
        return $this->permissionAndAssign($user, fn() => $user->hasDirectPermission($access));
    }
}
