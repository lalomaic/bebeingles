<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Sistema Integral de Gestion Interna</title>
<meta name="description" content="Sistema Integral de Gestion Interna">
<meta name="keywords" content="">
<meta name="Generator" content="Morelia">
<meta name="robots" content="index, follow">
<style>
/* Styles for the img box */
div.mtImgBoxStyle {
	margin-left: 5px;
	margin-right: 5px;
}
/* Styles for the caption */
div.mtCapStyle {
	font-weight: bold;
	color: black;
	background-color: #ddd;
	padding: 2px;
	text-align: center;
	overflow: hidden;
}
</style>
<link rel="stylesheet" href="<?php echo base_url(); ?>css/lightbox.css"
	type="text/css" media="screen">
<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
<link type="text/css" rel="stylesheet"
	href="<?php echo base_url(); ?>css/template_css.css">
<link rel="shortcut icon"
	href="<?php echo base_url(); ?>images/favicon.ico">
</head>
<body>
	<table id="main" style="width: 900px;" align="center" border="0"
		cellpadding="0" cellspacing="2">
		<tbody>
			<tr>
				<td colspan="2" align="center" width="100%">
					<div id="pathway_outer">
						<div id="pathway_inner">
							<b>Sistema Integral de Gestion Interna</b>
						</div>
					</div>
				</td>
			</tr>
			<tr>
				<td colspan="2" id="header_outer" align="center" width="100%"
					height="125">
					<div id="header_inner1">
						<img src="<?php echo base_url(); ?>images/banner1.png">
					</div>
				</td>
			</tr>
			<tr>
				<td id="left_outer1" style="width: 152px;" valign="top"
					bgcolor="#ef3121">
					<div id="left_inner">
						<table class="moduletable_menu" cellpadding="0" cellspacing="0">
							<tbody>
								<tr>
									<th valign="top"></th>
								</tr>
								<tr>
									<td>
										<table border="0" cellpadding="0" cellspacing="0" width="100%">
											<tbody>
												<?php 
												if($login==false) {
													//echo $this->load->view('menus/'.$menu);
												}
												?>
											</tbody>
										</table>
									</td>
								</tr>
							</tbody>
						</table>
						<table class="moduletable_menu" cellpadding="0" cellspacing="0">
							<tbody>
								<tr>
									<th valign="top"><?php if($login==false) {
										echo "Men� General";
									} ?>
									</th>
								</tr>
								<tr>
									<td><?php if($login==false) {
										echo $this->load->view('menus/'.$menu);
									} ?>
									</td>
								</tr>
							</tbody>
						</table>


					</div>
				</td>
				<td id="content_outer" valign="top">
					<table style="width: 746px;" border="0" cellpadding="0"
						cellspacing="0">
						<tbody>
							<tr>
								<td valign="top" width="100%">
									<div id="user1_outer">
										<div id="user1_inner">
											<table class="moduletable" cellpadding="0" cellspacing="0"
												bgcolor="white">
												<tr>
													<td colspan="2" valign="top"><?php 
													if($menu=="captura") {
														echo $this->load->view('captura/'.$principal);
													} else if($menu=="supervisor"){
														echo $this->load->view('supervisor/'.$principal);
													} else if ($menu=="inicio"){
														echo $this->load->view('login.php');
													} else if($menu=="admin"){
														echo $this->load->view('admin/'.$principal);
													} else if($menu=="juridico"){
														echo $this->load->view('juridico/'.$principal);
													} else if($menu=="jefe_staff"){
														echo $this->load->view('juridico/'.$principal);
													} else if($menu=="secretaria_d"){
														echo $this->load->view('secretaria_direccion/'.$principal);
													} else if($menu=="atencion_c"){
														echo $this->load->view('atencion_c/'.$principal);
													}
													?>
													</td>
												</tr>


											</table>
										</div>
									</div>
								</td>
							</tr>
						</tbody>
					</table>
			
			
			<tr>
				<td colspan="2" align="center" width="100%">
					<div id="pathway_outer">
						<div id="pathway_inner" style="text-align: center;">
							<b>Derechos Reservados H. Ayuntamiento Morelia</b>
						</div>
					</div>
				</td>
			</tr>
		</tbody>
	</table>
	<!-- 1229318246 -->
</body>
</html>
