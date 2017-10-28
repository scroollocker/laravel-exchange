@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Подтверждение по СМС</div>

                <div class="panel-body">

                    @if(session('custom_error') !== null)
                        <div class="alert alert-danger" >
                            {{ session('custom_error') }}
                        </div>
                    @endif

                    <form class="form-horizontal" method="POST" action="{{ route('login-confirm') }}">
                        {{ csrf_field() }}

                        <div class="form-group text-center">
                            <p>Вы пытаетесь авторизоваться от пользователя:</p>
                            <p><strong>{{ $user->email }}</strong></p>
                            <p>Для продолжения входа требуется ввести код подтверждения из СМС</p>
                        </div>


                        <div class="form-group{{ $errors->has('sms') ? ' has-error' : '' }}">
                            <label for="sms" class="col-md-4 control-label">Код из СМС</label>

                            <div class="col-md-6">
                                <input id="sms" type="text" class="form-control" maxlength="5" name="sms" required>

                                @if ($errors->has('sms'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('sms') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group text-center">
                            <div id="pin_deadline">Пин код истекает через: <span id="pin_timer"></span> </div>
                            <div id="resend_pin"><a href="/resend-pin">Отправить новый пин код</a></div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Подтвердить
                                </button>

                            </div>
                        </div>

                        <script>
                            var exp_time = {{ $exp_time }};
                            jQuery(document).ready(function() {

                                jQuery('#pin_deadline').show();
                                jQuery('#resend_pin').hide();

                                var interval = setInterval(function() {
                                    var nowTime = Math.trunc(new Date().getTime() / 1000);

                                    if (exp_time <= nowTime) {
                                        jQuery('#pin_deadline').hide();
                                        jQuery('#resend_pin').show();
                                        clearInterval(interval);
                                    }
                                    else {

                                        var timeTemplate = '<%- mm %> мин. <%- ss %> сек.';
                                        var timerRender = _.template(timeTemplate);


                                        var backTime = (nowTime - exp_time) * -1;

                                        var mm = Math.trunc(backTime / 60);
                                        var ss = Math.trunc(backTime % 60);

                                        var timerStr = timerRender({mm: mm, ss: ss});

                                        jQuery('#pin_timer').html(timerStr);
                                    }

                                },1000);

                            });
                        </script>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
