<?php
class Nomina_reportes extends Controller {
  //var $usuarioid, $username, $ruta, $controller, $funcion, $subfuncion, $modulos_totales;

	function Nomina_reportes() {
	  parent::Controller();
	  if($this->session->userdata('logged_in') == FALSE){redirect(base_url()."index.php/inicio/logout");}
	  $this->assetlibpro->add_css('default.css');
 	  $this->assetlibpro->add_css('menu_style.css');
	  $this->assetlibpro->add_css('date_input.css');
	  $this->assetlibpro->add_css('jquery.validator.css');
	  $this->assetlibpro->add_js('jquery.js');
	  $this->assetlibpro->add_js('jquery.validator.js');
	  $this->assetlibpro->add_js('jquery.date.js');
	  $this->assetlibpro->add_js('jquery.form.js');
	  $this->assetlibpro->add_js('fancybox/jquery.fancybox-1.3.1.js');
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
	}

	function formulario(){
		//Funcion para manejar formularios y listados,
		global $main_menu, $usuarioid, $username, $ruta, $controller, $funcion, $subfuncion, $modulos_totales;
	  $main_view=false;
	  $data['username']=$username;
	  $data['usuarioid']=$usuarioid;
	  $data['modulos_totales']=$modulos_totales;
	  $data['colect1']=$main_menu;
	  $data['title']=$this->accion->get_title("$subfuncion");
	  $accion_id=$this->accion->get_id("$subfuncion");
	  $row=$this->usuario->get_usuario($usuarioid);
	  $grupoid=$row->grupo_id;
	  $puestoid=$row->puesto_id;
	  $data['ruta']=$ruta;
	  $data['controller']=$controller;
	  $data['funcion']=$funcion;
	  $data['subfuncion']=$subfuncion;
	  $data['permisos']=$this->usuario_accion->get_permiso($accion_id, $usuarioid, $puestoid, $grupoid);

	  //Validacion del arreglo del menu, usuarioid y permisos especificos
	  if(is_array($data['colect1']) and $usuarioid>0 and $data['permisos']!= false and $this->session->userdata('logged_in') == TRUE){
			$main_view=true;
			if ($subfuncion=="rep_empleado"){
				// obtener datos
				$main_view=false;
				$usr=new Usuario();
				$usr->get_by_id($usuarioid);
				$sql="SELECT e.id, e.nombre, e.apaterno, e.amaterno, puestos.tag AS puesto, nominas.tag AS nomina, horarios.tag AS horario, espacios_fisicos.tag AS espacio FROM empleados as e Left Join puestos ON puestos.id = e.puesto_id Left Join nominas ON nominas.id = e.tipo_nomina_id Left Join horarios ON horarios.id = e.horario_id Left Join espacios_fisicos ON espacios_fisicos.id = e.espacio_fisico_id WHERE e.estatus_general_id=1 ORDER BY nombre, apaterno, amaterno";
				$data['datos']=$this->db->query($sql)->result();
				$this->load->view("nomina/rep_empleados_pdf", $data);
				}
			else if($subfuncion == 'rep_horarios'){
				$datos=new Horario();
				$data['datos']=$datos->get()->all;
				$this->load->view("nomina/rep_horarios_pdf", $data);
			} 
			else if($subfuncion == 'rep_puestos')
				{
				$datos=new Puesto();
				$data['puestos']=$datos->get()->all;
				$datos=new Prestacion();
				$data['prestaciones']=array();
				foreach($datos->get()->all as $prestacion)
					$data['prestaciones'][$prestacion->id]=$prestacion->tag;
				$datos=new Deduccion();
				$data['deducciones']=array();
				foreach($datos->get()->all as $deduccion)
					$data['deducciones'][$deduccion->id]=$deduccion->tag;
				$this->load->view("nomina/rep_puestos_pdf", $data);
				}
			else if($subfuncion == 'rep_prestaciones')
				{
				$datos=new Prestacion();
				$data['datos']=$datos->get()->all;
				$this->load->view("nomina/rep_prestaciones_pdf", $data);
				}
			else if($subfuncion == 'rep_deducciones')
				{
				$datos=new Deduccion();
				$data['datos']=$datos->get()->all;
				$this->load->view("nomina/rep_deducciones_pdf", $data);
				}
			else if ($subfuncion=='rep_ventas_empleado_gral'){
					//Ventas de la sucursal por empleado
					$data['frames']=1;
					//Definir la vista
					$data['principal']=$ruta."/".$subfuncion;
					$this->load->model("espacio_fisico");
					$data['espacios']=$this->espacio_fisico->get_espacios_tiendas();

				}
		if($main_view){
			//Llamar a la vista
			$this->load->view("ingreso", $data);
		} else {
			//redirect(base_url()."index.php/inicio/logout");
		}
	  }
 	} // end method formulario ##################

