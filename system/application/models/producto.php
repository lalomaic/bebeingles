<?php

/**
 * Productos Class
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Salvador Salgado Ramíre SARS
 * @link
 */
class Producto extends DataMapper {

	var $table = "cproductos";
	var $has_many = array(
			'entrada' => array(
					'class' => 'entrada',
					'other_field' => 'cproductos'
			),
			'salida' => array(
					'class' => 'salida',
					'other_field' => 'cproductos'
			),
			'promocion' => array(
					'class' => 'promocion',
					'other_field' => 'cproducto'
			),
			'view_entradas_y_salidas' => array(
					'class' => 'view_entradas_y_salidas',
					'other_field' => 'cproductos'
			)
	);

	function Producto($id=null) {
		parent::DataMapper($id);
	}

	// --------------------------------------------------------------------

	// Productos	 
	function get_cproductos() {
		// Create a temporary user object
		$e = new Producto();
		$e->where('estatus_general_id', "1");

		//Buscar en la base de datos
		$e->get();
		if ($e->c_rows > 0) {
			return $e;
		} else {
			return FALSE;
		}
	}

	function get_productos() {
		$this->get_cproductos();
	}

	function get_productos_list($offset, $per_page) {
		// Create a temporary user object
		$u = new Producto();
		$sql = "select p.*, mp.tag as marca_producto, psf.tag as subfamilia_producto, eg.tag as estatus, pf.tag as familia_producto, pc.tag as color, cmp.tag as marca  from cproductos as p left join cmarcas_productos as mp on mp.id=p.cmarca_producto_id left join cproductos_subfamilias as psf on psf.id=p.csubfamilia_id left join cproductos_familias as pf on pf.id=p.cfamilia_id left join cproductos_color as pc on pc.id=p.ccolor_id left join cmarcas_productos as cmp on cmp.id=p.cmarca_producto_id left join estatus_general as eg on eg.id=p.estatus_general_id where p.estatus_general_id='1' and (p.status = '1' or p.status isnull) and p.combo='0' order by p.descripcion asc limit $per_page offset $offset";
		//Buscar en la base de datos
		$u->query($sql);
		if ($u->c_rows > 0) {
			return $u;
		} else {
			return FALSE;
		}
	}

function get_productos_list_combo($offset, $per_page) {
		// Create a temporary user object
		$u = new Producto();
		$sql = "select p.*, mp.tag as marca_producto, psf.tag as subfamilia_producto, eg.tag as estatus, pf.tag as familia_producto, pc.tag as color, cmp.tag as marca  from cproductos as p left join cmarcas_productos as mp on mp.id=p.cmarca_producto_id left join cproductos_subfamilias as psf on psf.id=p.csubfamilia_id left join cproductos_familias as pf on pf.id=p.cfamilia_id left join cproductos_color as pc on pc.id=p.ccolor_id left join cmarcas_productos as cmp on cmp.id=p.cmarca_producto_id left join estatus_general as eg on eg.id=p.estatus_general_id where p.estatus_general_id='1' and (p.status = '1' or p.status isnull) and p.combo=1 order by p.descripcion asc limit $per_page offset $offset";
		//Buscar en la base de datos
		$u->query($sql);
		if ($u->c_rows > 0) {
			return $u;
		} else {
			return FALSE;
		}
	}




