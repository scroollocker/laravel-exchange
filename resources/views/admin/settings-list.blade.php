<div class="row">
    <div class="col-md-12">

        <div class="panel panel-default" >
            <div class="panel-heading" style="padding: 15px;">
                Настройки
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
                    <div class="alert alert-danger" ng-if="settingError.show">
                        <strong>Ошибка:</strong> @{{ settingError.message }}
                    </div>
                   <table class="table">
                       <tr ng-class="{'has-error': settingsForm.err_count.$invalid}">
                           <td><label>Блокировка после количества ошибок:</label></td>
                           <td><input name="err_count" ng-model="editSettings.settings_err_count" required class="form-control" type="number"></td>
                       </tr>
                       <tr ng-class="{'has-error': settingsForm.day.$invalid}">
                           <td><label>Дней для операций</label></td>
                           <td><input name="day" ng-model="editSettings.settings_day" class="form-control" required type="number"></td>
                       </tr>
                       <tr ng-class="{'has-error': settingsForm.op_count.$invalid}">
                           <td><label>Количество проводимых операций</label></td>
                           <td><input name="op_count" ng-model="editSettings.settings_op_count" class="form-control" required type="number"></td>
                       </tr>
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
