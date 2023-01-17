<?php

namespace Database\Seeders;

use App\Models\PaymentGateway;
use App\Models\Themes;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Session;

class DatabaseSeeder extends Seeder
{

    public function run()
    {
        // \App\Models\User::factory(10)->create();

//        $payment_gateway_markup = [
//            [
//                'name' => 'paypal',
//                'image' => 1,
//                'description' => 'if your currency is not available in paypal, it will convert you currency value to USD value based on your currency exchange rate.',
//                'status' => 1,
//                'test_mode' => 1,
//                'credentials' => json_encode([
//                        'sandbox_client_id'  => '',
//                        'sandbox_client_secret'  => '',
//                        'sandbox_app_id'  => '',
//                        'live_client_id'  => '',
//                        'live_client_secret'  => '',
//                        'live_app_id'  => ''
//                    ]
//                )
//            ],
//
//            [
//                'name' => 'paytm',
//                'image' => 1,
//                'description' => 'if your currency is not available in paytm, it will convert you currency value to INR value based on your currency exchange rate.',
//                'status' => 1,
//                'test_mode' => 1,
//                'credentials'=> json_encode([
//                    'merchant_key' => '',
//                    'merchant_mid' => '',
//                    'merchant_website' => '',
//                    'channel' => '',
//                    'industry_type'=> ''
//                ])
//            ],
//
//
//            [
//                'name' => 'stripe',
//                'image' => 1,
//                'description' => '',
//                'status' => 1,
//                'test_mode' => 1,
//                'credentials'=> json_encode([
//                    'public_key'=> '',
//                    'secret_key'=> ''
//                ])
//
//            ],
//
//
//            [
//                'name' => 'razorpay',
//                'image' => 1,
//                'description' => 'if your currency is not available in Razorpay, it will convert you currency value to INR value based on your currency exchange rate.',
//                'status' => 1,
//                'test_mode' => 1,
//                'credentials'=> json_encode([
//                    'api_key'=>'',
//                    'api_secret'=> ''
//                ])
//            ],
//
//
//            [
//                'name' => 'paystack',
//                'image' => 1,
//                'description' => 'if your currency is not available in Paystack, it will convert you currency value to NGN value based on your currency exchange rate.',
//                'status' => 1,
//                'test_mode' => 1,
//                'credentials'=> json_encode([
//                    'public_key'=>'',
//                    'secret_key'=>'',
//                    'merchant_email'=>''
//                ])
//            ],
//
//
//            [
//                'name' => 'mollie',
//                'image' => 1,
//                'description' => 'if your currency is not available in mollie, it will convert you currency value to USD value based on your currency exchange rate.',
//                'status' => 1,
//                'test_mode' => 1,
//                'credentials'=> json_encode(['public_key'=>''])
//
//            ],
//
//            [
//                'name' => 'flutterwave',
//                'image' => 1,
//                'description' => 'if your currency is not available in flutterwave, it will convert you currency value to USD value based on your currency exchange rate.',
//                'status' => 1,
//                'test_mode' => 1,
//                'credentials'=> json_encode([
//                    'public_key' => '',
//                    'secret_key' => '',
//                    'secret_hash' => ''
//                ])
//            ],
//
//
//            [
//                'name' => 'midtrans',
//                'image' => 1,
//                'description' => '',
//                'status' => 1,
//                'test_mode' => 1,
//                'credentials'=> json_encode([
//                    'merchant_id' => '',
//                    'server_key' => '',
//                    'client_key' => ''
//                ])
//            ],
//
//            [
//                'name' => 'payfast',
//                'image' => 1,
//                'description' => '',
//                'status' => 1,
//                'test_mode' => 1,
//                'credentials'=> json_encode([
//                    'merchant_id' => '',
//                    'merchant_key' => '',
//                    'passphrase' => '',
//                    'itn_url' => ''
//                ])
//            ],
//
//
//            [
//                'name' => 'cashfree',
//                'image' => 1,
//                'description' => '',
//                'status' => 1,
//                'test_mode' => 1,
//                'credentials'=> json_encode([
//                    'app_id' => '',
//                    'secret_key' => ''
//                ])
//            ],
//
//            [
//                'name' => 'instamojo',
//                'image' => 1,
//                'description' => '',
//                'status' => 1,
//                'test_mode' => 1,
//                'credentials'=> json_encode([
//                    'client_id' => '',
//                    'client_secret' => '',
//                    'username' => '',
//                    'password' => ''
//                ])
//            ],
//
//
//            [
//                'name' => 'marcadopago',
//                'image' => 1,
//                'description' => '',
//                'status' => 1,
//                'test_mode' => 1,
//                'credentials'=> json_encode([
//                    'client_id' => '',
//                    'client_secret' => ''
//                ])
//            ],
//
//
//            [
//                'name' => 'zitopay',
//                'image' => 1,
//                'description' => '',
//                'status' => 1,
//                'test_mode' => 1,
//                'credentials' => json_encode([
//                    'username' => '',
//                ])
//            ],
//
//
//            [
//                'name' => 'squareup',
//                'image' => 1,
//                'description' => '',
//                'status' => 1,
//                'test_mode' => 1,
//                'credentials' => json_encode([
//                    'location_id' => '',
//                    'access_token' => '',
//                ])
//            ],
//
//
//            [
//                'name' => 'cinetpay',
//                'image' => 1,
//                'description' => '',
//                'status' => 1,
//                'test_mode' => 1,
//                'credentials' => json_encode([
//                    'apiKey' => '',
//                    'site_id' => ''
//                ])
//            ],
//
//
//            [
//                'name' => 'paytabs',
//                'image' => 1,
//                'description' => '',
//                'status' => 1,
//                'test_mode' => 1,
//                'credentials' => json_encode([
//                    'profile_id' => '',
//                    'region' => '',
//                    'server_key' => ''
//                ])
//            ],
//
//
//            [
//                'name' => 'billplz',
//                'image' => 1,
//                'description' => '',
//                'status' => 1,
//                'test_mode' => 1,
//                'credentials' => json_encode([
//                    'key' => '',
//                    'version' => '',
//                    'x_signature' => '',
//                    'collection_name' => ''
//                ])
//            ],
//
//
//
//            [
//                'name' => 'manual_payment',
//                'image' => 1,
//                'description' => '',
//                'status' => 1,
//                'test_mode' => 1,
//                'credentials'=> json_encode([
//                    'name' => '',
//                    'description'=> ''
//                ])
//            ]
//
//            [
//                'name' => 'toyyibpay',
//                'image' => 1,
//                'description' => '',
//                'status' => 1,
//                'test_mode' => 1,
//                'credentials'=> json_encode([
//                    'client_secret' => '',
//                    'category_code'=> ''
//                ])
//            ]
//        ];
//
//        foreach ($payment_gateway_markup as $payment_gate) {
//            PaymentGateway::create($payment_gate);
//        }

//        $permissions = [
//           'page-list',
//           'page-create',
//           'page-edit',
//           'page-delete',
//
//           'price-plan-list',
//           'price-plan-create',
//           'price-plan-edit',
//           'price-plan-delete',
//
//          'package-order-all-order',
//          'package-order-pending-order',
//          'package-order-progress-order',
//          'package-order-complete-order',
//          'package-order-success-order-page',
//          'package-order-cancel-order-page',
//          'package-order-order-page-manage',
//          'package-order-order-report',
//          'package-order-payment-logs',
//          'package-order-payment-report',
//
//          'testimonial-list',
//          'testimonial-create',
//          'testimonial-edit',
//          'testimonial-delete',
//
//            'brand-list',
//            'brand-create',
//            'brand-edit',
//            'brand-delete',
//
//            'blog-category-list',
//            'blog-category-create',
//            'blog-category-edit',
//            'blog-category-delete',
//
//            'blog-list',
//            'blog-create',
//            'blog-edit',
//            'blog-delete',
//
//            'form-builder',
//            'widget-builder',
//
//            'general-settings-page-settings',
//            'general-settings-global-navbar-settings',
//            'general-settings-global-footer-settings',
//            'general-settings-site-identity',
//            'general-settings-basic-settings',
//            'general-settings-color-settings',
//            'general-settings-typography-settings',
//            'general-settings-seo-settings',
//            'general-settings-payment-settings',
//            'general-settings-third-party-script-settings',
//            'general-settings-smtp-settings',
//            'general-settings-custom-css-settings',
//            'general-settings-custom-js-settings',
//            'general-settings-database-upgrade-settings',
//            'general-settings-cache-clear-settings',
//            'general-settings-license-settings',
//
//            'language-list',
//            'language-create',
//            'language-edit',
//            'language-delete',
//        ];
//
//        foreach ($permissions as $permission){
//            \Spatie\Permission\Models\Permission::where(['name' => $permission])->delete();
//            \Spatie\Permission\Models\Permission::create(['name' => $permission,'guard_name' => 'admin']);
//        }

//        $themes = [
//            [
//                'title' => 'Luxury Perfume Store',
//                'slug' => \Str::slug('theme-1'),
//                'description' => 'This theme has a best design for perfume store'
//            ],
//            [
//                'title' => 'Clothing Store',
//                'slug' => \Str::slug('theme-2'),
//                'description' => 'This theme has a best design for clothing store'
//            ],
//            [
//                'title' => 'Fashion Shop',
//                'slug' => \Str::slug('theme-3'),
//                'description' => 'This theme has a best design for fashion shop'
//            ]
//        ];
//
//        foreach ($themes as $theme)
//        {
//            Themes::create($theme);
//        }
    }
}
