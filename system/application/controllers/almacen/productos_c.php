<?php
class Productos_c extends Controller {
	function Productos_c()
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
		$this->assetlibpro->add_js('jquery.autocomplete.js');
		$this->assetlibpro->add_js('jquery.validator.js');
		$this->assetlibpro->add_js('jquery.date.js');
		$this->assetlibpro->add_js('jquery.form.js');
		$this->assetlibpro->add_js('jquery.jfield.js');
		$this->assetlibpro->add_js('fancybox/jquery.mousewheel-3.0.2.pack.js');
		$this->assetlibpro->add_js('fancybox/jquery.fancybox-1.3.1.js');
		$this->load->model("espacio_fisico");
		$this->load->model("estatus_general");
		$this->load->model("familia_producto");
		$this->load->model("subfamilia_producto");
		$this->load->model("material_producto");
		$this->load->model("color_producto");
		$this->load->model("marca_producto");
		$this->load->model("unidad_medida");
		$this->load->model("producto");
		$this->load->model("proveedor");
		$this->load->model("temporada_producto");
		$this->load->model("producto_validacion");
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
	  //Inicio del Bloque Alta Familia
	  if($subfuncion=="alta_familia"){
	  	//Definir la vista
	  	$data['principal']=$ruta."/".$subfuncion;
	  	//Obtener Datos
		  $data['validation']=$this->producto_validacion->validacion_familia_producto();

	  } else if($subfuncion=="list_familias"){

	  	if($this->uri->segment(5)=="editar_familia"){
	  		//Cargar los datos para el formulario
	  		$edit_usr=$this->uri->segment(5);
	  		$id=$this->uri->segment(6);
	  		//Definir la vista
	  		$data['principal']=$ruta."/editar_familia";
	  		//Obtener Datos
	  		$data['validation']=$this->producto_validacion->validacion_familia_producto();
	  		$data['familia']=$this->familia_producto->get_familia($id);
	  		$data['estatus']=$this->estatus_general->get_estatus();

	  	} else if($this->uri->segment(5)=="borrar_familia"){
	  		$id=$this->uri->segment(6);
	  		$main_view=true;

	  		if(substr(decbin($data['permisos']), 2, 1)==1){
	  			$u=new Familia_producto();
	  			$u->get_by_id($id);
	  			$u->estatus_general_id=2;
	  			$u->save();

	  			redirect(base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion);
	  		} else {
	  			$main_view=true;
	  			//Definir la vista
	  			$data['principal']="error";
	  			$data['error_field']="No tiene permisos para deshabilitar la Familia de Productos";
	  		}

	  	} else  {

	  		//Cargar los datos para el listado
	  		//Definir la vista
	  		$data['principal']=$ruta."/".$subfuncion;
	  		//Obtener Datos
	  		// load pagination class
	  		$this->load->library('pagination');
	  		$config['base_url'] = base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_familias/";
	  		$config['per_page'] = '10';
	  		$page=$this->uri->segment(5);
	  		//Identificar el numero de pagina en el paginador si existe
	  		if($page>0) {
	  			$offset=$page;
	  		} else if ($page==''){
	  			$offset=0;
	  		} else {
	  			$offset=0;
	  		}

	  		$u1=new Familia_producto();
	  		$u1->get();
	  		$total_registros=$u1->c_rows;
	  		$data['familia_productos']=$this->familia_producto->get_familia_productos_list($offset, $config['per_page']);
	  		$data['total_registros']=$total_registros;
	  		$config['total_rows'] = $total_registros;
	  		$this->pagination->initialize($config);

	  	}
	  } //Final del Bloque Alta de Familia

