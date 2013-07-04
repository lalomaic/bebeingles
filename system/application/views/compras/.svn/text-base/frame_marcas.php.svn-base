<?="<h2>$title</h2>"?>
<br />
<div style="border-bottom: 1px solid blue; color: blue;">
	<?=$proveedor->id?>
	.-
	<?=$proveedor->razon_social?>
</div>
<div style="margin-top: 20px; background-color: #ccc;">
	<?php
	if($marcas!=false){
		echo "<div style='text-align:center;background-color:#fff;'>Total ".count($marcas->all)."</div>";
		foreach($marcas->all as $row){
			echo "<li>$row->id .- $row->tag</li>";
		}
	} else
		echo "<li>Sin marcas asociadas</li>";
	?>
</div>
