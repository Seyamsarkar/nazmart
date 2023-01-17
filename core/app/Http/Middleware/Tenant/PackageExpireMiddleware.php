<?php

namespace App\Http\Middleware\Tenant;

use App\Helpers\ResponseMessage;
use App\Mail\BasicMail;
use App\Models\PaymentLogs;
use App\Models\User;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class PackageExpireMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (tenant()) {
            $current_tenant_expire_date = tenant()->expire_date ?? '';
            $diff = Carbon::parse($current_tenant_expire_date)->lessThan(Carbon::today());

            if (!empty($current_tenant_expire_date) && $diff) {
                return redirect()->route('tenant.frontend.package.expired');
            }

            return $next($request);
        }
    }
}
