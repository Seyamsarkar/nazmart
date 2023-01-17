<?php

namespace Database\Seeders\Tenant;

use App\Helpers\ImageDataSeedingHelper;
use App\Mail\TenantCredentialMail;
use App\Models\Admin;
use App\Models\Language;
use App\Models\Menu;
use App\Models\TopbarInfo;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class GeneralData extends Seeder
{
    public function run()
    {
        $this->insertStaticOptionData();
        $this->seed_topbar_info();
        $this->central_data();
    }

    private function insertStaticOptionData()
    {
        DB::statement("INSERT INTO `static_options` (`id`, `option_name`, `option_value`, `created_at`, `updated_at`) VALUES
            (1,'site_title','HexFashion','2022-08-11 01:14:21','2023-01-02 17:34:09'),
            (3,'site_tag_line','A modern fashion website','2022-08-11 01:14:21','2023-01-02 17:34:09'),
            (5,'home_one_header_button_text','Join With Us','2022-08-11 01:14:21','2022-08-11 01:14:21'),
            (7,'language_selector_status','on','2022-08-11 01:14:21','2022-08-11 01:14:21'),
            (8,'home_page','1','2022-08-11 01:14:21','2022-08-11 01:14:21'),
            (10,'global_footer_variant','01','2022-08-11 01:14:21','2022-11-10 14:29:08'),
            (11,'order_form','02','2022-08-11 01:14:21','2022-08-11 01:14:21'),
            (12,'site_logo','324','2022-08-11 01:14:21','2022-10-23 07:04:21'),
            (13,'site_white_logo','324','2022-08-11 01:14:21','2022-10-23 07:04:21'),
            (14,'site_favicon','322','2022-08-11 01:14:21','2022-10-17 07:08:08'),
            (15,'site_footer_copyright_text','{copy} {year} Copyright All Right Reserved by HexFashion','2022-08-11 01:45:38','2023-01-02 17:34:09'),
            (17,'dark_mode_for_admin_panel',NULL,'2022-08-11 01:45:38','2022-08-11 01:45:53'),
            (18,'maintenance_mode',NULL,'2022-08-11 01:45:38','2022-08-11 01:45:38'),
            (19,'backend_preloader',NULL,'2022-08-11 01:45:38','2022-08-11 01:45:38'),
            (20,'user_email_verify_status',NULL,'2022-08-11 01:45:38','2022-08-11 01:45:38'),
            (21,'guest_order_system_status',NULL,'2022-08-11 01:45:38','2022-08-11 01:45:38'),
            (22,'timezone','Asia/Dhaka','2022-08-11 01:45:38','2022-09-12 00:48:23'),
            (23,'main_color_one_theme_one','rgb(255, 128, 93)','2022-08-11 02:23:08','2022-11-12 10:18:07'),
            (24,'main_color_two_theme_one','#ff805d','2022-08-11 02:23:08','2022-08-11 02:23:08'),
            (25,'main_color_three_theme_one','#599a8d','2022-08-11 02:23:08','2022-08-11 02:23:08'),
            (26,'main_color_four_theme_one','#1e88e5','2022-08-11 02:23:08','2022-08-11 02:23:08'),
            (27,'secondary_color_theme_one','#F7A3A8','2022-08-11 02:23:08','2022-08-11 02:23:08'),
            (28,'secondary_color_two_theme_one','#ffdcd2','2022-08-11 02:23:08','2022-08-11 02:23:08'),
            (29,'section_bg_1_theme_one','#FFFBFB','2022-08-11 02:23:08','2022-08-11 02:23:08'),
            (30,'section_bg_2_theme_one','#FFF6EE','2022-08-11 02:23:08','2022-08-11 02:23:08'),
            (31,'section_bg_3_theme_one','#F4F8FB','2022-08-11 02:23:08','2022-08-11 02:23:08'),
            (32,'section_bg_4_theme_one','#F2F3FB','2022-08-11 02:23:08','2022-08-11 02:23:08'),
            (33,'section_bg_5_theme_one','#F9F5F2','2022-08-11 02:23:08','2022-08-11 02:23:08'),
            (34,'section_bg_6_theme_one','#E5EFF8','2022-08-11 02:23:08','2022-08-11 02:23:08'),
            (35,'heading_color_theme_one','#333333','2022-08-11 02:23:08','2022-08-11 02:23:08'),
            (36,'body_color_theme_one','#666666','2022-08-11 02:23:08','2022-08-11 02:23:08'),
            (37,'light_color_theme_one','#666666','2022-08-11 02:23:08','2022-08-11 02:23:08'),
            (38,'extra_light_color_theme_one','#888888','2022-08-11 02:23:08','2022-08-11 02:23:08'),
            (39,'review_color_theme_one','#FABE50','2022-08-11 02:23:08','2022-08-11 02:23:08'),
            (40,'feedback_bg_item_theme_one','rgb(255, 246, 238)','2022-08-11 02:23:08','2022-11-13 06:14:29'),
            (41,'new_color_theme_one','#5AB27E','2022-08-11 02:23:08','2022-08-11 02:23:08'),
            (42,'main_color_one_theme_two','rgb(255, 128, 93)','2022-08-11 02:23:08','2022-11-12 10:18:20'),
            (43,'main_color_two_theme_two','rgb(255, 128, 93)','2022-08-11 02:23:08','2022-11-10 14:27:10'),
            (44,'main_color_three_theme_two','rgb(89, 154, 141)','2022-08-11 02:23:08','2022-11-10 14:27:10'),
            (45,'main_color_four_theme_two','#1e88e5','2022-08-11 02:23:08','2022-08-11 02:23:08'),
            (46,'secondary_color_theme_two','#F7A3A8','2022-08-11 02:23:08','2022-08-11 02:23:08'),
            (47,'secondary_color_two_theme_two','#ffdcd2','2022-08-11 02:23:08','2022-08-11 02:23:08'),
            (48,'section_bg_1_theme_two','#FFFBFB','2022-08-11 02:23:08','2022-08-11 02:23:08'),
            (49,'section_bg_2_theme_two','rgb(255, 246, 238)','2022-08-11 02:23:08','2022-11-12 07:07:39'),
            (50,'section_bg_3_theme_two','#F4F8FB','2022-08-11 02:23:08','2022-08-11 02:23:08'),
            (51,'section_bg_4_theme_two','#F2F3FB','2022-08-11 02:23:08','2022-08-11 02:23:08'),
            (52,'section_bg_5_theme_two','#F9F5F2','2022-08-11 02:23:08','2022-08-11 02:23:08'),
            (53,'section_bg_6_theme_two','#E5EFF8','2022-08-11 02:23:08','2022-08-11 02:23:08'),
            (54,'heading_color_theme_two','#333333','2022-08-11 02:23:08','2022-08-11 02:23:08'),
            (55,'body_color_theme_two','#666666','2022-08-11 02:23:08','2022-08-11 02:23:08'),
            (56,'light_color_theme_two','#666666','2022-08-11 02:23:08','2022-08-11 02:23:08'),
            (57,'extra_light_color_theme_two','#888888','2022-08-11 02:23:08','2022-08-11 02:23:08'),
            (58,'review_color_theme_two','#FABE50','2022-08-11 02:23:08','2022-08-11 02:23:08'),
            (59,'feedback_bg_item_theme_two','rgb(255, 246, 238)','2022-08-11 02:23:08','2022-11-13 06:14:29'),
            (60,'new_color_theme_two','#5AB27E','2022-08-11 02:23:08','2022-08-11 02:23:08'),
            (61,'main_color_one_theme_three','rgb(240, 93, 255)','2022-08-11 02:23:08','2022-09-20 11:09:28'),
            (62,'main_color_two_theme_three','rgb(93, 130, 255)','2022-08-11 02:23:08','2022-09-20 11:09:28'),
            (63,'main_color_three_theme_three','rgb(93, 0, 255)','2022-08-11 02:23:08','2022-11-10 14:28:07'),
            (64,'main_color_four_theme_three','#1e88e5','2022-08-11 02:23:08','2022-08-11 02:23:08'),
            (65,'secondary_color_theme_three','#F7A3A8','2022-08-11 02:23:08','2022-08-11 02:23:08'),
            (66,'secondary_color_two_theme_three','#ffdcd2','2022-08-11 02:23:08','2022-08-11 02:23:08'),
            (67,'section_bg_1_theme_three','#FFFBFB','2022-08-11 02:23:08','2022-08-11 02:23:08'),
            (68,'section_bg_2_theme_three','rgb(249, 232, 255)','2022-08-11 02:23:08','2022-09-20 11:10:34'),
            (69,'section_bg_3_theme_three','#F4F8FB','2022-08-11 02:23:08','2022-08-11 02:23:08'),
            (70,'section_bg_4_theme_three','#F2F3FB','2022-08-11 02:23:08','2022-08-11 02:23:08'),
            (71,'section_bg_5_theme_three','#F9F5F2','2022-08-11 02:23:08','2022-08-11 02:23:08'),
            (72,'section_bg_6_theme_three','#E5EFF8','2022-08-11 02:23:08','2022-08-11 02:23:08'),
            (73,'heading_color_theme_three','#333333','2022-08-11 02:23:08','2022-08-11 02:23:08'),
            (74,'body_color_theme_three','#666666','2022-08-11 02:23:08','2022-08-11 02:23:08'),
            (75,'light_color_theme_three','#666666','2022-08-11 02:23:08','2022-08-11 02:23:08'),
            (76,'extra_light_color_theme_three','#888888','2022-08-11 02:23:08','2022-08-11 02:23:08'),
            (77,'review_color_theme_three','#FABE50','2022-08-11 02:23:08','2022-08-11 02:23:08'),
            (78,'feedback_bg_item_theme_three','rgb(255, 246, 238)','2022-08-11 02:23:08','2022-11-13 06:14:29'),
            (79,'new_color_theme_three','#5AB27E','2022-08-11 02:23:08','2022-08-11 02:23:08'),
            (80,'body_font_family_theme_one','Jost','2022-08-11 04:43:05','2022-11-20 12:55:32'),
            (81,'heading_font_family_theme_one','Jost','2022-08-11 04:43:05','2022-11-20 12:55:32'),
            (82,'heading_font_theme_one','on','2022-08-11 04:43:05','2022-08-11 05:24:53'),
            (83,'body_font_family_theme_two','Jost','2022-08-11 04:43:05','2022-11-21 17:41:48'),
            (84,'heading_font_family_theme_two','Jost','2022-08-11 04:43:05','2022-11-21 17:41:48'),
            (85,'heading_font_theme_two','on','2022-08-11 04:43:05','2022-11-21 17:41:48'),
            (86,'body_font_family_theme_three','Arima Madurai','2022-08-11 04:43:05','2022-08-11 04:43:05'),
            (87,'heading_font_family_theme_three','Gentium Basic','2022-08-11 04:43:05','2022-08-11 04:43:05'),
            (88,'heading_font_theme_three','on','2022-08-11 04:43:05','2022-08-11 05:26:34'),
            (89,'body_font_variant_theme_one','a:8:{i:0;s:5:\"0,200\";i:1;s:5:\"0,300\";i:2;s:5:\"0,400\";i:3;s:5:\"0,500\";i:4;s:5:\"0,600\";i:5;s:5:\"0,700\";i:6;s:5:\"0,800\";i:7;s:5:\"0,900\";}','2022-08-11 04:43:05','2022-11-29 16:14:19'),
            (90,'heading_font_variant_theme_one','a:16:{i:0;s:5:\"0,200\";i:1;s:5:\"0,300\";i:2;s:5:\"0,400\";i:3;s:5:\"0,500\";i:4;s:5:\"0,600\";i:5;s:5:\"0,700\";i:6;s:5:\"0,800\";i:7;s:5:\"0,900\";i:8;s:5:\"1,200\";i:9;s:5:\"1,300\";i:10;s:5:\"1,400\";i:11;s:5:\"1,500\";i:12;s:5:\"1,600\";i:13;s:5:\"1,700\";i:14;s:5:\"1,800\";i:15;s:5:\"1,900\";}','2022-08-11 04:43:05','2022-11-29 16:14:19'),
            (91,'body_font_variant_theme_two','a:8:{i:0;s:5:\"0,200\";i:1;s:5:\"0,300\";i:2;s:5:\"0,400\";i:3;s:5:\"0,500\";i:4;s:5:\"0,600\";i:5;s:5:\"0,700\";i:6;s:5:\"0,800\";i:7;s:5:\"0,900\";}','2022-08-11 04:43:05','2022-11-29 16:14:19'),
            (92,'heading_font_variant_theme_two','a:8:{i:0;s:5:\"0,200\";i:1;s:5:\"0,300\";i:2;s:5:\"0,400\";i:3;s:5:\"0,500\";i:4;s:5:\"0,600\";i:5;s:5:\"0,700\";i:6;s:5:\"0,800\";i:7;s:5:\"0,900\";}','2022-08-11 04:43:05','2022-11-29 16:14:19'),
            (93,'body_font_variant_theme_three','a:2:{i:0;s:5:\"0,200\";i:1;s:5:\"0,300\";}','2022-08-11 04:43:05','2022-08-11 04:43:05'),
            (94,'heading_font_variant_theme_three','a:2:{i:0;s:5:\"0,400\";i:1;s:5:\"1,700\";}','2022-08-11 04:43:05','2022-08-11 04:43:05'),
            (95,'category_page_item_show','9','2022-08-17 00:22:46','2022-08-22 00:53:40'),
            (96,'tag_page_item_show','9','2022-08-17 00:22:46','2022-08-22 00:53:40'),
            (97,'search_page_item_show','9','2022-08-17 00:22:46','2022-08-22 00:53:40'),
            (98,'blog_avater_image','52','2022-08-17 00:22:46','2022-08-17 00:22:46'),
            (99,'pricing_plan',NULL,'2022-08-21 23:33:07','2022-08-22 00:08:33'),
            (100,'blog_page','5','2022-08-21 23:33:07','2022-11-16 05:14:54'),
            (101,'blogs_page_item_show','9','2022-08-22 00:53:40','2022-08-22 00:53:40'),
            (102,'site_global_currency','USD','2022-09-04 05:39:22','2022-09-04 05:39:22'),
            (103,'site_global_payment_gateway',NULL,'2022-09-04 05:39:22','2022-09-04 05:39:22'),
            (104,'site_usd_to_ngn_exchange_rate',NULL,'2022-09-04 05:39:22','2022-09-04 05:39:22'),
            (105,'site_euro_to_ngn_exchange_rate',NULL,'2022-09-04 05:39:22','2022-09-04 05:39:22'),
            (106,'site_currency_symbol_position','left','2022-09-04 05:39:22','2022-09-04 05:39:22'),
            (107,'site_default_payment_gateway','paypal','2022-09-04 05:39:22','2022-09-04 05:39:22'),
            (108,'site__to_idr_exchange_rate',NULL,'2022-09-04 05:39:22','2022-09-04 05:39:22'),
            (109,'site__to_inr_exchange_rate',NULL,'2022-09-04 05:39:22','2022-09-04 05:39:22'),
            (110,'site__to_ngn_exchange_rate',NULL,'2022-09-04 05:39:22','2022-09-04 05:39:22'),
            (111,'site__to_zar_exchange_rate',NULL,'2022-09-04 05:39:22','2022-09-04 05:39:22'),
            (112,'site__to_brl_exchange_rate',NULL,'2022-09-04 05:39:22','2022-09-04 05:39:22'),
            (113,'shop_page','4','2022-09-12 12:31:06','2022-11-16 05:14:54'),
            (114,'site_usd_to_idr_exchange_rate',NULL,'2022-10-12 05:57:47','2022-10-12 05:57:47'),
            (115,'site_usd_to_inr_exchange_rate',NULL,'2022-10-12 05:57:47','2022-10-12 05:57:47'),
            (116,'site_usd_to_zar_exchange_rate',NULL,'2022-10-12 05:57:47','2022-10-12 05:57:47'),
            (117,'site_usd_to_brl_exchange_rate',NULL,'2022-10-12 05:57:47','2022-10-12 05:57:47'),
            (119,'site_order_success_page_en_US_title','sdasd asde asd','2022-10-26 12:45:18','2022-10-26 12:45:18'),
            (120,'site_order_success_page_en_US_description','as das dasd asd asd','2022-10-26 12:45:18','2022-10-26 12:45:18'),
            (121,'site_order_success_page_ar_title',NULL,'2022-10-26 12:45:18','2022-10-26 12:45:18'),
            (122,'site_order_success_page_ar_description',NULL,'2022-10-26 12:45:18','2022-10-26 12:45:18'),
            (123,'site_order_cancel_page_en_US_title',NULL,'2022-10-26 12:47:05','2022-10-26 12:53:43'),
            (124,'site_order_cancel_page_en_US_subtitle',NULL,'2022-10-26 12:47:05','2022-10-27 04:58:45'),
            (125,'site_order_cancel_page_en_US_description',NULL,'2022-10-26 12:47:05','2022-10-27 04:58:45'),
            (126,'site_order_cancel_page_ar_title',NULL,'2022-10-26 12:47:05','2022-10-26 12:47:05'),
            (127,'site_order_cancel_page_ar_subtitle',NULL,'2022-10-26 12:47:05','2022-10-26 12:47:05'),
            (128,'site_order_cancel_page_ar_description',NULL,'2022-10-26 12:47:05','2022-10-26 12:47:05'),
            (130,'order_receiving_email','admin@gmail.com','2022-10-27 07:20:10','2022-10-27 07:20:10'),
            (131,'tenant_site_global_email','suzon@gmail.com','2022-10-27 07:20:22','2022-10-27 07:20:22'),
            (132,'stock_threshold_amount','5','2022-10-31 12:37:04','2022-10-31 13:37:03'),
            (133,'stock_warning_message','Following products stock are running low:','2022-11-01 06:31:26','2022-11-01 06:32:18'),
            (134,'track_order','7','2023-01-02 17:33:01','2023-01-02 17:33:01'),
            (135,'breadcrumb_bg_theme_one','rgb(255, 246, 238)','2023-01-02 17:34:20','2023-01-02 17:45:51'),
            (136,'breadcrumb_bg_theme_two','#E5EFF8','2023-01-02 17:34:20','2023-01-02 17:34:20'),
            (137,'breadcrumb_bg_theme_three','#E5EFF8','2023-01-02 17:34:20','2023-01-02 17:34:20'),
            (138,'blog_avatar_image','343','2023-01-03 17:51:46','2023-01-03 17:51:57')");
    }

    private function seed_topbar_info()
    {
        DB::statement("INSERT INTO `topbar_infos` (`id`, `icon`, `url`, `created_at`, `updated_at`) VALUES
        (1, 'lab la-twitter', '#', '2022-08-11 01:14:21', '2022-08-11 01:14:21'),
        (2, 'lab la-pinterest-p', '#', '2022-08-11 01:14:21', '2022-08-11 01:14:21'),
        (3, 'las la-user', '#', '2022-08-11 01:14:21', '2022-08-11 01:14:21'),
        (4, 'lab la-facebook-f', '#', '2022-08-11 01:14:21', '2022-08-11 01:14:21')");
    }

    private function central_data()
    {
        update_static_option_central('get_script_version', '1.0.0');
    }
}
