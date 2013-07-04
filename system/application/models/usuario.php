<?php

/**
 * Usuario Class
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Salvador Salgado Ramíre SARS
 * @link
 */
class Usuario extends DataMapper {

	var $table= "usuarios";

	var $has_many = array(
			'color_producto' => array(
					'class' => 'color_producto',
					'other_field' => 'usuario'
			)
	);
	function  Usuario($id=null)
	{
		parent::DataMapper($id);

	}

	// --------------------------------------------------------------------

	/**
	 * Login
	 *
	 * Authenticates a user for logging in.
	 *
	 * @access	public
	 * @return	bool
	 */
	function autenticar($username, $passwd)
	{
		// Create a temporary user object
		$u = new Usuario();

		//Buscar en la base de datos
		$u->select('id, email');
		$u->where('username',$username);
		$u->where('passwd',$passwd);
		$u->where('estatus_general_id',1);
		$u->get();
		if($u->c_rows == 1 ){
	  return $u;
		} else {
	  return FALSE;
		}
	}

	/**
	 * usuario_modulos
	 *
	 * Obtiene un usuario a partir del hash de la cookie para .
	 *
	 * @access	public
	 * @return	array
	 */
	function get_usuario_detalles($user_hash)
	{
		// Create a temporary user object
		$u = new Usuario();
		//Buscar en la base de datos
		$u->select('id, nombre, username, empresas_id, grupo_id, puesto_id, espacio_fisico_id');
		$u->where("user_data",$user_hash);
		$u->get();
		if($u->c_rows==1){
			return $u;
		} else {
			//echo "no";
			return FALSE;
		}
	}
	/**
	 * get_usuario_md5
	 *
	 * Obtiene el id de usuario a partir del hash de la cookie.
	 *
	 * @access	public
	 * @return	array
	 */
	function get_usuario($id)
	{
		// Create a temporary user object
		$u = new Usuario();
		//Buscar en la base de datos
		$u->get_by_id($id);
		if($u->c_rows ==1){
			return $u;
		} else {
			return FALSE;
		}
	}
	/**
	 * Obtener Listado de Usuarios, id, username, nombre, empresa, ubicacion, email
	 *
	 * .
	 *
	 * @access	public
	 * @return	array
	 */
	function get_usuarios_list($offset, $per_page)
	{
		// Create a temporary user object
		$u = new Usuario();
		$sql="select u.*, e.razon_social as empresa, ef.tag as espacio_fisico, p1.tag as puesto from usuarios as u left join empresas as e on e.id=u.empresas_id left join espacios_fisicos as ef on ef.id=u.espacio_fisico_id left join puestos as p1 on p1.id=u.puesto_id order by u.nombre limit $per_page offset $offset ";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}
	/**
	 * Obtener Listado de Usuarios, id, username, nombre, empresa, ubicacion, email para pdf
	 *
	 * .
	 *
	 * @access	public
	 * @return	array
	 */
	function get_usuarios_pdf($where, $order)
	{
		// Create a temporary user object
		$u = new Usuario();
		$sql="select u.*, e.razon_social as empresa, ef.tag as espacio_fisico, p1.tag as puesto from usuarios as u left join empresas as e on e.id=u.empresas_id left join espacios_fisicos as ef on ef.id=u.espacio_fisico_id left join puestos as p1 on p1.id=u.puesto_id $where $order";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}
	function get_usuario_by_key($key)
	{
		// Create a temporary user object
		$u = new Usuario();
		$u->where('llave_electronica', "$key");
		//Buscar en la base de datos
		$u->get();
		if($u->c_rows == 1){
			return $u->id;

		} else {
			return FALSE;
		}
	}

}
