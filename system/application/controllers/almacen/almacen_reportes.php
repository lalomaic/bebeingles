<?php
class Almacen_reportes extends Controller {
	//var $usuarioid, $username, $ruta, $controller, $funcion, $subfuncion, $modulos_totales;

	function Almacen_reportes()
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
		$this->assetlibpro->add_js('jquery.selectboxes.js');
		// 	  $this->assetlibpro->add_js('jquery.select-autocomplete.js');
		$this->assetlibpro->add_js('jquery.numberformatter.js');
		$this->assetlibpro->add_js('jquery.AddIncSearch.js');
		$this->assetlibpro->add_js('jquery.validator.js');
		$this->assetlibpro->add_js('jquery.date.js');
		$this->assetlibpro->add_js('jquery.form.js');
		$this->assetlibpro->add_js('fancybox/jquery.mousewheel-3.0.2.pack.js');
		$this->assetlibpro->add_js('fancybox/jquery.fancybox-1.3.1.js');
		$this->load->model("espacio_fisico");
		$this->load->model("familia_producto");
		$this->load->model("subfamilia_producto");
		$this->load->model("usuario_accion");
		$this->load->model("almacen");
		$this->load->model("cl_pedido");
		$this->load->model("cl_detalle_pedido");
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
	
	function rep_devolucion_proveedor(){
        $id = $this->uri->segment(4);
        $this->load->model("devolucion_proveedor");
        $dp = new Devolucion_proveedor();
        $dp->include_related('proveedor','razon_social');
        $data['devolucion_proveedor'] = $dp->get_by_id($id);

        $this->load->model("salida");
        $s = new Salida();
        $s->include_related("cproducto_numero","numero_mm");
        $s->include_related('cproductos','descripcion');
        $s->where("devolucion_proveedor_id", $id);
        $data['salidas'] = $s->get();
        $this->load->view("almacen/ajustes_reportes/rep_devolucion_proveedor", $data);
}

	  function rep_inventario_pdf(){
           global $ruta;
		$data['title']=$this->accion->get_title("{$_POST['subfuncion']}");
		//Obtener los datos
		$empresas=$_POST['empresas'];
		$espacios=$_POST['espacios'];
		$familia=$_POST['cfamilia_id'];
		$subfamilia=$_POST['csubfamilia_id'];
		
		if($empresas==0){
			$where=" where e1.estatus_general_id=1";
			$data['empresa']="Todos";
		} else {
			$where=" where e.id='$empresas' ";
			$e1=$this->empresa->get_empresa($empresas);
			$data['empresa']=$e1->razon_social;
		}
		
		if($espacios==0){
			$data['tienda']="TODAS";
                        $data['telefono']='';
                        $data['calle']='';
                        $data['colonia']='';
                        $data['rfc']='';
                        $data['razon_social']='';
                        $dat['cp']='';
                        $data['num_exterior']='';
                        $data['estado']='';
		} else {
			$where .=" and ef.id='$espacios' ";
			$ef=$this->espacio_fisico->get_espacio_f($espacios);
			$data['tienda']=$ef->tag;
                        $data['telefono']=$ef->telefono;
                        $data['calle']=$ef->calle;
                        $data['colonia']=$ef->colonia;
                        $data['rfc']=$ef->rfc;
                        $data['razon_social']=$ef->razon_social;
                        $data['codigo']=$ef->codigo_postal;
                        $data['num_exterior']=$ef->numero_exterior;
                        $data['estado']=$ef->estado;
                        $data['localidad']=$ef->localidad;
		}
		$where1="where p.id>='0'";
		if($familia==0){
			$data['familia']="Todos";
		} else {
			$where1 .=" and p.cfamilia_id='$familia' ";
			$fam=$this->familia_producto->get_familia($familia);
			$data['familia']=$fam->tag;
		}

		if($subfamilia==0){
			$data['subfamilia']="Todos";
		} else {
			$where1 .=" and p.csubfamilia_id='$subfamilia'";
			$sfam=$this->subfamilia_producto->get_subfamilia($subfamilia);
			$data['subfamilia']=$sfam->tag;
		}
		//Fecha
		if(isset($_POST['fecha'])==false) {
			$fecha1=date("Y-m-d H:i:s");
		} else {
			$fecha=explode(" ", $_POST['fecha']);
			$fecha1=$fecha[2]."-".$fecha[1]."-".$fecha[0]. " ".$_POST['hora'].":".$_POST['min'].":00";
		}
		$where .=" and fecha<='$fecha1'";
		$data['fecha']=$fecha1;
		$nivel1=$_POST['nivel1'];
		
		$order_clause=" order by ";
		$value="";
		$ingreso=0;
		for($x=0;$x<4;$x++){
			if($x==1){
				$value=$nivel1;
				$ingreso+=1;
			} else if($value ==1){
				$order_clause.="empresa,";
			} else if($value ==2){
				$order_clause.="espacio_fisico,";
			} else if($value ==3){
				$order_clause.="producto,";
			} else if($value ==0){
				//$order_clause.="f.generalid,";
			}
		}
		if($ingreso>0){
			$order_by=substr($order_clause, 0,-1);
		} else {
			$order_by="";
		}
		$data['inventario']=$this->almacen->inventario($where, $order_by, $where1);
                //$data['con']=$this->almacen->inventario($where, $order_by, $where1);
		$this->load->view("$ruta/rep_inventario_pdf", $data);
		unset($data);
		//Enviarselo al view para general el PDF
	}

