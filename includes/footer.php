<div class="footer">
    <div class="g-container">
        <div class="row" style="padding: 30px 0 60px 0;">
            <div class="col-md-4 col-md-offset-1" style="color: #FFF">
                <h3 style="color: #0162d7">Contact</h3>
                <p>Global Axis Co LLC</p>
                <p>P.O. Box: 1604, PC 130, Azaiba, Sultanate of Oman</p>
                <p>Tel: +968 24597462, Fax: +968 24597463</p>
                <p>Email: info@gaxisintl.com</p>
                <div style="display: inline-flex; align-content: top">
                    <i class="fa fa-facebook-square"></i><span style="padding: 10px 10px"> Like us</span>
                </div>
                <a class="btn btn-primary" style="float: right;" href="#">Send us Inquiry</a>
            </div>
            <div class="col-md-4 col-md-offset-2">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d17392.431289256434!2d58.38206082288828!3d23.59327439856485!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3e91ffe78e0c2067%3A0x72736e9a603a541a!2sAzaiba!5e0!3m2!1sen!2s!4v1446138392827" width="100%" height="270" frameborder="0" style="border:0" allowfullscreen></iframe>
            </div>
        </div>
        <div class="row footer-foot" style="">
            <div style="padding: 16px 0">
                <a href="#">Home</a>
                <a href="#">About Us</a>
                <a href="#">Products</a>
                <a href="#">Projects</a>
                <a href="#">Gallery</a>
                <a href="#">Careers</a>
                <a href="#">Contact</a>
            </div>
            <p>Copyright 2015 Global Axis Company LLC . Oman. All rights reserved.</p>
        </div>
    </div>
</div>
<script src="node_modules/jquery/dist/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
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
	setTimeout(openNewProduct, 5000);
</script>