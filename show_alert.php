<?php
  $region = $_POST['region'];
  $con = pg_connect("host=localhost dbname=pdt user=postgres password=postgres") or die ('Connection failed: ' . pg_last_error());

  $query = "SELECT ST_AsGeoJSON(ST_Transform(a.way,4326)) as way FROM planet_osm_polygon a, planet_osm_polygon b, planet_osm_polygon c WHERE a.landuse = 'forest' AND b.name = '".$region."' AND ST_Intersects(a.way, b.way) AND (c.natural = 'water' OR c.natural = 'lake' OR c.waterway = 'riverbank') AND (ST_Intersects(a.way,c.way) OR ST_Touches(a.way,c.way))";

  $result = pg_query($query) or die ('Query failed: ' . pg_last_error());

  $json_res = array();

  ###ORANGE### WATER, LAKE

  while($r = pg_fetch_assoc($result)) {
    #echo $r['way'];
    #$prop = $r;
    #unset($prop['way']);
    ini_set('memory_limit', '-1');
    $feature = array(
   		'type' => 'Feature',
   		'geometry' => json_decode($r['way'], true),
      	'properties' => array(
  			'fill' => '#ffd700',
  			'fill-opacity' => 1
  		)
    );
    array_push($json_res, $feature);
  }

  $query = "SELECT DISTINCT(ST_AsGeoJSON(ST_Transform(a.way,4326))) as way, ST_AsGeoJSON(ST_Transform(c.way,4326)) as FSway, c.name as FSname FROM planet_osm_polygon a, planet_osm_polygon b, planet_osm_point c WHERE a.landuse = 'forest' AND b.name = '".$region."' AND ST_Intersects(a.way, b.way) AND c.amenity = 'fire_station' AND ST_DWithin(a.way,c.way,5000)";

  $result = pg_query($query) or die ('Query failed: ' . pg_last_error());

  ###GREEN### FIRE STATION

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

    if ($r['fsname'] == NULL) {
      $r['fsname'] = 'požiarna stanica';
    }
    $feature = array(
      'type' => 'Feature',
      'geometry' => json_decode($r['fsway'], true),
        'properties' => array(
          'marker-color' => "#b22222",
          'description' => $r['fsname']
      )
    );
    array_push($json_res, $feature);
  }

  $query = "SELECT ST_AsGeoJSON(ST_Transform(a.way,4326)) as way FROM planet_osm_polygon a, planet_osm_polygon b WHERE a.landuse = 'forest' AND b.name = '".$region."' AND ST_Intersects(a.way, b.way)";

  $result = pg_query($query) or die ('Query failed: ' . pg_last_error());

  ###RED### NOTHING

  while($r = pg_fetch_assoc($result)) {
    #echo $r['way'];
    #$prop = $r;
    #unset($prop['way']);
    ini_set('memory_limit', '-1');
    $feature = array(
      'type' => 'Feature',
      'geometry' => json_decode($r['way'], true),
        'properties' => array(
          'fill' => '#b22222',
          'fill-opacity' => 0.4
      )
    );
    array_push($json_res, $feature);
  }

  $con = NULL;
  header('Content-Type:application/json');
  echo json_encode($json_res, JSON_NUMERIC_CHECK);
?>