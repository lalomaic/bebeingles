<?php
class Contabilidad_c extends Controller {
	//var $usuarioid, $username, $ruta, $controller, $funcion, $subfuncion, $modulos_totales;

	function Contabilidad_c()
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
		$this->assetlibpro->add_js('jquery.date.js');
		$this->assetlibpro->add_js('jquery.form.js');
		$this->assetlibpro->add_js('fancybox/jquery.mousewheel-3.0.2.pack.js');
		$this->assetlibpro->add_js('fancybox/jquery.fancybox-1.3.1.js');
		$this->load->model("proveedor");
		$this->load->model("entrada");
		$this->load->model("cuenta_bancaria");
		$this->load->model("compras_validacion");
		$this->load->model("pr_pedido");
		$this->load->model("cpr_estatus_pedido");
		$this->load->model("tipo_cuenta_bancaria");
		$this->load->model("cliente");
		$this->load->model("pr_detalle_pedido");
		$this->load->model("producto");
		$this->load->model("cpr_pago");
		$this->load->model("cuenta_comision");
		$this->load->model("bancos");
		$this->load->model("cpr_forma_pago");
		$this->load->model("espacio_fisico");
		$this->load->model("control_deposito");
		$this->load->model("tienda_validacion");
		$this->load->model("ctipo_gasto");
                 $this->load->model("Cbancarias_validacion");
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

