<?php

namespace PHPMaker2021\silpa;

use Doctrine\DBAL\ParameterType;

/**
 * Page class
 */
class SatkersAdd extends Satkers
{
    use MessagesTrait;

    // Page ID
    public $PageID = "add";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'satkers';

    // Page object name
    public $PageObjName = "SatkersAdd";

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

        // Table object (satkers)
        if (!isset($GLOBALS["satkers"]) || get_class($GLOBALS["satkers"]) == PROJECT_NAMESPACE . "satkers") {
            $GLOBALS["satkers"] = &$this;
        }

        // Page URL
        $pageUrl = $this->pageUrl();

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'satkers');
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
                $doc = new $class(Container("satkers"));
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
                    if ($pageName == "satkersview") {
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
            $key .= @$ar['idd_satker'];
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
            $this->idd_satker->Visible = false;
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
        $this->idd_satker->Visible = false;
        $this->kode_pemda->setVisibility();
        $this->kode_satker->setVisibility();
        $this->nama_satker->setVisibility();
        $this->wilayah->setVisibility();
        $this->idd_user->setVisibility();
        $this->no_telepon->setVisibility();
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
        $this->setupLookupOptions($this->wilayah);
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
            if (($keyValue = Get("idd_satker") ?? Route("idd_satker")) !== null) {
                $this->idd_satker->setQueryStringValue($keyValue);
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
                    $this->terminate("satkerslist"); // No matching record, return to list
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
                    if (GetPageName($returnUrl) == "satkerslist") {
                        $returnUrl = $this->addMasterUrl($returnUrl); // List page, return to List page with correct master key if necessary
                    } elseif (GetPageName($returnUrl) == "satkersview") {
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
        $this->idd_satker->CurrentValue = null;
        $this->idd_satker->OldValue = $this->idd_satker->CurrentValue;
        $this->kode_pemda->CurrentValue = null;
        $this->kode_pemda->OldValue = $this->kode_pemda->CurrentValue;
        $this->kode_satker->CurrentValue = null;
        $this->kode_satker->OldValue = $this->kode_satker->CurrentValue;
        $this->nama_satker->CurrentValue = null;
        $this->nama_satker->OldValue = $this->nama_satker->CurrentValue;
        $this->wilayah->CurrentValue = null;
        $this->wilayah->OldValue = $this->wilayah->CurrentValue;
        $this->idd_user->CurrentValue = CurrentUserID();
        $this->no_telepon->CurrentValue = null;
        $this->no_telepon->OldValue = $this->no_telepon->CurrentValue;
    }

    // Load form values
    protected function loadFormValues()
    {
        // Load from form
        global $CurrentForm;

        // Check field name 'kode_pemda' first before field var 'x_kode_pemda'
        $val = $CurrentForm->hasValue("kode_pemda") ? $CurrentForm->getValue("kode_pemda") : $CurrentForm->getValue("x_kode_pemda");
        if (!$this->kode_pemda->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->kode_pemda->Visible = false; // Disable update for API request
            } else {
                $this->kode_pemda->setFormValue($val);
            }
        }

        // Check field name 'kode_satker' first before field var 'x_kode_satker'
        $val = $CurrentForm->hasValue("kode_satker") ? $CurrentForm->getValue("kode_satker") : $CurrentForm->getValue("x_kode_satker");
        if (!$this->kode_satker->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->kode_satker->Visible = false; // Disable update for API request
            } else {
                $this->kode_satker->setFormValue($val);
            }
        }

        // Check field name 'nama_satker' first before field var 'x_nama_satker'
        $val = $CurrentForm->hasValue("nama_satker") ? $CurrentForm->getValue("nama_satker") : $CurrentForm->getValue("x_nama_satker");
        if (!$this->nama_satker->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->nama_satker->Visible = false; // Disable update for API request
            } else {
                $this->nama_satker->setFormValue($val);
            }
        }

        // Check field name 'wilayah' first before field var 'x_wilayah'
        $val = $CurrentForm->hasValue("wilayah") ? $CurrentForm->getValue("wilayah") : $CurrentForm->getValue("x_wilayah");
        if (!$this->wilayah->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->wilayah->Visible = false; // Disable update for API request
            } else {
                $this->wilayah->setFormValue($val);
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

        // Check field name 'no_telepon' first before field var 'x_no_telepon'
        $val = $CurrentForm->hasValue("no_telepon") ? $CurrentForm->getValue("no_telepon") : $CurrentForm->getValue("x_no_telepon");
        if (!$this->no_telepon->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->no_telepon->Visible = false; // Disable update for API request
            } else {
                $this->no_telepon->setFormValue($val);
            }
        }

        // Check field name 'idd_satker' first before field var 'x_idd_satker'
        $val = $CurrentForm->hasValue("idd_satker") ? $CurrentForm->getValue("idd_satker") : $CurrentForm->getValue("x_idd_satker");
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->kode_pemda->CurrentValue = $this->kode_pemda->FormValue;
        $this->kode_satker->CurrentValue = $this->kode_satker->FormValue;
        $this->nama_satker->CurrentValue = $this->nama_satker->FormValue;
        $this->wilayah->CurrentValue = $this->wilayah->FormValue;
        $this->idd_user->CurrentValue = $this->idd_user->FormValue;
        $this->no_telepon->CurrentValue = $this->no_telepon->FormValue;
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
        $this->idd_satker->setDbValue($row['idd_satker']);
        $this->kode_pemda->setDbValue($row['kode_pemda']);
        $this->kode_satker->setDbValue($row['kode_satker']);
        $this->nama_satker->setDbValue($row['nama_satker']);
        $this->wilayah->setDbValue($row['wilayah']);
        if (array_key_exists('EV__wilayah', $row)) {
            $this->wilayah->VirtualValue = $row['EV__wilayah']; // Set up virtual field value
        } else {
            $this->wilayah->VirtualValue = ""; // Clear value
        }
        $this->idd_user->setDbValue($row['idd_user']);
        $this->no_telepon->setDbValue($row['no_telepon']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $this->loadDefaultValues();
        $row = [];
        $row['idd_satker'] = $this->idd_satker->CurrentValue;
        $row['kode_pemda'] = $this->kode_pemda->CurrentValue;
        $row['kode_satker'] = $this->kode_satker->CurrentValue;
        $row['nama_satker'] = $this->nama_satker->CurrentValue;
        $row['wilayah'] = $this->wilayah->CurrentValue;
        $row['idd_user'] = $this->idd_user->CurrentValue;
        $row['no_telepon'] = $this->no_telepon->CurrentValue;
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

        // idd_satker

        // kode_pemda

        // kode_satker

        // nama_satker

        // wilayah

        // idd_user

        // no_telepon
        if ($this->RowType == ROWTYPE_VIEW) {
            // idd_satker
            $this->idd_satker->ViewValue = $this->idd_satker->CurrentValue;
            $this->idd_satker->ViewValue = FormatNumber($this->idd_satker->ViewValue, 0, -2, -2, -2);
            $this->idd_satker->ViewCustomAttributes = "";

            // kode_pemda
            $this->kode_pemda->ViewValue = $this->kode_pemda->CurrentValue;
            $this->kode_pemda->ViewCustomAttributes = "";

            // kode_satker
            $this->kode_satker->ViewValue = $this->kode_satker->CurrentValue;
            $this->kode_satker->ViewValue = FormatNumber($this->kode_satker->ViewValue, 0, -2, -2, -2);
            $this->kode_satker->ViewCustomAttributes = "";

            // nama_satker
            $this->nama_satker->ViewValue = $this->nama_satker->CurrentValue;
            $this->nama_satker->ViewCustomAttributes = "";

            // wilayah
            if ($this->wilayah->VirtualValue != "") {
                $this->wilayah->ViewValue = $this->wilayah->VirtualValue;
            } else {
                $curVal = trim(strval($this->wilayah->CurrentValue));
                if ($curVal != "") {
                    $this->wilayah->ViewValue = $this->wilayah->lookupCacheOption($curVal);
                    if ($this->wilayah->ViewValue === null) { // Lookup from database
                        $filterWrk = "`idd_wilayah`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                        $sqlWrk = $this->wilayah->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                        $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                        $ari = count($rswrk);
                        if ($ari > 0) { // Lookup values found
                            $arwrk = $this->wilayah->Lookup->renderViewRow($rswrk[0]);
                            $this->wilayah->ViewValue = $this->wilayah->displayValue($arwrk);
                        } else {
                            $this->wilayah->ViewValue = $this->wilayah->CurrentValue;
                        }
                    }
                } else {
                    $this->wilayah->ViewValue = null;
                }
            }
            $this->wilayah->ViewCustomAttributes = "";

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

            // no_telepon
            $this->no_telepon->ViewValue = $this->no_telepon->CurrentValue;
            $this->no_telepon->ViewCustomAttributes = "";

            // kode_pemda
            $this->kode_pemda->LinkCustomAttributes = "";
            $this->kode_pemda->HrefValue = "";
            $this->kode_pemda->TooltipValue = "";

            // kode_satker
            $this->kode_satker->LinkCustomAttributes = "";
            $this->kode_satker->HrefValue = "";
            $this->kode_satker->TooltipValue = "";

            // nama_satker
            $this->nama_satker->LinkCustomAttributes = "";
            $this->nama_satker->HrefValue = "";
            $this->nama_satker->TooltipValue = "";

            // wilayah
            $this->wilayah->LinkCustomAttributes = "";
            $this->wilayah->HrefValue = "";
            $this->wilayah->TooltipValue = "";

            // idd_user
            $this->idd_user->LinkCustomAttributes = "";
            $this->idd_user->HrefValue = "";
            $this->idd_user->TooltipValue = "";

            // no_telepon
            $this->no_telepon->LinkCustomAttributes = "";
            if (!EmptyValue($this->no_telepon->CurrentValue)) {
                $this->no_telepon->HrefValue = "https://wa.me/" . (!empty($this->no_telepon->ViewValue) && !is_array($this->no_telepon->ViewValue) ? RemoveHtml($this->no_telepon->ViewValue) : $this->no_telepon->CurrentValue) . "?text=Assalamu'alaikum"; // Add prefix/suffix
                $this->no_telepon->LinkAttrs["target"] = "_blank"; // Add target
                if ($this->isExport()) {
                    $this->no_telepon->HrefValue = FullUrl($this->no_telepon->HrefValue, "href");
                }
            } else {
                $this->no_telepon->HrefValue = "";
            }
            $this->no_telepon->TooltipValue = "";
        } elseif ($this->RowType == ROWTYPE_ADD) {
            // kode_pemda
            $this->kode_pemda->EditAttrs["class"] = "form-control";
            $this->kode_pemda->EditCustomAttributes = "";
            if (!$this->kode_pemda->Raw) {
                $this->kode_pemda->CurrentValue = HtmlDecode($this->kode_pemda->CurrentValue);
            }
            $this->kode_pemda->EditValue = HtmlEncode($this->kode_pemda->CurrentValue);
            $this->kode_pemda->PlaceHolder = RemoveHtml($this->kode_pemda->caption());

            // kode_satker
            $this->kode_satker->EditAttrs["class"] = "form-control";
            $this->kode_satker->EditCustomAttributes = "";
            $this->kode_satker->EditValue = HtmlEncode($this->kode_satker->CurrentValue);
            $this->kode_satker->PlaceHolder = RemoveHtml($this->kode_satker->caption());

            // nama_satker
            $this->nama_satker->EditAttrs["class"] = "form-control";
            $this->nama_satker->EditCustomAttributes = "";
            if (!$this->nama_satker->Raw) {
                $this->nama_satker->CurrentValue = HtmlDecode($this->nama_satker->CurrentValue);
            }
            $this->nama_satker->EditValue = HtmlEncode($this->nama_satker->CurrentValue);
            $this->nama_satker->PlaceHolder = RemoveHtml($this->nama_satker->caption());

            // wilayah
            $this->wilayah->EditAttrs["class"] = "form-control";
            $this->wilayah->EditCustomAttributes = "";
            $curVal = trim(strval($this->wilayah->CurrentValue));
            if ($curVal != "") {
                $this->wilayah->ViewValue = $this->wilayah->lookupCacheOption($curVal);
            } else {
                $this->wilayah->ViewValue = $this->wilayah->Lookup !== null && is_array($this->wilayah->Lookup->Options) ? $curVal : null;
            }
            if ($this->wilayah->ViewValue !== null) { // Load from cache
                $this->wilayah->EditValue = array_values($this->wilayah->Lookup->Options);
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = "`idd_wilayah`" . SearchString("=", $this->wilayah->CurrentValue, DATATYPE_NUMBER, "");
                }
                $sqlWrk = $this->wilayah->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->wilayah->EditValue = $arwrk;
            }
            $this->wilayah->PlaceHolder = RemoveHtml($this->wilayah->caption());

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

            // no_telepon
            $this->no_telepon->EditAttrs["class"] = "form-control";
            $this->no_telepon->EditCustomAttributes = "";
            if (!$this->no_telepon->Raw) {
                $this->no_telepon->CurrentValue = HtmlDecode($this->no_telepon->CurrentValue);
            }
            $this->no_telepon->EditValue = HtmlEncode($this->no_telepon->CurrentValue);
            $this->no_telepon->PlaceHolder = RemoveHtml($this->no_telepon->caption());

            // Add refer script

            // kode_pemda
            $this->kode_pemda->LinkCustomAttributes = "";
            $this->kode_pemda->HrefValue = "";

            // kode_satker
            $this->kode_satker->LinkCustomAttributes = "";
            $this->kode_satker->HrefValue = "";

            // nama_satker
            $this->nama_satker->LinkCustomAttributes = "";
            $this->nama_satker->HrefValue = "";

            // wilayah
            $this->wilayah->LinkCustomAttributes = "";
            $this->wilayah->HrefValue = "";

            // idd_user
            $this->idd_user->LinkCustomAttributes = "";
            $this->idd_user->HrefValue = "";

            // no_telepon
            $this->no_telepon->LinkCustomAttributes = "";
            if (!EmptyValue($this->no_telepon->CurrentValue)) {
                $this->no_telepon->HrefValue = "https://wa.me/" . (!empty($this->no_telepon->EditValue) && !is_array($this->no_telepon->EditValue) ? RemoveHtml($this->no_telepon->EditValue) : $this->no_telepon->CurrentValue) . "?text=Assalamu'alaikum"; // Add prefix/suffix
                $this->no_telepon->LinkAttrs["target"] = "_blank"; // Add target
                if ($this->isExport()) {
                    $this->no_telepon->HrefValue = FullUrl($this->no_telepon->HrefValue, "href");
                }
            } else {
                $this->no_telepon->HrefValue = "";
            }
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
        if ($this->kode_pemda->Required) {
            if (!$this->kode_pemda->IsDetailKey && EmptyValue($this->kode_pemda->FormValue)) {
                $this->kode_pemda->addErrorMessage(str_replace("%s", $this->kode_pemda->caption(), $this->kode_pemda->RequiredErrorMessage));
            }
        }
        if ($this->kode_satker->Required) {
            if (!$this->kode_satker->IsDetailKey && EmptyValue($this->kode_satker->FormValue)) {
                $this->kode_satker->addErrorMessage(str_replace("%s", $this->kode_satker->caption(), $this->kode_satker->RequiredErrorMessage));
            }
        }
        if (!CheckInteger($this->kode_satker->FormValue)) {
            $this->kode_satker->addErrorMessage($this->kode_satker->getErrorMessage(false));
        }
        if ($this->nama_satker->Required) {
            if (!$this->nama_satker->IsDetailKey && EmptyValue($this->nama_satker->FormValue)) {
                $this->nama_satker->addErrorMessage(str_replace("%s", $this->nama_satker->caption(), $this->nama_satker->RequiredErrorMessage));
            }
        }
        if ($this->wilayah->Required) {
            if (!$this->wilayah->IsDetailKey && EmptyValue($this->wilayah->FormValue)) {
                $this->wilayah->addErrorMessage(str_replace("%s", $this->wilayah->caption(), $this->wilayah->RequiredErrorMessage));
            }
        }
        if ($this->idd_user->Required) {
            if (!$this->idd_user->IsDetailKey && EmptyValue($this->idd_user->FormValue)) {
                $this->idd_user->addErrorMessage(str_replace("%s", $this->idd_user->caption(), $this->idd_user->RequiredErrorMessage));
            }
        }
        if ($this->no_telepon->Required) {
            if (!$this->no_telepon->IsDetailKey && EmptyValue($this->no_telepon->FormValue)) {
                $this->no_telepon->addErrorMessage(str_replace("%s", $this->no_telepon->caption(), $this->no_telepon->RequiredErrorMessage));
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

        // kode_pemda
        $this->kode_pemda->setDbValueDef($rsnew, $this->kode_pemda->CurrentValue, "", false);

        // kode_satker
        $this->kode_satker->setDbValueDef($rsnew, $this->kode_satker->CurrentValue, 0, false);

        // nama_satker
        $this->nama_satker->setDbValueDef($rsnew, $this->nama_satker->CurrentValue, "", false);

        // wilayah
        $this->wilayah->setDbValueDef($rsnew, $this->wilayah->CurrentValue, 0, false);

        // idd_user
        $this->idd_user->setDbValueDef($rsnew, $this->idd_user->CurrentValue, 0, false);

        // no_telepon
        $this->no_telepon->setDbValueDef($rsnew, $this->no_telepon->CurrentValue, null, false);

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
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("satkerslist"), "", $this->TableVar, true);
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
                case "x_wilayah":
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
