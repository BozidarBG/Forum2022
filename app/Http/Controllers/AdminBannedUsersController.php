<?php

namespace App\Http\Controllers;

use App\Models\Banned;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminBannedUsersController extends Controller
{

    public function index()
    {
        return view('admin.users.banned-users', ['banned_users'=>Banned::with('user')->paginate(10)]);

    }


    public function store(Request $request){
        //dd($request->all());
        $this->validate($request,[
            'id'=>'required',
            'reason_select'=>'required_without:reason_textarea',
            'reason_textarea'=>'required_without:reason_select',
            'ban_until'=>'required', Rule::in([1,2,3,4,5])
        ]);

        switch ($request->ban_until){
            case 1:
                $date=Carbon::now()->addHours(48);
                break;
            case 2:
                $date=Carbon::now()->addWeek();
                break;
            case 3:
                $date=Carbon::now()->addWeeks(2);
                break;
            case 4:
                $date=Carbon::now()->addMonth();
                break;
            default:
                $date=Carbon::now()->addCentury();
        }

        $banned=new Banned();
        $banned->user_id=$request->id;
        $banned->reason=$request->reason_select ?? $request->reason_textarea;
        $banned->banned_by=auth()->id();
        $banned->until=$date;
        $banned->save();

        return redirect()->back()->with('success', 'User banned successfully!');

    }


    public function update(Request $request){
        //dd($request->all());

        $this->validate($request, [
            'id'=>'required|integer',
            'role'=>'required', Rule::in([0, 1, 2])
        ]);
        $user=User::where('id', $request->id)->first();
        $user->update(['role'=>(int) $request->role]);

        return redirect()->back()->with('success', 'User updated successfully!');
    }



    //unban. we just soft delete ban so that we can later see how many times this user has been banned
    public function destroy(User $user)
    {
        $banned_user=Banned::find('user_id', $user->id)->first();
        if($banned_user){
            $banned_user->delete();
            return redirect()->back()->with('success', 'User unbanned!');
        }else{
            return redirect()->back()->with('errors', 'Something went wrong!');
        }


    }
}
