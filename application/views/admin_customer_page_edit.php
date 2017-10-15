			<div id="page-wrapper">
				<div class="graphs">
					<h3 class="blank1">Edición de usuario</h3>
					<div class="xs tabls">
						<div class="panel panel-warning" data-widget="{&quot;draggable&quot;: &quot;false&quot;}" data-widget-static="">
							<div class="panel-heading">
								<h2>Cliente seleccionado</h2>
								<div class="panel-ctrls" data-actions-container="" data-action-collapse="{&quot;target&quot;: &quot;.panel-body&quot;}"><span class="button-icon has-bg"><i class="ti ti-angle-down"></i></span></div>
							</div>
							<div class="panel-body no-padding" style="display: block;">
							<table class="table table-striped">
							  <thead>
								<tr class="warning">
								  <th>ID</th>
								  <th>Nombre de usuario</th>
								  <th>Email</th>
								  <th>Rol</th>
								  <th>Base de datos</th>
								</tr>
							  </thead>
							  <tbody>
								<tr class="warning">
								  <th scope="row"><?=$data_user->id?></th>
								  <td><?=$data_user->username?></td>
								  <td><?=$data_user->email?></td>
								  <td><?=$data_user->rol?></td>
								  <td><?=$data_user->name_db?></td>
								</tr>

								</tbody>
							</table>
						</div>



					</div>


				</div>
			</div>


			<!-- Formulario de nuevo rol -->
			<div class="tab-content">
			<div class="tab-pane active" id="horizontal-form">
				<form class="form-horizontal" action="<?=base_url()?>index.php/admincustomer_controller/edit_role" method="POST">
					<div class="form-group">
						<label for="focusedinput" class="col-sm-2 control-label">Nuevo rol</label>
						<div class="col-sm-8">
							<select name="role" id="role" class="form-control1">
										<option value="2">Administrador de clientes</option>
										<option value="3">Cliente</option>
									</select>
							<input type="hidden" name="id" value="<?=$data_user->id?>">
						</div>
						<div class="col-sm-2 jlkdfj1">
							<p class="help-block"><input type="submit" class="btn-success btn" value="Enviar"></p>
						</div>
					</div>

				</form>
			</div>
			</div>


			<!-- Formulario de nuevo password -->
			<div class="tab-content">
			<div class="tab-pane active" id="horizontal-form">
				<form class="form-horizontal" action="<?=base_url()?>index.php/admincustomer_controller/edit_password" method="POST">
					<div class="form-group">
						<label for="focusedinput" class="col-sm-2 control-label">Cambiar contraseña</label>
						<div class="col-sm-8">
							<input type="password" name="password" class="form-control1" id="focusedinput" placeholder="Nueva contraseña">
							<input type="hidden" name="id" value="<?=$data_user->id?>">
						</div>
						<div class="col-sm-2 jlkdfj1">
							<p class="help-block"><input type="submit" class="btn-success btn" value="Enviar"></p>
						</div>
					</div>

				</form>
			</div>
			</div>

			<!-- Desactivar usuario -->
			<div class="tab-content">
			<div class="tab-pane active" id="horizontal-form">
				<form class="form-horizontal" action="<?=base_url()?>index.php/admincustomer_controller/delete_user" method="POST">
					<div class="form-group">
						<label for="focusedinput" class="col-sm-2 control-label">Eliminar usuario: </label>
						<div class="col-sm-8">
							<input type="hidden" name="id" value="<?=$data_user->id?>">
							<p class="help-block"><input type="submit" onclick="return confirm('El usuario se eliminará, ¿estás seguro?');" class="btn-success btn" value="Eliminar"></p>
						</div>
					</div>

				</form>
			</div>
			</div>

		</div>
		<!--footer section start in controller-->



		
	</body>
</html>