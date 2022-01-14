<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\User;



class AdminUserController extends Controller
{

    public function index()
    {
        return view('admin.users.all-users', ['users'=>User::withTrashed()->paginate(10)]);
    }

    public function show(User $user)
    {
        return view('admin.users.user-info', ['user'=>$user]);
    }

    public function update(Request $request){
        $this->validate($request, [
            'id'=>'required|integer',
            'role'=>'required', Rule::in(["0", "1", "2"])
        ]);
        $user=User::where('id', $request->id)->first();
        $user->role=(int) $request->role;
        $user->update();

        return redirect()->back()->with('success', 'User updated successfully!');
    }


}
