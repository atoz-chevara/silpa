<?php

namespace PHPMaker2021\silpa;

use Doctrine\DBAL\ParameterType;

/**
 * Page class
 */
class Pertanggungjawaban2022Add extends Pertanggungjawaban2022
{
    use MessagesTrait;

    // Page ID
    public $PageID = "add";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'pertanggungjawaban2022';

    // Page object name
    public $PageObjName = "Pertanggungjawaban2022Add";

    // Rendering View
    public $RenderingView = false;

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

            // Handle modal response
            if ($this->IsModal) { // Show as modal
                $row = ["url" => GetUrl($url), "modal" => "1"];
                $pageName = GetPageName($url);
                if ($pageName != $this->getListUrl()) { // Not List page
                    $row["caption"] = $this->getModalCaption($pageName);
                    if ($pageName == "pertanggungjawaban2022view") {
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
    public $FormClassName = "ew-horizontal ew-form ew-add-form";
    public $IsModal = false;
    public $IsMobileOrModal = false;
    public $DbMasterFilter = "";
    public $DbDetailFilter = "";
    public $StartRecord;
    public $Priv = 0;
    public $OldRecordset;
    public $CopyRecord;

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

        // Create form object
        $CurrentForm = new HttpForm();
        $this->CurrentAction = Param("action"); // Set up current action
        $this->idd_evaluasi->Visible = false;
        $this->tanggal->setVisibility();
        $this->kd_satker->setVisibility();
        $this->idd_tahapan->setVisibility();
        $this->tahun_anggaran->setVisibility();
        $this->surat_pengantar->setVisibility();
        $this->skd_rqanunpert->setVisibility();
        $this->rqanun_apbkpert->setVisibility();
        $this->rperbup_apbkpert->setVisibility();
        $this->pbkdd_apbkpert->setVisibility();
        $this->risalah_sidang->setVisibility();
        $this->absen_peserta->setVisibility();
        $this->neraca->setVisibility();
        $this->lra->setVisibility();
        $this->calk->setVisibility();
        $this->lo->setVisibility();
        $this->lpe->setVisibility();
        $this->lpsal->setVisibility();
        $this->lak->setVisibility();
        $this->laporan_pemeriksaan->setVisibility();
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
        $this->setupLookupOptions($this->kd_satker);
        $this->setupLookupOptions($this->idd_tahapan);
        $this->setupLookupOptions($this->tahun_anggaran);
        $this->setupLookupOptions($this->idd_user);

        // Check modal
        if ($this->IsModal) {
            $SkipHeaderFooter = true;
        }
        $this->IsMobileOrModal = IsMobile() || $this->IsModal;
        $this->FormClassName = "ew-form ew-add-form ew-horizontal";
        $postBack = false;

        // Set up current action
        if (IsApi()) {
            $this->CurrentAction = "insert"; // Add record directly
            $postBack = true;
        } elseif (Post("action") !== null) {
            $this->CurrentAction = Post("action"); // Get form action
            $this->setKey(Post($this->OldKeyName));
            $postBack = true;
        } else {
            // Load key values from QueryString
            if (($keyValue = Get("idd_evaluasi") ?? Route("idd_evaluasi")) !== null) {
                $this->idd_evaluasi->setQueryStringValue($keyValue);
            }
            $this->OldKey = $this->getKey(true); // Get from CurrentValue
            $this->CopyRecord = !EmptyValue($this->OldKey);
            if ($this->CopyRecord) {
                $this->CurrentAction = "copy"; // Copy record
            } else {
                $this->CurrentAction = "show"; // Display blank record
            }
        }

        // Load old record / default values
        $loaded = $this->loadOldRecord();

        // Load form values
        if ($postBack) {
            $this->loadFormValues(); // Load form values
        }

        // Validate form if post back
        if ($postBack) {
            if (!$this->validateForm()) {
                $this->EventCancelled = true; // Event cancelled
                $this->restoreFormValues(); // Restore form values
                if (IsApi()) {
                    $this->terminate();
                    return;
                } else {
                    $this->CurrentAction = "show"; // Form error, reset action
                }
            }
        }

        // Perform current action
        switch ($this->CurrentAction) {
            case "copy": // Copy an existing record
                if (!$loaded) { // Record not loaded
                    if ($this->getFailureMessage() == "") {
                        $this->setFailureMessage($Language->phrase("NoRecord")); // No record found
                    }
                    $this->terminate("pertanggungjawaban2022list"); // No matching record, return to list
                    return;
                }
                break;
            case "insert": // Add new record
                $this->SendEmail = true; // Send email on add success
                if ($this->addRow($this->OldRecordset)) { // Add successful
                    if ($this->getSuccessMessage() == "" && Post("addopt") != "1") { // Skip success message for addopt (done in JavaScript)
                        $this->setSuccessMessage($Language->phrase("AddSuccess")); // Set up success message
                    }
                    $returnUrl = $this->getReturnUrl();
                    if (GetPageName($returnUrl) == "pertanggungjawaban2022list") {
                        $returnUrl = $this->addMasterUrl($returnUrl); // List page, return to List page with correct master key if necessary
                    } elseif (GetPageName($returnUrl) == "pertanggungjawaban2022view") {
                        $returnUrl = $this->getViewUrl(); // View page, return to View page with keyurl directly
                    }
                    if (IsApi()) { // Return to caller
                        $this->terminate(true);
                        return;
                    } else {
                        $this->terminate($returnUrl);
                        return;
                    }
                } elseif (IsApi()) { // API request, return
                    $this->terminate();
                    return;
                } else {
                    $this->EventCancelled = true; // Event cancelled
                    $this->restoreFormValues(); // Add failed, restore form values
                }
        }

        // Set up Breadcrumb
        $this->setupBreadcrumb();

        // Render row based on row type
        $this->RowType = ROWTYPE_ADD; // Render add type

        // Render row
        $this->resetAttributes();
        $this->renderRow();

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

    // Get upload files
    protected function getUploadFiles()
    {
        global $CurrentForm, $Language;
        $this->surat_pengantar->Upload->Index = $CurrentForm->Index;
        $this->surat_pengantar->Upload->uploadFile();
        $this->surat_pengantar->CurrentValue = $this->surat_pengantar->Upload->FileName;
        $this->skd_rqanunpert->Upload->Index = $CurrentForm->Index;
        $this->skd_rqanunpert->Upload->uploadFile();
        $this->skd_rqanunpert->CurrentValue = $this->skd_rqanunpert->Upload->FileName;
        $this->rqanun_apbkpert->Upload->Index = $CurrentForm->Index;
        $this->rqanun_apbkpert->Upload->uploadFile();
        $this->rqanun_apbkpert->CurrentValue = $this->rqanun_apbkpert->Upload->FileName;
        $this->rperbup_apbkpert->Upload->Index = $CurrentForm->Index;
        $this->rperbup_apbkpert->Upload->uploadFile();
        $this->rperbup_apbkpert->CurrentValue = $this->rperbup_apbkpert->Upload->FileName;
        $this->pbkdd_apbkpert->Upload->Index = $CurrentForm->Index;
        $this->pbkdd_apbkpert->Upload->uploadFile();
        $this->pbkdd_apbkpert->CurrentValue = $this->pbkdd_apbkpert->Upload->FileName;
        $this->risalah_sidang->Upload->Index = $CurrentForm->Index;
        $this->risalah_sidang->Upload->uploadFile();
        $this->risalah_sidang->CurrentValue = $this->risalah_sidang->Upload->FileName;
        $this->absen_peserta->Upload->Index = $CurrentForm->Index;
        $this->absen_peserta->Upload->uploadFile();
        $this->absen_peserta->CurrentValue = $this->absen_peserta->Upload->FileName;
        $this->neraca->Upload->Index = $CurrentForm->Index;
        $this->neraca->Upload->uploadFile();
        $this->neraca->CurrentValue = $this->neraca->Upload->FileName;
        $this->lra->Upload->Index = $CurrentForm->Index;
        $this->lra->Upload->uploadFile();
        $this->lra->CurrentValue = $this->lra->Upload->FileName;
        $this->calk->Upload->Index = $CurrentForm->Index;
        $this->calk->Upload->uploadFile();
        $this->calk->CurrentValue = $this->calk->Upload->FileName;
        $this->lo->Upload->Index = $CurrentForm->Index;
        $this->lo->Upload->uploadFile();
        $this->lo->CurrentValue = $this->lo->Upload->FileName;
        $this->lpe->Upload->Index = $CurrentForm->Index;
        $this->lpe->Upload->uploadFile();
        $this->lpe->CurrentValue = $this->lpe->Upload->FileName;
        $this->lpsal->Upload->Index = $CurrentForm->Index;
        $this->lpsal->Upload->uploadFile();
        $this->lpsal->CurrentValue = $this->lpsal->Upload->FileName;
        $this->lak->Upload->Index = $CurrentForm->Index;
        $this->lak->Upload->uploadFile();
        $this->lak->CurrentValue = $this->lak->Upload->FileName;
        $this->laporan_pemeriksaan->Upload->Index = $CurrentForm->Index;
        $this->laporan_pemeriksaan->Upload->uploadFile();
        $this->laporan_pemeriksaan->CurrentValue = $this->laporan_pemeriksaan->Upload->FileName;
    }

    // Load default values
    protected function loadDefaultValues()
    {
        $this->idd_evaluasi->CurrentValue = null;
        $this->idd_evaluasi->OldValue = $this->idd_evaluasi->CurrentValue;
        $this->tanggal->CurrentValue = null;
        $this->tanggal->OldValue = $this->tanggal->CurrentValue;
        $this->kd_satker->CurrentValue = null;
        $this->kd_satker->OldValue = $this->kd_satker->CurrentValue;
        $this->idd_tahapan->CurrentValue = null;
        $this->idd_tahapan->OldValue = $this->idd_tahapan->CurrentValue;
        $this->tahun_anggaran->CurrentValue = null;
        $this->tahun_anggaran->OldValue = $this->tahun_anggaran->CurrentValue;
        $this->surat_pengantar->Upload->DbValue = null;
        $this->surat_pengantar->OldValue = $this->surat_pengantar->Upload->DbValue;
        $this->surat_pengantar->CurrentValue = null; // Clear file related field
        $this->skd_rqanunpert->Upload->DbValue = null;
        $this->skd_rqanunpert->OldValue = $this->skd_rqanunpert->Upload->DbValue;
        $this->skd_rqanunpert->CurrentValue = null; // Clear file related field
        $this->rqanun_apbkpert->Upload->DbValue = null;
        $this->rqanun_apbkpert->OldValue = $this->rqanun_apbkpert->Upload->DbValue;
        $this->rqanun_apbkpert->CurrentValue = null; // Clear file related field
        $this->rperbup_apbkpert->Upload->DbValue = null;
        $this->rperbup_apbkpert->OldValue = $this->rperbup_apbkpert->Upload->DbValue;
        $this->rperbup_apbkpert->CurrentValue = null; // Clear file related field
        $this->pbkdd_apbkpert->Upload->DbValue = null;
        $this->pbkdd_apbkpert->OldValue = $this->pbkdd_apbkpert->Upload->DbValue;
        $this->pbkdd_apbkpert->CurrentValue = null; // Clear file related field
        $this->risalah_sidang->Upload->DbValue = null;
        $this->risalah_sidang->OldValue = $this->risalah_sidang->Upload->DbValue;
        $this->risalah_sidang->CurrentValue = null; // Clear file related field
        $this->absen_peserta->Upload->DbValue = null;
        $this->absen_peserta->OldValue = $this->absen_peserta->Upload->DbValue;
        $this->absen_peserta->CurrentValue = null; // Clear file related field
        $this->neraca->Upload->DbValue = null;
        $this->neraca->OldValue = $this->neraca->Upload->DbValue;
        $this->neraca->CurrentValue = null; // Clear file related field
        $this->lra->Upload->DbValue = null;
        $this->lra->OldValue = $this->lra->Upload->DbValue;
        $this->lra->CurrentValue = null; // Clear file related field
        $this->calk->Upload->DbValue = null;
        $this->calk->OldValue = $this->calk->Upload->DbValue;
        $this->calk->CurrentValue = null; // Clear file related field
        $this->lo->Upload->DbValue = null;
        $this->lo->OldValue = $this->lo->Upload->DbValue;
        $this->lo->CurrentValue = null; // Clear file related field
        $this->lpe->Upload->DbValue = null;
        $this->lpe->OldValue = $this->lpe->Upload->DbValue;
        $this->lpe->CurrentValue = null; // Clear file related field
        $this->lpsal->Upload->DbValue = null;
        $this->lpsal->OldValue = $this->lpsal->Upload->DbValue;
        $this->lpsal->CurrentValue = null; // Clear file related field
        $this->lak->Upload->DbValue = null;
        $this->lak->OldValue = $this->lak->Upload->DbValue;
        $this->lak->CurrentValue = null; // Clear file related field
        $this->laporan_pemeriksaan->Upload->DbValue = null;
        $this->laporan_pemeriksaan->OldValue = $this->laporan_pemeriksaan->Upload->DbValue;
        $this->laporan_pemeriksaan->CurrentValue = null; // Clear file related field
        $this->status->CurrentValue = null;
        $this->status->OldValue = $this->status->CurrentValue;
        $this->idd_user->CurrentValue = CurrentUserID();
    }

    // Load form values
    protected function loadFormValues()
    {
        // Load from form
        global $CurrentForm;

        // Check field name 'tanggal' first before field var 'x_tanggal'
        $val = $CurrentForm->hasValue("tanggal") ? $CurrentForm->getValue("tanggal") : $CurrentForm->getValue("x_tanggal");
        if (!$this->tanggal->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->tanggal->Visible = false; // Disable update for API request
            } else {
                $this->tanggal->setFormValue($val);
            }
            $this->tanggal->CurrentValue = UnFormatDateTime($this->tanggal->CurrentValue, 0);
        }

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

        // Check field name 'idd_user' first before field var 'x_idd_user'
        $val = $CurrentForm->hasValue("idd_user") ? $CurrentForm->getValue("idd_user") : $CurrentForm->getValue("x_idd_user");
        if (!$this->idd_user->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->idd_user->Visible = false; // Disable update for API request
            } else {
                $this->idd_user->setFormValue($val);
            }
        }

        // Check field name 'idd_evaluasi' first before field var 'x_idd_evaluasi'
        $val = $CurrentForm->hasValue("idd_evaluasi") ? $CurrentForm->getValue("idd_evaluasi") : $CurrentForm->getValue("x_idd_evaluasi");
        $this->getUploadFiles(); // Get upload files
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->tanggal->CurrentValue = $this->tanggal->FormValue;
        $this->tanggal->CurrentValue = UnFormatDateTime($this->tanggal->CurrentValue, 0);
        $this->kd_satker->CurrentValue = $this->kd_satker->FormValue;
        $this->idd_tahapan->CurrentValue = $this->idd_tahapan->FormValue;
        $this->tahun_anggaran->CurrentValue = $this->tahun_anggaran->FormValue;
        $this->status->CurrentValue = $this->status->FormValue;
        $this->idd_user->CurrentValue = $this->idd_user->FormValue;
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

        // Check if valid User ID
        if ($res) {
            $res = $this->showOptionLink("add");
            if (!$res) {
                $userIdMsg = DeniedMessage();
                $this->setFailureMessage($userIdMsg);
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
        $this->tanggal->setDbValue($row['tanggal']);
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
        $this->idd_user->setDbValue($row['idd_user']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $this->loadDefaultValues();
        $row = [];
        $row['idd_evaluasi'] = $this->idd_evaluasi->CurrentValue;
        $row['tanggal'] = $this->tanggal->CurrentValue;
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

        // Call Row_Rendering event
        $this->rowRendering();

        // Common render codes for all row types

        // idd_evaluasi

        // tanggal

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
            if (!EmptyValue($this->surat_pengantar->Upload->DbValue)) {
                $this->surat_pengantar->ViewValue = $this->surat_pengantar->Upload->DbValue;
            } else {
                $this->surat_pengantar->ViewValue = "";
            }
            $this->surat_pengantar->ViewCustomAttributes = "";

            // skd_rqanunpert
            if (!EmptyValue($this->skd_rqanunpert->Upload->DbValue)) {
                $this->skd_rqanunpert->ViewValue = $this->skd_rqanunpert->Upload->DbValue;
            } else {
                $this->skd_rqanunpert->ViewValue = "";
            }
            $this->skd_rqanunpert->ViewCustomAttributes = "";

            // rqanun_apbkpert
            if (!EmptyValue($this->rqanun_apbkpert->Upload->DbValue)) {
                $this->rqanun_apbkpert->ViewValue = $this->rqanun_apbkpert->Upload->DbValue;
            } else {
                $this->rqanun_apbkpert->ViewValue = "";
            }
            $this->rqanun_apbkpert->ViewCustomAttributes = "";

            // rperbup_apbkpert
            if (!EmptyValue($this->rperbup_apbkpert->Upload->DbValue)) {
                $this->rperbup_apbkpert->ViewValue = $this->rperbup_apbkpert->Upload->DbValue;
            } else {
                $this->rperbup_apbkpert->ViewValue = "";
            }
            $this->rperbup_apbkpert->ViewCustomAttributes = "";

            // pbkdd_apbkpert
            if (!EmptyValue($this->pbkdd_apbkpert->Upload->DbValue)) {
                $this->pbkdd_apbkpert->ViewValue = $this->pbkdd_apbkpert->Upload->DbValue;
            } else {
                $this->pbkdd_apbkpert->ViewValue = "";
            }
            $this->pbkdd_apbkpert->ViewCustomAttributes = "";

            // risalah_sidang
            if (!EmptyValue($this->risalah_sidang->Upload->DbValue)) {
                $this->risalah_sidang->ViewValue = $this->risalah_sidang->Upload->DbValue;
            } else {
                $this->risalah_sidang->ViewValue = "";
            }
            $this->risalah_sidang->ViewCustomAttributes = "";

            // absen_peserta
            if (!EmptyValue($this->absen_peserta->Upload->DbValue)) {
                $this->absen_peserta->ViewValue = $this->absen_peserta->Upload->DbValue;
            } else {
                $this->absen_peserta->ViewValue = "";
            }
            $this->absen_peserta->ViewCustomAttributes = "";

            // neraca
            if (!EmptyValue($this->neraca->Upload->DbValue)) {
                $this->neraca->ViewValue = $this->neraca->Upload->DbValue;
            } else {
                $this->neraca->ViewValue = "";
            }
            $this->neraca->ViewCustomAttributes = "";

            // lra
            if (!EmptyValue($this->lra->Upload->DbValue)) {
                $this->lra->ViewValue = $this->lra->Upload->DbValue;
            } else {
                $this->lra->ViewValue = "";
            }
            $this->lra->ViewCustomAttributes = "";

            // calk
            if (!EmptyValue($this->calk->Upload->DbValue)) {
                $this->calk->ViewValue = $this->calk->Upload->DbValue;
            } else {
                $this->calk->ViewValue = "";
            }
            $this->calk->ViewCustomAttributes = "";

            // lo
            if (!EmptyValue($this->lo->Upload->DbValue)) {
                $this->lo->ViewValue = $this->lo->Upload->DbValue;
            } else {
                $this->lo->ViewValue = "";
            }
            $this->lo->ViewCustomAttributes = "";

            // lpe
            if (!EmptyValue($this->lpe->Upload->DbValue)) {
                $this->lpe->ViewValue = $this->lpe->Upload->DbValue;
            } else {
                $this->lpe->ViewValue = "";
            }
            $this->lpe->ViewCustomAttributes = "";

            // lpsal
            if (!EmptyValue($this->lpsal->Upload->DbValue)) {
                $this->lpsal->ViewValue = $this->lpsal->Upload->DbValue;
            } else {
                $this->lpsal->ViewValue = "";
            }
            $this->lpsal->ViewCustomAttributes = "";

            // lak
            if (!EmptyValue($this->lak->Upload->DbValue)) {
                $this->lak->ViewValue = $this->lak->Upload->DbValue;
            } else {
                $this->lak->ViewValue = "";
            }
            $this->lak->ViewCustomAttributes = "";

            // laporan_pemeriksaan
            if (!EmptyValue($this->laporan_pemeriksaan->Upload->DbValue)) {
                $this->laporan_pemeriksaan->ViewValue = $this->laporan_pemeriksaan->Upload->DbValue;
            } else {
                $this->laporan_pemeriksaan->ViewValue = "";
            }
            $this->laporan_pemeriksaan->ViewCustomAttributes = "";

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

            // surat_pengantar
            $this->surat_pengantar->LinkCustomAttributes = "";
            $this->surat_pengantar->HrefValue = "";
            $this->surat_pengantar->ExportHrefValue = $this->surat_pengantar->UploadPath . $this->surat_pengantar->Upload->DbValue;
            $this->surat_pengantar->TooltipValue = "";

            // skd_rqanunpert
            $this->skd_rqanunpert->LinkCustomAttributes = "";
            $this->skd_rqanunpert->HrefValue = "";
            $this->skd_rqanunpert->ExportHrefValue = $this->skd_rqanunpert->UploadPath . $this->skd_rqanunpert->Upload->DbValue;
            $this->skd_rqanunpert->TooltipValue = "";

            // rqanun_apbkpert
            $this->rqanun_apbkpert->LinkCustomAttributes = "";
            $this->rqanun_apbkpert->HrefValue = "";
            $this->rqanun_apbkpert->ExportHrefValue = $this->rqanun_apbkpert->UploadPath . $this->rqanun_apbkpert->Upload->DbValue;
            $this->rqanun_apbkpert->TooltipValue = "";

            // rperbup_apbkpert
            $this->rperbup_apbkpert->LinkCustomAttributes = "";
            $this->rperbup_apbkpert->HrefValue = "";
            $this->rperbup_apbkpert->ExportHrefValue = $this->rperbup_apbkpert->UploadPath . $this->rperbup_apbkpert->Upload->DbValue;
            $this->rperbup_apbkpert->TooltipValue = "";

            // pbkdd_apbkpert
            $this->pbkdd_apbkpert->LinkCustomAttributes = "";
            $this->pbkdd_apbkpert->HrefValue = "";
            $this->pbkdd_apbkpert->ExportHrefValue = $this->pbkdd_apbkpert->UploadPath . $this->pbkdd_apbkpert->Upload->DbValue;
            $this->pbkdd_apbkpert->TooltipValue = "";

            // risalah_sidang
            $this->risalah_sidang->LinkCustomAttributes = "";
            $this->risalah_sidang->HrefValue = "";
            $this->risalah_sidang->ExportHrefValue = $this->risalah_sidang->UploadPath . $this->risalah_sidang->Upload->DbValue;
            $this->risalah_sidang->TooltipValue = "";

            // absen_peserta
            $this->absen_peserta->LinkCustomAttributes = "";
            $this->absen_peserta->HrefValue = "";
            $this->absen_peserta->ExportHrefValue = $this->absen_peserta->UploadPath . $this->absen_peserta->Upload->DbValue;
            $this->absen_peserta->TooltipValue = "";

            // neraca
            $this->neraca->LinkCustomAttributes = "";
            $this->neraca->HrefValue = "";
            $this->neraca->ExportHrefValue = $this->neraca->UploadPath . $this->neraca->Upload->DbValue;
            $this->neraca->TooltipValue = "";

            // lra
            $this->lra->LinkCustomAttributes = "";
            $this->lra->HrefValue = "";
            $this->lra->ExportHrefValue = $this->lra->UploadPath . $this->lra->Upload->DbValue;
            $this->lra->TooltipValue = "";

            // calk
            $this->calk->LinkCustomAttributes = "";
            $this->calk->HrefValue = "";
            $this->calk->ExportHrefValue = $this->calk->UploadPath . $this->calk->Upload->DbValue;
            $this->calk->TooltipValue = "";

            // lo
            $this->lo->LinkCustomAttributes = "";
            $this->lo->HrefValue = "";
            $this->lo->ExportHrefValue = $this->lo->UploadPath . $this->lo->Upload->DbValue;
            $this->lo->TooltipValue = "";

            // lpe
            $this->lpe->LinkCustomAttributes = "";
            $this->lpe->HrefValue = "";
            $this->lpe->ExportHrefValue = $this->lpe->UploadPath . $this->lpe->Upload->DbValue;
            $this->lpe->TooltipValue = "";

            // lpsal
            $this->lpsal->LinkCustomAttributes = "";
            $this->lpsal->HrefValue = "";
            $this->lpsal->ExportHrefValue = $this->lpsal->UploadPath . $this->lpsal->Upload->DbValue;
            $this->lpsal->TooltipValue = "";

            // lak
            $this->lak->LinkCustomAttributes = "";
            $this->lak->HrefValue = "";
            $this->lak->ExportHrefValue = $this->lak->UploadPath . $this->lak->Upload->DbValue;
            $this->lak->TooltipValue = "";

            // laporan_pemeriksaan
            $this->laporan_pemeriksaan->LinkCustomAttributes = "";
            $this->laporan_pemeriksaan->HrefValue = "";
            $this->laporan_pemeriksaan->ExportHrefValue = $this->laporan_pemeriksaan->UploadPath . $this->laporan_pemeriksaan->Upload->DbValue;
            $this->laporan_pemeriksaan->TooltipValue = "";

            // status
            $this->status->LinkCustomAttributes = "";
            $this->status->HrefValue = "";
            $this->status->TooltipValue = "";

            // idd_user
            $this->idd_user->LinkCustomAttributes = "";
            $this->idd_user->HrefValue = "";
            $this->idd_user->TooltipValue = "";
        } elseif ($this->RowType == ROWTYPE_ADD) {
            // tanggal
            $this->tanggal->EditAttrs["class"] = "form-control";
            $this->tanggal->EditCustomAttributes = "";
            $this->tanggal->EditValue = HtmlEncode(FormatDateTime($this->tanggal->CurrentValue, 8));
            $this->tanggal->PlaceHolder = RemoveHtml($this->tanggal->caption());

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

            // surat_pengantar
            $this->surat_pengantar->EditAttrs["class"] = "form-control";
            $this->surat_pengantar->EditCustomAttributes = "";
            if (!EmptyValue($this->surat_pengantar->Upload->DbValue)) {
                $this->surat_pengantar->EditValue = $this->surat_pengantar->Upload->DbValue;
            } else {
                $this->surat_pengantar->EditValue = "";
            }
            if (!EmptyValue($this->surat_pengantar->CurrentValue)) {
                $this->surat_pengantar->Upload->FileName = $this->surat_pengantar->CurrentValue;
            }
            if ($this->isShow() || $this->isCopy()) {
                RenderUploadField($this->surat_pengantar);
            }

            // skd_rqanunpert
            $this->skd_rqanunpert->EditAttrs["class"] = "form-control";
            $this->skd_rqanunpert->EditCustomAttributes = "";
            if (!EmptyValue($this->skd_rqanunpert->Upload->DbValue)) {
                $this->skd_rqanunpert->EditValue = $this->skd_rqanunpert->Upload->DbValue;
            } else {
                $this->skd_rqanunpert->EditValue = "";
            }
            if (!EmptyValue($this->skd_rqanunpert->CurrentValue)) {
                $this->skd_rqanunpert->Upload->FileName = $this->skd_rqanunpert->CurrentValue;
            }
            if ($this->isShow() || $this->isCopy()) {
                RenderUploadField($this->skd_rqanunpert);
            }

            // rqanun_apbkpert
            $this->rqanun_apbkpert->EditAttrs["class"] = "form-control";
            $this->rqanun_apbkpert->EditCustomAttributes = "";
            if (!EmptyValue($this->rqanun_apbkpert->Upload->DbValue)) {
                $this->rqanun_apbkpert->EditValue = $this->rqanun_apbkpert->Upload->DbValue;
            } else {
                $this->rqanun_apbkpert->EditValue = "";
            }
            if (!EmptyValue($this->rqanun_apbkpert->CurrentValue)) {
                $this->rqanun_apbkpert->Upload->FileName = $this->rqanun_apbkpert->CurrentValue;
            }
            if ($this->isShow() || $this->isCopy()) {
                RenderUploadField($this->rqanun_apbkpert);
            }

            // rperbup_apbkpert
            $this->rperbup_apbkpert->EditAttrs["class"] = "form-control";
            $this->rperbup_apbkpert->EditCustomAttributes = "";
            if (!EmptyValue($this->rperbup_apbkpert->Upload->DbValue)) {
                $this->rperbup_apbkpert->EditValue = $this->rperbup_apbkpert->Upload->DbValue;
            } else {
                $this->rperbup_apbkpert->EditValue = "";
            }
            if (!EmptyValue($this->rperbup_apbkpert->CurrentValue)) {
                $this->rperbup_apbkpert->Upload->FileName = $this->rperbup_apbkpert->CurrentValue;
            }
            if ($this->isShow() || $this->isCopy()) {
                RenderUploadField($this->rperbup_apbkpert);
            }

            // pbkdd_apbkpert
            $this->pbkdd_apbkpert->EditAttrs["class"] = "form-control";
            $this->pbkdd_apbkpert->EditCustomAttributes = "";
            if (!EmptyValue($this->pbkdd_apbkpert->Upload->DbValue)) {
                $this->pbkdd_apbkpert->EditValue = $this->pbkdd_apbkpert->Upload->DbValue;
            } else {
                $this->pbkdd_apbkpert->EditValue = "";
            }
            if (!EmptyValue($this->pbkdd_apbkpert->CurrentValue)) {
                $this->pbkdd_apbkpert->Upload->FileName = $this->pbkdd_apbkpert->CurrentValue;
            }
            if ($this->isShow() || $this->isCopy()) {
                RenderUploadField($this->pbkdd_apbkpert);
            }

            // risalah_sidang
            $this->risalah_sidang->EditAttrs["class"] = "form-control";
            $this->risalah_sidang->EditCustomAttributes = "";
            if (!EmptyValue($this->risalah_sidang->Upload->DbValue)) {
                $this->risalah_sidang->EditValue = $this->risalah_sidang->Upload->DbValue;
            } else {
                $this->risalah_sidang->EditValue = "";
            }
            if (!EmptyValue($this->risalah_sidang->CurrentValue)) {
                $this->risalah_sidang->Upload->FileName = $this->risalah_sidang->CurrentValue;
            }
            if ($this->isShow() || $this->isCopy()) {
                RenderUploadField($this->risalah_sidang);
            }

            // absen_peserta
            $this->absen_peserta->EditAttrs["class"] = "form-control";
            $this->absen_peserta->EditCustomAttributes = "";
            if (!EmptyValue($this->absen_peserta->Upload->DbValue)) {
                $this->absen_peserta->EditValue = $this->absen_peserta->Upload->DbValue;
            } else {
                $this->absen_peserta->EditValue = "";
            }
            if (!EmptyValue($this->absen_peserta->CurrentValue)) {
                $this->absen_peserta->Upload->FileName = $this->absen_peserta->CurrentValue;
            }
            if ($this->isShow() || $this->isCopy()) {
                RenderUploadField($this->absen_peserta);
            }

            // neraca
            $this->neraca->EditAttrs["class"] = "form-control";
            $this->neraca->EditCustomAttributes = "";
            if (!EmptyValue($this->neraca->Upload->DbValue)) {
                $this->neraca->EditValue = $this->neraca->Upload->DbValue;
            } else {
                $this->neraca->EditValue = "";
            }
            if (!EmptyValue($this->neraca->CurrentValue)) {
                $this->neraca->Upload->FileName = $this->neraca->CurrentValue;
            }
            if ($this->isShow() || $this->isCopy()) {
                RenderUploadField($this->neraca);
            }

            // lra
            $this->lra->EditAttrs["class"] = "form-control";
            $this->lra->EditCustomAttributes = "";
            if (!EmptyValue($this->lra->Upload->DbValue)) {
                $this->lra->EditValue = $this->lra->Upload->DbValue;
            } else {
                $this->lra->EditValue = "";
            }
            if (!EmptyValue($this->lra->CurrentValue)) {
                $this->lra->Upload->FileName = $this->lra->CurrentValue;
            }
            if ($this->isShow() || $this->isCopy()) {
                RenderUploadField($this->lra);
            }

            // calk
            $this->calk->EditAttrs["class"] = "form-control";
            $this->calk->EditCustomAttributes = "";
            if (!EmptyValue($this->calk->Upload->DbValue)) {
                $this->calk->EditValue = $this->calk->Upload->DbValue;
            } else {
                $this->calk->EditValue = "";
            }
            if (!EmptyValue($this->calk->CurrentValue)) {
                $this->calk->Upload->FileName = $this->calk->CurrentValue;
            }
            if ($this->isShow() || $this->isCopy()) {
                RenderUploadField($this->calk);
            }

            // lo
            $this->lo->EditAttrs["class"] = "form-control";
            $this->lo->EditCustomAttributes = "";
            if (!EmptyValue($this->lo->Upload->DbValue)) {
                $this->lo->EditValue = $this->lo->Upload->DbValue;
            } else {
                $this->lo->EditValue = "";
            }
            if (!EmptyValue($this->lo->CurrentValue)) {
                $this->lo->Upload->FileName = $this->lo->CurrentValue;
            }
            if ($this->isShow() || $this->isCopy()) {
                RenderUploadField($this->lo);
            }

            // lpe
            $this->lpe->EditAttrs["class"] = "form-control";
            $this->lpe->EditCustomAttributes = "";
            if (!EmptyValue($this->lpe->Upload->DbValue)) {
                $this->lpe->EditValue = $this->lpe->Upload->DbValue;
            } else {
                $this->lpe->EditValue = "";
            }
            if (!EmptyValue($this->lpe->CurrentValue)) {
                $this->lpe->Upload->FileName = $this->lpe->CurrentValue;
            }
            if ($this->isShow() || $this->isCopy()) {
                RenderUploadField($this->lpe);
            }

            // lpsal
            $this->lpsal->EditAttrs["class"] = "form-control";
            $this->lpsal->EditCustomAttributes = "";
            if (!EmptyValue($this->lpsal->Upload->DbValue)) {
                $this->lpsal->EditValue = $this->lpsal->Upload->DbValue;
            } else {
                $this->lpsal->EditValue = "";
            }
            if (!EmptyValue($this->lpsal->CurrentValue)) {
                $this->lpsal->Upload->FileName = $this->lpsal->CurrentValue;
            }
            if ($this->isShow() || $this->isCopy()) {
                RenderUploadField($this->lpsal);
            }

            // lak
            $this->lak->EditAttrs["class"] = "form-control";
            $this->lak->EditCustomAttributes = "";
            if (!EmptyValue($this->lak->Upload->DbValue)) {
                $this->lak->EditValue = $this->lak->Upload->DbValue;
            } else {
                $this->lak->EditValue = "";
            }
            if (!EmptyValue($this->lak->CurrentValue)) {
                $this->lak->Upload->FileName = $this->lak->CurrentValue;
            }
            if ($this->isShow() || $this->isCopy()) {
                RenderUploadField($this->lak);
            }

            // laporan_pemeriksaan
            $this->laporan_pemeriksaan->EditAttrs["class"] = "form-control";
            $this->laporan_pemeriksaan->EditCustomAttributes = "";
            if (!EmptyValue($this->laporan_pemeriksaan->Upload->DbValue)) {
                $this->laporan_pemeriksaan->EditValue = $this->laporan_pemeriksaan->Upload->DbValue;
            } else {
                $this->laporan_pemeriksaan->EditValue = "";
            }
            if (!EmptyValue($this->laporan_pemeriksaan->CurrentValue)) {
                $this->laporan_pemeriksaan->Upload->FileName = $this->laporan_pemeriksaan->CurrentValue;
            }
            if ($this->isShow() || $this->isCopy()) {
                RenderUploadField($this->laporan_pemeriksaan);
            }

            // status
            $this->status->EditAttrs["class"] = "form-control";
            $this->status->EditCustomAttributes = "";
            $this->status->EditValue = $this->status->options(true);
            $this->status->PlaceHolder = RemoveHtml($this->status->caption());

            // idd_user
            $this->idd_user->EditAttrs["class"] = "form-control";
            $this->idd_user->EditCustomAttributes = "";
            if (!$Security->isAdmin() && $Security->isLoggedIn() && !$this->userIDAllow("add")) { // Non system admin
                $this->idd_user->CurrentValue = CurrentUserID();
                $curVal = trim(strval($this->idd_user->CurrentValue));
                if ($curVal != "") {
                    $this->idd_user->EditValue = $this->idd_user->lookupCacheOption($curVal);
                    if ($this->idd_user->EditValue === null) { // Lookup from database
                        $filterWrk = "`idd_user`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                        $sqlWrk = $this->idd_user->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                        $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                        $ari = count($rswrk);
                        if ($ari > 0) { // Lookup values found
                            $arwrk = $this->idd_user->Lookup->renderViewRow($rswrk[0]);
                            $this->idd_user->EditValue = $this->idd_user->displayValue($arwrk);
                        } else {
                            $this->idd_user->EditValue = $this->idd_user->CurrentValue;
                        }
                    }
                } else {
                    $this->idd_user->EditValue = null;
                }
                $this->idd_user->ViewCustomAttributes = "";
            } else {
                $curVal = trim(strval($this->idd_user->CurrentValue));
                if ($curVal != "") {
                    $this->idd_user->ViewValue = $this->idd_user->lookupCacheOption($curVal);
                } else {
                    $this->idd_user->ViewValue = $this->idd_user->Lookup !== null && is_array($this->idd_user->Lookup->Options) ? $curVal : null;
                }
                if ($this->idd_user->ViewValue !== null) { // Load from cache
                    $this->idd_user->EditValue = array_values($this->idd_user->Lookup->Options);
                } else { // Lookup from database
                    if ($curVal == "") {
                        $filterWrk = "0=1";
                    } else {
                        $filterWrk = "`idd_user`" . SearchString("=", $this->idd_user->CurrentValue, DATATYPE_NUMBER, "");
                    }
                    $sqlWrk = $this->idd_user->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    $arwrk = $rswrk;
                    $this->idd_user->EditValue = $arwrk;
                }
                $this->idd_user->PlaceHolder = RemoveHtml($this->idd_user->caption());
            }

            // Add refer script

            // tanggal
            $this->tanggal->LinkCustomAttributes = "";
            $this->tanggal->HrefValue = "";

            // kd_satker
            $this->kd_satker->LinkCustomAttributes = "";
            $this->kd_satker->HrefValue = "";

            // idd_tahapan
            $this->idd_tahapan->LinkCustomAttributes = "";
            $this->idd_tahapan->HrefValue = "";

            // tahun_anggaran
            $this->tahun_anggaran->LinkCustomAttributes = "";
            $this->tahun_anggaran->HrefValue = "";

            // surat_pengantar
            $this->surat_pengantar->LinkCustomAttributes = "";
            $this->surat_pengantar->HrefValue = "";
            $this->surat_pengantar->ExportHrefValue = $this->surat_pengantar->UploadPath . $this->surat_pengantar->Upload->DbValue;

            // skd_rqanunpert
            $this->skd_rqanunpert->LinkCustomAttributes = "";
            $this->skd_rqanunpert->HrefValue = "";
            $this->skd_rqanunpert->ExportHrefValue = $this->skd_rqanunpert->UploadPath . $this->skd_rqanunpert->Upload->DbValue;

            // rqanun_apbkpert
            $this->rqanun_apbkpert->LinkCustomAttributes = "";
            $this->rqanun_apbkpert->HrefValue = "";
            $this->rqanun_apbkpert->ExportHrefValue = $this->rqanun_apbkpert->UploadPath . $this->rqanun_apbkpert->Upload->DbValue;

            // rperbup_apbkpert
            $this->rperbup_apbkpert->LinkCustomAttributes = "";
            $this->rperbup_apbkpert->HrefValue = "";
            $this->rperbup_apbkpert->ExportHrefValue = $this->rperbup_apbkpert->UploadPath . $this->rperbup_apbkpert->Upload->DbValue;

            // pbkdd_apbkpert
            $this->pbkdd_apbkpert->LinkCustomAttributes = "";
            $this->pbkdd_apbkpert->HrefValue = "";
            $this->pbkdd_apbkpert->ExportHrefValue = $this->pbkdd_apbkpert->UploadPath . $this->pbkdd_apbkpert->Upload->DbValue;

            // risalah_sidang
            $this->risalah_sidang->LinkCustomAttributes = "";
            $this->risalah_sidang->HrefValue = "";
            $this->risalah_sidang->ExportHrefValue = $this->risalah_sidang->UploadPath . $this->risalah_sidang->Upload->DbValue;

            // absen_peserta
            $this->absen_peserta->LinkCustomAttributes = "";
            $this->absen_peserta->HrefValue = "";
            $this->absen_peserta->ExportHrefValue = $this->absen_peserta->UploadPath . $this->absen_peserta->Upload->DbValue;

            // neraca
            $this->neraca->LinkCustomAttributes = "";
            $this->neraca->HrefValue = "";
            $this->neraca->ExportHrefValue = $this->neraca->UploadPath . $this->neraca->Upload->DbValue;

            // lra
            $this->lra->LinkCustomAttributes = "";
            $this->lra->HrefValue = "";
            $this->lra->ExportHrefValue = $this->lra->UploadPath . $this->lra->Upload->DbValue;

            // calk
            $this->calk->LinkCustomAttributes = "";
            $this->calk->HrefValue = "";
            $this->calk->ExportHrefValue = $this->calk->UploadPath . $this->calk->Upload->DbValue;

            // lo
            $this->lo->LinkCustomAttributes = "";
            $this->lo->HrefValue = "";
            $this->lo->ExportHrefValue = $this->lo->UploadPath . $this->lo->Upload->DbValue;

            // lpe
            $this->lpe->LinkCustomAttributes = "";
            $this->lpe->HrefValue = "";
            $this->lpe->ExportHrefValue = $this->lpe->UploadPath . $this->lpe->Upload->DbValue;

            // lpsal
            $this->lpsal->LinkCustomAttributes = "";
            $this->lpsal->HrefValue = "";
            $this->lpsal->ExportHrefValue = $this->lpsal->UploadPath . $this->lpsal->Upload->DbValue;

            // lak
            $this->lak->LinkCustomAttributes = "";
            $this->lak->HrefValue = "";
            $this->lak->ExportHrefValue = $this->lak->UploadPath . $this->lak->Upload->DbValue;

            // laporan_pemeriksaan
            $this->laporan_pemeriksaan->LinkCustomAttributes = "";
            $this->laporan_pemeriksaan->HrefValue = "";
            $this->laporan_pemeriksaan->ExportHrefValue = $this->laporan_pemeriksaan->UploadPath . $this->laporan_pemeriksaan->Upload->DbValue;

            // status
            $this->status->LinkCustomAttributes = "";
            $this->status->HrefValue = "";

            // idd_user
            $this->idd_user->LinkCustomAttributes = "";
            $this->idd_user->HrefValue = "";
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
        if ($this->tanggal->Required) {
            if (!$this->tanggal->IsDetailKey && EmptyValue($this->tanggal->FormValue)) {
                $this->tanggal->addErrorMessage(str_replace("%s", $this->tanggal->caption(), $this->tanggal->RequiredErrorMessage));
            }
        }
        if (!CheckDate($this->tanggal->FormValue)) {
            $this->tanggal->addErrorMessage($this->tanggal->getErrorMessage(false));
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
        if ($this->surat_pengantar->Required) {
            if ($this->surat_pengantar->Upload->FileName == "" && !$this->surat_pengantar->Upload->KeepFile) {
                $this->surat_pengantar->addErrorMessage(str_replace("%s", $this->surat_pengantar->caption(), $this->surat_pengantar->RequiredErrorMessage));
            }
        }
        if ($this->skd_rqanunpert->Required) {
            if ($this->skd_rqanunpert->Upload->FileName == "" && !$this->skd_rqanunpert->Upload->KeepFile) {
                $this->skd_rqanunpert->addErrorMessage(str_replace("%s", $this->skd_rqanunpert->caption(), $this->skd_rqanunpert->RequiredErrorMessage));
            }
        }
        if ($this->rqanun_apbkpert->Required) {
            if ($this->rqanun_apbkpert->Upload->FileName == "" && !$this->rqanun_apbkpert->Upload->KeepFile) {
                $this->rqanun_apbkpert->addErrorMessage(str_replace("%s", $this->rqanun_apbkpert->caption(), $this->rqanun_apbkpert->RequiredErrorMessage));
            }
        }
        if ($this->rperbup_apbkpert->Required) {
            if ($this->rperbup_apbkpert->Upload->FileName == "" && !$this->rperbup_apbkpert->Upload->KeepFile) {
                $this->rperbup_apbkpert->addErrorMessage(str_replace("%s", $this->rperbup_apbkpert->caption(), $this->rperbup_apbkpert->RequiredErrorMessage));
            }
        }
        if ($this->pbkdd_apbkpert->Required) {
            if ($this->pbkdd_apbkpert->Upload->FileName == "" && !$this->pbkdd_apbkpert->Upload->KeepFile) {
                $this->pbkdd_apbkpert->addErrorMessage(str_replace("%s", $this->pbkdd_apbkpert->caption(), $this->pbkdd_apbkpert->RequiredErrorMessage));
            }
        }
        if ($this->risalah_sidang->Required) {
            if ($this->risalah_sidang->Upload->FileName == "" && !$this->risalah_sidang->Upload->KeepFile) {
                $this->risalah_sidang->addErrorMessage(str_replace("%s", $this->risalah_sidang->caption(), $this->risalah_sidang->RequiredErrorMessage));
            }
        }
        if ($this->absen_peserta->Required) {
            if ($this->absen_peserta->Upload->FileName == "" && !$this->absen_peserta->Upload->KeepFile) {
                $this->absen_peserta->addErrorMessage(str_replace("%s", $this->absen_peserta->caption(), $this->absen_peserta->RequiredErrorMessage));
            }
        }
        if ($this->neraca->Required) {
            if ($this->neraca->Upload->FileName == "" && !$this->neraca->Upload->KeepFile) {
                $this->neraca->addErrorMessage(str_replace("%s", $this->neraca->caption(), $this->neraca->RequiredErrorMessage));
            }
        }
        if ($this->lra->Required) {
            if ($this->lra->Upload->FileName == "" && !$this->lra->Upload->KeepFile) {
                $this->lra->addErrorMessage(str_replace("%s", $this->lra->caption(), $this->lra->RequiredErrorMessage));
            }
        }
        if ($this->calk->Required) {
            if ($this->calk->Upload->FileName == "" && !$this->calk->Upload->KeepFile) {
                $this->calk->addErrorMessage(str_replace("%s", $this->calk->caption(), $this->calk->RequiredErrorMessage));
            }
        }
        if ($this->lo->Required) {
            if ($this->lo->Upload->FileName == "" && !$this->lo->Upload->KeepFile) {
                $this->lo->addErrorMessage(str_replace("%s", $this->lo->caption(), $this->lo->RequiredErrorMessage));
            }
        }
        if ($this->lpe->Required) {
            if ($this->lpe->Upload->FileName == "" && !$this->lpe->Upload->KeepFile) {
                $this->lpe->addErrorMessage(str_replace("%s", $this->lpe->caption(), $this->lpe->RequiredErrorMessage));
            }
        }
        if ($this->lpsal->Required) {
            if ($this->lpsal->Upload->FileName == "" && !$this->lpsal->Upload->KeepFile) {
                $this->lpsal->addErrorMessage(str_replace("%s", $this->lpsal->caption(), $this->lpsal->RequiredErrorMessage));
            }
        }
        if ($this->lak->Required) {
            if ($this->lak->Upload->FileName == "" && !$this->lak->Upload->KeepFile) {
                $this->lak->addErrorMessage(str_replace("%s", $this->lak->caption(), $this->lak->RequiredErrorMessage));
            }
        }
        if ($this->laporan_pemeriksaan->Required) {
            if ($this->laporan_pemeriksaan->Upload->FileName == "" && !$this->laporan_pemeriksaan->Upload->KeepFile) {
                $this->laporan_pemeriksaan->addErrorMessage(str_replace("%s", $this->laporan_pemeriksaan->caption(), $this->laporan_pemeriksaan->RequiredErrorMessage));
            }
        }
        if ($this->status->Required) {
            if (!$this->status->IsDetailKey && EmptyValue($this->status->FormValue)) {
                $this->status->addErrorMessage(str_replace("%s", $this->status->caption(), $this->status->RequiredErrorMessage));
            }
        }
        if ($this->idd_user->Required) {
            if (!$this->idd_user->IsDetailKey && EmptyValue($this->idd_user->FormValue)) {
                $this->idd_user->addErrorMessage(str_replace("%s", $this->idd_user->caption(), $this->idd_user->RequiredErrorMessage));
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
        }
        $rsnew = [];

        // tanggal
        $this->tanggal->setDbValueDef($rsnew, UnFormatDateTime($this->tanggal->CurrentValue, 0), CurrentDate(), false);

        // kd_satker
        $this->kd_satker->setDbValueDef($rsnew, $this->kd_satker->CurrentValue, "", false);

        // idd_tahapan
        $this->idd_tahapan->setDbValueDef($rsnew, $this->idd_tahapan->CurrentValue, 0, false);

        // tahun_anggaran
        $this->tahun_anggaran->setDbValueDef($rsnew, $this->tahun_anggaran->CurrentValue, "", false);

        // surat_pengantar
        if ($this->surat_pengantar->Visible && !$this->surat_pengantar->Upload->KeepFile) {
            $this->surat_pengantar->Upload->DbValue = ""; // No need to delete old file
            if ($this->surat_pengantar->Upload->FileName == "") {
                $rsnew['surat_pengantar'] = null;
            } else {
                $rsnew['surat_pengantar'] = $this->surat_pengantar->Upload->FileName;
            }
        }

        // skd_rqanunpert
        if ($this->skd_rqanunpert->Visible && !$this->skd_rqanunpert->Upload->KeepFile) {
            $this->skd_rqanunpert->Upload->DbValue = ""; // No need to delete old file
            if ($this->skd_rqanunpert->Upload->FileName == "") {
                $rsnew['skd_rqanunpert'] = null;
            } else {
                $rsnew['skd_rqanunpert'] = $this->skd_rqanunpert->Upload->FileName;
            }
        }

        // rqanun_apbkpert
        if ($this->rqanun_apbkpert->Visible && !$this->rqanun_apbkpert->Upload->KeepFile) {
            $this->rqanun_apbkpert->Upload->DbValue = ""; // No need to delete old file
            if ($this->rqanun_apbkpert->Upload->FileName == "") {
                $rsnew['rqanun_apbkpert'] = null;
            } else {
                $rsnew['rqanun_apbkpert'] = $this->rqanun_apbkpert->Upload->FileName;
            }
        }

        // rperbup_apbkpert
        if ($this->rperbup_apbkpert->Visible && !$this->rperbup_apbkpert->Upload->KeepFile) {
            $this->rperbup_apbkpert->Upload->DbValue = ""; // No need to delete old file
            if ($this->rperbup_apbkpert->Upload->FileName == "") {
                $rsnew['rperbup_apbkpert'] = null;
            } else {
                $rsnew['rperbup_apbkpert'] = $this->rperbup_apbkpert->Upload->FileName;
            }
        }

        // pbkdd_apbkpert
        if ($this->pbkdd_apbkpert->Visible && !$this->pbkdd_apbkpert->Upload->KeepFile) {
            $this->pbkdd_apbkpert->Upload->DbValue = ""; // No need to delete old file
            if ($this->pbkdd_apbkpert->Upload->FileName == "") {
                $rsnew['pbkdd_apbkpert'] = null;
            } else {
                $rsnew['pbkdd_apbkpert'] = $this->pbkdd_apbkpert->Upload->FileName;
            }
        }

        // risalah_sidang
        if ($this->risalah_sidang->Visible && !$this->risalah_sidang->Upload->KeepFile) {
            $this->risalah_sidang->Upload->DbValue = ""; // No need to delete old file
            if ($this->risalah_sidang->Upload->FileName == "") {
                $rsnew['risalah_sidang'] = null;
            } else {
                $rsnew['risalah_sidang'] = $this->risalah_sidang->Upload->FileName;
            }
        }

        // absen_peserta
        if ($this->absen_peserta->Visible && !$this->absen_peserta->Upload->KeepFile) {
            $this->absen_peserta->Upload->DbValue = ""; // No need to delete old file
            if ($this->absen_peserta->Upload->FileName == "") {
                $rsnew['absen_peserta'] = null;
            } else {
                $rsnew['absen_peserta'] = $this->absen_peserta->Upload->FileName;
            }
        }

        // neraca
        if ($this->neraca->Visible && !$this->neraca->Upload->KeepFile) {
            $this->neraca->Upload->DbValue = ""; // No need to delete old file
            if ($this->neraca->Upload->FileName == "") {
                $rsnew['neraca'] = null;
            } else {
                $rsnew['neraca'] = $this->neraca->Upload->FileName;
            }
        }

        // lra
        if ($this->lra->Visible && !$this->lra->Upload->KeepFile) {
            $this->lra->Upload->DbValue = ""; // No need to delete old file
            if ($this->lra->Upload->FileName == "") {
                $rsnew['lra'] = null;
            } else {
                $rsnew['lra'] = $this->lra->Upload->FileName;
            }
        }

        // calk
        if ($this->calk->Visible && !$this->calk->Upload->KeepFile) {
            $this->calk->Upload->DbValue = ""; // No need to delete old file
            if ($this->calk->Upload->FileName == "") {
                $rsnew['calk'] = null;
            } else {
                $rsnew['calk'] = $this->calk->Upload->FileName;
            }
        }

        // lo
        if ($this->lo->Visible && !$this->lo->Upload->KeepFile) {
            $this->lo->Upload->DbValue = ""; // No need to delete old file
            if ($this->lo->Upload->FileName == "") {
                $rsnew['lo'] = null;
            } else {
                $rsnew['lo'] = $this->lo->Upload->FileName;
            }
        }

        // lpe
        if ($this->lpe->Visible && !$this->lpe->Upload->KeepFile) {
            $this->lpe->Upload->DbValue = ""; // No need to delete old file
            if ($this->lpe->Upload->FileName == "") {
                $rsnew['lpe'] = null;
            } else {
                $rsnew['lpe'] = $this->lpe->Upload->FileName;
            }
        }

        // lpsal
        if ($this->lpsal->Visible && !$this->lpsal->Upload->KeepFile) {
            $this->lpsal->Upload->DbValue = ""; // No need to delete old file
            if ($this->lpsal->Upload->FileName == "") {
                $rsnew['lpsal'] = null;
            } else {
                $rsnew['lpsal'] = $this->lpsal->Upload->FileName;
            }
        }

        // lak
        if ($this->lak->Visible && !$this->lak->Upload->KeepFile) {
            $this->lak->Upload->DbValue = ""; // No need to delete old file
            if ($this->lak->Upload->FileName == "") {
                $rsnew['lak'] = null;
            } else {
                $rsnew['lak'] = $this->lak->Upload->FileName;
            }
        }

        // laporan_pemeriksaan
        if ($this->laporan_pemeriksaan->Visible && !$this->laporan_pemeriksaan->Upload->KeepFile) {
            $this->laporan_pemeriksaan->Upload->DbValue = ""; // No need to delete old file
            if ($this->laporan_pemeriksaan->Upload->FileName == "") {
                $rsnew['laporan_pemeriksaan'] = null;
            } else {
                $rsnew['laporan_pemeriksaan'] = $this->laporan_pemeriksaan->Upload->FileName;
            }
        }

        // status
        $this->status->setDbValueDef($rsnew, $this->status->CurrentValue, 0, false);

        // idd_user
        $this->idd_user->setDbValueDef($rsnew, $this->idd_user->CurrentValue, 0, false);
        if ($this->surat_pengantar->Visible && !$this->surat_pengantar->Upload->KeepFile) {
            $oldFiles = EmptyValue($this->surat_pengantar->Upload->DbValue) ? [] : [$this->surat_pengantar->htmlDecode($this->surat_pengantar->Upload->DbValue)];
            if (!EmptyValue($this->surat_pengantar->Upload->FileName)) {
                $newFiles = [$this->surat_pengantar->Upload->FileName];
                $NewFileCount = count($newFiles);
                for ($i = 0; $i < $NewFileCount; $i++) {
                    if ($newFiles[$i] != "") {
                        $file = $newFiles[$i];
                        $tempPath = UploadTempPath($this->surat_pengantar, $this->surat_pengantar->Upload->Index);
                        if (file_exists($tempPath . $file)) {
                            if (Config("DELETE_UPLOADED_FILES")) {
                                $oldFileFound = false;
                                $oldFileCount = count($oldFiles);
                                for ($j = 0; $j < $oldFileCount; $j++) {
                                    $oldFile = $oldFiles[$j];
                                    if ($oldFile == $file) { // Old file found, no need to delete anymore
                                        array_splice($oldFiles, $j, 1);
                                        $oldFileFound = true;
                                        break;
                                    }
                                }
                                if ($oldFileFound) { // No need to check if file exists further
                                    continue;
                                }
                            }
                            $file1 = UniqueFilename($this->surat_pengantar->physicalUploadPath(), $file); // Get new file name
                            if ($file1 != $file) { // Rename temp file
                                while (file_exists($tempPath . $file1) || file_exists($this->surat_pengantar->physicalUploadPath() . $file1)) { // Make sure no file name clash
                                    $file1 = UniqueFilename([$this->surat_pengantar->physicalUploadPath(), $tempPath], $file1, true); // Use indexed name
                                }
                                rename($tempPath . $file, $tempPath . $file1);
                                $newFiles[$i] = $file1;
                            }
                        }
                    }
                }
                $this->surat_pengantar->Upload->DbValue = empty($oldFiles) ? "" : implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $oldFiles);
                $this->surat_pengantar->Upload->FileName = implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $newFiles);
                $this->surat_pengantar->setDbValueDef($rsnew, $this->surat_pengantar->Upload->FileName, null, false);
            }
        }
        if ($this->skd_rqanunpert->Visible && !$this->skd_rqanunpert->Upload->KeepFile) {
            $oldFiles = EmptyValue($this->skd_rqanunpert->Upload->DbValue) ? [] : [$this->skd_rqanunpert->htmlDecode($this->skd_rqanunpert->Upload->DbValue)];
            if (!EmptyValue($this->skd_rqanunpert->Upload->FileName)) {
                $newFiles = [$this->skd_rqanunpert->Upload->FileName];
                $NewFileCount = count($newFiles);
                for ($i = 0; $i < $NewFileCount; $i++) {
                    if ($newFiles[$i] != "") {
                        $file = $newFiles[$i];
                        $tempPath = UploadTempPath($this->skd_rqanunpert, $this->skd_rqanunpert->Upload->Index);
                        if (file_exists($tempPath . $file)) {
                            if (Config("DELETE_UPLOADED_FILES")) {
                                $oldFileFound = false;
                                $oldFileCount = count($oldFiles);
                                for ($j = 0; $j < $oldFileCount; $j++) {
                                    $oldFile = $oldFiles[$j];
                                    if ($oldFile == $file) { // Old file found, no need to delete anymore
                                        array_splice($oldFiles, $j, 1);
                                        $oldFileFound = true;
                                        break;
                                    }
                                }
                                if ($oldFileFound) { // No need to check if file exists further
                                    continue;
                                }
                            }
                            $file1 = UniqueFilename($this->skd_rqanunpert->physicalUploadPath(), $file); // Get new file name
                            if ($file1 != $file) { // Rename temp file
                                while (file_exists($tempPath . $file1) || file_exists($this->skd_rqanunpert->physicalUploadPath() . $file1)) { // Make sure no file name clash
                                    $file1 = UniqueFilename([$this->skd_rqanunpert->physicalUploadPath(), $tempPath], $file1, true); // Use indexed name
                                }
                                rename($tempPath . $file, $tempPath . $file1);
                                $newFiles[$i] = $file1;
                            }
                        }
                    }
                }
                $this->skd_rqanunpert->Upload->DbValue = empty($oldFiles) ? "" : implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $oldFiles);
                $this->skd_rqanunpert->Upload->FileName = implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $newFiles);
                $this->skd_rqanunpert->setDbValueDef($rsnew, $this->skd_rqanunpert->Upload->FileName, null, false);
            }
        }
        if ($this->rqanun_apbkpert->Visible && !$this->rqanun_apbkpert->Upload->KeepFile) {
            $oldFiles = EmptyValue($this->rqanun_apbkpert->Upload->DbValue) ? [] : [$this->rqanun_apbkpert->htmlDecode($this->rqanun_apbkpert->Upload->DbValue)];
            if (!EmptyValue($this->rqanun_apbkpert->Upload->FileName)) {
                $newFiles = [$this->rqanun_apbkpert->Upload->FileName];
                $NewFileCount = count($newFiles);
                for ($i = 0; $i < $NewFileCount; $i++) {
                    if ($newFiles[$i] != "") {
                        $file = $newFiles[$i];
                        $tempPath = UploadTempPath($this->rqanun_apbkpert, $this->rqanun_apbkpert->Upload->Index);
                        if (file_exists($tempPath . $file)) {
                            if (Config("DELETE_UPLOADED_FILES")) {
                                $oldFileFound = false;
                                $oldFileCount = count($oldFiles);
                                for ($j = 0; $j < $oldFileCount; $j++) {
                                    $oldFile = $oldFiles[$j];
                                    if ($oldFile == $file) { // Old file found, no need to delete anymore
                                        array_splice($oldFiles, $j, 1);
                                        $oldFileFound = true;
                                        break;
                                    }
                                }
                                if ($oldFileFound) { // No need to check if file exists further
                                    continue;
                                }
                            }
                            $file1 = UniqueFilename($this->rqanun_apbkpert->physicalUploadPath(), $file); // Get new file name
                            if ($file1 != $file) { // Rename temp file
                                while (file_exists($tempPath . $file1) || file_exists($this->rqanun_apbkpert->physicalUploadPath() . $file1)) { // Make sure no file name clash
                                    $file1 = UniqueFilename([$this->rqanun_apbkpert->physicalUploadPath(), $tempPath], $file1, true); // Use indexed name
                                }
                                rename($tempPath . $file, $tempPath . $file1);
                                $newFiles[$i] = $file1;
                            }
                        }
                    }
                }
                $this->rqanun_apbkpert->Upload->DbValue = empty($oldFiles) ? "" : implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $oldFiles);
                $this->rqanun_apbkpert->Upload->FileName = implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $newFiles);
                $this->rqanun_apbkpert->setDbValueDef($rsnew, $this->rqanun_apbkpert->Upload->FileName, null, false);
            }
        }
        if ($this->rperbup_apbkpert->Visible && !$this->rperbup_apbkpert->Upload->KeepFile) {
            $oldFiles = EmptyValue($this->rperbup_apbkpert->Upload->DbValue) ? [] : [$this->rperbup_apbkpert->htmlDecode($this->rperbup_apbkpert->Upload->DbValue)];
            if (!EmptyValue($this->rperbup_apbkpert->Upload->FileName)) {
                $newFiles = [$this->rperbup_apbkpert->Upload->FileName];
                $NewFileCount = count($newFiles);
                for ($i = 0; $i < $NewFileCount; $i++) {
                    if ($newFiles[$i] != "") {
                        $file = $newFiles[$i];
                        $tempPath = UploadTempPath($this->rperbup_apbkpert, $this->rperbup_apbkpert->Upload->Index);
                        if (file_exists($tempPath . $file)) {
                            if (Config("DELETE_UPLOADED_FILES")) {
                                $oldFileFound = false;
                                $oldFileCount = count($oldFiles);
                                for ($j = 0; $j < $oldFileCount; $j++) {
                                    $oldFile = $oldFiles[$j];
                                    if ($oldFile == $file) { // Old file found, no need to delete anymore
                                        array_splice($oldFiles, $j, 1);
                                        $oldFileFound = true;
                                        break;
                                    }
                                }
                                if ($oldFileFound) { // No need to check if file exists further
                                    continue;
                                }
                            }
                            $file1 = UniqueFilename($this->rperbup_apbkpert->physicalUploadPath(), $file); // Get new file name
                            if ($file1 != $file) { // Rename temp file
                                while (file_exists($tempPath . $file1) || file_exists($this->rperbup_apbkpert->physicalUploadPath() . $file1)) { // Make sure no file name clash
                                    $file1 = UniqueFilename([$this->rperbup_apbkpert->physicalUploadPath(), $tempPath], $file1, true); // Use indexed name
                                }
                                rename($tempPath . $file, $tempPath . $file1);
                                $newFiles[$i] = $file1;
                            }
                        }
                    }
                }
                $this->rperbup_apbkpert->Upload->DbValue = empty($oldFiles) ? "" : implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $oldFiles);
                $this->rperbup_apbkpert->Upload->FileName = implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $newFiles);
                $this->rperbup_apbkpert->setDbValueDef($rsnew, $this->rperbup_apbkpert->Upload->FileName, null, false);
            }
        }
        if ($this->pbkdd_apbkpert->Visible && !$this->pbkdd_apbkpert->Upload->KeepFile) {
            $oldFiles = EmptyValue($this->pbkdd_apbkpert->Upload->DbValue) ? [] : [$this->pbkdd_apbkpert->htmlDecode($this->pbkdd_apbkpert->Upload->DbValue)];
            if (!EmptyValue($this->pbkdd_apbkpert->Upload->FileName)) {
                $newFiles = [$this->pbkdd_apbkpert->Upload->FileName];
                $NewFileCount = count($newFiles);
                for ($i = 0; $i < $NewFileCount; $i++) {
                    if ($newFiles[$i] != "") {
                        $file = $newFiles[$i];
                        $tempPath = UploadTempPath($this->pbkdd_apbkpert, $this->pbkdd_apbkpert->Upload->Index);
                        if (file_exists($tempPath . $file)) {
                            if (Config("DELETE_UPLOADED_FILES")) {
                                $oldFileFound = false;
                                $oldFileCount = count($oldFiles);
                                for ($j = 0; $j < $oldFileCount; $j++) {
                                    $oldFile = $oldFiles[$j];
                                    if ($oldFile == $file) { // Old file found, no need to delete anymore
                                        array_splice($oldFiles, $j, 1);
                                        $oldFileFound = true;
                                        break;
                                    }
                                }
                                if ($oldFileFound) { // No need to check if file exists further
                                    continue;
                                }
                            }
                            $file1 = UniqueFilename($this->pbkdd_apbkpert->physicalUploadPath(), $file); // Get new file name
                            if ($file1 != $file) { // Rename temp file
                                while (file_exists($tempPath . $file1) || file_exists($this->pbkdd_apbkpert->physicalUploadPath() . $file1)) { // Make sure no file name clash
                                    $file1 = UniqueFilename([$this->pbkdd_apbkpert->physicalUploadPath(), $tempPath], $file1, true); // Use indexed name
                                }
                                rename($tempPath . $file, $tempPath . $file1);
                                $newFiles[$i] = $file1;
                            }
                        }
                    }
                }
                $this->pbkdd_apbkpert->Upload->DbValue = empty($oldFiles) ? "" : implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $oldFiles);
                $this->pbkdd_apbkpert->Upload->FileName = implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $newFiles);
                $this->pbkdd_apbkpert->setDbValueDef($rsnew, $this->pbkdd_apbkpert->Upload->FileName, null, false);
            }
        }
        if ($this->risalah_sidang->Visible && !$this->risalah_sidang->Upload->KeepFile) {
            $oldFiles = EmptyValue($this->risalah_sidang->Upload->DbValue) ? [] : [$this->risalah_sidang->htmlDecode($this->risalah_sidang->Upload->DbValue)];
            if (!EmptyValue($this->risalah_sidang->Upload->FileName)) {
                $newFiles = [$this->risalah_sidang->Upload->FileName];
                $NewFileCount = count($newFiles);
                for ($i = 0; $i < $NewFileCount; $i++) {
                    if ($newFiles[$i] != "") {
                        $file = $newFiles[$i];
                        $tempPath = UploadTempPath($this->risalah_sidang, $this->risalah_sidang->Upload->Index);
                        if (file_exists($tempPath . $file)) {
                            if (Config("DELETE_UPLOADED_FILES")) {
                                $oldFileFound = false;
                                $oldFileCount = count($oldFiles);
                                for ($j = 0; $j < $oldFileCount; $j++) {
                                    $oldFile = $oldFiles[$j];
                                    if ($oldFile == $file) { // Old file found, no need to delete anymore
                                        array_splice($oldFiles, $j, 1);
                                        $oldFileFound = true;
                                        break;
                                    }
                                }
                                if ($oldFileFound) { // No need to check if file exists further
                                    continue;
                                }
                            }
                            $file1 = UniqueFilename($this->risalah_sidang->physicalUploadPath(), $file); // Get new file name
                            if ($file1 != $file) { // Rename temp file
                                while (file_exists($tempPath . $file1) || file_exists($this->risalah_sidang->physicalUploadPath() . $file1)) { // Make sure no file name clash
                                    $file1 = UniqueFilename([$this->risalah_sidang->physicalUploadPath(), $tempPath], $file1, true); // Use indexed name
                                }
                                rename($tempPath . $file, $tempPath . $file1);
                                $newFiles[$i] = $file1;
                            }
                        }
                    }
                }
                $this->risalah_sidang->Upload->DbValue = empty($oldFiles) ? "" : implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $oldFiles);
                $this->risalah_sidang->Upload->FileName = implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $newFiles);
                $this->risalah_sidang->setDbValueDef($rsnew, $this->risalah_sidang->Upload->FileName, null, false);
            }
        }
        if ($this->absen_peserta->Visible && !$this->absen_peserta->Upload->KeepFile) {
            $oldFiles = EmptyValue($this->absen_peserta->Upload->DbValue) ? [] : [$this->absen_peserta->htmlDecode($this->absen_peserta->Upload->DbValue)];
            if (!EmptyValue($this->absen_peserta->Upload->FileName)) {
                $newFiles = [$this->absen_peserta->Upload->FileName];
                $NewFileCount = count($newFiles);
                for ($i = 0; $i < $NewFileCount; $i++) {
                    if ($newFiles[$i] != "") {
                        $file = $newFiles[$i];
                        $tempPath = UploadTempPath($this->absen_peserta, $this->absen_peserta->Upload->Index);
                        if (file_exists($tempPath . $file)) {
                            if (Config("DELETE_UPLOADED_FILES")) {
                                $oldFileFound = false;
                                $oldFileCount = count($oldFiles);
                                for ($j = 0; $j < $oldFileCount; $j++) {
                                    $oldFile = $oldFiles[$j];
                                    if ($oldFile == $file) { // Old file found, no need to delete anymore
                                        array_splice($oldFiles, $j, 1);
                                        $oldFileFound = true;
                                        break;
                                    }
                                }
                                if ($oldFileFound) { // No need to check if file exists further
                                    continue;
                                }
                            }
                            $file1 = UniqueFilename($this->absen_peserta->physicalUploadPath(), $file); // Get new file name
                            if ($file1 != $file) { // Rename temp file
                                while (file_exists($tempPath . $file1) || file_exists($this->absen_peserta->physicalUploadPath() . $file1)) { // Make sure no file name clash
                                    $file1 = UniqueFilename([$this->absen_peserta->physicalUploadPath(), $tempPath], $file1, true); // Use indexed name
                                }
                                rename($tempPath . $file, $tempPath . $file1);
                                $newFiles[$i] = $file1;
                            }
                        }
                    }
                }
                $this->absen_peserta->Upload->DbValue = empty($oldFiles) ? "" : implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $oldFiles);
                $this->absen_peserta->Upload->FileName = implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $newFiles);
                $this->absen_peserta->setDbValueDef($rsnew, $this->absen_peserta->Upload->FileName, null, false);
            }
        }
        if ($this->neraca->Visible && !$this->neraca->Upload->KeepFile) {
            $oldFiles = EmptyValue($this->neraca->Upload->DbValue) ? [] : [$this->neraca->htmlDecode($this->neraca->Upload->DbValue)];
            if (!EmptyValue($this->neraca->Upload->FileName)) {
                $newFiles = [$this->neraca->Upload->FileName];
                $NewFileCount = count($newFiles);
                for ($i = 0; $i < $NewFileCount; $i++) {
                    if ($newFiles[$i] != "") {
                        $file = $newFiles[$i];
                        $tempPath = UploadTempPath($this->neraca, $this->neraca->Upload->Index);
                        if (file_exists($tempPath . $file)) {
                            if (Config("DELETE_UPLOADED_FILES")) {
                                $oldFileFound = false;
                                $oldFileCount = count($oldFiles);
                                for ($j = 0; $j < $oldFileCount; $j++) {
                                    $oldFile = $oldFiles[$j];
                                    if ($oldFile == $file) { // Old file found, no need to delete anymore
                                        array_splice($oldFiles, $j, 1);
                                        $oldFileFound = true;
                                        break;
                                    }
                                }
                                if ($oldFileFound) { // No need to check if file exists further
                                    continue;
                                }
                            }
                            $file1 = UniqueFilename($this->neraca->physicalUploadPath(), $file); // Get new file name
                            if ($file1 != $file) { // Rename temp file
                                while (file_exists($tempPath . $file1) || file_exists($this->neraca->physicalUploadPath() . $file1)) { // Make sure no file name clash
                                    $file1 = UniqueFilename([$this->neraca->physicalUploadPath(), $tempPath], $file1, true); // Use indexed name
                                }
                                rename($tempPath . $file, $tempPath . $file1);
                                $newFiles[$i] = $file1;
                            }
                        }
                    }
                }
                $this->neraca->Upload->DbValue = empty($oldFiles) ? "" : implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $oldFiles);
                $this->neraca->Upload->FileName = implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $newFiles);
                $this->neraca->setDbValueDef($rsnew, $this->neraca->Upload->FileName, null, false);
            }
        }
        if ($this->lra->Visible && !$this->lra->Upload->KeepFile) {
            $oldFiles = EmptyValue($this->lra->Upload->DbValue) ? [] : [$this->lra->htmlDecode($this->lra->Upload->DbValue)];
            if (!EmptyValue($this->lra->Upload->FileName)) {
                $newFiles = [$this->lra->Upload->FileName];
                $NewFileCount = count($newFiles);
                for ($i = 0; $i < $NewFileCount; $i++) {
                    if ($newFiles[$i] != "") {
                        $file = $newFiles[$i];
                        $tempPath = UploadTempPath($this->lra, $this->lra->Upload->Index);
                        if (file_exists($tempPath . $file)) {
                            if (Config("DELETE_UPLOADED_FILES")) {
                                $oldFileFound = false;
                                $oldFileCount = count($oldFiles);
                                for ($j = 0; $j < $oldFileCount; $j++) {
                                    $oldFile = $oldFiles[$j];
                                    if ($oldFile == $file) { // Old file found, no need to delete anymore
                                        array_splice($oldFiles, $j, 1);
                                        $oldFileFound = true;
                                        break;
                                    }
                                }
                                if ($oldFileFound) { // No need to check if file exists further
                                    continue;
                                }
                            }
                            $file1 = UniqueFilename($this->lra->physicalUploadPath(), $file); // Get new file name
                            if ($file1 != $file) { // Rename temp file
                                while (file_exists($tempPath . $file1) || file_exists($this->lra->physicalUploadPath() . $file1)) { // Make sure no file name clash
                                    $file1 = UniqueFilename([$this->lra->physicalUploadPath(), $tempPath], $file1, true); // Use indexed name
                                }
                                rename($tempPath . $file, $tempPath . $file1);
                                $newFiles[$i] = $file1;
                            }
                        }
                    }
                }
                $this->lra->Upload->DbValue = empty($oldFiles) ? "" : implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $oldFiles);
                $this->lra->Upload->FileName = implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $newFiles);
                $this->lra->setDbValueDef($rsnew, $this->lra->Upload->FileName, null, false);
            }
        }
        if ($this->calk->Visible && !$this->calk->Upload->KeepFile) {
            $oldFiles = EmptyValue($this->calk->Upload->DbValue) ? [] : [$this->calk->htmlDecode($this->calk->Upload->DbValue)];
            if (!EmptyValue($this->calk->Upload->FileName)) {
                $newFiles = [$this->calk->Upload->FileName];
                $NewFileCount = count($newFiles);
                for ($i = 0; $i < $NewFileCount; $i++) {
                    if ($newFiles[$i] != "") {
                        $file = $newFiles[$i];
                        $tempPath = UploadTempPath($this->calk, $this->calk->Upload->Index);
                        if (file_exists($tempPath . $file)) {
                            if (Config("DELETE_UPLOADED_FILES")) {
                                $oldFileFound = false;
                                $oldFileCount = count($oldFiles);
                                for ($j = 0; $j < $oldFileCount; $j++) {
                                    $oldFile = $oldFiles[$j];
                                    if ($oldFile == $file) { // Old file found, no need to delete anymore
                                        array_splice($oldFiles, $j, 1);
                                        $oldFileFound = true;
                                        break;
                                    }
                                }
                                if ($oldFileFound) { // No need to check if file exists further
                                    continue;
                                }
                            }
                            $file1 = UniqueFilename($this->calk->physicalUploadPath(), $file); // Get new file name
                            if ($file1 != $file) { // Rename temp file
                                while (file_exists($tempPath . $file1) || file_exists($this->calk->physicalUploadPath() . $file1)) { // Make sure no file name clash
                                    $file1 = UniqueFilename([$this->calk->physicalUploadPath(), $tempPath], $file1, true); // Use indexed name
                                }
                                rename($tempPath . $file, $tempPath . $file1);
                                $newFiles[$i] = $file1;
                            }
                        }
                    }
                }
                $this->calk->Upload->DbValue = empty($oldFiles) ? "" : implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $oldFiles);
                $this->calk->Upload->FileName = implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $newFiles);
                $this->calk->setDbValueDef($rsnew, $this->calk->Upload->FileName, null, false);
            }
        }
        if ($this->lo->Visible && !$this->lo->Upload->KeepFile) {
            $oldFiles = EmptyValue($this->lo->Upload->DbValue) ? [] : [$this->lo->htmlDecode($this->lo->Upload->DbValue)];
            if (!EmptyValue($this->lo->Upload->FileName)) {
                $newFiles = [$this->lo->Upload->FileName];
                $NewFileCount = count($newFiles);
                for ($i = 0; $i < $NewFileCount; $i++) {
                    if ($newFiles[$i] != "") {
                        $file = $newFiles[$i];
                        $tempPath = UploadTempPath($this->lo, $this->lo->Upload->Index);
                        if (file_exists($tempPath . $file)) {
                            if (Config("DELETE_UPLOADED_FILES")) {
                                $oldFileFound = false;
                                $oldFileCount = count($oldFiles);
                                for ($j = 0; $j < $oldFileCount; $j++) {
                                    $oldFile = $oldFiles[$j];
                                    if ($oldFile == $file) { // Old file found, no need to delete anymore
                                        array_splice($oldFiles, $j, 1);
                                        $oldFileFound = true;
                                        break;
                                    }
                                }
                                if ($oldFileFound) { // No need to check if file exists further
                                    continue;
                                }
                            }
                            $file1 = UniqueFilename($this->lo->physicalUploadPath(), $file); // Get new file name
                            if ($file1 != $file) { // Rename temp file
                                while (file_exists($tempPath . $file1) || file_exists($this->lo->physicalUploadPath() . $file1)) { // Make sure no file name clash
                                    $file1 = UniqueFilename([$this->lo->physicalUploadPath(), $tempPath], $file1, true); // Use indexed name
                                }
                                rename($tempPath . $file, $tempPath . $file1);
                                $newFiles[$i] = $file1;
                            }
                        }
                    }
                }
                $this->lo->Upload->DbValue = empty($oldFiles) ? "" : implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $oldFiles);
                $this->lo->Upload->FileName = implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $newFiles);
                $this->lo->setDbValueDef($rsnew, $this->lo->Upload->FileName, null, false);
            }
        }
        if ($this->lpe->Visible && !$this->lpe->Upload->KeepFile) {
            $oldFiles = EmptyValue($this->lpe->Upload->DbValue) ? [] : [$this->lpe->htmlDecode($this->lpe->Upload->DbValue)];
            if (!EmptyValue($this->lpe->Upload->FileName)) {
                $newFiles = [$this->lpe->Upload->FileName];
                $NewFileCount = count($newFiles);
                for ($i = 0; $i < $NewFileCount; $i++) {
                    if ($newFiles[$i] != "") {
                        $file = $newFiles[$i];
                        $tempPath = UploadTempPath($this->lpe, $this->lpe->Upload->Index);
                        if (file_exists($tempPath . $file)) {
                            if (Config("DELETE_UPLOADED_FILES")) {
                                $oldFileFound = false;
                                $oldFileCount = count($oldFiles);
                                for ($j = 0; $j < $oldFileCount; $j++) {
                                    $oldFile = $oldFiles[$j];
                                    if ($oldFile == $file) { // Old file found, no need to delete anymore
                                        array_splice($oldFiles, $j, 1);
                                        $oldFileFound = true;
                                        break;
                                    }
                                }
                                if ($oldFileFound) { // No need to check if file exists further
                                    continue;
                                }
                            }
                            $file1 = UniqueFilename($this->lpe->physicalUploadPath(), $file); // Get new file name
                            if ($file1 != $file) { // Rename temp file
                                while (file_exists($tempPath . $file1) || file_exists($this->lpe->physicalUploadPath() . $file1)) { // Make sure no file name clash
                                    $file1 = UniqueFilename([$this->lpe->physicalUploadPath(), $tempPath], $file1, true); // Use indexed name
                                }
                                rename($tempPath . $file, $tempPath . $file1);
                                $newFiles[$i] = $file1;
                            }
                        }
                    }
                }
                $this->lpe->Upload->DbValue = empty($oldFiles) ? "" : implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $oldFiles);
                $this->lpe->Upload->FileName = implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $newFiles);
                $this->lpe->setDbValueDef($rsnew, $this->lpe->Upload->FileName, null, false);
            }
        }
        if ($this->lpsal->Visible && !$this->lpsal->Upload->KeepFile) {
            $oldFiles = EmptyValue($this->lpsal->Upload->DbValue) ? [] : [$this->lpsal->htmlDecode($this->lpsal->Upload->DbValue)];
            if (!EmptyValue($this->lpsal->Upload->FileName)) {
                $newFiles = [$this->lpsal->Upload->FileName];
                $NewFileCount = count($newFiles);
                for ($i = 0; $i < $NewFileCount; $i++) {
                    if ($newFiles[$i] != "") {
                        $file = $newFiles[$i];
                        $tempPath = UploadTempPath($this->lpsal, $this->lpsal->Upload->Index);
                        if (file_exists($tempPath . $file)) {
                            if (Config("DELETE_UPLOADED_FILES")) {
                                $oldFileFound = false;
                                $oldFileCount = count($oldFiles);
                                for ($j = 0; $j < $oldFileCount; $j++) {
                                    $oldFile = $oldFiles[$j];
                                    if ($oldFile == $file) { // Old file found, no need to delete anymore
                                        array_splice($oldFiles, $j, 1);
                                        $oldFileFound = true;
                                        break;
                                    }
                                }
                                if ($oldFileFound) { // No need to check if file exists further
                                    continue;
                                }
                            }
                            $file1 = UniqueFilename($this->lpsal->physicalUploadPath(), $file); // Get new file name
                            if ($file1 != $file) { // Rename temp file
                                while (file_exists($tempPath . $file1) || file_exists($this->lpsal->physicalUploadPath() . $file1)) { // Make sure no file name clash
                                    $file1 = UniqueFilename([$this->lpsal->physicalUploadPath(), $tempPath], $file1, true); // Use indexed name
                                }
                                rename($tempPath . $file, $tempPath . $file1);
                                $newFiles[$i] = $file1;
                            }
                        }
                    }
                }
                $this->lpsal->Upload->DbValue = empty($oldFiles) ? "" : implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $oldFiles);
                $this->lpsal->Upload->FileName = implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $newFiles);
                $this->lpsal->setDbValueDef($rsnew, $this->lpsal->Upload->FileName, null, false);
            }
        }
        if ($this->lak->Visible && !$this->lak->Upload->KeepFile) {
            $oldFiles = EmptyValue($this->lak->Upload->DbValue) ? [] : [$this->lak->htmlDecode($this->lak->Upload->DbValue)];
            if (!EmptyValue($this->lak->Upload->FileName)) {
                $newFiles = [$this->lak->Upload->FileName];
                $NewFileCount = count($newFiles);
                for ($i = 0; $i < $NewFileCount; $i++) {
                    if ($newFiles[$i] != "") {
                        $file = $newFiles[$i];
                        $tempPath = UploadTempPath($this->lak, $this->lak->Upload->Index);
                        if (file_exists($tempPath . $file)) {
                            if (Config("DELETE_UPLOADED_FILES")) {
                                $oldFileFound = false;
                                $oldFileCount = count($oldFiles);
                                for ($j = 0; $j < $oldFileCount; $j++) {
                                    $oldFile = $oldFiles[$j];
                                    if ($oldFile == $file) { // Old file found, no need to delete anymore
                                        array_splice($oldFiles, $j, 1);
                                        $oldFileFound = true;
                                        break;
                                    }
                                }
                                if ($oldFileFound) { // No need to check if file exists further
                                    continue;
                                }
                            }
                            $file1 = UniqueFilename($this->lak->physicalUploadPath(), $file); // Get new file name
                            if ($file1 != $file) { // Rename temp file
                                while (file_exists($tempPath . $file1) || file_exists($this->lak->physicalUploadPath() . $file1)) { // Make sure no file name clash
                                    $file1 = UniqueFilename([$this->lak->physicalUploadPath(), $tempPath], $file1, true); // Use indexed name
                                }
                                rename($tempPath . $file, $tempPath . $file1);
                                $newFiles[$i] = $file1;
                            }
                        }
                    }
                }
                $this->lak->Upload->DbValue = empty($oldFiles) ? "" : implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $oldFiles);
                $this->lak->Upload->FileName = implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $newFiles);
                $this->lak->setDbValueDef($rsnew, $this->lak->Upload->FileName, null, false);
            }
        }
        if ($this->laporan_pemeriksaan->Visible && !$this->laporan_pemeriksaan->Upload->KeepFile) {
            $oldFiles = EmptyValue($this->laporan_pemeriksaan->Upload->DbValue) ? [] : [$this->laporan_pemeriksaan->htmlDecode($this->laporan_pemeriksaan->Upload->DbValue)];
            if (!EmptyValue($this->laporan_pemeriksaan->Upload->FileName)) {
                $newFiles = [$this->laporan_pemeriksaan->Upload->FileName];
                $NewFileCount = count($newFiles);
                for ($i = 0; $i < $NewFileCount; $i++) {
                    if ($newFiles[$i] != "") {
                        $file = $newFiles[$i];
                        $tempPath = UploadTempPath($this->laporan_pemeriksaan, $this->laporan_pemeriksaan->Upload->Index);
                        if (file_exists($tempPath . $file)) {
                            if (Config("DELETE_UPLOADED_FILES")) {
                                $oldFileFound = false;
                                $oldFileCount = count($oldFiles);
                                for ($j = 0; $j < $oldFileCount; $j++) {
                                    $oldFile = $oldFiles[$j];
                                    if ($oldFile == $file) { // Old file found, no need to delete anymore
                                        array_splice($oldFiles, $j, 1);
                                        $oldFileFound = true;
                                        break;
                                    }
                                }
                                if ($oldFileFound) { // No need to check if file exists further
                                    continue;
                                }
                            }
                            $file1 = UniqueFilename($this->laporan_pemeriksaan->physicalUploadPath(), $file); // Get new file name
                            if ($file1 != $file) { // Rename temp file
                                while (file_exists($tempPath . $file1) || file_exists($this->laporan_pemeriksaan->physicalUploadPath() . $file1)) { // Make sure no file name clash
                                    $file1 = UniqueFilename([$this->laporan_pemeriksaan->physicalUploadPath(), $tempPath], $file1, true); // Use indexed name
                                }
                                rename($tempPath . $file, $tempPath . $file1);
                                $newFiles[$i] = $file1;
                            }
                        }
                    }
                }
                $this->laporan_pemeriksaan->Upload->DbValue = empty($oldFiles) ? "" : implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $oldFiles);
                $this->laporan_pemeriksaan->Upload->FileName = implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $newFiles);
                $this->laporan_pemeriksaan->setDbValueDef($rsnew, $this->laporan_pemeriksaan->Upload->FileName, null, false);
            }
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
                if ($this->surat_pengantar->Visible && !$this->surat_pengantar->Upload->KeepFile) {
                    $oldFiles = EmptyValue($this->surat_pengantar->Upload->DbValue) ? [] : [$this->surat_pengantar->htmlDecode($this->surat_pengantar->Upload->DbValue)];
                    if (!EmptyValue($this->surat_pengantar->Upload->FileName)) {
                        $newFiles = [$this->surat_pengantar->Upload->FileName];
                        $newFiles2 = [$this->surat_pengantar->htmlDecode($rsnew['surat_pengantar'])];
                        $newFileCount = count($newFiles);
                        for ($i = 0; $i < $newFileCount; $i++) {
                            if ($newFiles[$i] != "") {
                                $file = UploadTempPath($this->surat_pengantar, $this->surat_pengantar->Upload->Index) . $newFiles[$i];
                                if (file_exists($file)) {
                                    if (@$newFiles2[$i] != "") { // Use correct file name
                                        $newFiles[$i] = $newFiles2[$i];
                                    }
                                    if (!$this->surat_pengantar->Upload->SaveToFile($newFiles[$i], true, $i)) { // Just replace
                                        $this->setFailureMessage($Language->phrase("UploadErrMsg7"));
                                        return false;
                                    }
                                }
                            }
                        }
                    } else {
                        $newFiles = [];
                    }
                    if (Config("DELETE_UPLOADED_FILES")) {
                        foreach ($oldFiles as $oldFile) {
                            if ($oldFile != "" && !in_array($oldFile, $newFiles)) {
                                @unlink($this->surat_pengantar->oldPhysicalUploadPath() . $oldFile);
                            }
                        }
                    }
                }
                if ($this->skd_rqanunpert->Visible && !$this->skd_rqanunpert->Upload->KeepFile) {
                    $oldFiles = EmptyValue($this->skd_rqanunpert->Upload->DbValue) ? [] : [$this->skd_rqanunpert->htmlDecode($this->skd_rqanunpert->Upload->DbValue)];
                    if (!EmptyValue($this->skd_rqanunpert->Upload->FileName)) {
                        $newFiles = [$this->skd_rqanunpert->Upload->FileName];
                        $newFiles2 = [$this->skd_rqanunpert->htmlDecode($rsnew['skd_rqanunpert'])];
                        $newFileCount = count($newFiles);
                        for ($i = 0; $i < $newFileCount; $i++) {
                            if ($newFiles[$i] != "") {
                                $file = UploadTempPath($this->skd_rqanunpert, $this->skd_rqanunpert->Upload->Index) . $newFiles[$i];
                                if (file_exists($file)) {
                                    if (@$newFiles2[$i] != "") { // Use correct file name
                                        $newFiles[$i] = $newFiles2[$i];
                                    }
                                    if (!$this->skd_rqanunpert->Upload->SaveToFile($newFiles[$i], true, $i)) { // Just replace
                                        $this->setFailureMessage($Language->phrase("UploadErrMsg7"));
                                        return false;
                                    }
                                }
                            }
                        }
                    } else {
                        $newFiles = [];
                    }
                    if (Config("DELETE_UPLOADED_FILES")) {
                        foreach ($oldFiles as $oldFile) {
                            if ($oldFile != "" && !in_array($oldFile, $newFiles)) {
                                @unlink($this->skd_rqanunpert->oldPhysicalUploadPath() . $oldFile);
                            }
                        }
                    }
                }
                if ($this->rqanun_apbkpert->Visible && !$this->rqanun_apbkpert->Upload->KeepFile) {
                    $oldFiles = EmptyValue($this->rqanun_apbkpert->Upload->DbValue) ? [] : [$this->rqanun_apbkpert->htmlDecode($this->rqanun_apbkpert->Upload->DbValue)];
                    if (!EmptyValue($this->rqanun_apbkpert->Upload->FileName)) {
                        $newFiles = [$this->rqanun_apbkpert->Upload->FileName];
                        $newFiles2 = [$this->rqanun_apbkpert->htmlDecode($rsnew['rqanun_apbkpert'])];
                        $newFileCount = count($newFiles);
                        for ($i = 0; $i < $newFileCount; $i++) {
                            if ($newFiles[$i] != "") {
                                $file = UploadTempPath($this->rqanun_apbkpert, $this->rqanun_apbkpert->Upload->Index) . $newFiles[$i];
                                if (file_exists($file)) {
                                    if (@$newFiles2[$i] != "") { // Use correct file name
                                        $newFiles[$i] = $newFiles2[$i];
                                    }
                                    if (!$this->rqanun_apbkpert->Upload->SaveToFile($newFiles[$i], true, $i)) { // Just replace
                                        $this->setFailureMessage($Language->phrase("UploadErrMsg7"));
                                        return false;
                                    }
                                }
                            }
                        }
                    } else {
                        $newFiles = [];
                    }
                    if (Config("DELETE_UPLOADED_FILES")) {
                        foreach ($oldFiles as $oldFile) {
                            if ($oldFile != "" && !in_array($oldFile, $newFiles)) {
                                @unlink($this->rqanun_apbkpert->oldPhysicalUploadPath() . $oldFile);
                            }
                        }
                    }
                }
                if ($this->rperbup_apbkpert->Visible && !$this->rperbup_apbkpert->Upload->KeepFile) {
                    $oldFiles = EmptyValue($this->rperbup_apbkpert->Upload->DbValue) ? [] : [$this->rperbup_apbkpert->htmlDecode($this->rperbup_apbkpert->Upload->DbValue)];
                    if (!EmptyValue($this->rperbup_apbkpert->Upload->FileName)) {
                        $newFiles = [$this->rperbup_apbkpert->Upload->FileName];
                        $newFiles2 = [$this->rperbup_apbkpert->htmlDecode($rsnew['rperbup_apbkpert'])];
                        $newFileCount = count($newFiles);
                        for ($i = 0; $i < $newFileCount; $i++) {
                            if ($newFiles[$i] != "") {
                                $file = UploadTempPath($this->rperbup_apbkpert, $this->rperbup_apbkpert->Upload->Index) . $newFiles[$i];
                                if (file_exists($file)) {
                                    if (@$newFiles2[$i] != "") { // Use correct file name
                                        $newFiles[$i] = $newFiles2[$i];
                                    }
                                    if (!$this->rperbup_apbkpert->Upload->SaveToFile($newFiles[$i], true, $i)) { // Just replace
                                        $this->setFailureMessage($Language->phrase("UploadErrMsg7"));
                                        return false;
                                    }
                                }
                            }
                        }
                    } else {
                        $newFiles = [];
                    }
                    if (Config("DELETE_UPLOADED_FILES")) {
                        foreach ($oldFiles as $oldFile) {
                            if ($oldFile != "" && !in_array($oldFile, $newFiles)) {
                                @unlink($this->rperbup_apbkpert->oldPhysicalUploadPath() . $oldFile);
                            }
                        }
                    }
                }
                if ($this->pbkdd_apbkpert->Visible && !$this->pbkdd_apbkpert->Upload->KeepFile) {
                    $oldFiles = EmptyValue($this->pbkdd_apbkpert->Upload->DbValue) ? [] : [$this->pbkdd_apbkpert->htmlDecode($this->pbkdd_apbkpert->Upload->DbValue)];
                    if (!EmptyValue($this->pbkdd_apbkpert->Upload->FileName)) {
                        $newFiles = [$this->pbkdd_apbkpert->Upload->FileName];
                        $newFiles2 = [$this->pbkdd_apbkpert->htmlDecode($rsnew['pbkdd_apbkpert'])];
                        $newFileCount = count($newFiles);
                        for ($i = 0; $i < $newFileCount; $i++) {
                            if ($newFiles[$i] != "") {
                                $file = UploadTempPath($this->pbkdd_apbkpert, $this->pbkdd_apbkpert->Upload->Index) . $newFiles[$i];
                                if (file_exists($file)) {
                                    if (@$newFiles2[$i] != "") { // Use correct file name
                                        $newFiles[$i] = $newFiles2[$i];
                                    }
                                    if (!$this->pbkdd_apbkpert->Upload->SaveToFile($newFiles[$i], true, $i)) { // Just replace
                                        $this->setFailureMessage($Language->phrase("UploadErrMsg7"));
                                        return false;
                                    }
                                }
                            }
                        }
                    } else {
                        $newFiles = [];
                    }
                    if (Config("DELETE_UPLOADED_FILES")) {
                        foreach ($oldFiles as $oldFile) {
                            if ($oldFile != "" && !in_array($oldFile, $newFiles)) {
                                @unlink($this->pbkdd_apbkpert->oldPhysicalUploadPath() . $oldFile);
                            }
                        }
                    }
                }
                if ($this->risalah_sidang->Visible && !$this->risalah_sidang->Upload->KeepFile) {
                    $oldFiles = EmptyValue($this->risalah_sidang->Upload->DbValue) ? [] : [$this->risalah_sidang->htmlDecode($this->risalah_sidang->Upload->DbValue)];
                    if (!EmptyValue($this->risalah_sidang->Upload->FileName)) {
                        $newFiles = [$this->risalah_sidang->Upload->FileName];
                        $newFiles2 = [$this->risalah_sidang->htmlDecode($rsnew['risalah_sidang'])];
                        $newFileCount = count($newFiles);
                        for ($i = 0; $i < $newFileCount; $i++) {
                            if ($newFiles[$i] != "") {
                                $file = UploadTempPath($this->risalah_sidang, $this->risalah_sidang->Upload->Index) . $newFiles[$i];
                                if (file_exists($file)) {
                                    if (@$newFiles2[$i] != "") { // Use correct file name
                                        $newFiles[$i] = $newFiles2[$i];
                                    }
                                    if (!$this->risalah_sidang->Upload->SaveToFile($newFiles[$i], true, $i)) { // Just replace
                                        $this->setFailureMessage($Language->phrase("UploadErrMsg7"));
                                        return false;
                                    }
                                }
                            }
                        }
                    } else {
                        $newFiles = [];
                    }
                    if (Config("DELETE_UPLOADED_FILES")) {
                        foreach ($oldFiles as $oldFile) {
                            if ($oldFile != "" && !in_array($oldFile, $newFiles)) {
                                @unlink($this->risalah_sidang->oldPhysicalUploadPath() . $oldFile);
                            }
                        }
                    }
                }
                if ($this->absen_peserta->Visible && !$this->absen_peserta->Upload->KeepFile) {
                    $oldFiles = EmptyValue($this->absen_peserta->Upload->DbValue) ? [] : [$this->absen_peserta->htmlDecode($this->absen_peserta->Upload->DbValue)];
                    if (!EmptyValue($this->absen_peserta->Upload->FileName)) {
                        $newFiles = [$this->absen_peserta->Upload->FileName];
                        $newFiles2 = [$this->absen_peserta->htmlDecode($rsnew['absen_peserta'])];
                        $newFileCount = count($newFiles);
                        for ($i = 0; $i < $newFileCount; $i++) {
                            if ($newFiles[$i] != "") {
                                $file = UploadTempPath($this->absen_peserta, $this->absen_peserta->Upload->Index) . $newFiles[$i];
                                if (file_exists($file)) {
                                    if (@$newFiles2[$i] != "") { // Use correct file name
                                        $newFiles[$i] = $newFiles2[$i];
                                    }
                                    if (!$this->absen_peserta->Upload->SaveToFile($newFiles[$i], true, $i)) { // Just replace
                                        $this->setFailureMessage($Language->phrase("UploadErrMsg7"));
                                        return false;
                                    }
                                }
                            }
                        }
                    } else {
                        $newFiles = [];
                    }
                    if (Config("DELETE_UPLOADED_FILES")) {
                        foreach ($oldFiles as $oldFile) {
                            if ($oldFile != "" && !in_array($oldFile, $newFiles)) {
                                @unlink($this->absen_peserta->oldPhysicalUploadPath() . $oldFile);
                            }
                        }
                    }
                }
                if ($this->neraca->Visible && !$this->neraca->Upload->KeepFile) {
                    $oldFiles = EmptyValue($this->neraca->Upload->DbValue) ? [] : [$this->neraca->htmlDecode($this->neraca->Upload->DbValue)];
                    if (!EmptyValue($this->neraca->Upload->FileName)) {
                        $newFiles = [$this->neraca->Upload->FileName];
                        $newFiles2 = [$this->neraca->htmlDecode($rsnew['neraca'])];
                        $newFileCount = count($newFiles);
                        for ($i = 0; $i < $newFileCount; $i++) {
                            if ($newFiles[$i] != "") {
                                $file = UploadTempPath($this->neraca, $this->neraca->Upload->Index) . $newFiles[$i];
                                if (file_exists($file)) {
                                    if (@$newFiles2[$i] != "") { // Use correct file name
                                        $newFiles[$i] = $newFiles2[$i];
                                    }
                                    if (!$this->neraca->Upload->SaveToFile($newFiles[$i], true, $i)) { // Just replace
                                        $this->setFailureMessage($Language->phrase("UploadErrMsg7"));
                                        return false;
                                    }
                                }
                            }
                        }
                    } else {
                        $newFiles = [];
                    }
                    if (Config("DELETE_UPLOADED_FILES")) {
                        foreach ($oldFiles as $oldFile) {
                            if ($oldFile != "" && !in_array($oldFile, $newFiles)) {
                                @unlink($this->neraca->oldPhysicalUploadPath() . $oldFile);
                            }
                        }
                    }
                }
                if ($this->lra->Visible && !$this->lra->Upload->KeepFile) {
                    $oldFiles = EmptyValue($this->lra->Upload->DbValue) ? [] : [$this->lra->htmlDecode($this->lra->Upload->DbValue)];
                    if (!EmptyValue($this->lra->Upload->FileName)) {
                        $newFiles = [$this->lra->Upload->FileName];
                        $newFiles2 = [$this->lra->htmlDecode($rsnew['lra'])];
                        $newFileCount = count($newFiles);
                        for ($i = 0; $i < $newFileCount; $i++) {
                            if ($newFiles[$i] != "") {
                                $file = UploadTempPath($this->lra, $this->lra->Upload->Index) . $newFiles[$i];
                                if (file_exists($file)) {
                                    if (@$newFiles2[$i] != "") { // Use correct file name
                                        $newFiles[$i] = $newFiles2[$i];
                                    }
                                    if (!$this->lra->Upload->SaveToFile($newFiles[$i], true, $i)) { // Just replace
                                        $this->setFailureMessage($Language->phrase("UploadErrMsg7"));
                                        return false;
                                    }
                                }
                            }
                        }
                    } else {
                        $newFiles = [];
                    }
                    if (Config("DELETE_UPLOADED_FILES")) {
                        foreach ($oldFiles as $oldFile) {
                            if ($oldFile != "" && !in_array($oldFile, $newFiles)) {
                                @unlink($this->lra->oldPhysicalUploadPath() . $oldFile);
                            }
                        }
                    }
                }
                if ($this->calk->Visible && !$this->calk->Upload->KeepFile) {
                    $oldFiles = EmptyValue($this->calk->Upload->DbValue) ? [] : [$this->calk->htmlDecode($this->calk->Upload->DbValue)];
                    if (!EmptyValue($this->calk->Upload->FileName)) {
                        $newFiles = [$this->calk->Upload->FileName];
                        $newFiles2 = [$this->calk->htmlDecode($rsnew['calk'])];
                        $newFileCount = count($newFiles);
                        for ($i = 0; $i < $newFileCount; $i++) {
                            if ($newFiles[$i] != "") {
                                $file = UploadTempPath($this->calk, $this->calk->Upload->Index) . $newFiles[$i];
                                if (file_exists($file)) {
                                    if (@$newFiles2[$i] != "") { // Use correct file name
                                        $newFiles[$i] = $newFiles2[$i];
                                    }
                                    if (!$this->calk->Upload->SaveToFile($newFiles[$i], true, $i)) { // Just replace
                                        $this->setFailureMessage($Language->phrase("UploadErrMsg7"));
                                        return false;
                                    }
                                }
                            }
                        }
                    } else {
                        $newFiles = [];
                    }
                    if (Config("DELETE_UPLOADED_FILES")) {
                        foreach ($oldFiles as $oldFile) {
                            if ($oldFile != "" && !in_array($oldFile, $newFiles)) {
                                @unlink($this->calk->oldPhysicalUploadPath() . $oldFile);
                            }
                        }
                    }
                }
                if ($this->lo->Visible && !$this->lo->Upload->KeepFile) {
                    $oldFiles = EmptyValue($this->lo->Upload->DbValue) ? [] : [$this->lo->htmlDecode($this->lo->Upload->DbValue)];
                    if (!EmptyValue($this->lo->Upload->FileName)) {
                        $newFiles = [$this->lo->Upload->FileName];
                        $newFiles2 = [$this->lo->htmlDecode($rsnew['lo'])];
                        $newFileCount = count($newFiles);
                        for ($i = 0; $i < $newFileCount; $i++) {
                            if ($newFiles[$i] != "") {
                                $file = UploadTempPath($this->lo, $this->lo->Upload->Index) . $newFiles[$i];
                                if (file_exists($file)) {
                                    if (@$newFiles2[$i] != "") { // Use correct file name
                                        $newFiles[$i] = $newFiles2[$i];
                                    }
                                    if (!$this->lo->Upload->SaveToFile($newFiles[$i], true, $i)) { // Just replace
                                        $this->setFailureMessage($Language->phrase("UploadErrMsg7"));
                                        return false;
                                    }
                                }
                            }
                        }
                    } else {
                        $newFiles = [];
                    }
                    if (Config("DELETE_UPLOADED_FILES")) {
                        foreach ($oldFiles as $oldFile) {
                            if ($oldFile != "" && !in_array($oldFile, $newFiles)) {
                                @unlink($this->lo->oldPhysicalUploadPath() . $oldFile);
                            }
                        }
                    }
                }
                if ($this->lpe->Visible && !$this->lpe->Upload->KeepFile) {
                    $oldFiles = EmptyValue($this->lpe->Upload->DbValue) ? [] : [$this->lpe->htmlDecode($this->lpe->Upload->DbValue)];
                    if (!EmptyValue($this->lpe->Upload->FileName)) {
                        $newFiles = [$this->lpe->Upload->FileName];
                        $newFiles2 = [$this->lpe->htmlDecode($rsnew['lpe'])];
                        $newFileCount = count($newFiles);
                        for ($i = 0; $i < $newFileCount; $i++) {
                            if ($newFiles[$i] != "") {
                                $file = UploadTempPath($this->lpe, $this->lpe->Upload->Index) . $newFiles[$i];
                                if (file_exists($file)) {
                                    if (@$newFiles2[$i] != "") { // Use correct file name
                                        $newFiles[$i] = $newFiles2[$i];
                                    }
                                    if (!$this->lpe->Upload->SaveToFile($newFiles[$i], true, $i)) { // Just replace
                                        $this->setFailureMessage($Language->phrase("UploadErrMsg7"));
                                        return false;
                                    }
                                }
                            }
                        }
                    } else {
                        $newFiles = [];
                    }
                    if (Config("DELETE_UPLOADED_FILES")) {
                        foreach ($oldFiles as $oldFile) {
                            if ($oldFile != "" && !in_array($oldFile, $newFiles)) {
                                @unlink($this->lpe->oldPhysicalUploadPath() . $oldFile);
                            }
                        }
                    }
                }
                if ($this->lpsal->Visible && !$this->lpsal->Upload->KeepFile) {
                    $oldFiles = EmptyValue($this->lpsal->Upload->DbValue) ? [] : [$this->lpsal->htmlDecode($this->lpsal->Upload->DbValue)];
                    if (!EmptyValue($this->lpsal->Upload->FileName)) {
                        $newFiles = [$this->lpsal->Upload->FileName];
                        $newFiles2 = [$this->lpsal->htmlDecode($rsnew['lpsal'])];
                        $newFileCount = count($newFiles);
                        for ($i = 0; $i < $newFileCount; $i++) {
                            if ($newFiles[$i] != "") {
                                $file = UploadTempPath($this->lpsal, $this->lpsal->Upload->Index) . $newFiles[$i];
                                if (file_exists($file)) {
                                    if (@$newFiles2[$i] != "") { // Use correct file name
                                        $newFiles[$i] = $newFiles2[$i];
                                    }
                                    if (!$this->lpsal->Upload->SaveToFile($newFiles[$i], true, $i)) { // Just replace
                                        $this->setFailureMessage($Language->phrase("UploadErrMsg7"));
                                        return false;
                                    }
                                }
                            }
                        }
                    } else {
                        $newFiles = [];
                    }
                    if (Config("DELETE_UPLOADED_FILES")) {
                        foreach ($oldFiles as $oldFile) {
                            if ($oldFile != "" && !in_array($oldFile, $newFiles)) {
                                @unlink($this->lpsal->oldPhysicalUploadPath() . $oldFile);
                            }
                        }
                    }
                }
                if ($this->lak->Visible && !$this->lak->Upload->KeepFile) {
                    $oldFiles = EmptyValue($this->lak->Upload->DbValue) ? [] : [$this->lak->htmlDecode($this->lak->Upload->DbValue)];
                    if (!EmptyValue($this->lak->Upload->FileName)) {
                        $newFiles = [$this->lak->Upload->FileName];
                        $newFiles2 = [$this->lak->htmlDecode($rsnew['lak'])];
                        $newFileCount = count($newFiles);
                        for ($i = 0; $i < $newFileCount; $i++) {
                            if ($newFiles[$i] != "") {
                                $file = UploadTempPath($this->lak, $this->lak->Upload->Index) . $newFiles[$i];
                                if (file_exists($file)) {
                                    if (@$newFiles2[$i] != "") { // Use correct file name
                                        $newFiles[$i] = $newFiles2[$i];
                                    }
                                    if (!$this->lak->Upload->SaveToFile($newFiles[$i], true, $i)) { // Just replace
                                        $this->setFailureMessage($Language->phrase("UploadErrMsg7"));
                                        return false;
                                    }
                                }
                            }
                        }
                    } else {
                        $newFiles = [];
                    }
                    if (Config("DELETE_UPLOADED_FILES")) {
                        foreach ($oldFiles as $oldFile) {
                            if ($oldFile != "" && !in_array($oldFile, $newFiles)) {
                                @unlink($this->lak->oldPhysicalUploadPath() . $oldFile);
                            }
                        }
                    }
                }
                if ($this->laporan_pemeriksaan->Visible && !$this->laporan_pemeriksaan->Upload->KeepFile) {
                    $oldFiles = EmptyValue($this->laporan_pemeriksaan->Upload->DbValue) ? [] : [$this->laporan_pemeriksaan->htmlDecode($this->laporan_pemeriksaan->Upload->DbValue)];
                    if (!EmptyValue($this->laporan_pemeriksaan->Upload->FileName)) {
                        $newFiles = [$this->laporan_pemeriksaan->Upload->FileName];
                        $newFiles2 = [$this->laporan_pemeriksaan->htmlDecode($rsnew['laporan_pemeriksaan'])];
                        $newFileCount = count($newFiles);
                        for ($i = 0; $i < $newFileCount; $i++) {
                            if ($newFiles[$i] != "") {
                                $file = UploadTempPath($this->laporan_pemeriksaan, $this->laporan_pemeriksaan->Upload->Index) . $newFiles[$i];
                                if (file_exists($file)) {
                                    if (@$newFiles2[$i] != "") { // Use correct file name
                                        $newFiles[$i] = $newFiles2[$i];
                                    }
                                    if (!$this->laporan_pemeriksaan->Upload->SaveToFile($newFiles[$i], true, $i)) { // Just replace
                                        $this->setFailureMessage($Language->phrase("UploadErrMsg7"));
                                        return false;
                                    }
                                }
                            }
                        }
                    } else {
                        $newFiles = [];
                    }
                    if (Config("DELETE_UPLOADED_FILES")) {
                        foreach ($oldFiles as $oldFile) {
                            if ($oldFile != "" && !in_array($oldFile, $newFiles)) {
                                @unlink($this->laporan_pemeriksaan->oldPhysicalUploadPath() . $oldFile);
                            }
                        }
                    }
                }
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
            // surat_pengantar
            CleanUploadTempPath($this->surat_pengantar, $this->surat_pengantar->Upload->Index);

            // skd_rqanunpert
            CleanUploadTempPath($this->skd_rqanunpert, $this->skd_rqanunpert->Upload->Index);

            // rqanun_apbkpert
            CleanUploadTempPath($this->rqanun_apbkpert, $this->rqanun_apbkpert->Upload->Index);

            // rperbup_apbkpert
            CleanUploadTempPath($this->rperbup_apbkpert, $this->rperbup_apbkpert->Upload->Index);

            // pbkdd_apbkpert
            CleanUploadTempPath($this->pbkdd_apbkpert, $this->pbkdd_apbkpert->Upload->Index);

            // risalah_sidang
            CleanUploadTempPath($this->risalah_sidang, $this->risalah_sidang->Upload->Index);

            // absen_peserta
            CleanUploadTempPath($this->absen_peserta, $this->absen_peserta->Upload->Index);

            // neraca
            CleanUploadTempPath($this->neraca, $this->neraca->Upload->Index);

            // lra
            CleanUploadTempPath($this->lra, $this->lra->Upload->Index);

            // calk
            CleanUploadTempPath($this->calk, $this->calk->Upload->Index);

            // lo
            CleanUploadTempPath($this->lo, $this->lo->Upload->Index);

            // lpe
            CleanUploadTempPath($this->lpe, $this->lpe->Upload->Index);

            // lpsal
            CleanUploadTempPath($this->lpsal, $this->lpsal->Upload->Index);

            // lak
            CleanUploadTempPath($this->lak, $this->lak->Upload->Index);

            // laporan_pemeriksaan
            CleanUploadTempPath($this->laporan_pemeriksaan, $this->laporan_pemeriksaan->Upload->Index);
        }

        // Write JSON for API request
        if (IsApi() && $addRow) {
            $row = $this->getRecordsFromRecordset([$rsnew], true);
            WriteJson(["success" => true, $this->TableVar => $row]);
        }
        return $addRow;
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
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("pertanggungjawaban2022list"), "", $this->TableVar, true);
        $pageId = ($this->isCopy()) ? "Copy" : "Add";
        $Breadcrumb->add("add", $pageId, $url);
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
}
