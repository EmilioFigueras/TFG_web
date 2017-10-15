			<script>
			var el = document.getElementById('AreYouSure');

			el.addEventListener('submit', function(){
			    return confirm('Are you sure you want to submit this form?');
			}, false);
			</script>

			<div id="page-wrapper">
				<div class="graphs">
					<h3 class="blank1">Informes</h3>
					<?php
					if(!empty($msg_db)){
						echo "<div class='alert alert-danger' role='alert'>".$msg_db."</div>";
					}
					if(!empty($msg_ok)){
						echo "<div class='alert alert-success' role='alert'>".$msg_ok."</div>";
					} 

					?>
					<div class="xs tabls">
						<div class="bs-example4" data-example-id="contextual-table">
						<table class="table">
						  <thead>
							<tr>
							  <th>ID</th>
							  <th>Título</th>
							  <th>Descripcion</th>
							  <th>Tipo</th>
							  <th>Activo</th>
							  <th>Editar</th>
							  <th>Ver</th>
							  <th>Eliminar</th>
							</tr>
						  </thead>
						  <tbody>

					<?php 
						foreach ($all_reports as $key => $value) { 
							if($value->active == 0)
							?>
							<tr>
							  <th scope="row"><?=$value->autoid?></th>
							  <td><?=$value->title?></td>
							  <td><?=$value->description?></td>
							  <td><?=$value->type?></td>
							  <td><?=$value->active?></td>
							  <td><a href='<?=base_url()?>index.php/admincustomer_controller/edit_report_form/<?=$value->autoid?>'>Editar</a></td>
							  <td><a href='<?=base_url()?>index.php/admincustomer_controller/show_report/<?=$value->autoid?>' target="_blank">Ver</a></td>
							  <td><a href='<?=base_url()?>index.php/admincustomer_controller/delete_report/<?=$value->autoid?>' onclick="return confirm('¿Estás seguro de que deseas eliminarlo?');">Eliminar</a></td>

							</tr>
							


					<?php
						}
					?>
							</tbody>
						</table>
					</div>


				</div>
			</div>

			<!-- Crear nuevo informe -->
			<br><br>
			<a class="btn-success btn" href='<?=base_url()?>index.php/admincustomer_controller/new_report_form'>Crear nuevo informe</a>



		</div>
		<!--footer section start in controller-->