	  //Inicio del Bloque Alta Subfamilia
	  else if ($subfuncion == "alta_subfamilia") {
	  	//Cargar los datos para el formulario
	  	//Definir la vista
	  	$data['principal'] = $ruta . "/" . $subfuncion;
	  	//Obtener Datos
	  	$data['validation'] = $this->producto_validacion->validacion_subfamilia_producto();
	  	$data['estatus'] = $this->estatus_general->get_estatus();
	  	$data['familia_productos'] = $this->familia_producto->get_cproductos_familias();
	  } else if ($subfuncion == "list_subfamilias") {

	  	if ($this->uri->segment(5) == "editar_subfamilia") {
	  		//Cargar los datos para el formulario
	  		$edit_usr = $this->uri->segment(5);
	  		$id = $this->uri->segment(6);
	  		//Definir la vista
	  		$data['principal'] = $ruta . "/editar_subfamilia";
	  		//Obtener Datos
	  		$data['validation'] = $this->producto_validacion->validacion_subfamilia_producto();
	  		$data['subfamilia'] = $this->subfamilia_producto->get_subfamilia($id);
	  		$data['estatus'] = $this->estatus_general->get_estatus();
	  		$data['familia_productos'] = $this->familia_producto->get_cproductos_familias();
	  	} else if ($this->uri->segment(5) == "borrar_subfamilia") {
	  		$id = $this->uri->segment(6);
	  		$main_view = false;
	  		if (substr(decbin($data['permisos']), 2, 1) == 1) {
	  			$u = new Subfamilia_producto();
	  			$u->get_by_id($id);
	  			$u->estatus_general_id = 2;
	  			$u->save();

	  			redirect(base_url() . "index.php/" . $ruta . "/" . $controller . "/" . $funcion . "/" . $subfuncion);
	  		} else {
	  			$main_view = true;
	  			//Definir la vista
	  			$data['principal'] = "error";
	  			$data['error_field'] = "No tiene permisos para deshabilitar la subfamilia de productos";
	  		}
	  	} else {

	  		//Definir la vista
	  		$data['principal'] = $ruta . "/" . $subfuncion;
	  		// load pagination class
	  		$this->load->library('pagination');
	  		$config['base_url'] = base_url() . "index.php/" . $ruta . "/" . $controller . "/" . $funcion . "/list_subfamilias/";
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

	  		$u1 = new Subfamilia_producto();
	  		$u1->get();
	  		$total_registros = $u1->c_rows;
	  		$data['subfamilia_productos'] = $this->subfamilia_producto->get_subfamilia_productos_list($offset, $config['per_page']);
	  		$data['total_registros'] = $total_registros;
	  		$config['total_rows'] = $total_registros;
	  		$this->pagination->initialize($config);
	  	}
	  } //Final del Bloque Alta de Subfamilia

