<?php
class Trans extends Controller {
	function Trans()
	{
		parent::Controller();
		if($this->session->userdata('logged_in') == FALSE){
			redirect(base_url()."index.php/inicio/logout");
		}
		$this->load->model("diversos");
		$this->load->model("pr_detalle_pedido");
		$this->load->model("pr_pedido");
		$this->load->model("empresa");
		$this->load->model("espacio_fisico");
		$this->load->model("producto");
		$this->load->model("pr_pedido_multiple");
		$this->load->model("lote");
		$this->load->model("lote_factura");
		$user_hash=$this->session->userdata('user_data');
		$row=$this->usuario->get_usuario_detalles($user_hash);
		$GLOBALS['usuarioid']=$row->id;
		$GLOBALS['empresa_id']=$row->empresas_id;
		$GLOBALS['ubicacion_id']=$row->espacio_fisico_id;
		$GLOBALS['username']=$row->username;
		$GLOBALS['ruta']=$this->uri->segment(1);
		$GLOBALS['controller']=$this->uri->segment(2);
		$GLOBALS['funcion']=$this->uri->segment(3);

	}
	function alta_proveedor(){
		//Guardar el usuario
		$u= new Proveedor();
		$u->usuario_id=$GLOBALS['usuarioid'];
		$u->fecha_alta=date("Y-m-d");
		$related = $u->from_array($_POST);

		// save with the related objects
		if($u->save($related))
		{
			echo "<html> <script>alert(\"Se ha registrado los datos generales del proveedor.\"); window.location='".base_url()."index.php/".$GLOBALS['ruta']."/proveedores_c/formulario/list_proveedor';</script></html>";
		}
		else
		{
			show_error("".$u->error->string);
		}
	}
	function alta_compra_multiple(){
		//Guardar Datos Generales de la compra
		$u= new Pr_pedido_multiple();
		$u->usuario_id=$GLOBALS['usuarioid'];
		$u->empresas_id=$GLOBALS['empresa_id'];
		$usuario=$this->usuario->get_usuario($GLOBALS['usuarioid']);
		$fecha_entrega=explode(" ", $_POST['fecha_entrega']);
		$u->fecha_entrega="".$fecha_entrega[2]."-".$fecha_entrega[1]."-".$fecha_entrega[0] ;
		$u->fecha_pago=$u->fecha_entrega;
		unset($_POST['fecha_entrega']); unset($_POST['']); unset($_POST['fecha_pago']);
		$x=0; $efisicos=array();
		for($x;$x<100;$x++){

			if(isset($_POST['chk'.$x])){
				$efisicos[$x]=$_POST['chk'.$x];
				unset($_POST['chk'.$x]);
				//echo $efisicos[$x].",";

			}
		}
		//print_r($_POST);
		if(count($efisicos>0)){
			$u->espacios_fisicos=implode(',',$efisicos);
		} else {
			$u->espacios_fisicos='';
		}
		$related = $u->from_array($_POST);
		if($u->save($related))
		{
			echo form_hidden('id', "$u->id");
			echo '<button type="submit" id="boton1" style="display:none;">Actualizar Registro</button>';
			echo "<p id=\"response\">Datos Generales Guardados<br/><b>Capturar los detalles del pedido</b></p>";
		} else {
			show_error("".$u->error->string);
		}
	}

	function alta_compra_detalles_multiples(){
		//Campos comunes
		$varP=$_POST;
		$line=$this->uri->segment(4);
		$pr=array();
		$pr['pr_pedido_multiple_id']=$varP['pr_pedidos_id'.$line];
		$pr['cproductos_id']=$varP['producto'.$line];
		$pr['costo_unitario']=$varP['precio_u'.$line];
		$pr['tasa_impuesto']=$varP['iva'.$line];
		$x=1; $insert=array();
		foreach($varP as $row){
			if(isset($varP["cproducto_numero_id{$line}_{$x}"])){
				if($varP["cproducto_numero_id{$line}_{$x}"]>0 and $varP["numeracion{$line}_{$x}"]>0){
					//Insertar los renglones para cada numero
					$insert=$pr;
					$insert['cproducto_numero_id']=$varP["cproducto_numero_id{$line}_{$x}"];
					$insert['cantidad']=$varP["numeracion{$line}_{$x}"];
					$insert['costo_total']=$varP["numeracion{$line}_{$x}"]*$pr['costo_unitario'];
					$this->db->insert('pr_detalle_pedidos_multiples', $insert);
				}
				$x+=1;
			}
		}
		if($x>1){
			echo "<img src=\"".base_url()."images/ok.png\" width=\"20px\" title=\"Guardado\"/>";
		} else {
			echo '<img src="'.base_url().'images/stop.png" width="20px" title="Error al Guardar"/>';
		}
	}

