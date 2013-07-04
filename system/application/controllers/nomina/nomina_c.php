<?php
class Nomina_c extends Controller {
    //var $usuarioid, $username, $ruta, $controller, $funcion, $subfuncion, $modulos_totales;
    function Nomina_c() {
        parent::Controller();
        if ($this->session->userdata('logged_in') == FALSE) {
            redirect(base_url() . "index.php/inicio/logout");
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
        $this->load->model("nomina");
        $this->load->model("nomina_validacion");
        $user_hash = $this->session->userdata('user_data');
        $row = $this->usuario->get_usuario_detalles($user_hash);
        $GLOBALS['usuarioid'] = $row->id;
        $GLOBALS['username'] = $row->username;
        $GLOBALS['ruta'] = $this->uri->segment(1);
        $GLOBALS['controller'] = $this->uri->segment(2);
        $GLOBALS['funcion'] = $this->uri->segment(3);
        $GLOBALS['subfuncion'] = $this->uri->segment(4);
        $GLOBALS['modulos_totales'] = $this->modulo->get_tmodulos();
        $GLOBALS['main_menu'] = $this->menu->menus($GLOBALS['usuarioid'], "principal", 0);
    }

//##################################

    function formulario() {
        //Funcion para manejar formularios y listados,
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
        if (($this->session->userdata('logged_in') == TRUE and $data['permisos']!= false )) {
            $main_view = true;
            if ($subfuncion == "alta_nomina") {
                //Cargar los datos para el formulario
                //Definir la vista
                $data['principal'] = $ruta . "/" . $subfuncion;
                $data['modulo'] = $ruta;
                //Obtener Datos
                $data['validation'] = $this->nomina_validacion->validacion_nomina();
            } else if ($subfuncion == "list_nominas") {
                if ($this->uri->segment(5) == "editar_nomina") {
                    //Cargar los datos para el formulario
                    $rid = (int) $this->uri->segment(6);
                    if ($rid <= 0)
                        show_error('No se ha seleccionado un registro valido');
                    //Definir el numero de frames
                    $data['frames'] = 0;
                    //Definir la vista
                    $data['principal'] = $ruta . "/editar_nomina";
                    //Obtener Datos de la nomina
                    $reg = new Nomina();
                    $reg->get_by_id($rid);
                    if (!$reg->exists())
                        show_error('El registro especificado no existe');
                    $data['reg'] = $reg;
                    $data['title'] = 'Editar nomina';
                    $data['validation'] = $this->nomina_validacion->validacion_nomina();
                }  else if($this->uri->segment(5)=="borrar_nomina"){
					$id=$this->uri->segment(6);
					$main_view=false;
					if(substr(decbin($data['permisos']), 2, 1)==1){
						$u=new Nomina();
						$u->get_by_id($id);
						$u->estatus_general_id=2;
						$u->usuario_id=$usuarioid;;
						if($u->save()){
						  $this->db->query("update nomina_detalles set estatus_general_id=2 where nomina_id=$id");
						  //$this->db->query("update prenominas set aplicada=0 where prenomina_id=$u->prenomina_id");
						  $nd=new Nomina_detalle();
						  $nd->where('nomina_id', $id)->get();
						  if($nd->c_rows>0){
							foreach($nd->all as $li){
							  $this->db->query("update nomina_empleado_detalles set estatus_general_id=2 where nomina_detalle_id=$li->id");
							}
						  }
						}
						redirect(base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion);
					} else {
						$main_view=true;
						$data['principal']="error";
						$data['error_field']="No tiene permisos para deshabilitar la Nómina";
					}
				} 
                else {
					
                    //Cargar los datos para el formulario
                    //Definir el numero de frames
                    $data['frames'] = 1;
                    //Definir la vista
                    $data['principal'] = $ruta . "/" . $subfuncion;
                    //Obtener Datos
                    $this->load->library('pagination');
                    $config['base_url'] = base_url() . "index.php/" . $ruta . "/" . $controller . "/" . $funcion . "/$subfuncion/";
                    $config['per_page'] = '30';
                    $page = (int) $this->uri->segment(5);
                    //Identificar el numero de pagina en el paginador si existe
                    if ($page > 0)
                        $offset = $page;
                    else if ($page == '')
                        $offset = 0;
                    else
                        $offset = 0;
                    $total_registros = (int) $this->db->count_all('nominas');
                    $data['nominas'] = $this->nomina->get_nominas_list($config['per_page'], $offset);
                    $data['total_registros'] = $total_registros;
                    $config['total_rows'] = $total_registros;
                    $this->pagination->initialize($config);
                }
            }
            elseif ($subfuncion == "generar_nomina_salarios") {
                //Cargar los datos para el formulario
                //Definir la vista
                $data['principal'] = $ruta . "/" . $subfuncion;
                $data['modulo'] = $ruta;
                //Obtener Datos
                $data['validation'] = $this->nomina_validacion->validacion_nomina();
                $data['espacios'] = $this->espacio_fisico->get_espacios_as_array();
            }  elseif ($subfuncion == "asignar_vacaciones") {                
                $data['principal'] = $ruta . "/" . $subfuncion;
                $data['modulo'] = $ruta;
            } elseif ($subfuncion == "asignar_vacaciones") {                
                $data['principal'] = $ruta . "/" . $subfuncion;
                $data['modulo'] = $ruta;
            }
            if ($main_view) {
                //Llamar a la vista
                $this->load->view("ingreso", $data);
            } else {
                redirect(base_url() . "index.php/inicio/logout");
            }
        }
    }

//##########################################

        function alta_nomina() { // BEGIN method alta_nomina
        global $ruta;
		$this->load->model(array("prenomina","nomina"));
//         echo "<pre>";var_dump($_POST);echo "</pre>";die();
        $prenomina_id=$_POST['prenomina_id'];
		$pre=new Prenomina();
		$pre->get_by_id($prenomina_id);
        $reg = new Nomina();
        if($_POST['id']==0)
		  unset($_POST['id']);
		else
		  $reg->get_by_id($_POST['id']);
		$reg->espacio_fisico_id=$pre->espacio_fisico_id;
		$reg->empresa_id=$pre->empresa_id;
		$reg->prenomina_id=$pre->id;
		$reg->fecha_captura=date("Y-m-d");
		$reg->usuario_id=$GLOBALS['usuarioid'];
        $reg->tag = " ";
        if ($reg->save()) {
		  $pre->aplicada=1;
		  $pre->save();
		  echo $reg->id;
		} else 
		  echo 0;
    }


    function alta_nomina_detalle(){
	  $this->load->model(array("prenomina","nomina",'nomina_detalle',"empleado", "nomina_empleado_detalle","prestacion", "deduccion"));
// 	  print_r($_POST); exit();
	  $nd= new Nomina_detalle();
	  if($_POST['id']==0)
		unset($_POST['id']);
	  else
		$nd->get_by_id($_POST['id']);
	  $nd->prenomina_detalle_id=$_POST['prenomina_detalle_id'];
	  $nd->nomina_id=$_POST['nomina_id'];
	  //Obtener el empleado
	  $e=new Empleado();
	  $e->get_by_id($_POST['empleado_id']);
	  $nd->empleado_id=$_POST['empleado_id'];
	  $nd->monto_total=$_POST['monto_total'];
	  $nd->monto_banco=$_POST['monto_banco'];
	  $nd->monto_efectivo=$_POST['monto_efectivo'];
	  $nd->fecha_captura=date("Y-m-d");
	  $nd->usuario_id=$GLOBALS['usuarioid'];
	  if($nd->save()){
		/**Dar de alta cada uno de los conceptos de la nomina **/
		$nem= new Nomina_empleado_detalle();
		$nem->nomina_detalle_id=$nd->id;
		$nem->empleado_id=$e->id;
		$nem->usuario_id=$nd->usuario_id;
		$nem->fecha_captura=$nd->fecha_captura;
		
		//Sueldo Base = Salario diario * Dias Laborados
		$nem->monto=$nd->monto_total;
		$nem->tipo_detalle_nomina_id=1;
		$nem->save();
		
		//Comision
		$c=$nem->get_copy();
		$c->monto=$_POST['comision'];
		$c->tipo_detalle_nomina_id=2;
		$c->referencia_id=$e->comision_id;
		$c->save();
		
		//INFONAVIT
		$infonavit=$_POST['descuento_infonavit'];
		if($infonavit>0){
		  $i=$nem->get_copy();
		  $i->monto=$infonavit;
		  $i->tipo_detalle_nomina_id=3;
		  $i->save();
		}
		
		//Horas Extra		
		$horas_e=$_POST['valor_horas_extra'];
		if($horas_e>0){
		  $he=$nem->get_copy();
		  $he->monto=$horas_e;
		  $he->tipo_detalle_nomina_id=4;
		  $he->save();
		}
		
		//Descuentos Programados
		$desc_prog=$_POST['descuento_programado'];
		if($desc_prog>0){
		  $this->load->model("descuento_programado", "nomina");
		  //Obtener la fecha final de la nomina
		  $prenomina=$this->nomina->get_prenomina_by_nomina($nd->nomina_id);
		  $mp=new Descuento_programado();
		  //Obtener los descuentos programados del empleado	 
		  $mp->where('empleado_id', $e->id)->where('estatus_descuento_id', 1)->where('fecha_inicio <=', $prenomina->fecha_final)->get();	
		  foreach($mp as $des){
			$de=$nem->get_copy();
			$de->monto=$des->monto_descuento_semanal;
			$de->tipo_detalle_nomina_id=5;
			$de->referencia_id=$des->id;
			$de->save();
		  }
		}
		
		//Percepciones
		//Obtener el id maximo de percepciones para hacer un bucle
		$p_max=$this->prestacion->get_id_maximo();
		if($p_max>0){
		  for($x=1;$x<=$p_max;$x++){
			if(isset($_POST["pres_$x"])){
			  //SI existe el campo en el formulario, validar que tenga valor
			  if($_POST["pres_$x"]>0){
				$pre=$nem->get_copy();
				$pre->monto=$_POST["pres_$x"];
				$pre->tipo_detalle_nomina_id=6;
				$pre->referencia_id=$x;
				$pre->save();
			  }
			}
		  }
		}
		
		//Deducciones
		//Obtener el id maximo de deducciones para hacer un bucle
		$p_max=$this->deduccion->get_id_maximo();
		if($p_max>0){
		  for($x=1;$x<=$p_max;$x++){
			if(isset($_POST["ded_$x"])){
			  //SI existe el campo en el formulario, validar que tenga valor
			  if($_POST["ded_$x"]>0){
				$pre=$nem->get_copy();
				$pre->monto=$_POST["ded_$x"];
				$pre->tipo_detalle_nomina_id=7;
				$pre->referencia_id=$x;
				$pre->save();
			  }
			}
		  }
		}
		
		//Regresar datos de alta de registro exitoso
		echo $nd->id;
	  } else {
		//No se guardo el detalle de nomina
		echo 0;
	  }
		

  }

	
	
    function asignar_vacaciones() {
        
    }
    


}

?>
