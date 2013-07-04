<?php
class Ajax_pet extends Controller {
	function Ajax_pet()
	{
		parent::Controller();
		if($this->session->userdata('logged_in') == FALSE){
			redirect(base_url()."index.php/inicio/logout");
		}
		$this->load->model("ajax_mod");
		$this->load->model("producto_numeracion");
		$this->load->model("pago");
		$this->load->model("cobro");
		$this->load->model("almacen");
		$this->load->model("cl_factura");
		$this->load->model("cuenta_bancaria");
		$this->load->model("cuenta_contable");
		$user_hash=$this->session->userdata('user_data');
		$row=$this->usuario->get_usuario_detalles($user_hash);
		$GLOBALS['usuarioid']=$row->id;
		$GLOBALS['ubicacion_id']=$row->espacio_fisico_id;
		$GLOBALS['empresa_id']=$row->empresas_id;
		$GLOBALS['username']=$row->username;
		$GLOBALS['ruta']=$this->uri->segment(1);
		$GLOBALS['controller']=$this->uri->segment(2);
		$GLOBALS['funcion']=$this->uri->segment(3);

	}
	function iva(){
		$id=$_POST['enviarvalor'];
		$linea=$_POST['linea'];
		$p= new Producto();
		$p->get_by_id($id);
		if($p->c_rows ==1) {
	  $iva=$p->tasa_impuesto;
	  echo "<input class=\"subtotal\" value=\"$iva\" name=\"iva$linea\" id=\"tasa_imp$linea\" size=\"2\" />";
	  $p->clear();
	  //echo "<input value=\"$iva\" name=\"iva$line\" id=\"tasa_imp$line\" size=\"2\" class=\"subtotal\">";
		} else {
			echo "error";
			//show_error("".$u->error->string);
		}
	}
	function iva1(){
		$id=$_POST['enviarvalor'];
		$options="";
		$p= new Producto();
		$p->get_by_id($id);
		if($p->c_rows ==1) {
			echo $p->tasa_impuesto;
			$p->clear();
		} else {
			echo "Error";
			//show_error("".$u->error->string);
		}
	}
	function fact(){
		//Obitene las Facturas de un Proveedor -> select
		$id=$_POST['enviarvalor'];
		$options="";
		$select=$this->ajax_mod->get_pr_facturas_select($id);
		echo "<select name=\"pr_facturas_id\">";
		if($select!=false) {
			foreach($select->all as $row){
				echo "<option value=\"$row->id\">$row->folio_factura: $ $row->monto_total </option>";
			}
		} else {
			echo "<option value=\"0\">Sin facturas</option>";
		}
		echo "</select>";
	 unset($select);
	}

    function get_comisiones_bancarias() {
        $id = $_POST['id'];
        $comisiones = new Cuenta_comision();
        $p = $comisiones->get_by_id($id);
        $json = "{'id':'$p->id', 
                'banco_id':'$p->banco_id',
                'debito':'$p->debito',
                'credito0':'$p->credito_0m',
                'credito3':'$p->credito_3m',
                'credito6':'$p->credito_6m',
                'credito9':'$p->credito_9m',
                'credito12':'$p->credito_12m'}";
        echo $json;
    }


	function pagos_factura(){
		//Obitene los pagos realisados a una Factura de proveedor teniendo el id de la FActura
		$id=$_POST['enviarvalor'];
		$pagos=$this->pago->get_pagos_by_factura_id($id);
		if ($pagos==false){
			echo "No existen abonos a la factura indicada.";
		} else {
			$factura=$this->pr_factura->get_pr_factura($id);
			echo "<table width='900' align='center'><tr><th>Id Pago</th><th>Fecha</th><th>Referencia</th><th>Cuentas de Origen</th><th>Monto</th></tr> \n";
		 $total=0;
		 foreach($pagos->all as $row){
                     $p2=number_format($row->monto_pagado,2,'.',','); 
		 	echo "<tr><td align='center'>$row->id</td><td align='center'>$row->fecha</td><td align='center'>$row->numero_referencia</td><td align='center'>$row->banco- $row->numero_cuenta</td><td align='right'>$p2</td></tr> \n";
		 	$total+=$row->monto_pagado;
                        
                       
		 }
                 $p1=number_format($factura->monto_total,2); 
                 $adeudo=number_format($factura->monto_total-$total,2);
		 echo "<tr><th></th><th></th><th></th><th align='right'>Adeudo</th><th align='right'>".($adeudo)."</th></tr> \n";
		 echo "</table>";
		}
	}

	function pagos_factura_multiple(){
		//Obitene los pagos realizados a una Factura de proveedor teniendo el id de la FActura form alta_multiple_pago
		$id=$_POST['enviarvalor'];
		$pagos=$this->pago->get_pagos_by_factura_id($id);
		if ($pagos==false){
			echo "No existen abonos a la factura indicada.";
		} else {
			$factura=$this->pr_factura->get_pr_factura($id);
			echo "<table width='500' align='center'><tr><th>Id Pago</th><th>Fecha</th><th>Referencia</th><th>Cuentas de Origen</th><th>Monto</th></tr> \n";
		 $total=0;
		 foreach($pagos->all as $row){
                     $p2=number_format($row->monto_pagado,2,'.',','); 
		 	echo "<tr><td align='center'>$row->id</td><td align='center'>$row->fecha</td><td align='center'>$row->numero_referencia</td><td align='center'>$row->banco- $row->numero_cuenta</td><td align='right'>$p2</td></tr> \n";
		 	$total+=$row->monto_pagado;
                        
                       
		 }
                 $p1=number_format($factura->monto_total,2); 
                 $adeudo=number_format($factura->monto_total-$total,2);
		 echo "<tr><th></th><th></th><th></th><th align='right'>Adeudo</th><th align='right'>".($adeudo)."</th></tr> \n";
		 echo "</table>";
		}
	}
	function cobros_factura(){
		//Obitene los pagos realisados a una Factura de proveedor teniendo el id de la FActura
		$id=$_POST['enviarvalor'];
		$pagos=$this->cobro->get_cobros_by_factura_id($id);
		if ($pagos==false){
			echo "No existen abonos a la factura indicada.";
		} else {
			$factura=$this->cl_factura->get_cl_factura($id);
			echo "<table width='900' align='center'><tr><th>Id Pago</th><th>Fecha</th><th>Referencia</th><th>Monto</th></tr> \n";
		 $total=0;
		 foreach($pagos->all as $row){
		 	echo "<tr><td align='center'>$row->id</td><td align='center'>$row->fecha</td><td align='center'>$row->numero_referencia</td><td align='right'>$row->monto_pagado</td></tr> \n";
		 	$total+=$row->monto_pagado;
		 }
		 echo "<tr><th></th><th></th><th align='right'>Adeudo</th><th align='right'>" .($factura->monto_total-$total). "</th></tr> \n";
		 echo "</table>";
		}
	}

	function get_facturas_pagos(){
            //OBtiene un listado de facturas de un proveedor dado para formar un select box
            $id=$this->uri->segment(3);
            $pr_factura_id=$this->uri->segment(4);
            if($id>0){
		$select=$this->ajax_mod->get_pr_facturas_select($id, $pr_factura_id);
                if($select!=false){
                    $x=0;
                    $j=array();
                    foreach($select->all as $row){
	  		$j[$x]="'$row->id': '$row->folio_factura - $ ". number_format($row->monto_total, 2, ".",",") ." '";
	  		$x+=1;
                    }
                    $json= "{". implode(", ", $j). "}";
                    echo $json;
                } 
                else { echo "{'0':'Sin facturas'}"; }
            } else { echo "{'0':'Sin facturas'}"; }
	}
	function get_facturas_cobros(){
		//OBtiene un listado de facturas de un cliente dado para formar un select box
		$id=$this->uri->segment(3);
		if($id>0){
	  $select=$this->ajax_mod->get_cl_facturas_select($id);
	  if($select!=false){
	  	$x=0;
	  	$j=array();
	  	foreach($select->all as $row){
	  		$j[$x]="'$row->id': '$row->folio_factura - $ $row->monto_total '";
	  		$x+=1;
	   }
	   $json= "{". implode(", ", $j). ", '0':'', }";
	   echo $json;
	  } else  {
	  	echo "{'0':'Sin facturas'}";
	  }
		} else {
			echo "{'0':'Sin facturas'}";
		}
		unset($select);
	}
	
