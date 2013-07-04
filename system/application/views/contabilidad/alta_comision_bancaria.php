<script>
    function get_editar(id){
         var id_c = $(document.createElement("input"));
                            id_c.attr("type", "hidden");
                            id_c.attr("name", "id");
                            id_c.attr("id", "id");
                            $('#comisiones_tabla').append(id_c);
        
        $.post("<? echo base_url(); ?>index.php/ajax_pet/get_comisiones_bancarias",{ id: id },  // create an object will all values
        function(data){
           var p=eval("("+data+")");
            $('#id').val(p.id);
            $('#banco_id').val(p.banco_id);
            $('#debito').val(p.debito);
            $('#credito_0m').val(p.credito0);
            $('#credito_3m').val(p.credito3);
            $('#credito_6m').val(p.credito6);
            $('#credito_9m').val(p.credito9);
            $('#credito_12m').val(p.credito12); 
        
        });
    }
</script>

<?php
//tipos de bancos
$select2[0] = "Elija";
if ($bank_filtro != false) {
    foreach ($bank_filtro->all as $row) {
        $y = $row->id;
        $select2[$y] = $row->tag;
    }
}
$debito = array(
    'name' => 'debito',
    'size' => '10',
    'value' => '',
    'id' => 'debito',
);

$credito_0 = array(
    'name' => 'credito_0m',
    'size' => '10',
    'value' => '',
    'id' => 'credito_0m',
);

$credito_3 = array(
    'name' => 'credito_3m',
    'size' => '10',
    'value' => '',
    'id' => 'credito_3m',
);
$credito_6 = array(
    'name' => 'credito_6m',
    'size' => '10',
    'value' => '',
    'id' => 'credito_6m',
);
$credito_9 = array(
    'name' => 'credito_9m',
    'size' => '10',
    'value' => '',
    'id' => 'credito_9m',
);
$credito_12 = array(
    'name' => 'credito_12m',
    'size' => '10',
    'value' => '',
    'id' => 'credito_12m',
);

//Titulo
echo "<h2>$title</h2>";

//Load validacion
$this->load->view('validation_view');


//Abrir Formulario
$atrib = array('name' => 'form1', 'id' => 'form1');
echo form_open($ruta . '/trans/act_comisiones_bancarias', $atrib) . "\n";
echo form_fieldset('<b>Datos de Familia de Productos</b>') . "\n";
//Mensajes de validacion
echo '<div id="validation_result" class="result_validator" align="center" width="200px"></div>';

echo "<table width=\"80%\" class='form_table' id='comisiones_tabla'>";
$img_row = "" . base_url() . "images/table_row.png";

//Campos del Formulario
echo "<tr><td class='form_tag'><label for=\"estatus\">Banco:</label></td><td class='form_input'>";
echo form_dropdown('banco_id', $select2,'', "id='banco_id'");
echo "</td></tr>";

echo "<tr><td class='form_tag'><label for=\"debito\">Debito:</label></td>
            <td class='form_input'>";
echo form_input($debito);
echo "</td><td class='form_tag'><label for=\"credito0\">Credito 0 meses:</label></td><td class='form_input'>";
echo form_input($credito_0);
echo "</td><td class='form_tag'><label for=\"credito3\">Credito 3 Meses:</label></td><td class='form_input'>";
echo form_input($credito_3);
echo "</td></tr>";
echo "<tr><td class='form_tag'><label for=\"credito6\">Credito 6 Meses:</label></td>
            <td class='form_input'>";
echo form_input($credito_6);
echo "</td><td class='form_tag'><label for=\"tag\">Credito 9 meses:</label></td><td class='form_input'>";
echo form_input($credito_9);
echo "</td><td class='form_tag'><label for=\"fecha_alta\">Credito 12 Meses:</label></td><td class='form_input'>";
echo form_input($credito_12);
echo "</td></tr>";

//Cerrar el Formulario
echo "<tr><td colspan='6' class=\"form_buttons\">";
echo form_reset('form1', 'Borrar');
echo "<button type='button' onclick=\"window.location='" . base_url() . "index.php/inicio/acceso/" . $ruta . "/menu'\">Cerrar sin guardar</button>";
echo form_close();

//Permisos de Escritura byte 1
if (substr(decbin($permisos), 0, 1) == 1) {
    echo '<button type="submit">Guardar Registro</button>';
}
echo '</td></tr></table>';
echo form_fieldset_close();

//Link al listado
?>
<!--LISTADO DE COMISION BANCARIA-->
<?php
echo "<div align=\"center\"><b>Total de registros:" . $total_registros . "</b></div>";
?>

<table class="listado" border="0">
    <tr>
        <th>Id</th>
        <th>BANCO</th>
        <th>DEBITO</th>
        <th>CREDITO 0 MESES</th>
        <th>CREDITO 3 MESES</th>
        <th>CREDITO 6 MESES</th>
        <th>CREDITO 9 MESES</th>
        <th>CREDITO 12 MESES</th>
        <th>Edición</th>
    </tr>
    <?php
    //Botones de Edicion
    $img_row = "" . base_url() . "images/table_row.png";
    $trash = "<img src=\"" . base_url() . "images/trash.png\" width=\"25px\" title=\"Eliminar Registro\" border=\"0\">";
    $delete = "";
    $edit = "";
    if ($comisiones_list != false) {
        foreach ($comisiones_list->all as $row) {
            //Permiso de Borrado en el 2 Byte X1X

            $delete = "<a href=\"" . base_url() . "index.php/" . $ruta . "/" . $controller . "/" . $funcion . "/" . $subfuncion . "/borrar_cuenta/" . $row->id . " \" onclick=\"return confirm('¿Estas seguro que deseas borrar la cuenta Bancaria?');\">$trash</a>";

            //echo "--".decbin($permisos);

            $edit = "<img src=\"" . base_url() . "images/edit.png\" width=\"25px\" title=\"Editar Registro\" border=\"0\">";

            echo "<tr background=\"$img_row\">
    <td>$row->id</td>
   <td>$row->banco</td>
   <td>$row->debito</td>
   <td>$row->credito_0m</td>
  <td>$row->credito_3m</td>
  <td>$row->credito_6m</td>
  <td>$row->credito_9m</td>
 <td>$row->credito_12m</td>
 <td><a onclick=\"get_editar($row->id);\">$edit</a>$delete</td></tr>";
        }
    } else {
        echo "SIN REGISTROS";
    }

    echo "</table></center>";
    echo $this->pagination->create_links();
    ?>
