<script type="text/javascript">
    $(document).ready(function(){
        $("#error").hide();
        $(".modal_upload").fancybox({
            'width' : '45%',
            'height' : '80%',
            'autoScale' : false,
            'transitionIn' : 'fade',
            'transitionOut' : 'fade',
            'titlePosition' : 'over',
            'type' : 'iframe'
        });
        $(".filtros").change(function(){ 
            var showin;
            if($("#inactivos").is(":checked"))
                showin = "showin";
            else
                showin = "notshowin";
            window.location='<?php echo base_url(); ?>index.php/nomina/empleado_c/formulario/list_empleados_inactivos/' + $(this).attr('id') + '/' + $(this).val();
        });
        $("#inactivos").click(function(){
            alert($(location).attr('href'));
        });
    });
</script>
<h2><?php echo $title; ?></h2>
<div style="float: left; margin-top: 15px; margin-right: -50px; ">
    <a href="<?php echo site_url('/nomina/empleado_c/formulario/alta_empleado') ?>" title="Nuevo empleado">
        <img src="<?php echo base_url() . "/images/adduser.png" ?>" alt="Nuevo empleado" align="absmiddle" border="0" width="50px">	
    </a>
</div>
<table style="margin-top: 25px; margin-bottom: 10px;">
    <tr>
        <td style="text-align: center;"><b>Nombre</b></td>
        <td style="text-align: center;"><b>A. Paterno</b></td>
        <td style="text-align: center;"><b>A. Materno</b></td>
        <td style="text-align: center;"><b>Puesto</b></td>
        <td style="text-align: center;"><b>Espacio Físico</b></td>
    </tr>
    <tr>
        <td style="text-align: center;"><input class="filtros" id="fil_nom" name="fil_nom" size="15"/></td>
        <td style="text-align: center;"><input class="filtros" id="fil_apa" name="fil_apa" size="15"/></td>
        <td style="text-align: center;"><input class="filtros" id="fil_ama" name="fil_ama" size="15"/></td>
        <td style="text-align: center;"><?php echo form_dropdown('fil_pue', $puestos, 0, "id='fil_pue' class='filtros'"); ?></td>
        <td style="text-align: center;"><?php echo form_dropdown('fil_esp', $espacios, 0, "id='fil_esp' class='filtros'"); ?></td>
    </tr>
<!--    <tr>
        <td colspan="5">
            Mostrar inactivos: <input type="checkbox" id="inactivos" name="inactivos" checked/>
        </td>
    </tr>-->
</table>
<div id="error"></div>
<div align="center">
    <b>Total de registros: <?php echo $total_registros; ?></b>
</div>
<?php echo $this->pagination->create_links(); ?>
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
    $img_del = base_url() . "images/bien.png";
    $link_del = $link_edit = "";
    if (substr(decbin($permisos), 1, 1) == 1)
        $link_del = '<a href="'.site_url("/$ruta/$controller/$funcion/$subfuncion/habilitar_empleado/__id__").'" title="Habilitar empleado"><img src="' . $img_del . '" height="25px" width="25px"/></a>';
    if (substr(decbin($permisos), 2, 1) == 1)
        $link_edit = '<a href="'.site_url("/$ruta/$controller/$funcion/$subfuncion/editar_empleado/__id__").'" title="editar"><img src="' . $img_edit . '" height="25px" width="25px"/></a>';
if($empleados != false){    
    foreach ($empleados as $empleado) {?>
        <tr background='<?php echo $img_row ?>'>
            <td><?php echo $empleado->id ?></td>
            <td><?php echo $empleado->nombre ?></td>
            <td><?php echo $empleado->apaterno ?></td>
            <td><?php echo $empleado->amaterno ?></td>
            <td><?php echo $empleado->puesto_tag ?></td>
            <td><?php echo $empleado->espacio_fisico_tag ?></td>
            <td><?php echo $empleado->estatus_general_tag ?></td>
            <td>
                <?php //echo str_ireplace("__id__", $empleado->id, $link_edit); ?>
                <?php echo str_ireplace("__id__", $empleado->id, $link_del); ?>
            </td>
        </tr>
    <?php } }?>
</table>
<?php echo $this->pagination->create_links();

//<div id="listado">
//    <?php $this->load->view("nomina/listado_empleados"); 
?>
<!--</div>-->