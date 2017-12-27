app.controller('InvoicesListController', ['$scope', '$http', 'AppUtils','$location', function ($scope, $http, AppUtils, $location) {

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

    $scope.editInvoice = function (id) {
        $location.path('/invoices/invoice/'+id);
        $location.replace();
    };

    $scope.removeInvoice = function (id) {
        if (confirm('Вы уверены, что хотите удалить заявку?')){
            $scope.isInvoiceLoading = true;

            var request = {
                id: id
            };

            $http.post('/invoices/remove', request).then(function (response) {
                $scope.isInvoiceLoading = false;
                response = response.data;
                if (response.status) {
                    $scope.loadInvoices();
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
        }
    };

    $scope.init = function () {
        $scope.loadInvoices();
    };

}]);
