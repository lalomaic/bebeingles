<script>
<?php
echo <<<end
  $(document).ready(function() { 
  $($.date_input.initialize);
      $('.visible').show()
      $('.invisible').hide();
      $('.prod').select_autocomplete(); 
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
}
for($t=0;$t<$rows;$t++) {

  echo     "$('#fdetail$t').submit(function() {".
        "$(this).ajaxSubmit(options$t);".
        // always return false to prevent standard browser submit and page navigation
        "return false;".
    "});\n";

//Cantidad
echo     "$('#cantidad$t').blur(function() {\n".
	 "if($(this).val()>0){ \n ".
	  "$('#row".($t+1)."').show(); \n".
	  "$('#row".($t+1)."').removeClass('invisible').addClass('visible'); \n ".
	  "}; \n".
"});\n";
//echo  "$('#producto$t').show(); \n";
}
?>

$(".cantidad").jField({
allowNegatives: false,
allowDecimal: true
});
}); //Final funcion ready

function borrar_detalle(id, line){
    if(id>0){
      $.post("<? echo base_url();?>index.php/ajax_pet/borrar_detalle", { enviarvalor: id, linea: line},  // create an object will all values
      function(data){
	  $('#content'+line).html(data);
	  $('#cantidad'+line).val('0');
      });
   }
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

function send_detalle(){
      var id=document.form1.id.value
	  document.getElementById('boton1').click();
}

function verificar(){
  var id=eval("document.form1.id.value");
/*  location.href="<? echo base_url();?>index.php/<? echo $ruta; ?>/tienda_c/formulario/list_transferencias/";*/
    location.href="<? echo base_url();?>index.php/<? echo $ruta; ?>/trans/verificar_pedido_traspaso/"+id;
}

  function mostrar_subform(){
  for(var y=0;y< <? echo $rows; ?>;y++){
   var cantidad=parseFloat(eval("document.fdetail"+y+".cantidad"+y+".value"));
   var producto=parseFloat(eval("document.fdetail"+y+".producto"+y+".value"));
    if(cantidad>0 && producto>0){
      eval("document.fdetail"+y+".cl_pedidos_id"+y+".value=document.form1.id.value");
      var id=parseFloat(eval("document.fdetail"+y+".cl_pedidos_id"+y+".value"));
      if(id>0){
	document.getElementById('b'+y).click();
      }
    }
  }
  document.getElementById("fin").innerHTML='<button type="button" onclick="javascript:verificar()"/>Finalizar</button>';
}

  function act_parent_id(obj, line){
    //Definir el text input a actualiar
    eval(obj+".cl_pedidos_id"+line+".value=document.form1.id.value");
  }
</script>
