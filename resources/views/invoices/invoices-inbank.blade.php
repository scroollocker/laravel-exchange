<div ng-init="init()">

</div>

{{--<nav ng-init="init()">--}}
    {{--<ol class="cd-breadcrumb triangle">--}}
        {{--<li><a>Шаг 1</a></li>--}}
        {{--<li><a>Шаг 2</a></li>--}}
        {{--<li><a>Шаг 3</a></li>--}}
        {{--<li><a>Шаг 4</a></li>--}}
        {{--<li><a>Шаг 5</a></li>--}}
        {{--<li class="current"><a>Шаг 6</a></li>--}}
        {{--<li><a>Шаг 7</a></li>--}}
    {{--</ol>--}}
{{--</nav>--}}

<div ng-if="invoiceError.show" class="alert alert-danger">
    <strong>Ошибка:</strong>
    <p>@{{ invoiceError.message }}</p>
</div>

<div class="row" ng-if="isInvoiceLoading">
    <div class="col-md-12">
        <div class="invoice-loading text-center" style="font-size: 32px;">
            <p><i class="fa fa-circle-o-notch fa-spin"></i></p>
            <p>Производится загрузка... Ждите.</p>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">


            <div class="panel-body">

                <div style="font-size: 25px;" class="text-center alert alert-info">
                    <p><i class="fa fa-clock-o"></i></p>
                    <p>Завершение операции. Формирование расчетов на стороне банка.</p>
                </div>

            </div>
        </div>
    </div>
</div>



