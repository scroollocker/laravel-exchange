<div class="row">
    <div class="col-md-12">

        <div class="panel panel-default" >
            <div class="panel-heading" style="padding: 15px;">
                Доступные счета
            </div>

            <div class="panel-body">
                <div class="loading-area" ng-if="accountsLoading">
                    <div class="form-group text-center" style="font-size: 35px;">
                        <p><i class="fa fa-circle-o-notch fa-spin"></i></p>
                        <p>Идет загрузка...</p>
                    </div>
                </div>
                <div class="empty-area" ng-if="!accountsLoading && getAccounts().length == 0">
                    <div class="form-group text-center" style="font-size: 35px;">
                        <p><i class="fa fa-flag"></i></p>
                        <p>Нет доступных счетов</p>
                    </div>
                </div>
                <div class="form-group accounts-area" ng-if="!accountsLoading && getAccounts().length > 0">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Валюта</th>
                                    <th>Доступный остаток</th>
                                    <th>Заблокированный остаток</th>
                                    <th>Назначение</th>
                                    <th>Статус</th>
                                    <th>Доступен для сделок</th>
                                    <th>Действие</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="account in getAccounts()">
                                    <td>@{{ account.number }}</td>
                                    <td>@{{ account.currency }}</td>
                                    <td>@{{ account.balance }}</td>
                                    <td>@{{ account.block_balance }}</td>
                                    <td>@{{ account.dest }}</td>
                                    <td>@{{ account.status }}</td>
                                    <td><input type="checkbox" disabled ng-true-value="1" ng-false-value="0" ng-model="account.allow"></td>
                                    <td>
                                        <button ng-if="account.allow == 1" class="btn btn-warning btn-sm">
                                            <i class="fa fa-lock"></i>
                                        </button>
                                        <button ng-if="account.allow == 0" class="btn btn-warning btn-sm">
                                            <i class="fa fa-unlock"></i>
                                        </button>
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
