<?php

namespace PHPMaker2021\silpa;

use Doctrine\DBAL\ParameterType;

/**
 * Page class
 */
class EvaluasiList extends Evaluasi
{
    use MessagesTrait;

    // Page ID
    public $PageID = "list";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'evaluasi';

    // Page object name
    public $PageObjName = "EvaluasiList";

    // Rendering View
    public $RenderingView = false;

    // Grid form hidden field names
    public $FormName = "fevaluasilist";
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

        // Table object (evaluasi)
        if (!isset($GLOBALS["evaluasi"]) || get_class($GLOBALS["evaluasi"]) == PROJECT_NAMESPACE . "evaluasi") {
            $GLOBALS["evaluasi"] = &$this;
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
        $this->AddUrl = "evaluasiadd";
        $this->InlineAddUrl = $pageUrl . "action=add";
        $this->GridAddUrl = $pageUrl . "action=gridadd";
        $this->GridEditUrl = $pageUrl . "action=gridedit";
        $this->MultiDeleteUrl = "evaluasidelete";
        $this->MultiUpdateUrl = "evaluasiupdate";

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'evaluasi');
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
        $this->FilterOptions->TagClassName = "ew-filter-option fevaluasilistsrch";

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
                $doc = new $class(Container("evaluasi"));
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
        $this->idd_wilayah->setVisibility();
        $this->kd_satker->setVisibility();
        $this->idd_tahapan->setVisibility();
        $this->tahun_anggaran->setVisibility();
        $this->surat_pengantar->setVisibility();
        $this->rpjmd->setVisibility();
        $this->rkpk->setVisibility();
        $this->skd_rkuappas->setVisibility();
        $this->kua->setVisibility();
        $this->ppas->setVisibility();
        $this->skd_rqanun->setVisibility();
        $this->nota_keuangan->setVisibility();
        $this->pengantar_nota->setVisibility();
        $this->risalah_sidang->setVisibility();
        $this->bap_apbk->setVisibility();
        $this->rq_apbk->setVisibility();
        $this->rp_penjabaran->setVisibility();
        $this->jadwal_proses->setVisibility();
        $this->sinkron_kebijakan->setVisibility();
        $this->konsistensi_program->setVisibility();
        $this->alokasi_pendidikan->setVisibility();
        $this->alokasi_kesehatan->setVisibility();
        $this->alokasi_belanja->setVisibility();
        $this->bak_kegiatan->setVisibility();
        $this->softcopy_rka->setVisibility();
        $this->otsus->setVisibility();
        $this->qanun_perbup->setVisibility();
        $this->tindak_apbkp->setVisibility();
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
        $this->setupLookupOptions($this->idd_wilayah);
        $this->setupLookupOptions($this->kd_satker);
        $this->setupLookupOptions($this->idd_tahapan);
        $this->setupLookupOptions($this->idd_user);

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
        $filterList = Concat($filterList, $this->idd_wilayah->AdvancedSearch->toJson(), ","); // Field idd_wilayah
        $filterList = Concat($filterList, $this->kd_satker->AdvancedSearch->toJson(), ","); // Field kd_satker
        $filterList = Concat($filterList, $this->idd_tahapan->AdvancedSearch->toJson(), ","); // Field idd_tahapan
        $filterList = Concat($filterList, $this->tahun_anggaran->AdvancedSearch->toJson(), ","); // Field tahun_anggaran
        $filterList = Concat($filterList, $this->surat_pengantar->AdvancedSearch->toJson(), ","); // Field surat_pengantar
        $filterList = Concat($filterList, $this->rpjmd->AdvancedSearch->toJson(), ","); // Field rpjmd
        $filterList = Concat($filterList, $this->rkpk->AdvancedSearch->toJson(), ","); // Field rkpk
        $filterList = Concat($filterList, $this->skd_rkuappas->AdvancedSearch->toJson(), ","); // Field skd_rkuappas
        $filterList = Concat($filterList, $this->kua->AdvancedSearch->toJson(), ","); // Field kua
        $filterList = Concat($filterList, $this->ppas->AdvancedSearch->toJson(), ","); // Field ppas
        $filterList = Concat($filterList, $this->skd_rqanun->AdvancedSearch->toJson(), ","); // Field skd_rqanun
        $filterList = Concat($filterList, $this->nota_keuangan->AdvancedSearch->toJson(), ","); // Field nota_keuangan
        $filterList = Concat($filterList, $this->pengantar_nota->AdvancedSearch->toJson(), ","); // Field pengantar_nota
        $filterList = Concat($filterList, $this->risalah_sidang->AdvancedSearch->toJson(), ","); // Field risalah_sidang
        $filterList = Concat($filterList, $this->bap_apbk->AdvancedSearch->toJson(), ","); // Field bap_apbk
        $filterList = Concat($filterList, $this->rq_apbk->AdvancedSearch->toJson(), ","); // Field rq_apbk
        $filterList = Concat($filterList, $this->rp_penjabaran->AdvancedSearch->toJson(), ","); // Field rp_penjabaran
        $filterList = Concat($filterList, $this->jadwal_proses->AdvancedSearch->toJson(), ","); // Field jadwal_proses
        $filterList = Concat($filterList, $this->sinkron_kebijakan->AdvancedSearch->toJson(), ","); // Field sinkron_kebijakan
        $filterList = Concat($filterList, $this->konsistensi_program->AdvancedSearch->toJson(), ","); // Field konsistensi_program
        $filterList = Concat($filterList, $this->alokasi_pendidikan->AdvancedSearch->toJson(), ","); // Field alokasi_pendidikan
        $filterList = Concat($filterList, $this->alokasi_kesehatan->AdvancedSearch->toJson(), ","); // Field alokasi_kesehatan
        $filterList = Concat($filterList, $this->alokasi_belanja->AdvancedSearch->toJson(), ","); // Field alokasi_belanja
        $filterList = Concat($filterList, $this->bak_kegiatan->AdvancedSearch->toJson(), ","); // Field bak_kegiatan
        $filterList = Concat($filterList, $this->softcopy_rka->AdvancedSearch->toJson(), ","); // Field softcopy_rka
        $filterList = Concat($filterList, $this->otsus->AdvancedSearch->toJson(), ","); // Field otsus
        $filterList = Concat($filterList, $this->qanun_perbup->AdvancedSearch->toJson(), ","); // Field qanun_perbup
        $filterList = Concat($filterList, $this->tindak_apbkp->AdvancedSearch->toJson(), ","); // Field tindak_apbkp
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
            $UserProfile->setSearchFilters(CurrentUserName(), "fevaluasilistsrch", $filters);
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

        // Field idd_wilayah
        $this->idd_wilayah->AdvancedSearch->SearchValue = @$filter["x_idd_wilayah"];
        $this->idd_wilayah->AdvancedSearch->SearchOperator = @$filter["z_idd_wilayah"];
        $this->idd_wilayah->AdvancedSearch->SearchCondition = @$filter["v_idd_wilayah"];
        $this->idd_wilayah->AdvancedSearch->SearchValue2 = @$filter["y_idd_wilayah"];
        $this->idd_wilayah->AdvancedSearch->SearchOperator2 = @$filter["w_idd_wilayah"];
        $this->idd_wilayah->AdvancedSearch->save();

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

