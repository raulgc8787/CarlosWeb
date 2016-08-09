<script language="JavaScript" src="js/dragndrop.js" type="text/javascript"> </script>
<script>

$(document).ready(
  function() {
	$("#sortme<?php echo $table;?>").Sortable({
	  accept : 'sortitem<?php echo $table;?>',
	  onchange : function (sorted) {
		serial = $.SortSerialize('sortme<?php echo $table;?>');
		

		$.ajax({
			  url: "sortdatalist.php",
			  type: "POST",
			  data: serial.hash,
			  complete: function(){
				pintaMensajeMini('showmsg<?php echo $table;?>','<?php echo $backoffice['orden_guardado_ok']['es'];?>')
			  }
		});

	  }
	});
  }
);
	
</script>