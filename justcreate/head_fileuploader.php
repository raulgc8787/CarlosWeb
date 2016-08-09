<script language="JavaScript" src="js/dragndrop.js" type="text/javascript"> </script>
<script type="text/javascript">
<?php

$c = db_query($link,$cols);
while($rc = mysqli_fetch_array($c)){
	$field = $rc[0];
	if(substr($field,0,12) == "fotouploader" || substr($field,0,12) == "bannuploader"){
		$nt = substr($field,13,strlen($field)-13); //Ya lo tengo
		?>
		$(document).ready(
		  function() {
			$("#sortme<?php echo $nt;?>").Sortable({
			  accept : 'sortitem<?php echo $nt;?>',
			  onchange : function (sorted) {
				serial = $.SortSerialize('sortme<?php echo $nt;?>');
		
				$.ajax({
						  url: "sortdata.php",
						  type: "POST",
						  data: serial.hash,
						   complete: function(){
						   	pintaMensajeMini('showmsg<?php echo $nt;?>','<?php echo $backoffice['orden_guardado_ok']['es'];?>')
						   },
						  //success: function(feedback){ $('#data').html(feedback); }
						  // error: function(){}
				});
			  }
			});
		  }
	    );
		<?php
	}
}

$result = db_query($link,"SHOW TABLES LIKE '".$table_content."'");
$tableExists = mysqli_num_rows($result) > 0;
if($tableExists){

	//Hare esto por cada idioma
	$i = db_query($link,"SELECT * FROM config_idiomes");
	while($ar_i = mysqli_fetch_array($i)){
		$idiom = $ar_i['nom_idioma'];
		
		$nt = "";
		$c = db_query($link,$cols_content);
		while($rc = mysqli_fetch_array($c)){
			$field = $rc[0];
			if( (substr($field,0,12) == "fotouploader") || (substr($field,0,12) == "fileuploader") || (substr($field,0,12) == "bannuploader") ){
				$nt = substr($field,13,strlen($field)-13); //Ya lo tengo
				?>
				$(document).ready(
				  function() {
					$("#sortme<?php echo $nt.$idiom;?>").Sortable({
					  accept : 'sortitem<?php echo $nt.$idiom;?>',
					  onchange : function (sorted) {
						serial = $.SortSerialize('sortme<?php echo $nt.$idiom;?>');
				
						$.ajax({
								  url: "sortdataidioma.php",
								  type: "POST",
								  data: serial.hash,
								  complete: function(){
									pintaMensajeMini('showmsg<?php echo $nt.$idiom;?>','<?php echo $backoffice['orden_guardado_ok']['es'];?>')
								  },
								  //success: function(feedback){ $('#data').html(feedback); }
								  // error: function(){}
						});
					  }
					});
				  }
				);
				<?php
			}//end if
		}//end while cols_content
	
	}//end while idiomas

}
?>

</script>

<!-- Load Queue widget CSS and jQuery -->
<style type="text/css">@import url(plupload/js/jquery.plupload.queue/css/jquery.plupload.queue.css);</style>
<script type="text/javascript" src="plupload/js/browserplus-min.js"></script>
<script type="text/javascript" src="plupload/js/plupload.full.js"></script>
<script type="text/javascript" src="plupload/js/jquery.plupload.queue/jquery.plupload.queue.js"></script>

