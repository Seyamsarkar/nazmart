@php
    $user_lang = get_user_lang();
@endphp

<div class="breadcrumb-area" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="shape"></div>
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="breadcrumb-inner">
                    <div class="icon">
                        <i class="{{$data['icon']}}"></i>
                        <p>{{$data['left_title']}}</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="breadcrumb-inner">
                    <h2 class="page-title">{{$data['right_title']}} </h2>
                </div>
            </div>
        </div>
    </div>
</div>
