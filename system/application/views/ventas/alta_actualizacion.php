<?php
//Construir Familias
$default="TODOS";
if($familias!= false){
	foreach($familias->all as $row){
		$y=$row->id;
		$select1[$y]=$row->tag;
	}
}

$select2[0]=$default;
if($subfamilia_productos!= false){
	foreach($subfamilia_productos->all as $row){
		$y=$row->id;
		if($y==0)
			$select2[$y]="TODOS";
		else
			$select2[$y]=$row->tag;
	}
}
//Inputs
$nombre=array(
		'name'=>'nombre',
		'size'=>'35',
		'value'=>'',
);
$familia=0;
$subfamilia=0;
?>
<script>
      $(document).ready(function() {
	      $('#frame1').hide();

		  $('#proveedor').val('');
		  $('#proveedores_drop').autocomplete('<?php echo base_url(); ?>index.php/ajax_pet/get_proveedores_ajax/', {
			  minLength: 3,
			  multiple: false,
			  noCache: true,
			  parse: function(data) {
				  return $.map(eval(data), function(row) {
					  return {
						  data: row,
						  value: row.id,
						  result: row.descripcion
					  }
				  });
			  },
			  formatItem: function(item) {
				  return format(item);
			  }
		  }).result(function(e, item) {
			  $("#proveedor").val(""+item.id);
		  });

		  $('#cmarca_id').val('');
		  $('#marca_drop').autocomplete('<?php echo base_url(); ?>index.php/ajax_pet/get_marcas_ajax/', {
			  extraParams: {pid: function() { return $("#proveedor").val(); } },
			  minLength: 3,
			  multiple: false,
			  noCache: true,
			  parse: function(data) {
				  return $.map(eval(data), function(row) {
					  return {
						  data: row,
						  value: row.id,
						  result: row.descripcion
					  }
				  });
			  },
			  formatItem: function(item) {
				  return format(item);
			  }
		  }).result(function(e, item) {
			  $("#cmarca_id").val(""+item.id);
			  get_proveedor_marca_ajax(item.id);
		  });


	      $('#fil1').click(function(){
		      $('#fil2').hide("slow");
		      $('#fil3').hide("slow");
		      $('#fil4').hide("slow");
		      $('#boton1').removeAttr('disabled');
	      });
	      $('#fil2').click(function(){
		      $('#fil1').hide("slow");
		      $('#fil3').hide("slow");
		      $('#fil4').hide("slow");
		      $('#boton2').removeAttr('disabled');
	      });
	      $('#fil3').click(function(){
		      $('#fil1').hide("slow");
		      $('#fil2').hide("slow");
		      $('#fil4').hide("slow");
		      $('#boton3').removeAttr('disabled');
	      });
	      $('#fil4').click(function(){
		      $('#fil1').hide("slow");
		      $('#fil2').hide("slow");
		      $('#fil3').hide("slow");
		      $('#boton4').removeAttr('disabled');
	      });
	      $('#limpia').click(function(){
		      $('#fil1').show("slow");
		      $('#subform1').each (function(){
			      this.reset();
		      });
		      $('#fil2').show("slow");
		      $('#subform2').each (function(){
			      this.reset();
		      });
		      $('#fil3').show("slow");
		      $('#subform3').each (function(){
			      this.reset();
		      });
		      $('#fil4').show("slow");
		      $('#subform4').each (function(){
			      this.reset();
		      });
		      $('#boton1').attr('disabled','enabled');
		      $('#boton2').attr('disabled','enabled');
		      $('#boton3').attr('disabled','enabled');
		      $('#boton4').attr('disabled','enabled');

	      });

	      $('#boton1').click(function() {
		$('#frame1').show('slow');
	      });

	      $('#boton2').click(function() {
		$('#frame1').show('slow');
	      });

	      $('#boton3').click(function() {
		$('#frame1').show('slow');
	      });

	      $('#boton4').click(function() {
		$('#frame1').show('slow');
	      });


	      $('#familias').change(function(){
		get_subfamilias($(this).val());
	      });
      });

	function get_subfamilias(valor){
		if(valor >= 0) {
			$.post("<? echo base_url(); ?>index.php/ajax_pet/subfamilias",{ enviarvalor: valor},
			function(data){
				$('#subfamilia').html(data);
				$('#subfamilia_productos').focus();
			});
		}
	}
	function format(r) {
		return r.descripcion;
	}

	function get_proveedor_marca_ajax(mid){
		//Obtener los datos via ajax_pet
		if(mid>0){
			$.post("<? echo base_url();?>index.php/ajax_pet/get_proveedor_marca_ajax/", { arg_id1: mid},       //function that is called when server returns a value.
				   function(data1){
					   data=JSON.parse(data1);
					   $('#proveedores_drop').val(data[0].descripcion);
					   $('#proveedor').val(data[0].id);

				   });
		}
		//Procesarlos y escribirlos
	}
