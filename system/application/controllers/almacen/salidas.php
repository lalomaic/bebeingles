<?php

class Salidas extends Controller {

    function Salidas() {
        parent::Controller();
        if ($this->session->userdata('logged_in') == FALSE) {
            redirect(base_url() . "index.php/inicio/logout");
        }
        $this->load->model("salida");
        $user_hash = $this->session->userdata('user_data');
        $row = $this->usuario->get_usuario_detalles($user_hash);
        $GLOBALS['usuario_id'] = $row->id;
        $GLOBALS['espacio_fisico_id'] = $row->espacio_fisico_id;
        $GLOBALS['empresa_id'] = $row->empresas_id;
        $GLOBALS['username'] = $row->username;
    }

    // Save de salidas post related
    function Save() {
        $_POST['espacio_fisico_id'] = $GLOBALS['espacio_fisico_id'];
        $_POST['usuario_id'] = $GLOBALS['usuario_id'];
        $s = new Salida();
        echo $s->save($s->from_array($_POST));
    }
    
		  // Cancelar la devolucion a proveedor y sus salidas
    function cancelar_devolucion_proveedor(){
        $this->load->model("devolucion_proveedor");
        $id = $this->uri->segment(4);
        $dp = new Devolucion_proveedor($id);
        $dp->estatus_general_id = 2;
        $dp->save();
        $this->db->query("update salidas set estatus_general_id='2' where devolucion_proveedor_id=$id ");
        redirect("almacen/almacen_c/formulario/list_devoluciones_proveedor");
    }

    // Test para probar el guardado de salidas
    function test(){

        $_POST = array(
            "cantidad" => 3,
            "cproductos_id" => 1,
            "cproducto_numero_id" => 1);

        $s = new Salida();
        if($s->save($s->from_array($_POST))) {
            echo "Guardado correctamente: ";
            foreach ($_POST as $key => $value) {
                echo " Key : $key; Value : $value\n";
            }
        } else {
            echo "Ocurrio un error al guardar";
        }
    }
    
}

?>