<script type="text/javascript">
<?php
$c = db_query($link,$cols);
while($rc = mysqli_fetch_array($c)){
	$field = $rc[0];
	if(substr($field,0,12) == "fotouploader"){
		$nt = substr($field,13,strlen($field)-13); //Ya lo tengo
		?>
	
		// Convert divs to queue widgets when the DOM is ready
		$(function() {
			$("#uploader<?php echo $nt;?>").pluploadQueue({
				// General settings
				
				runtimes : 'html5,flash,silverlight,html4',
				url : 'plupload/fotouploader.php',
				max_file_size : '10mb',
				chunk_size : '1mb',
				dragdrop: true,
				//unique_names : true,
				
				// Views to activate (esto no funciona en el queue)
				/*
				views: {
					list: false,
					thumbs: true,
					active: 'thumbs'
				},
 				*/
				
				unique_names : false, //añadido mio
				// adding this for redirecting to page once upload complete
				preinit: attachCallbacks,
		
				// Resize images on clientside if we can
				//resize : {width : 320, height : 240, quality : 90},
		
				// Specify what files to browse for
				filters : [
					{title : "Image files", extensions : "jpg,gif,png"}
				],
				
				init : {            
						FilesAdded: function(up, files) {
							while(up.files.length > <?php echo $quedanXfotos[$nt];?>) {
								up.removeFile(up.files[0]);
							}
							up.refresh();
						}
					},
		
				// Flash settings
				flash_swf_url : 'plupload/js/plupload.flash.swf',
		
				// Silverlight settings
				silverlight_xap_url : 'plupload/js/plupload.silverlight.xap',
				
				multipart_params : {
					"tabla" : "<?php echo $nt;?>"
				}
				
			});
		
			// Client side form validation
			$('form').submit(function(e) {
				var uploader = $('#uploader<?php echo $nt;?>').pluploadQueue();
		
				// Files in queue upload them first
				if (uploader.files.length > 0) {
					// When all files are uploaded submit form
					uploader.bind('StateChanged', function() {
						if (uploader.files.length === (uploader.total.uploaded + uploader.total.failed)) {
							$('form')[0].submit();
							//document.getElementById("muestraOk").innerHTML = "ok";
						}
					});
		
						
					uploader.start();
				} else {
					//alert('You must queue at least one file.');
				}
		
				//return false;
			});
		});
		// added below to redirect after uploaded
		function attachCallbacks(Uploader) {
		
			Uploader.bind('FileUploaded', function(Up, File, Response) {
			
			  if( (Uploader.total.uploaded + 1) == Uploader.files.length)
			  {

				cargaUploader('<?php echo $nt;?>','<?php echo $id;?>','');

			  }
			});
		}
// added above to redirect after uploade
<?php
	}
}

