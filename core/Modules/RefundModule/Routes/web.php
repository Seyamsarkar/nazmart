<?php

use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use Modules\RefundModule\Http\Controllers\RefundModuleController;
use Modules\RefundModule\Http\Controllers\RefundChatController;
use Modules\RefundModule\Http\Controllers\admin\AdminRefundChatController;
use Modules\RefundModule\Http\Controllers\admin\AdminRefundController;

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
    'tenant_glvar',
])->group(function () {
    Route::controller(RefundModuleController::class)->middleware(['tenantUserMailVerify','package_expire'])->name('tenant.')->group(function(){
        Route::get('request-refund/product', [RefundModuleController::class,'index'])->name('user.dashboard.package.order.refund');
        Route::post('request-refund/product', 'request_refund');
    });

    Route::controller(RefundChatController::class)->middleware(['tenantUserMailVerify','package_expire'])->name('tenant.')->group(function (){
        Route::get('refund/chat/list', 'chat_list')->name('user.dashboard.refund.chat.list');
        Route::post('refund/chat/list', 'chat_store');
        Route::get('refund/chat/list/{id}', 'message_show')->name('user.dashboard.chat.message.show');
        Route::post('refund/chat/list/{id}', 'refund_request_message');
    });
});


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
    Route::controller(AdminRefundChatController::class)->prefix('refund-chat')->group(function () {
        Route::get('/', 'all_chats')->name('admin.refund.chat.all');
        Route::get('/new', 'new_chat')->name('admin.refund.chat.new');
        Route::post('/new', 'store_chat');
        Route::post('/delete/{id}', 'delete')->name('admin.refund.chat.delete');
        Route::get('/view/{id}', 'views')->name('admin.refund.chat.view');
        Route::post('/bulk-action', 'bulk_action')->name('admin.refund.chat.bulk.action');
        Route::post('/priority-change', 'priority_change')->name('admin.refund.chat.priority.change');
        Route::post('/status-change', 'status_change')->name('admin.refund.chat.status.change');
        Route::post('/send message', 'send_message')->name('admin.refund.chat.send.message');
    });

    Route::controller(AdminRefundController::class)->prefix('refund')->group(function () {
        Route::get('/', 'index')->name('admin.refund.all');
        Route::post('/status-change', 'status_change')->name('admin.refund.status.change');
        Route::get('/show/{id}', 'product_view')->name('admin.refund.product.show');
    });
});

