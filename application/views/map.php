<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="<?=base_url()?>favicon.ico">

    <title>Starter Template for Bootstrap</title>

    <!-- Bootstrap core CSS -->
    <link href="<?=base_url()?>assets/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?=base_url()?>assets/dist/css/gis.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

        <style>
            body {
                padding: 0;
                margin: 0;
            }
            html, body, #map {
                height: 100%;
                width: 100%;
            }
        </style>
  </head>

  <body>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="<?=base_url()?>assets/dist/js/bootstrap.min.js"></script>
    
    <script src='https://api.tiles.mapbox.com/mapbox.js/v2.1.6/mapbox.js'></script>
    <link href='https://api.tiles.mapbox.com/mapbox.js/v2.1.6/mapbox.css' rel='stylesheet' />
    
<div id="map"></div>

        <script>
        L.mapbox.accessToken = 'pk.eyJ1IjoiYnB1cmNlbGwiLCJhIjoiZkJFN1NHayJ9.C6n4upSh8qH29uXzY-HAVw';
        var geojson = <?=$this->data?>;

        var map = L.mapbox.map('map', 'examples.map-i86nkdio')
          .setView([37.8, -96], 4);
          
      var featureLayer = L.mapbox.featureLayer()
          .setGeoJSON(geojson)
          .addTo(map);

         map.fitBounds(featureLayer.getBounds());
         
         
         featureLayer.eachLayer(function(layer) {

             // here you call `bindPopup` with a string of HTML you create - the feature
             // properties declared above are available under `layer.feature.properties`
             var content = '<dl>';
                 $.each( layer.feature.properties, function( key, value ) {
                     content += '<dt>'+key+'</dt><dd>'+value+'</dd>';
                 });
            content += '</dl>';
                 
             layer.bindPopup(content);
         });
        </script>
        


  </body>
</html>
