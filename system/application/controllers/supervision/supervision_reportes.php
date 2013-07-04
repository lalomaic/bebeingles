<?php
ini_set("memory_limit","800M");
class Supervision_reportes extends Controller {
	//var $usuarioid, $username, $ruta, $controller, $funcion, $subfuncion, $modulos_totales;

	function Supervision_reportes()
	{
		parent::Controller();
		if($this->session->userdata('logged_in') == FALSE){
			redirect(base_url()."index.php/inicio/logout");
		}
		$this->assetlibpro->add_css('default.css');
		$this->assetlibpro->add_css('menu_style.css');
		$this->assetlibpro->add_css('date_input.css');
		$this->assetlibpro->add_css('jquery.validator.css');
		$this->assetlibpro->add_css('autocomplete.css');
		$this->assetlibpro->add_css('fancybox/jquery.fancybox-1.3.1.css');
		$this->assetlibpro->add_css('style_fancy.css.css');
		$this->assetlibpro->add_js('jquery.js');
		$this->assetlibpro->add_js('jquery.autocomplete.js');
		$this->assetlibpro->add_js('jquery.selectboxes.js');
		$this->assetlibpro->add_js('jquery.select-autocomplete.js');
		$this->assetlibpro->add_js('jquery.numberformatter.js');
		$this->assetlibpro->add_js('jquery.AddIncSearch.js');
		$this->assetlibpro->add_js('jquery.validator.js');
		$this->assetlibpro->add_js('jquery.date.js');
		$this->assetlibpro->add_js('jquery.form.js');
		$this->assetlibpro->add_js('fancybox/jquery.mousewheel-3.0.2.pack.js');
		$this->assetlibpro->add_js('fancybox/jquery.fancybox-1.3.1.js');
		$this->load->model("espacio_fisico");
		$this->load->model("almacen");
		$this->load->model("arqueo");
		$this->load->model("salida");
		$this->load->model("entrada");
		$this->load->model("arqueo_detalle");
		$user_hash=$this->session->userdata('user_data');
		$row=$this->usuario->get_usuario_detalles($user_hash);
		$GLOBALS['usuarioid']=$row->id;
		$GLOBALS['empresa_id']=$row->empresas_id;
		$GLOBALS['username']=$row->username;
		$GLOBALS['ruta']=$this->uri->segment(1);
		$GLOBALS['controller']=$this->uri->segment(2);
		$GLOBALS['funcion']=$this->uri->segment(3);
		$GLOBALS['subfuncion']=$this->uri->segment(4);
		$GLOBALS['modulos_totales']=$this->modulo->get_tmodulos();
		$GLOBALS['main_menu']=$this->menu->menus($GLOBALS['usuarioid'],"principal",0);
	}


