<div class="row">
    <div class="ten columns centered">
        <h2>Permisos para el punto de venta <?= $usuario->nombre ?></h2>
        <input type="hidden" value="<?= $usuario->id?>" id="uid">
        <div align="left'">
            <a href=" <?= base_url() ?>index.php/admin/usuario_c/formulario/list_usuarios\">
            <img src=" <?= base_url() ?>images/factura.png" width="30px" title="Nuevo Ajuste de Inventario">
            </a>
        </div>

        <table  class="listado" border="0" width="900">
            <thead>
            <tr>
                <th>id</th>
                <th>menu</th>
                <th>descripcion</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <? foreach ($acciones as $a) { ?>
            <tr background="<?= base_url() ?>images/table_row.png">
                <td><?=$a->id ?></td>
                <td><?=$a->menu ?></td>
                <td><?=$a->descripcion ?></td>
                <td>
                    <input class="permiso"
                           accion="<?= $a->pid ?>"
                           menu_accion="<?= $a->id ?>"
                           type="checkbox" <?=$a->permiso == 't' ? 'checked="checked"' : '' ?>"/>
                </td>
            </tr>
            <?}?>
            </tbody>
        </table>
    </div>
</div>
<script>
    $(function(){
        $(".permiso").change(function(){
            modificar_permiso(this);
        });
    });
    function modificar_permiso(accion) {

        var url = "index.php/admin/usuario_c/asignar_permiso_pv";
        var menu_accion = $(accion).attr("menu_accion") || 0;
        var accion_id = $(accion).attr("accion") || 0;

        $.ajax({
            url:"<?= base_url() ?>" + url,
            type: "POST",
            dataType: "json",
            data: {
                usuario_id : $("#uid").val(),
                accion : accion_id,
                menu_accion : menu_accion
            },
            success : function(data){
                $(accion).attr("accion", data);
            }
        });
    }

</script>



