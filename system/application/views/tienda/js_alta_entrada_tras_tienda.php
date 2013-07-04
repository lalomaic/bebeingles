<script>
<?php
echo <<<end
  $(document).ready(function() { 
    $($.date_input.initialize);
    $('.invisible').hide(); \n
end;

for($t=0;$t<$rows;$t++) {

echo  <<<END

END;
//Codigo
    echo     "$('#cantidad$t').blur(function() {\n".
        "if($(this).val()>0){ \n ".
            "$('#row".($t+1)."').show(); \n".
            "$('#row".($t+1)."').removeClass('invisible').addClass('visible'); \n ".
        "}; \n".
    "});\n";
    
    echo "$('#codigo$t').keypress(function(e) { \n".
        "if(e.keyCode == 13) {\n".
            "get_producto($(this).val(),$t);\n".
            "$('#row".($t+1)."').show(); \n".
            "$('#row".($t+1)."').removeClass('invisible').addClass('visible'); \n ".
            "return false;".
        "}\n".
            "return false;".
    "}); \n";
    
}
?>
}); //Final funcion ready
function get_producto(id, line){
    if(id.length>7){
        $.post("<? echo base_url();?>index.php/ajax_pet/get_producto_by_codigo_entrada",{ enviarvalor: id, linea: line, recibe: <?=$ubicacion_id?>},  // create an object will all values                             //function that is called when server returns a value.
        function(data){
            if(data=='No encontrado')
                $('#codigo'+line).val('');
            else {
                $('#descripcion_span'+line).html(data);
                n=line+1;
                $('#codigo'+n).focus();
            }
        });
    } else {
        alert("El código leido no es correcto, intente de nuevo")
        $('#codigo'+line).val('');
    }
	return false;
}

 function buscar_productos(id, lines){
    if(id.length>0){
		$('#area_notificacion').html("Buscando...");
		encontrado=0;
        $.post("<? echo base_url();?>index.php/ajax_pet/get_producto_by_codigo_entrada",{ enviarvalor: id, linea: lines, recibe: <?=$ubicacion_id?>},  // create an object will all values                             //function that is called when server returns a value.
        function(data){
			for(z=0;z<=lines;z++){
				if((id==$("#etiqueta_"+z).val()) &&  ($('#codigo'+z).val()=='')){
					encontrado=1;
					$('#descripcion_span'+z).html(data);
					$('#row'+z).removeClass('visible').addClass('visible_encontrado');
					$('#codigo'+z).val($("#etiqueta_"+z).val());
					$('#area_notificacion').html("Siguiente...");
					alert("Encontrado");
					break;
				}
			}
			if(encontrado==0){
				$('#area_notificacion').html("No encontrado...");
			}
			$('#codigo_general').val("");
			$('#codigo_general').focus();

        });
    } else {
        alert("El código leido no es correcto, intente de nuevo")
        $('#codigo'+line).val('');
    }
    return false;
}

function send_detalle(){
      $('#boton_p').hide();
      alert("La salida por traspaso se esta procesando... Continuar");
      $('#msg1').html("<br/><img src='<? echo base_url(); ?>images/cargando.gif' width='50px'/><br/>Cargando...<br/>");
      alert("El traspaso se estan enviando... \n Presionar en Aceptar");
  for(var y=0;y< <? echo $rows; ?>;y++){
   var cantidad=parseFloat(eval("document.fdetail"+y+".cantidad"+y+".value"));
   var producto=parseFloat(eval("document.fdetail"+y+".producto"+y+".value"));
    if(cantidad>0 && producto>0){
    eval("document.fdetail"+y+".traspaso_id"+y+".value=document.form1.traspasos_id.value");
      var id=parseFloat(eval("document.fdetail"+y+".traspaso_id"+y+".value"));
      if(id>0){
	document.getElementById('b'+y).click();
      }
    }
  }
  document.getElementById("fin").innerHTML='<button type="button" onclick="javascript:verificar()" class="modal_pdf"/>Terminar</button>';
  $('#msg1').html("");
}

function verificar(){
  var id=eval("document.form1.traspasos_id.value");
  location.href="<? echo base_url();?>index.php/almacen/tienda_c/formulario/list_salida_traspasos/";
//  location.href="<? echo base_url();?>index.php/almacen/almacen_reportes/rep_pedido_traspaso/"+id;
}
</script>
