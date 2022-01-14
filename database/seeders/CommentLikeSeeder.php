<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use \App\Models\User;


class CommentLikeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users=User::all();
        foreach ($users as $user){
            $commentsArr=[];
            for($i=0; $i<40; $i++){
                $randomCommentId=random_int(1,928);
                if(!in_array($randomCommentId, $commentsArr)){
                    $commentsArr[]=$randomCommentId;
                    $x=new \App\Models\CommentLike();
                    $x->user_id=$user->id;
                    $x->comment_id=random_int(1,928);
                    $x->type=random_int(0,1) == "0" ? 'l': 'd';
                    $x->save();
                }
               
            }

        }
    }
}
