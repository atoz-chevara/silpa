<?php

namespace PHPMaker2021\silpa;

use Doctrine\DBAL\ParameterType;

/**
 * Page class
 */
class Pertanggungjawaban2022List extends Pertanggungjawaban2022
{
    use MessagesTrait;

    // Page ID
    public $PageID = "list";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'pertanggungjawaban2022';

    // Page object name
    public $PageObjName = "Pertanggungjawaban2022List";

    // Rendering View
    public $RenderingView = false;

    // Grid form hidden field names
    public $FormName = "fpertanggungjawaban2022list";
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

        // Table object (pertanggungjawaban2022)
        if (!isset($GLOBALS["pertanggungjawaban2022"]) || get_class($GLOBALS["pertanggungjawaban2022"]) == PROJECT_NAMESPACE . "pertanggungjawaban2022") {
            $GLOBALS["pertanggungjawaban2022"] = &$this;
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
        $this->AddUrl = "pertanggungjawaban2022add";
        $this->InlineAddUrl = $pageUrl . "action=add";
        $this->GridAddUrl = $pageUrl . "action=gridadd";
        $this->GridEditUrl = $pageUrl . "action=gridedit";
        $this->MultiDeleteUrl = "pertanggungjawaban2022delete";
        $this->MultiUpdateUrl = "pertanggungjawaban2022update";

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'pertanggungjawaban2022');
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
        $this->FilterOptions->TagClassName = "ew-filter-option fpertanggungjawaban2022listsrch";

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
                $doc = new $class(Container("pertanggungjawaban2022"));
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
		        $this->surat_pengantar->OldUploadPath = "files/evaluasi/2022/pertanggungjawaban";
		        $this->surat_pengantar->UploadPath = $this->surat_pengantar->OldUploadPath;
		        $this->skd_rqanunpert->OldUploadPath = "files/evaluasi/2022/pertanggungjawaban";
		        $this->skd_rqanunpert->UploadPath = $this->skd_rqanunpert->OldUploadPath;
		        $this->rqanun_apbkpert->OldUploadPath = "files/evaluasi/2022/pertanggungjawaban";
		        $this->rqanun_apbkpert->UploadPath = $this->rqanun_apbkpert->OldUploadPath;
		        $this->rperbup_apbkpert->OldUploadPath = "files/evaluasi/2022/pertanggungjawaban";
		        $this->rperbup_apbkpert->UploadPath = $this->rperbup_apbkpert->OldUploadPath;
		        $this->pbkdd_apbkpert->OldUploadPath = "files/evaluasi/2022/pertanggungjawaban";
		        $this->pbkdd_apbkpert->UploadPath = $this->pbkdd_apbkpert->OldUploadPath;
		        $this->risalah_sidang->OldUploadPath = "files/evaluasi/2022/pertanggungjawaban";
		        $this->risalah_sidang->UploadPath = $this->risalah_sidang->OldUploadPath;
		        $this->absen_peserta->OldUploadPath = "files/evaluasi/2022/pertanggungjawaban";
		        $this->absen_peserta->UploadPath = $this->absen_peserta->OldUploadPath;
		        $this->neraca->OldUploadPath = "files/evaluasi/2022/pertanggungjawaban";
		        $this->neraca->UploadPath = $this->neraca->OldUploadPath;
		        $this->lra->OldUploadPath = "files/evaluasi/2022/pertanggungjawaban";
		        $this->lra->UploadPath = $this->lra->OldUploadPath;
		        $this->calk->OldUploadPath = "files/evaluasi/2022/pertanggungjawaban";
		        $this->calk->UploadPath = $this->calk->OldUploadPath;
		        $this->lo->OldUploadPath = "files/evaluasi/2022/pertanggungjawaban";
		        $this->lo->UploadPath = $this->lo->OldUploadPath;
		        $this->lpe->OldUploadPath = "files/evaluasi/2022/pertanggungjawaban";
		        $this->lpe->UploadPath = $this->lpe->OldUploadPath;
		        $this->lpsal->OldUploadPath = "files/evaluasi/2022/pertanggungjawaban";
		        $this->lpsal->UploadPath = $this->lpsal->OldUploadPath;
		        $this->lak->OldUploadPath = "files/evaluasi/2022/pertanggungjawaban";
		        $this->lak->UploadPath = $this->lak->OldUploadPath;
		        $this->laporan_pemeriksaan->OldUploadPath = "files/evaluasi/2022/pertanggungjawaban";
		        $this->laporan_pemeriksaan->UploadPath = $this->laporan_pemeriksaan->OldUploadPath;
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

        // Create form object
        $CurrentForm = new HttpForm();
        $this->CurrentAction = Param("action"); // Set up current action

        // Get grid add count
        $gridaddcnt = Get(Config("TABLE_GRID_ADD_ROW_COUNT"), "");
        if (is_numeric($gridaddcnt) && $gridaddcnt > 0) {
            $this->GridAddRowCount = $gridaddcnt;
        }

        // Set up list options
        $this->setupListOptions();
        $this->idd_evaluasi->Visible = false;
        $this->kd_satker->setVisibility();
        $this->idd_tahapan->setVisibility();
        $this->tahun_anggaran->setVisibility();
        $this->surat_pengantar->Visible = false;
        $this->skd_rqanunpert->Visible = false;
        $this->rqanun_apbkpert->Visible = false;
        $this->rperbup_apbkpert->Visible = false;
        $this->pbkdd_apbkpert->Visible = false;
        $this->risalah_sidang->Visible = false;
        $this->absen_peserta->Visible = false;
        $this->neraca->Visible = false;
        $this->lra->Visible = false;
        $this->calk->Visible = false;
        $this->lo->Visible = false;
        $this->lpe->Visible = false;
        $this->lpsal->Visible = false;
        $this->lak->Visible = false;
        $this->laporan_pemeriksaan->Visible = false;
        $this->status->setVisibility();
        $this->tanggal_upload->Visible = false;
        $this->tanggal_update->Visible = false;
        $this->idd_user->Visible = false;
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
        $this->setupLookupOptions($this->kd_satker);
        $this->setupLookupOptions($this->idd_tahapan);
        $this->setupLookupOptions($this->tahun_anggaran);
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

