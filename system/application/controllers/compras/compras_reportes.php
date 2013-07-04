<?php
class Compras_reportes extends Controller {
	//var $usuarioid, $username, $ruta, $controller, $funcion, $subfuncion, $modulos_totales;
	function Compras_reportes()
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
		$this->assetlibpro->add_js('jquery.js');
		$this->assetlibpro->add_js('jquery.autocomplete.js');
		$this->assetlibpro->add_js('jquery.selectboxes.js');
		$this->assetlibpro->add_js('jquery.numberformatter.js');
		$this->assetlibpro->add_js('jquery.AddIncSearch.js');
		$this->assetlibpro->add_js('jquery.validator.js');
		$this->assetlibpro->add_js('jquery.jfield.js');
		$this->assetlibpro->add_js('jquery.date.js');
		$this->assetlibpro->add_js('jquery.form.js');
		$this->assetlibpro->add_js('fancybox/jquery.fancybox-1.3.1.js');
		$this->load->model("proveedor");
		$this->load->model("pr_factura");
		$this->load->model("entrada");
		$this->load->model("pago");
		$this->load->model("cpr_forma_pago");
		$this->load->model("lote");
		$this->load->model("espacio_fisico");
		$this->load->model("cpr_estatus_pedido");
		$this->load->model("pr_pedido");
		$this->load->model("tipo_pago");
		$this->load->model("pr_detalle_pedido");
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
			if ($subfuncion=="rep_proveedor"){
				//Cargar los datos para el formulario
				$data['frames']=1;
				$data['proveedores'] = $this->proveedor->get_proveedores_pdf();
				$this->load->view("compras/rep_proveedor_pdf", $data);
			} if ($subfuncion=="rep_buscador_productos"){
				//Cargar los datos para el formulario
				$data['frames']=1;
				$data['principal']=$ruta."/".$subfuncion;
			}
			else if ($subfuncion=="rep_pr_facturas"){
				$data['frames']=1;
				$data['principal']=$ruta."/".$subfuncion;
				$e=new Empresa();
				$p=new Proveedor();
				$ef=new Espacio_fisico();
				$data['empresas']=$e->select('id,razon_social')->where('estatus_general_id','1')->order_by('razon_social')->get()->all;
				if(count($data['empresas'])==0)
					show_error('No existen empresas');
				$data['espacios']=$ef->select('id,tag')->where('estatus_general_id','1')->order_by('tag')->get()->all;
				if(count($data['espacios'])==0)
					show_error('No existen espacios fisicos');
				$data['proveedores']=$p->select('id,razon_social')->where('estatus_general_id','1')->order_by('razon_social')->get()->all;
				if(count($data['proveedores'])==0)
					show_error('No existen proveedores');
			}
			else if ($subfuncion=="rep_pr_facturas_detalle"){
				$data['frames']=1;
				$data['principal']=$ruta."/".$subfuncion;
				$e=new Empresa();
				$p=new Proveedor();
				$ef=new Espacio_fisico();
				$data['empresas']=$e->select('id,razon_social')->where('estatus_general_id','1')->order_by('razon_social')->get()->all;
				if(count($data['empresas'])==0)
					show_error('No existen empresas');
				$data['espacios']=$ef->select('id,tag')->where('estatus_general_id','1')->order_by('tag')->get()->all;
				if(count($data['espacios'])==0)
					show_error('No existen espacios fisicos');
				$data['proveedores']=$p->select('id,razon_social')->where('estatus_general_id','1')->order_by('razon_social')->get()->all;
				if(count($data['proveedores'])==0)
					show_error('No existen proveedores');
				$data['funcion']=$subfuncion;
			}
			else if ($subfuncion=="rep_compras"){
				//Cargar los datos para el formulario
				//Definir el numero de frames
				$data['frames']=1;
				//Definir la vista
				$data['principal']=$ruta."/".$subfuncion;
				$data['funcion']=$subfuncion;
				//Obtener Datos
				$data['proveedores'] = $this->proveedor->get_proveedores();
				$data['empresas'] = $this->empresa->get_empresas();
				$data['estatus_pedidos'] = $this->cpr_estatus_pedido->get_cpr_estatus_all();
			} else if ($subfuncion=="rep_pagos_pr"){
				//Cargar los datos para el formulario
				//Definir el numero de frames
				$data['frames']=1;
				//Definir la vista
				$data['principal']=$ruta."/".$subfuncion;
				$data['funcion']=$subfuncion;
				//Obtener Datos
				$e=new Empresa();
				$p=new Proveedor();
				$ef=new Espacio_fisico();
				$data['empresas']=$e->select('id,razon_social')->where('estatus_general_id','1')->order_by('razon_social')->get()->all;
				if(count($data['empresas'])==0)
					show_error('No existen empresas');
				$data['espacios']=$ef->select('id,tag')->where('estatus_general_id','1')->order_by('tag')->get()->all;
				if(count($data['espacios'])==0)
					show_error('No existen espacios fisicos');
				$data['proveedores']=$p->select('id,razon_social')->where('estatus_general_id','1')->order_by('razon_social')->get()->all;
				if(count($data['proveedores'])==0)
					show_error('No existen proveedores');
			}
			else if($subfuncion=='rep_diario_compras')
			{
				$data['frames']=1;
				$data['principal']=$ruta."/".$subfuncion;
				$data['funcion']=$subfuncion;
				# seleccionar los principales
				$e=new Empresa();
				$data['empresas']=$e->select('id,razon_social')->where('estatus_general_id','1')->order_by('razon_social')->get()->all;
				if(count($data['empresas'])==0)
					show_error('No existen empresas');
			}
			else if ($subfuncion=="rep_pr_pedidos_pendientes"){
				//Cargar los datos para el formulario
				$data['frames']=1;
				$data['principal']=$ruta."/".$subfuncion;
				$data['espacios']=$this->espacio_fisico->get_espacios_almacenes();
			}

