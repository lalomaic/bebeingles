<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>El Bebé Inglés ERP</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="<?php echo base_url();?>css/template_css.css"
	rel="stylesheet" type="text/css">
<script src="<?php echo base_url();?>css/jquery.js"></script>
<script src="<?php echo base_url();?>css/jquery.form.js"></script>
<script>

$(document).ready(function () {
    $("table").hide();
    $("table").fadeOut("3000");
    $("table").slideDown("slow");
    $("#username").focus();
});
</script>
</head>
<body>
	<?php echo form_open('inicio/login') . "\n"; ?>
	<table align="center" width="700" id="table">
		<tr>
			<td height="100"></td>
		</tr>
		<tr>
			<th colspan="2">Identificación del Empleado</th>
		</tr>
		<tr>
			<td valign="middle" style="border: 1px solid red;" bgcolor="#ffffff">
				<div style="text-align: center;">
					<img src="<?php echo base_url(); ?>images/logo.png" width="500px">
				</div>
			</td>
			<td bgcolor="#FEFEA7" style="border: 1px solid red;"><?php echo $msg; ?>
				<p style="text-align: right;">
					<label for="username">Usuario: </label>
					<?php echo form_input($username); ?>
				</p>
				<p style="text-align: right;">
					<label for="password">Contraseña: </label>
					<?php echo form_password($password); ?>
				</p>
				<p style="text-align: right;">
					<?php echo form_submit('login', 'Entrar'); ?>
				</p>
			</td>
		</tr>
	</table>
	<?php echo form_close(); echo "\n"; ?>
</body>
</html>
