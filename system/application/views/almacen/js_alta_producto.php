<script>
  $(document).ready(function() {
	$('#subform_detalle').hide();
//	$('.dat_telefonos').hide();
     $('.visible').show();
     $('.invisible').hide();
	 
	var options = {
		target:   '#out_form1',   // target
		success:  mostrar_subform // post-submit callback
	  };
	  $('#form1').submit(function() {
		  $(this).ajaxSubmit(options);
		  return false;
	  });
	 
	 //Cantidad

       
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
//codigo barras
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
	//Iva
        /*  echo  "$('#iva$x').blur(function() {\n".
                  "u=parseFloat($('#cantidad$x').val()); \n".
		   "$('#subtotal$x').val(''+u*parseFloat($(this).val()))\n".
           "sumar_cantidades();\n".
	   "});\n";*/
        //Sumar Cunando se cambia cantidad
          echo  "$('#cantidad$x').blur(function() {\n".
               "var iva=$('#precio_u$x').val()*$('#cantidad$x').val()*$('#iva$x').val()/100\n".
                      "var to=$('#precio_u$x').val()*$('#cantidad$x').val()\n". 
                     "$('#subtotal$x').val($('#precio_u$x').val()*$('#cantidad$x').val())\n".
			 "$('#precio_iva$x').val(iva+to)\n".
                     "sumar_cantidades();\n".
           "sumar_cantidades();\n".
	   "});\n";
//
	  echo  "$('#prod$x').blur(function() {\n".
				"$('#row".($x+1)."').show(''); \n".
				"$('#row".($x+1)."').removeClass('invisible').addClass('visible'); \n ".
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
		producto_relacion=$('#producto_id'+linea).val();
		producto_relacion_num=$('#producto_nid'+linea).val();
		 semejanza=$('#semejanza'+linea).val();
		producto_combo=document.form1.id.value;
		if(producto_relacion>0 && producto_combo>0 && semejanza>0){
			$.post("<? echo base_url();?>index.php/almacen/trans/alta_pro_combo", { 
				cproducto_id_combo: producto_combo,
				cproducto_numeracion_id_relacion:producto_relacion_num,
				cproducto_id_relacion: producto_relacion,
				semejanza:semejanza,
				id: $('#id').val()
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
    for(var y=0;y< 10 ;y++){
        if($('#producto_id'+y).val()>0)
        enviar_renglon(y);
    }
    document.getElementById("fin").innerHTML='<button type="button" onclick="javascript:verificar()"/>Finalizar</button>';
    $('#msg1').html("");
}

function verificar(){
  var id=eval("document.form1.id_prod.value");
  location.href="<? echo base_url();?>index.php/<? echo $ruta; ?>/trans/verificar_relacion_combo/"+id;
}

function mostrar_subform(){
	$('#subform_detalle').show();
	alert("Relaciones los Productos");
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

  function get_codbar(producto_id, linea){
	$.post("<? echo base_url();?>index.php/ajax_pet/get_codbar_by_producto",{ enviarvalor: producto_id},  // create an object will all values
    //function that is called when server returns a value.
    function(data){
        $('#codigo_barras'+linea).val(''+data);
    });
}

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
		$('#subtotal').val(''+sum);
		$('#iva_total').val(''+sum_iva);
		$('#total').val(''+(sum+sum_iva));
	}
</script>
