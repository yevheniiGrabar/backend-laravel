<?php

namespace App\Http\Controllers;

use App\Classes\ConfigPages\PagesFactory;
use App\Http\Requests\CompanyStoreRequest;
use App\Http\Requests\CompanyUpdateRequest;
use App\Http\Requests\StoreCompanyPageConfigRequest;
use App\Http\Requests\UpdateCompanyPageConfigRequest;
use App\Http\Requests\UpdateConfigRequest;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use App\Models\CompanyPage;
use App\Models\User;
use App\Services\CompanyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
{
    /** @var CompanyService $service */
    private CompanyService $service;

    /**
     * CompanyController constructor.
     */
    public function __construct()
    {
        $this->service = PagesFactory::make(Company::class);
    }

    /**
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @return array
     */
    public function index(): array
    {
//        $this->authorize('canWorkWithCompany', Company::class);


        $user = Auth::user()
            ->companies()
            ->with('affiliates', 'affiliates.departments')
            ->paginate();

        return CompanyResource::collection($user)
            ->response()
            ->getData(true);
    }

    /**
     * @param CompanyStoreRequest $request
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @return JsonResponse
     */
    public function store(CompanyStoreRequest $request): JsonResponse
    {
        $this->authorize('canWorkWithCompany', Company::class);

        $data = $request->validated();
        $data['owner_id'] = Auth::user()->id;

        $company = Company::create($data);

        return $this->show($company);
    }

    /**
     * @param Company $company
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @return JsonResponse
     */
    public function show(Company $company): JsonResponse
    {
        $this->authorize('canWorkWithCompany', Company::class);

        return new JsonResponse(
            new CompanyResource($company),
            Response::HTTP_OK
        );
    }

    /**
     * @param CompanyUpdateRequest $request
     * @param Company $company
     * @return JsonResponse
     * @throws \Throwable
     */
    public function update(CompanyUpdateRequest $request, Company $company): JsonResponse
    {
        $this->authorize('canWorkWithCompany', Company::class);

        $company->updateOrFail($request->validated());

        return $this->show($company);
    }

    /**
     * @param Company $company
     * @return JsonResponse
     * @throws \Throwable
     */
    public function destroy(Company $company): JsonResponse
    {
        $this->authorize('canWorkWithCompany', Company::class);

        $company->deleteOrFail();

        return response_json([], Response::HTTP_NO_CONTENT);
    }

    /**
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @return JsonResponse
     */
    public function getConfig(int $companyId): JsonResponse
    {
        $this->authorize('canViewCurrent', [Company::class, $companyId]);

        return response_json($this->service->configs($companyId));
    }

    /**
     * @param string|null $locale
     *
     * @return JsonResponse
     */
    public function countriesByLocale(string $locale = null): JsonResponse
    {
        return response_json(__('countries', [], $locale));
    }

    /**
     * @param UpdateConfigRequest $request
     * @param int                 $companyId
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @return JsonResponse
     */
    public function updateConfigs(UpdateConfigRequest $request, int $companyId): JsonResponse
    {
        $this->authorize('canUpdate', [Company::class, $companyId]);

        return response_json($this->service->updateConfigs($companyId, $request->validated()));
    }

    /**
     * @param StoreCompanyPageConfigRequest $request
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @return JsonResponse
     */
    public function createPageConfig(StoreCompanyPageConfigRequest $request): JsonResponse
    {
        $payload = $request->validated();
        $payload['entity_type'] = Company::class;

        $this->authorize('canCreate', [CompanyPage::class, $payload['entity_id']]);

        return response_json($this->service->createPageConfigs($payload), Response::HTTP_CREATED);
    }

    /**
     * @param UpdateCompanyPageConfigRequest $request
     * @param int                            $id
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @return JsonResponse
     */
    public function updatePageConfig(UpdateCompanyPageConfigRequest $request, int $id): JsonResponse
    {
        $validated = $request->validated();
        $validated['entity_type'] = Company::class;

        $this->authorize('canUpdate', [CompanyPage::class, $validated['entity_id']]);

        return response_json($this->service->updatePageConfigs($validated, $id));
    }

    /**
     * @param Request $request
     * @param int     $id
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @return JsonResponse
     */
    public function uploadIcon(Request $request, int $id): JsonResponse
    {
        $this->authorize('canUpdate', [CompanyPage::class, $id]);

        $payload = $request->validate([
            'sidebar_icon' => 'nullable|image',
        ]);

        return response_json($this->service->uploadIcon($payload['sidebar_icon'], $id, CompanyPage::class));
    }

    /**
     * @param Request $request
     * @param int     $id
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @return JsonResponse
     */
    public function uploadLogo(Request $request, int $id): JsonResponse
    {
        $this->authorize('canUpdate', [CompanyPage::class, $id]);

        $payload = $request->validate([
            'logo' => 'nullable|image',
        ]);

        return response_json($this->service->uploadIcon($payload['logo'], $id, Company::class));
    }

    /**
     * @param int $id
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @return JsonResponse
     */
    public function deleteConfigs(int $id): JsonResponse
    {
        $this->authorize('canDelete', [CompanyPage::class, $id]);

        return response_json($this->service->delete($id));
    }

    /**
     * @throws \ReflectionException
     *
     * @return JsonResponse
     */
    public function languages(): JsonResponse
    {
        return response_json($this->service->languages());
    }
}
