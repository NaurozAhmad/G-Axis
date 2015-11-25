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

	<div class="content">
		<div class="g-container">
			<div class="row">
 <?php 
                       if (isset($_GET['newsid'])) {
                       	$news_id =$_GET['newsid'];
                       	$sql_news = "select * from news where news_id=$news_id";
                       	$result_news = mysqli_query($conn, $sql_news);
                       	}

                        if (mysqli_num_rows($result_news) > 0) {
                        // output data of each row
                        	while($row_news = mysqli_fetch_assoc($result_news)) {?>

				<h3 id="subcat-heading"><?php echo $row_news['news_title']; ?></h3>
				<h4 id="subcat-heading"><?php echo $row_news['news_date']; ?></h4>
				<p><?php echo $row_news['news_description']; ?></p>
				 <?php   }    }        ?>
			</div>
			     
			</div>
	</div>
	<?php require_once('includes/footer.php') ?>
	<?php require_once("livechat.php") ?>
</body>
</html>