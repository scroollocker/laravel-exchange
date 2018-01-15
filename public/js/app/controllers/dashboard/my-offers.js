app.controller('DashboardMyOffers', ['$scope', '$http', 'AppUtils', '$location', function($scope, $http, AppUtils, $location) {

    $scope.offers = [];

    $scope.isInvoiceLoading = false;
    $scope.invoiceError = {
        show: false,
        message: ''
    };

    $scope.getOffers = function() {
        return $scope.offers;
    };


    $scope.loadOffers = function() {
        $scope.isInvoiceLoading = true;
        $scope.offers = [];

        $http.get('/dashboard/getOffers').then(function(response){
            $scope.isInvoiceLoading = false;
            response = response.data;

            if (response.status) {
                $scope.offers = response.offers;
            }
            else {
                $scope.invoiceError.message = response.message;
                AppUtils.showAlertBox($scope.invoiceError);
            }
        }, function() {
            $scope.isInvoiceLoading = false;
            $scope.invoiceError.message = 'Произошла системная ошибка. Повторите позднее';
            AppUtils.showAlertBox($scope.invoiceError);
        });
    };

    $scope.init = function() {
        $scope.loadOffers();
    }



}]);