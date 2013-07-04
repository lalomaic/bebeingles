<div align="center">
    <b>Total de registros: <?php echo $total_registros; ?></b>
</div>
<?php echo $this->pagination->create_links();?>
<table  class="listado" border="0">
    <tr>
        <th>Id</th>
        <th>Nombre</th>
        <th>Apellido Paterno</th>
        <th>Apellido Materno</th>
        <th>Puesto</th>
        <th>Espacio Físico</th>
        <th>Estatus</th>
        <th>Edición</th>
    </tr>
    <?php
    $img_row = base_url() . "images/table_row.png";
    $img_edit = base_url() . "images/edit.png";
    $img_del = base_url() . "images/trash.png";
    $link_del = $link_edit = "";
    if (substr(decbin($permisos), 1, 1) == 1)
        $link_del = '<a onClick="return confirm(\'¿Estas seguro que deseas deshabilitar el empleado?\')" href="' . site_url("/$ruta/$controller/$funcion/$subfuncion/borrar_empleado/__id__") . '" title="desabilitar"><img src="' . $img_del . '" height="25px" width="25px"></a>';
    if (substr(decbin($permisos), 2, 1) == 1)
        $link_edit = '<a href="' . site_url("/$ruta/$controller/$funcion/$subfuncion/editar_empleado/__id__") . '" title="editar"><img src="' . $img_edit . '" height="25px" width="25px"></a>';
    foreach ($empleados as $empleado) {?>
        <tr background='<?php echo $img_row?>'>
            <td><?php echo $empleado->id?></td>
            <td><?php echo $empleado->nombre?></td>
            <td><?php echo $empleado->apaterno?></td>
            <td><?php echo $empleado->amaterno?></td>
            <td><?php echo $empleado->puesto?></td>
            <td><?php echo $empleado->espacio?></td>
            <td><?php echo $empleado->estado?></td>
            <td>
                <?php echo str_ireplace("__id__", $empleado->id, $link_edit);?>
                <?php echo str_ireplace("__id__", $empleado->id, $link_del);?>
            </td>
        </tr>
    <?php }?>
</table>
<?php echo $this->pagination->create_links();