        // Field surat_pengantar
        $this->surat_pengantar->AdvancedSearch->SearchValue = @$filter["x_surat_pengantar"];
        $this->surat_pengantar->AdvancedSearch->SearchOperator = @$filter["z_surat_pengantar"];
        $this->surat_pengantar->AdvancedSearch->SearchCondition = @$filter["v_surat_pengantar"];
        $this->surat_pengantar->AdvancedSearch->SearchValue2 = @$filter["y_surat_pengantar"];
        $this->surat_pengantar->AdvancedSearch->SearchOperator2 = @$filter["w_surat_pengantar"];
        $this->surat_pengantar->AdvancedSearch->save();

        // Field rpjmd
        $this->rpjmd->AdvancedSearch->SearchValue = @$filter["x_rpjmd"];
        $this->rpjmd->AdvancedSearch->SearchOperator = @$filter["z_rpjmd"];
        $this->rpjmd->AdvancedSearch->SearchCondition = @$filter["v_rpjmd"];
        $this->rpjmd->AdvancedSearch->SearchValue2 = @$filter["y_rpjmd"];
        $this->rpjmd->AdvancedSearch->SearchOperator2 = @$filter["w_rpjmd"];
        $this->rpjmd->AdvancedSearch->save();

        // Field rkpk
        $this->rkpk->AdvancedSearch->SearchValue = @$filter["x_rkpk"];
        $this->rkpk->AdvancedSearch->SearchOperator = @$filter["z_rkpk"];
        $this->rkpk->AdvancedSearch->SearchCondition = @$filter["v_rkpk"];
        $this->rkpk->AdvancedSearch->SearchValue2 = @$filter["y_rkpk"];
        $this->rkpk->AdvancedSearch->SearchOperator2 = @$filter["w_rkpk"];
        $this->rkpk->AdvancedSearch->save();

        // Field skd_rkuappas
        $this->skd_rkuappas->AdvancedSearch->SearchValue = @$filter["x_skd_rkuappas"];
        $this->skd_rkuappas->AdvancedSearch->SearchOperator = @$filter["z_skd_rkuappas"];
        $this->skd_rkuappas->AdvancedSearch->SearchCondition = @$filter["v_skd_rkuappas"];
        $this->skd_rkuappas->AdvancedSearch->SearchValue2 = @$filter["y_skd_rkuappas"];
        $this->skd_rkuappas->AdvancedSearch->SearchOperator2 = @$filter["w_skd_rkuappas"];
        $this->skd_rkuappas->AdvancedSearch->save();

        // Field kua
        $this->kua->AdvancedSearch->SearchValue = @$filter["x_kua"];
        $this->kua->AdvancedSearch->SearchOperator = @$filter["z_kua"];
        $this->kua->AdvancedSearch->SearchCondition = @$filter["v_kua"];
        $this->kua->AdvancedSearch->SearchValue2 = @$filter["y_kua"];
        $this->kua->AdvancedSearch->SearchOperator2 = @$filter["w_kua"];
        $this->kua->AdvancedSearch->save();

        // Field ppas
        $this->ppas->AdvancedSearch->SearchValue = @$filter["x_ppas"];
        $this->ppas->AdvancedSearch->SearchOperator = @$filter["z_ppas"];
        $this->ppas->AdvancedSearch->SearchCondition = @$filter["v_ppas"];
        $this->ppas->AdvancedSearch->SearchValue2 = @$filter["y_ppas"];
        $this->ppas->AdvancedSearch->SearchOperator2 = @$filter["w_ppas"];
        $this->ppas->AdvancedSearch->save();

        // Field skd_rqanun
        $this->skd_rqanun->AdvancedSearch->SearchValue = @$filter["x_skd_rqanun"];
        $this->skd_rqanun->AdvancedSearch->SearchOperator = @$filter["z_skd_rqanun"];
        $this->skd_rqanun->AdvancedSearch->SearchCondition = @$filter["v_skd_rqanun"];
        $this->skd_rqanun->AdvancedSearch->SearchValue2 = @$filter["y_skd_rqanun"];
        $this->skd_rqanun->AdvancedSearch->SearchOperator2 = @$filter["w_skd_rqanun"];
        $this->skd_rqanun->AdvancedSearch->save();

        // Field nota_keuangan
        $this->nota_keuangan->AdvancedSearch->SearchValue = @$filter["x_nota_keuangan"];
        $this->nota_keuangan->AdvancedSearch->SearchOperator = @$filter["z_nota_keuangan"];
        $this->nota_keuangan->AdvancedSearch->SearchCondition = @$filter["v_nota_keuangan"];
        $this->nota_keuangan->AdvancedSearch->SearchValue2 = @$filter["y_nota_keuangan"];
        $this->nota_keuangan->AdvancedSearch->SearchOperator2 = @$filter["w_nota_keuangan"];
        $this->nota_keuangan->AdvancedSearch->save();

        // Field pengantar_nota
        $this->pengantar_nota->AdvancedSearch->SearchValue = @$filter["x_pengantar_nota"];
        $this->pengantar_nota->AdvancedSearch->SearchOperator = @$filter["z_pengantar_nota"];
        $this->pengantar_nota->AdvancedSearch->SearchCondition = @$filter["v_pengantar_nota"];
        $this->pengantar_nota->AdvancedSearch->SearchValue2 = @$filter["y_pengantar_nota"];
        $this->pengantar_nota->AdvancedSearch->SearchOperator2 = @$filter["w_pengantar_nota"];
        $this->pengantar_nota->AdvancedSearch->save();

        // Field risalah_sidang
        $this->risalah_sidang->AdvancedSearch->SearchValue = @$filter["x_risalah_sidang"];
        $this->risalah_sidang->AdvancedSearch->SearchOperator = @$filter["z_risalah_sidang"];
        $this->risalah_sidang->AdvancedSearch->SearchCondition = @$filter["v_risalah_sidang"];
        $this->risalah_sidang->AdvancedSearch->SearchValue2 = @$filter["y_risalah_sidang"];
        $this->risalah_sidang->AdvancedSearch->SearchOperator2 = @$filter["w_risalah_sidang"];
        $this->risalah_sidang->AdvancedSearch->save();

        // Field bap_apbk
        $this->bap_apbk->AdvancedSearch->SearchValue = @$filter["x_bap_apbk"];
        $this->bap_apbk->AdvancedSearch->SearchOperator = @$filter["z_bap_apbk"];
        $this->bap_apbk->AdvancedSearch->SearchCondition = @$filter["v_bap_apbk"];
        $this->bap_apbk->AdvancedSearch->SearchValue2 = @$filter["y_bap_apbk"];
        $this->bap_apbk->AdvancedSearch->SearchOperator2 = @$filter["w_bap_apbk"];
        $this->bap_apbk->AdvancedSearch->save();

        // Field rq_apbk
        $this->rq_apbk->AdvancedSearch->SearchValue = @$filter["x_rq_apbk"];
        $this->rq_apbk->AdvancedSearch->SearchOperator = @$filter["z_rq_apbk"];
        $this->rq_apbk->AdvancedSearch->SearchCondition = @$filter["v_rq_apbk"];
        $this->rq_apbk->AdvancedSearch->SearchValue2 = @$filter["y_rq_apbk"];
        $this->rq_apbk->AdvancedSearch->SearchOperator2 = @$filter["w_rq_apbk"];
        $this->rq_apbk->AdvancedSearch->save();

