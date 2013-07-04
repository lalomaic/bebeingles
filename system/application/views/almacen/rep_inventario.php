<script>
  $(document).ready(function() {
	
	$('#familias').change(function(){
            get_subfamilias($(this).val());
	});
	//Cargar subfamilias si una falimia esta seleccionada
        if($('#familias').val()>0){
            get_subfamilias($('#familias').val());
        };
         
        
});

function get_subfamilias(valor){
if(valor >= 0) {
        $.post("<? echo base_url(); ?>index.php/ajax_pet/subfamilias",{ enviarvalor: valor},
        function(data){
            $('#subfamilia').html(data);
            $('#subfamilia_productos').focus();
	    if($('#subfamilia_id').val()>0){
                $('#subfamilias_productos').val($('#subfamilia_id').val());
                $('#subfamilia_id').val(0);
            };
        });
    }
};
  
  </script>
<?php
$selectf[0]="Elija";
if($familias!=false){
	foreach($familias->all as $row){
		$y=$row->id;
		$selectf[$y]=$row->tag;
	}
}

//Construir Subfamilia de Productos
$select2[0]="Elija Familia";
//Construir Empresas
$select1="<option value='0'>Todos</option>";
foreach($empresas->all as $row){
	$y=$row->id;
	$select1.="<option value='$y'>$row->razon_social</option>";
}
$empresas="<select name='empresas'>".$select1."</select>";
$espacios_fisicos[0]="TODAS";

//Titulo
echo "<h2>$title</h2>";
//Select para ordenar la consulta
$options="<option value='0'>Elija</option><option value='1'>Empresas</option><option value='2'>Espacio Fí­sico</option><option value='3'>Descripcion</option>";
$select1="<select name='nivel1'>".$options."</select>";


$fecha=array(
		'class'=>'date_input',
		'name'=>'fecha',
		'size'=>'10',
		'readonly'=>'readonly',
		'value'=>'',
);
//Hora
$hora="";
for($x=1;$x<25;$x++){
	$hora.="<option value='$x'>$x</option>";
}
//Minuto
$min="";
for($x=0;$x<60;$x++){
	$min.="<option value='$x'>$x</option>";
}

?>
<script>
  function send1(){
    document.report.submit();
  }
  $($.date_input.initialize);
  jQuery(document).ready(
	function(){
		jQuery(jQuery.date_input.initialize);
		}
	);

</script>
<?php
$atrib=array('name'=>'report', 'target'=>"pdf");
echo form_open($ruta."/".$ruta."_reportes/rep_inventario_pdf/", $atrib) . "\n";
echo form_hidden('subfuncion', "$subfuncion");
echo form_fieldset('<b>Argumentos del Reporte</b>') . "\n";
$img_row="".base_url()."images/table_row.png";
echo "<input type='hidden' id='subfamilia_id' value='$subfamilia_productos'/>";
?>
<table width="100%" class='form_table'>
	<tr>
		<td width="30%" valign="top" align="center">
			<center>
				<table border="1">
					<tr>
						<th colspan="4">Paso No 1 - Filtrar Reporte</th>
					</tr>
					<tr>
						<td colspan="0">Empresa:</td>
						<td colspan="3"><?php echo $empresas;?></td>
					</tr>
					
					<tr>
						<td colspan="0">Tiendas</td>
						<td colspan="3"><?php echo form_dropdown('espacios', $espacios_fisicos,0);?>
						</td>
					</tr>
					<tr>
						<td colspan="0">Familia:</td>
						<td colspan="3"><?php echo form_dropdown('cfamilia_id', $selectf, $familias, "id='familias'");?>
						</td>
					</tr>
					<tr>
						<td colspan="0">Subfamilia</td>
						<td colspan="3">
							<span id='subfamilia'>
								<? echo form_dropdown('csubfamilia_id', $select2, $subfamilia_productos, "id='subfamilia_productos'");?>
                                                        </span>
						</td>
					</tr>
					<tr>
						<th colspan="4">Paso No 2 - Fecha</th>
					</tr>
					<tr>
						<td>Fecha</td>
						<td colspan="3"><?php echo form_input($fecha);?> Hora: <select
							name='hora'><? echo $hora; ?>
						</select> Min: <select name='min'><? echo $min; ?>
						</select></td>
					</tr>
					<tr>
						<th colspan="4">Paso No 3 - Ordenar Por</th>
					</tr>
					<tr>
						<td>Nivel 1</td>
						<td colspan="3"><?php echo $select1;?></td>
					</tr>
					
					<tr>
						<td colspan="4" align="right"><button type="button"
								onclick="javascript:send1()">Informe</button>
							</form></td>
					</tr>
				</table>
			</center>
		</td>
	</tr>
	<tr>
		<td colspan='4'><iframe src='' name="pdf" width="100%" height='700'></iframe>
		</td>
	</tr>
</table>
</center>

