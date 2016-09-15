
WeatherApp.service('DashboardService',['$http',function($http){        
    function all(){
        return call({
            controller : 'dashboard',
            action     : 'all'
        });
    }
    
    function create(name){
        return call({
            controller : 'dashboard',
            action     : 'create',
            dashboardName:name
        });
    }
    
    function remove(id){
        return call({
            controller : 'dashboard',
            action     : 'delete',
            dashboardId: id
        });
    }
    
    function cities(dashboardId){
        return call({
            controller : 'dashboard',
            action     : 'cities',
            dashboardId : dashboardId
        });
    }
    function startWith(word){
        return call({
            controller : 'city',
            action     : 'start_with',
            word : word
        });

    }
    function addCity(dashboardId,cityId){
        return call({
            controller : 'dashboard',
            action     : 'add_city',
            dashboardId : dashboardId,
            cityId:cityId
        });
    }
    function removeCity(dashboardId,cityId){
        return call({
            controller : 'dashboard',
            action     : 'delete_city',
            dashboardId : dashboardId,
            cityId:cityId
        });
    }
    
    function call(params){
        return $http.get(WS_URL,{params:params}).then(handleSuccess,handleError);
    }

    function handleSuccess(response){
        if(!response.data.success){
            console.warn("Error " + response.data.errorCode);
            return {};
        }
        return response.data.data;
    }
    function handleError(response){
        console.log(response);
    }

    return {
        all:all,
        cities:cities,
        startWith:startWith,
        add:addCity,
        remove:removeCity,
        create:create,
        delete:remove
    };
}]);

WeatherApp.service('OWMService',['$http',function($http){
        function city(name,country){
            if(name    === undefined){name = "Montpellier";}
            if(country === undefined){country = "fr";}
            
            var query = [name,country].join(',');
            var urlParams = {
                q : query,
                appid : OWM_KEY
            };
            return $http.get(OWM_URL,{params:urlParams}).then(handleSuccess,handleError);
        }

        function id(id){
            if(id === undefined){id = 29656;}
            var urlParams = {
                id : id,
                appid : OWM_KEY
            };
            return $http.get(OWM_URL,{params:urlParams}).then(handleSuccess,handleError);
        }

        function handleSuccess(response){
            console.log(response);
            if(response.data !== null){
                return response.data;
            }
            return response;
        }
        function handleError(response){
            console.log(response);
            return response;
        }
        
        return {
            byCity : city,
            byId : id
        };
    }
]);