	  //Inicio del Bloque Alta Marca
	  else if($subfuncion=="alta_marca"){
	  	//Definir la vista
	  	$data['principal']=$ruta."/".$subfuncion;
	  	//Obtener Datos
		  $data['validation']=$this->producto_validacion->validacion_marca();
		  $data['estatus']=$this->estatus_general->get_estatus();
		  //	      $data['proveedores']=$this->proveedor->get_proveedores_marca();

	  } else if($subfuncion=="list_marcas"){
	  	$data['pag']=1;
	  	$data['principal']=$ruta."/".$subfuncion;
	  	$filtrado=$this->uri->segment(6);
	  	$id=$this->uri->segment(7);

	  	if($this->uri->segment(5)=="editar_marca"){
	  		//Cargar los datos para el formulario
	  		$edit_usr=$this->uri->segment(5);
	  		$id=$this->uri->segment(6);
	  		//Definir la vista
	  		$data['principal']=$ruta."/editar_marca";
	  		//Obtener Datos
	  		$data['validation']=$this->producto_validacion->validacion_marca();
	  		$data['marca']=$this->marca_producto->get_marca($id);
	  		$data['estatus']=$this->estatus_general->get_estatus();
	  		// 				$data['proveedores']=$this->proveedor->get_proveedores();

	  	} else if($this->uri->segment(5)=="borrar_marca"){
	  		$id=$this->uri->segment(6);
	  		$main_view=false;
	  		if(substr(decbin($data['permisos']), 2, 1)==1){
	  			$u=new Marca_producto();
	  			$u->get_by_id($id);
	  			$u->estatus_general_id=2;
	  			$u->save();
	  			redirect(base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion);
	  		} else {
	  			$main_view=true;
	  			//Definir la vista
	  			$data['principal']="error";
	  			$data['error_field']="No tiene permisos para deshabilitar la Marca de producto";
	  		}
	  	} else if($filtrado=="proveedor" && $id>0){
	  		$data['paginacion']=false;
	  		$data['marca_productos']=$this->marca_producto->get_marca_productos_list_proveedor($id);
	  		$data['cta']=$id;
	  		if($data['marca_productos']==false)
	  			$data['total_registros']=0;
	  		else
	  			$data['total_registros']=count($data['marca_productos']->all);
	  	} else  {
	  		//Definir la vista
	  		$data['principal']=$ruta."/".$subfuncion;
	  		$data['paginacion']=true;

	  		// load pagination class
	  		$this->load->library('pagination');
	  		$config['base_url'] = base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_marcas/";
	  		$config['per_page'] = '100';
	  		$page=$this->uri->segment(5);
	  		//Identificar el numero de pagina en el paginador si existe
	  		if($page>0)
	  			$offset=$page;
	  		else if ($page=='')
	  			$offset=0;
	  		else
	  			$offset=0;

	  		//$u1=new Marca_producto();
	  		$u1=$this->marca_producto->select("count(id) as total")->where("estatus_general_id",1)->get();
	  		$total_registros=$u1->total;
	  		$data['marca_productos']=$this->marca_producto->get_marca_productos_list($offset, $config['per_page']);
	  		$data['total_registros']=$total_registros;
	  		$config['total_rows'] = $total_registros;
	  		$this->pagination->initialize($config);
	  		$data['cta']=0;
	  	}
	  } //Final del Bloque Alta Marca
	  //Inicio del Bloque Alta Unidad de Medida
	  else if($subfuncion=="alta_unidad_m"){
	  	//Definir la vista
	  	$data['principal']=$ruta."/".$subfuncion;
		  //Obtener Datos
		  $data['validation']=$this->producto_validacion->validacion_unidad_m();

	  } else if($subfuncion=="list_unidades_m"){

	  	if($this->uri->segment(5)=="editar_unidad_m"){
	  		//Cargar los datos para el formulario
	  		$edit_usr=$this->uri->segment(5);
	  		$id=$this->uri->segment(6);
	  		//Definir la vista
	  		$data['principal']=$ruta."/editar_unidad_m";
	  		//Obtener Datos
	  		$data['validation']=$this->producto_validacion->validacion_unidad_m();
	  		$data['unidad']=$this->unidad_medida->get_unidad($id);

	  	} else if($this->uri->segment(5)=="borrar_unidad_m"){
	  		$id=$this->uri->segment(6);
	  		$main_view=false;
	  		if(substr(decbin($data['permisos']), 2, 1)==1){
	  			//Definir la vista
	  			$u=new Unidad_medida();
	  			$u->get_by_id($id);
	  			$u->estatus_general_id=2;
	  			$u->save();

	  			redirect(base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion);
	  		} else {
	  			$main_view=true;
	  			//Definir la vista
	  			$data['principal']="error";
	  			$data['error_field']="No tiene permisos para deshabilitar la Unidad de Medida";
	  		}


	  	} else  {

	  		//Definir la vista
	  		$data['principal']=$ruta."/".$subfuncion;
	  		//Obtener Datos
	  		// load pagination class
	  		$this->load->library('pagination');
	  		$config['base_url'] = base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_unidades_m/";
	  		$config['per_page'] = '10';
	  		$page=$this->uri->segment(5);
	  		//Identificar el numero de pagina en el paginador si existe
	  		if($page>0) {
	  			$offset=$page;
	  		} else if ($page==''){
	  			$offset=0;
	  		} else {
	  			$offset=0;
	  		}

	  		$u1=new Unidad_medida();
	  		$u1->get();
	  		$total_registros=$u1->c_rows;
	  		$data['unidades_medidas']=$this->unidad_medida->get_unidades_medidas_list($offset, $config['per_page']);
	  		$data['total_registros']=$total_registros;
	  		$config['total_rows'] = $total_registros;
	  		$this->pagination->initialize($config);

	  	}
	  } //Final del Bloque Alta Unidad de Medida

