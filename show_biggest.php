<?php
  $region = $_POST['region'];
  $con = pg_connect("host=localhost dbname=pdt user=postgres password=postgres") or die ('Connection failed: ' . pg_last_error());

  $query = "SELECT ST_AsGeoJSON(ST_Transform(a.way,4326)) as way, ST_Area(a.way) as rozloha FROM planet_osm_polygon a, planet_osm_polygon b WHERE a.landuse = 'forest' AND b.name = '".$region."' AND ST_Intersects(a.way, b.way) ORDER BY rozloha DESC LIMIT 1";

  $result = pg_query($query) or die ('Query failed: ' . pg_last_error());

  $json_res = array();

  while($r = pg_fetch_assoc($result)) {
    #echo $r['way'];
    #$prop = $r;
    #unset($prop['way']);
    ini_set('memory_limit', '-1');
    $feature = array(
 		'type' => 'Feature',
 		'geometry' => json_decode($r['way'], true),
    	'properties' => array(
			'fill' => '#008000',
			'fill-opacity' => 1
		)
    );
    array_push($json_res, $feature);
  }

  $con = NULL;
  header('Content-Type:application/json');
  echo json_encode($json_res, JSON_NUMERIC_CHECK);
?>