	function verificar_pedido_compra_multiple(){

		//Obtener el id del pedido_comptra_multiple
		$id=$this->uri->segment(4);
		//Obtener el registro de la tabla
		$pedidom=$this->pr_pedido_multiple->get_pr_pedido_multiple($id);
		if($pedidom!=false){
			//Verificar que contenga espacios fisicos
			if(strlen($pedidom->espacios_fisicos)>0){
				//echo $pedidom->espacios_fisicos;
				//Se procede a generar la matriz con los espacios_fisicos
				$espacios=explode(',', $pedidom->espacios_fisicos);
				//Verificar que tenga conceptos
				$detalles=$this->db->query("select * from pr_detalle_pedidos_multiples where pr_pedido_multiple_id ='$id' order by id");
				if($detalles->num_rows()>0){
					//Generar matriz con datos constantes
					$pr=array();
					$pr['cpr_estatus_pedido_id']=1;
					$pr['corigen_pedido_id']=2;
					$pr['fecha_entrega']=$pedidom->fecha_entrega;
					$pr['fecha_pago']=$pedidom->fecha_pago;
					$pr['cproveedores_id']=$pedidom->cproveedores_id;
					$pr['cmarca_id']=$pedidom->cmarca_id;
					$pr['usuario_id']=$GLOBALS['usuarioid'];
					$pr['empresas_id']=$GLOBALS['empresa_id'];
					// 					$pr['fecha_alta']='CURRENT_TIMESTAMP';
					//print_r($espacios);
					//Generar la entrada para cada espacio_fisico
					foreach($espacios as $k=>$v){
						$pr_total=0;
						$pr['espacio_fisico_id']=$v;
						$this->db->insert('pr_pedidos', $pr);
						$pr_pedido_id=$this->db->insert_id();
						if($pr_pedido_id>0){
							$l=new Lote();
							$l->where('pr_pedido_id', $pr_pedido_id)->get();
							//$l->pr_pedido_id=$pr_pedido_id;
							$l->cestatus_lote_id=0;
							$l->fecha_recepcion=$pedidom->fecha_entrega;
							$l->espacio_fisico_inicial_id=$v;
							$l->usuario_id=$GLOBALS['usuarioid'];
							if($l->save()){
								//Dar de alta la relacion del pedido con el lote en la tabla lotes_pr_facturas
								$lf=new Lote_factura();
								$lf->pr_pedido_id=$pr_pedido_id;
								$lf->lote_id=$l->id;
								$lf->save();
							}
							//Ingresar los detalles
							$det['pr_pedidos_id']=$pr_pedido_id;
							foreach($detalles->result() as $row){
								$det['cproductos_id']=$row->cproductos_id;
								$det['cproducto_numero_id']=$row->cproducto_numero_id;
								$det['cantidad']=$row->cantidad;
								$det['costo_unitario']=$row->costo_unitario;
								$det['costo_total']=$row->costo_total;
								$det['tasa_impuesto']=$row->tasa_impuesto;
								$det['usuario_id']=$GLOBALS['usuarioid'];
								// 								$det['fecha_captura']='CURRENT_TIMESTAMP';
								$this->db->insert('pr_detalle_pedidos', $det);
								$pr_total+=$row->costo_total;
							}
						} else
							show_error("El numero de pedido no es valido Espacio Fisico = $v");
						//Actualizar el costo total de los proyectos
						$this->db->query("update pr_pedidos set monto_total='$pr_total' where id='$pr_pedido_id'");

					}
					//Mensaje final

					echo "<html> <script>alert(\"Se ha registrado el Pedido de Compra correctamente.\"); window.location='".base_url()."index.php/".$GLOBALS['ruta']."/compras_c/formulario/list_compras';</script></html>";

				} else
					show_error("El pedido no tiene conceptos relacionados intente de nuevo");

			} else
				show_error("El pedido no posee sucursales seleccionadas");

		} else
			show_error("El pedido no es valido o se ha cancelado");

	}

