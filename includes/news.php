<?php require_once("connection.php") ?>
    <?php 
$sql_news = "SELECT * FROM news";
$result_news = mysqli_query($conn, $sql_news);
?>
<div class="news-slider" id="news-div">
	<div class="news-slider-header">
		<div class="news-slider-header-container">Latest News</div>
	</div>
    <marquee behavior="scroll" direction="left" onmouseover="this.stop();" onmouseout="this.start();">
      <?php  if (mysqli_num_rows($result_news) > 0) {
        // output data of each row
                 while($row_news = mysqli_fetch_assoc($result_news)) {?>
                 <a href="news_details.php?newsid=<?php echo $row_news['news_id']; ?>#news-div"><?php echo $row_news['news_title']; ?></a>
                 	<?php }}?>        
    </marquee>
</div>