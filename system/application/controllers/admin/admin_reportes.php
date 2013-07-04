<?php
class Admin_reportes extends Controller {
	//var $usuarioid, $username, $ruta, $controller, $funcion, $subfuncion, $modulos_totales;
	function Admin_reportes()
	{
		parent::Controller();
		if($this->session->userdata('logged_in') == FALSE){
			redirect(base_url()."index.php/inicio/logout");
		}
		$this->assetlibpro->add_css('default.css');
		$this->assetlibpro->add_css('menu_style.css');
		$this->assetlibpro->add_css('date_input.css');
		$this->assetlibpro->add_css('jquery.validator.css');
		// 		$this->assetlibpro->add_css('fancybox/jquery.fancybox-1.3.1.css');
		// 		$this->assetlibpro->add_css('style_fancy.css.css');
		$this->assetlibpro->add_js('jquery.js');
		/*		$this->assetlibpro->add_js('jquery.autocomplete.js.js');
		 $this->assetlibpro->add_js('jquery.selectboxes.js');
		$this->assetlibpro->add_js('jquery.select-autocomplete.js');
		$this->assetlibpro->add_js('jquery.numberformatter.js');
		$this->assetlibpro->add_js('jquery.AddIncSearch.js');*/
		$this->assetlibpro->add_js('jquery.validator.js');
		$this->assetlibpro->add_js('jquery.date.js');
		$this->assetlibpro->add_js('jquery.form.js');
		/*		$this->assetlibpro->add_js('fancybox/jquery.mousewheel-3.0.2.pack.js');*/
		$this->assetlibpro->add_js('fancybox/jquery.fancybox-1.3.1.js');
		$this->load->model("espacio_fisico");
		$this->load->model("accion");
		$this->load->model("usuario_accion");
		$this->load->model("tipo_espacio");
		$this->load->model("estado");
		$this->load->model("municipio");
		$this->load->model("grupo");
		$this->load->model("puesto");
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
			if ($subfuncion=="rep_espacios_f"){
				$data['frames']=1;
				$data['principal']=$ruta."/".$subfuncion;
				$data['empresas']=$this->empresa->get_empresas();
				$data['tipos_espacios']=$this->tipo_espacio->get_tipos_espacios();
			}
			if ($subfuncion=="rep_empresas"){
				//Cargar los datos para el formulario
				//Definir el numero de frames
				$data['frames']=1;
				//Definir la vista
				$data['principal']=$ruta."/".$subfuncion;
			}
			if ($subfuncion=="rep_grupos"){
				//Cargar los datos para el formulario
				$data['grupos'] = $this->grupo->get_grupos_pdf();
				$this->load->view("$ruta/rep_grupos_pdf", $data);
			}
			if ($subfuncion=="rep_estados"){
				//Cargar los datos para el formulario
				$data['estados'] = $this->estado->get_estados_pdf();
				$this->load->view("$ruta/rep_estados_pdf", $data);
			}
			if ($subfuncion=="rep_municipios"){
				//Cargar los datos para el formulario
				$data['municipios'] = $this->municipio->get_municipios_pdf();
				$this->load->view("$ruta/rep_municipios_pdf", $data);
			}
			if ($subfuncion=="rep_puestos"){
				//Cargar los datos para el formulario
				$data['puestos'] = $this->puesto->get_puestos_pdf();
				$this->load->view("$ruta/rep_puestos_pdf", $data);
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
		global $ruta, $subfuncion;
		$data['title']=$this->accion->get_title("{$_POST['subfuncion']}");
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
	function rep_espacios_f_pdf(){
		global $ruta, $subfuncion;
		$data['title']=$this->accion->get_title("{$_POST['subfuncion']}");
		//Obtener los datos
		$empresas=$_POST['empresas'];
		$tipos_espacios=$_POST['tipos_espacios'];
		if($empresas==1000){
			$where=" where ef.empresas_id>0 ";
		} else {
			$where=" where ef.empresas_id='$empresas' ";
		}
		if($tipos_espacios==1000){
			$where .=" and tipo_espacio_id>0 ";
		} else {
			$where .=" and tipo_espacio_id='$tipos_espacios' ";
		}
		$nivel = array();
		$nivel[]=(int)$_POST['nivel1'];
		$nivel[]=(int)$_POST['nivel2'];
		$nivel[]=(int)$_POST['nivel3'];
		$campos = array();
		foreach ($nivel as $my_key=>$my_value){
			switch($my_value){
				case 1: $campos[] = 'empresa'; break;
				case 2: $campos[] = 'tipo_espacio'; break;
				case 3: $campos[] = 'ef.tag'; break;
			}
		}
		if (count($campos))
			$order_by = " order by ".implode(",", $campos)." ";
		else
			$order_by = "";
		//die(var_dump($order_clause));
		$data['espacios_fisicos']=$this->espacio_fisico->get_espacios_f_pdf($where, $order_by);
		$this->load->view("$ruta/rep_espacios_f_pdf", $data);
		//Enviarselo al view para general el PDF
	}
	function rep_empresas_pdf(){
		global $ruta, $subfuncion;
		$data['title']=$this->accion->get_title("{$_POST['subfuncion']}");
		//Obtener los datos
		$nivel = array();
		$nivel[]=(int)$_POST['nivel1'];
		$nivel[]=(int)$_POST['nivel2'];
		$campos = array();
		foreach ($nivel as $my_key=>$my_value){
			switch($my_value){
				case 1: $campos[] = 'razon_social'; break;
				case 2: $campos[] = 'ciudad'; break;
			}
		}
		if (count($campos))
			$order_by = " order by ".implode(",", $campos)." ";
		else
			$order_by = "";
		//die(var_dump($order_clause));
		$data['empresas']=$this->empresa->get_empresas_pdf("",$order_by);
		$this->load->view("$ruta/rep_empresas_pdf", $data);
		//Enviarselo al view para general el PDF
	}
}
?>
