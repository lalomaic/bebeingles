<?php

class Almacen_c extends Controller {

    //var $usuarioid, $username, $ruta, $controller, $funcion, $subfuncion, $modulos_totales;

    function Almacen_c() {
        parent::Controller();
        if ($this->session->userdata('logged_in') == FALSE) {
            redirect(base_url() . "index.php/inicio/logout");
        }
        $this->assetlibpro->add_css('default.css');
        $this->assetlibpro->add_css('menu_style.css');
        $this->assetlibpro->add_css('date_input.css');
        $this->assetlibpro->add_css('jquery.validator.css');
        $this->assetlibpro->add_css('autocomplete.css');
        $this->assetlibpro->add_css('fancybox/jquery.fancybox-1.3.1.css');
        $this->assetlibpro->add_css('style_fancy.css.css');
        $this->assetlibpro->add_js('jquery.js');
        $this->assetlibpro->add_js('jquery.autocomplete.js');
        $this->assetlibpro->add_js('jquery.selectboxes.js');
        $this->assetlibpro->add_js('jquery.select-autocomplete.js');
        $this->assetlibpro->add_js('jquery.numberformatter.js');
        $this->assetlibpro->add_js('jquery.AddIncSearch.js');
        $this->assetlibpro->add_js('jquery.validator.js');
        $this->assetlibpro->add_js('jquery.date.js');
        $this->assetlibpro->add_js('jquery.form.js');
        $this->assetlibpro->add_js('jquery.jfield.js');
        $this->assetlibpro->add_js('fancybox/jquery.mousewheel-3.0.2.pack.js');
        $this->assetlibpro->add_js('fancybox/jquery.fancybox-1.3.1.js');
        $this->load->model("almacen_validacion");
        $this->load->model("proveedor");
        $this->load->model("producto");
        $this->load->model("espacio_fisico");
        $this->load->model("tipo_entrada");
        $this->load->model("tipo_salida");
        $this->load->model("entrada");
        $this->load->model("salida");
        $this->load->model("pr_factura");
        $this->load->model("lote");
        $this->load->model("pr_pedido");
        $user_hash = $this->session->userdata('user_data');
        $row = $this->usuario->get_usuario_detalles($user_hash);
        $GLOBALS['usuarioid'] = $row->id;
        $GLOBALS['ubicacion_id'] = $row->espacio_fisico_id;
        $GLOBALS['empresa_id'] = $row->empresas_id;
        $GLOBALS['ubicacion_nombre'] = $this->espacio_fisico->get_espacios_f_tag($GLOBALS['ubicacion_id']);
        $GLOBALS['username'] = $row->username;
        $GLOBALS['ruta'] = $this->uri->segment(1);
        $GLOBALS['controller'] = $this->uri->segment(2);
        $GLOBALS['funcion'] = $this->uri->segment(3);
        $GLOBALS['subfuncion'] = $this->uri->segment(4);
        $GLOBALS['modulos_totales'] = $this->modulo->get_tmodulos();
        $GLOBALS['main_menu'] = $this->menu->menus($GLOBALS['usuarioid'], "principal", 0);
    }

