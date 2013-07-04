<h2><?= $title?></h2>
<form method="post" accept-charset="utf-8" 
      action="<?=  base_url()?>index.php/ventas/ventas_c/formulario/list_facturas">
<table>
    <tr>
        <td>
            <b>Espacio fisico</b><br>
            <?= form_dropdown("espacio_fisico", $espacios, 0) ?>            
        </td>
        <td>
            <b>Cliente</b><br>
            <input type="text" name="cliente"/>
        </td>
        <td>
            <b>Fecha</b><br>
            <input class="date_input" type="text" name="fecha"/>
        </td>
        <td>
            <b>Serie</b><br>
            <input type="text" name="serie"/>
        </td>
        <td>
            <b>Folio</b><br>
            <input type="text" name="folio"/>
        </td>
        <td>
            <input type="submit" value="Buscar"/>
            <input type="button" onclick="location.reload()" value="Limpiar"/>
        </td>
    </tr>
</table>
</form>
<div align="center">
    <b>Total de facturas: <?= $total_registros?></b>
</div>
<?= $this->pagination->create_links(); ?>
<table>
    <thead>
        <tr>
            <th>Id</th>            
            <th>Serie</th>
            <th>Folio</th>
            <th>Usuario</th>
            <th>Fecha</th>
            <th>Cliente</th>
            <th>Monto</th>
            <th>Estatus</th>
            <th>Espacio fisico</th>
            <th></th>
        </tr>    
    </thead>
    <tbody class="list">
    <?foreach($facturas->all as $factura) {?>
        <tr>
            <td class="text-right"><?= $factura->id?> </td>
            <td class="text-center"><?= $factura->serie_factura?> </td>
            <td><?= $factura->folio_factura?> </td>
            <td><?= $factura->usuario?> </td>
            <td><?= $factura->fecha?> </td>
            <td><?= $factura->cliente?> </td>
            <td class="text-right">
            $<?= number_format($factura->monto_total,2)?> 
            </td>
            <td><?= $factura->estatus_factura?> </td>
            <td><?= $factura->espacio?> </td>
            <td>
                <a href="<?= base_url()?>index.php/ventas/ventas_reportes/reporte_cl_factura/<?= $factura->id?>">
                <img src="<?= base_url()?>/images/adobereader.png" style="width: 15px;height: 15px;" />
                </a>
            </td>
        </tr>
    <? } ?>
    </tbody>
</table>
<?= $this->pagination->create_links(); ?>
<style>
    .text-right{
        text-align: right;
    }
    .text-center{
        text-align: center;
    }
    
    .list tr:nth-child(odd) {
        background-color: #E7E4F5;
    }
    .list td{
        padding-left: 3px;
        padding-right: 3px;
    }    
</style>
<script type="text/javascript">
$($.date_input.initialize);
</script>