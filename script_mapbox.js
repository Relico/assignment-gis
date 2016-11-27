L.mapbox.accessToken = 'pk.eyJ1IjoicmVsaWNvIiwiYSI6ImNpdXZvaHN1ajAwMHUyeHA2aHk2NmczNmMifQ.9kI4z6Ub6WHhkEKaV-ks0A';
var map = L.mapbox.map('map', 'relico.1pjhof74').setView([48.622 ,19.718], 8);
var new_geojson = L.mapbox.featureLayer().addTo(map);

map.on('mouseover',function(e) {
	document.getElementById
})
$('#loader').hide();
$('#b_show_green_area').click(function() {
	$('#loader').show();
	var region = $('#regions option:selected').text();
	//$.get('show_green_area.php');
	$.ajax({
		type: 'POST',
		url: 'show_green_area.php',
		data: {region: region},
		timeout: 1000000,
		success: function (result) {
			L.mapbox.featureLayer(result).addTo(map);
			$('#loader').hide();
		},
		error: function (result) {
			alert('AJAX - Nastala chyba');
			$('#loader').hide();
		}
	})
});

$('#b_city').click(function() {
	var city = $('#input_city').val();
	var radius = $('#input_radius').val();
	if (city != "" && radius != "") {
		$('#loader').show();
	//$.get('show_nearest_fire_station_plus_radius.php');
		$.ajax({
			type: 'POST',
			url: 'show_nearest_plus_radius.php',
			data: {city: city, radius: radius},
			timeout: 1000000,
			success: function (result) {
				L.mapbox.featureLayer(result).addTo(map);
				$('#loader').hide();			
			},
			error: function (result) {
				alert('AJAX - Nastala chyba');
				$('#loader').hide();
			}
		})
	}
	else {
		alert('Chybné údaje');
		$('#loader').hide();
	}
});

$('#b_city_fire_station').click(function() {
	var city = $('#input_city').val();
	if (city != "") {
		$('#loader').show();
		$.ajax({
			type: 'POST',
			url: 'show_city_fire_station.php',
			data: {city: city},
			timeout: 1000000,
			success: function (result) {
				L.mapbox.featureLayer(result).addTo(map);
				$('#loader').hide();
			},
			error: function (result) {
				alert('AJAX - Nastala chyba');
				$('#loader').hide();
			}
		})
	}
	else {
		alert('Chybné údaje');
		$('#loader').hide();
	}
});

$('#b_alert').click(function() {
	$('#loader').show();
	var region = $('#regions option:selected').text();
	$.ajax({
		type: 'POST',
		url: 'show_alert.php',
		data: {region: region},
		timeout: 1000000,
		success: function (result) {
			L.mapbox.featureLayer(result).addTo(map);
			$('#loader').hide();
		},
		error: function (result) {
			alert('AJAX - Nastala chyba');
			$('#loader').hide();
		}
	})
});

$('#b_show_biggest_forest').click(function() {
	$('#loader').show();
	var region = $('#regions option:selected').text();
	$.ajax({
		type: 'POST',
		url: 'show_biggest.php',
		data: {region: region},
		timeout: 1000000,
		success: function (result) {
			L.mapbox.featureLayer(result).addTo(map);
			$('#loader').hide();
		},
		error: function (result) {
			alert('AJAX - Nastala chyba');
			$('#loader').hide();
		}
	})
});

$('#b_reload').click(function() {
	location.reload();
});

