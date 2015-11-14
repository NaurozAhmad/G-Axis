<div class="top-bar">
    <div class="g-container">
        <div class="row">
        	<div class="col-sm-4 col-xs-12">
        	    <a href="index.php"><img class="logo" src="images/logo.png" alt=""></a>
        	</div>
        	<div class="col-sm-4 col-sm-offset-4 col-xs-12 float-right">
        	    <div class="top-bar-container">
        	    	<h4 style="font-weight: bold; color: #004fc4">Need Help?</h4>
        	    	<span class="phone">+968 24597462</span>
        	    	<div class="search-bar">
        	    	    <input type="text" class="form-control" placeholder="Search">
        	    	</div>
        	    </div>
        	</div>
        </div>
    </div>
</div>

<div class="new-product-outer" style="">
	<button onclick="openNewProduct()" class="btn btn-default btn-toggle"><i class="fa fa-plus"></i></button>
	<div class="row new-product-inner" style="">
		  <?php 
            require_once('connection.php');
            $sql_random = "SELECT * FROM products p ORDER BY p.product_id DESC LIMIT 1";
            $result_random = mysqli_query($conn, $sql_random);
            $row_random = mysqli_fetch_assoc($result_random);
         ?>
        <div class="col-sm-6">
			<img src="admin/uploads/<?php echo $row_random['product_image']; ?>" alt="">
		</div>
      
		<div class="col-sm-6">
			<h4><?php echo $row_random['product_name']; ?></h4>
			<p> Warranty : <?php echo $row_random['feature_warranty']; ?></p>
			<a href="product-details.php?pid=<?php echo $row_random['product_id']; ?>#news-div" class="btn btn-success">Go to Details</a>
		</div>
	</div>
</div>



