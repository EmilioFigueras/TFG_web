<?php
	if (isset($this->session->userdata['logged_in']) && $this->session->userdata['logged_in']['role'] == 'admin') {
		$username = ($this->session->userdata['logged_in']['username']);
		$email = ($this->session->userdata['logged_in']['email']);
	} else {
		header("location: login");
	}
	?>
	<head>
	<title>Admin Page</title>
	</head>
	<body class="sticky-header left-side-collapsed"  onload="initMap()">
    <section>
    <!-- left side start-->
		<div class="left-side sticky-left-side">

			<!--logo and iconic logo start-->
			<div class="logo">
				<h1><a href="<?=base_url()?>index.php/login">Easy <span>Admin</span></a></h1>
			</div>
			<div class="logo-icon text-center">
				<a href="<?=base_url()?>index.php/login"><i class="lnr lnr-home"></i> </a>
			</div>

			<!--logo and iconic logo end-->
			<div class="left-side-inner">

				<!--sidebar nav start-->
					<ul class="nav nav-pills nav-stacked custom-nav">
						<li><a href="<?=base_url()?>index.php/login"><i class="lnr lnr-power-switch"></i><span>Cuadro de mando</span></a></li>
						<li><a href="<?=base_url()?>index.php/admin_controller/admin_registration"><i class="lnr lnr-pencil"></i> <span>Registrar administrador</span></a></li>
						<li><a href="<?=base_url()?>index.php/admin_controller/show_users"><i class="lnr lnr-select"></i> <span>Ver usuarios</span></a></li>
					</ul>

				<!--sidebar nav end-->
			</div>
		</div>
    <!-- left side end-->


       <!-- main content start-->
		<div class="main-content main-content2 main-content2copy">
			<!-- header-starts -->
			<div class="header-section">
			 
			<!--toggle button start-->
			<a class="toggle-btn  menu-collapsed"><i class="fa fa-bars"></i></a>
			<!--toggle button end-->

			<div class="profile_details">		
					<ul>
						<li class="dropdown profile_details_drop">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
								<div class="profile_img">
									<?php echo "<span style='background:url(".base_url()."assets/images/default_user.png) no-repeat center'> </span> "; ?>
									 <div class="user-name">
										<p><?php echo $this->session->userdata['logged_in']['username']; ?><span><?php echo $this->session->userdata['logged_in']['role']; ?></span></p>
									 </div>
									 <i class="lnr lnr-chevron-down"></i>
									 <i class="lnr lnr-chevron-up"></i>
								</div>	
							</a>
							<ul class="dropdown-menu drp-mnu">
								<li> <a href="<?=base_url()?>index.php/login/settings"><i class="fa fa-cog"></i> Configuración</a> </li> 
								<li> <a href="<?=base_url()?>index.php/login/logout"><i class="fa fa-sign-out"></i> Cerrar sesión</a> </li>
							</ul>
						</li>
					</ul>
				</div>		
		
			<!--notification menu end -->
			</div>
	<!-- //header-ends -->