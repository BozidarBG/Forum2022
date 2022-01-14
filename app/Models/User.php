<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'slug',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public $avatars_folder="images/avatars/";
    public $defaults_folder="images/defaults/";

    public function isAdmin(){
        return $this->role===1;
    }

    public function topics(){
        return $this->hasMany(Topic::class);
    }

    public function comment(){
        return $this->hasMany(Comment::class);
    }


    public function getAvatar(){
        return !is_null($this->avatar) ? $this->avatars_folder.$this->avatar : $this->defaults_folder.'/avatar.png';
    }

    public function favourites(){
        return $this->hasMany(Favourite::class);
    }

    public function profile(){
        return $this->hasOne(Profile::class);
    }

    public function settings(){
        return $this->hasOne(UserSettings::class);
    }

    public function getRole(){
        switch($this->role){
            case '0':
                return 'Regular User';
            break;
            case '1':
                return 'Admin';
                break;
            case '2':
                return 'Moderator';
                break;
            case '3':
                return 'Banned';
                default;
        }
    }

    public function isBanned(){
        //return $this->id;
        $banned=Banned::where('user_id', $this->id)->first();
        return $banned ? true : false;
    }

    public function numberOfBans(){
        return Banned::withTrashed()->where('user_id', $this->id)->count();
    }

    public function banned(){
        return $this->hasMany(Banned::class);
    }

}