	function alta_compra(){
		//Guardar Datos Generales de la compra
		$u= new Pr_pedido();
		$u->get_by_id($_POST['id']);
		$u->usuario_id=$GLOBALS['usuarioid'];
		$u->empresas_id=$GLOBALS['empresa_id'];
		$usuario=$this->usuario->get_usuario($GLOBALS['usuarioid']);
		//Encargada de Tienda
		if ($usuario->puesto_id==6 or $usuario->puesto_id==7) {
			$u->corigen_pedido_id=3;
			$u->usuario_validador_id=$u->usuario_id;
		} else {
			$u->corigen_pedido_id=2;
		}
		$u->fecha_alta=date("Y-m-d H:i:s");
		$related = $u->from_array($_POST);
		//Adecuar las Fechas al formato YYYY/MM/DD
		$fecha_pago=explode(" ", $_POST['fecha_pago']);
		$fecha_entrega=explode(" ", $_POST['fecha_entrega']);
		$u->fecha_pago="".$fecha_pago[2]."-".$fecha_pago[1]."-".$fecha_pago[0];
		$u->fecha_entrega="".$fecha_entrega[2]."-".$fecha_entrega[1]."-".$fecha_entrega[0] ;
		// save with the related objects
		if($u->save($related)) {
			echo form_hidden('id', "$u->id");
			echo '<button type="submit" id="boton1" style="display:none;">Actualizar Registro</button>';
			echo "<p id=\"response\">Datos Generales Guardados<br/><b>Capturar los detalles del pedido</b></p>";
		} else
			show_error("".$u->error->string);
	}

	function edicion_compra_detalles(){
		//Guardar el usuario
		$varP=$_POST;
		unset($_POST);
		$line=$this->uri->segment(4);
		$pr= new Pr_detalle_pedido();
		$pr->pr_pedidos_id=$varP['pr_pedidos_id'.$line];
		$pr->cantidad=$varP['unidades'.$line];
		$pr->costo_unitario=$varP['precio_u'.$line];
		$pr->tasa_impuesto=$varP['iva'.$line];
		if(isset($varP['producto'.$line]) and $varP['producto'.$line]>0 ){
			$pr->cproductos_id=$varP['producto'.$line];
		}
		if(isset($varP['producto_numeracion'.$line])){
			$pr->cproducto_numero_id=$varP['producto_numeracion'.$line];
		}
		$pr->costo_total=($pr->cantidad * $pr->costo_unitario);
		if($varP['id'.$line]>0)
			$pr->id=$varP['id'.$line];
		if($pr->save()){
			echo form_hidden('id'.$line, "$pr->id"); echo form_hidden('pr_pedidos_id'.$line, "$pr->pr_pedidos_id"); echo "<a href=\"javascript:borrar_detalle('$pr->id', $line)\"><img src=\"".base_url()."images/trash1.png\" width=\"20px\" title=\"Borrar Detalle\"/></a><img src=\"".base_url()."images/ok.png\" width=\"20px\" title=\"Guardado\"/>";
			$pr->clear();
		} else {
			echo '<img src="'.base_url().'images/stop.png" width="20px" title="Error al Guardar"/>';
		}

		unset($varP);
	}

	function verificar_pedido_compra(){
		$id=$this->uri->segment(4);
		$d=new Pr_detalle_pedido();
		$d->select("sum(costo_total) as total");
		$d->where("pr_pedidos_id", $id);
		$d->where("estatus_general_id", 1);
		$d->get();
		$total=$d->total;
		if ($total>0){
			$p=new Pr_pedido();
			$p->get_by_id($id);
			$p->monto_total=$total;
			if($p->save()){
				$l=new Lote_factura();
				$l->where('pr_pedido_id', $p->id)->get();
				$l->pr_pedido_id=$p->id;
				$l->cestatus_lote_id=0;
				$l->fecha_recepcion=$p->fecha_entrega;
				$l->usuario_id=$GLOBALS['usuarioid'];
				$l->save();
				//Se valida que el pedido tiene registrados productos y se da por terminado enviando al listado de pedidos de compra
				echo "<html> <script>alert(\"Se ha registrado el Pedido de Compra correctamente.\"); window.location='".base_url()."index.php/".$GLOBALS['ruta']."/compras_c/formulario/list_compras/".$GLOBALS['ruta']."/menu';</script></html>";
			} else {
				/**No se encontro registro valido de productos dentro del pedido por lo cual se cancela*/
				$this->db->query("delete from pr_detalle_pedidos where pr_pedidos_id='$id'");
				$this->db->query("delete from pr_pedidos where id='$id'");
				echo "<html> <script>alert(\"Se ha eliminado el Pedido de Compra, intente de nuevo.\"); window.location='".base_url()."index.php/".$GLOBALS['ruta']."/compras_c/formulario/list_compras';</script></html>";
			}
		} else {
			/**No se encontro registro valido de productos dentro del pedido por lo cual se cancela*/
			$this->db->query("delete from pr_detalle_pedidos where pr_pedidos_id='$id'");
			$this->db->query("delete from pr_pedidos where id='$id'");
			echo "<html> <script>alert(\"Se ha eliminado el Pedido de Compra, intente de nuevo.\"); window.location='".base_url()."index.php/".$GLOBALS['ruta']."/compras_c/formulario/list_compras';</script></html>";
		}
	}
	// Oscar Functions
	function alta_pr_forma_pago(){
		//Guardar la Forma de Cobro
		$u= new Forma_pago();
		$related = $u->from_array($_POST);
		// save with the related objects
		if($u->save($related))
		{
			echo "<html> <script>alert(\"Se han registrado los datos de la Forma de Pago.\"); window.location='".base_url()."index.php/inicio/acceso/".$GLOBALS['ruta']."/menu';</script></html>";
		}
		else
		{
			show_error("".$u->error->string);
		}
	}

