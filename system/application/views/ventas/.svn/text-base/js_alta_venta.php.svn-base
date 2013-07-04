<script>
<?php
echo <<<end
  $(document).ready(function() { 
  $('#clientes').focus();
      $('.visible').hide()
      $('.invisible').hide();
$('#clientes').select_autocomplete();

  $($.date_input.initialize);
      $('#subform_detalle').hide()
      $('#subtotal').val('0');
      $('#iva').val('0');
      $('#total').val('0');

    //opciones para el formulario principal
    var options = { 
        target:        '#out_form1',   // target element(s) to be updated with server response 
        beforeSubmit:  form_principal,  // pre-submit callback 
        success:       mostrar_subform // post-submit callback 
    }; 


  //Redirecciones a los contenedores de los estatus de los detalles y envio mediante ajax
    $('#form1').submit(function() {
        $(this).ajaxSubmit(options);
        // always return false to prevent standard browser submit and page navigation
        return false;
    });
end;
for($t=0;$t<$rows;$t++){
  echo "var options$t = {". 
      "target: '#content$t'". 
      "};"; 

  echo     "$('#fdetail$t').submit(function() {".
        "$(this).ajaxSubmit(options$t);".
        "return false;".
    "});\n";

//Cantidad
echo     "$('#cantidad$t').blur(function() {\n".
	 "if($(this).val()>0){ \n ".
	  "valor=document.fdetail$t.producto$t.value; \n".
	  "get_precio1(valor, $t, $('#cantidad$t').val())\n".
//	  "get_ext(valor, $t, $('#cantidad$t').val())\n".
	  "calc($t);\n".
	  "$('#subtotal$t').format({format:\"#,###.00\", locale:\"us\"});".
	  "$('#row".($t+1)."').show(''); \n".
	  "$('#row".($t+1)."').removeClass('invisible').addClass('visible'); \n ".
	  "}; \n".

"});\n";

echo  "$('#producto$t').select_autocomplete(); \n";
//echo  "$('#producto$t').show(); \n";

//Precio
echo     "$('#precio_u$t').blur(function() {\n".
	  "calc($t);\n".
	  //"$(this).format({format:\"#,###.00\", locale:\"us\"});".
 "});\n";

echo     "$('#precio_u$t').focus(function() {\n".
	  "valor=document.fdetail$t.producto$t.value; \n".
	  "get_precio1(valor, $t, $('#cantidad$t').val())\n".
//	  "get_ext(valor, $t, $('#cantidad$t').val())\n".
	  "calc($t);\n".
//	  "$(this).format({format:\"#,###.00\", locale:\"us\"});".
 "});\n";

//IVA
echo     "$('#tasa_imp$t').blur(function() {\n".
	  "calc($t);\n".
"});\n";
//Cantidad
echo     "$('#subtotal$t').blur(function() {\n".
//	  "get_ext(valor, $t, $('#cantidad$t').val())\n".
	  "calc($t);\n".
"});\n";
}
?>
}); //Final funcion ready
function borrar_detalle(id, line){
    // post(file, data, callback, type); (only "file" is required)
    if(id>0){
    $.post("<? echo base_url();?>index.php/ajax_pet/borrar_detalle",{ enviarvalor: id, linea: line},  // create an object will all values
    //function that is called when server returns a value.
    function(data){
        $('#content'+line).html(data);
        $('#cantidad'+line).val('0');
        $('#producto'+line).val('0');
        $('#precio_u'+line).val('0');
        $('#iva'+line).val('0');
	calc(line);
    });
   }
}

   function calc(line){
    path1="document.fdetail"+line;
    unit=eval(path1+".unidades"+line+".value");
    producto=eval(path1+".producto"+line+".value");
    imp=$('#tasa_imp'+line).val();
    $('#iva_total').val('0');
    precio=$('#precio_u'+line).val();
    tot=document.t1.total;
    iva_total=document.t1.iva_total;

    if(_IsNumber(unit) && producto>0 && _IsNumber(precio) && unit.length>0 && precio.length>0 && imp.length>0){
      price=parseFloat(precio.value);
      subt=eval(path1+".subtotal"+line);
      subt.value=roundVal(parseFloat(unit*precio));
      iva=1+eval(imp/100);
      siva=subt.value
      subt.value= roundVal(parseFloat(siva)*parseFloat(iva));
      var next=line+1;
/*      elem = eval("document.getElementById('fdetail"+ next +"')");
      elem.style.display = 'block';*/
      var colect=0;
      tot.value=0;
      for(var y=0;y<<? echo $rows; ?>;y++){
      sub=eval("document.fdetail"+y+".subtotal"+y+".value");
	 if(sub.length>0){
	  colect =  parseFloat(tot.value);
	  colect_iva =  parseFloat(iva_total.value);
/*	  iva_total.value=roundVal(colect_iva +  parseFloat($('#precio_u'+y).val()) *parseFloat($('#cantidad'+y).val())*parseFloat($('#tasa_imp'+y).val())/100);
	  tot.value = roundVal(colect +  parseFloat(eval("document.fdetail"+y+".subtotal"+y+".value")));*/
	  iva_total.value=roundVal(parseFloat(colect_iva) +  (imp/100) * parseFloat($('#precio_u'+y).val()) * parseFloat($('#cantidad'+y).val()) / iva);
	  tot.value = roundVal(colect +  parseFloat(eval("document.fdetail"+y+".subtotal"+y+".value")));
	 }
      }
      sub=parseFloat(tot.value) - parseFloat(iva_total.value);
      $('#subtotal').val(''+sub);
	document.t1.subtotal1.value=sub;
/*      pedidof=eval(path1+".pr_pedidos_id"+line);
      pedidof.value=document.form1.id.value;*/
      act_parent_id(path1,line);
    } else {
      subt=eval(path1+".subtotal"+line);
      subt.value=0;;
    }
  }

