<?php

namespace App\Http\Middleware\Tenant;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TenantUserMailVerifyMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guard('web')->check() &&  Auth::guard('web')->user()->email_verified == 0){
            if (!empty(get_static_option('user_email_verify_status'))) {
                return redirect()->route('tenant.user.email.verify');
            }
        }
        return $next($request);
    }
}
