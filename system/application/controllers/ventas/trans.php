<?php

class Trans extends Controller {

    function Trans() {
        parent::Controller();
        if ($this->session->userdata('logged_in') == FALSE) {
            redirect(base_url() . "index.php/inicio/logout");
        }
        $this->load->model("modulo");
        $this->load->model("cl_detalle_pedido");
        $this->load->model("cliente");
        $this->load->model("espacio_fisico");
        $this->load->model("familia_producto");
        $this->load->model("subfamilia_producto");
        $this->load->model("numero_letras");
        $user_hash = $this->session->userdata('user_data');
        $row = $this->usuario->get_usuario_detalles($user_hash);
        $GLOBALS['usuarioid'] = $row->id;
        $GLOBALS['username'] = $row->username;
        $GLOBALS['ubicacion_id'] = $row->espacio_fisico_id;
        $GLOBALS['empresa_id'] = $row->empresas_id;
        $GLOBALS['ruta'] = $this->uri->segment(1);
        $GLOBALS['controller'] = $this->uri->segment(2);
        $GLOBALS['funcion'] = $this->uri->segment(3);
    }

    function alta_cliente() {

        $c = new Cliente();
        $c->usuario_id = $GLOBALS['usuarioid'];
        $related = $c->from_array($_POST);
        // save with the related objects
        if ($c->save($related)) {
            echo "<html> <script>alert(\"Se ha registrado los datos generales del Cliente.\"); window.location='" . base_url() . "index.php/" . $GLOBALS['ruta'] . "/clientes_c/formulario/list_clientes';</script></html>";
        } else {
            show_error("" . $u->error->string);
        }
    }

    function alta_venta() {
        //Guardar el usuario
        $u = new Cl_pedido();
        $u->empresas_id = $GLOBALS['empresa_id'];
        //	  $u->no_lote=$this->diversos->get_no_lote();
        $u->usuario_id = $GLOBALS['usuarioid'];
        $u->espacio_fisico_id = $GLOBALS['ubicacion_id'];
        $u->corigen_pedido_id = 2;
        $u->ccl_estatus_pedido_id = 1;
        $u->ccl_tipo_pedido_id = 1;
        $u->fecha_alta = date("Y-m-d H:i:s");
        $related = $u->from_array($_POST);
        //Adecuar las Fechas al formato YYYY/MM/DD
        $fecha_pago = explode(" ", $_POST['fecha_pago']);
        $fecha_entrega = explode(" ", $_POST['fecha_entrega']);
        $u->fecha_pago = "" . $fecha_pago[2] . "/" . $fecha_pago[1] . "/" . $fecha_pago[0];
        $u->fecha_entrega = "" . $fecha_entrega[2] . "/" . $fecha_entrega[1] . "/" . $fecha_entrega[0];
        // save with the related objects
        if ($u->save($related)) {
            echo form_hidden('id', "$u->id");
            echo '<button type="submit" id="boton1" style="display:none;">Actualizar Registro</button>';
            echo "<p id=\"response\">Capturar los detalles del pedido</p>";
            $u->clear();
        } else {
            show_error("" . $u->error->string);
        }
    }

    function alta_venta_detalles() {
        //Guardar el usuario
        $varP = $_POST;
        $line = $this->uri->segment(4);
        $pr = new Cl_detalle_pedido();
        $pr->cl_pedidos_id = $varP['cl_pedidos_id' . $line];
        $pr->cproductos_id = $varP['producto' . $line];
        $pr->tasa_impuesto = $varP['iva' . $line];
        $pr->cantidad = $varP['unidades' . $line];
        $pr->costo_unitario = $varP['precio_u' . $line];
        $pr->costo_total = $pr->cantidad * $pr->costo_unitario;
        if (isset($varP['id' . $line]) == true)
            $pr->id = $varP['id' . $line];
        if ($pr->save()) {
            echo form_hidden('id' . $line, "$pr->id");
            echo form_hidden('cl_pedidos_id' . $line, "$pr->cl_pedidos_id");
            echo "<a href=\"javascript:borrar_detalle('$pr->id', $line)\"><img src=\"" . base_url() . "images/trash1.png\" width=\"20px\" title=\"Borrar Detalle\"/></a><img src=\"" . base_url() . "images/ok.png\" width=\"20px\" title=\"Guardado\"/>";
            $pr->clear();
        } else {
            echo '<img src="' . base_url() . 'images/stop.png" width="20px" title="Error al Guardar"/>';
        }
    }

