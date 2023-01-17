@extends('landlord.admin.admin-master')
@section('title') {{__('All Custom Domain Requests')}} @endsection

@section('style')
    <x-datatable.css/>
@endsection

@section('title')
    {{__('All Custom Domain Requests')}}
@endsection

@section('content')

    <div class="col-lg-12 col-ml-12">
        <div class="row">
            <div class="col-12">
                <div class="card">
                                <div class="card-body">
                                    <x-error-msg/>
                                    <x-flash-msg/>
                                    <h4 class="header-title mb-4"> {{__('All Custom Domain Requests')}}</h4>

                                    <x-bulk-action permissions="package-order-delete"/>
                                    <div class="table-wrap table-responsive">
                                        <table class="table table-default table-striped table-bordered">
                                            <thead class="text-white" style="background-color: #b66dff">
                                            <tr>
                                                <th class="no-sort">
                                                    <div class="mark-all-checkbox">
                                                        <input type="checkbox" class="all-checkbox">
                                                    </div>
                                                </th>
                                                <th>{{__('ID')}}</th>
                                                <th>{{__('Username')}}</th>
                                                <th>{{__('Current Domain')}}</th>
                                                <th>{{__('Requested Domain')}}</th>
                                                <th>{{__('Custom Domain Status')}}</th>
                                                <th>{{__('Date')}}</th>
                                                <th>{{__('Action')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($domain_infos as $data)
                                                <tr>
                                                    <td>
                                                        <div class="bulk-checkbox-wrapper">
                                                            <input type="checkbox" class="bulk-checkbox" name="bulk_delete[]" value="{{$data->id}}">
                                                        </div>
                                                    </td>
                                                    <td>{{$data->id}}</td>
                                                    <td>{{optional($data->user)->username}}</td>
                                                    <td>{{optional($data)->old_domain .'.' . getenv('CENTRAL_DOMAIN')}}</td>
                                                    <td>{{$data->custom_domain}}</td>
                                                    <td class="py-4">
                                                        @if($data->custom_domain_status == 'pending')
                                                            <span class="alert alert-warning text-capitalize">{{$data->custom_domain_status}}</span>
                                                        @elseif($data->custom_domain_status == 'in_progress')
                                                            <span class="alert alert-info text-capitalize">{{$data->custom_domain_status}}</span>
                                                        @elseif($data->custom_domain_status == 'connected')
                                                            <span class="alert alert-success text-capitalize">{{$data->custom_domain_status}}</span>
                                                        @elseif($data->custom_domain_status == 'removed')
                                                            <span class="alert alert-danger text-capitalize">{{str_replace('_', ' ',ucwords($data->custom_domain_status))}}</span>
                                                        @elseif($data->custom_domain_status == 'rejected')
                                                            <span class="alert alert-info text-capitalize">{{str_replace('_', ' ',ucwords($data->custom_domain_status))}}</span>
                                                        @endif
                                                    </td>
                                                         <td>{{date('d-m-Y',strtotime($data->updated_at))}}</td>
                                                    <td>
                                                        <x-delete-popover permissions="package-order-delete" url="{{route('landlord.admin.custom.domain.request.delete', $data->id)}}"/>
                                                        <a href="#"
                                                           data-id="{{$data->id}}"
                                                           data-status="{{$data->custom_domain_status}}"
                                                           data-bs-toggle="modal"
                                                           data-bs-target="#custom_domain_status_modal"
                                                           class="btn btn-lg btn-primary btn-sm mb-3 mr-1 order_status_change_btn"
                                                        >
                                                            {{__("Update Status")}}

                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="custom_domain_status_modal" aria-hidden="true">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__('Custom Domain Status Change')}}</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>×</span></button>
                </div>

                <form action="{{route('landlord.admin.custom.domain.status.change')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="custom_domain_id" id="custom_domain_id">
                        <div class="form-group">
                            <label for="order_status">{{__('Custom Domain Status')}}</label>
                            <select name="custom_domain_status" class="form-control" id="custom_domain_status">
                                <option value="pending">{{__('Pending')}}</option>
                                <option value="in_progress">{{__('In Progress')}}</option>
                                <option value="connected">{{__('Connected')}}</option>
                                <option value="rejected">{{__('Rejected')}}</option>
                                <option value="removed">{{__('Removed')}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('Close')}}</button>
                        <button type="submit" class="btn btn-primary">{{__('Change Status')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <x-datatable.js/>
    <x-bulk-action-js :url="route('landlord.admin.custom.domain.bulk.action')"/>
    <script>
        (function($){
            "use strict";
            $(document).ready(function() {
            } );

            $(document).on('click', '.order_status_change_btn', function (e) {
                e.preventDefault();
                var el = $(this);
                var form = $('#custom_domain_status_modal');
                form.find('#custom_domain_id').val(el.data('id'));
                form.find('#custom_domain_status option[value="' + el.data('status') + '"]').attr('selected', true);
            });
        })(jQuery);
    </script>

@endsection

