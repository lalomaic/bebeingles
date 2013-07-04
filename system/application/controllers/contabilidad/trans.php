<?php
class Trans extends Controller {
	function Trans() {
		parent::Controller();
		if($this->session->userdata('logged_in') == FALSE){
			redirect(base_url()."index.php/inicio/logout");
		}
		$this->load->model("diversos");
		$this->load->model("pr_detalle_pedido");
		$this->load->model("pr_pedido");
		$this->load->model("empresa");
		$this->load->model("espacio_fisico");
		$this->load->model("producto");
		$this->load->model("pago");
		$this->load->model("servicios");
		$this->load->model("cobro");
		$this->load->model("poliza");
		$this->load->model("cl_factura");
		$this->load->model("control_deposito");
		  $this->load->model("pr_factura");
		$this->load->model("cuenta_bancaria");
		$this->load->model("poliza_detalle");
		$user_hash=$this->session->userdata('user_data');
		$row=$this->usuario->get_usuario_detalles($user_hash);
		$GLOBALS['usuarioid']=$row->id;
		$GLOBALS['empresa_id']=$row->empresas_id;
		$GLOBALS['ubicacion_id']=$row->espacio_fisico_id;
		$GLOBALS['username']=$row->username;
		$GLOBALS['ruta']=$this->uri->segment(1);
		$GLOBALS['controller']=$this->uri->segment(2);
		$GLOBALS['funcion']=$this->uri->segment(3);
	}

	// Oscar Functions
	function alta_pr_forma_pago(){
		//Guardar la Forma de Cobro
		$u= new Forma_pago();
		$related = $u->from_array($_POST);
		// save with the related objects
		if($u->save($related)) {
			echo "<html> <script>alert(\"Se han registrado los datos de la Forma de Pago.\"); window.location='".base_url()."index.php/inicio/acceso/".$GLOBALS['ruta']."/menu';</script></html>";
		} else  {
			show_error("".$u->error->string);
		}
	}

