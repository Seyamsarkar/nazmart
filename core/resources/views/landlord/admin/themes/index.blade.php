@extends(route_prefix().'admin.admin-master')
@section('title')
    {{__('All Themes')}}
@endsection

@section('style')
    <x-datatable.css/>

    <style>
        .modal-image {
            width: 100%;
        }
    </style>
@endsection

@section('content')
    <div class="col-12 stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <x-admin.header-wrapper>
                        <x-slot name="left">
                            <h4 class="card-title mb-4">{{__('All Themes')}}</h4>
                        </x-slot>
                        <x-slot name="right" class="d-flex">
                        </x-slot>
                    </x-admin.header-wrapper>
                    <x-error-msg/>
                    <x-flash-msg/>
                </div>

                <div class="row g-4">
                    @foreach($all_themes as $theme)
                        @break($loop->iteration > 2)
                        @php
                            $status = $theme->status == 1 ? 'inactive' : 'active';
                        @endphp
                        <div class="col-xl-3 col-md-6">
                            <div class="themePreview">
                                <a href="javascript:void(0)" id="theme-preview" data-bs-target="#theme-modal"
                                   data-bs-toggle="modal"
                                   data-id="{{$theme->id}}"
                                   data-title="{{$theme->title}}"
                                   data-description="{{$theme->description}}"
                                   data-image="{{get_theme_image($theme->slug)}}"
                                   data-button_text="{{$status}}"
                                   data-url="{{$theme->theme_url ?? ''}}"
                                   class="theme-preview"
                                >
                                    <div class="bg"
                                         style="background-image: url('{{get_theme_image($theme->slug)}}');"></div>
                                </a>

                                <div class="themeInfo themeInfo_{{$theme->id}}" data-id="{{$theme->id}}">
                                    <h3 class="themeName text-center"></h3>
                                </div>

                                <div class="themeLink">
                                    <h3 class="themeName">{{$theme->title}}</h3>
                                    <a href="javascript:void(0)"
                                       class="active-btn text-capitalize theme_status_update_button"
                                       data-id="{{$theme->id}}"
                                       data-status="{{$status}}"
                                    >{{$status}}</a>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    @if(get_static_option('up_coming_themes_backend'))
                        @foreach(range(3, 12) as $item)
                            <div class="col-xl-3 col-md-6">
                                <div class="themePreview coming_soon">
                                    <a href="javascript:void(0)" id="theme-preview"
                                       data-bs-toggle="modal"
                                       class="theme-preview">
                                        <div class="bg"
                                             style="background-image: url('{{get_theme_image('theme-'.$item)}}');"></div>
                                    </a>
                                    <div class="coming-soon-theme">{{__('Coming Soon')}}</div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>

    <x-modal.theme-modal :target="'theme-modal'" :title="'Theme'" :user="'admin'"/>

    <div class="modal fade" id="edit-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">{{__('Edit Theme Details')}}</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{route('landlord.admin.theme.update')}}" method="POST">
                        @csrf
                        <input type="hidden" name="theme_id" value="">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="theme-name">{{__('Name')}}</label>
                                    <input type="text" class="form-control" name="theme_name" id="theme-name">
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="theme-name">{{__('Description')}}</label>
                                    <textarea class="form-control" name="theme_description" id="theme-description"
                                              rows="10"></textarea>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="theme-url">{{__('Theme URL')}}</label>
                                    <input type="text" class="form-control" name="theme_url" id="theme-url">
                                </div>
                            </div>
                        </div>

                        <div class="form-group d-flex justify-content-end">
                            <button type="submit" class="btn btn-success">{{__('Update')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <x-datatable.js/>
    <script>
        $(document).ready(function ($) {
            "use strict";

            $('.themeInfo').hide();
            $('.modal-success-msg').hide()

            $(document).on('change', 'select[name="lang"]', function (e) {
                $(this).closest('form').trigger('submit');
                $('input[name="lang"]').val($(this).val());
            });


            $(document).on('click', '#theme-preview', function (e) {
                let el = $(this);
                let id = el.data('id');
                let title = el.data('title');
                let description = el.data('description');
                let button_text = el.attr('data-button_text');
                let image = el.data('image');
                let url = el.data('url');

                let modal = $('#theme-modal');
                modal.attr("data-selected", id);
                modal.find('.modal-body img').attr('src', image);
                modal.find('.modal-body h2').text(title);
                modal.find('.modal-body p').text(description);
                modal.find('.modal-body a.theme_status_update_button').text(button_text);
                modal.find('.modal-body a.theme_status_update_button').attr('data-id', id);
                modal.find('.modal-body a.theme_status_update_button').attr('data-status', button_text);
                modal.find('.modal-body a.edit-btn').attr('data-id', id)
                modal.find('.modal-body a.edit-btn').attr('data-name', title)
                modal.find('.modal-body a.edit-btn').attr('data-description', description)
                modal.find('.modal-body a.edit-btn').attr('data-theme_url', url)
            });


            $(document).on('click', 'a.edit-btn', function (e) {
                let el = $(this);
                let id = el.attr('data-id');
                let name = el.attr('data-name');
                let description = el.attr('data-description');
                let theme_url = el.attr('data-theme_url');

                let modal = $('#edit-modal');
                modal.find('.modal-body input[name=theme_id]').val(id);
                modal.find('.modal-body input[name=theme_name]').val(name);
                modal.find('.modal-body textarea[name=theme_description]').text(description);
                modal.find('.modal-body input[name=theme_url]').val(theme_url);
            });
        });

        $(document).on('click', '.theme_status_update_button', function (e) {
            e.preventDefault();
            let el = $(this);
            let id = el.attr('data-id');
            let status = el.attr('data-status');

            let button = $('.theme_status_update_button[data-id=' + id + ']');
            let theme_preview_button = $('.theme-preview[data-id=' + id + ']');

            $.ajax({
                'type': 'POST',
                'url': '{{route('landlord.admin.theme.status.update')}}',
                'data': {
                    '_token': '{{csrf_token()}}',
                    'id': id
                },
                beforeSend: function () {
                    if (status == 'active') {
                        button.text('Inactivating..');
                    } else {
                        button.text('Activating..');
                    }
                },
                success: function (data) {
                    var success = $('.themeInfo_' + id + '');
                    var modal = $('#theme-modal');

                    if (data.status == true) {
                        button.text('Inactive');
                        button.attr('data-status', 'inactive');
                        theme_preview_button.attr('data-button_text', 'inactive');

                        success.find('h3').text('The theme is active successfully');
                        success.slideDown(20);

                        modal.find('.themeName').text('The theme is inactive successfully');
                        $('.modal-success-msg').slideDown(20)
                    } else {
                        button.text('Active');
                        button.attr('data-status', 'active');
                        theme_preview_button.attr('data-button_text', 'active');

                        success.find('h3').text('The theme is inactive successfully');
                        success.slideDown(20);

                        modal.find('.themeName').text('The theme is inactive successfully');
                        $('.modal-success-msg').slideDown(20)
                    }

                    setTimeout(function () {
                        success.slideUp()
                        $('.modal-success-msg').slideUp()
                    }, 5000);
                },
                error: function (data) {

                }
            });
        });
    </script>
@endsection