    function verificar_pedido_venta() {
        $id = $this->uri->segment(4);
        $d = new Cl_detalle_pedido();
        $d->select("sum(costo_total) as total");
        $d->where("cl_pedidos_id", $id);
        $d->get();
        $total = $d->total;
        if ($total > 0) {
            $p = new Cl_pedido();
            $p->get_by_id($id);
            $p->monto_total = $total;
            if ($p->save()) {
                //Se valida que el pedido tiene registrados productos y se da por terminado enviando al listado de pedidos de compra
                echo "<html> <script>alert(\"Se ha registrado el Pedido de Venta correctamente.\"); window.location='" . base_url() . "index.php/" . $GLOBALS['ruta'] . "/ventas_c/formulario/list_ventas/';</script></html>";
            } else {
                /*                 * No se encontro registro valido de productos dentro del pedido por lo cual se cancela */
                $this->db->trans_start();
                $this->db->query("delete from cl_detalle_pedidos where cl_pedidos_id='$id'");
                $this->db->query("delete from cl_pedidos where id='$id'");
                $this->db->trans_complete();

                echo "<html> <script>alert(\"Se ha eliminado el Pedido de Venta por falta de conceptos, intente de nuevo.\"); window.location='" . base_url() . "index.php/" . $GLOBALS['ruta'] . "/ventas_c/formulario/list_ventas';</script></html>";
            }
        } else {
            /*             * No se encontro registro valido de productos dentro del pedido por lo cual se cancela */
            $this->db->trans_start();
            $this->db->query("delete from cl_detalle_pedidos where cl_pedidos_id='$id'");
            $this->db->query("delete from cl_pedidos where id='$id'");
            $this->db->trans_complete();
            $p->clear();
            $d->clear();
            echo "<html> <script>alert(\"Se ha eliminado el Pedido de Venta por falta de conceptos, intente de nuevo.\"); window.location='" . base_url() . "index.php/" . $GLOBALS['ruta'] . "/ventas_c/formulario/list_ventas';</script></html>";
        }
    }

    function alta_cl_factura() {
        //Guardar el usuario
        $u = new Cl_factura();
        $u->usuario_id = $GLOBALS['usuarioid'];
        $u->fecha_impresion = date("Y-m-d H:i:s");
        $fecha = explode(" ", $_POST['fecha']);
        $u->fecha = "" . $fecha[2] . "/" . $fecha[1] . "/" . $fecha[0];
        $u->cclientes_id = $_POST['cclientes_id'];
        $u->folio_factura = utf8_decode($_POST['folio_factura']);
        $u->estatus_factura_id = $_POST['estatus_factura_id'];
        $u->empleado_id = $_POST['empleado_id'];
        $u->tipo_factura_id = $_POST['tipo_factura_id'];
        $u->espacios_fisicos_id = $GLOBALS['ubicacion_id'];
        $u->id = $_POST['id'];
        if ($u->tipo_factura_id == 3)
            $update_tif = ", ctipo_salida_id=9";
        else
            $update_tif = " ";

        $d = new Salida();
        $d->select("sum(cantidad*costo_unitario) as total, sum(cantidad*costo_unitario*tasa_impuesto/(100+tasa_impuesto)) as iva");
        $d->where("cl_facturas_id", $_POST['id']);
        $d->get();
        $u->monto_total = $d->total;
        $u->iva_total = $d->iva;
        //echo $d->total+$d->iva;
        $u->monto_letras = strtoupper($this->numero_letras->convertir_a_letras($d->total));
        if ($u->save()) {
            $this->db->query("update cl_pedidos set cclientes_id=$u->cclientes_id where id=" . $_POST['cl_pedido_id']);
            $this->db->query("update salidas set estatus_general_id=1, fecha='" . $u->fecha . date(" H:i:s") . "' $update_tif where cl_facturas_id=$u->id");
            echo form_hidden('id', "$u->id");
            $u->clear();
            $d->clear();
            echo "<html> <script>alert(\"Se ha registrado correctamente la Factura del Cliente.\"); window.location='" . base_url() . "index.php/ventas/cl_factura_c/formulario/list_cl_facturas/';</script></html>";
        } else {
            show_error("" . $u->error->string);
        }
    }

