<script>
$(document).ready(function() {
	$($.date_input.initialize);
	$('#cmarca_id').val('');
	  $('#marca_drop').autocomplete('<?php echo base_url(); ?>index.php/ajax_pet/get_marcas_ajax/', {
			extraParams: {pid: function() { return 0 } },
			minLength: 3,
			multiple: false,
			noCache: true,
		  parse: function(data) {
			  return $.map(eval(data), function(row) {
				  return {
					  data: row,
					  value: row.id,
					  result: row.descripcion
				  }
			  });
		  },
		  formatItem: function(item) {
			  return format(item);
		  }
	  }).result(function(e, item) {
		  $("#cmarca_id").val(""+item.id);
	  });

$('#producto_id').val('0');
	$("#producto_drop").autocomplete('<?=base_url()?>index.php/ajax_pet/get_ajax_productos', {
		extraParams: {mid: function() { return $("#cmarca_id").val(); }},
			minLength: 3,
			multiple: false,
			noCache: true,
			parse: function(data) {
				return $.map(eval(data), function(row) {
					return {
						data: row,
						value: row.id,
						result: row.descripcion
					}
				});
			},
			formatItem: function(item) {
				return format(item);
			}
	  }).result(function(e, item) {
			$("#producto_id").val(""+item.id);
	  });
});
	function format(r) {
	   return r.descripcion;
   }
	  
function cambiaredo(objetocheckbox,num){
    if(!objetocheckbox.checked){
        eval("document.form1.horai"+num+".disabled=true");
        eval("document.form1.mini"+num+".disabled=true");
        eval("document.form1.horaf"+num+".disabled=true");
        eval("document.form1.minf"+num+".disabled=true");
    }
    else{
        eval("document.form1.horai"+num+".disabled=false");
        eval("document.form1.mini"+num+".disabled=false");
        eval("document.form1.horaf"+num+".disabled=false");
        eval("document.form1.minf"+num+".disabled=false");
    }
}

function validar(total) {
if((eval("document.form1.fecha_inicio.value"))==""){
    alert("Introduzca la fecha de inicio de la promoci n");
    eval("document.form1.fecha_inicio.focus()");
    return (false);
}
if((eval("document.form1.fecha_final.value"))==""){
    alert("Introduzca la fecha de Terminaci n de la promoci n");
    eval("document.form1.fecha_final.focus()");
    return (false);
}

var bandera=0;
for(var i=1;i<=total;i++){
    if(eval("document.form1.espacios"+i+".checked")){
        bandera=1;
    }
}
if(bandera==0){
    alert("Por favor debe seleccionar al menos una sucursal donde sera valida la promoci n");
    eval("document.form1.espacios1.focus()")
    return (false);
}
bandera=0;
for(var j=1;j<=7;j++){
    if(eval("document.form1.dia"+j+".checked")){
        bandera=1;
    }
}
if (bandera==0){
    alert("Por favor debe seleccionar al menos un d a en que ser  valida la promoci n");
    eval("document.form1.dia1.focus()")
    return (false);
}
if(eval("isNaN(document.form1.limite.value)")){
    alert("Por favor inserta solo caracteres numericos");
    eval("document.form1.limite.focus()");
    return (false);
}
if(eval("isNaN(document.form1.precio.value)")){
    alert("Por favor inserta solo caracteres numericos");
    eval("document.form1.precio.focus()");
    return (false);
}
if(eval("document.form1.precio.value")<=0){
    alert("Por favor introduzca un precio mayor de cero");
    eval("document.form1.precio.focus()");
    return (false);
}
eval("document.form1.submit()");
}


</script>