	  //Inicio del Bloque Alta Producto
	  else if($subfuncion=="alta_producto"){
	  	//Cargar los datos para el formulario
	  	$data['principal']=$ruta."/".$subfuncion;
	  	//Obtener Datos
		  $data['validation']=$this->producto_validacion->validacion_producto();
		  $data['familias']=$this->familia_producto->get_cproductos_familias();
		  $data['subfamilia_productos']=$this->subfamilia_producto->get_cproductos_subfamilias();
		  $data['colores_productos']=$this->color_producto->get_cproductos_colores();
		  $data['marca_productos']=$this->marca_producto->get_cmarcas_productos();
		  $data['unidades_medidas']=$this->unidad_medida->get_cunidades_medidas();
		  $data['estatus']=$this->estatus_general->get_estatus();
		  $data['producto']=false;
		  $data['combo']=0;
		  if($this->uri->segment(5)=="preload"){
		  	$data['producto']=$this->producto->get_producto($this->uri->segment(6));
		  }

	  }else if($subfuncion=="alta_producto_combo"){
	  	//Cargar los datos para el formulario
	  	  $data['principal']=$ruta."/".$subfuncion;
              $data['validation']=$this->producto_validacion->validacion_producto();
		  $data['familias']=$this->familia_producto->get_cproductos_familias();
		  $data['subfamilia_productos']=$this->subfamilia_producto->get_cproductos_subfamilias();
		  $data['colores_productos']=$this->color_producto->get_cproductos_colores();
		  $data['marca_productos']=$this->marca_producto->get_cmarcas_productos();
		  $data['unidades_medidas']=$this->unidad_medida->get_cunidades_medidas();
		  $data['estatus']=$this->estatus_general->get_estatus();
                  $data['rows'] = 10;
		  $data['producto']=false;
                  $data['combo']=1;
} 
else if($subfuncion=="list_productos"){
	  	$this->load->model('producto_numeracion');
	  	$data['pag']=1;
	  	$data['principal']=$ruta."/".$subfuncion;
	  	$filtrado=$this->uri->segment(6);
	  	$id=$this->uri->segment(7);
	  	if($this->uri->segment(5)=="editar_producto"){
	  		//Cargar los datos para el formulario
	  		$edit_usr=$this->uri->segment(5);
	  		$id=$this->uri->segment(6);
	  		//Definir el numero de frames
	  		$data['frames']=0;
	  		//Definir la vista
	  		$data['principal']=$ruta."/editar_producto";
	  		//Obtener Datos
	  		$data['validation']=$this->producto_validacion->validacion_producto();
	  		$data['familias']=$this->familia_producto->get_cproductos_familias();
	  		$data['subfamilia_productos']=$this->subfamilia_producto->get_cproductos_subfamilias();
	  		$data['materiales_productos']=$this->material_producto->get_cproductos_materiales();
	  		$data['colores_productos']=$this->color_producto->get_cproductos_colores();
	  		$data['marca_productos']=$this->marca_producto->get_cmarcas_productos();
	  		$data['unidades_medidas']=$this->unidad_medida->get_cunidades_medidas();
	  		$data['temporada_productos']=$this->temporada_producto->get_temporadas();
	  		$data['estatus']=$this->estatus_general->get_estatus();
	  		$data['producto']=$this->producto->get_producto($id);
	  		$data['numeracion']=$this->producto_numeracion->get_numeracion_producto($id);

	  	} else if($this->uri->segment(5)=="borrar_producto"){
	  		$id=$this->uri->segment(6);
	  		$main_view=false;
	  		if(substr(decbin($data['permisos']), 2, 1)==1){
	  			//Ubicar si existe pedido con ese producto
	  			$this->load->model("pr_detalle_pedido");
                                $this->load->model("entrada");
	  			$pedidos=$this->pr_detalle_pedido->get_detalle_by_cproducto($id);
                                $entradas=$this->entrada->get_entrada_by_cproducto($id);
                                if($entradas==false and $pedidos==false){
                                    	$u=new Producto();
					//$producto_num= new cproductos_numeracion();
					$u->get_by_id($id);
	  				$u->estatus_general_id=2;
				// $query = $this->db->query("select * from cproductos_numeracion where cproducto_id=$id");
        			//	$row = $query->row();
        			//	$cod_barras = $row->codigo_barras."0000";
	  				if($u->save()){
                                        $this->db->query("update cproductos_numeracion set codigo_barras='0' where cproducto_id=$id");
					redirect(base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion);
                                        }
	  			} else {
                                        $main_view=true;
	  				$data['principal']="error";
	  				$data['error_field']="El Producto no se puede deshabilidad debido a que aparece en pedidos y en entradas, por lo tanto sigue vigente";
	  			}
	  		} else {
	  			$main_view=true;
	  			//Definir la vista
	  			$data['principal']="error";
	  			$data['error_field']="No tiene permisos para deshabilitar el Producto";
	  		}

	  	} else if($this->uri->segment(5)=="subir_foto"){
	  		$id=$this->uri->segment(6);
	  		$main_view=false;
	  		if($id>0){
	  			$data['id']=$id;
	  			$data['producto']=$this->producto->get_by_id($id);
	  			//Mostrar formulario
	  			$this->load->view('almacen/subir_foto', $data);
	  			//redirect(base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion);
	  		} else {
	  			$main_view=true;
	  			//Definir la vista
	  			$data['principal']="error";
	  			$data['error_field']="No tiene permisos para deshabilitar el Producto";
	  		}

	  	} else if($filtrado=="buscar"){
                    $data['paginacion']=false;
                    $producto = new Producto();
                    $where="";
                    if(isset($_POST['cfamilia_id'])){
                        $familia=strtoupper($_POST['cfamilia_id']);
                        if($familia>0)
                        $where=$where." and p.cfamilia_id=".$familia;
                    }
                    if(isset($_POST['csubfamilia_id'])){
                        $subfamilia=strtoupper($_POST['csubfamilia_id']);
                        if($subfamilia>0)
                        $where=$where." and p.csubfamilia_id=".$subfamilia;
                    }
                    if(isset($_POST['cmarca_id'])){
                        $marca=strtoupper($_POST['cmarca_id']);
                        if($marca>0)
                        $where=$where." and p.cmarca_producto_id=".$marca;
                    }
                    if(isset($_POST['descripcion'])){
                        $descripcion=strtoupper($_POST['descripcion']);
                        if($descripcion!="")
                        $where=$where." and p.descripcion like '%".$descripcion."%'";
                    }
                    if(isset($_POST['cod_bar'])){
                        $where=$where." and pnu.codigo_barras = ''";
                    }
                    if(isset($_POST['descontinuado'])){
                        $where=$where." and p.status = '0'";
                    }else{
                        $where=$where." and (p.status = '1' or p.status isnull)";
                    }
                    if(isset($_POST['img'])){
                        $where=$where." and p.ruta_foto isnull";
                    }
		    if(isset($_POST['codigo_barras'])){
                        $codigo=$_POST['codigo_barras'];
                        //Limpia el where para buscar solo por codigo de barras
                        if($codigo!="")
                        $where=" and pnu.codigo_barras = '".$codigo."'";
                    }
                    $sql = "select distinct(p.id),p.* , mp.tag as marca_producto, psf.tag as subfamilia_producto, eg.tag as estatus, pf.tag as familia_producto, pc.tag as color, cmp.tag as marca from cproductos as p left join cmarcas_productos as mp on mp.id=p.cmarca_producto_id left join cproductos_subfamilias as psf on psf.id=p.csubfamilia_id left join cproductos_familias as pf on pf.id=p.cfamilia_id left join cproductos_color as pc on pc.id=p.ccolor_id left join cmarcas_productos as cmp on cmp.id=p.cmarca_producto_id left join estatus_general as eg on eg.id=p.estatus_general_id left join cproductos_numeracion as pnu on pnu.cproducto_id=p.id "
		    ." where p.estatus_general_id='1' and p.combo='0' ".$where." order by p.descripcion";
                    $data['productos']=$producto->query($sql);
                    $data['total_registros']=count($data['productos']->all);
                    $data['offset']=0;

	  	}  else  {
                    	$data['paginacion']=true;
	  		$data['principal']=$ruta."/".$subfuncion;
	  		//Obtener Datos
	  		// load pagination class
	  		$this->load->library('pagination');
	  		$config['base_url'] = base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_productos/";
	  		$config['per_page'] = '50';
	  		$page=$this->uri->segment(5);
	  		//Identificar el numero de pagina en el paginador si existe
	  		if($page>0) {
	  			$offset=$page;
	  		} else if ($page==''){
	  			$offset=0;
	  		} else {
	  			$offset=0;
	  		}


	  		$data['productos']=$this->producto->get_productos_list($offset, $config['per_page']);
                        $total_registros=$this->producto->get_cproductos_count();
	  		$data['total_registros']=$total_registros;
	  		$data['offset']=$offset;
                        $config['total_rows'] = $total_registros;
	  		$this->pagination->initialize($config);
	  		$data['cta']=0;

	  	}
	  } //Final del Bloque Alta Producto

          
          
          
          
