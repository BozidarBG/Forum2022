<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class UserCommentController extends Controller
{
    /*
    public function store2(Request $request){
        $this->validate($request,[
            'body'=>'string|min:2'
        ]);

        //Log::info($request->all());
        if($request->has('topic_id')){
            $topic=Topic::where('id', $request->topic_id)->first();
            if($topic){
                $topic->comments()->create([
                    'user_id'=>auth()->id(),
                    'body'=>$request->body
                ]);
                $msg='Comment added successfully!';
            }


        }elseif($request->has('comment_id')){
            $comment=Comment::where('id', $request->comment_id)->first();
            if($comment){
                $comment->comments()->create([
                    'user_id'=>auth()->id(),
                    'body'=>$request->body
                ]);
                $msg='Reply added successfully!';
            }
        }else{
            return redirect()->back();
        }

        return redirect()->back()->with('comment_success', $msg);

    }
*/
    public function store(Request $request){
        $this->validate($request,[
            'body'=>'string|min:2'
        ]);
        //Log::info($request->all());
        if($request->has('topic_id')){
            $model=Topic::where('id', $request->topic_id)->first();
            if($model->status===0){
                //topic is closed for comments so user must have temepred with code
                return redirect()->back();
            }
            $msg='Comment added successfully!';

        }elseif($request->has('comment_id')){
            $model=Comment::where('id', $request->comment_id)->first();
            $msg='Reply added successfully!';

        }else{
            return redirect()->back();
        }
        if($model){
            $model->comments()->create([
                'user_id'=>auth()->id(),
                'body'=>$request->body
            ]);

        }
        return redirect()->back()->with('comment_success', $msg);

    }

}
