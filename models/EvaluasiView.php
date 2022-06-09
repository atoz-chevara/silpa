<?php

namespace PHPMaker2021\silpa;

use Doctrine\DBAL\ParameterType;

/**
 * Page class
 */
class EvaluasiView extends Evaluasi
{
    use MessagesTrait;

    // Page ID
    public $PageID = "view";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'evaluasi';

    // Page object name
    public $PageObjName = "EvaluasiView";

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

        // Table object (evaluasi)
        if (!isset($GLOBALS["evaluasi"]) || get_class($GLOBALS["evaluasi"]) == PROJECT_NAMESPACE . "evaluasi") {
            $GLOBALS["evaluasi"] = &$this;
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

            // Handle modal response
            if ($this->IsModal) { // Show as modal
                $row = ["url" => GetUrl($url), "modal" => "1"];
                $pageName = GetPageName($url);
                if ($pageName != $this->getListUrl()) { // Not List page
                    $row["caption"] = $this->getModalCaption($pageName);
                    if ($pageName == "evaluasiview") {
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

        // Do not use lookup cache
        $this->setUseLookupCache(false);

        // Global Page Loading event (in userfn*.php)
        Page_Loading();

        // Page Load event
        if (method_exists($this, "pageLoad")) {
            $this->pageLoad();
        }

        // Set up lookup cache
        $this->setupLookupOptions($this->idd_wilayah);
        $this->setupLookupOptions($this->kd_satker);
        $this->setupLookupOptions($this->idd_tahapan);
        $this->setupLookupOptions($this->idd_user);

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
                $returnUrl = "evaluasilist"; // Return to list
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
                        $returnUrl = "evaluasilist"; // No matching record, return to list
                    }
                    break;
            }
        } else {
            $returnUrl = "evaluasilist"; // Not page request, return to list
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
        $item->Visible = ($this->EditUrl != "" && $Security->canEdit() && $this->showOptionLink("edit"));

        // Copy
        $item = &$option->add("copy");
        $copycaption = HtmlTitle($Language->phrase("ViewPageCopyLink"));
        if ($this->IsModal) {
            $item->Body = "<a class=\"ew-action ew-copy\" title=\"" . $copycaption . "\" data-caption=\"" . $copycaption . "\" href=\"#\" onclick=\"return ew.modalDialogShow({lnk:this,btn:'AddBtn',url:'" . HtmlEncode(GetUrl($this->CopyUrl)) . "'});\">" . $Language->phrase("ViewPageCopyLink") . "</a>";
        } else {
            $item->Body = "<a class=\"ew-action ew-copy\" title=\"" . $copycaption . "\" data-caption=\"" . $copycaption . "\" href=\"" . HtmlEncode(GetUrl($this->CopyUrl)) . "\">" . $Language->phrase("ViewPageCopyLink") . "</a>";
        }
        $item->Visible = ($this->CopyUrl != "" && $Security->canAdd() && $this->showOptionLink("add"));

        // Delete
        $item = &$option->add("delete");
        if ($this->IsModal) { // Handle as inline delete
            $item->Body = "<a onclick=\"return ew.confirmDelete(this);\" class=\"ew-action ew-delete\" title=\"" . HtmlTitle($Language->phrase("ViewPageDeleteLink")) . "\" data-caption=\"" . HtmlTitle($Language->phrase("ViewPageDeleteLink")) . "\" href=\"" . HtmlEncode(UrlAddQuery(GetUrl($this->DeleteUrl), "action=1")) . "\">" . $Language->phrase("ViewPageDeleteLink") . "</a>";
        } else {
            $item->Body = "<a class=\"ew-action ew-delete\" title=\"" . HtmlTitle($Language->phrase("ViewPageDeleteLink")) . "\" data-caption=\"" . HtmlTitle($Language->phrase("ViewPageDeleteLink")) . "\" href=\"" . HtmlEncode(GetUrl($this->DeleteUrl)) . "\">" . $Language->phrase("ViewPageDeleteLink") . "</a>";
        }
        $item->Visible = ($this->DeleteUrl != "" && $Security->canDelete() && $this->showOptionLink("delete"));

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
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("evaluasilist"), "", $this->TableVar, true);
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
