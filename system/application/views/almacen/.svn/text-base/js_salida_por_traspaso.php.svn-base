<script>
  $(document).ready(function() {
     $('.visible').show();
     $('.invisible').hide();
	 
	 
	 //Cantidad
	$('.unidades').keyup(function() { 
		if($(this).val()!=''){ 
			sumar_cantidades();
		} 
	});
<?php
	$base=base_url();
	for($x=0;$x<=$rows;$x++){
	  $dif=md5(rand());
	  echo <<<END
	  $('#producto_nid$x').val('0');
	  $('#producto_id$x').val('0');
	  $("#prod$x").autocomplete('{$base}index.php/ajax_pet/get_productos_numeracion/{$dif}', {
			extraParams: {pid: 0, mid: 0},
			minLength: 3,
			multiple: false,
			cacheLength:0,
			noCache: true,
			parse: function(data) {
				return $.map(eval(data), function(row) {
					return {
						data: row,
						value: row.id,
						value1: row.nid,
						result: row.descripcion
					}
				});
			},
			formatItem: function(item) {
				return format(item);
			}
	  }).result(function(e, item) {
			$("#producto_id$x").val(""+item.id);
			$("#producto_nid$x").val(""+item.nid);
			get_precio_compra($x, item.id);
	  });
END;
	//Precio
	  echo  "$('#precio_u$x').blur(function() {\n".
		   "u=parseFloat($('#cantidad$x').val()); \n".
		   "$('#subtotal$x').val(''+u*parseFloat($(this).val()))\n".
           "sumar_cantidades();\n".
	   "});\n";
	//Iva
	  echo  "$('#iva$x').blur(function() {\n".
           "sumar_cantidades();\n".
	   "});\n";

	  	//Subtotal
	  echo  "$('#subtotal$x').blur(function() {\n".
				"$('#row".($x+1)."').show(''); \n".
				"$('#row".($x+1)."').removeClass('invisible').addClass('visible'); \n ".
				"$('#prod".($x+1)."').focus(); \n".
				"enviar_renglon($x);\n".
			"});\n";
  }
?>
   });
 
	function format(r) {
		  return r.descripcion;
	}
	
	function enviar_renglon(linea){
		//Obtener campos clave producto_id y cantidad
		$('#content'+linea).html("<img src='<? echo base_url(); ?>images/waiting.gif' width='20px'/>");
		producto=$('#producto_id'+linea).val();
		producto_num=$('#producto_nid'+linea).val();
		cantidad_js=$('#cantidad'+linea).val();
		tipo_salida=2; 
		precio_u=$('#precio_u'+linea).val();
// 		alert("Guardando .."+$('#precio_u'+linea).val());
		if(producto>0 && producto_num>0 && cantidad_js>0){
			$.post("<? echo base_url();?>index.php/almacen/trans/salida_por_traspaso", { 
				cproductos_id: producto,
				cproducto_numero_id: producto_num,
				cantidad: cantidad_js,
				costo_unitario: precio_u,
				tasa_impuesto: $('#iva'+linea).val(),
				cl_facturas_id: 0,
				id: $('#id'+linea).val(),
				ctipo_salida_id: tipo_salida
			}, 
				function(data){
					if(data>0){
						$('#content'+linea).html('<img src="<?=base_url()?>images/ok.png" width="20px" title="Guardado">');
						$('#id'+linea).val(data);
					}
			});
		} else{
			$('#content'+linea).html('');
			alert("Ocurrio un error al intentar guardar los detalles");
		}
	}
	
function send_detalle(){
	$('#msg1').html("<br/><img src='<? echo base_url(); ?>images/cargando.gif' width='50px'/><br/>Cargando...");
	for(var y=0;y< <? echo $rows; ?>;y++){
		if($('#cantidad'+y).val()>0)
			enviar_renglon(y);
	}
	document.getElementById("fin").innerHTML='<button type="button" onclick="javascript:verificar()"/>Finalizar</button>';
	$('#msg1').html("");
}


  function get_cfamilia(producto_id, linea){
	$.post("<? echo base_url();?>index.php/ajax_pet/get_cfamilia_by_producto",{ enviarvalor: producto_id}, 
    function(data){
		if(data==1){
			$('#dat_telefonos'+linea).show();
			$('#cantidad'+linea).val('1');
			$('#cantidad'+linea).attr('readonly', 'readonly');
			$('#imey'+linea).focus();
		}
    });
}

  

 function get_precio_compra(linea, producto_id){
	$.post("<? echo base_url();?>index.php/ajax_pet/get_precio_compra_indirecto",{ enviarvalor: producto_id},  // create an object will all values
    function(data){
        $('#precio_u'+linea).val(''+data);
    });
}

	function sumar_cantidades(){
		sum=0; sum_iva=0; sum_cant=0;
		$('#total').val('x');
		for(x=0;x<=<?=$rows?>;x++){
			if($('#subtotal'+x).val()>0){
				sum= parseFloat(sum)+parseFloat($('#subtotal'+x).val());
				if($('#iva'+x).val()>0){
					sum_iva= sum_iva + $('#subtotal'+x).val()*$('#iva'+x).val()/100;
				}
				if($('#cantidad'+x).val()>0){
					sum_cant= sum_cant + parseFloat($('#cantidad'+x).val());
				}
			}
		}
		$('#cantidad_total').val(sum_cant);
		$('#total').val(''+sum);
		$('#iva_total').val(''+sum_iva);
		$('#subtotal').val(''+(sum-sum_iva));
	}
	
function verificar(){
	location.href="<? echo base_url();?>index.php/inicio/acceso/almacen/menu";
}
</script>
