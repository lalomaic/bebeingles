<?php

class Trans extends Controller {

	function Trans() {
		parent::Controller();
		$this->load->model("empresa");
		$this->load->model("espacio_fisico");
		$this->load->model("grupo");
		$user_hash = $this->session->userdata('user_data');
		$row = $this->usuario->get_usuario_detalles($user_hash);
		$GLOBALS['usuarioid'] = $row->id;
		$GLOBALS['username'] = $row->username;
		$GLOBALS['ruta'] = $this->uri->segment(1);
		$GLOBALS['controller'] = $this->uri->segment(2);
		$GLOBALS['funcion'] = $this->uri->segment(3);
	}

	function act_usuario() {
		$u = new Usuario();
		$related = $u->from_array($_POST);
		if ($u->save($related)) {
			$this->db->query("update control_actualizaciones set fecha_cambio='" . date("Y-m-d") . "',  hora='" . date("H:i:s") . ".00' where id=9");
			echo "<html> <script>alert(\"Se han actualizado los generales del usuario.\"); window.location='" . base_url() . "index.php/inicio/acceso/" . $GLOBALS['ruta'] . "/menu';</script></html>";
		} else {
			show_error("" . $u->error->string);
		}
	}

	function alta_usuario() {
		$u = new Usuario();
		$u->passwd = md5($_POST['username']);
		$u->estatus_general_id = 1;
		$related = $u->from_array($_POST);
		if ($u->save($related)) {
			$u->user_data = md5($u->id . "&" . $u->email);
			$g = new Grupo_accion();
			$g->where("grupos_id", $_POST['grupo_id']);
			$g->get();
			foreach($g->all as $row){
				$us = new Usuario_accion();
				$us->acciones_id = $row->acciones_id;
				$us->permisos = $row->permisos;
				$us->grupo_id = $row->grupos_id;
				$us->usuario_id = $u->id;
				$us->capturista_id = $GLOBALS['usuarioid'];
				$us->date = date("Y-m-d");
				if(!$us->save())
					show_error("Error generando permisos: " . $us->error->string);
			}
			$u->save();
			$this->db->query("update control_actualizaciones set fecha_cambio='" . date("Y-m-d") . "',  hora='" . date("H:i:s") . "'where id=9");
			echo "<html> <script>alert(\"Se ha dado de alta el usuario.\"); window.location='" . base_url() . "index.php/inicio/acceso/" . $GLOBALS['ruta'] . "/menu';</script></html>";
		} else {
			show_error("" . $u->error->string);
		}
	}

	function alta_espacio_f() {
		//Guardar el espacio físico
		$u = new Espacio_fisico();
		$u->capturista_id = $GLOBALS['usuarioid'];
		$related = $u->from_array($_POST);
		if ($u->save($related)) {
			echo "<html> <script>alert(\"Se ha registrado los datos del local.\"); window.location='" . base_url() . "index.php/admin/generales_c/formulario/list_espacios_f';</script></html>";
		} else {
			show_error("" . $u->error->string);
		}
	}

	function act_espacio_f() {
		$u = new Espacio_fisico();
		$u->capturista_id = $GLOBALS['usuarioid'];
		$related = $u->from_array($_POST);
		if ($u->save($related)) {
			echo "<html> <script>alert(\"Se han actualizado los datos del espacio físico.\"); window.location='" . base_url() . "index.php/admin/generales_c/formulario/list_espacios_f';</script></html>";
		} else {
			show_error("" . $u->error->string);
		}
	}

	function alta_estado() {
		//Guardar el estado
		$u = new Estado();
		$u->capturista_id = $GLOBALS['usuarioid'];
		$related = $u->from_array($_POST);
		if ($u->save($related)) {
			echo "<html> <script>alert(\"Se ha registrado el Estado.\"); window.location='" . base_url() . "index.php/inicio/acceso/" . $GLOBALS['ruta'] . "/menu';</script></html>";
		} else {
			show_error("" . $u->error->string);
		}
	}

	function act_estado() {
		$u = new Estado();
		$u->capturista_id = $GLOBALS['usuarioid'];
		$related = $u->from_array($_POST);
		if ($u->save($related)) {
			echo "<html> <script>alert(\"Se ha actualizado el nombre del Estado.\"); window.location='" . base_url() . "index.php/inicio/acceso/" . $GLOBALS['ruta'] . "/menu';</script></html>";
		} else {
			show_error("" . $u->error->string);
		}
	}

