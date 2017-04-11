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


     <div id="map_canvas"></div> 
    <!--<div class="canvas_btm"></div>-->
    

    <form role="form">
           <form role="form">
        <div class="col-lg-6">
<div class="form-group">
     <input type="text" class="form-control" id="start"  id="InputName" placeholder="Enter your adres/city" value=""  onchange="calcRoute()" />
     <label for="InputName">Enter your address or Curunt location in.</label>
   
            </div>
            </div></form>



<?php include('navbar.php'); ?>


    <div class="clearfix"></div>
    	
    
<div class="container-fluid" style="margin-top:80px;">
	
   

</div>

</body>
</html>