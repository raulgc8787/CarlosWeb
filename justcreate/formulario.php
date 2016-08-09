<?php 
include("head.php");
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ES">
<head>
<title>Backoffice</title>
<meta name="keywords" content="" />
<meta name="description" content="" />

<?php include("header.php");?>
</head>

<body>
<?php include("body.php");?>

<div class="section-header">
    <h1>Gestión de...</h1>
</div>

<form action="" method="get">
	<ul>
    	<li class="full"><label>Label</label><input name="" type="text" /></li>
    	<li><label>Label</label><input name="" type="text" /></li>
    	<li><label>Label</label><input name="" type="text" /></li>
    	<li><label>Label</label><input name="" type="text" /></li>
    	<li class="full checkradiocontiner">
        	<label><input name="" type="radio" value="" /><span>Label</span></label>
        	<label><input name="" type="radio" value="" /><span>Label</span></label>
        </li>
    	<li><label>Label</label><textarea name="" cols="" rows=""></textarea></li>
    	<li class="full checkradiocontiner">
        	<label><input name="" type="checkbox" value="" /><span>Label</span></label>
            <label><input name="" type="checkbox" value="" /><span>Label</span></label>
        </li>

    </ul>
    <div class="form-footer">
    	<input type="button" class="btn" value="Go"/>
        <div id="errors">Error</div>
        <div id="okform">OK!</div>
    </div>
</form>





<?php include("footer.php");?>
</body>
</html>
<?php include("bottom.php");?>