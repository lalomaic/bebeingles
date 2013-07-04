<?php
class Contabilidad_reportes extends Controller {
	//var $usuarioid, $username, $ruta, $controller, $funcion, $subfuncion, $modulos_totales;
	function Contabilidad_reportes()
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
		$this->assetlibpro->add_js('jquery.select-autocomplete.js');
		$this->assetlibpro->add_js('jquery.numberformatter.js');
		$this->assetlibpro->add_js('jquery.AddIncSearch.js');
		$this->assetlibpro->add_js('jquery.validator.js');
		$this->assetlibpro->add_js('jquery.jfield.js');
		$this->assetlibpro->add_js('jquery.date.js');
		$this->assetlibpro->add_js('jquery.form.js');
		$this->assetlibpro->add_js('fancybox/jquery.mousewheel-3.0.2.pack.js');
		$this->assetlibpro->add_js('fancybox/jquery.fancybox-1.3.1.js');
		$this->load->model("proveedor");
		$this->load->model("marca_producto");
		$this->load->model("espacio_fisico");
		$this->load->model("pr_factura");
		$this->load->model("pago");
		$this->load->model("cpr_forma_pago");
		$this->load->model("cpr_estatus_pedido");
		$this->load->model("pr_pedido");
		$this->load->model("tipo_pago");
		$this->load->model("pr_detalle_pedido");
		$this->load->model("salida");
		$this->load->model("poliza_detalle");
		$this->load->model("cuenta_contable");
                $this->load->model("cuenta_bancaria");
		$this->load->model("subtipo_espacio");
		$this->load->model("cl_factura");
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
			if ($subfuncion=="rep_pr_facturas"){
				$data['frames']=1;
				$data['principal']=$ruta."/".$subfuncion;
				$data['empresas']=$this->empresa->get_empresas();
				$data['proveedores']=$this->proveedor->get_proveedores();
			} else if ($subfuncion=="rep_depositos_tiendas"){
				$data['frames']=1;
				$data['principal']=$ruta."/".$subfuncion;
				$data['empresas']=$this->empresa->get_empresas();
				$data['espacios']=$this->espacio_fisico->get_espacios_f();
			}
			else if ($subfuncion=="rep_pr_facturas_detalle"){
				$data['frames']=1;
				$data['principal']=$ruta."/".$subfuncion;
				$data['empresas']=$this->empresa->get_empresas();
				$data['proveedores']=$this->proveedor->get_proveedores();
				$data['funcion']=$subfuncion;
			}
			else if ($subfuncion=="rep_pr_formas_pago"){
				//Cargar los datos para el formulario
				$data['frames']=1;
				$data['forma_pagos'] = $this->cpr_forma_pago->get_formas_pago_pdf();				
                                if(!$data['forma_pagos'])
					show_error('No hay registros que cumplan los criterios.');
				$this->load->view("$ruta/rep_pr_formas_pago_pdf", $data);
			} else if ($subfuncion=="rep_tipos_pagos"){
				//Cargar los datos para el formulario
				$data['frames']=1;
				$data['tipo_pagos'] = $this->tipo_pago->get_ctipos_pagos();
				if(!$data['tipo_pagos'])
					show_error('No hay registros que cumplan los criterios.');
				$this->load->view("$ruta/rep_tipos_pago_pdf", $data);

			} else if ($subfuncion=="rep_pagos_multiples"){
				//Cargar los datos para el formulario
				$data['frames']=1;
				$data['principal']=$ruta."/".$subfuncion;
				$data['proveedores']=$this->proveedor->get_proveedores();
			} else if ($subfuncion=="rep_pagos_entre_tiendas"){
				$this->load->model('almacen');
				$data['frames']=1;
				$data['principal']=$ruta."/".$subfuncion;
				$data['espacios']=$this->espacio_fisico->get_espacios_tiendas_mtrx();
				$data['meses']=$this->almacen->get_meses_dropd();
				$data['anios']=$this->almacen->get_anios_dropd();
			} else if ($subfuncion=="rep_devoluciones_defectos"){
				//Cargar los datos para el formulario
				$data['frames']=1;
				$data['principal']=$ruta."/".$subfuncion;
			} else if ($subfuncion=="rep_cuentas_contables"){

				//Cargar los datos para el formulario
				$data['frames']=1;
				$data['principal']=$ruta."/".$subfuncion;
				$data['cuentas_select'] = $this->cuenta_contable->get_list_cuentas_contables_select_todos($GLOBALS['empresa_id']);
				if(!$data['cuentas_select'])
					show_error('No hay cuentas contables.');
				//$this->load->view("$ruta/rep_cuentas_contables", $data);
			} else if ($subfuncion=="rep_cuentas_bancarias"){
                            //HACER EL REPORTE DE CUENTAS BANCARIAS              
				
			} else if ($subfuncion=="rep_global_deuda"){
				//Cargar los datos para el formulario
				$data['frames']=1;
				$data['principal']=$ruta."/".$subfuncion;
				//Obtener los Proveedores
				$this->db->select("id,razon_social")->order_by("razon_social");
				$this->db->where('estatus_general_id',1);
				$data['proveedores']=$this->db->get('cproveedores')->result();
				if (count($data['proveedores'])==0)
					show_error('No existen Proveedores');
				//Obtener los Subtipos de Espacios
				$this->load->model('subtipo_espacio');
				$data['subtipos']=$this->subtipo_espacio->get_subtipos_espacios();
				//Obtener los espacios fisicos
				$this->db->select("id,tag")->order_by("tag");
				$this->db->where('estatus_general_id',1);
				$this->db->where('tipo_espacio_id',1);
				$data['espacios']=$this->db->get('espacios_fisicos')->result();
				if (count($data['espacios'])==0)
					show_error('No existen Espacios Fisicos');
				//Obtener las empresas
				$this->db->select("id,razon_social")->order_by("id");
				$this->db->where('estatus_general_id',1);
				$data['empresas']=$this->db->get('empresas')->result();
				if (count($data['empresas'])==0)
					show_error('No existen Empresas');
			} else if ($subfuncion=="rep_gen_ventas"){
				//Cargar los datos para el formulario
				//Definir el numero de frames
				$data['frames']=1;
				//Definir la vista
				$data['principal']=$ruta."/".$subfuncion;
				$data['funcion']=$subfuncion;
				//Obtener Datos
				$t=new Empresa();
				$t->select('id,razon_social')->order_by('razon_social')->get();
				if(!$t->exists()) show_error('No hay empresas');
				$ef=new Espacio_fisico();
				$ef->select('id,tag')->order_by('tag')->get();
				if(!$t->exists()) show_error('No hay espacios fisicos');
				$pv=new Cliente();
				$pv->select('id,razon_social')->order_by('razon_social')->get();
				if(!$t->exists()) show_error('No hay proveedores');
				$p=new Producto();
				$p->select('id,descripcion')->order_by('descripcion')->get();
				if(!$t->exists()) show_error('No hay productos');
				$data['empresas']=$t;
				$data['espaciosf']=$ef->all;
				$data['clientes']=$pv->all;

			} if ($subfuncion=="rep_diario_ventas"){
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
						$where=" where f.estatus_factura_id in (1,2) and ef.id=$_POST[espacio] ";
						$data['espacio']=$espacio[0]->tag;
					}

					else{
						$where="where f.estatus_factura_id in (1,2)";
						$data['espacio']="";
					}


