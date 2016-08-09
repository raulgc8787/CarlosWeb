<script type="text/javascript">

$(function() {
	
	var myOptions = {
		zoom: 15,
		center: new google.maps.LatLng(<?php echo MAPS_COORDENADAS;?>),
		scaleControl: false,   
		scrollwheel: false,
		navigationControl: false,
		mapTypeControl: false,
		draggable: false,

      mapTypeId: google.maps.MapTypeId.ROADMAP

    };
    
	var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
	var bounds = new google.maps.LatLngBounds();
	var image = 'images/icons/gmapMarker.svg';

	google.maps.event.addListener(map, 'zoom_changed', function() {
		zoomChangeBoundsListener = 
			google.maps.event.addListener(map, 'bounds_changed', function(event) {
				if (this.getZoom() > 15 && this.initialZoom == true) {
					// Change max/min zoom here
					this.setZoom(15);
					this.initialZoom = false;
				}
			google.maps.event.removeListener(zoomChangeBoundsListener);
		});
	});
	
	map.initialZoom = true;
	map.fitBounds(bounds);
	
	var latlng1 = new google.maps.LatLng(<?php echo MAPS_COORDENADAS;?>);
	var marker1 = new google.maps.Marker({
	  position: latlng1,
	  map: map,
	  title: '',
	  zIndex: 1,
	  html: '',
	  icon: image
	});
	
	bounds.extend(marker1.position);
	
	google.maps.event.addListener(marker1, 'click', function() {
	  infowindow.setContent("<strong><?php echo addslashes(BRAND);?></strong><br /><?php echo addslashes(COMMON_DIRECCION);?>");
	  infowindow.open(map, this);
	});
		
		
		/*var latlng2 = new google.maps.LatLng(41.973436, 2.807904);
		var marker2 = new google.maps.Marker({
		  position: latlng2,
		  map: map,
		  title: '',
		  zIndex: 2,
		  html: "<strong><?php echo addslashes(BRAND);?></strong><br /><?php echo addslashes(COMMON_DIRECCION2);?>",
		  icon: image
		});
		
		bounds.extend(marker2.position);
		
		google.maps.event.addListener(marker2, 'click', function() {
		  infowindow.setContent(this.html);
		  infowindow.open(map, this);
		});*/

	infowindow = new google.maps.InfoWindow({
			content: ""
	});

	map.fitBounds(bounds);

});

</script>