	function formulario(){
		//Funcion para manejar formularios y listados,
		global $main_menu, $usuarioid, $username, $ruta, $controller, $funcion, $subfuncion, $modulos_totales, $empresa_id;

		$main_view=false;
		$data['empresa_id']=$empresa_id;
		$data['username']=$username;
		$data['usuarioid']=$usuarioid;
		$data['modulos_totales']=$modulos_totales;
		$data['colect1']=$main_menu;
		$data['title']=$this->accion->get_title("$subfuncion");
		$accion_id=$this->accion->get_id("$subfuncion");
		$row=$this->usuario->get_usuario($usuarioid);
		$grupoid=$row->grupo_id;
		$puestoid=$row->puesto_id;
		$data['ruta']=$ruta;
		$data['controller']=$controller;
		$data['funcion']=$funcion;
		$data['subfuncion']=$subfuncion;
		$data['permisos']=$this->usuario_accion->get_permiso($accion_id, $usuarioid, $puestoid, $grupoid);

		//Validacion del arreglo del menu, usuarioid y permisos especificos
		if(is_array($data['colect1']) and $usuarioid>0 and $data['permisos']!= false and $this->session->userdata('logged_in') == TRUE){

			$main_view=true;
			if ($subfuncion=="rep_ajuste_inventario"){
				//Cargar los datos para el formulario
				$data['frames']=1;
				$data['principal']=$ruta."/".$subfuncion;
				$data['funcion']=$subfuncion;
				$data['espacios']=$this->espacio_fisico->get_espacios_by_empresa_id($empresa_id);
				//$this->load->view("supervision/rep_ajuste_inventario", $data);
			} else  if ($subfuncion=="rep_archivo_inventario"){
				//Cargar los datos para el formulario
				$data['frames']=1;
				$data['principal']=$ruta."/".$subfuncion;
				$data['espacios']=$this->espacio_fisico->get_espacios_tiendas();

			} else  if ($subfuncion=="rep_reimpresion_etiquetas"){
				//Cargar los datos para el formulario
				$data['frames']=1;
				$data['principal']=$ruta."/".$subfuncion;
				$data['espacios']=$this->espacio_fisico->get_espacios_tiendas();

			} else if ($subfuncion=="alta_cproductos_csv"){
				//Cargar los datos para el formulario
				$main_view=false;
				$this->load->dbutil();
				$this->load->helper('file');
				$this->load->helper('csv');
				$this->load->model('producto_numeracion');
				// 			$delimiter = ",";
				// 			$newline = "\r\n";
				// 			$datos=$this->dbutil->csv_from_result($query);
				//
				//Formar la cabecera
				$datos[]=array("cproducto_numeracion_id","codigo_anterior","descripcion","medida",);
				$contar=$this->producto_numeracion->select('count(id) as total')->get();
				$loop=30000;
				$veces=ceil($contar->total/$loop);
				$r=1;
				for($x=0;$x<$veces;$x++){
					//				$query = $this->db->query("select * from catalogo_csv where codigo_anterior not like 'B%' limit $loop offset ".($x*$loop));
					$query = $this->db->query("select * from catalogo_csv limit $loop offset ".($x*$loop));
					// 				echo "select * from catalogo_csv limit $loop offset ".($x*$loop);
					// 				echo $x."</br>";
					foreach($query->result() as $row){
						$datos[$r]=array("$row->cproducto_numeracion_id","$row->codigo_anterior","$row->descripcion","$row->medida");
						$r+=1;
						unset($row);
					}
					unset($query);
				}
				//array_to_csv($datos, 'toto.csv');
				$matriz=array_to_csv($datos);
				$ruta='/var/www/pavelerp/tmp/file.csv';
				if ( ! write_file($ruta, $matriz))
				{
					unset($query);
					echo 'No se puede escribir el archivo';
				} else {
					unset($query);
					$this->load->helper('download');
					force_download('catalogo_'.date("d_m_Y").'.csv', $matriz);
					redirect(base_url()."index.php/inicio/acceso/supervision/menu");
				}
			} else if ($subfuncion == 'verificar_producto') {
				$data['principal'] = "$ruta/$subfuncion";
				$data['funcion'] = $subfuncion;
			} else if ($subfuncion == "rep_etiquetas_codigo_barras") {
                $data['principal'] = $ruta . "/" . $subfuncion;
                $data['title'] = "Impresion de Etiquetas con Codigo de Barras";
          
            }

			if($main_view){
				//Llamar a la vista
				$this->load->view("ingreso", $data);
			} else {
				//	      redirect(base_url()."index.php/inicio/logout");
			}
		}
	} // end method formulario ##################



