var adminApp = angular.module('adminApp', ['ngRoute']);
    
adminApp.config(function ($routeProvider) {
    $routeProvider
        .when('/lstpcategory', {
            templateUrl: '/adm/ajax/template/lst-pcategory-angular.tpl',
            controller: 'ListPCategoryCtrl'
        })
        .when('/addpcategory', {
            templateUrl: '/adm/ajax/template/add-pcategory-angular.tpl',
            controller: 'AddPCategoryCtrl'
        })
        .when('/editpcategory/:id', {
            templateUrl: '/adm/ajax/template/add-pcategory-angular.tpl',
            controller: 'EditPCategoryCtrl'
        })
        .otherwise({ redirectTo: '/lstpcategory' });
});

adminApp.controller('ListPCategoryCtrl', function ($scope, $http) {
    $scope.pcatUrl = '/adm/ajax/pcategory/';
    $http.get($scope.pcatUrl).success(function (response) {

        $scope.pcategories = response.data;
        $scope.count = response.count;
    });

    $scope.deletePcategory = function (id) {
        if (confirm('Вы уверены, что хотите удалить категорию?')) {
            $http.delete($scope.pcatUrl + id, {id: id}).success(function (response) {
                $scope.pcategories = response.data;
                $scope.count = response.count;
            });
            
        }
    };
});

adminApp.controller('AddPCategoryCtrl', function ($scope, $http) {
    $scope.pcatUrl = '/adm/ajax/pcategory/';
    $http.get($scope.pcatUrl).success(function (response) {

        $scope.pcategories = response.data;
        $scope.count = response.count;
    });
    /*
    console.log('adding');

    $scope.deletePcategory = function (id) {
        if (confirm('Вы уверены, что хотите удалить категорию?')) {
            $http.delete($scope.pcatUrl + id, {id: id}).success(function (response) {
                $scope.pcategories = response.data;
                $scope.count = response.count;
            });
            
        }
    };*/
});


adminApp.controller('EditPCategoryCtrl', function ($scope, $http, $routeParams) {
    $scope.pcatUrl = '/adm/ajax/pcategory/';

    var id = $routeParams.id;

    $scope.loadPcategory = function (id) {
        $http.get($scope.pcatUrl + id).success(function (response) {
            $scope.pcategory = response.data;
            $scope.count = response.count;
        });        
    }

    $scope.savePcategory = function (id) {
        $http.put($scope.pcatUrl + id, {obj: $scope.pcategory}).success(function (response) {
            alert('saved');
        });
        
    
    };
});
