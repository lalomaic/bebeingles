<style>
    .invisible {
        display: none;
    }
</style>

<h2><?= $title ?></h2>
<div class="row">
    <div class="seven columns">

    </div>
    <div class="five columns">
        <!-- Basic Button Groups -->
        <ul class="button-group radius">
            <li>
                <a href="<?= base_url()."index.php/tienda/tienda_c/alta_entrada_local"?>" class="button radius small">
                    Alta Entrada
                </a>
            </li>
            <li>
                <a href="<?= base_url()."index.php/tienda/tienda_c/list_transferencias"?>" class="button radius small">
                    Traspasos recibidos
                </a>
            </li>
            <li>
                <a href="<?= base_url()."index.php/tienda/tienda_c/list_salida_traspasos"?>" class="button radius small">
                    Traspasos enviados
                </a>
            </li>
        </ul>
    </div>
</div>

<?php
$url_form=base_url()."index.php/".$ruta."/trans/alta_salida_traspasos";
//Inputs
$this->load->view('tienda/js_alta_salida_tras_tienda');
//Abrir Formulario
echo '<div id="validation_result" class="result_validator" align="center" width="200px"></div>';
$atrib=array('name'=>'form1', 'id'=>"form1");
echo form_open($ruta.'/trans/alta_traspaso', $atrib) . "\n";
$field_attr = array('style' => 'width:80%;margin:auto');
echo form_fieldset('<b>Pedido de Traspaso</b>', $field_attr) . "\n"; 
echo "<table class='form_table'>";

//Campos del Formulario
if(isset($espacio_tag)){
echo "<tr>
        <td class='form_tag'><label for=\"empresa\">Empresa:</label></td>
        <td>$empresa_tag->nombre_corto</td>
    </tr>";
echo "<tr>
        <td class='form_tag'><label for=\"empresa\">Espacio envia:</label></td>
        <td>$espacio_tag</td>
    </tr>";
}
echo form_hidden("espacio_envia", $espacio_fisico_id);

echo "<tr><td class='form_tag'><label for=\"ubicacion_salida_id\">Almacen/Tienda solicitante: </label></td><td>"; 
echo form_dropdown('ubicacion_salida_id', $espacios_fisicos, "$ubicacion_entrada_id", "id='ubicacion_salida'");
echo "</td></tr>";

//Cerrar el Formulario
echo "<tr><td colspan='2' class=\"form_buttons\">";
echo "<div id=\"out_form1\">";
//echo form_hidden("traspasos_id", "$traspaso->id");
  echo '<button type="submit" id="boton1">Paso 2. Detalles del Traspaso</button>'; 
echo form_hidden("id", "");
echo "</div>";
echo '</td></tr></table>';
echo form_fieldset_close(); 
echo form_close(); 
?>

<div id="subform_detalle" class="invisible" style="width: 80%;margin:auto" >
<hr/>
<table id="header" width="100%">
    <tr>
        <th  style='width:250px;' class='detalle_header'>Codigo de barras</th>
        <th class='detalle_header'>CLAVE - Producto - Presentacion U. Med</th>
        <th  style='width:150px;' class='detalle_header'>Cantidad</th>
    </tr>
<?php
//Imprimir valores actuales
$r=0;
$suma=0;
  for($x=$r;$x<500;$x++){
    $cla = $x > 10 ? "invisible" : "";
    echo "<tr class='$cla' id='row$x'>";
    echo "<td><input type=\"text\" name=\"cod_bar$x\" value='0' id=\"cod_bar$x\"></td>";
    echo "<td>"; 
    echo "<input id='pd$x'class='productos' line='$x' type='text'/>".
            "<input type='hidden' id='producto_id$x'>";
    echo "</td>";
    echo "<td><input type=\"text\" name=\"unidades$x\" value='0' id=\"cantidad$x\"></td></tr>";
    
  //}

}
?>
</table>
<table style="width: 100%;margin: auto;" class="row_detail_prev" id="total">
  <tr>
    <th class='detalle_header' width="700" align="center">
<?php
echo "<button type='button' onclick=\"open.window.location='".base_url()."index.php/inicio/acceso/".$ruta."/menu'\">Cerrar</button>"; 
if(substr(decbin($permisos), 0, 1)==1){
  echo '<button type="button" onclick="send_detalles()" id="boton_p">Traspasar</button><span id="msg1"></span>';
}
?>
  <hr/>
<span id="fin"></span><br>Al Guardar los detalles del Traspaso verifique que se han guardado adecuadamente cada uno de ellos.</th>
  </tr>
</table>
</div>
<br/>