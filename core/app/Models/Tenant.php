<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Laravel\Sanctum\HasApiTokens;
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;

class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase,HasDomains;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function payment_log(): HasOne
    {
        return $this->hasOne(PaymentLogs::class, 'tenant_id', 'id')->latest();
    }

    public function domain(): HasOne
    {
        return $this->hasOne(UserDomain::class, 'tenant_id', 'id');
    }

    public function custom_domain(): HasOne
    {
        return $this->hasOne(CustomDomain::class, 'old_domain', 'id');
    }
}