    //Cargar la vista base con el menu
    function load_main_view($data){
        global $usuarioid, $username, $modulos_totales, $main_menu, $ruta;
        $data['modulos_totales']=$modulos_totales;
        $data['usuarioid']=$usuarioid;		
        $data['username']=$username;		
        $data['colect1']=$main_menu;
        $data['ruta']=$ruta;		
        $accion_id=$this->accion->get_id($data["subfuncion"]);
        $row=$this->usuario->get_usuario($usuarioid);
        $grupoid=$row->grupo_id;
        $puestoid=$row->puesto_id;        
        $data['permisos']=$this->usuario_accion->get_permiso($accion_id, $usuarioid, $puestoid, $grupoid);
        if(is_array($data['colect1']) 
                and $usuarioid > 0 
                and $data['permisos'] != false 
                and $this->session->userdata('logged_in') == TRUE){
            $this->load->view("ingreso", $data);
        } else{
            redirect(base_url()."index.php/inicio/logout");		
        }        
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
			if ($subfuncion=="rep_productos"){
				//Cargar los datos para el formulario
				$data['frames']=1;
				$data['principal']=$ruta."/".$subfuncion;
				$data['funcion']=$subfuncion;
				$f=new Familia_producto();
				$sf=new Subfamilia_producto();
				$p=new Producto();
				$m=new Marca_producto();
				$data['familias']=$f->select('id,tag,clave')->get();
				if(count($data['familias'])==0)
					show_error('No existen familias');
				$data['subfamilias']=$sf->select('id,tag')->order_by('tag')->get();
				if(count($data['subfamilias'])==0)
					show_error('No existen subfamilias');
				$data['marcas']=$m->select('id,tag')->get();
				if(count($data['marcas'])==0)
					show_error('No existen marcas');
				//$this->load->view("almacen/rep_productos", $data);
			}
			else if($subfuncion == 'rep_unidades_m') {
				$u=new Unidad_medida();
				$u->get();
				$data['unidades']=$u->all;
				$this->load->view("almacen/rep_unidades_m_pdf", $data);

			} else if($subfuncion == 'rep_traspasos_tiendas') {
				$data['espacios']=$this->espacio_fisico->get_espacios_tiendas();
				$data['frames']=1;
				$data['principal']=$ruta."/".$subfuncion;

			} else if($subfuncion == 'rep_marcas') {
				// Formulario del Reporte de Marcas
				$data['frames']=1;
				$data['principal']=$ruta."/".$subfuncion;
				$data['funcion']=$subfuncion;
				$pv=new Proveedor();
				$pv->select('id,razon_social')->order_by('razon_social')->get();
				if(!$pv->exists()) show_error('No hay proveedores');
				$data['proveedores']=$pv->all;
				//$this->load->view("almacen/rep_marcas", $data);
				/*				$m=new Marca_producto();
				 $m->select('id,tag')->get();
				$data['unidades']=$m->all;*/
			} else if($subfuncion == 'rep_subfamilias')
			{
				$f=new Familia_producto();
				$f->select('id,clave')->get();
				$data['familias']=array();
				foreach($f->all as $fam)
				{
					$data['familias'][$fam->id]=$fam->clave;
				}
				$s=new Subfamilia_producto();
				$s->select('id, cproducto_familia_id, tag, clave, fecha_alta')->order_by('cproducto_familia_id,tag')->get();
				$data['subfamilias']=$s->all;
				$this->load->view("almacen/rep_subfamilias_pdf", $data);
			}
			else if($subfuncion == 'rep_familias')
			{
				$f=new Familia_producto();
				$f->select('id,tag,clave')->get();
				$data['familias']=$f->all;
				$this->load->view("almacen/rep_familias_pdf", $data);
			}
			else if($subfuncion == 'rep_tipos_salidas')
			{
				$t=new Tipo_salida();
				$t->select('id,tag')->get();
				$data['tipos']=$t->all;
				$this->load->view("almacen/rep_tipos_salidas_pdf", $data);
			}
			else if($subfuncion == 'rep_tipos_entradas')
			{
				$t=new Tipo_entrada();
				$t->select('id,tag')->get();
				$data['tipos']=$t->all;
				$this->load->view("almacen/rep_tipos_salidas_pdf", $data);
			} else if ($subfuncion == 'rep_existencias') {
				$this->assetlibpro->add_js('jquery.select-autocomplete.js');
				$data['principal'] = "$ruta/$subfuncion";
				$data['funcion'] = $subfuncion;
				$this->load->model('espacio_fisico');
				$data['espacios'] = $this->espacio_fisico->get_espacios_as_array();
				$data['espacios'][0] = "TODAS";
			}
			else if ($subfuncion=="rep_entradas"){
				//Cargar los datos para el formulario
				//Definir el numero de frames
				$data['frames']=1;
				//Definir la vista
				$data['principal']=$ruta."/".$subfuncion;
				$data['funcion']=$subfuncion;
				//Obtener Datos
				$t=new Tipo_entrada();
				$t->select('id,tag')->order_by('tag')->get();
				if(!$t->exists()) show_error('No hay tipos de entrada');
				$ef=new Espacio_fisico();
				$ef->select('id,tag')->order_by('tag')->get();
				if(!$t->exists()) show_error('No hay espacios fisicos');
				$pv=new Proveedor();
				$pv->select('id,razon_social')->order_by('razon_social')->get();
				if(!$t->exists()) show_error('No hay proveedores');
				$p=new Producto();
				$p->select('id,descripcion')->order_by('descripcion')->get();
				if(!$t->exists()) show_error('No hay productos');
				$data['tentradas']=$t->all;
				$data['espaciosf']=$ef->all;
				$data['proveedores']=$pv->all;
				$data['productos']=$p->all;
			}
			else if ($subfuncion=="rep_salidas"){
				//Cargar los datos para el formulario
				//Definir el numero de frames
				$data['frames']=1;
				//Definir la vista
				$data['principal']=$ruta."/".$subfuncion;
				$data['funcion']=$subfuncion;
				//Obtener Datos
				$t=new Tipo_salida();
				$t->select('id,tag')->order_by('tag')->get();
				if(!$t->exists()) show_error('No hay tipos de entrada');
				$ef=new Espacio_fisico();
				$ef->select('id,tag')->order_by('tag')->get();
				if(!$t->exists()) show_error('No hay espacios fisicos');
				$pv=new Cliente();
				$pv->select('id,razon_social')->order_by('razon_social')->get();
				if(!$t->exists()) show_error('No hay proveedores');
				$p=new Producto();
				$p->select('id,descripcion')->order_by('descripcion')->get();
				if(!$t->exists()) show_error('No hay productos');
				$data['tsalidas']=$t->all;
				$data['espaciosf']=$ef->all;
				$data['clientes']=$pv->all;
				$data['productos']=$p->all;
			} else if ($subfuncion=="rep_ejecutivo_salidas"){
				//Cargar los datos para el formulario
				//Definir el numero de frames
				$data['frames']=1;
				//Definir la vista
				$data['principal']=$ruta."/".$subfuncion;
				$data['funcion']=$subfuncion;
				//Obtener Datos
				$t=new Tipo_salida();
				$t->select('id,tag')->order_by('tag')->get();
				if(!$t->exists()) show_error('No hay tipos de entrada');
				$ef=new Espacio_fisico();
				$ef->select('id,tag')->order_by('tag')->get();
				if(!$t->exists()) show_error('No hay espacios fisicos');
				$pv=new Cliente();
				$pv->select('id,razon_social')->order_by('razon_social')->get();
				if(!$t->exists()) show_error('No hay proveedores');
				$f=new Familia_producto();
				$data['familias']=$f->select('id,tag,clave')->get()->all;
				if(count($data['familias'])==0)
					show_error('No existen familias');
				$data['tsalidas']=$t->all;
				$data['espaciosf']=$ef->all;
				$data['clientes']=$pv->all;
			} else if ($subfuncion=="rep_ejecutivo_salidas_cantidad") {
				//Cargar los datos para el formulario
				//Definir el numero de frames
				$data['frames']=1;
				//Definir la vista
				$data['principal']=$ruta."/".$subfuncion;
				$data['funcion']=$subfuncion;
				//Obtener Datos
				$t=new Tipo_salida();
				$t->select('id,tag')->order_by('tag')->get();
				if(!$t->exists()) show_error('No hay tipos de entrada');
				$ef=new Espacio_fisico();
				$ef->select('id,tag')->order_by('tag')->get();
				if(!$t->exists()) show_error('No hay espacios fisicos');
				$pv=new Cliente();
				$pv->select('id,razon_social')->order_by('razon_social')->get();
				if(!$t->exists()) show_error('No hay proveedores');
				$f=new Familia_producto();
				$data['familias']=$f->select('id,tag,clave')->get()->all;
				if(count($data['familias'])==0)
					show_error('No existen familias');
				$data['tsalidas']=$t->all;
				$data['espaciosf']=$ef->all;
				$data['clientes']=$pv->all;
			} else if ($subfuncion=="rep_inventario"){
				$data['frames']=1;
				//Definir la vista
				$data['principal']=$ruta."/".$subfuncion;
				//Obtener Datos
				$data['empresas']=$this->empresa->get_empresas();
				$data['espacios_fisicos']=$this->espacio_fisico->get_espacios_tiendas_almacenes_mtrx();
				$data['tipo'][0]="TODOS";
				$data['tipo'][1]="ALMACENES";
				$data['tipo'][2]="TIENDAS";
				$data['familias']=$this->familia_producto->get_cproductos_familias();
                                $data['subfamilia_productos']=$this->subfamilia_producto->get_cproductos_subfamilias();
			} else if ($subfuncion=="rep_inventario_talla"){
				$data['frames']=1;
				//Definir la vista
				$data['principal']=$ruta."/".$subfuncion;
				//Obtener Datos
				$data['empresas']=$this->empresa->get_empresas();
				$data['espacios_fisicos']=$this->espacio_fisico->get_espacios_tiendas_almacenes_mtrx();
				$data['tipo'][0]="TODOS";
				$data['tipo'][1]="ALMACENES";
				$data['tipo'][2]="TIENDAS";
				$data['familias']=$this->familia_producto->get_familias_mtrx();
				$data['subfamilias']=$this->subfamilia_producto->get_subfamilias_mtrx();
			} else if ($subfuncion=="rep_inventario_ejecutivo"){
				//Cargar los datos para el formulario
				//Definir el numero de frames
				$data['frames']=1;
				//Definir la vista
				$data['principal']=$ruta."/".$subfuncion;
				//Obtener Datos
				$data['espacios_fisicos']=$this->espacio_fisico->get_espacios_tiendas();

			}  else if($subfuncion=='rep_colores'){
				$c=new Color_producto();
				$c->order_by("tag","asc");
				$c->get();
				$data['unidades']=$c->all;
				$this->load->view("almacen/rep_colores_pdf", $data);
			} else if ($subfuncion == 'rep_kardex') {
				$this->assetlibpro->add_js('jquery.select-autocomplete.js');
				$data['principal'] = "$ruta/$subfuncion";
				$data['funcion'] = $subfuncion;
				$this->load->model('espacio_fisico');
				$data['espacios'] = $this->espacio_fisico-> get_espacios_tiendas_mtrx();
				$this->load->model('tipo_entrada');
				$data['entradas'] = $this->tipo_entrada->get_tipos_entrada_dropd();
				$this->load->model('tipo_salida');
				$data['salidas'] = $this->tipo_salida->get_tipos_salida_dropd();
				$data['salidas'][0] = $data['entradas'][0] = $data['espacios'][0] = "TODAS";
			} elseif($subfuncion=='rep_kardex_ejecutivo')
			{
				//Cargar los datos para el formulario
				$data['frames']=1;
				$data['principal']=$ruta."/".$subfuncion;
				$data['funcion']=$subfuncion;
				$e=new Espacio_fisico();
				$f=new Familia_producto();
				$sf=new Subfamilia_producto();
				$p=new Producto();
				$te=new Tipo_Entrada();
				$ts=new Tipo_Salida();
				$data['tipo_s']=$ts->select('id,tag')->get()->all;
				if(count($data['tipo_s'])==0) show_error('No existen tipos de salidas');
				$data['tipo_e']=$te->select('id,tag')->get()->all;
				if(count($data['tipo_e'])==0) show_error('No existen tipos de entradas');
				$data['espacios']=$e->select('id,tag')->get()->all;
				if(count($data['espacios'])==0)
					show_error('No existen espacios fisicos');
				$data['familias']=$f->select('id,tag,clave')->get()->all;
				if(count($data['familias'])==0)
					show_error('No existen familias');
				$data['subfamilias']=$sf->select('id,tag,cproducto_familia_id')->order_by('cproducto_familia_id,tag')->get()->all;
				if(count($data['subfamilias'])==0)
					show_error('No existen subfamilias');
				$data['productos']=$p->select('id,descripcion')->get()->all;
				if(count($data['productos'])==0)
					show_error('No existen productos');
			}
			if($main_view){
		  //Llamar a la vista
		  $this->load->view("ingreso", $data);
			} else {
		  redirect(base_url()."index.php/inicio/logout");
			}
		}
	} // end method formulario ##################

	function rep_productos_pdf()
	{ // BEGIN method rep_productos_pdf
		global $ruta;
		//Obtener los datos
		//echo "<pre>";var_dump($_POST);echo "</pre>";exit();
		$filtro=array();
		if((int)$_POST['familia']>0)
			$filtro[]="p.cfamilia_id=".(int)$_POST['familia'];
		if((int)$_POST['subfamilia']>0)
			$filtro[]="p.csubfamilia_id=".(int)$_POST['subfamilia'];
		if((int)$_POST['marca']>0)
			$filtro[]="p.cmarca_producto_id=".(int)$_POST['marca'];
		if (count($filtro))
			$where=" where ".implode(" and ", $filtro)." ";
		else
			$where="";
		$nivel=array();
		$nivel[]=(int)$_POST['nivel_1'];
		$nivel[]=(int)$_POST['nivel_2'];
		$nivel[]=(int)$_POST['nivel_3'];
		$nivel[]=(int)$_POST['nivel_4'];
		$campos=array();
		foreach ($nivel as $my_key=>$my_value){
			switch($my_value){
				case 1: $campos[]='familia'; break;
				case 2: $campos[]='subfamilia'; break;
				case 3: $campos[]='marca'; break;
				case 4: $campos[]='descripcion'; break;
			}
		}
		if (count($campos))
			$order_by=" order by ".implode(",", $campos)." ";
		else
			$order_by="";
		$data['title']=$_POST['title'];
		//
		$sql="SELECT p.id, p.descripcion, m.tag AS marca, f.tag AS familia, s.tag AS subfamilia, p.precio1, p.precio2, p.precio3, p.precio4, p.precio5, p.cunidad_medida_id AS medida, p.observaciones, p.tasa_impuesto, p.comision_venta,pm.tag as material  FROM cproductos AS p LEFT JOIN cmarcas_productos AS m ON m.id=p.cmarca_producto_id LEFT JOIN cproductos_familias AS f ON f.id=p.cfamilia_id LEFT JOIN cproductos_subfamilias AS s ON s.id=p.csubfamilia_id left join cproductos_material as pm on pm.id=p.cmaterial_id $where $order_by";
		$query=$this->db->query($sql);
		$res=$query->result();
		if(count($res)==0) show_error('No hay registros que cumplan los criterios.');
		$data['productos']=$res;
		if(!$data['productos'])
			show_error('No hay registros que cumplan los criterios.');
		$this->load->view("$ruta/rep_productos_pdf", $data);
	} // END method rep_productos_pdf #########################################################

	function rep_entradas_pdf()
	{ // BEGIN method rep_entradas_pdf
		global $ruta;
		//echo "<pre>";var_dump($_POST);echo "</pre>";exit();
		$filtro = array();
		if((int)$_POST['tentrada']>0)
			$filtro[] = "e.ctipo_entrada = ".(int)$_POST['tentrada'];
		if((int)$_POST['espaciof']>0)
			$filtro[] = "e.espacios_fisicos_id = ".(int)$_POST['espaciof'];
		if((int)$_POST['proveedor']>0)
			$filtro[] = "e.cproveedores_id = ".(int)$_POST['proveedor'];
		if((int)$_POST['producto']>0)
			$filtro[] = "e.cproductos_id = ".(int)$_POST['producto'];

		//Fecha
		if(isset($_POST['fecha1'])==false) {
			$hoy=date("Y-m-d");
			$fecha1=date("Y-m-d", strtotime($hoy));
			$fecha2=date("Y-m-d", strtotime($hoy)+(24 * 60 * 60));
		} else {
			$fecha=explode(" ", $_POST['fecha1']);
			$fecha1=$fecha[2]."-".$fecha[1]."-".$fecha[0];
			$fecha=explode(" ", $_POST['fecha2']);
			$fecha2a=$fecha[2]."-".$fecha[1]."-".$fecha[0];
			$fecha2=date("Y-m-d", strtotime($fecha[2]."-".$fecha[1]."-".$fecha[0]) + (24 * 60 * 60));
			$data['fecha1']=$_POST['fecha1'];
			$data['fecha2']=$_POST['fecha2'];
		}
		$filtro[] = "(e.fecha >= '$fecha1' and e.fecha <= '$fecha2')";

		$where = " where ".implode(" and ", $filtro)." ";

		$nivel=array();
		$nivel[]=(int)$_POST['nivel_1'];
		$nivel[]=(int)$_POST['nivel_2'];
		$nivel[]=(int)$_POST['nivel_3'];
		$nivel[]=(int)$_POST['nivel_4'];
		$nivel[]=(int)$_POST['nivel_5'];
		$campos=array();
		foreach ($nivel as $my_key=>$my_value){
			switch($my_value){
				case 1: $campos[]='fecha'; break;
				case 2: $campos[]='tipo'; break;
				case 3: $campos[]='espacio'; break;
				case 4: $campos[]='proveedor'; break;
				case 5: $campos[]='producto'; break;
			}
		}
		if (count($campos))
			$order_by=" order by ".implode(",", $campos)." ";
		else
			$order_by="";
		$data['title'] = $this->input->post('title');
		$query = $this->db->query("SELECT e.id, f.folio_factura AS factura, e.fecha, p.descripcion AS producto, e.cantidad, e.costo_unitario, e.tasa_impuesto, e.costo_total, te.tag AS tipo, ef.tag AS espacio, pv.clave AS proveedor FROM entradas AS e left Join espacios_fisicos AS ef ON ef.id = e.espacios_fisicos_id left Join pr_facturas AS f ON f.id = e.pr_facturas_id left Join cproductos AS p ON p.id = e.cproductos_id left Join cproveedores AS pv ON pv.id = e.cproveedores_id left Join ctipos_entradas AS te ON te.id = e.ctipo_entrada $where and e.estatus_general_id=1 $order_by");
		if($query->num_rows==0)
			show_error('No hay registros que cumplan los criterios.');
		$data['entradas']=$query->result();
		$query->free_result();
		$this->load->view("$ruta/rep_entradas_pdf", $data);
	} // END method rep_entradas_pdf #########################################################

	function rep_salidas_pdf()
	{ // BEGIN method rep_salidas_pdf
		global $ruta;
		//echo "<pre>";var_dump($_POST);echo "</pre>";exit();
		$filtro = array();
		if((int)$_POST['tsalida']>0)
			$filtro[] = "s.ctipo_salida_id= ".(int)$_POST['tsalida'];
		if((int)$_POST['espaciof']>0)
			$filtro[] = "s.espacios_fisicos_id = ".(int)$_POST['espaciof'];
		if((int)$_POST['cliente']>0)
			$filtro[] = "s.cproveedores_id = ".(int)$_POST['proveedor'];
		if((int)$_POST['producto']>0)
			$filtro[] = "s.cproductos_id = ".(int)$_POST['producto'];

		//$fecha1 = $this->input->post('fecha1');
		//$fecha2 = $this->input->post('fecha2');

		//Fecha
		if(isset($_POST['fecha1'])==false) {
			$hoy=date("Y-m-d");
			$fecha1=date("Y-m-d", strtotime($hoy));
			$fecha2=date("Y-m-d", strtotime($hoy)+(24 * 60 * 60));
		} else {
			$fecha=explode(" ", $_POST['fecha1']);
			$fecha1=$fecha[2]."-".$fecha[1]."-".$fecha[0];
			$fecha=explode(" ", $_POST['fecha2']);
			$fecha2a=$fecha[2]."-".$fecha[1]."-".$fecha[0];
			$fecha2=date("Y-m-d", strtotime($fecha[2]."-".$fecha[1]."-".$fecha[0]) + (24 * 60 * 60));
			$data['fecha1']=$_POST['fecha1'];
			$data['fecha2']=$_POST['fecha2'];
		}
		$filtro[] = "(s.fecha >= '$fecha1' and s.fecha <= '$fecha2')";

		$where = " where ".implode(" and ", $filtro)." ";

		$nivel=array();
		$nivel[]=(int)$_POST['nivel_1'];
		$nivel[]=(int)$_POST['nivel_2'];
		$nivel[]=(int)$_POST['nivel_3'];
		$nivel[]=(int)$_POST['nivel_4'];
		$nivel[]=(int)$_POST['nivel_5'];
		$campos=array();
		foreach ($nivel as $my_key=>$my_value){
			switch($my_value){
				case 1: $campos[]='fecha'; break;
				case 2: $campos[]='tipo'; break;
				case 3: $campos[]='espacio_fisico'; break;
				case 4: $campos[]='cliente'; break;
				case 5: $campos[]='producto'; break;
			}
		}
		if (count($campos))
			$order_by=" order by ".implode(",", $campos)." ";
		else
			$order_by="";
		$data['title'] = $this->input->post('title');
		$query = $this->db->query("SELECT s.id, fc.folio_factura AS factura, s.fecha, s.tasa_impuesto, p.descripcion AS producto, s.cantidad, s.costo_unitario, s.costo_total, ts.tag AS tipo, ef.tag AS espacio_fisico, c.clave AS cliente FROM salidas AS s Left Join cl_facturas AS fc ON fc.id = s.cl_facturas_id Left Join espacios_fisicos AS ef ON ef.id = s.espacios_fisicos_id Left Join cclientes AS c ON c.id = s.cclientes_id Left Join cproductos AS p ON p.id = s.cproductos_id Left Join ctipos_salidas AS ts ON ts.id = s.ctipo_salida_id $where and s.estatus_general_id=1 $order_by");
		if($query->num_rows==0)
			show_error('No hay registros que cumplan los criterios.');
		$data['salidas']=$query->result();
		$query->free_result();
		$this->load->view("$ruta/rep_salidas_pdf", $data);
	} // END method rep_salidas_pdf #########################################################

	function test()
	{ // BEGIN method test
		$f=new Familia_producto();
		$f->select('id,tag')->get();
		foreach($f->all as $reg)
			echo $reg->id.': '.$reg->tag."<br />";
	} // END method test #########################################################

	
	function rep_inventario_talla_pdf(){
		global $ruta;
		$data['title']=$this->accion->get_title("{$_POST['subfuncion']}");
		//Obtener los datos
		$empresas=$_POST['empresas'];
		$espacios=$_POST['espacios'];
		$familia=$_POST['familia'];
		$subfamilia=$_POST['subfamilia'];
		$tipo=$_POST['tipo'];
		if($empresas==0){
			$where=" where e1.estatus_general_id=1";
			$data['empresa']="Todos";
		} else {
			$where=" where e.id='$empresas' ";
			$e1=$this->empresa->get_empresa($empresas);
			$data['empresa']=$e1->razon_social;
		}
		if($tipo==0){
			$data['tipo']="TODOS";
		} else if($tipo==1){
			$where .=" and ef.tipo_espacio_id in (1) ";
			$data['tipo']="ALMACENES";
		} else if($tipo==2){
			$where .=" and ef.tipo_espacio_id in (2,4) ";
			$data['tipo']="TIENDAS";
		}

		if($espacios==0){
			$data['tienda']="TODAS";
		} else {
			$where .=" and ef.id='$espacios' ";
			$ef=$this->espacio_fisico->get_espacio_f($espacios);
			$data['tienda']=$ef->tag;
		}
		$where1="where p.id>='0'";
		if($familia==0){
			$data['familia']="Todos";
		} else {
			$where1 .=" and p.cfamilia_id='$familia' ";
			$fam=$this->familia_producto->get_familia($familia);
			$data['familia']=$fam->tag;
		}

		if($subfamilia==0){
			$data['subfamilia']="Todos";
		} else {
			$where1 .=" and p.csubfamilia_id='$subfamilia' ";
			$sfam=$this->subfamilia_producto->get_subfamilia($subfamilia);
			$data['subfamilia']=$sfam->tag;
		}
		//Fecha
		if(isset($_POST['fecha'])==false) {
			$fecha1=date("Y-m-d H:i:s");
		} else {
			$fecha=explode(" ", $_POST['fecha']);
			$fecha1=$fecha[2]."-".$fecha[1]."-".$fecha[0]. " ".$_POST['hora'].":".$_POST['min'].":00";
		}
		$where .=" and fecha<='$fecha1'";
		$data['fecha']=$fecha1;
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
				$order_clause.="producto,";
			} else if($value ==0){
				//$order_clause.="f.generalid,";
			}
		}
		if($ingreso>0){
			$order_by=substr($order_clause, 0,-1);
		} else {
			$order_by="";
		}
		$data['inventario']=$this->almacen->inventario_talla($where, $order_by, $where1);
		$this->load->view("$ruta/rep_inventario_pdf", $data);
		unset($data);
		//Enviarselo al view para general el PDF
	}///////Fin


	function rep_inventario_ejecutivo_pdf(){
		global $ruta, $subfuncion;
		$data['title']=$this->accion->get_title("{$_POST['subfuncion']}");
		//Obtener los datos
		$espacios=$_POST['espacios'];
		$data['espacio']=$espacio=$this->espacio_fisico->get_by_id($espacios);

		//Fecha
		if(isset($_POST['fecha'])==false) {
			//		    $fecha1=date("Y-m-d");
			$fecha2=date("Y-m-d", mktime()+(24 * 60 * 60));
			$fecha1=date("Y-m-d", mktime());
		} else {
			$fecha=explode(" ", $_POST['fecha']);
			$fecha1=$fecha[2]."-".$fecha[1]."-".$fecha[0]. " ".$_POST['hora'].":".$_POST['min'];
		}
		// 		$where .=" and fecha>='$fecha1' and fecha<'$fecha2'";
		$where =" where fecha<='$fecha1' and espacios_fisicos_id=$espacios ";

		$data['fecha']=$fecha1;
		$order_by="tag";
		$data['inventario']=$this->almacen->inventario_ejecutivo($where, $order_by);
		$this->load->view("$ruta/rep_inventario_ejecutivo_pdf", $data);
		unset($data);
		//Enviarselo al view para general el PDF
	}///////Fin


	function rep_pedido_traspaso(){
		$id=$this->uri->segment(4);
		if($this->uri->segment(5)!='')
			$estatus=$this->uri->segment(5);
		else
			$estatus=2;
		if(is_numeric($id)==false)
			show_error('Error 1: El número de pedido de traspaso no existe'.$id);
		$this->load->model('lote');
		$data['title']="Orden de Traspaso";
		$data['generales']=$this->lote->get_traspaso_pdf($id, $estatus);
		if($data['generales']==false)
			show_error('El Pedido de Traspaso no existe o ya ha sido enviado');
		$data['detalles']=$this->lote->get_traspaso_detalles_pdf($id,$estatus);
		if($data['generales']==false){
			/*				$this->db->query("delete from cl_detalle_pedidos where cl_pedidos_id='$id'");
				$this->db->query("delete from cl_pedidos where id='$id'");
			$this->db->query("delete from traspasos where cl_pedido_id='$id'");*/
			show_error('El pedido de Traspaso no tiene detalles en su pedido');
		}
		$this->load->view("almacen/rep_pedido_traspaso", $data);
		unset($data);
	}

	function rep_pedido_traspaso_salida(){
		$id=$this->uri->segment(4);
		if(is_numeric($id)==false)
			show_error('Error 1: El número de pedido de traspaso no existe'.$id);
		$this->load->model('lote');
		$data['title']="Orden de Traspaso";
		$data['generales']=$this->lote->get_traspaso_pdf($id,1);
		if($data['generales']==false)
			show_error('El Pedido de Traspaso no existe o ya ha sido enviado');
		$data['detalles']=$this->lote->get_traspaso_detalles_pdf($id,1); //Estatus 1
		if($data['generales']==false){
			/*				$this->db->query("delete from cl_detalle_pedidos where cl_pedidos_id='$id'");
				$this->db->query("delete from cl_pedidos where id='$id'");
			$this->db->query("delete from traspasos where cl_pedido_id='$id'");*/
			show_error('El pedido de Traspaso no tiene detalles en su pedido');
		}
		$this->load->view("almacen/rep_pedido_traspaso", $data);
		unset($data);
	}


	function rep_pedido_traspaso_prev(){
		$id=$this->uri->segment(4);
		if(is_numeric($id)==false)
			show_error('Error 1: El número de pedido de traspaso no existe'.$id);

		$data['title']="Orden de Traspaso";
		$data['generales']=$this->traspaso->get_traspaso_pdf($id);
		if($data['generales']==false)
			show_error('El Pedido de Traspaso no existe');

		$data['detalles']=$this->cl_detalle_pedido->get_cl_detalles_pedido_pdf($data['generales']->cl_pedido_id);
		if($data['generales']==false) {
			$this->db->query("delete from cl_detalle_pedidos where cl_pedidos_id='$id'");
			$this->db->query("delete from cl_pedidos where id='$id'");
			$this->db->query("delete from traspasos where cl_pedido_id='$id'");
			show_error('El pedido de Traspaso no tiene detalles en su pedido');
		}

		$this->load->view("almacen/rep_pedido_traspaso", $data);
		unset($data);
	}
	function rep_ejecutivo_salidas_pdf()
	{
		// BEGIN method rep_ejecutivo_salidas_pdf
		global $ruta;
		//echo "<pre>";var_dump($_POST);echo "</pre>";exit();
		$filtro = array();
		if((int)$_POST['espaciof']>0)
			$filtro[] = "s.espacios_fisicos_id = ".(int)$_POST['espaciof'];
		if((int)$_POST['cliente']>0)
			$filtro[] = "s.cclientes_id = ".(int)$_POST['cliente'];
		if(isset($_POST['producto'])==false)
			$_POST['producto']=0;
		if((int)$_POST['producto']>0)
			$filtro[] = "s.cproductos_id = ".(int)$_POST['producto'];
		$fecha1 = $this->input->post('fecha1');
		$fecha2 = $this->input->post('fecha2');

		if(isset($_POST['fecha1'])==false) {
			$hoy=date("Y-m-d");
			$fecha1=date("Y-m-d", strtotime($hoy));
			$fecha2=date("Y-m-d", strtotime($hoy)+(24 * 60 * 60));
		} else {
			$fecha=explode(" ", $_POST['fecha1']);
			$fecha1=$fecha[2]."-".$fecha[1]."-".$fecha[0];
			$fecha=explode(" ", $_POST['fecha2']);
			$fecha2a=$fecha[2]."-".$fecha[1]."-".$fecha[0];
			$fecha2=date("Y-m-d", strtotime($fecha[2]."-".$fecha[1]."-".$fecha[0]) + (24 * 60 * 60));
			$data['fecha1']=$_POST['fecha1'];
			$data['fecha2']=$_POST['fecha2'];
		}
		if (count($filtro)) {
			$where = " and ".implode(" and ", $filtro)." ";
			$where.=" and s.fecha >= '$fecha1' and s.fecha < '$fecha2'";
		} else
			$where = " and s.fecha >= '$fecha1' and s.fecha < '$fecha2'";


		$data['fecha1']=$fecha1;
		$data['fecha2']=$fecha2;
		$data['periodo']=$data['fecha1']." a ".$data['fecha1'];
		$nivel=array();
		$nivel[]=(int)$_POST['nivel_1'];
		$nivel[]=(int)$_POST['nivel_2'];
		$nivel[]=(int)$_POST['nivel_3'];
		$nivel[]=(int)$_POST['nivel_4'];
		$campos=array();
		foreach ($nivel as $my_value)
		{
			switch($my_value)
			{
				case 1:
					$campos[]='producto';
					break;
				case 2:
					$campos[]='cantidad';
					break;
				case 3:
					$campos[]='unidad';
					break;
				case 4:
					$campos[]='total';
					break;
			}
		}
		if (count($campos))
			$order_by=" order by ".implode(",", $campos)." ";
		else
			$order_by="";
		// obtener ids de productos
		$this->db->select("id ,cfamilia_id AS familia ,csubfamilia_id AS subfamilia ,descripcion");
		// 			$this->db->where("estatus_general_id",'1');
		if((int)$this->input->post('familia') > 0)
			$this->db->where("cfamilia_id",$_POST['familia']);
		if((int)$this->input->post('subfamilia') > 0)
			$this->db->where("csubfamilia_id",$_POST['subfamilia']);
		if((int)$this->input->post('producto') > 0)
			$this->db->where("id",$_POST['producto']);
		$this->db->order_by('familia ASC, subfamilia ASC, descripcion ASC ');
		$res=$this->db->get('cproductos')->result();
		if(count($res)==0)
			show_error('No hay productos');
		$pids=array();
		foreach($res as $row)
			$pids[]=$row->id;
		$sql="SELECT s.cproductos_id AS pid ,cproductos.descripcion AS producto ,sum(s.cantidad) AS cantidad ,cunidades_medidas.tag AS unidad ,sum(s.costo_total) AS total FROM salidas as s LEFT JOIN cproductos ON (s.cproductos_id = cproductos.id) LEFT JOIN cunidades_medidas ON (cproductos.cunidad_medida_id = cunidades_medidas.id) WHERE (s.cproductos_id in (".implode(',',$pids).") $where AND s.estatus_general_id ='1' AND s.ctipo_salida_id='1') GROUP BY pid,cproductos.descripcion,cunidades_medidas.tag $order_by";
		$res = $this->db->query($sql)->result();
		if(count($res)==0)
			show_error('No hay registros que cumplan los criterios.');
		$data['salidas']=$res;
		$data['title'] = $this->input->post('title');
		$this->load->view("$ruta/rep_ejecutivo_salidas_pdf", $data);
		unset($data);
	} // END method rep_ejecutivo_salidas_pdf #########################################################

	function rep_ejecutivo_salidas_cantidad_pdf()
	{
		// BEGIN method rep_ejecutivo_salidas_vol_pdf
		global $ruta;
		//echo "<pre>";var_dump($_POST);echo "</pre>";exit();
		$filtro = array();
		if((int)$_POST['espaciof']>0)
			$filtro[] = "s.espacios_fisicos_id = ".(int)$_POST['espaciof'];
		if((int)$_POST['cliente']>0)
			$filtro[] = "s.cclientes_id = ".(int)$_POST['cliente'];
		if(isset($_POST['producto'])==false)
			$_POST['producto']=0;
		if((int)$_POST['producto']>0)
			$filtro[] = "s.cproductos_id = ".(int)$_POST['producto'];
		$fecha1 = $this->input->post('fecha1');
		$fecha2 = $this->input->post('fecha2');

		if(isset($_POST['fecha1'])==false) {
			$hoy=date("Y-m-d");
			$fecha1=date("Y-m-d", strtotime($hoy));
			$fecha2=date("Y-m-d", strtotime($hoy)+(24 * 60 * 60));
		} else {
			$fecha=explode(" ", $_POST['fecha1']);
			$fecha1=$fecha[2]."-".$fecha[1]."-".$fecha[0];
			$fecha=explode(" ", $_POST['fecha2']);
			$fecha2a=$fecha[2]."-".$fecha[1]."-".$fecha[0];
			$fecha2=date("Y-m-d", strtotime($fecha[2]."-".$fecha[1]."-".$fecha[0]) + (24 * 60 * 60));
			$data['fecha1']=$_POST['fecha1'];
			$data['fecha2']=$_POST['fecha2'];
		}
		if (count($filtro)) {
			$where = " and ".implode(" and ", $filtro)." ";
			$where.=" and s.fecha >= '$fecha1' and s.fecha < '$fecha2'";
		} else
			$where = " and s.fecha >= '$fecha1' and s.fecha < '$fecha2'";

		$data['fecha1']=$fecha1;
		$data['fecha2']=$fecha2;
		$data['periodo']=$data['fecha1']." a ".$data['fecha1'];
		$nivel=array();
		$nivel[]=(int)$_POST['nivel_1'];
		$nivel[]=(int)$_POST['nivel_2'];
		$nivel[]=(int)$_POST['nivel_3'];
		$campos=array();
		foreach ($nivel as $my_value)
		{
			switch($my_value)
			{
				case 1:
					$campos[]='producto';
					break;
				case 2:
					$campos[]='cantidad';
					break;
				case 3:
					$campos[]='unidad';
			}
		}
		if (count($campos))
			$order_by=" order by ".implode(",", $campos)." ";
		else
			$order_by="";
		// obtener ids de productos
		$this->db->select("id ,cfamilia_id AS familia ,csubfamilia_id AS subfamilia ,descripcion");
		// 			$this->db->where("estatus_general_id",'1');
		if((int)$this->input->post('familia') > 0)
			$this->db->where("cfamilia_id",$_POST['familia']);
		if((int)$this->input->post('subfamilia') > 0)
			$this->db->where("csubfamilia_id",$_POST['subfamilia']);
		if((int)$this->input->post('producto') > 0)
			$this->db->where("id",$_POST['producto']);
		$this->db->order_by('familia ASC, subfamilia ASC, descripcion ASC');
		$res=$this->db->get('cproductos')->result();
		if(count($res)==0)
			show_error('No hay productos');
		$pids=array();
		foreach($res as $row)
			$pids[]=$row->id;
		$sql="SELECT s.cproductos_id AS pid ,cproductos.descripcion AS producto ,sum(s.cantidad) AS cantidad ,cunidades_medidas.tag AS unidad FROM soleman.salidas as s LEFT JOIN soleman.cproductos ON (s.cproductos_id = cproductos.id) LEFT JOIN soleman.cunidades_medidas ON (cproductos.cunidad_medida_id = cunidades_medidas.id) WHERE (s.cproductos_id in (".implode(',',$pids).") $where AND s.estatus_general_id ='1' AND s.ctipo_salida_id='1') GROUP BY pid $order_by";
		$res = $this->db->query($sql)->result();
		if(count($res)==0)
			show_error('No hay registros que cumplan los criterios.');
		$data['salidas']=$res;

		$data['title'] = $this->input->post('title');
		$this->load->view("$ruta/rep_ejecutivo_salidas_cantidad_pdf", $data);
		unset($data);
	} // END method rep_ejecutivo_salidas_vol_pdf #########################################################
	function rep_kardex_pdf() {
		global $ruta;
		$data['filename'] = "rep_kardex.pdf";
		$data['fecha_inicio'] = $_POST['fecha_inicio'];
		$data['fecha_fin'] = $_POST['fecha_fin'];
		$fecha_inicio = date_format(date_create_from_format('d m Y', $_POST['fecha_inicio']), 'Y-m-d');
		$fecha_fin = date_format(date_create_from_format('d m Y', $_POST['fecha_fin']), 'Y-m-d');
		$where_e = $where_s = '';
		if ($_POST['espacio_id'] > 0) {
			$where_e .= " AND e.espacios_fisicos_id = {$_POST['espacio_id']}";
			$where_s .= " AND s.espacios_fisicos_id = {$_POST['espacio_id']}";
			$espacio = new Espacio_fisico($_POST['espacio_id']);
			$data['espacios'][$espacio->id] = $espacio->tag;
		} else {
			$this->load->model('espacio_fisico');
			$data['espacios'] = $this->espacio_fisico->get_espacios_as_array();
		}
		if ($_POST['entrada_id'] > 0) {
			$where_e .= " AND e.ctipo_entrada = {$_POST['entrada_id']}";
			$entrada = new Tipo_entrada($_POST['entrada_id']);
			$data['entradas'][$entrada->id] = $entrada->tag;
		} else {
			$this->load->model('tipo_entrada');
			$data['entradas'] = $this->tipo_entrada->get_tipos_entrada_as_array();
		}
		if ($_POST['salida_id'] > 0) {
			$where_s .= " AND s.ctipo_salida_id = {$_POST['salida_id']}";
			$salida = new Tipo_salida($_POST['salida_id']);
			$data['salidas'][$salida->id] = $salida->tag;
		} else {
			$this->load->model('tipo_salida');
			$data['salidas'] = $this->tipo_salida->get_tipos_salida_as_array();
		}
		//if ($_POST['marca_id'] > 0) {
		//	$where_e .= " AND p.cmarca_producto_id = {$_POST['marca_id']}";
		//	$where_s .= " AND p.cmarca_producto_id = {$_POST['marca_id']}";
		//	$marca = new Marca_producto($_POST['marca_id']);
		//	$data['marcas'][$marca->id] = $marca->tag;
			if ($_POST['producto_id'] > 0) {
				$where_e .= " AND e.cproductos_id = {$_POST['producto_id']}";
				$where_s .= " AND s.cproductos_id = {$_POST['producto_id']}";
				if ($_POST['corrida_id'] > 0) {
					$where_e .= " AND e.cproducto_numero_id = {$_POST['corrida_id']}";
					$where_s .= " AND s.cproducto_numero_id = {$_POST['corrida_id']}";
				}
			}
		//} else {
			$this->load->model('marca_producto');
			$data['marcas'] = $this->marca_producto->get_marcas_as_array();
		//}
		$data['marcas'][0] = "Ninguna";
		$sql = "(SELECT
		e.id,
		e.espacios_fisicos_id,
		n.numero_mm,
		TO_CHAR(e.fecha, 'DD-MM-YYYY HH24:MI:SS') fecha,
		e.fecha fecha_format,
		e.cproductos_id,
		e.ctipo_entrada tipo_entrada,
		0 tipo_salida,
		e.cproducto_numero_id,
		TO_CHAR(e.cantidad, 'FM999G999G999') entrada,
		'' salida,
		p.cmarca_producto_id,
		p.descripcion,
		CASE
		WHEN e.ctipo_entrada = 1 THEN ('(Factura ' || e.pr_facturas_id || ')')
		WHEN e.ctipo_entrada = 2 THEN ('(ID ' || e.traspaso_id || ')')
		ELSE ('(Id ' || e.id || ')')
		END movimiento
		FROM
		entradas e
		JOIN cproductos p
		ON e.cproductos_id = p.id
		JOIN cproductos_numeracion n
		ON e.cproducto_numero_id = n.id
		WHERE
		e.fecha BETWEEN '$fecha_inicio' AND '$fecha_fin 23:59:59'
		AND e.estatus_general_id = 1
		$where_e
		UNION SELECT
		s.id,
		s.espacios_fisicos_id,
		n.numero_mm,
		TO_CHAR(s.fecha, 'DD-MM-YYYY HH24:MI:SS') fecha,
		s.fecha fecha_format,
		s.cproductos_id,
		0 tipo_entrada,
		s.ctipo_salida_id tipo_salida,
		s.cproducto_numero_id,
		'' entrada,
		TO_CHAR(s.cantidad, 'FM999G999G999') salida,
		p.cmarca_producto_id,
		p.descripcion,
		CASE
		WHEN s.ctipo_salida_id = 1 OR s.ctipo_salida_id = 5 THEN ('(Remisión ' || s.numero_remision_id || ')')
		WHEN s.ctipo_salida_id = 2 THEN ('(Id  ' || s.traspaso_id || ')')
		ELSE ('(Id ' || s.id || ')')
		END movimiento
		FROM
		salidas s
		JOIN cproductos p
		ON s.cproductos_id = p.id
		JOIN cproductos_numeracion n
		ON s.cproducto_numero_id = n.id
		WHERE
		s.fecha BETWEEN '$fecha_inicio' AND '$fecha_fin 23:59:59'
		AND s.estatus_general_id = 1
		$where_s)
		ORDER BY
		descripcion" . ($_POST['order_corrida'] == 1 ? ", numero_mm" : '') . ", fecha_format";
		$movimientos = $this->db->query($sql);
		// <editor-fold desc="Consulta para saldos iniciales">
		$sql = "SELECT TO_CHAR(
		SUM(e.cantidad) -
		(SELECT
		CASE WHEN SUM(s.cantidad) > 0 THEN SUM(s.cantidad) ELSE 0 END
		FROM
		salidas s
		WHERE
		s.fecha < '$fecha_inicio'
		AND s.estatus_general_id = 1
		AND s.cproductos_id = e.cproductos_id
		 " . ($_POST['order_corrida'] == 1 ? "AND s.cproducto_numero_id = e.cproducto_numero_id" : '') . "
		AND s.espacios_fisicos_id = e.espacios_fisicos_id), 'FM999G999G999') inventario,
		e.cproductos_id,
		e.espacios_fisicos_id,
		p.descripcion,
		p.cmarca_producto_id
		" . ($_POST['order_corrida'] == 1 ? ", n.numero_mm, e.cproducto_numero_id" : '') . "
		FROM
		entradas e
		JOIN cproductos p
		ON e.cproductos_id = p.id
		" . ($_POST['order_corrida'] == 1 ? "JOIN cproductos_numeracion n ON e.cproducto_numero_id = n.id" : '') . "
		WHERE
		e.fecha < '$fecha_inicio'
		AND e.estatus_general_id = 1
		$where_e
		GROUP BY
		e.espacios_fisicos_id,
		p.cmarca_producto_id,
		e.cproductos_id,
		p.descripcion
		" . ($_POST['order_corrida'] == 1 ? ", e.cproducto_numero_id, n.numero_mm" : '')."
		ORDER BY
		p.descripcion" . ($_POST['order_corrida'] == 1 ? ", n.numero_mm" : '');
		// </editor-fold>
		$inventario_inicial = $this->db->query($sql);
		// <editor-fold desc="Ciclos para orden por producto">
		if ($_POST['order_corrida'] != 1) {
			foreach ($movimientos->result() as $movimiento) {
				$data['movimientos']
				[$movimiento->espacios_fisicos_id]
				[$movimiento->cmarca_producto_id]
				[$movimiento->cproductos_id][] = array(
						'descripcion' => $movimiento->descripcion,
						'fecha' => $movimiento->fecha,
						'tipo_e' => $movimiento->tipo_entrada,
						'tipo_s' => $movimiento->tipo_salida,
						'movimiento' => $movimiento->movimiento,
						'numero' => $movimiento->numero_mm,
						'entrada' => $movimiento->entrada,
						'salida' => $movimiento->salida);
			}
			unset($movimientos);
			foreach ($inventario_inicial->result() as $cantidad) {
				if ($cantidad->inventario != 0)
					$data['inventario']
					[$cantidad->espacios_fisicos_id]
					[$cantidad->cmarca_producto_id]
					[$cantidad->cproductos_id] = array($cantidad->inventario, $cantidad->descripcion);
			}
			unset($inventario_inicial);
			$data['corrida'] = false;
		}
		// </editor-fold>
		// <editor-fold desc="Ciclos para orden por corrida">
		else {
			foreach ($movimientos->result() as $movimiento) {
				$data['movimientos']
				[$movimiento->espacios_fisicos_id]
				[$movimiento->cmarca_producto_id]
				[$movimiento->cproductos_id]
				[$movimiento->cproducto_numero_id][] = array(
						'descripcion' => $movimiento->descripcion,
						'fecha' => $movimiento->fecha,
						'tipo_e' => $movimiento->tipo_entrada,
						'tipo_s' => $movimiento->tipo_salida,
						'movimiento' => $movimiento->movimiento,
						'numero' => $movimiento->numero_mm,
						'entrada' => $movimiento->entrada,
						'salida' => $movimiento->salida);
			}
			unset($movimientos);
			foreach ($inventario_inicial->result() as $cantidad) {
				if ($cantidad->inventario != 0)
					$data['inventario']
					[$cantidad->espacios_fisicos_id]
					[$cantidad->cmarca_producto_id]
					[$cantidad->cproductos_id]
					[$cantidad->cproducto_numero_id] = array($cantidad->inventario, $cantidad->descripcion, $cantidad->numero_mm);
			}
			unset($inventario_inicial);
			$data['corrida'] = true;
		}
		// </editor-fold>
		if (!isset($data['movimientos']) && !isset($data['inventario'])) {
			?>
<div class="emptyList">No existen movimientos con esos datos de registro</div>
<?php
return;
		}
		$this->load->view("$ruta/rep_kardex_pdf", $data);
	}
	// END method kardex_pdf #########################################################
	function rep_kardex_ejecutivo_pdf()
	{
		// BEGIN method kardex_pdf
		global $ruta;
		//echo "<pre>";print_r($_POST);echo "</pre>";die();
		$periodo1=$periodo2='';
		$fecha1 = $this->input->post('fecha1');
		$fecha2 = $this->input->post('fecha2');
		$tipo_s=(int)$this->input->post('tipo_s');// 25.06.2010
		$tipo_e=(int)$this->input->post('tipo_e');
		if($fecha1 != false)
		{
			list($d,$m,$a) = explode(" ",$fecha1);
			$fecha1 = sprintf('%04d-%02d-%02d',$a,$m,$d);
		}
		if($fecha2 != false)
		{
			list($d,$m,$a) = explode(" ",$fecha2);
			$fecha2 = sprintf('%04d-%02d-%02d',$a,$m,$d);
		}
		$f1 = false;
		$f2 = false;
		$fsalida='s.fecha';
		$fentrada='e.fecha';
		$finicial='';
		// fecha a partir de la cual se tomara el inventario, debe ser el menor del rango o la unica
		if($fecha1 == date("Y-m-d",strtotime($fecha1)))
			$f1 = true;
		if($fecha2 == date("Y-m-d",strtotime($fecha2)))
			$f2 = true;
		if($f1 && $f2)
		{
			if(strcmp($fecha2,$fecha1) > 0)
			{
				$finicial=$fecha1;
				$periodo1 = "(left($fentrada,10) >= '$fecha1' and left($fentrada,10) <= '$fecha2')";
				$periodo2 = "(left($fsalida,10) >= '$fecha1' and left($fsalida,10) <= '$fecha2')";
			}
			elseif(strcmp($fecha2,$fecha1) < 0)
			{
				$finicial=$fecha2;
				$periodo1 = "(left($fentrada,10) >= '$fecha2' and left($fentrada,10) <= '$fecha1')";
				$periodo2 = "(left($fsalida,10) >= '$fecha2' and left($fsalida,10) <= '$fecha1')";
			}
			else
			{
				$finicial=$fecha1;
				$periodo1 = "$fentrada LIKE '$fecha1%'";
				$periodo2 = "$fsalida LIKE '$fecha1%'";
			}
		}
		elseif($f1)
		{
			$finicial=$fecha1;
			$periodo1 = "$fentrada LIKE '$fecha1%'";
			$periodo2 = "$fsalida LIKE '$fecha1%'";
		}
		elseif($f2)
		{
			$finicial=$fecha2;
			$periodo1 = "$fentrada LIKE '$fecha2%'";
			$periodo2 = "$fsalida LIKE '$fecha2%'";
		}
		if($f1 || $f2)
		{
			$periodo1='AND '.$periodo1;
			$periodo2='AND '.$periodo2;
		}
		// obtener los espacios fisicos
		$espacios=array();
		$familias=array();
		$subfamilias=array();
		$esp_ids=array();
		$this->db->select("id,tag as nombre")->order_by("id,nombre");
		if((int)$this->input->post('espacio')>0)
			$this->db->where('id',(int)$this->input->post('espacio'));
		$result=$this->db->get('espacios_fisicos')->result();
		foreach($result as $row)
		{
			$espacios[$row->id]=$row->nombre;
			$esp_ids[]=$row->id;
		}
		// las familias
		$res=$this->db->select("id,tag")->get('cproductos_familias')->result();
		if(count($res)==0)
			show_error('No hay familias');
		foreach($res as $row)
		{
			$familias[$row->id]=$row->tag;
		}
		// ahora las subfamilias
		$res=$this->db->select("id,tag")->get('cproductos_subfamilias')->result();
		if(count($res)==0)
			show_error('No hay subfamilias');
		foreach($res as $row)
		{
			$subfamilias[$row->id]=$row->tag;
		}
		$cond_te=($tipo_e>0)?"AND e.ctipo_entrada='$tipo_e'":"";// 25.06.2010
		$cond_ts=($tipo_s>0)?"AND s.ctipo_salida_id='$tipo_s'":"";
		// evaluar el orden
		$orden=$this->input->post('orden');
		$bloques=array();
		if($orden=='familia')
		{
			// Estructura:
			// Espacio > Familia > Subfamilia > Producto
			// construir la estructura
			$productos=array();
			// ahora los productos
			$this->db->select("id ,cfamilia_id AS familia ,csubfamilia_id AS subfamilia ,descripcion ,presentacion");
			if((int)$this->input->post('familia') > 0)
				$this->db->where("cfamilia_id",$_POST['familia']);
			if((int)$this->input->post('subfamilia') > 0)
				$this->db->where("csubfamilia_id",$_POST['subfamilia']);
			if((int)$this->input->post('producto') > 0)
				$this->db->where("id",$_POST['producto']);
			$this->db->order_by('familia ASC, subfamilia ASC, descripcion ASC, presentacion ASC');
			$res=$this->db->get('cproductos')->result();
			if(count($res)==0)
				show_error('No hay productos');
			foreach($res as $row)
			{
				$productos[$row->id]=array(
						'familia'=>$row->familia,
						'subfamilia'=>$row->subfamilia,
						'descripcion'=>$row->descripcion,
						'presentacion'=>$row->presentacion
				);
				$prod_ids[]=$row->id;
			}
			foreach($prod_ids as $pid)
			{
				// obtener los datos de entradas/salidas
				$sql="SELECT e.id, e.espacios_fisicos_id AS espacio, e.cproductos_id AS producto, e.cantidad AS entrada, 0 AS salida FROM entradas AS e WHERE (e.espacios_fisicos_id IN(".implode(',',$esp_ids).") AND e.estatus_general_id = 1 AND e.cproductos_id IN($pid)) $periodo1 $cond_te UNION SELECT s.id, s.espacios_fisicos_id AS espacio, s.cproductos_id AS producto, 0 AS entrada, s.cantidad AS salida FROM salidas AS s WHERE (s.espacios_fisicos_id IN(".implode(',',$esp_ids).") AND s.estatus_general_id = 1 AND s.cproductos_id IN($pid)) $periodo2 $cond_ts";
				$result=$this->db->query($sql)->result();
				if (count($result) > 0)
				{
					// obtener el inventario inicial
					$ei=(float)0;
					$si=(float)0;
					$date1='';
					if($f1 || $f2)
					{
						$date1=date("F j Y",strtotime($finicial))." -1 second";
						$date1=date("Y-m-d H:i:s",strtotime($date1));
					}
					// entradas
					$this->db->select_sum('cantidad');
					$this->db->where('espacios_fisicos_id',implode(',',$esp_ids));
					$this->db->where('cproductos_id',$pid);
					$this->db->where('estatus_general_id','1');
					$this->db->where('fecha <=',$date1);
					$res=$this->db->get('entradas')->result_array();
					if(count($res[0]['cantidad'])>0) $ei=(float)$res[0]['cantidad'];
					#	salidas
					$this->db->select_sum('cantidad');
					$this->db->where('espacios_fisicos_id',implode(',',$esp_ids));
					$this->db->where('cproductos_id',$pid);
					$this->db->where('estatus_general_id','1');
					$this->db->where('fecha <=',$date1);
					$res=$this->db->get('salidas')->result_array();
					if(count($res[0]['cantidad'])>0) $si=(float)$res[0]['cantidad'];
					$total=$ei-$si;
					$et=$st=(float)0;
					$first=true;// 25.06.2010
					foreach($result as $row)
					{
						if($first)// 25.06.2010
						{
							$first=false;
							$bloques[$row->espacio][$productos[$row->producto]['familia']][$productos[$row->producto]['subfamilia']][$row->producto][]=array('entrada'=>'','salida'=>'','existencia'=>$total);
						}
						$et+=$row->entrada;
						$st+=$row->salida;
					}
					$total+=$et;
					$total-=$st;
					$bloques[$row->espacio][$productos[$row->producto]['familia']][$productos[$row->producto]['subfamilia']][$row->producto][]=array('entrada'=>$et,'salida'=>$st,'existencia'=>$total);
				}
			}
			// checar que haya datos
			if(count($bloques)==0)
				show_error('No hay registros que cumplan los criterios.');
		}
		elseif($orden=='producto')
		{
			// Estructura:
			// Espacio > Producto
			// obtener los productos
			$this->db->select("id ,descripcion ,presentacion");
			if((int)$this->input->post('familia') > 0)
				$this->db->where("cfamilia_id",$_POST['familia']);
			if((int)$this->input->post('subfamilia') > 0)
				$this->db->where("csubfamilia_id",$_POST['subfamilia']);
			if((int)$this->input->post('producto') > 0)
				$this->db->where("id",$_POST['producto']);
			$this->db->order_by('descripcion ASC, presentacion ASC');
			$res=$this->db->get('cproductos')->result();
			if(count($res)==0)
				show_error('No hay productos');
			foreach($res as $row)
			{
				$productos[$row->id]=array(
						'descripcion'=>$row->descripcion,
						'presentacion'=>$row->presentacion
				);
				$prod_ids[]=$row->id;
			}
			foreach($prod_ids as $pid)
			{
				// obtener los datos de entradas/salidas
				$sql="SELECT e.id, e.espacios_fisicos_id AS espacio, e.cproductos_id AS producto, e.cantidad AS entrada, 0 AS salida FROM entradas AS e WHERE (e.espacios_fisicos_id IN(".implode(',',$esp_ids).") AND e.estatus_general_id = 1 AND e.cproductos_id IN($pid)) $periodo1 $cond_te UNION SELECT s.id, s.espacios_fisicos_id AS espacio, s.cproductos_id AS producto, 0 AS entrada, s.cantidad AS salida FROM salidas AS s WHERE (s.espacios_fisicos_id IN(".implode(',',$esp_ids).") AND s.estatus_general_id = 1 AND s.cproductos_id IN($pid)) $periodo2 $cond_ts";
				$result=$this->db->query($sql)->result();
				if (count($result) > 0)
				{
					//die($sql.';');
					// obtener el inventario inicial
					$ei=(float)0;
					$si=(float)0;
					$date1='';
					if($f1 || $f2)
					{
						$date1=date("F j Y",strtotime($finicial))." -1 second";
						$date1=date("Y-m-d H:i:s",strtotime($date1));
					}
					// entradas
					$this->db->select_sum('cantidad');
					$this->db->where('espacios_fisicos_id',implode(',',$esp_ids));
					$this->db->where('cproductos_id',$pid);
					$this->db->where('estatus_general_id','1');
					$this->db->where('fecha <=',$date1);
					$res=$this->db->get('entradas')->result_array();
					if(count($res[0]['cantidad'])>0) $ei=(float)$res[0]['cantidad'];
					#	salidas
					$this->db->select_sum('cantidad');
					$this->db->where('espacios_fisicos_id',implode(',',$esp_ids));
					$this->db->where('cproductos_id',$pid);
					$this->db->where('estatus_general_id','1');
					$this->db->where('fecha <=',$date1);
					$res=$this->db->get('salidas')->result_array();
					if(count($res[0]['cantidad'])>0) $si=(float)$res[0]['cantidad'];
					$total=$ei-$si;
					$et=$st=(float)0;
					$first=true;// 25.06.2010
					foreach($result as $row)
					{
						if($first)// 25.06.2010
						{
							$first=false;
							$bloques[$row->espacio][$row->producto][]=array('entrada'=>'','salida'=>'','existencia'=>$total);
						}
						$et+=$row->entrada;
						$st+=$row->salida;
					}
					$total+=$et;
					$total-=$st;
					$bloques[$row->espacio][$row->producto][]=array('entrada'=>$et,'salida'=>$st,'existencia'=>$total);
				}
			}
			// checar que haya datos
			if(count($bloques)==0)
				show_error('No hay registros que cumplan los criterios.');
		}
		else
			show_error('Error De Acceso');//*/
		$vista=array();
		if($f1 || $f2)
		{
			if($f1 && !$f2) $fecha2=$fecha1;
			if(!$f1 && $f2) $fecha1=$fecha2;
			$fecha1=date("d-m-Y",strtotime($fecha1));
			$fecha2=date("d-m-Y",strtotime($fecha2));
			$vista['periodo']=$fecha1.' a '.$fecha2;
		}
		else
			$vista['periodo']='A LA FECHA '.date("d-m-Y");
		$vista['bloques']=$bloques;
		$vista['espacios']=$espacios;
		$vista['familias']=$familias;
		$vista['subfamilias']=$subfamilias;
		$vista['productos']=$productos;
		$vista['orden']=$orden;
		$vista['title'] = $this->input->post('title');
		$this->load->view("$ruta/rep_kardex_ejecutivo_pdf", $vista);
	} // END method kardex_ejecutivo_pdf #########################################################

	function rep_marcas_pdf(){
		global $ruta;
		//Obtener los datos
		//echo "<pre>";var_dump($_POST);echo "</pre>";exit();
		$filtro=array();
		if((int)$_POST['proveedor']>0)
			$filtro[]="m.proveedor_id=".(int)$_POST['proveedor'];
		if (count($filtro))
			$where=" where ".implode(" and ", $filtro)." ";
		else
			$where="";
		$nivel=array();
		$nivel[]=(int)$_POST['nivel_1'];
		$nivel[]=(int)$_POST['nivel_2'];
		$campos=array();
		foreach ($nivel as $my_key=>$my_value){
			switch($my_value){
				case 1: $campos[]='proveedor'; break;
				case 2: $campos[]='marca'; break;
			}
		}
		if (count($campos))
			$order_by=" order by ".implode(",", $campos)." ";
		else
			$order_by="";
		$data['title']=$_POST['title'];
		//
		$sql="SELECT m.id, m.tag as marca, m.proveedor_id, p.razon_social as proveedor FROM cmarcas_productos AS m LEFT JOIN cproveedores AS p ON m.proveedor_id=p.id $where $order_by";
		$query=$this->db->query($sql);
		$res=$query->result();
		if(count($res)==0) show_error('No hay registros que cumplan los criterios.');
		$data['marcas']=$res;
		if(!$data['marcas'])
			show_error('No hay registros que cumplan los criterios.');
		$this->load->view("$ruta/rep_marcas_pdf", $data);
	}

	function rep_traspasos_tiendas_pdf(){
		$espacio_id=$this->input->post('espacios');
		$fecha1 = $this->input->post('fecha1');
		$fecha2 = $this->input->post('fecha2');
		//Fecha
		if($_POST['fecha1']=="" and strlen($_POST['fecha2'])>0) {
			$hoy=date("Y-m-d");
			$fecha1=date("Y-m-d", strtotime($hoy));
			$fecha=explode(" ", $_POST['fecha2']);
			$fecha2a=$fecha[2]."-".$fecha[1]."-".$fecha[0];
		} else if($_POST['fecha2']=="" and strlen($_POST['fecha1'])>0) {
			$hoy=date("Y-m-d");
			$fecha2=date("Y-m-d", strtotime($hoy)+(24 * 60 * 60));
			$fecha=explode(" ", $_POST['fecha1']);
			$fecha1=$fecha[2]."-".$fecha[1]."-".$fecha[0];
		} else {
			$fecha=explode(" ", $_POST['fecha1']);
			$fecha1=$fecha[2]."-".$fecha[1]."-".$fecha[0];
			$fecha=explode(" ", $_POST['fecha2']);
			$fecha2a=$fecha[2]."-".$fecha[1]."-".$fecha[0];
			$fecha2=date("Y-m-d", strtotime($fecha[2]."-".$fecha[1]."-".$fecha[0]) + (24 * 60 * 60));

		}
		$where =" tt.fecha_salida>='$fecha1' and tt.fecha_salida<'$fecha2' ";

		if($espacio_id!=0){
			$where .=" and tt.espacio_fisico_id=$espacio_id";
		} else {
			$espacios=$this->espacio_fisico->get_espacios_tiendas();
			foreach($espacios->all as $row){
				$espacios_mtrx[]=$row->id;
				$espacios_tag_mtrx[]=$row->tag;
			}
			$espacio_string=implode(", ", $espacios_mtrx);
			$where .= "and tt.espacio_fisico_id in ($espacio_string)";
		}
		//Construir el select
		$traspasos=$this->db->query("select tt.*, s.id, s.cproductos_id, s.cproducto_numero_id, s.cantidad, s.costo_unitario, s.lote_id, p.descripcion, pn.numero_mm, e.id, es.tag as espacio_salida, ee.tag as espacio_recibe from traspasos_tiendas as tt left join salidas as s on s.id=tt.salida_id left join entradas as e on e.id=tt.entrada_id left join cproductos as p on p.id=s.cproductos_id left join cproductos_numeracion as pn on pn.id=s.cproducto_numero_id left join espacios_fisicos as es on es.id=tt.espacio_fisico_id left join espacios_fisicos as ee on ee.id=tt.espacio_fisico_recibe_id where $where and s.estatus_general_id=1 order by tt.espacio_fisico_id, tt.espacio_fisico_recibe_id, tt.fecha_salida, p.descripcion, pn.numero_mm");

		$data['traspasos']=$traspasos;
		$data['fecha1']=$this->input->post('fecha1');;
		$data['fecha2']=$this->input->post('fecha2');;
		$this->load->view("almacen/rep_traspasos_tiendas_pdf", $data);

	}

	function rep_existencias_pdf() {
		global $ruta;
		$data['filename'] = "rep_existencias.pdf";
		$data['fecha'] = "{$_POST['fecha']} {$_POST['hora']}";
		$fecha = date_format(date_create_from_format('d m Y', $_POST['fecha']), 'Y-m-d')." {$_POST['hora']}";
		$where_e = $where_s = '';
		$producto_obj=$this->producto->get_by_id($_POST['producto_id']);
		$_POST['marca_id']=$producto_obj->cmarca_producto_id;
		$_POST['corrida_id']=0;
		if ($_POST['espacio_id'] > 0) {
			$where_e .= " AND e.espacios_fisicos_id = {$_POST['espacio_id']}";
			$where_s .= " AND s.espacios_fisicos_id = {$_POST['espacio_id']}";
			$espacio = new Espacio_fisico($_POST['espacio_id']);
			$data['espacios'][$espacio->id] = $espacio->tag;
		} else {
			$this->load->model('espacio_fisico');
			$data['espacios'] = $this->espacio_fisico->get_espacios_as_array();
		}
		if ($_POST['marca_id'] > 0) {
			$where_e .= " AND p.cmarca_producto_id = {$_POST['marca_id']}";
			$where_s .= " AND p.cmarca_producto_id = {$_POST['marca_id']}";
			$marca = new Marca_producto($_POST['marca_id']);
			$data['marcas'][$marca->id] = $marca->tag;
			if ($_POST['producto_id'] > 0) {
				$where_e .= " AND e.cproductos_id = {$_POST['producto_id']}";
				$where_s .= " AND s.cproductos_id = {$_POST['producto_id']}";
				if ($_POST['corrida_id'] > 0) {
					$where_e .= " AND e.cproducto_numero_id = {$_POST['corrida_id']}";
					$where_s .= " AND s.cproducto_numero_id = {$_POST['corrida_id']}";
				}
			}
		} else {
			$this->load->model('marca_producto');
			$data['marcas'] = $this->marca_producto->get_marcas_as_array();
		}
		$data['marcas'][0] = "Ninguna";
		$sql = "SELECT a.*, TO_CHAR(inventario, 'FM999G999G999') inventario
		FROM (SELECT
		e.espacios_fisicos_id,
		e.cproductos_id,
		e.lote_id,
		e.cproducto_numero_id,
		e.costo_unitario,
		p.cmarca_producto_id,
		p.descripcion,
		n.numero_mm,
		n.id as pn_id,
		SUM(e.cantidad) -
		(SELECT
		CASE WHEN SUM(s.cantidad) > 0 THEN SUM(s.cantidad) ELSE 0 END
		FROM
		salidas s
		WHERE
		s.fecha < '$fecha'
		AND s.estatus_general_id = 1
		AND s.cproductos_id = e.cproductos_id
		AND s.cproducto_numero_id = e.cproducto_numero_id
		AND s.espacios_fisicos_id = e.espacios_fisicos_id
		$where_s)
		inventario
		FROM
		entradas e
		JOIN cproductos p
		ON e.cproductos_id = p.id
		JOIN cproductos_numeracion n
		ON e.cproducto_numero_id = n.id
		WHERE
		e.fecha < '$fecha'
		AND e.estatus_general_id = 1
		$where_e
		GROUP BY
		e.costo_unitario,
		e.espacios_fisicos_id,
		p.cmarca_producto_id,
		e.cproductos_id,
		p.descripcion,
		e.lote_id,
		e.cproducto_numero_id,
		n.numero_mm,
		n.id
		ORDER BY
		e.lote_id, n.numero_mm) a
		WHERE inventario != 0";
		
		
		$movimientos = $this->db->query($sql);
		// <editor-fold desc="Ciclos para orden por producto">
		foreach ($movimientos->result() as $movimiento) {
			$data['movimientos']
			[$movimiento->espacios_fisicos_id]
			[$movimiento->cmarca_producto_id]
			[$movimiento->cproductos_id][] = array(
					'descripcion' => $movimiento->descripcion,
					'l' => $movimiento->lote_id,
					'costo_unitario' => $movimiento->costo_unitario,
					'numero' =>  $movimiento->numero_mm,
					'numero_id'=>$movimiento->pn_id,
					'numero_id' => $movimiento->cproducto_numero_id,
					'inventario' => $movimiento->inventario);
		}
		unset($movimientos);
		// </editor-fold>
		if (!isset($data['movimientos'])) {
			?>
<div class="emptyList">No existen movimientos con esos datos de registro</div>
<?php
return;
		}
		$this->load->view("$ruta/rep_existencias_pdf", $data);
	}

}//Final de la clase

?>
