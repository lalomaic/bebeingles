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
				$lote=new Lote();
				$lote->espacio_fisico_inicial_id=$p->espacio_fisico_id;
				$lote->fecha_recepcion=$p->fecha_entrega;
				$l->usuario_id=$GLOBALS['usuarioid'];
				$lote->save();
				$l=new Lote_factura();
				$l->where('pr_pedido_id', $p->id)->get();
				$l->pr_pedido_id=$p->id;
				$l->lote_id=$lote->id;
				$l->cestatus_lote_id=0;
				$l->fecha_recepcion=$p->fecha_entrega;
				$l->usuario_id=$GLOBALS['usuarioid'];
				$l->save();
				//Se valida que el pedido tiene registrados productos y se da por terminado enviando al listado de pedidos de compra
				echo "<html> <script>alert(\"Se ha registrado el Pedido de Compra correctamente.\"); window.location='".base_url()."index.php/".$GLOBALS['ruta']."/gerencia_c/formulario/list_pre_pedidos/';</script></html>";
			} else {
				/**No se encontro registro valido de productos dentro del pedido por lo cual se cancela*/
				$this->db->query("delete from pr_detalle_pedidos where pr_pedidos_id='$id'");
				$this->db->query("delete from pr_pedidos where id='$id'");
				echo "<html> <script>alert(\"Se ha eliminado el Pedido de Compra, intente de nuevo.\"); window.location='".base_url()."index.php/".$GLOBALS['ruta']."/gerencia_c/formulario/list_pre_pedidos';</script></html>";
			}
		} else {
			/**No se encontro registro valido de productos dentro del pedido por lo cual se cancela*/
			$this->db->query("delete from pr_detalle_pedidos where pr_pedidos_id='$id'");
			$this->db->query("delete from pr_pedidos where id='$id'");
			echo "<html> <script>alert(\"Se ha eliminado el Pedido de Compra, intente de nuevo.\"); window.location='".base_url()."index.php/".$GLOBALS['ruta']."/gerencia_c/formulario/list_pre_pedidos';</script></html>";
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

	function clonar_pedido(){
		$this->assetlibpro->add_css('default.css');
		$this->assetlibpro->add_js('jquery.js');
		$data['estatus']="Ocurrio un error al clonar los pedidos, revise el listado de Pre pedidos para evitar duplicar pedidos";
		$datos=$_POST;
		$efisico=array();
		for($x=0;$x<100;$x++){
			if(isset($_POST['chk'.$x])){
				$efisicos[$x]=$_POST['chk'.$x];
				unset($_POST['chk'.$x]);
			}
		}
		//Para cada espacio fisico generar el pedido
		$r=0;
		foreach($efisicos as $k=>$v){
			//Obtener el pedido Padre
			$pedido=new Pr_pedido();
			$pedido->get_by_id($datos['pedido_id']);
			unset($pedido->id);
			$pedido->espacio_fisico_id=$v;
				
			//Obtener los hijos del pedido
			if($pedido->save()){
				$hijos=$this->pr_detalle_pedido->get_pr_detalle_by_parent($datos['pedido_id']);
				if($hijos!=false){
					foreach($hijos->all as $row){
						$pr_hijo=new Pr_detalle_pedido();
						$pr_hijo->get_by_id($row->id);
						unset($pr_hijo->id);
						$pr_hijo->pr_pedidos_id=$pedido->id;
						$pr_hijo->save();
					}
				}
				//Crear el lote del pedido
				$lote=new Lote();
				$lote->espacio_fisico_inicial_id=$v;
				$lote->fecha_recepcion=$pedido->fecha_entrega;
				$l->usuario_id=$GLOBALS['usuarioid'];
				$lote->save();
				$l=new Lote_factura();
				$l->where('pr_pedido_id', $pedido->id)->get();
				$l->pr_pedido_id=$pedido->id;
				$l->lote_id=$lote->id;
				$l->cestatus_lote_id=0;
				$l->fecha_recepcion=$pedido->fecha_entrega;
				$l->usuario_id=$GLOBALS['usuarioid'];
				$l->save();
				$pedidos_mtrx[$r]['id']=$pedido->id;
				$pedidos_mtrx[$r]['espacio']=$this->espacio_fisico->get_espacios_f_tag($v);
				$r+=1;
			}
		}
		$data['estatus']="El pedido se ha clonado de manera exitosa";
		$data['pedido']=$this->pr_pedido->get_pr_pedido_detalles($pedido->id);
		$data['detalles']=$pedidos_mtrx;
		unset($hijos); unset($pedido); unset($lote); unset($l);
		//Se valida que el pedido tiene registrados productos y se da por terminado enviando al listado de pedidos de compra
		//echo "<html> <script>alert(\"Se ha registrado el Pedido de Compra correctamente.\"); window.location='".base_url()."index.php/".$GLOBALS['ruta']."/gerencia_c/formulario/list_pre_pedidos/';</script></html>";
		$this->load->view('ejecutivo/clonar_pedido_final', $data);

	}
	function pre_traspasos(){
		$this->load->model("pre_traspaso");
		$this->load->model("pre_traspaso_detalle");

		$datos=$_POST;
		$filas=$datos['filas'];
		$espacios_str=$datos['espacios'];
		$espacios_mtrx=explode(",",$espacios_str);
		for($x=1;$x<$filas;$x++){
			foreach($espacios_mtrx as $ev){
				if($datos["espacio_destino_{$x}_$ev"]>0 and $datos["existencia_{$x}_$ev"]>0 ){
					//Agregar un contador para saber cuantos detalles se estan enviando de una sucursal origen a las ubicaciones destino
					if(isset($espacio_cont[$ev][$datos["espacio_destino_{$x}_$ev"]]))
						$espacio_cont[$ev][$datos["espacio_destino_{$x}_$ev"]]+=1;
					else
						$espacio_cont[$ev][$datos["espacio_destino_{$x}_$ev"]]=1;
					$i=$espacio_cont[$ev][$datos["espacio_destino_{$x}_$ev"]];
					//En caso de haber seleccionado traspaso
					$cantidad=$datos["existencia_{$x}_$ev"];
					if($cantidad>0){
						//Si se tienen existencias positivas se almacenan en un arreglo
						$cproducto_id=$datos["cproducto_id$x"];
						$insert[$ev][$datos["espacio_destino_{$x}_$ev"]][$i]['cantidad']=$cantidad;
						$insert[$ev][$datos["espacio_destino_{$x}_$ev"]][$i]['cproducto_id']=$cproducto_id;
						//Obtener el precio de compra
						$compra=$this->db->query("select costo_unitario from entradas where cproductos_id=$cproducto_id and ctipo_entrada=1 order by fecha desc limit 1 ");
						if($compra->num_rows()>0){
							$c=$compra->row();
							$costo_unitario=$c->costo_unitario;
						} else {
							//En caso de no existir ese producto y lote buscar el la tabla cproductos
							$compra=$this->db->query("select c.precio1, m.porcentaje_utilidad from cproductos as c left join cmarcas_productos as m on m.id=c.cmarca_producto_id where c.id=$cproducto_id ");
							$c=$compra->row();
							$costo_unitario=round($c->precio1/((100+$c->porcentaje_utilidad)/100), 0);
						}
						$insert[$ev][$datos["espacio_destino_{$x}_$ev"]][$i]['precio_compra']=$costo_unitario;
						$i+=1;
					}
				}
			}
		}
		print_r($espacio_cont);
		if(count($insert)==0)
			show_error("No selecciono ningun producto para traspasar, intente de nuevo");
		//Recorrer la matriz para dar de alta los pre traspasos
		foreach($insert as $ev=>$ev_detalles){
			/** Ingreso al espacio fisico origen*/
			echo "Foreach 1b <br/>";

			foreach($espacios_mtrx as $esp=>$esp_detalles){
				echo "Foreach 2b <br/>";

				/** Ingreso a los espacios fisicos relacionados para encontrar los destinos*/
				if(isset($espacio_cont[$ev][$esp_detalles])){
					echo "If 1b <br/>";

					/** Valido que existan productos con el origen y destino existentes
					 Ademas doy de alta el pre_traspaso padre*/
					$pre_t=new Pre_traspaso();
					$pre_t->espacio_salida_id=$ev;
					$pre_t->espacio_entrada_id=$esp_detalles;
					$pre_t->fecha=date("Y-m-d");
					$pre_t->hora=date("H:i:s");
					$pre_t->usuario_id= $GLOBALS['usuarioid'];
					if($pre_t->save()){
						echo "$ev - $esp_detalles - ".$espacio_cont[$ev][$esp_detalles] ." <br/>";
						for($x=1;$x<=$espacio_cont[$ev][$esp_detalles];$x++){
							/** Dar de alta cada uno de los hijos relacionados con el pre traspaso padre */
							$pt_det=new Pre_traspaso_detalle();
							$pt_det->cproducto_id=$insert[$ev][$esp_detalles][$x]['cproducto_id'];
							$pt_det->cantidad=$insert[$ev][$esp_detalles][$x]['cantidad'];
							$pt_det->precio_compra=round($insert[$ev][$esp_detalles][$x]['precio_compra'],0);
							$pt_det->pre_traspaso_id=$pre_t->id;
							$pt_det->save(); $pt_det->clear();
							echo "-$x-<br/>";
						}
					}
				}
				unset($pre_t);
			}
		}
		echo "<html> <script>alert(\"Se ha finalizado la generación de los pre traspasos exitosamente.\"); window.top.location='".base_url()."index.php/ejecutivo/gerencia_c/formulario/list_pre_traspasos';</script></html>";
	}

}

?>
