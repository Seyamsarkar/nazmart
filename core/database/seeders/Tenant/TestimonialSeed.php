<?php

namespace Database\Seeders\Tenant;

use App\Helpers\ImageDataSeedingHelper;
use App\Helpers\SanitizeInput;
use App\Mail\TenantCredentialMail;
use App\Models\Admin;
use App\Models\Language;
use App\Models\Menu;
use App\Models\Page;
use App\Models\PlanFeature;
use App\Models\PricePlan;
use App\Models\Testimonial;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class TestimonialSeed extends Seeder
{
    public function run()
    {
        DB::statement("INSERT INTO `testimonials` (`id`, `name`, `designation`, `description`, `company`, `rating`, `image`, `status`, `created_at`, `updated_at`) VALUES
            (2, 'Wyatt Mayer', 'Chief Operation', 'Growers Supply Company sells high-quality hydroponic equipment, including pumps, timers, controllers, and more.', 'Espinoza and Montoya Associates', 5, '340', 1, '2022-08-15 23:00:37', '2022-11-12 12:19:12'),
            (3, 'Jolene Mercer', 'Marketing', 'Greenhouse Supply Company offers everything you need to start growing indoors. They sell everything from lighting systems to grow tents.', 'Fisher Hunt Traders', 4, '342', 1, '2022-08-15 23:09:13', '2022-11-12 12:19:04'),
            (4, 'Ethan Herrera', 'Chairman', 'Best Shop is a company that sells top quality products at affordable prices. Their products are guaranteed to work and they have great customer service.', 'Macdonald Coffey Trading', 5, '343', 1, '2022-08-15 23:09:47', '2022-11-12 12:18:57'),
            (5, 'John Abraham', 'Supplier', 'Gourmet Gardening Supplies sells a variety of gardening tools and supplies.', 'Gourmet Supplies', 5, '344', 1, '2022-08-16 00:16:17', '2022-11-12 12:18:50')");
    }
}
