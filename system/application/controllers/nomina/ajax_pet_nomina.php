<?php

class Ajax_pet_nomina extends Controller {

    function Ajax_pet_nomina() {
        parent::Controller();
        if ($this->session->userdata('logged_in') == FALSE) {
            redirect(base_url() . "index.php/inicio/logout");
        }
        $user_hash = $this->session->userdata('user_data');
        $row = $this->usuario->get_usuario_detalles($user_hash);
        $GLOBALS['usuarioid'] = $row->id;
        $GLOBALS['ubicacion_id'] = $row->espacio_fisico_id;
        $GLOBALS['empresa_id'] = $row->empresas_id;
        $GLOBALS['username'] = $row->username;
        $GLOBALS['ruta'] = $this->uri->segment(1);
        $GLOBALS['controller'] = $this->uri->segment(2);
        $GLOBALS['funcion'] = $this->uri->segment(3);
    }

    function get_datos_empleados_by_tienda() {
        $this->load->model("empleado");
        $query = $this->empleado->get_empleados_by_espacio_f($_POST['id']);
        if (!$query)
            $json = "false";
        else {
            foreach ($query as $row) {
                $j[] = "{   'id':'$row->id', 
                            'nombre_completo':'$row->nombre $row->apaterno $row->amaterno'
                        }";
            }
            $json = "[" . implode(", ", $j) . "]";
        }
        echo $json;
    }

    function get_empleados_prenomina_by_periodo() {
        $this->load->model(array("prenomina", "prenomina_detalle", "empleado"));
        $query = $this->prenomina->get_prenomina_by_periodo_tienda($_POST['fecha_inicial'], $_POST['fecha_final'], $_POST['espacio_id']);
        if (!$query)
            $json = "false";
        else {
            foreach ($query as $row) {
                $p_det = $this->prenomina_detalle->get_prenomina_detalle_by_prenomina_id($row->id);
                foreach ($p_det as $k) {
                    $dias_asistencia = 0;
                    $dias_asistencia = $row->dias_semana - $k->dias_faltas;
                    $empl = $this->empleado->get_empleado($k->empleado_id);
                    if ($empl->importe_infonavit>0)
                        $importe = $empl->importe_infonavit;
                    else
                        $importe = 0;
                    $j[] = "{   'id':'$row->id', 
                                'empleado_id':'$k->empleado_id',
                                'empleado':'$empl->nombre $empl->apaterno $empl->amaterno',
                                'salario':'$empl->salario',
                                'puesto':'$empl->puesto_id',
                                'comision':'$empl->comision_id',
                                'importe':$importe,
                                'dias_lab': $dias_asistencia
                        }";
                }
            }
            $json = "[" . implode(", ", $j) . "]";
        }
        echo $json;
    }

    function get_prestaciones_ajax() {
        $this->load->model("prestacion");
        $query = $this->prestacion->get_prestaciones();
        if (!$query)
            $json = "false";
        else {
            foreach ($query as $row) {
                $j[] = "{
                            'id':'$row->id',
                            'tag':'$row->tag'
                        }";
            }
            $json = "[" . implode(", ", $j) . "]";
        }
        echo $json;
    }

    function get_deducciones_ajax() {
        $this->load->model("deduccion");
        $query = $this->deduccion->get_deducciones();
        if (!$query)
            $json = "false";
        else {
            foreach ($query as $row) {
                $j[] = "{
                            'id':'$row->id',
                            'tag':'$row->tag'
                        }";
            }
            $json = "[" . implode(", ", $j) . "]";
        }
        echo $json;
    }

}