	function get_producto($id) {
		// Create a temporary user object
		$u = new Producto();
		//Buscar en la base de datos
		$u->get_by_id($id);
		if ($u->c_rows == 1) {
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_cproductos_detalles() {
		// Create a temporary user object
		$u = new Producto();
		$sql = "select p.id as cproducto_id, pn.id as producto_numero_id, p.descripcion, 'Par' as unidad_medida, pn.numero_mm,mp.tag as marca_producto, psf.tag as subfamilia_producto, eg.tag as estatus, pf.tag as familia_producto, pm.tag as material, pc.tag as color from cproductos as p left join cproductos_numeracion as pn on pn.cproducto_id=p.id left join cmarcas_productos as mp on mp.id=p.cmarca_producto_id left join cproductos_subfamilias as psf on psf.id=p.csubfamilia_id left join cproductos_familias as pf on pf.id=p.cfamilia_id left join cproductos_material as pm on p.cmaterial_id=pm.id left join cproductos_color as pc on pc.id=p.ccolor_id left join cmarcas_productos as cmp on cmp.id=p.cmarca_producto_id left join estatus_general as eg on eg.id=p.estatus_general_id where p.estatus_general_id='1' order by familia_producto, descripcion";
		//Buscar en la base de datos
		$u->query($sql);
		if ($u->c_rows > 0) {
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_iva($id) {
		// Create a temporary user object
		$u = new Producto();
		$u->select('tasa_impuesto');
		//Buscar en la base de datos
		$u->get_by_id($id);
		if ($u->c_rows == 1) {
			return $u->tasa_impuesto;
		} else {
			return FALSE;
		}
	}

	function get_actualiza_productos($query) {
		// Create a temporary user object
		$u = new Producto();
		//Buscar en la base de datos
		$u->query($query);
		if ($u->c_rows > 0) {
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_precio1($id) {
		// Create a temporary user object
		$u = new Producto();
		//Buscar en la base de datos
		$u->get_by_id($id);
		if ($u->c_rows == 1) {
			return $u->precio1;
		} else {
			return FALSE;
		}
	}

	function get_cproductos_stock() {
		// Create a temporary user object
		$u = new Producto();
		$sql = "select p.*, p.descripcion, p.clave, p.presentacion, um.tag as unidad_medida, f.tag as familia from cproductos as p  left join cunidades_medidas as um on um.id=p.cunidad_medida_id left join cproductos_familias as f on f.id=p.cfamilia_id where p.estatus_general_id='1' order by f.tag asc, p.descripcion";
		//Buscar en la base de datos
		$u->query($sql);
		if ($u->c_rows > 0) {
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_cproductos_etiquetas() {
		// Create a temporary user object
		$e = new Producto();
		$e->where("estatus_general_id", 1);
		$e->where('codigo_barras >', 0);
		$e->order_by('descripcion');
		//Buscar en la base de datos
		$e->get();
		if ($e->c_rows > 0) {
			return $e;
		} else {
			return FALSE;
		}
	}

 function get_cproductos_combo_count() {
        $p = new Producto();
        $sql = "select count(id) as total from cproductos where estatus_general_id=1 and combo=1";
        $p->query($sql);
        if ($p->c_rows > 0) {
            return $p->total;
        } else {
            return 0;
        }
    }
        

        function cobarras_producto($id, $pn) {
        $u = new Producto();
        $sql = "select pn.codigo_barras as cod,pn.cproducto_id as id,p.descripcion as tag,p.precio1 as precio, pn.numero_mm as numeracion
                from cproductos_numeracion as pn
                join cproductos as p on p.id=pn.cproducto_id
                where 
                cproducto_id='$id'
                and
                pn.id='$pn'";

        $u->query($sql);
        if ($u->c_rows > 0) {
            return $u;
        } else {
            return FALSE;
        }
    }

	function get_productos_departamento($id) {
		// Create a temporary user object
		$u = new Producto();
		$sql = "select p.*, mp.tag as marca_producto, psf.tag as subfamilia_producto, eg.tag as estatus, pf.tag as familia_producto, pc.tag as color, cmp.tag as marca from cproductos as p left join cmarcas_productos as mp on mp.id=p.cmarca_producto_id left join cproductos_subfamilias as psf on psf.id=p.csubfamilia_id left join cproductos_familias as pf on pf.id=p.cfamilia_id  left join cproductos_color as pc on pc.id=p.ccolor_id left join cmarcas_productos as cmp on cmp.id=p.cmarca_producto_id left join estatus_general as eg on eg.id=p.estatus_general_id  where p.estatus_general_id='1' and p.cfamilia_id=$id order by p.descripcion";
		//Buscar en la base de datos
		$u->query($sql);
		if ($u->c_rows > 0) {
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_productos_marca($id) {
		// Create a temporary user object
		$u = new Producto();
		$sql = "select p.*, mp.tag as marca_producto, psf.tag as subfamilia_producto, eg.tag as estatus, pf.tag as familia_producto, pc.tag as color, cmp.tag as marca from cproductos as p left join cmarcas_productos as mp on mp.id=p.cmarca_producto_id left join cproductos_subfamilias as psf on psf.id=p.csubfamilia_id left join cproductos_familias as pf on pf.id=p.cfamilia_id left join cproductos_color as pc on pc.id=p.ccolor_id left join cmarcas_productos as cmp on cmp.id=p.cmarca_producto_id left join estatus_general as eg on eg.id=p.estatus_general_id where p.estatus_general_id='1' and p.cmarca_producto_id=$id order by p.descripcion";
		//Buscar en la base de datos
		$u->query($sql);
		if ($u->c_rows > 0) {
			return $u;
		} else {
			return false;
		}
	}

	function get_productos_material($id) {
		// Create a temporary user object
		$u = new Producto();
		$sql = "select p.*, mp.tag as marca_producto, psf.tag as subfamilia_producto, eg.tag as estatus, pf.tag as familia_producto, pm.tag as material, pc.tag as color, cmp.tag as marca,ct.tag as temporada from cproductos as p left join cmarcas_productos as mp on mp.id=p.cmarca_producto_id left join cproductos_subfamilias as psf on psf.id=p.csubfamilia_id left join cproductos_familias as pf on pf.id=p.cfamilia_id left join cproductos_material as pm on p.cmaterial_id=pm.id left join cproductos_color as pc on pc.id=p.ccolor_id left join cmarcas_productos as cmp on cmp.id=p.cmarca_producto_id left join estatus_general as eg on eg.id=p.estatus_general_id   left join cproductos_temporada as ct on ct.id=p.cproductos_temporada_id where p.estatus_general_id='1' and p.cmaterial_id=$id order by p.descripcion";
		//Buscar en la base de datos
		$u->query($sql);
		if ($u->c_rows > 0) {
			return $u;
		} else {
			return FALSE;
		}
	}

	function get_cproductos_ordered() {
		$e = new Producto();
		$e->where('estatus_general_id', "1");
		$e->order_by("descripcion", "asc");
		//Buscar en la base de datos
		$e->get();
		if ($e->c_rows > 0) {
			return $e;
		} else {
			return FALSE;
		}
	}

	function get_cproductos_count() {
		$p = new Producto();
		$sql = "select count(id) as total from cproductos where estatus_general_id=1";
		$p->query($sql);
		if ($p->c_rows > 0) {
			return $p->total;
		} else {
			return 0;
		}
	}
function get_cproductos_count_combo() {
		$p = new Producto();
		$sql = "select count(id) as total from cproductos where estatus_general_id=1 and combo=1";
		$p->query($sql);
		if ($p->c_rows > 0) {
			return $p->total;
		} else {
			return 0;
		}
	}

	function get_productos_autocomplete($var, $familia, $subfamilia) {
		$producto = new Producto();
		$producto->select("id AS producto_id, descripcion AS descr")
		->where("cfamilia_id", $familia)
		->where("csubfamilia_id", $subfamilia)
		->ilike("descripcion", $var)->get();
		if ($producto->c_rows > 0) {
			return $producto;
		} else {
			return FALSE;
		}
	}

	function get_productos_autocomp ($var) {
		$producto = new Producto();
		$sql = "select p.id, p.descripcion, num.numero_mm, num.codigo_barras, num.id as nid
                        from cproductos as p 
                        join cproductos_numeracion as num on num.cproducto_id=p.id
                        where p.estatus_general_id='1' and p.descripcion like '%$var%'  order by p.descripcion ";
                
		//Buscar en la base de datos
		$producto->query($sql);
		if ($producto->c_rows > 0) {
			return $producto;
		} else {
			return FALSE;
		}
	}
	
        function get_productos_as_array() {
		$productos = new Producto();
		$productos->
		order_by('descripcion')->get();
		$array_productos = array();
		foreach($productos as $producto)
			$array_productos[$producto->id] = $producto->descripcion;
		return $array_productos;
	}

	function get_productos_proveedor($id) {
		$p = new Producto();
		$sql = "select
		m.proveedor_id as proveedor
		from
		cproductos as p
		left join
		cmarcas_productos as m on m.id = p.cmarca_producto_id
		where
		p.id = $id";
		$p->query($sql);
		if ($p->c_rows > 0) {
			return $p->proveedor;
		} else {
			return 0;
		}
	}
        
        function get_productos_por_descripcion($descripcion){
            $productos = new Producto();
		$sql = "select * from cproductos where descripcion  like '%$descripcion%'";
		$productos->query($sql);
		return $productos;		
        }
        
        function get_existencia_productos_por_espacio($espacio) {
            $productos = new Producto();
            $query = "select sum(e.cantidad)-sum(s.cantidad) as existencia_sistema,e.cproducto_numero_id as producto_id,
                        e.cproducto_numero_id as numero_id, p.precio1 as costo_unitario
                        from entradas as e 
                        join salidas as s on s.cproductos_id = e.cproductos_id and e.cproducto_numero_id = s.cproducto_numero_id 
                        join cproductos as p on p.id = e.cproducto_numero_id
                        where s.espacios_fisicos_id = '$espacio' and e.espacios_fisicos_id = '$espacio'
                        group by   e.cproducto_numero_id, s.cproducto_numero_id,p.precio1";
            return $productos->query($query);
        }

}
