@extends('layouts.app')

@section('content')

    <script src="{{ asset('js/app/main.route.js') }}"></script>
    <script src="{{ asset('js/app/controllers/invoice.js') }}"></script>

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
