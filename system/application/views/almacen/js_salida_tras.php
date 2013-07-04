<script>
var traspasoSalidas = [];
$(document).ready(function() {
    $('#proveedores').select_autocomplete();
    $('.visible').show();
    $('.invisible').hide();

    var options = {
        target:   '#out_form1',   // target
        success:  mostrar_subform, // post-submit callback
	error: show_error
    };

    function show_error() {
        alert("ocurrio un error");	
    };
    $('#form1').submit(function() {
        $(this).ajaxSubmit(options);
        return false;
    });

    //Cantidad
    $('.unidades').keyup(function() { 
        if($(this).val()!=''){ 
            sumar_cantidades();
        } 
    });

    $('.codigo_barras').keypress(function(e) { 
        if(e.keyCode == 13) {
            get_producto_by_cod_bar($(this).val(),$(this).attr("linea"));
            return false; 
        }
    });

    //Guardar el detalle de salida 
    //y mostrar el siguiente detalle
    $('.cantidad').blur(function() {
        var linea = parseInt($(this).attr("linea"));
        u=parseFloat($(this).val());
	if(traspasoSalidas[linea]){
	    var existencia = parseInt(traspasoSalidas[linea].existencia);
	    if(existencia < u){
	        $('#content'+linea).html('');
	        $('#cantidad'+linea).val(existencia).focus();
	        return;
	    }
	} else {
	    return;
	}
        sumar_cantidades();
        $('#row'+(linea+1)).show(''); 
        $('#row'+(linea+1)).removeClass('invisible').addClass('visible');
        $('#codigo_barras'+(linea+1)).focus();
    });
    
    $(".prod").autocomplete('<?= base_url() ?>index.php/ajax_pet/get_productos_existecia_entradas_numeracion/', {
        extraParams: {espacio:<?= $espacios_fisicos_id ?>, mid: 0},
        minLength: 3,
        multiple: false,
        cacheLength:0,
        noCache: true,
        parse: function(data) {
            return $.map(eval(data), function(row) {
                return {
                    data: row,
                    value: row.id,
                    result: row.descripcion
                }
            });
        },
        formatItem: function(item) {
            return format(item);
        }
    }).result(function(e, item) {
        var linea = $(this).attr("linea");
        if( !buscar_item(linea, item) ){
        $("#producto_id"+linea).val(item.id);
        $('#cantidad'+linea).val(parseInt(item.existencia));  
        $("#producto_nid"+linea).val(item.nid);
        $("#codigo_barras"+linea).val(item.cod_bar);
        $("#existencia-" + linea).text(parseInt(item.existencia || 0));
                item.line = linea;
        traspasoSalidas[linea] = item;
	        sumar_cantidades();
        }else {
            $(this).val('');
        }
    });   

   });

    function buscar_item(linea,item){
        var result = false;
        for(i = 0; i < linea; i++){
            if(traspasoSalidas[i]){
                if(traspasoSalidas[i].nid == item.nid){
                    $('#cantidad'+i).focus();
                   result = true;
                }
            }
        }
        return result;
    } 
  
    function format(r) {
        return r.descripcion;
    }
	
	function enviar_renglon(linea){
		//Validar el traspaso
		t_id=document.form1.id.value;
		entrada_js=$('#entrada_id'+linea).val();
		id_js=$('#id'+linea).val();

		//Obtener campos clave producto_id y cantidad
		$('#content'+linea).html("<img src='<? echo base_url(); ?>images/waiting.gif' width='20px'/>");
		producto=$('#producto_id'+linea).val();
		cantidad_js=$('#cantidad'+linea).val();
                numero=$('#producto_nid'+linea).val();

		if(producto>0 && cantidad_js>0 && t_id>0){
			$.post("<? echo base_url();?>index.php/almacen/trans/alta_salida_traspasos", { 
				cproductos_id: producto,
				cantidad: cantidad_js,
				entrada_id: entrada_js,
				id: id_js,
                                producto_nid:numero,
				//tipo: tipo_js,
				traspaso_id:t_id
			}, 
				function(data){
					if(data>0){
						
						$('#content'+linea).html('<img src="<?=base_url()?>images/ok.png" width="20px" title="Guardado">');
					} else {
						$('#content'+linea).html('<img src="<?=base_url()?>images/cancelado.png" width="20px" title="Error">');
						alert("El producto no tiene existencias suficientes en su ubicacion actual, intente disminuir la cantidad o revisar el inventario");
					}
					
			});
		} else{
			$('#content'+linea).html('');
		}
	}

function verificar(){
    // Registrar los el traspaso
    $('#form1').submit();
    var id=eval("document.form1.id.value");

}

function mostrar_subform(){
    // Registrar los detalles
    for(i=0; i<500; i++) {
        var producto = $('#producto_id' + i).val();
        var cantidad = $('#cantidad' + i).val();
        if(producto > 0 && cantidad > 0){
            enviar_renglon(i);
        }
    }
    $("#boton_p").hide();
    $("#cancelar").hide();
    $("#finalizar").show();
}

    function finalizar(){
        location.href="<? echo base_url();?>index.php/<? echo $ruta; ?>/almacen_c/formulario/list_traspasos/";
    }
function get_producto_by_cod_bar(codigo_barras, linea){
    $.post("<? echo base_url();?>index.php/ajax_pet/get_prod_exist_by_cod_bar",{ enviarvalor: codigo_barras},
    function(data){
        var producto = eval("("+data+")");   
        $("#producto_id"+linea).val(producto.id);
        $('#cantidad'+linea).val(1);  
        $('#cantidad'+linea).focus();  
        $('#prod'+linea).val(producto.descripcion);
	$("#producto_nid"+linea).val(producto.nid);
        $("#existencia-" + linea).text(parseInt(producto.existencia || 0));
	data.line = linea;
        traspasoSalidas[linea] = data;
        sumar_cantidades();
    });
}

function sumar_cantidades(){
    sum=0; sum_iva=0; sum_cant=0;
    $('#total').val('x');
    for(x=0;x<=<?=$rows?>;x++){
                    if($('#cantidad'+x).val()>0){
                            sum_cant= sum_cant + parseFloat($('#cantidad'+x).val());
                    }
            }
    $('#cantidad_total').val(sum_cant);
}

        
        function mostrar(element){
// 	  alert(element);
	  $('#'+element).show();
  }
    
  
  function ocultar(element){
	  $('#'+element).hide();
  }
 
</script>
