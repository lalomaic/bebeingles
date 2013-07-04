<?php
class Tienda_c extends Controller {
	function Tienda_c() {
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
		$this->assetlibpro->add_js('jquery.jfield.js');
		$this->assetlibpro->add_js('jquery.date.js');
		$this->assetlibpro->add_js('jquery.form.js');
		$this->assetlibpro->add_js('fancybox/jquery.mousewheel-3.0.2.pack.js');
		$this->assetlibpro->add_js('fancybox/jquery.fancybox-1.3.1.js');
		$this->load->model("producto");
		$this->load->model("cliente");
		$this->load->model("almacen_validacion");
		$this->load->model("ccl_estatus_pedido");
		$this->load->model("cl_pedido");
		$this->load->model("cl_detalle_pedido");
		$this->load->model("tienda_validacion");
		$this->load->model("espacio_fisico");
		$this->load->model("lote");
		$this->load->model("nota_remision");
		// 	    $this->load->model("salida_remision");
		$this->load->model("salida");
		// 	    $this->load->model("corte_diario");
		$this->load->model("cl_factura");
		$this->load->model("almacen");
		$this->load->model("arqueo");
		$this->load->model("cuenta_bancaria");
		$this->load->model("control_deposito");
		$this->load->model("espacio_stock");
		$this->load->model("stock_detalle");

		$user_hash=$this->session->userdata('user_data');
		$row=$this->usuario->get_usuario_detalles($user_hash);
		$GLOBALS['usuarioid']=$row->id;
		$GLOBALS['empresa_id']=$row->empresas_id;
		$GLOBALS['ubicacion_tienda']=$row->espacio_fisico_id;
		$GLOBALS['ubicacion_id']=$row->espacio_fisico_id;
		$GLOBALS['ubicacion_nombre']=$this->espacio_fisico->get_espacios_f_tag($GLOBALS['ubicacion_tienda']);
		$GLOBALS['username']=$row->username;
		$GLOBALS['ruta']=$this->uri->segment(1);
		$GLOBALS['controller']=$this->uri->segment(2);
		$GLOBALS['funcion']=$this->uri->segment(3);
		$GLOBALS['subfuncion']=$this->uri->segment(4);
		$GLOBALS['modulos_totales']=$this->modulo->get_tmodulos();
		$GLOBALS['main_menu']=$this->menu->menus($GLOBALS['usuarioid'],"principal",0);
	}


