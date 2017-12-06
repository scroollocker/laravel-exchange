/**
 * Created by scroollocker on 04.12.17.
 */

app.controller('UserPartners', ['$scope', '$http', function($scope, $http) {
    $scope.partnersLoading = false;

    $scope.parners = [];

    $scope.getPartners = function() {
        return $scope.parners;
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
            $scope.partnersLoading = false;
            alert('Произошла системная ошибка');
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

    $scope.loadPartners();
}]);
