<div class="row">
    <div class="col-md-12">

        <div class="panel panel-default" >
            <div class="panel-heading" style="padding: 15px;">
                Общие настройки
            </div>

            <div class="panel-body">
                <div class="loading-area" ng-if="settingsLoading">
                    <div class="form-group text-center" style="font-size: 35px;">
                        <p><i class="fa fa-circle-o-notch fa-spin"></i></p>
                        <p>Идет загрузка...</p>
                    </div>
                </div>
                <div class="form-group settings-area" ng-if="!settingsLoading">
                <form name="settingsForm">
                    <div class="alert alert-danger" ng-if="settingsError.show">
                        <strong>Ошибка:</strong> @{{ settingsError.message }}
                    </div>
                   <table class="table">
                       <tr>
                           <td><label>Принимать сделки автоматически</label></td>
                           <td><input  name="autoconfirm" icheck type="checkbox" ng-true-value="1" ng-false-value="0" ng-model="editSettings.autoconfirm" class="form-control"></td>
                       </tr>
                       <tr ng-class="{'has-error': settingsForm.phone.$invalid}">
                           <td><label>Номер телефона:</label></td>
                           <td><input name="phone" ng-model="editSettings.phone" required maxlength="12" placeholder="996yyyxxxxxx" class="form-control" pattern="996[0-9]{9}"></td>
                       </tr>
                       <tr ng-class="{'has-error': settingsForm.email.$invalid}">
                           <td><label>E-Mail:</label></td>
                           <td><input name="email" ng-model="editSettings.email" class="form-control" required type="email"></td>
                       </tr>
                       <tr>
                           <td><a ng-click="pwdChange()">Сменить пароль</a></td>
                           <td></td>
                       </tr>
                       <tbody class="change-password-area" ng-if="editSettings.changePassword">
                           <tr ng-class="{'has-error': settingsForm.old_password.$invalid}">
                               <td><label>Текущий пароль</label></td>
                               <td><input name="old_password" ng-model="editSettings.old_password" class="form-control" required type="password"></td>
                           </tr>
                           <tr ng-class="{'has-error': settingsForm.new_password.$invalid}">
                               <td><label>Новый пароль:</label></td>
                               <td><input name="new_password" ng-model="editSettings.new_password" class="form-control" required type="password"></td>
                           </tr>
                           <tr ng-class="{'has-error': settingsForm.new_password_confirmation.$invalid}">
                               <td><label>Новый пароль еще раз:</label></td>
                               <td><input name="new_password_confirmation" ng-model="editSettings.new_password_confirmation" class="form-control" required type="password"></td>
                           </tr>
                       </tbody>
                   </table>
                </form>

                <div class="form-group text-center" style="margin-top: 15px;">
                    <div class="btn-group">
                        <button ng-click="saveSettings(editSettings, settingsForm)" class="btn btn-success"><i class="fa fa-save"></i> Сохранить</button>
                    </div>

                </div>
                </div>
            </div>

        </div>
    </div>
</div>
