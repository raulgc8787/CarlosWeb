<div class="boxSearch">
	<form onSubmit="return validarForm();" action="send.php" class="formSearch" id="formularioSearch" name="formularioSearch" method="post" >
		<ul>
			<li class="full">
				<label><?php echo COMMON_LABELNOMBRE; ?></label>
				<input name="Nombre" type="text" id="Nombre" required/>
			</li>
			<li class="full">
				<label>Select01</label>
			  	<span class="selectcontainer"><select>
			  		<option>Option01</option>
			  		<option>Option02</option>
			  		<option>Option03</option>
			  		<option>Option04</option>
			  	</select></span>
			</li>
			<li class="full">
				<span class="button fullButton submit" name="button" type="submit" id="button"><?php echo COMMON_BUSCAR; ?></span>
			</li>

	</form>
</div>