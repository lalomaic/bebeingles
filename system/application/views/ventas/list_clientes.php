<script>
$(document).ready(function() {
	$('#clientes').select_autocomplete();
});
</script>

<?php 
$default="TODOS";
$select1[0]=$default;
if($clientes!= false){
	foreach($clientes->result_array() as $row){
		$y=$row['id'];
		$select1[$y]=$row['razon_social'];
	}
}

echo "<h2>$title</h2>";
echo "<div style='width:920px;margin:auto' align=\"right\">";
echo "<a href=\"". base_url() . "index.php/" . $ruta . "/" . $controller . "/" . $funcion . "/alta_cliente\"><img src=\"".base_url()."images/proveedor.png\" width=\"40px\" title=\"Agregar Cliente\" target='_blank'>Agregar Cliente </a></div>";

$atrib=array('name'=>'form1', 'id'=>'form1', 'target'=>"listado");
echo form_open($ruta."/".$controller.'/formulario/list_clientes/listado/', $atrib);
echo "<div align='center' style='background-color:#ccc; border:1px solid black; display:inline-block; width:50%; float:center; margin-left:300px'>";
//echo '<div id="fil" align="center">';
echo "<h3>Filtrado por Cliente</h3>";
echo "<label for=\"clientes\">Clientes:</label><br/>";echo form_dropdown('cliente',$select1, 0, "id='clientes'"); echo "<br/><br/>";
echo '<button type="submit" id="boton1" >Ver Listado</button></div>';
//echo '</div>';
echo form_close();

echo "<table width=\"100%\"><tr align='center'><td><iframe src='' name='listado'  id='frame1' width=\"90%\" height='800'></iframe></td></tr></table>";

?>
