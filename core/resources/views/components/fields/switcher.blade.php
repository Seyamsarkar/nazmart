<div class="form-group">
    <label>{{$label}}</label>
    <label class="switch {{$class ?? ''}}">
        <input type="checkbox"  name="{{$name}}" @if(!empty($value)) checked @endif>
        <span class="slider onff"></span>
    </label>
    @if(isset($info))
        <small class="info-text d-block mt-2">{{$info}}</small>
    @endif
</div>
