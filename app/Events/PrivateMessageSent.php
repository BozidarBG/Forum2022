<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;


class PrivateMessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user, $message;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($user, Message $message)
    {
        $this->user=$user;
        $this->message=$message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        //Log::warning((string)$this->message->to_user);
        //channel must be $message->to because only user that receives message will receive it via pusher
        //return new Channel('priv-chat');
        return new Channel((string)$this->message->to_user);

    }

    public function broadcastWith(){
        $date=$this->message->created_at->format('Y-m-d');
        $time=$this->message->created_at->format('H:i:s');
        return [
            'data'=>[
                'name'=>$this->user->name,
                'message'=>$this->message->message,
                //'created_at'=>$this->message->created_at->format('Y-m-d H:i:s'),"2015-03-25T12:00:00-06:30"
                'created_at'=>$date.'T'.$time.'Z',
                'created_at_short'=>$this->message->created_at->format('d/m/y'),
                'id'=>$this->user->id,
                'to'=>$this->message->to_user,
                'avatar'=>$this->user->getAvatar()
            ]
        ];
    }

    public function broadcastAs(){
        return 'PrivateMessageSent';
    }
}
