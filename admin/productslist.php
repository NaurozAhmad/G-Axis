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

$products_list = NULL; // Initialize page object first

class cproducts_list extends cproducts {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{C3CE8554-8FA2-42A1-89B9-3DB1F25B77B3}";

	// Table name
	var $TableName = 'products';

	// Page object name
	var $PageObjName = 'products_list';

	// Grid form hidden field names
	var $FormName = 'fproductslist';
	var $FormActionName = 'k_action';
	var $FormKeyName = 'k_key';
	var $FormOldKeyName = 'k_oldkey';
	var $FormBlankRowName = 'k_blankrow';
	var $FormKeyCountName = 'key_count';

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

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "productsadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "productsdelete.php";
		$this->MultiUpdateUrl = "productsupdate.php";

		// Table object (sub_category)
		if (!isset($GLOBALS['sub_category'])) $GLOBALS['sub_category'] = new csub_category();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'products', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect($this->DBID);

		// List options
		$this->ListOptions = new cListOptions();
		$this->ListOptions->TableVar = $this->TableVar;

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['addedit'] = new cListOptions();
		$this->OtherOptions['addedit']->Tag = "div";
		$this->OtherOptions['addedit']->TagClassName = "ewAddEditOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";

		// Filter options
		$this->FilterOptions = new cListOptions();
		$this->FilterOptions->Tag = "div";
		$this->FilterOptions->TagClassName = "ewFilterOption fproductslistsrch";

