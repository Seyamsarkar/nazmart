<?php

namespace Database\Seeders\Tenant;

use App\Helpers\ImageDataSeedingHelper;
use App\Helpers\SanitizeInput;
use App\Mail\TenantCredentialMail;
use App\Models\Admin;
use App\Models\Brand;
use App\Models\Language;
use App\Models\Menu;
use App\Models\Page;
use App\Models\PlanFeature;
use App\Models\PricePlan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class BrandSeed extends Seeder
{
    public function run()
    {
        DB::statement("INSERT INTO `brands` (`id`, `name`, `slug`, `image_id`, `banner_id`, `title`, `description`, `created_at`, `updated_at`, `deleted_at`, `url`, `status`) VALUES
        (2, 'Gucci', 'gucci', 331, 331, 'Gucci', 'Gucci is a Brand', '2022-08-24 04:41:51', '2022-10-31 11:28:59', NULL, '#', NULL),
        (3, 'Intel', 'intel', 330, 330, NULL, 'Intel', '2022-08-31 05:48:05', '2022-10-31 11:29:10', NULL, '#', NULL),
        (4, 'Mark', 'mark', 329, 329, NULL, NULL, '2022-09-10 04:21:04', '2022-10-31 11:29:19', NULL, '#', NULL),
        (5, 'Vagoda', 'vagoda', 328, 328, NULL, NULL, '2022-09-10 04:24:12', '2022-10-31 11:29:29', NULL, '#', NULL),
        (6, 'Quicker', 'quicker', 327, 327, NULL, NULL, '2022-09-10 04:24:43', '2022-10-31 11:29:40', NULL, '#', NULL),
        (7, 'boogie', 'boogie', 330, 330, NULL, NULL, '2022-09-10 04:25:07', '2022-10-31 11:29:51', NULL, '#', NULL),
        (8, 'Ogivo', 'ogivo', 328, 328, NULL, NULL, '2022-09-10 04:26:06', '2022-10-31 11:30:01', NULL, '#', NULL)");
    }
}
