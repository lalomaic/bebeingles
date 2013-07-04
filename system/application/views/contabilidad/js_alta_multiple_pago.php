<script type="text/javascript">
  $(document).ready(function() {
  $('#proveedor').select_autocomplete();
  $($.date_input.initialize);
  $('#subform_detalle').hide();
  $('#subform_devoluciones').hide();
  $('.visible').show();
  $('.invisible').hide();


  $('#fecha').blur(function() {
	  
// 		get_facturas_select($('#proveedor').val());
// 	  	get_pagos_multiples_devoluciones($('#proveedor').val());
get_cuentas_prov($('#proveedor').val());

  });

<?php
	for ($x=1;$x<=$rows;$x++){
            	
            echo "var options$x = {".
			"target: '#content$x'".
		"};";

		echo "$('#fdetail$x').submit(function() {".
			"$(this).ajaxSubmit(options$x);".
			"return false;".
		"});\n";

	  echo "$('#pr_factura_id$x').blur(function() { \n".
			"$('#detalles_pagos$x').html(\"Cargando pagos relacionados con la factura\"); \n".
			"get_pagos_factura('#detalles_pagos$x',$(this).val()) \n".
		"}); \n";
	  echo     "$('#monto_pagado$x').blur(function() {\n".
					"sumar_montos('#detalles_pagos$x',$(this).val(),$('#pr_factura_id$x').val()) \n".
		"});\n";
	           echo     "$('#monto_pagado$x').blur(function() {\n".
					"abrir_renglon($x) \n".
                           
		"});\n";
      
          
           
  }
?>
});


function get_pagos_factura(renglon, id){
    // post(file, data, callback, type); (only "file" is required)
    if(id>0){
		$.post("<? echo base_url();?>index.php/ajax_pet/pagos_factura_multiple",{ enviarvalor : id },  // create an object will all values
		//function that is called when server returns a value.
			function(data){
				$(renglon).html(data);
			}
		);
   } else {
      $('#fact').html("Ocurrio un problema con la conexión con el servidor, vuelva a intentar");
   }
}


function get_pagos_multiples_devoluciones(id){
    // post(file, data, callback, type); (only "file" is required)
    fecha_pago=$("#fecha").val();
    if(id>0){
		$.post("<? echo base_url();?>index.php/ajax_pet/pagos_multiples_devoluciones",{ enviarvalor : id, fecha: fecha_pago },  // create an object will all values
		//function that is called when server returns a value.
			function(data){
				$('#subform_devoluciones').html(data);
				get_facturas_select($('#proveedor').val());
				$('#subform_detalle').show();
				$('#subform_devoluciones').show();
				$('#subform_devoluciones_lote0').hide();
				$('#subform_devoluciones_lote0_trans').hide();

			}
		);
   } else {
      $('#subform_devoluciones').html("Seleccione un Proveedor válido");
   }
}
function mostrar_devoluciones_lote0(){
    // post(file, data, callback, type); (only "file" is required)
    fecha_pago=$("#fecha").val();
	id=$('#proveedor').val()
    if(id>0){
		$.post("<? echo base_url();?>index.php/ajax_pet/pagos_multiples_devoluciones_lote0",{ enviarvalor : id, fecha: fecha_pago },  // create an object will all values
		//function that is called when server returns a value.
			function(data){
				$('#subform_devoluciones_lote0').html(data);
			}
		);
   } else {
      $('#subform_devoluciones_lote0').html("Seleccione un Proveedor válido");
   }
}
function enviar_lote0_trans(){
	total=document.getElementById('total_lote0').value;
	salidas_id=document.getElementById('salidas_id').value;
	proveedor=$('#proveedor').val();
    fecha_pago=$("#fecha").val();
    if(total>0){
		$.post("<? echo base_url();?>index.php/ajax_pet/pagos_multiples_devoluciones_lote0_trans",{ enviarvalor : total, salidas: salidas_id, proveedor_id:proveedor, fecha: fecha_pago  }, 
			function(data){
				$('#subform_devoluciones_lote0_trans').html(data);
			}
		);
   } else {
      $('#subform_devoluciones_lote0_trans').html("Ingrese el costo de compra de los productos");
   }
}

