<?php

namespace Database\Seeders\Tenant;

use App\Mail\TenantCredentialMail;
use App\Models\Admin;
use App\Models\Language;
use App\Models\Menu;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;

class RolePermissionSeed extends Seeder
{
    public function run()
    {
        $permissions = [
            'page-list',
            'page-create',
            'page-edit',
            'page-delete',

            'product-order-all-order',
            'product-order-pending-order',
            'product-progress-order',
            'product-order-complete',
            'product-order-success-page',
            'product-order-cancel-page',
            'product-order-page-manage',
            'product-order-report',
            'product-order-payment-logs',
            'product-order-payment-report',
            'product-order-manage-settings',

            'badge-list',
            'badge-create',
            'badge-edit',
            'badge-delete',

            'country-list',
            'country-create',
            'country-edit',
            'country-delete',
            'state-list',
            'state-create',
            'state-edit',
            'state-delete',

            'country-tax-list',
            'country-tax-create',
            'country-tax-edit',
            'country-tax-delete',
            'state-tax-list',
            'state-tax-create',
            'state-tax-edit',
            'state-tax-delete',

            'shipping-method-list',
            'shipping-method-create',
            'shipping-method-edit',
            'shipping-method-delete',
            'shipping-method-make',
            'shipping-zone-list',
            'shipping-zone-create',
            'shipping-zone-edit',
            'shipping-zone-delete',

            'product-coupon-list',
            'product-coupon-create',
            'product-coupon-edit',
            'product-coupon-delete',

            'product-category-list',
            'product-category-create',
            'product-category-edit',
            'product-category-delete',
            'product-sub-category-list',
            'product-sub-category-create',
            'product-sub-category-edit',
            'product-sub-category-delete',
            'product-child-category-list',
            'product-child-category-create',
            'product-child-category-edit',
            'product-child-category-delete',
            'product-tag-list',
            'product-tag-create',
            'product-tag-edit',
            'product-tag-delete',
            'product-unit-list',
            'product-unit-create',
            'product-unit-edit',
            'product-unit-delete',
            'product-color-list',
            'product-color-create',
            'product-color-edit',
            'product-color-delete',
            'product-size-list',
            'product-size-create',
            'product-size-edit',
            'product-size-delete',
            'product-delivery-option-list',
            'product-delivery-option-create',
            'product-delivery-option-edit',
            'product-delivery-option-delete',
            'product-attribute-list',

            'product-list',
            'product-create',
            'product-edit',
            'product-delete',
            'product-settings',

            'campaign-list',
            'campaign-create',
            'campaign-edit',
            'campaign-delete',
            'campaign-settings',

            'inventory-list',
            'inventory-create',
            'inventory-edit',
            'inventory-delete',
            'inventory-settings',

            'refund-chat-list',
            'refund-chat-create',
            'refund-chat-edit',
            'refund-chat-delete',

            'testimonial-list',
            'testimonial-create',
            'testimonial-edit',
            'testimonial-delete',

            'brand-list',
            'brand-create',
            'brand-edit',
            'brand-delete',

            'blog-category-list',
            'blog-category-create',
            'blog-category-edit',
            'blog-category-delete',

            'blog-list',
            'blog-create',
            'blog-edit',
            'blog-delete',
            'blog-settings',
            'blog-comments',

            'form-builder',
            'widget-builder',

            'general-settings-page-settings',
            'general-settings-global-navbar-settings',
            'general-settings-global-footer-settings',
            'general-settings-site-identity',
            'general-settings-basic-settings',
            'general-settings-color-settings',
            'general-settings-typography-settings',
            'general-settings-seo-settings',
            'general-settings-payment-settings',
            'general-settings-third-party-script-settings',
            'general-settings-smtp-settings',
            'general-settings-custom-css-settings',
            'general-settings-custom-js-settings',
            'general-settings-database-upgrade-settings',
            'general-settings-cache-clear-settings',
            'general-settings-license-settings',

            'language-list',
            'language-create',
            'language-edit',
            'language-delete',

            'menu-manage',

            'newsletter-list',
            'newsletter-create',
            'newsletter-edit',
            'newsletter-delete',

            'support-ticket-list',
            'support-ticket-create',
            'support-ticket-edit',
            'support-ticket-delete',
            'support-ticket-department-list',
            'support-ticket-department-create',
            'support-ticket-department-edit',
            'support-ticket-department-delete',
        ];

        foreach ($permissions as $permission){
            \Spatie\Permission\Models\Permission::updateOrCreate(['name' => $permission,'guard_name' => 'admin']);
       }

        $demo_permissions = [];
        $role = Role::create(['name' => 'Super Admin','guard_name' => 'admin']);
        $role->syncPermissions($demo_permissions);
    }
}