            // Check QueryString parameters
            if (Get("action") !== null) {
                $this->CurrentAction = Get("action");

                // Clear inline mode
                if ($this->isCancel()) {
                    $this->clearInlineMode();
                }

                // Switch to inline edit mode
                if ($this->isEdit()) {
                    $this->inlineEditMode();
                }

                // Switch to inline add mode
                if ($this->isAdd() || $this->isCopy()) {
                    $this->inlineAddMode();
                }
            } else {
                if (Post("action") !== null) {
                    $this->CurrentAction = Post("action"); // Get action

                    // Inline Update
                    if (($this->isUpdate() || $this->isOverwrite()) && Session(SESSION_INLINE_MODE) == "edit") {
                        $this->setKey(Post($this->OldKeyName));
                        $this->inlineUpdate();
                    }

                    // Insert Inline
                    if ($this->isInsert() && Session(SESSION_INLINE_MODE) == "add") {
                        $this->setKey(Post($this->OldKeyName));
                        $this->inlineInsert();
                    }
                }
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

    // Exit inline mode
    protected function clearInlineMode()
    {
        $this->LastAction = $this->CurrentAction; // Save last action
        $this->CurrentAction = ""; // Clear action
        $_SESSION[SESSION_INLINE_MODE] = ""; // Clear inline mode
    }

    // Switch to Inline Edit mode
    protected function inlineEditMode()
    {
        global $Security, $Language;
        if (!$Security->canEdit()) {
            return false; // Edit not allowed
        }
        $inlineEdit = true;
        if (($keyValue = Get("idd_evaluasi") ?? Route("idd_evaluasi")) !== null) {
            $this->idd_evaluasi->setQueryStringValue($keyValue);
        } else {
            $inlineEdit = false;
        }
        if ($inlineEdit) {
            if ($this->loadRow()) {
                    // Check if valid User ID
                    if (!$this->showOptionLink("edit")) {
                        $userIdMsg = $Language->phrase("NoEditPermission");
                        $this->setFailureMessage($userIdMsg);
                        $this->clearInlineMode(); // Clear inline edit mode
                        return false;
                    }
                $this->OldKey = $this->getKey(true); // Get from CurrentValue
                $this->setKey($this->OldKey); // Set to OldValue
                $_SESSION[SESSION_INLINE_MODE] = "edit"; // Enable inline edit
            }
        }
        return true;
    }

    // Perform update to Inline Edit record
    protected function inlineUpdate()
    {
        global $Language, $CurrentForm;
        $CurrentForm->Index = 1;
        $this->loadFormValues(); // Get form values

        // Validate form
        $inlineUpdate = true;
        if (!$this->validateForm()) {
            $inlineUpdate = false; // Form error, reset action
        } else {
            $inlineUpdate = false;
            $this->SendEmail = true; // Send email on update success
            $inlineUpdate = $this->editRow(); // Update record
        }
        if ($inlineUpdate) { // Update success
            if ($this->getSuccessMessage() == "") {
                $this->setSuccessMessage($Language->phrase("UpdateSuccess")); // Set up success message
            }
            $this->clearInlineMode(); // Clear inline edit mode
        } else {
            if ($this->getFailureMessage() == "") {
                $this->setFailureMessage($Language->phrase("UpdateFailed")); // Set update failed message
            }
            $this->EventCancelled = true; // Cancel event
            $this->CurrentAction = "edit"; // Stay in edit mode
        }
    }

    // Check Inline Edit key
    public function checkInlineEditKey()
    {
        if (!SameString($this->idd_evaluasi->OldValue, $this->idd_evaluasi->CurrentValue)) {
            return false;
        }
        return true;
    }

    // Switch to Inline Add mode
    protected function inlineAddMode()
    {
        global $Security, $Language;
        if (!$Security->canAdd()) {
            return false; // Add not allowed
        }
        $this->CurrentAction = "add";
        $_SESSION[SESSION_INLINE_MODE] = "add"; // Enable inline add
        return true;
    }

    // Perform update to Inline Add/Copy record
    protected function inlineInsert()
    {
        global $Language, $CurrentForm;
        $this->loadOldRecord(); // Load old record
        $CurrentForm->Index = 0;
        $this->loadFormValues(); // Get form values

        // Validate form
        if (!$this->validateForm()) {
            $this->EventCancelled = true; // Set event cancelled
            $this->CurrentAction = "add"; // Stay in add mode
            return;
        }
        $this->SendEmail = true; // Send email on add success
        if ($this->addRow($this->OldRecordset)) { // Add record
            if ($this->getSuccessMessage() == "") {
                $this->setSuccessMessage($Language->phrase("AddSuccess")); // Set up add success message
            }
            $this->clearInlineMode(); // Clear inline add mode
        } else { // Add failed
            $this->EventCancelled = true; // Set event cancelled
            $this->CurrentAction = "add"; // Stay in add mode
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
        $filterList = Concat($filterList, $this->kd_satker->AdvancedSearch->toJson(), ","); // Field kd_satker
        $filterList = Concat($filterList, $this->idd_tahapan->AdvancedSearch->toJson(), ","); // Field idd_tahapan
        $filterList = Concat($filterList, $this->tahun_anggaran->AdvancedSearch->toJson(), ","); // Field tahun_anggaran
        $filterList = Concat($filterList, $this->surat_pengantar->AdvancedSearch->toJson(), ","); // Field surat_pengantar
        $filterList = Concat($filterList, $this->skd_rqanunpert->AdvancedSearch->toJson(), ","); // Field skd_rqanunpert
        $filterList = Concat($filterList, $this->rqanun_apbkpert->AdvancedSearch->toJson(), ","); // Field rqanun_apbkpert
        $filterList = Concat($filterList, $this->rperbup_apbkpert->AdvancedSearch->toJson(), ","); // Field rperbup_apbkpert
        $filterList = Concat($filterList, $this->pbkdd_apbkpert->AdvancedSearch->toJson(), ","); // Field pbkdd_apbkpert
        $filterList = Concat($filterList, $this->risalah_sidang->AdvancedSearch->toJson(), ","); // Field risalah_sidang
        $filterList = Concat($filterList, $this->absen_peserta->AdvancedSearch->toJson(), ","); // Field absen_peserta
        $filterList = Concat($filterList, $this->neraca->AdvancedSearch->toJson(), ","); // Field neraca
        $filterList = Concat($filterList, $this->lra->AdvancedSearch->toJson(), ","); // Field lra
        $filterList = Concat($filterList, $this->calk->AdvancedSearch->toJson(), ","); // Field calk
        $filterList = Concat($filterList, $this->lo->AdvancedSearch->toJson(), ","); // Field lo
        $filterList = Concat($filterList, $this->lpe->AdvancedSearch->toJson(), ","); // Field lpe
        $filterList = Concat($filterList, $this->lpsal->AdvancedSearch->toJson(), ","); // Field lpsal
        $filterList = Concat($filterList, $this->lak->AdvancedSearch->toJson(), ","); // Field lak
        $filterList = Concat($filterList, $this->laporan_pemeriksaan->AdvancedSearch->toJson(), ","); // Field laporan_pemeriksaan
        $filterList = Concat($filterList, $this->status->AdvancedSearch->toJson(), ","); // Field status
        $filterList = Concat($filterList, $this->tanggal_upload->AdvancedSearch->toJson(), ","); // Field tanggal_upload
        $filterList = Concat($filterList, $this->tanggal_update->AdvancedSearch->toJson(), ","); // Field tanggal_update
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
            $UserProfile->setSearchFilters(CurrentUserName(), "fpertanggungjawaban2022listsrch", $filters);
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

        // Field skd_rqanunpert
        $this->skd_rqanunpert->AdvancedSearch->SearchValue = @$filter["x_skd_rqanunpert"];
        $this->skd_rqanunpert->AdvancedSearch->SearchOperator = @$filter["z_skd_rqanunpert"];
        $this->skd_rqanunpert->AdvancedSearch->SearchCondition = @$filter["v_skd_rqanunpert"];
        $this->skd_rqanunpert->AdvancedSearch->SearchValue2 = @$filter["y_skd_rqanunpert"];
        $this->skd_rqanunpert->AdvancedSearch->SearchOperator2 = @$filter["w_skd_rqanunpert"];
        $this->skd_rqanunpert->AdvancedSearch->save();

        // Field rqanun_apbkpert
        $this->rqanun_apbkpert->AdvancedSearch->SearchValue = @$filter["x_rqanun_apbkpert"];
        $this->rqanun_apbkpert->AdvancedSearch->SearchOperator = @$filter["z_rqanun_apbkpert"];
        $this->rqanun_apbkpert->AdvancedSearch->SearchCondition = @$filter["v_rqanun_apbkpert"];
        $this->rqanun_apbkpert->AdvancedSearch->SearchValue2 = @$filter["y_rqanun_apbkpert"];
        $this->rqanun_apbkpert->AdvancedSearch->SearchOperator2 = @$filter["w_rqanun_apbkpert"];
        $this->rqanun_apbkpert->AdvancedSearch->save();

        // Field rperbup_apbkpert
        $this->rperbup_apbkpert->AdvancedSearch->SearchValue = @$filter["x_rperbup_apbkpert"];
        $this->rperbup_apbkpert->AdvancedSearch->SearchOperator = @$filter["z_rperbup_apbkpert"];
        $this->rperbup_apbkpert->AdvancedSearch->SearchCondition = @$filter["v_rperbup_apbkpert"];
        $this->rperbup_apbkpert->AdvancedSearch->SearchValue2 = @$filter["y_rperbup_apbkpert"];
        $this->rperbup_apbkpert->AdvancedSearch->SearchOperator2 = @$filter["w_rperbup_apbkpert"];
        $this->rperbup_apbkpert->AdvancedSearch->save();

        // Field pbkdd_apbkpert
        $this->pbkdd_apbkpert->AdvancedSearch->SearchValue = @$filter["x_pbkdd_apbkpert"];
        $this->pbkdd_apbkpert->AdvancedSearch->SearchOperator = @$filter["z_pbkdd_apbkpert"];
        $this->pbkdd_apbkpert->AdvancedSearch->SearchCondition = @$filter["v_pbkdd_apbkpert"];
        $this->pbkdd_apbkpert->AdvancedSearch->SearchValue2 = @$filter["y_pbkdd_apbkpert"];
        $this->pbkdd_apbkpert->AdvancedSearch->SearchOperator2 = @$filter["w_pbkdd_apbkpert"];
        $this->pbkdd_apbkpert->AdvancedSearch->save();

        // Field risalah_sidang
        $this->risalah_sidang->AdvancedSearch->SearchValue = @$filter["x_risalah_sidang"];
        $this->risalah_sidang->AdvancedSearch->SearchOperator = @$filter["z_risalah_sidang"];
        $this->risalah_sidang->AdvancedSearch->SearchCondition = @$filter["v_risalah_sidang"];
        $this->risalah_sidang->AdvancedSearch->SearchValue2 = @$filter["y_risalah_sidang"];
        $this->risalah_sidang->AdvancedSearch->SearchOperator2 = @$filter["w_risalah_sidang"];
        $this->risalah_sidang->AdvancedSearch->save();

        // Field absen_peserta
        $this->absen_peserta->AdvancedSearch->SearchValue = @$filter["x_absen_peserta"];
        $this->absen_peserta->AdvancedSearch->SearchOperator = @$filter["z_absen_peserta"];
        $this->absen_peserta->AdvancedSearch->SearchCondition = @$filter["v_absen_peserta"];
        $this->absen_peserta->AdvancedSearch->SearchValue2 = @$filter["y_absen_peserta"];
        $this->absen_peserta->AdvancedSearch->SearchOperator2 = @$filter["w_absen_peserta"];
        $this->absen_peserta->AdvancedSearch->save();

        // Field neraca
        $this->neraca->AdvancedSearch->SearchValue = @$filter["x_neraca"];
        $this->neraca->AdvancedSearch->SearchOperator = @$filter["z_neraca"];
        $this->neraca->AdvancedSearch->SearchCondition = @$filter["v_neraca"];
        $this->neraca->AdvancedSearch->SearchValue2 = @$filter["y_neraca"];
        $this->neraca->AdvancedSearch->SearchOperator2 = @$filter["w_neraca"];
        $this->neraca->AdvancedSearch->save();

        // Field lra
        $this->lra->AdvancedSearch->SearchValue = @$filter["x_lra"];
        $this->lra->AdvancedSearch->SearchOperator = @$filter["z_lra"];
        $this->lra->AdvancedSearch->SearchCondition = @$filter["v_lra"];
        $this->lra->AdvancedSearch->SearchValue2 = @$filter["y_lra"];
        $this->lra->AdvancedSearch->SearchOperator2 = @$filter["w_lra"];
        $this->lra->AdvancedSearch->save();

        // Field calk
        $this->calk->AdvancedSearch->SearchValue = @$filter["x_calk"];
        $this->calk->AdvancedSearch->SearchOperator = @$filter["z_calk"];
        $this->calk->AdvancedSearch->SearchCondition = @$filter["v_calk"];
        $this->calk->AdvancedSearch->SearchValue2 = @$filter["y_calk"];
        $this->calk->AdvancedSearch->SearchOperator2 = @$filter["w_calk"];
        $this->calk->AdvancedSearch->save();

        // Field lo
        $this->lo->AdvancedSearch->SearchValue = @$filter["x_lo"];
        $this->lo->AdvancedSearch->SearchOperator = @$filter["z_lo"];
        $this->lo->AdvancedSearch->SearchCondition = @$filter["v_lo"];
        $this->lo->AdvancedSearch->SearchValue2 = @$filter["y_lo"];
        $this->lo->AdvancedSearch->SearchOperator2 = @$filter["w_lo"];
        $this->lo->AdvancedSearch->save();

        // Field lpe
        $this->lpe->AdvancedSearch->SearchValue = @$filter["x_lpe"];
        $this->lpe->AdvancedSearch->SearchOperator = @$filter["z_lpe"];
        $this->lpe->AdvancedSearch->SearchCondition = @$filter["v_lpe"];
        $this->lpe->AdvancedSearch->SearchValue2 = @$filter["y_lpe"];
        $this->lpe->AdvancedSearch->SearchOperator2 = @$filter["w_lpe"];
        $this->lpe->AdvancedSearch->save();

        // Field lpsal
        $this->lpsal->AdvancedSearch->SearchValue = @$filter["x_lpsal"];
        $this->lpsal->AdvancedSearch->SearchOperator = @$filter["z_lpsal"];
        $this->lpsal->AdvancedSearch->SearchCondition = @$filter["v_lpsal"];
        $this->lpsal->AdvancedSearch->SearchValue2 = @$filter["y_lpsal"];
        $this->lpsal->AdvancedSearch->SearchOperator2 = @$filter["w_lpsal"];
        $this->lpsal->AdvancedSearch->save();

        // Field lak
        $this->lak->AdvancedSearch->SearchValue = @$filter["x_lak"];
        $this->lak->AdvancedSearch->SearchOperator = @$filter["z_lak"];
        $this->lak->AdvancedSearch->SearchCondition = @$filter["v_lak"];
        $this->lak->AdvancedSearch->SearchValue2 = @$filter["y_lak"];
        $this->lak->AdvancedSearch->SearchOperator2 = @$filter["w_lak"];
        $this->lak->AdvancedSearch->save();

        // Field laporan_pemeriksaan
        $this->laporan_pemeriksaan->AdvancedSearch->SearchValue = @$filter["x_laporan_pemeriksaan"];
        $this->laporan_pemeriksaan->AdvancedSearch->SearchOperator = @$filter["z_laporan_pemeriksaan"];
        $this->laporan_pemeriksaan->AdvancedSearch->SearchCondition = @$filter["v_laporan_pemeriksaan"];
        $this->laporan_pemeriksaan->AdvancedSearch->SearchValue2 = @$filter["y_laporan_pemeriksaan"];
        $this->laporan_pemeriksaan->AdvancedSearch->SearchOperator2 = @$filter["w_laporan_pemeriksaan"];
        $this->laporan_pemeriksaan->AdvancedSearch->save();

        // Field status
        $this->status->AdvancedSearch->SearchValue = @$filter["x_status"];
        $this->status->AdvancedSearch->SearchOperator = @$filter["z_status"];
        $this->status->AdvancedSearch->SearchCondition = @$filter["v_status"];
        $this->status->AdvancedSearch->SearchValue2 = @$filter["y_status"];
        $this->status->AdvancedSearch->SearchOperator2 = @$filter["w_status"];
        $this->status->AdvancedSearch->save();

        // Field tanggal_upload
        $this->tanggal_upload->AdvancedSearch->SearchValue = @$filter["x_tanggal_upload"];
        $this->tanggal_upload->AdvancedSearch->SearchOperator = @$filter["z_tanggal_upload"];
        $this->tanggal_upload->AdvancedSearch->SearchCondition = @$filter["v_tanggal_upload"];
        $this->tanggal_upload->AdvancedSearch->SearchValue2 = @$filter["y_tanggal_upload"];
        $this->tanggal_upload->AdvancedSearch->SearchOperator2 = @$filter["w_tanggal_upload"];
        $this->tanggal_upload->AdvancedSearch->save();

        // Field tanggal_update
        $this->tanggal_update->AdvancedSearch->SearchValue = @$filter["x_tanggal_update"];
        $this->tanggal_update->AdvancedSearch->SearchOperator = @$filter["z_tanggal_update"];
        $this->tanggal_update->AdvancedSearch->SearchCondition = @$filter["v_tanggal_update"];
        $this->tanggal_update->AdvancedSearch->SearchValue2 = @$filter["y_tanggal_update"];
        $this->tanggal_update->AdvancedSearch->SearchOperator2 = @$filter["w_tanggal_update"];
        $this->tanggal_update->AdvancedSearch->save();

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
        $this->buildBasicSearchSql($where, $this->surat_pengantar, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->skd_rqanunpert, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->rqanun_apbkpert, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->rperbup_apbkpert, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->pbkdd_apbkpert, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->risalah_sidang, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->absen_peserta, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->neraca, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->lra, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->calk, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->lo, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->lpe, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->lpsal, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->lak, $arKeywords, $type);
        $this->buildBasicSearchSql($where, $this->laporan_pemeriksaan, $arKeywords, $type);
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
            $this->updateSort($this->kd_satker); // kd_satker
            $this->updateSort($this->idd_tahapan); // idd_tahapan
            $this->updateSort($this->tahun_anggaran); // tahun_anggaran
            $this->updateSort($this->status); // status
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
                $this->kd_satker->setSort("");
                $this->idd_tahapan->setSort("");
                $this->tahun_anggaran->setSort("");
                $this->surat_pengantar->setSort("");
                $this->skd_rqanunpert->setSort("");
                $this->rqanun_apbkpert->setSort("");
                $this->rperbup_apbkpert->setSort("");
                $this->pbkdd_apbkpert->setSort("");
                $this->risalah_sidang->setSort("");
                $this->absen_peserta->setSort("");
                $this->neraca->setSort("");
                $this->lra->setSort("");
                $this->calk->setSort("");
                $this->lo->setSort("");
                $this->lpe->setSort("");
                $this->lpsal->setSort("");
                $this->lak->setSort("");
                $this->laporan_pemeriksaan->setSort("");
                $this->status->setSort("");
                $this->tanggal_upload->setSort("");
                $this->tanggal_update->setSort("");
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

        // Set up row action and key
        if ($CurrentForm && is_numeric($this->RowIndex) && $this->RowType != "view") {
            $CurrentForm->Index = $this->RowIndex;
            $actionName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormActionName);
            $oldKeyName = str_replace("k_", "k" . $this->RowIndex . "_", $this->OldKeyName);
            $blankRowName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormBlankRowName);
            if ($this->RowAction != "") {
                $this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $actionName . "\" id=\"" . $actionName . "\" value=\"" . $this->RowAction . "\">";
            }
            $oldKey = $this->getKey(false); // Get from OldValue
            if ($oldKeyName != "" && $oldKey != "") {
                $this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $oldKeyName . "\" id=\"" . $oldKeyName . "\" value=\"" . HtmlEncode($oldKey) . "\">";
            }
            if ($this->RowAction == "insert" && $this->isConfirm() && $this->emptyRow()) {
                $this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $blankRowName . "\" id=\"" . $blankRowName . "\" value=\"1\">";
            }
        }
        $pageUrl = $this->pageUrl();

        // "copy"
        $opt = $this->ListOptions["copy"];
        if ($this->isInlineAddRow() || $this->isInlineCopyRow()) { // Inline Add/Copy
            $this->ListOptions->CustomItem = "copy"; // Show copy column only
            $cancelurl = $this->addMasterUrl($pageUrl . "action=cancel");
            $opt->Body = "<div" . (($opt->OnLeft) ? " class=\"text-right\"" : "") . ">" .
            "<a class=\"ew-grid-link ew-inline-insert\" title=\"" . HtmlTitle($Language->phrase("InsertLink")) . "\" data-caption=\"" . HtmlTitle($Language->phrase("InsertLink")) . "\" href=\"#\" onclick=\"ew.forms.get(this).submit(event, '" . $this->pageName() . "'); return false;\">" . $Language->phrase("InsertLink") . "</a>&nbsp;" .
            "<a class=\"ew-grid-link ew-inline-cancel\" title=\"" . HtmlTitle($Language->phrase("CancelLink")) . "\" data-caption=\"" . HtmlTitle($Language->phrase("CancelLink")) . "\" href=\"" . $cancelurl . "\">" . $Language->phrase("CancelLink") . "</a>" .
            "<input type=\"hidden\" name=\"action\" id=\"action\" value=\"insert\"></div>";
            return;
        }

        // "edit"
        $opt = $this->ListOptions["edit"];
        if ($this->isInlineEditRow()) { // Inline-Edit
            $this->ListOptions->CustomItem = "edit"; // Show edit column only
            $cancelurl = $this->addMasterUrl($pageUrl . "action=cancel");
                $opt->Body = "<div" . (($opt->OnLeft) ? " class=\"text-right\"" : "") . ">" .
                "<a class=\"ew-grid-link ew-inline-update\" title=\"" . HtmlTitle($Language->phrase("UpdateLink")) . "\" data-caption=\"" . HtmlTitle($Language->phrase("UpdateLink")) . "\" href=\"#\" onclick=\"ew.forms.get(this).submit(event, '" . UrlAddHash($this->pageName(), "r" . $this->RowCount . "_" . $this->TableVar) . "'); return false;\">" . $Language->phrase("UpdateLink") . "</a>&nbsp;" .
                "<a class=\"ew-grid-link ew-inline-cancel\" title=\"" . HtmlTitle($Language->phrase("CancelLink")) . "\" data-caption=\"" . HtmlTitle($Language->phrase("CancelLink")) . "\" href=\"" . $cancelurl . "\">" . $Language->phrase("CancelLink") . "</a>" .
                "<input type=\"hidden\" name=\"action\" id=\"action\" value=\"update\"></div>";
            $opt->Body .= "<input type=\"hidden\" name=\"k" . $this->RowIndex . "_key\" id=\"k" . $this->RowIndex . "_key\" value=\"" . HtmlEncode($this->idd_evaluasi->CurrentValue) . "\">";
            return;
        }
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
                $opt->Body .= "<a class=\"ew-row-link ew-inline-edit\" title=\"" . HtmlTitle($Language->phrase("InlineEditLink")) . "\" data-caption=\"" . HtmlTitle($Language->phrase("InlineEditLink")) . "\" href=\"" . HtmlEncode(UrlAddHash(GetUrl($this->InlineEditUrl), "r" . $this->RowCount . "_" . $this->TableVar)) . "\">" . $Language->phrase("InlineEditLink") . "</a>";
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

        // Inline Add
        $item = &$option->add("inlineadd");
        $item->Body = "<a class=\"ew-add-edit ew-inline-add\" title=\"" . HtmlTitle($Language->phrase("InlineAddLink")) . "\" data-caption=\"" . HtmlTitle($Language->phrase("InlineAddLink")) . "\" href=\"" . HtmlEncode(GetUrl($this->InlineAddUrl)) . "\">" . $Language->phrase("InlineAddLink") . "</a>";
        $item->Visible = $this->InlineAddUrl != "" && $Security->canAdd();
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
        $item->Body = "<a class=\"ew-save-filter\" data-form=\"fpertanggungjawaban2022listsrch\" href=\"#\" onclick=\"return false;\">" . $Language->phrase("SaveCurrentFilter") . "</a>";
        $item->Visible = true;
        $item = &$this->FilterOptions->add("deletefilter");
        $item->Body = "<a class=\"ew-delete-filter\" data-form=\"fpertanggungjawaban2022listsrch\" href=\"#\" onclick=\"return false;\">" . $Language->phrase("DeleteFilter") . "</a>";
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
                $item->Body = '<a class="ew-action ew-list-action" title="' . HtmlEncode($caption) . '" data-caption="' . HtmlEncode($caption) . '" href="#" onclick="return ew.submitAction(event,jQuery.extend({f:document.fpertanggungjawaban2022list},' . $listaction->toJson(true) . '));">' . $icon . '</a>';
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

    // Load default values
    protected function loadDefaultValues()
    {
        $this->idd_evaluasi->CurrentValue = null;
        $this->idd_evaluasi->OldValue = $this->idd_evaluasi->CurrentValue;
        $this->kd_satker->CurrentValue = null;
        $this->kd_satker->OldValue = $this->kd_satker->CurrentValue;
        $this->idd_tahapan->CurrentValue = null;
        $this->idd_tahapan->OldValue = $this->idd_tahapan->CurrentValue;
        $this->tahun_anggaran->CurrentValue = null;
        $this->tahun_anggaran->OldValue = $this->tahun_anggaran->CurrentValue;
        $this->surat_pengantar->Upload->DbValue = null;
        $this->surat_pengantar->OldValue = $this->surat_pengantar->Upload->DbValue;
        $this->skd_rqanunpert->Upload->DbValue = null;
        $this->skd_rqanunpert->OldValue = $this->skd_rqanunpert->Upload->DbValue;
        $this->rqanun_apbkpert->Upload->DbValue = null;
        $this->rqanun_apbkpert->OldValue = $this->rqanun_apbkpert->Upload->DbValue;
        $this->rperbup_apbkpert->Upload->DbValue = null;
        $this->rperbup_apbkpert->OldValue = $this->rperbup_apbkpert->Upload->DbValue;
        $this->pbkdd_apbkpert->Upload->DbValue = null;
        $this->pbkdd_apbkpert->OldValue = $this->pbkdd_apbkpert->Upload->DbValue;
        $this->risalah_sidang->Upload->DbValue = null;
        $this->risalah_sidang->OldValue = $this->risalah_sidang->Upload->DbValue;
        $this->absen_peserta->Upload->DbValue = null;
        $this->absen_peserta->OldValue = $this->absen_peserta->Upload->DbValue;
        $this->neraca->Upload->DbValue = null;
        $this->neraca->OldValue = $this->neraca->Upload->DbValue;
        $this->lra->Upload->DbValue = null;
        $this->lra->OldValue = $this->lra->Upload->DbValue;
        $this->calk->Upload->DbValue = null;
        $this->calk->OldValue = $this->calk->Upload->DbValue;
        $this->lo->Upload->DbValue = null;
        $this->lo->OldValue = $this->lo->Upload->DbValue;
        $this->lpe->Upload->DbValue = null;
        $this->lpe->OldValue = $this->lpe->Upload->DbValue;
        $this->lpsal->Upload->DbValue = null;
        $this->lpsal->OldValue = $this->lpsal->Upload->DbValue;
        $this->lak->Upload->DbValue = null;
        $this->lak->OldValue = $this->lak->Upload->DbValue;
        $this->laporan_pemeriksaan->Upload->DbValue = null;
        $this->laporan_pemeriksaan->OldValue = $this->laporan_pemeriksaan->Upload->DbValue;
        $this->status->CurrentValue = null;
        $this->status->OldValue = $this->status->CurrentValue;
        $this->tanggal_upload->CurrentValue = null;
        $this->tanggal_upload->OldValue = $this->tanggal_upload->CurrentValue;
        $this->tanggal_update->CurrentValue = null;
        $this->tanggal_update->OldValue = $this->tanggal_update->CurrentValue;
        $this->idd_user->CurrentValue = CurrentUserID();
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

    // Load form values
    protected function loadFormValues()
    {
        // Load from form
        global $CurrentForm;

        // Check field name 'kd_satker' first before field var 'x_kd_satker'
        $val = $CurrentForm->hasValue("kd_satker") ? $CurrentForm->getValue("kd_satker") : $CurrentForm->getValue("x_kd_satker");
        if (!$this->kd_satker->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->kd_satker->Visible = false; // Disable update for API request
            } else {
                $this->kd_satker->setFormValue($val);
            }
        }

        // Check field name 'idd_tahapan' first before field var 'x_idd_tahapan'
        $val = $CurrentForm->hasValue("idd_tahapan") ? $CurrentForm->getValue("idd_tahapan") : $CurrentForm->getValue("x_idd_tahapan");
        if (!$this->idd_tahapan->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->idd_tahapan->Visible = false; // Disable update for API request
            } else {
                $this->idd_tahapan->setFormValue($val);
            }
        }

        // Check field name 'tahun_anggaran' first before field var 'x_tahun_anggaran'
        $val = $CurrentForm->hasValue("tahun_anggaran") ? $CurrentForm->getValue("tahun_anggaran") : $CurrentForm->getValue("x_tahun_anggaran");
        if (!$this->tahun_anggaran->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->tahun_anggaran->Visible = false; // Disable update for API request
            } else {
                $this->tahun_anggaran->setFormValue($val);
            }
        }

