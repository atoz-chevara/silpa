<?php

namespace PHPMaker2021\silpa;

use Doctrine\DBAL\ParameterType;

/**
 * Page class
 */
class ApbkList extends Apbk
{
    use MessagesTrait;

    // Page ID
    public $PageID = "list";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'apbk';

    // Page object name
    public $PageObjName = "ApbkList";

    // Rendering View
    public $RenderingView = false;

    // Grid form hidden field names
    public $FormName = "fapbklist";
    public $FormActionName = "k_action";
    public $FormBlankRowName = "k_blankrow";
    public $FormKeyCountName = "key_count";

    // Page URLs
    public $AddUrl;
    public $EditUrl;
    public $CopyUrl;
    public $DeleteUrl;
    public $ViewUrl;
    public $ListUrl;

    // Export URLs
    public $ExportPrintUrl;
    public $ExportHtmlUrl;
    public $ExportExcelUrl;
    public $ExportWordUrl;
    public $ExportXmlUrl;
    public $ExportCsvUrl;
    public $ExportPdfUrl;

    // Custom export
    public $ExportExcelCustom = false;
    public $ExportWordCustom = false;
    public $ExportPdfCustom = false;
    public $ExportEmailCustom = false;

    // Update URLs
    public $InlineAddUrl;
    public $InlineCopyUrl;
    public $InlineEditUrl;
    public $GridAddUrl;
    public $GridEditUrl;
    public $MultiDeleteUrl;
    public $MultiUpdateUrl;

    // Page headings
    public $Heading = "";
    public $Subheading = "";
    public $PageHeader;
    public $PageFooter;

    // Page terminated
    private $terminated = false;

    // Page heading
    public function pageHeading()
    {
        global $Language;
        if ($this->Heading != "") {
            return $this->Heading;
        }
        if (method_exists($this, "tableCaption")) {
            return $this->tableCaption();
        }
        return "";
    }

    // Page subheading
    public function pageSubheading()
    {
        global $Language;
        if ($this->Subheading != "") {
            return $this->Subheading;
        }
        if ($this->TableName) {
            return $Language->phrase($this->PageID);
        }
        return "";
    }

    // Page name
    public function pageName()
    {
        return CurrentPageName();
    }

    // Page URL
    public function pageUrl()
    {
        $url = ScriptName() . "?";
        if ($this->UseTokenInUrl) {
            $url .= "t=" . $this->TableVar . "&"; // Add page token
        }
        return $url;
    }

    // Show Page Header
    public function showPageHeader()
    {
        $header = $this->PageHeader;
        $this->pageDataRendering($header);
        if ($header != "") { // Header exists, display
            echo '<p id="ew-page-header">' . $header . '</p>';
        }
    }

    // Show Page Footer
    public function showPageFooter()
    {
        $footer = $this->PageFooter;
        $this->pageDataRendered($footer);
        if ($footer != "") { // Footer exists, display
            echo '<p id="ew-page-footer">' . $footer . '</p>';
        }
    }

    // Validate page request
    protected function isPageRequest()
    {
        global $CurrentForm;
        if ($this->UseTokenInUrl) {
            if ($CurrentForm) {
                return ($this->TableVar == $CurrentForm->getValue("t"));
            }
            if (Get("t") !== null) {
                return ($this->TableVar == Get("t"));
            }
        }
        return true;
    }

