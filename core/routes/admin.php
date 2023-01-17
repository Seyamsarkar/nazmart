<?php

use App\Http\Controllers\Landlord\Admin\LandlordAdminController;
use App\Http\Controllers\Landlord\Admin\AdminRoleManageController;
use App\Http\Controllers\Landlord\Admin\PagesController;
use App\Http\Controllers\Landlord\Admin\PricePlanController;
use App\Http\Controllers\Landlord\Admin\TestimonialController;
use App\Http\Controllers\Landlord\Admin\BrandController;
use App\Http\Controllers\Landlord\Admin\ContactMessageController;
use App\Http\Controllers\Landlord\Admin\WidgetsController;
use App\Http\Controllers\Landlord\Admin\CustomFormBuilderController;
use App\Http\Controllers\Landlord\Admin\FormBuilderController;
use App\Http\Controllers\Landlord\Admin\TenantManageController;
use App\Http\Controllers\Landlord\Admin\OrderManageController;
use App\Http\Controllers\Landlord\Admin\CustomDomainController;
use App\Http\Controllers\Landlord\Admin\GeneralSettingsController;
use App\Http\Controllers\Landlord\Admin\LanguagesController;
use App\Http\Controllers\Landlord\Admin\MediaUploaderController;
use App\Http\Controllers\Landlord\Admin\Error404PageManage;
use App\Http\Controllers\Landlord\Admin\MaintainsPageController;
use App\Http\Controllers\Landlord\Admin\PageBuilderController;
use App\Http\Controllers\Landlord\Admin\MenuController;
use App\Http\Controllers\Landlord\Admin\ThemeManageController;
use Modules\SupportTicket\Http\Controllers\Landlord\Admin\SupportTicketController;
use Modules\SupportTicket\Http\Controllers\Landlord\Admin\SupportDepartmentController;

