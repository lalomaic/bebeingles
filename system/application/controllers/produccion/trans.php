<?php
class Trans extends Controller {
	function Trans()
	{
		parent::Controller();
		if($this->session->userdata('logged_in') == FALSE){
			redirect(base_url()."index.php/inicio/logout");
		}
		$this->load->model("modulo");
		$this->load->model("cl_detalle_pedido");
		$this->load->model("cliente");
		$this->load->model("espacio_fisico");
		$this->load->model("numero_letras");
		$this->load->model("receta");
		$this->load->model("receta_detalle");
		$this->load->model("almacen");
		$this->load->model("produccion");
		$this->load->model("produccion_detalle");
		$this->load->model("producto_transformado");
		$user_hash=$this->session->userdata('user_data');
		$row=$this->usuario->get_usuario_detalles($user_hash);
		$GLOBALS['usuarioid']=$row->id;
		$GLOBALS['username']=$row->username;
		$GLOBALS['ubicacion_id']=$row->espacio_fisico_id;
		$GLOBALS['empresa_id']=$row->empresas_id;
		$GLOBALS['ruta']=$this->uri->segment(1);
		$GLOBALS['controller']=$this->uri->segment(2);
		$GLOBALS['funcion']=$this->uri->segment(3);

	}

	function alta_receta(){
		//Guardar el usuario
		$u= new Receta();
		//$u->empresas_id=$GLOBALS['empresa_id'];
		//	  $u->no_lote=$this->diversos->get_no_lote();
		$u->estatus_general_id=1;
		$u->usuario_id=$GLOBALS['usuarioid'];
		$u->fecha_captura=date("Y-m-d H:i:s");
		$related = $u->from_array($_POST);
		// save with the related objects
		if($u->save($related)) {
			echo form_hidden('id', "$u->id");
			echo '<button type="submit" id="boton1" style="display:none;">Actualizar Registro</button>';
			echo "<p id=\"response\">Capturar los detalles de la Receta</p>";
		} else {
			show_error("".$u->error->string);
		}
	}

	function alta_receta_detalles(){
		//Guardar el usuario
		$varP=$_POST;
		//print_r($_POST);exit;
		$line=$this->uri->segment(4);
		$pr= new Receta_detalle();
		$pr->receta_id=$varP['receta_id'.$line];
		$pr->cproducto_id=$varP['producto'.$line];
		$pr->cantidad=$varP['cantidad'.$line];
		$pr->usuario_id=$GLOBALS['usuarioid'];
		$pr->fecha_captura=date("Y-m-d H:i:s");
		if(isset($varP['id'.$line])==true)
			$pr->id=$varP['id'.$line];
		if($pr->save()){
			echo form_hidden('id'.$line, "$pr->id"); echo form_hidden('receta_id'.$line, "$pr->receta_id"); echo "<a href=\"javascript:borrar_detalle('$pr->id', $line)\"><img src=\"".base_url()."images/trash1.png\" width=\"20px\" title=\"Borrar Detalle\"/></a><img src=\"".base_url()."images/ok.png\" width=\"20px\" title=\"Guardado\"/>";
		} else{
			echo '<img src="'.base_url().'images/stop.png" width="20px" title="Error al Guardar"/>';
		}
	}

	function eliminar_receta(){
		$id=$this->uri->segment(4);
		$d=new Receta();
		$this->db->trans_start();
		$this->db->query("delete from recetas where id='$id'");
		$this->db->trans_complete();
		echo "<html> <script>alert(\"Se ha Cancelado el Alta de Receta .\"); window.location='".base_url()."index.php/inicio/acceso/produccion/menu';</script></html>";
	}
	 
