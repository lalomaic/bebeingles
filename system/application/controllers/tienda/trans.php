<?php
class Trans extends Controller {
	function Trans()
	{
		parent::Controller();
		if($this->session->userdata('logged_in') == FALSE){
			redirect(base_url()."index.php/inicio/logout");
		}
		$this->load->model("empresa");
		$this->load->model("espacio_fisico");
		$this->load->model("numero_letras");
		$this->load->model("grupo");
		$this->load->model("lote");
		$user_hash=$this->session->userdata('user_data');
		$row=$this->usuario->get_usuario_detalles($user_hash);
		$GLOBALS['usuarioid']=$row->id;
		$GLOBALS['empresaid']=$row->empresas_id;
		$GLOBALS['username']=$row->username;
		$GLOBALS['espacios_fisicos_id']=$row->espacio_fisico_id;
		$GLOBALS['ubicacion_tienda']=$row->espacio_fisico_id;
		$GLOBALS['empresa_id']=$row->empresas_id;
		$GLOBALS['ruta']=$this->uri->segment(1);
		$GLOBALS['controller']=$this->uri->segment(2);
		$GLOBALS['funcion']=$this->uri->segment(3);

	}

	function alta_transferencia(){
		$u= new Cl_pedido();
		$u->empresas_id=$GLOBALS['empresa_id'];
		$u->cclientes_id=$GLOBALS['empresa_id'];
		$u->fecha_alta=date("Y-m-d H:i:s");
		//Sincronizar con fecha de salida&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&
		$u->usuario_id=$GLOBALS['usuarioid'];
		$u->ccl_estatus_pedido_id=2;
		$u->corigen_pedido_id=3;
		$u->ccl_tipo_pedido_id=2;
		// save with the related objects
		if($_POST['id']>0){
			$u->get_by_id($_POST['id']);
			$insert_traspaso_salida=0;
		} else {
			$insert_traspaso_salida=1;
		}
		$u->trans_begin();
		if($u->save()) {
			$cl_pedido_id=$u->id;
			if($insert_traspaso_salida==1)  {
				//Dar de alta el traspaso
				$t= new Traspaso();
				$t->cl_pedido_id=$cl_pedido_id;
				$t->traspaso_estatus=1;
				$t->ubicacion_entrada_id=$GLOBALS['ubicacion_tienda'];
				$t->ubicacion_salida_id=$_POST['ubicacion_salida_id'];
				$t->trans_begin();
				$t->save();
				if ($t->trans_status() === FALSE) {
					// Transaction failed, rollback
					$t->trans_rollback();
					$u->trans_rollback();
					// Add error message
					echo form_hidden('id', "$u->id");
					echo '<button type="submit" style="display:none;" id="boton1">Intentar de nuevo</button><br/>';
					echo "El alta del traspaso no pudo registrarse, intente de nuevo";

				} else {
					// Transaction successful, commit
					$u->trans_commit();
					$t->trans_commit();
					echo form_hidden('id', "$u->id");
					echo '<p id="response">Capturar los detalles del pedido</p><button type="submit" id="boton1" style="display:none;" id="boton1">';

				}
			} else {
		  //Actualisar unicamente la tabla de los detalles
		  $u->trans_commit();
		  echo form_hidden('id', "$u->id");
		  echo '<p id="response">Capturar los detalles del pedido</p><button type="submit" id="boton1" style="display:none;" id="boton1">';
			}
	 } else {
	 	$u->trans_rollback();
	 	show_error("".$u->error->string);
	 }
	} //End function

