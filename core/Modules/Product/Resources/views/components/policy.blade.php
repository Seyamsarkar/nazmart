<div class="general-info-wrapper px-3">
    <h4 class="dashboard-common-title-two">{{ __("Product Shipping and Return Policy") }}</h4>
    <div class="general-info-form mt-0 mt-lg-4">
        <div class="dashboard-input mt-4">
            <label class="dashboard-label color-light mb-2"> {{ __("Policy Description") }} </label>
            <textarea class="form--control summernote radius-10" name="policy_description" placeholder="{{ __("Type Description") }}">{!! isset($product) ? purify_html($product?->return_policy?->shipping_return_description) : "" !!}</textarea>
        </div>
    </div>
</div>
