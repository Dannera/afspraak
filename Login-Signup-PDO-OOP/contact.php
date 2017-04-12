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
    <link rel="stylesheet" href="style.css" type="text/css"  />

  

    <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=AIzaSyDeopjvHjhKyGZAA06SuRNIyu90N0r7GPo&sensor=false">
    </script>

  
<script type="text/javascript">
    
    //direction properties
      var directionsDisplay;
      var directionsService = new google.maps.DirectionsService();
      var map;


    //destination properties




/*   == THE END == */
    
      function initialize() {
         directionsDisplay = new google.maps.DirectionsRenderer();
         var renkum = new google.maps.LatLng(52.151292, 5.658739);

         //START

 var bounds = new google.maps.LatLngBounds;
        var markersArray = [];

        var origin1 = {lat: 52.151292, lng: 5.658739};
        var origin2 = 'Arnhem, Holland';
        var destinationA = 'Utrecht, Holland';
        var destinationB = {lat: 51.985103, lng: 5.898730};

        var destinationIcon = 'https://chart.googleapis.com/chart?' +
            'chst=d_map_pin_letter&chld=D|999000|000000';
        var originIcon = 'https://chart.googleapis.com/chart?' +
            'chst=d_map_pin_letter&chld=O|00FF00|000000';
        var map = new google.maps.Map(document.getElementById('map_canvas'), {
          center: renkum,
          zoom: 8
           });
        var geocoder = new google.maps.Geocoder;

         var service = new google.maps.DistanceMatrixService;
        service.getDistanceMatrix({
          origins: [origin1, origin2],
          destinations: [destinationA, destinationB],
          travelMode: 'DRIVING',
          unitSystem: google.maps.UnitSystem.METRIC,
          avoidHighways: false,
          avoidTolls: false
        }, function(response, status) {
          if (status !== 'OK') {
            alert('Error was: ' + status);
          } else {
            var originList = response.originAddresses;
            var destinationList = response.destinationAddresses;
            var outputDiv = document.getElementById('output');
            outputDiv.innerHTML = '';
            deleteMarkers(markersArray);

            var showGeocodedAddressOnMap = function(asDestination) {
              var icon = asDestination ? destinationIcon : originIcon;
              return function(results, status) {
                if (status === 'OK') {
                  map.fitBounds(bounds.extend(results[0].geometry.location));
                  markersArray.push(new google.maps.Marker({
                    map: map,
                    position: results[0].geometry.location,
                    icon: icon
                  }));
                } else {
                  alert('Geocode was not successful due to: ' + status);
                }
              };
            };

            for (var i = 0; i < originList.length; i++) {
              var results = response.rows[i].elements;
              geocoder.geocode({'address': originList[i]},
                  showGeocodedAddressOnMap(false));
              for (var j = 0; j < results.length; j++) {
                geocoder.geocode({'address': destinationList[j]},
                    showGeocodedAddressOnMap(true));
                outputDiv.innerHTML += originList[i] + ' to ' + destinationList[j] +
                    ': ' + results[j].distance.text + ' in ' +
                    results[j].duration.text + '<br>';
              }
            }
          }
        });

         function deleteMarkers(markersArray) {
        for (var i = 0; i < markersArray.length; i++) {
          markersArray[i].setMap(null);
        }
        markersArray = [];
      }

         //END
         var mapOptions = {
         mapTypeId: google.maps.MapTypeId.ROADMAP,
        }
        map = new google.maps.Map(document.getElementById('map_canvas'),mapOptions);
        directionsDisplay.setMap(map);
      }

      
        
  // Try HTML5 geolocation

  if(navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function(position) {
      var pos = new google.maps.LatLng(position.coords.latitude,
                                       position.coords.longitude);

      var infowindow = new google.maps.InfoWindow({
        map: map,
        position: pos,
        content: 'Location found using HTML5.'
      });

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

  function calcRoute() {
  var start = document.getElementById("start").value;
  var selectedMode = document.getElementById("mode").value;
      
  var request = {
    origin:start,
    destination: new google.maps.LatLng(51.985103, 5.898730),
    travelMode: google.maps.TravelMode[selectedMode]
  };
  directionsService.route(request, function(result, status) {
    if (status == google.maps.DirectionsStatus.OK) {
      directionsDisplay.setDirections(result);
    }
  });
}
        google.maps.event.addDomListener(window, 'load', initialize);
        
    </script>

    
<style>
  #map_canvas {
    box-shadow: 1px 16px 9px -12px #333;
    height: 350px;
    margin-bottom: 20px;
    width: 100%;
}
.canvas_btm {

background-repeat : no-repeat;
height : 25px;
width: 100%;
}
.direction {
    float: right;
    margin: auto auto 20px;
    width: 590px;
}
.direction table {
    border: 1px solid #000000;
    float: right;
    margin: 4px 0 10px auto;
    width: 403px;
}
.direction table td input[type="text"] {
color : #000;
padding : 0;
}

.item-page p {
    width: 271px;
}
</style>

  </head>
  <body>

<?php include('navbar.php'); ?>

  <div id="map_canvas"></div> 
    <!--<div class="canvas_btm"></div>-->
   
<form role="form"><div class="col-lg-6">
<div class="form-group">
     <label for="InputName">Your address or location:</label>
     <input type="text" class="form-control" name="start"  id="start" placeholder="Enter your adres/city"  value=""  onchange="calcRoute()" />
      </div></div>
  <!--    <form role="form"></form> -->

<div class="col-lg-6">
<div class="form-group">
<label for="InputName">Method of travel:</label>   
<select class="form-control" id="mode" onchange="calcRoute()">
  <option value="DRIVING">Driving</option>
  <option value="WALKING">Walking</option>
  <option value="BICYCLING">Bicycling</option>
  <option value="TRANSIT">Transit</option>
</select>
</div></div>

 <div>
        <strong>Results</strong>
      </div>
      <div id="output"></div>
    </div>
</div>



  </body>
</html>