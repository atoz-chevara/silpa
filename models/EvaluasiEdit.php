<?php

namespace PHPMaker2021\silpa;

use Doctrine\DBAL\ParameterType;

/**
 * Page class
 */
class EvaluasiEdit extends Evaluasi
{
    use MessagesTrait;

    // Page ID
    public $PageID = "edit";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'evaluasi';

    // Page object name
    public $PageObjName = "EvaluasiEdit";

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

        // Table object (evaluasi)
        if (!isset($GLOBALS["evaluasi"]) || get_class($GLOBALS["evaluasi"]) == PROJECT_NAMESPACE . "evaluasi") {
            $GLOBALS["evaluasi"] = &$this;
        }

        // Page URL
        $pageUrl = $this->pageUrl();

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
    public $FormClassName = "ew-horizontal ew-form ew-edit-form";
    public $IsModal = false;
    public $IsMobileOrModal = false;
    public $DbMasterFilter;
    public $DbDetailFilter;
    public $HashValue; // Hash Value
    public $DisplayRecords = 1;
    public $StartRecord;
    public $StopRecord;
    public $TotalRecords = 0;
    public $RecordRange = 10;
    public $RecordCount;

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
        $this->IsMobileOrModal = IsMobile() || $this->IsModal;
        $this->FormClassName = "ew-form ew-edit-form ew-horizontal";
        $loaded = false;
        $postBack = false;

        // Set up current action and primary key
        if (IsApi()) {
            // Load key values
            $loaded = true;
            if (($keyValue = Get("idd_evaluasi") ?? Key(0) ?? Route(2)) !== null) {
                $this->idd_evaluasi->setQueryStringValue($keyValue);
                $this->idd_evaluasi->setOldValue($this->idd_evaluasi->QueryStringValue);
            } elseif (Post("idd_evaluasi") !== null) {
                $this->idd_evaluasi->setFormValue(Post("idd_evaluasi"));
                $this->idd_evaluasi->setOldValue($this->idd_evaluasi->FormValue);
            } else {
                $loaded = false; // Unable to load key
            }

            // Load record
            if ($loaded) {
                $loaded = $this->loadRow();
            }
            if (!$loaded) {
                $this->setFailureMessage($Language->phrase("NoRecord")); // Set no record message
                $this->terminate();
                return;
            }
            $this->CurrentAction = "update"; // Update record directly
            $this->OldKey = $this->getKey(true); // Get from CurrentValue
            $postBack = true;
        } else {
            if (Post("action") !== null) {
                $this->CurrentAction = Post("action"); // Get action code
                if (!$this->isShow()) { // Not reload record, handle as postback
                    $postBack = true;
                }

                // Get key from Form
                $this->setKey(Post($this->OldKeyName), $this->isShow());
            } else {
                $this->CurrentAction = "show"; // Default action is display

                // Load key from QueryString
                $loadByQuery = false;
                if (($keyValue = Get("idd_evaluasi") ?? Route("idd_evaluasi")) !== null) {
                    $this->idd_evaluasi->setQueryStringValue($keyValue);
                    $loadByQuery = true;
                } else {
                    $this->idd_evaluasi->CurrentValue = null;
                }
            }

            // Load recordset
            if ($this->isShow()) {
                // Load current record
                $loaded = $this->loadRow();
                $this->OldKey = $loaded ? $this->getKey(true) : ""; // Get from CurrentValue
            }
        }

        // Process form if post back
        if ($postBack) {
            $this->loadFormValues(); // Get form values
        }

        // Validate form if post back
        if ($postBack) {
            if (!$this->validateForm()) {
                $this->EventCancelled = true; // Event cancelled
                $this->restoreFormValues();
                if (IsApi()) {
                    $this->terminate();
                    return;
                } else {
                    $this->CurrentAction = ""; // Form error, reset action
                }
            }
        }

        // Perform current action
        switch ($this->CurrentAction) {
            case "show": // Get a record to display
                if (!$loaded) { // Load record based on key
                    if ($this->getFailureMessage() == "") {
                        $this->setFailureMessage($Language->phrase("NoRecord")); // No record found
                    }
                    $this->terminate("evaluasilist"); // No matching record, return to list
                    return;
                }
                break;
            case "update": // Update
                $returnUrl = $this->getReturnUrl();
                if (GetPageName($returnUrl) == "evaluasilist") {
                    $returnUrl = $this->addMasterUrl($returnUrl); // List page, return to List page with correct master key if necessary
                }
                $this->SendEmail = true; // Send email on update success
                if ($this->editRow()) { // Update record based on key
                    if ($this->getSuccessMessage() == "") {
                        $this->setSuccessMessage($Language->phrase("UpdateSuccess")); // Update success
                    }
                    if (IsApi()) {
                        $this->terminate(true);
                        return;
                    } else {
                        $this->terminate($returnUrl); // Return to caller
                        return;
                    }
                } elseif (IsApi()) { // API request, return
                    $this->terminate();
                    return;
                } elseif ($this->getFailureMessage() == $Language->phrase("NoRecord")) {
                    $this->terminate($returnUrl); // Return to caller
                    return;
                } else {
                    $this->EventCancelled = true; // Event cancelled
                    $this->restoreFormValues(); // Restore form values if update failed
                }
        }

        // Set up Breadcrumb
        $this->setupBreadcrumb();

