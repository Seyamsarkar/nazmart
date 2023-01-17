<?php

declare(strict_types=1);

use App\Http\Controllers\Tenant\Frontend\UserDashboardController;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use App\Http\Controllers\Tenant\Frontend\ShopCreationController;
use App\Http\Controllers\Tenant\Frontend\TenantFrontendController;
use App\Http\Controllers\Tenant\Frontend\CheckoutPaymentController;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/


Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
    'tenant_glvar',
    'maintenance_mode',
])->group(function () {

    Route::middleware('package_expire')->controller(\App\Http\Controllers\Tenant\Frontend\TenantFrontendController::class)->group(function () {
        Route::get('/', 'homepage')->name('tenant.frontend.homepage');
        Route::get('/lang-change','lang_change')->name('tenant.frontend.langchange');
    });


    Route::controller(\App\Http\Controllers\Landlord\Frontend\FrontendFormController::class)->prefix('tenant')->group(function () {
        Route::post('submit-custom-form', 'custom_form_builder_message')->name('tenant.frontend.form.builder.custom.submit');
    });

    /* tenant admin login */
    Route::middleware('package_expire')->controller(\App\Http\Controllers\Landlord\Admin\Auth\AdminLoginController::class)->prefix('admin')->group(function (){
        Route::get('/','login_form')->name('tenant.admin.login');
        Route::post('/','login_admin');
        Route::post('/logout','logout_admin')->name('tenant.admin.logout');
        Route::get('/restricted', 'restricted')->name('tenant.admin.restricted');

        Route::get('/forget-password','showUserForgetPasswordForm')->name('tenant.admin.forget.password');
        Route::get('/reset-password/{user}/{token}','showUserResetPasswordForm')->name('tenant.admin.reset.password');
        Route::post('/reset-password','UserResetPassword')->name('tenant.admin.reset.password.change');
        Route::post('/forget-password','sendUserForgetPasswordMail');
    });

    Route::get('/search', [TenantFrontendController::class, 'topbar_search'])->name('tenant.search.ajax');
    Route::get('/campaign/{id}', [TenantFrontendController::class, 'campaign'])->name('tenant.campaign.index');

    Route::prefix('shop')->as('tenant.')->group(function (){
        Route::get('/search', [TenantFrontendController::class, 'shop_page'])->name('shop');
        Route::get('/product/search', [TenantFrontendController::class, 'shop_search'])->name('shop.search');
        Route::get('/product/quick-view', [TenantFrontendController::class, 'product_quick_view'])->name('shop.quick.view');
        Route::get('/product/{slug}', [TenantFrontendController::class, 'product_details'])->name('shop.product.details'); // Product Details


        Route::get('/type/{category_type?}/{slug}', [TenantFrontendController::class, 'category_products'])->name('shop.category.products'); // Product Category / Subcategory / ChildCategory

        Route::post('/product/review', [TenantFrontendController::class, 'product_review'])->name('shop.product.review'); // Product Review
        Route::get('/product/review/more', [TenantFrontendController::class, 'render_reviews'])->name('shop.product.review.more.ajax'); // Product Review Ajax

        Route::post('/product/cart/add', [TenantFrontendController::class, 'add_to_cart'])->name('shop.product.add.to.cart.ajax'); // Shop to Add to Cart
        Route::post('/product/wishlist/add', [TenantFrontendController::class, 'add_to_wishlist'])->name('shop.product.add.to.wishlist.ajax'); // Shop to Add to Cart
        Route::post('/product/compare/add', [TenantFrontendController::class, 'add_to_compare'])->name('shop.product.add.to.compare.ajax'); // Shop to Add to Cart
        Route::get('/cart', [TenantFrontendController::class, 'cart_page'])->name('shop.cart');
        Route::get('/wishlist/page', [TenantFrontendController::class, 'wishlist_page'])->name('shop.wishlist.page');
        Route::get('/cart/update', [TenantFrontendController::class, 'cart_update_ajax'])->name('shop.cart.update.ajax');
        Route::get('/cart/clear', [TenantFrontendController::class, 'cart_clear_ajax'])->name('shop.cart.clear.ajax');
        Route::get('/cart/product/delete', [TenantFrontendController::class, 'cart_remove_product_ajax'])->name('shop.cart.remove.product.ajax');
        Route::get('/wishlist/product/delete', [TenantFrontendController::class, 'wishlist_remove_product_ajax'])->name('shop.wishlist.remove.product.ajax');
        Route::get('/wishlist/product/move', [TenantFrontendController::class, 'wishlist_move_product_ajax'])->name('shop.wishlist.move.product.ajax');

        Route::post('/product/buy/add', [TenantFrontendController::class, 'buy_now'])->name('shop.product.buy.now.ajax'); // Shop to Add to Cart

        Route::get('/checkout', [CheckoutPaymentController::class, 'checkout_page'])->name('shop.checkout');
        Route::post('/checkout', [CheckoutPaymentController::class, 'checkout'])->name('shop.checkout.final');

        Route::get('/checkout/get-state', [TenantFrontendController::class, 'get_state_ajax'])->name('shop.checkout.state.ajax');
        Route::get('/checkout/shipping-tax-data', [TenantFrontendController::class, 'sync_product_total'])->name('shop.checkout.sync-product-total.ajax');
        Route::get('/checkout/shipping-method-data', [TenantFrontendController::class, 'sync_product_total_wth_shipping_method'])->name('shop.checkout.sync-product-shipping.ajax');

        Route::get('/checkout/coupon', [TenantFrontendController::class, 'sync_product_coupon'])->name('shop.checkout.sync-product-coupon.ajax');

        Route::get('/wishlist', [TenantFrontendController::class, 'wishlist_product'])->name('shop.wishlist.product');

        Route::get('/compare/items', [TenantFrontendController::class, 'compare_product'])->name('shop.compare.product');
        Route::get('/compare', [TenantFrontendController::class, 'compare_product_page'])->name('shop.compare.product.page');
        Route::get('/compare/remove', [TenantFrontendController::class, 'compare_product_remove'])->name('shop.compare.product.remove');

        Route::get('/order-track', [TenantFrontendController::class, 'order_track'])->name('shop.order.track');
        Route::post('/order-track', [TenantFrontendController::class, 'order_track_post']);

        // Payment IPN
        Route::prefix("/")->as("user.frontend.")->group(function (){
            Route::controller(CheckoutPaymentController::class)->group(function (){
                Route::post('/paytm-ipn', 'paytm_ipn')->name('paytm.ipn');
                Route::get('/mollie-ipn', 'mollie_ipn')->name('mollie.ipn');
                Route::get('/stripe-ipn', 'stripe_ipn')->name('stripe.ipn');
                Route::post('/razorpay-ipn', 'razorpay_ipn')->name('razorpay.ipn');
                Route::post('/payfast-ipn', 'payfast_ipn')->name('payfast.ipn');
                Route::get('/flutterwave/ipn', 'flutterwave_ipn')->name('flutterwave.ipn');
                Route::get('/paystack-ipn', 'paystack_ipn')->name('paystack.ipn');
                Route::get('/midtrans-ipn', 'midtrans_ipn')->name('midtrans.ipn');
                Route::post('/cashfree-ipn', 'cashfree_ipn')->name('cashfree.ipn');
                Route::get('/instamojo-ipn', 'instamojo_ipn')->name('instamojo.ipn');
                Route::get('/paypal-ipn', 'paypal_ipn')->name('paypal.ipn');
                Route::get('/marcadopago-ipn', 'marcadopago_ipn')->name('marcadopago.ipn');
                Route::get('/squareup-ipn', 'squareup_ipn')->name('squareup.ipn');
                Route::post('/cinetpay-ipn', 'cinetpay_ipn')->name('cinetpay.ipn');
                Route::post('/paytabs-ipn', 'paytabs_ipn')->name('paytabs.ipn');
                Route::post('/billplz-ipn', 'billplz_ipn')->name('billplz.ipn');
                Route::post('/zitopay-ipn', 'zitopay_ipn')->name('zitopay.ipn');
                Route::post('/toyyibpay-ipn', 'toyyibpay_ipn')->name('toyyibpay.ipn');

                Route::get('/order-success/{id}','order_payment_success')->name('order.payment.success');
                Route::get('/order-cancel/{id}','order_payment_cancel')->name('order.payment.cancel');
                Route::get('/order-cancel-static','order_payment_cancel_static')->name('order.payment.cancel.static');
                Route::get('/order-confirm/{id}','order_confirm')->name('order.confirm');
            });
        });
    });
});