	function alta_pedido_transferencia_detalles(){
		//Guardar el usuario
		$varP=$_POST;
		$line=$this->uri->segment(4);
		$pr= new Cl_detalle_pedido();
		$pr->cl_pedidos_id=$varP['cl_pedidos_id'.$line];
		$pr->cproductos_id=$varP['producto'.$line];
		$pr->cantidad=$varP['unidades'.$line];
		if(isset($varP['id'.$line])==true){
			$pr->id=$varP['id'.$line];
		}
		if($pr->save())
		{
			echo form_hidden('id'.$line, "$pr->id"); echo form_hidden('cl_pedidos_id'.$line, "$pr->cl_pedidos_id"); echo "<a href=\"javascript:borrar_detalle('$pr->id', $line)\"><img src=\"".base_url()."images/trash1.png\" width=\"20px\" title=\"Borrar Detalle\"/></a><img src=\"".base_url()."images/ok.png\" width=\"20px\" title=\"Guardado\"/>";
		}
		else
		{
			//show_error("".$u->error->string);
			echo '<img src="'.base_url().'images/stop.png" width="20px" title="Error al Guardar"/>';
		}
	}
	function verificar_pedido_traspaso(){
		$id=$this->uri->segment(4);
		$d=new Cl_detalle_pedido();
		$d->select("count(id) as total");
		$d->where("cl_pedidos_id", $id);
		$d->get();
		$total=$d->total;
		if ($total>0){
			//Se valida que el pedido tiene registrados productos y se da por terminado enviando al listado de pedidos de compra
			echo "<html> <script>alert(\"El pedido de Traspaso con el Id : $id se ha registrado correctamente.\"); window.location='".base_url()."index.php/".$GLOBALS['ruta']."/tienda_c/formulario/list_transferencias/';</script></html>";
		} else {
			/**No se encontro registro valido de productos dentro del pedido por lo cual se cancela*/
			$this->db->trans_start();
			$this->db->query("delete from cl_detalle_pedidos where cl_pedidos_id='$id'");
			$this->db->query("delete from cl_pedidos where id='$id'");
			$this->db->query("delete from traspasos where cl_pedido_id='$id'");
			$this->db->trans_complete();
			echo "<html> <script>alert(\"Se ha eliminado el Pedido de Traspaso por falta de conceptos, intente de nuevo.\"); window.location='".base_url()."index.php/".$GLOBALS['ruta']."/tienda_c/formulario/list_transferencias';</script></html>";
		}
	}

function alta_entrada_traspasos(){
    //Procesamiento del lote
    $datos=$_POST;
    //Obtener los detalles del traspaso
    $traspaso_id=$datos['traspaso_id'];
    $this->actilizar_traspaso($traspaso_id);
    
    $salidas = $this->lote->get_traspaso_salidas($traspaso_id);
     
    if($salidas != false){
        $x = false;
        foreach($salidas->all as $s){
            $eok =  $this->save_entrada_en_tienda($s, $traspaso_id);			
            if($eok){
                $x = true; 
            }
        }
        
        if($x == true){
         //       $this->db->query("update lotes_pr_facturas set cestatus_lote_id='3' where lote_id=$lote_id and pr_factura_id>0 and cestatus_lote_id='2'");
                echo "<html> <script>alert(\"Se ha registrado la entrada local del producto con exito\"); window.location='".base_url()."index.php/".$GLOBALS['ruta']."/tienda_c/formulario/list_transferencias/';</script></html>";
        } else {
                echo "<html> <script>alert(\"Ocurrio un error al registrar el alta de entrada local, Comuniquese con el Área de Soporte Técnico\"); window.location='".base_url()."index.php/".$GLOBALS['ruta']."/tienda_c/formulario/alta_entrada_local/alta_entrada_local/$traspaso_id';</script></html>";

        }
    } else
        show_error("El lote que intenta traspasar no existe o ya fue ejecutado");
}

function actilizar_traspaso($id){
    $traspaso = new Traspaso();
    $traspaso->get_by_id($id);
    $traspaso->cestatus_traspaso_id = 2;
    $traspaso->fecha_recibe = date("Y-m-d H:m:s");
    $traspaso->usuario_recibe_id = $GLOBALS['usuarioid'];
    $traspaso->save();
}

function save_entrada_en_tienda($sal, $traspaso_id){
    $e=new Entrada();
    $e->espacios_fisicos_id=$GLOBALS['espacios_fisicos_id'];
    $e->pr_facturas_id=0;     
    $e->cproveedores_id=$sal->cproveedores_id;
    $e->cproductos_id=$sal->cproductos_id;
    $e->cproducto_numero_id=$sal->cproducto_numero_id;
    $e->usuario_id=$GLOBALS['usuarioid'];
    $e->cantidad=$sal->cantidad;
    $e->costo_unitario=$sal->costo_unitario;
    $e->tasa_impuesto=$sal->tasa_impuesto;
    $e->costo_total=$sal->costo_total;
    $e->ctipo_entrada=2;
    $e->lote_id=$sal->lote_id;
    $e->existencia = $sal->existencia;
    $e->traspaso_id = $traspaso_id;
    return $e->save();
}

	function alta_arqueo(){
		//Guardar factura de cliente
		$u= new Arqueo();
		$u->usuario_id=$GLOBALS['usuarioid'];
		$u->espacio_fisico_id=$GLOBALS['ubicacion_tienda'];
		$u->fecha=date("Y-m-d");
		$u->hora=date("H:i:s");
		$u->estatus_general_id=1;
		$u->cestatus_arqueo_id=1;
		if($u->save()){
			echo form_hidden('id', "$u->id"); echo '<p></p><button type="submit" id="boton1" style="display:none;">Generales Factura</button><p>Capturar Detalles de las Existencias</p>';
	 } else {
	 	show_error("".$u->error->string);
	 }
	}

