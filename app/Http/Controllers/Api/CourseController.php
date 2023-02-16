<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Libraries\ResponseBase;
use App\Models\Category;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Models\Course;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::all();
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
            'course_category_id' => 'required|numeric',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return response()->json($validator->errors(), 422);

        try {
            $category = Category::find($request->course_category_id);
            if(!$category){
                return ResponseBase::error("Category not found", 404);
            }

            $course = new Course();
            $course->title = $request->title;
            $course->course_category_id = $request->course_category_id;
            $course->save();

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
            'course_category_id' => 'required|numeric',
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
            $response = [
                'message' => "Successfully deleted course",
            ];
            return ResponseBase::success($response, 201);
        } catch (\Exception $e) {
            return ResponseBase::error($e->getMessage(), 409);
        }
    }
}