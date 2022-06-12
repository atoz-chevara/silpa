<?php

namespace PHPMaker2021\silpa;

use Doctrine\DBAL\ParameterType;

/**
 * Page class
 */
class Pertanggungjawaban2022Edit extends Pertanggungjawaban2022
{
    use MessagesTrait;

    // Page ID
    public $PageID = "edit";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'pertanggungjawaban2022';

    // Page object name
    public $PageObjName = "Pertanggungjawaban2022Edit";

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
        $this->idd_evaluasi->Visible = false;
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
        $this->tanggal_upload->Visible = false;
        $this->tanggal_update->Visible = false;
        $this->idd_user->Visible = false;
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
                    $this->terminate("pertanggungjawaban2022list"); // No matching record, return to list
                    return;
                }
                break;
            case "update": // Update
                $returnUrl = $this->getReturnUrl();
                if (GetPageName($returnUrl) == "pertanggungjawaban2022list") {
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
        if (!$this->idd_evaluasi->IsDetailKey) {
            $this->idd_evaluasi->setFormValue($val);
        }
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
        $this->getUploadFiles(); // Get upload files
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->idd_evaluasi->CurrentValue = $this->idd_evaluasi->FormValue;
        $this->kd_satker->CurrentValue = $this->kd_satker->FormValue;
        $this->idd_tahapan->CurrentValue = $this->idd_tahapan->FormValue;
        $this->tahun_anggaran->CurrentValue = $this->tahun_anggaran->FormValue;
        $this->status->CurrentValue = $this->status->FormValue;
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
        $row = [];
        $row['idd_evaluasi'] = null;
        $row['kd_satker'] = null;
        $row['idd_tahapan'] = null;
        $row['tahun_anggaran'] = null;
        $row['surat_pengantar'] = null;
        $row['skd_rqanunpert'] = null;
        $row['rqanun_apbkpert'] = null;
        $row['rperbup_apbkpert'] = null;
        $row['pbkdd_apbkpert'] = null;
        $row['risalah_sidang'] = null;
        $row['absen_peserta'] = null;
        $row['neraca'] = null;
        $row['lra'] = null;
        $row['calk'] = null;
        $row['lo'] = null;
        $row['lpe'] = null;
        $row['lpsal'] = null;
        $row['lak'] = null;
        $row['laporan_pemeriksaan'] = null;
        $row['status'] = null;
        $row['tanggal_upload'] = null;
        $row['tanggal_update'] = null;
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

            // surat_pengantar
            $this->surat_pengantar->LinkCustomAttributes = "";
            $this->surat_pengantar->UploadPath = "files/evaluasi/2022/pertanggungjawaban";
            if (!EmptyValue($this->surat_pengantar->Upload->DbValue)) {
                $this->surat_pengantar->HrefValue = GetFileUploadUrl($this->surat_pengantar, $this->surat_pengantar->htmlDecode($this->surat_pengantar->Upload->DbValue)); // Add prefix/suffix
                $this->surat_pengantar->LinkAttrs["target"] = "_blank"; // Add target
                if ($this->isExport()) {
                    $this->surat_pengantar->HrefValue = FullUrl($this->surat_pengantar->HrefValue, "href");
                }
            } else {
                $this->surat_pengantar->HrefValue = "";
            }
            $this->surat_pengantar->ExportHrefValue = $this->surat_pengantar->UploadPath . $this->surat_pengantar->Upload->DbValue;
            $this->surat_pengantar->TooltipValue = "";

            // skd_rqanunpert
            $this->skd_rqanunpert->LinkCustomAttributes = "";
            $this->skd_rqanunpert->UploadPath = "files/evaluasi/2022/pertanggungjawaban";
            if (!EmptyValue($this->skd_rqanunpert->Upload->DbValue)) {
                $this->skd_rqanunpert->HrefValue = GetFileUploadUrl($this->skd_rqanunpert, $this->skd_rqanunpert->htmlDecode($this->skd_rqanunpert->Upload->DbValue)); // Add prefix/suffix
                $this->skd_rqanunpert->LinkAttrs["target"] = "_blank"; // Add target
                if ($this->isExport()) {
                    $this->skd_rqanunpert->HrefValue = FullUrl($this->skd_rqanunpert->HrefValue, "href");
                }
            } else {
                $this->skd_rqanunpert->HrefValue = "";
            }
            $this->skd_rqanunpert->ExportHrefValue = $this->skd_rqanunpert->UploadPath . $this->skd_rqanunpert->Upload->DbValue;
            $this->skd_rqanunpert->TooltipValue = "";

            // rqanun_apbkpert
            $this->rqanun_apbkpert->LinkCustomAttributes = "";
            $this->rqanun_apbkpert->UploadPath = "files/evaluasi/2022/pertanggungjawaban";
            if (!EmptyValue($this->rqanun_apbkpert->Upload->DbValue)) {
                $this->rqanun_apbkpert->HrefValue = GetFileUploadUrl($this->rqanun_apbkpert, $this->rqanun_apbkpert->htmlDecode($this->rqanun_apbkpert->Upload->DbValue)); // Add prefix/suffix
                $this->rqanun_apbkpert->LinkAttrs["target"] = "_blank"; // Add target
                if ($this->isExport()) {
                    $this->rqanun_apbkpert->HrefValue = FullUrl($this->rqanun_apbkpert->HrefValue, "href");
                }
            } else {
                $this->rqanun_apbkpert->HrefValue = "";
            }
            $this->rqanun_apbkpert->ExportHrefValue = $this->rqanun_apbkpert->UploadPath . $this->rqanun_apbkpert->Upload->DbValue;
            $this->rqanun_apbkpert->TooltipValue = "";

            // rperbup_apbkpert
            $this->rperbup_apbkpert->LinkCustomAttributes = "";
            $this->rperbup_apbkpert->UploadPath = "files/evaluasi/2022/pertanggungjawaban";
            if (!EmptyValue($this->rperbup_apbkpert->Upload->DbValue)) {
                $this->rperbup_apbkpert->HrefValue = GetFileUploadUrl($this->rperbup_apbkpert, $this->rperbup_apbkpert->htmlDecode($this->rperbup_apbkpert->Upload->DbValue)); // Add prefix/suffix
                $this->rperbup_apbkpert->LinkAttrs["target"] = "_blank"; // Add target
                if ($this->isExport()) {
                    $this->rperbup_apbkpert->HrefValue = FullUrl($this->rperbup_apbkpert->HrefValue, "href");
                }
            } else {
                $this->rperbup_apbkpert->HrefValue = "";
            }
            $this->rperbup_apbkpert->ExportHrefValue = $this->rperbup_apbkpert->UploadPath . $this->rperbup_apbkpert->Upload->DbValue;
            $this->rperbup_apbkpert->TooltipValue = "";

            // pbkdd_apbkpert
            $this->pbkdd_apbkpert->LinkCustomAttributes = "";
            $this->pbkdd_apbkpert->UploadPath = "files/evaluasi/2022/pertanggungjawaban";
            if (!EmptyValue($this->pbkdd_apbkpert->Upload->DbValue)) {
                $this->pbkdd_apbkpert->HrefValue = GetFileUploadUrl($this->pbkdd_apbkpert, $this->pbkdd_apbkpert->htmlDecode($this->pbkdd_apbkpert->Upload->DbValue)); // Add prefix/suffix
                $this->pbkdd_apbkpert->LinkAttrs["target"] = "_blank"; // Add target
                if ($this->isExport()) {
                    $this->pbkdd_apbkpert->HrefValue = FullUrl($this->pbkdd_apbkpert->HrefValue, "href");
                }
            } else {
                $this->pbkdd_apbkpert->HrefValue = "";
            }
            $this->pbkdd_apbkpert->ExportHrefValue = $this->pbkdd_apbkpert->UploadPath . $this->pbkdd_apbkpert->Upload->DbValue;
            $this->pbkdd_apbkpert->TooltipValue = "";

            // risalah_sidang
            $this->risalah_sidang->LinkCustomAttributes = "";
            $this->risalah_sidang->UploadPath = "files/evaluasi/2022/pertanggungjawaban";
            if (!EmptyValue($this->risalah_sidang->Upload->DbValue)) {
                $this->risalah_sidang->HrefValue = GetFileUploadUrl($this->risalah_sidang, $this->risalah_sidang->htmlDecode($this->risalah_sidang->Upload->DbValue)); // Add prefix/suffix
                $this->risalah_sidang->LinkAttrs["target"] = "_blank"; // Add target
                if ($this->isExport()) {
                    $this->risalah_sidang->HrefValue = FullUrl($this->risalah_sidang->HrefValue, "href");
                }
            } else {
                $this->risalah_sidang->HrefValue = "";
            }
            $this->risalah_sidang->ExportHrefValue = $this->risalah_sidang->UploadPath . $this->risalah_sidang->Upload->DbValue;
            $this->risalah_sidang->TooltipValue = "";

            // absen_peserta
            $this->absen_peserta->LinkCustomAttributes = "";
            $this->absen_peserta->UploadPath = "files/evaluasi/2022/pertanggungjawaban";
            if (!EmptyValue($this->absen_peserta->Upload->DbValue)) {
                $this->absen_peserta->HrefValue = GetFileUploadUrl($this->absen_peserta, $this->absen_peserta->htmlDecode($this->absen_peserta->Upload->DbValue)); // Add prefix/suffix
                $this->absen_peserta->LinkAttrs["target"] = "_blank"; // Add target
                if ($this->isExport()) {
                    $this->absen_peserta->HrefValue = FullUrl($this->absen_peserta->HrefValue, "href");
                }
            } else {
                $this->absen_peserta->HrefValue = "";
            }
            $this->absen_peserta->ExportHrefValue = $this->absen_peserta->UploadPath . $this->absen_peserta->Upload->DbValue;
            $this->absen_peserta->TooltipValue = "";

            // neraca
            $this->neraca->LinkCustomAttributes = "";
            $this->neraca->UploadPath = "files/evaluasi/2022/pertanggungjawaban";
            if (!EmptyValue($this->neraca->Upload->DbValue)) {
                $this->neraca->HrefValue = GetFileUploadUrl($this->neraca, $this->neraca->htmlDecode($this->neraca->Upload->DbValue)); // Add prefix/suffix
                $this->neraca->LinkAttrs["target"] = "_blank"; // Add target
                if ($this->isExport()) {
                    $this->neraca->HrefValue = FullUrl($this->neraca->HrefValue, "href");
                }
            } else {
                $this->neraca->HrefValue = "";
            }
            $this->neraca->ExportHrefValue = $this->neraca->UploadPath . $this->neraca->Upload->DbValue;
            $this->neraca->TooltipValue = "";

            // lra
            $this->lra->LinkCustomAttributes = "";
            $this->lra->UploadPath = "files/evaluasi/2022/pertanggungjawaban";
            if (!EmptyValue($this->lra->Upload->DbValue)) {
                $this->lra->HrefValue = GetFileUploadUrl($this->lra, $this->lra->htmlDecode($this->lra->Upload->DbValue)); // Add prefix/suffix
                $this->lra->LinkAttrs["target"] = "_blank"; // Add target
                if ($this->isExport()) {
                    $this->lra->HrefValue = FullUrl($this->lra->HrefValue, "href");
                }
            } else {
                $this->lra->HrefValue = "";
            }
            $this->lra->ExportHrefValue = $this->lra->UploadPath . $this->lra->Upload->DbValue;
            $this->lra->TooltipValue = "";

            // calk
            $this->calk->LinkCustomAttributes = "";
            $this->calk->UploadPath = "files/evaluasi/2022/pertanggungjawaban";
            if (!EmptyValue($this->calk->Upload->DbValue)) {
                $this->calk->HrefValue = GetFileUploadUrl($this->calk, $this->calk->htmlDecode($this->calk->Upload->DbValue)); // Add prefix/suffix
                $this->calk->LinkAttrs["target"] = "_blank"; // Add target
                if ($this->isExport()) {
                    $this->calk->HrefValue = FullUrl($this->calk->HrefValue, "href");
                }
            } else {
                $this->calk->HrefValue = "";
            }
            $this->calk->ExportHrefValue = $this->calk->UploadPath . $this->calk->Upload->DbValue;
            $this->calk->TooltipValue = "";

            // lo
            $this->lo->LinkCustomAttributes = "";
            $this->lo->UploadPath = "files/evaluasi/2022/pertanggungjawaban";
            if (!EmptyValue($this->lo->Upload->DbValue)) {
                $this->lo->HrefValue = GetFileUploadUrl($this->lo, $this->lo->htmlDecode($this->lo->Upload->DbValue)); // Add prefix/suffix
                $this->lo->LinkAttrs["target"] = "_blank"; // Add target
                if ($this->isExport()) {
                    $this->lo->HrefValue = FullUrl($this->lo->HrefValue, "href");
                }
            } else {
                $this->lo->HrefValue = "";
            }
            $this->lo->ExportHrefValue = $this->lo->UploadPath . $this->lo->Upload->DbValue;
            $this->lo->TooltipValue = "";

            // lpe
            $this->lpe->LinkCustomAttributes = "";
            $this->lpe->UploadPath = "files/evaluasi/2022/pertanggungjawaban";
            if (!EmptyValue($this->lpe->Upload->DbValue)) {
                $this->lpe->HrefValue = GetFileUploadUrl($this->lpe, $this->lpe->htmlDecode($this->lpe->Upload->DbValue)); // Add prefix/suffix
                $this->lpe->LinkAttrs["target"] = "_blank"; // Add target
                if ($this->isExport()) {
                    $this->lpe->HrefValue = FullUrl($this->lpe->HrefValue, "href");
                }
            } else {
                $this->lpe->HrefValue = "";
            }
            $this->lpe->ExportHrefValue = $this->lpe->UploadPath . $this->lpe->Upload->DbValue;
            $this->lpe->TooltipValue = "";

            // lpsal
            $this->lpsal->LinkCustomAttributes = "";
            $this->lpsal->UploadPath = "files/evaluasi/2022/pertanggungjawaban";
            if (!EmptyValue($this->lpsal->Upload->DbValue)) {
                $this->lpsal->HrefValue = GetFileUploadUrl($this->lpsal, $this->lpsal->htmlDecode($this->lpsal->Upload->DbValue)); // Add prefix/suffix
                $this->lpsal->LinkAttrs["target"] = "_blank"; // Add target
                if ($this->isExport()) {
                    $this->lpsal->HrefValue = FullUrl($this->lpsal->HrefValue, "href");
                }
            } else {
                $this->lpsal->HrefValue = "";
            }
            $this->lpsal->ExportHrefValue = $this->lpsal->UploadPath . $this->lpsal->Upload->DbValue;
            $this->lpsal->TooltipValue = "";

            // lak
            $this->lak->LinkCustomAttributes = "";
            $this->lak->UploadPath = "files/evaluasi/2022/pertanggungjawaban";
            if (!EmptyValue($this->lak->Upload->DbValue)) {
                $this->lak->HrefValue = GetFileUploadUrl($this->lak, $this->lak->htmlDecode($this->lak->Upload->DbValue)); // Add prefix/suffix
                $this->lak->LinkAttrs["target"] = "_blank"; // Add target
                if ($this->isExport()) {
                    $this->lak->HrefValue = FullUrl($this->lak->HrefValue, "href");
                }
            } else {
                $this->lak->HrefValue = "";
            }
            $this->lak->ExportHrefValue = $this->lak->UploadPath . $this->lak->Upload->DbValue;
            $this->lak->TooltipValue = "";

            // laporan_pemeriksaan
            $this->laporan_pemeriksaan->LinkCustomAttributes = "";
            $this->laporan_pemeriksaan->UploadPath = "files/evaluasi/2022/pertanggungjawaban";
            if (!EmptyValue($this->laporan_pemeriksaan->Upload->DbValue)) {
                $this->laporan_pemeriksaan->HrefValue = GetFileUploadUrl($this->laporan_pemeriksaan, $this->laporan_pemeriksaan->htmlDecode($this->laporan_pemeriksaan->Upload->DbValue)); // Add prefix/suffix
                $this->laporan_pemeriksaan->LinkAttrs["target"] = "_blank"; // Add target
                if ($this->isExport()) {
                    $this->laporan_pemeriksaan->HrefValue = FullUrl($this->laporan_pemeriksaan->HrefValue, "href");
                }
            } else {
                $this->laporan_pemeriksaan->HrefValue = "";
            }
            $this->laporan_pemeriksaan->ExportHrefValue = $this->laporan_pemeriksaan->UploadPath . $this->laporan_pemeriksaan->Upload->DbValue;
            $this->laporan_pemeriksaan->TooltipValue = "";

            // status
            $this->status->LinkCustomAttributes = "";
            $this->status->HrefValue = "";
            $this->status->TooltipValue = "";
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

            // surat_pengantar
            $this->surat_pengantar->EditAttrs["class"] = "form-control";
            $this->surat_pengantar->EditCustomAttributes = "";
            $this->surat_pengantar->UploadPath = "files/evaluasi/2022/pertanggungjawaban";
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

            // skd_rqanunpert
            $this->skd_rqanunpert->EditAttrs["class"] = "form-control";
            $this->skd_rqanunpert->EditCustomAttributes = "";
            $this->skd_rqanunpert->UploadPath = "files/evaluasi/2022/pertanggungjawaban";
            if (!EmptyValue($this->skd_rqanunpert->Upload->DbValue)) {
                $this->skd_rqanunpert->EditValue = $this->skd_rqanunpert->Upload->DbValue;
            } else {
                $this->skd_rqanunpert->EditValue = "";
            }
            if (!EmptyValue($this->skd_rqanunpert->CurrentValue)) {
                $this->skd_rqanunpert->Upload->FileName = $this->skd_rqanunpert->CurrentValue;
            }
            if ($this->isShow()) {
                RenderUploadField($this->skd_rqanunpert);
            }

            // rqanun_apbkpert
            $this->rqanun_apbkpert->EditAttrs["class"] = "form-control";
            $this->rqanun_apbkpert->EditCustomAttributes = "";
            $this->rqanun_apbkpert->UploadPath = "files/evaluasi/2022/pertanggungjawaban";
            if (!EmptyValue($this->rqanun_apbkpert->Upload->DbValue)) {
                $this->rqanun_apbkpert->EditValue = $this->rqanun_apbkpert->Upload->DbValue;
            } else {
                $this->rqanun_apbkpert->EditValue = "";
            }
            if (!EmptyValue($this->rqanun_apbkpert->CurrentValue)) {
                $this->rqanun_apbkpert->Upload->FileName = $this->rqanun_apbkpert->CurrentValue;
            }
            if ($this->isShow()) {
                RenderUploadField($this->rqanun_apbkpert);
            }

            // rperbup_apbkpert
            $this->rperbup_apbkpert->EditAttrs["class"] = "form-control";
            $this->rperbup_apbkpert->EditCustomAttributes = "";
            $this->rperbup_apbkpert->UploadPath = "files/evaluasi/2022/pertanggungjawaban";
            if (!EmptyValue($this->rperbup_apbkpert->Upload->DbValue)) {
                $this->rperbup_apbkpert->EditValue = $this->rperbup_apbkpert->Upload->DbValue;
            } else {
                $this->rperbup_apbkpert->EditValue = "";
            }
            if (!EmptyValue($this->rperbup_apbkpert->CurrentValue)) {
                $this->rperbup_apbkpert->Upload->FileName = $this->rperbup_apbkpert->CurrentValue;
            }
            if ($this->isShow()) {
                RenderUploadField($this->rperbup_apbkpert);
            }

            // pbkdd_apbkpert
            $this->pbkdd_apbkpert->EditAttrs["class"] = "form-control";
            $this->pbkdd_apbkpert->EditCustomAttributes = "";
            $this->pbkdd_apbkpert->UploadPath = "files/evaluasi/2022/pertanggungjawaban";
            if (!EmptyValue($this->pbkdd_apbkpert->Upload->DbValue)) {
                $this->pbkdd_apbkpert->EditValue = $this->pbkdd_apbkpert->Upload->DbValue;
            } else {
                $this->pbkdd_apbkpert->EditValue = "";
            }
            if (!EmptyValue($this->pbkdd_apbkpert->CurrentValue)) {
                $this->pbkdd_apbkpert->Upload->FileName = $this->pbkdd_apbkpert->CurrentValue;
            }
            if ($this->isShow()) {
                RenderUploadField($this->pbkdd_apbkpert);
            }

            // risalah_sidang
            $this->risalah_sidang->EditAttrs["class"] = "form-control";
            $this->risalah_sidang->EditCustomAttributes = "";
            $this->risalah_sidang->UploadPath = "files/evaluasi/2022/pertanggungjawaban";
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

            // absen_peserta
            $this->absen_peserta->EditAttrs["class"] = "form-control";
            $this->absen_peserta->EditCustomAttributes = "";
            $this->absen_peserta->UploadPath = "files/evaluasi/2022/pertanggungjawaban";
            if (!EmptyValue($this->absen_peserta->Upload->DbValue)) {
                $this->absen_peserta->EditValue = $this->absen_peserta->Upload->DbValue;
            } else {
                $this->absen_peserta->EditValue = "";
            }
            if (!EmptyValue($this->absen_peserta->CurrentValue)) {
                $this->absen_peserta->Upload->FileName = $this->absen_peserta->CurrentValue;
            }
            if ($this->isShow()) {
                RenderUploadField($this->absen_peserta);
            }

            // neraca
            $this->neraca->EditAttrs["class"] = "form-control";
            $this->neraca->EditCustomAttributes = "";
            $this->neraca->UploadPath = "files/evaluasi/2022/pertanggungjawaban";
            if (!EmptyValue($this->neraca->Upload->DbValue)) {
                $this->neraca->EditValue = $this->neraca->Upload->DbValue;
            } else {
                $this->neraca->EditValue = "";
            }
            if (!EmptyValue($this->neraca->CurrentValue)) {
                $this->neraca->Upload->FileName = $this->neraca->CurrentValue;
            }
            if ($this->isShow()) {
                RenderUploadField($this->neraca);
            }

            // lra
            $this->lra->EditAttrs["class"] = "form-control";
            $this->lra->EditCustomAttributes = "";
            $this->lra->UploadPath = "files/evaluasi/2022/pertanggungjawaban";
            if (!EmptyValue($this->lra->Upload->DbValue)) {
                $this->lra->EditValue = $this->lra->Upload->DbValue;
            } else {
                $this->lra->EditValue = "";
            }
            if (!EmptyValue($this->lra->CurrentValue)) {
                $this->lra->Upload->FileName = $this->lra->CurrentValue;
            }
            if ($this->isShow()) {
                RenderUploadField($this->lra);
            }

            // calk
            $this->calk->EditAttrs["class"] = "form-control";
            $this->calk->EditCustomAttributes = "";
            $this->calk->UploadPath = "files/evaluasi/2022/pertanggungjawaban";
            if (!EmptyValue($this->calk->Upload->DbValue)) {
                $this->calk->EditValue = $this->calk->Upload->DbValue;
            } else {
                $this->calk->EditValue = "";
            }
            if (!EmptyValue($this->calk->CurrentValue)) {
                $this->calk->Upload->FileName = $this->calk->CurrentValue;
            }
            if ($this->isShow()) {
                RenderUploadField($this->calk);
            }

            // lo
            $this->lo->EditAttrs["class"] = "form-control";
            $this->lo->EditCustomAttributes = "";
            $this->lo->UploadPath = "files/evaluasi/2022/pertanggungjawaban";
            if (!EmptyValue($this->lo->Upload->DbValue)) {
                $this->lo->EditValue = $this->lo->Upload->DbValue;
            } else {
                $this->lo->EditValue = "";
            }
            if (!EmptyValue($this->lo->CurrentValue)) {
                $this->lo->Upload->FileName = $this->lo->CurrentValue;
            }
            if ($this->isShow()) {
                RenderUploadField($this->lo);
            }

            // lpe
            $this->lpe->EditAttrs["class"] = "form-control";
            $this->lpe->EditCustomAttributes = "";
            $this->lpe->UploadPath = "files/evaluasi/2022/pertanggungjawaban";
            if (!EmptyValue($this->lpe->Upload->DbValue)) {
                $this->lpe->EditValue = $this->lpe->Upload->DbValue;
            } else {
                $this->lpe->EditValue = "";
            }
            if (!EmptyValue($this->lpe->CurrentValue)) {
                $this->lpe->Upload->FileName = $this->lpe->CurrentValue;
            }
            if ($this->isShow()) {
                RenderUploadField($this->lpe);
            }

            // lpsal
            $this->lpsal->EditAttrs["class"] = "form-control";
            $this->lpsal->EditCustomAttributes = "";
            $this->lpsal->UploadPath = "files/evaluasi/2022/pertanggungjawaban";
            if (!EmptyValue($this->lpsal->Upload->DbValue)) {
                $this->lpsal->EditValue = $this->lpsal->Upload->DbValue;
            } else {
                $this->lpsal->EditValue = "";
            }
            if (!EmptyValue($this->lpsal->CurrentValue)) {
                $this->lpsal->Upload->FileName = $this->lpsal->CurrentValue;
            }
            if ($this->isShow()) {
                RenderUploadField($this->lpsal);
            }

            // lak
            $this->lak->EditAttrs["class"] = "form-control";
            $this->lak->EditCustomAttributes = "";
            $this->lak->UploadPath = "files/evaluasi/2022/pertanggungjawaban";
            if (!EmptyValue($this->lak->Upload->DbValue)) {
                $this->lak->EditValue = $this->lak->Upload->DbValue;
            } else {
                $this->lak->EditValue = "";
            }
            if (!EmptyValue($this->lak->CurrentValue)) {
                $this->lak->Upload->FileName = $this->lak->CurrentValue;
            }
            if ($this->isShow()) {
                RenderUploadField($this->lak);
            }

            // laporan_pemeriksaan
            $this->laporan_pemeriksaan->EditAttrs["class"] = "form-control";
            $this->laporan_pemeriksaan->EditCustomAttributes = "";
            $this->laporan_pemeriksaan->UploadPath = "files/evaluasi/2022/pertanggungjawaban";
            if (!EmptyValue($this->laporan_pemeriksaan->Upload->DbValue)) {
                $this->laporan_pemeriksaan->EditValue = $this->laporan_pemeriksaan->Upload->DbValue;
            } else {
                $this->laporan_pemeriksaan->EditValue = "";
            }
            if (!EmptyValue($this->laporan_pemeriksaan->CurrentValue)) {
                $this->laporan_pemeriksaan->Upload->FileName = $this->laporan_pemeriksaan->CurrentValue;
            }
            if ($this->isShow()) {
                RenderUploadField($this->laporan_pemeriksaan);
            }

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

            // surat_pengantar
            $this->surat_pengantar->LinkCustomAttributes = "";
            $this->surat_pengantar->UploadPath = "files/evaluasi/2022/pertanggungjawaban";
            if (!EmptyValue($this->surat_pengantar->Upload->DbValue)) {
                $this->surat_pengantar->HrefValue = GetFileUploadUrl($this->surat_pengantar, $this->surat_pengantar->htmlDecode($this->surat_pengantar->Upload->DbValue)); // Add prefix/suffix
                $this->surat_pengantar->LinkAttrs["target"] = "_blank"; // Add target
                if ($this->isExport()) {
                    $this->surat_pengantar->HrefValue = FullUrl($this->surat_pengantar->HrefValue, "href");
                }
            } else {
                $this->surat_pengantar->HrefValue = "";
            }
            $this->surat_pengantar->ExportHrefValue = $this->surat_pengantar->UploadPath . $this->surat_pengantar->Upload->DbValue;

            // skd_rqanunpert
            $this->skd_rqanunpert->LinkCustomAttributes = "";
            $this->skd_rqanunpert->UploadPath = "files/evaluasi/2022/pertanggungjawaban";
            if (!EmptyValue($this->skd_rqanunpert->Upload->DbValue)) {
                $this->skd_rqanunpert->HrefValue = GetFileUploadUrl($this->skd_rqanunpert, $this->skd_rqanunpert->htmlDecode($this->skd_rqanunpert->Upload->DbValue)); // Add prefix/suffix
                $this->skd_rqanunpert->LinkAttrs["target"] = "_blank"; // Add target
                if ($this->isExport()) {
                    $this->skd_rqanunpert->HrefValue = FullUrl($this->skd_rqanunpert->HrefValue, "href");
                }
            } else {
                $this->skd_rqanunpert->HrefValue = "";
            }
            $this->skd_rqanunpert->ExportHrefValue = $this->skd_rqanunpert->UploadPath . $this->skd_rqanunpert->Upload->DbValue;

            // rqanun_apbkpert
            $this->rqanun_apbkpert->LinkCustomAttributes = "";
            $this->rqanun_apbkpert->UploadPath = "files/evaluasi/2022/pertanggungjawaban";
            if (!EmptyValue($this->rqanun_apbkpert->Upload->DbValue)) {
                $this->rqanun_apbkpert->HrefValue = GetFileUploadUrl($this->rqanun_apbkpert, $this->rqanun_apbkpert->htmlDecode($this->rqanun_apbkpert->Upload->DbValue)); // Add prefix/suffix
                $this->rqanun_apbkpert->LinkAttrs["target"] = "_blank"; // Add target
                if ($this->isExport()) {
                    $this->rqanun_apbkpert->HrefValue = FullUrl($this->rqanun_apbkpert->HrefValue, "href");
                }
            } else {
                $this->rqanun_apbkpert->HrefValue = "";
            }
            $this->rqanun_apbkpert->ExportHrefValue = $this->rqanun_apbkpert->UploadPath . $this->rqanun_apbkpert->Upload->DbValue;

            // rperbup_apbkpert
            $this->rperbup_apbkpert->LinkCustomAttributes = "";
            $this->rperbup_apbkpert->UploadPath = "files/evaluasi/2022/pertanggungjawaban";
            if (!EmptyValue($this->rperbup_apbkpert->Upload->DbValue)) {
                $this->rperbup_apbkpert->HrefValue = GetFileUploadUrl($this->rperbup_apbkpert, $this->rperbup_apbkpert->htmlDecode($this->rperbup_apbkpert->Upload->DbValue)); // Add prefix/suffix
                $this->rperbup_apbkpert->LinkAttrs["target"] = "_blank"; // Add target
                if ($this->isExport()) {
                    $this->rperbup_apbkpert->HrefValue = FullUrl($this->rperbup_apbkpert->HrefValue, "href");
                }
            } else {
                $this->rperbup_apbkpert->HrefValue = "";
            }
            $this->rperbup_apbkpert->ExportHrefValue = $this->rperbup_apbkpert->UploadPath . $this->rperbup_apbkpert->Upload->DbValue;

            // pbkdd_apbkpert
            $this->pbkdd_apbkpert->LinkCustomAttributes = "";
            $this->pbkdd_apbkpert->UploadPath = "files/evaluasi/2022/pertanggungjawaban";
            if (!EmptyValue($this->pbkdd_apbkpert->Upload->DbValue)) {
                $this->pbkdd_apbkpert->HrefValue = GetFileUploadUrl($this->pbkdd_apbkpert, $this->pbkdd_apbkpert->htmlDecode($this->pbkdd_apbkpert->Upload->DbValue)); // Add prefix/suffix
                $this->pbkdd_apbkpert->LinkAttrs["target"] = "_blank"; // Add target
                if ($this->isExport()) {
                    $this->pbkdd_apbkpert->HrefValue = FullUrl($this->pbkdd_apbkpert->HrefValue, "href");
                }
            } else {
                $this->pbkdd_apbkpert->HrefValue = "";
            }
            $this->pbkdd_apbkpert->ExportHrefValue = $this->pbkdd_apbkpert->UploadPath . $this->pbkdd_apbkpert->Upload->DbValue;

            // risalah_sidang
            $this->risalah_sidang->LinkCustomAttributes = "";
            $this->risalah_sidang->UploadPath = "files/evaluasi/2022/pertanggungjawaban";
            if (!EmptyValue($this->risalah_sidang->Upload->DbValue)) {
                $this->risalah_sidang->HrefValue = GetFileUploadUrl($this->risalah_sidang, $this->risalah_sidang->htmlDecode($this->risalah_sidang->Upload->DbValue)); // Add prefix/suffix
                $this->risalah_sidang->LinkAttrs["target"] = "_blank"; // Add target
                if ($this->isExport()) {
                    $this->risalah_sidang->HrefValue = FullUrl($this->risalah_sidang->HrefValue, "href");
                }
            } else {
                $this->risalah_sidang->HrefValue = "";
            }
            $this->risalah_sidang->ExportHrefValue = $this->risalah_sidang->UploadPath . $this->risalah_sidang->Upload->DbValue;

            // absen_peserta
            $this->absen_peserta->LinkCustomAttributes = "";
            $this->absen_peserta->UploadPath = "files/evaluasi/2022/pertanggungjawaban";
            if (!EmptyValue($this->absen_peserta->Upload->DbValue)) {
                $this->absen_peserta->HrefValue = GetFileUploadUrl($this->absen_peserta, $this->absen_peserta->htmlDecode($this->absen_peserta->Upload->DbValue)); // Add prefix/suffix
                $this->absen_peserta->LinkAttrs["target"] = "_blank"; // Add target
                if ($this->isExport()) {
                    $this->absen_peserta->HrefValue = FullUrl($this->absen_peserta->HrefValue, "href");
                }
            } else {
                $this->absen_peserta->HrefValue = "";
            }
            $this->absen_peserta->ExportHrefValue = $this->absen_peserta->UploadPath . $this->absen_peserta->Upload->DbValue;

            // neraca
            $this->neraca->LinkCustomAttributes = "";
            $this->neraca->UploadPath = "files/evaluasi/2022/pertanggungjawaban";
            if (!EmptyValue($this->neraca->Upload->DbValue)) {
                $this->neraca->HrefValue = GetFileUploadUrl($this->neraca, $this->neraca->htmlDecode($this->neraca->Upload->DbValue)); // Add prefix/suffix
                $this->neraca->LinkAttrs["target"] = "_blank"; // Add target
                if ($this->isExport()) {
                    $this->neraca->HrefValue = FullUrl($this->neraca->HrefValue, "href");
                }
            } else {
                $this->neraca->HrefValue = "";
            }
            $this->neraca->ExportHrefValue = $this->neraca->UploadPath . $this->neraca->Upload->DbValue;

            // lra
            $this->lra->LinkCustomAttributes = "";
            $this->lra->UploadPath = "files/evaluasi/2022/pertanggungjawaban";
            if (!EmptyValue($this->lra->Upload->DbValue)) {
                $this->lra->HrefValue = GetFileUploadUrl($this->lra, $this->lra->htmlDecode($this->lra->Upload->DbValue)); // Add prefix/suffix
                $this->lra->LinkAttrs["target"] = "_blank"; // Add target
                if ($this->isExport()) {
                    $this->lra->HrefValue = FullUrl($this->lra->HrefValue, "href");
                }
            } else {
                $this->lra->HrefValue = "";
            }
            $this->lra->ExportHrefValue = $this->lra->UploadPath . $this->lra->Upload->DbValue;

            // calk
            $this->calk->LinkCustomAttributes = "";
            $this->calk->UploadPath = "files/evaluasi/2022/pertanggungjawaban";
            if (!EmptyValue($this->calk->Upload->DbValue)) {
                $this->calk->HrefValue = GetFileUploadUrl($this->calk, $this->calk->htmlDecode($this->calk->Upload->DbValue)); // Add prefix/suffix
                $this->calk->LinkAttrs["target"] = "_blank"; // Add target
                if ($this->isExport()) {
                    $this->calk->HrefValue = FullUrl($this->calk->HrefValue, "href");
                }
            } else {
                $this->calk->HrefValue = "";
            }
            $this->calk->ExportHrefValue = $this->calk->UploadPath . $this->calk->Upload->DbValue;

            // lo
            $this->lo->LinkCustomAttributes = "";
            $this->lo->UploadPath = "files/evaluasi/2022/pertanggungjawaban";
            if (!EmptyValue($this->lo->Upload->DbValue)) {
                $this->lo->HrefValue = GetFileUploadUrl($this->lo, $this->lo->htmlDecode($this->lo->Upload->DbValue)); // Add prefix/suffix
                $this->lo->LinkAttrs["target"] = "_blank"; // Add target
                if ($this->isExport()) {
                    $this->lo->HrefValue = FullUrl($this->lo->HrefValue, "href");
                }
            } else {
                $this->lo->HrefValue = "";
            }
            $this->lo->ExportHrefValue = $this->lo->UploadPath . $this->lo->Upload->DbValue;

            // lpe
            $this->lpe->LinkCustomAttributes = "";
            $this->lpe->UploadPath = "files/evaluasi/2022/pertanggungjawaban";
            if (!EmptyValue($this->lpe->Upload->DbValue)) {
                $this->lpe->HrefValue = GetFileUploadUrl($this->lpe, $this->lpe->htmlDecode($this->lpe->Upload->DbValue)); // Add prefix/suffix
                $this->lpe->LinkAttrs["target"] = "_blank"; // Add target
                if ($this->isExport()) {
                    $this->lpe->HrefValue = FullUrl($this->lpe->HrefValue, "href");
                }
            } else {
                $this->lpe->HrefValue = "";
            }
            $this->lpe->ExportHrefValue = $this->lpe->UploadPath . $this->lpe->Upload->DbValue;

            // lpsal
            $this->lpsal->LinkCustomAttributes = "";
            $this->lpsal->UploadPath = "files/evaluasi/2022/pertanggungjawaban";
            if (!EmptyValue($this->lpsal->Upload->DbValue)) {
                $this->lpsal->HrefValue = GetFileUploadUrl($this->lpsal, $this->lpsal->htmlDecode($this->lpsal->Upload->DbValue)); // Add prefix/suffix
                $this->lpsal->LinkAttrs["target"] = "_blank"; // Add target
                if ($this->isExport()) {
                    $this->lpsal->HrefValue = FullUrl($this->lpsal->HrefValue, "href");
                }
            } else {
                $this->lpsal->HrefValue = "";
            }
            $this->lpsal->ExportHrefValue = $this->lpsal->UploadPath . $this->lpsal->Upload->DbValue;

            // lak
            $this->lak->LinkCustomAttributes = "";
            $this->lak->UploadPath = "files/evaluasi/2022/pertanggungjawaban";
            if (!EmptyValue($this->lak->Upload->DbValue)) {
                $this->lak->HrefValue = GetFileUploadUrl($this->lak, $this->lak->htmlDecode($this->lak->Upload->DbValue)); // Add prefix/suffix
                $this->lak->LinkAttrs["target"] = "_blank"; // Add target
                if ($this->isExport()) {
                    $this->lak->HrefValue = FullUrl($this->lak->HrefValue, "href");
                }
            } else {
                $this->lak->HrefValue = "";
            }
            $this->lak->ExportHrefValue = $this->lak->UploadPath . $this->lak->Upload->DbValue;

            // laporan_pemeriksaan
            $this->laporan_pemeriksaan->LinkCustomAttributes = "";
            $this->laporan_pemeriksaan->UploadPath = "files/evaluasi/2022/pertanggungjawaban";
            if (!EmptyValue($this->laporan_pemeriksaan->Upload->DbValue)) {
                $this->laporan_pemeriksaan->HrefValue = GetFileUploadUrl($this->laporan_pemeriksaan, $this->laporan_pemeriksaan->htmlDecode($this->laporan_pemeriksaan->Upload->DbValue)); // Add prefix/suffix
                $this->laporan_pemeriksaan->LinkAttrs["target"] = "_blank"; // Add target
                if ($this->isExport()) {
                    $this->laporan_pemeriksaan->HrefValue = FullUrl($this->laporan_pemeriksaan->HrefValue, "href");
                }
            } else {
                $this->laporan_pemeriksaan->HrefValue = "";
            }
            $this->laporan_pemeriksaan->ExportHrefValue = $this->laporan_pemeriksaan->UploadPath . $this->laporan_pemeriksaan->Upload->DbValue;

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

            // surat_pengantar
            if ($this->surat_pengantar->Visible && !$this->surat_pengantar->ReadOnly && !$this->surat_pengantar->Upload->KeepFile) {
                $this->surat_pengantar->Upload->DbValue = $rsold['surat_pengantar']; // Get original value
                if ($this->surat_pengantar->Upload->FileName == "") {
                    $rsnew['surat_pengantar'] = null;
                } else {
                    $rsnew['surat_pengantar'] = $this->surat_pengantar->Upload->FileName;
                }
            }

            // skd_rqanunpert
            if ($this->skd_rqanunpert->Visible && !$this->skd_rqanunpert->ReadOnly && !$this->skd_rqanunpert->Upload->KeepFile) {
                $this->skd_rqanunpert->Upload->DbValue = $rsold['skd_rqanunpert']; // Get original value
                if ($this->skd_rqanunpert->Upload->FileName == "") {
                    $rsnew['skd_rqanunpert'] = null;
                } else {
                    $rsnew['skd_rqanunpert'] = $this->skd_rqanunpert->Upload->FileName;
                }
            }

            // rqanun_apbkpert
            if ($this->rqanun_apbkpert->Visible && !$this->rqanun_apbkpert->ReadOnly && !$this->rqanun_apbkpert->Upload->KeepFile) {
                $this->rqanun_apbkpert->Upload->DbValue = $rsold['rqanun_apbkpert']; // Get original value
                if ($this->rqanun_apbkpert->Upload->FileName == "") {
                    $rsnew['rqanun_apbkpert'] = null;
                } else {
                    $rsnew['rqanun_apbkpert'] = $this->rqanun_apbkpert->Upload->FileName;
                }
            }

            // rperbup_apbkpert
            if ($this->rperbup_apbkpert->Visible && !$this->rperbup_apbkpert->ReadOnly && !$this->rperbup_apbkpert->Upload->KeepFile) {
                $this->rperbup_apbkpert->Upload->DbValue = $rsold['rperbup_apbkpert']; // Get original value
                if ($this->rperbup_apbkpert->Upload->FileName == "") {
                    $rsnew['rperbup_apbkpert'] = null;
                } else {
                    $rsnew['rperbup_apbkpert'] = $this->rperbup_apbkpert->Upload->FileName;
                }
            }

            // pbkdd_apbkpert
            if ($this->pbkdd_apbkpert->Visible && !$this->pbkdd_apbkpert->ReadOnly && !$this->pbkdd_apbkpert->Upload->KeepFile) {
                $this->pbkdd_apbkpert->Upload->DbValue = $rsold['pbkdd_apbkpert']; // Get original value
                if ($this->pbkdd_apbkpert->Upload->FileName == "") {
                    $rsnew['pbkdd_apbkpert'] = null;
                } else {
                    $rsnew['pbkdd_apbkpert'] = $this->pbkdd_apbkpert->Upload->FileName;
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

            // absen_peserta
            if ($this->absen_peserta->Visible && !$this->absen_peserta->ReadOnly && !$this->absen_peserta->Upload->KeepFile) {
                $this->absen_peserta->Upload->DbValue = $rsold['absen_peserta']; // Get original value
                if ($this->absen_peserta->Upload->FileName == "") {
                    $rsnew['absen_peserta'] = null;
                } else {
                    $rsnew['absen_peserta'] = $this->absen_peserta->Upload->FileName;
                }
            }

            // neraca
            if ($this->neraca->Visible && !$this->neraca->ReadOnly && !$this->neraca->Upload->KeepFile) {
                $this->neraca->Upload->DbValue = $rsold['neraca']; // Get original value
                if ($this->neraca->Upload->FileName == "") {
                    $rsnew['neraca'] = null;
                } else {
                    $rsnew['neraca'] = $this->neraca->Upload->FileName;
                }
            }

            // lra
            if ($this->lra->Visible && !$this->lra->ReadOnly && !$this->lra->Upload->KeepFile) {
                $this->lra->Upload->DbValue = $rsold['lra']; // Get original value
                if ($this->lra->Upload->FileName == "") {
                    $rsnew['lra'] = null;
                } else {
                    $rsnew['lra'] = $this->lra->Upload->FileName;
                }
            }

            // calk
            if ($this->calk->Visible && !$this->calk->ReadOnly && !$this->calk->Upload->KeepFile) {
                $this->calk->Upload->DbValue = $rsold['calk']; // Get original value
                if ($this->calk->Upload->FileName == "") {
                    $rsnew['calk'] = null;
                } else {
                    $rsnew['calk'] = $this->calk->Upload->FileName;
                }
            }

            // lo
            if ($this->lo->Visible && !$this->lo->ReadOnly && !$this->lo->Upload->KeepFile) {
                $this->lo->Upload->DbValue = $rsold['lo']; // Get original value
                if ($this->lo->Upload->FileName == "") {
                    $rsnew['lo'] = null;
                } else {
                    $rsnew['lo'] = $this->lo->Upload->FileName;
                }
            }

            // lpe
            if ($this->lpe->Visible && !$this->lpe->ReadOnly && !$this->lpe->Upload->KeepFile) {
                $this->lpe->Upload->DbValue = $rsold['lpe']; // Get original value
                if ($this->lpe->Upload->FileName == "") {
                    $rsnew['lpe'] = null;
                } else {
                    $rsnew['lpe'] = $this->lpe->Upload->FileName;
                }
            }

            // lpsal
            if ($this->lpsal->Visible && !$this->lpsal->ReadOnly && !$this->lpsal->Upload->KeepFile) {
                $this->lpsal->Upload->DbValue = $rsold['lpsal']; // Get original value
                if ($this->lpsal->Upload->FileName == "") {
                    $rsnew['lpsal'] = null;
                } else {
                    $rsnew['lpsal'] = $this->lpsal->Upload->FileName;
                }
            }

            // lak
            if ($this->lak->Visible && !$this->lak->ReadOnly && !$this->lak->Upload->KeepFile) {
                $this->lak->Upload->DbValue = $rsold['lak']; // Get original value
                if ($this->lak->Upload->FileName == "") {
                    $rsnew['lak'] = null;
                } else {
                    $rsnew['lak'] = $this->lak->Upload->FileName;
                }
            }

            // laporan_pemeriksaan
            if ($this->laporan_pemeriksaan->Visible && !$this->laporan_pemeriksaan->ReadOnly && !$this->laporan_pemeriksaan->Upload->KeepFile) {
                $this->laporan_pemeriksaan->Upload->DbValue = $rsold['laporan_pemeriksaan']; // Get original value
                if ($this->laporan_pemeriksaan->Upload->FileName == "") {
                    $rsnew['laporan_pemeriksaan'] = null;
                } else {
                    $rsnew['laporan_pemeriksaan'] = $this->laporan_pemeriksaan->Upload->FileName;
                }
            }

            // status
            $this->status->setDbValueDef($rsnew, $this->status->CurrentValue, 0, $this->status->ReadOnly);
            if ($this->surat_pengantar->Visible && !$this->surat_pengantar->Upload->KeepFile) {
                $this->surat_pengantar->UploadPath = "files/evaluasi/2022/pertanggungjawaban";
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
                    $this->surat_pengantar->setDbValueDef($rsnew, $this->surat_pengantar->Upload->FileName, null, $this->surat_pengantar->ReadOnly);
                }
            }
            if ($this->skd_rqanunpert->Visible && !$this->skd_rqanunpert->Upload->KeepFile) {
                $this->skd_rqanunpert->UploadPath = "files/evaluasi/2022/pertanggungjawaban";
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
                    $this->skd_rqanunpert->setDbValueDef($rsnew, $this->skd_rqanunpert->Upload->FileName, null, $this->skd_rqanunpert->ReadOnly);
                }
            }
            if ($this->rqanun_apbkpert->Visible && !$this->rqanun_apbkpert->Upload->KeepFile) {
                $this->rqanun_apbkpert->UploadPath = "files/evaluasi/2022/pertanggungjawaban";
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
                    $this->rqanun_apbkpert->setDbValueDef($rsnew, $this->rqanun_apbkpert->Upload->FileName, null, $this->rqanun_apbkpert->ReadOnly);
                }
            }
            if ($this->rperbup_apbkpert->Visible && !$this->rperbup_apbkpert->Upload->KeepFile) {
                $this->rperbup_apbkpert->UploadPath = "files/evaluasi/2022/pertanggungjawaban";
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
                    $this->rperbup_apbkpert->setDbValueDef($rsnew, $this->rperbup_apbkpert->Upload->FileName, null, $this->rperbup_apbkpert->ReadOnly);
                }
            }
            if ($this->pbkdd_apbkpert->Visible && !$this->pbkdd_apbkpert->Upload->KeepFile) {
                $this->pbkdd_apbkpert->UploadPath = "files/evaluasi/2022/pertanggungjawaban";
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
                    $this->pbkdd_apbkpert->setDbValueDef($rsnew, $this->pbkdd_apbkpert->Upload->FileName, null, $this->pbkdd_apbkpert->ReadOnly);
                }
            }
            if ($this->risalah_sidang->Visible && !$this->risalah_sidang->Upload->KeepFile) {
                $this->risalah_sidang->UploadPath = "files/evaluasi/2022/pertanggungjawaban";
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
                    $this->risalah_sidang->setDbValueDef($rsnew, $this->risalah_sidang->Upload->FileName, null, $this->risalah_sidang->ReadOnly);
                }
            }
            if ($this->absen_peserta->Visible && !$this->absen_peserta->Upload->KeepFile) {
                $this->absen_peserta->UploadPath = "files/evaluasi/2022/pertanggungjawaban";
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
                    $this->absen_peserta->setDbValueDef($rsnew, $this->absen_peserta->Upload->FileName, null, $this->absen_peserta->ReadOnly);
                }
            }
            if ($this->neraca->Visible && !$this->neraca->Upload->KeepFile) {
                $this->neraca->UploadPath = "files/evaluasi/2022/pertanggungjawaban";
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
                    $this->neraca->setDbValueDef($rsnew, $this->neraca->Upload->FileName, null, $this->neraca->ReadOnly);
                }
            }
            if ($this->lra->Visible && !$this->lra->Upload->KeepFile) {
                $this->lra->UploadPath = "files/evaluasi/2022/pertanggungjawaban";
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
                    $this->lra->setDbValueDef($rsnew, $this->lra->Upload->FileName, null, $this->lra->ReadOnly);
                }
            }
            if ($this->calk->Visible && !$this->calk->Upload->KeepFile) {
                $this->calk->UploadPath = "files/evaluasi/2022/pertanggungjawaban";
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
                    $this->calk->setDbValueDef($rsnew, $this->calk->Upload->FileName, null, $this->calk->ReadOnly);
                }
            }
            if ($this->lo->Visible && !$this->lo->Upload->KeepFile) {
                $this->lo->UploadPath = "files/evaluasi/2022/pertanggungjawaban";
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
                    $this->lo->setDbValueDef($rsnew, $this->lo->Upload->FileName, null, $this->lo->ReadOnly);
                }
            }
            if ($this->lpe->Visible && !$this->lpe->Upload->KeepFile) {
                $this->lpe->UploadPath = "files/evaluasi/2022/pertanggungjawaban";
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
                    $this->lpe->setDbValueDef($rsnew, $this->lpe->Upload->FileName, null, $this->lpe->ReadOnly);
                }
            }
            if ($this->lpsal->Visible && !$this->lpsal->Upload->KeepFile) {
                $this->lpsal->UploadPath = "files/evaluasi/2022/pertanggungjawaban";
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
                    $this->lpsal->setDbValueDef($rsnew, $this->lpsal->Upload->FileName, null, $this->lpsal->ReadOnly);
                }
            }
            if ($this->lak->Visible && !$this->lak->Upload->KeepFile) {
                $this->lak->UploadPath = "files/evaluasi/2022/pertanggungjawaban";
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
                    $this->lak->setDbValueDef($rsnew, $this->lak->Upload->FileName, null, $this->lak->ReadOnly);
                }
            }
            if ($this->laporan_pemeriksaan->Visible && !$this->laporan_pemeriksaan->Upload->KeepFile) {
                $this->laporan_pemeriksaan->UploadPath = "files/evaluasi/2022/pertanggungjawaban";
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
                    $this->laporan_pemeriksaan->setDbValueDef($rsnew, $this->laporan_pemeriksaan->Upload->FileName, null, $this->laporan_pemeriksaan->ReadOnly);
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
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("pertanggungjawaban2022list"), "", $this->TableVar, true);
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
