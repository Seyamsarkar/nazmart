@php
    $route_name ='landlord';
@endphp

@extends($route_name.'.admin.admin-master')

@section('title')
    {{__('User Activity Logs')}}
@endsection

@section('style')
    <x-datatable.css/>
    <x-summernote.css/>
@endsection

@section('content')
    <div class="col-12 stretch-card">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h4>{{__('User Activity Logs')}}</h4>
            </div>
            <div class="card-body activity_log_table">
                <ul>
                    @foreach ($activities as $key => $activity)

                        @php
                            $ex = explode("App\Models\\",$activity->subject_type) ?? [];
                        @endphp

                        <li> <i class="las la-user mr-1 text-dark"></i> <strong>  {{ optional($activity->causer)->name ?? optional(Auth::guard('web')->user())->name}}</strong>
                            <span class="text-primary">{{$activity->description  }} </a> <span class="text-danger">{{$ex[1] ?? '' }} <span class="text-dark">{{__('section')}}</span></span></span>
                            <br>
                            {{ $activity->updated_at->diffForHumans() ?? '' }}
                        </li><hr>
                    @endforeach

                </ul>
                <div>
                    {{ $activities->links() }}
                </div>
            </div>
        </div>
    </div>



@endsection

@section('scripts')
    <x-datatable.js/>
    <x-summernote.js/>

@endsection

