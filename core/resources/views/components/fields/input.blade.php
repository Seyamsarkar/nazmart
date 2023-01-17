@php $type = $type ?? 'text';@endphp
<div class="form-group">
    @if(isset($type) && $type !== 'hidden')
        <label>{{$label}}</label>
    @endif
    <input type="{{$type ?? 'text'}}"
           name="{{$name}}"
           class="form-control {{$class ?? ''}}"
           @if( isset($type) && $type !== 'hidden')
           placeholder="{{$placeholder ?? $label}}"
           @endif
           value="{{$value ?? ''}}" min="{{$min ?? ''}}">
    @if(isset($info))
        <small class="info-text d-block mt-2">{!!  $info !!}</small>
    @endif
</div>
