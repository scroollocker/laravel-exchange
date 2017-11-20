<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <button class="btn btn-primary"><i class="fa fa-plus"></i> Добавить
                пользователя
            </button>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading" style="padding: 15px;">
                Список пользователей
            </div>

            <div class="panel-body">
                <div class="user-empty" ng-if="!userListLoading && getUserList().length === 0">
                    <div class="text-center" style="font-size: 35px;">
                        <p><i class="fa fa-flag"></i></p>
                        <p>Пользователи не найдены</p>
                    </div>
                </div>
                <div class="user-loading" ng-if="userListLoading">
                    <div class="text-center" style="font-size: 35px;">
                        <p><i class="fa fa-circle-o-notch fa-spin"></i></p>
                        <p>Загрузка... Ожидайте.</p>
                    </div>
                </div>
                <div class="user-list" ng-if="!userListLoading && getUserList().length > 0">
                    <div class="form-group">
                        <input ng-model="userSearch" class="form-control" placeholder="Поиск">
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Login</th>
                                <th>Идентификатор</th>
                                <th>Заблокирован</th>
                                <th>Действия</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr ng-repeat="user in getUserList()">
                                <td>@{{ user.id }}</td>
                                <td>@{{ user.email }}</td>
                                <td>@{{ user.ibs_id }}</td>
                                <td><input ng-model="user.blocked" ng-true-value="'1'" ng-false-value="'0'" disabled type="checkbox" checked></td>
                                <td>
                                    <button ng-click="editUser(user)" class="btn btn-success btn-xs"><i class="fa fa-edit"></i></button>
                                    <button ng-click="deleteUser(user)" class="btn btn-danger btn-xs"><i class="fa fa-remove"></i></button>
                                    <button ng-click="lock(user)" class="btn btn-warning btn-xs"><i ng-class="{'fa-unlock': isBlocked(user), 'fa-lock': !isBlocked(user)}" class="fa"></i></button>
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
<div id="userModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Добавление пользователя</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div class="form-group">
                        <form name="userEditForm">
                            <table class="table">
                                <tr>
                                    <td><label>E-mail: </label></td>
                                    <td><input ng-model="editedUser.email" maxlength="100" class="form-control" required type="email"></td>
                                </tr>
                                <tr>
                                    <td><label>Телефон: </label></td>
                                    <td><input ng-model="editedUser.phone" class="form-control" required pattern="996[0-9]{9}"></td>
                                </tr>
                                <tr>
                                    <td><label>Идентификатор в АБС: </label></td>
                                    <td><input class="form-control" ng-model="editedUser.ibs_id" required type="number"></td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Валюта</th>
                                                    <th>Кредит</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td>1</td>
                                                    <td>USD</td>
                                                    <td>1000</td>
                                                </tr>
                                                <tr>
                                                    <td>2</td>
                                                    <td>RUB</td>
                                                    <td>2000</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                    </td>
                                </tr>
                                <tr>
                                    <td><label>Количество сделок: </label></td>
                                    <td><input ng-model="editedUser.invoice_count" class="form-control" required type="number"></td>
                                </tr>
                                <tr>
                                    <td><label>До какой даты осуществляет сделки: </label></td>
                                    <td>
                                        <div class="input-group date">
                                            <input type="text" class="form-control"><span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><label>Дополнительная информация: </label></td>
                                    <td><input ng-model="editedUser.comment" class="form-control" type="text"></td>
                                </tr>
                            </table>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" ng-click="saveUser(editedUser, userEditForm)" class="btn btn-primary">Применить</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
            </div>
        </div>

    </div>
</div>

<script>
    $('#userModal .input-group.date').datepicker({
        format: 'dd.mm.yyyy',
        language: "ru",
        startDate: new Date()
    });
</script>