	function alta_arqueo_detalles(){
		//Guardar factura de cliente
		$linea=$this->uri->segment(4);
		$u= new Arqueo_detalle();
		$u->usuario_id=$GLOBALS['usuarioid'];
		$u->espacio_fisico_id=$GLOBALS['ubicacion_tienda'];
		$u->fecha=date("Y-m-d");
		$u->hora=date("H:i:s");
		$u->estatus_general_id=1;
		$u->arqueo_id=$_POST['arqueo_id'.$linea];
		$u->cproducto_id=$_POST['cproducto_id'.$linea];
		$u->cantidad_real=$_POST['cantidad_real'.$linea];
		$u->cantidad_sistema=$_POST['concepto'.$linea];
		$u->diferencia= abs($u->cantidad_real)-abs($u->cantidad_sistema);
		if($u->diferencia!=0 and (abs($u->cantidad_sistema)+abs($u->cantidad_real))>0)
			$u->porciento_error=200*$u->diferencia/(abs($u->cantidad_sistema + abs($u->cantidad_real)));
		else
			$u->porciento_error=0;
		if(isset($_POST['id'.$linea])==true){
			$u->id=$_POST['id'.$linea];
		}

		if($u->save()){
			echo form_hidden("concepto$linea", "$u->cantidad_sistema"); echo form_hidden("cproducto_id$linea", " $u->cproducto_id"); echo form_hidden('id'.$linea, "$u->id"); echo form_hidden('arqueo_id'.$linea, "$u->arqueo_id");echo form_hidden('content'.$linea, "$u->cantidad_sistema"); echo "<img src=\"".base_url()."images/ok.png\" width=\"20px\" title=\"Guardado\"/>";
	 } else {
	 	show_error("".$u->error->string);
	 }
	}
	function alta_cl_factura(){
		//Guardar factura de cliente
		$u= new Cl_factura();
		$u->usuario_id=$GLOBALS['usuarioid'];
		$u->empresas_id=$GLOBALS['empresa_id'];
		$u->espacios_fisicos_id=$GLOBALS['espacios_fisicos_id'];
		$u->fecha_captura=date("Y-m-d H:i:s");
		$u->estatus_general_id=1;
		$u->espacios_fisicos_id=$GLOBALS['espacios_fisicos_id'];
		$fecha=explode(" ", $_POST['fecha']);
		$u->fecha=$fecha[2]."/".$fecha[1]."/".$fecha[0];
		unset($_POST['fecha']);
		unset($_POST['tipo_entrada']);
		$related = $u->from_array($_POST);
		// save with the related objects
		if($u->save($related))
		{
			$this->db->query("update salidas set estatus_general_id=1, fecha='".$u->fecha.date(" H:i:s")."' where cl_facturas_id=$u->id");
			echo form_hidden('id', "$u->id");echo form_hidden('monto_total', "0"); echo form_hidden('iva_total', "0"); echo '<p></p><button type="submit" id="boton1" style="display:none;">Generales Factura</button>';
		}
		else
		{
			show_error("".$u->error->string);
		}
	}

	function factura_salida(){
		//Guardar factura_id en las salidas
		$varP=$_POST;
		$line=$this->uri->segment(4);
		$pr= new Salida();
		if(isset($varP['id_ubicacion_local'.$line])==true){
			$pr->where('id_ubicacion_local',$varP['id_ubicacion_local'.$line] );
			$pr->where('espacios_fisicos_id',  $GLOBALS['ubicacion_tienda']);
			$pr->get();
			$pr->cl_facturas_id=$varP['cl_factura_id'.$line];
			$pr->cclientes_id=$varP['cclientes_id'.$line];
			$pr->espacios_fisicos_id=$GLOBALS['ubicacion_tienda'];
			$pr->id_ubicacion_local=$varP['id_ubicacion_local'.$line];

			if($pr->save())
			{
				echo form_hidden('id'.$line, "$pr->id"); echo form_hidden('cl_factura_id'.$line, "$pr->cl_facturas_id");echo "<img src=\"".base_url()."images/ok.png\" width=\"20px\" title=\"Guardado\"/>";
			}
		}
		// save with the related objects
		else
		{
			show_error("".$pr->error->string);
		}
	}
	function verificar_factura(){
		$id=$this->uri->segment(4);
		$d=new Salida();
		$d->select("sum(costo_total) as total, sum(tasa_impuesto*cantidad*costo_unitario/(tasa_impuesto+100)) as iva");
		$d->where("cl_facturas_id", $id);
		$d->where("estatus_general_id", '1');
		$d->get();
		$total=$d->total;
		$iva=$d->iva;
		if ($total>0){
			$p=new Cl_factura();
			$p->get_by_id($id);
			$p->monto_total=$total;
			$p->iva_total=$iva;
			$p->monto_letras=strtoupper($this->numero_letras->convertir_a_letras($total));
			if($p->save()){
				//Se valida que el pedido tiene registrados productos y se da por terminado enviando al listado de pedidos de compra
				echo "<html> <script>alert(\"Se ha registrado la Factura del Cliente correctamente.\"); window.location='".base_url()."index.php/".$GLOBALS['ruta']."/tienda_c/formulario/list_cl_facturas_tienda/';</script></html>";
			} else {
				/**No se encontro registro valido de productos dentro del pedido por lo cual se cancela*/

			}
		} else {
	  /**No se encontro registro valido de productos dentro del pedido por lo cual se cancela*/

		}
	}


