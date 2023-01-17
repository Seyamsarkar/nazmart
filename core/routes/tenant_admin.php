<?php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use App\Http\Controllers\Landlord\Admin\GeneralSettingsController;
use Modules\Blog\Http\Controllers\Landlord\Admin\BlogTagController;
use App\Http\Controllers\Tenant\Admin\OrderManageController;


Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
    'auth:admin',
    'tenant_admin_glvar',
    'package_expire',
    'tenantAdminPanelMailVerify',
    'tenant_status'
])->prefix('admin-home')->name('tenant.')->group(function () {

    Route::get('/', [\App\Http\Controllers\Tenant\Admin\TenantDashboardController::class, 'dashboard'])->name('admin.dashboard');

    /* ------------------------------------------
       ADMIN DASHBOARD ROUTES
   -------------------------------------------- */
    Route::controller(\App\Http\Controllers\Landlord\Admin\LandlordAdminController::class)->group(function () {
        Route::get('/activity-logs', 'activity_logs')->name('admin.activity.log');
        Route::get('/edit-profile', 'edit_profile')->name('admin.edit.profile');
        Route::get('/change-password', 'change_password')->name('admin.change.password');
        Route::post('/edit-profile', 'update_edit_profile');
        Route::post('/change-password', 'update_change_password');
    });

    /* ------------------------------------------
        LANGUAGES
    -------------------------------------------- */
    Route::controller(\App\Http\Controllers\Landlord\Admin\LanguagesController::class)->prefix('languages')->group(function () {

        Route::get('/', 'index')->name('admin.languages');
        Route::get('/languages/words/all/{id}', 'all_edit_words')->name('admin.languages.words.all');
        Route::post('/languages/words/update/{id}', 'update_words')->name('admin.languages.words.update');
        Route::post('/languages/new', 'store')->name('admin.languages.new');
        Route::post('/languages/update', 'update')->name('admin.languages.update');
        Route::post('/languages/delete/{id}', 'delete')->name('admin.languages.delete');
        Route::post('/languages/default/{id}', 'make_default')->name('admin.languages.default');
        Route::post('/languages/clone', 'clone_languages')->name('admin.languages.clone');
        Route::post('/add-new-string', 'add_new_string')->name('admin.languages.add.string');
        Route::post('/languages/regenerate-source-text', 'regenerate_source_text')->name('admin.languages.regenerate.source.texts');
    });


    /* ------------------------------------------
    MEDIA UPLOADER ROUTES
   -------------------------------------------- */
    Route::prefix('media-upload')->controller(\App\Http\Controllers\Landlord\Admin\MediaUploaderController::class)->group(function () {
        Route::post('/delete', 'delete_upload_media_file')->name('admin.upload.media.file.delete');
        Route::get('/page', 'all_upload_media_images_for_page')->name('admin.upload.media.images.page');
        Route::post('/alt', 'alt_change_upload_media_file')->name('admin.upload.media.file.alt.change');
    });

    /* ------------------------------------------
      MEDIA UPLOADER ROUTES
    -------------------------------------------- */
    Route::prefix('media-upload')->controller(\App\Http\Controllers\Landlord\Admin\MediaUploaderController::class)->group(function () {
        Route::post('/media-upload/all', 'all_upload_media_file')->name('admin.upload.media.file.all');
        Route::post('/media-upload', 'upload_media_file')->name('admin.upload.media.file');
        Route::post('/media-upload/loadmore', 'get_image_for_load_more')->name('admin.upload.media.file.loadmore');
    });

    /*--------------------------
      PAGE BUILDER
    --------------------------*/
    Route::controller(\App\Http\Controllers\Landlord\Admin\PageBuilderController::class)->group(function () {
        Route::post('/update', 'update_addon_content')->name('admin.page.builder.update');
        Route::post('/new', 'store_new_addon_content')->name('admin.page.builder.new');
        Route::post('/delete', 'delete')->name('admin.page.builder.delete');
        Route::post('/update-order', 'update_addon_order')->name('admin.page.builder.update.addon.order');
        Route::post('/get-admin-markup', 'get_admin_panel_addon_markup')->name('admin.page.builder.get.addon.markup');
    });


    /*----------------------------------------------------------------------------------------------------------------------------
    | CUSTOM DOMAIN MANAGE
    |----------------------------------------------------------------------------------------------------------------------------*/
    Route::controller(\App\Http\Controllers\Tenant\Admin\CustomDomainController::class)->prefix('custom-domain')->group(function () {
        Route::get('/custom-domain-request', 'custom_domain_request')->name('admin.custom.domain.requests');
        Route::post('/custom-domain-request', 'custom_domain_request_change');
    });

    /*----------------------------------------------------------------------------------------------------------------------------
    | ADMIN USER ROLE MANAGE
    |----------------------------------------------------------------------------------------------------------------------------*/
    Route::controller(\App\Http\Controllers\Tenant\Admin\AdminRoleManageController::class)->prefix('admin')->group(function () {
        Route::get('/all', 'all_user')->name('admin.all.user');
        Route::get('/new', 'new_user')->name('admin.new.user');
        Route::post('/new', 'new_user_add');
        Route::get('/user-edit/{id}', 'user_edit')->name('admin.user.edit');
        Route::post('/user-update', 'user_update')->name('admin.user.update');
        Route::post('/user-password-change', 'user_password_change')->name('admin.user.password.change');
        Route::post('/delete-user/{id}', 'new_user_delete')->name('admin.delete.user');

        /*----------------------------
         ALL ADMIN ROLE ROUTES
        -----------------------------*/
        Route::get('/role', 'all_admin_role')->name('admin.all.admin.role');
        Route::get('/role/new', 'new_admin_role_index')->name('admin.role.new');
        Route::post('/role/new', 'store_new_admin_role');
        Route::get('/role/edit/{id}', 'edit_admin_role')->name('admin.user.role.edit');
        Route::post('/role/update', 'update_admin_role')->name('admin.user.role.update');
        Route::post('/role/delete/{id}', 'delete_admin_role')->name('admin.user.role.delete');
    });

    /*--------------------------
      TOPBAR SETTING ROUTE
    ----------------------------*/
    Route::controller(\App\Http\Controllers\Tenant\Admin\TopbarController::class)->group(function () {
        Route::get('/topbar-settings', "index")->name('admin.topbar.settings');
        Route::post('/topbar/new-social-item', 'new_social_item')->name('admin.new.social.item');
        Route::post('/topbar/update-social-item', 'update_social_item')->name('admin.update.social.item');
        Route::post('/topbar/delete-social-item/{id}', 'delete_social_item')->name('admin.delete.social.item');
    });

    /* ------------------------------------------
     PAGES MANAGE ROUTES
    -------------------------------------------- */
    Route::controller(\App\Http\Controllers\Landlord\Admin\PagesController::class)->prefix('pages')->group(function () {
        Route::get('/', 'all_pages')->name('admin.pages');
        Route::get('/new', 'create_page')->name('admin.pages.create');
        Route::post('/new', 'store_new_page')->middleware('page_limit');
        Route::get('/edit/{id}', 'edit_page')->name('admin.pages.edit');
        Route::get('/page-builder/{id}', 'page_builder')->name('admin.pages.builder');
        Route::post('/update', 'update')->name('admin.pages.update');
        Route::post('/delete/{id}', 'delete')->name('admin.pages.delete');
        Route::get('/download/{id}', 'download')->name('admin.pages.download');
        Route::post('/upload', 'upload')->name('admin.pages.upload');
    });


    //Others Page Settings
    Route::prefix('error')->controller(\App\Http\Controllers\Landlord\Admin\Error404PageManage::class)->group(function () {
        Route::get('/404-page-manage', 'error_404_page_settings')->name('admin.404.page.settings');
        Route::post('/404-page-manage', 'update_error_404_page_settings');
    });
    Route::prefix('maintenance')->controller(\App\Http\Controllers\Landlord\Admin\MaintainsPageController::class)->group(function () {
        Route::get('/settings', 'maintains_page_settings')->name('admin.maintains.page.settings');
        Route::post('/settings', 'update_maintains_page_settings');
    });


    /* ------------------------------------------
     PRICE PLAN MANAGE ROUTES
    -------------------------------------------- */
    Route::controller(\App\Http\Controllers\Landlord\Admin\PricePlanController::class)->prefix('price-plan')->group(function () {
        Route::get('/', 'all_price_plan')->name('admin.price.plan');
        Route::get('/new', 'create_price_plan')->name('admin.price.plan.create');
        Route::post('/new', 'store_new_price_plan');
        Route::get('/edit/{id}', 'edit_price_plan')->name('admin.price.plan.edit');
        Route::get('/page-builder/{id}', 'price_plan_builder')->name('admin.price.plan.builder');
        Route::post('/update', 'update')->name('admin.price.plan.update');
        Route::post('/delete/{id}', 'delete')->name('admin.price.plan.delete');
    });

    /*----------------------------------------------------------------------------------------------------------------------------
    | TESTIMONIAL  ROUTES
    |----------------------------------------------------------------------------------------------------------------------------*/
    Route::controller(\App\Http\Controllers\Landlord\Admin\TestimonialController::class)->prefix('testimonial')->group(function () {
        Route::get('/all', 'index')->name('admin.testimonial');
        Route::post('/all', 'store');
        Route::post('/clone', 'clone')->name('admin.testimonial.clone');
        Route::post('/update', 'update')->name('admin.testimonial.update');
        Route::post('/delete/{id}', 'delete')->name('admin.testimonial.delete');
        Route::post('/bulk-action', 'bulk_action')->name('admin.testimonial.bulk.action');
    });

    /*----------------------------------------------------------------------------------------------------------------------------
     | BRAND AREA ROUTES
     |----------------------------------------------------------------------------------------------------------------------------*/
    Route::controller(\App\Http\Controllers\Landlord\Admin\BrandController::class)->prefix('brands')->group(function () {
        Route::get('/', 'index')->name('admin.brands');
        Route::post('/', 'store');
        Route::post('/update', 'update')->name('admin.brands.update');
        Route::post('/delete/{id}', 'delete')->name('admin.brands.delete');
        Route::post('/bulk-action', 'bulk_action')->name('admin.brands.bulk.action');
    });


    /*----------------------------------------------------------------------------------------------------------------------------
    | BLOG  ROUTES
    |----------------------------------------------------------------------------------------------------------------------------*/

    Route::controller(\Modules\Blog\Http\Controllers\Landlord\Admin\BlogController::class)->prefix('blog')->group(function () {
        Route::get('/', 'index')->name('admin.blog');
        Route::get('/new', 'new_blog')->name('admin.blog.new');
        Route::post('/new', 'store_new_blog');
        Route::get('/edit/{id}', 'edit_blog')->name('admin.blog.edit');
        Route::post('/update/{id}', 'update_blog')->name('admin.blog.update');
        Route::post('/clone', 'clone_blog')->name('admin.blog.clone');
        Route::post('/delete/all/lang/{id}', 'delete_blog_all_lang')->name('admin.blog.delete.all.lang');
        Route::post('/bulk-action', 'bulk_action_blog')->name('admin.blog.bulk.action');
        Route::get('/view/analytics/{id}', 'view_analytics')->name('admin.blog.view.analytics');
        Route::post('/view/data/monthly', 'view_data_monthly')->name('admin.blog.view.data.monthly');

        //Blog Comments Route
        Route::get('/comments/view/{id}', 'view_comments')->name('admin.blog.comments.view');
        Route::post('/comments/delete/all/lang/{id}', 'delete_all_comments')->name('admin.blog.comments.delete.all.lang');
        Route::post('/comments/bulk-action', 'bulk_action_comments')->name('admin.blog.comments.bulk.action');

        // Page Settings
        Route::get('/settings', 'blog_settings')->name('admin.blog.settings');
        Route::post('/settings', 'update_blog_settings');

    });


    /*----------------------------------------------------------------------------------------------------------------------------
    | BACKEND BLOG CATEGORY AREA
    |----------------------------------------------------------------------------------------------------------------------------*/

    Route::controller(\Modules\Blog\Http\Controllers\Landlord\Admin\BlogCategoryController::class)->prefix('blog-category')->group(function () {
        Route::get('/', 'index')->name('admin.blog.category');
        Route::post('/store', 'new_category')->name('admin.blog.category.store');
        Route::post('/update', 'update_category')->name('admin.blog.category.update');
        Route::post('/delete/all/lang/{id}', 'delete_category_all_lang')->name('admin.blog.category.delete');
        Route::post('/bulk-action', 'bulk_action')->name('admin.blog.category.bulk.action');
    });

    /*----------------------------------------------------------------------------------------------------------------------------
    | BACKEND BLOG TAG AREA
    |----------------------------------------------------------------------------------------------------------------------------*/
    Route::controller(BlogTagController::class)->prefix('blog-tag')->group(function () {
        Route::get('/', 'index')->name('admin.blog.tag');
        Route::post('/store', 'new_tag')->name('admin.blog.tag.store');
        Route::post('/update', 'update_tag')->name('admin.blog.tag.update');
        Route::post('/delete/all/lang/{id}', 'delete_tag_all_lang')->name('admin.blog.tag.delete');
        Route::post('/bulk-action', 'bulk_action')->name('admin.blog.tag.bulk.action');

        Route::get('/get/tags','get_tags_by_ajax')->name('admin.blog.get.tags.by.ajax');
    });


    /*----------------------------------------------------------------------------------------------------------------------------
    | BACKEND NEWSLETTER AREA
    |---------------------------------------------------------------------------------------------------------------------------*/
    Route::controller(\App\Http\Controllers\Tenant\Admin\NewsletterController::class)->prefix('newsletter')->group(function () {
        Route::get('/', 'index')->name('admin.newsletter');
        Route::post('/delete/{id}', 'delete')->name('admin.newsletter.delete');
        Route::post('/single', 'send_mail')->name('admin.newsletter.single.mail');
        Route::get('/all', 'send_mail_all_index')->name('admin.newsletter.mail');
        Route::post('/all', 'send_mail_all');
        Route::post('/new', 'add_new_sub')->name('admin.newsletter.new.add');
        Route::post('/bulk-action', 'bulk_action')->name('admin.newsletter.bulk.action');
        Route::post('/newsletter/verify-mail-send', 'verify_mail_send')->name('admin.newsletter.verify.mail.send');
    });

    /*==============================================
           FORM BUILDER ROUTES
    ==============================================*/
    Route::controller(\App\Http\Controllers\Landlord\Admin\CustomFormBuilderController::class)->prefix('custom-form-builder')->group(function () {
        /*-------------------------
                CUSTOM FORM BUILDERs
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
    -------------------------*/
    Route::controller(\App\Http\Controllers\Landlord\Admin\FormBuilderController::class)->prefix('form-builder')->group(function () {
        Route::get('/contact-form', 'contact_form_index')->name('admin.form.builder.contact');
        Route::post('/contact-form', 'update_contact_form');
    });

    Route::controller(\App\Http\Controllers\Tenant\Admin\MenuController::class)->prefix('menu')->group(function () {
        //MENU MANAGE
        Route::get('/menu', 'index')->name('admin.menu');
        Route::post('/new-menu', 'store_new_menu')->name('admin.menu.new');
        Route::get('/menu-edit/{id}', 'edit_menu')->name('admin.menu.edit');
        Route::post('/menu-update/{id}', 'update_menu')->name('admin.menu.update');
        Route::post('/menu-delete/{id}', 'delete_menu')->name('admin.menu.delete');
        Route::post('/menu-default/{id}', 'set_default_menu')->name('admin.menu.default');
        Route::post('/mega-menu', 'mega_menu_item_select_markup')->name('admin.mega.menu.item.select.markup');
    });


    /*----------------------------------------------------------------------------------------------------------------------------
     | CONTACT MESSAGE AREA ROUTES
     |----------------------------------------------------------------------------------------------------------------------------*/
    Route::controller(\App\Http\Controllers\Landlord\Admin\ContactMessageController::class)->prefix('contact-message')->group(function () {
        Route::get('/', 'index')->name('admin.contact.message.all');
        Route::get('/view/{id}', 'view')->name('admin.contact.message.view');
        Route::post('/delete/{id}', 'delete')->name('admin.contact.message.delete');
        Route::post('/bulk-action', 'bulk_action')->name('admin.contact.message.bulk.action');
    });

    /* ------------------------------------------
    WIDGET BUILDER ROUTES
-------------------------------------------- */
    Route::controller(\App\Http\Controllers\Landlord\Admin\WidgetsController::class)->prefix('tenant')->group(function () {
        Route::get('/widgets', 'index')->name('admin.widgets');
        Route::post('/widgets/create', 'new_widget')->name('admin.widgets.new');
        Route::post('/widgets/markup', 'widget_markup')->name('admin.widgets.markup');
        Route::post('/widgets/update', 'update_widget')->name('admin.widgets.update');
        Route::post('/widgets/update/order', 'update_order_widget')->name('admin.widgets.update.order');
        Route::post('/widgets/delete', 'delete_widget')->name('admin.widgets.delete');
    });

    /*==============================================
       SUPPORT TICKET MODULE
    ==============================================*/
    Route::controller(\Modules\SupportTicket\Http\Controllers\Tenant\Admin\SupportTicketController::class)->prefix('support-ticket')->group(function () {
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
    Route::controller(\Modules\SupportTicket\Http\Controllers\Tenant\Admin\SupportDepartmentController::class)->prefix('support-department')->group(function () {
        Route::get('/', 'category')->name('admin.support.ticket.department');
        Route::post('/', 'new_category');
        Route::post('/delete/{id}', 'delete')->name('admin.support.ticket.department.delete');
        Route::post('/update', 'update')->name('admin.support.ticket.department.update');
        Route::post('/bulk-action', 'bulk_action')->name('admin.support.ticket.department.bulk.action');
    });


    /* ------------------------------------------
    OTHER SETTINGS ROUTES
    -------------------------------------------- */
    Route::controller(\App\Http\Controllers\Tenant\Admin\OtherSettingsController::class)->prefix('tenant')->group(function () {
        Route::get('/settings', 'other_settings_page')->name('admin.other.settings');
        Route::post('/settings', 'update_other_settings');
    });

    /* ------------------------------------------
      TENANT PACKAGE ORDER ROUTES
     -------------------------------------------- */
    Route::prefix('my')->controller(\App\Http\Controllers\Tenant\Admin\MyPackageOrderController::class)->group(function () {
        Route::get('/package-orders', 'my_payment_logs')->name('my.package.order.payment.logs');
        Route::post('/package-order/cancel', 'package_order_cancel')->name('admin.package.order.cancel');
        Route::post('/package/generate-invoice', 'generate_package_invoice')->name('my.package.invoice.generate');
    });

    /* ------------------------------------------
      USER MANAGE ROUTES
    -------------------------------------------- */
    Route::controller(\App\Http\Controllers\Tenant\Admin\FrontendUserManageController::class)->prefix('user')->group(function () {
        Route::get('/', 'all_users')->name('admin.user');
        Route::get('/new', 'new_user')->name('admin.user.new');
        Route::post('/new', 'new_user_store');
        Route::get('/edit-profile/{id}', 'edit_profile')->name('admin.user.edit.profile');
        Route::post('/update-profile', 'update_edit_profile')->name('admin.user.update.profile');
        Route::post('/delete/{id}', 'delete')->name('admin.user.delete');
        Route::post('/change-password', 'update_change_password')->name('admin.user.change.password');
        Route::get('/view/{id}', 'view_profile')->name('admin.user.view');
        Route::post('/send-mail', 'send_mail')->name('admin.user.send.mail');
        Route::post('/resend-verify-mail', 'resend_verify_mail')->name('admin.user.resend.verify.mail');
    });


    /*----------------------------------------------------------------------------------------------------------------------------
    | PACKAGE ORDER MANAGE
    |----------------------------------------------------------------------------------------------------------------------------*/
    Route::controller(OrderManageController::class)->prefix('order-manage')->group(function () {
        Route::get('/all', 'all_orders')->name('admin.product.order.manage.all');
        Route::get('/view/{id}', 'view_order')->name('admin.product.order.manage.view');
        Route::get('/pending', 'pending_orders')->name('admin.product.order.manage.pending');
        Route::get('/completed', 'completed_orders')->name('admin.product.order.manage.completed');
        Route::get('/in-progress', 'in_progress_orders')->name('admin.product.order.manage.in.progress');
        Route::post('/change-status', 'change_status')->name('admin.product.order.manage.change.status');
        Route::post('/send-mail', 'send_mail')->name('admin.product.order.manage.send.mail');
        //thank you page
        Route::get('/success-page', 'order_success_payment')->name('admin.product.order.success.page');
        Route::post('/success-page', 'update_order_success_payment');
        //cancel page
        Route::get('/cancel-page', 'order_cancel_payment')->name('admin.product.order.cancel.page');
        Route::post('/cancel-page', 'update_order_cancel_payment');
        Route::get('/order-page', 'index')->name('admin.product.order.page');
        Route::post('/order-page', 'udpate');
        Route::post('/bulk-action', 'bulk_action')->name('admin.product.order.bulk.action');
        Route::post('/reminder', 'order_reminder')->name('admin.product.order.reminder');
        Route::get('/order-report', 'order_report')->name('admin.product.order.report');
        //payment log route
        Route::get('/payment-logs', 'all_payment_logs')->name('admin.payment.logs');
        Route::post('/payment-logs/delete/{id}', 'payment_logs_delete')->name('admin.payment.delete');
        Route::post('/payment-logs/approve/{id}', 'payment_logs_approve')->name('admin.payment.approve');
        Route::post('/payment-logs/bulk-action', 'payment_log_bulk_action')->name('admin.payment.bulk.action');
        Route::get('/payment-logs/report', 'payment_report')->name('admin.payment.report');
        Route::post('/order-user/generate-invoice', 'generate_order_invoice')->name('admin.order.invoice.generate');

        //Order settings route
        Route::match(['get', 'post'] ,'/order/settings', 'order_manage_settings')->name('admin.product.order.settings');
    });

    /*------------------------------------------
        MY PACKAGE ORDER MANAGE ROUTES
    -------------------------------------------- */
    Route::controller(\App\Http\Controllers\Tenant\Admin\MyPackageOrderController::class)->prefix('package')->group(function () {
        Route::get('/payment-logs', 'my_payment_logs')->name('my.package.order.payment.logs');
        Route::get('/buy-plan', 'update_other_settings')->name('my.package.order.buy.plan');
    });


    /* ------------------------------------------
            GENERAL SETTINGS ROUTES
         -------------------------------------------- */
    Route::controller(GeneralSettingsController::class)->prefix('general-settings')->group(function () {

        //Reading
        Route::get('/page-settings', 'page_settings')->name('admin.general.page.settings');
        Route::post('/page-settings', 'update_page_settings');
        //Footer Global Variant
        Route::get('/global-variant-footer', 'global_variant_footer')->name('admin.general.global.footer.settings');
        Route::post('/global-variant-footer', 'update_global_variant_footer');

        /* Basic settings */
        Route::get('/basic-settings', 'basic_settings')->name('admin.general.basic.settings');
        Route::post('/basic-settings', 'update_basic_settings');
        /* Page Settings */

        Route::get('/page-settings', 'page_settings')->name('admin.general.page.settings');
        Route::post('/page-settings', 'update_page_settings');
        /* site identity Settings */
        Route::get('/site-identity', 'site_identity')->name('admin.general.site.identity');
        Route::post('/site-identity', 'update_site_identity');

        /* Color Settings */
        Route::get('/color-settings', 'color_settings')->name('admin.general.color.settings');
        Route::post('/color-settings', 'update_color_settings');

        /* Typography Settings */
        Route::get('/typography-settings', 'typography_settings')->name('admin.general.typography.settings');
        Route::post('/typography-settings', 'update_typography_settings');
        Route::post('typography-settings/single', 'get_single_font_variant')->name('admin.general.typography.single');

        /* SEO Settings */
        Route::get('/seo-settings', 'seo_settings')->name('admin.general.seo.settings');
        Route::post('/seo-settings', 'update_seo_settings');

        /* Payment Settings (Static) */
        Route::get('/payment-settings', 'payment_settings')->name('admin.general.payment.settings');
        Route::post('/payment-settings', 'update_payment_settings');

        /* Third party scripts Settings */
        Route::get('/third-party-script-settings', 'third_party_script_settings')->name('admin.general.third.party.script.settings');
        Route::post('/third-party-script-settings', 'update_third_party_script_settings');

        /* smtp Settings */
        Route::get('/email-settings', 'email_settings')->name('admin.general.email.settings');
        Route::post('/email-settings', 'update_email_settings');

        /* custom css Settings */
        Route::get('/custom-css-settings', 'custom_css_settings')->name('admin.general.custom.css.settings');
        Route::post('/custom-css-settings', 'update_custom_css_settings');

        /* js css Settings */
        Route::get('/custom-js-settings', 'custom_js_settings')->name('admin.general.custom.js.settings');
        Route::post('/custom-js-settings', 'update_custom_js_settings');
        /* Cache  Settings */
        Route::get('/cache-settings', 'cache_settings')->name('admin.general.cache.settings');
        Route::post('/cache-settings', 'update_cache_settings');

        /* Licennse Upgrade Settings */
        Route::get('/license-settings', 'license_settings')->name('admin.general.license.settings');
        Route::post('/license-settings', 'update_license_settings');

    });

});


