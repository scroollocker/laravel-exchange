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
            currency_buy: $scope.invoice.currency_sell.id,
            currency_sell: $scope.invoice.currency_buy.id
        };

        var deffer = $q.defer();

        $scope.acc = {
            sell : [],
            buy : []
        };

        $http.post('/dashboard/getAcc', request).then(function (response) {
            response = response.data;

            if (response.status) {
                //var items = [];
                //
                //for (var i = 0; i < response.acc_sell.length; i++) {
                //    var accItem = response.acc_sell[i];
                //    if ($scope.offer.sum_sell_nd <= accItem.saldo) {
                //        items.push(accItem);
                //    }
                //}
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

        var a = parseFloat(offer.sum_sell_nd);
        var b = parseFloat(offer.sum_buy_nd);
        console.log(a, b);
        if (a <= 0 && b <= 0) {
            $scope.invoiceError.message = 'Сумма продажи и сумма покупки не должны быть отрицательными';
            AppUtils.showAlertBox($scope.invoiceError);
            return;
        }

        $scope.isInvoiceLoading = true;
        $scope.loadAccounts().then(function () {
            $scope.isInvoiceLoading = false;
            $scope.offer.sum_buy_nd = $scope.getFinalSum();
            $scope.offer.currency_buy = $scope.invoice.currency_sell;
            $scope.offer.currency_sell = $scope.invoice.currency_buy;
            $scope.selectStep(2);
        }, function (err) {
            $scope.isInvoiceLoading = false;
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

        if (!moment(offer.endDate, 'DD.MM.YYYY HH:mm:ss').isAfter(new Date())) {
            $scope.invoiceError.message = 'Дата должна быть больше текущей';
            AppUtils.showAlertBox($scope.invoiceError);
            return;
        }


        console.log($scope.offer);

        $scope.selectStep(4);
    };

    $scope.createOffer = function (offer) {

        $scope.isInvoiceLoading = true;

        var requst = {
            invoice_id: $scope.invoice.declare_id,
            sum_sell_nd: offer.sum_sell_nd,
            sum_buy_nd: offer.sum_buy_nd,
            course_nd: offer.course_nd,
            currency_buy: offer.currency_buy.id,
            currency_sell: offer.currency_sell.id,
            acc_dt: offer.acc_dt.acc_id,
            acc_ct: offer.acc_ct.acc_id,
            endDate: AppUtils.mysqlDate(offer.endDate),
            comment: offer.comment
        };

        if (offer.id !== undefined && offer.id !== null) {
            offer_id: offer.offer_id
        }

        $http.post('/dashboard/createOffer', requst).then(function (response) {
            $scope.isInvoiceLoading = false;
            response = response.data;

            if (response.status) {
                /* TODO: REDIRECT TO MY OFFERS */
                $location.path('/my-offers');
                $location.replace();
            }
            else {
                $scope.invoiceError.message = response.message;
                AppUtils.showAlertBox($scope.invoiceError);
            }
        }, function () {
            $scope.isInvoiceLoading = false;
            $scope.invoiceError.message = 'Произошла системная ошибка, попробуйте позднее';
            AppUtils.showAlertBox($scope.invoiceError);
        });

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