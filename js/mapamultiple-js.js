

function mapLoad(lat,lon, id){
  
    $("#centros li").removeClass('current');
    $("#centro"+id).addClass('current');

/*    var styles = [
        {
          stylers: [
            { hue: "#232323" },
            { saturation: -100 }
          ]

        }
    ];*/

    var styles = [
       
        {
          "featureType": "poi",
          "stylers": [
            { "visibility": "off" }
          ]
        },
        {
          "featureType": "poi.park",
          "stylers": [
            { "visibility": "on" }
          ]
        }
    ];

  // Create a new StyledMapType object, passing it the array of styles,
  // as well as the name to be displayed on the map type control.

    var styledMap = new google.maps.StyledMapType(styles,
    {name: "Styled Map"});


  // Create a map object, and include the MapTypeId to add
  // to the map type control.

    var mapOptions = {
      zoom: 18,
      scrollwheel: false,
      draggable:false,
      clickableIcons: false,
      center: new google.maps.LatLng(lat,lon),
      mapTypeControlOptions: {
      mapTypeIds: [google.maps.MapTypeId.ROADMAP,'map_style']
          }
    };


    // Pintamos el mapa

    var map = new google.maps.Map(document.getElementById('map_canvas'),  mapOptions);


    // Cambiamos el icono del mapa

    var image = 'images/icons/gmapMarker.svg';
    var myLatLng = new google.maps.LatLng(lat,lon);
    var beachMarker = new google.maps.Marker({
    position: myLatLng,
    map: map,
    icon: image
    });
  
  /*
  google.maps.event.addListener(beachMarker, 'click', function() {
    if(id == 1){
      infowindow.setContent("CLÍNICA BARCELONA<br />C/ Viladomat 158 - 08015");
    } else if(id == 2){
      infowindow.setContent("<strong><?php echo addslashes(BRAND);?></strong><br /><?php echo addslashes(COMMON_DIRECCION);?>");
    } else{
      infowindow.setContent("<strong><?php echo addslashes(BRAND);?></strong><br /><?php echo addslashes(COMMON_DIRECCION);?>");
    }
    infowindow.open(map, this);
  });
  */
  
  /*
   infoWindow = new google.maps.InfoWindow();
    var windowLatLng = new google.maps.LatLng(lat, lon);
    infoWindow.setOptions({
        content: "<div>This is the html content.</div>",
        position: windowLatLng
    });
    infoWindow.open(map); 
  */
  
  /*
  //ESTE FUNCIONA
  var infowindow = new google.maps.InfoWindow();
  if(id == 1){
    infowindow.setContent('CLÍNICA BARCELONA<br />C/ Viladomat 158 - 08015');
  } else if(id == 2){
    infowindow.setContent('some content here');
  } else if(id == 3){
    infowindow.setContent('some content here');
  }
  infowindow.open(map, beachMarker);
  */



    //Associate the styled map with the MapTypeId and set it to display.

    map.mapTypes.set('map_style', styledMap);
    map.setMapTypeId('map_style');
  
  

}
