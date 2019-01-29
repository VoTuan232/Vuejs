<?php

namespace App\Http\Controllers\api\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Support\Facades\Hash;
use DB;

class CategoryController extends Controller
{
    public function index() {
    	return Category::with('childrens', 'parents')->paginate(2);		
    }

    public function getCategoryModel() {
        return Category::all();
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name_vn' => 'required|string|max:255|unique:categories',
            'name_en' => 'required|string|max:255|unique:categories',
        ]);

        DB::beginTransaction();
        try {
            $category = Category::create($request->only(['name_vn', 'name_en']));

            if(count($request->childrens) > 0) {
                $data_childrens_sync = [];
                foreach ($request->childrens as $key => $value) {
                    array_push($data_childrens_sync, $value['id']);
                }
                    $category->childrens()->sync($data_childrens_sync);
            }

            if(count($request->parents) > 0) {
                $data_parents_sync = [];
                foreach ($request->parents as $key => $value) {
                    array_push($data_parents_sync, $value['id']);
                }
                    $category->parents()->sync($data_parents_sync);
            }
            DB::commit();
            // all good
            return ['message' => 'true'];
        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return ['message' => 'false'];
        }
        return $category;
    }

    public function update(Request $request, Category $category) {
    	$this->validate($request, [
            'name_vn' => 'required|string|max:255|unique:categories,name_vn,'.$category->id,
            'name_en' => 'required|string|max:255|unique:categories,name_en,'.$category->id,
        ]);

    	DB::beginTransaction();
        try {
            $category->update($request->only(['name_vn', 'name_en']));
            
            if(count($request->childrens) > 0) {
                $data_childrens_sync = [];
                foreach ($request->childrens as $key => $value) {
                    array_push($data_childrens_sync, $value['id']);
                } 
                $category->childrens()->sync($data_childrens_sync);
            }

            if(count($request->parents) > 0) {
                $data_parents_sync = [];
                foreach ($request->parents as $key => $value) {
                    array_push($data_parents_sync, $value['id']);
                }
                $category->parents()->sync($data_parents_sync);
            }
            DB::commit();
            // all good
            return ['message' => 'true'];
        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return ['message' => 'false'];
        }
        return $category;
    }

    public function destroy(Category $category) {
    	$category->delete();
    }
}
