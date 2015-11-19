<?php require_once("connection.php") ?>
    <?php 
$sql_subcat = "SELECT * FROM sub_category";
$result_subcat = mysqli_query($conn, $sql_subcat);
?>
        <div class="gallery">
           
           
                <div class="row">
                 <?php 
        if (mysqli_num_rows($result_subcat) > 0) {
        // output data of each row
                 while($row_subcat = mysqli_fetch_assoc($result_subcat)) {?>
                    <div class="col-sm-4 gallery-item">
                        <a href="products.php?sid=<?php echo $row_subcat['scat_id']; ?>#news-div">
                            <div class="gallery-item-container">
                                <div class="product-img" style="background-image: url('admin/uploads/<?php echo $row_subcat['scat_picture'];  ?>')"></div>
                                <p class="item-name"><?php echo $row_subcat['scat_name']; ?></p>
                                <div class="gallery-overlay"><span>View Details</span></div>
                            </div>
                        </a>
                    </div>
           <?php }} ?>        
                      
						

                </div>
        </div>
