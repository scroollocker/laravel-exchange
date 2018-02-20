app.controller('InvoiceBankController', ['$scope', '$http', '$routeParams', '$location', 'AppUtils', '$timeout', '$interval', '$q', '$filter', function($scope, $http, $routeParams, $location, AppUtils, $timeout, $interval, $q, $filter) {

    $scope.invoiceId = $routeParams.invoiceId;
    $scope.invoiceError = {
        show: false,
        message: ''
    };
    $scope.isInvoiceLoading = false;

    $scope.currencies = [];
    $scope.payments = [];
    $scope.income = 0;

    $scope.getPayments = function () {
        return $scope.payments;
    };

    $scope.loadInvoiceState = function() {
        if ($scope.invoiceId === undefined || $scope.invoiceId === null) {
            $scope.invoiceError.message = 'Неверный запрос';
            AppUtils.showAlertBox($scope.invoiceError);
            return;
        }

        $scope.isInvoiceLoading = true;

        var request = {
            invoice_id: $scope.invoiceId
        };

        $http.post('/invoices/getState', request).then(function(response) {
            $scope.isInvoiceLoading = false;
            response = response.data;
            if (response.status) {
                if (response.msg === 'ok') {
                    $interval.cancel($scope.interval);
                    $location.path('/invoice/bank/detail/' + $scope.invoiceId);
                    $location.replace();
                }
            }
            else {
                $interval.cancel($scope.interval);
                $scope.invoiceError.message = response.message;
                AppUtils.showAlertBox($scope.invoiceError);
                $timeout(function() {
                    $location.path('/invoices/lists');
                    $location.replace();
                }, 3000);

            }

        }, function() {
            $scope.isInvoiceLoading = false;
            $scope.invoiceError.message = 'Произошла системная ошибка';
            AppUtils.showAlertBox($scope.invoiceError);
        });
    };

    $scope.loadCurrencies = function() {

        var deffer = $q.defer();
        $scope.currencies = [];

        $http.get('/dashboard/currencies-get').then(function(response){
            response = response.data;
            if (response.status) {
                $scope.currencies = response.currencies;
                deffer.resolve(true);
            }
            else {
                deffer.reject(response.message);
            }
        }, function() {
            deffer.reject('Произошла системная ошибка. Повторите позднее');
        });

        return deffer.promise;
    };

    $scope.loadPayments = function() {

        var deffer = $q.defer();
        $scope.payments = [];

        if ($scope.invoiceId === undefined || $scope.invoiceId === null) {
            deffer.reject('Неверный запрос. Не все параметры переданы корректно');
        }
        else {
            var request = {
                'invoice_id' : $scope.invoiceId
            };

            $http.post('/invoices/getPayments', request).then(function(response){
                response = response.data;
                if (response.status) {
                    $scope.payments = response.payments;
                    $scope.income = response.sum_income_nd;
                    console.log($scope.payments);
                    console.log($scope.income);
                    deffer.resolve(true);
                }
                else {
                    deffer.reject(response.message);
                }
            }, function() {
                deffer.reject('Произошла системная ошибка. Повторите позднее');
            });
        }

        return deffer.promise;
    };

    $scope.getCurName = function (id) {
        if ($scope.currencies.length > 0) {
            var items = $filter('filter')($scope.currencies, {'id':id});
            if (items.length > 0) {
                return items[0].cur_name + ' (' + items[0].cur_code + ')';
            }
        }

        return '';
    };

    $scope.sendCheck = function() {
        var win = window.open('invoices/show_check?invoice_id='+$scope.invoiceId, '_blank');
        win.focus();
    };

    $scope.init = function() {
        $scope.loadInvoiceState();
        $scope.interval = $interval(function() {
            $scope.loadInvoiceState();
        }, 10000);
    };

    $scope.paymentInit = function () {

        $scope.isInvoiceLoading = true;

        $scope.loadCurrencies().then(function () {
            $scope.loadPayments().then(function () {
                $scope.isInvoiceLoading = false;
            }, function (message) {
                $scope.isInvoiceLoading = false;
                $scope.invoiceError.message = message;
                AppUtils.showAlertBox($scope.invoiceError);
            })
        }, function (message) {
            $scope.isInvoiceLoading = false;
            $scope.invoiceError.message = message;
            AppUtils.showAlertBox($scope.invoiceError);
        });
    }

}]);