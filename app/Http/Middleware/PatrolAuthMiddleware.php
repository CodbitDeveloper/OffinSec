<?php

namespace App\Http\Middleware;

use App\Site;
use Closure;

class PatrolAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $siteId = $request->header("SiteId");

        if(Site::where("access_code", $siteId)->count() < 1){
            return abort(401, "Unauthenticated");
        }
        return $next($request);
    }
}
