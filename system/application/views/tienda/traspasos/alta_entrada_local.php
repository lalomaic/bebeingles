<style>
    fieldset{
        width: 940px;
        margin: auto;
        border-radius: 3px;
    }
</style>
<?php

//Titulo
echo "<h2>$title en $tienda</h2>";
//Abrir Formulario
$atrib=array('name'=>'form1', 'id'=>"form1");
echo form_open($ruta.'/trans/alta_entrada_traspasos', $atrib) . "\n";
echo form_fieldset('<b>Alta de entrada local </b>') . "\n";
echo "<table width=\"80%\" class='form_table'>";

//Campos del Formulario
echo "<tr><td class='form_tag' colspan='2'><label for=\"empresa\">Empresa:</label> $empresa->razon_social </td></tr>";

echo "<tr><td class='form_tag'><label for=\"ubicacion_salida_id\">Almacén/Tienda Recibe: </label>"; 
echo $tienda;
echo "</td><td class='form_input'></td></tr>";

//Cerrar el Formulario
echo "<tr><td class=\"form_buttons\"></td><td class=\"form_buttons\">";
echo "<div id=\"out_form1\">";
echo form_hidden("traspaso_id", "$traspaso->id");

echo "</div>";
echo '</td></tr></table>';
echo form_fieldset_close();
?>
<br/>
<div id="subform_detalle">
<table width="960" class="row_detail_pred" id="header">
  <tr>
      <th class='detalle_header'>Cantidad</th>
      <th class='detalle_header'>Producto </th>
  </tr>
<?php
//Imprimir valores actuales
$r=0;
if($traspasos_salidas!=false){
    $suma=0;
    //print_r(array_values($traspasos_salidas->all));
    foreach($traspasos_salidas->all as $linea){
        echo "<tr>".
                "<td class='detalle' width=\"10\">".
                "<input type='hidden' name='entrada_id$r' id='llave$r' value='$linea->id'>".
                "$linea->cantidad".        
                "<td class='detalle' style='text-align:center;' width=\"50\">".
                "$linea->descripcion # $linea->numero".
                "</td>".
                "</td>";
    }
}

echo form_close();
?>
<table width="870" class="row_detail_prev" id="total">
  <tr>
    <th class='detalle_header' width="730" colspan="2" align="right">
      <?php
      echo "<button type='button' onclick=\"window.location='".base_url()."index.php/inicio/acceso/".$ruta."/menu'\" id='cerrar'>Cerrar sin guardar</button>";
      if(substr(decbin($permisos), 0, 1)==1)
		echo '<button type="button" onclick="javascript:send_detalle()" id="boton_p">Registrar entrada local</button><span id="msg1"></span>';
      ?>
      <span id="fin"></span>
    </th>
  </tr>
</table>
</div>
<?php
$this->load->view('tienda/js_subformulario_entrada_local');
//Link al listado
echo "<div align=\"left\"><a href=\"".base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_transferencias\"><img src=\"".base_url()."images/consumo.png\" width=\"50px\" title=\"Listado de Pedidos de Traspaso\"></a></div>";
?>