	function rep_ajuste_inventario_pdf(){
		global $ruta, $subfuncion;
		$where="";
		$data['title']=$this->accion->get_title("{$_POST['subfuncion']}");
		//Obtener los datos
		$espacios=$_POST['espacios'];
		if($espacios==0){
			$where .=" where ef.id>0 ";
			$data['tienda']="Todos";
		} else {
			$where .=" where ef.id='$espacios' ";
			$ef=$this->espacio_fisico->get_espacio_f($espacios);
		}
		$where1=$where;
		$fecha1 = $this->input->post('fecha1');
		$fecha2 = $this->input->post('fecha2');
		if($fecha1 != false)
		{
			list($d,$m,$a) = explode(" ",$fecha1);
			$fecha1 = sprintf('%04d-%02d-%02d',$a,$m,$d);
		}
		if($fecha2 != false)
		{
			list($d,$m,$a) = explode(" ",$fecha2);
			$fecha2 = sprintf('%04d-%02d-%02d',$a,$m,$d);
		}
		$f1 = false;
		$f2 = false;
		if($fecha1 == date("Y-m-d",strtotime($fecha1)))
			$f1 = true;
		if($fecha2 == date("Y-m-d",strtotime($fecha2)))
			$f2 = true;
		if($f1 && $f2){
			if(strcmp($fecha2,$fecha1) > 0)
				$filtro[] = "(a.fecha >= '$fecha1' and a.fecha <= '$fecha2')";
			else
				$filtro[] = "(a.fecha >= '$fecha2' and a.fecha <= '$fecha1')";
		}
		elseif($f1)
		$filtro[] = "a.fecha = '$fecha1'";
		elseif($f2)
		$filtro[] = "a.fecha = '$fecha2'";
		if (count($filtro))
			$where = " where ".implode(" and ", $filtro)." ";

		$order_by=" order by espacio";
		$data['arqueo']=$this->arqueo->get_arqueos_pdf($where, $order_by);
		$data['arqueo_detalles']=$this->arqueo_detalle->get_arqueos_pdf($where1, $order_by);
		$this->load->view("$ruta/rep_ajuste_inventario_pdf", $data);
		//Enviarselo al view para general el PDF
	}

  function rep_etiquetas_codigo_barras_pdf() {
        //Generar el PDF
        $this->load->plugin('barcode');
        $this->load->model('entrada');
        //$id=505;
        $id = $_POST['producto_id'];
        $pn=$_POST['producto_m'];
        $data['producto'] = $this->producto->cobarras_producto($id,$pn);
        $var=  strtoupper($data['producto']->cod);
        //Verificar que existen entrada para el par de producto y lote
        if ($var == '') {
            $char = strlen($data['producto']->id);
            show_error("Este Producto No tiene Codigo de Barras");
           
			
        } else
            if($_POST['botPress']=="Generar Etiquetas 5CM"){
               $data['tam']=5; 
            }  else if($_POST['botPress']=="Generar Etiquetas 3CM"){ 
               $data['tam']=3;  
            }
            $numero=$data['producto']->cod;
        barcode_create($numero, "code128", "jpeg", 'cb_' . $id, '/var/www/bebe_ingles/tmp/');
        $data['pages'] = $_POST['pages'];
        $data['id']=$_POST['producto_id'];
        $this->load->library("fpdf_factura");
        $this->load->view('supervision/imp_etiquetas_codigo_barras_pdf', $data);
    }
	function rep_ajuste_pdf(){
		$id=$this->uri->segment(4);
		$data['title']="Reporte de Ajuste de Existencia Física";
		$data['arqueo']=$this->arqueo->get_arqueo_pdf($id);
		if ($data['arqueo']==false)
			show_error("El número de ajuste no concuerda");
		$data['arqueo_detalles']=$this->arqueo_detalle->get_arqueo_detalles_by_parent($id);
		if ($data['arqueo_detalles']==false)
			show_error("El arqueo no contiene conceptos");
		$r=0;
		foreach($data['arqueo_detalles']->all as $row){
			$datos["$r"]['id']=$row->id;
			$datos["$r"]['producto']=$row->producto;
			$datos["$r"]['numero']=$row->numero;
			$datos["$r"]['cantidad_real']=$row->cantidad_real;
			$datos["$r"]['cantidad_sistema']=$row->cantidad_sistema;
			$datos["$r"]['diferencia']=$row->diferencia;
			$datos["$r"]['transaccion_id']=$row->transaccion_id;
			$datos["$r"]['accion_id']=$row->accion_id;
			$datos["$r"]['accion']=$row->accion;
			$datos["$r"]['precio_unitario']=$row->costo_unitario;			
			$r+=1;
			unset($row);
		}
		$data['datos']=$datos;
                $this->load->view("supervision/rep_ajuste_pdf", $data);
		unset($data); unset($datos);
	}