	function formulario(){
		//Funcion para manejar formularios y listados,
		global $main_menu, $usuarioid, $username, $ruta, $controller, $funcion, $subfuncion, $modulos_totales, $empresa_id, $ubicacion_id;

		$main_view=false;
		$data['username']=$username;
		$data['usuarioid']=$usuarioid;
		$data['modulos_totales']=$modulos_totales;
		$data['colect1']=$main_menu;
		$data['title']=$this->accion->get_title("$subfuncion");
		;
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
			if($subfuncion=="alta_cuenta_bancaria"){
				//Cargar los datos para el formulario
				//Definir el numero de frames
				$data['frames']=0;
                                 $data['pag']=1;
				//Definir la vista
				$data['principal']=$ruta."/".$subfuncion;
				//Obtener Datos
				$data['tipos_cuentas_bancarias']=$this->tipo_cuenta_bancaria->get_ctipos_cuentas();                              $data['validation']=$this->Cbancarias_validacion->validacion_alta_cbancarias();
    				  $data['bank_filtro']=$this->bancos->get_bancos_filtro();

			}else if ($subfuncion == "alta_comision_bancaria") {
                 $data['pag'] = 1;
                $data['principal'] = $ruta . "/" . $subfuncion;
           
                if ($this->uri->segment(5) == "borrar_cuenta") {
                    $id = $this->uri->segment(6);
                    $main_view = false;
                    //Definir la vista
                    //Borrar Datos del Usuario
                    $u = new Cuenta_comision();
                    $u->get_by_id($id);
                    $this->db->trans_start();
                    $this->db->query("update comisiones_bancarias set estatus_general_id='2' where id='$id'");
                    $this->db->trans_complete();
                    redirect(base_url() . "index.php/" . $ruta . "/" . $controller . "/" . $funcion . "/" . $subfuncion);
                }
                $data['principal'] = $ruta . "/" . $subfuncion;
               $this->load->library('pagination');
                    $config['base_url'] = base_url() . "index.php/" . $ruta . "/" . $controller . "/" . $funcion . "/".$subfuncion;
                    $config['per_page'] = '20';
                    $page = $this->uri->segment(5);
                    //Identificar el numero de pagina en el paginador si existe
                    if ($page > 0)
                        $offset = $page;
                    else if ($page == '')
                        $offset = 0;
                    else
                        echo "Sin registros";

                    $u1 = new Cuenta_comision();
                    $u1->where('estatus_general_id',1)->get();
                    $total_registros = $u1->c_rows;
                    $data['comisiones_list'] = $this->cuenta_comision->get_cuentas_list($offset, $config['per_page']);
                    $data['validation'] = $this->Cbancarias_validacion->validacion_comision_bancarias();
                    $data['bank_filtro'] = $this->bancos->get_bancos_filtro();
                    $data['total_registros'] = $total_registros;
                    $this->pagination->initialize($config); 
                
                
            }  else if($subfuncion=="list_cuentas_bancarias"){
                                $data['pag']=1;
                               $data['principal']=$ruta."/".$subfuncion;
                                $filtrado=$this->uri->segment(6);
                                $id=$this->uri->segment(7);
				if($this->uri->segment(5)=="editar_cuenta_bancaria"){
					//Cargar los datos para el formulario
					$edit_usr=$this->uri->segment(5);
					$id=$this->uri->segment(6);
					//Definir el numero de frames
					$data['frames']=0;
					//Definir la vista
					$data['principal']=$ruta."/editar_cuenta_bancaria";
					//Obtener Datos
					$data['cuenta_bancaria']=$this->cuenta_bancaria->get_cuenta_bancaria($id);
					$data['tipos_cuentas_bancarias']=$this->tipo_cuenta_bancaria->get_ctipos_cuentas();
					 $data['bank_filtro']=$this->bancos->get_bancos_filtro();
				//	$data['proveedores']=$this->proveedor->get_proveedores_id();
					$data['clientes']=$this->cliente->get_clientes();
					$data['empresas']=$this->empresa->get_empresas();

				} else if($this->uri->segment(5)=="borrar_cuenta"){
					$id=$this->uri->segment(6);
					$main_view=false;
					//Definir la vista
					//Borrar Datos del Usuario
					$u=new Cuenta_bancaria();
					$u->get_by_id($id);
				 $this->db->trans_start();
                            $this->db->query("update ccuentas_bancarias set estatus_general_id='2' where id='$id'");
                            $this->db->trans_complete();
					 redirect(base_url() . "index.php/" . $ruta . "/" . $controller . "/" . $funcion . "/" . $subfuncion);

				} else if($filtrado=="buscar"){
                    $cuentas_bancarias = new Cuenta_bancaria();
                    $where=" where cb.estatus_general_id='1' ";
                    if(isset($_POST['banco'])){
                         $banco=strtoupper($_POST['banco']);
                        if($banco!="")
                        $where=$where." and cb.banco like '%".$banco."%'";
                        
                    }
                    if(isset($_POST['cproveedor_id'])){
                        $provedor=strtoupper($_POST['cproveedor_id']);
                        if($provedor>0)
                        $where=$where." and cb.cproveedor_id=".$provedor;
                    }
                    if(isset($_POST['num_cuenta'])){
                        $num_cuenta=strtoupper($_POST['num_cuenta']);
                        if($num_cuenta!="")
                        $where=$where." and cb.numero_cuenta like '%".$num_cuenta."%'";
                    }
                    if(isset($_POST['clave'])){
                        $clave=strtoupper($_POST['clave']);
                        if($clave!="")
                        $where=$where." and cb.clabe like '%".$clave."%'";
                    }
                    if(isset($_POST['empresa'])){
                          $empresa=$_POST['empresa'];
                        if($empresa!="")
                        $where=$where." and cb.empresa_id =".$empresa_id;
                    }
                    $sql="select cb.*, tc.tag as tipo_cuenta, p.razon_social as proveedor, c.razon_social as cliente, e.razon_social as empresa from ccuentas_bancarias as cb left join ctipos_cuentas as tc on tc.id=cb.ctipo_cuenta_id left join cproveedores as p on p.id=cproveedor_id left join cclientes as c on c.id=cb.ccliente_id left join empresas as e on e.id=cb.empresa_id"
                    .$where." order by id";
                    $data['cuentas_bancarias']=$cuentas_bancarias->query($sql);
                    $data['total_registros']=count($data['cuentas_bancarias']->all);
                    $this->load->library('pagination');
					$config['base_url'] = base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_cuentas_bancarias/1/buscar";

	  	} 
                                
                                else  {
                                    
					$data['frames']=0;
					//Definir la vista
					$data['principal']=$ruta."/".$subfuncion;
					//Obtener Datos
					// load pagination class
					$this->load->library('pagination');
					$config['base_url'] = base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_cuentas_bancarias";
					$config['per_page'] = '20';
					$page=$this->uri->segment(5);
					//Identificar el numero de pagina en el paginador si existe
					if($page>0)
						$offset=$page;
					else if ($page=='')
						$offset=0;
					else
						echo "Sin registros";

					$u1=new Cuenta_bancaria();
					$u1->get();
                                        $total_registros=$u1->c_rows;
					$data['cuentas_bancarias']=$this->cuenta_bancaria->get_cuentas_bancarias_list($offset, $config['per_page']);
					$data['total_registros']=$total_registros;
					$config['total_rows'] = $total_registros;
					$this->pagination->initialize($config);

				}
			} //Final del Bloque Alta Cuenta Bancaria

			//Inicio del Bloque Alta Tipo Cuenta Bancaria
			else if($subfuncion=="alta_tipo_cuenta_bancaria"){
				$data['frames']=0;
				$data['principal']=$ruta."/".$subfuncion;
			} else if($subfuncion=="list_tipos_cuentas_bancarias"){

				if($this->uri->segment(5)=="editar_tipo_cuenta_bancaria"){
					//Cargar los datos para el formulario
					$edit_usr=$this->uri->segment(5);
					$id=$this->uri->segment(6);
					//Definir el numero de frames
					$data['frames']=0;
					//Definir la vista
					$data['principal']=$ruta."/editar_tipo_cuenta_bancaria";
					//Obtener Datos
					$data['tipo_cuenta_bancaria']=$this->tipo_cuenta_bancaria->get_tipo_cuenta_bancaria($id);

				} else if($this->uri->segment(5)=="borrar_usuario"){
					$iduser=$this->uri->segment(6);
					$main_view=false;
					//Definir la vista
					//Borrar Datos del Usuario
					$u=new Usuario();
					$u->get_by_id($iduser);
					$u->delete();

					$ua= new Usuario_accion();
					$ua->where('usuario_id', $iduser);
					$ua->delete_all();
					redirect(base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfunction);

				} else  {

					//Cargar los datos para el listado
					//Definir el numero de frames
					$data['frames']=0;
					//Definir la vista
					$data['principal']=$ruta."/".$subfuncion;
					//Obtener Datos
					// load pagination class
					$this->load->library('pagination');
					$config['base_url'] = base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_tipos_cuentas_bancarias";
					$config['per_page'] = '10';
					$page=$this->uri->segment(5);
					//Identificar el numero de pagina en el paginador si existe
					if($page>0)
						$offset=$page;
					else if ($page=='')
						$offset=0;
					else
						echo "Sin registros";
					$u1=new Tipo_cuenta_bancaria();
					$u1->get();
					$total_registros=$u1->c_rows;
					$data['tipos_cuentas_bancarias']=$this->tipo_cuenta_bancaria->get_tipos_cuentas_bancarias_list($offset, $config['per_page']);
					$data['total_registros']=$total_registros;
					$config['total_rows'] = $total_registros;
					$this->pagination->initialize($config);

				}
			} //Final del Bloque Alta Tipo Cuenta Bancaria
			else if($subfuncion=="list_general_compras"){
				$data['principal']=$ruta."/".$subfuncion;
				$data['pag']=1;
				$filtrado=$this->uri->segment(6);
				$id=$this->uri->segment(7);
				$data['fecha1']="";
				$data['fecha2']="";
				if($this->uri->segment(5)=="validar_contablemente"){
					$main_view=false;
					$id=$this->uri->segment(6);
					$llave=$this->uri->segment(7);
					//Identificar la llave del usuario
					$usuario_id=$this->usuario->get_usuario_by_key($llave);
					//Validar que exista el usuario
					if($usuario_id!=false){
						$permisos1=$this->usuario_accion->get_permiso($accion_id, $usuario_id, $puestoid, $grupoid);
						if(substr(decbin($permisos1), 2, 1)==1){
							$u=new Pr_factura();
							$u->get_by_id($id);
							$u->validacion_contable=1;
							  $u->usuario_validador_id = $usuario_id;
							$u->save();
							 $this->db->query("update entradas set estatus_general_id='1' where pr_facturas_id='$id'");
							redirect(base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion);
						} else {
							$main_view=true;
							//Definir la vista
							$data['principal']="error";
							$data['error_field']="No tiene permisos para autorizar el Pedido de Compra";
						}
					} else {
						$main_view=true;
						//Definir la vista
						$data['principal']="error";
						$data['error_field']="La llave no esta asociada a un usuario";
					}
				} else if($filtrado=="sucursal" && $id>0){
					$main_view=true;
					//Filtrado por cuenta_id
					$data['paginacion']=false;
					$data['entradas']=$this->entrada->get_entradas_contabilidad_sucursal_list($id, 0); //Sin validar
					$data['cta']=$id;
					$data['total_registros']=count($data['entradas']->all);
				} else if($filtrado=="proveedor" && $id>0){
					$data['paginacion']=false;
					$data['entradas']=$this->entrada->get_entradas_contabilidad_proveedor_list($id,0); //Sin validar
					$data['cta']=$id;
					$data['total_registros']=count($data['entradas']->all);

				} else if($filtrado=="marcas" && $id>0){
					$data['paginacion']=false;
					$data['entradas']=$this->entrada->get_entradas_contabilidad_marca_list($id,0);
					$data['cta']=$id;
					if($data['entradas']!=false)
						$data['total_registros']=count($data['entradas']->all);
					else
						$data['total_registros']=0;
				} else if ($this->uri->segment(5)=="filtrado"){
					$data['principal']=$ruta."/".$subfuncion;
					//Filtrado
					$data['paginacion']=false;
					$data['fecha1']=$fecha1=$this->input->post('fecha_inicio');
					$data['fecha2']=$fecha2=$this->input->post('fecha_final');
					$data['espacio_id']=$espacio_id=$this->input->post('espacio_fisico_id');
					$data['espacio_tag']=$espacio_tag=$this->input->post('espacio_drop');

					$data['entradas']=$this->entrada->get_entradas_contabilidad_fecha_list($espacio_id, $fecha1, $fecha2,0); //Sin validar
					$data['cta']=0;
					if($data['entradas']==false)
						$data['total_registros']=0;
					else
						$data['total_registros']=count($data['entradas']->all);
				} else if ($filtrado=="factura_id" && $id>0){
					$data['paginacion']=false;
					$data['entradas']=$this->entrada->get_entradas_contabilidad_pr_factura_list($id,0);
					$data['cta']=$id;
					if($data['entradas']!=false)
						$data['total_registros']=count($data['entradas']->all);
					else
						$data['total_registros']=0;
				} else  {
					$data['paginacion']=true;
					$main_view=true;
					//Obtener Datos
					$this->load->library('pagination');
					$config['base_url'] = base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_general_compras/";
					$config['per_page'] = '40';
					$page=$this->uri->segment(5);
					//Identificar el numero de pagina en el paginador si existe
					if($page>0) {
						$offset=$page;
					} else if ($page==''){
						$offset=0;
					} else {
						$offset=0;
					}
				$data['total_registros']=$this->entrada->get_entradas_contabilidad_list_count(0); //Sin validad
					$data['entradas']=$this->entrada->get_entradas_contabilidad_list($offset, $config['per_page'], 0);
					//$data['entradas']=$this->entrada->get_entradas_contabilidad_sin_list($offset, $config['per_page'], 0); //sin validar
					$config['total_rows'] = $data['total_registros'];
					$this->pagination->initialize($config);
				}

			} else if($subfuncion=="list_general_compras_validadas"){
				$data['principal']=$ruta."/".$subfuncion;
				$this->assetlibpro->add_js('md5.js');
				$data['pag']=1;
				$filtrado=$this->uri->segment(6);
				$id=$this->uri->segment(7);
				$data['fecha1']="";
				$data['fecha2']="";
				if($filtrado=="sucursal" && $id>1){
					$main_view=true;
					//Filtrado por cuenta_id
					$data['paginacion']=false;
					$data['entradas'] = $this->entrada->get_entradas_sucursal_list($id);
					//$data['entradas']=$this->entrada->get_entradas_contabilidad_sucursal_list($id, 1); //validar
					$data['cta']=$id;
					$data['total_registros']=count($data['entradas']->all);
				} else if($filtrado=="proveedor" && $id>0){
					$data['paginacion']=false;
					$data['entradas'] = $this->entrada->get_entradas_proveedor_list($id);					
					//$data['entradas']=$this->entrada->get_entradas_contabilidad_proveedor_list($id,1); //validar
					$data['cta']=$id;
					$data['total_registros']=count($data['entradas']->all);

				} else if($filtrado=="marcas" && $id>0){
					$data['paginacion']=false;
					$data['entradas']=$this->entrada->get_entradas_contabilidad_proveedor_list($id,1);
					$data['cta']=$id;
					if($data['entradas']!=false)
						$data['total_registros']=count($data['entradas']->all);
					else
						$data['total_registros']=0;
				} else if ($this->uri->segment(5)=="filtrado"){
					$data['principal']=$ruta."/".$subfuncion;
					//Filtrado
					$data['paginacion']=false;
					$data['fecha1']=$fecha1=$this->input->post('fecha_inicio');
					$data['fecha2']=$fecha2=$this->input->post('fecha_final');
					$data['espacio_id']=$espacio_id=$this->input->post('espacio_fisico_id');
					$data['espacio_tag']=$espacio_tag=$this->input->post('espacio_drop');

					$data['entradas']=$this->entrada->get_entradas_contabilidad_fecha_list($espacio_id, $fecha1, $fecha2,1); //validar
					$data['cta']=0;
					if($data['entradas']==false)
						$data['total_registros']=0;
					else
						$data['total_registros']=count($data['entradas']->all);
				} else if ($filtrado=="factura_id" && $id>0){
					$data['paginacion']=false;
					//$data['entradas']=$this->entrada->get_entradas_contabilidad_pr_factura_list($id,1);
                    $data['entradas'] = $this->entrada->get_entrada_pr_factura_id($id, 1);

					$data['cta']=$id;
					if($data['entradas']!=false)
						$data['total_registros']=count($data['entradas']->all);
					else
						$data['total_registros']=0;
				} else  if($this->uri->segment(5)=="borrar_compra"){
					$main_view=false;
					$id=$this->uri->segment(6);
					$llave=$this->uri->segment(7);
					//Identificar la llave del usuario
					$usuario_id=$this->usuario->get_usuario_by_key($llave);
					//Validar que exista el usuario
					if($usuario_id!=false){
						$permisos1=$this->usuario_accion->get_permiso($accion_id, $usuario_id, $puestoid, $grupoid);
						if(substr(decbin($permisos1), 2, 1)==1){
							$u=new Pr_factura();
							$u->get_by_id($id);
							$u->estatus_general_id=2;
							$u->usuario_id=$GLOBALS['usuarioid'];
							if($u->save()){
								$this->db->query("update entradas set estatus_general_id=2 where pr_facturas_id='$u->id'");
							}
							redirect(base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion);
						} else {
							$main_view=true;
							//Definir la vista
							$data['principal']="error";
							$data['error_field']="No tiene permisos para borrar la Factura";
						}
					} else {
						$main_view=true;
						//Definir la vista
						$data['principal']="error";
						$data['error_field']="La llave no esta asociada a un usuario";
					}
				} else  {
					$data['paginacion']=true;
					$main_view=true;
					//Obtener Datos
					$this->load->library('pagination');
					$config['base_url'] = base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_general_compras_validadas/";
					$config['per_page'] = '40';
					$page=$this->uri->segment(5);
					//Identificar el numero de pagina en el paginador si existe
					if($page>0) {
						$offset=$page;
					} else if ($page==''){
						$offset=0;
					} else {
						$offset=0;
					}
					 //$data['total_registros'] = $this->entrada->get_count_validas(1,$GLOBALS['ubicacion_id']);  //Validadas
					$data['total_registros']=$this->entrada->get_entradas_contabilidad_list_count(1); //Sin validad
					$data['entradas']=$this->entrada->get_entradas_contabilidad_list($offset, $config['per_page'], 1); //sin validar
					//$data['entradas'] = $this->entrada->get_entradas_validadas($offset, $config['per_page'], 1,$GLOBALS['ubicacion_id']); //Validadas
					$config['total_rows'] = $data['total_registros'];
					$this->pagination->initialize($config);
				}
			} else if($subfuncion=="list_bonificaciones_generales"){
				if($this->uri->segment(5)=="borrar_entrada"){
					$id=$this->uri->segment(6);
					$main_view=false;
					if(substr(decbin($data['permisos']), 2, 1)==1){
						$u=new Entrada();
						$u->get_by_id($id);
						$u->estatus_general_id=2;
						redirect(base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion);
					} else {
						$main_view=true;
						//Definir la vista
						$data['principal']="error";
						$data['error_field']="No tiene permisos para deshabilitar la Entrada";
					}
				} else  {
					$main_view=true;
					//Definir la vista
					$data['principal']=$ruta."/".$subfuncion;
					//Obtener Datos
					// load pagination class
					$this->load->library('pagination');
					$config['base_url'] = base_url()."index.php/".$ruta."/".$controller."/".$funcion."/$subfuncion/";
					$config['per_page'] = '40';
					$page=$this->uri->segment(5);
					//Identificar el numero de pagina en el paginador si existe
					if($page>0)
						$offset=$page;
					else if ($page=='')
						$offset=0;
					else
						$offset=0;

					$data['total_registros']=$this->entrada->get_entradas_boni_gral_list_count($GLOBALS['ubicacion_id']);
					$data['entradas']=$this->entrada->get_entradas_boni_gral_list($offset, $config['per_page'], $GLOBALS['ubicacion_id']);
					$config['total_rows'] =$data['total_registros'];
					$this->pagination->initialize($config);
				}
			}
			else if($subfuncion=="list_control_depositos_general") {
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
					$config['base_url'] = base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_control_depositos_general/";
					$config['per_page'] = '50';
					$page=$this->uri->segment(5);
					//Identificar el numero de pagina en el paginador si existe
					if($page>0)
						$offset=$page;
					else if ($page=='')
						$offset=0;
					else
						$offset=0;

					$ubicacion_id=$GLOBALS['ubicacion_tienda'];
					$total_registros=$this->control_deposito->get_depositos_general_count();
					$data['depositos']=$this->control_deposito->get_depositos_general($offset, $config['per_page']);
					$data['total_registros']=$total_registros;
					$config['total_rows'] = $total_registros;
					$this->pagination->initialize($config);
				}
			}
			else if ($subfuncion=="alta_gasto_tienda"){
		$this->load->model("cgastos");
                $data['frames'] = 0;
                $data['lineas'] = 10;
                $data['principal'] = $ruta . "/" . $subfuncion;
                $data['catalogo_gastos'] = $this->cgastos->get_cgastos_dropd();
                $data['tipos_gastos'] = $this->ctipo_gasto->get_ctipo_gasto_dropd();
                $data['espacios'] = $this->espacio_fisico->get_espacios_dropd();
                $data['cuentas_bancarias'] = $this->cuenta_bancaria->get_cuentas_bancarias_banco();
                

			} else if($subfuncion=="list_gastos_tiendas"){
				$this->load->model("cgastos");
				$this->load->model("gasto_detalle");
				$data['catalogo_gastos']=$this->cgastos->get_cgastos_dropd();
				$data['tipos_gastos']=$this->ctipo_gasto->get_ctipo_gasto_dropd();
                                $data['cuentas_bancarias']=$this->cuenta_bancaria->get_cuentas_bancarias();
				if($this->uri->segment(5)=="editar_gasto_detalle"){
					//Cargar los datos para el formulario
					$id=$this->uri->segment(6);
					//Definir el numero de frames
					$data['frames']=0;
					//Definir la vista
					$data['principal']=$ruta."/editar_gasto_detalle";
					//Obtener Datos
					$data['tipos_gastos']=$this->ctipo_gasto->get_ctipo_gasto_dropd();
					$data['espacios']=$this->espacio_fisico->get_espacios_tiendas();
					$data['row']=$this->gasto_detalle->get_by_id($id);

				} else if ($this->uri->segment(5)=="filtrado"){
					$data['principal']=$ruta."/".$subfuncion;
					//Filtrado
					$data['paginacion']=false;
					$data['fecha1']=$fecha1=$this->input->post('fecha_inicio');
					$data['fecha2']=$fecha2=$this->input->post('fecha_final');
					$data['espacio_id']=$espacio_id=$this->input->post('espacio_fisico_id');
					$data['concepto_id']=$concepto_id=$this->input->post('concepto_id');
					$data['tipo_gasto_id']=$tipo_gasto_id=$this->input->post('tipo_gasto_id');
					$data['espacio_tag']=$espacio_tag=$this->input->post('espacio_drop');

					$data['gastos_detalles']=$this->gasto_detalle->get_gastos_detalles_filtrado($espacio_id, $fecha1, $fecha2, $concepto_id, $tipo_gasto_id);
					$data['cta']=0;
					if($data['gastos_detalles']==false)
						$data['total_registros']=0;
					else
						$data['total_registros']=count($data['gastos_detalles']->all);
				} else if($this->uri->segment(5)=="borrar_gasto"){
					$id=$this->uri->segment(6);
					$main_view=false;
					if(substr(decbin($data['permisos']), 2, 1)==1){
						$u=new Gasto_detalle();
						$u->get_by_id($id);
						$u->estatus_general_id=2;
						$u->save();
						redirect(base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion);
					} else {
						$main_view=true;
						//Definir la vista
						$data['principal']="error";
						$data['error_field']="No tiene permisos para deshabilitar el Gasto";
					}
				} else  {
					$data['concepto_id']=0;
					$data['tipo_gasto_id']=0;
					$data['fecha1']="";
					$data['fecha2']="";
					$data['espacio_id']=0;
					$data['espacio_tag']="";
					$data['paginacion']=true;
					$data['cta']=0;
					//Definir el numero de frames
					$data['frames']=0;
					//Definir la vista
					$data['principal']=$ruta."/".$subfuncion;
					//Obtener Datos
					$this->load->library('pagination');
					$config['base_url'] = base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_gastos_tiendas";
					$config['per_page'] = '200';
					$page=$this->uri->segment(5);
					//Identificar el numero de pagina en el paginador si existe
					if($page>0)
						$offset=$page;
					else if ($page=='')
						$offset=0;
					else
						$offset=0;
					$total_registros=$this->gasto_detalle->get_gastos_detalles_count();
					$data['gastos_detalles']=$this->gasto_detalle->get_gastos_detalles($offset, $config['per_page']);
					$data['total_registros']=$total_registros;
					$config['total_rows'] = $total_registros;
					$this->pagination->initialize($config);

				}
			}
			else if($subfuncion=="alta_gasto"){
				$this->load->model("cgastos");
				$data['frames']=0;
				$data['principal']=$ruta."/".$subfuncion;

			} else if($subfuncion=="list_gastos"){
				$this->load->model("cgastos");

				if($this->uri->segment(5)=="editar_gasto"){
					//Cargar los datos para el formulario
					$id=$this->uri->segment(6);
					//Definir el numero de frames
					$data['frames']=0;
					//Definir la vista
					$data['principal']=$ruta."/editar_gasto";
					//Obtener Datos
					$data['gasto']=$this->cgastos->get_by_id($id);

				} else if($this->uri->segment(5)=="borrar_gasto"){
					$id=$this->uri->segment(6);
					$main_view=false;
					if(substr(decbin($data['permisos']), 2, 1)==1){
						$u=new Cgastos();
						$u->get_by_id($id);
						$u->estatus_general_id=2;
						$u->save();
						redirect(base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion);
					} else {
						$main_view=true;
						//Definir la vista
						$data['principal']="error";
						$data['error_field']="No tiene permisos para deshabilitar el Gasto";
					}
				} else  {
					$data['paginacion']=true;
					$data['cta']=0;
					//Definir el numero de frames
					$data['frames']=0;
					//Definir la vista
					$data['principal']=$ruta."/".$subfuncion;
					//Obtener Datos
					$this->load->library('pagination');
					$config['base_url'] = base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_gastos";
					$config['per_page'] = '50';
					$page=$this->uri->segment(5);
					//Identificar el numero de pagina en el paginador si existe
					if($page>0)
						$offset=$page;
					else if ($page=='')
						$offset=0;
					else
						$offset=0;
					$total_registros=$this->cgastos->get_cgastos_list_count();
					$data['gastos_detalles']=$this->cgastos->get_cgastos_list($offset, $config['per_page']);
					$data['total_registros']=$total_registros;
					$config['total_rows'] = $total_registros;
					$this->pagination->initialize($config);

				}
			}