function get_facturas_select(valor){
    if(valor>0){
		<?php
		for ($x=1;$x<=1;$x++){
			echo "$(\"#pr_factura_id$x\").removeOption(/./); \n";
			echo "$(\"#pr_factura_id$x\").ajaxAddOption(\"". base_url() ."index.php/ajax_pet/get_facturas_pagos/\"+valor); \n";
		}
		?>
    }
}

function abrir_renglon(valor){
	nvalor=valor+1;
	pr_factura=$("#pr_factura_id"+valor).val();
        
	if(pr_factura>0){
		$("#pr_factura_id"+nvalor).removeOption(/./); 	$("#pr_factura_id"+nvalor).ajaxAddOption("<?=base_url()?>index.php/ajax_pet/get_facturas_pagos/"+$('#proveedor').val()+"/"+pr_factura); 
		$('#r'+nvalor).removeClass('invisible').addClass('visible');
		$('#r'+nvalor).show();
	} else
		alert("Seleccione una factura válida");
}

function get_cuentas_prov(valor){
    if(valor>0){
      $("#cuenta_destino").removeOption(/./);
      $("#cuenta_destino").ajaxAddOption("<? echo base_url();?>index.php/ajax_pet/get_cuentas_prov/"+valor);
    }
}

function borrar_detalle(id, line){
	// post(file, data, callback, type); (only "file" is required)
	if(id>0){
		$.post("<? echo base_url();?>index.php/ajax_pet/borrar_pago",{ enviarvalor: id, linea: line},  // create an object will all values
			function(data){
				$('#pr_factura_id'+line).html(data);
				$('#monto_pagado'+line).val('0');
				$('#no_autorizacion'+line).val('0');
			});
	}
}

function send_detalle(){
	proveedor=document.form1.proveedor.value;
	fecha=document.form1.fecha.value;
	cuenta_origen=parseFloat(document.form1.cuenta_origen_id.value);
	cuenta_destino=document.form1.cuenta_destino_id.value;
	cpr_forma_pago=document.form1.cpr_forma_pago_id.value;
	for(var y=1;y<= <? echo $rows; ?>;y++){
		monto_pagado=parseFloat($('#monto_pagado'+y).val());
		no_autorizacion=$('#no_autorizacion'+y).val();
		$("input[name=cproveedor_id"+y+"]").val(''+proveedor);
		$("input[name=fecha"+y+"]").val(''+fecha);
		if(proveedor > 0 && fecha!="" && cuenta_origen>0 && monto_pagado>0 ){
			//alert(monto_pagado);
			$("input[name=cuenta_origen_id"+y+"]").val(''+cuenta_origen);
			$("input[name=cuenta_destino_id"+y+"]").val(''+cuenta_destino);
			$("input[name=cpr_forma_pago_id"+y+"]").val(''+cpr_forma_pago);
			document.getElementById('b'+y).click();
		}
		//document.getElementById("fin").innerHTML='<button type="button" onclick = "javascript:imprimir_reporte" />Imprimir Informe</button>';
		$('#fin').html("<a href='javascript:imprimir_reporte()'  ><img src='<?=base_url()?>images/adobereader.png' width='50' title='Imprimir Reporte'></a>");
	}
}
	function mostrar_detalle(){
		$("#boton2").hide();
		alert("Se estan procesando los pagos por devolución del proveedor, espere un momento..");
		get_pagos_multiples_devoluciones($('#proveedor').val());
	}
	
	function sumar_montos(renglon,monto,id){
	if(id>0){
		$.post("<? echo base_url();?>index.php/ajax_pet/pago_sin_excederse",{ enviarvalor : id , monto:monto },  // create an object will all values
		//function that is called when server returns a value.
			function(data){
				$(renglon).html(data);
			}
		);
   }	
                total=0;
		for(y=1;y<<? echo $rows; ?>;y++){
			if($('#monto_pagado'+y).val()=='')
				$('#monto_pagado'+y).val(0);
			total=total + parseFloat($('#monto_pagado'+y).val());	
		}
		$('#total').val(total);
	}
	
	function imprimir_reporte(){
		pid=document.form1.proveedor.value;
		fecha=document.form1.fecha.value;
		fecha1=fecha.replace(/ /g, "-");
		location.href="<?=base_url()?>index.php/contabilidad/contabilidad_reportes/rep_pagos_multiples_pdf/"+pid+"/"+fecha1;
	}
</script>
