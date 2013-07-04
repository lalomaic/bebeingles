<?php
class Trans extends Controller {
	function Trans()
	{
		parent::Controller();
		$this->load->model("empresa");
		$this->load->model("pr_factura");
		$this->load->model("cl_factura");
		$this->load->model("entrada");
		$this->load->model("almacen");
		$this->load->model("salida");
		$this->load->model("espacio_fisico");
		$this->load->model("producto");
		$this->load->model("arqueo");
		$this->load->model("arqueo_detalle");
		$this->load->model("stock_detalle");
		$user_hash=$this->session->userdata('user_data');
		$row=$this->usuario->get_usuario_detalles($user_hash);
		$GLOBALS['usuarioid']=$row->id;
		$GLOBALS['empresaid']=$row->empresas_id;
		$GLOBALS['espacios_fisicos_id']=$row->espacio_fisico_id;
		$GLOBALS['username']=$row->username;
		$GLOBALS['ruta']=$this->uri->segment(1);
		$GLOBALS['controller']=$this->uri->segment(2);
		$GLOBALS['funcion']=$this->uri->segment(3);

	}

	function ajustar_arqueo_detalles_previo(){
		//Guardar factura de cliente
		$linea=$this->uri->segment(4);
		$u= new Arqueo_detalle();
		$u->usuario_id=$GLOBALS['usuarioid'];
		$u->fecha=date("Y-m-d");
		$u->hora=date("H:i:s");
		$u->estatus_general_id=1;
		/*	  $u->arqueo_id=$_POST['arqueo_id'.$linea];*/
		$u->cantidad_real=$_POST['cantidad_real'.$linea];
		$u->cantidad_sistema=$_POST['cantidad_sistema'.$linea];
		$u->ctipo_ajuste_estatus_id=$_POST['ctipo_ajuste_detalle_id'.$linea];
		$u->diferencia= $u->cantidad_real-$u->cantidad_sistema;
		if($u->cantidad_real>0)
			$u->porciento_error=200*$u->diferencia/($u->cantidad_sistema + $u->cantidad_real);
		else
			$u->porciento_error=0;
		if(isset($_POST['id'.$linea])==true){
			$u->id=$_POST['id'.$linea];
		}

		if($u->save()){
			echo form_hidden('id'.$linea, "$u->id"); echo form_hidden('arqueo_id'.$linea, "$u->arqueo_id"); echo "<img src=\"".base_url()."images/ok.png\" width=\"20px\" title=\"Guardado\"/>";
	 } else {
	 	show_error("".$u->error->string);
	 }
	}
	function ajustar_arqueo_detalles_final(){
		//Guardar factura de cliente
		$linea=$this->uri->segment(4);
		$arqueo_id=$_POST['arqueo_id'.$linea];
		$arqueo_db=$this->arqueo->get_arqueo($arqueo_id);
		if($arqueo_db==false)
			show_error("Error 101");
		$tipo_ajuste=$_POST['ctipo_ajuste_detalle_id'.$linea];
		if($tipo_ajuste==1){
			//Ninguna accion
			echo form_hidden('id'.$linea, "".$_POST['id'.$linea]); echo form_hidden('arqueo_id'.$linea, "$arqueo_id"); echo "<img src=\"".base_url()."images/ok.png\" width=\"20px\" title=\"Guardado\"/>";
		} else if($tipo_ajuste==2){
			$m=new Entrada();
			$m->ctipo_entrada=5;
			$precio1=$this->producto->get_producto($_POST['cproducto_id'.$linea]);
			$m->costo_unitario=$precio1->precio1;
			$m->cantidad=abs($_POST['cantidad_real'.$linea]-$_POST['cantidad_sistema'.$linea]);
			$m->costo_total=$m->costo_unitario * $m->cantidad;
		} else if($tipo_ajuste==3){
			$m=new Entrada();
			$m->ctipo_entrada=7;
		} else if($tipo_ajuste==4){
			$m=new Salida();
			$m->ctipo_salida_id=3;
			$precio1=$this->producto->get_producto($_POST['cproducto_id'.$linea]);
			$m->cantidad=abs($_POST['cantidad_real'.$linea]-$_POST['cantidad_sistema'.$linea]);
			$m->costo_unitario=$precio1->precio1;
			$m->costo_total=$m->costo_unitario * $m->cantidad;
		} else if($tipo_ajuste==5){
			$m=new Salida();
			$m->ctipo_salida_id=4;
		}
		$m->espacios_fisicos_id=$arqueo_db->espacio_fisico_id;
		$m->usuario_id=$GLOBALS['usuarioid'];
		$m->fecha=$arqueo_db->fecha_final ." ". $arqueo_db->hora_final;
		$m->estatus_general_id=1;
		$m->cantidad=abs($_POST['cantidad_real'.$linea]-$_POST['cantidad_sistema'.$linea]);
		$m->cproductos_id=$_POST['cproducto_id'.$linea];
	        $m->cproducto_numero_id = $_POST['cproductos_numero_id'.$linea];
		 
		if($m->save()){
			$this->db->query("update arqueo_detalles set transaccion_id='$m->id', ctipo_ajuste_estatus_id='$tipo_ajuste' where id=".$_POST['id'.$linea]);
			echo "<img src=\"".base_url()."images/ok.png\" width=\"20px\" title=\"Guardado\"/>";
		} else {
			show_error("".$u->error->string);
		}
	}

	function verificar_ajuste(){
		$arqueo_id=$this->uri->segment(4);
		$a=new Arqueo();
		$a->get_by_id($arqueo_id);
		$a->cestatus_arqueo_id=2;
		$a->save();
		echo "<html> <script>alert(\"Se han ejecutado las acciones del Arqueo: $arqueo_id.\"); window.location='".base_url()."index.php/".$GLOBALS['ruta']."/arqueos_c/formulario/list_ajuste_inventario';</script></html>";
	}


	function rectificar_ajuste(){
		$id=$this->uri->segment(4);
		//Obtener Arqueos ejecutados y validos
		$arqueos=$this->arqueo->get_arqueo($id);
		if($arqueos==false)
			show_error("No hay arqueos actualmente");
		foreach($arqueos->all as $row){
			$arqueo_detalles=$this->arqueo_detalle->get_arqueo_detalles_by_parent($row->id);
			if($arqueo_detalles!=false){
				//Obtener los detalles de cada uno de los arqueos
				foreach($arqueo_detalles->all as $row1){
					//verificar para la fecha del arqueo la existencia de ese producto en el sistema
					$where=" where fecha<='$row->fecha_final $row->hora_final' and espacios_fisicos_id='$row->espacio_fisico_id'";
					$resultado=$this->almacen->existencias($row1->cproducto_id, $where);
					$existencia=$resultado['existencias'];
					//actualizar el valor de cantidad_sistema
					$diff=$row1->cantidad_real-$existencia;
					if($diff>0 or $diff<0){
						echo $row1->id."<br/>";
						$error=200*$diff/(abs($existencia) + abs($row1->cantidad_real));
					} else
						$error=0;
					$this->db->query("update arqueo_detalles set cantidad_sistema=$existencia, diferencia='$diff', porciento_error='$error', ctipo_ajuste_estatus_id=0 where id=$row1->id");
				}
			} else {
				show_error("El Ajuste no contenia detalles y se ha retenido");
			}
		}
	}

