<script type="text/javascript"> 
  $(document).ready(function() { 
  $('#proveedor').select_autocomplete(); 
//  $($.date_input.initialize);

  $('#facturas').focus(function() {
    get_facturas_devolucion($('#proveedor').val());
  });


/*  $('#facturas').change(function() {
  $('#detalle_pagos').html("Cargando pagos relacionados con la factura");
    get_pagos_factura($(this).val());
  });*/
});

function get_facturas_devolucion(valor){
    if(valor>0){
      $("#facturas").removeOption(/./);
      $("#facturas").ajaxAddOption("<? echo base_url();?>index.php/ajax_pet/get_facturas_devolucion_compras/"+valor);
    } 
} 
</script>
