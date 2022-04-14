<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Topic extends Model
{
    use HasFactory, SoftDeletes;



    public function tags(){
        return $this->belongsToMany(Tag::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }



    public function likes(){
        return $this->hasMany(TopicLike::class)->where('type', 'l');
    }
    public function dislikes(){
        return $this->hasMany(TopicLike::class)->where('type', 'd');
    }

    public function likesDislikes(){
        return $this->hasMany(TopicLike::class);
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function commentsCount1()
    {
        return $this->morphMany(Comment::class, 'commentable')->count();
    }

    public function likesCount(){
        return $this->withCount('likes')->where('type', 'l');//->count();
    }
    public function dislikesCount(){
        return $this->likes()->where('type', 'd')->count();

    }

    public function favourites_count(){
        return $this->favourites()->count();
    }

    public function favourites(){
        return $this->hasMany(FavouriteTopic::class);
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

    public function complaints()
    {
        return $this->morphMany(Complaint::class, 'complaintable');
    }
}
