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
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class ProductSeed extends Seeder
{
    public function run()
    {
        if (!Schema::hasTable('shipping_methods'))
        {
            Schema::create('shipping_methods', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->unsignedBigInteger('zone_id')->nullable(); // could be zone independent, so default = null
                $table->boolean('is_default')->default(false);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('shipping_method_options'))
        {
            Schema::create('shipping_method_options', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->unsignedBigInteger('shipping_method_id');
                $table->boolean('status')->default(true);
                $table->boolean('tax_status')->default(true);
                $table->string('setting_preset')->nullable();
                $table->float('cost')->default(0);
                $table->float('minimum_order_amount')->nullable();
                $table->string('coupon')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('zones'))
        {
            Schema::create('zones', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('zone_regions'))
        {
            Schema::create('zone_regions', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('zone_id');
                $table->longText('country')->nullable();
                $table->longText('state')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('product_coupons'))
        {
            Schema::create('product_coupons', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('code')->unique();
                $table->string('discount')->nullable();
                $table->string('discount_type')->nullable();
                $table->string('discount_on')->nullable();
                $table->longText('discount_on_details')->nullable();
                $table->date('expire_date')->nullable();
                $table->string('status')->default('draft');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('refund_products'))
        {
            Schema::create('refund_products', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('order_id');
                $table->unsignedBigInteger('product_id');
                $table->boolean('status')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('refund_chat'))
        {
            Schema::create('refund_chat', function (Blueprint $table) {
                $table->id();
                $table->text('title')->nullable();
                $table->text('via')->nullable();
                $table->string('operating_system')->nullable();
                $table->string('user_agent')->nullable();
                $table->longText('description')->nullable();
                $table->text('subject')->nullable();
                $table->string('status')->nullable();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->unsignedBigInteger('admin_id')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('refund_messages'))
        {
            Schema::create('refund_messages', function (Blueprint $table) {
                $table->id();
                $table->longText('message')->nullable();
                $table->string('notify')->nullable();
                $table->string('attachment')->nullable();
                $table->string('type')->nullable();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->unsignedBigInteger('refund_chat_id')->nullable();
                $table->timestamps();
            });
        }

        $this->seedCategories();
        $this->seedSubCategories();
        $this->seedChildCategories();
        $this->seedColors();
        $this->seedSize();
        $this->seedTags();
        $this->seedUnit();
        $this->seedCountries();
        $this->seedStates();
        $this->seedDeliveryOption();
        $this->seedBadge();

        $this->seedProduct();
        $this->seedProductCategory();
        $this->seedProductSubCategories();
        $this->seedProductChildCategories();
        $this->seedProductTags();
        $this->seedProductGalleries();
        $this->seedProductInventories();
        $this->seedProductInventoryDetails();
        $this->seedProductUOM();
        $this->seedProductCreatedBy();
        $this->seedProductDeliveryOption();
        $this->seedProductReturnPolicies();

        if (!Schema::hasTable('campaigns'))
        {
            Schema::create('campaigns', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->longText('subtitle')->nullable();
                $table->bigInteger('image')->nullable();
                $table->timestamp('start_date')->nullable();
                $table->timestamp('end_date')->nullable();
                $table->string('status')->nullable();
                $table->unsignedInteger('admin_id')->nullable();
                $table->unsignedInteger('vendor_id')->nullable();
                $table->string('type')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('campaign_products'))
        {
            Schema::create('campaign_products', function (Blueprint $table) {
                $table->id();
                $table->bigInteger('product_id');
                $table->bigInteger('campaign_id')->nullable();
                $table->decimal('campaign_price')->nullable();
                $table->integer('units_for_sale')->nullable();
                $table->timestamp('start_date')->nullable();
                $table->timestamp('end_date')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('campaign_sold_products'))
        {
            Schema::create('campaign_sold_products', function (Blueprint $table) {
                $table->id();
                $table->bigInteger('product_id')->nullable();
                $table->integer('sold_count')->nullable();
                $table->double('total_amount')->nullable();
                $table->timestamps();
            });
        }

        $this->seedCampaign();
        $this->seedCampaignProducts();
    }

    private function seedCategories()
    {
        DB::statement("INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `image_id`, `status_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
        (6, 'Clothing', 'clothing', NULL, 370, 1, '2022-11-16 09:29:38', '2022-11-16 09:29:38', NULL),
        (7, 'Beauty', 'beauty', NULL, 356, 1, '2022-11-16 09:30:00', '2022-11-16 09:30:00', NULL),
        (8, 'Shoes', 'shoes', NULL, 365, 1, '2022-11-16 09:30:19', '2022-11-16 09:30:19', NULL),
        (9, 'Bag & Laggage', 'bag-laggage', NULL, 358, 1, '2022-11-16 09:30:47', '2022-11-16 09:30:47', NULL),
        (10, 'Man', 'man', NULL, 359, 1, '2022-11-16 09:31:07', '2022-11-16 09:31:07', NULL),
        (11, 'Woman', 'woman', NULL, 363, 1, '2022-11-16 09:31:18', '2022-11-16 09:31:18', NULL),
        (12, 'Baby', 'baby', NULL, 362, 1, '2022-11-16 09:34:38', '2022-11-16 09:34:38', NULL)");
    }

    private function seedSubCategories()
    {
        DB::statement("INSERT INTO `sub_categories` (`id`, `category_id`, `name`, `slug`, `description`, `image_id`, `created_at`, `updated_at`, `deleted_at`, `status_id`) VALUES
        (10, 10, 'T-Shirt', 't-shirt', NULL, 359, '2022-11-16 09:31:55', '2022-11-16 09:31:55', NULL, 1),
        (11, 6, 'Jacket', 'jacket', NULL, 357, '2022-11-16 09:32:19', '2022-11-16 09:32:19', NULL, 1),
        (12, 10, 'Jersy', 'jersy', NULL, 364, '2022-11-16 09:32:42', '2022-11-16 09:32:42', NULL, 1),
        (13, 7, 'Sun Glass', 'sun-glass', NULL, 360, '2022-11-16 09:33:04', '2022-11-16 09:33:04', NULL, 1),
        (14, 11, 'Sharee', 'sharee', NULL, 363, '2022-11-16 09:33:23', '2022-11-16 09:33:23', NULL, 1),
        (15, 8, 'High Heel', 'high-heel', NULL, 356, '2022-11-16 09:33:54', '2022-11-16 09:33:54', NULL, 1),
        (16, 8, 'Baby Shoe', 'baby-shoe', NULL, 362, '2022-11-16 09:35:14', '2022-11-16 09:35:14', NULL, 1),
        (17, 10, 'Pant', 'pant', NULL, 352, '2022-11-16 09:37:12', '2022-11-16 09:37:12', NULL, 1),
        (18, 9, 'Bag', 'bag', NULL, 367, '2022-11-16 09:39:03', '2022-11-16 09:39:03', NULL, 1)");
    }

    private function seedChildCategories()
    {
        DB::statement("INSERT INTO `child_categories` (`id`, `category_id`, `sub_category_id`, `name`, `slug`, `description`, `image_id`, `created_at`, `updated_at`, `deleted_at`, `status_id`) VALUES
        (12, 7, 13, 'Fiber Sun Glass', 'fiber-sun-glass', NULL, 360, '2022-11-16 09:35:45', '2022-11-16 09:35:45', NULL, 1),
        (13, 10, 10, 'T-Shirt Set', 't-shirt-set', NULL, 361, '2022-11-16 09:36:11', '2022-11-16 09:36:11', NULL, 1),
        (14, 10, 17, 'Jeans', 'jeans', NULL, 352, '2022-11-16 09:37:35', '2022-11-16 09:37:35', NULL, 1),
        (15, 6, 11, 'Leather Jacket', 'leather-jacket', NULL, 368, '2022-11-16 09:38:08', '2022-11-16 09:38:08', NULL, 1),
        (16, 9, 18, 'Purse Bag', 'purse-bag', NULL, 367, '2022-11-16 09:39:24', '2022-11-16 09:39:24', NULL, 1),
        (17, 8, 16, 'Fabric Shoe', 'fabric-shoe', NULL, 362, '2022-11-16 09:42:45', '2022-11-16 09:42:45', NULL, 1),
        (18, 11, 14, 'Classic Sharee', 'classic-sharee', NULL, 363, '2022-11-16 09:43:13', '2022-11-16 09:43:13', NULL, 1),
        (19, 10, 10, 'Graphics T-Short', 'graphics-t-short', NULL, 355, '2022-11-16 09:43:43', '2022-11-16 09:43:43', NULL, 1),
        (20, 8, 15, 'Party Heel', 'part-heel', NULL, 375, '2022-11-16 10:40:39', '2022-11-16 10:42:12', NULL, 1)");
    }

    private function seedColors()
    {
        DB::statement("INSERT INTO `colors` (`id`, `name`, `color_code`, `slug`, `created_at`, `updated_at`) VALUES
        (1, 'Red', '#ff3838', 'red', '2022-08-22 05:29:37', '2022-09-20 05:36:03'),
        (3, 'Black', '#000000', 'black', '2022-08-22 05:29:53', '2022-08-22 05:29:53'),
        (4, 'White', '#ffffff', 'white', '2022-08-22 05:30:01', '2022-09-20 04:38:05'),
        (5, 'Blue', '#0984e3', 'blue', '2022-08-22 05:30:12', '2022-09-20 05:31:20'),
        (6, 'Green', '#55efc4', 'green', '2022-08-22 05:30:20', '2022-09-20 05:30:30'),
        (7, 'Yellow', '#feca39', 'yellow', '2022-08-22 05:30:34', '2022-09-20 05:33:20'),
        (8, 'Magenta', '#e82fa7', 'magenta', '2022-09-15 12:16:06', '2022-09-20 05:31:58'),
        (9, 'Pink', '#e84393', 'pink', '2022-09-15 12:16:26', '2022-09-20 05:32:48'),
        (10, 'Purple', '#a600ff', 'purple', '2022-09-15 12:16:40', '2022-09-15 12:16:40'),
        (11, 'Sky Blue', '#54a0ff', 'sky-blue', '2022-09-15 12:16:57', '2022-09-20 05:34:20'),
        (12, 'Olive', '#c4e538', 'olive', '2022-09-15 12:17:14', '2022-09-20 05:37:02')");
    }

    private function seedSize()
    {
        DB::statement("INSERT INTO `sizes` (`id`, `name`, `size_code`, `slug`, `created_at`, `updated_at`) VALUES
        (1, 'Large', 'L', 'large', '2022-08-22 05:31:08', '2022-08-22 05:31:08'),
        (2, 'Small', 'S', 'small', '2022-08-22 05:31:12', '2022-08-22 05:31:12'),
        (3, 'Medium', 'M', 'medium', '2022-08-22 05:31:16', '2022-08-22 05:31:16'),
        (4, 'Very Small', 'XS', 'very-small', '2022-08-22 05:32:07', '2022-08-22 05:32:07'),
        (5, 'Very Large', 'XL', 'very-large', '2022-08-22 05:32:16', '2022-09-12 12:21:36')");
    }

    private function seedTags()
    {
        DB::statement("INSERT INTO `tags` (`id`, `tag_text`, `created_at`, `updated_at`, `deleted_at`) VALUES
        (5, 'abrasives', '2022-11-16 09:44:18', '2022-11-16 09:44:18', NULL),
        (6, 'baby suit', '2022-11-16 09:44:24', '2022-11-16 09:44:24', NULL),
        (7, 'ameriacan logo t shirt', '2022-11-16 09:44:29', '2022-11-16 09:44:29', NULL),
        (8, 'best jeans pant', '2022-11-16 09:44:34', '2022-11-16 09:44:34', NULL),
        (9, 'babys frock', '2022-11-16 09:44:40', '2022-11-16 09:44:40', NULL),
        (10, 'winter dress', '2022-11-16 09:44:46', '2022-11-16 09:44:46', NULL),
        (11, 'best saree for wedding', '2022-11-16 09:44:54', '2022-11-16 09:44:54', NULL),
        (12, 'best saree', '2022-11-16 09:44:58', '2022-11-16 09:44:58', NULL),
        (13, 'gifed saree', '2022-11-16 09:45:03', '2022-11-16 09:45:03', NULL),
        (14, 'color t shirt', '2022-11-16 09:45:08', '2022-11-16 09:45:08', NULL),
        (15, 'amazing t-shirt', '2022-11-16 09:45:12', '2022-11-16 09:45:12', NULL),
        (16, 'stylish hat', '2022-11-16 09:45:18', '2022-11-16 09:45:18', NULL),
        (17, 'denim shirt', '2022-11-16 09:45:27', '2022-11-16 09:45:27', NULL),
        (18, 'best dress for kid', '2022-11-16 09:45:33', '2022-11-16 09:45:33', NULL),
        (19, 'sun glasses', '2022-11-16 09:45:40', '2022-11-16 09:45:40', NULL),
        (20, 'casual t shirt', '2022-11-16 09:45:48', '2022-11-16 09:45:48', NULL),
        (21, 'kameez', '2022-11-16 09:46:06', '2022-11-16 09:46:06', NULL)");
    }

    private function seedUnit()
    {
        DB::statement("INSERT INTO `units` (`id`, `name`, `created_at`, `updated_at`, `deleted_at`) VALUES
        (1, 'Kg', '2022-08-22 05:28:38', '2022-08-22 05:28:38', NULL),
        (2, 'Lb', '2022-08-22 05:28:41', '2022-08-22 05:28:41', NULL),
        (3, 'Dozen', '2022-08-22 05:28:49', '2022-08-22 05:28:49', NULL),
        (4, 'Ltr', '2022-08-22 05:28:53', '2022-08-22 05:28:53', NULL),
        (5, 'g', '2022-08-22 05:29:02', '2022-08-22 05:29:02', NULL),
        (6, 'Piece', '2022-11-16 09:06:11', '2022-11-16 09:06:11', NULL)");
    }

    private function seedCountries()
    {
        DB::statement("INSERT INTO `countries` (`id`, `name`, `status`, `created_at`, `updated_at`) VALUES
        (1, 'Bangladesh', 'publish', '2022-08-22 06:35:32', '2022-08-22 06:35:32'),
        (2, 'USA', 'publish', '2022-08-22 06:35:38', '2022-08-22 06:35:38'),
        (3, 'Turkey', 'publish', '2022-08-22 06:35:43', '2022-08-22 06:35:43'),
        (4, 'Russia', 'publish', '2022-08-22 06:35:48', '2022-08-22 06:35:48'),
        (5, 'China', 'publish', '2022-08-22 06:35:52', '2022-08-22 06:35:52'),
        (6, 'England', 'publish', '2022-08-22 06:35:59', '2022-08-22 06:35:59'),
        (7, 'Saudi Arabia', 'publish', '2022-08-22 06:41:29', '2022-08-22 06:41:29')");
    }

    private function seedStates()
    {
        DB::statement("INSERT INTO `states` (`id`, `name`, `country_id`, `status`, `created_at`, `updated_at`) VALUES
        (1, 'Dhaka', 1, 'publish', '2022-08-22 06:36:11', '2022-08-22 06:36:11'),
        (2, 'Chandpur', 1, 'publish', '2022-08-22 06:36:15', '2022-08-22 06:36:15'),
        (3, 'Noakhali', 1, 'publish', '2022-08-22 06:36:21', '2022-08-22 06:36:21'),
        (4, 'Bhola', 1, 'publish', '2022-08-22 06:36:27', '2022-08-22 06:36:27'),
        (5, 'Barishal', 1, 'publish', '2022-08-22 06:36:32', '2022-08-22 06:36:32'),
        (6, 'Nework', 2, 'publish', '2022-08-22 06:36:43', '2022-08-22 06:36:43'),
        (7, 'Chicago', 2, 'publish', '2022-08-22 06:36:54', '2022-08-22 06:36:54'),
        (8, 'Las Vegas', 2, 'publish', '2022-08-22 06:37:05', '2022-08-22 06:37:05'),
        (9, 'Ankara', 3, 'publish', '2022-08-22 06:37:12', '2022-08-22 06:37:12'),
        (10, 'Istanbul', 3, 'publish', '2022-08-22 06:37:19', '2022-08-22 06:37:19'),
        (11, 'Izmir', 3, 'publish', '2022-08-22 06:37:26', '2022-08-22 06:37:26'),
        (12, 'Moscow', 4, 'publish', '2022-08-22 06:37:34', '2022-08-22 06:37:34'),
        (13, 'Lalingard', 4, 'publish', '2022-08-22 06:37:44', '2022-08-22 06:37:44'),
        (14, 'Siberia', 4, 'publish', '2022-08-22 06:37:55', '2022-08-22 06:37:55'),
        (15, 'Shanghai', 5, 'publish', '2022-08-22 06:38:04', '2022-08-22 06:38:04'),
        (16, 'Anuhai', 5, 'publish', '2022-08-22 06:38:13', '2022-08-22 06:38:13'),
        (17, 'Hong Kong', 5, 'publish', '2022-08-22 06:38:29', '2022-08-22 06:38:29'),
        (18, 'London', 6, 'publish', '2022-08-22 06:38:37', '2022-08-22 06:38:37'),
        (19, 'Madina', 7, 'publish', '2022-08-22 06:41:44', '2022-08-22 06:41:44')");
    }

    private function seedDeliveryOption()
    {
        DB::statement("INSERT INTO `delivery_options` (`id`, `icon`, `title`, `sub_title`, `created_at`, `updated_at`, `deleted_at`) VALUES
        (1, 'las la-gift', 'Estimated Delivery', 'With 4 Days', '2022-08-25 04:04:31', '2022-08-25 04:04:31', NULL),
        (2, 'las la-gift', 'Free Shipping', 'Order over 100$', '2022-08-25 04:04:52', '2022-08-25 04:04:52', NULL),
        (3, 'las la-gift', '7 Days Return', 'Without any damage', '2022-08-25 04:05:17', '2022-08-25 04:05:17', NULL)");
    }

    private function seedBadge()
    {
        DB::statement("INSERT INTO `badges` (`id`, `name`, `image`, `for`, `sale_count`, `type`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
        (2, '100 Sales', 255, NULL, NULL, NULL, 'active', '2022-08-22 06:31:46', '2022-08-22 06:31:46', NULL),
        (3, 'New Arival', 251, NULL, NULL, NULL, 'active', '2022-08-25 04:41:24', '2022-08-25 04:41:24', NULL)");
    }

    private function seedProduct()
    {
        DB::statement("INSERT INTO `products` (`id`, `name`, `slug`, `summary`, `description`, `image_id`, `price`, `sale_price`, `cost`, `badge_id`, `brand_id`, `status_id`, `product_type`, `sold_count`, `min_purchase`, `max_purchase`, `is_refundable`, `is_in_house`, `is_inventory_warn_able`, `created_at`, `updated_at`, `deleted_at`) VALUES
        (190, 'High Heel Wedding Shoes', 'high-heel-wedding-shoes', 'No Import Fees Deposit and $25.56 Shipping to Bangladesh', '<h3 class=\"product-facts-title\" style=\"margin-right: 0px; margin-bottom: 0px; margin-left: 0px; padding: 4px 0px 14px; font-family: \" amazon=\"\" ember\",=\"\" arial,=\"\" sans-serif;=\"\" font-weight:=\"\" 700;=\"\" line-height:=\"\" 20px;=\"\" color:=\"\" rgb(15,=\"\" 17,=\"\" 17);=\"\" font-size:=\"\" 16px;=\"\" text-rendering:=\"\" optimizelegibility;\"=\"\"><ul class=\"a-unordered-list a-vertical a-spacing-small\" style=\"margin-right: 0px; margin-bottom: 0px; margin-left: 18px; padding: 0px; list-style-type: none; font-size: 14px; color: rgb(15, 17, 17); font-family: &quot;Amazon Ember&quot;, Arial, sans-serif;\"><li style=\"margin: 0px; padding: 0px; list-style: disc; overflow-wrap: break-word;\"><span class=\"a-list-item a-size-base a-color-secondary\" style=\"margin: 0px; padding: 0px; line-height: 20px !important;\">Heel Height approximately 3.0\" Platform measures approximately 0.25\"| True size to fit.</span></li></ul><ul class=\"a-unordered-list a-vertical a-spacing-small\" style=\"margin-right: 0px; margin-bottom: 0px; margin-left: 18px; padding: 0px; list-style-type: none; font-size: 14px; color: rgb(15, 17, 17); font-family: &quot;Amazon Ember&quot;, Arial, sans-serif;\"><li style=\"margin: 0px; padding: 0px; list-style: disc; overflow-wrap: break-word;\"><span class=\"a-list-item a-size-base a-color-secondary\" style=\"margin: 0px; padding: 0px; line-height: 20px !important;\">All your friends will be asking your advice when they see you with these sexy sandals! The open toe and strappy with sparkling rhinestone design front is eye-catching and alluring and will have envious stares on you all night long.</span></li></ul><ul class=\"a-unordered-list a-vertical a-spacing-small\" style=\"margin-right: 0px; margin-bottom: 0px; margin-left: 18px; padding: 0px; list-style-type: none; font-size: 14px; color: rgb(15, 17, 17); font-family: &quot;Amazon Ember&quot;, Arial, sans-serif;\"><li style=\"margin: 0px; padding: 0px; list-style: disc; overflow-wrap: break-word;\"><span class=\"a-list-item a-size-base a-color-secondary\" style=\"margin: 0px; padding: 0px; line-height: 20px !important;\">This pair is perfectly designed for steady steps, as it features a single, slim sole that ideally balances the heel height with the rest of the sleek shoe design.</span></li></ul><ul class=\"a-unordered-list a-vertical a-spacing-small\" style=\"margin-right: 0px; margin-bottom: 0px; margin-left: 18px; padding: 0px; list-style-type: none; font-size: 14px; color: rgb(15, 17, 17); font-family: &quot;Amazon Ember&quot;, Arial, sans-serif;\"><li style=\"margin: 0px; padding: 0px; list-style: disc; overflow-wrap: break-word;\"><span class=\"a-list-item a-size-base a-color-secondary\" style=\"margin: 0px; padding: 0px; line-height: 20px !important;\">This stunning pair of heels is ideal for weddings, parties and every other special occasion that calls for dressy, upscale shoes!</span></li></ul><ul class=\"a-unordered-list a-vertical a-spacing-small\" style=\"margin-right: 0px; margin-bottom: 0px; margin-left: 18px; padding: 0px; list-style-type: none; font-size: 14px; color: rgb(15, 17, 17); font-family: &quot;Amazon Ember&quot;, Arial, sans-serif;\"><li style=\"margin: 0px; padding: 0px; list-style: disc; overflow-wrap: break-word;\"><span class=\"a-list-item a-size-base a-color-secondary\" style=\"margin: 0px; padding: 0px; line-height: 20px !important;\">Featuring a slim straps that hugs your ankle for custom support and provides a comfort throughout wear. Your feet will not slip, turn or move out of place while wearing these gorgeous sandals!</span></li></ul><span style=\"margin: 0px; padding: 0px; font-size: 14px; color: rgb(51, 51, 51); font-family: &quot;segoe ui&quot;, Helvetica, &quot;droid sans&quot;, Arial, &quot;lucida grande&quot;, tahoma, verdana, arial, sans-serif;\">&nbsp;apples and other desserts.</span><br></h3><h3 class=\"product-facts-title\" style=\"margin-right: 0px; margin-bottom: 0px; margin-left: 0px; padding: 4px 0px 14px; font-family: \" amazon=\"\" ember\",=\"\" arial,=\"\" sans-serif;=\"\" font-weight:=\"\" 700;=\"\" line-height:=\"\" 20px;=\"\" color:=\"\" rgb(15,=\"\" 17,=\"\" 17);=\"\" font-size:=\"\" 16px;=\"\" text-rendering:=\"\" optimizelegibility;\"=\"\"><div class=\"a-fixed-left-grid product-facts-detail\" style=\"margin: 0px 0px 18px; padding: 0px; font-size: 14px; position: relative; line-height: 16px; color: rgb(15, 17, 17); font-family: \" amazon=\"\" ember\",=\"\" arial,=\"\" sans-serif;\"=\"\"></div></h3>', '375', 250, 240, 250, 2, 2, 1, 1, NULL, 1, 10, 0, 1, 1, '2022-11-16 10:29:36', '2022-11-16 10:42:27', NULL),
        (191, 'Mans Silver Ridge Lite Long Sleeve Shirt', 'mans-silver-ridge-lite-long-sleeve-shirt-1', 'No Import Fees Deposit and $25.56 Shipping to Bangladesh', '<h3 class=\"product-facts-title\" style=\"margin-right: 0px; margin-bottom: 0px; margin-left: 0px; padding: 4px 0px 14px; font-family: \"Amazon Ember\", Arial, sans-serif; font-weight: 700; line-height: 20px; color: rgb(15, 17, 17); font-size: 16px; text-rendering: optimizelegibility;\">Product Details</h3><div class=\"a-fixed-left-grid product-facts-detail\" style=\"margin: 0px 0px 18px; padding: 0px; font-size: 14px; position: relative; line-height: 16px; color: rgb(15, 17, 17); font-family: \"Amazon Ember\", Arial, sans-serif;\"><div class=\"a-fixed-left-grid-inner\" style=\"margin: 0px; padding: 0px 0px 0px 140px; position: relative;\"><div class=\"a-fixed-left-grid-col a-col-left\" style=\"margin: 0px 0px 0px -140px; padding: 0px; position: relative; overflow: visible; zoom: 1; min-height: 1px; width: 140px; float: left;\"><span class=\"a-color-base a-text-bold\" style=\"margin: 0px; padding: 0px; word-break: break-word; hyphens: auto; font-weight: 700 !important;\">Fabric Type</span></div><div class=\"a-fixed-left-grid-col a-col-right\" style=\"margin: 0px; padding: 0px 0px 0px 32.0739px; position: relative; overflow: visible; zoom: 1; min-height: 1px; width: 534.588px; float: left;\"><span class=\"a-color-secondary\" style=\"margin: 0px; padding: 0px; word-break: break-word; hyphens: auto; color: rgb(86, 89, 89) !important;\">100% Polyester</span></div></div></div><div class=\"a-fixed-left-grid product-facts-detail\" style=\"margin: 0px 0px 18px; padding: 0px; font-size: 14px; position: relative; line-height: 16px; color: rgb(15, 17, 17); font-family: \"Amazon Ember\", Arial, sans-serif;\"><div class=\"a-fixed-left-grid-inner\" style=\"margin: 0px; padding: 0px 0px 0px 140px; position: relative;\"><div class=\"a-fixed-left-grid-col a-col-left\" style=\"margin: 0px 0px 0px -140px; padding: 0px; position: relative; overflow: visible; zoom: 1; min-height: 1px; width: 140px; float: left;\"><span class=\"a-color-base a-text-bold\" style=\"margin: 0px; padding: 0px; word-break: break-word; hyphens: auto; font-weight: 700 !important;\">Origin</span></div><div class=\"a-fixed-left-grid-col a-col-right\" style=\"margin: 0px; padding: 0px 0px 0px 32.0739px; position: relative; overflow: visible; zoom: 1; min-height: 1px; width: 534.588px; float: left;\"><span class=\"a-color-secondary\" style=\"margin: 0px; padding: 0px; word-break: break-word; hyphens: auto; color: rgb(86, 89, 89) !important;\">Imported</span></div></div></div><div class=\"a-fixed-left-grid product-facts-detail\" style=\"margin: 0px 0px 18px; padding: 0px; font-size: 14px; position: relative; line-height: 16px; color: rgb(15, 17, 17); font-family: \"Amazon Ember\", Arial, sans-serif;\"><div class=\"a-fixed-left-grid-inner\" style=\"margin: 0px; padding: 0px 0px 0px 140px; position: relative;\"><div class=\"a-fixed-left-grid-col a-col-left\" style=\"margin: 0px 0px 0px -140px; padding: 0px; position: relative; overflow: visible; zoom: 1; min-height: 1px; width: 140px; float: left;\"><span class=\"a-color-base a-text-bold\" style=\"margin: 0px; padding: 0px; word-break: break-word; hyphens: auto; font-weight: 700 !important;\">Closure Type</span></div><div class=\"a-fixed-left-grid-col a-col-right\" style=\"margin: 0px; padding: 0px 0px 0px 32.0739px; position: relative; overflow: visible; zoom: 1; min-height: 1px; width: 534.588px; float: left;\"><span class=\"a-color-secondary\" style=\"margin: 0px; padding: 0px; word-break: break-word; hyphens: auto; color: rgb(86, 89, 89) !important;\">Button</span></div></div></div><div class=\"a-fixed-left-grid product-facts-detail\" style=\"margin: 0px 0px 18px; padding: 0px; font-size: 14px; position: relative; line-height: 16px; color: rgb(15, 17, 17); font-family: \"Amazon Ember\", Arial, sans-serif;\"><div class=\"a-fixed-left-grid-inner\" style=\"margin: 0px; padding: 0px 0px 0px 140px; position: relative;\"><div class=\"a-fixed-left-grid-col a-col-left\" style=\"margin: 0px 0px 0px -140px; padding: 0px; position: relative; overflow: visible; zoom: 1; min-height: 1px; width: 140px; float: left;\"><span class=\"a-color-base a-text-bold\" style=\"margin: 0px; padding: 0px; word-break: break-word; hyphens: auto; font-weight: 700 !important;\">Neck Style</span></div><div class=\"a-fixed-left-grid-col a-col-right\" style=\"margin: 0px; padding: 0px 0px 0px 32.0739px; position: relative; overflow: visible; zoom: 1; min-height: 1px; width: 534.588px; float: left;\"><span class=\"a-color-secondary\" style=\"margin: 0px; padding: 0px; word-break: break-word; hyphens: auto; color: rgb(86, 89, 89) !important;\">Collared Neck</span></div></div></div><hr aria-hidden=\"true\" class=\"a-spacing-base a-spacing-top-base a-divider-normal\" style=\"padding: 0px; overflow: visible; border-top: 1px solid rgb(231, 231, 231); font-size: 14px; line-height: 19px; color: rgb(15, 17, 17); font-family: \"Amazon Ember\", Arial, sans-serif; margin-top: 12px !important; margin-bottom: 12px !important;\"><h3 class=\"product-facts-title\" style=\"margin-right: 0px; margin-bottom: 0px; margin-left: 0px; padding: 4px 0px 14px; font-family: \"Amazon Ember\", Arial, sans-serif; font-weight: 700; line-height: 20px; color: rgb(15, 17, 17); font-size: 16px; text-rendering: optimizelegibility;\">About this Item</h3><ul class=\"a-unordered-list a-vertical a-spacing-small\" style=\"margin-right: 0px; margin-bottom: 0px; margin-left: 18px; padding: 0px; list-style-type: none; font-size: 14px; color: rgb(15, 17, 17); font-family: \"Amazon Ember\", Arial, sans-serif;\"><li style=\"margin: 0px; padding: 0px; list-style: disc; overflow-wrap: break-word;\"><span class=\"a-list-item a-size-base a-color-secondary\" style=\"margin: 0px; padding: 0px; line-height: 20px !important;\">Omni-wick - the ultimate moisture management technology for the outdoors. Omni-wick quickly moves moisture from the skin into the fabric where it spreads across the surface to quickly evaporate—keeping you cool and your clothing dry.</span></li></ul><ul class=\"a-unordered-list a-vertical a-spacing-small\" style=\"margin-right: 0px; margin-bottom: 0px; margin-left: 18px; padding: 0px; list-style-type: none; font-size: 14px; color: rgb(15, 17, 17); font-family: \"Amazon Ember\", Arial, sans-serif;\"><li style=\"margin: 0px; padding: 0px; list-style: disc; overflow-wrap: break-word;\"><span class=\"a-list-item a-size-base a-color-secondary\" style=\"margin: 0px; padding: 0px; line-height: 20px !important;\">Handy features: it features two chest pockets to keep your small items secure.</span></li></ul><ul class=\"a-unordered-list a-vertical a-spacing-small\" style=\"margin-right: 0px; margin-bottom: 0px; margin-left: 18px; padding: 0px; list-style-type: none; font-size: 14px; color: rgb(15, 17, 17); font-family: \"Amazon Ember\", Arial, sans-serif;\"><li style=\"margin: 0px; padding: 0px; list-style: disc; overflow-wrap: break-word;\"><span class=\"a-list-item a-size-base a-color-secondary\" style=\"margin: 0px; padding: 0px; line-height: 20px !important;\">Adjustable features: front button closures and button-down cuffs add adjustable comfort.</span></li></ul><ul class=\"a-unordered-list a-vertical a-spacing-small\" style=\"margin-right: 0px; margin-bottom: 0px; margin-left: 18px; padding: 0px; list-style-type: none; font-size: 14px; color: rgb(15, 17, 17); font-family: \"Amazon Ember\", Arial, sans-serif;\"><li style=\"margin: 0px; padding: 0px; list-style: disc; overflow-wrap: break-word;\"><span class=\"a-list-item a-size-base a-color-secondary\" style=\"margin: 0px; padding: 0px; line-height: 20px !important;\">Casual fit: with 100% cotton fabric, this women\'s flannel features a casual fit perfect for everyday wear.</span></li></ul><ul class=\"a-unordered-list a-vertical a-spacing-small\" style=\"margin-right: 0px; margin-bottom: 0px; margin-left: 18px; padding: 0px; list-style-type: none; font-size: 14px; color: rgb(15, 17, 17); font-family: \"Amazon Ember\", Arial, sans-serif;\"><li style=\"margin: 0px; padding: 0px; list-style: disc; overflow-wrap: break-word;\"><span class=\"a-list-item a-size-base a-color-secondary\" style=\"margin: 0px; padding: 0px; line-height: 20px !important;\">Advanced technology: Columbia women\'s silver ridge lite long sleeve shirt features signature wicking fabric that pulls moisture away from the body so sweat can evaporate quickly and UPF 40 sun protection.</span></li></ul>', '359', 774, 533, 774, NULL, 2, 1, 1, NULL, 1, 10, 0, 1, 1, '2022-11-16 10:30:14', '2022-11-16 10:30:22', NULL),
        (192, 'Buck  Long Sleeve Button Down Shirt', 'buck-long-sleeve-button-down-shirt-1', 'No Import Fees Deposit and $25.56 Shipping to Bangladesh', '<h3 class=\"product-facts-title\" style=\"margin-right: 0px; margin-bottom: 0px; margin-left: 0px; padding: 4px 0px 14px; font-family: &quot;Amazon Ember&quot;, Arial, sans-serif; font-weight: 700; line-height: 20px; color: rgb(15, 17, 17); font-size: 16px; text-rendering: optimizelegibility;\">Product Details</h3><h3 class=\"product-facts-title\" style=\"margin-right: 0px; margin-bottom: 0px; margin-left: 0px; padding: 4px 0px 14px; font-family: \" amazon=\"\" ember\",=\"\" arial,=\"\" sans-serif;=\"\" font-weight:=\"\" 700;=\"\" line-height:=\"\" 20px;=\"\" color:=\"\" rgb(15,=\"\" 17,=\"\" 17);=\"\" font-size:=\"\" 16px;=\"\" text-rendering:=\"\" optimizelegibility;\"=\"\"><div class=\"a-fixed-left-grid product-facts-detail\" style=\"margin: 0px 0px 18px; padding: 0px; font-size: 14px; position: relative; line-height: 16px; color: rgb(15, 17, 17); font-family: &quot;Amazon Ember&quot;, Arial, sans-serif;\"><div class=\"a-fixed-left-grid-inner\" style=\"margin: 0px; padding: 0px 0px 0px 140px; position: relative;\"><div class=\"a-fixed-left-grid-col a-col-left\" style=\"margin: 0px 0px 0px -140px; padding: 0px; position: relative; overflow: visible; zoom: 1; min-height: 1px; width: 140px; float: left;\"><span class=\"a-color-base a-text-bold\" style=\"margin: 0px; padding: 0px; word-break: break-word; hyphens: auto; font-weight: 700 !important;\">Fabric Type</span></div><div class=\"a-fixed-left-grid-col a-col-right\" style=\"margin: 0px; padding: 0px 0px 0px 32.0739px; position: relative; overflow: visible; zoom: 1; min-height: 1px; width: 534.588px; float: left;\"><span class=\"a-color-secondary\" style=\"margin: 0px; padding: 0px; word-break: break-word; hyphens: auto; color: rgb(86, 89, 89) !important;\">64% Cotton, 34% Polyester, 2% Spandex</span></div></div></div><div class=\"a-fixed-left-grid product-facts-detail\" style=\"margin: 0px 0px 18px; padding: 0px; font-size: 14px; position: relative; line-height: 16px; color: rgb(15, 17, 17); font-family: &quot;Amazon Ember&quot;, Arial, sans-serif;\"><div class=\"a-fixed-left-grid-inner\" style=\"margin: 0px; padding: 0px 0px 0px 140px; position: relative;\"><div class=\"a-fixed-left-grid-col a-col-left\" style=\"margin: 0px 0px 0px -140px; padding: 0px; position: relative; overflow: visible; zoom: 1; min-height: 1px; width: 140px; float: left;\"><span class=\"a-color-base a-text-bold\" style=\"margin: 0px; padding: 0px; word-break: break-word; hyphens: auto; font-weight: 700 !important;\">Care Instructions</span></div><div class=\"a-fixed-left-grid-col a-col-right\" style=\"margin: 0px; padding: 0px 0px 0px 32.0739px; position: relative; overflow: visible; zoom: 1; min-height: 1px; width: 534.588px; float: left;\"><span class=\"a-color-secondary\" style=\"margin: 0px; padding: 0px; word-break: break-word; hyphens: auto; color: rgb(86, 89, 89) !important;\">Machine Wash</span></div></div></div><div class=\"a-fixed-left-grid product-facts-detail\" style=\"margin: 0px 0px 18px; padding: 0px; font-size: 14px; position: relative; line-height: 16px; color: rgb(15, 17, 17); font-family: &quot;Amazon Ember&quot;, Arial, sans-serif;\"><div class=\"a-fixed-left-grid-inner\" style=\"margin: 0px; padding: 0px 0px 0px 140px; position: relative;\"><div class=\"a-fixed-left-grid-col a-col-left\" style=\"margin: 0px 0px 0px -140px; padding: 0px; position: relative; overflow: visible; zoom: 1; min-height: 1px; width: 140px; float: left;\"><span class=\"a-color-base a-text-bold\" style=\"margin: 0px; padding: 0px; word-break: break-word; hyphens: auto; font-weight: 700 !important;\">Origin</span></div><div class=\"a-fixed-left-grid-col a-col-right\" style=\"margin: 0px; padding: 0px 0px 0px 32.0739px; position: relative; overflow: visible; zoom: 1; min-height: 1px; width: 534.588px; float: left;\"><span class=\"a-color-secondary\" style=\"margin: 0px; padding: 0px; word-break: break-word; hyphens: auto; color: rgb(86, 89, 89) !important;\">Imported</span></div></div></div><div class=\"a-fixed-left-grid product-facts-detail\" style=\"margin: 0px 0px 18px; padding: 0px; font-size: 14px; position: relative; line-height: 16px; color: rgb(15, 17, 17); font-family: &quot;Amazon Ember&quot;, Arial, sans-serif;\"><div class=\"a-fixed-left-grid-inner\" style=\"margin: 0px; padding: 0px 0px 0px 140px; position: relative;\"><div class=\"a-fixed-left-grid-col a-col-left\" style=\"margin: 0px 0px 0px -140px; padding: 0px; position: relative; overflow: visible; zoom: 1; min-height: 1px; width: 140px; float: left;\"><span class=\"a-color-base a-text-bold\" style=\"margin: 0px; padding: 0px; word-break: break-word; hyphens: auto; font-weight: 700 !important;\">Closure Type</span></div><div class=\"a-fixed-left-grid-col a-col-right\" style=\"margin: 0px; padding: 0px 0px 0px 32.0739px; position: relative; overflow: visible; zoom: 1; min-height: 1px; width: 534.588px; float: left;\"><span class=\"a-color-secondary\" style=\"margin: 0px; padding: 0px; word-break: break-word; hyphens: auto; color: rgb(86, 89, 89) !important;\">Button</span></div></div></div><div class=\"a-fixed-left-grid product-facts-detail\" style=\"margin: 0px 0px 18px; padding: 0px; font-size: 14px; position: relative; line-height: 16px; color: rgb(15, 17, 17); font-family: &quot;Amazon Ember&quot;, Arial, sans-serif;\"><div class=\"a-fixed-left-grid-inner\" style=\"margin: 0px; padding: 0px 0px 0px 140px; position: relative;\"><div class=\"a-fixed-left-grid-col a-col-left\" style=\"margin: 0px 0px 0px -140px; padding: 0px; position: relative; overflow: visible; zoom: 1; min-height: 1px; width: 140px; float: left;\"><span class=\"a-color-base a-text-bold\" style=\"margin: 0px; padding: 0px; word-break: break-word; hyphens: auto; font-weight: 700 !important;\">Country of Origin</span></div><div class=\"a-fixed-left-grid-col a-col-right\" style=\"margin: 0px; padding: 0px 0px 0px 32.0739px; position: relative; overflow: visible; zoom: 1; min-height: 1px; width: 534.588px; float: left;\"><span class=\"a-color-secondary\" style=\"margin: 0px; padding: 0px; word-break: break-word; hyphens: auto; color: rgb(86, 89, 89) !important;\">China</span></div></div></div></h3>', '391', 452, 321, 452, NULL, 2, 1, 1, NULL, 1, 10, 0, 1, 1, '2022-11-16 10:32:18', '2022-11-16 10:32:18', NULL),
        (193, 'Mens Regular-Fit Long-Sleeve Poplin Jacket', 'mens-regular-fit-long-sleeve-poplin-jacket-1', 'No Import Fees Deposit and $25.56 Shipping to Bangladesh', '<h3 class=\"product-facts-title\" style=\"margin-right: 0px; margin-bottom: 0px; margin-left: 0px; padding: 4px 0px 14px; font-family: \"Amazon Ember\", Arial, sans-serif; font-weight: 700; line-height: 20px; color: rgb(15, 17, 17); font-size: 16px; text-rendering: optimizelegibility;\">Product Details</h3><h3 class=\"product-facts-title\" style=\"margin-right: 0px; margin-bottom: 0px; margin-left: 0px; padding: 4px 0px 14px; font-family: \" amazon=\"\" ember\",=\"\" arial,=\"\" sans-serif;=\"\" font-weight:=\"\" 700;=\"\" line-height:=\"\" 20px;=\"\" color:=\"\" rgb(15,=\"\" 17,=\"\" 17);=\"\" font-size:=\"\" 16px;=\"\" text-rendering:=\"\" optimizelegibility;\"=\"\"><div class=\"a-fixed-left-grid product-facts-detail\" style=\"margin: 0px 0px 18px; padding: 0px; font-size: 14px; position: relative; line-height: 16px; color: rgb(15, 17, 17); font-family: \"Amazon Ember\", Arial, sans-serif;\"><div class=\"a-fixed-left-grid-inner\" style=\"margin: 0px; padding: 0px 0px 0px 140px; position: relative;\"><div class=\"a-fixed-left-grid-col a-col-left\" style=\"margin: 0px 0px 0px -140px; padding: 0px; position: relative; overflow: visible; zoom: 1; min-height: 1px; width: 140px; float: left;\"><span class=\"a-color-base a-text-bold\" style=\"margin: 0px; padding: 0px; word-break: break-word; hyphens: auto; font-weight: 700 !important;\">Fabric Type</span></div><div class=\"a-fixed-left-grid-col a-col-right\" style=\"margin: 0px; padding: 0px 0px 0px 32.0739px; position: relative; overflow: visible; zoom: 1; min-height: 1px; width: 534.588px; float: left;\"><span class=\"a-color-secondary\" style=\"margin: 0px; padding: 0px; word-break: break-word; hyphens: auto; color: rgb(86, 89, 89) !important;\">64% Cotton, 34% Polyester, 2% Spandex</span></div></div></div><div class=\"a-fixed-left-grid product-facts-detail\" style=\"margin: 0px 0px 18px; padding: 0px; font-size: 14px; position: relative; line-height: 16px; color: rgb(15, 17, 17); font-family: \"Amazon Ember\", Arial, sans-serif;\"><div class=\"a-fixed-left-grid-inner\" style=\"margin: 0px; padding: 0px 0px 0px 140px; position: relative;\"><div class=\"a-fixed-left-grid-col a-col-left\" style=\"margin: 0px 0px 0px -140px; padding: 0px; position: relative; overflow: visible; zoom: 1; min-height: 1px; width: 140px; float: left;\"><span class=\"a-color-base a-text-bold\" style=\"margin: 0px; padding: 0px; word-break: break-word; hyphens: auto; font-weight: 700 !important;\">Care Instructions</span></div><div class=\"a-fixed-left-grid-col a-col-right\" style=\"margin: 0px; padding: 0px 0px 0px 32.0739px; position: relative; overflow: visible; zoom: 1; min-height: 1px; width: 534.588px; float: left;\"><span class=\"a-color-secondary\" style=\"margin: 0px; padding: 0px; word-break: break-word; hyphens: auto; color: rgb(86, 89, 89) !important;\">Machine Wash</span></div></div></div><div class=\"a-fixed-left-grid product-facts-detail\" style=\"margin: 0px 0px 18px; padding: 0px; font-size: 14px; position: relative; line-height: 16px; color: rgb(15, 17, 17); font-family: \"Amazon Ember\", Arial, sans-serif;\"><div class=\"a-fixed-left-grid-inner\" style=\"margin: 0px; padding: 0px 0px 0px 140px; position: relative;\"><div class=\"a-fixed-left-grid-col a-col-left\" style=\"margin: 0px 0px 0px -140px; padding: 0px; position: relative; overflow: visible; zoom: 1; min-height: 1px; width: 140px; float: left;\"><span class=\"a-color-base a-text-bold\" style=\"margin: 0px; padding: 0px; word-break: break-word; hyphens: auto; font-weight: 700 !important;\">Origin</span></div><div class=\"a-fixed-left-grid-col a-col-right\" style=\"margin: 0px; padding: 0px 0px 0px 32.0739px; position: relative; overflow: visible; zoom: 1; min-height: 1px; width: 534.588px; float: left;\"><span class=\"a-color-secondary\" style=\"margin: 0px; padding: 0px; word-break: break-word; hyphens: auto; color: rgb(86, 89, 89) !important;\">Imported</span></div></div></div><div class=\"a-fixed-left-grid product-facts-detail\" style=\"margin: 0px 0px 18px; padding: 0px; font-size: 14px; position: relative; line-height: 16px; color: rgb(15, 17, 17); font-family: \"Amazon Ember\", Arial, sans-serif;\"><div class=\"a-fixed-left-grid-inner\" style=\"margin: 0px; padding: 0px 0px 0px 140px; position: relative;\"><div class=\"a-fixed-left-grid-col a-col-left\" style=\"margin: 0px 0px 0px -140px; padding: 0px; position: relative; overflow: visible; zoom: 1; min-height: 1px; width: 140px; float: left;\"><span class=\"a-color-base a-text-bold\" style=\"margin: 0px; padding: 0px; word-break: break-word; hyphens: auto; font-weight: 700 !important;\">Closure Type</span></div><div class=\"a-fixed-left-grid-col a-col-right\" style=\"margin: 0px; padding: 0px 0px 0px 32.0739px; position: relative; overflow: visible; zoom: 1; min-height: 1px; width: 534.588px; float: left;\"><span class=\"a-color-secondary\" style=\"margin: 0px; padding: 0px; word-break: break-word; hyphens: auto; color: rgb(86, 89, 89) !important;\">Button</span></div></div></div><div class=\"a-fixed-left-grid product-facts-detail\" style=\"margin: 0px 0px 18px; padding: 0px; font-size: 14px; position: relative; line-height: 16px; color: rgb(15, 17, 17); font-family: \"Amazon Ember\", Arial, sans-serif;\"><div class=\"a-fixed-left-grid-inner\" style=\"margin: 0px; padding: 0px 0px 0px 140px; position: relative;\"><div class=\"a-fixed-left-grid-col a-col-left\" style=\"margin: 0px 0px 0px -140px; padding: 0px; position: relative; overflow: visible; zoom: 1; min-height: 1px; width: 140px; float: left;\"><span class=\"a-color-base a-text-bold\" style=\"margin: 0px; padding: 0px; word-break: break-word; hyphens: auto; font-weight: 700 !important;\">Country of Origin</span></div><div class=\"a-fixed-left-grid-col a-col-right\" style=\"margin: 0px; padding: 0px 0px 0px 32.0739px; position: relative; overflow: visible; zoom: 1; min-height: 1px; width: 534.588px; float: left;\"><span class=\"a-color-secondary\" style=\"margin: 0px; padding: 0px; word-break: break-word; hyphens: auto; color: rgb(86, 89, 89) !important;\">China</span></div></div></div></h3>', '357', 800, 1000, 800, 3, 2, 1, 1, NULL, 1, 10, 0, 1, 1, '2022-11-16 10:37:51', '2022-11-16 10:37:51', NULL),
        (195, 'Baby shoes', 'baby-shoes', '100% Textile\r\nSynthetic sole\r\nBoys sneaker-style boots with hook and loop closure\r\nHigh-top styling\r\nHook and loop closure for easy on-and-off', '<ul class=\"a-unordered-list a-vertical a-spacing-mini\" style=\"margin-right: 0px; margin-bottom: 0px; margin-left: 18px; padding: 0px; list-style-type: none; font-size: 14px; color: rgb(15, 17, 17); font-family: &quot;Amazon Ember&quot;, Arial, sans-serif;\"><li style=\"margin: 0px; padding: 0px; list-style: disc; overflow-wrap: break-word;\"><span class=\"a-list-item\" style=\"margin: 0px; padding: 0px; overflow-wrap: break-word; display: block;\">100% Textile</span></li><li style=\"margin: 0px; padding: 0px; list-style: disc; overflow-wrap: break-word;\"><span class=\"a-list-item\" style=\"margin: 0px; padding: 0px; overflow-wrap: break-word; display: block;\">Synthetic sole</span></li><li style=\"margin: 0px; padding: 0px; list-style: disc; overflow-wrap: break-word;\"><span class=\"a-list-item\" style=\"margin: 0px; padding: 0px; overflow-wrap: break-word; display: block;\">Boy’s sneaker-style boots with hook and loop closure</span></li><li style=\"margin: 0px; padding: 0px; list-style: disc; overflow-wrap: break-word;\"><span class=\"a-list-item\" style=\"margin: 0px; padding: 0px; overflow-wrap: break-word; display: block;\">High-top styling</span></li><li style=\"margin: 0px; padding: 0px; list-style: disc; overflow-wrap: break-word;\"><span class=\"a-list-item\" style=\"margin: 0px; padding: 0px; overflow-wrap: break-word; display: block;\">Hook and loop closure for easy on-and-off</span></li></ul>', '374', 223, 200, 223, NULL, 2, 1, 1, NULL, 1, 10, 0, 1, 1, '2022-11-16 10:51:12', '2022-11-16 10:51:19', NULL),
        (196, 'Stylish color  Jersey', 'stylish-color-jersey', 'The Blackout Jersey will match with any dirt bike pant, because what doesnt match with black? It has a moisture-wicking main body construction to keep you comfortable while youre putting down laps on the track or miles on the local trail. Plus, it has a perforated mesh fabric, so there is plenty of airflow through this motocross jersey.', '<ul class=\"a-unordered-list a-vertical a-spacing-mini\" style=\"margin-right: 0px; margin-bottom: 0px; margin-left: 18px; padding: 0px; list-style-type: none; color: rgb(15, 17, 17); font-family: &quot;Amazon Ember&quot;, Arial, sans-serif; font-size: 14px;\"><li style=\"margin: 0px; padding: 0px; list-style: disc; overflow-wrap: break-word;\"><span class=\"a-list-item\" style=\"margin: 0px; padding: 0px; overflow-wrap: break-word; display: block;\">100% Polyester</span></li><li style=\"margin: 0px; padding: 0px; list-style: disc; overflow-wrap: break-word;\"><span class=\"a-list-item\" style=\"margin: 0px; padding: 0px; overflow-wrap: break-word; display: block;\">Imported</span></li><li style=\"margin: 0px; padding: 0px; list-style: disc; overflow-wrap: break-word;\"><span class=\"a-list-item\" style=\"margin: 0px; padding: 0px; overflow-wrap: break-word; display: block;\">Pull On closure</span></li><li style=\"margin: 0px; padding: 0px; list-style: disc; overflow-wrap: break-word;\"><span class=\"a-list-item\" style=\"margin: 0px; padding: 0px; overflow-wrap: break-word; display: block;\">Machine Wash</span></li><li style=\"margin: 0px; padding: 0px; list-style: disc; overflow-wrap: break-word;\"><span class=\"a-list-item\" style=\"margin: 0px; padding: 0px; overflow-wrap: break-word; display: block;\">Breathable crewneck jersey made for soccer</span></li><li style=\"margin: 0px; padding: 0px; list-style: disc; overflow-wrap: break-word;\"><span class=\"a-list-item\" style=\"margin: 0px; padding: 0px; overflow-wrap: break-word; display: block;\">Regular fit is wider at the body, with a straight silhouette</span></li><li style=\"margin: 0px; padding: 0px; list-style: disc; overflow-wrap: break-word;\"><span class=\"a-list-item\" style=\"margin: 0px; padding: 0px; overflow-wrap: break-word; display: block;\">Crewneck provides full coverage</span></li><li style=\"margin: 0px; padding: 0px; list-style: disc; overflow-wrap: break-word;\"><span class=\"a-list-item\" style=\"margin: 0px; padding: 0px; overflow-wrap: break-word; display: block;\">This product is made with recycled content as part of our ambition to end plastic waste</span></li></ul>', '377', 250, 190, 250, NULL, 7, 1, 1, NULL, 2, 10, 0, 1, 2, '2022-11-16 10:54:10', '2022-11-16 10:54:26', NULL),
        (197, 'High Heel Wedding Shoes', 'high-heel-wedding-shoes-1', 'No Import Fees Deposit and $25.56 Shipping to Bangladesh', '<h3 class=\"product-facts-title\" style=\"margin-right: 0px; margin-bottom: 0px; margin-left: 0px; padding: 4px 0px 14px; font-family: \" amazon=\"\" ember\",=\"\" arial,=\"\" sans-serif;=\"\" font-weight:=\"\" 700;=\"\" line-height:=\"\" 20px;=\"\" color:=\"\" rgb(15,=\"\" 17,=\"\" 17);=\"\" font-size:=\"\" 16px;=\"\" text-rendering:=\"\" optimizelegibility;\"=\"\"><ul class=\"a-unordered-list a-vertical a-spacing-small\" style=\"margin-right: 0px; margin-bottom: 0px; margin-left: 18px; padding: 0px; list-style-type: none; font-size: 14px; color: rgb(15, 17, 17); font-family: &quot;Amazon Ember&quot;, Arial, sans-serif;\"><li style=\"margin: 0px; padding: 0px; list-style: disc; overflow-wrap: break-word;\"><span class=\"a-list-item a-size-base a-color-secondary\" style=\"margin: 0px; padding: 0px; line-height: 20px !important;\">Heel Height approximately 3.0\" Platform measures approximately 0.25\"| True size to fit.</span></li></ul><ul class=\"a-unordered-list a-vertical a-spacing-small\" style=\"margin-right: 0px; margin-bottom: 0px; margin-left: 18px; padding: 0px; list-style-type: none; font-size: 14px; color: rgb(15, 17, 17); font-family: &quot;Amazon Ember&quot;, Arial, sans-serif;\"><li style=\"margin: 0px; padding: 0px; list-style: disc; overflow-wrap: break-word;\"><span class=\"a-list-item a-size-base a-color-secondary\" style=\"margin: 0px; padding: 0px; line-height: 20px !important;\">All your friends will be asking your advice when they see you with these sexy sandals! The open toe and strappy with sparkling rhinestone design front is eye-catching and alluring and will have envious stares on you all night long.</span></li></ul><ul class=\"a-unordered-list a-vertical a-spacing-small\" style=\"margin-right: 0px; margin-bottom: 0px; margin-left: 18px; padding: 0px; list-style-type: none; font-size: 14px; color: rgb(15, 17, 17); font-family: &quot;Amazon Ember&quot;, Arial, sans-serif;\"><li style=\"margin: 0px; padding: 0px; list-style: disc; overflow-wrap: break-word;\"><span class=\"a-list-item a-size-base a-color-secondary\" style=\"margin: 0px; padding: 0px; line-height: 20px !important;\">This pair is perfectly designed for steady steps, as it features a single, slim sole that ideally balances the heel height with the rest of the sleek shoe design.</span></li></ul><ul class=\"a-unordered-list a-vertical a-spacing-small\" style=\"margin-right: 0px; margin-bottom: 0px; margin-left: 18px; padding: 0px; list-style-type: none; font-size: 14px; color: rgb(15, 17, 17); font-family: &quot;Amazon Ember&quot;, Arial, sans-serif;\"><li style=\"margin: 0px; padding: 0px; list-style: disc; overflow-wrap: break-word;\"><span class=\"a-list-item a-size-base a-color-secondary\" style=\"margin: 0px; padding: 0px; line-height: 20px !important;\">This stunning pair of heels is ideal for weddings, parties and every other special occasion that calls for dressy, upscale shoes!</span></li></ul><ul class=\"a-unordered-list a-vertical a-spacing-small\" style=\"margin-right: 0px; margin-bottom: 0px; margin-left: 18px; padding: 0px; list-style-type: none; font-size: 14px; color: rgb(15, 17, 17); font-family: &quot;Amazon Ember&quot;, Arial, sans-serif;\"><li style=\"margin: 0px; padding: 0px; list-style: disc; overflow-wrap: break-word;\"><span class=\"a-list-item a-size-base a-color-secondary\" style=\"margin: 0px; padding: 0px; line-height: 20px !important;\">Featuring a slim straps that hugs your ankle for custom support and provides a comfort throughout wear. Your feet will not slip, turn or move out of place while wearing these gorgeous sandals!</span></li></ul><span style=\"margin: 0px; padding: 0px; font-size: 14px; color: rgb(51, 51, 51); font-family: &quot;segoe ui&quot;, Helvetica, &quot;droid sans&quot;, Arial, &quot;lucida grande&quot;, tahoma, verdana, arial, sans-serif;\">&nbsp;apples and other desserts.</span><br></h3><h3 class=\"product-facts-title\" style=\"margin-right: 0px; margin-bottom: 0px; margin-left: 0px; padding: 4px 0px 14px; font-family: \" amazon=\"\" ember\",=\"\" arial,=\"\" sans-serif;=\"\" font-weight:=\"\" 700;=\"\" line-height:=\"\" 20px;=\"\" color:=\"\" rgb(15,=\"\" 17,=\"\" 17);=\"\" font-size:=\"\" 16px;=\"\" text-rendering:=\"\" optimizelegibility;\"=\"\"><div class=\"a-fixed-left-grid product-facts-detail\" style=\"margin: 0px 0px 18px; padding: 0px; font-size: 14px; position: relative; line-height: 16px; color: rgb(15, 17, 17); font-family: \" amazon=\"\" ember\",=\"\" arial,=\"\" sans-serif;\"=\"\"></div></h3>', '375', 250, 240, 250, 2, 2, 1, 1, NULL, 1, 10, 0, 1, 1, '2022-11-16 11:24:22', '2022-11-16 11:25:58', '2022-11-16 11:25:58')");
    }

    private function seedProductCategory()
    {
        DB::statement("INSERT INTO `product_categories` (`id`, `product_id`, `category_id`) VALUES
        (151, 190, 8),
        (152, 191, 10),
        (153, 192, 10),
        (154, 193, 6),
        (155, 195, 8),
        (156, 196, 10),
        (157, 197, 8)");
    }

    private function seedProductSubCategories()
    {
        DB::statement("INSERT INTO `product_sub_categories` (`id`, `product_id`, `sub_category_id`) VALUES
        (150, 190, 15),
        (151, 191, 10),
        (152, 192, 10),
        (153, 193, 11),
        (154, 195, 16),
        (155, 196, 10),
        (156, 197, 15)");
    }

    private function seedProductChildCategories()
    {
        DB::statement("INSERT INTO `product_child_categories` (`id`, `product_id`, `child_category_id`) VALUES
        (550, 191, 13),
        (551, 191, 19),
        (554, 192, 13),
        (555, 192, 19),
        (557, 193, 15),
        (558, 190, 20),
        (560, 195, 17),
        (561, 196, 13),
        (562, 197, 20)");
    }

    private function seedProductTags()
    {
        DB::statement("INSERT INTO `product_tags` (`id`, `tag_name`, `product_id`) VALUES
        (738, 'tshirt', 191),
        (740, 'tshirt', 192),
        (742, 'jacket', 193),
        (743, 'jacket', 190),
        (745, 'baby shoe', 195),
        (746, 'jersy', 196),
        (747, 'jacket', 197)");
    }

    private function seedProductGalleries()
    {
        DB::statement("INSERT INTO `product_galleries` (`id`, `product_id`, `image_id`) VALUES
        (147, 191, 380),
        (148, 191, 379),
        (149, 191, 377),
        (153, 192, 382),
        (154, 192, 379),
        (155, 192, 372),
        (159, 193, 377),
        (160, 193, 368),
        (161, 193, 357),
        (162, 195, 362),
        (163, 196, 361)");
    }

    private function seedProductInventories()
    {
        DB::statement("INSERT INTO `product_inventories` (`id`, `product_id`, `sku`, `stock_count`, `sold_count`) VALUES
        (211, 190, 'phh4', 20, NULL),
        (212, 191, 'swr234-1', 100, NULL),
        (213, 192, 'srw12-1', 100, NULL),
        (214, 193, 'jck12-1', 50, NULL),
        (216, 195, 'bbs15', 20, NULL),
        (217, 196, 'jrs45', 45, NULL),
        (218, 197, 'phh4-1', 20, NULL)");
    }

    private function seedProductInventoryDetails()
    {
        DB::statement("INSERT INTO `product_inventory_details` (`id`, `product_inventory_id`, `product_id`, `color`, `size`, `hash`, `additional_price`, `add_cost`, `image`, `stock_count`, `sold_count`) VALUES
        (379, 216, 195, '1', '2', '', 2.00, 2.00, 362, 10, 0),
        (380, 216, 195, '5', '2', '', 3.00, 3.00, 354, 10, 0)");
    }

    private function seedProductUOM()
    {
        DB::statement("INSERT INTO `product_uom` (`id`, `product_id`, `unit_id`, `quantity`) VALUES
        (123, 190, 6, 1.00),
        (124, 191, 6, 1.00),
        (125, 192, 6, 1.00),
        (126, 193, 6, 1.00),
        (127, 195, 6, 1.00),
        (128, 196, 6, 1.00),
        (129, 197, 6, 1.00)");
    }

    private function seedProductCreatedBy()
    {
        DB::statement("INSERT INTO `product_created_by` (`id`, `product_id`, `created_by_id`, `guard_name`, `updated_by`, `updated_by_guard`, `deleted_by`, `deleted_by_guard`) VALUES
        (181, 190, 1, 'admin', 1, 'admin', NULL, NULL),
        (182, 191, 1, 'admin', 1, 'admin', NULL, NULL),
        (183, 192, 1, 'admin', NULL, NULL, NULL, NULL),
        (184, 193, 1, 'admin', NULL, NULL, NULL, NULL),
        (186, 195, 1, 'admin', 1, 'admin', NULL, NULL),
        (187, 196, 1, 'admin', 1, 'admin', NULL, NULL),
        (188, 197, 1, 'admin', NULL, NULL, NULL, NULL)");
    }

    private function seedProductDeliveryOption()
    {
        DB::statement("INSERT INTO `product_delivery_options` (`id`, `product_id`, `delivery_option_id`) VALUES
        (754, 191, 1),
        (755, 191, 2),
        (756, 191, 3),
        (760, 192, 1),
        (761, 192, 2),
        (762, 192, 3),
        (766, 193, 1),
        (767, 193, 2),
        (768, 193, 3),
        (769, 190, 1),
        (770, 190, 3),
        (774, 195, 1),
        (775, 195, 2),
        (776, 195, 3),
        (777, 197, 1),
        (778, 197, 3)");
    }

    private function seedProductReturnPolicies()
    {
        DB::statement("INSERT INTO `product_shipping_return_policies` (`id`, `product_id`, `shipping_return_description`, `created_at`, `updated_at`) VALUES
        (31, 190, '<p>Return in 7 Days is acceptable</p>', '2022-11-16 10:29:36', '2022-11-16 10:29:36'),
        (32, 191, '<p>Return in 7 Days is acceptable</p>', '2022-11-16 10:30:14', '2022-11-16 10:30:14'),
        (33, 192, '<p>Return in 7 Days is acceptable</p>', '2022-11-16 10:32:18', '2022-11-16 10:32:18'),
        (34, 193, '<p>Return in 7 Days is acceptable</p>', '2022-11-16 10:37:52', '2022-11-16 10:37:52'),
        (36, 195, NULL, '2022-11-16 10:51:12', '2022-11-16 10:51:12'),
        (37, 196, NULL, '2022-11-16 10:54:10', '2022-11-16 10:54:10'),
        (38, 197, '<p>Return in 7 Days is acceptable</p>', '2022-11-16 11:24:22', '2022-11-16 11:24:22')");
    }

    private function seedCampaign()
    {
        DB::statement("INSERT INTO `campaigns` (`id`, `title`, `subtitle`, `image`, `start_date`, `end_date`, `status`, `created_at`, `updated_at`, `admin_id`, `vendor_id`, `type`) VALUES
        (14, 'Winter Collection', 'Winter', 368, '2022-11-19 18:00:00', '2023-01-30 18:00:00', 'publish', '2022-11-16 11:01:00', '2022-11-16 11:12:03', 1, NULL, 'admin')");
    }

    private function seedCampaignProducts()
    {
        DB::statement("INSERT INTO `campaign_products` (`id`, `product_id`, `campaign_id`, `campaign_price`, `units_for_sale`, `start_date`, `end_date`, `created_at`, `updated_at`) VALUES
        (118, 191, 14, '479.70', 5, '2022-11-19 18:00:00', '2023-01-30 18:00:00', NULL, NULL),
        (119, 192, 14, '288.90', 5, '2022-11-19 18:00:00', '2023-01-30 18:00:00', NULL, NULL),
        (120, 196, 14, '171.00', 5, '2022-11-19 18:00:00', '2023-01-30 18:00:00', NULL, NULL),
        (121, 193, 14, '900.00', 5, '2022-11-19 18:00:00', '2023-01-30 18:00:00', NULL, NULL)");
    }
}