          else if($subfuncion=="list_productos_combo"){
	  	$this->load->model('producto_numeracion');
	  	$data['pag']=1;
	  	$data['principal']=$ruta."/".$subfuncion;
	  	$filtrado=$this->uri->segment(6);
	  	$id=$this->uri->segment(7);
	  	if($this->uri->segment(5)=="editar_producto"){
	  		//Cargar los datos para el formulario
	  		$edit_usr=$this->uri->segment(5);
	  		$id=$this->uri->segment(6);
	  		//Definir el numero de frames
	  		$data['frames']=0;
	  		//Definir la vista
	  		$data['principal']=$ruta."/editar_producto_combo";
	  		//Obtener Datos
	  		$data['validation']=$this->producto_validacion->validacion_producto();
	  		$data['familias']=$this->familia_producto->get_cproductos_familias();
	  		$data['subfamilia_productos']=$this->subfamilia_producto->get_cproductos_subfamilias();
	  		$data['materiales_productos']=$this->material_producto->get_cproductos_materiales();
	  		$data['colores_productos']=$this->color_producto->get_cproductos_colores();
	  		$data['marca_productos']=$this->marca_producto->get_cmarcas_productos();
	  		$data['unidades_medidas']=$this->unidad_medida->get_cunidades_medidas();
	  		$data['temporada_productos']=$this->temporada_producto->get_temporadas();
	  		$data['estatus']=$this->estatus_general->get_estatus();
	  		$data['producto']=$this->producto->get_producto($id);
	  		$data['numeracion']=$this->producto_numeracion->get_numeracion_producto($id);
                         $data['rows']=15;
			$data['rowt']=15;
                     $u=new Entrada();
                    //$sql="select e.id, e.cproducto_id_combo as id_combo, e.cproducto_numeracion_id_combo as numeracion_combo, p.descripcion as producto_nombre, n.numero_mm, n.codigo_barras as cod, e.cproducto_id_relacion as relacion_id, e.cproducto_numeracion_id_relacion as relacion_numeracion from cproductos_combo as e join cproductos_numeracion as n on n.cproducto_id = e.cproducto_id_relacion and n.id=e.cproducto_numeracion_id_relacion join cproductos as p on p.id = e.cproducto_id_relacion where  e.estatus_general_id='1' and p.relacion='1' and e.cproducto_id_combo=$id";   
                     // $sql="select * from entradas where pr_facturas_id=$id";   
					$sql="select e.id,e.semejante_producto_combo as semejante, e.cproducto_id_combo as id_combo, e.cproducto_numeracion_id_combo as numeracion_combo, p.descripcion as producto_nombre, n.numero_mm, n.codigo_barras as cod, e.cproducto_id_relacion as relacion_id, e.cproducto_numeracion_id_relacion as relacion_numeracion
					from cproductos_combo as e
					join cproductos_numeracion as n on n.cproducto_id = e.cproducto_id_relacion and n.id=e.cproducto_numeracion_id_relacion
					join cproductos as p on p.id = e.cproducto_id_relacion
					where 
					e.estatus_general_id='1'
					and
					p.relacion='1'
					and
					e.cproducto_id_combo=$id";
                    $data['entra']=$u->query($sql);

	  	} else if($this->uri->segment(5)=="borrar_producto"){
	  		$id=$this->uri->segment(6);
	  		$main_view=false;
	  		if(substr(decbin($data['permisos']), 2, 1)==1){
	  			//Ubicar si existe pedido con ese producto
	  			$this->load->model("pr_detalle_pedido");
                                $this->load->model("entrada");
	  			$pedidos=$this->pr_detalle_pedido->get_detalle_by_cproducto($id);
                                $entradas=$this->entrada->get_entrada_by_cproducto($id);
                                if($entradas==false and $pedidos==false){
                                    	$u=new Producto();
                                        $u->get_by_id($id);
	  				$u->estatus_general_id=2;
				
				 //$query = $this->db->query("select * from cproductos_numeracion where cproducto_id=$id");
        			//	$row = $query->row();
        			//	$cod_barras = $row->codigo_barras."0000";
	  				if($u->save()){
					 $this->db->query("update cproductos_numeracion set codigo_barras='0' where cproducto_id=$id");
	  				redirect(base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion);
					}
	  			} else {
                                        $main_view=true;
	  				$data['principal']="error";
	  				$data['error_field']="El Producto no se puede deshabilidad debido a que aparece en pedidos y en entradas, por lo tanto sigue vigente";
	  			}
	  		} else {
	  			$main_view=true;
	  			//Definir la vista
	  			$data['principal']="error";
	  			$data['error_field']="No tiene permisos para deshabilitar el Producto";
	  		}

	  	} else if($this->uri->segment(5)=="subir_foto"){
	  		$id=$this->uri->segment(6);
	  		$main_view=false;
	  		if($id>0){
	  			$data['id']=$id;
	  			$data['producto']=$this->producto->get_by_id($id);
	  			//Mostrar formulario
	  			$this->load->view('almacen/subir_foto', $data);
	  			//redirect(base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion);
	  		} else {
	  			$main_view=true;
	  			//Definir la vista
	  			$data['principal']="error";
	  			$data['error_field']="No tiene permisos para deshabilitar el Producto";
	  		}

	  	} else if($filtrado=="buscar"){
                    $data['paginacion']=false;
                    $producto = new Producto();
                    $where="";
                    if(isset($_POST['cfamilia_id'])){
                        $familia=strtoupper($_POST['cfamilia_id']);
                        if($familia>0)
                        $where=$where." and p.cfamilia_id=".$familia;
                    }
                    if(isset($_POST['csubfamilia_id'])){
                        $subfamilia=strtoupper($_POST['csubfamilia_id']);
                        if($subfamilia>0)
                        $where=$where." and p.csubfamilia_id=".$subfamilia;
                    }
                    if(isset($_POST['cmarca_id'])){
                        $marca=strtoupper($_POST['cmarca_id']);
                        if($marca>0)
                        $where=$where." and p.cmarca_producto_id=".$marca;
                    }
                    if(isset($_POST['descripcion'])){
                        $descripcion=strtoupper($_POST['descripcion']);
                        if($descripcion!="")
                        $where=$where." and p.descripcion like '%".$descripcion."%'";
                    }
                    if(isset($_POST['cod_bar'])){
                        $where=$where." and pnu.codigo_barras = ''";
                    }
                    if(isset($_POST['descontinuado'])){
                        $where=$where." and p.status = '0'";
                    }else{
                        $where=$where." and (p.status = '1' or p.status isnull)";
                    }
                    if(isset($_POST['img'])){
                        $where=$where." and p.ruta_foto isnull";
                    }
		    if(isset($_POST['codigo_barras'])){
                        $codigo=$_POST['codigo_barras'];
                        //Limpia el where para buscar solo por codigo de barras
                        if($codigo!="")
                        $where=" and pnu.codigo_barras = '".$codigo."'";
                    }
                    $sql = "select distinct(p.id),p.* , mp.tag as marca_producto, psf.tag as subfamilia_producto, eg.tag as estatus, pf.tag as familia_producto, pc.tag as color, cmp.tag as marca from cproductos as p left join cmarcas_productos as mp on mp.id=p.cmarca_producto_id left join cproductos_subfamilias as psf on psf.id=p.csubfamilia_id left join cproductos_familias as pf on pf.id=p.cfamilia_id left join cproductos_color as pc on pc.id=p.ccolor_id left join cmarcas_productos as cmp on cmp.id=p.cmarca_producto_id left join estatus_general as eg on eg.id=p.estatus_general_id left join cproductos_numeracion as pnu on pnu.cproducto_id=p.id "
		    ." where p.estatus_general_id='1' and p.combo='1'".$where." order by p.descripcion";
                    $data['productos']=$producto->query($sql);
                    $data['total_registros']=count($data['productos']->all);
                    $data['offset']=0;

	  	}  else  {
                    	$data['paginacion']=true;
	  		$data['principal']=$ruta."/".$subfuncion;
	  		//Obtener Datos
	  		// load pagination class
	  		$this->load->library('pagination');
	  		$config['base_url'] = base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_productos_combo/";
	  		$config['per_page'] = '50';
	  		$page=$this->uri->segment(5);
	  		//Identificar el numero de pagina en el paginador si existe
	  		if($page>0) {
	  			$offset=$page;
	  		} else if ($page==''){
	  			$offset=0;
	  		} else {
	  			$offset=0;
	  		}

	  		$data['productos']=$this->producto->get_productos_list_combo($offset, $config['per_page']);
                        $total_registros=$this->producto->get_cproductos_combo_count();
	  		$data['total_registros']=$total_registros;
	  		$data['offset']=$offset;
                        $config['total_rows'] = $total_registros;
	  		$this->pagination->initialize($config);
	  		$data['cta']=0;

	  	}
	  } //Final del Bloque Alta Producto


          
          
          
	  //Inicio del Bloque Materiales
	  else if($subfuncion=="alta_material"){
	  	//Definir la vista
	  	$data['principal']=$ruta."/".$subfuncion;
	  	//Obtener Datos
		  $data['validation']=$this->producto_validacion->validacion_material();
		  $data['estatus']=$this->estatus_general->get_estatus();

	  } else if($subfuncion=="list_materiales"){

	  	if($this->uri->segment(5)=="editar_material"){
	  		//Cargar los datos para el formulario
	  		$edit_usr=$this->uri->segment(5);
	  		$id=$this->uri->segment(6);
	  		//Definir la vista
	  		$data['principal']=$ruta."/editar_material";
	  		//Obtener Datos
	  		$data['validation']=$this->producto_validacion->validacion_material();
	  		$data['material']=$this->material_producto->get_material($id);
	  	} else if($this->uri->segment(5)=="borrar_material"){
	  		$id=$this->uri->segment(6);
	  		$main_view=false;
	  		if(substr(decbin($data['permisos']), 2, 1)==1){
	  			$u=new Material_producto();
	  			$u->get_by_id($id);
	  			$u->estatus_general_id=2;
	  			$u->save();

	  			redirect(base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion);
	  		} else {
	  			$main_view=true;
	  			//Definir la vista
	  			$data['principal']="error";
	  			$data['error_field']="No tiene permisos para deshabilitar el Material de producto";
	  		}
	  	} else  {
	  		//Definir la vista
	  		$data['principal']=$ruta."/".$subfuncion;
	  		// load pagination class
	  		$this->load->library('pagination');
	  		$config['base_url'] = base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_materiales/";
	  		$config['per_page'] = '100';
	  		$page=$this->uri->segment(5);
	  		//Identificar el numero de pagina en el paginador si existe
	  		if($page>0)
	  			$offset=$page;
	  		else if ($page=='')
	  			$offset=0;
	  		else
	  			$offset=0;

	  		$u1=new Material_producto();
	  		$u1->get();
	  		$total_registros=$u1->c_rows;
	  		$data['materiales']=$this->material_producto->get_materiales_list($offset, $config['per_page']);
	  		$data['total_registros']=$total_registros;
	  		$config['total_rows'] = $total_registros;
	  		$this->pagination->initialize($config);
	  	}
	  } //Final del Bloque Materiales
	  else if ($subfuncion == "alta_color") {
	  	//definir la vista
	  	$data['principal'] = $ruta . "/" . $subfuncion;
	  	//Obtener  Datos
	  	$data['validation'] = $this->producto_validacion->validacion_color();
	  	$data['estatus'] = $this->estatus_general->get_estatus();
	  } else if ($subfuncion == "list_colores") {

	  	if ($this->uri->segment(5) == "editar_color") {
	  		$edit_usr = $this->uri->segment(5);
	  		$id = $this->uri->segment(6);
	  		$color = new Color_producto($id);
	  		$data['color'] = $color;
	  		$data['usuariop'] = new Usuario($color->usuario_id);

	  		//Definir la vista
	  		$data['principal'] = $ruta . "/editar_color";
	  		$data['rows_pred'] = '0';
	  		$data['validation'] = $this->producto_validacion->validacion_color();
	  		$data['estatus'] = $this->estatus_general->get_estatus();


	  	} else if ($this->uri->segment(5) == "borrar_color") {
	  		$main_view = false;
	  		$id = $this->uri->segment(6);
	  		if (substr(decbin($data['permisos']), 2, 1) == 1) {
	  			$this->color_producto->cancelar_color($id);
	  			redirect(base_url() . "index.php/" . $ruta . "/" . $controller . "/" . $funcion . "/" . $subfuncion);
	  		} else {
	  			$main_view = true;
	  			//Definir la vista
	  			$data['principal'] = "error";
	  			$data['error_field'] = "No tiene permisos para deshabilitar el color";
	  		}
	  	} else {

	  		$data['principal'] = $ruta . "/" . $subfuncion;
	  		$this->load->library('pagination');
	  		$config['base_url'] = base_url() . "index.php/" . $ruta . "/" . $controller . "/" . $funcion . "/list_colores/";
	  		$config['per_page'] = '60';
	  		$page = $this->uri->segment(5);

	  		//Identificar el numero de pagina en el paginador si existe
	  		if ($page > 0)
	  			$offset = $page;
	  		else if ($page == '')
	  			$offset = 0;
	  		else
	  			$offset=0;

	  		$u1 = $this->color_producto->get_colores_list($config['per_page'], $page);
	  		if ($u1 == false)
	  			show_error("No hay colores registrados");
	  		$total_registros = $this->color_producto->get_colores_count();
	  		$data['colores'] = $u1->limit(1, 2);
	  		$data['total_registros'] = $total_registros;
	  		$config['total_rows'] = $total_registros;
	  		$this->pagination->initialize($config);
	  	}
	  }


	  if($main_view){
	  	//Llamar a la vista
	  	$this->load->view("ingreso", $data);
	  	unset($data);
	  } else {
	   // redirect(base_url()."index.php/inicio/logout");
	  }
		}

	}
}
?>