	function rep_archivo_inventario_csv(){
		//Cargar los datos para el formulario
		$espacio=$this->input->post('espacios');
		if($espacio==0)
			show_error("Elija una Tienda para Generar el Archivo de Inventario");
		$espacio_obj=$this->espacio_fisico->get_by_id($espacio);
		$this->load->dbutil();
		$this->load->helper('file');
		$this->load->helper('csv');
		$this->load->model('producto_numeracion');
		//Formar la cabecera

		$datos[]=array("cproducto_numeracion_id","lote_id","cantidad_sistema","cantidad_real","diferencia",);
		$r=1;
		$query=$this->db->query("select distinct(pn.id) as cproducto_numero_id  from cproductos_numeracion as pn left join cproductos as p on p.id=pn.cproducto_id where  p.estatus_general_id=1 and clave_anterior!='0' order by pn.id");
		foreach($query->result() as $row) {
			$datos["$row->cproducto_numero_id&0"]=array("$row->cproducto_numero_id","0", '0',"0", "0");
			unset($row);
		}
		$this->db->query("drop view arqueo_existencia");
		$this->db->query("create view arqueo_existencia as select cproducto_numero_id, sum(cantidad), lote_id from entradas where espacios_fisicos_id='$espacio' and estatus_general_id=1 and fecha<='".date("Y-m-d H:i:s")."' and lote_id>0 group by cproducto_numero_id, lote_id union select cproducto_numero_id, (sum(cantidad)*(-1)), lote_id from salidas where espacios_fisicos_id=$espacio and estatus_general_id=1 and fecha<='".date("Y-m-d H:i:s")."' and lote_id>0 group by cproducto_numero_id, lote_id order by cproducto_numero_id");

		$query=$this->db->query("select distinct(cproducto_numero_id, lote_id), cproducto_numero_id, lote_id, sum(sum) as total from arqueo_existencia group by cproducto_numero_id, lote_id order by lote_id, cproducto_numero_id");
		foreach($query->result() as $row) {
			$datos["$row->cproducto_numero_id&$row->lote_id"]=array("$row->cproducto_numero_id",($row->lote_id), round($row->total,0),"0", "0");
			unset($row);
		}
		$matriz=array_to_csv($datos);
		$ruta='/var/www/pavelerp/tmp/file.csv';
		if ( ! write_file($ruta, $matriz)) {
			unset($query);
			echo 'No se puede escribir el archivo';
		} else {
			unset($query);
			$this->load->helper('download');
			force_download('inventario_tienda_'.$espacio."-".date("d_m_Y").'.csv', $matriz);
			redirect(base_url()."index.php/inicio/acceso/supervision/menu");
		}
	}

	function rep_reimprimir_etiquetas_pdf(){
		//Obtener datos via post
		$path="/var/www/pavelerp/tmp/";
		$this->load->model("producto_numeracion");
		$this->load->plugin('barcode');
		$codigo=$this->input->post('numero_codigo');
		if(strlen($codigo<7))
			show_error("Capture un código de etiqueta válido");
		$espacio=$this->input->post('espacios');
		if($espacio==0)
			show_error("Capture una Sucursal");
		if(strlen($codigo)>=13){
			$pn_id=substr($codigo, -6);
			$lote_id=substr($codigo,0,5);
			//             $charl=strlen($lote_id);
			// 			for($charl;$charl<5;$charl++){
			// 				$lote_id="0".$lote_id;
			// 			}
			} else
				show_error("Capture un código de etiqueta válido");
			$pn=new Producto_numeracion();
			$pn->get_by_id($pn_id);
			$producto=$this->producto->get_by_id($pn->cproducto_id);
			// 		echo $pn->cproducto_id;
			// 		exit();
			$productos=$this->producto_numeracion->where('cproducto_id',$pn->cproducto_id)->order_by('numero_mm')->get();
			$x=1;
			foreach($productos->all as $row){
				$char=strlen($row->id);
				$numero=$row->id;
				for($char;$char<8;$char++){
					$numero="0".$numero;
				}
				barcode_create("$lote_id$numero","code128","jpeg", 'cb_'.$lote_id.$numero, $path);
				$codigos[$x]['codigo']="$lote_id$row->id";
				$codigos[$x]['ruta']=$path."cb_$lote_id$numero.jpeg";
				$codigos[$x]['descripcion']=$producto->descripcion." ".($row->numero_mm/10);
				$x+=1;
				//			echo $x;
				unset($row);
			}
			$data['espacio']=$espacio;
			$data['lote']=$lote_id;
			$data['detalles']=$codigos;
			$this->load->library("fpdf_factura");
			$this->load->view('supervision/rep_etiquetas_codigo_barras_pdf', $data);
	}


