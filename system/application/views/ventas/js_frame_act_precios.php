<script>
  $(document).ready(function() { 
<?php
    for($x=0;$x<count($coleccion);$x++){
	echo "var options$x = {". 
	"target: '#content$x'". 
	"};"; 

	echo "$('#fdetail$x').submit(function() {".
	"$(this).ajaxSubmit(options$x);".
	"return false;".
	"});\n";

	echo "$('#precio1$x').change(function() { \n".
	"$('#chk$x').attr('checked', true); \n".
	"$('#precio1$x').removeClass(\"subtotal\").addClass(\"modificado1\");".
	"}) \n";

	echo "$('#precio2$x').change(function() {  \n".
	"$('#chk$x').attr('checked', true); \n".
	"$('#precio2$x').removeClass(\"subtotal\").addClass(\"modificado1\");".
	"}) \n";
    }
    
    
?>
  });

  function sel_chk(){
    $('.chk').attr('checked', true);
  }
  function unsel_chk(){
    $('.chk').attr('checked', false);
  }

  function activar(){
      ;
    var tipo=$('#tipo').val();
    var inc=$('#incremento').val();
    if($("#incremento").val().length>0){
      renglon=<? echo count($coleccion); ?>;
    for(r=0;r<renglon;r++){
	if($('#chk'+r).attr('checked')== true ){
	    if(tipo==1){
	      val1=parseFloat($('#precio1'+r).val())+parseFloat(inc);
	      $('#precio1'+r).val(''+val1);
	      $('#precio1'+r).removeClass("subtotal").addClass("modificado");

	      val2=parseFloat($('#precio2'+r).val())+parseFloat(inc);
	      $('#precio2'+r).val(''+val2)
	      $('#precio2'+r).removeClass("subtotal").addClass("modificado");

	      

	    } else if (tipo==2){
	      val1=roundVal(parseFloat($('#precio1'+r).val()) * (100 + parseFloat(inc)) / 100);
	      $('#precio1'+r).val(''+val1)
	      $('#precio1'+r).removeClass("subtotal").addClass("modificado");

	      val2=roundVal(parseFloat($('#precio2'+r).val()) * (100 + parseFloat(inc)) / 100);
	      $('#precio2'+r).val(''+val2)
	      $('#precio2'+r).removeClass("subtotal").addClass("modificado");

	      

	    }
	}
    }     
    }else{
          alert('Escribe alguna cantidad para aplicar') 
       }
    
  }

  function send_detalle(){
    renglon=<? echo count($coleccion); ?>;
    for(y=0;y<renglon;y++){
	if($('#chk'+y).attr('checked')== true ){
		$('#cambio_global'+y).val(''+$('#global').val());
	    document.getElementById('b'+y).click();
	}
    }
    alert('Los nuevos precios se han guardado para los productos seleccionados, favor de notificar a las Sucursales');
  }
  
  function roundVal(val){
	var dec = 0;
	var result = Math.round(val*Math.pow(10,dec))/Math.pow(10,dec);
	return result;
}
</script>
