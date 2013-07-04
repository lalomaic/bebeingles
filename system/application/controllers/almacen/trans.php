<?php
class Trans extends Controller {
	function Trans()
	{
		parent::Controller();
		if($this->session->userdata('logged_in') == FALSE){
			redirect(base_url()."index.php/inicio/logout");
		}
		$this->load->model("empresa");
		$this->load->model("almacen");
		$this->load->model("pr_factura");
		$this->load->model("cl_factura");
		$this->load->model("entrada");
		$this->load->model("salida");
                $this->load->model("traspaso");
                //$this->load->model("traspaso_salida");
		$this->load->model("espacio_fisico");
		$this->load->model("producto");
		$this->load->model("producto_numeracion");
		$user_hash=$this->session->userdata('user_data');
		$row=$this->usuario->get_usuario_detalles($user_hash);
		$GLOBALS['usuarioid']=$row->id;
		$GLOBALS['empresaid']=$row->empresas_id;
		$GLOBALS['espacios_fisicos_id']=$row->espacio_fisico_id;
		$GLOBALS['username']=$row->username;
		$GLOBALS['ruta']=$this->uri->segment(1);
		$GLOBALS['controller']=$this->uri->segment(2);
		$GLOBALS['funcion']=$this->uri->segment(3);
		$GLOBALS['ubicacion_nombre']=$this->espacio_fisico->get_espacios_f_tag($GLOBALS['espacios_fisicos_id']);

	}

	function alta_tipo_entrada(){
		//Guardar el tipo de entrada
		$u= new Tipo_entrada();
		$related = $u->from_array($_POST);
		// save with the related objects
		if($u->save($related))
		{
			echo "<html> <script>alert(\"Se han registrado los datos del Tipo de Entrada.\"); window.location='".base_url()."index.php/inicio/acceso/".$GLOBALS['ruta']."/menu';</script></html>";
		}
		else
		{
			show_error("".$u->error->string);
		}
	}

	function act_tipo_entrada(){
		//Actualizar el Tipo de Entrada
		$u= new Tipo_entrada();
		$related = $u->from_array($_POST);

		// save with the related objects
		if($u->save($related))
		{
			echo "<html> <script>alert(\"Se han actualizado los datos del Tipo de Entrada.\"); window.location='".base_url()."index.php/inicio/acceso/".$GLOBALS['ruta']."/menu';</script></html>";
		}
		else
		{
			show_error("".$u->error->string);
		}
	}

	function alta_tipo_salida(){
		//Guardar el tipo de salida
		$u= new Tipo_salida();
		$related = $u->from_array($_POST);
		// save with the related objects
		if($u->save($related))
		{
			echo "<html> <script>alert(\"Se han registrado los datos del Tipo de Salida.\"); window.location='".base_url()."index.php/inicio/acceso/".$GLOBALS['ruta']."/menu';</script></html>";
		}
		else
		{
			show_error("".$u->error->string);
		}
	}

	function act_tipo_salida(){
		//Actualizar el Tipo de Salida
		$u= new Tipo_salida();
		$related = $u->from_array($_POST);

		// save with the related objects
		if($u->save($related))
		{
			echo "<html> <script>alert(\"Se han actualizado los datos del Tipo de Salida.\"); window.location='".base_url()."index.php/inicio/acceso/".$GLOBALS['ruta']."/menu';</script></html>";
		}
		else
		{
			show_error("".$u->error->string);
		}
	}

	function alta_familia(){
		//Guardar la Familia de Productos
		$u= new Familia_producto();
		$u->fecha_alta=date("Y/m/d");
		$u->usuario_id=$GLOBALS['usuarioid'];
		$related = $u->from_array($_POST);

		// save with the related objects
		if($u->save($related))
		{
			echo "<html> <script>alert(\"Se ha registrado los datos de la Familia de Productos.\"); window.location='".base_url()."index.php/inicio/acceso/".$GLOBALS['ruta']."/menu';</script></html>";
		}
		else
		{
			show_error("".$u->error->string);
		}
	}

	function act_familia(){
		//Actualizar la Familia de Productos
		$u= new Familia_producto();
		$u->usuario_id=$GLOBALS['usuarioid'];
		$related = $u->from_array($_POST);

		// save with the related objects
		if($u->save($related))
		{
			echo "<html> <script>alert(\"Se han actualizado los datos de la Familia de Productos.\"); window.location='".base_url()."index.php/inicio/acceso/".$GLOBALS['ruta']."/menu';</script></html>";
		}
		else
		{
			show_error("".$u->error->string);
		}
	}

	function alta_subfamilia(){
		//Guardar la Subfamilia de Productos
		$u= new Subfamilia_producto();
		$u->fecha_alta=date("Y/m/d");
		$u->usuario_id=$GLOBALS['usuarioid'];
		$related = $u->from_array($_POST);

		// save with the related objects
		if($u->save($related)){
			echo "<html> <script>alert(\"Se han registrado los datos de la Subfamilia de Productos.\"); window.location='".base_url()."index.php/".$GLOBALS['ruta']."/productos_c/formulario/list_subfamilias';</script></html>";
		} else
			show_error("".$u->error->string);
	}

	function act_subfamilia(){
		//Actualizar la Subfamilia de Productos
		$u= new Subfamilia_producto();
		$u->usuario_id=$GLOBALS['usuarioid'];
		$related = $u->from_array($_POST);
		if($u->save($related)) {
			echo "<html> <script>alert(\"Se han registrado los datos de la Subfamilia de Productos.\"); window.location='".base_url()."index.php/".$GLOBALS['ruta']."/productos_c/formulario/list_subfamilias';</script></html>";
		} else
			show_error("".$u->error->string);
	}

	function alta_marca(){
		//Guardar la Marca de Productos
		$u= new Marca_producto();
		$related = $u->from_array($_POST);
		$u->usuario_id=$GLOBALS['usuarioid'];
		// save with the related objects
		if($u->save($related)) {
			echo "<html> <script>alert(\"Se han actualizado los datos de la Subfamilia de Productos.\"); window.location='".base_url()."index.php/inicio/acceso/".$GLOBALS['ruta']."/menu';</script></html>";
		} else
			show_error("".$u->error->string);
	}

	function alta_material(){
		//Guardar la Marca de Productos
		$u= new Material_producto();
		$related = $u->from_array($_POST);
		$u->usuario_id=$GLOBALS['usuarioid'];
		// save with the related objects
		if($u->save($related)) {
			echo "<html> <script>alert(\"Se han registrado los datos del  Material del Productos.\"); window.location='".base_url()."index.php/almacen/productos_c/formulario/list_materiales';</script></html>";
		} else
			show_error("".$u->error->string);
	}

	function alta_unidad_m(){
		//Guardar la Unidad de Medida
		$u= new Unidad_medida();
		$u->fecha_alta=date("Y-m-d");
		$u->estatus_general_id=1;
		$u->usuario_id=$GLOBALS['usuarioid'];
		$related = $u->from_array($_POST);

		// save with the related objects
		if($u->save($related))
		{
			echo "<html> <script>alert(\"Se han registrado los datos de la Unidad de Medida.\"); window.location='".base_url()."index.php/inicio/acceso/".$GLOBALS['ruta']."/menu';</script></html>";
		}
		else
		{
			show_error("".$u->error->string);
		}
	}

	function act_unidad_m(){
		//Actualizar la Unidad de Medida
		$u= new Unidad_medida();
		$u->usuario_id=$GLOBALS['usuarioid'];
		$related = $u->from_array($_POST);

		// save with the related objects
		if($u->save($related))
		{
			echo "<html> <script>alert(\"Se han actualizado los datos de la Unidad de Medida.\"); window.location='".base_url()."index.php/inicio/acceso/".$GLOBALS['ruta']."/menu';</script></html>";
		}
		else
		{
			show_error("".$u->error->string);
		}
	}

	function alta_producto(){
		$u= new Producto();
		$u->fecha_alta=date("Y-m-d");
		$u->usuario_id=$GLOBALS['usuarioid'];
		$_POST['descripcion']=strtoupper($_POST['descripcion']);
		$u->precio_compra=0;
		$u->combo=0;
		$iteraccion=count($_POST);
		//Generar matriz con las tallas
		$colector=array(); $codigos=array();
		for($x=1;$x<=$iteraccion;$x++){
			if(isset($_POST["talla$x"]) and strlen($_POST["talla$x"])>0 ){
				$colector[]=$_POST["talla$x"];
                                $gen_cod[] = $_POST["gen_cod_bar$x"];
                                if($_POST["gen_cod_bar$x"]=="false") {
                                    $codigos[]=$_POST["codigo_barra$x"];
                                } else {
                                    $codigos[]="";
                                }
			}
		}
		$related = $u->from_array($_POST);
		if($u->save($related)) {
			//Generar Entradas para las tallas
			foreach($colector as $k=>$row){
				$pn=new Producto_numeracion();
				$pn->cproducto_id=$u->id;
				$pn->numero_mm=$row;
				$pn->clave_anterior=0;
				$pn->codigo_barras=$codigos[$k];
				$pn->usuario_id=$u->usuario_id;
				$pn->fecha_captura=$u->fecha_alta;
				$pn->save();
				if($gen_cod[$k]=="true"){
                                    $pn->codigo_barras = "BB".$pn->id;
                                } else{
                                    $pn->codigo_barras=$codigos[$k];				
                                }
                                $pn->save();
                                $pn->clear();
			}
			$this->db->query("update control_actualizaciones set fecha_cambio='".date("Y-m-d")."', hora='".date("H:i:s.u")."' where id=1 or id=2");
			echo "<html> <script>alert(\"Se han registrado los datos del Producto con el Id = $u->id .\"); window.location='".base_url()."index.php/".$GLOBALS['ruta']."/productos_c/formulario/alta_producto/preload/$u->id';</script></html>";
		} else
			show_error("".$u->error->string);
		unset($_POST);
	}


    
    function act_producto() {
        $u = new Producto();
        $u->usuario_id = $GLOBALS['usuarioid'];
        $u->get_by_id = $_POST['id'];
        // save with the related objects
    	$iteraccion=count($_POST);
		//Generar matriz con las tallas
		$colector=array(); $codigos=array();
		for($x=1;$x<=$iteraccion;$x++){
			if(isset($_POST["talla$x"]) and strlen($_POST["talla$x"])>0 ){
				$colector[]=$_POST["talla$x"];
                                $gen_cod[] = $_POST["gen_cod_bar$x"];
                                if($_POST["gen_cod_bar$x"]=="false") {
                                    $codigos[]=$_POST["codigo_barra$x"];
                                } else {
                                    $codigos[]="";
                                }
			}
		}
		$related = $u->from_array($_POST);
		if($u->save($related)) {
			//Generar Entradas para las tallas
			foreach($colector as $k=>$row){
				$pn=new Producto_numeracion();
//                                $pid= $varP['num_id' . $line];
//                                $pn->get_by_id($pid);
				$pn->cproducto_id=$u->id;
				$pn->numero_mm=$row;
				$pn->clave_anterior=0;
				$pn->codigo_barras=$codigos[$k];
				$pn->usuario_id=$u->usuario_id;
				$pn->fecha_captura=$u->fecha_alta;
				$pn->save();
				if($gen_cod[$k]=="true"){
                                    $pn->codigo_barras = "BB".$pn->id;
                                } else{
                                    $pn->codigo_barras=$codigos[$k];				
                                }
                                $pn->save();
                                $pn->clear();
			}
			$u->get_by_id($u->id);
            $this->db->query("update control_actualizaciones set fecha_cambio='" . date("Y-m-d") . "', hora='" . date("H:i:s.u") . "' where id=1");
             $this->db->query("update cproductos set fecha_cambio='" . date("Y-m-d") . "', hora_cambio='" . date("H:i:s.u") . "' where id=$u->id");

            //$this->db->query("update control_actualizaciones set fecha_cambio='".date("Y/m/d")."' where id=1");
            echo "<html> <script>alert(\"Se han actualizado los datos del Producto.\"); window.location='" . base_url() . "index.php/almacen/productos_c/formulario/list_productos';</script></html>";
		} else
			show_error("".$u->error->string);
		unset($_POST);    
    }

