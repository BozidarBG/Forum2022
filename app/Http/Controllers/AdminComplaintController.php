<?php

namespace App\Http\Controllers;

use App\Models\Complaint;

class AdminComplaintController extends Controller
{

    public function index()
    {
        return view('admin.users.complaints', ['complaints'=>Complaint::with('user', 'complaintable')->paginate()]);

    }

    public function destroy(Complaint $complaint)
    {
        if($complaint->delete()){
            return redirect()->back()->with(['success'=>'Complaint deleted.']);
        }else{
            return redirect()->back()->with(['error'=>'Oops something went wrong']);
        }
    }
}