        // Field rp_penjabaran
        $this->rp_penjabaran->AdvancedSearch->SearchValue = @$filter["x_rp_penjabaran"];
        $this->rp_penjabaran->AdvancedSearch->SearchOperator = @$filter["z_rp_penjabaran"];
        $this->rp_penjabaran->AdvancedSearch->SearchCondition = @$filter["v_rp_penjabaran"];
        $this->rp_penjabaran->AdvancedSearch->SearchValue2 = @$filter["y_rp_penjabaran"];
        $this->rp_penjabaran->AdvancedSearch->SearchOperator2 = @$filter["w_rp_penjabaran"];
        $this->rp_penjabaran->AdvancedSearch->save();

        // Field jadwal_proses
        $this->jadwal_proses->AdvancedSearch->SearchValue = @$filter["x_jadwal_proses"];
        $this->jadwal_proses->AdvancedSearch->SearchOperator = @$filter["z_jadwal_proses"];
        $this->jadwal_proses->AdvancedSearch->SearchCondition = @$filter["v_jadwal_proses"];
        $this->jadwal_proses->AdvancedSearch->SearchValue2 = @$filter["y_jadwal_proses"];
        $this->jadwal_proses->AdvancedSearch->SearchOperator2 = @$filter["w_jadwal_proses"];
        $this->jadwal_proses->AdvancedSearch->save();

        // Field sinkron_kebijakan
        $this->sinkron_kebijakan->AdvancedSearch->SearchValue = @$filter["x_sinkron_kebijakan"];
        $this->sinkron_kebijakan->AdvancedSearch->SearchOperator = @$filter["z_sinkron_kebijakan"];
        $this->sinkron_kebijakan->AdvancedSearch->SearchCondition = @$filter["v_sinkron_kebijakan"];
        $this->sinkron_kebijakan->AdvancedSearch->SearchValue2 = @$filter["y_sinkron_kebijakan"];
        $this->sinkron_kebijakan->AdvancedSearch->SearchOperator2 = @$filter["w_sinkron_kebijakan"];
        $this->sinkron_kebijakan->AdvancedSearch->save();

        // Field konsistensi_program
        $this->konsistensi_program->AdvancedSearch->SearchValue = @$filter["x_konsistensi_program"];
        $this->konsistensi_program->AdvancedSearch->SearchOperator = @$filter["z_konsistensi_program"];
        $this->konsistensi_program->AdvancedSearch->SearchCondition = @$filter["v_konsistensi_program"];
        $this->konsistensi_program->AdvancedSearch->SearchValue2 = @$filter["y_konsistensi_program"];
        $this->konsistensi_program->AdvancedSearch->SearchOperator2 = @$filter["w_konsistensi_program"];
        $this->konsistensi_program->AdvancedSearch->save();

        // Field alokasi_pendidikan
        $this->alokasi_pendidikan->AdvancedSearch->SearchValue = @$filter["x_alokasi_pendidikan"];
        $this->alokasi_pendidikan->AdvancedSearch->SearchOperator = @$filter["z_alokasi_pendidikan"];
        $this->alokasi_pendidikan->AdvancedSearch->SearchCondition = @$filter["v_alokasi_pendidikan"];
        $this->alokasi_pendidikan->AdvancedSearch->SearchValue2 = @$filter["y_alokasi_pendidikan"];
        $this->alokasi_pendidikan->AdvancedSearch->SearchOperator2 = @$filter["w_alokasi_pendidikan"];
        $this->alokasi_pendidikan->AdvancedSearch->save();

        // Field alokasi_kesehatan
        $this->alokasi_kesehatan->AdvancedSearch->SearchValue = @$filter["x_alokasi_kesehatan"];
        $this->alokasi_kesehatan->AdvancedSearch->SearchOperator = @$filter["z_alokasi_kesehatan"];
        $this->alokasi_kesehatan->AdvancedSearch->SearchCondition = @$filter["v_alokasi_kesehatan"];
        $this->alokasi_kesehatan->AdvancedSearch->SearchValue2 = @$filter["y_alokasi_kesehatan"];
        $this->alokasi_kesehatan->AdvancedSearch->SearchOperator2 = @$filter["w_alokasi_kesehatan"];
        $this->alokasi_kesehatan->AdvancedSearch->save();

        // Field alokasi_belanja
        $this->alokasi_belanja->AdvancedSearch->SearchValue = @$filter["x_alokasi_belanja"];
        $this->alokasi_belanja->AdvancedSearch->SearchOperator = @$filter["z_alokasi_belanja"];
        $this->alokasi_belanja->AdvancedSearch->SearchCondition = @$filter["v_alokasi_belanja"];
        $this->alokasi_belanja->AdvancedSearch->SearchValue2 = @$filter["y_alokasi_belanja"];
        $this->alokasi_belanja->AdvancedSearch->SearchOperator2 = @$filter["w_alokasi_belanja"];
        $this->alokasi_belanja->AdvancedSearch->save();

        // Field bak_kegiatan
        $this->bak_kegiatan->AdvancedSearch->SearchValue = @$filter["x_bak_kegiatan"];
        $this->bak_kegiatan->AdvancedSearch->SearchOperator = @$filter["z_bak_kegiatan"];
        $this->bak_kegiatan->AdvancedSearch->SearchCondition = @$filter["v_bak_kegiatan"];
        $this->bak_kegiatan->AdvancedSearch->SearchValue2 = @$filter["y_bak_kegiatan"];
        $this->bak_kegiatan->AdvancedSearch->SearchOperator2 = @$filter["w_bak_kegiatan"];
        $this->bak_kegiatan->AdvancedSearch->save();

        // Field softcopy_rka
        $this->softcopy_rka->AdvancedSearch->SearchValue = @$filter["x_softcopy_rka"];
        $this->softcopy_rka->AdvancedSearch->SearchOperator = @$filter["z_softcopy_rka"];
        $this->softcopy_rka->AdvancedSearch->SearchCondition = @$filter["v_softcopy_rka"];
        $this->softcopy_rka->AdvancedSearch->SearchValue2 = @$filter["y_softcopy_rka"];
        $this->softcopy_rka->AdvancedSearch->SearchOperator2 = @$filter["w_softcopy_rka"];
        $this->softcopy_rka->AdvancedSearch->save();

        // Field otsus
        $this->otsus->AdvancedSearch->SearchValue = @$filter["x_otsus"];
        $this->otsus->AdvancedSearch->SearchOperator = @$filter["z_otsus"];
        $this->otsus->AdvancedSearch->SearchCondition = @$filter["v_otsus"];
        $this->otsus->AdvancedSearch->SearchValue2 = @$filter["y_otsus"];
        $this->otsus->AdvancedSearch->SearchOperator2 = @$filter["w_otsus"];
        $this->otsus->AdvancedSearch->save();

