<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&key=AIzaSyDbtpWrcRhFcocHY8WqYL4_FbdTE2fcZA4"></script>
<script type="text/javascript">
  function initialize(lat, lng) {
	
	var myOptions = {
      zoom: 14,
      center: new google.maps.LatLng(lat,lng),
	  navigationControl: true,
  	  mapTypeControl: false,
      scaleControl: true,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    
	var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
	
	var bounds = new google.maps.LatLngBounds();
	
	var image = 'images/icons/gmapMarker.svg';

		var latlng = new google.maps.LatLng(lat,lng);
		var marker = new google.maps.Marker({
		  position: latlng,
		  map: map,
		  title: '',
		  zIndex: 1,
		  html: '',
		  icon: image
		});
		
		bounds.extend(marker.position);
		
		google.maps.event.addListener(marker, 'click', function() {
		  //document.location.href=''
		  infowindow.setContent("");
		  infowindow.open(map, this);
		});

	
	infowindow = new google.maps.InfoWindow({
			content: ""
	});
	

  }
</script>