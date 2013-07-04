<?php

/**
 * Usuario Accion Class
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Diego
 * @link
 */
class Grupo_accion extends DataMapper {

	var $table = "grupos_acciones";

	function Grupo_accion($id=null) {
		parent::DataMapper($id);
	}

	/**
	 * Obtiene los permisos a los que tiene acceso el grupo
	 * @category	Models
	 * @author  	Diego
	 * @arguments	grupoid
	 */
	function get_grupo_accion($grupoid) {
		$u = new Grupo_accion();
		$u->where('grupos_id', $grupoid)->order_by('acciones_id', 'asc')->get();
		$prev = 0;
		foreach ($u->all as $row) {
			//Limpiar en caso de haber acciones duplicadas para el mismo usuario
			if ($row->acciones_id == $prev) {
				$this->db->query("delete from grupos_acciones where id=$row->id");
			}
			$prev = $row->acciones_id;
		}
		$sql = $this->db->query("select * from view_grupos_acciones where grupos_id=$grupoid order by modulo_id, submodulo_id, acciones_id");
		if ($sql->num_rows > 0) {
			foreach ($sql->result() as $line) {
				$acciones_permisos[$line->acciones_id] = $line->permisos;
			}
			$sql->free_result();
			$u->clear();
			return $acciones_permisos;
		} else
			return false;
	}

	/**
	 * Obtiene los permisos a los que tiene acceso el grupo
	 * @category	Models
	 * @author  	Diego
	 * @arguments	acciones_id, grupoid
	 */
	function get_permiso($acciones_id, $grupoid) {
		$ua = new Grupo_accion();
		$ua->where('grupos_id', $grupoid);
		$ua->where('acciones_id', $acciones_id);
		$ua->get();

		if ($ua->c_rows > 0) {
			return $ua->permisos;
		} else {
			return false;
		}
	}

}

