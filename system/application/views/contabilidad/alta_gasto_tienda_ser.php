<?php
$select3[0]="Elija";
if($cuentas_bancarias!=false){

	foreach($cuentas_bancarias->all as $row){
		$y=$row->id;
		if($row->empresa_id>0 and $row->estatus_general_id==1)
	  $select3[$y]=$row->banco. " - ".$row->numero_cuenta;
	}
}
//Inputs
$monto=array(
		'name'=>'monto',
		'id'=>'monto',
		'class'=>'subtotal',
		'size'=>'10',
		'value'=>'',
);

$fecha=array(
		'name'=>'fecha',
		'id'=>'fecha',
		'class'=>'date_input',
		'size'=>'12',
		'value'=>'',
		'onlyread'=>'onlyread'
);

$concepto=array(
		'name'=>'concepto',
		'id'=>'concepto',
		'size'=>'30',
		'value'=>'',
);
$referencia=array(
		'name'=>'referencia',
		'id'=>'referencia',
		'size'=>'15',
		'value'=>'',
);
?>
<script>
	$(document).ready(function() {
		$($.date_input.initialize);
	});

</script>
<?php
$this->load->view("contabilidad/js_alta_gasto_tienda");
//Titulo
echo "<h2>$title</h2>";
//Abrir Formulario
echo "<table width=\"95%\" class='form_table'>";
$img_row="".base_url()."images/table_row.png";

//Campos del Formulario
echo "<tr><th>1.- Seleccione la Sucursal</th><th></th></tr>";
echo "<tr><td class='form_tag' align='center'>";
echo form_dropdown("espacio", $espacios, 0,"id='espacio'");
echo "</td><td class='form_tag'>";
echo "<a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_gastos_tiendas\"><img src=\"".base_url()."images/factura.png\" width=\"30px\" title=\"Lista de Gastos\" align='right'></a>";
echo "</td></tr></table>";

echo "<table width=\"95%\" class='form_table'>";
$img_row="".base_url()."images/table_row.png";
?>
<tr>
	<th>Fecha</th>
	<th>Rubro</th>
	<th>Cuenta Bancaria</th>
        <th>Referencia</th>
        <th>Concepto</th>
	<th>Monto</th>
</tr>
<?php
for($x=0;$x<$lineas;$x++){
	$fecha['id'].=$x; $concepto['id'].=$x; $monto['id'].=$x; $referencia['id'].=$x;

	echo "<tr><td>"; echo form_input($fecha); echo "</td>"; echo "<td>"; echo form_dropdown("cgasto_id$x", $catalogo_gastos, 0,"id='cgasto_id$x'"); echo "</td><td>"; echo form_dropdown("cuenta_empresa$x", $select3, 0, "id='cuenta_empresa$x'"); echo "</td><td>"; echo form_input($referencia); echo"</td><td>"; echo form_input($concepto); echo "</td><td align='right'><span id='estatus$x'></span>"; echo form_input($monto); echo "</td></tr>";

	$fecha['id']="fecha";	$concepto['id']="concepto"; $referencia['id']="referencia"; $monto['id']="monto";

}
echo "<tr><th colspan='5' align='right'><strong>Total</strong></th><th align='right'><input type='text' name='total' id='total' value='0' size='10' class='subtotal'></th></tr>";

echo "<tr><th colspan='7' align='right'><button type='button' onclick=\"window.location='".base_url()."index.php/inicio/acceso/".$ruta."/menu'\">Cerrar </button>";
echo "<button type='button' onclick=\"window.location='".base_url()."index.php/contabilidad/contabilidad_c/formulario/alta_gasto_tienda'\">Nueva Sucursal</button></th></tr>";

echo "</table>";



?>
