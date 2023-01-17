@extends('tenant.admin.admin-master')
@section('title')
    {{ __('Theme Manage') }}
@endsection

@section('style')
    <link rel="stylesheet" href="{{global_asset('assets/common/css/custom-style.css')}}">

    <style>
        .selected_text{
            top: 0;
            left: 0;
            background-color: #b66dff;
            padding: 15px;
            position: absolute;
            color: white;
            transition: 0.3s;
        }
        .selected_text i{
            font-size: 30px;
        }
        .active_theme{
            background-color: #b66dff;
        }
        .modal-image{
            width: 100%;
        }
    </style>
@endsection
@section('content')
    @php
        $selected_theme = tenant()->theme_slug;
    @endphp
    <div class="dashboard-recent-order">
        <div class="row">
            @foreach($themes as $theme)
                <div class="col-xl-3 col-sm-6">
                    <div class="themePreview">
                        <a href="javascript:void(0)" id="theme-preview" data-bs-target="#theme-modal"
                           data-bs-toggle="modal"
                           data-id="{{$theme->id}}"
                           data-title="{{$theme->title}}"
                           data-description="{{$theme->description}}"
                           data-image="{{get_theme_image($theme->slug)}}"
                           data-button_text="{{$theme->slug == $selected_theme ? 'Selected' : 'Select'}}"
                           data-url="{{route('tenant.admin.theme.update', $theme->slug)}}"
                           class="theme-preview active"
                        >
                            <div class="bg" style="background-image: url('{{get_theme_image($theme->slug)}}');"></div>
                        </a>

                        <div class="themeInfo themeInfo_{{$theme->id}}" data-id="{{$theme->id}}">
                            <h3 class="themeName text-center"></h3>
                        </div>

                        <div class="themeLink {{$theme->slug == $selected_theme ? 'active_theme' : ''}}">
                            <h3 class="themeName">{{$theme->title}}</h3>
                        </div>

                        @if($theme->slug == $selected_theme)
                            <h4 class="selected_text"><i class="las la-check-circle"></i></h4>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>


    <x-modal.theme-modal :target="'theme-modal'" :title="'Theme'" :user="'tenant'"/>
@endsection
@section('scripts')
    <x-datatable.js/>
    <script>
        $(document).ready(function ($) {
            "use strict";

            $('.themeInfo').hide();
            $('.modal-success-msg').hide()


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
                modal.find('.modal-body a.theme_status_update_button').attr('data-url', url)

                modal.find('.modal-body a.edit-btn').attr('data-id', id)
                modal.find('.modal-body a.edit-btn').attr('data-name', title)
                modal.find('.modal-body a.edit-btn').attr('data-description', description)
            });
        });

        $(document).on('click', '.theme_status_update_button', function (e) {
            e.preventDefault();
            let el = $(this);
            let id = el.attr('data-id');
            let status = el.attr('data-status');
            let url = el.attr('data-url');

            let button = $('.theme_status_update_button[data-id=' + id + ']');
            let theme_preview_button = $('.theme-preview[data-id=' + id + ']');

            $.ajax({
                'type': 'POST',
                'url': url,
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
                    var success = $('.themeInfo_'+id+'');
                    var modal = $('#theme-modal');

                    if (data.status == true) {
                        button.text('Selected');
                        button.attr('data-status','selected');
                        theme_preview_button.attr('data-button_text','selected');

                        success.find('h3').text('{{__('The theme is active successfully')}}');
                        success.slideDown(20);

                        modal.find('.themeName').text('{{__('The theme is active successfully')}}');
                        $('.modal-success-msg').slideDown(20)

                        setTimeout(function (){
                            location.reload();
                        }, 1000);

                    }
                },
                error: function (data) {
                    console.log(data);
                }
            });
        });
    </script>
@endsection
