<!DOCTYPE html>
<html lang="sk">

  <meta charset="utf-8">
  <meta name="viewport" content="initial-scale=1,maximum-scale=1,user-scalable=no" />
  <script src="jquery-1.11.2.min.js" type="text/javascript"></script>   
  <script src="https://api.mapbox.com/mapbox.js/v2.4.0/mapbox.js"></script>
  <script src="script_db.js" type="text/javascript"></script>
  <link href="https://api.mapbox.com/mapbox.js/v2.4.0/mapbox.css" rel="stylesheet" />
  <link href="layout.css" type="text/css" rel="stylesheet">
<head>  
  <title>FireAlert</title>
</head>

<body>
  <div class="loader" id="loader"></div>
  <div class="menu">
    <div class="title">
      <h1>FireAlert</h1>
    </div>

    <div id="select_region">
      <div class="label">
  		  <label">Kraj</label>
      </div>
    	<select id="regions">
    		<option value="BA">Bratislavský kraj</option>
    		<option value="TT">Trnavský kraj</option>
    		<option value="TN">Trenčiansky kraj</option>
    		<option value="NR">Nitriansky kraj</option>
    	</select>
    </div>

    <div id="textfield_city">
      <div class="label">
        <label>Mesto</label>
      </div>
    <input id="input_city" type="text" name="city">
    </div>

    <div id="textfield_radius">
      <div class="label">
        <label>Radius - km</label>
      </div>
    <input id="input_radius" type="text" name="radius" onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
    </div>
  
    <div class="button" id="buttons">
      <div id="show_all_regions">
         <button class="button" id="b_show_green_area">Zobraz kraj</button>
         <button class="button" id="b_show_biggest_forest">Zobraz najväčší les v kraji</button>
         <button class="button" id="b_city">Zobraz okolie mesta</button>
         <button class="button" id="b_city_fire_station">Zobraz najbližšiu požiarnu stanicu</button>
         <button class="button" id="b_alert">Zobraz výstrahy v kraji</button>
         <button class="button" id="b_reload">Reset mapy</button>
      </div>
    </div>
  </div>

  <div id="map">
  </div>
  <script src="script_mapbox.js" type="text/javascript"></script>
</body>