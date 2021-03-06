<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "sub_categoryinfo.php" ?>
<?php include_once "categoryinfo.php" ?>
<?php include_once "productsgridcls.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$sub_category_edit = NULL; // Initialize page object first

class csub_category_edit extends csub_category {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{C3CE8554-8FA2-42A1-89B9-3DB1F25B77B3}";

	// Table name
	var $TableName = 'sub_category';

	// Page object name
	var $PageObjName = 'sub_category_edit';

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

		// Table object (sub_category)
		if (!isset($GLOBALS["sub_category"]) || get_class($GLOBALS["sub_category"]) == "csub_category") {
			$GLOBALS["sub_category"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["sub_category"];
		}

		// Table object (category)
		if (!isset($GLOBALS['category'])) $GLOBALS['category'] = new ccategory();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'sub_category', TRUE);

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
		$this->scat_id->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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

			// Process auto fill for detail table 'products'
			if (@$_POST["grid"] == "fproductsgrid") {
				if (!isset($GLOBALS["products_grid"])) $GLOBALS["products_grid"] = new cproducts_grid;
				$GLOBALS["products_grid"]->Page_Init();
				$this->Page_Terminate();
				exit();
			}
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
		global $EW_EXPORT, $sub_category;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($sub_category);
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
		if (@$_GET["scat_id"] <> "") {
			$this->scat_id->setQueryStringValue($_GET["scat_id"]);
		}

		// Set up master detail parameters
		$this->SetUpMasterParms();

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values

