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

    <style>
  #map {
    height: 100%;
  }
  html, body {
    height: 100%;
    margin: 0;
    padding: 0;
  }
</style>

  </head>
  <body>

<?php include('navbar.php'); ?>

    <div id="map"></div>
    <script>
      function initMap() {
        var chicago = {lat: 41.85, lng: -87.65};
        var indianapolis = {lat: 39.79, lng: -86.14};

        var map = new google.maps.Map(document.getElementById('map'), {
          center: chicago,
          scrollwheel: false,
          zoom: 7
        });

        var directionsDisplay = new google.maps.DirectionsRenderer({
          map: map
        });

        // Set destination, origin and travel mode.
        var request = {
          destination: indianapolis,
          origin: chicago,
          travelMode: 'DRIVING'
        };

        // Pass the directions request to the directions service.
        var directionsService = new google.maps.DirectionsService();
        directionsService.route(request, function(response, status) {
          if (status == 'OK') {
            // Display the route on the map.
            directionsDisplay.setDirections(response);
          }
        });
      }

    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&callback=initMap"
        async defer></script>
  </body>
</html>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
<link href="bootstrap/css/bootstrap-theme.min.css" rel="stylesheet" media="screen">
<script type="text/javascript" src="jquery-1.11.3-jquery.min.js"></script>
<link rel="stylesheet" href="style.css" type="text/css"  />
<title>Contact US</title>

    <script src="http:/maps.google.com/maps?file=api&v=2&key=AIzaSyA-DljXyQ49BOznFIbjDj8_m2b9R5AfypU" type="text/javascript"></script>

 


  
    <script type="text/javascript">
    
      var directionsDisplay;
      var directionsService = new google.maps.DirectionsService();
      var map;
    
      function initialize() {
         directionsDisplay = new google.maps.DirectionsRenderer();
         var frankfurt = new google.maps.LatLng(52.151292, 5.658739);
         var mapOptions = {
         zoom:10,
         mapTypeId: google.maps.MapTypeId.ROADMAP,
         center: frankfurt
        }
        map = new google.maps.Map(document.getElementById("map_canvas"),mapOptions);
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
    destination: new google.maps.LatLng(52.151292, 5.658739),
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
    
   
</head>

<body>


    <!--
      
       <div id="map_canvas"></div> 
    
    

    <form role="form">
           <form role="form">
        <div class="col-lg-6">
<div class="form-group">
     <input type="text" class="form-control" id="start"  id="InputName" placeholder="Enter your adres/city" value=""  onchange="calcRoute()" />
     <label for="InputName">Enter your address or Curunt location in.</label>
   
            </div>
            </div></form>





    <div class="clearfix"></div>
    	
    
<div class="container-fluid" style="margin-top:80px;">
	

</div>
   -->


</body>
</html>



