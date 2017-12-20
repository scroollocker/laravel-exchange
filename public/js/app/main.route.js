app.config(['$routeProvider', function($routeProvider) {
    $routeProvider.when('/invoices/lists',  {
        templateUrl: '/invoices/list',
        controller: 'InvoicesController'
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

    $routeProvider.when('/invoices/add/:invoiceId?',  {
        templateUrl: '/invoices/add',
        controller: 'InvoicesController'
    });

    $routeProvider.when('/invoices/chat/:invoice_id',  {
        templateUrl: '/chat/base',
        controller: 'ChatTemplate'
    });

    $routeProvider.when('/invoices', {
        redirectTo: '/invoices/lists'
    });

    $routeProvider.otherwise({
        redirectTo: '/invoices/lists'
    });
}]);