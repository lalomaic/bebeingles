<?php

/**
 * Usuario Class
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Salvador Salgado RamÃ­re SARS
 * @link
 */
class Sesion extends DataMapper {

	var $table= "sessions";

	function Sesion($id=null)
	{
		parent::DataMapper($id);
	}

	// --------------------------------------------------------------------

	/**
	 * Loggedin
	 *
	 * Uptadate session true on db.
	 *
	 * @access	public
	 * @return	bool
	 */
	function loggedin($user_data, $session_id)
	{
		// Create a temporary user object

		$result1=$this->db->query("select * from sessions where id='$session_id'");
		if ($result1->num_rows()==1){
			$this->db->query("update sessions set user_data='$user_data', ultimo_ingreso='".date("Y-m-d H:i:s")."' where id='$session_id'");
			$result1->free_result();
			return "done";
		} else {
			echo "Error de Sesión No. 1";
		}

	}



}
