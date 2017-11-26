/**
 * Created by scroollocker on 26.11.17.
 */
app.controller('UserSettings', ['$scope', 'AppUtils', '$http', function($scope, AppUtils, $http) {
    $scope.settingsError = {
        show: false,
        message: ''
    };

    $scope.editSettings = {
        changePassword: false
    };

    $scope.settingsLoading = false;

    $scope.pwdChange = function() {
        $scope.editSettings.changePassword = !$scope.editSettings.changePassword;
    };

    $scope.loadSettings = function() {
        $scope.settingsLoading = true;
        $scope.editSettings = {
            changePassword: false
        };

        $http.get('user/settings/get').then(function(response) {
            $scope.settingsLoading = false;
            response = response.data;
            if (response.status) {
                $scope.editSettings = response.settings;
            }
            else {
                $scope.settingsError.message = response.message;
                AppUtils.showAlertBox($scope.settingsError);
                return;
            }
        }, function(response) {
            $scope.settingsLoading = false;
            $scope.settingsError.message = 'Произошла системная ошибка';
            AppUtils.showAlertBox($scope.settingsError);
            return;
        });
    };

    $scope.loadSettings();

    $scope.saveSettings = function(settings, form) {
        if (form.$invalid) {
            $scope.settingsError.message = 'Не все поля заполнены правильно';
            AppUtils.showAlertBox($scope.settingsError);
            return;
        }

        var request = settings;

        $scope.settingsLoading = true;
        $http.post('user/settings/save', request).then(function(response) {
            $scope.settingsLoading = false;
            response = response.data;
            if (response.status) {
                $scope.loadSettings();
            }
            else {
                $scope.settingsError.message = response.message;
                AppUtils.showAlertBox($scope.settingsError);
            }
        }, function(response) {
            $scope.settingsLoading = false;
            $scope.settingsError.message = 'Произошла системная ошибка';
            AppUtils.showAlertBox($scope.settingsError);
        });
   };
}]);