<?php

/**
 * Modelo para Generar HTML de los correos en Pavel
 *
 * Transforms users table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Salvador Salgado Ramírez SARS
 * @link
 */
class Enviar_correo extends Model{


	function Menu()
	{
		parent::Model();
	}

	function pedido_html($datos){
		//Obtener la razon social del proveedor
		$this->load->model("proveedor");
		$p=$this->proveedor->get_by_id($datos->cproveedores_id);
		//Generar html de la bienvenida
		$verificacion=md5($datos->id."&".$p->razon_social);
		$body="<img src='".base_url()."images/logo.jpg'' width='200px' align='center'><p>$p->razon_social: <br/>Por medio del presente correo, Grupo Pavel le solicita se surta la mercancia correspondiente de la siguiente orden de compra, así mismo se anexan las etiquetas de dicho pedido, las medidas de la etiqueta son  7.5 cm. de largo por 2.5 cm de alto. </p><p>La Orden de Compra es: $datos->id </p>
		<p>Fecha de Envio:  ".date("d-m-Y")." <br/>

		Más datos<br/>
		<center><a href=\"".base_url()."index.php/compras_correos/pedido_compra/$datos->id/$verificacion\">Orden de Compra # $datos->id</a></center><br/>
		<center><a href=\"".base_url()."index.php/compras_correos/pedido_etiquetas/$datos->id/$verificacion\">Etiquetas</a></center>
		</p>".
		"<p>Atentamente: <br/><br/>Grupo Pavel Pedidos</p>";
		return $body;
	}
}
