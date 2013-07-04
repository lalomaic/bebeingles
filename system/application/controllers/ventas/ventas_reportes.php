<?php
class Ventas_reportes extends Controller {
	//var $usuarioid, $username, $ruta, $controller, $funcion, $subfuncion, $modulos_totales;

	function Ventas_reportes()
	{
		parent::Controller();
		if($this->session->userdata('logged_in') == FALSE){
			redirect(base_url()."index.php/inicio/logout");
		}
		$this->assetlibpro->add_css('default.css');
		$this->assetlibpro->add_css('menu_style.css');
		$this->assetlibpro->add_css('date_input.css');
		$this->assetlibpro->add_css('jquery.validator.css');
		/*		$this->assetlibpro->add_css('fancybox/jquery.fancybox-1.3.1.css');
	  $this->assetlibpro->add_css('style_fancy.css.css');*/
		$this->assetlibpro->add_js('jquery.js');
		/*		$this->assetlibpro->add_js('jquery.autocomplete.js.js');
	  $this->assetlibpro->add_js('jquery.selectboxes.js');
		$this->assetlibpro->add_js('jquery.select-autocomplete.js');
		$this->assetlibpro->add_js('jquery.numberformatter.js');
		$this->assetlibpro->add_js('jquery.AddIncSearch.js');*/
		$this->assetlibpro->add_js('jquery.validator.js');
		$this->assetlibpro->add_js('jquery.jfield.js');
		$this->assetlibpro->add_js('jquery.date.js');
		$this->assetlibpro->add_js('jquery.form.js');
		/*		$this->assetlibpro->add_js('fancybox/jquery.mousewheel-3.0.2.pack.js');*/
		$this->assetlibpro->add_js('fancybox/jquery.fancybox-1.3.1.js');
		$this->load->model("espacio_fisico");
		$this->load->model("cl_pedido");
		$this->load->model("cl_detalle_pedido");
		$this->load->model("salida");
		$this->load->model("cliente");
		$this->load->model("cl_factura");
		$this->load->model("tipo_cobro");
		$this->load->model("forma_cobro");
		$user_hash=$this->session->userdata('user_data');
		$row=$this->usuario->get_usuario_detalles($user_hash);
		$GLOBALS['usuarioid']=$row->id;
                $GLOBALS['espacio_fisico_id'] = $row->espacio_fisico_id;
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
		global $main_menu, $usuarioid, $username, $ruta, $controller,$funcion,$subfuncion,$modulos_totales;

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
		$data['permisos']=$this->usuario_accion->get_permiso($accion_id,$usuarioid,$puestoid,$grupoid);

		//Validacion del arreglo del menu,usuarioid y permisos especificos
		if(is_array($data['colect1']) and $usuarioid>0 and $data['permisos']!= false and $this->session->userdata('logged_in') == TRUE){

			$main_view=true;
			if($subfuncion == 'rep_clientes')
			{
				$c=new Cliente();
				$c->get();
				$data['clientes']=$c;
				$this->load->view("ventas/rep_clientes_pdf",$data);
			}
			if ($subfuncion=="rep_cl_facturas"){
				//Cargar los datos para el formulario
				$data['frames']=1;
				$data['principal']=$ruta."/".$subfuncion;
				$data['funcion']=$subfuncion;
				$e=new Empresa();
				$c=new Cliente();
				$ef=new Espacio_fisico();
				$data['empresas']=$e->select('id,razon_social')->where('estatus_general_id','1')->order_by('razon_social')->get()->all;
				if(count($data['empresas'])==0)
					show_error('No existen empresas');
				$data['espacios']=$ef->select('id,tag')->where('estatus_general_id','1')->order_by('tag')->get()->all;
				if(count($data['espacios'])==0)
					show_error('No existen espacios fisicos');
				$data['clientes']=$c->select('id,razon_social')->where('estatus_general_id','1')->order_by('razon_social')->get()->all;
				if(count($data['clientes'])==0)
					show_error('No existen clientes');
			}
			if ($subfuncion=="rep_cl_facturas_detalle"){
				//Cargar los datos para el formulario
				$data['frames']=1;
				$data['principal']=$ruta."/".$subfuncion;
				$data['funcion']=$subfuncion;
				$e=new Empresa();
				$c=new Cliente();
				$ef=new Espacio_fisico();
				$data['empresas']=$e->select('id,razon_social')->where('estatus_general_id','1')->order_by('razon_social')->get()->all;
				if(count($data['empresas'])==0)
					show_error('No existen empresas');
				$data['espacios']=$ef->select('id,tag')->where('estatus_general_id','1')->order_by('tag')->get()->all;
				if(count($data['espacios'])==0)
					show_error('No existen espacios fisicos');
				$data['clientes']=$c->select('id,razon_social')->where('estatus_general_id','1')->order_by('razon_social')->get()->all;
				if(count($data['clientes'])==0)
					show_error('No existen clientes');

			} else if($subfuncion=="rep_tickets") {
				//Cargar los datos para el formulario
				$data['frames']=1;
				$data['principal']=$ruta."/".$subfuncion;
				$data['funcion']=$subfuncion;
				// seleccionar los principales
				$e=new Empresa();
				$data['empresas']=$e->select('id,razon_social')->order_by('razon_social')->get()->all;
				if(count($data['empresas'])==0)
					show_error('No existen empresas');
			} else 	if($subfuncion=="rep_tickets_vol"){
				//Cargar los datos para el formulario
				$data['frames']=1;
				$data['principal']=$ruta."/".$subfuncion;
				$data['funcion']=$subfuncion;
				// seleccionar los principales
				$e=new Empresa();
				$data['empresas']=$e->select('id,razon_social')->order_by('razon_social')->get()->all;
				if(count($data['empresas'])==0)
					show_error('No existen empresas');
			} else if ($subfuncion=="rep_diario_ventas"){
				//Cargar los datos para el formulario
				$data['title']="DIARIO DE VENTAS";
				$data['principal']=$ruta."/".$subfuncion;
				$data['funcion']=$subfuncion;
				$this->db->select("id,tag")->order_by("tag");
				$this->db->where('estatus_general_id',1);
				$data['espacios']=$this->db->get('espacios_fisicos')->result();
				if (count($data['espacios'])==0)
					show_error('No existen Espacios Fisicos');
				if($this->uri->segment(5)=="pdf"){
					$this->db->select("tag");
					$this->db->where('id',$_POST['espacio']);
					$espacio=$this->db->get('espacios_fisicos')->result();
					$f=date("Y-m-d");
					$periodo='';
					$fecha = $this->input->post('fecha');
					if($fecha != false){
						list($d,$m,$a) = explode(" ",$fecha);
						$fecha = sprintf('%04d-%02d-%02d',$a,$m,$d);
						$periodo = "AND f.fecha = '$fecha'";
						$data['fecha']=$fecha;
					}
					else{
						$periodo = "AND f.fecha = '$f'";
						$data['fecha']=$f;
					}
					if ($this->input->post('espacio')>0){
						$where=" where ef.id=$_POST[espacio] ";
						$data['espacio']=$espacio[0]->tag;
					}

					else{
						$where="where f.estatus_factura_id in (1,2)";
						$data['espacio']="";
					}
					$where=$where." and f.espacios_fisicos_id=$_POST[espacio]";
						

					$sql="SELECT f.id AS id_factura, f.folio_factura AS factura, cc.razon_social AS cliente, cc.clave AS clave, f.monto_total, ef.tag AS espacio, f.fecha AS fecha, e.id AS estatus_factura, eg.id AS estatus_general, f.tipo_factura_id as tipof FROM cl_facturas AS f LEFT JOIN cclientes AS cc ON cc.id = f.cclientes_id LEFT JOIN usuarios AS u ON u.id = f.usuario_id	LEFT JOIN espacios_fisicos AS ef ON ef.id = u.espacio_fisico_id LEFT JOIN estatus_facturas AS e ON e.id = f.estatus_factura_id LEFT JOIN estatus_general AS eg ON eg.id = f.estatus_general_id $where $periodo order by factura, fecha";
					$facturas=$this->db->query($sql)->result();
					if(count($facturas)==0)
						show_error('No hay registros que cumplan los criterios.');
					$data['facturas']=$facturas;
					$data['total_registros']=count($facturas);
					$data['title'] = $this->input->post('title');
					$this->load->view("$ruta/rep_diario_ventas_pdf", $data);
				}
					
			} if ($subfuncion=="rep_ventas"){
				//Cargar los datos para el formulario
				$data['title']="VENTAS FACTURADAS";
				$data['principal']=$ruta."/".$subfuncion;
				$data['funcion']=$subfuncion;
				$this->db->select("id,tag")->order_by("tag");
				$this->db->where('estatus_general_id',1);
				$data['espacios']=$this->db->get('espacios_fisicos')->result();
				if (count($data['espacios'])==0)
					show_error('No existen Espacios Fisicos');
				if($this->uri->segment(5)=="pdf"){
					$this->db->select("tag");
					$this->db->where('id',$_POST['espacio']);
					$espacio=$this->db->get('espacios_fisicos')->result();
					$f=date("Y-m-d");
					$periodo='';
					$data['fech']=false;
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
						if(strcmp($fecha2,$fecha1) > 0){
							$periodo = "(f.fecha between '$fecha1' and '$fecha2')";
							$data['fech']=true;
							//$fech2=$fecha2;
						}
							
						elseif(strcmp($fecha2,$fecha1) < 0){
							$periodo = "(f.fecha between '$fecha2' and '$fecha1')";
							$data['fech']=true;
							//$fech2=$fecha1;
						}
							
						else
						{
							$periodo = "f.fecha = '$fecha1'";
							//$fech1=$fecha1;
						}

					}
					elseif($f1){
						$periodo = "f.fecha = '$fecha1'";
						//$fech1=$fecha1;
					}

					elseif($f2){
						$periodo = "f.fecha = '$fecha2'";
						//$fech1=$fecha2;
					}
					if($f1 || $f2)
						$periodo='AND '.$periodo;
					else
						$periodo="AND f.fecha = '".date("Y-m-d")."'";
					if ($this->input->post('espacio')>0){
						$where=" where f.estatus_factura_id in (1,2,3) and f.tipo_factura_id<3 and f.estatus_general_id=1 and f.espacios_fisicos_id=$_POST[espacio] ";
						$data['espacio']=$espacio[0]->tag;
					} else {
						$where="where f.estatus_factura_id in (1,2,3) and f.tipo_factura_id<3 and f.estatus_general_id=1";
						$data['espacio']="";
					}
					//$where=$where." and f.espacios_fisicos_id=$_POST[espacio]";


					$sql="SELECT f.id AS id_factura, f.folio_factura AS factura, cc.razon_social AS cliente, cc.clave AS clave, f.monto_total, f.iva_total, ef.tag AS espacio, f.fecha AS fecha, e.id AS estatus_factura, eg.id AS estatus_general FROM cl_facturas AS f LEFT JOIN cclientes AS cc ON cc.id = f.cclientes_id LEFT JOIN usuarios AS u ON u.id = f.usuario_id	LEFT JOIN espacios_fisicos AS ef ON ef.id = f.espacios_fisicos_id LEFT JOIN estatus_facturas AS e ON e.id = f.estatus_factura_id LEFT JOIN estatus_general AS eg ON eg.id = f.estatus_general_id $where $periodo order by fecha, factura";
					$facturas=$this->db->query($sql)->result();
					//echo $sql;exit;
					if(count($facturas)==0)
						show_error('No hay registros que cumplan los criterios.');
					$data['facturas']=$facturas;
					$data['total_registros']=count($facturas);
					if($f1 || $f2){
						if($f1 && !$f2)
							$data['periodo']='DEL DÃA '.implode("-", array_reverse(explode("-", $fecha1)));
						elseif(!$f1 && $f2)
						$data['periodo']='DEL DÃA '.implode("-", array_reverse(explode("-", $fecha2)));
						elseif($fecha1==$fecha2)
						$data['periodo']='DEL DÃA '.implode("-", array_reverse(explode("-", $fecha1)));
						else
							$data['periodo']='DEL '.implode("-", array_reverse(explode("-", $fecha1))).' AL '.implode("-", array_reverse(explode("-", $fecha2)));
					}
					else
						$data['periodo']='DEL DÃA '.date("d-m-Y");
					$data['title'] = $this->input->post('title');
					$this->load->view("$ruta/rep_ventas_pdf", $data);
				}
					
			}

			else if ($subfuncion=="rep_cobros_cl"){
				//Cargar los datos para el formulario
				$data['frames']=1;
				$data['principal']=$ruta."/".$subfuncion;
				$data['funcion']=$subfuncion;
				$e=new Empresa();
				$c=new Cliente();
				$ef=new Espacio_fisico();
				$data['empresas']=$e->select('id,razon_social')->where('estatus_general_id','1')->order_by('razon_social')->get()->all;
				if(count($data['empresas'])==0)
					show_error('No existen empresas');
				$data['espacios']=$ef->select('id,tag')->where('estatus_general_id','1')->order_by('tag')->get()->all;
				if(count($data['espacios'])==0)
					show_error('No existen espacios fisicos');
				$data['clientes']=$c->select('id,razon_social')->where('estatus_general_id','1')->order_by('razon_social')->get()->all;
				if(count($data['clientes'])==0)
					show_error('No existen clientes');
			} if ($subfuncion=="rep_cobros_diarios_cl"){
				//Cargar los datos para el formulario
				$data['frames']=1;
				$data['principal']=$ruta."/".$subfuncion;
				$data['funcion']=$subfuncion;
				$e=new Empresa();
				$c=new Cliente();
				$ef=new Espacio_fisico();
				$data['empresas']=$e->select('id,razon_social')->where('estatus_general_id','1')->order_by('razon_social')->get()->all;
				if(count($data['empresas'])==0)
					show_error('No existen empresas');
				$data['espacios']=$ef->select('id,tag')->where('estatus_general_id','1')->order_by('tag')->get()->all;
				if(count($data['espacios'])==0)
					show_error('No existen espacios fisicos');
				$data['clientes']=$c->select('id,razon_social')->where('estatus_general_id','1')->order_by('razon_social')->get()->all;
				if(count($data['clientes'])==0)
					show_error('No existen clientes');
			}
			if ($subfuncion=="rep_facturas_canceladas"){
				//Cargar los datos para el formulario
				$data['title']="VENTAS CANCELADAS";
				$data['principal']=$ruta."/".$subfuncion;
				$data['funcion']=$subfuncion;
				$this->db->select("id,tag")->order_by("tag");
				$this->db->where('estatus_general_id',1);
				$data['espacios']=$this->db->get('espacios_fisicos')->result();
				if (count($data['espacios'])==0)
					show_error('No existen Espacios Fisicos');
				if($this->uri->segment(5)=="pdf"){
					$this->db->select("tag");
					$this->db->where('id',$_POST['espacio']);
					$espacio=$this->db->get('espacios_fisicos')->result();
					//$fech1='';
					//$fech2='';
					$data['fech']=false;
					$periodo='';
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
						if(strcmp($fecha2,$fecha1) > 0){
							$periodo = "(f.fecha between '$fecha1' and '$fecha2')";
							$data['fech']=true;
							//$fech2=$fecha2;
						}
							
						elseif(strcmp($fecha2,$fecha1) < 0){
							$periodo = "(f.fecha between '$fecha2' and '$fecha1')";
							$data['fech']=true;
							//$fech2=$fecha1;
						}
							
						else
						{
							$periodo = "f.fecha = '$fecha1'";
							//$fech1=$fecha1;
						}

					}
					elseif($f1){
						$periodo = "f.fecha = '$fecha1'";
						//$fech1=$fecha1;
					}

					elseif($f2){
						$periodo = "f.fecha = '$fecha2'";
						//$fech1=$fecha2;
					}
					if($f1 || $f2)
						$periodo='AND '.$periodo;
					else
						$periodo="AND f.fecha = '".date("Y-m-d")."'";
					if ($this->input->post('espacio')>0){
						$where=" where f.estatus_general_id=2 and ef.id=$_POST[espacio] ";
						$data['espacio']=$espacio[0]->tag;
					}

					else{
						$where="where f.estatus_general_id=2";
						$data['espacio']="";
					}


					$sql="SELECT f.id AS id_factura, f.folio_factura AS factura, cc.razon_social AS cliente, cc.clave AS clave, f.monto_total, ef.tag AS espacio, f.fecha AS fecha, e.id AS estatus_factura, eg.id AS estatus_general FROM cl_facturas AS f LEFT JOIN cclientes AS cc ON cc.id = f.cclientes_id LEFT JOIN usuarios AS u ON u.id = f.usuario_id	LEFT JOIN espacios_fisicos AS ef ON ef.id = u.espacio_fisico_id LEFT JOIN estatus_facturas AS e ON e.id = f.estatus_factura_id LEFT JOIN estatus_general AS eg ON eg.id = f.estatus_general_id $where $periodo order by factura, fecha";
					$facturas=$this->db->query($sql)->result();
					//echo $sql;exit;
					if(count($facturas)==0)
						show_error('No hay registros que cumplan los criterios.');
					$data['facturas']=$facturas;
					$data['total_registros']=count($facturas);
					if($f1 || $f2){
						if($f1 && !$f2)
							$data['periodo']='DEL DÃA '.implode("-", array_reverse(explode("-", $fecha1)));
						elseif(!$f1 && $f2)
						$data['periodo']='DEL DÃA '.implode("-", array_reverse(explode("-", $fecha2)));
						elseif($fecha1==$fecha2)
						$data['periodo']='DEL DÃA '.implode("-", array_reverse(explode("-", $fecha1)));
						else
							$data['periodo']='DEL '.implode("-", array_reverse(explode("-", $fecha1))).' AL '.implode("-", array_reverse(explode("-", $fecha2)));
					}
					else
						$data['periodo']='DEL DÃA '.date("d-m-Y");
					$data['title'] = $this->input->post('title');
					$this->load->view("$ruta/rep_facturas_canceladas_pdf", $data);
				}
					
			} else if($subfuncion=="rep_cobranza"){
				//Cargar los datos para el formulario
				$data['frames']=1;
				$data['principal']=$ruta."/".$subfuncion;
				$data['funcion']=$subfuncion;
				# seleccionar los principales
				$e=new Empresa();
				$data['empresas']=$e->select('id,razon_social')->order_by('razon_social')->get()->all;
				if(count($data['empresas'])==0)
					show_error('No existen empresas');
			}

