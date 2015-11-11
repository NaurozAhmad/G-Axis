<?php

// Create page object
if (!isset($products_grid)) $products_grid = new cproducts_grid();

// Page init
$products_grid->Page_Init();

// Page main
$products_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$products_grid->Page_Render();
?>
<?php if ($products->Export == "") { ?>
<script type="text/javascript">

// Form object
var fproductsgrid = new ew_Form("fproductsgrid", "grid");
fproductsgrid.FormKeyCountName = '<?php echo $products_grid->FormKeyCountName ?>';

// Validate form
fproductsgrid.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.FormKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);
		var checkrow = (gridinsert) ? !this.EmptyRow(infix) : true;
		if (checkrow) {
			addcnt++;
			elm = this.GetElements("x" + infix + "_category_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $products->category_id->FldCaption(), $products->category_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_scat_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $products->scat_id->FldCaption(), $products->scat_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_product_name");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $products->product_name->FldCaption(), $products->product_name->ReqErrMsg)) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	return true;
}

// Check empty row
fproductsgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "category_id", false)) return false;
	if (ew_ValueChanged(fobj, infix, "scat_id", false)) return false;
	if (ew_ValueChanged(fobj, infix, "product_name", false)) return false;
	if (ew_ValueChanged(fobj, infix, "product_image", false)) return false;
	if (ew_ValueChanged(fobj, infix, "product_secimage", false)) return false;
	return true;
}

// Form_CustomValidate event
fproductsgrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fproductsgrid.ValidateRequired = true;
<?php } else { ?>
fproductsgrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fproductsgrid.Lists["x_category_id"] = {"LinkField":"x_category_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_category_name","","",""],"ParentFields":["sub_category x_category_id"],"ChildFields":["x_scat_id"],"FilterFields":["x_category_name"],"Options":[],"Template":""};
fproductsgrid.Lists["x_scat_id"] = {"LinkField":"x_scat_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_scat_name","","",""],"ParentFields":["x_category_id"],"ChildFields":[],"FilterFields":["x_category_id"],"Options":[],"Template":""};

