<?php

// category_id
// scat_name
// scat_picture

?>
<?php if ($sub_category->Visible) { ?>
<!-- <h4 class="ewMasterCaption"><?php echo $sub_category->TableCaption() ?></h4> -->
<table id="tbl_sub_categorymaster" class="table table-bordered table-striped ewViewTable">
	<tbody>
<?php if ($sub_category->category_id->Visible) { // category_id ?>
		<tr id="r_category_id">
			<td><?php echo $sub_category->category_id->FldCaption() ?></td>
			<td<?php echo $sub_category->category_id->CellAttributes() ?>>
<span id="el_sub_category_category_id">
<span<?php echo $sub_category->category_id->ViewAttributes() ?>>
<?php echo $sub_category->category_id->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($sub_category->scat_name->Visible) { // scat_name ?>
		<tr id="r_scat_name">
			<td><?php echo $sub_category->scat_name->FldCaption() ?></td>
			<td<?php echo $sub_category->scat_name->CellAttributes() ?>>
<span id="el_sub_category_scat_name">
<span<?php echo $sub_category->scat_name->ViewAttributes() ?>>
<?php echo $sub_category->scat_name->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($sub_category->scat_picture->Visible) { // scat_picture ?>
		<tr id="r_scat_picture">
			<td><?php echo $sub_category->scat_picture->FldCaption() ?></td>
			<td<?php echo $sub_category->scat_picture->CellAttributes() ?>>
<span id="el_sub_category_scat_picture">
<span<?php echo $sub_category->scat_picture->ViewAttributes() ?>>
<?php echo ew_GetFileViewTag($sub_category->scat_picture, $sub_category->scat_picture->ListViewValue()) ?>
</span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
<?php } ?>
