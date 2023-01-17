@extends(route_prefix().'admin.admin-master')
@section('title')
    {{__('Order Success Page Settings')}}
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row g-4">
            <div class="col-12">
                <x-error-msg/>
                <x-flash-msg/>
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-4">{{__("Order Success Page Settings")}}</h4>
                        <form action="{{route(route_prefix().'admin.product.order.success.page')}}" method="POST"
                              enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="site_order_success_page_title">{{__('Main Title')}}</label>
                                <input type="text" name="site_order_success_page_title"
                                       class="form-control"
                                       value="{{get_static_option('site_order_success_page_title')}}"
                                       id="site_order_success_page_title">
                            </div>
                            <div class="form-group">
                                <label
                                    for="site_order_success_page_description">{{__('Description')}}</label>
                                <textarea name="site_order_success_page_description"
                                          class="form-control" id="site_order_success_page_description"
                                          cols="30"
                                          rows="10">{{get_static_option('site_order_success_page_description')}}</textarea>
                            </div>
                            <button type="submit"
                                    class="btn btn-primary mt-4 pr-4 pl-4">{{__('Update Changes')}}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
