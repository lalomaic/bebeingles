<script>
	var tipos_factura;
  $(document).ready(function() {
	get_tipos_factura();	
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
	$('.unidades').keyup(function() { 
		if($(this).val()!=''){ 
			sumar_cantidades();
		} 
	});
       
        $('#proveedores').next().blur(function (){
            $.post("<? echo base_url();?>index.php/almacen/trans/dias_de_credito", { 
                proveedor : $('#proveedores').val()}, 
                function(data){
                    if(data == "")
                        return;
                    var fecha =$("#fecha_ingreso").val().split(" ");
                    $("#dias_credito").text(data);
                    dcredito = new Date ();
                    dias = fecha[0];
                    dcredito.setMonth(fecha[1]);
                    dcredito.setYear(fecha[2]);
                    dcredito.setDate(parseInt(dias) + parseInt(data));
                    $("#fecha_pago").val(dcredito.getDate()+ " " + dcredito.getMonth() + " " + dcredito.getFullYear());
                    
		});
        });
        $('#fecha_ingreso').change(function(){
            var fecha =$("#fecha_ingreso").val().split(" ");
            dias1 =parseInt($("#dias_credito").text());
            dcredito = new Date ();
            dias = fecha[0];
            dcredito.setMonth(fecha[1]);
            dcredito.setYear(fecha[2]);
            dcredito.setDate(parseInt(dias) + parseInt(dias1));
            $("#fecha_pago").val(dcredito.getDate()+ " " + dcredito.getMonth() + " " + dcredito.getFullYear());            
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
		   "$('#row".($x+1)."').show(''); \n".
				"$('#row".($x+1)."').removeClass('invisible').addClass('visible'); \n ".
				"$('#cod_bar".($x+1)."').focus(); \n".
           "sumar_cantidades();\n".
           "enviar_renglon($x);\n".
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
	  	//Subtotal
	  echo  "$('#subtotal$x').blur(function() {\n".
				"$('#row".($x+1)."').show(''); \n".
				"$('#row".($x+1)."').removeClass('invisible').addClass('visible'); \n ".
				"$('#cod_bar".($x+1)."').focus(); \n".
				"enviar_renglon($x);\n".
			"});\n";
			
			 echo  "$('#iva$x').blur(function() {\n".
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
			var iva;
			var tipo_factura = parseInt($("#ctipo_factura_id").val());
			for(i=0;i<tipos_facturas.length;i++){
				if(tipos_facturas[i].id == tipo_factura){
					iva = parseFloat(tipos_facturas[i].impuesto);					
				}
			}
			$.post("<? echo base_url();?>index.php/almacen/trans/alta_entrada", { 
				cproductos_id: producto,
				cproducto_numero_id: producto_num,
				cantidad: cantidad_js,
				costo_unitario: precio_u,
				tasa_impuesto: iva,
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
	
function send_detalle(){
	$('#msg1').html("<br/><img src='<? echo base_url(); ?>images/cargando.gif' width='50px'/><br/>Cargando...");
	for(var y=0;y< <? echo $rows; ?>;y++){
		if($('#cantidad'+y).val()>0)
			enviar_renglon(y);
	}
	document.getElementById("fin").innerHTML='<button type="button" onclick="javascript:verificar()"/>Finalizar</button>';
	$('#msg1').html("");
}

function verificar(){
  var id=eval("document.form1.id.value");
  location.href="<? echo base_url();?>index.php/<? echo $ruta; ?>/trans/verificar_entrada/"+id;
}

function mostrar_subform(){
	$('#subform_detalle').show();
	alert("Capture los detalles de la factura");
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
				var tipo_factura = parseInt($("#ctipo_factura_id").val());
				for(i=0;i<tipos_facturas.length;i++){
					if(tipos_facturas[i].id == tipo_factura){
					sum_iva = parseFloat(tipos_facturas[i].impuesto);					
					}
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
		var sum = round_two_decimal(sum);
		$("#subtotal_sin_descuento").val(sum);
		var descuento = round_two_decimal(descuento_label);
		$('#descuento_lbl').val(descuento);
		var cantidad = round_two_decimal(sum_cant);
		$('#cantidad_total').val(cantidad);
		var subtotal = round_two_decimal(sum - descuento_label);
		$('#subtotal').val(subtotal);
		var iva_total = round_two_decimal((sum - descuento_label) * sum_iva/100);
		$('#iva_total').val(iva_total);
		var total = round_two_decimal((sum - descuento_label) * (100 + sum_iva) /100);
		$('#total').val(total);
	}
	
	function round_two_decimal(num){
		return Math.round(num * 100)/100;	
	}

    	function get_tipos_factura(){
		$.ajax({
			url : "<?= base_url() ?>index.php/almacen/trans/get_tipos_facturas",
			type: "POST",
			dataType : "json",
			success : function(data){
				tipos_facturas = data;			
			},
			error: function(data){
			alert(data);
			}
		});
	}


function cancelar_entrada() {
		$.ajax({
			url : "<?=base_url()?>index.php/almacen/trans/cancelar_alta_pr_factura",
			type: "POST",
			dataType : "json",
			data: { "id" : $("#id").val() },
			success : function(data){
				window.location = "<?=base_url()?>index.php/inicio/acceso/<?=$ruta?>/menu";			
			},
			error: function(data){
			alert(data);
			}
		});
	
}
</script>
