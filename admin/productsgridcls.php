<?php include_once "productsinfo.php" ?>
<?php

//
// Page class
//

$products_grid = NULL; // Initialize page object first

class cproducts_grid extends cproducts {

	// Page ID
	var $PageID = 'grid';

	// Project ID
	var $ProjectID = "{C3CE8554-8FA2-42A1-89B9-3DB1F25B77B3}";

	// Table name
	var $TableName = 'products';

	// Page object name
	var $PageObjName = 'products_grid';

	// Grid form hidden field names
	var $FormName = 'fproductsgrid';
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
		$this->FormActionName .= '_' . $this->FormName;
		$this->FormKeyName .= '_' . $this->FormName;
		$this->FormOldKeyName .= '_' . $this->FormName;
		$this->FormBlankRowName .= '_' . $this->FormName;
		$this->FormKeyCountName .= '_' . $this->FormName;
		$GLOBALS["Grid"] = &$this;
		$this->TokenTimeout = ew_SessionTimeoutTime();

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (products)
		if (!isset($GLOBALS["products"]) || get_class($GLOBALS["products"]) == "cproducts") {
			$GLOBALS["products"] = &$this;

//			$GLOBALS["MasterTable"] = &$GLOBALS["Table"];
//			if (!isset($GLOBALS["Table"])) $GLOBALS["Table"] = &$GLOBALS["products"];

		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'grid', TRUE);

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

		// Other options
		$this->OtherOptions['addedit'] = new cListOptions();
		$this->OtherOptions['addedit']->Tag = "div";
		$this->OtherOptions['addedit']->TagClassName = "ewAddEditOption";
	}

	// 
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

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
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $gsExportFile, $gTmpImages;

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

