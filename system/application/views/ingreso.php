<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo $title; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="<?php echo base_url();?>images/favicon.ico"
	rel="shortcut icon" type="image/x-icon" />
<?= $this->assetlibpro->output('css'); ?>
<?= $this->assetlibpro->output('js'); ?>
<script>
$(document).ready(function() {
    $(".modal_pdf").fancybox({
	  'width'				: '95%',
	  'height'			: '95%',
	  'autoScale'			: false,
	  'transitionIn'		: 'none',
	  'transitionOut'		: 'none',
	  'type'				: 'iframe'
    });
});
</script>
</head>
<body>
	<table class="callout_main" cellpadding="0" cellspacing="0">
		<tbody>
			<tr>
				<td colspan="2" rowspan="2">
					<table class="main_page" cellpadding="0" cellspacing="0">
						<tbody>
							<tr>
								<td>
									<table width="100%" border="0" cellpadding="0" cellspacing="0">
										<tbody>
											<tr>
												<td>
													<table id="quick_menu" class="quick_menu" border="0"
														cellpadding="0" cellspacing="0">
														<tbody>
															<tr>
																<td style="width: 100%;" class="quick_menu_left"
																	align="left"><img
																	src="<?php echo base_url();?>images/logo.png"
																	title="Logo de la Empresa" width="150" alt=""
																	align="left"> <b>Bebé Inglés</b><br /> <a href="">Nombre
																		de Usuario: <?php echo $username; ?>
																</a> <br>Menú Principal</td>
																<td class="quick_menu_tabs">
																	<table class="quick_menu_tabs" cellpadding="0"
																		cellspacing="0">
																		<tbody>
																			<tr>
																				<td class="quick_menu_tab" align="center"><a
																					accesskey="1"
																					href="<?php echo base_url();?>index.php/inicio/acceso/bienvenida"><span
																						style="text-decoration: underline;">1</span> Menú
																						Principal</a></td>
																				<td class="quick_menu_tab" align="center"><a
																					accesskey="2" href=""><span
																						style="text-decoration: underline;">2</span>
																						Avisos</a></td>
																				<td class="quick_menu_tab" align="center"><a
																					accesskey="3"
																					href="<?php echo base_url();?>index.php/inicio/changue_password"><span
																						style="text-decoration: underline;">3</span>
																						Cambiar contraseña</a></td>
																				<td class="quick_menu_tab" align="center"><a
																					target="_blank" rel="external" accesskey="8"
																					href=""><span style="text-decoration: underline;">8</span>
																						Manual</a></td>
																				<td class="quick_menu_tab" align="center"><a
																					accesskey="0"
																					href="<?php echo base_url();?>index.php/inicio/logout"
																					onclick="return confirm('¿Estas seguro que deseas cerrar la sesión?');"><span
																						style="text-decoration: underline;">0</span>
																						Cerrar Sesión</a></td>
																			</tr>
																		</tbody>
																	</table>
																</td>
															</tr>
														</tbody>
													</table>
												</td>
											</tr>
										</tbody>
									</table>
									<table class="main_menu" width="100%" border="0"
										cellpadding="0" cellspacing="0">
										<tbody>
											<tr>
												<td class="main_menu">
													<table class="main_menu">
														<tbody>
															<tr>
																<?php
																//Imprimir Menu del Usuario
																$r=0;
																$s=count($colect1);
																$width=950/$modulos_totales;
																foreach($colect1 as $row){
																	echo "<td class=\"main_menu_unselected\" width='".$width."'><a href=\"".base_url()."index.php/inicio/acceso/{$row['ruta']}/menu\">{$row['nombre']}</a></td>";
					$r+=1;
				}
				for($x=$r;$x<=$modulos_totales;$x++){
					echo "<td class=\"main_menu_unselected\" width='".$width."'></td>";
				}
				?>
															</tr>
														</tbody>
													</table>
												</td>
											</tr>
										</tbody>
									</table> <?php
	  $this->load->view($principal);
	?>
									<div style="width: 100%; margin-left: 30%;">
										<div id="digiclock"></div>
									</div>
									<p style="text-align: center; font-size: 12pt;">
										Fecha:
										<?php echo date("d-m-Y");?>
									</p>
									<table id="footer" width="100%">
										<tbody>
											<tr>
												<td class="footer" align="center"><a href="void(0)">SIIAPRE
														- Bebé Inglés 2012- Desarrollado por <b>Soluciones
															Tecnológicas de Codigo Abierto</b>
												</a></td>
											</tr>
										</tbody>
									</table>

</body>
</html>
