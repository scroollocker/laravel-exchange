<style>
    .chat
    {
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .chat li
    {
        margin-bottom: 10px;
        padding-bottom: 5px;
        border-bottom: 1px dotted #B3A9A9;
    }

    .chat li.left .chat-body
    {
        margin-left: 60px;
    }

    .chat li.right .chat-body
    {
        margin-right: 60px;
    }


    .chat li .chat-body p
    {
        margin: 0;
        color: #777777;
    }

    .chat-panel .slidedown .glyphicon, .chat .glyphicon
    {
        margin-right: 5px;
    }

    .chat-panel-body
    {
        overflow-y: scroll;
        height: 250px;
    }

    ::-webkit-scrollbar-track
    {
        -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
        background-color: #F5F5F5;
    }

    ::-webkit-scrollbar
    {
        width: 12px;
        background-color: #F5F5F5;
    }

    ::-webkit-scrollbar-thumb
    {
        -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,.3);
        background-color: #555;
    }

</style>

<nav >
    <ol class="cd-breadcrumb triangle">
        <li ng-class="{'current':isSelect(1)}"><a ng-click="selectStep(1)">Шаг 1</a></li>
        <li ng-class="{'current':isSelect(2)}"><a ng-click="selectStep(2)">Шаг 2</a></li>
        <li ng-class="{'current':isSelect(3)}"><a ng-click="selectStep(3)">Шаг 3</a></li>
        <li ng-class="{'current':isSelect(4)}"><a ng-click="selectStep(4)">Шаг 4</a></li>
        <li ng-class="{'current':isSelect(5)}"><a ng-click="selectStep(5)">Шаг 5</a></li>
        <li ng-class="{'current':isSelect(6)}"><a ng-click="selectStep(6)">Шаг 6</a></li>
        <li ng-class="{'current':isSelect(7)}"><a ng-click="selectStep(7)">Шаг 7</a></li>
    </ol>
</nav>