Route::group(['prefix' => 'product', 'as' => 'tenant.products.', 'middleware' =>[
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
    'tenant_glvar',
    'maintenance_mode',
]], function () {
    Route::get('/quick-viewpage/{slug}', [TenantFrontendController::class, 'productQuickViewPage'])->name('single-quick-view');

    /**--------------------------------
     *      COMPARE PRODUCT ROUTES
     * ---------------------------------*/
    Route::group(['prefix' => 'compare'], function () {
        Route::get('all', 'FrontendProductController@productsComparePage')->name('compare');
        Route::post('add', 'ProductCompareController@addToCompare')->name('add.to.compare');
        Route::post('remove', 'ProductCompareController@removeFromCompare')->name('compare.ajax.remove');
        Route::post('clear', 'ProductCompareController@clearCompare')->name('ajax.compare.update');
    });
});

require_once __DIR__ .'/tenant_admin.php';


Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
    'tenant_glvar',
])->group(function () {


    //TENANT USER LOGIN - REGISTRATION
    Route::middleware(['maintenance_mode','package_expire'])->controller(\App\Http\Controllers\Tenant\Frontend\TenantFrontendController::class)->name('tenant.')->group(function () {
        Route::get('/login', 'showTenantLoginForm')->name('user.login');
        Route::post('store-login','ajax_login')->name('user.ajax.login');
        Route::get('/register','showTenantRegistrationForm')->name('user.register');
        Route::post('/register-store','tenant_user_create')->name('user.register.store');
        Route::get('/logout','tenant_logout')->name('user.logout');
        Route::get('/login/forget-password','showUserForgetPasswordForm')->name('user.forget.password');
        Route::get('/login/reset-password/{user}/{token}','showUserResetPasswordForm')->name('user.reset.password');
        Route::post('/login/reset-password','UserResetPassword')->name('user.reset.password.change');
        Route::post('/login/forget-password','sendUserForgetPasswordMail');
        Route::get('/user-logout','user_logout')->name('frontend.user.logout');

        //for user
        Route::get('/verify-email','verify_user_email')->name('user.email.verify');
        Route::post('/verify-email','check_verify_user_email');
        Route::get('/resend-verify-email','resend_verify_user_email')->name('user.email.verify.resend');

        //for admin
        Route::get('/verify-admin-email','verify_admin_email')->name('admin.email.verify');
        Route::post('/verify-admin-email','check_verify_admin_email');
        Route::get('/resend-admin-verify-email','resend_admin_verify_user_email')->name('admin.email.verify.resend');

        //Order
        Route::get('/plan-order/{id}','plan_order')->name('frontend.plan.order');
        //payment status route
        Route::get('/order-success/{id}','order_payment_success')->name('frontend.order.payment.success');
        Route::get('/order-cancel/{id}','order_payment_cancel')->name('frontend.order.payment.cancel');
        Route::get('/order-cancel-static','order_payment_cancel_static')->name('frontend.order.payment.cancel.static');
        Route::get('/order-confirm/{id}','order_confirm')->name('frontend.order.confirm');
    });


    Route::middleware(['maintenance_mode'])->controller(\App\Http\Controllers\Tenant\Frontend\PaymentLogController::class)->name('tenant.')->group(function () {
        Route::post('/paytm-ipn', 'paytm_ipn')->name('frontend.paytm.ipn');
        Route::get('/mollie-ipn', 'mollie_ipn')->name('frontend.mollie.ipn');
        Route::get('/stripe-ipn', 'stripe_ipn')->name('frontend.stripe.ipn');
        Route::post('/razorpay-ipn', 'razorpay_ipn')->name('frontend.razorpay.ipn');
        Route::post('/payfast-ipn', 'payfast_ipn')->name('frontend.payfast.ipn');
        Route::get('/flutterwave/ipn', 'flutterwave_ipn')->name('frontend.flutterwave.ipn');
        Route::get('/paystack-ipn', 'paystack_ipn')->name('frontend.paystack.ipn');
        Route::get('/midtrans-ipn', 'midtrans_ipn')->name('frontend.midtrans.ipn');
        Route::post('/cashfree-ipn', 'cashfree_ipn')->name('frontend.cashfree.ipn');
        Route::get('/instamojo-ipn', 'instamojo_ipn')->name('frontend.instamojo.ipn');
        Route::get('/paypal-ipn', 'paypal_ipn')->name('frontend.paypal.ipn');
        Route::get('/marcadopago-ipn', 'marcadopago_ipn')->name('frontend.marcadopago.ipn');
        Route::post('/order-confirm','order_payment_form')->name('frontend.order.payment.form');
    });


    //User Dashboard Routes
    Route::controller(UserDashboardController::class)->middleware(['tenantUserMailVerify','package_expire'])->name('tenant.')->group(function(){
        Route::get('/user-home', 'user_index')->name('user.home');
        Route::get('/user/download/file/{id}', 'download_file')->name('user.dashboard.download.file');
        Route::get('/user/change-password', 'change_password')->name('user.home.change.password');
        Route::get('/user/manage-account', 'manage_account')->name('user.home.manage.account');
        Route::get('/user/edit-profile', 'edit_profile')->name('user.home.edit.profile');

        Route::post('/user/address-book', 'address_book')->name('user.home.address.update');
        Route::post('/user/profile-update', 'user_profile_update')->name('user.profile.update');

        Route::post('/user/password-change', 'user_password_change')->name('user.password.change');

        Route::get('/user/support-tickets', 'support_tickets')->name('user.home.support.tickets');
        Route::get('support-ticket/view/{id}', 'support_ticket_view')->name('user.dashboard.support.ticket.view');
        Route::post('support-ticket/priority-change', 'support_ticket_priority_change')->name('user.dashboard.support.ticket.priority.change');
        Route::post('support-ticket/status-change', 'support_ticket_status_change')->name('user.dashboard.support.ticket.status.change');
        Route::post('support-ticket/message', 'support_ticket_message')->name('user.dashboard.support.ticket.message');
        Route::get('/order-list/{id?}', 'order_list')->name('user.dashboard.package.order');
        Route::post('/package-order/cancel', 'package_order_cancel')->name('user.dashboard.package.order.cancel');
        Route::post('/package-user/generate-invoice', 'generate_package_invoice')->name('frontend.package.invoice.generate');

        Route::get('/country-wise-state-tax', 'stateAjax')->name('user.dashboard.get.state.ajax');
    });

    //User Support Ticket Routes
    Route::controller(\App\Http\Controllers\Tenant\Frontend\SupportTicketController::class)->name('tenant.')->group(function(){
        Route::get('user/support-tickets/new', 'page')->name('frontend.support.ticket');
        Route::post('support-tickets/new', 'store')->name('frontend.support.ticket.store');
    });

    //expire package redirection
    Route::get('/expired-package', 'App\Http\Controllers\Tenant\Frontend\TenantFrontendController@expired_package_redirection')->name('tenant.frontend.package.expired');

    Route::middleware(['maintenance_mode','package_expire'])->controller(\App\Http\Controllers\Tenant\Frontend\TenantFrontendController::class)->group(function () {
        //Newsletter
        Route::get('/subscriber/email-verify/{token}','subscriber_verify')->name('tenant.subscriber.verify');
        Route::post('/subscribe-newsletter','subscribe_newsletter')->name('tenant.frontend.subscribe.newsletter');

        Route::get('/{slug}', 'dynamic_single_page')->name('tenant.dynamic.page');
    });


    //User shop creation + subdomain creation through a package
    Route::controller(ShopCreationController::class)->name('tenant.')->group(function (){
        Route::get('select-theme/{id}', 'show_theme')->name('frontend.theme.show');
    });

    Route::name('tenant.')->group(function (){
        Route::get('category-wise-product/theme-one', [TenantFrontendController::class, 'product_by_category_ajax_one'])->name('category.wise.product.one');
        Route::get('category-wise-product/theme-two', [TenantFrontendController::class, 'product_by_category_ajax_two'])->name('category.wise.product.two');
    });
});
