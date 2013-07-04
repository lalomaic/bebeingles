<?php
class Admin_reportes extends Controller {
	//var $usuarioid, $username, $ruta, $controller, $funcion, $subfuncion, $modulos_totales;

	function Admin_reportes()
	{
		parent::Controller();
		if($this->session->userdata('logged_in') == FALSE){
			redirect(base_url()."index.php/inicio/logout");
		}
		$this->load->model("modulo");
		$this->load->model("menu");
		$this->load->model("usuario");
		$this->load->model("empresa");
		$this->load->model("espacio_fisico");
		$this->load->model("usuario_accion");
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
			if ($subfuncion=="rep_usuarios"){
				//Cargar los datos para el formulario
				//Definir el numero de frames
				$data['frames']=1;
				//Definir la vista
				$data['principal']=$ruta."/".$subfuncion;
				//Obtener Datos
				$data['empresas']=$this->empresa->get_empresas();
				$data['espacios_fisicos']=$this->espacio_fisico->get_espacios_f();

			}

			if($main_view){
				//Llamar a la vista
				$this->load->view("ingreso", $data);
			} else {
	  			redirect(base_url()."index.php/inicio/logout");
	  		}
		}
	}

	function rep_usuarios_pdf(){
		global $ruta;
		//Obtener los datos
		$empresas=$_POST['empresas'];
		$espacios=$_POST['espacios'];

		if($empresas==1000){
			$where=" where u.empresas_id>0 ";
		} else {
			$where=" where u.empresas_id='$empresas' ";
		}


		if($espacios==1000){
			$where .=" and espacio_fisico_id>0 ";
		} else {
			$where .=" and espacio_fisico_id='$espacios' ";
		}


		$nivel1=$_POST['nivel1'];
		if(isset($_POST['nivel2'])==false){
			$nivel2='';
		} else {
			$nivel2=$_POST['nivel2'];
		}
		if(isset($_POST['nivel3'])==false){
			$nivel3='';
		} else {
			$nivel3=$_POST['nivel3'];
		}
		$order_clause=" order by ";
		$value="";
		$ingreso=0;
		for($x=0;$x<4;$x++){
			if($x==1){
				$value=$nivel1;
				$ingreso+=1;
			} else if($x==2){
				$value=$nivel2;
				$ingreso+=1;
			}

			if($value ==1){
				$order_clause.="empresa,";
			} else if($value ==2){
				$order_clause.="espacio_fisico,";
			} else if($value ==3){
				$order_clause.="u.nombre,";
			} else if($value ==0){
				//$order_clause.="f.generalid,";
			}
		}
		if($ingreso>0){
			$order_by=substr($order_clause, 0,-1);
		} else {
			$order_by="";
		}
		$data['usuarios']=$this->usuario->get_usuarios_pdf($where, $order_by);
		$this->load->view("$ruta/rep_usuarios_pdf", $data);

		//Enviarselo al view para general el PDF

	}

}
?>
