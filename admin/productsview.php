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

$products_view = NULL; // Initialize page object first

class cproducts_view extends cproducts {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{C3CE8554-8FA2-42A1-89B9-3DB1F25B77B3}";

	// Table name
	var $TableName = 'products';

	// Page object name
	var $PageObjName = 'products_view';

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

	// Page URLs
	var $AddUrl;
	var $EditUrl;
	var $CopyUrl;
	var $DeleteUrl;
	var $ViewUrl;
	var $ListUrl;

	// Export URLs
	var $ExportPrintUrl;
	var $ExportHtmlUrl;
	var $ExportExcelUrl;
	var $ExportWordUrl;
	var $ExportXmlUrl;
	var $ExportCsvUrl;
	var $ExportPdfUrl;

	// Custom export
	var $ExportExcelCustom = FALSE;
	var $ExportWordCustom = FALSE;
	var $ExportPdfCustom = FALSE;
	var $ExportEmailCustom = FALSE;

	// Update URLs
	var $InlineAddUrl;
	var $InlineCopyUrl;
	var $InlineEditUrl;
	var $GridAddUrl;
	var $GridEditUrl;
	var $MultiDeleteUrl;
	var $MultiUpdateUrl;

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
		$KeyUrl = "";
		if (@$_GET["product_id"] <> "") {
			$this->RecKey["product_id"] = $_GET["product_id"];
			$KeyUrl .= "&amp;product_id=" . urlencode($this->RecKey["product_id"]);
		}
		$this->ExportPrintUrl = $this->PageUrl() . "export=print" . $KeyUrl;
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html" . $KeyUrl;
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel" . $KeyUrl;
		$this->ExportWordUrl = $this->PageUrl() . "export=word" . $KeyUrl;
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml" . $KeyUrl;
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv" . $KeyUrl;
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf" . $KeyUrl;