	function alta_stock(){
		$u= new Stock();
		$related = $u->from_array($_POST);
		$u->fecha_captura=date("Y-m-d H:i:s");
		$u->usuario_id=$GLOBALS['usuarioid'];
		if(isset($_POST['id']))
			$pag_siguiente=0;
		else
			$pag_siguiente=1;

		if($u->save($related)) {
			if($pag_siguiente==1)
				echo "<html> <script>alert(\"Se ha dado de alta la plantilla de stock: $u->nombre, ahora agregue los detalles de la misma.\"); window.location='".base_url()."index.php/supervision/arqueos_c/formulario/alta_stock/agregar_detalles_stock/$u->id';</script></html>";
			else
				echo "<html> <script>alert(\"Se ha actualizado la plantilla de stock: $u->nombre\"); window.location='".base_url()."index.php/supervision/arqueos_c/formulario/list_stock/';</script></html>";

		} else {
			show_error("Se produjo un error al intentar guardar la plantilla, intente de nuevo"); //.$u->error->string);
		}
	}
	function alta_detalles_stock(){
		//]Obtener los datos via post
		$stock_id=$_POST['stock_id'];
		unset($_POST['stock_id']);
		$datos=$_POST;
		for($x=0;$x<count($datos);$x++ ){
			if(isset($datos['producto'.$x])){
				if($datos['producto'.$x]>0){
					$s=new Stock_detalle();
					$s->stock_id=$stock_id;
					$s->cproducto_id=$datos['producto'.$x];
					$s->cantidad=$datos['cantidad'.$x];
					$s->fecha_captura=date("Y-m-d H:i:s");
					$s->usuario_id=$GLOBALS['usuarioid'];
					if($s->save()==false) {
						$this->db->query("delete from stock_detalles where stock_id='$stock_id'");
						show_error("Se produjo un error al intentar guardar los detalles de la plantilla, regrese con el boton ATRAS del navegador e intente de nuevo"); //.$u->error->string);
					}
				}
			}
		}
		echo "<html> <script>alert(\"Se han registrado los detalles de la plantilla de stock, ahora elija las sucursales que utilizarán esta plantilla.\"); window.location='".base_url()."index.php/supervision/arqueos_c/formulario/alta_stock/agregar_espacios/$stock_id';</script></html>";
	}
	function editar_detalles_stock(){
		//]Obtener los datos via post
		$stock_id=$_POST['stock_id'];
		unset($_POST['stock_id']);
		$datos=$_POST;
		for($x=0;$x<count($datos);$x++ ){
			if(isset($datos['producto'.$x])){
				if($datos['producto'.$x]>0){
					$s=new Stock_detalle();
					$s->get_by_id($datos['id'.$x]);
					$s->cantidad=$datos['cantidad'.$x];
					$s->fecha_captura=date("Y-m-d H:i:s");
					$s->usuario_id=$GLOBALS['usuarioid'];
					if($s->save()==false) {
						//						$this->db->query("delete from stock_detalles where stock_id='$stock_id'");
						show_error("Se produjo un error al intentar guardar los detalles de la plantilla, regrese con el boton ATRAS del navegador e intente de nuevo"); //.$u->error->string);
					}
				}
			}
		}
		echo "<html> <script>alert(\"Se han editado los detalles de la plantilla de stock.\"); window.location='".base_url()."index.php/supervision/arqueos_c/formulario/list_stock/';</script></html>";
	}

	function relacionar_espacios(){
		//]Obtener los datos via post
		$stock_id=$_POST['stock_id'];
		unset($_POST['stock_id']);
		$datos=$_POST;
		for($x=0;$x<count($datos);$x++ ){
			if(isset($datos['sucursal'.$x])){
				if($datos['sucursal'.$x]>0){
					$s=new Espacio_stock();
					$s->where('espacio_fisico_id', $datos['sucursal'.$x]);
					$s->get();
					$s->espacio_fisico_id=$datos['sucursal'.$x];
					$s->stock_id=$stock_id;
					$s->fecha_captura=date("Y-m-d H:i:s");
					$s->usuario_id=$GLOBALS['usuarioid'];
					if($s->save()==false) {
						$this->db->query("delete from espacios_stock where stock_id='$stock_id'");
						show_error("Se produjo un error al intentar guardar las relaciones de la plantilla con las sucursales, regrese con el boton ATRAS del navegador e intente de nuevo"); //.$u->error->string);
					}
				}
			}
		}
		echo "<html> <script>alert(\"Se han asociado las sucursales a la plantilla de stock finalmente.\"); window.location='".base_url()."index.php/supervision/arqueos_c/formulario/list_stock';</script></html>";
	}
	function edicion_relacionar_espacios(){
		//]Obtener los datos via post
		$stock_id=$_POST['stock_id'];
		unset($_POST['stock_id']);
		$datos=$_POST;
		//print_r($datos);
		$n=count($datos);
		if($n==0)
			show_error("Seleccione al menos un espacio físico");
		$t_espacios=$this->espacio_fisico->get_total_espacios_by_empresa_id($GLOBALS['empresaid']);
		for($x=0;$x<=$t_espacios;$x++ ){
			//echo $n;
			if(isset($datos['sucursal'.$x])){
				if($datos['sucursal'.$x]>0){
					$s=new Espacio_stock();
					$s->where('stock_id', $stock_id);
					$s->where('espacio_fisico_id', $datos['sucursal'.$x]);
					$s->get();
					$s->espacio_fisico_id=$datos['sucursal'.$x];
					$s->stock_id=$stock_id;
					$s->fecha_captura=date("Y-m-d H:i:s");
					$s->usuario_id=$GLOBALS['usuarioid'];
					if($s->save()==false) {
						$this->db->query("delete from espacios_stock where stock_id='$stock_id'");
						show_error("Se produjo un error al intentar guardar las relaciones de la plantilla con las sucursales, regrese con el boton ATRAS del navegador e intente de nuevo"); //.$u->error->string);
					} else {
						//Se comenta esta parte para contar con multiples plantillas de stock por espacio fisico
						//Borrar la aparición de ese espacio fisico en otras plantillas
						//$this->db->query("delete from espacios_stock where stock_id!='$stock_id' and espacio_fisico_id=".$datos['sucursal'.$x]);
					}
				}
			}
		}
		echo "<html> <script>alert(\"Se han asociado las sucursales a la plantilla de stock actual, deshabilitando esas sucursales en otras plantillas.\"); window.location='".base_url()."index.php/supervision/arqueos_c/formulario/list_stock';</script></html>";
	}

