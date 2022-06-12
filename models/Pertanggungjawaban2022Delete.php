<?php

namespace PHPMaker2021\silpa;

use Doctrine\DBAL\ParameterType;

/**
 * Page class
 */
class Pertanggungjawaban2022Delete extends Pertanggungjawaban2022
{
    use MessagesTrait;

    // Page ID
    public $PageID = "delete";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'pertanggungjawaban2022';

    // Page object name
    public $PageObjName = "Pertanggungjawaban2022Delete";

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
        $this->idd_evaluasi->Visible = false;
        $this->kd_satker->setVisibility();
        $this->idd_tahapan->setVisibility();
        $this->tahun_anggaran->setVisibility();
        $this->surat_pengantar->Visible = false;
        $this->skd_rqanunpert->Visible = false;
        $this->rqanun_apbkpert->Visible = false;
        $this->rperbup_apbkpert->Visible = false;
        $this->pbkdd_apbkpert->Visible = false;
        $this->risalah_sidang->Visible = false;
        $this->absen_peserta->Visible = false;
        $this->neraca->Visible = false;
        $this->lra->Visible = false;
        $this->calk->Visible = false;
        $this->lo->Visible = false;
        $this->lpe->Visible = false;
        $this->lpsal->Visible = false;
        $this->lak->Visible = false;
        $this->laporan_pemeriksaan->Visible = false;
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

        // Set up Breadcrumb
        $this->setupBreadcrumb();

        // Load key parameters
        $this->RecKeys = $this->getRecordKeys(); // Load record keys
        $filter = $this->getFilterFromRecordKeys();
        if ($filter == "") {
            $this->terminate("pertanggungjawaban2022list"); // Prevent SQL injection, return to list
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
            $this->terminate("pertanggungjawaban2022list"); // Return to list
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
                $this->terminate("pertanggungjawaban2022list"); // Return to list
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

            // status
            $this->status->LinkCustomAttributes = "";
            $this->status->HrefValue = "";
            $this->status->TooltipValue = "";
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
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("pertanggungjawaban2022list"), "", $this->TableVar, true);
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
