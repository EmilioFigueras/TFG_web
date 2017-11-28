<div id="page-wrapper">
	<div class="graphs">
		<h3 class="blank1">Nuevo informe</h3>
		<?php
			if(!empty($msg_db)){
				echo "<div class='alert alert-danger' role='alert'>".$msg_db."</div>";
			}
			if(!empty($msg_ok)){
				echo "<div class='alert alert-success' role='alert'>".$msg_ok."</div>";
			} 

		?>
			<div class='alert alert-success' role='alert'>INFORMACIÓN: Existen tres tipos de gráficos, que son, el diagrama de barras, 
				el gráfico circular de tipo pastel y el gráfico de lineas. Se debe introducir una consulta a la base de datos cuyos resultados,
				en todos los casos, deben ser valores numéricos. Los nombres de las columnas identificará el eje X y los valores numéricos el eje Y.
				A continuación le ofrecemos la información que su base de datos almacena y con la que puede jugar para obtener diferentes
				gráficas.</div>
			<h3 class="blank1">Productos (el nombre de la tabla es 'products'): </h3>
			<ul>
			<?php
			foreach ($structure['products'] as $key => $value) { 
				?>
				<li><?=$value?></li>
			<?php } ?>
			</ul>
			<br>
			<h3 class="blank1">Comentarios (el nombre de la tabla es 'comments'): </h3>
			<ul>
			<?php
				foreach ($structure['comments'] as $key => $value) { 
				?>
					<li><?=$value?></li>
				<?php } ?>
			</ul>
			<br>
			<h3 class="blank1">Análisis (el nombre de la tabla es 'analysis'): </h3>
			<ul>
				<li>personalRating ('Sí' o 'No') </li>
			</ul>

			<br>
			<h3 class="blank1">Editar informe: </h3>

			<div class="tab-content">
			<div class="tab-pane active" id="horizontal-form">
				<form class="form-horizontal" action="<?=base_url()?>index.php/admincustomer_controller/edit_report/<?=$data_report[0]->autoid?>" method="POST">
					<!-- Titulo -->
					<div class="form-group">
						<label for="focusedinput" class="col-sm-2 control-label">Título</label>
						<div class="col-sm-8">
							<input type="text" name="title_report" value="<?=$data_report[0]->title?>" class="form-control1" id="focusedinput" placeholder="Título del informe...">
						</div>
					</div>

					<!-- Descripcion -->
					<div class="form-group">
						<label for="txtarea1" class="col-sm-2 control-label">Descripcion</label>
						<div class="col-sm-8">
							<textarea name="description_report" cols="50" rows="4" id="txtarea1" placeholder="Escriba aquí una descripción de la consulta realizada..."><?=$data_report[0]->description?></textarea>
						</div>
					</div>

					<!-- Tipo -->
					<div class="form-group">
						<label for="selector1" class="col-sm-2 control-label">Tipo de gráfico</label>
						<div class="col-sm-8">
						<select name="type_report" id="selector1" class="form-control1">
							<option value="bar" <?php if($data_report[0]->type == "bar") echo "selected"; ?> >Barras</option>
							<option value="line" <?php if($data_report[0]->type == "line") echo "selected"; ?>>Líneas</option>
							<option value="pie" <?php if($data_report[0]->type == "pie") echo "selected"; ?>>Pastel</option>
						</select></div>
					</div>

					<!-- Activar/Desactivar -->
					<div class="form-group">
						<label for="checkbox" class="col-sm-2 control-label">Activar</label>
						<div class="col-sm-8">
							<div class="checkbox-inline1"><label><input type="checkbox" name="active_report" <?php if($data_report[0]->active == 1) echo "checked"; ?>> Activar/Desactivar visualización</label></div>
						</div>
					</div>

					<!-- Consulta -->
					<h3 class="blank1">Consulta </h3>
					<div class='alert alert-success' role='alert'>IMPORTANTE: Las tres tablas de las que se pueden sacar
						datos (productos, comentarios y análisis) ya han sido agregadas, por ello ES IMPORTANTE USAR LOS ALIAS asignados
						a la hora de trabajar con los atributos en el select, estos son:
						<br>
						<ul>	
							<li>Productos: p</li>
							<li>Comentarios: c</li>
							<li>Análisis: a</li>
						</ul>
						Es decir, no hay que agregar ni 'from' ni 'join' a la consulta. Solo el 'select', el 'where', 'group by' y 'order by'.
						<br><br>
						MUY IMPORTANTE: El 'select' puede ser de dos formas:<br>
						<ul>
							<li>Qué contenga dos columna, una llamada 'label' y otra llamada 'data'. 
								En 'label' será el nombre de las columnas del informe final y en 'data' los valores 
								numéricos a comparar. Utilice alias para conseguir los nombres correctos ('label'
								y 'data'). </li>
							<li>Qué los resultados solo sean datos numéricos, en ese caso los nombres de las columnas
								del informe final serán los nombres de cada columna de la query. </li>

						</div>


					<div class="form-group has-warning">
				        <label for="selector1">
				        	SELECT
							<textarea cols="50" rows="4" name="query_report_select"><?=$data_report[0]->query_select?></textarea>
							<br>
							WHERE
							<textarea cols="50" rows="2" name="query_report_where"><?=$data_report[0]->query_where?></textarea>
				        </label>
				    </div>

				    <div class="form-group has-warning">
				        <label for="selector1">
				        	<input type="checkbox" name="query_report_groupby_active" <?php if(!empty($data_report[0]->query_groupby)) echo "checked";?>></input>
				        	GROUP BY
							<input type="text" name="query_report_groupby" value="<?=$data_report[0]->query_groupby?>"></input>
				        </label>
				    </div>

				     <div class="form-group has-warning">
				        <label for="selector1">
				        	<input type="checkbox" name="query_report_orderby_active" <?php if(!empty($data_report[0]->query_orderby)) echo "checked";?>></input>
				        	ORDER BY
							<input type="text" name="query_report_orderby" value="<?=$data_report[0]->query_orderby?>"></input>
				        </label>
				    </div>

					<div class="form-group">
						<!-- Enviar -->
						<div class="col-sm-2 jlkdfj1">
							<p class="help-block"><input type="submit" class="btn-success btn" value="Enviar"></p>
						</div>
					</div>

				</form>
			</div>
			</div>


		</div>
	</div>