	function alta_cuenta_contable() {
        $this->load->model('cuenta_contable');
        if ($_POST['tag'] == '') {
            echo "<html> <script>alert(\"Debe seleccionar un nombre para la cuenta contable.\"); window.location='" . base_url() . "index.php/" . $GLOBALS['ruta'] . "/poliza_c/formulario/alta_cuenta_contable/';</script></html>";
            return;
        }
        $_POST['tag'] = strtoupper($_POST['tag']);
        //Guardar la Forma de Cobro
        $u = new Cuenta_contable();
        $u->from_array($_POST);
        $u->usuario_id = $GLOBALS['usuarioid'];
        $u->empresa_id = $GLOBALS['empresa_id'];
        $u->estatus_general_id = 1;
        $conts = new Cuenta_contable();
        if (isset($_POST['sscta']) && $_POST['sscta'] != '') {
            //<editor-fold>
            $conts->
                    where('cta', $_POST['cta'])->
                    where('scta', $_POST['scta'])->
                    where('sscta', $_POST['sscta'])->
                    where('ssscta', 0)->
                    where('empresa_id', $GLOBALS['empresa_id'])->
                    limit(1)->get();
            if ($conts->exists()) {
                $conts->
                        select('ssscta')->
                        where('cta', $_POST['cta'])->
                        where('scta', $_POST['scta'])->
                        where('sscta', $_POST['sscta'])->
                        where('empresa_id', $GLOBALS['empresa_id'])->
                        order_by('ssscta')->get();
                $index = 0;
                foreach ($conts as $cont) {
                    if ($cont->ssscta != $index)
                        break;
                    $index++;
                }
                $cuenta = $this->cuenta_contable->
                                where('tag', $_POST['tag'])->
                                where('cta', $_POST['cta'])->
                                where('scta', $_POST['scta'])->
                                where('sscta', $_POST['sscta'])->
                                where('ssscta >', 0)->
                                where('empresa_id', $GLOBALS['empresa_id'])->limit(1)->get();
                if (sizeof($cuenta->all) > 0) {
                    echo "<html> <script>alert(\"Ya existe la cuenta contable $cuenta->id con el nombre de {$_POST['tag']}.\"); window.location='" . base_url() . "index.php/" . $GLOBALS['ruta'] . "/poliza_c/formulario/alta_cuenta_contable/';</script></html>";
                    return;
                }
                $texto = "con el id de sub-sub-sub cuenta automático $index";
                $u->ssscta = $index;
            } else {
                $cuenta = $this->cuenta_contable->
                                where('tag', $_POST['tag'])->
                                where('cta', $_POST['cta'])->
                                where('scta', $_POST['scta'])->
                                where('sscta >', 0)->
                                where('ssscta', 0)->
                                where('empresa_id', $GLOBALS['empresa_id'])->limit(1)->get();
                if (sizeof($cuenta->all) > 0) {
                    echo "<html> <script>alert(\"Ya existe la cuenta contable $cuenta->id con el nombre de {$_POST['tag']}.\"); window.location='" . base_url() . "index.php/" . $GLOBALS['ruta'] . "/poliza_c/formulario/alta_cuenta_contable/';</script></html>";
                    return;
                }
                $u->sscta = $_POST['sscta'];
                $texto = "con el id de sub-sub cuenta manual $u->sscta";
            }
            //</editor-fold>
        } else if (isset($_POST['scta']) && $_POST['scta'] != '') {
            //<editor-fold>
            $conts->
                    where('cta', $_POST['cta'])->
                    where('scta', $_POST['scta'])->
                    where('sscta', 0)->
                    where('ssscta', 0)->
                    where('empresa_id', $GLOBALS['empresa_id'])->
                    limit(1)->get();
            if ($conts->exists()) {
                $conts->
                        select('sscta')->
                        where('cta', $_POST['cta'])->
                        where('scta', $_POST['scta'])->
                        where('ssscta', 0)->
                        where('empresa_id', $GLOBALS['empresa_id'])->
                        order_by('sscta')->get();
                $index = 0;
                foreach ($conts as $cont) {
                    if ($cont->sscta != $index)
                        break;
                    $index++;
                }
                $cuenta = $this->cuenta_contable->
                                where('tag', $_POST['tag'])->
                                where('cta', $_POST['cta'])->
                                where('scta', $_POST['scta'])->
                                where('sscta >', 0)->
                                where('ssscta', 0)->
                                where('empresa_id', $GLOBALS['empresa_id'])->limit(1)->get();
                if (sizeof($cuenta->all) > 0) {
                    echo "<html> <script>alert(\"Ya existe la cuenta contable $cuenta->id con el nombre de {$_POST['tag']}.\"); window.location='" . base_url() . "index.php/" . $GLOBALS['ruta'] . "/poliza_c/formulario/alta_cuenta_contable/';</script></html>";
                    return;
                }
                $texto = "con el id de sub-sub cuenta automático $index";
                $u->sscta = $index;
            } else {
                $cuenta = $this->cuenta_contable->
                                where('tag', $_POST['tag'])->
                                where('cta', $_POST['cta'])->
                                where('scta >', 0)->
                                where('sscta', 0)->
                                where('ssscta', 0)->
                                where('empresa_id', $GLOBALS['empresa_id'])->limit(1)->get();
                if (sizeof($cuenta->all) > 0) {
                    echo "<html> <script>alert(\"Ya existe la cuenta contable $cuenta->id con el nombre de {$_POST['tag']}.\"); window.location='" . base_url() . "index.php/" . $GLOBALS['ruta'] . "/poliza_c/formulario/alta_cuenta_contable/';</script></html>";
                    return;
                }
                $u->scta = $_POST['scta'];
                $texto = "con el id de sub cuenta manual $u->scta";
            }
            //</editor-fold>
        } else if (isset($_POST['cta']) && $_POST['cta'] != '') {
            //<editor-fold>
            $conts->
                    where('cta', $_POST['cta'])->
                    where('scta', 0)->
                    where('sscta', 0)->
                    where('ssscta', 0)->
                    where('empresa_id', $GLOBALS['empresa_id'])->
                    limit(1)->get();
            if ($conts->exists()) {
                $conts->
                        select('scta')->
                        where('cta', $_POST['cta'])->
                        where('sscta', 0)->
                        where('ssscta', 0)->
                        where('empresa_id', $GLOBALS['empresa_id'])->
                        order_by('scta')->get();
                $index = 0;
                foreach ($conts as $cont) {
                    if ($cont->scta != $index)
                        break;
                    $index++;
                }
                $cuenta = $this->cuenta_contable->
                                where('tag', $_POST['tag'])->
                                where('cta', $_POST['cta'])->
                                where('scta >', 0)->
                                where('sscta', 0)->
                                where('ssscta', 0)->
                                where('empresa_id', $GLOBALS['empresa_id'])->limit(1)->get();
                if (sizeof($cuenta->all) > 0) {
                    echo "<html> <script>alert(\"Ya existe la cuenta contable $cuenta->id con el nombre de {$_POST['tag']}.\"); window.location='" . base_url() . "index.php/" . $GLOBALS['ruta'] . "/poliza_c/formulario/alta_cuenta_contable/';</script></html>";
                    return;
                }
                $texto = "con el id de sub cuenta automático $index";
                $u->scta = $index;
            } else {
                $cuenta = $this->cuenta_contable->
                                where('tag', $_POST['tag'])->
                                where('cta >', 0)->
                                where('scta', 0)->
                                where('sscta', 0)->
                                where('ssscta', 0)->
                                where('empresa_id', $GLOBALS['empresa_id'])->limit(1)->get();
                if (sizeof($cuenta->all) > 0) {
                    echo "<html> <script>alert(\"Ya existe la cuenta contable $cuenta->id con el nombre de {$_POST['tag']}.\"); window.location='" . base_url() . "index.php/" . $GLOBALS['ruta'] . "/poliza_c/formulario/alta_cuenta_contable/';</script></html>";
                    return;
                }
                $u->cta = $_POST['cta'];
                $texto = "con el id de cuenta manual $u->cta";
            }
            //</editor-fold>
        } else {
            //<editor-fold>
            $conts->
                    select('cta')->
                    where('scta', 0)->
                    where('sscta', 0)->
                    where('ssscta', 0)->
                    where('empresa_id', $GLOBALS['empresa_id'])->
                    order_by('cta')->get();
            $index = 1;
            foreach ($conts as $cont) {
                if ($cont->cta != $index)
                    break;
                $index++;
            }
            $cuenta = $this->cuenta_contable->
                            where('tag', $_POST['tag'])->
                            where('cta >', 0)->
                            where('scta', 0)->
                            where('sscta', 0)->
                            where('ssscta', 0)->
                            where('empresa_id', $GLOBALS['empresa_id'])->limit(1)->get();
            if (sizeof($cuenta->all) > 0) {
                echo "<html> <script>alert(\"Ya existe la cuenta contable $cuenta->id con el nombre de {$_POST['tag']}.\"); window.location='" . base_url() . "index.php/" . $GLOBALS['ruta'] . "/poliza_c/formulario/alta_cuenta_contable/';</script></html>";
                return;
            }
            $texto = "con el id de cuenta automático $index";
            $u->cta = $index;
            //</editor-fold>
        }
        // save with the related objects
        if ($u->save())
            echo "<html> <script>alert(\"Se han registrado la cuenta contable $texto.\"); window.location='" . base_url() . "index.php/inicio/acceso/" . $GLOBALS['ruta'] . "/menu';</script></html>";
        else
            echo "<html> <script>alert(\"Ha ocurrido un problema con registro de la cuenta contable, favor de intentar de nuevo.\"); window.location='" . base_url() . "index.php/" . $GLOBALS['ruta'] . "/poliza_c/formulario/alta_cuenta_contable/';</script></html>";
    }
	function act_pr_forma_pago(){
		//Guardar la Forma de Cobro
		$u= new Forma_pago();
		$related = $u->from_array($_POST);
		// save with the related objects
		if($u->save($related)) {
			echo "<html> <script>alert(\"Se han actualizado los datos de la Forma de Pago.\"); window.location='".base_url()."index.php/inicio/acceso/".$GLOBALS['ruta']."/menu';</script></html>";
		} else  {
			show_error("".$u->error->string);
		}
	}

function act_comisiones_bancarias(){
		//Guardar la Forma de Cobro
		$u= new Cuenta_comision();
		$related = $u->from_array($_POST);
                // save with the related objects
		if($u->save($related))
		{
			echo "<html> <script>alert(\"Se han registrado los datos del la comision.\"); window.location='".base_url()."index.php/".$GLOBALS['ruta']."/contabilidad_c/formulario/alta_comision_bancaria';</script></html>";
		}
		else
		{
			show_error("".$u->error->string);
		}
	}


function alta_servicio(){
		//Guardar la Forma de Cobro
		$u= new servicios();
		$u->estatus_general_id=1;
		$u->usuario_id=$GLOBALS['usuarioid'];
		$u->fecha_cambio=date("Y-m-d");
		$u->hora_cambio=date("H:i:s");
		$related = $u->from_array($_POST);
		// save with the related objects
		if($u->save($related)) {
			$this->db->query("update control_actualizaciones set fecha_cambio='". date("Y-m-d") . "', hora='" . date("H:i:s") . ".00' where id='17'");			
			echo "<html> <script>alert(\"Se han actualizado los datos para el Servicio.\"); window.location='".base_url()."index.php/inicio/acceso/".$GLOBALS['ruta']."/menu';</script></html>";
		} else  {
			show_error("".$u->error->string);
		}
	}



