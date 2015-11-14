<div class="gallery">
    <div class="row">
        <?php 
$sql_products = "select * from products p where p.scat_id=$sid";
$result_products = mysqli_query($conn, $sql_products);
?>
            <div class="col-sm-4 gallery-item">
                <?php 
if (mysqli_num_rows($result_products) > 0) {
// output data of each row
while($row_products = mysqli_fetch_assoc($result_products)) {?>
                    <a href="product-details.php?pid=<?php echo $row_products['product_id']; ?>#news-div">
                        <div class="gallery-item-container">
                            <div class="product-img" style="background-image: url('admin/uploads/<?php echo $row_products['product_image']; ?>')"></div>
                            <p class="item-name">
                                <?php echo $row_products['product_name']; ?>
                            </p>
                            <div class="gallery-overlay"><span>View Details</span></div>
                        </div>
                    </a>
                    <?php   }    }        ?>
            </div>
    </div>
</div>
