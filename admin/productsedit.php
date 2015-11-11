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

$products_edit = NULL; // Initialize page object first

class cproducts_edit extends cproducts {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{C3CE8554-8FA2-42A1-89B9-3DB1F25B77B3}";

	// Table name
	var $TableName = 'products';

	// Page object name
	var $PageObjName = 'products_edit';

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
			define("EW_PAGE_ID", 'edit', TRUE);

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

		// Create form object
		$objForm = new cFormObj();
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

		// Process auto fill
		if (@$_POST["ajax"] == "autofill") {
			$results = $this->GetAutoFill(@$_POST["name"], @$_POST["q"]);
			if ($results) {

				// Clean output buffer
				if (!EW_DEBUG_ENABLED && ob_get_length())
					ob_end_clean();
				echo $results;
				$this->Page_Terminate();
				exit();
			}
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
	var $FormClassName = "form-horizontal ewForm ewEditForm";
	var $DbMasterFilter;
	var $DbDetailFilter;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Load key from QueryString
		if (@$_GET["product_id"] <> "") {
			$this->product_id->setQueryStringValue($_GET["product_id"]);
		}

		// Set up master detail parameters
		$this->SetUpMasterParms();

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->product_id->CurrentValue == "")
			$this->Page_Terminate("productslist.php"); // Invalid key, return to list

		// Validate form if post back
		if (@$_POST["a_edit"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = ""; // Form error, reset action
				$this->setFailureMessage($gsFormError);
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues();
			}
		}
		switch ($this->CurrentAction) {
			case "I": // Get a record to display
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("productslist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					$sReturnUrl = $this->getReturnUrl();
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Restore form values if update failed
				}
		}

