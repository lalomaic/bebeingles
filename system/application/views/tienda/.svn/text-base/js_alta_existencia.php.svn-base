<script>
<?php
echo <<<end
  $(document).ready(function() { 
    $('#boton1').hide();
    //opciones para el formulario principal
    var options = { 
        target:        '#out_form1',   // target element(s) to be updated with server response 
//        beforeSubmit:  form_principal,  pre-submit callback 
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
echo     "$('#cantidad_real$t').blur(function() {\n".
	  "var id=eval(\"document.form1.id.value\"); \n".
	  "document.fdetail$t.arqueo_id$t.value=id; \n".
	 "if(id>0){ \n ".
//	  "$('#b'+$t).click();\n".
//	  "document.getElementById('b$t').click(); \n".
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

function send_detalle(){
      $('#boton_p').hide();
      $('#msg1').html("<br/><img src='<? echo base_url(); ?>images/cargando.gif' width='50px'/><br/>Cargando...<br/>");
      alert("Las existencias se estan enviando... Presionar en Aceptar");
      document.getElementById('boton1').click();
}

function verificar(){
  var id=eval("document.form1.id.value");
  location.href="<? echo base_url();?>index.php/<? echo $ruta; ?>/tienda_c/formulario/list_existencia_fisica/";
}

  function mostrar_subform(){
  for(var y=0;y< <? echo $rows; ?>;y++){
   var cantidad_real=parseFloat(eval("document.fdetail"+y+".cantidad_real"+y+".value"));
   var producto=parseFloat(eval("document.fdetail"+y+".cproducto_id"+y+".value"));
    if(producto>0){
      eval("document.fdetail"+y+".arqueo_id"+y+".value=document.form1.id.value");
      var id=eval("document.fdetail"+y+".arqueo_id"+y+".value");
      if(id>0){
	document.getElementById('b'+y).click();
      }
    }
  }
  document.getElementById("fin").innerHTML='<button type="button" onclick="javascript:verificar()"/>Finalizar</button>';
  $('#msg1').html("");
}

  function act_parent_id(obj, line){
    //Definir el text input a actualiar
    eval(obj+".cl_pedidos_id"+line+".value=document.form1.id.value");
  }
</script>
