app.controller('SettingsController', ['$scope', '$http', 'AppUtils', function($scope, $http, AppUtils) {
    $scope.settingsLoading = false;

    $scope.editSettings = {};
    $scope.settingError = {
        show: false,
        message: ''
    };

    $scope.loadSettings = function() {
        $scope.settingsLoading = true;
        $http.get('/settings/get').then(function(response) {
            $scope.settingsLoading = false;
            response = response.data;
            if (response.status) {
                $scope.editSettings = response.setting;
                if ($scope.editSettings === null || $scope.editSettings === undefined) {
                    $scope.editSettings = {};
                }
            }
            else {
                $scope.settingError.message = response.message;
                AppUtils.showAlertBox($scope.settingError);
            }
        }, function(response) {
            $scope.settingsLoading = false;
            $scope.settingError.message = 'Произошла системная ошибка';
            AppUtils.showAlertBox($scope.settingError);
        });
    };

    $scope.saveSettings = function(settings, form) {
        if (form.$invalid) {
            $scope.settingError.message = 'Не все поля заполнены верно';
            AppUtils.showAlertBox($scope.settingError);
            return;
        }

        $scope.settingsLoading = true;

        var request = $scope.editSettings;

        $http.post('/settings/save', request).then(function(response) {
            $scope.settingsLoading = false;
            response = response.data;
            if (response.status) {
                $scope.loadSettings();
            }
            else {
                $scope.settingError.message = response.message;
                AppUtils.showAlertBox($scope.settingError);
            }
        }, function (response) {
            $scope.settingsLoading = false;
            $scope.settingError.message = 'Произошла системная ошибка';
            AppUtils.showAlertBox($scope.settingError);
        });
    };

    $scope.loadSettings();
}]);