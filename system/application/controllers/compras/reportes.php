<?php
class Reportes extends Controller {

	function Welcome()
	{
		parent::Controller();
	}



	function sespolice() {
		//Controla todos los redireccionamientos y peticiones validando las sesiones activas
		if ($this->session->userdata('logged_in') == TRUE)
		{
			$user_hash=$this->session->userdata('user_data');
			$data1=$this->db->query("select u.userid, u.puesto from users as u where user_data='$user_hash'");
			if ($data1->num_rows()>0) {
				$doctypeid="";
				foreach ($data1->result() as $row){
					$userid=$row->userid;
					//identificar el puesto para definir actividades en el sistema
					$daccess=$row->puesto;

				}

				//Variables fijas
				$data['login']=false;
				$data['user']=$userid;

				//Condicionante para usuarios de captura
				//Obtener la página a la que desea ir
				$data['principal']=$this->uri->segment(3);
				if($daccess=='6') {
					//Secretaria del Director
					if($data['principal']=="reporte_staff") {
		    //REporte general del Staff Juridico
		    if(isset($_POST['from'])==false){
		    	$from="";
		    } else {
		    	$from=$_POST['from'];
		    }
		    if(isset($_POST['to'])==false){
		    	$to="";
		    } else {
		    	$to=$_POST['to'];
		    }
		    if($from!='' && $to!=''){
		    	$where="";
		    } else {
		    	echo "<html><script>alert(\"Se necesita definir un rango de tiempo\"); window.location='".$base."index.php/welcome/sespolice/menu_reportes';</script></html>";
		    }
		    //Quejas presentadas
		    $query_h=$this->db->query("select t.tag as tipo, count(quejaid) as num from formato_queja as fq left join tipo_queja as t on t.tipo_quejaid=fq.tipo_quejaid where fq.fecha_presentacion>='$from' and fq.fecha_presentacion<='$to' and generoid=1 group by tipo");

		    $query_m=$this->db->query("select t.tag as tipo, count(quejaid) as num from formato_queja as fq left join tipo_queja as t on t.tipo_quejaid=fq.tipo_quejaid where fq.fecha_presentacion>='$from' and fq.fecha_presentacion<='$to' and generoid=2 group by tipo");
		    $aprimera=array();
		    $n=0;
		    $total_mujeres=0;
		    $total_hombres=0;
		    foreach($query_m->result() as $row){
		    	$aprimera["$n"]['Tipo']=$row->tipo;
		    	$aprimera["$n"]['Mujeres']=$row->num;
		    	$total_mujeres +=$row->num;
		    	//echo $total_mujeres."<br/>";
		    	$n+=1;
		    }
		    if($n>3){
		    	$m=1;
		    }else{$m=0;
		    }
		    $n=0;
		    foreach($query_h->result() as $row){
		    	$aprimera["$n"]['Tipo']=$row->tipo;
		    	$aprimera["$n"]['Hombres']=$row->num;
		    	$total_hombres+=$row->num;
		    	$n+=1;
		    }
		    if($n>3){
		    	$m=1;
		    }
		    $t=3+$m;
		    $aprimera["$t"]['Tipo']='Total';
		    $aprimera["$t"]['Mujeres']=$total_mujeres;
		    $aprimera["$t"]['Hombres']=$total_hombres;
		    $data['m']=$m;
		    for($x=0;$x<4+$m;$x++){
		    	if (array_key_exists('Mujeres', $aprimera["$x"])==false) {
		    		$aprimera["$x"]['Mujeres']=0;
		    	}
		    	if (array_key_exists('Hombres', $aprimera["$x"])==false) {
		    		$aprimera["$x"]['Hombres']=0;
		    	}
		    	$aprimera[$x]['Subtotal']=$aprimera[$x]['Mujeres'] + $aprimera[$x]['Hombres'];
		    }
		    $data['quejas']=$aprimera;

		    //Quejas en Tramite
		    $query_h=$this->db->query("select t.tag as tipo, count(quejaid) as num from formato_queja as fq left join tipo_queja as t on t.tipo_quejaid=fq.tipo_quejaid where estatus_quejaid<'5' and lugar_ingreso_quejaid='2' and generoid=1 group by tipo");

		    $query_m=$this->db->query("select t.tag as tipo, count(quejaid) as num from formato_queja as fq left join tipo_queja as t on t.tipo_quejaid=fq.tipo_quejaid where estatus_quejaid<'5' and lugar_ingreso_quejaid='2' and generoid=2 group by tipo");
		    $aprimera=array();
		    $n=0;
		    $total_mujeres=0;
		    $total_hombres=0;
		    foreach($query_m->result() as $row){
		    	$aprimera["$n"]['Tipo']=$row->tipo;
		    	$aprimera["$n"]['Mujeres']=$row->num;
		    	$total_mujeres +=$row->num;
		    	$n+=1;
		    }
		    if($n>3){
		    	$m=1;
		    }else{$m=0;
		    }
		    $n=0;
		    foreach($query_h->result() as $row){
		    	$aprimera["$n"]['Tipo']=$row->tipo;
		    	$aprimera["$n"]['Hombres']=$row->num;
		    	$total_hombres+=$row->num;
		    	$n+=1;
		    }
		    if($n>3){
		    	$m=1;
		    }
		    $t=3+$m;
		    $aprimera["$t"]['Tipo']='Total';
		    $aprimera["$t"]['Mujeres']=$total_mujeres;
		    $aprimera["$t"]['Hombres']=$total_hombres;
		    $data['m']=$m;

		    for($x=0;$x<4+$m;$x++){
		    	if (array_key_exists('Mujeres', $aprimera["$x"])==false) {
		    		$aprimera["$x"]['Mujeres']=0;
		    	}
		    	if (array_key_exists('Hombres', $aprimera["$x"])==false) {
		    		$aprimera["$x"]['Hombres']=0;
		    	}
		    	$aprimera[$x]['Subtotal']=$aprimera[$x]['Mujeres'] + $aprimera[$x]['Hombres'];
		    }
		    $data['quejas_tramite']=$aprimera;

		    //Liberacion de vehiculos
		    $data['query_lib']=$this->db->query("select liberacion_id as num from liberacion_vehiculos where fecha_recepcion>='$from' and fecha_recepcion<='$to'");
		    //Accidentes de Patrullas
		    $data['query_acc']=$this->db->query("select accidentes_pid as num from accidentes_patrullas where fecha_accidente>='$from' and fecha_accidente<='$to'");
		    //Accidentes de Semaforos
		    $data['query_sem']=$this->db->query("select semaforoid as num from accidentes_semaforos where fecha_accidente>='$from' and fecha_accidente<='$to'");
		    //Cancelacion de boletas
		    $data['query_can']=$this->db->query("select cancelacion_bid as num from cancelacion_boletas where fecha>='$from' and fecha<='$to'");
		    //Reuniones asistidas
		    $data['query_reun']=$this->db->query("select asistencia_rid as num from asistencia_reuniones where fecha>='$from' and fecha<='$to'");
		    //Asistencia Juridica
		    $data['query_ases']=$this->db->query("select asesoria_jid as num from asesoria_juridica where fecha>='$from' and fecha<='$to'");
		    $data['from']=$from;
		    $data['to']=$to;
		    $this->load->plugin( 'to_pdf_doc' );
		    $this->load->view('reportes/rg_jur01', $data);
		    $html = $this->output->get_output();
		    pdf_create( $html, 'Reporte_RG01_Juridico-'.date("d-m-Y"), true ); //this will stream only

					} else if($data['principal']=="reporte_audiencias") {
		    //REporte general del Staff Juridico
		    if(isset($_POST['from'])==false){
		    	$from="";
		    } else {
		    	$from=$_POST['from'];
		    }
		    if(isset($_POST['to'])==false){
		    	$to="";
		    } else {
		    	$to=$_POST['to'];
		    }
		    if($from!='' && $to!=''){
		    	$where="";
		    } else {
		    	echo "<html><script>alert(\"Se necesita definir un rango de tiempo\"); window.location='".$base."index.php/welcome/sespolice/menu_reportes';</script></html>";
		    }
		    //Querys de audiencias
		    $query_h=$this->db->query("select t.tag as tipo, count(audienciasid) as num from audiencias_direccion as fq left join audiencia_asunto as t on t.audiencia_asuntoid=fq.audiencia_asuntoid where generoid=1 group by tipo");

		    $query_m=$this->db->query("select t.tag as tipo, count(audienciasid) as num from audiencias_direccion as fq left join audiencia_asunto as t on t.audiencia_asuntoid=fq.audiencia_asuntoid where generoid=2 group by tipo");

		    $aprimera=array();
		    $n=0;
		    $total_mujeres=0;
		    $total_hombres=0;
		    foreach($query_m->result() as $row){
		    	$aprimera["$n"]['Tipo']=$row->tipo;
		    	$aprimera["$n"]['Mujeres']=$row->num;
		    	$total_mujeres +=$row->num;
		    	$n+=1;
		    }
		    $n=0;
		    foreach($query_h->result() as $row){
		    	$aprimera["$n"]['Tipo']=$row->tipo;
		    	$aprimera["$n"]['Hombres']=$row->num;
		    	$total_hombres+=$row->num;
		    	$n+=1;
		    }
		    $aprimera["$n"]['Tipo']='Total';
		    $aprimera["$n"]['Mujeres']=$total_mujeres;
		    $aprimera["$n"]['Hombres']=$total_hombres;

		    for($x=0;$x<$n+1;$x++){
		    	if (array_key_exists('Mujeres', $aprimera["$x"])==false) {
		    		$aprimera["$x"]['Mujeres']=0;
		    	}
		    	if (array_key_exists('Hombres', $aprimera["$x"])==false) {
		    		$aprimera["$x"]['Hombres']=0;
		    	}
		    	$aprimera[$x]['Subtotal']=$aprimera[$x]['Mujeres'] + $aprimera[$x]['Hombres'];
		    }
		    $data['audiencias']=$aprimera;
		    //print_r(array_values($aprimera));
		    $data['from']=$from;
		    $data['to']=$to;
		    $this->load->plugin( 'to_pdf_doc' );
		    $this->load->view('reportes/rg_audiencias', $data);
		    $html = $this->output->get_output();
		    pdf_create( $html, 'Reporte_Audiencias-'.date("d-m-Y"), true ); //this will stream only

					} else if($data['principal']=="listado_vehiculos"){

		    //Listado de los Vehiculos
		    $data['query']=$this->db->query("select v.*, t.tag as tipo, e.tag as efisico, p.tag as procedencia, v.no_economico from vehiculos as v left join tipo_vehiculo as t on t.tipovid=v.tipovid left join estado_fisico as e on e.efisicoid=v.efisicoid left join procedencia as p on p.procedenciaid=v.procedenciaid order by tipovid,marca_tipo,modelo,placa");
		    $this->load->view('reportes/listado_vehiculos', $data);

					} else if($data['principal']=="listado_agentes"){

		    //Listado de los Vehiculos

		    $data['query']=$this->db->query("select a.*, v. placa,c.tag as modulo, p.tag as procedencia, pu.tag as puesto from agentes as a left join vehiculos as v on v.vehiculoid=a.vehiculos_vehiculoid left join cat_modulos as c on c.moduloid=a.moduloid left join procedencia as p on p.procedenciaid=a.procedenciaid left join tipo_agente as pu on pu.tipo_agenteid=a.tipo_agenteid order by modulo, lastname, name_real");

		    $this->load->view('reportes/listado_agentes', $data);

					}

				} else if($daccess=='7') {
					//Asuntos Internos
					$data['menu']="atencion_c";
					if($data['principal']=="listado") {

					}
				} else if($daccess=='5') {
					//Redirigir al tipo de usuario Jefatura de Juridico
					$data['menu']="jefe_staff";
					if($data['principal']=="listado" or $data['principal']=="principal"){
						$data['principal']="jefatura/principal";
					} else if ($data['principal']=="menu_reportes" ){
						$data['principal']="jefatura/menu_reportes";
					} else if ($data['principal']=="quejas1_pdf"){
						if(isset($_POST['from'])==false){
							$from="";
						} else {
							$from=$_POST['from'];
						}
						if(isset($_POST['to'])==false){
							$to="";
						} else {
							$to=$_POST['to'];
						}
						if(isset($_POST['tiempo'])==false){
							$range=0;
						} else {
							$range=$_POST['tiempo'];
						}
						$agenteid=$_POST['agente'];
						$tipo=$_POST['tipo'];
						$estatus=$_POST['estatus'];
						$generoid=$_POST['genero'];
						$userid=$_POST['capturista'];
						$nivel1=$_POST['nivel1'];
						$nivel2=$_POST['nivel2'];
						$nivel3=$_POST['nivel3'];
						$where_clause="";
						//Conformando el Query. Filtrado
		    if($tipo=='1000'){
		    	$where_clause.="";
		    } else {
		    	$where_clause.=" fq.tipo_quejaid='$tipo' and";
		    }
		    if($agenteid =='1000'){
		    	$where_clause.="";
		    } else {
		    	$where_clause.=" fq.agenteid='$agenteid' and";
		    }
		    if($estatus=='1000'){
		    	$where_clause.="";
		    } else {
		    	$where_clause.=" fq.estatus_quejaid='$estatus' and";
		    }
		    if($generoid=='1000'){
		    	$where_clause.="";
		    } else {
		    	$where_clause.=" fq.generoid='$generoid' and";
		    }
		    if($userid=='1000'){
		    	$where_clause.="";
		    } else {
		    	$where_clause.=" fq.userid='$userid' and";
		    }

		    if(strlen($where_clause)>0){
		    	$where_clause=substr($where_clause, 0,-3);
		    } else {
		    	$where_clause=" quejaid>'0'";
		    }
		    $select_clause="select fq.*, t.tag as tipo, concat(u.name_real, ' ', u.lastname) as capturista,  concat('Calle ', fq.calle, ' # ', fq.numero,' Col. ', fq.colonia) as domicilio, concat(' Tel.  ', fq.telefono_part) as telefono, g.tag as genero,  concat(a.lastname, ' ', a.name_real) as agente, s.tag as sancion from formato_queja as fq left join users as u on u.userid=fq.userid left join tipo_queja as t on t.tipo_quejaid=fq.tipo_quejaid left join agentes as a on a.agenteid=fq.agentes_agenteid left join genero as g on g.generoid=fq.generoid left join sancion as s on s.sancion_id=fq.sancionid where $where_clause";

		    //Rango de Tiempo
		    if($range>0){
		    	//Obtener la fecha actual
		    	$hoy=date("Y/m/d");
		    	$hoy_ts=mktime();
		    	if($range==1){
		      $deseada_ts=$hoy_ts-(24 * 60 * 60);
		      $fecha_deseada=date("Y/m/d", $deseada_ts);
		    	} else if($range==2){
		      //Semana
		      $deseada_ts=$hoy_ts-(7 * 24 * 60 * 60);
		      $fecha_deseada=date("Y/m/d", $deseada_ts);
		    	} else if($range==3){
		      //Mes Actual
		      $mes=date('m');
		      $año=date('Y');
		      //$dias=getdate('t'); //Obtiene el numero de dias del mes para usos posteriores
		      $fecha_deseada=$año."/".$mes."/01";
		    	} else if($range==4){
		      //Año
		      $año=date('Y');
		      $fecha_deseada=$año."/01/01";
		    	}
		    	$and_clause=" and fq.fecha_presentacion>='$fecha_deseada' and fq.fecha_presentacion<='$hoy'";
		    	//$fecha1=$this->model1->fecha_imp($fecha_deseada);
		    	//$fecha2=$this->model1->fecha_imp($hoy);
		    	$fecha1=$fecha_deseada;
		    	$fecha2=$hoy;
		    }

		    if ($from!='') {
		    	if ($to==''){
		    		$to=date("Y/m/d");
		    	}
		    	$and_clause=" and fq.fecha_presentacion>='$from' and fq.fecha_presentacion<='$to'";
		    	$fecha1=$from;
		    	$fecha2=$to;

		    }

		    //Order by
		    $order_clause=" order by ";
		    $value="";
		    $ingreso=0;
		    for($x=0;$x<3;$x++){
		    	if($x==1){
		      $value=$nivel1;
		      $ingreso+=1;
		    	} else if($x==2){
		      $value=$nivel2;
		      $ingreso+=1;
		    	} else if($x==1){
		      $value=$nivel3;
		      $ingreso+=1;
		    	}
		    	 
		    	if($value ==1){
		      $order_clause.="fq.fecha_presentacion,";
		    	} else if($value ==2){
		      $order_clause.="t.tag,";
		    	} else if($value ==3){
		      $order_clause.="fq.estatus_quejaid,";
		    	} else if($value ==4){
		      $order_clause.="a.lastname, a.name_real,";
		    	} else if($value ==5){
		      $order_clause.="u.lastname, u.name_real,";
		    	} else if($value ==6){
		      $order_clause.="fq.generoid,";
		    	} else if($value ==0){
		      //$order_clause.="f.generalid,";
		    	}
		    }
		    if($ingreso>0){
		    	$order_by=substr($order_clause, 0,-1);
		    } else {
		    	$order_by="";
		    }
		    //Make query
		    $query=$select_clause.$and_clause.$order_by;
		    $data['query']=$this->db->query($query);
		    $data['fecha1']=$fecha1;
		    $data['fecha2']=$fecha2;
		    $this->load->view('reportes/quejas1_pdf', $data);

					} else if($data['principal']=="historial_agente1_pdf"){
						//Historial Agente
						$agentesid=$_POST['agente'];
						$tipo=$_POST['tipo'];
						if(isset($_POST['from'])==false){
							$from="";
						} else {
							$from=$_POST['from'];
						}
						if(isset($_POST['to'])==false){
							$to="";
						} else {
							$to=$_POST['to'];
						}
						if(isset($_POST['tiempo'])==false){
							$range=0;
						} else {
							$range=$_POST['tiempo'];
						}
						$nivel1=$_POST['nivel1'];
						//$nivel2=$_POST['nivel2'];
						//$nivel3=$_POST['nivel3'];

						if($agentesid=='1000'){
							$where_clause=" >'0'";
						} else {
							$where_clause=" ='$agentesid'";
						}
						//Rango de Tiempo
						if($range>0){
							//Obtener la fecha actual
							$hoy=date("Y/m/d");
							$hoy_ts=mktime();
							if($range==1){
		      $deseada_ts=$hoy_ts-(24 * 60 * 60);
		      $fecha_deseada=date("Y/m/d", $deseada_ts);
							} else if($range==2){
		      //Semana
		      $deseada_ts=$hoy_ts-(7 * 24 * 60 * 60);
		      $fecha_deseada=date("Y/m/d", $deseada_ts);
							} else if($range==3){
		      //Mes Actual
		      $mes=date('m');
		      $año=date('Y');
		      //$dias=getdate('t'); //Obtiene el numero de dias del mes para usos posteriores
		      $fecha_deseada=$año."/".$mes."/01";
							} else if($range==4){
		      //Año
		      $año=date('Y');
		      $fecha_deseada=$año."/01/01";
							}
							$and_clause1=" >='$fecha_deseada' ";
							$and_clause2=" <='$hoy'";
							//$fecha1=$this->model1->fecha_imp($fecha_deseada);
							//$fecha2=$this->model1->fecha_imp($hoy);
							$data['fecha1']=$fecha_deseada;
							$data['fecha2']=$hoy;
						}

						if ($from!='') {
							if ($to==''){
								$to=date("Y/m/d");
							}
							$and_clause1=" >='$from'";
							$and_clause2=" <='$to'";
							$data['fecha1']=$from;
							$data['fecha2']=$to;

						}

						$data['agentes_dis']=$this->db->query("select distinct(agentes_agenteid) from formato_queja as fq where tipo_quejaid='2' and agentes_agenteid $where_clause and fq.fecha_presentacion $and_clause1 and fq.fecha_presentacion $and_clause2  order by agentes_agenteid");
						$data['admin_dis']=$this->db->query("select distinct(agentes_agenteid) from juicio_administrativo as ja where agentes_agenteid $where_clause and ja.fecha_oficio $and_clause1 and ja.fecha_oficio $and_clause2 order by agentes_agenteid");
						$data['cancelacion_dis']=$this->db->query("select distinct(agentes_agenteid) from cancelacion_boletas as c where agentes_agenteid $where_clause and c.fecha $and_clause1 and c.fecha $and_clause2  order by agentes_agenteid");

						if($tipo==1){
							//Agentes filtrados
							$data['query_agentes']=$this->db->query("select * from agentes where agenteid $where_clause");
							 
							//Query de inconformidades
							$data['query_quejas']=$this->db->query("select fq.*, s.tag as sancion, concat(a.name_real, ' ', a.lastname) as agente, l.tag as lugar from formato_queja as fq left join sancion as s on fq.sancionid=s.sancion_id left join agentes as a on a.agenteid=fq.agentes_agenteid left join lugar_ingreso_queja as l on l.ingreso_quejaid=fq.lugar_ingreso_quejaid where tipo_quejaid='2' and agentes_agenteid $where_clause and fq.fecha_presentacion $and_clause1 and fq.fecha_presentacion $and_clause2 ");

							//Query de Juicios Admin
							$data['query_admin']=$this->db->query("select ja.*, s.tag as sancion, concat(a.name_real, ' ', a.lastname) as agente, e.tag as estatus from juicio_administrativo as ja left join sancion as s on ja.sancionid=s.sancion_id left join agentes as a on a.agenteid=ja.agentes_agenteid left join estatus_juicio_a as e on e.estatus_juicio_aid=ja.estatus_juicio_aid where agentes_agenteid $where_clause and ja.fecha_oficio $and_clause1 and ja.fecha_oficio $and_clause2");

							//Accidentes de Vehiculos
							$data['query_accidentes_v']=$this->db->query("select ap.*, v.*, e.tag as estatus from accidentes_patrullas as ap left join vehiculos as v on v.vehiculoid=ap.vehiculos_vehiculoid left join estatus_ap as e on e.estatus_apid=ap.estatus_apid where agenteid $where_clause and ap.fecha_accidente $and_clause1 and ap.fecha_accidente $and_clause2 ");

							//Cancelacion de boletas
							$data['query_cancelacion']=$this->db->query("select c.* from cancelacion_boletas as c where agentes_agenteid $where_clause and c.fecha $and_clause1 and c.fecha $and_clause2 ");
							$this->load->view('reportes/historia_agentes_pdf', $data);

						} else if($tipo==2) {
							$array_id="";
							foreach($data['agentes_dis']->result() as $row){
		      $array_id.=$row->agentes_agenteid.",";
							}
							foreach($data['admin_dis']->result() as $row){
		      $array_id.=$row->agentes_agenteid.",";
							}
							foreach($data['cancelacion_dis']->result() as $row){
		      $array_id.=$row->agentes_agenteid.",";
							}
							if(strlen($array_id)>0){
								$array_id=substr($array_id, 0,-1);
							}
							$data['query']=$this->db->query("select * from agentes where agenteid not in ($array_id)");
							$this->load->view('reportes/clean_agentes_pdf', $data);

						}


					} else if($data['principal']=="reporte_staff") {
		    //REporte general del Staff Juridico
		    if(isset($_POST['from'])==false){
		    	$from="";
		    } else {
		    	$from=$_POST['from'];
		    }
		    if(isset($_POST['to'])==false){
		    	$to="";
		    } else {
		    	$to=$_POST['to'];
		    }
		    if($from!='' && $to!=''){
		    	$where="";
		    } else {
		    	echo "<html><script>alert(\"Se necesita definir un rango de tiempo\"); window.location='".$base."index.php/welcome/sespolice/menu_reportes';</script></html>";
		    }
		    //Quejas presentadas
		    $query_h=$this->db->query("select t.tag as tipo, count(quejaid) as num from formato_queja as fq left join tipo_queja as t on t.tipo_quejaid=fq.tipo_quejaid where fq.fecha_presentacion>='$from' and fq.fecha_presentacion<='$to' and generoid=1 group by tipo");

		    $query_m=$this->db->query("select t.tag as tipo, count(quejaid) as num from formato_queja as fq left join tipo_queja as t on t.tipo_quejaid=fq.tipo_quejaid where fq.fecha_presentacion>='$from' and fq.fecha_presentacion<='$to' and generoid=2 group by tipo");
		    $aprimera=array();
		    $n=0;
		    $total_mujeres=0;
		    $total_hombres=0;
		    foreach($query_m->result() as $row){
		    	$aprimera["$n"]['Tipo']=$row->tipo;
		    	$aprimera["$n"]['Mujeres']=$row->num;
		    	$total_mujeres +=$row->num;
		    	//echo $total_mujeres."<br/>";
		    	$n+=1;
		    }
		    if($n>3){
		    	$m=1;
		    }else{$m=0;
		    }
		    $n=0;
		    foreach($query_h->result() as $row){
		    	$aprimera["$n"]['Tipo']=$row->tipo;
		    	$aprimera["$n"]['Hombres']=$row->num;
		    	$total_hombres+=$row->num;
		    	$n+=1;
		    }
		    if($n>3){
		    	$m=1;
		    }
		    $t=3+$m;
		    $aprimera["$t"]['Tipo']='Total';
		    $aprimera["$t"]['Mujeres']=$total_mujeres;
		    $aprimera["$t"]['Hombres']=$total_hombres;
		    $data['m']=$m;
		    for($x=0;$x<4+$m;$x++){
		    	if (array_key_exists('Mujeres', $aprimera["$x"])==false) {
		    		$aprimera["$x"]['Mujeres']=0;
		    	}
		    	if (array_key_exists('Hombres', $aprimera["$x"])==false) {
		    		$aprimera["$x"]['Hombres']=0;
		    	}
		    	$aprimera[$x]['Subtotal']=$aprimera[$x]['Mujeres'] + $aprimera[$x]['Hombres'];
		    }
		    $data['quejas']=$aprimera;

		    //Quejas en Tramite
		    $query_h=$this->db->query("select t.tag as tipo, count(quejaid) as num from formato_queja as fq left join tipo_queja as t on t.tipo_quejaid=fq.tipo_quejaid where estatus_quejaid<'5' and lugar_ingreso_quejaid='2' and generoid=1 group by tipo");

		    $query_m=$this->db->query("select t.tag as tipo, count(quejaid) as num from formato_queja as fq left join tipo_queja as t on t.tipo_quejaid=fq.tipo_quejaid where estatus_quejaid<'5' and lugar_ingreso_quejaid='2' and generoid=2 group by tipo");
		    $aprimera=array();
		    $n=0;
		    $total_mujeres=0;
		    $total_hombres=0;
		    foreach($query_m->result() as $row){
		    	$aprimera["$n"]['Tipo']=$row->tipo;
		    	$aprimera["$n"]['Mujeres']=$row->num;
		    	$total_mujeres +=$row->num;
		    	$n+=1;
		    }
		    if($n>3){
		    	$m=1;
		    }else{$m=0;
		    }
		    $n=0;
		    foreach($query_h->result() as $row){
		    	$aprimera["$n"]['Tipo']=$row->tipo;
		    	$aprimera["$n"]['Hombres']=$row->num;
		    	$total_hombres+=$row->num;
		    	$n+=1;
		    }
		    if($n>3){
		    	$m=1;
		    }
		    $t=3+$m;
		    $aprimera["$t"]['Tipo']='Total';
		    $aprimera["$t"]['Mujeres']=$total_mujeres;
		    $aprimera["$t"]['Hombres']=$total_hombres;
		    $data['m']=$m;

		    for($x=0;$x<4+$m;$x++){
		    	if (array_key_exists('Mujeres', $aprimera["$x"])==false) {
		    		$aprimera["$x"]['Mujeres']=0;
		    	}
		    	if (array_key_exists('Hombres', $aprimera["$x"])==false) {
		    		$aprimera["$x"]['Hombres']=0;
		    	}
		    	$aprimera[$x]['Subtotal']=$aprimera[$x]['Mujeres'] + $aprimera[$x]['Hombres'];
		    }
		    $data['quejas_tramite']=$aprimera;

		    //Liberacion de vehiculos
		    $data['query_lib']=$this->db->query("select liberacion_id as num from liberacion_vehiculos where fecha_recepcion>='$from' and fecha_recepcion<='$to'");
		    //Accidentes de Patrullas
		    $data['query_acc']=$this->db->query("select accidentes_pid as num from accidentes_patrullas where fecha_accidente>='$from' and fecha_accidente<='$to'");
		    //Accidentes de Semaforos
		    $data['query_sem']=$this->db->query("select semaforoid as num from accidentes_semaforos where fecha_accidente>='$from' and fecha_accidente<='$to'");
		    //Cancelacion de boletas
		    $data['query_can']=$this->db->query("select cancelacion_bid as num from cancelacion_boletas where fecha>='$from' and fecha<='$to'");
		    //Reuniones asistidas
		    $data['query_reun']=$this->db->query("select asistencia_rid as num from asistencia_reuniones where fecha>='$from' and fecha<='$to'");
		    //Asistencia Juridica
		    $data['query_ases']=$this->db->query("select asesoria_jid as num from asesoria_juridica where fecha>='$from' and fecha<='$to'");
		    $data['from']=$from;
		    $data['to']=$to;
		    $this->load->plugin( 'to_pdf_doc' );
		    $this->load->view('reportes/rg_jur01', $data);
		    $html = $this->output->get_output();
		    pdf_create( $html, 'Reporte_RG01_Juridico-'.date("d-m-Y"), true ); //this will stream only

					} else if($data['principal']=="informe_mensual_pdf"){
		    //REporte mensual Juridico
						$id=$this->uri->segment(4);
		    $data['query1']=$this->db->query("select * from informe_mensual where informe_id='$id'");
		    foreach($data['query1']->result() as $row){
		    	$mes=$row->mes;
		    	$year=$row->year;
		    }
		    $from=$year."/".$mes."/01";
		    $to=$year."/".($mes+1)."/01";
		    //Resumen Quejas presentadas
		    $query_h=$this->db->query("select t.tag as tipo, count(quejaid) as num from formato_queja as fq left join tipo_queja as t on t.tipo_quejaid=fq.tipo_quejaid where fq.fecha_presentacion>='$from' and fq.fecha_presentacion<='$to' and fq.generoid=1  group by tipo order by fq.tipo_quejaid desc");

		    $query_m=$this->db->query("select t.tag as tipo, count(quejaid) as num from formato_queja as fq left join tipo_queja as t on t.tipo_quejaid=fq.tipo_quejaid where fq.fecha_presentacion>='$from' and fq.fecha_presentacion<='$to' and fq.generoid=2  group by tipo order by fq.tipo_quejaid desc");
		    $aprimera=array();
		    $n=0;
		    $total_mujeres=0;
		    $total_hombres=0;
		    $aprimera[0]['Tipo']="Sin clasificar";
		    $aprimera[0]['Hombres']=0;
		    $aprimera[0]['Mujeres']=0;

		    foreach($query_m->result() as $row){
		    	$aprimera["$n"]['Tipo']=$row->tipo;
		    	$aprimera["$n"]['Mujeres']=$row->num;
		    	$total_mujeres +=$row->num;
		    	$n+=1;
		    }
		    if($n>3){
		    	$m=1;
		    }else{$m=0;
		    }
		    $n=0;
		    foreach($query_h->result() as $row){
		    	$aprimera["$n"]['Tipo']=$row->tipo;
		    	$aprimera["$n"]['Hombres']=$row->num;
		    	$total_hombres+=$row->num;
		    	$n+=1;
		    }
		    if($n>3){
		    	$m=1;
		    }
		    $t=3+$m;
		    $aprimera["$t"]['Tipo']='Total';
		    $aprimera["$t"]['Mujeres']=$total_mujeres;
		    $aprimera["$t"]['Hombres']=$total_hombres;
		    $data['m']=$m;
		    for($x=0;$x<4+$m;$x++){
		    	if (array_key_exists('Mujeres', $aprimera["$x"])==false) {
		    		$aprimera["$x"]['Mujeres']=0;
		    	}
		    	if (array_key_exists('Hombres', $aprimera["$x"])==false) {
		    		$aprimera["$x"]['Hombres']=0;
		    	}
		    	$aprimera[$x]['Subtotal']=$aprimera[$x]['Mujeres'] + $aprimera[$x]['Hombres'];
		    }
		    $data['quejas']=$aprimera;

		    //Listados Generales Tipo Inconformidad
		    $data['query2_h']=$this->db->query("select fq.*, concat(a.name_real, ' ', a.lastname) as agente, s.tag as sancion from formato_queja as fq left join agentes as a on a.agenteid=fq.agentes_agenteid left join sancion as s on fq.sancionid=s.sancion_id where fq.fecha_presentacion>='$from' and fq.fecha_presentacion<='$to' and fq.generoid=1 and tipo_quejaid=2 order by fecha_presentacion");

		    $data['query2_m']=$this->db->query("select fq.*, concat(a.name_real, ' ', a.lastname) as agente, s.tag as sancion from formato_queja as fq left join agentes as a on a.agenteid=fq.agentes_agenteid left join sancion as s on fq.sancionid=s.sancion_id where fq.fecha_presentacion>='$from' and fq.fecha_presentacion<='$to' and fq.generoid=2 and tipo_quejaid=2  order by fecha_presentacion");


		    //Listados Generales Tipo Solicitud
		    $data['query3_h']=$this->db->query("select fq.*, concat(a.name_real, ' ', a.lastname) as agente, s.tag as sancion from formato_queja as fq left join agentes as a on a.agenteid=fq.agentes_agenteid left join sancion as s on fq.sancionid=s.sancion_id where fq.fecha_presentacion>='$from' and fq.fecha_presentacion<='$to' and fq.generoid=1 and tipo_quejaid=3 order by fecha_presentacion");

		    $data['query3_m']=$this->db->query("select fq.*, concat(a.name_real, ' ', a.lastname) as agente, s.tag as sancion from formato_queja as fq left join agentes as a on a.agenteid=fq.agentes_agenteid left join sancion as s on fq.sancionid=s.sancion_id where fq.fecha_presentacion>='$from' and fq.fecha_presentacion<='$to' and fq.generoid=2 and tipo_quejaid=3 order by fecha_presentacion");


		    //Listados Generales Tipo Comentario
		    $data['query1_h']=$this->db->query("select fq.*, concat(a.name_real, ' ', a.lastname) as agente, s.tag as sancion from formato_queja as fq left join agentes as a on a.agenteid=fq.agentes_agenteid left join sancion as s on fq.sancionid=s.sancion_id where fq.fecha_presentacion>='$from' and fq.fecha_presentacion<='$to' and fq.generoid=1 and tipo_quejaid=1 order by fecha_presentacion");

		    $data['query1_m']=$this->db->query("select fq.*, concat(a.name_real, ' ', a.lastname) as agente, s.tag as sancion from formato_queja as fq left join agentes as a on a.agenteid=fq.agentes_agenteid left join sancion as s on fq.sancionid=s.sancion_id where fq.fecha_presentacion>='$from' and fq.fecha_presentacion<='$to' and fq.generoid=2 and tipo_quejaid=1 order by fecha_presentacion");

		    $this->load->view('reportes/informe_mensual_pdf', $data);

					}
						
					//Final Usuario Juridico de Captura
				}else if($daccess=='4') {
					//Redirigir al tipo de usuario Juridico Captura
					$data['menu']="juridico";
					if($data['principal']=="listado") {
		    $data['principal']="principal";
					}

					//Final Usuario Juridico de Captura
				} else if($daccess=='3') {
					//Redirigir al tipo de usuario captura

					 
				} else if ($daccess==2){
					//Usuario de Supervisor

		  }
		  //Usuario Administración
		  if($daccess==1){
		  	//Usuario adminsitrador

		  	//Final usuario admin
		  }

			}
	   
		}
	}
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */
