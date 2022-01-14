<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TopicLike extends Model
{
    use HasFactory;

    use SoftDeletes;
    protected $table="like_topic";
    protected $fillable=['type'];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function topic(){
        return $this->belongsTo(Topic::class);
    }
}
