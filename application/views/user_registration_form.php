			<div id="page-wrapper" class="sign-in-wrapper">
				<div class="graphs">
					<div class="sign-up">
						<?php echo form_open('admincustomer_controller/new_user_registration'); ?>
						<h3>Formulario de registro</h3>
						<?php
						if(!empty($msg_db)){
							echo "<div class='alert alert-danger' role='alert'>".$msg_db."</div>";
						}
						?>
						<p class="creating">Crear un nuevo usuario.</p>
						<h5>Datos del usuario</h5>
						<div class="sign-u">
							<div class="sign-up1">
								<h4>Nombre de usuario*:</h4>
							</div>
							<div class="sign-up2">
								<?php echo form_input('username'); ?>
							</div>
							<div class="clearfix"> </div>
						</div>
						<div class="sign-u">
							<div class="sign-up1">
								<h4>Correo electrónico* :</h4>
							</div>
							<div class="sign-up2">
								<?php 
									$data = array(
										'type' => 'email',
										'name' => 'email'
									);
									echo form_input('email');
								?>
							</div>
							<div class="clearfix"> </div>
						</div>
						<div class="sign-u">
							<div class="sign-up1">
								<h4>Contraseña* :</h4>
							</div>
							<div class="sign-up2">
								<?php echo form_password('password'); ?>
							</div>
							<div class="clearfix"> </div>
						</div>
						
						
						<div class="sub_home">
							<div class="sub_home_left">
								<?php echo form_submit('submit', 'Completar'); ?>
							</div>
							<div class="sub_home_right">
								<p>Volver al <a href="<?php echo base_url(); ?>">Inicio</a></p>
							</div>
							<div class="clearfix"> </div>
						</div>
						<?php echo form_close(); ?>
					</div>
				</div>



			</div>
		<!--footer section start in controller-->
