<!DOCTYPE html>
<html>
<head>
	<title>Commercial Lights - Global Axis International Co.</title>
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="node_modules/font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="css/custom.css">
</head>
<body>
	<?php require_once('includes/header.php') ?>
	<div class="slider">
		<div class="banner" style="background-image: url(images/slider/slider-1.jpg)">
			<?php require_once('includes/news.php') ?>
		</div>
	</div>
	<div class="g-nav">
		<?php require_once('includes/navbar.php'); ?>
	</div>
	<?php if (isset($_GET['id'])) {
		$id=$_GET['id'];
		$sql_category = "select * from category c where c.category_id = $id";
		$result_category = mysqli_query($conn, $sql_category);
	} ?>
	<div class="content">
		<div class="g-container">
			<div class="row">

			 <?php 
                        if (mysqli_num_rows($result_category) > 0) {
                        // output data of each row
                            while($row_category = mysqli_fetch_assoc($result_category)) {?> 
                          

				<h3><?php echo $row_category['category_name']; ?></h3>
				<p><?php echo $row_category['category_description'] ?></p>
			  <?php   }    }        ?>
			</div>
<?php if (isset($_GET['id'])) {
		$sid=$_GET['id'];
		$sql_scategory = "select * from sub_category s where s.category_id = $sid";
		$result_scategory = mysqli_query($conn, $sql_scategory);
	} ?>
		
			<!-- gallery items  -->
<div class="gallery">
    <div class="row">

			 <?php 
                        if (mysqli_num_rows($result_scategory) > 0) {
                        // output data of each row
                            while($row_scategory = mysqli_fetch_assoc($result_scategory)) {?> 
           <div class="col-sm-4 gallery-item">
            <a href="products.php?sid=<?php echo $row_scategory['scat_id']; ?>#news-div">
	            <div class="gallery-item-container">
	        	    <div class="product-img" style="background-image: url('admin/uploads/<?php echo $row_scategory['scat_picture']; ?>')"></div>
	        	    <p class="item-name"><?php echo $row_scategory['scat_name']; ?></p>
	        		<div class="gallery-overlay"><span>View Details</span></div>
	        	</div>
            </a>
        </div>   
      <?php   }    }        ?>
    </div>
</div>			
			<!-- gallery items -->
		</div>
	</div>
	<?php require_once('includes/footer.php') ?>

	
</body>
</html>