        // Field qanun_perbup
        $this->qanun_perbup->AdvancedSearch->SearchValue = @$filter["x_qanun_perbup"];
        $this->qanun_perbup->AdvancedSearch->SearchOperator = @$filter["z_qanun_perbup"];
        $this->qanun_perbup->AdvancedSearch->SearchCondition = @$filter["v_qanun_perbup"];
        $this->qanun_perbup->AdvancedSearch->SearchValue2 = @$filter["y_qanun_perbup"];
        $this->qanun_perbup->AdvancedSearch->SearchOperator2 = @$filter["w_qanun_perbup"];
        $this->qanun_perbup->AdvancedSearch->save();

        // Field tindak_apbkp
        $this->tindak_apbkp->AdvancedSearch->SearchValue = @$filter["x_tindak_apbkp"];
        $this->tindak_apbkp->AdvancedSearch->SearchOperator = @$filter["z_tindak_apbkp"];
        $this->tindak_apbkp->AdvancedSearch->SearchCondition = @$filter["v_tindak_apbkp"];
        $this->tindak_apbkp->AdvancedSearch->SearchValue2 = @$filter["y_tindak_apbkp"];
        $this->tindak_apbkp->AdvancedSearch->SearchOperator2 = @$filter["w_tindak_apbkp"];
        $this->tindak_apbkp->AdvancedSearch->save();

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
        $this->buildBasicSearchSql($where, $this->tahun_anggaran, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->surat_pengantar, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->rpjmd, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->rkpk, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->skd_rkuappas, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->kua, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->ppas, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->skd_rqanun, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->nota_keuangan, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->pengantar_nota, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->risalah_sidang, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->bap_apbk, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->rq_apbk, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->rp_penjabaran, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->jadwal_proses, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->sinkron_kebijakan, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->konsistensi_program, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->alokasi_pendidikan, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->alokasi_kesehatan, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->alokasi_belanja, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->bak_kegiatan, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->softcopy_rka, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->otsus, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->qanun_perbup, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->tindak_apbkp, $arKeywords, $type);
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
            $this->updateSort($this->idd_wilayah); // idd_wilayah
            $this->updateSort($this->kd_satker); // kd_satker
            $this->updateSort($this->idd_tahapan); // idd_tahapan
            $this->updateSort($this->tahun_anggaran); // tahun_anggaran
            $this->updateSort($this->surat_pengantar); // surat_pengantar
            $this->updateSort($this->rpjmd); // rpjmd
            $this->updateSort($this->rkpk); // rkpk
            $this->updateSort($this->skd_rkuappas); // skd_rkuappas
            $this->updateSort($this->kua); // kua
            $this->updateSort($this->ppas); // ppas
            $this->updateSort($this->skd_rqanun); // skd_rqanun
            $this->updateSort($this->nota_keuangan); // nota_keuangan
            $this->updateSort($this->pengantar_nota); // pengantar_nota
            $this->updateSort($this->risalah_sidang); // risalah_sidang
            $this->updateSort($this->bap_apbk); // bap_apbk
            $this->updateSort($this->rq_apbk); // rq_apbk
            $this->updateSort($this->rp_penjabaran); // rp_penjabaran
            $this->updateSort($this->jadwal_proses); // jadwal_proses
            $this->updateSort($this->sinkron_kebijakan); // sinkron_kebijakan
            $this->updateSort($this->konsistensi_program); // konsistensi_program
            $this->updateSort($this->alokasi_pendidikan); // alokasi_pendidikan
            $this->updateSort($this->alokasi_kesehatan); // alokasi_kesehatan
            $this->updateSort($this->alokasi_belanja); // alokasi_belanja
            $this->updateSort($this->bak_kegiatan); // bak_kegiatan
            $this->updateSort($this->softcopy_rka); // softcopy_rka
            $this->updateSort($this->otsus); // otsus
            $this->updateSort($this->qanun_perbup); // qanun_perbup
            $this->updateSort($this->tindak_apbkp); // tindak_apbkp
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
                $this->idd_wilayah->setSort("");
                $this->kd_satker->setSort("");
                $this->idd_tahapan->setSort("");
                $this->tahun_anggaran->setSort("");
                $this->surat_pengantar->setSort("");
                $this->rpjmd->setSort("");
                $this->rkpk->setSort("");
                $this->skd_rkuappas->setSort("");
                $this->kua->setSort("");
                $this->ppas->setSort("");
                $this->skd_rqanun->setSort("");
                $this->nota_keuangan->setSort("");
                $this->pengantar_nota->setSort("");
                $this->risalah_sidang->setSort("");
                $this->bap_apbk->setSort("");
                $this->rq_apbk->setSort("");
                $this->rp_penjabaran->setSort("");
                $this->jadwal_proses->setSort("");
                $this->sinkron_kebijakan->setSort("");
                $this->konsistensi_program->setSort("");
                $this->alokasi_pendidikan->setSort("");
                $this->alokasi_kesehatan->setSort("");
                $this->alokasi_belanja->setSort("");
                $this->bak_kegiatan->setSort("");
                $this->softcopy_rka->setSort("");
                $this->otsus->setSort("");
                $this->qanun_perbup->setSort("");
                $this->tindak_apbkp->setSort("");
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
            if ($Security->canView() && $this->showOptionLink("view")) {
                $opt->Body = "<a class=\"ew-row-link ew-view\" title=\"" . $viewcaption . "\" data-caption=\"" . $viewcaption . "\" href=\"" . HtmlEncode(GetUrl($this->ViewUrl)) . "\">" . $Language->phrase("ViewLink") . "</a>";
            } else {
                $opt->Body = "";
            }

            // "edit"
            $opt = $this->ListOptions["edit"];
            $editcaption = HtmlTitle($Language->phrase("EditLink"));
            if ($Security->canEdit() && $this->showOptionLink("edit")) {
                $opt->Body = "<a class=\"ew-row-link ew-edit\" title=\"" . HtmlTitle($Language->phrase("EditLink")) . "\" data-caption=\"" . HtmlTitle($Language->phrase("EditLink")) . "\" href=\"" . HtmlEncode(GetUrl($this->EditUrl)) . "\">" . $Language->phrase("EditLink") . "</a>";
            } else {
                $opt->Body = "";
            }

            // "copy"
            $opt = $this->ListOptions["copy"];
            $copycaption = HtmlTitle($Language->phrase("CopyLink"));
            if ($Security->canAdd() && $this->showOptionLink("add")) {
                $opt->Body = "<a class=\"ew-row-link ew-copy\" title=\"" . $copycaption . "\" data-caption=\"" . $copycaption . "\" href=\"" . HtmlEncode(GetUrl($this->CopyUrl)) . "\">" . $Language->phrase("CopyLink") . "</a>";
            } else {
                $opt->Body = "";
            }

