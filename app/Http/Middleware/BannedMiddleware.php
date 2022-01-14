<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Banned;
use Carbon\Carbon;

class BannedMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $banned_user=Banned::where('user_id', auth()->id())->first();
        if($banned_user){
            $now=Carbon::now();
            if($banned_user->until > $now ){
                return redirect()->action([\App\Http\Controllers\ProfileController::class, 'redirectBanned']);
            }else{
                //ban has expired
                $banned_user->delete();
                return $next($request);
            }
         
        }
        return $next($request);
    }
}
