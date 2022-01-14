<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Complaint;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserComplaintController extends Controller
{
    public function store(Request $request)
    {
        //Log::info(\URL::previous());
        //Log::debug($request->all());
        $result = Validator::make($request->all(), [
            'id' => ['required', 'integer'],
            'type'=>['required', Rule::in(['comment', 'topic'])],
            'body'=>['sometimes', 'string']
        ]);

        if(!$result->fails()){
            if($request->type==='topic') {
                $model = Topic::where('id', $request->id)->first();
            }elseif($request->type==='comment'){
                $model = Comment::where('id', $request->id)->first();
            }
            if($model){
                $model->complaints()->create([
                    'complained_by'=>auth()->id(),
                    'reason'=>$request->body,
                    'link'=>\URL::previous()
                ]);
            return redirect()->back()->with(['comment_success'=> "Someone will look into your complaint shortly."]);

                }else{
                    return redirect()->back()->with(['errors'=> 'Something went wrong. Please try again later.']);
                }


        }else{
            //we won't show errors since user has tempered with code
            return redirect()->back();
        }

    }

}
