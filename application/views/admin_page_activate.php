			<div id="page-wrapper">
				<div class="graphs">
					<h3 class="blank1">Activación de cliente</h3>
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
								  <th>Nombre de la web</th>
								</tr>
							  </thead>
							  <tbody>
								<tr class="warning">
								  <th scope="row"><?=$data_user->id?></th>
								  <td><?=$data_user->username?></td>
								  <td><?=$data_user->email?></td>
								  <td><?=$data_user->customer?></td>
								</tr>

								</tbody>
							</table>
						</div>



					</div>


				</div>
			</div>

			<!-- Formulario de activacion -->
			<div class="tab-content">
			<div class="tab-pane active" id="horizontal-form">
				<form class="form-horizontal" action="<?=base_url()?>index.php/admin_controller/activate_user" method="POST">
					<div class="form-group">
						<label for="focusedinput" class="col-sm-2 control-label">Base de datos</label>
						<div class="col-sm-8">
							<input type="text" name="database_name" class="form-control1" id="focusedinput" placeholder="Nombre de la base de datos (máximo 15 caracteres)">
							<input type="hidden" name="id" value="<?=$data_user->id?>">
						</div>
						<div class="col-sm-2 jlkdfj1">
							<p class="help-block"><input type="submit" class="btn-success btn" value="Activar"></p>
						</div>
					</div>

				</form>
			</div>
			</div>

		</div>
		<!--footer section start in controller-->



		
	</body>
</html>