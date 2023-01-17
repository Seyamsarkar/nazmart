<?php

namespace Database\Seeders\Tenant;

use App\Jobs\PlaceOrderMailJob;
use App\Jobs\TenantCredentialJob;
use App\Mail\TenantCredentialMail;
use App\Models\Admin;
use App\Models\Status;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class StatusSeed extends Seeder
{
    public function run()
    {
        $status = [
            [
                'id' => 1,
                'name' => 'active'
            ],
            [
                'id' => 2,
                'name' => 'inactive'
            ]
        ];

        Status::insert($status);
    }
}