	function alta_tipo_pago(){
		//Guardar la Forma de Cobro
		$u= new Tipo_pago();
		$related = $u->from_array($_POST);
		// save with the related objects
		if($u->save($related))
		{
			echo "<html> <script>alert(\"Se han registrado los datos del Tipo de Pago.\"); window.location='".base_url()."index.php/inicio/acceso/".$GLOBALS['ruta']."/menu';</script></html>";
		}
		else
		{
			show_error("".$u->error->string);
		}
	}

	function act_tipo_pago(){
		//Guardar la Forma de Cobro
		$u= new Tipo_pago();
		$related = $u->from_array($_POST);
		// save with the related objects
		if($u->save($related))
		{
			echo "<html> <script>alert(\"Se han actualizado los datos del Tipo de Pago.\"); window.location='".base_url()."index.php/inicio/acceso/".$GLOBALS['ruta']."/menu';</script></html>";
		}
		else
		{
			show_error("".$u->error->string);
		}
	}

	function act_pago(){
		//Guardar Pago
		$monto_total=$this->pr_factura->get_monto_factura_id($_POST['pr_factura_id']);
         $abono=$_POST['monto_pagado'];
         $liquidado=$_POST['ctipo_pago_id'];
		
				$total_abonos=$this->pago->get_pagos_total_factura_id($_POST['pr_factura_id']);
				$suma_abonos=round($total_abonos->total,2);
                                $monto_factura=  round($monto_total->monto_total,2);
                                $abono=  round($abono,2);
				$diff=$monto_factura-$suma_abonos;
				if($abono>$diff){
                                echo "<html> <script>alert(\"El Abono es Mayor a la deuda reducir\"); window.history.back(); </script></html>";    
                                }
                                if($liquidado==2 and $abono<$diff){
                echo "<html> <script>alert(\"No se puede liquidar la deuda la cantidad es Menor al Adeudo\"); window.history.back(); </script></html>";
																				                                	
                                	}else {
                               
	     
		$u= new Pago();
		$u->usuario_id=$GLOBALS['usuarioid'];
                $u->fecha_captura=date("Y-m-d H:i:s");
		$fecha=explode(" ", $_POST['fecha']);
		$u->fecha=$fecha[2]."/".$fecha[1]."/".$fecha[0];
		unset($_POST['fecha']);
		$related = $u->from_array($_POST);
		if(isset($_POST['id']))
			$nueva_poliza=0;
		else
			$nueva_poliza=1;

		if($u->save($related)) {
			$id=$u->pr_factura_id;
			$total=$this->pago->get_pagos_total_factura_id($id);
			$f=new Pr_factura();
			$f->get_by_id($id);
			$fac_total=$f->monto_total;
			if($fac_total==$total->total or $fac_total<$total->total)
				$f->estatus_factura_id=3;
			else
				$f->estatus_factura_id=2;
			if($f->save()){
				//Generar Poliza de egresos
				$p=new Poliza();
				if($nueva_poliza==0)
					$p->get_by_id($u->poliza_id);
				$p->ctipo_poliza_id=2; //Ctipo poliza de egresos=2
				$p->empresa_id=$GLOBALS['empresa_id'];
				$p->espacio_fisico_id=$GLOBALS['ubicacion_id'];
				$p->concepto="Pago a Proveedor";
				if($u->cpr_forma_pago_id==1)
					$p->ctipo_pago_poliza=3;
				if($u->cpr_forma_pago_id==2)
					$p->ctipo_pago_poliza=2;
				if($u->cpr_forma_pago_id==3)
					$p->ctipo_pago_poliza=1;
				if($u->cpr_forma_pago_id==4)
					$p->ctipo_pago_poliza=2;
				else
					$p->ctipo_pago_poliza=2;
				$p->fecha=$u->fecha;
				$p->fecha_captura=date("Y-m-d H:i:s");
				$p->usuario_id=$u->usuario_id;
				$p->estatus_general_id=1;
				$p->debe=$u->monto_pagado;
				$p->haber=$u->monto_pagado;
				$p->save();

				//Generar el detalle de la poliza cuenta proveedor
				$this->db->query("delete from poliza_detalles where poliza_id='$p->id'");
				$np= new Poliza_detalle();
				$np->poliza_id=$p->id;
				$np->cuenta_contable_id=296;
				$np->haber=$u->monto_pagado;
				$np->debe=0;
				$np->fecha_captura=date("Y-m-d H:i:s");
				$np->usuario_id=$u->usuario_id;
				$np->save();


				echo "<html> <script>alert(\"Se han actualizado los datos del Pago\"); window.location='".base_url()."index.php/".$GLOBALS['ruta']."/pagos_c/formulario/list_pagos';</script></html>";
			
                }
		} else
			show_error("".$u->error->string);
		}
                                
                                }
	function act_cuenta_bancaria(){
		///Guardar la Forma de Cobro
		$u= new Cuenta_bancaria();
		 if($_POST['id']>0)
            $u->get_by_id($_POST['id']);
            unset($_POST['id']);
	
		$u->usuario_id=$GLOBALS['usuarioid'];
		$u->fecha_captura=date("Y-m-d");
                $u->banco=$_POST['banco'];
                $u->numero_sucursal=$_POST['numero_sucursal'];
	        $u->nombre_sucursal=$_POST['nombre_sucursal'];
                $u->numero_cuenta=$_POST['numero_cuenta'];
                $u->clabe=$_POST['clabe'];
                $u->ctipo_cuenta_id=$_POST['ctipo_cuenta_id'];
                if(isset($_POST['terminal'])){
                	$u->terminal="t";
                	}else{
                		$u->terminal="f";
                		}
                //$related = $u->from_array($_POST);
		// save with the related objects
                if($_POST['cproveedor_id']==''){
                    $u->cproveedor_id=0;
                }  else {
                   $u->cproveedor_id=$_POST['cproveedor_id']; 
                }if($_POST['cliente']==0){
                    $u->ccliente_id=0;
                }else{
                   $u->ccliente_id=$_POST['cliente'];  
                }if($_POST['empresa']==0){
                 $u->empresa_id=0;   
                }else{
                $u->empresa_id=$_POST['empresa'];    
                }
		if($u->save()){
                    echo "<html> <script>alert(\"Se han actualizado los datos de la Cuenta Bancaria.\"); window.location='".base_url()."index.php/".$GLOBALS['ruta']."/contabilidad_c/formulario/list_cuentas_bancarias';</script></html>";}
		else
                    show_error("".$u->error->string);
	}
	function alta_cl_forma_cobro(){
		//Guardar la Forma de Cobro
		$u= new Forma_cobro();
		$related = $u->from_array($_POST);
		// save with the related objects
		if($u->save($related))
		{
			echo "<html> <script>alert(\"Se han registrado los datos de la Forma de Cobro.\"); window.location='".base_url()."index.php/inicio/acceso/".$GLOBALS['ruta']."/menu';</script></html>";
		}
		else
		{
			show_error("".$u->error->string);
		}
	}

