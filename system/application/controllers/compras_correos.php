<?php
class Compras_correos extends Controller {
	function Compras_correos()
	{
		parent::Controller();
		$this->assetlibpro->add_css('default.css');
		$this->assetlibpro->add_css('menu_style.css');
		$this->assetlibpro->add_css('date_input.css');
		$this->assetlibpro->add_css('jquery.validator.css');
		$this->assetlibpro->add_css('autocomplete.css');
		/*		$this->assetlibpro->add_css('fancybox/jquery.fancybox-1.3.1.css');
		 $this->assetlibpro->add_css('style_fancy.css.css');*/
		$this->assetlibpro->add_js('jquery.js');
		$this->assetlibpro->add_js('jquery.autocomplete.js');
		$this->assetlibpro->add_js('jquery.selectboxes.js');
		$this->assetlibpro->add_js('jquery.numberformatter.js');
		$this->assetlibpro->add_js('jquery.AddIncSearch.js');
		$this->assetlibpro->add_js('jquery.validator.js');
		$this->assetlibpro->add_js('jquery.jfield.js');
		$this->assetlibpro->add_js('jquery.date.js');
		$this->assetlibpro->add_js('jquery.form.js');
		/*		$this->assetlibpro->add_js('fancybox/jquery.mousewheel-3.0.2.pack.js');*/
		$this->assetlibpro->add_js('fancybox/jquery.fancybox-1.3.1.js');
		$this->load->model("proveedor");
		$this->load->model("pr_factura");
		$this->load->model("entrada");
		$this->load->model("pago");
		$this->load->model("cpr_forma_pago");
		$this->load->model("lote");
		$this->load->model("espacio_fisico");
		$this->load->model("cpr_estatus_pedido");
		$this->load->model("pr_pedido");
		$this->load->model("tipo_pago");
		$this->load->model("pr_detalle_pedido");
		$GLOBALS['ruta']=$this->uri->segment(1);
		$GLOBALS['controller']=$this->uri->segment(2);
		$GLOBALS['funcion']=$this->uri->segment(3);
		$GLOBALS['subfuncion']=$this->uri->segment(4);
	}

	function pedido_compra(){
		$id=$this->uri->segment(3);
		$verificacion=$this->uri->segment(4);
		if(is_numeric($id)==false)
			show_error('Error 1: El número de pedido de compra no existe'.$id);

		$pedido=$this->pr_pedido->get_pr_pedido_detalles($id);
		if($verificacion!=md5($pedido->id."&".$pedido->razon_social)){
			show_error("El código de seguridad no coincide con el pedido, comuniquese con el área de pedidos de Grupo Pavel");
		}
		$data['title']="Orden de Compra";
		$data['generales']=$this->pr_pedido->get_pr_pedidos_pdf(" where pr.id='$id'", "");
		if($data['generales']==false)
			show_error('El Pedido de compra no existe');

		$data['detalles']=$this->pr_detalle_pedido->get_pr_detalles_pedido_pdf($id);
		if($data['generales']==false)
			show_error('El pedido de compra no tiene detalles en su pedido');

		$this->load->view("compras/rep_pr_pedido", $data);
	}




	function pedido_etiquetas(){
		//Generar el PDF
		if(base_url()=='http://grupopavel1.no-ip.org/pavelerp/')
			$path="/var/www/pavelerp/tmp/";
		else
			$path="/var/www/pavelerp/tmp/";
		$this->load->plugin('barcode');
		//Pedido id;
		$id=$this->uri->segment(3);
		$verificacion=$this->uri->segment(4);
		$pedido=$this->pr_pedido->get_pr_pedido_detalles($id);
		if($verificacion!=md5($pedido->id."&".$pedido->razon_social)){
			show_error("El código de seguridad no coincide con el pedido, comuniquese con el área de pedidos de Grupo Pavel");
		}

		//Obtener el Lote id del pedido
		$l=new Lote_factura();
		$l->where('pr_pedido_id', $id)->get();
		$charl=strlen($l->lote_id);
		$lote=$l->lote_id;
		for($charl;$charl<4;$charl++){
			$lote="0".$lote;
		}
		$lote="1".$lote;
		//Obtener detalles de los productos
		$productos=$this->pr_detalle_pedido->get_pr_detalles_pedido_parent($id);
		//Recorrer los productos
		$x=1;
		foreach($productos->all as $row){
			$char=strlen($row->cproducto_numero_id);
			$numero=$row->cproducto_numero_id;
			for($char;$char<8;$char++){
				$numero="0".$numero;
			}
			for($y=1;$y<=$row->cantidad;$y++){
				barcode_create("$lote$numero","code128","jpeg", 'cb_'.$lote.$numero, $path);
				$codigos[$x]['codigo']="".$lote.$numero;
				$codigos[$x]['ruta']=$path."cb_".$lote.$numero.".jpeg";
				$codigos[$x]['descripcion']=$row->producto." ".($row->numero_mm/10);
				$x+=1;
			}
		}
		//Obtener el Nombre de la Sucursal
		$lote_a=new Lote();
		$lote_a->get_by_id($l->lote_id);
		$suc_hijo=$lote_a->espacio_fisico_inicial_id;
		$efi=new Espacio_Fisico();
		$efi->get_by_id($suc_hijo);
		$ef=$this->espacio_fisico->get_espacio_f($efi->espacio_fisico_matriz_id);
		$data['espacio']=$ef->id;
		$data['lote']=$lote;
		$data['detalles']=$codigos;
		$this->load->library("fpdf_factura");
		$this->load->view('compras/rep_etiquetas_codigo_barras_pdf', $data);
	}

}
?>
