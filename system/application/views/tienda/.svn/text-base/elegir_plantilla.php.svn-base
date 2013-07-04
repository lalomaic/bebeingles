<script>
	$(document).ready(function() { 
		$('#plantilla').change(function(){
			id=$(this).val();
			location.href="<? echo base_url(); ?>index.php/tienda/tienda_c/formulario/alta_traspaso_plantilla/alta_traspaso/"+id;
		});
	});
</script>
<?php
//Plantillas
$select2[0]="Elija";
if($plantillas != false){
	foreach($plantillas->all as $row){
		$y=$row->stock_id;
		$select2[$y]=$row->nombre;
	}
}
echo "<h2>$title en $tienda</h2>";
echo "<div id='contenedor' style='text-align:center;'><label for=\"plantilla\">Elija la plantilla que desea aplicar: </label>"; echo form_dropdown('plantilla', $select2, 0, "id='plantilla'"); echo "</div>";
?>