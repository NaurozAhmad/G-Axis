<!DOCTYPE html>
<html>
<head>
	<title>Global Axis International Co.</title>
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="node_modules/font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="css/custom.css">
</head>
<body id="products">
	<?php require_once('includes/header.html') ?>
	<div class="slider">
		<?php require_once('includes/slider.html'); ?>
		<div class="news-bar">
			
		</div>
	</div>
	<div class="g-nav">
		<?php require_once('includes/navbar.html'); ?>
	</div>
	<div class="content">
		<div class="g-container">
			<div class="row">
				<h3>SELECTED ITEM MODELS</h3>
				<p>The exponential growth of LED technology in the lighting industry has helped develop lights that are getting cheaper by time and a quality that is being improved on regular basis. The constant development in this segment has lead to a large number of light design types being produced in mass to suit the day to day needs. This includes spotlights, candle lights, tube lights, down lights, strip lights, retrofit bulbs, facade, pool, outdoor & solar lights, all of which come in a large variety of fittings, colours, materials etc.</p>
			</div>
			
			<div class="row" style="margin-bottom: 100px;">
				<div class="col-md-3">
					<?php require_once('includes/categories-navigator.html') ?>
				</div>
				<div style="margin-top: 20px;" class="col-md-4">
					<img class="details-img" src="images/bulb.jpg" alt="">
					<div style="background-color:#FFF">
						<img class="details-img" src="images/bulb-details.gif" alt="">
					</div>
				</div>
				<div style="margin-top: 20px;" class="col-md-5">
					<div>
						<div class="details-heading">
							<h4 class="text-center" style="color: white; margin: 10px 0;">FEATURES</h4>
						</div>
						<div class="features">
							<table class="table">
								<tr>
									<td>LED Type</td>
									<td>SMD</td>
								</tr>
								<tr>
									<td>Power</td>
									<td>12W</td>
								</tr>
								<tr>
									<td>Lumens</td>
									<td>1080</td>
								</tr>
								<tr>
									<td>View Angle</td>
									<td>60<sup>o</sup></td>
								</tr>
								<tr>
									<td>CRI</td>
									<td>z80</td>
								</tr>
								<tr>
									<td>IP Rating</td>
									<td>44</td>
								</tr>
								<tr>
									<td>Color Temperature</td>
									<td>3000-6500K</td>
								</tr>
								<tr>
									<td>Body</td>
									<td>Die-Cast Aluminium</td>
								</tr>
								<tr>
									<td>Cutout Size</td>
									<td>125mm</td>
								</tr>
								<tr>
									<td>Available Colors</td>
									<td>White</td>
								</tr>
								<tr>
									<td>Dimmable</td>
									<td>Yes</td>
								</tr>
								<tr>
									<td>Warranty</td>
									<td>3 Years</td>
								</tr>
								<tr>
									<td>Application</td>
									<td>Rooms, Malls, Offices</td>
								</tr>
							</table>
						</div>
					</div>
					<button class="btn btn-lg btn-primary" style="float: right; border-radius: 0px; border: none; margin-right: 7px;">Get Quote</button>
				</div>
			</div>
		</div>
	</div>
	<?php require_once('includes/footer.html') ?>

	<script src="node_modules/jquery/dist/jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script>
		$('.panel-title a').click(function (event) {
			$('.panel-title a').children('.fa').addClass('fa-plus-square');
			$('.panel-title a').children('.fa').removeClass('fa-minus-square');
			if ($(this).hasClass('collapsed')) {
				$(this).children('.fa').removeClass('fa-plus-square');
				$(this).children('.fa').addClass('fa-minus-square');
			}
			else {
				$(this).children('.fa').addClass('fa-plus-square');
				$(this).children('.fa').removeClass('fa-minus-square');
			}
		})
	</script>
</body>
</html>