	function verificar_receta(){
		$id=$this->uri->segment(4);
		//echo $id; exit;
		//	$d=new Receta_detalle();
		//	$d->where("receta_id", $id);
		//	$d->get_receta_detalles();
		$this->db->select("*");
		$this->db->where('receta_id',$id);
		$res=$this->db->get('receta_detalles')->result();
		//print_r($res);
		$total=count($res);
		//echo $total; exit;
		if ($total>0){
			$p=new Receta();
			$p->get_by_id($id);
			if($p->save()){
				//Se valida que el pedido tiene registrados productos y se da por terminado enviando al listado de pedidos de compra
				echo "<html> <script>alert(\"Se ha registrado la Receta correctamente.\"); window.location='".base_url()."index.php/produccion/produccion_c/formulario/list_recetas';</script></html>";
			} else {
				/**No se encontro registro valido de productos dentro del pedido por lo cual se cancela*/
				$this->db->trans_start();
				$this->db->query("delete from receta_detalles where receta_id='$id'");
				$this->db->query("delete from recetas where id='$id'");
				$this->db->trans_complete();

				echo "<html> <script>alert(\"Se ha eliminado la Receta por falta de Detalles, intente de nuevo.\"); window.location='".base_url()."index.php/inicio/acceso/produccion/menu';</script></html>";
			}
		} else {
	  /**No se encontro registro valido de productos dentro del pedido por lo cual se cancela*/
			$this->db->trans_start();
			$this->db->query("delete from receta_detalles where receta_id='$id'");
			$this->db->query("delete from recetas where id='$id'");
			$this->db->trans_complete();
			echo "<html> <script>alert(\"Se ha eliminado la Receta por falta de Detalles, intente de nuevo.\"); window.location='".base_url()."index.php/inicio/acceso/produccion/menu';</script></html>";
		}
	}

	function alta_produccion(){
		//Guardar el usuario
		$u= new Produccion();
		$u->fecha_captura=date("Y-m-d H:i:s");
		$u->estatus_general_id=1;
		$u->usuario_id=$GLOBALS['usuarioid'];
		$u->espacio_fisico_id=$GLOBALS['ubicacion_id'];
		$related = $u->from_array($_POST);
		// save with the related objects
		if($u->save($related)) {
			//echo form_hidden('id', "$u->id");
			//echo form_hidden('receta_id', "$u->receta_id");
			//echo '<button type="submit" id="boton1" style="display:none;">Actualizar Registro</button>';
			//echo "<p id=\"response\">Capturar los detalles de la Receta</p>";
			$data['produccion_id']=$u->id;
			$data['receta_id']=$u->receta_id;
			$data['cantidad_producida']=$u->cantidad_producida;
			$sql="select rd.id, rd.cproducto_id, rd.cantidad, p.descripcion from receta_detalles as rd left join cproductos as p on rd.cproducto_id=p.id where rd.receta_id=$data[receta_id]";
		 $result=$this->db->query($sql)->result();
		 $data['receta_detalles']=$result;
		 $this->load->view("produccion/frame_detalles_produccion", $data);
		} else {
			show_error("".$u->error->string);
		}
	}

	function alta_produccion_detalles(){
		// 		print_r ($_POST);
		$datos=$_POST;
		$receta_id=$datos['receta_id'];
		unset($datos['receta_id']);
		$cantidad_producida=$datos['cantidad_producida'];
		unset($datos['cantidad_producida']);
		$lineas=$datos['lineas'];
		unset($datos['lineas']);
		if(count($datos)==0)
			show_error("La transformacion que esta intentando realizar no tiene los detalles de los productos, intente de nuevo]");
		$p=new Produccion();
		$p->receta_id=$receta_id;
		$p->cantidad_producida=$cantidad_producida;
		$p->fecha_captura=date("Y-m-d H:i:s");
		$p->estatus_general_id=1;
		$p->usuario_id=$GLOBALS['usuarioid'];
		$p->espacio_fisico_id=$GLOBALS['ubicacion_id'];

		$p->trans_begin();
		if($p->save()){
			for($r=0;$r<$lineas;$r++){
				//Realizar inserciones en la tabla salidas
				$s=new Salida();
				$s->cproductos_id=$datos['cproducto_id'.$r];
				$s->cantidad=$datos['cantidad_usada'.$r];
				//Del modelo almacen obtener el costo promedio de compra del insumo
				$s->costo_unitario=$this->almacen->get_precio_promedio($datos['cproducto_id'.$r]);
				$s->costo_total=$s->costo_unitario * $s->cantidad;
				$s->estatus_general_id=1;
				$s->espacio_fisico_id=$GLOBALS['ubicacion_id'];
				$s->fecha=date("Y-m-d H:i:s");
				$s->ctipo_salida_id=5;
				$s->usuario_id=$GLOBALS['usuarioid'];
				$s->trans_begin();
				if($s->save()){
					//Relizar insercion en la tabla produccion_detalles
					$pd=new Produccion_detalle();
					$pd->produccion_id=$p->id;
					$pd->cproducto_id=$s->cproductos_id;
					$pd->cantidad_usada=$datos['cantidad_usada'.$r];
					$pd->cantidad_receta=$datos['cantidad_receta'.$r];
					$pd->cantidad_merma=$datos['cantidad_merma'.$r];
					$pd->salida_id=$s->id;
					$pd->fecha_captura=date("Y-m-d H:i:s");
					$pd->usuario_id=$GLOBALS['usuarioid'];
					$pd->save();
				}

			}
			//Realizar insercion en la table entradas
			$e=new Entrada();
			$receta=$this->receta->get_receta($receta_id);
			$e->cproductos_id=$receta->cproductos_id;
			$e->cantidad=$cantidad_producida;
			//Del modelo almacen obtener el costo promedio de compra del insumo
			$e->costo_unitario=$this->almacen->get_precio_promedio($receta->cproductos_id);
			$e->costo_total=$e->costo_unitario * $e->cantidad;
			$e->estatus_general_id=1;
			$e->espacio_fisico_id=$GLOBALS['ubicacion_id'];
			$e->fecha=date("Y-m-d H:i:s");
			$e->ctipo_entrada=8;
			$e->usuario_id=$GLOBALS['usuarioid'];
			$e->trans_begin();
			if($e->save()){
				$p->entrada_id=$e->id;
				$p->save();
				$e->trans_commit();
				$s->trans_commit();
				$pd->trans_commit();
				$p->trans_commit();
				//Mensaje de exito y redireccion al listado de produccion
				echo "<html> <script>alert(\"Se ha registrado la Transformacion correctamente.\"); window.location='".base_url()."index.php/produccion/produccion_c/formulario/list_produccion';</script></html>";
			} else {
				$e->trans_rollback();
				$s->trans_rollback();
				$pd->trans_rollback();
				$p->trans_rollback();
			}
		} else {
			//Mensaje de error
			show_error("El registro de transformacion no se registro adecuadamente, intente de nuevo");
		}

	}

