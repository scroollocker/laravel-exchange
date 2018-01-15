@extends('layouts.app')

@section('content')

    <link href="{{ asset('css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap-datepicker.standalone.min.css') }}" rel="stylesheet">

    <script src="{{ asset('js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('locales/bootstrap-datepicker.ru.min.js') }}"></script>

    <script src="{{ asset('js/angular-datetimepicker-directive.js') }}"></script>

    <script src="{{ asset('js/app/main.route.js') }}"></script>
    <script src="{{ asset('js/app/controllers/invoice.js') }}"></script>
    <script src="{{ asset('js/app/controllers/invoice-list.js') }}"></script>
    <script src="{{ asset('js/app/controllers/chat.js') }}"></script>
    <script src="{{ asset('js/app/controllers/settings.js') }}"></script>
    <script src="{{ asset('js/app/controllers/accounts.js') }}"></script>
    <script src="{{ asset('js/app/controllers/partners.js') }}"></script>
    <script src="{{ asset('js/app/controllers/offers.js') }}"></script>
    <script src="{{ asset('js/app/controllers/invoice-bank.js') }}"></script>
    <script src="{{ asset('js/app/controllers/dashboard/invoices.js') }}"></script>
    <script src="{{ asset('js/app/controllers/dashboard/offers.js') }}"></script>
    <script src="{{ asset('js/app/controllers/dashboard/my-offers.js') }}"></script>

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