    function editar_cl_factura() {
        //Guardar el usuario
        $u = new Cl_factura();
        $u->id = $_POST['id'];
        $u->get_by_id($_POST['id']);
        $u->usuario_id = $GLOBALS['usuarioid'];
        $u->fecha_impresion = date("Y-m-d H:i:s");
        $fecha = explode(" ", $_POST['fecha']);
        $u->fecha = "" . $fecha[2] . "/" . $fecha[1] . "/" . $fecha[0];
        $u->cclientes_id = $_POST['cclientes_id'];
        $u->folio_factura = $_POST['folio_factura'];
        $u->estatus_factura_id = $_POST['estatus_factura_id'];
        $u->tipo_factura_id = $_POST['tipo_factura_id'];
        if ($u->save()) {
            $this->db->query("update cl_pedidos set cclientes_id=$u->cclientes_id where id=" . $u->cl_pedido_id);
            $this->db->query("update salidas set estatus_general_id=1, fecha='$u->fecha', cclientes_id=$u->cclientes_id where cl_facturas_id=$u->id");
            $u->clear();
            echo "<html> <script>alert(\"Se ha registrado correctamente la Factura del Cliente.\"); window.location='" . base_url() . "index.php/ventas/cl_factura_c/formulario/list_cl_facturas/';</script></html>";
        } else {
            show_error("Existe un error de referencia de la Factura con el Pedido de Venta, favor de verificarlo");
        }
    }

    //Oscar Funciones

    function act_precios_venta() {
        //Guardar la Forma de Cobro
        $line = $this->uri->segment(4);
         $pre= $_POST['precio'. $line];
        $u = new Producto();
        $b = new Bitacora_precio();
        $u->get_by_id($_POST['cproducto_id' . $line]);
        $u->usuario_id = $GLOBALS['usuarioid']; 
        $precio_anterior = $u->precio1;
        $precio_nuevo = $_POST['precio1' . $line];
        $u->precio1 = $_POST['precio1' . $line];
        $u->precio2 = $_POST['precio2' . $line];
        $afect_global = $_POST['cambio_global' . $line];
        $u->fecha_cambio = date("Y-m-d");
        // save with the related objects
        if ($u->save()) {
   
                $b->cproducto_id = $u->id;
                $b->usuario_id = $GLOBALS['usuarioid'];
                $b->fecha = date("Y-m-d H:i:s");
                $b->hora = date("H:i:s");
                $b->precio = $pre;
                $b->save();
  
            
            if (isset($_POST['bar' . $line]) and $_POST['bar' . $line] == 1){
                $this->db->query("update cproductos set oferta=1 where id=$u->id");
            }else{
                $this->db->query("update cproductos set oferta=0 where id=$u->id");}
            if (isset($_POST['des' . $line]) and $_POST['des' . $line] == 1){
                $this->db->query("update cproductos set status=0 where id=$u->id");}
                else{
                  $this->db->query("update cproductos set status=1 where id=$u->id");
                }
            if ($precio_anterior != $precio_nuevo) {
                $dif = $precio_nuevo - $precio_anterior;
                $upd = $this->db->query("select id, cproducto_id, diferencia_pesos from precios_sucursales where cproducto_id='$u->id' and diferencia_pesos!='0'");
                if ($upd->num_rows() > 0) {
                    foreach ($upd->result() as $linea) {
                        if ($afect_global == 'parcial') {
                            $n_dif_precios = $linea->diferencia_pesos - $dif;
                            $this->db->query("update precios_sucursales set diferencia_pesos='$n_dif_precios' where id=$linea->id");
                        } else if ($afect_global == 'global') {
                            $this->db->query("update precios_sucursales set diferencia_pesos='0' where id=$linea->id");
                        }
                    }
                }
            }
            $this->db->query("update control_actualizaciones set fecha_cambio='" . date("Y-m-d") . "', hora='" . date("H:i:s.u") . "' where id=1 or id=2");
            echo form_hidden("cproducto_id$line", "$u->id");
            echo "<img src=\"" . base_url() . "images/ok.png\" width=\"20px\" title=\"Guardado\"/><input type='hidden' value='$afect_global' name='cambio_global$line' id='cambio_global$line'>";
        } else
            show_error("" . $u->error->string);
    }

