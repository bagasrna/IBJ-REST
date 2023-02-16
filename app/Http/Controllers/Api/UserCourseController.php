<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Libraries\ResponseBase;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Models\UserCourse;
use App\Models\Course;
use App\Models\User;

class UserCourseController extends Controller
{
    public function index()
    {
        $user_courses = UserCourse::all();
        if (count($user_courses) > 0) {
            $response = [
                'data' => $user_courses,
                'message' => "Successfully recieved courses with all user registered",
            ];
            return ResponseBase::success($response);
        }

        return ResponseBase::error("User Course not found", 404);
    }

    public function store(Request $request)
    {
        $rules = [
            'users_id' => 'required|numeric|exists:users,id',
            'course_id' => 'required|numeric|exists:courses,id',
        ];

        $validator = Validator::make($request->all(), $rules);
        $validator->after(function ($validator) use ($request) {
            $count = DB::table('user_courses')
                ->where('users_id', $request->users_id)
                ->where('course_id', $request->course_id)
                ->count();

            if ($count > 0) {
                $validator->errors()->add('users_id', 'The combination of users_id and course_id already exists.');
            }
        });

        if ($validator->fails())
            return response()->json($validator->errors(), 422);

        try {
            $user_course = new UserCourse();
            $user_course->users_id = $request->users_id;
            $user_course->course_id = $request->course_id;
            $user_course->save();

            $response = [
                'data' => $user_course,
                'message' => "Successfully added access course user",
            ];
            return ResponseBase::success($response, 201);
        } catch (\Exception $e) {
            return ResponseBase::error($e->getMessage(), 409);
        }
    }

    public function showByUser($id)
    {
        $user = User::with('courses')->find($id);
        $response = [];

        if ($user) {
            $response['data'] = $user;
            if (count($user->courses) > 0) {
                $response['message'] = "Successfully recieved user with course";
            } else {
                $response['message'] = "User not already have course";
            }
            return ResponseBase::success($response);
        }

        return ResponseBase::error("User not found", 404);
    }

    public function showByCourse($id)
    {
        $course = Course::with('users')->find($id);
        $response = [];

        if ($course) {
            $response['data'] = $course;
            if (count($course->users) > 0) {
                $response['message'] = "Successfully recieved course with user";
            } else {
                $response['message'] = "Course not already have user";
            }
            return ResponseBase::success($response);
        }

        return ResponseBase::error("Course not found", 404);
    }

    public function update(Request $request, $id)
    {
        $request->merge(['id' => $id]);
        $rules = [
            'id' => 'required|numeric|exists:user_courses,id',
            'users_id' => 'required|numeric|exists:users,id',
            'course_id' => 'required|numeric|exists:courses,id',
        ];

        $validator = Validator::make($request->all(), $rules);
        $validator->after(function ($validator) use ($request) {
            $count = DB::table('user_courses')
                ->where('users_id', $request->users_id)
                ->where('course_id', $request->course_id)
                ->count();

            if ($count > 0) {
                $validator->errors()->add('users_id', "Can't update with the same users_id and course_id as before.");
            }
        });

        if ($validator->fails())
            return response()->json($validator->errors(), 422);

        try {
            $user_course = UserCourse::find($id);
            $user_course->users_id = $request->users_id;
            $user_course->course_id = $request->course_id;
            $user_course->save();

            $response = [
                'data' => $user_course,
                'message' => "Successfully updated user course",
            ];
            return ResponseBase::success($response, 201);
        } catch (\Exception $e) {
            return ResponseBase::error($e->getMessage(), 409);
        }
    }

    public function delete($id)
    {
        try {
            $user_course = UserCourse::find($id);
            if (!$user_course) {
                return ResponseBase::error("User course not found", 404);
            }

            $user_course->delete();
            $response = [
                'message' => "Successfully deleted user course",
            ];
            return ResponseBase::success($response, 201);
        } catch (\Exception $e) {
            return ResponseBase::error($e->getMessage(), 409);
        }
    }
}