	function formulario() {

		global $main_menu, $usuarioid, $username, $ruta, $controller, $funcion, $subfuncion, $modulos_totales, $empresa_id, $ubicacion_nombre, $ubicacion_id;
		$main_view=false;
		$data['username']=$username;
		$data['tienda']=$ubicacion_nombre;
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
		if (is_array($data['colect1']) and $usuarioid>0 and $data['permisos']!= false and $this->session->userdata('logged_in') == TRUE) {
			if($subfuncion=="alta_transferencia"){
				//Pedido de tipo de transferencia
				$main_view=true;
				$data['principal']=$ruta."/".$subfuncion;
				//Obtener Datos
				$data['validation']=$this->tienda_validacion->validacion_transferencia();
				$data['rows']=30;
				$data['renglon_adic']=0;
				$data['empresa']=$this->empresa->get_empresa($empresa_id);
				$data['productos']=$this->producto->get_cproductos_detalles();
				$data['estatus']=$this->ccl_estatus_pedido->get_ccl_estatus_pedido_all();
				$data['espacios_fisicos']=$this->espacio_fisico->get_espacios_f();
			} else if($subfuncion=="alta_traspaso_plantilla") {
				//TRaspaso via plantilla de stock
				if($this->uri->segment(5)==""){
					$data['plantillas']=$this->espacio_stock->get_espacio_stocks_select($GLOBALS['ubicacion_id']);
					if($data['plantillas']==false)
						show_error("La sucursal no cuenta con plantilla de stock registradas, comuniquese con el Supervisor de Sucursales");
					$main_view=true;
					$data['principal']=$ruta."/elegir_plantilla";
				} else if($this->uri->segment(5)=="alta_traspaso") {
					$main_view=true;
					$data['principal']=$ruta."/alta_traspaso_plantilla";
					//Obtener Datos
					$data['empresa']=$this->empresa->get_empresa($empresa_id);
					//Obtener los productos de la plantilla designada
					$stock_id=$this->uri->segment(6);
					//$stock_id=$this->espacio_stock->get_espacio_stock_by_espacio($GLOBALS['ubicacion_id']);
					$productos1=$this->stock_detalle->get_stock_detalle_by_stock_id_tienda($stock_id);
					if($productos1==false)
						show_error("La plantilla de stock no posee productos, comuniquese con el Supervisor de Sucursales");
					$mtx_producto=array(); $r=0;
					foreach($productos1->all as $linea){
						$mtx_producto[$r]['cproducto_id']=$linea->cproducto_id;
						$existencia=$this->almacen->existencias($linea->cproducto_id, "where espacios_fisicos_id='{$GLOBALS['ubicacion_id']}' ");
						$diff=$linea->cantidad - $existencia['existencias'];
						if($diff<0)
							$diff=0;
						$mtx_producto[$r]['cantidad']=$diff;
						$mtx_producto[$r]['cantidad_default']=$linea->cantidad;
						$mtx_producto[$r]['descripcion']=$linea->descripcion. " ".$linea->presentacion;
						$r+=1;
					}

					$data['productos']=$mtx_producto;
					$data['estatus']=$this->ccl_estatus_pedido->get_ccl_estatus_pedido_all();
					$data['espacios_fisicos']=$this->espacio_fisico->get_espacios_f();
				}
			}   else if($subfuncion=="list_transferencias") {
                                $data['paginacion']=true;
                                $data['title']="Listado de Traspasos recibidos en ";
                                $main_view=true;
                                //Cargar los datos para el formulario
                                $data['principal']=$ruta."/traspasos/listado_transferencias";
                                //Obtener Datos
                                $this->load->library('pagination');
                                $config['base_url'] = base_url()."index.php/".$ruta."/".$controller."/".$funcion."/traspasos/list_transferencias/";
                                $config['per_page'] = '50';
                                $page=$this->uri->segment(5);
                                //Identificar el numero de pagina en el paginador si existe
                                if($page>0)
                                        $offset=$page;
                                else if ($page=='')
                                        $offset=0;
                                else
                                        $offset=0;
                                $ubicacion = $GLOBALS['ubicacion_tienda'];
                                //Estatus traspaso = 2 recibido, en esta tienda = 1
                                $data['traspasos'] = $this->lote->get_entrada_traspaso_local($offset, $config['per_page'], $ubicacion, 2, 1);
                                $total_registros = $this->lote->get_entrada_traspaso_local_count($ubicacion, 2, 1); 
                                $data['total_registros']=$total_registros;
                                $config['total_rows'] = $total_registros;
                                $this->pagination->initialize($config);                                        

			} else if($subfuncion=="list_salida_traspasos") {
				$data['paginacion']=true;
                                $data['title']="Listado de Traspasos enviados de ";
                                $main_view=true;
                                //Cargar los datos para el formulario
                                $data['principal']=$ruta."/traspasos/listado_salidas";
                                //Obtener Datos
                                $this->load->library('pagination');
                                $config['base_url'] = base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_salida_traspasos/";
                                $config['per_page'] = '50';
                                $page=$this->uri->segment(5);
                                //Identificar el numero de pagina en el paginador si existe
                                if($page>0)
                                        $offset=$page;
                                else if ($page=='')
                                        $offset=0;
                                else
                                        $offset=0;
                                $espacio = $GLOBALS['ubicacion_tienda'];
                                //Estatus traspaso = 2 recibido, Envio = 2 desde esta tienda
                                $data['traspasos'] = $this->lote->get_entrada_traspaso_local($offset, $config['per_page'], $espacio, 2, 2);
                                $total_registros = $this->lote->get_entrada_traspaso_local_count($espacio, 2, 2); 
                                $data['total_registros']=$total_registros;
                                $config['total_rows'] = $total_registros;
                                $this->pagination->initialize($config);                                        

			} else if($subfuncion=="alta_entrada_local"){
				//Dar salida a un pedido de traspaso
				if($this->uri->segment(5)=="alta_traspaso"){
					//Definir la vista
					$main_view=true;
                                        $id=$this->uri->segment(6);
					$data['principal']=$ruta."/traspasos/".$subfuncion;
					/*Cargar Catalogos*/
					$data['traspaso']=$this->lote->get_traspaso_por_id($id);
					$data['traspasos_salidas']=$this->lote->get_traspaso_salidas($id);
					$data['empresa']=$this->empresa->get_empresa($empresa_id);
					
                                        if($data['traspasos_salidas']!=false){
						$data['rows']=count($data['traspasos_salidas']->all);
					} else
						$data['rows']=0;

				} else {
					$main_view=true;
					//Cargar los datos para el listado de pedidos de compra en proceso
					//Definir la vista
					$data['principal']=$ruta."/traspasos/listado_entradas";
					$data['title']="Entrada Local por Traspaso. Paso 1 Seleccione el Traspaso";
					// load pagination class
					$this->load->library('pagination');
					$config['base_url'] = base_url()."index.php/tienda/tienda_c/formulario/alta_entrada_local";
					$config['per_page'] = '20';
					$page=$this->uri->segment(5);
					//Identificar el numero de pagina en el paginador si existe
					if($page>0) {
						$offset=$page;
					} else {
						$offset=0;
					}
                                        $ubicacion = $GLOBALS['ubicacion_tienda'];
                                        //Estatus traspaso = 1 enviado, a esta tienda = 1
                                        $data['traspasos']=$this->lote->get_entrada_traspaso_local($offset, $config['per_page'], $ubicacion, 1, 1);
					$data['total_registros']=$this->lote->get_entrada_traspaso_local_count($ubicacion, 1, 1); 
					$config['total_rows']=$data['total_registros'];
					$this->pagination->initialize($config);
				}
			}//Final del Bloque Alta entrada local
			else if ($subfuncion=="alta_cl_factura_remision"){
				//Definir la vista
				$main_view=false;
				$pre=$this->uri->segment(5);
				if($pre==''){
					//Pedir el número de remision y validar que exista la remision
					echo "<html><script> remision=prompt(\"Ingrese el número de remisión\");";
					echo "window.location='".base_url()."index.php/".$GLOBALS['ruta']."/tienda_c/formulario/alta_cl_factura_remision/no_remision/'+remision;</script></html>";
				} else if ($pre=='no_remision') {
					$main_view=true;
					$no_remision=$this->uri->segment(6);
					if (strlen($no_remision) > 0 or is_integer($no_remision)==false) {
						//Verificar la remision.- que existan salidas con esa remision, .- y que no esten asociadas a una factura en el caso de que sean mas de 10 conceptos
						$get_remision=$this->nota_remision->get_nota_remision_factura($no_remision, $GLOBALS['ubicacion_tienda']);
						if ($get_remision!=false) {
							$data['principal']=$ruta."/".$subfuncion;
							//Si existe la remision, obtener las salidas de la remision
							$data['remision']=$get_remision;
							$data['salidas_remision']=$this->salida_remision->get_salida_remision_detalles($no_remision, $GLOBALS['ubicacion_tienda']);
							$data['empresa']=$this->empresa->get_empresa($GLOBALS['empresa_id']);
							$data['productos']=$this->producto->get_cproductos_detalles();
							$data['clientes']=$this->cliente->get_clientes();
							$data['estatus']=$this->ccl_estatus_pedido->get_ccl_estatus_pedido_all();
							$data['espacios_fisicos']=$this->espacio_fisico->get_espacios_f();
							if($data['salidas_remision']!=false){
								$data['renglon_adic']=count($data['salidas_remision']->all);
								$data['rows']=count($data['salidas_remision']->all);
							} else {
								$data['rows']=0;
								$data['renglon_adic']=0;
							}
						} else {
							//Regresar al menu
							echo "<html><script>alert(\"Se ha ingresado un valor no válido 1, intente de nuevo.\");". "window.location='".base_url()."index.php/inicio/acceso/".$GLOBALS['ruta']."/menu';</script></html>";
						}
					} else {
						//Regresar al menu
						echo "<html><script>alert(\"Se ha ingresado un valor no valido 2, intente de nuevo.\")". "window.location='".base_url()."index.php/inicio/acceso/".$GLOBALS['ruta']."/menu';</script></html>";
					}
					//Obtener el formulario con los datos generales necesarios y el detalle del mismo para generar la factura
				}
			} else if ($subfuncion=="alta_corte_caja"){
				//Definir la vista
				$pre=$this->uri->segment(5);
				if($pre=="folio_factura"){
					$corte=$this->uri->segment(6);
					echo "<html><script> folio=prompt(\"Ingrese el folio de la Factura\");";
					echo "window.location='".base_url()."index.php/".$GLOBALS['ruta']."/tienda_c/formulario/alta_corte_caja/alta_corte_caja/$corte/'+folio;</script></html>";
				} else if ($pre=='alta_corte_caja') {
					$main_view=true;
					$corte_id=$this->uri->segment(6);
					$folio=$this->uri->segment(7);
					if(strlen($folio)==0 or $folio=='0' or $folio=='null'){
						echo "<html><script> alert(\"No ingreso el folio de la Factura $folio\");";
						echo "window.location='".base_url()."index.php/".$GLOBALS['ruta']."/tienda_c/formulario/alta_corte_caja/';</script></html>";
					} else {
						//Obtener el monto total de la Factura de Corte de la suma de las remisiones de la fecha
						$fecha_corte=$this->corte_diario->get_corte_diario($corte_id);
						if($fecha_corte!=false){
							$fecha=$fecha_corte->fecha_corte;
							$remisiones_corte=$this->nota_remision->get_notas_remision_corte_count($fecha, $GLOBALS['ubicacion_tienda'], $fecha_corte->remision_inicial, $fecha_corte->remision_final);
							if($remisiones_corte!=false )
								$total_remisiones=$remisiones_corte;
							else {
								$total_remisiones=0;
								show_error('Error 1: No hay tickets dentro del periodo'.$id);

							}
							$remisiones=$this->nota_remision->get_notas_remision_corte($fecha, $GLOBALS['ubicacion_tienda'], $fecha_corte->remision_inicial, $fecha_corte->remision_final);
							if($remisiones==false)
								show_error("Ocurrio un Error al leer las notas de remision del corte");
							$monto_corte=$this->nota_remision->get_monto_remisiones_corte($fecha, $GLOBALS['ubicacion_tienda'],$fecha_corte->remision_inicial, $fecha_corte->remision_final);
							$iva_total=$this->nota_remision->get_impuesto_corte($fecha, $GLOBALS['ubicacion_tienda']);
							//Generar la Factura
							$fac=new Cl_factura();
							$fac->empresas_id=$GLOBALS['empresa_id'];
							$fac->cclientes_id=1;
							$fac->folio_factura=$folio;
							$fac->conceptos=1;
							//			$fac->monto_total=$monto_corte;
							$fac->fecha_captura=date("Y-m-d H:i:s");
							$fac->fecha=$fecha;
							$fac->estatus_general_id=1;
							$fac->cl_pedido_id=0;
							$fac->espacios_fisicos_id=$GLOBALS['ubicacion_id'];
							$fac->usuario_id=$GLOBALS['usuarioid'];
							if($fac->save()) {
								$monto_salidas=0;
								foreach($remisiones->all as $row1){
									//Obtener los id de salidas de la tabla salidas_remisiones
									if($row1->estatus_general_id==2){
										//Actualisar las salidas deshabilitadas
										$this->db->query("update salidas set estatus_general_id='2', cl_facturas_id=0 where id_ubicacion_local=$row1->id_ubicacion_local and espacios_fisicos_id={$GLOBALS['ubicacion_tienda']}");
									} else if($row1->estatus_general_id==1){
										//Actualisar con la factura sin afectar facturacion de otros clientes
										$sql2="update salidas set cl_facturas_id=$fac->id, corte_diario_id=$fecha_corte->id, numero_remision='$row1->numero_remision', cclientes_id=1 where id_ubicacion_local=$row1->id_ubicacion_local and espacios_fisicos_id={$GLOBALS['ubicacion_tienda']} and estatus_general_id=1 and ctipo_salida_id=1 and cclientes_id<=1	";
										$this->db->query($sql2);
										//echo $sql2."<br/>";
									}
								}

								$sql3="select sum(costo_total) as total, sum(tasa_impuesto*cantidad*costo_unitario/(tasa_impuesto+100)) as iva from salidas where cl_facturas_id=$fac->id and estatus_general_id=1 and numero_remision is not null";
								$t=$this->db->query($sql3);
								foreach($t->result() as $res){
									$total=$res->total;
									$iva=$res->iva;
								}
								$fac->monto_total=$total;
								$fac->iva_total=$iva;
								$fac->save();

								$this->db->query("update cortes_diarios set factura_id='$fac->id', fecha_captura='".date("Y-m-d H:i:s")."', conteo_remisiones='$total_remisiones' where id='$corte_id'");

								//Llamado a la función que genera la poliza de ingresos para la sucursales
								//				$this->poliza_ingreso($GLOBALS['ubicacion_tienda'], $fecha);
								echo "<html><script> alert(\"Se ha registrado exitosamente la Facturación del Corte\");";
								echo "window.location='".base_url()."index.php/".$GLOBALS['ruta']."/tienda_c/formulario/list_cl_facturas_tienda';</script></html>";
							} else	{
								show_error("".$fac->error->string);
							}
						} else {
							show_error("El corte no existe");//No existe el corte
						}
					}
				} else {
					$main_view=true;
					//Obtener el Listado de Cortes de Caja enviados por el pVenta
					$data['title']="Facturación del Corte de Caja";
					// load pagination class
					$data['principal']=$ruta."/list_cortes_pendientes";
					$this->load->library('pagination');
					$config['base_url'] = base_url()."index.php/".$ruta."/".$controller."/".$funcion."/alta_corte_caja/";
					$config['per_page'] = '15';
					$page=$this->uri->segment(5);
					//Identificar el numero de pagina en el paginador si existe
					if($page>0) {
						$offset=$page;
					} else if ($page==''){
						$offset=0;
					} else {
						$offset=0;
					}

					$u1=new Corte_diario();
					$u1->where("espacios_fisicos_id","".$GLOBALS['ubicacion_tienda']);
					$u1->where('factura_id', NULL);
					$u1->get();
					$total_registros=$u1->c_rows;
					$data['cortes']=$this->corte_diario->get_cortes_diarios_pendientes_tienda($offset, $config['per_page'], $GLOBALS['ubicacion_tienda']);
					$data['total_registros']=$total_registros;
					$config['total_rows'] = $total_registros;
					$this->pagination->initialize($config);
				}
			} else if($subfuncion=='list_cortes_diarios'){
				//Obtener el Listado de Cortes de Caja enviados por el pVenta
				$main_view=true;
				$data['title']="Facturación del Corte de Caja";
				// load pagination class
				$data['principal']=$ruta."/".$subfuncion;
				$this->load->library('pagination');
				$config['base_url'] = base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_cortes_diarios";
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

				$u1=new Corte_diario();
				$u1->where("espacios_fisicos_id","".$GLOBALS['ubicacion_tienda']);
				$u1->where('factura_id >', '0');
				$u1->get();
				$total_registros=$u1->c_rows;
				$data['cortes']=$this->corte_diario->get_cortes_diarios_tienda($offset, $config['per_page'], $GLOBALS['ubicacion_tienda']);
				$data['total_registros']=$total_registros;
				$config['total_rows'] = $total_registros;
				$this->pagination->initialize($config);
			}
			else if($subfuncion=='list_remisiones'){
				$main_view=true;
				//Obtener el Listado de Cortes de Caja enviados por el pVenta
				//Definir la vista
				$data['title']="Listado de Remisiones en la Tienda";
				// load pagination class
				$data['principal']=$ruta."/".$subfuncion;
				$this->load->library('pagination');
				$config['base_url'] = base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_remisiones";
				$config['per_page'] = '30';
				$page=$this->uri->segment(5);
				//Identificar el numero de pagina en el paginador si existe
				if($page>0) {
					$offset=$page;
				} else if ($page==''){
					$offset=0;
				} else {
					$offset=0;
				}
				$data['remisiones']=$this->nota_remision->get_notas_remision_list($offset, $config['per_page'], $GLOBALS['ubicacion_tienda']);
				$all=$this->db->query("select id from nota_remision where espacio_fisico_id='{$GLOBALS['ubicacion_tienda']}'");
				$data['total_registros']=$all->num_rows();
				$config['total_rows'] =  $data['total_registros'];
				$this->pagination->initialize($config);

			} else if($subfuncion=='list_cl_facturas_tienda'){
				if($this->uri->segment(5)=="cancelar_factura"){
					$id=$this->uri->segment(6);
					$main_view=false;
					if(substr(decbin($data['permisos']), 2, 1)==1){
						$u=new Cl_factura();
						$u->get_by_id($id);
						$u->estatus_general_id=2;
						if($u->save())
						{
							$this->db->query("update salidas set cl_facturas_id=0 where cl_facturas_id=$id");
							$this->db->query("update cortes_diarios set factura_id=null where factura_id=$id");
							redirect(base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion);
						} else
							show_error("".$u->error->string);
					}
				} else {
					$main_view=true;
					$data['principal']=$ruta."/".$subfuncion;
					$this->load->library('pagination');
					$config['base_url'] = base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_cl_facturas_tienda/";
					$config['per_page'] = '30';
					$page=$this->uri->segment(5);
					//Identificar el numero de pagina en el paginador si existe
					if($page>0)
						$offset=$page;
					else if ($page=='')
						$offset=0;
					else
						echo "Sin registros";
					$u1=$this->cl_factura->get_cl_facturas_tienda_count($GLOBALS['ubicacion_tienda']);
					$total_registros=$u1->total;
					$data['cl_facturas']=$this->cl_factura->get_cl_facturas_tienda($offset, $config['per_page'], $GLOBALS['ubicacion_tienda']);
					$data['total_registros']=$total_registros;
					$config['total_rows'] = $total_registros;
					$this->pagination->initialize($config);
				}
			}//End Facturas
			else if($subfuncion=="alta_existencia_fisica"){
				//Pedido de tipo de transferencia
				$main_view=true;
				$data['principal']=$ruta."/".$subfuncion;
				//Obtener Datos
				$data['empresa']=$this->empresa->get_empresa($empresa_id);
				$data['productos']=$this->producto->get_cproductos_detalles();
				$data['espacios_fisicos']=$this->espacio_fisico->get_espacio_f($GLOBALS['ubicacion_tienda']);
				$where=" where espacios_fisicos_id='{$GLOBALS['ubicacion_tienda']}'";
				$order_by ="";
				$data['inventario']=$this->almacen->inventario($where, $order_by, "");
				if(is_array($data['inventario'])){
					$data['rows']=count($data['inventario']);
				} else {
					$main_view=false;
					$this->load->view('error', "No hay movimiento de productos en dicha ubicación");
				}
			} else if($subfuncion=="list_existencia_fisica") {
				$main_view=true;
				//Cargar los datos para el formulario
				//Definir la vista
				$data['principal']=$ruta."/".$subfuncion;
				//Obtener Datos
				// load pagination class
				$this->load->library('pagination');
				$config['base_url'] = base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_existencia_fisica/";
				$config['per_page'] = '20';
				$page=$this->uri->segment(5);
				//Identificar el numero de pagina en el paginador si existe
				if($page>0) {
					$offset=$page;
				} else if ($page==''){
					$offset=0;
				} else {
					$offset=0;
				}

				$u1=new Arqueo();
				$u1->get();
				$total_registros=$u1->c_rows;
				$ubicacion_id=$GLOBALS['ubicacion_tienda'];
				$data['arqueos']=$this->arqueo->get_arqueos_tienda($offset, $config['per_page'], $ubicacion_id);
				$data['total_registros']=$total_registros;
				$config['total_rows'] = $total_registros;
				$this->pagination->initialize($config);
			} //Fin existencias fisicas
			else if($subfuncion=="alta_salida_tras_tienda"){
				//Dar salida a determinados productos
				$main_view=true;
				$data['principal']=$ruta."/alta_salida_traspaso_tienda";
				$data['empresa']=$this->empresa->get_empresa($empresa_id);
				$data['espacios_fisicos']=$this->espacio_fisico->get_espacios_tiendas();
				$data['rows']=300;
			} else if($subfuncion=='alta_control_deposito'){
				$main_view=true;
				$data['principal']=$ruta."/".$subfuncion;
				//Obtener Datos
				$data['validation']=$this->tienda_validacion->validacion_deposito();
				$data['bancos']=$this->cuenta_bancaria->get_cuentas_bancarias_empresa($empresa_id);

			} else if($subfuncion=="list_control_depositos") {
				if($this->uri->segment(5)=="editar_deposito"){
					$main_view=true;
					$id=$this->uri->segment(6);
					$data['principal']=$ruta."/editar_deposito";
					/*Cargar Catalogos*/
					$data['validation']=$this->tienda_validacion->validacion_deposito();
					$data['bancos']=$this->cuenta_bancaria->get_cuentas_bancarias_empresa($empresa_id);
					$data['deposito']=$this->control_deposito->get_control_deposito($id);
				} else if($this->uri->segment(5)=="borrar_deposito"){
					$id=$this->uri->segment(6);
					$main_view=false;
					if(substr(decbin($data['permisos']), 2, 1)==1){
						$this->db->query("update control_depositos set estatus_general_id=2 where id='$id'");
						redirect(base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion);
					}
					//Definir la vista
					redirect(base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion);
				} else  {
					$main_view=true;
					//Cargar los datos para el formulario
					//Definir la vista
					$data['principal']=$ruta."/".$subfuncion;
					//Obtener Datos
					// load pagination class
					$this->load->library('pagination');
					$config['base_url'] = base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_control_depositos/";
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
					$ubicacion_id=$GLOBALS['ubicacion_tienda'];
					$total_registros=$this->control_deposito->get_depositos_tienda_count($ubicacion_id);
					$data['depositos']=$this->control_deposito->get_depositos_tienda($offset, $config['per_page'], $ubicacion_id);
					$data['total_registros']=$total_registros;
					$config['total_rows'] = $total_registros;
					$this->pagination->initialize($config);
				}
			}
			if($main_view) {
				$this->load->view("ingreso", $data);
				unset($data);
			} else {
				//	    redirect(base_url()."index.php/inicio/logout");
			}
		}
	}

}

?>
