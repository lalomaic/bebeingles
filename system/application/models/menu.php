<?php

/**
 * Modelo para Escribir el menu en la IGU fuera de DataMapper
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Salvador Salgado Ram�re SARS
 * @link
 */
class Menu extends Model{


	function Menu()
	{
		parent::Model();
		$this->load->model( 'usuario_acc' );
		$this->load->model( 'usuario' );
		$this->load->model( 'modulo' );
		$this->load->model( 'submodulo' );
		$this->load->model( 'accion' );

	}

	function menus($usuarioid, $tipo_menu="principal", $mid){
		$row=$this->usuario->get_usuario($usuarioid);
		$username=$row->username;
		$grupoid=$row->grupo_id;
		$puestoid=$row->puesto_id;
		//Obtener los menus
		if($this->usuario_acc->modulos_usuario($usuarioid)!=false) {
			$validate=true;
			//Buscando permisos especificos
			$modulos=$this->usuario_acc->modulos_usuario($usuarioid);
			$submodulos=$this->usuario_acc->submodulos_usuario($usuarioid);
			$acciones=$this->usuario_acc->acciones_usuario($usuarioid);
		} else {
			//Buscar por el tipo de puesto
			$modulos=$this->usuario_acc->modulos_puesto($puestoid);
			if($modulos!=false){
				$submodulos=$this->usuario_acc->submodulos_puesto($puestoid);
				$acciones=$this->usuario_acc->acciones_puesto($puestooid);
				$validate=true;
			} else {
				//Buscar por el grupo
				$modulos=$this->usuario_acc->modulos_grupo($grupoid);
				if($modulos!=false){
					$submodulos=$this->usuario_acc->submodulos_grupo($grupoid);
					$acciones=$this->usuario_acc->acciones_grupo($grupoid);
					$validate=true;
				} else {
					//**************No existen asignaciones para el usuario, ejecutar una accion para este tipo de usuarios
					$this->index('<div id="message">El usuario no tiene establecidos permisos de acceso definidos, consulte con el Administrador del Sistema.</div>');
					$validate=false;
				}
			}
		}
		if($validate==true){
			/*Generar las matrices para los menus con los Modulos y Submodulos
			 MODULOS*/
			$r=0;
			$colect1=array();
			foreach($modulos->result() as $row){
				$colect1[$row->modulo_id]['modulo_id']=$row->modulo_id;
				$colect1[$row->modulo_id]['nombre']=$row->nombre;
				$colect1[$row->modulo_id]['ruta']=$row->ruta;
			}
			//		print_r($colect1); exit();
			if($tipo_menu=="submodulos"){
				$colect2=array();
				//Submodulos
				foreach($submodulos->result() as $row) {
					if($row->modulo_id == $mid){
						$colect2[$row->submodulo_id]['submodulo_id']=$row->submodulo_id;
						$colect2[$row->submodulo_id]['ruta']=$colect1[$mid]['ruta'];
						$colect2[$row->submodulo_id]['modulo_id']=$row->modulo_id;
						$colect2[$row->submodulo_id]['nombre']=$row->nombre;
						$colect2[$row->submodulo_id]['controller']=$row->controller;
						//Agregar las acciones asignadas
						foreach($acciones->result() as $row2) {
							if($row2->submodulo_id==$row->submodulo_id){
								$colect3[$row2->acciones_id]['accion_id']=$row2->acciones_id;
								$colect3[$row2->acciones_id]['submodulo_id']=$row2->submodulo_id;
								$colect3[$row2->acciones_id]['nombre_accion']=$row2->nombre;
								$colect3[$row2->acciones_id]['function_controller']=$row2->function_controller;
								$colect3[$row2->acciones_id]['tipo_accion_id']=$row2->tipo_accion_id;
							}
						}
					}
				}
				$com[0]=$colect2;
				$com[1]=$colect3;
				return $com;
			} else {
		  return $colect1;
			}

		} else {
			return false;
		}
	}


}
