<?php
class Factura extends Controller
{ // BEGIN class Factura

function Factura()
{ // BEGIN constructor
parent::Controller();
if($this->session->userdata('logged_in') == FALSE){
	redirect(base_url()."index.php/inicio/logout");
}
$this->load->library('fpdf_factura');
$this->load->model("cl_factura");
$this->load->model("espacio_fisico");
$user_hash=$this->session->userdata('user_data');
$row=$this->usuario->get_usuario_detalles($user_hash);
$GLOBALS['usuarioid']=$row->id;
$GLOBALS['espacio']=$this->espacio_fisico->get_espacio_f($row->espacio_fisico_id);

} // END constructor ################################

function generar()
{ // BEGIN method generar
// obtener el id de la factura a generar
$id = (int)$this->uri->segment(4);
// obtener los datos de la factura
$f=$this->cl_factura->get_cl_factura($id);
if($f==false)
	show_error('No existe el registro especificado');
// obtener los datos del cliente
$c = new Cliente();
$c->get_by_id($f->cclientes_id);
if(!$c->exists())
	show_error('No existe el cliente especificado');
// obtener los detalles de la factura
$sql= "select s.cantidad, p.clave, p.descripcion, um.tag, s.costo_unitario, (s.cantidad*costo_unitario) as costo_total, (s.costo_total*s.tasa_impuesto/(100+16)) as iva_total from salidas as s left join cproductos as p on p.id=s.cproductos_id left join cunidades_medidas as um on p.cunidad_medida_id=um.id where cl_facturas_id= '$id'";
$query = $this->db->query($sql);
$reporte = array();
$reporte['espacio'] = $GLOBALS['espacio'];
$reporte['factura'] = $f;
$reporte['cliente'] = $c;
$reporte['conceptos'] = $query->result();
$this->load->view('ventas/factura_pdf',$reporte);
} // END method generar #########################################################

} // END class Factura
/* End of file factura.php */
?>
