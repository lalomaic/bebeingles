<script>
<?php
echo <<<end
$(document).ready(function() {
	$($.date_input.initialize);
	$('#subform_detalle').hide()
	$('#subtotal').val('0');
	$('#iva').val('0');
	$('#total').val('0');
	$('.invisible').hide();

  //opciones para el formulario principal
  var options = {
	  target:        '#out_form1',   // target element(s) to be updated with server response
					beforeSubmit:  form_principal,  // pre-submit callback
  };


  //Redirecciones a los contenedores de los estatus de los detalles y envio mediante ajax
  $('#form1').submit(function() {
	  $(this).ajaxSubmit(options);
  // always return false to prevent standard browser submit and page navigation
  return false;
  });
end;
$base=base_url();
$n=count($pr_detalle)+$rows;
for($t=0;$t<$n;$t++) {

echo <<<END
	$('#producto_id$t').val('0');
	$("#producto_drop$t").autocomplete('{$base}index.php/ajax_pet/get_productos_numeracion/', {
		extraParams: {pid: function() { return $("#proveedores").val(); }, mid: function() { return $("#cmarca_id").val(); }},
			minLength: 3,
			multiple: false,
			noCache: true,
			parse: function(data) {
				return $.map(eval(data), function(row) {
					return {
						data: row,
						value: row.id,
						value1: row.nid,
						result: row.descripcion
					}
				});
			},
			formatItem: function(item) {
				return format(item);
			}
	  }).result(function(e, item) {
			$("#producto_id$t").val(""+item.id);
			$("#producto_numeracion$t").val(""+item.nid);
	  });
END;

  echo "var options$t = {".
      "target: '#content$t'".
      "}; \n";

  echo   "$('#fdetail$t').submit(function() {".
        "$(this).ajaxSubmit(options$t); \n".
        // always return false to prevent standard browser submit and page navigation
        "return false;".
    "});\n";

//Cantidad
  echo     "$('#cantidad$t').blur(function() {\n".
		   "calc($t);\n".
        "if($(this).val()>0){ \n ".
        "$('#row".($t+1)."').show(); \n".
        "$('#row".($t+1)."').removeClass('invisible').addClass('visible'); \n ".
	  "}; \n".
"});\n";

		echo "$('#cantidad$t').keyup(function() { \n".
		"contar_pares();\n".
		"\n });";

		//Cantidad
		echo "$('#precio_u$t').blur(function() {\n".
				"calc($t);\n".
			"});\n";
		echo "$('#tasa_imp$t').blur(function() {\n".
			 "calc($t);\n".
			 "});\n";
}
?>
   });
   function format(r) {
	   return r.descripcion;
   }
function send_detalle(){
      $('#boton_p').hide();
      alert("La actualización del pedido de compra se esta procesando... Continuar");
      $('#msg1').html("<br/><img src='<? echo base_url(); ?>images/cargando.gif' width='50px'/><br/>Cargando...");
      document.getElementById('boton1').click();
  for(var y=0;y< <? echo (count($pr_detalle)+$rows-1); ?>;y++){
	eval("document.fdetail"+y+".pr_pedidos_id"+y+".value=document.form1.id.value");
	var id=parseFloat(eval("document.fdetail"+y+".id"+y+".value"));
	var subtotal=parseFloat(eval("document.fdetail"+y+".subtotal"+y+".value"));

	if(subtotal>0){

      document.getElementById('b'+y).click();
    }
  }
  document.getElementById("fin").innerHTML='<button type="button" onclick="javascript:verificar()"/>Finalizar</button>';
  $('#msg1').html("");
}

function verificar(){
  var id=eval("document.form1.id.value");
  location.href="<? echo base_url();?>index.php/<? echo $ruta; ?>/trans/verificar_pedido_compra/"+id;
}

function mostrar_subform(){
  $('#subform_detalle').show()
}

  function act_parent_id(obj, line){
    //Definir el text input a actualiar
    eval(obj+".pr_pedidos_id"+line+".value=document.form1.id.value");
  }

  function otra_accion(valor, linea){
    //Vacio
  }

  function otra_accion1(valor, linea){
    get_iva(valor,linea);
  }

