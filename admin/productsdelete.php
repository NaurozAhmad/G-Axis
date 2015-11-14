<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "productsinfo.php" ?>
<?php include_once "sub_categoryinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$products_delete = NULL; // Initialize page object first

class cproducts_delete extends cproducts {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{C3CE8554-8FA2-42A1-89B9-3DB1F25B77B3}";

	// Table name
	var $TableName = 'products';

	// Page object name
	var $PageObjName = 'products_delete';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
		return $PageUrl;
	}

	// Message
	function getMessage() {
		return @$_SESSION[EW_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EW_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EW_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EW_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_WARNING_MESSAGE], $v);
	}

	// Methods to clear message
	function ClearMessage() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
	}

	function ClearFailureMessage() {
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
	}

	function ClearSuccessMessage() {
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
	}

	function ClearWarningMessage() {
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	function ClearMessages() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	// Show message
	function ShowMessage() {
		$hidden = FALSE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
			$html .= "<div class=\"alert alert-info ewInfo\">" . $sMessage . "</div>";
			$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
			$html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
			$_SESSION[EW_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sErrorMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sErrorMessage;
			$html .= "<div class=\"alert alert-danger ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") { // Header exists, display
			echo "<p>" . $sHeader . "</p>";
		}
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") { // Footer exists, display
			echo "<p>" . $sFooter . "</p>";
		}
	}

	// Validate page request
	function IsPageRequest() {
		global $objForm;
		if ($this->UseTokenInUrl) {
			if ($objForm)
				return ($this->TableVar == $objForm->GetValue("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == $_GET["t"]);
		} else {
			return TRUE;
		}
	}
	var $Token = "";
	var $TokenTimeout = 0;
	var $CheckToken = EW_CHECK_TOKEN;
	var $CheckTokenFn = "ew_CheckToken";
	var $CreateTokenFn = "ew_CreateToken";

	// Valid Post
	function ValidPost() {
		if (!$this->CheckToken || !ew_IsHttpPost())
			return TRUE;
		if (!isset($_POST[EW_TOKEN_NAME]))
			return FALSE;
		$fn = $this->CheckTokenFn;
		if (is_callable($fn))
			return $fn($_POST[EW_TOKEN_NAME], $this->TokenTimeout);
		return FALSE;
	}

	// Create Token
	function CreateToken() {
		global $gsToken;
		if ($this->CheckToken) {
			$fn = $this->CreateTokenFn;
			if ($this->Token == "" && is_callable($fn)) // Create token
				$this->Token = $fn();
			$gsToken = $this->Token; // Save to global variable
		}
	}

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $Language;
		$GLOBALS["Page"] = &$this;
		$this->TokenTimeout = ew_SessionTimeoutTime();

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (products)
		if (!isset($GLOBALS["products"]) || get_class($GLOBALS["products"]) == "cproducts") {
			$GLOBALS["products"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["products"];
		}

		// Table object (sub_category)
		if (!isset($GLOBALS['sub_category'])) $GLOBALS['sub_category'] = new csub_category();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'products', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect($this->DBID);
	}

	// 
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->product_id->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();

		// Check token
		if (!$this->ValidPost()) {
			echo $Language->Phrase("InvalidPostRequest");
			$this->Page_Terminate();
			exit();
		}

		// Create Token
		$this->CreateToken();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $gsExportFile, $gTmpImages;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export
		global $EW_EXPORT, $products;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($products);
				$doc->Text = $sContent;
				if ($this->Export == "email")
					echo $this->ExportEmail($doc->Text);
				else
					$doc->Export();
				ew_DeleteTmpImages(); // Delete temp images
				exit();
			}
		}
		$this->Page_Redirecting($url);

		 // Close connection
		ew_CloseConn();

		// Go to URL if specified
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			header("Location: " . $url);
		}
		exit();
	}
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $TotalRecs = 0;
	var $RecCnt;
	var $RecKeys = array();
	var $Recordset;
	var $StartRowCnt = 1;
	var $RowCnt = 0;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Set up master/detail parameters
		$this->SetUpMasterParms();

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Load key parameters
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		$sFilter = $this->GetKeyFilter();
		if ($sFilter == "")
			$this->Page_Terminate("productslist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in products class, productsinfo.php

		$this->CurrentFilter = $sFilter;

		// Get action
		if (@$_POST["a_delete"] <> "") {
			$this->CurrentAction = $_POST["a_delete"];
		} else {
			$this->CurrentAction = "I"; // Display record
		}
		switch ($this->CurrentAction) {
			case "D": // Delete
				$this->SendEmail = TRUE; // Send email on delete success
				if ($this->DeleteRows()) { // Delete rows
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("DeleteSuccess")); // Set up success message
					$this->Page_Terminate($this->getReturnUrl()); // Return to caller
				}
		}
	}

	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {

		// Load List page SQL
		$sSql = $this->SelectSQL();
		$conn = &$this->Connection();

		// Load recordset
		$dbtype = ew_GetConnectionType($this->DBID);
		if ($this->UseSelectLimit) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			if ($dbtype == "MSSQL") {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset, array("_hasOrderBy" => trim($this->getOrderBy()) || trim($this->getSessionOrderBy())));
			} else {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset);
			}
			$conn->raiseErrorFn = '';
		} else {
			$rs = ew_LoadRecordset($sSql, $conn);
		}

		// Call Recordset Selected event
		$this->Recordset_Selected($rs);
		return $rs;
	}

	// Load row based on key values
	function LoadRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();

		// Call Row Selecting event
		$this->Row_Selecting($sFilter);

		// Load SQL based on filter
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$res = FALSE;
		$rs = ew_LoadRecordset($sSql, $conn);
		if ($rs && !$rs->EOF) {
			$res = TRUE;
			$this->LoadRowValues($rs); // Load row values
			$rs->Close();
		}
		return $res;
	}

	// Load row values from recordset
	function LoadRowValues(&$rs) {
		if (!$rs || $rs->EOF) return;

		// Call Row Selected event
		$row = &$rs->fields;
		$this->Row_Selected($row);
		$this->product_id->setDbValue($rs->fields('product_id'));
		$this->category_id->setDbValue($rs->fields('category_id'));
		$this->scat_id->setDbValue($rs->fields('scat_id'));
		$this->product_name->setDbValue($rs->fields('product_name'));
		$this->product_image->Upload->DbValue = $rs->fields('product_image');
		$this->product_image->CurrentValue = $this->product_image->Upload->DbValue;
		$this->product_secimage->Upload->DbValue = $rs->fields('product_secimage');
		$this->product_secimage->CurrentValue = $this->product_secimage->Upload->DbValue;
		$this->product_description->setDbValue($rs->fields('product_description'));
		$this->feature_ledtype->setDbValue($rs->fields('feature_ledtype'));
		$this->feature_power->setDbValue($rs->fields('feature_power'));
		$this->feature_lumen->setDbValue($rs->fields('feature_lumen'));
		$this->feature_viewangle->setDbValue($rs->fields('feature_viewangle'));
		$this->feature_cri->setDbValue($rs->fields('feature_cri'));
		$this->feature_iprating->setDbValue($rs->fields('feature_iprating'));
		$this->feature_colortemp->setDbValue($rs->fields('feature_colortemp'));
		$this->feature_body->setDbValue($rs->fields('feature_body'));
		$this->feature_cutoutsize->setDbValue($rs->fields('feature_cutoutsize'));
		$this->feature_colors->setDbValue($rs->fields('feature_colors'));
		$this->feature_dimmable->setDbValue($rs->fields('feature_dimmable'));
		$this->feature_warranty->setDbValue($rs->fields('feature_warranty'));
		$this->feature_application->setDbValue($rs->fields('feature_application'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->product_id->DbValue = $row['product_id'];
		$this->category_id->DbValue = $row['category_id'];
		$this->scat_id->DbValue = $row['scat_id'];
		$this->product_name->DbValue = $row['product_name'];
		$this->product_image->Upload->DbValue = $row['product_image'];
		$this->product_secimage->Upload->DbValue = $row['product_secimage'];
		$this->product_description->DbValue = $row['product_description'];
		$this->feature_ledtype->DbValue = $row['feature_ledtype'];
		$this->feature_power->DbValue = $row['feature_power'];
		$this->feature_lumen->DbValue = $row['feature_lumen'];
		$this->feature_viewangle->DbValue = $row['feature_viewangle'];
		$this->feature_cri->DbValue = $row['feature_cri'];
		$this->feature_iprating->DbValue = $row['feature_iprating'];
		$this->feature_colortemp->DbValue = $row['feature_colortemp'];
		$this->feature_body->DbValue = $row['feature_body'];
		$this->feature_cutoutsize->DbValue = $row['feature_cutoutsize'];
		$this->feature_colors->DbValue = $row['feature_colors'];
		$this->feature_dimmable->DbValue = $row['feature_dimmable'];
		$this->feature_warranty->DbValue = $row['feature_warranty'];
		$this->feature_application->DbValue = $row['feature_application'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// product_id
		// category_id
		// scat_id
		// product_name
		// product_image
		// product_secimage
		// product_description
		// feature_ledtype
		// feature_power
		// feature_lumen
		// feature_viewangle
		// feature_cri
		// feature_iprating
		// feature_colortemp
		// feature_body
		// feature_cutoutsize
		// feature_colors
		// feature_dimmable
		// feature_warranty
		// feature_application

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// product_id
		$this->product_id->ViewValue = $this->product_id->CurrentValue;
		$this->product_id->ViewCustomAttributes = "";

		// category_id
		if (strval($this->category_id->CurrentValue) <> "") {
			$sFilterWrk = "`category_id`" . ew_SearchString("=", $this->category_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `category_id`, `category_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `category`";
		$sWhereWrk = "";
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->category_id, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->category_id->ViewValue = $this->category_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->category_id->ViewValue = $this->category_id->CurrentValue;
			}
		} else {
			$this->category_id->ViewValue = NULL;
		}
		$this->category_id->ViewCustomAttributes = "";

		// scat_id
		if (strval($this->scat_id->CurrentValue) <> "") {
			$sFilterWrk = "`scat_id`" . ew_SearchString("=", $this->scat_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `scat_id`, `scat_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `sub_category`";
		$sWhereWrk = "";
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->scat_id, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->scat_id->ViewValue = $this->scat_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->scat_id->ViewValue = $this->scat_id->CurrentValue;
			}
		} else {
			$this->scat_id->ViewValue = NULL;
		}
		$this->scat_id->ViewCustomAttributes = "";

		// product_name
		$this->product_name->ViewValue = $this->product_name->CurrentValue;
		$this->product_name->ViewCustomAttributes = "";

		// product_image
		if (!ew_Empty($this->product_image->Upload->DbValue)) {
			$this->product_image->ViewValue = $this->product_image->Upload->DbValue;
		} else {
			$this->product_image->ViewValue = "";
		}
		$this->product_image->ViewCustomAttributes = "";

		// product_secimage
		if (!ew_Empty($this->product_secimage->Upload->DbValue)) {
			$this->product_secimage->ViewValue = $this->product_secimage->Upload->DbValue;
		} else {
			$this->product_secimage->ViewValue = "";
		}
		$this->product_secimage->ViewCustomAttributes = "";

		// feature_ledtype
		$this->feature_ledtype->ViewValue = $this->feature_ledtype->CurrentValue;
		$this->feature_ledtype->ViewCustomAttributes = "";

		// feature_power
		$this->feature_power->ViewValue = $this->feature_power->CurrentValue;
		$this->feature_power->ViewCustomAttributes = "";

		// feature_lumen
		$this->feature_lumen->ViewValue = $this->feature_lumen->CurrentValue;
		$this->feature_lumen->ViewCustomAttributes = "";

		// feature_viewangle
		$this->feature_viewangle->ViewValue = $this->feature_viewangle->CurrentValue;
		$this->feature_viewangle->ViewCustomAttributes = "";

		// feature_cri
		$this->feature_cri->ViewValue = $this->feature_cri->CurrentValue;
		$this->feature_cri->ViewCustomAttributes = "";

		// feature_iprating
		$this->feature_iprating->ViewValue = $this->feature_iprating->CurrentValue;
		$this->feature_iprating->ViewCustomAttributes = "";

		// feature_colortemp
		$this->feature_colortemp->ViewValue = $this->feature_colortemp->CurrentValue;
		$this->feature_colortemp->ViewCustomAttributes = "";

		// feature_body
		$this->feature_body->ViewValue = $this->feature_body->CurrentValue;
		$this->feature_body->ViewCustomAttributes = "";

		// feature_cutoutsize
		$this->feature_cutoutsize->ViewValue = $this->feature_cutoutsize->CurrentValue;
		$this->feature_cutoutsize->ViewCustomAttributes = "";

		// feature_dimmable
		$this->feature_dimmable->ViewValue = $this->feature_dimmable->CurrentValue;
		$this->feature_dimmable->ViewCustomAttributes = "";

		// feature_warranty
		$this->feature_warranty->ViewValue = $this->feature_warranty->CurrentValue;
		$this->feature_warranty->ViewCustomAttributes = "";

			// product_id
			$this->product_id->LinkCustomAttributes = "";
			$this->product_id->HrefValue = "";
			$this->product_id->TooltipValue = "";

			// category_id
			$this->category_id->LinkCustomAttributes = "";
			$this->category_id->HrefValue = "";
			$this->category_id->TooltipValue = "";

			// scat_id
			$this->scat_id->LinkCustomAttributes = "";
			$this->scat_id->HrefValue = "";
			$this->scat_id->TooltipValue = "";

			// product_name
			$this->product_name->LinkCustomAttributes = "";
			$this->product_name->HrefValue = "";
			$this->product_name->TooltipValue = "";

			// product_image
			$this->product_image->LinkCustomAttributes = "";
			$this->product_image->HrefValue = "";
			$this->product_image->HrefValue2 = $this->product_image->UploadPath . $this->product_image->Upload->DbValue;
			$this->product_image->TooltipValue = "";

			// product_secimage
			$this->product_secimage->LinkCustomAttributes = "";
			$this->product_secimage->HrefValue = "";
			$this->product_secimage->HrefValue2 = $this->product_secimage->UploadPath . $this->product_secimage->Upload->DbValue;
			$this->product_secimage->TooltipValue = "";
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	//
	// Delete records based on current filter
	//
	function DeleteRows() {
		global $Language, $Security;
		$DeleteRows = TRUE;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE) {
			return FALSE;
		} elseif ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
			$rs->Close();
			return FALSE;

		//} else {
		//	$this->LoadRowValues($rs); // Load row values

		}
		$rows = ($rs) ? $rs->GetRows() : array();
		$conn->BeginTrans();

		// Clone old rows
		$rsold = $rows;
		if ($rs)
			$rs->Close();

		// Call row deleting event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$DeleteRows = $this->Row_Deleting($row);
				if (!$DeleteRows) break;
			}
		}
		if ($DeleteRows) {
			$sKey = "";
			foreach ($rsold as $row) {
				$sThisKey = "";
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['product_id'];
				$this->LoadDbValues($row);
				@unlink(ew_UploadPathEx(TRUE, $this->product_image->OldUploadPath) . $row['product_image']);
				@unlink(ew_UploadPathEx(TRUE, $this->product_secimage->OldUploadPath) . $row['product_secimage']);
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				$DeleteRows = $this->Delete($row); // Delete
				$conn->raiseErrorFn = '';
				if ($DeleteRows === FALSE)
					break;
				if ($sKey <> "") $sKey .= ", ";
				$sKey .= $sThisKey;
			}
		} else {

			// Set up error message
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("DeleteCancelled"));
			}
		}
		if ($DeleteRows) {
			$conn->CommitTrans(); // Commit the changes
		} else {
			$conn->RollbackTrans(); // Rollback changes
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
	}

	// Set up master/detail based on QueryString
	function SetUpMasterParms() {
		$bValidMaster = FALSE;

		// Get the keys for master table
		if (isset($_GET[EW_TABLE_SHOW_MASTER])) {
			$sMasterTblVar = $_GET[EW_TABLE_SHOW_MASTER];
			if ($sMasterTblVar == "") {
				$bValidMaster = TRUE;
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
			}
			if ($sMasterTblVar == "sub_category") {
				$bValidMaster = TRUE;
				if (@$_GET["fk_scat_id"] <> "") {
					$GLOBALS["sub_category"]->scat_id->setQueryStringValue($_GET["fk_scat_id"]);
					$this->scat_id->setQueryStringValue($GLOBALS["sub_category"]->scat_id->QueryStringValue);
					$this->scat_id->setSessionValue($this->scat_id->QueryStringValue);
					if (!is_numeric($GLOBALS["sub_category"]->scat_id->QueryStringValue)) $bValidMaster = FALSE;
				} else {
					$bValidMaster = FALSE;
				}
			}
		}
		if ($bValidMaster) {

			// Save current master table
			$this->setCurrentMasterTable($sMasterTblVar);

			// Reset start record counter (new master key)
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);

			// Clear previous master key from Session
			if ($sMasterTblVar <> "sub_category") {
				if ($this->scat_id->QueryStringValue == "") $this->scat_id->setSessionValue("");
			}
		}
		$this->DbMasterFilter = $this->GetMasterFilter(); // Get master filter
		$this->DbDetailFilter = $this->GetDetailFilter(); // Get detail filter
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, "productslist.php", "", $this->TableVar, TRUE);
		$PageId = "delete";
		$Breadcrumb->Add("delete", $PageId, $url);
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
	}

	// Page Redirecting event
	function Page_Redirecting(&$url) {

		// Example:
		//$url = "your URL";

	}

	// Message Showing event
	// $type = ''|'success'|'failure'|'warning'
	function Message_Showing(&$msg, $type) {
		if ($type == 'success') {

			//$msg = "your success message";
		} elseif ($type == 'failure') {

			//$msg = "your failure message";
		} elseif ($type == 'warning') {

			//$msg = "your warning message";
		} else {

			//$msg = "your message";
		}
	}

	// Page Render event
	function Page_Render() {

		//echo "Page Render";
	}

	// Page Data Rendering event
	function Page_DataRendering(&$header) {

		// Example:
		//$header = "your header";

	}

	// Page Data Rendered event
	function Page_DataRendered(&$footer) {

		// Example:
		//$footer = "your footer";

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($products_delete)) $products_delete = new cproducts_delete();

