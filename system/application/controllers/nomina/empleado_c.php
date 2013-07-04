<?php
class Empleado_c extends Controller {
	
    function Empleado_c() {
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
        $this->assetlibpro->add_js('jquery.js');
        $this->assetlibpro->add_js('jquery.validator.js');
        $this->assetlibpro->add_js('jquery.date.js');
        $this->assetlibpro->add_js('jquery.form.js');
        $this->assetlibpro->add_js('jquery.autocomplete.js');
		$this->assetlibpro->add_js('fancybox/jquery.mousewheel-3.0.2.pack.js');
        $this->assetlibpro->add_js('fancybox/jquery.fancybox-1.3.1.js');
        $this->load->model("espacio_fisico");
        $this->load->model("nomina_validacion");
        $this->load->model("tipo_comision");
        $this->load->model("comision");
        $this->load->model("puesto");
        $this->load->model("empleado");
        $this->load->model("smg_zona");
        $user_hash = $this->session->userdata('user_data');
        $row = $this->usuario->get_usuario_detalles($user_hash);
        $GLOBALS['usuarioid'] = $row->id;
        $GLOBALS['ubicacion_id'] = $row->espacio_fisico_id;
        $GLOBALS['username'] = $row->username;
        $GLOBALS['ruta'] = $this->uri->segment(1);
        $GLOBALS['controller'] = $this->uri->segment(2);
        $GLOBALS['funcion'] = $this->uri->segment(3);
        $GLOBALS['subfuncion'] = $this->uri->segment(4);
        $GLOBALS['modulos_totales'] = $this->modulo->get_tmodulos();
        $GLOBALS['main_menu'] = $this->menu->menus($GLOBALS['usuarioid'], "principal", 0);
    }

