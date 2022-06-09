<?php

namespace PHPMaker2021\silpa;

use Doctrine\DBAL\ParameterType;

/**
 * Page class
 */
class ApbkAdd extends Apbk
{
    use MessagesTrait;

    // Page ID
    public $PageID = "add";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'apbk';

    // Page object name
    public $PageObjName = "ApbkAdd";

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

        // Table object (apbk)
        if (!isset($GLOBALS["apbk"]) || get_class($GLOBALS["apbk"]) == PROJECT_NAMESPACE . "apbk") {
            $GLOBALS["apbk"] = &$this;
        }

        // Page URL
        $pageUrl = $this->pageUrl();

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

            // Handle modal response
            if ($this->IsModal) { // Show as modal
                $row = ["url" => GetUrl($url), "modal" => "1"];
                $pageName = GetPageName($url);
                if ($pageName != $this->getListUrl()) { // Not List page
                    $row["caption"] = $this->getModalCaption($pageName);
                    if ($pageName == "apbkview") {
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
                    $this->terminate("apbklist"); // No matching record, return to list
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
                    if (GetPageName($returnUrl) == "apbklist") {
                        $returnUrl = $this->addMasterUrl($returnUrl); // List page, return to List page with correct master key if necessary
                    } elseif (GetPageName($returnUrl) == "apbkview") {
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
        $this->idd_wilayah->CurrentValue = null;
        $this->idd_wilayah->OldValue = $this->idd_wilayah->CurrentValue;
        $this->file_01->CurrentValue = null;
        $this->file_01->OldValue = $this->file_01->CurrentValue;
        $this->file_02->CurrentValue = null;
        $this->file_02->OldValue = $this->file_02->CurrentValue;
        $this->file_03->CurrentValue = null;
        $this->file_03->OldValue = $this->file_03->CurrentValue;
        $this->file_04->CurrentValue = null;
        $this->file_04->OldValue = $this->file_04->CurrentValue;
        $this->file_05->CurrentValue = null;
        $this->file_05->OldValue = $this->file_05->CurrentValue;
        $this->file_06->CurrentValue = null;
        $this->file_06->OldValue = $this->file_06->CurrentValue;
        $this->file_07->CurrentValue = null;
        $this->file_07->OldValue = $this->file_07->CurrentValue;
        $this->file_08->CurrentValue = null;
        $this->file_08->OldValue = $this->file_08->CurrentValue;
        $this->file_09->CurrentValue = null;
        $this->file_09->OldValue = $this->file_09->CurrentValue;
        $this->file_10->CurrentValue = null;
        $this->file_10->OldValue = $this->file_10->CurrentValue;
        $this->file_11->CurrentValue = null;
        $this->file_11->OldValue = $this->file_11->CurrentValue;
        $this->file_12->CurrentValue = null;
        $this->file_12->OldValue = $this->file_12->CurrentValue;
        $this->file_13->CurrentValue = null;
        $this->file_13->OldValue = $this->file_13->CurrentValue;
        $this->file_14->CurrentValue = null;
        $this->file_14->OldValue = $this->file_14->CurrentValue;
        $this->file_15->CurrentValue = null;
        $this->file_15->OldValue = $this->file_15->CurrentValue;
        $this->file_16->CurrentValue = null;
        $this->file_16->OldValue = $this->file_16->CurrentValue;
        $this->file_17->CurrentValue = null;
        $this->file_17->OldValue = $this->file_17->CurrentValue;
        $this->file_18->CurrentValue = null;
        $this->file_18->OldValue = $this->file_18->CurrentValue;
        $this->file_19->CurrentValue = null;
        $this->file_19->OldValue = $this->file_19->CurrentValue;
        $this->file_20->CurrentValue = null;
        $this->file_20->OldValue = $this->file_20->CurrentValue;
        $this->file_21->CurrentValue = null;
        $this->file_21->OldValue = $this->file_21->CurrentValue;
        $this->file_22->CurrentValue = null;
        $this->file_22->OldValue = $this->file_22->CurrentValue;
        $this->file_23->CurrentValue = null;
        $this->file_23->OldValue = $this->file_23->CurrentValue;
        $this->file_24->CurrentValue = null;
        $this->file_24->OldValue = $this->file_24->CurrentValue;
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

        // Check field name 'idd_wilayah' first before field var 'x_idd_wilayah'
        $val = $CurrentForm->hasValue("idd_wilayah") ? $CurrentForm->getValue("idd_wilayah") : $CurrentForm->getValue("x_idd_wilayah");
        if (!$this->idd_wilayah->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->idd_wilayah->Visible = false; // Disable update for API request
            } else {
                $this->idd_wilayah->setFormValue($val);
            }
        }

        // Check field name 'file_01' first before field var 'x_file_01'
        $val = $CurrentForm->hasValue("file_01") ? $CurrentForm->getValue("file_01") : $CurrentForm->getValue("x_file_01");
        if (!$this->file_01->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->file_01->Visible = false; // Disable update for API request
            } else {
                $this->file_01->setFormValue($val);
            }
        }

        // Check field name 'file_02' first before field var 'x_file_02'
        $val = $CurrentForm->hasValue("file_02") ? $CurrentForm->getValue("file_02") : $CurrentForm->getValue("x_file_02");
        if (!$this->file_02->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->file_02->Visible = false; // Disable update for API request
            } else {
                $this->file_02->setFormValue($val);
            }
        }

        // Check field name 'file_03' first before field var 'x_file_03'
        $val = $CurrentForm->hasValue("file_03") ? $CurrentForm->getValue("file_03") : $CurrentForm->getValue("x_file_03");
        if (!$this->file_03->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->file_03->Visible = false; // Disable update for API request
            } else {
                $this->file_03->setFormValue($val);
            }
        }

