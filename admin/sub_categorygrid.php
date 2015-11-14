<?php

// Create page object
if (!isset($sub_category_grid)) $sub_category_grid = new csub_category_grid();

// Page init
$sub_category_grid->Page_Init();

// Page main
$sub_category_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$sub_category_grid->Page_Render();
?>
<?php if ($sub_category->Export == "") { ?>
<script type="text/javascript">

// Form object
var fsub_categorygrid = new ew_Form("fsub_categorygrid", "grid");
fsub_categorygrid.FormKeyCountName = '<?php echo $sub_category_grid->FormKeyCountName ?>';

// Validate form
fsub_categorygrid.Validate = function() {
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
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $sub_category->category_id->FldCaption(), $sub_category->category_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_scat_name");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $sub_category->scat_name->FldCaption(), $sub_category->scat_name->ReqErrMsg)) ?>");
			felm = this.GetElements("x" + infix + "_scat_picture");
			elm = this.GetElements("fn_x" + infix + "_scat_picture");
			if (felm && elm && !ew_HasValue(elm))
				return this.OnError(felm, "<?php echo ew_JsEncode2(str_replace("%s", $sub_category->scat_picture->FldCaption(), $sub_category->scat_picture->ReqErrMsg)) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	return true;
}

// Check empty row
fsub_categorygrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "category_id", false)) return false;
	if (ew_ValueChanged(fobj, infix, "scat_name", false)) return false;
	if (ew_ValueChanged(fobj, infix, "scat_picture", false)) return false;
	return true;
}

// Form_CustomValidate event
fsub_categorygrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fsub_categorygrid.ValidateRequired = true;
<?php } else { ?>
fsub_categorygrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fsub_categorygrid.Lists["x_category_id"] = {"LinkField":"x_category_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_category_name","","",""],"ParentFields":[],"ChildFields":["products x_category_id"],"FilterFields":[],"Options":[],"Template":""};

