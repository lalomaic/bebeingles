<?php

/**
 * Usuario Accion Class
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Salvador Salgado Ram�re SARS
 * @link
 */
class Usuario_accion extends DataMapper {

	var $table= "usuarios_acciones";

	function Usuario_accion($id=null)
	{
		parent::DataMapper($id);

	}

	/**
	 * Obtiene los permisos a los que tiene acceso el usuario
	 * @category	Models
	 * @author  	Salvador Salgado Ram�rez SARS
	 * @arguments	usuarioid
	 */
	function get_permisos_usuario($usuarioid){
		$u=new Usuario_accion();
		$u->where('usuario_id', $usuarioid)->order_by('acciones_id', 'asc')->get();
		$prev=0;
		foreach($u->all as $row){
			//Limpiar en caso de haber acciones duplicadas para el mismo usuario
			if($row->acciones_id==$prev){
				$this->db->query("delete from usuarios_acciones where id=$row->id");
			}
			$prev=$row->acciones_id;
		}
		$sql=$this->db->query("select * from view_usuarios_acciones where usuario_id=$usuarioid order by modulo_id, submodulo_id, acciones_id");
		if($sql->num_rows>0){
			foreach($sql->result() as $line){
				$acciones_permisos[$line->acciones_id]=$line->permisos;
			}
			$sql->free_result();
			$u->clear();
			return $acciones_permisos;
		} else
			return false;
	}



	/**
	 * Obtiene los modulos a los que tiene acceso el usuario
	 * @category	Models
	 * @author  	Salvador Salgado Ram�re SARS
	 * @arguments	usuarioid
	 */
	function get_permiso($acciones_id, $usuarioid, $puestoid, $grupoid){
		$ua= new Usuario_accion();
		$ua->where('usuario_id', $usuarioid);
		$ua->where('acciones_id', $acciones_id);
		$ua->get();

		//echo "$usuarioid--$acciones_id".$ua->permisos;
		if($ua->c_rows>0){
			return $ua->permisos;
		} else {

			//$ua= new Usuario_accion();
			$ua->where('grupo_id', $grupoid);
			$ua->where('acciones_id', $acciones_id);
			$ua->get();
			if($ua->c_rows==1){
				return $ua->permisos;
			} else {
		  //$ua= new Usuario_accion();
		  $ua->where('puesto_id', $puestoid);
		  $ua->where('acciones_id', $acciones_id);
		  $ua->get();
		  if($ua->c_rows==1){
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
	 * @author  	Salvador Salgado Ram�re SARS
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
	 * @author  	Salvador Salgado Ram�re SARS
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
	 * @author  	Salvador Salgado Ram�re SARS
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
	 * @author  	Salvador Salgado Ram�re SARS
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
	 * @author  	Salvador Salgado Ram�re SARS
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
	 * @author  	Salvador Salgado Ram�re SARS
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
	 * @author  	Salvador Salgado Ram�re SARS
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
	 * @author  	Salvador Salgado Ram�re SARS
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

