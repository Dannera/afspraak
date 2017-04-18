<!DOCTYPE html>
<html>
  <head>
    <!-- This stylesheet contains specific styles for displaying the map
         on this page. Replace it with your own styles as described in the
         documentation:
         https://developers.google.com/maps/documentation/javascript/tutorial -->
    <link rel="stylesheet" href="/maps/documentation/javascript/demos/demos.css">
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link href="bootstrap/css/bootstrap-theme.min.css" rel="stylesheet" media="screen">
    <script type="text/javascript" src="jquery-1.11.3-jquery.min.js"></script>
    <link rel="stylesheet" href="../css/style.css" type="text/css"  />
    <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=AIzaSyDeopjvHjhKyGZAA06SuRNIyu90N0r7GPo&sensor=false">
    </script>
  </head>
  <body>
<?php require_once("../message.php"); ?>
<?php include("../navbar.php"); ?>

  <div id="map_canvas"></div> 
    <!--<div class="canvas_btm"></div>-->
   
<form role="form"><div class="col-lg-6">
<div class="form-group">
     <label for="InputName"><?php echo Message::LOCATION . ":"; ?></label>
     <input type="text" class="form-control" name="start"  id="start" placeholder="Enter your adres/city"  value=""  onchange="calcRoute()" />
      </div></div>
  <!--    <form role="form"></form> -->

<div class="col-lg-6">
<div class="form-group">
<label for="InputName"><?php echo Message::TRAVELMETHOD . ":"; ?></label>   
<select class="form-control" id="mode" onchange="calcRoute()">
<?php echo  Message::travelOptions(); ?>
</select>
</div></div>

 <div>
        <strong><?php echo Message::CALC_TRAVELMETHOD_MSG . ":"; ?>:</strong>
      </div>


      <div id="output">
      </div>
    </div>
</div>




<script type="text/javascript">
/* varibles ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */

//LatLngBounds
        var bounds = new google.maps.LatLngBounds();
        var markersArray = [];

//direction properties
        var directionsDisplay;
        var directionsService = new google.maps.DirectionsService();
        var map;
        var renkum = {lat: 51.982983, lng: 5.739978};
        var request = {
            origin:start,
            destination: new google.maps.LatLng(51.982983, 5.739978),
            travelMode: google.maps.TravelMode[selectedMode]
        };

//varssss
         var geocoder = new google.maps.Geocoder();
         var service = new google.maps.DistanceMatrixService();

//start and travel mode
         var start = document.getElementById("start").value;
         var selectedMode = document.getElementById("mode").value;

//Icons and styles        
         var destinationIcon = 'https://chart.googleapis.com/chart?' +
          'chst=d_map_pin_letter&chld=B|00ff00|000000';
         var originIcon = 'https://chart.googleapis.com/chart?' +
            'chst=d_map_pin_letter&chld=A|ffa500|000000';
         var map = new google.maps.Map(document.getElementById("map_canvas"),mapOptions);
             {
             center: renkum,
             zoom: 8
             };
        
        directionsDisplay.setMap(map);
        var pos = new google.maps.LatLng(position.coords.latitude,position.coords.longitude);
        var infowindow = new google.maps.InfoWindow({
        map: map,
        position: pos,
        content: "Location found"
      });

 function initialize() {
          directionsDisplay = new google.maps.DirectionsRenderer();
          var mapOptions = {
          mapTypeId: google.maps.MapTypeId.ROADMAP;
          };
      }

//HTML5 geolocation
if(navigator.geolocation) {
     navigator.geolocation.getCurrentPosition(function(position) { 
      document.getElementById("start").value = pos,
       calcRoute();
       map.setCenter(pos);
       }, function() {
       handleNoGeolocation(true);
      });
    } else {
    // Browser doesn't support Geolocation
    handleNoGeolocation(false);
  }


//calculate distance
  function calcRoute() {

      service.getDistanceMatrix({
      origins: [start],
      destinations: [renkum],
      travelMode: selectedMode,
      unitSystem: google.maps.UnitSystem.METRIC,
      avoidHighways: false,
      avoidTolls: false
  
  }, function(response, status) {
          if (status !== "OK") {
            alert("Error was: " + status);
   } else {
            directionsDisplay.setDirections(result);
            var originList = response.originAddresses;
            var destinationList = response.destinationAddresses;
            var outputDiv = document.getElementById("output");
                outputDiv.innerHTML = "";
            deleteMarkers(markersArray);
            var showGeocodedAddressOnMap = function(asDestination) {
            var icon = asDestination ? destinationIcon : originIcon;
              return function(results, status) {
                if (status === "OK") {
                  map.fitBounds(bounds.extend(results[0].geometry.location));
                  markersArray.push(new google.maps.Marker({
                  map: map,
                  position: results[0].geometry.location,
                  icon: icon
                  }));
                } else {
                  alert("Geocode was not successful due to: " + status);
                }
              };
            };

         for (var i = 0; i < originList.length; i++) {
              var results = response.rows[i].elements;
              geocoder.geocode({"address": originList[i]},
                  showGeocodedAddressOnMap(false));
              for (var j = 0; j < results.length; j++) {
                geocoder.geocode({"address": destinationList[j]},
                    showGeocodedAddressOnMap(true));
                outputDiv.innerHTML += "From: " + originList[i] + "<br> To: " + destinationList[j] + "<br>Distance: " + results[j].distance.text + "<br> Time: " +
                    results[j].duration.text + "<br>";
              }
         };
  }
   google.maps.event.addDomListener(window, "load", initialize);
  
   function deleteMarkers(markersArray) {
        for (var i = 0; i < markersArray.length; i++) {
          markersArray[i].setMap(null);
        }
        markersArray = [];
      }
    
    </script>

  </body>
</html>