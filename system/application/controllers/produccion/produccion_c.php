<?php
class Produccion_c extends Controller {
	//var $usuarioid, $username, $ruta, $controller, $funcion, $subfuncion, $modulos_totales;

	function Produccion_c()
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
		$this->assetlibpro->add_js('jquery.jfield.js');
		$this->assetlibpro->add_js('jquery.select-autocomplete.js');
		$this->assetlibpro->add_js('jquery.numberformatter.js');
		$this->assetlibpro->add_js('jquery.AddIncSearch.js');
		$this->assetlibpro->add_js('jquery.validator.js');
		$this->assetlibpro->add_js('jquery.date.js');
		$this->assetlibpro->add_js('jquery.form.js');
		$this->assetlibpro->add_js('jquery.jfield.js');
		$this->assetlibpro->add_js('fancybox/jquery.mousewheel-3.0.2.pack.js');
		$this->assetlibpro->add_js('fancybox/jquery.fancybox-1.3.1.js');
		$this->load->model("producto");
		$this->load->model("produccion_validacion");
		$this->load->model("receta");
		$this->load->model("receta_detalle");
		$this->load->model("espacio_fisico");
		$this->load->model("produccion");
		$this->load->model("produccion_detalle");
		$user_hash=$this->session->userdata('user_data');
		$row=$this->usuario->get_usuario_detalles($user_hash);
		$GLOBALS['usuarioid']=$row->id;
		$GLOBALS['empresa_id']=$row->empresas_id;
		$GLOBALS['ubicacion_tienda']=$row->espacio_fisico_id;
		$GLOBALS['ubicacion_id']=$row->espacio_fisico_id;
		$GLOBALS['ubicacion_nombre']=$this->espacio_fisico->get_espacios_f_tag($GLOBALS['ubicacion_tienda']);
		$GLOBALS['username']=$row->username;
		$GLOBALS['puesto']=$row->puesto_id;
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
		$data['title']=$this->accion->get_title("$subfuncion") ." en ".$GLOBALS['ubicacion_nombre'];
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
			if($subfuncion=="alta_receta"){
				//Definir la vista
				$data['principal']=$ruta."/".$subfuncion;
				//Obtener Datos
				$data['validation']=$this->produccion_validacion->validacion_receta();
				//$data['rows_pred']="";
				$data['rows_pred']=0;
				$data['rows']=15;
				$data['productos']=$this->producto->get_cproductos_detalles();
				 
			}  else if($subfuncion=="list_recetas"){

				if($this->uri->segment(5)=="editar_receta"){
					//Cargar los datos para el formulario
					$id=$this->uri->segment(6);
					//Definir la vista
					$data['principal']=$ruta."/editar_receta";
					$data['title']="EDICI�N DE RECETA";
					//Obtener Datos
					$data['receta']=$this->receta->get_receta($id);
					$data['productos']=$this->producto->get_cproductos_detalles();
					$data['validation']=$this->produccion_validacion->validacion_receta();
					$sql="select * from receta_detalles where receta_id=$id";# 11.Jun.2010 @aku
					$result=$this->db->query($sql)->result();
					$data['receta_detalles']=$result;
					$data['rows_pred']=0;
					//$data['rows']=15;
					if($data['receta_detalles']!=false){
						$data['rows']=count($data['receta_detalles'])+10;
					} else {
						$data['rows']=15;
						$data['renglon_adic']=0;
					}
					 

				} else if($this->uri->segment(5)=="borrar_receta"){
					$id=$this->uri->segment(6);
					$main_view=false;
					if(substr(decbin($data['permisos']), 2, 1)==1){
						$u=new Receta();
						$u->get_by_id($id);
						$u->estatus_general_id=2;
						$u->save();
						redirect(base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion);
					} else {
						$main_view=true;
						//Definir la vista
						$data['principal']="error";
						$data['error_field']="No tiene permisos para deshabilitar la receta";
					}

				} else if($this->uri->segment(5)=="genera_receta"){
					$data['title']="GENERACION DE RECETA";
					$id=$this->uri->segment(6);
					$main_view=false;
					$pre=$this->uri->segment(7);
					if($pre==''){
						//Pedir la cantidad a elaborar
						echo "<html><script> cantidad=prompt(\"Ingrese la cantidad a Elaborar\");";
						echo "window.location='".base_url()."index.php/".$GLOBALS['ruta']."/produccion_c/formulario/list_recetas/genera_receta/".$id."/numero/'+cantidad;</script></html>";
					} else if ($pre=='numero') {
						$main_view=true;
						$cantidad=$this->uri->segment(8);
						if($cantidad!='' and $cantidad>0){
							//$data['receta_detalles']=$this->receta_detalle->get_receta_detalles_pdf($id);
							$sql="select r.id, rd.receta_id, rd.cantidad, r.nombre, r.descripcion, r.modo_preparacion, r.dias_consumo, pr.descripcion as genera, p.descripcion as producto from receta_detalles as rd left join recetas as r on r.id=rd.receta_id left join cproductos as pr on pr.id=r.cproductos_id left join cproductos as p on p.id=rd.cproducto_id where r.id=$id order by rd.id";# 11.Jun.2010 @aku
							$result=$this->db->query($sql)->result();
							$data['receta_detalles']=$result;
							$data['cantidad']=$cantidad;
							$data['imprime']=false;
							$this->load->view("$ruta/rep_genera_receta_pdf", $data);
							//echo count($data['receta_detalles']);exit;
						}
						else{
							echo "<html> <script>alert(\"Se ha ingresado una cantidad invalida, intentelo nuevamente.\"); window.location='".base_url()."index.php/inicio/acceso/produccion/menu';</script></html>";
						}
	  		}
	  		else{
	  			echo "<html> <script>alert(\"Error en la generacion de la receta, intentelo nuevamente.\"); window.location='".base_url()."index.php/inicio/acceso/produccion/menu';</script></html>";
	  		}
				}

				else if($this->uri->segment(5)=="imprime_pdf"){
					$data['title']="IMPRESION DE RECETA";
					$id=$this->uri->segment(6);
					$main_view=true;
					$sql="select r.id, rd.receta_id, rd.cantidad, r.nombre, r.descripcion, r.modo_preparacion, r.dias_consumo, pr.descripcion as genera, p.descripcion as producto from receta_detalles as rd left join recetas as r on r.id=rd.receta_id left join cproductos as pr on pr.id=r.cproductos_id left join cproductos as p on p.id=rd.cproducto_id where r.id=$id order by rd.id";# 11.Jun.2010 @aku
					$result=$this->db->query($sql)->result();
					$data['receta_detalles']=$result;
					$data['cantidad']=1;
					$data['imprime']=true;
					$this->load->view("$ruta/rep_genera_receta_pdf", $data);
					//$this->load->view("$ruta/rep_receta_pdf", $data);

				}

				else  {
	    $main_view=true;
	    //Definir la vista
	    $data['principal']=$ruta."/".$subfuncion;
	    //Obtener Datos
	    // load pagination class
	    $this->load->library('pagination');
	    $config['base_url'] = base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_recetas/";
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
	    $data['total_registros']=$this->receta->get_recetas_list_count();
	    $data['recetas']=$this->receta->get_recetas_list($offset, $config['per_page']);
	    $config['total_rows'] =$data['total_registros'];
	    $this->pagination->initialize($config);
				}
			}
			//Inicio del Bloque Alta Produccion
			else if($subfuncion=="alta_produccion"){
				//Cargar los datos para el formulario
				//Definir la vista
				$data['principal']=$ruta."/".$subfuncion;
				$data['validation']=$this->produccion_validacion->validacion_produccion();
		  $data['recetas']=$this->receta->get_recetas();
		  if($this->uri->segment(5)=="detalles_produccion"){
		  	$main_view=false;
		  	$data['receta_id']=$this->input->post('receta_id');
		  	$data['cantidad_producida']=$this->input->post('cantidad_producida');
		  	$sql="select rd.id, rd.cproducto_id, rd.cantidad, p.descripcion from receta_detalles as rd left join cproductos as p on rd.cproducto_id=p.id where rd.receta_id=$data[receta_id]";
		  	$result=$this->db->query($sql)->result();
		  	foreach($result as $row){
		  		$productos[$row->cproducto_id]=$row->descripcion;
		  	}
		  	$data['receta_detalles']=$result;
		  	$data['productos']=$productos;
		  	$this->load->view("produccion/frame_detalles_produccion", $data);
		  }

			} else if($subfuncion=="list_produccion"){
				if($this->uri->segment(5)=="editar_produccion"){
					//Cargar los datos para el formulario
					$edit_usr=$this->uri->segment(5);
					$id=$this->uri->segment(6);
					//Definir la vista
					$data['principal']=$ruta."/editar_produccion";
					//Obtener Datos
					$data['tipo_pago']=$this->tipo_pago->get_tipo_pago($id);
				} else if($this->uri->segment(5)=="borrar_produccion"){
					$id=$this->uri->segment(6);
					$main_view=false;
					if(substr(decbin($data['permisos']), 2, 1)==1){
						$u=new Produccion();
						$u->get_by_id($id);
						$u->estatus_general_id=2;
						if($u->save()){
							//CAncelar entrada y salidas
							$e=new Entrada();
							$e->get_by_id($u->entrada_id);
							$e->estatus_general_id=2;
							if($e->save()){
								$produccion_detalles=$this->produccion_detalle->get_produccion_detalles_by_parent($u->id);
								if($produccion_detalles!=false){
									foreach($produccion_detalles->all as $row){
										$this->db->query("update salidas set estatus_general_id='2' where id='$row->salida_id'");
									}
								}
							}
							redirect(base_url()."index.php/".$ruta."/".$controller."/".$funcion."/".$subfuncion);
						}
					} else {
						$main_view=true;
						//Definir la vista
						$data['principal']="error";
						$data['error_field']="No tiene permisos para deshabilitar la Transformaci�n por producci�n";
					}
				} else  {
					//Definir la vista
					$data['principal']=$ruta."/".$subfuncion;
					//Obtener Datos
					// load pagination class
					$this->load->library('pagination');
					$config['base_url'] = base_url()."index.php/".$ruta."/".$controller."/".$funcion."/list_produccion/";
					$config['per_page'] = '20';
					$page=$this->uri->segment(5);
					//Identificar el numero de pagina en el paginador si existe
					if($page>0)
						$offset=$page;
					else if ($page=='')
						$offset=0;
					else
						$offset=0;
						
					$u1=$this->produccion->get_produccion_list($offset, $config['per_page'], $GLOBALS['ubicacion_id']);
					if($u1==false)
						show_error("No hay registros de transformaci�n por producci�n");
					$total_registros=count($u1->all);
					$data['produccion']=$u1;
					$data['total_registros']=$total_registros;
					$config['total_rows'] = $total_registros;
					$this->pagination->initialize($config);
				}
			} //Final del Bloque Alta Produccion
			 
			else if($subfuncion=="alta_producto_transformado"){
				$data['title']="ALTA TRANSFORMACI�N DE PRODUCTO";
				//Definir la vista
				$data['principal']=$ruta."/".$subfuncion;
				//Obtener Datos
				$data['validation']=$this->produccion_validacion->validacion_producto_transformado();
				$data['productos']=$this->producto->get_cproductos_detalles();
			}
			 
	  if($main_view){
	  	//Llamar a la vista
	  	$this->load->view("ingreso", $data);
	  } else {
	  	//redirect(base_url()."index.php/inicio/logout");
	  }
		}

	}

}//**End of Controller Class*/
?>
