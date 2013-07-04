<?php

/**
 * Lote Class
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Salvador Salgado Ramíre SARS
 * @link
 */
class Lote extends DataMapper {

	var $table= "lotes";

	function  Lote($id=null)
	{
		parent::DataMapper($id);

	}

	// --------------------------------------------------------------------

	/**
	 * Lote
	 *
	 * Authenticates a user for logging in.
	 *
	 * @access	public
	 * @return	bool
	 */
	function get_lotes()
	{
		// Create a temporary user object
		$e = new Lote();
		$e->where('estatus_general_id', "1");

		//Buscar en la base de datos
		$e->get();
		if($e->c_rows>0){
	  return $e;
		} else {
	  return FALSE;
		}
	}

	function get_lotes_list($offset, $per_page)
	{
		// Create a temporary user object
		$u = new Lote();
		$sql="select p.*, mp.tag as marca_lote, psf.tag as subfamilia_lote, eg.tag as estatus, pf.tag as familia_lote, pm.tag as material, pc.tag as color, cmp.tag as marca from clotes as p left join cmarcas_lotes as mp on mp.id=p.cmarca_lote_id left join clotes_subfamilias as psf on psf.id=p.csubfamilia_id left join clotes_familias as pf on pf.id=p.cfamilia_id left join clotes_material as pm on p.cmaterial_id=pm.id left join clotes_color as pc on pc.id=p.ccolor_id left join cmarcas_lotes as cmp on cmp.id=p.cmarca_lote_id left join estatus_general as eg on eg.id=p.estatus_general_id order by p.id desc limit $per_page offset $offset";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_lote($id)
	{
		// Create a temporary user object
		$u = new Lote();
		//Buscar en la base de datos
		$u->get_by_id($id);
		if($u->c_rows ==1){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_lotes_detalles()
	{
		// Create a temporary user object
		$u = new Lote();
		$sql="select p.id as clote_id, pn.id as lote_numero_id, p.descripcion,  'Par' as unidad_medida from clotes as p left join clotes_numeracion as pn on pn.clote_id=p.id where p.estatus_general_id='1' order by descripcion limit 10";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_iva($id)
	{
		// Create a temporary user object
		$u = new Lote();
		//Buscar en la base de datos
		$u->get_by_id($id);
		if($u->c_rows ==1){
			return $u->tasa_impuesto;
		} else {
			return FALSE;
		}
	}
	function get_actualiza_lotes($query)
	{
		// Create a temporary user object
		$u = new Lote();
		//Buscar en la base de datos
		$u->query($query);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_precio1($id)
	{
		// Create a temporary user object
		$u = new Lote();
		//Buscar en la base de datos
		$u->get_by_id($id);
		if($u->c_rows ==1){
			return $u->precio1;
		} else {
			return FALSE;
		}
	}

	function get_lotes_stock()
	{
		// Create a temporary user object
		$u = new Lote();
		$sql="select p.*, p.descripcion, p.clave, p.presentacion, um.tag as unidad_medida, f.tag as familia from clotes as p  left join cunidades_medidas as um on um.id=p.cunidad_medida_id left join clotes_familias as f on f.id=p.cfamilia_id where p.estatus_general_id='1' order by f.tag asc, p.descripcion";
		//Buscar en la base de datos
		$u->query($sql);
		if($u->c_rows > 0){
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_lotes_etiquetas()
	{
		// Create a temporary user object
		$e = new Lote();
		$e->where("estatus_general_id", 1);
		$e->where('codigo_barras >', 0);
		$e->order_by('descripcion');
		//Buscar en la base de datos
		$e->get();
		if($e->c_rows>0){
			return $e;
		} else {
			return FALSE;
		}
	}

	function get_count_traspasos_pendientes($estatus=1,$espacio=0){
		$l= new Lote();
		if ($espacio==0)
			$and = " ";
		else
			$and =" and l.espacio_fisico_inicial_id='$espacio' ";
		$sql="select distinct(lf.lote_id) as total from lotes_pr_facturas as lf left join pr_facturas as pr on pr.id=lf.pr_factura_id left join cproveedores as p on p.id=pr.cproveedores_id left join lotes as l on l.id=lf.lote_id left join espacios_fisicos as ef on ef.id=l.espacio_fisico_inicial_id left join pr_pedidos as ppr on ppr.id=pr.pr_pedido_id left join cmarcas_productos as m on m.id=ppr.cmarca_id left join usuarios as u on u.id=pr.usuario_id where lf.pr_factura_id>0 and lf.cestatus_lote_id='$estatus' and lf.lote_id>0 $and group by lote_id, l.fecha_recepcion, p.razon_social, ef.tag, m.tag,u.username ";
		$l->query($sql);
		return $l->c_rows;
	}

	function get_traspasos_pendientes($espacio, $offset, $per_page){
		$l= new Lote();
		if ($espacio==0)
			$and = " ";
		else
			$and =" and l.espacio_fisico_inicial_id='$espacio' ";
		$sql="select distinct(lf.lote_id) as lote_id, l.fecha_recepcion, sum(pr.monto_total) as monto_total, p.razon_social as proveedor, ef.tag as espacio, m.tag as marca, u.username as usuario from lotes_pr_facturas as lf left join pr_facturas as pr on pr.id=lf.pr_factura_id left join cproveedores as p on p.id=pr.cproveedores_id left join lotes as l on l.id=lf.lote_id left join espacios_fisicos as ef on ef.id=l.espacio_fisico_inicial_id left join pr_pedidos as ppr on ppr.id=pr.pr_pedido_id left join cmarcas_productos as m on m.id=ppr.cmarca_id left join usuarios as u on u.id=pr.usuario_id where lf.pr_factura_id>0 and lf.cestatus_lote_id='1' and lf.lote_id>0 $and group by lote_id, l.fecha_recepcion, p.razon_social, ef.tag, m.tag,u.username order by lote_id  limit $per_page offset $offset";
		$l->query($sql);
		if($l->c_rows>0)
			return $l;
		else
			return FALSE;
	}


	function get_traspasos_pendientes_sucursal($espacio, $estatus=1){
		$l= new Lote();
		$sql="select distinct(lf.lote_id) as lote_id, l.fecha_recepcion, sum(pr.monto_total) as monto_total, p.razon_social as proveedor, ef.tag as espacio, m.tag as marca, u.username as usuario from lotes_pr_facturas as lf left join pr_facturas as pr on pr.id=lf.pr_factura_id left join cproveedores as p on p.id=pr.cproveedores_id left join lotes as l on l.id=lf.lote_id left join espacios_fisicos as ef on ef.id=l.espacio_fisico_inicial_id left join pr_pedidos as ppr on ppr.id=pr.pr_pedido_id left join cmarcas_productos as m on m.id=ppr.cmarca_id left join usuarios as u on u.id=pr.usuario_id where lf.pr_factura_id>0 and lf.cestatus_lote_id='$estatus' and l.espacio_fisico_inicial_id='$espacio' group by lote_id, l.fecha_recepcion, p.razon_social, ef.tag, m.tag,u.username order by lf.lote_id desc";
		$l->query($sql);
		if($l->c_rows>0)
			return $l;
		else
			return FALSE;
	}

	function get_traspasos_pendientes_proveedor($id, $estatus=1){
		$l= new Lote();
		$sql="select distinct(lf.lote_id) as lote_id, l.fecha_recepcion, sum(pr.monto_total) as monto_total, p.razon_social as proveedor, ef.tag as espacio, m.tag as marca, u.username as usuario from lotes_pr_facturas as lf left join pr_facturas as pr on pr.id=lf.pr_factura_id left join cproveedores as p on p.id=pr.cproveedores_id left join lotes as l on l.id=lf.lote_id left join espacios_fisicos as ef on ef.id=l.espacio_fisico_inicial_id left join pr_pedidos as ppr on ppr.id=pr.pr_pedido_id left join cmarcas_productos as m on m.id=ppr.cmarca_id left join usuarios as u on u.id=pr.usuario_id where lf.pr_factura_id>0 and lf.cestatus_lote_id='$estatus' and pr.cproveedores_id='$id' group by lote_id, l.fecha_recepcion, p.razon_social, ef.tag, m.tag,u.username order by lf.lote_id desc ";
		$l->query($sql);
		if($l->c_rows>0)
			return $l;
		else
			return FALSE;
	}
	function get_traspasos_pendientes_marcas($id, $estatus=1){
		$l= new Lote();
		$sql="select distinct(lf.lote_id) as lote_id, l.fecha_recepcion, sum(pr.monto_total) as monto_total, p.razon_social as proveedor, ef.tag as espacio, m.tag as marca, u.username as usuario from lotes_pr_facturas as lf left join pr_facturas as pr on pr.id=lf.pr_factura_id left join cproveedores as p on p.id=pr.cproveedores_id left join lotes as l on l.id=lf.lote_id left join espacios_fisicos as ef on ef.id=l.espacio_fisico_inicial_id left join pr_pedidos as ppr on ppr.id=pr.pr_pedido_id left join cmarcas_productos as m on m.id=ppr.cmarca_id left join usuarios as u on u.id=pr.usuario_id where lf.pr_factura_id>0 and lf.cestatus_lote_id='$estatus' and ppr.cmarca_id='$id' group by lote_id, l.fecha_recepcion, p.razon_social, ef.tag, m.tag,u.username order by lf.lote_id desc";
		$l->query($sql);
		if($l->c_rows>0)
			return $l;
		else
			return FALSE;
	}

	function get_traspaso_pdf($id,$estatus=1){
		$l= new Lote();
		$sql="select distinct(lf.lote_id) as lote_id, l.fecha_recepcion, sum(pr.monto_total) as monto_total, p.razon_social as proveedor, m.tag as marca, u.username as usuario, ef.tag as entrega, ef.id as espacios_fisicos_id, r1.tag as recibe, r1.id as recibe_id, lf.pr_factura_id from lotes_pr_facturas as lf left join pr_facturas as pr on pr.id=lf.pr_factura_id left join cproveedores as p on p.id=pr.cproveedores_id left join lotes as l on l.id=lf.lote_id left join espacios_fisicos as ef on ef.id=l.espacio_fisico_inicial_id left join espacios_fisicos as r1 on r1.id=ef.espacio_fisico_matriz_id left join pr_pedidos as ppr on ppr.id=pr.pr_pedido_id left join cmarcas_productos as m on m.id=ppr.cmarca_id left join usuarios as u on u.id=pr.usuario_id where t.id=$id and lf.cestatus_lote_id='$estatus' and lf.pr_factura_id>0 group by lf.lote_id, l.fecha_recepcion, p.razon_social, ef.tag,ef.id, r1.tag, m.tag,u.username,r1.id, lf.pr_factura_id order by lf.pr_factura_id ";

		$l->query($sql);
		if($l->c_rows>0)
			return $l;
		else
			return FALSE;
	}
	function get_traspaso_entrada_pdf($id,$estatus=2)
	{
		//Objeto de la clase
		$obj = new Lote();
		$sql="select e.*, p.descripcion AS producto, pn.numero_mm as numero, e.pr_facturas_id, lf.cestatus_lote_id from entradas as e LEFT JOIN cproductos as p ON p.id=e.cproductos_id left join cproductos_numeracion as pn on pn.id=e.cproducto_numero_id left join lotes_pr_facturas as lf on lf.pr_factura_id=e.pr_facturas_id where e.lote_id='$id' and e.estatus_general_id='1' and lf.cestatus_lote_id='$estatus' order by producto, pn.id ";
		$obj->query($sql);
		if($obj->c_rows > 0)
			return $obj;
		else
			return false;
	}

	function get_traspasos_enviados($offset, $per_page){
		$l= new Lote();
		$sql="select distinct(lf.lote_id) as lote_id, l.fecha_recepcion, sum(pr.monto_total) as monto_total, p.razon_social as proveedor, ef.tag as espacio, m.tag as marca, u.username as usuario from lotes_pr_facturas as lf left join pr_facturas as pr on pr.id=lf.pr_factura_id left join cproveedores as p on p.id=pr.cproveedores_id left join lotes as l on l.id=lf.lote_id left join espacios_fisicos as ef on ef.id=l.espacio_fisico_inicial_id left join pr_pedidos as ppr on ppr.id=pr.pr_pedido_id left join cmarcas_productos as m on m.id=ppr.cmarca_id left join usuarios as u on u.id=pr.usuario_id where lf.pr_factura_id>0 and lf.cestatus_lote_id='2' group by lf.lote_id, l.fecha_recepcion, p.razon_social, ef.tag, m.tag,u.username order by lf.lote_id desc limit $per_page offset $offset";

		$l->query($sql);
		if($l->c_rows>0)
			return $l;
		else
			return FALSE;
	}
	function get_traspasos_enviados_count(){
		$l= new Lote();
		$sql="select distinct(lf.lote_id) as lote_id from lotes_pr_facturas as lf left join pr_facturas as pr on pr.id=lf.pr_factura_id left join cproveedores as p on p.id=pr.cproveedores_id left join lotes as l on l.id=lf.lote_id left join espacios_fisicos as ef on ef.id=l.espacio_fisico_inicial_id left join pr_pedidos as ppr on ppr.id=pr.pr_pedido_id left join cmarcas_productos as m on m.id=ppr.cmarca_id left join usuarios as u on u.id=pr.usuario_id where lf.pr_factura_id>0 and lf.cestatus_lote_id='2' group by lf.lote_id, l.fecha_recepcion, p.razon_social, ef.tag, m.tag,u.username";
		$l->query($sql);
		if($l->c_rows>0)
			return $l->c_rows;
		else
			return FALSE;
	}
	function get_lote_entrada_local_count($espacio){
		$l= new Lote();
		if ($espacio==0)
			$and = " ";
		else
			$and =" and r1.id='$espacio' ";

		$sql="select distinct(lf.lote_id) as lote_id from lotes_pr_facturas as lf left join pr_facturas as pr on pr.id=lf.pr_factura_id left join cproveedores as p on p.id=pr.cproveedores_id left join lotes as l on l.id=lf.lote_id left join espacios_fisicos as ef on ef.id=l.espacio_fisico_inicial_id left join espacios_fisicos as r1 on r1.id=ef.espacio_fisico_matriz_id left join pr_pedidos as ppr on ppr.id=pr.pr_pedido_id left join cmarcas_productos as m on m.id=ppr.cmarca_id left join usuarios as u on u.id=pr.usuario_id where lf.pr_factura_id>0 and lf.cestatus_lote_id='2' $and group by lf.lote_id";

		$l->query($sql);
		if($l->c_rows>0)
			return $l->c_rows;
		else
			return FALSE;
	}


	function get_lote_entrada_local($offset, $per_page, $espacio){
		$l= new Lote();
		if ($espacio==0)
			$and = " ";
		else
			$and =" and r1.id='$espacio' ";

		$sql="select distinct(lf.lote_id) as lote_id, l.fecha_recepcion, sum(pr.monto_total) as monto_total, p.razon_social as proveedor, m.tag as marca, u.username as usuario, ef.tag as entrega, r1.tag as recibe from lotes_pr_facturas as lf left join pr_facturas as pr on pr.id=lf.pr_factura_id left join cproveedores as p on p.id=pr.cproveedores_id left join lotes as l on l.id=lf.lote_id left join espacios_fisicos as ef on ef.id=l.espacio_fisico_inicial_id left join espacios_fisicos as r1 on r1.id=ef.espacio_fisico_matriz_id left join pr_pedidos as ppr on ppr.id=pr.pr_pedido_id left join cmarcas_productos as m on m.id=ppr.cmarca_id left join usuarios as u on u.id=pr.usuario_id where lf.pr_factura_id>0 and lf.cestatus_lote_id='2' $and group by lf.lote_id, l.fecha_recepcion, p.razon_social, ef.tag, m.tag,u.username,r1.tag order by lote_id asc limit $per_page offset $offset";

		$l->query($sql);
		if($l->c_rows>0)
			return $l;
		else
			return FALSE;
	}
	function get_traspaso_detalles_pdf($id,$estatus)
	{
		//Objeto de la clase
		$obj = new Lote();
		$detalles=$this->db->query("select * from lotes_pr_facturas where lote_id='$id' and cestatus_lote_id='$estatus'");
		if($detalles->num_rows()>0){
			foreach($detalles->result() as $row){
				$facturas[]=$row->pr_factura_id;
			}
			$facturas_sql=" (".implode(",",$facturas).") ";
		}
		$sql="select e.*, p.descripcion AS producto, pn.numero_mm as numero,e.pr_facturas_id from entradas as e LEFT JOIN cproductos as p ON p.id=e.cproductos_id left join cproductos_numeracion as pn on pn.id=e.cproducto_numero_id where e.pr_facturas_id in $facturas_sql and e.estatus_general_id='1' order by e.pr_facturas_id, producto, numero";
		$obj->query($sql);
		if($obj->c_rows > 0)
			return $obj;
		else
			return false;
	}
        function get_traspaso_por_id($id){
            $ent= new Lote();
            $sql="select * from traspasos where id=$id";
            $ent->query($sql);
            if($ent->c_rows>0)
                    return $ent;
            else
                    return FALSE;
        }
	function get_traspaso_salidas($id){
            $sal = new Lote();
            $sql = "select s.*,p.descripcion,pn.numero_mm as numero,p.precio1 as precio 
                        from salidas as s 
                        join cproductos as p 
                        on p.id=s.cproductos_id
                        join cproductos_numeracion as pn on pn.id=s.cproducto_numero_id	
                        where s.traspaso_id =$id";
            $sal->query($sql);
            if($sal->c_rows>0)
                return $sal;
            else
                return false;
        }
	function get_entrada_traspaso_local($offset, $per_page, $espacio, $estatus, $envio){
		$l= new Lote();//Envio "1 = a esta tienda", "2 = desde esta tienda"
		if ($envio == 1)
                    $and = " where t.espacio_fisico_recibe_id = '$espacio' ";
                    
		else if ($envio == 2)
                    $and = " where t.espacio_fisico_envia_id = '$espacio' ";
                
                $and = $and."and cestatus_traspaso_id = '$estatus' ";
                $sql = "select " .
                "t.id,".                
                "t.fecha_envio,".
                "t.fecha_recibe,".
                "efe.tag as espacio_envia,".
                "efr.tag as espacio_recibe,".
                "et.tag as estatus,".
                "u.nombre as usuario ".
                "from traspasos as t ".
                "join espacios_fisicos as efe on efe.id=t.espacio_fisico_envia_id ".
                "join espacios_fisicos as efr on efr.id=t.espacio_fisico_recibe_id ".
                "join cestatus_traspasos as et on et.id=t.cestatus_traspaso_id ".
                "join usuarios as u on u.id=t.usuario_id ".
                $and.
                "order by t.id desc limit $per_page offset $offset";

		$l->query($sql);
		if($l->c_rows>0)
			return $l;
		else
			return FALSE;
	}
	function get_entrada_traspaso_local_count($espacio){
		$l= new Lote();
		if ($espacio==0)
			$and = " ";
		else
			$and =" where espacio_fisico_recibe_id='$espacio' ";

		$sql="select count(t.id) from traspasos as t join espacios_fisicos as efe on efe.id=t.espacio_fisico_envia_id join espacios_fisicos as efr on efr.id=t.espacio_fisico_recibe_id join cestatus_traspasos as et on et.id=t.cestatus_traspaso_id join usuarios as u on u.id=t.usuario_id $and";
                $l->query($sql);
		if($l->c_rows>0)
			return $l->count;
		else
			return FALSE;
	}

}