        // Check field name 'file_04' first before field var 'x_file_04'
        $val = $CurrentForm->hasValue("file_04") ? $CurrentForm->getValue("file_04") : $CurrentForm->getValue("x_file_04");
        if (!$this->file_04->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->file_04->Visible = false; // Disable update for API request
            } else {
                $this->file_04->setFormValue($val);
            }
        }

        // Check field name 'file_05' first before field var 'x_file_05'
        $val = $CurrentForm->hasValue("file_05") ? $CurrentForm->getValue("file_05") : $CurrentForm->getValue("x_file_05");
        if (!$this->file_05->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->file_05->Visible = false; // Disable update for API request
            } else {
                $this->file_05->setFormValue($val);
            }
        }

        // Check field name 'file_06' first before field var 'x_file_06'
        $val = $CurrentForm->hasValue("file_06") ? $CurrentForm->getValue("file_06") : $CurrentForm->getValue("x_file_06");
        if (!$this->file_06->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->file_06->Visible = false; // Disable update for API request
            } else {
                $this->file_06->setFormValue($val);
            }
        }

        // Check field name 'file_07' first before field var 'x_file_07'
        $val = $CurrentForm->hasValue("file_07") ? $CurrentForm->getValue("file_07") : $CurrentForm->getValue("x_file_07");
        if (!$this->file_07->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->file_07->Visible = false; // Disable update for API request
            } else {
                $this->file_07->setFormValue($val);
            }
        }

        // Check field name 'file_08' first before field var 'x_file_08'
        $val = $CurrentForm->hasValue("file_08") ? $CurrentForm->getValue("file_08") : $CurrentForm->getValue("x_file_08");
        if (!$this->file_08->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->file_08->Visible = false; // Disable update for API request
            } else {
                $this->file_08->setFormValue($val);
            }
        }

        // Check field name 'file_09' first before field var 'x_file_09'
        $val = $CurrentForm->hasValue("file_09") ? $CurrentForm->getValue("file_09") : $CurrentForm->getValue("x_file_09");
        if (!$this->file_09->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->file_09->Visible = false; // Disable update for API request
            } else {
                $this->file_09->setFormValue($val);
            }
        }

        // Check field name 'file_10' first before field var 'x_file_10'
        $val = $CurrentForm->hasValue("file_10") ? $CurrentForm->getValue("file_10") : $CurrentForm->getValue("x_file_10");
        if (!$this->file_10->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->file_10->Visible = false; // Disable update for API request
            } else {
                $this->file_10->setFormValue($val);
            }
        }

        // Check field name 'file_11' first before field var 'x_file_11'
        $val = $CurrentForm->hasValue("file_11") ? $CurrentForm->getValue("file_11") : $CurrentForm->getValue("x_file_11");
        if (!$this->file_11->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->file_11->Visible = false; // Disable update for API request
            } else {
                $this->file_11->setFormValue($val);
            }
        }

        // Check field name 'file_12' first before field var 'x_file_12'
        $val = $CurrentForm->hasValue("file_12") ? $CurrentForm->getValue("file_12") : $CurrentForm->getValue("x_file_12");
        if (!$this->file_12->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->file_12->Visible = false; // Disable update for API request
            } else {
                $this->file_12->setFormValue($val);
            }
        }

        // Check field name 'file_13' first before field var 'x_file_13'
        $val = $CurrentForm->hasValue("file_13") ? $CurrentForm->getValue("file_13") : $CurrentForm->getValue("x_file_13");
        if (!$this->file_13->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->file_13->Visible = false; // Disable update for API request
            } else {
                $this->file_13->setFormValue($val);
            }
        }

        // Check field name 'file_14' first before field var 'x_file_14'
        $val = $CurrentForm->hasValue("file_14") ? $CurrentForm->getValue("file_14") : $CurrentForm->getValue("x_file_14");
        if (!$this->file_14->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->file_14->Visible = false; // Disable update for API request
            } else {
                $this->file_14->setFormValue($val);
            }
        }

        // Check field name 'file_15' first before field var 'x_file_15'
        $val = $CurrentForm->hasValue("file_15") ? $CurrentForm->getValue("file_15") : $CurrentForm->getValue("x_file_15");
        if (!$this->file_15->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->file_15->Visible = false; // Disable update for API request
            } else {
                $this->file_15->setFormValue($val);
            }
        }

        // Check field name 'file_16' first before field var 'x_file_16'
        $val = $CurrentForm->hasValue("file_16") ? $CurrentForm->getValue("file_16") : $CurrentForm->getValue("x_file_16");
        if (!$this->file_16->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->file_16->Visible = false; // Disable update for API request
            } else {
                $this->file_16->setFormValue($val);
            }
        }

        // Check field name 'file_17' first before field var 'x_file_17'
        $val = $CurrentForm->hasValue("file_17") ? $CurrentForm->getValue("file_17") : $CurrentForm->getValue("x_file_17");
        if (!$this->file_17->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->file_17->Visible = false; // Disable update for API request
            } else {
                $this->file_17->setFormValue($val);
            }
        }

        // Check field name 'file_18' first before field var 'x_file_18'
        $val = $CurrentForm->hasValue("file_18") ? $CurrentForm->getValue("file_18") : $CurrentForm->getValue("x_file_18");
        if (!$this->file_18->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->file_18->Visible = false; // Disable update for API request
            } else {
                $this->file_18->setFormValue($val);
            }
        }

        // Check field name 'file_19' first before field var 'x_file_19'
        $val = $CurrentForm->hasValue("file_19") ? $CurrentForm->getValue("file_19") : $CurrentForm->getValue("x_file_19");
        if (!$this->file_19->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->file_19->Visible = false; // Disable update for API request
            } else {
                $this->file_19->setFormValue($val);
            }
        }

        // Check field name 'file_20' first before field var 'x_file_20'
        $val = $CurrentForm->hasValue("file_20") ? $CurrentForm->getValue("file_20") : $CurrentForm->getValue("x_file_20");
        if (!$this->file_20->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->file_20->Visible = false; // Disable update for API request
            } else {
                $this->file_20->setFormValue($val);
            }
        }

        // Check field name 'file_21' first before field var 'x_file_21'
        $val = $CurrentForm->hasValue("file_21") ? $CurrentForm->getValue("file_21") : $CurrentForm->getValue("x_file_21");
        if (!$this->file_21->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->file_21->Visible = false; // Disable update for API request
            } else {
                $this->file_21->setFormValue($val);
            }
        }

        // Check field name 'file_22' first before field var 'x_file_22'
        $val = $CurrentForm->hasValue("file_22") ? $CurrentForm->getValue("file_22") : $CurrentForm->getValue("x_file_22");
        if (!$this->file_22->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->file_22->Visible = false; // Disable update for API request
            } else {
                $this->file_22->setFormValue($val);
            }
        }

        // Check field name 'file_23' first before field var 'x_file_23'
        $val = $CurrentForm->hasValue("file_23") ? $CurrentForm->getValue("file_23") : $CurrentForm->getValue("x_file_23");
        if (!$this->file_23->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->file_23->Visible = false; // Disable update for API request
            } else {
                $this->file_23->setFormValue($val);
            }
        }

        // Check field name 'file_24' first before field var 'x_file_24'
        $val = $CurrentForm->hasValue("file_24") ? $CurrentForm->getValue("file_24") : $CurrentForm->getValue("x_file_24");
        if (!$this->file_24->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->file_24->Visible = false; // Disable update for API request
            } else {
                $this->file_24->setFormValue($val);
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
        $this->idd_wilayah->CurrentValue = $this->idd_wilayah->FormValue;
        $this->file_01->CurrentValue = $this->file_01->FormValue;
        $this->file_02->CurrentValue = $this->file_02->FormValue;
        $this->file_03->CurrentValue = $this->file_03->FormValue;
        $this->file_04->CurrentValue = $this->file_04->FormValue;
        $this->file_05->CurrentValue = $this->file_05->FormValue;
        $this->file_06->CurrentValue = $this->file_06->FormValue;
        $this->file_07->CurrentValue = $this->file_07->FormValue;
        $this->file_08->CurrentValue = $this->file_08->FormValue;
        $this->file_09->CurrentValue = $this->file_09->FormValue;
        $this->file_10->CurrentValue = $this->file_10->FormValue;
        $this->file_11->CurrentValue = $this->file_11->FormValue;
        $this->file_12->CurrentValue = $this->file_12->FormValue;
        $this->file_13->CurrentValue = $this->file_13->FormValue;
        $this->file_14->CurrentValue = $this->file_14->FormValue;
        $this->file_15->CurrentValue = $this->file_15->FormValue;
        $this->file_16->CurrentValue = $this->file_16->FormValue;
        $this->file_17->CurrentValue = $this->file_17->FormValue;
        $this->file_18->CurrentValue = $this->file_18->FormValue;
        $this->file_19->CurrentValue = $this->file_19->FormValue;
        $this->file_20->CurrentValue = $this->file_20->FormValue;
        $this->file_21->CurrentValue = $this->file_21->FormValue;
        $this->file_22->CurrentValue = $this->file_22->FormValue;
        $this->file_23->CurrentValue = $this->file_23->FormValue;
        $this->file_24->CurrentValue = $this->file_24->FormValue;
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
        $this->loadDefaultValues();
        $row = [];
        $row['idd_evaluasi'] = $this->idd_evaluasi->CurrentValue;
        $row['tanggal'] = $this->tanggal->CurrentValue;
        $row['kd_satker'] = $this->kd_satker->CurrentValue;
        $row['idd_tahapan'] = $this->idd_tahapan->CurrentValue;
        $row['tahun_anggaran'] = $this->tahun_anggaran->CurrentValue;
        $row['idd_wilayah'] = $this->idd_wilayah->CurrentValue;
        $row['file_01'] = $this->file_01->CurrentValue;
        $row['file_02'] = $this->file_02->CurrentValue;
        $row['file_03'] = $this->file_03->CurrentValue;
        $row['file_04'] = $this->file_04->CurrentValue;
        $row['file_05'] = $this->file_05->CurrentValue;
        $row['file_06'] = $this->file_06->CurrentValue;
        $row['file_07'] = $this->file_07->CurrentValue;
        $row['file_08'] = $this->file_08->CurrentValue;
        $row['file_09'] = $this->file_09->CurrentValue;
        $row['file_10'] = $this->file_10->CurrentValue;
        $row['file_11'] = $this->file_11->CurrentValue;
        $row['file_12'] = $this->file_12->CurrentValue;
        $row['file_13'] = $this->file_13->CurrentValue;
        $row['file_14'] = $this->file_14->CurrentValue;
        $row['file_15'] = $this->file_15->CurrentValue;
        $row['file_16'] = $this->file_16->CurrentValue;
        $row['file_17'] = $this->file_17->CurrentValue;
        $row['file_18'] = $this->file_18->CurrentValue;
        $row['file_19'] = $this->file_19->CurrentValue;
        $row['file_20'] = $this->file_20->CurrentValue;
        $row['file_21'] = $this->file_21->CurrentValue;
        $row['file_22'] = $this->file_22->CurrentValue;
        $row['file_23'] = $this->file_23->CurrentValue;
        $row['file_24'] = $this->file_24->CurrentValue;
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
        } elseif ($this->RowType == ROWTYPE_ADD) {
            // tanggal
            $this->tanggal->EditAttrs["class"] = "form-control";
            $this->tanggal->EditCustomAttributes = "";
            $this->tanggal->EditValue = HtmlEncode(FormatDateTime($this->tanggal->CurrentValue, 8));
            $this->tanggal->PlaceHolder = RemoveHtml($this->tanggal->caption());

            // kd_satker
            $this->kd_satker->EditAttrs["class"] = "form-control";
            $this->kd_satker->EditCustomAttributes = "";
            if (!$this->kd_satker->Raw) {
                $this->kd_satker->CurrentValue = HtmlDecode($this->kd_satker->CurrentValue);
            }
            $this->kd_satker->EditValue = HtmlEncode($this->kd_satker->CurrentValue);
            $this->kd_satker->PlaceHolder = RemoveHtml($this->kd_satker->caption());

            // idd_tahapan
            $this->idd_tahapan->EditAttrs["class"] = "form-control";
            $this->idd_tahapan->EditCustomAttributes = "";
            $this->idd_tahapan->EditValue = HtmlEncode($this->idd_tahapan->CurrentValue);
            $this->idd_tahapan->PlaceHolder = RemoveHtml($this->idd_tahapan->caption());

            // tahun_anggaran
            $this->tahun_anggaran->EditAttrs["class"] = "form-control";
            $this->tahun_anggaran->EditCustomAttributes = "";
            if (!$this->tahun_anggaran->Raw) {
                $this->tahun_anggaran->CurrentValue = HtmlDecode($this->tahun_anggaran->CurrentValue);
            }
            $this->tahun_anggaran->EditValue = HtmlEncode($this->tahun_anggaran->CurrentValue);
            $this->tahun_anggaran->PlaceHolder = RemoveHtml($this->tahun_anggaran->caption());

            // idd_wilayah
            $this->idd_wilayah->EditAttrs["class"] = "form-control";
            $this->idd_wilayah->EditCustomAttributes = "";
            $this->idd_wilayah->EditValue = HtmlEncode($this->idd_wilayah->CurrentValue);
            $this->idd_wilayah->PlaceHolder = RemoveHtml($this->idd_wilayah->caption());

            // file_01
            $this->file_01->EditAttrs["class"] = "form-control";
            $this->file_01->EditCustomAttributes = "";
            if (!$this->file_01->Raw) {
                $this->file_01->CurrentValue = HtmlDecode($this->file_01->CurrentValue);
            }
            $this->file_01->EditValue = HtmlEncode($this->file_01->CurrentValue);
            $this->file_01->PlaceHolder = RemoveHtml($this->file_01->caption());

            // file_02
            $this->file_02->EditAttrs["class"] = "form-control";
            $this->file_02->EditCustomAttributes = "";
            if (!$this->file_02->Raw) {
                $this->file_02->CurrentValue = HtmlDecode($this->file_02->CurrentValue);
            }
            $this->file_02->EditValue = HtmlEncode($this->file_02->CurrentValue);
            $this->file_02->PlaceHolder = RemoveHtml($this->file_02->caption());

            // file_03
            $this->file_03->EditAttrs["class"] = "form-control";
            $this->file_03->EditCustomAttributes = "";
            if (!$this->file_03->Raw) {
                $this->file_03->CurrentValue = HtmlDecode($this->file_03->CurrentValue);
            }
            $this->file_03->EditValue = HtmlEncode($this->file_03->CurrentValue);
            $this->file_03->PlaceHolder = RemoveHtml($this->file_03->caption());

            // file_04
            $this->file_04->EditAttrs["class"] = "form-control";
            $this->file_04->EditCustomAttributes = "";
            if (!$this->file_04->Raw) {
                $this->file_04->CurrentValue = HtmlDecode($this->file_04->CurrentValue);
            }
            $this->file_04->EditValue = HtmlEncode($this->file_04->CurrentValue);
            $this->file_04->PlaceHolder = RemoveHtml($this->file_04->caption());

            // file_05
            $this->file_05->EditAttrs["class"] = "form-control";
            $this->file_05->EditCustomAttributes = "";
            if (!$this->file_05->Raw) {
                $this->file_05->CurrentValue = HtmlDecode($this->file_05->CurrentValue);
            }
            $this->file_05->EditValue = HtmlEncode($this->file_05->CurrentValue);
            $this->file_05->PlaceHolder = RemoveHtml($this->file_05->caption());

            // file_06
            $this->file_06->EditAttrs["class"] = "form-control";
            $this->file_06->EditCustomAttributes = "";
            if (!$this->file_06->Raw) {
                $this->file_06->CurrentValue = HtmlDecode($this->file_06->CurrentValue);
            }
            $this->file_06->EditValue = HtmlEncode($this->file_06->CurrentValue);
            $this->file_06->PlaceHolder = RemoveHtml($this->file_06->caption());

            // file_07
            $this->file_07->EditAttrs["class"] = "form-control";
            $this->file_07->EditCustomAttributes = "";
            if (!$this->file_07->Raw) {
                $this->file_07->CurrentValue = HtmlDecode($this->file_07->CurrentValue);
            }
            $this->file_07->EditValue = HtmlEncode($this->file_07->CurrentValue);
            $this->file_07->PlaceHolder = RemoveHtml($this->file_07->caption());

            // file_08
            $this->file_08->EditAttrs["class"] = "form-control";
            $this->file_08->EditCustomAttributes = "";
            if (!$this->file_08->Raw) {
                $this->file_08->CurrentValue = HtmlDecode($this->file_08->CurrentValue);
            }
            $this->file_08->EditValue = HtmlEncode($this->file_08->CurrentValue);
            $this->file_08->PlaceHolder = RemoveHtml($this->file_08->caption());

            // file_09
            $this->file_09->EditAttrs["class"] = "form-control";
            $this->file_09->EditCustomAttributes = "";
            if (!$this->file_09->Raw) {
                $this->file_09->CurrentValue = HtmlDecode($this->file_09->CurrentValue);
            }
            $this->file_09->EditValue = HtmlEncode($this->file_09->CurrentValue);
            $this->file_09->PlaceHolder = RemoveHtml($this->file_09->caption());

            // file_10
            $this->file_10->EditAttrs["class"] = "form-control";
            $this->file_10->EditCustomAttributes = "";
            if (!$this->file_10->Raw) {
                $this->file_10->CurrentValue = HtmlDecode($this->file_10->CurrentValue);
            }
            $this->file_10->EditValue = HtmlEncode($this->file_10->CurrentValue);
            $this->file_10->PlaceHolder = RemoveHtml($this->file_10->caption());

            // file_11
            $this->file_11->EditAttrs["class"] = "form-control";
            $this->file_11->EditCustomAttributes = "";
            if (!$this->file_11->Raw) {
                $this->file_11->CurrentValue = HtmlDecode($this->file_11->CurrentValue);
            }
            $this->file_11->EditValue = HtmlEncode($this->file_11->CurrentValue);
            $this->file_11->PlaceHolder = RemoveHtml($this->file_11->caption());

            // file_12
            $this->file_12->EditAttrs["class"] = "form-control";
            $this->file_12->EditCustomAttributes = "";
            if (!$this->file_12->Raw) {
                $this->file_12->CurrentValue = HtmlDecode($this->file_12->CurrentValue);
            }
            $this->file_12->EditValue = HtmlEncode($this->file_12->CurrentValue);
            $this->file_12->PlaceHolder = RemoveHtml($this->file_12->caption());

            // file_13
            $this->file_13->EditAttrs["class"] = "form-control";
            $this->file_13->EditCustomAttributes = "";
            if (!$this->file_13->Raw) {
                $this->file_13->CurrentValue = HtmlDecode($this->file_13->CurrentValue);
            }
            $this->file_13->EditValue = HtmlEncode($this->file_13->CurrentValue);
            $this->file_13->PlaceHolder = RemoveHtml($this->file_13->caption());

            // file_14
            $this->file_14->EditAttrs["class"] = "form-control";
            $this->file_14->EditCustomAttributes = "";
            if (!$this->file_14->Raw) {
                $this->file_14->CurrentValue = HtmlDecode($this->file_14->CurrentValue);
            }
            $this->file_14->EditValue = HtmlEncode($this->file_14->CurrentValue);
            $this->file_14->PlaceHolder = RemoveHtml($this->file_14->caption());

            // file_15
            $this->file_15->EditAttrs["class"] = "form-control";
            $this->file_15->EditCustomAttributes = "";
            if (!$this->file_15->Raw) {
                $this->file_15->CurrentValue = HtmlDecode($this->file_15->CurrentValue);
            }
            $this->file_15->EditValue = HtmlEncode($this->file_15->CurrentValue);
            $this->file_15->PlaceHolder = RemoveHtml($this->file_15->caption());

            // file_16
            $this->file_16->EditAttrs["class"] = "form-control";
            $this->file_16->EditCustomAttributes = "";
            if (!$this->file_16->Raw) {
                $this->file_16->CurrentValue = HtmlDecode($this->file_16->CurrentValue);
            }
            $this->file_16->EditValue = HtmlEncode($this->file_16->CurrentValue);
            $this->file_16->PlaceHolder = RemoveHtml($this->file_16->caption());

            // file_17
            $this->file_17->EditAttrs["class"] = "form-control";
            $this->file_17->EditCustomAttributes = "";
            if (!$this->file_17->Raw) {
                $this->file_17->CurrentValue = HtmlDecode($this->file_17->CurrentValue);
            }
            $this->file_17->EditValue = HtmlEncode($this->file_17->CurrentValue);
            $this->file_17->PlaceHolder = RemoveHtml($this->file_17->caption());

            // file_18
            $this->file_18->EditAttrs["class"] = "form-control";
            $this->file_18->EditCustomAttributes = "";
            if (!$this->file_18->Raw) {
                $this->file_18->CurrentValue = HtmlDecode($this->file_18->CurrentValue);
            }
            $this->file_18->EditValue = HtmlEncode($this->file_18->CurrentValue);
            $this->file_18->PlaceHolder = RemoveHtml($this->file_18->caption());

            // file_19
            $this->file_19->EditAttrs["class"] = "form-control";
            $this->file_19->EditCustomAttributes = "";
            if (!$this->file_19->Raw) {
                $this->file_19->CurrentValue = HtmlDecode($this->file_19->CurrentValue);
            }
            $this->file_19->EditValue = HtmlEncode($this->file_19->CurrentValue);
            $this->file_19->PlaceHolder = RemoveHtml($this->file_19->caption());

            // file_20
            $this->file_20->EditAttrs["class"] = "form-control";
            $this->file_20->EditCustomAttributes = "";
            if (!$this->file_20->Raw) {
                $this->file_20->CurrentValue = HtmlDecode($this->file_20->CurrentValue);
            }
            $this->file_20->EditValue = HtmlEncode($this->file_20->CurrentValue);
            $this->file_20->PlaceHolder = RemoveHtml($this->file_20->caption());

            // file_21
            $this->file_21->EditAttrs["class"] = "form-control";
            $this->file_21->EditCustomAttributes = "";
            if (!$this->file_21->Raw) {
                $this->file_21->CurrentValue = HtmlDecode($this->file_21->CurrentValue);
            }
            $this->file_21->EditValue = HtmlEncode($this->file_21->CurrentValue);
            $this->file_21->PlaceHolder = RemoveHtml($this->file_21->caption());

            // file_22
            $this->file_22->EditAttrs["class"] = "form-control";
            $this->file_22->EditCustomAttributes = "";
            if (!$this->file_22->Raw) {
                $this->file_22->CurrentValue = HtmlDecode($this->file_22->CurrentValue);
            }
            $this->file_22->EditValue = HtmlEncode($this->file_22->CurrentValue);
            $this->file_22->PlaceHolder = RemoveHtml($this->file_22->caption());

            // file_23
            $this->file_23->EditAttrs["class"] = "form-control";
            $this->file_23->EditCustomAttributes = "";
            if (!$this->file_23->Raw) {
                $this->file_23->CurrentValue = HtmlDecode($this->file_23->CurrentValue);
            }
            $this->file_23->EditValue = HtmlEncode($this->file_23->CurrentValue);
            $this->file_23->PlaceHolder = RemoveHtml($this->file_23->caption());

            // file_24
            $this->file_24->EditAttrs["class"] = "form-control";
            $this->file_24->EditCustomAttributes = "";
            if (!$this->file_24->Raw) {
                $this->file_24->CurrentValue = HtmlDecode($this->file_24->CurrentValue);
            }
            $this->file_24->EditValue = HtmlEncode($this->file_24->CurrentValue);
            $this->file_24->PlaceHolder = RemoveHtml($this->file_24->caption());

            // status
            $this->status->EditAttrs["class"] = "form-control";
            $this->status->EditCustomAttributes = "";
            $this->status->EditValue = HtmlEncode($this->status->CurrentValue);
            $this->status->PlaceHolder = RemoveHtml($this->status->caption());

            // idd_user
            $this->idd_user->EditAttrs["class"] = "form-control";
            $this->idd_user->EditCustomAttributes = "";
            if (!$Security->isAdmin() && $Security->isLoggedIn() && !$this->userIDAllow("add")) { // Non system admin
                $this->idd_user->CurrentValue = CurrentUserID();
                $this->idd_user->EditValue = $this->idd_user->CurrentValue;
                $this->idd_user->EditValue = FormatNumber($this->idd_user->EditValue, 0, -2, -2, -2);
                $this->idd_user->ViewCustomAttributes = "";
            } else {
                $this->idd_user->EditValue = HtmlEncode($this->idd_user->CurrentValue);
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

            // idd_wilayah
            $this->idd_wilayah->LinkCustomAttributes = "";
            $this->idd_wilayah->HrefValue = "";

            // file_01
            $this->file_01->LinkCustomAttributes = "";
            $this->file_01->HrefValue = "";

            // file_02
            $this->file_02->LinkCustomAttributes = "";
            $this->file_02->HrefValue = "";

            // file_03
            $this->file_03->LinkCustomAttributes = "";
            $this->file_03->HrefValue = "";

            // file_04
            $this->file_04->LinkCustomAttributes = "";
            $this->file_04->HrefValue = "";

            // file_05
            $this->file_05->LinkCustomAttributes = "";
            $this->file_05->HrefValue = "";

            // file_06
            $this->file_06->LinkCustomAttributes = "";
            $this->file_06->HrefValue = "";

            // file_07
            $this->file_07->LinkCustomAttributes = "";
            $this->file_07->HrefValue = "";

            // file_08
            $this->file_08->LinkCustomAttributes = "";
            $this->file_08->HrefValue = "";

            // file_09
            $this->file_09->LinkCustomAttributes = "";
            $this->file_09->HrefValue = "";

            // file_10
            $this->file_10->LinkCustomAttributes = "";
            $this->file_10->HrefValue = "";

            // file_11
            $this->file_11->LinkCustomAttributes = "";
            $this->file_11->HrefValue = "";

            // file_12
            $this->file_12->LinkCustomAttributes = "";
            $this->file_12->HrefValue = "";

            // file_13
            $this->file_13->LinkCustomAttributes = "";
            $this->file_13->HrefValue = "";

            // file_14
            $this->file_14->LinkCustomAttributes = "";
            $this->file_14->HrefValue = "";

            // file_15
            $this->file_15->LinkCustomAttributes = "";
            $this->file_15->HrefValue = "";

            // file_16
            $this->file_16->LinkCustomAttributes = "";
            $this->file_16->HrefValue = "";

            // file_17
            $this->file_17->LinkCustomAttributes = "";
            $this->file_17->HrefValue = "";

            // file_18
            $this->file_18->LinkCustomAttributes = "";
            $this->file_18->HrefValue = "";

            // file_19
            $this->file_19->LinkCustomAttributes = "";
            $this->file_19->HrefValue = "";

            // file_20
            $this->file_20->LinkCustomAttributes = "";
            $this->file_20->HrefValue = "";

            // file_21
            $this->file_21->LinkCustomAttributes = "";
            $this->file_21->HrefValue = "";

            // file_22
            $this->file_22->LinkCustomAttributes = "";
            $this->file_22->HrefValue = "";

            // file_23
            $this->file_23->LinkCustomAttributes = "";
            $this->file_23->HrefValue = "";

            // file_24
            $this->file_24->LinkCustomAttributes = "";
            $this->file_24->HrefValue = "";

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
        if (!CheckInteger($this->idd_tahapan->FormValue)) {
            $this->idd_tahapan->addErrorMessage($this->idd_tahapan->getErrorMessage(false));
        }
        if ($this->tahun_anggaran->Required) {
            if (!$this->tahun_anggaran->IsDetailKey && EmptyValue($this->tahun_anggaran->FormValue)) {
                $this->tahun_anggaran->addErrorMessage(str_replace("%s", $this->tahun_anggaran->caption(), $this->tahun_anggaran->RequiredErrorMessage));
            }
        }
        if ($this->idd_wilayah->Required) {
            if (!$this->idd_wilayah->IsDetailKey && EmptyValue($this->idd_wilayah->FormValue)) {
                $this->idd_wilayah->addErrorMessage(str_replace("%s", $this->idd_wilayah->caption(), $this->idd_wilayah->RequiredErrorMessage));
            }
        }
        if (!CheckInteger($this->idd_wilayah->FormValue)) {
            $this->idd_wilayah->addErrorMessage($this->idd_wilayah->getErrorMessage(false));
        }
        if ($this->file_01->Required) {
            if (!$this->file_01->IsDetailKey && EmptyValue($this->file_01->FormValue)) {
                $this->file_01->addErrorMessage(str_replace("%s", $this->file_01->caption(), $this->file_01->RequiredErrorMessage));
            }
        }
        if ($this->file_02->Required) {
            if (!$this->file_02->IsDetailKey && EmptyValue($this->file_02->FormValue)) {
                $this->file_02->addErrorMessage(str_replace("%s", $this->file_02->caption(), $this->file_02->RequiredErrorMessage));
            }
        }
        if ($this->file_03->Required) {
            if (!$this->file_03->IsDetailKey && EmptyValue($this->file_03->FormValue)) {
                $this->file_03->addErrorMessage(str_replace("%s", $this->file_03->caption(), $this->file_03->RequiredErrorMessage));
            }
        }
        if ($this->file_04->Required) {
            if (!$this->file_04->IsDetailKey && EmptyValue($this->file_04->FormValue)) {
                $this->file_04->addErrorMessage(str_replace("%s", $this->file_04->caption(), $this->file_04->RequiredErrorMessage));
            }
        }
        if ($this->file_05->Required) {
            if (!$this->file_05->IsDetailKey && EmptyValue($this->file_05->FormValue)) {
                $this->file_05->addErrorMessage(str_replace("%s", $this->file_05->caption(), $this->file_05->RequiredErrorMessage));
            }
        }
        if ($this->file_06->Required) {
            if (!$this->file_06->IsDetailKey && EmptyValue($this->file_06->FormValue)) {
                $this->file_06->addErrorMessage(str_replace("%s", $this->file_06->caption(), $this->file_06->RequiredErrorMessage));
            }
        }
        if ($this->file_07->Required) {
            if (!$this->file_07->IsDetailKey && EmptyValue($this->file_07->FormValue)) {
                $this->file_07->addErrorMessage(str_replace("%s", $this->file_07->caption(), $this->file_07->RequiredErrorMessage));
            }
        }
        if ($this->file_08->Required) {
            if (!$this->file_08->IsDetailKey && EmptyValue($this->file_08->FormValue)) {
                $this->file_08->addErrorMessage(str_replace("%s", $this->file_08->caption(), $this->file_08->RequiredErrorMessage));
            }
        }
        if ($this->file_09->Required) {
            if (!$this->file_09->IsDetailKey && EmptyValue($this->file_09->FormValue)) {
                $this->file_09->addErrorMessage(str_replace("%s", $this->file_09->caption(), $this->file_09->RequiredErrorMessage));
            }
        }
        if ($this->file_10->Required) {
            if (!$this->file_10->IsDetailKey && EmptyValue($this->file_10->FormValue)) {
                $this->file_10->addErrorMessage(str_replace("%s", $this->file_10->caption(), $this->file_10->RequiredErrorMessage));
            }
        }
        if ($this->file_11->Required) {
            if (!$this->file_11->IsDetailKey && EmptyValue($this->file_11->FormValue)) {
                $this->file_11->addErrorMessage(str_replace("%s", $this->file_11->caption(), $this->file_11->RequiredErrorMessage));
            }
        }
        if ($this->file_12->Required) {
            if (!$this->file_12->IsDetailKey && EmptyValue($this->file_12->FormValue)) {
                $this->file_12->addErrorMessage(str_replace("%s", $this->file_12->caption(), $this->file_12->RequiredErrorMessage));
            }
        }
        if ($this->file_13->Required) {
            if (!$this->file_13->IsDetailKey && EmptyValue($this->file_13->FormValue)) {
                $this->file_13->addErrorMessage(str_replace("%s", $this->file_13->caption(), $this->file_13->RequiredErrorMessage));
            }
        }
        if ($this->file_14->Required) {
            if (!$this->file_14->IsDetailKey && EmptyValue($this->file_14->FormValue)) {
                $this->file_14->addErrorMessage(str_replace("%s", $this->file_14->caption(), $this->file_14->RequiredErrorMessage));
            }
        }
        if ($this->file_15->Required) {
            if (!$this->file_15->IsDetailKey && EmptyValue($this->file_15->FormValue)) {
                $this->file_15->addErrorMessage(str_replace("%s", $this->file_15->caption(), $this->file_15->RequiredErrorMessage));
            }
        }
        if ($this->file_16->Required) {
            if (!$this->file_16->IsDetailKey && EmptyValue($this->file_16->FormValue)) {
                $this->file_16->addErrorMessage(str_replace("%s", $this->file_16->caption(), $this->file_16->RequiredErrorMessage));
            }
        }
        if ($this->file_17->Required) {
            if (!$this->file_17->IsDetailKey && EmptyValue($this->file_17->FormValue)) {
                $this->file_17->addErrorMessage(str_replace("%s", $this->file_17->caption(), $this->file_17->RequiredErrorMessage));
            }
        }
        if ($this->file_18->Required) {
            if (!$this->file_18->IsDetailKey && EmptyValue($this->file_18->FormValue)) {
                $this->file_18->addErrorMessage(str_replace("%s", $this->file_18->caption(), $this->file_18->RequiredErrorMessage));
            }
        }
        if ($this->file_19->Required) {
            if (!$this->file_19->IsDetailKey && EmptyValue($this->file_19->FormValue)) {
                $this->file_19->addErrorMessage(str_replace("%s", $this->file_19->caption(), $this->file_19->RequiredErrorMessage));
            }
        }
        if ($this->file_20->Required) {
            if (!$this->file_20->IsDetailKey && EmptyValue($this->file_20->FormValue)) {
                $this->file_20->addErrorMessage(str_replace("%s", $this->file_20->caption(), $this->file_20->RequiredErrorMessage));
            }
        }
        if ($this->file_21->Required) {
            if (!$this->file_21->IsDetailKey && EmptyValue($this->file_21->FormValue)) {
                $this->file_21->addErrorMessage(str_replace("%s", $this->file_21->caption(), $this->file_21->RequiredErrorMessage));
            }
        }
        if ($this->file_22->Required) {
            if (!$this->file_22->IsDetailKey && EmptyValue($this->file_22->FormValue)) {
                $this->file_22->addErrorMessage(str_replace("%s", $this->file_22->caption(), $this->file_22->RequiredErrorMessage));
            }
        }
        if ($this->file_23->Required) {
            if (!$this->file_23->IsDetailKey && EmptyValue($this->file_23->FormValue)) {
                $this->file_23->addErrorMessage(str_replace("%s", $this->file_23->caption(), $this->file_23->RequiredErrorMessage));
            }
        }
        if ($this->file_24->Required) {
            if (!$this->file_24->IsDetailKey && EmptyValue($this->file_24->FormValue)) {
                $this->file_24->addErrorMessage(str_replace("%s", $this->file_24->caption(), $this->file_24->RequiredErrorMessage));
            }
        }
        if ($this->status->Required) {
            if (!$this->status->IsDetailKey && EmptyValue($this->status->FormValue)) {
                $this->status->addErrorMessage(str_replace("%s", $this->status->caption(), $this->status->RequiredErrorMessage));
            }
        }
        if (!CheckInteger($this->status->FormValue)) {
            $this->status->addErrorMessage($this->status->getErrorMessage(false));
        }
        if ($this->idd_user->Required) {
            if (!$this->idd_user->IsDetailKey && EmptyValue($this->idd_user->FormValue)) {
                $this->idd_user->addErrorMessage(str_replace("%s", $this->idd_user->caption(), $this->idd_user->RequiredErrorMessage));
            }
        }
        if (!CheckInteger($this->idd_user->FormValue)) {
            $this->idd_user->addErrorMessage($this->idd_user->getErrorMessage(false));
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

        // idd_wilayah
        $this->idd_wilayah->setDbValueDef($rsnew, $this->idd_wilayah->CurrentValue, 0, false);

        // file_01
        $this->file_01->setDbValueDef($rsnew, $this->file_01->CurrentValue, "", false);

        // file_02
        $this->file_02->setDbValueDef($rsnew, $this->file_02->CurrentValue, "", false);

        // file_03
        $this->file_03->setDbValueDef($rsnew, $this->file_03->CurrentValue, "", false);

        // file_04
        $this->file_04->setDbValueDef($rsnew, $this->file_04->CurrentValue, "", false);

        // file_05
        $this->file_05->setDbValueDef($rsnew, $this->file_05->CurrentValue, "", false);

        // file_06
        $this->file_06->setDbValueDef($rsnew, $this->file_06->CurrentValue, "", false);

        // file_07
        $this->file_07->setDbValueDef($rsnew, $this->file_07->CurrentValue, "", false);

        // file_08
        $this->file_08->setDbValueDef($rsnew, $this->file_08->CurrentValue, "", false);

        // file_09
        $this->file_09->setDbValueDef($rsnew, $this->file_09->CurrentValue, "", false);

        // file_10
        $this->file_10->setDbValueDef($rsnew, $this->file_10->CurrentValue, "", false);

        // file_11
        $this->file_11->setDbValueDef($rsnew, $this->file_11->CurrentValue, "", false);

        // file_12
        $this->file_12->setDbValueDef($rsnew, $this->file_12->CurrentValue, "", false);

        // file_13
        $this->file_13->setDbValueDef($rsnew, $this->file_13->CurrentValue, "", false);

        // file_14
        $this->file_14->setDbValueDef($rsnew, $this->file_14->CurrentValue, "", false);

        // file_15
        $this->file_15->setDbValueDef($rsnew, $this->file_15->CurrentValue, "", false);

        // file_16
        $this->file_16->setDbValueDef($rsnew, $this->file_16->CurrentValue, "", false);

        // file_17
        $this->file_17->setDbValueDef($rsnew, $this->file_17->CurrentValue, "", false);

        // file_18
        $this->file_18->setDbValueDef($rsnew, $this->file_18->CurrentValue, "", false);

        // file_19
        $this->file_19->setDbValueDef($rsnew, $this->file_19->CurrentValue, "", false);

        // file_20
        $this->file_20->setDbValueDef($rsnew, $this->file_20->CurrentValue, "", false);

        // file_21
        $this->file_21->setDbValueDef($rsnew, $this->file_21->CurrentValue, "", false);

        // file_22
        $this->file_22->setDbValueDef($rsnew, $this->file_22->CurrentValue, "", false);

        // file_23
        $this->file_23->setDbValueDef($rsnew, $this->file_23->CurrentValue, "", false);

        // file_24
        $this->file_24->setDbValueDef($rsnew, $this->file_24->CurrentValue, "", false);

        // status
        $this->status->setDbValueDef($rsnew, $this->status->CurrentValue, 0, false);

        // idd_user
        $this->idd_user->setDbValueDef($rsnew, $this->idd_user->CurrentValue, 0, false);

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
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("apbklist"), "", $this->TableVar, true);
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
