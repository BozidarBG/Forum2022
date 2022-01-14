<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Topic;
use App\Models\TopicLike;
use App\Models\Comment;

class AdminTopicController extends Controller
{

    public function index()
    {
        $topics=Topic::with('category', 'tags', 'user','comments', 'likes')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.topics.index', ['topics'=>$topics, 'title'=>'Active Topics']);
    }

    public function onlyTrashed()
    {
        $topics=Topic::onlyTrashed()->with('category', 'tags', 'user','comments', 'likes')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.topics.index', ['topics'=>$topics,'title'=>'Deleted Topics' ]);
    }


    public function show(Topic $topic)
    {
        $topic=Topic::with('tags', 'category', 'user','comments', 'comments.replies', 'comments.user')->first();

        //$topic_likes=TopicLike::where('topic_id', $topic->id)->get();
        $likes_count=$topic->likesCount(); //$topic_likes->where('type', 'l')->count();
        $dislikes_count=$topic->dislikesCount(); //$topic_likes->where('type', 'd')->count();
        $fav_count=$topic->favourites_count();
        $favourited_by_auth_user=$topic->favouritedByAuthUser();

        return view('admin.topics.single_topic', [
            'topic'=>$topic,
            'likes_count'=>$likes_count,
            'dislikes_count'=>$dislikes_count,
            'fav_count'=>$fav_count,
            'favourited_by_auth_user'=>$favourited_by_auth_user]);
    }


    public function update(Request $request, Topic $topic)
    {
        if($topic->pinned===0){
            $topic->pinned=1;
        }else{
            $topic->pinned=0;
        }
        $topic->update();

        return back()->with('success', "Topic {$topic->title} status changed");
    }


    public function destroy(Topic $topic)
    {
        $topic->delete();
        return redirect()->back()->with('success', 'Topic deleted successfully');
    }

    public function restore($id)
    {
        $topic=Topic::where('id', $id)->withTrashed()->first();
        if($topic){
            $topic->restore();
            return back()->with('success', "Topic is active again");
        }
        return back()->with('error', "Topic not found");
    }

    public function forceDelete(Request $request, $id)
    {
        $topic=Topic::where('id', $id)->withTrashed()->first();
        //dd($topic);
        //delete comments,
       //$comment_ids=$topic->comments()->get()->pluck('id')->toArray();
       $topic->comments()->delete();
        //dd($comment_ids);
        //Comment::delete($comment_ids);
        //favourites
        $topic->favourites()->delete();
        //like topic
        $topic->likesDislikes()->delete();
        //tagtopic
        $topic->tags()->delete();
        $topic->complaints()->delete();
        if($topic->forceDelete()){
            return redirect()->back()->with('success', 'Topic deleted permanently');
        }
    }
}

