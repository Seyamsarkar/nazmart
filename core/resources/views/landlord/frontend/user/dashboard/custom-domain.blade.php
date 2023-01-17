@extends('landlord.frontend.user.dashboard.user-master')
@section('title')
    {{__('Custom Domain')}}
@endsection

@section('page-title')
    {{__('Custom Domain')}}
@endsection

@section('section')
    @php
        $central_domain = getenv('CENTRAL_DOMAIN');
    @endphp
<div class="parent">
    <div class="row">
        <div class="col-12">
            <div class="card card-new-styles">
                <div class="card-header">
                    <h3 class="text-center">{{get_static_option_central('custom_domain_settings_title')}}</h3>
                </div>

                <div class="card-body">
                    <p class="custom_domain_para">{{get_static_option_central('custom_domain_settings_description')}}</p>

                    <h5 class="custom_domain_title_two mt-4">{{get_static_option_central('custom_domain_table_title')}}</h5>
                    <div class="custom_domain_table mt-4">
                        <table class="table table-default table-striped table-bordered">
                        <thead class="text-white bg-dark">
                        <tr>
                            <th>{{__('Type')}}</th>
                            <th>{{__('Host')}}</th>
                            <th>{{__('Value')}}</th>
                            <th>{{__('TTL')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>{{__('CNAME Record')}}</td>
                            <td>www</td>
                            <td>{{getenv('CENTRAL_DOMAIN')}}</td>
                            <td>{{__('Automatic')}}</td>
                        </tr>

                        <tr>
                            <td>{{__('CNAME Record')}}</td>
                            <td>@</td>
                            <td>{{getenv('CENTRAL_DOMAIN')}}</td>
                            <td>{{__('Automatic')}}</td>
                        </tr>

                        <tr>
                            <td colspan="4">{{__('Use this if you are using cloulflare')}}</td>
                        </tr>
                        <tr>
                            <td>A Record</td>
                            <td>@</td>
                            <td>{{$_SERVER['SERVER_ADDR']}}</td>
                            <td>Automatic</td>
                        </tr>
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-body">
                            <div class="card-body">
                                <x-admin.header-wrapper>
                                    <x-slot name="left">
                                        <h4 class="card-title mb-4">{{__('Custom Domain Request')}}</h4>
                                    </x-slot>
                                    <x-slot name="right" class="d-flex">
                                        <button class="btn btn-info btn-sm mb-3" data-bs-toggle="modal" data-bs-target="#new_custom_domain">{{__('Request Custom Domain')}}</button>
                                    </x-slot>
                                </x-admin.header-wrapper>

                                <div class="table-wrap custom_domain_table">
                                    <table class="table table-default table-striped table-bordered">
                                        <thead class="text-white" style="background-color: #b66dff">
                                        <tr>
                                            <th>{{__('Current Domain')}}</th>
                                            <th>{{__('Requested Domain')}}</th>
                                            <th>{{__('Requested Domain Status')}}</th>
                                            <th>{{__('Date')}}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($user_domain_infos->tenant_details ?? [] as $tenant)
                                                <tr>
                                                <td>{{$tenant->id . '.'. getenv('CENTRAL_DOMAIN')}}</td>
                                                <td>{{optional($tenant->custom_domain)->custom_domain}}</td>
                                                <td class="py-4">
                                                    @if(optional($tenant->custom_domain)->custom_domain_status == 'pending')
                                                        <span class="alert alert-warning text-capitalize">{{optional($tenant->custom_domain)->custom_domain_status}}</span>
                                                    @elseif(optional($tenant->custom_domain)->custom_domain_status == 'in_progress')
                                                        <span class="alert alert-info text-capitalize">{{ str_replace('_',' ',optional($tenant->custom_domain)->custom_domain_status) }}</span>
                                                    @elseif(optional($tenant->custom_domain)->custom_domain_status == 'connected')
                                                        <span class="alert alert-success text-capitalize">{{optional($tenant->custom_domain)->custom_domain_status}}</span>
                                                    @elseif(optional($tenant->custom_domain)->custom_domain_status == 'rejected')
                                                        <span class="alert alert-info text-capitalize">{{str_replace('_', ' ',ucwords(optional($tenant->custom_domain)->custom_domain_status))}}</span>
                                                    @elseif(optional($tenant->custom_domain)->custom_domain_status == null)

                                                    @else
                                                        <span class="alert alert-danger text-capitalize">{{optional($tenant->custom_domain)->custom_domain_status ?? __('Removed')}}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if(!empty($tenant->custom_domain))
                                                        {{date('d-m-Y',strtotime(optional($tenant->custom_domain)->updated_at))}}
                                                    @endif
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
 </div>

    <div class="modal fade" id="new_custom_domain" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__('Request Custom Domain')}}</h5>
                    <button type="button" class="close" data-bs-dismiss="modal"><span>×</span></button>
                </div>
                <form action="{{route('landlord.user.dashboard.custom.domain')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">

                        <div class="alert-alert-warning">
                            {{__('You already have a custom domain ('.$central_domain.') connected with your portfolio website.
                                if you request another domain now & if it gets connected with our server, then your current domain ('.$central_domain.') will be removed')
                             }}
                        </div>

                        <input type="hidden" name="user_id" value="{{$user_domain_infos->id}}">

                        <div class="form-group my-3">
                            @php
                                $domain_list = optional($user_domain_infos)->tenant_details;
                            @endphp
                            <label for="name">{{__('Select your domain')}}</label>
                            <select class="form-control" name="old_domain" id="">
                                <option value="">Select a domain</option>
                                @foreach($domain_list as $domain)
                                    <option value="{{$domain->id}}">{{$domain->id}}</option>
                                @endforeach
                            </select>
                            <small>{{__('Select the domain which you want to change')}}</small>
                        </div>

                        <div class="form-group my-3">
                            <label for="name">{{__('Enter your custom domain')}}</label>
                            <input type="text" class="form-control" name="custom_domain" value="{{$custom_domain_info->custom_domain ?? ''}}">
                            <div id="subdomain-wrap"></div>
                        </div>

                        <div class="form-group">
                            {{sprintf(__('Do not use http:// or https://
                              The valid format will be exactly like this one - domain.tld, domain.tld or subdomain.domain.tld, subdomain.domain.tld'))}}
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('Close')}}</button>
                        <button type="submit" class="btn btn-primary">{{__('Send Request')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        $('.close-bars, .body-overlay').on('click', function() {
            $('.dashboard-close, .dashboard-close-main, .body-overlay').removeClass('active');
        });
        $('.sidebar-icon').on('click', function() {
            $('.dashboard-close, .dashboard-close-main, .body-overlay').addClass('active');
        });

        $(function () {
            $(document).ready(function ($) {
                "use strict";

                function removeTags(str) {
                    if ((str === null) || (str === '')) {
                        return false;
                    }
                    str = str.toString();
                    return str.replace(/(<([^>]+)>)/ig, '');
                }

                $(document).on('keyup paste change', 'input[name="custom_domain"]', function (e) {

                    var value = '';
                    if ($(this).val() != '') {
                        value = removeTags($(this).val()).toLowerCase().replace(/\s/g, "-");
                        $(this).val(value)
                    }

                    if (value.length < 1) {
                        $('#subdomain-wrap').html('');
                        return;
                    }
                    let msgWrap = $('#subdomain-wrap');
                    msgWrap.html('');
                    msgWrap.append('<span class="text-warning">{{__('availability checking..')}}</span>');

                    axios({
                        url: "{{route('landlord.subdomain.check')}}",
                        method: 'post',
                        responseType: 'json',
                        data: {
                            subdomain: value
                        }
                    }).then(function (res) {
                        msgWrap.html('');
                        msgWrap.append('<span class="text-success"> ' + value + ' {{__('is available')}}</span>');
                        $('#login_button').attr('disabled', false)
                    }).catch(function (error) {
                        var responseData = error.response.data.errors;
                        msgWrap.html('');
                        msgWrap.append('<span class="text-danger"> ' + responseData.subdomain + '</span>');
                        $('#login_button').attr('disabled', true)
                    });
                });
            });
        });
    </script>
@endsection