    function alta_promociones() {
        $promo = new Promocion();
        if (isset($_POST['id']) == true)
            $promo->where('id', $this->input->post('id'))->get();
        $promo->cproducto_id = $this->input->post('producto_id');
        if ($this->input->post('cmarca_id') == '')
            $promo->cmarca_id = 0;
        else
            $promo->cmarca_id = $this->input->post('cmarca_id');
        if ($this->input->post('cfamilia_id') == '')
            $promo->cproductos_familias_id = 0;
        else
            $promo->cproductos_familias_id = $this->input->post('cfamilia_id');
        $total = $this->espacio_fisico->get_total_espacios_by_empresa_id(1);
        $espacios = "";
        for ($i = 1; $i <= $total; $i++) {
            if (($this->input->post('espacios' . $i)) != null) {
                $espacios = $espacios . $this->input->post('espacios' . $i) . ",";
            }
        }
        $espacios = substr($espacios, 0, strlen($espacios) - 1);
        $promo->espacios_fisicos = $espacios;
        //Familias
        $familias = $this->familia_producto->get_cproductos_familias();
        $total_familias = count($familias->all);
        for ($x = 0; $x < $total_familias; $x++) {
            if (($this->input->post('chk' . $x)) != null) {
                $familias_mtrx[] = $this->input->post('chk' . $x);
            }
        }
        $familias_str = implode(",", $familias_mtrx);
        $promo->cproductos_familias = $familias_str;

        //SubFamilias
        $subfamilias = $this->subfamilia_producto->get_cproductos_subfamilias();
        $total_subfamilias = count($subfamilias->all);
        for ($x = 0; $x <= $total_subfamilias; $x++) {
            if (($this->input->post('schk' . $x)) != null) {
                $subfamilias_mtrx[] = $this->input->post('schk' . $x);
            }
        }
        $subfamilias_str = implode(",", $subfamilias_mtrx);
        $promo->cproductos_subfamilias = $subfamilias_str;


        $fecha_inicio = explode(" ", $this->input->post('fecha_inicio'));
        $fecha_final = explode(" ", $_POST['fecha_final']);
        /* $u->fecha_pago=" */
        $promo->fecha_inicio = "" . $fecha_inicio[2] . "/" . $fecha_inicio[1] . "/" . $fecha_inicio[0];
        $promo->fecha_final = "" . $fecha_final[2] . "/" . $fecha_final[1] . "/" . $fecha_final[0];

        $diashoras = "";
        for ($j = 1; $j <= 7; $j++) {
            $hini = $this->input->post('horai' . $j);
            $mini = $this->input->post('mini' . $j);
            $hfin = $this->input->post('horaf' . $j);
            $mfin = $this->input->post('minf' . $j);
            if ($hini < 10) {
                $hini = "0" . $hini;
            }
            if ($mini < 10) {
                $mini = "0" . $mini;
            }
            if ($hfin < 10) {
                $hfin = "0" . $hfin;
            }
            if ($mfin < 10) {
                $mfin = "0" . $mfin;
            }

            if (($this->input->post('dia' . $j)) != null) {
                $diashoras = $diashoras . $this->input->post('dia' . $j) . "&" . $hini . ":" . $mini . "&" . $hfin . ":" . $mfin . ",";
            }
        }
        $diashoras = substr($diashoras, 0, strlen($diashoras) - 1);
        $promo->dias_horas = $diashoras;
        $promo->fecha_captura = date("Y-m-d H:i:s");
        $promo->limite_cantidad = $this->input->post('limite');
        $promo->usuario_id = $GLOBALS['usuarioid'];
        $promo->precio_venta = $this->input->post('precio');
        $promo->estatus_general_id = 1;
        if ($promo->save()) {
            $this->db->query("update control_actualizaciones set fecha_cambio='" . date("Y-m-d") . "', hora='" . date("H:i:s.u") . "' where id=12");
            echo "<html> <script>alert(\"Se ha registrado la promocion correctamente.\"); window.location='" . base_url() . "index.php/ventas/ventas_c/formulario/list_promociones';</script></html>";
        }
    }

