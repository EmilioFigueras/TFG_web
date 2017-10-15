			<div id="page-wrapper">
				<div class="graphs">
					<h3 class="blank1">Informes</h3>
					<h5 class="blank1">Se presentan todos los informes en orden alfabético</h5>
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
							  <th>Ver</th>
							  <th>Notificar</th>
							</tr>
						  </thead>
						  <tbody>

					<?php 
						foreach ($all_reports as $key => $value) { 
							?>
							<tr>
							  <th scope="row"><?=$value->autoid?></th>
							  <td><?=$value->title?></td>
							  <td><?=$value->description?></td>
							  <td><?=$value->type?></td>
							  <td><a href='<?=base_url()?>index.php/usercustomer_controller/show_report/<?=$value->autoid?>' target="_blank">Ver</a></td>
							  <td><a href='<?=base_url()?>index.php/usercustomer_controller/report_error_form/<?=$value->autoid?>'>Reportar error</a></td>

							</tr>
							


					<?php
						}
					?>
							</tbody>
						</table>
					</div>


				</div>
			</div>

		</div>
		<!--footer section start in controller-->