		// List actions
		$this->ListActions = new cListActions();
	}

	// 
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// Create form object
		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

		// Get grid add count
		$gridaddcnt = @$_GET[EW_TABLE_GRID_ADD_ROW_COUNT];
		if (is_numeric($gridaddcnt) && $gridaddcnt > 0)
			$this->GridAddRowCount = $gridaddcnt;

		// Set up list options
		$this->SetupListOptions();
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

		// Setup other options
		$this->SetupOtherOptions();

		// Set up custom action (compatible with old version)
		foreach ($this->CustomActions as $name => $action)
			$this->ListActions->Add($name, $action);

		// Show checkbox column if multiple action
		foreach ($this->ListActions->Items as $listaction) {
			if ($listaction->Select == EW_ACTION_MULTIPLE && $listaction->Allow) {
				$this->ListOptions->Items["checkbox"]->Visible = TRUE;
				break;
			}
		}
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

	// Class variables
	var $ListOptions; // List options
	var $ExportOptions; // Export options
	var $SearchOptions; // Search options
	var $OtherOptions = array(); // Other options
	var $FilterOptions; // Filter options
	var $ListActions; // List actions
	var $SelectedCount = 0;
	var $SelectedIndex = 0;
	var $DisplayRecs = 20;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $Pager;
	var $DefaultSearchWhere = ""; // Default search WHERE clause
	var $SearchWhere = ""; // Search WHERE clause
	var $RecCnt = 0; // Record count
	var $EditRowCnt;
	var $StartRowCnt = 1;
	var $RowCnt = 0;
	var $Attrs = array(); // Row attributes and cell attributes
	var $RowIndex = 0; // Row index
	var $KeyCount = 0; // Key count
	var $RowAction = ""; // Row action
	var $RowOldKey = ""; // Row old key (for copy)
	var $RecPerRow = 0;
	var $MultiColumnClass;
	var $MultiColumnEditClass = "col-sm-12";
	var $MultiColumnCnt = 12;
	var $MultiColumnEditCnt = 12;
	var $GridCnt = 0;
	var $ColCnt = 0;
	var $DbMasterFilter = ""; // Master filter
	var $DbDetailFilter = ""; // Detail filter
	var $MasterRecordExists;	
	var $MultiSelectKey;
	var $Command;
	var $RestoreSearch = FALSE;
	var $DetailPages;
	var $Recordset;
	var $OldRecordset;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError, $gsSearchError, $Security;

		// Search filters
		$sSrchAdvanced = ""; // Advanced search filter
		$sSrchBasic = ""; // Basic search filter
		$sFilter = "";

		// Get command
		$this->Command = strtolower(@$_GET["cmd"]);
		if ($this->IsPageRequest()) { // Validate request

			// Process list action first
			if ($this->ProcessListAction()) // Ajax request
				$this->Page_Terminate();

			// Handle reset command
			$this->ResetCmd();

			// Set up master detail parameters
			$this->SetUpMasterParms();

			// Set up Breadcrumb
			if ($this->Export == "")
				$this->SetupBreadcrumb();

			// Check QueryString parameters
			if (@$_GET["a"] <> "") {
				$this->CurrentAction = $_GET["a"];

				// Clear inline mode
				if ($this->CurrentAction == "cancel")
					$this->ClearInlineMode();

				// Switch to grid edit mode
				if ($this->CurrentAction == "gridedit")
					$this->GridEditMode();

				// Switch to grid add mode
				if ($this->CurrentAction == "gridadd")
					$this->GridAddMode();
			} else {
				if (@$_POST["a_list"] <> "") {
					$this->CurrentAction = $_POST["a_list"]; // Get action

					// Grid Update
					if (($this->CurrentAction == "gridupdate" || $this->CurrentAction == "gridoverwrite") && @$_SESSION[EW_SESSION_INLINE_MODE] == "gridedit") {
						if ($this->ValidateGridForm()) {
							$bGridUpdate = $this->GridUpdate();
						} else {
							$bGridUpdate = FALSE;
							$this->setFailureMessage($gsFormError);
						}
						if (!$bGridUpdate) {
							$this->EventCancelled = TRUE;
							$this->CurrentAction = "gridedit"; // Stay in Grid Edit mode
						}
					}

					// Grid Insert
					if ($this->CurrentAction == "gridinsert" && @$_SESSION[EW_SESSION_INLINE_MODE] == "gridadd") {
						if ($this->ValidateGridForm()) {
							$bGridInsert = $this->GridInsert();
						} else {
							$bGridInsert = FALSE;
							$this->setFailureMessage($gsFormError);
						}
						if (!$bGridInsert) {
							$this->EventCancelled = TRUE;
							$this->CurrentAction = "gridadd"; // Stay in Grid Add mode
						}
					}
				}
			}

			// Hide list options
			if ($this->Export <> "") {
				$this->ListOptions->HideAllOptions(array("sequence"));
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			} elseif ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
				$this->ListOptions->HideAllOptions();
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			}

			// Hide options
			if ($this->Export <> "" || $this->CurrentAction <> "") {
				$this->ExportOptions->HideAllOptions();
				$this->FilterOptions->HideAllOptions();
			}

			// Hide other options
			if ($this->Export <> "") {
				foreach ($this->OtherOptions as &$option)
					$option->HideAllOptions();
			}

			// Show grid delete link for grid add / grid edit
			if ($this->AllowAddDeleteRow) {
				if ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
					$item = $this->ListOptions->GetItem("griddelete");
					if ($item) $item->Visible = TRUE;
				}
			}

			// Get default search criteria
			ew_AddFilter($this->DefaultSearchWhere, $this->BasicSearchWhere(TRUE));

			// Get basic search values
			$this->LoadBasicSearchValues();

			// Restore filter list
			$this->RestoreFilterList();

			// Restore search parms from Session if not searching / reset / export
			if (($this->Export <> "" || $this->Command <> "search" && $this->Command <> "reset" && $this->Command <> "resetall") && $this->CheckSearchParms())
				$this->RestoreSearchParms();

			// Call Recordset SearchValidated event
			$this->Recordset_SearchValidated();

			// Set up sorting order
			$this->SetUpSortOrder();

			// Get basic search criteria
			if ($gsSearchError == "")
				$sSrchBasic = $this->BasicSearchWhere();
		}

		// Restore display records
		if ($this->getRecordsPerPage() <> "") {
			$this->DisplayRecs = $this->getRecordsPerPage(); // Restore from Session
		} else {
			$this->DisplayRecs = 20; // Load default
		}

		// Load Sorting Order
		$this->LoadSortOrder();

		// Load search default if no existing search criteria
		if (!$this->CheckSearchParms()) {

			// Load basic search from default
			$this->BasicSearch->LoadDefault();
			if ($this->BasicSearch->Keyword != "")
				$sSrchBasic = $this->BasicSearchWhere();
		}

		// Build search criteria
		ew_AddFilter($this->SearchWhere, $sSrchAdvanced);
		ew_AddFilter($this->SearchWhere, $sSrchBasic);

		// Call Recordset_Searching event
		$this->Recordset_Searching($this->SearchWhere);

		// Save search criteria
		if ($this->Command == "search" && !$this->RestoreSearch) {
			$this->setSearchWhere($this->SearchWhere); // Save to Session
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} else {
			$this->SearchWhere = $this->getSearchWhere();
		}

		// Build filter
		$sFilter = "";

		// Restore master/detail filter
		$this->DbMasterFilter = $this->GetMasterFilter(); // Restore master filter
		$this->DbDetailFilter = $this->GetDetailFilter(); // Restore detail filter
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);

		// Load master record
		if ($this->CurrentMode <> "add" && $this->GetMasterFilter() <> "" && $this->getCurrentMasterTable() == "sub_category") {
			global $sub_category;
			$rsmaster = $sub_category->LoadRs($this->DbMasterFilter);
			$this->MasterRecordExists = ($rsmaster && !$rsmaster->EOF);
			if (!$this->MasterRecordExists) {
				$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record found
				$this->Page_Terminate("sub_categorylist.php"); // Return to master page
			} else {
				$sub_category->LoadListRowValues($rsmaster);
				$sub_category->RowType = EW_ROWTYPE_MASTER; // Master row
				$sub_category->RenderListRow();
				$rsmaster->Close();
			}
		}

		// Set up filter in session
		$this->setSessionWhere($sFilter);
		$this->CurrentFilter = "";

		// Load record count first
		if (!$this->IsAddOrEdit()) {
			$bSelectLimit = $this->UseSelectLimit;
			if ($bSelectLimit) {
				$this->TotalRecs = $this->SelectRecordCount();
			} else {
				if ($this->Recordset = $this->LoadRecordset())
					$this->TotalRecs = $this->Recordset->RecordCount();
			}
		}

		// Search options
		$this->SetupSearchOptions();
	}

	//  Exit inline mode
	function ClearInlineMode() {
		$this->LastAction = $this->CurrentAction; // Save last action
		$this->CurrentAction = ""; // Clear action
		$_SESSION[EW_SESSION_INLINE_MODE] = ""; // Clear inline mode
	}

	// Switch to Grid Add mode
	function GridAddMode() {
		$_SESSION[EW_SESSION_INLINE_MODE] = "gridadd"; // Enabled grid add
	}

	// Switch to Grid Edit mode
	function GridEditMode() {
		$_SESSION[EW_SESSION_INLINE_MODE] = "gridedit"; // Enable grid edit
	}

	// Perform update to grid
	function GridUpdate() {
		global $Language, $objForm, $gsFormError;
		$bGridUpdate = TRUE;

		// Get old recordset
		$this->CurrentFilter = $this->BuildKeyFilter();
		if ($this->CurrentFilter == "")
			$this->CurrentFilter = "0=1";
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		if ($rs = $conn->Execute($sSql)) {
			$rsold = $rs->GetRows();
			$rs->Close();
		}

		// Call Grid Updating event
		if (!$this->Grid_Updating($rsold)) {
			if ($this->getFailureMessage() == "")
				$this->setFailureMessage($Language->Phrase("GridEditCancelled")); // Set grid edit cancelled message
			return FALSE;
		}

		// Begin transaction
		$conn->BeginTrans();
		$sKey = "";

		// Update row index and get row key
		$objForm->Index = -1;
		$rowcnt = strval($objForm->GetValue($this->FormKeyCountName));
		if ($rowcnt == "" || !is_numeric($rowcnt))
			$rowcnt = 0;

		// Update all rows based on key
		for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {
			$objForm->Index = $rowindex;
			$rowkey = strval($objForm->GetValue($this->FormKeyName));
			$rowaction = strval($objForm->GetValue($this->FormActionName));

			// Load all values and keys
			if ($rowaction <> "insertdelete") { // Skip insert then deleted rows
				$this->LoadFormValues(); // Get form values
				if ($rowaction == "" || $rowaction == "edit" || $rowaction == "delete") {
					$bGridUpdate = $this->SetupKeyValues($rowkey); // Set up key values
				} else {
					$bGridUpdate = TRUE;
				}

				// Skip empty row
				if ($rowaction == "insert" && $this->EmptyRow()) {

					// No action required
				// Validate form and insert/update/delete record

				} elseif ($bGridUpdate) {
					if ($rowaction == "delete") {
						$this->CurrentFilter = $this->KeyFilter();
						$bGridUpdate = $this->DeleteRows(); // Delete this row
					} else if (!$this->ValidateForm()) {
						$bGridUpdate = FALSE; // Form error, reset action
						$this->setFailureMessage($gsFormError);
					} else {
						if ($rowaction == "insert") {
							$bGridUpdate = $this->AddRow(); // Insert this row
						} else {
							if ($rowkey <> "") {
								$this->SendEmail = FALSE; // Do not send email on update success
								$bGridUpdate = $this->EditRow(); // Update this row
							}
						} // End update
					}
				}
				if ($bGridUpdate) {
					if ($sKey <> "") $sKey .= ", ";
					$sKey .= $rowkey;
				} else {
					break;
				}
			}
		}
		if ($bGridUpdate) {
			$conn->CommitTrans(); // Commit transaction

			// Get new recordset
			if ($rs = $conn->Execute($sSql)) {
				$rsnew = $rs->GetRows();
				$rs->Close();
			}

			// Call Grid_Updated event
			$this->Grid_Updated($rsold, $rsnew);
			if ($this->getSuccessMessage() == "")
				$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Set up update success message
			$this->ClearInlineMode(); // Clear inline edit mode
		} else {
			$conn->RollbackTrans(); // Rollback transaction
			if ($this->getFailureMessage() == "")
				$this->setFailureMessage($Language->Phrase("UpdateFailed")); // Set update failed message
		}
		return $bGridUpdate;
	}

	// Build filter for all keys
	function BuildKeyFilter() {
		global $objForm;
		$sWrkFilter = "";

		// Update row index and get row key
		$rowindex = 1;
		$objForm->Index = $rowindex;
		$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		while ($sThisKey <> "") {
			if ($this->SetupKeyValues($sThisKey)) {
				$sFilter = $this->KeyFilter();
				if ($sWrkFilter <> "") $sWrkFilter .= " OR ";
				$sWrkFilter .= $sFilter;
			} else {
				$sWrkFilter = "0=1";
				break;
			}

			// Update row index and get row key
			$rowindex++; // Next row
			$objForm->Index = $rowindex;
			$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		}
		return $sWrkFilter;
	}

	// Set up key values
	function SetupKeyValues($key) {
		$arrKeyFlds = explode($GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"], $key);
		if (count($arrKeyFlds) >= 1) {
			$this->product_id->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->product_id->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Perform Grid Add
	function GridInsert() {
		global $Language, $objForm, $gsFormError;
		$rowindex = 1;
		$bGridInsert = FALSE;
		$conn = &$this->Connection();

		// Call Grid Inserting event
		if (!$this->Grid_Inserting()) {
			if ($this->getFailureMessage() == "") {
				$this->setFailureMessage($Language->Phrase("GridAddCancelled")); // Set grid add cancelled message
			}
			return FALSE;
		}

		// Begin transaction
		$conn->BeginTrans();

		// Init key filter
		$sWrkFilter = "";
		$addcnt = 0;
		$sKey = "";

		// Get row count
		$objForm->Index = -1;
		$rowcnt = strval($objForm->GetValue($this->FormKeyCountName));
		if ($rowcnt == "" || !is_numeric($rowcnt))
			$rowcnt = 0;

		// Insert all rows
		for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {

			// Load current row values
			$objForm->Index = $rowindex;
			$rowaction = strval($objForm->GetValue($this->FormActionName));
			if ($rowaction <> "" && $rowaction <> "insert")
				continue; // Skip
			$this->LoadFormValues(); // Get form values
			if (!$this->EmptyRow()) {
				$addcnt++;
				$this->SendEmail = FALSE; // Do not send email on insert success

				// Validate form
				if (!$this->ValidateForm()) {
					$bGridInsert = FALSE; // Form error, reset action
					$this->setFailureMessage($gsFormError);
				} else {
					$bGridInsert = $this->AddRow($this->OldRecordset); // Insert this row
				}
				if ($bGridInsert) {
					if ($sKey <> "") $sKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
					$sKey .= $this->product_id->CurrentValue;

					// Add filter for this record
					$sFilter = $this->KeyFilter();
					if ($sWrkFilter <> "") $sWrkFilter .= " OR ";
					$sWrkFilter .= $sFilter;
				} else {
					break;
				}
			}
		}
		if ($addcnt == 0) { // No record inserted
			$this->setFailureMessage($Language->Phrase("NoAddRecord"));
			$bGridInsert = FALSE;
		}
		if ($bGridInsert) {
			$conn->CommitTrans(); // Commit transaction

			// Get new recordset
			$this->CurrentFilter = $sWrkFilter;
			$sSql = $this->SQL();
			if ($rs = $conn->Execute($sSql)) {
				$rsnew = $rs->GetRows();
				$rs->Close();
			}

			// Call Grid_Inserted event
			$this->Grid_Inserted($rsnew);
			if ($this->getSuccessMessage() == "")
				$this->setSuccessMessage($Language->Phrase("InsertSuccess")); // Set up insert success message
			$this->ClearInlineMode(); // Clear grid add mode
		} else {
			$conn->RollbackTrans(); // Rollback transaction
			if ($this->getFailureMessage() == "") {
				$this->setFailureMessage($Language->Phrase("InsertFailed")); // Set insert failed message
			}
		}
		return $bGridInsert;
	}

	// Check if empty row
	function EmptyRow() {
		global $objForm;
		if ($objForm->HasValue("x_category_id") && $objForm->HasValue("o_category_id") && $this->category_id->CurrentValue <> $this->category_id->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_scat_id") && $objForm->HasValue("o_scat_id") && $this->scat_id->CurrentValue <> $this->scat_id->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_product_name") && $objForm->HasValue("o_product_name") && $this->product_name->CurrentValue <> $this->product_name->OldValue)
			return FALSE;
		if (!ew_Empty($this->product_image->Upload->Value))
			return FALSE;
		if (!ew_Empty($this->product_secimage->Upload->Value))
			return FALSE;
		return TRUE;
	}

	// Validate grid form
	function ValidateGridForm() {
		global $objForm;

		// Get row count
		$objForm->Index = -1;
		$rowcnt = strval($objForm->GetValue($this->FormKeyCountName));
		if ($rowcnt == "" || !is_numeric($rowcnt))
			$rowcnt = 0;

		// Validate all records
		for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {

			// Load current row values
			$objForm->Index = $rowindex;
			$rowaction = strval($objForm->GetValue($this->FormActionName));
			if ($rowaction <> "delete" && $rowaction <> "insertdelete") {
				$this->LoadFormValues(); // Get form values
				if ($rowaction == "insert" && $this->EmptyRow()) {

					// Ignore
				} else if (!$this->ValidateForm()) {
					return FALSE;
				}
			}
		}
		return TRUE;
	}

	// Get all form values of the grid
	function GetGridFormValues() {
		global $objForm;

		// Get row count
		$objForm->Index = -1;
		$rowcnt = strval($objForm->GetValue($this->FormKeyCountName));
		if ($rowcnt == "" || !is_numeric($rowcnt))
			$rowcnt = 0;
		$rows = array();

		// Loop through all records
		for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {

			// Load current row values
			$objForm->Index = $rowindex;
			$rowaction = strval($objForm->GetValue($this->FormActionName));
			if ($rowaction <> "delete" && $rowaction <> "insertdelete") {
				$this->LoadFormValues(); // Get form values
				if ($rowaction == "insert" && $this->EmptyRow()) {

					// Ignore
				} else {
					$rows[] = $this->GetFieldValues("FormValue"); // Return row as array
				}
			}
		}
		return $rows; // Return as array of array
	}

	// Restore form values for current row
	function RestoreCurrentRowFormValues($idx) {
		global $objForm;

		// Get row based on current index
		$objForm->Index = $idx;
		$this->LoadFormValues(); // Load form values
	}

	// Get list of filters
	function GetFilterList() {

		// Initialize
		$sFilterList = "";
		$sFilterList = ew_Concat($sFilterList, $this->product_id->AdvancedSearch->ToJSON(), ","); // Field product_id
		$sFilterList = ew_Concat($sFilterList, $this->category_id->AdvancedSearch->ToJSON(), ","); // Field category_id
		$sFilterList = ew_Concat($sFilterList, $this->scat_id->AdvancedSearch->ToJSON(), ","); // Field scat_id
		$sFilterList = ew_Concat($sFilterList, $this->product_name->AdvancedSearch->ToJSON(), ","); // Field product_name
		$sFilterList = ew_Concat($sFilterList, $this->product_image->AdvancedSearch->ToJSON(), ","); // Field product_image
		$sFilterList = ew_Concat($sFilterList, $this->product_secimage->AdvancedSearch->ToJSON(), ","); // Field product_secimage
		$sFilterList = ew_Concat($sFilterList, $this->product_description->AdvancedSearch->ToJSON(), ","); // Field product_description
		$sFilterList = ew_Concat($sFilterList, $this->feature_ledtype->AdvancedSearch->ToJSON(), ","); // Field feature_ledtype
		$sFilterList = ew_Concat($sFilterList, $this->feature_power->AdvancedSearch->ToJSON(), ","); // Field feature_power
		$sFilterList = ew_Concat($sFilterList, $this->feature_lumen->AdvancedSearch->ToJSON(), ","); // Field feature_lumen
		$sFilterList = ew_Concat($sFilterList, $this->feature_viewangle->AdvancedSearch->ToJSON(), ","); // Field feature_viewangle
		$sFilterList = ew_Concat($sFilterList, $this->feature_cri->AdvancedSearch->ToJSON(), ","); // Field feature_cri
		$sFilterList = ew_Concat($sFilterList, $this->feature_iprating->AdvancedSearch->ToJSON(), ","); // Field feature_iprating
		$sFilterList = ew_Concat($sFilterList, $this->feature_colortemp->AdvancedSearch->ToJSON(), ","); // Field feature_colortemp
		$sFilterList = ew_Concat($sFilterList, $this->feature_body->AdvancedSearch->ToJSON(), ","); // Field feature_body
		$sFilterList = ew_Concat($sFilterList, $this->feature_cutoutsize->AdvancedSearch->ToJSON(), ","); // Field feature_cutoutsize
		$sFilterList = ew_Concat($sFilterList, $this->feature_colors->AdvancedSearch->ToJSON(), ","); // Field feature_colors
		$sFilterList = ew_Concat($sFilterList, $this->feature_dimmable->AdvancedSearch->ToJSON(), ","); // Field feature_dimmable
		$sFilterList = ew_Concat($sFilterList, $this->feature_warranty->AdvancedSearch->ToJSON(), ","); // Field feature_warranty
		$sFilterList = ew_Concat($sFilterList, $this->feature_application->AdvancedSearch->ToJSON(), ","); // Field feature_application
		if ($this->BasicSearch->Keyword <> "") {
			$sWrk = "\"psearch\":\"" . ew_JsEncode2($this->BasicSearch->Keyword) . "\",\"psearchtype\":\"" . ew_JsEncode2($this->BasicSearch->Type) . "\"";
			$sFilterList = ew_Concat($sFilterList, $sWrk, ",");
		}

		// Return filter list in json
		return ($sFilterList <> "") ? "{" . $sFilterList . "}" : "null";
	}

	// Restore list of filters
	function RestoreFilterList() {

		// Return if not reset filter
		if (@$_POST["cmd"] <> "resetfilter")
			return FALSE;
		$filter = json_decode(ew_StripSlashes(@$_POST["filter"]), TRUE);
		$this->Command = "search";

		// Field product_id
		$this->product_id->AdvancedSearch->SearchValue = @$filter["x_product_id"];
		$this->product_id->AdvancedSearch->SearchOperator = @$filter["z_product_id"];
		$this->product_id->AdvancedSearch->SearchCondition = @$filter["v_product_id"];
		$this->product_id->AdvancedSearch->SearchValue2 = @$filter["y_product_id"];
		$this->product_id->AdvancedSearch->SearchOperator2 = @$filter["w_product_id"];
		$this->product_id->AdvancedSearch->Save();

		// Field category_id
		$this->category_id->AdvancedSearch->SearchValue = @$filter["x_category_id"];
		$this->category_id->AdvancedSearch->SearchOperator = @$filter["z_category_id"];
		$this->category_id->AdvancedSearch->SearchCondition = @$filter["v_category_id"];
		$this->category_id->AdvancedSearch->SearchValue2 = @$filter["y_category_id"];
		$this->category_id->AdvancedSearch->SearchOperator2 = @$filter["w_category_id"];
		$this->category_id->AdvancedSearch->Save();

		// Field scat_id
		$this->scat_id->AdvancedSearch->SearchValue = @$filter["x_scat_id"];
		$this->scat_id->AdvancedSearch->SearchOperator = @$filter["z_scat_id"];
		$this->scat_id->AdvancedSearch->SearchCondition = @$filter["v_scat_id"];
		$this->scat_id->AdvancedSearch->SearchValue2 = @$filter["y_scat_id"];
		$this->scat_id->AdvancedSearch->SearchOperator2 = @$filter["w_scat_id"];
		$this->scat_id->AdvancedSearch->Save();

		// Field product_name
		$this->product_name->AdvancedSearch->SearchValue = @$filter["x_product_name"];
		$this->product_name->AdvancedSearch->SearchOperator = @$filter["z_product_name"];
		$this->product_name->AdvancedSearch->SearchCondition = @$filter["v_product_name"];
		$this->product_name->AdvancedSearch->SearchValue2 = @$filter["y_product_name"];
		$this->product_name->AdvancedSearch->SearchOperator2 = @$filter["w_product_name"];
		$this->product_name->AdvancedSearch->Save();

		// Field product_image
		$this->product_image->AdvancedSearch->SearchValue = @$filter["x_product_image"];
		$this->product_image->AdvancedSearch->SearchOperator = @$filter["z_product_image"];
		$this->product_image->AdvancedSearch->SearchCondition = @$filter["v_product_image"];
		$this->product_image->AdvancedSearch->SearchValue2 = @$filter["y_product_image"];
		$this->product_image->AdvancedSearch->SearchOperator2 = @$filter["w_product_image"];
		$this->product_image->AdvancedSearch->Save();

		// Field product_secimage
		$this->product_secimage->AdvancedSearch->SearchValue = @$filter["x_product_secimage"];
		$this->product_secimage->AdvancedSearch->SearchOperator = @$filter["z_product_secimage"];
		$this->product_secimage->AdvancedSearch->SearchCondition = @$filter["v_product_secimage"];
		$this->product_secimage->AdvancedSearch->SearchValue2 = @$filter["y_product_secimage"];
		$this->product_secimage->AdvancedSearch->SearchOperator2 = @$filter["w_product_secimage"];
		$this->product_secimage->AdvancedSearch->Save();

		// Field product_description
		$this->product_description->AdvancedSearch->SearchValue = @$filter["x_product_description"];
		$this->product_description->AdvancedSearch->SearchOperator = @$filter["z_product_description"];
		$this->product_description->AdvancedSearch->SearchCondition = @$filter["v_product_description"];
		$this->product_description->AdvancedSearch->SearchValue2 = @$filter["y_product_description"];
		$this->product_description->AdvancedSearch->SearchOperator2 = @$filter["w_product_description"];
		$this->product_description->AdvancedSearch->Save();

		// Field feature_ledtype
		$this->feature_ledtype->AdvancedSearch->SearchValue = @$filter["x_feature_ledtype"];
		$this->feature_ledtype->AdvancedSearch->SearchOperator = @$filter["z_feature_ledtype"];
		$this->feature_ledtype->AdvancedSearch->SearchCondition = @$filter["v_feature_ledtype"];
		$this->feature_ledtype->AdvancedSearch->SearchValue2 = @$filter["y_feature_ledtype"];
		$this->feature_ledtype->AdvancedSearch->SearchOperator2 = @$filter["w_feature_ledtype"];
		$this->feature_ledtype->AdvancedSearch->Save();

		// Field feature_power
		$this->feature_power->AdvancedSearch->SearchValue = @$filter["x_feature_power"];
		$this->feature_power->AdvancedSearch->SearchOperator = @$filter["z_feature_power"];
		$this->feature_power->AdvancedSearch->SearchCondition = @$filter["v_feature_power"];
		$this->feature_power->AdvancedSearch->SearchValue2 = @$filter["y_feature_power"];
		$this->feature_power->AdvancedSearch->SearchOperator2 = @$filter["w_feature_power"];
		$this->feature_power->AdvancedSearch->Save();

		// Field feature_lumen
		$this->feature_lumen->AdvancedSearch->SearchValue = @$filter["x_feature_lumen"];
		$this->feature_lumen->AdvancedSearch->SearchOperator = @$filter["z_feature_lumen"];
		$this->feature_lumen->AdvancedSearch->SearchCondition = @$filter["v_feature_lumen"];
		$this->feature_lumen->AdvancedSearch->SearchValue2 = @$filter["y_feature_lumen"];
		$this->feature_lumen->AdvancedSearch->SearchOperator2 = @$filter["w_feature_lumen"];
		$this->feature_lumen->AdvancedSearch->Save();

		// Field feature_viewangle
		$this->feature_viewangle->AdvancedSearch->SearchValue = @$filter["x_feature_viewangle"];
		$this->feature_viewangle->AdvancedSearch->SearchOperator = @$filter["z_feature_viewangle"];
		$this->feature_viewangle->AdvancedSearch->SearchCondition = @$filter["v_feature_viewangle"];
		$this->feature_viewangle->AdvancedSearch->SearchValue2 = @$filter["y_feature_viewangle"];
		$this->feature_viewangle->AdvancedSearch->SearchOperator2 = @$filter["w_feature_viewangle"];
		$this->feature_viewangle->AdvancedSearch->Save();

		// Field feature_cri
		$this->feature_cri->AdvancedSearch->SearchValue = @$filter["x_feature_cri"];
		$this->feature_cri->AdvancedSearch->SearchOperator = @$filter["z_feature_cri"];
		$this->feature_cri->AdvancedSearch->SearchCondition = @$filter["v_feature_cri"];
		$this->feature_cri->AdvancedSearch->SearchValue2 = @$filter["y_feature_cri"];
		$this->feature_cri->AdvancedSearch->SearchOperator2 = @$filter["w_feature_cri"];
		$this->feature_cri->AdvancedSearch->Save();

		// Field feature_iprating
		$this->feature_iprating->AdvancedSearch->SearchValue = @$filter["x_feature_iprating"];
		$this->feature_iprating->AdvancedSearch->SearchOperator = @$filter["z_feature_iprating"];
		$this->feature_iprating->AdvancedSearch->SearchCondition = @$filter["v_feature_iprating"];
		$this->feature_iprating->AdvancedSearch->SearchValue2 = @$filter["y_feature_iprating"];
		$this->feature_iprating->AdvancedSearch->SearchOperator2 = @$filter["w_feature_iprating"];
		$this->feature_iprating->AdvancedSearch->Save();

		// Field feature_colortemp
		$this->feature_colortemp->AdvancedSearch->SearchValue = @$filter["x_feature_colortemp"];
		$this->feature_colortemp->AdvancedSearch->SearchOperator = @$filter["z_feature_colortemp"];
		$this->feature_colortemp->AdvancedSearch->SearchCondition = @$filter["v_feature_colortemp"];
		$this->feature_colortemp->AdvancedSearch->SearchValue2 = @$filter["y_feature_colortemp"];
		$this->feature_colortemp->AdvancedSearch->SearchOperator2 = @$filter["w_feature_colortemp"];
		$this->feature_colortemp->AdvancedSearch->Save();

		// Field feature_body
		$this->feature_body->AdvancedSearch->SearchValue = @$filter["x_feature_body"];
		$this->feature_body->AdvancedSearch->SearchOperator = @$filter["z_feature_body"];
		$this->feature_body->AdvancedSearch->SearchCondition = @$filter["v_feature_body"];
		$this->feature_body->AdvancedSearch->SearchValue2 = @$filter["y_feature_body"];
		$this->feature_body->AdvancedSearch->SearchOperator2 = @$filter["w_feature_body"];
		$this->feature_body->AdvancedSearch->Save();

		// Field feature_cutoutsize
		$this->feature_cutoutsize->AdvancedSearch->SearchValue = @$filter["x_feature_cutoutsize"];
		$this->feature_cutoutsize->AdvancedSearch->SearchOperator = @$filter["z_feature_cutoutsize"];
		$this->feature_cutoutsize->AdvancedSearch->SearchCondition = @$filter["v_feature_cutoutsize"];
		$this->feature_cutoutsize->AdvancedSearch->SearchValue2 = @$filter["y_feature_cutoutsize"];
		$this->feature_cutoutsize->AdvancedSearch->SearchOperator2 = @$filter["w_feature_cutoutsize"];
		$this->feature_cutoutsize->AdvancedSearch->Save();

		// Field feature_colors
		$this->feature_colors->AdvancedSearch->SearchValue = @$filter["x_feature_colors"];
		$this->feature_colors->AdvancedSearch->SearchOperator = @$filter["z_feature_colors"];
		$this->feature_colors->AdvancedSearch->SearchCondition = @$filter["v_feature_colors"];
		$this->feature_colors->AdvancedSearch->SearchValue2 = @$filter["y_feature_colors"];
		$this->feature_colors->AdvancedSearch->SearchOperator2 = @$filter["w_feature_colors"];
		$this->feature_colors->AdvancedSearch->Save();

		// Field feature_dimmable
		$this->feature_dimmable->AdvancedSearch->SearchValue = @$filter["x_feature_dimmable"];
		$this->feature_dimmable->AdvancedSearch->SearchOperator = @$filter["z_feature_dimmable"];
		$this->feature_dimmable->AdvancedSearch->SearchCondition = @$filter["v_feature_dimmable"];
		$this->feature_dimmable->AdvancedSearch->SearchValue2 = @$filter["y_feature_dimmable"];
		$this->feature_dimmable->AdvancedSearch->SearchOperator2 = @$filter["w_feature_dimmable"];
		$this->feature_dimmable->AdvancedSearch->Save();

		// Field feature_warranty
		$this->feature_warranty->AdvancedSearch->SearchValue = @$filter["x_feature_warranty"];
		$this->feature_warranty->AdvancedSearch->SearchOperator = @$filter["z_feature_warranty"];
		$this->feature_warranty->AdvancedSearch->SearchCondition = @$filter["v_feature_warranty"];
		$this->feature_warranty->AdvancedSearch->SearchValue2 = @$filter["y_feature_warranty"];
		$this->feature_warranty->AdvancedSearch->SearchOperator2 = @$filter["w_feature_warranty"];
		$this->feature_warranty->AdvancedSearch->Save();

		// Field feature_application
		$this->feature_application->AdvancedSearch->SearchValue = @$filter["x_feature_application"];
		$this->feature_application->AdvancedSearch->SearchOperator = @$filter["z_feature_application"];
		$this->feature_application->AdvancedSearch->SearchCondition = @$filter["v_feature_application"];
		$this->feature_application->AdvancedSearch->SearchValue2 = @$filter["y_feature_application"];
		$this->feature_application->AdvancedSearch->SearchOperator2 = @$filter["w_feature_application"];
		$this->feature_application->AdvancedSearch->Save();
		$this->BasicSearch->setKeyword(@$filter["psearch"]);
		$this->BasicSearch->setType(@$filter["psearchtype"]);
	}

	// Return basic search SQL
	function BasicSearchSQL($arKeywords, $type) {
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->product_name, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->product_image, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->product_secimage, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->product_description, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->feature_ledtype, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->feature_power, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->feature_lumen, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->feature_viewangle, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->feature_cri, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->feature_iprating, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->feature_colortemp, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->feature_body, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->feature_cutoutsize, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->feature_colors, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->feature_dimmable, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->feature_warranty, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->feature_application, $arKeywords, $type);
		return $sWhere;
	}

	// Build basic search SQL
	function BuildBasicSearchSql(&$Where, &$Fld, $arKeywords, $type) {
		$sDefCond = ($type == "OR") ? "OR" : "AND";
		$sCond = $sDefCond;
		$arSQL = array(); // Array for SQL parts
		$arCond = array(); // Array for search conditions
		$cnt = count($arKeywords);
		$j = 0; // Number of SQL parts
		for ($i = 0; $i < $cnt; $i++) {
			$Keyword = $arKeywords[$i];
			$Keyword = trim($Keyword);
			if (EW_BASIC_SEARCH_IGNORE_PATTERN <> "") {
				$Keyword = preg_replace(EW_BASIC_SEARCH_IGNORE_PATTERN, "\\", $Keyword);
				$ar = explode("\\", $Keyword);
			} else {
				$ar = array($Keyword);
			}
			foreach ($ar as $Keyword) {
				if ($Keyword <> "") {
					$sWrk = "";
					if ($Keyword == "OR" && $type == "") {
						if ($j > 0)
							$arCond[$j-1] = "OR";
					} elseif ($Keyword == EW_NULL_VALUE) {
						$sWrk = $Fld->FldExpression . " IS NULL";
					} elseif ($Keyword == EW_NOT_NULL_VALUE) {
						$sWrk = $Fld->FldExpression . " IS NOT NULL";
					} elseif ($Fld->FldIsVirtual && $Fld->FldVirtualSearch) {
						$sWrk = $Fld->FldVirtualExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING, $this->DBID), $this->DBID);
					} elseif ($Fld->FldDataType != EW_DATATYPE_NUMBER || is_numeric($Keyword)) {
						$sWrk = $Fld->FldBasicSearchExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING, $this->DBID), $this->DBID);
					}
					if ($sWrk <> "") {
						$arSQL[$j] = $sWrk;
						$arCond[$j] = $sDefCond;
						$j += 1;
					}
				}
			}
		}
		$cnt = count($arSQL);
		$bQuoted = FALSE;
		$sSql = "";
		if ($cnt > 0) {
			for ($i = 0; $i < $cnt-1; $i++) {
				if ($arCond[$i] == "OR") {
					if (!$bQuoted) $sSql .= "(";
					$bQuoted = TRUE;
				}
				$sSql .= $arSQL[$i];
				if ($bQuoted && $arCond[$i] <> "OR") {
					$sSql .= ")";
					$bQuoted = FALSE;
				}
				$sSql .= " " . $arCond[$i] . " ";
			}
			$sSql .= $arSQL[$cnt-1];
			if ($bQuoted)
				$sSql .= ")";
		}
		if ($sSql <> "") {
			if ($Where <> "") $Where .= " OR ";
			$Where .=  "(" . $sSql . ")";
		}
	}

	// Return basic search WHERE clause based on search keyword and type
	function BasicSearchWhere($Default = FALSE) {
		global $Security;
		$sSearchStr = "";
		$sSearchKeyword = ($Default) ? $this->BasicSearch->KeywordDefault : $this->BasicSearch->Keyword;
		$sSearchType = ($Default) ? $this->BasicSearch->TypeDefault : $this->BasicSearch->Type;
		if ($sSearchKeyword <> "") {
			$sSearch = trim($sSearchKeyword);
			if ($sSearchType <> "=") {
				$ar = array();

				// Match quoted keywords (i.e.: "...")
				if (preg_match_all('/"([^"]*)"/i', $sSearch, $matches, PREG_SET_ORDER)) {
					foreach ($matches as $match) {
						$p = strpos($sSearch, $match[0]);
						$str = substr($sSearch, 0, $p);
						$sSearch = substr($sSearch, $p + strlen($match[0]));
						if (strlen(trim($str)) > 0)
							$ar = array_merge($ar, explode(" ", trim($str)));
						$ar[] = $match[1]; // Save quoted keyword
					}
				}

				// Match individual keywords
				if (strlen(trim($sSearch)) > 0)
					$ar = array_merge($ar, explode(" ", trim($sSearch)));
				$sSearchStr = $this->BasicSearchSQL($ar, $sSearchType);
			} else {
				$sSearchStr = $this->BasicSearchSQL(array($sSearch), $sSearchType);
			}
			if (!$Default) $this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->BasicSearch->setKeyword($sSearchKeyword);
			$this->BasicSearch->setType($sSearchType);
		}
		return $sSearchStr;
	}

	// Check if search parm exists
	function CheckSearchParms() {

		// Check basic search
		if ($this->BasicSearch->IssetSession())
			return TRUE;
		return FALSE;
	}

	// Clear all search parameters
	function ResetSearchParms() {

		// Clear search WHERE clause
		$this->SearchWhere = "";
		$this->setSearchWhere($this->SearchWhere);

		// Clear basic search parameters
		$this->ResetBasicSearchParms();
	}

	// Load advanced search default values
	function LoadAdvancedSearchDefault() {
		return FALSE;
	}

	// Clear all basic search parameters
	function ResetBasicSearchParms() {
		$this->BasicSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->product_id); // product_id
			$this->UpdateSort($this->category_id); // category_id
			$this->UpdateSort($this->scat_id); // scat_id
			$this->UpdateSort($this->product_name); // product_name
			$this->UpdateSort($this->product_image); // product_image
			$this->UpdateSort($this->product_secimage); // product_secimage
			$this->setStartRecordNumber(1); // Reset start position
		}
	}

	// Load sort order parameters
	function LoadSortOrder() {
		$sOrderBy = $this->getSessionOrderBy(); // Get ORDER BY from Session
		if ($sOrderBy == "") {
			if ($this->getSqlOrderBy() <> "") {
				$sOrderBy = $this->getSqlOrderBy();
				$this->setSessionOrderBy($sOrderBy);
			}
		}
	}

	// Reset command
	// - cmd=reset (Reset search parameters)
	// - cmd=resetall (Reset search and master/detail parameters)
	// - cmd=resetsort (Reset sort parameters)
	function ResetCmd() {

		// Check if reset command
		if (substr($this->Command,0,5) == "reset") {

			// Reset search criteria
			if ($this->Command == "reset" || $this->Command == "resetall")
				$this->ResetSearchParms();

			// Reset master/detail keys
			if ($this->Command == "resetall") {
				$this->setCurrentMasterTable(""); // Clear master table
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
				$this->scat_id->setSessionValue("");
			}

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->product_id->setSort("");
				$this->category_id->setSort("");
				$this->scat_id->setSort("");
				$this->product_name->setSort("");
				$this->product_image->setSort("");
				$this->product_secimage->setSort("");
			}

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Set up list options
	function SetupListOptions() {
		global $Security, $Language;

		// "griddelete"
		if ($this->AllowAddDeleteRow) {
			$item = &$this->ListOptions->Add("griddelete");
			$item->CssStyle = "white-space: nowrap;";
			$item->OnLeft = FALSE;
			$item->Visible = FALSE; // Default hidden
		}

		// Add group option item
		$item = &$this->ListOptions->Add($this->ListOptions->GroupOptionName);
		$item->Body = "";
		$item->OnLeft = FALSE;
		$item->Visible = FALSE;

		// "view"
		$item = &$this->ListOptions->Add("view");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = TRUE;
		$item->OnLeft = FALSE;

		// "edit"
		$item = &$this->ListOptions->Add("edit");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = TRUE;
		$item->OnLeft = FALSE;

		// "copy"
		$item = &$this->ListOptions->Add("copy");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = TRUE;
		$item->OnLeft = FALSE;

		// "delete"
		$item = &$this->ListOptions->Add("delete");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = TRUE;
		$item->OnLeft = FALSE;

		// List actions
		$item = &$this->ListOptions->Add("listactions");
		$item->CssStyle = "white-space: nowrap;";
		$item->OnLeft = FALSE;
		$item->Visible = FALSE;
		$item->ShowInButtonGroup = FALSE;
		$item->ShowInDropDown = FALSE;

		// "checkbox"
		$item = &$this->ListOptions->Add("checkbox");
		$item->Visible = FALSE;
		$item->OnLeft = FALSE;
		$item->Header = "<input type=\"checkbox\" name=\"key\" id=\"key\" onclick=\"ew_SelectAllKey(this);\">";
		$item->ShowInDropDown = FALSE;
		$item->ShowInButtonGroup = FALSE;

		// Drop down button for ListOptions
		$this->ListOptions->UseImageAndText = TRUE;
		$this->ListOptions->UseDropDownButton = FALSE;
		$this->ListOptions->DropDownButtonPhrase = $Language->Phrase("ButtonListOptions");
		$this->ListOptions->UseButtonGroup = FALSE;
		if ($this->ListOptions->UseButtonGroup && ew_IsMobile())
			$this->ListOptions->UseDropDownButton = TRUE;
		$this->ListOptions->ButtonClass = "btn-sm"; // Class for button group

		// Call ListOptions_Load event
		$this->ListOptions_Load();
		$this->SetupListOptionsExt();
		$item = &$this->ListOptions->GetItem($this->ListOptions->GroupOptionName);
		$item->Visible = $this->ListOptions->GroupOptionVisible();
	}

	// Render list options
	function RenderListOptions() {
		global $Security, $Language, $objForm;
		$this->ListOptions->LoadDefault();

		// Set up row action and key
		if (is_numeric($this->RowIndex) && $this->CurrentMode <> "view") {
			$objForm->Index = $this->RowIndex;
			$ActionName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormActionName);
			$OldKeyName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormOldKeyName);
			$KeyName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormKeyName);
			$BlankRowName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormBlankRowName);
			if ($this->RowAction <> "")
				$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $ActionName . "\" id=\"" . $ActionName . "\" value=\"" . $this->RowAction . "\">";
			if ($this->RowAction == "delete") {
				$rowkey = $objForm->GetValue($this->FormKeyName);
				$this->SetupKeyValues($rowkey);
			}
			if ($this->RowAction == "insert" && $this->CurrentAction == "F" && $this->EmptyRow())
				$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $BlankRowName . "\" id=\"" . $BlankRowName . "\" value=\"1\">";
		}

		// "delete"
		if ($this->AllowAddDeleteRow) {
			if ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
				$option = &$this->ListOptions;
				$option->UseButtonGroup = TRUE; // Use button group for grid delete button
				$option->UseImageAndText = TRUE; // Use image and text for grid delete button
				$oListOpt = &$option->Items["griddelete"];
				$oListOpt->Body = "<a class=\"ewGridLink ewGridDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" onclick=\"return ew_DeleteGridRow(this, " . $this->RowIndex . ");\">" . $Language->Phrase("DeleteLink") . "</a>";
			}
		}

		// "view"
		$oListOpt = &$this->ListOptions->Items["view"];
		if (TRUE)
			$oListOpt->Body = "<a class=\"ewRowLink ewView\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewLink")) . "\" href=\"" . ew_HtmlEncode($this->ViewUrl) . "\">" . $Language->Phrase("ViewLink") . "</a>";
		else
			$oListOpt->Body = "";

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		if (TRUE) {
			$oListOpt->Body = "<a class=\"ewRowLink ewEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("EditLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "copy"
		$oListOpt = &$this->ListOptions->Items["copy"];
		if (TRUE) {
			$oListOpt->Body = "<a class=\"ewRowLink ewCopy\" title=\"" . ew_HtmlTitle($Language->Phrase("CopyLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("CopyLink")) . "\" href=\"" . ew_HtmlEncode($this->CopyUrl) . "\">" . $Language->Phrase("CopyLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "delete"
		$oListOpt = &$this->ListOptions->Items["delete"];
		if (TRUE)
			$oListOpt->Body = "<a class=\"ewRowLink ewDelete\"" . "" . " title=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" href=\"" . ew_HtmlEncode($this->DeleteUrl) . "\">" . $Language->Phrase("DeleteLink") . "</a>";
		else
			$oListOpt->Body = "";

		// Set up list action buttons
		$oListOpt = &$this->ListOptions->GetItem("listactions");
		if ($oListOpt) {
			$body = "";
			$links = array();
			foreach ($this->ListActions->Items as $listaction) {
				if ($listaction->Select == EW_ACTION_SINGLE && $listaction->Allow) {
					$action = $listaction->Action;
					$caption = $listaction->Caption;
					$icon = ($listaction->Icon <> "") ? "<span class=\"" . ew_HtmlEncode($listaction->Icon) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\"></span> " : "";
					$links[] = "<li><a class=\"ewAction ewListAction\" data-action=\"" . ew_HtmlEncode($action) . "\" title=\"" . ew_HtmlTitle($caption) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({key:" . $this->KeyToJson() . "}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . $listaction->Caption . "</a></li>";
					if (count($links) == 1) // Single button
						$body = "<a class=\"ewAction ewListAction\" data-action=\"" . ew_HtmlEncode($action) . "\" title=\"" . ew_HtmlTitle($caption) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({key:" . $this->KeyToJson() . "}," . $listaction->ToJson(TRUE) . "));return false;\">" . $Language->Phrase("ListActionButton") . "</a>";
				}
			}
			if (count($links) > 1) { // More than one buttons, use dropdown
				$body = "<button class=\"dropdown-toggle btn btn-default btn-sm ewActions\" data-toggle=\"dropdown\">" . $Language->Phrase("ListActionButton") . "<b class=\"caret\"></b></button>";
				$content = "";
				foreach ($links as $link)
					$content .= "<li>" . $link . "</li>";
				$body .= "<ul class=\"dropdown-menu" . ($oListOpt->OnLeft ? "" : " dropdown-menu-right") . "\">". $content . "</ul>";
				$body = "<div class=\"btn-group\">" . $body . "</div>";
			}
			if (count($links) > 0) {
				$oListOpt->Body = $body;
				$oListOpt->Visible = TRUE;
			}
		}

		// "checkbox"
		$oListOpt = &$this->ListOptions->Items["checkbox"];
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->product_id->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event);'>";
		if ($this->CurrentAction == "gridedit" && is_numeric($this->RowIndex)) {
			$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $KeyName . "\" id=\"" . $KeyName . "\" value=\"" . $this->product_id->CurrentValue . "\">";
		}
		$this->RenderListOptionsExt();

		// Call ListOptions_Rendered event
		$this->ListOptions_Rendered();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = $options["addedit"];

		// Add
		$item = &$option->Add("add");
		$item->Body = "<a class=\"ewAddEdit ewAdd\" title=\"" . ew_HtmlTitle($Language->Phrase("AddLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("AddLink")) . "\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("AddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "");
		$item = &$option->Add("gridadd");
		$item->Body = "<a class=\"ewAddEdit ewGridAdd\" title=\"" . ew_HtmlTitle($Language->Phrase("GridAddLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("GridAddLink")) . "\" href=\"" . ew_HtmlEncode($this->GridAddUrl) . "\">" . $Language->Phrase("GridAddLink") . "</a>";
		$item->Visible = ($this->GridAddUrl <> "");

		// Add grid edit
		$option = $options["addedit"];
		$item = &$option->Add("gridedit");
		$item->Body = "<a class=\"ewAddEdit ewGridEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("GridEditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("GridEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GridEditUrl) . "\">" . $Language->Phrase("GridEditLink") . "</a>";
		$item->Visible = ($this->GridEditUrl <> "");
		$option = $options["action"];

		// Set up options default
		foreach ($options as &$option) {
			$option->UseImageAndText = TRUE;
			$option->UseDropDownButton = FALSE;
			$option->UseButtonGroup = TRUE;
			$option->ButtonClass = "btn-sm"; // Class for button group
			$item = &$option->Add($option->GroupOptionName);
			$item->Body = "";
			$item->Visible = FALSE;
		}
		$options["addedit"]->DropDownButtonPhrase = $Language->Phrase("ButtonAddEdit");
		$options["detail"]->DropDownButtonPhrase = $Language->Phrase("ButtonDetails");
		$options["action"]->DropDownButtonPhrase = $Language->Phrase("ButtonActions");

		// Filter button
		$item = &$this->FilterOptions->Add("savecurrentfilter");
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fproductslistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fproductslistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
		$item->Visible = TRUE;
		$this->FilterOptions->UseDropDownButton = TRUE;
		$this->FilterOptions->UseButtonGroup = !$this->FilterOptions->UseDropDownButton;
		$this->FilterOptions->DropDownButtonPhrase = $Language->Phrase("Filters");

		// Add group option item
		$item = &$this->FilterOptions->Add($this->FilterOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
	}

	// Render other options
	function RenderOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		if ($this->CurrentAction <> "gridadd" && $this->CurrentAction <> "gridedit") { // Not grid add/edit mode
			$option = &$options["action"];

			// Set up list action buttons
			foreach ($this->ListActions->Items as $listaction) {
				if ($listaction->Select == EW_ACTION_MULTIPLE) {
					$item = &$option->Add("custom_" . $listaction->Action);
					$caption = $listaction->Caption;
					$icon = ($listaction->Icon <> "") ? "<span class=\"" . ew_HtmlEncode($listaction->Icon) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\"></span> " : $caption;
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fproductslist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
					$item->Visible = $listaction->Allow;
				}
			}

			// Hide grid edit and other options
			if ($this->TotalRecs <= 0) {
				$option = &$options["addedit"];
				$item = &$option->GetItem("gridedit");
				if ($item) $item->Visible = FALSE;
				$option = &$options["action"];
				$option->HideAllOptions();
			}
		} else { // Grid add/edit mode

			// Hide all options first
			foreach ($options as &$option)
				$option->HideAllOptions();
			if ($this->CurrentAction == "gridadd") {
				if ($this->AllowAddDeleteRow) {

					// Add add blank row
					$option = &$options["addedit"];
					$option->UseDropDownButton = FALSE;
					$option->UseImageAndText = TRUE;
					$item = &$option->Add("addblankrow");
					$item->Body = "<a class=\"ewAddEdit ewAddBlankRow\" title=\"" . ew_HtmlTitle($Language->Phrase("AddBlankRow")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("AddBlankRow")) . "\" href=\"javascript:void(0);\" onclick=\"ew_AddGridRow(this);\">" . $Language->Phrase("AddBlankRow") . "</a>";
					$item->Visible = TRUE;
				}
				$option = &$options["action"];
				$option->UseDropDownButton = FALSE;
				$option->UseImageAndText = TRUE;

				// Add grid insert
				$item = &$option->Add("gridinsert");
				$item->Body = "<a class=\"ewAction ewGridInsert\" title=\"" . ew_HtmlTitle($Language->Phrase("GridInsertLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("GridInsertLink")) . "\" href=\"\" onclick=\"return ewForms(this).Submit();\">" . $Language->Phrase("GridInsertLink") . "</a>";

				// Add grid cancel
				$item = &$option->Add("gridcancel");
				$item->Body = "<a class=\"ewAction ewGridCancel\" title=\"" . ew_HtmlTitle($Language->Phrase("GridCancelLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("GridCancelLink")) . "\" href=\"" . $this->PageUrl() . "a=cancel\">" . $Language->Phrase("GridCancelLink") . "</a>";
			}
			if ($this->CurrentAction == "gridedit") {
				if ($this->AllowAddDeleteRow) {

					// Add add blank row
					$option = &$options["addedit"];
					$option->UseDropDownButton = FALSE;
					$option->UseImageAndText = TRUE;
					$item = &$option->Add("addblankrow");
					$item->Body = "<a class=\"ewAddEdit ewAddBlankRow\" title=\"" . ew_HtmlTitle($Language->Phrase("AddBlankRow")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("AddBlankRow")) . "\" href=\"javascript:void(0);\" onclick=\"ew_AddGridRow(this);\">" . $Language->Phrase("AddBlankRow") . "</a>";
					$item->Visible = TRUE;
				}
				$option = &$options["action"];
				$option->UseDropDownButton = FALSE;
				$option->UseImageAndText = TRUE;
					$item = &$option->Add("gridsave");
					$item->Body = "<a class=\"ewAction ewGridSave\" title=\"" . ew_HtmlTitle($Language->Phrase("GridSaveLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("GridSaveLink")) . "\" href=\"\" onclick=\"return ewForms(this).Submit();\">" . $Language->Phrase("GridSaveLink") . "</a>";
					$item = &$option->Add("gridcancel");
					$item->Body = "<a class=\"ewAction ewGridCancel\" title=\"" . ew_HtmlTitle($Language->Phrase("GridCancelLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("GridCancelLink")) . "\" href=\"" . $this->PageUrl() . "a=cancel\">" . $Language->Phrase("GridCancelLink") . "</a>";
			}
		}
	}

	// Process list action
	function ProcessListAction() {
		global $Language, $Security;
		$userlist = "";
		$user = "";
		$sFilter = $this->GetKeyFilter();
		$UserAction = @$_POST["useraction"];
		if ($sFilter <> "" && $UserAction <> "") {

			// Check permission first
			$ActionCaption = $UserAction;
			if (array_key_exists($UserAction, $this->ListActions->Items)) {
				$ActionCaption = $this->ListActions->Items[$UserAction]->Caption;
				if (!$this->ListActions->Items[$UserAction]->Allow) {
					$errmsg = str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionNotAllowed"));
					if (@$_POST["ajax"] == $UserAction) // Ajax
						echo "<p class=\"text-danger\">" . $errmsg . "</p>";
					else
						$this->setFailureMessage($errmsg);
					return FALSE;
				}
			}
			$this->CurrentFilter = $sFilter;
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$rs = $conn->Execute($sSql);
			$conn->raiseErrorFn = '';
			$this->CurrentAction = $UserAction;

			// Call row action event
			if ($rs && !$rs->EOF) {
				$conn->BeginTrans();
				$this->SelectedCount = $rs->RecordCount();
				$this->SelectedIndex = 0;
				while (!$rs->EOF) {
					$this->SelectedIndex++;
					$row = $rs->fields;
					$Processed = $this->Row_CustomAction($UserAction, $row);
					if (!$Processed) break;
					$rs->MoveNext();
				}
				if ($Processed) {
					$conn->CommitTrans(); // Commit the changes
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage(str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionCompleted"))); // Set up success message
				} else {
					$conn->RollbackTrans(); // Rollback changes

					// Set up error message
					if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

						// Use the message, do nothing
					} elseif ($this->CancelMessage <> "") {
						$this->setFailureMessage($this->CancelMessage);
						$this->CancelMessage = "";
					} else {
						$this->setFailureMessage(str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionFailed")));
					}
				}
			}
			if ($rs)
				$rs->Close();
			if (@$_POST["ajax"] == $UserAction) { // Ajax
				if ($this->getSuccessMessage() <> "") {
					echo "<p class=\"text-success\">" . $this->getSuccessMessage() . "</p>";
					$this->ClearSuccessMessage(); // Clear message
				}
				if ($this->getFailureMessage() <> "") {
					echo "<p class=\"text-danger\">" . $this->getFailureMessage() . "</p>";
					$this->ClearFailureMessage(); // Clear message
				}
				return TRUE;
			}
		}
		return FALSE; // Not ajax request
	}

	// Set up search options
	function SetupSearchOptions() {
		global $Language;
		$this->SearchOptions = new cListOptions();
		$this->SearchOptions->Tag = "div";
		$this->SearchOptions->TagClassName = "ewSearchOption";

		// Search button
		$item = &$this->SearchOptions->Add("searchtoggle");
		$SearchToggleClass = ($this->SearchWhere <> "") ? " active" : " active";
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fproductslistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
		$item->Visible = TRUE;

		// Show all button
		$item = &$this->SearchOptions->Add("showall");
		$item->Body = "<a class=\"btn btn-default ewShowAll\" title=\"" . $Language->Phrase("ShowAll") . "\" data-caption=\"" . $Language->Phrase("ShowAll") . "\" href=\"" . $this->PageUrl() . "cmd=reset\">" . $Language->Phrase("ShowAllBtn") . "</a>";
		$item->Visible = ($this->SearchWhere <> $this->DefaultSearchWhere && $this->SearchWhere <> "0=101");

		// Button group for search
		$this->SearchOptions->UseDropDownButton = FALSE;
		$this->SearchOptions->UseImageAndText = TRUE;
		$this->SearchOptions->UseButtonGroup = TRUE;
		$this->SearchOptions->DropDownButtonPhrase = $Language->Phrase("ButtonSearch");

		// Add group option item
		$item = &$this->SearchOptions->Add($this->SearchOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Hide search options
		if ($this->Export <> "" || $this->CurrentAction <> "")
			$this->SearchOptions->HideAllOptions();
	}

	function SetupListOptionsExt() {
		global $Security, $Language;
	}

	function RenderListOptionsExt() {
		global $Security, $Language;
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

	// Load default values
	function LoadDefaultValues() {
		$this->product_id->CurrentValue = NULL;
		$this->product_id->OldValue = $this->product_id->CurrentValue;
		$this->category_id->CurrentValue = NULL;
		$this->category_id->OldValue = $this->category_id->CurrentValue;
		$this->scat_id->CurrentValue = NULL;
		$this->scat_id->OldValue = $this->scat_id->CurrentValue;
		$this->product_name->CurrentValue = NULL;
		$this->product_name->OldValue = $this->product_name->CurrentValue;
		$this->product_image->Upload->DbValue = NULL;
		$this->product_image->OldValue = $this->product_image->Upload->DbValue;
		$this->product_secimage->Upload->DbValue = NULL;
		$this->product_secimage->OldValue = $this->product_secimage->Upload->DbValue;
	}

	// Load basic search values
	function LoadBasicSearchValues() {
		$this->BasicSearch->Keyword = @$_GET[EW_TABLE_BASIC_SEARCH];
		if ($this->BasicSearch->Keyword <> "") $this->Command = "search";
		$this->BasicSearch->Type = @$_GET[EW_TABLE_BASIC_SEARCH_TYPE];
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->product_id->FldIsDetailKey && $this->CurrentAction <> "gridadd" && $this->CurrentAction <> "add")
			$this->product_id->setFormValue($objForm->GetValue("x_product_id"));
		if (!$this->category_id->FldIsDetailKey) {
			$this->category_id->setFormValue($objForm->GetValue("x_category_id"));
		}
		$this->category_id->setOldValue($objForm->GetValue("o_category_id"));
		if (!$this->scat_id->FldIsDetailKey) {
			$this->scat_id->setFormValue($objForm->GetValue("x_scat_id"));
		}
		$this->scat_id->setOldValue($objForm->GetValue("o_scat_id"));
		if (!$this->product_name->FldIsDetailKey) {
			$this->product_name->setFormValue($objForm->GetValue("x_product_name"));
		}
		$this->product_name->setOldValue($objForm->GetValue("o_product_name"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		if ($this->CurrentAction <> "gridadd" && $this->CurrentAction <> "add")
			$this->product_id->CurrentValue = $this->product_id->FormValue;
		$this->category_id->CurrentValue = $this->category_id->FormValue;
		$this->scat_id->CurrentValue = $this->scat_id->FormValue;
		$this->product_name->CurrentValue = $this->product_name->FormValue;
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

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("product_id")) <> "")
			$this->product_id->CurrentValue = $this->getKey("product_id"); // product_id
		else
			$bValidKey = FALSE;

		// Load old recordset
		if ($bValidKey) {
			$this->CurrentFilter = $this->KeyFilter();
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$this->OldRecordset = ew_LoadRecordset($sSql, $conn);
			$this->LoadRowValues($this->OldRecordset); // Load row values
		} else {
			$this->OldRecordset = NULL;
		}
		return $bValidKey;
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		$this->ViewUrl = $this->GetViewUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->InlineEditUrl = $this->GetInlineEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->InlineCopyUrl = $this->GetInlineCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();

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
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// product_id
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
				$this->scat_id->OldValue = $this->scat_id->CurrentValue;
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
			if (is_numeric($this->RowIndex) && !$this->EventCancelled) ew_RenderUploadField($this->product_image, $this->RowIndex);

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
			if (is_numeric($this->RowIndex) && !$this->EventCancelled) ew_RenderUploadField($this->product_secimage, $this->RowIndex);

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
				$this->scat_id->OldValue = $this->scat_id->CurrentValue;
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
			if (is_numeric($this->RowIndex) && !$this->EventCancelled) ew_RenderUploadField($this->product_image, $this->RowIndex);

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
			if (is_numeric($this->RowIndex) && !$this->EventCancelled) ew_RenderUploadField($this->product_secimage, $this->RowIndex);

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
		} else {
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
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

	// Add record
	function AddRow($rsold = NULL) {
		global $Language, $Security;
		$conn = &$this->Connection();

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// category_id
		$this->category_id->SetDbValueDef($rsnew, $this->category_id->CurrentValue, 0, FALSE);

		// scat_id
		$this->scat_id->SetDbValueDef($rsnew, $this->scat_id->CurrentValue, 0, FALSE);

		// product_name
		$this->product_name->SetDbValueDef($rsnew, $this->product_name->CurrentValue, "", FALSE);

		// product_image
		if (!$this->product_image->Upload->KeepFile) {
			$this->product_image->Upload->DbValue = ""; // No need to delete old file
			if ($this->product_image->Upload->FileName == "") {
				$rsnew['product_image'] = NULL;
			} else {
				$rsnew['product_image'] = $this->product_image->Upload->FileName;
			}
		}

		// product_secimage
		if (!$this->product_secimage->Upload->KeepFile) {
			$this->product_secimage->Upload->DbValue = ""; // No need to delete old file
			if ($this->product_secimage->Upload->FileName == "") {
				$rsnew['product_secimage'] = NULL;
			} else {
				$rsnew['product_secimage'] = $this->product_secimage->Upload->FileName;
			}
		}
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

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {

				// Get insert id if necessary
				$this->product_id->setDbValue($conn->Insert_ID());
				$rsnew['product_id'] = $this->product_id->DbValue;
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
				$this->setFailureMessage($Language->Phrase("InsertCancelled"));
			}
			$AddRow = FALSE;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}

		// product_image
		ew_CleanUploadTempPath($this->product_image, $this->product_image->Upload->Index);

		// product_secimage
		ew_CleanUploadTempPath($this->product_secimage, $this->product_secimage->Upload->Index);
		return $AddRow;
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
		$url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset / cmd=resetall
		$Breadcrumb->Add("list", $this->TableVar, $url, "", $this->TableVar, TRUE);
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

	// ListOptions Load event
	function ListOptions_Load() {

		// Example:
		//$opt = &$this->ListOptions->Add("new");
		//$opt->Header = "xxx";
		//$opt->OnLeft = TRUE; // Link on left
		//$opt->MoveTo(0); // Move to first column

	}

	// ListOptions Rendered event
	function ListOptions_Rendered() {

		// Example: 
		//$this->ListOptions->Items["new"]->Body = "xxx";

	}

	// Row Custom Action event
	function Row_CustomAction($action, $row) {

		// Return FALSE to abort
		return TRUE;
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
if (!isset($products_list)) $products_list = new cproducts_list();

// Page init
$products_list->Page_Init();

// Page main
$products_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$products_list->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fproductslist = new ew_Form("fproductslist", "list");
fproductslist.FormKeyCountName = '<?php echo $products_list->FormKeyCountName ?>';

// Validate form
fproductslist.Validate = function() {
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
	if (gridinsert && addcnt == 0) { // No row added
		ew_Alert(ewLanguage.Phrase("NoAddRecord"));
		return false;
	}
	return true;
}

// Check empty row
fproductslist.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "category_id", false)) return false;
	if (ew_ValueChanged(fobj, infix, "scat_id", false)) return false;
	if (ew_ValueChanged(fobj, infix, "product_name", false)) return false;
	if (ew_ValueChanged(fobj, infix, "product_image", false)) return false;
	if (ew_ValueChanged(fobj, infix, "product_secimage", false)) return false;
	return true;
}

// Form_CustomValidate event
fproductslist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fproductslist.ValidateRequired = true;
<?php } else { ?>
fproductslist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fproductslist.Lists["x_category_id"] = {"LinkField":"x_category_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_category_name","","",""],"ParentFields":[],"ChildFields":["x_scat_id"],"FilterFields":[],"Options":[],"Template":""};
fproductslist.Lists["x_scat_id"] = {"LinkField":"x_scat_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_scat_name","","",""],"ParentFields":["x_category_id"],"ChildFields":[],"FilterFields":["x_category_id"],"Options":[],"Template":""};

// Form object for search
var CurrentSearchForm = fproductslistsrch = new ew_Form("fproductslistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php if ($products_list->TotalRecs > 0 && $products_list->ExportOptions->Visible()) { ?>
<?php $products_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($products_list->SearchOptions->Visible()) { ?>
<?php $products_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($products_list->FilterOptions->Visible()) { ?>
<?php $products_list->FilterOptions->Render("body") ?>
<?php } ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php if (($products->Export == "") || (EW_EXPORT_MASTER_RECORD && $products->Export == "print")) { ?>
<?php
$gsMasterReturnUrl = "sub_categorylist.php";
if ($products_list->DbMasterFilter <> "" && $products->getCurrentMasterTable() == "sub_category") {
	if ($products_list->MasterRecordExists) {
		if ($products->getCurrentMasterTable() == $products->TableVar) $gsMasterReturnUrl .= "?" . EW_TABLE_SHOW_MASTER . "=";
?>
<?php include_once "sub_categorymaster.php" ?>
<?php
	}
}
?>
<?php } ?>
<?php
if ($products->CurrentAction == "gridadd") {
	$products->CurrentFilter = "0=1";
	$products_list->StartRec = 1;
	$products_list->DisplayRecs = $products->GridAddRowCount;
	$products_list->TotalRecs = $products_list->DisplayRecs;
	$products_list->StopRec = $products_list->DisplayRecs;
} else {
	$bSelectLimit = $products_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($products_list->TotalRecs <= 0)
			$products_list->TotalRecs = $products->SelectRecordCount();
	} else {
		if (!$products_list->Recordset && ($products_list->Recordset = $products_list->LoadRecordset()))
			$products_list->TotalRecs = $products_list->Recordset->RecordCount();
	}
	$products_list->StartRec = 1;
	if ($products_list->DisplayRecs <= 0 || ($products->Export <> "" && $products->ExportAll)) // Display all records
		$products_list->DisplayRecs = $products_list->TotalRecs;
	if (!($products->Export <> "" && $products->ExportAll))
		$products_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$products_list->Recordset = $products_list->LoadRecordset($products_list->StartRec-1, $products_list->DisplayRecs);

	// Set no record found message
	if ($products->CurrentAction == "" && $products_list->TotalRecs == 0) {
		if ($products_list->SearchWhere == "0=101")
			$products_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$products_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$products_list->RenderOtherOptions();
?>
<?php if ($products->Export == "" && $products->CurrentAction == "") { ?>
<form name="fproductslistsrch" id="fproductslistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($products_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="fproductslistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="products">
	<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($products_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($products_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $products_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($products_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($products_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($products_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($products_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
		</ul>
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
	</div>
	</div>
</div>
	</div>
</div>
</form>
<?php } ?>
<?php $products_list->ShowPageHeader(); ?>
<?php
$products_list->ShowMessage();
?>
<?php if ($products_list->TotalRecs > 0 || $products->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid">
<form name="fproductslist" id="fproductslist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($products_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $products_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="products">
<div id="gmp_products" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($products_list->TotalRecs > 0) { ?>
<table id="tbl_productslist" class="table ewTable">
<?php echo $products->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$products_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$products_list->RenderListOptions();

// Render list options (header, left)
$products_list->ListOptions->Render("header", "left");
?>
<?php if ($products->product_id->Visible) { // product_id ?>
	<?php if ($products->SortUrl($products->product_id) == "") { ?>
		<th data-name="product_id"><div id="elh_products_product_id" class="products_product_id"><div class="ewTableHeaderCaption"><?php echo $products->product_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="product_id"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $products->SortUrl($products->product_id) ?>',1);"><div id="elh_products_product_id" class="products_product_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $products->product_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($products->product_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($products->product_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($products->category_id->Visible) { // category_id ?>
	<?php if ($products->SortUrl($products->category_id) == "") { ?>
		<th data-name="category_id"><div id="elh_products_category_id" class="products_category_id"><div class="ewTableHeaderCaption"><?php echo $products->category_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="category_id"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $products->SortUrl($products->category_id) ?>',1);"><div id="elh_products_category_id" class="products_category_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $products->category_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($products->category_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($products->category_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($products->scat_id->Visible) { // scat_id ?>
	<?php if ($products->SortUrl($products->scat_id) == "") { ?>
		<th data-name="scat_id"><div id="elh_products_scat_id" class="products_scat_id"><div class="ewTableHeaderCaption"><?php echo $products->scat_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="scat_id"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $products->SortUrl($products->scat_id) ?>',1);"><div id="elh_products_scat_id" class="products_scat_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $products->scat_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($products->scat_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($products->scat_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($products->product_name->Visible) { // product_name ?>
	<?php if ($products->SortUrl($products->product_name) == "") { ?>
		<th data-name="product_name"><div id="elh_products_product_name" class="products_product_name"><div class="ewTableHeaderCaption"><?php echo $products->product_name->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="product_name"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $products->SortUrl($products->product_name) ?>',1);"><div id="elh_products_product_name" class="products_product_name">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $products->product_name->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($products->product_name->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($products->product_name->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($products->product_image->Visible) { // product_image ?>
	<?php if ($products->SortUrl($products->product_image) == "") { ?>
		<th data-name="product_image"><div id="elh_products_product_image" class="products_product_image"><div class="ewTableHeaderCaption"><?php echo $products->product_image->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="product_image"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $products->SortUrl($products->product_image) ?>',1);"><div id="elh_products_product_image" class="products_product_image">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $products->product_image->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($products->product_image->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($products->product_image->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($products->product_secimage->Visible) { // product_secimage ?>
	<?php if ($products->SortUrl($products->product_secimage) == "") { ?>
		<th data-name="product_secimage"><div id="elh_products_product_secimage" class="products_product_secimage"><div class="ewTableHeaderCaption"><?php echo $products->product_secimage->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="product_secimage"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $products->SortUrl($products->product_secimage) ?>',1);"><div id="elh_products_product_secimage" class="products_product_secimage">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $products->product_secimage->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($products->product_secimage->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($products->product_secimage->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$products_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($products->ExportAll && $products->Export <> "") {
	$products_list->StopRec = $products_list->TotalRecs;
} else {

	// Set the last record to display
	if ($products_list->TotalRecs > $products_list->StartRec + $products_list->DisplayRecs - 1)
		$products_list->StopRec = $products_list->StartRec + $products_list->DisplayRecs - 1;
	else
		$products_list->StopRec = $products_list->TotalRecs;
}

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($products_list->FormKeyCountName) && ($products->CurrentAction == "gridadd" || $products->CurrentAction == "gridedit" || $products->CurrentAction == "F")) {
		$products_list->KeyCount = $objForm->GetValue($products_list->FormKeyCountName);
		$products_list->StopRec = $products_list->StartRec + $products_list->KeyCount - 1;
	}
}
$products_list->RecCnt = $products_list->StartRec - 1;
if ($products_list->Recordset && !$products_list->Recordset->EOF) {
	$products_list->Recordset->MoveFirst();
	$bSelectLimit = $products_list->UseSelectLimit;
	if (!$bSelectLimit && $products_list->StartRec > 1)
		$products_list->Recordset->Move($products_list->StartRec - 1);
} elseif (!$products->AllowAddDeleteRow && $products_list->StopRec == 0) {
	$products_list->StopRec = $products->GridAddRowCount;
}

// Initialize aggregate
$products->RowType = EW_ROWTYPE_AGGREGATEINIT;
$products->ResetAttrs();
$products_list->RenderRow();
if ($products->CurrentAction == "gridadd")
	$products_list->RowIndex = 0;
if ($products->CurrentAction == "gridedit")
	$products_list->RowIndex = 0;
while ($products_list->RecCnt < $products_list->StopRec) {
	$products_list->RecCnt++;
	if (intval($products_list->RecCnt) >= intval($products_list->StartRec)) {
		$products_list->RowCnt++;
		if ($products->CurrentAction == "gridadd" || $products->CurrentAction == "gridedit" || $products->CurrentAction == "F") {
			$products_list->RowIndex++;
			$objForm->Index = $products_list->RowIndex;
			if ($objForm->HasValue($products_list->FormActionName))
				$products_list->RowAction = strval($objForm->GetValue($products_list->FormActionName));
			elseif ($products->CurrentAction == "gridadd")
				$products_list->RowAction = "insert";
			else
				$products_list->RowAction = "";
		}

		// Set up key count
		$products_list->KeyCount = $products_list->RowIndex;

		// Init row class and style
		$products->ResetAttrs();
		$products->CssClass = "";
		if ($products->CurrentAction == "gridadd") {
			$products_list->LoadDefaultValues(); // Load default values
		} else {
			$products_list->LoadRowValues($products_list->Recordset); // Load row values
		}
		$products->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($products->CurrentAction == "gridadd") // Grid add
			$products->RowType = EW_ROWTYPE_ADD; // Render add
		if ($products->CurrentAction == "gridadd" && $products->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$products_list->RestoreCurrentRowFormValues($products_list->RowIndex); // Restore form values
		if ($products->CurrentAction == "gridedit") { // Grid edit
			if ($products->EventCancelled) {
				$products_list->RestoreCurrentRowFormValues($products_list->RowIndex); // Restore form values
			}
			if ($products_list->RowAction == "insert")
				$products->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$products->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($products->CurrentAction == "gridedit" && ($products->RowType == EW_ROWTYPE_EDIT || $products->RowType == EW_ROWTYPE_ADD) && $products->EventCancelled) // Update failed
			$products_list->RestoreCurrentRowFormValues($products_list->RowIndex); // Restore form values
		if ($products->RowType == EW_ROWTYPE_EDIT) // Edit row
			$products_list->EditRowCnt++;

		// Set up row id / data-rowindex
		$products->RowAttrs = array_merge($products->RowAttrs, array('data-rowindex'=>$products_list->RowCnt, 'id'=>'r' . $products_list->RowCnt . '_products', 'data-rowtype'=>$products->RowType));

		// Render row
		$products_list->RenderRow();

		// Render list options
		$products_list->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($products_list->RowAction <> "delete" && $products_list->RowAction <> "insertdelete" && !($products_list->RowAction == "insert" && $products->CurrentAction == "F" && $products_list->EmptyRow())) {
?>
	<tr<?php echo $products->RowAttributes() ?>>
<?php

// Render list options (body, left)
$products_list->ListOptions->Render("body", "left", $products_list->RowCnt);
?>
	<?php if ($products->product_id->Visible) { // product_id ?>
		<td data-name="product_id"<?php echo $products->product_id->CellAttributes() ?>>
<?php if ($products->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="products" data-field="x_product_id" name="o<?php echo $products_list->RowIndex ?>_product_id" id="o<?php echo $products_list->RowIndex ?>_product_id" value="<?php echo ew_HtmlEncode($products->product_id->OldValue) ?>">
<?php } ?>
<?php if ($products->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $products_list->RowCnt ?>_products_product_id" class="form-group products_product_id">
<span<?php echo $products->product_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $products->product_id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="products" data-field="x_product_id" name="x<?php echo $products_list->RowIndex ?>_product_id" id="x<?php echo $products_list->RowIndex ?>_product_id" value="<?php echo ew_HtmlEncode($products->product_id->CurrentValue) ?>">
<?php } ?>
<?php if ($products->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $products_list->RowCnt ?>_products_product_id" class="products_product_id">
<span<?php echo $products->product_id->ViewAttributes() ?>>
<?php echo $products->product_id->ListViewValue() ?></span>
</span>
<?php } ?>
<a id="<?php echo $products_list->PageObjName . "_row_" . $products_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($products->category_id->Visible) { // category_id ?>
		<td data-name="category_id"<?php echo $products->category_id->CellAttributes() ?>>
<?php if ($products->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $products_list->RowCnt ?>_products_category_id" class="form-group products_category_id">
<?php $products->category_id->EditAttrs["onchange"] = "ew_UpdateOpt.call(this); " . @$products->category_id->EditAttrs["onchange"]; ?>
<select data-table="products" data-field="x_category_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($products->category_id->DisplayValueSeparator) ? json_encode($products->category_id->DisplayValueSeparator) : $products->category_id->DisplayValueSeparator) ?>" id="x<?php echo $products_list->RowIndex ?>_category_id" name="x<?php echo $products_list->RowIndex ?>_category_id"<?php echo $products->category_id->EditAttributes() ?>>
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
<input type="hidden" name="s_x<?php echo $products_list->RowIndex ?>_category_id" id="s_x<?php echo $products_list->RowIndex ?>_category_id" value="<?php echo $products->category_id->LookupFilterQuery() ?>">
</span>
<input type="hidden" data-table="products" data-field="x_category_id" name="o<?php echo $products_list->RowIndex ?>_category_id" id="o<?php echo $products_list->RowIndex ?>_category_id" value="<?php echo ew_HtmlEncode($products->category_id->OldValue) ?>">
<?php } ?>
<?php if ($products->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $products_list->RowCnt ?>_products_category_id" class="form-group products_category_id">
<?php $products->category_id->EditAttrs["onchange"] = "ew_UpdateOpt.call(this); " . @$products->category_id->EditAttrs["onchange"]; ?>
<select data-table="products" data-field="x_category_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($products->category_id->DisplayValueSeparator) ? json_encode($products->category_id->DisplayValueSeparator) : $products->category_id->DisplayValueSeparator) ?>" id="x<?php echo $products_list->RowIndex ?>_category_id" name="x<?php echo $products_list->RowIndex ?>_category_id"<?php echo $products->category_id->EditAttributes() ?>>
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
<input type="hidden" name="s_x<?php echo $products_list->RowIndex ?>_category_id" id="s_x<?php echo $products_list->RowIndex ?>_category_id" value="<?php echo $products->category_id->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php if ($products->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $products_list->RowCnt ?>_products_category_id" class="products_category_id">
<span<?php echo $products->category_id->ViewAttributes() ?>>
<?php echo $products->category_id->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($products->scat_id->Visible) { // scat_id ?>
		<td data-name="scat_id"<?php echo $products->scat_id->CellAttributes() ?>>
<?php if ($products->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($products->scat_id->getSessionValue() <> "") { ?>
<span id="el<?php echo $products_list->RowCnt ?>_products_scat_id" class="form-group products_scat_id">
<span<?php echo $products->scat_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $products->scat_id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $products_list->RowIndex ?>_scat_id" name="x<?php echo $products_list->RowIndex ?>_scat_id" value="<?php echo ew_HtmlEncode($products->scat_id->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $products_list->RowCnt ?>_products_scat_id" class="form-group products_scat_id">
<select data-table="products" data-field="x_scat_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($products->scat_id->DisplayValueSeparator) ? json_encode($products->scat_id->DisplayValueSeparator) : $products->scat_id->DisplayValueSeparator) ?>" id="x<?php echo $products_list->RowIndex ?>_scat_id" name="x<?php echo $products_list->RowIndex ?>_scat_id"<?php echo $products->scat_id->EditAttributes() ?>>
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
<input type="hidden" name="s_x<?php echo $products_list->RowIndex ?>_scat_id" id="s_x<?php echo $products_list->RowIndex ?>_scat_id" value="<?php echo $products->scat_id->LookupFilterQuery() ?>">
</span>
<?php } ?>
<input type="hidden" data-table="products" data-field="x_scat_id" name="o<?php echo $products_list->RowIndex ?>_scat_id" id="o<?php echo $products_list->RowIndex ?>_scat_id" value="<?php echo ew_HtmlEncode($products->scat_id->OldValue) ?>">
<?php } ?>
<?php if ($products->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($products->scat_id->getSessionValue() <> "") { ?>
<span id="el<?php echo $products_list->RowCnt ?>_products_scat_id" class="form-group products_scat_id">
<span<?php echo $products->scat_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $products->scat_id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $products_list->RowIndex ?>_scat_id" name="x<?php echo $products_list->RowIndex ?>_scat_id" value="<?php echo ew_HtmlEncode($products->scat_id->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $products_list->RowCnt ?>_products_scat_id" class="form-group products_scat_id">
<select data-table="products" data-field="x_scat_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($products->scat_id->DisplayValueSeparator) ? json_encode($products->scat_id->DisplayValueSeparator) : $products->scat_id->DisplayValueSeparator) ?>" id="x<?php echo $products_list->RowIndex ?>_scat_id" name="x<?php echo $products_list->RowIndex ?>_scat_id"<?php echo $products->scat_id->EditAttributes() ?>>
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
<input type="hidden" name="s_x<?php echo $products_list->RowIndex ?>_scat_id" id="s_x<?php echo $products_list->RowIndex ?>_scat_id" value="<?php echo $products->scat_id->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php } ?>
<?php if ($products->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $products_list->RowCnt ?>_products_scat_id" class="products_scat_id">
<span<?php echo $products->scat_id->ViewAttributes() ?>>
<?php echo $products->scat_id->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($products->product_name->Visible) { // product_name ?>
		<td data-name="product_name"<?php echo $products->product_name->CellAttributes() ?>>
<?php if ($products->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $products_list->RowCnt ?>_products_product_name" class="form-group products_product_name">
<input type="text" data-table="products" data-field="x_product_name" name="x<?php echo $products_list->RowIndex ?>_product_name" id="x<?php echo $products_list->RowIndex ?>_product_name" placeholder="<?php echo ew_HtmlEncode($products->product_name->getPlaceHolder()) ?>" value="<?php echo $products->product_name->EditValue ?>"<?php echo $products->product_name->EditAttributes() ?>>
</span>
<input type="hidden" data-table="products" data-field="x_product_name" name="o<?php echo $products_list->RowIndex ?>_product_name" id="o<?php echo $products_list->RowIndex ?>_product_name" value="<?php echo ew_HtmlEncode($products->product_name->OldValue) ?>">
<?php } ?>
<?php if ($products->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $products_list->RowCnt ?>_products_product_name" class="form-group products_product_name">
<input type="text" data-table="products" data-field="x_product_name" name="x<?php echo $products_list->RowIndex ?>_product_name" id="x<?php echo $products_list->RowIndex ?>_product_name" placeholder="<?php echo ew_HtmlEncode($products->product_name->getPlaceHolder()) ?>" value="<?php echo $products->product_name->EditValue ?>"<?php echo $products->product_name->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($products->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $products_list->RowCnt ?>_products_product_name" class="products_product_name">
<span<?php echo $products->product_name->ViewAttributes() ?>>
<?php echo $products->product_name->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($products->product_image->Visible) { // product_image ?>
		<td data-name="product_image"<?php echo $products->product_image->CellAttributes() ?>>
<?php if ($products->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $products_list->RowCnt ?>_products_product_image" class="form-group products_product_image">
<div id="fd_x<?php echo $products_list->RowIndex ?>_product_image">
<span title="<?php echo $products->product_image->FldTitle() ? $products->product_image->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($products->product_image->ReadOnly || $products->product_image->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="products" data-field="x_product_image" name="x<?php echo $products_list->RowIndex ?>_product_image" id="x<?php echo $products_list->RowIndex ?>_product_image"<?php echo $products->product_image->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x<?php echo $products_list->RowIndex ?>_product_image" id= "fn_x<?php echo $products_list->RowIndex ?>_product_image" value="<?php echo $products->product_image->Upload->FileName ?>">
<input type="hidden" name="fa_x<?php echo $products_list->RowIndex ?>_product_image" id= "fa_x<?php echo $products_list->RowIndex ?>_product_image" value="0">
<input type="hidden" name="fs_x<?php echo $products_list->RowIndex ?>_product_image" id= "fs_x<?php echo $products_list->RowIndex ?>_product_image" value="200">
<input type="hidden" name="fx_x<?php echo $products_list->RowIndex ?>_product_image" id= "fx_x<?php echo $products_list->RowIndex ?>_product_image" value="<?php echo $products->product_image->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?php echo $products_list->RowIndex ?>_product_image" id= "fm_x<?php echo $products_list->RowIndex ?>_product_image" value="<?php echo $products->product_image->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?php echo $products_list->RowIndex ?>_product_image" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<input type="hidden" data-table="products" data-field="x_product_image" name="o<?php echo $products_list->RowIndex ?>_product_image" id="o<?php echo $products_list->RowIndex ?>_product_image" value="<?php echo ew_HtmlEncode($products->product_image->OldValue) ?>">
<?php } ?>
<?php if ($products->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $products_list->RowCnt ?>_products_product_image" class="form-group products_product_image">
<div id="fd_x<?php echo $products_list->RowIndex ?>_product_image">
<span title="<?php echo $products->product_image->FldTitle() ? $products->product_image->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($products->product_image->ReadOnly || $products->product_image->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="products" data-field="x_product_image" name="x<?php echo $products_list->RowIndex ?>_product_image" id="x<?php echo $products_list->RowIndex ?>_product_image"<?php echo $products->product_image->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x<?php echo $products_list->RowIndex ?>_product_image" id= "fn_x<?php echo $products_list->RowIndex ?>_product_image" value="<?php echo $products->product_image->Upload->FileName ?>">
<?php if (@$_POST["fa_x<?php echo $products_list->RowIndex ?>_product_image"] == "0") { ?>
<input type="hidden" name="fa_x<?php echo $products_list->RowIndex ?>_product_image" id= "fa_x<?php echo $products_list->RowIndex ?>_product_image" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x<?php echo $products_list->RowIndex ?>_product_image" id= "fa_x<?php echo $products_list->RowIndex ?>_product_image" value="1">
<?php } ?>
<input type="hidden" name="fs_x<?php echo $products_list->RowIndex ?>_product_image" id= "fs_x<?php echo $products_list->RowIndex ?>_product_image" value="200">
<input type="hidden" name="fx_x<?php echo $products_list->RowIndex ?>_product_image" id= "fx_x<?php echo $products_list->RowIndex ?>_product_image" value="<?php echo $products->product_image->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?php echo $products_list->RowIndex ?>_product_image" id= "fm_x<?php echo $products_list->RowIndex ?>_product_image" value="<?php echo $products->product_image->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?php echo $products_list->RowIndex ?>_product_image" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php } ?>
<?php if ($products->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $products_list->RowCnt ?>_products_product_image" class="products_product_image">
<span<?php echo $products->product_image->ViewAttributes() ?>>
<?php echo ew_GetFileViewTag($products->product_image, $products->product_image->ListViewValue()) ?>
</span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($products->product_secimage->Visible) { // product_secimage ?>
		<td data-name="product_secimage"<?php echo $products->product_secimage->CellAttributes() ?>>
<?php if ($products->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $products_list->RowCnt ?>_products_product_secimage" class="form-group products_product_secimage">
<div id="fd_x<?php echo $products_list->RowIndex ?>_product_secimage">
<span title="<?php echo $products->product_secimage->FldTitle() ? $products->product_secimage->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($products->product_secimage->ReadOnly || $products->product_secimage->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="products" data-field="x_product_secimage" name="x<?php echo $products_list->RowIndex ?>_product_secimage" id="x<?php echo $products_list->RowIndex ?>_product_secimage"<?php echo $products->product_secimage->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x<?php echo $products_list->RowIndex ?>_product_secimage" id= "fn_x<?php echo $products_list->RowIndex ?>_product_secimage" value="<?php echo $products->product_secimage->Upload->FileName ?>">
<input type="hidden" name="fa_x<?php echo $products_list->RowIndex ?>_product_secimage" id= "fa_x<?php echo $products_list->RowIndex ?>_product_secimage" value="0">
<input type="hidden" name="fs_x<?php echo $products_list->RowIndex ?>_product_secimage" id= "fs_x<?php echo $products_list->RowIndex ?>_product_secimage" value="200">
<input type="hidden" name="fx_x<?php echo $products_list->RowIndex ?>_product_secimage" id= "fx_x<?php echo $products_list->RowIndex ?>_product_secimage" value="<?php echo $products->product_secimage->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?php echo $products_list->RowIndex ?>_product_secimage" id= "fm_x<?php echo $products_list->RowIndex ?>_product_secimage" value="<?php echo $products->product_secimage->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?php echo $products_list->RowIndex ?>_product_secimage" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<input type="hidden" data-table="products" data-field="x_product_secimage" name="o<?php echo $products_list->RowIndex ?>_product_secimage" id="o<?php echo $products_list->RowIndex ?>_product_secimage" value="<?php echo ew_HtmlEncode($products->product_secimage->OldValue) ?>">
<?php } ?>
<?php if ($products->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $products_list->RowCnt ?>_products_product_secimage" class="form-group products_product_secimage">
<div id="fd_x<?php echo $products_list->RowIndex ?>_product_secimage">
<span title="<?php echo $products->product_secimage->FldTitle() ? $products->product_secimage->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($products->product_secimage->ReadOnly || $products->product_secimage->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="products" data-field="x_product_secimage" name="x<?php echo $products_list->RowIndex ?>_product_secimage" id="x<?php echo $products_list->RowIndex ?>_product_secimage"<?php echo $products->product_secimage->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x<?php echo $products_list->RowIndex ?>_product_secimage" id= "fn_x<?php echo $products_list->RowIndex ?>_product_secimage" value="<?php echo $products->product_secimage->Upload->FileName ?>">
<?php if (@$_POST["fa_x<?php echo $products_list->RowIndex ?>_product_secimage"] == "0") { ?>
<input type="hidden" name="fa_x<?php echo $products_list->RowIndex ?>_product_secimage" id= "fa_x<?php echo $products_list->RowIndex ?>_product_secimage" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x<?php echo $products_list->RowIndex ?>_product_secimage" id= "fa_x<?php echo $products_list->RowIndex ?>_product_secimage" value="1">
<?php } ?>
<input type="hidden" name="fs_x<?php echo $products_list->RowIndex ?>_product_secimage" id= "fs_x<?php echo $products_list->RowIndex ?>_product_secimage" value="200">
<input type="hidden" name="fx_x<?php echo $products_list->RowIndex ?>_product_secimage" id= "fx_x<?php echo $products_list->RowIndex ?>_product_secimage" value="<?php echo $products->product_secimage->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?php echo $products_list->RowIndex ?>_product_secimage" id= "fm_x<?php echo $products_list->RowIndex ?>_product_secimage" value="<?php echo $products->product_secimage->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?php echo $products_list->RowIndex ?>_product_secimage" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php } ?>
<?php if ($products->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $products_list->RowCnt ?>_products_product_secimage" class="products_product_secimage">
<span<?php echo $products->product_secimage->ViewAttributes() ?>>
<?php echo ew_GetFileViewTag($products->product_secimage, $products->product_secimage->ListViewValue()) ?>
</span>
</span>
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$products_list->ListOptions->Render("body", "right", $products_list->RowCnt);
?>
	</tr>
<?php if ($products->RowType == EW_ROWTYPE_ADD || $products->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fproductslist.UpdateOpts(<?php echo $products_list->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($products->CurrentAction <> "gridadd")
		if (!$products_list->Recordset->EOF) $products_list->Recordset->MoveNext();
}
?>
<?php
	if ($products->CurrentAction == "gridadd" || $products->CurrentAction == "gridedit") {
		$products_list->RowIndex = '$rowindex$';
		$products_list->LoadDefaultValues();

		// Set row properties
		$products->ResetAttrs();
		$products->RowAttrs = array_merge($products->RowAttrs, array('data-rowindex'=>$products_list->RowIndex, 'id'=>'r0_products', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($products->RowAttrs["class"], "ewTemplate");
		$products->RowType = EW_ROWTYPE_ADD;

		// Render row
		$products_list->RenderRow();

		// Render list options
		$products_list->RenderListOptions();
		$products_list->StartRowCnt = 0;
?>
	<tr<?php echo $products->RowAttributes() ?>>
<?php

// Render list options (body, left)
$products_list->ListOptions->Render("body", "left", $products_list->RowIndex);
?>
	<?php if ($products->product_id->Visible) { // product_id ?>
		<td data-name="product_id">
<input type="hidden" data-table="products" data-field="x_product_id" name="o<?php echo $products_list->RowIndex ?>_product_id" id="o<?php echo $products_list->RowIndex ?>_product_id" value="<?php echo ew_HtmlEncode($products->product_id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($products->category_id->Visible) { // category_id ?>
		<td data-name="category_id">
<span id="el$rowindex$_products_category_id" class="form-group products_category_id">
<?php $products->category_id->EditAttrs["onchange"] = "ew_UpdateOpt.call(this); " . @$products->category_id->EditAttrs["onchange"]; ?>
<select data-table="products" data-field="x_category_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($products->category_id->DisplayValueSeparator) ? json_encode($products->category_id->DisplayValueSeparator) : $products->category_id->DisplayValueSeparator) ?>" id="x<?php echo $products_list->RowIndex ?>_category_id" name="x<?php echo $products_list->RowIndex ?>_category_id"<?php echo $products->category_id->EditAttributes() ?>>
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
<input type="hidden" name="s_x<?php echo $products_list->RowIndex ?>_category_id" id="s_x<?php echo $products_list->RowIndex ?>_category_id" value="<?php echo $products->category_id->LookupFilterQuery() ?>">
</span>
<input type="hidden" data-table="products" data-field="x_category_id" name="o<?php echo $products_list->RowIndex ?>_category_id" id="o<?php echo $products_list->RowIndex ?>_category_id" value="<?php echo ew_HtmlEncode($products->category_id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($products->scat_id->Visible) { // scat_id ?>
		<td data-name="scat_id">
<?php if ($products->scat_id->getSessionValue() <> "") { ?>
<span id="el$rowindex$_products_scat_id" class="form-group products_scat_id">
<span<?php echo $products->scat_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $products->scat_id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $products_list->RowIndex ?>_scat_id" name="x<?php echo $products_list->RowIndex ?>_scat_id" value="<?php echo ew_HtmlEncode($products->scat_id->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_products_scat_id" class="form-group products_scat_id">
<select data-table="products" data-field="x_scat_id" data-value-separator="<?php echo ew_HtmlEncode(is_array($products->scat_id->DisplayValueSeparator) ? json_encode($products->scat_id->DisplayValueSeparator) : $products->scat_id->DisplayValueSeparator) ?>" id="x<?php echo $products_list->RowIndex ?>_scat_id" name="x<?php echo $products_list->RowIndex ?>_scat_id"<?php echo $products->scat_id->EditAttributes() ?>>
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
<input type="hidden" name="s_x<?php echo $products_list->RowIndex ?>_scat_id" id="s_x<?php echo $products_list->RowIndex ?>_scat_id" value="<?php echo $products->scat_id->LookupFilterQuery() ?>">
</span>
<?php } ?>
<input type="hidden" data-table="products" data-field="x_scat_id" name="o<?php echo $products_list->RowIndex ?>_scat_id" id="o<?php echo $products_list->RowIndex ?>_scat_id" value="<?php echo ew_HtmlEncode($products->scat_id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($products->product_name->Visible) { // product_name ?>
		<td data-name="product_name">
<span id="el$rowindex$_products_product_name" class="form-group products_product_name">
<input type="text" data-table="products" data-field="x_product_name" name="x<?php echo $products_list->RowIndex ?>_product_name" id="x<?php echo $products_list->RowIndex ?>_product_name" placeholder="<?php echo ew_HtmlEncode($products->product_name->getPlaceHolder()) ?>" value="<?php echo $products->product_name->EditValue ?>"<?php echo $products->product_name->EditAttributes() ?>>
</span>
<input type="hidden" data-table="products" data-field="x_product_name" name="o<?php echo $products_list->RowIndex ?>_product_name" id="o<?php echo $products_list->RowIndex ?>_product_name" value="<?php echo ew_HtmlEncode($products->product_name->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($products->product_image->Visible) { // product_image ?>
		<td data-name="product_image">
<span id="el$rowindex$_products_product_image" class="form-group products_product_image">
<div id="fd_x<?php echo $products_list->RowIndex ?>_product_image">
<span title="<?php echo $products->product_image->FldTitle() ? $products->product_image->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($products->product_image->ReadOnly || $products->product_image->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="products" data-field="x_product_image" name="x<?php echo $products_list->RowIndex ?>_product_image" id="x<?php echo $products_list->RowIndex ?>_product_image"<?php echo $products->product_image->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x<?php echo $products_list->RowIndex ?>_product_image" id= "fn_x<?php echo $products_list->RowIndex ?>_product_image" value="<?php echo $products->product_image->Upload->FileName ?>">
<input type="hidden" name="fa_x<?php echo $products_list->RowIndex ?>_product_image" id= "fa_x<?php echo $products_list->RowIndex ?>_product_image" value="0">
<input type="hidden" name="fs_x<?php echo $products_list->RowIndex ?>_product_image" id= "fs_x<?php echo $products_list->RowIndex ?>_product_image" value="200">
<input type="hidden" name="fx_x<?php echo $products_list->RowIndex ?>_product_image" id= "fx_x<?php echo $products_list->RowIndex ?>_product_image" value="<?php echo $products->product_image->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?php echo $products_list->RowIndex ?>_product_image" id= "fm_x<?php echo $products_list->RowIndex ?>_product_image" value="<?php echo $products->product_image->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?php echo $products_list->RowIndex ?>_product_image" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<input type="hidden" data-table="products" data-field="x_product_image" name="o<?php echo $products_list->RowIndex ?>_product_image" id="o<?php echo $products_list->RowIndex ?>_product_image" value="<?php echo ew_HtmlEncode($products->product_image->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($products->product_secimage->Visible) { // product_secimage ?>
		<td data-name="product_secimage">
<span id="el$rowindex$_products_product_secimage" class="form-group products_product_secimage">
<div id="fd_x<?php echo $products_list->RowIndex ?>_product_secimage">
<span title="<?php echo $products->product_secimage->FldTitle() ? $products->product_secimage->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($products->product_secimage->ReadOnly || $products->product_secimage->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="products" data-field="x_product_secimage" name="x<?php echo $products_list->RowIndex ?>_product_secimage" id="x<?php echo $products_list->RowIndex ?>_product_secimage"<?php echo $products->product_secimage->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x<?php echo $products_list->RowIndex ?>_product_secimage" id= "fn_x<?php echo $products_list->RowIndex ?>_product_secimage" value="<?php echo $products->product_secimage->Upload->FileName ?>">
<input type="hidden" name="fa_x<?php echo $products_list->RowIndex ?>_product_secimage" id= "fa_x<?php echo $products_list->RowIndex ?>_product_secimage" value="0">
<input type="hidden" name="fs_x<?php echo $products_list->RowIndex ?>_product_secimage" id= "fs_x<?php echo $products_list->RowIndex ?>_product_secimage" value="200">
<input type="hidden" name="fx_x<?php echo $products_list->RowIndex ?>_product_secimage" id= "fx_x<?php echo $products_list->RowIndex ?>_product_secimage" value="<?php echo $products->product_secimage->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?php echo $products_list->RowIndex ?>_product_secimage" id= "fm_x<?php echo $products_list->RowIndex ?>_product_secimage" value="<?php echo $products->product_secimage->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?php echo $products_list->RowIndex ?>_product_secimage" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<input type="hidden" data-table="products" data-field="x_product_secimage" name="o<?php echo $products_list->RowIndex ?>_product_secimage" id="o<?php echo $products_list->RowIndex ?>_product_secimage" value="<?php echo ew_HtmlEncode($products->product_secimage->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$products_list->ListOptions->Render("body", "right", $products_list->RowCnt);
?>
<script type="text/javascript">
fproductslist.UpdateOpts(<?php echo $products_list->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($products->CurrentAction == "gridadd") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $products_list->FormKeyCountName ?>" id="<?php echo $products_list->FormKeyCountName ?>" value="<?php echo $products_list->KeyCount ?>">
<?php echo $products_list->MultiSelectKey ?>
<?php } ?>
<?php if ($products->CurrentAction == "gridedit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $products_list->FormKeyCountName ?>" id="<?php echo $products_list->FormKeyCountName ?>" value="<?php echo $products_list->KeyCount ?>">
<?php echo $products_list->MultiSelectKey ?>
<?php } ?>
<?php if ($products->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($products_list->Recordset)
	$products_list->Recordset->Close();
?>
<div class="panel-footer ewGridLowerPanel">
<?php if ($products->CurrentAction <> "gridadd" && $products->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($products_list->Pager)) $products_list->Pager = new cPrevNextPager($products_list->StartRec, $products_list->DisplayRecs, $products_list->TotalRecs) ?>
<?php if ($products_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($products_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $products_list->PageUrl() ?>start=<?php echo $products_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($products_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $products_list->PageUrl() ?>start=<?php echo $products_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $products_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($products_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $products_list->PageUrl() ?>start=<?php echo $products_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($products_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $products_list->PageUrl() ?>start=<?php echo $products_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $products_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $products_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $products_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $products_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($products_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
</div>
<?php } ?>
<?php if ($products_list->TotalRecs == 0 && $products->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($products_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<script type="text/javascript">
fproductslistsrch.Init();
fproductslistsrch.FilterList = <?php echo $products_list->GetFilterList() ?>;
fproductslist.Init();
</script>
<?php
$products_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$products_list->Page_Terminate();
?>
