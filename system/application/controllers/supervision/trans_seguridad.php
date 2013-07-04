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
		$m->fecha=$arqueo_db->fecha ." ". $arqueo_db->hora;
		$m->estatus_general_id=1;
		$m->cantidad=abs($_POST['cantidad_real'.$linea]-$_POST['cantidad_sistema'.$linea]);
		$m->cproductos_id=$_POST['cproducto_id'.$linea];
		 
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
					$where=" where fecha<='$row->fecha $row->hora' and espacios_fisicos_id='$row->espacio_fisico_id'";
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
			echo "<input type='hidden' name='id' id='id' value='$u->id'>Dado de Alta el Inventario";
		else
			echo "<h2>Error al guardar el alta de inventario</h2>";
	}
	function ejecutar_inventario(){
		$this->load->model('producto_numeracion');
		$id=$this->uri->segment('4');
		if($id>0){
			$arqueo=$this->arqueo->get_by_id($id);
			if($arqueo->cestatus_arqueo_id==1){
				show_error("No se puede ejecutar el inventario debido a que no se a subido ningun archivo con los datos requeridos");
			}
			if($arqueo->cestatus_arqueo_id==3){
				show_error("El inventario ya se ha ejecutado previamente, favor de revisarlo");
			}
			//Generar la vista temporal para ese espacio fisico y con las fechas finales del arqueo
			$this->db->query("drop view arqueo_existencia");
			$this->db->query("create view arqueo_existencia as select cproducto_numero_id, sum(cantidad), lote_id from entradas where espacios_fisicos_id='$arqueo->espacio_fisico_id' and estatus_general_id=1 and fecha<='$arqueo->fecha_final $arqueo->hora_final' group by cproducto_numero_id, lote_id union select cproducto_numero_id, (sum(cantidad)*(-1)), lote_id from salidas where espacios_fisicos_id=$arqueo->espacio_fisico_id and estatus_general_id=1 and fecha<='$arqueo->fecha_final $arqueo->hora_final' group by cproducto_numero_id, lote_id order by cproducto_numero_id");
			//Obtener el archivo
			$path="/var/www/pavelerp/$arqueo->ruta_archivo";
			echo date("H:i:s")."Inicio<br/>";
			$csv_inicial=1; $csv_longitud=100001;
			$archivo_csv=file($path);
			echo "<br/>ARchivo cargado: ".date("H:i:s");
			$total_archivo=count($archivo_csv);
			$pasos=ceil($total_archivo/$csv_longitud);
			echo "Numero de pasos: $pasos, Num de registros: $total_archivo<br/>";
			//unset($archivo_csv);
			//Crear mecanismo para crear sentencia INSERT MULTIPLE
			$sql_string="insert into arqueo_detalles (arqueo_id, usuario_id, cproductos_numero_id, cproducto_id, cantidad_real, cantidad_sistema, diferencia, ctipo_ajuste_detalle_id, costo_unitario, lote_id) values ";

			$existe=$this->db->query("select distinct(cproducto_numero_id, lote_id), cproducto_numero_id, lote_id, sum(sum) as total from arqueo_existencia group by cproducto_numero_id, lote_id order by cproducto_numero_id, lote_id");
			foreach($existe->result() as $lineas){
				$exist_mtrx[$lineas->cproducto_numero_id][$lineas->lote_id]['id']=$lineas->cproducto_numero_id;
				if($lineas->lote_id=='')
					$lineas->lote_id=0;
				$exist_mtrx[$lineas->cproducto_numero_id][$lineas->lote_id]['lote_id']=$lineas->lote_id;
				$exist_mtrx[$lineas->cproducto_numero_id][$lineas->lote_id]['existencia']=$lineas->total;
				unset($lineas);
			}
			unset($existe);
			for($xy=1;$xy<=$pasos;$xy++) {
				echo $csv_inicial."-Inicial<br/>";
				if(isset($archivo_csv)==false){
					$archivo_csv=file($path);
				}
				$archivo_csv_loops=array_slice($archivo_csv, $csv_inicial, $csv_longitud);
				//unset($archivo_csv);
				//print_r($archivo_csv_loops);
				foreach($archivo_csv_loops as $r=>$row) {
					echo "<br/>Paso".$r." ".date("H:i:s")." ";
					$fila=explode(',', $row);
						
					if(isset($fila[2]) and isset($fila[3])) {
						$lote_id=trim($fila[1]);
						$pn_id=trim($fila[0]);

						if(isset($exist_mtrx[$pn_id][$lote_id]['id'])==true)
							$existencia['existencias']=$exist_mtrx[$pn_id][$lote_id]['existencia'];
						else
							$existencia['existencias']=0;
						unset($r1);


						/*						if($this->almacen->pre_existencia(trim($fila[0]), $arqueo->espacio_fisico_id)==true)
							$existencia=$this->almacen->existencias_numero(trim($fila[0]), " where espacios_fisicos_id=$arqueo->espacio_fisico_id and fecha<='".$arqueo->fecha_final." ".$arqueo->hora_final."' and lote_id=$lote_id");
						else
							$existencia['existencias']=0;*/

						if($existencia['existencias'] !=0) {
							$cantidad_real=trim($fila[3]);
							$cantidad_sistema=$existencia['existencias'];
							$diferencia=$existencia['existencias']-$cantidad_real;
							if($diferencia==0)
								$ctipo_ajuste_detalle_id=1;
							else if($diferencia>0)
								$ctipo_ajuste_detalle_id=4;
							else if($diferencia<0)
								$ctipo_ajuste_detalle_id=3;
							// 						Obtener costo de venta
							$pn=$this->producto_numeracion->select('cproducto_id')->where('id', $pn_id)->get();
							$prod=$this->producto->select('precio1')->where('id',$pn->cproducto_id)->get();
							$costo_unitario=$prod->precio1;

							$value="( $id, {$GLOBALS['usuarioid']}, ".trim($fila[0]).", $pn->cproducto_id, $cantidad_real, $cantidad_sistema, '$diferencia', $ctipo_ajuste_detalle_id, '$costo_unitario', $lote_id )";
							//Cuando el $loop llegue al $n_registros, realizar la insercion y blanquear la variable value_mtrx
							unset($pn); unset($prod);
							echo $r."-". $fila[0];

							echo " Final -".date("H:i:s")."<br/>";
							$this->db->query($sql_string." ".$value);
							unset($fila);
						}
					}
					unset($row); unset($existencia);
					unset($archivo_csv_loops[$r]);
				}
				unset($archivo_csv_loops);
				$csv_inicial=(($csv_longitud-1)*$xy);
				//if($xy==10)
				//exit();
				//echo "Final";
			}
			unset($archivo_csv); unset($exist_mtrx);
			//Cargar funcion para ejecutar inventario
			//	$this->cuadrar_inventario($id);
		}
	}
	function cuadrar_inventario($id){
		//Función para cuadrar inventario en base al arqueo con archivo migrado a la base de datos
		$arqueo=new Arqueo();
		$arqueo->get_by_id($id);
		$size=$this->arqueo_detalle->get_arqueo_total_xcuadrar($id);
		$per_page=20000; $offset=0;
		$loops=ceil($size/$per_page);
		if($size>0) {
			for($loop=0; $loop<$loops;$loop++) {
				$arqueo_detalles=$this->arqueo_detalle->get_arqueo_detalles_by_padre($id, $per_page, $offset);
				foreach($arqueo_detalles->all as $row){
					if($row->transaccion_id==0 or $row->transaccion_id==''){
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
								$precio1=$this->producto->get_producto($row->cproducto_id);
								$m->cantidad=abs($row->diferencia);
								$m->costo_unitario=$precio1->precio1;
								$m->costo_total=$m->costo_unitario * $m->cantidad;
								$m->cl_facturas_id=0;
								$m->cclientes_id=0;

							}
							$m->espacios_fisicos_id=$arqueo->espacio_fisico_id;
							$m->usuario_id=$GLOBALS['usuarioid'];
							$m->fecha=$arqueo->fecha_final ." " .$arqueo->hora_final;
							$m->estatus_general_id=1;
							$m->cantidad=abs($row->diferencia);
							$m->cproductos_id=$row->cproducto_id;
							$m->cproducto_numero_id=$row->cproductos_numero_id;
							if($m->save()){
								$this->db->query("update arqueo_detalles set transaccion_id=$m->id where id=$row->llave");
								echo "$row->ctipo_ajuste_detalle_id -> $m->id";
							}
						}
					}
					unset($row);
				}

			}
			$offset+=$per_page;
		}
		$arqueo->cestatus_arqueo_id=3;
		$arqueo->save();
		if($size>0)
			echo "<html> <script>alert(\"Se ha cuadrado el arqueo $arqueo->id.\"); window.location='".base_url()."index.php/supervision/arqueos_c/formulario/list_ajuste_inventario';</script></html>";
		else
			show_error("El arqueo ya ha sido cuadrado o bien no se han cargado los detalles del mismo, favor de contactar a los Administradores del Sistema");

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


}
?>
