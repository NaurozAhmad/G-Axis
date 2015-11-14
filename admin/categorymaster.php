<?php

// category_name
?>
<?php if ($category->Visible) { ?>
<!-- <h4 class="ewMasterCaption"><?php echo $category->TableCaption() ?></h4> -->
<table id="tbl_categorymaster" class="table table-bordered table-striped ewViewTable">
	<tbody>
<?php if ($category->category_name->Visible) { // category_name ?>
		<tr id="r_category_name">
			<td><?php echo $category->category_name->FldCaption() ?></td>
			<td<?php echo $category->category_name->CellAttributes() ?>>
<span id="el_category_category_name">
<span<?php echo $category->category_name->ViewAttributes() ?>>
<?php echo $category->category_name->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
<?php } ?>