   function alta_bancos() {
        //Guardar la Marca de Productos
        $u = new Bancos();
        $related = $u->from_array($_POST);
        // save with the related objects
        if ($u->save($related)) {
            echo "<html> <script>alert(\"Se han Registrador el Banco.\"); window.location='" . base_url() . "index.php/inicio/acceso/" . $GLOBALS['ruta'] . "/menu';</script></html>";
        } else
            show_error("" . $u->error->string);
    }

	function act_cl_forma_cobro(){
		//Actualizar la Forma de Cobro
		$u= new Forma_cobro();
		$related = $u->from_array($_POST);
		// save with the related objects
		if($u->save($related))
		{
			echo "<html> <script>alert(\"Se han actualizado los datos de la Forma de Cobro.\"); window.location='".base_url()."index.php/inicio/acceso/".$GLOBALS['ruta']."/menu';</script></html>";
		}
		else
		{
			show_error("".$u->error->string);
		}
	}

	function alta_tipo_cobro(){
		//Guardar la Forma de Cobro
		$u= new Tipo_cobro();
		$related = $u->from_array($_POST);
		// save with the related objects
		if($u->save($related)) {
			echo "<html> <script>alert(\"Se han registrado los datos del Tipo de Cobro.\"); window.location='".base_url()."index.php/inicio/acceso/".$GLOBALS['ruta']."/menu';</script></html>";
		}
		else
		{
			show_error("".$u->error->string);
		}
	}

	function act_tipo_cobro(){
		//Actualizar la Forma de Cobro
		$u= new Tipo_cobro();
		$related = $u->from_array($_POST);
		// save with the related objects
		if($u->save($related))
		{
			echo "<html> <script>alert(\"Se han actualizado los datos del Tipo de Cobro.\"); window.location='".base_url()."index.php/inicio/acceso/".$GLOBALS['ruta']."/menu';</script></html>";
		}
		else
		{
			show_error("".$u->error->string);
		}
	}

