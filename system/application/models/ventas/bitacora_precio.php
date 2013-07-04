<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


class Bitacora_precio extends DataMapper {

	var $table= "bitacora_precios";

	function Bitacora_precio ($id = NULL)
	{
		parent::DataMapper($id);
	}
        
        function get_bitacora(){
                $a= new Bitacora_precio();
		$a->get();
		if($a->c_rows==1){
			return $a;
		} else {
			return FALSE;
		}
            
        }


}


?>