        // Check field name 'status' first before field var 'x_status'
        $val = $CurrentForm->hasValue("status") ? $CurrentForm->getValue("status") : $CurrentForm->getValue("x_status");
        if (!$this->status->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->status->Visible = false; // Disable update for API request
            } else {
                $this->status->setFormValue($val);
            }
        }

        // Check field name 'idd_evaluasi' first before field var 'x_idd_evaluasi'
        $val = $CurrentForm->hasValue("idd_evaluasi") ? $CurrentForm->getValue("idd_evaluasi") : $CurrentForm->getValue("x_idd_evaluasi");
        if (!$this->idd_evaluasi->IsDetailKey && !$this->isGridAdd() && !$this->isAdd()) {
            $this->idd_evaluasi->setFormValue($val);
        }
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        if (!$this->isGridAdd() && !$this->isAdd()) {
            $this->idd_evaluasi->CurrentValue = $this->idd_evaluasi->FormValue;
        }
        $this->kd_satker->CurrentValue = $this->kd_satker->FormValue;
        $this->idd_tahapan->CurrentValue = $this->idd_tahapan->FormValue;
        $this->tahun_anggaran->CurrentValue = $this->tahun_anggaran->FormValue;
        $this->status->CurrentValue = $this->status->FormValue;
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
            if (!$this->EventCancelled) {
                $this->HashValue = $this->getRowHash($row); // Get hash value for record
            }
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
        $this->kd_satker->setDbValue($row['kd_satker']);
        $this->idd_tahapan->setDbValue($row['idd_tahapan']);
        $this->tahun_anggaran->setDbValue($row['tahun_anggaran']);
        $this->surat_pengantar->Upload->DbValue = $row['surat_pengantar'];
        $this->surat_pengantar->setDbValue($this->surat_pengantar->Upload->DbValue);
        $this->skd_rqanunpert->Upload->DbValue = $row['skd_rqanunpert'];
        $this->skd_rqanunpert->setDbValue($this->skd_rqanunpert->Upload->DbValue);
        $this->rqanun_apbkpert->Upload->DbValue = $row['rqanun_apbkpert'];
        $this->rqanun_apbkpert->setDbValue($this->rqanun_apbkpert->Upload->DbValue);
        $this->rperbup_apbkpert->Upload->DbValue = $row['rperbup_apbkpert'];
        $this->rperbup_apbkpert->setDbValue($this->rperbup_apbkpert->Upload->DbValue);
        $this->pbkdd_apbkpert->Upload->DbValue = $row['pbkdd_apbkpert'];
        $this->pbkdd_apbkpert->setDbValue($this->pbkdd_apbkpert->Upload->DbValue);
        $this->risalah_sidang->Upload->DbValue = $row['risalah_sidang'];
        $this->risalah_sidang->setDbValue($this->risalah_sidang->Upload->DbValue);
        $this->absen_peserta->Upload->DbValue = $row['absen_peserta'];
        $this->absen_peserta->setDbValue($this->absen_peserta->Upload->DbValue);
        $this->neraca->Upload->DbValue = $row['neraca'];
        $this->neraca->setDbValue($this->neraca->Upload->DbValue);
        $this->lra->Upload->DbValue = $row['lra'];
        $this->lra->setDbValue($this->lra->Upload->DbValue);
        $this->calk->Upload->DbValue = $row['calk'];
        $this->calk->setDbValue($this->calk->Upload->DbValue);
        $this->lo->Upload->DbValue = $row['lo'];
        $this->lo->setDbValue($this->lo->Upload->DbValue);
        $this->lpe->Upload->DbValue = $row['lpe'];
        $this->lpe->setDbValue($this->lpe->Upload->DbValue);
        $this->lpsal->Upload->DbValue = $row['lpsal'];
        $this->lpsal->setDbValue($this->lpsal->Upload->DbValue);
        $this->lak->Upload->DbValue = $row['lak'];
        $this->lak->setDbValue($this->lak->Upload->DbValue);
        $this->laporan_pemeriksaan->Upload->DbValue = $row['laporan_pemeriksaan'];
        $this->laporan_pemeriksaan->setDbValue($this->laporan_pemeriksaan->Upload->DbValue);
        $this->status->setDbValue($row['status']);
        $this->tanggal_upload->setDbValue($row['tanggal_upload']);
        $this->tanggal_update->setDbValue($row['tanggal_update']);
        $this->idd_user->setDbValue($row['idd_user']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $this->loadDefaultValues();
        $row = [];
        $row['idd_evaluasi'] = $this->idd_evaluasi->CurrentValue;
        $row['kd_satker'] = $this->kd_satker->CurrentValue;
        $row['idd_tahapan'] = $this->idd_tahapan->CurrentValue;
        $row['tahun_anggaran'] = $this->tahun_anggaran->CurrentValue;
        $row['surat_pengantar'] = $this->surat_pengantar->Upload->DbValue;
        $row['skd_rqanunpert'] = $this->skd_rqanunpert->Upload->DbValue;
        $row['rqanun_apbkpert'] = $this->rqanun_apbkpert->Upload->DbValue;
        $row['rperbup_apbkpert'] = $this->rperbup_apbkpert->Upload->DbValue;
        $row['pbkdd_apbkpert'] = $this->pbkdd_apbkpert->Upload->DbValue;
        $row['risalah_sidang'] = $this->risalah_sidang->Upload->DbValue;
        $row['absen_peserta'] = $this->absen_peserta->Upload->DbValue;
        $row['neraca'] = $this->neraca->Upload->DbValue;
        $row['lra'] = $this->lra->Upload->DbValue;
        $row['calk'] = $this->calk->Upload->DbValue;
        $row['lo'] = $this->lo->Upload->DbValue;
        $row['lpe'] = $this->lpe->Upload->DbValue;
        $row['lpsal'] = $this->lpsal->Upload->DbValue;
        $row['lak'] = $this->lak->Upload->DbValue;
        $row['laporan_pemeriksaan'] = $this->laporan_pemeriksaan->Upload->DbValue;
        $row['status'] = $this->status->CurrentValue;
        $row['tanggal_upload'] = $this->tanggal_upload->CurrentValue;
        $row['tanggal_update'] = $this->tanggal_update->CurrentValue;
        $row['idd_user'] = $this->idd_user->CurrentValue;
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

        // kd_satker

        // idd_tahapan

        // tahun_anggaran

        // surat_pengantar

        // skd_rqanunpert

        // rqanun_apbkpert

        // rperbup_apbkpert

        // pbkdd_apbkpert

        // risalah_sidang

        // absen_peserta

        // neraca

        // lra

        // calk

        // lo

        // lpe

        // lpsal

        // lak

        // laporan_pemeriksaan

        // status

        // tanggal_upload

        // tanggal_update

        // idd_user
        if ($this->RowType == ROWTYPE_VIEW) {
            // idd_evaluasi
            $this->idd_evaluasi->ViewValue = $this->idd_evaluasi->CurrentValue;
            $this->idd_evaluasi->ViewCustomAttributes = "";

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
            $curVal = trim(strval($this->tahun_anggaran->CurrentValue));
            if ($curVal != "") {
                $this->tahun_anggaran->ViewValue = $this->tahun_anggaran->lookupCacheOption($curVal);
                if ($this->tahun_anggaran->ViewValue === null) { // Lookup from database
                    $filterWrk = "`id_tahun`" . SearchString("=", $curVal, DATATYPE_STRING, "");
                    $sqlWrk = $this->tahun_anggaran->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->tahun_anggaran->Lookup->renderViewRow($rswrk[0]);
                        $this->tahun_anggaran->ViewValue = $this->tahun_anggaran->displayValue($arwrk);
                    } else {
                        $this->tahun_anggaran->ViewValue = $this->tahun_anggaran->CurrentValue;
                    }
                }
            } else {
                $this->tahun_anggaran->ViewValue = null;
            }
            $this->tahun_anggaran->ViewCustomAttributes = "";

            // surat_pengantar
            $this->surat_pengantar->UploadPath = "files/evaluasi/2022/pertanggungjawaban";
            if (!EmptyValue($this->surat_pengantar->Upload->DbValue)) {
                $this->surat_pengantar->ViewValue = $this->surat_pengantar->Upload->DbValue;
            } else {
                $this->surat_pengantar->ViewValue = "";
            }
            $this->surat_pengantar->CssClass = "font-italic";
            $this->surat_pengantar->ViewCustomAttributes = "";

            // skd_rqanunpert
            $this->skd_rqanunpert->UploadPath = "files/evaluasi/2022/pertanggungjawaban";
            if (!EmptyValue($this->skd_rqanunpert->Upload->DbValue)) {
                $this->skd_rqanunpert->ViewValue = $this->skd_rqanunpert->Upload->DbValue;
            } else {
                $this->skd_rqanunpert->ViewValue = "";
            }
            $this->skd_rqanunpert->CssClass = "font-italic";
            $this->skd_rqanunpert->ViewCustomAttributes = "";

            // rqanun_apbkpert
            $this->rqanun_apbkpert->UploadPath = "files/evaluasi/2022/pertanggungjawaban";
            if (!EmptyValue($this->rqanun_apbkpert->Upload->DbValue)) {
                $this->rqanun_apbkpert->ViewValue = $this->rqanun_apbkpert->Upload->DbValue;
            } else {
                $this->rqanun_apbkpert->ViewValue = "";
            }
            $this->rqanun_apbkpert->CssClass = "font-italic";
            $this->rqanun_apbkpert->ViewCustomAttributes = "";

            // rperbup_apbkpert
            $this->rperbup_apbkpert->UploadPath = "files/evaluasi/2022/pertanggungjawaban";
            if (!EmptyValue($this->rperbup_apbkpert->Upload->DbValue)) {
                $this->rperbup_apbkpert->ViewValue = $this->rperbup_apbkpert->Upload->DbValue;
            } else {
                $this->rperbup_apbkpert->ViewValue = "";
            }
            $this->rperbup_apbkpert->CssClass = "font-italic";
            $this->rperbup_apbkpert->ViewCustomAttributes = "";

            // pbkdd_apbkpert
            $this->pbkdd_apbkpert->UploadPath = "files/evaluasi/2022/pertanggungjawaban";
            if (!EmptyValue($this->pbkdd_apbkpert->Upload->DbValue)) {
                $this->pbkdd_apbkpert->ViewValue = $this->pbkdd_apbkpert->Upload->DbValue;
            } else {
                $this->pbkdd_apbkpert->ViewValue = "";
            }
            $this->pbkdd_apbkpert->CssClass = "font-italic";
            $this->pbkdd_apbkpert->ViewCustomAttributes = "";

            // risalah_sidang
            $this->risalah_sidang->UploadPath = "files/evaluasi/2022/pertanggungjawaban";
            if (!EmptyValue($this->risalah_sidang->Upload->DbValue)) {
                $this->risalah_sidang->ViewValue = $this->risalah_sidang->Upload->DbValue;
            } else {
                $this->risalah_sidang->ViewValue = "";
            }
            $this->risalah_sidang->CssClass = "font-italic";
            $this->risalah_sidang->ViewCustomAttributes = "";

            // absen_peserta
            $this->absen_peserta->UploadPath = "files/evaluasi/2022/pertanggungjawaban";
            if (!EmptyValue($this->absen_peserta->Upload->DbValue)) {
                $this->absen_peserta->ViewValue = $this->absen_peserta->Upload->DbValue;
            } else {
                $this->absen_peserta->ViewValue = "";
            }
            $this->absen_peserta->CssClass = "font-italic";
            $this->absen_peserta->ViewCustomAttributes = "";

            // neraca
            $this->neraca->UploadPath = "files/evaluasi/2022/pertanggungjawaban";
            if (!EmptyValue($this->neraca->Upload->DbValue)) {
                $this->neraca->ViewValue = $this->neraca->Upload->DbValue;
            } else {
                $this->neraca->ViewValue = "";
            }
            $this->neraca->CssClass = "font-italic";
            $this->neraca->ViewCustomAttributes = "";

            // lra
            $this->lra->UploadPath = "files/evaluasi/2022/pertanggungjawaban";
            if (!EmptyValue($this->lra->Upload->DbValue)) {
                $this->lra->ViewValue = $this->lra->Upload->DbValue;
            } else {
                $this->lra->ViewValue = "";
            }
            $this->lra->CssClass = "font-italic";
            $this->lra->ViewCustomAttributes = "";

            // calk
            $this->calk->UploadPath = "files/evaluasi/2022/pertanggungjawaban";
            if (!EmptyValue($this->calk->Upload->DbValue)) {
                $this->calk->ViewValue = $this->calk->Upload->DbValue;
            } else {
                $this->calk->ViewValue = "";
            }
            $this->calk->CssClass = "font-italic";
            $this->calk->ViewCustomAttributes = "";

            // lo
            $this->lo->UploadPath = "files/evaluasi/2022/pertanggungjawaban";
            if (!EmptyValue($this->lo->Upload->DbValue)) {
                $this->lo->ViewValue = $this->lo->Upload->DbValue;
            } else {
                $this->lo->ViewValue = "";
            }
            $this->lo->CssClass = "font-italic";
            $this->lo->ViewCustomAttributes = "";

            // lpe
            $this->lpe->UploadPath = "files/evaluasi/2022/pertanggungjawaban";
            if (!EmptyValue($this->lpe->Upload->DbValue)) {
                $this->lpe->ViewValue = $this->lpe->Upload->DbValue;
            } else {
                $this->lpe->ViewValue = "";
            }
            $this->lpe->CssClass = "font-italic";
            $this->lpe->ViewCustomAttributes = "";

            // lpsal
            $this->lpsal->UploadPath = "files/evaluasi/2022/pertanggungjawaban";
            if (!EmptyValue($this->lpsal->Upload->DbValue)) {
                $this->lpsal->ViewValue = $this->lpsal->Upload->DbValue;
            } else {
                $this->lpsal->ViewValue = "";
            }
            $this->lpsal->CssClass = "font-italic";
            $this->lpsal->ViewCustomAttributes = "";

            // lak
            $this->lak->UploadPath = "files/evaluasi/2022/pertanggungjawaban";
            if (!EmptyValue($this->lak->Upload->DbValue)) {
                $this->lak->ViewValue = $this->lak->Upload->DbValue;
            } else {
                $this->lak->ViewValue = "";
            }
            $this->lak->CssClass = "font-italic";
            $this->lak->ViewCustomAttributes = "";

            // laporan_pemeriksaan
            $this->laporan_pemeriksaan->UploadPath = "files/evaluasi/2022/pertanggungjawaban";
            if (!EmptyValue($this->laporan_pemeriksaan->Upload->DbValue)) {
                $this->laporan_pemeriksaan->ViewValue = $this->laporan_pemeriksaan->Upload->DbValue;
            } else {
                $this->laporan_pemeriksaan->ViewValue = "";
            }
            $this->laporan_pemeriksaan->CssClass = "font-italic";
            $this->laporan_pemeriksaan->ViewCustomAttributes = "";

            // status
            if (strval($this->status->CurrentValue) != "") {
                $this->status->ViewValue = $this->status->optionCaption($this->status->CurrentValue);
            } else {
                $this->status->ViewValue = null;
            }
            $this->status->ViewCustomAttributes = "";

            // tanggal_upload
            $this->tanggal_upload->ViewValue = $this->tanggal_upload->CurrentValue;
            $this->tanggal_upload->ViewCustomAttributes = "";

            // tanggal_update
            $this->tanggal_update->ViewValue = $this->tanggal_update->CurrentValue;
            $this->tanggal_update->ViewCustomAttributes = "";

            // idd_user
            $this->idd_user->ViewValue = $this->idd_user->CurrentValue;
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

            // status
            $this->status->LinkCustomAttributes = "";
            $this->status->HrefValue = "";
            $this->status->TooltipValue = "";
        } elseif ($this->RowType == ROWTYPE_ADD) {
            // kd_satker
            $this->kd_satker->EditAttrs["class"] = "form-control";
            $this->kd_satker->EditCustomAttributes = "";
            $curVal = trim(strval($this->kd_satker->CurrentValue));
            if ($curVal != "") {
                $this->kd_satker->ViewValue = $this->kd_satker->lookupCacheOption($curVal);
            } else {
                $this->kd_satker->ViewValue = $this->kd_satker->Lookup !== null && is_array($this->kd_satker->Lookup->Options) ? $curVal : null;
            }
            if ($this->kd_satker->ViewValue !== null) { // Load from cache
                $this->kd_satker->EditValue = array_values($this->kd_satker->Lookup->Options);
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = "`kode_pemda`" . SearchString("=", $this->kd_satker->CurrentValue, DATATYPE_STRING, "");
                }
                $sqlWrk = $this->kd_satker->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->kd_satker->EditValue = $arwrk;
            }
            $this->kd_satker->PlaceHolder = RemoveHtml($this->kd_satker->caption());

            // idd_tahapan
            $this->idd_tahapan->EditAttrs["class"] = "form-control";
            $this->idd_tahapan->EditCustomAttributes = "";
            $curVal = trim(strval($this->idd_tahapan->CurrentValue));
            if ($curVal != "") {
                $this->idd_tahapan->ViewValue = $this->idd_tahapan->lookupCacheOption($curVal);
            } else {
                $this->idd_tahapan->ViewValue = $this->idd_tahapan->Lookup !== null && is_array($this->idd_tahapan->Lookup->Options) ? $curVal : null;
            }
            if ($this->idd_tahapan->ViewValue !== null) { // Load from cache
                $this->idd_tahapan->EditValue = array_values($this->idd_tahapan->Lookup->Options);
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = "`idd_tahapan`" . SearchString("=", $this->idd_tahapan->CurrentValue, DATATYPE_NUMBER, "");
                }
                $sqlWrk = $this->idd_tahapan->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->idd_tahapan->EditValue = $arwrk;
            }
            $this->idd_tahapan->PlaceHolder = RemoveHtml($this->idd_tahapan->caption());

            // tahun_anggaran
            $this->tahun_anggaran->EditAttrs["class"] = "form-control";
            $this->tahun_anggaran->EditCustomAttributes = "";
            $curVal = trim(strval($this->tahun_anggaran->CurrentValue));
            if ($curVal != "") {
                $this->tahun_anggaran->ViewValue = $this->tahun_anggaran->lookupCacheOption($curVal);
            } else {
                $this->tahun_anggaran->ViewValue = $this->tahun_anggaran->Lookup !== null && is_array($this->tahun_anggaran->Lookup->Options) ? $curVal : null;
            }
            if ($this->tahun_anggaran->ViewValue !== null) { // Load from cache
                $this->tahun_anggaran->EditValue = array_values($this->tahun_anggaran->Lookup->Options);
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = "`id_tahun`" . SearchString("=", $this->tahun_anggaran->CurrentValue, DATATYPE_STRING, "");
                }
                $sqlWrk = $this->tahun_anggaran->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->tahun_anggaran->EditValue = $arwrk;
            }
            $this->tahun_anggaran->PlaceHolder = RemoveHtml($this->tahun_anggaran->caption());

            // status
            $this->status->EditAttrs["class"] = "form-control";
            $this->status->EditCustomAttributes = "";
            $this->status->EditValue = $this->status->options(true);
            $this->status->PlaceHolder = RemoveHtml($this->status->caption());

            // Add refer script

            // kd_satker
            $this->kd_satker->LinkCustomAttributes = "";
            $this->kd_satker->HrefValue = "";

            // idd_tahapan
            $this->idd_tahapan->LinkCustomAttributes = "";
            $this->idd_tahapan->HrefValue = "";

            // tahun_anggaran
            $this->tahun_anggaran->LinkCustomAttributes = "";
            $this->tahun_anggaran->HrefValue = "";

            // status
            $this->status->LinkCustomAttributes = "";
            $this->status->HrefValue = "";
        } elseif ($this->RowType == ROWTYPE_EDIT) {
            // kd_satker
            $this->kd_satker->EditAttrs["class"] = "form-control";
            $this->kd_satker->EditCustomAttributes = "";
            $curVal = trim(strval($this->kd_satker->CurrentValue));
            if ($curVal != "") {
                $this->kd_satker->ViewValue = $this->kd_satker->lookupCacheOption($curVal);
            } else {
                $this->kd_satker->ViewValue = $this->kd_satker->Lookup !== null && is_array($this->kd_satker->Lookup->Options) ? $curVal : null;
            }
            if ($this->kd_satker->ViewValue !== null) { // Load from cache
                $this->kd_satker->EditValue = array_values($this->kd_satker->Lookup->Options);
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = "`kode_pemda`" . SearchString("=", $this->kd_satker->CurrentValue, DATATYPE_STRING, "");
                }
                $sqlWrk = $this->kd_satker->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->kd_satker->EditValue = $arwrk;
            }
            $this->kd_satker->PlaceHolder = RemoveHtml($this->kd_satker->caption());

            // idd_tahapan
            $this->idd_tahapan->EditAttrs["class"] = "form-control";
            $this->idd_tahapan->EditCustomAttributes = "";
            $curVal = trim(strval($this->idd_tahapan->CurrentValue));
            if ($curVal != "") {
                $this->idd_tahapan->ViewValue = $this->idd_tahapan->lookupCacheOption($curVal);
            } else {
                $this->idd_tahapan->ViewValue = $this->idd_tahapan->Lookup !== null && is_array($this->idd_tahapan->Lookup->Options) ? $curVal : null;
            }
            if ($this->idd_tahapan->ViewValue !== null) { // Load from cache
                $this->idd_tahapan->EditValue = array_values($this->idd_tahapan->Lookup->Options);
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = "`idd_tahapan`" . SearchString("=", $this->idd_tahapan->CurrentValue, DATATYPE_NUMBER, "");
                }
                $sqlWrk = $this->idd_tahapan->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->idd_tahapan->EditValue = $arwrk;
            }
            $this->idd_tahapan->PlaceHolder = RemoveHtml($this->idd_tahapan->caption());

            // tahun_anggaran
            $this->tahun_anggaran->EditAttrs["class"] = "form-control";
            $this->tahun_anggaran->EditCustomAttributes = "";
            $curVal = trim(strval($this->tahun_anggaran->CurrentValue));
            if ($curVal != "") {
                $this->tahun_anggaran->ViewValue = $this->tahun_anggaran->lookupCacheOption($curVal);
            } else {
                $this->tahun_anggaran->ViewValue = $this->tahun_anggaran->Lookup !== null && is_array($this->tahun_anggaran->Lookup->Options) ? $curVal : null;
            }
            if ($this->tahun_anggaran->ViewValue !== null) { // Load from cache
                $this->tahun_anggaran->EditValue = array_values($this->tahun_anggaran->Lookup->Options);
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = "`id_tahun`" . SearchString("=", $this->tahun_anggaran->CurrentValue, DATATYPE_STRING, "");
                }
                $sqlWrk = $this->tahun_anggaran->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->tahun_anggaran->EditValue = $arwrk;
            }
            $this->tahun_anggaran->PlaceHolder = RemoveHtml($this->tahun_anggaran->caption());

            // status
            $this->status->EditAttrs["class"] = "form-control";
            $this->status->EditCustomAttributes = "";
            $this->status->EditValue = $this->status->options(true);
            $this->status->PlaceHolder = RemoveHtml($this->status->caption());

            // Edit refer script

            // kd_satker
            $this->kd_satker->LinkCustomAttributes = "";
            $this->kd_satker->HrefValue = "";

            // idd_tahapan
            $this->idd_tahapan->LinkCustomAttributes = "";
            $this->idd_tahapan->HrefValue = "";

            // tahun_anggaran
            $this->tahun_anggaran->LinkCustomAttributes = "";
            $this->tahun_anggaran->HrefValue = "";

            // status
            $this->status->LinkCustomAttributes = "";
            $this->status->HrefValue = "";
        }
        if ($this->RowType == ROWTYPE_ADD || $this->RowType == ROWTYPE_EDIT || $this->RowType == ROWTYPE_SEARCH) { // Add/Edit/Search row
            $this->setupFieldTitles();
        }

        // Call Row Rendered event
        if ($this->RowType != ROWTYPE_AGGREGATEINIT) {
            $this->rowRendered();
        }
    }

    // Validate form
    protected function validateForm()
    {
        global $Language;

        // Check if validation required
        if (!Config("SERVER_VALIDATE")) {
            return true;
        }
        if ($this->kd_satker->Required) {
            if (!$this->kd_satker->IsDetailKey && EmptyValue($this->kd_satker->FormValue)) {
                $this->kd_satker->addErrorMessage(str_replace("%s", $this->kd_satker->caption(), $this->kd_satker->RequiredErrorMessage));
            }
        }
        if ($this->idd_tahapan->Required) {
            if (!$this->idd_tahapan->IsDetailKey && EmptyValue($this->idd_tahapan->FormValue)) {
                $this->idd_tahapan->addErrorMessage(str_replace("%s", $this->idd_tahapan->caption(), $this->idd_tahapan->RequiredErrorMessage));
            }
        }
        if ($this->tahun_anggaran->Required) {
            if (!$this->tahun_anggaran->IsDetailKey && EmptyValue($this->tahun_anggaran->FormValue)) {
                $this->tahun_anggaran->addErrorMessage(str_replace("%s", $this->tahun_anggaran->caption(), $this->tahun_anggaran->RequiredErrorMessage));
            }
        }
        if ($this->status->Required) {
            if (!$this->status->IsDetailKey && EmptyValue($this->status->FormValue)) {
                $this->status->addErrorMessage(str_replace("%s", $this->status->caption(), $this->status->RequiredErrorMessage));
            }
        }

        // Return validate result
        $validateForm = !$this->hasInvalidFields();

        // Call Form_CustomValidate event
        $formCustomError = "";
        $validateForm = $validateForm && $this->formCustomValidate($formCustomError);
        if ($formCustomError != "") {
            $this->setFailureMessage($formCustomError);
        }
        return $validateForm;
    }

    // Update record based on key values
    protected function editRow()
    {
        global $Security, $Language;
        $oldKeyFilter = $this->getRecordFilter();
        $filter = $this->applyUserIDFilters($oldKeyFilter);
        $conn = $this->getConnection();
        $this->CurrentFilter = $filter;
        $sql = $this->getCurrentSql();
        $rsold = $conn->fetchAssoc($sql);
        $editRow = false;
        if (!$rsold) {
            $this->setFailureMessage($Language->phrase("NoRecord")); // Set no record message
            $editRow = false; // Update Failed
        } else {
            // Save old values
            $this->loadDbValues($rsold);
            $this->surat_pengantar->OldUploadPath = "files/evaluasi/2022/pertanggungjawaban";
            $this->surat_pengantar->UploadPath = $this->surat_pengantar->OldUploadPath;
            $this->skd_rqanunpert->OldUploadPath = "files/evaluasi/2022/pertanggungjawaban";
            $this->skd_rqanunpert->UploadPath = $this->skd_rqanunpert->OldUploadPath;
            $this->rqanun_apbkpert->OldUploadPath = "files/evaluasi/2022/pertanggungjawaban";
            $this->rqanun_apbkpert->UploadPath = $this->rqanun_apbkpert->OldUploadPath;
            $this->rperbup_apbkpert->OldUploadPath = "files/evaluasi/2022/pertanggungjawaban";
            $this->rperbup_apbkpert->UploadPath = $this->rperbup_apbkpert->OldUploadPath;
            $this->pbkdd_apbkpert->OldUploadPath = "files/evaluasi/2022/pertanggungjawaban";
            $this->pbkdd_apbkpert->UploadPath = $this->pbkdd_apbkpert->OldUploadPath;
            $this->risalah_sidang->OldUploadPath = "files/evaluasi/2022/pertanggungjawaban";
            $this->risalah_sidang->UploadPath = $this->risalah_sidang->OldUploadPath;
            $this->absen_peserta->OldUploadPath = "files/evaluasi/2022/pertanggungjawaban";
            $this->absen_peserta->UploadPath = $this->absen_peserta->OldUploadPath;
            $this->neraca->OldUploadPath = "files/evaluasi/2022/pertanggungjawaban";
            $this->neraca->UploadPath = $this->neraca->OldUploadPath;
            $this->lra->OldUploadPath = "files/evaluasi/2022/pertanggungjawaban";
            $this->lra->UploadPath = $this->lra->OldUploadPath;
            $this->calk->OldUploadPath = "files/evaluasi/2022/pertanggungjawaban";
            $this->calk->UploadPath = $this->calk->OldUploadPath;
            $this->lo->OldUploadPath = "files/evaluasi/2022/pertanggungjawaban";
            $this->lo->UploadPath = $this->lo->OldUploadPath;
            $this->lpe->OldUploadPath = "files/evaluasi/2022/pertanggungjawaban";
            $this->lpe->UploadPath = $this->lpe->OldUploadPath;
            $this->lpsal->OldUploadPath = "files/evaluasi/2022/pertanggungjawaban";
            $this->lpsal->UploadPath = $this->lpsal->OldUploadPath;
            $this->lak->OldUploadPath = "files/evaluasi/2022/pertanggungjawaban";
            $this->lak->UploadPath = $this->lak->OldUploadPath;
            $this->laporan_pemeriksaan->OldUploadPath = "files/evaluasi/2022/pertanggungjawaban";
            $this->laporan_pemeriksaan->UploadPath = $this->laporan_pemeriksaan->OldUploadPath;
            $rsnew = [];

            // kd_satker
            $this->kd_satker->setDbValueDef($rsnew, $this->kd_satker->CurrentValue, "", $this->kd_satker->ReadOnly);

            // idd_tahapan
            $this->idd_tahapan->setDbValueDef($rsnew, $this->idd_tahapan->CurrentValue, 0, $this->idd_tahapan->ReadOnly);

            // tahun_anggaran
            $this->tahun_anggaran->setDbValueDef($rsnew, $this->tahun_anggaran->CurrentValue, "", $this->tahun_anggaran->ReadOnly);

            // status
            $this->status->setDbValueDef($rsnew, $this->status->CurrentValue, 0, $this->status->ReadOnly);

            // Call Row Updating event
            $updateRow = $this->rowUpdating($rsold, $rsnew);
            if ($updateRow) {
                if (count($rsnew) > 0) {
                    try {
                        $editRow = $this->update($rsnew, "", $rsold);
                    } catch (\Exception $e) {
                        $this->setFailureMessage($e->getMessage());
                    }
                } else {
                    $editRow = true; // No field to update
                }
                if ($editRow) {
                }
            } else {
                if ($this->getSuccessMessage() != "" || $this->getFailureMessage() != "") {
                    // Use the message, do nothing
                } elseif ($this->CancelMessage != "") {
                    $this->setFailureMessage($this->CancelMessage);
                    $this->CancelMessage = "";
                } else {
                    $this->setFailureMessage($Language->phrase("UpdateCancelled"));
                }
                $editRow = false;
            }
        }

        // Call Row_Updated event
        if ($editRow) {
            $this->rowUpdated($rsold, $rsnew);
        }

        // Clean upload path if any
        if ($editRow) {
        }

        // Write JSON for API request
        if (IsApi() && $editRow) {
            $row = $this->getRecordsFromRecordset([$rsnew], true);
            WriteJson(["success" => true, $this->TableVar => $row]);
        }
        return $editRow;
    }

    // Load row hash
    protected function loadRowHash()
    {
        $filter = $this->getRecordFilter();

        // Load SQL based on filter
        $this->CurrentFilter = $filter;
        $sql = $this->getCurrentSql();
        $conn = $this->getConnection();
        $row = $conn->fetchAssoc($sql);
        $this->HashValue = $row ? $this->getRowHash($row) : ""; // Get hash value for record
    }

    // Get Row Hash
    public function getRowHash(&$rs)
    {
        if (!$rs) {
            return "";
        }
        $row = ($rs instanceof Recordset) ? $rs->fields : $rs;
        $hash = "";
        $hash .= GetFieldHash($row['kd_satker']); // kd_satker
        $hash .= GetFieldHash($row['idd_tahapan']); // idd_tahapan
        $hash .= GetFieldHash($row['tahun_anggaran']); // tahun_anggaran
        $hash .= GetFieldHash($row['status']); // status
        return md5($hash);
    }

    // Add record
    protected function addRow($rsold = null)
    {
        global $Language, $Security;

        // Check if valid User ID
        $validUser = false;
        if ($Security->currentUserID() != "" && !EmptyValue($this->idd_user->CurrentValue) && !$Security->isAdmin()) { // Non system admin
            $validUser = $Security->isValidUserID($this->idd_user->CurrentValue);
            if (!$validUser) {
                $userIdMsg = str_replace("%c", CurrentUserID(), $Language->phrase("UnAuthorizedUserID"));
                $userIdMsg = str_replace("%u", $this->idd_user->CurrentValue, $userIdMsg);
                $this->setFailureMessage($userIdMsg);
                return false;
            }
        }
        $conn = $this->getConnection();

        // Load db values from rsold
        $this->loadDbValues($rsold);
        if ($rsold) {
            $this->surat_pengantar->OldUploadPath = "files/evaluasi/2022/pertanggungjawaban";
            $this->surat_pengantar->UploadPath = $this->surat_pengantar->OldUploadPath;
            $this->skd_rqanunpert->OldUploadPath = "files/evaluasi/2022/pertanggungjawaban";
            $this->skd_rqanunpert->UploadPath = $this->skd_rqanunpert->OldUploadPath;
            $this->rqanun_apbkpert->OldUploadPath = "files/evaluasi/2022/pertanggungjawaban";
            $this->rqanun_apbkpert->UploadPath = $this->rqanun_apbkpert->OldUploadPath;
            $this->rperbup_apbkpert->OldUploadPath = "files/evaluasi/2022/pertanggungjawaban";
            $this->rperbup_apbkpert->UploadPath = $this->rperbup_apbkpert->OldUploadPath;
            $this->pbkdd_apbkpert->OldUploadPath = "files/evaluasi/2022/pertanggungjawaban";
            $this->pbkdd_apbkpert->UploadPath = $this->pbkdd_apbkpert->OldUploadPath;
            $this->risalah_sidang->OldUploadPath = "files/evaluasi/2022/pertanggungjawaban";
            $this->risalah_sidang->UploadPath = $this->risalah_sidang->OldUploadPath;
            $this->absen_peserta->OldUploadPath = "files/evaluasi/2022/pertanggungjawaban";
            $this->absen_peserta->UploadPath = $this->absen_peserta->OldUploadPath;
            $this->neraca->OldUploadPath = "files/evaluasi/2022/pertanggungjawaban";
            $this->neraca->UploadPath = $this->neraca->OldUploadPath;
            $this->lra->OldUploadPath = "files/evaluasi/2022/pertanggungjawaban";
            $this->lra->UploadPath = $this->lra->OldUploadPath;
            $this->calk->OldUploadPath = "files/evaluasi/2022/pertanggungjawaban";
            $this->calk->UploadPath = $this->calk->OldUploadPath;
            $this->lo->OldUploadPath = "files/evaluasi/2022/pertanggungjawaban";
            $this->lo->UploadPath = $this->lo->OldUploadPath;
            $this->lpe->OldUploadPath = "files/evaluasi/2022/pertanggungjawaban";
            $this->lpe->UploadPath = $this->lpe->OldUploadPath;
            $this->lpsal->OldUploadPath = "files/evaluasi/2022/pertanggungjawaban";
            $this->lpsal->UploadPath = $this->lpsal->OldUploadPath;
            $this->lak->OldUploadPath = "files/evaluasi/2022/pertanggungjawaban";
            $this->lak->UploadPath = $this->lak->OldUploadPath;
            $this->laporan_pemeriksaan->OldUploadPath = "files/evaluasi/2022/pertanggungjawaban";
            $this->laporan_pemeriksaan->UploadPath = $this->laporan_pemeriksaan->OldUploadPath;
        }
        $rsnew = [];

        // kd_satker
        $this->kd_satker->setDbValueDef($rsnew, $this->kd_satker->CurrentValue, "", false);

        // idd_tahapan
        $this->idd_tahapan->setDbValueDef($rsnew, $this->idd_tahapan->CurrentValue, 0, false);

        // tahun_anggaran
        $this->tahun_anggaran->setDbValueDef($rsnew, $this->tahun_anggaran->CurrentValue, "", false);

        // status
        $this->status->setDbValueDef($rsnew, $this->status->CurrentValue, 0, false);

        // idd_user
        if (!$Security->isAdmin() && $Security->isLoggedIn()) { // Non system admin
            $rsnew['idd_user'] = CurrentUserID();
        }

        // Call Row Inserting event
        $insertRow = $this->rowInserting($rsold, $rsnew);
        $addRow = false;
        if ($insertRow) {
            try {
                $addRow = $this->insert($rsnew);
            } catch (\Exception $e) {
                $this->setFailureMessage($e->getMessage());
            }
            if ($addRow) {
            }
        } else {
            if ($this->getSuccessMessage() != "" || $this->getFailureMessage() != "") {
                // Use the message, do nothing
            } elseif ($this->CancelMessage != "") {
                $this->setFailureMessage($this->CancelMessage);
                $this->CancelMessage = "";
            } else {
                $this->setFailureMessage($Language->phrase("InsertCancelled"));
            }
            $addRow = false;
        }
        if ($addRow) {
            // Call Row Inserted event
            $this->rowInserted($rsold, $rsnew);
        }

        // Clean upload path if any
        if ($addRow) {
        }

        // Write JSON for API request
        if (IsApi() && $addRow) {
            $row = $this->getRecordsFromRecordset([$rsnew], true);
            WriteJson(["success" => true, $this->TableVar => $row]);
        }
        return $addRow;
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
        $item->Body = "<a class=\"btn btn-default ew-search-toggle" . $searchToggleClass . "\" href=\"#\" role=\"button\" title=\"" . $Language->phrase("SearchPanel") . "\" data-caption=\"" . $Language->phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fpertanggungjawaban2022listsrch\" aria-pressed=\"" . ($searchToggleClass == " active" ? "true" : "false") . "\">" . $Language->phrase("SearchLink") . "</a>";
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