    function formulario() {
        //Funcion para manejar formularios y listados,
        global $main_menu, $usuarioid, $username, $ruta, $controller, $funcion, $subfuncion, $modulos_totales, $empresa_id;

        $main_view = false;
        $data['username'] = $username;
        $data['usuarioid'] = $usuarioid;
        $data['modulos_totales'] = $modulos_totales;
        $data['colect1'] = $main_menu;
        $data['title'] = $this->accion->get_title("$subfuncion") . " de la Empresa";
        $accion_id = $this->accion->get_id("$subfuncion");
        $row = $this->usuario->get_usuario($usuarioid);
        $grupoid = $row->grupo_id;
        $puestoid = $row->puesto_id;
        $data['ruta'] = $ruta;
        $data['controller'] = $controller;
        $data['funcion'] = $funcion;
        $data['subfuncion'] = $subfuncion;
        $data['permisos'] = $this->usuario_accion->get_permiso($accion_id, $usuarioid, $puestoid, $grupoid);

        //Validacion del arreglo del menu, usuarioid y permisos especificos
        if (is_array($data['colect1']) and $usuarioid > 0 and $data['permisos'] != false and $this->session->userdata('logged_in') == TRUE) {
            $main_view = true;
            //Inicio del Bloque tipo entrada
            if ($subfuncion == "salida_por_traspaso") {
                //Definir la vista
                $data['principal'] = $ruta . "/" . $subfuncion;
                $data['espaciosf'] = $this->espacio_fisico->get_by_id($GLOBALS['ubicacion_id']);
                $data['rows'] = 10;
                //Obtener Datos
            } else if ($subfuncion == "entrada_por_traspaso") {
                //Definir la vista
                $data['principal'] = $ruta . "/" . $subfuncion;
                $data['espaciosf'] = $this->espacio_fisico->get_by_id($GLOBALS['ubicacion_id']);
                $data['rows'] = 10;
                //Obtener Datos
            } else if ($subfuncion == "salida_por_ajuste") {
                //Definir la vista
                $data['principal'] = $ruta . "/" . $subfuncion;
                $data['espaciosf'] = $this->espacio_fisico->get_by_id($GLOBALS['ubicacion_id']);
                $data['rows'] = 10;
                //Obtener Datos
            } else if ($subfuncion == "salida_devolucion_proveedor") {
                //Definir la vista
                $data['principal'] = $ruta . "/" . $subfuncion;
                $data['title'] = "Devolución a Proveedor";
                $data['tipos_entrega'] = array("domicilio"=>"Domicilio","ocurre"=>"Ocurre");
                $data['espacio_fisico_id'] = $GLOBALS['ubicacion_id'];
                $data['rows'] = 10;
                //Obtener Datos
            } else if ($subfuncion == "entrada_por_ajuste") {
                //Definir la vista
                $data['principal'] = $ruta . "/" . $subfuncion;
                $data['espaciosf'] = $this->espacio_fisico->get_by_id($GLOBALS['ubicacion_id']);
                $data['rows'] = 10;
                //Obtener Datos
            } else if ($subfuncion == "alta_tipo_entrada") {
                //Definir la vista
                $data['principal'] = $ruta . "/" . $subfuncion;
                //Obtener Datos
                $data['validation'] = $this->almacen_validacion->validacion_tipo_entrada();
            } else if ($subfuncion == "list_tipos_entradas") {

                if ($this->uri->segment(5) == "editar_tipo_entrada") {
                    //Cargar los datos para el formulario
                    $edit_usr = $this->uri->segment(5);
                    $id = $this->uri->segment(6);
                    //Definir la vista
                    $data['principal'] = $ruta . "/editar_tipo_entrada";
                    //Obtener Datos
                    $data['tipo_entrada'] = $this->tipo_entrada->get_entrada($id);
                    $data['validation'] = $this->almacen_validacion->validacion_tipo_entrada();
                } else if ($this->uri->segment(5) == "borrar_tipo_entrada") {
                    $id = $this->uri->segment(6);
                    $main_view = false;
                    if (substr(decbin($data['permisos']), 2, 1) == 1) {
                        $u = new Tipo_entrada();
                        $u->get_by_id($id);
                        $u->estatus_general_id = 2;
                        $u->save();
                        redirect(base_url() . "index.php/" . $ruta . "/" . $controller . "/" . $funcion . "/" . $subfuncion);
                    } else {
                        $main_view = true;
                        //Definir la vista
                        $data['principal'] = "error";
                        $data['error_field'] = "No tiene permisos para deshabilitar el Tipo de Entrada";
                    }
                } else {
                    $main_view = true;
                    //Definir la vista
                    $data['principal'] = $ruta . "/" . $subfuncion;
                    //Obtener Datos
                    // load pagination class
                    $this->load->library('pagination');
                    $config['base_url'] = base_url() . "index.php/" . $ruta . "/" . $controller . "/" . $funcion . "/list_tipos_entradas/";
                    $config['per_page'] = '30';
                    $page = $this->uri->segment(5);
                    //Identificar el numero de pagina en el paginador si existe
                    if ($page > 0) {
                        $offset = $page;
                    } else if ($page == '') {
                        $offset = 0;
                    } else {
                        $offset = 0;
                    }

                    $u1 = new Tipo_entrada();
                    $u1->get();
                    $total_registros = $u1->c_rows;
                    $data['tipos_entradas'] = $this->tipo_entrada->get_tipos_entradas_list($config['per_page'], $offset);
                    $data['total_registros'] = $total_registros;
                    $config['total_rows'] = $total_registros;
                    $this->pagination->initialize($config);
                }
            } //Final del Bloque tipo entrada
            //Inicio del Bloque tipo salida
            else if ($subfuncion == "alta_tipo_salida") {
                //Cargar los datos para el formulario
                //Definir el numero de frames
                $data['frames'] = 0;
                //Definir la vista
                $data['principal'] = $ruta . "/" . $subfuncion;
                //Obtener Datos
                $data['validation'] = $this->almacen_validacion->validacion_tipo_salida();
            } else if ($subfuncion == "list_tipos_salidas") {

                if ($this->uri->segment(5) == "editar_tipo_salida") {
                    //Cargar los datos para el formulario
                    $edit_usr = $this->uri->segment(5);
                    $id = $this->uri->segment(6);
                    //Definir el numero de frames
                    $data['frames'] = 0;
                    //Definir la vista
                    $data['principal'] = $ruta . "/editar_tipo_salida";
                    //Obtener Datos
                    $data['tipo_salida'] = $this->tipo_salida->get_salida($id);
                    $data['validation'] = $this->almacen_validacion->validacion_tipo_salida();
                } else if ($this->uri->segment(5) == "borrar_tipo_salida") {
                    $id = $this->uri->segment(6);
                    $main_view = false;
                    if (substr(decbin($data['permisos']), 2, 1) == 1) {
                        $u = new Tipo_salida();
                        $u->get_by_id($id);
                        $u->estatus_general_id = 2;
                        redirect(base_url() . "index.php/" . $ruta . "/" . $controller . "/" . $funcion . "/" . $subfuncion);
                    } else {
                        $main_view = true;
                        //Definir la vista
                        $data['principal'] = "error";
                        $data['error_field'] = "No tiene permisos para deshabilitar el Tipo de Salida";
                    }
                } else {
                    //Definir la vista
                    $data['principal'] = $ruta . "/" . $subfuncion;
                    //Obtener Datos
                    // load pagination class
                    $this->load->library('pagination');
                    $config['base_url'] = base_url() . "index.php/" . $ruta . "/" . $controller . "/" . $funcion . "/list_tipos_salidas/";
                    $config['per_page'] = '30';
                    $page = $this->uri->segment(5);
                    //Identificar el numero de pagina en el paginador si existe
                    if ($page > 0) {
                        $offset = $page;
                    } else if ($page == '') {
                        $offset = 0;
                    } else {
                        $offset = 0;
                    }

                    $u1 = new Tipo_salida();
                    $u1->get();
                    $total_registros = $u1->c_rows;
                    $data['tipos_salidas'] = $this->tipo_salida->get_tipos_salidas_list($offset, $config['per_page']);
                    $data['total_registros'] = $total_registros;
                    $config['total_rows'] = $total_registros;
                    $this->pagination->initialize($config);
                }
            } //Final del Bloque tipo de salida
            else if ($subfuncion == "alta_entrada") {
                $main_view = true;
                $this->load->model('ctipo_factura');
                $this->load->model('estatus_factura');
                $data['principal'] = $ruta . "/" . $subfuncion;
                /* Cargar Catalogos */
                $data['validation'] = $this->almacen_validacion->validacion_entrada_compra();
                $data['empresa'] = $this->empresa->get_empresa($empresa_id);
                $data['espacios_fisicos_id'] = $GLOBALS['ubicacion_id'];
                $data['espacios_fisicos_tag'] = $GLOBALS['ubicacion_nombre'];
                $data['proveedor'] = $this->proveedor->get_proveedores_hab();
                $data['tipos_facturas'] = $this->ctipo_factura->get_ctipos_factura();
                $data['estatus_facturas'] = $this->estatus_factura->get_estatus_facturas();
                $data['rows'] = 100;
                $data['tipo_entrada'] = 1;
                $data['estatus_general_id'] = 2; //Compra directa
            } else if ($subfuncion == "alta_inventario_inicial") {
                $main_view = true;
                $this->load->model('ctipo_factura');
                $this->load->model('estatus_factura');
                $data['principal'] = $ruta . "/" . $subfuncion;
                /* Cargar Catalogos */
                $data['validation'] = $this->almacen_validacion->validacion_entrada_compra();
                $data['empresa'] = $this->empresa->get_empresa($empresa_id);
                $data['espacios_fisicos_id'] = $GLOBALS['ubicacion_id'];
                $data['espacios_fisicos_tag'] = $GLOBALS['ubicacion_nombre'];
                $data['proveedor'] = $this->proveedor->get_proveedores_hab();
                $data['tipos_facturas'] = $this->ctipo_factura->get_ctipos_factura();
                $data['estatus_facturas'] = $this->estatus_factura->get_estatus_facturas();
                $data['rows'] = 5000;
                $data['tipo_entrada'] = 6;
                $data['estatus_general_id'] = 1;
                
            }else if ($subfuncion == "list_entradas") {
                $data['principal'] = $ruta . "/" . $subfuncion;
                $data['pag'] = 1;
                $filtrado = $this->uri->segment(6);
                $id = $this->uri->segment(7);
                $this->load->model('ctipo_factura');
                if ($this->uri->segment(5) == "editar_factura_entrada") {
                    //Cargar los datos para el formulario                    
                    $edit_usr = $this->uri->segment(5);
                    $id = $this->uri->segment(6);
                    //Definir la vista
                    $data['principal'] = $ruta . "/editar_factura_entrada";
                    //Obtener Datos
                    $data['pr_factura'] = $this->pr_factura->get_pr_factura($id);
                    $data['tipos_facturas'] = $this->ctipo_factura->get_ctipos_factura();
                    $data['empresa'] = $this->empresa->get_empresa($empresa_id);
                    $data['proveedores'] = $this->proveedor->get_by_id($data['pr_factura']->cproveedores_id);
                    $data['espacio'] = $this->espacio_fisico->get_by_id($data['pr_factura']->espacios_fisicos_id);
                     $data['rows']=100;
                    $data['tipo_entrada'] =1;
                			$data['estatus_general_id'] = 2;
                
                $u=new Entrada();
                    $sql="select e.costo_total,e.id,e.costo_unitario,e.cantidad,e.cproductos_id,e.cproducto_numero_id,p.descripcion as producto_nombre,n.numero_mm,n.codigo_barras as cod
from 
entradas as e 
join cproductos as p on p.id = e.cproductos_id 
join cproductos_numeracion as n on n.id = e.cproducto_numero_id 
where 
e.ctipo_entrada = '1'
and e.estatus_general_id='2' 
and
e.pr_facturas_id=$id order by e.id asc";   
                  $en=new Entrada();
                    $ql="select sum(costo_total) as total, sum(tasa_impuesto * cantidad * costo_unitario/(100+tasa_impuesto)) as impuesto, sum(cantidad) as cantidad
from entradas
where
pr_facturas_id=$id";
                     // $sql="select * from entradas where pr_facturas_id=$id";   
                    $data['entra']=$u->query($sql);
                    $data['ent_totales']=$en->query($ql);
                    
                } else if ($this->uri->segment(5) == "borrar_entrada") {
                    $id = $this->uri->segment(6);
                    $main_view = false;
                    if (substr(decbin($data['permisos']), 2, 1) == 1) {
                        $factura = $this->pr_factura->get_pr_factura($id);
                        //Identificar si la factura ya se traspaso y ademas ya se recibio en tienda no se pueda cancelar
                        if ($factura->validacion_contable == 0) {
                            $this->db->trans_start();
                            $this->db->query("update entradas set estatus_general_id='2' where pr_facturas_id='$id'");
                            $this->db->query("update pr_facturas set estatus_general_id='2' where id='$factura->id'");
                            $this->db->trans_complete();
                            redirect(base_url() . "index.php/" . $ruta . "/" . $controller . "/" . $funcion . "/" . $subfuncion);
                        } else
                            show_error("La factura no se puede cancelar, debido a que se le ha dado de entrada en la Sucursal.");
                    } else {
                        $main_view = true;
                        //Definir la vista
                        $data['principal'] = "error";
                        $data['error_field'] = "No tiene permisos para deshabilitar la Salida";
                    }
                } else if ($this->uri->segment(5) == "validar_entrada") {
                    $main_view = false;
                    $id = $this->uri->segment(6);
                    $llave = $this->uri->segment(7);
                    //Identificar la llave del usuario
                    $usuario_id = $this->usuario->get_usuario_by_key($llave);
                    //Validar que exista el usuario
                    if ($usuario_id != false) {
                        $permisos1 = $this->usuario_accion->get_permiso($accion_id, $usuario_id, $puestoid, $grupoid);
                        if (substr(decbin($permisos1), 2, 1) == 1) {
                            $u = new Pr_factura();
                            $u->get_by_id($id);
                            $u->usuario_validador_id = $usuario_id;
                            $u->validacion_contable=1;
                            $u->save();
                            $this->db->query("update entradas set estatus_general_id='1' where pr_facturas_id='$id'");
                            redirect(base_url() . "index.php/" . $ruta . "/" . $controller . "/" . $funcion . "/" . $subfuncion);
                        } else {
                            $main_view = true;
                            //Definir la vista
                            $data['principal'] = "error";
                            $data['error_field'] = "No tiene permisos para validar la Factura de Entrada";
                        }
                    } else {
                        $main_view = true;
                        //Definir la vista
                        $data['principal'] = "error";
                        $data['error_field'] = "La llave no esta asociada a un usuario";
                    }
                } else if ($filtrado == "sucursal" && $id > 0) {
                    $main_view = true;
                    //Filtrado por cuenta_id
                    $data['paginacion'] = false;
                    $data['entradas'] = $this->entrada->get_entradas_sucursal_list($id);
                    $data['cta'] = $id;
                    $data['total_registros'] = count($data['entradas']->all);
                } else if ($filtrado == "proveedor" && $id > 0) {
                    $data['paginacion'] = false;
                    $data['entradas'] = $this->entrada->get_entradas_proveedor_list($id);
                    $data['cta'] = $id;
                    $data['total_registros'] = count($data['entradas']->all);
                } else if ($filtrado == "marcas" && $id > 0) {
                    $data['paginacion'] = false;
                    $data['entradas'] = $this->entrada->get_entradas_marcas_list($id);
                    $data['cta'] = $id;
                    if ($data['entradas'] != false)
                        $data['total_registros'] = count($data['entradas']->all);
                    else
                        $data['total_registros'] = 0;
                } else if ($filtrado == "pedido_id" && $id > 0) {
                    $data['paginacion'] = false;
                    $data['entradas'] = $this->entrada->get_entrada_pr_pedido_id($id);
                    $data['cta'] = $id;
                    if ($data['entradas'] != false)
                        $data['total_registros'] = count($data['entradas']->all);
                    else
                        $data['total_registros'] = 0;
                } else if ($filtrado == "factura_id" && $id > 0) {
                    $data['paginacion'] = false;
                    $validacion = 0;
                    $data['entradas'] = $this->entrada->get_entrada_pr_factura_id($id, $validacion);
                    $data['cta'] = $id;
                    if ($data['entradas'] != false)
                        $data['total_registros'] = count($data['entradas']->all);
                    else
                        $data['total_registros'] = 0;
                } else if ($filtrado == "lote_id" && $id > 0) {
                    $data['paginacion'] = false;
                    $validacion = 0;
                    $data['entradas'] = $this->entrada->get_entrada_lote_id($id, $validacion);
                    $data['cta'] = $id;
                    if ($data['entradas'] != false)
                        $data['total_registros'] = count($data['entradas']->all);
                    else
                        $data['total_registros'] = 0;
                } else {
                    $data['paginacion'] = true;
                    $main_view = true;
                    //Definir la vista
                    //Obtener Datos
                    $this->load->library('pagination');
                    $config['base_url'] = base_url() . "index.php/" . $ruta . "/" . $controller . "/" . $funcion . "/list_entradas/";
                    $config['per_page'] = '40';
                    $page = $this->uri->segment(5);
                    //Identificar el numero de pagina en el paginador si existe
                    if ($page > 0) {
                        $offset = $page;
                    } else if ($page == '') {
                        $offset = 0;
                    } else {
                        $offset = 0;
                    }
                    $data['total_registros'] = $this->entrada->get_count_entradas(0,$GLOBALS['ubicacion_id']);  //Sin validar
                    $data['entradas'] = $this->entrada->get_entradas_list($offset, $config['per_page'], 0,$GLOBALS['ubicacion_id']); //Sin autorizar
                    $config['total_rows'] = $data['total_registros'];
                    $this->pagination->initialize($config);
                    $cta = 0;
                }
            } //Final del Bloque Entradas sin Validar
            else if ($subfuncion == "list_entradas_validadas") {
                $data['principal'] = $ruta . "/" . $subfuncion;
                $data['pag'] = 1;
                //			$data['principal']=$ruta."/".$subfuncion;
                $filtrado = $this->uri->segment(6);
                $id = $this->uri->segment(7);
                $this->load->model('ctipo_factura');
                if ($this->uri->segment(5) == "editar_entrada_validada") {
                    //Cargar los datos para el formulario
                    $edit_usr = $this->uri->segment(5);
                    $id = $this->uri->segment(6);
                    //Definir la vista
                    $data['principal'] = $ruta . "/editar_factura_entrada";
                    //Obtener Datos
                    $data['pr_factura'] = $this->pr_factura->get_pr_factura($id);
                    $data['pr_pedido'] = $this->pr_pedido->get_pr_pedido_entrada($data['pr_factura']->pr_pedido_id);
                    $data['tipos_facturas'] = $this->ctipo_factura->get_ctipos_factura();
                    $data['empresa'] = $this->empresa->get_empresa($empresa_id);
                    $data['proveedores'] = $this->proveedor->get_by_id($data['pr_factura']->cproveedores_id);
                    $data['espacio'] = $this->espacio_fisico->get_by_id($data['pr_factura']->espacios_fisicos_id);
                    
                } else if ($this->uri->segment(5) == "borrar_entrada") {
                    $id = $this->uri->segment(6);
                    $main_view = false;
                    $factura = $this->pr_factura->get_pr_factura($id);
                    if (substr(decbin($data['permisos']), 2, 1) == 1 and $factura->validacion_contable == 0) {
                        $this->db->trans_start();
                        $this->db->query("update entradas set estatus_general_id=2 where pr_facturas_id='$id'");
                        //					$this->db->query("delete from entradas where pr_facturas_id='$id'");
                        $this->db->query("update pr_facturas set estatus_general_id=2 where id='$id'");
                        $this->db->query("update pr_pedidos set cpr_estatus_pedido_id='2' where id='$factura->pr_pedido_id'");
                        $this->db->query("update lotes_pr_facturas set cestatus_lote_id='0' where pr_pedido_id='$factura->pr_pedido_id'");
                        $this->db->trans_complete();
                        redirect(base_url() . "index.php/" . $ruta . "/" . $controller . "/" . $funcion . "/" . $subfuncion);
                    } else {
                        $main_view = true;
                        //Definir la vista
                        $data['principal'] = "error";
                        $data['error_field'] = "No tiene permisos para deshabilitar la Entrada o bien ya se valido contablemente";
                    }
                } else if ($filtrado == "sucursal" && $id > 0) {
                    $main_view = true;
                    //Filtrado por cuenta_id
                    $data['paginacion'] = false;
                    $data['entradas'] = $this->entrada->get_entradas_sucursal_list($id);
                    $data['cta'] = $id;
                    $data['total_registros'] = count($data['entradas']->all);
                } else if ($filtrado == "proveedor" && $id > 0) {
                    $data['paginacion'] = false;
                    $data['entradas'] = $this->entrada->get_entradas_proveedor_list($id);
                    $data['cta'] = $id;
                    $data['total_registros'] = count($data['entradas']->all);
                } else if ($filtrado == "marcas" && $id > 0) {
                    $data['paginacion'] = false;
                    $data['entradas'] = $this->entrada->get_entradas_marcas_list($id);
                    $data['cta'] = $id;
                    if ($data['entradas'] != false)
                        $data['total_registros'] = count($data['entradas']->all);
                    else
                        $data['total_registros'] = 0;
                } else if ($filtrado == "pedido_id" && $id > 0) {
                    $data['paginacion'] = false;
                    $data['entradas'] = $this->entrada->get_entrada_pr_pedido_id($id);
                    $data['cta'] = $id;
                    if ($data['entradas'] != false)
                        $data['total_registros'] = count($data['entradas']->all);
                    else
                        $data['total_registros'] = 0;
                } else if ($filtrado == "factura_id" && $id > 0) {
                    $data['paginacion'] = false;
                    $validacion = 1;
                    $data['entradas'] = $this->entrada->get_entrada_pr_factura_id($id, $validacion);
                    $data['cta'] = $id;
                    if ($data['entradas'] != false)
                        $data['total_registros'] = count($data['entradas']->all);
                    else
                        $data['total_registros'] = 0;
                } else if ($filtrado == "lote_id" && $id > 0) {
                    $data['paginacion'] = false;
                    $validacion = 1;
                    $data['entradas'] = $this->entrada->get_entrada_lote_id($id, $validacion);
                    $data['cta'] = $id;
                    if ($data['entradas'] != false) {
                        $data['total_registros'] = count($data['entradas']->all);
                    } else {
                        $data['total_registros'] = 0;
                    }
                } else {
                    $data['paginacion'] = true;
                    $main_view = true;
                    //Definir la vista
                    //Obtener Datos
                    $this->load->library('pagination');
                    $config['base_url'] = base_url() . "index.php/" . $ruta . "/" . $controller . "/" . $funcion . "/list_entradas_validadas/";
                    $config['per_page'] = '60';
                    $page = $this->uri->segment(5);
                    //Identificar el numero de pagina en el paginador si existe
                    if ($page > 0) {
                        $offset = $page;
                    } else if ($page == '') {
                        $offset = 0;
                    } else {
                        $offset = 0;
                    }
                    $data['total_registros'] = $this->entrada->get_count_validas(1,$GLOBALS['ubicacion_id']);  //Validadas
                    $data['entradas'] = $this->entrada->get_entradas_validadas($offset, $config['per_page'], 1,$GLOBALS['ubicacion_id']); //Validadas
                    $config['total_rows'] = $data['total_registros'];
                    $this->pagination->initialize($config);
                    $cta = 0;
                }
            } else if ($subfuncion == "list_devoluciones_proveedor") {
                $page = $this->uri->segment(5);
                if($page == "edit"){
                    $id = $this->uri->segment(6);
                    $data['principal'] = $ruta . "/editar_devolucion_proveedor";
                    $data['title'] = "Editar devolucion proveedor";
                    $this->load->model("devolucion_proveedor");
                    $dp = new Devolucion_proveedor($id);
                    $dp->include_related('proveedor','razon_social');
                    $data['devolucion'] = $dp->get();  
                    $detalles = $this->devolucion_proveedor->detalles_devolucion($id);
                    $data['detalles'] = $detalles;                
                    $data['tipos_entrega'] = array("domicilio"=>"Domicilio","ocurre"=>"Ocurre");
                    $data['espacio_fisico_id'] = $GLOBALS['ubicacion_id'];
                } else {
                    $data['principal'] = $ruta . "/" . $subfuncion;
                    $data['title'] = "Listado devoluciones a proveedor";
                    $this->load->model("devolucion_proveedor");
                    $dp = new Devolucion_proveedor();

                    $data['url'] = $url = base_url() . "index.php/" . $ruta . "/" . $controller . "/" . $funcion . "/list_devoluciones_proveedor/";
               
                    $page = $page == "" ? 0 : $page;
                    //Identificar el numero de pagina en el paginador si existe
                    $offset = $page > 0 ? $page : 0;
                    $data['total_registros']= $dp->count();
                    $this->load_paginacion($url,$data['total_registros'],20);
                    // icluye la razon social del proveedor
                    $dp->include_related('proveedor','razon_social');
                    $dp->where("estatus_general_id", "1");
                    $data['devoluciones'] = $dp->get(20,$offset);
                }
            } else if ($subfuncion == "list_inventario_inicial") {
                $data['principal'] = $ruta . "/" . $subfuncion;
                $main_view = true;
                $data['pag'] = 1;
                $filtrado = $this->uri->segment(6);
                $id = $this->uri->segment(7);
                $this->load->model('ctipo_factura');
                if ($this->uri->segment(5) == "editar_factura_entrada") {
                    //Cargar los datos para el formulario
                    $edit_usr = $this->uri->segment(5);
                    $id = $this->uri->segment(6);
                    //Definir la vista
                    $data['principal'] = $ruta . "/editar_inventario_inicial";
                    //Obtener Datos
                    $data['pr_factura'] = $this->pr_factura->get_pr_factura($id);
                    $data['rows']=5000;
                    $data['tipos_facturas'] = $this->ctipo_factura->get_ctipos_factura();
                    $data['empresa'] = $this->empresa->get_empresa($empresa_id);
                    $data['proveedores'] = $this->proveedor->get_by_id($data['pr_factura']->cproveedores_id);                 $data['tipo_entrada'] = 6;
                $data['estatus_general_id'] = 1;
                    $data['espacio'] = $this->espacio_fisico->get_by_id($data['pr_factura']->espacios_fisicos_id);
                    $u=new Entrada();
                    $sql="select e.costo_total,e.id,e.costo_unitario,e.cantidad,e.cproductos_id,e.cproducto_numero_id,p.descripcion as producto_nombre,n.numero_mm,n.codigo_barras as cod
from 
entradas as e 
join cproductos as p on p.id = e.cproductos_id 
join cproductos_numeracion as n on n.id = e.cproducto_numero_id 
where 
e.ctipo_entrada = '6' 
and
e.estatus_general_id='1' and e.pr_facturas_id=$id order by e.id asc";   
                  $en=new Entrada();
                    $ql="select sum(costo_total) as total, sum(tasa_impuesto * cantidad * costo_unitario/(100+tasa_impuesto)) as impuesto, sum(cantidad) as cantidad
from entradas
where
pr_facturas_id=$id";
                     // $sql="select * from entradas where pr_facturas_id=$id";   
                    $data['entra']=$u->query($sql);
                    $data['ent_totales']=$en->query($ql);
                   
                } else if ($this->uri->segment(5) == "borrar_entrada") {
                    $id = $this->uri->segment(6);
                    $main_view = false;
                    if (substr(decbin($data['permisos']), 2, 1) == 1) {
                        $factura = $this->pr_factura->get_pr_factura($id);
                        //Identificar si la factura ya se traspaso y ademas ya se recibio en tienda no se pueda cancelar
                        if ($factura->validacion_contable == 0) {
                            $this->db->trans_start();
                            $this->db->query("update entradas set estatus_general_id='2' where pr_facturas_id='$id'");
                            $this->db->query("update pr_facturas set estatus_general_id='2' where id='$factura->id'");
                            $this->db->trans_complete();
                            redirect(base_url() . "index.php/" . $ruta . "/" . $controller . "/" . $funcion . "/" . $subfuncion);
                        } else
                            show_error("La factura no se puede cancelar, debido a que se le ha dado de entrada en la Sucursal.");
                    } else {
                        $main_view = true;
                        //Definir la vista
                        $data['principal'] = "error";
                        $data['error_field'] = "No tiene permisos para deshabilitar la Salida";
                    }
                } else if ($filtrado=="buscar") {
		$espacio=$GLOBALS['ubicacion_id'];
                 $en = new Entrada();
                    $where=" where e.estatus_general_id='1' and ctipo_entrada='6' and e.espacios_fisicos_id=$espacio ";
                     if(isset($_POST['espacio_fisico_id'])){
                         $espacio=$_POST['espacio_fisico_id'];
                        if($espacio>0)
                        $where=$where." and e.espacios_fisicos_id =".$espacio;
                    }
                    if(isset($_POST['cproveedor_id'])){
                         $prove_id=strtoupper($_POST['cproveedor_id']);
                        if($prove_id>0)
                        $where=$where." and e.cproveedores_id =".$prove_id;
                    }
     
                     if(isset($_POST['factura_id'])){
                        $clave=strtoupper($_POST['factura_id']);
                        if($clave>0)
                        $where=$where." and e.pr_facturas_id = ".$clave;
                    }
                 $sql="select distinct on (e.pr_facturas_id) e.pr_facturas_id, e.id as id1, date(prf.fecha)fecha, prf.monto_total as importe_factura, pr.razon_social as proveedor,prf.folio_factura, prf.pr_pedido_id, ef.tag as espacio_fisico, eg.tag as estatus, e.lote_id, cel.tag as estatus_traspaso ".
				"from entradas as e ".
				"left join cproveedores as pr on pr.id=e.cproveedores_id ".
				"left join pr_facturas as prf on prf.id=e.pr_facturas_id ".
				"left join espacios_fisicos as ef on ef.id=e.espacios_fisicos_id ".
                         "left join estatus_general as eg on eg.id=e.estatus_general_id ".
                               "left join lotes_pr_facturas as lf on lf.pr_factura_id=prf.id ".
				"left join cestatus_lotes as cel on cel.id=lf.cestatus_lote_id "
				 .$where."order by e.pr_facturas_id";
                      


       
                       $data['entradas']=$en->query($sql);
                    $data['total_registros']=count($data['entradas']->all);
                    $this->load->library('pagination');
					$config['base_url'] = base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_inventario_inicial/1/buscar";
                     
                     
                    

            }
                
               else {
                    $data['paginacion'] = true;
                    $main_view = true;
                    //Definir la vista
                    //Obtener Datos
                    $this->load->library('pagination');
                    $config['base_url'] = base_url() . "index.php/" . $ruta . "/" . $controller . "/" . $funcion . "/list_inventario_inicial/";
                    $config['per_page'] = '40';
                    $page = $this->uri->segment(5);
                    //Identificar el numero de pagina en el paginador si existe
                    if ($page > 0) {
                        $offset = $page;
                    } else if ($page == '') {
                        $offset = 0;
                    } else {
                        $offset = 0;
                    }
                    $data['total_registros'] = $this->entrada->get_inventario_inicial_count($GLOBALS['ubicacion_id']); //Sin validar
                    $data['entradas'] = $this->entrada->get_inventario_inicial($offset, $config['per_page'],$GLOBALS['ubicacion_id']); //Sin autorizar
                    $config['total_rows'] = $data['total_registros'];
                    $this->pagination->initialize($config);
                    $cta = 0;
                }
            } 




//Final de entradas con validacion
            else if ($subfuncion == "alta_salidas") {
                if ($this->uri->segment(5) == "salida_cl_pedido") {
                    //Definir la vista
                    $main_view = true;
                    $id = $this->uri->segment(6);
                    $data['principal'] = $ruta . "/" . $subfuncion;
                    /* Cargar Catalogos */
                    $data['validation'] = $this->almacen_validacion->validacion_salida_venta();
                    $data['rows_pred'] = 0;
                    $data['pedido_venta'] = $this->cl_pedido->get_cl_pedido($id);
                    $data['cl_detalle'] = $this->cl_detalle_pedido->get_cl_detalles_pedido_parent($id);
                    $data['empresa'] = $this->empresa->get_empresa($empresa_id);
                    $data['clientes'] = $this->cliente->get_clientes();
                    $data['formas_cobro'] = $this->ccl_forma_cobro->get_ccl_formas_cobro();
                    $data['productos'] = $this->producto->get_cproductos_detalles();
                    $data['estatus'] = $this->ccl_estatus_pedido->get_ccl_estatus_pedido_all();
                    $data['cl_pedido'] = $this->cl_pedido->get_cl_pedido($id);
                    if ($data['cl_detalle'] != false) {
                        $data['renglon_adic'] = count($data['cl_detalle']->all) + 1;
                        $data['rows'] = count($data['cl_detalle']->all);
                    } else {
                        $data['renglon_adic'] = 2;
                    }
                } else if ($this->uri->segment(5) == "cancelar_cl_pedido") {
                    $id = $this->uri->segment(6);
                    $main_view = false;
                    if (substr(decbin($data['permisos']), 2, 1) == 1) {
                        $this->db->trans_start();
                        $this->db->query("update cl_pedidos set ccl_estatus_pedido_id=1 where id='$id'");
                        $this->db->trans_complete();
                        redirect(base_url() . "index.php/" . $ruta . "/" . $controller . "/" . $funcion . "/" . $subfuncion);
                    } else {
                        $main_view = true;
                        //Definir la vista
                        $data['principal'] = "error";
                        $data['error_field'] = "No tiene permisos para deshabilitar el pedido";
                    }
                } else {
                    //Definir la vista
                    $data['principal'] = $ruta . "/list_cl_pedidos";
                    $data['title'] = "Alta de Salida por Venta. Paso 1 Seleccione el Pedido de Venta";
                    // load pagination class
                    $this->load->library('pagination');
                    $config['base_url'] = base_url() . "index.php/" . $ruta . "/" . $controller . "/" . $funcion . "/alta_salidas/";
                    $config['per_page'] = '15';
                    $page = $this->uri->segment(5);
                    //Identificar el numero de pagina en el paginador si existe
                    if ($page > 0) {
                        $offset = $page;
                    } else if ($page == '') {
                        $offset = 0;
                    } else {
                        $offset = 0;
                    }

                    $u1 = new Cl_pedido();
                    $u1->where('ccl_estatus_pedido_id', '2');
                    $u1->where('ccl_tipo_pedido_id', '1');
                    $u1->get();
                    $total_registros = $u1->c_rows;
                    $data['cl_pedidos'] = $this->cl_pedido->get_cl_pedidos_list_auth($offset, $config['per_page'], $GLOBALS['ubicacion_id']);
                    $data['total_registros'] = $total_registros;
                    $config['total_rows'] = $total_registros;
                    $this->pagination->initialize($config);
                }
            } else if ($subfuncion == "list_salidas") {

                if ($this->uri->segment(5) == "editar_salida") {
                    //Cargar los datos para el formulario
                    $edit_usr = $this->uri->segment(5);
                    $id = $this->uri->segment(6);
                    //Definir la vista
                    $data['principal'] = $ruta . "/editar_salida";
                    //Obtener Datos
                } else if ($this->uri->segment(5) == "borrar_salida") {
                    $id = $this->uri->segment(6);

                    $main_view = false;
                    if (substr(decbin($data['permisos']), 2, 1) == 1) {
                        $factura = $this->cl_factura->get_cl_factura($id);
                        $this->db->trans_start();
                        $this->db->query("delete from salidas where cl_facturas_id='$id'");
                        $this->db->query("update cl_facturas set estatus_general_id='2' where id='$factura->id'");
                        $this->db->query("update cl_pedidos set ccl_estatus_pedido_id=1 where id='$factura->cl_pedido_id'");
                        $this->db->trans_complete();
                        redirect(base_url() . "index.php/" . $ruta . "/" . $controller . "/" . $funcion . "/" . $subfuncion);
                    } else {
                        $main_view = true;
                        //Definir la vista
                        $data['principal'] = "error";
                        $data['error_field'] = "No tiene permisos para deshabilitar la Salida";
                    }
                } else {
                    $main_view = true;
                    //Cargar los datos para el formulario
                    //Definir la vista
                    $data['principal'] = $ruta . "/" . $subfuncion;
                    //Obtener Datos
                    // load pagination class
                    $this->load->library('pagination');
                    $config['base_url'] = base_url() . "index.php/" . $ruta . "/" . $controller . "/" . $funcion . "/list_salidas/";
                    $config['per_page'] = '40';
                    $page = $this->uri->segment(5);
                    //Identificar el numero de pagina en el paginador si existe
                    if ($page > 0) {
                        $offset = $page;
                    } else if ($page == '') {
                        $offset = 0;
                    } else {
                        $offset = 0;
                    }

                    $data['total_registros'] = $this->salida->get_salidas_list_count($GLOBALS['ubicacion_id']);
                    $data['salidas'] = $this->salida->get_salidas_list($offset, $config['per_page'], $GLOBALS['ubicacion_id']);
                    $config['total_rows'] = $data['total_registros'];
                    $this->pagination->initialize($config);
                }
            } //Final del Bloque Salidas por Facturacion
            else if ($subfuncion == "alta_salida_tras") {
                $main_view = true;
                $this->load->model('traspaso');
                $data['principal'] = $ruta . "/" . $subfuncion;
                /* Cargar Catalogos */
                $data['validation'] = $this->almacen_validacion->validacion_entrada_compra();
                $data['empresa'] = $this->empresa->get_empresa($empresa_id);
                $data['espacios'] = $this->espacio_fisico->get_tiendas_almacenes_mtrx($GLOBALS['ubicacion_id']);
                $data['espacios_fisicos_tag'] = $GLOBALS['ubicacion_nombre'];
                $data['espacios_fisicos_id'] = $GLOBALS['ubicacion_id'];
                $data['rows'] = 300;
                $data['tipo_salida'] = 1; //Compra directa
            } else if ($subfuncion == "list_traspasos") {
                $this->load->model("traspaso");
                $data['pag'] = 1;
                $data['principal'] = $ruta . "/" . $subfuncion;
                $filtrado = $this->uri->segment(6);
                $id = $this->uri->segment(7);
										if ($this->uri->segment(5) == "editar_traspaso") {
                    //Cargar los datos para el formulario                    
                    $edit_usr = $this->uri->segment(5);
                    $id = $this->uri->segment(6);
                    //Definir la vista
                    $data['principal'] = $ruta . "/editar_traspaso";
                    //Obtener Datos
                $data['id_tras'] = $id;
                $data['empresa'] = $this->empresa->get_empresa($empresa_id);
                $data['espacios'] = $this->espacio_fisico->get_tiendas_almacenes_mtrx($GLOBALS['ubicacion_id']);
                $data['espacios_fisicos_tag'] = $GLOBALS['ubicacion_nombre'];
                $data['espacios_fisicos_id'] = $GLOBALS['ubicacion_id'];
                $data['rows'] = 300;
                $data['tipo_salida'] = 1; //Compra directa
                $u=new Salida();
                    $sql="select s.*,p.descripcion as producto_nombre,c.tag,p.id as cproducto_id,pn.id as cproducto_numero_id,pn.codigo_barras as cod,pn.numero_mm 
                        from salidas as s 
                        join cproductos as p 
                        on p.id=s.cproductos_id
                        join cproductos_numeracion as pn on pn.id=s.cproducto_numero_id
                        join cproductos_color as c on p.ccolor_id = c.id	
                        where s.traspaso_id =$id and s.estatus_general_id=1 order by s.id asc";   
                 
                     // $sql="select * from entradas where pr_facturas_id=$id";   
                    $data['entra']=$u->query($sql);
                    //$data['ent_totales']=$en->query($ql);
                    
                }else if ($filtrado == "sucursal" && $id > 0) {
                    $main_view = true;
                    //Filtrado por cuenta_id
                    $data['paginacion'] = false;
                    $data['traspasos'] = $this->lote->get_traspasos_pendientes_sucursal($id, '1'); //2= Enviado
                    $data['cta'] = $id;
                    if ($data['traspasos'] != false)
                        $data['total_registros'] = count($data['traspasos']->all);
                    else
                        $data['total_registros'] = 0;
                } else if ($filtrado == "proveedor" && $id > 0) {
                    $data['paginacion'] = false;
                    $data['traspasos'] = $this->lote->get_traspasos_pendientes_proveedor($id, '1');
                    $data['cta'] = $id;
                    if ($data['traspasos'] != false)
                        $data['total_registros'] = count($data['traspasos']->all);
                    else
                        $data['total_registros'] = 0;
                } else if ($filtrado == "marcas" && $id > 0) {
                    $data['paginacion'] = false;
                    $data['traspasos'] = $this->lote->get_traspasos_pendientes_marcas($id, '2');
                    $data['cta'] = $id;
                    if ($data['traspasos'] != false)
                        $data['total_registros'] = count($data['traspasos']->all);
                    else
                        $data['total_registros'] = 0;
                } else if ($this->uri->segment(5) == "cancelar_traspaso") {
                    $this->load->model('traspaso');
                    $id = $this->uri->segment(6);
                    $main_view = false;
                    if (substr(decbin($data['permisos']), 2, 1) == 1) {
                        $t = new Traspaso();
                        $t->get_by_id($id);
                        //Identificar si la factura ya se traspaso y ademas ya se recibio en tienda no se pueda cancelar
                        if ($t->cestatus_traspaso_id != 2) {
                            $salidas = new Salida();
                            $salidas->where('traspaso_id', $t->id)->get();
                            foreach($salidas as $s)
                            {
                                $s->estatus_general_id = 2;
                                $s->save();
                            }
                            //$this->db->query("update salidas set estatus_general_id='2' where traspaso_id=$t->id ");
                            //$this->db->query("update entradas set traspaso_id='0' where traspaso_id=$t->id ");
                            $t->estatus_general_id = 2;
                            $t->cestatus_traspaso_id = 3;
                            $t->save();
                            redirect(base_url() . "index.php/" . $ruta . "/" . $controller . "/" . $funcion . "/" . $subfuncion);
                        } else
                            show_error("El traspaso no se puede cancelar, debido a que se le ha dado de entrada en la Sucursal.");
                    } else {
                        $main_view = true;
                        //Definir la vista
                        $data['principal'] = "error";
                        $data['error_field'] = "No tiene permisos para deshabilitar el Traspaso";
                    }
                } else {
                    //	      $id=$this->uri->segment(6);
                    $data['paginacion'] = true;
                    //Cargar los datos para el listado de pedidos de compra en proceso
                    $data['principal'] = $ruta . "/" . $subfuncion;
                    $this->load->library('pagination');
                    // load pagination class
                    $data['title'] = "Listado de Traspasos Enviados para recepción en Sucursal";
                    $config['base_url'] = base_url() . "index.php/" . $ruta . "/" . $controller . "/" . $funcion . "/list_traspasos";
                    $config['per_page'] = '80';
                    $page = $this->uri->segment(5);
                    //Identificar el numero de pagina en el paginador si existe
                    if ($page > 0)
                        $offset = $page;
                    else
                        $offset = 0;

                    $data['total_registros'] = $config['total_rows'] = $this->traspaso->get_traspasos_enviados_count($GLOBALS['ubicacion_id'], 1); //Enviados
                    $data['traspasos'] = $this->traspaso->get_traspasos_enviados($offset, $config['per_page'], 1, $GLOBALS['ubicacion_id']); //enviados
                    $this->pagination->initialize($config);
                    $data['cta'] = 0;
                }
            }else if ($subfuncion == 'list_traspasos_recibido') {
                $this->load->model("traspaso");
                $data['pag'] = 1;
                $data['principal'] = $ruta . "/" . $subfuncion;
                $filtrado = $this->uri->segment(6);
                $id = $this->uri->segment(7);
                if ($filtrado == "sucursal" && $id > 0) {
                    $main_view = true;
                    //Filtrado por cuenta_id
                    $data['paginacion'] = false;
                    $data['traspasos'] = $this->lote->get_traspasos_pendientes_sucursal($id, '3'); //2= Enviado
                    $data['cta'] = $id;
                    if ($data['traspasos'] != false)
                        $data['total_registros'] = count($data['traspasos']->all);
                    else
                        $data['total_registros'] = 0;
                } else if ($filtrado == "proveedor" && $id > 0) {
                    $data['paginacion'] = false;
                    $data['traspasos'] = $this->lote->get_traspasos_pendientes_proveedor($id, '3');
                    $data['cta'] = $id;
                    if ($data['traspasos'] != false)
                        $data['total_registros'] = count($data['traspasos']->all);
                    else
                        $data['total_registros'] = 0;
                } else if ($filtrado == "marcas" && $id > 0) {
                    $data['paginacion'] = false;
                    $data['traspasos'] = $this->lote->get_traspasos_pendientes_marca($id, '3');
                    $data['cta'] = $id;
                    if ($data['traspasos'] != false)
                        $data['total_registros'] = count($data['traspasos']->all);
                    else
                        $data['total_registros'] = 0;
                } else {
                    $data['paginacion'] = true;
                    $data['principal'] = $ruta . "/" . $subfuncion;
                    $this->load->library('pagination');
                    $data['title'] = "Listado de Traspasos Entregados en Sucursal";
                    $config['base_url'] = base_url() . "index.php/" . $ruta . "/" . $controller . "/" . $funcion . "/list_traspasos_recibido";
                    $config['per_page'] = '80';
                    $page = $this->uri->segment(5);
                    //Identificar el numero de pagina en el paginador si existe
                    if ($page > 0) {
                        $offset = $page;
                    } else if ($page == '') {
                        $offset = 0;
                    } else {
                        $offset = 0;
                    }
                    $data['total_registros'] = $config['total_rows'] = $this->traspaso->get_traspasos_enviados_count($GLOBALS['ubicacion_id'], 2); //Recibidos
                    $data['traspasos'] = $this->traspaso->get_traspasos_enviados($offset, $config['per_page'], 2); //Recibidos
                    $this->pagination->initialize($config);
                }
            } else if ($subfuncion == 'list_salidas_canceladas') {
                $data['principal'] = $ruta . "/" . $subfuncion;
                $this->load->library('pagination');
                $data['title'] = "Listado de Salidas Canceladas";
                $config['base_url'] = base_url() . "index.php/" . $ruta . "/" . $controller . "/" . $funcion . "/list_salidas_canceladas";
                $config['per_page'] = '50';
                $page = $this->uri->segment(5);
                //Identificar el numero de pagina en el paginador si existe
                if ($page > 0) {
                    $offset = $page;
                } else if ($page == '') {
                    $offset = 0;
                } else {
                    $offset = 0;
                }
                $u1 = $this->salida->get_salidas_canceladas_list_count($GLOBALS['ubicacion_id']);
                if ($u1 == false)
                    show_error("No hay datos disponibles");
                $total_registros = $u1;
                $data['salidas'] = $this->salida->get_salidas_canceladas_list($offset, $config['per_page'], $GLOBALS['ubicacion_id']);
                $data['total_registros'] = $total_registros;
                $config['total_rows'] = $total_registros;
                $this->pagination->initialize($config);
            } //Final list_traspasos_recibido
            else if ($subfuncion == "alta_entrada_bonificacion") {
                //Definir la vista
                $data['principal'] = $ruta . "/" . $subfuncion;
                /* Cargar Catalogos */
                $data['rows_pred'] = 0;
                $data['validation'] = $this->almacen_validacion->validacion_entrada_compra();
                $data['empresa'] = $this->empresa->get_empresa($empresa_id);
                $data['proveedores'] = $this->proveedor->get_proveedores_hab();
                $data['tipos_entradas'] = $this->tipo_entrada->get_ctipos_entradas();
                $data['espacios_fisicos'] = $this->espacio_fisico->get_espacios_f();
                $data['productos'] = $this->producto->get_cproductos_detalles();
                $data['rows'] = 30;
            } else if ($subfuncion == "list_entrada_bonificacion") {
                if ($this->uri->segment(5) == "borrar_entrada") {
                    $id = $this->uri->segment(6);
                    $main_view = false;
                    if (substr(decbin($data['permisos']), 2, 1) == 1) {
                        $u = new Entrada();
                        $u->get_by_id($id);
                        $u->estatus_general_id = 2;
                        redirect(base_url() . "index.php/" . $ruta . "/" . $controller . "/" . $funcion . "/" . $subfuncion);
                    } else {
                        $main_view = true;
                        //Definir la vista
                        $data['principal'] = "error";
                        $data['error_field'] = "No tiene permisos para deshabilitar la Entrada";
                    }
                } else {
                    $main_view = true;
                    //Definir la vista
                    $data['principal'] = $ruta . "/" . $subfuncion;
                    //Obtener Datos
                    // load pagination class
                    $this->load->library('pagination');
                    $config['base_url'] = base_url() . "index.php/" . $ruta . "/" . $controller . "/" . $funcion . "/$subfuncion/";
                    $config['per_page'] = '40';
                    $page = $this->uri->segment(5);
                    //Identificar el numero de pagina en el paginador si existe
                    if ($page > 0) {
                        $offset = $page;
                    } else if ($page == '') {
                        $offset = 0;
                    } else {
                        $offset = 0;
                    }
                    $data['total_registros'] = $this->entrada->get_entradas_boni_list_count($GLOBALS['ubicacion_id']);
                    $data['entradas'] = $this->entrada->get_entradas_boni_list($offset, $config['per_page'], $GLOBALS['ubicacion_id']);
                    $config['total_rows'] = $data['total_registros'];
                    $this->pagination->initialize($config);
                }
            } //Final del Bloque Entradas bonificacion
            else if ($subfuncion == "alta_devolucion_nc") {
                if ($this->uri->segment(5) == "detalles_factura") {
                    $main_view = false;
                    //Obtener los detalles de una factura determinada
                    $id = $_POST['facturas'];
                    $cliente_id = $_POST['cliente'];
                    $data['salidas'] = $this->salida->get_salidas_by_factura($id);
                    if ($data['salidas'] == false)
                        show_error("La factura no tiene detalles o fue cancelada");
                    $this->load->view('almacen/detalles_facturas', $data);
                } else {
                    //Definir la vista
                    $data['principal'] = $ruta . "/" . $subfuncion;
                    /* Cargar Catalogos */
                    $data['empresa'] = $this->empresa->get_empresa($empresa_id);
                    $data['clientes'] = $this->cliente->get_clientes();
                }
            } //DEvoluciones a clientes
            else if ($subfuncion == "list_devolucion_venta") {
                $main_view = true;
                //Definir la vista
                $data['principal'] = $ruta . "/" . $subfuncion;
                //Obtener Datos
                // load pagination class
                $this->load->library('pagination');
                $config['base_url'] = base_url() . "index.php/" . $ruta . "/" . $controller . "/" . $funcion . "/$subfuncion/";
                $config['per_page'] = '40';
                $page = $this->uri->segment(5);
                //Identificar el numero de pagina en el paginador si existe
                if ($page > 0)
                    $offset = $page;
                else if ($page == '')
                    $offset = 0;
                else
                    $offset = 0;

                $data['total_registros'] = $this->salida->get_salidas_dev_list_count($GLOBALS['ubicacion_id']);
                $data['salidas'] = $this->salida->get_salidas_dev_list($offset, $config['per_page'], $GLOBALS['ubicacion_id']);
                $config['total_rows'] = $data['total_registros'];
                $this->pagination->initialize($config);
            } //Final de Devoluciones de clientes
            else if ($subfuncion == "alta_devolucion_compra") {
                if ($this->uri->segment(5) == "detalles_factura_compra") {
                    $main_view = false;
                    //Obtener los detalles de una factura determinada
                    $id = $_POST['facturas'];
                    $proveedor_id = $_POST['proveedor'];
                    $data['entradas'] = $this->entrada->get_entradas_by_factura($id);
                    if ($data['entradas'] == false)
                        show_error("La factura no tiene detalles o fue cancelada");
                    $this->load->view('almacen/detalles_facturas_compra', $data);
                } else {
                    //Definir la vista
                    $data['principal'] = $ruta . "/" . $subfuncion;
                    /* Cargar Catalogos */
                    $data['empresa'] = $this->empresa->get_empresa($empresa_id);
                    $data['proveedores'] = $this->proveedor->get_proveedores_hab();
                }
            } //DEvoluciones a proveedores
            else if ($subfuncion == "list_devolucion_compra") {
                $main_view = true;
                //Definir la vista
                $data['principal'] = $ruta . "/" . $subfuncion;
                //Obtener Datos
                // load pagination class
                $this->load->library('pagination');
                $config['base_url'] = base_url() . "index.php/" . $ruta . "/" . $controller . "/" . $funcion . "/$subfuncion/";
                $config['per_page'] = '40';
                $page = $this->uri->segment(5);
                //Identificar el numero de pagina en el paginador si existe
                if ($page > 0)
                    $offset = $page;
                else if ($page == '')
                    $offset = 0;
                else
                    $offset = 0;

                $data['total_registros'] = $this->salida->get_salidas_dev_list_count($GLOBALS['ubicacion_id']);
                $data['salidas'] = $this->salida->get_salidas_dev_list($offset, $config['per_page'], $GLOBALS['ubicacion_id']);
                $config['total_rows'] = $data['total_registros'];
                $this->pagination->initialize($config);
            }

            /** Bloque para gestionar pedidos de traspaso desde el almacen */
            else if ($subfuncion == "alta_traspaso_stock_almacen") {
                $this->load->model("espacio_stock");
                if ($this->uri->segment(5) == '') {
                    //Cargar miniform donde se elija la plantilla de Stock que se utilizara para generar el traspaso
                    $main_view = true;
                    //Definir la vista
                    $data['principal'] = $ruta . "/" . $subfuncion;
                    //Obtener Datos
                    $data['empresa'] = $this->empresa->get_empresa($GLOBALS['empresa_id']);
                    $data['plantillas'] = $this->espacio_stock->get_espacio_stocks_almacen($GLOBALS['empresa_id']);
                    if ($data['plantillas'] == false)
                        show_error("No hay plantillas de Stock Habilitadas");
                } else if ($this->uri->segment(5) == 'generar_pedido') {
                    //Traer la plantilla de Stock y generar el formulario del traspaso
                    $main_view = true;
                    //Definir la vista
                    $data['principal'] = $ruta . "/alta_traspaso_plantilla";
                    //Obtener Datos
                    $stock_id = $_POST['stock_id'];
                    $this->load->model("stock_detalle");
                    $this->load->model("espacio_stock");
                    $this->load->model("almacen");
                    $stock = $this->espacio_stock->get_espacio_stocks_ubicacion($stock_id);
                    $productos1 = $this->stock_detalle->get_stock_detalle_by_stock_id_tienda($stock_id);
                    if ($productos1 == false)
                        show_error("La plantilla de stock no posee productos, comuniquese con el Supervisor de Sucursales");
                    $mtx_producto = array();
                    $r = 0;
                    foreach ($productos1->all as $linea) {
                        $mtx_producto[$r]['cproducto_id'] = $linea->cproducto_id;
                        $existencia = $this->almacen->existencias($linea->cproducto_id, "where espacios_fisicos_id='$stock->espacio_fisico_id' ");
                        $diff = $linea->cantidad - abs($existencia['existencias']);
                        if ($diff < 0)
                            $diff = 0;
                        $mtx_producto[$r]['cantidad'] = $diff;
                        $mtx_producto[$r]['cantidad_default'] = $linea->cantidad;
                        $mtx_producto[$r]['descripcion'] = $linea->descripcion . " " . $linea->presentacion;
                        $r+=1;
                    }
                    $data['empresa'] = $this->empresa->get_empresa($GLOBALS['empresa_id']);
                    $data['productos'] = $mtx_producto;
                    $data['estatus'] = $this->ccl_estatus_pedido->get_ccl_estatus_pedido_all();
                    //$data['espacios_fisicos']=$this->espacio_fisico->get_espacios_f();
                    $data['espacios_fisicos'] = $this->espacio_fisico->get_espacio_f($stock->espacio_fisico_id);
                    $data['ubicacion_salida_id'] = $GLOBALS['ubicacion_id'];
                }
            } else if ($subfuncion == "list_traspasos_almacen") {
                if ($this->uri->segment(5) == "editar_transferencia") {
                    $main_view = true;
                    $id = $this->uri->segment(6);
                    $data['principal'] = $ruta . "/editar_transferencia";
                    /* Cargar Catalogos */
                    $data['validation'] = $this->almacen_validacion->validacion_salida_venta();
                    $data['rows_pred'] = 0;
                    $data['cl_pedido'] = $this->cl_pedido->get_cl_pedido($id);
                    $data['traspaso'] = $this->traspaso->get_traspaso_by_cl_pedido_id($id);
                    $data['traspaso_salidas'] = $this->traspaso_salida->get_salidas_traspaso($data['traspaso']->id);
                    $data['cl_detalle'] = $this->cl_detalle_pedido->get_cl_detalles_pedido_parent($id);
                    if ($data['cl_detalle'] == false) {
                        show_error('El pedido de traspaso no tiene conceptos por lo tanto se ha cancelado');
                        $this->db->query("delete from cl_detalle_pedidos where cl_pedidos_id='$id'");
                        $this->db->query("delete from cl_pedidos where id='$id'");
                        $this->db->query("delete from traspasos where cl_pedido_id='$id'");
                    }
                    //4341082294 pats
                    $data['rows'] = count($data['traspaso_salidas']->all);
                    $data['empresa'] = $this->empresa->get_empresa($empresa_id);
                    $data['productos'] = $this->producto->get_cproductos_detalles();
                    $data['empresa'] = $this->empresa->get_empresa($empresa_id);
                    $data['productos'] = $this->producto->get_cproductos_detalles();
                    $data['estatus'] = $this->ccl_estatus_pedido->get_ccl_estatus_pedido_all();
                    $data['espacios_fisicos'] = $this->espacio_fisico->get_espacios_f();
                    $data['renglon_adic'] = 10;
                } else if ($this->uri->segment(5) == "borrar_traspaso") {
                    $id = $this->uri->segment(6);
                    $main_view = false;
                    if (substr(decbin($data['permisos']), 2, 1) == 1) {
                        $this->db->query("delete from cl_detalle_pedidos where cl_pedidos_id='$id'");
                        $this->db->query("delete from cl_pedidos where id='$id'");
                        $this->db->query("delete from traspasos where cl_pedido_id='$id'");
                        redirect(base_url() . "index.php/" . $ruta . "/" . $controller . "/" . $funcion . "/" . $subfuncion);
                    }
                    //Definir la vista
                    redirect(base_url() . "index.php/" . $ruta . "/" . $controller . "/" . $funcion . "/" . $subfuncion);
                } else {
                    $main_view = true;
                    //Cargar los datos para el formulario
                    //Definir la vista
                    $data['principal'] = $ruta . "/" . $subfuncion;
                    //Obtener Datos
                    // load pagination class
                    $this->load->library('pagination');
                    $config['base_url'] = base_url() . "index.php/" . $ruta . "/" . $controller . "/" . $funcion . "/list_traspasos_almacen/";
                    $config['per_page'] = '20';
                    $page = $this->uri->segment(5);
                    //Identificar el numero de pagina en el paginador si existe
                    if ($page > 0)
                        $offset = $page;
                    else if ($page == '')
                        $offset = 0;
                    else
                        $offset = 0;

                    $u1 = $this->cl_pedido->get_cl_pedidos_traspasos_almacen($offset, $config['per_page'], $GLOBALS['ubicacion_id']);
                    $total_registros = $this->cl_pedido->get_cl_pedidos_traspasos_almacen_count($GLOBALS['ubicacion_id']);
                    $ubicacion_id = $GLOBALS['ubicacion_id'];
                    $data['transferencias'] = $u1;
                    $data['total_registros'] = $total_registros;
                    $config['total_rows'] = $total_registros;
                    $this->pagination->initialize($config);
                }
            }


            if ($main_view) {
                //Llamar a la vista
                $this->load->view("ingreso", $data);
                unset($data);
            } else {
                //redirect(base_url()."index.php/inicio/logout");
            }
        }
    }