	function alta_municipio() {
		//Guardar el estado
		$u = new Municipio();
		$u->capturista_id = $GLOBALS['usuarioid'];
		$related = $u->from_array($_POST);
		if ($u->save($related)) {
			echo "<html> <script>alert(\"Se ha registrado el Municipio.\"); window.location='" . base_url() . "index.php/inicio/acceso/" . $GLOBALS['ruta'] . "/menu';</script></html>";
		} else {
			show_error("" . $u->error->string);
		}
	}

	function act_municipio() {
		$u = new Municipio();
		$u->capturista_id = $GLOBALS['usuarioid'];
		$related = $u->from_array($_POST);
		if ($u->save($related)) {
			echo "<html> <script>alert(\"Se han actualizado los datos del Municipio.\"); window.location='" . base_url() . "index.php/inicio/acceso/" . $GLOBALS['ruta'] . "/menu';</script></html>";
		} else {
			show_error("" . $u->error->string);
		}
	}

	function alta_empresa() {
		//Guardar el estado
		$u = new Empresa();
		$u->capturista_id = $GLOBALS['usuarioid'];
		$related = $u->from_array($_POST);
		if ($u->save($related)) {
			echo "<html> <script>alert(\"Se ha registrado la Empresa.\"); window.location='" . base_url() . "index.php/inicio/acceso/" . $GLOBALS['ruta'] . "/menu';</script></html>";
		} else {
			show_error("" . $u->error->string);
		}
	}

	function act_empresa() {
		$u = new Empresa();
		$u->capturista_id = $GLOBALS['usuarioid'];
		$related = $u->from_array($_POST);
		if ($u->save($related)) {
			echo "<html> <script>alert(\"Se han actualizado los datos de la Empresa.\"); window.location='" . base_url() . "index.php/inicio/acceso/" . $GLOBALS['ruta'] . "/menu';</script></html>";
		} else {
			show_error("" . $u->error->string);
		}
	}

	function alta_grupo() {
		//Guardar el estado
		$u = new Grupo();
		$u->usuario_id = $GLOBALS['usuarioid'];
		$u->nombre = $_POST['nombre'];
		$u->descripcion = $_POST['descripcion'];
		if ($u->save()) {
			unset($_POST['nombre']);
			unset($_POST['descripcion']);

			$varp = $_POST;
			$filas = $varp['filas'];
			unset($varp['filas']);
			$acciones = array();
			for ($r = 0; $r < $filas; $r++) {
				//Buscar en permisos asignados
				if (isset($varp['accion' . $r])) {
					$us = new Grupo_accion();
					$us->where("acciones_id", $varp['accion' . $r]);
					$us->where("grupos_id", $u->id);
					$us->get();
					if (isset($varp['permiso_a' . $r]))
						$us->permisos = $varp['permiso_a' . $r];
					else
						$us->permisos = 0;
					if (isset($varp['permiso_b' . $r]))
						$us->permisos+=$varp['permiso_b' . $r];
					if (isset($varp['permiso_e' . $r]))
						$us->permisos+=$varp['permiso_e' . $r];

					$us->acciones_id = $varp['accion' . $r];
					$us->grupos_id = $u->id;
					$us->capturista_id = $GLOBALS['usuarioid'];
					$us->date = date("Y-m-d");
					$us->save();
					$acciones[$r] = $us->acciones_id;
					//echo "<br/>$r".$us->acciones_id;
				}
			}
			$acciones_str = implode(', ', $acciones);
			$this->db->query("delete from grupos_acciones where grupos_id=$u->id and acciones_id not in($acciones_str)");

			echo "<html> <script>alert(\"Se ha registrado el Grupo.\"); window.location='" . base_url() . "index.php/inicio/acceso/" . $GLOBALS['ruta'] . "/menu';</script></html>";
		}
		else {
			show_error("" . $u->error->string);
		}
	}

	function act_grupo() {
		$u = new Grupo();
		$u->capturista_id = $GLOBALS['usuarioid'];
		$related = $u->from_array($_POST);
		if ($u->save($related)) {
			echo "<html> <script>alert(\"Se ha actualizado el nombre del Grupo.\"); window.location='" . base_url() . "index.php/inicio/acceso/" . $GLOBALS['ruta'] . "/menu';</script></html>";
		} else {
			show_error("" . $u->error->string);
		}
	}

