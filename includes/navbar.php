<?php require_once("connection.php") ?>
<?php 
$sql_cat = "SELECT * FROM category";
$result = mysqli_query($conn, $sql_cat);
?>

 
<nav class="navbar navbar-default">
    <div class="container-fluid g-navbar-container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#rest-of-navbar" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>
        <div class="collapse navbar-collapse" id="rest-of-navbar">
        	<ul class="nav navbar-nav">
        	    <li><a href="index.php">Home</a></li>
        	    <li><a href="about.php">About Us</a></li>
        	    <li class="dropdown">
        	        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Products <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="about-led.php">About LED</a></li>
                        <li><a href="about-solar.php">About Solar</a></li>
                        <?php 
                        if (mysqli_num_rows($result) > 0) {
                        // output data of each row
                            while($row = mysqli_fetch_assoc($result)) {?> 
                            <li><a href="#"><?php echo $row['category_name'];?></a></li> 
                            <?php  
                        }
                    } 
                    mysqli_close($conn); ?>

                </ul>
        	    </li>
        	    <li><a href="">Projects</a></li>
        	    <li><a href="gallery.php">Gallery</a></li>
        	    <li><a href="">Careers</a></li>
        	    <li><a href="contact.php">Contact</a></li>
        	</ul>
        </div>
    </div>
</nav>
