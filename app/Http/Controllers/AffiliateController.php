<?php

namespace App\Http\Controllers;

use App\Http\Requests\AffiliateStoreRequest;
use App\Http\Requests\AffiliateUpdateRequest;
use App\Http\Resources\AffiliateResource;
use App\Models\Affiliate;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class AffiliateController extends Controller
{
    /**
     * @throws AuthorizationException
     */
    public function index(Request $request)
    {
//        $this->authorize('canViewAll', Affiliate::class);
//        Affiliate::viewable()->paginate($request->get('perPage', self::DEFAULT_PER_PAGE))
        $affiliate = Affiliate::query()->paginate(self::DEFAULT_PER_PAGE);
        return AffiliateResource::collection($affiliate)->response()->getData(true);
    }

    /**
     * @param AffiliateStoreRequest $request
     *
     * @throws AuthorizationException
     * @return JsonResponse
     */
    public function store(AffiliateStoreRequest $request): JsonResponse
    {
        $this->authorize('canCreate', Affiliate::class);

        $data = $request->validated();

        if (empty($data['company_id'])) {
            $data['company_id'] = Auth::user()->companies()->first()->id;
        }
        $affiliate = Affiliate::create($data);

        if ($request->has('course_ids')) {
            $affiliate->courses()->sync($request->get('course_ids'));
        }

        return new JsonResponse(
            new AffiliateResource($affiliate->load('courses')),
            Response::HTTP_OK
        );
    }

    /**
     * @param Affiliate $affiliate
     *
     * @throws AuthorizationException
     * @return JsonResponse
     */
    public function show(Affiliate $affiliate): JsonResponse
    {
        $this->authorize('canViewCurrent', [Affiliate::class, $affiliate]);

        return new JsonResponse(new AffiliateResource($affiliate->load(['courses'])), Response::HTTP_OK);
    }

    /**
     * @param AffiliateUpdateRequest $request
     * @param Affiliate              $affiliate
     *
     * @throws AuthorizationException
     * @throws Throwable
     * @return JsonResponse
     */
    public function update(AffiliateUpdateRequest $request, Affiliate $affiliate): JsonResponse
    {
        $this->authorize('canUpdate', [Affiliate::class, $affiliate]);

        $affiliate->updateOrFail($request->validated());

        if ($request->has('course_ids')) {
            $affiliate->courses()->sync($request->get('course_ids'));
        }

        return new JsonResponse(
            new AffiliateResource($affiliate->load('courses')),
            Response::HTTP_OK
        );
    }

    /**
     * @param Affiliate $affiliate
     *
     * @throws Throwable
     * @return JsonResponse
     */
    public function destroy(Affiliate $affiliate): JsonResponse
    {
        $this->authorize('canDelete', [Affiliate::class, $affiliate]);

        $affiliate->deleteOrFail();

        return new JsonResponse([], Response::HTTP_OK);
    }
}
