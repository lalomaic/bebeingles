<script>
    $(document).ready(function() {
    $($.date_input.initialize);
    $('#proveedores').select_autocomplete();


function get_producto_by_cod_bar(cod_bar, linea){
    $.post("<? echo base_url();?>index.php/ajax_pet/get_producto_by_cod_bar",{ enviarvalor: cod_bar},
    function(data){
        var producto = eval("("+data+")");
        $('#precio_u'+linea).val(producto.precio);
        $('#cantidad'+linea).val(producto.cantidad);
        $('#subtotal'+linea).val(parseInt(producto.precio));
        $('#prod'+linea).val(producto.descripcion);
        $("#producto_id"+linea).val(producto.id);
	$("#producto_nid"+linea).val(producto.nid);
        sumar_cantidades();
    });
}
function get_precio_compra(linea, producto_id){
	$.post("<? echo base_url();?>index.php/ajax_pet/get_producto_detalles",{ enviarvalor: producto_id, prod:$("#prod"+linea).val()},  // create an object will all values
    function(data){
        p=eval("("+data+")");
        $('#precio_u'+linea).val(p.precio);
        $('#cantidad'+linea).val(1);
        $('#subtotal'+linea).val(parseInt(p.precio));
        $('#cod_bar'+linea).val(p.cod_bar);
        sumar_cantidades();
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
		var descuento = parseFloat($('#descuento').val());
		// Tipo de descuento [0] = porcentaje, [1] = pesos
		var tipo = $('input[name="tipo_descuento"]')[0];
		if($(tipo).is(':checked')){
			var descuento_label = (sum * descuento)/100; 
		} else {
			var descuento_label = descuento; 
		}
		
		$('#descuento_lbl').val(descuento_label);
		
		$('#cantidad_total').val(sum_cant);
		$('#subtotal').val(sum - descuento_label);
		$('#iva_total').val(''+sum_iva);
		$('#total').val(''+(sum+sum_iva));
	}
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
          
            echo "$('#cod_bar$x').keypress(function(e) { \n".
        "if(e.keyCode == 13) {\n".
                    "$('#cantidad$x').focus();\n".
            "get_producto_by_cod_bar($(this).val(),$x);\n".
            "return false;".
        "}\n".
    "}); \n";
          
         
	//Precio
	  echo  "$('#precio_u$x').blur(function() {\n".
		   "u=parseFloat($('#cantidad$x').val()); \n".
		   "$('#subtotal$x').val(''+u*parseFloat($(this).val()))\n".
        "sumar_cantidades();\n".
"$('#row".($x+1)."').show(''); \n".
				"$('#row".($x+1)."').removeClass('invisible').addClass('visible'); \n ".
				"$('#cod_bar".($x+1)."').focus(); \n".
                                 "enviar_renglon($x);\n".
	   "});\n";
	  
	  

	  	  
	  
	  
	   
	   
          
          
          echo  "$('#cantidad$x').blur(function() {\n".
                   "$('#subtotal$x').val($('#precio_u$x').val()*$('#cantidad$x').val())\n".
                  
           "sumar_cantidades();\n".
	   "});\n";
          echo  "$('#$x').blur(function() {\n".
				"$('#row".($x+1)."').show(''); \n".
				"$('#row".($x+1)."').removeClass('invisible').addClass('visible'); \n ".
				"$('#prod".($x+1)."').focus(); \n".
				
			"});\n";
	  	//Subtotal
	  echo  "$('#subtotal$x').blur(function() {\n".
				"$('#row".($x+1)."').show(''); \n".
				"$('#row".($x+1)."').removeClass('invisible').addClass('visible'); \n ".
				"$('#cod_bar".($x+1)."').focus(); \n".
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
		tipo_entrada=<?=$tipo_entrada?>; 
                estatus_general_id=<?=$estatus_general_id?>; 
		precio_u=$('#precio_u'+linea).val();
// 		alert("Guardando .."+$('#precio_u'+linea).val());
		pr_fid=document.form1.id.value;
		if(producto>0 && producto_num>0 && cantidad_js>0 && pr_fid>0){
			$.post("<? echo base_url();?>index.php/almacen/trans/alta_entrada", { 
				cproductos_id: producto,
				cproducto_numero_id: producto_num,
				cantidad: cantidad_js,
				costo_unitario: precio_u,
				tasa_impuesto: $('#iva'+linea).val(),
				pr_facturas_id: pr_fid,
				id: $('#id'+linea).val(),
				ctipo_entrada: tipo_entrada,
                                   estatus_general_id: estatus_general_id
				//costo_total:cantidad_js *precio_u
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


function borrar(linea,linea2){
		//Obtener campos clave producto_id y cantidad
		$('#content'+linea+linea2).html("<img src='<? echo base_url(); ?>images/waiting.gif' width='20px'/>");
		producto=$('#id_producto'+linea).val();
                espacio_fisico=$('#espacio_fisico').val();
                provedor=$('#provedor').val();
                f_id=$('#f_id').val();
		producto_num=$('#producto_m'+linea2).val();
		tipo_entrada=<?=$tipo_entrada?>; 
                estatus_general_id=2; 
		precio_u=$('#precio_u'+linea).val();
// 		alert("Guardando .."+$('#precio_u'+linea).val());
		pr_fid=document.form1.id.value;
		if(producto>0){
			$.post("<? echo base_url();?>index.php/almacen/trans/editar_entrada_compra", { 
				cproductos_id: producto,
				cproducto_numero_id: producto_num,
				costo_unitario: precio_u,
				tasa_impuesto: $('#iva'+linea).val(),
				pr_facturas_id: pr_fid,
                                id: $('#id'+linea).val(),
				ctipo_entrada: tipo_entrada,
                                estatus_general_id: estatus_general_id,
                                espacios_fisicos_id:espacio_fisico,
                                cproveedores_id:provedor
                          
			}, 
				function(data){
					if(data>0){
						$('#content'+linea+linea2).html('<img src="<?=base_url()?>images/cancelado.png" width="20px" title="Guardado">');
						$('#id'+linea).val(data);
					}
			});
		} else{
			$('#content'+linea).html('');
			alert("Ocurrio un error al intentar guardar los detalles");
		}
	}
	
function editar_inventario(linea,linea2){
		//Obtener campos clave producto_id y cantidad
		$('#content'+linea+linea2).html("<img src='<? echo base_url(); ?>images/waiting.gif' width='20px'/>");
		producto=$('#id_producto'+linea).val();
    
      f_id=$('#f_id').val();
		producto_num=$('#producto_m'+linea2).val();
		tipo_entrada=<?=$tipo_entrada?>; 
                estatus_general_id=2; 
		precio_u=$('#precio_uni'+linea2).val();
		cantidad=$('#cantidad'+linea2).val();
// 		alert("Guardando .."+$('#precio_u'+linea).val());
		pr_fid=document.form1.id.value;
		if(producto>0){
			$.post("<? echo base_url();?>index.php/almacen/trans/editar_precio_inventario_inicial", { 
				cproductos_id: producto,
				cproducto_numero_id: producto_num,
				costo_unitario: precio_u,
				tasa_impuesto: $('#iva'+linea).val(),
				pr_facturas_id: f_id,
      id: $('#id'+linea).val(),
      cantidad: cantidad,
				ctipo_entrada: tipo_entrada,
      estatus_general_id: estatus_general_id,
      espacios_fisicos_id:espacio_fisico,
      cproveedores_id:provedor
                          
			}, 
				function(data){
					if(data>0){
						$('#content'+linea+linea2).html('<img src="<?=base_url()?>images/stock.png" width="20px" title="Guardado">');
						$('#id'+linea).val(data);
						//document.form.precio_uni.disabled=true;
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

function send_inventario(){
	$('#msg1').html("<br/><img src='<? echo base_url(); ?>images/cargando.gif' width='50px'/><br/>Cargando...");
	for(var y=0;y< 10000 ;y++){
		if($('#producto_m'+y).val()>0)
			borrar(y);
	}
}



function verificar(){
		pr_fid=document.form1.id.value;
		folio=$('#folio_factura').val();
		fecha=$('#fecha').val();
		fecha_pago=$('#fecha_pago').val();
		fecha_ingreso=$('#fecha_ingreso').val();
		monto=$('#monto_total').val();
		descuento=$('#descuento').val();
		vigencia=$('#vigencia_descuento').val();
		tipo_factura=$('#ctipo_factura_id').val();
		$.post("<? echo base_url();?>index.php/almacen/trans/act_veri_entrada", { 
                        id: pr_fid,
                        folio_factura: folio,
                        fecha:fecha,
                        fecha_pago: fecha_pago,
                        fecha_ingreso:fecha_ingreso,
                        monto_total: monto,
                        porcentaje_descuento: descuento,
                        vigencia_descuento:vigencia,
                        ctipo_factura_id	:tipo_factura
			}, 
                        
				function(data){
					if((data>0)){
						alert("Datos Guardados Correctamente")
window.location='<? echo base_url(); ?>index.php/almacen/almacen_c/formulario/list_entradas'
//$('#msg1').html("<br/><img src='<? echo base_url(); ?>images/cargando.gif' width='50px'/><br/>Cargando...");
   }
			});

}


</script>