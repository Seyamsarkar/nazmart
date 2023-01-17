@php
    $route_name = is_null(tenant()) ? 'landlord' : 'tenant';
@endphp
@extends($route_name.'.admin.admin-master')
@section('title') {{__('Languages')}} @endsection
@section('style')

@endsection
@section('content')
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="heading-wrap d-flex justify-content-between">
                    <h4 class="card-title mb-5">{{__('All Languages')}}</h4>
                    <div class="btn-wrapper">
                        <x-modal.button type="primary" target="addNewLanguage">{{__('Add New')}}</x-modal.button>
                   </div>
                </div>
                <x-error-msg/>
                <x-flash-msg/>
                <x-datatable.table>
                    <x-slot name="th">
                        <th>{{__('Name')}}</th>
                        <th>{{__('Direction')}}</th>
                        <th>{{__('Status')}}</th>
                        <th>{{__('Default')}}</th>
                        <th>{{__('Action')}}</th>
                    </x-slot>
                    <x-slot name="tr">
                        @foreach($all_lang as $data)
                            <tr>
                                <td>{{$data->name}}</td>
                                <td>{{$data->direction === 0 ? __('LTR') : __('RTL')}}</td>
                                <td>{{$data->status === 0 ? __('Draft') : __('Publish')}}</td>
                                <td>
                                    @if($data->default == 1)
                                        <a href="javascript:void(0)"
                                           class="btn btn-xs btn-success btn-sm mb-3 mr-1">{{__("Default")}}</a>
                                    @else
                                        <x-change-default-lang :url="route($route_name.'.admin.languages.default',$data->id)"/>
                                    @endif
                                </td>
                                <td>
                                    @if($data->default != 1)
                                        <x-delete-popover :url="route($route_name.'.admin.languages.delete',$data->id)"/>
                                    @endif
                                    <a href="{{route($route_name.'.admin.languages.words.admin',$data->slug)}}"
                                       title="{{__('Edit Admin Panel Words')}}"
                                       class="btn btn-secondary btn-xs mb-3 mr-1" style="color: #fff;">
                                        <i class="mdi mdi-pencil-box-outline"></i> {{__('Edit Admin Words')}}
                                    </a>
                                    <a href="{{route($route_name.'.admin.languages.words.frontend',$data->slug)}}"
                                       title="{{__('Edit Frontend Words')}}" class="btn btn-info btn-xs mb-3 mr-1"
                                       style="color: #fff;">
                                        <i class="mdi mdi-pencil-box-outline"></i> {{__('Edit Frontend Words')}}
                                    </a>
                                    <a href="#"
                                       data-bs-toggle="modal"
                                       data-bs-target="#EditLanguage"
                                       class="btn btn-primary btn-xs mb-3 mr-1 lang_edit_btn"
                                       data-id="{{$data->id}}"
                                       data-name="{{$data->name}}"
                                       data-slug="{{$data->slug}}"
                                       data-status="{{$data->status}}"
                                       data-direction="{{$data->direction}}"
                                    >
                                        <i class="mdi mdi-pencil-box-outline"></i>
                                    </a>
                                    <a href="#"
                                       data-bs-toggle="modal"
                                       data-bs-target="#language_item_clone_modal"
                                       class="btn btn-dark btn-xs mb-3 mr-1 lang_clone_btn"
                                       data-id="{{$data->id}}"
                                    >
                                        <i class="mdi mdi-content-copy"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </x-slot>
                </x-datatable.table>

            </div>
        </div>
    </div>

    <x-modal.markup target="addNewLanguage" title="Add New Language">
        <form class="forms-sample" method="post" action="{{route($route_name.'.admin.languages.new')}}">
            @csrf
            <input type="hidden" name="slug">
            <x-fields.language-select/>
            <x-fields.select name="status" title="{{__('Status')}}">
                <option value="1">{{__('Publish')}}</option>
                <option value="0">{{__("Draft")}}</option>
            </x-fields.select>
            <x-fields.select name="direction" title="{{__('Direction')}}">
                <option value="0">{{__('LTR')}}</option>
                <option value="1">{{__("RTL")}}</option>
            </x-fields.select>
            <button type="submit" class="btn btn-primary">{{__('Save Changes')}}</button>
        </form>
    </x-modal.markup>

    <x-modal.markup target="EditLanguage" title="Edit Language">
        <form class="forms-sample" method="post" action="{{route($route_name.'.admin.languages.update')}}">
            @csrf
            <input type="hidden" name="id" id="lang_id">
            <input type="hidden" name="slug">
            <x-fields.language-select/>
            <x-fields.select name="status" title="{{__('Status')}}">
                <option value="1">{{__('Publish')}}</option>
                <option value="0">{{__("Draft")}}</option>
            </x-fields.select>
            <x-fields.select name="direction" title="{{__('Direction')}}">
                <option value="0">{{__('LTR')}}</option>
                <option value="1">{{__("RTL")}}</option>
            </x-fields.select>
            <button type="submit" class="btn btn-primary">{{__('Save Changes')}}</button>
        </form>
    </x-modal.markup>
    <x-modal.markup target="language_item_clone_modal" title="Clone Language">
        <form class="forms-sample" method="post" action="{{route($route_name.'.admin.languages.clone')}}">
            @csrf
            <input type="hidden" name="id">
            <input type="hidden" name="slug">
            <x-fields.language-select/>
            <x-fields.select name="status" title="{{__('Status')}}">
                <option value="1">{{__('Publish')}}</option>
                <option value="0">{{__("Draft")}}</option>
            </x-fields.select>
            <x-fields.select name="direction" title="{{__('Direction')}}">
                <option value="0">{{__('LTR')}}</option>
                <option value="1">{{__("RTL")}}</option>
            </x-fields.select>
            <button type="submit" class="btn btn-primary">{{__('Save Changes')}}</button>
        </form>
    </x-modal.markup>
@endsection
@section('scripts')
    <script>
        $(document).ready(function(){
            "use strict";

            $(document).on('change', 'select[name="language_select"]', function () {
                var el = $(this);
                var name = el.parent().find('select[name="language_select"] option[value="'+el.val()+'"]' ).text()
                el.parent().find('input[name="name"]').val(name)
                el.parent().parent().find('input[name="slug"]').val(el.val())
            });

            $(document).on('click', '.lang_edit_btn', function () {
                var el = $(this);
                var id = el.data('id');
                var name = el.data('name');
                var slug = el.data('slug');
                var form = $('#EditLanguage');
                form.find('#lang_id').val(id);
                form.find('input[name="name"]').val(name);
                form.find('select[name="language_select"] option[value="'+slug+'"]').attr('selected',true);
                form.find('input[name="slug"]').val(slug);
                form.find('select[name="direction"] option[value="' + el.data('direction') + '"]').prop('selected', true);
                form.find('select[name="status"] option[value="' + el.data('status') + '"]').prop('selected', true);
            });
            $(document).on('click', '.lang_clone_btn', function () {
                var el = $(this);
                var id = el.data('id');
                var form = $('#language_item_clone_modal');
                form.find('input[name="id"]').val(id);
            });
        })
    </script>
@endsection