		// Table object (sub_category)
		if (!isset($GLOBALS['sub_category'])) $GLOBALS['sub_category'] = new csub_category();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'view', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'products', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect($this->DBID);

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
	}

	// 
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

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
	var $ExportOptions; // Export options
	var $OtherOptions = array(); // Other options
	var $DisplayRecs = 1;
	var $DbMasterFilter;
	var $DbDetailFilter;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $RecCnt;
	var $RecKey = array();
	var $Recordset;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;
		$sReturnUrl = "";
		$bMatchRecord = FALSE;

		// Set up master/detail parameters
		$this->SetUpMasterParms();

		// Set up Breadcrumb
		if ($this->Export == "")
			$this->SetupBreadcrumb();
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET["product_id"] <> "") {
				$this->product_id->setQueryStringValue($_GET["product_id"]);
				$this->RecKey["product_id"] = $this->product_id->QueryStringValue;
			} elseif (@$_POST["product_id"] <> "") {
				$this->product_id->setFormValue($_POST["product_id"]);
				$this->RecKey["product_id"] = $this->product_id->FormValue;
			} else {
				$sReturnUrl = "productslist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "productslist.php"; // No matching record, return to list
					}
			}
		} else {
			$sReturnUrl = "productslist.php"; // Not page request, return to list
		}
		if ($sReturnUrl <> "")
			$this->Page_Terminate($sReturnUrl);

		// Render row
		$this->RowType = EW_ROWTYPE_VIEW;
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = &$options["action"];

		// Add
		$item = &$option->Add("add");
		$item->Body = "<a class=\"ewAction ewAdd\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageAddLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageAddLink")) . "\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("ViewPageAddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "");

		// Edit
		$item = &$option->Add("edit");
		$item->Body = "<a class=\"ewAction ewEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageEditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageEditLink")) . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("ViewPageEditLink") . "</a>";
		$item->Visible = ($this->EditUrl <> "");

		// Copy
		$item = &$option->Add("copy");
		$item->Body = "<a class=\"ewAction ewCopy\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageCopyLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageCopyLink")) . "\" href=\"" . ew_HtmlEncode($this->CopyUrl) . "\">" . $Language->Phrase("ViewPageCopyLink") . "</a>";
		$item->Visible = ($this->CopyUrl <> "");

		// Delete
		$item = &$option->Add("delete");
		$item->Body = "<a class=\"ewAction ewDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" href=\"" . ew_HtmlEncode($this->DeleteUrl) . "\">" . $Language->Phrase("ViewPageDeleteLink") . "</a>";
		$item->Visible = ($this->DeleteUrl <> "");

		// Set up action default
		$option = &$options["action"];
		$option->DropDownButtonPhrase = $Language->Phrase("ButtonActions");
		$option->UseImageAndText = TRUE;
		$option->UseDropDownButton = FALSE;
		$option->UseButtonGroup = TRUE;
		$item = &$option->Add($option->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
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
		$this->AddUrl = $this->GetAddUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();
		$this->ListUrl = $this->GetListUrl();
		$this->SetupOtherOptions();

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
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
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
		$PageId = "view";
		$Breadcrumb->Add("view", $PageId, $url);
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

	// Page Exporting event
	// $this->ExportDoc = export document object
	function Page_Exporting() {

		//$this->ExportDoc->Text = "my header"; // Export header
		//return FALSE; // Return FALSE to skip default export and use Row_Export event

		return TRUE; // Return TRUE to use default export and skip Row_Export event
	}

	// Row Export event
	// $this->ExportDoc = export document object
	function Row_Export($rs) {

	    //$this->ExportDoc->Text .= "my content"; // Build HTML with field value: $rs["MyField"] or $this->MyField->ViewValue
	}

	// Page Exported event
	// $this->ExportDoc = export document object
	function Page_Exported() {

		//$this->ExportDoc->Text .= "my footer"; // Export footer
		//echo $this->ExportDoc->Text;

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($products_view)) $products_view = new cproducts_view();

// Page init
$products_view->Page_Init();

// Page main
$products_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$products_view->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "view";
var CurrentForm = fproductsview = new ew_Form("fproductsview", "view");

// Form_CustomValidate event
fproductsview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fproductsview.ValidateRequired = true;
<?php } else { ?>
fproductsview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fproductsview.Lists["x_category_id"] = {"LinkField":"x_category_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_category_name","","",""],"ParentFields":[],"ChildFields":["x_scat_id"],"FilterFields":[],"Options":[],"Template":""};
fproductsview.Lists["x_scat_id"] = {"LinkField":"x_scat_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_scat_name","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php $products_view->ExportOptions->Render("body") ?>
<?php
	foreach ($products_view->OtherOptions as &$option)
		$option->Render("body");
?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $products_view->ShowPageHeader(); ?>
<?php
$products_view->ShowMessage();
?>
<form name="fproductsview" id="fproductsview" class="form-inline ewForm ewViewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($products_view->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $products_view->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="products">
<table class="table table-bordered table-striped ewViewTable">
<?php if ($products->category_id->Visible) { // category_id ?>
	<tr id="r_category_id">
		<td><span id="elh_products_category_id"><?php echo $products->category_id->FldCaption() ?></span></td>
		<td data-name="category_id"<?php echo $products->category_id->CellAttributes() ?>>
<span id="el_products_category_id">
<span<?php echo $products->category_id->ViewAttributes() ?>>
<?php echo $products->category_id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($products->scat_id->Visible) { // scat_id ?>
	<tr id="r_scat_id">
		<td><span id="elh_products_scat_id"><?php echo $products->scat_id->FldCaption() ?></span></td>
		<td data-name="scat_id"<?php echo $products->scat_id->CellAttributes() ?>>
<span id="el_products_scat_id">
<span<?php echo $products->scat_id->ViewAttributes() ?>>
<?php echo $products->scat_id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($products->product_name->Visible) { // product_name ?>
	<tr id="r_product_name">
		<td><span id="elh_products_product_name"><?php echo $products->product_name->FldCaption() ?></span></td>
		<td data-name="product_name"<?php echo $products->product_name->CellAttributes() ?>>
<span id="el_products_product_name">
<span<?php echo $products->product_name->ViewAttributes() ?>>
<?php echo $products->product_name->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($products->product_image->Visible) { // product_image ?>
	<tr id="r_product_image">
		<td><span id="elh_products_product_image"><?php echo $products->product_image->FldCaption() ?></span></td>
		<td data-name="product_image"<?php echo $products->product_image->CellAttributes() ?>>
<span id="el_products_product_image">
<span<?php echo $products->product_image->ViewAttributes() ?>>
<?php echo ew_GetFileViewTag($products->product_image, $products->product_image->ViewValue) ?>
</span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($products->product_secimage->Visible) { // product_secimage ?>
	<tr id="r_product_secimage">
		<td><span id="elh_products_product_secimage"><?php echo $products->product_secimage->FldCaption() ?></span></td>
		<td data-name="product_secimage"<?php echo $products->product_secimage->CellAttributes() ?>>
<span id="el_products_product_secimage">
<span<?php echo $products->product_secimage->ViewAttributes() ?>>
<?php echo ew_GetFileViewTag($products->product_secimage, $products->product_secimage->ViewValue) ?>
</span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($products->product_description->Visible) { // product_description ?>
	<tr id="r_product_description">
		<td><span id="elh_products_product_description"><?php echo $products->product_description->FldCaption() ?></span></td>
		<td data-name="product_description"<?php echo $products->product_description->CellAttributes() ?>>
<span id="el_products_product_description">
<span<?php echo $products->product_description->ViewAttributes() ?>>
<?php echo $products->product_description->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($products->feature_ledtype->Visible) { // feature_ledtype ?>
	<tr id="r_feature_ledtype">
		<td><span id="elh_products_feature_ledtype"><?php echo $products->feature_ledtype->FldCaption() ?></span></td>
		<td data-name="feature_ledtype"<?php echo $products->feature_ledtype->CellAttributes() ?>>
<span id="el_products_feature_ledtype">
<span<?php echo $products->feature_ledtype->ViewAttributes() ?>>
<?php echo $products->feature_ledtype->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($products->feature_power->Visible) { // feature_power ?>
	<tr id="r_feature_power">
		<td><span id="elh_products_feature_power"><?php echo $products->feature_power->FldCaption() ?></span></td>
		<td data-name="feature_power"<?php echo $products->feature_power->CellAttributes() ?>>
<span id="el_products_feature_power">
<span<?php echo $products->feature_power->ViewAttributes() ?>>
<?php echo $products->feature_power->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($products->feature_lumen->Visible) { // feature_lumen ?>
	<tr id="r_feature_lumen">
		<td><span id="elh_products_feature_lumen"><?php echo $products->feature_lumen->FldCaption() ?></span></td>
		<td data-name="feature_lumen"<?php echo $products->feature_lumen->CellAttributes() ?>>
<span id="el_products_feature_lumen">
<span<?php echo $products->feature_lumen->ViewAttributes() ?>>
<?php echo $products->feature_lumen->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($products->feature_viewangle->Visible) { // feature_viewangle ?>
	<tr id="r_feature_viewangle">
		<td><span id="elh_products_feature_viewangle"><?php echo $products->feature_viewangle->FldCaption() ?></span></td>
		<td data-name="feature_viewangle"<?php echo $products->feature_viewangle->CellAttributes() ?>>
<span id="el_products_feature_viewangle">
<span<?php echo $products->feature_viewangle->ViewAttributes() ?>>
<?php echo $products->feature_viewangle->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($products->feature_cri->Visible) { // feature_cri ?>
	<tr id="r_feature_cri">
		<td><span id="elh_products_feature_cri"><?php echo $products->feature_cri->FldCaption() ?></span></td>
		<td data-name="feature_cri"<?php echo $products->feature_cri->CellAttributes() ?>>
<span id="el_products_feature_cri">
<span<?php echo $products->feature_cri->ViewAttributes() ?>>
<?php echo $products->feature_cri->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($products->feature_iprating->Visible) { // feature_iprating ?>
	<tr id="r_feature_iprating">
		<td><span id="elh_products_feature_iprating"><?php echo $products->feature_iprating->FldCaption() ?></span></td>
		<td data-name="feature_iprating"<?php echo $products->feature_iprating->CellAttributes() ?>>
<span id="el_products_feature_iprating">
<span<?php echo $products->feature_iprating->ViewAttributes() ?>>
<?php echo $products->feature_iprating->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($products->feature_colortemp->Visible) { // feature_colortemp ?>
	<tr id="r_feature_colortemp">
		<td><span id="elh_products_feature_colortemp"><?php echo $products->feature_colortemp->FldCaption() ?></span></td>
		<td data-name="feature_colortemp"<?php echo $products->feature_colortemp->CellAttributes() ?>>
<span id="el_products_feature_colortemp">
<span<?php echo $products->feature_colortemp->ViewAttributes() ?>>
<?php echo $products->feature_colortemp->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($products->feature_body->Visible) { // feature_body ?>
	<tr id="r_feature_body">
		<td><span id="elh_products_feature_body"><?php echo $products->feature_body->FldCaption() ?></span></td>
		<td data-name="feature_body"<?php echo $products->feature_body->CellAttributes() ?>>
<span id="el_products_feature_body">
<span<?php echo $products->feature_body->ViewAttributes() ?>>
<?php echo $products->feature_body->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($products->feature_cutoutsize->Visible) { // feature_cutoutsize ?>
	<tr id="r_feature_cutoutsize">
		<td><span id="elh_products_feature_cutoutsize"><?php echo $products->feature_cutoutsize->FldCaption() ?></span></td>
		<td data-name="feature_cutoutsize"<?php echo $products->feature_cutoutsize->CellAttributes() ?>>
<span id="el_products_feature_cutoutsize">
<span<?php echo $products->feature_cutoutsize->ViewAttributes() ?>>
<?php echo $products->feature_cutoutsize->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($products->feature_colors->Visible) { // feature_colors ?>
	<tr id="r_feature_colors">
		<td><span id="elh_products_feature_colors"><?php echo $products->feature_colors->FldCaption() ?></span></td>
		<td data-name="feature_colors"<?php echo $products->feature_colors->CellAttributes() ?>>
<span id="el_products_feature_colors">
<span<?php echo $products->feature_colors->ViewAttributes() ?>>
<?php echo $products->feature_colors->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($products->feature_dimmable->Visible) { // feature_dimmable ?>
	<tr id="r_feature_dimmable">
		<td><span id="elh_products_feature_dimmable"><?php echo $products->feature_dimmable->FldCaption() ?></span></td>
		<td data-name="feature_dimmable"<?php echo $products->feature_dimmable->CellAttributes() ?>>
<span id="el_products_feature_dimmable">
<span<?php echo $products->feature_dimmable->ViewAttributes() ?>>
<?php echo $products->feature_dimmable->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($products->feature_warranty->Visible) { // feature_warranty ?>
	<tr id="r_feature_warranty">
		<td><span id="elh_products_feature_warranty"><?php echo $products->feature_warranty->FldCaption() ?></span></td>
		<td data-name="feature_warranty"<?php echo $products->feature_warranty->CellAttributes() ?>>
<span id="el_products_feature_warranty">
<span<?php echo $products->feature_warranty->ViewAttributes() ?>>
<?php echo $products->feature_warranty->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($products->feature_application->Visible) { // feature_application ?>
	<tr id="r_feature_application">
		<td><span id="elh_products_feature_application"><?php echo $products->feature_application->FldCaption() ?></span></td>
		<td data-name="feature_application"<?php echo $products->feature_application->CellAttributes() ?>>
<span id="el_products_feature_application">
<span<?php echo $products->feature_application->ViewAttributes() ?>>
<?php echo $products->feature_application->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</form>
<script type="text/javascript">
fproductsview.Init();
</script>
<?php
$products_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$products_view->Page_Terminate();
?>
