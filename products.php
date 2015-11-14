<!DOCTYPE html>
<html>
<head>
	<title>Global Axis International Co.</title>
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="node_modules/font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="css/custom.css">
</head>
<body id="products">
	<?php require_once('includes/header.php') ?>
	<div class="slider">
		<div class="banner" style="background-image: url(images/slider/slider-1.jpg)">
			<?php require_once('includes/news.php') ?>
		</div>
	</div>
	<div class="g-nav">
		<?php require_once('includes/navbar.php'); ?>
	</div>
	<?php if (isset($_GET['sid'])) {
		$sid=$_GET['sid'];
		$sql_scategory = "select * from sub_category s where s.scat_id = $sid";
		$result_scategory = mysqli_query($conn, $sql_scategory);
	} ?>
	<div class="content">
		<div class="g-container">
			<div class="row">
 <?php 
                        if (mysqli_num_rows($result_scategory) > 0) {
                        // output data of each row
                        	while($row_scategory = mysqli_fetch_assoc($result_scategory)) {?>

				<h3><?php echo $row_scategory['scat_name']; ?></h3>
				<p><?php echo $row_scategory['scat_description']; ?></p>
			</div>
			      <?php   }    }        ?>
			<div class="row">
				<div class="col-md-3">
					<?php require_once('includes/categories-navigator.php') ?>
				</div>
				<div class="col-md-9">
					<?php require_once('includes/products-gallery.php') ?>
				</div>
			</div>
		</div>
	</div>
	<?php require_once('includes/footer.php') ?>

	<script src="node_modules/jquery/dist/jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script>
		$('.panel-title a').click(function (event) {
			if ($(this).hasClass('collapsed')) {
				$('.panel-title a').children('.fa').removeClass('fa-minus-square');
				$('.panel-title a').children('.fa').addClass('fa-plus-square');
				$(this).children('.fa').removeClass('fa-plus-square');
				$(this).children('.fa').addClass('fa-minus-square');
			}
			else {
				/*$('.panel-title a').children('.fa').removeClass('fa-plus-square');
				$('.panel-title a').children('.fa').addClass('fa-minus-square');*/
				$(this).children('.fa').addClass('fa-plus-square');
				$(this).children('.fa').removeClass('fa-minus-square');
			}
		})
	</script>
</body>
</html>