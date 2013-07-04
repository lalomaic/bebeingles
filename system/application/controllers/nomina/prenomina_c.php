<?php
class Prenomina_c extends Controller
	{
	//var $usuarioid, $username, $ruta, $controller, $funcion, $subfuncion, $modulos_totales;
	function Prenomina_c() {
		parent::Controller();
		if($this->session->userdata('logged_in') == FALSE) {
			redirect(base_url()."index.php/inicio/logout");
		}
		$this->assetlibpro->add_css('default.css');
		$this->assetlibpro->add_css('menu_style.css');
		$this->assetlibpro->add_css('date_input.css');
		$this->assetlibpro->add_css('jquery.validator.css');
		$this->assetlibpro->add_js('jquery.js');
		$this->assetlibpro->add_js('jquery.validator.js');
		$this->assetlibpro->add_js('jquery.date.js');
		$this->assetlibpro->add_js('jquery.form.js');
		$this->assetlibpro->add_js('fancybox/jquery.fancybox-1.3.1.js');
		$this->load->model("espacio_fisico");
		$this->load->model("nomina_validacion");
		$this->load->model("prenomina");
		$user_hash=$this->session->userdata('user_data');
		$row=$this->usuario->get_usuario_detalles($user_hash);
		$GLOBALS['usuarioid']=$row->id;
		$GLOBALS['username']=$row->username;
		$GLOBALS['ruta']=$this->uri->segment(1);
		$GLOBALS['controller']=$this->uri->segment(2);
		$GLOBALS['funcion']=$this->uri->segment(3);
		$GLOBALS['subfuncion']=$this->uri->segment(4);
		$GLOBALS['modulos_totales']=$this->modulo->get_tmodulos();
		$GLOBALS['main_menu']=$this->menu->menus($GLOBALS['usuarioid'],"principal",0);
		}//##################################

	function formulario()
		{
		//Funcion para manejar formularios y listados,
		global $main_menu, $usuarioid, $username, $ruta, $controller, $funcion, $subfuncion, $modulos_totales;
		$main_view=false;
		$data['username']=$username;
		$data['usuarioid']=$usuarioid;
		$data['modulos_totales']=$modulos_totales;
		$data['colect1']=$main_menu;
		$data['title']=$this->accion->get_title($subfuncion);
		$accion_id=$this->accion->get_id($subfuncion);
		$row=$this->usuario->get_usuario($usuarioid);
		$grupoid=$row->grupo_id;
		$puestoid=$row->puesto_id;
		$data['ruta']=$ruta;
		$data['controller']=$controller;
		$data['funcion']=$funcion;
		$data['subfuncion']=$subfuncion;
		$data['permisos']=$this->usuario_accion->get_permiso($accion_id, $usuarioid, $puestoid, $grupoid);
		//Validacion del arreglo del menu, usuarioid y permisos especificos
		//$data['colect1']) and $usuarioid>0 and $data['permisos']!= false and
		if(($this->session->userdata('logged_in') == TRUE)) {
			$main_view=true;
			if($subfuncion=="generar_prenomina") {
				$data['principal']=$ruta."/".$subfuncion;
				$data['modulo']=$ruta;
				//Obtener Datos
				$data['validation']=$this->nomina_validacion->validacion_nomina();
				$data['espacios']=$this->espacio_fisico->get_espacios_tiendas_oficinas_mtrx();
			} else if($subfuncion=='list_prenominas'){
				if($this->uri->segment(5)=="editar_prenomina"){
					$main_view=true;
					$edit_usr=$this->uri->segment(5);
					$id=$this->uri->segment(6);
					$data['principal']=$ruta."/editar_prenomina";
					$data['modulo']=$ruta;
					$data['validation']=$this->nomina_validacion->validacion_nomina();
					$data['prenomina']=$this->prenomina->get_by_id($id);
					$data['espacios']=$this->espacio_fisico->get_espacios_tiendas_oficinas_mtrx();
				} else if ($this->uri->segment(5) == "generar_nomina") {
				  $this->load->model(array("nomina_calculos", "prestacion", "deduccion", "salario_minimo"));
				  //Cargar los datos para el formulario
				  $preid=$this->uri->segment(6);
				  $data['prenomina']=$this->prenomina->get_by_id($preid);
				  $data['salario_minimo']=$this->salario_minimo->get_by_anio(date("Y"));
				  //Obtener los datos de los detalles
				  $data['detalles']=$this->nomina_calculos->obtener_empleados($preid);
// 				  print_r($data['detalles']); exit();
				  $data['principal'] = $ruta . "/generar_nomina_salarios_prenomina";
				  $data['modulo'] = $ruta;
				  //Obtener Datos
				  $data['validation'] = $this->nomina_validacion->validacion_nomina();
				  $data['espacios'] = $this->espacio_fisico->get_espacios_as_array();
				  $data['prestaciones']=$this->prestacion->get_prestaciones();
				  $data['deducciones']=$this->deduccion->get_deducciones();
				} else if($this->uri->segment(5)=="borrar_prenomina"){
					$id=$this->uri->segment(6);
					$main_view=false;
					if(substr(decbin($data['permisos']), 2, 1)==1){
						$u=new Prenomina();
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
					$config['base_url'] = base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_prenominas/";
					$config['per_page'] = '30';
					$page=$this->uri->segment(5);
					if($page>0) 
						$offset=$page;
					else 
						$offset=0;
					$u1=new Prenomina();
					$u1->select('id')->get();
					$data['total_registros']=$config['total_rows']=$u1->c_rows;
					$data['prenominas']=$this->prenomina->get_prenominas_list($config['per_page'],$offset);
					$this->pagination->initialize($config);
				}				
			}
			if($main_view) {
				//Llamar a la vista
				$this->load->view("ingreso", $data);
			} else {
				redirect(base_url()."index.php/inicio/logout");
			}
		}
	}

	function generar_prenomina()
        {
            $this->load->model("prenomina");
            $pn=new Prenomina();
            $pn->usuario_id = $GLOBALS['usuarioid'];
            if($pn->save($pn->from_array($_POST))){
                if($pn->save()){
                    for($i=0; $i< count($_POST['empleado_id']); $i++){
                        $df = 0;
                        $dinc = 0;
                        $pnd=new Prenomina_detalle();
                        $pnd->prenominas_id = $pn->id;
                        $pnd->empleado_id = $_POST['empleado_id'][$i];
                        for($x = 1; $x <= $pn->dias_semana; $x++){
                            if($_POST['lista_asist'][$_POST['empleado_id'][$i]][$x] == 1 or $_POST['lista_asist'][$_POST['empleado_id'][$i]][$x] == 2 or $_POST['lista_asist'][$_POST['empleado_id'][$i]][$x] == 4)
                                $df++; 
                            else if($_POST['lista_asist'][$_POST['empleado_id'][$i]][$x] == 3)
                                $dinc++;
                        }
                        $pnd->dias_faltas = $df;
                        $pnd->dias_incapa = $dinc;
                        $pnd->horas_extra = $_POST['horas_extras'][$i];
                        if(!$pnd->save())
                            echo "false";
                    }
                    echo "true";
                } else
                    echo "false";
            } else {
                echo "false";
            }
	}
        }
?>
