<script>
  $(document).ready(function() { 
    $($.date_input.initialize);
    $('#proveedores').select_autocomplete(); 
    $('.prod').select_autocomplete(); 
    $('#subform_detalle').show()

    var options = { 
        target:        '#out_form1',   // target element(s) to be updated with server response 
//        beforeSubmit:  form_principal,  // pre-submit callback 
        success:       mostrar_subform // post-submit callback 
    }; 


  //Redirecciones a los contenedores de los estatus de los detalles y envio mediante ajax
    $('#form1').submit(function() {
        $(this).ajaxSubmit(options);
        // always return false to prevent standard browser submit and page navigation
        return false;
    });
<?php
    for($t=0;$t<$rows;$t++) {
      echo "var options$t = {". 
	  "target: '#content$t'". 
	  "}; \n"; 

      echo "$('#fdetail$t').submit(function() {".
	    "$(this).ajaxSubmit(options$t); \n".
	    // always return false to prevent standard browser submit and page navigation
	    "return false;".
	"});\n";
    }
?>


});

function send_detalle(){
      document.getElementById('boton1').click();
}

function verificar(){
  var id=eval("document.form1.id.value");
  location.href="<? echo base_url();?>index.php/<? echo $ruta; ?>/trans/verificar_entrada_bonificacion/"+id;
}

function mostrar_subform(){
  for(var y=0;y< <? echo $rows; ?>;y++){
   eval("document.fdetail"+y+".pr_facturas_id"+y+".value=document.form1.id.value");
   var id=parseFloat(eval("document.fdetail"+y+".cantidad"+y+".value"));
    if(id>0){
      document.getElementById('b'+y).click();
    }
  }
  document.getElementById("fin").innerHTML='<button type="button" onclick="javascript:verificar()"/>Finalizar</button>';
}

  function act_parent_id(obj, line){
    //Definir el text input a actualiar
    eval(obj+".pr_facturas_id"+line+".value=document.form1.id.value");
  }

  function otra_accion(valor, linea){
    //Vacio
  }
</script>