    function verificar_act_pro_combo() {
        $id = $this->uri->segment(4);
        if ($id > 0) {
            $p = new Producto();
            $p->get_by_id($id);
           $p->hora_cambio=  date("H:s:i");
           $p->fecha_cambio=  date("Y-m-d");
            if ($p->save()) {
                $this->db->query("update control_actualizaciones set fecha_cambio='" . date("Y/m/d") . "' where id=1");
                echo "<html> <script>alert(\"Se han actualizado los datos del producto.\"); window.location='" . base_url() . "index.php/" . $GLOBALS['ruta'] . "/productos_c/formulario/list_productos_combo';</script></html>";
            } else {
                show_error("1" . $p->error->string);
                /*                 * No se encontro registro valido de productos dentro del pedido por lo cual se cancela */
                echo "<html> <script>alert(\"Se ha eliminado el Pedido de Compra, intente de nuevo.\"); window.location='" . base_url() . "index.php/" . $GLOBALS['ruta'] . "/almacen_c/formulario/list_inventario_inicial/';</script></html>";
            }
        } else {
            show_error("2" . $p->error->string);
            /*             * No se encontro registro valido de productos dentro del pedido por lo cual se cancela */
            echo "<html> <script>alert(\"Se ha eliminado el Pedido de Compra, intente de nuevo.\"); window.location='" . base_url() . "index.php/" . $GLOBALS['ruta'] . "/almacen_c/formulario/list_entradas';</script></html>";
        }
    }
    
function act_combo() {
        $u = new Producto();
        $u->usuario_id = $GLOBALS['usuarioid'];
        $u->get_by_id = $_POST['id'];
        $related = $u->from_array($_POST);
        // save with the related objects
        if ($u->save($related)) {
            $u->get_by_id($u->id);
            $this->db->query("update control_actualizaciones set fecha_cambio='" . date("Y-m-d") . "', hora='" . date("H:i:s.u") . "' where id=1");
            $this->db->query("update cproductos set fecha_cambio='" . date("Y-m-d") . "', hora='" . date("H:i:s.u") . "' where id=$u->id");
            //$this->db->query("update control_actualizaciones set fecha_cambio='".date("Y/m/d")."' where id=1");
           echo 1;
        }
        
    }

    
    
    
     function act_pro_combo1() {
        $u = new Producto();
        //$pc=new cproducto_combo();
        $u->usuario_id = $GLOBALS['usuarioid'];
        $u->get_by_id = $_POST['id'];
        $related = $u->from_array($_POST);
        
        // save with the related objects
        if ($u->save($related)) {
			
            $u->get_by_id($u->id);
           // $this->db->query("update cproductos set relacion=1 where id= $pc->cproducto_id_relacion");
            $this->db->query("update control_actualizaciones set fecha_cambio='" . date("Y-m-d") . "', hora='" . date("H:i:s.u") . "' where id=1");
            $this->db->query("update cproductos set fecha_cambio='" . date("Y-m-d") . "', hora_cambio='" . date("H:i:s.u") . "' where id=$u->id");
            //$this->db->query("update control_actualizaciones set fecha_cambio='".date("Y/m/d")."' where id=1");
            echo 1;
        }
        else
            show_error("" . $u->error->string);
    }

	function act_pro_combo() {
	        $u = new Producto();
	        $pc=new cproducto_combo();
	        if($_POST['id']>0)
            $pc->get_by_id($_POST['id']);
            unset($_POST['id']);
	        $u->usuario_id = $GLOBALS['usuarioid'];
	        //$u->get_by_id = $_POST['id'];
	        $related = $u->from_array($_POST);
	        $pc->cproducto_id_combo=$_POST['cproducto_id_combo'];
	        $pc->cproducto_numeracion_id_combo=$_POST['cproducto_numeracion_id_combo'];
	        $pc->cproducto_id_relacion=$_POST['cproducto_id_relacion'];
	        $pc->cproducto_numeracion_id_relacion=$_POST['cproducto_numeracion_id_relacion'];
	        $pc->fecha_cambio= date("Y-m-d");
	        $pc->hora_cambio=  date("H:i:s");
	        $pc->usuario_id=$GLOBALS['usuarioid'];
	        $pc->	semejante_producto_combo=$_POST['semejanza'];

	        if ($u->save($related) && $pc->save()) {
	            $u->get_by_id($u->id);
	            $this->db->query("update cproductos set relacion=1 where id= $pc->cproducto_id_relacion");
	            $this->db->query("update control_actualizaciones set fecha_cambio='" . date("Y-m-d") . "', hora='" . date("H:i:s.u") . "' where id=1");
	            $this->db->query("update cproductos set fecha_cambio='" . date("Y-m-d") . "', hora_cambio='" . date("H:i:s.u") . "' where id=$pc->cproducto_id_combo");
	            //$this->db->query("update control_actualizaciones set fecha_cambio='".date("Y/m/d")."' where id=1");
	            echo 1;
	        }
	        else
	            show_error("" . $u->error->string);
	    }


function verificar_relacion_combo() {
        $id = $this->uri->segment(4);
        $p = new Producto();
        if ($id > 0) {
           
            $p->get_by_id($id);
            $p->estatus_general_id = 1;
            if ($p->save()) {
                $this->db->query("update control_actualizaciones set fecha_cambio='" . date("Y/m/d") . "' where id=3");
                echo "<html> <script>alert(\"Se ha registrado la relacion de productos Combos.\"); window.location='" . base_url() . "index.php/almacen/productos_c/formulario/list_productos_combo';</script></html>";
            } else {
                show_error("1" . $p->error->string);
                /*                 * No se encontro registro valido de productos dentro del pedido por lo cual se cancela */
                echo "<html> <script>alert(\"Se ha eliminado el Pedido de Compra, intente de nuevo.\"); window.location='" . base_url() . "index.php/" . $GLOBALS['ruta'] . "/almacen_c/formulario/list_entradas';</script></html>";
            }
        } else {
            show_error("2" . $p->error->string);
            /*             * No se encontro registro valido de productos dentro del pedido por lo cual se cancela */
            echo "<html> <script>alert(\"Se ha eliminado el Pedido de Compra, intente de nuevo.\"); window.location='" . base_url() . "index.php/" . $GLOBALS['ruta'] . "/almacen_c/formulario/list_entradas';</script></html>";
        }
    }
    
	function alta_pro_combo() {
	        //$u = new Producto();
	         $d = new Producto_numeracion();
	        $d->select("id");
	        $d->where('cproducto_id', $_POST['cproducto_id_combo']);
	        $d->limit(1);
	        $d->get();
	        $cproducto_numeracion_id_combo = $d->id;
	        $pc=new cproducto_combo();
	        //$u->usuario_id = $GLOBALS['usuarioid'];
	        //$u->get_by_id = $_POST['id'];
	        //$related = $u->from_array($_POST);
	        $pc->cproducto_id_combo=$_POST['cproducto_id_combo'];
	        $pc->cproducto_numeracion_id_combo=$cproducto_numeracion_id_combo;
	        $pc->cproducto_id_relacion=$_POST['cproducto_id_relacion'];
	        $pc->cproducto_numeracion_id_relacion=$_POST['cproducto_numeracion_id_relacion'];
	        $pc->fecha_cambio= date("Y-m-d");
	        $pc->hora_cambio=  date("H:i:s");
	        $pc->usuario_id=$GLOBALS['usuarioid'];
	          $pc->	semejante_producto_combo=$_POST['semejanza'];
	        // save with the related objects
	        if ($pc->save()) {
	            //$u->get_by_id($u->id);
	            $this->db->query("update cproductos set relacion=1 where id= $pc->cproducto_id_relacion");
	            $this->db->query("update control_actualizaciones set fecha_cambio='" . date("Y-m-d") . "', hora='" . date("H:i:s.u") . "' where id=1");
	            $this->db->query("update control_actualizaciones set fecha_cambio='" . date("Y-m-d") . "', hora='" . date("H:i:s.u") . "' where id=2");
	            //$this->db->query("update control_actualizaciones set fecha_cambio='".date("Y/m/d")."' where id=1");
	            echo $pc->id;
	        }
	        else
	            show_error("" . $u->error->string);
	    }



function alta_producto_combo() {
        $u= new Producto();
        if(isset($_POST["id_prod"])){
         if($_POST['id_prod']>0)
                                 $u->get_by_id($_POST['id_prod']);
                                    unset($_POST['id_pro']);
        }
		$u->fecha_alta=date("Y-m-d");
		$u->usuario_id=$GLOBALS['usuarioid'];
		$_POST['descripcion']=strtoupper($_POST['descripcion']);
		$u->precio_compra=0;
		$u->combo=1;
		$u->estatus_general_id=2;
		$iteraccion=count($_POST);
		//Generar matriz con las tallas
		$colector=array(); $codigos=array();
		for($x=1;$x<=1;$x++){
			if(isset($_POST["talla$x"]) and strlen($_POST["talla$x"])>0 ){
				$colector[]=$_POST["talla$x"];
                                $gen_cod[] = $_POST["gen_cod_bar$x"];
                                if($_POST["gen_cod_bar$x"]=="false") {
                                    $codigos[]=$_POST["codigo_barra$x"];
                                } else {
                                    $codigos[]="";
                                }
			}
		}
		$related = $u->from_array($_POST);
		if($u->save($related)) {
				foreach($colector as $k=>$row){
				$pn=new Producto_numeracion();
                                 if(isset($_POST["id_cod"])){
                                 if($_POST['id_cod']>0)
                                 $pn->get_by_id($_POST['id_cod']);
                                    unset($_POST['id_cod']);
        }
				$pn->cproducto_id=$u->id;
				$pn->numero_mm=$row;
				$pn->clave_anterior=0;
				$pn->codigo_barras=$codigos[$k];
				$pn->usuario_id=$u->usuario_id;
				$pn->fecha_captura=$u->fecha_alta;
				
				if($gen_cod[$k]=="true"){
                                    $pn->codigo_barras = "BB".$pn->id;
                                } else{
                                    $pn->codigo_barras=$codigos[$k];				
                                }
                                $pn->save();
                             
			}	//Generar Entradas para las tallas
         echo "<input type='hidden' name='id_cod' id='id_cod' value='$pn->id'>";             
	
	  echo "<input type='hidden' name='id_prod' id='id_prod' value='$u->id'>";
          
			$this->db->query("update control_actualizaciones set fecha_cambio='".date("Y-m-d")."', hora='".date("H:i:s.u")."' where id=1 or id=2");
			
		} else
			show_error("".$u->error->string);
		unset($_POST);
    }

	function alta_pr_factura(){
		 //Guardar factura de proveedor
        $tipo_descuento = $_POST['tipo_descuento'];
        unset($_POST['tipo_descuento']);
        if ($tipo_descuento == "porcentaje") {
            $_POST['porcentaje_descuento'] = $_POST['descuento'];
            $_POST['descuento'] = 0;
        } else {
            //$monto = $_POST['monto_total'] != 0 ? $_POST['monto_total'] : 1;
            $_POST['porcentaje_descuento'] = 0;//100 * $_POST['descuento'] / $monto;
        }
        $u = new Pr_factura();
        $u->usuario_id = $GLOBALS['usuarioid'];
        $u->empresas_id = $GLOBALS['empresaid'];
        $u->espacios_fisicos_id = $GLOBALS['espacios_fisicos_id'];
        $u->fecha_captura = date("Y-m-d H:i:s");
        $fecha = explode(" ", $_POST['fecha']);
        $u->fecha = $fecha[2] . "/" . $fecha[1] . "/" . $fecha[0];
        unset($_POST['fecha']);
        unset($_POST['tipo_entrada']);
        $related = $u->from_array($_POST);
        // save with the related objects
        $u->lote_id = 0;
        $u->save($related);
        
	/*if ($u->save($related)) {
            //Dar de alta el lote si el proveedor no es el inicial
            if ($u->cproveedores_id > 0) {
                $l = new Lote();
                $l->fecha_recepcion = $u->fecha;
                $l->espacio_fisico_inicial_id = $u->espacios_fisicos_id;
                $l->pr_factura_id = $u->id;
                $l->save();
                $u->lote_id = $l->id;
                $u->save();
            }
        }*/
        echo "<input type='hidden' name='id' id='id' value='$u->id'>";
    }

