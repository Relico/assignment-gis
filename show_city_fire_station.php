<?php
  $city = $_POST['city'];

  $con = pg_connect("host=localhost dbname=pdt user=postgres password=postgres") or die ('Connection failed: ' . pg_last_error());

  $query = "SELECT ST_Distance(a.way,b.way) as distance, ST_AsGeoJSON(ST_Transform(a.way,4326)) as Cway, ST_AsGeoJSON(ST_Transform(b.way,4326)) as FSway, a.name as Cname, b.name as FSname FROM planet_osm_point a, planet_osm_point b WHERE a.name = '".$city."' AND (a.place = 'town' OR a.place = 'village' OR a.place = 'city' OR a.place = 'suburb') AND b.amenity = 'fire_station' ORDER BY distance LIMIT 1";

  $result = pg_query($query) or die ('Query failed: ' . pg_last_error());

  $json_res = array();

  if ($r = pg_fetch_assoc($result)) {
    #echo $r['way'];
    #$prop = $r;
    #unset($prop['Fway']);
    if ($r['fsname'] == NULL) {
        $r['fsname'] = 'požiarna stanica';
    }
    if ($r['cname'] == NULL) {
        $r['cname'] = 'Mesto';
    }
    ini_set('memory_limit', '-1');
    $feature = array(
     		'type' => 'Feature',
     		'geometry' => json_decode($r['fsway'], true),
        'properties' => array(
      	   'marker-color' => "#b22222",
           'description' => $r['fsname']
    		)
    );
    array_push($json_res, $feature);

    $feature = array(
      'type' => 'Feature',
      'geometry' => json_decode($r['cway'], true),
      'properties' => array(
              'marker-color' => "#2980b9",
              'description' => $r['cname']
          )
    );
    array_push($json_res, $feature);
  }

  $con = NULL;
  header('Content-Type:application/json');
  echo json_encode($json_res, JSON_NUMERIC_CHECK);
?>