<?php
header("Content-Type: text/html; charset=utf-8");
class Graficas extends Model {
	//var $id='';
	function Graficas(){
		parent::Model();
	}
	function ventas_globales($valores, $graf_labels, $title){
		$this->load->plugin('jpgraph');
		// Setup Chart
		$ydata = $valores; // this should come from the model
		$tickLabels=$graf_labels;
		$graph = pastelchart($ydata, $title, $tickLabels);  // add more parameters to plugin function as required

		// File locations
		$graph_temp_directory = 'tmp';  // in the webroot (add directory to .htaccess exclude)
		$graph_file_name = 'ventas_globales1.jpeg';
		$graph_file_location = $graph_temp_directory . '/' . $graph_file_name;
		$graph->Stroke('./'.$graph_file_location);  // create the graph and write to file
		$data['graph1'] = $graph_file_location;


	} function ventas_familias($datosy, $leyendas, $title){
		$this->load->plugin('jpgraph');
		$graph = grafica_lineas($datosy, $leyendas, utf8_decode($title));  // add more parameters to plugin function as required
		// File locations
		$graph_temp_directory = 'tmp';  // in the webroot (add directory to .htaccess exclude)
		$graph_file_name = 'ventas_familias.jpeg';
		$graph_file_location = $graph_temp_directory . '/' . $graph_file_name;
		$graph->Stroke('./'.$graph_file_location);  // create the graph and write to file
		$data['graph1'] = $graph_file_location;


	}

	function audiencias($graf_hom, $graf_muj, $graf_total, $graf_labels){
		$this->load->plugin('jpgraph');
		//Construir la gráfica
		$title = "Audiencias Totales del Periodo";
		// Setup Chart
		$ydata = $graf_total; // this should come from the model
		$tickLabels=$graf_labels;
		$graph = pastelchart($ydata, $title, $tickLabels);  // add more parameters to plugin function as required

		// File locations
		$graph_temp_directory = 'tmp';  // in the webroot (add directory to .htaccess exclude)
		$graph_file_name = 'a1.jpeg';
		$graph_file_location = $graph_temp_directory . '/' . $graph_file_name;
		$graph->Stroke('./'.$graph_file_location);  // create the graph and write to file
		$data['graph'] = $graph_file_location;


		$title = "Audiencias a Hombres";
		// Setup Chart
		$ydata = $graf_hom; // this should come from the model
		$tickLabels=$graf_labels;
		$graph = pastelchart($ydata, $title, $tickLabels);  // add more parameters to plugin function as required

		// File locations
		$graph_temp_directory = 'tmp';  // in the webroot (add directory to .htaccess exclude)
		$graph_file_name = 'a_hom.jpeg';
		$graph_file_location = $graph_temp_directory . '/' . $graph_file_name;
		$graph->Stroke('./'.$graph_file_location);  // create the graph and write to file
		$data['graph'] = $graph_file_location;

		$title = "Audiencias a Mujeres";
		// Setup Chart
		$ydata = $graf_muj; // this should come from the model
		$tickLabels=$graf_labels;
		$graph = pastelchart($ydata, $title, $tickLabels);  // add more parameters to plugin function as required

		// File locations
		$graph_temp_directory = 'tmp';  // in the webroot (add directory to .htaccess exclude)
		$graph_file_name = 'a_muj.jpeg';
		$graph_file_location = $graph_temp_directory . '/' . $graph_file_name;
		$graph->Stroke('./'.$graph_file_location);  // create the graph and write to file
		$data['graph'] = $graph_file_location;

	}

	//Se agregaron las siguentes dos funciones para el manejo del
	//archivo grafico. La funcion @unlink puede quitarse en el servidor
	function tickets_hora($valores, $graf_labels, $title) {
		$this->load->plugin('jpgraph');
		// Setup Chart
		$ydata = $valores; // this should come from the model
		$tickLabels=$graf_labels;
		$graph = barchart($ydata, $title, $tickLabels);  // add more parameters to plugin function as required

		// File locations
		$graph_temp_directory = 'tmp';  // in the webroot (add directory to .htaccess exclude)
		$graph_file_name = 'tickets_horas1.jpeg';
		$graph_file_location = $graph_temp_directory . '/' . $graph_file_name;
		//@unlink('./'.$graph_file_location);
		$graph->Stroke('./'.$graph_file_location);  // create the graph and write to file
		$data['graph1'] = $graph_file_location;
	}

	function tickets_dia($valores, $graf_labels, $title) {
		$this->load->plugin('jpgraph');
		// Setup Chart
		$ydata = $valores; // this should come from the model
		$tickLabels=$graf_labels;
		$graph = barchart($ydata, $title, $tickLabels);  // add more parameters to plugin function as required

		// File locations
		$graph_temp_directory = 'tmp';  // in the webroot (add directory to .htaccess exclude)
		$graph_file_name = 'tickets_dias1.jpeg';
		$graph_file_location = $graph_temp_directory . '/' . $graph_file_name;
		//@unlink('./'.$graph_file_location);
		$graph->Stroke('./'.$graph_file_location);  // create the graph and write to file
		$data['graph1'] = $graph_file_location;
	}

	function gastos_globales($valores, $graf_labels, $title){
		$this->load->plugin('jpgraph');
		// Setup Chart
		$ydata = $valores; // this should come from the model
		$tickLabels=$graf_labels;
		$graph = pastelchart($ydata, $title, $tickLabels);  // add more parameters to plugin function as required

		// File locations
		$graph_temp_directory = 'tmp';  // in the webroot (add directory to .htaccess exclude)
		$graph_file_name = 'gastos_globales1.jpeg';
		$graph_file_location = $graph_temp_directory . '/' . $graph_file_name;
		$graph->Stroke('./'.$graph_file_location);  // create the graph and write to file
		$data['graph1'] = $graph_file_location;
	}

	function rentabilidad($valores, $graf_labels, $title){
		$this->load->plugin('jpgraph');
		// Setup Chart
		$ydata = $valores; // this should come from the model
		$tickLabels=$graf_labels;
		$graph = pastelchart($ydata, $title, $tickLabels);  // add more parameters to plugin function as required

		// File locations
		$graph_temp_directory = 'tmp';  // in the webroot (add directory to .htaccess exclude)
		$graph_file_name = 'rentabilidad.jpeg';
		$graph_file_location = $graph_temp_directory . '/' . $graph_file_name;
		$graph->Stroke('./'.$graph_file_location);  // create the graph and write to file
		$data['graph1'] = $graph_file_location;
	}

	function comparativo($valores, $graf_labels, $title, $nombre){
		$this->load->plugin('jpgraph');
		$ydata = $valores; // this should come from the model
		$tickLabels=$graf_labels;
		$graph = barchart($ydata, $title, $tickLabels);
		// File locations
		$graph_temp_directory = 'tmp';  // in the webroot (add directory to .htaccess exclude)
		$graph_file_name = $nombre;
		$graph_file_location = $graph_temp_directory . '/' . $graph_file_name;
		$graph->Stroke('./'.$graph_file_location);  // create the graph and write to file
		$data['graph1'] = $graph_file_location;

	}

}
?>
