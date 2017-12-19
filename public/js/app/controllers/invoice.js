/**
 * Created by scroollocker on 28.10.17.
 */

app.controller('InvoicesController', ['$scope', '$http', 'AppUtils', '$filter', '$location', function($scope, $http, AppUtils, $filter, $location) {
    $scope.step = 1;
    $scope.selectStep = function(step) {
        $scope.step = step;
    };

    $scope.isSelect = function(step) {
        return ($scope.step == step && $scope.isInvoiceLoading == false);
    };

    $scope.invoice = {};
    $scope.invoiceError = {
        show: false,
        message: ''
    };
    $scope.isInvoiceLoading = false;
    $scope.currences = [];
    $scope.accounts = {
        cur_1: [],
        cur_2: []
    };

    $scope.getAccForCur1 = function () {
        return $scope.accounts.cur_1;
    };

    $scope.getAccForCur2 = function () {
        return $scope.accounts.cur_2;
    };

    $scope.getCurrencies = function() {
        return $scope.currences;
    };

    $scope.getPartners = function () {
        return $scope.invoice.partners;
    };

    $scope.getPartnersAutoconfirm = function () {
        return $filter('filter')($scope.invoice.partners, {autoconfirm: 1});
    };

    $scope.loadCurrencies = function() {
        $scope.isInvoiceLoading = true;
        $http.get('/invoices/getCurrences').then(function(response) {
            $scope.isInvoiceLoading = false;
            response = response.data;
            if (response.status) {
                $scope.currences = response.currences;
            }
            else {
                $scope.invoiceError.message = response.message;
                AppUtils.showAlertBox($scope.invoiceError);
            }
        }, function() {
            $scope.isInvoiceLoading = false;
            $scope.invoiceError.message = 'Во время загрузки валюты произошла ошибка. Обновите страницу';
            AppUtils.showAlertBox($scope.invoiceError);
        })
    };

    $scope.loadAccounts = function() {
        $scope.isInvoiceLoading = true;

        var request = {
            cur_1_id: $scope.invoice.cur_1.id,
            cur_2_id: $scope.invoice.cur_2.id
        };

        $http.post('/invoices/getAccounts', request).then(function(response) {
            $scope.isInvoiceLoading = false;
            response = response.data;
            if (response.status) {
                $scope.accounts.cur_1 = response.accounts_cur_1;
                $scope.accounts.cur_2 = response.accounts_cur_2;
            }
            else {
                $scope.invoiceError.message = response.message;
                AppUtils.showAlertBox($scope.invoiceError);
            }
        }, function() {
            $scope.isInvoiceLoading = false;
            $scope.invoiceError.message = 'Во время загрузки валюты произошла ошибка. Обновите страницу';
            AppUtils.showAlertBox($scope.invoiceError);
        })
    };

    $scope.loadPartners = function () {
        $scope.isInvoiceLoading = true;

        $http.get('/invoices/getPartners').then(function (response) {
            $scope.isInvoiceLoading = false;
            response = response.data;

            if (response.status) {
                $scope.invoice.autoconfirm = response.autoconfirm;
                $scope.invoice.partners = response.partners;
            }
            else {
                $scope.invoiceError.message = response.message;
                AppUtils.showAlertBox($scope.invoiceError);
            }

        }, function (response) {
            $scope.isInvoiceLoading = false;
            $scope.invoiceError.message = 'Произошла системная ошибка. Обновите страницу.';
            AppUtils.showAlertBox($scope.invoiceError);
        })
    };

    $scope.loadInvoiceById = function(id) {
        $scope.isInvoiceLoading = true;
        var request = {
            invoiceId: id
        };

        $http.post('/invoice/getInvoice', request).then(function(response) {
            $scope.isInvoiceLoading = false;
            response = response.data;
            if (response.status) {
                $scope.invoice = response.invoice;
            }
            else {
                $location.path('/invoices');
                $location.replace();
            }
        }, function() {
            $scope.isInvoiceLoading = false;
            $scope.invoiceError.message = 'Произошла сисемная ошибка';
            AppUtils.showAlertBox($scope.invoiceError);
        })
    };

    $scope.confirmInvoiceStep1 = function (invoice, form) {
        if (form.$invalid) {
            $scope.invoiceError.message = 'Не все поля заполнены верно';
            AppUtils.showAlertBox($scope.invoiceError);
            return;
        }

        if (invoice.type === undefined) {
            $scope.invoiceError.message = 'Не заполнен тип операции';
            AppUtils.showAlertBox($scope.invoiceError);
            return;
        }

        if (invoice.cur_1.id === invoice.cur_2.id) {
            $scope.invoiceError.message = 'Валюта источник и валюта назначение должны отличаться';
            AppUtils.showAlertBox($scope.invoiceError);
            return;
        }

        $scope.invoice.final_sum = $scope.computeCursSum();

        $scope.loadAccounts();

        $scope.selectStep(2);
    };

    $scope.confirmInvoiceStep2 = function (invoice, form) {
        if (form.$invalid) {
            $scope.invoiceError.message = 'Не все поля заполнены верно';
            AppUtils.showAlertBox($scope.invoiceError);
            return;
        }

        $scope.loadPartners();

        $scope.selectStep(3);
    };

    $scope.confirmInvoiceStep3 = function (invoice, form) {
        if (form.$invalid) {
            $scope.invoiceError.message = 'Не все поля заполнены верно';
            AppUtils.showAlertBox($scope.invoiceError);
            return;
        }

        console.log(invoice);

        $scope.selectStep(4);
    };

    $scope.computeCursSum = function () {
        if ($scope.invoice.cur_sum !== undefined && $scope.invoice.cur_curs !== undefined) {
            return $scope.invoice.cur_sum * $scope.invoice.cur_curs;
        }
        else {
            return 0;
        }
    };

    $scope.init = function () {

        if ($routeParams.invoiceId !== undefined) {
            $scope.loadInvoiceById($routeParams.invoiceId);
        }
        else {
            $scope.loadCurrencies();
        }

    };

    $scope.init();
}]);
