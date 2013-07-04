<script>
    $(document).ready(function() {
           
        $('#empresa').val('');
        $('#empresa_drop').autocomplete('<?php echo base_url(); ?>index.php/ajax_pet/get_empresa_ajax/', {
            extraParams: {pid: 0 },
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
            $("#empresa").val(""+item.id);
        });
         
          
        $('#cproveedor_id').val('');
        $('#proveedor_drop').autocomplete('<?php echo base_url(); ?>index.php/ajax_pet/get_proveedores_ajax_autocomplete/', {
            extraParams: {pid: 0 },
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
            $("#cproveedor_id").val(""+item.id);
        });
	 
          
          
          

        $('#cliente').val('');
        $('#cliente_drop').autocomplete('<?php echo base_url(); ?>index.php/ajax_pet/get_clientes_ajax/', {
            //extraParams: {pid: function() { return $("#proveedor").val(); } },
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
            $("#cliente").val(""+item.id);
        });
          

   	  
    }); 
    function format(r) {
        return r.descripcion;
    }
    
</script>

<?php
//Construir Tipos de Cuentas Bancarias
$select1[0] = "Elija";
if ($tipos_cuentas_bancarias != false) {
    foreach ($tipos_cuentas_bancarias->all as $row) {
        $y = $row->id;
        $select1[$y] = $row->tag;
    }
}

$select2[0] = "Elija";
if ($bank_filtro != false) {
    foreach ($bank_filtro->all as $row) {
        $y = $row->id;
        $select2[$y] = $row->tag;
    }
}

//Inputs


$numero_sucursal = array(
    'name' => 'numero_sucursal',
    'size' => '4',
    'value' => '',
    'id' => 'numero_sucursal',
);
$nombre_sucursal = array(
    'name' => 'nombre_sucursal',
    'size' => '30',
    'value' => '',
    'id' => 'nombre_sucursal'
);

$numero_cuenta = array(
    'name' => 'numero_cuenta',
    'size' => '15',
    'value' => '',
    'id' => 'numero_cuenta'
);
$clabe = array(
    'name' => 'clabe',
    'size' => '15',
    'value' => '',
    'id' => 'clabe',
);
?>
<script>
  $(document).ready(function() {
    $('#empresa_drop').keyup(function() {
            $('#proveedor_drop').hide('slow');
            $('#cliente_drop').hide('slow');
            $('#proveedor_drop').val('');
            $('#cliente_drop').val('');
        });

        $('#proveedor_drop').keyup(function() {
            $('#empresa_drop').hide('slow');
            $('#cliente_drop').hide('slow');
            $('#empresa_drop').val('');
            $('#cliente_drop').val('');
        });

        $('#cliente_drop').keyup(function() {
            $('#proveedor_drop').hide('slow');
            $('#empresa_drop').hide('slow');
            $('#proveedor_drop').val('');
            $('#empresa_drop').val('');
        });
  
        $('#restablecer').click(function(){
            $('#proveedor_drop').show('slow');
            $('#empresa_drop').show('slow');
            $('#cliente_drop').show('slow');
             $('#proveedor_drop').val('');
            $('#empresa_drop').val('');
            $('#cliente_drop').val('');
        });
  });
</script>

<?php
//Titulo
echo "<h2>$title</h2>";
$this->load->view('validation_view');

//Abrir Formulario
$atrib=array('name'=>'form1','id'=>'form1');
echo form_open($ruta . '/trans/act_cuenta_bancaria', $atrib) . "\n";
echo form_fieldset('<b>Datos de la Cuenta Bancaria</b>') . "\n";
echo '<div id="validation_result" class="result_validator" align="center" width="200px"></div>';

echo "<table width=\"80%\" class='form_table'>";
$img_row = "" . base_url() . "images/table_row.png";


//Campos del Formulario
echo "<tr><td class='form_tag'><label for=\"banco\">Nombre del Banco:  </label><br/>";
echo form_dropdown('banco', $select2);echo "<a href=\"".base_url()."index.php/".$ruta."/pagos_c/".$funcion."/alta_bancos\">Registrar Banco</a>";


echo "</td><td class='form_tag'><label for=\"nombre_sucursal\">Nombre de la Sucursal Bancaria:</label><br/>";
echo form_input($nombre_sucursal);

echo "</td><td class='form_tag'><label for=\"numero_sucursal\">Número de Sucursal:</label><br/>";
echo form_input($numero_sucursal);
echo "</td></tr>";

echo "<tr><td class='form_tag'><label for=\"numero_cuenta\">Numero de Cuenta:</label><br/>";
echo form_input($numero_cuenta);

echo "</td><td class='form_tag'><label for=\"clabe\">Clabe Interbancaria:</label><br/>";
echo form_input($clabe);

echo "</td><td class='form_tag'><label for=\"tipos_cuentas_bancarias\">Tipo de Cuenta Bancaria:</label><br/>";
echo form_dropdown('ctipo_cuenta_id', $select1);
echo "</td></tr>";

echo "<tr><td class='form_tag' colspan='4'><label for=\"empresas\">La cuenta le pertenece a la Empresa:</label><br/>";
echo "<input type='hidden' name='empresa' id='empresa' value='' size=\"3\"><input id='empresa_drop' class='marca' value='' size='20'></b><br/>";
echo "</td></tr>";

echo "<tr><td class='form_tag' colspan='4'><label for=\"proveedores\">Ó al Proveedor: </label><br/>";
echo "<input type='hidden' name='cproveedor_id' id='cproveedor_id' value='0' size=\"3\"><input id='proveedor_drop' class='proveedor' value='' size='30'></b><br/>";
echo "</td></tr>";

//Cerrar el Formulario
echo "<tr><td class='form_tag' colspan='4'><label for=\"clientes\">Ó al Cliente: </gabela><br/>";
echo "<input type='hidden' name='cliente' id='cliente' value='0' size=\"3\"><input id='cliente_drop' class='cliente' value='' size='20'></b><br/>";
echo "<input type='hidden' name='id' id='id' value='0' size=\"3\"></td></tr>";
echo "<tr><td class='form_tag' colspan='4'><a href='#' id='restablecer'>Restablecer</a><br/>";
echo "</td></tr><tr><td><label for=\"clientes\">Utilizar como terminal Bancaria de Punto de Venta: </label><br/>
 <input type='checkbox' name='terminal' id='terminal' ></td></tr>";

echo "<tr><td colspan='8' class=\"form_buttons\">";
echo form_reset('form1', 'Borrar', 'id="borrar"');
echo "<button type='button' onclick=\"window.location='" . base_url() . "index.php/inicio/acceso/" . $ruta . "/menu'\">Cerrar sin guardar</button>";

//Permisos de Escritura byte 1
if (substr(decbin($permisos), 0, 1) == 1) {
    echo '<button type="submit">Guardar Registro</button>';
}
echo '</td></tr></table>';
echo form_fieldset_close();
echo form_close();
//Link al listado
echo "<div align=\"left\"><a href=\"" . base_url() . "index.php/" . $ruta . "/" . $controller . "/" . $funcion . "/list_cuentas_bancarias\"><img src=\"" . base_url() . "images/adduser.png\" width=\"50px\" title=\"Listado de Cuentas Bancarias\"></a></div>";
?>