    function formulario() {
        global $main_menu, $usuarioid, $username, $ruta, $controller, $funcion, $subfuncion, $modulos_totales;
        $main_view = false;
        $data['username'] = $username;
        $data['usuarioid'] = $usuarioid;
        $data['modulos_totales'] = $modulos_totales;
        $data['colect1'] = $main_menu;
        $data['title'] = $this->accion->get_title($subfuncion);
        $accion_id = $this->accion->get_id($subfuncion);
        $row = $this->usuario->get_usuario($usuarioid);
        $grupoid = $row->grupo_id;
        $puestoid = $row->puesto_id;
        $data['ruta'] = $ruta;
        $data['controller'] = $controller;
        $data['funcion'] = $funcion;
        $data['subfuncion'] = $subfuncion;
        $data['permisos'] = $this->usuario_accion->get_permiso($accion_id, $usuarioid, $puestoid, $grupoid);
        //Validacion del arreglo del menu, usuarioid y permisos especificos
        //$data['colect1']) and $usuarioid>0 and $data['permisos']!= false and
        if (($this->session->userdata('logged_in') == TRUE)) {
            $main_view = true;
            if ($subfuncion == "alta_comision") {
				$data['rangos']=6;
                $data['principal'] = $ruta . "/" . $subfuncion;
                $data['tipo_comisiones'] = $this->tipo_comision->get_tipos_comisiones_dropd();
                		$data['espacios'] = $this->espacio_fisico->get_espacios_tiendas_oficinas_mtrx();
			} else if($subfuncion=="list_comisiones"){
				if($this->uri->segment(5)=="editar_comision"){
					$id=$this->uri->segment(6);
					$data['rangos']=6;
					$data['principal'] = $ruta . "/editar_comision";
					$data['datos']=$this->comision->get_by_id($id);
					$data['tipo_comisiones'] = $this->tipo_comision->get_tipos_comisiones_dropd();
				} else if($this->uri->segment(5)=="borrar_tipo_entrada"){
					$id=$this->uri->segment(6);
					$main_view=false;
					if(substr(decbin($data['permisos']), 2, 1)==1){
						$u=new Comision();
						$u->get_by_id($id);
						$u->estatus_general_id=2;
						$u->save();
						redirect(base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion);
					} else {
						$main_view=true;
						$data['principal']="error";
						$data['error_field']="No tiene permisos para deshabilitar la Comisión";
					}
				} else  {
					$main_view=true;
					$data['principal']=$ruta."/".$subfuncion;
					$this->load->library('pagination');
					$config['base_url'] = base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_comisiones/";
					$config['per_page'] = '30';
					$page=$this->uri->segment(5);
					if($page>0) 
						$offset=$page;
					else 
						$offset=0;
					$u1=new Comision();
					$u1->select('id')->get();
					$data['total_registros']=$config['total_rows']=$u1->c_rows;
					$data['comisiones']=$this->comision->get_comisiones_list($config['per_page'],$offset,$GLOBALS['ubicacion_id']);
					$this->pagination->initialize($config);
				}
			} else if($subfuncion=="list_salarios_minimos"){
				$this->load->model("salario_minimo");
				if($this->uri->segment(5)=="editar_salario_minimo"){
					$edit_usr=$this->uri->segment(5);
					$id=$this->uri->segment(6);
					$data['principal']=$ruta."/editar_salario_minimo";
					$data['salario']=$this->salario_minimo->get_entrada($id);
				} else if($this->uri->segment(5)=="borrar_salario_minimo"){
					$id=$this->uri->segment(6);
					$main_view=false;
					if(substr(decbin($data['permisos']), 2, 1)==1){
						$u=new Salario_minimo();
						$u->get_by_id($id);
						$u->estatus_general_id=2;
						$u->save();
						redirect(base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion);
					} else {
						$main_view=true;
						$data['principal']="error";
						$data['error_field']="No tiene permisos para deshabilitar la Salario Minimo";
					}
				} else  {
					$main_view=true;
					$data['principal']=$ruta."/".$subfuncion;
					$this->load->library('pagination');
					$config['base_url'] = base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_salarios_minimos/";
					$config['per_page'] = '30';
					$page=$this->uri->segment(5);
					if($page>0) 
						$offset=$page;
					else 
						$offset=0;
					$u1=new Salario_minimo();
					$u1->select('id')->get();
					$data['total_registros']=$config['total_rows']=$u1->c_rows;
					$data['salarios']=$this->salario_minimo->get_salarios_minimos_list($config['per_page'],$offset);
					$this->pagination->initialize($config);
				}
			}	 else if($subfuncion=="list_tipos_descuentos"){
				$this->load->model("tipo_descuento");
				if($this->uri->segment(5)=="editar_tipo_descuento"){
					$edit_usr=$this->uri->segment(5);
					$id=$this->uri->segment(6);
					$data['principal']=$ruta."/editar_tipo_descuento";
					$data['salario']=$this->salario_minimo->get_entrada($id);
				} else if($this->uri->segment(5)=="borrar_tipo_descuento"){
					$id=$this->uri->segment(6);
					$main_view=false;
					if(substr(decbin($data['permisos']), 2, 1)==1){
						$u=new Tipo_descuento();
						$u->get_by_id($id);
						$u->estatus_general_id=2;
						$u->save();
						redirect(base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion);
					} else {
						$main_view=true;
						$data['principal']="error";
						$data['error_field']="No tiene permisos para deshabilitar el Tipo descuento";
					}
				} else  {
					$main_view=true;
					$data['principal']=$ruta."/".$subfuncion;
					$this->load->library('pagination');
					$config['base_url'] = base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_tipos_descuentos/";
					$config['per_page'] = '30';
					$page=$this->uri->segment(5);
					if($page>0) 
						$offset=$page;
					else 
						$offset=0;
					$u1=new Tipo_descuento();
					$u1->select('id')->get();
					$data['total_registros']=$config['total_rows']=$u1->c_rows;
					$data['tipos']=$this->tipo_descuento->get_tipos_descuentos_list($config['per_page'],$offset);
					$this->pagination->initialize($config);
				}
			}	
			
			 else if ($subfuncion == "alta_descuento_programado") {
				$this->load->model(array("descuento_programado", 'tipo_descuento', 'estatus_descuento'));
				$data['rangos']=6;
                $data['principal'] = $ruta . "/" . $subfuncion;
                $data['tipos_descuentos'] = $this->tipo_descuento->get_tipos_descuentos_dropd();
			} else if($subfuncion=="list_descuentos_programados"){
				$this->load->model(array("descuento_programado", 'tipo_descuento', 'estatus_descuento'));
				if($this->uri->segment(5)=="editar_descuento"){
					$edit_usr=$this->uri->segment(5);
					$id=$this->uri->segment(6);
					$data['principal']=$ruta."/editar_descuento";
					$data['descuento']=$this->descuento_programado->get_by_id($id);
					$data['empleado']=$this->empleado->get_by_id($data['descuento']->empleado_id);
					$data['tipos_descuentos'] = $this->tipo_descuento->get_tipos_descuentos_dropd();
				} else if($this->uri->segment(5)=="borrar_descuento"){
					$id=$this->uri->segment(6);
					$main_view=false;
					if(substr(decbin($data['permisos']), 2, 1)==1){
						$u=new Descuento_programado();
						$u->get_by_id($id);
						$u->estatus_descuento_id=3;
						$u->save();
						redirect(base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion);
					} else {
						$main_view=true;
						$data['principal']="error";
						$data['error_field']="No tiene permisos para cancelar el descuento";
					}
				} else  {
					$main_view=true;
					$data['principal']=$ruta."/".$subfuncion;
					$this->load->library('pagination');
					$config['base_url'] = base_url()."index.php/$ruta/$controller/$funcion/$subfuncion/";
					$config['per_page'] = '30';
					$page=$this->uri->segment(5);
					if($page>0) 
						$offset=$page;
					else 
						$offset=0;
					$u1=new Descuento_programado();
					$u1->select('id')->get();
					$data['total_registros']=$config['total_rows']=$u1->c_rows;
					$data['descuentos']=$this->descuento_programado->get_descuentos_programados_list($config['per_page'],$offset);
					$this->pagination->initialize($config);
				}
			}
			
			
			else if ($subfuncion == "alta_empleado") {
                $data['principal'] = $ruta . "/" . $subfuncion;
                $data['validation'] = $this->nomina_validacion->validacion_empleado();
				$data['comisiones'] = $this->comision->get_comisiones_dropd();
                $data['espacios'] = $this->espacio_fisico->get_espacios_tiendas_oficinas_mtrx();
				$data['zonas'] = $this->smg_zona->get_zonas_dropd();
                $data['puestos'] = $this->puesto->get_puestos_dropd();
            } elseif ($subfuncion == "list_empleados") {
                if ($this->uri->segment(5) == "editar_empleado") {
                    //Cargar los datos para el formulario
                    $eid = (int) $this->uri->segment(6);
                    if ($eid <= 0)
                        show_error('No se ha seleccionado un registro valido');
                    $data['frames'] = 0;
                    $data['principal'] = $ruta . "/editar_empleado";
                    $data['empleado']=$this->empleado->get_by_id($eid);
                    $data['title'] = 'Editar empleado';
                    //Obtener Datos
                    $data['validation'] = $this->nomina_validacion->validacion_empleado();
					$data['comisiones'] = $this->comision->get_comisiones_dropd();
					$data['espacios'] = $this->espacio_fisico->get_espacios_tiendas_oficinas_mtrx();
					$data['puestos'] = $this->puesto->get_puestos_dropd();
					$data['zonas'] = $this->smg_zona->get_zonas_dropd();
                } elseif ($this->uri->segment(5) == "frame_foto") {
                    $eid = (int) $this->uri->segment(6);
                    $data['empleado']=$this->empleado->get_by_id($eid);
                    $main_view = false;
					$this->load->view('nomina/frame_foto', $data);
                } elseif ($this->uri->segment(5) == "borrar_empleado") {
                    $eid = (int) $this->uri->segment(6);
                    $main_view = false;
                    if ($eid <= 0)
                        show_error('El registro especificado no existe');
                    elseif (substr(decbin($data['permisos']), 2, 1) == 1) {
                        $data['empleado'] = $this->empleado->get_empleado_list2($eid);
                              $data['espacio'] = $this->empleado->get_espacio_empleado($eid);
                        $this->load->view('nomina/borrar_empleado', $data);
                    } else {
                        $main_view = true;
                        $data['principal'] = "error";
                        $data['error_field'] = "No tiene permisos para deshabilitar el empleado seleccionado";
                    }
                } else {
//                    $this->_comprobar_vacaciones_empleados();
                    $data['principal'] = $ruta . "/" . $subfuncion;
                    $data['espacios'][-1] = "Elija";
                    $data['espacios'] = $data['espacios'] + $this->espacio_fisico->get_espacios_as_array();
                    $data['puestos'][-1] = "Elija";
                    $data['puestos'] = $data['puestos'] + $this->puesto->get_puestos_dropd();
                    $this->load->library('pagination');
                    $config['per_page'] = '30';
                    if ($this->uri->segment(5) == "fil_nom") {
                        $nombre = $this->uri->segment(6);
                        $page = (int) $this->uri->segment(7);
                        if ($page > 0)
                            $offset = $page;
                        else if ($page == '')
                            $offset = 0;
                        else
                            $offset = 0;
                        $data['empleados'] = $this->empleado->get_empleados_list_by_nombre($nombre, $config['per_page'] , $offset);
                        $config['total_rows'] = $data['total_registros'] = $this->empleado->get_empleados_count_list_by_nombre($nombre);
                        $config['base_url'] = base_url() . "index.php/" . $ruta . "/" . $controller . "/" . $funcion . "/$subfuncion/fil_nom/$nombre/";
                        $config['uri_segment'] = 7;
                    } elseif ($this->uri->segment(5) == "fil_apa") {
                        $apaterno = $this->uri->segment(6);
                        $page = (int) $this->uri->segment(7);
                        if ($page > 0)
                            $offset = $page;
                        else if ($page == '')
                            $offset = 0;
                        else
                            $offset = 0;
                        $data['empleados'] = $this->empleado->get_empleados_list_by_apaterno($apaterno, $config['per_page'] , $offset,1);
                        $config['total_rows'] = $data['total_registros'] = $this->empleado->get_empleados_count_list_by_apaterno($apaterno);
                        $config['base_url'] = base_url() . "index.php/" . $ruta . "/" . $controller . "/" . $funcion . "/$subfuncion/fil_apa/$apaterno/";
                        $config['uri_segment'] = 7;
                    } elseif ($this->uri->segment(5) == "fil_ama") {
                        $amaterno = $this->uri->segment(6);
                        $page = (int) $this->uri->segment(7);
                        if ($page > 0)
                            $offset = $page;
                        else if ($page == '')
                            $offset = 0;
                        else
                            $offset = 0;
                        $data['empleados'] = $this->empleado->get_empleados_list_by_amaterno($amaterno, 30, $offset,1);
                        $config['total_rows'] = $data['total_registros'] = $this->empleado->get_empleados_count_list_by_amaterno($amaterno);
                        $config['base_url'] = base_url() . "index.php/" . $ruta . "/" . $controller . "/" . $funcion . "/$subfuncion/fil_ama/$amaterno/";
                        $config['uri_segment'] = 7;
                    } elseif ($this->uri->segment(5) == "fil_pue") {
                        $puesto_id = $this->uri->segment(6);
                        $page = (int) $this->uri->segment(7);
                        if ($page > 0)
                            $offset = $page;
                        else if ($page == '')
                            $offset = 0;
                        else
                            $offset = 0;
                        $data['empleados'] = $this->empleado->get_empleados_list_by_puestoid($puesto_id, 30, $offset,1);
                        $config['total_rows'] = $data['total_registros'] = $this->empleado->get_empleados_count_list_by_puestoid($puesto_id);
                        $config['base_url'] = base_url() . "index.php/" . $ruta . "/" . $controller . "/" . $funcion . "/$subfuncion/fil_pue/$puesto_id/";
                        $config['uri_segment'] = 7;
                    } elseif ($this->uri->segment(5) == "fil_esp") {
                        $espacio_id = $this->uri->segment(6);
                        $page = (int) $this->uri->segment(7);
                        if ($page > 0)
                            $offset = $page;
                        else if ($page == '')
                            $offset = 0;
                        else
                            $offset = 0;
                        $data['empleados'] = $this->empleado->get_empleados_list_by_espacioid($espacio_id, 30, $offset,1);
                        $config['total_rows'] = $data['total_registros'] = $this->empleado->get_empleados_count_list_by_espacioid($espacio_id);
                        $config['base_url'] = base_url() . "index.php/" . $ruta . "/" . $controller . "/" . $funcion . "/$subfuncion/fil_esp/$espacio_id/";
                        $config['uri_segment'] = 7;
                    } else {
                        $page = (int) $this->uri->segment(5);
                        if ($page > 0)
                            $offset = $page;
                        else if ($page == '')
                            $offset = 0;
                        else
                            $offset = 0;
                        $data['empleados'] = $this->empleado->get_empleados_list(30, $offset,1);
                        $config['total_rows'] = $data['total_registros'] = $this->empleado->get_empleados_count_list();
                        $config['base_url'] = base_url() . "index.php/" . $ruta . "/" . $controller . "/" . $funcion . "/$subfuncion/";
                    }
                    $this->pagination->initialize($config);
                }
            } elseif ($subfuncion == "list_empleados_inactivos") {
				if ($this->uri->segment(5) == "habilitar_empleado") {
                    $eid = (int) $this->uri->segment(6);
                    $main_view = false;
                    if ($eid <= 0)
                        show_error('El registro especificado no existe');
                    elseif (substr(decbin($data['permisos']), 2, 1) == 1) {
						$e=new Empleado();
						$e->get_by_id($eid);
						$e->estatus_general_id=1;
						$e->save();
						redirect(base_url()."index.php/nomina/empleado_c/formulario/list_empleados_inactivos");
                    } else {
                        $main_view = true;
                        $data['principal'] = "error";
                        $data['error_field'] = "No tiene permisos para deshabilitar el empleado seleccionado";
                    }
                } else {
                    $data['principal'] = $ruta . "/list_empleados_inactivos";
                    $data['espacios'][-1] = "Elija";
                    $data['espacios'] = $data['espacios'] + $this->espacio_fisico->get_espacios_as_array();
                    $data['puestos'][-1] = "Elija";
                    $data['puestos'] = $data['puestos'] + $this->puesto->get_puestos_dropd();
                    $this->load->library('pagination');
                    $config['per_page'] = '30';
                    if ($this->uri->segment(5) == "fil_nom") {
                        $nombre = $this->uri->segment(6);
                        $page = (int) $this->uri->segment(7);
                        if ($page > 0)
                            $offset = $page;
                        else if ($page == '')
                            $offset = 0;
                        else
                            $offset = 0;
                        $data['empleados'] = $this->empleado->get_empleados_list_by_nombre($nombre, $config['per_page'] , $offset, 2);
                        $config['total_rows'] = $data['total_registros'] = $this->empleado->get_empleados_count_list_by_nombre($nombre,2);
                        $config['base_url'] = base_url() . "index.php/" . $ruta . "/" . $controller . "/" . $funcion . "/$subfuncion/fil_nom/$nombre/";
                        $config['uri_segment'] = 7;
                    } elseif ($this->uri->segment(5) == "fil_apa") {
                        $apaterno = $this->uri->segment(6);
                        $page = (int) $this->uri->segment(7);
                        if ($page > 0)
                            $offset = $page;
                        else if ($page == '')
                            $offset = 0;
                        else
                            $offset = 0;
                        $data['empleados'] = $this->empleado->get_empleados_list_by_apaterno($apaterno, $config['per_page'] , $offset,2);
                        $config['total_rows'] = $data['total_registros'] = $this->empleado->get_empleados_count_list_by_apaterno($apaterno,2);
                        $config['base_url'] = base_url() . "index.php/" . $ruta . "/" . $controller . "/" . $funcion . "/$subfuncion/fil_apa/$apaterno/";
                        $config['uri_segment'] = 7;
                    } elseif ($this->uri->segment(5) == "fil_ama") {
                        $amaterno = $this->uri->segment(6);
                        $page = (int) $this->uri->segment(7);
                        if ($page > 0)
                            $offset = $page;
                        else if ($page == '')
                            $offset = 0;
                        else
                            $offset = 0;
                        $data['empleados'] = $this->empleado->get_empleados_list_by_amaterno($amaterno, 30, $offset,2);
                        $config['total_rows'] = $data['total_registros'] = $this->empleado->get_empleados_count_list_by_amaterno($amaterno,2);
                        $config['base_url'] = base_url() . "index.php/" . $ruta . "/" . $controller . "/" . $funcion . "/$subfuncion/fil_ama/$amaterno/";
                        $config['uri_segment'] = 7;
                    } elseif ($this->uri->segment(5) == "fil_pue") {
                        $puesto_id = $this->uri->segment(6);
                        $page = (int) $this->uri->segment(7);
                        if ($page > 0)
                            $offset = $page;
                        else if ($page == '')
                            $offset = 0;
                        else
                            $offset = 0;
                        $data['empleados'] = $this->empleado->get_empleados_list_by_puestoid($puesto_id, 30, $offset,2);
                        $config['total_rows'] = $data['total_registros'] = $this->empleado->get_empleados_count_list_by_puestoid($puesto_id,2);
                        $config['base_url'] = base_url() . "index.php/" . $ruta . "/" . $controller . "/" . $funcion . "/$subfuncion/fil_pue/$puesto_id/";
                        $config['uri_segment'] = 7;
                    } elseif ($this->uri->segment(5) == "fil_esp") {
                        $espacio_id = $this->uri->segment(6);
                        $page = (int) $this->uri->segment(7);
                        if ($page > 0)
                            $offset = $page;
                        else if ($page == '')
                            $offset = 0;
                        else
                            $offset = 0;
                        $data['empleados'] = $this->empleado->get_empleados_list_by_espacioid($espacio_id, 30, $offset,2);
                        $config['total_rows'] = $data['total_registros'] = $this->empleado->get_empleados_count_list_by_espacioid($espacio_id,2);
                        $config['base_url'] = base_url() . "index.php/" . $ruta . "/" . $controller . "/" . $funcion . "/$subfuncion/fil_esp/$espacio_id/";
                        $config['uri_segment'] = 7;
                    } else {
                        $page = (int) $this->uri->segment(5);
                        if ($page > 0)
                            $offset = $page;
                        else if ($page == '')
                            $offset = 0;
                        else
                            $offset = 0;
                        $data['empleados'] = $this->empleado->get_empleados_list(30, $offset,2);
                        $config['total_rows'] = $data['total_registros'] = $this->empleado->get_empleados_count_list(2);
                        $config['base_url'] = base_url() . "index.php/" . $ruta . "/" . $controller . "/" . $funcion . "/$subfuncion/";
                    }
                    $this->pagination->initialize($config);
                }
            }
            if ($main_view) {
                $this->load->view("ingreso", $data);
                unset($data);
            }
        }
    }

