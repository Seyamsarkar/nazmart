@php
if (empty(get_static_option('language_selector_status'))){
    return ;
}

@endphp
<div class="language_dropdown" id="languages_selector">
    <div class="selected-language">{{current(explode('(',\App\Facades\GlobalLanguage::user_lang()->name))}} <i class="las la-caret-down"></i></div>
    <ul>

        @foreach(\App\Facades\GlobalLanguage::all_languages(\App\Enums\StatusEnums::PUBLISH) as $lang)
         <li data-value="{{$lang->slug}}">{{current(explode('(',$lang->name))}}</li>
        @endforeach
    </ul>
</div>
