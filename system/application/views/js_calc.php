<script>
function calc(line){
    path1="document.fdetail"+line;
    unit=eval(path1+".unidades"+line+".value");
//     producto=parseFloat(eval(path1+".cproducto_id"+line+".value"));
	producto=eval($('#producto_id'+line).val());
    imp=$('#tasa_imp'+line).val();
    $('#iva_total').val('0');
    precio=$('#precio_u'+line).val();
    tot=document.t1.total;
    iva_total=document.t1.iva_total;
    if(producto>0 && _IsNumber(precio) && precio.length>0 && imp.length>0){
		price=parseFloat(precio.value);
		subt=eval(path1+".subtotal"+line);
		subt.value=roundVal(parseFloat(unit*precio));
		iva=1+eval(imp/100);
		var next=line+1;
		var colect=0;
		tot.value=0;
		for(var y=1;y<=<? echo $rows; ?>;y++){
			sub=eval("document.fdetail"+y+".subtotal"+y+".value");
			if(sub.length>0){
				colect =  parseFloat(tot.value);
				colect_iva =  parseFloat(iva_total.value);
				iva_total.value=roundVal(parseFloat(colect_iva) +  (imp/100) * parseFloat($('#precio_u'+y).val()) * parseFloat($('#cantidad'+y).val()) / iva);
				tot.value = roundVal(colect +  parseFloat(eval("document.fdetail"+y+".subtotal"+y+".value")));
			}
		}
		sub=roundVal(parseFloat(tot.value) - parseFloat(iva_total.value));
		$('#subtotal').val(''+sub);
		document.t1.subtotal1.value=sub;
		act_parent_id(path1,line);
	} else {
		subt=eval(path1+".subtotal"+line);
		subt.value=0;;
    }
}

  function roundVal(val){
	  var dec = 2;
	  var result = Math.round(val*Math.pow(10,dec))/Math.pow(10,dec);
	  return result;
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
