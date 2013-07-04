<?php

/**
 * Usuario Accion Class
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Salvador Salgado RamÃ­re SARS
 * @link    	
 */
class Usuario_accion extends DataMapper {

	var $table= "usuarios_acciones";

	function Usuario_accion($id=null)
	{
		parent::DataMapper($id);

	}

/**
 * Obtiene los modulos a los que tiene acceso el usuario
 * @category	Models
 * @author  	Salvador Salgado RamÃ­re SARS
 * @arguments	usuarioid    	
*/
	function get_permiso($acciones_id, $usuarioid){

		$row=$this->usuario->get_usuario($usuarioid);
		$grupoid=$row->grupo_id;
		$puestoid=$row->puesto_id;

		$ua= new Usuario_accion();
		$ua=where('usuario_id', $usuarioid);
		$ua=where('acciones_id', $acciones_id);
		$ua->get()
		if($ua->num_rows==1){
		  return $ua->permisos;
		} else {
		  $ua= new Usuario_accion();
		  $ua=where('grupo_id', $grupoid);
		  $ua=where('acciones_id', $acciones_id);
		  $ua->get()
		  if($ua->num_rows==1){
		    return $ua->permisos;
		  } else {
		  $ua= new Usuario_accion();
		  $ua=where('puesto_id', $puestoid);
		  $ua=where('acciones_id', $acciones_id);
		  $ua->get()
		  if($ua->num_rows==1){
		    return $ua->permisos;
		  } else {
		    return false;
		  }
		}
	      }
	}
/**
 * Obtiene los submodulos a los que tiene acceso el usuario
 * @category	Models
 * @author  	Salvador Salgado RamÃ­re SARS
 * @arguments   usuarioid    	
*/
	function submodulos_usuario($usuarioid){
		$sql=$this->db->query("select distinct(submodulo_id) as submodulo_id from view_usuarios_acciones where usuario_id='$usuarioid' order by submodulo_id");
		if($sql->num_rows>0){
		  return $sql;
		} else {
		  return false;
		}
	}
/**
 * Obtiene las acciones a las que tiene acceso el usuario
 * @category	Models
 * @author  	Salvador Salgado RamÃ­re SARS
 * @arguments   usuarioid    	
*/
	function acciones_usuario($usuarioid){
		$sql=$this->db->query("select distinct(u1.acciones_id) from view_usuarios_acciones as u1 where usuario_id=$usuarioid");
		if($sql->num_rows>0){
		  return $sql;
		} else {
		  return false;
		}
	}
/**
 * Obtiene los modulos a los que tiene acceso el puesto
 * @category	Models
 * @author  	Salvador Salgado RamÃ­re SARS
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
 * @author  	Salvador Salgado RamÃ­re SARS
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
 * @author  	Salvador Salgado RamÃ­re SARS
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
 * @author  	Salvador Salgado RamÃ­re SARS
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
 * @author  	Salvador Salgado RamÃ­re SARS
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
 * @author  	Salvador Salgado RamÃ­re SARS
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

?>	