<?php

class Inicio extends Controller {
	var $main_menu;
	function Inicio() {
		parent::Controller();
		$this->assetlibpro->add_css('default.css');
		$this->assetlibpro->add_css('menu_style.css');
		$this->assetlibpro->add_js('jquery.js');
		$this->assetlibpro->add_js('fancybox/jquery.mousewheel-3.0.2.pack.js');
		$this->assetlibpro->add_js('fancybox/jquery.fancybox-1.3.1.js');
		$this->load->model("promocion");
		$this->load->model("espacio_fisico");
	}

	function index($msg='') {
		$data['msg'] = $msg;
		$data['title'] = 'Ingreso';
		$data['username'] = array('id' => 'username', 'name' => 'username', 'size' => '30', 'id' => 'username');
		$data['password'] = array('id' => 'password', 'name' => 'password', 'size' => '30');
		$this->load->view('login', $data);
	}

	function login() {
		$log = $this->usuario->autenticar($this->input->post('username'), md5($this->input->post('password')));
		if ($log != false) {
			foreach ($log->all as $row) {
				$user_data = md5($row->id . "&" . $row->email);
			}
			$data = array(
					'user_data' => $user_data,
					'logged_in' => TRUE
			);
			$this->session->set_userdata($data);
			//Actualizar session table
			$session_id = $this->session->userdata('id');
			$upd_session = $this->sesion->loggedin($data['user_data'], $session_id);
			if ($upd_session == "done") {
				$this->acceso();
			} else {
				$this->index('<div id="message">El nombre de usuario y/o contraseña es incorrecto, intente de nuevo.</div>');
			}
		} else {
			$this->index('<div id="message">El nombre de usuario y/o contraseña es incorrecto, intente de nuevo.</div>');
		}
	}

	function logout() {
		$this->session->sess_destroy();
		$this->index('<div id="message">La sesión se ha cerrado exitosamente.</div>');
	}

	function acceso() {
		//Controla todos los redireccionamientos y peticiones validando las sesiones activas
		$validate = false;
		if ($this->session->userdata('logged_in') == TRUE) {
			/* 			$this->assetlibpro->add_js('jquery.jdigiclock.js');
			 $this->assetlibpro->add_css('jquery.jdigiclock.css'); */
			$user_hash = $this->session->userdata('user_data');
			$row = $this->usuario->get_usuario_detalles($user_hash);
			if ($row == false)
				redirect(base_url() . "index.php/inicio/logout");
			$usuarioid = $row->id;
			$espacio_fisico_id = $row->espacio_fisico_id;
			$username = $row->username;
			$usuario = $row->nombre;
			$grupoid = $row->grupo_id;
			$puestoid = $row->puesto_id;
			$data['usuarioid'] = $usuarioid;
			$GLOBALS['empresa_id'] = $row->empresas_id;
			$data['username'] = $username;
			$data['espacio_fisico_id'] = $espacio_fisico_id;
			$data['espacio'] = $this->espacio_fisico->get_espacios_f_tag($espacio_fisico_id);
			//echo $espacio_fisico_id;
			$data['grupoid'] = $grupoid;
			$data['puestoid'] = $puestoid;
			$colect1 = $this->menu->menus($usuarioid, "principal", 0);
			if (is_array($colect1)) {
				$data['colect1'] = $colect1;
				$data['modulos_totales'] = $this->modulo->get_tmodulos();
			} else {
				//En caso de no tener modulos asociados
				$this->index('<div id="message">El usuario no tiene establecidos permisos de acceso definidos, consulte con el Administrador del Sistema.</div>');
			}
			//Redirecciones a los Menus de los Modulos, Submodulos y Acciones
			$ruta = $this->uri->segment(3);
			//Controller
			$controller = $this->uri->segment(4);
			//Accion
			$funcion = $this->uri->segment(5);
			$data['principal'] = "";
			//Identificando Pagina Principal
			if ($ruta == '' and $this->uri->segment(2) == "login" or $this->uri->segment(3) == "bienvenida") {
				$data['principal'] = "bienvenida";
				$data['title'] = "Menú Principal";
				$data['usuario'] = $usuario;
				//Obtener las promociones vigentes
				$data['promociones'] = $this->promocion->get_promociones_validas();
			}

			//Redirecciones a las Areas de trabajo
			else if ($data['principal'] != "bienvenida") {
				$data['title'] = "Bienvenidos";
				//Obtener las promociones vigentes
				$data['promociones'] = $this->promocion->get_promociones_validas();
				$modulo_id = $this->modulo->get_modulo_id($ruta);
				if ($modulo_id > 0) {
					if ($controller == '' or $controller == "menu") {
						$data['module_name'] = $this->modulo->get_modulo_name($ruta);
						//Se trata de mostrar los Submodulos y acciones del modulo
						$data['principal'] = $ruta . "/menu";
						//Obtener los id de los submodulos y acciones de la view_usuarios_acciones y obtener el tipo de accion y etiquetas
						//Transacciones
						$com = $this->menu->menus($usuarioid, "submodulos", $modulo_id);
						$colect2 = $com[0];
						$colect3 = $com[1];

						if (is_array($colect2)) {
							$data['colect2'] = $colect2;
						} else {
							//no se encontraron submodulos para el usuario
						}

						if (is_array($colect3)) {
							$data['colect3'] = $colect3;
						} else {
							//no se encontraron acciones para el usuario
						}
					} else if ($controller != "" and $funcion != "") {
						redirect("index.php" . $ruta . "/" . $controller . "/" . $funcion);
					}
				}
			}
			//Cargar el Template
			$this->load->view('ingreso', $data);
			unset($data);
		} else {
			$this->index('<div id="message">El usuario no tiene establecidos permisos de acceso definidos, consulte con el Administrador del Sistema.</div>');
		}
	}

