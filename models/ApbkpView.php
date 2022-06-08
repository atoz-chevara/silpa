<?php

namespace PHPMaker2021\silpa;

use Doctrine\DBAL\ParameterType;

/**
 * Page class
 */
class ApbkpView extends Apbkp
{
    use MessagesTrait;

    // Page ID
    public $PageID = "view";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'apbkp';

    // Page object name
    public $PageObjName = "ApbkpView";

    // Rendering View
    public $RenderingView = false;

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

        // Table object (apbkp)
        if (!isset($GLOBALS["apbkp"]) || get_class($GLOBALS["apbkp"]) == PROJECT_NAMESPACE . "apbkp") {
            $GLOBALS["apbkp"] = &$this;
        }

        // Page URL
        $pageUrl = $this->pageUrl();
        if (($keyValue = Get("idd_evaluasi") ?? Route("idd_evaluasi")) !== null) {
            $this->RecKey["idd_evaluasi"] = $keyValue;
        }
        $this->ExportPrintUrl = $pageUrl . "export=print";
        $this->ExportHtmlUrl = $pageUrl . "export=html";
        $this->ExportExcelUrl = $pageUrl . "export=excel";
        $this->ExportWordUrl = $pageUrl . "export=word";
        $this->ExportXmlUrl = $pageUrl . "export=xml";
        $this->ExportCsvUrl = $pageUrl . "export=csv";
        $this->ExportPdfUrl = $pageUrl . "export=pdf";

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'apbkp');
        }

        // Start timer
        $DebugTimer = Container("timer");

        // Debug message
        LoadDebugMessage();

        // Open connection
        $GLOBALS["Conn"] = $GLOBALS["Conn"] ?? $this->getConnection();

        // User table object
        $UserTable = Container("usertable");

        // Export options
        $this->ExportOptions = new ListOptions("div");
        $this->ExportOptions->TagClassName = "ew-export-option";

        // Other options
        if (!$this->OtherOptions) {
            $this->OtherOptions = new ListOptionsArray();
        }
        $this->OtherOptions["action"] = new ListOptions("div");
        $this->OtherOptions["action"]->TagClassName = "ew-action-option";
        $this->OtherOptions["detail"] = new ListOptions("div");
        $this->OtherOptions["detail"]->TagClassName = "ew-detail-option";
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
                $doc = new $class(Container("apbkp"));
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

            // Handle modal response
            if ($this->IsModal) { // Show as modal
                $row = ["url" => GetUrl($url), "modal" => "1"];
                $pageName = GetPageName($url);
                if ($pageName != $this->getListUrl()) { // Not List page
                    $row["caption"] = $this->getModalCaption($pageName);
                    if ($pageName == "apbkpview") {
                        $row["view"] = "1";
                    }
                } else { // List page should not be shown as modal => error
                    $row["error"] = $this->getFailureMessage();
                    $this->clearFailureMessage();
                }
                WriteJson($row);
            } else {
                SaveDebugMessage();
                Redirect(GetUrl($url));
            }
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
    public $ExportOptions; // Export options
    public $OtherOptions; // Other options
    public $DisplayRecords = 1;
    public $DbMasterFilter;
    public $DbDetailFilter;
    public $StartRecord;
    public $StopRecord;
    public $TotalRecords = 0;
    public $RecordRange = 10;
    public $RecKey = [];
    public $IsModal = false;

    /**
     * Page run
     *
     * @return void
     */
    public function run()
    {
        global $ExportType, $CustomExportType, $ExportFileName, $UserProfile, $Language, $Security, $CurrentForm,
            $SkipHeaderFooter;

        // Is modal
        $this->IsModal = Param("modal") == "1";
        $this->CurrentAction = Param("action"); // Set up current action
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

        // Do not use lookup cache
        $this->setUseLookupCache(false);

        // Global Page Loading event (in userfn*.php)
        Page_Loading();

        // Page Load event
        if (method_exists($this, "pageLoad")) {
            $this->pageLoad();
        }

        // Set up lookup cache

        // Check modal
        if ($this->IsModal) {
            $SkipHeaderFooter = true;
        }

        // Load current record
        $loadCurrentRecord = false;
        $returnUrl = "";
        $matchRecord = false;
        if ($this->isPageRequest()) { // Validate request
            if (($keyValue = Get("idd_evaluasi") ?? Route("idd_evaluasi")) !== null) {
                $this->idd_evaluasi->setQueryStringValue($keyValue);
                $this->RecKey["idd_evaluasi"] = $this->idd_evaluasi->QueryStringValue;
            } elseif (Post("idd_evaluasi") !== null) {
                $this->idd_evaluasi->setFormValue(Post("idd_evaluasi"));
                $this->RecKey["idd_evaluasi"] = $this->idd_evaluasi->FormValue;
            } elseif (IsApi() && ($keyValue = Key(0) ?? Route(2)) !== null) {
                $this->idd_evaluasi->setQueryStringValue($keyValue);
                $this->RecKey["idd_evaluasi"] = $this->idd_evaluasi->QueryStringValue;
            } else {
                $returnUrl = "apbkplist"; // Return to list
            }

            // Get action
            $this->CurrentAction = "show"; // Display
            switch ($this->CurrentAction) {
                case "show": // Get a record to display

                    // Load record based on key
                    if (IsApi()) {
                        $filter = $this->getRecordFilter();
                        $this->CurrentFilter = $filter;
                        $sql = $this->getCurrentSql();
                        $conn = $this->getConnection();
                        $this->Recordset = LoadRecordset($sql, $conn);
                        $res = $this->Recordset && !$this->Recordset->EOF;
                    } else {
                        $res = $this->loadRow();
                    }
                    if (!$res) { // Load record based on key
                        if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "") {
                            $this->setFailureMessage($Language->phrase("NoRecord")); // Set no record message
                        }
                        $returnUrl = "apbkplist"; // No matching record, return to list
                    }
                    break;
            }
        } else {
            $returnUrl = "apbkplist"; // Not page request, return to list
        }
        if ($returnUrl != "") {
            $this->terminate($returnUrl);
            return;
        }

        // Set up Breadcrumb
        if (!$this->isExport()) {
            $this->setupBreadcrumb();
        }

        // Render row
        $this->RowType = ROWTYPE_VIEW;
        $this->resetAttributes();
        $this->renderRow();

        // Normal return
        if (IsApi()) {
            $rows = $this->getRecordsFromRecordset($this->Recordset, true); // Get current record only
            $this->Recordset->close();
            WriteJson(["success" => true, $this->TableVar => $rows]);
            $this->terminate(true);
            return;
        }

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

    // Set up other options
    protected function setupOtherOptions()
    {
        global $Language, $Security;
        $options = &$this->OtherOptions;
        $option = $options["action"];

        // Add
        $item = &$option->add("add");
        $addcaption = HtmlTitle($Language->phrase("ViewPageAddLink"));
        if ($this->IsModal) {
            $item->Body = "<a class=\"ew-action ew-add\" title=\"" . $addcaption . "\" data-caption=\"" . $addcaption . "\" href=\"#\" onclick=\"return ew.modalDialogShow({lnk:this,url:'" . HtmlEncode(GetUrl($this->AddUrl)) . "'});\">" . $Language->phrase("ViewPageAddLink") . "</a>";
        } else {
            $item->Body = "<a class=\"ew-action ew-add\" title=\"" . $addcaption . "\" data-caption=\"" . $addcaption . "\" href=\"" . HtmlEncode(GetUrl($this->AddUrl)) . "\">" . $Language->phrase("ViewPageAddLink") . "</a>";
        }
        $item->Visible = ($this->AddUrl != "" && $Security->canAdd());

        // Edit
        $item = &$option->add("edit");
        $editcaption = HtmlTitle($Language->phrase("ViewPageEditLink"));
        if ($this->IsModal) {
            $item->Body = "<a class=\"ew-action ew-edit\" title=\"" . $editcaption . "\" data-caption=\"" . $editcaption . "\" href=\"#\" onclick=\"return ew.modalDialogShow({lnk:this,url:'" . HtmlEncode(GetUrl($this->EditUrl)) . "'});\">" . $Language->phrase("ViewPageEditLink") . "</a>";
        } else {
            $item->Body = "<a class=\"ew-action ew-edit\" title=\"" . $editcaption . "\" data-caption=\"" . $editcaption . "\" href=\"" . HtmlEncode(GetUrl($this->EditUrl)) . "\">" . $Language->phrase("ViewPageEditLink") . "</a>";
        }
        $item->Visible = ($this->EditUrl != "" && $Security->canEdit());

        // Copy
        $item = &$option->add("copy");
        $copycaption = HtmlTitle($Language->phrase("ViewPageCopyLink"));
        if ($this->IsModal) {
            $item->Body = "<a class=\"ew-action ew-copy\" title=\"" . $copycaption . "\" data-caption=\"" . $copycaption . "\" href=\"#\" onclick=\"return ew.modalDialogShow({lnk:this,btn:'AddBtn',url:'" . HtmlEncode(GetUrl($this->CopyUrl)) . "'});\">" . $Language->phrase("ViewPageCopyLink") . "</a>";
        } else {
            $item->Body = "<a class=\"ew-action ew-copy\" title=\"" . $copycaption . "\" data-caption=\"" . $copycaption . "\" href=\"" . HtmlEncode(GetUrl($this->CopyUrl)) . "\">" . $Language->phrase("ViewPageCopyLink") . "</a>";
        }
        $item->Visible = ($this->CopyUrl != "" && $Security->canAdd());

        // Delete
        $item = &$option->add("delete");
        if ($this->IsModal) { // Handle as inline delete
            $item->Body = "<a onclick=\"return ew.confirmDelete(this);\" class=\"ew-action ew-delete\" title=\"" . HtmlTitle($Language->phrase("ViewPageDeleteLink")) . "\" data-caption=\"" . HtmlTitle($Language->phrase("ViewPageDeleteLink")) . "\" href=\"" . HtmlEncode(UrlAddQuery(GetUrl($this->DeleteUrl), "action=1")) . "\">" . $Language->phrase("ViewPageDeleteLink") . "</a>";
        } else {
            $item->Body = "<a class=\"ew-action ew-delete\" title=\"" . HtmlTitle($Language->phrase("ViewPageDeleteLink")) . "\" data-caption=\"" . HtmlTitle($Language->phrase("ViewPageDeleteLink")) . "\" href=\"" . HtmlEncode(GetUrl($this->DeleteUrl)) . "\">" . $Language->phrase("ViewPageDeleteLink") . "</a>";
        }
        $item->Visible = ($this->DeleteUrl != "" && $Security->canDelete());

        // Set up action default
        $option = $options["action"];
        $option->DropDownButtonPhrase = $Language->phrase("ButtonActions");
        $option->UseDropDownButton = false;
        $option->UseButtonGroup = true;
        $item = &$option->add($option->GroupOptionName);
        $item->Body = "";
        $item->Visible = false;
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

    // Render row values based on field settings
    public function renderRow()
    {
        global $Security, $Language, $CurrentLanguage;

        // Initialize URLs
        $this->AddUrl = $this->getAddUrl();
        $this->EditUrl = $this->getEditUrl();
        $this->CopyUrl = $this->getCopyUrl();
        $this->DeleteUrl = $this->getDeleteUrl();
        $this->ListUrl = $this->getListUrl();
        $this->setupOtherOptions();

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

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("index");
        $url = CurrentUrl();
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("apbkplist"), "", $this->TableVar, true);
        $pageId = "view";
        $Breadcrumb->add("view", $pageId, $url);
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
}
