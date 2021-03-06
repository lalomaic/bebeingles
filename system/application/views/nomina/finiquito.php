<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<style>
h2{
  background-color:#ccc;
}
.izquierda {
  text-align:left;
}
.final_seccion{
  border-bottom:1px solid #ccc;
}
.fecha_impresion{
  font-size:5pt;
  text-align:left;
}
.total{
  border-top:2px solid #000;
  font-size:8pt;
}
</style>
 <body>
<table style="table-layout: fixed; font-size: 11pt;  vertical-align: middle; " width="510" id="table_tcp">
  <tr>
	<td  valign="middle" align="center"><h3>R E N U N C I A <br><br></h3></td>
  </tr>
  <tr>
  	<td  align="right"><h4> Morelía, Mich., a <?php echo $fecha1 ?> </h4></td>
  </tr><br>
  <tr>
  <td  valign="top" class="izquierda"><h4><?php echo $razon_social?><br/>
	P R E S E N T E.<br/><br></h4></td>
  </tr>
<tr>
<td  valign="top" class="izquierda"> &nbsp; &nbsp; &nbsp;&nbsp;&nbsp; &nbsp; Por&nbsp; la&nbsp; presente,&nbsp; le&nbsp; manifiesto que&nbsp; con esta&nbsp; fecha,&nbsp; y&nbsp; por&nbsp; así convenir&nbsp; a mis&nbsp; intereses,
me &nbsp; separo&nbsp; voluntariamente&nbsp; del&nbsp; trabajo&nbsp; que&nbsp; venía&nbsp; desempeñando&nbsp; para la fuente de trabajo 
que usted representa, bajo la categoría de “<?php echo $puesto ?>”, es decir, renuncio en  
forma voluntaria a las labores que venía desempeñando y sin responsabilidad para la patronal. <br><br>
</td>
</tr>
<tr>
<td valign="top" class="izquierda">
&nbsp; &nbsp; &nbsp;&nbsp;&nbsp; &nbsp;  Expresamente,&nbsp; les&nbsp; manifiesto&nbsp; que&nbsp; durante &nbsp;mi&nbsp; trabajo&nbsp; siempre&nbsp; se &nbsp;me &nbsp;otorgaron y pagaron íntegramente todas las prestaciones que me correspondieron, 
por lo que a&nbsp; la fecha no se&nbsp; me adeuda en&nbsp;&nbsp; lo&nbsp; absoluto&nbsp;&nbsp; ninguna &nbsp; cantidad&nbsp; por&nbsp; conceptos&nbsp; de&nbsp; salarios,&nbsp; séptimos&nbsp; días,&nbsp; días&nbsp; de&nbsp; descanso obligatorio,
vacaciones,&nbsp; prima vacacional&nbsp; ni dominical,&nbsp; aguinaldo,&nbsp; utilidades, prima de antigüedad ni por ningún otro título derivado de la relación de 
trabajo  que con esta  fecha, personal y voluntariamente termino. 
<br><br>&nbsp; &nbsp; &nbsp;&nbsp;&nbsp; &nbsp; Así mismo,&nbsp; manifiesto &nbsp;que no labore horas extras&nbsp; y&nbsp; las&nbsp; que&nbsp; labore&nbsp; me&nbsp; fueron&nbsp; 
pagadas&nbsp; de&nbsp; manera&nbsp; oportuna,&nbsp; no sufrí&nbsp; accidentes&nbsp; o enfermedades &nbsp;de trabajo, y en general me fueron cubiertas todas las prestaciones laborales 
a que tuve derecho durante la época en que preste mis servicios para usted. <br><br>
</td>
</tr>
<tr>
<td  valign="top" class="izquierda">
&nbsp; &nbsp; &nbsp;&nbsp;&nbsp; &nbsp; Por otra parte, deseo hacer constar que siempre cumplió para conmigo, con sus obligaciones en materia&nbsp; de&nbsp; seguridad&nbsp; social,&nbsp; tales&nbsp; como&nbsp; afiliarme&nbsp; al Instituto Mexicano del Seguro Social, pagar las cuotas patronales, dar los avisos correspondientes, INFONAVIT, SAR y/o Afore.
<br><br>
</td>
</tr>
<tr>
<td valign="top" class="izquierda">
&nbsp; &nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; Por&nbsp; todo&nbsp; lo anterior, &nbsp;no&nbsp; me&nbsp; resta más que expresarle mi agradecimiento, quedando de usted como su S.S.
<br><br><br><br><br>
</td>
</tr>

<tr style="width:20%" align="center">
<td>
A T E N T A M E N T E<br><br><br><br><br>
</td>
</tr>

