<div class="categories-container">
    <div class="categories-heading">
        <h4 class="text-center" style="color: white; margin: 10px 0;">PRODUCTS</h4>
    </div>
    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
        <?php 
$sql_cat_nav = "select * from category c";
$result_cat_nav = mysqli_query($conn, $sql_cat_nav);
 ?>
            <div class="panel panel-default">
                <!-- category data -->
                <?php 
					if (mysqli_num_rows($result_cat_nav) > 0) {
					// output data of each row
						$i = 0;
					while($row_cat_nav = mysqli_fetch_assoc($result_cat_nav)) { $i++;
				?>
                    <div class="panel-heading" role="tab" id="heading-commercial">
                        <h4 class="panel-title">
					<a role="button" class=" <?php if ($i > 1) {echo 'collapsed';} ?>" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $i ?>" aria-expanded="<?php if ($i == 1) {echo 'true';} else {echo 'false';}?>" aria-controls="collapseOne">
						<i class="fa <?php if ($i == 1) {echo 'fa-minus-square';} else {echo 'fa-plus-square';} ?>"></i> <?php echo $row_cat_nav['category_name']; ?>
					</a>
			
				</h4>
                    </div>
                    <!-- category data -->
                    <?php 
						$cat_id = $row_cat_nav['category_id'];
						$sql_scat_nav = "select * from sub_category sc where sc.category_id = $cat_id";
						$result_scat_nav = mysqli_query($conn, $sql_scat_nav);
					 ?>
                        <!-- sub-category -->
                        <div id="collapse<?php echo $i ?>" class="panel-collapse collapse <?php if ($i == 1) {echo 'in';} else {echo 'false';}?>" role="tabpanel" aria-labelledby="heading-commercial">
                            <div class="panel-body">
                                <ul>
                                    <?php while($row_scat_nav = mysqli_fetch_assoc($result_scat_nav)) {?>
                                        <li>
                                            <a href="products.php?sid=<?php echo $row_scat_nav['scat_id']; ?>">
                                                <?php echo $row_scat_nav['scat_name']; ?>
                                            </a>
                                        </li>
                                        <?php } ?>
                                </ul>
                            </div>
                        </div>
                        <!-- sub-category -->
                        <?php   }    }        ?>
            </div>
    </div>
</div>