		// Render the record
		$this->RowType = EW_ROWTYPE_EDIT; // Render as Edit
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up starting record parameters
	function SetUpStartRec() {
		if ($this->DisplayRecs == 0)
			return;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET[EW_TABLE_START_REC] <> "") { // Check for "start" parameter
				$this->StartRec = $_GET[EW_TABLE_START_REC];
				$this->setStartRecordNumber($this->StartRec);
			} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
				$PageNo = $_GET[EW_TABLE_PAGE_NO];
				if (is_numeric($PageNo)) {
					$this->StartRec = ($PageNo-1)*$this->DisplayRecs+1;
					if ($this->StartRec <= 0) {
						$this->StartRec = 1;
					} elseif ($this->StartRec >= intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1) {
						$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1;
					}
					$this->setStartRecordNumber($this->StartRec);
				}
			}
		}
		$this->StartRec = $this->getStartRecordNumber();

		// Check if correct start record counter
		if (!is_numeric($this->StartRec) || $this->StartRec == "") { // Avoid invalid start record counter
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif (intval($this->StartRec) > intval($this->TotalRecs)) { // Avoid starting record > total records
			$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to last page first record
			$this->setStartRecordNumber($this->StartRec);
		} elseif (($this->StartRec-1) % $this->DisplayRecs <> 0) {
			$this->StartRec = intval(($this->StartRec-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to page boundary
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
		$this->product_image->Upload->Index = $objForm->Index;
		$this->product_image->Upload->UploadFile();
		$this->product_image->CurrentValue = $this->product_image->Upload->FileName;
		$this->product_secimage->Upload->Index = $objForm->Index;
		$this->product_secimage->Upload->UploadFile();
		$this->product_secimage->CurrentValue = $this->product_secimage->Upload->FileName;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->product_id->FldIsDetailKey)
			$this->product_id->setFormValue($objForm->GetValue("x_product_id"));
		if (!$this->category_id->FldIsDetailKey) {
			$this->category_id->setFormValue($objForm->GetValue("x_category_id"));
		}
		if (!$this->scat_id->FldIsDetailKey) {
			$this->scat_id->setFormValue($objForm->GetValue("x_scat_id"));
		}
		if (!$this->product_name->FldIsDetailKey) {
			$this->product_name->setFormValue($objForm->GetValue("x_product_name"));
		}
		if (!$this->product_description->FldIsDetailKey) {
			$this->product_description->setFormValue($objForm->GetValue("x_product_description"));
		}
		if (!$this->feature_ledtype->FldIsDetailKey) {
			$this->feature_ledtype->setFormValue($objForm->GetValue("x_feature_ledtype"));
		}
		if (!$this->feature_power->FldIsDetailKey) {
			$this->feature_power->setFormValue($objForm->GetValue("x_feature_power"));
		}
		if (!$this->feature_lumen->FldIsDetailKey) {
			$this->feature_lumen->setFormValue($objForm->GetValue("x_feature_lumen"));
		}
		if (!$this->feature_viewangle->FldIsDetailKey) {
			$this->feature_viewangle->setFormValue($objForm->GetValue("x_feature_viewangle"));
		}
		if (!$this->feature_cri->FldIsDetailKey) {
			$this->feature_cri->setFormValue($objForm->GetValue("x_feature_cri"));
		}
		if (!$this->feature_iprating->FldIsDetailKey) {
			$this->feature_iprating->setFormValue($objForm->GetValue("x_feature_iprating"));
		}
		if (!$this->feature_colortemp->FldIsDetailKey) {
			$this->feature_colortemp->setFormValue($objForm->GetValue("x_feature_colortemp"));
		}
		if (!$this->feature_body->FldIsDetailKey) {
			$this->feature_body->setFormValue($objForm->GetValue("x_feature_body"));
		}
		if (!$this->feature_cutoutsize->FldIsDetailKey) {
			$this->feature_cutoutsize->setFormValue($objForm->GetValue("x_feature_cutoutsize"));
		}
		if (!$this->feature_colors->FldIsDetailKey) {
			$this->feature_colors->setFormValue($objForm->GetValue("x_feature_colors"));
		}
		if (!$this->feature_dimmable->FldIsDetailKey) {
			$this->feature_dimmable->setFormValue($objForm->GetValue("x_feature_dimmable"));
		}
		if (!$this->feature_warranty->FldIsDetailKey) {
			$this->feature_warranty->setFormValue($objForm->GetValue("x_feature_warranty"));
		}
		if (!$this->feature_application->FldIsDetailKey) {
			$this->feature_application->setFormValue($objForm->GetValue("x_feature_application"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->product_id->CurrentValue = $this->product_id->FormValue;
		$this->category_id->CurrentValue = $this->category_id->FormValue;
		$this->scat_id->CurrentValue = $this->scat_id->FormValue;
		$this->product_name->CurrentValue = $this->product_name->FormValue;
		$this->product_description->CurrentValue = $this->product_description->FormValue;
		$this->feature_ledtype->CurrentValue = $this->feature_ledtype->FormValue;
		$this->feature_power->CurrentValue = $this->feature_power->FormValue;
		$this->feature_lumen->CurrentValue = $this->feature_lumen->FormValue;
		$this->feature_viewangle->CurrentValue = $this->feature_viewangle->FormValue;
		$this->feature_cri->CurrentValue = $this->feature_cri->FormValue;
		$this->feature_iprating->CurrentValue = $this->feature_iprating->FormValue;
		$this->feature_colortemp->CurrentValue = $this->feature_colortemp->FormValue;
		$this->feature_body->CurrentValue = $this->feature_body->FormValue;
		$this->feature_cutoutsize->CurrentValue = $this->feature_cutoutsize->FormValue;
		$this->feature_colors->CurrentValue = $this->feature_colors->FormValue;
		$this->feature_dimmable->CurrentValue = $this->feature_dimmable->FormValue;
		$this->feature_warranty->CurrentValue = $this->feature_warranty->FormValue;
		$this->feature_application->CurrentValue = $this->feature_application->FormValue;
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

		// product_description
		$this->product_description->ViewValue = $this->product_description->CurrentValue;
		$this->product_description->ViewCustomAttributes = "";

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

		// feature_colors
		$this->feature_colors->ViewValue = $this->feature_colors->CurrentValue;
		$this->feature_colors->ViewCustomAttributes = "";

		// feature_dimmable
		$this->feature_dimmable->ViewValue = $this->feature_dimmable->CurrentValue;
		$this->feature_dimmable->ViewCustomAttributes = "";

		// feature_warranty
		$this->feature_warranty->ViewValue = $this->feature_warranty->CurrentValue;
		$this->feature_warranty->ViewCustomAttributes = "";

		// feature_application
		$this->feature_application->ViewValue = $this->feature_application->CurrentValue;
		$this->feature_application->ViewCustomAttributes = "";

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

			// product_description
			$this->product_description->LinkCustomAttributes = "";
			$this->product_description->HrefValue = "";
			$this->product_description->TooltipValue = "";

			// feature_ledtype
			$this->feature_ledtype->LinkCustomAttributes = "";
			$this->feature_ledtype->HrefValue = "";
			$this->feature_ledtype->TooltipValue = "";

			// feature_power
			$this->feature_power->LinkCustomAttributes = "";
			$this->feature_power->HrefValue = "";
			$this->feature_power->TooltipValue = "";

			// feature_lumen
			$this->feature_lumen->LinkCustomAttributes = "";
			$this->feature_lumen->HrefValue = "";
			$this->feature_lumen->TooltipValue = "";

			// feature_viewangle
			$this->feature_viewangle->LinkCustomAttributes = "";
			$this->feature_viewangle->HrefValue = "";
			$this->feature_viewangle->TooltipValue = "";

			// feature_cri
			$this->feature_cri->LinkCustomAttributes = "";
			$this->feature_cri->HrefValue = "";
			$this->feature_cri->TooltipValue = "";

			// feature_iprating
			$this->feature_iprating->LinkCustomAttributes = "";
			$this->feature_iprating->HrefValue = "";
			$this->feature_iprating->TooltipValue = "";

			// feature_colortemp
			$this->feature_colortemp->LinkCustomAttributes = "";
			$this->feature_colortemp->HrefValue = "";
			$this->feature_colortemp->TooltipValue = "";

			// feature_body
			$this->feature_body->LinkCustomAttributes = "";
			$this->feature_body->HrefValue = "";
			$this->feature_body->TooltipValue = "";

			// feature_cutoutsize
			$this->feature_cutoutsize->LinkCustomAttributes = "";
			$this->feature_cutoutsize->HrefValue = "";
			$this->feature_cutoutsize->TooltipValue = "";

			// feature_colors
			$this->feature_colors->LinkCustomAttributes = "";
			$this->feature_colors->HrefValue = "";
			$this->feature_colors->TooltipValue = "";

			// feature_dimmable
			$this->feature_dimmable->LinkCustomAttributes = "";
			$this->feature_dimmable->HrefValue = "";
			$this->feature_dimmable->TooltipValue = "";

			// feature_warranty
			$this->feature_warranty->LinkCustomAttributes = "";
			$this->feature_warranty->HrefValue = "";
			$this->feature_warranty->TooltipValue = "";

			// feature_application
			$this->feature_application->LinkCustomAttributes = "";
			$this->feature_application->HrefValue = "";
			$this->feature_application->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// product_id
			$this->product_id->EditAttrs["class"] = "form-control";
			$this->product_id->EditCustomAttributes = "";
			$this->product_id->EditValue = $this->product_id->CurrentValue;
			$this->product_id->ViewCustomAttributes = "";

			// category_id
			$this->category_id->EditAttrs["class"] = "form-control";
			$this->category_id->EditCustomAttributes = "";
			if (trim(strval($this->category_id->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`category_id`" . ew_SearchString("=", $this->category_id->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `category_id`, `category_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, `category_name` AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `category`";
			$sWhereWrk = "";
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->category_id, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->category_id->EditValue = $arwrk;

			// scat_id
			$this->scat_id->EditAttrs["class"] = "form-control";
			$this->scat_id->EditCustomAttributes = "";
			if ($this->scat_id->getSessionValue() <> "") {
				$this->scat_id->CurrentValue = $this->scat_id->getSessionValue();
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
			} else {
			if (trim(strval($this->scat_id->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`scat_id`" . ew_SearchString("=", $this->scat_id->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `scat_id`, `scat_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, `category_id` AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `sub_category`";
			$sWhereWrk = "";
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->scat_id, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->scat_id->EditValue = $arwrk;
			}

			// product_name
			$this->product_name->EditAttrs["class"] = "form-control";
			$this->product_name->EditCustomAttributes = "";
			$this->product_name->EditValue = ew_HtmlEncode($this->product_name->CurrentValue);
			$this->product_name->PlaceHolder = ew_RemoveHtml($this->product_name->FldCaption());

			// product_image
			$this->product_image->EditAttrs["class"] = "form-control";
			$this->product_image->EditCustomAttributes = "";
			if (!ew_Empty($this->product_image->Upload->DbValue)) {
				$this->product_image->EditValue = $this->product_image->Upload->DbValue;
			} else {
				$this->product_image->EditValue = "";
			}
			if (!ew_Empty($this->product_image->CurrentValue))
				$this->product_image->Upload->FileName = $this->product_image->CurrentValue;
			if ($this->CurrentAction == "I" && !$this->EventCancelled) ew_RenderUploadField($this->product_image);

			// product_secimage
			$this->product_secimage->EditAttrs["class"] = "form-control";
			$this->product_secimage->EditCustomAttributes = "";
			if (!ew_Empty($this->product_secimage->Upload->DbValue)) {
				$this->product_secimage->EditValue = $this->product_secimage->Upload->DbValue;
			} else {
				$this->product_secimage->EditValue = "";
			}
			if (!ew_Empty($this->product_secimage->CurrentValue))
				$this->product_secimage->Upload->FileName = $this->product_secimage->CurrentValue;
			if ($this->CurrentAction == "I" && !$this->EventCancelled) ew_RenderUploadField($this->product_secimage);

			// product_description
			$this->product_description->EditAttrs["class"] = "form-control";
			$this->product_description->EditCustomAttributes = "";
			$this->product_description->EditValue = ew_HtmlEncode($this->product_description->CurrentValue);
			$this->product_description->PlaceHolder = ew_RemoveHtml($this->product_description->FldCaption());

			// feature_ledtype
			$this->feature_ledtype->EditAttrs["class"] = "form-control";
			$this->feature_ledtype->EditCustomAttributes = "";
			$this->feature_ledtype->EditValue = ew_HtmlEncode($this->feature_ledtype->CurrentValue);
			$this->feature_ledtype->PlaceHolder = ew_RemoveHtml($this->feature_ledtype->FldCaption());

			// feature_power
			$this->feature_power->EditAttrs["class"] = "form-control";
			$this->feature_power->EditCustomAttributes = "";
			$this->feature_power->EditValue = ew_HtmlEncode($this->feature_power->CurrentValue);
			$this->feature_power->PlaceHolder = ew_RemoveHtml($this->feature_power->FldCaption());

			// feature_lumen
			$this->feature_lumen->EditAttrs["class"] = "form-control";
			$this->feature_lumen->EditCustomAttributes = "";
			$this->feature_lumen->EditValue = ew_HtmlEncode($this->feature_lumen->CurrentValue);
			$this->feature_lumen->PlaceHolder = ew_RemoveHtml($this->feature_lumen->FldCaption());

			// feature_viewangle
			$this->feature_viewangle->EditAttrs["class"] = "form-control";
			$this->feature_viewangle->EditCustomAttributes = "";
			$this->feature_viewangle->EditValue = ew_HtmlEncode($this->feature_viewangle->CurrentValue);
			$this->feature_viewangle->PlaceHolder = ew_RemoveHtml($this->feature_viewangle->FldCaption());

			// feature_cri
			$this->feature_cri->EditAttrs["class"] = "form-control";
			$this->feature_cri->EditCustomAttributes = "";
			$this->feature_cri->EditValue = ew_HtmlEncode($this->feature_cri->CurrentValue);
			$this->feature_cri->PlaceHolder = ew_RemoveHtml($this->feature_cri->FldCaption());

			// feature_iprating
			$this->feature_iprating->EditAttrs["class"] = "form-control";
			$this->feature_iprating->EditCustomAttributes = "";
			$this->feature_iprating->EditValue = ew_HtmlEncode($this->feature_iprating->CurrentValue);
			$this->feature_iprating->PlaceHolder = ew_RemoveHtml($this->feature_iprating->FldCaption());

			// feature_colortemp
			$this->feature_colortemp->EditAttrs["class"] = "form-control";
			$this->feature_colortemp->EditCustomAttributes = "";
			$this->feature_colortemp->EditValue = ew_HtmlEncode($this->feature_colortemp->CurrentValue);
			$this->feature_colortemp->PlaceHolder = ew_RemoveHtml($this->feature_colortemp->FldCaption());

			// feature_body
			$this->feature_body->EditAttrs["class"] = "form-control";
			$this->feature_body->EditCustomAttributes = "";
			$this->feature_body->EditValue = ew_HtmlEncode($this->feature_body->CurrentValue);
			$this->feature_body->PlaceHolder = ew_RemoveHtml($this->feature_body->FldCaption());

			// feature_cutoutsize
			$this->feature_cutoutsize->EditAttrs["class"] = "form-control";
			$this->feature_cutoutsize->EditCustomAttributes = "";
			$this->feature_cutoutsize->EditValue = ew_HtmlEncode($this->feature_cutoutsize->CurrentValue);
			$this->feature_cutoutsize->PlaceHolder = ew_RemoveHtml($this->feature_cutoutsize->FldCaption());

			// feature_colors
			$this->feature_colors->EditAttrs["class"] = "form-control";
			$this->feature_colors->EditCustomAttributes = "";
			$this->feature_colors->EditValue = ew_HtmlEncode($this->feature_colors->CurrentValue);
			$this->feature_colors->PlaceHolder = ew_RemoveHtml($this->feature_colors->FldCaption());

			// feature_dimmable
			$this->feature_dimmable->EditAttrs["class"] = "form-control";
			$this->feature_dimmable->EditCustomAttributes = "";
			$this->feature_dimmable->EditValue = ew_HtmlEncode($this->feature_dimmable->CurrentValue);
			$this->feature_dimmable->PlaceHolder = ew_RemoveHtml($this->feature_dimmable->FldCaption());

			// feature_warranty
			$this->feature_warranty->EditAttrs["class"] = "form-control";
			$this->feature_warranty->EditCustomAttributes = "";
			$this->feature_warranty->EditValue = ew_HtmlEncode($this->feature_warranty->CurrentValue);
			$this->feature_warranty->PlaceHolder = ew_RemoveHtml($this->feature_warranty->FldCaption());

			// feature_application
			$this->feature_application->EditAttrs["class"] = "form-control";
			$this->feature_application->EditCustomAttributes = "";
			$this->feature_application->EditValue = ew_HtmlEncode($this->feature_application->CurrentValue);
			$this->feature_application->PlaceHolder = ew_RemoveHtml($this->feature_application->FldCaption());

			// Edit refer script
			// product_id

			$this->product_id->HrefValue = "";

			// category_id
			$this->category_id->HrefValue = "";

			// scat_id
			$this->scat_id->HrefValue = "";

			// product_name
			$this->product_name->HrefValue = "";

			// product_image
			$this->product_image->HrefValue = "";
			$this->product_image->HrefValue2 = $this->product_image->UploadPath . $this->product_image->Upload->DbValue;

			// product_secimage
			$this->product_secimage->HrefValue = "";
			$this->product_secimage->HrefValue2 = $this->product_secimage->UploadPath . $this->product_secimage->Upload->DbValue;

			// product_description
			$this->product_description->HrefValue = "";

			// feature_ledtype
			$this->feature_ledtype->HrefValue = "";

			// feature_power
			$this->feature_power->HrefValue = "";

			// feature_lumen
			$this->feature_lumen->HrefValue = "";

			// feature_viewangle
			$this->feature_viewangle->HrefValue = "";

			// feature_cri
			$this->feature_cri->HrefValue = "";

			// feature_iprating
			$this->feature_iprating->HrefValue = "";

			// feature_colortemp
			$this->feature_colortemp->HrefValue = "";

			// feature_body
			$this->feature_body->HrefValue = "";

			// feature_cutoutsize
			$this->feature_cutoutsize->HrefValue = "";

			// feature_colors
			$this->feature_colors->HrefValue = "";

			// feature_dimmable
			$this->feature_dimmable->HrefValue = "";

			// feature_warranty
			$this->feature_warranty->HrefValue = "";

			// feature_application
			$this->feature_application->HrefValue = "";
		}
		if ($this->RowType == EW_ROWTYPE_ADD ||
			$this->RowType == EW_ROWTYPE_EDIT ||
			$this->RowType == EW_ROWTYPE_SEARCH) { // Add / Edit / Search row
			$this->SetupFieldTitles();
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!$this->category_id->FldIsDetailKey && !is_null($this->category_id->FormValue) && $this->category_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->category_id->FldCaption(), $this->category_id->ReqErrMsg));
		}
		if (!$this->scat_id->FldIsDetailKey && !is_null($this->scat_id->FormValue) && $this->scat_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->scat_id->FldCaption(), $this->scat_id->ReqErrMsg));
		}
		if (!$this->product_name->FldIsDetailKey && !is_null($this->product_name->FormValue) && $this->product_name->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->product_name->FldCaption(), $this->product_name->ReqErrMsg));
		}
		if (!$this->product_description->FldIsDetailKey && !is_null($this->product_description->FormValue) && $this->product_description->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->product_description->FldCaption(), $this->product_description->ReqErrMsg));
		}

		// Return validate result
		$ValidateForm = ($gsFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsFormError, $sFormCustomError);
		}
		return $ValidateForm;
	}

	// Update record based on key values
	function EditRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();
		$conn = &$this->Connection();
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE)
			return FALSE;
		if ($rs->EOF) {
			$EditRow = FALSE; // Update Failed
		} else {

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$rsnew = array();

			// category_id
			$this->category_id->SetDbValueDef($rsnew, $this->category_id->CurrentValue, 0, $this->category_id->ReadOnly);

			// scat_id
			$this->scat_id->SetDbValueDef($rsnew, $this->scat_id->CurrentValue, 0, $this->scat_id->ReadOnly);

			// product_name
			$this->product_name->SetDbValueDef($rsnew, $this->product_name->CurrentValue, "", $this->product_name->ReadOnly);

			// product_image
			if (!($this->product_image->ReadOnly) && !$this->product_image->Upload->KeepFile) {
				$this->product_image->Upload->DbValue = $rsold['product_image']; // Get original value
				if ($this->product_image->Upload->FileName == "") {
					$rsnew['product_image'] = NULL;
				} else {
					$rsnew['product_image'] = $this->product_image->Upload->FileName;
				}
			}

			// product_secimage
			if (!($this->product_secimage->ReadOnly) && !$this->product_secimage->Upload->KeepFile) {
				$this->product_secimage->Upload->DbValue = $rsold['product_secimage']; // Get original value
				if ($this->product_secimage->Upload->FileName == "") {
					$rsnew['product_secimage'] = NULL;
				} else {
					$rsnew['product_secimage'] = $this->product_secimage->Upload->FileName;
				}
			}

			// product_description
			$this->product_description->SetDbValueDef($rsnew, $this->product_description->CurrentValue, "", $this->product_description->ReadOnly);

			// feature_ledtype
			$this->feature_ledtype->SetDbValueDef($rsnew, $this->feature_ledtype->CurrentValue, NULL, $this->feature_ledtype->ReadOnly);

			// feature_power
			$this->feature_power->SetDbValueDef($rsnew, $this->feature_power->CurrentValue, NULL, $this->feature_power->ReadOnly);

			// feature_lumen
			$this->feature_lumen->SetDbValueDef($rsnew, $this->feature_lumen->CurrentValue, NULL, $this->feature_lumen->ReadOnly);

			// feature_viewangle
			$this->feature_viewangle->SetDbValueDef($rsnew, $this->feature_viewangle->CurrentValue, NULL, $this->feature_viewangle->ReadOnly);

			// feature_cri
			$this->feature_cri->SetDbValueDef($rsnew, $this->feature_cri->CurrentValue, NULL, $this->feature_cri->ReadOnly);

			// feature_iprating
			$this->feature_iprating->SetDbValueDef($rsnew, $this->feature_iprating->CurrentValue, NULL, $this->feature_iprating->ReadOnly);

			// feature_colortemp
			$this->feature_colortemp->SetDbValueDef($rsnew, $this->feature_colortemp->CurrentValue, NULL, $this->feature_colortemp->ReadOnly);

			// feature_body
			$this->feature_body->SetDbValueDef($rsnew, $this->feature_body->CurrentValue, NULL, $this->feature_body->ReadOnly);

			// feature_cutoutsize
			$this->feature_cutoutsize->SetDbValueDef($rsnew, $this->feature_cutoutsize->CurrentValue, NULL, $this->feature_cutoutsize->ReadOnly);

			// feature_colors
			$this->feature_colors->SetDbValueDef($rsnew, $this->feature_colors->CurrentValue, NULL, $this->feature_colors->ReadOnly);

			// feature_dimmable
			$this->feature_dimmable->SetDbValueDef($rsnew, $this->feature_dimmable->CurrentValue, NULL, $this->feature_dimmable->ReadOnly);

			// feature_warranty
			$this->feature_warranty->SetDbValueDef($rsnew, $this->feature_warranty->CurrentValue, NULL, $this->feature_warranty->ReadOnly);

			// feature_application
			$this->feature_application->SetDbValueDef($rsnew, $this->feature_application->CurrentValue, NULL, $this->feature_application->ReadOnly);
			if (!$this->product_image->Upload->KeepFile) {
				if (!ew_Empty($this->product_image->Upload->Value)) {
					if ($this->product_image->Upload->FileName == $this->product_image->Upload->DbValue) { // Overwrite if same file name
						$this->product_image->Upload->DbValue = ""; // No need to delete any more
					} else {
						$rsnew['product_image'] = ew_UploadFileNameEx(ew_UploadPathEx(TRUE, $this->product_image->UploadPath), $rsnew['product_image']); // Get new file name
					}
				}
			}
			if (!$this->product_secimage->Upload->KeepFile) {
				if (!ew_Empty($this->product_secimage->Upload->Value)) {
					if ($this->product_secimage->Upload->FileName == $this->product_secimage->Upload->DbValue) { // Overwrite if same file name
						$this->product_secimage->Upload->DbValue = ""; // No need to delete any more
					} else {
						$rsnew['product_secimage'] = ew_UploadFileNameEx(ew_UploadPathEx(TRUE, $this->product_secimage->UploadPath), $rsnew['product_secimage']); // Get new file name
					}
				}
			}

			// Call Row Updating event
			$bUpdateRow = $this->Row_Updating($rsold, $rsnew);
			if ($bUpdateRow) {
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				if (count($rsnew) > 0)
					$EditRow = $this->Update($rsnew, "", $rsold);
				else
					$EditRow = TRUE; // No field to update
				$conn->raiseErrorFn = '';
				if ($EditRow) {
					if (!$this->product_image->Upload->KeepFile) {
						if (!ew_Empty($this->product_image->Upload->Value)) {
							$this->product_image->Upload->SaveToFile($this->product_image->UploadPath, $rsnew['product_image'], TRUE);
						}
						if ($this->product_image->Upload->DbValue <> "")
							@unlink(ew_UploadPathEx(TRUE, $this->product_image->OldUploadPath) . $this->product_image->Upload->DbValue);
					}
					if (!$this->product_secimage->Upload->KeepFile) {
						if (!ew_Empty($this->product_secimage->Upload->Value)) {
							$this->product_secimage->Upload->SaveToFile($this->product_secimage->UploadPath, $rsnew['product_secimage'], TRUE);
						}
						if ($this->product_secimage->Upload->DbValue <> "")
							@unlink(ew_UploadPathEx(TRUE, $this->product_secimage->OldUploadPath) . $this->product_secimage->Upload->DbValue);
					}
				}
			} else {
				if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

					// Use the message, do nothing
				} elseif ($this->CancelMessage <> "") {
					$this->setFailureMessage($this->CancelMessage);
					$this->CancelMessage = "";
				} else {
					$this->setFailureMessage($Language->Phrase("UpdateCancelled"));
				}
				$EditRow = FALSE;
			}
		}

		// Call Row_Updated event
		if ($EditRow)
			$this->Row_Updated($rsold, $rsnew);
		$rs->Close();

		// product_image
		ew_CleanUploadTempPath($this->product_image, $this->product_image->Upload->Index);

		// product_secimage
		ew_CleanUploadTempPath($this->product_secimage, $this->product_secimage->Upload->Index);
		return $EditRow;
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
			$this->setSessionWhere($this->GetDetailFilter());

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
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, $url);
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

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($products_edit)) $products_edit = new cproducts_edit();

