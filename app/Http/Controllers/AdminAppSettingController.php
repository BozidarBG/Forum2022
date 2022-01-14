<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Cache;

use Log;

class AdminAppSettingController extends Controller
{

    public function index()
    {
        return view('admin.settings.index')->with('settings', AppSetting::all());
    }


    public function store(Request $request)
    {
        //Log::info($request->all());
        $result= Validator::make($request->all(), [
            'settings_key'=>['required', 'string', 'max:30', 'min:2'],
            'settings_value' => ['required', 'string', 'max:250'],
        ]);


        if(!$result->fails()){
            $settings=new AppSetting();
            $settings->settings_key=$request->settings_key;
            $settings->settings_value=$request->settings_value;
            $settings->save();
            $this->forgetCache();

            return response()->json(['success'=> 'Settings created!', 'settings'=>$settings]) ;
        }else{
            return response()->json(['errors'=>true, 'settings'=>$result->errors()->all()]);
        }
    }

    public function update(Request $request, $id)
    {

        $result= Validator::make($request->all(), [
            'settings_key'=>['required', 'string', 'max:30', 'min:2'],
            'settings_value' => ['required', 'string', 'max:250'],
        ]);

        $settings=AppSetting::find($id);

        if($settings && !$result->fails()){
            $settings->settings_key=$request->settings_key;
            $settings->settings_value=$request->settings_value;
            $settings->save();
            $this->forgetCache();

            return redirect()->back()->with('success', 'Setting updated!');
        }else{
            return response()->json(['errors', $result->errors()->all()]);
        }
    }


    public function destroy($id)
    {
        $settings=AppSetting::find($id);
        //Log::info($category);
        if($settings && $settings->delete()){
            $this->forgetCache();
            return response()->json(['success', $settings->id]);
        }
        return response()->json(['errors',$settings->id]);


    }

    private function forgetCache(){
        Cache::forget('settings');
    }
}
