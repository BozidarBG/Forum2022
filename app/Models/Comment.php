<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Log;

class Comment extends Model
{
    use HasFactory;

    protected $fillable=['user_id', 'body'];
/*
    public function replies()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
*/
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function commentable()
    {
        return $this->morphTo();
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function likes(){

        return $this->hasMany(CommentLike::class)->where('type', 'l');
    }

    public function dislikes(){

        return $this->hasMany(CommentLike::class)->where('type', 'd');
    }

    public function likesDislikes(){

        return $this->hasMany(CommentLike::class);
    }


    public function likesCount(){

        return $this->likes()->withCount('id')->get;
    }

    public function dislikesCount(){

        return $this->dislikes()->withCount();
    }
/*
    public function likedByAuthUser(){
        return in_array(auth()->id(), $this->likes()->pluck('user_id')->toArray());
    }

    public function dislikedByAuthUser(){
        return in_array(auth()->id(), $this->dislikes()->pluck('user_id')->toArray());
    }

    
    public function favouritedByAuthUser(){
        return in_array(auth()->id(), $this->favourites()->pluck('user_id')->toArray());
    }

*/
    public function complaints()
    {
        return $this->morphMany(Complaint::class, 'complaintable');
    }

    public function favourites(){
        return $this->hasMany(FavouriteComment::class);
    }


    public function likedByAuthUser($col){
        return in_array(auth()->id(), $col->where('type', 'l')->pluck('user_id')->toArray());
    }
    
    public function dislikedByAuthUser($col){
        return in_array(auth()->id(), $col->where('type', 'd')->pluck('user_id')->toArray());
    }
    
    public function favouritedByAuthUser($col){
        return in_array(auth()->id(), $col->pluck('user_id')->toArray());
    }
}