	function cancelar_alta_pr_factura() {
   	$id = $_POST['id'];
   	$prf = new Pr_factura($id);
      $prf->estatus_general_id = 2;
      $prf->save();
      $entradas = new Entrada();
      $entradas->where("pr_facturas_id", $id);
      $entradas->get();           
      $entradas->update_all("estatus_general_id", "2");   
   	echo "{ \"status\" : \"ok\" }";
	}
      function alta_inventario(){
		//Guardar factura de proveedor
		
		$u= new Pr_factura();
		$u->usuario_id=$GLOBALS['usuarioid'];
		$u->empresas_id=$GLOBALS['empresaid'];
		$u->espacios_fisicos_id=$GLOBALS['espacios_fisicos_id'];
		$u->fecha_captura=date("Y-m-d H:i:s");
		$u->fecha=date("Y-m-d H:i:s");
		$u->cproveedores_id=1;
                $u->fecha_pago=date("Y-m-d H:i:s");
                $u->ctipo_factura_id=2;
		$fecha=explode(" ", $_POST['fecha']);
		$u->fecha=$fecha[2]."/".$fecha[1]."/".$fecha[0];
		unset($_POST['fecha']);
		unset($_POST['tipo_entrada']);
		$related = $u->from_array($_POST);
		// save with the related objects
		
		if($u->save($related)){
                    //Dar de alta el lote si el proveedor no es el inicial
                    if($u->cproveedores_id>0){
                            $l=new Lote();
                            $l->fecha_recepcion=$u->fecha;
                            $l->espacio_fisico_inicial_id=$u->espacios_fisicos_id;
                            $l->pr_factura_id=$u->id;
                            $l->save(); 
                            $u->lote_id=$l->id;
                            $u->save();
                    }
                }
			echo "<input type='hidden' name='id' id='id' value='$u->id'>";
                        
	}  
        
        function dias_de_credito(){
            $id = $_POST['proveedor'];
            $prov = new Proveedor();
            $prov->get_by_id($id);
            echo $prov->dias_credito;
        } 
        
        function edi_traspaso(){
        	$traspaso_id=$_POST['traspaso_id'];
        	$s= new Salida();
        	$s->cclientes_id	=1;
        	$s->fecha=date("Y-m-d");
        	$s->ctipo_salida_id=2;
        	$s->lote_id=0;
        	$espacio=$_POST['espacio_fisico_recibe_id'];
        $related = $s->from_array($_POST); 	
        	if ($s->id == 0)
            unset($s->id);
        if ($s->save($related)) {
            echo $s->id;
//$this->db->query("update traspasos set espacio_fisico_recibe_id='$espacio' where id=$traspaso_id");
        } else
            echo 0;
        	}
	function alta_entrada(){
		//Guardar factura de proveedor
        $e = new Entrada();
		$precio=$_POST['costo_unitario'];
		$producto_id=$_POST['cproductos_id'];
        $e->usuario_id = $GLOBALS['usuarioid'];
        $e->empresas_id = $GLOBALS['empresaid'];
        $related = $e->from_array($_POST);
        //if($e->costo_unitario=='undefined') $e->costo_unitario=0;
        $f = new Pr_factura();
        $f->get_by_id($e->pr_facturas_id);
        $e->espacios_fisicos_id = $f->espacios_fisicos_id;
        $e->lote_id = $f->lote_id;
        $e->costo_total = ($e->cantidad * $e->costo_unitario);
		$e->existencia = $e->cantidad;
        $e->cproveedores_id = $f->cproveedores_id;
        $e->fecha = date("Y-m-d H:i:s");
        if ($e->id == 0)
            unset($e->id);
        if ($e->save($related)) {
            echo $e->id;
			$this->db->query("update cproductos set precio_compra='$precio' where id=$producto_id");
        } else
            echo 0;
    }

	function verificar_entrada(){
		$id=$this->uri->segment(4);
		$d=new Entrada();
		$d->select("sum(costo_total) as total, sum(tasa_impuesto * cantidad * costo_unitario/(100+tasa_impuesto)) as impuesto, sum(cantidad) as cantidad");
		$d->where('pr_facturas_id', "$id");
		$d->get();
		echo "-total-db:".$total=$d->total;
		//$impuesto=$d->impuesto;
		if ($d->cantidad>0){
			$p=new Pr_factura();
			$p->get_by_id($id);
			$tipo_id =$p->ctipo_factura_id;
			$this->load->model("ctipo_factura");
			$tipo = new Ctipo_factura($tipo_id);
         		echo "-tasa impuesto:".$tipo->tasa_impuesto;
			echo " -descuento porcentaje:".$p->porcentaje_descuento;
			echo " -descuento pesos".$p->descuento;
			$p->descuento = $p->porcentaje_descuento == 0 ? $p->descuento : $total*$p->porcentaje_descuento/100;
			echo " -iva:".$p->iva_total= (($total - $p->descuento) * $tipo->tasa_impuesto  /100); //$impuesto;
			echo " -monto:".$p->monto_total= ($total - $p->descuento) + $p->iva_total;			
			if($p->save()){
				$this->db->query("update control_actualizaciones set fecha_cambio='".date("Y/m/d")."' where id='3'");
				echo "<html> <script>alert(\"Se registrado el Alta de Entrada de manera exitosa.\"); window.location='".base_url()."index.php/".$GLOBALS['ruta']."/almacen_c/formulario/list_entradas';</script></html>";
				//echo 1;			
			} else {
				show_error("1".$p->error->string);
				/**No se encontro registro valido de productos dentro del pedido por lo cual se cancela*/
				echo "<html> <script>alert(\"Se ha eliminado el Pedido de Compra, intente de nuevo.\"); window.location='".base_url()."index.php/".$GLOBALS['ruta']."/almacen_c/formulario/list_entradas';</script></html>";
			}
		}
		else {
			show_error("2".$p->error->string);
			/**No se encontro registro valido de productos dentro del pedido por lo cual se cancela*/
			echo "<html> <script>alert(\"Se ha eliminado el Pedido de Compra, intente de nuevo.\"); window.location='".base_url()."index.php/".$GLOBALS['ruta']."/almacen_c/formulario/list_entradas';</script></html>";
		}
	//}
	
}


function act_veri_entrada(){
	$id=$_POST['id'];
		$e=new pr_factura();
		 $related = $e->from_array($_POST);
		 if($e->save($related)){
		$d=new Entrada();
		$d->select("sum(costo_total) as total, sum(tasa_impuesto * cantidad * costo_unitario/(100+tasa_impuesto)) as impuesto, sum(cantidad) as cantidad");
		$d->where('pr_facturas_id', "$id");
		$d->get();
		$total=$d->total;
		$impuesto=$d->impuesto;
		if ($d->cantidad>0){
			$p=new Pr_factura();
			$p->get_by_id($id);
			$p->monto_total=$total;
			$p->descuento=$total*$p->porcentaje_descuento/100;
			$p->iva_total=$impuesto;
			if($p->save()){
				$this->db->query("update control_actualizaciones set fecha_cambio='".date("Y/m/d")."' where id=3");
				//echo "<html> <script>alert(\"Se registrado el Alta de Entrada de manera exitosa.\"); window.location='".base_url()."index.php/".$GLOBALS['ruta']."/almacen_c/formulario/list_entradas';</script></html>";
				echo 1;			
			} else {
				show_error("1".$p->error->string);
				/**No se encontro registro valido de productos dentro del pedido por lo cual se cancela*/
				echo "<html> <script>alert(\"Se ha eliminado el Pedido de Compra, intente de nuevo.\"); window.location='".base_url()."index.php/".$GLOBALS['ruta']."/almacen_c/formulario/list_entradas';</script></html>";
			}
		}
		else {
			show_error("2".$p->error->string);
			/**No se encontro registro valido de productos dentro del pedido por lo cual se cancela*/
			echo "<html> <script>alert(\"Se ha eliminado el Pedido de Compra, intente de nuevo.\"); window.location='".base_url()."index.php/".$GLOBALS['ruta']."/almacen_c/formulario/list_entradas';</script></html>";
		}
	}else {
show_error("2".$p->error->string);
			/**No se encontro registro valido de productos dentro del pedido por lo cual se cancela*/
			echo "<html> <script>alert(\"Se ha eliminado el Pedido de Compra, intente de nuevo.\"); window.location='".base_url()."index.php/".$GLOBALS['ruta']."/almacen_c/formulario/list_entradas';</script></html>";

	}
	
	
	}

	function alta_cl_factura(){
		//Guardar factura de proveedor
		$u= new Cl_factura();
		$u->usuario_id=$GLOBALS['usuarioid'];
		$u->empresas_id=$GLOBALS['empresaid'];
		$u->fecha_captura=date("Y-m-d H:i:s");
		$u->estatus_general_id=1;
		$related = $u->from_array($_POST);
		if($u->save($related)) {
			$pr=new Cl_pedido();
			$pr->get_by_id($u->cl_pedido_id);
			$pr->ccl_estatus_pedido_id=3;
			$pr->save();


			$this->db->query("update salidas set cclientes_id=$u->cclientes_id, fecha='$u->fecha' where cl_facturas_id=$u->id");
			echo form_hidden('id', "$u->id"); echo '<p></p><button type="submit" id="boton1" style="display:none;">Paso 2. Registrar Factura</button>';
		} else {
			echo form_hidden('id', "0"); echo '<p></p><button type="submit" id="boton1" style="display:none;">Paso 2. Registrar Factura</button><h3>El pedido de venta nÃºmero:'. $u->cl_pedido_id. ', previamente se le ha dado salida, se cancela la salida</h3>';
		}
	}

	function alta_salidas(){
		//Guardar factura de proveedor
		$varP=$_POST;
		$line=$this->uri->segment(4);
		$pr= new Salida();
		$pr->usuario_id=$GLOBALS['usuarioid'];
		$pr->empresas_id=$GLOBALS['empresaid'];
		$pr->espacios_fisicos_id=$GLOBALS['espacios_fisicos_id'];
		$pr->cl_facturas_id=$varP['cl_facturas_id'.$line];
		$pr->cproductos_id=$varP['producto'.$line];
		$pr->cantidad=$varP['unidades'.$line];
		$pr->costo_unitario=$varP['precio_u'.$line];
		$pr->tasa_impuesto=$varP['iva'.$line];
		//	  $pr->costo_total=($pr->cantidad * $pr->costo_unitario) * ((100+$pr->tasa_impuesto)/100);
		$pr->costo_total=$pr->cantidad * $pr->costo_unitario;
		$pr->ctipo_salida_id=1;
		$pr->estatus_general_id=2;
		$pr->cclientes_id=$this->cl_factura->get_cl_factura_salida($varP['cl_facturas_id'.$line]);
		$pr->fecha=date("Y-m-d H:i:s");
		if(isset($varP['id'.$line])==true){
			$pr->id=$varP['id'.$line];
		}
		if($pr->save()) {
			echo form_hidden('id'.$line, "$pr->id"); echo form_hidden('cl_facturas_id'.$line, "$pr->cl_facturas_id"); echo "<img src=\"".base_url()."images/ok.png\" width=\"20px\" title=\"Guardado\"/>";
		} else
			show_error("".$u->error->string);

	}

	function verificar_salida(){
		$id=$this->uri->segment(4);
		$d=new Salida();
		$d->select("sum(costo_total) as total, sum(tasa_impuesto * costo_total/(tasa_impuesto+100)) as impuesto");
		$d->where('cl_facturas_id', "$id");
		$d->get();
		$total=$d->total;
		$impuesto=$d->impuesto;
		if ($total>0){
			$p=new Cl_factura();
			$p->get_by_id($id);
			$p->monto_total=$total;
			$p->iva_total=$impuesto;
			if($p->save()){
				$pedido= new Cl_pedido();
				$pedido->where('id', "$p->cl_pedido_id");
				$pedido->get();
				$pedido->cclientes_id=$p->cclientes_id;
				$pedido->ccl_estatus_pedido_id=3;
				$pedido->save();
				//Se valida que la factura y la entrada poseen el mismo importe se da por terminado enviando al listado de entradas
				echo "<html> <script>alert(\"Se ha dado salida a la id Factura= $id  de manera exitosa.\"); window.location='".base_url()."index.php/".$GLOBALS['ruta']."/almacen_c/formulario/list_salidas/".$GLOBALS['ruta']."/menu';</script></html>";
			} else {
				/**No se encontro registro valido de productos dentro del pedido por lo cual se cancela*/
				/*	    $this->db->query("delete from pr_detalle_pedidos where pr_pedidos_id='$id'");
	    $this->db->query("delete from pr_pedidos where id='$id'");*/
				echo "<html> <script>alert(\"Se ha eliminado el Pedido de Compra, intente de nuevo.\"); window.location='".base_url()."index.php/".$GLOBALS['ruta']."/almacen_c/formulario/list_salidas';</script></html>";
			}
		} else {
	  /**No se encontro registro valido de productos dentro del pedido por lo cual se cancela*/
	  /*	$this->db->query("delete from pr_detalle_pedidos where pr_pedidos_id='$id'");
	   $this->db->query("delete from pr_pedidos where id='$id'");*/
			echo "<html> <script>alert(\"Se ha eliminado el Pedido de Compra, intente de nuevo.\"); window.location='".base_url()."index.php/".$GLOBALS['ruta']."/almacen_c/formulario/list_salidas';</script></html>";
		}
	}
        
