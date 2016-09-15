Demo Weather app
================
Allow user to construct dashboards containing cities weather information collected with openweathermap API.


Technologies
============
Backend : PHP, MySQL
Frontend : HTML, CSS/SaSS, Twitter Bootstrap 3, AngularJS

Purpose
=======
Allow a unique user to manage dashboards. Each displays a collection of cities current weather.

Limitation :
    * a single user application
    * do not allow user to update dashboard name
    * a city search better solution can be
         - once the first search request succeed, and the collection datas are in memory : filter with angular filter.


Installation
============

Sources
-------
Copy sources to publication directory
ex : /var/www/weather

Modify "web/js/app.js" parameters variable :

```javascript
var OWM_KEY = "2de143494c0b295cca9337e1e96b00e0";
var WS_URL = "http://weather.localhost/ws.php";
var GMAP_API_KEY = '';
```

OWM_KEY must be a valid OpenWeatherMap key

if GMAP_API_KEY (my own key) is empty the embed google maps load is disabled.
the above key restrict Google API Call only from "weather.localhost" domain.


Virtual host (apache2)
----------------------

Create a virtualhost in "/etc/apache2/sites-available/weather.conf" with :
```
<VirtualHost *:80>
    AddType images/png .png
    ServerName weather.localhost

    DocumentRoot /var/www/weather/web

    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
```

Enable the virtual host
```
$ a2ensite weather.conf
```


Create database structure
-------------------------
Create the database and the tables structure
```
$ cd /var/www/weather
$ mysql -u [user] -p[password] < data/weather-structure.sql
```



Set DB parameters
-----------------
Change DB connection informations in /src/config.ini

Import OpenWeatherMap cities datas
----------------------------------
```
$ cd /var/www/weather
$ php install.php
```


200.000+ cities to import (can be quite long).
The script display a counter.

Implementaion overview
======================
Webservices

Controller expose method through the RequestHandler class (made with Reflection).
Public controller method can be called by GET request with parameters :

Webservice call
---------------
Call /ws.php
GET params {
    controller : string, controller name (ex : name)
    action : string, controller method name (ex : method_name)
    paramName : paramValue
}

The ws service call will response by invoking : NameController->methodName($paramName)

The response is packaged by the following JSON payload :
```JSON
{
    "success"   : bool,
    "errorCode" : int,
    "data"      : bool | object
}
```

Controllers
-----------
2 controllers provide data to end-user's interfaces throught data model classes.
These classes are mapping classes for DB class fetch :


src/city.php
CityModel
{
    "id"        : int,
    "name"      : string,
    "longitude" : real,
    "latitude"  : real
    "country"   : 2 character
}

src/dashboard.php
DashboardModel
{
    "id": int,
    "name": string
}


Database
--------
3 tables :
 * dashboard : stores the dashboard name and id
 * city      : stores the city data imported from (data/city.list.json)
 * dashboard_city : stores the relations between dashboard and cities (n--n)

The repository layer access is manage by full static class in DBWeather.php


PHP class call sequence
-----------------------
-> means "invoke"
    [User request] -> [RequestHandler] -> [Controller Method] -> [DBWeather Mysql requests]


Front HTML
==========
Dependencies
------------
 * Angular JS 1.4.x + ngRoute
 * Twitter Bootstrap 3.3.x
 * jQuery 1.11.x

View and Layout
---------------
The main layout is embedded into index.php (containing the "ng-view" directive)
Routing and frontend templates rendering are managed by Angular JS

2 views are available :
 * view/home.php corresponding to "HomeController"
 * view/settings.php corresponding to "SettingsController"


Angular Services
----------------
2 customs services provides helper methods to access the web services.
 * DashboardService : The services provided by the application enable user to create and
   store dashboard composed of cities to display.
 * OWMService : The services provided by OpenWeatherMap enable to retrieve current
   weather data by city name or city id.


Angular Controllers
-------------------
2 controllers handling datas access and binding to views.
 * HomeController : get dashboard datas and related cities
 * SettingsController : get dashboard datas and allow
   create, delete, add cities and remove city to dashboard