	function alta_salida_traspasos(){
		//Guardar factura de proveedor
		$varP=$_POST;
		$line=$this->uri->segment(4);
		$traspasos_id=$varP['traspaso_id'.$line];
		$pr= new Salida();
		$pr->usuario_id=$GLOBALS['usuarioid'];
		$pr->empresas_id=$GLOBALS['empresaid'];
		$pr->espacios_fisicos_id=$GLOBALS['espacios_fisicos_id'];
		$pr->cl_facturas_id=0;
		$pr->cproductos_id=$varP['producto'.$line];
		$pr->cantidad=$varP['unidades'.$line];
		$pr->ctipo_salida_id=2;
		$pr->estatus_general_id=1;
		$pr->cclientes_id=0;
		$pr->fecha=date("Y-m-d H:i:s");
		if(isset($varP['id'.$line])==true){
			$pr->id=$varP['id'.$line];
			$insert_traspaso_salida=0;
		} else {
			$insert_traspaso_salida=1;
		}

		// save with the related objects
		$pr->trans_begin();
		if($pr->save())
		{
			$cl= new Cl_detalle_pedido();
			$cl_pedido=$this->traspaso->get_traspaso($traspasos_id);
			if(isset($varP['cl_detalle_pedido_id'.$line])==false )
				$varP['cl_detalle_pedido_id'.$line]=0;

			if($cl_pedido!=false){
				$cl->get_by_id($varP['cl_detalle_pedido_id'.$line]);
				$cl->cproductos_id=$varP['producto'.$line];
				$cl->cantidad=$varP['unidades'.$line];
				$cl->cl_pedidos_id=$cl_pedido->cl_pedido_id;
				$cl->save();
				//echo "".$varP['cl_detalle_pedido_id'.$line];
			}
			if($insert_traspaso_salida==1){
				$ts= new Traspaso_salida();
				$ts->salidas_id=$pr->id;
				$ts->traspasos_id=$traspasos_id;
				$ts->trans_begin();
				$ts->save();

				if ($ts->trans_status() === FALSE) {
					// Transaction failed, rollback

					$ts->trans_rollback();

					$pr->trans_rollback();
					// Add error message
					echo form_hidden('traspasos_id', "$traspasos_id"); echo "Error: no guardado";
					//		echo '<button type="submit" style="display:none;" id="boton1">Intentar de nuevo</button><br/>';

				} else {
					$t=new Traspaso();
					$t->get_by_id($traspasos_id);
					$t->traspaso_estatus=1;
					$t->save();
					$cl_pedido_id=$t->cl_pedido_id;

					$p= new Cl_pedido();
					$p->get_by_id($cl_pedido_id);
					$p->ccl_estatus_pedido_id=3;
					$p->trans_begin();
					$p->save();
					if ($p->trans_status() === FALSE) {
						// Transaction failed, rollback
						$ts->trans_rollback();
						$pr->trans_rollback();
						// Add error message
						echo form_hidden('traspasos_id', "$traspasos_id"); echo "Error: no guardado";
						//		echo '<button type="submit" style="display:none;" id="boton1">Intentar de nuevo</button><br/>';

					} else {
						$pr->trans_commit();
						$p->trans_commit();
						$ts->trans_commit();
						echo form_hidden('id'.$line, "$pr->id"); echo form_hidden('traspaso_id'.$line, "$traspasos_id"); echo "<img src=\"".base_url()."images/ok.png\" width=\"20px\" title=\"Guardado\"/>";
					}
				}
			}
			else
			{
				show_error("".$pr->error->string);
			}
		}
	}

