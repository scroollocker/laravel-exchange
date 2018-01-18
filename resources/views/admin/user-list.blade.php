<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <button ng-click="addUser()" class="btn btn-primary"><i class="fa fa-plus"></i> Добавить
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
                            <tr ng-repeat="user in getUserList() | filter:userSearch">
                                <td>@{{ user.id }}</td>
                                <td>@{{ user.email }}</td>
                                <td>@{{ user.ibs_id }}</td>
                                <td><input icheck ng-model="user.blocked" ng-true-value="1" ng-false-value="0" disabled type="checkbox" checked></td>
                                <td>
                                    <button ng-click="editUser(user)" class="btn btn-success btn-xs"><i class="fa fa-edit"></i></button>
                                    <button ng-click="removeUser(user)" class="btn btn-danger btn-xs"><i class="fa fa-remove"></i></button>
                                    <button ng-click="blockUser(user)" class="btn btn-warning btn-xs"><i ng-class="{'fa-unlock': isBlocked(user), 'fa-lock': !isBlocked(user)}" class="fa"></i></button>
                                    <button ng-click="resetPassword(user)" class="btn btn-success btn-xs"><i class="fa fa-retweet"></i></button>
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
                <h4 class="modal-title"><span ng-if="!editedUser.id">Добавление</span><span ng-if="editedUser.id">Изменение</span> пользователя</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div class="form-group">
                        <div class="alert alert-danger" ng-if="userError.show">
                            <strong>Ошибка: </strong> @{{ userError.message }}
                        </div>
                        <form name="userEditForm">
                            <table class="table">
                                <tr ng-class="{'has-error':userEditForm.name.$invalid}">
                                    <td><label>Имя: </label></td>
                                    <td><input ng-model="editedUser.name" name="name" maxlength="150" class="form-control" required type="text"></td>
                                </tr>
                                <tr ng-class="{'has-error':userEditForm.email.$invalid}">
                                    <td><label>E-mail: </label></td>
                                    <td><input ng-model="editedUser.email" name="email" maxlength="100" class="form-control" required type="email"></td>
                                </tr>
                                <tr ng-class="{'has-error':userEditForm.phone.$invalid}">
                                    <td><label>Телефон: </label></td>
                                    <td><input ng-model="editedUser.phone" name="phone" class="form-control" required pattern="996[0-9]{9}"></td>
                                </tr>
                                <tr ng-class="{'has-error':userEditForm.ibs.$invalid}">
                                    <td><label>Идентификатор в АБС: </label></td>
                                    <td><input class="form-control" name="ibs" ng-model="editedUser.ibs_id" required type="number"></td>
                                </tr>
                                <tr ng-class="{'has-error':userEditForm.icount.$invalid}">
                                    <td><label>Количество сделок: </label></td>
                                    <td><input ng-model="editedUser.invoice_count" name="icount" class="form-control" required type="number"></td>
                                </tr>
                                <tr>
                                    <td><label>До какой даты осуществляет сделки: </label></td>
                                    <td>
                                        <div class="input-group date">
                                            <input id="activeDate" name="date_end" datetimepicker required ng-model="editedUser.active_date" datetimepicker-options="{language: 'ru'}" type="text" class="form-control"><span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                        </div>
                                    </td>
                                </tr>
                                <tr ng-class="{'has-error':userEditForm.comment.$invalid}">
                                    <td><label>Дополнительная информация: </label></td>
                                    <td><input ng-model="editedUser.comment" name="comment" maxlength="500" class="form-control" type="text"></td>
                                </tr>
                            </table>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button ng-if="!editedUser.id" type="button" ng-click="saveAddUser(editedUser, userEditForm)" class="btn btn-primary">Добавить</button>
                <button ng-if="editedUser.id" type="button" ng-click="saveEditUser(editedUser, userEditForm)" class="btn btn-primary">Изменить</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
            </div>
        </div>

    </div>
</div>
