<script>
    $(document).ready(function() {
    


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
		$('#cantidad_total').val(sum_cant);
		$('#total').val(''+sum);
		$('#iva_total').val(''+sum_iva);
		$('#subtotal').val(''+(sum-sum_iva));
	}
	<?php
	$base=base_url();
	for($x=0;$x<=$rows;$x++){
	  $dif=md5(rand());
	  echo <<<END
	  $('#producto_nid$x').val('0');
	  $('#producto_id$x').val('0');
	  $("#prod$x").autocomplete('{$base}index.php/ajax_pet/get_productos_combo/{$dif}', {
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
	  echo  "$('#prod$x').blur(function() {\n".
				"$('#row".($x+1)."').show(''); \n".
				"$('#row".($x+1)."').removeClass('invisible').addClass('visible'); \n ".
                                 //"enviar_renglon($x);\n".
				
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
                familia_id=$('#familias').val();
                subfamilia_id=$('#csubfamilia_id').val();
                cmarca_id=$('#marca_productos').val();
                color_id=$('#color').val();
                descripcion=$('#descripcion').val();
                precio1=$('#precio1').val();
                precio2=$('#precio2').val();
                precio3=$('#precio3').val();
                semejanza=$('#semejanza'+linea).val();
                tasa=$('#tasa_impuesto').val();
                
                observaciones=$('#observaciones').val();   
// 		alert("Guardando .."+$('#precio_u'+linea).val());
		combo_id=document.form1.id.value;
                combo_numeracion=$('#id_numeracion').val();
		if(combo_id>0 && producto>0 && semejanza>0){
			$.post("<? echo base_url();?>index.php/almacen/trans/act_pro_combo", { 
                                descripcion:descripcion,
                                cmarca_producto_id:cmarca_id,
                                cfamilia_id:familia_id,
                                csubfamilia_id:subfamilia_id,
                                precio1:precio1,
                                precio2:precio2,
                                precio3:precio3,
                                observaciones:observaciones,
                                ccolor_id:color_id,
                                tasa_impuesto:tasa,
                                cproducto_id_combo:combo_id,
                                cproducto_numeracion_id_combo:combo_numeracion,
                                cproducto_id_relacion:producto,
                                cproducto_numeracion_id_relacion:producto_num,
                                id_c: combo_id,
                                id:$('#id'+linea).val(),
                                semejanza:semejanza
                               
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


function enviar_producto(){
		//Obtener campos clave producto_id y cantidad
                familia_id=$('#familias').val();
                subfamilia_id=$('#csubfamilia_id').val();
                cmarca_id=$('#marca_productos').val();
                color_id=$('#color').val();
                descripcion=$('#descripcion').val();
                precio1=$('#precio1').val();
                precio2=$('#precio2').val();
                precio3=$('#precio3').val();
	
	        
		tasa=$('#tasa_impuesto').val();
                observaciones=$('#observaciones').val();   
		combo_id=document.form1.id.value;
                combo_numeracion=$('#id_numeracion').val();
		if(combo_id>0){
			$.post("<? echo base_url();?>index.php/almacen/trans/act_pro_combo1", { 
                                descripcion:descripcion,
                                cmarca_producto_id:cmarca_id,
                                cfamilia_id:familia_id,
                                csubfamilia_id:subfamilia_id,
                                precio1:precio1,
                                precio2:precio2,
                                precio3:precio3,
                                observaciones:observaciones,
                                ccolor_id:color_id,
                                tasa_impuesto:tasa,
				//talla:talla,
				//codigo_barra:codigo_barra,
				//gen_cod_bar:gen_cod_bar,
                                id: combo_id
                               
			}, 
				function(data){
					if(data>0){
						alert("Se Modificado los datos del Producto");
					//	$('#id'+linea).val(data);
					}
			});
		} else{
			//$('#content'+linea).html('');
			alert("Ocurrio un error al intentar guardar los detalles");
		}
	}



function enviar_semejanza(linea){
		//$('#content'+linea).html("<img src='<? echo base_url(); ?>images/waiting.gif' width='20px'/>");
		id=$('#id'+linea).val();
		semejanza=$('#semejanza'+linea).val(); 
		if(id>0){
			$.post("<? echo base_url();?>index.php/almacen/trans/editar_semejanza", { 
				id: id,
				semejanza: semejanza
           
			}, 
				function(data){
					if(data>0){
						$('#content'+linea).html('<img src="<?=base_url()?>images/cancelado.png" width="20px" title="Guardado">');
					//$('#id'+linea).val(data);
					}
			});
		} else{
			$('#content'+linea).html('');
			alert("Ocurrio un error al intentar guardar los detalles");
		}
	}



function enviar_inventario(linea){
		//Obtener campos clave producto_id y cantidad
		$('#content'+linea).html("<img src='<? echo base_url(); ?>images/waiting.gif' width='20px'/>");
		id_relacion=$('#id_producto'+linea).val();
		combo_numeracion=$('#combo_numeracion').val();
		numeracion_relacion=$('#producto_m'+linea).val(); 
// 		alert("Guardando .."+$('#precio_u'+linea).val());
		pr_fid=document.form1.id.value;
		if(pr_fid>0){
			$.post("<? echo base_url();?>index.php/almacen/trans/editar_producto_combo", { 
				cproducto_id_relacion: id_relacion,
				cproducto_numeracion_id_relacion: numeracion_relacion,
				combo_numeracion:combo_numeracion,
       id_combo: pr_fid
                               
			}, 
				function(data){
					if(data>0){
						$('#content'+linea).html('<img src="<?=base_url()?>images/cancelado.png" width="20px" title="Guardado">');
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
    for(var y=0;y< 10 ;y++){
        if($('#producto_id'+y).val()>0){
        enviar_renglon(y);
        //enviar_producto();
     }
    }
    $('#boton_p').hide();
    document.getElementById("fin").innerHTML='<button type="button" onclick="javascript:verificar()"/>Finalizar</button>';
    $('#msg1').html("");
}


function guardar_semejanza(){
for(var y=0;y< 10 ;y++){
        if($('#id_producto'+y).val()>0){
        enviar_semejanza(y);
        //enviar_producto();
     }
    }	
	
	
}


function send_inventario(){
	$('#msg1').html("<br/><img src='<? echo base_url(); ?>images/cargando.gif' width='50px'/><br/>Cargando...");
		if($('#id_numeracion').val()>0){
			enviar_inventario(y);
                }
	
}



function verificar(){
  	combo_id=document.form1.id.value;
  location.href="<? echo base_url();?>index.php/<? echo $ruta; ?>/trans/verificar_act_pro_combo/"+combo_id;
}


</script>