    function alta_empleado() {
        if (isset($_POST['id']) && (int) $_POST['id'] > 0)
            $e = new Empleado((int) $_POST['id']);
        else
            $e = new Empleado();
        $dest = '/nomina/empleado_c/formulario/list_empleados';
        $e->fecha_ingreso = date_format(date_create_from_format("d m Y", $_POST['fecha_ingreso']), "Y-m-d");
        unset($_POST['fecha_ingreso']);
        if($_POST['fecha_ingreso_imss']!="")
	        $e->fecha_ingreso_imss = date_format(date_create_from_format("d m Y", $_POST['fecha_ingreso_imss']), "Y-m-d");
        unset($_POST['fecha_ingreso_imss']);
        $e->fecha_nacimiento = date_format(date_create_from_format("d m Y", $_POST['fecha_nacimiento']), "Y-m-d");
        unset($_POST['fecha_nacimiento']);
        $_POST['salario'] = str_replace(array("$", ","), "", $_POST['salario']);
        $_POST['salario_imss'] = str_replace(array("$", ","), "", $_POST['salario_imss']);
        $e->fecha_cambio = date("Y-m-d");
        if ($e->save($e->from_array($_POST))) {
            $this->db->query("update control_actualizaciones set fecha_cambio='" . date("Y-m-d") . "', hora='" . date("H:i:s") . ".00' where id='8'");
            echo '<script type="text/javascript">' . "\n";
            echo 'alert("El registro se ha guardado.");' . "\n";
            echo 'window.location.href="' . site_url($dest) . '";' . "\n";
            echo '</script>';
        } else {
            echo '<script type="text/javascript">' . "\n";
            echo 'alert("El registro no se pudo guardar, intente de nuevo");' . "\n";
            echo 'window.location.href="' . site_url('/inicio/acceso/nomina/menu') . '";' . "\n";
            echo '</script>';
        }
    }

