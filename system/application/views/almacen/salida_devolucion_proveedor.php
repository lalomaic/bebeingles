<style>
    fieldset { width: 938px; margin: auto; margin-bottom: 5px; border-radius: 3px; }
    .tipo-envio-cont{ text-align: right; padding-right: 40px }
    .form_tag span { margin-left: 10px; text-align: right; display: inline-block; width: 100px; }
    #productos { width: 100%;}
</style>
<h2><?= $title ?></h2>
<br/>
<fieldset>
    <legend>Devolución a proveedor</legend>
    <table class="form_table" width="100%">
        <tr>
            <td class="form_tag">
                <span>Proveedor</span>
                <input type="text" id="proveedor" size="60">
                <input type="hidden" id="proveedor_id" value="">
            </td class="form_tag">
            <td class="form_tag tipo-envio-cont">
                Devolución por envio <input id="tipo_envio" type="radio" name="tipo_envio" checked="checked" value="envio"/><br/>
                Devolución por entrega directa <input id="tipo_directo" type="radio" name="tipo_envio" value="directo"/>
            </td>
        </tr>
        <tr>
            <td class="form_tag">
                <span>Fecha</span>
                <input id="fecha" class="date_input" type="text" value="<?= date("d m Y")?>" />
            </td>
        </tr>
        <tr>
            <td class="form_tag">
                <span>Transporte</span>
                <input id="transporte" class="por-envio" type="text" size="60" />
            </td>
        </tr>
        <tr>
            <td class="form_tag">
                <span>Numero de Guia</span>
                <input id="numero_guia" class="por-envio" type="text" size="60" />
            </td>
        </tr>
        <tr>
            <td class="form_tag">
                <span>Entrega</span>
                <?= form_dropdown("tipo_entraga",$tipos_entrega, '','class="por-envio" id="tipo_entrega"' ) ?>
            </td>
        </tr>
        <tr>
            <td class="form_tag">
                <span style="vertical-align: top">Domicilio</span>
                <textarea id="domicilio" class="por-envio" cols="51" rows="5"></textarea>
            </td>
        </tr>
    </table>
</fieldset>
<fieldset>
    <legend>Productos para devolución</legend>
    <table id="productos">
        <thead>
        <tr>
            <th>Codigo</th>
            <th>Producto</th>
            <th>Cantidad</th>
            <th>Costo</th>
            <th>Subtotal</th>
        </tr>
        </thead>
        <tbody id="productos-list">
        <? for($i = 0; $i < 500; $i++) {?>
        <tr id="linea-<?= $i?>" class="item <?= $i > 10 ? "invisible" : "" ?>" >
            <td><input id="cod_bar-<?= $i?>" class="cod_bar" line="<?=$i?>" type="text" size="20" /></td>
            <td>
                <input id="name-<?= $i?>" linea="<?= $i?>" class="prod"  type="text" size="55" />
                <input id="pid-<?= $i?>" type="hidden" />
                <input id="nid-<?= $i?>" type="hidden" />
            </td>
            <td><input id="cantidad-<?= $i?>" class="cantidad" type="text" size="10" /></td>
            <td><input id="costo-<?= $i?>" class="costo" type="text" size="10" /></td>
            <td>
                <input id="sub-total-<?= $i?>" type="text" size="10" />
                <img id="img-wait-<?= $i ?>" src="<?= base_url()?>/images/waiting.gif" style="width: 15px; height: 15; display: none">
                <img id="img-ok-<?= $i ?>" src="<?= base_url()?>/images/ok.png" style="width: 15px; height: 15; display: none">
            </td>
        </tr>
        <? } ?>
        </tbody>
    </table>
    <div style="text-align: right; margin-right: 15px;">
        Total:
        <input id="total" type="text" size="10" />
    </div>
</fieldset>
<fieldset>
    <div style="text-align: right">
        <input type="button" id="save-button" value="Ejecutar devolución" />
        <input type="button" id="finalizar-button" style="display: none" value="Finalizar devolución" />
        <input type="hidden" id="devolucion_proveedor_id"  value=""/>
    </div>
</fieldset>
<? $this->load->view("almacen/js_salida_devolucion_proveedor") ?>