        function alta_traspaso(){
		
		$t=new Traspaso();
		$t->espacio_fisico_envia_id=$_POST['espacio_fisico'];
		$t->espacio_fisico_recibe_id=$_POST['espacio_fisico_recibe_id'];
		$t->cestatus_traspaso_id=1;
		$t->fecha_envio=date("Y-m-d");
		$t->usuario_id=$GLOBALS['usuarioid'];
		
		if($t->save()){
			echo "<input type='hidden' value='$t->id' id='id' name='id'><h2>Traspaso Id: $t->id</h2>";
		} else 
			echo "<input type='hidden' value='0' id='id' name='id'>";
	}
        

	function alta_salida_traspasos(){
        $traspaso_id=$_POST['traspaso_id'];
        $traspaso=$this->traspaso->get_by_id($traspaso_id);

        echo $pnid =$_POST['producto_nid'];
        $uid=$GLOBALS['usuarioid'];
        $eid=$GLOBALS['empresaid'];
        $cantidad=$_POST['cantidad'];
        echo $pid = $_POST['cproductos_id'];
        $p = new Producto($pid);
        //$existencia = $this->get_existencia($pid,$pnid);
        //if($existencia < $cantidad){
        //    echo 0;
        //    return;
        //}
        //$e = $this->get_entrada_con_existencia($pid,$pnid);

        $this->guardar_salida_traspaso($traspaso,$cantidad,$p,$pnid,$uid,$eid);
        //$traspaso->cestatus_traspaso_id=1;
        //$traspaso->save();
           echo 1;		
	}
		
		function get_existencia($pid,$pnid){
		        $e = new Entrada();
		        $sql="select sum(e.cantidad) as suma from entradas as e where e.cproductos_id = $pid and e.estatus_general_id =1 and e.cproducto_numero_id = $pnid";
		        $e->query($sql);
		        $entrada =  $e->suma;
		        $sql="select sum(e.cantidad) as suma from salidas as e where e.cproductos_id = $pid and e.estatus_general_id =1 and e.cproducto_numero_id = $pnid";
		        $e->query($sql);
		        $salida = $e->suma;
		        return ($entrada -$salida);
		    }

function get_entrada_con_existencia($pid,$pnid){
    $e = new Entrada();
    $sql="select * from entradas as e where e.cproductos_id='$pid' and cproducto_numero_id='$pnid' and existencia>'0' limit 1";
    $e->query($sql);
    if($e->c_rows == 1){
        $en = new Entrada();
        return $en->get_by_id( $e->id);
    }
    else
        return false;
}
        
	function guardar_salida_traspaso($tid,$cantidad,$p,$pnid,$uid,$eid){

            $s= new Salida();
            $s->usuario_id=$uid;
            $s->empresas_id=$eid;
            $s->espacios_fisicos_id=$tid->espacio_fisico_envia_id;
            $s->cproductos_id=$p->id;
            $s->cproducto_numero_id  =$pnid;
            $s->cl_facturas_id=0;
            $s->ctipo_salida_id=2;
            $s->estatus_general_id=1;
            $s->cclientes_id=1;
            $s->fecha=date("Y-m-d H:i:s");
            $s->traspaso_id=$tid->id;
            $s->costo_unitario= $p->precio_compra;
            $s->tasa_impuesto=$p->tasa_impuesto;
            $s->costo_total=$p->precio_compra * $cantidad;
            //$s->lote_id=$e->lote_id;
            $s->cantidad=$cantidad;
            $s->existencia = $cantidad;
            //$s->cproveedores_id=$e->cproveedores_id;
            $s->save();             
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
					//		echo '<button type="submit" style="display:none;" id="boton1">Intentar de nuevo</button><br/>';
				} else {
					$pr->trans_commit();
					$ts->trans_commit();
				}
			}
			echo form_hidden('id'.$line, "$pr->id"); echo form_hidden('traspaso_id'.$line, "$traspasos_id"); echo "<a href=\"javascript:borrar_detalle($line)\"><img src=\"".base_url()."images/trash1.png\" width=\"20px\" title=\"Borrar Detalle\"/></a><img src=\"".base_url()."images/ok.png\" width=\"20px\" title=\"Guardado\"/>";
		} else {
			$pr->trans_rollback();
			show_error("Error al Editar el Traspaso, regresar a la pÃÂ¡gina anterior.");
		}
	}

	function alta_entrada_bonificacion(){
		//Guardar factura de proveedor
		$varP=$_POST;
		$line=$this->uri->segment(4);
		$pr= new Entrada();
		$pr->usuario_id=$GLOBALS['usuarioid'];
		$pr->empresas_id=$GLOBALS['empresaid'];
		$pr->espacios_fisicos_id=$GLOBALS['espacios_fisicos_id'];
		$pr->pr_facturas_id=$varP['pr_facturas_id'.$line];
		$pr->cproductos_id=$varP['producto'.$line];
		$pr->cantidad=$varP['unidades'.$line];
		$pr->estatus_general_id=1;
		$pr->costo_unitario=0;
		$pr->tasa_impuesto=0;
		$pr->costo_total=0;
		$pr->ctipo_entrada=9;
		$pr->cproveedores_id=$this->pr_factura->get_pr_factura_entrada($varP['pr_facturas_id'.$line]);
		$pr->fecha=date("Y-m-d H:i:s");
		if(isset($varP['id'.$line])==true){
			$pr->id=$varP['id'.$line];
		}
		// save with the related objects
		if($pr->save())
		{
			echo form_hidden('id'.$line, "$pr->id"); echo form_hidden('pr_facturas_id'.$line, "$pr->pr_facturas_id"); echo "<a href=\"javascript:borrar_detalle('$pr->id', $line)\"><img src=\"".base_url()."images/trash1.png\" width=\"20px\" title=\"Borrar Detalle\"/></a><img src=\"".base_url()."images/ok.png\" width=\"20px\" title=\"Guardado\"/>";
		}
		else
		{
			show_error("".$u->error->string);
		}
	}

	function alta_pr_factura_bonificacion(){
		$u= new Pr_factura();
		$u->usuario_id=$GLOBALS['usuarioid'];
		$u->empresas_id=$GLOBALS['empresaid'];
		$u->fecha_captura=date("Y-m-d H:i:s");
		$fecha=explode(" ", $_POST['fecha']);
		$estatus_factura_id=1;
		$u->fecha=$fecha[2]."/".$fecha[1]."/".$fecha[0];
		unset($_POST['fecha']);
		$related = $u->from_array($_POST);
		// save with the related objects
		if($u->save($related))
		{
			echo form_hidden('id', "$u->id"); echo '<button type="submit" id="boton1" style="display:none;">Registrar Detalles</button>';
		}
		else
		{
			show_error("".$u->error->string);
		}
	}
	function verificar_entrada_bonificacion(){
		$id=$this->uri->segment(4);
		$d=new Entrada();
		$d->select("count(id) as total");
		$d->where('pr_facturas_id', "$id");
		$d->get();
		$total=$d->total;
		if ($total>0){
			$p=new Pr_factura();
			$p->get_by_id($id);
			$pedido= new Pr_pedido();
			$pedido->where('id', "$p->pr_pedido_id");
			$pedido->get();
			$pedido->cpr_estatus_pedido_id=3;
			$pedido->save();
			$this->db->query("update control_actualizaciones set fecha_cambio='".date("Y/m/d")."' where id=3");
			//Se valida que la factura y la entrada poseen el mismo importe se da por terminado enviando al listado de entradas
			echo "<html> <script>alert(\"Se registrado el Alta de Entrada por BonificaciÃ³n de manera exitosa.\"); window.location='".base_url()."index.php/".$GLOBALS['ruta']."/almacen_c/formulario/list_entradas';</script></html>";

		} else {
	  /**No se encontro registro valido de productos dentro del pedido por lo cual se cancela*/
			echo "<html> <script>alert(\"Se ha eliminado el Pedido de Compra, intente de nuevo.\"); window.location='".base_url()."index.php/".$GLOBALS['ruta']."/compras_c/formulario/list_compras';</script></html>";
		}
	}

	function devolucion_nc(){
		//Devolucion con nota de crÃ©dito
		$id_salidas=$_POST['detalles'];
		$ids=explode(",", $id_salidas);
		//Dar de alta la nota de crÃ©dito
		$n=new Nota_credito();
		$n->pr_factura_id=0;
		$n->usuario_id=$GLOBALS['usuarioid'];
		$n->cl_factura_id=$_POST['cl_factura'];
		$n->folio=$_POST['folio'];
		$n->ctipo_nota_id=1;
		if($_POST['fecha']=='')
			$_POST['fecha']=date("d-m-Y");
		$fecha=explode(" ", $_POST['fecha']);
		$n->fecha=$fecha[2]."/".$fecha[1]."/".$fecha[0];
		$n->fecha_captura=date("Y-m-d H:i:s");
		$n->save();
		$total=0;
		for($c=0;$c<count($ids)-1;$c++){
			$id=substr($ids[$c], 0, strripos($ids[$c], '&'));
			$cantidad=substr($ids[$c], strripos($ids[$c], '&')+1, strlen($ids[$c]));
			// 	      $this->db->query("update salidas set ctipo_salida_id='7' where id=$id");
			$salida=$this->salida->get_salida($id);
			if($salida==false)
				show_error("La salida con Id= $id, no existe");

			$e=new Entrada();
			$e->cproductos_id=$salida->cproductos_id;
			$e->cantidad=$cantidad;
			$e->nota_credito_id=$n->id;
			$e->costo_unitario=$salida->costo_unitario;
			$e->tasa_impuesto=$salida->tasa_impuesto;
			$e->costo_total=$cantidad*$salida->costo_unitario;
			$e->ctipo_entrada=4;
			$e->usuario_id=$GLOBALS['usuarioid'];
			$e->espacios_fisicos_id=$salida->espacios_fisicos_id;
			$e->fecha=$fecha[2]."/".$fecha[1]."/".$fecha[0];
			$e->cproveedores_id=0;
			$e->save();
			//	      echo $id.'&'.$cantidad."<br/>";
			$cl_factura=$salida->cl_facturas_id;
			$total+=$e->costo_total;
		}
		$n->cl_factura=$cl_factura;
		$n->monto_total=$total;
		$n->save();
		//	echo "<html> <script>alert(\"Se registrado la devoluciÃ³n, y se genero la nota de c?edito folio: $n->folio de manera exitosa.\"); window.location='".base_url()."index.php/".$GLOBALS['ruta']."/almacen_reportes/nota_credito_pdf/$n->id';</script></html>";
		echo "<html>Se registrado la devoluciÃ³n, y se genero la nota de crÃ©dito folio: $n->folio de manera exitosa</html>";

	}

	function devolucion_compra(){
		//Devolucion con nota de crÃ©dito
		$id_entradas=$_POST['detalles'];
		$ids=explode(",", $id_entradas);
		//Dar de alta la nota de crÃ©dito
		$n=new Nota_credito();
		$n->cl_factura_id=0;
		$n->usuario_id=$GLOBALS['usuarioid'];
		$n->pr_factura=$_POST['pr_factura'];
		$n->ctipo_nota_id=$_POST['ctipo_nota_id'];
		$n->folio=$_POST['folio'];
		if($_POST['fecha']=='')
			$_POST['fecha']=date("d-m-Y");
		$fecha=explode(" ", $_POST['fecha']);
		$n->fecha=$fecha[2]."/".$fecha[1]."/".$fecha[0];
		$n->fecha_captura=date("Y-m-d H:i:s");
		$n->save();
		$total=0;
		for($c=0;$c<count($ids)-1;$c++){
			$id=substr($ids[$c], 0, strripos($ids[$c], '&'));
			$cantidad=substr($ids[$c], strripos($ids[$c], '&')+1, strlen($ids[$c]));
			// 	      $this->db->query("update salidas set ctipo_salida_id='7' where id=$id");
			$entrada=$this->entrada->get_entrada($id);
			if($entrada==false)
		  show_error("La entrada con Id= $id, no existe");

			$e=new Salida();
			$e->cproductos_id=$entrada->cproductos_id;
			$e->cantidad=$cantidad;
			$e->nota_credito_id=$n->id;
			$e->costo_unitario=$entrada->costo_unitario;
			$e->tasa_impuesto=$entrada->tasa_impuesto;
			$e->costo_total=$cantidad*$entrada->costo_unitario;
			$e->ctipo_salida_id=7;
			$e->estatus_general_id=1;
			$e->usuario_id=$GLOBALS['usuarioid'];
			$e->espacios_fisicos_id=$entrada->espacios_fisicos_id;
			$e->fecha=$fecha[2]."/".$fecha[1]."/".$fecha[0];
			$e->cproveedores_id=0;
			$e->save();
			//	      echo $id.'&'.$cantidad."<br/>";
			$pr_factura=$entrada->pr_facturas_id;
			$total+=$e->costo_total;
		}
		$n->pr_factura=$pr_factura;
		$n->monto_total=$total;
		$n->save();
		//	echo "<html> <script>alert(\"Se registrado la devoluciÃ³n, y se genero la nota de c?edito folio: $n->folio de manera exitosa.\"); window.location='".base_url()."index.php/".$GLOBALS['ruta']."/almacen_reportes/nota_credito_pdf/$n->id';</script></html>";
		echo "<html>Se registrado la devolución de la compra, y se genero la nota de crÃ©dito folio: $n->folio de manera exitosa</html>";

	}

