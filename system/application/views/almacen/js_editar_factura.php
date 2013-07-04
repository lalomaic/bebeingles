<script>
function send_detalle(){
      document.getElementById('boton1').click();
  for(var y=0;y< <? echo $rows; ?>;y++){
   eval("document.fdetail"+y+".pr_facturas_id"+y+".value=document.form1.id.value");
   var id=parseFloat(eval("document.fdetail"+y+".id"+y+".value"));
   var subtotal=parseFloat(eval("document.fdetail"+y+".subtotal"+y+".value"));
    if(subtotal>0){
      document.getElementById('b'+y).click();
    }
  }
  document.getElementById("fin").innerHTML='<button type="button" onclick="javascript:verificar()"/>Finalizar</button>';
    
}

function verificar(){
  var id=eval("document.form1.id.value");
  location.href="<? echo base_url();?>index.php/<? echo $ruta; ?>/trans/verificar_entrada/"+id;
}

function mostrar_subform(){
  $('#subform_detalle').show()
}

  function act_parent_id(obj, line){
    //Definir el text input a actualiar
    eval(obj+".pr_facturas_id"+line+".value=document.form1.id.value");
  }

  function otra_accion(valor, linea){
    //Vacio
  }
</script>
