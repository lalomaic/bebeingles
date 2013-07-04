<?php

class Poliza_c extends Controller {

    function Poliza_c() {
        parent::Controller();
        if ($this->session->userdata('logged_in') == FALSE)
            redirect(base_url() . "index.php/inicio/logout");
        $this->assetlibpro->add_css('default.css');
        $this->assetlibpro->add_css('menu_style.css');
        $this->assetlibpro->add_css('date_input.css');
        $this->assetlibpro->add_css('jquery.validator.css');
        $this->assetlibpro->add_css('autocomplete.css');
        $this->assetlibpro->add_css('fancybox/jquery.fancybox-1.3.1.css');
        $this->assetlibpro->add_css('style_fancy.css.css');
        $this->assetlibpro->add_js('prototipe.js');
        $this->assetlibpro->add_js('jquery.js');
        $this->assetlibpro->add_js('jquery.autocomplete.js');
        $this->assetlibpro->add_js('jquery.selectboxes.js');
        $this->assetlibpro->add_js('jquery.select-autocomplete.js');
        $this->assetlibpro->add_js('jquery.numberformatter.js');
        $this->assetlibpro->add_js('jquery.AddIncSearch.js');
        $this->assetlibpro->add_js('jquery.validator.js');
        $this->assetlibpro->add_js('jquery.date.js');
        $this->assetlibpro->add_js('jquery.form.js');
        $this->assetlibpro->add_js('fancybox/jquery.mousewheel-3.0.2.pack.js');
        $this->assetlibpro->add_js('fancybox/jquery.fancybox-1.3.1.js');
        $this->load->model("cobro_validacion");
        $this->load->model("pr_factura");
        $this->load->model("espacio_fisico");
        $this->load->model("forma_cobro");
        $this->load->model("tipo_cuenta_bancaria");
        $this->load->model("proveedor");
        $this->load->model("cliente");
        $this->load->model("empresa");
        $this->load->model("cuenta_bancaria");
        $this->load->model("cl_factura");
        $this->load->model("tipo_cobro");
        $this->load->model("cobro");
        $this->load->model("poliza");
        $this->load->model("poliza_detalle");
        $this->load->model("cuenta_contable");
        $this->load->model("ventas_validacion");
        $user_hash = $this->session->userdata('user_data');
        $row = $this->usuario->get_usuario_detalles($user_hash);
        $GLOBALS['usuarioid'] = $row->id;
        $GLOBALS['username'] = $row->username;
        $GLOBALS['empresa_id'] = $row->empresas_id;
        $GLOBALS['ruta'] = $this->uri->segment(1);
        $GLOBALS['controller'] = $this->uri->segment(2);
        $GLOBALS['funcion'] = $this->uri->segment(3);
        $GLOBALS['subfuncion'] = $this->uri->segment(4);
        $GLOBALS['modulos_totales'] = $this->modulo->get_tmodulos();
        $GLOBALS['main_menu'] = $this->menu->menus($GLOBALS['usuarioid'], "principal", 0);
    }

