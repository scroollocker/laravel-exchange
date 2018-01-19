
app.controller('OffersController', ['$scope', '$routeParams', 'AppUtils', '$location', '$http', function($scope, $routeParams, AppUtils, $location, $http) {
    $scope.invoiceError = {
        show: false,
        message: ''
    };

    $scope.invoiceId = $routeParams.invoiceId;
    $scope.detailOfferId = $routeParams.offerId;

    $scope.offers = [];
    $scope.invoice = {};
    $scope.offer = {};

    $scope.isOffersLoading = false;

    $scope.getOffers = function() {
        return $scope.offers;
    };

    $scope.loadOffers = function() {
        $scope.isOffersLoading = true;

        var request = {
            invoice_id: $scope.invoiceId
        };

        $http.post('/invoices/getOffers', request).then(function(response) {
            $scope.isOffersLoading = false;
            response = response.data;
            if (response.status) {
                $scope.offers = AppUtils.pushKeys(response.offers);
            }
            else {
                $scope.invoiceError.message = response.message;
                AppUtils.showAlertBox($scope.invoiceError);
            }
        }, function() {
            $scope.isOffersLoading = false;
            $scope.invoiceError.message = 'Произошла системная ошибка. Повторите запрос позднее.';
            AppUtils.showAlertBox($scope.invoiceError);
        });
    };

    $scope.loadInvoice = function() {
        $scope.invoice = {};
        $scope.offer = {};
        $scope.isOffersLoading = true;
        if ($scope.detailOfferId === undefined || $scope.detailOfferId === null) {
            $scope.invoiceError.message = 'Неверный запрос. Неверно переданы параметры.';
            AppUtils.showAlertBox($scope.invoiceError);
            return;
        }

        var request = {
            offer_id: $scope.detailOfferId
        };

        $http.post('/invoices/getOfferById', request).then(function(response) {
            $scope.isOffersLoading = false;


            response = response.data;

            if (response.status) {
                $scope.invoice = AppUtils.pushKey(response.invoice);
                $scope.offer = response.offer;
            }
            else {
                $scope.invoiceError.message = response.message;
                AppUtils.showAlertBox()
            }
        }, function() {
            $scope.isOffersLoading = false;
            $scope.invoiceError.message = 'Произошла системная ошибка. Повторите запрос позднее.';
            AppUtils.showAlertBox($scope.invoiceError);
        });
    };

    $scope.openOffer = function(offer) {
        //$location.path('/offers/open/'+offer.id);
        $location.path('/invoices/chat/'+offer.declare_id);
        $location.replace();
    };
    
    $scope.agreeOffer = function (id) {

        if (!confirm('Вы действительно хотите подтвердить предложение?')) {
            return;
        }

        $scope.isOffersLoading = true;
        
        var request = {
            'offer_id': id
        };
        
        $http.post('/invoices/agreeOffer', request).then(function (response) {
            response = response.data;
            
            if (response.status) {
                $location.path('/invoices');
                $location.replace();
            }
            else {
                $scope.invoiceError.message = response.message;
                AppUtils.showAlertBox($scope.invoiceError);
            }
        }, function () {
            $scope.invoiceError.message = 'Произошла системная ошибка. Повторите позднее.';
            AppUtils.showAlertBox($scope.invoiceError);
        });
    };

    $scope.disagreeOffer = function (id) {

        if (!confirm('Вы действительно хотите отменить предложение?')) {
            return;
        }

        $scope.isOffersLoading = true;

        var request = {
            'offer_id': id
        };

        $http.post('/invoices/disagreeOffer', request).then(function (response) {
            response = response.data;

            if (response.status) {
                $location.path('/invoices');
                $location.replace();
            }
            else {
                $scope.invoiceError.message = response.message;
                AppUtils.showAlertBox($scope.invoiceError);
            }
        }, function () {
            $scope.invoiceError.message = 'Произошла системная ошибка. Повторите позднее.';
            AppUtils.showAlertBox($scope.invoiceError);
        });
    };

    $scope.normalizeDate = function(date) {
        return AppUtils.normalizeDate(date);
    };

    $scope.init = function() {
        if ($scope.invoiceId === undefined || $scope.invoiceId === null) {
            $location.path('/invoices');
            $location.replace();
            return;
        }

        $scope.loadOffers();
    }
}]);
