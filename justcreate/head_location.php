<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&key=AIzaSyDbtpWrcRhFcocHY8WqYL4_FbdTE2fcZA4"></script>
<script type="text/javascript">
	var geocoder = new google.maps.Geocoder();
	var map;
	var marker;
	var image = '../images/icons/gmapMarker.svg';
	
	function geocodePosition(pos) {
		geocoder.geocode({
			latLng: pos
		}, function(responses) {
			if (responses && responses.length > 0) {
				//updateMarkerAddress(responses[0].formatted_address);
			} else {
				//updateMarkerAddress('Cannot determine address at this location.');
			}
		});
	}

	function updateMarkerPosition(latLng) {
		
		//document.getElementById("lat").value = latLng.lat();
		//document.getElementById("lng").value = latLng.lng();
		valueLatLng = latLng.lat() + "," + latLng.lng()
		document.getElementById("text_latlng").value = valueLatLng;
		
	}

	function initialize(latitud,longitud) {
		if(!latitud){
			latitud = "41.692248";	
		}
		if(!longitud){
			longitud = "1.74586";
		}
		var latLng = new google.maps.LatLng(latitud,longitud);
		map = new google.maps.Map(document.getElementById('map_canvas'), {
			zoom: 15,
			center: latLng,
			mapTypeId: google.maps.MapTypeId.ROADMAP
		});
		
		marker = new google.maps.Marker({
			position: latLng,
			title: '',
			map: map,
			draggable: true,
			icon: image
		});

		// Update current position info.
		updateMarkerPosition(latLng);
		geocodePosition(latLng);

		// Add dragging event listeners.
		google.maps.event.addListener(marker, 'dragstart', function() {
			//updateMarkerAddress('Dragging...');
		});

		google.maps.event.addListener(marker, 'drag', function() {
			//updateMarkerStatus('Dragging...');
			updateMarkerPosition(marker.getPosition());
		});

		google.maps.event.addListener(marker, 'dragend', function() {
			//updateMarkerStatus('Drag ended');
			geocodePosition(marker.getPosition());
		});
	}
	
	
	
	
	function codeAddress() {
		
		//window.alert("Ubicación establecida. Si es necesario, ajusta manualmente la ubicación arrastrando el marcador sobre el mapa");
		document.getElementById("map").style.display = "block";
	
		initialize();
		
		var poblacion = document.getElementById("text_city").value;
		var direccion = document.getElementById("text_address").value;



		var address = direccion + ", " + poblacion ;
		var address2 = direccion;
		
		geocoder.geocode( { 'address': address}, function(results, status) {
		  if (status == google.maps.GeocoderStatus.OK) {
			
			marker.setPosition(results[0].geometry.location);
					
			map.setCenter(results[0].geometry.location);
			map.setZoom(15);
			
			updateMarkerPosition(marker.getPosition());
			geocodePosition(marker.getPosition());
			
		  } else {
				
				//alert("Geocode was not successful for the following reason: " + status);
				
				geocoder.geocode( { 'address': address2}, function(results, status) {
				  if (status == google.maps.GeocoderStatus.OK) {
					
					marker.setPosition(results[0].geometry.location);
							
					map.setCenter(results[0].geometry.location);
					map.setZoom(15);
					
					updateMarkerPosition(marker.getPosition());
					geocodePosition(marker.getPosition());
					
				  } else {
					//alert("Geocode was not successful for the following reason: " + status);
				  }
				});
		  }
		});
		
		if(document.getElementById("map").style.display = "none"){
			document.getElementById("map").style.display = "block";
			
		}
	}
	
	function codeAddressLoad(lati,longi) {
		initialize(lati,longi);

	}	
</script>