	function verificar_traspaso(){
		$traspaso_id=$this->uri->segment(4);
		if($traspaso_id>0){
			//Leer las salidas de la tabla traspasos_salidas
			$traspaso=$this->traspaso->get_traspaso($traspaso_id);
			$salidas=$this->traspaso_salida->get_salidas_traspaso($traspaso_id);
			$entradas=$this->traspaso_entrada->get_entradas_traspaso($traspaso_id);
			if($salidas==false)
		  show_error("No se encontraron conceptos con salida vÃ¡lida del Almacen de transferencia");
			//Verificar que para cada salida corresponden una entrada y en caso contrario generar la entrada necesaria
			$id=0;
			$t=1;
			foreach($salidas->all as $row){
				$s=new Salida();
				$s->get_by_id($row->salidas_id);

				if($entradas!=false){
					foreach($entradas->all as $entr){
						//Buscar que exista la entrada correspondiente a la salida
						if($s->cproductos_id==$entr->cproductos_id and $s->cantidad==$entr->cantidad)
							$id=$entr->id;
					}
				} else {
					$t=1;
				}
				//Verificar que exista la entrada
				$e=new Entrada();
				if($id>0){
					$e->get_by_id($id);
					$t=0;
				} else {
					$t=1;
				}
				$e->usuario_id=$GLOBALS['usuarioid'];
				$e->fecha=$s->fecha;
				$e->espacios_fisicos_id=$traspaso->ubicacion_entrada_id;
				$e->pr_facturas_id=0;
				$e->ctipo_entrada=2;
				$e->cproductos_id=$row->cproductos_id;
				$e->cantidad=$s->cantidad;
				$e->costo_unitario=0;
				$e->tasa_impuesto=0;
				$e->costo_total=0;
				$e->estatus_general_id=1;
				$e->cproveedores_id=0;
				$e->save();
				if($t==1){
					$te= new Traspaso_entrada();
					$te->entradas_id=$e->id;
					$te->traspasos_id=$traspaso_id;
					$te->save();
				}
				$id=0;
				$s->ctipo_salida_id=2;
				$s->espacios_fisicos_id=$traspaso->ubicacion_salida_id;
				$s->save();
			}

			echo "<html> <script>alert(\"Se ha registrado el Ingreso Local del Traspaso con id= $traspaso_id correctamente.\"); window.location='".base_url()."index.php/".$GLOBALS['ruta']."/tienda_c/formulario/list_transferencias/';</script></html>";

		} else {
			echo "<html> <script>alert(\"Ha ocurrido un problema con el Ingreso Local del Traspaso con id= $traspaso_id , favor de comunicarse con el Ãrea de Asistencia y Soporte TÃ©cnico.\"); window.location='".base_url()."index.php/".$GLOBALS['ruta']."/tienda_c/formulario/list_transferencias/';</script></html>";
		}

	}


	function editar_salida_traspasos(){
		//Guardar factura de proveedor
		$varP=$_POST;
		$line=$this->uri->segment(4);
		$traspasos_id=$varP['traspaso_id'.$line];
		$pr= new Salida();
		$pr->get_by_id($varP['id'.$line]);
		$pr->usuario_id=$GLOBALS['usuarioid'];
		$pr->empresas_id=$GLOBALS['empresaid'];
		$pr->espacios_fisicos_id=$GLOBALS['espacios_fisicos_id'];
		$pr->cproductos_id=$varP['producto'.$line];
		$pr->cantidad=$varP['unidades'.$line];
		$pr->fecha=date("Y-m-d H:i:s");
		$pr->trans_begin();
		if($pr->save()) {
			if($varP['id'.$line]==0){
				$ts= new Traspaso_salida();
				$ts->salidas_id=$pr->id;
				$ts->traspasos_id=$traspasos_id;
				$ts->trans_begin();
				$ts->save();
				if ($ts->trans_status() === FALSE) {
					// Transaction failed, rollback
					$ts->trans_rollback();
					$pr->trans_rollback();
					// Add error message
					echo form_hidden('traspasos_id', "$traspasos_id"); echo "Error: no guardado";
				} else {
					$pr->trans_commit();
					$ts->trans_commit();
				}
			}
			echo form_hidden('id'.$line, "$pr->id"); echo form_hidden('traspaso_id'.$line, "$traspasos_id"); echo "<a href=\"javascript:borrar_detalle($line)\"><img src=\"".base_url()."images/trash1.png\" width=\"20px\" title=\"Borrar Detalle\"/></a><img src=\"".base_url()."images/ok.png\" width=\"20px\" title=\"Guardado\"/>";
		} else {
			$pr->trans_rollback();
			show_error("Error al Editar el Traspaso, regresar a la página anterior.");
		}
	}