	function act_pr_forma_pago(){
		//Guardar la Forma de Cobro
		$u= new Forma_pago();
		$related = $u->from_array($_POST);
		// save with the related objects
		if($u->save($related))
		{
			echo "<html> <script>alert(\"Se han actualizado los datos de la Forma de Pago.\"); window.location='".base_url()."index.php/inicio/acceso/".$GLOBALS['ruta']."/menu';</script></html>";
		}
		else
		{
			show_error("".$u->error->string);
		}
	}

	function alta_tipo_pago(){
		//Guardar la Forma de Cobro
		$u= new Tipo_pago();
		$related = $u->from_array($_POST);
		// save with the related objects
		if($u->save($related))
		{
			echo "<html> <script>alert(\"Se han registrado los datos del Tipo de Pago.\"); window.location='".base_url()."index.php/inicio/acceso/".$GLOBALS['ruta']."/menu';</script></html>";
		}
		else
		{
			show_error("".$u->error->string);
		}
	}

	function act_tipo_pago(){
		//Guardar la Forma de Cobro
		$u= new Tipo_pago();
		$related = $u->from_array($_POST);
		// save with the related objects
		if($u->save($related))
		{
			echo "<html> <script>alert(\"Se han actualizado los datos del Tipo de Pago.\"); window.location='".base_url()."index.php/inicio/acceso/".$GLOBALS['ruta']."/menu';</script></html>";
		}
		else
		{
			show_error("".$u->error->string);
		}
	}

	function alta_pago(){
		//Guardar la Forma de Pago
		$u= new Pago();
		$u->usuario_id=$GLOBALS['usuarioid'];
		$related = $u->from_array($_POST);
		// save with the related objects
		if($u->save($related))
		{
			echo "<html> <script>alert(\"Se han registrado los datos del Pago.\"); window.location='".base_url()."index.php/inicio/acceso/".$GLOBALS['ruta']."/menu';</script></html>";
		}
		else
		{
			show_error("".$u->error->string);
		}
	}

	function act_pago(){
		//Guardar la Forma de Pago
		$u= new Pago();
		$u->usuario_id=$GLOBALS['usuarioid'];
		$related = $u->from_array($_POST);
		// save with the related objects
		if($u->save($related))
		{
			echo "<html> <script>alert(\"Se han actualizado los datos del Pago.\"); window.location='".base_url()."index.php/inicio/acceso/".$GLOBALS['ruta']."/menu';</script></html>";
		}
		else
		{
			show_error("".$u->error->string);
		}
	}


	function proveedores_marcas(){
		$prov_nuevos=$this->db->query("select p.* from cproveedores as p where usuario_id>0");
		foreach($prov_nuevos->result() as $row){
			$marcas=$this->db->query("select * from cmarcas_productos where proveedor_id=$row->id");
			$n=$marcas->num_rows();
			$this->db->query("update cproveedores set num_marcas='$n' where id=$row->id");
			if($n==0){
				$this->db->query("update cproveedores set estatus_general_id='2' where id=$row->id");
			}
		}
	}

	function corregir_pedidos(){
		$facturas=$this->db->query("select l.pr_factura_id, l.pr_pedido_id, cpr_estatus_pedido_id, fecha_alta from lotes_pr_facturas as l left join pr_facturas as f on f.id=pr_factura_id left join pr_pedidos as pe on pe.id=l.pr_pedido_id where cestatus_lote_id=0 and pr_factura_id=0 and pe.cpr_estatus_pedido_id=2 order by pr_pedido_id desc");
		echo $facturas->num_rows();
		foreach($facturas->result() as $row){
			echo "$row->pr_factura_id  ------> $row->cpr_estatus_pedido_id ------> $row->fecha_alta<br/>";
		}
	}

}
?>