					$sql="SELECT f.id AS id_factura, f.folio_factura AS factura, cc.razon_social AS cliente, cc.clave AS clave, f.monto_total, ef.tag AS espacio, f.fecha AS fecha, e.id AS estatus_factura, eg.id AS estatus_general FROM cl_facturas AS f LEFT JOIN cclientes AS cc ON cc.id = f.cclientes_id LEFT JOIN usuarios AS u ON u.id = f.usuario_id	LEFT JOIN espacios_fisicos AS ef ON ef.id = u.espacio_fisico_id LEFT JOIN estatus_facturas AS e ON e.id = f.estatus_factura_id LEFT JOIN estatus_general AS eg ON eg.id = f.estatus_general_id $where $periodo order by factura, fecha";
					$facturas=$this->db->query($sql)->result();
					if(count($facturas)==0)
						show_error('No hay registros que cumplan los criterios.');
					$data['facturas']=$facturas;
					$data['total_registros']=count($facturas);
					$data['title'] = $this->input->post('title');
					$this->load->view("$ruta/rep_diario_ventas_pdf", $data);
				}

			}
			else if($subfuncion=='rep_antiguedad_saldos_proveedores'){
				//Cargar los datos para el formulario
				$data['title']="ANTIGÜEDAD DE SALDOS A PROVEEDORES (CxP)";
				$data['frames']=1;
				$data['principal']=$ruta."/".$subfuncion;
				$data['funcion']=$subfuncion;
				//Obtener los Proveedores
				$this->db->select("id,razon_social")->order_by("razon_social");
				$this->db->where('estatus_general_id',1);
				$data['proveedores']=$this->db->get('cproveedores')->result();
				if (count($data['proveedores'])==0)
					show_error('No existen Proveedores');
				//Obtener los Subtipos de Espacios
				$this->load->model('subtipo_espacio');
				$data['subtipos']=$this->subtipo_espacio->get_subtipos_espacios();
				//Obtener los espacios fisicos
				$this->db->select("id,tag")->order_by("tag");
				$this->db->where('estatus_general_id',1);
				$this->db->where('tipo_espacio_id',1);
				$data['espacios']=$this->db->get('espacios_fisicos')->result();
				if (count($data['espacios'])==0)
					show_error('No existen Espacios Fisicos');
				//Obtener las empresas
				$this->db->select("id,razon_social")->order_by("id");
				$this->db->where('estatus_general_id',1);
				$data['empresas']=$this->db->get('empresas')->result();
				if (count($data['empresas'])==0)
					show_error('No existen Empresas');
				if($this->uri->segment(5)=="pdf")
				{
					$fech1='';
					$periodo='';
					$fecha1 = $this->input->post('fecha1');
					if($fecha1 != false) {
						list($d,$m,$a) = explode(" ",$fecha1);
						$fecha1 = sprintf('%04d-%02d-%02d',$a,$m,$d);
					} else {
						$fecha1 == date("Y-m-d",strtotime($fecha1));
					}
					$f1 = false;
					if($fecha1 == date("Y-m-d",strtotime($fecha1)))
						$f1 = true;
					if($f1) {
						$periodo = "(f.fecha <= '$fecha1' OR p.fecha <= '$fecha1')";
						$fech1=$fecha1;
						$periodo='AND '.$periodo;
					}
					# obtener los proveedores y espacios fisicos
					$proveedores=array();
					$prov_ids=array();
					$espacios=array();
					$esp_ids=array();
					$vista=array();
					$where=" where f.estatus_factura_id in (2,3) and f.estatus_general_id=1 and f.empresas_id=$_POST[empresa] ";
					$this->db->select("id,razon_social as proveedor")->order_by("proveedor");# 11.Jun.2010 @aku
					if((int)$this->input->post('proveedor')>0){
						$this->db->where('id',(int)$this->input->post('proveedor'));
						$vista['salto_pagina']=false;
						$where.="  and cp.id=$_POST[proveedor] ";
					} else {
						$this->db->where('estatus_general_id',1);
						$vista['salto_pagina']=true;
					}
					$result=$this->db->get('cproveedores')->result();
					foreach($result as $row) {
						$proveedores[$row->id]=$row->proveedor;
						$prov_ids[]=$row->id;
					}
					$ef=new Espacio_Fisico();
					if((int)$this->input->post('espacio')>0) {
						$ef->where('id', $this->input->post('espacio'));
						$where.="  and ef.id=$_POST[espacio] ";
					} else if($this->input->post('subtipo')>0){
						$ef->where('subtipo_espacio_id', $this->input->post('subtipo'))->order_by('tag asc');
						$where.="  and ef.subtipo_espacio_id=$_POST[subtipo] ";

					}
					$ef->where('estatus_general_id',1)->get();
					foreach($ef->all as $row){
						$espacios[$row->id]=$row->tag;
						$esp_ids[]=$row->id;
						$esp_tag[]=$row->tag;
					}
					$vista['esp']=$esp_tag;
					$vista['empresa']=$this->empresa->get_by_id($_POST['empresa']);
					$vista['ef']=false;
					$bloques=array();
					$facturas=array();
					# obtener las facturas
					$this->db->select("id,folio_factura as factura,fecha as fecha_alta,monto_total");
					$this->db->where_in("estatus_factura_id",array(2,3));// aceptar facturas con estatus 2 y 3 #22-07-10
					if((int)$_POST['proveedor'] > 0)
						$this->db->where("cproveedores_id",$_POST['proveedor']);
					$this->db->order_by('factura,fecha_alta');
					$res=$this->db->get('pr_facturas')->result();
					if(count($res)==0)
						show_error('No hay Facturas');
					foreach($res as $row)
					{
						$facturas[$row->id]=array(
								'id'=>$row->id,
								'factura'=>$row->factura,
								'fecha_alta'=>$row->fecha_alta,
								'monto_total'=>$row->monto_total
						);
						$fact_ids[]=$row->id;
					}
					$order_by="order by cp.razon_social, espacio, fecha_pago asc";
					$sql="SELECT cp.id AS proveedor, cp.razon_social, ef.id AS espacio, f.empresas_id, f.id AS id_factura, f.folio_factura AS factura, f.fecha AS fecha_alta, f.monto_total, p.fecha, p.monto_pagado, f.fecha_pago as vencimiento, mp.tag as marca FROM pr_facturas AS f LEFT JOIN pagos AS p ON p.pr_factura_id = f.id  LEFT JOIN espacios_fisicos AS ef ON ef.id = f.espacios_fisicos_id LEFT JOIN cproveedores AS cp ON cp.id = f.cproveedores_id left join cmarcas_productos as mp on mp.id=f.cmarca_id LEFT JOIN usuarios AS u ON u.id = f.usuario_id  $where  and validacion_contable=1 $periodo $order_by ";# 11.Jun.2010 @aku
					$result=$this->db->query($sql);
					if (count($result) > 0){
						foreach($result->result() as $row){

							if(isset($bloq[$row->espacio][$row->proveedor]['marca'])==false){
								$bloq[$row->espacio][$row->proveedor]['marca']=$row->marca;
							} else if ($bloq[$row->espacio][$row->proveedor]['marca']!=$row->marca){
								$bloq[$row->espacio][$row->proveedor]['marca']="Varias";
							}

							$bloques[$row->espacio][$row->proveedor][$row->id_factura][]=array('id_factura'=>$row->id_factura,'factura'=>$row->factura,'fecha_alta'=>$row->fecha_alta,'monto_total'=>$row->monto_total,'fecha'=>$row->fecha,'monto_pagado'=>$row->monto_pagado,'vencimiento'=>$row->vencimiento);

						}
					}
					##### Inicio Bloque para eliminar del array facturas con estatus_factura_id=3 que esten liquidadas a la fecha #22-07-10
					if(!$fecha1)
						$fecha1=date("Y-m-d"); $ciclos=0;
					foreach($bloques as $eid=>$espacio){
						foreach($espacio as $pid=>$proveedor){
							foreach($proveedor as $fid=>$id_factura){
								$abono_total=(float)0;
								$cargo_total=(float)0;
								$saldo=(float)0;
								foreach($id_factura as $movimiento){
									if($movimiento['fecha_alta'] <= $fecha1){
										$cargo_total=$movimiento['monto_total'];
										if($movimiento['fecha']  && $movimiento['fecha'] <= $fecha1){
											$abono_total+=$movimiento['monto_pagado'];
										} else
											$abono_total+=0;

									}
									$ciclos+=1;
									$saldo=$cargo_total-$abono_total;

								}
								if($saldo<=0){
									unset ($bloques[$eid][$pid][$fid]);
								}
							}
						}
					}
					//echo count($bloques)."-$ciclos<br/>";
					##### Fin Bloque para eliminar del array facturas con estatus_factura_id = 3 que esten liquidadas a la fecha
					##### Inicio Bloque para depurar el array con Facturas, Espacios fisicos y Proveedores inexistentes #22-07-10
					$prov=0;$esp=0;$fac=0;$mov=0;
					foreach($bloques as $eid=>$espacio){
						$prov=0;
						foreach($espacio as $pid=>$proveedor){
							$fac=0;
							foreach($proveedor as $fid=>$id_factura){
								$mov=0;
								foreach($id_factura as $movimiento){
									$mov++;
								}
								if($mov==0){
									unset ($bloques[$eid][$pid][$fid]);
								}
								$fac++;
							}
							if($fac==0){
								unset ($bloques[$eid][$pid]);
							} else {
								$prov++;
							}
						}
						if($prov==0){
							unset ($bloques[$eid]);
						}
						$esp++;
					}
					if($esp==0){
						unset ($bloques);
					}
					##### Fin Bloque para depurar el array con Facturas, Espacios fisicos y Proveedores inexistentes

					# checar que haya datos
					if(count($bloques)==0)
						show_error('No hay registros que cumplan los criterios.');
					if($f1)
						$vista['periodo']='A LA FECHA '.implode("-", array_reverse(explode("-", $fecha1)));
					else
						$vista['periodo']='A LA FECHA '.date("d-m-Y");
					$vista['fecha']=$fech1;
					$vista['bloques']=$bloques;
					$vista['bloq']=$bloq;
					$vista['proveedores']=$proveedores;
					$vista['espacios']=$espacios;
					$vista['facturas']=$facturas;
					$vista['title'] = $this->input->post('title');
					$main_view=false;
					$this->load->view("$ruta/rep_antiguedad_saldos_proveedores_pdf", $vista);
				}
			}


			else if($subfuncion=='rep_vencimiento_saldos_proveedores'){
				//Cargar los datos para el formulario
				$data['title']="VENCIMIENTO DE SALDOS DE PROVEEDORES";
				$data['frames']=1;
				$data['principal']=$ruta."/".$subfuncion;
				$data['funcion']=$subfuncion;
				$this->db->select("id,razon_social")->order_by("razon_social");
				$this->db->where('estatus_general_id',1);
				$data['proveedores']=$this->db->get('cproveedores')->result();
				if (count($data['proveedores'])==0)
					show_error('No existen Proveedores');
				$this->db->select("id,tag")->order_by("tag");
				$this->db->where('estatus_general_id',1);
				$data['espacios']=$this->db->get('espacios_fisicos')->result();
				if (count($data['espacios'])==0)
					show_error('No existen Espacios Fisicos');
				$this->db->select("id,razon_social")->order_by("id");
				$this->db->where('estatus_general_id',1);
				$data['empresas']=$this->db->get('empresas')->result();
				if (count($data['empresas'])==0)
					show_error('No existen Empresas');
				if($this->uri->segment(5)=="pdf")
				{
					//print_r($_POST);exit;
					$fech1='';
					$periodo='';
					$fecha1 = $this->input->post('fecha1');
					if($fecha1 != false)
					{
						list($d,$m,$a) = explode(" ",$fecha1);
						$fecha1 = sprintf('%04d-%02d-%02d',$a,$m,$d);
					}
					$f1 = false;
					if($fecha1 == date("Y-m-d",strtotime($fecha1)))
						$f1 = true;
					if($f1)
					{
						$periodo = "(f.fecha <= '$fecha1' OR p.fecha <= '$fecha1')";
						$fech1=$fecha1;
						$periodo='AND '.$periodo;
					}
					else
						$fech1=date("Y-m-d");

					# obtener los proveedores y espacios fisicos
					$proveedores=array();
					$prov_ids=array();
					$espacios=array();
					$esp_ids=array();
					$this->db->select("id,razon_social as proveedor")->order_by("proveedor");# 11.Jun.2010 @aku
					if((int)$this->input->post('proveedor')>0)
						$this->db->where('id',(int)$this->input->post('proveedor'));
					else
						$this->db->where('estatus_general_id',1);
					$result=$this->db->get('cproveedores')->result();
					foreach($result as $row)
					{
						$proveedores[$row->id]=$row->proveedor;
						$prov_ids[]=$row->id;
					}
					$this->db->select("id,tag as espacio")->order_by("id,espacio");
					if((int)$this->input->post('espacio')>0)
						$this->db->where('id',(int)$this->input->post('espacio'));
					else
						$this->db->where('estatus_general_id',1);
					$result=$this->db->get('espacios_fisicos')->result();
					foreach($result as $row)
					{
						$espacios[$row->id]=$row->espacio;
						$esp_ids[]=$row->id;
					}
					$vista=array();
					$this->db->select("razon_social");
					$this->db->where('id',$_POST['empresa']);
					$vista['empresa']=$this->db->get('empresas')->result_array();
					$vista['ef']=false;
					$this->db->select("tag");
					$this->db->where('id',$_POST['espacio']);
					$vista['esp']=$this->db->get('espacios_fisicos')->result_array();
					$bloques=array();
					$facturas=array();
					# obtener las facturas
					$this->db->select("id,folio_factura as factura,fecha as fecha_alta,monto_total");
					//$this->db->where("estatus_factura_id",2);
					$this->db->where_in("estatus_factura_id",array(2,3));// aceptar facturas con estatus 2 y 3 #22-07-10
					if((int)$_POST['proveedor'] > 0)
						$this->db->where("cproveedores_id",$_POST['proveedor']);
					$this->db->order_by('factura,fecha_alta');
					$res=$this->db->get('pr_facturas')->result();
					if(count($res)==0)
						show_error('No hay Facturas');
					foreach($res as $row)
					{
						$facturas[$row->id]=array(
								'id'=>$row->id,
								'factura'=>$row->factura,
								'fecha_alta'=>$row->fecha_alta,
								'monto_total'=>$row->monto_total
						);
						$fact_ids[]=$row->id;
					}
					$order_by="order by proveedor, espacio, fecha_alta, fecha";
					if($this->input->post('proveedor')>0 && $this->input->post('espacio')>0)
						$where=" where f.estatus_factura_id in (2,3) and f.estatus_general_id=1 and ef.id=$_POST[espacio] and f.empresas_id=$_POST[empresa]";
					if($this->input->post('proveedor')>0 && $this->input->post('espacio')==0)
						$where=" where f.estatus_factura_id in (2,3) and f.estatus_general_id=1 and f.empresas_id=$_POST[empresa]";
					if($this->input->post('proveedor')==0 && $this->input->post('espacio')>0)
						$where=" where f.estatus_factura_id in (2,3) and f.estatus_general_id=1 and ef.id=$_POST[espacio] and f.empresas_id=$_POST[empresa]";
					if($this->input->post('proveedor')==0 && $this->input->post('espacio')==0)
						$where=" where f.estatus_factura_id in (2,3) and f.estatus_general_id=1 and cp.estatus_general_id=1 and f.empresas_id=$_POST[empresa]";
					# obtener los datos de facturas
					foreach($prov_ids as $prid){
						$sql="SELECT cp.id AS proveedor, ef.id AS espacio, f.empresas_id, f.id AS id_factura, f.folio_factura AS factura, f.fecha AS fecha_alta, f.monto_total, p.fecha, p.monto_pagado, f.fecha_pago FROM pr_facturas AS f LEFT JOIN pagos AS p ON p.pr_factura_id = f.id LEFT JOIN cproveedores AS cp ON cp.id = f.cproveedores_id LEFT JOIN usuarios AS u ON u.id = f.usuario_id LEFT JOIN espacios_fisicos AS ef ON ef.id = u.espacio_fisico_id LEFT JOIN pr_pedidos AS pe ON pe.id = f.pr_pedido_id $where and cp.id=$prid $periodo $order_by";# 11.Jun.2010 @aku
						$result=$this->db->query($sql)->result();
						//echo $sql;exit;
						if (count($result) > 0){
							//echo $sql."<br><br>";
							foreach($result as $row){
								$bloques[$row->proveedor][$row->espacio][$row->id_factura][]=array('id_factura'=>$row->id_factura,'factura'=>$row->factura,'fecha_alta'=>$row->fecha_alta,'monto_total'=>$row->monto_total,'fecha'=>$row->fecha,'monto_pagado'=>$row->monto_pagado,'fecha_pago'=>$row->fecha_pago);
							}
						}
					}
					//exit;
					##### Inicio Bloque para eliminar del array facturas con estatus_factura_id=3 que esten liquidadas a la fecha #22-07-10
					if(!$fecha1)
						$fecha1=date("Y-m-d");
					foreach($bloques as $pid=>$proveedor){
						foreach($proveedor as $eid=>$espacio){
							foreach($espacio as $fid=>$id_factura){
								$abono_total=(float)0;
								$cargo_total=(float)0;
								$saldo=(float)0;
								foreach($id_factura as $movimiento){
									if($movimiento['fecha_alta'] < $fecha1){
										$cargo_total=$movimiento['monto_total'];
										if($movimiento['fecha'] && $movimiento['fecha'] < $fecha1){
											$abono_total+=$movimiento['monto_pagado'];
										}
									}
									$saldo=$cargo_total-$abono_total;

								}
								if($saldo<=0){
									unset ($bloques[$pid][$eid][$fid]);
								}

							}
						}
					}
					##### Fin Bloque para eliminar del array facturas con estatus_factura_id = 3 que esten liquidadas a la fecha
					##### Inicio Bloque para depurar el array con Facturas, Espacios fisicos y Proveedores inexistentes #22-07-10
					$prov=0;$esp=0;$fac=0;$mov=0;
					foreach($bloques as $pid=>$proveedor){
						$prov++;
						$esp=0;
						foreach($proveedor as $eid=>$espacio){
							$esp++;
							$fac=0;
							foreach($espacio as $fid=>$id_factura){
								$fac++;
								$mov=0;
								foreach($id_factura as $movimiento){
									$mov++;
								}
								if($mov==0){
									unset ($bloques[$pid][$eid][$fid]);
								}
							}
							if($fac==0){
								unset ($bloques[$pid][$eid]);
							}
						}
						if($fac==0){
							unset ($bloques[$pid]);
						}
					}
					if($prov==0){
						unset ($bloques);
					}
					##### Fin Bloque para depurar el array con Facturas, Espacios fisicos y Proveedores inexistentes

					# checar que haya datos
					if(count($bloques)==0)
						show_error('No hay registros que cumplan los criterios.');
					if($f1)
						$vista['periodo']='A LA FECHA '.implode("-", array_reverse(explode("-", $fecha1)));
					else
						$vista['periodo']='A LA FECHA '.date("d-m-Y");
					$vista['fecha']=$fech1;
					$vista['bloques']=$bloques;
					$vista['proveedores']=$proveedores;
					$vista['espacios']=$espacios;
					$vista['facturas']=$facturas;
					$vista['title'] = $this->input->post('title');
					$this->load->view("$ruta/rep_vencimiento_saldos_proveedores_pdf", $vista);
				}
			}


			else if($subfuncion=='rep_antiguedad_saldos_clientes'){
				//Cargar los datos para el formulario
				$data['title']="ANTIGÜEDAD DE SALDOS A CLIENTES(CxC)";
				$data['frames']=1;
				$data['principal']=$ruta."/".$subfuncion;
				$data['funcion']=$subfuncion;
				$this->db->select("id,razon_social")->order_by("razon_social");
				$this->db->where('estatus_general_id',1);
				$data['clientes']=$this->db->get('cclientes')->result();
				if (count($data['clientes'])==0)
					show_error('No existen Clientes');
				$this->db->select("id,tag")->order_by("tag");
				$this->db->where('estatus_general_id',1);
				$data['espacios']=$this->db->get('espacios_fisicos')->result();
				if (count($data['espacios'])==0)
					show_error('No existen Espacios Fisicos');
				$this->db->select("id,razon_social")->order_by("id");
				$this->db->where('estatus_general_id',1);
				$data['empresas']=$this->db->get('empresas')->result();
				if (count($data['empresas'])==0)
					show_error('No existen Empresas');
				if($this->uri->segment(5)=="pdf"){
					$fech1='';
					$periodo='';
					$fecha1 = $this->input->post('fecha1');
					if($fecha1 != false)
					{
						list($d,$m,$a) = explode(" ",$fecha1);
						$fecha1 = sprintf('%04d-%02d-%02d',$a,$m,$d);
					}
					$f1 = false;
					if($fecha1 == date("Y-m-d",strtotime($fecha1)))
						$f1 = true;
					if($f1){
						$periodo = "(f.fecha <= '$fecha1' OR c.fecha <= '$fecha1')";
						$fech1=$fecha1;
						$periodo='AND '.$periodo;
					}
					# obtener los clientes y espacios fisicos
					$clientes=array();
					$clien_ids=array();
					$espacios=array();
					$esp_ids=array();
					$this->db->select("id,razon_social as cliente")->order_by("cliente");
					if((int)$this->input->post('cliente')>0)
						$this->db->where('id',(int)$this->input->post('cliente'));
					else
						$this->db->where('estatus_general_id',1);
					$result=$this->db->get('cclientes')->result();
					foreach($result as $row){
						$clientes[$row->id]=$row->cliente;
						$clien_ids[]=$row->id;
					}
					$this->db->select("id,tag as espacio")->order_by("id,espacio");
					if((int)$this->input->post('espacio')>0)
						$this->db->where('id',(int)$this->input->post('espacio'));
					else
						$this->db->where('estatus_general_id',1);
					$result=$this->db->get('espacios_fisicos')->result();
					foreach($result as $row){
						$espacios[$row->id]=$row->espacio;
						$esp_ids[]=$row->id;
					}
					$vista=array();
					$this->db->select("razon_social");
					$this->db->where('id',$_POST['empresa']);
					$vista['empresa']=$this->db->get('empresas')->result_array();
					$vista['ef']=false;
					$this->db->select("tag");
					$this->db->where('id',$_POST['espacio']);
					$vista['esp']=$this->db->get('espacios_fisicos')->result_array();
					$bloques=array();
					$facturas=array();
					# obtener las facturas
					$this->db->select("id,folio_factura as factura,fecha as fecha_alta,monto_total");
					//$this->db->where("estatus_factura_id",2);
					$this->db->where_in("estatus_factura_id",array(2,3));// aceptar facturas con estatus 2 y 3 #22-07-10
					if((int)$_POST['cliente'] > 0)
						$this->db->where("cclientes_id",$_POST['cliente']);
					$this->db->order_by('factura,fecha_alta');
					$res=$this->db->get('cl_facturas')->result();
					if(count($res)==0)
						show_error('No hay Facturas');
					foreach($res as $row){
						$facturas[$row->id]=array(
								'id'=>$row->id,
								'factura'=>$row->factura,
								'fecha_alta'=>$row->fecha_alta,
								'monto_total'=>$row->monto_total
						);
						$fact_ids[]=$row->id;
					}
					$order_by="order by cliente, espacio, fecha_alta, fecha";
					if($this->input->post('cliente')>0 && $this->input->post('espacio')>0)
						$where=" where f.estatus_factura_id in (2,3) and f.estatus_general_id=1 and f.tipo_factura_id<3 and cc.id=$_POST[cliente] and ef.id=$_POST[espacio] and f.empresas_id=$_POST[empresa]";
					if($this->input->post('cliente')>0 && $this->input->post('espacio')==0)
						$where=" where f.estatus_factura_id in (2,3) and f.estatus_general_id=1 and f.tipo_factura_id<3 and cc.id=$_POST[cliente] and f.empresas_id=$_POST[empresa]";
					if($this->input->post('cliente')==0 && $this->input->post('espacio')>0)
						$where=" where f.estatus_factura_id in (2,3) and f.estatus_general_id=1 and f.tipo_factura_id<3 and ef.id=$_POST[espacio] and f.empresas_id=$_POST[empresa]";
					if($this->input->post('cliente')==0 && $this->input->post('espacio')==0)
						$where=" where f.estatus_factura_id in (2,3) and f.estatus_general_id=1 and f.tipo_factura_id<3 and cc.estatus_general_id=1 and f.empresas_id=$_POST[empresa]";
					# obtener los datos de entradas/salidas
					foreach($clien_ids as $prid){
						$sql="SELECT cc.id AS cliente, cc.razon_social, ef.id AS espacio, f.empresas_id, f.id AS id_factura, f.folio_factura AS factura, f.fecha AS fecha_alta, f.monto_total, c.fecha, c.monto_pagado FROM cl_facturas AS f LEFT JOIN cobros AS c ON c.cl_factura_id = f.id LEFT JOIN cclientes AS cc ON cc.id = f.cclientes_id LEFT JOIN usuarios AS u ON u.id = f.usuario_id LEFT JOIN espacios_fisicos AS ef ON ef.id = u.espacio_fisico_id $where and cc.id=$prid $periodo $order_by";
						$result=$this->db->query($sql)->result();
						//echo $sql;exit;
						if (count($result) > 0){
							foreach($result as $row){
								$bloques[$row->cliente][$row->espacio][$row->id_factura][]=array('id_factura'=>$row->id_factura,'factura'=>$row->factura,'fecha_alta'=>$row->fecha_alta,'monto_total'=>$row->monto_total,'fecha'=>$row->fecha,'monto_pagado'=>$row->monto_pagado);
							}
						}
					}
					##### Inicio Bloque para eliminar del array facturas con estatus_factura_id=3 que esten liquidadas a la fecha #22-07-10
					if(!$fecha1)
						$fecha1=date("Y-m-d");
					foreach($bloques as $cid=>$cliente){
						foreach($cliente as $eid=>$espacio){
							foreach($espacio as $fid=>$id_factura){
								$abono_total=(float)0;
								$cargo_total=(float)0;
								$saldo=(float)0;
								foreach($id_factura as $movimiento){
									if($movimiento['fecha_alta'] < $fecha1){
										$cargo_total=$movimiento['monto_total'];
										if($movimiento['fecha'] && $movimiento['fecha'] < $fecha1){
											$abono_total+=$movimiento['monto_pagado'];
										}
									}
									$saldo=$cargo_total-$abono_total;
								}
								if($saldo<=0){
									unset ($bloques[$cid][$eid][$fid]);
								}
							}
						}
					}
					##### Fin Bloque para eliminar del array facturas con estatus_factura_id = 3 que esten liquidadas a la fecha			##### Inicio Bloque para depurar el array con Facturas, Espacios fisicos y Clientes inexistentes #22-07-10
					$clien=0;$esp=0;$fac=0;$mov=0;
					foreach($bloques as $cid=>$cliente){
						$clien++;
						$esp=0;
						foreach($cliente as $eid=>$espacio){
							$fac=0;
							foreach($espacio as $fid=>$id_factura){
								$fac++;
								$mov=0;
								foreach($id_factura as $movimiento){
									$mov++;
								}
								if($mov==0){
									unset ($bloques[$cid][$eid][$fid]);
								}
							}
							if($fac==0){
								unset ($bloques[$cid][$eid]);
							} else {
								$esp++;
							}
						}
						if($esp==0){
							unset ($bloques[$cid]);
						}
					}
					if($clien==0){
						unset ($bloques);
					}
					##### Fin Bloque para depurar el array con Facturas, Espacios fisicos y Clientes inexistentes


					# checar que haya datos
					if(count($bloques)==0)
						show_error('No hay registros que cumplan los criterios.');
					if($f1)
						$vista['periodo']='A LA FECHA '.implode("-", array_reverse(explode("-", $fecha1)));
					else
						$vista['periodo']='A LA FECHA '.date("d-m-Y");
					$vista['fecha']=$fech1;
					$vista['bloques']=$bloques;
					$vista['clientes']=$clientes;
					$vista['espacios']=$espacios;
					$vista['facturas']=$facturas;
					$vista['title'] = $this->input->post('title');
					$this->load->view("$ruta/rep_antiguedad_saldos_clientes_pdf", $vista);
				}
			}
			else if($subfuncion=='rep_edo_cuenta_proveedores'){
				//Cargar los datos para el formulario
				$data['title']="ESTADO DE CUENTA DE PROVEEDORES";
				$data['frames']=1;
				$data['principal']=$ruta."/".$subfuncion;
				$data['funcion']=$subfuncion;
				$this->db->select("id,razon_social")->order_by("razon_social");
				$this->db->where('estatus_general_id',1);
				$data['proveedores']=$this->db->get('cproveedores')->result();
				if (count($data['proveedores'])==0)
					show_error('No existen Proveedores');
				$this->db->select("id,tag")->order_by("tag");
				$this->db->where('estatus_general_id',1);
				$data['espacios']=$this->db->get('espacios_fisicos')->result();
				if (count($data['espacios'])==0)
					show_error('No existen Espacios Fisicos');
				$this->db->select("id,razon_social")->order_by("id");
				$this->db->where('estatus_general_id',1);
				$data['empresas']=$this->db->get('empresas')->result();
				if (count($data['empresas'])==0)
					show_error('No existen Empresas');
				if($this->uri->segment(5)=="pdf"){
					$fech1='';
					$fech2='';
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
							$periodo = "((f.fecha between '$fecha1' and '$fecha2') OR (p.fecha between '$fecha1' and '$fecha2'))";
							$fech1=$fecha1;
							$fech2=$fecha2;
						}

						elseif(strcmp($fecha2,$fecha1) < 0){
							$periodo = "((f.fecha between '$fecha2' and '$fecha1') OR (p.fecha between '$fecha2' and '$fecha1'))";
							$fech1=$fecha2;
							$fech2=$fecha1;
						}

						else
						{
							$periodo = "(f.fecha <= '$fecha1' OR p.fecha <= '$fecha1')";
							$fech1=$fecha1;
						}

					}
					elseif($f1){
						$periodo = "(f.fecha <= '$fecha1' OR p.fecha <= '$fecha1')";
						$fech1=$fecha1;
					}

					elseif($f2){
						$periodo = "(f.fecha <= '$fecha2' OR p.fecha <= '$fecha2')";
						$fech1=$fecha2;
					}
					if($f1 || $f2)
						$periodo='AND '.$periodo;
					# obtener los proveedores y espacios fisicos
					$proveedores=array();
					$prov_ids=array();
					$espacios=array();
					$esp_ids=array();
					$this->db->select("id,razon_social as proveedor")->order_by("id,proveedor");
					if((int)$this->input->post('proveedor')>0)
						$this->db->where('id',(int)$this->input->post('proveedor'));
					else
						$this->db->where('estatus_general_id',1);
					$result=$this->db->get('cproveedores')->result();
					foreach($result as $row){
						$proveedores[$row->id]=$row->proveedor;
						$prov_ids[]=$row->id;
					}
					$this->db->select("id,tag as espacio")->order_by("id,espacio");
					if((int)$this->input->post('espacio')>0)
						$this->db->where('id',(int)$this->input->post('espacio'));
					else
						$this->db->where('estatus_general_id',1);
					$result=$this->db->get('espacios_fisicos')->result();
					foreach($result as $row){
						$espacios[$row->id]=$row->espacio;
						$esp_ids[]=$row->id;
					}
					$vista=array();
					$this->db->select("razon_social");
					$this->db->where('id',$_POST['empresa']);
					$vista['empresa']=$this->db->get('empresas')->result_array();
					$vista['ef']=false;
					$this->db->select("tag");
					$this->db->where('id',$_POST['espacio']);
					$vista['esp']=$this->db->get('espacios_fisicos')->result_array();
					$bloques=array();
					$facturas=array();
					# obtener las facturas
					$this->db->select("id,folio_factura as factura,fecha as fecha_alta,monto_total");
					$this->db->where_in("estatus_factura_id",array(1,2,3));
					if((int)$_POST['proveedor'] > 0)
						$this->db->where("cproveedores_id",$_POST['proveedor']);
					$this->db->order_by('factura,fecha_alta');
					$res=$this->db->get('pr_facturas')->result();
					if(count($res)==0)
						show_error('No hay Facturas');
					foreach($res as $row){
						$facturas[$row->id]=array(
								'id'=>$row->id,
								'factura'=>$row->factura,
								'fecha_alta'=>$row->fecha_alta,
								'monto_total'=>$row->monto_total
						);
						$fact_ids[]=$row->id;
					}
					$order_by="order by proveedor, espacio, fecha_alta, fecha";

					if($this->input->post('proveedor')>0 && $this->input->post('espacio')>0)
						$where=" where f.estatus_factura_id in (1,2,3) and f.estatus_general_id=1 and cp.id=$_POST[proveedor] and ef.id=$_POST[espacio] and f.empresas_id=$_POST[empresa]";
					if($this->input->post('proveedor')>0 && $this->input->post('espacio')==0)
						$where=" where f.estatus_factura_id in (1,2,3) and f.estatus_general_id=1 and cp.id=$_POST[proveedor] and f.empresas_id=$_POST[empresa]";
					if($this->input->post('proveedor')==0 && $this->input->post('espacio')>0){
						$where=" where f.estatus_factura_id in (1,2,3) and f.estatus_general_id=1 and ef.id=$_POST[espacio] and f.empresas_id=$_POST[empresa]";
						$vista['ef']=true;
					}
					if($this->input->post('proveedor')==0 && $this->input->post('espacio')==0)
						$where=" where f.estatus_factura_id in (1,2,3) and f.estatus_general_id=1 and cp.estatus_general_id=1 and f.empresas_id=$_POST[empresa]";

					# obtener los datos de facturas
					$sql="SELECT cp.id AS proveedor, ef.id AS espacio, f.empresas_id, f.id AS id_factura, f.folio_factura AS factura, f.fecha AS fecha_alta, f.monto_total, p.fecha, p.monto_pagado FROM pr_facturas AS f LEFT JOIN pagos AS p ON p.pr_factura_id = f.id LEFT JOIN cproveedores AS cp ON cp.id = f.cproveedores_id LEFT JOIN usuarios AS u ON u.id = f.usuario_id LEFT JOIN espacios_fisicos AS ef ON ef.id = f.espacios_fisicos_id $where $periodo $order_by";
					$result=$this->db->query($sql)->result();
					//echo $sql;exit;
					if (count($result) > 0){
						foreach($result as $row){
							$bloques[$row->proveedor][$row->espacio][$row->id_factura][]=array('id_factura'=>$row->id_factura,'factura'=>$row->factura,'fecha_alta'=>$row->fecha_alta,'monto_total'=>$row->monto_total,'fecha'=>$row->fecha,'monto_pagado'=>$row->monto_pagado);
						}
					}
					# checar que haya datos
					if(count($bloques)==0)
						show_error('No hay registros que cumplan los criterios.');
					if($f1 || $f2){
						if($f1 && !$f2)
							$vista['periodo']='A LA FECHA '.implode("-", array_reverse(explode("-", $fecha1)));
						elseif(!$f1 && $f2)
						$vista['periodo']='A LA FECHA '.implode("-", array_reverse(explode("-", $fecha2)));
						elseif($fecha1==$fecha2)
						$vista['periodo']='A LA FECHA '.implode("-", array_reverse(explode("-", $fecha1)));
						else
							$vista['periodo']='DEL '.implode("-", array_reverse(explode("-", $fecha1))).' AL '.implode("-", array_reverse(explode("-", $fecha2)));
					}
					else
						$vista['periodo']='A LA FECHA '.date("d-m-Y");
					$vista['fecha1']=$fech1;
					$vista['fecha2']=$fech2;
					$vista['bloques']=$bloques;
					$vista['proveedores']=$proveedores;
					$vista['espacios']=$espacios;
					$vista['facturas']=$facturas;
					$vista['title'] = $this->input->post('title');
					$this->load->view("$ruta/rep_edo_cuenta_proveedores_pdf", $vista);
				}
			}
			else if($subfuncion=='rep_edo_cuenta_clientes'){
				//Cargar los datos para el formulario
				$data['title']="ESTADO DE CUENTA DE CLIENTES";
				$data['frames']=1;
				$data['principal']=$ruta."/".$subfuncion;
				$data['funcion']=$subfuncion;
				$this->db->select("id,razon_social")->order_by("razon_social");
				$this->db->where('estatus_general_id',1);
				$data['clientes']=$this->db->get('cclientes')->result();
				if (count($data['clientes'])==0)
					show_error('No existen Clientes');
				$this->db->select("id,tag")->order_by("tag");
				$this->db->where('estatus_general_id',1);
				$data['espacios']=$this->db->get('espacios_fisicos')->result();
				if (count($data['espacios'])==0)
					show_error('No existen Espacios Fisicos');
				$this->db->select("id,razon_social")->order_by("id");
				$this->db->where('estatus_general_id',1);
				$data['empresas']=$this->db->get('empresas')->result();
				if (count($data['empresas'])==0)
					show_error('No existen Empresas');
				if($this->uri->segment(5)=="pdf")
				{
					$fech1='';
					$fech2='';
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
					if($f1 && $f2)
					{
						if(strcmp($fecha2,$fecha1) > 0)
						{
							$periodo = "((f.fecha between '$fecha1' and '$fecha2') OR (c.fecha between '$fecha1' and '$fecha2'))";
							$fech1=$fecha1;
							$fech2=$fecha2;
						}

						elseif(strcmp($fecha2,$fecha1) < 0)
						{
							$periodo = "((f.fecha between '$fecha2' and '$fecha1') OR (c.fecha between '$fecha2' and '$fecha1'))";
							$fech1=$fecha2;
							$fech2=$fecha1;
						}

						else
						{
							$periodo = "(f.fecha <= '$fecha1' OR c.fecha <= '$fecha1')";
							$fech1=$fecha1;
						}

					}
					elseif($f1)
					{
						$periodo = "(f.fecha <= '$fecha1' OR c.fecha <= '$fecha1')";
						$fech1=$fecha1;
					}

					elseif($f2)
					{
						$periodo = "(f.fecha <= '$fecha2' OR c.fecha <= '$fecha2')";
						$fech1=$fecha2;
					}
					if($f1 || $f2)
						$periodo='AND '.$periodo;
					# obtener los clientes y espacios fisicos
					$clientes=array();
					$clien_ids=array();
					$espacios=array();
					$esp_ids=array();
					$this->db->select("id,razon_social as cliente,clave")->order_by("cliente");# 12.Jun.2010 @aku
					if((int)$this->input->post('cliente')>0)
						$this->db->where('id',(int)$this->input->post('cliente'));
					else
						$this->db->where('estatus_general_id',1);
					$result=$this->db->get('cclientes')->result();
					foreach($result as $row)
					{
						$clientes[$row->id]['id']=$row->id;
						$clientes[$row->id]['cliente']=$row->cliente;
						$clientes[$row->id]['clave']=$row->clave;
						$clien_ids[]=$row->id;
					}
					$this->db->select("id,tag as espacio")->order_by("id,espacio");
					if((int)$this->input->post('espacio')>0)
						$this->db->where('id',(int)$this->input->post('espacio'));
					else
						$this->db->where('estatus_general_id',1);
					$result=$this->db->get('espacios_fisicos')->result();
					foreach($result as $row)
					{
						$espacios[$row->id]=$row->espacio;
						$esp_ids[]=$row->id;
					}
					$vista=array();
					$this->db->select("razon_social");
					$this->db->where('id',$_POST['empresa']);
					$vista['empresa']=$this->db->get('empresas')->result_array();
					$vista['ef']=false;
					$this->db->select("tag");
					$this->db->where('id',$_POST['espacio']);
					$vista['esp']=$this->db->get('espacios_fisicos')->result_array();
					$bloques=array();
					$facturas=array();
					# obtener las facturas
					$this->db->select("id,folio_factura as factura,fecha as fecha_alta,monto_total");
					$this->db->where_in("estatus_factura_id",array(1,2,3));
					if((int)$_POST['cliente'] > 0)
						$this->db->where("cclientes_id",$_POST['cliente']);
					$this->db->order_by('factura,fecha_alta');
					$res=$this->db->get('cl_facturas')->result();
					if(count($res)==0)
						show_error('No hay Facturas');
					foreach($res as $row)
					{
						$facturas[$row->id]=array(
								'id'=>$row->id,
								'factura'=>$row->factura,
								'fecha_alta'=>$row->fecha_alta,
								'monto_total'=>$row->monto_total
						);
						$fact_ids[]=$row->id;
					}
					$order_by="order by cliente, espacio, fecha_alta, fecha";
					if($this->input->post('cliente')>0 && $this->input->post('espacio')>0)
						$where=" where f.estatus_factura_id in (1,2,3) and f.estatus_general_id=1 and cc.id=$_POST[cliente] and ef.id=$_POST[espacio] and f.empresas_id=$_POST[empresa]";
					if($this->input->post('cliente')>0 && $this->input->post('espacio')==0)
						$where=" where f.estatus_factura_id in (1,2,3) and f.estatus_general_id=1 and cc.id=$_POST[cliente] and f.empresas_id=$_POST[empresa]";
					if($this->input->post('cliente')==0 && $this->input->post('espacio')>0)
					{
						$where=" where f.estatus_factura_id in (1,2,3) and f.estatus_general_id=1 and ef.id=$_POST[espacio] and f.empresas_id=$_POST[empresa]";
						$vista['ef']=true;
					}
					if($this->input->post('cliente')==0 && $this->input->post('espacio')==0)
						$where=" where f.estatus_factura_id in (1,2,3) and f.estatus_general_id=1 and cc.estatus_general_id=1 and f.empresas_id=$_POST[empresa]";

					# obtener los datos de entradas/salidas
					foreach($clien_ids as $cid)# 12.Jun.2010 @aku
					{
						$sql="SELECT cc.id AS cliente, ef.id AS espacio, f.empresas_id, f.id AS id_factura, f.folio_factura AS factura, f.fecha AS fecha_alta, f.monto_total, c.fecha, c.monto_pagado FROM cl_facturas AS f LEFT JOIN cobros AS c ON c.cl_factura_id = f.id LEFT JOIN cclientes AS cc ON cc.id = f.cclientes_id LEFT JOIN usuarios AS u ON u.id = f.usuario_id LEFT JOIN espacios_fisicos AS ef ON ef.id = u.espacio_fisico_id $where and cc.id=$cid $periodo $order_by";
						$result=$this->db->query($sql)->result();
						//echo $sql;exit;
						if (count($result) > 0)
						{
							foreach($result as $row)
							{
								$bloques[$row->cliente][$row->espacio][$row->id_factura][]=array('id_factura'=>$row->id_factura,'factura'=>$row->factura,'fecha_alta'=>$row->fecha_alta,'monto_total'=>$row->monto_total,'fecha'=>$row->fecha,'monto_pagado'=>$row->monto_pagado);
							}
						}
					}
					# checar que haya datos
					if(count($bloques)==0)
						show_error('No hay registros que cumplan los criterios.');
					if($f1 || $f2)
					{
						if($f1 && !$f2)
							$vista['periodo']='A LA FECHA '.implode("-", array_reverse(explode("-", $fecha1)));
						elseif(!$f1 && $f2)
						$vista['periodo']='A LA FECHA '.implode("-", array_reverse(explode("-", $fecha2)));
						elseif($fecha1==$fecha2)
						$vista['periodo']='A LA FECHA '.implode("-", array_reverse(explode("-", $fecha1)));
						else
							$vista['periodo']='DEL '.implode("-", array_reverse(explode("-", $fecha1))).' AL '.implode("-", array_reverse(explode("-", $fecha2)));
					}
					else
						$vista['periodo']='A LA FECHA '.date("d-m-Y");
					$vista['fecha1']=$fech1;
					$vista['fecha2']=$fech2;
					$vista['bloques']=$bloques;
					$vista['clientes']=$clientes;
					$vista['espacios']=$espacios;
					$vista['facturas']=$facturas;
					$vista['title'] = $this->input->post('title');
					$this->load->view("$ruta/rep_edo_cuenta_clientes_pdf", $vista);
				}
			}

			if($main_view){
				//Llamar a la vista
				$this->load->view("ingreso", $data);
				unset($data);
			} else {
				//redirect(base_url()."index.php/inicio/logout");
			}
		}
	}
	function rep_pr_facturas_pdf()
	{ // BEGIN function rep_pr_facturas_pdf
		global $ruta;
		//Obtener los datos
		//echo "<pre>";var_dump($_POST);echo "</pre>";exit();
		$filtro = array();
		if((int)$_POST['empresa']>0)
			$filtro[] = "prf.empresas_id = ".(int)$_POST['empresa'];
		if((int)$_POST['proveedor']>0)
			$filtro[] = "prf.cproveedores_id = ".(int)$_POST['proveedor'];
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
				$filtro[] = "(prf.fecha >= '$fecha1' and prf.fecha <= '$fecha2')";
			else
				$filtro[] = "(prf.fecha >= '$fecha2' and prf.fecha <= '$fecha1')";
		}
		elseif($f1)
		$filtro[] = "prf.fecha = '$fecha1'";
		elseif($f2)
		$filtro[] = "prf.fecha = '$fecha2'";
		if (count($filtro))
			$where = " where ".implode(" and ", $filtro)." ";
		else
			$where = "";
		$nivel = array();
		$nivel[]=(int)$_POST['nivel1'];
		$nivel[]=(int)$_POST['nivel2'];
		$nivel[]=(int)$_POST['nivel3'];
		$campos = array();
		foreach ($nivel as $my_key=>$my_value){
			switch($my_value){
				case 1: $campos[] = 'fecha'; break;
				case 2: $campos[] = 'proveedor'; break;
				case 3: $campos[] = 'empresa'; break;
			}
		}
		if (count($campos))
			$order_by = " order by ".implode(",", $campos)." ";
		else
			$order_by = "";
		//die(var_dump($order_clause));
		$data['title'] = $_POST['title'];
		$data['facturas']=$this->pr_factura->get_pr_facturas_pdf($where,$order_by);
		if(!$data['facturas'])
			show_error('No hay registros que cumplan los criterios.');
		$this->load->view("$ruta/rep_pr_facturas_pdf", $data);
		unset($data);
	} // END function rep_pr_facturas_pdf ############################################

	function rep_pagos_pdf()
	{ // BEGIN method rep_pagos_pdf
		global $ruta;
		//Obtener los datos
		//echo "<pre>";var_dump($_POST);echo "</pre>";exit();
		$filtro = array();
		$proveedor = (int)$this->input->post('proveedor');
		if($proveedor>0)
			$filtro[] = "cproveedores.id = ".$proveedor;
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
		// Validar las fechas
		if($fecha1 == date("Y-m-d",strtotime($fecha1)))
			$f1 = true;
		if($fecha2 == date("Y-m-d",strtotime($fecha2)))
			$f2 = true;
		$campo_fecha = 'pagos.fecha';// campo para filtrar la fecha
		// Evaluar como se filtrara la fecha
		if($f1 && $f2){
			if(strcmp($fecha2,$fecha1) > 0)
				$filtro[] = "($campo_fecha >= '$fecha1' and $campo_fecha <= '$fecha2')";
			else
				$filtro[] = "($campo_fecha >= '$fecha2' and $campo_fecha <= '$fecha1')";
		}
		elseif($f1)
		$filtro[] = "$campo_fecha = '$fecha1'";
		elseif($f2)
		$filtro[] = "$campo_fecha = '$fecha2'";
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
		$campos = array();
		foreach ($nivel as $my_key=>$my_value){
			switch($my_value){
				case 1: $campos[] = 'fecha'; break;
				case 2: $campos[] = 'proveedor'; break;
				case 3: $campos[] = 'folio'; break;
				case 4: $campos[] = 'numero_cuenta'; break;
				case 5: $campos[] = 'forma_pago'; break;
				case 6: $campos[] = 'tipo_pago'; break;
			}
		}
		if (count($campos))
			$order_by = " order by ".implode(",", $campos)." ";
		else
			$order_by = "";
		//echo "<pre>";var_dump($order_by);echo "</pre>";die();
		$data['title'] = $this->input->post('title');
		$data['pagos']=$this->pago->get_pagos_pdf($where,$order_by);
		if(!$data['pagos'])
			show_error('No hay registros que cumplan los criterios.');
		$this->load->view("$ruta/rep_pagos_pdf", $data);
		unset($data);
	} // END method rep_pagos_pdf #########################################################

	function rep_gen_ventas_pdf()
	{ // BEGIN method rep_salidas_pdf
		global $ruta;
		//		echo "<pre>";var_dump($_POST);echo "</pre>";exit();
		//print_r(array_keys($_POST));
		$filtro = array();
		$filtro[] = "s.ctipo_salida_id=1";
		if((int)$_POST['espaciof']>0)
			$filtro[] = "s.espacios_fisicos_id = ".(int)$_POST['espaciof'];
		if (count($filtro))
			$where = " where s.estatus_general_id=1 and s.ctipo_salida_id=1 and ".implode(" and ", $filtro)." ";
		else
			$where = "";
		//Fecha
		if(isset($_POST['fecha1'])==false) {
			$fecha1=date("Y-m-d");
			$fecha2=date("Y-m-d", strtotime(date("Y-m-d"))+(24 * 60 * 60));
		} else {
			$fecha=explode(" ", $_POST['fecha1']);
			$fecha1=$fecha[2]."-".$fecha[1]."-".$fecha[0];
			$fecha=explode(" ", $_POST['fecha2']);
			$fecha2a=$fecha[2]."-".$fecha[1]."-".$fecha[0];
			$fecha2=date("Y-m-d", strtotime($fecha[2]."-".$fecha[1]."-".$fecha[0]) + (1*24 * 60 * 60));
			//					$fecha2=$fecha2a;
			$data['fecha1']=$_POST['fecha1'];
			$data['fecha2']=$_POST['fecha2'];
		}
		$tunix1=strtotime($fecha1);
		$tunix2=strtotime($fecha2);
		$diff=($tunix2-$tunix1) / (24 * 60 * 60);
		//echo $fecha2."%$fecha2a<br/>";

		if($diff<=0)
			show_error("Las fechas seleccionadas no son válidas");
		$diff=round($diff,0);
		$data['diff']=$diff;
		$fecha_inicial=$tunix1;
		$colect=array();
		for($x=0;$x<$diff;$x++){
			$fecha_uno=$fecha_inicial;
			$fecha_dos=$fecha_uno+(24 * 60 *60);
			$where_ini =" and fecha >='". date("Y-m-d", $fecha_uno) ."' and fecha <'". date("Y-m-d", $fecha_dos) ."'";
			/*echo $where*/;
			//echo $where_ini."<br/>";
			$query = $this->db->query("SELECT distinct(espacios_fisicos_id), sum(costo_total) as total, sum(tasa_impuesto*costo_total/(100+tasa_impuesto)) as iva, ef.tag AS espacio_fisico FROM salidas AS s  Left Join espacios_fisicos AS ef ON ef.id = s.espacios_fisicos_id $where $where_ini group by espacios_fisicos_id");
			if($query->num_rows()>0){
				$f=date("Y-m-d", $fecha_uno);
				foreach($query->result() as $row){
					$colect["$x"]["$row->espacios_fisicos_id"]['id']=$row->espacios_fisicos_id;
					$colect["$x"]["$row->espacios_fisicos_id"]['fecha']=$f;
					$colect["$x"]["$row->espacios_fisicos_id"]['tag']=$row->espacio_fisico;
					$colect["$x"]["$row->espacios_fisicos_id"]['monto_total']=$row->total;
					$colect["$x"]["$row->espacios_fisicos_id"]['iva']=$row->iva;
				}
			} else {
				/*			    $colect["$x"]["$row->espacios_fisicos_id"]['id']=$row->espacios_fisicos_id;
				 $colect["$x"]["$row->espacios_fisicos_id"]['fecha']=$f;
				$colect["$x"]["$row->espacios_fisicos_id"]['tag']=$row->espacio_fisico;
				$colect["$x"]["$row->espacios_fisicos_id"]['monto_total']=$row->total;*/
			}
			$fecha_inicial=$fecha_dos;
		}

		$data['salidas']=$colect;
		$data['title'] = $this->input->post('title');
		$query->free_result();
		$this->load->view("$ruta/rep_gen_ventas_pdf", $data);
		unset($data);

	} // END method rep_salidas_pdf #########################################################

	function rep_depositos_tiendas_pdf()
	{ // BEGIN method rep_salidas_pdf
		global $ruta;
		//		echo "<pre>";var_dump($_POST);echo "</pre>";exit();
		//print_r(array_keys($_POST));
		$filtro = array();
		if((int)$_POST['espaciof']>0)
			$filtro[] = "c.espacios_fisicos_id = ".(int)$_POST['espaciof'];
		if (count($filtro))
			$where = " where c.estatus_general_id=1 and ".implode(" and ", $filtro)." ";
		else
			$where = "";
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
		$where .=" and fecha_deposito>='$fecha1' and fecha_deposito<'$fecha2' ";

		$nivel[]=(int)$_POST['nivel_1'];
		$nivel[]=(int)$_POST['nivel_2'];
		$campos=array();
		foreach ($nivel as $my_key=>$my_value){
			switch($my_value){
				case 1: $campos[]='c.fecha_deposito'; break;
				case 2: $campos[]='c.espacios_fisicos_id'; break;
			}
		}
		if (count($campos))
			$order_by=" order by ".implode(",", $campos)." ";
		else
			$order_by="";
		$query=$this->db->query("select c.*, e.tag as espacio, cb.banco as nombre_banco, cb.numero_cuenta as numero_cuenta from control_depositos as c left join espacios_fisicos as e on e.id=c.espacios_fisicos_id left join ccuentas_bancarias as cb on cb.id=c.cuenta_bancaria_id $where $order_by");

		//echo $query->num_rows();
		$data['depositos']=$query;
		$data['title'] = $this->input->post('title');
		$this->load->view("$ruta/rep_depositos_tiendas_pdf", $data);
		unset($data);
	} // END method rep_salidas_pdf #########################################################


	function rep_cuentas_contables_pdf(){
		global $ruta;
		$cuenta= $this->input->post('cuenta');
		if(is_numeric($cuenta)==false)
			show_error("Error 1: Error de cuenta principal = $id");
		$data['empresa']=$this->empresa->get_empresa($GLOBALS['empresa_id']);
		$data['title']="Catálogo de Cuentas Contables";
		$data['cuentas']=$this->cuenta_contable->get_cuentas_contables_pdf($cuenta, $GLOBALS['empresa_id']);
		if($data['cuentas']==false)
			show_error("No existen cuentas contables");
		$this->load->view("$ruta/rep_cuentas_contables_pdf", $data);
		unset($data);

	}

	function rep_pagos_multiples_pdf (){
		$vista=array();
		//Cargar los datos para el formulario
		if(strlen($this->uri->segment(4))>0)
			$proveedor_id=$this->uri->segment(4);
		else if (isset($_POST['proveedor']) and $_POST['proveedor']>0 )
			$proveedor_id=$this->input->post('proveedor');
		if(strlen($this->uri->segment(5))>0) {
			$fecha_usr=$this->uri->segment(5);
			$fecha=explode("-", $this->uri->segment(5));
			$fecha1=$fecha[2]."-".$fecha[1]."-".$fecha[0];
		} else if (isset($_POST['fecha1'])){
			$fecha=$this->input->post('fecha1');
			$fecha_usr=$fecha;
			$fecha=explode(" ", $_POST['fecha1']);
			$fecha1=$fecha[2]."-".$fecha[1]."-".$fecha[0];
		} else {
			$fecha_usr=date("d m Y");
			$fecha1=date("Y-m-d");
		}
		$temporal=explode("-", $fecha1);
		//Obtener los Proveedores
		$vista['proveedores']=$this->proveedor->get_by_id($proveedor_id);
		$vista['proveedor_tag']=$vista['proveedores']->razon_social;
		//echo $vista['proveedores']->razon_social."&&";
		//Obtener los espacios fisicos
		$this->db->select("id,tag")->order_by("tag");
		$this->db->where('estatus_general_id',1);
		$this->db->where('tipo_espacio_id',1);
		$data['espacios']=$this->db->get('espacios_fisicos')->result();
		if (count($data['espacios'])==0)
			show_error('No existen Espacios Fisicos');
		//Obtener las empresas
		$this->db->select("id,razon_social")->order_by("id");
		$this->db->where('estatus_general_id',1);
		$data['empresas']=$this->db->get('empresas')->result();
		if (count($data['empresas'])==0)
			show_error('No existen Empresas');
		# obtener los proveedores y espacios fisicos
		$proveedores=array();
		$prov_ids=array();
		$espacios=array();
		$esp_ids=array();
		$proveedores[$proveedor_id]=$vista['proveedores']->razon_social;
		$prov_ids[]=$proveedor_id;
		$this->db->select("id,tag as espacio")->order_by("id,espacio");
		$this->db->where('estatus_general_id',1);
		$result=$this->db->get('espacios_fisicos')->result();
		//Datamapper
		$_POST['espacio']=0;
		$vista['esp']=$this->espacio_fisico->get_by_id($_POST['espacio']);
		foreach($result as $row) {
			$espacios[$row->id]=$row->espacio;
			$esp_ids[]=$row->id;
		}
		$this->db->select("razon_social");
		$vista['empresa']=$this->db->get('empresas')->result_array();
		$vista['ef']=false;
		$this->db->select("tag");
		$vista['esp']=$this->db->get('espacios_fisicos')->result_array();
		$bloques=array();
		$facturas=array();
		# obtener las facturas
		$this->db->select("id,folio_factura as factura,fecha as fecha_alta,monto_total");
		//$this->db->where("estatus_factura_id",2);
		$this->db->where_in("estatus_factura_id",array(2,3));// aceptar facturas con estatus 2 y 3 #22-07-10
		$this->db->where("cproveedores_id", $proveedor_id);
		$this->db->order_by('factura,fecha_alta');
		$res=$this->db->get('pr_facturas')->result();
		if(count($res)==0)
			show_error('No hay Facturas');
		foreach($res as $row) {
			$facturas[$row->id]=array(
					'id'=>$row->id,
					'factura'=>$row->factura,
					'fecha_alta'=>$row->fecha_alta,
					'monto_total'=>$row->monto_total
			);
			$fact_ids[]=$row->id;
		}
		$periodo=" and p.fecha='".$temporal[0]."-".$temporal[1]."-".$temporal[2]."' ";
		$fecha1=$temporal[0]."-".$temporal[1]."-".$temporal[2];
		$order_by="order by cp.razon_social, espacio, fecha_pago asc";
		$where=" where f.estatus_factura_id in (2,3) and f.estatus_general_id=1  ";
		$where.="  and cp.id=$proveedor_id ";
		# obtener los datos de facturas
		//foreach($prov_ids as $prid){
		$sql="SELECT cp.id AS proveedor, cp.razon_social, ef.id AS espacio, f.empresas_id, f.id AS id_factura, f.folio_factura AS factura, f.fecha AS fecha_alta, f.monto_total, p.fecha as fechap, p.monto_pagado, p.salidas_str, f.fecha_pago as vencimiento, mp.tag as marca, p.numero_referencia FROM pr_facturas AS f LEFT JOIN pagos AS p ON p.pr_factura_id = f.id  LEFT JOIN espacios_fisicos AS ef ON ef.id = f.espacios_fisicos_id LEFT JOIN cproveedores AS cp ON cp.id = f.cproveedores_id left join cmarcas_productos as mp on mp.id=f.cmarca_id LEFT JOIN usuarios AS u ON u.id = f.usuario_id  $where  $periodo $order_by ";# 11.Jun.2010 @aku
		$result=$this->db->query($sql);
		if (count($result) > 0){
			$salidas_mtrx=array();
			foreach($result->result() as $row){
				if(isset($bloq[$row->espacio][$row->proveedor]['marca'])==false){
					$bloq[$row->espacio][$row->proveedor]['marca']=$row->marca;
				} else if ($bloq[$row->espacio][$row->proveedor]['marca']!=$row->marca){
					$bloq[$row->espacio][$row->proveedor]['marca']="Varias";
				}
				$bloques[$row->espacio][$row->proveedor][$row->id_factura][]=array('id_factura'=>$row->id_factura,'factura'=>$row->factura,'fecha_alta'=>$row->fecha_alta,'monto_total'=>$row->monto_total,'fecha'=>$row->fechap,'monto_pagado'=>$row->monto_pagado,'vencimiento'=>$row->vencimiento, 'numero_referencia'=>$row->numero_referencia);
				if(strlen($row->salidas_str)>0)
					$salidas_mtrx[]=$row->salidas_str;
			}
			//Obtener los detalles de las salidas para el reporte en la seccion de devoluciones
			if(count($salidas_mtrx)>0){
				$devolucion=array();
				//Obtener primero los que si tienen un lote y costo unitario
				foreach($salidas_mtrx as $k=>$v){
					$devolucion[]=$this->salida->get_salidas_detalles_devoluciones($v,1);
				}
				//obtener despues los que no tienen lote ni costo unitario
				foreach($salidas_mtrx as $k=>$v){
					$devolucion_0[]=$this->salida->get_salidas_detalles_devoluciones($v,0);
				}
				//  				print_r($devolucion); exit;
				$vista['devolucion']=$devolucion;
				$vista['devolucion_0']=$devolucion_0;
			}
		}
		# checar que haya datos
		if(count($bloques)==0)
			show_error('No hay registros que cumplan los criterios.');
		$vista['periodo']='FECHA DE PAGO:  '. $fecha_usr;
		$vista['fecha']=$fecha1;
		$vista['bloques']=$bloques;
		$vista['bloq']=$bloq;
		$vista['proveedores']=$proveedores;
		$vista['espacios']=$espacios;
		$vista['facturas']=$facturas;
		$vista['title'] = $this->input->post('title');
		$main_view=false;
		$this->load->view("contabilidad/rep_pagos_multiples_pdf", $vista);

	}
	function rep_global_deuda_pdf(){
			
		$filtro = array();
		$where = " where f.estatus_general_id=1 and validacion_contable>0 and estatus_factura_id=2 ";
		$data['criterios']="";
		//Proveedor
		if((int)$_POST['proveedor']>0){
			$where.= " and f.cproveedores_id = ".(int)$_POST['proveedor'];
			$proveedor=$this->proveedor->get_by_id($_POST['proveedor']);
			$data['criterios'].=utf8_decode("Proveedor: $proveedor->razon_social, ");
		} else
			$data['criterios'].=utf8_decode("Proveedor: TODOS ");
		//Espacio Fisico
		if((int)$_POST['espaciof']>0){
			$where .= " and f.espacios_fisicos_id = ".(int)$_POST['espaciof'];
			$espacio_fisico=$this->espacio_fisico->get_by_id($_POST['espaciof']);
			$data['criterios'].=utf8_decode("Sucursal : $espacio_fisico->tag, ");
		} else
			$data['criterios'].=utf8_decode("Sucursal: TODAS, ");

		//Subtipos
		if($this->input->post('subtipo')>0){
			$where .=" and e.subtipo_espacio_id={$_POST['subtipo']} ";
			$subtipo=$this->subtipo_espacio->get_by_id($_POST['subtipo']);
			$data['criterios'].=utf8_decode("Subtipo : $subtipo->tag, ");
		} else
			$data['criterios'].=utf8_decode("Subtipo: TODOS, ");

		//Detallar marcas
		if($this->input->post('desglosado')>0)
			$desglosar=true;
		else
			$desglosar=false;
		//Fecha
		if(isset($_POST['fecha'])==false) {
			$hoy=date("Y-m-d");
			$fecha=date("Y-m-d", strtotime($hoy));
		} else {
			$fecha1=explode(" ", $_POST['fecha']);
			$fecha=$fecha1[2]."-".$fecha1[1]."-".$fecha1[0];
			$data['fecha']=$_POST['fecha'];
		}
		$where .=" and f.fecha<='$fecha'  ";

		if($desglosar==false) {
			//Obtener las facturas que estan a crédito
			$facturas=$this->db->query("select f.id, f.fecha, cproveedores_id, monto_total, f.descuento, cmarca_id, p.razon_social as proveedor from pr_facturas as f left join cproveedores as p on p.id=f.cproveedores_id left join espacios_fisicos as e on e.id=f.espacios_fisicos_id $where order by proveedor, f.fecha asc ");
			if($facturas->num_rows()>0){
				foreach($facturas->result() as $row){
					$matriz_adeudo[$row->cproveedores_id]['proveedor']=$row->proveedor;
					//Descontar a cada factura los pagos que se han hecho
					$pago=new Pago();
					$pago->select("sum(monto_pagado) as pagado")->where('pr_factura_id', $row->id)->get();
					//Formar los renglones Proveedor | Marca o Marcas si es el caso | Saldo X vencer | Saldo Vencido | Total Adeudo |
					if(isset($matriz_adeudo[$row->cproveedores_id]['total_adeudo']))
						$matriz_adeudo[$row->cproveedores_id]['total_adeudo']+=$row->monto_total-$pago->pagado;
					else
						$matriz_adeudo[$row->cproveedores_id]['total_adeudo']=$row->monto_total-$pago->pagado;
					//identificar si el saldo ya se vencion
					if($row->fecha<=$fecha){
						if(isset($matriz_adeudo[$row->cproveedores_id]['saldo_vencido']))
							$matriz_adeudo[$row->cproveedores_id]['saldo_vencido']+=$row->monto_total-$pago->pagado;
						else
							$matriz_adeudo[$row->cproveedores_id]['saldo_vencido']=$row->monto_total-$pago->pagado;
					} else{
						//Saldo por vencer
						if(isset($matriz_adeudo[$row->cproveedores_id]['saldo_xvencer']))
							$matriz_adeudo[$row->cproveedores_id]['saldo_xvencer']+=$row->monto_total-$pago->pagado;
						else
							$matriz_adeudo[$row->cproveedores_id]['saldo_xvencer']=$row->monto_total-$pago->pagado;
					}
				} //Final foreach
			} else
				show_error("No hay facturas pendientes de pago validadas actualmente para esa fecha");
		} else {
			//Obtener las facturas que estan a crédito
			$facturas=$this->db->query("select f.id, f.fecha, cproveedores_id, monto_total, f.descuento, cmarca_id, p.razon_social as proveedor, m.tag as marca from pr_facturas as f left join cproveedores as p on p.id=f.cproveedores_id left join cmarcas_productos as m on m.id=f.cmarca_id left join espacios_fisicos as e on e.id=f.espacios_fisicos_id $where order by proveedor, marca, f.fecha asc ");
			if($facturas->num_rows()>0){
				foreach($facturas->result() as $row) {
					$matriz_adeudo[$row->cproveedores_id][$row->cmarca_id]['proveedor']=$row->proveedor;
					if($row->cmarca_id>0)
						$matriz_adeudo[$row->cproveedores_id][$row->cmarca_id]['marca']=$row->marca;
					else
						$matriz_adeudo[$row->cproveedores_id][$row->cmarca_id]['marca']="VARIAS";
					//Descontar a cada factura los pagos que se han hecho
					$pago=new Pago();
					$pago->select("sum(monto_pagado) as pagado")->where('pr_factura_id', $row->id)->get();
					//Formar los renglones Proveedor | Marca o Marcas si es el caso | Saldo X vencer | Saldo Vencido | Total Adeudo |
					if(isset($matriz_adeudo[$row->cproveedores_id][$row->cmarca_id]['total_adeudo']))
						$matriz_adeudo[$row->cproveedores_id][$row->cmarca_id]['total_adeudo']+=$row->monto_total-$pago->pagado;
					else
						$matriz_adeudo[$row->cproveedores_id][$row->cmarca_id]['total_adeudo']=$row->monto_total-$pago->pagado;
					//identificar si el saldo ya se vencio
					if($row->fecha<=$fecha){
						if(isset($matriz_adeudo[$row->cproveedores_id][$row->cmarca_id]['saldo_vencido']))
							$matriz_adeudo[$row->cproveedores_id][$row->cmarca_id]['saldo_vencido']+=$row->monto_total-$pago->pagado;
						else
							$matriz_adeudo[$row->cproveedores_id][$row->cmarca_id]['saldo_vencido']=$row->monto_total-$pago->pagado;
					} else{
						//Saldo por vencer
						if(isset($matriz_adeudo[$row->cproveedores_id][$row->cmarca_id]['saldo_xvencer']))
							$matriz_adeudo[$row->cproveedores_id][$row->cmarca_id]['saldo_xvencer']+=$row->monto_total-$pago->pagado;
						else
							$matriz_adeudo[$row->cproveedores_id][$row->cmarca_id]['saldo_xvencer']=$row->monto_total-$pago->pagado;
					}
				} //Final foreach
			} else
				show_error("No hay facturas pendientes de pago validadas actualmente para esa fecha");
		}
		//print_r($matriz_adeudo);
		$data['datos']=$matriz_adeudo;
		$data['desglosar']=$desglosar;
		$data['title'] = $this->input->post('title');
		$main_view=false;
		$this->load->view("contabilidad/rep_global_deuda_pdf", $data);

	}

	function corregir_proveedores_marcas(){
		//funcion para corregir las relaciones entre proveedores y marcas en las tablas de pedidos y facturas
		$query=$this->db->query("select f.id, f.fecha, f.cproveedores_id, f.pr_pedido_id, cmarca_id, p.id as pid, m.tag as marca from pr_facturas as f left join cmarcas_productos as m on m.id=cmarca_id left join cproveedores as p on p.id=m.proveedor_id where cmarca_id>0 order by f.id ");
		foreach($query->result() as $row){
			if($row->cproveedores_id!=$row->pid){
				//actualizar la relacion de la marca al proveedor en la tabla cmarcas_productos
				if($row->pid ==1){
					$this->db->query("update cmarcas_productos set proveedor_id=$row->cproveedores_id where id=$row->cmarca_id");
					//echo "Factura id: $row->id Proveedor_antiguo: $row->cproveedores_id Nuevo: $row->pid <br/>";
					echo "Caso 1 $row->id D- $row->fecha Marca: ($row->cmarca_id - $row->marca ) Prov_m: $row->pid Prov_pr_f: $row->cproveedores_id <br/>";
				} else {
					if($row->cproveedores_id>1)
						//		$this->db->query("update cmarcas_productos set proveedor_id=$row->pid where id=$row->cmarca_id");
						//echo "Factura id: $row->id Proveedor_antiguo: $row->cproveedores_id Nuevo: $row->pid <br/>";
						echo "Caso 2 pr_id: $row->id  Marca id: $row->cmarca_id - $row->marca Prov_m: $row->pid Prov_pr_f: $row->cproveedores_id<br/>";
				}
			}
			unset($row);
		}
	}
	function rep_pagos_entre_tiendas_pdf(){
		//Recibir criterios
		$espacio_id=$this->input->post('espacio');
		$mes_id=$this->input->post('mes');
		$anio=$this->input->post('anio');
		//Obtener un periodo de fechas del 1 dia del mes al ultimo
		$mes_siguiente=$mes_id+1;
		if($mes_siguiente>12){
			$mes_siguiente=$mes_id+1;
			$anio_siguiente=$anio+1;
		} else
			$anio_siguiente=$anio;
		$fecha2_pre=date("d m Y", strtotime("$anio_siguiente-$mes_siguiente-01")-(24*3600));
		$data['dia']=date("d", strtotime("$anio_siguiente-$mes_siguiente-01")-(24*3600));
		$data['fecha2']=$fecha2=$fecha2_pre;
		$data['mes']=$mes_id;
		$data['anio']=$anio;
		//Obtener el periodo
		// 		$data['periodo']=$this->deuda_tienda->get_pagos_entre_tiendas($espacio, $fecha2);
		// 		$data['espacios']=$this->espacio_fisico->get_espacios_tiendas_mtrx();
		// 		$data['tipo_pago']=$this->ctipo_deuda_tienda->ctipo_deuda_tiendas_mtrx();
		show_error("Reporte en Construcción");
	}
	function rep_devoluciones_defectos_pdf(){
		$data['title']="Reporte de Devoluciones por defecto de fabricación";
		//proveedor
		$data['finiquitadas']=false; $data['pendientes']=false;

		$proveedor_id=$this->input->post('cproveedores_id');
		if($proveedor_id>0){
			$p=$this->proveedor->get_proveedor($proveedor_id);
			$data['proveedor']=$p->razon_social;
		} else
			$data['proveedor']="TODOS";
		//Marca
		$marca_id=$this->input->post('cmarca_id');
		if($marca_id>0){
			$m=$this->marca_producto->get_marca($marca_id);
			$data['marca']=$m->tag;
		} else
			$data['marca']="TODAS";

		$fecha1u=$this->input->post("fecha1");
		$fecha2u=$this->input->post("fecha2");
		$tipo=$this->input->post("tipo");
		//Manejo de las Fechas
		if($fecha1u=='') {
			$hoy=date("Y-m-d");
			$fecha1=date("Y-m-d", strtotime($hoy));
		} else if($fecha2u=='') {
			$hoy=date("Y-m-d");
			$fecha2=date("Y-m-d", strtotime($hoy)+(24 * 60 * 60));
		} else {
			$fecha=explode(" ", $fecha1u);
			$fecha1=$fecha[2]."-".$fecha[1]."-".$fecha[0];
			$fecha=explode(" ", $fecha2u);
			$fecha2a=$fecha[2]."-".$fecha[1]."-".$fecha[0];
			$fecha2=date("Y-m-d", strtotime($fecha[2]."-".$fecha[1]."-".$fecha[0]) + (24 * 60 * 60));
			$data['fecha1']=$fecha1u;
			$data['fecha2']=$fecha2u;
		}
		if($tipo==3 or $tipo==2){
			//Consulta para traer las devoluciones que no han sido abonadas a pago
			$data['pendientes']=$this->salida->get_rep_devoluciones_defectuosas($proveedor_id, $fecha1, $fecha2, $marca_id, 0);
		}
		if($tipo==3 or $tipo==1){
			//Consulta para traer las devoluciones que ya fueron abonadas a un pago
			$data['finiquitadas']=$this->salida->get_rep_devoluciones_defectuosas($proveedor_id, $fecha1, $fecha2, $marca_id, 1);
		}
		$this->load->view('contabilidad/rep_devoluciones_defectos_pdf', $data);
	}
}
?>