	function rectificar_fac(){
		$ubicacion=$this->uri->segment(4);
		//Obtener los id de facturas de los cortes que estan repetidas y eliminar sus entradas en la tabla salidas y salidas remision_final

		$clean=$this->db->query("select id, id_ubicacion_local from salidas where espacios_fisicos_id=$ubicacion and id_ubicacion_local is not null and id_ubicacion_local!=0 order by id_ubicacion_local");
		if($clean->num_rows()>0){
			$x=0;
			foreach($clean->result() as $row){
				$matrix[$x]['sid']=$row->id;
				$matrix[$x]['idu']=$row->id_ubicacion_local;
				$x+=1;
			}
		}
		for($r=0;$r<$x-1;$r++){
			if($matrix[$r]['idu']==$matrix[$r+1]['idu']){
				echo "Algo <br/>";
				if($matrix[$r]['sid']<$matrix[$r+1]['sid']){
					//Eliminar el registro con salida_id mayor
					// 		  $this->db->query("delete from salidas where id='".$matrix[$r+1]['sid']."'");
					$this->db->query("update salidas set estatus_general_id=2 where id='".$matrix[$r+1]['sid']."'");
					//$this->db->query("delete from salidas_remision where id_ubicacion_local='".$matrix[$r+1]['idu']."' and espacio_fisico_id=$ubicacion");
					echo "Eliminado $r <br/>";
				} else {
					// 		  $this->db->query("delete from salidas where id='".$matrix[$r]['sid']."'");
					$this->db->query("update salidas set estatus_general_id=2 where id='".$matrix[$r]['sid']."'");
					//$this->db->query("delete from salidas_remision where id_ubicacion_local='".$matrix[$r]['idu']."' and espacio_fisico_id=$ubicacion");
				}
			}
		}
		$fac_cortes=$this->db->query("select * from cortes_diarios where espacios_fisicos_id=$ubicacion order by fecha_corte");
		//print_r($fac_cortes);
		// Obtener los id de las remisiones comprendidas
		foreach($fac_cortes->result() as $row){
			$sql="select sr.id_ubicacion_local, sr.espacio_fisico_id, n.estatus_general_id, n.numero_remision from nota_remision as n left join salidas_remision as sr on sr.numero_remision=n.numero_remision where n.estatus_general_id>'0' and n.espacio_fisico_id='$ubicacion' and n.fecha='$row->fecha_corte' and n.numero_remision>=$row->remision_inicial and n.numero_remision<=$row->remision_final and sr.espacio_fisico_id='$ubicacion' ";
			$salidas_rem=$this->db->query($sql);
			//echo $sql."<br/>";
			$u=0;
			foreach($salidas_rem->result() as $row1){
				if($row->factura_id=='')
					$row->factura_id=0;
		  //Obtener los id de salidas de la tabla salidas_remisiones
		  if($row1->estatus_general_id==2){
		  	//Actualisar las salidas deshabilitadas
		  	$this->db->query("update salidas set estatus_general_id='2', cl_facturas_id=0 where id_ubicacion_local=$row1->id_ubicacion_local and espacios_fisicos_id=$ubicacion");
		  } else if($row1->estatus_general_id==1){
		  	//Actualisar con la factura sin afectar facturacion de otros clientes
		  	$sql2="update salidas set cl_facturas_id=$row->factura_id, corte_diario_id=$row->id, numero_remision='$row1->numero_remision', cclientes_id=1 where id_ubicacion_local=$row1->id_ubicacion_local and espacios_fisicos_id=$ubicacion and estatus_general_id=1 and ctipo_salida_id=1 and cclientes_id<=1	";
		  	$this->db->query($sql2);
		  	//echo $sql2."<br/>";
		  	$u+=1;
		  }
			}
			//Calcular el importe total de la factura
			if($row->factura_id=='')
				$row->factura_id=0;

			$sql3="select sum(costo_total) as total, sum(tasa_impuesto*cantidad*costo_unitario/(tasa_impuesto+100)) as iva from salidas where cl_facturas_id=$row->factura_id and estatus_general_id=1 and numero_remision is not null";
			$t=$this->db->query($sql3);
			foreach($t->result() as $res){
		  $total=$res->total;
		  $iva=$res->iva;
			}
			echo "<br>$sql3 - Total:".$total."<br/>";;
			$this->db->query("update cl_facturas set monto_total='$total', iva_total='$iva' where id=$row->factura_id");
		}
	}

	function alta_control_deposito(){
		$deposito=$_POST;
		$hora=$_POST['hora'].":".$_POST['min'];

		unset($deposito['hora']); unset($deposito['min']);
		$u=new Control_deposito();
		$fecha=explode(" ", $_POST['fecha_deposito']);
		$u->fecha_deposito=$fecha[2]."/".$fecha[1]."/".$fecha[0];
		$fecha=explode(" ", $_POST['fecha_venta']);
		$u->fecha_venta=$fecha[2]."/".$fecha[1]."/".$fecha[0];
		unset($deposito['fecha_deposito']); unset($deposito['fecha_venta']);
		$u->hora_deposito=$hora;
		$u->id_local=0;
		$u->nombre_banco='';
		$u->numero_cuenta='';
		$u->espacios_fisicos_id=$GLOBALS['espacios_fisicos_id'];
		$u->usuario_id=$GLOBALS['usuarioid'];
		$u->fecha_captura=date("Y-m-d H:i:s");
		$related = $u->from_array($deposito);
		if($u->save()){
			echo "<html> <script>alert(\"Se ha registrado el DepÃ³sito en la cuenta bancaria correctamente.\"); window.location='".base_url()."index.php/".$GLOBALS['ruta']."/tienda_c/formulario/list_control_depositos/';</script></html>";
		} else {
			echo "<html> <script>alert(\"Ha ocurrido un problema con registro del depÃ³sito, favor de intentar de nuevo.\"); window.location='".base_url()."index.php/".$GLOBALS['ruta']."/tienda_c/formulario/alta_control_deposito/';</script></html>";
		}
	}

