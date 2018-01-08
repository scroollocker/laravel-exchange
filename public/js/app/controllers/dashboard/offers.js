app.controller('DashboardOffer', ['$scope', '$http', 'AppUtils', '$routeParams', '$q', function($scope, $http, AppUtils, $routeParams, $q) {
    $scope.selectedStep = 1;

    $scope.isSelect = function(step) {
        return step === $scope.selectedStep;
    };

    $scope.selectStep = function(step) {
        $scope.selectedStep = step;
    };

    $scope.invoiceError = {
        show: false,
        message: ''
    };

    $scope.isInvoiceLoading = false;

    $scope.invoice = {};

    $scope.invoiceId = $routeParams.invoiceId;
    $scope.offerId = $routeParams.offerId;

    $scope.loadInvoice = function() {
        var defer = $q.defer();

        if ($scope.invoiceId === undefined || $scope.invoiceId === null) {
            defer.reject('Неверный запрос');
        }
        else {
            var request = {
                invoice_id: $scope.invoiceId,
                offer_id: $scope.offerId
            };

            $http.post('/dashboard/getInvoice', request).then(function(response) {
                response = response.data;

                if (response.status) {
                    $scope.invoice = response.invoice;
                    $scope.offer = response.offer;
                    defer.resolve(true);
                }
                else {
                    defer.reject(response.message);
                }
            }, function() {
                defer.reject('Произошла системная ошибка');
            });
        }

        return defer.promise();
    }


}]);