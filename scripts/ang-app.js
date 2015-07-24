var app = angular.module("band-sched",[]);
app.controller('LoginCtrl',LoginCtrl);
app.controller('HeadCtrl',HeadCtrl);

function HeadCtrl($scope, $http){
        $scope.title = "Band Schedule";
}


function LoginCtrl($scope, $http){
	$scope.title = "Band Schedule";
	$.cookie('fccw','auth',Date()+60*60*24*30);
}