    function pedidos_total() {
        //Funcion para actualizar el monto total de los pedidos pendientes
        $pedidos = $this->db->query("select id, monto_total from pr_pedidos where cpr_estatus_pedido_id='2'");
        if ($pedidos->num_rows() > 0) {
            $this->load->model('pr_detalle_pedido');
            foreach ($pedidos->result() as $row) {
                $pr = new Pr_detalle_pedido();
                $pr->select("sum(costo_total) as costo_total");
                $pr->where('pr_pedidos_id', $row->id);
                $pr->get();
                if ($pr->costo_total > 0)
                    $this->db->query("update pr_pedidos set monto_total='$pr->costo_total' where id='$row->id'");
                else
                //$this->db->query("update pr_pedidos set cpr_estatus_pedido_id='4' where id='$row->id'");
                    $pr->clear();
            }
        }
    }

    function cancelar_traspaso(){
        $id = $this->uri->segment(4);
        $t = new Traspaso($id);
	$t->cestatus_traspaso_id = 3;
        $t->estatus_general_id = 2;
        $t->save();
        $salidas = new Salida();
        $salidas->where('traspaso_id', $id)->get();
        foreach($salidas as $s)
        {
            $s->estatus_general_id = 2;
            $s->save();
        }
        redirect("inicio/acceso/almacen/menu");
    }
    
	   function load_paginacion($url,$total,$per_page = 10){
        $this->load->library('pagination');
        $config['base_url'] = $url;
        $config['per_page'] = $per_page;
        $config['total_rows'] = $total;
        $this->pagination->initialize($config);
    }

}

?>
