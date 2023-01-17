<?php

use Modules\Product\Http\Controllers\CategoryController;
use Modules\Product\Http\Controllers\ProductController;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use Modules\Product\Http\Middleware\ProductLimitMiddleware;

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
                    PRODUCT MODULE
    ==============================================*/
    Route::prefix('product')->as("admin.product.")->group(function (){
        Route::controller(ProductController::class)->group(function (){
            Route::get("all",'index')->name("all");
            Route::get("create","create")->name("create");
            Route::post("create","store")->middleware(ProductLimitMiddleware::class);
            Route::post("status-update","update_status")->name("update.status");
            Route::get("update/{id}/{aria_name?}", "edit")->name("edit");
            Route::post("update/{id}", "update");
            Route::get("destroy/{id}", "destroy")->name("destroy");
            Route::get("clone/{id}", "clone")->name("clone")->middleware(ProductLimitMiddleware::class);
            Route::post("bulk/destroy", "bulk_destroy")->name("bulk.destroy");
            Route::get("search","productSearch")->name("search");

            Route::prefix('trash')->name('trash.')->group(function (){
                Route::get('/', 'trash')->name('all');
                Route::get('/restore/{id}', 'restore')->name('restore');
                Route::get('/delete/{id}', 'trash_delete')->name('delete');
                Route::post("/bulk/destroy", "trash_bulk_destroy")->name("bulk.destroy");
                Route::post("/empty", "trash_empty")->name("empty");
            });
        });
    });

    /*==============================================
                    Product Module Category Route
    ==============================================*/
    Route::prefix("category")->as("admin.category.")->group(function (){
        Route::controller(CategoryController::class)->group(function (){
            Route::post("category","getCategory")->name("all");
            Route::post("sub-category","getSubCategory")->name("sub-category");
            Route::post("child-category","getChildCategory")->name("child-category");
        });
    });
});
