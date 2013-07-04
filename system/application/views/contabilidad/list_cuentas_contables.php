<?php
//Construir Select cuentas principales
$select2[0] = "Todos";
if ($cuentas_select != false) {
    foreach ($cuentas_select->all as $row) {
        $y = $row->cta;
        $select2[$y] = $row->cta . " - " . $row->tag;
    }
}
?>
<h2><?php echo $title ?></h2>
<div align="left">
    <table style="margin: 0;text-align:center;">
        <tr>
            <td>
                <a href="<?php echo base_url() . "index.php/" . $ruta . "/" . $controller . "/" . $funcion . "/alta_cuenta_contable" ?>">
                    <img src="<?php echo base_url() . "images/add_cuenta_cont.png" ?>" width="30px" title="Alta de Cuenta contable" alt="Alta de Cuenta contable">
                    Alta de cuenta contable
                </a>
            </td>
        </tr>
    </table>
</div>
<?php
echo "<div align=\"center\"><b>Filtrado por Cuentas:";
echo form_dropdown("cuenta_id", $select2, $cta, 'id="cuenta_id"');
echo "</b></div>";
echo "<div align=\"center\"><b>Total de registros:" . count($cuentas->all) . "</b></div>";
if ($paginacion == true)
    echo $this->pagination->create_links();
?>
<script type="text/javascript"> 
    $(document).ready(function() {
	
        $('#cuenta_id').change(function() {
            pag=<? echo $pag; ?>;
            location.href="<?php echo base_url(); ?>index.php/contabilidad/poliza_c/formulario/list_cuentas_contables/"+pag+"/"+$(this).val();
        });
});
</script>
<table  class="listado" border="0" >
    <tr>
        <th>Id Cuenta C.</th>
        <th>Cuenta</th>
        <th>SubCuenta</th>
        <th>SubSubCuenta</th>
        <th>Sub-subSubcuenta</th>
        <th>Etiqueta</th>
        <th>Tipo cuenta</th>
        <th>Estatus</th>
        <th >Edicion</th>
    </tr>
    <?php

//Function for formating the date tags YYYY/MM/DD to DD/MM/YYYY
    function fecha_imp($date) {
        if ($date != '0000-00-00') {
            $fecha = substr($date, 0, 10);
            $hora = substr($date, 11, strlen($date));
            $new = explode("-", $fecha);
            $a = array($new[2], $new[1], $new[0]);
            if (strlen($hora) > 2) {
                return $n_date = implode("-", $a) . " Hora: " . $hora;
            } else {
                return $n_date = implode("-", $a);
            }
        } else {
            return "Sin fecha";
        }
    }

//Botones de Edicion
    $photo = "<img src=\"" . base_url() . "images/adobereader.png\" width=\"20px\" title=\"Factura\" border=\"0\">";
    $trash = "<img src=\"" . base_url() . "images/trash.png\" width=\"20px\" title=\"Deshabilitar Cuenta Contable\" border=\"0\">";
    $delete = "";
    $edit = "";

    if ($cuentas != false) {
        foreach ($cuentas->all as $row) {
            //Permiso de Borrado en el 2 Byte X1X
            if (substr(decbin($permisos), 1, 1) == 1) {
                $delete = "<a href=\"" . base_url() . "index.php/" . $ruta . "/" . $controller . "/" . $funcion . "/" . $subfuncion . "/borrar_cuenta_contable/" . $row->id . " \" onclick=\"return confirm('Estas seguro que deseas deshabilitar la cuenta contable?');\">$trash</a>";
            }
            //echo "--".decbin($permisos);
            if (substr(decbin($permisos), 2, 1) == 1) {
                $edit = "<img src=\"" . base_url() . "images/edit.png\" width=\"25px\" title=\"Editar Registro\" border=\"0\">";
            }
            if ($row->estatus_general_id == 1)
                $estatus = "Habilitada";
            else
                $estatus="Cancelada";

            echo "<tr class=\"row\">
            <td align=\"center\" class=\"detalle\">$row->id</td>
                    <td align=\"center\">$row->cta</td>
                    <td align=\"center\">$row->scta</td>
                    <td>$row->sscta</td>
                    <td>$row->ssscta</td>
                    <td>$row->tag</td>
                    <td>$row->ctipo_cuenta_contable_tag</td>
                    <td>$estatus</td>
                    <td><a href=\"" . base_url() . "index.php/" . $ruta . "/" . $controller . "/" . $funcion . "/" . $subfuncion . "/editar_cuenta_contable/" . $row->id . "\">$edit $delete</a></td></tr>";
        }
    }
    ?>
    </table>    
<?php
    if ($paginacion == true)
        echo $this->pagination->create_links();
?>
