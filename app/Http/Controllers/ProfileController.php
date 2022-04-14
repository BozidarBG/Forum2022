<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use App\Models\Banned;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Database\Eloquent\Builder;

use File;
use Illuminate\Support\Facades\Validator;
use Log;

class ProfileController extends Controller
{
    public function redirectBanned(){
        $banned=Banned::where('user_id', auth()->id())->first();
        //dd($banned);
        return view('user.redirected_if_banned', compact('banned'));
    }

    public function myProfile(){

        $topics=Topic::with('category', 'tags', 'user')
        ->withCount([
            'comments',
            'likesDislikes as likes_count'=>function(Builder $query){
            $query->where('type', 'l');
        }])

        ->where('user_id', auth()->id())
        ->orderBy('created_at', 'desc')
        ->paginate(15);

        return view('user.profile', ['topics'=>$topics, 'page_name'=>"Topics you created", 'profile'=>auth()->user()->profile]);
    }


    public function updateAvatar(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'image' => 'required|image|max:1000',
        ]);

        if ($validator->fails()) {
            return $validator->errors();
        }

        $status = "Not uploaded";

        if ($request->hasFile('image')) {
            $user=Auth::user();
            $image = $request->file('image');
            // Rename image
            $filename = time().$user->username . '.' . $image->guessExtension();


            $image->move($user->avatars_folder, $filename);

            $status = "Uploaded";


            //ako hoćemo samo jednu sliku da imamo, tj avatar, prvo treba da obrišemo postojeću
            //pa onda da usnimimo novu

            $image = isset($user->avatar) ? $user->avatar : null;

            if ($image) {
                $path = parse_url($user->avatars_folder.$user->avatar);
                File::delete(public_path($path['path']));
            }

            $user->avatar = $filename;
            if ($user->save()) {
                return response($status, 200);
            } else {
                return response($status, 500);
            }

        }else{
            return response($status, 500);
        }
    }


    public function updatePassword(Request $request)
    {
        if(Hash::check($request->old_password, auth()->user()->password)){
            $user=auth()->user();
            //dd('ok je');
        }else{
            return redirect()->back()->withErrors(['old_password'=> 'Password is incorrect!']);
        }

        $request->validate([
            'password' => ['required', 'confirmed', Rules\Password::min(8)->mixedCase()->numbers()->symbols()]
        ]);

        $user->update([
            'password' => Hash::make($request->password),
        ]);
        return redirect()->back()->with('profile_updated', 'Password changed successfully!');
    }

    public function updateAbout(Request $request)
    {
        $this->validate($request, [
            'contact_email'=>'email',
            'website'=>"url",
            'location'=>'max:250',
            'about'=>"max:1000"
        ]);

        auth()->user()->profile->public_email=$request->contact_email;
        auth()->user()->profile->website=$request->website;
        auth()->user()->profile->location=$request->location;
        auth()->user()->profile->about=$request->about;
        auth()->user()->profile->update();

        return redirect()->back()->with('profile_updated', 'About me changed successfully!');

    }

    public function deleteProfileAvatar(){
        $user=auth()->user();
        if ($user->avatar) {
            $path = parse_url($user->avatars_folder.$user->avatar);
            File::delete(public_path($path['path']));
        }

        $user->avatar = null;
        $user->save();
        return redirect()->back()->with('profile_updated', 'Avatar deleted!');

    }


    public function deleteProfile(Request $request)
    {
        //delete avatar
        $user=auth()->user();
        if ($user->avatar) {
            $path = parse_url($user->avatars_folder.$user->avatar);
            File::delete(public_path($path['path']));
        }

        $user->profile->public_email=null;
        $user->profile->website=null;
        $user->profile->location=null;
        $user->profile->about=null;
        $user->profile->update();

        $user->avatar = null;
        $user->name='Deleted User';
        $user->username="Deleted User";
        $user->slug=Str::slug('Deleted User');
        $user->password=Hash::make('secret_admin_password');
        $user->email="secret_admin_email@email.com";
        $user->save();

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Profile deleted successfully!');


    }
}
