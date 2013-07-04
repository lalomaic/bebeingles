<?php
//Preparar arrays para Tran, Rep y Mantenimiento
$tran=array();
$rep=array();
$man=array();
$url_base=base_url()."index.php/";
$m=0;$t=0;$r=0;
$pretag=array();
$ruta=array();
$controller=array();
//Datos de los Submodulos
foreach($colect2 as $row){
	$id=$row['submodulo_id'];
	$pretag['s']["$id"]=$row['nombre'];
	$ruta['s']["$id"]=$row['ruta'];
	$controller['s']["$id"]=$row['controller'];
}
//Separar en base al tipo de accion
foreach($colect3 as $row1){
	$sid=$row1['submodulo_id'];
	if($row1['tipo_accion_id']==1){
		$tran[$t]['submodulo']=$sid;
		$tran[$t]['accion_id']=$row1['accion_id'];
		$tran[$t]['nombre']=$pretag['s']["$sid"]." : ".$row1['nombre_accion'];
		$tran[$t]['link']=$url_base.$ruta['s']["$sid"]."/".$controller['s']["$sid"]."/formulario/".$row1['function_controller'];
		$t+=1;
	} else if ($row1['tipo_accion_id']==2){
		$rep[$r]['submodulo']=$sid;
		$rep[$r]['accion_id']=$row1['accion_id'];
		$rep[$r]['nombre']=$pretag['s']["$sid"]." : ".$row1['nombre_accion'];
		$rep[$r]['link']=$url_base.$ruta['s']["$sid"]."/".$ruta['s']["$sid"]."_reportes/formulario/".$row1['function_controller'];

		$r+=1;
	} else if($row1['tipo_accion_id']==3){
		$man[$m]['submodulo']=$sid;
		$man[$m]['accion_id']=$row1['accion_id'];
		$man[$m]['nombre']=$pretag['s']["$sid"]." : ".$row1['nombre_accion'];
		$man[$m]['link']=$url_base.$ruta['s']["$sid"]."/".$controller['s']["$sid"]."/formulario/".$row1['function_controller'];
		$m+=1;
	}


}
echo "<h2>  Módulo de ".$module_name."</h2>";
?>
<table width="100%">
	<tbody>
		<tr>
			<td class="menu_group_area" valign="top">
				<table width="100%">
					<tbody>
						<tr>
							<td class="menu_group_headers">
								<table>
									<tbody>
										<tr>
											<td><img src="<?php echo base_url();?>images/company.png"
												title="Transacciones" alt=""></td>
											<td class="menu_group_headers_text">Transacciones</td>
										</tr>
									</tbody>
								</table>
							</td>
							<td class="menu_group_headers">
								<table>
									<tbody>
										<tr>
											<td><img src="<?php echo base_url();?>images/ar.png"
												title="Reportes" alt=""></td>
											<td class="menu_group_headers_text">Reportes</td>
										</tr>
									</tbody>
								</table>
							</td>
							<td class="menu_group_headers">
								<table>
									<tbody>
										<tr>
											<td><img src="<?php echo base_url();?>images/inventory.png"
												title="Administrar" alt=""></td>
											<td class="menu_group_headers_text">Administrar</td>
										</tr>
									</tbody>
								</table>
							</td>
						</tr>
						<tr>
							<td class="menu_group_items">
								<!-- Gereral set up options --> <?php
								$sub_anterior=0;
								foreach($tran as $line){
									if($line['submodulo']!=$sub_anterior){
										echo "<h3>".$colect2[$line['submodulo']]['nombre']."</h3>";
										$sub_anterior=$line['submodulo'];
									}
									echo "<a class=\"undecoratedLink ui-corner-all\" href=\"{$line['link']}\">{$line['nombre']}</a>";
								}
								?>
							</td>
							<td class="menu_group_items">
								<!-- AR/AP set-up options --> <?php
								$sub_anterior=0;
								foreach($rep as $line){
									if($line['submodulo']!=$sub_anterior){
										echo "<h3>".$colect2[$line['submodulo']]['nombre']."</h3>";
										$sub_anterior=$line['submodulo'];
									}
									echo "<a class=\"undecoratedLink ui-corner-all\" href=\"{$line['link']}\">{$line['nombre']}</a>";
								}
								?>
							</td>
							<td class="menu_group_items">
								<!-- Inventory set-up options --> <?php
								$sub_anterior=0;
								foreach($man as $line){
									if($line['submodulo']!=$sub_anterior){
										echo "<h3>".$colect2[$line['submodulo']]['nombre']."</h3>";
										$sub_anterior=$line['submodulo'];
									}
									echo "<a class=\"undecoratedLink ui-corner-all\" href=\"{$line['link']}\">{$line['nombre']}</a>";
								}
								?>
							</td>
						</tr>
					</tbody>
					</td>
					</tr>
					</tbody>
				</table>