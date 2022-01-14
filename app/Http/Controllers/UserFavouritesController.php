<?php

namespace App\Http\Controllers;


use App\Models\FavouriteTopic;
use App\Models\FavouriteComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use \Illuminate\Support\Facades\Validator;

class UserFavouritesController extends Controller
{
    public function toggleFavourite(Request $request){
        //Log::info($request->all());
        $result = Validator::make($request->all(), [
            'id' => ['required', 'integer'],
            'model'=>["required",Rule::in(['favourite_topic', 'favourite_comment'])]
        ]);


        if(!$result->fails()) {
            if($request->model==="favourite_topic"){
                $favourites_for_this_topic=FavouriteTopic::where('topic_id', $request->id)->get();
                $favourite=$favourites_for_this_topic->where('user_id', auth()->id())->first();
                $fav_count=$favourites_for_this_topic->count();
                if($favourite){
                    $favourite->delete();
                    $new_count=$fav_count-1;
                }else{
                    $favourite=new FavouriteTopic();
                    $favourite->topic_id=$request->id;
                    $favourite->user_id=auth()->id();
                    $favourite->save();
                    $new_count=$fav_count+1;
                }
            }else{
                $favourites_for_this_comment=FavouriteComment::where('comment_id', $request->id)->get();
                $favourite=$favourites_for_this_comment->where('user_id', auth()->id())->first();
                $fav_count=$favourites_for_this_comment->count();
                if($favourite){
                    $favourite->delete();
                    $new_count=$fav_count-1;
                }else{
                    $favourite=new FavouriteComment();
                    $favourite->comment_id=$request->id;
                    $favourite->user_id=auth()->id();
                    $favourite->save();
                    $new_count=$fav_count+1;
                }
            }
            
            return response()->json(['success'=>['new_count'=>$new_count]]);
        } else {
            //user has tempered with code
            return response()->json(['error']);
        }

    }
    //
}
