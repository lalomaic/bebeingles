<script>
<?php
echo <<<end
  $(document).ready(function() { 
      $('#subform_detalle').show('slow')
    //opciones para el formulario principal
    var options = { 
        target:        '#out_form1',   // target element(s) to be updated with server response 
//        beforeSubmit:  form_principal,  pre-submit callback 
//        success:       mostrar_subform // post-submit callback 
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
        echo "$('#fdetail$t').submit(function() {".
                "$(this).ajaxSubmit(options$t);".
                // always return false to prevent standard browser submit and page navigation
                "return false;".
                "});\n";
    }
?>
 

    $(".cantidad").jField({
        allowNegatives: false,
        allowDecimal: true
    });
}); //Final funcion ready

function send_detalle(){
     // document.getElementById('boton1').click();
  for(var y=0;y< <? echo $rows; ?>;y++){
//      eval("document.fdetail"+y+".arqueo_id"+y+".value=document.form1.id.value");
      var arqueo_id=parseFloat(eval("document.fdetail"+y+".arqueo_id"+y+".value"));
      var id=parseFloat(eval("document.fdetail"+y+".id"+y+".value"));
      var cantidad=parseFloat(eval("document.fdetail"+y+".cantidad_real"+y+".value"));
      if(id>0 && arqueo_id>0 ){
	document.getElementById('b'+y).click();
      }
    }
//  document.getElementById("fin").innerHTML='<button type="button" onclick="javascript:verificar()"/>Finalizar</button>';
}

function send_detalle_final(){
  $('#exe').hide();
  $('#guardar').hide();
  alert("Se comenzar� el Ajuste de las existencias... Continuar");
  $('#msg1').html("< img src='<? echo base_url(); ?>images/cargando.gif' width='50px'/>Cargando...");

  for(var x=0;x< <? echo $rows; ?>;x++){
      eval("document.fdetail"+x+".action='<? echo base_url()."index.php/".$ruta."/trans/ajustar_arqueo_detalles_final"; ?>/"+x+"'");
      var arqueo_id=parseFloat(eval("document.fdetail"+x+".arqueo_id"+x+".value"));
      var id=parseFloat(eval("document.fdetail"+x+".id"+x+".value"));
      var cantidad=parseFloat(eval("document.fdetail"+x+".ctipo_ajuste_detalle_id"+x+".value"));
      if(id>0 && arqueo_id>0 & cantidad>1){
	document.getElementById('b'+x).click();
      }
    }
  document.getElementById("fin").innerHTML='<button type="button" onclick="javascript:verificar('+arqueo_id+')"/>Finalizar</button>';
  $('#msg1').html('');
}

function verificar(ajuste){
  location.href="<? echo base_url();?>index.php/<? echo $ruta; ?>/trans/verificar_ajuste/"+ajuste;
}
</script>
