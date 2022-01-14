<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use App\Models\AppSetting;
use Cache;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Paginator::useBootstrap();

        
        
        view()->composer('*', function($view)
            {
                //Cache::forget('settings');
                if(Cache::has('settings')){
                    $settings=Cache::get('settings');
                }else{
                    $s=AppSetting::all();
                    $settings_arr=[];
                    foreach($s as $key =>$value){
                        $settings_arr[$value->settings_key]=$value->settings_value;
                    }
                    Cache::forever('settings', $settings_arr);
                    $settings=$settings_arr;
                    //print_r($settings_arr);
                }
                view()->share('settings', $settings);
              
            });
            
       
    }
}