$result = db_query($link,"SHOW TABLES LIKE '".$table_content."'");
$tableExists = mysqli_num_rows($result) > 0;
if($tableExists){
			
	//Hare esto por cada idioma
	$i = db_query($link,"SELECT * FROM config_idiomes");
	while($ar_i = mysqli_fetch_array($i)){
		$idiom = $ar_i['nom_idioma'];
		
		$nt = "";
		$c = db_query($link,$cols_content);
		while($rc = mysqli_fetch_array($c)){
			$field = $rc[0];
			if(substr($field,0,12) == "fotouploader" || substr($field,0,12) == "bannuploader"){
				$nt = substr($field,13,strlen($field)-13); //Ya lo tengo
				?>
				// Convert divs to queue widgets when the DOM is ready
				$(function() {
					$("#uploader<?php echo $nt.$idiom;?>").pluploadQueue({
						// General settings
						
						runtimes : 'gears,flash,silverlight,browserplus,html5',
						url : 'plupload/fotouploader.php',
						max_file_size : '10mb',
						chunk_size : '1mb',
						//unique_names : true,
						
						unique_names : false, //añadido mio
						// adding this for redirecting to page once upload complete
						preinit: attachCallbacks,
				
						// Resize images on clientside if we can
						//resize : {width : 320, height : 240, quality : 90},
				
						// Specify what files to browse for
						filters : [
							{title : "Image files", extensions : "jpg,gif,png"}
						],
						
						init : {            
								FilesAdded: function(up, files) {
									while(up.files.length > <?php echo $quedanXfotos[$nt][$idiom];?>) {
										up.removeFile(up.files[0]);
									}
									up.refresh();
								}
							},
				
						// Flash settings
						flash_swf_url : 'plupload/js/plupload.flash.swf',
				
						// Silverlight settings
						silverlight_xap_url : 'plupload/js/plupload.silverlight.xap',
						
						multipart_params : {
							"tabla" : "<?php echo $nt;?>",
							"idiom" : "<?php echo $idiom;?>"
						}
						
					});
				
					// Client side form validation
					$('form').submit(function(e) {
						var uploader = $('#uploader<?php echo $nt.$idiom;?>').pluploadQueue();
				
						// Files in queue upload them first
						if (uploader.files.length > 0) {
							// When all files are uploaded submit form
							uploader.bind('StateChanged', function() {
								if (uploader.files.length === (uploader.total.uploaded + uploader.total.failed)) {
									$('form')[0].submit();
									//document.getElementById("muestraOk").innerHTML = "ok";
								}
							});
				
								
							uploader.start();
						} else {
							//alert('You must queue at least one file.');
						}
				
						//return false;
					});
				});
				// added below to redirect after uploaded
				function attachCallbacks(Uploader) {
				
					Uploader.bind('FileUploaded', function(Up, File, Response) {
					
					  if( (Uploader.total.uploaded + 1) == Uploader.files.length)
					  {
						window.location = '<?php echo $_SERVER['REQUEST_URI'];?>';
					  }
					});
				}
			// added above to redirect after uploade
			<?php
			}//end if
			
			if(substr($field,0,12) == "fileuploader"){
				$nt = substr($field,13,strlen($field)-13); //Ya lo tengo
				?>
				// Convert divs to queue widgets when the DOM is ready
				$(function() {
					$("#uploader<?php echo $nt.$idiom;?>").pluploadQueue({
						// General settings
						
						runtimes : 'gears,flash,silverlight,browserplus,html5',
						url : 'plupload/fileuploader.php',
						max_file_size : '10mb',
						chunk_size : '1mb',
						//unique_names : true,
						
						unique_names : false, //añadido mio
						// adding this for redirecting to page once upload complete
						preinit: attachCallbacks,
				
						// Resize images on clientside if we can
						//resize : {width : 320, height : 240, quality : 90},
				
						// Specify what files to browse for
						filters : [
							{ title : "Files", extensions : "*" }
						],
						
						init : {            
								FilesAdded: function(up, files) {
									while(up.files.length > 100) {
										up.removeFile(up.files[0]);
									}
									up.refresh();
								}
							},
				
						// Flash settings
						flash_swf_url : 'plupload/js/plupload.flash.swf',
				
						// Silverlight settings
						silverlight_xap_url : 'plupload/js/plupload.silverlight.xap',
						
						multipart_params : {
							"tabla" : "<?php echo $nt;?>",
							"idiom" : "<?php echo $idiom;?>"
						}
						
					});
				
					// Client side form validation
					$('form').submit(function(e) {
						var uploader = $('#uploader<?php echo $nt.$idiom;?>').pluploadQueue();
				
						// Files in queue upload them first
						if (uploader.files.length > 0) {
							// When all files are uploaded submit form
							uploader.bind('StateChanged', function() {
								if (uploader.files.length === (uploader.total.uploaded + uploader.total.failed)) {
									$('form')[0].submit();
									//document.getElementById("muestraOk").innerHTML = "ok";
								}
							});
				
								
							uploader.start();
						} else {
							//alert('You must queue at least one file.');
						}
				
						//return false;
					});
				});
				// added below to redirect after uploaded
				function attachCallbacks(Uploader) {
				
					Uploader.bind('FileUploaded', function(Up, File, Response) {
					
					  if( (Uploader.total.uploaded + 1) == Uploader.files.length)
					  {
						window.location = '<?php echo $_SERVER['REQUEST_URI'];?>';
					  }
					});
				}
			// added above to redirect after uploade
			<?php
			}//end if
			
		}//end while cols_content
	
	}//end while idioma
}
?>
</script>