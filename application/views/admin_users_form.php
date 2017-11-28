

			<div id="page-wrapper">
				<div class="graphs">
					<h3 class="blank1">Usuarios activos</h3>
					<div class="xs tabls">
						<div class="bs-example4 table-responsive" data-example-id="contextual-table">
						<table class="table">
						  <thead>
							<tr>
							  <th>ID</th>
							  <th>Nombre de usuario</th>
							  <th>Email</th>
							  <th>Nombre de la web</th>
							  <th>Rol</th>
							  <th>Editar</th>
							</tr>
						  </thead>
						  <tbody>

					<?php 
						foreach ($all_active_users as $key => $value) { 
							if($value->active == 0)
							?>
							<tr>
							  <th scope="row"><?=$value->id?></th>
							  <td><?=$value->username?></td>
							  <td><?=$value->email?></td>
							  <td><?=$value->customer?></td>
							  <td><?=$value->rol?></td>
							  <td><a href='<?=base_url()?>index.php/admin_controller/edit_form/<?=$value->id?>'>Editar</a></td>
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