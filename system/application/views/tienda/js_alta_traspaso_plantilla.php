<script>
<?php
echo <<<end
  $(document).ready(function() { 
  $($.date_input.initialize);

    //opciones para el formulario principal
    var options = { 
        target:        '#out_form1',   // target element(s) to be updated with server response 
        success:       mostrar_subform // post-submit callback 
    }; 


  //Redirecciones a los contenedores de los estatus de los detalles y envio mediante ajax
    $('#form1').submit(function() {
        $(this).ajaxSubmit(options);
        // always return false to prevent standard browser submit and page navigation
        return false;
    });
end;

	for($t=0;$t<count($productos);$t++){
		echo "var options$t = {". 
		"target: '#content$t'". 
		"};"; 
	}

	for($t=0;$t<count($productos);$t++) {
		echo     "\n $('#fdetail$t').submit(function() {".
			"$(this).ajaxSubmit(options$t);".
			"return false;".
		"});\n";

	}
	?>

	$(".cantidad").jField({
		allowNegatives: false,
		allowDecimal: true
	});
}); //Final funcion ready

function send_detalle1(){
      var id=document.form1.id.value
	  document.getElementById('boton1').click();
}

function verificar(){
	var id=eval("document.form1.id.value");
    location.href="<? echo base_url();?>index.php/<? echo $ruta; ?>/trans/verificar_pedido_traspaso/"+id;
}

  function mostrar_subform(){
	for(var y=0;y<<? echo count($productos); ?>;y++){
		var cantidad=parseFloat(eval("document.fdetail"+y+".unidades"+y+".value"));
		var producto=parseFloat(eval("document.fdetail"+y+".producto"+y+".value"));
		if(cantidad>0 && producto>0){
		$('#fin').text(''+producto+"&"+cantidad+"--");
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
    //Definir el text input a actualizar
    eval(obj+".cl_pedidos_id"+line+".value=document.form1.id.value");
  }
</script>