	function alta_tipo_cuenta_bancaria(){
		//Guardar la Forma de Cobro
		$u= new Tipo_cuenta_bancaria();
		$related = $u->from_array($_POST);
		// save with the related objects
		if($u->save($related))
		{
			echo "<html> <script>alert(\"Se han registrado los datos del Tipo de Cuenta Bancaria.\"); window.location='".base_url()."index.php/inicio/acceso/".$GLOBALS['ruta']."/menu';</script></html>";
		}
		else
		{
			show_error("".$u->error->string);
		}
	}

	function act_tipo_cuenta_bancaria(){
		//Guardar la Forma de Cobro
		$u= new Tipo_cuenta_bancaria();
		$related = $u->from_array($_POST);
		// save with the related objects
		if($u->save($related)) {
			echo "<html> <script>alert(\"Se han actualizado los datos del Tipo de Cuenta Bancaria.\"); window.location='".base_url()."index.php/inicio/acceso/".$GLOBALS['ruta']."/menu';</script></html>";
		}  else
			show_error("".$u->error->string);
	}

	function alta_cobro() {
		$u= new Cobro();
		//Guardar la Forma de PCobro
		$u->usuario_id=$GLOBALS['usuarioid'];
		$u->fecha_captura=date("Y-m-d H:i:s");
		$fecha=explode(" ", $_POST['fecha']);
		$u->fecha=$fecha[2]."-".$fecha[1]."-".$fecha[0];
		unset($_POST['fecha']);
		$related = $u->from_array($_POST);
		if($u->save($related)){
			$id=$u->cl_factura_id;
			$total=$this->cobro->get_cobro_total_factura_id($id);
			$f=new Cl_factura();
			$f->get_by_id($id);
			$fac_total=$f->monto_total;
			$iva_total=$f->iva_total;
			if($fac_total==$total->total)
				$f->estatus_factura_id=3;
			else
				$f->estatus_factura_id=2;
			if($f->save()){
				echo "<html> <script>alert(\"Se han actualizado los datos del Cobro y se ha generado la Poliza de Ingresos del mismo con Id Poliza: $p->id.\"); window.location='".base_url()."index.php/".$GLOBALS['ruta']."/cobros_c/formulario/list_cobros';</script></html>";
			}
		} else
			show_error("".$u->error->string);
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
		$u->usuario_id=$GLOBALS['usuarioid'];
		$u->fecha_captura=date("Y-m-d H:i:s");
		$related = $u->from_array($deposito);
		if($u->save()){
			echo "<html> <script>alert(\"Se ha registrado el Deposito en la cuenta bancaria correctamente.\"); window.location='".base_url()."index.php/".$GLOBALS['ruta']."/contabilidad_c/formulario/list_control_depositos_general/';</script></html>";
		} else {
			echo "<html> <script>alert(\"Ha ocurrido un problema con registro del deposito, favor de intentar de nuevo.\"); window.location='".base_url()."index.php/".$GLOBALS['ruta']."/contabilidad_c/formulario/alta_control_deposito/';</script></html>";
		}
	}



	function verificar_pagos(){
		//Obtener las facturas a cr\00dito estatus_factura_id=2
		$this->load->model("pr_factura");
		$facturas_credito=$this->pr_factura->get_pr_facturas_xpagar_verificacion();
		if($facturas_credito!=false){
			foreach($facturas_credito->all as $row){
				$total_fac=$this->pago->get_pagos_total_factura_id($row->id);
				$total_fac->total=round($total_fac->total, 1);
				$diff=abs($total_fac->total)-abs($row->monto_total);
				if($total_fac->total >= $row->monto_total or $diff > '0.1'){
					$this->db->query("update pr_facturas set estatus_factura_id=3 where id=$row->id");
					echo "$row->id%$total_fac<br/>";
				}
			}
		}

	}

	function act_multiple_pago(){
		//Guardar Pago
		// 		print_r($_POST);
		$datos=$_POST;
		$line=$this->uri->segment(4);
		$u= new Pago();
		$u->usuario_id=$GLOBALS['usuarioid'];
		if(isset($_POST['fecha'.$line]) and strpos($_POST['fecha'.$line],' ')!=false) {
			$fecha=explode(" ", $_POST['fecha'.$line]);
			$u->fecha=$fecha[2]."/".$fecha[1]."/".$fecha[0];
			unset($_POST['fecha'+$line]);
		} else {
			$u->fecha=date("Y-m-d H:i:s");
		}
		$u->cproveedor_id=$datos['cproveedor_id'.$line];
		$u->cuenta_destino_id=$_POST['cuenta_destino_id'.$line];
		$u->cuenta_origen_id=$_POST['cuenta_origen_id'.$line];
		$u->cpr_forma_pago_id=$_POST['cpr_forma_pago_id'.$line];
		$u->monto_pagado=$_POST['monto_pagado'.$line];
		$u->numero_referencia=$_POST['no_autorizacion'.$line];
		$u->pr_factura_id=$_POST['pr_factura_id'.$line];
		//$u->fecha=date("Y-m-d H:i:s");
	$monto_total=$this->pr_factura->get_monto_factura_id($u->pr_factura_id);
         $abono=$u->monto_pagado;
		
				$total_abonos=$this->pago->get_pagos_total_factura_id($u->pr_factura_id);
				$suma_abonos=round($total_abonos->total,2);
                                $monto_factura=  round($monto_total->monto_total,2);
                                //$abono=  round($abono,2);
				$diff=$monto_factura-$suma_abonos;
                              if($abono>$diff){
        echo "<html> <script>alert(\"El Abono es Mayor a la deuda reducir\");  </script></html>";   }
        else{
               
		if($_POST['id'.$line]>0)
			$u->id=$_POST['id'.$line];
		unset($_POST);

		if($u->save()) {
			//Actualizar el Estatus de Pago de la Factura si ya esta siendo liquidada o solamente es un abono
			$id=$u->pr_factura_id;
			$total=$this->pago->get_pagos_total_factura_id($id);
			$f=new Pr_factura();
			$f->get_by_id($id);
			$fac_total=$f->monto_total;
			if($fac_total==$total->total or $fac_total<$total->total){
				$f->estatus_factura_id=3;
				$u->ctipo_pago_id=2;
			} else{
				$f->estatus_factura_id=2;
				$u->ctipo_pago_id=1;
			}
			if($f->save()){
				$u->save();
				//Identificar si el pago ya esta en una poliza y directamente actualizar
				echo "<img src=\"".base_url()."images/ok.png\" width=\"20px\" title=\"Guardado\"/><input type='hidden' id='id$line' name='id$line' value='$u->id'>";
			}
		} else
			show_error("".$u->error->string);
	}
}