function editar_producto_combo(){
         $u = new Cproducto_combo();
 $u->cproducto_id_combo=$_POST['id_combo'];  
  $u->cproducto_numeracion_id_combo=$_POST['combo_numeracion'];        
         $u->cproductos_id_relacion=$_POST['cproducto_id_relacion'];
         $u->cproducto_numeracion_id_relacion=$_POST['cproducto_numeracion_id_relacion'];
$this->db->query("update cproductos_combo set estatus_general_id='2' where cproducto_id_combo=$u->cproducto_id_combo and cproducto_numeracion_id_combo=$u->cproducto_numeracion_id_combo and cproducto_id_relacion=$u->cproductos_id_relacion and cproducto_numeracion_id_relacion=$u->cproducto_numeracion_id_relacion");
       echo 1;
        
         
    }


	function editar_pr_factura(){
		//Guardar factura de proveedor

		 $u = new entrada();
        $u->usuario_id = $GLOBALS['usuarioid'];
        $u->empresas_id = $GLOBALS['empresaid'];
	$precio=$_POST['costo_unitario'];
	$producto_id=$_POST['cproductos_id'];
        $u->fecha = date("Y-m-d H:i:s");
         $related = $u->from_array($_POST);
              if ($u->id == 0)
            unset($u->id);
        if ($u->save($related)) {
$this->db->query("update cproductos set precio_compra='$precio' where id=$producto_id");
 echo $u->id;
        } else
            echo 0; 
        
	}

	function editar_pr_factura_inventario(){
		//Guardar factura de proveedor

		 $u = new entrada();
        $u->usuario_id = $GLOBALS['usuarioid'];
        $u->empresas_id = $GLOBALS['empresaid'];
	$precio=$_POST['costo_unitario'];
	$producto_id=$_POST['cproductos_id'];
        $u->fecha = date("Y-m-d H:i:s");
         $related = $u->from_array($_POST);
	 $f = new Pr_factura();
        $f->get_by_id($u->pr_facturas_id);
        $u->espacios_fisicos_id = $f->espacios_fisicos_id;
        $u->lote_id = $f->lote_id;
        $u->costo_total = ($u->cantidad * $u->costo_unitario);
	$u->existencia = $u->cantidad;
        $u->cproveedores_id = $f->cproveedores_id;
        $u->fecha = date("Y-m-d H:i:s");
              if ($u->id == 0)
            unset($u->id);
        if ($u->save($related)) {
$this->db->query("update cproductos set precio_compra='$precio' where id=$producto_id");
 echo $u->id;
        } else
            echo 0; 
        
	}




function verificar_entrada_inventario() {
        $id = $this->uri->segment(4);
        $d = new Entrada();
        $d->select("sum(costo_total) as total, sum(tasa_impuesto * cantidad * costo_unitario/(100+tasa_impuesto)) as impuesto, sum(cantidad) as cantidad");
        $d->where('pr_facturas_id', "$id");
        $d->get();
        $total = $d->total;
        $impuesto = $d->impuesto;
        if ($d->cantidad > 0) {
            $p = new Pr_factura();
            $p->get_by_id($id);
            $p->monto_total = $total;
            $p->descuento = $total * $p->porcentaje_descuento / 100;
            $p->iva_total = $impuesto;
            if ($p->save()) {
                $this->db->query("update control_actualizaciones set fecha_cambio='" . date("Y/m/d") . "' where id=3");
                echo "<html> <script>alert(\"Se registrado el Alta de Entrada de manera exitosa.\"); window.location='" . base_url() . "index.php/" . $GLOBALS['ruta'] . "/almacen_c/formulario/list_inventario_inicial';</script></html>";
            } else {
                show_error("1" . $p->error->string);
                /*                 * No se encontro registro valido de productos dentro del pedido por lo cual se cancela */
                echo "<html> <script>alert(\"Se ha eliminado el Pedido de Compra, intente de nuevo.\"); window.location='" . base_url() . "index.php/" . $GLOBALS['ruta'] . "/almacen_c/formulario/list_inventario_inicial/';</script></html>";
            }
        } else {
            show_error("2" . $p->error->string);
            /*             * No se encontro registro valido de productos dentro del pedido por lo cual se cancela */
            echo "<html> <script>alert(\"Se ha eliminado el Pedido de Compra, intente de nuevo.\"); window.location='" . base_url() . "index.php/" . $GLOBALS['ruta'] . "/almacen_c/formulario/list_entradas';</script></html>";
        }
    }

function editar_inventario_inicial(){
         $u = new entrada();
         $u->usuario_id = $GLOBALS['usuarioid'];
        $u->empresas_id = $GLOBALS['empresaid'];
        $u->fecha = date("Y-m-d H:i:s");
         $u->pr_facturas_id=$_POST['pr_facturas_id'];
         $u->cproductos_id=$_POST['cproductos_id'];
         $u->cproducto_numero_id=$_POST['cproducto_numero_id'];
$this->db->query("update entradas set estatus_general_id='2' where pr_facturas_id='$u->pr_facturas_id' and cproductos_id=$u->cproductos_id and cproducto_numero_id=$u->cproducto_numero_id");
       echo 1;
        
         
    }
function editar_entrada_compra(){
         $u = new entrada();
         $u->usuario_id = $GLOBALS['usuarioid'];
        $u->empresas_id = $GLOBALS['empresaid'];
        $u->fecha = date("Y-m-d H:i:s");
         $u->pr_facturas_id=$_POST['pr_facturas_id'];
         $u->cproductos_id=$_POST['cproductos_id'];
         $u->cproducto_numero_id=$_POST['cproducto_numero_id'];
$this->db->query("update entradas set estatus_general_id='1', pr_facturas_id=0 where pr_facturas_id='$u->pr_facturas_id' and cproductos_id=$u->cproductos_id and cproducto_numero_id=$u->cproducto_numero_id");
       echo 1;
        
         
    }
    
    function borrar_traspaso(){
         $u = new entrada();
         $u->usuario_id = $GLOBALS['usuarioid'];
        $u->empresas_id = $GLOBALS['empresaid'];
        $u->fecha = date("Y-m-d H:i:s");
         $u->pr_facturas_id=$_POST['pr_facturas_id'];
         $u->cproductos_id=$_POST['cproductos_id'];
         $u->cproducto_numero_id=$_POST['cproducto_numero_id'];
$this->db->query("update salidas set estatus_general_id='2' where traspaso_id='$u->pr_facturas_id' and cproductos_id=$u->cproductos_id and cproducto_numero_id=$u->cproducto_numero_id");
       echo 1;
        
         
    }

function editar_precio_inventario_inicial(){
         $u = new entrada();
         $u->usuario_id = $GLOBALS['usuarioid'];
        $u->empresas_id = $GLOBALS['empresaid'];
        $u->fecha = date("Y-m-d H:i:s");
         $u->pr_facturas_id=$_POST['pr_facturas_id'];
	$u->costo_unitario=$_POST['costo_unitario'];	
				$u->cantidad=$_POST['cantidad'];	
         $u->cproductos_id=$_POST['cproductos_id'];
         $u->cproducto_numero_id=$_POST['cproducto_numero_id'];
         $costo=	$u->cantidad*$u->costo_unitario;
$this->db->query("update entradas set costo_unitario=$u->costo_unitario, cantidad=$u->cantidad, costo_total=$costo where pr_facturas_id='$u->pr_facturas_id' and cproductos_id=$u->cproductos_id and cproducto_numero_id=$u->cproducto_numero_id");
       echo 1;
        
         
    }
    