	function rep_reimprimir_etiquetas_anteriores_pdf(){
		//Obtener datos via post
		$path="/var/www/pavelerp/tmp/";
		$this->load->plugin('barcode');
		$lineas=count($_POST)/2;
		$codigos=array(); $n=0;
		for($x=0;$x<$lineas;$x++){
			if($_POST['cantidad'.$x]>0 and strlen($_POST['codigo'.$x])>0){
				$pn=new Producto_numeracion();
				$pn->where('clave_anterior', $_POST["codigo$x"])->limit(1)->get();
				$producto=$this->producto->get_by_id($pn->cproducto_id);
				$codigos[$n]['descripcion']=" $producto->descripcion # ".($pn->numero_mm/10);
				$_POST["codigo$x"]=str_replace('B', '', strtoupper($_POST["codigo$x"]));
				barcode_create($_POST["codigo$x"],"code128","jpeg", $_POST["codigo$x"], $path);
				$codigos[$n]['codigo']=$_POST["codigo$x"];
				//Obtener la descripcion del producto_hijo
				$codigos[$n]['ruta']=$path . $_POST["codigo$x"] . ".jpeg";
				$codigos[$n]['cantidad']=$_POST['cantidad'.$x];
				$n+=1;
				unset($pn); unset($producto);
			}
		}
		//		print_r($codigos);
		$data['detalles']=$codigos;
		$this->load->library("fpdf_factura");
		$this->load->view('supervision/rep_etiquetas_codigo_barras_anteriores_pdf', $data);
	}
	function rep_ajuste_parcial_pdf() {
		$id = $this->uri->segment(5);
		$ubicacion_id = $this->uri->segment(4);
		$data['title'] = "Reporte de Ajuste Parcial de Existencia Física";
		$this->load->model('arqueo_parcial');
		$this->load->model('arqueo_parcial_detalle');
		$data['arqueo'] = $this->arqueo_parcial->get_arqueo_pdf($id, $ubicacion_id);
		if ($data['arqueo'] == false)
			show_error("El número de ajuste no concuerda");
		$data['arqueo_detalles'] = $this->arqueo_detalle->get_arqueo_detalles_by_parent($id);
		if ($data['arqueo_detalles'] == false)
			show_error("El arqueo no contiene conceptos");
		$r = 0;
		foreach ($data['arqueo_detalles']->all as $row) {
			$datos["$r"]['lote_id'] = $row->lote_id;
			$datos["$r"]['producto'] = $row->producto;
			$datos["$r"]['numero'] = $row->numero;
			$datos["$r"]['cantidad_real'] = $row->cantidad_real;
			$datos["$r"]['cantidad_sistema'] = $row->cantidad_sistema;
			$datos["$r"]['diferencia'] = $row->diferencia;
			$datos["$r"]['transaccion_id'] = $row->transaccion_id;
			$datos["$r"]['accion_id'] = $row->accion_id;
			$datos["$r"]['accion'] = $row->accion;
			if ($row->accion_id == 4 or $row->accion_id == 5) {
				//Salidas
				$datos["$r"]['precio_unitario'] = $this->salida->get_precio_unitario($row->transaccion_id);
			} else if ($row->accion_id == 2 or $row->accion_id == 3) {
				//Entradas
				$datos["$r"]['precio_unitario'] = $this->entrada->get_precio_unitario($row->transaccion_id);
			} else {
				$datos["$r"]['precio_unitario'] = 0;
			}
			$r+=1;
			unset($row);
		}
		$data['datos'] = $datos;
		$this->load->view("supervision/rep_ajuste_parcial_pdf", $data);
		unset($data);
		unset($datos);
	}
	function verificar_producto() {
		if(!isset($_POST['codigo']) || strlen($_POST['codigo']) != 13) {
			?>
<div class="emptyList">El código no tiene el formato correcto, debe
	contener 13 caracteres</div>
<?
return;
		}
		$data['producto'] = new Entrada();
		$data['producto']->
		where('lote_id', (int)substr($_POST['codigo'], 0, 5))->
		where('cproducto_numero_id', (int)substr($_POST['codigo'], 5))->
		include_related('cproductos', 'descripcion')->
		include_related('cproducto_numero', 'numero_mm')->
		limit(1)->get();
		if(!$data['producto']->exists()){
			?>
<div class="emptyList">No existen productos con ese código</div>
<?
return;
		}
		$sql = "(SELECT
		1 entrada,
		te.tag movimiento,
		ef.tag tienda,
		e.ctipo_entrada,
		e.fecha,
		TO_CHAR(e.cantidad, 'FM999G999G999') cantidad,
		e.espacios_fisicos_id,
		TO_CHAR(e.fecha, 'DD-MM-YYYY HH24:MI:SS') fecha_format,
		CASE
		WHEN e.ctipo_entrada = 1 THEN
		e.pr_facturas_id || ',' || pf.folio_factura
		WHEN e.ctipo_entrada = 7 THEN
		ad.arqueo_id || ',' || ad.id
		ELSE
		e.id || ''
		END id_movimiento
		FROM
		entradas e
		JOIN espacios_fisicos ef
		ON ef.id = e.espacios_fisicos_id
		JOIN ctipos_entradas te
		ON te.id = e.ctipo_entrada
		LEFT JOIN arqueo_detalles ad
		ON ad.transaccion_id = e.id
		AND ad.ctipo_ajuste_detalle_id = 3
		LEFT JOIN pr_facturas pf
		ON pf.id = e.pr_facturas_id
		WHERE
		e.lote_id = ".(int)substr($_POST['codigo'], 0, 5)."
		AND e.cproducto_numero_id = ".(int)substr($_POST['codigo'], 5)."
		AND e.estatus_general_id = 1
		UNION SELECT
		0 entrada,
		ts.tag movimiento,
		ef.tag tienda,
		s.ctipo_salida_id,
		s.fecha,
		TO_CHAR(s.cantidad, 'FM999G999G999') cantidad,
		s.espacios_fisicos_id,
		TO_CHAR(s.fecha, 'DD-MM-YYYY HH24:MI:SS') fecha_format,
		CASE
		WHEN s.ctipo_salida_id = 1 THEN
		s.numero_remision_id || ''
		WHEN s.ctipo_salida_id = 7 THEN
		ad.arqueo_id || ',' || ad.id
		ELSE
		s.id || ''
		END id_movimiento
		FROM
		salidas s
		JOIN espacios_fisicos ef
		ON ef.id = s.espacios_fisicos_id
		JOIN ctipos_salidas ts
		ON ts.id = s.ctipo_salida_id
		LEFT JOIN arqueo_detalles ad
		ON ad.transaccion_id = s.id
		AND ad.ctipo_ajuste_detalle_id = 4
		LEFT JOIN cl_facturas cf
		ON cf.id = s.cl_facturas_id
		WHERE
		s.lote_id = ".(int)substr($_POST['codigo'], 0, 5)."
		AND s.cproducto_numero_id = ".(int)substr($_POST['codigo'], 5)."
		AND s.estatus_general_id = 1)
		ORDER BY
		fecha";
		$data['movimientos'] = $this->db->query($sql)->result();
		$this->load->view('supervision/verificar_prducto_detalle', $data);
	}
}
?>
