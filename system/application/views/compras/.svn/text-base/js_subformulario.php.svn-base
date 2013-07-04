<script>
function get_iva(valor, linea) {
  if(valor > 0) { 
    $.post("<? echo base_url(); ?>index.php/ajax_pet/iva",{ enviarvalor: valor, linea: linea },
    function(data){
      $('#tasa_i'+linea).html(data);
      $('#tasa_imp'+linea).focus();
      $('#cantidad'+(linea+1)).focus();
      $('#cantidad'+(linea+1)).val('');
      calc(linea);
      $('.subtotal').format({format:"#,###.00", locale:"us"});
    });
  }
}

function send_detalle(){
      document.getElementById('boton1').click();
  for(var y=0;y< <? echo $rows; ?>;y++){
   var id=parseFloat(eval("document.fdetail"+y+".pr_pedidos_id"+y+".value"));
   var subtotal=parseFloat(eval("document.fdetail"+y+".subtotal"+y+".value"));
    if(id > 0 && subtotal>0){
      document.getElementById('b'+y).click();
    }
  }
  document.getElementById("fin").innerHTML='<button type="button" onclick="javascript:verificar()"/>Finalizar</button>';
    
}

function verificar(){
  var id=eval("document.form1.id.value");
  location.href="<? echo base_url();?>index.php/<? echo $ruta; ?>/trans/verificar_pedido_compra/"+id;
}

  function mostrar_subform(){
  $('#subform_detalle').show()
}
</script>
