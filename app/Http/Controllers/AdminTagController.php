<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AdminTagController extends Controller
{

    public function index()
    {
        $tags=Tag::withCount('topics')->get();
        return view('admin.tags.index', ['tags'=>$tags]);
    }


    public function store(Request $request)
    {
        $result= Validator::make($request->all(), [
            'name'=>['required', 'string', 'max:20', 'unique:tags'],
        ]);

        if(!$result->fails()){
            $obj=collect($request->all())->merge(['slug'=>Str::slug($request->name)]);
            //Log::info($obj);return;
            $tag = Tag::create($obj->toArray());
            Log::info($tag);
            return response()->json(['success'=> true, 'tag'=>$tag]) ;
        }else{
            return response()->json(['errors'=>true, 'tag'=>$result->errors()->all()]);
        }
    }

    public function update(Request $request, $id)
    {
        $result= Validator::make($request->all(), [
            'name'=>['required', 'string', 'max:20', 'unique:tags,name,'.$id],
        ]);
        $tag=Tag::find($id);

        if($tag && !$result->fails()){
            $obj=collect($request->all())->merge(['slug'=>Str::slug($request->name)]);
            $tag->update($obj->toArray());
            return redirect()->back()->with('success', 'Tag updated!');
        }else{
            return response()->json(['errors', $result->errors()->all()]);
        }
    }


    public function destroy($id)
    {
        $tag=Tag::find($id);
        if($tag && $tag->delete()){
            $tag->topics()->detach($tag->id);
            return response()->json(['success', $tag->id]);
        }
        return response()->json(['errors',$tag->id]);
    }
}