<div class="row" ng-if="isSelect(1)">
    <div class="col-md-12">
        <div class="panel panel-default" >
            <div class="panel-heading" style="padding: 15px;">
                Условия сделки
            </div>

            <div class="panel-body">
                <table>
                    <tr>
                        <td><label>Вид сделки:</label></td>
                        <td>
                            <select class="form-control" style="width: 200px;">
                                <option value="1">Покупка</option>
                                <option value="2">Продажа</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><label>Валюта:</label></td>
                        <td>
                            <select class="form-control" style="width: 200px;">
                                <option value="1">Выберите...</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><label>Контр. валюта:</label></td>
                        <td>
                            <select class="form-control" style="width: 200px;">
                                <option value="1">Выберите...</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><label>Сумма: </label></td>
                        <td>
                            <input type="text" placeholder="1000" class="form-control">
                        </td>
                    </tr>
                    <tr>
                        <td><label>Курс: </label></td>
                        <td>
                            <div class="form-inline">
                                <input type="text" class="form-control" placeholder="47,50">
                                <input type="text" class="form-control" placeholder="47,50">
                            </div>
                        </td>
                    </tr>
                </table>

                <p style="margin: 10px;">Найдено сделок - <strong>0</strong> - удовлетворяющих условиям. <button class="btn btn-default">Посмотреть</button></p>

                <div class="text-center ">
                    <div class="btn-group">
                        <button class="btn btn-default">Отмена</button>
                        <button class="btn btn-primary">Дальше</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row" ng-if="isSelect(2)">
    <div class="col-md-12">
        <div class="panel panel-default" >
            <div class="panel-heading" style="padding: 15px;">
                Укажите счета для расчетов
            </div>

            <div class="panel-body">
                <table>
                    <tr>
                        <td><label>Счет выплат</label></td>
                        <td>
                            <select class="form-control" style="width: 200px;">
                                <option value="1">Счет</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><label>Счет получения средств:</label></td>
                        <td>
                            <select class="form-control" style="width: 200px;">
                                <option value="1">Счет</option>
                            </select>
                        </td>
                    </tr>

                </table>

                <div class="text-center ">
                    <div class="btn-group">
                        <button class="btn btn-default">Назад</button>
                        <button class="btn btn-primary">Дальше</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row" ng-if="isSelect(3)">
    <div class="col-md-12">
        <div class="panel panel-default" >
            <div class="panel-heading" style="padding: 15px;">
                Укажите ограничения по сделке
            </div>

            <div class="panel-body">
                <table>
                    <tr>
                        <td><label>Дата окончания сделки</label></td>
                        <td>
                            <input type="date" class="form-control">
                        </td>
                    </tr>
                    <tr>
                        <td><label for="ass">Принемать предложения автоматически:</label></td>
                        <td>
                            <input name="ass" type="checkbox" class="form-control" style="width: 20px;">
                        </td>
                    </tr>

                </table>

                <div class="table-responsive">

                    <table class="table ">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Логин</th>
                            <th>Принемать</th>
                            <th>Не принемать</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>1</td>
                            <td>test1</td>
                            <td><input type="checkbox"></td>
                            <td><input type="checkbox"></td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>test2</td>
                            <td><input type="checkbox"></td>
                            <td><input type="checkbox"></td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>test3</td>
                            <td><input type="checkbox"></td>
                            <td><input type="checkbox"></td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <div class="text-center ">
                    <div class="btn-group">
                        <button class="btn btn-default">Назад</button>
                        <button class="btn btn-primary">Дальше</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row" ng-if="isSelect(4)">
    <div class="col-md-12">
        <div class="panel panel-default" >
            <div class="panel-heading" style="padding: 15px;">
                Оформление сделки
            </div>

            <div class="panel-body">
                <table>
                    <tr>
                        <td><label>Вид сделки: </label></td>
                        <td>
                            <p>Продажа</p>
                        </td>
                    </tr>
                    <tr>
                        <td><label>Валюта: </label></td>
                        <td>
                            <p>USD</p>
                        </td>
                    </tr>
                    <tr>
                        <td><label>Контр валюта: </label></td>
                        <td>
                            <p>KGS</p>
                        </td>
                    </tr>
                    <tr>
                        <td><label>Сумма: </label></td>
                        <td>
                            <p>1000</p>
                        </td>
                    </tr>
                    <tr>
                        <td><label>Курс: </label></td>
                        <td>
                            <p>47,5</p>
                        </td>
                    </tr>
                    <tr>
                        <td><label>Счет выплат: </label></td>
                        <td>
                            <p>123456789 (USD)</p>
                        </td>
                    </tr>
                    <tr>
                        <td><label>Счет получения средств: </label></td>
                        <td>
                            <p>123456789 (KGS)</p>
                        </td>
                    </tr>

                    <tr>
                        <td><label> Дата окончания сделки: </label></td>
                        <td>
                            <p>10.11.2017</p>
                        </td>
                    </tr>
                    <tr>
                        <td><label>Принемать сделки автоматически:   </label></td>
                        <td>
                            <p>&nbsp;&nbsp;Да</p>
                        </td>
                    </tr>

                </table>

                <div class="text-center ">
                    <div class="btn-group">
                        <button class="btn btn-default">Изменить</button>
                        <button class="btn btn-primary">Дальше</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row" ng-if="isSelect(5)">
    <div class="col-md-12">
        <div class="panel panel-default" >
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

                <!-- Modal -->
                <div id="chatModal" class="modal fade" role="dialog">
                    <div class="modal-dialog">

                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-body">
                                <div class="chat-panel panel panel-primary">
                                    <div class="panel-heading">
                                        <span class="glyphicon glyphicon-comment"></span> Chat

                                    </div>
                                    <div class="panel-body chat-panel-body">
                                        <ul class="chat">
                                            <li class="left clearfix"><span class="chat-img pull-left">
                            <img src="http://placehold.it/50/55C1E7/fff&text=U" alt="User Avatar" class="img-circle" />
                        </span>
                                                <div class="chat-body clearfix">
                                                    <div class="header">
                                                        <strong class="primary-font">Jack Sparrow</strong> <small class="pull-right text-muted">
                                                            <span class="glyphicon glyphicon-time"></span>12 mins ago</small>
                                                    </div>
                                                    <p>
                                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur bibendum ornare
                                                        dolor, quis ullamcorper ligula sodales.
                                                    </p>
                                                </div>
                                            </li>
                                            <li class="right clearfix"><span class="chat-img pull-right">
                            <img src="http://placehold.it/50/FA6F57/fff&text=ME" alt="User Avatar" class="img-circle" />
                        </span>
                                                <div class="chat-body clearfix">
                                                    <div class="header">
                                                        <small class=" text-muted"><span class="glyphicon glyphicon-time"></span>13 mins ago</small>
                                                        <strong class="pull-right primary-font">Bhaumik Patel</strong>
                                                    </div>
                                                    <p>
                                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur bibendum ornare
                                                        dolor, quis ullamcorper ligula sodales.
                                                    </p>
                                                </div>
                                            </li>
                                            <li class="left clearfix"><span class="chat-img pull-left">
                            <img src="http://placehold.it/50/55C1E7/fff&text=U" alt="User Avatar" class="img-circle" />
                        </span>
                                                <div class="chat-body clearfix">
                                                    <div class="header">
                                                        <strong class="primary-font">Jack Sparrow</strong> <small class="pull-right text-muted">
                                                            <span class="glyphicon glyphicon-time"></span>14 mins ago</small>
                                                    </div>
                                                    <p>
                                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur bibendum ornare
                                                        dolor, quis ullamcorper ligula sodales.
                                                    </p>
                                                </div>
                                            </li>
                                            <li class="right clearfix"><span class="chat-img pull-right">
                            <img src="http://placehold.it/50/FA6F57/fff&text=ME" alt="User Avatar" class="img-circle" />
                        </span>
                                                <div class="chat-body clearfix">
                                                    <div class="header">
                                                        <small class=" text-muted"><span class="glyphicon glyphicon-time"></span>15 mins ago</small>
                                                        <strong class="pull-right primary-font">Bhaumik Patel</strong>
                                                    </div>
                                                    <p>
                                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur bibendum ornare
                                                        dolor, quis ullamcorper ligula sodales.
                                                    </p>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="panel-footer">
                                        <div class="input-group">
                                            <input id="btn-input" type="text" class="form-control input-sm" placeholder="Type your message here..." />
                        <span class="input-group-btn">
                            <button class="btn btn-warning btn-sm" id="btn-chat">
                                Send</button>
                        </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row" ng-if="isSelect(6)">
    <div class="col-md-12">
        <div class="panel panel-default" >


            <div class="panel-body">

                <div style="font-size: 50px;" class="text-center alert alert-info">
                    <p><i class="fa fa-clock-o"></i> </p>
                    <p>Завершение операции. Формирование расчетов на стороне банка.</p>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="row" ng-if="isSelect(7)">
    <div class="col-md-12">
        <div class="panel panel-default" >
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
                            <td><i class="fa fa-print"></i> </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td><input type="checkbox" disabled ></td>
                            <td>50000</td>
                            <td>KGS</td>
                            <td>47855</td>
                            <td>78797</td>
                            <td>22</td>
                            <td>Покупка рублей</td>
                            <td><i class="fa fa-print"></i> </td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group" style="border: 1px solid #eee; border-radius: 3px; padding: 10px;">
                            <h4>Оцените партнера <button class="btn btn-warning btn-sm"><i class="fa fa-save"></i></button></h4>
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
                            <h4>Оцените сервис <button class="btn btn-warning btn-sm"><i class="fa fa-save"></i></button></h4>
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
