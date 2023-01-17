@php
    $userlang = get_user_lang();
@endphp

<div class="map-area" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-lg-12">
                <div class="contact_map">
                   {!! $data['location'] !!}
                </div>
            </div>
        </div>
    </div>
</div>
