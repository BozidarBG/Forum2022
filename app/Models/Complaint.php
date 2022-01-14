<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Complaint extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable=['complained_by', 'reason', 'link'];

    public function complaintable()
    {
        return $this->morphTo();
    }


    public function user(){
        return $this->belongsTo(User::class, 'complained_by');
    }

    public function getContent(){
        $model=$this->complaintable_type;
        if($model==='App\Topic'){
            return "Title: ".$this->complaintable->title."<br>Description: ".$this->complaintable->description;
        }elseif($model==='App\Comment'){
            return "Comment: ".$this->complaintable->body;
        }
    }


    }