 	function rep_ventas_empleado_gral_pdf(){
		//Procesar las fechas
		$this->load->model("nota_remision");
		$this->load->model("espacio_fisico");
		$espacio = $this->input->post('espacio_fisico_id');
		$fecha1 = $this->input->post('fecha1');
		$fecha2 = $this->input->post('fecha2');
		if(isset($_POST['fecha1'])==false or strlen($_POST['fecha1'])==0) {
			$hoy=date("Y-m-d");
			$fecha1=date("Y-m-d", strtotime($hoy));
			$fecha2=date("Y-m-d", strtotime($hoy)+(24 * 60 * 60));
			$data['fecha1']=date("d m Y", strtotime($hoy));;
			$data['fecha2']=$data['fecha1'];
		} else {
			$fecha=explode(" ", $_POST['fecha1']);
			$fecha1=$fecha[2]."-".$fecha[1]."-".$fecha[0];
			$fecha2a=$fecha[2]."-".$fecha[1]."-".$fecha[0];
			$data['fecha1']=$_POST['fecha1'];
			$data['fecha2']=$_POST['fecha2'];
		}
		$fecha=explode(" ", $_POST['fecha2']);
		$fecha2=date("Y-m-d", strtotime($fecha[2]."-".$fecha[1]."-".$fecha[0]));
		if($espacio>0) {
			$data['espacio']=$this->espacio_fisico->get_by_id($espacio);
			$data['espacio_nombre']=$data['espacio']->tag;
		} else {
			$data['espacio_nombre']="TODAS";
		}
		
				$data['ventas_empleados']=$this->nota_remision->get_ventas_empleados($fecha1,$fecha2, $espacio);
				$data['t_empleado']=$this->nota_remision->get_empleados_total();
				$data['fletes']=$this->nota_remision->get_ventas_empleados_flete($fecha1,$fecha2, $espacio);
				$data['comision_tienda']=$this->nota_remision->get_comision_tienda($espacio);
			 $data['abonos']=$this->nota_remision->get_abonos_empleados($fecha1,$fecha2,$espacio);
     $data['abonos_vales']=$this->nota_remision->get_abonos_empleados_vales($fecha1,$fecha2,$espacio);
 $data['abonos_cancelados']=$this->nota_remision->get_abonos_empleados_cancelados($fecha1,$fecha2,$espacio,$espacio);
		$this->load->view("nomina/rep_ventas_empleado_gral_pdf", $data);
	}
	
