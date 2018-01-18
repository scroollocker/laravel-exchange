@extends('layouts.app')

@section('content')
    <link href="{{ asset('css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">
    {{--<link href="{{ asset('css/bootstrap-datepicker.standalone.min.css') }}" rel="stylesheet">--}}

    <script src="{{ asset('js/bootstrap-datetimepicker.min.js') }}"></script>
    <script src="{{ asset('locales/bootstrap-datetimepicker.ru.js') }}"></script>

    <script src="{{ asset('js/angular-datetimepicker-directive.js') }}"></script>

    <script src="{{ asset('js/app/admin.route.js') }}"></script>
    <script src="{{ asset('js/app/controllers/admin/user.js') }}"></script>
    <script src="{{ asset('js/app/controllers/admin/currency.js') }}"></script>
    <script src="{{ asset('js/app/controllers/admin/settings.js') }}"></script>

<div class="container">
    <ng-view></ng-view>

</div>
@endsection
