			<div id="page-wrapper">
				<div class="graphs">
					<h3 class="blank1">Edición de usuario</h3>
					<?php
					if(!empty($msg_db)){
						echo "<div class='alert alert-danger' role='alert'>".$msg_db."</div>";
					}
					if(!empty($msg_ok)){
						echo "<div class='alert alert-success' role='alert'>".$msg_ok."</div>";
					} 

					?>
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
								  <th>Nombre de usuario</th>
								  <th>Email</th>
								  <th>Rol</th>
								  <th>Base de datos</th>
								</tr>
							  </thead>
							  <tbody>
								<tr class="warning">
								  <td><?=$data_user['username']?></td>
								  <td><?=$data_user['email']?></td>
								  <td><?=$data_user['role']?></td>
								  <td><?=$data_user['name_db']?></td>
								</tr>

								</tbody>
							</table>
						</div>



					</div>


				</div>
			</div>


			<!-- Formulario de nuevo password -->
			<div class="tab-content">
			<div class="tab-pane active" id="horizontal-form">
				<form class="form-horizontal" action="<?=base_url()?>index.php/login/edit_password" method="POST">
					<div class="form-group">
						<label for="focusedinput" class="col-sm-2 control-label">Cambiar contraseña</label>
						<div class="col-sm-8">
							<input type="password" name="password" class="form-control1" id="focusedinput" placeholder="Nueva contraseña">
							<input type="password" name="password2" class="form-control1" id="focusedinput" placeholder="Escribe de nuevo la contraseña">
							<input type="hidden" name="id" value="<?=$data_user['id']?>">
						</div>
						<div class="col-sm-2 jlkdfj1">
							<p class="help-block"><input type="submit" class="btn-success btn" value="Enviar"></p>
						</div>
					</div>

				</form>
			</div>
			</div>

		

		</div>
		<!--footer section start in controller-->



		
	</body>
</html>