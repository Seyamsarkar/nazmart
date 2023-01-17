<?php

namespace Database\Seeders\Tenant;

use App\Mail\TenantCredentialMail;
use App\Models\Admin;
use App\Models\Language;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Mail;

class LanguageSeed extends Seeder
{
    public function run()
    {
        Language::create([
            'name' => __('English (USA)'),
            'direction' => 0,
            'slug' => 'en_US',
            'status' => 1,
            'default' => 1
        ]);

        Language::create([
            'name' => __('Arabic'),
            'direction' => 1,
            'slug' => 'ar',
            'status' => 1,
            'default' => 0
        ]);
    }
}
