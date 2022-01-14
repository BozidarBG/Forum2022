<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AdminCategoriesController extends Controller
{

    public function index()
    {
        $categories=Category::withCount('topics')->get();
        return view('admin.categories.index', ['categories'=>$categories]);
    }


    public function store(Request $request)
    {
        $result= Validator::make($request->all(), [
            'name'=>['required', 'string', 'max:20', 'unique:categories,name'],
            'color' => ['required', 'string', 'max:7'],
        ]);

        if(!$result->fails()){
            $obj=collect($request->all())->merge(['slug'=>Str::slug($request->name)]);

            $category = Category::create($obj->toArray());

            return response()->json(['success'=> true, 'category'=>$category]) ;
        }else{
            return response()->json(['errors'=>true, 'category'=>$result->errors()->all()]);
        }
    }


    public function update(Request $request, $id)
    {
        $result= Validator::make($request->all(), [
            'name'=>['required', 'string', 'max:20', 'unique:categories,name,'.$id],
            'color' => ['required', 'string', 'max:7'],
        ]);

        $category=Category::find($id);

        if($category && !$result->fails()){
            $obj=collect($request->all())->merge(['slug'=>Str::slug($request->name)]);
            $category->update($obj->toArray());

            return redirect()->back()->with('success', 'Category updated!');
        }else{
            return response()->json(['errors', $result->errors()->all()]);
        }
    }


    public function destroy($id)
    {
        $category=Category::find($id);

        if($category && $category->delete()){
            return response()->json(['success', $category->id]);
        }
        return response()->json(['errors',$category->id]);


    }
}
