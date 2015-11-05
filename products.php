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
		<div class="banner" style="background-image: url(images/slider/slider-1.jpg)">
			<?php require_once('includes/news.html') ?>
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
			
			<div class="row">
				<div class="col-md-3">
					<?php require_once('includes/categories-navigator.html') ?>
				</div>
				<div class="col-md-9">
					<?php require_once('includes/products-gallery.html') ?>
				</div>
			</div>
		</div>
	</div>
	<?php require_once('includes/footer.html') ?>

	<script src="node_modules/jquery/dist/jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script>
		$('.panel-title a').click(function (event) {
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