	function alta_ajuste_inventario(){
		$u= new Arqueo();
		$related = $u->from_array($_POST);
		$u->fecha_captura=date("Y-m-d");
		$u->usuario_id=$GLOBALS['usuarioid'];
		$u->cestatus_arqueo_id=1;
		$u->estatus_general_id=1;
		if($u->save($related))
			echo "<input type='hidden' name='id' id='id' value='$u->id'>Ajuste registrado id: $u->id";
		else
			echo "<h2>Error al guardar el alta de inventario</h2>";
	}
/*
	function ejecutar_inventario(){
		$this->relacionar_hijos_papas('entradas');
		$this->relacionar_hijos_papas('salidas');

		$this->load->model('producto_numeracion');
		$id=$this->uri->segment('4');
		if($id>0){
			$arqueo=$this->arqueo->get_by_id($id);
			if($arqueo->cestatus_arqueo_id==1){
				show_error("No se puede ejecutar el inventario debido a que no se a subido ningun archivo con los datos requeridos");
			}
			if($arqueo->cestatus_arqueo_id==3){
				//show_error("El inventario ya se ha ejecutado previamente, favor de revisarlo");
			}
			//Generar la vista temporal para ese espacio fisico y con las fechas finales del arqueo
			$this->db->query("drop view arqueo_existencia");
			$this->db->query("create view arqueo_existencia as select cproducto_numero_id, sum(cantidad), lote_id from entradas where espacios_fisicos_id='$arqueo->espacio_fisico_id' and estatus_general_id=1 and fecha<='$arqueo->fecha_final $arqueo->hora_final' group by cproducto_numero_id, lote_id union select cproducto_numero_id, (sum(cantidad)*(-1)), lote_id from salidas where espacios_fisicos_id=$arqueo->espacio_fisico_id and estatus_general_id=1 and fecha<='$arqueo->fecha_final $arqueo->hora_final' group by cproducto_numero_id, lote_id order by cproducto_numero_id");
			//Obtener el archivo
			$path="/var/www/pavelerp/$arqueo->ruta_archivo";
			echo date("H:i:s")."Inicio<br/>";
			$archivo_csv=file($path);
			echo "<br/>ARchivo cargado: ".date("H:i:s");
			$total_archivo=count($archivo_csv);
			$csv_inicial=1; $csv_longitud=$total_archivo;
			$pasos=ceil($total_archivo/$csv_longitud);
			echo "Numero de pasos: $pasos, Num de registros: $total_archivo<br/>";
			//unset($archivo_csv);
			//Crear mecanismo para crear sentencia INSERT MULTIPLE
			$sql_string="insert into arqueo_detalles (arqueo_id, usuario_id, cproductos_numero_id, cproducto_id, cantidad_real, cantidad_sistema, diferencia, ctipo_ajuste_detalle_id, costo_unitario, lote_id) values ";

			$existe=$this->db->query("select distinct(cproducto_numero_id, lote_id), cproducto_numero_id, lote_id, sum(sum) as total from arqueo_existencia group by cproducto_numero_id, lote_id order by cproducto_numero_id, lote_id");
			foreach($existe->result() as $lineas){
				if(isset($exist_mtrx[$lineas->cproducto_numero_id][$lineas->lote_id]['id'])==false) {
					$exist_mtrx[$lineas->cproducto_numero_id][$lineas->lote_id]['id']=$lineas->cproducto_numero_id;
					$exist_mtrx[$lineas->cproducto_numero_id][$lineas->lote_id]['lote_id']=$lineas->lote_id;
					$exist_mtrx[$lineas->cproducto_numero_id][$lineas->lote_id]['existencia']=$lineas->total;

				} else {
					$exist_mtrx[$lineas->cproducto_numero_id][$lineas->lote_id]['existencia']+=$lineas->total;
				}
				unset($lineas);
			}
			unset($existe);
			for($xy=1;$xy<=$pasos;$xy++) {
				//echo $csv_inicial."-Inicial<br/>";
				if(isset($archivo_csv)==false){
					$archivo_csv=file($path);
				}
				$archivo_csv_loops=array_slice($archivo_csv, $csv_inicial, $csv_longitud);
				//unset($archivo_csv);
				//print_r($archivo_csv_loops);
				foreach($archivo_csv_loops as $r=>$row) {
					//echo "<br/>Paso".$r." ".date("H:i:s")." ";
					$fila=explode(',', $row);
					//print_r($fila)."<br/>";
					if(isset($fila[2]) and isset($fila[3])) {
						if(trim($fila[1])>0)
							$lote_id=trim($fila[1]);
						else
							$lote_id=0;
						$pn_id=trim($fila[0]);
						$cantidad_real=trim($fila[3]);

						if(isset($exist_mtrx[$pn_id][$lote_id]['id'])==true)
							$existencia['existencias']=$exist_mtrx[$pn_id][$lote_id]['existencia'];
						else
							$existencia['existencias']=0;
						unset($r1);
						if($cantidad_real >0 or $existencia['existencias']!=0) {
							$cantidad_sistema=$existencia['existencias'];
							// 						Obtener costo de venta
							$pn=$this->producto_numeracion->select('cproducto_id')->where('id', $pn_id)->get();
							$prod=$this->producto->select('precio1')->where('id',$pn->cproducto_id)->get();
							$costo_unitario=$prod->precio1;

							$diferencia=$cantidad_sistema-$cantidad_real;
							if($diferencia==0)
								$ctipo_ajuste_detalle_id=1;
							else if($diferencia>0)
								$ctipo_ajuste_detalle_id=4;
							else if($diferencia<0)
								$ctipo_ajuste_detalle_id=3;
							$value="( $id, {$GLOBALS['usuarioid']}, ".trim($fila[0]).", $pn->cproducto_id, $cantidad_real, $cantidad_sistema, '$diferencia', $ctipo_ajuste_detalle_id, '$costo_unitario', $lote_id )";
							unset($pn); unset($prod);
							$this->db->query($sql_string." ".$value);
							unset($fila);
						}
					}
					unset($row); //unset($existencia);
					unset($archivo_csv_loops[$r]);
				}
				unset($archivo_csv_loops);
				$csv_inicial=(($csv_longitud-1)*$xy);
			}
			unset($archivo_csv); unset($exist_mtrx);
			//Cargar funcion para ejecutar inventario
			$this->cuadrar_inventario($id);
		}
	}*/
	function cuadrar_inventario($id){
		//echo $id;
		//Función para cuadrar inventario en base al arqueo con archivo migrado a la base de datos
		$arqueo=new Arqueo();
		$arqueo->get_by_id($id);
		//Generar la vista temporal para ese espacio fisico y con las fechas finales del arqueo
		$size=$this->arqueo_detalle->get_arqueo_total_xcuadrar($id);
		if($size>0) {
			$arqueo_detalles=$this->arqueo_detalle->get_arqueo_detalles_by_padre($id);
			foreach($arqueo_detalles->all as $row){
				if($row->transaccion_id==0 ){
					if($row->ctipo_ajuste_detalle_id==3 or $row->ctipo_ajuste_detalle_id==4){
						if($row->ctipo_ajuste_detalle_id==3 ) {
							//Entrada sin saldo sistema < real = Diff
							$m=new Entrada();
							$m->ctipo_entrada=7;
							$m->pr_facturas_id=0;
							$m->cproveedores_id=1;

						} else if($row->ctipo_ajuste_detalle_id==4){
							//Salida con saldo sistema > real = -Diff
							$m=new Salida();
							$m->ctipo_salida_id=3;
							$m->cl_facturas_id=0;
							$m->cclientes_id=0;

						}
						$m->lote_id=$row->lote_id;
						$m->espacios_fisicos_id=$arqueo->espacio_fisico_id;
						$m->usuario_id=$GLOBALS['usuarioid'];
						$m->fecha=$arqueo->fecha_final ." " .$arqueo->hora_final;
						$m->estatus_general_id=1;
						$m->cantidad=abs($row->diferencia);
						//Obtener el precio de compra del cproducto_id y el lote
						$p1=$this->db->query("select precio1 as precio_compra from cproductos as p  where p.id=$row->cproducto_id");
						$precio1=$p1->row();

						// 							if($row->lote_id==0 or $row->lote_id==''){
						// 								//Calcularlo en base al porcentaje de ganancia de la tabla cmarcas_productos
						// // 								$p1=$this->db->query("select (precio1*(100-porcentaje_utilidad)/100) as precio_compra from cproductos as p left join cmarcas_productos as m on m.id=p.cmarca_producto_id where p.id=$row->cproducto_id");
						// 								$p1=$this->db->query("select precio1 as precio_compra from cproductos as p  where p.id=$row->cproducto_id");
						// 								$precio1=$p1->row();
						// 							} else {
						// 								//Calcularlo en base al costo_unitario de la entrada del lote producto_id y espacio fisico
						// 								$p1=$this->db->query("select costo_unitario as precio_compra from entradas  where id=$row->cproducto_id and lote_id=$row->lote_id and espacios_fisicos_id=$arqueo->espacio_fisico_id and estatus_general_id=1 and ctipo_entrada='2' order by fecha desc limit 1");
						// 								if($p1->num_rows()==1)
							// 									$precio1=$p1->row();
							// 								else {
							// 									$p1=$this->db->query("select (precio1*(100-porcentaje_utilidad)/100) as precio_compra from cproductos as p left join cmarcas_productos as m on m.id=p.cmarca_producto_id where p.id=$row->cproducto_id");
							// 									$precio1=$p1->row();
							// 								}
							// 							}
						if(isset($precio1->precio_compra)==false)
							show_error($row->lote_id."&&");
						$m->costo_unitario=$precio1->precio_compra;
						$m->costo_total=$m->costo_unitario * $m->cantidad;
						$m->cproductos_id=$row->cproducto_id;
						$m->cproducto_numero_id=$row->cproductos_numero_id;
						if($m->save()){
							//Quitar de la matriz el dato correspondiente

							$this->db->query("update arqueo_detalles set transaccion_id=$m->id where id=$row->llave");
							//echo "$row->ctipo_ajuste_detalle_id -> $m->id";
						}
				}

			}
		}
		unset($row);
	}
	/**Mandar a la funcion que revisa productos que no estan en el arqueo y que tienen movimientos no cuadrados **/
	$this->faltantes_existencias($arqueo->id);
}

function faltantes_existencias($id){
	$this->load->model("producto_numeracion");
	$arqueo=new Arqueo();
	$arqueo->get_by_id($id);
	$arqueo_detalles=$this->arqueo_detalle->get_arqueo_detalles_cuadrados($id);
	$this->db->query("drop view arqueo_existencia");
	$this->db->query("create view arqueo_existencia as select cproducto_numero_id, sum(cantidad), lote_id from entradas where espacios_fisicos_id='$arqueo->espacio_fisico_id' and estatus_general_id=1 and fecha<='$arqueo->fecha_final $arqueo->hora_final' group by cproducto_numero_id, lote_id union select cproducto_numero_id, (sum(cantidad)*(-1)), lote_id from salidas where espacios_fisicos_id=$arqueo->espacio_fisico_id and estatus_general_id=1 and fecha<='$arqueo->fecha_final $arqueo->hora_final' group by cproducto_numero_id, lote_id order by cproducto_numero_id");

	$existe=$this->db->query("select distinct(cproducto_numero_id, lote_id), cproducto_numero_id, lote_id, sum(sum) as total from arqueo_existencia group by cproducto_numero_id, lote_id order by cproducto_numero_id, lote_id");
	foreach($existe->result() as $lineas){
		if(isset($exist_mtrx[$lineas->cproducto_numero_id][$lineas->lote_id]['id'])==false) {
			$exist_mtrx[$lineas->cproducto_numero_id][$lineas->lote_id]['existencia']=$lineas->total;
		}
	}
	unset($existe);
	if($arqueo_detalles!=false){
		foreach($arqueo_detalles->all as $row){
			unset($exist_mtrx[$row->cproductos_numero_id][$row->lote_id]);
			if(count($exist_mtrx[$row->cproductos_numero_id])==0)
				unset($exist_mtrx[$row->cproductos_numero_id]);
			unset($row);
		}
		unset($arqueo_detalles);
	}
	foreach($exist_mtrx as $prod=>$prod1){
		foreach($prod1 as $lote=>$lote1 ){
			if($lote1['existencia']==0)
				unset($exist_mtrx[$prod][$lote]);
		}
		if(count($exist_mtrx[$prod])==0)
			unset($exist_mtrx[$prod]);
	}

	$faltantes=count($exist_mtrx);
	if($faltantes>0) {
		$sql_string="insert into arqueo_detalles (arqueo_id, usuario_id, cproductos_numero_id, cproducto_id, cantidad_real, cantidad_sistema, diferencia, ctipo_ajuste_detalle_id, costo_unitario, lote_id) values ";
		foreach($exist_mtrx as $prod=>$prod1){
			$pn=$this->producto_numeracion->select('id, cproducto_id')->where('id', $prod)->get();
			$prod2=$this->producto->select('precio1')->where('id',$pn->cproducto_id)->get();
			foreach($prod1 as $lote=>$lote1 ){
				//echo "$pn->id&";
				$cantidad_real=0;
				$lote_id=$lote;
				$cantidad_sistema=$lote1['existencia'];
				$pn=$this->producto_numeracion->select('id,cproducto_id')->where('id', $pn->id)->get();
				$prod=$this->producto->select('precio1')->where('id',$pn->cproducto_id)->get();
				$costo_unitario=$prod->precio1;
				if($diferencia==0)
					$ctipo_ajuste_detalle_id=1;
				else if($diferencia>0)
					$ctipo_ajuste_detalle_id=4;
				else if($diferencia<0)
					$ctipo_ajuste_detalle_id=3;
				/*				$ad->diferencia=$diferencia;
					$ad->ctipo_ajuste_detalle_id=$ctipo_ajuste_detalle_id;
				$ad->costo_unitario=$prod2->precio1;
				$ad->save(); $ad->clear();
				*/				//echo $pn->id."No en Archivo CSV<br/>";
				$diferencia=$cantidad_sistema-$cantidad_real;
				if($diferencia==0)
					$ctipo_ajuste_detalle_id=1;
				else if($diferencia>0)
					$ctipo_ajuste_detalle_id=4;
				else if($diferencia<0)
					$ctipo_ajuste_detalle_id=3;
				//Obtener costo de venta
				$costo_unitario=$prod2->precio1;
				$value="( $id, {$GLOBALS['usuarioid']}, $pn->id, $pn->cproducto_id, $cantidad_real, $cantidad_sistema, '$diferencia', $ctipo_ajuste_detalle_id, '$costo_unitario', $lote_id )";
				$this->db->query($sql_string." ".$value);
			}
			unset($pn); unset($prod1);
		}
		unset($exist_mtrx);
		$this->cuadrar_inventario($id);
	} else {
		//Cuando esta todo cuadrado reenviar
		$arqueo->cestatus_arqueo_id=3;
		$arqueo->save();
		echo "<html> <script>alert(\"Se ha cuadrado el arqueo $arqueo->id.\"); window.location='".base_url()."index.php/supervision/arqueos_c/formulario/list_ajuste_inventario';</script></html>";
		//show_error("El arqueo ya ha sido cuadrado o bien no se han cargado los detalles del mismo, favor de contactar a los Administradores del Sistema");
	}
		

}

function imprimir_rep_cuadratura(){
	//Funcion para imprimir en pantalla errores en el arqueo ejecutado
	$id=$this->uri->segment(4);
	$arqueo=new Arqueo();
	$arqueo->get_by_id($id);
	$arqueo_detalles=$this->arqueo_detalle->get_arqueo_detalles_cuadrados($id);
	$this->db->query("drop view arqueo_existencia");
	$this->db->query("create view arqueo_existencia as select cproducto_numero_id, sum(cantidad), lote_id from entradas where espacios_fisicos_id='$arqueo->espacio_fisico_id' and estatus_general_id=1 and fecha<='$arqueo->fecha_final $arqueo->hora_final' group by cproducto_numero_id, lote_id union select cproducto_numero_id, (sum(cantidad)*(-1)), lote_id from salidas where espacios_fisicos_id=$arqueo->espacio_fisico_id and estatus_general_id=1 and fecha<='$arqueo->fecha_final $arqueo->hora_final' group by cproducto_numero_id, lote_id order by cproducto_numero_id");

	$existe=$this->db->query("select distinct(cproducto_numero_id, lote_id), cproducto_numero_id, lote_id, sum(sum) as total from arqueo_existencia group by cproducto_numero_id, lote_id order by cproducto_numero_id, lote_id");
	foreach($existe->result() as $lineas){
		if(isset($exist_mtrx[$lineas->cproducto_numero_id][$lineas->lote_id]['id'])==false) {
			$exist_mtrx[$lineas->cproducto_numero_id][$lineas->lote_id]['existencia']=$lineas->total;
		} else
			$exist_mtrx[$lineas->cproducto_numero_id][$lineas->lote_id]['existencia']=0;
	}
	//Necesito Obtener de cada detalle la Existencia Real, Existencia Sistema(Calculada al cuadrar), Calcular la Existencia Sistema Posterior
	$r=0;
	echo "<html><body>";
	foreach($arqueo_detalles->all as $row){
		//Obtener la existencia
		if($exist_mtrx[$row->cproductos_numero_id][$row->lote_id]['existencia']!=$row->cantidad_real) {

			echo "<div>Id=$row->llave, Producto_id=$row->cproducto_id, Pn_id=$row->cproductos_numero_id, Lote_id=$row->lote_id, Ctipo_ajuste=$row->ctipo_ajuste_detalle_id, Ex_r=$row->cantidad_real, Ex_Sis_pre=$row->cantidad_sistema, ";
				
			echo "Exis=".round($exist_mtrx[$row->cproductos_numero_id][$row->lote_id]['existencia'])."</div>";
		}
		//unset($exist_mtrx[$row->cproductos_numero_id][$row->lote_id]);
		if(count($exist_mtrx[$row->cproductos_numero_id])==0)
			unset($exist_mtrx[$row->cproductos_numero_id]);
		unset($row);
		$r+=1;
		/*			if($r==20000)
			exit();*/
	}
	foreach($exist_mtrx as $prod=>$prod1){
		foreach($prod1 as $lote=>$lote1 ){
			if($lote1['existencia']==0)
				unset($exist_mtrx[$prod][$lote]);

		}
		if(count($exist_mtrx[$prod])==0)
			unset($exist_mtrx[$prod]);
	}
	echo count($exist_mtrx);
	//print_r($exist_mtrx);
	echo "</body></html>";
}

function generar_etiquetas_csv(){
	//Obtener el archivo
	$this->load->plugin('barcode');
	$path_file="/var/www/pavelerp/archivo.csv";
	$path="/var/www/pavelerp/tmp/";
	$archivo_csv=file($path_file);
	$x=1;
	foreach($archivo_csv as $r=>$row){
		$fila=explode(',', $row);
		if($fila[0]!=''){
			barcode_create("{$fila[0]}","code128","jpeg", 'cb_'.$fila[0], $path);
			$codigos[$x]['codigo']=$fila[0];
			$codigos[$x]['ruta']=$path."cb_".$fila[0].".jpeg";
			$codigos[$x]['descripcion']=$fila[1];
			$x+=1;
		}
		//echo $x."<br/>";
	}
	$data['espacio']=12;
	$data['lote']=0;
	$data['detalles']=$codigos;
	$this->load->library("fpdf_factura");
	$this->load->view('supervision/rep_etiquetas_codigo_barras_pdf', $data);
}

function relacionar_hijos_papas($table1){
	$table=$this->uri->segment(4);
	if($table>0 or strlen($table)==0)
		$table=$table1;
	$this->load->model('producto_numeracion');
	$query_sa=$this->db->query("select distinct(s.cproducto_numero_id) as cproducto_numero_id, s.cproductos_id as t1, p.id as t2, pn.cproducto_id from $table as s left join cproductos_numeracion as pn on pn.id=s.cproducto_numero_id left join cproductos as p on p.id=pn.cproducto_id where  s.estatus_general_id=1 and s.cproductos_id!=p.id group by s.cproducto_numero_id, pn.cproducto_id, t1, t2 order by cproducto_numero_id desc");
	$size=$query_sa->num_rows(); $r=1;
	foreach($query_sa->result() as $row) {
		if($row->cproducto_id!=$row->t1 and $row->t2==$row->cproducto_id){
			$this->db->query("update salidas set cproductos_id=$row->cproducto_id where cproducto_numero_id=$row->cproducto_numero_id; update entradas set cproductos_id=$row->cproducto_id where cproducto_numero_id=$row->cproducto_numero_id");
			//echo "$r - $row->cproducto_numero_id- ".date("H:i:s")."<br/>";
		}
		if ($row->t2 != $row->t1 and $row->t2 =='') {
			$this->db->query("update cproductos_numeracion set cproducto_id=31 where id=$row->cproducto_numero_id");
			$this->db->query("update salidas set cproductos_id=31 where cproducto_numero_id=$row->cproducto_numero_id; update entradas set cproductos_id=31 where cproducto_numero_id=$row->cproducto_numero_id");
				
			//echo "Sin papa  $row->cproducto_numero_id- ".date("H:i:s")."<br/>";
		}
		unset($row);
		$r+=1;
	}
	unset($query_sa);
	//echo "Fin";
}

function pre_validar_archivo_inventario($id1){
	$id=$this->uri->segment(4);
	if($id>0 or strlen($id)==0)
		$id=$id1;
	$arqueo=$this->arqueo->get_by_id($id);
	if($arqueo->cestatus_arqueo_id==1){
		show_error("No se puede ejecutar el inventario debido a que no se a subido ningun archivo con los datos requeridos");
	}
	$path="/var/www/pavelerp/$arqueo->ruta_archivo";
	$archivo_csv=file($path);
	echo "<br/>ARchivo cargado: ".date("H:i:s");
	foreach($archivo_csv as $row){
		$fila=explode(',', $row);
		//0 pn_id 3 cantidad_real
		if(trim($fila[1])>0)
			$lote_id=trim($fila[1])-10000;
		else
			$lote_id=0;
		$pn_id=trim($fila[0]);
		$cantidad_real=trim($fila[3]);
		//Ya tengo el pn_id, el lote_id y la cantidad_real
	}
}

function desaplicar_ajuste_inventario($id = 0, $llave = "") {
	//Identificar la llave del usuario
	$usuario_id = $this->usuario->get_usuario_by_key($llave);
	//Validar que exista el usuario
	if ($usuario_id == false) {
		echo "<html><script>alert(\"Llave de autorización incorrecta.\"); window.location='" . base_url() . "index.php/{$GLOBALS['ruta']}/arqueos_c/formulario/list_ajuste_inventario';</script></html>";
		return;
	}
	$arqueo = new Arqueo($id);
	if (!$arqueo->exists()) {
		echo "<html><script>alert(\"El arqueo no existe.\"); window.location='" . base_url() . "index.php/{$GLOBALS['ruta']}/arqueos_c/formulario/list_ajuste_inventario';</script></html>";
		return;
	}
	$arqueo->cestatus_arqueo_id = 2;
	$arqueo->save();

	$arqueo_detalles = new Arqueo_detalle();
	$arqueo_detalles->where("arqueo_id", $id)->get();
	foreach ($arqueo_detalles as $detalle) {
		switch ($detalle->ctipo_ajuste_estatus_id) {
			case 2:
			case 3:
				$e = new Entrada($detalle->transaccion_id);
				$e->estatus_general_id = 2;
				$e->save();
				break;
			case 4:
			case 5:
				$s = new Salida($detalle->transaccion_id);
				$s->estatus_general_id = 2;
				$s->save();
		}
	}
	redirect('supervision/arqueos_c/formulario/list_ajuste_inventario');
}

function reaplicar_ajuste_inventario($id = 0, $llave = "") {
	//Identificar la llave del usuario
	$usuario_id = $this->usuario->get_usuario_by_key($llave);
	//Validar que exista el usuario
	if ($usuario_id == false) {
		echo "<html><script>alert(\"Llave de autorización incorrecta.\"); window.location='" . base_url() . "index.php/{$GLOBALS['ruta']}/arqueos_c/formulario/list_ajuste_inventario';</script></html>";
		return;
	}
	$this->rectificar_ajuste();
	$arqueo = new Arqueo($id);
	$arqueo->cestatus_arqueo_id = 2;
	$arqueo->save();
	echo "<script>alert(\"Rectificación terminada.\"); window.location='" . base_url() . "index.php/{$GLOBALS['ruta']}/arqueos_c/formulario/list_ajuste_inventario';</script>";
	//redirect('supervision/arqueos_c/formulario/list_ajuste_inventario');
}
function alta_ajuste_inventario_parcial() {
	$a = new Arqueo_parcial();
	$a->from_array($_POST);
	$a->fecha_captura = date("Y-m-d");
	$a->usuario_id = $GLOBALS['usuarioid'];
	$a->cestatus_arqueo_id = 1;
	$a->estatus_general_id = 1;
	if ($a->save())
		echo "<input type='hidden' name='id' id='id' value='$a->id'>Inventario Guardado";
	else
		echo "<div class='errorAjax'>Error al guardar el alta de inventario</div>";
}
function ejecutar_inventario_parcial() {
	//$this->relacionar_hijos_papas('entradas');
	//$this->relacionar_hijos_papas('salidas');
	$this->load->model('producto_numeracion');
	$this->load->model('arqueo_parcial');
	$id = $this->uri->segment('4');
	if ($id > 0) {
		$arqueo = $this->arqueo_parcial->get_by_id($id);
		if ($arqueo->cestatus_arqueo_id == 1) {
			show_error("No se puede ejecutar el inventario debido a que no se a subido ningun archivo con los datos requeridos");
		}
		if ($arqueo->cestatus_arqueo_id == 3) {
			//show_error("El inventario ya se ha ejecutado previamente, favor de revisarlo");
		}
		//Generar la vista temporal para ese espacio fisico y con las fechas finales del arqueo
		$this->db->query("drop view if exists arqueo_parcial_existencia");
		$this->db->query("create view arqueo_parcial_existencia as select cproducto_numero_id, sum(cantidad), lote_id from entradas where espacios_fisicos_id='$arqueo->espacio_fisico_id' and estatus_general_id=1 and fecha<='$arqueo->fecha_final $arqueo->hora_final' group by cproducto_numero_id, lote_id union select cproducto_numero_id, (sum(cantidad)*(-1)), lote_id from salidas where espacios_fisicos_id=$arqueo->espacio_fisico_id and estatus_general_id=1 and fecha<='$arqueo->fecha_final $arqueo->hora_final' group by cproducto_numero_id, lote_id order by cproducto_numero_id");
		//Obtener el archivo
		$path = "/var/www/pavelerp/$arqueo->ruta_archivo";
		echo date("H:i:s") . "Inicio<br/>";
		$archivo_csv = file($path);
		echo "<br/>Archivo cargado: " . date("H:i:s");
		$total_archivo = count($archivo_csv);
		$csv_inicial = 1;
		$csv_longitud = $total_archivo;
		$pasos = ceil($total_archivo / $csv_longitud);
		echo "Numero de pasos: $pasos, Num de registros: $total_archivo<br/>";
		//unset($archivo_csv);
		//Crear mecanismo para crear sentencia INSERT MULTIPLE
		//			$sql_string="insert into arqueo_detalles (arqueo_id, usuario_id, cproductos_numero_id, cproducto_id, cantidad_real, cantidad_sistema, diferencia, ctipo_ajuste_detalle_id, costo_unitario, lote_id) values ";

		$existe = $this->db->query("select distinct(cproducto_numero_id, lote_id), cproducto_numero_id, lote_id, sum(sum) as total from arqueo_parcial_existencia group by cproducto_numero_id, lote_id order by cproducto_numero_id, lote_id");
		foreach ($existe->result() as $lineas) {
			if (isset($exist_mtrx[$lineas->cproducto_numero_id][$lineas->lote_id]['id']) == false) {
				$exist_mtrx[$lineas->cproducto_numero_id][$lineas->lote_id]['id'] = $lineas->cproducto_numero_id;
				$exist_mtrx[$lineas->cproducto_numero_id][$lineas->lote_id]['lote_id'] = $lineas->lote_id;
				$exist_mtrx[$lineas->cproducto_numero_id][$lineas->lote_id]['existencia'] = $lineas->total;
			} else {
				$exist_mtrx[$lineas->cproducto_numero_id][$lineas->lote_id]['existencia']+=$lineas->total;
			}
			unset($lineas);
		}
		unset($existe);
		for ($xy = 1; $xy <= $pasos; $xy++) {
			//echo $csv_inicial."-Inicial<br/>";
			if (isset($archivo_csv) == false) {
				$archivo_csv = file($path);
			}
			$archivo_csv_loops = array_slice($archivo_csv, $csv_inicial, $csv_longitud);
			//unset($archivo_csv);
			//print_r($archivo_csv_loops);
			foreach ($archivo_csv_loops as $r => $row) {
				//echo "<br/>Paso".$r." ".date("H:i:s")." ";
				$fila = explode(',', $row);
				//print_r($fila);
				if (isset($fila[2]) and isset($fila[3])) {
					if (trim($fila[1]) > 0)
						$lote_id = trim($fila[1]) - 10000;
					else
						$lote_id = 0;
					$pn_id = trim($fila[0]);
					$cantidad_real = trim($fila[3]);

					if (isset($exist_mtrx[$pn_id][$lote_id]['id']) == true)
						$existencia['existencias'] = $exist_mtrx[$pn_id][$lote_id]['existencia'];
					else
						$existencia['existencias'] = 0;
					unset($r1);
					if ($cantidad_real > 0 or $existencia['existencias'] != 0) {
						$cantidad_sistema = $existencia['existencias'];
						// 						Obtener costo de venta
						$pn = $this->producto_numeracion->select('cproducto_id')->where('id', $pn_id)->get();
						$prod = $this->producto->select('precio1')->where('id', $pn->cproducto_id)->get();
						$costo_unitario = $prod->precio1;
						$ad = new arqueo_parcial_detalle();
						$ad->where('cproductos_numero_id', trim($fila[0]))->where('lote_id', $lote_id)->where('arqueo_parcial_id', $arqueo->id)->limit(1)->get();
						if ($ad->id > 0) {
							echo $fila[0] . "-$ad->id-$$" . $cantidad_sistema . " " . $cantidad_real + $ad->cantidad_real . "$$<br/>";
							$ad->cantidad_real+=$cantidad_real;
						} else
							$ad->cantidad_real = $cantidad_real;
						$ad->cproductos_numero_id = trim($fila[0]);
						$ad->lote_id = $lote_id;
						$ad->arqueo_parcial_id = $id;
						$ad->usuario_id = $GLOBALS['usuarioid'];
						$ad->cproducto_id = $pn->cproducto_id;
						$ad->cantidad_sistema = $cantidad_sistema;
						$diferencia = $cantidad_sistema - $cantidad_real;
						if ($diferencia == 0)
							$ctipo_ajuste_detalle_id = 1;
						else if ($diferencia > 0)
							$ctipo_ajuste_detalle_id = 4;
						else if ($diferencia < 0)
							$ctipo_ajuste_detalle_id = 3;
						$ad->diferencia = $diferencia;
						$ad->ctipo_ajuste_detalle_id = $ctipo_ajuste_detalle_id;
						$ad->costo_unitario = $costo_unitario;
						$ad->save();
						unset($ad);
						/* 							$value="( $id, {$GLOBALS['usuarioid']}, ".trim($fila[0]).", $pn->cproducto_id, $cantidad_real, $cantidad_sistema, '$diferencia', $ctipo_ajuste_detalle_id, '$costo_unitario', $lote_id )"; */
						unset($pn);
						unset($prod);
						//echo $sql_string." ".$value."<br/>";
						// 							$this->db->query($sql_string." ".$value);
						unset($fila);
					}
				}
				unset($row); //unset($existencia);
				unset($archivo_csv_loops[$r]);
			}
			unset($archivo_csv_loops);
			$csv_inicial = (($csv_longitud - 1) * $xy);
		}
		unset($archivo_csv);
		unset($exist_mtrx);
		//Cargar funcion para ejecutar inventario
		//$this->cuadrar_inventario($id);
	}
}

