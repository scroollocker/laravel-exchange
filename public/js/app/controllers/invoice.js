/**
 * Created by scroollocker on 28.10.17.
 */

app.controller('InvoicesController', ['$scope', '$http', 'AppUtils', '$filter', '$location', '$routeParams', function($scope, $http, AppUtils, $filter, $location, $routeParams) {
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

    $scope.isAvalibleLoading = false;

    $scope.avalibleInvoices = [];

    $scope.getAvalible = function() {
        return $scope.avalibleInvoices;
    }

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
                var items = [];

                if ($scope.invoice.type == '2') {
                    for (var i = 0; i < response.accounts_cur_1.length; i++) {
                        var accItem = response.accounts_cur_1[i];
                        if (parseFloat($scope.invoice.cur_sum) <= parseFloat(accItem.saldo)) {
                            items.push(accItem);
                        }
                    }

                    $scope.accounts.cur_1 = items;
                    $scope.accounts.cur_2 = response.accounts_cur_2;
                }
                if ($scope.invoice.type == '1') {
                    for (var i = 0; i < response.accounts_cur_2.length; i++) {
                        var accItem = response.accounts_cur_2[i];
                        if (parseFloat($scope.invoice.final_sum) <= parseFloat(accItem.saldo)) {
                            items.push(accItem);
                        }
                    }

                    $scope.accounts.cur_1 = response.accounts_cur_1;
                    $scope.accounts.cur_2 = items;
                }

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
                if ($scope.invoice.id === undefined || $scope.invoice.id === null) {
                    $scope.invoice.autoconfirm = response.autoconfirm;
                }
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
            invoice_id: id
        };

        $http.post('/invoices/getInvoiceById', request).then(function(response) {
            $scope.isInvoiceLoading = false;
            response = response.data;
            if (response.status) {

                var tmpInvoice = response.invoice;

                $scope.invoice = AppUtils.pushKey(tmpInvoice);

                $scope.loadCurrencies();

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

    $scope.loadAvalible = function() {
        var request = {};
        if ($scope.invoice.type == '2') {
            if ($scope.invoice.cur_1) {
                request.buy_cur = $scope.invoice.cur_1;
            }
            if ($scope.invoice.cur_2) {
                request.sell_cur = $scope.invoice.cur_2;
            }
            if ($scope.invoice.cur_sum !== undefined && $scope.invoice.cur_sum !== null && parseFloat($scope.invoice.cur_sum) > 0) {
                request.buy_from = $scope.invoice.cur_sum;
            }
            if ($scope.invoice.final_sum !== undefined && $scope.invoice.final_sum !== null && parseFloat($scope.invoice.final_sum) > 0) {
                request.sell_from = $scope.invoice.final_sum;
            }
        }
        else {
            if ($scope.invoice.cur_1) {
                request.sell_cur = $scope.invoice.cur_1;
            }
            if ($scope.invoice.cur_2) {
                request.buy_cur = $scope.invoice.cur_2;
            }
            if ($scope.invoice.cur_sum !== undefined && $scope.invoice.cur_sum !== null && parseFloat($scope.invoice.cur_sum) > 0) {
                request.sell_from = $scope.invoice.cur_sum;
            }
            if ($scope.invoice.final_sum !== undefined && $scope.invoice.final_sum !== null && parseFloat($scope.invoice.final_sum) > 0) {
                request.buy_from = $scope.invoice.final_sum;
            }
        }
        console.log(request);
        if (Object.keys(request).length == 0) return;


        //$scope.avalibleInvoices = [];
        $scope.isAvalibleLoading = true;
        $http.post('/dashboard/invoices-list-get', request).then(function(response) {
            $scope.avalibleInvoices = [];
            $scope.isAvalibleLoading = false;
            response = response.data;
            if (response.status) {
                $scope.avalibleInvoices = response.invoices;
            }
        }, function() {
            $scope.avalibleInvoices = [];
            $scope.isAvalibleLoading = false;
        });
    };

    $scope.cancelCreateInvoice = function () {
        if (confirm('Все изменения будут потеряны. Вы уверены?')) {
            $location.path('/invoices');
            $location.replace();
        }
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

        $scope.invoice.acc_1 = null;
        $scope.invoice.acc_2 = null;

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

        if (!moment(invoice.endDate, 'DD.MM.YYYY HH:mm:ss').isAfter(new Date())) {
            $scope.invoiceError.message = 'Дата должна быть больше текущей';
            AppUtils.showAlertBox($scope.invoiceError);
            return;
        }

        console.log(invoice);

        $scope.selectStep(4);
    };

    $scope.saveInvoice = function (invoice) {
        var request = {
            'acc_1': invoice.acc_1.id,
            'acc_2': invoice.acc_2.id,
            'autoconfirm': invoice.autoconfirm,
            'cur_1': invoice.cur_1.id,
            'cur_2': invoice.cur_2.id,
            'sum_1': invoice.cur_sum,
            'sum_2': invoice.final_sum,
            'type': invoice.type,
            'endDate': AppUtils.mysqlDate(invoice.endDate)

        };
        if (invoice.id !== undefined && invoice.id !== null) {
            request['id'] = invoice.id;
            request['action'] = 'edit';
        }
        else {
            request['action'] = 'add';
        }

        if (invoice.autoconfirm == '0') {
            var partners = $scope.getPartnersAutoconfirm();
            request['partners'] = partners;
        }
        else {
            request['partners'] = [];
        }

        $http.post('/invoices/save', request).then(function (response) {
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
            $scope.invoiceError.message = 'Произошла системная ошибка. Попробуйте еще раз.';
            AppUtils.showAlertBox($scope.invoiceError);
        });
    };

    $scope.computeCursSum = function () {
        if ($scope.invoice.cur_sum !== undefined && $scope.invoice.cur_curs !== undefined) {
            var result = $scope.invoice.cur_sum * $scope.invoice.cur_curs;
            var float = parseFloat(result);

            return float.toFixed(2);
            //return result;
        }
        else {
            return 0;
        }
    };

    $scope.sendOffer = function(invoice) {
        $location.path('/dashboard/editOffer/'+invoice.declare_id);
        $location.replace();
    };

    $scope.normalizeDate = function(date) {
        return AppUtils.normalizeDate(date);
    };

    $scope.initAdd = function () {

        if ($routeParams.invoiceId !== undefined) {
            $scope.loadInvoiceById($routeParams.invoiceId);
        }
        else {
            $scope.loadCurrencies();
        }

        $scope.$watch('invoice', function() {
            $scope.loadAvalible();
        }, true);

    };

    $scope.getCurrentCourse = function () {
        if ($scope.invoice.cur_1 && $scope.invoice.cur_2) {

            var request = {
                type: $scope.invoice.type,
                currency_1: $scope.invoice.cur_1.id,
                currency_2: $scope.invoice.cur_2.id
            };

            $http.post('invoices/getCourse', request).then(function (response) {
                response = response.data;

                if (response.status) {
                    var float = parseFloat(response.course);

                    $scope.invoice.cur_curs = float.toFixed(4);
                    //$scope.invoice.cur_curs = response.course;
                }
                else {
                    console.log(response.message);
                }

            }, function () {
                $scope.invoiceError.message = "Произошла системная ошибка при получении курса";
                AppUtils.showAlertBox($scope.invoiceError);
            });
        }
    }


}]);