			else if ($subfuncion=="alta_egresos_tienda"){
				$this->load->model("otros_egresos");
				$data['frames']=0;
				$data['principal']=$ruta."/".$subfuncion;
				$data['catalogo_egresos']=$this->otros_egresos->get_otros_egresos_dropd();
				$data['espacios']=$this->espacio_fisico->get_espacios_dropd();

			} else if($subfuncion=="list_egresos_tienda"){
				$this->load->model("otros_egresos");
				$this->load->model("egresos_detalles");

				if($this->uri->segment(5)=="editar_egreso_tienda"){
					//Cargar los datos para el formulario
					$id=$this->uri->segment(6);
					//Definir el numero de frames
					$data['frames']=0;
					//Definir la vista
					$data['principal']=$ruta."/editar_egreso_tienda";
					//Obtener Datos
					$data['catalogo_gastos']=$this->otros_egresos->get_otros_egresos_dropd();
					$data['espacios']=$this->espacio_fisico->get_espacios_tiendas();
					$data['row']=$this->egresos_detalles->get_by_id($id);

				} else if ($this->uri->segment(5)=="filtrado"){
					$data['principal']=$ruta."/".$subfuncion;
					//Filtrado
					$data['paginacion']=false;
					$fecha1=$this->input->post('fecha_inicio');
					$fecha2=$this->input->post('fecha_final');
					$espacio_id=$this->input->post('espacio_fisico_id');
					$data['gastos_detalles']=$this->egresos_detalles->get_egresos_detalles_filtrado($espacio_id, $fecha1, $fecha2);
					$data['cta']=0;
					if($data['gastos_detalles']==false)
						$data['total_registros']=0;
					else
						$data['total_registros']=count($data['gastos_detalles']->all);
				} else if($this->uri->segment(5)=="borrar_egreso"){
					$id=$this->uri->segment(6);
					$main_view=false;
					if(substr(decbin($data['permisos']), 2, 1)==1){
						$u=new Egresos_detalles();
						$u->get_by_id($id);
						$u->estatus_general_id=2;
						$u->save();
						redirect(base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion);
					} else {
						$main_view=true;
						//Definir la vista
						$data['principal']="error";
						$data['error_field']="No tiene permisos para deshabilitar el Egreso";
					}
				} else  {
					$data['paginacion']=true;
					$data['cta']=0;
					//Definir el numero de frames
					$data['frames']=0;
					//Definir la vista
					$data['principal']=$ruta."/".$subfuncion;
					//Obtener Datos
					$this->load->library('pagination');
					$config['base_url'] = base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_egresos_tienda";
					$config['per_page'] = '200';
					$page=$this->uri->segment(5);
					//Identificar el numero de pagina en el paginador si existe
					if($page>0)
						$offset=$page;
					else if ($page=='')
						$offset=0;
					else
						$offset=0;
					$total_registros=$this->egresos_detalles->get_egresos_detalles_count();
					$data['gastos_detalles']=$this->egresos_detalles->get_egresos_detalles($offset, $config['per_page']);
					$data['total_registros']=$total_registros;
					$config['total_rows'] = $total_registros;
					$this->pagination->initialize($config);

				}
			}
			else if($subfuncion=="alta_otros_egresos"){
				$this->load->model("otros_egresos");
				$data['frames']=0;
				$data['principal']=$ruta."/".$subfuncion;

			} else if($subfuncion=="list_otros_egresos"){
				$this->load->model("otros_egresos");

				if($this->uri->segment(5)=="editar_otro_egreso"){
					//Cargar los datos para el formulario
					$id=$this->uri->segment(6);
					//Definir el numero de frames
					$data['frames']=0;
					//Definir la vista
					$data['principal']=$ruta."/editar_otro_egreso";
					//Obtener Datos
					$data['egreso']=$this->otros_egresos->get_by_id($id);

				} else if($this->uri->segment(5)=="borrar_otro_egreso"){
					$id=$this->uri->segment(6);
					$main_view=false;
					if(substr(decbin($data['permisos']), 2, 1)==1){
						$u=new Otros_egresos();
						$u->get_by_id($id);
						$u->estatus_general_id=2;
						$u->save();
						redirect(base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion);
					} else {
						$main_view=true;
						//Definir la vista
						$data['principal']="error";
						$data['error_field']="No tiene permisos para deshabilitar el Egreso";
					}
				} else  {
					$data['paginacion']=true;
					$data['cta']=0;
					//Definir el numero de frames
					$data['frames']=0;
					//Definir la vista
					$data['principal']=$ruta."/".$subfuncion;
					//Obtener Datos
					$this->load->library('pagination');
					$config['base_url'] = base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_otros_egresos";
					$config['per_page'] = '50';
					$page=$this->uri->segment(5);
					//Identificar el numero de pagina en el paginador si existe
					if($page>0)
						$offset=$page;
					else if ($page=='')
						$offset=0;
					else
						$offset=0;
					$total_registros=$this->otros_egresos->get_otros_egresos_list_count();
					$data['egresos_detalles']=$this->otros_egresos->get_otros_egresos_list($offset, $config['per_page']);
					$data['total_registros']=$total_registros;
					$config['total_rows'] = $total_registros;
					$this->pagination->initialize($config);

				}
			}



			if($main_view){
		  $this->load->view("ingreso", $data);
		  unset($data);
			} else {
		  redirect(base_url()."index.php/inicio/logout");
			}
		}

	}

}//**End of Controller Class*/
?>