</script>

<?php
$this->load->view('ventas/css_alta_act.php');
//Titulo
echo "<br/><h2>$title</h2>";
echo '<div id="filtrado" align="center">';
//Campos del Formulario

$atrib=array('name'=>'subform1', 'id'=>'subform1', 'target'=>"listado");
echo form_open($ruta."/".$ruta.'_c/actualizacion_productos/', $atrib) . "\n";
echo form_hidden('subfuncion', "$subfuncion");
echo '<div id="fil1" align="left">';
echo "<label class='tag_act' for=\"familias\">Familias:</label><br/>";echo form_dropdown('cfamilia_id',$select1, $familia, "id='familias'"); echo "<br/><br/>";
echo "<label class='tag_act' for=\"subfamilia_productos\">Subfamilias:</label><br/><span id='subfamilia'>"; 		echo form_dropdown('csubfamilia_id', $select2, $subfamilia, "id='subfamilia_productos'");echo "</span><br/><br/>";
if(substr(decbin($permisos), 0, 1)==1){
	echo '<button type="submit" id="boton1" >Ver Listado</button>';
}
echo '</div>';
echo form_close();

$atrib=array('name'=>'subform4', 'id'=>'subform4', 'target'=>"listado");
echo form_open($ruta."/".$ruta.'_c/actualizacion_productos/', $atrib) . "\n";
echo form_hidden('subfuncion', "$subfuncion");
echo '<div id="fil4" align="left">';
echo "<label class='tag_act' for=\"nombre\">Palabra(s) Clave de Producto:</label><br/>"; echo form_input($nombre); echo "<br/><br/><br/><br/>";
if(substr(decbin($permisos), 0, 1)==1){
	echo '<button type="submit" id="boton4" >Ver Listado</button>';
}
echo '</div>';
echo form_close();

$atrib=array('name'=>'subform2', 'id'=>'subform2', 'target'=>"listado");
echo form_open($ruta."/".$ruta.'_c/actualizacion_productos/', $atrib) . "\n";
echo form_hidden('subfuncion', "$subfuncion");
echo '<div id="fil2" align="left">';
echo "<label class='tag_act' for=\"proveedor_id\">Proveedor: </label><br/><input type='hidden' name='cproveedores_id' id='proveedor' value='' size=\"3\"><input id='proveedores_drop' class='proveedor' value='' size='60'><br/>";
echo "<label class='tag_act' for=\"marca_id\">Marca: </label><br/><input type='hidden' name='cmarca_id' id='cmarca_id' value='0' size=\"3\"><input id='marca_drop' class='marca' value='' size='60'><br/>";
if(substr(decbin($permisos), 0, 1)==1){
	echo '<button type="submit" id="boton2"  >Ver Listado</button>';
}
echo '</div>';
echo form_close();
echo '</div>';

//Cerrar el Formulario
$img_row="".base_url()."images/table_row.png";
echo "<br/><table width=\"20%\" class='form_table' align='center'>";
echo "<tr><td class=\"form_buttons\"><button type=\"reset\" id=\"limpia\" style=\"display:inline-block;\">Restablecer</button><button type='button' onclick=\"window.location='".base_url()."index.php/inicio/acceso/".$ruta."/menu'\" style=\"display:inline-block;\">Salir al Menú</button>";

echo '</td></tr></table>';

echo form_fieldset_close();
echo form_close();
echo "<table width=\"100%\"><tr align='center'><td><iframe src='' name='listado'  id='frame1' width=\"90%\" height='800'></iframe></td></tr>";
//Link al listado
echo "<tr><td><div align=\"left\"><a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_compras\"><img src=\"".base_url()."images/consumo.png\" width=\"50px\" title=\"Listado de Pedidos de Compra\"></a><a href=\"".base_url()."index.php/".$ruta."/proveedores_c/".$funcion."/alta_proveedor\"><img src=\"".base_url()."images/adduser.png\" width=\"50px\" title=\"Nuevo Usuario\"></a></div></td></tr></table>";
?>