	function act_gasto_tienda(){
		$datos=$_POST;
		$ajax=1;
		if($this->uri->segment(4)!=1)
			$ajax=0;
		$u= new Gasto_detalle();
		$related = $u->from_array($_POST);
		$u->usuario_id=$GLOBALS['usuarioid'];
		$fecha=explode(" ", $_POST['fecha']);
		$u->fecha=$fecha[2]."/".$fecha[1]."/".$fecha[0];
		// save with the related objects
		if($u->save($related)) {
			//Correccion para responder via ajax
			if($ajax==1)
				echo "<img src='".base_url()."images/bien.png' width='15px' align='left' title='Gasto Registrado exitosamente'>";
			else
				echo "<html> <script>alert(\"Se han registrado los datos del Gasto.\"); window.location='".base_url()."index.php/contabilidad/contabilidad_c/formulario/list_gastos_tiendas';</script></html>";
		} else
			echo "<img src='".base_url()."images/cancelado.png' width='15' align='left' title='Error de registro'>";
		// 			show_error("".$u->error->string);
}

	


	function act_egresos_tienda(){
		$this->load->model("egresos_detalles");
		$datos=$_POST;
		$u= new Egresos_detalles();
		$related = $u->from_array($_POST);
		$u->usuario_id=$GLOBALS['usuarioid'];
		$fecha=explode(" ", $_POST['fecha']);
		$u->fecha=$fecha[2]."/".$fecha[1]."/".$fecha[0];
		// save with the related objects
		if($u->save($related)) {
			echo "<html> <script>alert(\"Se han registrado los datos del Egreso.\"); window.location='".base_url()."index.php/contabilidad/contabilidad_c/formulario/list_egresos_tienda';</script></html>";
		} else {
			show_error("".$u->error->string);
		}
	}


	function alta_gasto(){
		//Guardar la Marca de Productos
		$u= new Cgastos();
		$_POST['tag']=strtoupper($_POST['tag']);
		$related = $u->from_array($_POST);
		$u->estatus_general_id=1;
		// save with the related objects
		if($u->save($related)) {
			echo "<html> <script>alert(\"Se ha registrado el Concepto de Gasto '$u->tag'.\"); window.location='".base_url()."index.php/contabilidad/contabilidad_c/formulario/list_gastos';</script></html>";
		} else
			show_error("".$u->error->string);
	}

	function alta_otros_egresos(){
		//Guardar la Marca de Productos
		$this->load->model("otros_egresos");
		$u= new Otros_egresos();
		$_POST['tag']=strtoupper($_POST['tag']);
		$related = $u->from_array($_POST);
		$u->estatus_general_id=1;
		// save with the related objects
		if($u->save($related)) {
			echo "<html> <script>alert(\"Se ha registrado el Concepto de Otro Egreso '$u->tag'.\"); window.location='".base_url()."index.php/contabilidad/contabilidad_c/formulario/list_otros_egresos';</script></html>";
		} else
			show_error("".$u->error->string);
	}

