
WeatherApp.controller('HomeController',['$scope','$routeParams','OWMService','DashboardService',function($scope,$routeParams,owm,dashboard){
    $scope.owmCitiesData = [];
    $scope.dashboards = []
    $scope.selectedDashboardId = -1;
    $scope.mapsActive = (GMAP_API_KEY !== undefined && GMAP_API_KEY !== '');
    if ($routeParams.dashboardId !== undefined) {
        $scope.selectedDashboardId = $routeParams.dashboardId;
    }
    
    dashboard.all().then(function(data){
        $scope.dashboards = data;
        var defaultId = $scope.selectedDashboardId;
        if(defaultId === -1 && $scope.dashboards.length > 0){
            defaultId = $scope.dashboards[0].id;
        }
        $scope.display(defaultId);
    });

    $scope.display = function(dashboardId){
	if(dashboardId === -1) return;
        $scope.selectedDashboardId = dashboardId;
        dashboard.cities(dashboardId).then(function(data){
            $scope.cities = data;
            $scope.owmCitiesData = [];
            for(var i = 0; i < $scope.cities.length;i++){
                owm.byId($scope.cities[i].id).then(function(data){
                    $scope.owmCitiesData.push(data);
                });
            }
        });
    };
    
    $scope.loadMap = function(cityId){
        if($scope.mapsActive){
            var city = $scope.owmCitiesData.getById(cityId);
            var src = 'https://www.google.com/maps/embed/v1/view?zoom=8&center='+city.coord.lat+','+city.coord.lon+'&key='+GMAP_API_KEY;
            $('#map-' + cityId + ' iframe').prop('src',src);
            $('#map-' + cityId).removeClass('hidden');
        }
    };

}]);


WeatherApp.controller('SettingsController',['$scope','$routeParams','DashboardService',function($scope,$routeParams,dashboard){
    $scope.selectedDashboardId = -1;
    $scope.message = '';
	$scope.dashboards = [];
    if ($routeParams.dashboardId !== undefined) {
        $scope.selectedDashboardId = $routeParams.dashboardId;
    }
    
    $scope.load = function(){
        dashboard.all().then(function(data){
            $scope.dashboards = data;
            var defaultId = $scope.selectedDashboardId;
            if(defaultId === -1 && $scope.dashboards.length > 0){
				defaultId = $scope.dashboards[0].id;
			}
            $scope.display(defaultId);
        });
    };
    
    $scope.load();
    
    $scope.display = function(dashboardId){
		if(dashboardId === -1) return;
        $scope.dashboardCities = [];
        $scope.selectedDashboardId = dashboardId;
        dashboard.cities(dashboardId).then(function(data){
            $scope.dashboardCities = data;
        });
    };    
    
    $scope.keyword = '';
    $scope.listCity = function(keyword){
        if(keyword.length > 2){
            dashboard.startWith(keyword).then(function(data){
                $scope.cities = data;
            });
        }else{
            $scope.cities = [];
        }
    };
    
    $scope.clearSearch = function(){
        $scope.cities = [];
        $scope.keyword = '';  
    };
    
    $scope.add = function(cityId){
        if($scope.selectedDashboardId !== -1){
            dashboard.add($scope.selectedDashboardId,cityId).then(function(success){
                if(success){
                    $scope.message = "City added";
                    $scope.messageClass = "alert-success";
                    $scope.dashboardCities.push($scope.cities.getById(cityId));
                }else{
                    $scope.message = "Error while adding city";
                    $scope.messageClass = "alert-warning";
                }    
                $scope.clearSearch();
            });
        }
    };
    
    $scope.remove = function(cityId){
        if($scope.selectedDashboardId !== -1){
            dashboard.remove($scope.selectedDashboardId,cityId).then(function(success){
                if(success){
                    $scope.message = "City deleted";
                    $scope.messageClass = "alert-success";
                    $scope.dashboardCities.remove($scope.dashboardCities.getById(cityId));
                }else{
                    $scope.message = "Error while removing city";
                    $scope.messageClass = "alert-warning";
                }
            });
        }
    };
    
    $scope.create = function(dashboardName){
        dashboard.create(dashboardName).then(function(success){
            if(success){
                $scope.message = "Dashboard created";
                $scope.messageClass = "alert-success";
                $scope.load();
            }else{
                $scope.message = "Error while creating a Dashboard";
                $scope.messageClass = "alert-warning";
            }
        });
    };
    
    $scope.delete = function(dashboardId){
        dashboard.delete(dashboardId).then(function(success){
            if(success){
                $scope.message = "Dashboard deleted";
                $scope.messageClass = "alert-success";
                $scope.load();
            }else{
                $scope.message = "Error while removing dashboard";
                $scope.messageClass = "alert-warning";
            }
        });
    };
}]);