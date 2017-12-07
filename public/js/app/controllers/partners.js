/**
 * Created by scroollocker on 04.12.17.
 */

app.controller('UserPartners', ['$scope', '$http', function($scope, $http) {
    $scope.partnersLoading = false;
    $scope.userLoading = false;

    $scope.parners = [];
    $scope.userList = [];
    $scope.isAddView = false;

    $scope.paginator = {
        "total": 50,
        "per_page": 15,
        "current_page": 1,
        "last_page": 4,
        "first_page_url": "http://laravel.app?page=1",
        "last_page_url": "http://laravel.app?page=4",
        "next_page_url": "http://laravel.app?page=2",
        "prev_page_url": null,
        "path": "http://laravel.app",
        "from": 1,
        "to": 15
    };

    $scope.currentPage = 1;
    $scope.pageCount = 10;
    $scope.perViewPages = 4;
    $scope.userSearch = '';
    $scope.selectedUser = {};

    $scope.getPages = function() {
        var fView = $scope.paginator.current_page - $scope.perViewPages;
        if (fView <= 0) {
            fView = 1;
        }
        var lView = $scope.paginator.current_page + $scope.perViewPages;
        if (lView > $scope.paginator.last_page) {
            lView = $scope.paginator.last_page;
        }
        var pages = [];
        for (var i = fView; i <= lView; i++) {
            pages.push(i);
        }

        return pages;
    };

    $scope.isFirstPage = function() {
        if ($scope.paginator.current_page == 1) {
            return true;
        }
        else {
            return false;
        }
    };

    $scope.isLastPage = function() {
        if ($scope.paginator.current_page = $scope.paginator.last_page) {
            return true;
        }
        else {
            return false;
        }
    };

    $scope.getNextPage = function() {
        var nextPage = $scope.paginator.current_page + 1;
        if (nextPage > $scope.paginator.last_page) {
            nextPage = $scope.paginator.last_page;
        }
        return nextPage;
    };

    $scope.getPrevPage = function() {
        var prevPage = $scope.paginator.current_page - 1;
        if (prevPage <= 0) {
            prevPage = 1;
        }
        return prevPage;
    };

    $scope.getPartners = function() {
        return $scope.parners;
    };

    $scope.getUserList = function() {
        return $scope.userList;
    };

    $scope.loadUsers = function (page) {
        $scope.userLoading = true;
        var request = {
            'page': page,
            'q': $scope.userSearch
        };

        $http.post('/user/partners/userlist', request).then(function (response) {
            $scope.userLoading = false;
            response = response.data;
            if (response.status) {
                $scope.paginator = response.paginator;
                if ($scope.paginator.data !== null && $scope.paginator.data !== undefined) {
                    $scope.userList = $scope.paginator.data;
                }
                else {
                    $scope.userList = [];
                }
            }
            else {

                alert(response.message);
            }
        }, function (response) {
            $scope.userLoading = false;
            alert('Произошла системная ошибка');
        });
    };

    $scope.showAddView = function (user) {
        $scope.selectedUser = angular.copy(user);
        $scope.isAddView = true;
    };

    $scope.closeAddView = function () {
        $scope.isAddView = false;
    };

    $scope.loadPartners = function() {
        $scope.partnersLoading = true;

        $http.get('user/partners/get').then(function(response){
            $scope.partnersLoading = false;
            response = response.data;
            if (response.status) {
                $scope.parners = response.partners;
            }
            else {
                alert(response.message);
            }
        }, function(response) {
            alert('Произошла системная ошибка');
            $scope.partnersLoading = false;
        });
    };

    $scope.setPartnerState = function (partner) {
        var newState = (partner.state == 1) ? 2 : 1;

        var request = {
            'partner_id': partner.partner_id,
            'state': newState
        };

        $scope.partnersLoading = true;
        $http.post('/user/partners/state', request).then(function (response) {
            $scope.partnersLoading = false;
            response = response.data;
            if (response.status) {
                $scope.loadPartners();
            }
            else {
                alert(response.message);
            }
        }, function (response) {
            $scope.partnersLoading = false;
            alert('Произошла системная ошибка');
        });
    };

    $scope.removePartner = function (partner) {

        if (!confirm('Вы действительно хотите удалить партнера?')) {
            return;
        }
        var request = {
            'partner_id' : partner.partner_id
        };

        $http.post('user/partners/remove', request).then(function (response) {
            response = response.data;

            if (response.status) {
                $scope.loadPartners();
            }
            else {
                alert(response.message);
            }
        }, function (response) {
            alert('Произошла системная ошибка');
        });
    };

    $scope.addPartner = function () {
        $scope.isAddView = false;
        $('#addPartnerModal').modal('show');
        $scope.loadUsers(1);
    };

    $scope.loadPartners();
}]);