// Form object for search
</script>
<?php } ?>
<?php
if ($sub_category->CurrentAction == "gridadd") {
	if ($sub_category->CurrentMode == "copy") {
		$bSelectLimit = $sub_category_grid->UseSelectLimit;
		if ($bSelectLimit) {
			$sub_category_grid->TotalRecs = $sub_category->SelectRecordCount();
			$sub_category_grid->Recordset = $sub_category_grid->LoadRecordset($sub_category_grid->StartRec-1, $sub_category_grid->DisplayRecs);
		} else {
			if ($sub_category_grid->Recordset = $sub_category_grid->LoadRecordset())
				$sub_category_grid->TotalRecs = $sub_category_grid->Recordset->RecordCount();
		}
		$sub_category_grid->StartRec = 1;
		$sub_category_grid->DisplayRecs = $sub_category_grid->TotalRecs;
	} else {
		$sub_category->CurrentFilter = "0=1";
		$sub_category_grid->StartRec = 1;
		$sub_category_grid->DisplayRecs = $sub_category->GridAddRowCount;
	}
	$sub_category_grid->TotalRecs = $sub_category_grid->DisplayRecs;
	$sub_category_grid->StopRec = $sub_category_grid->DisplayRecs;
} else {
	$bSelectLimit = $sub_category_grid->UseSelectLimit;
	if ($bSelectLimit) {
		if ($sub_category_grid->TotalRecs <= 0)
			$sub_category_grid->TotalRecs = $sub_category->SelectRecordCount();
	} else {
		if (!$sub_category_grid->Recordset && ($sub_category_grid->Recordset = $sub_category_grid->LoadRecordset()))
			$sub_category_grid->TotalRecs = $sub_category_grid->Recordset->RecordCount();
	}
	$sub_category_grid->StartRec = 1;
	$sub_category_grid->DisplayRecs = $sub_category_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$sub_category_grid->Recordset = $sub_category_grid->LoadRecordset($sub_category_grid->StartRec-1, $sub_category_grid->DisplayRecs);

	// Set no record found message
	if ($sub_category->CurrentAction == "" && $sub_category_grid->TotalRecs == 0) {
		if ($sub_category_grid->SearchWhere == "0=101")
			$sub_category_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$sub_category_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$sub_category_grid->RenderOtherOptions();
?>
<?php $sub_category_grid->ShowPageHeader(); ?>
<?php
$sub_category_grid->ShowMessage();
?>
<?php if ($sub_category_grid->TotalRecs > 0 || $sub_category->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid">
<div id="fsub_categorygrid" class="ewForm form-inline">
<div id="gmp_sub_category" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_sub_categorygrid" class="table ewTable">
<?php echo $sub_category->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$sub_category_grid->RowType = EW_ROWTYPE_HEADER;

// Render list options
$sub_category_grid->RenderListOptions();

// Render list options (header, left)
$sub_category_grid->ListOptions->Render("header", "left");
?>
<?php if ($sub_category->category_id->Visible) { // category_id ?>
	<?php if ($sub_category->SortUrl($sub_category->category_id) == "") { ?>
		<th data-name="category_id"><div id="elh_sub_category_category_id" class="sub_category_category_id"><div class="ewTableHeaderCaption"><?php echo $sub_category->category_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="category_id"><div><div id="elh_sub_category_category_id" class="sub_category_category_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $sub_category->category_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($sub_category->category_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($sub_category->category_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($sub_category->scat_name->Visible) { // scat_name ?>
	<?php if ($sub_category->SortUrl($sub_category->scat_name) == "") { ?>
		<th data-name="scat_name"><div id="elh_sub_category_scat_name" class="sub_category_scat_name"><div class="ewTableHeaderCaption"><?php echo $sub_category->scat_name->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="scat_name"><div><div id="elh_sub_category_scat_name" class="sub_category_scat_name">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $sub_category->scat_name->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($sub_category->scat_name->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($sub_category->scat_name->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($sub_category->scat_picture->Visible) { // scat_picture ?>
	<?php if ($sub_category->SortUrl($sub_category->scat_picture) == "") { ?>
		<th data-name="scat_picture"><div id="elh_sub_category_scat_picture" class="sub_category_scat_picture"><div class="ewTableHeaderCaption"><?php echo $sub_category->scat_picture->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="scat_picture"><div><div id="elh_sub_category_scat_picture" class="sub_category_scat_picture">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $sub_category->scat_picture->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($sub_category->scat_picture->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($sub_category->scat_picture->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$sub_category_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$sub_category_grid->StartRec = 1;
$sub_category_grid->StopRec = $sub_category_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($sub_category_grid->FormKeyCountName) && ($sub_category->CurrentAction == "gridadd" || $sub_category->CurrentAction == "gridedit" || $sub_category->CurrentAction == "F")) {
		$sub_category_grid->KeyCount = $objForm->GetValue($sub_category_grid->FormKeyCountName);
		$sub_category_grid->StopRec = $sub_category_grid->StartRec + $sub_category_grid->KeyCount - 1;
	}
}
$sub_category_grid->RecCnt = $sub_category_grid->StartRec - 1;
if ($sub_category_grid->Recordset && !$sub_category_grid->Recordset->EOF) {
	$sub_category_grid->Recordset->MoveFirst();
	$bSelectLimit = $sub_category_grid->UseSelectLimit;
	if (!$bSelectLimit && $sub_category_grid->StartRec > 1)
		$sub_category_grid->Recordset->Move($sub_category_grid->StartRec - 1);
} elseif (!$sub_category->AllowAddDeleteRow && $sub_category_grid->StopRec == 0) {
	$sub_category_grid->StopRec = $sub_category->GridAddRowCount;
}

// Initialize aggregate
$sub_category->RowType = EW_ROWTYPE_AGGREGATEINIT;
$sub_category->ResetAttrs();
$sub_category_grid->RenderRow();
if ($sub_category->CurrentAction == "gridadd")
	$sub_category_grid->RowIndex = 0;
if ($sub_category->CurrentAction == "gridedit")
	$sub_category_grid->RowIndex = 0;
while ($sub_category_grid->RecCnt < $sub_category_grid->StopRec) {
	$sub_category_grid->RecCnt++;
	if (intval($sub_category_grid->RecCnt) >= intval($sub_category_grid->StartRec)) {
		$sub_category_grid->RowCnt++;
		if ($sub_category->CurrentAction == "gridadd" || $sub_category->CurrentAction == "gridedit" || $sub_category->CurrentAction == "F") {
			$sub_category_grid->RowIndex++;
			$objForm->Index = $sub_category_grid->RowIndex;
			if ($objForm->HasValue($sub_category_grid->FormActionName))
				$sub_category_grid->RowAction = strval($objForm->GetValue($sub_category_grid->FormActionName));
			elseif ($sub_category->CurrentAction == "gridadd")
				$sub_category_grid->RowAction = "insert";
			else
				$sub_category_grid->RowAction = "";
		}

		// Set up key count
		$sub_category_grid->KeyCount = $sub_category_grid->RowIndex;

		// Init row class and style
		$sub_category->ResetAttrs();
		$sub_category->CssClass = "";
		if ($sub_category->CurrentAction == "gridadd") {
			if ($sub_category->CurrentMode == "copy") {
				$sub_category_grid->LoadRowValues($sub_category_grid->Recordset); // Load row values
				$sub_category_grid->SetRecordKey($sub_category_grid->RowOldKey, $sub_category_grid->Recordset); // Set old record key
			} else {
				$sub_category_grid->LoadDefaultValues(); // Load default values
				$sub_category_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$sub_category_grid->LoadRowValues($sub_category_grid->Recordset); // Load row values
		}
		$sub_category->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($sub_category->CurrentAction == "gridadd") // Grid add
			$sub_category->RowType = EW_ROWTYPE_ADD; // Render add
		if ($sub_category->CurrentAction == "gridadd" && $sub_category->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$sub_category_grid->RestoreCurrentRowFormValues($sub_category_grid->RowIndex); // Restore form values
		if ($sub_category->CurrentAction == "gridedit") { // Grid edit
			if ($sub_category->EventCancelled) {
				$sub_category_grid->RestoreCurrentRowFormValues($sub_category_grid->RowIndex); // Restore form values
			}
			if ($sub_category_grid->RowAction == "insert")
				$sub_category->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$sub_category->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($sub_category->CurrentAction == "gridedit" && ($sub_category->RowType == EW_ROWTYPE_EDIT || $sub_category->RowType == EW_ROWTYPE_ADD) && $sub_category->EventCancelled) // Update failed
			$sub_category_grid->RestoreCurrentRowFormValues($sub_category_grid->RowIndex); // Restore form values
		if ($sub_category->RowType == EW_ROWTYPE_EDIT) // Edit row
			$sub_category_grid->EditRowCnt++;
		if ($sub_category->CurrentAction == "F") // Confirm row
			$sub_category_grid->RestoreCurrentRowFormValues($sub_category_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$sub_category->RowAttrs = array_merge($sub_category->RowAttrs, array('data-rowindex'=>$sub_category_grid->RowCnt, 'id'=>'r' . $sub_category_grid->RowCnt . '_sub_category', 'data-rowtype'=>$sub_category->RowType));

		// Render row
		$sub_category_grid->RenderRow();

		// Render list options
		$sub_category_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($sub_category_grid->RowAction <> "delete" && $sub_category_grid->RowAction <> "insertdelete" && !($sub_category_grid->RowAction == "insert" && $sub_category->CurrentAction == "F" && $sub_category_grid->EmptyRow())) {
?>
	<tr<?php echo $sub_category->RowAttributes() ?>>
<?php

// Render list options (body, left)
$sub_category_grid->ListOptions->Render("body", "left", $sub_category_grid->RowCnt);
?>
	<?php if ($sub_category->category_id->Visible) { // category_id ?>
		<td data-name="category_id"<?php echo $sub_category->category_id->CellAttributes() ?>>
<?php if ($sub_category->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($sub_category->category_id->getSessionValue() <> "") { ?>
<span id="el<?php echo $sub_category_grid->RowCnt ?>_sub_category_category_id" class="form-group sub_category_category_id">
<span<?php echo $sub_category->category_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $sub_category->category_id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $sub_category_grid->RowIndex ?>_category_id" name="x<?php echo $sub_category_grid->RowIndex ?>_category_id" value="<?php echo ew_HtmlEncode($sub_category->category_id->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $sub_category_grid->RowCnt ?>_sub_category_category_id" class="form-group sub_category_category_id">
<?php $sub_category->category_id->EditAttrs["onchange"] = "ew_UpdateOpt.call(this); " . @$sub_category->category_id->EditAttrs["onchange"]; ?>
<select data-table="sub_category" data-field="x_category_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($sub_category->category_id->DisplayValueSeparator) ? json_encode($sub_category->category_id->DisplayValueSeparator) : $sub_category->category_id->DisplayValueSeparator) ?>" id="x<?php echo $sub_category_grid->RowIndex ?>_category_id" name="x<?php echo $sub_category_grid->RowIndex ?>_category_id"<?php echo $sub_category->category_id->EditAttributes() ?>>
<?php
if (is_array($sub_category->category_id->EditValue)) {
	$arwrk = $sub_category->category_id->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($sub_category->category_id->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
		if ($selwrk <> "" || $arwrk[$rowcntwrk][0] == "") {
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $sub_category->category_id->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
		}
	}
}
if (@$emptywrk) $sub_category->category_id->OldValue = "";
?>
</select>
<?php
$sSqlWrk = "SELECT `category_id`, `category_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `category`";
$sWhereWrk = "";
$sub_category->category_id->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$sub_category->category_id->LookupFilters += array("f0" => "`category_id` = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$sub_category->Lookup_Selecting($sub_category->category_id, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $sub_category->category_id->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x<?php echo $sub_category_grid->RowIndex ?>_category_id" id="s_x<?php echo $sub_category_grid->RowIndex ?>_category_id" value="<?php echo $sub_category->category_id->LookupFilterQuery() ?>">
</span>
<?php } ?>
<input type="hidden" data-table="sub_category" data-field="x_category_id" name="o<?php echo $sub_category_grid->RowIndex ?>_category_id" id="o<?php echo $sub_category_grid->RowIndex ?>_category_id" value="<?php echo ew_HtmlEncode($sub_category->category_id->OldValue) ?>">
<?php } ?>
<?php if ($sub_category->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($sub_category->category_id->getSessionValue() <> "") { ?>
<span id="el<?php echo $sub_category_grid->RowCnt ?>_sub_category_category_id" class="form-group sub_category_category_id">
<span<?php echo $sub_category->category_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $sub_category->category_id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $sub_category_grid->RowIndex ?>_category_id" name="x<?php echo $sub_category_grid->RowIndex ?>_category_id" value="<?php echo ew_HtmlEncode($sub_category->category_id->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $sub_category_grid->RowCnt ?>_sub_category_category_id" class="form-group sub_category_category_id">
<select data-table="sub_category" data-field="x_category_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($sub_category->category_id->DisplayValueSeparator) ? json_encode($sub_category->category_id->DisplayValueSeparator) : $sub_category->category_id->DisplayValueSeparator) ?>" id="x<?php echo $sub_category_grid->RowIndex ?>_category_id" name="x<?php echo $sub_category_grid->RowIndex ?>_category_id"<?php echo $sub_category->category_id->EditAttributes() ?>>
<?php
if (is_array($sub_category->category_id->EditValue)) {
	$arwrk = $sub_category->category_id->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($sub_category->category_id->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
		if ($selwrk <> "" || $arwrk[$rowcntwrk][0] == "") {
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $sub_category->category_id->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
		}
	}
}
if (@$emptywrk) $sub_category->category_id->OldValue = "";
?>
</select>
<?php
$sSqlWrk = "SELECT `category_id`, `category_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `category`";
$sWhereWrk = "";
$sub_category->category_id->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$sub_category->category_id->LookupFilters += array("f0" => "`category_id` = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$sub_category->Lookup_Selecting($sub_category->category_id, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $sub_category->category_id->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x<?php echo $sub_category_grid->RowIndex ?>_category_id" id="s_x<?php echo $sub_category_grid->RowIndex ?>_category_id" value="<?php echo $sub_category->category_id->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php } ?>
<?php if ($sub_category->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $sub_category_grid->RowCnt ?>_sub_category_category_id" class="sub_category_category_id">
<span<?php echo $sub_category->category_id->ViewAttributes() ?>>
<?php echo $sub_category->category_id->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="sub_category" data-field="x_category_id" name="x<?php echo $sub_category_grid->RowIndex ?>_category_id" id="x<?php echo $sub_category_grid->RowIndex ?>_category_id" value="<?php echo ew_HtmlEncode($sub_category->category_id->FormValue) ?>">
<input type="hidden" data-table="sub_category" data-field="x_category_id" name="o<?php echo $sub_category_grid->RowIndex ?>_category_id" id="o<?php echo $sub_category_grid->RowIndex ?>_category_id" value="<?php echo ew_HtmlEncode($sub_category->category_id->OldValue) ?>">
<?php } ?>
<a id="<?php echo $sub_category_grid->PageObjName . "_row_" . $sub_category_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($sub_category->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="sub_category" data-field="x_scat_id" name="x<?php echo $sub_category_grid->RowIndex ?>_scat_id" id="x<?php echo $sub_category_grid->RowIndex ?>_scat_id" value="<?php echo ew_HtmlEncode($sub_category->scat_id->CurrentValue) ?>">
<input type="hidden" data-table="sub_category" data-field="x_scat_id" name="o<?php echo $sub_category_grid->RowIndex ?>_scat_id" id="o<?php echo $sub_category_grid->RowIndex ?>_scat_id" value="<?php echo ew_HtmlEncode($sub_category->scat_id->OldValue) ?>">
<?php } ?>
<?php if ($sub_category->RowType == EW_ROWTYPE_EDIT || $sub_category->CurrentMode == "edit") { ?>
<input type="hidden" data-table="sub_category" data-field="x_scat_id" name="x<?php echo $sub_category_grid->RowIndex ?>_scat_id" id="x<?php echo $sub_category_grid->RowIndex ?>_scat_id" value="<?php echo ew_HtmlEncode($sub_category->scat_id->CurrentValue) ?>">
<?php } ?>
	<?php if ($sub_category->scat_name->Visible) { // scat_name ?>
		<td data-name="scat_name"<?php echo $sub_category->scat_name->CellAttributes() ?>>
<?php if ($sub_category->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $sub_category_grid->RowCnt ?>_sub_category_scat_name" class="form-group sub_category_scat_name">
<textarea data-table="sub_category" data-field="x_scat_name" name="x<?php echo $sub_category_grid->RowIndex ?>_scat_name" id="x<?php echo $sub_category_grid->RowIndex ?>_scat_name" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($sub_category->scat_name->getPlaceHolder()) ?>"<?php echo $sub_category->scat_name->EditAttributes() ?>><?php echo $sub_category->scat_name->EditValue ?></textarea>
</span>
<input type="hidden" data-table="sub_category" data-field="x_scat_name" name="o<?php echo $sub_category_grid->RowIndex ?>_scat_name" id="o<?php echo $sub_category_grid->RowIndex ?>_scat_name" value="<?php echo ew_HtmlEncode($sub_category->scat_name->OldValue) ?>">
<?php } ?>
<?php if ($sub_category->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $sub_category_grid->RowCnt ?>_sub_category_scat_name" class="form-group sub_category_scat_name">
<textarea data-table="sub_category" data-field="x_scat_name" name="x<?php echo $sub_category_grid->RowIndex ?>_scat_name" id="x<?php echo $sub_category_grid->RowIndex ?>_scat_name" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($sub_category->scat_name->getPlaceHolder()) ?>"<?php echo $sub_category->scat_name->EditAttributes() ?>><?php echo $sub_category->scat_name->EditValue ?></textarea>
</span>
<?php } ?>
<?php if ($sub_category->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $sub_category_grid->RowCnt ?>_sub_category_scat_name" class="sub_category_scat_name">
<span<?php echo $sub_category->scat_name->ViewAttributes() ?>>
<?php echo $sub_category->scat_name->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="sub_category" data-field="x_scat_name" name="x<?php echo $sub_category_grid->RowIndex ?>_scat_name" id="x<?php echo $sub_category_grid->RowIndex ?>_scat_name" value="<?php echo ew_HtmlEncode($sub_category->scat_name->FormValue) ?>">
<input type="hidden" data-table="sub_category" data-field="x_scat_name" name="o<?php echo $sub_category_grid->RowIndex ?>_scat_name" id="o<?php echo $sub_category_grid->RowIndex ?>_scat_name" value="<?php echo ew_HtmlEncode($sub_category->scat_name->OldValue) ?>">
<?php } ?>
</td>
	<?php } ?>
	<?php if ($sub_category->scat_picture->Visible) { // scat_picture ?>
		<td data-name="scat_picture"<?php echo $sub_category->scat_picture->CellAttributes() ?>>
<?php if ($sub_category_grid->RowAction == "insert") { // Add record ?>
<span id="el$rowindex$_sub_category_scat_picture" class="form-group sub_category_scat_picture">
<div id="fd_x<?php echo $sub_category_grid->RowIndex ?>_scat_picture">
<span title="<?php echo $sub_category->scat_picture->FldTitle() ? $sub_category->scat_picture->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($sub_category->scat_picture->ReadOnly || $sub_category->scat_picture->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="sub_category" data-field="x_scat_picture" name="x<?php echo $sub_category_grid->RowIndex ?>_scat_picture" id="x<?php echo $sub_category_grid->RowIndex ?>_scat_picture"<?php echo $sub_category->scat_picture->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x<?php echo $sub_category_grid->RowIndex ?>_scat_picture" id= "fn_x<?php echo $sub_category_grid->RowIndex ?>_scat_picture" value="<?php echo $sub_category->scat_picture->Upload->FileName ?>">
<input type="hidden" name="fa_x<?php echo $sub_category_grid->RowIndex ?>_scat_picture" id= "fa_x<?php echo $sub_category_grid->RowIndex ?>_scat_picture" value="0">
<input type="hidden" name="fs_x<?php echo $sub_category_grid->RowIndex ?>_scat_picture" id= "fs_x<?php echo $sub_category_grid->RowIndex ?>_scat_picture" value="200">
<input type="hidden" name="fx_x<?php echo $sub_category_grid->RowIndex ?>_scat_picture" id= "fx_x<?php echo $sub_category_grid->RowIndex ?>_scat_picture" value="<?php echo $sub_category->scat_picture->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?php echo $sub_category_grid->RowIndex ?>_scat_picture" id= "fm_x<?php echo $sub_category_grid->RowIndex ?>_scat_picture" value="<?php echo $sub_category->scat_picture->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?php echo $sub_category_grid->RowIndex ?>_scat_picture" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<input type="hidden" data-table="sub_category" data-field="x_scat_picture" name="o<?php echo $sub_category_grid->RowIndex ?>_scat_picture" id="o<?php echo $sub_category_grid->RowIndex ?>_scat_picture" value="<?php echo ew_HtmlEncode($sub_category->scat_picture->OldValue) ?>">
<?php } elseif ($sub_category->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $sub_category_grid->RowCnt ?>_sub_category_scat_picture" class="sub_category_scat_picture">
<span<?php echo $sub_category->scat_picture->ViewAttributes() ?>>
<?php echo ew_GetFileViewTag($sub_category->scat_picture, $sub_category->scat_picture->ListViewValue()) ?>
</span>
</span>
<?php } else  { // Edit record ?>
<span id="el<?php echo $sub_category_grid->RowCnt ?>_sub_category_scat_picture" class="form-group sub_category_scat_picture">
<div id="fd_x<?php echo $sub_category_grid->RowIndex ?>_scat_picture">
<span title="<?php echo $sub_category->scat_picture->FldTitle() ? $sub_category->scat_picture->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($sub_category->scat_picture->ReadOnly || $sub_category->scat_picture->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="sub_category" data-field="x_scat_picture" name="x<?php echo $sub_category_grid->RowIndex ?>_scat_picture" id="x<?php echo $sub_category_grid->RowIndex ?>_scat_picture"<?php echo $sub_category->scat_picture->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x<?php echo $sub_category_grid->RowIndex ?>_scat_picture" id= "fn_x<?php echo $sub_category_grid->RowIndex ?>_scat_picture" value="<?php echo $sub_category->scat_picture->Upload->FileName ?>">
<?php if (@$_POST["fa_x<?php echo $sub_category_grid->RowIndex ?>_scat_picture"] == "0") { ?>
<input type="hidden" name="fa_x<?php echo $sub_category_grid->RowIndex ?>_scat_picture" id= "fa_x<?php echo $sub_category_grid->RowIndex ?>_scat_picture" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x<?php echo $sub_category_grid->RowIndex ?>_scat_picture" id= "fa_x<?php echo $sub_category_grid->RowIndex ?>_scat_picture" value="1">
<?php } ?>
<input type="hidden" name="fs_x<?php echo $sub_category_grid->RowIndex ?>_scat_picture" id= "fs_x<?php echo $sub_category_grid->RowIndex ?>_scat_picture" value="200">
<input type="hidden" name="fx_x<?php echo $sub_category_grid->RowIndex ?>_scat_picture" id= "fx_x<?php echo $sub_category_grid->RowIndex ?>_scat_picture" value="<?php echo $sub_category->scat_picture->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?php echo $sub_category_grid->RowIndex ?>_scat_picture" id= "fm_x<?php echo $sub_category_grid->RowIndex ?>_scat_picture" value="<?php echo $sub_category->scat_picture->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?php echo $sub_category_grid->RowIndex ?>_scat_picture" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$sub_category_grid->ListOptions->Render("body", "right", $sub_category_grid->RowCnt);
?>
	</tr>
<?php if ($sub_category->RowType == EW_ROWTYPE_ADD || $sub_category->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fsub_categorygrid.UpdateOpts(<?php echo $sub_category_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($sub_category->CurrentAction <> "gridadd" || $sub_category->CurrentMode == "copy")
		if (!$sub_category_grid->Recordset->EOF) $sub_category_grid->Recordset->MoveNext();
}
?>
<?php
	if ($sub_category->CurrentMode == "add" || $sub_category->CurrentMode == "copy" || $sub_category->CurrentMode == "edit") {
		$sub_category_grid->RowIndex = '$rowindex$';
		$sub_category_grid->LoadDefaultValues();

		// Set row properties
		$sub_category->ResetAttrs();
		$sub_category->RowAttrs = array_merge($sub_category->RowAttrs, array('data-rowindex'=>$sub_category_grid->RowIndex, 'id'=>'r0_sub_category', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($sub_category->RowAttrs["class"], "ewTemplate");
		$sub_category->RowType = EW_ROWTYPE_ADD;

		// Render row
		$sub_category_grid->RenderRow();

		// Render list options
		$sub_category_grid->RenderListOptions();
		$sub_category_grid->StartRowCnt = 0;
?>
	<tr<?php echo $sub_category->RowAttributes() ?>>
<?php

// Render list options (body, left)
$sub_category_grid->ListOptions->Render("body", "left", $sub_category_grid->RowIndex);
?>
	<?php if ($sub_category->category_id->Visible) { // category_id ?>
		<td data-name="category_id">
<?php if ($sub_category->CurrentAction <> "F") { ?>
<?php if ($sub_category->category_id->getSessionValue() <> "") { ?>
<span id="el$rowindex$_sub_category_category_id" class="form-group sub_category_category_id">
<span<?php echo $sub_category->category_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $sub_category->category_id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $sub_category_grid->RowIndex ?>_category_id" name="x<?php echo $sub_category_grid->RowIndex ?>_category_id" value="<?php echo ew_HtmlEncode($sub_category->category_id->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_sub_category_category_id" class="form-group sub_category_category_id">
<?php $sub_category->category_id->EditAttrs["onchange"] = "ew_UpdateOpt.call(this); " . @$sub_category->category_id->EditAttrs["onchange"]; ?>
<select data-table="sub_category" data-field="x_category_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($sub_category->category_id->DisplayValueSeparator) ? json_encode($sub_category->category_id->DisplayValueSeparator) : $sub_category->category_id->DisplayValueSeparator) ?>" id="x<?php echo $sub_category_grid->RowIndex ?>_category_id" name="x<?php echo $sub_category_grid->RowIndex ?>_category_id"<?php echo $sub_category->category_id->EditAttributes() ?>>
<?php
if (is_array($sub_category->category_id->EditValue)) {
	$arwrk = $sub_category->category_id->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($sub_category->category_id->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
		if ($selwrk <> "" || $arwrk[$rowcntwrk][0] == "") {
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $sub_category->category_id->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
		}
	}
}
if (@$emptywrk) $sub_category->category_id->OldValue = "";
?>
</select>
<?php
$sSqlWrk = "SELECT `category_id`, `category_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `category`";
$sWhereWrk = "";
$sub_category->category_id->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$sub_category->category_id->LookupFilters += array("f0" => "`category_id` = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$sub_category->Lookup_Selecting($sub_category->category_id, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $sub_category->category_id->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x<?php echo $sub_category_grid->RowIndex ?>_category_id" id="s_x<?php echo $sub_category_grid->RowIndex ?>_category_id" value="<?php echo $sub_category->category_id->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_sub_category_category_id" class="form-group sub_category_category_id">
<span<?php echo $sub_category->category_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $sub_category->category_id->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="sub_category" data-field="x_category_id" name="x<?php echo $sub_category_grid->RowIndex ?>_category_id" id="x<?php echo $sub_category_grid->RowIndex ?>_category_id" value="<?php echo ew_HtmlEncode($sub_category->category_id->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="sub_category" data-field="x_category_id" name="o<?php echo $sub_category_grid->RowIndex ?>_category_id" id="o<?php echo $sub_category_grid->RowIndex ?>_category_id" value="<?php echo ew_HtmlEncode($sub_category->category_id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($sub_category->scat_name->Visible) { // scat_name ?>
		<td data-name="scat_name">
<?php if ($sub_category->CurrentAction <> "F") { ?>
<span id="el$rowindex$_sub_category_scat_name" class="form-group sub_category_scat_name">
<textarea data-table="sub_category" data-field="x_scat_name" name="x<?php echo $sub_category_grid->RowIndex ?>_scat_name" id="x<?php echo $sub_category_grid->RowIndex ?>_scat_name" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($sub_category->scat_name->getPlaceHolder()) ?>"<?php echo $sub_category->scat_name->EditAttributes() ?>><?php echo $sub_category->scat_name->EditValue ?></textarea>
</span>
<?php } else { ?>
<span id="el$rowindex$_sub_category_scat_name" class="form-group sub_category_scat_name">
<span<?php echo $sub_category->scat_name->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $sub_category->scat_name->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="sub_category" data-field="x_scat_name" name="x<?php echo $sub_category_grid->RowIndex ?>_scat_name" id="x<?php echo $sub_category_grid->RowIndex ?>_scat_name" value="<?php echo ew_HtmlEncode($sub_category->scat_name->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="sub_category" data-field="x_scat_name" name="o<?php echo $sub_category_grid->RowIndex ?>_scat_name" id="o<?php echo $sub_category_grid->RowIndex ?>_scat_name" value="<?php echo ew_HtmlEncode($sub_category->scat_name->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($sub_category->scat_picture->Visible) { // scat_picture ?>
		<td data-name="scat_picture">
<span id="el$rowindex$_sub_category_scat_picture" class="form-group sub_category_scat_picture">
<div id="fd_x<?php echo $sub_category_grid->RowIndex ?>_scat_picture">
<span title="<?php echo $sub_category->scat_picture->FldTitle() ? $sub_category->scat_picture->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($sub_category->scat_picture->ReadOnly || $sub_category->scat_picture->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="sub_category" data-field="x_scat_picture" name="x<?php echo $sub_category_grid->RowIndex ?>_scat_picture" id="x<?php echo $sub_category_grid->RowIndex ?>_scat_picture"<?php echo $sub_category->scat_picture->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x<?php echo $sub_category_grid->RowIndex ?>_scat_picture" id= "fn_x<?php echo $sub_category_grid->RowIndex ?>_scat_picture" value="<?php echo $sub_category->scat_picture->Upload->FileName ?>">
<input type="hidden" name="fa_x<?php echo $sub_category_grid->RowIndex ?>_scat_picture" id= "fa_x<?php echo $sub_category_grid->RowIndex ?>_scat_picture" value="0">
<input type="hidden" name="fs_x<?php echo $sub_category_grid->RowIndex ?>_scat_picture" id= "fs_x<?php echo $sub_category_grid->RowIndex ?>_scat_picture" value="200">
<input type="hidden" name="fx_x<?php echo $sub_category_grid->RowIndex ?>_scat_picture" id= "fx_x<?php echo $sub_category_grid->RowIndex ?>_scat_picture" value="<?php echo $sub_category->scat_picture->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?php echo $sub_category_grid->RowIndex ?>_scat_picture" id= "fm_x<?php echo $sub_category_grid->RowIndex ?>_scat_picture" value="<?php echo $sub_category->scat_picture->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?php echo $sub_category_grid->RowIndex ?>_scat_picture" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<input type="hidden" data-table="sub_category" data-field="x_scat_picture" name="o<?php echo $sub_category_grid->RowIndex ?>_scat_picture" id="o<?php echo $sub_category_grid->RowIndex ?>_scat_picture" value="<?php echo ew_HtmlEncode($sub_category->scat_picture->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$sub_category_grid->ListOptions->Render("body", "right", $sub_category_grid->RowCnt);
?>
<script type="text/javascript">
fsub_categorygrid.UpdateOpts(<?php echo $sub_category_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($sub_category->CurrentMode == "add" || $sub_category->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $sub_category_grid->FormKeyCountName ?>" id="<?php echo $sub_category_grid->FormKeyCountName ?>" value="<?php echo $sub_category_grid->KeyCount ?>">
<?php echo $sub_category_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($sub_category->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $sub_category_grid->FormKeyCountName ?>" id="<?php echo $sub_category_grid->FormKeyCountName ?>" value="<?php echo $sub_category_grid->KeyCount ?>">
<?php echo $sub_category_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($sub_category->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fsub_categorygrid">
</div>
<?php

// Close recordset
if ($sub_category_grid->Recordset)
	$sub_category_grid->Recordset->Close();
?>
<?php if ($sub_category_grid->ShowOtherOptions) { ?>
<div class="panel-footer ewGridLowerPanel">
<?php
	foreach ($sub_category_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($sub_category_grid->TotalRecs == 0 && $sub_category->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($sub_category_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($sub_category->Export == "") { ?>
<script type="text/javascript">
fsub_categorygrid.Init();
</script>
<?php } ?>
<?php
$sub_category_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$sub_category_grid->Page_Terminate();
?>