function roundVal(val){
	var dec = 3;
	var result = Math.round(val*Math.pow(10,dec))/Math.pow(10,dec);
	return result;
}

function get_precio1(valor, line, cantidad){
    if(valor>0){
      $("#precio_u"+line).removeOption(/./);
      $("#precio_u"+line).ajaxAddOption("<? echo base_url();?>index.php/ajax_pet/precios1/"+valor+"/"+line);
      get_iva(valor, line);
      calc(line);
    }
}

function get_ext(valor, linea, cantidad) {
  if(valor > 0 && cantidad>0) { 
    $.post("<? echo base_url(); ?>index.php/ajax_pet/existencia",{ enviarvalor: valor, unidades: cantidad},
    function(data){
      e=data;
      if(parseFloat(e)<parseFloat(cantidad)){
      txt="<p id='mensaje'>Disponible: "+data+"</p>";
      $('#msg'+linea).html(txt);
      $('#cantidad'+linea).val(''+e)
      $('#cantidad'+linea).focus();
      } else {
      $('#msg'+linea).html('');
      }
//      $('#subtotal'+linea).format({format:"#,###.00", locale:"us"});
    });
  }
}

function get_iva(valor, linea) {
  if(valor > 0) { 
    $.post("<? echo base_url(); ?>index.php/ajax_pet/iva1",{ enviarvalor: valor},
    function(data){
      $('#tasa_imp'+linea).val(data);
//      $('#subtotal'+linea).format({format:"#,###.00", locale:"us"});
    });
  }
}

function send_detalle(){
      document.getElementById('boton1').click();
  for(var y=0;y< <? echo $rows; ?>;y++){
   var id=parseFloat(eval("document.fdetail"+y+".cl_pedidos_id"+y+".value"));
   var subtotal=parseFloat(eval("document.fdetail"+y+".subtotal"+y+".value"));
    if(id > 0 && subtotal>0){
      document.getElementById('b'+y).click();
    }
  }
  document.getElementById("fin").innerHTML='<button type="button" onclick="javascript:verificar()"/>Finalizar</button>';
    
}

function verificar(){
  var id=eval("document.form1.id.value");
  location.href="<? echo base_url();?>index.php/<? echo $ruta; ?>/trans/verificar_pedido_venta/"+id;
}

  function mostrar_subform(){
  $('#subform_detalle').show()
  $('.visible').show()
  $('.invisible').hide()
}

  function act_parent_id(obj, line){
    //Definir el text input a actualiar
    eval(obj+".cl_pedidos_id"+line+".value=document.form1.id.value");
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
