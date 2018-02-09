

<div ng-init="init()">
    {{--<ol class="cd-breadcrumb triangle">--}}
        {{--<li ng-class="{'current':isSelect(1)}"><a>Шаг 1</a></li>--}}
        {{--<li ng-class="{'current':isSelect(2)}"><a>Шаг 1</a></li>--}}
        {{--<li ng-class="{'current':isSelect(3)}"><a>Шаг 3</a></li>--}}
        {{--<li ng-class="{'current':isSelect(4)}"><a>Шаг 4</a></li>--}}
        {{--<li ng-class="{'current':isSelect(5)}"><a>Шаг 5</a></li>--}}
        {{--<li ng-class="{'current':isSelect(6)}"><a>Шаг 6</a></li>--}}
        {{--<li ng-class="{'current':isSelect(7)}"><a>Шаг 7</a></li>--}}
    {{--</ol>--}}
</div>

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

<div class="row" ng-if="isSelect(1)">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading" style="padding: 15px;">
                Условия предложения
            </div>
            <div class="panel-body">
                <form name="step1_form">
                    <table class='table'>
                        <tr>
                            <td>Сумма продажи:</td>
                            <td>@{{ invoice.sum_sell_nd }} @{{ invoice.currency_sell.cur_code }}</td>
                        </tr>
                        <tr>
                            <td>Сумма покупки:</td>
                            <td>@{{ invoice.sum_buy_nd }} @{{ invoice.currency_buy.cur_code }}</td>
                        </tr>

                        <tr>
                            <td>Курс:</td>
                            <td>@{{ invoice.course_nd }}</td>
                        </tr>

                        <tr ng-class="{'has-error': step1_form.sum_sell_nd.$invalid}">
                            <td>Сумма продажи: </td>
                            <td><input name="sum_sell_nd" string-to-number type="number" class="form-control" ng-model="offer.sum_sell_nd"></td>
                        </tr>
                        <tr ng-class="{'has-error': step1_form.course_nd.$invalid}">
                            <td>Курс: </td>
                            <td><input name="course_nd" string-to-number type="number" class="form-control" ng-model="offer.course_nd"></td>
                        </tr>
                        <tr>
                            <td>Сумма покупки: </td>
                            <td><input disabled class="form-control" ng-value="getFinalSum()"></td>
                        </tr>

                    </table>

                </form>

                <div class="text-center ">
                    <div class="btn-group">
                        <button class="btn btn-default" ng-click="cancelCreateOffer()">Отмена</button>
                        <button class="btn btn-primary" ng-click="confirmOfferStep1(offer, step1_form)">Дальше
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row" ng-if="isSelect(2)">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading" style="padding: 15px;">
                Укажите счета для расчетов
            </div>

            <div class="panel-body">
                <form name="step2_form">
                    <table class="table">
                        <tr ng-class="{'has-error':step2_form.acc_1.$invalid}">
                            <td><label>Счет выплат</label></td>
                            <td>
                                <select name="acc_1" required ng-model="offer.acc_dt"
                                        ng-options="item as item.acc_num + '  (' + item.acc_name + ') ('+ item.saldo + ')' for item in getAccForCur1() track by item.acc_id"
                                        class="form-control" style="width: 200px;"></select>
                            </td>
                        </tr>
                        <tr ng-class="{'has-error':step2_form.acc_2.$invalid}">
                            <td><label>Счет получения средств:</label></td>
                            <td>
                                <select name="acc_2" required ng-model="offer.acc_ct"
                                        ng-options="item as item.acc_num + '  (' + item.acc_name + ') ('+ item.saldo + ')' for item in getAccForCur2() track by item.acc_id"
                                        class="form-control" style="width: 200px;"></select>
                            </td>
                        </tr>

                    </table>
                </form>

                <div class="text-center ">
                    <div class="btn-group">
                        <button class="btn btn-default" ng-click="cancelCreateOffer()">Отмена</button>
                        <button class="btn btn-primary" ng-click="confirmOfferStep2()">Далее</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row" ng-if="isSelect(3)">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading" style="padding: 15px;">
                Укажите ограничения по предложению
            </div>

            <div class="panel-body">
                <form class="form" name="step3_form">
                    <table class="table">
                        <tr ng-class="{'has-error':step3_form.date_end.$invalid}">
                            <td><label>Дата окончания предложения: </label></td>
                            <td>
                                <div class="input-group date">
                                    <input name="date_end" datetimepicker required ng-model="offer.endDate" datetimepicker-options="{language: 'ru'}" id="invoice-timeout" type="text" class="form-control"><span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="comment">Комментарий к предложению:</label></td>
                            <td>
                                <textarea name="comment" ng-model="offer.comment" class="form-control" >

                                </textarea>
                            </td>
                        </tr>

                    </table>
                </form>

                <div class="text-center ">
                    <div class="btn-group">
                        <button class="btn btn-default" ng-click="selectStep(2)">Назад</button>
                        <button class="btn btn-primary" ng-click="confirmOfferStep3(offer, step3_form)">Дальше</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row" ng-if="isSelect(4)">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading" style="padding: 15px;">
                Оформление предложения
            </div>

            <div class="panel-body">
                <table class="table">
                    <tr>
                        <td><label>Валюта: </label></td>
                        <td>
                            <p>@{{ offer.currency_sell.cur_name }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td><label>Контр валюта: </label></td>
                        <td>
                            <p>@{{ offer.currency_buy.cur_name }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td><label>Сумма: </label></td>
                        <td>
                            <p>@{{ offer.sum_sell_nd }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td><label>Курс: </label></td>
                        <td>
                            <p>@{{ offer.course_nd }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td><label>Сумма по курсу: </label></td>
                        <td>
                            <p>@{{ offer.sum_buy_nd }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td><label>Счет выплат: </label></td>
                        <td>
                            <p>@{{ offer.acc_dt.acc_num }} (@{{ offer.acc_dt.acc_name }})</p>
                        </td>
                    </tr>
                    <tr>
                        <td><label>Счет получения средств: </label></td>
                        <td>
                            <p>@{{ offer.acc_ct.acc_num }} (@{{ offer.acc_ct.acc_name }})</p>
                        </td>
                    </tr>

                    <tr>
                        <td><label> Дата окончания сделки: </label></td>
                        <td>
                            <p>@{{ offer.endDate }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td><label>Комментарий: </label></td>
                        <td>
                            <p>@{{ offer.comment }}</p>
                        </td>
                    </tr>

                </table>

                <div class="text-center ">
                    <div class="btn-group">
                        <button class="btn btn-default" ng-click="selectStep(2)">Изменить</button>
                        <button class="btn btn-primary" ng-click="createOffer(offer)">Создать</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--
<div class="row" ng-if="isSelect(5)">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading" style="padding: 15px;">
                Просмотр предложений
            </div>

            <div class="panel-body">


                <div class="table-responsive">

                    <table class="table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Партнер</th>
                            <th>Сделано</th>
                            <th>Действует до</th>
                            <th>Сообщения</th>
                            <th>Действия</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr onclick="$('#chatModal').modal('show');">
                            <td>1</td>
                            <td>test1</td>
                            <td>10.10.2017</td>
                            <td>20.10.2017</td>
                            <td>2</td>
                            <td>
                                <button class="btn btn-sm btn-success"><i class="fa fa-check"></i> Принять</button>
                                <button class="btn btn-sm btn-danger"><i class="fa fa-remove"></i> Отклонить</button>
                            </td>
                        </tr>
                        <tr onclick="$('#chatModal').modal('show');">
                            <td>2</td>
                            <td>test3</td>
                            <td>10.10.2017</td>
                            <td>20.10.2017</td>
                            <td>2</td>
                            <td>
                                <button class="btn btn-sm btn-success"><i class="fa fa-check"></i> Принять</button>
                                <button class="btn btn-sm btn-danger"><i class="fa fa-remove"></i> Отклонить</button>
                            </td>
                        </tr>
                        <tr onclick="$('#chatModal').modal('show');">
                            <td>3</td>
                            <td>test2</td>
                            <td>10.10.2017</td>
                            <td>20.10.2017</td>
                            <td>2</td>
                            <td>
                                <button class="btn btn-sm btn-success"><i class="fa fa-check"></i> Принять</button>
                                <button class="btn btn-sm btn-danger"><i class="fa fa-remove"></i> Отклонить</button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>


                </div>
            </div>
        </div>
    </div>
</div>

<div class="row" ng-if="isSelect(6)">
    <div class="col-md-12">
        <div class="panel panel-default">


            <div class="panel-body">

                <div style="font-size: 50px;" class="text-center alert alert-info">
                    <p><i class="fa fa-clock-o"></i></p>
                    <p>Завершение операции. Формирование расчетов на стороне банка.</p>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="row" ng-if="isSelect(7)">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading" style="padding: 15px;">
                Отчеты о проведении сделки.
            </div>

            <div class="panel-body">

                <div class="table-responsive">

                    <table class="table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Приход</th>
                            <th>Сумма</th>
                            <th>Валюта</th>
                            <th>Счет Дт</th>
                            <th>Счет Кт</th>
                            <th>Код</th>
                            <th>Назначение</th>
                            <th>Действие</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>1</td>
                            <td><input type="checkbox" disabled checked></td>
                            <td>100</td>
                            <td>USD</td>
                            <td>123</td>
                            <td>456</td>
                            <td>21</td>
                            <td>Покупка долларов</td>
                            <td><i class="fa fa-print"></i></td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td><input type="checkbox" disabled></td>
                            <td>50000</td>
                            <td>KGS</td>
                            <td>47855</td>
                            <td>78797</td>
                            <td>22</td>
                            <td>Покупка рублей</td>
                            <td><i class="fa fa-print"></i></td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group" style="border: 1px solid #eee; border-radius: 3px; padding: 10px;">
                            <h4>Оцените партнера
                                <button class="btn btn-warning btn-sm"><i class="fa fa-save"></i></button>
                            </h4>
                            <div class="form-group form-inline">
                                <label>Логин:</label>
                                <span>User 1</span>
                            </div>
                            <div class="form-group form-inline">
                                <label>Оценка:</label>
                                <select class="form-control">
                                    <option value="1">Оценка</option>
                                </select>
                            </div>
                            <div class="form-group form-inline">
                                <label>Отзыв:</label>
                                <textarea class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group" style="border: 1px solid #eee; border-radius: 3px; padding: 10px;">
                            <h4>Оцените сервис
                                <button class="btn btn-warning btn-sm"><i class="fa fa-save"></i></button>
                            </h4>
                            <div class="form-group form-inline">
                                <label>Оценка:</label>
                                <select class="form-control">
                                    <option value="1">Оценка</option>
                                </select>
                            </div>
                            <div class="form-group form-inline">
                                <label>Отзыв:</label>
                                <textarea class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center ">
                    <div class="btn-group">
                        <button class="btn btn-primary">Завершить</button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
-->