	function alta_pago_entre_tiendas(){
		$this->load->model("traspaso_tienda");
		//Funcion para registrar pagos entre tiendas
		$recibe_id=$this->input->post('recibe_id');
		$envia_id=$this->input->post('envia_id');
		$importe_abono=$this->input->post('importe');
		$fecha=$this->input->post('fecha_pago');
		$concepto=$this->input->post('concepto');
		//Obtener el importe por traspaso entre tiendas no pagadas por mes, ordenandolas por la fecha mas antigua
		$entradas=$this->traspaso_tienda->get_listado_deuda_tiendas($envia_id, $recibe_id, $fecha);

		if($entradas!=false){
			$bucle_pago=0;
			$matriz_eid=array();
			foreach($entradas->all as $row){
				if($bucle_pago<=$importe_abono and $row->costo_total<=($importe_abono-$bucle_pago)){
					$matriz_eid[]=$row->id;
					$bucle_pago+=$row->costo_total;
					//echo $row->costo_total."<br/>";
				} else
					break;
			}
			if($bucle_pago>0){
				//Dar de alta el pago de ese periodo
				$this->load->model("deuda_tienda");
				$fecha_m=explode("-", $fecha);
				$dt=new Pago_deuda_tienda();
				$dt->where('tienda_envia_id', $envia_id)->where('tienda_recibe_id', $recibe_id)->where('fecha_abono', $fecha)->where('estatus_general_id',1)->where('ctipo_deuda_tienda_id', $concepto)->get();
				$dt->tienda_envia_id=$envia_id;
				$dt->tienda_recibe_id=$recibe_id;
				$dt->fecha_abono=$fecha;
				$dt->estatus_general_id=1;
				//				$dt->ctipo_deuda_tienda_id=$concepto;
				$dt->importe_abono=$bucle_pago;
				$dt->dia=$fecha_m[2];
				$dt->mes=$fecha_m[1];
				$dt->anio=$fecha_m[0];
				if($dt->save()){
					$eid=implode(", ", $matriz_eid);
					$this->db->query("update entradas set deuda_tiendas=1 where id in ($eid)");
					echo "<h3>Abono Registrado: $bucle_pago</h3>";
				}

			}
		}
		//actualizar el campo deuda_tiendas =1 en la tabla entradas de ese periodo equivalente a ese monto
	}


function editar_cuenta_contable() {
        //Guardar la Forma de Cobro
        $u = new Cuenta_contable($_POST['id']);
        if (!$u->exists()) {
            echo "<html> <script>alert(\"No existe la cuenta contable.\"); window.location='" . base_url() . "index.php/contabilidad/poliza_c/formulario/list_cuentas_contables';</script></html>";
            return;
        }
        $_POST['tag'] = strtoupper($_POST['tag']);
        if ($u->tag != $_POST['tag']) {
            $this->load->model('cuenta_contable');
            $cuenta = $this->cuenta_contable->where('tag', $_POST['tag'])->where('empresa_id', $GLOBALS['empresa_id'])->limit(1)->get();
            if (sizeof($cuenta->all) > 0) {
                echo "<html> <script>alert(\"Ya existe la cuenta contable $cuenta->id con el nombre de {$_POST['tag']}.\"); window.location='" . base_url() . "index.php/" . $GLOBALS['ruta'] . "/poliza_c/formulario/list_cuentas_contables/editar_cuenta_contable/{$_POST['id']}';</script></html>";
                return;
            }
        }
        $related = $u->from_array($_POST);
        $u->usuario_id = $GLOBALS['usuarioid'];
        $u->empresa_id = $GLOBALS['empresa_id'];
        $u->estatus_general_id = 1;
        // save with the related objects
        if (isset($_POST['cascada'])) {
            $this->db->query("UPDATE ccuentas_contables SET ctipo_cuenta_contable_id={$_POST['ctipo_cuenta_contable_id']} WHERE cta=$u->cta;");
        }
        if ($u->save($related)) {
            echo "<html> <script>alert(\"Se editado la cuenta contable exitosamente.\"); window.location='" . base_url() . "index.php/contabilidad/poliza_c/formulario/list_cuentas_contables';</script></html>";
        } else {
            echo "<html> <script>alert(\"Ha ocurrido un problema con registro de la cuenta contable, favor de intentar de nuevo.\"); window.location='" . base_url() . "index.php/contabilidad/poliza_c/formulario/list_cuentas_contables';</script></html>";
        }
    }

function alta_manual_poliza_diario() {       

        $p = new Poliza();
        if($_POST['id']>0){
            $p->get_by_id($_POST['id']);        
        }
        
        unset($_POST['id']);
        $fecha = explode(" ", $_POST['fecha']);
      
        if ($p->exists() && ($p->anio != substr($fecha[2], 2, 2) || $p->mes != $fecha[1])) {
            $p->concepto = "Cancelada por cambio de mes, nueva poliza " . substr($fecha[2], 2, 2) . $fecha[1] . sprintf("%04d", $_POST['consecutivo']);
            $p->estatus_general_id = 2;
            $this->db->query("DELETE FROM poliza_detalles WHERE poliza_id = $p->id");
            $p->save();
            $p->id = null;
            unset($_POST['id']);
        }
        $p->from_array($_POST);
        $p->ctipo_poliza_id = 3;
        $p->empresa_id = $GLOBALS['empresa_id'];
        $p->espacio_fisico_id = $GLOBALS['empresa_id'];
        $p->concepto = utf8_decode($_POST['concepto']);
        $p->fecha = "{$fecha[2]}-{$fecha[1]}-{$fecha[0]}";
        $p->fecha_captura = date("Y-m-d H:i:s");
        $p->usuario_id = $GLOBALS['usuarioid'];
        $p->anio = substr($fecha[2], 2, 2);
        $p->mes = $fecha[1];
        if ($p->automatico != 1) {
            $p->automatico = 0;
            $p->ctipo_pago_poliza = 1;
        }
        $p->consecutivo = sprintf("%04d", $p->consecutivo);
        $p->folio_poliza = "$p->anio$p->mes$p->consecutivo";
        if ($p->save()){
            echo "{id:$p->id}";
        } else
            echo utf8_encode("{id:0, error:'Ocurrio un error al salvar la poliza, por favor intenta de nuevo'}");
    }
        
        
         function alta_manual_poliza_ingreso() {
      $p = new Poliza();
               if($_POST['id']>0)
                  $p->get_by_id($_POST['id']);
                 unset($_POST['id']);
        $fecha = explode(" ", $_POST['fecha']);
        if ($p->exists() && ($p->anio != substr($fecha[2], 2, 2) || $p->mes != $fecha[1])) {
            $p->concepto = "Cancelada por cambio de mes, nueva poliza " . substr($fecha[2], 2, 2) . $fecha[1] . sprintf("%04d", $_POST['consecutivo']);
            $p->estatus_general_id = 2;
            $this->db->query("DELETE FROM poliza_detalles WHERE poliza_id = $p->id");
            $p->save();
            $p->id = null;
            unset($_POST['id']);
        }
        $p->from_array($_POST);
        $p->ctipo_poliza_id = 1;
        $p->empresa_id = $GLOBALS['empresa_id'];
        $p->concepto = utf8_decode($_POST['concepto']);
        $p->fecha = "{$fecha[2]}-{$fecha[1]}-{$fecha[0]}";
        $p->fecha_captura = date("Y-m-d H:i:s");
        $p->usuario_id = $GLOBALS['usuarioid'];
        $p->anio = substr($fecha[2], 2, 2);
        $p->mes = $fecha[1];
        $p->estatus_general_id = 1;
        if ($p->automatico != 1) {
            $p->automatico = 0;
            $p->ctipo_pago_poliza = 1;
        }
        $p->consecutivo = sprintf("%04d", $p->consecutivo);
        $p->folio_poliza = "$p->anio$p->mes$p->consecutivo";
        if ($p->save())
            echo "{id:$p->id}";
        else
            echo utf8_encode("{id:0, error:'Ocurrio un error al salvar la poliza, por favor intenta de nuevo'}");
    }
        
        
         function alta_manual_poliza_egreso() {
        //Dar de alta los datos generales de la poliza de diario
       $p = new Poliza();
               if($_POST['id']>0)
                  $p->get_by_id($_POST['id']);
                 unset($_POST['id']);
        $fecha = explode(" ", $_POST['fecha']);
        if ($p->exists() && ($p->anio != substr($fecha[2], 2, 2) || $p->mes != $fecha[1])) {
            $p->concepto = "Cancelada por cambio de mes, nueva poliza " . substr($fecha[2], 2, 2) . $fecha[1] . sprintf("%03d", $_POST['consecutivo']);
            $p->estatus_general_id = 2;
            $this->db->query("DELETE FROM poliza_detalles WHERE poliza_id = $p->id");
            $p->save();
            $p->id = null;
            unset($_POST['id']);
        }
        $p->from_array($_POST);
        $beneficiario = '';
        if ($_POST['beneficiario_id'] != 0) {
            $beneficiario = new Cbeneficiario($_POST['beneficiario_id']);
            $beneficiario = (utf8_decode($_POST['concepto']) != '' ? ' ' : '') .
                    'Beneficiario: ' . $beneficiario->nombre;
        }
        $p->ctipo_poliza_id = 2;
        $p->empresa_id = $GLOBALS['empresa_id'];
        $p->espacio_fisico_id = $GLOBALS['empresa_id'];
        $p->concepto = utf8_decode($_POST['concepto']) . $beneficiario;
        $p->fecha = "{$fecha[2]}-{$fecha[1]}-{$fecha[0]}";
        $p->fecha_captura = date("Y-m-d H:i:s");
        $p->usuario_id = $GLOBALS['usuarioid'];
        $p->anio = substr($fecha[2], 2, 2);
        $p->mes = $fecha[1];
        if ($p->automatico != 1) {
            $p->automatico = 0;
            $p->ctipo_pago_poliza = 1;
        }
        $p->consecutivo = sprintf("%03d", $p->consecutivo);
        $p->folio_poliza = "$p->anio$p->mes$p->banco$p->consecutivo";
        if ($p->save())
            echo "{id:$p->id}";
        else
            echo utf8_encode("{id:0, error:'Ocurrio un error al salvar la poliza, por favor intenta de nuevo'}");
    }
        function alta_manual_poliza_detalle() {
        $pr = new Poliza_detalle();
        if($_POST['id']>0)
                  $pr->get_by_id($_POST['id']);
                 unset($_POST['id']);
        $pr->from_array($_POST);
        $pr->fecha_captura = date("Y-m-d H:i:s");
        $pr->usuario_id = $GLOBALS['usuarioid'];
        if ($pr->save()) {
            $p = new Poliza($pr->poliza_id);
            $varp = $this->poliza_detalle->get_debe_haber_poliza($pr->poliza_id);
            $p->debe = $varp->debe;
            $p->haber = $varp->haber;
            $p->save();
            echo "{id:$pr->id}";
        }
        else
            echo utf8_encode("{id:0, error:'Ocurrio un error al salvar detalle de poliza, por favor intenta de nuevo'}");
    }

