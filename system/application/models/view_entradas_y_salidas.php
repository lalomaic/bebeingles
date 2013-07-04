<?php

class View_entradas_y_salidas extends DataMapper {

	var $table = "view_entradas_y_salidas";
	var $has_one = array(
			'cproductos' => array(
					'class' => 'producto',
					'other_field' => 'view_entradas_y_salidas'
			)
	);

	function View_entradas_y_salidas($id=null) {
		parent::DataMapper($id);
	}
}
