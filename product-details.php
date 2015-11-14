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
	  <?php 
	  if(isset($_GET['pid']))
	  {
	  	$pid = $_GET['pid'];
$sql_products = "select * from products p where p.product_id=$pid";
$result_products = mysqli_query($conn, $sql_products);
}
?>
	<div class="content">
		<div class="g-container">
			<div class="row">
			<?php 
if (mysqli_num_rows($result_products) > 0) {
// output data of each row
while($row_products = mysqli_fetch_assoc($result_products)) {?>
				<h3><?php echo $row_products['product_name']; ?></h3>
				<p><?php echo $row_products['product_description']; ?></p>
			</div>
			
			<div class="row" style="margin-bottom: 100px;">
				<div class="col-md-3">
					<?php require_once('includes/categories-navigator.php') ?>
				</div>
				<div style="margin-top: 20px;" class="col-md-4">
					<img class="details-img" src="admin/uploads/<?php echo $row_products['product_image'];  ?>" alt="">
					<div style="background-color:#FFF">
						<img class="details-img" src="admin/uploads/<?php echo $row_products['product_secimage'];  ?>" alt="">
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
									<td><?php echo $row_products['feature_ledtype']; ?></td>
								</tr>
								<tr>
									<td>Power</td>
									<td><?php echo $row_products['feature_power']; ?></td>
								</tr>
								<tr>
									<td>Lumens</td>
									<td><?php echo $row_products['feature_lumen']; ?></td>
								</tr>
								<tr>
									<td>View Angle</td>
									<td><?php echo $row_products['feature_viewangle']; ?><sup>o</sup></td>
								</tr>
								<tr>
									<td>CRI</td>
									<td><?php echo $row_products['feature_cri']; ?></td>
								</tr>
								<tr>
									<td>IP Rating</td>
									<td><?php echo $row_products['feature_iprating']; ?></td>
								</tr>
								<tr>
									<td>Color Temperature</td>
									<td><?php echo $row_products['feature_colortemp']; ?></td>
								</tr>
								<tr>
									<td>Body</td>
									<td><?php echo $row_products['feature_body']; ?></td>
								</tr>
								<tr>
									<td>Cutout Size</td>
									<td><?php echo $row_products['feature_cutoutsize']; ?></td>
								</tr>
								<tr>
									<td>Available Colors</td>
									<td><?php echo $row_products['feature_colors']; ?></td>
								</tr>
								<tr>
									<td>Dimmable</td>
									<td><?php echo $row_products['feature_dimmable']; ?></td>
								</tr>
								<tr>
									<td>Warranty</td>
									<td><?php echo $row_products['feature_warranty']; ?></td>
								</tr>
								<tr>
									<td>Application</td>
									<td><?php echo $row_products['feature_application']; ?></td>
								</tr>
							</table>
						</div>
						<?php   }    }        ?>
					</div>
					<button class="btn btn-lg btn-primary" style="float: right; border-radius: 0px; border: none; margin-right: 7px;">Get Quote</button>
				</div>
			</div>
		</div>
	</div>
	<?php require_once('includes/footer.php') ?>

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