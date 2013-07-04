<script type="text/javascript">    
  $(document).ready(function(){  
        $($.date_input.initialize);
        $("#error").hide();
		  $("#b_fin").hide();
        <?
        if($prestaciones!=false){
		  foreach ($prestaciones as $row1) {?>
			$('.pre_<?=$row1->id?>').hide();
        <? }
        } ?>
		<? 
        if($deducciones!=false){
		  foreach ($deducciones as $row1) {?>
			$('.ded_<?=$row1->id?>').hide();
        <? } 
        } 
        ?>        
        
        $(".prestacion").change(function(){
		  if($(this).attr('checked')==true){
			//Mostrar la columna			
			$('.pre_'+$(this).val()).show();
		  } else {
 			$('.pre_'+$(this).val()).hide();
			<? for($p=0;$p<count($detalles);$p++) {?>
			  $('#pres_<?=$p?>_'+$(this).val()).val('0');
			  act_total_linea(<?=$p?>);
			<? } ?>	   			
		  }
        });
        
        $(".deduccion").change(function(){
		  if($(this).attr('checked')==true){
			//Mostrar la columna
			$('.ded_'+$(this).val()).show();
		  } else {
			$('.ded_'+$(this).val()).hide();		  
			<? for($p=0;$p<count($detalles);$p++) {?>
			  $('#ded_<?=$p?>_'+$(this).val()).val('0');
			  act_total_linea(<?=$p?>);
			<? } ?>	   			
		  }
        });
        
        
    });  
    
    function act_total_linea(linea){
	  //Actualiza el total de la linea
	  subtotal= ( parseFloat($("#salario_diario_"+linea).val()) * parseFloat($("#dias_lab_"+linea).val())+parseFloat($("#horas_extra_"+linea).val())) + parseFloat($("#comision_"+linea).val()) - parseFloat($("#descuento_infonavit_"+linea).val());
	  total_percepciones=calcular_percepciones(linea);
	  total_deducciones=calcular_deducciones(linea);
	  total=subtotal+total_percepciones-total_deducciones;
	  $("#total_pagar_"+linea).val(""+total);
    }
    
    function calcular_percepciones(linea){
    //Sumar las percepciones
        <? 
        $str="";
        if($prestaciones!=false){
		  foreach ($prestaciones as $row1) {
			$str.="+parseFloat($('#pres_'+linea+'_$row1->id').val()) ";
		  } 
        }
        ?>
        $total_percepciones=0<?=$str?>;
        return $total_percepciones;
    }
    function calcular_deducciones(linea){
    //Sumar las percepciones
        <? 
        $str="";
        if($deducciones!=false){
		  foreach ($deducciones as $row1) {
			$str.="+parseFloat($('#ded_'+linea+'_$row1->id').val()) ";
		  } 
		 }
		?>
        $total_deducciones=0<?=$str?>;
        return $total_deducciones;
    }
    function act_efectivo(linea){
	  banco=parseFloat($("#banco_deposito_"+linea).val());
	  total=parseFloat($("#total_pagar_"+linea).val());
	  if(banco>total){
		$("#efectivo_deposito_"+linea).css('background-color','red');
		$("#efectivo_deposito_"+linea).val(0);
	  } else {
		$("#efectivo_deposito_"+linea).val((total-banco));
		$("#efectivo_deposito_"+linea).css("background-color", "white");
	  }
    }
    function enviar_todo(){
	  //Paso 1 dar de alta la nomina
	  $.post("<? echo base_url();?>index.php/nomina/nomina_c/alta_nomina/", { prenomina_id: $("#prenomina_id").val(), id: $("#id").val()},       //function that is called when server returns a value.
      function(data){
		if(data>0){
		  //Asignar el id de respuesta al input id
		  $('#id').val(data);
		  for(x=0;x<<?=count($detalles)?>;x++){
			//Enviar linea por linea, siempre y cuando la nomina se haya dado de alta
			enviar_linea(x);
		  }
		  $("#b_fin").show();
		} else
		  alert("Ocurrio un error al intentar guardar");
      });
	  	  
	  //Validar el id>0 y enviar linea por linea de todos los empleados
	  
	  //
    }
    
    function enviar_linea(linea){
	  $('#content'+linea).html("<img src='<? echo base_url(); ?>images/waiting.gif' width='20px'/>");
	  //Mostrar el estatus de cada linea
	  nom_id=$("#id").val();
	  prenom_det_id=$("#prenomina_detalle_id_"+linea).val();
	  empl_id=$("#empleado_id_"+linea).val();
	  <?
		//Generar la cadena de las percepciones, id y valor
		if($prestaciones!=false){
		  foreach ($prestaciones as $row1) {
			$mtx[]="pres_$row1->id: $('#pres_'+linea+'_$row1->id').val() ";
		  }
		  $pres_str=implode(",\n", $mtx).",";
        } else
		  $pres_str="";
				//Generar la cadena de las percepciones, id y valor
		if($deducciones!=false){
		  foreach ($deducciones as $row1) {
			$mtx1[]=" ded_$row1->id: $('#ded_'+linea+'_$row1->id').val() ";
		  }
		  $ded_str=implode(",\n", $mtx1).",";
        } else
		  $ded_str="";

	  ?>
	  $.post("<? echo base_url();?>index.php/nomina/nomina_c/alta_nomina_detalle", { 
		nomina_id: nom_id,
		prenomina_detalle_id: prenom_det_id,
		empleado_id: empl_id,
		id: $('#id_'+linea).val(),
		comision: $('#comision_'+linea).val(),
		dias_lab: $('#dias_lab_'+linea).val(),
		descuento_programado: $('#desc_prog_'+linea).val(),
		valor_horas_extra: $('#horas_extra_'+linea).val(),
		descuento_infonavit: $('#descuento_infonavit_'+linea).val(),<?=$pres_str?>
		monto_total: $('#total_pagar_'+linea).val(),<?=$ded_str?>
		monto_banco: $('#banco_deposito_'+linea).val(),
		monto_efectivo: $('#efectivo_deposito_'+linea).val(),
	  }, 
	  function(data){
		if(data>0){
		  $('#content'+linea).html('<img src="<?=base_url()?>images/ok.png" width="20px" title="Guardado">');
		  $('#id'+linea).val(data);
		} else {
		  $('#content'+linea).html('<img src="<?=base_url()?>images/trash.png" width="20px" title="Error">');
		}
	  });
    }

</script>