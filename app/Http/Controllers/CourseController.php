<?php

namespace App\Http\Controllers;

use App\Http\Requests\CourseListRequest;
use App\Http\Requests\CourseStoreRequest;
use App\Http\Requests\CourseUpdateRequest;
use App\Http\Requests\SaveCourseLogoRequest;
use App\Http\Resources\CourseMutateResource;
use App\Http\Resources\CourseResource;
use App\Models\Course;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class CourseController extends Controller
{
    /**
     * @param CourseListRequest $request
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @return array
     */
    public function index(CourseListRequest $request): array
    {
        $this->authorize('canViewAll', Course::class);

        /** @var Builder $query */
        $query = Course::viewable();

        if ($request->has('company_id')) {
            $query->where('company_id', $request->get('company_id'));
        }

        if ($request->has('department_id')) {
            $query->where('department_id', $request->get('department_id'));
        }

        if ($request->has('department_unassigned')) {
            $query->whereNull('department_id');
        }

        if ($request->has('affiliates_unassigned')) {
            if ($request->get('affiliates_unassigned') == true) {
                $query->doesntHave('affiliates');
            } else {
                $query->has('affiliates');
            }
        }

        return CourseResource::collection(
            $query->with('department')->paginate($request->get('perPage', self::DEFAULT_PER_PAGE))
        )
        ->response()
        ->getData(true);
    }

    /**
     * @param CourseStoreRequest $request
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @return JsonResponse
     */
    public function store(CourseStoreRequest $request): JsonResponse
    {
        $this->authorize('canCreate', Course::class);

        DB::beginTransaction();

        $image = $request->file('logo');

        if ($image) {
            $imageUploadPath = $image->store('courses', 'public');
        }

        $courseMutateResource = (new CourseMutateResource($request->validated()))
            ->additional(['logo' => $image ? Storage::disk('public')->path($imageUploadPath) : null]);

        /** @var Course $course */
        $course = Course::create($courseMutateResource->toArray());
        if ($request->has('affiliate_ids')) {
            $course->affiliates()->attach($request->get('affiliate_ids'));
        }

        if ($request->has('moderator_ids')) {
            $course->moderators()->sync($request->get('moderator_ids'));
        }

        DB::commit();

        return new JsonResponse(
            new CourseResource($course),
            Response::HTTP_OK
        );
    }

    /**
     * @param Course $course
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @return JsonResponse
     */
    public function show(Course $course): JsonResponse
    {
        $course->load(['company', 'department', 'affiliates', 'moderators']);
        $this->authorize('canViewCurrent', [Course::class, $course->company]);

        return new JsonResponse(new CourseResource($course), Response::HTTP_OK);
    }

    /**
     * @param CourseUpdateRequest $request
     * @param Course              $course
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException|Throwable
     * @return JsonResponse
     */
    public function update(CourseUpdateRequest $request, Course $course): JsonResponse
    {
        $this->authorize('canUpdate', [Course::class, $course->company]);

        DB::beginTransaction();

        $courseMutateResource = new CourseMutateResource($request->toArray());

        $course->updateOrFail($courseMutateResource->toArray());

        if ($request->has('affiliate_ids')) {
            $course->affiliates()->sync($request->get('affiliate_ids'));
        }

        if ($request->has('moderator_ids')) {
            $course->moderators()->sync($request->get('moderator_ids'));
        }

        DB::commit();

        return $this->show($course);
    }

    /**
     * @param Course $course
     * @return JsonResponse
     * @throws Throwable
     */
    public function destroy(Course $course): JsonResponse
    {
        $this->authorize('canDelete', [Course::class, $course->company]);

        $course->deleteOrFail();

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }

    /**
     * @param SaveCourseLogoRequest $request
     * @param Course $course
     * @return JsonResponse
     * @throws Throwable
     */
    public function saveLogo(SaveCourseLogoRequest $request, Course $course): JsonResponse
    {
        $this->authorize('canUpdate', [Course::class, $course->company]);

        try {
            $image = $request->file('logo');

            $imageUploadPath = $image->store('courses', 'public');

            $course->updateOrFail(['logo' => Storage::disk('public')->path($imageUploadPath)]);

            return new JsonResponse('', Response::HTTP_CREATED);
        } catch (Exception $e) {
            Storage::disk('public')->delete($imageUploadPath);
            throw $e;
        }
    }
}
