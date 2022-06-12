<?php

namespace PHPMaker2021\silpa;

use Doctrine\DBAL\ParameterType;

/**
 * Page class
 */
class EvaluatorsEdit extends Evaluators
{
    use MessagesTrait;

    // Page ID
    public $PageID = "edit";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'evaluators';

    // Page object name
    public $PageObjName = "EvaluatorsEdit";

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

        // Table object (evaluators)
        if (!isset($GLOBALS["evaluators"]) || get_class($GLOBALS["evaluators"]) == PROJECT_NAMESPACE . "evaluators") {
            $GLOBALS["evaluators"] = &$this;
        }

        // Page URL
        $pageUrl = $this->pageUrl();

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'evaluators');
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
                $doc = new $class(Container("evaluators"));
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
                    if ($pageName == "evaluatorsview") {
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
            $key .= @$ar['idd_evaluator'];
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
            $this->idd_evaluator->Visible = false;
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
        $this->idd_evaluator->Visible = false;
        $this->nip->setVisibility();
        $this->nama_lengkap->setVisibility();
        $this->alamat->setVisibility();
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
        $this->FormClassName = "ew-form ew-edit-form ew-horizontal";
        $loaded = false;
        $postBack = false;

        // Set up current action and primary key
        if (IsApi()) {
            // Load key values
            $loaded = true;
            if (($keyValue = Get("idd_evaluator") ?? Key(0) ?? Route(2)) !== null) {
                $this->idd_evaluator->setQueryStringValue($keyValue);
                $this->idd_evaluator->setOldValue($this->idd_evaluator->QueryStringValue);
            } elseif (Post("idd_evaluator") !== null) {
                $this->idd_evaluator->setFormValue(Post("idd_evaluator"));
                $this->idd_evaluator->setOldValue($this->idd_evaluator->FormValue);
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
                if (($keyValue = Get("idd_evaluator") ?? Route("idd_evaluator")) !== null) {
                    $this->idd_evaluator->setQueryStringValue($keyValue);
                    $loadByQuery = true;
                } else {
                    $this->idd_evaluator->CurrentValue = null;
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
                    $this->terminate("evaluatorslist"); // No matching record, return to list
                    return;
                }
                break;
            case "update": // Update
                $returnUrl = $this->getReturnUrl();
                if (GetPageName($returnUrl) == "evaluatorslist") {
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
    }

    // Load form values
    protected function loadFormValues()
    {
        // Load from form
        global $CurrentForm;

        // Check field name 'nip' first before field var 'x_nip'
        $val = $CurrentForm->hasValue("nip") ? $CurrentForm->getValue("nip") : $CurrentForm->getValue("x_nip");
        if (!$this->nip->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->nip->Visible = false; // Disable update for API request
            } else {
                $this->nip->setFormValue($val);
            }
        }

        // Check field name 'nama_lengkap' first before field var 'x_nama_lengkap'
        $val = $CurrentForm->hasValue("nama_lengkap") ? $CurrentForm->getValue("nama_lengkap") : $CurrentForm->getValue("x_nama_lengkap");
        if (!$this->nama_lengkap->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->nama_lengkap->Visible = false; // Disable update for API request
            } else {
                $this->nama_lengkap->setFormValue($val);
            }
        }

        // Check field name 'alamat' first before field var 'x_alamat'
        $val = $CurrentForm->hasValue("alamat") ? $CurrentForm->getValue("alamat") : $CurrentForm->getValue("x_alamat");
        if (!$this->alamat->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->alamat->Visible = false; // Disable update for API request
            } else {
                $this->alamat->setFormValue($val);
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

        // Check field name 'idd_evaluator' first before field var 'x_idd_evaluator'
        $val = $CurrentForm->hasValue("idd_evaluator") ? $CurrentForm->getValue("idd_evaluator") : $CurrentForm->getValue("x_idd_evaluator");
        if (!$this->idd_evaluator->IsDetailKey) {
            $this->idd_evaluator->setFormValue($val);
        }
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->idd_evaluator->CurrentValue = $this->idd_evaluator->FormValue;
        $this->nip->CurrentValue = $this->nip->FormValue;
        $this->nama_lengkap->CurrentValue = $this->nama_lengkap->FormValue;
        $this->alamat->CurrentValue = $this->alamat->FormValue;
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
        $this->idd_evaluator->setDbValue($row['idd_evaluator']);
        $this->nip->setDbValue($row['nip']);
        $this->nama_lengkap->setDbValue($row['nama_lengkap']);
        $this->alamat->setDbValue($row['alamat']);
        $this->wilayah->setDbValue($row['wilayah']);
        $this->idd_user->setDbValue($row['idd_user']);
        $this->no_telepon->setDbValue($row['no_telepon']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['idd_evaluator'] = null;
        $row['nip'] = null;
        $row['nama_lengkap'] = null;
        $row['alamat'] = null;
        $row['wilayah'] = null;
        $row['idd_user'] = null;
        $row['no_telepon'] = null;
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

        // idd_evaluator

        // nip

        // nama_lengkap

        // alamat

        // wilayah

        // idd_user

        // no_telepon
        if ($this->RowType == ROWTYPE_VIEW) {
            // idd_evaluator
            $this->idd_evaluator->ViewValue = $this->idd_evaluator->CurrentValue;
            $this->idd_evaluator->ViewValue = FormatNumber($this->idd_evaluator->ViewValue, 0, -2, -2, -2);
            $this->idd_evaluator->ViewCustomAttributes = "";

            // nip
            $this->nip->ViewValue = $this->nip->CurrentValue;
            $this->nip->ViewCustomAttributes = "";

            // nama_lengkap
            $this->nama_lengkap->ViewValue = $this->nama_lengkap->CurrentValue;
            $this->nama_lengkap->ViewCustomAttributes = "";

            // alamat
            $this->alamat->ViewValue = $this->alamat->CurrentValue;
            $this->alamat->ViewCustomAttributes = "";

            // wilayah
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

            // nip
            $this->nip->LinkCustomAttributes = "";
            $this->nip->HrefValue = "";
            $this->nip->TooltipValue = "";

            // nama_lengkap
            $this->nama_lengkap->LinkCustomAttributes = "";
            $this->nama_lengkap->HrefValue = "";
            $this->nama_lengkap->TooltipValue = "";

            // alamat
            $this->alamat->LinkCustomAttributes = "";
            $this->alamat->HrefValue = "";
            $this->alamat->TooltipValue = "";

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
        } elseif ($this->RowType == ROWTYPE_EDIT) {
            // nip
            $this->nip->EditAttrs["class"] = "form-control";
            $this->nip->EditCustomAttributes = "";
            if (!$this->nip->Raw) {
                $this->nip->CurrentValue = HtmlDecode($this->nip->CurrentValue);
            }
            $this->nip->EditValue = HtmlEncode($this->nip->CurrentValue);
            $this->nip->PlaceHolder = RemoveHtml($this->nip->caption());

            // nama_lengkap
            $this->nama_lengkap->EditAttrs["class"] = "form-control";
            $this->nama_lengkap->EditCustomAttributes = "";
            if (!$this->nama_lengkap->Raw) {
                $this->nama_lengkap->CurrentValue = HtmlDecode($this->nama_lengkap->CurrentValue);
            }
            $this->nama_lengkap->EditValue = HtmlEncode($this->nama_lengkap->CurrentValue);
            $this->nama_lengkap->PlaceHolder = RemoveHtml($this->nama_lengkap->caption());

            // alamat
            $this->alamat->EditAttrs["class"] = "form-control";
            $this->alamat->EditCustomAttributes = "";
            $this->alamat->EditValue = HtmlEncode($this->alamat->CurrentValue);
            $this->alamat->PlaceHolder = RemoveHtml($this->alamat->caption());

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

            // no_telepon
            $this->no_telepon->EditAttrs["class"] = "form-control";
            $this->no_telepon->EditCustomAttributes = "";
            if (!$this->no_telepon->Raw) {
                $this->no_telepon->CurrentValue = HtmlDecode($this->no_telepon->CurrentValue);
            }
            $this->no_telepon->EditValue = HtmlEncode($this->no_telepon->CurrentValue);
            $this->no_telepon->PlaceHolder = RemoveHtml($this->no_telepon->caption());

            // Edit refer script

            // nip
            $this->nip->LinkCustomAttributes = "";
            $this->nip->HrefValue = "";

            // nama_lengkap
            $this->nama_lengkap->LinkCustomAttributes = "";
            $this->nama_lengkap->HrefValue = "";

            // alamat
            $this->alamat->LinkCustomAttributes = "";
            $this->alamat->HrefValue = "";

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
        if ($this->nip->Required) {
            if (!$this->nip->IsDetailKey && EmptyValue($this->nip->FormValue)) {
                $this->nip->addErrorMessage(str_replace("%s", $this->nip->caption(), $this->nip->RequiredErrorMessage));
            }
        }
        if ($this->nama_lengkap->Required) {
            if (!$this->nama_lengkap->IsDetailKey && EmptyValue($this->nama_lengkap->FormValue)) {
                $this->nama_lengkap->addErrorMessage(str_replace("%s", $this->nama_lengkap->caption(), $this->nama_lengkap->RequiredErrorMessage));
            }
        }
        if ($this->alamat->Required) {
            if (!$this->alamat->IsDetailKey && EmptyValue($this->alamat->FormValue)) {
                $this->alamat->addErrorMessage(str_replace("%s", $this->alamat->caption(), $this->alamat->RequiredErrorMessage));
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

            // nip
            $this->nip->setDbValueDef($rsnew, $this->nip->CurrentValue, "", $this->nip->ReadOnly);

            // nama_lengkap
            $this->nama_lengkap->setDbValueDef($rsnew, $this->nama_lengkap->CurrentValue, "", $this->nama_lengkap->ReadOnly);

            // alamat
            $this->alamat->setDbValueDef($rsnew, $this->alamat->CurrentValue, "", $this->alamat->ReadOnly);

            // wilayah
            $this->wilayah->setDbValueDef($rsnew, $this->wilayah->CurrentValue, 0, $this->wilayah->ReadOnly);

            // idd_user
            $this->idd_user->setDbValueDef($rsnew, $this->idd_user->CurrentValue, 0, $this->idd_user->ReadOnly);

            // no_telepon
            $this->no_telepon->setDbValueDef($rsnew, $this->no_telepon->CurrentValue, null, $this->no_telepon->ReadOnly);

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
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("evaluatorslist"), "", $this->TableVar, true);
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