            // "delete"
            $opt = $this->ListOptions["delete"];
            if ($Security->canDelete() && $this->showOptionLink("delete")) {
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
        $item->Body = "<a class=\"ew-save-filter\" data-form=\"fevaluasilistsrch\" href=\"#\" onclick=\"return false;\">" . $Language->phrase("SaveCurrentFilter") . "</a>";
        $item->Visible = true;
        $item = &$this->FilterOptions->add("deletefilter");
        $item->Body = "<a class=\"ew-delete-filter\" data-form=\"fevaluasilistsrch\" href=\"#\" onclick=\"return false;\">" . $Language->phrase("DeleteFilter") . "</a>";
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
                $item->Body = '<a class="ew-action ew-list-action" title="' . HtmlEncode($caption) . '" data-caption="' . HtmlEncode($caption) . '" href="#" onclick="return ew.submitAction(event,jQuery.extend({f:document.fevaluasilist},' . $listaction->toJson(true) . '));">' . $icon . '</a>';
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
        $this->idd_wilayah->setDbValue($row['idd_wilayah']);
        $this->kd_satker->setDbValue($row['kd_satker']);
        $this->idd_tahapan->setDbValue($row['idd_tahapan']);
        $this->tahun_anggaran->setDbValue($row['tahun_anggaran']);
        $this->surat_pengantar->Upload->DbValue = $row['surat_pengantar'];
        $this->surat_pengantar->setDbValue($this->surat_pengantar->Upload->DbValue);
        $this->rpjmd->Upload->DbValue = $row['rpjmd'];
        $this->rpjmd->setDbValue($this->rpjmd->Upload->DbValue);
        $this->rkpk->Upload->DbValue = $row['rkpk'];
        $this->rkpk->setDbValue($this->rkpk->Upload->DbValue);
        $this->skd_rkuappas->Upload->DbValue = $row['skd_rkuappas'];
        $this->skd_rkuappas->setDbValue($this->skd_rkuappas->Upload->DbValue);
        $this->kua->Upload->DbValue = $row['kua'];
        $this->kua->setDbValue($this->kua->Upload->DbValue);
        $this->ppas->Upload->DbValue = $row['ppas'];
        $this->ppas->setDbValue($this->ppas->Upload->DbValue);
        $this->skd_rqanun->Upload->DbValue = $row['skd_rqanun'];
        $this->skd_rqanun->setDbValue($this->skd_rqanun->Upload->DbValue);
        $this->nota_keuangan->Upload->DbValue = $row['nota_keuangan'];
        $this->nota_keuangan->setDbValue($this->nota_keuangan->Upload->DbValue);
        $this->pengantar_nota->Upload->DbValue = $row['pengantar_nota'];
        $this->pengantar_nota->setDbValue($this->pengantar_nota->Upload->DbValue);
        $this->risalah_sidang->Upload->DbValue = $row['risalah_sidang'];
        $this->risalah_sidang->setDbValue($this->risalah_sidang->Upload->DbValue);
        $this->bap_apbk->Upload->DbValue = $row['bap_apbk'];
        $this->bap_apbk->setDbValue($this->bap_apbk->Upload->DbValue);
        $this->rq_apbk->Upload->DbValue = $row['rq_apbk'];
        $this->rq_apbk->setDbValue($this->rq_apbk->Upload->DbValue);
        $this->rp_penjabaran->Upload->DbValue = $row['rp_penjabaran'];
        $this->rp_penjabaran->setDbValue($this->rp_penjabaran->Upload->DbValue);
        $this->jadwal_proses->Upload->DbValue = $row['jadwal_proses'];
        $this->jadwal_proses->setDbValue($this->jadwal_proses->Upload->DbValue);
        $this->sinkron_kebijakan->Upload->DbValue = $row['sinkron_kebijakan'];
        $this->sinkron_kebijakan->setDbValue($this->sinkron_kebijakan->Upload->DbValue);
        $this->konsistensi_program->Upload->DbValue = $row['konsistensi_program'];
        $this->konsistensi_program->setDbValue($this->konsistensi_program->Upload->DbValue);
        $this->alokasi_pendidikan->Upload->DbValue = $row['alokasi_pendidikan'];
        $this->alokasi_pendidikan->setDbValue($this->alokasi_pendidikan->Upload->DbValue);
        $this->alokasi_kesehatan->Upload->DbValue = $row['alokasi_kesehatan'];
        $this->alokasi_kesehatan->setDbValue($this->alokasi_kesehatan->Upload->DbValue);
        $this->alokasi_belanja->Upload->DbValue = $row['alokasi_belanja'];
        $this->alokasi_belanja->setDbValue($this->alokasi_belanja->Upload->DbValue);
        $this->bak_kegiatan->Upload->DbValue = $row['bak_kegiatan'];
        $this->bak_kegiatan->setDbValue($this->bak_kegiatan->Upload->DbValue);
        $this->softcopy_rka->Upload->DbValue = $row['softcopy_rka'];
        $this->softcopy_rka->setDbValue($this->softcopy_rka->Upload->DbValue);
        $this->otsus->Upload->DbValue = $row['otsus'];
        $this->otsus->setDbValue($this->otsus->Upload->DbValue);
        $this->qanun_perbup->Upload->DbValue = $row['qanun_perbup'];
        $this->qanun_perbup->setDbValue($this->qanun_perbup->Upload->DbValue);
        $this->tindak_apbkp->Upload->DbValue = $row['tindak_apbkp'];
        $this->tindak_apbkp->setDbValue($this->tindak_apbkp->Upload->DbValue);
        $this->status->setDbValue($row['status']);
        $this->idd_user->setDbValue($row['idd_user']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['idd_evaluasi'] = null;
        $row['tanggal'] = null;
        $row['idd_wilayah'] = null;
        $row['kd_satker'] = null;
        $row['idd_tahapan'] = null;
        $row['tahun_anggaran'] = null;
        $row['surat_pengantar'] = null;
        $row['rpjmd'] = null;
        $row['rkpk'] = null;
        $row['skd_rkuappas'] = null;
        $row['kua'] = null;
        $row['ppas'] = null;
        $row['skd_rqanun'] = null;
        $row['nota_keuangan'] = null;
        $row['pengantar_nota'] = null;
        $row['risalah_sidang'] = null;
        $row['bap_apbk'] = null;
        $row['rq_apbk'] = null;
        $row['rp_penjabaran'] = null;
        $row['jadwal_proses'] = null;
        $row['sinkron_kebijakan'] = null;
        $row['konsistensi_program'] = null;
        $row['alokasi_pendidikan'] = null;
        $row['alokasi_kesehatan'] = null;
        $row['alokasi_belanja'] = null;
        $row['bak_kegiatan'] = null;
        $row['softcopy_rka'] = null;
        $row['otsus'] = null;
        $row['qanun_perbup'] = null;
        $row['tindak_apbkp'] = null;
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

        // idd_wilayah

        // kd_satker

        // idd_tahapan

        // tahun_anggaran

        // surat_pengantar

        // rpjmd

        // rkpk

        // skd_rkuappas

        // kua

        // ppas

        // skd_rqanun

        // nota_keuangan

        // pengantar_nota

        // risalah_sidang

        // bap_apbk

        // rq_apbk

        // rp_penjabaran

        // jadwal_proses

        // sinkron_kebijakan

        // konsistensi_program

        // alokasi_pendidikan

        // alokasi_kesehatan

        // alokasi_belanja

        // bak_kegiatan

        // softcopy_rka

        // otsus

        // qanun_perbup

        // tindak_apbkp

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

            // idd_wilayah
            $curVal = trim(strval($this->idd_wilayah->CurrentValue));
            if ($curVal != "") {
                $this->idd_wilayah->ViewValue = $this->idd_wilayah->lookupCacheOption($curVal);
                if ($this->idd_wilayah->ViewValue === null) { // Lookup from database
                    $filterWrk = "`idd_wilayah`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                    $sqlWrk = $this->idd_wilayah->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->idd_wilayah->Lookup->renderViewRow($rswrk[0]);
                        $this->idd_wilayah->ViewValue = $this->idd_wilayah->displayValue($arwrk);
                    } else {
                        $this->idd_wilayah->ViewValue = $this->idd_wilayah->CurrentValue;
                    }
                }
            } else {
                $this->idd_wilayah->ViewValue = null;
            }
            $this->idd_wilayah->ViewCustomAttributes = "";

            // kd_satker
            $curVal = trim(strval($this->kd_satker->CurrentValue));
            if ($curVal != "") {
                $this->kd_satker->ViewValue = $this->kd_satker->lookupCacheOption($curVal);
                if ($this->kd_satker->ViewValue === null) { // Lookup from database
                    $filterWrk = "`kode_pemda`" . SearchString("=", $curVal, DATATYPE_STRING, "");
                    $sqlWrk = $this->kd_satker->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->kd_satker->Lookup->renderViewRow($rswrk[0]);
                        $this->kd_satker->ViewValue = $this->kd_satker->displayValue($arwrk);
                    } else {
                        $this->kd_satker->ViewValue = $this->kd_satker->CurrentValue;
                    }
                }
            } else {
                $this->kd_satker->ViewValue = null;
            }
            $this->kd_satker->ViewCustomAttributes = "";