    function borrar_usuario() {
        $this->load->model(array("vacacion_factor", "salario_integral_factor","descuento_programado"));
        $id = $_POST['id'];
        //SALARIOS
        $salario_diario = (float) str_replace(array("$", ","), "", $_POST['salario']);
        $salario_imss = (float) str_replace(array("$", ","), "", $_POST['salario_imss']);
        //FECHAS        
        $ingreso = explode(" ", $_POST['fecha_ingreso']);
        $ingreso_imss = explode(" ", $_POST['fecha_ingreso_imss']);
        $baja = explode(" ", $_POST['fecha_baja']);
        $inicio = gregoriantojd($ingreso[1], $ingreso[0], ((int) $ingreso[1] < (int) $baja[1] ? $baja[2] : $baja[2] - 1));
        $inicio_imss = gregoriantojd($ingreso_imss[1], $ingreso_imss[0], $ingreso_imss[2]);
        
        if($ingreso[2]<$baja[2])
		  $inico_anio = gregoriantojd("01", "01", $baja[2]);
		else
		  $inico_anio = gregoriantojd($ingreso[1], $ingreso[0], $ingreso[2]);
        
        $fin = gregoriantojd($baja[1], $baja[0], $baja[2]);

        //DIAS TRABAJADOS
        $aniosCumplidos = 1 + date_diff(date_create_from_format("d m Y", $_POST['fecha_baja']), date_create_from_format("d m Y", $_POST['fecha_ingreso']))->format("%y");
        $aniosCumplidos_imss = 1 + date_diff(date_create_from_format("d m Y", $_POST['fecha_baja']), date_create_from_format("d m Y", $_POST['fecha_ingreso_imss']))->format("%y");
        $diasTrabajados = 1 + ($fin - $inicio);
        $diasTrabajados_imss = 1 + ($fin - $inicio_imss);
        $diasVacaciones = $this->vacacion_factor->get_dias_by_anos($aniosCumplidos);
        $diasVacaciones_imss = $this->vacacion_factor->get_dias_by_anos($aniosCumplidos_imss);
        $diasAguinaldo = 15;
        $diasTAguinaldo = 1 + ($fin - $inico_anio);
        $diasAno = 365;
        //FACTORES
        $factorIntegracion = $this->salario_integral_factor->get_factor_by_anos($aniosCumplidos);
        $primaVacacional = 0.25;

        //CALCULO REAL------------>
        $pagoVacaciones = (($diasVacaciones * $salario_diario) / $diasAno) * $diasTrabajados; //cambiar a dias trabajados por año en vacaciones no en total
        $pagoPVacacional = $pagoVacaciones * $primaVacacional;
        $pagoAguinaldo = (($diasAguinaldo * $salario_diario) / $diasAno) * $diasTAguinaldo;

        //CALCULO IMSS------------>
        $salarioDesintegrado = $salario_imss / $factorIntegracion;
        $pagoVacaciones_imss = (($diasVacaciones_imss * $salarioDesintegrado) / $diasAno) * $diasTrabajados_imss;
        $pagoPVacacional_imss = $pagoVacaciones_imss * $primaVacacional;
        $pagoAguinaldo_imss = (($diasAguinaldo * $salarioDesintegrado) / $diasAno) * $diasTAguinaldo;
        
        //Calculo de descuentos_programados
        $descuento=$this->descuento_programado->get_descuentos($_POST['id']);
        if($descuento != false){
							$debe=0;        	
        	}
				  if($descuento->monto <= $descuento->deuda_total){
            $debe=$descuento->deuda_total - $descuento->monto;
            $concepto=$descuento->tag;
        }else {
					$debe=0;        
        }
        $to=$pagoVacaciones + $pagoPVacacional + $pagoAguinaldo-$debe;
    

         $json = "[{'vacas':'" . $pagoVacaciones . "','pvaca':'" . $pagoPVacacional . "','debe1':'" . $debe . "','concepto':'" . $concepto . "','aguna':'" . $pagoAguinaldo . "','tor':'" . round($to,2) . "','total':'" . ($pagoVacaciones + $pagoPVacacional + $pagoAguinaldo-$debe) . "'}," .
                "{'vacas':'" . $pagoVacaciones_imss . "','pvaca':'" . $pagoPVacacional_imss . "','debe1':'" . $debe . "','concepto':'" . $concepto . "','aguna':'" . $pagoAguinaldo_imss . "','tor':'" . round($to,2) . "','total':'" . ($pagoVacaciones_imss + $pagoPVacacional_imss + $pagoAguinaldo_imss) . "'}]";
        $emp = new Empleado($id);
        $emp->estatus_general_id = 1;
        if ($emp->save())
            echo $json;
        else
            echo "false";
    }

