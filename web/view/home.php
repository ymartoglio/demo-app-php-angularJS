<h1>Your dashboards</h1>
<div class="row">
    <!-- Dashboard list -->
    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
        <div class="panel panel-default">
            <div class="panel-heading">Dashboards</div>

            <ul class="list-group">
				<li ng-show="dashboards.length == 0" class="list-group-item"><a href="#/settings">Create a dashboard !</a></li>
                <li ng-repeat="dashboard in dashboards" ng-click="display(dashboard.id)"
                    ng-class="{'selected':dashboard.id == selectedDashboardId}" 
                    class="list-group-item">{{dashboard.name}}</li>
            </ul>
        </div>
    </div>    
    <!-- City list -->
    <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
        <div ng-show="dashboards.length == 0">
            No dashboard... add some on the <a href="#/settings">Settings page</a>.
        </div>
        <div ng-show="owmCitiesData.length == 0 && dashboards.length > 0">
            You have no city in this dashboard... add some on the <a href="#/settings/{{selectedDashboardId}}">Settings page</a>.
        </div>
        
        <div ng-repeat="city in owmCitiesData" class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <div  class="panel panel-default">
                <div class="panel-heading">
                    <i class="wi wi-owm-{{city.weather[0].id}}"></i> {{city.name}} / <span>{{city.main.temp | K2C | number : 1 }}°C</span> 
                    <span class="pull-right"><img ng-src="/img/flags/24x24/{{city.sys.country}}.png" /></span>
                </div>
                <ul class="list-group">
                    <li class="list-group-item">
                        {{city.weather[0].main}}
                        <span class="pull-right">{{city.main.humidity}} <i class="wi wi-humidity"></i></span>
                    </li>
                    <li class="list-group-item" ng-show="mapsActive">
                        <button class="btn btn-xs btn-default" ng-click="loadMap(city.id)">load map</button>
                        <div id="map-{{city.id}}" class="row hidden">
                            <iframe class="col-xs-12 col-sm-12 col-md-12 col-lg-12" height="250" frameborder="0" style="border:0" src="" allowfullscreen></iframe> 
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