// Page init
$products_edit->Page_Init();

// Page main
$products_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$products_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fproductsedit = new ew_Form("fproductsedit", "edit");

// Validate form
fproductsedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_category_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $products->category_id->FldCaption(), $products->category_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_scat_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $products->scat_id->FldCaption(), $products->scat_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_product_name");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $products->product_name->FldCaption(), $products->product_name->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_product_description");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $products->product_description->FldCaption(), $products->product_description->ReqErrMsg)) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}

	// Process detail forms
	var dfs = $fobj.find("input[name='detailpage']").get();
	for (var i = 0; i < dfs.length; i++) {
		var df = dfs[i], val = df.value;
		if (val && ewForms[val])
			if (!ewForms[val].Validate())
				return false;
	}
	return true;
}

// Form_CustomValidate event
fproductsedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fproductsedit.ValidateRequired = true;
<?php } else { ?>
fproductsedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fproductsedit.Lists["x_category_id"] = {"LinkField":"x_category_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_category_name","","",""],"ParentFields":[],"ChildFields":["x_scat_id"],"FilterFields":[],"Options":[],"Template":""};
fproductsedit.Lists["x_scat_id"] = {"LinkField":"x_scat_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_scat_name","","",""],"ParentFields":["x_category_id"],"ChildFields":[],"FilterFields":["x_category_id"],"Options":[],"Template":""};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $products_edit->ShowPageHeader(); ?>
<?php
$products_edit->ShowMessage();
?>
<form name="fproductsedit" id="fproductsedit" class="<?php echo $products_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($products_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $products_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="products">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<div>
<?php if ($products->product_id->Visible) { // product_id ?>
	<div id="r_product_id" class="form-group">
		<label id="elh_products_product_id" class="col-sm-2 control-label ewLabel"><?php echo $products->product_id->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $products->product_id->CellAttributes() ?>>
<span id="el_products_product_id">
<span<?php echo $products->product_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $products->product_id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="products" data-field="x_product_id" name="x_product_id" id="x_product_id" value="<?php echo ew_HtmlEncode($products->product_id->CurrentValue) ?>">
<?php echo $products->product_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($products->category_id->Visible) { // category_id ?>
	<div id="r_category_id" class="form-group">
		<label id="elh_products_category_id" for="x_category_id" class="col-sm-2 control-label ewLabel"><?php echo $products->category_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $products->category_id->CellAttributes() ?>>
<span id="el_products_category_id">
<?php $products->category_id->EditAttrs["onchange"] = "ew_UpdateOpt.call(this); " . @$products->category_id->EditAttrs["onchange"]; ?>
<select data-table="products" data-field="x_category_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($products->category_id->DisplayValueSeparator) ? json_encode($products->category_id->DisplayValueSeparator) : $products->category_id->DisplayValueSeparator) ?>" id="x_category_id" name="x_category_id"<?php echo $products->category_id->EditAttributes() ?>>
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
<input type="hidden" name="s_x_category_id" id="s_x_category_id" value="<?php echo $products->category_id->LookupFilterQuery() ?>">
</span>
<?php echo $products->category_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($products->scat_id->Visible) { // scat_id ?>
	<div id="r_scat_id" class="form-group">
		<label id="elh_products_scat_id" for="x_scat_id" class="col-sm-2 control-label ewLabel"><?php echo $products->scat_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $products->scat_id->CellAttributes() ?>>
<?php if ($products->scat_id->getSessionValue() <> "") { ?>
<span id="el_products_scat_id">
<span<?php echo $products->scat_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $products->scat_id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x_scat_id" name="x_scat_id" value="<?php echo ew_HtmlEncode($products->scat_id->CurrentValue) ?>">
<?php } else { ?>
<span id="el_products_scat_id">
<select data-table="products" data-field="x_scat_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($products->scat_id->DisplayValueSeparator) ? json_encode($products->scat_id->DisplayValueSeparator) : $products->scat_id->DisplayValueSeparator) ?>" id="x_scat_id" name="x_scat_id"<?php echo $products->scat_id->EditAttributes() ?>>
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
<input type="hidden" name="s_x_scat_id" id="s_x_scat_id" value="<?php echo $products->scat_id->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php echo $products->scat_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($products->product_name->Visible) { // product_name ?>
	<div id="r_product_name" class="form-group">
		<label id="elh_products_product_name" for="x_product_name" class="col-sm-2 control-label ewLabel"><?php echo $products->product_name->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $products->product_name->CellAttributes() ?>>
<span id="el_products_product_name">
<input type="text" data-table="products" data-field="x_product_name" name="x_product_name" id="x_product_name" placeholder="<?php echo ew_HtmlEncode($products->product_name->getPlaceHolder()) ?>" value="<?php echo $products->product_name->EditValue ?>"<?php echo $products->product_name->EditAttributes() ?>>
</span>
<?php echo $products->product_name->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($products->product_image->Visible) { // product_image ?>
	<div id="r_product_image" class="form-group">
		<label id="elh_products_product_image" class="col-sm-2 control-label ewLabel"><?php echo $products->product_image->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $products->product_image->CellAttributes() ?>>
<span id="el_products_product_image">
<div id="fd_x_product_image">
<span title="<?php echo $products->product_image->FldTitle() ? $products->product_image->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($products->product_image->ReadOnly || $products->product_image->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="products" data-field="x_product_image" name="x_product_image" id="x_product_image"<?php echo $products->product_image->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x_product_image" id= "fn_x_product_image" value="<?php echo $products->product_image->Upload->FileName ?>">
<?php if (@$_POST["fa_x_product_image"] == "0") { ?>
<input type="hidden" name="fa_x_product_image" id= "fa_x_product_image" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x_product_image" id= "fa_x_product_image" value="1">
<?php } ?>
<input type="hidden" name="fs_x_product_image" id= "fs_x_product_image" value="200">
<input type="hidden" name="fx_x_product_image" id= "fx_x_product_image" value="<?php echo $products->product_image->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_product_image" id= "fm_x_product_image" value="<?php echo $products->product_image->UploadMaxFileSize ?>">
</div>
<table id="ft_x_product_image" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $products->product_image->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($products->product_secimage->Visible) { // product_secimage ?>
	<div id="r_product_secimage" class="form-group">
		<label id="elh_products_product_secimage" class="col-sm-2 control-label ewLabel"><?php echo $products->product_secimage->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $products->product_secimage->CellAttributes() ?>>
<span id="el_products_product_secimage">
<div id="fd_x_product_secimage">
<span title="<?php echo $products->product_secimage->FldTitle() ? $products->product_secimage->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($products->product_secimage->ReadOnly || $products->product_secimage->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="products" data-field="x_product_secimage" name="x_product_secimage" id="x_product_secimage"<?php echo $products->product_secimage->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x_product_secimage" id= "fn_x_product_secimage" value="<?php echo $products->product_secimage->Upload->FileName ?>">
<?php if (@$_POST["fa_x_product_secimage"] == "0") { ?>
<input type="hidden" name="fa_x_product_secimage" id= "fa_x_product_secimage" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x_product_secimage" id= "fa_x_product_secimage" value="1">
<?php } ?>
<input type="hidden" name="fs_x_product_secimage" id= "fs_x_product_secimage" value="200">
<input type="hidden" name="fx_x_product_secimage" id= "fx_x_product_secimage" value="<?php echo $products->product_secimage->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_product_secimage" id= "fm_x_product_secimage" value="<?php echo $products->product_secimage->UploadMaxFileSize ?>">
</div>
<table id="ft_x_product_secimage" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $products->product_secimage->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($products->product_description->Visible) { // product_description ?>
	<div id="r_product_description" class="form-group">
		<label id="elh_products_product_description" class="col-sm-2 control-label ewLabel"><?php echo $products->product_description->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $products->product_description->CellAttributes() ?>>
<span id="el_products_product_description">
<?php ew_AppendClass($products->product_description->EditAttrs["class"], "editor"); ?>
<textarea data-table="products" data-field="x_product_description" name="x_product_description" id="x_product_description" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($products->product_description->getPlaceHolder()) ?>"<?php echo $products->product_description->EditAttributes() ?>><?php echo $products->product_description->EditValue ?></textarea>
<script type="text/javascript">
ew_CreateEditor("fproductsedit", "x_product_description", 35, 4, <?php echo ($products->product_description->ReadOnly || FALSE) ? "true" : "false" ?>);
</script>
</span>
<?php echo $products->product_description->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($products->feature_ledtype->Visible) { // feature_ledtype ?>
	<div id="r_feature_ledtype" class="form-group">
		<label id="elh_products_feature_ledtype" for="x_feature_ledtype" class="col-sm-2 control-label ewLabel"><?php echo $products->feature_ledtype->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $products->feature_ledtype->CellAttributes() ?>>
<span id="el_products_feature_ledtype">
<input type="text" data-table="products" data-field="x_feature_ledtype" name="x_feature_ledtype" id="x_feature_ledtype" size="30" maxlength="200" placeholder="<?php echo ew_HtmlEncode($products->feature_ledtype->getPlaceHolder()) ?>" value="<?php echo $products->feature_ledtype->EditValue ?>"<?php echo $products->feature_ledtype->EditAttributes() ?>>
</span>
<?php echo $products->feature_ledtype->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($products->feature_power->Visible) { // feature_power ?>
	<div id="r_feature_power" class="form-group">
		<label id="elh_products_feature_power" for="x_feature_power" class="col-sm-2 control-label ewLabel"><?php echo $products->feature_power->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $products->feature_power->CellAttributes() ?>>
<span id="el_products_feature_power">
<input type="text" data-table="products" data-field="x_feature_power" name="x_feature_power" id="x_feature_power" size="30" maxlength="200" placeholder="<?php echo ew_HtmlEncode($products->feature_power->getPlaceHolder()) ?>" value="<?php echo $products->feature_power->EditValue ?>"<?php echo $products->feature_power->EditAttributes() ?>>
</span>
<?php echo $products->feature_power->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($products->feature_lumen->Visible) { // feature_lumen ?>
	<div id="r_feature_lumen" class="form-group">
		<label id="elh_products_feature_lumen" for="x_feature_lumen" class="col-sm-2 control-label ewLabel"><?php echo $products->feature_lumen->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $products->feature_lumen->CellAttributes() ?>>
<span id="el_products_feature_lumen">
<input type="text" data-table="products" data-field="x_feature_lumen" name="x_feature_lumen" id="x_feature_lumen" size="30" maxlength="200" placeholder="<?php echo ew_HtmlEncode($products->feature_lumen->getPlaceHolder()) ?>" value="<?php echo $products->feature_lumen->EditValue ?>"<?php echo $products->feature_lumen->EditAttributes() ?>>
</span>
<?php echo $products->feature_lumen->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($products->feature_viewangle->Visible) { // feature_viewangle ?>
	<div id="r_feature_viewangle" class="form-group">
		<label id="elh_products_feature_viewangle" for="x_feature_viewangle" class="col-sm-2 control-label ewLabel"><?php echo $products->feature_viewangle->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $products->feature_viewangle->CellAttributes() ?>>
<span id="el_products_feature_viewangle">
<input type="text" data-table="products" data-field="x_feature_viewangle" name="x_feature_viewangle" id="x_feature_viewangle" size="30" maxlength="200" placeholder="<?php echo ew_HtmlEncode($products->feature_viewangle->getPlaceHolder()) ?>" value="<?php echo $products->feature_viewangle->EditValue ?>"<?php echo $products->feature_viewangle->EditAttributes() ?>>
</span>
<?php echo $products->feature_viewangle->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($products->feature_cri->Visible) { // feature_cri ?>
	<div id="r_feature_cri" class="form-group">
		<label id="elh_products_feature_cri" for="x_feature_cri" class="col-sm-2 control-label ewLabel"><?php echo $products->feature_cri->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $products->feature_cri->CellAttributes() ?>>
<span id="el_products_feature_cri">
<input type="text" data-table="products" data-field="x_feature_cri" name="x_feature_cri" id="x_feature_cri" size="30" maxlength="200" placeholder="<?php echo ew_HtmlEncode($products->feature_cri->getPlaceHolder()) ?>" value="<?php echo $products->feature_cri->EditValue ?>"<?php echo $products->feature_cri->EditAttributes() ?>>
</span>
<?php echo $products->feature_cri->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($products->feature_iprating->Visible) { // feature_iprating ?>
	<div id="r_feature_iprating" class="form-group">
		<label id="elh_products_feature_iprating" for="x_feature_iprating" class="col-sm-2 control-label ewLabel"><?php echo $products->feature_iprating->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $products->feature_iprating->CellAttributes() ?>>
<span id="el_products_feature_iprating">
<input type="text" data-table="products" data-field="x_feature_iprating" name="x_feature_iprating" id="x_feature_iprating" size="30" maxlength="200" placeholder="<?php echo ew_HtmlEncode($products->feature_iprating->getPlaceHolder()) ?>" value="<?php echo $products->feature_iprating->EditValue ?>"<?php echo $products->feature_iprating->EditAttributes() ?>>
</span>
<?php echo $products->feature_iprating->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($products->feature_colortemp->Visible) { // feature_colortemp ?>
	<div id="r_feature_colortemp" class="form-group">
		<label id="elh_products_feature_colortemp" for="x_feature_colortemp" class="col-sm-2 control-label ewLabel"><?php echo $products->feature_colortemp->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $products->feature_colortemp->CellAttributes() ?>>
<span id="el_products_feature_colortemp">
<input type="text" data-table="products" data-field="x_feature_colortemp" name="x_feature_colortemp" id="x_feature_colortemp" size="30" maxlength="200" placeholder="<?php echo ew_HtmlEncode($products->feature_colortemp->getPlaceHolder()) ?>" value="<?php echo $products->feature_colortemp->EditValue ?>"<?php echo $products->feature_colortemp->EditAttributes() ?>>
</span>
<?php echo $products->feature_colortemp->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($products->feature_body->Visible) { // feature_body ?>
	<div id="r_feature_body" class="form-group">
		<label id="elh_products_feature_body" for="x_feature_body" class="col-sm-2 control-label ewLabel"><?php echo $products->feature_body->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $products->feature_body->CellAttributes() ?>>
<span id="el_products_feature_body">
<input type="text" data-table="products" data-field="x_feature_body" name="x_feature_body" id="x_feature_body" size="30" maxlength="200" placeholder="<?php echo ew_HtmlEncode($products->feature_body->getPlaceHolder()) ?>" value="<?php echo $products->feature_body->EditValue ?>"<?php echo $products->feature_body->EditAttributes() ?>>
</span>
<?php echo $products->feature_body->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($products->feature_cutoutsize->Visible) { // feature_cutoutsize ?>
	<div id="r_feature_cutoutsize" class="form-group">
		<label id="elh_products_feature_cutoutsize" for="x_feature_cutoutsize" class="col-sm-2 control-label ewLabel"><?php echo $products->feature_cutoutsize->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $products->feature_cutoutsize->CellAttributes() ?>>
<span id="el_products_feature_cutoutsize">
<input type="text" data-table="products" data-field="x_feature_cutoutsize" name="x_feature_cutoutsize" id="x_feature_cutoutsize" size="30" maxlength="200" placeholder="<?php echo ew_HtmlEncode($products->feature_cutoutsize->getPlaceHolder()) ?>" value="<?php echo $products->feature_cutoutsize->EditValue ?>"<?php echo $products->feature_cutoutsize->EditAttributes() ?>>
</span>
<?php echo $products->feature_cutoutsize->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($products->feature_colors->Visible) { // feature_colors ?>
	<div id="r_feature_colors" class="form-group">
		<label id="elh_products_feature_colors" for="x_feature_colors" class="col-sm-2 control-label ewLabel"><?php echo $products->feature_colors->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $products->feature_colors->CellAttributes() ?>>
<span id="el_products_feature_colors">
<textarea data-table="products" data-field="x_feature_colors" name="x_feature_colors" id="x_feature_colors" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($products->feature_colors->getPlaceHolder()) ?>"<?php echo $products->feature_colors->EditAttributes() ?>><?php echo $products->feature_colors->EditValue ?></textarea>
</span>
<?php echo $products->feature_colors->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($products->feature_dimmable->Visible) { // feature_dimmable ?>
	<div id="r_feature_dimmable" class="form-group">
		<label id="elh_products_feature_dimmable" for="x_feature_dimmable" class="col-sm-2 control-label ewLabel"><?php echo $products->feature_dimmable->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $products->feature_dimmable->CellAttributes() ?>>
<span id="el_products_feature_dimmable">
<input type="text" data-table="products" data-field="x_feature_dimmable" name="x_feature_dimmable" id="x_feature_dimmable" size="30" maxlength="200" placeholder="<?php echo ew_HtmlEncode($products->feature_dimmable->getPlaceHolder()) ?>" value="<?php echo $products->feature_dimmable->EditValue ?>"<?php echo $products->feature_dimmable->EditAttributes() ?>>
</span>
<?php echo $products->feature_dimmable->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($products->feature_warranty->Visible) { // feature_warranty ?>
	<div id="r_feature_warranty" class="form-group">
		<label id="elh_products_feature_warranty" for="x_feature_warranty" class="col-sm-2 control-label ewLabel"><?php echo $products->feature_warranty->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $products->feature_warranty->CellAttributes() ?>>
<span id="el_products_feature_warranty">
<input type="text" data-table="products" data-field="x_feature_warranty" name="x_feature_warranty" id="x_feature_warranty" size="30" maxlength="200" placeholder="<?php echo ew_HtmlEncode($products->feature_warranty->getPlaceHolder()) ?>" value="<?php echo $products->feature_warranty->EditValue ?>"<?php echo $products->feature_warranty->EditAttributes() ?>>
</span>
<?php echo $products->feature_warranty->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($products->feature_application->Visible) { // feature_application ?>
	<div id="r_feature_application" class="form-group">
		<label id="elh_products_feature_application" for="x_feature_application" class="col-sm-2 control-label ewLabel"><?php echo $products->feature_application->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $products->feature_application->CellAttributes() ?>>
<span id="el_products_feature_application">
<textarea data-table="products" data-field="x_feature_application" name="x_feature_application" id="x_feature_application" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($products->feature_application->getPlaceHolder()) ?>"<?php echo $products->feature_application->EditAttributes() ?>><?php echo $products->feature_application->EditValue ?></textarea>
</span>
<?php echo $products->feature_application->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $products_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fproductsedit.Init();
</script>
<?php
$products_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$products_edit->Page_Terminate();
?>
