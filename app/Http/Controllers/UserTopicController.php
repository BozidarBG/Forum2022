<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Tag;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Log;

class UserTopicController extends Controller
{

    public function store(Request $request)
    {
        //dd($request->all());
        $this->validate($request, [
            'title'=>'required|string|min:5|max:200',
            'description'=>'required|string|min:5|max:1000',
            'category'=>'required|exists:categories,id',
            'tags'=>'required|exists:tags,id',
        ]);

        $name_count=Topic::where('title', $request->title)->count();
        if($name_count==0){
            $slug=Str::slug($request->title);
        }else {
            $slug = Str::slug($request->title) . '-' . $name_count;
        }
        $t=new Topic();
        $t->title=$request->title;
        $t->description=$request->description;
        $t->slug=$slug;
        $t->user_id=auth()->id();
        $t->category_id=$request->category;
        $t->save();

        $t->tags()->attach($request->tags);

        return redirect()->back()->with('topic_created', 'Topic created sussessfully!');

    }


    public function create()
    {
        return view('user.create_topic')->with(['categories'=>Category::all(), 'tags'=>Tag::all()]);
    }

    public function update(Topic $topic)
    {
        if($topic->user_id === auth()->id()){
            if($topic->status===1){
                $topic->status=0;
                $topic->save();
                $msg="Topic closed successfully!";
            }else{
                $topic->status=1;
                $topic->save();
                $msg="Topic reopened successfully!";

            }
            return redirect()->back()->with(['topic_success'=>$msg]);
        }
        else{
            return redirect()->back();
        }
    }

}
