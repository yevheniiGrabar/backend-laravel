<?php

namespace App\Http\Controllers;

use App\Http\Requests\DepartmentStoreRequest;
use App\Http\Requests\DepartmentUpdateRequest;
use App\Http\Resources\DepartmentResource;
use App\Models\Course;
use App\Models\Department;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class DepartmentController extends Controller
{
    /**
     * @param Request $request
     *
     * @return array
     */
    public function index(Request $request): array
    {
        return DepartmentResource::collection(
            Department::viewable()->with('company')
                ->paginate($request->get('perPage', self::DEFAULT_PER_PAGE))
        )
        ->response()
        ->getData(true);
    }

    /**
     * @param DepartmentStoreRequest $request
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @return JsonResponse
     */
    public function store(DepartmentStoreRequest $request): JsonResponse
    {
        $data = $request->validated();

        if (empty($data['company_id'])) {
            $data['company_id'] = Auth::user()->companies()->first()->id;
        }

        $this->authorize('canCreate', [Department::class, $data['company_id']]);

        $department = Department::create($data);

        if ($request->has('affiliate_ids')) {
            $department->affiliates()->sync($request->get('affiliate_ids'));
        }

        if ($request->has('course_ids')) {
            $ids = $request->get('course_ids');
            Course::where('department_id', $department->id)
                ->whereNotIn('id', $ids)
                ->update([
                    'department_id' => null
                ]);
            Course::whereIn('id', $ids)
                ->update([
                    'department_id' => $department->id
                ]);
        }

        return $this->show($department);
    }

    /**
     * @param Department $department
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @return JsonResponse
     */
    public function show(Department $department): JsonResponse
    {
        $this->authorize('canViewCurrent', [Department::class, $department->company_id]);

        return new JsonResponse(new DepartmentResource($department), Response::HTTP_OK);
    }

    /**
     * @param DepartmentUpdateRequest $request
     * @param Department              $department
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @return JsonResponse
     */
    public function update(DepartmentUpdateRequest $request, Department $department): JsonResponse
    {
        $this->authorize('canUpdate', [Department::class, $department->company_id]);

        $department->update($request->validated());

        if ($request->has('affiliate_ids')) {
            $department->affiliates()->sync($request->get('affiliate_ids'));
        }

        if ($request->has('course_ids')) {
            $ids = $request->get('course_ids');
            Course::where('department_id', $department->id)
                ->whereNotIn('id', $ids)
                ->update([
                    'department_id' => null
                ]);
            Course::whereIn('id', $ids)
                ->update([
                    'department_id' => $department->id
                ]);
        }

        return $this->show($department);
    }

    /**
     * @param Department $department
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Throwable
     * @return JsonResponse
     */
    public function destroy(Department $department): JsonResponse
    {
        $this->authorize('canDelete', [Department::class, $department->company_id]);

        $department->deleteOrFail();

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }
}
