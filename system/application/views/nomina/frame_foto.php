<!DOCTYPE HTML>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Subir Foto</title>
	<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.9/themes/base/jquery-ui.css" id="theme">
	<link rel="stylesheet" href="<? echo base_url();?>css/jquery.fileupload-ui.css">
<style>
body {
  font-family: Verdana, Arial, sans-serif;
  font-size: 13px;
  margin: 0;
  padding: 20px;
}
</style>
</head>
<body style="width:85%; text-align:center;">
<?php
	if($empleado->ruta_foto!=''){
		$ruta_foto=$empleado->ruta_foto;
	} else
		$ruta_foto="images/image.png";
?>
<div id="foto" style="width:100%;text-align:center;"><img src="<?=base_url()?><?=$ruta_foto?>" align="center" height="100px"></div>
<form id="file_upload" action="<? echo base_url();?>index.php/ajax_pet/subir_foto_empleado/<?=$empleado->id?>" method="POST" enctype="multipart/form-data">
    <input type="file" name="file" multiple>
    <button>Upload</button>
    <div>Subir Foto</div>
</form>
<table id="files">
<!-- 	<tr><td><img src="<?=base_url()?><?=$empleado->ruta_foto?>"></td><td></td></tr> -->
</table>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.0/jquery.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.9/jquery-ui.min.js"></script>
<script src="<? echo base_url();?>css/jquery.fileupload.js"></script>
<script src="<? echo base_url();?>css/jquery.fileupload-ui.js"></script>
<script>
/*global $ */
$(function () {
    $('#file_upload').fileUploadUI({
        uploadTable: $('#files'),
        downloadTable: $('#files'),
        buildUploadRow: function (files, index) {
            return $('<tr><td>' + files[index].name + '<\/td>' +
                    '<td class="file_upload_progress"><div><\/div><\/td>' +
                    '<td class="file_upload_cancel">' +
                    '<button class="ui-state-default ui-corner-all" title="Cancelar">' +
                    '<span class="ui-icon ui-icon-cancel">Cancelar<\/span>' +
                    '<\/button><\/td><\/tr>');
        },
        buildDownloadRow: function (file) {
			$('#foto').html('');
			$('#foto').html('<img src="<?=base_url()?>'+ file.ruta_archivo +'" height="120px" align="center">');
// 			return $('<tr><td><img src="<?=base_url()?>images/productos/'+ file.rutafoto +'"><\/td><td>' + file.name + '<\/td><\/tr>');
        }
    });
});
</script>
</body>
</html>