//		$GLOBALS["Table"] = &$GLOBALS["MasterTable"];
		unset($GLOBALS["Grid"]);
		if ($url == "")
			return;
		$this->Page_Redirecting($url);

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
	var $ShowOtherOptions = FALSE;
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

			// Handle reset command
			$this->ResetCmd();

			// Set up master detail parameters
			$this->SetUpMasterParms();

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

			// Show grid delete link for grid add / grid edit
			if ($this->AllowAddDeleteRow) {
				if ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
					$item = $this->ListOptions->GetItem("griddelete");
					if ($item) $item->Visible = TRUE;
				}
			}

			// Set up sorting order
			$this->SetUpSortOrder();
		}

		// Restore display records
		if ($this->getRecordsPerPage() <> "") {
			$this->DisplayRecs = $this->getRecordsPerPage(); // Restore from Session
		} else {
			$this->DisplayRecs = 20; // Load default
		}

		// Load Sorting Order
		$this->LoadSortOrder();

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

			// Get new recordset
			if ($rs = $conn->Execute($sSql)) {
				$rsnew = $rs->GetRows();
				$rs->Close();
			}

			// Call Grid_Updated event
			$this->Grid_Updated($rsold, $rsnew);
			$this->ClearInlineMode(); // Clear inline edit mode
		} else {
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
			if ($rowaction == "insert") {
				$this->RowOldKey = strval($objForm->GetValue($this->FormOldKeyName));
				$this->LoadOldRecord(); // Load old recordset
			}
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
			$this->ClearInlineMode(); // Clear grid add mode and return
			return TRUE;
		}
		if ($bGridInsert) {

			// Get new recordset
			$this->CurrentFilter = $sWrkFilter;
			$sSql = $this->SQL();
			if ($rs = $conn->Execute($sSql)) {
				$rsnew = $rs->GetRows();
				$rs->Close();
			}

			// Call Grid_Inserted event
			$this->Grid_Inserted($rsnew);
			$this->ClearInlineMode(); // Clear grid add mode
		} else {
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

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
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

		// Drop down button for ListOptions
		$this->ListOptions->UseImageAndText = TRUE;
		$this->ListOptions->UseDropDownButton = FALSE;
		$this->ListOptions->DropDownButtonPhrase = $Language->Phrase("ButtonListOptions");
		$this->ListOptions->UseButtonGroup = FALSE;
		if ($this->ListOptions->UseButtonGroup && ew_IsMobile())
			$this->ListOptions->UseDropDownButton = TRUE;
		$this->ListOptions->ButtonClass = "btn-sm"; // Class for button group
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
			if ($objForm->HasValue($this->FormOldKeyName))
				$this->RowOldKey = strval($objForm->GetValue($this->FormOldKeyName));
			if ($this->RowOldKey <> "")
				$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $OldKeyName . "\" id=\"" . $OldKeyName . "\" value=\"" . ew_HtmlEncode($this->RowOldKey) . "\">";
			if ($this->RowAction == "delete") {
				$rowkey = $objForm->GetValue($this->FormKeyName);
				$this->SetupKeyValues($rowkey);
			}
			if ($this->RowAction == "insert" && $this->CurrentAction == "F" && $this->EmptyRow())
				$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $BlankRowName . "\" id=\"" . $BlankRowName . "\" value=\"1\">";
		}

		// "delete"
		if ($this->AllowAddDeleteRow) {
			if ($this->CurrentMode == "add" || $this->CurrentMode == "copy" || $this->CurrentMode == "edit") {
				$option = &$this->ListOptions;
				$option->UseButtonGroup = TRUE; // Use button group for grid delete button
				$option->UseImageAndText = TRUE; // Use image and text for grid delete button
				$oListOpt = &$option->Items["griddelete"];
				$oListOpt->Body = "<a class=\"ewGridLink ewGridDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" onclick=\"return ew_DeleteGridRow(this, " . $this->RowIndex . ");\">" . $Language->Phrase("DeleteLink") . "</a>";
			}
		}
		if ($this->CurrentMode == "edit" && is_numeric($this->RowIndex)) {
			$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $KeyName . "\" id=\"" . $KeyName . "\" value=\"" . $this->product_id->CurrentValue . "\">";
		}
		$this->RenderListOptionsExt();
	}

	// Set record key
	function SetRecordKey(&$key, $rs) {
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs->fields('product_id');
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$option = &$this->OtherOptions["addedit"];
		$option->UseDropDownButton = FALSE;
		$option->DropDownButtonPhrase = $Language->Phrase("ButtonAddEdit");
		$option->UseButtonGroup = TRUE;
		$option->ButtonClass = "btn-sm"; // Class for button group
		$item = &$option->Add($option->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
	}

	// Render other options
	function RenderOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		if (($this->CurrentMode == "add" || $this->CurrentMode == "copy" || $this->CurrentMode == "edit") && $this->CurrentAction != "F") { // Check add/copy/edit mode
			if ($this->AllowAddDeleteRow) {
				$option = &$options["addedit"];
				$option->UseDropDownButton = FALSE;
				$option->UseImageAndText = TRUE;
				$item = &$option->Add("addblankrow");
				$item->Body = "<a class=\"ewAddEdit ewAddBlankRow\" title=\"" . ew_HtmlTitle($Language->Phrase("AddBlankRow")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("AddBlankRow")) . "\" href=\"javascript:void(0);\" onclick=\"ew_AddGridRow(this);\">" . $Language->Phrase("AddBlankRow") . "</a>";
				$item->Visible = TRUE;
				$this->ShowOtherOptions = $item->Visible;
			}
		}
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
		$this->product_image->Upload->Index = $this->RowIndex;
		$this->product_secimage->Upload->DbValue = NULL;
		$this->product_secimage->OldValue = $this->product_secimage->Upload->DbValue;
		$this->product_secimage->Upload->Index = $this->RowIndex;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$objForm->FormName = $this->FormName;
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
		$this->product_image->Upload->Index = $this->RowIndex;
		$this->product_secimage->Upload->DbValue = $rs->fields('product_secimage');
		$this->product_secimage->CurrentValue = $this->product_secimage->Upload->DbValue;
		$this->product_secimage->Upload->Index = $this->RowIndex;
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
		$arKeys[] = $this->RowOldKey;
		$cnt = count($arKeys);
		if ($cnt >= 1) {
			if (strval($arKeys[0]) <> "")
				$this->product_id->CurrentValue = strval($arKeys[0]); // product_id
			else
				$bValidKey = FALSE;
		} else {
			$bValidKey = FALSE;
		}

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

		// Set up foreign key field value from Session
			if ($this->getCurrentMasterTable() == "sub_category") {
				$this->scat_id->CurrentValue = $this->scat_id->getSessionValue();
			}
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

		// Hide foreign keys
		$sMasterTblVar = $this->getCurrentMasterTable();
		if ($sMasterTblVar == "sub_category") {
			$this->scat_id->Visible = FALSE;
			if ($GLOBALS["sub_category"]->EventCancelled) $this->EventCancelled = TRUE;
		}
		$this->DbMasterFilter = $this->GetMasterFilter(); // Get master filter
		$this->DbDetailFilter = $this->GetDetailFilter(); // Get detail filter
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
