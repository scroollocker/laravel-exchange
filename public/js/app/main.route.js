app.config(['$routeProvider', function($routeProvider) {
    $routeProvider.when('/invoices/lists',  {
        templateUrl: '/invoices/list',
        controller: 'InvoicesListController'
    });

    $routeProvider.when('/user/settings',  {
        templateUrl: '/user/settings',
        controller: 'UserSettings'
    });

    $routeProvider.when('/user/accounts',  {
        templateUrl: '/user/accounts',
        controller: 'UserAccounts'
    });

    $routeProvider.when('/user/partners',  {
        templateUrl: '/user/partners',
        controller: 'UserPartners'
    });

    $routeProvider.when('/invoices/invoice/:invoiceId?',  {
        templateUrl: '/invoices/add',
        controller: 'InvoicesController'
    });

    $routeProvider.when('/invoice/inBank/:invoiceId?',  {
        templateUrl: '/invoices/inBank',
        controller: 'InvoiceBankController'
    });

    $routeProvider.when('/offers/byInvoice/:invoiceId',  {
        templateUrl: '/invoices/offers',
        controller: 'OffersController'
    });

    $routeProvider.when('/offers/open/:offerId',  {
        templateUrl: '/invoices/offersDetail',
        controller: 'OffersController'
    });

    $routeProvider.when('/invoices/chat/:invoice_id',  {
        templateUrl: '/chat/base',
        controller: 'ChatTemplate'
    });

    $routeProvider.when('/dashboard/invoices',  {
        templateUrl: '/dashboard/invoices-list',
        controller: 'DashboardInvoices'
    });

    $routeProvider.when('/dashboard/editOffer/:invoiceId/:offerId?', {
        templateUrl: '/dashboard/offer-add',
        controller: 'DashboardOffer'
    });

    $routeProvider.when('/invoices', {
        redirectTo: '/invoices/lists'
    });

    $routeProvider.otherwise({
        redirectTo: '/invoices/lists'
    });
}]);