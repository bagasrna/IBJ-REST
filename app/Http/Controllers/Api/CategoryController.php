<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Libraries\ResponseBase;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        $response = [];

        if (count($categories) > 0) {
            $response = [
                'data' => $categories,
                'message' => "Successfully recieved categories",
            ];
            return ResponseBase::success($response);
        }

        $response['message'] = "Categories not found";
        return ResponseBase::error($response, 404);
    }

    public function store(Request $request)
    {
        $rules['name'] ='required|max:255|unique:course_categories';

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return response()->json($validator->errors(), 422);

        try {
            $category = new Category();
            $category->name = $request->name;
            $category->save();

            $response = [
                'data' => $category,
                'message' => "Successfully added category",
            ];
            return ResponseBase::success($response, 201);

        } catch (\Exception $e) {
            return ResponseBase::error($e->getMessage(), 409);
        }
    }

    public function show($id)
    {
        $category = Category::find($id);
        $response = [];

        if ($category) {
            $response = [
                'data' => $category,
                'message' => "Successfully recieved category",
            ];
            return ResponseBase::success($response);
        }

        $response['message'] = "Category not found";
        return ResponseBase::error($response, 404);
    }

    public function update(Request $request, $id)
    {
        $rules['name'] = ['required', 'string', 'max:255', Rule::unique('course_categories')->ignore($id)];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return response()->json($validator->errors(), 422);

        try {
            $category = Category::find($id);
            $response = [];
            if (!$category){
                $response['message'] = "Category not found";
                return ResponseBase::error($response, 404);
            }

            $category->name = $request->name;
            $category->save();

            $response = [
                'data' => $category,
                'message' => "Successfully updated category",
            ];
            return ResponseBase::success($response, 201);
        } catch (\Exception $e) {
            return ResponseBase::error($e->getMessage(), 409);
        }
    }

    public function delete($id)
    {
        try {
            $category = Category::with('courses')->find($id);
            $response = [];
            if (!$category){
                $response['message'] = "Category not found";
                return ResponseBase::error($response, 404);
            }

            $category->delete();
            $response = [
                'message' => "Successfully deleted category",
            ];
            return ResponseBase::success($response, 201);
        } catch (\Exception $e) {
            return ResponseBase::error($e->getMessage(), 409);
        }
    }
}