// Form object for search
</script>
<?php } ?>
<?php
if ($products->CurrentAction == "gridadd") {
	if ($products->CurrentMode == "copy") {
		$bSelectLimit = $products_grid->UseSelectLimit;
		if ($bSelectLimit) {
			$products_grid->TotalRecs = $products->SelectRecordCount();
			$products_grid->Recordset = $products_grid->LoadRecordset($products_grid->StartRec-1, $products_grid->DisplayRecs);
		} else {
			if ($products_grid->Recordset = $products_grid->LoadRecordset())
				$products_grid->TotalRecs = $products_grid->Recordset->RecordCount();
		}
		$products_grid->StartRec = 1;
		$products_grid->DisplayRecs = $products_grid->TotalRecs;
	} else {
		$products->CurrentFilter = "0=1";
		$products_grid->StartRec = 1;
		$products_grid->DisplayRecs = $products->GridAddRowCount;
	}
	$products_grid->TotalRecs = $products_grid->DisplayRecs;
	$products_grid->StopRec = $products_grid->DisplayRecs;
} else {
	$bSelectLimit = $products_grid->UseSelectLimit;
	if ($bSelectLimit) {
		if ($products_grid->TotalRecs <= 0)
			$products_grid->TotalRecs = $products->SelectRecordCount();
	} else {
		if (!$products_grid->Recordset && ($products_grid->Recordset = $products_grid->LoadRecordset()))
			$products_grid->TotalRecs = $products_grid->Recordset->RecordCount();
	}
	$products_grid->StartRec = 1;
	$products_grid->DisplayRecs = $products_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$products_grid->Recordset = $products_grid->LoadRecordset($products_grid->StartRec-1, $products_grid->DisplayRecs);

	// Set no record found message
	if ($products->CurrentAction == "" && $products_grid->TotalRecs == 0) {
		if ($products_grid->SearchWhere == "0=101")
			$products_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$products_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$products_grid->RenderOtherOptions();
?>
<?php $products_grid->ShowPageHeader(); ?>
<?php
$products_grid->ShowMessage();
?>
<?php if ($products_grid->TotalRecs > 0 || $products->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid">
<div id="fproductsgrid" class="ewForm form-inline">
<div id="gmp_products" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_productsgrid" class="table ewTable">
<?php echo $products->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$products_grid->RowType = EW_ROWTYPE_HEADER;

// Render list options
$products_grid->RenderListOptions();

// Render list options (header, left)
$products_grid->ListOptions->Render("header", "left");
?>
<?php if ($products->product_id->Visible) { // product_id ?>
	<?php if ($products->SortUrl($products->product_id) == "") { ?>
		<th data-name="product_id"><div id="elh_products_product_id" class="products_product_id"><div class="ewTableHeaderCaption"><?php echo $products->product_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="product_id"><div><div id="elh_products_product_id" class="products_product_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $products->product_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($products->product_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($products->product_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($products->category_id->Visible) { // category_id ?>
	<?php if ($products->SortUrl($products->category_id) == "") { ?>
		<th data-name="category_id"><div id="elh_products_category_id" class="products_category_id"><div class="ewTableHeaderCaption"><?php echo $products->category_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="category_id"><div><div id="elh_products_category_id" class="products_category_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $products->category_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($products->category_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($products->category_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($products->scat_id->Visible) { // scat_id ?>
	<?php if ($products->SortUrl($products->scat_id) == "") { ?>
		<th data-name="scat_id"><div id="elh_products_scat_id" class="products_scat_id"><div class="ewTableHeaderCaption"><?php echo $products->scat_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="scat_id"><div><div id="elh_products_scat_id" class="products_scat_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $products->scat_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($products->scat_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($products->scat_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($products->product_name->Visible) { // product_name ?>
	<?php if ($products->SortUrl($products->product_name) == "") { ?>
		<th data-name="product_name"><div id="elh_products_product_name" class="products_product_name"><div class="ewTableHeaderCaption"><?php echo $products->product_name->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="product_name"><div><div id="elh_products_product_name" class="products_product_name">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $products->product_name->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($products->product_name->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($products->product_name->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($products->product_image->Visible) { // product_image ?>
	<?php if ($products->SortUrl($products->product_image) == "") { ?>
		<th data-name="product_image"><div id="elh_products_product_image" class="products_product_image"><div class="ewTableHeaderCaption"><?php echo $products->product_image->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="product_image"><div><div id="elh_products_product_image" class="products_product_image">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $products->product_image->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($products->product_image->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($products->product_image->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($products->product_secimage->Visible) { // product_secimage ?>
	<?php if ($products->SortUrl($products->product_secimage) == "") { ?>
		<th data-name="product_secimage"><div id="elh_products_product_secimage" class="products_product_secimage"><div class="ewTableHeaderCaption"><?php echo $products->product_secimage->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="product_secimage"><div><div id="elh_products_product_secimage" class="products_product_secimage">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $products->product_secimage->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($products->product_secimage->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($products->product_secimage->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$products_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$products_grid->StartRec = 1;
$products_grid->StopRec = $products_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($products_grid->FormKeyCountName) && ($products->CurrentAction == "gridadd" || $products->CurrentAction == "gridedit" || $products->CurrentAction == "F")) {
		$products_grid->KeyCount = $objForm->GetValue($products_grid->FormKeyCountName);
		$products_grid->StopRec = $products_grid->StartRec + $products_grid->KeyCount - 1;
	}
}
$products_grid->RecCnt = $products_grid->StartRec - 1;
if ($products_grid->Recordset && !$products_grid->Recordset->EOF) {
	$products_grid->Recordset->MoveFirst();
	$bSelectLimit = $products_grid->UseSelectLimit;
	if (!$bSelectLimit && $products_grid->StartRec > 1)
		$products_grid->Recordset->Move($products_grid->StartRec - 1);
} elseif (!$products->AllowAddDeleteRow && $products_grid->StopRec == 0) {
	$products_grid->StopRec = $products->GridAddRowCount;
}

// Initialize aggregate
$products->RowType = EW_ROWTYPE_AGGREGATEINIT;
$products->ResetAttrs();
$products_grid->RenderRow();
if ($products->CurrentAction == "gridadd")
	$products_grid->RowIndex = 0;
if ($products->CurrentAction == "gridedit")
	$products_grid->RowIndex = 0;
while ($products_grid->RecCnt < $products_grid->StopRec) {
	$products_grid->RecCnt++;
	if (intval($products_grid->RecCnt) >= intval($products_grid->StartRec)) {
		$products_grid->RowCnt++;
		if ($products->CurrentAction == "gridadd" || $products->CurrentAction == "gridedit" || $products->CurrentAction == "F") {
			$products_grid->RowIndex++;
			$objForm->Index = $products_grid->RowIndex;
			if ($objForm->HasValue($products_grid->FormActionName))
				$products_grid->RowAction = strval($objForm->GetValue($products_grid->FormActionName));
			elseif ($products->CurrentAction == "gridadd")
				$products_grid->RowAction = "insert";
			else
				$products_grid->RowAction = "";
		}

		// Set up key count
		$products_grid->KeyCount = $products_grid->RowIndex;

		// Init row class and style
		$products->ResetAttrs();
		$products->CssClass = "";
		if ($products->CurrentAction == "gridadd") {
			if ($products->CurrentMode == "copy") {
				$products_grid->LoadRowValues($products_grid->Recordset); // Load row values
				$products_grid->SetRecordKey($products_grid->RowOldKey, $products_grid->Recordset); // Set old record key
			} else {
				$products_grid->LoadDefaultValues(); // Load default values
				$products_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$products_grid->LoadRowValues($products_grid->Recordset); // Load row values
		}
		$products->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($products->CurrentAction == "gridadd") // Grid add
			$products->RowType = EW_ROWTYPE_ADD; // Render add
		if ($products->CurrentAction == "gridadd" && $products->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$products_grid->RestoreCurrentRowFormValues($products_grid->RowIndex); // Restore form values
		if ($products->CurrentAction == "gridedit") { // Grid edit
			if ($products->EventCancelled) {
				$products_grid->RestoreCurrentRowFormValues($products_grid->RowIndex); // Restore form values
			}
			if ($products_grid->RowAction == "insert")
				$products->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$products->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($products->CurrentAction == "gridedit" && ($products->RowType == EW_ROWTYPE_EDIT || $products->RowType == EW_ROWTYPE_ADD) && $products->EventCancelled) // Update failed
			$products_grid->RestoreCurrentRowFormValues($products_grid->RowIndex); // Restore form values
		if ($products->RowType == EW_ROWTYPE_EDIT) // Edit row
			$products_grid->EditRowCnt++;
		if ($products->CurrentAction == "F") // Confirm row
			$products_grid->RestoreCurrentRowFormValues($products_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$products->RowAttrs = array_merge($products->RowAttrs, array('data-rowindex'=>$products_grid->RowCnt, 'id'=>'r' . $products_grid->RowCnt . '_products', 'data-rowtype'=>$products->RowType));

		// Render row
		$products_grid->RenderRow();

		// Render list options
		$products_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($products_grid->RowAction <> "delete" && $products_grid->RowAction <> "insertdelete" && !($products_grid->RowAction == "insert" && $products->CurrentAction == "F" && $products_grid->EmptyRow())) {
?>
	<tr<?php echo $products->RowAttributes() ?>>
<?php

// Render list options (body, left)
$products_grid->ListOptions->Render("body", "left", $products_grid->RowCnt);
?>
	<?php if ($products->product_id->Visible) { // product_id ?>
		<td data-name="product_id"<?php echo $products->product_id->CellAttributes() ?>>
<?php if ($products->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="products" data-field="x_product_id" name="o<?php echo $products_grid->RowIndex ?>_product_id" id="o<?php echo $products_grid->RowIndex ?>_product_id" value="<?php echo ew_HtmlEncode($products->product_id->OldValue) ?>">
<?php } ?>
<?php if ($products->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $products_grid->RowCnt ?>_products_product_id" class="form-group products_product_id">
<span<?php echo $products->product_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $products->product_id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="products" data-field="x_product_id" name="x<?php echo $products_grid->RowIndex ?>_product_id" id="x<?php echo $products_grid->RowIndex ?>_product_id" value="<?php echo ew_HtmlEncode($products->product_id->CurrentValue) ?>">
<?php } ?>
<?php if ($products->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $products_grid->RowCnt ?>_products_product_id" class="products_product_id">
<span<?php echo $products->product_id->ViewAttributes() ?>>
<?php echo $products->product_id->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="products" data-field="x_product_id" name="x<?php echo $products_grid->RowIndex ?>_product_id" id="x<?php echo $products_grid->RowIndex ?>_product_id" value="<?php echo ew_HtmlEncode($products->product_id->FormValue) ?>">
<input type="hidden" data-table="products" data-field="x_product_id" name="o<?php echo $products_grid->RowIndex ?>_product_id" id="o<?php echo $products_grid->RowIndex ?>_product_id" value="<?php echo ew_HtmlEncode($products->product_id->OldValue) ?>">
<?php } ?>
<a id="<?php echo $products_grid->PageObjName . "_row_" . $products_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($products->category_id->Visible) { // category_id ?>
		<td data-name="category_id"<?php echo $products->category_id->CellAttributes() ?>>
<?php if ($products->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $products_grid->RowCnt ?>_products_category_id" class="form-group products_category_id">
<?php $products->category_id->EditAttrs["onchange"] = "ew_UpdateOpt.call(this); " . @$products->category_id->EditAttrs["onchange"]; ?>
<select data-table="products" data-field="x_category_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($products->category_id->DisplayValueSeparator) ? json_encode($products->category_id->DisplayValueSeparator) : $products->category_id->DisplayValueSeparator) ?>" id="x<?php echo $products_grid->RowIndex ?>_category_id" name="x<?php echo $products_grid->RowIndex ?>_category_id"<?php echo $products->category_id->EditAttributes() ?>>
<?php
if (is_array($products->category_id->EditValue)) {
	$arwrk = $products->category_id->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($products->category_id->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
		if ($selwrk <> "" || $arwrk[$rowcntwrk][0] == "") {
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $products->category_id->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
		}
	}
}
if (@$emptywrk) $products->category_id->OldValue = "";
?>
</select>
<?php
$sSqlWrk = "SELECT `category_id`, `category_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `category`";
$sWhereWrk = "{filter}";
$products->category_id->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$products->category_id->LookupFilters += array("f0" => "`category_id` = {filter_value}", "t0" => "3", "fn0" => "");
$products->category_id->LookupFilters += array("f1" => "`category_name` IN ({filter_value})", "t1" => "201", "fn1" => "");
$sSqlWrk = "";
$products->Lookup_Selecting($products->category_id, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $products->category_id->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x<?php echo $products_grid->RowIndex ?>_category_id" id="s_x<?php echo $products_grid->RowIndex ?>_category_id" value="<?php echo $products->category_id->LookupFilterQuery() ?>">
</span>
<input type="hidden" data-table="products" data-field="x_category_id" name="o<?php echo $products_grid->RowIndex ?>_category_id" id="o<?php echo $products_grid->RowIndex ?>_category_id" value="<?php echo ew_HtmlEncode($products->category_id->OldValue) ?>">
<?php } ?>
<?php if ($products->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $products_grid->RowCnt ?>_products_category_id" class="form-group products_category_id">
<?php $products->category_id->EditAttrs["onchange"] = "ew_UpdateOpt.call(this); " . @$products->category_id->EditAttrs["onchange"]; ?>
<select data-table="products" data-field="x_category_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($products->category_id->DisplayValueSeparator) ? json_encode($products->category_id->DisplayValueSeparator) : $products->category_id->DisplayValueSeparator) ?>" id="x<?php echo $products_grid->RowIndex ?>_category_id" name="x<?php echo $products_grid->RowIndex ?>_category_id"<?php echo $products->category_id->EditAttributes() ?>>
<?php
if (is_array($products->category_id->EditValue)) {
	$arwrk = $products->category_id->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($products->category_id->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
		if ($selwrk <> "" || $arwrk[$rowcntwrk][0] == "") {
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $products->category_id->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
		}
	}
}
if (@$emptywrk) $products->category_id->OldValue = "";
?>
</select>
<?php
$sSqlWrk = "SELECT `category_id`, `category_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `category`";
$sWhereWrk = "";
$products->category_id->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$products->category_id->LookupFilters += array("f0" => "`category_id` = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$products->Lookup_Selecting($products->category_id, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $products->category_id->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x<?php echo $products_grid->RowIndex ?>_category_id" id="s_x<?php echo $products_grid->RowIndex ?>_category_id" value="<?php echo $products->category_id->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php if ($products->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $products_grid->RowCnt ?>_products_category_id" class="products_category_id">
<span<?php echo $products->category_id->ViewAttributes() ?>>
<?php echo $products->category_id->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="products" data-field="x_category_id" name="x<?php echo $products_grid->RowIndex ?>_category_id" id="x<?php echo $products_grid->RowIndex ?>_category_id" value="<?php echo ew_HtmlEncode($products->category_id->FormValue) ?>">
<input type="hidden" data-table="products" data-field="x_category_id" name="o<?php echo $products_grid->RowIndex ?>_category_id" id="o<?php echo $products_grid->RowIndex ?>_category_id" value="<?php echo ew_HtmlEncode($products->category_id->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($products->scat_id->Visible) { // scat_id ?>
		<td data-name="scat_id"<?php echo $products->scat_id->CellAttributes() ?>>
<?php if ($products->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($products->scat_id->getSessionValue() <> "") { ?>
<span id="el<?php echo $products_grid->RowCnt ?>_products_scat_id" class="form-group products_scat_id">
<span<?php echo $products->scat_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $products->scat_id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $products_grid->RowIndex ?>_scat_id" name="x<?php echo $products_grid->RowIndex ?>_scat_id" value="<?php echo ew_HtmlEncode($products->scat_id->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $products_grid->RowCnt ?>_products_scat_id" class="form-group products_scat_id">
<select data-table="products" data-field="x_scat_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($products->scat_id->DisplayValueSeparator) ? json_encode($products->scat_id->DisplayValueSeparator) : $products->scat_id->DisplayValueSeparator) ?>" id="x<?php echo $products_grid->RowIndex ?>_scat_id" name="x<?php echo $products_grid->RowIndex ?>_scat_id"<?php echo $products->scat_id->EditAttributes() ?>>
<?php
if (is_array($products->scat_id->EditValue)) {
	$arwrk = $products->scat_id->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($products->scat_id->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
		if ($selwrk <> "" || $arwrk[$rowcntwrk][0] == "") {
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $products->scat_id->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
		}
	}
}
if (@$emptywrk) $products->scat_id->OldValue = "";
?>
</select>
<?php
$sSqlWrk = "SELECT `scat_id`, `scat_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sub_category`";
$sWhereWrk = "{filter}";
$products->scat_id->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$products->scat_id->LookupFilters += array("f0" => "`scat_id` = {filter_value}", "t0" => "3", "fn0" => "");
$products->scat_id->LookupFilters += array("f1" => "`category_id` IN ({filter_value})", "t1" => "3", "fn1" => "");
$sSqlWrk = "";
$products->Lookup_Selecting($products->scat_id, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $products->scat_id->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x<?php echo $products_grid->RowIndex ?>_scat_id" id="s_x<?php echo $products_grid->RowIndex ?>_scat_id" value="<?php echo $products->scat_id->LookupFilterQuery() ?>">
</span>
<?php } ?>
<input type="hidden" data-table="products" data-field="x_scat_id" name="o<?php echo $products_grid->RowIndex ?>_scat_id" id="o<?php echo $products_grid->RowIndex ?>_scat_id" value="<?php echo ew_HtmlEncode($products->scat_id->OldValue) ?>">
<?php } ?>
<?php if ($products->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($products->scat_id->getSessionValue() <> "") { ?>
<span id="el<?php echo $products_grid->RowCnt ?>_products_scat_id" class="form-group products_scat_id">
<span<?php echo $products->scat_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $products->scat_id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $products_grid->RowIndex ?>_scat_id" name="x<?php echo $products_grid->RowIndex ?>_scat_id" value="<?php echo ew_HtmlEncode($products->scat_id->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $products_grid->RowCnt ?>_products_scat_id" class="form-group products_scat_id">
<select data-table="products" data-field="x_scat_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($products->scat_id->DisplayValueSeparator) ? json_encode($products->scat_id->DisplayValueSeparator) : $products->scat_id->DisplayValueSeparator) ?>" id="x<?php echo $products_grid->RowIndex ?>_scat_id" name="x<?php echo $products_grid->RowIndex ?>_scat_id"<?php echo $products->scat_id->EditAttributes() ?>>
<?php
if (is_array($products->scat_id->EditValue)) {
	$arwrk = $products->scat_id->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($products->scat_id->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
		if ($selwrk <> "" || $arwrk[$rowcntwrk][0] == "") {
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $products->scat_id->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
		}
	}
}
if (@$emptywrk) $products->scat_id->OldValue = "";
?>
</select>
<?php
$sSqlWrk = "SELECT `scat_id`, `scat_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sub_category`";
$sWhereWrk = "{filter}";
$products->scat_id->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$products->scat_id->LookupFilters += array("f0" => "`scat_id` = {filter_value}", "t0" => "3", "fn0" => "");
$products->scat_id->LookupFilters += array("f1" => "`category_id` IN ({filter_value})", "t1" => "3", "fn1" => "");
$sSqlWrk = "";
$products->Lookup_Selecting($products->scat_id, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $products->scat_id->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x<?php echo $products_grid->RowIndex ?>_scat_id" id="s_x<?php echo $products_grid->RowIndex ?>_scat_id" value="<?php echo $products->scat_id->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php } ?>
<?php if ($products->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $products_grid->RowCnt ?>_products_scat_id" class="products_scat_id">
<span<?php echo $products->scat_id->ViewAttributes() ?>>
<?php echo $products->scat_id->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="products" data-field="x_scat_id" name="x<?php echo $products_grid->RowIndex ?>_scat_id" id="x<?php echo $products_grid->RowIndex ?>_scat_id" value="<?php echo ew_HtmlEncode($products->scat_id->FormValue) ?>">
<input type="hidden" data-table="products" data-field="x_scat_id" name="o<?php echo $products_grid->RowIndex ?>_scat_id" id="o<?php echo $products_grid->RowIndex ?>_scat_id" value="<?php echo ew_HtmlEncode($products->scat_id->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($products->product_name->Visible) { // product_name ?>
		<td data-name="product_name"<?php echo $products->product_name->CellAttributes() ?>>
<?php if ($products->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $products_grid->RowCnt ?>_products_product_name" class="form-group products_product_name">
<input type="text" data-table="products" data-field="x_product_name" name="x<?php echo $products_grid->RowIndex ?>_product_name" id="x<?php echo $products_grid->RowIndex ?>_product_name" placeholder="<?php echo ew_HtmlEncode($products->product_name->getPlaceHolder()) ?>" value="<?php echo $products->product_name->EditValue ?>"<?php echo $products->product_name->EditAttributes() ?>>
</span>
<input type="hidden" data-table="products" data-field="x_product_name" name="o<?php echo $products_grid->RowIndex ?>_product_name" id="o<?php echo $products_grid->RowIndex ?>_product_name" value="<?php echo ew_HtmlEncode($products->product_name->OldValue) ?>">
<?php } ?>
<?php if ($products->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $products_grid->RowCnt ?>_products_product_name" class="form-group products_product_name">
<input type="text" data-table="products" data-field="x_product_name" name="x<?php echo $products_grid->RowIndex ?>_product_name" id="x<?php echo $products_grid->RowIndex ?>_product_name" placeholder="<?php echo ew_HtmlEncode($products->product_name->getPlaceHolder()) ?>" value="<?php echo $products->product_name->EditValue ?>"<?php echo $products->product_name->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($products->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $products_grid->RowCnt ?>_products_product_name" class="products_product_name">
<span<?php echo $products->product_name->ViewAttributes() ?>>
<?php echo $products->product_name->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="products" data-field="x_product_name" name="x<?php echo $products_grid->RowIndex ?>_product_name" id="x<?php echo $products_grid->RowIndex ?>_product_name" value="<?php echo ew_HtmlEncode($products->product_name->FormValue) ?>">
<input type="hidden" data-table="products" data-field="x_product_name" name="o<?php echo $products_grid->RowIndex ?>_product_name" id="o<?php echo $products_grid->RowIndex ?>_product_name" value="<?php echo ew_HtmlEncode($products->product_name->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($products->product_image->Visible) { // product_image ?>
		<td data-name="product_image"<?php echo $products->product_image->CellAttributes() ?>>
<?php if ($products_grid->RowAction == "insert") { // Add record ?>
<span id="el$rowindex$_products_product_image" class="form-group products_product_image">
<div id="fd_x<?php echo $products_grid->RowIndex ?>_product_image">
<span title="<?php echo $products->product_image->FldTitle() ? $products->product_image->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($products->product_image->ReadOnly || $products->product_image->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="products" data-field="x_product_image" name="x<?php echo $products_grid->RowIndex ?>_product_image" id="x<?php echo $products_grid->RowIndex ?>_product_image"<?php echo $products->product_image->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x<?php echo $products_grid->RowIndex ?>_product_image" id= "fn_x<?php echo $products_grid->RowIndex ?>_product_image" value="<?php echo $products->product_image->Upload->FileName ?>">
<input type="hidden" name="fa_x<?php echo $products_grid->RowIndex ?>_product_image" id= "fa_x<?php echo $products_grid->RowIndex ?>_product_image" value="0">
<input type="hidden" name="fs_x<?php echo $products_grid->RowIndex ?>_product_image" id= "fs_x<?php echo $products_grid->RowIndex ?>_product_image" value="200">
<input type="hidden" name="fx_x<?php echo $products_grid->RowIndex ?>_product_image" id= "fx_x<?php echo $products_grid->RowIndex ?>_product_image" value="<?php echo $products->product_image->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?php echo $products_grid->RowIndex ?>_product_image" id= "fm_x<?php echo $products_grid->RowIndex ?>_product_image" value="<?php echo $products->product_image->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?php echo $products_grid->RowIndex ?>_product_image" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<input type="hidden" data-table="products" data-field="x_product_image" name="o<?php echo $products_grid->RowIndex ?>_product_image" id="o<?php echo $products_grid->RowIndex ?>_product_image" value="<?php echo ew_HtmlEncode($products->product_image->OldValue) ?>">
<?php } elseif ($products->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $products_grid->RowCnt ?>_products_product_image" class="products_product_image">
<span<?php echo $products->product_image->ViewAttributes() ?>>
<?php echo ew_GetFileViewTag($products->product_image, $products->product_image->ListViewValue()) ?>
</span>
</span>
<?php } else  { // Edit record ?>
<span id="el<?php echo $products_grid->RowCnt ?>_products_product_image" class="form-group products_product_image">
<div id="fd_x<?php echo $products_grid->RowIndex ?>_product_image">
<span title="<?php echo $products->product_image->FldTitle() ? $products->product_image->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($products->product_image->ReadOnly || $products->product_image->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="products" data-field="x_product_image" name="x<?php echo $products_grid->RowIndex ?>_product_image" id="x<?php echo $products_grid->RowIndex ?>_product_image"<?php echo $products->product_image->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x<?php echo $products_grid->RowIndex ?>_product_image" id= "fn_x<?php echo $products_grid->RowIndex ?>_product_image" value="<?php echo $products->product_image->Upload->FileName ?>">
<?php if (@$_POST["fa_x<?php echo $products_grid->RowIndex ?>_product_image"] == "0") { ?>
<input type="hidden" name="fa_x<?php echo $products_grid->RowIndex ?>_product_image" id= "fa_x<?php echo $products_grid->RowIndex ?>_product_image" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x<?php echo $products_grid->RowIndex ?>_product_image" id= "fa_x<?php echo $products_grid->RowIndex ?>_product_image" value="1">
<?php } ?>
<input type="hidden" name="fs_x<?php echo $products_grid->RowIndex ?>_product_image" id= "fs_x<?php echo $products_grid->RowIndex ?>_product_image" value="200">
<input type="hidden" name="fx_x<?php echo $products_grid->RowIndex ?>_product_image" id= "fx_x<?php echo $products_grid->RowIndex ?>_product_image" value="<?php echo $products->product_image->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?php echo $products_grid->RowIndex ?>_product_image" id= "fm_x<?php echo $products_grid->RowIndex ?>_product_image" value="<?php echo $products->product_image->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?php echo $products_grid->RowIndex ?>_product_image" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($products->product_secimage->Visible) { // product_secimage ?>
		<td data-name="product_secimage"<?php echo $products->product_secimage->CellAttributes() ?>>
<?php if ($products_grid->RowAction == "insert") { // Add record ?>
<span id="el$rowindex$_products_product_secimage" class="form-group products_product_secimage">
<div id="fd_x<?php echo $products_grid->RowIndex ?>_product_secimage">
<span title="<?php echo $products->product_secimage->FldTitle() ? $products->product_secimage->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($products->product_secimage->ReadOnly || $products->product_secimage->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="products" data-field="x_product_secimage" name="x<?php echo $products_grid->RowIndex ?>_product_secimage" id="x<?php echo $products_grid->RowIndex ?>_product_secimage"<?php echo $products->product_secimage->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x<?php echo $products_grid->RowIndex ?>_product_secimage" id= "fn_x<?php echo $products_grid->RowIndex ?>_product_secimage" value="<?php echo $products->product_secimage->Upload->FileName ?>">
<input type="hidden" name="fa_x<?php echo $products_grid->RowIndex ?>_product_secimage" id= "fa_x<?php echo $products_grid->RowIndex ?>_product_secimage" value="0">
<input type="hidden" name="fs_x<?php echo $products_grid->RowIndex ?>_product_secimage" id= "fs_x<?php echo $products_grid->RowIndex ?>_product_secimage" value="200">
<input type="hidden" name="fx_x<?php echo $products_grid->RowIndex ?>_product_secimage" id= "fx_x<?php echo $products_grid->RowIndex ?>_product_secimage" value="<?php echo $products->product_secimage->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?php echo $products_grid->RowIndex ?>_product_secimage" id= "fm_x<?php echo $products_grid->RowIndex ?>_product_secimage" value="<?php echo $products->product_secimage->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?php echo $products_grid->RowIndex ?>_product_secimage" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<input type="hidden" data-table="products" data-field="x_product_secimage" name="o<?php echo $products_grid->RowIndex ?>_product_secimage" id="o<?php echo $products_grid->RowIndex ?>_product_secimage" value="<?php echo ew_HtmlEncode($products->product_secimage->OldValue) ?>">
<?php } elseif ($products->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $products_grid->RowCnt ?>_products_product_secimage" class="products_product_secimage">
<span<?php echo $products->product_secimage->ViewAttributes() ?>>
<?php echo ew_GetFileViewTag($products->product_secimage, $products->product_secimage->ListViewValue()) ?>
</span>
</span>
<?php } else  { // Edit record ?>
<span id="el<?php echo $products_grid->RowCnt ?>_products_product_secimage" class="form-group products_product_secimage">
<div id="fd_x<?php echo $products_grid->RowIndex ?>_product_secimage">
<span title="<?php echo $products->product_secimage->FldTitle() ? $products->product_secimage->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($products->product_secimage->ReadOnly || $products->product_secimage->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="products" data-field="x_product_secimage" name="x<?php echo $products_grid->RowIndex ?>_product_secimage" id="x<?php echo $products_grid->RowIndex ?>_product_secimage"<?php echo $products->product_secimage->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x<?php echo $products_grid->RowIndex ?>_product_secimage" id= "fn_x<?php echo $products_grid->RowIndex ?>_product_secimage" value="<?php echo $products->product_secimage->Upload->FileName ?>">
<?php if (@$_POST["fa_x<?php echo $products_grid->RowIndex ?>_product_secimage"] == "0") { ?>
<input type="hidden" name="fa_x<?php echo $products_grid->RowIndex ?>_product_secimage" id= "fa_x<?php echo $products_grid->RowIndex ?>_product_secimage" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x<?php echo $products_grid->RowIndex ?>_product_secimage" id= "fa_x<?php echo $products_grid->RowIndex ?>_product_secimage" value="1">
<?php } ?>
<input type="hidden" name="fs_x<?php echo $products_grid->RowIndex ?>_product_secimage" id= "fs_x<?php echo $products_grid->RowIndex ?>_product_secimage" value="200">
<input type="hidden" name="fx_x<?php echo $products_grid->RowIndex ?>_product_secimage" id= "fx_x<?php echo $products_grid->RowIndex ?>_product_secimage" value="<?php echo $products->product_secimage->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?php echo $products_grid->RowIndex ?>_product_secimage" id= "fm_x<?php echo $products_grid->RowIndex ?>_product_secimage" value="<?php echo $products->product_secimage->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?php echo $products_grid->RowIndex ?>_product_secimage" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$products_grid->ListOptions->Render("body", "right", $products_grid->RowCnt);
?>
	</tr>
<?php if ($products->RowType == EW_ROWTYPE_ADD || $products->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fproductsgrid.UpdateOpts(<?php echo $products_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($products->CurrentAction <> "gridadd" || $products->CurrentMode == "copy")
		if (!$products_grid->Recordset->EOF) $products_grid->Recordset->MoveNext();
}
?>
<?php
	if ($products->CurrentMode == "add" || $products->CurrentMode == "copy" || $products->CurrentMode == "edit") {
		$products_grid->RowIndex = '$rowindex$';
		$products_grid->LoadDefaultValues();

		// Set row properties
		$products->ResetAttrs();
		$products->RowAttrs = array_merge($products->RowAttrs, array('data-rowindex'=>$products_grid->RowIndex, 'id'=>'r0_products', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($products->RowAttrs["class"], "ewTemplate");
		$products->RowType = EW_ROWTYPE_ADD;

		// Render row
		$products_grid->RenderRow();

		// Render list options
		$products_grid->RenderListOptions();
		$products_grid->StartRowCnt = 0;
?>
	<tr<?php echo $products->RowAttributes() ?>>
<?php

// Render list options (body, left)
$products_grid->ListOptions->Render("body", "left", $products_grid->RowIndex);
?>
	<?php if ($products->product_id->Visible) { // product_id ?>
		<td data-name="product_id">
<?php if ($products->CurrentAction <> "F") { ?>
<?php } else { ?>
<span id="el$rowindex$_products_product_id" class="form-group products_product_id">
<span<?php echo $products->product_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $products->product_id->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="products" data-field="x_product_id" name="x<?php echo $products_grid->RowIndex ?>_product_id" id="x<?php echo $products_grid->RowIndex ?>_product_id" value="<?php echo ew_HtmlEncode($products->product_id->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="products" data-field="x_product_id" name="o<?php echo $products_grid->RowIndex ?>_product_id" id="o<?php echo $products_grid->RowIndex ?>_product_id" value="<?php echo ew_HtmlEncode($products->product_id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($products->category_id->Visible) { // category_id ?>
		<td data-name="category_id">
<?php if ($products->CurrentAction <> "F") { ?>
<span id="el$rowindex$_products_category_id" class="form-group products_category_id">
<?php $products->category_id->EditAttrs["onchange"] = "ew_UpdateOpt.call(this); " . @$products->category_id->EditAttrs["onchange"]; ?>
<select data-table="products" data-field="x_category_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($products->category_id->DisplayValueSeparator) ? json_encode($products->category_id->DisplayValueSeparator) : $products->category_id->DisplayValueSeparator) ?>" id="x<?php echo $products_grid->RowIndex ?>_category_id" name="x<?php echo $products_grid->RowIndex ?>_category_id"<?php echo $products->category_id->EditAttributes() ?>>
<?php
if (is_array($products->category_id->EditValue)) {
	$arwrk = $products->category_id->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($products->category_id->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
		if ($selwrk <> "" || $arwrk[$rowcntwrk][0] == "") {
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $products->category_id->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
		}
	}
}
if (@$emptywrk) $products->category_id->OldValue = "";
?>
</select>
<?php
$sSqlWrk = "SELECT `category_id`, `category_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `category`";
$sWhereWrk = "{filter}";
$products->category_id->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$products->category_id->LookupFilters += array("f0" => "`category_id` = {filter_value}", "t0" => "3", "fn0" => "");
$products->category_id->LookupFilters += array("f1" => "`category_name` IN ({filter_value})", "t1" => "201", "fn1" => "");
$sSqlWrk = "";
$products->Lookup_Selecting($products->category_id, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $products->category_id->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x<?php echo $products_grid->RowIndex ?>_category_id" id="s_x<?php echo $products_grid->RowIndex ?>_category_id" value="<?php echo $products->category_id->LookupFilterQuery() ?>">
</span>
<?php } else { ?>
<span id="el$rowindex$_products_category_id" class="form-group products_category_id">
<span<?php echo $products->category_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $products->category_id->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="products" data-field="x_category_id" name="x<?php echo $products_grid->RowIndex ?>_category_id" id="x<?php echo $products_grid->RowIndex ?>_category_id" value="<?php echo ew_HtmlEncode($products->category_id->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="products" data-field="x_category_id" name="o<?php echo $products_grid->RowIndex ?>_category_id" id="o<?php echo $products_grid->RowIndex ?>_category_id" value="<?php echo ew_HtmlEncode($products->category_id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($products->scat_id->Visible) { // scat_id ?>
		<td data-name="scat_id">
<?php if ($products->CurrentAction <> "F") { ?>
<?php if ($products->scat_id->getSessionValue() <> "") { ?>
<span id="el$rowindex$_products_scat_id" class="form-group products_scat_id">
<span<?php echo $products->scat_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $products->scat_id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $products_grid->RowIndex ?>_scat_id" name="x<?php echo $products_grid->RowIndex ?>_scat_id" value="<?php echo ew_HtmlEncode($products->scat_id->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_products_scat_id" class="form-group products_scat_id">
<select data-table="products" data-field="x_scat_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($products->scat_id->DisplayValueSeparator) ? json_encode($products->scat_id->DisplayValueSeparator) : $products->scat_id->DisplayValueSeparator) ?>" id="x<?php echo $products_grid->RowIndex ?>_scat_id" name="x<?php echo $products_grid->RowIndex ?>_scat_id"<?php echo $products->scat_id->EditAttributes() ?>>
<?php
if (is_array($products->scat_id->EditValue)) {
	$arwrk = $products->scat_id->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($products->scat_id->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
		if ($selwrk <> "" || $arwrk[$rowcntwrk][0] == "") {
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $products->scat_id->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
		}
	}
}
if (@$emptywrk) $products->scat_id->OldValue = "";
?>
</select>
<?php
$sSqlWrk = "SELECT `scat_id`, `scat_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sub_category`";
$sWhereWrk = "{filter}";
$products->scat_id->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$products->scat_id->LookupFilters += array("f0" => "`scat_id` = {filter_value}", "t0" => "3", "fn0" => "");
$products->scat_id->LookupFilters += array("f1" => "`category_id` IN ({filter_value})", "t1" => "3", "fn1" => "");
$sSqlWrk = "";
$products->Lookup_Selecting($products->scat_id, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $products->scat_id->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x<?php echo $products_grid->RowIndex ?>_scat_id" id="s_x<?php echo $products_grid->RowIndex ?>_scat_id" value="<?php echo $products->scat_id->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_products_scat_id" class="form-group products_scat_id">
<span<?php echo $products->scat_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $products->scat_id->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="products" data-field="x_scat_id" name="x<?php echo $products_grid->RowIndex ?>_scat_id" id="x<?php echo $products_grid->RowIndex ?>_scat_id" value="<?php echo ew_HtmlEncode($products->scat_id->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="products" data-field="x_scat_id" name="o<?php echo $products_grid->RowIndex ?>_scat_id" id="o<?php echo $products_grid->RowIndex ?>_scat_id" value="<?php echo ew_HtmlEncode($products->scat_id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($products->product_name->Visible) { // product_name ?>
		<td data-name="product_name">
<?php if ($products->CurrentAction <> "F") { ?>
<span id="el$rowindex$_products_product_name" class="form-group products_product_name">
<input type="text" data-table="products" data-field="x_product_name" name="x<?php echo $products_grid->RowIndex ?>_product_name" id="x<?php echo $products_grid->RowIndex ?>_product_name" placeholder="<?php echo ew_HtmlEncode($products->product_name->getPlaceHolder()) ?>" value="<?php echo $products->product_name->EditValue ?>"<?php echo $products->product_name->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_products_product_name" class="form-group products_product_name">
<span<?php echo $products->product_name->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $products->product_name->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="products" data-field="x_product_name" name="x<?php echo $products_grid->RowIndex ?>_product_name" id="x<?php echo $products_grid->RowIndex ?>_product_name" value="<?php echo ew_HtmlEncode($products->product_name->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="products" data-field="x_product_name" name="o<?php echo $products_grid->RowIndex ?>_product_name" id="o<?php echo $products_grid->RowIndex ?>_product_name" value="<?php echo ew_HtmlEncode($products->product_name->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($products->product_image->Visible) { // product_image ?>
		<td data-name="product_image">
<span id="el$rowindex$_products_product_image" class="form-group products_product_image">
<div id="fd_x<?php echo $products_grid->RowIndex ?>_product_image">
<span title="<?php echo $products->product_image->FldTitle() ? $products->product_image->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($products->product_image->ReadOnly || $products->product_image->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="products" data-field="x_product_image" name="x<?php echo $products_grid->RowIndex ?>_product_image" id="x<?php echo $products_grid->RowIndex ?>_product_image"<?php echo $products->product_image->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x<?php echo $products_grid->RowIndex ?>_product_image" id= "fn_x<?php echo $products_grid->RowIndex ?>_product_image" value="<?php echo $products->product_image->Upload->FileName ?>">
<input type="hidden" name="fa_x<?php echo $products_grid->RowIndex ?>_product_image" id= "fa_x<?php echo $products_grid->RowIndex ?>_product_image" value="0">
<input type="hidden" name="fs_x<?php echo $products_grid->RowIndex ?>_product_image" id= "fs_x<?php echo $products_grid->RowIndex ?>_product_image" value="200">
<input type="hidden" name="fx_x<?php echo $products_grid->RowIndex ?>_product_image" id= "fx_x<?php echo $products_grid->RowIndex ?>_product_image" value="<?php echo $products->product_image->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?php echo $products_grid->RowIndex ?>_product_image" id= "fm_x<?php echo $products_grid->RowIndex ?>_product_image" value="<?php echo $products->product_image->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?php echo $products_grid->RowIndex ?>_product_image" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<input type="hidden" data-table="products" data-field="x_product_image" name="o<?php echo $products_grid->RowIndex ?>_product_image" id="o<?php echo $products_grid->RowIndex ?>_product_image" value="<?php echo ew_HtmlEncode($products->product_image->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($products->product_secimage->Visible) { // product_secimage ?>
		<td data-name="product_secimage">
<span id="el$rowindex$_products_product_secimage" class="form-group products_product_secimage">
<div id="fd_x<?php echo $products_grid->RowIndex ?>_product_secimage">
<span title="<?php echo $products->product_secimage->FldTitle() ? $products->product_secimage->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($products->product_secimage->ReadOnly || $products->product_secimage->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="products" data-field="x_product_secimage" name="x<?php echo $products_grid->RowIndex ?>_product_secimage" id="x<?php echo $products_grid->RowIndex ?>_product_secimage"<?php echo $products->product_secimage->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x<?php echo $products_grid->RowIndex ?>_product_secimage" id= "fn_x<?php echo $products_grid->RowIndex ?>_product_secimage" value="<?php echo $products->product_secimage->Upload->FileName ?>">
<input type="hidden" name="fa_x<?php echo $products_grid->RowIndex ?>_product_secimage" id= "fa_x<?php echo $products_grid->RowIndex ?>_product_secimage" value="0">
<input type="hidden" name="fs_x<?php echo $products_grid->RowIndex ?>_product_secimage" id= "fs_x<?php echo $products_grid->RowIndex ?>_product_secimage" value="200">
<input type="hidden" name="fx_x<?php echo $products_grid->RowIndex ?>_product_secimage" id= "fx_x<?php echo $products_grid->RowIndex ?>_product_secimage" value="<?php echo $products->product_secimage->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?php echo $products_grid->RowIndex ?>_product_secimage" id= "fm_x<?php echo $products_grid->RowIndex ?>_product_secimage" value="<?php echo $products->product_secimage->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?php echo $products_grid->RowIndex ?>_product_secimage" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<input type="hidden" data-table="products" data-field="x_product_secimage" name="o<?php echo $products_grid->RowIndex ?>_product_secimage" id="o<?php echo $products_grid->RowIndex ?>_product_secimage" value="<?php echo ew_HtmlEncode($products->product_secimage->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$products_grid->ListOptions->Render("body", "right", $products_grid->RowCnt);
?>
<script type="text/javascript">
fproductsgrid.UpdateOpts(<?php echo $products_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($products->CurrentMode == "add" || $products->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $products_grid->FormKeyCountName ?>" id="<?php echo $products_grid->FormKeyCountName ?>" value="<?php echo $products_grid->KeyCount ?>">
<?php echo $products_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($products->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $products_grid->FormKeyCountName ?>" id="<?php echo $products_grid->FormKeyCountName ?>" value="<?php echo $products_grid->KeyCount ?>">
<?php echo $products_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($products->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fproductsgrid">
</div>
<?php

// Close recordset
if ($products_grid->Recordset)
	$products_grid->Recordset->Close();
?>
<?php if ($products_grid->ShowOtherOptions) { ?>
<div class="panel-footer ewGridLowerPanel">
<?php
	foreach ($products_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($products_grid->TotalRecs == 0 && $products->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($products_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($products->Export == "") { ?>
<script type="text/javascript">
fproductsgrid.Init();
</script>
<?php } ?>
<?php
$products_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$products_grid->Page_Terminate();
?>
