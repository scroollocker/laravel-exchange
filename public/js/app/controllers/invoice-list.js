app.controller('InvoicesListController', ['$scope', '$http', 'AppUtils', function ($scope, $http, AppUtils) {

    $scope.isInvoiceLoading = false;

    $scope.invoicesList = [];

    $scope.invoiceError = {
        show: false,
        message: ''
    };

    $scope.getInvoices = function () {
        return $scope.invoicesList;
    };

    $scope.loadInvoices = function () {
        $scope.isInvoiceLoading = true;
        $scope.invoicesList = [];
        $http.get('/invoices/getInvoices').then(function (response) {
            $scope.isInvoiceLoading = false;
            response = response.data;

            if (response.status) {
                var invoices = AppUtils.pushKeys(response.invoices);
                $scope.invoicesList = invoices;

                console.log($scope.invoicesList);
            }
            else {
                $scope.invoiceError.message = response.message;
                AppUtils.showAlertBox($scope.invoiceError);
            }

        }, function () {
            $scope.isInvoiceLoading = false;
            $scope.invoiceError.message = 'Произошла системная ошибка. Повторите запрос позднее.';
            AppUtils.showAlertBox($scope.invoiceError);
        });
    };

    $scope.init = function () {
        $scope.loadInvoices();
    };

}]);
