<script>
  $(document).ready(function() {


});

function send_detalle(){
	$('#boton_p').hide();
	$('#cerrar').hide();
	alert("Se esta procesando la entrada de productos a sus inventarios... Continuar");
	$('#msg1').html("<br/><img src='<? echo base_url(); ?>images/cargando.gif' width='50px'/><br/>Cargando...");
	document.form1.submit();

}
</script>
