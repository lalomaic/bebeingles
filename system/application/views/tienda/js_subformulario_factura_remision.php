<script>
  $(document).ready(function() { 
  $('.subtotal').format({format:"#,###.00", locale:"us"});
  $($.date_input.initialize);
  $('.cliente').select_autocomplete(); 


    $('#folio_factura').focus(function(){
	get_cliente($('#clientes').val());
    });
    //opciones para el formulario principal
    var options = { 
        target:        '#out_form1',   // target element(s) to be updated with server response 
        beforeSubmit:  totales,  // pre-submit callback 
        success:       subform// post-submit callback 
    }; 
//Redirecciones a los contenedores de los estatus de los detalles y envio mediante ajax
    $('#form1').submit(function() {
        $(this).ajaxSubmit(options);
        // always return false to prevent standard browser submit and page navigation
        return false;
    });
<?php
for($t=0;$t<$rows;$t++){
  echo "var options$t = {". 
      "target: '#content$t'". 
      "};"; 
}

for($t=0;$t<$rows;$t++){
  echo     "$('#fdetail$t').submit(function() {".
        "$(this).ajaxSubmit(options$t);".
        // always return false to prevent standard browser submit and page navigation
        "return false;".
    "});\n";
//Cantidad, productos, precio, iva y subtotal
//Echo disparadores de los select de los productos
}
?>
// changing date format to DD/MM/YYYY
});

function send_detalle(){
  $('#boton_p').hide();
  $('#msg1').html("<br/><img src='<? echo base_url(); ?>images/cargando.gif' width='50px'/><br/>Cargando...<br/>");
  alert("Se esta generando la Factura de la Remisi�n, presione aceptar para continuar");
  document.getElementById('boton1').click();
 }

function totales(){
  document.form1.monto_total=document.t1.total;
  document.form1.iva_total=document.t1.iva;
}

function subform(){
  for(var y=0;y< <? echo $rows; ?>;y++){
    var factura_id=document.form1.id.value
    if(factura_id>0){
      eval("document.fdetail"+y+".cl_factura_id"+y+".value="+factura_id+";");
      eval("document.fdetail"+y+".cclientes_id"+y+".value=document.form1.cclientes_id.value");
      var id=parseFloat(eval("document.fdetail"+y+".id_ubicacion_local"+y+".value"));
      var subtotal=parseFloat( eval("document.fdetail"+y+".subtotal"+y+".value"));
      if(id > 0 && subtotal>0 ){
	document.getElementById('b'+y).click();
      }
      document.getElementById("fin").innerHTML= "<button type='button' onclick=\"window.location='<? echo base_url(); ?>index.php/<? echo $GLOBALS['ruta']; ?>/trans/verificar_factura/"+factura_id+"'>Verificar y Salir</button>";
      $('#msg1').html("");
    } else {
      document.getElementById("fin").innerHTML="Error al registrar la factura";
    }
  }
}

  function get_cliente(cliente_id){
    if(cliente_id>0){
      $.post("<? echo base_url();?>index.php/ajax_pet/get_cliente/", { id: cliente_id},       //function that is called when server returns a value.
      function(data){
	  $('#datos_cliente').html(data);
      });
   }

  }
</script>
