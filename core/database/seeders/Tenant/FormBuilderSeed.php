<?php

namespace Database\Seeders\Tenant;

use App\Mail\TenantCredentialMail;
use App\Models\Admin;
use App\Models\FormBuilder;
use App\Models\Language;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class FormBuilderSeed extends Seeder
{
    public function run()
    {
        DB::statement("INSERT INTO `form_builders` (`id`, `title`, `email`, `button_text`, `fields`, `success_message`, `created_at`, `updated_at`) VALUES
            (1, 'Contact Form', 'suzon.xgenious@gmail.com', 'Send Message', '{\"success_message\":\"Your message is sent. Please wait for the response. Thank you!\",\"field_type\":[\"text\",\"email\",\"textarea\"],\"field_name\":[\"your-name\",\"your-email\",\"your-message\"],\"field_placeholder\":[\"Your Name\",\"Your Email\",\"Your Message\"],\"field_required\":[\"on\",\"on\",\"on\"]}', 'Your message is sent. Please wait for the response. Thank you!', '2022-11-15 12:08:27', '2022-11-15 12:10:09')");
    }
}