	function alta_producto_transformado(){
		//print_r ($_POST);
		//exit;
		$pt=new Producto_transformado();
		$pt->cproducto_id=$this->input->post('cproducto_id');
		$pt->cproducto_transformado_id=$this->input->post('cproducto_transformado_id');
		$pt->cantidad=$this->input->post('cantidad');
		$pt->estatus_general_id=1;
		$pt->trans_begin();
		if($pt->save()){
			//Realizar inserciones en la tabla salidas
			$s=new Salida();
			$s->cproductos_id=$this->input->post('cproducto_id');
			$s->cantidad=$this->input->post('cantidad');
			//Del modelo producto_transformado obtener el ultimo costo de salida del producto
			$s->costo_unitario=$this->producto_transformado->get_ultimo_precio_salida($this->input->post('cproducto_id'));
			$s->estatus_general_id=1;
			$s->espacios_fisicos_id=$GLOBALS['ubicacion_id'];
			$s->fecha=date("Y-m-d H:i:s");
			$s->ctipo_salida_id=5;
			$s->usuario_id=$GLOBALS['usuarioid'];

			//Realizar insercion en la table entradas
			$e=new Entrada();
			$e->cproductos_id=$this->input->post('cproducto_transformado_id');
			$e->cantidad=$this->input->post('cantidad');
			//Del modelo producto_transformado obtener el ultimo costo de entrada del producto
			$e->costo_unitario=$this->producto_transformado->get_ultimo_precio_entrada($this->input->post('cproducto_id'));
			$e->estatus_general_id=1;
			$e->espacios_fisicos_id=$GLOBALS['ubicacion_id'];
			$e->fecha=date("Y-m-d H:i:s");
			$e->ctipo_entrada=8;
			$e->usuario_id=$GLOBALS['usuarioid'];
			$e->trans_begin();
			if($s->save() and $e->save()){
				$pt->entrada_id=$e->id;
				$pt->salida_id=$s->id;
				$pt->save();
				$e->trans_commit();
				$s->trans_commit();
				$pt->trans_commit();
				//Mensaje de exito y redireccion al listado de produccion
				echo "<html> <script>alert(\"Se ha registrado la Transformacion del Producto correctamente.\"); window.location='".base_url()."index.php/produccion/produccion_c/formulario/list_producto_transformado';</script></html>";
			} else {
				$e->trans_rollback();
				$s->trans_rollback();
				$pt->trans_rollback();
			}
		} else {
			//Mensaje de error
			show_error("El registro de transformacion de producto no se registro adecuadamente, intente de nuevo");
		}

	}


}
?>
