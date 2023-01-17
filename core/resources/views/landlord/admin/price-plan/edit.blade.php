@extends(route_prefix().'admin.admin-master')
@section('title')
    {{__('Edit Price Plan')}}
@endsection

@section('style')

    <style>
        .all-field-wrap .action-wrap {
            position: absolute;
            right: 0;
            top: 0;
            background-color: #f2f2f2;
            height: 100%;
            width: 60px;
            text-align: center;
            display: flex;
            justify-content: center;
            flex-direction: column;
            align-items: center;
        }

        .f_desc {
            height: 100px;
        }

        small {
            font-size: 12px;
            color: #b66dff;
        }
        .price_plan_info{
            cursor: pointer;
        }
    </style>

@endsection

@section('content')

    @php
        $features = ['products','pages','blog','storage',  'inventory', 'campaign'];
    @endphp

    <div class="col-12 stretch-card">
        <div class="card">
            <div class="card-body">
                <x-admin.header-wrapper>
                    <x-slot name="left">
                        <h4 class="card-title mb-4">{{__('Edit Price Plan')}}</h4>
                    </x-slot>
                    <x-slot name="right" class="d-flex">
                        <x-link-with-popover permissions="price-plan-list"
                                             url="{{route(route_prefix().'admin.price.plan')}}" extraclass="ml-3">
                            {{__('All Price Plan')}}
                        </x-link-with-popover>
                        <x-link-with-popover permissions="price-plan-create" class="secondary"
                                             url="{{route(route_prefix().'admin.price.plan.create')}}"
                                             extraclass="ml-3">
                            {{__('Create Price Plan')}}
                        </x-link-with-popover>
                    </x-slot>
                </x-admin.header-wrapper>

                <x-error-msg/>
                <x-flash-msg/>

                <form class="forms-sample" method="post" action="{{route(route_prefix().'admin.price.plan.update')}}">
                    @csrf
                    <x-fields.input type="hidden" name="id" value="{{$plan->id}}"/>

                    <x-fields.input name="title" label="{{__('Title')}}"
                                    value="{{$plan->title}}"/>

                    <x-fields.input name="package_badge" label="{{__('Package Badge')}}"
                                    value="{{$plan->package_badge}}"/>

                    @if(tenant())
                        <x-fields.textarea name="features" value="{{$plan->getTranslation('features',$lang_slug)}}"
                                           label="{{__('Features')}}"
                                           info="{{__('separate new feature by new line, add {close} for (x) icon add {check} for check icon')}}"/>
                    @endif

                    @if(!tenant())
                        <div class="form-group landlord_price_plan_feature">
                            <h4>{{__('Select Features')}}</h4>
                            <div class="feature-section">
                                <ul>
                                    @foreach($features as $key => $feat)
                                        <li class="d-inline">
                                            <input type="checkbox" name="features[]" id="{{$key}}" class="exampleCheck1"
                                                   value="{{$feat}}"

                                            @foreach($plan->plan_features as $feat_old)
                                                {{$feat_old->feature_name == $feat ? 'checked' : ''}}
                                                @endforeach
                                            >
                                            <label class="ml-1"
                                                   for="{{$key}}">{{str_replace('_', ' ', ucfirst($feat))}}</label>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>

                        <div class="form-group page_permission_box">
                            <label for="">{{__('Page Create Permission')}} <i class="mdi mdi-information-outline text-primary price_plan_info" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Keep -1 for Unlimited')}}"></i></label>
                            <input type="text" min="-1" class="form-control" name="page_permission_feature"
                                   value="{{$plan->page_permission_feature}}">
                            <small>{{__('Page limit')}}</small>
                        </div>

                        <div class="form-group blog_permission_box">
                            <label for="">{{__('Blog Create Permission')}} <i class="mdi mdi-information-outline text-primary price_plan_info" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Keep -1 for Unlimited')}}"></i></label>
                            <input type="text" min="-1" class="form-control" name="blog_permission_feature"
                                   value="{{$plan->blog_permission_feature}}">
                            <small>{{__('Blog limit')}}</small>
                        </div>

                        <div class="form-group product_permission_box">
                            <label for="">{{__('Product Create Permission')}} <i class="mdi mdi-information-outline text-primary price_plan_info" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Keep -1 for Unlimited')}}"></i></label>
                            <input type="text" min="-1" class="form-control" name="product_permission_feature"
                                   value="{{$plan->product_permission_feature}}">
                            <small>{{__('Product limit')}}</small>
                        </div>

                        <div class="form-group storage_permission_box">
                            <label for="">{{__('Storage Create Permission')}} <i class="mdi mdi-information-outline text-primary price_plan_info" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Keep -1 for Unlimited')}}"></i></label>
                            <input type="text" min="-1" class="form-control" name="storage_permission_feature"
                                   value="{{$plan->storage_permission_feature}}">
                            <small>{{__('Storage limit (MB)')}}</small>
                        </div>

                        <x-fields.select name="type" title="{{__('Type')}}">
                            <option @if($plan->type === \App\Enums\PricePlanTypEnums::MONTHLY) selected
                                    @endif value="0">{{__('Monthly')}}</option>
                            <option @if($plan->type === \App\Enums\PricePlanTypEnums::YEARLY) selected
                                    @endif value="1">{{__('Yearly')}}</option>
                            <option @if($plan->type === \App\Enums\PricePlanTypEnums::LIFETIME) selected
                                    @endif value="2">{{__('Lifetime')}}</option>
                        </x-fields.select>

                        <div class="d-flex justify-content-start">
                            <x-fields.switcher name="has_trial" label="{{__('Free Trial')}}"
                                               value="{{$plan->has_trial}}"/>

                            <div class="form-group trial_date_box mx-4">
                                <label for="">{{__('Trial Days')}}</label>
                                <input type="number" class="form-control" name="trial_days" placeholder="Days.."
                                       value="{{$plan->trial_days}}">
                            </div>
                        </div>
                    @endif

                    <x-fields.input type="number" name="price" label="{{__('Price')}}" value="{{$plan->price}}"/>
                    @if($plan->price == 0)
                        <small
                            class="price_warning text-danger my-0">{{__('Zero price will increase the possibility of spamming')}}</small>
                    @endif

                    <x-fields.select name="status" title="{{__('Status')}}">
                        <option @if($plan->status === \App\Enums\StatusEnums::PUBLISH) selected
                                @endif value="{{\App\Enums\StatusEnums::PUBLISH}}">{{__('Publish')}}</option>
                        <option @if($plan->status === \App\Enums\StatusEnums::DRAFT) selected
                                @endif value="{{\App\Enums\StatusEnums::DRAFT}}">{{__('Draft')}}</option>
                    </x-fields.select>


                    @if(!tenant())
                        <div class="iconbox-repeater-wrapper">
                            @php
                                $faq_items = !empty($plan->faq) ? unserialize($plan->faq,['class' => false]) : ['title' => ['']];
                            @endphp
                            @forelse($faq_items['title'] as $faq)
                                <div class="all-field-wrap">
                                    <div class="form-group">
                                        <label for="faq">{{__('Faq Title')}}</label>
                                        <input type="text" name="faq[title][]" class="form-control" value="{{$faq}}">
                                    </div>
                                    <div class="form-group">
                                        <label for="faq_desc">{{__('Faq Description')}}</label>
                                        <textarea name="faq[description][]"
                                                  class="form-control f_desc">{{$faq_items['description'][$loop->index] ?? ''}}</textarea>
                                    </div>
                                    <div class="action-wrap">
                                        <span class="add"><i class="las la-plus"></i></span>
                                        <span class="remove"><i class="las la-trash"></i></span>
                                    </div>
                                </div>
                            @empty
                                <div class="all-field-wrap">
                                    <div class="form-group">
                                        <label for="faq">{{__('Faq Title')}}</label>
                                        <input type="text" name="faq[title][]" class="form-control"
                                               placeholder="{{__('faq title')}}">
                                    </div>
                                    <div class="form-group">
                                        <label for="faq_desc">{{__('Faq Description')}}</label>
                                        <textarea name="faq[description][]" class="form-control f_desc"
                                                  placeholder="{{__('faq description')}}"></textarea>
                                    </div>
                                    <div class="action-wrap">
                                        <span class="add"><i class="ti-plus"></i></span>
                                        <span class="remove"><i class="ti-trash"></i></span>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    @endif


                    <button type="submit" class="btn btn-gradient-primary me-2 mt-5">{{__('Save Changes')}}</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')

    <script>
        //Date Picker
        flatpickr('.date', {
            enableTime: false,
            dateFormat: "d-m-Y",
            minDate: "today"
        });
        $(document).on('change', 'select[name="lang"]', function (e) {
            $(this).closest('form').trigger('submit');
            $('input[name="lang"]').val($(this).val());
        });

        let page_permission = '{{$plan->page_permission_feature}}';
        let blog_permission = '{{$plan->blog_permission_feature}}';
        let product_permission = '{{$plan->product_permission_feature}}';
        let storage_permission = '{{$plan->storage_permission_feature}}';

        if (page_permission != '') {
            $('.page_permission_box').removeClass('d-none');
        }

        if (blog_permission != '') {
            $('.blog_permission_box').removeClass('d-none');
        }

        if (product_permission != '') {
            $('.product_permission_box').removeClass('d-none');
        }

        if (storage_permission != '') {
            $('.storage_permission_box').removeClass('d-none');
        }

        let trial_days = '{{$plan->trial_days}}';
        if (trial_days != '') {
            $('.trial_date_box').show();
        } else {
            $('.trial_date_box').hide();
        }

        $(document).on('change', 'input[name=has_trial]', function (e) {
            let el = $(this).val();

            $('.trial_date_box').toggle(500);
        });

        $(document).on('keyup', 'input[name=price]', function () {
            let price = $(this);
            let price_val = $.trim(price.val());
            let message = '{{__('Zero price will increase the possibility of spamming')}}';

            $('.price_warning').remove();
            if (price_val != '' && price_val == 0) {
                toastr.error(message, 'Waring');
                price.after('<small class="price_warning text-danger my-0">' + message + '</small>')
            }
        });

        $(document).on('change', '.exampleCheck1', function (e) {
            let feature = $('.exampleCheck1').data('feature');
            let el = $(this).val();

            if (el == 'pages') {
                let page = $('.page_permission_box');
                if (el == 'pages' && this.checked) {
                    page.slideDown();
                } else {
                    page.slideUp();
                    page.find('input').val('');
                }
            }


            if (el == 'blog') {
                let blog = $('.blog_permission_box');
                if (el == 'blog' && this.checked) {
                    blog.slideDown();
                } else {
                    blog.slideUp();
                    blog.find('input').val('');
                }

            }


            if (el == 'products') {
                let product = $('.product_permission_box');
                if (el == 'products' && this.checked) {
                    product.slideDown();
                } else {
                    product.slideUp();
                    product.find('input').val('');
                }

            }

            if (el == 'storage') {
                let storage = $('.storage_permission_box');
                if (el == 'storage' && this.checked) {
                    storage.slideDown();
                } else {
                    storage.slideUp();
                    storage.find('input').val('');
                }

            }

        });
    </script>
    <x-repeater/>
@endsection
