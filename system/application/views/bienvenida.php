<?php
//Obtener las promociones
$prom=array();
if($promociones!=false) {
	if(is_array($promociones->all)){
		$r=0;
		foreach($promociones->all as $linea){
			//Recorrer todas las promociones para obtener las validas para la ubicaciÃ³n
			$matriz=explode(",", $linea->espacios_fisicos);
			//print_r($matriz);
			if(array_search($espacio_fisico_id, $matriz)>0){
				//Si esta considerado ese espacio fisico en la promocion
				$dias_h=explode(",", $linea->dias_horas);
				$dh="";
				foreach($dias_h as $dias=>$k){
					//Ubicar cada dia y hora de la promociones
					//Obtener el numero de dia de la semana
					$d=substr($k, 0, 1);
					if($d==1){
						$x="Lunes";
					} else if($d==2){
						$x="Martes";
					} else if($d==3){
						$x="Miercoles";
					} else if($d==4){
						$x="Jueves";
					} else if($d==5){
						$x="Viernes";
					} else if($d==6){
						$x="Sábado";
					} else if($d==7){
						$x="Domingo";
					} else {
						$x="Todos";
					}
					//Obtener la hora inicial y final
					$horas = strstr($k, "&");
					$hf=str_replace("&", "-", substr(strstr($horas, "&"), 1));
					//Agregarlo en una cadena
					$dh.="". $x." ( ".  $hf ." ),  ";
				}
				$prom[$r]="<strong>$linea->descripcion $". number_format($linea->precio_venta, 2, ".",",") ." </strong>$dh";
				//echo $linea->presentacion;
				$r+=1;
			}
		}
	}
}
?>

<style>
#inicio {
	background-image: url("<?php echo base_url();?>images/fondo.jpeg");
	color: black;
	border-bottom: 3px solid #ccc;
	border-top: 3px solid #ccc;
	height: 160px;
}
</style>
<div id="inicio">
	<h2>Bienvenido(a) al SIIAPRE El Bebe Ingles</h2>
	<h3>
		Sistema Integral de Administración y Planeación de los Recursos
		Empresariales<br /> Sucursal: <strong><? echo $espacio; ?>.</strong>
		Usuario: <b><? echo $usuario; ?>
	
	</h3>
</div>
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
												title="Promociones" alt=""></td>
											<td class="menu_group_headers_text">Promociones</td>
										</tr>
									</tbody>
								</table>
							</td>
							<td class="menu_group_headers">
								<table>
									<tbody>
										<tr>
											<td><img src="<?php echo base_url();?>images/ar.png"
												title="Avisos" alt=""></td>
											<td class="menu_group_headers_text">Avisos</td>
										</tr>
									</tbody>
								</table>
							</td>
							<td class="menu_group_headers">
								<table>
									<tbody>
										<tr>
											<td><img src="<?php echo base_url();?>images/inventory.png"
												title="Eventos" alt=""></td>
											<td class="menu_group_headers_text">Eventos</td>
										</tr>
									</tbody>
								</table>
							</td>
						</tr>
						<tr>
							<td class="menu_group_items">
								<!-- Gereral set up options -->
								<table class="table_index" width="100%">
									<tbody>
										<?php
										$x=0;
										//print_r($prom);
										foreach($prom as $line=>$v){
											echo "<tr><td class=\"promociones\"><li>".$v."</li></td></tr>";
											$x+=1;
										}
										?>
									</tbody>
								</table>
							</td>
							<td class="menu_group_items">
								<!-- AR/AP set-up options -->
								<table class="table_index" width="100%">
									<tbody>
										<?php
										/*foreach($rep as $line){
										 echo "<tr><td class=\"menu_group_item\"><a href=\"{$line['link']}\"></a><li><a href=\"{$line['link']}\">{$line['nombre']}</a></li></td></tr>";
										}*/
										?>
									</tbody>
								</table>
							</td>
							<td class="menu_group_items">
								<!-- Inventory set-up options -->
								<table class="table_index" width="100%">
									<tbody>
										<?php
										/*foreach($man as $line){
										 echo "<tr><td class=\"menu_group_item\"><a href=\"{$line['link']}\"></a><li><a href=\"{$line['link']}\">{$line['nombre']}</a></li></td></tr>";
										}*/
										?>
									</tbody>
								</table>
							</td>
						</tr>
					</tbody>
					</td>
					</tr>
					<tr>
						<td align="center" colspan="3"><img
							src="<?=base_url()?>images/logo.png" width="400px" align="center">
						</td>
					
					
					</tbody>
				</table>