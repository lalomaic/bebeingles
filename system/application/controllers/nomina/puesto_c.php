<?php
class Puesto_c extends Controller
	{
	//var $usuarioid, $username, $ruta, $controller, $funcion, $subfuncion, $modulos_totales;
	function Puesto_c()
		{
		parent::Controller();
		if($this->session->userdata('logged_in') == FALSE)
			{
			redirect(base_url()."index.php/inicio/logout");
			}
		$this->assetlibpro->add_css('default.css');
		$this->assetlibpro->add_css('menu_style.css');
		$this->assetlibpro->add_css('date_input.css');
		$this->assetlibpro->add_css('jquery.validator.css');
		$this->assetlibpro->add_js('jquery.js');
		$this->assetlibpro->add_js('jquery.validator.js');
		$this->assetlibpro->add_js('jquery.date.js');
		$this->assetlibpro->add_js('jquery.form.js');
		$this->assetlibpro->add_js('fancybox/jquery.fancybox-1.3.1.js');
		$this->load->model("espacio_fisico");
		$this->load->model("nomina_validacion");
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
		}//##################################

	function formulario()
		{
		//Funcion para manejar formularios y listados,
		global $main_menu, $usuarioid, $username, $ruta, $controller, $funcion, $subfuncion, $modulos_totales;
		$main_view=false;
		$data['username']=$username;
		$data['usuarioid']=$usuarioid;
		$data['modulos_totales']=$modulos_totales;
		$data['colect1']=$main_menu;
		$data['title']=$this->accion->get_title($subfuncion);
		$accion_id=$this->accion->get_id($subfuncion);
		$row=$this->usuario->get_usuario($usuarioid);
		$grupoid=$row->grupo_id;
		$puestoid=$row->puesto_id;
		$data['ruta']=$ruta;
		$data['controller']=$controller;
		$data['funcion']=$funcion;
		$data['subfuncion']=$subfuncion;
		$data['permisos']=$this->usuario_accion->get_permiso($accion_id, $usuarioid, $puestoid, $grupoid);
		//Validacion del arreglo del menu, usuarioid y permisos especificos
		if(is_array($data['colect1']) and $usuarioid>0 and $data['permisos']!= false and $this->session->userdata('logged_in') == TRUE)
			{
			$main_view=true;
			if($subfuncion=="alta_puesto")
				{
				//Cargar los datos para el formulario
				//Definir la vista
				$data['principal']=$ruta."/".$subfuncion;
				$data['modulo']=$ruta;
				//Obtener prestaciones y deducciones
				$datos=new Prestacion();
				$data['prestaciones']=$datos->get()->all;
				$datos=new Deduccion();
				$data['deducciones']=$datos->get()->all;
				$data['validation']=$this->nomina_validacion->validacion_puesto();
				}
			elseif($subfuncion=="list_puestos")
				{
				if($this->uri->segment(5)=="editar_puesto")
					{
					//Cargar los datos para el formulario
					$rid=(int)$this->uri->segment(6);
					if($rid <= 0) show_error('No se ha seleccionado un registro valido');
					//Definir el numero de frames
					$data['frames']=0;
					//Definir la vista
					$data['principal']=$ruta."/editar_puesto";
					$datos=new Prestacion();
					$data['prestaciones']=$datos->get()->all;
					$datos=new Deduccion();
					$data['deducciones']=$datos->get()->all;
					//Obtener Datos del puesto
					$reg=new Puesto();
					$reg->get_by_id($rid);
					if(!$reg->exists()) show_error('El registro especificado no existe');
					$data['reg']=$reg;
					$data['title']='Editar puesto';
					$data['validation']=$this->nomina_validacion->validacion_puesto();
					}
				else
					{
					//Cargar los datos para el formulario
					//Definir el numero de frames
					$data['frames']=1;
					//Definir la vista
					$data['principal']=$ruta."/".$subfuncion;
					//Obtener Datos
					$this->load->library('pagination');
					$config['base_url'] = base_url()."index.php/".$ruta."/".$controller."/".$funcion."/$subfuncion/";
					$config['per_page'] = '30';
					$page=(int)$this->uri->segment(5);
					//Identificar el numero de pagina en el paginador si existe
					if($page>0)
						$offset=$page;
					else if ($page=='')
						$offset=0;
					else
						$offset=0;
					$total_registros=(int)$this->db->count_all('puestos');
					$sql="SELECT * from puestos where estatus_general_id = 1 order by tag LIMIT $offset,".$config['per_page'];
					//die($sql);
					$data['puestos']=$this->db->query($sql)->result();
					$data['total_registros']=$total_registros;
					$config['total_rows'] =$total_registros;
					$this->pagination->initialize($config);
					}
				}
			if($main_view)
				{
				//Llamar a la vista
				$this->load->view("ingreso", $data);
				}
			else
				{
				redirect(base_url()."index.php/inicio/logout");
				}
			}
		}//##########################################

	function alta_puesto()
		{ // BEGIN method alta_puesto
		global $ruta;
		//echo "<pre>";var_dump($_POST);echo "</pre>";die();
		$reg=new Puesto();
		if(isset($_POST['id']) && (int)$_POST['id'] > 0)
			$reg->get_by_id((int)$_POST['id']);
		$dest="/$ruta/puesto_c/formulario/list_puestos";
		$reg->tag=$this->input->post('tag');
		// obtener los datos de las prestaciones
		$prestaciones=array();
		if(isset($_POST['prestaciones']))
			{
			foreach($_POST['prestaciones'] as $p=>$val)
				{
				$pid=(int)substr($p,1);
				$cantidad=sprintf("%.2f",(float)$_POST['pc'.$pid]);
				$prestaciones[]="$pid:$cantidad";
				}
			}
		$reg->prestaciones=implode(',',$prestaciones);
		// obtener los datos de las deducciones
		$deducciones=array();
		if(isset($_POST['deducciones']))
			{
			foreach($_POST['deducciones'] as $d=>$val)
				{
				$did=(int)substr($d,1);
				$cantidad=sprintf("%.2f",(float)$_POST['dc'.$did]);
				$deducciones[]="$did:$cantidad";
				}
			}
		$reg->deducciones=implode(',',$deducciones);
		if($reg->save())
			{
			echo '<script type="text/javascript">'."\n";
			echo 'alert("El registro se ha guardado.");'."\n";
			echo 'window.location.href="'.site_url($dest).'";'."\n";
			echo '</script>';
			}
		else
			{
			echo '<script type="text/javascript">'."\n";
			echo 'alert("El registro no se pudo guardar, intente de nuevo");'."\n";
			echo 'window.location.href="'.site_url("/inicio/acceso/$ruta/menu").'";'."\n";
			echo '</script>';
			}
		} // END method alta_puesto #########################################################

	}
?>
