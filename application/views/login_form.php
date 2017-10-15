<!DOCTYPE HTML>
<html>
	<?php
	if (isset($this->session->userdata['logged_in'])) {
		header("location: ".base_url()."index.php/login/user_login_process");
	}
?>
	<head>
		<title>Login Form</title>
	</head>
	

 <body class="sign-in-up">
    <section>
			<div id="page-wrapper" class="sign-in-wrapper">
				<div class="graphs">
					<div class="sign-in-form">
						<div class="sign-in-form-top">
							<?php
							if (isset($logout_message)) {
								echo "<p><span>". $logout_message ."</span></p>";
							}else if (isset($message_display)) {
								echo "<p><span>". $message_display ."</span></p>";
							}else{
								echo "<p><span>Iniciar sesión</span></p>";
							}

							?>
						</div>
						<div class="signin">
							<div class="signin-rit">
								<?php echo form_open('login/user_login_process'); ?>
								<?php
									if (isset($error_message)) {
										echo $error_message;
									}
									echo validation_errors();
								?>
								<p><a href="<?php echo base_url() ?>index.php/login/forgot_form">Olvidé mi contraseña</a> </p>
								<div class="clearfix"> </div>
							</div>
							<div class="log-input">
								<div class="log-input-left">
								   <input type="text" class="user" id="name" name="username" value="Nombre de usuario:" 
								   onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Nombre de usuario:';}"/>
								</div>
								<div class="clearfix"> </div>
							</div>
							<div class="log-input">
								<div class="log-input-left">
								   <input type="password" class="lock" name="password" type="password" value="password" 
								   onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'password';}"/>
								</div>
								<div class="clearfix"> </div>
							</div>
							<input type="submit" value="Entrar" name="submit">
							<?php echo form_close(); ?>
						</div>
						<div class="new_people">
							<h4>Inscripción</h4>
							<p>Inscríbete y nos pondremos en contacto con usted para enviarle la información correspondiente.</p>
							<a href="<?php echo base_url() ?>index.php/login/registration">¡Regístrate aquí!</a>
						</div>
					</div>
				</div>
			</div>
		<!--footer section start in controller-->
