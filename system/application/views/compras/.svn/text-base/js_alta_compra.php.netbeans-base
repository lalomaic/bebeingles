<script>
  $(document).ready(function() {
	$($.date_input.initialize);
    $('.visible').show();
    $('.invisible').hide();
    $('#subform_detalle').hide();


	  $('#fecha_entrega').focus(function() {
		  get_marcas_select($('#proveedores').val());
	  });

	  //opciones para el formulario principal
	  var options = {
		  target:   '#out_form1',   // target element(s) to be updated with server response
					beforeSubmit:  form_principal,  // pre-submit callback
					success:       mostrar_subform // post-submit callback
	  };
	  //Redirecciones a los contenedores de los estatus de los detalles y envio mediante ajax
	  $('#form1').submit(function() {
		  $(this).ajaxSubmit(options);
		  // always return false to prevent standard browser submit and page navigation
		  return false;
	  });


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

<?php
	$base=base_url();
	for($x=1;$x<=$rows;$x++){
	  $dif=md5(rand());
	  echo <<<END
	  $('#producto_id$x').val('0');
  $("#prod$x").autocomplete('{$base}index.php/ajax_pet/get_productos/{$dif}', {
			extraParams: {pid: function() { return $("#proveedor").val(); }, mid: function() { return $("#cmarca_id").val(); }},
			minLength: 3,
			multiple: false,
			cacheLength:0,
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
			$("#producto_id$x").val(""+item.id);
			get_numeracion(item.id, $x);
	  });
END;

	echo "var options$x = {".
			"target: '#content$x'".
		"};";

  echo     "$('#fdetail$x').submit(function() {".
		   "$(this).ajaxSubmit(options$x);".
		   // always return false to prevent standard browser submit and page navigation
		   "return false;".
		   "});\n";

//Cantidad
		echo "$('.cant$x').keyup(function() { \n".
		"if($(this).val()!=''){ \n".
		"sumar_cantidades($x);\n".
		"} \n });";

			echo     "$('#cantidad$x').blur(function() {\n".
					"if($(this).val()>0){ \n ".
					"calc($x);\n".
		 // 	  "$('#subtotal$x').format({format:\"#,###.0000\", locale:\"us\"});".
					"$('#row".($x+1)."').show(''); \n".
					"$('#row".($x+1)."').removeClass('invisible').addClass('visible'); \n ".
		  "} }); \n";

		  //Precio
		  echo     "$('#precio_u$x').blur(function() {\n".
				   "valor=$('#producto_id$x').val(); \n".
				   "otra_accion(valor, $x) \n".
				   "$(this).format({format:\"#,###.00\", locale:\"us\"});".
				   "});\n";

		  echo     "$('#precio_u$x').focus(function() {\n".
				   "valor=$('#producto_id$x').val(); \n".
				   "otra_accion1(valor, $x) \n".
				   "$('#detalle_num$x').show(); \n".
				   "$(this).format({format:\"#,###.00\", locale:\"us\"});".
				   "});\n";

		  //IVA
		  echo     "$('#tasa_imp$x').blur(function() {\n".
				   "calc($x);\n".
				   "$('#numeracion${x}_1').focus()".
				   "});\n";
		  //Cantidad
		  echo     "$('#subtotal$x').blur(function() {\n".
				   "calc($x);\n".
				   "$('#row".($x+1)."').show(''); \n".
					"$('#row".($x+1)."').removeClass('invisible').addClass('visible'); \n ".
				   "});\n";
		 //Ocultar Detalles de numeracion
		 echo "$('#detalle_num$x').hide(); \n";
	}
?>
	  function format(r) {
		  return r.descripcion;
	  }
});

function get_iva(valor, linea) {
	if(valor > 0) {
		$.post("<? echo base_url(); ?>index.php/ajax_pet/iva",{ enviarvalor: valor, linea: linea },
		function(data){
			$('#tasa_i'+linea).html(data);
			$('#tasa_imp'+linea).focus();
			$('#cantidad'+(linea+1)).focus();
			calc(linea);
	/*      $('#subtotal'+linea).format({format:"#,###.0000", locale:"us"});*/
		});
	}
}

