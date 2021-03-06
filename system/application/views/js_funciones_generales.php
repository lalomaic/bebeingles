<script>
	function format(r) {
		return r.descripcion;
	}
//funcion para obtener un select ajax
function get_select_ajax_municipio(id_select_objetivo,  value){
	if(value>0){
		$.ajax({
			type: "post",
			data: {estado_id: value},
			url: "<? echo base_url(); ?>index.php/ajax_pet/poblar_select_municipios/"+value,
			success: function (data) {
				$("#"+id_select_objetivo).html(data);
			}
		});
	}
}
//funcion para obtener un select ajax
function get_select_ajax_subfamilias(id_select_objetivo,  value){
	if(value>0){
		$.ajax({
			type: "post",
			data: {familia_id: value},
			url: "<? echo base_url(); ?>index.php/ajax_pet/poblar_select_subfamilias/"+value,
			success: function (data) {
				$("#"+id_select_objetivo).html(data);
			}
		});
	}
}

//Funcion replica de la anterior para el area de captura
function get_select_ajax_captura(id_select_objetivo, tabla, col_key1, col_tag1, criterios1, orden, value){
	if(value>0){
		$('#'+id_select_objetivo+"_span").html('Cargando...');
		$.post("<? echo base_url(); ?>index.php/ajax_pet/dibujar_select_captura", {tabla_db: tabla, col_key: col_key1, col_tag: col_tag1, criterios: criterios1, order: orden, dom_id: id_select_objetivo},
			   function(data){
				   $('#'+id_select_objetivo+"_span").html(data);
			   });
	}
}

    //Funcion para limitar caracteres en textarea
    function ismaxlength(obj){
        var mlength=obj.getAttribute? parseInt(obj.getAttribute("maxlength")) : ""
        if (obj.getAttribute && obj.value.length>mlength)
            obj.value=obj.value.substring(0,mlength)
    }

    function validarFechaIni(Fecha, Fecha2){
       var date=new Date()
        var fecha=new Array("0")
        var fecha2=new Array("0")
        if(Fecha[1]==" "){
            for(i = 0; i < 9;i++){
                fecha[i+1]=Fecha[i]                
            }
            var año=fecha[6]+fecha[7]+fecha[8]+fecha[9]
            var mes=fecha[3]+fecha[4]
            var dia=fecha[0]+fecha[1]
        }else{
            var año=Fecha[6]+Fecha[7]+Fecha[8]+Fecha[9]
            var mes=Fecha[3]+Fecha[4]
            var dia=Fecha[0]+Fecha[1]
       }

        
       if(Fecha2[1]==" "){
            for(i = 0; i < 9;i++){
                fecha2[i+1]=Fecha2[i]                
            }
            var año2=fecha2[6]+fecha2[7]+fecha2[8]+fecha2[9]
            var mes2=fecha2[3]+fecha2[4]
            var dia2=fecha2[0]+fecha2[1]
        }else{
            var año2=Fecha2[6]+Fecha[7]+Fecha2[8]+Fecha2[9]
            var mes2=Fecha2[3]+Fecha[4]
            var dia2=Fecha2[0]+Fecha[1]
        }
        
        // Valido el año
        if (parseInt(año) > parseInt(año2) || parseInt(año) > parseInt(date.getFullYear())){
            return false
        }else{
            if(parseInt(año) == parseInt(año2)){
                if(parseInt(mes) > parseInt(mes2) || parseInt(mes) > parseInt(date.getMonth())){
                    return false
                }else{
                    if(parseInt(mes) == parseInt(mes2)){
                        if(parseInt(dia) > parseInt(dia2) || parseInt(dia) > parseInt(date.getDate())){
                            return false
                        }else{
                            return true

                        }
                    }else{
                        return true
                    }
                }


            }else{
                return true
            }
        }
    }
    
   

    function validarCorreo(str) {
        var at="@"
        var dot="."
        var em = ""
        var lat=str.indexOf(at)
        var lstr=str.length
        var ldot=str.indexOf(dot)
        if (str.indexOf(at)==-1){
            return false;
        }

        if (str.indexOf(at)==-1 || str.indexOf(at)==0 || str.indexOf(at)==lstr){
            return false;

        }

        if (str.indexOf(dot)==-1 || str.indexOf(dot)==0 || str.indexOf(dot)==lstr){
            return false;
        }

        if (str.indexOf(at,(lat+1))!=-1){
            return false;
        }

        if (str.substring(lat-1,lat)==dot || str.substring(lat+1,lat+2)==dot){
            return false;
        }

        if (str.indexOf(dot,(lat+2))==-1){
            return false;
        }

        if (str.indexOf(" ")!=-1){
            return false;
        }

        return true;

    }

    function validarCurp(curp){
        var patron=/(^[A-Z]{4}[0-9]{6}[A-Z]{6}[0-9]{2}$)/;
        if(curp.match(patron) || curp == ""){
            return true;
        }else{
            return false;
        }
    }

    function validarTelefono(telefono){
        var RegExPatternX = new RegExp("[0123456789 -]");
        if(telefono != ""){
            if (telefono.match(RegExPatternX)) {
                return true;
            }else{
                return false;
            }
        }
    }

    function validarCodigo(codigo){
        var RegExPatternX = new RegExp("[0123456789 -]");
        
            if (codigo.match(RegExPatternX) || codigo == "") {
                return true;
            }else{
                return false;
            }
        
    }

    function validarPuntos(puntos){
        var RegExPatternX = new RegExp("[0123456789 ]");
            if (puntos.match(RegExPatternX) || puntos.length == " ") {
                return true;
            }
            else {

               return false;
            }
        
    }

    function validarAnio(anio){
        var RegExPatternX = new RegExp("[0123456789]");
        if (anio.match(RegExPatternX)) {
            if(anio.length != 4){
                return false
            }else{
                return true
            }
        }else {
            return false;
        }
    }

    function validarAutores(autores){
        var RegExPatternX = new RegExp("[0123456789]");
        if (autores.match(RegExPatternX)) {
               return true;
            }else {
               return false;
            }
    }
    

    function validarCodigoPostal(codigo){
        var RegExPatternX = /(^([1-9]{2}|[0-9][1-9]|[1-9][0-9])[0-9]{3}$)/;
        if(codigo != ""){
            if (codigo.match(RegExPatternX)) {
                return true;
            }
            else {

               return false;
            }
        }
    }

    function validarRFC(codigo){
        var RegExPatternX =/^[a-zA-Z]{3,4}(\d{6})((\D|\d){3})?$/;
        if(codigo != " "){
            if (codigo.match(RegExPatternX)) {
                return true;
            }else{
                return false;
            }
        }else {

               return true;
            }
        }
    


   function validarFecha(fecha){
        if (fecha == "") {
                return false;
        }else {
                return true;
        }
    }

     function validarEntidad(entidad){
        if (entidad == 4) {
                return false;
        }else {
                return true;
        }
    }

     function validarNombreEntidad(entidad){
         if (entidad.length < 5) {
                return false

        }else{

            var checkOK = "abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZ0123456789ÁÉÍÓÚáéíóú#.-" + " ";
            if(entidad.match(checkOK)){
                return false
            }else{
                return true   
            }
        }
    }

     function validarPuesto(puesto){
         if (puesto.length < 4) {
                return false

        }else{

            var checkOK = "abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZ0123456789ÁÉÍÓÚáéíóú#.-" + " ";
            if(puesto.match(checkOK)){
                return false
            }else{
                return true
            }
        }
    }

    function validarDomicilio(dom1, dom2, dom3){
        if (dom1.length < 4 && dom2.length < 4 && dom3.length < 4) {
            return false

        }else{

            var checkOK = "abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZ0123456789ÁÉÍÓÚáéíóú#.-" + " ";
            if(dom1.match(checkOK) && dom2.match(checkOK) && dom3.match(checkOK)){
                return false
            }else{
                return true
            }
        }
    }

    function validarPais(entidad){
        if (entidad == 0) {
            return false;
        }else {
            return true;
        }
    }

   function validarCD(entidad){
        if (entidad == 0) {
            return false;
        }else {
            return true;
        }
    }

    function validarString(dom1){
        if (dom1.length < 3) {
            return false

        }else{

            var checkOK = "abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZ0123456789ÁÉÍÓÚáéíóú#.-" + " ";
            if(dom1.match(checkOK)){
                return false
            }else{
                return true
            }
        }
    }

    function validarSector(entidad){
        if (entidad == 0) {
            return false;
        }else {
            return true;
        }
    }

     function validarEstatus(entidad){
        if (entidad == 8) {
                return false;
        }else {
                return true;
        }
    }

    function validarEstatusGrado(entidad){
        if (entidad == 5) {
                return false;
        }else {
                return true;
        }
    }

     function validarConocimiento(entidad){
        
        if (entidad == 0) {
            return false;
        }else {
            return true;
        }
    }

    function validarTipPart(entidad){
        if (entidad == 12) {
            return false;
        }else {
            return true;
        }
    }
    function validarPaginas(ini, fin){
        var RegExPatternX = new RegExp("[0123456789]");
        if (ini.match(RegExPatternX) && fin.match(RegExPatternX)) {
            if(parseInt(ini) > parseInt(fin)){
                return false
            }else{
                return true
            }
        }else{
            return false
        }
    }

    function validarNumPag(anio){
        var RegExPatternX = new RegExp("[0123456789]");
        if (anio.match(RegExPatternX)) {
            if(anio.length > 2){
                return false
            }else{
                return true
            }
        }else {
            return false;
        }
    }

    function validarDirigido(entidad){
        if (entidad == 9) {
                return false;
        }else {
                return true;
        }
    }

    function validarTiPub(entidad){
        if (entidad == 3) {
                return false;
        }else {
                return true;
        }
    }

    function validarTipo(entidad){
        
        if (entidad == 10 || entidad == 9) {
                return false;
        }else {
                return true;
        }
    }

     function validarTipo2(entidad){
        
        if (entidad == 24 || entidad == 23) {
                return false;
        }else {
                return true;
        }
    }

    function validarGrado(entidad){

        if (entidad == 0  || entidad == 7) {
                return false;
        }else {
                return true;
        }
    }

    function validarPais2(entidad){

        if (entidad == 0  || entidad == 8) {
                return false;
        }else {
                return true;
        }
    }

    function validarISBN(dom1){
        var RegExPatternX = new RegExp("[0123456789]");
        if (dom1.match(RegExPatternX)) {
            if(dom1.length != 10){
                return false
            }else{
                return true
            }
        }else {
            return false;
        }
    }

//$(document).ready(function() {
  //	$('.subtotal').jField();
   // $('.mayus').keyup(function(){
	//	this.value = this.value.toUpperCase();
//	});
//});
</script>