<tr style="width:20%" align="center">
<td>
___________________________________ <br><br>
<?php  echo $empleado ?>
</td>
</tr>

<br><br><br><br><br>

<tr>
<td  valign="top" class="izquierda">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;RECIBÍ: de <?php  echo $razon_social ?> , en cuanto propietario y responsable de la fuente de trabajo ubicada en la calle <?php  echo $calle ." Num. Exterior ". $num_ext ." Colonia ". $colonia ." de la ciudad de ". $localidad ?> ,comercialmente conocida como <?php echo '"'. $espacio_tag.'"' ?>, la cantidad de: <?php echo $total ." (". $letra .")" ?> ,por concepto de pago de 
diversas prestaciones generadas durante la Relación de Trabajo, y como finiquito de la misma por terminación de mutuo consentimiento, en términos del artículo 53 fracción I de la Ley Federal del Trabajo, cuantificadas conforme a los siguientes datos generales, que constituyen las últimas condiciones de trabajo a las que estuve sujeta.
<br><br>
</td>
</tr>
</table>

<table style="margin-top:20px; table-layout: fixed; font-size: 11pt; text-align: left; align: center; border: .5px solid #ccc;" width="510" id="table_tcp">

  <tr align="center">
	<td>Categoria: </td>
	<td><?php echo $puesto ?></td> 
	 </tr>
	 
<tr align="center">
<td> Fecha de Ingreso: </td>
	<td> <?php  echo $fecha_ingreso ?> </td>
</tr>

  <tr align="center">
	<td> Fecha de Baja: </td>
	<td> <?php  echo $fecha_baja ?> </td></tr>

<tr align="center">
	<td> Salario Diario: </td>
	<td><?php  echo $salario ?></td>
  </tr> 
</table>
<br>
<table style="margin-top:20px; font-size: 11pt; text-align: left;  border: .5px solid #ccc;" width="510" id="table_tcp">
  <tr>
  <td></td>
	<td><b>Prestaciones:</b> </td>
	<td></td>
	<td align="right"><b>Cantidades</b></td> 
	<td></td>
	 </tr>
	 
<tr>
<td></td>
<td>Aguinaldo: </td>
<td></td>
	<td align="right"><?php  echo $aguinaldo ?> </td>
	<td></td>
</tr>
  <tr>
  <td></td>
	<td >Vacaciones: </td>
	<td></td>
	<td align="right"><?php  echo $vacaciones ?> </td>
<td></td>	
	</tr>

  <tr>
<td></td>
	<td >Adeudos: </td>
	<td></td>
	<td align="right"><?php echo "$ -".$adeudos ?> </td>
<td></td>	
	</tr>

<tr>
<td></td>
	<td>Prima Vacacional: </td>
	<td></td>
	<td align="right"><?php  echo $pri_vacacional ?></td>
	<td></td>
  </tr>
  
  <tr>
  <td></td>
	<td>Total A recibir: </td>
	<td></td>
	<td align="right"><?php  echo $total ?></td>
	<td></td>
  </tr>
</table>
<br><br>
<table style="margin-top:20px; table-layout: fixed; font-size: 11pt; text-align: left; vertical-align: middle; " width="510" id="table_tcp">
<tr>
<td valign="top" class="izquierda">
Cantidad que se me cubre en este mismo acto y a mi más entera satisfacción. Declarando para los efectos legales correspondientes, que otorgo a <?php  echo $empleado ?> , el más amplio finiquito de obligaciones en los términos de la Ley Federal del Trabajo, por concepto de sueldos ordinarios, séptimos días, días de descanso obligatorio, 
horas extras, vacaciones, prima vacacional, aguinaldo, así como cualquiera otra prestación legal o contractual, manifestando que no contraje enfermedad profesional alguna durante el tiempo laborado, y no me reservo acción, ni derecho de ninguna naturaleza que ejercen en su contra o de sus representantes así como de quien resulte responsable de la 
fuente de trabajo calle <?php  echo $calle  ." Num. Exterior ". $num_ext ." Colonia ". $colonia ." de la ciudad de ". $localidad ?> o
 de sus sucursales en el Estado o en la República Mexicana. 
 <p align="right">Morelia, Michoacán a <?php echo $fecha1 ?>.</p><br><br><br><br><br>
</td>
</tr>
<tr style="width:20%" align="center">
<td>
RECIBI DE CONFORMIDAD <br><br><br><br><br>
</td>
</tr>
<tr style="width:20%" align="center">
<td>
___________________________________ <br><br>
<?php  echo $empleado ?>
</td>
</tr>
</table>

</body>
</html>