    function _comprobar_vacaciones_empleados() {
        $this->load->model(array("vacacion", "vacacion_factor"));
        $empleados = $this->empleado->get_empleados_activos();
        foreach ($empleados as $row) {
            $aniosCumplidos = 1 + date_diff(date_create_from_format("Y-m-d", date("Y-m-d")), date_create_from_format("Y-m-d", $row->fecha_ingreso))->format("%y");
            $hoy = explode("-", date("Y-m-d"));
            $ingreso = explode("-", $row->fecha_ingreso);
            if (!$this->vacacion->registrado($hoy[0], $row->id)) {
                if ($ingreso[0] < $hoy[0]) {
                    if ($ingreso[1] <= $hoy[1]) {
                        if ($ingreso[2] <= $hoy[2]) {
                            $vacas = new Vacacion();
                            $dias = $this->vacacion_factor->get_dias_by_anos($aniosCumplidos);
                            $vacas->fecha_inicio = date("Y-m-d");
                            $vacas->fecha_fin = date_format(date_create_from_format("d/m/Y", jdtogregorian(gregoriantojd($hoy[1], $hoy[2], $hoy[0]) + $dias)), Y-m-d);
                            $vacas->ano = $hoy[0];
                            $vacas->vacacion_factor_id = $this->vacacion_factor->get_id_by_anos($aniosCumplidos);
                            $vacas->empleado_id = $row->id;
                            $vacas->vacacion_estado_id = 2;
                            if (!$vacas->save()) {
                                echo '<script type="text/javascript">' . "\n";
                                echo 'alert("Error al verificar vacaciones");' . "\n";
                                echo 'window.location.href="' . site_url('/inicio/acceso/nomina/menu') . '";' . "\n";
                                echo '</script>';              
                            }
                        }
                    }
                }
            }
        }
    }

