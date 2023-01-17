@extends('tenant.frontend.frontend-page-master')
@php
    $post_img = null;
    $user_lang = get_user_lang();

@endphp

@section('page-title')
    {{ $service->getTranslation('title',$user_lang) }}
@endsection

@section('title')
    {{ $service->getTranslation('title',$user_lang) }}
@endsection

@section('meta-data')
    {!!  render_page_meta_data($service) !!}
@endsection

@section('content')
<div class="page-content service-details" data-padding-top="110" data-padding-bottom="100">
      <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="service-details-item">
                        <div class="thumb margin-bottom-40">
                            {!! render_image_markup_by_attachment_id($service->image,'','large') !!}
                        </div>
                        <div class="service-description">
                            {!! $service->getTranslation('description',get_user_lang()) !!}
                        </div>

                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="widget-area">
                        {!! render_frontend_sidebar('service',['column' => false]) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