			// Set up detail parameters
			$this->SetUpDetailParms();
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->scat_id->CurrentValue == "")
			$this->Page_Terminate("sub_categorylist.php"); // Invalid key, return to list

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
					$this->Page_Terminate("sub_categorylist.php"); // No matching record, return to list
				}

				// Set up detail parameters
				$this->SetUpDetailParms();
				break;
			Case "U": // Update
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					if ($this->getCurrentDetailTable() <> "") // Master/detail edit
						$sReturnUrl = $this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=" . $this->getCurrentDetailTable()); // Master/Detail view page
					else
						$sReturnUrl = $this->getReturnUrl();
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Restore form values if update failed

					// Set up detail parameters
					$this->SetUpDetailParms();
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
		$this->scat_picture->Upload->Index = $objForm->Index;
		$this->scat_picture->Upload->UploadFile();
		$this->scat_picture->CurrentValue = $this->scat_picture->Upload->FileName;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->scat_id->FldIsDetailKey)
			$this->scat_id->setFormValue($objForm->GetValue("x_scat_id"));
		if (!$this->category_id->FldIsDetailKey) {
			$this->category_id->setFormValue($objForm->GetValue("x_category_id"));
		}
		if (!$this->scat_name->FldIsDetailKey) {
			$this->scat_name->setFormValue($objForm->GetValue("x_scat_name"));
		}
		if (!$this->scat_description->FldIsDetailKey) {
			$this->scat_description->setFormValue($objForm->GetValue("x_scat_description"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->scat_id->CurrentValue = $this->scat_id->FormValue;
		$this->category_id->CurrentValue = $this->category_id->FormValue;
		$this->scat_name->CurrentValue = $this->scat_name->FormValue;
		$this->scat_description->CurrentValue = $this->scat_description->FormValue;
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
		$this->scat_id->setDbValue($rs->fields('scat_id'));
		$this->category_id->setDbValue($rs->fields('category_id'));
		$this->scat_name->setDbValue($rs->fields('scat_name'));
		$this->scat_description->setDbValue($rs->fields('scat_description'));
		$this->scat_picture->Upload->DbValue = $rs->fields('scat_picture');
		$this->scat_picture->CurrentValue = $this->scat_picture->Upload->DbValue;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->scat_id->DbValue = $row['scat_id'];
		$this->category_id->DbValue = $row['category_id'];
		$this->scat_name->DbValue = $row['scat_name'];
		$this->scat_description->DbValue = $row['scat_description'];
		$this->scat_picture->Upload->DbValue = $row['scat_picture'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// scat_id
		// category_id
		// scat_name
		// scat_description
		// scat_picture

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// scat_id
		$this->scat_id->ViewValue = $this->scat_id->CurrentValue;
		$this->scat_id->ViewCustomAttributes = "";

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

		// scat_name
		$this->scat_name->ViewValue = $this->scat_name->CurrentValue;
		$this->scat_name->ViewCustomAttributes = "";

		// scat_description
		$this->scat_description->ViewValue = $this->scat_description->CurrentValue;
		$this->scat_description->ViewCustomAttributes = "";

		// scat_picture
		if (!ew_Empty($this->scat_picture->Upload->DbValue)) {
			$this->scat_picture->ViewValue = $this->scat_picture->Upload->DbValue;
		} else {
			$this->scat_picture->ViewValue = "";
		}
		$this->scat_picture->ViewCustomAttributes = "";

			// scat_id
			$this->scat_id->LinkCustomAttributes = "";
			$this->scat_id->HrefValue = "";
			$this->scat_id->TooltipValue = "";

			// category_id
			$this->category_id->LinkCustomAttributes = "";
			$this->category_id->HrefValue = "";
			$this->category_id->TooltipValue = "";

			// scat_name
			$this->scat_name->LinkCustomAttributes = "";
			$this->scat_name->HrefValue = "";
			$this->scat_name->TooltipValue = "";

			// scat_description
			$this->scat_description->LinkCustomAttributes = "";
			$this->scat_description->HrefValue = "";
			$this->scat_description->TooltipValue = "";

			// scat_picture
			$this->scat_picture->LinkCustomAttributes = "";
			$this->scat_picture->HrefValue = "";
			$this->scat_picture->HrefValue2 = $this->scat_picture->UploadPath . $this->scat_picture->Upload->DbValue;
			$this->scat_picture->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// scat_id
			$this->scat_id->EditAttrs["class"] = "form-control";
			$this->scat_id->EditCustomAttributes = "";
			$this->scat_id->EditValue = $this->scat_id->CurrentValue;
			$this->scat_id->ViewCustomAttributes = "";

			// category_id
			$this->category_id->EditAttrs["class"] = "form-control";
			$this->category_id->EditCustomAttributes = "";
			if ($this->category_id->getSessionValue() <> "") {
				$this->category_id->CurrentValue = $this->category_id->getSessionValue();
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
			} else {
			if (trim(strval($this->category_id->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`category_id`" . ew_SearchString("=", $this->category_id->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `category_id`, `category_name` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `category`";
			$sWhereWrk = "";
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->category_id, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->category_id->EditValue = $arwrk;
			}

			// scat_name
			$this->scat_name->EditAttrs["class"] = "form-control";
			$this->scat_name->EditCustomAttributes = "";
			$this->scat_name->EditValue = ew_HtmlEncode($this->scat_name->CurrentValue);
			$this->scat_name->PlaceHolder = ew_RemoveHtml($this->scat_name->FldCaption());

			// scat_description
			$this->scat_description->EditAttrs["class"] = "form-control";
			$this->scat_description->EditCustomAttributes = "";
			$this->scat_description->EditValue = ew_HtmlEncode($this->scat_description->CurrentValue);
			$this->scat_description->PlaceHolder = ew_RemoveHtml($this->scat_description->FldCaption());

			// scat_picture
			$this->scat_picture->EditAttrs["class"] = "form-control";
			$this->scat_picture->EditCustomAttributes = "";
			if (!ew_Empty($this->scat_picture->Upload->DbValue)) {
				$this->scat_picture->EditValue = $this->scat_picture->Upload->DbValue;
			} else {
				$this->scat_picture->EditValue = "";
			}
			if (!ew_Empty($this->scat_picture->CurrentValue))
				$this->scat_picture->Upload->FileName = $this->scat_picture->CurrentValue;
			if ($this->CurrentAction == "I" && !$this->EventCancelled) ew_RenderUploadField($this->scat_picture);

			// Edit refer script
			// scat_id

			$this->scat_id->HrefValue = "";

			// category_id
			$this->category_id->HrefValue = "";

			// scat_name
			$this->scat_name->HrefValue = "";

			// scat_description
			$this->scat_description->HrefValue = "";

			// scat_picture
			$this->scat_picture->HrefValue = "";
			$this->scat_picture->HrefValue2 = $this->scat_picture->UploadPath . $this->scat_picture->Upload->DbValue;
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
		if (!$this->scat_name->FldIsDetailKey && !is_null($this->scat_name->FormValue) && $this->scat_name->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->scat_name->FldCaption(), $this->scat_name->ReqErrMsg));
		}
		if (!$this->scat_description->FldIsDetailKey && !is_null($this->scat_description->FormValue) && $this->scat_description->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->scat_description->FldCaption(), $this->scat_description->ReqErrMsg));
		}
		if ($this->scat_picture->Upload->FileName == "" && !$this->scat_picture->Upload->KeepFile) {
			ew_AddMessage($gsFormError, str_replace("%s", $this->scat_picture->FldCaption(), $this->scat_picture->ReqErrMsg));
		}

		// Validate detail grid
		$DetailTblVar = explode(",", $this->getCurrentDetailTable());
		if (in_array("products", $DetailTblVar) && $GLOBALS["products"]->DetailEdit) {
			if (!isset($GLOBALS["products_grid"])) $GLOBALS["products_grid"] = new cproducts_grid(); // get detail page object
			$GLOBALS["products_grid"]->ValidateGridForm();
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

			// Begin transaction
			if ($this->getCurrentDetailTable() <> "")
				$conn->BeginTrans();

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$rsnew = array();

			// category_id
			$this->category_id->SetDbValueDef($rsnew, $this->category_id->CurrentValue, 0, $this->category_id->ReadOnly);

			// scat_name
			$this->scat_name->SetDbValueDef($rsnew, $this->scat_name->CurrentValue, "", $this->scat_name->ReadOnly);

			// scat_description
			$this->scat_description->SetDbValueDef($rsnew, $this->scat_description->CurrentValue, "", $this->scat_description->ReadOnly);

			// scat_picture
			if (!($this->scat_picture->ReadOnly) && !$this->scat_picture->Upload->KeepFile) {
				$this->scat_picture->Upload->DbValue = $rsold['scat_picture']; // Get original value
				if ($this->scat_picture->Upload->FileName == "") {
					$rsnew['scat_picture'] = NULL;
				} else {
					$rsnew['scat_picture'] = $this->scat_picture->Upload->FileName;
				}
			}
			if (!$this->scat_picture->Upload->KeepFile) {
				if (!ew_Empty($this->scat_picture->Upload->Value)) {
					if ($this->scat_picture->Upload->FileName == $this->scat_picture->Upload->DbValue) { // Overwrite if same file name
						$this->scat_picture->Upload->DbValue = ""; // No need to delete any more
					} else {
						$rsnew['scat_picture'] = ew_UploadFileNameEx(ew_UploadPathEx(TRUE, $this->scat_picture->UploadPath), $rsnew['scat_picture']); // Get new file name
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
					if (!$this->scat_picture->Upload->KeepFile) {
						if (!ew_Empty($this->scat_picture->Upload->Value)) {
							$this->scat_picture->Upload->SaveToFile($this->scat_picture->UploadPath, $rsnew['scat_picture'], TRUE);
						}
						if ($this->scat_picture->Upload->DbValue <> "")
							@unlink(ew_UploadPathEx(TRUE, $this->scat_picture->OldUploadPath) . $this->scat_picture->Upload->DbValue);
					}
				}

				// Update detail records
				if ($EditRow) {
					$DetailTblVar = explode(",", $this->getCurrentDetailTable());
					if (in_array("products", $DetailTblVar) && $GLOBALS["products"]->DetailEdit) {
						if (!isset($GLOBALS["products_grid"])) $GLOBALS["products_grid"] = new cproducts_grid(); // Get detail page object
						$EditRow = $GLOBALS["products_grid"]->GridUpdate();
					}
				}

				// Commit/Rollback transaction
				if ($this->getCurrentDetailTable() <> "") {
					if ($EditRow) {
						$conn->CommitTrans(); // Commit transaction
					} else {
						$conn->RollbackTrans(); // Rollback transaction
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

		// scat_picture
		ew_CleanUploadTempPath($this->scat_picture, $this->scat_picture->Upload->Index);
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
			if ($sMasterTblVar == "category") {
				$bValidMaster = TRUE;
				if (@$_GET["fk_category_id"] <> "") {
					$GLOBALS["category"]->category_id->setQueryStringValue($_GET["fk_category_id"]);
					$this->category_id->setQueryStringValue($GLOBALS["category"]->category_id->QueryStringValue);
					$this->category_id->setSessionValue($this->category_id->QueryStringValue);
					if (!is_numeric($GLOBALS["category"]->category_id->QueryStringValue)) $bValidMaster = FALSE;
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
			if ($sMasterTblVar <> "category") {
				if ($this->category_id->QueryStringValue == "") $this->category_id->setSessionValue("");
			}
		}
		$this->DbMasterFilter = $this->GetMasterFilter(); // Get master filter
		$this->DbDetailFilter = $this->GetDetailFilter(); // Get detail filter
	}

	// Set up detail parms based on QueryString
	function SetUpDetailParms() {

		// Get the keys for master table
		if (isset($_GET[EW_TABLE_SHOW_DETAIL])) {
			$sDetailTblVar = $_GET[EW_TABLE_SHOW_DETAIL];
			$this->setCurrentDetailTable($sDetailTblVar);
		} else {
			$sDetailTblVar = $this->getCurrentDetailTable();
		}
		if ($sDetailTblVar <> "") {
			$DetailTblVar = explode(",", $sDetailTblVar);
			if (in_array("products", $DetailTblVar)) {
				if (!isset($GLOBALS["products_grid"]))
					$GLOBALS["products_grid"] = new cproducts_grid;
				if ($GLOBALS["products_grid"]->DetailEdit) {
					$GLOBALS["products_grid"]->CurrentMode = "edit";
					$GLOBALS["products_grid"]->CurrentAction = "gridedit";

					// Save current master table to detail table
					$GLOBALS["products_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["products_grid"]->setStartRecordNumber(1);
					$GLOBALS["products_grid"]->scat_id->FldIsDetailKey = TRUE;
					$GLOBALS["products_grid"]->scat_id->CurrentValue = $this->scat_id->CurrentValue;
					$GLOBALS["products_grid"]->scat_id->setSessionValue($GLOBALS["products_grid"]->scat_id->CurrentValue);
				}
			}
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, "sub_categorylist.php", "", $this->TableVar, TRUE);
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
if (!isset($sub_category_edit)) $sub_category_edit = new csub_category_edit();

// Page init
$sub_category_edit->Page_Init();

// Page main
$sub_category_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$sub_category_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fsub_categoryedit = new ew_Form("fsub_categoryedit", "edit");

// Validate form
fsub_categoryedit.Validate = function() {
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
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $sub_category->category_id->FldCaption(), $sub_category->category_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_scat_name");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $sub_category->scat_name->FldCaption(), $sub_category->scat_name->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_scat_description");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $sub_category->scat_description->FldCaption(), $sub_category->scat_description->ReqErrMsg)) ?>");
			felm = this.GetElements("x" + infix + "_scat_picture");
			elm = this.GetElements("fn_x" + infix + "_scat_picture");
			if (felm && elm && !ew_HasValue(elm))
				return this.OnError(felm, "<?php echo ew_JsEncode2(str_replace("%s", $sub_category->scat_picture->FldCaption(), $sub_category->scat_picture->ReqErrMsg)) ?>");

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
fsub_categoryedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fsub_categoryedit.ValidateRequired = true;
<?php } else { ?>
fsub_categoryedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fsub_categoryedit.Lists["x_category_id"] = {"LinkField":"x_category_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_category_name","","",""],"ParentFields":[],"ChildFields":["products x_category_id"],"FilterFields":[],"Options":[],"Template":""};

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
<?php $sub_category_edit->ShowPageHeader(); ?>
<?php
$sub_category_edit->ShowMessage();
?>
<form name="fsub_categoryedit" id="fsub_categoryedit" class="<?php echo $sub_category_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($sub_category_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $sub_category_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="sub_category">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<div>
<?php if ($sub_category->scat_id->Visible) { // scat_id ?>
	<div id="r_scat_id" class="form-group">
		<label id="elh_sub_category_scat_id" class="col-sm-2 control-label ewLabel"><?php echo $sub_category->scat_id->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $sub_category->scat_id->CellAttributes() ?>>
<span id="el_sub_category_scat_id">
<span<?php echo $sub_category->scat_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $sub_category->scat_id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="sub_category" data-field="x_scat_id" name="x_scat_id" id="x_scat_id" value="<?php echo ew_HtmlEncode($sub_category->scat_id->CurrentValue) ?>">
<?php echo $sub_category->scat_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sub_category->category_id->Visible) { // category_id ?>
	<div id="r_category_id" class="form-group">
		<label id="elh_sub_category_category_id" for="x_category_id" class="col-sm-2 control-label ewLabel"><?php echo $sub_category->category_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $sub_category->category_id->CellAttributes() ?>>
<?php if ($sub_category->category_id->getSessionValue() <> "") { ?>
<span id="el_sub_category_category_id">
<span<?php echo $sub_category->category_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $sub_category->category_id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x_category_id" name="x_category_id" value="<?php echo ew_HtmlEncode($sub_category->category_id->CurrentValue) ?>">
<?php } else { ?>
<span id="el_sub_category_category_id">
<select data-table="sub_category" data-field="x_category_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($sub_category->category_id->DisplayValueSeparator) ? json_encode($sub_category->category_id->DisplayValueSeparator) : $sub_category->category_id->DisplayValueSeparator) ?>" id="x_category_id" name="x_category_id"<?php echo $sub_category->category_id->EditAttributes() ?>>
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
<input type="hidden" name="s_x_category_id" id="s_x_category_id" value="<?php echo $sub_category->category_id->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php echo $sub_category->category_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sub_category->scat_name->Visible) { // scat_name ?>
	<div id="r_scat_name" class="form-group">
		<label id="elh_sub_category_scat_name" for="x_scat_name" class="col-sm-2 control-label ewLabel"><?php echo $sub_category->scat_name->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $sub_category->scat_name->CellAttributes() ?>>
<span id="el_sub_category_scat_name">
<textarea data-table="sub_category" data-field="x_scat_name" name="x_scat_name" id="x_scat_name" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($sub_category->scat_name->getPlaceHolder()) ?>"<?php echo $sub_category->scat_name->EditAttributes() ?>><?php echo $sub_category->scat_name->EditValue ?></textarea>
</span>
<?php echo $sub_category->scat_name->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sub_category->scat_description->Visible) { // scat_description ?>
	<div id="r_scat_description" class="form-group">
		<label id="elh_sub_category_scat_description" class="col-sm-2 control-label ewLabel"><?php echo $sub_category->scat_description->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $sub_category->scat_description->CellAttributes() ?>>
<span id="el_sub_category_scat_description">
<?php ew_AppendClass($sub_category->scat_description->EditAttrs["class"], "editor"); ?>
<textarea data-table="sub_category" data-field="x_scat_description" name="x_scat_description" id="x_scat_description" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($sub_category->scat_description->getPlaceHolder()) ?>"<?php echo $sub_category->scat_description->EditAttributes() ?>><?php echo $sub_category->scat_description->EditValue ?></textarea>
<script type="text/javascript">
ew_CreateEditor("fsub_categoryedit", "x_scat_description", 35, 4, <?php echo ($sub_category->scat_description->ReadOnly || FALSE) ? "true" : "false" ?>);
</script>
</span>
<?php echo $sub_category->scat_description->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($sub_category->scat_picture->Visible) { // scat_picture ?>
	<div id="r_scat_picture" class="form-group">
		<label id="elh_sub_category_scat_picture" class="col-sm-2 control-label ewLabel"><?php echo $sub_category->scat_picture->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $sub_category->scat_picture->CellAttributes() ?>>
<span id="el_sub_category_scat_picture">
<div id="fd_x_scat_picture">
<span title="<?php echo $sub_category->scat_picture->FldTitle() ? $sub_category->scat_picture->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($sub_category->scat_picture->ReadOnly || $sub_category->scat_picture->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="sub_category" data-field="x_scat_picture" name="x_scat_picture" id="x_scat_picture"<?php echo $sub_category->scat_picture->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x_scat_picture" id= "fn_x_scat_picture" value="<?php echo $sub_category->scat_picture->Upload->FileName ?>">
<?php if (@$_POST["fa_x_scat_picture"] == "0") { ?>
<input type="hidden" name="fa_x_scat_picture" id= "fa_x_scat_picture" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x_scat_picture" id= "fa_x_scat_picture" value="1">
<?php } ?>
<input type="hidden" name="fs_x_scat_picture" id= "fs_x_scat_picture" value="200">
<input type="hidden" name="fx_x_scat_picture" id= "fx_x_scat_picture" value="<?php echo $sub_category->scat_picture->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_scat_picture" id= "fm_x_scat_picture" value="<?php echo $sub_category->scat_picture->UploadMaxFileSize ?>">
</div>
<table id="ft_x_scat_picture" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $sub_category->scat_picture->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php
	if (in_array("products", explode(",", $sub_category->getCurrentDetailTable())) && $products->DetailEdit) {
?>
<?php if ($sub_category->getCurrentDetailTable() <> "") { ?>
<h4 class="ewDetailCaption"><?php echo $Language->TablePhrase("products", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "productsgrid.php" ?>
<?php } ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $sub_category_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fsub_categoryedit.Init();
</script>
<?php
$sub_category_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$sub_category_edit->Page_Terminate();
?>