	function alta_salida_traspaso_tienda(){
		$this->load->model('traspaso_tienda');
		$datos=$_POST;
		$espacio_fisico_recibe_id=$datos['espacio_fisico_recibe_id'];
		unset($datos['espacio_fisico_recibe_id']);
		if($espacio_fisico_recibe_id==0)
			show_error("Seleccione la tienda que va recibir la mercancia");

		$bandera=0;
		$r=0;
		foreach($datos as $row1){
			// Extraer el codigo del producto
			$codigo=strtoupper(trim($datos['codigo'.$r]));
			if(strlen($codigo)<13 and strlen($codigo)>0){
				$where=" clave_anterior='$codigo'";
				$lote_id=0;
				$bandera=1;
			} else if(strlen($codigo)>=13){
				$pn_id=substr($codigo, -6);
				$lote_id=substr($codigo,0,5);
				$where= " id='$pn_id'";
				$bandera=1;
			} else {
				$bandera=0;
			}

			if($bandera==1) {
				$producto=$this->db->query("select id, cproducto_id from cproductos_numeracion where $where ");
				$row=$producto->row();
				$compra=$this->db->query("select costo_unitario from entradas where cproducto_numero_id=$row->id and lote_id=$lote_id and ctipo_entrada=1 limit 1 ");
				if($compra->num_rows()>0){
					$c=$compra->row();
					$costo_unitario=$c->costo_unitario;
				} else {
					//En caso de no existir ese producto y lote buscar el la tabla cproductos
					$compra=$this->db->query("select c.precio1, m.porcentaje_utilidad from cproductos as c left join cmarcas_productos as m on m.id=c.cmarca_producto_id where c.id=$row->cproducto_id ");
					$c=$compra->row();
					$costo_unitario=round($c->precio1/((100+$c->porcentaje_utilidad)/100), 0);
				}

				//Dar salida de la ubicacion actual
				$s=new Salida();
				$s->espacios_fisicos_id=$GLOBALS['espacios_fisicos_id'];
				$s->cl_facturas_id=0;
				$s->cclientes_id=0;
				$s->cproductos_id=$row->cproducto_id;
				$s->cproducto_numero_id=$row->id;
				$s->id_ubicacion_local=0;
				$s->usuario_id=$GLOBALS['usuarioid'];
				$s->cantidad=1;
				$s->costo_unitario=$costo_unitario;
				$s->tasa_impuesto=0;
				$s->costo_total=$costo_unitario;
				$s->ctipo_salida_id=2;
				$s->lote_id=$lote_id;

				//Registrar traspasos_tiendas
				if($s->save()){
					$st=new Traspaso_tienda();
					$st->salida_id=$s->id;
					$st->espacio_fisico_id=$GLOBALS['espacios_fisicos_id'];
					$st->espacio_fisico_recibe_id=$espacio_fisico_recibe_id;
					$st->fecha_salida=date("Y-m-d");
					$st->save();
				}
			}
			$r+=1;
			unset($row);
		}
		echo "<html> <script>alert(\"Se ha terminado el proceso.\"); window.location='".base_url()."index.php/".$GLOBALS['ruta']."/tienda_c/formulario/list_salida_traspasos/';</script></html>";

	}