	function alta_comision_porcentaje_meta(){
		$u= new Comision();
		$u->usuario_id=$GLOBALS['usuarioid'];
		$u->fecha_captura=date("Y-m-d");
		$related = $u->from_array($_POST);
		if($u->save($related))
			echo $u->id;
		else
			echo 0;
	}
    
    function alta_comision_rango(){
		$datos=$_POST;
		$rango=$datos['rango'];
		$colect=array();
		//Preparar los rangos en el criterio uno
		for($x=1;$x<$rango;$x++){
			$min=$datos['min'.$x];
			$max=$datos['max'.$x];
			$comision=$datos['comision'.$x];
			if($min>0){
				$colect[]="$min-$max-$comision";
			}
		}
		//Revisar que colect tenga al menos un rango
		if(count($colect)>0){
			$string=implode('&', $colect);
			$u= new Comision();
			if(isset($datos['id']))
			  $u->get_by_id($datos['id']);
			$u->usuario_id=$GLOBALS['usuarioid'];
			$u->fecha_captura=date("Y-m-d");
			$u->tag=$datos['tag'];
			$u->criterio1=$string;
			$u->espacio_fisico_id=$datos['espacio_fisico'];
			$u->tipo_comision_id=$datos['tipo_comision_id'];
			if($u->save()){
				echo "<html> <script>alert(\"Se ha guardado la comision por rango de ventas.\"); window.location='".base_url()."index.php/nomina/empleado_c/formulario/list_comisiones';</script></html>";
			}
		} else {
			show_error("Error al intentar guardar la comision por rango, intente de nuevo");
		}
	}
	