    function eliminar_detalle_poliza() {
        $pd = new Poliza_detalle($_POST['id']);
        $id = $pd->poliza_id;
        if ($pd->delete()) {
            $p = new Poliza($id);
            $varp = $this->poliza_detalle->get_debe_haber_poliza($id);
            $p->debe = $varp->debe;
            $p->haber = $varp->haber;
            $p->save();
            echo "{id:1}";
        }else
            echo utf8_encode("{id:0, error:'Ocurrio un error al eliminar el detalle de poliza, por favor intenta de nuevo'}");
    }







	function alta_deuda_tienda(){
		//Guardar factura de proveedor
		$tipo=$this->uri->segment(4);
		$e= new Deuda_tienda();
		$e->usuario_id=$GLOBALS['usuarioid'];
		$e->fecha_caotura=date("Y-m-d");
		$related = $e->from_array($_POST);
		if($e->id==0)
			unset($e->id);
		if($e->save($related)) {
			if($tipo=='form'){
				echo "<html> <script>alert(\"Se ha actualizado el registro '$e->id'.\"); window.location='".base_url()."index.php/contabilidad/pagos_c/formulario/list_deuda_tiendas';</script></html>";
			} else
				echo $e->id;
		} else {
			if($tipo=='form'){
				echo "<html> <script>alert(\"Ocurrio un erro al actualizar, intente de nuevo'$e->id'.\"); window.location='".base_url()."index.php/contabilidad/pagos_c/formulario/list_deuda_tienda/editar_deuda_tienda/$u->id';</script></html>";
			} else
				echo 0;
		}
	}
}
?>
