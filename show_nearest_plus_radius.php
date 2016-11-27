<?php
  $city = $_POST['city'];
  $radius = $_POST['radius'] * 1000;

  $con = pg_connect("host=localhost dbname=pdt user=postgres password=postgres") or die ('Connection failed: ' . pg_last_error());

  $query = "SELECT ST_AsGeoJSON(ST_Transform(a.way,4326)) as Cway, ST_AsGeoJSON(ST_Transform(b.way,4326)) as Fway FROM planet_osm_point a, planet_osm_polygon b WHERE a.name = '".$city."' AND (a.place = 'town' OR a.place = 'village' OR a.place = 'city' OR a.place = 'suburb') AND b.landuse = 'forest' AND ST_DWithin(a.way,b.way,".$radius.")";

  $result = pg_query($query) or die ('Query failed: ' . pg_last_error());

  $json_res = array();

  while($r = pg_fetch_assoc($result)) {
    #echo $r['way'];
    #$prop = $r;
    #unset($prop['Fway']);
    $Cway = json_decode($r['cway'], true);
    ini_set('memory_limit', '-1');
    $feature = array(
     		'type' => 'Feature',
     		'geometry' => json_decode($r['fway'], true),
        'properties' => array(
      	   'fill' => '#003300',
      		 'fill-opacity' => 0.5
    		)
    );
    array_push($json_res, $feature);
  }
  $feature = array(
      'type' => 'Feature',
      'geometry' => $Cway,
      'properties' => array(
          'marker-color' => "#2980b9",
          'description' => $city
      )
  );
  array_push($json_res, $feature);

  $con = NULL;
  header('Content-Type:application/json');
  echo json_encode($json_res, JSON_NUMERIC_CHECK);
?>