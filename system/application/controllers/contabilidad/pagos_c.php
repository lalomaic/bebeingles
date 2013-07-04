<?php
class Pagos_c extends Controller {

	function Pagos_c() {
		parent::Controller();
		if($this->session->userdata('logged_in') == FALSE)
			redirect(base_url()."index.php/inicio/logout");
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
		$this->assetlibpro->add_js('jquery.date.js');
		$this->assetlibpro->add_js('jquery.form.js');
		$this->assetlibpro->add_js('fancybox/jquery.mousewheel-3.0.2.pack.js');
		$this->assetlibpro->add_js('fancybox/jquery.fancybox-1.3.1.js');
		$this->load->model("forma_pago");
		$this->load->model("pr_factura");
		$this->load->model("cuenta_bancaria");
		$this->load->model("tipo_pago");
		$this->load->model("pago");
		$this->load->model("proveedor");
		$this->load->model("pago_validacion");
		$this->load->model("espacio_fisico");
		$this->load->model("almacen");
		$this->load->model("bancos");
		$this->load->model("estatus_general");
		$this->load->model("ctipo_deuda_tienda");
		$this->load->model("deuda_tienda");
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
		if(is_array($data['colect1']) and $usuarioid>0 and $data['permisos']!= false and $this->session->userdata('logged_in') == TRUE) {
			$main_view=true;
			//Inicio del Bloque Alta Forma Pago
			if($subfuncion=="alta_pr_forma_pago"){
				//Cargar los datos para el formulario
				$data['principal']=$ruta."/".$subfuncion;
				$data['validation']=$this->pago_validacion->validacion_forma_pago();
			} else if($subfuncion=="alta_bancos"){
                            $data['principal']=$ruta."/".$subfuncion;
                            $data['estatus']=$this->estatus_general->get_estatus();

                             }else if($subfuncion=="list_bancos"){
				$data['pag']=1;
	  			$data['principal']=$ruta."/".$subfuncion;
	  			$filtrado=$this->uri->segment(6);
	  			$id=$this->uri->segment(7);
				if($this->uri->segment(5)=="editar_bancos"){
	  		//Cargar los datos para el formulario
	  		$edit_usr=$this->uri->segment(5);
	  		$id=$this->uri->segment(6);
	  		//Definir la vista
	  		$data['principal']=$ruta."/editar_bancos";
	  		//Obtener Datos
	  		$data['marca']=$this->bancos->get_banco($id);
	  		$data['estatus']=$this->estatus_general->get_estatus();
	  		

	  	} else if($this->uri->segment(5)=="borrar_bancos"){
	  		$id=$this->uri->segment(6);
	  		$main_view=false;
	  		if(substr(decbin($data['permisos']), 2, 1)==1){
	  			$u=new bancos();
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
	  		$u1=$this->bancos->select("count(id) as total")->where("estatus_general_id",1)->get();
	  		$total_registros=$u1->total;
	  		$data['marca_productos']=$this->bancos->get_bancos_list($offset, $config['per_page']);
	  		$data['total_registros']=$total_registros;
	  		$config['total_rows'] = $total_registros;
	  		$this->pagination->initialize($config);
	  		$data['cta']=0;
	  	}
				}
 				else if($subfuncion=="list_pr_formas_pago"){
				if($this->uri->segment(5)=="editar_pr_forma_pago"){
					//Cargar los datos para el formulario
					$edit_usr=$this->uri->segment(5);
					$id=$this->uri->segment(6);
					//Definir la vista
					$data['principal']=$ruta."/editar_pr_forma_pago";
					//Obtener Datos
					$data['forma_pago']=$this->forma_pago->get_forma_pago($id);
					$data['validation']=$this->pago_validacion->validacion_forma_pago();
				} else if($this->uri->segment(5)=="borrar_forma_pago"){
					$iduser=$this->uri->segment(6);
					$main_view=false;
					redirect(base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfunction);
				} else  {
					//Cargar los datos para el listado
					$data['principal']=$ruta."/".$subfuncion;
					//Obtener Datos
					// load pagination class
					$this->load->library('pagination');
					$config['base_url'] = base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_pr_formas_pago";
					$config['per_page'] = '10';
					$page=$this->uri->segment(5);
					//Identificar el numero de pagina en el paginador si existe
					if($page>0)
						$offset=$page;
					else if ($page=='')
						$offset=0;
					else
						$offset=0;
					$u1=new Forma_pago();
					$u1->get();
					$total_registros=$u1->c_rows;
					$data['formas_pagos']=$this->forma_pago->get_formas_pagos_list($offset, $config['per_page']);
					$data['total_registros']=$total_registros;
					$config['total_rows'] = $total_registros;
					$this->pagination->initialize($config);
				}
			} //Final del Bloque Alta Forma de Pago

			//Inicio del Bloque Alta Tipo Pago
			else if($subfuncion=="alta_tipo_pago"){
				//Definir la vista
				$data['principal']=$ruta."/".$subfuncion;
				$data['validation']=$this->pago_validacion->validacion_tipo_pago();

			} else if($subfuncion=="list_tipos_pagos"){

				if($this->uri->segment(5)=="editar_tipo_pago"){
					//Cargar los datos para el formulario
					$edit_usr=$this->uri->segment(5);
					$id=$this->uri->segment(6);
					//Definir la vista
					$data['principal']=$ruta."/editar_tipo_pago";
					//Obtener Datos
					$data['tipo_pago']=$this->tipo_pago->get_tipo_pago($id);

				} else if($this->uri->segment(5)=="borrar_tipo_pago"){
					$iduser=$this->uri->segment(6);
					$main_view=false;
					$data['validation']=$this->pago_validacion->validacion_tipo_pago();
					redirect(base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfunction);
				} else  {
					//Definir la vista
					$data['principal']=$ruta."/".$subfuncion;
					//Obtener Datos
					// load pagination class
					$this->load->library('pagination');
					$config['base_url'] = base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_tipos_pagos";
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

					$u1=new Tipo_pago();
					$u1->get();
					$total_registros=$u1->c_rows;
					$data['tipos_pagos']=$this->tipo_pago->get_tipos_pagos_list($offset, $config['per_page']);
					$data['total_registros']=$total_registros;
					$config['total_rows'] = $total_registros;
					$this->pagination->initialize($config);
				}
			} //Final del Bloque Alta Tipo de Pago

			//Inicio del Bloque Alta Pago
			else if($subfuncion=="alta_pago"){
				//Definir la vista
				$data['principal']=$ruta."/".$subfuncion;
				//Obtener Datos
				$data['validation']=$this->pago_validacion->validacion_pago();
				$data['proveedores']=$this->proveedor->get_proveedores();
				$data['formas_pagos']=$this->forma_pago->get_formas_pagos();
			 $data['cuentas_bancarias'] = $this->cuenta_bancaria->get_cuentas_bancarias_banco();
				$data['tipos_pagos']=$this->tipo_pago->get_ctipos_pagos();
			} else if($subfuncion=="alta_multiples_pagos"){
				//Definir la vista
				$data['principal']=$ruta."/".$subfuncion;
				//Obtener Datos
				$data['rows']=300;
				$data['validation']=$this->pago_validacion->validacion_pago();
				$data['proveedores']=$this->proveedor->get_proveedores();
				$data['formas_pagos']=$this->forma_pago->get_formas_pagos();
				 $data['cuentas_bancarias'] = $this->cuenta_bancaria->get_cuentas_bancarias_banco();
				$data['tipos_pagos']=$this->tipo_pago->get_ctipos_pagos();
			} else if($subfuncion=="list_pagos"){
				$data['principal']=$ruta."/".$subfuncion;
				$data['pag']=1;
				$filtrado=$this->uri->segment(6);
				$id=$this->uri->segment(7);
				$data['fecha1']="";
				$data['fecha2']="";
					
				if($this->uri->segment(5)=="editar_pago"){
					//Cargar los datos para el formulario
					$edit_usr=$this->uri->segment(5);
					$id=$this->uri->segment(6);
					//Definir la vista
					$data['principal']=$ruta."/editar_pago";
					//Obtener Datos
					$data['validation']=$this->pago_validacion->validacion_pago();
					$data['pagos']=$this->pago->get_editar_pagos_pr_factura($id);
					$data['factura']=$this->pr_factura->get_pr_factura_pdf($id);
				} else if($this->uri->segment(5)=="borrar_pago"){
					$id=$this->uri->segment(6);
					$main_view=false;
					if(substr(decbin($data['permisos']), 2, 1)==1){
						$u=new Pago();
						$u->get_by_id($id);
						$factura=$u->pr_factura_id;
						$this->db->query("update pr_facturas set estatus_factura_id=2 where id=$factura");
						$u->delete();
						redirect(base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion."/editar_pago/$factura");
					} else {
						$main_view=true;
						//Definir la vista
						$data['principal']="error";
						$data['error_field']="No tiene permisos para cancelar el pago";
					}
				}  else if($filtrado=="proveedor" && $id>0){
					$data['paginacion']=false;
					$data['pagos']=$this->pago->get_pagos_proveedores_list($id);
					$data['cta']=$id;
					$data['total_registros']=count($data['pagos']->all);

				} else if($filtrado=="marcas" && $id>0){
					$data['paginacion']=false;
					$data['pagos']=$this->pago->get_pagos_marcas_list($id,1);
					$data['cta']=$id;
					if($data['pagos']!=false)
						$data['total_registros']=count($data['pagos']->all);
					else
						$data['total_registros']=0;
				} else if ($this->uri->segment(5)=="filtrado"){
					$data['principal']=$ruta."/".$subfuncion;
					//Filtrado
					$data['paginacion']=false;
					$data['fecha1']=$fecha1=$this->input->post('fecha_inicio');
					$data['fecha2']=$fecha2=$this->input->post('fecha_final');
					$data['proveedor_id']=$cproveedor_id=$this->input->post('cproveedor_id');
					$data['proveedor_tag']=$espacio_tag=$this->input->post('cproveedor_drop');
						
					$data['pagos']=$this->pago->get_pagos_filtrado_fecha_list($fecha1, $fecha2, $cproveedor_id);
					$data['cta']=0;
					if($data['pagos']==false)
						$data['total_registros']=0;
					else
						$data['total_registros']=count($data['pagos']->all);
				} else if ($filtrado=="factura_id" && $id>0){
					$data['paginacion']=false;
					$data['pagos']=$this->pago->get_pagos_by_factura_id($id);
					$data['cta']=$id;
					if($data['pagos']!=false)
						$data['total_registros']=count($data['pagos']->all);
					else
						$data['total_registros']=0;
				} else  {
					$data['paginacion']=true;
					$main_view=true;
					//Definir la vista
					$data['principal']=$ruta."/list_pagos";
					//Obtener Datos
					$this->load->library('pagination');
					$config['base_url'] = base_url()."index.php/".$ruta."/".$controller."/formulario/list_pagos/";
					$config['per_page'] = '50';
					$page=(int)$this->uri->segment(5);
					//Identificar el numero de pagina en el paginador si existe
					//echo $page;
					if($page>0)
						$offset=$page;
					else if ($page==0)
						$offset=0;
					else
						$offset=0;
					$data['total_registros']=$this->pago->get_pagos_list_count("", "");
					$data['pagos']=$this->pago->get_pagos_list($offset, $config['per_page'], "", "");
					$config['total_rows'] =$data['total_registros'];
					$this->pagination->initialize($config);
				}
			} //Final del Bloque Alta Pago
			else if($subfuncion=="list_pagos_multiples"){
				$data['principal']=$ruta."/".$subfuncion;
				$data['pag']=1;
				$filtrado=$this->uri->segment(6);
				$id=$this->uri->segment(7);
				$data['fecha1']="";
				$data['fecha2']="";
					
				if($this->uri->segment(5)=="borrar_pago_multiple"){
					$proveedor_id=$this->uri->segment(6);
					$fecha_pago=$this->uri->segment(7);
						
					$main_view=false;
					if(substr(decbin($data['permisos']), 2, 1)==1){
						//Obtener los pagos y cancelarlos en base al proveedor y la fecha
						$pagos=$this->pago->get_pagos_borrado($proveedor_id, $fecha_pago);
						if($pagos!=false){
							foreach($pagos->all as $row){
								//Actualizar el estatus de las pr_facturas del pago multiple regresandolas a Credito
								$this->db->query("update pr_facturas set  estatus_factura_id=2 where id=$row->pr_factura_id");

								//Actualizar en la tabla salidas el campo que corresponde a la devolucion_finiquitada en base al campo salidas_str
								if(strlen($row->salidas_str)>0){
									$this->db->query("update salidas set devolucion_finiquitada=0 where id in ($row->salidas_str)");
								}
								//Cancelar el pago
								$u=new Pago();
								$u->get_by_id($row->id);
								$u->delete(); unset($u); unset($row);
							}
						}
						redirect(base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion);
					} else {
						$main_view=true;
						//Definir la vista
						$data['principal']="error";
						$data['error_field']="No tiene permisos para cancelar el pago múltiple";
					}
				}  else if($filtrado=="proveedor" && $id>0){
					$data['paginacion']=false;
					$data['pagos']=$this->pago->get_pagos_multiples_proveedores_list($id);
					$data['cta']=$id;
					$data['total_registros']=count($data['pagos']->all);

				} else if($filtrado=="marcas" && $id>0){
					$data['paginacion']=false;
					$data['pagos']=$this->pago->get_pagos_multiples_marcas_list($id,1);
					$data['cta']=$id;
					if($data['pagos']!=false)
						$data['total_registros']=count($data['pagos']->all);
					else
						$data['total_registros']=0;
				} else if ($this->uri->segment(5)=="filtrado"){
					$data['principal']=$ruta."/".$subfuncion;
					//Filtrado
					$data['paginacion']=false;
					$data['fecha1']=$fecha1=$this->input->post('fecha_inicio');
					$data['fecha2']=$fecha2=$this->input->post('fecha_final');
					$data['proveedor_id']=$cproveedor_id=$this->input->post('cproveedor_id');
					$data['proveedor_tag']=$espacio_tag=$this->input->post('cproveedor_drop');
						
					$data['pagos']=$this->pago->get_pagos_multiples_filtrado_fecha_list($fecha1, $fecha2, $cproveedor_id);
					$data['cta']=0;
					if($data['pagos']==false)
						$data['total_registros']=0;
					else
						$data['total_registros']=count($data['pagos']->all);
				}  else  {
					$data['paginacion']=true;
					$main_view=true;
					//Definir la vista
					$data['principal']=$ruta."/list_pagos_multiples";
					//Obtener Datos
					$this->load->library('pagination');
					$config['base_url'] = base_url()."index.php/".$ruta."/".$controller."/formulario/list_pagos_multiples/";
					$config['per_page'] = '50';
					$page=(int)$this->uri->segment(5);
					//Identificar el numero de pagina en el paginador si existe
					//echo $page;
					if($page>0)
						$offset=$page;
					else if ($page==0)
						$offset=0;
					else
						$offset=0;
					$data['total_registros']=$this->pago->get_pagos_list_count("", "");
					$data['pagos']=$this->pago->get_pagos_multiples_list($offset, $config['per_page'], "", "");
					$config['total_rows'] =$data['total_registros'];
					$this->pagination->initialize($config);
				}
			} //Final del Bloque Alta Pago

			else if($subfuncion=="list_cuentas_xpagar"){
				//Cargar los datos para el listado
				$data['principal']=$ruta."/".$subfuncion;
				// load pagination class
				$this->load->library('pagination');
				$config['base_url'] = base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_cuentas_xpagar";
				$config['per_page'] = '20';
				$page=$this->uri->segment(5);
				//Identificar el numero de pagina en el paginador si existe
				if($page>0)
					$offset=$page;
				else if ($page=='')
					$offset=0;
				else
					$offset=0;
				$u1=$this->pr_factura->get_pr_facturas_xpagar_count();
				$total_registros=$u1->total;
				$data['pr_facturas']=$this->pr_factura->get_pr_facturas_xpagar($offset, $config['per_page']);
				$data['total_registros']=$total_registros;
				$config['total_rows'] = $total_registros;
				$this->pagination->initialize($config);
			} else if($subfuncion=="alta_pago_entre_tiendas"){
				//Definir la vista
				$data['principal']=$ruta."/".$subfuncion;
				$data['meses']=$this->almacen->get_meses_dropd();
				$data['anios']=$this->almacen->get_anios_dropd();
				if($this->uri->segment(5)!=''){
					$this->load->model("almacen");
					$this->load->model("ctipo_deuda_tienda");
					//Obtener las fechas
					$main_view=false;
					$mes_id=$this->input->post('mes');
					$anio=$this->input->post('anio');
					//Obtener un periodo de fechas del 1 dia del mes al ultimo
					$fecha1_pre="01 ".$mes_id." ".$anio;
					$mes_siguiente=$mes_id+1;
					if($mes_siguiente>12){
						$mes_siguiente=$mes_id+1;
						$anio_siguiente=$anio+1;
					} else
						$anio_siguiente=$anio;
					$fecha2_pre=date("d m Y", strtotime("$anio_siguiente-$mes_siguiente-01")-(24*3600));
					$data['dia']=date("d", strtotime("$anio_siguiente-$mes_siguiente-01")-(24*3600));
					$data['fecha1']=$fecha1=$fecha1_pre;
					$data['fecha2']=$fecha2=$fecha2_pre;
					$data['mes']=$mes_id;
					$data['anio']=$anio;
					//Obtener el periodo
					$data['periodo']=$this->almacen->get_pagos_entre_tiendas_periodo($fecha1, $fecha2);
					$data['espacios']=$this->espacio_fisico->get_espacios_tiendas_mtrx();
					$data['tipo_pago']=$this->ctipo_deuda_tienda->ctipo_deuda_tiendas_mtrx();
					//print_r($data['periodo']);

					$this->load->view("contabilidad/alta_pago_entre_tiendas_form", $data);
				}
			}	else if($subfuncion=="alta_deuda_tienda"){
				//Definir la vista
				$data['principal']=$ruta."/".$subfuncion;
				//Obtener Datos
				$data['tiendas']=$this->espacio_fisico->get_tiendas_dropd();
				$data['conceptos']=$this->ctipo_deuda_tienda-> ctipo_deuda_tiendas_mtrx();
				$data['rows']=20;
			}else if($subfuncion=="list_deuda_tiendas"){
				$data['principal']=$ruta."/".$subfuncion;
				$data['pag']=1;
				$filtrado=$this->uri->segment(6);
				$id=$this->uri->segment(7);
				$data['fecha1']="";
				$data['fecha2']="";
				if ($this->uri->segment(5)=="filtrado"){
					$data['principal']=$ruta."/".$subfuncion;
					//Filtrado
					$data['paginacion']=false;
					$data['fecha1']=$fecha1=$this->input->post('fecha_inicio');
					$data['fecha2']=$fecha2=$this->input->post('fecha_final');
					$data['espacio_debe_id']=$espacio_debe_id=$this->input->post('espacio_fisico_debe_id');
					$data['espacio_debe_tag']=$espacio_debe_tag=$this->input->post('espacio_debe_drop');
					$data['espacio_recibe_id']=$espacio_recibe_id=$this->input->post('espacio_fisico_recibe_id');
					$data['espacio_recibe_tag']=$espacio_recibe_tag=$this->input->post('espacio_recibe_drop');
					$data['concepto_id']=$concepto_id=$this->input->post('concepto_id');
					$data['conceptos']=$this->ctipo_deuda_tienda-> ctipo_deuda_tiendas_mtrx();

					$data['entradas']=$this->deuda_tienda->get_listado_filtrado($espacio_debe_id, $espacio_recibe_id, $fecha1, $fecha2,$concepto_id); //Sin validar
					$data['cta']=0;
					if($data['entradas']==false)
						$data['total_registros']=0;
					else
						$data['total_registros']=count($data['entradas']->all);
				}  else if($this->uri->segment(5)=='editar_deuda_tienda'){
					// 				echo 666; exit;
					$id=$this->uri->segment(6);
					$data['principal']=$ruta."/editar_deuda_tienda";
					//Obtener Datos
					$data['row']=$this->deuda_tienda->get_deuda_tienda($id);
					$data['tiendas']=$this->espacio_fisico->get_tiendas_dropd();
					$data['conceptos']=$this->ctipo_deuda_tienda-> ctipo_deuda_tiendas_mtrx();
				}else  {
					$data['paginacion']=true;
					$data['fecha1']=$fecha1=$this->input->post('fecha_inicio');
					$data['fecha2']=$fecha2=$this->input->post('fecha_final');
					$data['espacio_debe_id']=$espacio_id=$this->input->post('espacio_fisico_debe_id');
					$data['espacio_debe_tag']=$espacio_tag=$this->input->post('espacio_debe_drop');
					$data['espacio_recibe_id']=$espacio_id=$this->input->post('espacio_fisico_recibe_id');
					$data['espacio_recibe_tag']=$espacio_tag=$this->input->post('espacio_recibe_drop');
					$data['concepto_id']=$espacio_id=$this->input->post('concepto_id');
					$data['concepto_tag']=$espacio_tag=$this->input->post('concepto_drop');
					$data['conceptos']=$this->ctipo_deuda_tienda-> ctipo_deuda_tiendas_mtrx();
					$main_view=true;
					//Obtener Datos
					$this->load->library('pagination');
					$config['base_url'] = base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_deuda_tiendas/";
					$config['per_page'] = '60';
					$page=$this->uri->segment(5);
					//Identificar el numero de pagina en el paginador si existe
					if($page>0) {
						$offset=$page;
					} else if ($page==''){
						$offset=0;
					} else {
						$offset=0;
					}
					$data['total_registros']=$this->deuda_tienda->get_listado_count(); //Sin validad
					$data['entradas']=$this->deuda_tienda->get_listado($offset, $config['per_page']); //sin validar
					$config['total_rows'] = $data['total_registros'];
					$this->pagination->initialize($config);
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
}
?>
