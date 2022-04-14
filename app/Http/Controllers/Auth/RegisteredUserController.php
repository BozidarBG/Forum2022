<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\Support\Str;

class RegisteredUserController extends Controller
{

    public function create()
    {
        return view('auth.register');
    }


    public function store(Request $request)
    {
        //dd($request->all());
        $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::notIn(['deleted user', 'admin', 'administrator'])],
            'username' => ['required', 'string', 'max:20','unique:users',Rule::notIn(['deleted user', 'admin', 'administrator'])],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::min(8)->mixedCase()->numbers()->symbols()],
            'agree'=>['required']
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'username'=>$request->username,
            'slug'=>Str::slug($request->username),
            'password' => Hash::make($request->password),
        ]);

        $profile=new Profile();
        $profile->user_id=$user->id;
        $profile->save();

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('after.registration');
    }

}