	//Final

	function changue_password() {
		if ($this->session->userdata('logged_in') == FALSE)
			return;
		$user_hash = $this->session->userdata('user_data');
		$row = $this->usuario->get_usuario_detalles($user_hash);
		if ($row == false)
			redirect(base_url() . "index.php/inicio/logout");
		if ($this->uri->segment(3) == 'confirm') {
			if (!isset($_POST['old_pass']) || strlen($_POST['old_pass']) < 1)
				show_error('Debe introducir su viejo password');
			if (!isset($_POST['new_pass']) || strlen($_POST['new_pass']) < 1)
				show_error('Debe introducir su nuevo password');
			if (!isset($_POST['conf_pass']) || strlen($_POST['conf_pass']) < 1)
				show_error('Debe confirmar su nuevo password');
			if ($_POST['new_pass'] != $_POST['conf_pass'])
				show_error('El password nuevo no coincide con el de confirmación');
			$exists = $this->db->query(
					"SELECT id FROM usuarios WHERE id = $row->id AND passwd = md5('" . $_POST['old_pass'] . "')");
			if (!$exists->row())
				show_error('La contraseña es incorrecta, intentelo de nuevo');
			if ($this->db->query(
					"UPDATE usuarios SET passwd = md5('" . $_POST['new_pass'] . "') WHERE id = $row->id;"))
				echo utf8_decode("<html> <script>alert(\"Se ha cambiado la contraseña exitosamente.\"); window.location='" . base_url() . "index.php/inicio/acceso/bienvenida';</script></html>");
			else
				show_error('Ocurrió un error al cambiar su contraseña, intentelo más tarde');
			return;
		}
		$data['title'] = 'Cambio de contraseña';
		$data['usuarioid'] = $row->id;
		$data['username'] = $row->username;
		$GLOBALS['ruta'] = $this->uri->segment(1);
		$GLOBALS['controller'] = $this->uri->segment(2);
		$GLOBALS['funcion'] = $this->uri->segment(3);
		$GLOBALS['subfuncion'] = $this->uri->segment(4);
		$data['principal'] = 'change_password';

		$usuarioid = $row->id;
		$colect1 = $this->menu->menus($usuarioid, "principal", 0);
		if (is_array($colect1)) {
			$data['colect1'] = $colect1;
			$data['modulos_totales'] = $this->modulo->get_tmodulos();
		} else {
			//En caso de no tener modulos asociados
			$this->index('<div id="message">El usuario no tiene establecidos permisos de acceso definidos, consulte con el Administrador del Sistema.</div>');
		}
		$this->load->view("change_password", $data);
	}

}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */
?>
