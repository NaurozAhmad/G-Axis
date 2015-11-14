<!DOCTYPE html>
<html>
<head>
	<title>Global Axis International Co.</title>
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="node_modules/font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="css/custom.css">
</head>
<body>
	<?php require_once('includes/header.php') ?>
	<div class="slider">
		<?php require_once('includes/slider.php'); ?>
	</div>
	<div class="g-nav">
		<?php require_once('includes/navbar.php'); ?>
	</div>
	<div class="content">
		<div class="g-container">
			<?php require_once('includes/lights-gallery.php') ?>
		</div>
		<div class="about">
			<div class="g-container">
				<div class="row">
					<h2>ABOUT US</h2>
				</div>
				<div class="row">
					<div class="about-main-container col-md-5">
						<img src="images/slider/slider-1.jpg" alt="">
						<div class="about-main-text" style="padding-top: 38px">An Oman based well-known manufacturing company since 2007, having clientele/vendors in all major countries of the world. Global Axis is a company specialized in offering turnkey solutions for all kinds of LED lighting & Renewable Energy solutions. It also deals with Road and Safety signs...</div>
						<a href="about.php" class="btn btn-primary">Read More</a>
					</div>
					<div class="about-main-container col-md-5 col-md-offset-2">
						<img src="images/slider/slider-2.jpg" alt="">
						<div class="about-main-text"  style="padding-top: 5px;">
						<h4 style="color: blue">Why Global Axis?</h4>
						We assure the product's quality by conducting exttensive research and development activites before introducing the products in the market followed by a feedback system that alows us to come up with improved and innovative lights. G-AXIS lights are manufactured using various kinds of...</div>
						<a href="about.php" class="btn btn-primary">Read More</a>
					</div>
				</div>
			</div>
		</div>
		<div class="clients">
			<h3>OUR CORPORATE CLIENTS</h3>
			<div>
				
			</div>
			<div style="max-width: 800px; margin: 0 auto;">
				<img style="width: 100%; opacity: 0.4;" src="images/conveyer.png" alt="">
			</div>
		</div>
	</div>
	<?php require_once('includes/footer.php') ?>
	<script src="node_modules/jquery/dist/jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
</body>
</html>