    // Constructor
    public function __construct()
    {
        global $Language, $DashboardReport, $DebugTimer;
        global $UserTable;

        // Initialize
        $GLOBALS["Page"] = &$this;

        // Language object
        $Language = Container("language");

        // Parent constuctor
        parent::__construct();

        // Table object (apbk)
        if (!isset($GLOBALS["apbk"]) || get_class($GLOBALS["apbk"]) == PROJECT_NAMESPACE . "apbk") {
            $GLOBALS["apbk"] = &$this;
        }

        // Page URL
        $pageUrl = $this->pageUrl();

        // Initialize URLs
        $this->ExportPrintUrl = $pageUrl . "export=print";
        $this->ExportExcelUrl = $pageUrl . "export=excel";
        $this->ExportWordUrl = $pageUrl . "export=word";
        $this->ExportPdfUrl = $pageUrl . "export=pdf";
        $this->ExportHtmlUrl = $pageUrl . "export=html";
        $this->ExportXmlUrl = $pageUrl . "export=xml";
        $this->ExportCsvUrl = $pageUrl . "export=csv";
        $this->AddUrl = "apbkadd";
        $this->InlineAddUrl = $pageUrl . "action=add";
        $this->GridAddUrl = $pageUrl . "action=gridadd";
        $this->GridEditUrl = $pageUrl . "action=gridedit";
        $this->MultiDeleteUrl = "apbkdelete";
        $this->MultiUpdateUrl = "apbkupdate";

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'apbk');
        }

        // Start timer
        $DebugTimer = Container("timer");

        // Debug message
        LoadDebugMessage();

        // Open connection
        $GLOBALS["Conn"] = $GLOBALS["Conn"] ?? $this->getConnection();

        // User table object
        $UserTable = Container("usertable");

        // List options
        $this->ListOptions = new ListOptions();
        $this->ListOptions->TableVar = $this->TableVar;

        // Export options
        $this->ExportOptions = new ListOptions("div");
        $this->ExportOptions->TagClassName = "ew-export-option";

        // Import options
        $this->ImportOptions = new ListOptions("div");
        $this->ImportOptions->TagClassName = "ew-import-option";

        // Other options
        if (!$this->OtherOptions) {
            $this->OtherOptions = new ListOptionsArray();
        }
        $this->OtherOptions["addedit"] = new ListOptions("div");
        $this->OtherOptions["addedit"]->TagClassName = "ew-add-edit-option";
        $this->OtherOptions["detail"] = new ListOptions("div");
        $this->OtherOptions["detail"]->TagClassName = "ew-detail-option";
        $this->OtherOptions["action"] = new ListOptions("div");
        $this->OtherOptions["action"]->TagClassName = "ew-action-option";

        // Filter options
        $this->FilterOptions = new ListOptions("div");
        $this->FilterOptions->TagClassName = "ew-filter-option fapbklistsrch";

        // List actions
        $this->ListActions = new ListActions();
    }

    // Get content from stream
    public function getContents($stream = null): string
    {
        global $Response;
        return is_object($Response) ? $Response->getBody() : ob_get_clean();
    }

    // Is lookup
    public function isLookup()
    {
        return SameText(Route(0), Config("API_LOOKUP_ACTION"));
    }

    // Is AutoFill
    public function isAutoFill()
    {
        return $this->isLookup() && SameText(Post("ajax"), "autofill");
    }

    // Is AutoSuggest
    public function isAutoSuggest()
    {
        return $this->isLookup() && SameText(Post("ajax"), "autosuggest");
    }

    // Is modal lookup
    public function isModalLookup()
    {
        return $this->isLookup() && SameText(Post("ajax"), "modal");
    }

    // Is terminated
    public function isTerminated()
    {
        return $this->terminated;
    }

    /**
     * Terminate page
     *
     * @param string $url URL for direction
     * @return void
     */
    public function terminate($url = "")
    {
        if ($this->terminated) {
            return;
        }
        global $ExportFileName, $TempImages, $DashboardReport, $Response;

        // Page is terminated
        $this->terminated = true;

         // Page Unload event
        if (method_exists($this, "pageUnload")) {
            $this->pageUnload();
        }

        // Global Page Unloaded event (in userfn*.php)
        Page_Unloaded();

        // Export
        if ($this->CustomExport && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, Config("EXPORT_CLASSES"))) {
            $content = $this->getContents();
            if ($ExportFileName == "") {
                $ExportFileName = $this->TableVar;
            }
            $class = PROJECT_NAMESPACE . Config("EXPORT_CLASSES." . $this->CustomExport);
            if (class_exists($class)) {
                $doc = new $class(Container("apbk"));
                $doc->Text = @$content;
                if ($this->isExport("email")) {
                    echo $this->exportEmail($doc->Text);
                } else {
                    $doc->export();
                }
                DeleteTempImages(); // Delete temp images
                return;
            }
        }
        if (!IsApi() && method_exists($this, "pageRedirecting")) {
            $this->pageRedirecting($url);
        }

        // Close connection
        CloseConnections();

        // Return for API
        if (IsApi()) {
            $res = $url === true;
            if (!$res) { // Show error
                WriteJson(array_merge(["success" => false], $this->getMessages()));
            }
            return;
        } else { // Check if response is JSON
            if (StartsString("application/json", $Response->getHeaderLine("Content-type")) && $Response->getBody()->getSize()) { // With JSON response
                $this->clearMessages();
                return;
            }
        }

        // Go to URL if specified
        if ($url != "") {
            if (!Config("DEBUG") && ob_get_length()) {
                ob_end_clean();
            }
            SaveDebugMessage();
            Redirect(GetUrl($url));
        }
        return; // Return to controller
    }

    // Get records from recordset
    protected function getRecordsFromRecordset($rs, $current = false)
    {
        $rows = [];
        if (is_object($rs)) { // Recordset
            while ($rs && !$rs->EOF) {
                $this->loadRowValues($rs); // Set up DbValue/CurrentValue
                $row = $this->getRecordFromArray($rs->fields);
                if ($current) {
                    return $row;
                } else {
                    $rows[] = $row;
                }
                $rs->moveNext();
            }
        } elseif (is_array($rs)) {
            foreach ($rs as $ar) {
                $row = $this->getRecordFromArray($ar);
                if ($current) {
                    return $row;
                } else {
                    $rows[] = $row;
                }
            }
        }
        return $rows;
    }

    // Get record from array
    protected function getRecordFromArray($ar)
    {
        $row = [];
        if (is_array($ar)) {
            foreach ($ar as $fldname => $val) {
                if (array_key_exists($fldname, $this->Fields) && ($this->Fields[$fldname]->Visible || $this->Fields[$fldname]->IsPrimaryKey)) { // Primary key or Visible
                    $fld = &$this->Fields[$fldname];
                    if ($fld->HtmlTag == "FILE") { // Upload field
                        if (EmptyValue($val)) {
                            $row[$fldname] = null;
                        } else {
                            if ($fld->DataType == DATATYPE_BLOB) {
                                $url = FullUrl(GetApiUrl(Config("API_FILE_ACTION") .
                                    "/" . $fld->TableVar . "/" . $fld->Param . "/" . rawurlencode($this->getRecordKeyValue($ar))));
                                $row[$fldname] = ["type" => ContentType($val), "url" => $url, "name" => $fld->Param . ContentExtension($val)];
                            } elseif (!$fld->UploadMultiple || !ContainsString($val, Config("MULTIPLE_UPLOAD_SEPARATOR"))) { // Single file
                                $url = FullUrl(GetApiUrl(Config("API_FILE_ACTION") .
                                    "/" . $fld->TableVar . "/" . Encrypt($fld->physicalUploadPath() . $val)));
                                $row[$fldname] = ["type" => MimeContentType($val), "url" => $url, "name" => $val];
                            } else { // Multiple files
                                $files = explode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $val);
                                $ar = [];
                                foreach ($files as $file) {
                                    $url = FullUrl(GetApiUrl(Config("API_FILE_ACTION") .
                                        "/" . $fld->TableVar . "/" . Encrypt($fld->physicalUploadPath() . $file)));
                                    if (!EmptyValue($file)) {
                                        $ar[] = ["type" => MimeContentType($file), "url" => $url, "name" => $file];
                                    }
                                }
                                $row[$fldname] = $ar;
                            }
                        }
                    } else {
                        if ($fld->DataType == DATATYPE_MEMO && $fld->MemoMaxLength > 0) {
                            $val = TruncateMemo($val, $fld->MemoMaxLength, $fld->TruncateMemoRemoveHtml);
                        }
                        $row[$fldname] = $val;
                    }
                }
            }
        }
        return $row;
    }

    // Get record key value from array
    protected function getRecordKeyValue($ar)
    {
        $key = "";
        if (is_array($ar)) {
            $key .= @$ar['idd_evaluasi'];
        }
        return $key;
    }

    /**
     * Hide fields for add/edit
     *
     * @return void
     */
    protected function hideFieldsForAddEdit()
    {
        if ($this->isAdd() || $this->isCopy() || $this->isGridAdd()) {
            $this->idd_evaluasi->Visible = false;
        }
    }

    // Lookup data
    public function lookup()
    {
        global $Language, $Security;

        // Get lookup object
        $fieldName = Post("field");
        $lookup = $this->Fields[$fieldName]->Lookup;

        // Get lookup parameters
        $lookupType = Post("ajax", "unknown");
        $pageSize = -1;
        $offset = -1;
        $searchValue = "";
        if (SameText($lookupType, "modal")) {
            $searchValue = Post("sv", "");
            $pageSize = Post("recperpage", 10);
            $offset = Post("start", 0);
        } elseif (SameText($lookupType, "autosuggest")) {
            $searchValue = Param("q", "");
            $pageSize = Param("n", -1);
            $pageSize = is_numeric($pageSize) ? (int)$pageSize : -1;
            if ($pageSize <= 0) {
                $pageSize = Config("AUTO_SUGGEST_MAX_ENTRIES");
            }
            $start = Param("start", -1);
            $start = is_numeric($start) ? (int)$start : -1;
            $page = Param("page", -1);
            $page = is_numeric($page) ? (int)$page : -1;
            $offset = $start >= 0 ? $start : ($page > 0 && $pageSize > 0 ? ($page - 1) * $pageSize : 0);
        }
        $userSelect = Decrypt(Post("s", ""));
        $userFilter = Decrypt(Post("f", ""));
        $userOrderBy = Decrypt(Post("o", ""));
        $keys = Post("keys");
        $lookup->LookupType = $lookupType; // Lookup type
        if ($keys !== null) { // Selected records from modal
            if (is_array($keys)) {
                $keys = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $keys);
            }
            $lookup->FilterFields = []; // Skip parent fields if any
            $lookup->FilterValues[] = $keys; // Lookup values
            $pageSize = -1; // Show all records
        } else { // Lookup values
            $lookup->FilterValues[] = Post("v0", Post("lookupValue", ""));
        }
        $cnt = is_array($lookup->FilterFields) ? count($lookup->FilterFields) : 0;
        for ($i = 1; $i <= $cnt; $i++) {
            $lookup->FilterValues[] = Post("v" . $i, "");
        }
        $lookup->SearchValue = $searchValue;
        $lookup->PageSize = $pageSize;
        $lookup->Offset = $offset;
        if ($userSelect != "") {
            $lookup->UserSelect = $userSelect;
        }
        if ($userFilter != "") {
            $lookup->UserFilter = $userFilter;
        }
        if ($userOrderBy != "") {
            $lookup->UserOrderBy = $userOrderBy;
        }
        $lookup->toJson($this); // Use settings from current page
    }

    // Class variables
    public $ListOptions; // List options
    public $ExportOptions; // Export options
    public $SearchOptions; // Search options
    public $OtherOptions; // Other options
    public $FilterOptions; // Filter options
    public $ImportOptions; // Import options
    public $ListActions; // List actions
    public $SelectedCount = 0;
    public $SelectedIndex = 0;
    public $DisplayRecords = 10;
    public $StartRecord;
    public $StopRecord;
    public $TotalRecords = 0;
    public $RecordRange = 10;
    public $PageSizes = "10,20,50,-1"; // Page sizes (comma separated)
    public $DefaultSearchWhere = ""; // Default search WHERE clause
    public $SearchWhere = ""; // Search WHERE clause
    public $SearchPanelClass = "ew-search-panel collapse show"; // Search Panel class
    public $SearchRowCount = 0; // For extended search
    public $SearchColumnCount = 0; // For extended search
    public $SearchFieldsPerRow = 1; // For extended search
    public $RecordCount = 0; // Record count
    public $EditRowCount;
    public $StartRowCount = 1;
    public $RowCount = 0;
    public $Attrs = []; // Row attributes and cell attributes
    public $RowIndex = 0; // Row index
    public $KeyCount = 0; // Key count
    public $RowAction = ""; // Row action
    public $MultiColumnClass = "col-sm";
    public $MultiColumnEditClass = "w-100";
    public $DbMasterFilter = ""; // Master filter
    public $DbDetailFilter = ""; // Detail filter
    public $MasterRecordExists;
    public $MultiSelectKey;
    public $Command;
    public $RestoreSearch = false;
    public $HashValue; // Hash value
    public $DetailPages;
    public $OldRecordset;

    /**
     * Page run
     *
     * @return void
     */
    public function run()
    {
        global $ExportType, $CustomExportType, $ExportFileName, $UserProfile, $Language, $Security, $CurrentForm;
        $this->CurrentAction = Param("action"); // Set up current action

        // Get grid add count
        $gridaddcnt = Get(Config("TABLE_GRID_ADD_ROW_COUNT"), "");
        if (is_numeric($gridaddcnt) && $gridaddcnt > 0) {
            $this->GridAddRowCount = $gridaddcnt;
        }

        // Set up list options
        $this->setupListOptions();
        $this->idd_evaluasi->setVisibility();
        $this->tanggal->setVisibility();
        $this->kd_satker->setVisibility();
        $this->idd_tahapan->setVisibility();
        $this->tahun_anggaran->setVisibility();
        $this->idd_wilayah->setVisibility();
        $this->file_01->setVisibility();
        $this->file_02->setVisibility();
        $this->file_03->setVisibility();
        $this->file_04->setVisibility();
        $this->file_05->setVisibility();
        $this->file_06->setVisibility();
        $this->file_07->setVisibility();
        $this->file_08->setVisibility();
        $this->file_09->setVisibility();
        $this->file_10->setVisibility();
        $this->file_11->setVisibility();
        $this->file_12->setVisibility();
        $this->file_13->setVisibility();
        $this->file_14->setVisibility();
        $this->file_15->setVisibility();
        $this->file_16->setVisibility();
        $this->file_17->setVisibility();
        $this->file_18->setVisibility();
        $this->file_19->setVisibility();
        $this->file_20->setVisibility();
        $this->file_21->setVisibility();
        $this->file_22->setVisibility();
        $this->file_23->setVisibility();
        $this->file_24->setVisibility();
        $this->status->setVisibility();
        $this->idd_user->setVisibility();
        $this->hideFieldsForAddEdit();

        // Global Page Loading event (in userfn*.php)
        Page_Loading();

        // Page Load event
        if (method_exists($this, "pageLoad")) {
            $this->pageLoad();
        }

        // Setup other options
        $this->setupOtherOptions();

        // Set up custom action (compatible with old version)
        foreach ($this->CustomActions as $name => $action) {
            $this->ListActions->add($name, $action);
        }

        // Show checkbox column if multiple action
        foreach ($this->ListActions->Items as $listaction) {
            if ($listaction->Select == ACTION_MULTIPLE && $listaction->Allow) {
                $this->ListOptions["checkbox"]->Visible = true;
                break;
            }
        }

        // Set up lookup cache

        // Search filters
        $srchAdvanced = ""; // Advanced search filter
        $srchBasic = ""; // Basic search filter
        $filter = "";

        // Get command
        $this->Command = strtolower(Get("cmd"));
        if ($this->isPageRequest()) {
            // Process list action first
            if ($this->processListAction()) { // Ajax request
                $this->terminate();
                return;
            }

            // Set up records per page
            $this->setupDisplayRecords();

            // Handle reset command
            $this->resetCmd();

            // Set up Breadcrumb
            if (!$this->isExport()) {
                $this->setupBreadcrumb();
            }

            // Hide list options
            if ($this->isExport()) {
                $this->ListOptions->hideAllOptions(["sequence"]);
                $this->ListOptions->UseDropDownButton = false; // Disable drop down button
                $this->ListOptions->UseButtonGroup = false; // Disable button group
            } elseif ($this->isGridAdd() || $this->isGridEdit()) {
                $this->ListOptions->hideAllOptions();
                $this->ListOptions->UseDropDownButton = false; // Disable drop down button
                $this->ListOptions->UseButtonGroup = false; // Disable button group
            }

            // Hide options
            if ($this->isExport() || $this->CurrentAction) {
                $this->ExportOptions->hideAllOptions();
                $this->FilterOptions->hideAllOptions();
                $this->ImportOptions->hideAllOptions();
            }

            // Hide other options
            if ($this->isExport()) {
                $this->OtherOptions->hideAllOptions();
            }

            // Get default search criteria
            AddFilter($this->DefaultSearchWhere, $this->basicSearchWhere(true));

            // Get basic search values
            $this->loadBasicSearchValues();

            // Process filter list
            if ($this->processFilterList()) {
                $this->terminate();
                return;
            }

            // Restore search parms from Session if not searching / reset / export
            if (($this->isExport() || $this->Command != "search" && $this->Command != "reset" && $this->Command != "resetall") && $this->Command != "json" && $this->checkSearchParms()) {
                $this->restoreSearchParms();
            }

            // Call Recordset SearchValidated event
            $this->recordsetSearchValidated();

            // Set up sorting order
            $this->setupSortOrder();

            // Get basic search criteria
            if (!$this->hasInvalidFields()) {
                $srchBasic = $this->basicSearchWhere();
            }
        }

        // Restore display records
        if ($this->Command != "json" && $this->getRecordsPerPage() != "") {
            $this->DisplayRecords = $this->getRecordsPerPage(); // Restore from Session
        } else {
            $this->DisplayRecords = 10; // Load default
            $this->setRecordsPerPage($this->DisplayRecords); // Save default to Session
        }

        // Load Sorting Order
        if ($this->Command != "json") {
            $this->loadSortOrder();
        }

        // Load search default if no existing search criteria
        if (!$this->checkSearchParms()) {
            // Load basic search from default
            $this->BasicSearch->loadDefault();
            if ($this->BasicSearch->Keyword != "") {
                $srchBasic = $this->basicSearchWhere();
            }
        }

        // Build search criteria
        AddFilter($this->SearchWhere, $srchAdvanced);
        AddFilter($this->SearchWhere, $srchBasic);

        // Call Recordset_Searching event
        $this->recordsetSearching($this->SearchWhere);

        // Save search criteria
        if ($this->Command == "search" && !$this->RestoreSearch) {
            $this->setSearchWhere($this->SearchWhere); // Save to Session
            $this->StartRecord = 1; // Reset start record counter
            $this->setStartRecordNumber($this->StartRecord);
        } elseif ($this->Command != "json") {
            $this->SearchWhere = $this->getSearchWhere();
        }

        // Build filter
        $filter = "";
        if (!$Security->canList()) {
            $filter = "(0=1)"; // Filter all records
        }
        AddFilter($filter, $this->DbDetailFilter);
        AddFilter($filter, $this->SearchWhere);

        // Set up filter
        if ($this->Command == "json") {
            $this->UseSessionForListSql = false; // Do not use session for ListSQL
            $this->CurrentFilter = $filter;
        } else {
            $this->setSessionWhere($filter);
            $this->CurrentFilter = "";
        }
        if ($this->isGridAdd()) {
            $this->CurrentFilter = "0=1";
            $this->StartRecord = 1;
            $this->DisplayRecords = $this->GridAddRowCount;
            $this->TotalRecords = $this->DisplayRecords;
            $this->StopRecord = $this->DisplayRecords;
        } else {
            $this->TotalRecords = $this->listRecordCount();
            $this->StartRecord = 1;
            if ($this->DisplayRecords <= 0 || ($this->isExport() && $this->ExportAll)) { // Display all records
                $this->DisplayRecords = $this->TotalRecords;
            }
            if (!($this->isExport() && $this->ExportAll)) { // Set up start record position
                $this->setupStartRecord();
            }
            $this->Recordset = $this->loadRecordset($this->StartRecord - 1, $this->DisplayRecords);

            // Set no record found message
            if (!$this->CurrentAction && $this->TotalRecords == 0) {
                if (!$Security->canList()) {
                    $this->setWarningMessage(DeniedMessage());
                }
                if ($this->SearchWhere == "0=101") {
                    $this->setWarningMessage($Language->phrase("EnterSearchCriteria"));
                } else {
                    $this->setWarningMessage($Language->phrase("NoRecord"));
                }
            }
        }

        // Search options
        $this->setupSearchOptions();

        // Set up search panel class
        if ($this->SearchWhere != "") {
            AppendClass($this->SearchPanelClass, "show");
        }

        // Normal return
        if (IsApi()) {
            $rows = $this->getRecordsFromRecordset($this->Recordset);
            $this->Recordset->close();
            WriteJson(["success" => true, $this->TableVar => $rows, "totalRecordCount" => $this->TotalRecords]);
            $this->terminate(true);
            return;
        }

        // Set up pager
        $this->Pager = new PrevNextPager($this->StartRecord, $this->getRecordsPerPage(), $this->TotalRecords, $this->PageSizes, $this->RecordRange, $this->AutoHidePager, $this->AutoHidePageSizeSelector);

        // Set LoginStatus / Page_Rendering / Page_Render
        if (!IsApi() && !$this->isTerminated()) {
            // Pass table and field properties to client side
            $this->toClientVar(["tableCaption"], ["caption", "Visible", "Required", "IsInvalid", "Raw"]);

            // Setup login status
            SetupLoginStatus();

            // Pass login status to client side
            SetClientVar("login", LoginStatus());

            // Global Page Rendering event (in userfn*.php)
            Page_Rendering();

            // Page Render event
            if (method_exists($this, "pageRender")) {
                $this->pageRender();
            }
        }
    }

    // Set up number of records displayed per page
    protected function setupDisplayRecords()
    {
        $wrk = Get(Config("TABLE_REC_PER_PAGE"), "");
        if ($wrk != "") {
            if (is_numeric($wrk)) {
                $this->DisplayRecords = (int)$wrk;
            } else {
                if (SameText($wrk, "all")) { // Display all records
                    $this->DisplayRecords = -1;
                } else {
                    $this->DisplayRecords = 10; // Non-numeric, load default
                }
            }
            $this->setRecordsPerPage($this->DisplayRecords); // Save to Session
            // Reset start position
            $this->StartRecord = 1;
            $this->setStartRecordNumber($this->StartRecord);
        }
    }

    // Build filter for all keys
    protected function buildKeyFilter()
    {
        global $CurrentForm;
        $wrkFilter = "";

        // Update row index and get row key
        $rowindex = 1;
        $CurrentForm->Index = $rowindex;
        $thisKey = strval($CurrentForm->getValue($this->OldKeyName));
        while ($thisKey != "") {
            $this->setKey($thisKey);
            if ($this->OldKey != "") {
                $filter = $this->getRecordFilter();
                if ($wrkFilter != "") {
                    $wrkFilter .= " OR ";
                }
                $wrkFilter .= $filter;
            } else {
                $wrkFilter = "0=1";
                break;
            }

            // Update row index and get row key
            $rowindex++; // Next row
            $CurrentForm->Index = $rowindex;
            $thisKey = strval($CurrentForm->getValue($this->OldKeyName));
        }
        return $wrkFilter;
    }

    // Get list of filters
    public function getFilterList()
    {
        global $UserProfile;

        // Initialize
        $filterList = "";
        $savedFilterList = "";
        $filterList = Concat($filterList, $this->idd_evaluasi->AdvancedSearch->toJson(), ","); // Field idd_evaluasi
        $filterList = Concat($filterList, $this->tanggal->AdvancedSearch->toJson(), ","); // Field tanggal
        $filterList = Concat($filterList, $this->kd_satker->AdvancedSearch->toJson(), ","); // Field kd_satker
        $filterList = Concat($filterList, $this->idd_tahapan->AdvancedSearch->toJson(), ","); // Field idd_tahapan
        $filterList = Concat($filterList, $this->tahun_anggaran->AdvancedSearch->toJson(), ","); // Field tahun_anggaran
        $filterList = Concat($filterList, $this->idd_wilayah->AdvancedSearch->toJson(), ","); // Field idd_wilayah
        $filterList = Concat($filterList, $this->file_01->AdvancedSearch->toJson(), ","); // Field file_01
        $filterList = Concat($filterList, $this->file_02->AdvancedSearch->toJson(), ","); // Field file_02
        $filterList = Concat($filterList, $this->file_03->AdvancedSearch->toJson(), ","); // Field file_03
        $filterList = Concat($filterList, $this->file_04->AdvancedSearch->toJson(), ","); // Field file_04
        $filterList = Concat($filterList, $this->file_05->AdvancedSearch->toJson(), ","); // Field file_05
        $filterList = Concat($filterList, $this->file_06->AdvancedSearch->toJson(), ","); // Field file_06
        $filterList = Concat($filterList, $this->file_07->AdvancedSearch->toJson(), ","); // Field file_07
        $filterList = Concat($filterList, $this->file_08->AdvancedSearch->toJson(), ","); // Field file_08
        $filterList = Concat($filterList, $this->file_09->AdvancedSearch->toJson(), ","); // Field file_09
        $filterList = Concat($filterList, $this->file_10->AdvancedSearch->toJson(), ","); // Field file_10
        $filterList = Concat($filterList, $this->file_11->AdvancedSearch->toJson(), ","); // Field file_11
        $filterList = Concat($filterList, $this->file_12->AdvancedSearch->toJson(), ","); // Field file_12
        $filterList = Concat($filterList, $this->file_13->AdvancedSearch->toJson(), ","); // Field file_13
        $filterList = Concat($filterList, $this->file_14->AdvancedSearch->toJson(), ","); // Field file_14
        $filterList = Concat($filterList, $this->file_15->AdvancedSearch->toJson(), ","); // Field file_15
        $filterList = Concat($filterList, $this->file_16->AdvancedSearch->toJson(), ","); // Field file_16
        $filterList = Concat($filterList, $this->file_17->AdvancedSearch->toJson(), ","); // Field file_17
        $filterList = Concat($filterList, $this->file_18->AdvancedSearch->toJson(), ","); // Field file_18
        $filterList = Concat($filterList, $this->file_19->AdvancedSearch->toJson(), ","); // Field file_19
        $filterList = Concat($filterList, $this->file_20->AdvancedSearch->toJson(), ","); // Field file_20
        $filterList = Concat($filterList, $this->file_21->AdvancedSearch->toJson(), ","); // Field file_21
        $filterList = Concat($filterList, $this->file_22->AdvancedSearch->toJson(), ","); // Field file_22
        $filterList = Concat($filterList, $this->file_23->AdvancedSearch->toJson(), ","); // Field file_23
        $filterList = Concat($filterList, $this->file_24->AdvancedSearch->toJson(), ","); // Field file_24
        $filterList = Concat($filterList, $this->status->AdvancedSearch->toJson(), ","); // Field status
        $filterList = Concat($filterList, $this->idd_user->AdvancedSearch->toJson(), ","); // Field idd_user
        if ($this->BasicSearch->Keyword != "") {
            $wrk = "\"" . Config("TABLE_BASIC_SEARCH") . "\":\"" . JsEncode($this->BasicSearch->Keyword) . "\",\"" . Config("TABLE_BASIC_SEARCH_TYPE") . "\":\"" . JsEncode($this->BasicSearch->Type) . "\"";
            $filterList = Concat($filterList, $wrk, ",");
        }

        // Return filter list in JSON
        if ($filterList != "") {
            $filterList = "\"data\":{" . $filterList . "}";
        }
        if ($savedFilterList != "") {
            $filterList = Concat($filterList, "\"filters\":" . $savedFilterList, ",");
        }
        return ($filterList != "") ? "{" . $filterList . "}" : "null";
    }

    // Process filter list
    protected function processFilterList()
    {
        global $UserProfile;
        if (Post("ajax") == "savefilters") { // Save filter request (Ajax)
            $filters = Post("filters");
            $UserProfile->setSearchFilters(CurrentUserName(), "fapbklistsrch", $filters);
            WriteJson([["success" => true]]); // Success
            return true;
        } elseif (Post("cmd") == "resetfilter") {
            $this->restoreFilterList();
        }
        return false;
    }

    // Restore list of filters
    protected function restoreFilterList()
    {
        // Return if not reset filter
        if (Post("cmd") !== "resetfilter") {
            return false;
        }
        $filter = json_decode(Post("filter"), true);
        $this->Command = "search";

        // Field idd_evaluasi
        $this->idd_evaluasi->AdvancedSearch->SearchValue = @$filter["x_idd_evaluasi"];
        $this->idd_evaluasi->AdvancedSearch->SearchOperator = @$filter["z_idd_evaluasi"];
        $this->idd_evaluasi->AdvancedSearch->SearchCondition = @$filter["v_idd_evaluasi"];
        $this->idd_evaluasi->AdvancedSearch->SearchValue2 = @$filter["y_idd_evaluasi"];
        $this->idd_evaluasi->AdvancedSearch->SearchOperator2 = @$filter["w_idd_evaluasi"];
        $this->idd_evaluasi->AdvancedSearch->save();

        // Field tanggal
        $this->tanggal->AdvancedSearch->SearchValue = @$filter["x_tanggal"];
        $this->tanggal->AdvancedSearch->SearchOperator = @$filter["z_tanggal"];
        $this->tanggal->AdvancedSearch->SearchCondition = @$filter["v_tanggal"];
        $this->tanggal->AdvancedSearch->SearchValue2 = @$filter["y_tanggal"];
        $this->tanggal->AdvancedSearch->SearchOperator2 = @$filter["w_tanggal"];
        $this->tanggal->AdvancedSearch->save();

        // Field kd_satker
        $this->kd_satker->AdvancedSearch->SearchValue = @$filter["x_kd_satker"];
        $this->kd_satker->AdvancedSearch->SearchOperator = @$filter["z_kd_satker"];
        $this->kd_satker->AdvancedSearch->SearchCondition = @$filter["v_kd_satker"];
        $this->kd_satker->AdvancedSearch->SearchValue2 = @$filter["y_kd_satker"];
        $this->kd_satker->AdvancedSearch->SearchOperator2 = @$filter["w_kd_satker"];
        $this->kd_satker->AdvancedSearch->save();

        // Field idd_tahapan
        $this->idd_tahapan->AdvancedSearch->SearchValue = @$filter["x_idd_tahapan"];
        $this->idd_tahapan->AdvancedSearch->SearchOperator = @$filter["z_idd_tahapan"];
        $this->idd_tahapan->AdvancedSearch->SearchCondition = @$filter["v_idd_tahapan"];
        $this->idd_tahapan->AdvancedSearch->SearchValue2 = @$filter["y_idd_tahapan"];
        $this->idd_tahapan->AdvancedSearch->SearchOperator2 = @$filter["w_idd_tahapan"];
        $this->idd_tahapan->AdvancedSearch->save();

        // Field tahun_anggaran
        $this->tahun_anggaran->AdvancedSearch->SearchValue = @$filter["x_tahun_anggaran"];
        $this->tahun_anggaran->AdvancedSearch->SearchOperator = @$filter["z_tahun_anggaran"];
        $this->tahun_anggaran->AdvancedSearch->SearchCondition = @$filter["v_tahun_anggaran"];
        $this->tahun_anggaran->AdvancedSearch->SearchValue2 = @$filter["y_tahun_anggaran"];
        $this->tahun_anggaran->AdvancedSearch->SearchOperator2 = @$filter["w_tahun_anggaran"];
        $this->tahun_anggaran->AdvancedSearch->save();

        // Field idd_wilayah
        $this->idd_wilayah->AdvancedSearch->SearchValue = @$filter["x_idd_wilayah"];
        $this->idd_wilayah->AdvancedSearch->SearchOperator = @$filter["z_idd_wilayah"];
        $this->idd_wilayah->AdvancedSearch->SearchCondition = @$filter["v_idd_wilayah"];
        $this->idd_wilayah->AdvancedSearch->SearchValue2 = @$filter["y_idd_wilayah"];
        $this->idd_wilayah->AdvancedSearch->SearchOperator2 = @$filter["w_idd_wilayah"];
        $this->idd_wilayah->AdvancedSearch->save();

        // Field file_01
        $this->file_01->AdvancedSearch->SearchValue = @$filter["x_file_01"];
        $this->file_01->AdvancedSearch->SearchOperator = @$filter["z_file_01"];
        $this->file_01->AdvancedSearch->SearchCondition = @$filter["v_file_01"];
        $this->file_01->AdvancedSearch->SearchValue2 = @$filter["y_file_01"];
        $this->file_01->AdvancedSearch->SearchOperator2 = @$filter["w_file_01"];
        $this->file_01->AdvancedSearch->save();

        // Field file_02
        $this->file_02->AdvancedSearch->SearchValue = @$filter["x_file_02"];
        $this->file_02->AdvancedSearch->SearchOperator = @$filter["z_file_02"];
        $this->file_02->AdvancedSearch->SearchCondition = @$filter["v_file_02"];
        $this->file_02->AdvancedSearch->SearchValue2 = @$filter["y_file_02"];
        $this->file_02->AdvancedSearch->SearchOperator2 = @$filter["w_file_02"];
        $this->file_02->AdvancedSearch->save();

        // Field file_03
        $this->file_03->AdvancedSearch->SearchValue = @$filter["x_file_03"];
        $this->file_03->AdvancedSearch->SearchOperator = @$filter["z_file_03"];
        $this->file_03->AdvancedSearch->SearchCondition = @$filter["v_file_03"];
        $this->file_03->AdvancedSearch->SearchValue2 = @$filter["y_file_03"];
        $this->file_03->AdvancedSearch->SearchOperator2 = @$filter["w_file_03"];
        $this->file_03->AdvancedSearch->save();

        // Field file_04
        $this->file_04->AdvancedSearch->SearchValue = @$filter["x_file_04"];
        $this->file_04->AdvancedSearch->SearchOperator = @$filter["z_file_04"];
        $this->file_04->AdvancedSearch->SearchCondition = @$filter["v_file_04"];
        $this->file_04->AdvancedSearch->SearchValue2 = @$filter["y_file_04"];
        $this->file_04->AdvancedSearch->SearchOperator2 = @$filter["w_file_04"];
        $this->file_04->AdvancedSearch->save();

        // Field file_05
        $this->file_05->AdvancedSearch->SearchValue = @$filter["x_file_05"];
        $this->file_05->AdvancedSearch->SearchOperator = @$filter["z_file_05"];
        $this->file_05->AdvancedSearch->SearchCondition = @$filter["v_file_05"];
        $this->file_05->AdvancedSearch->SearchValue2 = @$filter["y_file_05"];
        $this->file_05->AdvancedSearch->SearchOperator2 = @$filter["w_file_05"];
        $this->file_05->AdvancedSearch->save();

        // Field file_06
        $this->file_06->AdvancedSearch->SearchValue = @$filter["x_file_06"];
        $this->file_06->AdvancedSearch->SearchOperator = @$filter["z_file_06"];
        $this->file_06->AdvancedSearch->SearchCondition = @$filter["v_file_06"];
        $this->file_06->AdvancedSearch->SearchValue2 = @$filter["y_file_06"];
        $this->file_06->AdvancedSearch->SearchOperator2 = @$filter["w_file_06"];
        $this->file_06->AdvancedSearch->save();

        // Field file_07
        $this->file_07->AdvancedSearch->SearchValue = @$filter["x_file_07"];
        $this->file_07->AdvancedSearch->SearchOperator = @$filter["z_file_07"];
        $this->file_07->AdvancedSearch->SearchCondition = @$filter["v_file_07"];
        $this->file_07->AdvancedSearch->SearchValue2 = @$filter["y_file_07"];
        $this->file_07->AdvancedSearch->SearchOperator2 = @$filter["w_file_07"];
        $this->file_07->AdvancedSearch->save();

        // Field file_08
        $this->file_08->AdvancedSearch->SearchValue = @$filter["x_file_08"];
        $this->file_08->AdvancedSearch->SearchOperator = @$filter["z_file_08"];
        $this->file_08->AdvancedSearch->SearchCondition = @$filter["v_file_08"];
        $this->file_08->AdvancedSearch->SearchValue2 = @$filter["y_file_08"];
        $this->file_08->AdvancedSearch->SearchOperator2 = @$filter["w_file_08"];
        $this->file_08->AdvancedSearch->save();

        // Field file_09
        $this->file_09->AdvancedSearch->SearchValue = @$filter["x_file_09"];
        $this->file_09->AdvancedSearch->SearchOperator = @$filter["z_file_09"];
        $this->file_09->AdvancedSearch->SearchCondition = @$filter["v_file_09"];
        $this->file_09->AdvancedSearch->SearchValue2 = @$filter["y_file_09"];
        $this->file_09->AdvancedSearch->SearchOperator2 = @$filter["w_file_09"];
        $this->file_09->AdvancedSearch->save();

        // Field file_10
        $this->file_10->AdvancedSearch->SearchValue = @$filter["x_file_10"];
        $this->file_10->AdvancedSearch->SearchOperator = @$filter["z_file_10"];
        $this->file_10->AdvancedSearch->SearchCondition = @$filter["v_file_10"];
        $this->file_10->AdvancedSearch->SearchValue2 = @$filter["y_file_10"];
        $this->file_10->AdvancedSearch->SearchOperator2 = @$filter["w_file_10"];
        $this->file_10->AdvancedSearch->save();

        // Field file_11
        $this->file_11->AdvancedSearch->SearchValue = @$filter["x_file_11"];
        $this->file_11->AdvancedSearch->SearchOperator = @$filter["z_file_11"];
        $this->file_11->AdvancedSearch->SearchCondition = @$filter["v_file_11"];
        $this->file_11->AdvancedSearch->SearchValue2 = @$filter["y_file_11"];
        $this->file_11->AdvancedSearch->SearchOperator2 = @$filter["w_file_11"];
        $this->file_11->AdvancedSearch->save();

        // Field file_12
        $this->file_12->AdvancedSearch->SearchValue = @$filter["x_file_12"];
        $this->file_12->AdvancedSearch->SearchOperator = @$filter["z_file_12"];
        $this->file_12->AdvancedSearch->SearchCondition = @$filter["v_file_12"];
        $this->file_12->AdvancedSearch->SearchValue2 = @$filter["y_file_12"];
        $this->file_12->AdvancedSearch->SearchOperator2 = @$filter["w_file_12"];
        $this->file_12->AdvancedSearch->save();

        // Field file_13
        $this->file_13->AdvancedSearch->SearchValue = @$filter["x_file_13"];
        $this->file_13->AdvancedSearch->SearchOperator = @$filter["z_file_13"];
        $this->file_13->AdvancedSearch->SearchCondition = @$filter["v_file_13"];
        $this->file_13->AdvancedSearch->SearchValue2 = @$filter["y_file_13"];
        $this->file_13->AdvancedSearch->SearchOperator2 = @$filter["w_file_13"];
        $this->file_13->AdvancedSearch->save();

        // Field file_14
        $this->file_14->AdvancedSearch->SearchValue = @$filter["x_file_14"];
        $this->file_14->AdvancedSearch->SearchOperator = @$filter["z_file_14"];
        $this->file_14->AdvancedSearch->SearchCondition = @$filter["v_file_14"];
        $this->file_14->AdvancedSearch->SearchValue2 = @$filter["y_file_14"];
        $this->file_14->AdvancedSearch->SearchOperator2 = @$filter["w_file_14"];
        $this->file_14->AdvancedSearch->save();

        // Field file_15
        $this->file_15->AdvancedSearch->SearchValue = @$filter["x_file_15"];
        $this->file_15->AdvancedSearch->SearchOperator = @$filter["z_file_15"];
        $this->file_15->AdvancedSearch->SearchCondition = @$filter["v_file_15"];
        $this->file_15->AdvancedSearch->SearchValue2 = @$filter["y_file_15"];
        $this->file_15->AdvancedSearch->SearchOperator2 = @$filter["w_file_15"];
        $this->file_15->AdvancedSearch->save();

        // Field file_16
        $this->file_16->AdvancedSearch->SearchValue = @$filter["x_file_16"];
        $this->file_16->AdvancedSearch->SearchOperator = @$filter["z_file_16"];
        $this->file_16->AdvancedSearch->SearchCondition = @$filter["v_file_16"];
        $this->file_16->AdvancedSearch->SearchValue2 = @$filter["y_file_16"];
        $this->file_16->AdvancedSearch->SearchOperator2 = @$filter["w_file_16"];
        $this->file_16->AdvancedSearch->save();

        // Field file_17
        $this->file_17->AdvancedSearch->SearchValue = @$filter["x_file_17"];
        $this->file_17->AdvancedSearch->SearchOperator = @$filter["z_file_17"];
        $this->file_17->AdvancedSearch->SearchCondition = @$filter["v_file_17"];
        $this->file_17->AdvancedSearch->SearchValue2 = @$filter["y_file_17"];
        $this->file_17->AdvancedSearch->SearchOperator2 = @$filter["w_file_17"];
        $this->file_17->AdvancedSearch->save();

        // Field file_18
        $this->file_18->AdvancedSearch->SearchValue = @$filter["x_file_18"];
        $this->file_18->AdvancedSearch->SearchOperator = @$filter["z_file_18"];
        $this->file_18->AdvancedSearch->SearchCondition = @$filter["v_file_18"];
        $this->file_18->AdvancedSearch->SearchValue2 = @$filter["y_file_18"];
        $this->file_18->AdvancedSearch->SearchOperator2 = @$filter["w_file_18"];
        $this->file_18->AdvancedSearch->save();

        // Field file_19
        $this->file_19->AdvancedSearch->SearchValue = @$filter["x_file_19"];
        $this->file_19->AdvancedSearch->SearchOperator = @$filter["z_file_19"];
        $this->file_19->AdvancedSearch->SearchCondition = @$filter["v_file_19"];
        $this->file_19->AdvancedSearch->SearchValue2 = @$filter["y_file_19"];
        $this->file_19->AdvancedSearch->SearchOperator2 = @$filter["w_file_19"];
        $this->file_19->AdvancedSearch->save();

        // Field file_20
        $this->file_20->AdvancedSearch->SearchValue = @$filter["x_file_20"];
        $this->file_20->AdvancedSearch->SearchOperator = @$filter["z_file_20"];
        $this->file_20->AdvancedSearch->SearchCondition = @$filter["v_file_20"];
        $this->file_20->AdvancedSearch->SearchValue2 = @$filter["y_file_20"];
        $this->file_20->AdvancedSearch->SearchOperator2 = @$filter["w_file_20"];
        $this->file_20->AdvancedSearch->save();

        // Field file_21
        $this->file_21->AdvancedSearch->SearchValue = @$filter["x_file_21"];
        $this->file_21->AdvancedSearch->SearchOperator = @$filter["z_file_21"];
        $this->file_21->AdvancedSearch->SearchCondition = @$filter["v_file_21"];
        $this->file_21->AdvancedSearch->SearchValue2 = @$filter["y_file_21"];
        $this->file_21->AdvancedSearch->SearchOperator2 = @$filter["w_file_21"];
        $this->file_21->AdvancedSearch->save();

        // Field file_22
        $this->file_22->AdvancedSearch->SearchValue = @$filter["x_file_22"];
        $this->file_22->AdvancedSearch->SearchOperator = @$filter["z_file_22"];
        $this->file_22->AdvancedSearch->SearchCondition = @$filter["v_file_22"];
        $this->file_22->AdvancedSearch->SearchValue2 = @$filter["y_file_22"];
        $this->file_22->AdvancedSearch->SearchOperator2 = @$filter["w_file_22"];
        $this->file_22->AdvancedSearch->save();

        // Field file_23
        $this->file_23->AdvancedSearch->SearchValue = @$filter["x_file_23"];
        $this->file_23->AdvancedSearch->SearchOperator = @$filter["z_file_23"];
        $this->file_23->AdvancedSearch->SearchCondition = @$filter["v_file_23"];
        $this->file_23->AdvancedSearch->SearchValue2 = @$filter["y_file_23"];
        $this->file_23->AdvancedSearch->SearchOperator2 = @$filter["w_file_23"];
        $this->file_23->AdvancedSearch->save();

        // Field file_24
        $this->file_24->AdvancedSearch->SearchValue = @$filter["x_file_24"];
        $this->file_24->AdvancedSearch->SearchOperator = @$filter["z_file_24"];
        $this->file_24->AdvancedSearch->SearchCondition = @$filter["v_file_24"];
        $this->file_24->AdvancedSearch->SearchValue2 = @$filter["y_file_24"];
        $this->file_24->AdvancedSearch->SearchOperator2 = @$filter["w_file_24"];
        $this->file_24->AdvancedSearch->save();

        // Field status
        $this->status->AdvancedSearch->SearchValue = @$filter["x_status"];
        $this->status->AdvancedSearch->SearchOperator = @$filter["z_status"];
        $this->status->AdvancedSearch->SearchCondition = @$filter["v_status"];
        $this->status->AdvancedSearch->SearchValue2 = @$filter["y_status"];
        $this->status->AdvancedSearch->SearchOperator2 = @$filter["w_status"];
        $this->status->AdvancedSearch->save();

        // Field idd_user
        $this->idd_user->AdvancedSearch->SearchValue = @$filter["x_idd_user"];
        $this->idd_user->AdvancedSearch->SearchOperator = @$filter["z_idd_user"];
        $this->idd_user->AdvancedSearch->SearchCondition = @$filter["v_idd_user"];
        $this->idd_user->AdvancedSearch->SearchValue2 = @$filter["y_idd_user"];
        $this->idd_user->AdvancedSearch->SearchOperator2 = @$filter["w_idd_user"];
        $this->idd_user->AdvancedSearch->save();
        $this->BasicSearch->setKeyword(@$filter[Config("TABLE_BASIC_SEARCH")]);
        $this->BasicSearch->setType(@$filter[Config("TABLE_BASIC_SEARCH_TYPE")]);
    }

    // Return basic search SQL
    protected function basicSearchSql($arKeywords, $type)
    {
        $where = "";
        $this->buildBasicSearchSql($where, $this->kd_satker, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->tahun_anggaran, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->file_01, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->file_02, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->file_03, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->file_04, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->file_05, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->file_06, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->file_07, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->file_08, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->file_09, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->file_10, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->file_11, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->file_12, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->file_13, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->file_14, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->file_15, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->file_16, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->file_17, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->file_18, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->file_19, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->file_20, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->file_21, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->file_22, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->file_23, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->file_24, $arKeywords, $type);
        return $where;
    }

    // Build basic search SQL
    protected function buildBasicSearchSql(&$where, &$fld, $arKeywords, $type)
    {
        $defCond = ($type == "OR") ? "OR" : "AND";
        $arSql = []; // Array for SQL parts
        $arCond = []; // Array for search conditions
        $cnt = count($arKeywords);
        $j = 0; // Number of SQL parts
        for ($i = 0; $i < $cnt; $i++) {
            $keyword = $arKeywords[$i];
            $keyword = trim($keyword);
            if (Config("BASIC_SEARCH_IGNORE_PATTERN") != "") {
                $keyword = preg_replace(Config("BASIC_SEARCH_IGNORE_PATTERN"), "\\", $keyword);
                $ar = explode("\\", $keyword);
            } else {
                $ar = [$keyword];
            }
            foreach ($ar as $keyword) {
                if ($keyword != "") {
                    $wrk = "";
                    if ($keyword == "OR" && $type == "") {
                        if ($j > 0) {
                            $arCond[$j - 1] = "OR";
                        }
                    } elseif ($keyword == Config("NULL_VALUE")) {
                        $wrk = $fld->Expression . " IS NULL";
                    } elseif ($keyword == Config("NOT_NULL_VALUE")) {
                        $wrk = $fld->Expression . " IS NOT NULL";
                    } elseif ($fld->IsVirtual && $fld->Visible) {
                        $wrk = $fld->VirtualExpression . Like(QuotedValue("%" . $keyword . "%", DATATYPE_STRING, $this->Dbid), $this->Dbid);
                    } elseif ($fld->DataType != DATATYPE_NUMBER || is_numeric($keyword)) {
                        $wrk = $fld->BasicSearchExpression . Like(QuotedValue("%" . $keyword . "%", DATATYPE_STRING, $this->Dbid), $this->Dbid);
                    }
                    if ($wrk != "") {
                        $arSql[$j] = $wrk;
                        $arCond[$j] = $defCond;
                        $j += 1;
                    }
                }
            }
        }
        $cnt = count($arSql);
        $quoted = false;
        $sql = "";
        if ($cnt > 0) {
            for ($i = 0; $i < $cnt - 1; $i++) {
                if ($arCond[$i] == "OR") {
                    if (!$quoted) {
                        $sql .= "(";
                    }
                    $quoted = true;
                }
                $sql .= $arSql[$i];
                if ($quoted && $arCond[$i] != "OR") {
                    $sql .= ")";
                    $quoted = false;
                }
                $sql .= " " . $arCond[$i] . " ";
            }
            $sql .= $arSql[$cnt - 1];
            if ($quoted) {
                $sql .= ")";
            }
        }
        if ($sql != "") {
            if ($where != "") {
                $where .= " OR ";
            }
            $where .= "(" . $sql . ")";
        }
    }

    // Return basic search WHERE clause based on search keyword and type
    protected function basicSearchWhere($default = false)
    {
        global $Security;
        $searchStr = "";
        if (!$Security->canSearch()) {
            return "";
        }
        $searchKeyword = ($default) ? $this->BasicSearch->KeywordDefault : $this->BasicSearch->Keyword;
        $searchType = ($default) ? $this->BasicSearch->TypeDefault : $this->BasicSearch->Type;

        // Get search SQL
        if ($searchKeyword != "") {
            $ar = $this->BasicSearch->keywordList($default);
            // Search keyword in any fields
            if (($searchType == "OR" || $searchType == "AND") && $this->BasicSearch->BasicSearchAnyFields) {
                foreach ($ar as $keyword) {
                    if ($keyword != "") {
                        if ($searchStr != "") {
                            $searchStr .= " " . $searchType . " ";
                        }
                        $searchStr .= "(" . $this->basicSearchSql([$keyword], $searchType) . ")";
                    }
                }
            } else {
                $searchStr = $this->basicSearchSql($ar, $searchType);
            }
            if (!$default && in_array($this->Command, ["", "reset", "resetall"])) {
                $this->Command = "search";
            }
        }
        if (!$default && $this->Command == "search") {
            $this->BasicSearch->setKeyword($searchKeyword);
            $this->BasicSearch->setType($searchType);
        }
        return $searchStr;
    }

    // Check if search parm exists
    protected function checkSearchParms()
    {
        // Check basic search
        if ($this->BasicSearch->issetSession()) {
            return true;
        }
        return false;
    }

    // Clear all search parameters
    protected function resetSearchParms()
    {
        // Clear search WHERE clause
        $this->SearchWhere = "";
        $this->setSearchWhere($this->SearchWhere);

        // Clear basic search parameters
        $this->resetBasicSearchParms();
    }

    // Load advanced search default values
    protected function loadAdvancedSearchDefault()
    {
        return false;
    }

    // Clear all basic search parameters
    protected function resetBasicSearchParms()
    {
        $this->BasicSearch->unsetSession();
    }

    // Restore all search parameters
    protected function restoreSearchParms()
    {
        $this->RestoreSearch = true;

        // Restore basic search values
        $this->BasicSearch->load();
    }

    // Set up sort parameters
    protected function setupSortOrder()
    {
        // Check for "order" parameter
        if (Get("order") !== null) {
            $this->CurrentOrder = Get("order");
            $this->CurrentOrderType = Get("ordertype", "");
            $this->updateSort($this->idd_evaluasi); // idd_evaluasi
            $this->updateSort($this->tanggal); // tanggal
            $this->updateSort($this->kd_satker); // kd_satker
            $this->updateSort($this->idd_tahapan); // idd_tahapan
            $this->updateSort($this->tahun_anggaran); // tahun_anggaran
            $this->updateSort($this->idd_wilayah); // idd_wilayah
            $this->updateSort($this->file_01); // file_01
            $this->updateSort($this->file_02); // file_02
            $this->updateSort($this->file_03); // file_03
            $this->updateSort($this->file_04); // file_04
            $this->updateSort($this->file_05); // file_05
            $this->updateSort($this->file_06); // file_06
            $this->updateSort($this->file_07); // file_07
            $this->updateSort($this->file_08); // file_08
            $this->updateSort($this->file_09); // file_09
            $this->updateSort($this->file_10); // file_10
            $this->updateSort($this->file_11); // file_11
            $this->updateSort($this->file_12); // file_12
            $this->updateSort($this->file_13); // file_13
            $this->updateSort($this->file_14); // file_14
            $this->updateSort($this->file_15); // file_15
            $this->updateSort($this->file_16); // file_16
            $this->updateSort($this->file_17); // file_17
            $this->updateSort($this->file_18); // file_18
            $this->updateSort($this->file_19); // file_19
            $this->updateSort($this->file_20); // file_20
            $this->updateSort($this->file_21); // file_21
            $this->updateSort($this->file_22); // file_22
            $this->updateSort($this->file_23); // file_23
            $this->updateSort($this->file_24); // file_24
            $this->updateSort($this->status); // status
            $this->updateSort($this->idd_user); // idd_user
            $this->setStartRecordNumber(1); // Reset start position
        }
    }

    // Load sort order parameters
    protected function loadSortOrder()
    {
        $orderBy = $this->getSessionOrderBy(); // Get ORDER BY from Session
        if ($orderBy == "") {
            $this->DefaultSort = "";
            if ($this->getSqlOrderBy() != "") {
                $useDefaultSort = true;
                if ($useDefaultSort) {
                    $orderBy = $this->getSqlOrderBy();
                    $this->setSessionOrderBy($orderBy);
                } else {
                    $this->setSessionOrderBy("");
                }
            }
        }
    }

    // Reset command
    // - cmd=reset (Reset search parameters)
    // - cmd=resetall (Reset search and master/detail parameters)
    // - cmd=resetsort (Reset sort parameters)
    protected function resetCmd()
    {
        // Check if reset command
        if (StartsString("reset", $this->Command)) {
            // Reset search criteria
            if ($this->Command == "reset" || $this->Command == "resetall") {
                $this->resetSearchParms();
            }

            // Reset (clear) sorting order
            if ($this->Command == "resetsort") {
                $orderBy = "";
                $this->setSessionOrderBy($orderBy);
                $this->idd_evaluasi->setSort("");
                $this->tanggal->setSort("");
                $this->kd_satker->setSort("");
                $this->idd_tahapan->setSort("");
                $this->tahun_anggaran->setSort("");
                $this->idd_wilayah->setSort("");
                $this->file_01->setSort("");
                $this->file_02->setSort("");
                $this->file_03->setSort("");
                $this->file_04->setSort("");
                $this->file_05->setSort("");
                $this->file_06->setSort("");
                $this->file_07->setSort("");
                $this->file_08->setSort("");
                $this->file_09->setSort("");
                $this->file_10->setSort("");
                $this->file_11->setSort("");
                $this->file_12->setSort("");
                $this->file_13->setSort("");
                $this->file_14->setSort("");
                $this->file_15->setSort("");
                $this->file_16->setSort("");
                $this->file_17->setSort("");
                $this->file_18->setSort("");
                $this->file_19->setSort("");
                $this->file_20->setSort("");
                $this->file_21->setSort("");
                $this->file_22->setSort("");
                $this->file_23->setSort("");
                $this->file_24->setSort("");
                $this->status->setSort("");
                $this->idd_user->setSort("");
            }

            // Reset start position
            $this->StartRecord = 1;
            $this->setStartRecordNumber($this->StartRecord);
        }
    }

    // Set up list options
    protected function setupListOptions()
    {
        global $Security, $Language;

        // Add group option item
        $item = &$this->ListOptions->add($this->ListOptions->GroupOptionName);
        $item->Body = "";
        $item->OnLeft = false;
        $item->Visible = false;

        // "view"
        $item = &$this->ListOptions->add("view");
        $item->CssClass = "text-nowrap";
        $item->Visible = $Security->canView();
        $item->OnLeft = false;

        // "edit"
        $item = &$this->ListOptions->add("edit");
        $item->CssClass = "text-nowrap";
        $item->Visible = $Security->canEdit();
        $item->OnLeft = false;

        // "copy"
        $item = &$this->ListOptions->add("copy");
        $item->CssClass = "text-nowrap";
        $item->Visible = $Security->canAdd();
        $item->OnLeft = false;

        // "delete"
        $item = &$this->ListOptions->add("delete");
        $item->CssClass = "text-nowrap";
        $item->Visible = $Security->canDelete();
        $item->OnLeft = false;

        // List actions
        $item = &$this->ListOptions->add("listactions");
        $item->CssClass = "text-nowrap";
        $item->OnLeft = false;
        $item->Visible = false;
        $item->ShowInButtonGroup = false;
        $item->ShowInDropDown = false;

        // "checkbox"
        $item = &$this->ListOptions->add("checkbox");
        $item->Visible = false;
        $item->OnLeft = false;
        $item->Header = "<div class=\"custom-control custom-checkbox d-inline-block\"><input type=\"checkbox\" name=\"key\" id=\"key\" class=\"custom-control-input\" onclick=\"ew.selectAllKey(this);\"><label class=\"custom-control-label\" for=\"key\"></label></div>";
        $item->ShowInDropDown = false;
        $item->ShowInButtonGroup = false;

        // Drop down button for ListOptions
        $this->ListOptions->UseDropDownButton = false;
        $this->ListOptions->DropDownButtonPhrase = $Language->phrase("ButtonListOptions");
        $this->ListOptions->UseButtonGroup = false;
        if ($this->ListOptions->UseButtonGroup && IsMobile()) {
            $this->ListOptions->UseDropDownButton = true;
        }

        //$this->ListOptions->ButtonClass = ""; // Class for button group

        // Call ListOptions_Load event
        $this->listOptionsLoad();
        $this->setupListOptionsExt();
        $item = $this->ListOptions[$this->ListOptions->GroupOptionName];
        $item->Visible = $this->ListOptions->groupOptionVisible();
    }

    // Render list options
    public function renderListOptions()
    {
        global $Security, $Language, $CurrentForm;
        $this->ListOptions->loadDefault();

        // Call ListOptions_Rendering event
        $this->listOptionsRendering();
        $pageUrl = $this->pageUrl();
        if ($this->CurrentMode == "view") {
            // "view"
            $opt = $this->ListOptions["view"];
            $viewcaption = HtmlTitle($Language->phrase("ViewLink"));
            if ($Security->canView()) {
                $opt->Body = "<a class=\"ew-row-link ew-view\" title=\"" . $viewcaption . "\" data-caption=\"" . $viewcaption . "\" href=\"" . HtmlEncode(GetUrl($this->ViewUrl)) . "\">" . $Language->phrase("ViewLink") . "</a>";
            } else {
                $opt->Body = "";
            }

            // "edit"
            $opt = $this->ListOptions["edit"];
            $editcaption = HtmlTitle($Language->phrase("EditLink"));
            if ($Security->canEdit()) {
                $opt->Body = "<a class=\"ew-row-link ew-edit\" title=\"" . HtmlTitle($Language->phrase("EditLink")) . "\" data-caption=\"" . HtmlTitle($Language->phrase("EditLink")) . "\" href=\"" . HtmlEncode(GetUrl($this->EditUrl)) . "\">" . $Language->phrase("EditLink") . "</a>";
            } else {
                $opt->Body = "";
            }

            // "copy"
            $opt = $this->ListOptions["copy"];
            $copycaption = HtmlTitle($Language->phrase("CopyLink"));
            if ($Security->canAdd()) {
                $opt->Body = "<a class=\"ew-row-link ew-copy\" title=\"" . $copycaption . "\" data-caption=\"" . $copycaption . "\" href=\"" . HtmlEncode(GetUrl($this->CopyUrl)) . "\">" . $Language->phrase("CopyLink") . "</a>";
            } else {
                $opt->Body = "";
            }

            // "delete"
            $opt = $this->ListOptions["delete"];
            if ($Security->canDelete()) {
            $opt->Body = "<a class=\"ew-row-link ew-delete\"" . "" . " title=\"" . HtmlTitle($Language->phrase("DeleteLink")) . "\" data-caption=\"" . HtmlTitle($Language->phrase("DeleteLink")) . "\" href=\"" . HtmlEncode(GetUrl($this->DeleteUrl)) . "\">" . $Language->phrase("DeleteLink") . "</a>";
            } else {
                $opt->Body = "";
            }
        } // End View mode

        // Set up list action buttons
        $opt = $this->ListOptions["listactions"];
        if ($opt && !$this->isExport() && !$this->CurrentAction) {
            $body = "";
            $links = [];
            foreach ($this->ListActions->Items as $listaction) {
                if ($listaction->Select == ACTION_SINGLE && $listaction->Allow) {
                    $action = $listaction->Action;
                    $caption = $listaction->Caption;
                    $icon = ($listaction->Icon != "") ? "<i class=\"" . HtmlEncode(str_replace(" ew-icon", "", $listaction->Icon)) . "\" data-caption=\"" . HtmlTitle($caption) . "\"></i> " : "";
                    $links[] = "<li><a class=\"dropdown-item ew-action ew-list-action\" data-action=\"" . HtmlEncode($action) . "\" data-caption=\"" . HtmlTitle($caption) . "\" href=\"#\" onclick=\"return ew.submitAction(event,jQuery.extend({key:" . $this->keyToJson(true) . "}," . $listaction->toJson(true) . "));\">" . $icon . $listaction->Caption . "</a></li>";
                    if (count($links) == 1) { // Single button
                        $body = "<a class=\"ew-action ew-list-action\" data-action=\"" . HtmlEncode($action) . "\" title=\"" . HtmlTitle($caption) . "\" data-caption=\"" . HtmlTitle($caption) . "\" href=\"#\" onclick=\"return ew.submitAction(event,jQuery.extend({key:" . $this->keyToJson(true) . "}," . $listaction->toJson(true) . "));\">" . $icon . $listaction->Caption . "</a>";
                    }
                }
            }
            if (count($links) > 1) { // More than one buttons, use dropdown
                $body = "<button class=\"dropdown-toggle btn btn-default ew-actions\" title=\"" . HtmlTitle($Language->phrase("ListActionButton")) . "\" data-toggle=\"dropdown\">" . $Language->phrase("ListActionButton") . "</button>";
                $content = "";
                foreach ($links as $link) {
                    $content .= "<li>" . $link . "</li>";
                }
                $body .= "<ul class=\"dropdown-menu" . ($opt->OnLeft ? "" : " dropdown-menu-right") . "\">" . $content . "</ul>";
                $body = "<div class=\"btn-group btn-group-sm\">" . $body . "</div>";
            }
            if (count($links) > 0) {
                $opt->Body = $body;
                $opt->Visible = true;
            }
        }

        // "checkbox"
        $opt = $this->ListOptions["checkbox"];
        $opt->Body = "<div class=\"custom-control custom-checkbox d-inline-block\"><input type=\"checkbox\" id=\"key_m_" . $this->RowCount . "\" name=\"key_m[]\" class=\"custom-control-input ew-multi-select\" value=\"" . HtmlEncode($this->idd_evaluasi->CurrentValue) . "\" onclick=\"ew.clickMultiCheckbox(event);\"><label class=\"custom-control-label\" for=\"key_m_" . $this->RowCount . "\"></label></div>";
        $this->renderListOptionsExt();

        // Call ListOptions_Rendered event
        $this->listOptionsRendered();
    }

    // Set up other options
    protected function setupOtherOptions()
    {
        global $Language, $Security;
        $options = &$this->OtherOptions;
        $option = $options["addedit"];

        // Add
        $item = &$option->add("add");
        $addcaption = HtmlTitle($Language->phrase("AddLink"));
        $item->Body = "<a class=\"ew-add-edit ew-add\" title=\"" . $addcaption . "\" data-caption=\"" . $addcaption . "\" href=\"" . HtmlEncode(GetUrl($this->AddUrl)) . "\">" . $Language->phrase("AddLink") . "</a>";
        $item->Visible = $this->AddUrl != "" && $Security->canAdd();
        $option = $options["action"];

        // Set up options default
        foreach ($options as $option) {
            $option->UseDropDownButton = false;
            $option->UseButtonGroup = true;
            //$option->ButtonClass = ""; // Class for button group
            $item = &$option->add($option->GroupOptionName);
            $item->Body = "";
            $item->Visible = false;
        }
        $options["addedit"]->DropDownButtonPhrase = $Language->phrase("ButtonAddEdit");
        $options["detail"]->DropDownButtonPhrase = $Language->phrase("ButtonDetails");
        $options["action"]->DropDownButtonPhrase = $Language->phrase("ButtonActions");

        // Filter button
        $item = &$this->FilterOptions->add("savecurrentfilter");
        $item->Body = "<a class=\"ew-save-filter\" data-form=\"fapbklistsrch\" href=\"#\" onclick=\"return false;\">" . $Language->phrase("SaveCurrentFilter") . "</a>";
        $item->Visible = true;
        $item = &$this->FilterOptions->add("deletefilter");
        $item->Body = "<a class=\"ew-delete-filter\" data-form=\"fapbklistsrch\" href=\"#\" onclick=\"return false;\">" . $Language->phrase("DeleteFilter") . "</a>";
        $item->Visible = true;
        $this->FilterOptions->UseDropDownButton = true;
        $this->FilterOptions->UseButtonGroup = !$this->FilterOptions->UseDropDownButton;
        $this->FilterOptions->DropDownButtonPhrase = $Language->phrase("Filters");

        // Add group option item
        $item = &$this->FilterOptions->add($this->FilterOptions->GroupOptionName);
        $item->Body = "";
        $item->Visible = false;
    }

    // Render other options
    public function renderOtherOptions()
    {
        global $Language, $Security;
        $options = &$this->OtherOptions;
        $option = $options["action"];
        // Set up list action buttons
        foreach ($this->ListActions->Items as $listaction) {
            if ($listaction->Select == ACTION_MULTIPLE) {
                $item = &$option->add("custom_" . $listaction->Action);
                $caption = $listaction->Caption;
                $icon = ($listaction->Icon != "") ? '<i class="' . HtmlEncode($listaction->Icon) . '" data-caption="' . HtmlEncode($caption) . '"></i>' . $caption : $caption;
                $item->Body = '<a class="ew-action ew-list-action" title="' . HtmlEncode($caption) . '" data-caption="' . HtmlEncode($caption) . '" href="#" onclick="return ew.submitAction(event,jQuery.extend({f:document.fapbklist},' . $listaction->toJson(true) . '));">' . $icon . '</a>';
                $item->Visible = $listaction->Allow;
            }
        }

        // Hide grid edit and other options
        if ($this->TotalRecords <= 0) {
            $option = $options["addedit"];
            $item = $option["gridedit"];
            if ($item) {
                $item->Visible = false;
            }
            $option = $options["action"];
            $option->hideAllOptions();
        }
    }

    // Process list action
    protected function processListAction()
    {
        global $Language, $Security;
        $userlist = "";
        $user = "";
        $filter = $this->getFilterFromRecordKeys();
        $userAction = Post("useraction", "");
        if ($filter != "" && $userAction != "") {
            // Check permission first
            $actionCaption = $userAction;
            if (array_key_exists($userAction, $this->ListActions->Items)) {
                $actionCaption = $this->ListActions[$userAction]->Caption;
                if (!$this->ListActions[$userAction]->Allow) {
                    $errmsg = str_replace('%s', $actionCaption, $Language->phrase("CustomActionNotAllowed"));
                    if (Post("ajax") == $userAction) { // Ajax
                        echo "<p class=\"text-danger\">" . $errmsg . "</p>";
                        return true;
                    } else {
                        $this->setFailureMessage($errmsg);
                        return false;
                    }
                }
            }
            $this->CurrentFilter = $filter;
            $sql = $this->getCurrentSql();
            $conn = $this->getConnection();
            $rs = LoadRecordset($sql, $conn, \PDO::FETCH_ASSOC);
            $this->CurrentAction = $userAction;

            // Call row action event
            if ($rs) {
                $conn->beginTransaction();
                $this->SelectedCount = $rs->recordCount();
                $this->SelectedIndex = 0;
                while (!$rs->EOF) {
                    $this->SelectedIndex++;
                    $row = $rs->fields;
                    $processed = $this->rowCustomAction($userAction, $row);
                    if (!$processed) {
                        break;
                    }
                    $rs->moveNext();
                }
                if ($processed) {
                    $conn->commit(); // Commit the changes
                    if ($this->getSuccessMessage() == "" && !ob_get_length()) { // No output
                        $this->setSuccessMessage(str_replace('%s', $actionCaption, $Language->phrase("CustomActionCompleted"))); // Set up success message
                    }
                } else {
                    $conn->rollback(); // Rollback changes

                    // Set up error message
                    if ($this->getSuccessMessage() != "" || $this->getFailureMessage() != "") {
                        // Use the message, do nothing
                    } elseif ($this->CancelMessage != "") {
                        $this->setFailureMessage($this->CancelMessage);
                        $this->CancelMessage = "";
                    } else {
                        $this->setFailureMessage(str_replace('%s', $actionCaption, $Language->phrase("CustomActionFailed")));
                    }
                }
            }
            if ($rs) {
                $rs->close();
            }
            $this->CurrentAction = ""; // Clear action
            if (Post("ajax") == $userAction) { // Ajax
                if ($this->getSuccessMessage() != "") {
                    echo "<p class=\"text-success\">" . $this->getSuccessMessage() . "</p>";
                    $this->clearSuccessMessage(); // Clear message
                }
                if ($this->getFailureMessage() != "") {
                    echo "<p class=\"text-danger\">" . $this->getFailureMessage() . "</p>";
                    $this->clearFailureMessage(); // Clear message
                }
                return true;
            }
        }
        return false; // Not ajax request
    }

    // Set up list options (extended codes)
    protected function setupListOptionsExt()
    {
    }

    // Render list options (extended codes)
    protected function renderListOptionsExt()
    {
    }

    // Load basic search values
    protected function loadBasicSearchValues()
    {
        $this->BasicSearch->setKeyword(Get(Config("TABLE_BASIC_SEARCH"), ""), false);
        if ($this->BasicSearch->Keyword != "" && $this->Command == "") {
            $this->Command = "search";
        }
        $this->BasicSearch->setType(Get(Config("TABLE_BASIC_SEARCH_TYPE"), ""), false);
    }

    // Load recordset
    public function loadRecordset($offset = -1, $rowcnt = -1)
    {
        // Load List page SQL (QueryBuilder)
        $sql = $this->getListSql();

        // Load recordset
        if ($offset > -1) {
            $sql->setFirstResult($offset);
        }
        if ($rowcnt > 0) {
            $sql->setMaxResults($rowcnt);
        }
        $stmt = $sql->execute();
        $rs = new Recordset($stmt, $sql);

        // Call Recordset Selected event
        $this->recordsetSelected($rs);
        return $rs;
    }

    /**
     * Load row based on key values
     *
     * @return void
     */
    public function loadRow()
    {
        global $Security, $Language;
        $filter = $this->getRecordFilter();

        // Call Row Selecting event
        $this->rowSelecting($filter);

        // Load SQL based on filter
        $this->CurrentFilter = $filter;
        $sql = $this->getCurrentSql();
        $conn = $this->getConnection();
        $res = false;
        $row = $conn->fetchAssoc($sql);
        if ($row) {
            $res = true;
            $this->loadRowValues($row); // Load row values
        }
        return $res;
    }

    /**
     * Load row values from recordset or record
     *
     * @param Recordset|array $rs Record
     * @return void
     */
    public function loadRowValues($rs = null)
    {
        if (is_array($rs)) {
            $row = $rs;
        } elseif ($rs && property_exists($rs, "fields")) { // Recordset
            $row = $rs->fields;
        } else {
            $row = $this->newRow();
        }

        // Call Row Selected event
        $this->rowSelected($row);
        if (!$rs) {
            return;
        }
        $this->idd_evaluasi->setDbValue($row['idd_evaluasi']);
        $this->tanggal->setDbValue($row['tanggal']);
        $this->kd_satker->setDbValue($row['kd_satker']);
        $this->idd_tahapan->setDbValue($row['idd_tahapan']);
        $this->tahun_anggaran->setDbValue($row['tahun_anggaran']);
        $this->idd_wilayah->setDbValue($row['idd_wilayah']);
        $this->file_01->setDbValue($row['file_01']);
        $this->file_02->setDbValue($row['file_02']);
        $this->file_03->setDbValue($row['file_03']);
        $this->file_04->setDbValue($row['file_04']);
        $this->file_05->setDbValue($row['file_05']);
        $this->file_06->setDbValue($row['file_06']);
        $this->file_07->setDbValue($row['file_07']);
        $this->file_08->setDbValue($row['file_08']);
        $this->file_09->setDbValue($row['file_09']);
        $this->file_10->setDbValue($row['file_10']);
        $this->file_11->setDbValue($row['file_11']);
        $this->file_12->setDbValue($row['file_12']);
        $this->file_13->setDbValue($row['file_13']);
        $this->file_14->setDbValue($row['file_14']);
        $this->file_15->setDbValue($row['file_15']);
        $this->file_16->setDbValue($row['file_16']);
        $this->file_17->setDbValue($row['file_17']);
        $this->file_18->setDbValue($row['file_18']);
        $this->file_19->setDbValue($row['file_19']);
        $this->file_20->setDbValue($row['file_20']);
        $this->file_21->setDbValue($row['file_21']);
        $this->file_22->setDbValue($row['file_22']);
        $this->file_23->setDbValue($row['file_23']);
        $this->file_24->setDbValue($row['file_24']);
        $this->status->setDbValue($row['status']);
        $this->idd_user->setDbValue($row['idd_user']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['idd_evaluasi'] = null;
        $row['tanggal'] = null;
        $row['kd_satker'] = null;
        $row['idd_tahapan'] = null;
        $row['tahun_anggaran'] = null;
        $row['idd_wilayah'] = null;
        $row['file_01'] = null;
        $row['file_02'] = null;
        $row['file_03'] = null;
        $row['file_04'] = null;
        $row['file_05'] = null;
        $row['file_06'] = null;
        $row['file_07'] = null;
        $row['file_08'] = null;
        $row['file_09'] = null;
        $row['file_10'] = null;
        $row['file_11'] = null;
        $row['file_12'] = null;
        $row['file_13'] = null;
        $row['file_14'] = null;
        $row['file_15'] = null;
        $row['file_16'] = null;
        $row['file_17'] = null;
        $row['file_18'] = null;
        $row['file_19'] = null;
        $row['file_20'] = null;
        $row['file_21'] = null;
        $row['file_22'] = null;
        $row['file_23'] = null;
        $row['file_24'] = null;
        $row['status'] = null;
        $row['idd_user'] = null;
        return $row;
    }

    // Load old record
    protected function loadOldRecord()
    {
        // Load old record
        $this->OldRecordset = null;
        $validKey = $this->OldKey != "";
        if ($validKey) {
            $this->CurrentFilter = $this->getRecordFilter();
            $sql = $this->getCurrentSql();
            $conn = $this->getConnection();
            $this->OldRecordset = LoadRecordset($sql, $conn);
        }
        $this->loadRowValues($this->OldRecordset); // Load row values
        return $validKey;
    }

    // Render row values based on field settings
    public function renderRow()
    {
        global $Security, $Language, $CurrentLanguage;

        // Initialize URLs
        $this->ViewUrl = $this->getViewUrl();
        $this->EditUrl = $this->getEditUrl();
        $this->InlineEditUrl = $this->getInlineEditUrl();
        $this->CopyUrl = $this->getCopyUrl();
        $this->InlineCopyUrl = $this->getInlineCopyUrl();
        $this->DeleteUrl = $this->getDeleteUrl();

        // Call Row_Rendering event
        $this->rowRendering();

        // Common render codes for all row types

        // idd_evaluasi

        // tanggal

        // kd_satker

        // idd_tahapan

        // tahun_anggaran

        // idd_wilayah

        // file_01

        // file_02

        // file_03

        // file_04

        // file_05

        // file_06

        // file_07

        // file_08

        // file_09

        // file_10

        // file_11

        // file_12

        // file_13

        // file_14

        // file_15

        // file_16

        // file_17

        // file_18

        // file_19

        // file_20

        // file_21

        // file_22

        // file_23

        // file_24

        // status

        // idd_user
        if ($this->RowType == ROWTYPE_VIEW) {
            // idd_evaluasi
            $this->idd_evaluasi->ViewValue = $this->idd_evaluasi->CurrentValue;
            $this->idd_evaluasi->ViewCustomAttributes = "";

            // tanggal
            $this->tanggal->ViewValue = $this->tanggal->CurrentValue;
            $this->tanggal->ViewValue = FormatDateTime($this->tanggal->ViewValue, 0);
            $this->tanggal->ViewCustomAttributes = "";

            // kd_satker
            $this->kd_satker->ViewValue = $this->kd_satker->CurrentValue;
            $this->kd_satker->ViewCustomAttributes = "";

            // idd_tahapan
            $this->idd_tahapan->ViewValue = $this->idd_tahapan->CurrentValue;
            $this->idd_tahapan->ViewValue = FormatNumber($this->idd_tahapan->ViewValue, 0, -2, -2, -2);
            $this->idd_tahapan->ViewCustomAttributes = "";

            // tahun_anggaran
            $this->tahun_anggaran->ViewValue = $this->tahun_anggaran->CurrentValue;
            $this->tahun_anggaran->ViewCustomAttributes = "";

            // idd_wilayah
            $this->idd_wilayah->ViewValue = $this->idd_wilayah->CurrentValue;
            $this->idd_wilayah->ViewValue = FormatNumber($this->idd_wilayah->ViewValue, 0, -2, -2, -2);
            $this->idd_wilayah->ViewCustomAttributes = "";

            // file_01
            $this->file_01->ViewValue = $this->file_01->CurrentValue;
            $this->file_01->ViewCustomAttributes = "";

            // file_02
            $this->file_02->ViewValue = $this->file_02->CurrentValue;
            $this->file_02->ViewCustomAttributes = "";

            // file_03
            $this->file_03->ViewValue = $this->file_03->CurrentValue;
            $this->file_03->ViewCustomAttributes = "";

            // file_04
            $this->file_04->ViewValue = $this->file_04->CurrentValue;
            $this->file_04->ViewCustomAttributes = "";

            // file_05
            $this->file_05->ViewValue = $this->file_05->CurrentValue;
            $this->file_05->ViewCustomAttributes = "";

            // file_06
            $this->file_06->ViewValue = $this->file_06->CurrentValue;
            $this->file_06->ViewCustomAttributes = "";

            // file_07
            $this->file_07->ViewValue = $this->file_07->CurrentValue;
            $this->file_07->ViewCustomAttributes = "";

            // file_08
            $this->file_08->ViewValue = $this->file_08->CurrentValue;
            $this->file_08->ViewCustomAttributes = "";

            // file_09
            $this->file_09->ViewValue = $this->file_09->CurrentValue;
            $this->file_09->ViewCustomAttributes = "";

            // file_10
            $this->file_10->ViewValue = $this->file_10->CurrentValue;
            $this->file_10->ViewCustomAttributes = "";

            // file_11
            $this->file_11->ViewValue = $this->file_11->CurrentValue;
            $this->file_11->ViewCustomAttributes = "";

            // file_12
            $this->file_12->ViewValue = $this->file_12->CurrentValue;
            $this->file_12->ViewCustomAttributes = "";

            // file_13
            $this->file_13->ViewValue = $this->file_13->CurrentValue;
            $this->file_13->ViewCustomAttributes = "";

            // file_14
            $this->file_14->ViewValue = $this->file_14->CurrentValue;
            $this->file_14->ViewCustomAttributes = "";

            // file_15
            $this->file_15->ViewValue = $this->file_15->CurrentValue;
            $this->file_15->ViewCustomAttributes = "";

            // file_16
            $this->file_16->ViewValue = $this->file_16->CurrentValue;
            $this->file_16->ViewCustomAttributes = "";

            // file_17
            $this->file_17->ViewValue = $this->file_17->CurrentValue;
            $this->file_17->ViewCustomAttributes = "";

            // file_18
            $this->file_18->ViewValue = $this->file_18->CurrentValue;
            $this->file_18->ViewCustomAttributes = "";

            // file_19
            $this->file_19->ViewValue = $this->file_19->CurrentValue;
            $this->file_19->ViewCustomAttributes = "";

            // file_20
            $this->file_20->ViewValue = $this->file_20->CurrentValue;
            $this->file_20->ViewCustomAttributes = "";

            // file_21
            $this->file_21->ViewValue = $this->file_21->CurrentValue;
            $this->file_21->ViewCustomAttributes = "";

            // file_22
            $this->file_22->ViewValue = $this->file_22->CurrentValue;
            $this->file_22->ViewCustomAttributes = "";

            // file_23
            $this->file_23->ViewValue = $this->file_23->CurrentValue;
            $this->file_23->ViewCustomAttributes = "";

            // file_24
            $this->file_24->ViewValue = $this->file_24->CurrentValue;
            $this->file_24->ViewCustomAttributes = "";

            // status
            $this->status->ViewValue = $this->status->CurrentValue;
            $this->status->ViewValue = FormatNumber($this->status->ViewValue, 0, -2, -2, -2);
            $this->status->ViewCustomAttributes = "";

            // idd_user
            $this->idd_user->ViewValue = $this->idd_user->CurrentValue;
            $this->idd_user->ViewValue = FormatNumber($this->idd_user->ViewValue, 0, -2, -2, -2);
            $this->idd_user->ViewCustomAttributes = "";

            // idd_evaluasi
            $this->idd_evaluasi->LinkCustomAttributes = "";
            $this->idd_evaluasi->HrefValue = "";
            $this->idd_evaluasi->TooltipValue = "";

            // tanggal
            $this->tanggal->LinkCustomAttributes = "";
            $this->tanggal->HrefValue = "";
            $this->tanggal->TooltipValue = "";

            // kd_satker
            $this->kd_satker->LinkCustomAttributes = "";
            $this->kd_satker->HrefValue = "";
            $this->kd_satker->TooltipValue = "";

            // idd_tahapan
            $this->idd_tahapan->LinkCustomAttributes = "";
            $this->idd_tahapan->HrefValue = "";
            $this->idd_tahapan->TooltipValue = "";

            // tahun_anggaran
            $this->tahun_anggaran->LinkCustomAttributes = "";
            $this->tahun_anggaran->HrefValue = "";
            $this->tahun_anggaran->TooltipValue = "";

            // idd_wilayah
            $this->idd_wilayah->LinkCustomAttributes = "";
            $this->idd_wilayah->HrefValue = "";
            $this->idd_wilayah->TooltipValue = "";

            // file_01
            $this->file_01->LinkCustomAttributes = "";
            $this->file_01->HrefValue = "";
            $this->file_01->TooltipValue = "";

            // file_02
            $this->file_02->LinkCustomAttributes = "";
            $this->file_02->HrefValue = "";
            $this->file_02->TooltipValue = "";

            // file_03
            $this->file_03->LinkCustomAttributes = "";
            $this->file_03->HrefValue = "";
            $this->file_03->TooltipValue = "";

            // file_04
            $this->file_04->LinkCustomAttributes = "";
            $this->file_04->HrefValue = "";
            $this->file_04->TooltipValue = "";

            // file_05
            $this->file_05->LinkCustomAttributes = "";
            $this->file_05->HrefValue = "";
            $this->file_05->TooltipValue = "";

            // file_06
            $this->file_06->LinkCustomAttributes = "";
            $this->file_06->HrefValue = "";
            $this->file_06->TooltipValue = "";

            // file_07
            $this->file_07->LinkCustomAttributes = "";
            $this->file_07->HrefValue = "";
            $this->file_07->TooltipValue = "";

            // file_08
            $this->file_08->LinkCustomAttributes = "";
            $this->file_08->HrefValue = "";
            $this->file_08->TooltipValue = "";

            // file_09
            $this->file_09->LinkCustomAttributes = "";
            $this->file_09->HrefValue = "";
            $this->file_09->TooltipValue = "";

            // file_10
            $this->file_10->LinkCustomAttributes = "";
            $this->file_10->HrefValue = "";
            $this->file_10->TooltipValue = "";

            // file_11
            $this->file_11->LinkCustomAttributes = "";
            $this->file_11->HrefValue = "";
            $this->file_11->TooltipValue = "";

            // file_12
            $this->file_12->LinkCustomAttributes = "";
            $this->file_12->HrefValue = "";
            $this->file_12->TooltipValue = "";

            // file_13
            $this->file_13->LinkCustomAttributes = "";
            $this->file_13->HrefValue = "";
            $this->file_13->TooltipValue = "";

            // file_14
            $this->file_14->LinkCustomAttributes = "";
            $this->file_14->HrefValue = "";
            $this->file_14->TooltipValue = "";

            // file_15
            $this->file_15->LinkCustomAttributes = "";
            $this->file_15->HrefValue = "";
            $this->file_15->TooltipValue = "";

            // file_16
            $this->file_16->LinkCustomAttributes = "";
            $this->file_16->HrefValue = "";
            $this->file_16->TooltipValue = "";

            // file_17
            $this->file_17->LinkCustomAttributes = "";
            $this->file_17->HrefValue = "";
            $this->file_17->TooltipValue = "";

            // file_18
            $this->file_18->LinkCustomAttributes = "";
            $this->file_18->HrefValue = "";
            $this->file_18->TooltipValue = "";

            // file_19
            $this->file_19->LinkCustomAttributes = "";
            $this->file_19->HrefValue = "";
            $this->file_19->TooltipValue = "";

            // file_20
            $this->file_20->LinkCustomAttributes = "";
            $this->file_20->HrefValue = "";
            $this->file_20->TooltipValue = "";

            // file_21
            $this->file_21->LinkCustomAttributes = "";
            $this->file_21->HrefValue = "";
            $this->file_21->TooltipValue = "";

            // file_22
            $this->file_22->LinkCustomAttributes = "";
            $this->file_22->HrefValue = "";
            $this->file_22->TooltipValue = "";

            // file_23
            $this->file_23->LinkCustomAttributes = "";
            $this->file_23->HrefValue = "";
            $this->file_23->TooltipValue = "";

            // file_24
            $this->file_24->LinkCustomAttributes = "";
            $this->file_24->HrefValue = "";
            $this->file_24->TooltipValue = "";

            // status
            $this->status->LinkCustomAttributes = "";
            $this->status->HrefValue = "";
            $this->status->TooltipValue = "";

            // idd_user
            $this->idd_user->LinkCustomAttributes = "";
            $this->idd_user->HrefValue = "";
            $this->idd_user->TooltipValue = "";
        }

        // Call Row Rendered event
        if ($this->RowType != ROWTYPE_AGGREGATEINIT) {
            $this->rowRendered();
        }
    }

    // Set up search options
    protected function setupSearchOptions()
    {
        global $Language, $Security;
        $pageUrl = $this->pageUrl();
        $this->SearchOptions = new ListOptions("div");
        $this->SearchOptions->TagClassName = "ew-search-option";

        // Search button
        $item = &$this->SearchOptions->add("searchtoggle");
        $searchToggleClass = ($this->SearchWhere != "") ? " active" : " active";
        $item->Body = "<a class=\"btn btn-default ew-search-toggle" . $searchToggleClass . "\" href=\"#\" role=\"button\" title=\"" . $Language->phrase("SearchPanel") . "\" data-caption=\"" . $Language->phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fapbklistsrch\" aria-pressed=\"" . ($searchToggleClass == " active" ? "true" : "false") . "\">" . $Language->phrase("SearchLink") . "</a>";
        $item->Visible = true;

        // Show all button
        $item = &$this->SearchOptions->add("showall");
        $item->Body = "<a class=\"btn btn-default ew-show-all\" title=\"" . $Language->phrase("ShowAll") . "\" data-caption=\"" . $Language->phrase("ShowAll") . "\" href=\"" . $pageUrl . "cmd=reset\">" . $Language->phrase("ShowAllBtn") . "</a>";
        $item->Visible = ($this->SearchWhere != $this->DefaultSearchWhere && $this->SearchWhere != "0=101");

        // Button group for search
        $this->SearchOptions->UseDropDownButton = false;
        $this->SearchOptions->UseButtonGroup = true;
        $this->SearchOptions->DropDownButtonPhrase = $Language->phrase("ButtonSearch");

        // Add group option item
        $item = &$this->SearchOptions->add($this->SearchOptions->GroupOptionName);
        $item->Body = "";
        $item->Visible = false;

        // Hide search options
        if ($this->isExport() || $this->CurrentAction) {
            $this->SearchOptions->hideAllOptions();
        }
        if (!$Security->canSearch()) {
            $this->SearchOptions->hideAllOptions();
            $this->FilterOptions->hideAllOptions();
        }
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("index");
        $url = CurrentUrl();
        $url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset / cmd=resetall
        $Breadcrumb->add("list", $this->TableVar, $url, "", $this->TableVar, true);
    }

    // Setup lookup options
    public function setupLookupOptions($fld)
    {
        if ($fld->Lookup !== null && $fld->Lookup->Options === null) {
            // Get default connection and filter
            $conn = $this->getConnection();
            $lookupFilter = "";

            // No need to check any more
            $fld->Lookup->Options = [];

            // Set up lookup SQL and connection
            switch ($fld->FieldVar) {
                default:
                    $lookupFilter = "";
                    break;
            }

            // Always call to Lookup->getSql so that user can setup Lookup->Options in Lookup_Selecting server event
            $sql = $fld->Lookup->getSql(false, "", $lookupFilter, $this);

            // Set up lookup cache
            if ($fld->UseLookupCache && $sql != "" && count($fld->Lookup->Options) == 0) {
                $totalCnt = $this->getRecordCount($sql, $conn);
                if ($totalCnt > $fld->LookupCacheCount) { // Total count > cache count, do not cache
                    return;
                }
                $rows = $conn->executeQuery($sql)->fetchAll(\PDO::FETCH_BOTH);
                $ar = [];
                foreach ($rows as $row) {
                    $row = $fld->Lookup->renderViewRow($row);
                    $ar[strval($row[0])] = $row;
                }
                $fld->Lookup->Options = $ar;
            }
        }
    }

    // Set up starting record parameters
    public function setupStartRecord()
    {
        if ($this->DisplayRecords == 0) {
            return;
        }
        if ($this->isPageRequest()) { // Validate request
            $startRec = Get(Config("TABLE_START_REC"));
            $pageNo = Get(Config("TABLE_PAGE_NO"));
            if ($pageNo !== null) { // Check for "pageno" parameter first
                if (is_numeric($pageNo)) {
                    $this->StartRecord = ($pageNo - 1) * $this->DisplayRecords + 1;
                    if ($this->StartRecord <= 0) {
                        $this->StartRecord = 1;
                    } elseif ($this->StartRecord >= (int)(($this->TotalRecords - 1) / $this->DisplayRecords) * $this->DisplayRecords + 1) {
                        $this->StartRecord = (int)(($this->TotalRecords - 1) / $this->DisplayRecords) * $this->DisplayRecords + 1;
                    }
                    $this->setStartRecordNumber($this->StartRecord);
                }
            } elseif ($startRec !== null) { // Check for "start" parameter
                $this->StartRecord = $startRec;
                $this->setStartRecordNumber($this->StartRecord);
            }
        }
        $this->StartRecord = $this->getStartRecordNumber();

        // Check if correct start record counter
        if (!is_numeric($this->StartRecord) || $this->StartRecord == "") { // Avoid invalid start record counter
            $this->StartRecord = 1; // Reset start record counter
            $this->setStartRecordNumber($this->StartRecord);
        } elseif ($this->StartRecord > $this->TotalRecords) { // Avoid starting record > total records
            $this->StartRecord = (int)(($this->TotalRecords - 1) / $this->DisplayRecords) * $this->DisplayRecords + 1; // Point to last page first record
            $this->setStartRecordNumber($this->StartRecord);
        } elseif (($this->StartRecord - 1) % $this->DisplayRecords != 0) {
            $this->StartRecord = (int)(($this->StartRecord - 1) / $this->DisplayRecords) * $this->DisplayRecords + 1; // Point to page boundary
            $this->setStartRecordNumber($this->StartRecord);
        }
    }

    // Page Load event
    public function pageLoad()
    {
        //Log("Page Load");
    }

    // Page Unload event
    public function pageUnload()
    {
        //Log("Page Unload");
    }

    // Page Redirecting event
    public function pageRedirecting(&$url)
    {
        // Example:
        //$url = "your URL";
    }

    // Message Showing event
    // $type = ''|'success'|'failure'|'warning'
    public function messageShowing(&$msg, $type)
    {
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
    public function pageRender()
    {
        //Log("Page Render");
    }

    // Page Data Rendering event
    public function pageDataRendering(&$header)
    {
        // Example:
        //$header = "your header";
    }

    // Page Data Rendered event
    public function pageDataRendered(&$footer)
    {
        // Example:
        //$footer = "your footer";
    }

    // Form Custom Validate event
    public function formCustomValidate(&$customError)
    {
        // Return error message in CustomError
        return true;
    }

    // ListOptions Load event
    public function listOptionsLoad()
    {
        // Example:
        //$opt = &$this->ListOptions->Add("new");
        //$opt->Header = "xxx";
        //$opt->OnLeft = true; // Link on left
        //$opt->MoveTo(0); // Move to first column
    }

    // ListOptions Rendering event
    public function listOptionsRendering()
    {
        //Container("DetailTableGrid")->DetailAdd = (...condition...); // Set to true or false conditionally
        //Container("DetailTableGrid")->DetailEdit = (...condition...); // Set to true or false conditionally
        //Container("DetailTableGrid")->DetailView = (...condition...); // Set to true or false conditionally
    }

    // ListOptions Rendered event
    public function listOptionsRendered()
    {
        // Example:
        //$this->ListOptions["new"]->Body = "xxx";
    }

    // Row Custom Action event
    public function rowCustomAction($action, $row)
    {
        // Return false to abort
        return true;
    }

    // Page Exporting event
    // $this->ExportDoc = export document object
    public function pageExporting()
    {
        //$this->ExportDoc->Text = "my header"; // Export header
        //return false; // Return false to skip default export and use Row_Export event
        return true; // Return true to use default export and skip Row_Export event
    }

    // Row Export event
    // $this->ExportDoc = export document object
    public function rowExport($rs)
    {
        //$this->ExportDoc->Text .= "my content"; // Build HTML with field value: $rs["MyField"] or $this->MyField->ViewValue
    }

    // Page Exported event
    // $this->ExportDoc = export document object
    public function pageExported()
    {
        //$this->ExportDoc->Text .= "my footer"; // Export footer
        //Log($this->ExportDoc->Text);
    }

    // Page Importing event
    public function pageImporting($reader, &$options)
    {
        //var_dump($reader); // Import data reader
        //var_dump($options); // Show all options for importing
        //return false; // Return false to skip import
        return true;
    }

    // Row Import event
    public function rowImport(&$row, $cnt)
    {
        //Log($cnt); // Import record count
        //var_dump($row); // Import row
        //return false; // Return false to skip import
        return true;
    }

    // Page Imported event
    public function pageImported($reader, $results)
    {
        //var_dump($reader); // Import data reader
        //var_dump($results); // Import results
    }
}
