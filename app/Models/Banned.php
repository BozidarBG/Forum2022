<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Banned extends Model
{
    use HasFactory, SoftDeletes;

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'until'
    ];
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function bannedBy(){
        return $this->belongsTo(User::class, 'banned_by');
    }

    public function bannedTimes(){
        return $this->hasMany(User::class)->withTrashed()->count();
    }
}