function get_iva(valor, linea) {
  if(valor > 0) {
    $.post("<? echo base_url(); ?>index.php/ajax_pet/iva1",{ enviarvalor: valor},
    function(data){
      $('#tasa_imp'+linea).val(data);
//      $('#subtotal'+linea).format({format:"#,###.00", locale:"us"});
    });
  }
}

function calc(line){
	path1="document.fdetail"+line;
	$('#iva_total').val('0');
	unit=parseFloat($('#cantidad'+line).val());
	precio=parseFloat($('#precio_u'+line).val());
	imp=parseFloat($('#tasa_imp'+line).val());
	tot=document.t1.total;
	iva_total=document.t1.iva_total;

	if(_IsNumber(unit) && _IsNumber(precio)  && precio>0 ){
		//alert(precio)
		subt=eval(path1+".subtotal"+line);
		subt.value=roundVal(parseFloat(unit*precio));
		iva=1+eval(imp/100);
		var next=line+1;
		var colect=0;
		tot.value=0;
		for(var y=0;y<<? echo $rows; ?>;y++){
			sub=parseFloat($('#subtotal'+line).val());
			if(sub>0){
				colect =  parseFloat(tot.value);
				colect_iva =  parseFloat(iva_total.value);
				iva_total.value=roundVal(parseFloat(colect_iva) +  (imp/100) * parseFloat($('#precio_u'+y).val()) * parseFloat($('#cantidad'+y).val()) / iva);
				tot.value = roundVal(colect +  parseFloat(eval("document.fdetail"+y+".subtotal"+y+".value")));
			}
		}
		sub=roundVal(parseFloat(tot.value)) - roundVal(parseFloat(iva_total.value));
		$('#subtotal').val(''+sub);
		document.t1.subtotal1.value=sub;
		act_parent_id(path1,line);
	} else {
		subt=eval(path1+".subtotal"+line);
		subt.value=0;;
	}
}

function roundVal(val){
	var dec = 2;
	var result = Math.round(val*Math.pow(10,dec))/Math.pow(10,dec);
	return result;
}
function form_principal(){
<?php
    //imprime la validacion de los campos anteriormente seleccionada
    foreach ($validation as $row){
      echo "$('#{$row['id']}').validator({\n".
	$row['arguments']."\n".
      "  correct: function() {\n".
      "		     return true;\n".
      "	    },\n".
      "	    error: function() {\n".
      "		    $('#validation_result').text('{$row['response_false']}');\n".
      "	    }\n".
      "    });\n";
    }

    //Genera el bifurcador para liberar el envio del formulario
    $n=count($validation);
    if($n>0){
      $condicion="if(";
      $y=0;
      foreach ($validation as $row){
	$condicion .="$('#{$row['id']}').validator('validate') == true ";
	$y+=1;
	if($y<$n){
	  $condicion .="&& ";
	}
      }
      $condicion .="){ \n return true;\n } else {\n return false; }\n";
//      $condicion .="){ \n document.form1.submit();\n } else {\n return false; }\n";
      echo $condicion;
    } else {
      //echo true;
    }
?>
}
function borrar_detalle(id, line){
    // post(file, data, callback, type); (only "file" is required)
    if(id>0){
    $.post("<? echo base_url();?>index.php/ajax_pet/borrar_detalle",{ enviarvalor: id, linea: line},  // create an object will all values
    //function that is called when server returns a value.
    function(data){
        $('#content'+line).html(data);
        $('#cantidad'+line).val('0');
        $('#producto'+line).val('0');
        $('#precio_u'+line).val('0');
        $('#iva'+line).val('0');
	calc(line);
    });
   }
}
function sin_iva(){
	$('.subtotal_iva').val('0');
	for(x=0;x<<?=$n?>;x++){
		calc(x);
	}
}
function con_iva(){
	$('.subtotal_iva').val('16');
	for(x=0;x<<?=$n?>;x++){
		calc(x);
	}

}

function contar_pares(){
	$('#pares_totales').val('0');
	pares=0;
	for(x=0;x<<? echo count($pr_detalle)+$rows;?>;x++){
		celda=parseFloat($('#cantidad'+x).val());
		pares=pares+celda;
	}
//	alert();
	$('#pares_totales').val(pares);
}

</script>
