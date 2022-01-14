<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class CommentLike extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table="comment_likes";

    public $fillable=['type'];
}
