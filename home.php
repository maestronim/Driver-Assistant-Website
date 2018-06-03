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
			var path;
			var currentDate;

			var output = d.getFullYear() + '-' +
			(month<10 ? '0' : '') + month + '-' +
			(day<10 ? '0' : '') + day;
			$(function () {
				var loadCarParameters = function(path_id) {
					$.ajax({
						beforeSend: function(request){
							request.setRequestHeader('Authorization', 'Bearer <?php echo $_COOKIE['jwt']; ?>');
						},
						type: 'GET',
						url: './api/car-parameters/read.php?user_id=<?php echo $username; ?>&path_id=' + path_id,
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
								loadCarParameters(userPath.path.id);

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
							var count = pathsCount.count;
							$(".dropdown-menu").empty();
							for (var i = 0; i < count; i++) {
								$(".dropdown-menu").append( '<li id=' + i +'><a href=#>Percorso ' + (i+1) + '<a></li>' );
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
				$("ul").on('click', 'li', function () {
					var id = this.id;
					loadPath(id);
				});
				var setInfo = function(userPath, id) {
					$("#hard_braking").html("<h1>" + userPath.path.hard_braking + "</h1>");
					$("#speed_limit_exceeded").html("<h1>" + userPath.path.speed_limit_exceeded + "</h1>");
					$("#dangerous_time").html("<h1>" + userPath.path.dangerous_time + "</h1>");
					$("#duration").html("<h1>" + userPath.path.duration + "</h1>");
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
					var parametersNames = ["oilTemperature", "RPM", "throttlePosition", "airFuelRatio"];

					for (var i = 0; i < parametersNames.length; i++) {
						var parameters = [];
						for (var j = 0; j < carParameters.parameters.length; j++) {
							parameters.push(carParameters.parameters[j][i]);
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
							echo $username;
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
