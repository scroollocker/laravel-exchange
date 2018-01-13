app.controller('InvoiceBankController', ['$scope', '$http', '$routeParams', '$location', 'AppUtils', '$timeout', '$interval', function($scope, $http, $routeParams, $location, AppUtils, $timeout, $interval) {

    $scope.invoiceId = $routeParams.invoiceId;
    $scope.invoiceError = {
        show: false,
        message: ''
    };
    $scope.isInvoiceLoading = false;

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
                $location.path('/invoice/bank/detail/'+$scope.invoiceId);
                $location.replace();
            }
            else {
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

    $scope.init = function() {
        $scope.loadInvoiceState();
        $interval(function() {
            $scope.loadInvoiceState();
        }, 10000);
    };

}]);