    function formulario() {
        //Funcion para manejar formularios y listados,
        global $main_menu, $usuarioid, $username, $ruta, $controller, $funcion, $subfuncion, $modulos_totales;

        $main_view = false;
        $data['username'] = $username;
        $data['usuarioid'] = $usuarioid;
        $data['modulos_totales'] = $modulos_totales;
        $data['colect1'] = $main_menu;
        $data['title'] = $this->accion->get_title("$subfuncion");
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
            //Inicio del Bloque Alta Forma de Cobro
            if ($subfuncion == "alta_poliza_ingreso") {
                $data['frames'] = 0;
                $data['principal'] = "$ruta/$subfuncion";
                $data['espacios'] = $this->espacio_fisico->get_espacios_for_polizas($GLOBALS['empresa_id']);
            } else if ($subfuncion == "alta_beneficiario") {
                $main_view = false;
                $pre = $this->uri->segment(5);
                if ($pre == '') {
                    //Pedir el número de remision y validar que exista la remision
                    echo "<html><script> nombre=prompt(\"Ingrese el nombre del beneficiario\");";
                    echo "window.location='" . base_url() . "index.php/" . $GLOBALS['ruta'] . "/poliza_c/formulario/alta_beneficiario/nombre/'+nombre;</script></html>";
                    return;
                } else if ($pre == 'nombre') {
                    $main_view = true;
                    $nombre = $this->uri->segment(6);
                    if ($nombre == '' || $nombre == 'null') {
                        echo "<html><script>alert(\"Debe escribir el nombre del beneficiario.\");" . "window.location='" . base_url() . "index.php/inicio/acceso/" . $GLOBALS['ruta'] . "/menu';</script></html>";
                        return;
                    }
                    $beneficiario = new Cbeneficiario();
                    $beneficiario->nombre = $nombre;
                    $beneficiario->save();
                    echo "<html><script>alert(\"Se ha guardado el beneficiario $nombre.\");" . "window.location='" . base_url() . "index.php/inicio/acceso/" . $GLOBALS['ruta'] . "/menu';</script></html>";
                }
            } else if ($subfuncion == "list_beneficiarios") {
                //<editor-fold>
                if ($this->uri->segment(5) == "borrar_beneficiario") {
                    $main_view = false;
                    $u = new Cbeneficiario($this->uri->segment(6));
                    $u->delete();
                    redirect(base_url() . "index.php/$ruta/$controller/$funcion/list_beneficiarios");
                    return;
                } else {
                    $main_view = true;
                    $config['per_page'] = '20';
                    $this->load->model('cbeneficiario');
                    $data['total_registros'] = $config['total_rows'] = $this->cbeneficiario->count();
                    $page = (int) $this->uri->segment(6);
                    //Identificar el numero de pagina en el paginador si existe
                    //echo $page;
                    if ($page > 0) {
                        $offset = $page;
                    } else {
                        $offset = 0;
                    }
                    $data['beneficiarios'] = $this->cbeneficiario->order_by('nombre')->limit($config['per_page'], $offset)->get();
                    $config['base_url'] = base_url() . "index.php/" . $ruta . "/" . $controller . "/formulario/list_beneficiarios";
                    $config['cur_page'] = $offset;
                    $this->load->library('pagination');
                    $this->pagination->initialize($config);
                    $data['principal'] = "$ruta/$subfuncion";
                }
                //</editor-fold>
            } else if ($subfuncion == "list_polizas_ingresos") {
                //<editor-fold>
                if($this->uri->segment(5)=="editar_poliza_ingreso"){
					//Cargar los datos para el formulario
					$edit_usr=$this->uri->segment(5);
					$id=$this->uri->segment(6);
					$data['principal']=$ruta."/editar_poliza_ingreso";

				} else if($this->uri->segment(5)=="borrar_poliza"){
					$id=$this->uri->segment(6);
					$main_view=false;
					//Definir la vista
					//Borrar Datos del Usuario
					$this->db->query("delete from poliza_detalles where poliza_id=$id");
					$this->db->query("delete from polizas where id=$id");
					redirect(base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion);
				} else  {
					$data['principal']=$ruta."/".$subfuncion;
					$this->load->library('pagination');
					$config['base_url'] = base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_polizas_ingresos";
					$config['per_page'] = '60';
					$page=$this->uri->segment(5);
					//Identificar el numero de pagina en el paginador si existe
					if($page>0)
						$offset=$page;
				 else if ($page=='')
				 	$offset=0;
				 else
				 	echo "Sin registros";
				 $total_registros=$this->poliza->get_polizas_ingreso_count($GLOBALS['empresa_id']);
				 $data['polizas_i']=$this->poliza->get_polizas_ingreso($offset, $config['per_page'], $GLOBALS['empresa_id']);
				 $data['total_registros']=$total_registros;
				 $config['total_rows'] = $total_registros;
				 $this->pagination->initialize($config);
				   }
                //</editor-fold>
            } else if ($subfuncion == "list_polizas_egresos") {
                //<editor-fold>
                if ($this->uri->segment(5) == "editar_poliza_egreso") {
                    $data['titulo'] = 'egreso';
                    $data['enlaces'] = array(
                        array(
                            'link' => 'list_polizas_egresos',
                            'image' => 'list.png',
                            'descripcion' => 'Listado de General de Pólizas de Egreso'
                        ),
                        array(
                            'link' => 'alta_manual_poliza_egreso',
                            'image' => 'add_manual_factura.png',
                            'descripcion' => 'Agregar Póliza manual de Egreso'
                        )
                    );
                    $data['poliza'] = new Poliza($this->uri->segment(6));
                    if (!$data['poliza']->exists())
                        show_error("No existe la poliza");

                    $this->load->model('cbeneficiario');
                    $data['beneficiarios'] = $this->cbeneficiario->order_by('nombre')->get();
                    $data['empresa'] = $this->empresa->get_empresa($GLOBALS['empresa_id']);
                    $data['cuentas'] = $this->cuenta_contable->get_list_cuentas_contables_select_todos($GLOBALS['empresa_id']);
                    $data['principal'] = $ruta . "/editar_poliza_egreso";
                    $this->assetlibpro->add_js('jquery.autocomplete.remote.js');
                    $data['bancos'] = $this->poliza->get_bancos();
                    $beneficiario = new Cbeneficiario();
                    $data['beneficiario'] = $beneficiario->get_by_nombre(trim(str_replace("Beneficiario: ", '', strstr($data['poliza']->concepto, "Beneficiario: "))));
                    if (!$data['beneficiario']->exists()) {
                        $data['beneficiario'] = new Cbeneficiario();
                        $data['beneficiario']->id = 0;
                    }
                } else if ($this->uri->segment(5) == "borrar_poliza") {
                    $id = $this->uri->segment(6);
                    $this->db->query("delete from poliza_detalles where poliza_id=$id");
                    $poliza = new Poliza($id);
                    $poliza->estatus_general_id = 2;
                    $poliza->debe = 0;
                    $poliza->haber = 0;
                    $poliza->save();
                    return;
                } else {
                    $data['titulo'] = 'egreso';
                    $tipo = 1;
                    $data['enlaces'] = array(
                        array(
                            'link' => 'alta_manual_poliza_egreso',
                            'image' => 'add_manual_factura.png',
                            'descripcion' => 'Agregar Póliza manual de Egreso'
                        )
                    );

                    $data['bancos'] = $this->poliza->get_bancos();
                    $data['principal'] = "$ruta/list_polizas_egreso";
                }
                //</editor-fold>
            } else if ($subfuncion == "list_polizas_diario") {
                //<editor-fold>
                if($this->uri->segment(5)=="editar_poliza_diario"){
					//Cargar los datos para el formulario
					$edit_usr=$this->uri->segment(5);
					$id=$this->uri->segment(6);
					$data['principal']=$ruta."/editar_poliza_diario";
					$data['lineas_totales']=60;
					$data['poliza']=$this->poliza->get_poliza($id);
					if($data['poliza']==false)
						show_error("No existe la poliza");
					$data['poliza_detalles']=$this->poliza_detalle->get_poliza_detalles_by_poliza_diario($id);
					if($data['poliza_detalles']==false)
						show_error("La poliza no tiene asosiado ningun detalle, le recomendamos cancelarla");
					$data['lineas_vis']=count($data['poliza_detalles']->all);
					$data['empresa']=$this->empresa->get_empresa($GLOBALS['empresa_id']);
					$data['cuentas']=$this->cuenta_contable->get_list_cuentas_contables_select_todos($GLOBALS['empresa_id']);
					$data['validation']=$this->ventas_validacion->validacion_poliza_diario();

				} else if($this->uri->segment(5)=="borrar_poliza"){
					$id=$this->uri->segment(6);
					$main_view=false;
					//Definir la vista
					$this->db->query("update polizas set estatus_general_id=2 where id=$id");
					redirect(base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion);
				} else  {
					$data['principal']=$ruta."/".$subfuncion;
					$this->load->library('pagination');
					$config['base_url'] = base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_polizas_diario";
					$config['per_page'] = '40';
					$page=$this->uri->segment(5);
					//Identificar el numero de pagina en el paginador si existe
					if($page>0)
						$offset=$page;
					else if ($page=='')
						$offset=0;
					else
						$offset=0;
					$total_registros=$this->poliza->get_polizas_diario_count($GLOBALS['empresa_id']);
					$data['polizas']=$this->poliza->get_polizas_diario($offset, $config['per_page'], $GLOBALS['empresa_id']);
					$data['total_registros']=$total_registros;
					$config['total_rows'] = $total_registros;
					$this->pagination->initialize($config);
				   }
                //</editor-fold>
            } else if ($subfuncion == "alta_manual_poliza_ingreso") {
                //<editor-fold>
                $main_view = true;
                $this->assetlibpro->add_js('jquery.autocomplete.remote.js');
                $data['principal'] = $ruta . "/alta_manual_poliza_ingreso";
                $data['empresa'] = $this->empresa->get_empresa($GLOBALS['empresa_id']);
                $data['cuentas'] = $this->cuenta_contable->get_list_cuentas_contables_select_todos($GLOBALS['empresa_id']);
                $data['espacios'] = $this->espacio_fisico->get_espacios_by_empresa_id($GLOBALS['empresa_id']);
                $data['titulo'] = 'Ingreso';
                $data['enlaces'] = array(
                    array(
                        'link' => 'list_polizas_ingresos',
                        'image' => 'list.png',
                        'descripcion' => 'Listado de General de Pólizas de Ingreso'
                    ),
                    array(
                        'link' => 'alta_poliza_ingreso',
                        'image' => 'add_factura.png',
                        'descripcion' => 'Agregar Póliza automática de Ingreso'
                    )
                );
                //</editor-fold>
            } else if ($subfuncion == "alta_manual_poliza_egreso") {
                //<editor-fold>
                $main_view = true;
                $this->assetlibpro->add_js('jquery.autocomplete.remote.js');
                $data['principal'] = $ruta . "/alta_manual_poliza_egreso";
                $data['empresa'] = $this->empresa->get_empresa($GLOBALS['empresa_id']);
                $data['cuentas'] = $this->cuenta_contable->get_list_cuentas_contables_select_todos($GLOBALS['empresa_id']);
                $this->load->model('cbeneficiario');
                $data['beneficiarios'] = $this->cbeneficiario->order_by('nombre')->get();
                $data['bancos'] = $this->poliza->get_bancos();
                $data['titulo'] = 'Egreso';
                $data['enlaces'] = array(
                    array(
                        'link' => 'list_polizas_egresos',
                        'image' => 'list.png',
                        'descripcion' => 'Listado de General de Pólizas de Egreso'
                    )
                );
                //</editor-fold>
            } else if ($subfuncion == "alta_manual_poliza_diario") {
                //<editor-fold>
                $main_view = true;
                $this->assetlibpro->add_js('jquery.autocomplete.remote.js');
                $data['principal'] = $ruta . "/alta_manual_poliza_diario";
                $data['empresa'] = $this->empresa->get_empresa($GLOBALS['empresa_id']);
                $data['cuentas'] = $this->cuenta_contable->get_list_cuentas_contables_select_todos($GLOBALS['empresa_id']);
                $this->load->model('cbeneficiario');
                $data['beneficiarios'] = $this->cbeneficiario->order_by('nombre')->get();
                $data['bancos'] = $this->poliza->get_bancos();
                $data['titulo'] = 'Diario';
                $data['enlaces'] = array(
                    array(
                        'link' => 'list_polizas_diario',
                        'image' => 'list.png',
                        'descripcion' => 'Listado de General de Pólizas de Ingreso'
                    )
                );
                //</editor-fold>
            }//Fin bloque polizas
            else if ($subfuncion == "alta_cuenta_contable") {
                $this->assetlibpro->add_js('jquery.autocomplete.remote.js');
                $main_view = true;
                $data['principal'] = $ruta . "/" . $subfuncion;
                $this->load->model('tipo_cuenta_contable');
                $this->load->model('ccuentas_contables_naturaleza');
                $data['cuentas_naturalezas'] = $this->ccuentas_contables_naturaleza->cuenta_naturaleza_drop();
                $data['tipos_cuentas'] = $this->tipo_cuenta_contable->get_cuentas_drop();
                $data['tipos_cuentas'][0] = "Elija";
            } else if ($subfuncion == "list_cuentas_contables") {
                //<editor-fold>
                if ($this->uri->segment(5) == "editar_cuenta_contable") {
                    //Cargar los datos para el formulario
                    $id = $this->uri->segment(6);

                    $this->assetlibpro->add_js('jquery.autocomplete.remote.js');
                    $main_view = true;
                    $data['principal'] = $ruta . "/" . $subfuncion;
                    $this->load->model('ccuentas_contables_naturaleza');
                	  $data['cuentas_naturalezas'] = $this->ccuentas_contables_naturaleza->cuenta_naturaleza_drop();
                
                    $this->load->model('tipo_cuenta_contable');
                    $data['tipos_cuentas'] = $this->tipo_cuenta_contable->get_cuentas_drop();
                    $data['cuenta'] = new Cuenta_contable($id);
                    $data['principal'] = $ruta . "/editar_cuenta_contable";
                } else if ($this->uri->segment(5) == "borrar_cuenta_contable") {
                    $id = $this->uri->segment(6);
                    $main_view = false;
                    //Definir la vista
                    $this->db->query("update ccuentas_contables set estatus_general_id=2 where id=$id");
                    redirect(base_url() . "index.php/" . $ruta . "/" . $controller . "/" . $funcion . "/" . $subfuncion);
                } else {
                    $data['pag'] = 1;
                    $cuenta_id = $this->uri->segment(6);
                    $data['principal'] = $ruta . "/" . $subfuncion;
                    if ($cuenta_id > 0) {
                        //Filtrado por cuenta_id
                        $data['paginacion'] = false;
                        $data['cuentas'] = $this->cuenta_contable->get_cuentas_contables_filtrado($cuenta_id, $GLOBALS['empresa_id']);
                        $data['cuentas_select'] = $this->cuenta_contable->get_list_cuentas_contables_select_todos($GLOBALS['empresa_id']);
                        if ($data['cuentas'] == false)
                            show_error("El catálogo de Cuentas Contables de la Empresa está vacío");
                        $data['cta'] = $cuenta_id;
                    } else {
                        $data['paginacion'] = true;
                        $data['cuentas_select'] = $this->cuenta_contable->get_list_cuentas_contables_select_todos($GLOBALS['empresa_id']);
                        $this->load->library('pagination');
                        $config['base_url'] = base_url() . "index.php/" . $ruta . "/" . $controller . "/" . $funcion . "/list_cuentas_contables";
                        $config['per_page'] = '100';
                        $page = $this->uri->segment(5);
                        //Identificar el numero de pagina en el paginador si existe
                        if ($page > 0)
                            $offset = $page;
                        else if ($page == '')
                            $offset = 0;
                        else
                            $offset = 0;
                        $data['pag'] = $offset;
                        $total_registros = $this->cuenta_contable->get_list_cuentas_contables_count($GLOBALS['empresa_id']);
                        $data['cuentas'] = $this->cuenta_contable->get_list_cuentas_contables($offset, $config['per_page'], $GLOBALS['empresa_id']);
                        $data['total_registros'] = $total_registros;
                        $config['total_rows'] = $total_registros;
                        $this->pagination->initialize($config);
                        $data['cta'] = 0;
                    }
                }
                //</editor-fold>
            }//Fin bloque cuentas contables

            if ($main_view) {
                $this->load->view("ingreso", $data);
            } else {
                redirect(base_url() . "index.php/inicio/logout");
            }
        }
    }

    function list_polizas_ingreso($offset = 0) {
        global $main_menu, $usuarioid, $username, $ruta, $controller, $funcion, $modulos_totales;
        $data['polizas'] = $this->poliza->get_polizas_ingreso_list(
                $GLOBALS['empresa_id'], $offset, 20, date_format(date_create_from_format('d m Y', $_POST['fecha_inicio']), 'Y-m-d'), date_format(date_create_from_format('d m Y', $_POST['fecha_fin']), 'Y-m-d'), $_POST['espacio_fisico_id'], $_POST['tipo']);
        $data['total_registros'] = $config['total_rows'] = $this->poliza->get_polizas_ingreso_list_count(
                $GLOBALS['empresa_id'], date_format(date_create_from_format('d m Y', $_POST['fecha_inicio']), 'Y-m-d'), date_format(date_create_from_format('d m Y', $_POST['fecha_fin']), 'Y-m-d'), $_POST['espacio_fisico_id'], $_POST['tipo']);

        $data['titulo'] = 'ingreso';
        $data['ruta'] = $ruta;
        $data['controller'] = $controller;
        $data['funcion'] = $funcion;
        $data['subfuncion'] = 'list_polizas_ingresos';

        $this->load->library('Jquery_pagination');
        $config['base_url'] = base_url() . "index.php/$ruta/$controller/list_polizas_ingreso/";
        $config['div'] = '#listado';
        $config['per_page'] = 20;
        $config['cur_page'] = $offset;
        $config['extra_params'] = array(
            'fecha_inicio' => $_POST['fecha_inicio'],
            'fecha_fin' => $_POST['fecha_fin'],
            'espacio_fisico_id' => $_POST['espacio_fisico_id'],
            'tipo' => $_POST['tipo']);
        $this->jquery_pagination->initialize($config);
        $data['paginacion'] = $this->jquery_pagination->create_links();

        $this->load->view("contabilidad/listado_polizas_ingreso", $data);
    }

    function list_polizas_egreso($offset = 0) {
        global $main_menu, $usuarioid, $username, $ruta, $controller, $funcion, $modulos_totales;
        $data['polizas'] = $this->poliza->get_polizas_egreso_list(
                $GLOBALS['empresa_id'], $offset, 20, date_format(date_create_from_format('d m Y', $_POST['fecha_inicio']), 'Y-m-d'), date_format(date_create_from_format('d m Y', $_POST['fecha_fin']), 'Y-m-d'), $_POST['banco'], $_POST['tipo']);
        $data['total_registros'] = $config['total_rows'] = $this->poliza->get_polizas_egreso_list_count(
                $GLOBALS['empresa_id'], date_format(date_create_from_format('d m Y', $_POST['fecha_inicio']), 'Y-m-d'), date_format(date_create_from_format('d m Y', $_POST['fecha_fin']), 'Y-m-d'), $_POST['banco'], $_POST['tipo']);

        $bancos = $this->poliza->get_bancos();
        $data['bancos'] = array();
        $data['bancos'][0] = 'Sin banco';
        foreach ($bancos as $banco) {
            $data['bancos'][$banco->banco] = $banco->tag;
        }

        $data['titulo'] = 'egreso';
        $data['ruta'] = $ruta;
        $data['controller'] = $controller;
        $data['funcion'] = $funcion;
        $data['subfuncion'] = 'list_polizas_egresos';

        $this->load->library('Jquery_pagination');
        $config['base_url'] = base_url() . "index.php/$ruta/$controller/list_polizas_egreso/";
        $config['div'] = '#listado';
        $config['per_page'] = 20;
        $config['cur_page'] = $offset;
        $config['extra_params'] = array(
            'fecha_inicio' => $_POST['fecha_inicio'],
            'fecha_fin' => $_POST['fecha_fin'],
            'banco' => $_POST['banco'],
            'tipo' => $_POST['tipo']);
        $this->jquery_pagination->initialize($config);
        $data['paginacion'] = $this->jquery_pagination->create_links();

        $this->load->view("contabilidad/listado_polizas_egreso", $data);
    }

    function list_polizas_diario($offset = 0) {
        global $main_menu, $usuarioid, $username, $ruta, $controller, $funcion, $modulos_totales;
        $data['polizas'] = $this->poliza->get_polizas_diario_list(
                $GLOBALS['empresa_id'], $offset, 20, date_format(date_create_from_format('d m Y', $_POST['fecha_inicio']), 'Y-m-d'), date_format(date_create_from_format('d m Y', $_POST['fecha_fin']), 'Y-m-d'), $_POST['tipo']);
        $data['total_registros'] = $config['total_rows'] = $this->poliza->get_polizas_diario_list_count(
                $GLOBALS['empresa_id'], date_format(date_create_from_format('d m Y', $_POST['fecha_inicio']), 'Y-m-d'), date_format(date_create_from_format('d m Y', $_POST['fecha_fin']), 'Y-m-d'), $_POST['tipo']);

        $data['titulo'] = 'diario';
        $data['ruta'] = $ruta;
        $data['controller'] = $controller;
        $data['funcion'] = $funcion;
        $data['subfuncion'] = 'list_polizas_diario';

        $this->load->library('Jquery_pagination');
        $config['base_url'] = base_url() . "index.php/$ruta/$controller/list_polizas_diario/";
        $config['div'] = '#listado';
        $config['per_page'] = 20;
        $config['cur_page'] = $offset;
        $config['extra_params'] = array(
            'fecha_inicio' => $_POST['fecha_inicio'],
            'fecha_fin' => $_POST['fecha_fin'],
            'tipo' => $_POST['tipo']);
        $this->jquery_pagination->initialize($config);
        $data['paginacion'] = $this->jquery_pagination->create_links();

        $this->load->view("contabilidad/listado_polizas_diario", $data);
    }

}