// Page init
$products_delete->Page_Init();

// Page main
$products_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$products_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = fproductsdelete = new ew_Form("fproductsdelete", "delete");

// Form_CustomValidate event
fproductsdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fproductsdelete.ValidateRequired = true;
<?php } else { ?>
fproductsdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fproductsdelete.Lists["x_category_id"] = {"LinkField":"x_category_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_category_name","","",""],"ParentFields":[],"ChildFields":["x_scat_id"],"FilterFields":[],"Options":[],"Template":""};
fproductsdelete.Lists["x_scat_id"] = {"LinkField":"x_scat_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_scat_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($products_delete->Recordset = $products_delete->LoadRecordset())
	$products_deleteTotalRecs = $products_delete->Recordset->RecordCount(); // Get record count
if ($products_deleteTotalRecs <= 0) { // No record found, exit
	if ($products_delete->Recordset)
		$products_delete->Recordset->Close();
	$products_delete->Page_Terminate("productslist.php"); // Return to list
}
?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $products_delete->ShowPageHeader(); ?>
<?php
$products_delete->ShowMessage();
?>
<form name="fproductsdelete" id="fproductsdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($products_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $products_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="products">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($products_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $products->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($products->product_id->Visible) { // product_id ?>
		<th><span id="elh_products_product_id" class="products_product_id"><?php echo $products->product_id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($products->category_id->Visible) { // category_id ?>
		<th><span id="elh_products_category_id" class="products_category_id"><?php echo $products->category_id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($products->scat_id->Visible) { // scat_id ?>
		<th><span id="elh_products_scat_id" class="products_scat_id"><?php echo $products->scat_id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($products->product_name->Visible) { // product_name ?>
		<th><span id="elh_products_product_name" class="products_product_name"><?php echo $products->product_name->FldCaption() ?></span></th>
<?php } ?>
<?php if ($products->product_image->Visible) { // product_image ?>
		<th><span id="elh_products_product_image" class="products_product_image"><?php echo $products->product_image->FldCaption() ?></span></th>
<?php } ?>
<?php if ($products->product_secimage->Visible) { // product_secimage ?>
		<th><span id="elh_products_product_secimage" class="products_product_secimage"><?php echo $products->product_secimage->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$products_delete->RecCnt = 0;
$i = 0;
while (!$products_delete->Recordset->EOF) {
	$products_delete->RecCnt++;
	$products_delete->RowCnt++;

	// Set row properties
	$products->ResetAttrs();
	$products->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$products_delete->LoadRowValues($products_delete->Recordset);

	// Render row
	$products_delete->RenderRow();
?>
	<tr<?php echo $products->RowAttributes() ?>>
<?php if ($products->product_id->Visible) { // product_id ?>
		<td<?php echo $products->product_id->CellAttributes() ?>>
<span id="el<?php echo $products_delete->RowCnt ?>_products_product_id" class="products_product_id">
<span<?php echo $products->product_id->ViewAttributes() ?>>
<?php echo $products->product_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($products->category_id->Visible) { // category_id ?>
		<td<?php echo $products->category_id->CellAttributes() ?>>
<span id="el<?php echo $products_delete->RowCnt ?>_products_category_id" class="products_category_id">
<span<?php echo $products->category_id->ViewAttributes() ?>>
<?php echo $products->category_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($products->scat_id->Visible) { // scat_id ?>
		<td<?php echo $products->scat_id->CellAttributes() ?>>
<span id="el<?php echo $products_delete->RowCnt ?>_products_scat_id" class="products_scat_id">
<span<?php echo $products->scat_id->ViewAttributes() ?>>
<?php echo $products->scat_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($products->product_name->Visible) { // product_name ?>
		<td<?php echo $products->product_name->CellAttributes() ?>>
<span id="el<?php echo $products_delete->RowCnt ?>_products_product_name" class="products_product_name">
<span<?php echo $products->product_name->ViewAttributes() ?>>
<?php echo $products->product_name->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($products->product_image->Visible) { // product_image ?>
		<td<?php echo $products->product_image->CellAttributes() ?>>
<span id="el<?php echo $products_delete->RowCnt ?>_products_product_image" class="products_product_image">
<span<?php echo $products->product_image->ViewAttributes() ?>>
<?php echo ew_GetFileViewTag($products->product_image, $products->product_image->ListViewValue()) ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($products->product_secimage->Visible) { // product_secimage ?>
		<td<?php echo $products->product_secimage->CellAttributes() ?>>
<span id="el<?php echo $products_delete->RowCnt ?>_products_product_secimage" class="products_product_secimage">
<span<?php echo $products->product_secimage->ViewAttributes() ?>>
<?php echo ew_GetFileViewTag($products->product_secimage, $products->product_secimage->ListViewValue()) ?>
</span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$products_delete->Recordset->MoveNext();
}
$products_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $products_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fproductsdelete.Init();
</script>
<?php
$products_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$products_delete->Page_Terminate();
?>
