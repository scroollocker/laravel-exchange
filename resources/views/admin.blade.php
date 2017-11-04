@extends('layouts.app')

@section('content')
    <script src="{{ asset('js/app/admin.route.js') }}"></script>
    <script src="{{ asset('js/app/controllers/admin/user.js') }}"></script>
    <script src="{{ asset('js/app/controllers/admin/currency.js') }}"></script>
    <script src="{{ asset('js/app/controllers/admin/settings.js') }}"></script>

<div class="container">
    <ng-view></ng-view>

</div>
@endsection
