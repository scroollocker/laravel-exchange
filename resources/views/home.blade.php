@extends('layouts.app')

@section('content')

    <link href="{{ assetV('css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">
    {{--<link href="{{ asset('css/bootstrap-datepicker.standalone.min.css') }}" rel="stylesheet">--}}

    <script src="{{ assetV('js/bootstrap-datetimepicker.min.js') }}"></script>
    <script src="{{ assetV('locales/bootstrap-datetimepicker.ru.js') }}"></script>

    <script src="{{ assetV('js/angular-datetimepicker-directive.js') }}"></script>

    <script src="{{ assetV('js/app/main.route.js') }}"></script>
    <script src="{{ assetV('js/app/controllers/invoice.js') }}"></script>
    <script src="{{ assetV('js/app/controllers/invoice-list.js') }}"></script>
    <script src="{{ assetV('js/app/controllers/chat.js') }}"></script>
    <script src="{{ assetV('js/app/controllers/settings.js') }}"></script>
    <script src="{{ assetV('js/app/controllers/accounts.js') }}"></script>
    <script src="{{ assetV('js/app/controllers/partners.js') }}"></script>
    <script src="{{ assetV('js/app/controllers/offers.js') }}"></script>
    <script src="{{ assetV('js/app/controllers/invoice-bank.js') }}"></script>
    <script src="{{ assetV('js/app/controllers/dashboard/invoices.js') }}"></script>
    <script src="{{ assetV('js/app/controllers/dashboard/offers.js') }}"></script>
    <script src="{{ assetV('js/app/controllers/dashboard/my-offers.js') }}"></script>

<div class="container">
    <!--<div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    You are logged in!
                        @can('is-admin')
                            You are admin!
                        @endcan
                </div>
            </div>
        </div>
    </div>-->

    <ng-view></ng-view>

</div>
@endsection