	function rep_nomina_pdf(){
	  $this->load->model(array("nomina", "nomina_detalle", "nomina_calculos"));
	  $data=array();
		//Obtener los datos de la nomina y prenomina
		$id=$this->uri->segment(4);
		if($id<0)
		  show_error("La nómina no es valida");
		
		$data['nomina']=$nomina=$this->nomina->get_nomina_pdf($id);
		//Obtener los detalles de la nomina
		$data['empleado']=$detalles=$this->nomina_detalle->get_detalles_by_nomina_id($nomina->id);
		
		$colect=array();
		//Obtener el detalle por empleado de la nomina
		foreach($detalles->all as $row)
		  $colect[$row->id]=$this->nomina_calculos->obtener_detalle_empleado_nomina($row->empleado_id, $row->id);
		
//		print_r($colect); exit();
		$data['detalles']=$colect;
		$this->load->helper('to_pdf');
		$this->load->view("nomina/rep_nominas_pdf", $data);
		$html = $this->output->get_output();
		pdf_create($html,'nomina');
		
	}


function rep_finiquito_pdf(){
	 	$fecha=explode(" ", $_POST['fecha_baja1']);
 	if($fecha['1']==01){
 $fecha[1]="Enero";		
 		}else if($fecha[1]==02){
 $fecha[1]="Febrero";		
 		}else if($fecha[1]==03) {$fecha[1]="Marzo";}
 			else if($fecha[1]==04) {$fecha[1]="Abril";}
 			else if($fecha[1]==05) {$fecha[1]="Mayo";}
 			else if($fecha[1]==06) {$fecha[1]="Junio";}
 			else if($fecha[1]==07) {$fecha[1]="Julio";}
 			else if($fecha[1]==08) {$fecha[1]="Agosto";}
 			else if($fecha[1]==09) {$fecha[1]="Septiembre";}
 			else if($fecha[1]==10) {$fecha[1]="Octubre";}
 			else if($fecha[1]==11) {$fecha[1]="Noviembre";}
 		 	else if($fecha[1]==12) {$fecha[1]="Diciembre";}	
 		
$fecha1=$fecha[0]." De ".$fecha[1]." del ".$fecha[2];		
	$id=$_POST['e_id'];
	 $emp = new Empleado($id);
        $emp->estatus_general_id = 2;
        $emp->save();
        if($_POST['debe1']>0)
     $this->db->query("update descuentos_programados set estatus_descuento_id	=3 where empleado_id	='$id'");
        	
	$this->load->model("numero_letras");
	$numer=$_POST['total1'];
	$letra=$this->numero_letras->convertir_a_letras($_POST['total1']);
	$data = array(
               'empleado' => $_POST['empleado'],
               'adeudos' => $_POST['debe1'],
               'aguinaldo' => $_POST['aguin1'],
               'aguinaldo_i' => $_POST['aguin1_i'],
               'total'=>	$_POST['total2'],
               'total_i'=>	$_POST['total2_i'],
               'vacaciones'=>$_POST['vacas1'],
               'vacaciones_i'=>$_POST['vacas1_i'],
               'pri_vacacional'=>$_POST['p_vaca1'],
                'pri_vacacional_i'=>$_POST['p_vaca1_i'],
               'fecha_ingreso'=>$_POST['fecha_ingreso1'],
               'fecha_ingreso_imss'=>$_POST['fecha_ingreso_imss1'],
                'fecha_baja'=>$_POST['fecha_baja1'],
               'fecha1'=>$fecha1,
               'puesto'=>$_POST['puesto'],
               'razon_social'=>$_POST['razon_social'],
               'salario'=> $_POST['salario1'],
                 'salario_im'=> $_POST['salario_imss'],
                 'letra'=> $letra,
                 'calle'=>$_POST['calle'],
                 'localidad'=>$_POST['localidad'],
                 'num_ext'=>$_POST['num_ext'],
                 'num_int'=>$_POST['num_int'],
                 'espacio_tag'=>$_POST['espacio_tag'],
                 'colonia'=>$_POST['colonia']
          );
      if($_POST['salario_imss']>0){
	 $this->load->helper('to_pdf');
		$this->load->view("nomina/finiquito_ims",$data);
		$html = $this->output->get_output();
		pdf_create($html,'Finiquito');
	}else {
			 $this->load->helper('to_pdf');
		$this->load->view("nomina/finiquito",$data);
		$html = $this->output->get_output();
		pdf_create($html,'Finiquito');
	
	}
		
		}

  } 
  // end class Nomina_reportes

?>
