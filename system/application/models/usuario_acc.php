<?php

/**
 * Usuario Acciones Class
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Salvador Salgado Ramíre SARS
 * @link
 */
class Usuario_acc extends DataMapper {

	var $table= "view_usuarios_acciones";

	function Usuario_acc($id=null)
	{
		parent::DataMapper($id);
	}


	/**
	 * Obtiene los modulos a los que tiene acceso el usuario
	 * @category	Models
	 * @author  	Salvador Salgado Ram�rez SARS
	 * @arguments	usuarioid
	 */
	function modulos_usuario($usuarioid){
		$sql=$this->db->query("select distinct(modulo_id), nombre, ruta from view_usuarios_acciones left join modulos as m on m.id=modulo_id where usuario_id='$usuarioid' and modulo_id is not null group by modulo_id, nombre, ruta order by nombre");
		if($sql->num_rows>0){
			return $sql;
		} else {
			return false;
		}
	}
	/**
	 * Obtiene los submodulos a los que tiene acceso el usuario
	 * @category	Models
	 * @author  	Salvador Salgado Ramíre SARS
	 * @arguments   usuarioid
	 */
	function submodulos_usuario($usuarioid){
		$sql=$this->db->query("select distinct(submodulo_id) as submodulo_id, s.modulo_id,nombre,controller from view_usuarios_acciones left join submodulos as s on s.id=submodulo_id where usuario_id='$usuarioid' group by submodulo_id, s.modulo_id,nombre,controller order by nombre");
		if($sql->num_rows>0){
			return $sql;
		} else {
			return false;
		}
	}
	/**
	 * Obtiene las acciones a las que tiene acceso el usuario
	 * @category	Models
	 * @author  	Salvador Salgado Ramíre SARS
	 * @arguments   usuarioid
	 */
	function acciones_usuario($usuarioid){
		$sql=$this->db->query("select distinct(u1.acciones_id), a.submodulo_id, nombre, function_controller, tipo_accion_id from view_usuarios_acciones as u1 left join acciones as a on a.id=u1.acciones_id where usuario_id=$usuarioid group by acciones_id, a.submodulo_id, nombre, function_controller, tipo_accion_id order by nombre");
		if($sql->num_rows>0){
			return $sql;
		} else {
			return false;
		}
	}
	/**
	 * Obtiene los modulos a los que tiene acceso el puesto
	 * @category	Models
	 * @author  	Salvador Salgado Ramíre SARS
	 * @arguments	puestoid
	 */
	function modulos_puesto($puestoid){

		$sql=$this->db->query("select distinct(ac1.acciones_id) from view_usuarios_acciones as u1 left join acciones as ac1 on ac1.id=u1.acciones_id where puesto_id=$puestoid");
		if($sql->num_rows>0){
			return $sql;
		} else {
			return false;
		}
	}
	/**
	 * Obtiene los submodulos a los que tiene acceso el puesto
	 * @category	Models
	 * @author  	Salvador Salgado Ramíre SARS
	 * @arguments   puesto
	 */
	function submodulos_puesto($puestoid){
		$sql=$this->db->query("select distinct(ac1.submodulo_id) from view_usuarios_acciones as u1 left join acciones as ac1 on ac1.id=u1.acciones_id where puesto_id=$puestoid");
		if($sql->num_rows>0){
			return $sql;
		} else {
			return false;
		}
	}
	/**
	 * Obtiene las acciones a las que tiene acceso el puesto
	 * @category	Models
	 * @author  	Salvador Salgado Ramíre SARS
	 * @arguments   puestoid
	 */
	function acciones_puesto($puestoid){
		$sql=$this->db->query("select distinct(u1.acciones_id) from view_usuarios_acciones as u1 where puesto_id=$puestoid");
		if($sql->num_rows>0){
			return $sql;
		} else {
			return false;
		}
	}
	/**
	 * Obtiene los modulos a los que tiene acceso el grupo
	 * @category	Models
	 * @author  	Salvador Salgado Ramíre SARS
	 * @arguments	grupoid
	 */
	function modulos_grupo($grupoid){
		$sql=$this->db->query("select distinct(ac1.modulo_id) from view_usuarios_acciones as u1 left join acciones as ac1 on ac1.id=u1.acciones_id where grupo_id=$grupoid");
		if($sql->num_rows>0){
			return $sql;
		} else {
			return false;
		}
	}
	/**
	 * Obtiene los submodulos a los que tiene acceso el grupo
	 * @category	Models
	 * @author  	Salvador Salgado Ramíre SARS
	 * @arguments   grupo
	 */
	function submodulos_grupo($grupoid){
		$sql=$this->db->query("select distinct(ac1.submodulo_id) from view_usuarios_acciones as u1 left join acciones as ac1 on ac1.id=u1.acciones_id where grupo_id=$grupoid");
		if($sql->num_rows>0){
			return $sql;
		} else {
			return false;
		}
	}
	/**
	 * Obtiene las acciones a las que tiene acceso el grupo
	 * @category	Models
	 * @author  	Salvador Salgado Ramíre SARS
	 * @arguments   grupoid
	 */
	function acciones_grupo($grupoid){
		$sql=$this->db->query("select distinct(u1.acciones_id) from view_usuarios_acciones as u1 where grupo_id=$grupoid");
		if($sql->num_rows>0){
			return $sql;
		} else {
			return false;
		}
	}
}
