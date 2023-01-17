@extends(route_prefix().'admin.admin-master')
@section('title')
    {{__('All Product Size')}}
@endsection
@section('style')
    <x-datatable.css />
    <x-bulk-action.css />
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row g-4">
            <div class="col-lg-12">
                <div class="margin-top-40">
                    <x-error-msg/>
                    <x-flash-msg/>
                </div>
            </div>
            <div class="col-xl-7 col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="header-wrap d-flex flex-wrap justify-content-between">
                            <h4 class="header-title mb-4">{{__('All Product Sizes')}}</h4>
                        </div>
                        @can('product-size-delete')
                        <x-bulk-action.dropdown />
                        @endcan
                        <div class="table-wrap table-responsive">
                            <table class="table table-default">
                                <thead>
                                <x-bulk-action.th />
                                <th>{{__('ID')}}</th>
                                <th>{{__('Name')}}</th>
                                <th>{{__('Size Code')}}</th>
                                <th>{{__('Slug')}}</th>
                                <th>{{__('Action')}}</th>
                                </thead>
                                <tbody>
                                @foreach($product_sizes as $product_size)
                                    <tr>
                                        <x-bulk-action.td :id="$product_size->id" />
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $product_size->name }}</td>
                                        <td>{{ $product_size->size_code }}</td>
                                        <td>{{ $product_size->slug }}</td>
                                        <td>
                                            @can('product-size-delete')
                                            <x-table.btn.swal.delete :route="route('tenant.admin.product.sizes.delete', $product_size->id)" />
                                            @endcan
                                            @can('product-size-edit')
                                            <a href="javascript:void(0)"
                                               data-bs-toggle="modal"
                                               data-bs-target="#size_edit_modal"
                                               class="btn btn-primary btn-xs mb-3 mr-1 size_edit_btn"
                                               data-id="{{ $product_size->id }}"
                                               data-name="{{ $product_size->name }}"
                                               data-size_code="{{ $product_size->size_code }}"
                                               data-slug="{{ $product_size->slug }}"
                                            >
                                                <i class="mdi mdi-pencil"></i>
                                            </a>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @can('product-size-create')
            <div class="col-xl-5 col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">{{__('New Size')}}</h4>
                        <form action="{{ route('tenant.admin.product.sizes.new') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="name">{{__('Name')}}</label>
                                <input type="text" class="form-control"  id="name" name="name" placeholder="{{__('Name')}}">
                            </div>
                            <div class="form-group">
                                <label for="size_code">{{__('Size Code')}}</label>
                                <input type="text" class="form-control"  id="size_code" name="size_code" placeholder="{{__('Size Code')}}">
                            </div>
                            <div class="form-group">
                                <label for="slug">{{__('Slug')}}</label>
                                <input type="text" class="form-control"  id="slug" name="slug" placeholder="{{__('Slug')}}">
                            </div>
                            <button class="btn btn-primary px-5 my-3">{{ __('Save Size') }}</button>
                        </form>
                    </div>
                </div>
            </div>
            @endcan
        </div>
    </div>
    @can('product-size-edit')
    <div class="modal fade" id="size_edit_modal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__('Edit Product Size')}}</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>×</span></button>
                </div>
                <form action="{{route('tenant.admin.product.sizes.update')}}"  method="post">
                    <input type="hidden" name="id" id="size_id">
                    <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <label for="edit_name">{{__('Name')}}</label>
                            <input type="text" class="form-control"  id="edit_name" name="name" placeholder="{{__('Name')}}">
                        </div>
                        <div class="form-group">
                            <label for="edit_size_code">{{__('size_code')}}</label>
                            <input type="text" class="form-control"  id="edit_size_code" name="size_code" placeholder="{{__('Size Code')}}">
                        </div>
                        <div class="form-group">
                            <label for="edit_slug">{{__('Slug')}}</label>
                            <input type="text" class="form-control"  id="edit_slug" name="slug" placeholder="{{__('Slug')}}">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">{{__('Close')}}</button>
                        <button type="submit" class="btn btn-primary">{{__('Save Change')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endcan
@endsection
@section('scripts')
    <x-datatable.js />
    <x-table.btn.swal.js/>
    @can('product-size-delete')
    <x-bulk-action.js :route="route('tenant.admin.product.sizes.bulk.action')" />
    @endcan
    <script>
        $(document).ready(function () {
            $(document).on('click','.size_edit_btn',function(){
                let el = $(this);
                let modal = $('#size_edit_modal');

                modal.find('#size_id').val(el.data('id'));
                modal.find('#edit_name').val(el.data('name'));
                modal.find('#edit_size_code').val(el.data('size_code'));
                modal.find('#edit_slug').val(el.data('slug'));
            });
        });
    </script>
@endsection
