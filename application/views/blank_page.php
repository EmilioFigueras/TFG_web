<head>
<title>Error 404 - Page Not Found</title>
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

		
			<!--notification menu end -->
			</div>
	<!-- //header-ends -->
			<div id="page-wrapper">
				<div class="graphs">
					<div class="error-main">
						<h3><i class="fa fa-exclamation-triangle"></i> <span>404</span></h3>
					<div class="col-xs-7 error-main-left">
						<span>:(</span>
						<p>Parece que estás tomando el camino equivocado...</p>
						<div class="error-btn">
							<a href="<?=base_url()?>index.php/login">Sigue por aquí</a>
						</div>
					</div>
					<div class="col-xs-5 error-main-right">
						<img src="<?php echo base_url(); ?>assets/images/7.png" alt=" " class="img-responsive" />
					</div>
					<div class="clearfix"> </div>
				</div>
			</div>
		</div>
		<!--footer section start in controller-->
