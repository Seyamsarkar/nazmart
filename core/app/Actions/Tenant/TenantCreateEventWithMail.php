<?php

namespace App\Actions\Tenant;

use App\Events\TenantRegisterEvent;
use App\Mail\TenantCredentialMail;
use App\Models\PaymentLogs;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class TenantCreateEventWithMail
{
    public static function tenant_create_event_with_credential_mail($user, $subdomain, $theme='theme-1')
    {
        event(new TenantRegisterEvent($user, $subdomain, $theme));
        try {
            $raw_pass = get_static_option('tenant_default_pass') ?? '12345678';
            $credential_password = $raw_pass;
            $credential_email = $user->email;
            $credential_username = get_static_option('tenant_default_username') ?? $user->username;

            Mail::to($credential_email)->send(new TenantCredentialMail($credential_username, $credential_password));

            return true;
        } catch (\Exception $e) {
        }
    }
}
