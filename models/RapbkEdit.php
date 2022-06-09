<?php

namespace PHPMaker2021\silpa;

use Doctrine\DBAL\ParameterType;

/**
 * Page class
 */
class RapbkEdit extends Rapbk
{
    use MessagesTrait;

    // Page ID
    public $PageID = "edit";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'rapbk';

    // Page object name
    public $PageObjName = "RapbkEdit";

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

        // Table object (rapbk)
        if (!isset($GLOBALS["rapbk"]) || get_class($GLOBALS["rapbk"]) == PROJECT_NAMESPACE . "rapbk") {
            $GLOBALS["rapbk"] = &$this;
        }

        // Page URL
        $pageUrl = $this->pageUrl();

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'rapbk');
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
                $doc = new $class(Container("rapbk"));
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
                    if ($pageName == "rapbkview") {
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
                    $this->terminate("rapbklist"); // No matching record, return to list
                    return;
                }
                break;
            case "update": // Update
                $returnUrl = $this->getReturnUrl();
                if (GetPageName($returnUrl) == "rapbklist") {
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
        $this->file_01->Upload->Index = $CurrentForm->Index;
        $this->file_01->Upload->uploadFile();
        $this->file_01->CurrentValue = $this->file_01->Upload->FileName;
        $this->file_02->Upload->Index = $CurrentForm->Index;
        $this->file_02->Upload->uploadFile();
        $this->file_02->CurrentValue = $this->file_02->Upload->FileName;
        $this->file_03->Upload->Index = $CurrentForm->Index;
        $this->file_03->Upload->uploadFile();
        $this->file_03->CurrentValue = $this->file_03->Upload->FileName;
        $this->file_04->Upload->Index = $CurrentForm->Index;
        $this->file_04->Upload->uploadFile();
        $this->file_04->CurrentValue = $this->file_04->Upload->FileName;
        $this->file_05->Upload->Index = $CurrentForm->Index;
        $this->file_05->Upload->uploadFile();
        $this->file_05->CurrentValue = $this->file_05->Upload->FileName;
        $this->file_06->Upload->Index = $CurrentForm->Index;
        $this->file_06->Upload->uploadFile();
        $this->file_06->CurrentValue = $this->file_06->Upload->FileName;
        $this->file_07->Upload->Index = $CurrentForm->Index;
        $this->file_07->Upload->uploadFile();
        $this->file_07->CurrentValue = $this->file_07->Upload->FileName;
        $this->file_08->Upload->Index = $CurrentForm->Index;
        $this->file_08->Upload->uploadFile();
        $this->file_08->CurrentValue = $this->file_08->Upload->FileName;
        $this->file_09->Upload->Index = $CurrentForm->Index;
        $this->file_09->Upload->uploadFile();
        $this->file_09->CurrentValue = $this->file_09->Upload->FileName;
        $this->file_10->Upload->Index = $CurrentForm->Index;
        $this->file_10->Upload->uploadFile();
        $this->file_10->CurrentValue = $this->file_10->Upload->FileName;
        $this->file_11->Upload->Index = $CurrentForm->Index;
        $this->file_11->Upload->uploadFile();
        $this->file_11->CurrentValue = $this->file_11->Upload->FileName;
        $this->file_12->Upload->Index = $CurrentForm->Index;
        $this->file_12->Upload->uploadFile();
        $this->file_12->CurrentValue = $this->file_12->Upload->FileName;
        $this->file_13->Upload->Index = $CurrentForm->Index;
        $this->file_13->Upload->uploadFile();
        $this->file_13->CurrentValue = $this->file_13->Upload->FileName;
        $this->file_14->Upload->Index = $CurrentForm->Index;
        $this->file_14->Upload->uploadFile();
        $this->file_14->CurrentValue = $this->file_14->Upload->FileName;
        $this->file_15->Upload->Index = $CurrentForm->Index;
        $this->file_15->Upload->uploadFile();
        $this->file_15->CurrentValue = $this->file_15->Upload->FileName;
        $this->file_16->Upload->Index = $CurrentForm->Index;
        $this->file_16->Upload->uploadFile();
        $this->file_16->CurrentValue = $this->file_16->Upload->FileName;
        $this->file_17->Upload->Index = $CurrentForm->Index;
        $this->file_17->Upload->uploadFile();
        $this->file_17->CurrentValue = $this->file_17->Upload->FileName;
        $this->file_18->Upload->Index = $CurrentForm->Index;
        $this->file_18->Upload->uploadFile();
        $this->file_18->CurrentValue = $this->file_18->Upload->FileName;
        $this->file_19->Upload->Index = $CurrentForm->Index;
        $this->file_19->Upload->uploadFile();
        $this->file_19->CurrentValue = $this->file_19->Upload->FileName;
        $this->file_20->Upload->Index = $CurrentForm->Index;
        $this->file_20->Upload->uploadFile();
        $this->file_20->CurrentValue = $this->file_20->Upload->FileName;
        $this->file_21->Upload->Index = $CurrentForm->Index;
        $this->file_21->Upload->uploadFile();
        $this->file_21->CurrentValue = $this->file_21->Upload->FileName;
        $this->file_22->Upload->Index = $CurrentForm->Index;
        $this->file_22->Upload->uploadFile();
        $this->file_22->CurrentValue = $this->file_22->Upload->FileName;
        $this->file_23->Upload->Index = $CurrentForm->Index;
        $this->file_23->Upload->uploadFile();
        $this->file_23->CurrentValue = $this->file_23->Upload->FileName;
        $this->file_24->Upload->Index = $CurrentForm->Index;
        $this->file_24->Upload->uploadFile();
        $this->file_24->CurrentValue = $this->file_24->Upload->FileName;
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
        $this->kd_satker->CurrentValue = $this->kd_satker->FormValue;
        $this->idd_tahapan->CurrentValue = $this->idd_tahapan->FormValue;
        $this->tahun_anggaran->CurrentValue = $this->tahun_anggaran->FormValue;
        $this->idd_wilayah->CurrentValue = $this->idd_wilayah->FormValue;
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
        $this->kd_satker->setDbValue($row['kd_satker']);
        $this->idd_tahapan->setDbValue($row['idd_tahapan']);
        $this->tahun_anggaran->setDbValue($row['tahun_anggaran']);
        $this->idd_wilayah->setDbValue($row['idd_wilayah']);
        $this->file_01->Upload->DbValue = $row['file_01'];
        $this->file_01->setDbValue($this->file_01->Upload->DbValue);
        $this->file_02->Upload->DbValue = $row['file_02'];
        $this->file_02->setDbValue($this->file_02->Upload->DbValue);
        $this->file_03->Upload->DbValue = $row['file_03'];
        $this->file_03->setDbValue($this->file_03->Upload->DbValue);
        $this->file_04->Upload->DbValue = $row['file_04'];
        $this->file_04->setDbValue($this->file_04->Upload->DbValue);
        $this->file_05->Upload->DbValue = $row['file_05'];
        $this->file_05->setDbValue($this->file_05->Upload->DbValue);
        $this->file_06->Upload->DbValue = $row['file_06'];
        $this->file_06->setDbValue($this->file_06->Upload->DbValue);
        $this->file_07->Upload->DbValue = $row['file_07'];
        $this->file_07->setDbValue($this->file_07->Upload->DbValue);
        $this->file_08->Upload->DbValue = $row['file_08'];
        $this->file_08->setDbValue($this->file_08->Upload->DbValue);
        $this->file_09->Upload->DbValue = $row['file_09'];
        $this->file_09->setDbValue($this->file_09->Upload->DbValue);
        $this->file_10->Upload->DbValue = $row['file_10'];
        $this->file_10->setDbValue($this->file_10->Upload->DbValue);
        $this->file_11->Upload->DbValue = $row['file_11'];
        $this->file_11->setDbValue($this->file_11->Upload->DbValue);
        $this->file_12->Upload->DbValue = $row['file_12'];
        $this->file_12->setDbValue($this->file_12->Upload->DbValue);
        $this->file_13->Upload->DbValue = $row['file_13'];
        $this->file_13->setDbValue($this->file_13->Upload->DbValue);
        $this->file_14->Upload->DbValue = $row['file_14'];
        $this->file_14->setDbValue($this->file_14->Upload->DbValue);
        $this->file_15->Upload->DbValue = $row['file_15'];
        $this->file_15->setDbValue($this->file_15->Upload->DbValue);
        $this->file_16->Upload->DbValue = $row['file_16'];
        $this->file_16->setDbValue($this->file_16->Upload->DbValue);
        $this->file_17->Upload->DbValue = $row['file_17'];
        $this->file_17->setDbValue($this->file_17->Upload->DbValue);
        $this->file_18->Upload->DbValue = $row['file_18'];
        $this->file_18->setDbValue($this->file_18->Upload->DbValue);
        $this->file_19->Upload->DbValue = $row['file_19'];
        $this->file_19->setDbValue($this->file_19->Upload->DbValue);
        $this->file_20->Upload->DbValue = $row['file_20'];
        $this->file_20->setDbValue($this->file_20->Upload->DbValue);
        $this->file_21->Upload->DbValue = $row['file_21'];
        $this->file_21->setDbValue($this->file_21->Upload->DbValue);
        $this->file_22->Upload->DbValue = $row['file_22'];
        $this->file_22->setDbValue($this->file_22->Upload->DbValue);
        $this->file_23->Upload->DbValue = $row['file_23'];
        $this->file_23->setDbValue($this->file_23->Upload->DbValue);
        $this->file_24->Upload->DbValue = $row['file_24'];
        $this->file_24->setDbValue($this->file_24->Upload->DbValue);
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

            // idd_wilayah
            $this->idd_wilayah->ViewValue = $this->idd_wilayah->CurrentValue;
            $this->idd_wilayah->ViewValue = FormatNumber($this->idd_wilayah->ViewValue, 0, -2, -2, -2);
            $this->idd_wilayah->ViewCustomAttributes = "";

            // file_01
            if (!EmptyValue($this->file_01->Upload->DbValue)) {
                $this->file_01->ViewValue = $this->file_01->Upload->DbValue;
            } else {
                $this->file_01->ViewValue = "";
            }
            $this->file_01->ViewCustomAttributes = "";

            // file_02
            if (!EmptyValue($this->file_02->Upload->DbValue)) {
                $this->file_02->ViewValue = $this->file_02->Upload->DbValue;
            } else {
                $this->file_02->ViewValue = "";
            }
            $this->file_02->ViewCustomAttributes = "";

            // file_03
            if (!EmptyValue($this->file_03->Upload->DbValue)) {
                $this->file_03->ViewValue = $this->file_03->Upload->DbValue;
            } else {
                $this->file_03->ViewValue = "";
            }
            $this->file_03->ViewCustomAttributes = "";

            // file_04
            if (!EmptyValue($this->file_04->Upload->DbValue)) {
                $this->file_04->ViewValue = $this->file_04->Upload->DbValue;
            } else {
                $this->file_04->ViewValue = "";
            }
            $this->file_04->ViewCustomAttributes = "";

            // file_05
            if (!EmptyValue($this->file_05->Upload->DbValue)) {
                $this->file_05->ViewValue = $this->file_05->Upload->DbValue;
            } else {
                $this->file_05->ViewValue = "";
            }
            $this->file_05->ViewCustomAttributes = "";

            // file_06
            if (!EmptyValue($this->file_06->Upload->DbValue)) {
                $this->file_06->ViewValue = $this->file_06->Upload->DbValue;
            } else {
                $this->file_06->ViewValue = "";
            }
            $this->file_06->ViewCustomAttributes = "";

            // file_07
            if (!EmptyValue($this->file_07->Upload->DbValue)) {
                $this->file_07->ViewValue = $this->file_07->Upload->DbValue;
            } else {
                $this->file_07->ViewValue = "";
            }
            $this->file_07->ViewCustomAttributes = "";

            // file_08
            if (!EmptyValue($this->file_08->Upload->DbValue)) {
                $this->file_08->ViewValue = $this->file_08->Upload->DbValue;
            } else {
                $this->file_08->ViewValue = "";
            }
            $this->file_08->ViewCustomAttributes = "";

            // file_09
            if (!EmptyValue($this->file_09->Upload->DbValue)) {
                $this->file_09->ViewValue = $this->file_09->Upload->DbValue;
            } else {
                $this->file_09->ViewValue = "";
            }
            $this->file_09->ViewCustomAttributes = "";

            // file_10
            if (!EmptyValue($this->file_10->Upload->DbValue)) {
                $this->file_10->ViewValue = $this->file_10->Upload->DbValue;
            } else {
                $this->file_10->ViewValue = "";
            }
            $this->file_10->ViewCustomAttributes = "";

            // file_11
            if (!EmptyValue($this->file_11->Upload->DbValue)) {
                $this->file_11->ViewValue = $this->file_11->Upload->DbValue;
            } else {
                $this->file_11->ViewValue = "";
            }
            $this->file_11->ViewCustomAttributes = "";

            // file_12
            if (!EmptyValue($this->file_12->Upload->DbValue)) {
                $this->file_12->ViewValue = $this->file_12->Upload->DbValue;
            } else {
                $this->file_12->ViewValue = "";
            }
            $this->file_12->ViewCustomAttributes = "";

            // file_13
            if (!EmptyValue($this->file_13->Upload->DbValue)) {
                $this->file_13->ViewValue = $this->file_13->Upload->DbValue;
            } else {
                $this->file_13->ViewValue = "";
            }
            $this->file_13->ViewCustomAttributes = "";

            // file_14
            if (!EmptyValue($this->file_14->Upload->DbValue)) {
                $this->file_14->ViewValue = $this->file_14->Upload->DbValue;
            } else {
                $this->file_14->ViewValue = "";
            }
            $this->file_14->ViewCustomAttributes = "";

            // file_15
            if (!EmptyValue($this->file_15->Upload->DbValue)) {
                $this->file_15->ViewValue = $this->file_15->Upload->DbValue;
            } else {
                $this->file_15->ViewValue = "";
            }
            $this->file_15->ViewCustomAttributes = "";

            // file_16
            if (!EmptyValue($this->file_16->Upload->DbValue)) {
                $this->file_16->ViewValue = $this->file_16->Upload->DbValue;
            } else {
                $this->file_16->ViewValue = "";
            }
            $this->file_16->ViewCustomAttributes = "";

            // file_17
            if (!EmptyValue($this->file_17->Upload->DbValue)) {
                $this->file_17->ViewValue = $this->file_17->Upload->DbValue;
            } else {
                $this->file_17->ViewValue = "";
            }
            $this->file_17->ViewCustomAttributes = "";

            // file_18
            if (!EmptyValue($this->file_18->Upload->DbValue)) {
                $this->file_18->ViewValue = $this->file_18->Upload->DbValue;
            } else {
                $this->file_18->ViewValue = "";
            }
            $this->file_18->ViewCustomAttributes = "";

            // file_19
            if (!EmptyValue($this->file_19->Upload->DbValue)) {
                $this->file_19->ViewValue = $this->file_19->Upload->DbValue;
            } else {
                $this->file_19->ViewValue = "";
            }
            $this->file_19->ViewCustomAttributes = "";

            // file_20
            if (!EmptyValue($this->file_20->Upload->DbValue)) {
                $this->file_20->ViewValue = $this->file_20->Upload->DbValue;
            } else {
                $this->file_20->ViewValue = "";
            }
            $this->file_20->ViewCustomAttributes = "";

            // file_21
            if (!EmptyValue($this->file_21->Upload->DbValue)) {
                $this->file_21->ViewValue = $this->file_21->Upload->DbValue;
            } else {
                $this->file_21->ViewValue = "";
            }
            $this->file_21->ViewCustomAttributes = "";

            // file_22
            if (!EmptyValue($this->file_22->Upload->DbValue)) {
                $this->file_22->ViewValue = $this->file_22->Upload->DbValue;
            } else {
                $this->file_22->ViewValue = "";
            }
            $this->file_22->ViewCustomAttributes = "";

            // file_23
            if (!EmptyValue($this->file_23->Upload->DbValue)) {
                $this->file_23->ViewValue = $this->file_23->Upload->DbValue;
            } else {
                $this->file_23->ViewValue = "";
            }
            $this->file_23->ViewCustomAttributes = "";

            // file_24
            if (!EmptyValue($this->file_24->Upload->DbValue)) {
                $this->file_24->ViewValue = $this->file_24->Upload->DbValue;
            } else {
                $this->file_24->ViewValue = "";
            }
            $this->file_24->ViewCustomAttributes = "";

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
            $this->file_01->ExportHrefValue = $this->file_01->UploadPath . $this->file_01->Upload->DbValue;
            $this->file_01->TooltipValue = "";

            // file_02
            $this->file_02->LinkCustomAttributes = "";
            $this->file_02->HrefValue = "";
            $this->file_02->ExportHrefValue = $this->file_02->UploadPath . $this->file_02->Upload->DbValue;
            $this->file_02->TooltipValue = "";

            // file_03
            $this->file_03->LinkCustomAttributes = "";
            $this->file_03->HrefValue = "";
            $this->file_03->ExportHrefValue = $this->file_03->UploadPath . $this->file_03->Upload->DbValue;
            $this->file_03->TooltipValue = "";

            // file_04
            $this->file_04->LinkCustomAttributes = "";
            $this->file_04->HrefValue = "";
            $this->file_04->ExportHrefValue = $this->file_04->UploadPath . $this->file_04->Upload->DbValue;
            $this->file_04->TooltipValue = "";

            // file_05
            $this->file_05->LinkCustomAttributes = "";
            $this->file_05->HrefValue = "";
            $this->file_05->ExportHrefValue = $this->file_05->UploadPath . $this->file_05->Upload->DbValue;
            $this->file_05->TooltipValue = "";

            // file_06
            $this->file_06->LinkCustomAttributes = "";
            $this->file_06->HrefValue = "";
            $this->file_06->ExportHrefValue = $this->file_06->UploadPath . $this->file_06->Upload->DbValue;
            $this->file_06->TooltipValue = "";

            // file_07
            $this->file_07->LinkCustomAttributes = "";
            $this->file_07->HrefValue = "";
            $this->file_07->ExportHrefValue = $this->file_07->UploadPath . $this->file_07->Upload->DbValue;
            $this->file_07->TooltipValue = "";

            // file_08
            $this->file_08->LinkCustomAttributes = "";
            $this->file_08->HrefValue = "";
            $this->file_08->ExportHrefValue = $this->file_08->UploadPath . $this->file_08->Upload->DbValue;
            $this->file_08->TooltipValue = "";

            // file_09
            $this->file_09->LinkCustomAttributes = "";
            $this->file_09->HrefValue = "";
            $this->file_09->ExportHrefValue = $this->file_09->UploadPath . $this->file_09->Upload->DbValue;
            $this->file_09->TooltipValue = "";

            // file_10
            $this->file_10->LinkCustomAttributes = "";
            $this->file_10->HrefValue = "";
            $this->file_10->ExportHrefValue = $this->file_10->UploadPath . $this->file_10->Upload->DbValue;
            $this->file_10->TooltipValue = "";

            // file_11
            $this->file_11->LinkCustomAttributes = "";
            $this->file_11->HrefValue = "";
            $this->file_11->ExportHrefValue = $this->file_11->UploadPath . $this->file_11->Upload->DbValue;
            $this->file_11->TooltipValue = "";

            // file_12
            $this->file_12->LinkCustomAttributes = "";
            $this->file_12->HrefValue = "";
            $this->file_12->ExportHrefValue = $this->file_12->UploadPath . $this->file_12->Upload->DbValue;
            $this->file_12->TooltipValue = "";

            // file_13
            $this->file_13->LinkCustomAttributes = "";
            $this->file_13->HrefValue = "";
            $this->file_13->ExportHrefValue = $this->file_13->UploadPath . $this->file_13->Upload->DbValue;
            $this->file_13->TooltipValue = "";

            // file_14
            $this->file_14->LinkCustomAttributes = "";
            $this->file_14->HrefValue = "";
            $this->file_14->ExportHrefValue = $this->file_14->UploadPath . $this->file_14->Upload->DbValue;
            $this->file_14->TooltipValue = "";

            // file_15
            $this->file_15->LinkCustomAttributes = "";
            $this->file_15->HrefValue = "";
            $this->file_15->ExportHrefValue = $this->file_15->UploadPath . $this->file_15->Upload->DbValue;
            $this->file_15->TooltipValue = "";

            // file_16
            $this->file_16->LinkCustomAttributes = "";
            $this->file_16->HrefValue = "";
            $this->file_16->ExportHrefValue = $this->file_16->UploadPath . $this->file_16->Upload->DbValue;
            $this->file_16->TooltipValue = "";

            // file_17
            $this->file_17->LinkCustomAttributes = "";
            $this->file_17->HrefValue = "";
            $this->file_17->ExportHrefValue = $this->file_17->UploadPath . $this->file_17->Upload->DbValue;
            $this->file_17->TooltipValue = "";

            // file_18
            $this->file_18->LinkCustomAttributes = "";
            $this->file_18->HrefValue = "";
            $this->file_18->ExportHrefValue = $this->file_18->UploadPath . $this->file_18->Upload->DbValue;
            $this->file_18->TooltipValue = "";

            // file_19
            $this->file_19->LinkCustomAttributes = "";
            $this->file_19->HrefValue = "";
            $this->file_19->ExportHrefValue = $this->file_19->UploadPath . $this->file_19->Upload->DbValue;
            $this->file_19->TooltipValue = "";

            // file_20
            $this->file_20->LinkCustomAttributes = "";
            $this->file_20->HrefValue = "";
            $this->file_20->ExportHrefValue = $this->file_20->UploadPath . $this->file_20->Upload->DbValue;
            $this->file_20->TooltipValue = "";

            // file_21
            $this->file_21->LinkCustomAttributes = "";
            $this->file_21->HrefValue = "";
            $this->file_21->ExportHrefValue = $this->file_21->UploadPath . $this->file_21->Upload->DbValue;
            $this->file_21->TooltipValue = "";

            // file_22
            $this->file_22->LinkCustomAttributes = "";
            $this->file_22->HrefValue = "";
            $this->file_22->ExportHrefValue = $this->file_22->UploadPath . $this->file_22->Upload->DbValue;
            $this->file_22->TooltipValue = "";

            // file_23
            $this->file_23->LinkCustomAttributes = "";
            $this->file_23->HrefValue = "";
            $this->file_23->ExportHrefValue = $this->file_23->UploadPath . $this->file_23->Upload->DbValue;
            $this->file_23->TooltipValue = "";

            // file_24
            $this->file_24->LinkCustomAttributes = "";
            $this->file_24->HrefValue = "";
            $this->file_24->ExportHrefValue = $this->file_24->UploadPath . $this->file_24->Upload->DbValue;
            $this->file_24->TooltipValue = "";

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

            // idd_wilayah
            $this->idd_wilayah->EditAttrs["class"] = "form-control";
            $this->idd_wilayah->EditCustomAttributes = "";
            $this->idd_wilayah->EditValue = HtmlEncode($this->idd_wilayah->CurrentValue);
            $this->idd_wilayah->PlaceHolder = RemoveHtml($this->idd_wilayah->caption());

            // file_01
            $this->file_01->EditAttrs["class"] = "form-control";
            $this->file_01->EditCustomAttributes = "";
            if (!EmptyValue($this->file_01->Upload->DbValue)) {
                $this->file_01->EditValue = $this->file_01->Upload->DbValue;
            } else {
                $this->file_01->EditValue = "";
            }
            if (!EmptyValue($this->file_01->CurrentValue)) {
                $this->file_01->Upload->FileName = $this->file_01->CurrentValue;
            }
            if ($this->isShow()) {
                RenderUploadField($this->file_01);
            }

            // file_02
            $this->file_02->EditAttrs["class"] = "form-control";
            $this->file_02->EditCustomAttributes = "";
            if (!EmptyValue($this->file_02->Upload->DbValue)) {
                $this->file_02->EditValue = $this->file_02->Upload->DbValue;
            } else {
                $this->file_02->EditValue = "";
            }
            if (!EmptyValue($this->file_02->CurrentValue)) {
                $this->file_02->Upload->FileName = $this->file_02->CurrentValue;
            }
            if ($this->isShow()) {
                RenderUploadField($this->file_02);
            }

            // file_03
            $this->file_03->EditAttrs["class"] = "form-control";
            $this->file_03->EditCustomAttributes = "";
            if (!EmptyValue($this->file_03->Upload->DbValue)) {
                $this->file_03->EditValue = $this->file_03->Upload->DbValue;
            } else {
                $this->file_03->EditValue = "";
            }
            if (!EmptyValue($this->file_03->CurrentValue)) {
                $this->file_03->Upload->FileName = $this->file_03->CurrentValue;
            }
            if ($this->isShow()) {
                RenderUploadField($this->file_03);
            }

            // file_04
            $this->file_04->EditAttrs["class"] = "form-control";
            $this->file_04->EditCustomAttributes = "";
            if (!EmptyValue($this->file_04->Upload->DbValue)) {
                $this->file_04->EditValue = $this->file_04->Upload->DbValue;
            } else {
                $this->file_04->EditValue = "";
            }
            if (!EmptyValue($this->file_04->CurrentValue)) {
                $this->file_04->Upload->FileName = $this->file_04->CurrentValue;
            }
            if ($this->isShow()) {
                RenderUploadField($this->file_04);
            }

            // file_05
            $this->file_05->EditAttrs["class"] = "form-control";
            $this->file_05->EditCustomAttributes = "";
            if (!EmptyValue($this->file_05->Upload->DbValue)) {
                $this->file_05->EditValue = $this->file_05->Upload->DbValue;
            } else {
                $this->file_05->EditValue = "";
            }
            if (!EmptyValue($this->file_05->CurrentValue)) {
                $this->file_05->Upload->FileName = $this->file_05->CurrentValue;
            }
            if ($this->isShow()) {
                RenderUploadField($this->file_05);
            }

            // file_06
            $this->file_06->EditAttrs["class"] = "form-control";
            $this->file_06->EditCustomAttributes = "";
            if (!EmptyValue($this->file_06->Upload->DbValue)) {
                $this->file_06->EditValue = $this->file_06->Upload->DbValue;
            } else {
                $this->file_06->EditValue = "";
            }
            if (!EmptyValue($this->file_06->CurrentValue)) {
                $this->file_06->Upload->FileName = $this->file_06->CurrentValue;
            }
            if ($this->isShow()) {
                RenderUploadField($this->file_06);
            }

            // file_07
            $this->file_07->EditAttrs["class"] = "form-control";
            $this->file_07->EditCustomAttributes = "";
            if (!EmptyValue($this->file_07->Upload->DbValue)) {
                $this->file_07->EditValue = $this->file_07->Upload->DbValue;
            } else {
                $this->file_07->EditValue = "";
            }
            if (!EmptyValue($this->file_07->CurrentValue)) {
                $this->file_07->Upload->FileName = $this->file_07->CurrentValue;
            }
            if ($this->isShow()) {
                RenderUploadField($this->file_07);
            }

            // file_08
            $this->file_08->EditAttrs["class"] = "form-control";
            $this->file_08->EditCustomAttributes = "";
            if (!EmptyValue($this->file_08->Upload->DbValue)) {
                $this->file_08->EditValue = $this->file_08->Upload->DbValue;
            } else {
                $this->file_08->EditValue = "";
            }
            if (!EmptyValue($this->file_08->CurrentValue)) {
                $this->file_08->Upload->FileName = $this->file_08->CurrentValue;
            }
            if ($this->isShow()) {
                RenderUploadField($this->file_08);
            }

            // file_09
            $this->file_09->EditAttrs["class"] = "form-control";
            $this->file_09->EditCustomAttributes = "";
            if (!EmptyValue($this->file_09->Upload->DbValue)) {
                $this->file_09->EditValue = $this->file_09->Upload->DbValue;
            } else {
                $this->file_09->EditValue = "";
            }
            if (!EmptyValue($this->file_09->CurrentValue)) {
                $this->file_09->Upload->FileName = $this->file_09->CurrentValue;
            }
            if ($this->isShow()) {
                RenderUploadField($this->file_09);
            }

            // file_10
            $this->file_10->EditAttrs["class"] = "form-control";
            $this->file_10->EditCustomAttributes = "";
            if (!EmptyValue($this->file_10->Upload->DbValue)) {
                $this->file_10->EditValue = $this->file_10->Upload->DbValue;
            } else {
                $this->file_10->EditValue = "";
            }
            if (!EmptyValue($this->file_10->CurrentValue)) {
                $this->file_10->Upload->FileName = $this->file_10->CurrentValue;
            }
            if ($this->isShow()) {
                RenderUploadField($this->file_10);
            }

            // file_11
            $this->file_11->EditAttrs["class"] = "form-control";
            $this->file_11->EditCustomAttributes = "";
            if (!EmptyValue($this->file_11->Upload->DbValue)) {
                $this->file_11->EditValue = $this->file_11->Upload->DbValue;
            } else {
                $this->file_11->EditValue = "";
            }
            if (!EmptyValue($this->file_11->CurrentValue)) {
                $this->file_11->Upload->FileName = $this->file_11->CurrentValue;
            }
            if ($this->isShow()) {
                RenderUploadField($this->file_11);
            }

            // file_12
            $this->file_12->EditAttrs["class"] = "form-control";
            $this->file_12->EditCustomAttributes = "";
            if (!EmptyValue($this->file_12->Upload->DbValue)) {
                $this->file_12->EditValue = $this->file_12->Upload->DbValue;
            } else {
                $this->file_12->EditValue = "";
            }
            if (!EmptyValue($this->file_12->CurrentValue)) {
                $this->file_12->Upload->FileName = $this->file_12->CurrentValue;
            }
            if ($this->isShow()) {
                RenderUploadField($this->file_12);
            }

            // file_13
            $this->file_13->EditAttrs["class"] = "form-control";
            $this->file_13->EditCustomAttributes = "";
            if (!EmptyValue($this->file_13->Upload->DbValue)) {
                $this->file_13->EditValue = $this->file_13->Upload->DbValue;
            } else {
                $this->file_13->EditValue = "";
            }
            if (!EmptyValue($this->file_13->CurrentValue)) {
                $this->file_13->Upload->FileName = $this->file_13->CurrentValue;
            }
            if ($this->isShow()) {
                RenderUploadField($this->file_13);
            }

            // file_14
            $this->file_14->EditAttrs["class"] = "form-control";
            $this->file_14->EditCustomAttributes = "";
            if (!EmptyValue($this->file_14->Upload->DbValue)) {
                $this->file_14->EditValue = $this->file_14->Upload->DbValue;
            } else {
                $this->file_14->EditValue = "";
            }
            if (!EmptyValue($this->file_14->CurrentValue)) {
                $this->file_14->Upload->FileName = $this->file_14->CurrentValue;
            }
            if ($this->isShow()) {
                RenderUploadField($this->file_14);
            }

            // file_15
            $this->file_15->EditAttrs["class"] = "form-control";
            $this->file_15->EditCustomAttributes = "";
            if (!EmptyValue($this->file_15->Upload->DbValue)) {
                $this->file_15->EditValue = $this->file_15->Upload->DbValue;
            } else {
                $this->file_15->EditValue = "";
            }
            if (!EmptyValue($this->file_15->CurrentValue)) {
                $this->file_15->Upload->FileName = $this->file_15->CurrentValue;
            }
            if ($this->isShow()) {
                RenderUploadField($this->file_15);
            }

            // file_16
            $this->file_16->EditAttrs["class"] = "form-control";
            $this->file_16->EditCustomAttributes = "";
            if (!EmptyValue($this->file_16->Upload->DbValue)) {
                $this->file_16->EditValue = $this->file_16->Upload->DbValue;
            } else {
                $this->file_16->EditValue = "";
            }
            if (!EmptyValue($this->file_16->CurrentValue)) {
                $this->file_16->Upload->FileName = $this->file_16->CurrentValue;
            }
            if ($this->isShow()) {
                RenderUploadField($this->file_16);
            }

            // file_17
            $this->file_17->EditAttrs["class"] = "form-control";
            $this->file_17->EditCustomAttributes = "";
            if (!EmptyValue($this->file_17->Upload->DbValue)) {
                $this->file_17->EditValue = $this->file_17->Upload->DbValue;
            } else {
                $this->file_17->EditValue = "";
            }
            if (!EmptyValue($this->file_17->CurrentValue)) {
                $this->file_17->Upload->FileName = $this->file_17->CurrentValue;
            }
            if ($this->isShow()) {
                RenderUploadField($this->file_17);
            }

            // file_18
            $this->file_18->EditAttrs["class"] = "form-control";
            $this->file_18->EditCustomAttributes = "";
            if (!EmptyValue($this->file_18->Upload->DbValue)) {
                $this->file_18->EditValue = $this->file_18->Upload->DbValue;
            } else {
                $this->file_18->EditValue = "";
            }
            if (!EmptyValue($this->file_18->CurrentValue)) {
                $this->file_18->Upload->FileName = $this->file_18->CurrentValue;
            }
            if ($this->isShow()) {
                RenderUploadField($this->file_18);
            }

            // file_19
            $this->file_19->EditAttrs["class"] = "form-control";
            $this->file_19->EditCustomAttributes = "";
            if (!EmptyValue($this->file_19->Upload->DbValue)) {
                $this->file_19->EditValue = $this->file_19->Upload->DbValue;
            } else {
                $this->file_19->EditValue = "";
            }
            if (!EmptyValue($this->file_19->CurrentValue)) {
                $this->file_19->Upload->FileName = $this->file_19->CurrentValue;
            }
            if ($this->isShow()) {
                RenderUploadField($this->file_19);
            }

            // file_20
            $this->file_20->EditAttrs["class"] = "form-control";
            $this->file_20->EditCustomAttributes = "";
            if (!EmptyValue($this->file_20->Upload->DbValue)) {
                $this->file_20->EditValue = $this->file_20->Upload->DbValue;
            } else {
                $this->file_20->EditValue = "";
            }
            if (!EmptyValue($this->file_20->CurrentValue)) {
                $this->file_20->Upload->FileName = $this->file_20->CurrentValue;
            }
            if ($this->isShow()) {
                RenderUploadField($this->file_20);
            }

            // file_21
            $this->file_21->EditAttrs["class"] = "form-control";
            $this->file_21->EditCustomAttributes = "";
            if (!EmptyValue($this->file_21->Upload->DbValue)) {
                $this->file_21->EditValue = $this->file_21->Upload->DbValue;
            } else {
                $this->file_21->EditValue = "";
            }
            if (!EmptyValue($this->file_21->CurrentValue)) {
                $this->file_21->Upload->FileName = $this->file_21->CurrentValue;
            }
            if ($this->isShow()) {
                RenderUploadField($this->file_21);
            }

            // file_22
            $this->file_22->EditAttrs["class"] = "form-control";
            $this->file_22->EditCustomAttributes = "";
            if (!EmptyValue($this->file_22->Upload->DbValue)) {
                $this->file_22->EditValue = $this->file_22->Upload->DbValue;
            } else {
                $this->file_22->EditValue = "";
            }
            if (!EmptyValue($this->file_22->CurrentValue)) {
                $this->file_22->Upload->FileName = $this->file_22->CurrentValue;
            }
            if ($this->isShow()) {
                RenderUploadField($this->file_22);
            }

            // file_23
            $this->file_23->EditAttrs["class"] = "form-control";
            $this->file_23->EditCustomAttributes = "";
            if (!EmptyValue($this->file_23->Upload->DbValue)) {
                $this->file_23->EditValue = $this->file_23->Upload->DbValue;
            } else {
                $this->file_23->EditValue = "";
            }
            if (!EmptyValue($this->file_23->CurrentValue)) {
                $this->file_23->Upload->FileName = $this->file_23->CurrentValue;
            }
            if ($this->isShow()) {
                RenderUploadField($this->file_23);
            }

            // file_24
            $this->file_24->EditAttrs["class"] = "form-control";
            $this->file_24->EditCustomAttributes = "";
            if (!EmptyValue($this->file_24->Upload->DbValue)) {
                $this->file_24->EditValue = $this->file_24->Upload->DbValue;
            } else {
                $this->file_24->EditValue = "";
            }
            if (!EmptyValue($this->file_24->CurrentValue)) {
                $this->file_24->Upload->FileName = $this->file_24->CurrentValue;
            }
            if ($this->isShow()) {
                RenderUploadField($this->file_24);
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
            $this->file_01->ExportHrefValue = $this->file_01->UploadPath . $this->file_01->Upload->DbValue;

            // file_02
            $this->file_02->LinkCustomAttributes = "";
            $this->file_02->HrefValue = "";
            $this->file_02->ExportHrefValue = $this->file_02->UploadPath . $this->file_02->Upload->DbValue;

            // file_03
            $this->file_03->LinkCustomAttributes = "";
            $this->file_03->HrefValue = "";
            $this->file_03->ExportHrefValue = $this->file_03->UploadPath . $this->file_03->Upload->DbValue;

            // file_04
            $this->file_04->LinkCustomAttributes = "";
            $this->file_04->HrefValue = "";
            $this->file_04->ExportHrefValue = $this->file_04->UploadPath . $this->file_04->Upload->DbValue;

            // file_05
            $this->file_05->LinkCustomAttributes = "";
            $this->file_05->HrefValue = "";
            $this->file_05->ExportHrefValue = $this->file_05->UploadPath . $this->file_05->Upload->DbValue;

            // file_06
            $this->file_06->LinkCustomAttributes = "";
            $this->file_06->HrefValue = "";
            $this->file_06->ExportHrefValue = $this->file_06->UploadPath . $this->file_06->Upload->DbValue;

            // file_07
            $this->file_07->LinkCustomAttributes = "";
            $this->file_07->HrefValue = "";
            $this->file_07->ExportHrefValue = $this->file_07->UploadPath . $this->file_07->Upload->DbValue;

            // file_08
            $this->file_08->LinkCustomAttributes = "";
            $this->file_08->HrefValue = "";
            $this->file_08->ExportHrefValue = $this->file_08->UploadPath . $this->file_08->Upload->DbValue;

            // file_09
            $this->file_09->LinkCustomAttributes = "";
            $this->file_09->HrefValue = "";
            $this->file_09->ExportHrefValue = $this->file_09->UploadPath . $this->file_09->Upload->DbValue;

            // file_10
            $this->file_10->LinkCustomAttributes = "";
            $this->file_10->HrefValue = "";
            $this->file_10->ExportHrefValue = $this->file_10->UploadPath . $this->file_10->Upload->DbValue;

            // file_11
            $this->file_11->LinkCustomAttributes = "";
            $this->file_11->HrefValue = "";
            $this->file_11->ExportHrefValue = $this->file_11->UploadPath . $this->file_11->Upload->DbValue;

            // file_12
            $this->file_12->LinkCustomAttributes = "";
            $this->file_12->HrefValue = "";
            $this->file_12->ExportHrefValue = $this->file_12->UploadPath . $this->file_12->Upload->DbValue;

            // file_13
            $this->file_13->LinkCustomAttributes = "";
            $this->file_13->HrefValue = "";
            $this->file_13->ExportHrefValue = $this->file_13->UploadPath . $this->file_13->Upload->DbValue;

            // file_14
            $this->file_14->LinkCustomAttributes = "";
            $this->file_14->HrefValue = "";
            $this->file_14->ExportHrefValue = $this->file_14->UploadPath . $this->file_14->Upload->DbValue;

            // file_15
            $this->file_15->LinkCustomAttributes = "";
            $this->file_15->HrefValue = "";
            $this->file_15->ExportHrefValue = $this->file_15->UploadPath . $this->file_15->Upload->DbValue;

            // file_16
            $this->file_16->LinkCustomAttributes = "";
            $this->file_16->HrefValue = "";
            $this->file_16->ExportHrefValue = $this->file_16->UploadPath . $this->file_16->Upload->DbValue;

            // file_17
            $this->file_17->LinkCustomAttributes = "";
            $this->file_17->HrefValue = "";
            $this->file_17->ExportHrefValue = $this->file_17->UploadPath . $this->file_17->Upload->DbValue;

            // file_18
            $this->file_18->LinkCustomAttributes = "";
            $this->file_18->HrefValue = "";
            $this->file_18->ExportHrefValue = $this->file_18->UploadPath . $this->file_18->Upload->DbValue;

            // file_19
            $this->file_19->LinkCustomAttributes = "";
            $this->file_19->HrefValue = "";
            $this->file_19->ExportHrefValue = $this->file_19->UploadPath . $this->file_19->Upload->DbValue;

            // file_20
            $this->file_20->LinkCustomAttributes = "";
            $this->file_20->HrefValue = "";
            $this->file_20->ExportHrefValue = $this->file_20->UploadPath . $this->file_20->Upload->DbValue;

            // file_21
            $this->file_21->LinkCustomAttributes = "";
            $this->file_21->HrefValue = "";
            $this->file_21->ExportHrefValue = $this->file_21->UploadPath . $this->file_21->Upload->DbValue;

            // file_22
            $this->file_22->LinkCustomAttributes = "";
            $this->file_22->HrefValue = "";
            $this->file_22->ExportHrefValue = $this->file_22->UploadPath . $this->file_22->Upload->DbValue;

            // file_23
            $this->file_23->LinkCustomAttributes = "";
            $this->file_23->HrefValue = "";
            $this->file_23->ExportHrefValue = $this->file_23->UploadPath . $this->file_23->Upload->DbValue;

            // file_24
            $this->file_24->LinkCustomAttributes = "";
            $this->file_24->HrefValue = "";
            $this->file_24->ExportHrefValue = $this->file_24->UploadPath . $this->file_24->Upload->DbValue;

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
        if ($this->idd_wilayah->Required) {
            if (!$this->idd_wilayah->IsDetailKey && EmptyValue($this->idd_wilayah->FormValue)) {
                $this->idd_wilayah->addErrorMessage(str_replace("%s", $this->idd_wilayah->caption(), $this->idd_wilayah->RequiredErrorMessage));
            }
        }
        if (!CheckInteger($this->idd_wilayah->FormValue)) {
            $this->idd_wilayah->addErrorMessage($this->idd_wilayah->getErrorMessage(false));
        }
        if ($this->file_01->Required) {
            if ($this->file_01->Upload->FileName == "" && !$this->file_01->Upload->KeepFile) {
                $this->file_01->addErrorMessage(str_replace("%s", $this->file_01->caption(), $this->file_01->RequiredErrorMessage));
            }
        }
        if ($this->file_02->Required) {
            if ($this->file_02->Upload->FileName == "" && !$this->file_02->Upload->KeepFile) {
                $this->file_02->addErrorMessage(str_replace("%s", $this->file_02->caption(), $this->file_02->RequiredErrorMessage));
            }
        }
        if ($this->file_03->Required) {
            if ($this->file_03->Upload->FileName == "" && !$this->file_03->Upload->KeepFile) {
                $this->file_03->addErrorMessage(str_replace("%s", $this->file_03->caption(), $this->file_03->RequiredErrorMessage));
            }
        }
        if ($this->file_04->Required) {
            if ($this->file_04->Upload->FileName == "" && !$this->file_04->Upload->KeepFile) {
                $this->file_04->addErrorMessage(str_replace("%s", $this->file_04->caption(), $this->file_04->RequiredErrorMessage));
            }
        }
        if ($this->file_05->Required) {
            if ($this->file_05->Upload->FileName == "" && !$this->file_05->Upload->KeepFile) {
                $this->file_05->addErrorMessage(str_replace("%s", $this->file_05->caption(), $this->file_05->RequiredErrorMessage));
            }
        }
        if ($this->file_06->Required) {
            if ($this->file_06->Upload->FileName == "" && !$this->file_06->Upload->KeepFile) {
                $this->file_06->addErrorMessage(str_replace("%s", $this->file_06->caption(), $this->file_06->RequiredErrorMessage));
            }
        }
        if ($this->file_07->Required) {
            if ($this->file_07->Upload->FileName == "" && !$this->file_07->Upload->KeepFile) {
                $this->file_07->addErrorMessage(str_replace("%s", $this->file_07->caption(), $this->file_07->RequiredErrorMessage));
            }
        }
        if ($this->file_08->Required) {
            if ($this->file_08->Upload->FileName == "" && !$this->file_08->Upload->KeepFile) {
                $this->file_08->addErrorMessage(str_replace("%s", $this->file_08->caption(), $this->file_08->RequiredErrorMessage));
            }
        }
        if ($this->file_09->Required) {
            if ($this->file_09->Upload->FileName == "" && !$this->file_09->Upload->KeepFile) {
                $this->file_09->addErrorMessage(str_replace("%s", $this->file_09->caption(), $this->file_09->RequiredErrorMessage));
            }
        }
        if ($this->file_10->Required) {
            if ($this->file_10->Upload->FileName == "" && !$this->file_10->Upload->KeepFile) {
                $this->file_10->addErrorMessage(str_replace("%s", $this->file_10->caption(), $this->file_10->RequiredErrorMessage));
            }
        }
        if ($this->file_11->Required) {
            if ($this->file_11->Upload->FileName == "" && !$this->file_11->Upload->KeepFile) {
                $this->file_11->addErrorMessage(str_replace("%s", $this->file_11->caption(), $this->file_11->RequiredErrorMessage));
            }
        }
        if ($this->file_12->Required) {
            if ($this->file_12->Upload->FileName == "" && !$this->file_12->Upload->KeepFile) {
                $this->file_12->addErrorMessage(str_replace("%s", $this->file_12->caption(), $this->file_12->RequiredErrorMessage));
            }
        }
        if ($this->file_13->Required) {
            if ($this->file_13->Upload->FileName == "" && !$this->file_13->Upload->KeepFile) {
                $this->file_13->addErrorMessage(str_replace("%s", $this->file_13->caption(), $this->file_13->RequiredErrorMessage));
            }
        }
        if ($this->file_14->Required) {
            if ($this->file_14->Upload->FileName == "" && !$this->file_14->Upload->KeepFile) {
                $this->file_14->addErrorMessage(str_replace("%s", $this->file_14->caption(), $this->file_14->RequiredErrorMessage));
            }
        }
        if ($this->file_15->Required) {
            if ($this->file_15->Upload->FileName == "" && !$this->file_15->Upload->KeepFile) {
                $this->file_15->addErrorMessage(str_replace("%s", $this->file_15->caption(), $this->file_15->RequiredErrorMessage));
            }
        }
        if ($this->file_16->Required) {
            if ($this->file_16->Upload->FileName == "" && !$this->file_16->Upload->KeepFile) {
                $this->file_16->addErrorMessage(str_replace("%s", $this->file_16->caption(), $this->file_16->RequiredErrorMessage));
            }
        }
        if ($this->file_17->Required) {
            if ($this->file_17->Upload->FileName == "" && !$this->file_17->Upload->KeepFile) {
                $this->file_17->addErrorMessage(str_replace("%s", $this->file_17->caption(), $this->file_17->RequiredErrorMessage));
            }
        }
        if ($this->file_18->Required) {
            if ($this->file_18->Upload->FileName == "" && !$this->file_18->Upload->KeepFile) {
                $this->file_18->addErrorMessage(str_replace("%s", $this->file_18->caption(), $this->file_18->RequiredErrorMessage));
            }
        }
        if ($this->file_19->Required) {
            if ($this->file_19->Upload->FileName == "" && !$this->file_19->Upload->KeepFile) {
                $this->file_19->addErrorMessage(str_replace("%s", $this->file_19->caption(), $this->file_19->RequiredErrorMessage));
            }
        }
        if ($this->file_20->Required) {
            if ($this->file_20->Upload->FileName == "" && !$this->file_20->Upload->KeepFile) {
                $this->file_20->addErrorMessage(str_replace("%s", $this->file_20->caption(), $this->file_20->RequiredErrorMessage));
            }
        }
        if ($this->file_21->Required) {
            if ($this->file_21->Upload->FileName == "" && !$this->file_21->Upload->KeepFile) {
                $this->file_21->addErrorMessage(str_replace("%s", $this->file_21->caption(), $this->file_21->RequiredErrorMessage));
            }
        }
        if ($this->file_22->Required) {
            if ($this->file_22->Upload->FileName == "" && !$this->file_22->Upload->KeepFile) {
                $this->file_22->addErrorMessage(str_replace("%s", $this->file_22->caption(), $this->file_22->RequiredErrorMessage));
            }
        }
        if ($this->file_23->Required) {
            if ($this->file_23->Upload->FileName == "" && !$this->file_23->Upload->KeepFile) {
                $this->file_23->addErrorMessage(str_replace("%s", $this->file_23->caption(), $this->file_23->RequiredErrorMessage));
            }
        }
        if ($this->file_24->Required) {
            if ($this->file_24->Upload->FileName == "" && !$this->file_24->Upload->KeepFile) {
                $this->file_24->addErrorMessage(str_replace("%s", $this->file_24->caption(), $this->file_24->RequiredErrorMessage));
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

            // kd_satker
            $this->kd_satker->setDbValueDef($rsnew, $this->kd_satker->CurrentValue, "", $this->kd_satker->ReadOnly);

            // idd_tahapan
            $this->idd_tahapan->setDbValueDef($rsnew, $this->idd_tahapan->CurrentValue, 0, $this->idd_tahapan->ReadOnly);

            // tahun_anggaran
            $this->tahun_anggaran->setDbValueDef($rsnew, $this->tahun_anggaran->CurrentValue, "", $this->tahun_anggaran->ReadOnly);

            // idd_wilayah
            $this->idd_wilayah->setDbValueDef($rsnew, $this->idd_wilayah->CurrentValue, 0, $this->idd_wilayah->ReadOnly);

            // file_01
            if ($this->file_01->Visible && !$this->file_01->ReadOnly && !$this->file_01->Upload->KeepFile) {
                $this->file_01->Upload->DbValue = $rsold['file_01']; // Get original value
                if ($this->file_01->Upload->FileName == "") {
                    $rsnew['file_01'] = null;
                } else {
                    $rsnew['file_01'] = $this->file_01->Upload->FileName;
                }
            }

            // file_02
            if ($this->file_02->Visible && !$this->file_02->ReadOnly && !$this->file_02->Upload->KeepFile) {
                $this->file_02->Upload->DbValue = $rsold['file_02']; // Get original value
                if ($this->file_02->Upload->FileName == "") {
                    $rsnew['file_02'] = null;
                } else {
                    $rsnew['file_02'] = $this->file_02->Upload->FileName;
                }
            }

            // file_03
            if ($this->file_03->Visible && !$this->file_03->ReadOnly && !$this->file_03->Upload->KeepFile) {
                $this->file_03->Upload->DbValue = $rsold['file_03']; // Get original value
                if ($this->file_03->Upload->FileName == "") {
                    $rsnew['file_03'] = null;
                } else {
                    $rsnew['file_03'] = $this->file_03->Upload->FileName;
                }
            }

            // file_04
            if ($this->file_04->Visible && !$this->file_04->ReadOnly && !$this->file_04->Upload->KeepFile) {
                $this->file_04->Upload->DbValue = $rsold['file_04']; // Get original value
                if ($this->file_04->Upload->FileName == "") {
                    $rsnew['file_04'] = null;
                } else {
                    $rsnew['file_04'] = $this->file_04->Upload->FileName;
                }
            }

            // file_05
            if ($this->file_05->Visible && !$this->file_05->ReadOnly && !$this->file_05->Upload->KeepFile) {
                $this->file_05->Upload->DbValue = $rsold['file_05']; // Get original value
                if ($this->file_05->Upload->FileName == "") {
                    $rsnew['file_05'] = null;
                } else {
                    $rsnew['file_05'] = $this->file_05->Upload->FileName;
                }
            }

            // file_06
            if ($this->file_06->Visible && !$this->file_06->ReadOnly && !$this->file_06->Upload->KeepFile) {
                $this->file_06->Upload->DbValue = $rsold['file_06']; // Get original value
                if ($this->file_06->Upload->FileName == "") {
                    $rsnew['file_06'] = null;
                } else {
                    $rsnew['file_06'] = $this->file_06->Upload->FileName;
                }
            }

            // file_07
            if ($this->file_07->Visible && !$this->file_07->ReadOnly && !$this->file_07->Upload->KeepFile) {
                $this->file_07->Upload->DbValue = $rsold['file_07']; // Get original value
                if ($this->file_07->Upload->FileName == "") {
                    $rsnew['file_07'] = null;
                } else {
                    $rsnew['file_07'] = $this->file_07->Upload->FileName;
                }
            }

            // file_08
            if ($this->file_08->Visible && !$this->file_08->ReadOnly && !$this->file_08->Upload->KeepFile) {
                $this->file_08->Upload->DbValue = $rsold['file_08']; // Get original value
                if ($this->file_08->Upload->FileName == "") {
                    $rsnew['file_08'] = null;
                } else {
                    $rsnew['file_08'] = $this->file_08->Upload->FileName;
                }
            }

            // file_09
            if ($this->file_09->Visible && !$this->file_09->ReadOnly && !$this->file_09->Upload->KeepFile) {
                $this->file_09->Upload->DbValue = $rsold['file_09']; // Get original value
                if ($this->file_09->Upload->FileName == "") {
                    $rsnew['file_09'] = null;
                } else {
                    $rsnew['file_09'] = $this->file_09->Upload->FileName;
                }
            }

            // file_10
            if ($this->file_10->Visible && !$this->file_10->ReadOnly && !$this->file_10->Upload->KeepFile) {
                $this->file_10->Upload->DbValue = $rsold['file_10']; // Get original value
                if ($this->file_10->Upload->FileName == "") {
                    $rsnew['file_10'] = null;
                } else {
                    $rsnew['file_10'] = $this->file_10->Upload->FileName;
                }
            }

            // file_11
            if ($this->file_11->Visible && !$this->file_11->ReadOnly && !$this->file_11->Upload->KeepFile) {
                $this->file_11->Upload->DbValue = $rsold['file_11']; // Get original value
                if ($this->file_11->Upload->FileName == "") {
                    $rsnew['file_11'] = null;
                } else {
                    $rsnew['file_11'] = $this->file_11->Upload->FileName;
                }
            }

            // file_12
            if ($this->file_12->Visible && !$this->file_12->ReadOnly && !$this->file_12->Upload->KeepFile) {
                $this->file_12->Upload->DbValue = $rsold['file_12']; // Get original value
                if ($this->file_12->Upload->FileName == "") {
                    $rsnew['file_12'] = null;
                } else {
                    $rsnew['file_12'] = $this->file_12->Upload->FileName;
                }
            }

            // file_13
            if ($this->file_13->Visible && !$this->file_13->ReadOnly && !$this->file_13->Upload->KeepFile) {
                $this->file_13->Upload->DbValue = $rsold['file_13']; // Get original value
                if ($this->file_13->Upload->FileName == "") {
                    $rsnew['file_13'] = null;
                } else {
                    $rsnew['file_13'] = $this->file_13->Upload->FileName;
                }
            }

            // file_14
            if ($this->file_14->Visible && !$this->file_14->ReadOnly && !$this->file_14->Upload->KeepFile) {
                $this->file_14->Upload->DbValue = $rsold['file_14']; // Get original value
                if ($this->file_14->Upload->FileName == "") {
                    $rsnew['file_14'] = null;
                } else {
                    $rsnew['file_14'] = $this->file_14->Upload->FileName;
                }
            }

            // file_15
            if ($this->file_15->Visible && !$this->file_15->ReadOnly && !$this->file_15->Upload->KeepFile) {
                $this->file_15->Upload->DbValue = $rsold['file_15']; // Get original value
                if ($this->file_15->Upload->FileName == "") {
                    $rsnew['file_15'] = null;
                } else {
                    $rsnew['file_15'] = $this->file_15->Upload->FileName;
                }
            }

            // file_16
            if ($this->file_16->Visible && !$this->file_16->ReadOnly && !$this->file_16->Upload->KeepFile) {
                $this->file_16->Upload->DbValue = $rsold['file_16']; // Get original value
                if ($this->file_16->Upload->FileName == "") {
                    $rsnew['file_16'] = null;
                } else {
                    $rsnew['file_16'] = $this->file_16->Upload->FileName;
                }
            }

            // file_17
            if ($this->file_17->Visible && !$this->file_17->ReadOnly && !$this->file_17->Upload->KeepFile) {
                $this->file_17->Upload->DbValue = $rsold['file_17']; // Get original value
                if ($this->file_17->Upload->FileName == "") {
                    $rsnew['file_17'] = null;
                } else {
                    $rsnew['file_17'] = $this->file_17->Upload->FileName;
                }
            }

            // file_18
            if ($this->file_18->Visible && !$this->file_18->ReadOnly && !$this->file_18->Upload->KeepFile) {
                $this->file_18->Upload->DbValue = $rsold['file_18']; // Get original value
                if ($this->file_18->Upload->FileName == "") {
                    $rsnew['file_18'] = null;
                } else {
                    $rsnew['file_18'] = $this->file_18->Upload->FileName;
                }
            }

            // file_19
            if ($this->file_19->Visible && !$this->file_19->ReadOnly && !$this->file_19->Upload->KeepFile) {
                $this->file_19->Upload->DbValue = $rsold['file_19']; // Get original value
                if ($this->file_19->Upload->FileName == "") {
                    $rsnew['file_19'] = null;
                } else {
                    $rsnew['file_19'] = $this->file_19->Upload->FileName;
                }
            }

            // file_20
            if ($this->file_20->Visible && !$this->file_20->ReadOnly && !$this->file_20->Upload->KeepFile) {
                $this->file_20->Upload->DbValue = $rsold['file_20']; // Get original value
                if ($this->file_20->Upload->FileName == "") {
                    $rsnew['file_20'] = null;
                } else {
                    $rsnew['file_20'] = $this->file_20->Upload->FileName;
                }
            }

            // file_21
            if ($this->file_21->Visible && !$this->file_21->ReadOnly && !$this->file_21->Upload->KeepFile) {
                $this->file_21->Upload->DbValue = $rsold['file_21']; // Get original value
                if ($this->file_21->Upload->FileName == "") {
                    $rsnew['file_21'] = null;
                } else {
                    $rsnew['file_21'] = $this->file_21->Upload->FileName;
                }
            }

            // file_22
            if ($this->file_22->Visible && !$this->file_22->ReadOnly && !$this->file_22->Upload->KeepFile) {
                $this->file_22->Upload->DbValue = $rsold['file_22']; // Get original value
                if ($this->file_22->Upload->FileName == "") {
                    $rsnew['file_22'] = null;
                } else {
                    $rsnew['file_22'] = $this->file_22->Upload->FileName;
                }
            }

            // file_23
            if ($this->file_23->Visible && !$this->file_23->ReadOnly && !$this->file_23->Upload->KeepFile) {
                $this->file_23->Upload->DbValue = $rsold['file_23']; // Get original value
                if ($this->file_23->Upload->FileName == "") {
                    $rsnew['file_23'] = null;
                } else {
                    $rsnew['file_23'] = $this->file_23->Upload->FileName;
                }
            }

            // file_24
            if ($this->file_24->Visible && !$this->file_24->ReadOnly && !$this->file_24->Upload->KeepFile) {
                $this->file_24->Upload->DbValue = $rsold['file_24']; // Get original value
                if ($this->file_24->Upload->FileName == "") {
                    $rsnew['file_24'] = null;
                } else {
                    $rsnew['file_24'] = $this->file_24->Upload->FileName;
                }
            }

            // status
            $this->status->setDbValueDef($rsnew, $this->status->CurrentValue, 0, $this->status->ReadOnly);

            // idd_user
            $this->idd_user->setDbValueDef($rsnew, $this->idd_user->CurrentValue, 0, $this->idd_user->ReadOnly);
            if ($this->file_01->Visible && !$this->file_01->Upload->KeepFile) {
                $oldFiles = EmptyValue($this->file_01->Upload->DbValue) ? [] : [$this->file_01->htmlDecode($this->file_01->Upload->DbValue)];
                if (!EmptyValue($this->file_01->Upload->FileName)) {
                    $newFiles = [$this->file_01->Upload->FileName];
                    $NewFileCount = count($newFiles);
                    for ($i = 0; $i < $NewFileCount; $i++) {
                        if ($newFiles[$i] != "") {
                            $file = $newFiles[$i];
                            $tempPath = UploadTempPath($this->file_01, $this->file_01->Upload->Index);
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
                                $file1 = UniqueFilename($this->file_01->physicalUploadPath(), $file); // Get new file name
                                if ($file1 != $file) { // Rename temp file
                                    while (file_exists($tempPath . $file1) || file_exists($this->file_01->physicalUploadPath() . $file1)) { // Make sure no file name clash
                                        $file1 = UniqueFilename([$this->file_01->physicalUploadPath(), $tempPath], $file1, true); // Use indexed name
                                    }
                                    rename($tempPath . $file, $tempPath . $file1);
                                    $newFiles[$i] = $file1;
                                }
                            }
                        }
                    }
                    $this->file_01->Upload->DbValue = empty($oldFiles) ? "" : implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $oldFiles);
                    $this->file_01->Upload->FileName = implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $newFiles);
                    $this->file_01->setDbValueDef($rsnew, $this->file_01->Upload->FileName, "", $this->file_01->ReadOnly);
                }
            }
            if ($this->file_02->Visible && !$this->file_02->Upload->KeepFile) {
                $oldFiles = EmptyValue($this->file_02->Upload->DbValue) ? [] : [$this->file_02->htmlDecode($this->file_02->Upload->DbValue)];
                if (!EmptyValue($this->file_02->Upload->FileName)) {
                    $newFiles = [$this->file_02->Upload->FileName];
                    $NewFileCount = count($newFiles);
                    for ($i = 0; $i < $NewFileCount; $i++) {
                        if ($newFiles[$i] != "") {
                            $file = $newFiles[$i];
                            $tempPath = UploadTempPath($this->file_02, $this->file_02->Upload->Index);
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
                                $file1 = UniqueFilename($this->file_02->physicalUploadPath(), $file); // Get new file name
                                if ($file1 != $file) { // Rename temp file
                                    while (file_exists($tempPath . $file1) || file_exists($this->file_02->physicalUploadPath() . $file1)) { // Make sure no file name clash
                                        $file1 = UniqueFilename([$this->file_02->physicalUploadPath(), $tempPath], $file1, true); // Use indexed name
                                    }
                                    rename($tempPath . $file, $tempPath . $file1);
                                    $newFiles[$i] = $file1;
                                }
                            }
                        }
                    }
                    $this->file_02->Upload->DbValue = empty($oldFiles) ? "" : implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $oldFiles);
                    $this->file_02->Upload->FileName = implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $newFiles);
                    $this->file_02->setDbValueDef($rsnew, $this->file_02->Upload->FileName, "", $this->file_02->ReadOnly);
                }
            }
            if ($this->file_03->Visible && !$this->file_03->Upload->KeepFile) {
                $oldFiles = EmptyValue($this->file_03->Upload->DbValue) ? [] : [$this->file_03->htmlDecode($this->file_03->Upload->DbValue)];
                if (!EmptyValue($this->file_03->Upload->FileName)) {
                    $newFiles = [$this->file_03->Upload->FileName];
                    $NewFileCount = count($newFiles);
                    for ($i = 0; $i < $NewFileCount; $i++) {
                        if ($newFiles[$i] != "") {
                            $file = $newFiles[$i];
                            $tempPath = UploadTempPath($this->file_03, $this->file_03->Upload->Index);
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
                                $file1 = UniqueFilename($this->file_03->physicalUploadPath(), $file); // Get new file name
                                if ($file1 != $file) { // Rename temp file
                                    while (file_exists($tempPath . $file1) || file_exists($this->file_03->physicalUploadPath() . $file1)) { // Make sure no file name clash
                                        $file1 = UniqueFilename([$this->file_03->physicalUploadPath(), $tempPath], $file1, true); // Use indexed name
                                    }
                                    rename($tempPath . $file, $tempPath . $file1);
                                    $newFiles[$i] = $file1;
                                }
                            }
                        }
                    }
                    $this->file_03->Upload->DbValue = empty($oldFiles) ? "" : implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $oldFiles);
                    $this->file_03->Upload->FileName = implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $newFiles);
                    $this->file_03->setDbValueDef($rsnew, $this->file_03->Upload->FileName, "", $this->file_03->ReadOnly);
                }
            }
            if ($this->file_04->Visible && !$this->file_04->Upload->KeepFile) {
                $oldFiles = EmptyValue($this->file_04->Upload->DbValue) ? [] : [$this->file_04->htmlDecode($this->file_04->Upload->DbValue)];
                if (!EmptyValue($this->file_04->Upload->FileName)) {
                    $newFiles = [$this->file_04->Upload->FileName];
                    $NewFileCount = count($newFiles);
                    for ($i = 0; $i < $NewFileCount; $i++) {
                        if ($newFiles[$i] != "") {
                            $file = $newFiles[$i];
                            $tempPath = UploadTempPath($this->file_04, $this->file_04->Upload->Index);
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
                                $file1 = UniqueFilename($this->file_04->physicalUploadPath(), $file); // Get new file name
                                if ($file1 != $file) { // Rename temp file
                                    while (file_exists($tempPath . $file1) || file_exists($this->file_04->physicalUploadPath() . $file1)) { // Make sure no file name clash
                                        $file1 = UniqueFilename([$this->file_04->physicalUploadPath(), $tempPath], $file1, true); // Use indexed name
                                    }
                                    rename($tempPath . $file, $tempPath . $file1);
                                    $newFiles[$i] = $file1;
                                }
                            }
                        }
                    }
                    $this->file_04->Upload->DbValue = empty($oldFiles) ? "" : implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $oldFiles);
                    $this->file_04->Upload->FileName = implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $newFiles);
                    $this->file_04->setDbValueDef($rsnew, $this->file_04->Upload->FileName, "", $this->file_04->ReadOnly);
                }
            }
            if ($this->file_05->Visible && !$this->file_05->Upload->KeepFile) {
                $oldFiles = EmptyValue($this->file_05->Upload->DbValue) ? [] : [$this->file_05->htmlDecode($this->file_05->Upload->DbValue)];
                if (!EmptyValue($this->file_05->Upload->FileName)) {
                    $newFiles = [$this->file_05->Upload->FileName];
                    $NewFileCount = count($newFiles);
                    for ($i = 0; $i < $NewFileCount; $i++) {
                        if ($newFiles[$i] != "") {
                            $file = $newFiles[$i];
                            $tempPath = UploadTempPath($this->file_05, $this->file_05->Upload->Index);
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
                                $file1 = UniqueFilename($this->file_05->physicalUploadPath(), $file); // Get new file name
                                if ($file1 != $file) { // Rename temp file
                                    while (file_exists($tempPath . $file1) || file_exists($this->file_05->physicalUploadPath() . $file1)) { // Make sure no file name clash
                                        $file1 = UniqueFilename([$this->file_05->physicalUploadPath(), $tempPath], $file1, true); // Use indexed name
                                    }
                                    rename($tempPath . $file, $tempPath . $file1);
                                    $newFiles[$i] = $file1;
                                }
                            }
                        }
                    }
                    $this->file_05->Upload->DbValue = empty($oldFiles) ? "" : implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $oldFiles);
                    $this->file_05->Upload->FileName = implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $newFiles);
                    $this->file_05->setDbValueDef($rsnew, $this->file_05->Upload->FileName, "", $this->file_05->ReadOnly);
                }
            }
            if ($this->file_06->Visible && !$this->file_06->Upload->KeepFile) {
                $oldFiles = EmptyValue($this->file_06->Upload->DbValue) ? [] : [$this->file_06->htmlDecode($this->file_06->Upload->DbValue)];
                if (!EmptyValue($this->file_06->Upload->FileName)) {
                    $newFiles = [$this->file_06->Upload->FileName];
                    $NewFileCount = count($newFiles);
                    for ($i = 0; $i < $NewFileCount; $i++) {
                        if ($newFiles[$i] != "") {
                            $file = $newFiles[$i];
                            $tempPath = UploadTempPath($this->file_06, $this->file_06->Upload->Index);
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
                                $file1 = UniqueFilename($this->file_06->physicalUploadPath(), $file); // Get new file name
                                if ($file1 != $file) { // Rename temp file
                                    while (file_exists($tempPath . $file1) || file_exists($this->file_06->physicalUploadPath() . $file1)) { // Make sure no file name clash
                                        $file1 = UniqueFilename([$this->file_06->physicalUploadPath(), $tempPath], $file1, true); // Use indexed name
                                    }
                                    rename($tempPath . $file, $tempPath . $file1);
                                    $newFiles[$i] = $file1;
                                }
                            }
                        }
                    }
                    $this->file_06->Upload->DbValue = empty($oldFiles) ? "" : implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $oldFiles);
                    $this->file_06->Upload->FileName = implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $newFiles);
                    $this->file_06->setDbValueDef($rsnew, $this->file_06->Upload->FileName, "", $this->file_06->ReadOnly);
                }
            }
            if ($this->file_07->Visible && !$this->file_07->Upload->KeepFile) {
                $oldFiles = EmptyValue($this->file_07->Upload->DbValue) ? [] : [$this->file_07->htmlDecode($this->file_07->Upload->DbValue)];
                if (!EmptyValue($this->file_07->Upload->FileName)) {
                    $newFiles = [$this->file_07->Upload->FileName];
                    $NewFileCount = count($newFiles);
                    for ($i = 0; $i < $NewFileCount; $i++) {
                        if ($newFiles[$i] != "") {
                            $file = $newFiles[$i];
                            $tempPath = UploadTempPath($this->file_07, $this->file_07->Upload->Index);
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
                                $file1 = UniqueFilename($this->file_07->physicalUploadPath(), $file); // Get new file name
                                if ($file1 != $file) { // Rename temp file
                                    while (file_exists($tempPath . $file1) || file_exists($this->file_07->physicalUploadPath() . $file1)) { // Make sure no file name clash
                                        $file1 = UniqueFilename([$this->file_07->physicalUploadPath(), $tempPath], $file1, true); // Use indexed name
                                    }
                                    rename($tempPath . $file, $tempPath . $file1);
                                    $newFiles[$i] = $file1;
                                }
                            }
                        }
                    }
                    $this->file_07->Upload->DbValue = empty($oldFiles) ? "" : implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $oldFiles);
                    $this->file_07->Upload->FileName = implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $newFiles);
                    $this->file_07->setDbValueDef($rsnew, $this->file_07->Upload->FileName, "", $this->file_07->ReadOnly);
                }
            }
            if ($this->file_08->Visible && !$this->file_08->Upload->KeepFile) {
                $oldFiles = EmptyValue($this->file_08->Upload->DbValue) ? [] : [$this->file_08->htmlDecode($this->file_08->Upload->DbValue)];
                if (!EmptyValue($this->file_08->Upload->FileName)) {
                    $newFiles = [$this->file_08->Upload->FileName];
                    $NewFileCount = count($newFiles);
                    for ($i = 0; $i < $NewFileCount; $i++) {
                        if ($newFiles[$i] != "") {
                            $file = $newFiles[$i];
                            $tempPath = UploadTempPath($this->file_08, $this->file_08->Upload->Index);
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
                                $file1 = UniqueFilename($this->file_08->physicalUploadPath(), $file); // Get new file name
                                if ($file1 != $file) { // Rename temp file
                                    while (file_exists($tempPath . $file1) || file_exists($this->file_08->physicalUploadPath() . $file1)) { // Make sure no file name clash
                                        $file1 = UniqueFilename([$this->file_08->physicalUploadPath(), $tempPath], $file1, true); // Use indexed name
                                    }
                                    rename($tempPath . $file, $tempPath . $file1);
                                    $newFiles[$i] = $file1;
                                }
                            }
                        }
                    }
                    $this->file_08->Upload->DbValue = empty($oldFiles) ? "" : implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $oldFiles);
                    $this->file_08->Upload->FileName = implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $newFiles);
                    $this->file_08->setDbValueDef($rsnew, $this->file_08->Upload->FileName, "", $this->file_08->ReadOnly);
                }
            }
            if ($this->file_09->Visible && !$this->file_09->Upload->KeepFile) {
                $oldFiles = EmptyValue($this->file_09->Upload->DbValue) ? [] : [$this->file_09->htmlDecode($this->file_09->Upload->DbValue)];
                if (!EmptyValue($this->file_09->Upload->FileName)) {
                    $newFiles = [$this->file_09->Upload->FileName];
                    $NewFileCount = count($newFiles);
                    for ($i = 0; $i < $NewFileCount; $i++) {
                        if ($newFiles[$i] != "") {
                            $file = $newFiles[$i];
                            $tempPath = UploadTempPath($this->file_09, $this->file_09->Upload->Index);
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
                                $file1 = UniqueFilename($this->file_09->physicalUploadPath(), $file); // Get new file name
                                if ($file1 != $file) { // Rename temp file
                                    while (file_exists($tempPath . $file1) || file_exists($this->file_09->physicalUploadPath() . $file1)) { // Make sure no file name clash
                                        $file1 = UniqueFilename([$this->file_09->physicalUploadPath(), $tempPath], $file1, true); // Use indexed name
                                    }
                                    rename($tempPath . $file, $tempPath . $file1);
                                    $newFiles[$i] = $file1;
                                }
                            }
                        }
                    }
                    $this->file_09->Upload->DbValue = empty($oldFiles) ? "" : implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $oldFiles);
                    $this->file_09->Upload->FileName = implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $newFiles);
                    $this->file_09->setDbValueDef($rsnew, $this->file_09->Upload->FileName, "", $this->file_09->ReadOnly);
                }
            }
            if ($this->file_10->Visible && !$this->file_10->Upload->KeepFile) {
                $oldFiles = EmptyValue($this->file_10->Upload->DbValue) ? [] : [$this->file_10->htmlDecode($this->file_10->Upload->DbValue)];
                if (!EmptyValue($this->file_10->Upload->FileName)) {
                    $newFiles = [$this->file_10->Upload->FileName];
                    $NewFileCount = count($newFiles);
                    for ($i = 0; $i < $NewFileCount; $i++) {
                        if ($newFiles[$i] != "") {
                            $file = $newFiles[$i];
                            $tempPath = UploadTempPath($this->file_10, $this->file_10->Upload->Index);
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
                                $file1 = UniqueFilename($this->file_10->physicalUploadPath(), $file); // Get new file name
                                if ($file1 != $file) { // Rename temp file
                                    while (file_exists($tempPath . $file1) || file_exists($this->file_10->physicalUploadPath() . $file1)) { // Make sure no file name clash
                                        $file1 = UniqueFilename([$this->file_10->physicalUploadPath(), $tempPath], $file1, true); // Use indexed name
                                    }
                                    rename($tempPath . $file, $tempPath . $file1);
                                    $newFiles[$i] = $file1;
                                }
                            }
                        }
                    }
                    $this->file_10->Upload->DbValue = empty($oldFiles) ? "" : implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $oldFiles);
                    $this->file_10->Upload->FileName = implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $newFiles);
                    $this->file_10->setDbValueDef($rsnew, $this->file_10->Upload->FileName, "", $this->file_10->ReadOnly);
                }
            }
            if ($this->file_11->Visible && !$this->file_11->Upload->KeepFile) {
                $oldFiles = EmptyValue($this->file_11->Upload->DbValue) ? [] : [$this->file_11->htmlDecode($this->file_11->Upload->DbValue)];
                if (!EmptyValue($this->file_11->Upload->FileName)) {
                    $newFiles = [$this->file_11->Upload->FileName];
                    $NewFileCount = count($newFiles);
                    for ($i = 0; $i < $NewFileCount; $i++) {
                        if ($newFiles[$i] != "") {
                            $file = $newFiles[$i];
                            $tempPath = UploadTempPath($this->file_11, $this->file_11->Upload->Index);
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
                                $file1 = UniqueFilename($this->file_11->physicalUploadPath(), $file); // Get new file name
                                if ($file1 != $file) { // Rename temp file
                                    while (file_exists($tempPath . $file1) || file_exists($this->file_11->physicalUploadPath() . $file1)) { // Make sure no file name clash
                                        $file1 = UniqueFilename([$this->file_11->physicalUploadPath(), $tempPath], $file1, true); // Use indexed name
                                    }
                                    rename($tempPath . $file, $tempPath . $file1);
                                    $newFiles[$i] = $file1;
                                }
                            }
                        }
                    }
                    $this->file_11->Upload->DbValue = empty($oldFiles) ? "" : implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $oldFiles);
                    $this->file_11->Upload->FileName = implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $newFiles);
                    $this->file_11->setDbValueDef($rsnew, $this->file_11->Upload->FileName, "", $this->file_11->ReadOnly);
                }
            }
            if ($this->file_12->Visible && !$this->file_12->Upload->KeepFile) {
                $oldFiles = EmptyValue($this->file_12->Upload->DbValue) ? [] : [$this->file_12->htmlDecode($this->file_12->Upload->DbValue)];
                if (!EmptyValue($this->file_12->Upload->FileName)) {
                    $newFiles = [$this->file_12->Upload->FileName];
                    $NewFileCount = count($newFiles);
                    for ($i = 0; $i < $NewFileCount; $i++) {
                        if ($newFiles[$i] != "") {
                            $file = $newFiles[$i];
                            $tempPath = UploadTempPath($this->file_12, $this->file_12->Upload->Index);
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
                                $file1 = UniqueFilename($this->file_12->physicalUploadPath(), $file); // Get new file name
                                if ($file1 != $file) { // Rename temp file
                                    while (file_exists($tempPath . $file1) || file_exists($this->file_12->physicalUploadPath() . $file1)) { // Make sure no file name clash
                                        $file1 = UniqueFilename([$this->file_12->physicalUploadPath(), $tempPath], $file1, true); // Use indexed name
                                    }
                                    rename($tempPath . $file, $tempPath . $file1);
                                    $newFiles[$i] = $file1;
                                }
                            }
                        }
                    }
                    $this->file_12->Upload->DbValue = empty($oldFiles) ? "" : implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $oldFiles);
                    $this->file_12->Upload->FileName = implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $newFiles);
                    $this->file_12->setDbValueDef($rsnew, $this->file_12->Upload->FileName, "", $this->file_12->ReadOnly);
                }
            }
            if ($this->file_13->Visible && !$this->file_13->Upload->KeepFile) {
                $oldFiles = EmptyValue($this->file_13->Upload->DbValue) ? [] : [$this->file_13->htmlDecode($this->file_13->Upload->DbValue)];
                if (!EmptyValue($this->file_13->Upload->FileName)) {
                    $newFiles = [$this->file_13->Upload->FileName];
                    $NewFileCount = count($newFiles);
                    for ($i = 0; $i < $NewFileCount; $i++) {
                        if ($newFiles[$i] != "") {
                            $file = $newFiles[$i];
                            $tempPath = UploadTempPath($this->file_13, $this->file_13->Upload->Index);
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
                                $file1 = UniqueFilename($this->file_13->physicalUploadPath(), $file); // Get new file name
                                if ($file1 != $file) { // Rename temp file
                                    while (file_exists($tempPath . $file1) || file_exists($this->file_13->physicalUploadPath() . $file1)) { // Make sure no file name clash
                                        $file1 = UniqueFilename([$this->file_13->physicalUploadPath(), $tempPath], $file1, true); // Use indexed name
                                    }
                                    rename($tempPath . $file, $tempPath . $file1);
                                    $newFiles[$i] = $file1;
                                }
                            }
                        }
                    }
                    $this->file_13->Upload->DbValue = empty($oldFiles) ? "" : implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $oldFiles);
                    $this->file_13->Upload->FileName = implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $newFiles);
                    $this->file_13->setDbValueDef($rsnew, $this->file_13->Upload->FileName, "", $this->file_13->ReadOnly);
                }
            }
            if ($this->file_14->Visible && !$this->file_14->Upload->KeepFile) {
                $oldFiles = EmptyValue($this->file_14->Upload->DbValue) ? [] : [$this->file_14->htmlDecode($this->file_14->Upload->DbValue)];
                if (!EmptyValue($this->file_14->Upload->FileName)) {
                    $newFiles = [$this->file_14->Upload->FileName];
                    $NewFileCount = count($newFiles);
                    for ($i = 0; $i < $NewFileCount; $i++) {
                        if ($newFiles[$i] != "") {
                            $file = $newFiles[$i];
                            $tempPath = UploadTempPath($this->file_14, $this->file_14->Upload->Index);
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
                                $file1 = UniqueFilename($this->file_14->physicalUploadPath(), $file); // Get new file name
                                if ($file1 != $file) { // Rename temp file
                                    while (file_exists($tempPath . $file1) || file_exists($this->file_14->physicalUploadPath() . $file1)) { // Make sure no file name clash
                                        $file1 = UniqueFilename([$this->file_14->physicalUploadPath(), $tempPath], $file1, true); // Use indexed name
                                    }
                                    rename($tempPath . $file, $tempPath . $file1);
                                    $newFiles[$i] = $file1;
                                }
                            }
                        }
                    }
                    $this->file_14->Upload->DbValue = empty($oldFiles) ? "" : implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $oldFiles);
                    $this->file_14->Upload->FileName = implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $newFiles);
                    $this->file_14->setDbValueDef($rsnew, $this->file_14->Upload->FileName, "", $this->file_14->ReadOnly);
                }
            }
            if ($this->file_15->Visible && !$this->file_15->Upload->KeepFile) {
                $oldFiles = EmptyValue($this->file_15->Upload->DbValue) ? [] : [$this->file_15->htmlDecode($this->file_15->Upload->DbValue)];
                if (!EmptyValue($this->file_15->Upload->FileName)) {
                    $newFiles = [$this->file_15->Upload->FileName];
                    $NewFileCount = count($newFiles);
                    for ($i = 0; $i < $NewFileCount; $i++) {
                        if ($newFiles[$i] != "") {
                            $file = $newFiles[$i];
                            $tempPath = UploadTempPath($this->file_15, $this->file_15->Upload->Index);
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
                                $file1 = UniqueFilename($this->file_15->physicalUploadPath(), $file); // Get new file name
                                if ($file1 != $file) { // Rename temp file
                                    while (file_exists($tempPath . $file1) || file_exists($this->file_15->physicalUploadPath() . $file1)) { // Make sure no file name clash
                                        $file1 = UniqueFilename([$this->file_15->physicalUploadPath(), $tempPath], $file1, true); // Use indexed name
                                    }
                                    rename($tempPath . $file, $tempPath . $file1);
                                    $newFiles[$i] = $file1;
                                }
                            }
                        }
                    }
                    $this->file_15->Upload->DbValue = empty($oldFiles) ? "" : implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $oldFiles);
                    $this->file_15->Upload->FileName = implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $newFiles);
                    $this->file_15->setDbValueDef($rsnew, $this->file_15->Upload->FileName, "", $this->file_15->ReadOnly);
                }
            }
            if ($this->file_16->Visible && !$this->file_16->Upload->KeepFile) {
                $oldFiles = EmptyValue($this->file_16->Upload->DbValue) ? [] : [$this->file_16->htmlDecode($this->file_16->Upload->DbValue)];
                if (!EmptyValue($this->file_16->Upload->FileName)) {
                    $newFiles = [$this->file_16->Upload->FileName];
                    $NewFileCount = count($newFiles);
                    for ($i = 0; $i < $NewFileCount; $i++) {
                        if ($newFiles[$i] != "") {
                            $file = $newFiles[$i];
                            $tempPath = UploadTempPath($this->file_16, $this->file_16->Upload->Index);
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
                                $file1 = UniqueFilename($this->file_16->physicalUploadPath(), $file); // Get new file name
                                if ($file1 != $file) { // Rename temp file
                                    while (file_exists($tempPath . $file1) || file_exists($this->file_16->physicalUploadPath() . $file1)) { // Make sure no file name clash
                                        $file1 = UniqueFilename([$this->file_16->physicalUploadPath(), $tempPath], $file1, true); // Use indexed name
                                    }
                                    rename($tempPath . $file, $tempPath . $file1);
                                    $newFiles[$i] = $file1;
                                }
                            }
                        }
                    }
                    $this->file_16->Upload->DbValue = empty($oldFiles) ? "" : implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $oldFiles);
                    $this->file_16->Upload->FileName = implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $newFiles);
                    $this->file_16->setDbValueDef($rsnew, $this->file_16->Upload->FileName, "", $this->file_16->ReadOnly);
                }
            }
            if ($this->file_17->Visible && !$this->file_17->Upload->KeepFile) {
                $oldFiles = EmptyValue($this->file_17->Upload->DbValue) ? [] : [$this->file_17->htmlDecode($this->file_17->Upload->DbValue)];
                if (!EmptyValue($this->file_17->Upload->FileName)) {
                    $newFiles = [$this->file_17->Upload->FileName];
                    $NewFileCount = count($newFiles);
                    for ($i = 0; $i < $NewFileCount; $i++) {
                        if ($newFiles[$i] != "") {
                            $file = $newFiles[$i];
                            $tempPath = UploadTempPath($this->file_17, $this->file_17->Upload->Index);
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
                                $file1 = UniqueFilename($this->file_17->physicalUploadPath(), $file); // Get new file name
                                if ($file1 != $file) { // Rename temp file
                                    while (file_exists($tempPath . $file1) || file_exists($this->file_17->physicalUploadPath() . $file1)) { // Make sure no file name clash
                                        $file1 = UniqueFilename([$this->file_17->physicalUploadPath(), $tempPath], $file1, true); // Use indexed name
                                    }
                                    rename($tempPath . $file, $tempPath . $file1);
                                    $newFiles[$i] = $file1;
                                }
                            }
                        }
                    }
                    $this->file_17->Upload->DbValue = empty($oldFiles) ? "" : implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $oldFiles);
                    $this->file_17->Upload->FileName = implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $newFiles);
                    $this->file_17->setDbValueDef($rsnew, $this->file_17->Upload->FileName, "", $this->file_17->ReadOnly);
                }
            }
            if ($this->file_18->Visible && !$this->file_18->Upload->KeepFile) {
                $oldFiles = EmptyValue($this->file_18->Upload->DbValue) ? [] : [$this->file_18->htmlDecode($this->file_18->Upload->DbValue)];
                if (!EmptyValue($this->file_18->Upload->FileName)) {
                    $newFiles = [$this->file_18->Upload->FileName];
                    $NewFileCount = count($newFiles);
                    for ($i = 0; $i < $NewFileCount; $i++) {
                        if ($newFiles[$i] != "") {
                            $file = $newFiles[$i];
                            $tempPath = UploadTempPath($this->file_18, $this->file_18->Upload->Index);
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
                                $file1 = UniqueFilename($this->file_18->physicalUploadPath(), $file); // Get new file name
                                if ($file1 != $file) { // Rename temp file
                                    while (file_exists($tempPath . $file1) || file_exists($this->file_18->physicalUploadPath() . $file1)) { // Make sure no file name clash
                                        $file1 = UniqueFilename([$this->file_18->physicalUploadPath(), $tempPath], $file1, true); // Use indexed name
                                    }
                                    rename($tempPath . $file, $tempPath . $file1);
                                    $newFiles[$i] = $file1;
                                }
                            }
                        }
                    }
                    $this->file_18->Upload->DbValue = empty($oldFiles) ? "" : implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $oldFiles);
                    $this->file_18->Upload->FileName = implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $newFiles);
                    $this->file_18->setDbValueDef($rsnew, $this->file_18->Upload->FileName, "", $this->file_18->ReadOnly);
                }
            }
            if ($this->file_19->Visible && !$this->file_19->Upload->KeepFile) {
                $oldFiles = EmptyValue($this->file_19->Upload->DbValue) ? [] : [$this->file_19->htmlDecode($this->file_19->Upload->DbValue)];
                if (!EmptyValue($this->file_19->Upload->FileName)) {
                    $newFiles = [$this->file_19->Upload->FileName];
                    $NewFileCount = count($newFiles);
                    for ($i = 0; $i < $NewFileCount; $i++) {
                        if ($newFiles[$i] != "") {
                            $file = $newFiles[$i];
                            $tempPath = UploadTempPath($this->file_19, $this->file_19->Upload->Index);
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
                                $file1 = UniqueFilename($this->file_19->physicalUploadPath(), $file); // Get new file name
                                if ($file1 != $file) { // Rename temp file
                                    while (file_exists($tempPath . $file1) || file_exists($this->file_19->physicalUploadPath() . $file1)) { // Make sure no file name clash
                                        $file1 = UniqueFilename([$this->file_19->physicalUploadPath(), $tempPath], $file1, true); // Use indexed name
                                    }
                                    rename($tempPath . $file, $tempPath . $file1);
                                    $newFiles[$i] = $file1;
                                }
                            }
                        }
                    }
                    $this->file_19->Upload->DbValue = empty($oldFiles) ? "" : implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $oldFiles);
                    $this->file_19->Upload->FileName = implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $newFiles);
                    $this->file_19->setDbValueDef($rsnew, $this->file_19->Upload->FileName, "", $this->file_19->ReadOnly);
                }
            }
            if ($this->file_20->Visible && !$this->file_20->Upload->KeepFile) {
                $oldFiles = EmptyValue($this->file_20->Upload->DbValue) ? [] : [$this->file_20->htmlDecode($this->file_20->Upload->DbValue)];
                if (!EmptyValue($this->file_20->Upload->FileName)) {
                    $newFiles = [$this->file_20->Upload->FileName];
                    $NewFileCount = count($newFiles);
                    for ($i = 0; $i < $NewFileCount; $i++) {
                        if ($newFiles[$i] != "") {
                            $file = $newFiles[$i];
                            $tempPath = UploadTempPath($this->file_20, $this->file_20->Upload->Index);
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
                                $file1 = UniqueFilename($this->file_20->physicalUploadPath(), $file); // Get new file name
                                if ($file1 != $file) { // Rename temp file
                                    while (file_exists($tempPath . $file1) || file_exists($this->file_20->physicalUploadPath() . $file1)) { // Make sure no file name clash
                                        $file1 = UniqueFilename([$this->file_20->physicalUploadPath(), $tempPath], $file1, true); // Use indexed name
                                    }
                                    rename($tempPath . $file, $tempPath . $file1);
                                    $newFiles[$i] = $file1;
                                }
                            }
                        }
                    }
                    $this->file_20->Upload->DbValue = empty($oldFiles) ? "" : implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $oldFiles);
                    $this->file_20->Upload->FileName = implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $newFiles);
                    $this->file_20->setDbValueDef($rsnew, $this->file_20->Upload->FileName, "", $this->file_20->ReadOnly);
                }
            }
            if ($this->file_21->Visible && !$this->file_21->Upload->KeepFile) {
                $oldFiles = EmptyValue($this->file_21->Upload->DbValue) ? [] : [$this->file_21->htmlDecode($this->file_21->Upload->DbValue)];
                if (!EmptyValue($this->file_21->Upload->FileName)) {
                    $newFiles = [$this->file_21->Upload->FileName];
                    $NewFileCount = count($newFiles);
                    for ($i = 0; $i < $NewFileCount; $i++) {
                        if ($newFiles[$i] != "") {
                            $file = $newFiles[$i];
                            $tempPath = UploadTempPath($this->file_21, $this->file_21->Upload->Index);
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
                                $file1 = UniqueFilename($this->file_21->physicalUploadPath(), $file); // Get new file name
                                if ($file1 != $file) { // Rename temp file
                                    while (file_exists($tempPath . $file1) || file_exists($this->file_21->physicalUploadPath() . $file1)) { // Make sure no file name clash
                                        $file1 = UniqueFilename([$this->file_21->physicalUploadPath(), $tempPath], $file1, true); // Use indexed name
                                    }
                                    rename($tempPath . $file, $tempPath . $file1);
                                    $newFiles[$i] = $file1;
                                }
                            }
                        }
                    }
                    $this->file_21->Upload->DbValue = empty($oldFiles) ? "" : implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $oldFiles);
                    $this->file_21->Upload->FileName = implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $newFiles);
                    $this->file_21->setDbValueDef($rsnew, $this->file_21->Upload->FileName, "", $this->file_21->ReadOnly);
                }
            }
            if ($this->file_22->Visible && !$this->file_22->Upload->KeepFile) {
                $oldFiles = EmptyValue($this->file_22->Upload->DbValue) ? [] : [$this->file_22->htmlDecode($this->file_22->Upload->DbValue)];
                if (!EmptyValue($this->file_22->Upload->FileName)) {
                    $newFiles = [$this->file_22->Upload->FileName];
                    $NewFileCount = count($newFiles);
                    for ($i = 0; $i < $NewFileCount; $i++) {
                        if ($newFiles[$i] != "") {
                            $file = $newFiles[$i];
                            $tempPath = UploadTempPath($this->file_22, $this->file_22->Upload->Index);
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
                                $file1 = UniqueFilename($this->file_22->physicalUploadPath(), $file); // Get new file name
                                if ($file1 != $file) { // Rename temp file
                                    while (file_exists($tempPath . $file1) || file_exists($this->file_22->physicalUploadPath() . $file1)) { // Make sure no file name clash
                                        $file1 = UniqueFilename([$this->file_22->physicalUploadPath(), $tempPath], $file1, true); // Use indexed name
                                    }
                                    rename($tempPath . $file, $tempPath . $file1);
                                    $newFiles[$i] = $file1;
                                }
                            }
                        }
                    }
                    $this->file_22->Upload->DbValue = empty($oldFiles) ? "" : implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $oldFiles);
                    $this->file_22->Upload->FileName = implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $newFiles);
                    $this->file_22->setDbValueDef($rsnew, $this->file_22->Upload->FileName, "", $this->file_22->ReadOnly);
                }
            }
            if ($this->file_23->Visible && !$this->file_23->Upload->KeepFile) {
                $oldFiles = EmptyValue($this->file_23->Upload->DbValue) ? [] : [$this->file_23->htmlDecode($this->file_23->Upload->DbValue)];
                if (!EmptyValue($this->file_23->Upload->FileName)) {
                    $newFiles = [$this->file_23->Upload->FileName];
                    $NewFileCount = count($newFiles);
                    for ($i = 0; $i < $NewFileCount; $i++) {
                        if ($newFiles[$i] != "") {
                            $file = $newFiles[$i];
                            $tempPath = UploadTempPath($this->file_23, $this->file_23->Upload->Index);
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
                                $file1 = UniqueFilename($this->file_23->physicalUploadPath(), $file); // Get new file name
                                if ($file1 != $file) { // Rename temp file
                                    while (file_exists($tempPath . $file1) || file_exists($this->file_23->physicalUploadPath() . $file1)) { // Make sure no file name clash
                                        $file1 = UniqueFilename([$this->file_23->physicalUploadPath(), $tempPath], $file1, true); // Use indexed name
                                    }
                                    rename($tempPath . $file, $tempPath . $file1);
                                    $newFiles[$i] = $file1;
                                }
                            }
                        }
                    }
                    $this->file_23->Upload->DbValue = empty($oldFiles) ? "" : implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $oldFiles);
                    $this->file_23->Upload->FileName = implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $newFiles);
                    $this->file_23->setDbValueDef($rsnew, $this->file_23->Upload->FileName, "", $this->file_23->ReadOnly);
                }
            }
            if ($this->file_24->Visible && !$this->file_24->Upload->KeepFile) {
                $oldFiles = EmptyValue($this->file_24->Upload->DbValue) ? [] : [$this->file_24->htmlDecode($this->file_24->Upload->DbValue)];
                if (!EmptyValue($this->file_24->Upload->FileName)) {
                    $newFiles = [$this->file_24->Upload->FileName];
                    $NewFileCount = count($newFiles);
                    for ($i = 0; $i < $NewFileCount; $i++) {
                        if ($newFiles[$i] != "") {
                            $file = $newFiles[$i];
                            $tempPath = UploadTempPath($this->file_24, $this->file_24->Upload->Index);
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
                                $file1 = UniqueFilename($this->file_24->physicalUploadPath(), $file); // Get new file name
                                if ($file1 != $file) { // Rename temp file
                                    while (file_exists($tempPath . $file1) || file_exists($this->file_24->physicalUploadPath() . $file1)) { // Make sure no file name clash
                                        $file1 = UniqueFilename([$this->file_24->physicalUploadPath(), $tempPath], $file1, true); // Use indexed name
                                    }
                                    rename($tempPath . $file, $tempPath . $file1);
                                    $newFiles[$i] = $file1;
                                }
                            }
                        }
                    }
                    $this->file_24->Upload->DbValue = empty($oldFiles) ? "" : implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $oldFiles);
                    $this->file_24->Upload->FileName = implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $newFiles);
                    $this->file_24->setDbValueDef($rsnew, $this->file_24->Upload->FileName, "", $this->file_24->ReadOnly);
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
                    if ($this->file_01->Visible && !$this->file_01->Upload->KeepFile) {
                        $oldFiles = EmptyValue($this->file_01->Upload->DbValue) ? [] : [$this->file_01->htmlDecode($this->file_01->Upload->DbValue)];
                        if (!EmptyValue($this->file_01->Upload->FileName)) {
                            $newFiles = [$this->file_01->Upload->FileName];
                            $newFiles2 = [$this->file_01->htmlDecode($rsnew['file_01'])];
                            $newFileCount = count($newFiles);
                            for ($i = 0; $i < $newFileCount; $i++) {
                                if ($newFiles[$i] != "") {
                                    $file = UploadTempPath($this->file_01, $this->file_01->Upload->Index) . $newFiles[$i];
                                    if (file_exists($file)) {
                                        if (@$newFiles2[$i] != "") { // Use correct file name
                                            $newFiles[$i] = $newFiles2[$i];
                                        }
                                        if (!$this->file_01->Upload->SaveToFile($newFiles[$i], true, $i)) { // Just replace
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
                                    @unlink($this->file_01->oldPhysicalUploadPath() . $oldFile);
                                }
                            }
                        }
                    }
                    if ($this->file_02->Visible && !$this->file_02->Upload->KeepFile) {
                        $oldFiles = EmptyValue($this->file_02->Upload->DbValue) ? [] : [$this->file_02->htmlDecode($this->file_02->Upload->DbValue)];
                        if (!EmptyValue($this->file_02->Upload->FileName)) {
                            $newFiles = [$this->file_02->Upload->FileName];
                            $newFiles2 = [$this->file_02->htmlDecode($rsnew['file_02'])];
                            $newFileCount = count($newFiles);
                            for ($i = 0; $i < $newFileCount; $i++) {
                                if ($newFiles[$i] != "") {
                                    $file = UploadTempPath($this->file_02, $this->file_02->Upload->Index) . $newFiles[$i];
                                    if (file_exists($file)) {
                                        if (@$newFiles2[$i] != "") { // Use correct file name
                                            $newFiles[$i] = $newFiles2[$i];
                                        }
                                        if (!$this->file_02->Upload->SaveToFile($newFiles[$i], true, $i)) { // Just replace
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
                                    @unlink($this->file_02->oldPhysicalUploadPath() . $oldFile);
                                }
                            }
                        }
                    }
                    if ($this->file_03->Visible && !$this->file_03->Upload->KeepFile) {
                        $oldFiles = EmptyValue($this->file_03->Upload->DbValue) ? [] : [$this->file_03->htmlDecode($this->file_03->Upload->DbValue)];
                        if (!EmptyValue($this->file_03->Upload->FileName)) {
                            $newFiles = [$this->file_03->Upload->FileName];
                            $newFiles2 = [$this->file_03->htmlDecode($rsnew['file_03'])];
                            $newFileCount = count($newFiles);
                            for ($i = 0; $i < $newFileCount; $i++) {
                                if ($newFiles[$i] != "") {
                                    $file = UploadTempPath($this->file_03, $this->file_03->Upload->Index) . $newFiles[$i];
                                    if (file_exists($file)) {
                                        if (@$newFiles2[$i] != "") { // Use correct file name
                                            $newFiles[$i] = $newFiles2[$i];
                                        }
                                        if (!$this->file_03->Upload->SaveToFile($newFiles[$i], true, $i)) { // Just replace
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
                                    @unlink($this->file_03->oldPhysicalUploadPath() . $oldFile);
                                }
                            }
                        }
                    }
                    if ($this->file_04->Visible && !$this->file_04->Upload->KeepFile) {
                        $oldFiles = EmptyValue($this->file_04->Upload->DbValue) ? [] : [$this->file_04->htmlDecode($this->file_04->Upload->DbValue)];
                        if (!EmptyValue($this->file_04->Upload->FileName)) {
                            $newFiles = [$this->file_04->Upload->FileName];
                            $newFiles2 = [$this->file_04->htmlDecode($rsnew['file_04'])];
                            $newFileCount = count($newFiles);
                            for ($i = 0; $i < $newFileCount; $i++) {
                                if ($newFiles[$i] != "") {
                                    $file = UploadTempPath($this->file_04, $this->file_04->Upload->Index) . $newFiles[$i];
                                    if (file_exists($file)) {
                                        if (@$newFiles2[$i] != "") { // Use correct file name
                                            $newFiles[$i] = $newFiles2[$i];
                                        }
                                        if (!$this->file_04->Upload->SaveToFile($newFiles[$i], true, $i)) { // Just replace
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
                                    @unlink($this->file_04->oldPhysicalUploadPath() . $oldFile);
                                }
                            }
                        }
                    }
                    if ($this->file_05->Visible && !$this->file_05->Upload->KeepFile) {
                        $oldFiles = EmptyValue($this->file_05->Upload->DbValue) ? [] : [$this->file_05->htmlDecode($this->file_05->Upload->DbValue)];
                        if (!EmptyValue($this->file_05->Upload->FileName)) {
                            $newFiles = [$this->file_05->Upload->FileName];
                            $newFiles2 = [$this->file_05->htmlDecode($rsnew['file_05'])];
                            $newFileCount = count($newFiles);
                            for ($i = 0; $i < $newFileCount; $i++) {
                                if ($newFiles[$i] != "") {
                                    $file = UploadTempPath($this->file_05, $this->file_05->Upload->Index) . $newFiles[$i];
                                    if (file_exists($file)) {
                                        if (@$newFiles2[$i] != "") { // Use correct file name
                                            $newFiles[$i] = $newFiles2[$i];
                                        }
                                        if (!$this->file_05->Upload->SaveToFile($newFiles[$i], true, $i)) { // Just replace
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
                                    @unlink($this->file_05->oldPhysicalUploadPath() . $oldFile);
                                }
                            }
                        }
                    }
                    if ($this->file_06->Visible && !$this->file_06->Upload->KeepFile) {
                        $oldFiles = EmptyValue($this->file_06->Upload->DbValue) ? [] : [$this->file_06->htmlDecode($this->file_06->Upload->DbValue)];
                        if (!EmptyValue($this->file_06->Upload->FileName)) {
                            $newFiles = [$this->file_06->Upload->FileName];
                            $newFiles2 = [$this->file_06->htmlDecode($rsnew['file_06'])];
                            $newFileCount = count($newFiles);
                            for ($i = 0; $i < $newFileCount; $i++) {
                                if ($newFiles[$i] != "") {
                                    $file = UploadTempPath($this->file_06, $this->file_06->Upload->Index) . $newFiles[$i];
                                    if (file_exists($file)) {
                                        if (@$newFiles2[$i] != "") { // Use correct file name
                                            $newFiles[$i] = $newFiles2[$i];
                                        }
                                        if (!$this->file_06->Upload->SaveToFile($newFiles[$i], true, $i)) { // Just replace
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
                                    @unlink($this->file_06->oldPhysicalUploadPath() . $oldFile);
                                }
                            }
                        }
                    }
                    if ($this->file_07->Visible && !$this->file_07->Upload->KeepFile) {
                        $oldFiles = EmptyValue($this->file_07->Upload->DbValue) ? [] : [$this->file_07->htmlDecode($this->file_07->Upload->DbValue)];
                        if (!EmptyValue($this->file_07->Upload->FileName)) {
                            $newFiles = [$this->file_07->Upload->FileName];
                            $newFiles2 = [$this->file_07->htmlDecode($rsnew['file_07'])];
                            $newFileCount = count($newFiles);
                            for ($i = 0; $i < $newFileCount; $i++) {
                                if ($newFiles[$i] != "") {
                                    $file = UploadTempPath($this->file_07, $this->file_07->Upload->Index) . $newFiles[$i];
                                    if (file_exists($file)) {
                                        if (@$newFiles2[$i] != "") { // Use correct file name
                                            $newFiles[$i] = $newFiles2[$i];
                                        }
                                        if (!$this->file_07->Upload->SaveToFile($newFiles[$i], true, $i)) { // Just replace
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
                                    @unlink($this->file_07->oldPhysicalUploadPath() . $oldFile);
                                }
                            }
                        }
                    }
                    if ($this->file_08->Visible && !$this->file_08->Upload->KeepFile) {
                        $oldFiles = EmptyValue($this->file_08->Upload->DbValue) ? [] : [$this->file_08->htmlDecode($this->file_08->Upload->DbValue)];
                        if (!EmptyValue($this->file_08->Upload->FileName)) {
                            $newFiles = [$this->file_08->Upload->FileName];
                            $newFiles2 = [$this->file_08->htmlDecode($rsnew['file_08'])];
                            $newFileCount = count($newFiles);
                            for ($i = 0; $i < $newFileCount; $i++) {
                                if ($newFiles[$i] != "") {
                                    $file = UploadTempPath($this->file_08, $this->file_08->Upload->Index) . $newFiles[$i];
                                    if (file_exists($file)) {
                                        if (@$newFiles2[$i] != "") { // Use correct file name
                                            $newFiles[$i] = $newFiles2[$i];
                                        }
                                        if (!$this->file_08->Upload->SaveToFile($newFiles[$i], true, $i)) { // Just replace
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
                                    @unlink($this->file_08->oldPhysicalUploadPath() . $oldFile);
                                }
                            }
                        }
                    }
                    if ($this->file_09->Visible && !$this->file_09->Upload->KeepFile) {
                        $oldFiles = EmptyValue($this->file_09->Upload->DbValue) ? [] : [$this->file_09->htmlDecode($this->file_09->Upload->DbValue)];
                        if (!EmptyValue($this->file_09->Upload->FileName)) {
                            $newFiles = [$this->file_09->Upload->FileName];
                            $newFiles2 = [$this->file_09->htmlDecode($rsnew['file_09'])];
                            $newFileCount = count($newFiles);
                            for ($i = 0; $i < $newFileCount; $i++) {
                                if ($newFiles[$i] != "") {
                                    $file = UploadTempPath($this->file_09, $this->file_09->Upload->Index) . $newFiles[$i];
                                    if (file_exists($file)) {
                                        if (@$newFiles2[$i] != "") { // Use correct file name
                                            $newFiles[$i] = $newFiles2[$i];
                                        }
                                        if (!$this->file_09->Upload->SaveToFile($newFiles[$i], true, $i)) { // Just replace
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
                                    @unlink($this->file_09->oldPhysicalUploadPath() . $oldFile);
                                }
                            }
                        }
                    }
                    if ($this->file_10->Visible && !$this->file_10->Upload->KeepFile) {
                        $oldFiles = EmptyValue($this->file_10->Upload->DbValue) ? [] : [$this->file_10->htmlDecode($this->file_10->Upload->DbValue)];
                        if (!EmptyValue($this->file_10->Upload->FileName)) {
                            $newFiles = [$this->file_10->Upload->FileName];
                            $newFiles2 = [$this->file_10->htmlDecode($rsnew['file_10'])];
                            $newFileCount = count($newFiles);
                            for ($i = 0; $i < $newFileCount; $i++) {
                                if ($newFiles[$i] != "") {
                                    $file = UploadTempPath($this->file_10, $this->file_10->Upload->Index) . $newFiles[$i];
                                    if (file_exists($file)) {
                                        if (@$newFiles2[$i] != "") { // Use correct file name
                                            $newFiles[$i] = $newFiles2[$i];
                                        }
                                        if (!$this->file_10->Upload->SaveToFile($newFiles[$i], true, $i)) { // Just replace
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
                                    @unlink($this->file_10->oldPhysicalUploadPath() . $oldFile);
                                }
                            }
                        }
                    }
                    if ($this->file_11->Visible && !$this->file_11->Upload->KeepFile) {
                        $oldFiles = EmptyValue($this->file_11->Upload->DbValue) ? [] : [$this->file_11->htmlDecode($this->file_11->Upload->DbValue)];
                        if (!EmptyValue($this->file_11->Upload->FileName)) {
                            $newFiles = [$this->file_11->Upload->FileName];
                            $newFiles2 = [$this->file_11->htmlDecode($rsnew['file_11'])];
                            $newFileCount = count($newFiles);
                            for ($i = 0; $i < $newFileCount; $i++) {
                                if ($newFiles[$i] != "") {
                                    $file = UploadTempPath($this->file_11, $this->file_11->Upload->Index) . $newFiles[$i];
                                    if (file_exists($file)) {
                                        if (@$newFiles2[$i] != "") { // Use correct file name
                                            $newFiles[$i] = $newFiles2[$i];
                                        }
                                        if (!$this->file_11->Upload->SaveToFile($newFiles[$i], true, $i)) { // Just replace
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
                                    @unlink($this->file_11->oldPhysicalUploadPath() . $oldFile);
                                }
                            }
                        }
                    }
                    if ($this->file_12->Visible && !$this->file_12->Upload->KeepFile) {
                        $oldFiles = EmptyValue($this->file_12->Upload->DbValue) ? [] : [$this->file_12->htmlDecode($this->file_12->Upload->DbValue)];
                        if (!EmptyValue($this->file_12->Upload->FileName)) {
                            $newFiles = [$this->file_12->Upload->FileName];
                            $newFiles2 = [$this->file_12->htmlDecode($rsnew['file_12'])];
                            $newFileCount = count($newFiles);
                            for ($i = 0; $i < $newFileCount; $i++) {
                                if ($newFiles[$i] != "") {
                                    $file = UploadTempPath($this->file_12, $this->file_12->Upload->Index) . $newFiles[$i];
                                    if (file_exists($file)) {
                                        if (@$newFiles2[$i] != "") { // Use correct file name
                                            $newFiles[$i] = $newFiles2[$i];
                                        }
                                        if (!$this->file_12->Upload->SaveToFile($newFiles[$i], true, $i)) { // Just replace
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
                                    @unlink($this->file_12->oldPhysicalUploadPath() . $oldFile);
                                }
                            }
                        }
                    }
                    if ($this->file_13->Visible && !$this->file_13->Upload->KeepFile) {
                        $oldFiles = EmptyValue($this->file_13->Upload->DbValue) ? [] : [$this->file_13->htmlDecode($this->file_13->Upload->DbValue)];
                        if (!EmptyValue($this->file_13->Upload->FileName)) {
                            $newFiles = [$this->file_13->Upload->FileName];
                            $newFiles2 = [$this->file_13->htmlDecode($rsnew['file_13'])];
                            $newFileCount = count($newFiles);
                            for ($i = 0; $i < $newFileCount; $i++) {
                                if ($newFiles[$i] != "") {
                                    $file = UploadTempPath($this->file_13, $this->file_13->Upload->Index) . $newFiles[$i];
                                    if (file_exists($file)) {
                                        if (@$newFiles2[$i] != "") { // Use correct file name
                                            $newFiles[$i] = $newFiles2[$i];
                                        }
                                        if (!$this->file_13->Upload->SaveToFile($newFiles[$i], true, $i)) { // Just replace
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
                                    @unlink($this->file_13->oldPhysicalUploadPath() . $oldFile);
                                }
                            }
                        }
                    }
                    if ($this->file_14->Visible && !$this->file_14->Upload->KeepFile) {
                        $oldFiles = EmptyValue($this->file_14->Upload->DbValue) ? [] : [$this->file_14->htmlDecode($this->file_14->Upload->DbValue)];
                        if (!EmptyValue($this->file_14->Upload->FileName)) {
                            $newFiles = [$this->file_14->Upload->FileName];
                            $newFiles2 = [$this->file_14->htmlDecode($rsnew['file_14'])];
                            $newFileCount = count($newFiles);
                            for ($i = 0; $i < $newFileCount; $i++) {
                                if ($newFiles[$i] != "") {
                                    $file = UploadTempPath($this->file_14, $this->file_14->Upload->Index) . $newFiles[$i];
                                    if (file_exists($file)) {
                                        if (@$newFiles2[$i] != "") { // Use correct file name
                                            $newFiles[$i] = $newFiles2[$i];
                                        }
                                        if (!$this->file_14->Upload->SaveToFile($newFiles[$i], true, $i)) { // Just replace
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
                                    @unlink($this->file_14->oldPhysicalUploadPath() . $oldFile);
                                }
                            }
                        }
                    }
                    if ($this->file_15->Visible && !$this->file_15->Upload->KeepFile) {
                        $oldFiles = EmptyValue($this->file_15->Upload->DbValue) ? [] : [$this->file_15->htmlDecode($this->file_15->Upload->DbValue)];
                        if (!EmptyValue($this->file_15->Upload->FileName)) {
                            $newFiles = [$this->file_15->Upload->FileName];
                            $newFiles2 = [$this->file_15->htmlDecode($rsnew['file_15'])];
                            $newFileCount = count($newFiles);
                            for ($i = 0; $i < $newFileCount; $i++) {
                                if ($newFiles[$i] != "") {
                                    $file = UploadTempPath($this->file_15, $this->file_15->Upload->Index) . $newFiles[$i];
                                    if (file_exists($file)) {
                                        if (@$newFiles2[$i] != "") { // Use correct file name
                                            $newFiles[$i] = $newFiles2[$i];
                                        }
                                        if (!$this->file_15->Upload->SaveToFile($newFiles[$i], true, $i)) { // Just replace
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
                                    @unlink($this->file_15->oldPhysicalUploadPath() . $oldFile);
                                }
                            }
                        }
                    }
                    if ($this->file_16->Visible && !$this->file_16->Upload->KeepFile) {
                        $oldFiles = EmptyValue($this->file_16->Upload->DbValue) ? [] : [$this->file_16->htmlDecode($this->file_16->Upload->DbValue)];
                        if (!EmptyValue($this->file_16->Upload->FileName)) {
                            $newFiles = [$this->file_16->Upload->FileName];
                            $newFiles2 = [$this->file_16->htmlDecode($rsnew['file_16'])];
                            $newFileCount = count($newFiles);
                            for ($i = 0; $i < $newFileCount; $i++) {
                                if ($newFiles[$i] != "") {
                                    $file = UploadTempPath($this->file_16, $this->file_16->Upload->Index) . $newFiles[$i];
                                    if (file_exists($file)) {
                                        if (@$newFiles2[$i] != "") { // Use correct file name
                                            $newFiles[$i] = $newFiles2[$i];
                                        }
                                        if (!$this->file_16->Upload->SaveToFile($newFiles[$i], true, $i)) { // Just replace
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
                                    @unlink($this->file_16->oldPhysicalUploadPath() . $oldFile);
                                }
                            }
                        }
                    }
                    if ($this->file_17->Visible && !$this->file_17->Upload->KeepFile) {
                        $oldFiles = EmptyValue($this->file_17->Upload->DbValue) ? [] : [$this->file_17->htmlDecode($this->file_17->Upload->DbValue)];
                        if (!EmptyValue($this->file_17->Upload->FileName)) {
                            $newFiles = [$this->file_17->Upload->FileName];
                            $newFiles2 = [$this->file_17->htmlDecode($rsnew['file_17'])];
                            $newFileCount = count($newFiles);
                            for ($i = 0; $i < $newFileCount; $i++) {
                                if ($newFiles[$i] != "") {
                                    $file = UploadTempPath($this->file_17, $this->file_17->Upload->Index) . $newFiles[$i];
                                    if (file_exists($file)) {
                                        if (@$newFiles2[$i] != "") { // Use correct file name
                                            $newFiles[$i] = $newFiles2[$i];
                                        }
                                        if (!$this->file_17->Upload->SaveToFile($newFiles[$i], true, $i)) { // Just replace
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
                                    @unlink($this->file_17->oldPhysicalUploadPath() . $oldFile);
                                }
                            }
                        }
                    }
                    if ($this->file_18->Visible && !$this->file_18->Upload->KeepFile) {
                        $oldFiles = EmptyValue($this->file_18->Upload->DbValue) ? [] : [$this->file_18->htmlDecode($this->file_18->Upload->DbValue)];
                        if (!EmptyValue($this->file_18->Upload->FileName)) {
                            $newFiles = [$this->file_18->Upload->FileName];
                            $newFiles2 = [$this->file_18->htmlDecode($rsnew['file_18'])];
                            $newFileCount = count($newFiles);
                            for ($i = 0; $i < $newFileCount; $i++) {
                                if ($newFiles[$i] != "") {
                                    $file = UploadTempPath($this->file_18, $this->file_18->Upload->Index) . $newFiles[$i];
                                    if (file_exists($file)) {
                                        if (@$newFiles2[$i] != "") { // Use correct file name
                                            $newFiles[$i] = $newFiles2[$i];
                                        }
                                        if (!$this->file_18->Upload->SaveToFile($newFiles[$i], true, $i)) { // Just replace
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
                                    @unlink($this->file_18->oldPhysicalUploadPath() . $oldFile);
                                }
                            }
                        }
                    }
                    if ($this->file_19->Visible && !$this->file_19->Upload->KeepFile) {
                        $oldFiles = EmptyValue($this->file_19->Upload->DbValue) ? [] : [$this->file_19->htmlDecode($this->file_19->Upload->DbValue)];
                        if (!EmptyValue($this->file_19->Upload->FileName)) {
                            $newFiles = [$this->file_19->Upload->FileName];
                            $newFiles2 = [$this->file_19->htmlDecode($rsnew['file_19'])];
                            $newFileCount = count($newFiles);
                            for ($i = 0; $i < $newFileCount; $i++) {
                                if ($newFiles[$i] != "") {
                                    $file = UploadTempPath($this->file_19, $this->file_19->Upload->Index) . $newFiles[$i];
                                    if (file_exists($file)) {
                                        if (@$newFiles2[$i] != "") { // Use correct file name
                                            $newFiles[$i] = $newFiles2[$i];
                                        }
                                        if (!$this->file_19->Upload->SaveToFile($newFiles[$i], true, $i)) { // Just replace
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
                                    @unlink($this->file_19->oldPhysicalUploadPath() . $oldFile);
                                }
                            }
                        }
                    }
                    if ($this->file_20->Visible && !$this->file_20->Upload->KeepFile) {
                        $oldFiles = EmptyValue($this->file_20->Upload->DbValue) ? [] : [$this->file_20->htmlDecode($this->file_20->Upload->DbValue)];
                        if (!EmptyValue($this->file_20->Upload->FileName)) {
                            $newFiles = [$this->file_20->Upload->FileName];
                            $newFiles2 = [$this->file_20->htmlDecode($rsnew['file_20'])];
                            $newFileCount = count($newFiles);
                            for ($i = 0; $i < $newFileCount; $i++) {
                                if ($newFiles[$i] != "") {
                                    $file = UploadTempPath($this->file_20, $this->file_20->Upload->Index) . $newFiles[$i];
                                    if (file_exists($file)) {
                                        if (@$newFiles2[$i] != "") { // Use correct file name
                                            $newFiles[$i] = $newFiles2[$i];
                                        }
                                        if (!$this->file_20->Upload->SaveToFile($newFiles[$i], true, $i)) { // Just replace
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
                                    @unlink($this->file_20->oldPhysicalUploadPath() . $oldFile);
                                }
                            }
                        }
                    }
                    if ($this->file_21->Visible && !$this->file_21->Upload->KeepFile) {
                        $oldFiles = EmptyValue($this->file_21->Upload->DbValue) ? [] : [$this->file_21->htmlDecode($this->file_21->Upload->DbValue)];
                        if (!EmptyValue($this->file_21->Upload->FileName)) {
                            $newFiles = [$this->file_21->Upload->FileName];
                            $newFiles2 = [$this->file_21->htmlDecode($rsnew['file_21'])];
                            $newFileCount = count($newFiles);
                            for ($i = 0; $i < $newFileCount; $i++) {
                                if ($newFiles[$i] != "") {
                                    $file = UploadTempPath($this->file_21, $this->file_21->Upload->Index) . $newFiles[$i];
                                    if (file_exists($file)) {
                                        if (@$newFiles2[$i] != "") { // Use correct file name
                                            $newFiles[$i] = $newFiles2[$i];
                                        }
                                        if (!$this->file_21->Upload->SaveToFile($newFiles[$i], true, $i)) { // Just replace
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
                                    @unlink($this->file_21->oldPhysicalUploadPath() . $oldFile);
                                }
                            }
                        }
                    }
                    if ($this->file_22->Visible && !$this->file_22->Upload->KeepFile) {
                        $oldFiles = EmptyValue($this->file_22->Upload->DbValue) ? [] : [$this->file_22->htmlDecode($this->file_22->Upload->DbValue)];
                        if (!EmptyValue($this->file_22->Upload->FileName)) {
                            $newFiles = [$this->file_22->Upload->FileName];
                            $newFiles2 = [$this->file_22->htmlDecode($rsnew['file_22'])];
                            $newFileCount = count($newFiles);
                            for ($i = 0; $i < $newFileCount; $i++) {
                                if ($newFiles[$i] != "") {
                                    $file = UploadTempPath($this->file_22, $this->file_22->Upload->Index) . $newFiles[$i];
                                    if (file_exists($file)) {
                                        if (@$newFiles2[$i] != "") { // Use correct file name
                                            $newFiles[$i] = $newFiles2[$i];
                                        }
                                        if (!$this->file_22->Upload->SaveToFile($newFiles[$i], true, $i)) { // Just replace
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
                                    @unlink($this->file_22->oldPhysicalUploadPath() . $oldFile);
                                }
                            }
                        }
                    }
                    if ($this->file_23->Visible && !$this->file_23->Upload->KeepFile) {
                        $oldFiles = EmptyValue($this->file_23->Upload->DbValue) ? [] : [$this->file_23->htmlDecode($this->file_23->Upload->DbValue)];
                        if (!EmptyValue($this->file_23->Upload->FileName)) {
                            $newFiles = [$this->file_23->Upload->FileName];
                            $newFiles2 = [$this->file_23->htmlDecode($rsnew['file_23'])];
                            $newFileCount = count($newFiles);
                            for ($i = 0; $i < $newFileCount; $i++) {
                                if ($newFiles[$i] != "") {
                                    $file = UploadTempPath($this->file_23, $this->file_23->Upload->Index) . $newFiles[$i];
                                    if (file_exists($file)) {
                                        if (@$newFiles2[$i] != "") { // Use correct file name
                                            $newFiles[$i] = $newFiles2[$i];
                                        }
                                        if (!$this->file_23->Upload->SaveToFile($newFiles[$i], true, $i)) { // Just replace
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
                                    @unlink($this->file_23->oldPhysicalUploadPath() . $oldFile);
                                }
                            }
                        }
                    }
                    if ($this->file_24->Visible && !$this->file_24->Upload->KeepFile) {
                        $oldFiles = EmptyValue($this->file_24->Upload->DbValue) ? [] : [$this->file_24->htmlDecode($this->file_24->Upload->DbValue)];
                        if (!EmptyValue($this->file_24->Upload->FileName)) {
                            $newFiles = [$this->file_24->Upload->FileName];
                            $newFiles2 = [$this->file_24->htmlDecode($rsnew['file_24'])];
                            $newFileCount = count($newFiles);
                            for ($i = 0; $i < $newFileCount; $i++) {
                                if ($newFiles[$i] != "") {
                                    $file = UploadTempPath($this->file_24, $this->file_24->Upload->Index) . $newFiles[$i];
                                    if (file_exists($file)) {
                                        if (@$newFiles2[$i] != "") { // Use correct file name
                                            $newFiles[$i] = $newFiles2[$i];
                                        }
                                        if (!$this->file_24->Upload->SaveToFile($newFiles[$i], true, $i)) { // Just replace
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
                                    @unlink($this->file_24->oldPhysicalUploadPath() . $oldFile);
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
            // file_01
            CleanUploadTempPath($this->file_01, $this->file_01->Upload->Index);

            // file_02
            CleanUploadTempPath($this->file_02, $this->file_02->Upload->Index);

            // file_03
            CleanUploadTempPath($this->file_03, $this->file_03->Upload->Index);

            // file_04
            CleanUploadTempPath($this->file_04, $this->file_04->Upload->Index);

            // file_05
            CleanUploadTempPath($this->file_05, $this->file_05->Upload->Index);

            // file_06
            CleanUploadTempPath($this->file_06, $this->file_06->Upload->Index);

            // file_07
            CleanUploadTempPath($this->file_07, $this->file_07->Upload->Index);

            // file_08
            CleanUploadTempPath($this->file_08, $this->file_08->Upload->Index);

            // file_09
            CleanUploadTempPath($this->file_09, $this->file_09->Upload->Index);

            // file_10
            CleanUploadTempPath($this->file_10, $this->file_10->Upload->Index);

            // file_11
            CleanUploadTempPath($this->file_11, $this->file_11->Upload->Index);

            // file_12
            CleanUploadTempPath($this->file_12, $this->file_12->Upload->Index);

            // file_13
            CleanUploadTempPath($this->file_13, $this->file_13->Upload->Index);

            // file_14
            CleanUploadTempPath($this->file_14, $this->file_14->Upload->Index);

            // file_15
            CleanUploadTempPath($this->file_15, $this->file_15->Upload->Index);

            // file_16
            CleanUploadTempPath($this->file_16, $this->file_16->Upload->Index);

            // file_17
            CleanUploadTempPath($this->file_17, $this->file_17->Upload->Index);

            // file_18
            CleanUploadTempPath($this->file_18, $this->file_18->Upload->Index);

            // file_19
            CleanUploadTempPath($this->file_19, $this->file_19->Upload->Index);

            // file_20
            CleanUploadTempPath($this->file_20, $this->file_20->Upload->Index);

            // file_21
            CleanUploadTempPath($this->file_21, $this->file_21->Upload->Index);

            // file_22
            CleanUploadTempPath($this->file_22, $this->file_22->Upload->Index);

            // file_23
            CleanUploadTempPath($this->file_23, $this->file_23->Upload->Index);

            // file_24
            CleanUploadTempPath($this->file_24, $this->file_24->Upload->Index);
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
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("rapbklist"), "", $this->TableVar, true);
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
