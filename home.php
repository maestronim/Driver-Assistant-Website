<?php
   session_start();

   // Check user login or not
   if(!isset($_SESSION['username'])){
   	header('Location: index.php');
   } else {
   	$username = $_SESSION['username'];
   }
   ?>
<!DOCTYPE html>
<html>
   <head>
      <title>Home</title>
      <meta name="viewport" content="initial-scale=1.0">
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <!-- Latest compiled and minified CSS -->
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
      <link rel="stylesheet" href="./templates/style-edit.css">
      <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
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
   <body style="font-family: 'Montserrat', sans-serif;">
      <nav class="navbar navbar-default navbar-blue" style="margin: 0;">
         <div class="container-fluid">
            <div class="navbar-header">
               <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
               <span class="icon-bar"></span>
               <span class="icon-bar"></span>
               <span class="icon-bar"></span>
               </button>
               <a class="navbar-brand " href="#" style="color: white !important; padding: 0; margin-right: 20px;">
                  <img src="./templates/logo-mae.svg" height="40" style="display: inline; vertical-align: baseline;">
                  <h1 class="navbar-title" style="color: white !important; display: inline; vertical-align: baseline;">DRIVER</h1>
                  <h3 class="navbar-title" style="color: white !important; display: inline; vertical-align: baseline;">-Assistant</h3>
               </a>
            </div>
            <!--ul class="nav navbar-nav">
               <li class="navbar-link-active"><a href="#" style="text-decoration: none !important; color: white;">Home</a></li>
               <li><a href="#">Page 1</a></li>
               <li><a href="#">Page 2</a></li>
               <li><a href="#">Page 3</a></li>
               </ul-->
            <div class="collapse navbar-collapse" id="myNavbar">
               <ul class="nav navbar-nav navbar-right">
                  <li><a href="./authentication/logout.php" style="text-decoration: none !important; color: white;"><span class="glyphicon glyphicon-log-out"></span> Log out</a></li>
               </ul>
            </div>
         </div>
      </nav>
      <div class="container-fluid">
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
               var path;
               var currentDate;
               var path_id;

               var output = d.getFullYear() + '-' +
               (month<10 ? '0' : '') + month + '-' +
               (day<10 ? '0' : '') + day;
               $(function () {
               	var loadCarParameters = function(path_id, offset) {
               		$.ajax({
               			beforeSend: function(request){
               				request.setRequestHeader('Authorization', 'Bearer <?php echo $_COOKIE['jwt']; ?>');
               			},
               			type: 'GET',
               			url: './api/car-parameters/read.php?user_id=<?php echo $username; ?>&path_id=' + path_id + '&offset=' + offset,
               			success: function(data) {
               				var carParameters = jQuery.parseJSON(JSON.stringify(data));
               				if(carParameters.success === "yes") {
               					setGraphs(carParameters);
               				} else {
               					alert(carParameters.message);
               				}
               			},
               			contentType: "application/json",
               			dataType: 'json'
               		});
               	};
               	var loadPath = function(offset) {
               		$.ajax({
               			beforeSend: function(request){
               				request.setRequestHeader('Authorization', 'Bearer <?php echo $_COOKIE['jwt']; ?>');
               			},
               			type: 'GET',
               			url: './api/user-path/read.php?user_id=<?php echo $username; ?>&path_date=' + currentDate + '&offset=' + offset,
               			success: function(data) {
               				var userPath = jQuery.parseJSON(JSON.stringify(data));
               				if(userPath.success === "yes") {
               					getCarParametersCount(userPath.path.id);
               					loadCarParameters(userPath.path.id, 0);

               					path_id = userPath.path.id;

               					setInfo(userPath, 0);
               					setPolyline(userPath, 0);
               				} else {
               					alert(userPath.message);
               				}
               			},
               			contentType: "application/json",
               			dataType: "json"
               		});
               	};
               	var getPathsCount = function() {
               		$.ajax({
               			beforeSend: function(request){
               				request.setRequestHeader('Authorization', 'Bearer <?php echo $_COOKIE['jwt']; ?>');
               			},
               			type: 'GET',
               			url: './api/user-path/count.php?user_id=<?php echo $username; ?>&path_date=' + currentDate,
               			success: function(data) {
               				var pathsCount = jQuery.parseJSON(JSON.stringify(data));
               				if(pathsCount.success === "yes") {
               					var count = pathsCount.count;
               					$(".dropdown-menu").empty();
               					for (var i = 0; i < count; i++) {
               						$(".dropdown-menu").append( '<li id=' + i +'><a href=#>Percorso ' + (i+1) + '<a></li>' );
               					}
               				} else {
               					alert(pathsCount.message);
               				}
               			},
               			contentType: "application/json",
               			dataType: "json"
               		});
               	};
               	var getCarParametersCount = function(path_id) {
               		$.ajax({
               			beforeSend: function(request){
               				request.setRequestHeader('Authorization', 'Bearer <?php echo $_COOKIE['jwt']; ?>');
               			},
               			type: 'GET',
               			url: './api/car-parameters/count.php?user_id=<?php echo $username; ?>&path_id=' + path_id,
               			success: function(data) {
               				var carParametersCount = jQuery.parseJSON(JSON.stringify(data));
               				if(carParametersCount.success === "yes") {
               					var count = carParametersCount.count;
               					$(".pagination").empty();
               					for (var i = 0; i < Math.round(count/2); i++) {
               						$(".pagination").append( '<li><a href=#>' + (i+1) + '</a></li>' );
               					}
               				} else {
               					alert(carParametersCount.message);
               				}
               			},
               			contentType: "application/json",
               			dataType: "json"
               		});
               	};
               	$('#datetimepicker5').datetimepicker({
               		format: 'YYYY-MM-DD',
               		defaultDate: output
               	});

               	currentDate = $("#datetimepicker5").find("input").val();
               	getPathsCount();
               	loadPath(0);

               	$("#datetimepicker5").on("dp.change", function (e) {
               		currentDate = $("#datetimepicker5").find("input").val();
               		getPathsCount();
               		loadPath(0);
               	});
               	$(".dropdown-menu").on('click', 'li', function () {
               		var offset = this.id;
               		loadPath(offset);
               	});
               	$(".pagination").on('click', 'li', function () {
               		var offset = $(this).text();
               		loadCarParameters(path_id, (offset*2)-2);
               	});
               	var setInfo = function(userPath, id) {
               		$("#hard_braking").html(userPath.path.hard_braking);
               		$("#speed_limit_exceeded").html(userPath.path.speed_limit_exceeded);
               		$("#dangerous_time").html(userPath.path.dangerous_time);
               		$("#duration").html(userPath.path.duration);
               	};
               	var setPolyline = function(userPath, id) {
               		var coordinates = [];
               		var distanceInMeters = 0;
               		for (var i = 0; i < userPath.path.coordinates.length; i++) {
               			var lat = userPath.path.coordinates[i][0];
               			var lng = userPath.path.coordinates[i][1];
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
               	var setGraphs = function(carParameters) {
               		for (var i = 0; i < carParameters.parameters.length; i++) {
               			$("#graph" + (i+1)).html("<canvas id='" + carParameters.parameters[i].name + "'></canvas>");
               			setGraph(carParameters.parameters[i].name, carParameters.parameters[i].values);
               		}
               	};
               	var setDistance = function(distance) {
               		$("#distance").html(distance);
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
                        echo $username;
                        ?>
                  </div>
               </div>
               <div class="row">
                  <div class="col-sm-4">
                     <strong>
                        <h1 class="text-center" id="hard_braking">0</h1>
                     </strong>
                  </div>
                  <div class="col-sm-4">
                     <strong>
                        <h1 class="text-center" id="speed_limit_exceeded">0</h1>
                     </strong>
                  </div>
                  <div class="col-sm-4">
                     <strong>
                        <h1 class="text-center" id="dangerous_time">0</h1>
                     </strong>
                  </div>
               </div>
               <div class="row">
                  <div class="col-sm-4">
                     <p class="text-center">Hard Braking</p>
                  </div>
                  <div class="col-sm-4">
                     <p class="text-center">Speed limit exceeded</p>
                  </div>
                  <div class="col-sm-4">
                     <p class="text-center">Dangerous time</p>
                  </div>
               </div>
               <hr>
               <div class="row">
                  <div class="col-sm-6">
                     <strong>
                        <h3 class="text-center" id="duration">00:00:00</h3>
                     </strong>
                  </div>
                  <div class="col-sm-6">
                     <strong>
                        <h3 class="text-center" id="distance">0Km</h3>
                     </strong>
                  </div>
               </div>
               <div class="row">
                  <div class="col-sm-6">
                     <p class="text-center">Duration</p>
                  </div>
                  <div class="col-sm-6">
                     <p class="text-center">Distance</p>
                  </div>
               </div>
            </div>
         </div>
         <div class="row">
            <div class="col-sm-6" id="graph1">
            </div>
            <div class="col-sm-6" id="graph2">
            </div>
         </div>
         <div class="row">
            <div class="col-sm-4">
               <ul class="pagination">
               </ul>
            </div>
         </div>
      </div>
      <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBDvwj0qhbcfyAS_jjPL9sORaAJlYUjduI&callback=initMap&sensor=false&v=3&libraries=geometry"
         async defer></script>
   </body>
</html>
