<script>
<?php
echo <<<end
	$(document).ready(function() { 
		$('#concepto').focus();
		$('.cuentas_fil').select_autocomplete();
		$($.date_input.initialize);
		$('#subtotal').val('0');
		$('#iva').val('0');
		$('#total').val('0');

		//opciones para el formulario principal
		var options = { 
			target:        '#out_form1',   // target element(s) to be updated with server response 
			beforeSubmit:  form_principal,  // pre-submit callback 
			success:       send_detalle// post-submit callback 
		}; 


		//Redirecciones a los contenedores de los estatus de los detalles y envio mediante ajax
		$('#form1').submit(function() {
			$(this).ajaxSubmit(options);
			// always return false to prevent standard browser submit and page navigation
			return false;
		});
end;

	for($t=0;$t<$lineas_totales;$t++){
		echo "var options$t = {". 
			"target: '#content$t'". 
			"};"; 

		echo     "$('#fdetail$t').submit(function() {".
				"$(this).ajaxSubmit(options$t);".
				"return false;".
			"});\n";

			//Subcuentas
			echo "$('#subcuenta$t').focus(function() {\n".
			"valor=document.fdetail$t.cta$t.value; \n".
			"get_subcuentas(valor, $t)\n".
			"});\n";
			
			//Debe
			echo "$('#debe$t').blur(function() {\n".
			"calc($t)\n".
			"});\n";
			//Haber
			echo "$('#haber$t').blur(function() {\n".
			"calc($t)\n".
			"});\n";
			
			if($t>$lineas_vis)
				echo "$(\"#r$t\").hide();\n";
			echo "calc($t);\n";
	
	}
?>
}); //Final funcion ready

function borrar_detalle(id, line){
    // post(file, data, callback, type); (only "file" is required)
    if(id>0){
		$.post("<? echo base_url();?>index.php/ajax_pet/borrar_poliza_detalle",{ enviarvalor: id, linea: line},  // create an object will all values
    //function that is called when server returns a value.
		function(data){
			$('#content'+line).html(data);
			$('#haber'+line).val('0');
			$('#debe'+line).val('0');
		});
   }
}

function get_subcuentas(valor, line){
    if(valor>0){
      $("#subcuenta"+line).removeOption(/./);
      $("#subcuenta"+line).ajaxAddOption("<? echo base_url();?>index.php/ajax_pet/get_subcuentas/"+valor);
    }
}

function calc(line){
	path1="document.fdetail"+line;
	debe=eval(path1+".debe"+line+".value");
	haber=eval(path1+".haber"+line+".value");
	
	if(_IsNumber(debe) && _IsNumber(haber)){
		var next=line+1;
		$('#r'+next).show();
/*		elem = eval("document.getElementById('fdetail"+ next +"')");
		elem.style.display = 'block';*/
		var debe_t=0; var haber_t=0;
		for(var y=0;y<<? echo $lineas_totales; ?>;y++){
			subcuenta=$('#subcuenta'+y).val();
			if(subcuenta>0){
				debe_t=parseFloat(debe_t)+parseFloat($('#debe'+y).val());
				haber_t=haber_t+parseFloat($('#haber'+y).val());
			}
		}

		$('#haber_t').val(''+haber_t);
		$('#debe_t').val(''+debe_t);
	} else {
		$('#debe'+line).val(0);
		$('#haber'+line).val(0);
	}
}

function send_principal(){
	document.getElementById('boton1').click();
}

function send_detalle(){
      //document.getElementById('boton1').click();
	for(var y=0;y< <? echo $lineas_totales; ?>;y++){
		poliza_id=document.form1.id.value;
		$("input[name=poliza_id"+y+"]").val(''+poliza_id);
//		eval("document.fdetail"+y+".poliza_id"+y+".value="+poliza_id)
		var id=parseFloat(eval("document.fdetail"+y+".poliza_id"+y+".value"));
		haber=$("input[name=haber"+y+"]").val();
		debe=$("input[name=debe"+y+"]").val();
		subcuenta=$("select[name=subcuenta"+y+"]").val();
		if(id > 0 && subcuenta>0){
			if(haber>0 || debe>0 ){
				document.getElementById('b'+y).click();
			}
		}
		document.getElementById("fin").innerHTML='<button type="button" onclick="javascript:verificar_poliza_diario()"/>Finalizar</button>';
	}
}

function verificar_poliza_diario(){
  var id=eval("document.form1.id.value");
  location.href="<? echo base_url();?>index.php/<? echo $ruta; ?>/trans/verificar_poliza_diario/"+$("input[name=id]").val();
}

function form_principal(){
<?php
    //imprime la validacion de los campos anteriormente seleccionada
    foreach ($validation as $row){
      echo "$('#{$row['id']}').validator({\n".
	$row['arguments']."\n".
      "  correct: function() {\n".
      "		     return true;\n".
      "	    },\n".
      "	    error: function() {\n".
      "		    $('#validation_result').text('{$row['response_false']}');\n".
      "	    }\n".
      "    });\n";
    }

    //Genera el bifurcador para liberar el envio del formulario
    $n=count($validation);
    if($n>0){
      $condicion="if(";
      $y=0;
      foreach ($validation as $row){
	$condicion .="$('#{$row['id']}').validator('validate') == true ";
	$y+=1;
	if($y<$n){
	  $condicion .="&& ";
	}
      }
      $condicion .="){ \n return true;\n } else {\n return false; }\n";
//      $condicion .="){ \n document.form1.submit();\n } else {\n return false; }\n";
      echo $condicion;
    } else {
      //echo true;
    }
?>
}
</script>
