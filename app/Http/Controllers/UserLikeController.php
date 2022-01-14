<?php

namespace App\Http\Controllers;



use App\Models\CommentLike;

use App\Models\TopicLike;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class UserLikeController extends Controller
{


    //if auth user clicks something, we check if he has done something for this topic
    //request->type is 'l' for like or 'd' for dislike. if we receive something else (user has tempered with code), we won't do anything
    public function like(Request $request)
    {
      // Log::info($request->all());

        //$topic=Topic::findOrFail($topic);
        //check if there is topic->id with user_id in topic_likes table
        //if there aren't any, we create new row
        //if there is, we check type
        //

        $result = Validator::make($request->all(), [
            'type' => ['required', Rule::in(['l', 'd'])],
            'id' => ['required', 'integer'],
            'model' => ['required', Rule::in(['comment', 'topic'])],
        ]);


        if (!$result->fails()) {

            if ($request->model === "comment") {
                $rowCollection = CommentLike::withTrashed()->where('comment_id', $request->id)->get();
            } else {
                $rowCollection = TopicLike::withTrashed()->where('topic_id', $request->id)->get();
            }

            $row = $rowCollection->where('user_id', auth()->id())->first();
            $likes = $rowCollection->where('type', 'l')->count();
            $dislikes = $rowCollection->where('type', 'd')->count();
            if ($row) {

                //user has already liked or disliked this topic
                if ($row->type === $request->type) {
                    //user has clicked again same icon (he wants to undo)
                    $row->update(['type' => null]);
                    if ($request->type === "l") {
                        $likes = $likes - 1;

                    } else {
                        $dislikes = $dislikes - 1;
                    }

                } else {
                    if (is_null($row->type)) {
                        //user has removed his like or dislike and wants to like again
                        if ($request->type === "l") {
                            $likes = $likes + 1;
                        } else {
                            $dislikes = $dislikes + 1;
                        }
                    } else {
                        //user will switch like to dislike and vice versa
                        if ($request->type === "l") {
                            $likes = $likes + 1;
                            $dislikes = $dislikes - 1;
                        } else {
                            $dislikes = $dislikes + 1;
                            $likes = $likes - 1;
                        }
                    }
                    //user has clicked different icon. he wants to undo previous and make different type
                    $row->update(['type' => $request->type]);
                }


            } else {
                //user has never liked or disliked this topic
                if ($request->model === "topic") {
                    $like = new TopicLike();
                    $like->topic_id = $request->id;
                } else {
                    $like = new CommentLike();
                    $like->comment_id = $request->id;
                }

                $like->user_id = auth()->id();
                $like->type = $request->type;
                $like->save();
                if ($request->type === "l") {
                    $likes = $likes + 1;
                } else {
                    $dislikes = $dislikes + 1;
                }
            }
            return response()->json(['success' => ['likes' => $likes, 'dislikes' => $dislikes, 'id' => $request->id, 'model' => $request->model]]);
        } else {
            //user has tempered with code
            return response()->json(['error']);
        }
    }

}