   function guardar_arqueo_detalle(){
        $pid = $_POST['pid'];
        $nid = $_POST['nid'];
        $arqueo = $_POST['arqueo'];
        $cantidad = $_POST['cantidad'];
        $espacio = $_POST['espacio'];
        $detalle = $_POST['detalle'];
        
        $arqueo_detalle = new Arqueo_detalle();
        if(0+$detalle> 0){
            $a = $arqueo_detalle->get_by_id($detalle);
            $diferencia = $a->cantidad_sistema - $cantidad;
            $a->diferencia = $diferencia;
            $a->cantidad_real = $cantidad;
            //Ajuste de entrada o de salida segun diferencia
            if($diferencia == 0){
                $tipo_ajuste = 1;
            } else {
                $tipo_ajuste = $diferencia > 0 ? 2 : 4;
            }        
            $a->ctipo_ajuste_detalle_id  = $tipo_ajuste;
            if ($a->save()) {
                echo $a->id;
            } else {
                echo 0;
            }
        }else {

            $arqueo_detalle->arqueo_id = $arqueo;
            $arqueo_detalle->cproducto_id = $pid;
            $arqueo_detalle->cproductos_numero_id = $nid;
            $arqueo_detalle->cantidad_real = $cantidad;

            $cantidad_sistema = $this->buscar_existencia($pid, $nid, $espacio);
            $arqueo_detalle->cantidad_sistema = $cantidad_sistema;//Sacar la cantidad de sistema
            $diferencia = $cantidad_sistema - $cantidad;
            $arqueo_detalle->diferencia = $diferencia;
            $arqueo_detalle->usuario_id = $GLOBALS['usuarioid'];
            $arqueo_detalle->costo_unitario = $this->producto->get_precio1($pid);
            $cantidad_divisora = $cantidad_sistema == 0 ? 1 : $cantidad_sistema;
            $arqueo_detalle->porciento_error = (($cantidad_sistema - $cantidad )/$cantidad_divisora)*100;
            //Ajuste de entrada o de salida segun diferencia
            if($diferencia == 0){
                $tipo_ajuste = 1;
            } else {
                $tipo_ajuste = $diferencia < 0 ? 2 : 4;
            }        
            $arqueo_detalle->ctipo_ajuste_detalle_id  = $tipo_ajuste;
            if ($arqueo_detalle->save()) {
                echo $arqueo_detalle->id;
            } else {
                echo 0;
            }
        }
        
        
    }
    
