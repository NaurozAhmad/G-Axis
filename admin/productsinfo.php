<?php

// Global variable for table object
$products = NULL;

//
// Table class for products
//
class cproducts extends cTable {
	var $product_id;
	var $category_id;
	var $scat_id;
	var $product_name;
	var $product_image;
	var $product_secimage;
	var $product_description;
	var $feature_ledtype;
	var $feature_power;
	var $feature_lumen;
	var $feature_viewangle;
	var $feature_cri;
	var $feature_iprating;
	var $feature_colortemp;
	var $feature_body;
	var $feature_cutoutsize;
	var $feature_colors;
	var $feature_dimmable;
	var $feature_warranty;
	var $feature_application;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'products';
		$this->TableName = 'products';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`products`";
		$this->DBID = 'DB';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->ExportExcelPageOrientation = ""; // Page orientation (PHPExcel only)
		$this->ExportExcelPageSize = ""; // Page size (PHPExcel only)
		$this->DetailAdd = TRUE; // Allow detail add
		$this->DetailEdit = TRUE; // Allow detail edit
		$this->DetailView = TRUE; // Allow detail view
		$this->ShowMultipleDetails = FALSE; // Show multiple details
		$this->GridAddRowCount = 5;
		$this->AllowAddDeleteRow = ew_AllowAddDeleteRow(); // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// product_id
		$this->product_id = new cField('products', 'products', 'x_product_id', 'product_id', '`product_id`', '`product_id`', 3, -1, FALSE, '`product_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->product_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['product_id'] = &$this->product_id;

		// category_id
		$this->category_id = new cField('products', 'products', 'x_category_id', 'category_id', '`category_id`', '`category_id`', 3, -1, FALSE, '`category_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->category_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['category_id'] = &$this->category_id;

		// scat_id
		$this->scat_id = new cField('products', 'products', 'x_scat_id', 'scat_id', '`scat_id`', '`scat_id`', 3, -1, FALSE, '`scat_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->scat_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['scat_id'] = &$this->scat_id;

		// product_name
		$this->product_name = new cField('products', 'products', 'x_product_name', 'product_name', '`product_name`', '`product_name`', 201, -1, FALSE, '`product_name`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['product_name'] = &$this->product_name;

		// product_image
		$this->product_image = new cField('products', 'products', 'x_product_image', 'product_image', '`product_image`', '`product_image`', 200, -1, TRUE, '`product_image`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'FILE');
		$this->fields['product_image'] = &$this->product_image;

		// product_secimage
		$this->product_secimage = new cField('products', 'products', 'x_product_secimage', 'product_secimage', '`product_secimage`', '`product_secimage`', 200, -1, TRUE, '`product_secimage`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'FILE');
		$this->fields['product_secimage'] = &$this->product_secimage;

		// product_description
		$this->product_description = new cField('products', 'products', 'x_product_description', 'product_description', '`product_description`', '`product_description`', 201, -1, FALSE, '`product_description`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->fields['product_description'] = &$this->product_description;

		// feature_ledtype
		$this->feature_ledtype = new cField('products', 'products', 'x_feature_ledtype', 'feature_ledtype', '`feature_ledtype`', '`feature_ledtype`', 200, -1, FALSE, '`feature_ledtype`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['feature_ledtype'] = &$this->feature_ledtype;

		// feature_power
		$this->feature_power = new cField('products', 'products', 'x_feature_power', 'feature_power', '`feature_power`', '`feature_power`', 200, -1, FALSE, '`feature_power`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['feature_power'] = &$this->feature_power;

		// feature_lumen
		$this->feature_lumen = new cField('products', 'products', 'x_feature_lumen', 'feature_lumen', '`feature_lumen`', '`feature_lumen`', 200, -1, FALSE, '`feature_lumen`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['feature_lumen'] = &$this->feature_lumen;

		// feature_viewangle
		$this->feature_viewangle = new cField('products', 'products', 'x_feature_viewangle', 'feature_viewangle', '`feature_viewangle`', '`feature_viewangle`', 200, -1, FALSE, '`feature_viewangle`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['feature_viewangle'] = &$this->feature_viewangle;

		// feature_cri
		$this->feature_cri = new cField('products', 'products', 'x_feature_cri', 'feature_cri', '`feature_cri`', '`feature_cri`', 200, -1, FALSE, '`feature_cri`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['feature_cri'] = &$this->feature_cri;

		// feature_iprating
		$this->feature_iprating = new cField('products', 'products', 'x_feature_iprating', 'feature_iprating', '`feature_iprating`', '`feature_iprating`', 200, -1, FALSE, '`feature_iprating`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['feature_iprating'] = &$this->feature_iprating;

		// feature_colortemp
		$this->feature_colortemp = new cField('products', 'products', 'x_feature_colortemp', 'feature_colortemp', '`feature_colortemp`', '`feature_colortemp`', 200, -1, FALSE, '`feature_colortemp`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['feature_colortemp'] = &$this->feature_colortemp;

		// feature_body
		$this->feature_body = new cField('products', 'products', 'x_feature_body', 'feature_body', '`feature_body`', '`feature_body`', 200, -1, FALSE, '`feature_body`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['feature_body'] = &$this->feature_body;

		// feature_cutoutsize
		$this->feature_cutoutsize = new cField('products', 'products', 'x_feature_cutoutsize', 'feature_cutoutsize', '`feature_cutoutsize`', '`feature_cutoutsize`', 200, -1, FALSE, '`feature_cutoutsize`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['feature_cutoutsize'] = &$this->feature_cutoutsize;

		// feature_colors
		$this->feature_colors = new cField('products', 'products', 'x_feature_colors', 'feature_colors', '`feature_colors`', '`feature_colors`', 201, -1, FALSE, '`feature_colors`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->fields['feature_colors'] = &$this->feature_colors;

		// feature_dimmable
		$this->feature_dimmable = new cField('products', 'products', 'x_feature_dimmable', 'feature_dimmable', '`feature_dimmable`', '`feature_dimmable`', 200, -1, FALSE, '`feature_dimmable`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['feature_dimmable'] = &$this->feature_dimmable;

		// feature_warranty
		$this->feature_warranty = new cField('products', 'products', 'x_feature_warranty', 'feature_warranty', '`feature_warranty`', '`feature_warranty`', 200, -1, FALSE, '`feature_warranty`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['feature_warranty'] = &$this->feature_warranty;

		// feature_application
		$this->feature_application = new cField('products', 'products', 'x_feature_application', 'feature_application', '`feature_application`', '`feature_application`', 201, -1, FALSE, '`feature_application`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->fields['feature_application'] = &$this->feature_application;
	}

	// Single column sort
	function UpdateSort(&$ofld) {
		if ($this->CurrentOrder == $ofld->FldName) {
			$sSortField = $ofld->FldExpression;
			$sLastSort = $ofld->getSort();
			if ($this->CurrentOrderType == "ASC" || $this->CurrentOrderType == "DESC") {
				$sThisSort = $this->CurrentOrderType;
			} else {
				$sThisSort = ($sLastSort == "ASC") ? "DESC" : "ASC";
			}
			$ofld->setSort($sThisSort);
			$this->setSessionOrderBy($sSortField . " " . $sThisSort); // Save to Session
		} else {
			$ofld->setSort("");
		}
	}

	// Current master table name
	function getCurrentMasterTable() {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_MASTER_TABLE];
	}

	function setCurrentMasterTable($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_MASTER_TABLE] = $v;
	}

	// Session master WHERE clause
	function GetMasterFilter() {

		// Master filter
		$sMasterFilter = "";
		if ($this->getCurrentMasterTable() == "sub_category") {
			if ($this->scat_id->getSessionValue() <> "")
				$sMasterFilter .= "`scat_id`=" . ew_QuotedValue($this->scat_id->getSessionValue(), EW_DATATYPE_NUMBER, "DB");
			else
				return "";
		}
		return $sMasterFilter;
	}

	// Session detail WHERE clause
	function GetDetailFilter() {

		// Detail filter
		$sDetailFilter = "";
		if ($this->getCurrentMasterTable() == "sub_category") {
			if ($this->scat_id->getSessionValue() <> "")
				$sDetailFilter .= "`scat_id`=" . ew_QuotedValue($this->scat_id->getSessionValue(), EW_DATATYPE_NUMBER, "DB");
			else
				return "";
		}
		return $sDetailFilter;
	}

	// Master filter
	function SqlMasterFilter_sub_category() {
		return "`scat_id`=@scat_id@";
	}

	// Detail filter
	function SqlDetailFilter_sub_category() {
		return "`scat_id`=@scat_id@";
	}

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`products`";
	}

	function SqlFrom() { // For backward compatibility
    	return $this->getSqlFrom();
	}

	function setSqlFrom($v) {
    	$this->_SqlFrom = $v;
	}
	var $_SqlSelect = "";

	function getSqlSelect() { // Select
		return ($this->_SqlSelect <> "") ? $this->_SqlSelect : "SELECT * FROM " . $this->getSqlFrom();
	}

	function SqlSelect() { // For backward compatibility
    	return $this->getSqlSelect();
	}

	function setSqlSelect($v) {
    	$this->_SqlSelect = $v;
	}
	var $_SqlWhere = "";

	function getSqlWhere() { // Where
		$sWhere = ($this->_SqlWhere <> "") ? $this->_SqlWhere : "";
		$this->TableFilter = "";
		ew_AddFilter($sWhere, $this->TableFilter);
		return $sWhere;
	}

	function SqlWhere() { // For backward compatibility
    	return $this->getSqlWhere();
	}

	function setSqlWhere($v) {
    	$this->_SqlWhere = $v;
	}
	var $_SqlGroupBy = "";

	function getSqlGroupBy() { // Group By
		return ($this->_SqlGroupBy <> "") ? $this->_SqlGroupBy : "";
	}

	function SqlGroupBy() { // For backward compatibility
    	return $this->getSqlGroupBy();
	}

	function setSqlGroupBy($v) {
    	$this->_SqlGroupBy = $v;
	}
	var $_SqlHaving = "";

	function getSqlHaving() { // Having
		return ($this->_SqlHaving <> "") ? $this->_SqlHaving : "";
	}

	function SqlHaving() { // For backward compatibility
    	return $this->getSqlHaving();
	}

	function setSqlHaving($v) {
    	$this->_SqlHaving = $v;
	}
	var $_SqlOrderBy = "";

	function getSqlOrderBy() { // Order By
		return ($this->_SqlOrderBy <> "") ? $this->_SqlOrderBy : "";
	}

	function SqlOrderBy() { // For backward compatibility
    	return $this->getSqlOrderBy();
	}

	function setSqlOrderBy($v) {
    	$this->_SqlOrderBy = $v;
	}

	// Apply User ID filters
	function ApplyUserIDFilters($sFilter) {
		return $sFilter;
	}

	// Check if User ID security allows view all
	function UserIDAllow($id = "") {
		$allow = EW_USER_ID_ALLOW;
		switch ($id) {
			case "add":
			case "copy":
			case "gridadd":
			case "register":
			case "addopt":
				return (($allow & 1) == 1);
			case "edit":
			case "gridedit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return (($allow & 4) == 4);
			case "delete":
				return (($allow & 2) == 2);
			case "view":
				return (($allow & 32) == 32);
			case "search":
				return (($allow & 64) == 64);
			default:
				return (($allow & 8) == 8);
		}
	}

	// Get SQL
	function GetSQL($where, $orderby) {
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$where, $orderby);
	}

	// Table SQL
	function SQL() {
		$sFilter = $this->CurrentFilter;
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$sFilter, $sSort);
	}

	// Table SQL with List page filter
	function SelectSQL() {
		$sFilter = $this->getSessionWhere();
		ew_AddFilter($sFilter, $this->CurrentFilter);
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$this->Recordset_Selecting($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(), $this->getSqlGroupBy(),
			$this->getSqlHaving(), $this->getSqlOrderBy(), $sFilter, $sSort);
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->getSqlOrderBy(), "", $sSort);
	}

	// Try to get record count
	function TryGetRecordCount($sSql) {
		$cnt = -1;
		if (($this->TableType == 'TABLE' || $this->TableType == 'VIEW' || $this->TableType == 'LINKTABLE') && preg_match("/^SELECT \* FROM/i", $sSql)) {
			$sSql = "SELECT COUNT(*) FROM" . preg_replace('/^SELECT\s([\s\S]+)?\*\sFROM/i', "", $sSql);
			$sOrderBy = $this->GetOrderBy();
			if (substr($sSql, strlen($sOrderBy) * -1) == $sOrderBy)
				$sSql = substr($sSql, 0, strlen($sSql) - strlen($sOrderBy)); // Remove ORDER BY clause
		} else {
			$sSql = "SELECT COUNT(*) FROM (" . $sSql . ") EW_COUNT_TABLE";
		}
		$conn = &$this->Connection();
		if ($rs = $conn->Execute($sSql)) {
			if (!$rs->EOF && $rs->FieldCount() > 0) {
				$cnt = $rs->fields[0];
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// Get record count based on filter (for detail record count in master table pages)
	function LoadRecordCount($sFilter) {
		$origFilter = $this->CurrentFilter;
		$this->CurrentFilter = $sFilter;
		$this->Recordset_Selecting($this->CurrentFilter);

		//$sSql = $this->SQL();
		$sSql = $this->GetSQL($this->CurrentFilter, "");
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $this->LoadRs($this->CurrentFilter)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Get record count (for current List page)
	function SelectRecordCount() {
		$sSql = $this->SelectSQL();
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			$conn = &$this->Connection();
			if ($rs = $conn->Execute($sSql)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// INSERT statement
	function InsertSQL(&$rs) {
		$names = "";
		$values = "";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			$names .= $this->fields[$name]->FldExpression . ",";
			$values .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		while (substr($names, -1) == ",")
			$names = substr($names, 0, -1);
		while (substr($values, -1) == ",")
			$values = substr($values, 0, -1);
		return "INSERT INTO " . $this->UpdateTable . " ($names) VALUES ($values)";
	}

	// Insert
	function Insert(&$rs) {
		$conn = &$this->Connection();
		return $conn->Execute($this->InsertSQL($rs));
	}

	// UPDATE statement
	function UpdateSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "UPDATE " . $this->UpdateTable . " SET ";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			$sql .= $this->fields[$name]->FldExpression . "=";
			$sql .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		while (substr($sql, -1) == ",")
			$sql = substr($sql, 0, -1);
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		ew_AddFilter($filter, $where);
		if ($filter <> "")	$sql .= " WHERE " . $filter;
		return $sql;
	}

	// Update
	function Update(&$rs, $where = "", $rsold = NULL, $curfilter = TRUE) {
		$conn = &$this->Connection();
		return $conn->Execute($this->UpdateSQL($rs, $where, $curfilter));
	}

	// DELETE statement
	function DeleteSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "DELETE FROM " . $this->UpdateTable . " WHERE ";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		if ($rs) {
			if (array_key_exists('product_id', $rs))
				ew_AddFilter($where, ew_QuotedName('product_id', $this->DBID) . '=' . ew_QuotedValue($rs['product_id'], $this->product_id->FldDataType, $this->DBID));
		}
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		ew_AddFilter($filter, $where);
		if ($filter <> "")
			$sql .= $filter;
		else
			$sql .= "0=1"; // Avoid delete
		return $sql;
	}

	// Delete
	function Delete(&$rs, $where = "", $curfilter = TRUE) {
		$conn = &$this->Connection();
		return $conn->Execute($this->DeleteSQL($rs, $where, $curfilter));
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "`product_id` = @product_id@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->product_id->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@product_id@", ew_AdjustSql($this->product_id->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
		return $sKeyFilter;
	}

	// Return page URL
	function getReturnUrl() {
		$name = EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL;

		// Get referer URL automatically
		if (ew_ServerVar("HTTP_REFERER") <> "" && ew_ReferPage() <> ew_CurrentPage() && ew_ReferPage() <> "login.php") // Referer not same page or login page
			$_SESSION[$name] = ew_ServerVar("HTTP_REFERER"); // Save to Session
		if (@$_SESSION[$name] <> "") {
			return $_SESSION[$name];
		} else {
			return "productslist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "productslist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("productsview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("productsview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			return "productsadd.php?" . $this->UrlParm($parm);
		else
			return "productsadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("productsedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("productsadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("productsdelete.php", $this->UrlParm());
	}

	function KeyToJson() {
		$json = "";
		$json .= "product_id:" . ew_VarToJson($this->product_id->CurrentValue, "number", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->product_id->CurrentValue)) {
			$sUrl .= "product_id=" . urlencode($this->product_id->CurrentValue);
		} else {
			return "javascript:ew_Alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		return $sUrl;
	}

	// Sort URL
	function SortUrl(&$fld) {
		if ($this->CurrentAction <> "" || $this->Export <> "" ||
			in_array($fld->FldType, array(128, 204, 205))) { // Unsortable data type
				return "";
		} elseif ($fld->Sortable) {
			$sUrlParm = $this->UrlParm("order=" . urlencode($fld->FldName) . "&amp;ordertype=" . $fld->ReverseSort());
			return ew_CurrentPage() . "?" . $sUrlParm;
		} else {
			return "";
		}
	}

	// Get record keys from $_POST/$_GET/$_SESSION
	function GetRecordKeys() {
		global $EW_COMPOSITE_KEY_SEPARATOR;
		$arKeys = array();
		$arKey = array();
		if (isset($_POST["key_m"])) {
			$arKeys = ew_StripSlashes($_POST["key_m"]);
			$cnt = count($arKeys);
		} elseif (isset($_GET["key_m"])) {
			$arKeys = ew_StripSlashes($_GET["key_m"]);
			$cnt = count($arKeys);
		} elseif (!empty($_GET) || !empty($_POST)) {
			$isPost = ew_IsHttpPost();
			$arKeys[] = $isPost ? ew_StripSlashes(@$_POST["product_id"]) : ew_StripSlashes(@$_GET["product_id"]); // product_id

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		foreach ($arKeys as $key) {
			if (!is_numeric($key))
				continue;
			$ar[] = $key;
		}
		return $ar;
	}

	// Get key filter
	function GetKeyFilter() {
		$arKeys = $this->GetRecordKeys();
		$sKeyFilter = "";
		foreach ($arKeys as $key) {
			if ($sKeyFilter <> "") $sKeyFilter .= " OR ";
			$this->product_id->CurrentValue = $key;
			$sKeyFilter .= "(" . $this->KeyFilter() . ")";
		}
		return $sKeyFilter;
	}

	// Load rows based on filter
	function &LoadRs($sFilter) {

		// Set up filter (SQL WHERE clause) and get return SQL
		//$this->CurrentFilter = $sFilter;
		//$sSql = $this->SQL();

		$sSql = $this->GetSQL($sFilter, "");
		$conn = &$this->Connection();
		$rs = $conn->Execute($sSql);
		return $rs;
	}

	// Load row values from recordset
	function LoadListRowValues(&$rs) {
		$this->product_id->setDbValue($rs->fields('product_id'));
		$this->category_id->setDbValue($rs->fields('category_id'));
		$this->scat_id->setDbValue($rs->fields('scat_id'));
		$this->product_name->setDbValue($rs->fields('product_name'));
		$this->product_image->Upload->DbValue = $rs->fields('product_image');
		$this->product_secimage->Upload->DbValue = $rs->fields('product_secimage');
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

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
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

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// product_id
		$this->product_id->EditAttrs["class"] = "form-control";
		$this->product_id->EditCustomAttributes = "";
		$this->product_id->EditValue = $this->product_id->CurrentValue;
		$this->product_id->ViewCustomAttributes = "";

		// category_id
		$this->category_id->EditAttrs["class"] = "form-control";
		$this->category_id->EditCustomAttributes = "";

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
		}

		// product_name
		$this->product_name->EditAttrs["class"] = "form-control";
		$this->product_name->EditCustomAttributes = "";
		$this->product_name->EditValue = $this->product_name->CurrentValue;
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

		// product_description
		$this->product_description->EditAttrs["class"] = "form-control";
		$this->product_description->EditCustomAttributes = "";
		$this->product_description->EditValue = $this->product_description->CurrentValue;
		$this->product_description->PlaceHolder = ew_RemoveHtml($this->product_description->FldCaption());

		// feature_ledtype
		$this->feature_ledtype->EditAttrs["class"] = "form-control";
		$this->feature_ledtype->EditCustomAttributes = "";
		$this->feature_ledtype->EditValue = $this->feature_ledtype->CurrentValue;
		$this->feature_ledtype->PlaceHolder = ew_RemoveHtml($this->feature_ledtype->FldCaption());

		// feature_power
		$this->feature_power->EditAttrs["class"] = "form-control";
		$this->feature_power->EditCustomAttributes = "";
		$this->feature_power->EditValue = $this->feature_power->CurrentValue;
		$this->feature_power->PlaceHolder = ew_RemoveHtml($this->feature_power->FldCaption());

		// feature_lumen
		$this->feature_lumen->EditAttrs["class"] = "form-control";
		$this->feature_lumen->EditCustomAttributes = "";
		$this->feature_lumen->EditValue = $this->feature_lumen->CurrentValue;
		$this->feature_lumen->PlaceHolder = ew_RemoveHtml($this->feature_lumen->FldCaption());

		// feature_viewangle
		$this->feature_viewangle->EditAttrs["class"] = "form-control";
		$this->feature_viewangle->EditCustomAttributes = "";
		$this->feature_viewangle->EditValue = $this->feature_viewangle->CurrentValue;
		$this->feature_viewangle->PlaceHolder = ew_RemoveHtml($this->feature_viewangle->FldCaption());

		// feature_cri
		$this->feature_cri->EditAttrs["class"] = "form-control";
		$this->feature_cri->EditCustomAttributes = "";
		$this->feature_cri->EditValue = $this->feature_cri->CurrentValue;
		$this->feature_cri->PlaceHolder = ew_RemoveHtml($this->feature_cri->FldCaption());

		// feature_iprating
		$this->feature_iprating->EditAttrs["class"] = "form-control";
		$this->feature_iprating->EditCustomAttributes = "";
		$this->feature_iprating->EditValue = $this->feature_iprating->CurrentValue;
		$this->feature_iprating->PlaceHolder = ew_RemoveHtml($this->feature_iprating->FldCaption());

		// feature_colortemp
		$this->feature_colortemp->EditAttrs["class"] = "form-control";
		$this->feature_colortemp->EditCustomAttributes = "";
		$this->feature_colortemp->EditValue = $this->feature_colortemp->CurrentValue;
		$this->feature_colortemp->PlaceHolder = ew_RemoveHtml($this->feature_colortemp->FldCaption());

		// feature_body
		$this->feature_body->EditAttrs["class"] = "form-control";
		$this->feature_body->EditCustomAttributes = "";
		$this->feature_body->EditValue = $this->feature_body->CurrentValue;
		$this->feature_body->PlaceHolder = ew_RemoveHtml($this->feature_body->FldCaption());

		// feature_cutoutsize
		$this->feature_cutoutsize->EditAttrs["class"] = "form-control";
		$this->feature_cutoutsize->EditCustomAttributes = "";
		$this->feature_cutoutsize->EditValue = $this->feature_cutoutsize->CurrentValue;
		$this->feature_cutoutsize->PlaceHolder = ew_RemoveHtml($this->feature_cutoutsize->FldCaption());

		// feature_colors
		$this->feature_colors->EditAttrs["class"] = "form-control";
		$this->feature_colors->EditCustomAttributes = "";
		$this->feature_colors->EditValue = $this->feature_colors->CurrentValue;
		$this->feature_colors->PlaceHolder = ew_RemoveHtml($this->feature_colors->FldCaption());

		// feature_dimmable
		$this->feature_dimmable->EditAttrs["class"] = "form-control";
		$this->feature_dimmable->EditCustomAttributes = "";
		$this->feature_dimmable->EditValue = $this->feature_dimmable->CurrentValue;
		$this->feature_dimmable->PlaceHolder = ew_RemoveHtml($this->feature_dimmable->FldCaption());

		// feature_warranty
		$this->feature_warranty->EditAttrs["class"] = "form-control";
		$this->feature_warranty->EditCustomAttributes = "";
		$this->feature_warranty->EditValue = $this->feature_warranty->CurrentValue;
		$this->feature_warranty->PlaceHolder = ew_RemoveHtml($this->feature_warranty->FldCaption());

		// feature_application
		$this->feature_application->EditAttrs["class"] = "form-control";
		$this->feature_application->EditCustomAttributes = "";
		$this->feature_application->EditValue = $this->feature_application->CurrentValue;
		$this->feature_application->PlaceHolder = ew_RemoveHtml($this->feature_application->FldCaption());

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Aggregate list row values
	function AggregateListRowValues() {
	}

	// Aggregate list row (for rendering)
	function AggregateListRow() {

		// Call Row Rendered event
		$this->Row_Rendered();
	}
	var $ExportDoc;

	// Export data in HTML/CSV/Word/Excel/Email/PDF format
	function ExportDocument(&$Doc, &$Recordset, $StartRec, $StopRec, $ExportPageType = "") {
		if (!$Recordset || !$Doc)
			return;
		if (!$Doc->ExportCustom) {

			// Write header
			$Doc->ExportTableHeader();
			if ($Doc->Horizontal) { // Horizontal format, write header
				$Doc->BeginExportRow();
				if ($ExportPageType == "view") {
					if ($this->category_id->Exportable) $Doc->ExportCaption($this->category_id);
					if ($this->scat_id->Exportable) $Doc->ExportCaption($this->scat_id);
					if ($this->product_name->Exportable) $Doc->ExportCaption($this->product_name);
					if ($this->product_image->Exportable) $Doc->ExportCaption($this->product_image);
					if ($this->product_secimage->Exportable) $Doc->ExportCaption($this->product_secimage);
					if ($this->product_description->Exportable) $Doc->ExportCaption($this->product_description);
					if ($this->feature_ledtype->Exportable) $Doc->ExportCaption($this->feature_ledtype);
					if ($this->feature_power->Exportable) $Doc->ExportCaption($this->feature_power);
					if ($this->feature_lumen->Exportable) $Doc->ExportCaption($this->feature_lumen);
					if ($this->feature_viewangle->Exportable) $Doc->ExportCaption($this->feature_viewangle);
					if ($this->feature_cri->Exportable) $Doc->ExportCaption($this->feature_cri);
					if ($this->feature_iprating->Exportable) $Doc->ExportCaption($this->feature_iprating);
					if ($this->feature_colortemp->Exportable) $Doc->ExportCaption($this->feature_colortemp);
					if ($this->feature_body->Exportable) $Doc->ExportCaption($this->feature_body);
					if ($this->feature_cutoutsize->Exportable) $Doc->ExportCaption($this->feature_cutoutsize);
					if ($this->feature_colors->Exportable) $Doc->ExportCaption($this->feature_colors);
					if ($this->feature_dimmable->Exportable) $Doc->ExportCaption($this->feature_dimmable);
					if ($this->feature_warranty->Exportable) $Doc->ExportCaption($this->feature_warranty);
					if ($this->feature_application->Exportable) $Doc->ExportCaption($this->feature_application);
				} else {
					if ($this->product_id->Exportable) $Doc->ExportCaption($this->product_id);
					if ($this->category_id->Exportable) $Doc->ExportCaption($this->category_id);
					if ($this->scat_id->Exportable) $Doc->ExportCaption($this->scat_id);
					if ($this->product_image->Exportable) $Doc->ExportCaption($this->product_image);
					if ($this->product_secimage->Exportable) $Doc->ExportCaption($this->product_secimage);
					if ($this->feature_ledtype->Exportable) $Doc->ExportCaption($this->feature_ledtype);
					if ($this->feature_power->Exportable) $Doc->ExportCaption($this->feature_power);
					if ($this->feature_lumen->Exportable) $Doc->ExportCaption($this->feature_lumen);
					if ($this->feature_viewangle->Exportable) $Doc->ExportCaption($this->feature_viewangle);
					if ($this->feature_cri->Exportable) $Doc->ExportCaption($this->feature_cri);
					if ($this->feature_iprating->Exportable) $Doc->ExportCaption($this->feature_iprating);
					if ($this->feature_colortemp->Exportable) $Doc->ExportCaption($this->feature_colortemp);
					if ($this->feature_body->Exportable) $Doc->ExportCaption($this->feature_body);
					if ($this->feature_cutoutsize->Exportable) $Doc->ExportCaption($this->feature_cutoutsize);
					if ($this->feature_dimmable->Exportable) $Doc->ExportCaption($this->feature_dimmable);
					if ($this->feature_warranty->Exportable) $Doc->ExportCaption($this->feature_warranty);
				}
				$Doc->EndExportRow();
			}
		}

		// Move to first record
		$RecCnt = $StartRec - 1;
		if (!$Recordset->EOF) {
			$Recordset->MoveFirst();
			if ($StartRec > 1)
				$Recordset->Move($StartRec - 1);
		}
		while (!$Recordset->EOF && $RecCnt < $StopRec) {
			$RecCnt++;
			if (intval($RecCnt) >= intval($StartRec)) {
				$RowCnt = intval($RecCnt) - intval($StartRec) + 1;

				// Page break
				if ($this->ExportPageBreakCount > 0) {
					if ($RowCnt > 1 && ($RowCnt - 1) % $this->ExportPageBreakCount == 0)
						$Doc->ExportPageBreak();
				}
				$this->LoadListRowValues($Recordset);

				// Render row
				$this->RowType = EW_ROWTYPE_VIEW; // Render view
				$this->ResetAttrs();
				$this->RenderListRow();
				if (!$Doc->ExportCustom) {
					$Doc->BeginExportRow($RowCnt); // Allow CSS styles if enabled
					if ($ExportPageType == "view") {
						if ($this->category_id->Exportable) $Doc->ExportField($this->category_id);
						if ($this->scat_id->Exportable) $Doc->ExportField($this->scat_id);
						if ($this->product_name->Exportable) $Doc->ExportField($this->product_name);
						if ($this->product_image->Exportable) $Doc->ExportField($this->product_image);
						if ($this->product_secimage->Exportable) $Doc->ExportField($this->product_secimage);
						if ($this->product_description->Exportable) $Doc->ExportField($this->product_description);
						if ($this->feature_ledtype->Exportable) $Doc->ExportField($this->feature_ledtype);
						if ($this->feature_power->Exportable) $Doc->ExportField($this->feature_power);
						if ($this->feature_lumen->Exportable) $Doc->ExportField($this->feature_lumen);
						if ($this->feature_viewangle->Exportable) $Doc->ExportField($this->feature_viewangle);
						if ($this->feature_cri->Exportable) $Doc->ExportField($this->feature_cri);
						if ($this->feature_iprating->Exportable) $Doc->ExportField($this->feature_iprating);
						if ($this->feature_colortemp->Exportable) $Doc->ExportField($this->feature_colortemp);
						if ($this->feature_body->Exportable) $Doc->ExportField($this->feature_body);
						if ($this->feature_cutoutsize->Exportable) $Doc->ExportField($this->feature_cutoutsize);
						if ($this->feature_colors->Exportable) $Doc->ExportField($this->feature_colors);
						if ($this->feature_dimmable->Exportable) $Doc->ExportField($this->feature_dimmable);
						if ($this->feature_warranty->Exportable) $Doc->ExportField($this->feature_warranty);
						if ($this->feature_application->Exportable) $Doc->ExportField($this->feature_application);
					} else {
						if ($this->product_id->Exportable) $Doc->ExportField($this->product_id);
						if ($this->category_id->Exportable) $Doc->ExportField($this->category_id);
						if ($this->scat_id->Exportable) $Doc->ExportField($this->scat_id);
						if ($this->product_image->Exportable) $Doc->ExportField($this->product_image);
						if ($this->product_secimage->Exportable) $Doc->ExportField($this->product_secimage);
						if ($this->feature_ledtype->Exportable) $Doc->ExportField($this->feature_ledtype);
						if ($this->feature_power->Exportable) $Doc->ExportField($this->feature_power);
						if ($this->feature_lumen->Exportable) $Doc->ExportField($this->feature_lumen);
						if ($this->feature_viewangle->Exportable) $Doc->ExportField($this->feature_viewangle);
						if ($this->feature_cri->Exportable) $Doc->ExportField($this->feature_cri);
						if ($this->feature_iprating->Exportable) $Doc->ExportField($this->feature_iprating);
						if ($this->feature_colortemp->Exportable) $Doc->ExportField($this->feature_colortemp);
						if ($this->feature_body->Exportable) $Doc->ExportField($this->feature_body);
						if ($this->feature_cutoutsize->Exportable) $Doc->ExportField($this->feature_cutoutsize);
						if ($this->feature_dimmable->Exportable) $Doc->ExportField($this->feature_dimmable);
						if ($this->feature_warranty->Exportable) $Doc->ExportField($this->feature_warranty);
					}
					$Doc->EndExportRow();
				}
			}

			// Call Row Export server event
			if ($Doc->ExportCustom)
				$this->Row_Export($Recordset->fields);
			$Recordset->MoveNext();
		}
		if (!$Doc->ExportCustom) {
			$Doc->ExportTableFooter();
		}
	}

	// Get auto fill value
	function GetAutoFill($id, $val) {
		$rsarr = array();
		$rowcnt = 0;

		// Output
		if (is_array($rsarr) && $rowcnt > 0) {
			$fldcnt = count($rsarr[0]);
			for ($i = 0; $i < $rowcnt; $i++) {
				for ($j = 0; $j < $fldcnt; $j++) {
					$str = strval($rsarr[$i][$j]);
					$str = ew_ConvertToUtf8($str);
					if (isset($post["keepCRLF"])) {
						$str = str_replace(array("\r", "\n"), array("\\r", "\\n"), $str);
					} else {
						$str = str_replace(array("\r", "\n"), array(" ", " "), $str);
					}
					$rsarr[$i][$j] = $str;
				}
			}
			return ew_ArrayToJson($rsarr);
		} else {
			return FALSE;
		}
	}

	// Table level events
	// Recordset Selecting event
	function Recordset_Selecting(&$filter) {

		// Enter your code here	
	}

	// Recordset Selected event
	function Recordset_Selected(&$rs) {

		//echo "Recordset Selected";
	}

	// Recordset Search Validated event
	function Recordset_SearchValidated() {

		// Example:
		//$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value

	}

	// Recordset Searching event
	function Recordset_Searching(&$filter) {

		// Enter your code here	
	}

	// Row_Selecting event
	function Row_Selecting(&$filter) {

		// Enter your code here	
	}

	// Row Selected event
	function Row_Selected(&$rs) {

		//echo "Row Selected";
	}

	// Row Inserting event
	function Row_Inserting($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Inserted event
	function Row_Inserted($rsold, &$rsnew) {

		//echo "Row Inserted"
	}

	// Row Updating event
	function Row_Updating($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Updated event
	function Row_Updated($rsold, &$rsnew) {

		//echo "Row Updated";
	}

	// Row Update Conflict event
	function Row_UpdateConflict($rsold, &$rsnew) {

		// Enter your code here
		// To ignore conflict, set return value to FALSE

		return TRUE;
	}

	// Grid Inserting event
	function Grid_Inserting() {

		// Enter your code here
		// To reject grid insert, set return value to FALSE

		return TRUE;
	}

	// Grid Inserted event
	function Grid_Inserted($rsnew) {

		//echo "Grid Inserted";
	}

	// Grid Updating event
	function Grid_Updating($rsold) {

		// Enter your code here
		// To reject grid update, set return value to FALSE

		return TRUE;
	}

	// Grid Updated event
	function Grid_Updated($rsold, $rsnew) {

		//echo "Grid Updated";
	}

	// Row Deleting event
	function Row_Deleting(&$rs) {

		// Enter your code here
		// To cancel, set return value to False

		return TRUE;
	}

	// Row Deleted event
	function Row_Deleted(&$rs) {

		//echo "Row Deleted";
	}

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
	}

	// Lookup Selecting event
	function Lookup_Selecting($fld, &$filter) {

		//var_dump($fld->FldName, $fld->LookupFilters, $filter); // Uncomment to view the filter
		// Enter your code here

	}

	// Row Rendering event
	function Row_Rendering() {

		// Enter your code here	
	}

	// Row Rendered event
	function Row_Rendered() {

		// To view properties of field class, use:
		//var_dump($this-><FieldName>); 

	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