function get_cuenta_like() {
        $cta = new Cuenta_contable();
        $cta->where('scta', 0)->like('tag', $_POST['q'])->get();
        echo "[";
        if (count($cta->all) == 0)
            echo "{id:'0',descripcion:'Sin cuentas'}";
        else {
            $temp = "";
            foreach ($cta as $cuenta)
                $temp .= "{id:'$cuenta->cta',descripcion:'$cuenta->cta: $cuenta->tag',tipo_cuenta: $cuenta->ctipo_cuenta_contable_id},";
            echo substr($temp, 0, strlen($temp) - 1);
        }
        echo "]";
    }

     function get_cuenta_by_id($id = 0) {
        $cta = new Cuenta_contable();
        $cta->where('cta', $id)->where('scta', 0)->get();
        if (count($cta->all) != 1)
            echo "{id:'0'}";
        else
            echo json_encode(array("id" => $cta->cta, "descripcion" => "$cta->cta: $cta->tag", "tipo_cuenta" => $cta->ctipo_cuenta_contable_id));
    }

    function get_scuenta_like() {
        $cta = new Cuenta_contable();
        $cta->where('cta', $_POST['cta'])->where('scta !=', 0)->where('sscta', 0)->like('tag', $_POST['q'])->get();
        echo "[";
        if (count($cta->all) == 0)
            echo "{id:'0',descripcion:'Sin subcuentas'}";
        else {
            $temp = "";
            foreach ($cta as $cuenta)
                $temp .= "{id:'$cuenta->scta',descripcion:'$cuenta->scta: $cuenta->tag'},";
            echo substr($temp, 0, strlen($temp) - 1);
        }
        echo "]";
    }

    function get_scuenta_by_id($id = 0, $cuenta = 0) {
               $scta = new Cuenta_contable();
        $scta->where('cta', $cuenta)->where('scta', $id)->where('sscta', 0)->where('ssscta', 0)->get();
        if (count($scta->all) != 1){
            echo "{id:'0'}";
        } else{
            $sscta = new Cuenta_contable();
            $sscta->where('cta', $cuenta)->where('scta', $id)->get();
            $ssub = array();
            foreach ($sscta as $sub)
                $ssub[$sub->id] = $sub->sscta.' : '.$sub->tag;
            echo json_encode(array(
                "id" => $scta->scta, 
                "descripcion" => "$scta->scta: $scta->tag",
                "subsubcuentas" => $ssub));
        }
    }

    function get_sscuenta_like() {
        $cta = new Cuenta_contable();
        $cta->where('cta', $_POST['cta'])->where('scta', $_POST['scta'])->where('ssscta', 0)->where('sscta !=', 0)->like('tag', $_POST['q'])->get();
        echo "[";
        if (count($cta->all) == 0)
            echo "{id:'0',descripcion:'Sin sub-subcuentas'}";
        else {
            $temp = "";
            foreach ($cta as $cuenta)
                $temp .= "{id:'$cuenta->sscta',descripcion:'$cuenta->sscta: $cuenta->tag'},";
            echo substr($temp, 0, strlen($temp) - 1);
        }
        echo "]";
    }

    function get_sscuenta_by_id($id = 0, $subcuenta = 0, $cuenta = 0) {
        $scta = new Cuenta_contable();
        $scta->where('cta', $cuenta)->where('scta', $subcuenta)->where('sscta', $id)->where('ssscta', 0)->get();
        if (count($scta->all) != 1)
            echo "{id:'0'}";
        else
            echo json_encode(array("id" => $scta->sscta, "descripcion" => "$scta->sscta: $scta->tag"));
    }
	
	
	
	function get_cuentas_prov(){
		//OBtiene un listado de las cuentas bancarias de un proveedor
		$id=$this->uri->segment(3);
		if($id>0){
	  $select=$this->cuenta_bancaria->get_cuentas_bancarias_prov($id);
	  if($select!=false){
	  	$x=0;
	  	$j=array();
	  	foreach($select->all as $row){
	  		$j[$x]="'$row->id': '$row->banco - $row->numero_cuenta'";
	  		$x+=1;
	   }
	   $json= "{". implode(", ", $j). "}";
	   echo $json;
	  } else  {
	  	echo "{'0':'Sin Cuenta Bancaria Caso 1'}";
	  }
		} else {
			echo "{'0':'Sin Cuenta Bancaria Caso 2'}";
		}
	}

	function get_cuentas_cliente(){
		//OBtiene un listado de las cuentas bancarias de un cliente
		$id=$this->uri->segment(3);
		if($id>0){
	  $select=$this->cuenta_bancaria->get_cuentas_bancarias_cliente($id);
	  if($select!=false){
	  	$x=0;
	  	$j=array();
	  	foreach($select->all as $row){
	  		$j[$x]="'$row->id': '$row->banco - $row->numero_cuenta'";
	  		$x+=1;
	   }
	   $json= "{". implode(", ", $j). "}";
	   echo $json;
	  } else  {
	  	echo "{'0':'Sin Cuenta Bancaria Caso 1'}";
	  }
		} else {
			echo "{'0':'Sin Cuenta Bancaria Caso 2'}";
		}
	}

        
           
    function get_codbar_by_producto(){
			$producto_id=$_POST['enviarvalor'];
			 $query=$this->db->query("select codigo_barras from cproductos_numeracion where cproducto_id=$producto_id ");
		$producto=$query->row();
                
		if(strlen($producto->codigo_barras)>4)
			echo $producto->codigo_barras;
		else
			echo "Sin Cod. Bar.";
	}
        
	function borrar_detalle(){
		/* Funcion para borrar los detalles dentro de los subformularios*/
		$id=$_POST['enviarvalor'];
		$line=$_POST['linea'];
		if($id>0){
			$this->db->query("update pr_detalle_pedidos set estatus_general_id='2' where id='$id'");
			echo form_hidden('id'.$line, "0"); echo form_hidden('pr_pedidos_id'.$line, "0"); echo "<img src=\"".base_url()."images/trash.png\" width=\"20px\" title=\"Eliminado\"/>";
		} else {
			echo "El registro no ha sido previamente guardado";
		}
	}

	###### Funcion para eliminar los detalles del subformulario de recetas	04/ago/10
	function elimina_detalle(){
		/* Funcion para borrar los detalles dentro de los subformularios*/
		$id=$_POST['enviarvalor'];
		$line=$_POST['linea'];
		if($id>0){
			$this->db->query("delete from receta_detalles where id='$id'");
			echo form_hidden('id'.$line, "0"); echo form_hidden('receta_id'.$line, "0"); echo "<img src=\"".base_url()."images/trash.png\" width=\"20px\" title=\"Eliminado\"/>";
		} else {
			echo "El registro no ha sido previamente guardado";
		}
	}
	###### Fin Funcion para eliminar los detalles del subformulario de recetas 04/ago/10

	function baja_detalle(){
		/* Funcion para borrar los detalles de cualquier subformulario recibiendo el caso espefico*/
		$caso=$this->uri->segment(3);
		$id1=$_POST['arg_id1'];
		$id2=$_POST['arg_id2'];
		$line=$_POST['linea'];
		if($caso=="caso_1"){
			//ALta de Salida de Traspaso: tablas => cl_detalle_pedidos y salidas, id1= salida, id2=cl_detalle_pedidos
			if($id2>0){
				$this->db->query("delete from cl_detalle_pedidos where id=$id2");
				$this->db->query("delete from salidas where id=$id1");
				$this->db->query("delete from traspasos_salidas where salidas_id=$id1");

				echo form_hidden('id'.$line, "0"); echo form_hidden('id'.$line, "0"); echo form_hidden('cl_detalle_pedido_id'.$line, "0"); echo "<img src=\"".base_url()."images/cancelado.png\" width=\"20px\" title=\"Cancelado\"/>";

			}
		} else if($caso=="caso_2"){
			//ALta de Salida de Traspaso: tablas => cl_detalle_pedidos y salidas, id1= salida, id2=cl_detalle_pedidos
			if($id1>0){
				$this->db->query("delete from salidas where id=$id1");
				$this->db->query("delete from traspasos_salidas where salidas_id=$id1");
				echo form_hidden('id'.$line, "0"); echo form_hidden('id'.$line, "0"); echo "<img src=\"".base_url()."images/cancelado.png\" width=\"20px\" title=\"Cancelado\"/>";

			}
		} else if($caso=="caso_3"){
			//Editar Pedido de Compra: tablas => pr_detalle_pedidos
			if($id1>0){
				$this->db->query("delete from pr_detalle_pedidos where id=$id1");
				echo form_hidden('id'.$line, "0"); echo form_hidden('id'.$line, "0"); echo form_hidden('pr_pedidos_id'.$line, "0"); echo "<img src=\"".base_url()."images/cancelado.png\" width=\"20px\" title=\"Cancelado\"/>";

			}
		}


	}
	function clave(){
		/* Funcion para checar la clave del producto*/
		$clave=$_POST['enviarvalor'];
		$obj=$_POST['obj'];
		$p=new Producto();
		$p->where('clave', "$clave");
		$p->get();
		if($p->c_rows>0){
			echo "<h4>La clave ingresada ya esta en uso, agrege un caracter adicional o ingrese una diferente</h4>";
		} else { echo "bien";
		}
	}
	function subfamilias(){
		/* Funcion para filtrar select de subfamilias de producto*/
		$cfamilia_id=$_POST['enviarvalor'];
		$p=new Subfamilia_producto();
		$p->where('familia_id', "$cfamilia_id");
		$p->get();
		if($p->c_rows>0){
			echo "<select name='csubfamilia_id' id='subfamilias_productos'>";
			foreach($p->all as $row){
				echo "<option value='$row->id'>$row->tag</option>";
			}
			echo "</select>";
		} else {
			echo "Sin Entradas";
		}
	}

	function rfc(){
		/* Funcion para filtrar select de subfamilias de producto*/
		$rfc=$_POST['enviarvalor'];
		$obj=$_POST['obj'];
		$p=new Proveedor();
		$p->where('rfc', "$rfc");
		$p->get();
		if($p->c_rows>0){
			echo "<h4>El RFC se encuentra duplicada</h4>";
		}
	}
        
        function rfc_cliente(){
		/* Funcion para filtrar select de subfamilias de producto*/
		$rfc=$_POST['enviarvalor'];
		$obj=$_POST['obj'];
		$p=new Cliente();
		$p->where('rfc', "$rfc");
		$p->get();
		if($p->c_rows>0){
			echo "<h4>El RFC se encuentra duplicada Ver Sus Datos</h4>";
                        foreach($p->all as $row){
				echo "<p>$row->id.- $row->razon_social - $row->rfc </p>";
                         echo "<a href=\"". base_url() . "index.php/ventas/clientes_c/formulario/list_clientes/editar_cliente/$row->id\" target='_blank'> Ir a datos RFC </a>";
                        
		}
                }else{
                    echo "";
                }
	}
	
	
	
	function get_empleados_ajax_autocomplete(){
		//OBtiene un listado de empleados formato json
		$var=$_POST['q'];
		$query=$this->db->query("select id, (nombre || ' ' || apaterno || ' ' || amaterno) as nombre from empleados where nombre ilike '%$var%' or apaterno ilike '%$var%' or amaterno ilike '%$var%' and estatus_general_id=1 order by nombre, apaterno, amaterno");
		if($query->num_rows()>0){
			$x=0;
			$j=array();
			foreach($query->result() as $row){
				$j[$x]="{\"id\" : \"$row->id\", \"descripcion\" : \"$row->nombre\"}";
				$x+=1;
			}
		$json= "[". implode(", ", $j). "]";
		} else  {
			$json="[{'id':'0', 'descripcion':'No encontrado'}]";
		}
		echo $json;
	}

        
	
	
	
        
        function razon_social(){
            $razon_social=strtoupper(trim($_POST['enviarvalor']));
		$c=new Cliente();
		$c->like('razon_social', "$razon_social");
		$c->get();
		if($c->c_rows>0){
			echo "<h4>Existen Razones Sociales similares, revise que no este generando un proveedor duplicado.</h4>";
			foreach($c->all as $row){
				echo "<p>$row->id.- $row->razon_social - $row->rfc </p>";
			}
		} else {
			echo "";
		}
        }
	function proveedor(){
		/* Funcion para checar el nombre de un proveedor*/
		$proveedor=strtoupper(trim($_POST['enviarvalor']));
		$p=new Proveedor();
		$p->like('razon_social', "$proveedor");
		$p->get();
		if($p->c_rows>0){
			echo "<h4>Existen Razones Sociales similares, revise que no este generando un proveedor duplicado.</h4>";
			foreach($p->all as $row){
				echo "<p>$row->id.- $row->razon_social - $row->rfc </p>";
			}
		} else {
			echo "";
		}
	}


	function marca(){
		/* Funcion para checar la marcas del producto*/
		$clave=strtoupper(trim($_POST['enviarvalor']));
		$p=new Marca_producto();
		$p->like('tag', "$clave");
		$p->get();
		if($p->c_rows>0){
			echo "<h4>Existen marcas similares";
			foreach($p->all as $row){
				echo "<p>$row->id.- $row->tag </p>";
			}
		} else
			echo "";
	}

	function precios1(){
		$id=$this->uri->segment(3);
		$line=$this->uri->segment(4);
		$options="";
		$select=$this->ajax_mod->get_select_precios($id);
		if($select!=false){
	  $json="";
	  foreach($select->all as $row){
	  	$json="{'". ($row->precio1-($row->precio1*$row->tasa_impuesto/100)) ."':'Precio 1: ". ($row->precio1-($row->precio1*$row->tasa_impuesto/100)) ."', '". ($row->precio2-($row->precio2*$row->tasa_impuesto/100)) ."':'Precio 2: ". ($row->precio2-($row->precio2*$row->tasa_impuesto/100)) ."', '". ($row->precio3-($row->precio3*$row->tasa_impuesto/100)) ."':'Precio 3: ". ($row->precio3-($row->precio3*$row->tasa_impuesto/100)) ."'}";
	  }
	  echo $json;
		} else  {

			echo "error";
			//show_error("".$u->error->string);
		}
	}

	function existencia(){
		$id=$_POST['enviarvalor'];
		$cantidad=$_POST['unidades'];
		$exist=$this->almacen->existencias($id, 'where espacios_fisicos_id='.$GLOBALS['ubicacion_id']);
		$comp=$this->almacen->producto_comprometido($id);
		echo $exist['existencias'];
	}
	function get_subfamilias()
	{ // BEGIN method get_subfamilias
		$fam=(int)$this->input->post('fam_id');
		$nombre=$this->input->post('nombre');
		$cond=array('cproducto_familia_id'=>$fam,'estatus_general_id'=>'1');
		$subfamilias=$this->db->select('id,tag')->where($cond)->order_by('tag')->get('cproductos_subfamilias')->result();
		if(count($subfamilias)==0)
			echo '[ Seleccione una familia ]';
		else
		{
			echo "<select name='$nombre' id='$nombre' style='width:200px'>\n";
			echo "<option value='0'>Todos</option>\n";
			foreach($subfamilias as $row)
				echo "<option value='$row->id'>$row->tag</option>\n";
			echo '</select>';
		}
	} // END method get_subfamilias #########################################################

	function get_productos()
	{ // BEGIN method get_productos
		$return_arr=array();
		$var=strtoupper($_POST['q']);
		$pid= (int) $_POST['q'];
		if($pid==0 or $pid=='')
			$and1="";
		else
			$and1=" or p.id='$var' ";
			
                                         //select p.id, p.descripcion from cproductos as p where p.descripcion  like '%CON%'  and p.estatus_general_id='1' order by p.descripcion 
		$query=$this->db->query("select p.id, p.descripcion from cproductos as p  where (p.descripcion  like '%$var%'  $and1) and p.estatus_general_id='1' order by p.descripcion ");
		$productos=$query->result();
		$x=0;
		$j=array();
		if($query->num_rows()>0){
			foreach($productos as $row){
				$j[$x]="{\"id\" :\"$row->id\", \"descripcion\":\"$row->descripcion\"}";
				$x+=1;
			}
			$json= "[". implode(", ", $j). "]";
		} else {
			$json="[{\"id\": \"0\", \"descripcion\":\"No encontrado\"}]";
		}
		echo $json;

		//echo json_encode($return_arr);
	} // END method get_productos #########################################################

	function get_numeracion()
	{ // BEGIN method get_productos
		//$return_arr=array();
		$pid=$_POST['arg_id1'];
		//$line=$_POST['linea'];
		$query=$this->db->query("select id, numero_mm, clave_anterior from cproductos_numeracion where cproducto_id='$pid' order by numero_mm");
                $queryPrecio = $this->db->query("select precio_compra, fecha_ultima_compra from cproductos where id ='$pid'");
                $precio =$queryPrecio->row();
                $x=0;
		$j=array();
		if($query->num_rows()>0){
			foreach($query->result() as $row){
				$j[$x]="{\"id\":\"$row->id\", \"descripcion\":\" $row->clave_anterior - #". ($row->numero_mm/10)."\"}";
				$x+=1;
			}
			$json= "{\"datos\": [". implode(", ", $j). "], \"rows\" : [ {\"valor\" : \"$x\" }],".
                                " \"precio\":\"$precio->precio_compra\", \"fecha_compr\":\"$precio->fecha_ultima_compra\" }";
		} else {
			$json=false;
		}
		echo $json;
		//echo json_encode($return_arr);
	} // END method get_productos #########################################################

        
        function get_numeracion_salida_traspaso()
	{ // BEGIN method get_productos
		//$return_arr=array();
		$pid=$_POST['arg_id1'];
		//$line=$_POST['linea'];
		$query=$this->db->query("select id, numero_mm, clave_anterior from cproductos_numeracion where cproducto_id='$pid' order by numero_mm");
                $queryPrecio = $this->db->query("select precio_compra,  from cproductos where id ='$pid'");
                $precio =$queryPrecio->row();
                $x=0;
		$j=array();
		if($query->num_rows()>0){
			foreach($query->result() as $row){
				$j[$x]="{\"id\":\"$row->id\", \"descripcion\":\" $row->clave_anterior - #". ($row->numero_mm/10)."\"}";
				$x+=1;
			}
			$json= "{\"datos\": [". implode(", ", $j). "], \"rows\" : [ {\"valor\" : \"$x\" }],".
                                " \"precio\":\"$precio->precio_compra\", \"fecha_compr\":\"$precio->fecha_ultima_compra\" }";
		} else {
			$json=false;
		}
		echo $json;
		//echo json_encode($return_arr);
	} // END method get_productos #########################################################

        
        
	function get_espacios()
	{ // BEGIN method get_espacios
		$empresa=(int)$this->input->post('empresa_id');
		$nombre=$this->input->post('nombre');
		$cond=array('empresas_id'=>$empresa,'estatus_general_id'=>'1');
		$result=$this->db->select('id,tag')->where($cond)->order_by('tag')->get('espacios_fisicos')->result();
		if(count($result)==0)
			echo '[ Seleccione una empresa ]';
		else
		{
			echo "<select name='$nombre' id='$nombre' style='width:200px'>\n";
			foreach($result as $row)
				echo "<option value='$row->id'>$row->tag</option>\n";
			echo '</select>';
		}
	} // END method get_espacios #########################################################

	function get_espacios_todos()
	{ // BEGIN method get_espacios with option ALL's
		$empresa=(int)$this->input->post('empresa_id');
		$nombre=$this->input->post('nombre');
		$cond=array('empresas_id'=>$empresa,'estatus_general_id'=>'1');
		$result=$this->db->select('id,tag')->where($cond)->order_by('tag')->get('espacios_fisicos')->result();
		if(count($result)==0)
			echo '[ Seleccione una empresa ]';
		else
		{
			echo "<select name='$nombre' id='$nombre' style='width:200px'><option value='0'>Todos</option>\n";
			foreach($result as $row)
				echo "<option value='$row->id'>$row->tag</option>\n";
			echo '</select>';
		}
	} // END method get_espacios #########################################################

	function get_espacios_venta()
	{ // BEGIN method get_espacios with option ALL's
		$empresa=(int)$this->input->post('empresa_id');
		$nombre=$this->input->post('nombre');
		$cond=array('empresas_id'=>$empresa,'estatus_general_id'=>'1','tipo_espacio_id'=> '2');
		$result=$this->db->select('id,tag')->where($cond)->order_by('tag')->get('espacios_fisicos')->result();
		if(count($result)==0)
			echo '[ Seleccione una empresa ]';
		else
		{
			echo "<select name='$nombre' id='$nombre' style='width:200px'><option value='0'>Todos</option>\n";
			foreach($result as $row)
				echo "<option value='$row->id'>$row->tag</option>\n";
			echo '</select>';
		}
	} // END method get_espacios #########################################################

	function get_cliente(){
		/* Funcion para obtener el nombre de un proveedor*/
		$cliente=$_POST['id'];
		$p=new Cliente();
		$p->get_by_id($cliente);
		if($p->c_rows==1){
			echo "<h4>RFC: $p->rfc <br/>Domicilio Fiscal: $p->domicilio <br/> C.P.: $p->codigo_postal<br/> $p->municipio, $p->estado</h4>";
		} else {
			echo "Datos no encontrados";
		}
	}


	function get_facturas_devolucion(){
		//OBtiene un listado de facturas de un cliente para devoluciondentro de un select box
		$id=$this->uri->segment(3);
		if($id>0){
	  $select=$this->ajax_mod->get_cl_facturas_dev($id);
	  if($select!=false){
	  	$x=0;
	  	$j=array();
	  	foreach($select->all as $row){
	  		$j[$x]="'$row->cl_facturas_id': '$row->folio_factura - $ $row->importe_factura'";
	  		$x+=1;
	   }
	   $json= "{". implode(", ", $j). ", '0':'Elija la Factura', }";
	   echo $json;
	  } else  {
	  	echo "{'0':'Sin facturas'}";
	  }
		} else {
			echo "{'0':'Sin facturas'}";
		}
	}
	function get_facturas_devolucion_compras(){
		//OBtiene un listado de facturas de un cliente para devoluciondentro de un select box
		$id=$this->uri->segment(3);
		if($id>0){
	  $select=$this->ajax_mod->get_pr_facturas_dev($id);
	  if($select!=false){
	  	$x=0;
	  	$j=array();
	  	foreach($select->all as $row){
	  		$j[$x]="'$row->pr_facturas_id': '$row->folio_factura - $ $row->importe_factura'";
	  		$x+=1;
	   }
	   $json= "{". implode(", ", $j). ", '0':'Elija la Factura', }";
	   echo $json;
	  } else  {
	  	echo "{'0':'Sin facturas'}";
	  }
		} else {
			echo "{'0':'Sin facturas'}";
		}
	}

	//Obtiene las subcuentas del catalogo de cuentas contables para ingresar en un select
	function get_subcuentas(){
		//OBtiene un listado de facturas de un cliente para devoluciondentro de un select box
		$id=$this->uri->segment(3);
		if($id>0){
			$select=$this->cuenta_contable->get_cuentas_contables_filtrado($id, $GLOBALS['empresa_id']);
			if($select!=false){
				$x=0;
				$j=array();
				foreach($select->all as $row){
					$j[$x]="'$row->id': '$row->scta $row->sscta $row->sscta - $row->tag'";
					$x+=1;
				}
				$json= "{". implode(", ", $j). ", '0':'Elija la Subcuenta', }";
				echo $json;
			} else  {
				echo "{'0':'Sin Subcuentas1'}";
			}
		} else {
			echo "{'0':'Sin Subcuentas2'}";
		}
	}
	
	 function get_consecutivo_diario() {
        $query_con = $this->db->query(
                        "SELECT
                    max(consecutivo) AS consecutivo
                FROM
                   polizas
                WHERE
                    anio = " . $_POST['anio'] . "
                    AND mes = " . $_POST['mes'] . "
                    AND ctipo_poliza_id = 3;")->row();
        if ($query_con->consecutivo == null || $query_con->consecutivo < cal_days_in_month(CAL_GREGORIAN, $_POST['mes'], $_POST['anio'])) {
            printf('%04d', $resultado = cal_days_in_month(CAL_GREGORIAN, $_POST['mes'], $_POST['anio']) + 1);
        } else
            printf('%04d', $query_con->consecutivo + 1);
    }


        function get_subcuentas_poliza() {
        //OBtiene un listado de facturas de un cliente para devoluciondentro de un select box
        if ($_POST['cta'] > 0) {
            $select = $this->cuenta_contable->get_cuentas_contables_ajax($_POST['cta'], $GLOBALS['empresa_id'], $_POST['q']);
            if ($select != false) {
                $x = 0;
                $j = array();
                foreach ($select->all as $row) {
                    $j[$x++] = "{'id':'$row->id', 'descripcion':'$row->descripcion'}";
                }
                $json = "[" . implode(", ", $j) . "]";
                echo $json;
            } else {
                echo "[{'id': 0, 'descripcion':'Sin cuentas'}]";
            }
        } else {
            echo "[{'id': 0, 'descripcion':'Seleccione una cuenta'}]";
        }
    }
	
 function get_consecutivo_egreso() {
        $query_con = $this->db->query(
                        "SELECT
                    max(consecutivo) AS consecutivo
                FROM
                    polizas
                WHERE
                    ctipo_poliza_id = 2
                    AND anio = {$_POST['anio']}
                    AND mes = {$_POST['mes']}
                    AND banco = {$_POST['banco']}
                    AND empresa_id = {$GLOBALS['empresa_id']};")->row();
        echo sprintf('%03d', $query_con->consecutivo + 1);
    }	
	
	
 function get_consecutivo_ingreso() {
        $dias_mes = cal_days_in_month(CAL_GREGORIAN, $_POST['mes'], $_POST['anio']);
        $query_con = $this->db->query(
                        "SELECT
                    max(consecutivo) AS consecutivo
                FROM
                    polizas
                WHERE
                    ctipo_poliza_id = 1
                    AND anio = {$_POST['anio']}
                    AND mes = {$_POST['mes']}
                    AND empresa_id = {$GLOBALS['empresa_id']};")->row();
        $this->load->model('espacio_fisico');
        $apartado = $this->espacio_fisico->get_espacios_by_empresa_count("{$_POST['anio']}-{$_POST['mes']}-1") * $dias_mes;
        if ($query_con->consecutivo == null || $query_con->consecutivo < $apartado)
            printf('%04d', $apartado + 1);
        else
            printf('%04d', $query_con->consecutivo + 1);
    }	
	

	function get_marcas_ajax(){
		//OBtiene un listado de facturas de un proveedor dado para formar un select box
		$var=strtoupper($_POST['q']);
		$pid=$_POST['pid'];
		if ($pid==0 or $pid=='')
			$pro="";
		else
			$pro=" and proveedor_id='$pid' ";
		$query=$this->db->query("select id, tag as marca from cmarcas_productos where estatus_general_id=1 and tag like '%$var%' $pro order by tag asc");
		if($query->num_rows()>0){
			$x=0;
			$j=array();
			foreach($query->result() as $row){
				$j[$x]="{\"id\" : \"$row->id\", \"descripcion\" : \"$row->marca\"}";
				$x+=1;
			}
			$json= "[". implode(", ", $j). ", {\"id\" : \"0\", \"descripcion\" : \"TODAS\"} ]";

		} else  {
			$json="[{'id':'0', 'descripcion':'TODAS'}]";
		}
		echo $json;
	}

	function get_proveedores_ajax()
	{ // BEGIN method get_clientes
		$var=strtoupper($_POST['q']);
		$query=$this->db->query("select id, (razon_social || ' - ' || clave) as proveedor from cproveedores where estatus_general_id=1 and razon_social like '%$var%' order by razon_social ");
		$x=0;
		$j=array();
		if($query->num_rows()>0){
			foreach($query->result() as $row){
				$j[$x]="{'id':'$row->id', 'descripcion':'$row->proveedor'}";
				$x+=1;
			}
			$json= "[". implode(", ", $j). "]";
		} else {
			$json="[{'id':'0', 'descripcion':'TODOS'}]";
		}
		echo $json;
	} // END method get_clientes #########################################################

	function get_proveedor_marca_ajax()
	{ // BEGIN method get_clientes

		$return_arr=array();
		$mid=$_POST['arg_id1'];
		$query=$this->db->query("select p.id, (razon_social || ' - ' || clave) as proveedor from cproveedores as p left join cmarcas_productos as m on m.proveedor_id = p.id where m.id=$mid and m.estatus_general_id=1 and p.estatus_general_id=1");
		$x=0;
		$j=array();
		if($query->num_rows()==1){
			foreach($query->result() as $row){
				$j[$x]="{\"id\" : \"$row->id\", \"descripcion\" : \"$row->proveedor\"}";
				$x+=1;
			}
			$json= "[". implode(", ", $j). "]";
		} else {
			$json="[{'id':'0', 'descripcion':'TODAS'}]";
		}
		echo $json;

	}

	function get_material_ajax(){
		//OBtiene un listado de facturas de un proveedor dado para formar un select box
		$var=strtoupper($_POST['q']);
		$query=$this->db->query("select id, tag as material from cproductos_material where estatus_general_id=1 and tag like '%$var%' order by tag asc");
		if($query->num_rows()>0){
			$x=0;
			$j=array();
			foreach($query->result() as $row){
				$j[$x]="{\"id\" : \"$row->id\", \"descripcion\" : \"$row->material\"}";
				$x+=1;
			}
			$json= "[". implode(", ", $j). ", {\"id\" : \"0\", \"descripcion\" : \"TODAS\"} ]";

		} else  {
			$json="[{'id':'0', 'descripcion':'No encontrado'}]";
		}
		echo $json;
	}

        function get_subfamilia_ajax(){
 $var=strtoupper($_POST['q']);
		$query=$this->db->query("select id, tag as subfamilia from cproductos_subfamilias where estatus_general_id=1 and tag like '%$var%' order by tag asc");
		if($query->num_rows()>0){
			$x=0;
			$j=array();
			foreach($query->result() as $row){
				$j[$x]="{\"id\" : \"$row->id\", \"descripcion\" : \"$row->subfamilia\"}";
				$x+=1;
			}
			$json= "[". implode(", ", $j). ", {\"id\" : \"0\", \"descripcion\" : \"TODAS\"} ]";

		} else  {
			$json="[{'id':'0', 'descripcion':'No encontrado'}]";
		}
		echo $json;
	}
        

 function get_productos_combo() {
		// BEGIN method get_productos
		$return_arr=array();
		$var=strtoupper($_POST['q']);
		$pid=$_POST['pid'];
		if($pid==0 or $pid=='')
			$and1="";
		else
			$and1=" and cp.id='$pid' ";
		$mid=$_POST['mid'];
		if($mid==0 or $mid=='')
			$and2="";
		else
			$and2=" and p.cmarca_producto_id='$mid' ";
                
		$query=$this->db->query("select p.id, p.descripcion, pn.numero_mm, pn.id as nid,pn.codigo_barras as cod from cproductos as p left join cmarcas_productos as m on m.id=p.cmarca_producto_id left join cproveedores as cp on cp.id=m.proveedor_id left join cproductos_numeracion as pn on pn.cproducto_id=p.id where p.descripcion like '%$var%' $and1 $and2  and p.estatus_general_id=1  order by p.descripcion, pn.numero_mm ");
		$productos=$query->result();
		$x=0;
		$j=array();
		if($query->num_rows()>0){
			foreach($productos as $row){
				$j[$x]="{\"id\" :\"$row->id\",  \"codigo\" :\"$row->cod\",   \"nid\" :\"$row->nid\", \"descripcion\":\"$row->descripcion # ".($row->numero_mm)."\" }";
				$x+=1;
			}
			$json= "[". implode(", ", $j). "]";
		} else {
			$json="[{\"id\": \"0\", \"nid\" :\"0\", \"codigo\" :\"Sin codigo de Barras\", \"descripcion\":\"No encontrado\"}]";
		}
		echo $json;

		//echo json_encode($return_arr);
	} // END method get_productos #########################################################


        
	function subir_foto(){
		$id=$this->uri->segment(3);
		if($id>0){
			//Proceder a llamar al archivo
			$file = $_FILES['file'];
			$config['field_name']  = 'file';
			$config['upload_path'] = 'tmp/';
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			$config['max_size']	= '1000000';
			$config['max_width']  = '4024';
			$config['max_height']  = '3768';

			$this->load->library('upload', $config);
			if (! $this->upload->do_upload('file')){
				$error = array('error_field' => $this->upload->display_errors());
				$this->load->view('error', $error);
				$pass=0;
			} else {
				$file_info=$this->upload->data();
				$source=$file_info['full_path'];
				//echo "info0=".$file_info0['full_path'];
				//	  print_r(array_keys($file_info0));
				//Proceder a mover el archivo de tmp a la ubicacion final
				if(base_url()=='http://localost/bebe_ingles/')
					$destino="/var/www/bebe_ingles/images/productos/{$id}_img.".$file_info['image_type'];
				else
					$destino="/var/www/bebe_ingles/images/productos/{$id}_img.".$file_info['image_type'];

				$ruta_foto="images/producto/{$id}_img.".$file_info['image_type'];
				$archivo="{$id}_img.".$file_info['image_type'];
				if (!copy($source, $destino)) {
					echo "Fallo el almacenamiento del archivo \n";
				} else {
					$ruta_foto="images/productos/{$id}_img.".$file_info['image_type'];
					$p=$this->producto->get_by_id($id);
					if($p->id!='' or $p->id>0){
						$p->ruta_foto=$ruta_foto;
						$p->save();
						echo '{"name":"'.$file['name'].'","type":"'.$file['type'].'","size":"'.$file['size'].'","rutafoto":"'.$archivo.'"}';

					} else
						show_error("Error: producto no encontrado");
				}

			}
		}
	}

	function get_familias_ajax(){
		//OBtiene un listado de facturas de un proveedor dado para formar un select box
		$var=strtoupper($_POST['q']);
		$query=$this->db->query("select id, tag as familia from cproductos_familias where estatus_general_id=1 and tag like '%$var%' order by tag asc");
		if($query->num_rows()>0){
			$x=0;
			$j=array();
			foreach($query->result() as $row){
				$j[$x]="{\"id\" : \"$row->id\", \"descripcion\" : \"$row->familia\"}";
				$x+=1;
			}
			$json= "[". implode(", ", $j). ", {\"id\" : \"0\", \"descripcion\" : \"TODAS\"} ]";

		} else  {
			$json="[{'id':'0', 'descripcion':'No encontrado'}]";
		}
		echo $json;
	}

	function color() {
		/* Funcion para checar la marcas del producto */
		$clave = trim($_POST['enviarvalor']);
		$permitidos = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-_ ";
		for ($i = 0; $i < strlen($clave); $i++) {
			if (strpos($permitidos, substr($clave, $i, 1)) === false) {
				echo  "<h4>El color contiene caracteres inválidos</h4>";

			}
		}

		$clave=strtoupper($clave);
		$p = new Color_producto();
		$p->where('tag', "$clave");
		$p->get();
		if ($p->c_rows > 0) {
			echo "<h4>El color ya existe";
		}
	}
	function coloredit(){
		$clave = trim($_POST['enviarvalor']);
		$permitidos = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-_ ";
		for ($i = 0; $i < strlen($clave); $i++) {
			if (strpos($permitidos, substr($clave, $i, 1)) === false) {
				echo  "<h4>El color contiene caracteres inválidos</h4>";

			}
		}
	}

	function get_espacios_ajax(){
		//OBtiene un listado de facturas de un proveedor dado para formar un select box
		$var=strtoupper($_POST['q']);
		$query=$this->db->query("select id, tag as espacio from espacios_fisicos where estatus_general_id=1 and tag like '%$var%' order by tag asc");
		if($query->num_rows()>0){
			$x=0;
			$j=array();
			foreach($query->result() as $row){
				$j[$x]="{\"id\" : \"$row->id\", \"descripcion\" : \"$row->espacio\"}";
				$x+=1;
			}
			$json= "[". implode(", ", $j). ", {\"id\" : \"0\", \"descripcion\" : \"TODAS\"} ]";

		} else  {
			$json="[{'id':'0', 'descripcion':'No encontrado'}]";
		}
		echo $json;
	}



        function get_cuentas_bancarias_ajax(){
		//OBtiene un listado de facturas de un proveedor dado para formar un select box
		$var=strtoupper($_POST['q']);
		$query=$this->db->query("select id, banco from ccuentas_bancarias where estatus_general_id=1 and banco like '%$var%' order by banco asc");
		if($query->num_rows()>0){
			$x=0;
			$j=array();
			foreach($query->result() as $row){
				$j[$x]="{\"id\" : \"$row->id\", \"descripcion\" : \"$row->banco\"}";
				$x+=1;
			}
			$json= "[". implode(", ", $j). ", {\"id\" : \"0\", \"descripcion\" : \"TODAS\"} ]";

		} else  {
			$json="[{'id':'0', 'descripcion':'No encontrado'}]";
		}
		echo $json;
	}
        
        function get_num_cuentas_ajax(){
		//OBtiene un listado de facturas de un proveedor dado para formar un select box
		$var=strtoupper($_POST['q']);
		$query=$this->db->query("select id, numero_cuenta from ccuentas_bancarias where estatus_general_id=1 and numero_cuenta like '%$var%' order by numero_cuenta asc");
		if($query->num_rows()>0){
			$x=0;
			$j=array();
			foreach($query->result() as $row){
				$j[$x]="{\"id\" : \"$row->id\", \"descripcion\" : \"$row->numero_cuenta\"}";
				$x+=1;
			}
			$json= "[". implode(", ", $j). ", {\"id\" : \"0\", \"descripcion\" : \"TODAS\"} ]";

		} else  {
			$json="[{'id':'0', 'descripcion':'No encontrado'}]";
		}
		echo $json;
	}
        
        
        function get_clave_bancaria_ajax(){
		//OBtiene un listado de facturas de un proveedor dado para formar un select box
		$var=strtoupper($_POST['q']);
		$query=$this->db->query("select id, clabe from ccuentas_bancarias where estatus_general_id=1 and clabe like '%$var%' order by clabe asc");
		if($query->num_rows()>0){
			$x=0;
			$j=array();
			foreach($query->result() as $row){
				$j[$x]="{\"id\" : \"$row->id\", \"descripcion\" : \"$row->clabe\"}";
				$x+=1;
			}
			$json= "[". implode(", ", $j). ", {\"id\" : \"0\", \"descripcion\" : \"TODAS\"} ]";

		} else  {
			$json="[{'id':'0', 'descripcion':'No encontrado'}]";
		}
		echo $json;
	}
        
        function get_clientes_ajax(){
		//OBtiene un listado de facturas de un proveedor dado para formar un select box
		$var=strtoupper($_POST['q']);
		$query=$this->db->query("select id, razon_social as cliente from cclientes where estatus_general_id=1 and razon_social like '%$var%' order by id asc");
		if($query->num_rows()>0){
			$x=0;
			$j=array();
			foreach($query->result() as $row){
				$j[$x]="{\"id\" : \"$row->id\", \"descripcion\" : \"$row->cliente\"}";
				$x+=1;
			}
			$json= "[". implode(", ", $j). ", {\"id\" : \"0\", \"descripcion\" : \"TODAS\"} ]";

		} else  {
			$json="[{'id':'0', 'descripcion':'No encontrado'}]";
		}
		echo $json;
	}
        
        
	function get_empresa_ajax(){
		//OBtiene un listado de facturas de un proveedor dado para formar un select box
		$var=strtoupper($_POST['q']);
		$query=$this->db->query("select id, razon_social as empresa from empresas where estatus_general_id=1 and  razon_social like '%$var%' order by razon_social asc");
		if($query->num_rows()>0){
			$x=0;
			$j=array();
			foreach($query->result() as $row){
				$j[$x]="{\"id\" : \"$row->id\", \"descripcion\" : \"$row->empresa\"}";
				$x+=1;
			}
			$json= "[". implode(", ", $j). ", {\"id\" : \"0\", \"descripcion\" : \"TODOS\"} ]";

		} else  {
			$json="[{'id':'0', 'descripcion':'No encontrado'}]";
		}
		echo $json;
	}





	function get_proveedores_ajax_autocomplete(){
		//OBtiene un listado de facturas de un proveedor dado para formar un select box
		$var=strtoupper($_POST['q']);
		$query=$this->db->query("select id, razon_social as proveedor from cproveedores where estatus_general_id=1 and  razon_social like '%$var%' order by razon_social asc");
		if($query->num_rows()>0){
			$x=0;
			$j=array();
			foreach($query->result() as $row){
				$j[$x]="{\"id\" : \"$row->id\", \"descripcion\" : \"$row->proveedor\"}";
				$x+=1;
			}
			$json= "[". implode(", ", $j). ", {\"id\" : \"0\", \"descripcion\" : \"TODOS\"} ]";

		} else  {
			$json="[{'id':'0', 'descripcion':'No encontrado'}]";
		}
		echo $json;
	}
        
        function get_precio_compra_indirecto(){
            $productoId = $_POST['enviarvalor'];
            $producto = new Producto();
            echo $producto->get_producto($productoId)->precio_compra;	   
        }
        
        function get_producto_detalles(){
            $productoId = $_POST['enviarvalor'];
            $prod_nume=$_POST['prod'];
            $prod_nume=  explode("# ", $prod_nume);            
            $producto = new Producto();
            $p=$producto->get_producto($productoId);
            $num = new Producto_numeracion();
            $n=$num->get_numeracion_by_prod_val($productoId, $prod_nume[1]);
            //$numBar = ($num->num_rows() > 0) ? "results": 'No Results';
            $json="{'precio':'$p->precio_compra', 'cod_bar':'$n'}";
            echo $json;
        }
        
        function get_producto_by_cod_bar(){
            $codigo_barras = $_POST['enviarvalor'];
            $query=$this->db->query("select 
                    p.id, p.descripcion, p.precio_compra,
                    pn.numero_mm,
                    pn.id as nid 
                    from 
                    cproductos as p 
                    left join cproductos_numeracion as pn 
                    on pn.cproducto_id=p.id 
                    where 
                    pn.codigo_barras = '$codigo_barras'");
            if($query->num_rows()==1){
		foreach($query->result() as $p){
                        $json="{'precio':'$p->precio_compra',".
                                "'descripcion':'$p->descripcion # $p->numero_mm',".
                                "'id':'$p->id','nid':'$p->nid','cantidad':'1'}";                        
                    }
		} 
            else if ($query->num_rows()>1) 
                $json="{'precio':'0','cantidad':'0','id':'0','nid':'0',".
                    "'descripcion':'Existe mas de un producto con este codigo'}";
            else 
                $json="{'precio':'0','cantidad':'0','id':'0','nid':'0',".
                    "'descripcion':'Este codigo no esta asignado a ningun producto'}";
            
            echo $json;           
		
        }

function get_prod_exist_by_cod_bar(){
    $codigo_barras = $_POST['enviarvalor'];
    $espacio_id = $GLOBALS['ubicacion_id'];
    /*$query=$this->db->query("
                        select c.tag,p.id as pid, n.id as nid, n.numero_mm, n.codigo_barras, p.descripcion
                        from cproductos  as p
                        join cproductos_numeracion as n on p.id = n.cproducto_id
                        join cproductos_color as c on p.ccolor_id = c.id
                        where codigo_barras = '$codigo_barras' ");*/
$query=$this->db->query("select * from existencia_detalle
			            where codigo_barras = '$codigo_barras' and sum > 0 and espacio_fisico = $espacio_id ");
    if($query->num_rows()==1){
        foreach($query->result() as $row){
                /*$json="{'descripcion':'$p->descripcion # ".
                        ($p->numero_mm)." - ".$p->tag."',".
                        "'id':'$p->pid','nid':'$p->nid'}"; */
		   $json="{\"id\" :\"$row->pid\",
                                        \"nid\" :\"$row->nid\",
                                        \"descripcion\":\"$row->descripcion # ".$row->talla." - ".$row->color."\",
		                        \"existencia\":\"$row->sum\",
                                        \"cod_bar\":\"$row->codigo_barras \"}";                    
            }
        } 
    else if ($query->num_rows()>1) 
        $json="{'cantidad':'0','id':'0','nid':'0','existencia':'0',".
            "'descripcion':'Existe mas de un producto con este codigo'}";
    else 
        $json="{'cantidad':'0','id':'0','nid':'0','existencia':'0',".
            "'descripcion':'Este producto no tiene existencia'}";

    echo $json;                
}

function get_productos_numeracion() {
		// BEGIN method get_productos
		$return_arr=array();
		$var=strtoupper($_POST['q']);
		$pid=$_POST['pid'];
		if($pid==0 or $pid=='')
			$and1="";
		else
			$and1=" and cp.id='$pid' ";
		$mid=$_POST['mid'];
		if($mid==0 or $mid=='')
			$and2="";
		else
			$and2=" and p.cmarca_producto_id='$mid' ";
                
		$query=$this->db->query("select p.id, p.descripcion, pn.numero_mm, pn.id as nid,pn.codigo_barras as cod from cproductos as p left join cmarcas_productos as m on m.id=p.cmarca_producto_id left join cproveedores as cp on cp.id=m.proveedor_id left join cproductos_numeracion as pn on pn.cproducto_id=p.id where p.descripcion like '%$var%' $and1 $and2  and p.estatus_general_id=1 order by p.descripcion, pn.numero_mm ");
		$productos=$query->result();
		$x=0;
		$j=array();
		if($query->num_rows()>0){
			foreach($productos as $row){
				$j[$x]="{\"id\" :\"$row->id\",  \"codigo\" :\"$row->cod\",   \"nid\" :\"$row->nid\", \"descripcion\":\"$row->descripcion # ".($row->numero_mm)."\" }";
				$x+=1;
			}
			$json= "[". implode(", ", $j). "]";
		} else {
			$json="[{\"id\": \"0\", \"nid\" :\"0\", \"codigo\" :\"Sin codigo de Barras\", \"descripcion\":\"No encontrado\"}]";
		}
		echo $json;

		//echo json_encode($return_arr);
	}  // END method get_productos #########################################################
	
	function get_productos_existecia_entradas_numeracion() {
		 $var=strtoupper($_POST['q']);
    $espacio =  $_POST['espacio'];
    $espacio_id = $GLOBALS['ubicacion_id'];
		 $query=$this->db->query("select * 
		 									from existencia_detalle
			            where descripcion like '%$var%' and sum > 0 and espacio_fisico = $espacio_id ");
    
			$productos=$query->result();
		$x=0;
		$j=array();
		if($query->num_rows()>0){
			foreach($productos as $row){
                            $bar = $row->codigo_barras != "" ? $row->codigo_barras : "Sin codigo de barras";
				        $j[$x]="{\"id\" :\"$row->pid\",
                                        \"nid\" :\"$row->nid\",
                                        \"descripcion\":\"$row->descripcion # ".$row->talla." - ".$row->color."\",
																										 \"precio_compra\":\"$row->precio_compra\",
                                        \"existencia\":\"$row->sum\",
                                        \"cod_bar\":\"$bar \"}";
				$x+=1;
			}
			$json= "[". implode(", ", $j). "]";
		} else {
			$json="[{\"id\": \"0\", \"nid\" :\"0\", \"descripcion\":\"No encontrado\"},\"existencia\":\"\",\"precio_compra\":\"0\",
                                        \"cod_bar\":\"\"]";
		}
		echo $json;
	}

	function productos_existecia_general() {
		 $var=strtoupper($_POST['q']);
    $query=$this->db->query("select sum(sum) as existencia, pid, nid, descripcion, talla, color, precio_compra, codigo_barras 
		 									from existencia_detalle
			            where descripcion like '%$var%' and sum > 0
			            group by pid, nid, descripcion, talla, color, precio_compra, codigo_barras");
    
			$productos=$query->result();
   $x=0;
		$j=array();
		if($query->num_rows()>0){
			foreach($productos as $row){
						$bar = $row->codigo_barras != "" ? $row->codigo_barras : "Sin codigo de barras";
				   $j[$x]="{\"id\" :\"$row->pid\",
                                        \"nid\" :\"$row->nid\",
                                        \"descripcion\":\"$row->descripcion # ".$row->talla." - ".$row->color."\",
																										 \"precio_compra\":\"$row->precio_compra\",
                                        \"existencia\":\"$row->existencia\",
                                        \"cod_bar\":\"$bar \"}";
				$x+=1;
			}
			$json= "[". implode(", ", $j). "]";
		} else {
			$json="[{\"id\": \"0\", \"nid\" :\"0\", \"descripcion\":\"No encontrado\"},\"existencia\":\"\",\"precio_compra\":\"0\",\"cod_bar\":\"\"]";
		}
		echo $json;
	}
	
	function producto_existecia_general_codigo() {
		 $codigo = $_POST['cod_bar'];
    $query=$this->db->query("select sum(sum) as existencia, pid, nid, descripcion, talla, color, precio_compra, codigo_barras 
		 									from existencia_detalle
			            where codigo_barras = '$codigo' and sum > 0
			            group by pid, nid, descripcion, talla, color, precio_compra, codigo_barras");
    
			$productos=$query->result();
   $x=0;
		$j=array();
		if($query->num_rows()>0){
			foreach($productos as $row){
						$bar = $row->codigo_barras != "" ? $row->codigo_barras : "Sin codigo de barras";
				   $j[$x]="{\"id\" :\"$row->pid\",
                                        \"nid\" :\"$row->nid\",
                                        \"descripcion\":\"$row->descripcion # ".$row->talla." - ".$row->color."\",
																										 \"precio_compra\":\"$row->precio_compra\",
                                        \"existencia\":\"$row->existencia\",
                                        \"cod_bar\":\"$bar \"}";
				$x+=1;
			}
			$json= implode(", ", $j);
		} else {
			$json="{\"id\": \"0\", \"nid\" :\"0\", \"descripcion\":\"No encontrado\"},\"existencia\":\"\",\"precio_compra\":\"0\",\"cod_bar\":\"\"}";
		}
		echo $json;
	}
	
	function get_ajax_productos() {
		// BEGIN method get_productos
		$return_arr=array();
		$var=strtoupper($_POST['q']);
		$mid=$_POST['mid'];
		if($mid==0 or $mid=='')
			$and2="";
		else
			$and2=" and p.cmarca_producto_id='$mid' ";
		$query=$this->db->query("select p.id, p.descripcion from cproductos as p left join cmarcas_productos as m on m.id=p.cmarca_producto_id where p.descripcion like '%$var%' $and2  and p.estatus_general_id=1 order by p.descripcion ");
		$productos=$query->result();
		$x=0;
		$j=array();
		if($query->num_rows()>0){
			foreach($productos as $row){
				$j[$x]="{\"id\" :\"$row->id\", \"descripcion\":\"$row->descripcion\"}";
				$x+=1;
			}
			$json= "[". implode(", ", $j). ", {\"id\": \"0\", \"descripcion\":\"TODOS\"}]";
		} else {
			$json="[{\"id\": \"0\", \"descripcion\":\"TODOS\"}]";
		}
		echo $json;

		//echo json_encode($return_arr);
	} // END method get_productos #########################################################


	function get_producto_by_codigo()
	{ // BEGIN method get_clientes
            $return_arr=array();
           $codigo=trim($_POST['enviarvalor']);
                $query=$this->db->query("select cproducto_id from cproductos_numeracion where codigo_barras='$codigo'");
	        $pro_id=$query->row();
		$query=$this->db->query("select p.descripcion, p.id from cproductos as p join cproductos_numeracion as pn on p.id=pn.cproducto_id where p.id=$pro_id->cproducto_id");
              $descri=$query->row();
               $x=0;
        $j=array();
        if($query->num_rows()==1){
            foreach($query->result() as $row){
				if($_POST['tipo']=='tag')
					echo trim($descri->descripcion);
				else if($_POST['tipo']=='id')
					echo trim($descri->id);
            }
        } else if($query->num_rows()==0){
           echo "No encontrado";
        } else if($query->num_rows()>1){
           echo "No encontrado1";
        }
	}

	function get_producto_by_codigo_entrada()
	{ // BEGIN method get_clientes

		$return_arr=array();
		$codigo=trim($_POST['enviarvalor']);
		$recibe=$_POST['recibe'];
		if(strlen($codigo)<13){
			//            $where=" clave_anterior='$codigo'";
			$where= " clave_anterior='$codigo'";
			$lote_id=0;
		} else if(strlen($codigo)>=13){
			$pn_id=intval(substr($codigo, -6));
			$lote_id=intval(substr($codigo,0,5));
			$where= " pn.id='$pn_id'";
		}

		$query=$this->db->query("select p.id, p.descripcion, pn.numero_mm, pn.id as nid from cproductos as p left join cmarcas_productos as m on m.id=p.cmarca_producto_id left join cproveedores as cp on cp.id=m.proveedor_id left join cproductos_numeracion as pn on pn.cproducto_id=p.id where $where ");
		$x=0;
		$j=array();
		if($query->num_rows()==1){
			foreach($query->result() as $row){
				$pn_id=$row->nid;
				//Buscar la salida del producto para validar que esta enviado
				$salida=$this->db->query("select s.id, s.costo_unitario from traspasos_tiendas as tt left join salidas as s on s.id=tt.salida_id where s.lote_id=$lote_id and cproducto_numero_id=$pn_id and s.estatus_general_id=1 and espacio_fisico_recibe_id=$recibe ");

				if($salida->num_rows()>0){
					echo "<img src='".base_url()."images/ok.png' width='25' valign='middle'><span style='background-color:red;color:#fff;width:100%;'>".$row->descripcion ." # ". ($row->numero_mm/10)."</span>";
				} else
					echo "No encontrado Caso 1";
			}
		} else
			echo "No encontrado Caso 2";
	}

	function get_espacios_tiendas_oficinas_ajax(){
		//OBtiene un listado de facturas de un proveedor dado para formar un select box
		$var=strtoupper($_POST['q']);
		$query=$this->db->query("select id, tag as espacio from espacios_fisicos where estatus_general_id=1 and tipo_espacio_id>1 and tag like '%$var%' order by tag asc");
		if($query->num_rows()>0) {
			$x=0;
			$j=array();
			foreach($query->result() as $row){
				$j[$x]="{\"id\" : \"$row->id\", \"descripcion\" : \"$row->espacio\"}";
				$x+=1;
			}
			$json= "[". implode(", ", $j). ", {\"id\" : \"0\", \"descripcion\" : \"TODAS\"} ]";

		} else  {
			$json="[{'id':'0', 'descripcion':'TODAS'}]";
		}
		echo $json;
	}

	function concepto_gasto(){
		/* Funcion para checar los conceptos de gastos*/
		$clave=strtoupper(trim($_POST['enviarvalor']));
		$p=new Cgastos();
		$p->like('tag', "$clave");
		$p->get();
		if($p->c_rows>0){
			echo "<h4>Existen conceptos de gastos similares";
			foreach($p->all as $row){
				echo "<p>$row->id.- $row->tag </p>";
			}
		} else
			echo "";
	}

	function concepto_egreso(){
		/* Funcion para checar los conceptos de gastos*/
		$clave=strtoupper(trim($_POST['enviarvalor']));
		$p=new Otros_egresos();
		$p->like('tag', "$clave");
		$p->get();
		if($p->c_rows>0){
			echo "<h4>Existen conceptos de otros egresos similares";
			foreach($p->all as $row){
				echo "<p>$row->id.- $row->tag </p>";
			}
		} else
			echo "";
	}
	 
	function subir_inventario(){
		$id=$this->uri->segment(3);
		if($id>0){
			//Proceder a llamar al archivo
			$file = $_FILES['file'];
			$config['field_name']  = 'file';
			$config['upload_path'] = 'tmp/';
			$config['allowed_types'] = 'csv';
			$config['max_size']	= '30000000';
			// 			$config['max_width']  = '4024';
			// 			$config['max_height']  = '3768';

			$this->load->library('upload', $config);
			if (! $this->upload->do_upload('file')){
				$error = $this->upload->display_errors();
				echo '{"name":" Error al Subir el Documento","size":"0","rutafoto":" '.$error.' "}';
				$pass=0;
			} else {
				$this->load->model('arqueo');
				$p=$this->arqueo->get_by_id($id);
				$file_info=$this->upload->data();
				$source=$file_info['full_path'];
				//echo "info0=".$file_info0['full_path'];
				// 					  print_r(array_keys($file_info0));
				//Proceder a mover el archivo de tmp a la ubicacion final
				$file_info['image_type']="csv";
				$destino="/var/www/bebe_ingles/inventarios/{$id}_tienda-{$p->espacio_fisico_id}-inv_".date("d-m-Y").".".$file_info['image_type'];
				$ruta_foto="inventarios/{$id}_tienda-{$p->espacio_fisico_id}-inv_".date("d-m-Y").".".$file_info['image_type'];
				$archivo="{$id}_tienda-{$p->espacio_fisico_id}-inv_".date("d-m-Y").".".$file_info['image_type'];
				if (!copy($source, $destino)) {
					echo "Fallo el almacenamiento del archivo \n";
				} else {
					$ruta_foto="inventarios/{$id}_tienda-{$p->espacio_fisico_id}-inv_".date("d-m-Y").".".$file_info['image_type'];
					if($p->id!='' or $p->id>0){
						$p->cestatus_arqueo_id=2;
						$p->ruta_archivo=$ruta_foto;
						$p->save();
						echo '{"name":"'.$file['name'].'","type":"'.$file['type'].'","size":"'.$file['size'].'","rutafoto":"'.$archivo.'"}';

					} else
						show_error("Error: producto no encontrado");
				}

			}
		}
	}

	function borrar_gasto_tienda(){
		$id=$this->input->post('enviarvalor');
		if($id>0) {
			$g=new Gasto_detalle();
			$g->get_by_id($id);
			$g->estatus_general_id=2;
			$g->save();
			return 1;
		}
	}

	function pagos_multiples_devoluciones(){
		$this->load->model("salida");
		$this->load->model("entrada");
		//Obtener el proveedor_id via ajax
		$proveedor_id=$_POST['enviarvalor'];
		//Obtener el listado de producto que se le dio salida por defecto de ese proveedor con lote mayor de cero
		$defectos=$this->salida->get_salidas_defectuosas_by_proveedor($proveedor_id);
		$total_devolucion=0; $salidas_mtrx=array();
		if($defectos!=false){
			foreach($defectos->all as $row){
				//Encontrar el precio de compra de cada producto
				$entrada=$this->entrada->get_entradas_by_lote_cproducto($row->lote_id, $row->cproductos_id);
				$costo_unitario=$entrada->costo_unitario;
				$costo_total=$costo_unitario*$row->cantidad;
				$this->db->query("update salidas set costo_unitario=$costo_unitario, costo_total=$costo_total where id=$row->id");
				// Obtener el total de valor de la devolucion
				$total_devolucion+=$costo_total;
				$salidas_mtrx[]=$row->id;
			}
		}

		// Obtener las facturas que estan pendientes de pago
		$facturas=$this->pr_factura->get_pr_facturas_xpagar_proveedor($proveedor_id);
		if($facturas!=false and $total_devolucion>0){
			$salidas_id=implode(",", $salidas_mtrx);
			$fecha=explode(" ", $_POST['fecha']);
			$total_facturas=0; $total_devolucion_restante=$total_devolucion; $r=0;
			foreach($facturas->all as $fact){
				$facturas_mtrx[$r]['id']=$fact->id;
				$facturas_mtrx[$r]['folio_factura']=$fact->folio_factura;
				$total_facturas+=$facturas->monto_total;
				//Realizar el pago(s) correspondientes para que se liquide esa factura total o parcialmente por orden ascendente
				$u= new Pago();
				$u->usuario_id=$GLOBALS['usuarioid'];
				$u->fecha=$fecha[2]."/".$fecha[1]."/".$fecha[0];
				$u->cproveedor_id=$proveedor_id;
				$u->cuenta_destino_id=1;
				$u->cuenta_origen_id=1;
				$u->cpr_forma_pago_id=1;
				$u->numero_referencia="DEVOLUCION POR DEFECTO FISICO";
				$u->pr_factura_id=$fact->id;
				$u->salidas_str=$salidas_id;
				if($total_devolucion_restante>=$fact->monto_total){
					$u->monto_pagado=$fact->monto_total;
					if($u->save()){
						$salidas_id="";
						//Cambiar el estatus de cada una de las facturas
						$this->db->query("update pr_facturas set estatus_factura_id=3 where id=$fact->id");
						$total_devolucion_restante-=$fact->monto_total; unset($u);
						$facturas_mtrx[$r]['monto_pagado']=$fact->monto_total;
					}
				} else {
					$u->monto_pagado=$total_devolucion_restante;
					if($u->save()){
						//Cambiar el estatus de cada una de las facturas
						$salidas_id="";
						$this->db->query("update pr_facturas set estatus_factura_id=2 where id=$fact->id");
						$facturas_mtrx[$r]['monto_pagado']=$total_devolucion_restante;
						$total_devolucion_restante=0; unset($u);
						break;
					}
				}
				$r+=1;
			}
			//			echo $total_facturas;
			foreach($defectos->all as $row){
				//Cambiar el valor en el campo devolucion_finiquitada a 1
				$this->db->query("update salidas set devolucion_finiquitada=1 where id=$row->id");
			}
			//Enviar los datos de las facturas liquidadas por devolucion
			echo "<h2>Detalle de Facturas Finiquitadas por concepto de Devolución por defectos físicos</h2>";
			echo "<table width='500' align='center' style='font-size:7pt;'><tr><th>Id Factura</th><th>Folio</th><th>Fecha</th><th>Monto</th></tr> \n";
			$t=0;
			foreach($facturas_mtrx as $linea){
				echo "<tr><td align='center'>{$linea['id']}</td><td align='center'>{$linea['folio_factura']}</td><td align='center'>{$_POST['fecha']}</td><td align='right'>".number_format($linea['monto_pagado'],2,".",",")."</td></tr> \n";
				$t+=$linea['monto_pagado'];
			}
			echo "<tr><td align='center'>Total</td><td align='center'></td><td align='center'></td><td align='right'>".number_format($t,2,".",",")."</td></tr> \n";
			echo "</table>";
		} else
			echo "No hay devoluciones pendientes";
	}

	function pagos_multiples_devoluciones_lote0(){
		$this->load->model("salida");
		$this->load->model("entrada");
		//Obtener el proveedor_id via ajax
		$proveedor_id=$_POST['enviarvalor'];
		//Obtener el listado de producto que se le dio salida por defecto de ese proveedor con lote mayor de cero
		$defectos=$this->salida->get_salidas_defectuosas_by_proveedor_lote0($proveedor_id);
		if($defectos!=false){
			$total_cantidad=0;
			echo "<h2>Mercancia defectuosa con Lote = 0 (Producto Antiguo) </h2>";
			echo "<table width='500' align='center' style='font-size:7pt;'><tr><th>Id Salida</th><th>Fecha</th><th>Producto</th><th>Cantidad</th></tr> \n";
			foreach($defectos->all as $row){
				//Obtener un codigo_anterior de los hijos de ese producto
				$pn=new Producto_numeracion();
				$pn->select("clave_anterior")->where('cproducto_id', $row->id)->limit(1)->get();
				if($pn->clave_anterior>0)
					$ca="(CA= $pn->clave_anterior)";
				else
					$ca="(Sin CA)";
				//Encontrar el precio de com
				echo "<tr><td align='center'>$row->id</td><td align='center'>$row->fecha</td><td align='left'>$row->producto $ca </td><td align='right'>".number_format($row->cantidad,0,".",",")."</td></tr> \n";
				$total_cantidad+=$row->cantidad;
				$salidas[]=$row->id;
			}
			$salidas_id=implode(",", $salidas);
			echo "<tr><th colspan='3' align='left'>Total</th><th align='right'>$total_cantidad</th></tr>";
			echo "<tr><th colspan='3' align='left'>Costo de Total de Compra  :<br/><input type='text' name='total_lote0' id='total_lote0' value='0' size='10' class='subtotal'><input type='hidden' name='salidas_id' id='salidas_id' value='$salidas_id' size='10' ></th><th><button type='button' onclick='javascript:enviar_lote0_trans()' >Generar Abono </button></th></tr>";
		} else
			echo "<center></strong>No hay facturas disponibles a las cuales abonar el pago por devolucion por productos defectuosos</strong></center>";
	}

function pago_sin_excederse(){
          $id=$_POST['enviarvalor'];
         
		$monto_total=$this->pr_factura->get_monto_factura_id($id);
         $abono=$_POST['monto'];
		
				$total_abonos=$this->pago->get_pagos_total_factura_id($id);
				$suma_abonos=round($total_abonos->total,2);
                                $monto_factura=  round($monto_total->monto_total,2);
                                //$abono=  round($abono,2);
				$diff=$monto_factura-$suma_abonos;
                              if($abono>$diff){
         echo "<table width='500' align='center'><tr>El Abono $abono es mayor a su deuda $diff</tr></table> \n";  }else{
             $pagos=$this->pago->get_pagos_by_factura_id($id);
		if ($pagos==false){
			echo "No existen abonos a la factura indicada.";
		} else {
			$factura=$this->pr_factura->get_pr_factura($id);
			echo "<table width='500' align='center'><tr><th>Id Pago</th><th>Fecha</th><th>Referencia</th><th>Cuentas de Origen</th><th>Monto</th></tr> \n";
		 $total=0;
		 foreach($pagos->all as $row){
                     $p2=number_format($row->monto_pagado,2,'.',','); 
		 	echo "<tr><td align='center'>$row->id</td><td align='center'>$row->fecha</td><td align='center'>$row->numero_referencia</td><td align='center'>$row->banco- $row->numero_cuenta</td><td align='right'>$p2</td></tr> \n";
		 	$total+=$row->monto_pagado;
                        
                       
		 }
                 $p1=number_format($factura->monto_total,2); 
                 $adeudo=number_format($factura->monto_total-$total,2);
		 echo "<tr><th></th><th></th><th></th><th align='right'>Adeudo</th><th align='right'>".($adeudo)."</th></tr> \n";
		 echo "</table>";
		}
         }
                               
                }
        





	function pagos_multiples_devoluciones_lote0_trans(){
		$salidas=$_POST['salidas'];
		$total=$_POST['enviarvalor'];
		$proveedor_id=$_POST['proveedor_id'];
		//Obtener las facturar y cubrir el pago en base al total
		$facturas=$this->pr_factura->get_pr_facturas_xpagar_proveedor($proveedor_id);
		if($facturas!=false){
			$fecha=explode(" ", $_POST['fecha']);
			$total_facturas=0; $total_devolucion_restante=$total; $r=0;
			foreach($facturas->all as $fact){
				$facturas_mtrx[$r]['id']=$fact->id;
				$facturas_mtrx[$r]['folio_factura']=$fact->folio_factura;
				$total_facturas+=$facturas->monto_total;
				//Realizar el pago(s) correspondientes para que se liquide esa factura total o parcialmente por orden ascendente
				$u= new Pago();
				$u->usuario_id=$GLOBALS['usuarioid'];
				$u->fecha=$fecha[2]."/".$fecha[1]."/".$fecha[0];
				$u->cproveedor_id=$proveedor_id;
				$u->cuenta_destino_id=1;
				$u->cuenta_origen_id=1;
				$u->cpr_forma_pago_id=1;
				$u->numero_referencia="DEVOLUCION POR DEFECTO FISICO";
				$u->pr_factura_id=$fact->id;
				$u->salidas_str=$salidas;

				if($total_devolucion_restante>=$fact->monto_total){
					$u->monto_pagado=$fact->monto_total;
					if($u->save()){
						$salidas_id="";
						//Cambiar el estatus de cada una de las facturas
						$this->db->query("update pr_facturas set estatus_factura_id=3 where id=$fact->id");
						$total_devolucion_restante-=$fact->monto_total; unset($u);
						$facturas_mtrx[$r]['monto_pagado']=$fact->monto_total;
					}
				} else {
					$u->monto_pagado=$total_devolucion_restante;
					if($u->save()){
						//Cambiar el estatus de cada una de las facturas
						$this->db->query("update pr_facturas set estatus_factura_id=2 where id=$fact->id");
						$facturas_mtrx[$r]['monto_pagado']=$total_devolucion_restante;
						$total_devolucion_restante=0; unset($u);
						break;
					}
				}
				$r+=1;
			}
			//Actualizar las salidas
			$defectos=$this->db->query("select id from salidas where id in ($salidas)");
			foreach($defectos->result() as $row){
				//Cambiar el valor en el campo devolucion_finiquitada a 1
				$this->db->query("update salidas set devolucion_finiquitada=1 where id=$row->id");
			}
			//Enviar los datos de las facturas liquidadas por devolucion
			echo "<h4>Detalle de Facturas Finiquitadas por concepto de Devolución de Lote 0</h4>";
			echo "<table width='500' align='center' style='font-size:7pt;'><tr><th>Id Factura</th><th>Folio</th><th>Fecha</th><th>Monto</th></tr> \n";
			$t=0;
			foreach($facturas_mtrx as $linea){
				echo "<tr><td align='center'>{$linea['id']}</td><td align='center'>{$linea['folio_factura']}</td><td align='center'>{$_POST['fecha']}</td><td align='right'>".number_format($linea['monto_pagado'],2,".",",")."</td></tr> \n";
				$t+=$linea['monto_pagado'];
			}
			echo "<tr><td align='center'>Total</td><td align='center'></td><td align='center'></td><td align='right'>".number_format($t,2,".",",")."</td></tr> \n";
			echo "</table>";
		} else
			echo "<center></strong>No hay facturas disponibles a las cuales abonar el pago por devolucion por productos defectuosos</strong></center>";


	}
	function get_imagen(){
		$ruta=$_POST['ruta_foto'];
		echo "<img src='".base_url()."$ruta' width='150px' border='0' alt='Foto del calzado'>";
	}

	function borrar_traspaso_tienda(){
		$id=$this->input->post('enviarvalor');
		if($id>0) {
			$u=new Traspaso_tienda();
			$u->get_by_id($id);
			$this->db->query("update salidas set estatus_general_id=2 where id=$u->salida_id");
			return 1;
		}
	}
	function prod_estacionado_descuento(){
		$linea=$this->input->post('linea');
		$cproducto_id=$this->input->post('cproducto_id');
		$espacios_str=$this->input->post('espacios');
		$descuento=$this->input->post('descuento');
		$familia=$this->input->post('fam');
		//Obtener el precio global de venta
		$producto=$this->producto->get_by_id($cproducto_id);
		//cambiar el precio en la tabla cproductos
		$producto->precio1-=$descuento;
		//Cambiar el departamento si el valor es 5
		if($familia==5)
			$producto->cfamilia_id=$familia;
		$producto->fecha_cambio=date("Y-m-d");
		$producto->hora_cambio=date("H:i:s");
		if($producto->save()){
			//Eliminar los registros de precios_sucursales de ese producto
			$this->db->query("delete from precios_sucursales where cproducto_id=$producto->id");
			$this->db->query("update control_actualizaciones set fecha_cambio='".date("Y-m-d")."', hora='".date("H:i:s.u")."' where id=1 or id=2");
		}

		echo "<img src='".base_url()."images/bien.png' width='20px'><br/><span style='background-color:blue;color:#fff;text-align:middle;width:100%;'>".round($producto->precio1)."</span>";
	}

	function get_productos_autocomplete() { // BEGIN method get_subcuentas_rep
		$return_arr = array();
		$var = strtoupper($_POST['q']);
		$marca = $_POST['marca'];
		$this->load->model("producto");
		$query = $this->producto->get_productos_autocomplete($var, $marca);
		$x = 0;
		$j = array();
		if ($query != false) {
			foreach ($query->all as $row) {
				$j[$x] = "{'id':'$row->producto_id', 'descripcion':'$row->descr'}";
				$x+=1;
			}
			$json = "[" . implode(", ", $j) . "]";
		} else {
			$json = "[{'id':'', 'descripcion':'Sin productos'}]";
		}
		echo $json;
	}

	function get_producto_num_autocomp () {
		$var = strtoupper($_POST['q']);
		$this->load->model("producto");
		$query = $this->producto->get_productos_autocomp($var);
		$x = 0;
		$j = array();
		if ($query != false) {
			foreach ($query->all as $row) {
				$j[$x] = "{'id':'$row->id', 'descripcion':'$row->descripcion # $row->numero_mm','codigo_barras':'$row->codigo_barras','nid':'$row->nid'}";
				$x+=1;
			}
			$json = "[" . implode(", ", $j) . "]";
		} else {
			$json = "[{'id':'', 'descripcion':'Sin productos','codigo_barras':'','nid':''}]";
		}
		echo $json;
	}

	function subir_inventario_parcial() {
		$id = $this->uri->segment(3);
		if ($id > 0) {
			//Proceder a llamar al archivo
			$file = $_FILES['file'];
			$config['field_name'] = 'file';
			$config['upload_path'] = 'tmp/';
			$config['allowed_types'] = 'csv';
			$config['max_size'] = '30000000';
			// 			$config['max_width']  = '4024';
			// 			$config['max_height']  = '3768';

			$this->load->library('upload', $config);
			if (!$this->upload->do_upload('file')) {
				$error = $this->upload->display_errors();
				echo '{"name":" Error al Subir el Documento","size":"0","rutafoto":" ' . $error . ' "}';
				$pass = 0;
			} else {
				$this->load->model('arqueo_parcial');
				$p = $this->arqueo_parcial->get_by_id($id);
				$file_info = $this->upload->data();
				$source = $file_info['full_path'];
				//echo "info0=".$file_info0['full_path'];
				// 					  print_r(array_keys($file_info0));
				//Proceder a mover el archivo de tmp a la ubicacion final
				$file_info['image_type'] = "csv";
				$destino = "/var/www/bebe_ingles/inventarios_parciales/{$id}_tienda-{$p->espacio_fisico_id}-inv_" . date("d-m-Y") . "." . $file_info['image_type'];
				$ruta_foto = "inventarios_parciales/{$id}_tienda-{$p->espacio_fisico_id}-inv_" . date("d-m-Y") . "." . $file_info['image_type'];
				$archivo = "{$id}_tienda-{$p->espacio_fisico_id}-inv_" . date("d-m-Y") . "." . $file_info['image_type'];
				if (!copy($source, $destino)) {
					echo "Fallo el almacenamiento del archivo \n";
				} else {
					$ruta_foto = "inventarios_parciales/{$id}_tienda-{$p->espacio_fisico_id}-inv_" . date("d-m-Y") . "." . $file_info['image_type'];
					if ($p->id != '' or $p->id > 0) {
						$p->cestatus_arqueo_id = 2;
						$p->ruta_archivo = $ruta_foto;
						$p->save();
						echo '{"name":"' . $file['name'] . '","type":"' . $file['type'] . '","size":"' . $file['size'] . '","rutafoto":"' . $archivo . '"}';
					} else
						show_error("Error: producto no encontrado");
				}
			}
		}
	}

	function cancelar_gasto_tienda(){
		$id=$this->input->post('enviarvalor');
		if($id>0) {
			$u=new Deuda_tienda();
			$u->get_by_id($id);
			$u->estatus_general_id=2;
			$u->save();
			echo "<span style='color:red;'>Deshabilitado</span>";
		}
	}
	
	function poblar_select_subfamilias(){
		//OBtiene un listado de facturas de un proveedor dado para formar un select box
		$familia_id=$this->uri->segment(3);
		$m=new Subfamilia_producto();
		$m->where('familia_id', $familia_id)->order_by("tag")->get();
		if($m->c_rows>0){
			echo "<option value='0'>Elija</option>";
			foreach($m as $row){
				echo "<option value='$row->id'>$row->clave - $row->tag</option>";
			}
		} else
			echo "<option value='0'>Sin Subfamilias</option>";
	}
	
	function actualizar_talla(){
		$pid=$this->input->post('llave');
		$talla=$this->input->post('str');
		$codigo_barra=$this->input->post('codigo_barra');
		$pn= new Producto_numeracion();
               if($codigo_barra==''){
               $pn->get_by_id($pid);
		$pn->numero_mm=$talla;
		$pn->codigo_barras="BB".$pid;
                $pn->save();
               }else{
                $pn->get_by_id($pid);
		$pn->numero_mm=$talla;
                $pn->codigo_barras=$codigo_barra;
                $pn->save();
		
               }
		
	}
	
	function nueva_talla(){
// 		enviarvalor: id, str: tag, pid:pid,codigo_barra: codigo
		$producto_id=$this->input->post('pid');
		$talla=$this->input->post('str');
		$codigo_barra=$this->input->post('codigo_barra');
		$pn= new Producto_numeracion();
		$pn->cproducto_id=$producto_id;
		$pn->numero_mm=$talla;
		$pn->codigo_barras=$codigo_barra;
		$pn->save();

	}
	function borrar_talla(){
		$pid=$this->input->post('llave');
		$pn= new Producto_numeracion();
		$pn->get_by_id($pid);
		$pn->save();
	}
        
        function producto_descripcion(){
		/* Funcion para checar la descripcion del producto*/
		$clave=$_POST['enviarvalor'];
		$obj=$_POST['obj'];
		$p=new Producto();
		$p->ilike('descripcion', "$clave");
		$p->get();
		if($p->c_rows>0){
			echo "<h4>Existen productos similares al que esta capturando, valide que no lo esta duplicando</h4>";
			foreach($p->all as $row){
				echo "<p>$row->id.- $row->descripcion </p>";
			}
		} else 
			echo "bien";
	}
	
}
?>
