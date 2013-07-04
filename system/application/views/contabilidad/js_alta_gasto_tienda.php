<script type="text/javascript">
$(document).ready(function() {
	$($.date_input.initialize);
	$('.visible').show();
	$('.invisible').hide();

	$('#fecha').blur(function() {	  
		get_cuentas_prov($('#proveedor').val());
	});
<?php
	for ($x=0;$x<$lineas;$x++){
		echo "$('#monto$x').blur(function() {".
			"sumar_montos(); \n".
			"enviar_linea_ajax($x);\n".
		"});\n";
	}
?>
});


function enviar_linea_ajax(linea){
	cgastos_id1=$('#cgasto_id'+linea).val();
	ccuenta_bancaria_id1=$('#cuenta_empresa'+linea).val();
	concepto1=$('#concepto'+linea).val();
        fecha1=$('#fecha'+linea).val();
	hora1=$('#hora'+linea).val();
	monto1=$('#monto'+linea).val();
	espacio=$('#espacio').val();
  	referencia1=$('#referencia'+linea).val();

// 	alert(ctipo_gasto_id1);
    if(monto1>0 && espacio>0 && ccuenta_bancaria_id1>0 && cgastos_id1>0 ){
		$.post("<? echo base_url();?>index.php/contabilidad/trans/act_gasto_tienda/1",{ 
			cgastos_id : cgastos_id1,
			ccuenta_bancaria_id: ccuenta_bancaria_id1,
			concepto : concepto1,
			fecha : fecha1,
			monto : monto1,
			referencia:referencia1,
			espacios_fisicos_id : espacio			
		},  
		//function that is called when server returns a value.
			function(data){
				$('#estatus'+linea).html(data);
			}
		);
   } else {
      $('#estatus'+linea).html("<img src='<?=base_url()?>images/cancelado.png' width='15' align='left' title='Faltan datos para registrar el gasto'>");
   }
}


	
	function sumar_montos(){
		total=0;
		for(y=0;y<<?=$lineas?>;y++){
			if($('#monto'+y).val()=='')
				$('#monto'+y).val(0);
			total=total + parseFloat($('#monto'+y).val());	
		}
		$('#total').val(total);
	}
	
</script>
