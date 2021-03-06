<h2><?= $title ?></h2>
<br/>
<?= $this->pagination->create_links(); ?>
<table style="width: 960px">
    <thead>
    <tr>
        <th>id</th>
        <th>Tipo</th>
        <th>Proveedor</th>
        <th>Transporte</th>
        <th>Numero de guia</th>
        <th>Tipo de entrega</th>
        <th>Fecha</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    <? foreach($devoluciones as $d) {?>
    <tr>
        <td><?= $d->id ?></td>
        <td><?= $d->tipo ?></td>
        <td>
            <?= //prefijo proveedor_ para obtener datos del proveedor
            $d->proveedor_razon_social
            ?>
        </td>
        <td><?= $d->transporte ?></td>
        <td><?= $d->numero_guia ?></td>
        <td><?= $d->tipo_entrega ?></td>
        <td><?= date('Y-m-d', strtotime($d->fecha)) ?></td>
        <td>
            <a title="Eliminar la devolucion"
               onclick=" return confirm('¿Desea eliminar este traspaso?')"
               href="<?= base_url()."index.php/almacen/salidas/cancelar_devolucion_proveedor/".$d->id ?>">
                <img style="width: 20px; height: 20px; border:0" src="<?= base_url()?>/images/trash.png" />
            </a>
            <a title="Editar devolucion" href="<?= $url."edit/".$d->id ?>">
                <img style="width: 20px; height: 20px; border:0" src="<?= base_url()?>/images/edit.png" />
            </a>
            <a title="Reporte devolucion" href="<?= base_url()."index.php/almacen/almacen_reportes/rep_devolucion_proveedor/".$d->id ?>">
            <img style="width: 20px; height: 20px; border:0" src="<?= base_url()?>/images/adobereader.png" />
            </a>
        </td>
    </tr>
    <?}?>
    </tbody>
</table>
<?= $this->pagination->create_links(); ?>