			if($main_view){
		  //Llamar a la vista
		  $this->load->view("ingreso",$data);
			} else {
		  redirect(base_url()."index.php/inicio/logout");
			}

		}

	}



	function rep_pedido_venta() {
		$id=$this->uri->segment(4);
		if(is_numeric($id)==false)
			show_error('Error 1: El nÃºmero de pedido de venta no existe Id Pedido Venta='.$id);

		$data['title']="Pedido de Venta";
		$data['generales']=$this->cl_pedido->get_cl_pedido_venta($id);
		if($data['generales']==false)
			show_error('Error 2 El Pedido de Venta no existe');
			
		$data['detalles']=$this->cl_detalle_pedido->get_cl_detalles_pedido_pdf($id);
		if($data['generales']==false){
			show_error('Error 3: El pedido de venta no tiene detalles en su pedido y por tanto ha sido borrado');
			$this->db->query("delete from cl_pedidos where id='$id'");
			$this->db->query("delete from cl_detalle_pedidos where cl_pedidos_id='$id'");
		}
		$this->load->view("ventas/rep_pedido_venta", $data);
	}

	function rep_cl_factura_pdf() {
		$id=$this->uri->segment(4);
		if(is_numeric($id)==false)
			show_error("Error 1: El Id de Factura = $id no existe");

		$data['title']="Orden de Salida por FacturaciÃ³n";
		$data['generales']=$this->cl_factura->get_cl_factura_pdf($id);
		$data['pedido']=$this->cl_pedido->get_cl_pedido_venta($data['generales']->cl_pedido_id);
		if($data['generales']==false)
			show_error('Error 2 La Orden de Salida no existe');
			
		$data['detalles']=$this->salida->get_salidas_by_factura($id);
		if($data['generales']==false){
			show_error('Error 3: La Orden de Salida no contiene detalles en su pedido');
			//			$this->db->query("delete from cl_pedidos where id='$id'");
			//			$this->db->query("delete from cl_detalle_pedidos where cl_pedidos_id='$id'");
		}
		$this->load->view("ventas/rep_cl_factura", $data);
		unset($data);
	}

	function rep_cl_facturas_pdf()
	{ // BEGIN method rep_cl_facturas_pdf
		global $ruta;
		//echo "<pre>";var_dump($_POST);echo "</pre>";exit();
		# Obtener los datos
		$filtro=array();
		$filtro[]="estatus_general_id = 1";
		# filtro por empresa
		if((int)$_POST['empresa']>0)
			$filtro[]="empresas_id=".(int)$_POST['empresa'];
		# filtrado de la fecha
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
		$cfecha='fecha';
		if($f1 && $f2){
			if(strcmp($fecha2,$fecha1) > 0)
				$filtro[] = "($cfecha >= '$fecha1' and $cfecha <= '$fecha2')";
			elseif(strcmp($fecha2,$fecha1) < 0)
			$filtro[] = "($cfecha >= '$fecha2' and $cfecha <= '$fecha1')";
			else
				$filtro[] = "$cfecha = '$fecha1'";
		}
		elseif($f1)
		$filtro[] = "$cfecha = '$fecha1'";
		elseif($f2)
		$filtro[] = "$cfecha = '$fecha2'";
		# agregar el filtro para el espacio fisico
		$lusrs=array();
		if((int)$_POST['espacio']>0)
		{
			# obtener los usuarios del espacio fisico
			$usuarios=$this->db->select('id')->where('espacio_fisico_id',$_POST['espacio'])->get('usuarios')->result_array();
			foreach($usuarios as &$usr)
				$lusrs[]=$usr['id'];
		}
		$filtro[]="usuario_id in (".implode(',',$lusrs).")";
		# crear la cadena del WHERE
		if (count($filtro))
			$where = " and ".implode(" and ", $filtro)." ";
		else
			$where="";
		# procesar el ordenamiento
		$nivel=array();
		$nivel[]=(int)$_POST['nivel_1'];
		$nivel[]=(int)$_POST['nivel_2'];
		$campos=array();
		foreach ($nivel as $my_key=>$my_value){
			switch($my_value){
				case 1: $campos[]='fecha'; break;
				case 2: $campos[]='monto desc'; break;
			}
		}
		# crear la cadena del ORDER
		if (count($campos))
			$order_by=" order by ".implode(",",$campos)." ";
		else
			$order_by="";
		//die($order_by);
		$data['title']=$_POST['title'];
		# obtener el nombre de la empresa
		$empresa=$this->db->select('razon_social')->where('id',(int)$_POST['empresa'])->get('empresas')->result_array();
		if(count($empresa)==0)
			show_error('La empresa especificada no existe');
		$empresa=array_pop($empresa);
		# obtener el nombre del espacio
		$espaciof=$this->db->select('tag')->where('id',(int)$_POST['espacio'])->get('espacios_fisicos')->result_array();
		if(count($espaciof)==0)
			show_error('El espacio especificado no existe');
		$espaciof=array_pop($espaciof);
		# sacar la lista de estatus de facturas
		$data['tipofac']=array();
		$res=$this->db->select('id,tag')->get('estatus_facturas')->result();
		foreach($res as $r)
			$data['tipofac'][$r->id]=$r->tag;
		# sacar la lista de clientes
		$this->db->select("id,razon_social AS nombre");
		if((int)$this->input->post('cliente')>0)
			$this->db->where("id",$this->input->post('cliente'));
		$result=$this->db->order_by('nombre')->get('cclientes')->result_array();
		$clientes=array();
		foreach($result as &$row)
		{# de este modo los clientes salen ordenados por su nombre
			$clientes[]=array('id'=>$row['id'],'nombre'=>$row['nombre'],'facturas'=>array());
		}
		$n=0;
		$vacios=array();
		# obtener los datos para cada cliente
		foreach($clientes as &$cliente)
		{
			$sql="select id,folio_factura AS folio,DATE_FORMAT(fecha,'%d-%m-%Y') AS fecha,iva_total AS iva,monto_total AS monto,estatus_factura_id AS pago from cl_facturas where cclientes_id = ".((int)$cliente['id'])." $where $order_by";
			$cliente['facturas']=$this->db->query($sql)->result_array();
			//die($this->db->last_query());
			$n+=count($cliente['facturas']);
			if(count($cliente['facturas'])==0)
				$cliente=array();
		}
		foreach($clientes as $i=>&$cliente)
			if(count($cliente)==0) $vacios[]=$i;
		foreach($vacios as $vacio)
			unset($clientes[$vacio]);
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
		if(count($clientes)==0)
			show_error('No hay registros que cumplan los criterios.');
		$data['clientes']=$clientes;
		$data['n']=$n;
		$data['title'] = $_POST['title'];
		$data['empresa']=$empresa['razon_social'];
		$data['espacio']=$espaciof['tag'];
		$this->load->view("$ruta/rep_cl_facturas_pdf",$data);
		unset($data);

	} // END method rep_cl_facturas_pdf #########################################################


	function rep_cl_facturas_detalle_pdf()
	{ // BEGIN method rep_cl_facturas_detalle_pdf
		global $ruta;
		//Obtener los datos
		//echo "<pre>";var_dump($_POST);echo "</pre>";exit();
		$filtro=array();
		if((int)$_POST['empresa']>0)
			$filtro[]="cl_facturas.empresas_id=".(int)$_POST['empresa'];
		if((int)$_POST['cliente']>0)
			$filtro[]="cl_facturas.cclientes_id=".(int)$_POST['cliente'];
		$filtro[]="cl_facturas.estatus_general_id = 1";
		# agregar el filtro para el espacio fisico
		$lusrs=array();
		if((int)$_POST['espacio']>0)
		{
			# obtener los usuarios del espacio fisico
			$usuarios=$this->db->select('id')->where('espacio_fisico_id',$_POST['espacio'])->get('usuarios')->result_array();
			foreach($usuarios as &$usr)
				$lusrs[]=$usr['id'];
		}
		$filtro[]="cl_facturas.usuario_id in (".implode(',',$lusrs).")";
		# obtener el nombre del espacio
		$espaciof=$this->db->select('tag')->where('id',(int)$_POST['espacio'])->get('espacios_fisicos')->result_array();
		if(count($espaciof)==0)
			show_error('El espacio especificado no existe');
		$espaciof=array_pop($espaciof);
		$data['espacio']=$espaciof['tag'];
		//$filtro[]="cl_facturas.cl_pedido_id > 0";
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
		$campo_fecha='cl_facturas.fecha';
		if($f1 && $f2){
			if(strcmp($fecha2,$fecha1) > 0)
				$filtro[] = "($campo_fecha >= '$fecha1' and $campo_fecha <= '$fecha2')";
			elseif(strcmp($fecha2,$fecha1) < 0)
			$filtro[] = "($campo_fecha >= '$fecha2' and $campo_fecha <= '$fecha1')";
			else
				$filtro[] = "$campo_fecha = '$fecha1'";
		}
		elseif($f1)
		$filtro[] = "$campo_fecha = '$fecha1'";
		elseif($f2)
		$filtro[] = "$campo_fecha = '$fecha2'";
		if (count($filtro))
			$where=" where ".implode(" and ",$filtro)." ";
		else
			$where="";
		//die($where);
		$nivel=array();
		$nivel[]=(int)$_POST['nivel_1'];
		$nivel[]=(int)$_POST['nivel_2'];
		$campos=array();
		foreach ($nivel as $my_key=>$my_value){
			switch($my_value){
				case 1: $campos[]='cliente'; break;
				case 2: $campos[]='fecha'; break;
			}
		}
		if (count($campos))
			$order_by=" order by ".implode(",",$campos)." ";
		else
			$order_by="";
		//die($order_by);
		$data['title']=$_POST['title'];
		# obtener datos generales de las facturas
		$sql="SELECT cl_facturas.folio_factura as folio, cl_facturas.fecha, empresas.razon_social AS empresa, cclientes.razon_social AS cliente, cl_facturas.cl_pedido_id AS pedido, cl_facturas.monto_total FROM cl_facturas Left Join empresas ON empresas.id = cl_facturas.empresas_id Left Join cclientes ON cclientes.id = cl_facturas.cclientes_id $where $order_by";
		$query=$this->db->query($sql);
		$data['facturas']=$query->result();
		$data['detalles'] = array();
		$vacios=array();
		# obtener los detalles de las facturas
		foreach($data['facturas'] as $i=>$factura)
		{
			$sql="SELECT cl_detalle_pedidos.cantidad, cproductos.descripcion AS producto, cl_detalle_pedidos.costo_unitario, cl_detalle_pedidos.costo_total, cl_detalle_pedidos.tasa_impuesto FROM cl_detalle_pedidos Left Join cproductos ON cproductos.id = cl_detalle_pedidos.cproductos_id WHERE cl_detalle_pedidos.cl_pedidos_id = '{$factura->pedido}' ORDER BY producto ASC";
			$query=$this->db->query($sql);
			$data['detalles'][$factura->pedido]=$query->result();
			if(count($data['detalles'][$factura->pedido])==0)
				$vacios[]=$i;
		}
		foreach($vacios as &$v)
			unset($data['facturas'][$v]);
		if(count($data['facturas'])==0)
			show_error('No hay registros que cumplan los criterios o LAS FACTURAS EN CUESTION NO TIENEN DETALLES EN SUS PEDIDOS.');
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
		$this->load->view("$ruta/rep_cl_facturas_detalle_pdf",$data);
		unset($data);
	} // END method rep_cl_facturas_detalle_pdf #########################################################

	function rep_cobros_cl_pdf()
	{ // BEGIN method rep_cobros_cl_pdf
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
		//$filtro[]="cobros.estatus_general_id = 1"; # 15/05/2010 18:24:26 no tiene el campo
		$filtro[]="usuarios.empresas_id = '{$_POST['empresa']}'";
		# filtrar espacio fisico
		$filtro[]="usuarios.espacio_fisico_id = '{$_POST['espacio']}'";
		# filtrar fecha
		$fecha1 = $this->input->post('fecha1');
		$fecha2 = $this->input->post('fecha2');
		$campof='cobros.fecha';
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
		if((int)$_POST['cliente']>0)
			$this->db->where('id',(int)$_POST['cliente']);
		$clientes=$this->db->order_by('nombre')->get('cclientes')->result_array();
		#obtener los datos para cada proveedor
		$n=0;
		foreach($clientes as &$cliente)
		{
			$sql="SELECT cobros.id, DATE_FORMAT(cobros.fecha,'%d-%m-%Y') AS fecha, cobros.cuenta_origen_id, cobros.cuenta_destino_id, cobros.numero_referencia, cobros.monto_pagado, cl_facturas.folio_factura, usuarios.nombre FROM soleman.cobros LEFT JOIN soleman.cl_facturas ON (cobros.cl_factura_id = cl_facturas.id) LEFT JOIN soleman.usuarios ON (cobros.usuario_id = usuarios.id) WHERE (cobros.id > 0 AND cl_facturas.cclientes_id = '".$cliente['id']."' AND $where)  $order_by";
			$cliente['cobros']= $this->db->query($sql)->result_array();
			$n+=count($cliente['cobros']);
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
		$data['clientes']=$clientes;
		$data['n']=$n;
		$data['title']=$this->input->post('title');
		$this->load->view("$ruta/rep_cobros_cl_pdf", $data);
		unset($data);
	} // END method rep_cobros_cl_pdf #########################################################

	function rep_cobros_diarios_cl_pdf()
	{ // BEGIN method rep_cobros_diarios_cl_pdf
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
		$filtro[]="cobros.estatus_general_id = 1";
		$filtro[]="usuarios.empresas_id = '{$_POST['empresa']}'";
		# filtrar espacio fisico
		$filtro[]="usuarios.espacio_fisico_id = '{$_POST['espacio']}'";
		# filtrar fecha
		$fecha1 = $this->input->post('fecha1');
		$campof='cobros.fecha_captura';
		if($fecha1 != false)
		{
			list($d,$m,$a) = explode(" ",$fecha1);
			$fecha1 = sprintf('%04d-%02d-%02d',$a,$m,$d);
		}
		$f1 = false;
		if($fecha1 == date("Y-m-d",strtotime($fecha1)))
			$f1 = true;
		if($f1)
			$filtro[] = "$campof like '$fecha1%'";
		else
			show_error("No se ha establecido la fecha del reporte.");
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
		# obtener la lista de clientes requeridos
		$this->db->select('id,razon_social as nombre,clave');
		if((int)$_POST['cliente']>0)
			$this->db->where('id',(int)$_POST['cliente']);
		$res=$this->db->order_by('id')->get('cclientes')->result_array();
		#obtener los datos
		$clientes=$cl_ids=array();
		foreach($res as &$cliente)
		{
			$clientes[$cliente['id']]=array('nombre'=>$cliente['nombre'],'clave'=>$cliente['clave']);
			$cl_ids[]=$cliente['id'];
		}
		$sql="SELECT cobros.id, DATE_FORMAT(cobros.fecha,'%d-%m-%Y') AS fecha, cobros.cuenta_origen_id, cobros.cuenta_destino_id, cobros.numero_referencia, cobros.monto_pagado, cl_facturas.folio_factura, cl_facturas.cclientes_id as cliente FROM soleman.cobros LEFT JOIN soleman.cl_facturas ON (cobros.cl_factura_id = cl_facturas.id) LEFT JOIN soleman.usuarios ON (cobros.usuario_id = usuarios.id) WHERE (cobros.id > 0 AND cl_facturas.cclientes_id in (".implode(',',$cl_ids).") AND $where)  $order_by ";
		$movs= $this->db->query($sql)->result_array();
		if(count($movs)==0)
			show_error('No hay registros que cumplan los criterios');
		//echo "<pre>";var_dump($movs);echo "</pre>";die();
		# periodo del reporte
		$data['periodo']='DEL DIA '.date("d-m-Y",strtotime($fecha1));
		# pasar datos faltantes a la vista y cargar la vista
		$data['clientes']=$clientes;
		$data['movs']=$movs;
		$data['n']=count($movs);
		$data['title']=$this->input->post('title');
		$this->load->view("$ruta/rep_cobros_diarios_cl_pdf", $data);
		unset($data);

	} // END method rep_cobros_diarios_cl_pdf #########################################################



	function rep_cobranza_pdf()
	{ // BEGIN method rep_cobranza_pdf
		//echo "<pre>";var_dump($_POST);echo "</pre>";die();
		$vista=array();
		# evaluar fecha
		$fecha = $this->input->post('fecha1');
		if($fecha != false)
		{
			list($d,$m,$a) = explode(" ",$fecha);
			$fecha = sprintf('%04d-%02d-%02d',$a,$m,$d);
		}
		$f1 = false;
		if($fecha == date("Y-m-d",strtotime($fecha)))
			$f1 = true;
		if(!$f1) $fecha=date("Y-m-d");
		# empresa
		$emp=new Empresa();
		$emp->get_by_id($this->input->post('empresa'));
		if(!$emp->exists()) show_error('La empresa especificada no existe');
		$vista['empresa']=$emp->razon_social;
		# espacios fisicos
		$eids=array();
		$espacios=array();
		$esp=new Espacio_fisico();
		$esp->select('id,tag as espacio')->where('empresas_id',$emp->id);
		if((int)$this->input->post('espacio')>0) $esp->where('id',$this->input->post('espacio'));
		$esp=$esp->order_by('espacio')->get()->all;
		if(count($esp)==0) show_error('No existen espacios fisicos que cumplan las condiciones');
		foreach($esp as $row)
		{
			$espacios[$row->id]=$row->espacio;
			$eids[]=$row->id;
		}
		$vista['espacios']=$espacios;
		# clientes
		$cids=array();
		$clientes=array();
		$c=new Cliente();
		$c=$c->select('id,razon_social as cliente')->where('estatus_general_id','1')->order_by('cliente')->get()->all;
		if(count($c)==0) show_error('No existen clientes');
		foreach($c as $row)
		{
			$clientes[$row->id]=$row->cliente;
			$cids[]=$row->id;
		}
		$vista['clientes']=$clientes;
		# obtener los datos
		$datos=array();
		foreach($cids as $cid)
		{
			foreach($eids as $eid)
			{
				$sql="SELECT fc.id, fc.fecha, fc.folio_factura, fc.monto_total, pc.fecha_pago FROM cl_facturas AS fc Left Join cl_pedidos AS pc ON pc.id = fc.cl_pedido_id Left Join usuarios AS u ON u.id = fc.usuario_id WHERE fc.id >  '0' AND u.espacio_fisico_id =  '$eid' AND fc.cclientes_id =  '$cid' AND pc.fecha_pago <= '$fecha' AND fc.estatus_factura_id=2  AND fc.estatus_general_id=1 ORDER BY fc.fecha";
				$res=$this->db->query($sql)->result();
				if(count($res)>0)
				{
					foreach($res as $row)
						$datos[$cid][$eid][]=array('id'=> $row->id, 'fecha'=> $row->fecha, 'folio_factura'=> $row->folio_factura, 'monto_total'=> $row->monto_total, 'fecha_pago'=> $row->fecha_pago);
				}
			}
		}
		if(count($datos)==0) show_error('No hay datos que mostrar');
		$vista['title']=$_POST['title'];
		$vista['datos']=$datos;
		$vista['periodo']='A LA FECHA '.$fecha;
		$this->load->view('ventas/rep_cobranza_pdf',$vista);
		unset($data);
	} // END method rep_cobranza_pdf #########################################################

	function rep_tickets_pdf()
	{ // BEGIN method rep_tickets_pdf
		//echo "<pre>";var_dump($_POST);echo "</pre>";die();
		$empresa=(int)$this->input->post('empresa');
		if($empresa<=0) show_error('La empresa especificada no existe');
		$espacio=$this->input->post('espacio');
		if($empresa<=0) show_error('El espacio especificado no existe');
		$fecha1=$this->input->post('fecha1');
		$fecha2=$this->input->post('fecha2');
		$vista=array();
		$vista['title']=$this->input->post('title');
		// filtro del espacio fisico
		if((int)$espacio > 0) $filtro[]='espacio_fisico_id='.$espacio;
		// filtrado de la fecha
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
		$fi=$ff='';
		if($fecha1 == date("Y-m-d",strtotime($fecha1)))
			$f1 = true;
		if($fecha2 == date("Y-m-d",strtotime($fecha2)))
			$f2 = true;
		//$cfecha='fecha';
		if($f1 && $f2)
		{
			if(strcmp($fecha2,$fecha1) > 0)
			{
				$fi=$fecha1;
				$ff=$fecha2;
				//$filtro[] = "($cfecha >= '$fecha1' and $cfecha <= '$fecha2')";
			}
			elseif(strcmp($fecha2,$fecha1) < 0)
			{
				$fi=$fecha2;
				$ff=$fecha1;
				//$filtro[] = "($cfecha >= '$fecha2' and $cfecha <= '$fecha1')";
			}
			else
			{
				$fi=$ff=$fecha1;
				//$filtro[] = "$cfecha = '$fecha1'";
			}
		}
		elseif($f1)
		{
			$fi=$ff=$fecha1;
			//$filtro[] = "$cfecha = '$fecha1'";
		}
		elseif($f2)
		{
			$fi=$ff=$fecha2;
			//$filtro[] = "$cfecha = '$fecha2'";
		}
		else
		{
			// buscar la fecha del primer movimiento registrado
			$sql="SELECT DISTINCT fecha from nota_remision where nota_remision.estatus_general_id=1 order by fecha limit 1";
			$res=$this->db->query($sql)->result_array();
			if(count($res)==0) show_error('No hay tickets registrados');
			$fi=$res[0]['fecha'];
			$ff=date('Y-m-d');
		}
		if($f1 || $f2)
		{
			if($f1 && !$f2) $fecha2=$fecha1;
			if(!$f1 && $f2) $fecha1=$fecha2;
			$fecha1=date("d-m-Y",strtotime($fecha1));
			$fecha2=date("d-m-Y",strtotime($fecha2));
			$vista['periodo']=$fecha1.' A '.$fecha2;
		}
		else
			$vista['periodo']='A LA FECHA '.date("d-m-Y");
		// obtener datos de la empresa y del espacio fisico
		$db=new Empresa();
		$db->get_by_id($empresa);
		$vista['empresa']=$db->razon_social;
		$db->clear();
		$db=new Espacio_fisico();
		$db->get_by_id($espacio);
		$vista['espacio']=$db->tag;
		// obtener los datos para el reporte dia por dia
		$vista['tickets']=array();
		while(strcmp($ff, $fi)>=0)
		{
			$sql="SELECT numero_remision AS remision , importe_total AS importe , estatus_general_id AS estatus FROM soleman.nota_remision WHERE (fecha ='$fi' AND espacio_fisico_id =$espacio AND estatus_general_id in (1,2)) ORDER BY numero_remision ASC";
			$res=$this->db->query($sql)->result();
			if(count($res)>0)
			{
				$ti=(int)$res[0]->remision;// tik ini
				$tf=(int)$res[count($res)-1]->remision;// tik fin
				$nt=$tf-$ti+1;// num tix
				$it=(float)0;// imp tix
				$tc=array();// tix canc
				foreach($res as $row)
				{
					if((int)$row->estatus==2)
						$tc[]=$row->remision;
					else
						$it+=(float)$row->importe;
				}
				$dia=date("N",strtotime($fi));
				switch($dia)
				{
					case '1': $dia='Lunes'; break;
					case '2': $dia='Martes'; break;
					case '3': $dia='Miercoles'; break;
					case '4': $dia='Jueves'; break;
					case '5': $dia='Viernes'; break;
					case '6': $dia='Sabado'; break;
					case '7': $dia='Domingo';
				}
				$vista['tickets'][]=array('f'=>date("d-m-Y",strtotime($fi)).'       '.$dia,'ti'=>$ti,'tf'=>$tf,'tt'=>$nt,'it'=>$it,'tc'=>implode(', ',$tc));
			}
			$fi=date("Y-m-d",strtotime($fi.' +1 day'));
		}
		// checar que haya datos que mostrar
		if(count($vista['tickets'])==0) show_error('No hay datos que cumplan los criterios');
		$this->load->view('ventas/rep_tickets_pdf',$vista);
		unset($data);
	} // END method rep_tickets_pdf #########################################################

	function rep_tickets_vol_pdf()
	{ // BEGIN method rep_tickets_vol_pdf
		$empresa=(int)$this->input->post('empresa');
		if($empresa<=0) show_error('La empresa especificada no existe');
		$espacio=$this->input->post('espacio');
		if($empresa<=0) show_error('El espacio especificado no existe');
		$fecha1=$this->input->post('fecha1');
		$fecha2=$this->input->post('fecha2');
		$vista=array();
		$vista['title']=$this->input->post('title');
		// filtro del espacio fisico
		if((int)$espacio > 0) $filtro[]='espacio_fisico_id='.$espacio;
		// filtrado de la fecha
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
		$fi=$ff='';
		if($fecha1 == date("Y-m-d",strtotime($fecha1)))
			$f1 = true;
		if($fecha2 == date("Y-m-d",strtotime($fecha2)))
			$f2 = true;
		//$cfecha='fecha';
		if($f1 && $f2)
		{
			if(strcmp($fecha2,$fecha1) > 0)
			{
				$fi=$fecha1;
				$ff=$fecha2;
				//$filtro[] = "($cfecha >= '$fecha1' and $cfecha <= '$fecha2')";
			}
			elseif(strcmp($fecha2,$fecha1) < 0)
			{
				$fi=$fecha2;
				$ff=$fecha1;
				//$filtro[] = "($cfecha >= '$fecha2' and $cfecha <= '$fecha1')";
			}
			else
			{
				$fi=$ff=$fecha1;
				//$filtro[] = "$cfecha = '$fecha1'";
			}
		}
		elseif($f1)
		{
			$fi=$ff=$fecha1;
			//$filtro[] = "$cfecha = '$fecha1'";
		}
		elseif($f2)
		{
			$fi=$ff=$fecha2;
			//$filtro[] = "$cfecha = '$fecha2'";
		}
		else
		{
			// buscar la fecha del primer movimiento registrado
			$sql="SELECT DISTINCT fecha from nota_remision where nota_remision.estatus_general_id=1 order by fecha limit 1";
			$res=$this->db->query($sql)->result_array();
			if(count($res)==0) show_error('No hay tickets registrados');
			$fi=$res[0]['fecha'];
			$ff=date('Y-m-d');
		}
		if($f1 || $f2)
		{
			if($f1 && !$f2) $fecha2=$fecha1;
			if(!$f1 && $f2) $fecha1=$fecha2;
			$fecha1=date("d-m-Y",strtotime($fecha1));
			$fecha2=date("d-m-Y",strtotime($fecha2));
			$vista['periodo']=$fecha1.' A '.$fecha2;
		}
		else
			$vista['periodo']='A LA FECHA '.date("d-m-Y");
		// obtener datos de la empresa y del espacio fisico
		$db=new Empresa();
		$db->get_by_id($empresa);
		$vista['empresa']=$db->razon_social;
		$db->clear();
		$db=new Espacio_fisico();
		$db->get_by_id($espacio);
		$vista['espacio']=$db->tag;
		// obtener los datos para el reporte dia por dia
		$vista['tickets']=array();
		while(strcmp($ff, $fi)>=0)
		{
			$sql="SELECT numero_remision AS remision , importe_total AS importe , estatus_general_id AS estatus FROM soleman.nota_remision WHERE (fecha ='$fi' AND espacio_fisico_id =$espacio AND estatus_general_id in (1,2)) ORDER BY numero_remision ASC";
			$res=$this->db->query($sql)->result();
			if(count($res)>0)
			{
				$ti=(int)$res[0]->remision;// tik ini
				$tf=(int)$res[count($res)-1]->remision;// tik fin
				$nt=$tf-$ti+1;// num tix
				$it=(float)0;// imp tix
				$tc=array();// tix canc
				foreach($res as $row)
				{
					if((int)$row->estatus==2)
						$tc[]=$row->remision;
				}
				$dia=date("N",strtotime($fi));
				switch($dia)
				{
					case '1': $dia='Lunes'; break;
					case '2': $dia='Martes'; break;
					case '3': $dia='Miercoles'; break;
					case '4': $dia='Jueves'; break;
					case '5': $dia='Viernes'; break;
					case '6': $dia='Sabado'; break;
					case '7': $dia='Domingo';
				}
				$vista['tickets'][]=array('f'=>date("d-m-Y",strtotime($fi)).'       '.$dia,'ti'=>$ti,'tf'=>$tf,'tt'=>$nt,'tc'=>implode(', ',$tc));
			}
			$fi=date("Y-m-d",strtotime($fi.' +1 day'));
		}
		// checar que haya datos que mostrar
		if(count($vista['tickets'])==0) show_error('No hay datos que cumplan los criterios');
		$this->load->view('ventas/rep_tickets_vol_pdf',$vista);
		unset($data);
	} // END method rep_tickets_vol_pdf #########################################################
        
     function reporte_cl_factura(){
        global $espacio_fisico_id;
        $data['title'] = "factura";
        $id = (int) $this->uri->segment(4);
        
        $factura = new Cl_factura($id);
        $data['factura'] = $factura;
        $cliente = new Cliente($factura->cclientes_id);
        $data['folio_certificado'] = $this->cl_factura->get_folio_certificado($espacio_fisico_id);
        $data['cliente'] = $cliente;
        $data['salidas'] = $this->cl_factura->get_cl_factura_salidas($factura->id);
        
        $this->load->library('fpdf_cl_factura');
        $this->load->view('ventas/reporte_cl_factura_pdf',$data);
        unset($data);
     }

}
?>