function editar_traspaso_enviado(){
         $u = new entrada();
         $u->usuario_id = $GLOBALS['usuarioid'];
        $u->empresas_id = $GLOBALS['empresaid'];
        $u->fecha = date("Y-m-d H:i:s");
         $u->pr_facturas_id=$_POST['pr_facturas_id'];
	$u->costo_unitario=$_POST['costo_unitario'];	
				$u->cantidad=$_POST['cantidad'];	
         $u->cproductos_id=$_POST['cproductos_id'];
         $u->cproducto_numero_id=$_POST['cproducto_numero_id'];
         $costo=	$u->cantidad*$u->costo_unitario;
$this->db->query("update salidas set cantidad=$u->cantidad  where traspaso_id='$u->pr_facturas_id' and cproductos_id=$u->cproductos_id and cproducto_numero_id=$u->cproducto_numero_id");
       echo 1;
        
         
    }    
    
    

	function verificar_traspaso(){
		$traspaso_id=$this->uri->segment(4);
		if($traspaso_id>0){
			//Leer las salidas de la tabla traspasos_salidas
			$salidas_t=$this->traspaso_salida->get_salidas_traspaso($traspaso_id);
			//Leer las salidas de la tabla salidas
			$salidas=$this->salida->get_salida($traspaso_id);
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
		  		if($s->cproductos_id==$entr->cproductos_id and $s->cantidad==$entr->cantidad){
		  			$id=$entr->id;
		  		}
		  	}
		  } else {
		  	$t=1;
		  }
		  //Verificar que exista la entrada
		  $e=new Entrada();
		  if($id>0){
		  	$e->id=$id;
		  	$t=0;
		  } else {
		  	$t=1;
		  }
		  $e->usuario_id=$GLOBALS['usuarioid'];
		  $e->fecha=$s->fecha;
		  $e->espacios_fisicos_id=$GLOBALS['espacios_fisicos_id'];
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
			}

			echo "<html> <script>alert(\"Se ha registrado el Ingreso Local del Traspaso con id= $traspaso_id correctamente.\"); window.location='".base_url()."index.php/".$GLOBALS['ruta']."/tienda_c/formulario/list_transferencias/';</script></html>";

		} else {
			echo "<html> <script>alert(\"Ha ocurrido un problema con el Ingreso Local del Traspaso con id= $traspaso_id , favor de comunicarse con el Ãrea de Asistencia y Soporte TÃ©cnico.\"); window.location='".base_url()."index.php/".$GLOBALS['ruta']."/tienda_c/formulario/list_transferencias/';</script></html>";
		}

	}
	/**Funciones para generar traspasos en el almacen en base a las plantillas de Stock*/
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
				$t->ubicacion_entrada_id=$_POST['ubicacion_entrada_id'];
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
		if(isset($varP['id'.$line])==true)
			$pr->id=$varP['id'.$line];

		if($pr->save()){
			echo form_hidden('id'.$line, "$pr->id"); echo form_hidden('cl_pedidos_id'.$line, "$pr->cl_pedidos_id"); echo "<a href=\"javascript:borrar_detalle('$pr->id', $line)\"><img src=\"".base_url()."images/trash1.png\" width=\"20px\" title=\"Borrar Detalle\"/></a><img src=\"".base_url()."images/ok.png\" width=\"20px\" title=\"Guardado\"/>";
		}else{
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
			echo "<html> <script>alert(\"El pedido de Traspaso con el Id : $id se ha registrado correctamente.\"); window.location='".base_url()."index.php/".$GLOBALS['ruta']."/tienda_c/formulario/list_traspasos_almacen/';</script></html>";
		} else {
			/**No se encontro registro valido de productos dentro del pedido por lo cual se cancela*/
			$this->db->trans_start();
			$this->db->query("delete from cl_detalle_pedidos where cl_pedidos_id='$id'");
			$this->db->query("delete from cl_pedidos where id='$id'");
			$this->db->query("delete from traspasos where cl_pedido_id='$id'");
			$this->db->trans_complete();
			echo "<html> <script>alert(\"Se ha eliminado el Pedido de Traspaso por falta de conceptos, intente de nuevo.\"); window.location='".base_url()."index.php/".$GLOBALS['ruta']."/almacen_c/formulario/list_traspasos_almacen';</script></html>";
		}
	}

	function migrar_catalogo(){
		$f_id=$this->uri->segment(4);
		//Primer paso seleccionar por departamento
		$r=0;
		$departamentos=$this->db->query("select * from cproductos_familias where id=$f_id order by tag");
		if($departamentos->num_rows()>0){
			//Recorrer por departamentos para que no truene el script
			foreach($departamentos->result() as $row){
				$zapatos=$this->db->query("select * from catalogo_inicial where clasificacion_1 like '%$row->tag%' order by nombre");
				if($zapatos->num_rows()>0){
					//Recorrer cada registro de la tabla catalogo inicial
					$nombre_anterior="";
					foreach($zapatos->result() as $zap){
						$nombre_completo=$zap->nombre;
						/**Caso para Catalogo General */
						$gato=strpos($nombre_completo, "#");
						//$numero=floatval(trim(substr($nombre_completo,$gato+1,100)))*(10);
						/**Caso para Catalogo Barata */
						if($zap->clasificacion_6=='VARIOS NUMEROS')
							$numero=0;
						else
							$numero=floatval(trim($zap->clasificacion_6));
						$nombre=substr($nombre_completo,0,$gato-1);

						$producto=$this->db->query("select * from cproductos where descripcion ='BARATA -".trim($nombre)."'");
						if($producto->num_rows()==0){
							//Obtener el departamento
							$familia_id=$row->id;
							//Obtener el tipo
							$subfamilia=$this->db->query("select * from cproductos_subfamilias where tag = '$zap->clasificacion_2'");
							if($subfamilia->num_rows()==1){
								$subf=$subfamilia->row();
								$subfamilia_id=$subf->id;
							} else {
								$this->db->query("insert into cproductos_subfamilias(clave,tag,fecha_alta, usuario_id) values ('','".trim($zap->clasificacion_2)."', '2010-12-12',1)");
								$subfamilia_id=$this->db->insert_id();
								//$subfamilia_id=0;
							}
							//Obtener el material
							$material=$this->db->query("select * from cproductos_material where tag = '".trim($zap->clasificacion_3)."'");
							if($material->num_rows()==1){
								$subf=$material->row();
								$material_id=$subf->id;
							} else {
								$this->db->query("insert into cproductos_material(clave,tag,fecha_captura, usuario_id) values ('','".trim($zap->clasificacion_3)."', '2010-12-12',1)");
								$material_id=$this->db->insert_id();
								//$material_id=0;
							}

							//Obtener el Color
							$color=$this->db->query("select * from cproductos_color where tag = '".trim($zap->clasificacion_5)."'");
							if($color->num_rows()>0){
								$subf=$color->row();
								$color_id=$subf->id;
							} else {
								//Dar de alta la marca
								$this->db->query("insert into cproductos_color(clave,tag,fecha_captura) values ('','".trim($zap->clasificacion_5)."', '2010-12-12')");
								$color_id=$this->db->insert_id();
							}

							//Obtener Marca
							$marca=$this->db->query("select * from cmarcas_productos where tag = '".trim($zap->clasificacion_4)."'");
							if($marca->num_rows()==1){
								$subf=$marca->row();
								$marca_id=$subf->id;
							} else {
								//Dar de alta la marca
								$this->db->query("insert into cmarcas_productos(tag, proveedor_id) values ('".trim($zap->clasificacion_4)."', 1)");
								$marca_id=$this->db->insert_id();
							}
							echo "$r Ingresado: ".$zap->nombre."</br>";
							//Insertar el REgistro en la tabla cproductos
							$p= new Producto();
							$p->descripcion="BARATA -".trim($nombre);
							$p->cmarca_producto_id=$marca_id;
							$p->cfamilia_id=$familia_id;
							$p->csubfamilia_id=$subfamilia_id;
							$p->precio1=$zap->precio_1;
							$p->precio2=0;
							$p->precio3=0;
							$p->fecha_alta='2010-12-12';
							$p->usuario_id=1;
							$p->comision_venta=0;
							$p->empresas_id_bin=1;
							$p->cmaterial_id=$material_id;
							$p->ccolor_id=$color_id;
							$p->ruta_foto='';
							if($p->save()){
								//Insert Registro del numero correspondiente en la tabla cproductos_numeracion
								$pn= new Producto_numeracion();
								$pn->cproducto_id=$p->id;
								$pn->numero_mm=$numero;
								$pn->clave_anterior=$zap->codigo;
								$pn->codigo_barras=0;
								$pn->usuario_id=1;
								$pn->fecha_captura='2010-12-12';
								$pn->save();
							}
							$r+=1;
							$nombre_anterior=$nombre;
						} else {
							//Dar entrada a la tabla cproductos_numeracion, buscar por descripcion el id de producto
							$producto=$this->db->query("select * from cproductos where descripcion='BARATA -".trim($nombre)."'");
							if($producto->num_rows()==1){
								$prod=$producto->row();
								//Insert Registro del numero correspondiente en la tabla cproductos_numeracion
								$pn= new Producto_numeracion();
								$pn->cproducto_id=$prod->id;
								$pn->numero_mm=$numero;
								$pn->clave_anterior=$zap->codigo;
								$pn->codigo_barras=0;
								$pn->usuario_id=1;
								$pn->fecha_captura='2010-12-12';
								$pn->save();
								echo "$r Numero agregado: ".$zap->nombre."</br>";
							} else {
								echo "Error 101: $zap->nombre</br>";
							}
						}
					}
				}
			}
			echo $r;
		}
	}

	function editar_color(){
		$colore=new Color_producto($this->uri->segment(4));
		$colore->tag=$this->input->post('tag');
		$colore->estatus_general_id=$this->input->post('estatus_general_id');
		$colore->fecha_cambio=date("Y-m-d H:i:s");
		$colore->usuario_id = $GLOBALS['usuarioid'];

		if($colore->save()){
			echo "<html> <script>alert(\"Se ha actualizado el color correctamente.\"); window.location='" . base_url() . "index.php/almacen/productos_c/formulario/list_colores';</script></html>";
		}
	}
	function alta_color(){
		//Guardar la Marca de Productos

		$clave = $this->input->post('tag');
		$permitidos = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-_ ";
		$cont=0;

		for ($i = 0; $i < strlen($clave); $i++) {
			if (strpos($permitidos, substr($clave, $i, 1)) === false) {
				$cont++;
				echo "<html> <script>alert(\"EL color contiene caracteres invalidos.\"); window.location='".base_url()."index.php/almacen/productos_c/formulario/alta_color';</script></html>";
			}
		}
		if($cont==0){
			$p = new Color_producto();
			$p->where('tag', "$clave");
			$p->get();
			if ($p->c_rows > 0) {
				echo "<html> <script>alert(\"Ya existe el color.\"); window.location='".base_url()."index.php/almacen/productos_c/formulario/alta_color';</script></html>";

			} else {
				$u= new Color_producto();
				$u->fecha_captura=date("Y/m/d");
				$u->fecha_cambio=date("Y/m/d");
				$u->usuario_id=$GLOBALS['usuarioid'];
				$u->clave="\"";
				$related = $u->from_array($_POST);

				// save with the related objects
				if($u->save($related)) {
					echo "<html> <script>alert(\"Se ha registrado el color.\"); window.location='".base_url()."index.php/almacen/productos_c/formulario/list_colores';</script></html>";
				} else
					show_error("".$u->error->string);
			}
		}
	}

	function catalogo_corridas(){
		$f_id=$this->uri->segment(4);
		//Primer paso seleccionar por departamento
		$r=1;
		//$zapatos=$this->db->query("select * from cproductos where cfamilia_id='$f_id' and descripcion not like 'BARATA -%'order by descripcion");
		// 		$zapatos=$this->db->query("select * from cproductos where cfamilia_id='$f_id' and descripcion='( 0 - 0 )' order by descripcion");
		$zapatos=$this->db->query("select * from cproductos where id='32' order by descripcion");
		if($zapatos->num_rows()>0){
			$clave_pre=""; $precio_pre=0; $collect_array=array(); $sx=0;
			//bebe
			$corridas['bebe'][1]="90,115";
			$corridas['ninos'][1]="120,145";
			$corridas['ninos'][2]="150,175";
			$corridas['ninos'][3]="180,215";
			$corridas['dama'][1]="220,270";
			$corridas['caballero'][1]="220,245";
			$corridas['caballero'][2]="250,300";
			$corridas['joven'][2]="220,265";

			foreach($zapatos->result() as $zap){
				$sx=0;
				$b=0; $n1=0;$n2=0;$n3=0;$d=0;$c1=0;$c2=0;$c3=0;

				//Obtener los numeros de cada cproducto
				$numeros=$this->db->query("select id, numero_mm, clave_anterior, cproducto_id from cproductos_numeracion where cproducto_id='$zap->id' order by numero_mm");
				if($numeros->num_rows()>0) {
					foreach($numeros->result() as $num){
						$sx=0;
						//Checar numeracion de las corridas
						if($num->numero_mm<=120){
							$collect_array['bebe'][$b]['nid']=$num->id;
							$collect_array['bebe'][$b]['numero_mm']=$num->numero_mm;
							$b+=1;
						}
						if($num->numero_mm<=145 and $num->numero_mm>120){
							$collect_array['n1'][$n1]['nid']=$num->id;
							$collect_array['n1'][$n1]['numero_mm']=$num->numero_mm;
							$n1+=1;
						}
						if($num->numero_mm<=175 and $num->numero_mm>145){
							$collect_array['n2'][$n2]['nid']=$num->id;
							$collect_array['n2'][$n2]['numero_mm']=$num->numero_mm;
							$n2+=1;
						}
						if($num->numero_mm<=215 and $num->numero_mm>175){
							$collect_array['n3'][$n3]['nid']=$num->id;
							$collect_array['n3'][$n3]['numero_mm']=$num->numero_mm;
							$n3+=1;
						}
						//Dama
						if($f_id==1){
							if($num->numero_mm<=270 and $num->numero_mm>=220){
								$collect_array['d'][$d]['nid']=$num->id;
								$collect_array['d'][$d]['numero_mm']=$num->numero_mm;
								$d+=1;
							}
						}
						//CAballero
						if($f_id==2){
							if($num->numero_mm<=245 and $num->numero_mm>215){
								$collect_array['c1'][$c1]['nid']=$num->id;
								$collect_array['c1'][$c1]['numero_mm']=$num->numero_mm;
								$c1+=1;
							}
							if($num->numero_mm<=300 and $num->numero_mm>245){
								$collect_array['c2'][$c2]['nid']=$num->id;
								$collect_array['c2'][$c2]['numero_mm']=$num->numero_mm;
								$c2+=1;
							}
							// 							if($num->numero_mm<=265 and $num->numero_mm>=220){
							// 								$collect_array[$sx]['c3']['nid']=$num->id;
							// 								$collect_array[$sx]['c3']['numero_mm']=$num->numero_mm;
							// 								$c1+=1;
							// 							}
						}
						$sx+=1;
					} //Final bucle numeracion
					unset($numeros);
					/** Con la matriz collect_array corregir la descripcion del zapato y agregar las entradas para cada rango */
					if(isset($collect_array)==false)
						$n=0;
					else
						$n=count($collect_array);
					$corrida_n="";
					$c=0;
					$titulos[0]="bebe";
					$titulos[1]="n1";
					$titulos[2]="n2";
					$titulos[3]="n3";
					$titulos[4]="d";
					$titulos[5]="c1";
					$titulos[6]="c2";
					// 					$titulos[7]="c3";
					//print_r($collect_array);
					foreach ($titulos as $k=>$v){
						if(isset($collect_array) and isset($collect_array[$v][0])){
							$limite_min=round($collect_array[$v][0]['numero_mm']/10,1);
							$ca=count($collect_array[$v])-1;
							echo "Numero-$ca: ".$collect_array[$v][$ca]['numero_mm']."?";

							if($ca<0){
								$ca=0;
							}
							$limite_max=round($collect_array[$v][$ca]['numero_mm']/10,1);
							$string=" ( ".$limite_min." - ".$limite_max." )";

							$prod_num=$this->db->query("select * from cproductos_numeracion where id='{$collect_array[$v][$ca]['nid']}'");
							$rt=$prod_num->row();
							//echo $rt->clave_anterior."///////<br/>";

							$clave_anterior=$rt->clave_anterior;
							$prod_init=$this->db->query("select * from catalogo_inicial where codigo='$clave_anterior' ");
							if($prod_init->num_rows()==1){
								$cat=$prod_init->row();
								$precio1=$cat->precio_1;
								$nombre_completo=$cat->nombre;
								/**Caso para Catalogo General */
								$gato=strpos($nombre_completo, "#");
								// 								$descripcion=substr($nombre_completo,0,$gato);
								$descripcion=$nombre_completo;
							} else {
								$precio1=$zap->precio1;
								$par=strpos($zap->descripcion, " (");
								$descripcion=substr($zap->descripcion,0, $par);
							}
							$c+=1;
							$string="";
							if($c==1){
								//Update cproducto descripcion
								$this->db->query("update cproductos set descripcion='$descripcion $string', numero_min='$limite_min', num_max='$limite_max' where id=$zap->id");
								echo $r."-".$descripcion. $string .$zap->id."&<br/>";
							} if ($c>1){
								//Dar de alta los registros de los nuevos productos
								if($ca>0){
									$nuevp=new Producto();
									$nuevp->descripcion= $descripcion." ". $string;
									$nuevp->cmarca_producto_id=$zap->cmarca_producto_id;
									$nuevp->cfamilia_id=$zap->cfamilia_id;
									$nuevp->csubfamilia_id=$zap->csubfamilia_id;
									$nuevp->precio1=$precio1;
									$nuevp->precio2=0;
									$nuevp->precio3=0;
									$nuevp->cmaterial_id=$zap->cmaterial_id;
									$nuevp->ccolor_id=$zap->ccolor_id;
									$nuevp->tasa_impuesto=$zap->tasa_impuesto;
									$nuevp->ruta_foto=$zap->ruta_foto;
									$nuevp->numero_min=$limite_min;
									$nuevp->num_max=$limite_max;
									$nuevp->precio_compra=$zap->precio_compra;
									$nuevp->usuario_id=$zap->usuario_id;
									$nuevp->observaciones="";
									if($nuevp->save()){
										//Actualizar el id de la numeracion al nuevo producto
										for($y=0;$y<$ca+1;$y++){
											if($collect_array[$v][$y]['nid']>0){
												$this->db->query("update cproductos_numeracion set cproducto_id='$nuevp->id' where id={$collect_array[$v][$y]['nid']}");
												echo $r."-NUEVO- ".$nuevp->id ." $nuevp->descripcion&<br/>";
											}
										}
										$nuevp->clear();

									}
								}
							}
						}
					}
					//						$c+=1;
					unset($collect_array);
				}
				$r+=1;
			}// Final bucle zapato
			unset($zapato);
		}
	}

	function catalogo_corridas_baratas(){
		$f_id=$this->uri->segment(4);
		//Primer paso seleccionar por departamento
		$r=1;
		//$zapatos=$this->db->query("select * from cproductos where cfamilia_id='$f_id' and descripcion not like 'BARATA -%'order by descripcion");
		$zapatos=$this->db->query("select * from cproductos where cfamilia_id='$f_id' and descripcion like 'BARATA -%' order by descripcion");
		// 		$zapatos=$this->db->query("select * from cproductos where id='12814' order by descripcion");
		if($zapatos->num_rows()>0){
			$clave_pre=""; $precio_pre=0; $collect_array=array(); $sx=0;
			//bebe
			$corridas['bebe'][1]="90,115";
			$corridas['ninos'][1]="120,145";
			$corridas['ninos'][2]="150,175";
			$corridas['ninos'][3]="180,215";
			$corridas['dama'][1]="220,270";
			$corridas['caballero'][1]="220,245";
			$corridas['caballero'][2]="250,300";
			$corridas['joven'][2]="220,265";

			foreach($zapatos->result() as $zap){
				$sx=0;
				$b=0; $n1=0;$n2=0;$n3=0;$d=0;$c1=0;$c2=0;$c3=0;

				//Obtener los numeros de cada cproducto
				$numeros=$this->db->query("select id, numero_mm, clave_anterior, cproducto_id from cproductos_numeracion where cproducto_id='$zap->id' order by numero_mm");
				if($numeros->num_rows()>0) {
					foreach($numeros->result() as $num){
						$sx=0;
						//Checar numeracion de las corridas
						if($num->numero_mm<=120){
							$collect_array['bebe'][$b]['nid']=$num->id;
							$collect_array['bebe'][$b]['numero_mm']=$num->numero_mm;
							$b+=1;
						}
						if($num->numero_mm<=145 and $num->numero_mm>120){
							$collect_array['n1'][$n1]['nid']=$num->id;
							$collect_array['n1'][$n1]['numero_mm']=$num->numero_mm;
							$n1+=1;
						}
						if($num->numero_mm<=175 and $num->numero_mm>145){
							$collect_array['n2'][$n2]['nid']=$num->id;
							$collect_array['n2'][$n2]['numero_mm']=$num->numero_mm;
							$n2+=1;
						}
						if($num->numero_mm<=215 and $num->numero_mm>175){
							$collect_array['n3'][$n3]['nid']=$num->id;
							$collect_array['n3'][$n3]['numero_mm']=$num->numero_mm;
							$n3+=1;
						}
						//Dama
						if($f_id==1){
							if($num->numero_mm<=270 and $num->numero_mm>=220){
								$collect_array['d'][$d]['nid']=$num->id;
								$collect_array['d'][$d]['numero_mm']=$num->numero_mm;
								$d+=1;
							}
						}
						//CAballero
						if($f_id==2){
							if($num->numero_mm<=245 and $num->numero_mm>215){
								$collect_array['c1'][$c1]['nid']=$num->id;
								$collect_array['c1'][$c1]['numero_mm']=$num->numero_mm;
								$c1+=1;
							}
							if($num->numero_mm<=300 and $num->numero_mm>245){
								$collect_array['c2'][$c2]['nid']=$num->id;
								$collect_array['c2'][$c2]['numero_mm']=$num->numero_mm;
								$c2+=1;
							}
							// 							if($num->numero_mm<=265 and $num->numero_mm>=220){
							// 								$collect_array[$sx]['c3']['nid']=$num->id;
							// 								$collect_array[$sx]['c3']['numero_mm']=$num->numero_mm;
							// 								$c1+=1;
							// 							}
						}
						$sx+=1;
					} //Final bucle numeracion
					unset($numeros);
					/** Con la matriz collect_array corregir la descripcion del zapato y agregar las entradas para cada rango */
					if(isset($collect_array)==false)
						$n=0;
					else
						$n=count($collect_array);
					$corrida_n="";
					$c=0;
					$titulos[0]="bebe";
					$titulos[1]="n1";
					$titulos[2]="n2";
					$titulos[3]="n3";
					$titulos[4]="d";
					$titulos[5]="c1";
					$titulos[6]="c2";
					// 	$titulos[7]="c3";
					//print_r($collect_array);
					foreach ($titulos as $k=>$v){
						if(isset($collect_array) and isset($collect_array[$v][0])){
							$limite_min=$collect_array[$v][0]['numero_mm']/10;
							$ca=count($collect_array[$v])-1;
							echo "Numero-$ca: ".$collect_array[$v][$ca]['numero_mm']."?";

							if($ca<0){
								$ca=0;
							}
							$limite_max=round($collect_array[$v][$ca]['numero_mm']/10,1);
							$string=" ( ".$limite_min." - ".$limite_max." )";

							$prod_num=$this->db->query("select * from cproductos_numeracion where id='{$collect_array[$v][$ca]['nid']}'");
							$rt=$prod_num->row();
							//echo $rt->clave_anterior."///////<br/>";

							$clave_anterior=$rt->clave_anterior;
							$prod_init=$this->db->query("select * from catalogo_inicial where codigo='B$clave_anterior' ");
							if($prod_init->num_rows()==1){
								echo "<br/>->$cat->codigo";
								$cat=$prod_init->row();
								$precio1=$cat->precio_1;
								$nombre_completo=$cat->nombre;
								/**Caso para Catalogo General */
								$gato=strpos($nombre_completo, "#");
								$descripcion="BARATA -".substr($nombre_completo,0,$gato);
								$descripcion='BARATA -'.$nombre_completo;
							} else {
								$precio1=$zap->precio1;
								$par=strpos($zap->descripcion, " (");
								if($par==0)
									$descripcion=$zap->descripcion;
								else
									$descripcion=substr($zap->descripcion,0, $par);
								// 								$string="";
							}
							$c+=1;

							if($c==1){
								//Update cproducto descripcion
								$this->db->query("update cproductos set descripcion='$descripcion $string', numero_min='$limite_min', num_max='$limite_max' where id=$zap->id");
								echo $r."-".$descripcion. $string .$zap->id."&<br/>";
							} if ($c>1){
								//Dar de alta los registros de los nuevos productos
								if($ca>0){
									$nuevp=new Producto();
									$nuevp->descripcion= $descripcion." ". $string;
									$nuevp->cmarca_producto_id=$zap->cmarca_producto_id;
									$nuevp->cfamilia_id=$zap->cfamilia_id;
									$nuevp->csubfamilia_id=$zap->csubfamilia_id;
									$nuevp->precio1=$precio1;
									$nuevp->precio2=0;
									$nuevp->precio3=0;
									$nuevp->cmaterial_id=$zap->cmaterial_id;
									$nuevp->ccolor_id=$zap->ccolor_id;
									$nuevp->tasa_impuesto=$zap->tasa_impuesto;
									$nuevp->ruta_foto=$zap->ruta_foto;
									$nuevp->numero_min=$limite_min;
									$nuevp->num_max=$limite_max;
									$nuevp->precio_compra=$zap->precio_compra;
									$nuevp->usuario_id=$zap->usuario_id;
									$nuevp->observaciones="";
									if($nuevp->save()){
										//Actualizar el id de la numeracion al nuevo producto
										for($y=0;$y<$ca+1;$y++){
											if($collect_array[$v][$y]['nid']>0){
												$this->db->query("update cproductos_numeracion set cproducto_id='$nuevp->id' where id={$collect_array[$v][$y]['nid']}");
												echo $r."-NUEVO- ".$nuevp->id ." $nuevp->descripcion&<br/>";
											}
										}
										$nuevp->clear();

									}
								}
							}
						}
						$strin='';
					}
					//						$c+=1;
					unset($collect_array);
				}
				$r+=1;
			}// Final bucle zapato
			unset($zapato);
		}
	}
	function baratas_numero_mm(){
		$cprod=$this->db->query("select * from cproductos_numeracion where numero_mm<90");
		foreach($cprod->result() as $row){
			$cprod=$this->db->query("update cproductos_numeracion  set numero_mm=".($row->numero_mm*10)." where id=$row->id;");
		}
		unset($cprod);
	}

	function estilos_solos(){
		$fid=$this->uri->segment(4);
		$cprod=$this->db->query("select * from cproductos where cfamilia_id='$fid' order by id");
		foreach($cprod->result() as $row){
			$cprod=$this->db->query("select count(id) as hijos from cproductos_numeracion where cproducto_id=$row->id;");
			$r=$cprod->row();
			$hijos=$r->hijos;
			$cprod=$this->db->query("update cproductos set num_hijos='$hijos' where id=$row->id;");
			if($hijos==0)
				echo $row->id."-$row->descripcion <br/>";
			unset($row); unset($cprod);
		}
		unset($cprod);
	}
	function buscar_hijos(){
		$fid=$this->uri->segment(4);
		$cprod=$this->db->query("select * from cproductos where num_hijos=0 order by id");
		foreach($cprod->result() as $row){
			echo $row->descripcion."_______<br/>";
			//Buscar Clave anterior en catalogo inicial
			$par=strpos($row->descripcion, " (");
			$descripcion=substr($row->descripcion,0, $par-1);
			$cat_init=$this->db->query("select * from catalogo_inicial where nombre like '%$descripcion%'");
			//echo "select * from catalogo_inicial where nombre like '%$descripcion%';{$cat_init->num_rows()} <br/>";
			if($cat_init->num_rows()>0){
				//echo $cat_init->num_rows();
				foreach($cat_init->result() as $cat){
					echo $cat->nombre ."-". $cat->codigo."<br/>";
				}
			} else {
				$row->descripcion ."-No encontrado en cat_inicial";
			}
			//Buscar con la clave_anterior en la numeracion si hay detalles que coincidan

			//Si existen esos detalles redirigirlos a cproductos id correctamente


		}
	}
	function actualizar_lotes(){
		$entradas=$this->db->query("select distinct(pr_facturas_id) as pr_factura_id, lote_id, fecha, espacios_fisicos_id from entradas where pr_facturas_id>0 group by pr_factura_id, lote_id, fecha, espacios_fisicos_id");
		$pre_fact=10000000000;
		foreach($entradas->result() as $row ){

			if($row->lote_id==0){
				echo "Nuevo Lote ";
				$l=new Lote();
				$l->fecha_recepcion=$row->fecha;
				$l->espacio_fisico_inicial_id=$row->espacios_fisicos_id;
				$l->save();
				$lote1=$l->id;
				$l->clear();

			} else {
				$lote1=$row->lote_id;
			}
			/*				$lote=new Lote_factura();
				$lote->where('pr_factura_id', $row->pr_factura_id)->where('cestatus_lote_id',0)->where('pr_pedido_id >',0)->limit(1)->get();
			$lote->pr_factura_id=$row->pr_factura_id;
			$lote->lote_id=$lote1;
			$lote->cestatus_lote_id=1;
			$lote->save();*/
			$this->db->query("update lotes_pr_facturas set lote_id='$lote1' where pr_factura_id='$row->pr_factura_id'");
			$this->db->query("update entradas set lote_id='$lote1' where pr_facturas_id='$row->pr_factura_id'");
			echo $row->pr_factura_id."- lote: ".$lote1."<br/>";
			$pre_fact=$row->pr_factura_id;

			//			$this->db->query("update lotes_pr_facturas set cestatus_lote_id='1' where pr_factura_id=$row->pr_facturas_id and cestatus_lote_id='0'");
		}
	}
	function clave_anterior_7(){
		$p=$this->db->query("select clave_anterior, id from cproductos_numeracion where clave_anterior!='0' and clave_anterior not like 'B%'");
		$cero[6]="0";
		$cero[5]="00";
		$cero[4]="000";
		$cero[3]="0000";
		$cero[2]="00000";
		$cero[1]="000000";
		foreach($p->result() as $row){
			if(strlen($row->clave_anterior)<7){
				$dif=strlen($row->clave_anterior);
				$str=$cero[$dif]."".$row->clave_anterior;
				echo "Id: $row->id - $row->clave_anterior  = $str <br/>";
				$this->db->query("update cproductos_numeracion set clave_anterior='$str' where id=$row->id");
				unset($row);
			}
		}
	}

	function actualizar_precios_traspasos(){
		//Obtener entradas y salidas de la tabla traspasos tiendas, filtrando las que tienen lote_id=0 y actualizar el precio_unitario, y precio_total
		$traspasos=$this->db->query("select tt.entrada_id, tt.salida_id from traspasos_tiendas as tt");
		foreach($traspasos->result() as $row){
			$e=new Salida();
			$e->get_by_id($row->salida_id);
			if($e->lote_id==0){
				$compra=$this->db->query("select c.precio1, m.porcentaje_utilidad from cproductos as c left join cmarcas_productos as m on m.id=c.cmarca_producto_id where c.id=$e->cproductos_id ");
				$c=$compra->row();
				$e->costo_unitario=ceil($c->precio1/((100+$c->porcentaje_utilidad)/100));
				$e->costo_total=$e->costo_unitario*$e->cantidad;
				$e->save();
				unset($compra);
			}
			if($row->entrada_id>0){
				$e=new Entrada();
				$e->get_by_id($row->entrada_id);
				if($e->lote_id==0){
					$compra=$this->db->query("select c.precio1, m.porcentaje_utilidad from cproductos as c left join cmarcas_productos as m on m.id=c.cmarca_producto_id where c.id=$e->cproductos_id ");
					$c=$compra->row();
					$e->costo_unitario=ceil($c->precio1/((100+$c->cporcentaje_utilidad)/100));
					$e->costo_total=$e->costo_unitario*$e->cantidad;
					$e->save();
					unset($compra);
				}
			}
			echo $row->salida_id."<br/>";
			unset($row);
		}
	}

	function act_marcas_facturas(){
		$facturas=$this->db->query("select f.id, p.cmarca_id from pr_facturas as f left join pr_pedidos as p on p.id=f.pr_pedido_id left join cmarcas_productos as m on m.id=f.cmarca_id where f.cmarca_id=0");
		foreach($facturas->result() as $row){
			$this->db->query("update pr_facturas set cmarca_id=$row->cmarca_id where id=$row->id");
		}
	}

	function salida_por_ajuste() {
		switch (strlen($_POST['codigo'])){
			case 13:
				$lote_id = (int)substr($_POST['codigo'], 0, 5);
				$num_id = (int)substr($_POST['codigo'], 5);
				$produ_id = $this->producto_numeracion->get_producto_id_by_id($num_id);
				$costo_u = $this->almacen->get_costo_promedio($produ_id);
				break;
			case 8:
			case 7:
				$lote_id = 0;
				$num_id = $this->producto_numeracion->get_by_clave_anterior(strtoupper($_POST['codigo']));
				$produ_id = $this->producto_numeracion->get_producto_id_by_id($num_id);
				$costo_u = $this->almacen->get_costo_promedio($produ_id);
				break;
			default :
				echo "<html> <script>alert(\"Código incorrecto, intente de nuevo.\"); window.location='" . base_url() . "index.php/" . $GLOBALS['ruta'] . "/almacen_c/formulario/alta_por_ajuste';</script></html>";
				return;
		}
		$salida = new Salida();
		$salida->espacios_fisicos_id = (int)$_POST['tienda_id'];
		$salida->cl_facturas_id = 0;
		$salida->cclientes_id = 0;
		$salida->cproductos_id = $produ_id;
		$salida->usuario_id = $GLOBALS['usuarioid'];
		$salida->cantidad = (int)$_POST['cantidad'];
		$salida->costo_unitario = $costo_u;
		$salida->costo_total = $costo_u * (int)$_POST['cantidad'];
		$salida->ctipo_salida_id = 3;
		$salida->cproducto_numero_id = $num_id;
		$salida->lote_id = $lote_id;
		if($salida->save()){
			echo "<html> <script>alert(\"El registro se ha dado de alta correctamente.\"); window.location='" . base_url() . "index.php/inicio/acceso/almacen/menu';</script></html>";
		}
	}

	function entrada_por_ajuste() {
		switch (strlen($_POST['codigo'])){
			case 13:
				$lote_id = (int)substr($_POST['codigo'], 0, 5);
				$num_id = (int)substr($_POST['codigo'], 5);
				$produ_id = $this->producto_numeracion->get_producto_id_by_id($num_id);
				$costo_u = $this->almacen->get_costo_promedio($produ_id);
				break;
			case 8:
			case 7:
				$lote_id = 0;
				$num_id = $this->producto_numeracion->get_by_clave_anterior(strtoupper($_POST['codigo']));
				$produ_id = $this->producto_numeracion->get_producto_id_by_id($num_id);
				$costo_u = $this->almacen->get_costo_promedio($produ_id);
				break;
			default :
				echo "<html> <script>alert(\"Código incorrecto, intente de nuevo.\"); window.location='" . base_url() . "index.php/" . $GLOBALS['ruta'] . "/almacen_c/formulario/entrada_por_ajuste';</script></html>";
				return;
		}
		$entrada = new Entrada();
		$entrada->espacios_fisicos_id = (int)$_POST['tienda_id'];
		$entrada->pr_facturas_id = 0;
		$entrada->cproveedores_id = $this->producto->get_productos_proveedor($produ_id);
		$entrada->cproductos_id = $produ_id;
		$entrada->usuario_id = $GLOBALS['usuarioid'];
		$entrada->cantidad = (int)$_POST['cantidad'];
		$entrada->costo_unitario = $costo_u;
		$entrada->costo_total = $costo_u * (int)$_POST['cantidad'];
		$entrada->ctipo_entrada = 5;
		$entrada->cproducto_numero_id = $num_id;
		$entrada->lote_id = $lote_id;
		if($entrada->save()){
			echo "<html> <script>alert(\"El registro se ha dado de alta correctamente.\"); window.location='" . base_url() . "index.php/inicio/acceso/almacen/menu';</script></html>";
		}
	}
	function salida_por_traspaso(){
		//Guardar factura de proveedor
		$e= new Salida();
		$e->usuario_id=$GLOBALS['usuarioid'];
		$e->empresas_id=$GLOBALS['empresaid'];
		$related = $e->from_array($_POST);
		//if($e->costo_unitario=='undefined') $e->costo_unitario=0;
		$e->espacios_fisicos_id=$GLOBALS['espacios_fisicos_id'];
		$e->costo_total=($e->cantidad * $e->costo_unitario);
		$e->cclientes_id=1;
		$e->fecha=date("Y-m-d H:i:s");
		if($e->id==0)
			unset($e->id);
		if($e->save($related)) {
			echo $e->id;
		} else
			echo 0;
	}
	function entrada_por_traspaso(){
		// Dar entrada por traspaso o por ajuste de inventario
		$e= new Entrada();
		$e->usuario_id=$GLOBALS['usuarioid'];
		$e->empresas_id=$GLOBALS['empresaid'];
		$related = $e->from_array($_POST);
		//if($e->costo_unitario=='undefined') $e->costo_unitario=0;
		$e->espacios_fisicos_id=$GLOBALS['espacios_fisicos_id'];
		$e->costo_total=($e->cantidad * $e->costo_unitario);
		$e->cproveedores_id=1;
		$e->fecha=date("Y-m-d H:i:s");
		if($e->id==0)
			unset($e->id);
		if($e->save($related)) {
			echo $e->id;
		} else
			echo 0;
	}
	
	function get_tipos_facturas (){
		$this->load->model("ctipo_factura");
		$tf = new Ctipo_factura();
		$tf->get();
		$x=0;		
		$j=array();
		foreach($tf->all as $row){
	  		$j[$x]="{'id': '$row->id','nombre':'$row->tag','impuesto':'$row->tasa_impuesto'}";
	  		$x+=1;
			
                }
                    $json= implode(", ", $j);
                    echo "[".$json."]";
	}
	
	function editar_semejanza() {
	        $pc=new cproducto_combo();
	        if($_POST['id']>0)
            $pc->get_by_id($_POST['id']);
            unset($_POST['id']);
	        $pc->semejante_producto_combo=$_POST['semejanza'];
	        if($pc->save()){
	            echo 1;}else {
	echo 0;            
	            }
	    }
	
	   function save_devolucion(){
        $this->load->model("devolucion_proveedor");
        $dp = new Devolucion_proveedor();
        $_POST['estatus_general_id'] = 1;
        $_POST['usuario_id'] = $GLOBALS['usuarioid'];
        $dp->save($dp->from_array($_POST));
        echo $dp->id;
    }


}
?>