	function alta_entrada_traspaso_tienda(){
		$this->load->model('traspaso_tienda');
		$datos=$_POST;
		$_POST['lineas']=$this->uri->segment(4);
		$espacio_fisico_recibe_id=$GLOBALS['espacios_fisicos_id'];
		// 		limpiar datos
		//		echo $_POST['lineas']; exit;
		$r=0;
		for($r=0;$r<$_POST['lineas'];$r++){
			unset($datos['etiqueta_'.$r]);
			if(isset($datos['codigo'.$r])){
				if($datos['codigo'.$r]=='' or $datos['codigo'.$r]=='0')
					unset($datos['codigo'.$r]);
			}
		}
		$bandera=0;
		$r=0;
		for($r;$r<$_POST['lineas'];$r++){
			if(isset($datos['codigo'.$r])){
				// Extraer el codigo del producto
				$codigo=trim($datos['codigo'.$r]);
				if(strlen($codigo)<13 and strlen($codigo)>0){
					$where= " clave_anterior='$codigo'";
					$lote_id=0;
					$bandera=1;
				} else if(strlen($codigo)>=13){
					$pn_id=intval(substr($codigo, -6));
					$lote_id=intval(substr($codigo,0,5));
					$where= " id='$pn_id'";
					$bandera=1;
				} else {
					$bandera=0;
				}
				if($bandera==1) {
					$producto=$this->db->query("select id, cproducto_id from cproductos_numeracion where $where ");
					$row_p=$producto->row();
					$salida=$this->db->query("select s.*, tt.id as tid from traspasos_tiendas as tt left join salidas as s on s.id=tt.salida_id where s.lote_id=$lote_id and cproducto_numero_id=$row_p->id and s.estatus_general_id=1 and espacio_fisico_recibe_id=$espacio_fisico_recibe_id and entrada_id=0 limit 1  ");
					if($salida->num_rows()==1){
						$row=$salida->row();
						//Dar entrada de la ubicacion actual
						$s=new Entrada();
						$s->espacios_fisicos_id=$GLOBALS['espacios_fisicos_id'];
						$s->pr_facturas_id=0;
						$s->cproveedores_id=1;
						$s->cproductos_id=$row_p->cproducto_id;
						$s->cproducto_numero_id=$row_p->id;
						$s->id_ubicacion_local=0;
						$s->usuario_id=$GLOBALS['usuarioid'];
						$s->cantidad=1;
						$s->costo_unitario=$row->costo_unitario;
						$s->tasa_impuesto=0;
						$s->costo_total=$row->costo_unitario;
						$s->ctipo_entrada=2;
						$s->lote_id=$lote_id;

						//Registrar traspasos_tiendas
						if($s->save()){
							echo $r."<br/>";
							$st=new Traspaso_tienda();
							$st->get_by_id($row->tid);
							$st->entrada_id=$s->id;
							echo $row->tid."%%<br/>";
							$st->fecha_entrada=date("Y-m-d");
							$st->save();
						}
					}
				}
				unset($row);
			}
		}
		echo "<html> <script>alert(\"Se ha terminado el proceso.\"); window.location='".base_url()."index.php/".$GLOBALS['ruta']."/tienda_c/formulario/alta_entrada_tras_tienda/';</script></html>";
	}

	function rectificar_traspasos(){
		$traspasos=$this->db->query("select tt.*, s.cproductos_id, s.lote_id from traspasos_tiendas as tt left join salidas as s on s.id=tt.salida_id where espacio_fisico_id=12");
		if($traspasos->num_rows()>0){
			foreach($traspasos->result() as $tra) {
				$compra=$this->db->query("select costo_unitario from entradas where id=$tra->entrada_id and lote_id=$tra->lote_id and ctipo_entrada=1 limit 1 ");
				if($compra->num_rows()>0){
					$c=$compra->row();
					//echo "update salidas set costo_unitario='$c->costo_unitario', costo_total=(costo_unitario * cantidad) where id=$tra->salida_id";
					$this->db->query("update salidas set costo_unitario='$c->costo_unitario', costo_total=(costo_unitario * cantidad) where id=$tra->salida_id");
					if($tra->entrada_id>0) {
						//echo "update entradas set costo_unitario='$c->costo_unitario', costo_total=(costo_unitario * cantidad) where id=$tra->entrada_id";
						$this->db->query("update entradas set costo_unitario='$c->costo_unitario', costo_total=(costo_unitario * cantidad) where id=$tra->entrada_id");
					}
						
				} else {
					//En caso de no existir ese producto y lote buscar el la tabla cproductos
					$compra=$this->db->query("select c.precio1, m.porcentaje_utilidad from cproductos as c left join cmarcas_productos as m on m.id=c.cmarca_producto_id where c.id=$tra->cproductos_id ");
					$c=$compra->row();
					$costo_unitario=round($c->precio1/((100+$c->porcentaje_utilidad)/100), 0);
					//echo "update salidas set costo_unitario='$costo_unitario', costo_total=(costo_unitario * cantidad) where id=$tra->salida_id";
					$this->db->query("update salidas set costo_unitario='$costo_unitario', costo_total=(costo_unitario * cantidad) where id=$tra->salida_id");
					if($tra->entrada_id>0) {
						//echo "update entradas set costo_unitario='$costo_unitario', costo_total=(costo_unitario * cantidad) where id=$tra->entrada_id";
						$this->db->query("update entradas set costo_unitario='$costo_unitario', costo_total=(costo_unitario * cantidad) where id=$tra->entrada_id");
					}
				}
				unset($tra);
			}
		}

	}

}
?>
