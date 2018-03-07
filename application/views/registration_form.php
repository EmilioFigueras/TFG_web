	<?php
	if (isset($this->session->userdata['logged_in'])) {
		header("location: ".base_url()."index.php/login/user_login_process");
	}
?>
		<title>Registro</title>
	</head>


 <body class="sign-in-up">
    <section>
			<div id="page-wrapper" class="sign-in-wrapper">
				<?php
					if(!empty($message_display)){
						echo "<div class='alert alert-success' role='alert'>".$message_display."</div>";
					}
					if(!empty($msg_db)){
						echo "<div class='alert alert-danger' role='alert'>".$msg_db."</div>";
					}

					?>
				<div class="graphs">
					<div class="sign-up">
						<?php echo form_open('login/new_user_registration'); ?>
						<h3>Formulario de registro</h3>
						<p class="creating">Registrate para solicitar la aplicación y comenzar a disfrutar de sus estadísticas e informes cuanto antes.</p>
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
						
						<h6>Sobre la página web</h6>
						<div class="sign-u">
							<div class="sign-up1">
								<h4>Nombre de la web* :</h4>
							</div>
							<div class="sign-up2">
								<?php echo form_input('customer'); ?>
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
		