/* ------------------------------------------
     LANDLORD ADMIN ROUTES
-------------------------------------------- */
Route::group(['middleware' => ['auth:admin','adminglobalVariable'],'prefix' => 'admin-home'],function (){
    /* ------------------------------------------
        ADMIN DASHBOARD ROUTES
    -------------------------------------------- */
    Route::controller(LandlordAdminController::class)->group(function (){
        Route::get('/','dashboard')->name('landlord.admin.home');
        Route::get('/edit-profile','edit_profile')->name('landlord.admin.edit.profile');
        Route::get('/change-password','change_password')->name('landlord.admin.change.password');
        Route::post('/edit-profile','update_edit_profile');
        Route::post('/change-password','update_change_password');
    });

    /* ------------------------------------------
     PAGES MANAGE ROUTES
   -------------------------------------------- */
    Route::controller(PagesController::class)->name('landlord.')->prefix('pages')->group(function (){
        Route::get('/','all_pages')->name('admin.pages');
        Route::get('/new','create_page')->name('admin.pages.create');
        Route::post('/new','store_new_page');
        Route::get('/edit/{id}','edit_page')->name('admin.pages.edit');
        Route::get('/page-builder/{id}','page_builder')->name('admin.pages.builder');
        Route::post('/update','update')->name('admin.pages.update');
        Route::post('/delete/{id}','delete')->name('admin.pages.delete');
    });

    /* ------------------------------------------
     THEMES MANAGE ROUTES
   -------------------------------------------- */
    Route::controller(ThemeManageController::class)->name('landlord.')->prefix('theme')->group(function (){
        Route::get('/','all_theme')->name('admin.theme');
        Route::post('/update','update_status')->name('admin.theme.status.update');

        Route::post('/theme/update','update_theme')->name('admin.theme.update');
        Route::get('/settings','theme_settings')->name('admin.theme.settings');
        Route::post('/settings','theme_settings_update');
    });

   /* ------------------------------------------
     PRICE PLAN MANAGE ROUTES
   -------------------------------------------- */
    Route::controller(PricePlanController::class)->name('landlord.')->prefix('price-plan')->group(function (){
        Route::get('/','all_price_plan')->name('admin.price.plan');
        Route::get('/new','create_price_plan')->name('admin.price.plan.create');
        Route::post('/new','store_new_price_plan');
        Route::get('/edit/{id}','edit_price_plan')->name('admin.price.plan.edit');
        Route::get('/page-builder/{id}','price_plan_builder')->name('admin.price.plan.builder');
        Route::post('/update','update')->name('admin.price.plan.update');
        Route::post('/delete/{id}','delete')->name('admin.price.plan.delete');
        Route::get('/settings','price_plan_settings')->name('admin.price.plan.settings');
        Route::post('/settings','update_price_plan_settings');
    });


    /* ------------------------------------------
    TENANT WEBSITE ISSUES MANAGE ROUTES
-------------------------------------------- */
    Route::controller(\App\Http\Controllers\Landlord\Admin\TenantExceptionController::class)->name('landlord.')->prefix('website-issues')->group(function (){
        Route::get('/','website_issues')->name('admin.tenant.website.issues');
        Route::post('/','generate_domain')->name('admin.failed.domain.generate');

    });


    /*==============================================
       SUPPORT TICKET MODULE
    ==============================================*/
    Route::controller(SupportTicketController::class)->name('landlord.')->prefix('support-ticket')->group(function (){
        Route::get('/', 'all_tickets')->name('admin.support.ticket.all');
        Route::get('/new', 'new_ticket')->name('admin.support.ticket.new');
        Route::post('/new', 'store_ticket');
        Route::post('/delete/{id}', 'delete')->name('admin.support.ticket.delete');
        Route::get('/view/{id}', 'view')->name('admin.support.ticket.view');
        Route::post('/bulk-action', 'bulk_action')->name('admin.support.ticket.bulk.action');
        Route::post('/priority-change', 'priority_change')->name('admin.support.ticket.priority.change');
        Route::post('/status-change', 'status_change')->name('admin.support.ticket.status.change');
        Route::post('/send message', 'send_message')->name('admin.support.ticket.send.message');

        /*-----------------------------------
            SUPPORT TICKET : PAGE SETTINGS ROUTES
        ------------------------------------*/
        Route::get('/page-settings', 'page_settings')->name('admin.support.ticket.page.settings');
        Route::post('/page-settings', 'update_page_settings');
    });

    /*-----------------------------------
        SUPPORT TICKET : DEPARTMENT ROUTES
    ------------------------------------*/
    Route::controller(SupportDepartmentController::class)->name('landlord.')->prefix('support-department')->group(function (){
        Route::get('/', 'category')->name('admin.support.ticket.department');
        Route::post('/', 'new_category');
        Route::post('/delete/{id}', 'delete')->name('admin.support.ticket.department.delete');
        Route::post('/update', 'update')->name('admin.support.ticket.department.update');
        Route::post('/bulk-action', 'bulk_action')->name('admin.support.ticket.department.bulk.action');
    });

/*----------------------------------------------------------------------------------------------------------------------------
| TESTIMONIAL ROUTES
|----------------------------------------------------------------------------------------------------------------------------*/
    Route::controller(TestimonialController::class)->name('landlord.')->prefix('testimonial')->group(function (){
        Route::get('/all','index')->name('admin.testimonial');
        Route::post('/all','store');
        Route::post('/clone','clone')->name('admin.testimonial.clone');
        Route::post('/update','update')->name('admin.testimonial.update');
        Route::post('/delete/{id}','delete')->name('admin.testimonial.delete');
        Route::post('/bulk-action','bulk_action')->name('admin.testimonial.bulk.action');
    });

/*----------------------------------------------------------------------------------------------------------------------------
 | BRAND AREA ROUTES
 |----------------------------------------------------------------------------------------------------------------------------*/
    Route::controller(BrandController::class)->name('landlord.')->prefix('brands')->group(function (){
        Route::get('/', 'index')->name('admin.brands');
        Route::post('/', 'store');
        Route::post('/update', 'update')->name('admin.brands.update');
        Route::post('/delete/{id}', 'delete')->name('admin.brands.delete');
        Route::post('/bulk-action', 'bulk_action')->name('admin.brands.bulk.action');
    });

/*----------------------------------------------------------------------------------------------------------------------------
 | CONTACT MESSAGE AREA ROUTES
 |----------------------------------------------------------------------------------------------------------------------------*/
    Route::controller(ContactMessageController::class)->name('landlord.')->prefix('contact-message')->group(function (){
        Route::get('/', 'index')->name('admin.contact.message.all');
        Route::get('/view/{id}', 'view')->name('admin.contact.message.view');
        Route::post('/delete/{id}', 'delete')->name('admin.contact.message.delete');
        Route::post('/bulk-action', 'bulk_action')->name('admin.contact.message.bulk.action');
    });


/* ------------------------------------------
    WIDGET BUILDER ROUTES
-------------------------------------------- */
    Route::controller(WidgetsController::class)->name('landlord.')->prefix('landlord')->group(function (){
        Route::get('/widgets','index')->name('admin.widgets');
        Route::post('/widgets/create','new_widget')->name('admin.widgets.new');
        Route::post('/widgets/markup','widget_markup')->name('admin.widgets.markup');
        Route::post('/widgets/update','update_widget')->name('admin.widgets.update');
        Route::post('/widgets/update/order','update_order_widget')->name('admin.widgets.update.order');
        Route::post('/widgets/delete','delete_widget')->name('admin.widgets.delete');
    });


    /*==============================================
           FORM BUILDER ROUTES
    ==============================================*/
    Route::controller(CustomFormBuilderController::class)->name('landlord.')->prefix('custom-form-builder')->group(function (){
/*-------------------------
    CUSTOM FORM BUILDER
--------------------------*/
        Route::get('/all', 'all')->name('admin.form.builder.all');
        Route::post('/new', 'store')->name('admin.form.builder.store');
        Route::get('/edit/{id}', 'edit')->name('admin.form.builder.edit');
        Route::post('/update', 'update')->name('admin.form.builder.update');
        Route::post('/delete/{id}', 'delete')->name('admin.form.builder.delete');
        Route::post('/bulk-action', 'bulk_action')->name('admin.form.builder.bulk.action');
    });
/*-------------------------
  CONTACT FORM ROUTES
  --------------------------*/
Route::controller(FormBuilderController::class)->name('landlord.')->prefix('form-builder')->group(function () {
    Route::get('/contact-form', 'contact_form_index')->name('admin.form.builder.contact');
    Route::post('/contact-form', 'update_contact_form');
});

    /* Topbar Settings */
    Route::controller(LandlordAdminController::class)->name('landlord.')->prefix('topbar')->group(function () {
         Route::get('/settings','topbar_settings')->name('admin.topbar.settings');
         Route::post('/settings','update_topbar_settings');
        Route::post('/chart', 'get_chart_data_month')->name('admin.home.chart.data.month');
        Route::post('/chart/day', 'get_chart_by_date_data')->name('admin.home.chart.data.by.day');
    });


    Route::controller(MenuController::class)->prefix('landlord')->group(function () {
        //MENU MANAGE
        Route::get('/menu', 'index')->name('landlord.admin.menu');
        Route::post('/new-menu', 'store_new_menu')->name('landlord.admin.menu.new');
        Route::get('/menu-edit/{id}', 'edit_menu')->name('landlord.admin.menu.edit');
        Route::post('/menu-update/{id}', 'update_menu')->name('landlord.admin.menu.update');
        Route::post('/menu-delete/{id}', 'delete_menu')->name('landlord.admin.menu.delete');
        Route::post('/menu-default/{id}', 'set_default_menu')->name('landlord.admin.menu.default');
        Route::post('/mega-menu', 'mega_menu_item_select_markup')->name('landlord.admin.mega.menu.item.select.markup');
    });


    /*----------------------------------------------------------------------------------------------------------------------------
    | ADMIN USER ROLE MANAGE
    |----------------------------------------------------------------------------------------------------------------------------*/
    Route::controller(AdminRoleManageController::class)->name('landlord.')->prefix('admin')->group(function (){
        Route::get('/all','all_user')->name('admin.all.user');
        Route::get('/new','new_user')->name('admin.new.user');
        Route::post('/new','new_user_add');
        Route::get('/user-edit/{id}','user_edit')->name('admin.user.edit');
        Route::post('/user-update','user_update')->name('admin.user.update');
        Route::post('/user-password-change','user_password_change')->name('admin.user.password.change');
        Route::post('/delete-user/{id}','new_user_delete')->name('admin.delete.user');
        /*----------------------------
         ALL ADMIN ROLE ROUTES
        -----------------------------*/
        Route::get('/role','all_admin_role')->name('admin.all.admin.role');
        Route::get('/role/new','new_admin_role_index')->name('admin.role.new');
        Route::post('/role/new','store_new_admin_role');
        Route::get('/role/edit/{id}','edit_admin_role')->name('admin.user.role.edit');
        Route::post('/role/update','update_admin_role')->name('admin.user.role.update');
        Route::post('/role/delete/{id}','delete_admin_role')->name('admin.user.role.delete');
    });


    /* ------------------------------------------
      TENANT MANAGE ROUTES
    -------------------------------------------- */
    Route::controller(TenantManageController::class)->name('landlord.')->prefix('tenant')->group(function (){
        Route::get('/','all_tenants')->name('admin.tenant');
        Route::get('/new','new_tenant')->name('admin.tenant.new');
        Route::post('/new','new_tenant_store');
        Route::get('/edit-profile/{id}','edit_profile')->name('admin.tenant.edit.profile');
        Route::post('/update-profile','update_edit_profile')->name('admin.tenant.update.profile');
        Route::post('/delete/{id}','delete')->name('admin.tenant.delete');
        Route::post('/change-password','update_change_password')->name('admin.tenant.change.password');
        Route::get('/view/{id}','view_profile')->name('admin.tenant.view');
        Route::post('/send-mail','send_mail')->name('admin.tenant.send.mail');
        Route::post('/resend-verify-mail','resend_verify_mail')->name('admin.tenant.resend.verify.mail');
        Route::get('/activity-log','tenant_activity_log')->name('admin.tenant.activity.log');
        Route::get('/details/{id}','tenant_details')->name('admin.tenant.details');
        Route::post('/domain/delete/{tenant_id}','tenant_domain_delete')->name('admin.tenant.domain.delete');
        Route::post('/tenant/status','tenant_account_status')->name('admin.tenant.account.status');
        Route::post('/assign-subscription','assign_subscription')->name('admin.tenant.assign.subscription');
        Route::get('/account-settings','account_settings')->name('admin.tenant.settings');
        Route::post('/account-settings','account_settings_update');
    });

/*----------------------------------------------------------------------------------------------------------------------------
| PACKAGE ORDER MANAGE
|----------------------------------------------------------------------------------------------------------------------------*/
    Route::controller(OrderManageController::class)->name('landlord.')->prefix('order-manage')->group(function (){
        Route::get('/all','all_orders')->name('admin.package.order.manage.all');
        Route::get('/view/{id}','view_order')->name('admin.package.order.manage.view');;
        Route::post('/change-status','change_status')->name('admin.package.order.manage.change.status');
        Route::post('/send-mail','send_mail')->name('admin.package.order.manage.send.mail');
        Route::post('/delete/{id}','order_delete')->name('admin.package.order.manage.delete');
        //thank you page
        Route::get('/success-page','order_success_payment')->name('admin.package.order.success.page');
        Route::post('/success-page','update_order_success_payment');
        //cancel page
        Route::get('/cancel-page','order_cancel_payment')->name('admin.package.order.cancel.page');
        Route::post('/cancel-page','update_order_cancel_payment');
        Route::get('/order-page','index')->name('admin.package.order.page');
        Route::post('/order-page','udpate');
        Route::post('/bulk-action','bulk_action')->name('admin.package.order.bulk.action');
        Route::post('/reminder','order_reminder')->name('admin.package.order.reminder');
        Route::get('/order-report','order_report')->name('admin.package.order.report');
        //payment log route
        Route::post('/generate-invoice/frontend-user', 'generate_package_invoice')->name('package.invoice.generate');
        Route::get('/payment-logs','all_payment_logs')->name('admin.payment.logs');
        Route::post('/payment-logs/delete/{id}','payment_logs_delete')->name('admin.payment.delete');
        Route::post('/payment-logs/approve/{id}','payment_logs_approve')->name('admin.payment.approve');
        Route::post('/payment-logs/bulk-action','payment_log_bulk_action')->name('admin.payment.bulk.action');
        Route::get('/payment-logs/report','payment_report')->name('admin.payment.report');
        Route::get('/payment-logs/payment-status/{id}','payment_log_payment_status_change')->name('admin.package.order.payment.status.change');
    });

/*----------------------------------------------------------------------------------------------------------------------------
| CUSTOM DOMAIN MANAGE
|----------------------------------------------------------------------------------------------------------------------------*/
    Route::controller(CustomDomainController::class)->name('landlord.')->prefix('custom-domain')->group(function (){
        Route::get('/all-pending-requests','all_pending_custom_domain_requests')->name('admin.custom.domain.requests.all.pending');
        Route::get('/all-requests','all_domain_requests')->name('admin.custom.domain.requests.all');
        Route::get('/settings','settings')->name('admin.custom.domain.requests.settings');
        Route::post('/settings','update_settings');
        Route::post('/status-change','status_change')->name('admin.custom.domain.status.change');
        Route::post('/delete/{id}','delete_request')->name('admin.custom.domain.request.delete');
        Route::post('/bulk-action','bulk_action')->name('admin.custom.domain.bulk.action');
    });

    /* ------------------------------------------
        GENERAL SETTINGS ROUTES
     -------------------------------------------- */
    Route::controller(GeneralSettingsController::class)->prefix('general-settings')->group(function (){

        /* Basic settings */
        Route::get('/basic-settings','basic_settings')->name('landlord.admin.general.basic.settings');
        Route::post('/basic-settings','update_basic_settings');
        /* Page Settings */

        Route::get('/page-settings','page_settings')->name('landlord.admin.general.page.settings');
        Route::post('/page-settings','update_page_settings');
        /* site identity Settings */
        Route::get('/site-identity','site_identity')->name('landlord.admin.general.site.identity');
        Route::post('/site-identity','update_site_identity');

        /* Color Settings */
        Route::get('/color-settings','color_settings')->name('landlord.admin.general.color.settings');
        Route::post('/color-settings','update_color_settings');

        /* Typography Settings */
        Route::get('/typography-settings','typography_settings')->name('landlord.admin.general.typography.settings');
        Route::post('/typography-settings','update_typography_settings');
        Route::post('typography-settings/single','GeneralSettingsController@get_single_font_variant')->name('landlord.admin.general.typography.single');

        /* SEO Settings */
        Route::get('/seo-settings','seo_settings')->name('landlord.admin.general.seo.settings');
        Route::post('/seo-settings','update_seo_settings');

        /* Payment Settings (Static) */
        Route::get('/payment-settings','payment_settings')->name('landlord.admin.general.payment.settings');
        Route::post('/payment-settings','update_payment_settings');

        /* Third party scripts Settings */
        Route::get('/third-party-script-settings','third_party_script_settings')->name('landlord.admin.general.third.party.script.settings');
        Route::post('/third-party-script-settings','update_third_party_script_settings');

        /* smtp Settings */
        Route::get('/smtp-settings','smtp_settings')->name('landlord.admin.general.smtp.settings');
        Route::post('/smtp-settings','update_smtp_settings');
        Route::post('/send-test-mail','send_test_mail')->name('landlord.admin.general.smtp.settings.test.mail');

        /* custom css Settings */
        Route::get('/custom-css-settings','custom_css_settings')->name('landlord.admin.general.custom.css.settings');
        Route::post('/custom-css-settings','update_custom_css_settings');

        /* js css Settings */
        Route::get('/custom-js-settings','custom_js_settings')->name('landlord.admin.general.custom.js.settings');
        Route::post('/custom-js-settings','update_custom_js_settings');

        /* Database Upgrade Settings */
        Route::get('/database-upgrade-settings','database_upgrade')->name('landlord.admin.general.database.upgrade.settings');
        Route::post('/database-upgrade-settings','update_database_upgrade');

        /* Cache  Settings */
        Route::get('/cache-settings','cache_settings')->name('landlord.admin.general.cache.settings');
        Route::post('/cache-settings','update_cache_settings');

        /* License Upgrade Settings */
        Route::get('/license-settings','license_settings')->name('landlord.admin.general.license.settings');
        Route::post('/license-settings','update_license_settings');

    });

    /* ------------------------------------------
        LANGUAGES
    -------------------------------------------- */
    Route::controller(LanguagesController::class)->name('landlord.')->prefix('languages')->group(function (){
        Route::get('/','index')->name('admin.languages');
        Route::get('/languages/words/all/{id}','all_edit_words')->name('admin.languages.words.all');
        Route::post('/languages/words/update/{id}','update_words')->name('admin.languages.words.update');
        Route::post('/languages/new','store')->name('admin.languages.new');
        Route::post('/languages/update','update')->name('admin.languages.update');
        Route::post('/languages/delete/{id}','delete')->name('admin.languages.delete');
        Route::post('/languages/default/{id}','make_default')->name('admin.languages.default');
        Route::post('/languages/clone','clone_languages')->name('admin.languages.clone');
        Route::post('/add-new-string', 'add_new_string')->name('admin.languages.add.string');
        Route::post('/languages/regenerate-source-text','regenerate_source_text')->name('admin.languages.regenerate.source.texts');

    });

    /* ------------------------------------------
      MEDIA UPLOADER ROUTES
     -------------------------------------------- */
    Route::prefix('media-upload')->controller(MediaUploaderController::class)->group(function () {
        Route::post('/delete', 'delete_upload_media_file')->name('landlord.admin.upload.media.file.delete');
        Route::get('/page', 'all_upload_media_images_for_page')->name('landlord.admin.upload.media.images.page');
        Route::post('/alt', 'alt_change_upload_media_file')->name('landlord.admin.upload.media.file.alt.change');
    });

    //Others Page Settings
    Route::prefix('error')->controller(Error404PageManage::class)->group(function () {
        Route::get('/404-page-manage', 'error_404_page_settings')->name('landlord.admin.404.page.settings');
        Route::post('/404-page-manage', 'update_error_404_page_settings');
    });
    Route::prefix('maintenance')->controller(MaintainsPageController::class)->group(function () {
        Route::get('/settings', 'maintains_page_settings')->name('landlord.admin.maintains.page.settings');
        Route::post('/settings', 'update_maintains_page_settings');
    });



    /*--------------------------
      PAGE BUILDER
    --------------------------*/
    Route::controller(PageBuilderController::class)->group(function (){
        Route::post('/update', 'update_addon_content')->name('landlord.admin.page.builder.update');
        Route::post('/new', 'store_new_addon_content')->name('landlord.admin.page.builder.new');
        Route::post('/delete', 'delete')->name('landlord.admin.page.builder.delete');
        Route::post('/update-order', 'update_addon_order')->name('landlord.admin.page.builder.update.addon.order');
        Route::post('/get-admin-markup', 'get_admin_panel_addon_markup')->name('landlord.admin.page.builder.get.addon.markup');
    });

} );

/* ------------------------------------------
      MEDIA UPLOADER ROUTES
-------------------------------------------- */
Route::prefix('media-upload')->controller(MediaUploaderController::class)->group(function () {
    Route::post('/media-upload/all', 'all_upload_media_file')->name('landlord.admin.upload.media.file.all');
    Route::post('/media-upload', 'upload_media_file')->name('landlord.admin.upload.media.file');
    Route::post('/media-upload/loadmore', 'get_image_for_load_more')->name('landlord.admin.upload.media.file.loadmore');
});



