<!DOCTYPE html>
<html>
<head>
	<title>Global Axis International Co.</title>
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="node_modules/font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="css/custom.css">
</head>
<body>
	<?php require_once('includes/header.html') ?>
	<div class="slider">
		<div class="banner" style="background-image: url(images/slider/slider-2.jpg)">
			<?php require_once('includes/news.html') ?>
		</div>
	</div>
	<div class="g-nav">
		<?php require_once('includes/navbar.html'); ?>
	</div>
	<div class="content">
		<div class="g-container">
			<div class="row">
				<div class="col-md-6">
					<h3>ENQUIRY</h3>
					<form>
						<div class="form-group">
							<input type="text" class="form-control" id="exampleInputEmail1" placeholder="Name">
						</div>
						<div class="form-group">
							<input type="email" class="form-control" id="exampleInputEmail1" placeholder="Email">
						</div>
						<div class="form-group">
							<input type="text" class="form-control" id="exampleInputEmail1" placeholder="Contact Number">
						</div>
						<div class="form-group">
							<input type="text" class="form-control" id="exampleInputEmail1" placeholder="Subject">
						</div>
						<div class="form-group">
							<textarea class="form-control" rows="3" placeholder="Your Message"></textarea>
						</div>
						<button type="submit" class="btn btn-primary btn-pointy btn-lg" style="float: right;">Submit</button>
					</form>
				</div>
				<div class="col-md-6" style="height: 420px">
					<div class="row" style="display: flex; align-items: center; justify-content: center; height: 100%">
						<div class="col-sm-6">
							<img src="images/stamp.png" style="width: 200px; float: right;" alt="">
						</div>
						<div class="col-sm-6">
							<h3>Message Delivered</h3>
							<p>Dear Mr. Sender</p>
							<p>Thank you for your enquiry. One of our sales staff will be in touch with you as soon as possible.</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php require_once('includes/footer.html') ?>

	<script src="node_modules/jquery/dist/jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
</body>
</html>