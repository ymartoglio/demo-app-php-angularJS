var WeatherApp = angular.module('WeatherApp', ['ngRoute'])
.config(['$routeProvider',function($routeProvider) {
    $routeProvider
    .when('/', {templateUrl: '/view/home.php',controller: 'HomeController'})
    .when('/settings', {templateUrl: '/view/settings.php',controller: 'SettingsController'})
    .when('/settings/:dashboardId', {templateUrl: '/view/settings.php',controller: 'SettingsController'})
    .otherwise({ redirectTo: '/' });
}]);


var OWM_URL = "http://api.openweathermap.org/data/2.5/weather";
var OWM_KEY = "2de143494c0b295cca9337e1e96b00e0";
var WS_URL = "http://weather.localhost/ws.php";
var GMAP_API_KEY = 'AIzaSyD6QKQYfUJ8EbBYI1ZrdKQf8ka_djBei3Y';


/**************************************************************************************
ANGULAR FILTERS
**************************************************************************************/
// Temperature conversion angular filters
WeatherApp.filter('F2C',function(){
    return function(input){
        return F2C(input);
    };
});

WeatherApp.filter('C2F',function(){
    return function(input){
        return C2F(input);
    };
});

WeatherApp.filter('K2C',function(){
    return function(input){
        return K2C(input);
    };
});

// Temperature conversion 
function K2C(kelvin){
    return kelvin -272.15;
}

function F2C(fahrenheit){
    return ((fahrenheit - 32) * (5/9));
}

function C2F(celsius){
    return ((celsius * 9/5) + 32);
}