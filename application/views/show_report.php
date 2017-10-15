<?php
	/*
	results => Resultados de la query
	title => Titulo del informe
	type => Tipo de grafica. 
	*/
	$report_data = ""; //Datos numericos
	$report_label = ""; //Nombre de las columnas (etiquetas)
	$i=1;
	$colorArray = array("#4de484", "#4de4d0", "#4dade4", "#4d61e4", "#d1d69d", "#d04de4", "#e4a74d", "#d6e44d",
		"#8ae44d", "#4de45b", "#4de4a7", "#4dd6e4", "#d69dd1", "#d69db4", "#d6a29d", "#b5d69d"); //16 elementos
	$label_index = array();
	foreach ($results as $key => $value) { //En results estan los resultados de la query
		foreach ($value as $key2 => $value2) {
			if(strpos(strtolower($key2), "data") !== false){ //Son datos numericos
				if(empty($report_data)){
					$report_data .= $value2;
				}else{
					$report_data .= ', ' . $value2;

				}
			}else if(strpos(strtolower($key2), "label") !== false){ //Son los labels
				if(empty($report_label)){
					$report_label .= '"' . $i . '"';
				}else{
					$report_label .= ', ' . '"' . $i . '"';

				}
				$label_index[$i] = $value2;

				$i++;
			}else{ //Los labels son los nombres de la columna y los datos numericos su valor
				if(empty($report_label)){
					$report_label .= '"' . $i . '"';
				}else{
					$report_label .= ', ' . '"' . $i . '"';

				}
				if(empty($report_data)){
					$report_data .= $value2;
				}else{
					$report_data .= ', ' . $value2;

				}
				$label_index[$i] = $key2;

				$i++;
			}

		}
	}

?>
<div id="page-wrapper">
	<div class="graphs">
		<h3 class="blank1">Ver informe</h3>
		<div class="graph_box">
			<div class="line-bottom-grid">
				<div class="grid_1">
					<h4><?=$title?></h4>
					<canvas id="canvas"></canvas>
					<?php
					switch ($type) {
						case 'bar':

					?>
					<script> //Script para grafico de barras
						var barChartData = {
							labels : [<?=$report_label?>],
							datasets : [
								{
									fillColor : ["<?=$colorArray[rand(0,16)%16]?>"],
									strokeColor : ["#00033e"],
									//strokeColor : ["<?=$colorArray[rand(0,16)%16]?>"],
									data : [<?=$report_data?>]
								}
							]
							
						};
						var myBarChart = new Chart(document.getElementById("canvas").getContext("2d")).Bar(barChartData, 
							{	responsive: true,
								maintainAspectRatio: false,
								scaleFontSize : 13, 
								scaleFontColor : "#1E5B17",

							});
					</script>

					<?php 
							break;
						case 'pie':
						$data_pie = explode(',', $report_data);
						$label_pie = explode(',', $report_label);

					?>
					<script> //Script para grafico de pastel
						var pieChartData = [
						<?php
							foreach ($data_pie as $key => $value) {
						?>
						{value: <?=trim($value)?>,color:"<?=$colorArray[$key%16]?>",highlight:"<?=$colorArray[$key%16]?>",label: <?=str_replace('"', '', trim($label_pie[$key]))?>}
						<?php
							if((count($data_pie)-1) != $key){
						?>
						,
						<?php
							}
						?>

						<?php 
							} //Fin foreach
						?>
						];
						
						var myPieChart = new Chart(document.getElementById("canvas").getContext("2d")).Pie(pieChartData, 
							{	responsive: true,
								maintainAspectRatio: false,
								scaleFontSize : 13, 
								scaleFontColor : "#1E5B17",

							});
					</script>

					<?php 
							break;
						case 'line':
					?>
					<script> //Script para grafico lineal
						var lineChartData = {
							labels : [<?=$report_label?>],
							datasets : [
								{
									fillColor : ["<?=$colorArray[rand(0,16)%16]?>"],
									strokeColor : ["#00033e"],
									//strokeColor : ["<?=$colorArray[rand(0,16)%16]?>"],
									data : [<?=$report_data?>]
								}
							]
							
						};
						var myLineChart = new Chart(document.getElementById("canvas").getContext("2d")).Line(lineChartData, 
							{	responsive: true,
								maintainAspectRatio: false,
								scaleFontSize : 13, 
								scaleFontColor : "#1E5B17",

							});
					</script>

					<?php
							break;
						}
					?>




				</div>
			</div>
		</div>

			<br>
			<h3 class="blank1">Relaciones: </h3>
			<ol type="1">
			<?php
				foreach ($label_index as $key => $value) { 
				?>
					<li><?=$value?></li>
				<?php } ?>
			</ol>
			<br>
	</div>
</div>