            // idd_tahapan
            $curVal = trim(strval($this->idd_tahapan->CurrentValue));
            if ($curVal != "") {
                $this->idd_tahapan->ViewValue = $this->idd_tahapan->lookupCacheOption($curVal);
                if ($this->idd_tahapan->ViewValue === null) { // Lookup from database
                    $filterWrk = "`idd_tahapan`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                    $sqlWrk = $this->idd_tahapan->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->idd_tahapan->Lookup->renderViewRow($rswrk[0]);
                        $this->idd_tahapan->ViewValue = $this->idd_tahapan->displayValue($arwrk);
                    } else {
                        $this->idd_tahapan->ViewValue = $this->idd_tahapan->CurrentValue;
                    }
                }
            } else {
                $this->idd_tahapan->ViewValue = null;
            }
            $this->idd_tahapan->ViewCustomAttributes = "";

            // tahun_anggaran
            if (strval($this->tahun_anggaran->CurrentValue) != "") {
                $this->tahun_anggaran->ViewValue = $this->tahun_anggaran->optionCaption($this->tahun_anggaran->CurrentValue);
            } else {
                $this->tahun_anggaran->ViewValue = null;
            }
            $this->tahun_anggaran->ViewCustomAttributes = "";

            // surat_pengantar
            if (!EmptyValue($this->surat_pengantar->Upload->DbValue)) {
                $this->surat_pengantar->ViewValue = $this->surat_pengantar->Upload->DbValue;
            } else {
                $this->surat_pengantar->ViewValue = "";
            }
            $this->surat_pengantar->ViewCustomAttributes = "";

            // rpjmd
            if (!EmptyValue($this->rpjmd->Upload->DbValue)) {
                $this->rpjmd->ViewValue = $this->rpjmd->Upload->DbValue;
            } else {
                $this->rpjmd->ViewValue = "";
            }
            $this->rpjmd->ViewCustomAttributes = "";

            // rkpk
            if (!EmptyValue($this->rkpk->Upload->DbValue)) {
                $this->rkpk->ViewValue = $this->rkpk->Upload->DbValue;
            } else {
                $this->rkpk->ViewValue = "";
            }
            $this->rkpk->ViewCustomAttributes = "";

            // skd_rkuappas
            if (!EmptyValue($this->skd_rkuappas->Upload->DbValue)) {
                $this->skd_rkuappas->ViewValue = $this->skd_rkuappas->Upload->DbValue;
            } else {
                $this->skd_rkuappas->ViewValue = "";
            }
            $this->skd_rkuappas->ViewCustomAttributes = "";

            // kua
            if (!EmptyValue($this->kua->Upload->DbValue)) {
                $this->kua->ViewValue = $this->kua->Upload->DbValue;
            } else {
                $this->kua->ViewValue = "";
            }
            $this->kua->ViewCustomAttributes = "";

            // ppas
            if (!EmptyValue($this->ppas->Upload->DbValue)) {
                $this->ppas->ViewValue = $this->ppas->Upload->DbValue;
            } else {
                $this->ppas->ViewValue = "";
            }
            $this->ppas->ViewCustomAttributes = "";

            // skd_rqanun
            if (!EmptyValue($this->skd_rqanun->Upload->DbValue)) {
                $this->skd_rqanun->ViewValue = $this->skd_rqanun->Upload->DbValue;
            } else {
                $this->skd_rqanun->ViewValue = "";
            }
            $this->skd_rqanun->ViewCustomAttributes = "";

            // nota_keuangan
            if (!EmptyValue($this->nota_keuangan->Upload->DbValue)) {
                $this->nota_keuangan->ViewValue = $this->nota_keuangan->Upload->DbValue;
            } else {
                $this->nota_keuangan->ViewValue = "";
            }
            $this->nota_keuangan->ViewCustomAttributes = "";

            // pengantar_nota
            if (!EmptyValue($this->pengantar_nota->Upload->DbValue)) {
                $this->pengantar_nota->ViewValue = $this->pengantar_nota->Upload->DbValue;
            } else {
                $this->pengantar_nota->ViewValue = "";
            }
            $this->pengantar_nota->ViewCustomAttributes = "";

            // risalah_sidang
            if (!EmptyValue($this->risalah_sidang->Upload->DbValue)) {
                $this->risalah_sidang->ViewValue = $this->risalah_sidang->Upload->DbValue;
            } else {
                $this->risalah_sidang->ViewValue = "";
            }
            $this->risalah_sidang->ViewCustomAttributes = "";

            // bap_apbk
            if (!EmptyValue($this->bap_apbk->Upload->DbValue)) {
                $this->bap_apbk->ViewValue = $this->bap_apbk->Upload->DbValue;
            } else {
                $this->bap_apbk->ViewValue = "";
            }
            $this->bap_apbk->ViewCustomAttributes = "";

            // rq_apbk
            if (!EmptyValue($this->rq_apbk->Upload->DbValue)) {
                $this->rq_apbk->ViewValue = $this->rq_apbk->Upload->DbValue;
            } else {
                $this->rq_apbk->ViewValue = "";
            }
            $this->rq_apbk->ViewCustomAttributes = "";

            // rp_penjabaran
            if (!EmptyValue($this->rp_penjabaran->Upload->DbValue)) {
                $this->rp_penjabaran->ViewValue = $this->rp_penjabaran->Upload->DbValue;
            } else {
                $this->rp_penjabaran->ViewValue = "";
            }
            $this->rp_penjabaran->ViewCustomAttributes = "";

            // jadwal_proses
            if (!EmptyValue($this->jadwal_proses->Upload->DbValue)) {
                $this->jadwal_proses->ViewValue = $this->jadwal_proses->Upload->DbValue;
            } else {
                $this->jadwal_proses->ViewValue = "";
            }
            $this->jadwal_proses->ViewCustomAttributes = "";

            // sinkron_kebijakan
            if (!EmptyValue($this->sinkron_kebijakan->Upload->DbValue)) {
                $this->sinkron_kebijakan->ViewValue = $this->sinkron_kebijakan->Upload->DbValue;
            } else {
                $this->sinkron_kebijakan->ViewValue = "";
            }
            $this->sinkron_kebijakan->ViewCustomAttributes = "";

            // konsistensi_program
            if (!EmptyValue($this->konsistensi_program->Upload->DbValue)) {
                $this->konsistensi_program->ViewValue = $this->konsistensi_program->Upload->DbValue;
            } else {
                $this->konsistensi_program->ViewValue = "";
            }
            $this->konsistensi_program->ViewCustomAttributes = "";

            // alokasi_pendidikan
            if (!EmptyValue($this->alokasi_pendidikan->Upload->DbValue)) {
                $this->alokasi_pendidikan->ViewValue = $this->alokasi_pendidikan->Upload->DbValue;
            } else {
                $this->alokasi_pendidikan->ViewValue = "";
            }
            $this->alokasi_pendidikan->ViewCustomAttributes = "";

            // alokasi_kesehatan
            if (!EmptyValue($this->alokasi_kesehatan->Upload->DbValue)) {
                $this->alokasi_kesehatan->ViewValue = $this->alokasi_kesehatan->Upload->DbValue;
            } else {
                $this->alokasi_kesehatan->ViewValue = "";
            }
            $this->alokasi_kesehatan->ViewCustomAttributes = "";

            // alokasi_belanja
            if (!EmptyValue($this->alokasi_belanja->Upload->DbValue)) {
                $this->alokasi_belanja->ViewValue = $this->alokasi_belanja->Upload->DbValue;
            } else {
                $this->alokasi_belanja->ViewValue = "";
            }
            $this->alokasi_belanja->ViewCustomAttributes = "";

            // bak_kegiatan
            if (!EmptyValue($this->bak_kegiatan->Upload->DbValue)) {
                $this->bak_kegiatan->ViewValue = $this->bak_kegiatan->Upload->DbValue;
            } else {
                $this->bak_kegiatan->ViewValue = "";
            }
            $this->bak_kegiatan->ViewCustomAttributes = "";

            // softcopy_rka
            if (!EmptyValue($this->softcopy_rka->Upload->DbValue)) {
                $this->softcopy_rka->ViewValue = $this->softcopy_rka->Upload->DbValue;
            } else {
                $this->softcopy_rka->ViewValue = "";
            }
            $this->softcopy_rka->ViewCustomAttributes = "";

            // otsus
            if (!EmptyValue($this->otsus->Upload->DbValue)) {
                $this->otsus->ViewValue = $this->otsus->Upload->DbValue;
            } else {
                $this->otsus->ViewValue = "";
            }
            $this->otsus->ViewCustomAttributes = "";

            // qanun_perbup
            if (!EmptyValue($this->qanun_perbup->Upload->DbValue)) {
                $this->qanun_perbup->ViewValue = $this->qanun_perbup->Upload->DbValue;
            } else {
                $this->qanun_perbup->ViewValue = "";
            }
            $this->qanun_perbup->ViewCustomAttributes = "";

            // tindak_apbkp
            if (!EmptyValue($this->tindak_apbkp->Upload->DbValue)) {
                $this->tindak_apbkp->ViewValue = $this->tindak_apbkp->Upload->DbValue;
            } else {
                $this->tindak_apbkp->ViewValue = "";
            }
            $this->tindak_apbkp->ViewCustomAttributes = "";

            // status
            if (strval($this->status->CurrentValue) != "") {
                $this->status->ViewValue = $this->status->optionCaption($this->status->CurrentValue);
            } else {
                $this->status->ViewValue = null;
            }
            $this->status->ViewCustomAttributes = "";

            // idd_user
            $curVal = trim(strval($this->idd_user->CurrentValue));
            if ($curVal != "") {
                $this->idd_user->ViewValue = $this->idd_user->lookupCacheOption($curVal);
                if ($this->idd_user->ViewValue === null) { // Lookup from database
                    $filterWrk = "`idd_user`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                    $sqlWrk = $this->idd_user->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->idd_user->Lookup->renderViewRow($rswrk[0]);
                        $this->idd_user->ViewValue = $this->idd_user->displayValue($arwrk);
                    } else {
                        $this->idd_user->ViewValue = $this->idd_user->CurrentValue;
                    }
                }
            } else {
                $this->idd_user->ViewValue = null;
            }
            $this->idd_user->ViewCustomAttributes = "";

            // idd_evaluasi
            $this->idd_evaluasi->LinkCustomAttributes = "";
            $this->idd_evaluasi->HrefValue = "";
            $this->idd_evaluasi->TooltipValue = "";

            // tanggal
            $this->tanggal->LinkCustomAttributes = "";
            $this->tanggal->HrefValue = "";
            $this->tanggal->TooltipValue = "";

            // idd_wilayah
            $this->idd_wilayah->LinkCustomAttributes = "";
            $this->idd_wilayah->HrefValue = "";
            $this->idd_wilayah->TooltipValue = "";

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

            // surat_pengantar
            $this->surat_pengantar->LinkCustomAttributes = "";
            $this->surat_pengantar->HrefValue = "";
            $this->surat_pengantar->ExportHrefValue = $this->surat_pengantar->UploadPath . $this->surat_pengantar->Upload->DbValue;
            $this->surat_pengantar->TooltipValue = "";

            // rpjmd
            $this->rpjmd->LinkCustomAttributes = "";
            $this->rpjmd->HrefValue = "";
            $this->rpjmd->ExportHrefValue = $this->rpjmd->UploadPath . $this->rpjmd->Upload->DbValue;
            $this->rpjmd->TooltipValue = "";

            // rkpk
            $this->rkpk->LinkCustomAttributes = "";
            $this->rkpk->HrefValue = "";
            $this->rkpk->ExportHrefValue = $this->rkpk->UploadPath . $this->rkpk->Upload->DbValue;
            $this->rkpk->TooltipValue = "";

            // skd_rkuappas
            $this->skd_rkuappas->LinkCustomAttributes = "";
            $this->skd_rkuappas->HrefValue = "";
            $this->skd_rkuappas->ExportHrefValue = $this->skd_rkuappas->UploadPath . $this->skd_rkuappas->Upload->DbValue;
            $this->skd_rkuappas->TooltipValue = "";

            // kua
            $this->kua->LinkCustomAttributes = "";
            $this->kua->HrefValue = "";
            $this->kua->ExportHrefValue = $this->kua->UploadPath . $this->kua->Upload->DbValue;
            $this->kua->TooltipValue = "";

            // ppas
            $this->ppas->LinkCustomAttributes = "";
            $this->ppas->HrefValue = "";
            $this->ppas->ExportHrefValue = $this->ppas->UploadPath . $this->ppas->Upload->DbValue;
            $this->ppas->TooltipValue = "";

            // skd_rqanun
            $this->skd_rqanun->LinkCustomAttributes = "";
            $this->skd_rqanun->HrefValue = "";
            $this->skd_rqanun->ExportHrefValue = $this->skd_rqanun->UploadPath . $this->skd_rqanun->Upload->DbValue;
            $this->skd_rqanun->TooltipValue = "";

            // nota_keuangan
            $this->nota_keuangan->LinkCustomAttributes = "";
            $this->nota_keuangan->HrefValue = "";
            $this->nota_keuangan->ExportHrefValue = $this->nota_keuangan->UploadPath . $this->nota_keuangan->Upload->DbValue;
            $this->nota_keuangan->TooltipValue = "";

            // pengantar_nota
            $this->pengantar_nota->LinkCustomAttributes = "";
            $this->pengantar_nota->HrefValue = "";
            $this->pengantar_nota->ExportHrefValue = $this->pengantar_nota->UploadPath . $this->pengantar_nota->Upload->DbValue;
            $this->pengantar_nota->TooltipValue = "";

            // risalah_sidang
            $this->risalah_sidang->LinkCustomAttributes = "";
            $this->risalah_sidang->HrefValue = "";
            $this->risalah_sidang->ExportHrefValue = $this->risalah_sidang->UploadPath . $this->risalah_sidang->Upload->DbValue;
            $this->risalah_sidang->TooltipValue = "";

            // bap_apbk
            $this->bap_apbk->LinkCustomAttributes = "";
            $this->bap_apbk->HrefValue = "";
            $this->bap_apbk->ExportHrefValue = $this->bap_apbk->UploadPath . $this->bap_apbk->Upload->DbValue;
            $this->bap_apbk->TooltipValue = "";

            // rq_apbk
            $this->rq_apbk->LinkCustomAttributes = "";
            $this->rq_apbk->HrefValue = "";
            $this->rq_apbk->ExportHrefValue = $this->rq_apbk->UploadPath . $this->rq_apbk->Upload->DbValue;
            $this->rq_apbk->TooltipValue = "";

            // rp_penjabaran
            $this->rp_penjabaran->LinkCustomAttributes = "";
            $this->rp_penjabaran->HrefValue = "";
            $this->rp_penjabaran->ExportHrefValue = $this->rp_penjabaran->UploadPath . $this->rp_penjabaran->Upload->DbValue;
            $this->rp_penjabaran->TooltipValue = "";

            // jadwal_proses
            $this->jadwal_proses->LinkCustomAttributes = "";
            $this->jadwal_proses->HrefValue = "";
            $this->jadwal_proses->ExportHrefValue = $this->jadwal_proses->UploadPath . $this->jadwal_proses->Upload->DbValue;
            $this->jadwal_proses->TooltipValue = "";

            // sinkron_kebijakan
            $this->sinkron_kebijakan->LinkCustomAttributes = "";
            $this->sinkron_kebijakan->HrefValue = "";
            $this->sinkron_kebijakan->ExportHrefValue = $this->sinkron_kebijakan->UploadPath . $this->sinkron_kebijakan->Upload->DbValue;
            $this->sinkron_kebijakan->TooltipValue = "";

            // konsistensi_program
            $this->konsistensi_program->LinkCustomAttributes = "";
            $this->konsistensi_program->HrefValue = "";
            $this->konsistensi_program->ExportHrefValue = $this->konsistensi_program->UploadPath . $this->konsistensi_program->Upload->DbValue;
            $this->konsistensi_program->TooltipValue = "";

            // alokasi_pendidikan
            $this->alokasi_pendidikan->LinkCustomAttributes = "";
            $this->alokasi_pendidikan->HrefValue = "";
            $this->alokasi_pendidikan->ExportHrefValue = $this->alokasi_pendidikan->UploadPath . $this->alokasi_pendidikan->Upload->DbValue;
            $this->alokasi_pendidikan->TooltipValue = "";

            // alokasi_kesehatan
            $this->alokasi_kesehatan->LinkCustomAttributes = "";
            $this->alokasi_kesehatan->HrefValue = "";
            $this->alokasi_kesehatan->ExportHrefValue = $this->alokasi_kesehatan->UploadPath . $this->alokasi_kesehatan->Upload->DbValue;
            $this->alokasi_kesehatan->TooltipValue = "";

            // alokasi_belanja
            $this->alokasi_belanja->LinkCustomAttributes = "";
            $this->alokasi_belanja->HrefValue = "";
            $this->alokasi_belanja->ExportHrefValue = $this->alokasi_belanja->UploadPath . $this->alokasi_belanja->Upload->DbValue;
            $this->alokasi_belanja->TooltipValue = "";

            // bak_kegiatan
            $this->bak_kegiatan->LinkCustomAttributes = "";
            $this->bak_kegiatan->HrefValue = "";
            $this->bak_kegiatan->ExportHrefValue = $this->bak_kegiatan->UploadPath . $this->bak_kegiatan->Upload->DbValue;
            $this->bak_kegiatan->TooltipValue = "";

            // softcopy_rka
            $this->softcopy_rka->LinkCustomAttributes = "";
            $this->softcopy_rka->HrefValue = "";
            $this->softcopy_rka->ExportHrefValue = $this->softcopy_rka->UploadPath . $this->softcopy_rka->Upload->DbValue;
            $this->softcopy_rka->TooltipValue = "";

            // otsus
            $this->otsus->LinkCustomAttributes = "";
            $this->otsus->HrefValue = "";
            $this->otsus->ExportHrefValue = $this->otsus->UploadPath . $this->otsus->Upload->DbValue;
            $this->otsus->TooltipValue = "";

            // qanun_perbup
            $this->qanun_perbup->LinkCustomAttributes = "";
            $this->qanun_perbup->HrefValue = "";
            $this->qanun_perbup->ExportHrefValue = $this->qanun_perbup->UploadPath . $this->qanun_perbup->Upload->DbValue;
            $this->qanun_perbup->TooltipValue = "";

            // tindak_apbkp
            $this->tindak_apbkp->LinkCustomAttributes = "";
            $this->tindak_apbkp->HrefValue = "";
            $this->tindak_apbkp->ExportHrefValue = $this->tindak_apbkp->UploadPath . $this->tindak_apbkp->Upload->DbValue;
            $this->tindak_apbkp->TooltipValue = "";

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
        $item->Body = "<a class=\"btn btn-default ew-search-toggle" . $searchToggleClass . "\" href=\"#\" role=\"button\" title=\"" . $Language->phrase("SearchPanel") . "\" data-caption=\"" . $Language->phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fevaluasilistsrch\" aria-pressed=\"" . ($searchToggleClass == " active" ? "true" : "false") . "\">" . $Language->phrase("SearchLink") . "</a>";
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

    // Show link optionally based on User ID
    protected function showOptionLink($id = "")
    {
        global $Security;
        if ($Security->isLoggedIn() && !$Security->isAdmin() && !$this->userIDAllow($id)) {
            return $Security->isValidUserID($this->idd_user->CurrentValue);
        }
        return true;
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
                case "x_idd_wilayah":
                    break;
                case "x_kd_satker":
                    break;
                case "x_idd_tahapan":
                    break;
                case "x_tahun_anggaran":
                    break;
                case "x_status":
                    break;
                case "x_idd_user":
                    break;
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
