<?php

namespace PHPMaker2021\silpa;

use Doctrine\DBAL\ParameterType;

/**
 * Page class
 */
class RapbkDelete extends Rapbk
{
    use MessagesTrait;

    // Page ID
    public $PageID = "delete";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'rapbk';

    // Page object name
    public $PageObjName = "RapbkDelete";

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
    public $DbMasterFilter = "";
    public $DbDetailFilter = "";
    public $StartRecord;
    public $TotalRecords = 0;
    public $RecordCount;
    public $RecKeys = [];
    public $StartRowCount = 1;
    public $RowCount = 0;

    /**
     * Page run
     *
     * @return void
     */
    public function run()
    {
        global $ExportType, $CustomExportType, $ExportFileName, $UserProfile, $Language, $Security, $CurrentForm;
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

        // Set up Breadcrumb
        $this->setupBreadcrumb();

        // Load key parameters
        $this->RecKeys = $this->getRecordKeys(); // Load record keys
        $filter = $this->getFilterFromRecordKeys();
        if ($filter == "") {
            $this->terminate("rapbklist"); // Prevent SQL injection, return to list
            return;
        }

        // Set up filter (WHERE Clause)
        $this->CurrentFilter = $filter;

        // Check if valid User ID
        $conn = $this->getConnection();
        $sql = $this->getSql($this->CurrentFilter);
        $rows = $conn->fetchAll($sql);
        $res = true;
        foreach ($rows as $row) {
            $this->loadRowValues($row);
            if (!$this->showOptionLink("delete")) {
                $userIdMsg = $Language->phrase("NoDeletePermission");
                $this->setFailureMessage($userIdMsg);
                $res = false;
                break;
            }
        }
        if (!$res) {
            $this->terminate("rapbklist"); // Return to list
            return;
        }

        // Get action
        if (IsApi()) {
            $this->CurrentAction = "delete"; // Delete record directly
        } elseif (Post("action") !== null) {
            $this->CurrentAction = Post("action");
        } elseif (Get("action") == "1") {
            $this->CurrentAction = "delete"; // Delete record directly
        } else {
            $this->CurrentAction = "show"; // Display record
        }
        if ($this->isDelete()) {
            $this->SendEmail = true; // Send email on delete success
            if ($this->deleteRows()) { // Delete rows
                if ($this->getSuccessMessage() == "") {
                    $this->setSuccessMessage($Language->phrase("DeleteSuccess")); // Set up success message
                }
                if (IsApi()) {
                    $this->terminate(true);
                    return;
                } else {
                    $this->terminate($this->getReturnUrl()); // Return to caller
                    return;
                }
            } else { // Delete failed
                if (IsApi()) {
                    $this->terminate();
                    return;
                }
                $this->CurrentAction = "show"; // Display record
            }
        }
        if ($this->isShow()) { // Load records for display
            if ($this->Recordset = $this->loadRecordset()) {
                $this->TotalRecords = $this->Recordset->recordCount(); // Get record count
            }
            if ($this->TotalRecords <= 0) { // No record found, exit
                if ($this->Recordset) {
                    $this->Recordset->close();
                }
                $this->terminate("rapbklist"); // Return to list
                return;
            }
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
        }

        // Call Row Rendered event
        if ($this->RowType != ROWTYPE_AGGREGATEINIT) {
            $this->rowRendered();
        }
    }

    // Delete records based on current filter
    protected function deleteRows()
    {
        global $Language, $Security;
        if (!$Security->canDelete()) {
            $this->setFailureMessage($Language->phrase("NoDeletePermission")); // No delete permission
            return false;
        }
        $deleteRows = true;
        $sql = $this->getCurrentSql();
        $conn = $this->getConnection();
        $rows = $conn->fetchAll($sql);
        if (count($rows) == 0) {
            $this->setFailureMessage($Language->phrase("NoRecord")); // No record found
            return false;
        }
        $conn->beginTransaction();

        // Clone old rows
        $rsold = $rows;

        // Call row deleting event
        if ($deleteRows) {
            foreach ($rsold as $row) {
                $deleteRows = $this->rowDeleting($row);
                if (!$deleteRows) {
                    break;
                }
            }
        }
        if ($deleteRows) {
            $key = "";
            foreach ($rsold as $row) {
                $thisKey = "";
                if ($thisKey != "") {
                    $thisKey .= Config("COMPOSITE_KEY_SEPARATOR");
                }
                $thisKey .= $row['idd_evaluasi'];
                if (Config("DELETE_UPLOADED_FILES")) { // Delete old files
                    $this->deleteUploadedFiles($row);
                }
                $deleteRows = $this->delete($row); // Delete
                if ($deleteRows === false) {
                    break;
                }
                if ($key != "") {
                    $key .= ", ";
                }
                $key .= $thisKey;
            }
        }
        if (!$deleteRows) {
            // Set up error message
            if ($this->getSuccessMessage() != "" || $this->getFailureMessage() != "") {
                // Use the message, do nothing
            } elseif ($this->CancelMessage != "") {
                $this->setFailureMessage($this->CancelMessage);
                $this->CancelMessage = "";
            } else {
                $this->setFailureMessage($Language->phrase("DeleteCancelled"));
            }
        }
        if ($deleteRows) {
            $conn->commit(); // Commit the changes
        } else {
            $conn->rollback(); // Rollback changes
        }

        // Call Row Deleted event
        if ($deleteRows) {
            foreach ($rsold as $row) {
                $this->rowDeleted($row);
            }
        }

        // Write JSON for API request
        if (IsApi() && $deleteRows) {
            $row = $this->getRecordsFromRecordset($rsold);
            WriteJson(["success" => true, $this->TableVar => $row]);
        }
        return $deleteRows;
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
        $pageId = "delete";
        $Breadcrumb->add("delete", $pageId, $url);
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
}