	function asignar_permisos_grupo() {
		//print_r($_POST);
		$varp = $_POST;
		$filas = $varp['filas'];
		$grupo_id = $varp['grupos_id'];
		unset($varp['filas']);
		unset($varp['grupos_id']);
		$acciones = array();
		for ($r = 0; $r < $filas; $r++) {
			//Buscar en permisos asignados
			if (isset($varp['accion' . $r])) {
				$us = new Grupo_accion();
				$us->where("acciones_id", $varp['accion' . $r]);
				$us->where("grupos_id", $grupo_id);
				$us->get();
				if (isset($varp['permiso_a' . $r]))
					$us->permisos = $varp['permiso_a' . $r];
				else
					$us->permisos = 0;
				if (isset($varp['permiso_b' . $r]))
					$us->permisos+=$varp['permiso_b' . $r];
				if (isset($varp['permiso_e' . $r]))
					$us->permisos+=$varp['permiso_e' . $r];

				$us->acciones_id = $varp['accion' . $r];
				$us->grupos_id = $grupo_id;
				$us->capturista_id = $GLOBALS['usuarioid'];
				$us->date = date("Y-m-d");
				$us->save();
				$acciones[$r] = $us->acciones_id;
				//echo "<br/>$r".$us->acciones_id;
			}
		}
		$acciones_str = implode(', ', $acciones);
		$this->db->query("delete from grupos_acciones where grupos_id=$grupo_id and acciones_id not in($acciones_str)");
		echo "<html> <script>alert(\"Se han actualizado los permisos del Grupo.\"); window.location='" . base_url() . "index.php/inicio/acceso/" . $GLOBALS['ruta'] . "/menu';</script></html>";
	}

	function alta_puesto() {
		//Guardar el estado
		$u = new Puesto();
		$u->usuario_id = $GLOBALS['usuarioid'];
		$related = $u->from_array($_POST);
		if ($u->save($related)) {
			echo "<html> <script>alert(\"Se ha registrado el Puesto.\"); window.location='" . base_url() . "index.php/inicio/acceso/" . $GLOBALS['ruta'] . "/menu';</script></html>";
		} else {
			show_error("" . $u->error->string);
		}
	}

	function act_puesto() {
		$u = new Puesto();
		$u->capturista_id = $GLOBALS['usuarioid'];
		$related = $u->from_array($_POST);
		if ($u->save($related)) {
			echo "<html> <script>alert(\"Se han actualizado los datos del Puesto.\"); window.location='" . base_url() . "index.php/inicio/acceso/" . $GLOBALS['ruta'] . "/menu';</script></html>";
		} else {
			show_error("" . $u->error->string);
		}
	}

	function asignar_permisos_usuario() {
		//print_r($_POST);
		$varp = $_POST;
		$usuario_id = $varp['usuario_id'];
		$filas = $varp['filas'];
		unset($varp['usuario_id']);
		unset($varp['filas']);
		$acciones = array();
		for ($r = 0; $r < $filas; $r++) {
			//Buscar en permisos asignados
			if (isset($varp['accion' . $r])) {
				$us = new Usuario_accion();
				$us->where("acciones_id", $varp['accion' . $r]);
				$us->where("usuario_id", $usuario_id);
				$us->get();
				if (isset($varp['permiso_a' . $r]))
					$us->permisos = $varp['permiso_a' . $r];
				else
					$us->permisos = 0;
				if (isset($varp['permiso_b' . $r]))
					$us->permisos+=$varp['permiso_b' . $r];
				if (isset($varp['permiso_e' . $r]))
					$us->permisos+=$varp['permiso_e' . $r];

				$us->acciones_id = $varp['accion' . $r];
				$us->usuario_id = $usuario_id;
				$us->capturista_id = $GLOBALS['usuarioid'];
				$us->date = date("Y-m-d");
				$us->save();
				$acciones[$r] = $us->acciones_id;
				//echo "<br/>$r".$us->acciones_id;
			}
		}
		$acciones_str = implode(', ', $acciones);
		$this->db->query("delete from usuarios_acciones where usuario_id=$usuario_id and acciones_id not in($acciones_str)");
		echo "<html> <script>alert(\"Se han actualizado los permisos del Usuario.\"); window.location='" . base_url() . "index.php/inicio/acceso/" . $GLOBALS['ruta'] . "/menu';</script></html>";
	}

}

?>
