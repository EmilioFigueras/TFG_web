<div id="page-wrapper">
	<div class="graphs">
		<h3 class="blank1">Se va a reportar el informe <?=$report_sel[0]->autoid?> de título "<?=$report_sel[0]->title?>"</h3>
		<?php
			if(!empty($msg_db)){
				echo "<div class='alert alert-danger' role='alert'>".$msg_db."</div>";
			}
			if(!empty($msg_ok)){
				echo "<div class='alert alert-success' role='alert'>".$msg_ok."</div>";
			} 

		?>
			<div class='alert alert-success' role='alert'>INFORMACIÓN: Se va a enviar un correo electrónico a su administrador,
				indicando la información del informe seleccionado y agregando su comentario adicional describiendo el problema.
				No guardaremos ninguna información de este correo electrónico.</div>

			<div class="tab-content">
			<div class="tab-pane active" id="horizontal-form">
				<form class="form-horizontal" action="<?=base_url()?>index.php/usercustomer_controller/report_error" method="POST">
					

					<!-- Descripcion -->
					<div class="form-group">
						<label for="txtarea1" class="col-sm-2 control-label">Describe el problema</label>
						<div class="col-sm-8">
							<textarea name="description_report"  cols="50" rows="4" id="txtarea1" placeholder="Escriba aquí una descripción del problema..."></textarea>
						</div>
					</div>

					<input type="hidden" name="id_report" value="<?=$report_sel[0]->autoid?>">
					<input type="hidden" name="title_report" value="<?=$report_sel[0]->title?>">
					<input type="hidden" name="username_report" value="<?=$this->session->userdata()['logged_in']['username']?>">
					<input type="hidden" name="email_report" value="<?=$this->session->userdata()['logged_in']['email']?>">

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