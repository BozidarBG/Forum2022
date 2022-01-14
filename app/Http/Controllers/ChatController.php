<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use App\Models\User;
use App\Events\PrivateMessageSent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Pusher\Pusher;
use Illuminate\Support\Arr;

class ChatController extends Controller
{

    public function showMessengerPageWithContacts()
    {
        return view('user.messages');
    }

    public function getUnreadMessagesCount(){
        $unread_messages_count=Message::where('to_user',\Auth::id())->where('is_read',0)->count();
        return response()->json(['unread_messages_count'=> $unread_messages_count]);
    }

    public function findUser(Request $request){
        //Log::info($request->all());
        $results=Validator::make($request->all(), ['username'=>'required|min:4|max:50']);

        if(!$results->fails()){
            $users=User::where('username', 'like', '%'.$request->username.'%')->get(['id', 'username', 'avatar']);
            return response()->json(['users'=>$users]);
        }else{
            return response()->json(['error'=>$results->errors()->all()]);
        }
    }

    public function showMessengerContacts()
    {
        //all messages for auth user
        $messages=Message::where('from_user', Auth::id())->orWhere('to_user', Auth::id())->get();

        //contains ids of users that talked to auth user
        $usersThatTalkedToAuthUser=[];
        foreach($messages as $message){
            if(!in_array($message->from_user, $usersThatTalkedToAuthUser)){
                if($message->from_user != Auth::id()){
                    $usersThatTalkedToAuthUser[]=$message->from_user;
                }
            }
            if(!in_array($message->to_user, $usersThatTalkedToAuthUser)){
                if($message->to_user != Auth::id()){
                    $usersThatTalkedToAuthUser[]=$message->to_user;
                }
            }
        }

        $users=User::whereIn('id', $usersThatTalkedToAuthUser)->get();
        //Log::info($usersThatTalkedToAuthUser);
        $data=[];

        foreach($users as $user){
            $unreadMessagesForUser=0;
            $channel=$this->getChannelName($user->id);
            $lastMessageForChannel=null;
            foreach($messages as $message){
                if($message->channel == $channel){
                    if($lastMessageForChannel ===null || $message->created_at > $lastMessageForChannel->created_at){
                        $lastMessageForChannel=$message;
                    }
                    if($message->to_user === Auth::id() && $message->is_read == 0){
                        $unreadMessagesForUser++;
                    }
                }
            }
            $data[]=$this->transform($user, $lastMessageForChannel, $unreadMessagesForUser);
        }

        array_multisort( array_column($data, "sort"), SORT_DESC, $data );

        return response()->json(['data'=>$data, 'ids'=>$usersThatTalkedToAuthUser]);
    }

    protected function transform($user, $message, $unreadMessagesForUser){
        $short_message=strlen($message->message) > 15 ? substr($message->message, 0,13).'...' : $message->message;
        return [
            'id'=>$user->id,
            'username'=>$user->username,
            'avatar'=>$user->getAvatar(),
            'message'=>$message->from_user === Auth::id() ? 'You: '.$short_message : $short_message,
            'created_at'=>$message->created_at->format('d/m/y'),
            'unread_messages'=>$unreadMessagesForUser,
            'sort'=>$message->created_at
        ];
    }


    public function getMessages($user_id){
        $channel=$this->getChannelName($user_id);
        $messages=Message::where('channel', $channel)->get();
        Message::where(['from_user' => $user_id, 'to_user' => Auth::id()])->update(['is_read' => 1]);

        return response()->json(['data'=>$messages]);
    }

    public function savePrivateMessage(Request $request){
        $result=Validator::make($request->all(), [
            'message'=>'string|min:1',
            'user_id'=>'integer|exists:users,id'
        ]);

        if(!$result->fails()){
            $message=new Message();
            $message->from_user=Auth::id();
            $message->to_user=$request->user_id;
            $message->channel=$this->getChannelName($request->user_id);
            $message->message=htmlspecialchars($request->message, ENT_QUOTES);
            if($message->save()){
                broadcast(new PrivateMessageSent(Auth::user(), $message))->toOthers();
                return response()->json('ok');
            }else{
                return response()->json('error');
            }
        }
        return response()->json(['error'=>$result->errors()->all()]);
    }

    //returns "1_50" or "55_56"
    private function getChannelName($toId) : string {
        return Auth::id() > $toId ? $toId.'_'.Auth::id() : Auth::id().'_'.$toId;
    }
}
