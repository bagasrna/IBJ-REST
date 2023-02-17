<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Controller;
use App\Libraries\ResponseBase;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        if (Cache::has('categories')) {
            $categories = Cache::get('categories');
        } else {
            $categories = Category::all();
            Cache::put('categories', $categories, 600);
        }
        if (count($categories) > 0) {
            $response = [
                'data' => $categories,
                'message' => "Successfully recieved categories",
            ];
            return ResponseBase::success($response);
        }

        return ResponseBase::error("Categories not found", 404);
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

            Cache::forget('categories');

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
        if ($category) {
            $response = [
                'data' => $category,
                'message' => "Successfully recieved category",
            ];
            return ResponseBase::success($response);
        }

        return ResponseBase::error("Category not found", 404);
    }

    public function update(Request $request, $id)
    {
        $rules['name'] = ['required', 'string', 'max:255', Rule::unique('course_categories')->ignore($id)];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return response()->json($validator->errors(), 422);

        try {
            $category = Category::find($id);
            if (!$category){
                return ResponseBase::error("Category not found", 404);
            }

            $category->name = $request->name;
            $category->save();

            Cache::forget('categories');

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
            if (!$category){
                return ResponseBase::error("Category not found", 404);
            }

            $category->delete();
            Cache::forget('categories');
            $response = [
                'message' => "Successfully deleted category",
            ];
            return ResponseBase::success($response, 201);
        } catch (\Exception $e) {
            return ResponseBase::error($e->getMessage(), 409);
        }
    }
}