	function alta_salario_minimo(){
		$this->load->model("salario_minimo");
		$u= new Salario_minimo();
		$u->where('anio', $_POST['anio'])->limit(1)->get();
		$u->usuario_id=$GLOBALS['usuarioid'];
		$u->fecha_captura=date("Y-m-d");
		$related = $u->from_array($_POST);
		// save with the related objects
		if($u->save($related)) {
				echo "<html> <script>alert(\"Se ha guardado el nuevo registro del salario minimo general.\"); window.location='".base_url()."index.php/nomina/empleado_c/formulario/list_salarios_minimos';</script></html>";
		} else {
			show_error("Error al intentar guardar el registro del salario minimo, intente de nuevo");
		}
		
	}
	
	function alta_tipo_descuento(){
		$this->load->model("tipo_descuento");
		$u= new Tipo_descuento();
		$u->usuario_id=$GLOBALS['usuarioid'];
		$u->fecha_captura=date("Y-m-d");
		$related = $u->from_array($_POST);
		if($u->save($related)) {
				echo "<html> <script>alert(\"Se ha guardado el nuevo registro del tipo de descuento.\"); window.location='".base_url()."index.php/nomina/empleado_c/formulario/list_tipos_descuentos';</script></html>";
		} else {
			show_error("Error al intentar guardar el registro del tipo de descuento, intente de nuevo");
		}
		
	}
	
	function alta_descuento_programado(){
		$this->load->model("descuento_programado");
		$u= new Descuento_programado();
		$u->usuario_id=$GLOBALS['usuarioid'];
		$u->fecha_captura=date("Y-m-d");
		$fecha=explode(" ", $_POST['fecha_inicio']);
		unset($_POST['fecha_inicio']);
		$u->fecha_inicio=$fecha[2]."-".$fecha[1]."-".$fecha[0];
		$u->estatus_descuento_id=1;
		$related = $u->from_array($_POST);
		$u->no_pagos=ceil($u->deuda_total/$u->monto_descuento_semanal);
		if($u->save($related)) {
				echo "<html> <script>alert(\"Se ha guardado el nuevo registro del descuento.\"); window.location='".base_url()."index.php/nomina/empleado_c/formulario/list_descuentos_programados';</script></html>";
		} else {
			show_error("Error al intentar guardar el registro del descuento, intente de nuevo");
		}
		
	}
}
?>