<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <button class="btn btn-primary" ng-click="addCurrency()"><i class="fa fa-plus"></i> Добавить валюту</button>
        </div>
        <div class="panel panel-default" >
            <div class="panel-heading" style="padding: 15px;">
                Список валют
            </div>
            <div class="panel-body">
                <div class="currency-empty" ng-if="!isCurrenciesLoading && getCurrencies().length === 0">
                    <div class="text-center" style="font-size: 35px;">
                        <p><i class="fa fa-flag"></i></p>
                        <p>Валюты не найдены</p>
                    </div>
                </div>
                <div class="currency-loading" ng-if="isCurrenciesLoading">
                    <div class="text-center" style="font-size: 35px;">
                        <p><i class="fa fa-circle-o-notch fa-spin"></i></p>
                        <p>Загрузка... Ожидайте.</p>
                    </div>
                </div>
                <div class="currency-list" ng-if="!isCurrenciesLoading && getCurrencies().length > 0">
                    <div class="form-group">
                        <input ng-model="searchText" class="form-control" placeholder="Поиск">
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Код</th>
                                    <th>Наименование</th>
                                    <th>Сделки</th>
                                    <th>Действия</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="currency in getCurrencies() | filter: searchText">
                                    <td>@{{ currency.id }}</td>
                                    <td>@{{ currency.cur_code }}</td>
                                    <td>@{{ currency.cur_name }}</td>
                                    <td><input icheck ng-model="currency.cur_enable" ng-true-value="1" ng-false-value="0" type="checkbox" disabled></td>
                                    <td>
                                        <button data-toggle="tooltip" data-placement="bottom" title="Изменить" class="btn btn-success btn-xs" ng-click="editCurrency(currency)"><i class="fa fa-edit"></i></button>

                                        <button data-toggle="tooltip" data-placement="bottom" title="Заблокировать" class="btn btn-warning btn-xs" ng-click="blockCurrency(currency)"><i class="fa fa-lock"></i></button>
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

<!-- Modal -->
<div id="currencyModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><span ng-if="!currencyEdit.id">Добавление</span><span ng-if="currencyEdit.id">Изменение</span> валюты</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <form name="currencyEditForm">
                        <div class="form-group">
                            <div class="alert alert-danger" ng-if="currencyError.show">
                                <strong>Ошибка</strong> @{{ currencyError.message }}
                            </div>
                           <table class="table">
                               <tr ng-class="{'has-error':currencyEditForm.code.$invalid}">
                                   <td><label>Код: </label></td>
                                   <td><input name="code" ng-model="currencyEdit.cur_code" class="form-control" type="text" maxlength="20" required></td>
                               </tr>
                               <tr ng-class="{'has-error':currencyEditForm.name.$invalid}">
                                   <td><label>Наименование: </label></td>
                                   <td><input name="name" ng-model="currencyEdit.cur_name" class="form-control" type="text" maxlength="50" required></td>
                               </tr>
                           </table>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" ng-click="saveCurrency(currencyEdit, currencyEditForm)" class="btn btn-primary" ng-if="!currencyEdit.id">Добавить</button>
                <button type="button" ng-click="saveEditCurrency(currencyEdit, currencyEditForm)" class="btn btn-primary"  ng-if="currencyEdit.id">Изменить</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
            </div>
        </div>

    </div>
</div>

<script>
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>