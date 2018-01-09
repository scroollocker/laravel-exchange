app.controller('DashboardOffer', ['$scope', '$http', 'AppUtils', '$routeParams', '$q', '$location', function($scope, $http, AppUtils, $routeParams, $q, $location) {
    $scope.selectedStep = 1;

    $scope.isSelect = function(step) {
        return step == $scope.selectedStep;
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
    $scope.offer = {};

    $scope.acc = {
        sell : [],
        buy : []
    };

    $scope.invoiceId = $routeParams.invoiceId;
    $scope.offerId = $routeParams.offerId;

    $scope.getAccForCur1 = function () {
        return $scope.acc.sell;
    };

    $scope.getAccForCur2 = function () {
        return $scope.acc.buy;
    };

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
                    if ($scope.offer === null || $scope.offer === undefined) {
                        $scope.offer = {
                            sum_sell_nd: $scope.invoice.sum_sell_nd,
                            course_nd: $scope.invoice.course_nd
                        };
                    }
                    defer.resolve(true);
                }
                else {
                    defer.reject(response.message);
                }
            }, function() {
                defer.reject('Произошла системная ошибка');
            });
        }

        return defer.promise;
    };

    $scope.getFinalSum = function () {

        if ($scope.offer.sum_sell_nd !== undefined &&
            $scope.offer.sum_sell_nd !== null &&
            $scope.offer.course_nd !== undefined &&
            $scope.offer.course_nd !== null) {

            console.log($scope.offer);

            return parseFloat($scope.offer.sum_sell_nd) * parseFloat($scope.offer.course_nd);
        }

        return 0
    };

    $scope.loadAccounts = function () {
        var request = {
            currency_buy: $scope.invoice.currency_buy.id,
            currency_sell: $scope.invoice.currency_sell.id
        };

        var deffer = $q.defer();

        $scope.acc = {
            sell : [],
            buy : []
        };

        $http.post('/dashboard/getAcc', request).then(function (response) {
            response = response.data;

            if (response.status) {
                $scope.acc.sell = response.acc_sell;
                $scope.acc.buy = response.acc_buy;

                deffer.resolve(true);
            }
            else {
                deffer.reject(response.message);
            }
        }, function () {
            deffer.reject('Произошла системная ошибка. Повторите запрос еще раз.');
        });

        return deffer.promise;
    };

    $scope.confirmOfferStep1 = function (offer, form) {
        if (form.$invalid) {
            $scope.invoiceError.message = 'Не все поля заполнены корректно';
            AppUtils.showAlertBox($scope.invoiceError);
            return;
        }

        $scope.loadAccounts().then(function () {
            $scope.selectStep(2);
        }, function (err) {
            $scope.invoiceError.message = err;
            AppUtils.showAlertBox($scope.invoiceError);
        });
    };

    $scope.confirmOfferStep2 = function (offer, form) {
        if (form.$invalid) {
            $scope.invoiceError.message = 'Не все поля заполнены верно';
            AppUtils.showAlertBox($scope.invoiceError);
            return;
        }

        $scope.selectStep(3);
    };

    $scope.confirmOfferStep3 = function (offer, form) {
        if (form.$invalid) {
            $scope.invoiceError.message = 'Не все поля заполнены верно';
            AppUtils.showAlertBox($scope.invoiceError);
            return;
        }

        if (!moment(invoice.endDate).isAfter(new Date())) {
            $scope.invoiceError.message = 'Дата должна быть больше текущей';
            AppUtils.showAlertBox($scope.invoiceError);
            return;
        }

        $scope.selectStep(4);
    };

    $scope.cancelCreateOffer = function () {
        if (!confirm('Вы действительно хотите отменить создание предложение?')) {
            return;
        }
        $location.path('/dashboard/invoices');
        $location.replace();
    };

    $scope.init = function () {
        $scope.isInvoiceLoading = true;
        $scope.loadInvoice().then(function (result) {
            $scope.isInvoiceLoading = false;

        }, function (message) {
            $scope.isInvoiceLoading = false;
            $scope.invoiceError.message = message;
            AppUtils.showAlertBox($scope.invoiceError);
        });
    };


}]);