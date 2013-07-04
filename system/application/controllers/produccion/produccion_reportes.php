<?php
class Produccion_reportes extends Controller {
	//var $usuarioid, $username, $ruta, $controller, $funcion, $subfuncion, $modulos_totales;

	function Produccion_reportes()
	{
		parent::Controller();
		if($this->session->userdata('logged_in') == FALSE){
			redirect(base_url()."index.php/inicio/logout");
		}
		$this->assetlibpro->add_css('default.css');
		$this->assetlibpro->add_css('menu_style.css');
		$this->assetlibpro->add_css('date_input.css');
		$this->assetlibpro->add_css('jquery.validator.css');
		$this->assetlibpro->add_css('autocomplete.css');
		$this->assetlibpro->add_css('fancybox/jquery.fancybox-1.3.1.css');
		$this->assetlibpro->add_css('style_fancy.css.css');
		$this->assetlibpro->add_js('jquery.js');
		$this->assetlibpro->add_js('jquery.jfield.js');
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
		$this->load->model("receta");
		$this->load->model("producto");
		$user_hash=$this->session->userdata('user_data');
		$row=$this->usuario->get_usuario_detalles($user_hash);
		$GLOBALS['usuarioid']=$row->id;
		$GLOBALS['empresa_id']=$row->empresas_id;
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
			if ($subfuncion=="rep_general_recetas"){
				//Cargar los datos para el formulario
				$data['frames']=1;
				$data['recetas'] = $this->receta->get_recetas_pdf();
				if ($data['recetas']){
					$this->load->view("produccion/rep_general_recetas_pdf", $data);
				}
				else{
					echo "<html> <script>alert(\"No hay aun Registros de Recetas en la Base de Datos favor de verificar.\"); window.location='".base_url()."index.php/inicio/acceso/produccion/menu';</script></html>";
				}
			}
			else if($subfuncion=="rep_etiquetas_codigo_barras"){
				$data['principal']=$ruta."/".$subfuncion;
				$data['title']="Impresi�n de Etiquetas con C�digo de Barras";
				$data['productos']=$this->producto->get_cproductos_etiquetas();
			}
		}
		if($main_view){
			//Llamar a la vista
			$this->load->view("ingreso", $data);
		} else {
			redirect(base_url()."index.php/inicio/logout");
		}
	}

	function rep_etiquetas_codigo_barras_pdf(){
		//Generar el PDF
		$this->load->plugin('barcode');
		//$id=505;
		$id=$_POST['producto_id'];
		$data['producto']=$this->producto->get_by_id($id);
		barcode_create($data['producto']->codigo_barras,"ean13","jpeg", 'cb_'.$id, '/var/www/soleman/tmp/');
		$data['pages']=$_POST['pages'];
		$this->load->library("fpdf_factura");
		$this->load->view('produccion/rep_etiquetas_codigo_barras_pdf', $data);
	}
	 
	 
}


?>
