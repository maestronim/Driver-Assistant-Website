<?php
	session_start();
    
	if(!isset($_SESSION['username'])) {
    	header('Location: ./authentication');
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Simple Map</title>
        <meta name="viewport" content="initial-scale=1.0">
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

        <!-- jQuery library -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

        <!-- Popper JS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>

        <!-- Latest compiled JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>
        
        <!-- momentjs library -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.1/moment.min.js"></script>
        
        <!-- datetimepicker library -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
        
      	<!-- Chart.js library --> 
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.min.js"></script>
        
        <style>
        #map {
            height: 100%;
        }
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }
        </style>
    </head>
    <body>
    <div class="container">
        <div class="row">
            <div class='col-sm-5'>
            	Seleziona una data
                <div class="form-group">
                    <div class='input-group date' id='datetimepicker5'>
                        <input type='text' class="form-control" />
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>
            </div>
            <script type="text/javascript">
            	var d = new Date();

                var month = d.getMonth()+1;
                var day = d.getDate();
                var map;
                var myObj;
                var path;
                var carParamObj;

                var output = d.getFullYear() + '-' +
                    (month<10 ? '0' : '') + month + '-' +
                    (day<10 ? '0' : '') + day;
                
                $(function () {
                	var loadPath = function() {
                    	var currentDate = $("#datetimepicker5").find("input").val();
                        $.ajax({
                            type: 'POST',
                            url: './api/user-path/read.php',
                            data: '{"user_id":"<?php echo $_SESSION['username']; ?>","path_date":"' + currentDate + '"}',
                            success: function(data) {
                            	myObj = jQuery.parseJSON(JSON.stringify(data));
                               	if(myObj.success === "yes") {
                                    $(".dropdown-menu").empty();
                                    
                                    for (var i = 0; i < myObj.paths.length; i++) {
                                    	$(".dropdown-menu").append( '<li id=' + i +'><a href=#>Percorso ' + (i+1) + '<a></li>' );
                                    }
                                    
                                    setInfo(0);
                                    setPolyline(0);
                                } else {
                                	alert(myObj.message);
                                }
                            },
                            contentType: "application/json",
                            dataType: 'json'
                        });
                    };
                    var loadCarParameters = function() {
                    	var currentDate = $("#datetimepicker5").find("input").val();
                    	$.ajax({
                            type: 'POST',
                            url: './api/car-parameters/read.php',
                            data: '{"user_id":"<?php echo $_SESSION['username']; ?>","path_date":"' + currentDate + '"}',
                            success: function(data) {
                            	carParamObj = jQuery.parseJSON(JSON.stringify(data));
                                if(carParamObj.success === "yes") {
                                	setGraphs(0);
                                } else {
                                	alert(carParamObj.message);
                                }
                            },
                            contentType: "application/json",
                            dataType: 'json'
                        });
                    };
                    $('#datetimepicker5').datetimepicker({
                    	format: 'YYYY-MM-DD',
                        defaultDate: output
                    });
                    loadPath();
                    loadCarParameters();
                    $("#datetimepicker5").on("dp.change", function (e) {
						loadPath();
                        loadCarParameters();
        			});
                    $("ul").on('click', 'li', function () {
                    	var id = this.id;
                    	if(myObj != null) {
                            setInfo(id);
                          	setPolyline(id);
                        }
                        
                        if(carParamObj != null) {
                        	setGraphs();
                        }
                    });
                    var setInfo = function(id) {
                    	$("#hard_braking").html("<h1>" + myObj.paths[id].hard_braking + "</h1>");
                        $("#speed_limit_exceeded").html("<h1>" + myObj.paths[id].speed_limit_exceeded + "</h1>");
                        $("#dangerous_time").html("<h1>" + myObj.paths[id].dangerous_time + "</h1>");
                        $("#duration").html("<h1>" + myObj.paths[id].duration + "</h1>");
                    };
                    var setPolyline = function(id) {
                    	var coordinates = [];
                        var distanceInMeters = 0;
                    	for (var i = 0; i < myObj.paths[id].coordinates.length; i++) {
                        	var lat = myObj.paths[id].coordinates[i][0];
                            var lng = myObj.paths[id].coordinates[i][1];
                          	var coordinate = new google.maps.LatLng(lat, lng);
                          	coordinates.push(coordinate);
                            if (i > 0) distanceInMeters += google.maps.geometry.spherical.computeDistanceBetween(coordinates[i], coordinates[i-1]);
                        }
                        var distanceInKilometers = distanceInMeters / 1000;
                        setDistance(distanceInKilometers.toFixed(2));
                        
						if(path != null) {
                        	path.setMap(null);
                        }
                        
                        path = new google.maps.Polyline({
                            path: coordinates,
                            geodesic: true,
                            strokeColor: '#FF0000',
                            strokeOpacity: 1.0,
                            strokeWeight: 2
                        });
						
                        path.setMap(map);
                        zoomToObject();
                    };
                    var setGraph = function(graphId, parameters) {
                    	var labels = [];
                        var value = 0;
                        for (var i = 0; i < parameters.length; i++) {
                        	labels.push(value + "sec");
                            value += 10;
                        }
                        
                    	var ctxL = document.getElementById(graphId).getContext('2d');
                        var myLineChart = new Chart(ctxL, {
                            type: 'line',
                            data: {
                                labels: labels,
                                datasets: [
                                    {
                                        label: graphId,
                                        fillColor: "rgba(220,220,220,0.2)",
                                        strokeColor: "rgba(220,220,220,1)",
                                        pointColor: "rgba(220,220,220,1)",
                                        pointStrokeColor: "#fff",
                                        pointHighlightFill: "#fff",
                                        pointHighlightStroke: "rgba(220,220,220,1)",
                                        data: parameters
                                    }
                                ]
                            },
                            options: {
                                responsive: true
                            }    
                        });
                    };
                    var setGraphs = function(id) {
                    	var parametersNames = ["oilTemperature", "RPM", "throttlePosition", "airFuelRatio"];
                        
                        for (var i = 0; i < parametersNames.length; i++) {
                        	var parameters = [];
                        	for (var j = 0; j < carParamObj.pathsParameters[id].length; j++) {
                            	parameters.push(carParamObj.pathsParameters[id][j][i]);
                            }
                            setGraph(parametersNames[i], parameters);
                        }
                    };
                    var setDistance = function(distance) {
                    	$("#distance").html("<h1>" + distance + " Km</h1>");
                    };
                    var zoomToObject = function() {
                    	var bounds = new google.maps.LatLngBounds();
                        var points = path.getPath().getArray();
                        for (var n = 0; n < points.length ; n++){
                            bounds.extend(points[n]);
                        }
                        map.fitBounds(bounds);
                    };
                });
            </script>
            <div class='col-sm-3'>
            	Seleziona un percorso
                <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">--
                    <span class="caret"></span></button>
                    <ul class="dropdown-menu">
                    </ul>
                  </div>
        	</div>
        </div>
        <div class="row">
            <div class='col-sm-8'>
                <div id="map-container" class="z-depth-1" style="height: 500px"></div>
                <script>
                    function initMap() {
                        map = new google.maps.Map(document.getElementById('map-container'), {
                        center: {lat: -34.397, lng: 150.644},
                        zoom: 8
                        });
                    }
                </script>
            </div>
			<div class='col-sm-4'>
            	<div class="row">
                	<div class="col-sm-12">
                    By 
                    <?php
                        echo $_SESSION['username'];
                    ?>
                    </div>
                </div>
                <div class="row">
                	<div class="col-sm-4" id="hard_braking">
                    </div>
                    <div class="col-sm-4" id="speed_limit_exceeded">
                    </div>
                    <div class="col-sm-4" id="dangerous_time">
                    </div>
                </div>
                <div class="row">
                	<div class="col-sm-4">
                    	Hard Braking
                    </div>
                    <div class="col-sm-4">
                    	Speed limit exceeded
                    </div>
                    <div class="col-sm-4">
                    	Dangerous time
                    </div>
                </div>
                <div class="row">
                	<div class="col-sm-6" id="duration">
                    </div>
                    <div class="col-sm-6" id="distance">
                    </div>
                </div>
                <div class="row">
                	<div class="col-sm-6">
                    	Duration
                    </div>
                    <div class="col-sm-6">
                    	Distance
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
        	<div class="col-sm-6">
            	<canvas id="oilTemperature"></canvas>
			</div>
           	<div class="col-sm-6">
            	<canvas id="RPM"></canvas>
            </div>
        </div>
        <div class="row">
        	<div class="col-sm-6">
            	<canvas id="throttlePosition"></canvas>
			</div>
           	<div class="col-sm-6">
            	<canvas id="airFuelRatio"></canvas>
            </div>
        </div>
    </div>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBDvwj0qhbcfyAS_jjPL9sORaAJlYUjduI&callback=initMap&sensor=false&v=3&libraries=geometry"
    async defer></script>
    </body>
</html>