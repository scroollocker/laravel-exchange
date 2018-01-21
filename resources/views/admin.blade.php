@extends('layouts.app')

@section('content')
    <link href="{{ assetV('css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">
    {{--<link href="{{ asset('css/bootstrap-datepicker.standalone.min.css') }}" rel="stylesheet">--}}

    <script src="{{ assetV('js/bootstrap-datetimepicker.min.js') }}"></script>
    <script src="{{ assetV('locales/bootstrap-datetimepicker.ru.js') }}"></script>

    <script src="{{ assetV('js/angular-datetimepicker-directive.js') }}"></script>

    <script src="{{ assetV('js/app/admin.route.js') }}"></script>
    <script src="{{ assetV('js/app/controllers/admin/user.js') }}"></script>
    <script src="{{ assetV('js/app/controllers/admin/currency.js') }}"></script>
    <script src="{{ assetV('js/app/controllers/admin/settings.js') }}"></script>

<div class="container">
    <ng-view></ng-view>

</div>
@endsection
