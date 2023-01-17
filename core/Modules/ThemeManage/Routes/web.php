<?php

use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use Modules\ThemeManage\Http\Controllers\ThemeManageController;

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
    'auth:admin',
    'tenant_admin_glvar',
    'package_expire',
    'tenantAdminPanelMailVerify'
])->prefix('admin-home')->name('tenant.')->group(function () {
    /*==============================================
                    THEME MANAGE MODULE
    ==============================================*/

    Route::prefix('themes')->name('admin.theme.')->controller(ThemeManageController::class)->group(function (){
        Route::get('/', 'index')->name('all');
        Route::post('/update/{slug}', 'update')->name('update');
    });
});
