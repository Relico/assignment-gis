<?php
  $region = $_POST['region'];
  $con = pg_connect("host=localhost dbname=pdt user=postgres password=postgres") or die ('Connection failed: ' . pg_last_error());

  $query = "SELECT ST_AsGeoJSON(ST_Transform(a.way,4326)) as way FROM planet_osm_polygon a, planet_osm_polygon b WHERE a.landuse = 'forest' AND b.name = '".$region."' AND ST_Intersects(a.way, b.way)";

  $query2 = "SELECT ST_AsGeoJSON(ST_Transform(a.way,4326)) as way, ST_AsGeoJSON(ST_Transform(b.way,4326)) as rway, a.name as fsname FROM planet_osm_point a, planet_osm_polygon b WHERE b.name = '".$region."' AND a.amenity = 'fire_station' AND ST_Contains(b.way,a.way)";

  $result = pg_query($query) or die ('Query failed: ' . pg_last_error());

  $result2 = pg_query($query2) or die ('Query failed: ' . pg_last_error());

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

  while($r = pg_fetch_assoc($result2)) {
    #echo $r['way'];
    #$prop = $r;
    #unset($prop['way']);
    $Rway = json_decode($r['rway'], true);
    if ($r['fsname'] == NULL) {
    	$r['fsname'] = 'požiarna stanica';
    }
    ini_set('memory_limit', '-1');
    $feature = array(
 		'type' => 'Feature',
 		'geometry' => json_decode($r['way'], true),
    	'properties' => array(
			'marker-color' => "#b22222",
			'description' => $r['fsname']
		)
    );
    array_push($json_res, $feature);
  }

  $feature = array(
      'type' => 'Feature',
      'geometry' => $Rway,
      'properties' => array(
          'stroke' => '#0000cc',
          'strok-width' => 1,
          'fill-opacity' => 0.1
      )
  );
  array_push($json_res, $feature);

  $con = NULL;
  header('Content-Type:application/json');
  echo json_encode($json_res, JSON_NUMERIC_CHECK);
?>