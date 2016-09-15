<!doctype html>
<html lang="en" ng-app="WeatherApp">
    <head>
        <meta charset="utf-8">
        <title>Weather App</title>
        <link href="css/vendor/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="css/style.css" rel="stylesheet" type="text/css"/>
        <script src="js/vendor/angular.min.js" type="text/javascript"></script>
        <script src="js/vendor/angular-route.min.js" type="text/javascript"></script>
        <script src="js/vendor/jquery.min.js" type="text/javascript"></script>
        <script src="js/tools.js" type="text/javascript"></script>
        <script src="js/app.js" type="text/javascript"></script>
	    <script src="js/service.js" type="text/javascript"></script>
	    <script src="js/controller.js" type="text/javascript"></script>
        <link href="css/vendor/weather-icon/css/weather-icons.min.css" rel="stylesheet" type="text/css"/>
    </head>

    <body>
        <div id="header" class="fluid-container">
            <header class="container">
                <a href="/#/">
                    <i class="wi wi-day-sunny"></i> Weather Dashboard
                </a>
            </header>
        </div>
        <div  class="fluid-container">
            <div class="container" id="section">
                <div ng-view=""></div>
            </div>
        </div>
        <div id="footer" class="fluid-container">
            <footer class="container">
                <a href="#/settings">settings</a>
            </footer>
        </div>
    </body>
</html>






