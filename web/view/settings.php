<h1>Settings</h1>
<div ng-show="message != ''" class="alert alert-dismissible {{messageClass}}" role="alert">
    {{message}}
</div>
<!-- City Searchbar -->
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <form>
            <input type="text" ng-model="keyword" ng-change="listCity(keyword)" class="form-control" placeholder="Enter a city name"/>
        </form>
        
        <div>
            <ul class="list-group">
                <li class="list-group-item" ng-repeat="city in cities">
                    <img ng-src="/img/flags/24x24/{{city.country}}.png" /> {{city.name}} 
                    <button class="btn btn-xs btn-success pull-right" ng-click="add(city.id)">add</button>
		</li> 
            </ul>
        </div>    
    </div>
</div>
<!-- Dashboard list -->
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
        <div class="panel panel-default">
            <div class="panel-heading">Dashboards</div>
            <div class="panel-body">
                <form>
                    <div class="input-group">
                        <input type="text" ng-model="dashboardName" class="form-control" placeholder="New dashboard...">
                        <span class="input-group-btn">
                            <input type="submit" class="btn btn-primary" ng-click="create(dashboardName)" value="Create"/>
                        </span>
                    </div>
                </form>
            </div>
            <ul class="list-group">
                <li ng-repeat="dashboard in dashboards" ng-click="display(dashboard.id)"
                    ng-class="{'selected':dashboard.id == selectedDashboardId}" 
                    class="list-group-item">
                    {{dashboard.name}}
                    <button class="btn btn-xs btn-danger pull-right" ng-click="delete(dashboard.id)">remove</button>
                </li>
            </ul>
        </div>
    </div> 
    <!-- Cities list -->
    <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">        
        <div>
            <div  class="panel panel-default">
                <div class="panel-heading">Cities</div>
                
                <ul class="list-group">
                    
                    <li  ng-show="dashboardCities.length == 0" class="list-group-item">
                        No city in this dashboard. Type in the above searchbar...
                    </li>
                    <li ng-repeat="city in dashboardCities" class="list-group-item">
                        <img ng-src="/img/flags/24x24/{{city.country}}.png" /> {{city.name}} 
                        <button class="btn btn-xs btn-danger pull-right" ng-click="remove(city.id)">remove</button>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>