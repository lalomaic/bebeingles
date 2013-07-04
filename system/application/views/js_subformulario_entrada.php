<script>
<?php
echo <<<end
  $(document).ready(function() { 
  $($.date_input.initialize);
  $(".prod").sexyCombo({changeCallback: true});
  $("#date_input").date_input(); 

//Mostrar/ocultar columnas de las tablas del detalle
  $(".row_detail").hide();
    $("#fdetail<?php echo $renglon_adic;?>").fadeIn("4000");
    $("#table_row<?php echo $renglon_adic;?>").fadeIn("4000");
    $("#header").fadeIn("4000");
    $("#total").fadeIn("4000");
    $("#total1").fadeIn("4000");

$rows_pred;

    //opciones para el formulario principal
    var options = { 
        target:        '#out_form1',   // target element(s) to be updated with server response 
        beforeSubmit:  form_principal,  // pre-submit callback 
        success:       update_id, // post-submit callback 
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
echo "function update_id(){ \n";
echo "id = document.form1.id.value; \n ";
echo "for(x=0;x<$rows;x++){ \n ";
echo     "$('#precio_u'+x).focus(); \n ";
echo     "$('#precio_u'+x).blur(); \n ";
echo "} \n ";
echo     "$('#i0').focus(); \n ";
echo "} \n ";

for($t=0;$t<$rows;$t++){
  echo     "$('#fdetail$t').submit(function() {".
        "$(this).ajaxSubmit(options$t);".
        // always return false to prevent standard browser submit and page navigation
        "return false;".
    "});\n";
//Cantidad, productos, precio, iva y subtotal
//Echo disparadores de los select de los productos

//Cantidad
echo     "$('#cantidad$t').blur(function() {\n".
	 "calc($t);\n".
"});\n";

//Producto
 echo     "$('#producto$t').change(function() { \n".
 	  "get_iva($(this).val(), $t); \n".
 "}); ";

//Cantidad
echo     "$('#precio_u$t').blur(function() {\n".
	 "calc($t);\n".
"});\n";

//IVA
echo     "$('#tasa_imp$t').blur(function() {\n".
	 "calc($t);\n".
"});\n";
//Cantidad
echo     "$('#subtotal$t').blur(function() {\n".
	 "calc($t);\n".
"});\n";
}
?>
// changing date format to DD/MM/YYYY
 });
function get_iva(valor, linea) {
  if(valor > 0) { 
    $.post("<? echo base_url(); ?>index.php/ajax_pet/iva",{ enviarvalor: valor, linea: linea },
    function(data){
      $('#tasa_i'+linea).html(data);
      calc(linea);
    });
  }
}
function borrar_detalle(id, line){
    // post(file, data, callback, type); (only "file" is required)
    if(id>0){
    $.post("<? echo base_url();?>index.php/ajax_pet/borrar_detalle",{ enviarvalor: id, linea: line},  // create an object will all values
    //function that is called when server returns a value.
    function(data){
        $('#content'+line).html(data);
        $('#cantidad'+line).val('0');
        $('#prod'+line).val('0');
        $('#precio_u'+line).val('0');
        $('#iva'+line).val('0');
	calc(line);
    });
   }
}

   function calc(line){
    path1="document.fdetail"+line;
    unit=eval(path1+".unidades"+line+".value");
    producto=eval(path1+".producto"+line+".value");
    precio=eval(path1+".precio_u"+line+".value");
    imp=eval(path1+".iva"+line+".value");
    tot=document.t1.total;

    if(_IsNumber(unit) && producto>0 && _IsNumber(precio) && unit.length>0 && precio.length>0 && imp.length>0){
      price=parseFloat(precio.value);
      subt=eval(path1+".subtotal"+line);
      subt.value=roundVal(unit*precio);
      iva=1+eval(imp/100);
      siva=subt.value
      subt.value= roundVal(siva*iva);
      var colect=0;
      tot.value=0;
      for(var y=0;y<<? echo $rows; ?>;y++){
      sub=eval("document.fdetail"+y+".subtotal"+y+".value");
	 if(sub.length>0){
	  colect =  parseFloat(tot.value);
	  tot.value = colect +  parseFloat(eval("document.fdetail"+y+".subtotal"+y+".value"));
	 }
      }
      pedidof=eval(path1+".pr_facturas_id"+line);
      pedidof.value=document.form1.id.value;
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

function send_detalle(){
      document.getElementById('boton1').click();
  for(var y=0;y< <? echo $rows; ?>;y++){
   var id=parseFloat(eval("document.fdetail"+y+".pr_facturas_id"+y+".value"));
   var subtotal=parseFloat(eval("document.fdetail"+y+".subtotal"+y+".value"));
    if(id > 0 && subtotal>0){
      document.getElementById('b'+y).click();
    }
  }
  document.getElementById("fin").innerHTML='<button type="button" onclick="javascript:verificar()"/>Finalizar</button>';
    
}

function verificar(){
  var id=eval("document.form1.id.value");
  location.href="<? echo base_url();?>index.php/<? echo $ruta; ?>/trans/verificar_entrada/"+id;
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
</script>