    //Buscar existencia por espacio fisico
    function buscar_existencia($producto, $numero, $espacio){
        $entradas = $this->db->query("select 
        sum(cantidad) as cantidad
            from entradas
        where cproductos_id = '$producto' and cproducto_numero_id = '$numero' and espacios_fisicos_id = '$espacio'");    
        $salidas = $this->db->query("select 
        sum(cantidad) as cantidad
            from salidas
        where cproductos_id = '$producto' and cproducto_numero_id = '$numero' and espacios_fisicos_id = '$espacio'");    
        $can_e=$entradas->row();
        $can_s=$salidas->row();
        //cantidad de entradas - cantidad de salidas = existencia en espacio
        return $can_e->cantidad - $can_s->cantidad;
    }
    
    function finalizar_arqueo(){
        $arqueo_id = $_POST["arqueo_id"];
        $arqueo = new Arqueo();
        $arqueo->get_by_id($arqueo_id);
        //$arq = $arqueo->row();
        $arqueo->fecha_final =  date("Y-m-d");
        $arqueo->hora_final =  date("H:i:s");
	$arqueo->cestatus_arqueo_id = 2;
        if($arqueo->save()){
            echo "Arqueo finalizado a las: ".date("H:i:s");
        } else {
            echo "No se pudo guardar terminar el arqueo";
        }
    }

    function finalizar_arqueo_total(){
        $arqueo_id = $_POST["arqueo_id"];
        $espacio = $_POST["espacio"];
        $arqueo = new Arqueo();
        $arqueo->get_by_id($arqueo_id);
        // Sacar todos los detalles del arqueo
        $arqueo_detalles=$this->arqueo_detalle->get_arqueo_detalles_by_parent($arqueo_id);
        // Sacar todos los productos con existencia en el espacio
        $prod_espacio = new Producto();
        $p_espacio = $prod_espacio->get_existencia_productos_por_espacio($espacio);
        foreach ($p_espacio->all as $prod) {
            if($prod->existencia_sistema != 0){
                $existe_ajuste = false;
                foreach($arqueo_detalles->all as $detalle){
                    // Buscar que no exista ajuste
                    if($detalle->cproducto_id == $prod->producto_id && 
                            $detalle->cproductos_numero_id == $prod->numero_id) {
                        $existe_ajuste = true;
                    }
                }
                // Si no existe ajuste crearlo
                if(!$existe_ajuste){
                    $this->crear_ajuste($prod, $arqueo_id);
                }
            }

        }
        $arqueo->fecha_final =  date("Y-m-d");
        $arqueo->hora_final =  date("H:i:s");
	$arqueo->cestatus_arqueo_id = 2;	
        if($arqueo->save()){
            echo "Arqueo finalizado a las: ".date("H:i:s");
        } else {
            echo "No se pudo guardar terminar el arqueo";
        }
    }
    
    function crear_ajuste($prod, $arqueo_id){
        $arqueo_detalle = new Arqueo_detalle();
        $arqueo_detalle->arqueo_id = $arqueo_id;
            $arqueo_detalle->cproducto_id = $prod->producto_id;
            $arqueo_detalle->cproductos_numero_id = $prod->numero_id;
            $arqueo_detalle->cantidad_real = 0;

            $cantidad_sistema = $prod->existencia_sistema;
            $arqueo_detalle->cantidad_sistema = $cantidad_sistema;//Sacar la cantidad de sistema
            $diferencia = $cantidad_sistema;
            $arqueo_detalle->diferencia = $diferencia;
            $arqueo_detalle->usuario_id = $GLOBALS['usuarioid'];
            $arqueo_detalle->costo_unitario = $prod->costo_unitario;
            $arqueo_detalle->porciento_error = $cantidad_sistema*100;
            //Ajuste de entrada o de salida segun diferencia
            if($diferencia == 0){
                $tipo_ajuste = 1;
            } else {
                $tipo_ajuste = $diferencia < 0 ? 2 : 4;
            }        
            $arqueo_detalle->ctipo_ajuste_detalle_id  = $tipo_ajuste;
            if ($arqueo_detalle->save()) {
                echo $arqueo_detalle->id;
            } else {
                echo 0;
            }
            
      }
    
    
}
?>
