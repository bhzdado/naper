<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckToken {

    CONST HTTP_UNAUTHORIZED = Response::HTTP_UNAUTHORIZED;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        
        //$request->headers->set('Accept', 'application/json');
        
        $user = Auth::user();
        
        $oat = DB::table('oauth_access_tokens')->where('user_id', $user->id)->first();

        if (strtotime($oat->expires_at) < strtotime(date('Y-m-d H:i:s'))) {
            $error = "Unauthorized Access";
            $response = self::HTTP_UNAUTHORIZED;
            
            DB::table('oauth_access_tokens')->where('user_id', $user->id)->delete();
            
            return route('api.expiredToken');
            return $this->get_http_response("Error", $error, $response);
        } else {
            DB::table('oauth_access_tokens')
            ->where('user_id', $user->id)
            ->update(array(
                'expires_at' => date("Y-m-d H:i:s", strtotime("+30 minutes"))
            ));
        }
        
        return $next($request);
    }
}
