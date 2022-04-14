<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Tag;
use App\Models\Topic;
use App\Models\User;
use App\Models\TopicLike;
use App\Models\TagTopic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class PagesController extends Controller
{

    public function home()
    {

        $topics=Topic::with('category', 'tags', 'user')
        ->withCount([
            'comments',
            'likesDislikes as likes_count'=>function(Builder $query){
            $query->where('type', 'l');
        }])

        ->where('pinned', 0)
        ->orderBy('created_at', 'desc')
        ->paginate(10);

        $pinned=Topic::with('category', 'tags', 'user')
        ->withCount([
            'comments',
            'likesDislikes as likes_count'=>function(Builder $query){
            $query->where('type', 'l');
        }])
        ->where('pinned', 1)
        ->orderBy('created_at', 'desc')
        ->paginate(10);

        return view('user.home', ['topics'=>$topics, 'pinned'=>$pinned, 'page_name'=>"Cool Forum"]);
    }


    public function allCategories(){
        return view('user.categories', ['categories'=>Category::with('topics')->get()]);
    }

    public function topicsByUser($slug){
        $user=User::where('slug', $slug)->firstOrFail();
        $topics=Topic::with('category', 'tags', 'user','comments', 'likes')
        ->where('user_id', $user->id)
        ->orderBy('created_at', 'desc')
        ->paginate(15);

        return view('user.home', ['topics'=>$topics, 'page_name'=>"Topics for user ".$user->username]);
    }

    public function topicsByCategory($slug){

        $category=Category::where('slug', $slug)->firstOrFail();
        $topics=Topic::with('category', 'tags', 'user')
        ->withCount([
            'comments',
            'likesDislikes as likes_count'=>function(Builder $query){
            $query->where('type', 'l');
        }])

        ->where('category_id', $category->id)
        ->orderBy('created_at', 'desc')
        ->paginate(15);


        return view('user.home', ['topics'=>$topics, 'page_name'=>"Topics for category ".$category->name]);

    }

    public function topicsByTag($slug){
        $tag=Tag::where('slug', $slug)->firstOrFail();
        $tag_ids=TagTopic::where('tag_id', $tag->id)->get()->pluck('topic_id');

        $topics=Topic::with('category', 'tags', 'user')
        ->withCount([
            'comments',
            'likesDislikes as likes_count'=>function(Builder $query){
            $query->where('type', 'l');
        }])

        ->whereIn('id', $tag_ids)
        ->orderBy('created_at', 'desc')
        ->paginate(15);


        return view('user.home', ['topics'=>$topics, 'page_name'=>"Topics for tag ".$tag->name]);

    }

    public function showTopic($slug){

        $topic=Topic::where('slug', $slug)
        ->with('tags', 'category', 'user','comments','favourites', 'likesDislikes', 'comments.user', 'comments.likesDislikes', 'comments.favourites')
        ->first();

        //dd($topic);
        if(!$topic){
            return redirect()->back();
        }

        $view_count=$topic->views;
        $topic->views=$view_count+1;
        $topic->update();

        return view('user.single_topic', [
            'topic'=>$topic,
            'page_name'=>$topic->title
        ]);
    }

    public function search(Request $request){

        if(is_string($request->title) && strlen(trim($request->title))>2){
            $topics=Topic::with('user', 'category', 'tags', 'user','comments', 'likes')
            ->where('title', 'like', '%'.$request->title.'%')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

            return view('user.home', ['topics'=>$topics, 'page_name'=>"Search topic for query: ".$request->title]);
        }else{

            return redirect()->back()->withErrors(['search'=>"Search length must be at least 3 characters long!"]);
        }

    }

    public function seeUser($slug){
        $user=User::where('slug', $slug)->withCount('comment')->firstOrFail();

        $topics=Topic::with('category', 'tags', 'user')
        ->withCount([
            'comments',
            'likesDislikes as likes_count'=>function(Builder $query){
            $query->where('type', 'l');
        }])

        ->where('user_id', $user->id)
        ->orderBy('created_at', 'desc')
        ->paginate(15);

        return view('user.single_user')->with(['user'=>$user, 'topics'=>$topics]);
    }


    public function registrationCompleted(){
        return view('user.registration-email-sent');
    }



}