        // Render the record
        $this->RowType = ROWTYPE_EDIT; // Render as Edit
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
        $this->rpjmd->Upload->Index = $CurrentForm->Index;
        $this->rpjmd->Upload->uploadFile();
        $this->rpjmd->CurrentValue = $this->rpjmd->Upload->FileName;
        $this->rkpk->Upload->Index = $CurrentForm->Index;
        $this->rkpk->Upload->uploadFile();
        $this->rkpk->CurrentValue = $this->rkpk->Upload->FileName;
        $this->skd_rkuappas->Upload->Index = $CurrentForm->Index;
        $this->skd_rkuappas->Upload->uploadFile();
        $this->skd_rkuappas->CurrentValue = $this->skd_rkuappas->Upload->FileName;
        $this->kua->Upload->Index = $CurrentForm->Index;
        $this->kua->Upload->uploadFile();
        $this->kua->CurrentValue = $this->kua->Upload->FileName;
        $this->ppas->Upload->Index = $CurrentForm->Index;
        $this->ppas->Upload->uploadFile();
        $this->ppas->CurrentValue = $this->ppas->Upload->FileName;
        $this->skd_rqanun->Upload->Index = $CurrentForm->Index;
        $this->skd_rqanun->Upload->uploadFile();
        $this->skd_rqanun->CurrentValue = $this->skd_rqanun->Upload->FileName;
        $this->nota_keuangan->Upload->Index = $CurrentForm->Index;
        $this->nota_keuangan->Upload->uploadFile();
        $this->nota_keuangan->CurrentValue = $this->nota_keuangan->Upload->FileName;
        $this->pengantar_nota->Upload->Index = $CurrentForm->Index;
        $this->pengantar_nota->Upload->uploadFile();
        $this->pengantar_nota->CurrentValue = $this->pengantar_nota->Upload->FileName;
        $this->risalah_sidang->Upload->Index = $CurrentForm->Index;
        $this->risalah_sidang->Upload->uploadFile();
        $this->risalah_sidang->CurrentValue = $this->risalah_sidang->Upload->FileName;
        $this->bap_apbk->Upload->Index = $CurrentForm->Index;
        $this->bap_apbk->Upload->uploadFile();
        $this->bap_apbk->CurrentValue = $this->bap_apbk->Upload->FileName;
        $this->rq_apbk->Upload->Index = $CurrentForm->Index;
        $this->rq_apbk->Upload->uploadFile();
        $this->rq_apbk->CurrentValue = $this->rq_apbk->Upload->FileName;
        $this->rp_penjabaran->Upload->Index = $CurrentForm->Index;
        $this->rp_penjabaran->Upload->uploadFile();
        $this->rp_penjabaran->CurrentValue = $this->rp_penjabaran->Upload->FileName;
        $this->jadwal_proses->Upload->Index = $CurrentForm->Index;
        $this->jadwal_proses->Upload->uploadFile();
        $this->jadwal_proses->CurrentValue = $this->jadwal_proses->Upload->FileName;
        $this->sinkron_kebijakan->Upload->Index = $CurrentForm->Index;
        $this->sinkron_kebijakan->Upload->uploadFile();
        $this->sinkron_kebijakan->CurrentValue = $this->sinkron_kebijakan->Upload->FileName;
        $this->konsistensi_program->Upload->Index = $CurrentForm->Index;
        $this->konsistensi_program->Upload->uploadFile();
        $this->konsistensi_program->CurrentValue = $this->konsistensi_program->Upload->FileName;
        $this->alokasi_pendidikan->Upload->Index = $CurrentForm->Index;
        $this->alokasi_pendidikan->Upload->uploadFile();
        $this->alokasi_pendidikan->CurrentValue = $this->alokasi_pendidikan->Upload->FileName;
        $this->alokasi_kesehatan->Upload->Index = $CurrentForm->Index;
        $this->alokasi_kesehatan->Upload->uploadFile();
        $this->alokasi_kesehatan->CurrentValue = $this->alokasi_kesehatan->Upload->FileName;
        $this->alokasi_belanja->Upload->Index = $CurrentForm->Index;
        $this->alokasi_belanja->Upload->uploadFile();
        $this->alokasi_belanja->CurrentValue = $this->alokasi_belanja->Upload->FileName;
        $this->bak_kegiatan->Upload->Index = $CurrentForm->Index;
        $this->bak_kegiatan->Upload->uploadFile();
        $this->bak_kegiatan->CurrentValue = $this->bak_kegiatan->Upload->FileName;
        $this->softcopy_rka->Upload->Index = $CurrentForm->Index;
        $this->softcopy_rka->Upload->uploadFile();
        $this->softcopy_rka->CurrentValue = $this->softcopy_rka->Upload->FileName;
        $this->otsus->Upload->Index = $CurrentForm->Index;
        $this->otsus->Upload->uploadFile();
        $this->otsus->CurrentValue = $this->otsus->Upload->FileName;
        $this->qanun_perbup->Upload->Index = $CurrentForm->Index;
        $this->qanun_perbup->Upload->uploadFile();
        $this->qanun_perbup->CurrentValue = $this->qanun_perbup->Upload->FileName;
        $this->tindak_apbkp->Upload->Index = $CurrentForm->Index;
        $this->tindak_apbkp->Upload->uploadFile();
        $this->tindak_apbkp->CurrentValue = $this->tindak_apbkp->Upload->FileName;
    }

    // Load form values
    protected function loadFormValues()
    {
        // Load from form
        global $CurrentForm;

        // Check field name 'idd_evaluasi' first before field var 'x_idd_evaluasi'
        $val = $CurrentForm->hasValue("idd_evaluasi") ? $CurrentForm->getValue("idd_evaluasi") : $CurrentForm->getValue("x_idd_evaluasi");
        if (!$this->idd_evaluasi->IsDetailKey) {
            $this->idd_evaluasi->setFormValue($val);
        }

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

        // Check field name 'idd_wilayah' first before field var 'x_idd_wilayah'
        $val = $CurrentForm->hasValue("idd_wilayah") ? $CurrentForm->getValue("idd_wilayah") : $CurrentForm->getValue("x_idd_wilayah");
        if (!$this->idd_wilayah->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->idd_wilayah->Visible = false; // Disable update for API request
            } else {
                $this->idd_wilayah->setFormValue($val);
            }
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
        $this->getUploadFiles(); // Get upload files
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->idd_evaluasi->CurrentValue = $this->idd_evaluasi->FormValue;
        $this->tanggal->CurrentValue = $this->tanggal->FormValue;
        $this->tanggal->CurrentValue = UnFormatDateTime($this->tanggal->CurrentValue, 0);
        $this->idd_wilayah->CurrentValue = $this->idd_wilayah->FormValue;
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
            $res = $this->showOptionLink("edit");
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
        } elseif ($this->RowType == ROWTYPE_EDIT) {
            // idd_evaluasi
            $this->idd_evaluasi->EditAttrs["class"] = "form-control";
            $this->idd_evaluasi->EditCustomAttributes = "";
            $this->idd_evaluasi->EditValue = $this->idd_evaluasi->CurrentValue;
            $this->idd_evaluasi->ViewCustomAttributes = "";

            // tanggal
            $this->tanggal->EditAttrs["class"] = "form-control";
            $this->tanggal->EditCustomAttributes = "";
            $this->tanggal->EditValue = HtmlEncode(FormatDateTime($this->tanggal->CurrentValue, 8));
            $this->tanggal->PlaceHolder = RemoveHtml($this->tanggal->caption());

            // idd_wilayah
            $this->idd_wilayah->EditAttrs["class"] = "form-control";
            $this->idd_wilayah->EditCustomAttributes = "";
            $curVal = trim(strval($this->idd_wilayah->CurrentValue));
            if ($curVal != "") {
                $this->idd_wilayah->ViewValue = $this->idd_wilayah->lookupCacheOption($curVal);
            } else {
                $this->idd_wilayah->ViewValue = $this->idd_wilayah->Lookup !== null && is_array($this->idd_wilayah->Lookup->Options) ? $curVal : null;
            }
            if ($this->idd_wilayah->ViewValue !== null) { // Load from cache
                $this->idd_wilayah->EditValue = array_values($this->idd_wilayah->Lookup->Options);
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = "`idd_wilayah`" . SearchString("=", $this->idd_wilayah->CurrentValue, DATATYPE_NUMBER, "");
                }
                $sqlWrk = $this->idd_wilayah->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->idd_wilayah->EditValue = $arwrk;
            }
            $this->idd_wilayah->PlaceHolder = RemoveHtml($this->idd_wilayah->caption());

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
            $this->tahun_anggaran->EditValue = $this->tahun_anggaran->options(true);
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
            if ($this->isShow()) {
                RenderUploadField($this->surat_pengantar);
            }

            // rpjmd
            $this->rpjmd->EditAttrs["class"] = "form-control";
            $this->rpjmd->EditCustomAttributes = "";
            if (!EmptyValue($this->rpjmd->Upload->DbValue)) {
                $this->rpjmd->EditValue = $this->rpjmd->Upload->DbValue;
            } else {
                $this->rpjmd->EditValue = "";
            }
            if (!EmptyValue($this->rpjmd->CurrentValue)) {
                $this->rpjmd->Upload->FileName = $this->rpjmd->CurrentValue;
            }
            if ($this->isShow()) {
                RenderUploadField($this->rpjmd);
            }

            // rkpk
            $this->rkpk->EditAttrs["class"] = "form-control";
            $this->rkpk->EditCustomAttributes = "";
            if (!EmptyValue($this->rkpk->Upload->DbValue)) {
                $this->rkpk->EditValue = $this->rkpk->Upload->DbValue;
            } else {
                $this->rkpk->EditValue = "";
            }
            if (!EmptyValue($this->rkpk->CurrentValue)) {
                $this->rkpk->Upload->FileName = $this->rkpk->CurrentValue;
            }
            if ($this->isShow()) {
                RenderUploadField($this->rkpk);
            }

            // skd_rkuappas
            $this->skd_rkuappas->EditAttrs["class"] = "form-control";
            $this->skd_rkuappas->EditCustomAttributes = "";
            if (!EmptyValue($this->skd_rkuappas->Upload->DbValue)) {
                $this->skd_rkuappas->EditValue = $this->skd_rkuappas->Upload->DbValue;
            } else {
                $this->skd_rkuappas->EditValue = "";
            }
            if (!EmptyValue($this->skd_rkuappas->CurrentValue)) {
                $this->skd_rkuappas->Upload->FileName = $this->skd_rkuappas->CurrentValue;
            }
            if ($this->isShow()) {
                RenderUploadField($this->skd_rkuappas);
            }

            // kua
            $this->kua->EditAttrs["class"] = "form-control";
            $this->kua->EditCustomAttributes = "";
            if (!EmptyValue($this->kua->Upload->DbValue)) {
                $this->kua->EditValue = $this->kua->Upload->DbValue;
            } else {
                $this->kua->EditValue = "";
            }
            if (!EmptyValue($this->kua->CurrentValue)) {
                $this->kua->Upload->FileName = $this->kua->CurrentValue;
            }
            if ($this->isShow()) {
                RenderUploadField($this->kua);
            }

            // ppas
            $this->ppas->EditAttrs["class"] = "form-control";
            $this->ppas->EditCustomAttributes = "";
            if (!EmptyValue($this->ppas->Upload->DbValue)) {
                $this->ppas->EditValue = $this->ppas->Upload->DbValue;
            } else {
                $this->ppas->EditValue = "";
            }
            if (!EmptyValue($this->ppas->CurrentValue)) {
                $this->ppas->Upload->FileName = $this->ppas->CurrentValue;
            }
            if ($this->isShow()) {
                RenderUploadField($this->ppas);
            }

            // skd_rqanun
            $this->skd_rqanun->EditAttrs["class"] = "form-control";
            $this->skd_rqanun->EditCustomAttributes = "";
            if (!EmptyValue($this->skd_rqanun->Upload->DbValue)) {
                $this->skd_rqanun->EditValue = $this->skd_rqanun->Upload->DbValue;
            } else {
                $this->skd_rqanun->EditValue = "";
            }
            if (!EmptyValue($this->skd_rqanun->CurrentValue)) {
                $this->skd_rqanun->Upload->FileName = $this->skd_rqanun->CurrentValue;
            }
            if ($this->isShow()) {
                RenderUploadField($this->skd_rqanun);
            }

            // nota_keuangan
            $this->nota_keuangan->EditAttrs["class"] = "form-control";
            $this->nota_keuangan->EditCustomAttributes = "";
            if (!EmptyValue($this->nota_keuangan->Upload->DbValue)) {
                $this->nota_keuangan->EditValue = $this->nota_keuangan->Upload->DbValue;
            } else {
                $this->nota_keuangan->EditValue = "";
            }
            if (!EmptyValue($this->nota_keuangan->CurrentValue)) {
                $this->nota_keuangan->Upload->FileName = $this->nota_keuangan->CurrentValue;
            }
            if ($this->isShow()) {
                RenderUploadField($this->nota_keuangan);
            }

            // pengantar_nota
            $this->pengantar_nota->EditAttrs["class"] = "form-control";
            $this->pengantar_nota->EditCustomAttributes = "";
            if (!EmptyValue($this->pengantar_nota->Upload->DbValue)) {
                $this->pengantar_nota->EditValue = $this->pengantar_nota->Upload->DbValue;
            } else {
                $this->pengantar_nota->EditValue = "";
            }
            if (!EmptyValue($this->pengantar_nota->CurrentValue)) {
                $this->pengantar_nota->Upload->FileName = $this->pengantar_nota->CurrentValue;
            }
            if ($this->isShow()) {
                RenderUploadField($this->pengantar_nota);
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
            if ($this->isShow()) {
                RenderUploadField($this->risalah_sidang);
            }

            // bap_apbk
            $this->bap_apbk->EditAttrs["class"] = "form-control";
            $this->bap_apbk->EditCustomAttributes = "";
            if (!EmptyValue($this->bap_apbk->Upload->DbValue)) {
                $this->bap_apbk->EditValue = $this->bap_apbk->Upload->DbValue;
            } else {
                $this->bap_apbk->EditValue = "";
            }
            if (!EmptyValue($this->bap_apbk->CurrentValue)) {
                $this->bap_apbk->Upload->FileName = $this->bap_apbk->CurrentValue;
            }
            if ($this->isShow()) {
                RenderUploadField($this->bap_apbk);
            }

            // rq_apbk
            $this->rq_apbk->EditAttrs["class"] = "form-control";
            $this->rq_apbk->EditCustomAttributes = "";
            if (!EmptyValue($this->rq_apbk->Upload->DbValue)) {
                $this->rq_apbk->EditValue = $this->rq_apbk->Upload->DbValue;
            } else {
                $this->rq_apbk->EditValue = "";
            }
            if (!EmptyValue($this->rq_apbk->CurrentValue)) {
                $this->rq_apbk->Upload->FileName = $this->rq_apbk->CurrentValue;
            }
            if ($this->isShow()) {
                RenderUploadField($this->rq_apbk);
            }

            // rp_penjabaran
            $this->rp_penjabaran->EditAttrs["class"] = "form-control";
            $this->rp_penjabaran->EditCustomAttributes = "";
            if (!EmptyValue($this->rp_penjabaran->Upload->DbValue)) {
                $this->rp_penjabaran->EditValue = $this->rp_penjabaran->Upload->DbValue;
            } else {
                $this->rp_penjabaran->EditValue = "";
            }
            if (!EmptyValue($this->rp_penjabaran->CurrentValue)) {
                $this->rp_penjabaran->Upload->FileName = $this->rp_penjabaran->CurrentValue;
            }
            if ($this->isShow()) {
                RenderUploadField($this->rp_penjabaran);
            }

            // jadwal_proses
            $this->jadwal_proses->EditAttrs["class"] = "form-control";
            $this->jadwal_proses->EditCustomAttributes = "";
            if (!EmptyValue($this->jadwal_proses->Upload->DbValue)) {
                $this->jadwal_proses->EditValue = $this->jadwal_proses->Upload->DbValue;
            } else {
                $this->jadwal_proses->EditValue = "";
            }
            if (!EmptyValue($this->jadwal_proses->CurrentValue)) {
                $this->jadwal_proses->Upload->FileName = $this->jadwal_proses->CurrentValue;
            }
            if ($this->isShow()) {
                RenderUploadField($this->jadwal_proses);
            }

            // sinkron_kebijakan
            $this->sinkron_kebijakan->EditAttrs["class"] = "form-control";
            $this->sinkron_kebijakan->EditCustomAttributes = "";
            if (!EmptyValue($this->sinkron_kebijakan->Upload->DbValue)) {
                $this->sinkron_kebijakan->EditValue = $this->sinkron_kebijakan->Upload->DbValue;
            } else {
                $this->sinkron_kebijakan->EditValue = "";
            }
            if (!EmptyValue($this->sinkron_kebijakan->CurrentValue)) {
                $this->sinkron_kebijakan->Upload->FileName = $this->sinkron_kebijakan->CurrentValue;
            }
            if ($this->isShow()) {
                RenderUploadField($this->sinkron_kebijakan);
            }

            // konsistensi_program
            $this->konsistensi_program->EditAttrs["class"] = "form-control";
            $this->konsistensi_program->EditCustomAttributes = "";
            if (!EmptyValue($this->konsistensi_program->Upload->DbValue)) {
                $this->konsistensi_program->EditValue = $this->konsistensi_program->Upload->DbValue;
            } else {
                $this->konsistensi_program->EditValue = "";
            }
            if (!EmptyValue($this->konsistensi_program->CurrentValue)) {
                $this->konsistensi_program->Upload->FileName = $this->konsistensi_program->CurrentValue;
            }
            if ($this->isShow()) {
                RenderUploadField($this->konsistensi_program);
            }

            // alokasi_pendidikan
            $this->alokasi_pendidikan->EditAttrs["class"] = "form-control";
            $this->alokasi_pendidikan->EditCustomAttributes = "";
            if (!EmptyValue($this->alokasi_pendidikan->Upload->DbValue)) {
                $this->alokasi_pendidikan->EditValue = $this->alokasi_pendidikan->Upload->DbValue;
            } else {
                $this->alokasi_pendidikan->EditValue = "";
            }
            if (!EmptyValue($this->alokasi_pendidikan->CurrentValue)) {
                $this->alokasi_pendidikan->Upload->FileName = $this->alokasi_pendidikan->CurrentValue;
            }
            if ($this->isShow()) {
                RenderUploadField($this->alokasi_pendidikan);
            }

            // alokasi_kesehatan
            $this->alokasi_kesehatan->EditAttrs["class"] = "form-control";
            $this->alokasi_kesehatan->EditCustomAttributes = "";
            if (!EmptyValue($this->alokasi_kesehatan->Upload->DbValue)) {
                $this->alokasi_kesehatan->EditValue = $this->alokasi_kesehatan->Upload->DbValue;
            } else {
                $this->alokasi_kesehatan->EditValue = "";
            }
            if (!EmptyValue($this->alokasi_kesehatan->CurrentValue)) {
                $this->alokasi_kesehatan->Upload->FileName = $this->alokasi_kesehatan->CurrentValue;
            }
            if ($this->isShow()) {
                RenderUploadField($this->alokasi_kesehatan);
            }

            // alokasi_belanja
            $this->alokasi_belanja->EditAttrs["class"] = "form-control";
            $this->alokasi_belanja->EditCustomAttributes = "";
            if (!EmptyValue($this->alokasi_belanja->Upload->DbValue)) {
                $this->alokasi_belanja->EditValue = $this->alokasi_belanja->Upload->DbValue;
            } else {
                $this->alokasi_belanja->EditValue = "";
            }
            if (!EmptyValue($this->alokasi_belanja->CurrentValue)) {
                $this->alokasi_belanja->Upload->FileName = $this->alokasi_belanja->CurrentValue;
            }
            if ($this->isShow()) {
                RenderUploadField($this->alokasi_belanja);
            }

            // bak_kegiatan
            $this->bak_kegiatan->EditAttrs["class"] = "form-control";
            $this->bak_kegiatan->EditCustomAttributes = "";
            if (!EmptyValue($this->bak_kegiatan->Upload->DbValue)) {
                $this->bak_kegiatan->EditValue = $this->bak_kegiatan->Upload->DbValue;
            } else {
                $this->bak_kegiatan->EditValue = "";
            }
            if (!EmptyValue($this->bak_kegiatan->CurrentValue)) {
                $this->bak_kegiatan->Upload->FileName = $this->bak_kegiatan->CurrentValue;
            }
            if ($this->isShow()) {
                RenderUploadField($this->bak_kegiatan);
            }

            // softcopy_rka
            $this->softcopy_rka->EditAttrs["class"] = "form-control";
            $this->softcopy_rka->EditCustomAttributes = "";
            if (!EmptyValue($this->softcopy_rka->Upload->DbValue)) {
                $this->softcopy_rka->EditValue = $this->softcopy_rka->Upload->DbValue;
            } else {
                $this->softcopy_rka->EditValue = "";
            }
            if (!EmptyValue($this->softcopy_rka->CurrentValue)) {
                $this->softcopy_rka->Upload->FileName = $this->softcopy_rka->CurrentValue;
            }
            if ($this->isShow()) {
                RenderUploadField($this->softcopy_rka);
            }

            // otsus
            $this->otsus->EditAttrs["class"] = "form-control";
            $this->otsus->EditCustomAttributes = "";
            if (!EmptyValue($this->otsus->Upload->DbValue)) {
                $this->otsus->EditValue = $this->otsus->Upload->DbValue;
            } else {
                $this->otsus->EditValue = "";
            }
            if (!EmptyValue($this->otsus->CurrentValue)) {
                $this->otsus->Upload->FileName = $this->otsus->CurrentValue;
            }
            if ($this->isShow()) {
                RenderUploadField($this->otsus);
            }

            // qanun_perbup
            $this->qanun_perbup->EditAttrs["class"] = "form-control";
            $this->qanun_perbup->EditCustomAttributes = "";
            if (!EmptyValue($this->qanun_perbup->Upload->DbValue)) {
                $this->qanun_perbup->EditValue = $this->qanun_perbup->Upload->DbValue;
            } else {
                $this->qanun_perbup->EditValue = "";
            }
            if (!EmptyValue($this->qanun_perbup->CurrentValue)) {
                $this->qanun_perbup->Upload->FileName = $this->qanun_perbup->CurrentValue;
            }
            if ($this->isShow()) {
                RenderUploadField($this->qanun_perbup);
            }

            // tindak_apbkp
            $this->tindak_apbkp->EditAttrs["class"] = "form-control";
            $this->tindak_apbkp->EditCustomAttributes = "";
            if (!EmptyValue($this->tindak_apbkp->Upload->DbValue)) {
                $this->tindak_apbkp->EditValue = $this->tindak_apbkp->Upload->DbValue;
            } else {
                $this->tindak_apbkp->EditValue = "";
            }
            if (!EmptyValue($this->tindak_apbkp->CurrentValue)) {
                $this->tindak_apbkp->Upload->FileName = $this->tindak_apbkp->CurrentValue;
            }
            if ($this->isShow()) {
                RenderUploadField($this->tindak_apbkp);
            }

            // status
            $this->status->EditAttrs["class"] = "form-control";
            $this->status->EditCustomAttributes = "";
            $this->status->EditValue = $this->status->options(true);
            $this->status->PlaceHolder = RemoveHtml($this->status->caption());

            // idd_user
            $this->idd_user->EditAttrs["class"] = "form-control";
            $this->idd_user->EditCustomAttributes = "";
            if (!$Security->isAdmin() && $Security->isLoggedIn() && !$this->userIDAllow("edit")) { // Non system admin
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

            // Edit refer script

            // idd_evaluasi
            $this->idd_evaluasi->LinkCustomAttributes = "";
            $this->idd_evaluasi->HrefValue = "";

            // tanggal
            $this->tanggal->LinkCustomAttributes = "";
            $this->tanggal->HrefValue = "";

            // idd_wilayah
            $this->idd_wilayah->LinkCustomAttributes = "";
            $this->idd_wilayah->HrefValue = "";

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

            // rpjmd
            $this->rpjmd->LinkCustomAttributes = "";
            $this->rpjmd->HrefValue = "";
            $this->rpjmd->ExportHrefValue = $this->rpjmd->UploadPath . $this->rpjmd->Upload->DbValue;

            // rkpk
            $this->rkpk->LinkCustomAttributes = "";
            $this->rkpk->HrefValue = "";
            $this->rkpk->ExportHrefValue = $this->rkpk->UploadPath . $this->rkpk->Upload->DbValue;

            // skd_rkuappas
            $this->skd_rkuappas->LinkCustomAttributes = "";
            $this->skd_rkuappas->HrefValue = "";
            $this->skd_rkuappas->ExportHrefValue = $this->skd_rkuappas->UploadPath . $this->skd_rkuappas->Upload->DbValue;

            // kua
            $this->kua->LinkCustomAttributes = "";
            $this->kua->HrefValue = "";
            $this->kua->ExportHrefValue = $this->kua->UploadPath . $this->kua->Upload->DbValue;

            // ppas
            $this->ppas->LinkCustomAttributes = "";
            $this->ppas->HrefValue = "";
            $this->ppas->ExportHrefValue = $this->ppas->UploadPath . $this->ppas->Upload->DbValue;

            // skd_rqanun
            $this->skd_rqanun->LinkCustomAttributes = "";
            $this->skd_rqanun->HrefValue = "";
            $this->skd_rqanun->ExportHrefValue = $this->skd_rqanun->UploadPath . $this->skd_rqanun->Upload->DbValue;

            // nota_keuangan
            $this->nota_keuangan->LinkCustomAttributes = "";
            $this->nota_keuangan->HrefValue = "";
            $this->nota_keuangan->ExportHrefValue = $this->nota_keuangan->UploadPath . $this->nota_keuangan->Upload->DbValue;

            // pengantar_nota
            $this->pengantar_nota->LinkCustomAttributes = "";
            $this->pengantar_nota->HrefValue = "";
            $this->pengantar_nota->ExportHrefValue = $this->pengantar_nota->UploadPath . $this->pengantar_nota->Upload->DbValue;

            // risalah_sidang
            $this->risalah_sidang->LinkCustomAttributes = "";
            $this->risalah_sidang->HrefValue = "";
            $this->risalah_sidang->ExportHrefValue = $this->risalah_sidang->UploadPath . $this->risalah_sidang->Upload->DbValue;

            // bap_apbk
            $this->bap_apbk->LinkCustomAttributes = "";
            $this->bap_apbk->HrefValue = "";
            $this->bap_apbk->ExportHrefValue = $this->bap_apbk->UploadPath . $this->bap_apbk->Upload->DbValue;

            // rq_apbk
            $this->rq_apbk->LinkCustomAttributes = "";
            $this->rq_apbk->HrefValue = "";
            $this->rq_apbk->ExportHrefValue = $this->rq_apbk->UploadPath . $this->rq_apbk->Upload->DbValue;

            // rp_penjabaran
            $this->rp_penjabaran->LinkCustomAttributes = "";
            $this->rp_penjabaran->HrefValue = "";
            $this->rp_penjabaran->ExportHrefValue = $this->rp_penjabaran->UploadPath . $this->rp_penjabaran->Upload->DbValue;

            // jadwal_proses
            $this->jadwal_proses->LinkCustomAttributes = "";
            $this->jadwal_proses->HrefValue = "";
            $this->jadwal_proses->ExportHrefValue = $this->jadwal_proses->UploadPath . $this->jadwal_proses->Upload->DbValue;

            // sinkron_kebijakan
            $this->sinkron_kebijakan->LinkCustomAttributes = "";
            $this->sinkron_kebijakan->HrefValue = "";
            $this->sinkron_kebijakan->ExportHrefValue = $this->sinkron_kebijakan->UploadPath . $this->sinkron_kebijakan->Upload->DbValue;

            // konsistensi_program
            $this->konsistensi_program->LinkCustomAttributes = "";
            $this->konsistensi_program->HrefValue = "";
            $this->konsistensi_program->ExportHrefValue = $this->konsistensi_program->UploadPath . $this->konsistensi_program->Upload->DbValue;

            // alokasi_pendidikan
            $this->alokasi_pendidikan->LinkCustomAttributes = "";
            $this->alokasi_pendidikan->HrefValue = "";
            $this->alokasi_pendidikan->ExportHrefValue = $this->alokasi_pendidikan->UploadPath . $this->alokasi_pendidikan->Upload->DbValue;

            // alokasi_kesehatan
            $this->alokasi_kesehatan->LinkCustomAttributes = "";
            $this->alokasi_kesehatan->HrefValue = "";
            $this->alokasi_kesehatan->ExportHrefValue = $this->alokasi_kesehatan->UploadPath . $this->alokasi_kesehatan->Upload->DbValue;

            // alokasi_belanja
            $this->alokasi_belanja->LinkCustomAttributes = "";
            $this->alokasi_belanja->HrefValue = "";
            $this->alokasi_belanja->ExportHrefValue = $this->alokasi_belanja->UploadPath . $this->alokasi_belanja->Upload->DbValue;

            // bak_kegiatan
            $this->bak_kegiatan->LinkCustomAttributes = "";
            $this->bak_kegiatan->HrefValue = "";
            $this->bak_kegiatan->ExportHrefValue = $this->bak_kegiatan->UploadPath . $this->bak_kegiatan->Upload->DbValue;

            // softcopy_rka
            $this->softcopy_rka->LinkCustomAttributes = "";
            $this->softcopy_rka->HrefValue = "";
            $this->softcopy_rka->ExportHrefValue = $this->softcopy_rka->UploadPath . $this->softcopy_rka->Upload->DbValue;

            // otsus
            $this->otsus->LinkCustomAttributes = "";
            $this->otsus->HrefValue = "";
            $this->otsus->ExportHrefValue = $this->otsus->UploadPath . $this->otsus->Upload->DbValue;

            // qanun_perbup
            $this->qanun_perbup->LinkCustomAttributes = "";
            $this->qanun_perbup->HrefValue = "";
            $this->qanun_perbup->ExportHrefValue = $this->qanun_perbup->UploadPath . $this->qanun_perbup->Upload->DbValue;

            // tindak_apbkp
            $this->tindak_apbkp->LinkCustomAttributes = "";
            $this->tindak_apbkp->HrefValue = "";
            $this->tindak_apbkp->ExportHrefValue = $this->tindak_apbkp->UploadPath . $this->tindak_apbkp->Upload->DbValue;

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
        if ($this->idd_evaluasi->Required) {
            if (!$this->idd_evaluasi->IsDetailKey && EmptyValue($this->idd_evaluasi->FormValue)) {
                $this->idd_evaluasi->addErrorMessage(str_replace("%s", $this->idd_evaluasi->caption(), $this->idd_evaluasi->RequiredErrorMessage));
            }
        }
        if ($this->tanggal->Required) {
            if (!$this->tanggal->IsDetailKey && EmptyValue($this->tanggal->FormValue)) {
                $this->tanggal->addErrorMessage(str_replace("%s", $this->tanggal->caption(), $this->tanggal->RequiredErrorMessage));
            }
        }
        if (!CheckDate($this->tanggal->FormValue)) {
            $this->tanggal->addErrorMessage($this->tanggal->getErrorMessage(false));
        }
        if ($this->idd_wilayah->Required) {
            if (!$this->idd_wilayah->IsDetailKey && EmptyValue($this->idd_wilayah->FormValue)) {
                $this->idd_wilayah->addErrorMessage(str_replace("%s", $this->idd_wilayah->caption(), $this->idd_wilayah->RequiredErrorMessage));
            }
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
        if ($this->rpjmd->Required) {
            if ($this->rpjmd->Upload->FileName == "" && !$this->rpjmd->Upload->KeepFile) {
                $this->rpjmd->addErrorMessage(str_replace("%s", $this->rpjmd->caption(), $this->rpjmd->RequiredErrorMessage));
            }
        }
        if ($this->rkpk->Required) {
            if ($this->rkpk->Upload->FileName == "" && !$this->rkpk->Upload->KeepFile) {
                $this->rkpk->addErrorMessage(str_replace("%s", $this->rkpk->caption(), $this->rkpk->RequiredErrorMessage));
            }
        }
        if ($this->skd_rkuappas->Required) {
            if ($this->skd_rkuappas->Upload->FileName == "" && !$this->skd_rkuappas->Upload->KeepFile) {
                $this->skd_rkuappas->addErrorMessage(str_replace("%s", $this->skd_rkuappas->caption(), $this->skd_rkuappas->RequiredErrorMessage));
            }
        }
        if ($this->kua->Required) {
            if ($this->kua->Upload->FileName == "" && !$this->kua->Upload->KeepFile) {
                $this->kua->addErrorMessage(str_replace("%s", $this->kua->caption(), $this->kua->RequiredErrorMessage));
            }
        }
        if ($this->ppas->Required) {
            if ($this->ppas->Upload->FileName == "" && !$this->ppas->Upload->KeepFile) {
                $this->ppas->addErrorMessage(str_replace("%s", $this->ppas->caption(), $this->ppas->RequiredErrorMessage));
            }
        }
        if ($this->skd_rqanun->Required) {
            if ($this->skd_rqanun->Upload->FileName == "" && !$this->skd_rqanun->Upload->KeepFile) {
                $this->skd_rqanun->addErrorMessage(str_replace("%s", $this->skd_rqanun->caption(), $this->skd_rqanun->RequiredErrorMessage));
            }
        }
        if ($this->nota_keuangan->Required) {
            if ($this->nota_keuangan->Upload->FileName == "" && !$this->nota_keuangan->Upload->KeepFile) {
                $this->nota_keuangan->addErrorMessage(str_replace("%s", $this->nota_keuangan->caption(), $this->nota_keuangan->RequiredErrorMessage));
            }
        }
        if ($this->pengantar_nota->Required) {
            if ($this->pengantar_nota->Upload->FileName == "" && !$this->pengantar_nota->Upload->KeepFile) {
                $this->pengantar_nota->addErrorMessage(str_replace("%s", $this->pengantar_nota->caption(), $this->pengantar_nota->RequiredErrorMessage));
            }
        }
        if ($this->risalah_sidang->Required) {
            if ($this->risalah_sidang->Upload->FileName == "" && !$this->risalah_sidang->Upload->KeepFile) {
                $this->risalah_sidang->addErrorMessage(str_replace("%s", $this->risalah_sidang->caption(), $this->risalah_sidang->RequiredErrorMessage));
            }
        }
        if ($this->bap_apbk->Required) {
            if ($this->bap_apbk->Upload->FileName == "" && !$this->bap_apbk->Upload->KeepFile) {
                $this->bap_apbk->addErrorMessage(str_replace("%s", $this->bap_apbk->caption(), $this->bap_apbk->RequiredErrorMessage));
            }
        }
        if ($this->rq_apbk->Required) {
            if ($this->rq_apbk->Upload->FileName == "" && !$this->rq_apbk->Upload->KeepFile) {
                $this->rq_apbk->addErrorMessage(str_replace("%s", $this->rq_apbk->caption(), $this->rq_apbk->RequiredErrorMessage));
            }
        }
        if ($this->rp_penjabaran->Required) {
            if ($this->rp_penjabaran->Upload->FileName == "" && !$this->rp_penjabaran->Upload->KeepFile) {
                $this->rp_penjabaran->addErrorMessage(str_replace("%s", $this->rp_penjabaran->caption(), $this->rp_penjabaran->RequiredErrorMessage));
            }
        }
        if ($this->jadwal_proses->Required) {
            if ($this->jadwal_proses->Upload->FileName == "" && !$this->jadwal_proses->Upload->KeepFile) {
                $this->jadwal_proses->addErrorMessage(str_replace("%s", $this->jadwal_proses->caption(), $this->jadwal_proses->RequiredErrorMessage));
            }
        }
        if ($this->sinkron_kebijakan->Required) {
            if ($this->sinkron_kebijakan->Upload->FileName == "" && !$this->sinkron_kebijakan->Upload->KeepFile) {
                $this->sinkron_kebijakan->addErrorMessage(str_replace("%s", $this->sinkron_kebijakan->caption(), $this->sinkron_kebijakan->RequiredErrorMessage));
            }
        }
        if ($this->konsistensi_program->Required) {
            if ($this->konsistensi_program->Upload->FileName == "" && !$this->konsistensi_program->Upload->KeepFile) {
                $this->konsistensi_program->addErrorMessage(str_replace("%s", $this->konsistensi_program->caption(), $this->konsistensi_program->RequiredErrorMessage));
            }
        }
        if ($this->alokasi_pendidikan->Required) {
            if ($this->alokasi_pendidikan->Upload->FileName == "" && !$this->alokasi_pendidikan->Upload->KeepFile) {
                $this->alokasi_pendidikan->addErrorMessage(str_replace("%s", $this->alokasi_pendidikan->caption(), $this->alokasi_pendidikan->RequiredErrorMessage));
            }
        }
        if ($this->alokasi_kesehatan->Required) {
            if ($this->alokasi_kesehatan->Upload->FileName == "" && !$this->alokasi_kesehatan->Upload->KeepFile) {
                $this->alokasi_kesehatan->addErrorMessage(str_replace("%s", $this->alokasi_kesehatan->caption(), $this->alokasi_kesehatan->RequiredErrorMessage));
            }
        }
        if ($this->alokasi_belanja->Required) {
            if ($this->alokasi_belanja->Upload->FileName == "" && !$this->alokasi_belanja->Upload->KeepFile) {
                $this->alokasi_belanja->addErrorMessage(str_replace("%s", $this->alokasi_belanja->caption(), $this->alokasi_belanja->RequiredErrorMessage));
            }
        }
        if ($this->bak_kegiatan->Required) {
            if ($this->bak_kegiatan->Upload->FileName == "" && !$this->bak_kegiatan->Upload->KeepFile) {
                $this->bak_kegiatan->addErrorMessage(str_replace("%s", $this->bak_kegiatan->caption(), $this->bak_kegiatan->RequiredErrorMessage));
            }
        }
        if ($this->softcopy_rka->Required) {
            if ($this->softcopy_rka->Upload->FileName == "" && !$this->softcopy_rka->Upload->KeepFile) {
                $this->softcopy_rka->addErrorMessage(str_replace("%s", $this->softcopy_rka->caption(), $this->softcopy_rka->RequiredErrorMessage));
            }
        }
        if ($this->otsus->Required) {
            if ($this->otsus->Upload->FileName == "" && !$this->otsus->Upload->KeepFile) {
                $this->otsus->addErrorMessage(str_replace("%s", $this->otsus->caption(), $this->otsus->RequiredErrorMessage));
            }
        }
        if ($this->qanun_perbup->Required) {
            if ($this->qanun_perbup->Upload->FileName == "" && !$this->qanun_perbup->Upload->KeepFile) {
                $this->qanun_perbup->addErrorMessage(str_replace("%s", $this->qanun_perbup->caption(), $this->qanun_perbup->RequiredErrorMessage));
            }
        }
        if ($this->tindak_apbkp->Required) {
            if ($this->tindak_apbkp->Upload->FileName == "" && !$this->tindak_apbkp->Upload->KeepFile) {
                $this->tindak_apbkp->addErrorMessage(str_replace("%s", $this->tindak_apbkp->caption(), $this->tindak_apbkp->RequiredErrorMessage));
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
            $rsnew = [];

            // tanggal
            $this->tanggal->setDbValueDef($rsnew, UnFormatDateTime($this->tanggal->CurrentValue, 0), CurrentDate(), $this->tanggal->ReadOnly);

            // idd_wilayah
            $this->idd_wilayah->setDbValueDef($rsnew, $this->idd_wilayah->CurrentValue, 0, $this->idd_wilayah->ReadOnly);

            // kd_satker
            $this->kd_satker->setDbValueDef($rsnew, $this->kd_satker->CurrentValue, "", $this->kd_satker->ReadOnly);

            // idd_tahapan
            $this->idd_tahapan->setDbValueDef($rsnew, $this->idd_tahapan->CurrentValue, 0, $this->idd_tahapan->ReadOnly);

            // tahun_anggaran
            $this->tahun_anggaran->setDbValueDef($rsnew, $this->tahun_anggaran->CurrentValue, "", $this->tahun_anggaran->ReadOnly);

            // surat_pengantar
            if ($this->surat_pengantar->Visible && !$this->surat_pengantar->ReadOnly && !$this->surat_pengantar->Upload->KeepFile) {
                $this->surat_pengantar->Upload->DbValue = $rsold['surat_pengantar']; // Get original value
                if ($this->surat_pengantar->Upload->FileName == "") {
                    $rsnew['surat_pengantar'] = null;
                } else {
                    $rsnew['surat_pengantar'] = $this->surat_pengantar->Upload->FileName;
                }
            }

            // rpjmd
            if ($this->rpjmd->Visible && !$this->rpjmd->ReadOnly && !$this->rpjmd->Upload->KeepFile) {
                $this->rpjmd->Upload->DbValue = $rsold['rpjmd']; // Get original value
                if ($this->rpjmd->Upload->FileName == "") {
                    $rsnew['rpjmd'] = null;
                } else {
                    $rsnew['rpjmd'] = $this->rpjmd->Upload->FileName;
                }
            }

            // rkpk
            if ($this->rkpk->Visible && !$this->rkpk->ReadOnly && !$this->rkpk->Upload->KeepFile) {
                $this->rkpk->Upload->DbValue = $rsold['rkpk']; // Get original value
                if ($this->rkpk->Upload->FileName == "") {
                    $rsnew['rkpk'] = null;
                } else {
                    $rsnew['rkpk'] = $this->rkpk->Upload->FileName;
                }
            }

            // skd_rkuappas
            if ($this->skd_rkuappas->Visible && !$this->skd_rkuappas->ReadOnly && !$this->skd_rkuappas->Upload->KeepFile) {
                $this->skd_rkuappas->Upload->DbValue = $rsold['skd_rkuappas']; // Get original value
                if ($this->skd_rkuappas->Upload->FileName == "") {
                    $rsnew['skd_rkuappas'] = null;
                } else {
                    $rsnew['skd_rkuappas'] = $this->skd_rkuappas->Upload->FileName;
                }
            }

            // kua
            if ($this->kua->Visible && !$this->kua->ReadOnly && !$this->kua->Upload->KeepFile) {
                $this->kua->Upload->DbValue = $rsold['kua']; // Get original value
                if ($this->kua->Upload->FileName == "") {
                    $rsnew['kua'] = null;
                } else {
                    $rsnew['kua'] = $this->kua->Upload->FileName;
                }
            }

            // ppas
            if ($this->ppas->Visible && !$this->ppas->ReadOnly && !$this->ppas->Upload->KeepFile) {
                $this->ppas->Upload->DbValue = $rsold['ppas']; // Get original value
                if ($this->ppas->Upload->FileName == "") {
                    $rsnew['ppas'] = null;
                } else {
                    $rsnew['ppas'] = $this->ppas->Upload->FileName;
                }
            }

            // skd_rqanun
            if ($this->skd_rqanun->Visible && !$this->skd_rqanun->ReadOnly && !$this->skd_rqanun->Upload->KeepFile) {
                $this->skd_rqanun->Upload->DbValue = $rsold['skd_rqanun']; // Get original value
                if ($this->skd_rqanun->Upload->FileName == "") {
                    $rsnew['skd_rqanun'] = null;
                } else {
                    $rsnew['skd_rqanun'] = $this->skd_rqanun->Upload->FileName;
                }
            }

            // nota_keuangan
            if ($this->nota_keuangan->Visible && !$this->nota_keuangan->ReadOnly && !$this->nota_keuangan->Upload->KeepFile) {
                $this->nota_keuangan->Upload->DbValue = $rsold['nota_keuangan']; // Get original value
                if ($this->nota_keuangan->Upload->FileName == "") {
                    $rsnew['nota_keuangan'] = null;
                } else {
                    $rsnew['nota_keuangan'] = $this->nota_keuangan->Upload->FileName;
                }
            }

            // pengantar_nota
            if ($this->pengantar_nota->Visible && !$this->pengantar_nota->ReadOnly && !$this->pengantar_nota->Upload->KeepFile) {
                $this->pengantar_nota->Upload->DbValue = $rsold['pengantar_nota']; // Get original value
                if ($this->pengantar_nota->Upload->FileName == "") {
                    $rsnew['pengantar_nota'] = null;
                } else {
                    $rsnew['pengantar_nota'] = $this->pengantar_nota->Upload->FileName;
                }
            }

            // risalah_sidang
            if ($this->risalah_sidang->Visible && !$this->risalah_sidang->ReadOnly && !$this->risalah_sidang->Upload->KeepFile) {
                $this->risalah_sidang->Upload->DbValue = $rsold['risalah_sidang']; // Get original value
                if ($this->risalah_sidang->Upload->FileName == "") {
                    $rsnew['risalah_sidang'] = null;
                } else {
                    $rsnew['risalah_sidang'] = $this->risalah_sidang->Upload->FileName;
                }
            }

            // bap_apbk
            if ($this->bap_apbk->Visible && !$this->bap_apbk->ReadOnly && !$this->bap_apbk->Upload->KeepFile) {
                $this->bap_apbk->Upload->DbValue = $rsold['bap_apbk']; // Get original value
                if ($this->bap_apbk->Upload->FileName == "") {
                    $rsnew['bap_apbk'] = null;
                } else {
                    $rsnew['bap_apbk'] = $this->bap_apbk->Upload->FileName;
                }
            }

            // rq_apbk
            if ($this->rq_apbk->Visible && !$this->rq_apbk->ReadOnly && !$this->rq_apbk->Upload->KeepFile) {
                $this->rq_apbk->Upload->DbValue = $rsold['rq_apbk']; // Get original value
                if ($this->rq_apbk->Upload->FileName == "") {
                    $rsnew['rq_apbk'] = null;
                } else {
                    $rsnew['rq_apbk'] = $this->rq_apbk->Upload->FileName;
                }
            }

            // rp_penjabaran
            if ($this->rp_penjabaran->Visible && !$this->rp_penjabaran->ReadOnly && !$this->rp_penjabaran->Upload->KeepFile) {
                $this->rp_penjabaran->Upload->DbValue = $rsold['rp_penjabaran']; // Get original value
                if ($this->rp_penjabaran->Upload->FileName == "") {
                    $rsnew['rp_penjabaran'] = null;
                } else {
                    $rsnew['rp_penjabaran'] = $this->rp_penjabaran->Upload->FileName;
                }
            }

            // jadwal_proses
            if ($this->jadwal_proses->Visible && !$this->jadwal_proses->ReadOnly && !$this->jadwal_proses->Upload->KeepFile) {
                $this->jadwal_proses->Upload->DbValue = $rsold['jadwal_proses']; // Get original value
                if ($this->jadwal_proses->Upload->FileName == "") {
                    $rsnew['jadwal_proses'] = null;
                } else {
                    $rsnew['jadwal_proses'] = $this->jadwal_proses->Upload->FileName;
                }
            }

            // sinkron_kebijakan
            if ($this->sinkron_kebijakan->Visible && !$this->sinkron_kebijakan->ReadOnly && !$this->sinkron_kebijakan->Upload->KeepFile) {
                $this->sinkron_kebijakan->Upload->DbValue = $rsold['sinkron_kebijakan']; // Get original value
                if ($this->sinkron_kebijakan->Upload->FileName == "") {
                    $rsnew['sinkron_kebijakan'] = null;
                } else {
                    $rsnew['sinkron_kebijakan'] = $this->sinkron_kebijakan->Upload->FileName;
                }
            }

            // konsistensi_program
            if ($this->konsistensi_program->Visible && !$this->konsistensi_program->ReadOnly && !$this->konsistensi_program->Upload->KeepFile) {
                $this->konsistensi_program->Upload->DbValue = $rsold['konsistensi_program']; // Get original value
                if ($this->konsistensi_program->Upload->FileName == "") {
                    $rsnew['konsistensi_program'] = null;
                } else {
                    $rsnew['konsistensi_program'] = $this->konsistensi_program->Upload->FileName;
                }
            }

            // alokasi_pendidikan
            if ($this->alokasi_pendidikan->Visible && !$this->alokasi_pendidikan->ReadOnly && !$this->alokasi_pendidikan->Upload->KeepFile) {
                $this->alokasi_pendidikan->Upload->DbValue = $rsold['alokasi_pendidikan']; // Get original value
                if ($this->alokasi_pendidikan->Upload->FileName == "") {
                    $rsnew['alokasi_pendidikan'] = null;
                } else {
                    $rsnew['alokasi_pendidikan'] = $this->alokasi_pendidikan->Upload->FileName;
                }
            }

            // alokasi_kesehatan
            if ($this->alokasi_kesehatan->Visible && !$this->alokasi_kesehatan->ReadOnly && !$this->alokasi_kesehatan->Upload->KeepFile) {
                $this->alokasi_kesehatan->Upload->DbValue = $rsold['alokasi_kesehatan']; // Get original value
                if ($this->alokasi_kesehatan->Upload->FileName == "") {
                    $rsnew['alokasi_kesehatan'] = null;
                } else {
                    $rsnew['alokasi_kesehatan'] = $this->alokasi_kesehatan->Upload->FileName;
                }
            }

            // alokasi_belanja
            if ($this->alokasi_belanja->Visible && !$this->alokasi_belanja->ReadOnly && !$this->alokasi_belanja->Upload->KeepFile) {
                $this->alokasi_belanja->Upload->DbValue = $rsold['alokasi_belanja']; // Get original value
                if ($this->alokasi_belanja->Upload->FileName == "") {
                    $rsnew['alokasi_belanja'] = null;
                } else {
                    $rsnew['alokasi_belanja'] = $this->alokasi_belanja->Upload->FileName;
                }
            }

            // bak_kegiatan
            if ($this->bak_kegiatan->Visible && !$this->bak_kegiatan->ReadOnly && !$this->bak_kegiatan->Upload->KeepFile) {
                $this->bak_kegiatan->Upload->DbValue = $rsold['bak_kegiatan']; // Get original value
                if ($this->bak_kegiatan->Upload->FileName == "") {
                    $rsnew['bak_kegiatan'] = null;
                } else {
                    $rsnew['bak_kegiatan'] = $this->bak_kegiatan->Upload->FileName;
                }
            }

            // softcopy_rka
            if ($this->softcopy_rka->Visible && !$this->softcopy_rka->ReadOnly && !$this->softcopy_rka->Upload->KeepFile) {
                $this->softcopy_rka->Upload->DbValue = $rsold['softcopy_rka']; // Get original value
                if ($this->softcopy_rka->Upload->FileName == "") {
                    $rsnew['softcopy_rka'] = null;
                } else {
                    $rsnew['softcopy_rka'] = $this->softcopy_rka->Upload->FileName;
                }
            }

            // otsus
            if ($this->otsus->Visible && !$this->otsus->ReadOnly && !$this->otsus->Upload->KeepFile) {
                $this->otsus->Upload->DbValue = $rsold['otsus']; // Get original value
                if ($this->otsus->Upload->FileName == "") {
                    $rsnew['otsus'] = null;
                } else {
                    $rsnew['otsus'] = $this->otsus->Upload->FileName;
                }
            }

            // qanun_perbup
            if ($this->qanun_perbup->Visible && !$this->qanun_perbup->ReadOnly && !$this->qanun_perbup->Upload->KeepFile) {
                $this->qanun_perbup->Upload->DbValue = $rsold['qanun_perbup']; // Get original value
                if ($this->qanun_perbup->Upload->FileName == "") {
                    $rsnew['qanun_perbup'] = null;
                } else {
                    $rsnew['qanun_perbup'] = $this->qanun_perbup->Upload->FileName;
                }
            }

            // tindak_apbkp
            if ($this->tindak_apbkp->Visible && !$this->tindak_apbkp->ReadOnly && !$this->tindak_apbkp->Upload->KeepFile) {
                $this->tindak_apbkp->Upload->DbValue = $rsold['tindak_apbkp']; // Get original value
                if ($this->tindak_apbkp->Upload->FileName == "") {
                    $rsnew['tindak_apbkp'] = null;
                } else {
                    $rsnew['tindak_apbkp'] = $this->tindak_apbkp->Upload->FileName;
                }
            }

            // status
            $this->status->setDbValueDef($rsnew, $this->status->CurrentValue, 0, $this->status->ReadOnly);

            // idd_user
            $this->idd_user->setDbValueDef($rsnew, $this->idd_user->CurrentValue, 0, $this->idd_user->ReadOnly);
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
                    $this->surat_pengantar->setDbValueDef($rsnew, $this->surat_pengantar->Upload->FileName, "", $this->surat_pengantar->ReadOnly);
                }
            }
            if ($this->rpjmd->Visible && !$this->rpjmd->Upload->KeepFile) {
                $oldFiles = EmptyValue($this->rpjmd->Upload->DbValue) ? [] : [$this->rpjmd->htmlDecode($this->rpjmd->Upload->DbValue)];
                if (!EmptyValue($this->rpjmd->Upload->FileName)) {
                    $newFiles = [$this->rpjmd->Upload->FileName];
                    $NewFileCount = count($newFiles);
                    for ($i = 0; $i < $NewFileCount; $i++) {
                        if ($newFiles[$i] != "") {
                            $file = $newFiles[$i];
                            $tempPath = UploadTempPath($this->rpjmd, $this->rpjmd->Upload->Index);
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
                                $file1 = UniqueFilename($this->rpjmd->physicalUploadPath(), $file); // Get new file name
                                if ($file1 != $file) { // Rename temp file
                                    while (file_exists($tempPath . $file1) || file_exists($this->rpjmd->physicalUploadPath() . $file1)) { // Make sure no file name clash
                                        $file1 = UniqueFilename([$this->rpjmd->physicalUploadPath(), $tempPath], $file1, true); // Use indexed name
                                    }
                                    rename($tempPath . $file, $tempPath . $file1);
                                    $newFiles[$i] = $file1;
                                }
                            }
                        }
                    }
                    $this->rpjmd->Upload->DbValue = empty($oldFiles) ? "" : implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $oldFiles);
                    $this->rpjmd->Upload->FileName = implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $newFiles);
                    $this->rpjmd->setDbValueDef($rsnew, $this->rpjmd->Upload->FileName, "", $this->rpjmd->ReadOnly);
                }
            }
            if ($this->rkpk->Visible && !$this->rkpk->Upload->KeepFile) {
                $oldFiles = EmptyValue($this->rkpk->Upload->DbValue) ? [] : [$this->rkpk->htmlDecode($this->rkpk->Upload->DbValue)];
                if (!EmptyValue($this->rkpk->Upload->FileName)) {
                    $newFiles = [$this->rkpk->Upload->FileName];
                    $NewFileCount = count($newFiles);
                    for ($i = 0; $i < $NewFileCount; $i++) {
                        if ($newFiles[$i] != "") {
                            $file = $newFiles[$i];
                            $tempPath = UploadTempPath($this->rkpk, $this->rkpk->Upload->Index);
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
                                $file1 = UniqueFilename($this->rkpk->physicalUploadPath(), $file); // Get new file name
                                if ($file1 != $file) { // Rename temp file
                                    while (file_exists($tempPath . $file1) || file_exists($this->rkpk->physicalUploadPath() . $file1)) { // Make sure no file name clash
                                        $file1 = UniqueFilename([$this->rkpk->physicalUploadPath(), $tempPath], $file1, true); // Use indexed name
                                    }
                                    rename($tempPath . $file, $tempPath . $file1);
                                    $newFiles[$i] = $file1;
                                }
                            }
                        }
                    }
                    $this->rkpk->Upload->DbValue = empty($oldFiles) ? "" : implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $oldFiles);
                    $this->rkpk->Upload->FileName = implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $newFiles);
                    $this->rkpk->setDbValueDef($rsnew, $this->rkpk->Upload->FileName, "", $this->rkpk->ReadOnly);
                }
            }
            if ($this->skd_rkuappas->Visible && !$this->skd_rkuappas->Upload->KeepFile) {
                $oldFiles = EmptyValue($this->skd_rkuappas->Upload->DbValue) ? [] : [$this->skd_rkuappas->htmlDecode($this->skd_rkuappas->Upload->DbValue)];
                if (!EmptyValue($this->skd_rkuappas->Upload->FileName)) {
                    $newFiles = [$this->skd_rkuappas->Upload->FileName];
                    $NewFileCount = count($newFiles);
                    for ($i = 0; $i < $NewFileCount; $i++) {
                        if ($newFiles[$i] != "") {
                            $file = $newFiles[$i];
                            $tempPath = UploadTempPath($this->skd_rkuappas, $this->skd_rkuappas->Upload->Index);
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
                                $file1 = UniqueFilename($this->skd_rkuappas->physicalUploadPath(), $file); // Get new file name
                                if ($file1 != $file) { // Rename temp file
                                    while (file_exists($tempPath . $file1) || file_exists($this->skd_rkuappas->physicalUploadPath() . $file1)) { // Make sure no file name clash
                                        $file1 = UniqueFilename([$this->skd_rkuappas->physicalUploadPath(), $tempPath], $file1, true); // Use indexed name
                                    }
                                    rename($tempPath . $file, $tempPath . $file1);
                                    $newFiles[$i] = $file1;
                                }
                            }
                        }
                    }
                    $this->skd_rkuappas->Upload->DbValue = empty($oldFiles) ? "" : implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $oldFiles);
                    $this->skd_rkuappas->Upload->FileName = implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $newFiles);
                    $this->skd_rkuappas->setDbValueDef($rsnew, $this->skd_rkuappas->Upload->FileName, "", $this->skd_rkuappas->ReadOnly);
                }
            }
            if ($this->kua->Visible && !$this->kua->Upload->KeepFile) {
                $oldFiles = EmptyValue($this->kua->Upload->DbValue) ? [] : [$this->kua->htmlDecode($this->kua->Upload->DbValue)];
                if (!EmptyValue($this->kua->Upload->FileName)) {
                    $newFiles = [$this->kua->Upload->FileName];
                    $NewFileCount = count($newFiles);
                    for ($i = 0; $i < $NewFileCount; $i++) {
                        if ($newFiles[$i] != "") {
                            $file = $newFiles[$i];
                            $tempPath = UploadTempPath($this->kua, $this->kua->Upload->Index);
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
                                $file1 = UniqueFilename($this->kua->physicalUploadPath(), $file); // Get new file name
                                if ($file1 != $file) { // Rename temp file
                                    while (file_exists($tempPath . $file1) || file_exists($this->kua->physicalUploadPath() . $file1)) { // Make sure no file name clash
                                        $file1 = UniqueFilename([$this->kua->physicalUploadPath(), $tempPath], $file1, true); // Use indexed name
                                    }
                                    rename($tempPath . $file, $tempPath . $file1);
                                    $newFiles[$i] = $file1;
                                }
                            }
                        }
                    }
                    $this->kua->Upload->DbValue = empty($oldFiles) ? "" : implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $oldFiles);
                    $this->kua->Upload->FileName = implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $newFiles);
                    $this->kua->setDbValueDef($rsnew, $this->kua->Upload->FileName, "", $this->kua->ReadOnly);
                }
            }
            if ($this->ppas->Visible && !$this->ppas->Upload->KeepFile) {
                $oldFiles = EmptyValue($this->ppas->Upload->DbValue) ? [] : [$this->ppas->htmlDecode($this->ppas->Upload->DbValue)];
                if (!EmptyValue($this->ppas->Upload->FileName)) {
                    $newFiles = [$this->ppas->Upload->FileName];
                    $NewFileCount = count($newFiles);
                    for ($i = 0; $i < $NewFileCount; $i++) {
                        if ($newFiles[$i] != "") {
                            $file = $newFiles[$i];
                            $tempPath = UploadTempPath($this->ppas, $this->ppas->Upload->Index);
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
                                $file1 = UniqueFilename($this->ppas->physicalUploadPath(), $file); // Get new file name
                                if ($file1 != $file) { // Rename temp file
                                    while (file_exists($tempPath . $file1) || file_exists($this->ppas->physicalUploadPath() . $file1)) { // Make sure no file name clash
                                        $file1 = UniqueFilename([$this->ppas->physicalUploadPath(), $tempPath], $file1, true); // Use indexed name
                                    }
                                    rename($tempPath . $file, $tempPath . $file1);
                                    $newFiles[$i] = $file1;
                                }
                            }
                        }
                    }
                    $this->ppas->Upload->DbValue = empty($oldFiles) ? "" : implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $oldFiles);
                    $this->ppas->Upload->FileName = implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $newFiles);
                    $this->ppas->setDbValueDef($rsnew, $this->ppas->Upload->FileName, "", $this->ppas->ReadOnly);
                }
            }
            if ($this->skd_rqanun->Visible && !$this->skd_rqanun->Upload->KeepFile) {
                $oldFiles = EmptyValue($this->skd_rqanun->Upload->DbValue) ? [] : [$this->skd_rqanun->htmlDecode($this->skd_rqanun->Upload->DbValue)];
                if (!EmptyValue($this->skd_rqanun->Upload->FileName)) {
                    $newFiles = [$this->skd_rqanun->Upload->FileName];
                    $NewFileCount = count($newFiles);
                    for ($i = 0; $i < $NewFileCount; $i++) {
                        if ($newFiles[$i] != "") {
                            $file = $newFiles[$i];
                            $tempPath = UploadTempPath($this->skd_rqanun, $this->skd_rqanun->Upload->Index);
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
                                $file1 = UniqueFilename($this->skd_rqanun->physicalUploadPath(), $file); // Get new file name
                                if ($file1 != $file) { // Rename temp file
                                    while (file_exists($tempPath . $file1) || file_exists($this->skd_rqanun->physicalUploadPath() . $file1)) { // Make sure no file name clash
                                        $file1 = UniqueFilename([$this->skd_rqanun->physicalUploadPath(), $tempPath], $file1, true); // Use indexed name
                                    }
                                    rename($tempPath . $file, $tempPath . $file1);
                                    $newFiles[$i] = $file1;
                                }
                            }
                        }
                    }
                    $this->skd_rqanun->Upload->DbValue = empty($oldFiles) ? "" : implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $oldFiles);
                    $this->skd_rqanun->Upload->FileName = implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $newFiles);
                    $this->skd_rqanun->setDbValueDef($rsnew, $this->skd_rqanun->Upload->FileName, "", $this->skd_rqanun->ReadOnly);
                }
            }
            if ($this->nota_keuangan->Visible && !$this->nota_keuangan->Upload->KeepFile) {
                $oldFiles = EmptyValue($this->nota_keuangan->Upload->DbValue) ? [] : [$this->nota_keuangan->htmlDecode($this->nota_keuangan->Upload->DbValue)];
                if (!EmptyValue($this->nota_keuangan->Upload->FileName)) {
                    $newFiles = [$this->nota_keuangan->Upload->FileName];
                    $NewFileCount = count($newFiles);
                    for ($i = 0; $i < $NewFileCount; $i++) {
                        if ($newFiles[$i] != "") {
                            $file = $newFiles[$i];
                            $tempPath = UploadTempPath($this->nota_keuangan, $this->nota_keuangan->Upload->Index);
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
                                $file1 = UniqueFilename($this->nota_keuangan->physicalUploadPath(), $file); // Get new file name
                                if ($file1 != $file) { // Rename temp file
                                    while (file_exists($tempPath . $file1) || file_exists($this->nota_keuangan->physicalUploadPath() . $file1)) { // Make sure no file name clash
                                        $file1 = UniqueFilename([$this->nota_keuangan->physicalUploadPath(), $tempPath], $file1, true); // Use indexed name
                                    }
                                    rename($tempPath . $file, $tempPath . $file1);
                                    $newFiles[$i] = $file1;
                                }
                            }
                        }
                    }
                    $this->nota_keuangan->Upload->DbValue = empty($oldFiles) ? "" : implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $oldFiles);
                    $this->nota_keuangan->Upload->FileName = implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $newFiles);
                    $this->nota_keuangan->setDbValueDef($rsnew, $this->nota_keuangan->Upload->FileName, "", $this->nota_keuangan->ReadOnly);
                }
            }
            if ($this->pengantar_nota->Visible && !$this->pengantar_nota->Upload->KeepFile) {
                $oldFiles = EmptyValue($this->pengantar_nota->Upload->DbValue) ? [] : [$this->pengantar_nota->htmlDecode($this->pengantar_nota->Upload->DbValue)];
                if (!EmptyValue($this->pengantar_nota->Upload->FileName)) {
                    $newFiles = [$this->pengantar_nota->Upload->FileName];
                    $NewFileCount = count($newFiles);
                    for ($i = 0; $i < $NewFileCount; $i++) {
                        if ($newFiles[$i] != "") {
                            $file = $newFiles[$i];
                            $tempPath = UploadTempPath($this->pengantar_nota, $this->pengantar_nota->Upload->Index);
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
                                $file1 = UniqueFilename($this->pengantar_nota->physicalUploadPath(), $file); // Get new file name
                                if ($file1 != $file) { // Rename temp file
                                    while (file_exists($tempPath . $file1) || file_exists($this->pengantar_nota->physicalUploadPath() . $file1)) { // Make sure no file name clash
                                        $file1 = UniqueFilename([$this->pengantar_nota->physicalUploadPath(), $tempPath], $file1, true); // Use indexed name
                                    }
                                    rename($tempPath . $file, $tempPath . $file1);
                                    $newFiles[$i] = $file1;
                                }
                            }
                        }
                    }
                    $this->pengantar_nota->Upload->DbValue = empty($oldFiles) ? "" : implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $oldFiles);
                    $this->pengantar_nota->Upload->FileName = implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $newFiles);
                    $this->pengantar_nota->setDbValueDef($rsnew, $this->pengantar_nota->Upload->FileName, "", $this->pengantar_nota->ReadOnly);
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
                    $this->risalah_sidang->setDbValueDef($rsnew, $this->risalah_sidang->Upload->FileName, "", $this->risalah_sidang->ReadOnly);
                }
            }
            if ($this->bap_apbk->Visible && !$this->bap_apbk->Upload->KeepFile) {
                $oldFiles = EmptyValue($this->bap_apbk->Upload->DbValue) ? [] : [$this->bap_apbk->htmlDecode($this->bap_apbk->Upload->DbValue)];
                if (!EmptyValue($this->bap_apbk->Upload->FileName)) {
                    $newFiles = [$this->bap_apbk->Upload->FileName];
                    $NewFileCount = count($newFiles);
                    for ($i = 0; $i < $NewFileCount; $i++) {
                        if ($newFiles[$i] != "") {
                            $file = $newFiles[$i];
                            $tempPath = UploadTempPath($this->bap_apbk, $this->bap_apbk->Upload->Index);
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
                                $file1 = UniqueFilename($this->bap_apbk->physicalUploadPath(), $file); // Get new file name
                                if ($file1 != $file) { // Rename temp file
                                    while (file_exists($tempPath . $file1) || file_exists($this->bap_apbk->physicalUploadPath() . $file1)) { // Make sure no file name clash
                                        $file1 = UniqueFilename([$this->bap_apbk->physicalUploadPath(), $tempPath], $file1, true); // Use indexed name
                                    }
                                    rename($tempPath . $file, $tempPath . $file1);
                                    $newFiles[$i] = $file1;
                                }
                            }
                        }
                    }
                    $this->bap_apbk->Upload->DbValue = empty($oldFiles) ? "" : implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $oldFiles);
                    $this->bap_apbk->Upload->FileName = implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $newFiles);
                    $this->bap_apbk->setDbValueDef($rsnew, $this->bap_apbk->Upload->FileName, "", $this->bap_apbk->ReadOnly);
                }
            }
            if ($this->rq_apbk->Visible && !$this->rq_apbk->Upload->KeepFile) {
                $oldFiles = EmptyValue($this->rq_apbk->Upload->DbValue) ? [] : [$this->rq_apbk->htmlDecode($this->rq_apbk->Upload->DbValue)];
                if (!EmptyValue($this->rq_apbk->Upload->FileName)) {
                    $newFiles = [$this->rq_apbk->Upload->FileName];
                    $NewFileCount = count($newFiles);
                    for ($i = 0; $i < $NewFileCount; $i++) {
                        if ($newFiles[$i] != "") {
                            $file = $newFiles[$i];
                            $tempPath = UploadTempPath($this->rq_apbk, $this->rq_apbk->Upload->Index);
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
                                $file1 = UniqueFilename($this->rq_apbk->physicalUploadPath(), $file); // Get new file name
                                if ($file1 != $file) { // Rename temp file
                                    while (file_exists($tempPath . $file1) || file_exists($this->rq_apbk->physicalUploadPath() . $file1)) { // Make sure no file name clash
                                        $file1 = UniqueFilename([$this->rq_apbk->physicalUploadPath(), $tempPath], $file1, true); // Use indexed name
                                    }
                                    rename($tempPath . $file, $tempPath . $file1);
                                    $newFiles[$i] = $file1;
                                }
                            }
                        }
                    }
                    $this->rq_apbk->Upload->DbValue = empty($oldFiles) ? "" : implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $oldFiles);
                    $this->rq_apbk->Upload->FileName = implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $newFiles);
                    $this->rq_apbk->setDbValueDef($rsnew, $this->rq_apbk->Upload->FileName, "", $this->rq_apbk->ReadOnly);
                }
            }
            if ($this->rp_penjabaran->Visible && !$this->rp_penjabaran->Upload->KeepFile) {
                $oldFiles = EmptyValue($this->rp_penjabaran->Upload->DbValue) ? [] : [$this->rp_penjabaran->htmlDecode($this->rp_penjabaran->Upload->DbValue)];
                if (!EmptyValue($this->rp_penjabaran->Upload->FileName)) {
                    $newFiles = [$this->rp_penjabaran->Upload->FileName];
                    $NewFileCount = count($newFiles);
                    for ($i = 0; $i < $NewFileCount; $i++) {
                        if ($newFiles[$i] != "") {
                            $file = $newFiles[$i];
                            $tempPath = UploadTempPath($this->rp_penjabaran, $this->rp_penjabaran->Upload->Index);
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
                                $file1 = UniqueFilename($this->rp_penjabaran->physicalUploadPath(), $file); // Get new file name
                                if ($file1 != $file) { // Rename temp file
                                    while (file_exists($tempPath . $file1) || file_exists($this->rp_penjabaran->physicalUploadPath() . $file1)) { // Make sure no file name clash
                                        $file1 = UniqueFilename([$this->rp_penjabaran->physicalUploadPath(), $tempPath], $file1, true); // Use indexed name
                                    }
                                    rename($tempPath . $file, $tempPath . $file1);
                                    $newFiles[$i] = $file1;
                                }
                            }
                        }
                    }
                    $this->rp_penjabaran->Upload->DbValue = empty($oldFiles) ? "" : implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $oldFiles);
                    $this->rp_penjabaran->Upload->FileName = implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $newFiles);
                    $this->rp_penjabaran->setDbValueDef($rsnew, $this->rp_penjabaran->Upload->FileName, "", $this->rp_penjabaran->ReadOnly);
                }
            }
            if ($this->jadwal_proses->Visible && !$this->jadwal_proses->Upload->KeepFile) {
                $oldFiles = EmptyValue($this->jadwal_proses->Upload->DbValue) ? [] : [$this->jadwal_proses->htmlDecode($this->jadwal_proses->Upload->DbValue)];
                if (!EmptyValue($this->jadwal_proses->Upload->FileName)) {
                    $newFiles = [$this->jadwal_proses->Upload->FileName];
                    $NewFileCount = count($newFiles);
                    for ($i = 0; $i < $NewFileCount; $i++) {
                        if ($newFiles[$i] != "") {
                            $file = $newFiles[$i];
                            $tempPath = UploadTempPath($this->jadwal_proses, $this->jadwal_proses->Upload->Index);
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
                                $file1 = UniqueFilename($this->jadwal_proses->physicalUploadPath(), $file); // Get new file name
                                if ($file1 != $file) { // Rename temp file
                                    while (file_exists($tempPath . $file1) || file_exists($this->jadwal_proses->physicalUploadPath() . $file1)) { // Make sure no file name clash
                                        $file1 = UniqueFilename([$this->jadwal_proses->physicalUploadPath(), $tempPath], $file1, true); // Use indexed name
                                    }
                                    rename($tempPath . $file, $tempPath . $file1);
                                    $newFiles[$i] = $file1;
                                }
                            }
                        }
                    }
                    $this->jadwal_proses->Upload->DbValue = empty($oldFiles) ? "" : implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $oldFiles);
                    $this->jadwal_proses->Upload->FileName = implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $newFiles);
                    $this->jadwal_proses->setDbValueDef($rsnew, $this->jadwal_proses->Upload->FileName, "", $this->jadwal_proses->ReadOnly);
                }
            }
            if ($this->sinkron_kebijakan->Visible && !$this->sinkron_kebijakan->Upload->KeepFile) {
                $oldFiles = EmptyValue($this->sinkron_kebijakan->Upload->DbValue) ? [] : [$this->sinkron_kebijakan->htmlDecode($this->sinkron_kebijakan->Upload->DbValue)];
                if (!EmptyValue($this->sinkron_kebijakan->Upload->FileName)) {
                    $newFiles = [$this->sinkron_kebijakan->Upload->FileName];
                    $NewFileCount = count($newFiles);
                    for ($i = 0; $i < $NewFileCount; $i++) {
                        if ($newFiles[$i] != "") {
                            $file = $newFiles[$i];
                            $tempPath = UploadTempPath($this->sinkron_kebijakan, $this->sinkron_kebijakan->Upload->Index);
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
                                $file1 = UniqueFilename($this->sinkron_kebijakan->physicalUploadPath(), $file); // Get new file name
                                if ($file1 != $file) { // Rename temp file
                                    while (file_exists($tempPath . $file1) || file_exists($this->sinkron_kebijakan->physicalUploadPath() . $file1)) { // Make sure no file name clash
                                        $file1 = UniqueFilename([$this->sinkron_kebijakan->physicalUploadPath(), $tempPath], $file1, true); // Use indexed name
                                    }
                                    rename($tempPath . $file, $tempPath . $file1);
                                    $newFiles[$i] = $file1;
                                }
                            }
                        }
                    }
                    $this->sinkron_kebijakan->Upload->DbValue = empty($oldFiles) ? "" : implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $oldFiles);
                    $this->sinkron_kebijakan->Upload->FileName = implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $newFiles);
                    $this->sinkron_kebijakan->setDbValueDef($rsnew, $this->sinkron_kebijakan->Upload->FileName, "", $this->sinkron_kebijakan->ReadOnly);
                }
            }
            if ($this->konsistensi_program->Visible && !$this->konsistensi_program->Upload->KeepFile) {
                $oldFiles = EmptyValue($this->konsistensi_program->Upload->DbValue) ? [] : [$this->konsistensi_program->htmlDecode($this->konsistensi_program->Upload->DbValue)];
                if (!EmptyValue($this->konsistensi_program->Upload->FileName)) {
                    $newFiles = [$this->konsistensi_program->Upload->FileName];
                    $NewFileCount = count($newFiles);
                    for ($i = 0; $i < $NewFileCount; $i++) {
                        if ($newFiles[$i] != "") {
                            $file = $newFiles[$i];
                            $tempPath = UploadTempPath($this->konsistensi_program, $this->konsistensi_program->Upload->Index);
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
                                $file1 = UniqueFilename($this->konsistensi_program->physicalUploadPath(), $file); // Get new file name
                                if ($file1 != $file) { // Rename temp file
                                    while (file_exists($tempPath . $file1) || file_exists($this->konsistensi_program->physicalUploadPath() . $file1)) { // Make sure no file name clash
                                        $file1 = UniqueFilename([$this->konsistensi_program->physicalUploadPath(), $tempPath], $file1, true); // Use indexed name
                                    }
                                    rename($tempPath . $file, $tempPath . $file1);
                                    $newFiles[$i] = $file1;
                                }
                            }
                        }
                    }
                    $this->konsistensi_program->Upload->DbValue = empty($oldFiles) ? "" : implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $oldFiles);
                    $this->konsistensi_program->Upload->FileName = implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $newFiles);
                    $this->konsistensi_program->setDbValueDef($rsnew, $this->konsistensi_program->Upload->FileName, "", $this->konsistensi_program->ReadOnly);
                }
            }
            if ($this->alokasi_pendidikan->Visible && !$this->alokasi_pendidikan->Upload->KeepFile) {
                $oldFiles = EmptyValue($this->alokasi_pendidikan->Upload->DbValue) ? [] : [$this->alokasi_pendidikan->htmlDecode($this->alokasi_pendidikan->Upload->DbValue)];
                if (!EmptyValue($this->alokasi_pendidikan->Upload->FileName)) {
                    $newFiles = [$this->alokasi_pendidikan->Upload->FileName];
                    $NewFileCount = count($newFiles);
                    for ($i = 0; $i < $NewFileCount; $i++) {
                        if ($newFiles[$i] != "") {
                            $file = $newFiles[$i];
                            $tempPath = UploadTempPath($this->alokasi_pendidikan, $this->alokasi_pendidikan->Upload->Index);
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
                                $file1 = UniqueFilename($this->alokasi_pendidikan->physicalUploadPath(), $file); // Get new file name
                                if ($file1 != $file) { // Rename temp file
                                    while (file_exists($tempPath . $file1) || file_exists($this->alokasi_pendidikan->physicalUploadPath() . $file1)) { // Make sure no file name clash
                                        $file1 = UniqueFilename([$this->alokasi_pendidikan->physicalUploadPath(), $tempPath], $file1, true); // Use indexed name
                                    }
                                    rename($tempPath . $file, $tempPath . $file1);
                                    $newFiles[$i] = $file1;
                                }
                            }
                        }
                    }
                    $this->alokasi_pendidikan->Upload->DbValue = empty($oldFiles) ? "" : implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $oldFiles);
                    $this->alokasi_pendidikan->Upload->FileName = implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $newFiles);
                    $this->alokasi_pendidikan->setDbValueDef($rsnew, $this->alokasi_pendidikan->Upload->FileName, "", $this->alokasi_pendidikan->ReadOnly);
                }
            }
            if ($this->alokasi_kesehatan->Visible && !$this->alokasi_kesehatan->Upload->KeepFile) {
                $oldFiles = EmptyValue($this->alokasi_kesehatan->Upload->DbValue) ? [] : [$this->alokasi_kesehatan->htmlDecode($this->alokasi_kesehatan->Upload->DbValue)];
                if (!EmptyValue($this->alokasi_kesehatan->Upload->FileName)) {
                    $newFiles = [$this->alokasi_kesehatan->Upload->FileName];
                    $NewFileCount = count($newFiles);
                    for ($i = 0; $i < $NewFileCount; $i++) {
                        if ($newFiles[$i] != "") {
                            $file = $newFiles[$i];
                            $tempPath = UploadTempPath($this->alokasi_kesehatan, $this->alokasi_kesehatan->Upload->Index);
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
                                $file1 = UniqueFilename($this->alokasi_kesehatan->physicalUploadPath(), $file); // Get new file name
                                if ($file1 != $file) { // Rename temp file
                                    while (file_exists($tempPath . $file1) || file_exists($this->alokasi_kesehatan->physicalUploadPath() . $file1)) { // Make sure no file name clash
                                        $file1 = UniqueFilename([$this->alokasi_kesehatan->physicalUploadPath(), $tempPath], $file1, true); // Use indexed name
                                    }
                                    rename($tempPath . $file, $tempPath . $file1);
                                    $newFiles[$i] = $file1;
                                }
                            }
                        }
                    }
                    $this->alokasi_kesehatan->Upload->DbValue = empty($oldFiles) ? "" : implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $oldFiles);
                    $this->alokasi_kesehatan->Upload->FileName = implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $newFiles);
                    $this->alokasi_kesehatan->setDbValueDef($rsnew, $this->alokasi_kesehatan->Upload->FileName, "", $this->alokasi_kesehatan->ReadOnly);
                }
            }
            if ($this->alokasi_belanja->Visible && !$this->alokasi_belanja->Upload->KeepFile) {
                $oldFiles = EmptyValue($this->alokasi_belanja->Upload->DbValue) ? [] : [$this->alokasi_belanja->htmlDecode($this->alokasi_belanja->Upload->DbValue)];
                if (!EmptyValue($this->alokasi_belanja->Upload->FileName)) {
                    $newFiles = [$this->alokasi_belanja->Upload->FileName];
                    $NewFileCount = count($newFiles);
                    for ($i = 0; $i < $NewFileCount; $i++) {
                        if ($newFiles[$i] != "") {
                            $file = $newFiles[$i];
                            $tempPath = UploadTempPath($this->alokasi_belanja, $this->alokasi_belanja->Upload->Index);
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
                                $file1 = UniqueFilename($this->alokasi_belanja->physicalUploadPath(), $file); // Get new file name
                                if ($file1 != $file) { // Rename temp file
                                    while (file_exists($tempPath . $file1) || file_exists($this->alokasi_belanja->physicalUploadPath() . $file1)) { // Make sure no file name clash
                                        $file1 = UniqueFilename([$this->alokasi_belanja->physicalUploadPath(), $tempPath], $file1, true); // Use indexed name
                                    }
                                    rename($tempPath . $file, $tempPath . $file1);
                                    $newFiles[$i] = $file1;
                                }
                            }
                        }
                    }
                    $this->alokasi_belanja->Upload->DbValue = empty($oldFiles) ? "" : implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $oldFiles);
                    $this->alokasi_belanja->Upload->FileName = implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $newFiles);
                    $this->alokasi_belanja->setDbValueDef($rsnew, $this->alokasi_belanja->Upload->FileName, "", $this->alokasi_belanja->ReadOnly);
                }
            }
            if ($this->bak_kegiatan->Visible && !$this->bak_kegiatan->Upload->KeepFile) {
                $oldFiles = EmptyValue($this->bak_kegiatan->Upload->DbValue) ? [] : [$this->bak_kegiatan->htmlDecode($this->bak_kegiatan->Upload->DbValue)];
                if (!EmptyValue($this->bak_kegiatan->Upload->FileName)) {
                    $newFiles = [$this->bak_kegiatan->Upload->FileName];
                    $NewFileCount = count($newFiles);
                    for ($i = 0; $i < $NewFileCount; $i++) {
                        if ($newFiles[$i] != "") {
                            $file = $newFiles[$i];
                            $tempPath = UploadTempPath($this->bak_kegiatan, $this->bak_kegiatan->Upload->Index);
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
                                $file1 = UniqueFilename($this->bak_kegiatan->physicalUploadPath(), $file); // Get new file name
                                if ($file1 != $file) { // Rename temp file
                                    while (file_exists($tempPath . $file1) || file_exists($this->bak_kegiatan->physicalUploadPath() . $file1)) { // Make sure no file name clash
                                        $file1 = UniqueFilename([$this->bak_kegiatan->physicalUploadPath(), $tempPath], $file1, true); // Use indexed name
                                    }
                                    rename($tempPath . $file, $tempPath . $file1);
                                    $newFiles[$i] = $file1;
                                }
                            }
                        }
                    }
                    $this->bak_kegiatan->Upload->DbValue = empty($oldFiles) ? "" : implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $oldFiles);
                    $this->bak_kegiatan->Upload->FileName = implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $newFiles);
                    $this->bak_kegiatan->setDbValueDef($rsnew, $this->bak_kegiatan->Upload->FileName, "", $this->bak_kegiatan->ReadOnly);
                }
            }
            if ($this->softcopy_rka->Visible && !$this->softcopy_rka->Upload->KeepFile) {
                $oldFiles = EmptyValue($this->softcopy_rka->Upload->DbValue) ? [] : [$this->softcopy_rka->htmlDecode($this->softcopy_rka->Upload->DbValue)];
                if (!EmptyValue($this->softcopy_rka->Upload->FileName)) {
                    $newFiles = [$this->softcopy_rka->Upload->FileName];
                    $NewFileCount = count($newFiles);
                    for ($i = 0; $i < $NewFileCount; $i++) {
                        if ($newFiles[$i] != "") {
                            $file = $newFiles[$i];
                            $tempPath = UploadTempPath($this->softcopy_rka, $this->softcopy_rka->Upload->Index);
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
                                $file1 = UniqueFilename($this->softcopy_rka->physicalUploadPath(), $file); // Get new file name
                                if ($file1 != $file) { // Rename temp file
                                    while (file_exists($tempPath . $file1) || file_exists($this->softcopy_rka->physicalUploadPath() . $file1)) { // Make sure no file name clash
                                        $file1 = UniqueFilename([$this->softcopy_rka->physicalUploadPath(), $tempPath], $file1, true); // Use indexed name
                                    }
                                    rename($tempPath . $file, $tempPath . $file1);
                                    $newFiles[$i] = $file1;
                                }
                            }
                        }
                    }
                    $this->softcopy_rka->Upload->DbValue = empty($oldFiles) ? "" : implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $oldFiles);
                    $this->softcopy_rka->Upload->FileName = implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $newFiles);
                    $this->softcopy_rka->setDbValueDef($rsnew, $this->softcopy_rka->Upload->FileName, "", $this->softcopy_rka->ReadOnly);
                }
            }
            if ($this->otsus->Visible && !$this->otsus->Upload->KeepFile) {
                $oldFiles = EmptyValue($this->otsus->Upload->DbValue) ? [] : [$this->otsus->htmlDecode($this->otsus->Upload->DbValue)];
                if (!EmptyValue($this->otsus->Upload->FileName)) {
                    $newFiles = [$this->otsus->Upload->FileName];
                    $NewFileCount = count($newFiles);
                    for ($i = 0; $i < $NewFileCount; $i++) {
                        if ($newFiles[$i] != "") {
                            $file = $newFiles[$i];
                            $tempPath = UploadTempPath($this->otsus, $this->otsus->Upload->Index);
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
                                $file1 = UniqueFilename($this->otsus->physicalUploadPath(), $file); // Get new file name
                                if ($file1 != $file) { // Rename temp file
                                    while (file_exists($tempPath . $file1) || file_exists($this->otsus->physicalUploadPath() . $file1)) { // Make sure no file name clash
                                        $file1 = UniqueFilename([$this->otsus->physicalUploadPath(), $tempPath], $file1, true); // Use indexed name
                                    }
                                    rename($tempPath . $file, $tempPath . $file1);
                                    $newFiles[$i] = $file1;
                                }
                            }
                        }
                    }
                    $this->otsus->Upload->DbValue = empty($oldFiles) ? "" : implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $oldFiles);
                    $this->otsus->Upload->FileName = implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $newFiles);
                    $this->otsus->setDbValueDef($rsnew, $this->otsus->Upload->FileName, "", $this->otsus->ReadOnly);
                }
            }
            if ($this->qanun_perbup->Visible && !$this->qanun_perbup->Upload->KeepFile) {
                $oldFiles = EmptyValue($this->qanun_perbup->Upload->DbValue) ? [] : [$this->qanun_perbup->htmlDecode($this->qanun_perbup->Upload->DbValue)];
                if (!EmptyValue($this->qanun_perbup->Upload->FileName)) {
                    $newFiles = [$this->qanun_perbup->Upload->FileName];
                    $NewFileCount = count($newFiles);
                    for ($i = 0; $i < $NewFileCount; $i++) {
                        if ($newFiles[$i] != "") {
                            $file = $newFiles[$i];
                            $tempPath = UploadTempPath($this->qanun_perbup, $this->qanun_perbup->Upload->Index);
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
                                $file1 = UniqueFilename($this->qanun_perbup->physicalUploadPath(), $file); // Get new file name
                                if ($file1 != $file) { // Rename temp file
                                    while (file_exists($tempPath . $file1) || file_exists($this->qanun_perbup->physicalUploadPath() . $file1)) { // Make sure no file name clash
                                        $file1 = UniqueFilename([$this->qanun_perbup->physicalUploadPath(), $tempPath], $file1, true); // Use indexed name
                                    }
                                    rename($tempPath . $file, $tempPath . $file1);
                                    $newFiles[$i] = $file1;
                                }
                            }
                        }
                    }
                    $this->qanun_perbup->Upload->DbValue = empty($oldFiles) ? "" : implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $oldFiles);
                    $this->qanun_perbup->Upload->FileName = implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $newFiles);
                    $this->qanun_perbup->setDbValueDef($rsnew, $this->qanun_perbup->Upload->FileName, "", $this->qanun_perbup->ReadOnly);
                }
            }
            if ($this->tindak_apbkp->Visible && !$this->tindak_apbkp->Upload->KeepFile) {
                $oldFiles = EmptyValue($this->tindak_apbkp->Upload->DbValue) ? [] : [$this->tindak_apbkp->htmlDecode($this->tindak_apbkp->Upload->DbValue)];
                if (!EmptyValue($this->tindak_apbkp->Upload->FileName)) {
                    $newFiles = [$this->tindak_apbkp->Upload->FileName];
                    $NewFileCount = count($newFiles);
                    for ($i = 0; $i < $NewFileCount; $i++) {
                        if ($newFiles[$i] != "") {
                            $file = $newFiles[$i];
                            $tempPath = UploadTempPath($this->tindak_apbkp, $this->tindak_apbkp->Upload->Index);
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
                                $file1 = UniqueFilename($this->tindak_apbkp->physicalUploadPath(), $file); // Get new file name
                                if ($file1 != $file) { // Rename temp file
                                    while (file_exists($tempPath . $file1) || file_exists($this->tindak_apbkp->physicalUploadPath() . $file1)) { // Make sure no file name clash
                                        $file1 = UniqueFilename([$this->tindak_apbkp->physicalUploadPath(), $tempPath], $file1, true); // Use indexed name
                                    }
                                    rename($tempPath . $file, $tempPath . $file1);
                                    $newFiles[$i] = $file1;
                                }
                            }
                        }
                    }
                    $this->tindak_apbkp->Upload->DbValue = empty($oldFiles) ? "" : implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $oldFiles);
                    $this->tindak_apbkp->Upload->FileName = implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $newFiles);
                    $this->tindak_apbkp->setDbValueDef($rsnew, $this->tindak_apbkp->Upload->FileName, "", $this->tindak_apbkp->ReadOnly);
                }
            }

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
                    if ($this->rpjmd->Visible && !$this->rpjmd->Upload->KeepFile) {
                        $oldFiles = EmptyValue($this->rpjmd->Upload->DbValue) ? [] : [$this->rpjmd->htmlDecode($this->rpjmd->Upload->DbValue)];
                        if (!EmptyValue($this->rpjmd->Upload->FileName)) {
                            $newFiles = [$this->rpjmd->Upload->FileName];
                            $newFiles2 = [$this->rpjmd->htmlDecode($rsnew['rpjmd'])];
                            $newFileCount = count($newFiles);
                            for ($i = 0; $i < $newFileCount; $i++) {
                                if ($newFiles[$i] != "") {
                                    $file = UploadTempPath($this->rpjmd, $this->rpjmd->Upload->Index) . $newFiles[$i];
                                    if (file_exists($file)) {
                                        if (@$newFiles2[$i] != "") { // Use correct file name
                                            $newFiles[$i] = $newFiles2[$i];
                                        }
                                        if (!$this->rpjmd->Upload->SaveToFile($newFiles[$i], true, $i)) { // Just replace
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
                                    @unlink($this->rpjmd->oldPhysicalUploadPath() . $oldFile);
                                }
                            }
                        }
                    }
                    if ($this->rkpk->Visible && !$this->rkpk->Upload->KeepFile) {
                        $oldFiles = EmptyValue($this->rkpk->Upload->DbValue) ? [] : [$this->rkpk->htmlDecode($this->rkpk->Upload->DbValue)];
                        if (!EmptyValue($this->rkpk->Upload->FileName)) {
                            $newFiles = [$this->rkpk->Upload->FileName];
                            $newFiles2 = [$this->rkpk->htmlDecode($rsnew['rkpk'])];
                            $newFileCount = count($newFiles);
                            for ($i = 0; $i < $newFileCount; $i++) {
                                if ($newFiles[$i] != "") {
                                    $file = UploadTempPath($this->rkpk, $this->rkpk->Upload->Index) . $newFiles[$i];
                                    if (file_exists($file)) {
                                        if (@$newFiles2[$i] != "") { // Use correct file name
                                            $newFiles[$i] = $newFiles2[$i];
                                        }
                                        if (!$this->rkpk->Upload->SaveToFile($newFiles[$i], true, $i)) { // Just replace
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
                                    @unlink($this->rkpk->oldPhysicalUploadPath() . $oldFile);
                                }
                            }
                        }
                    }
                    if ($this->skd_rkuappas->Visible && !$this->skd_rkuappas->Upload->KeepFile) {
                        $oldFiles = EmptyValue($this->skd_rkuappas->Upload->DbValue) ? [] : [$this->skd_rkuappas->htmlDecode($this->skd_rkuappas->Upload->DbValue)];
                        if (!EmptyValue($this->skd_rkuappas->Upload->FileName)) {
                            $newFiles = [$this->skd_rkuappas->Upload->FileName];
                            $newFiles2 = [$this->skd_rkuappas->htmlDecode($rsnew['skd_rkuappas'])];
                            $newFileCount = count($newFiles);
                            for ($i = 0; $i < $newFileCount; $i++) {
                                if ($newFiles[$i] != "") {
                                    $file = UploadTempPath($this->skd_rkuappas, $this->skd_rkuappas->Upload->Index) . $newFiles[$i];
                                    if (file_exists($file)) {
                                        if (@$newFiles2[$i] != "") { // Use correct file name
                                            $newFiles[$i] = $newFiles2[$i];
                                        }
                                        if (!$this->skd_rkuappas->Upload->SaveToFile($newFiles[$i], true, $i)) { // Just replace
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
                                    @unlink($this->skd_rkuappas->oldPhysicalUploadPath() . $oldFile);
                                }
                            }
                        }
                    }
                    if ($this->kua->Visible && !$this->kua->Upload->KeepFile) {
                        $oldFiles = EmptyValue($this->kua->Upload->DbValue) ? [] : [$this->kua->htmlDecode($this->kua->Upload->DbValue)];
                        if (!EmptyValue($this->kua->Upload->FileName)) {
                            $newFiles = [$this->kua->Upload->FileName];
                            $newFiles2 = [$this->kua->htmlDecode($rsnew['kua'])];
                            $newFileCount = count($newFiles);
                            for ($i = 0; $i < $newFileCount; $i++) {
                                if ($newFiles[$i] != "") {
                                    $file = UploadTempPath($this->kua, $this->kua->Upload->Index) . $newFiles[$i];
                                    if (file_exists($file)) {
                                        if (@$newFiles2[$i] != "") { // Use correct file name
                                            $newFiles[$i] = $newFiles2[$i];
                                        }
                                        if (!$this->kua->Upload->SaveToFile($newFiles[$i], true, $i)) { // Just replace
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
                                    @unlink($this->kua->oldPhysicalUploadPath() . $oldFile);
                                }
                            }
                        }
                    }
                    if ($this->ppas->Visible && !$this->ppas->Upload->KeepFile) {
                        $oldFiles = EmptyValue($this->ppas->Upload->DbValue) ? [] : [$this->ppas->htmlDecode($this->ppas->Upload->DbValue)];
                        if (!EmptyValue($this->ppas->Upload->FileName)) {
                            $newFiles = [$this->ppas->Upload->FileName];
                            $newFiles2 = [$this->ppas->htmlDecode($rsnew['ppas'])];
                            $newFileCount = count($newFiles);
                            for ($i = 0; $i < $newFileCount; $i++) {
                                if ($newFiles[$i] != "") {
                                    $file = UploadTempPath($this->ppas, $this->ppas->Upload->Index) . $newFiles[$i];
                                    if (file_exists($file)) {
                                        if (@$newFiles2[$i] != "") { // Use correct file name
                                            $newFiles[$i] = $newFiles2[$i];
                                        }
                                        if (!$this->ppas->Upload->SaveToFile($newFiles[$i], true, $i)) { // Just replace
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
                                    @unlink($this->ppas->oldPhysicalUploadPath() . $oldFile);
                                }
                            }
                        }
                    }
                    if ($this->skd_rqanun->Visible && !$this->skd_rqanun->Upload->KeepFile) {
                        $oldFiles = EmptyValue($this->skd_rqanun->Upload->DbValue) ? [] : [$this->skd_rqanun->htmlDecode($this->skd_rqanun->Upload->DbValue)];
                        if (!EmptyValue($this->skd_rqanun->Upload->FileName)) {
                            $newFiles = [$this->skd_rqanun->Upload->FileName];
                            $newFiles2 = [$this->skd_rqanun->htmlDecode($rsnew['skd_rqanun'])];
                            $newFileCount = count($newFiles);
                            for ($i = 0; $i < $newFileCount; $i++) {
                                if ($newFiles[$i] != "") {
                                    $file = UploadTempPath($this->skd_rqanun, $this->skd_rqanun->Upload->Index) . $newFiles[$i];
                                    if (file_exists($file)) {
                                        if (@$newFiles2[$i] != "") { // Use correct file name
                                            $newFiles[$i] = $newFiles2[$i];
                                        }
                                        if (!$this->skd_rqanun->Upload->SaveToFile($newFiles[$i], true, $i)) { // Just replace
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
                                    @unlink($this->skd_rqanun->oldPhysicalUploadPath() . $oldFile);
                                }
                            }
                        }
                    }
                    if ($this->nota_keuangan->Visible && !$this->nota_keuangan->Upload->KeepFile) {
                        $oldFiles = EmptyValue($this->nota_keuangan->Upload->DbValue) ? [] : [$this->nota_keuangan->htmlDecode($this->nota_keuangan->Upload->DbValue)];
                        if (!EmptyValue($this->nota_keuangan->Upload->FileName)) {
                            $newFiles = [$this->nota_keuangan->Upload->FileName];
                            $newFiles2 = [$this->nota_keuangan->htmlDecode($rsnew['nota_keuangan'])];
                            $newFileCount = count($newFiles);
                            for ($i = 0; $i < $newFileCount; $i++) {
                                if ($newFiles[$i] != "") {
                                    $file = UploadTempPath($this->nota_keuangan, $this->nota_keuangan->Upload->Index) . $newFiles[$i];
                                    if (file_exists($file)) {
                                        if (@$newFiles2[$i] != "") { // Use correct file name
                                            $newFiles[$i] = $newFiles2[$i];
                                        }
                                        if (!$this->nota_keuangan->Upload->SaveToFile($newFiles[$i], true, $i)) { // Just replace
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
                                    @unlink($this->nota_keuangan->oldPhysicalUploadPath() . $oldFile);
                                }
                            }
                        }
                    }
                    if ($this->pengantar_nota->Visible && !$this->pengantar_nota->Upload->KeepFile) {
                        $oldFiles = EmptyValue($this->pengantar_nota->Upload->DbValue) ? [] : [$this->pengantar_nota->htmlDecode($this->pengantar_nota->Upload->DbValue)];
                        if (!EmptyValue($this->pengantar_nota->Upload->FileName)) {
                            $newFiles = [$this->pengantar_nota->Upload->FileName];
                            $newFiles2 = [$this->pengantar_nota->htmlDecode($rsnew['pengantar_nota'])];
                            $newFileCount = count($newFiles);
                            for ($i = 0; $i < $newFileCount; $i++) {
                                if ($newFiles[$i] != "") {
                                    $file = UploadTempPath($this->pengantar_nota, $this->pengantar_nota->Upload->Index) . $newFiles[$i];
                                    if (file_exists($file)) {
                                        if (@$newFiles2[$i] != "") { // Use correct file name
                                            $newFiles[$i] = $newFiles2[$i];
                                        }
                                        if (!$this->pengantar_nota->Upload->SaveToFile($newFiles[$i], true, $i)) { // Just replace
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
                                    @unlink($this->pengantar_nota->oldPhysicalUploadPath() . $oldFile);
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
                    if ($this->bap_apbk->Visible && !$this->bap_apbk->Upload->KeepFile) {
                        $oldFiles = EmptyValue($this->bap_apbk->Upload->DbValue) ? [] : [$this->bap_apbk->htmlDecode($this->bap_apbk->Upload->DbValue)];
                        if (!EmptyValue($this->bap_apbk->Upload->FileName)) {
                            $newFiles = [$this->bap_apbk->Upload->FileName];
                            $newFiles2 = [$this->bap_apbk->htmlDecode($rsnew['bap_apbk'])];
                            $newFileCount = count($newFiles);
                            for ($i = 0; $i < $newFileCount; $i++) {
                                if ($newFiles[$i] != "") {
                                    $file = UploadTempPath($this->bap_apbk, $this->bap_apbk->Upload->Index) . $newFiles[$i];
                                    if (file_exists($file)) {
                                        if (@$newFiles2[$i] != "") { // Use correct file name
                                            $newFiles[$i] = $newFiles2[$i];
                                        }
                                        if (!$this->bap_apbk->Upload->SaveToFile($newFiles[$i], true, $i)) { // Just replace
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
                                    @unlink($this->bap_apbk->oldPhysicalUploadPath() . $oldFile);
                                }
                            }
                        }
                    }
                    if ($this->rq_apbk->Visible && !$this->rq_apbk->Upload->KeepFile) {
                        $oldFiles = EmptyValue($this->rq_apbk->Upload->DbValue) ? [] : [$this->rq_apbk->htmlDecode($this->rq_apbk->Upload->DbValue)];
                        if (!EmptyValue($this->rq_apbk->Upload->FileName)) {
                            $newFiles = [$this->rq_apbk->Upload->FileName];
                            $newFiles2 = [$this->rq_apbk->htmlDecode($rsnew['rq_apbk'])];
                            $newFileCount = count($newFiles);
                            for ($i = 0; $i < $newFileCount; $i++) {
                                if ($newFiles[$i] != "") {
                                    $file = UploadTempPath($this->rq_apbk, $this->rq_apbk->Upload->Index) . $newFiles[$i];
                                    if (file_exists($file)) {
                                        if (@$newFiles2[$i] != "") { // Use correct file name
                                            $newFiles[$i] = $newFiles2[$i];
                                        }
                                        if (!$this->rq_apbk->Upload->SaveToFile($newFiles[$i], true, $i)) { // Just replace
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
                                    @unlink($this->rq_apbk->oldPhysicalUploadPath() . $oldFile);
                                }
                            }
                        }
                    }
                    if ($this->rp_penjabaran->Visible && !$this->rp_penjabaran->Upload->KeepFile) {
                        $oldFiles = EmptyValue($this->rp_penjabaran->Upload->DbValue) ? [] : [$this->rp_penjabaran->htmlDecode($this->rp_penjabaran->Upload->DbValue)];
                        if (!EmptyValue($this->rp_penjabaran->Upload->FileName)) {
                            $newFiles = [$this->rp_penjabaran->Upload->FileName];
                            $newFiles2 = [$this->rp_penjabaran->htmlDecode($rsnew['rp_penjabaran'])];
                            $newFileCount = count($newFiles);
                            for ($i = 0; $i < $newFileCount; $i++) {
                                if ($newFiles[$i] != "") {
                                    $file = UploadTempPath($this->rp_penjabaran, $this->rp_penjabaran->Upload->Index) . $newFiles[$i];
                                    if (file_exists($file)) {
                                        if (@$newFiles2[$i] != "") { // Use correct file name
                                            $newFiles[$i] = $newFiles2[$i];
                                        }
                                        if (!$this->rp_penjabaran->Upload->SaveToFile($newFiles[$i], true, $i)) { // Just replace
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
                                    @unlink($this->rp_penjabaran->oldPhysicalUploadPath() . $oldFile);
                                }
                            }
                        }
                    }
                    if ($this->jadwal_proses->Visible && !$this->jadwal_proses->Upload->KeepFile) {
                        $oldFiles = EmptyValue($this->jadwal_proses->Upload->DbValue) ? [] : [$this->jadwal_proses->htmlDecode($this->jadwal_proses->Upload->DbValue)];
                        if (!EmptyValue($this->jadwal_proses->Upload->FileName)) {
                            $newFiles = [$this->jadwal_proses->Upload->FileName];
                            $newFiles2 = [$this->jadwal_proses->htmlDecode($rsnew['jadwal_proses'])];
                            $newFileCount = count($newFiles);
                            for ($i = 0; $i < $newFileCount; $i++) {
                                if ($newFiles[$i] != "") {
                                    $file = UploadTempPath($this->jadwal_proses, $this->jadwal_proses->Upload->Index) . $newFiles[$i];
                                    if (file_exists($file)) {
                                        if (@$newFiles2[$i] != "") { // Use correct file name
                                            $newFiles[$i] = $newFiles2[$i];
                                        }
                                        if (!$this->jadwal_proses->Upload->SaveToFile($newFiles[$i], true, $i)) { // Just replace
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
                                    @unlink($this->jadwal_proses->oldPhysicalUploadPath() . $oldFile);
                                }
                            }
                        }
                    }
                    if ($this->sinkron_kebijakan->Visible && !$this->sinkron_kebijakan->Upload->KeepFile) {
                        $oldFiles = EmptyValue($this->sinkron_kebijakan->Upload->DbValue) ? [] : [$this->sinkron_kebijakan->htmlDecode($this->sinkron_kebijakan->Upload->DbValue)];
                        if (!EmptyValue($this->sinkron_kebijakan->Upload->FileName)) {
                            $newFiles = [$this->sinkron_kebijakan->Upload->FileName];
                            $newFiles2 = [$this->sinkron_kebijakan->htmlDecode($rsnew['sinkron_kebijakan'])];
                            $newFileCount = count($newFiles);
                            for ($i = 0; $i < $newFileCount; $i++) {
                                if ($newFiles[$i] != "") {
                                    $file = UploadTempPath($this->sinkron_kebijakan, $this->sinkron_kebijakan->Upload->Index) . $newFiles[$i];
                                    if (file_exists($file)) {
                                        if (@$newFiles2[$i] != "") { // Use correct file name
                                            $newFiles[$i] = $newFiles2[$i];
                                        }
                                        if (!$this->sinkron_kebijakan->Upload->SaveToFile($newFiles[$i], true, $i)) { // Just replace
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
                                    @unlink($this->sinkron_kebijakan->oldPhysicalUploadPath() . $oldFile);
                                }
                            }
                        }
                    }
                    if ($this->konsistensi_program->Visible && !$this->konsistensi_program->Upload->KeepFile) {
                        $oldFiles = EmptyValue($this->konsistensi_program->Upload->DbValue) ? [] : [$this->konsistensi_program->htmlDecode($this->konsistensi_program->Upload->DbValue)];
                        if (!EmptyValue($this->konsistensi_program->Upload->FileName)) {
                            $newFiles = [$this->konsistensi_program->Upload->FileName];
                            $newFiles2 = [$this->konsistensi_program->htmlDecode($rsnew['konsistensi_program'])];
                            $newFileCount = count($newFiles);
                            for ($i = 0; $i < $newFileCount; $i++) {
                                if ($newFiles[$i] != "") {
                                    $file = UploadTempPath($this->konsistensi_program, $this->konsistensi_program->Upload->Index) . $newFiles[$i];
                                    if (file_exists($file)) {
                                        if (@$newFiles2[$i] != "") { // Use correct file name
                                            $newFiles[$i] = $newFiles2[$i];
                                        }
                                        if (!$this->konsistensi_program->Upload->SaveToFile($newFiles[$i], true, $i)) { // Just replace
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
                                    @unlink($this->konsistensi_program->oldPhysicalUploadPath() . $oldFile);
                                }
                            }
                        }
                    }
                    if ($this->alokasi_pendidikan->Visible && !$this->alokasi_pendidikan->Upload->KeepFile) {
                        $oldFiles = EmptyValue($this->alokasi_pendidikan->Upload->DbValue) ? [] : [$this->alokasi_pendidikan->htmlDecode($this->alokasi_pendidikan->Upload->DbValue)];
                        if (!EmptyValue($this->alokasi_pendidikan->Upload->FileName)) {
                            $newFiles = [$this->alokasi_pendidikan->Upload->FileName];
                            $newFiles2 = [$this->alokasi_pendidikan->htmlDecode($rsnew['alokasi_pendidikan'])];
                            $newFileCount = count($newFiles);
                            for ($i = 0; $i < $newFileCount; $i++) {
                                if ($newFiles[$i] != "") {
                                    $file = UploadTempPath($this->alokasi_pendidikan, $this->alokasi_pendidikan->Upload->Index) . $newFiles[$i];
                                    if (file_exists($file)) {
                                        if (@$newFiles2[$i] != "") { // Use correct file name
                                            $newFiles[$i] = $newFiles2[$i];
                                        }
                                        if (!$this->alokasi_pendidikan->Upload->SaveToFile($newFiles[$i], true, $i)) { // Just replace
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
                                    @unlink($this->alokasi_pendidikan->oldPhysicalUploadPath() . $oldFile);
                                }
                            }
                        }
                    }
                    if ($this->alokasi_kesehatan->Visible && !$this->alokasi_kesehatan->Upload->KeepFile) {
                        $oldFiles = EmptyValue($this->alokasi_kesehatan->Upload->DbValue) ? [] : [$this->alokasi_kesehatan->htmlDecode($this->alokasi_kesehatan->Upload->DbValue)];
                        if (!EmptyValue($this->alokasi_kesehatan->Upload->FileName)) {
                            $newFiles = [$this->alokasi_kesehatan->Upload->FileName];
                            $newFiles2 = [$this->alokasi_kesehatan->htmlDecode($rsnew['alokasi_kesehatan'])];
                            $newFileCount = count($newFiles);
                            for ($i = 0; $i < $newFileCount; $i++) {
                                if ($newFiles[$i] != "") {
                                    $file = UploadTempPath($this->alokasi_kesehatan, $this->alokasi_kesehatan->Upload->Index) . $newFiles[$i];
                                    if (file_exists($file)) {
                                        if (@$newFiles2[$i] != "") { // Use correct file name
                                            $newFiles[$i] = $newFiles2[$i];
                                        }
                                        if (!$this->alokasi_kesehatan->Upload->SaveToFile($newFiles[$i], true, $i)) { // Just replace
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
                                    @unlink($this->alokasi_kesehatan->oldPhysicalUploadPath() . $oldFile);
                                }
                            }
                        }
                    }
                    if ($this->alokasi_belanja->Visible && !$this->alokasi_belanja->Upload->KeepFile) {
                        $oldFiles = EmptyValue($this->alokasi_belanja->Upload->DbValue) ? [] : [$this->alokasi_belanja->htmlDecode($this->alokasi_belanja->Upload->DbValue)];
                        if (!EmptyValue($this->alokasi_belanja->Upload->FileName)) {
                            $newFiles = [$this->alokasi_belanja->Upload->FileName];
                            $newFiles2 = [$this->alokasi_belanja->htmlDecode($rsnew['alokasi_belanja'])];
                            $newFileCount = count($newFiles);
                            for ($i = 0; $i < $newFileCount; $i++) {
                                if ($newFiles[$i] != "") {
                                    $file = UploadTempPath($this->alokasi_belanja, $this->alokasi_belanja->Upload->Index) . $newFiles[$i];
                                    if (file_exists($file)) {
                                        if (@$newFiles2[$i] != "") { // Use correct file name
                                            $newFiles[$i] = $newFiles2[$i];
                                        }
                                        if (!$this->alokasi_belanja->Upload->SaveToFile($newFiles[$i], true, $i)) { // Just replace
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
                                    @unlink($this->alokasi_belanja->oldPhysicalUploadPath() . $oldFile);
                                }
                            }
                        }
                    }
                    if ($this->bak_kegiatan->Visible && !$this->bak_kegiatan->Upload->KeepFile) {
                        $oldFiles = EmptyValue($this->bak_kegiatan->Upload->DbValue) ? [] : [$this->bak_kegiatan->htmlDecode($this->bak_kegiatan->Upload->DbValue)];
                        if (!EmptyValue($this->bak_kegiatan->Upload->FileName)) {
                            $newFiles = [$this->bak_kegiatan->Upload->FileName];
                            $newFiles2 = [$this->bak_kegiatan->htmlDecode($rsnew['bak_kegiatan'])];
                            $newFileCount = count($newFiles);
                            for ($i = 0; $i < $newFileCount; $i++) {
                                if ($newFiles[$i] != "") {
                                    $file = UploadTempPath($this->bak_kegiatan, $this->bak_kegiatan->Upload->Index) . $newFiles[$i];
                                    if (file_exists($file)) {
                                        if (@$newFiles2[$i] != "") { // Use correct file name
                                            $newFiles[$i] = $newFiles2[$i];
                                        }
                                        if (!$this->bak_kegiatan->Upload->SaveToFile($newFiles[$i], true, $i)) { // Just replace
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
                                    @unlink($this->bak_kegiatan->oldPhysicalUploadPath() . $oldFile);
                                }
                            }
                        }
                    }
                    if ($this->softcopy_rka->Visible && !$this->softcopy_rka->Upload->KeepFile) {
                        $oldFiles = EmptyValue($this->softcopy_rka->Upload->DbValue) ? [] : [$this->softcopy_rka->htmlDecode($this->softcopy_rka->Upload->DbValue)];
                        if (!EmptyValue($this->softcopy_rka->Upload->FileName)) {
                            $newFiles = [$this->softcopy_rka->Upload->FileName];
                            $newFiles2 = [$this->softcopy_rka->htmlDecode($rsnew['softcopy_rka'])];
                            $newFileCount = count($newFiles);
                            for ($i = 0; $i < $newFileCount; $i++) {
                                if ($newFiles[$i] != "") {
                                    $file = UploadTempPath($this->softcopy_rka, $this->softcopy_rka->Upload->Index) . $newFiles[$i];
                                    if (file_exists($file)) {
                                        if (@$newFiles2[$i] != "") { // Use correct file name
                                            $newFiles[$i] = $newFiles2[$i];
                                        }
                                        if (!$this->softcopy_rka->Upload->SaveToFile($newFiles[$i], true, $i)) { // Just replace
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
                                    @unlink($this->softcopy_rka->oldPhysicalUploadPath() . $oldFile);
                                }
                            }
                        }
                    }
                    if ($this->otsus->Visible && !$this->otsus->Upload->KeepFile) {
                        $oldFiles = EmptyValue($this->otsus->Upload->DbValue) ? [] : [$this->otsus->htmlDecode($this->otsus->Upload->DbValue)];
                        if (!EmptyValue($this->otsus->Upload->FileName)) {
                            $newFiles = [$this->otsus->Upload->FileName];
                            $newFiles2 = [$this->otsus->htmlDecode($rsnew['otsus'])];
                            $newFileCount = count($newFiles);
                            for ($i = 0; $i < $newFileCount; $i++) {
                                if ($newFiles[$i] != "") {
                                    $file = UploadTempPath($this->otsus, $this->otsus->Upload->Index) . $newFiles[$i];
                                    if (file_exists($file)) {
                                        if (@$newFiles2[$i] != "") { // Use correct file name
                                            $newFiles[$i] = $newFiles2[$i];
                                        }
                                        if (!$this->otsus->Upload->SaveToFile($newFiles[$i], true, $i)) { // Just replace
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
                                    @unlink($this->otsus->oldPhysicalUploadPath() . $oldFile);
                                }
                            }
                        }
                    }
                    if ($this->qanun_perbup->Visible && !$this->qanun_perbup->Upload->KeepFile) {
                        $oldFiles = EmptyValue($this->qanun_perbup->Upload->DbValue) ? [] : [$this->qanun_perbup->htmlDecode($this->qanun_perbup->Upload->DbValue)];
                        if (!EmptyValue($this->qanun_perbup->Upload->FileName)) {
                            $newFiles = [$this->qanun_perbup->Upload->FileName];
                            $newFiles2 = [$this->qanun_perbup->htmlDecode($rsnew['qanun_perbup'])];
                            $newFileCount = count($newFiles);
                            for ($i = 0; $i < $newFileCount; $i++) {
                                if ($newFiles[$i] != "") {
                                    $file = UploadTempPath($this->qanun_perbup, $this->qanun_perbup->Upload->Index) . $newFiles[$i];
                                    if (file_exists($file)) {
                                        if (@$newFiles2[$i] != "") { // Use correct file name
                                            $newFiles[$i] = $newFiles2[$i];
                                        }
                                        if (!$this->qanun_perbup->Upload->SaveToFile($newFiles[$i], true, $i)) { // Just replace
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
                                    @unlink($this->qanun_perbup->oldPhysicalUploadPath() . $oldFile);
                                }
                            }
                        }
                    }
                    if ($this->tindak_apbkp->Visible && !$this->tindak_apbkp->Upload->KeepFile) {
                        $oldFiles = EmptyValue($this->tindak_apbkp->Upload->DbValue) ? [] : [$this->tindak_apbkp->htmlDecode($this->tindak_apbkp->Upload->DbValue)];
                        if (!EmptyValue($this->tindak_apbkp->Upload->FileName)) {
                            $newFiles = [$this->tindak_apbkp->Upload->FileName];
                            $newFiles2 = [$this->tindak_apbkp->htmlDecode($rsnew['tindak_apbkp'])];
                            $newFileCount = count($newFiles);
                            for ($i = 0; $i < $newFileCount; $i++) {
                                if ($newFiles[$i] != "") {
                                    $file = UploadTempPath($this->tindak_apbkp, $this->tindak_apbkp->Upload->Index) . $newFiles[$i];
                                    if (file_exists($file)) {
                                        if (@$newFiles2[$i] != "") { // Use correct file name
                                            $newFiles[$i] = $newFiles2[$i];
                                        }
                                        if (!$this->tindak_apbkp->Upload->SaveToFile($newFiles[$i], true, $i)) { // Just replace
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
                                    @unlink($this->tindak_apbkp->oldPhysicalUploadPath() . $oldFile);
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
            // surat_pengantar
            CleanUploadTempPath($this->surat_pengantar, $this->surat_pengantar->Upload->Index);

            // rpjmd
            CleanUploadTempPath($this->rpjmd, $this->rpjmd->Upload->Index);

            // rkpk
            CleanUploadTempPath($this->rkpk, $this->rkpk->Upload->Index);

            // skd_rkuappas
            CleanUploadTempPath($this->skd_rkuappas, $this->skd_rkuappas->Upload->Index);

            // kua
            CleanUploadTempPath($this->kua, $this->kua->Upload->Index);

            // ppas
            CleanUploadTempPath($this->ppas, $this->ppas->Upload->Index);

            // skd_rqanun
            CleanUploadTempPath($this->skd_rqanun, $this->skd_rqanun->Upload->Index);

            // nota_keuangan
            CleanUploadTempPath($this->nota_keuangan, $this->nota_keuangan->Upload->Index);

            // pengantar_nota
            CleanUploadTempPath($this->pengantar_nota, $this->pengantar_nota->Upload->Index);

            // risalah_sidang
            CleanUploadTempPath($this->risalah_sidang, $this->risalah_sidang->Upload->Index);

            // bap_apbk
            CleanUploadTempPath($this->bap_apbk, $this->bap_apbk->Upload->Index);

            // rq_apbk
            CleanUploadTempPath($this->rq_apbk, $this->rq_apbk->Upload->Index);

            // rp_penjabaran
            CleanUploadTempPath($this->rp_penjabaran, $this->rp_penjabaran->Upload->Index);

            // jadwal_proses
            CleanUploadTempPath($this->jadwal_proses, $this->jadwal_proses->Upload->Index);

            // sinkron_kebijakan
            CleanUploadTempPath($this->sinkron_kebijakan, $this->sinkron_kebijakan->Upload->Index);

            // konsistensi_program
            CleanUploadTempPath($this->konsistensi_program, $this->konsistensi_program->Upload->Index);

            // alokasi_pendidikan
            CleanUploadTempPath($this->alokasi_pendidikan, $this->alokasi_pendidikan->Upload->Index);

            // alokasi_kesehatan
            CleanUploadTempPath($this->alokasi_kesehatan, $this->alokasi_kesehatan->Upload->Index);

            // alokasi_belanja
            CleanUploadTempPath($this->alokasi_belanja, $this->alokasi_belanja->Upload->Index);

            // bak_kegiatan
            CleanUploadTempPath($this->bak_kegiatan, $this->bak_kegiatan->Upload->Index);

            // softcopy_rka
            CleanUploadTempPath($this->softcopy_rka, $this->softcopy_rka->Upload->Index);

            // otsus
            CleanUploadTempPath($this->otsus, $this->otsus->Upload->Index);

            // qanun_perbup
            CleanUploadTempPath($this->qanun_perbup, $this->qanun_perbup->Upload->Index);

            // tindak_apbkp
            CleanUploadTempPath($this->tindak_apbkp, $this->tindak_apbkp->Upload->Index);
        }

        // Write JSON for API request
        if (IsApi() && $editRow) {
            $row = $this->getRecordsFromRecordset([$rsnew], true);
            WriteJson(["success" => true, $this->TableVar => $row]);
        }
        return $editRow;
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
        $pageId = "edit";
        $Breadcrumb->add("edit", $pageId, $url);
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
}
