<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use App\Libraries\ResponseBase;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Course;

class CourseController extends Controller
{
    public function index()
    {
        if (Cache::has('courses')) {
            $courses = Cache::get('courses');
        } else {
            $courses = Course::all();
            Cache::put('courses', $courses, 600);
        }
        if (count($courses) > 0) {
            $response = [
                'data' => $courses,
                'message' => "Successfully recieved courses",
            ];
            return ResponseBase::success($response);
        }

        return ResponseBase::error("Courses not found", 404);
    }

    public function store(Request $request)
    {
        $rules = [
            'title' => 'required|max:255|unique:courses',
            'course_category_id' => 'required|numeric|exists:course_categories,id',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return response()->json($validator->errors(), 422);

        try {
            $course = new Course();
            $course->title = $request->title;
            $course->course_category_id = $request->course_category_id;
            $course->save();

            Cache::forget('courses');

            $response = [
                'data' => $course,
                'message' => "Successfully added course",
            ];
            return ResponseBase::success($response, 201);

        } catch (\Exception $e) {
            return ResponseBase::error($e->getMessage(), 409);
        }
    }

    public function show($id)
    {
        $course = Course::find($id);
        if ($course) {
            $response = [
                'data' => $course,
                'message' => "Successfully recieved course",
            ];
            return ResponseBase::success($response);
        }

        return ResponseBase::error("Course not found", 404);
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'title' => ['required', 'string', 'max:255', Rule::unique('courses')->ignore($id)],
            'course_category_id' => 'required|numeric|exists:course_categories,id',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return response()->json($validator->errors(), 422);

        try {
            $course = Course::find($id);
            if (!$course){
                return ResponseBase::error("Course not found", 404);
            }

            $course->title = $request->title;
            $course->course_category_id = $request->course_category_id;
            $course->save();

            Cache::forget('courses');

            $response = [
                'data' => $course,
                'message' => "Successfully updated course",
            ];
            return ResponseBase::success($response, 201);
        } catch (\Exception $e) {
            return ResponseBase::error($e->getMessage(), 409);
        }
    }

    public function delete($id)
    {
        try {
            $course = Course::find($id);
            if (!$course){
                return ResponseBase::error("Course not found", 404);
            }

            $course->delete();
            Cache::forget('courses');
            $response = [
                'message' => "Successfully deleted course",
            ];
            return ResponseBase::success($response, 201);
        } catch (\Exception $e) {
            return ResponseBase::error($e->getMessage(), 409);
        }
    }
}
