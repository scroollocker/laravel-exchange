app.controller('CurrencyController', ['$scope', 'AppUtils', '$http', function($scope, AppUtils, $http) {

    $scope.currenciesList = [];
    $scope.isCurrenciesLoading = false;

    $scope.curencyError = {
        show: false,
        message: ''
    };

    $scope.currencyEdit = {};

    $scope.loadCurrencyList = function () {
        $scope.isCurrenciesLoading = true;

        $http.get('/currency/get').then(function (response) {
            $scope.isCurrenciesLoading = false;
            response = response.data;
            if (response.status) {
                $scope.currenciesList = response.currencies;
            }
            else {
                $scope.curencyError.message = response.message;
                AppUtils.showAlertBox($scope.curencyError);
                return;
            }
        }, function (response) {
            $scope.isCurrenciesLoading = false;
            $scope.curencyError.message = 'Произошла системная ошибка';
            AppUtils.showAlertBox($scope.curencyError);
            return;
        });
    };

    $scope.loadCurrencyList();

    $scope.getCurrencies = function () {
        return $scope.currenciesList;
    };

    $scope.editCurrency = function (currency) {
        $scope.currencyEdit = angular.copy(currency);
        $('#currencyModal').modal('show');
    };

    $scope.addCurrency = function () {
        $scope.currencyEdit = {};
        $('#currencyModal').modal('show');
    };

    $scope.saveCurrency = function (currency, form) {
        if (form.$invalid) {
            $scope.curencyError.message = 'Форма заполнена неверно';
            AppUtils.showAlertBox($scope.curencyError);
            return;
        }

        var request = $scope.currencyEdit;

        $http.post('/currency/add', request).then(function (response) {
            response = response.data;
            if (response.status) {

                $scope.loadCurrencyList();
                $('#currencyModal').modal('hide');
            }
            else {
                $scope.curencyError.message = response.message;
                AppUtils.showAlertBox($scope.curencyError);
                return;
            }
        }, function (response) {
            $scope.curencyError.message = 'Произошла системная ошибка';
            AppUtils.showAlertBox($scope.curencyError);
            return;
        })
    };

    $scope.saveEditCurrency = function (currency, form) {
        if (form.$invalid) {
            $scope.curencyError.message = 'Форма заполнена неверно';
            AppUtils.showAlertBox($scope.curencyError);
            return;
        }

        var request = $scope.currencyEdit;

        $http.post('/currency/edit', request).then(function (response) {
            response = response.data;
            if (response.status) {
                $scope.loadCurrencyList();
                $('#currencyModal').modal('hide');
            }
            else {
                $scope.curencyError.message = response.message;
                AppUtils.showAlertBox($scope.curencyError);
                return;
            }
        }, function (response) {
            $scope.curencyError.message = 'Произошла системная ошибка';
            AppUtils.showAlertBox($scope.curencyError);
            return;
        })
    };

    $scope.blockCurrency = function (currency) {
        var request = {
            'id': currency.id,
            'block': (currency.cur_enable === 1) ? 0 : 1
        };

        $http.post('/currency/block', request).then(function (response) {
            response = response.data;
            if (response.status) {
                $scope.loadCurrencyList();
            }
            else {
                alert(response.message);
            }
        }, function (response) {
            alert('Произошла системная ошибка');
        });

    };


}]);