			if($main_view){
				//Llamar a la vista
				$this->load->view("ingreso", $data);
			} else {
				redirect(base_url()."index.php/inicio/logout");
			}
		}
	}
	function rep_pr_facturas_pdf() { // BEGIN function rep_pr_facturas_pdf
		global $ruta;
		//Obtener los datos
		$filtro = array();
		if((int)$_POST['empresa']>0)
			$filtro[] = "fp.empresas_id = ".(int)$_POST['empresa'];
		$fecha1 = $this->input->post('fecha1');
		$fecha2 = $this->input->post('fecha2');
		$data['periodo']=$fecha1. " - " .$fecha2;
		if($fecha1 != false) {
			list($d,$m,$a) = explode(" ",$fecha1);
			$fecha1 = sprintf('%04d-%02d-%02d',$a,$m,$d);
		}
		if($fecha2 != false) {
			list($d,$m,$a) = explode(" ",$fecha2);
			$fecha2 = sprintf('%04d-%02d-%02d',$a,$m,$d);
		}
		$f1 = false;
		$f2 = false;
		if($fecha1 == date("Y-m-d",strtotime($fecha1)))
			$f1 = true;
		if($fecha2 == date("Y-m-d",strtotime($fecha2)))
			$f2 = true;
		if($f1 && $f2){
			if(strcmp($fecha2,$fecha1) > 0)
				$filtro[] = "(fp.fecha >= '$fecha1' and fp.fecha <= '$fecha2')";
			elseif(strcmp($fecha2,$fecha1) < 0)
			$filtro[] = "(fp.fecha >= '$fecha2' and fp.fecha <= '$fecha1')";
			else
				$filtro[] = "fp.fecha = '$fecha1'";
		}
		elseif($f1)
		$filtro[] = "fp.fecha = '$fecha1'";
		elseif($f2)
		$filtro[] = "fp.fecha = '$fecha2'";
		if (count($filtro))
			$where = " and ".implode(" and ", $filtro)." ";
		else
			$where = "";
		$nivel = array();
		$nivel[]=(int)$_POST['nivel1'];
		$nivel[]=(int)$_POST['nivel2'];
		$campos = array();
		foreach ($nivel as $my_key=>$my_value){
			switch($my_value){
				case 1: $campos[] = 'fecha'; break;
				case 2: $campos[] = 'empresa'; break;
			}
		}
		if (count($campos))
			$order_by = " order by ".implode(",", $campos)." ";
		else
			$order_by = "";
		//die(var_dump($order_by));
		# sacar la lista de proveedores
		$this->db->select("id,razon_social as nombre");
		if((int)$this->input->post('proveedor')>0)
			$this->db->where("id",$this->input->post('proveedor'));
		$proveedores=$this->db->get('cproveedores')->result_array();
		if(count($proveedores)==0)
			show_error('No hay proveedores');
		$vacios=array();
		foreach($proveedores as $i=>$prov)
		{
			$sql="SELECT fp.id, fp.fecha, fp.folio_factura AS folio, fp.monto_total AS monto, e.razon_social AS empresa, fp.pr_pedido_id AS pedido, pp.fecha_pago, ef.tag as tipofac FROM pr_facturas AS fp Left Join pr_pedidos AS pp ON fp.pr_pedido_id = pp.id Left Join empresas AS e ON fp.empresas_id = e.id left join estatus_facturas as ef on ef.id=fp.estatus_factura_id where fp.estatus_general_id=1 and fp.cproveedores_id=".$prov['id']." $where $order_by";
			$proveedores[$i]['facturas']=$this->db->query($sql)->result_array();
		}
		foreach($proveedores as $i=>$prov)
		{
			if(count($prov['facturas'])==0)
				$vacios[]=$i;
		}
		foreach($vacios as $v)
			unset($proveedores[$v]);
		$data['title'] = $_POST['title'];
		$data['proveedores']=$proveedores;
		$this->load->view("$ruta/rep_pr_facturas_pdf", $data);
	} // END function rep_pr_facturas_pdf ############################################



	function rep_compras_pdf()
	{ // BEGIN method rep_compras_pdf
		global $ruta;
		//Obtener los datos
		//echo "<pre>";var_dump($_POST);echo "</pre>";exit();
		$proveedor = (int)$this->input->post('proveedor');
		$empresa = (int)$this->input->post('empresa');
		$estatus = (int)$this->input->post('estatus');
		$filtro = array();
		if($proveedor>0)
			$filtro[] = "pr.cproveedores_id = ".$proveedor;
		if($empresa>0)
			$filtro[] = "pr.empresas_id = ".$empresa;
		if($estatus>0)
			$filtro[] = "pr.cpr_estatus_pedido_id = ".$estatus;
		if (count($filtro))
			$where = " where ".implode(" and ", $filtro)." ";
		else
			$where = "";
		$nivel = array();
		$nivel[]=(int)$this->input->post('nivel_1');
		$nivel[]=(int)$this->input->post('nivel_2');
		$nivel[]=(int)$this->input->post('nivel_3');
		$nivel[]=(int)$this->input->post('nivel_4');
		$nivel[]=(int)$this->input->post('nivel_5');
		$nivel[]=(int)$this->input->post('nivel_6');
		$nivel[]=(int)$this->input->post('nivel_7');
		$nivel[]=(int)$this->input->post('nivel_8');
		$nivel[]=(int)$this->input->post('nivel_9');
		$campos = array();
		foreach ($nivel as $my_key=>$my_value){
			switch($my_value){
				case 1: $campos[] = 'empresa'; break;
				case 2: $campos[] = 'proveedor'; break;
				case 3: $campos[] = 'fecha_alta'; break;
				case 4: $campos[] = 'capturista'; break;
				case 5: $campos[] = 'estatus'; break;
				case 6: $campos[] = 'fecha_entrega'; break;
				case 7: $campos[] = 'monto_total'; break;
				case 8: $campos[] = 'forma_pago'; break;
				case 9: $campos[] = 'fecha_pago'; break;
			}
		}
		if (count($campos))
			$order_by = " order by ".implode(",", $campos)." ";
		else
			$order_by = "";
		$data['title'] = $this->input->post('title');
		$data['pedidos']=$this->pr_pedido->get_pr_pedidos_pdf($where,$order_by);
		if(!$data['pedidos'])
			show_error('No hay registros que cumplan los criterios.');
		$this->load->view("$ruta/rep_compras_pdf", $data);
	} // END method rep_compras_pdf #########################################################

	function rep_pr_facturas_detalle_pdf()
	{ // BEGIN function rep_pr_facturas_detalle_pdf
		global $ruta;
		//Obtener los datos
		$filtro = array();
		$filtro[]="pr_facturas.estatus_general_id = 1";
		# filtrar empresa
		if((int)$_POST['empresa']>0)
			$filtro[] = "pr_facturas.empresas_id = ".(int)$_POST['empresa'];
		# agregar el filtro para el espacio fisico
		$lusrs=array();
		if((int)$_POST['espacio']>0)
		{
			# obtener los usuarios del espacio fisico
			$usuarios=$this->db->select('id')->where('espacio_fisico_id',$_POST['espacio'])->get('usuarios')->result_array();
			foreach($usuarios as &$usr)
				$lusrs[]=$usr['id'];
		}
		$filtro[]="pr_facturas.usuario_id in (".implode(',',$lusrs).")";
		# obtener el nombre del espacio
		$espaciof=$this->db->select('tag')->where('id',(int)$_POST['espacio'])->get('espacios_fisicos')->result_array();
		if(count($espaciof)==0)
			show_error('El espacio especificado no existe');
		$espaciof=array_pop($espaciof);
		$data['espacio']=$espaciof['tag'];
		# filtrar proveedor
		if((int)$_POST['proveedor']>0)
			$filtro[] = "pr_facturas.cproveedores_id = ".(int)$_POST['proveedor'];
		$fecha1 = $this->input->post('fecha1');
		$fecha2 = $this->input->post('fecha2');
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
		if($fecha1 == date("Y-m-d",strtotime($fecha1)))
			$f1 = true;
		if($fecha2 == date("Y-m-d",strtotime($fecha2)))
			$f2 = true;
		if($f1 && $f2){
			if(strcmp($fecha2,$fecha1) > 0)
				$filtro[] = "(pr_facturas.fecha >= '$fecha1' and pr_facturas.fecha <= '$fecha2')";
			elseif(strcmp($fecha2,$fecha1) < 0)
			$filtro[] = "(pr_facturas.fecha >= '$fecha2' and pr_facturas.fecha <= '$fecha1')";
			else
				$filtro[] = "pr_facturas.fecha = '$fecha1'";
		}
		elseif($f1)
		$filtro[] = "pr_facturas.fecha = '$fecha1'";
		elseif($f2)
		$filtro[] = "pr_facturas.fecha = '$fecha2'";
		if (count($filtro))
			$where = " where ".implode(" and ", $filtro)." ";
		else
			$where = "";
		$nivel = array();
		$nivel[]=(int)$_POST['nivel1'];
		$nivel[]=(int)$_POST['nivel2'];
		$campos = array();
		foreach ($nivel as $my_key=>$my_value){
			switch($my_value){
				case 1: $campos[] = 'fecha'; break;
				case 2: $campos[] = 'proveedor'; break;
			}
		}
		if (count($campos))
			$order_by = " order by ".implode(",", $campos)." ";
		else
			$order_by = "";
		$data['title'] = $_POST['title'];
		$sql="SELECT pr_facturas.pr_pedido_id,pr_facturas.folio_factura,pr_facturas.fecha,pr_facturas.fecha_captura,usuarios.nombre AS capturista,empresas.razon_social AS empresa,cproveedores.razon_social AS proveedor,pr_facturas.monto_total FROM soleman.empresas LEFT JOIN soleman.pr_facturas ON (empresas.id = pr_facturas.empresas_id) LEFT JOIN soleman.cproveedores ON (cproveedores.id = pr_facturas.cproveedores_id) LEFT JOIN soleman.usuarios ON (usuarios.id = pr_facturas.usuario_id) $where $order_by";
		$data['facturas']=$this->db->query($sql)->result();
		# obtener los detalles de los pedidos
		$data['detalles'] = array();
		$vacios=array();
		foreach($data['facturas'] as $factura)
		{
			$sql="SELECT pr_detalle_pedidos.cantidad,cproductos.descripcion AS producto,pr_detalle_pedidos.costo_unitario,pr_detalle_pedidos.costo_total,pr_detalle_pedidos.tasa_impuesto FROM soleman.cproductos LEFT JOIN soleman.pr_detalle_pedidos ON (cproductos.id = pr_detalle_pedidos.cproductos_id) WHERE pr_detalle_pedidos.estatus_general_id = 1 AND pr_detalle_pedidos.pr_pedidos_id = {$factura->pr_pedido_id}";
			$data['detalles'][$factura->pr_pedido_id] = $this->db->query($sql)->result();
			if(count($data['detalles'][$factura->pr_pedido_id])==0)
				$vacios[]=$factura->pr_pedido_id;
		}
		# eliminar los que no tienen facturas
		foreach($vacios as $v)
			unset($data['detalles'][$v]);
		if(count($data['facturas'])==0)
			show_error('No hay registros que cumplan los criterios.');
		if($f1 || $f2)
		{
			if($f1 && !$f2) $fecha2=$fecha1;
			if(!$f1 && $f2) $fecha1=$fecha2;
			$fecha1=date("d-m-Y",strtotime($fecha1));
			$fecha2=date("d-m-Y",strtotime($fecha2));
			$data['periodo']=$fecha1.' a '.$fecha2;
		}
		else
			$data['periodo']='A LA FECHA '.date("d-m-Y");
		$this->load->view("$ruta/rep_pr_facturas_detalle_pdf", $data);
	} // END function rep_pr_facturas_detalle_pdf ############################################


	function rep_pedido_compra(){
		$id=$this->uri->segment(4);
		if(is_numeric($id)==false)
			show_error('Error 1: El número de pedido de compra no existe'.$id);

		$data['title']="Orden de Compra";
		$data['generales']=$this->pr_pedido->get_pr_pedidos_pdf(" where pr.id='$id'", "");
		if($data['generales']==false)
			show_error('El Pedido de compra no existe');

		$data['detalles']=$this->pr_detalle_pedido->get_pr_detalles_pedido_pdf($id);
		if($data['generales']==false)
			show_error('El pedido de compra no tiene detalles en su pedido');

		$this->load->view("compras/rep_pr_pedido", $data);
	}
	
	function rep_pr_factura_pdf() {
		$id=$this->uri->segment(4);
					$factura=$this->pr_factura->get_by_id($id);
			$validada=$factura->usuario_validador_id;
		if(is_numeric($id)==false)
			show_error("Error 1: El Id de Factura = $id no existe");

		$data['title']="Orden de Entrada por Facturación";
		$data['generales']=$this->pr_factura->get_pr_factura_pdf($id);
		if($data['generales']==false)
			show_error('Error 2 La Orden de Entrada no existe');

		$data['detalles']=$this->entrada->get_entrada_pr_factura($id,$validada);
		if($data['generales']==false){
			show_error('Error 3: La Orden de Entrada no contiene detalles en su pedido');
			//			$this->db->query("delete from cl_pedidos where id='$id'");
			//			$this->db->query("delete from cl_detalle_pedidos where cl_pedidos_id='$id'");
		}
		$this->load->view("compras/rep_pr_factura", $data);
	}

	function rep_pagos_pr_pdf()
	{ // BEGIN method rep_pagos_prov_pdf 14/05/2010
		global $ruta;
		# Obtener los datos
		# obtener el nombre de la empresa
		$empresa=$this->db->select('razon_social')->where('id',(int)$_POST['empresa'])->get('empresas')->result_array();
		if(count($empresa)==0)
			show_error('La empresa especificada no existe');
		$empresa=array_pop($empresa);
		$data['empresa']=$empresa['razon_social'];
		# obtener el nombre del espacio
		$espaciof=$this->db->select('tag')->where('id',(int)$_POST['espacio'])->get('espacios_fisicos')->result_array();
		if(count($espaciof)==0)
			show_error('El espacio especificado no existe');
		$espaciof=array_pop($espaciof);
		$data['espacio']=$espaciof['tag'];
		# obtener los datos de las cuentas
		$data['cuentas']=array();
		$cuentas=$this->db->select('id,banco,numero_cuenta')->get('ccuentas_bancarias')->result_array();
		if(count($cuentas)==0)
			show_error('No existen cuentas registradas');
		foreach($cuentas as &$cuenta)
			$data['cuentas'][$cuenta['id']]=array('banco'=>$cuenta['banco'],'cuenta'=>$cuenta['numero_cuenta']);
		# filtrar empresa
		$filtro = array();
		//$filtro[]="pagos.estatus_general_id = 1"; # 15/05/2010 18:24:26 no tiene el campo
		$filtro[]="usuarios.empresas_id = '{$_POST['empresa']}'";
		# filtrar espacio fisico
		$filtro[]="usuarios.espacio_fisico_id = '{$_POST['espacio']}'";
		# filtrar fecha
		$fecha1 = $this->input->post('fecha1');
		$fecha2 = $this->input->post('fecha2');
		$campof='pagos.fecha';
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
		if($fecha1 == date("Y-m-d",strtotime($fecha1)))
			$f1 = true;
		if($fecha2 == date("Y-m-d",strtotime($fecha2)))
			$f2 = true;
		if($f1 && $f2){
			if(strcmp($fecha2,$fecha1) > 0)
				$filtro[] = "($campof >= '$fecha1' and $campof <= '$fecha2')";
			elseif(strcmp($fecha2,$fecha1) < 0)
			$filtro[] = "($campof >= '$fecha2' and $campof <= '$fecha1')";
			else
				$filtro[] = "$campof = '$fecha1'";
		}
		elseif($f1)
		$filtro[] = "$campof = '$fecha1'";
		elseif($f2)
		$filtro[] = "$campof = '$fecha2'";
		# formar cadena para el WHERE
		if (count($filtro))
			$where = implode(" and ", $filtro);
		else
			$where = "";
		# formar cadena de orden
		$nivel = array();
		$nivel[]=(int)$_POST['nivel_1'];
		$nivel[]=(int)$_POST['nivel_2'];
		$campos = array();
		foreach ($nivel as $niv){
			switch($niv){
				case 1: $campos[] = 'fecha'; break;
				case 2: $campos[] = 'monto_pagado desc'; break;
			}
		}
		if (count($campos))
			$order_by = " ORDER BY ".implode(",", $campos)." ";
		else
			$order_by = "";
		# obtener la lista de proveedores requeridos
		$this->db->select('id,razon_social as nombre');
		if((int)$_POST['proveedor']>0)
			$this->db->where('id',(int)$_POST['proveedor']);
		$proveedores=$this->db->order_by('nombre')->get('cproveedores')->result_array();
		#obtener los datos para cada proveedor
		$n=0;
		foreach($proveedores as &$proveedor)
		{
			$sql="SELECT pagos.id, DATE_FORMAT(pagos.fecha,'%d-%m-%Y') AS fecha, pagos.cuenta_origen_id, pagos.cuenta_destino_id, pagos.numero_referencia, pagos.monto_pagado, pr_facturas.folio_factura, usuarios.nombre FROM soleman.pr_facturas LEFT JOIN soleman.pagos ON (pr_facturas.id = pagos.pr_factura_id) LEFT JOIN soleman.usuarios ON (usuarios.id = pagos.usuario_id) WHERE (pagos.id > 0 AND pr_facturas.cproveedores_id = '".$proveedor['id']."' AND $where)  $order_by";
			$proveedor['pagos']= $this->db->query($sql)->result_array();
			$n+=count($proveedor['pagos']);
		}
		if($n==0)
			show_error("No hay datos que cumplan con los criterios");
		# periodo del reporte
		if($f1 || $f2)
		{
			if($f1 && !$f2) $fecha2=$fecha1;
			if(!$f1 && $f2) $fecha1=$fecha2;
			$fecha1=date("d-m-Y",strtotime($fecha1));
			$fecha2=date("d-m-Y",strtotime($fecha2));
			$data['periodo']=$fecha1.' a '.$fecha2;
		}
		else
			$data['periodo']='A LA FECHA '.date("d-m-Y");
		# pasar datos faltantes a la vista y cargar la vista
		$data['proveedores']=$proveedores;
		$data['n']=$n;
		$data['title']=$this->input->post('title');
		$this->load->view("$ruta/rep_pagos_pr_pdf", $data);
	} // END method rep_pagos_prov_pdf #########################################################

	function rep_diario_compras_pdf()
	{ // BEGIN method rep_diario_compras_pdf 26/05/2010
		//echo "<pre>";var_dump($_POST);echo "</pre>";die();
		global $ruta;
		# Obtener los datos de la empresa
		$e=new Empresa();
		$e->get_by_id((int)$_POST['empresa']);
		if(!$e->exists()) show_error('La empresa no existe');
		# Obtener los datos del espacio
		$ef=new Espacio_Fisico();
		$ef->get_by_id((int)$_POST['espacio']);
		if(!$ef->exists()) show_error('La empresa no existe');
		# filtrar fecha
		$fecha1 = $this->input->post('fecha1');
		$fecha2 = $this->input->post('fecha2');
		$campof='pr_facturas.fecha';
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
		if($fecha1 == date("Y-m-d",strtotime($fecha1)))
			$f1 = true;
		if($fecha2 == date("Y-m-d",strtotime($fecha2)))
			$f2 = true;
		if($f1 && $f2){
			if(strcmp($fecha2,$fecha1) > 0)
				$filtro[] = "($campof >= '$fecha1' and $campof <= '$fecha2')";
			elseif(strcmp($fecha2,$fecha1) < 0)
			$filtro[] = "($campof >= '$fecha2' and $campof <= '$fecha1')";
			else
				$filtro[] = "$campof = '$fecha1'";
		}
		elseif($f1)
		$filtro[] = "$campof = '$fecha1'";
		elseif($f2)
		$filtro[] = "$campof = '$fecha2'";
		# filtrar empresa y espacio
		$filtro[]="pr_facturas.empresas_id = '{$e->id}'";
		$filtro[]="usuarios.espacio_fisico_id = '{$ef->id}'";
		# filtrar solo las habilitadas
		$filtro[]="pr_facturas.estatus_general_id = '1'";
		# formar cadena para el WHERE
		if (count($filtro))
			$where = implode(" and ", $filtro);
		else
			$where = "";
		# obtener los datos para el reporte
		$data=array();
		$sql="SELECT pr_facturas.id, DATE_FORMAT(pr_facturas.fecha,'%d-%m-%Y') AS fecha, cproveedores.razon_social AS proveedor, pr_facturas.iva_total, pr_facturas.monto_total FROM soleman.pr_facturas LEFT JOIN soleman.cproveedores ON (pr_facturas.cproveedores_id = cproveedores.id) INNER JOIN soleman.usuarios ON (pr_facturas.usuario_id = usuarios.id) WHERE ( $where ) ORDER BY fecha,proveedor";
		$data['diario']=$this->db->query($sql)->result_array();
		if(count($data['diario'])==0)
			show_error('No hay datos que complan los criterios');
		# establecer los datos para la vista
		if($f1 || $f2)
		{
			if($f1 && !$f2) $fecha2=$fecha1;
			if(!$f1 && $f2) $fecha1=$fecha2;
			$fecha1=date("d-m-Y",strtotime($fecha1));
			$fecha2=date("d-m-Y",strtotime($fecha2));
			$data['periodo']=$fecha1.' a '.$fecha2;
		}
		else
			$data['periodo']='A LA FECHA '.date("d-m-Y");
		$data['title']=$this->input->post('title');
		$data['empresa']=$e;
		$data['espacio']=$ef;
		$this->load->view("$ruta/rep_diario_compras_pdf",$data);
	} // END method rep_diario_compras_pdf #########################################################

	function rep_etiquetas_codigo_barras_pdf(){
		//Generar el PDF
		$path="/var/www/bebe/tmp/";
		$this->load->plugin('barcode');
	
		$id=$this->uri->segment(4);
		$x=1;
		$productos=$this->entrada->get_entrada_pr_factura($id,$x);
		//Recorrer los productos
		
        $data['pr_factura']=$id;
		foreach($productos->all as $row){
			$char=strlen($row->cproducto_numero_id);
			$cproducto_num_id=$row->cproducto_numero_id;
			$cproducto_id=$row->cproductos_id;
			$numero=$row->codigo_barras;
			
			for($y=1;$y<=$row->cantidad;$y++){
				//barcode_create("$numero","code128","jpeg", 'cb_'.$cproducto_id, $path);
				barcode_create("$numero","code128","jpeg", 'cb_'.$cproducto_num_id, $path);               
				$codigos[$x]['codigo']=$numero;
				$codigos[$x]['ruta']=$path."cb_".$cproducto_num_id.".jpeg";
				$codigos[$x]['descripcion']=$row->descripcion."# ".($row->numero_mm);
                $codigos[$x]['precio']=$row->precio1;
				$x+=1;
			}
		}
		//Obtener el Nombre de la Sucursal
		
		$data['detalles']=$codigos;
		$this->load->library("fpdf_factura");
		$this->load->view('compras/rep_etiquetas_codigo_barras_pdf', $data);
	}
	
	function rep_pr_pedidos_pendientes_pdf() { // BEGIN method rep_productos_pdf
		global $ruta;
		//Obtener los datos
		$espacio = $this->input->post('espacio');
		$proveedor = $this->input->post('proveedor');
		$marca_id = $this->input->post('cmarca_id');
		$marca = $this->input->post('marca_drop');

		if($proveedor>0) {
			$proveedor=$this->proveedor->get_by_id($espacio);
			$nombre_proveedor=$proveedor->razon_social;
		} else {
			//Primero obtener los proveedores
			$nombre_proveedor="Todos los Proveedores";
			//Obtener proveedores cuando son TODOS
			$proveedor=$this->proveedor->get_proveedores_hab();
		}

		if($espacio>0) {
			$espacios_f=$this->espacio_fisico->get_by_id($espacio);
			$nombre_espacio=$espacio->tag;
		} else {
			//Primero obtener los espacios fisicos
			$nombre_espacio="Todas las Tiendas";
			//Obtener espacios fisicos cuando son TODOS
			$espacios_f=$this->espacio_fisico->get_espacios_almacenes();
		}


		$s=0; $matriz_p=array();
		foreach($espacios_f->all as $list) {
			$sucursales_id[$s]=$list->id;
			$sucursales_tag[$list->id]=$list->tag;
			//Obtener el query por espacio fisico y asignarlo a una matriz de PHP
			//OBtener los pedidos
			$pedidos=$this->pr_pedido->get_pedidos_sucursal($list->id,2,$and); //EStatus 2 de Autorizado y en espera

			foreach($datos->result() as $renglon){
				//Asignar los datos a la matriz
				// 				$matriz_p[$renglon->espacios_fisicos_id]['tag']=$list->tag;
				$matriz_p[$renglon->espacios_fisicos_id][$renglon->cproductos_id]['descripcion']=$renglon->descripcion;
				$matriz_p[$renglon->espacios_fisicos_id][$renglon->cproductos_id]['producto_id']=$renglon->cproductos_id;
				$matriz_p[$renglon->espacios_fisicos_id][$renglon->cproductos_id]['numero_'.$renglon->numero_mm]=$renglon->pares;
				unset($renglon);
			}
			$s+=1;
		}
		//print_r($matriz_p);
		$title="Reporte de Resurtidos por Marca: '$marca' ";
		$data['title']=$title;
		$data['sucursales_tag']=$sucursales_tag;
		$data['sucursales_id']=$sucursales_id;
		$data['datos']=$matriz_p;
		$data['orientation']="L";
		$this->load->view("$ruta/rep_resurtidos_marcas_pdf", $data);
		unset($data);
	}

	function rep_buscador_productos_html(){
		$producto_id=$this->input->post('producto');
		$producto=$this->input->post('tag_producto');
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
		$where_p =" where pr.fecha_alta>='$fecha1' and pr.fecha_alta<'$fecha2'";

		if($producto_id==0)
			show_error("Seleccione un producto válido");
		$pedidos=$this->db->query("select distinct(pr_pedidos_id) as pr_pedido_id from pr_detalle_pedidos as p  where cproductos_id=$producto_id  group by pr_pedidos_id");
		if($pedidos->num_rows()>0){
			$pedidos_id=array();
			foreach($pedidos->result() as $row){
				$pedidos_id[]=$row->pr_pedido_id;
			}
			$list_pedidos=implode(', ',$pedidos_id);
		} else
			show_error("No existen pedidos con el producto: $producto");
		$where_p .=" and pr.id in ($list_pedidos) ";
		if($_POST['espacio_fisico_id']!=0){
			$where_p .=" and pr.espacio_fisico_id='{$_POST['espacio_fisico_id']}'";
			//show_error($_POST['espacio_fisico_id']);
		}

		$pedidos=$this->pr_pedido->get_rep_pedidos($where_p);

		//id tienda proveedor marca status link pdf
		$data['title']= "BUSQUEDA DE PRODUCTOS EN TRÁNSITO : '".$producto."'";
		$data['pedidos']=$pedidos;
		$data['fecha1']=$_POST['fecha1'];
		$data['fecha2']=$_POST['fecha2'];
		$this->load->view("compras/rep_buscador_productos_html", $data);
		unset($data);


	}
}
?>
