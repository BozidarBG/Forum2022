<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Banned;
use App\Models\Complaint;
use App\Models\Topic;
use DB;

class AdminDashboardController extends Controller
{

    public function index()
    {
        $users=User::orderBy('created_at', 'desc')->get()->take(10);
        $users_count=User::count();
        $banneds=Banned::with('user')->orderBy('created_at', 'desc')->get()->take(10);
        $banned_users_count=Banned::count();
        $complaints=Complaint::with('user', 'complaintable')->orderBy('created_at', 'desc')->get()->take(10);
        $complaints_count=Complaint::count();
        $topics=Topic::with('user')->orderBy('created_at', 'desc')->get()->take(10);
        $topics_count=Topic::count();

        return view('admin.dashboard', compact('users', 'users_count','banneds', 'banned_users_count', 'complaints', 'complaints_count','topics', 'topics_count'));
    }

}