    function alta_actualizacion_sucursales() {
        $this->load->model('precio_sucursal');
        $this->load->model('producto');
        //Obtener datos
        $rows = $this->uri->segment(5);
        $esp = $this->uri->segment(4);
        $row = $_POST;
        //Contabilizar Renglones
        $i = 1;
        $v = 0;
        for ($i; $i <= $rows; $i++) {
            // 			echo $i.'_'.$v;
            $cproducto_id = $row['cproducto_' . $i . '_' . $v];
            //echo "&Prod=$cproducto_id<br/>";
            $precio1 = $this->producto->select("precio1")->where('id', $cproducto_id)->get();
            if (isset($_POST['bar' . $i]) and $_POST['bar' . $i] == 1)
                $this->db->query("update cproductos set cfamilia_id=5 where id=$cproducto_id");
            for ($v = 0; $v < $esp; $v++) {
                //Obtener el id del espacio_fisico
                $espacio_id = $row['espacio_fisico_' . $i . '_' . $v];
                $precio_local = $row['precio_local_' . $i . '_' . $v];
                //REcorrer los espacios fisicos para guardar la diferencia de pesos
                if ($row['chk_' . $i . '_' . $v] == 1) {
                    //Realizar actualizacion del precio local
                    $ps = new Precio_sucursal();
                    $ps->where('cproducto_id', $cproducto_id)->where('espacios_fisicos_id', $espacio_id)->get();
                    if ($ps->c_rows > 1)
                        $this->db->query("delete from precios_sucursales where espacios_fisicos_id=$espacio_id and cproducto_id=$cproducto_id");
                    $ps->cproducto_id = $cproducto_id;
                    $ps->espacios_fisicos_id = $espacio_id;
                    $ps->fecha_captura = date("Y-m-d H:i:s");
                    $ps->diferencia_pesos = $precio_local - $precio1->precio1;
                    echo $precio_local . "-" . $precio1->precio1 . "<br/>";
                    $ps->usuario_id = $GLOBALS['usuarioid'];
                    $ps->save();
                    $this->db->query("update cproductos set fecha_cambio='" . date("Y-m-d") . "', hora_cambio='" . date("H:i:s.u") . "', usuario_id={$GLOBALS['usuarioid']} where id=$cproducto_id");
                    $this->db->query("update control_actualizaciones set fecha_cambio='" . date("Y-m-d") . "', hora='" . date("H:i:s.u") . "' where id=1 or id=2");
                    $this->db->query("insert into bitacora_precios(usuario_id, cproducto_id, espacio_fisico_id, fecha, hora,precio,sucursal) values ({$GLOBALS['usuarioid']}, $ps->cproducto_id, $espacio_id, '" . date("Y-m-d") . "', '" . date("H:i:s.u") . "', '$precio_local', 'f' )");
                    $ps->clear();
                }
            }
            $v = 0;
            echo "<br/>";
        }
        echo "<html> <script>alert(\"Se ha registrado la actualizacion de precios por sucursal correctamente.\"); window.location='" . base_url() . "index.php/ventas/ventas_c/formulario/alta_actualizacion_sucursales/';</script></html>";
    }

    function delete_errores() {
        for ($x = 800000; $x > 200000; $x--) {
            //$this->db->query("delete from cproductos_numeracion where id=$x");
            echo "delete from cproductos_numeracion where id=$x;";
            echo "<br/>";
        }
    }

    function delete_errores_1() {
        for ($x = 200000; $x < 1913434; $x++) {
            $this->db->query("delete from cproductos_numeracion where id=$x");
            echo $x . "<br/>";
        }
    }

}

?>
