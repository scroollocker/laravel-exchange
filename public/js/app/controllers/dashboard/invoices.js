app.controller('DashboardInvoices', ['$scope', '$http', 'AppUtils', '$location', function($scope, $http, AppUtils, $location) {

    $scope.invoices = [];
    $scope.currencies = [];
    $scope.filter = {};

    $scope.isInvoiceLoading = false;
    $scope.invoiceError = {
        show: false,
        message: ''
    };

    $scope.getInvoices = function() {
        return $scope.invoices;
    };

    $scope.getCurrencies = function() {
        return $scope.currencies;
    };

    $scope.doFilter = function() {
        console.log($scope.filter);
        $scope.loadInvoices();
    };

    $scope.clearFilter = function() {
        $scope.filter = {};
    };

    $scope.getSortItems = function () {
        var sortable = [
            {
                'name': 'Дата создания',
                'id': 'created_dt'
            },
            {
                'name': 'Дата окончания',
                'id': 'end_dt'
            },
            {
                'name': 'Сумма продажи',
                'id': 'sum_sell_nd'
            },
            {
                'name': 'Сумма покупки',
                'id': 'sum_buy_nd'
            },
            {
                'name': 'Валюта покупки',
                'id': 'cur_buy'
            },
            {
                'name': 'Валюта продажи',
                'id': 'cur_sell'
            }
        ];
        return sortable;
    };

    $scope.loadCurrencies = function() {
        $scope.isInvoiceLoading = true;
        $scope.currencies = [];

        $http.get('/dashboard/currencies-get').then(function(response){
            $scope.isInvoiceLoading = false;
            response = response.data;

            if (response.status) {
                $scope.currencies = response.currencies;
            }
            else {
                $scope.invoiceError.message = response.message;
                AppUtils.showAlertBox($scope.invoiceError);
            }
        }, function() {
            $scope.isInvoiceLoading = false;
            $scope.invoiceError.message = 'Произошла системная ошибка. Повторите позднее';
            AppUtils.showAlertBox($scope.invoiceError);
        })
    };

    $scope.loadInvoices = function() {
        $scope.isInvoiceLoading = true;
        $scope.invoices = [];

        var request = $scope.filter;

        $http.post('/dashboard/invoices-list-get', request).then(function(response){
            $scope.isInvoiceLoading = false;
            response = response.data;

            if (response.status) {
                $scope.invoices = response.invoices;
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

    $scope.sendOffer = function(invoice) {
        $location.path('/dashboard/editOffer/'+invoice.declare_id);
        $location.replace();
    };

    $scope.normalizeDate = function(date) {
        return AppUtils.normalizeDate(date);
    };

    $scope.init = function() {
        $scope.loadCurrencies();
        $scope.loadInvoices();
    }



}]);