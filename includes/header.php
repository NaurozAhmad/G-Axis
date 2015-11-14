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
		<div class="col-sm-6">
			<img src="images/bulb.jpg" alt="">
		</div>
		<div class="col-sm-6">
			<h4>Lorem ipsum dolor</h4>
			<p> sit amet.</p>
			<a href="product-details.php" class="btn btn-success">Go to Details</a>
		</div>
	</div>
</div>
<script type="text/javascript">
	function openNewProduct () {	
		if($('.new-product-outer').hasClass('open')) {
			$('.new-product-outer').removeClass('open');
			$('.new-product-outer .fa').addClass('fa-plus');
			$('.new-product-outer .fa').removeClass('fa-minus');
		}
		else {
			$('.new-product-outer').addClass('open');
			$('.new-product-outer .fa').removeClass('fa-plus');
			$('.new-product-outer .fa').addClass('fa-minus');
		}
	}
</script>