function send_detalle(){
    document.getElementById('boton1').click();
	for(var y=1;y<= <? echo $rows; ?>;y++){
		var id=parseFloat(eval("document.fdetail"+y+".pr_pedidos_id"+y+".value"));
		var subtotal=parseFloat(eval("document.fdetail"+y+".subtotal"+y+".value"));
		if(id > 0 && subtotal>0){
			document.getElementById('b'+y).click();
		}
	}
	document.getElementById("fin").innerHTML='<button type="button" onclick="javascript:verificar()"/>Finalizar</button>';
}

function get_marcas_select(valor){
	if(valor>0){
		$("#cmarcas_id").removeOption(/./);
		$("#cmarcas_id").ajaxAddOption("<? echo base_url();?>index.php/ajax_pet/get_marcas_proveedor/"+valor);
	}
}
function borrar_detalle(line){
    // post(file, data, callback, type); (only "file" is required)
    pr_id=eval("document.fdetail"+line+".id"+line+".value");
//    alert(''+cl_id);
    if(pr_id>0){
      $.post("<? echo base_url();?>index.php/ajax_pet/baja_detalle/caso_3", { arg_id1: pr_id, linea: line, arg_id2: 0},       //function that is called when server returns a value.
      function(data){
	  $('#content'+line).html(data);
	  $('#cantidad'+line).val('0');
      });
   }
}

function verificar(){
  var id=eval("document.form1.id.value");
  location.href="<? echo base_url();?>index.php/<? echo $ruta; ?>/trans/verificar_pedido_compra_multiple/"+id;
}

  function mostrar_subform(){
  $('#subform_detalle').show()
  $('.visible').show()
  $('.invisible').hide()
}

  function act_parent_id(obj, line){
    //Definir el text input a actualiar
    eval(obj+".pr_pedidos_id"+line+".value=document.form1.id.value");
  }
  function otra_accion(valor, linea){
	  //Vacio
	  if(valor==0){
		$('#producto_id'+linea).val('0');
		$('#precio_u'+linea).val('0');
	  }
	  if($('#prod').val()==''){
		  $('#producto_id'+linea).val('0');
		  $('#precio_u'+linea).val('0');
	}
  }

  function otra_accion1(valor, linea){
  }

  function mostrar(element){
// 	  alert(element);
	  $('#'+element).show();
  }
  function ocultar(element){
	  $('#'+element).hide();
  }

  function get_numeracion(pid, line){
	 //Obtener los datos via ajax_pet
	 if(pid>0){
		$.post("<? echo base_url();?>index.php/ajax_pet/get_numeracion/", { arg_id1: pid, linea: line},       //function that is called when server returns a value.
		function(data1){
			data=JSON.parse(data1);
			rows=data.rows[0].valor;
			for(x=0;x<rows;x++){
				//alert(x);
					$('#l'+line+'_'+(x+1)).html(eval("data.datos["+x+"].descripcion"));
					$('#cproducto_numero_id'+line+'_'+(x+1)).val(eval("data.datos["+x+"].id"));
					//$('#cantidad'+line).val('0');
			}
			for(x=x+1;x<=20;x++){
				$('#l'+line+'_'+(x)).hide();
				$('#numeracion'+line+'_'+(x)).hide();
			}
                        $("#precio_u"+line).val(data.precio);
                        $("#cantidad"+line).val(1);
                        $("#numeracion"+line+"_1").val(1);
                        $("#fecha_compr"+line).val(data.fecha_compr);
                        calc(line);
                        mostrar("detalle_num"+line);
		});
	}
	 //Procesarlos y escribirlos
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

	//Funcion para sumar las cantidades de cada numero
	function sumar_cantidades(linea){
		$('#cantidad'+linea).val(0); sumt=0;
		sum2=parseFloat($("#cantidad"+linea).val());
		for(x=1;x<21;x++){
			sum1=parseFloat($('#numeracion'+linea+'_'+x).val());
			sumt=sumt+sum1;
		}
		$("#cantidad"+linea).val(sumt);
		calc(linea);
	}

	function marcar(){
		$(':checkbox').attr('checked', true);
	}
	function desmarcar(){
		$(':checkbox').attr('checked', false);
	}

	function sin_iva(){
		$('.subtotal_iva').val('0');
	}
	function con_iva(){
		$('.subtotal_iva').val('16');
	}

</script>

