<?php

namespace PHPMaker2021\silpa;

use Doctrine\DBAL\ParameterType;

/**
 * Table class for view_pertanggungjawaban_2022_ev
 */
class ViewPertanggungjawaban2022Ev extends DbTable
{
    protected $SqlFrom = "";
    protected $SqlSelect = null;
    protected $SqlSelectList = null;
    protected $SqlWhere = "";
    protected $SqlGroupBy = "";
    protected $SqlHaving = "";
    protected $SqlOrderBy = "";
    public $UseSessionForListSql = true;

    // Column CSS classes
    public $LeftColumnClass = "col-sm-2 col-form-label ew-label";
    public $RightColumnClass = "col-sm-10";
    public $OffsetColumnClass = "col-sm-10 offset-sm-2";
    public $TableLeftColumnClass = "w-col-2";

    // Export
    public $ExportDoc;

    // Fields
    public $kd_satker;
    public $idd_tahapan;
    public $tahun_anggaran;
    public $surat_pengantar;
    public $skd_rqanunpert;
    public $rqanun_apbkpert;
    public $rperbup_apbkpert;
    public $pbkdd_apbkpert;
    public $risalah_sidang;
    public $absen_peserta;
    public $neraca;
    public $lra;
    public $calk;
    public $lo;
    public $lpe;
    public $lpsal;
    public $lak;
    public $laporan_pemeriksaan;
    public $status;
    public $tanggal_upload;
    public $tanggal_update;

    // Page ID
    public $PageID = ""; // To be overridden by subclass

    // Constructor
    public function __construct()
    {
        global $Language, $CurrentLanguage;
        parent::__construct();

        // Language object
        $Language = Container("language");
        $this->TableVar = 'view_pertanggungjawaban_2022_ev';
        $this->TableName = 'view_pertanggungjawaban_2022_ev';
        $this->TableType = 'VIEW';

        // Update Table
        $this->UpdateTable = "`view_pertanggungjawaban_2022_ev`";
        $this->Dbid = 'DB';
        $this->ExportAll = true;
        $this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
        $this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
        $this->ExportPageSize = "a4"; // Page size (PDF only)
        $this->ExportExcelPageOrientation = ""; // Page orientation (PhpSpreadsheet only)
        $this->ExportExcelPageSize = ""; // Page size (PhpSpreadsheet only)
        $this->ExportWordPageOrientation = "portrait"; // Page orientation (PHPWord only)
        $this->ExportWordColumnWidth = null; // Cell width (PHPWord only)
        $this->DetailAdd = false; // Allow detail add
        $this->DetailEdit = false; // Allow detail edit
        $this->DetailView = false; // Allow detail view
        $this->ShowMultipleDetails = false; // Show multiple details
        $this->GridAddRowCount = 5;
        $this->AllowAddDeleteRow = true; // Allow add/delete row
        $this->UserIDAllowSecurity = Config("DEFAULT_USER_ID_ALLOW_SECURITY"); // Default User ID allowed permissions
        $this->BasicSearch = new BasicSearch($this->TableVar);

        // kd_satker
        $this->kd_satker = new DbField('view_pertanggungjawaban_2022_ev', 'view_pertanggungjawaban_2022_ev', 'x_kd_satker', 'kd_satker', '`kd_satker`', '`kd_satker`', 200, 100, -1, false, '`kd_satker`', false, false, false, 'FORMATTED TEXT', 'SELECT');
        $this->kd_satker->Nullable = false; // NOT NULL field
        $this->kd_satker->Required = true; // Required field
        $this->kd_satker->Sortable = true; // Allow sort
        $this->kd_satker->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->kd_satker->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        switch ($CurrentLanguage) {
            case "en":
                $this->kd_satker->Lookup = new Lookup('kd_satker', 'satkers', false, 'kode_pemda', ["nama_satker","","",""], [], [], [], [], [], [], '', '');
                break;
            default:
                $this->kd_satker->Lookup = new Lookup('kd_satker', 'satkers', false, 'kode_pemda', ["nama_satker","","",""], [], [], [], [], [], [], '', '');
                break;
        }
        $this->kd_satker->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->kd_satker->Param, "CustomMsg");
        $this->Fields['kd_satker'] = &$this->kd_satker;

        // idd_tahapan
        $this->idd_tahapan = new DbField('view_pertanggungjawaban_2022_ev', 'view_pertanggungjawaban_2022_ev', 'x_idd_tahapan', 'idd_tahapan', '`idd_tahapan`', '`idd_tahapan`', 3, 100, -1, false, '`idd_tahapan`', false, false, false, 'FORMATTED TEXT', 'SELECT');
        $this->idd_tahapan->Nullable = false; // NOT NULL field
        $this->idd_tahapan->Required = true; // Required field
        $this->idd_tahapan->Sortable = true; // Allow sort
        $this->idd_tahapan->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->idd_tahapan->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        switch ($CurrentLanguage) {
            case "en":
                $this->idd_tahapan->Lookup = new Lookup('idd_tahapan', 'tahapan', false, 'idd_tahapan', ["nama_tahapan","","",""], [], [], [], [], [], [], '', '');
                break;
            default:
                $this->idd_tahapan->Lookup = new Lookup('idd_tahapan', 'tahapan', false, 'idd_tahapan', ["nama_tahapan","","",""], [], [], [], [], [], [], '', '');
                break;
        }
        $this->idd_tahapan->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->idd_tahapan->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->idd_tahapan->Param, "CustomMsg");
        $this->Fields['idd_tahapan'] = &$this->idd_tahapan;

        // tahun_anggaran
        $this->tahun_anggaran = new DbField('view_pertanggungjawaban_2022_ev', 'view_pertanggungjawaban_2022_ev', 'x_tahun_anggaran', 'tahun_anggaran', '`tahun_anggaran`', '`tahun_anggaran`', 200, 100, -1, false, '`tahun_anggaran`', false, false, false, 'FORMATTED TEXT', 'SELECT');
        $this->tahun_anggaran->Nullable = false; // NOT NULL field
        $this->tahun_anggaran->Required = true; // Required field
        $this->tahun_anggaran->Sortable = true; // Allow sort
        $this->tahun_anggaran->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->tahun_anggaran->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        switch ($CurrentLanguage) {
            case "en":
                $this->tahun_anggaran->Lookup = new Lookup('tahun_anggaran', 'tahun', false, 'id_tahun', ["tahun","","",""], [], [], [], [], [], [], '', '');
                break;
            default:
                $this->tahun_anggaran->Lookup = new Lookup('tahun_anggaran', 'tahun', false, 'id_tahun', ["tahun","","",""], [], [], [], [], [], [], '', '');
                break;
        }
        $this->tahun_anggaran->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->tahun_anggaran->Param, "CustomMsg");
        $this->Fields['tahun_anggaran'] = &$this->tahun_anggaran;

        // surat_pengantar
        $this->surat_pengantar = new DbField('view_pertanggungjawaban_2022_ev', 'view_pertanggungjawaban_2022_ev', 'x_surat_pengantar', 'surat_pengantar', '`surat_pengantar`', '`surat_pengantar`', 200, 200, -1, true, '`surat_pengantar`', false, false, false, 'FORMATTED TEXT', 'FILE');
        $this->surat_pengantar->Sortable = true; // Allow sort
        $this->surat_pengantar->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->surat_pengantar->Param, "CustomMsg");
        $this->Fields['surat_pengantar'] = &$this->surat_pengantar;

        // skd_rqanunpert
        $this->skd_rqanunpert = new DbField('view_pertanggungjawaban_2022_ev', 'view_pertanggungjawaban_2022_ev', 'x_skd_rqanunpert', 'skd_rqanunpert', '`skd_rqanunpert`', '`skd_rqanunpert`', 200, 200, -1, true, '`skd_rqanunpert`', false, false, false, 'FORMATTED TEXT', 'FILE');
        $this->skd_rqanunpert->Sortable = true; // Allow sort
        $this->skd_rqanunpert->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->skd_rqanunpert->Param, "CustomMsg");
        $this->Fields['skd_rqanunpert'] = &$this->skd_rqanunpert;

        // rqanun_apbkpert
        $this->rqanun_apbkpert = new DbField('view_pertanggungjawaban_2022_ev', 'view_pertanggungjawaban_2022_ev', 'x_rqanun_apbkpert', 'rqanun_apbkpert', '`rqanun_apbkpert`', '`rqanun_apbkpert`', 200, 200, -1, true, '`rqanun_apbkpert`', false, false, false, 'FORMATTED TEXT', 'FILE');
        $this->rqanun_apbkpert->Sortable = true; // Allow sort
        $this->rqanun_apbkpert->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->rqanun_apbkpert->Param, "CustomMsg");
        $this->Fields['rqanun_apbkpert'] = &$this->rqanun_apbkpert;

        // rperbup_apbkpert
        $this->rperbup_apbkpert = new DbField('view_pertanggungjawaban_2022_ev', 'view_pertanggungjawaban_2022_ev', 'x_rperbup_apbkpert', 'rperbup_apbkpert', '`rperbup_apbkpert`', '`rperbup_apbkpert`', 200, 200, -1, true, '`rperbup_apbkpert`', false, false, false, 'FORMATTED TEXT', 'FILE');
        $this->rperbup_apbkpert->Sortable = true; // Allow sort
        $this->rperbup_apbkpert->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->rperbup_apbkpert->Param, "CustomMsg");
        $this->Fields['rperbup_apbkpert'] = &$this->rperbup_apbkpert;

        // pbkdd_apbkpert
        $this->pbkdd_apbkpert = new DbField('view_pertanggungjawaban_2022_ev', 'view_pertanggungjawaban_2022_ev', 'x_pbkdd_apbkpert', 'pbkdd_apbkpert', '`pbkdd_apbkpert`', '`pbkdd_apbkpert`', 200, 200, -1, true, '`pbkdd_apbkpert`', false, false, false, 'FORMATTED TEXT', 'FILE');
        $this->pbkdd_apbkpert->Sortable = true; // Allow sort
        $this->pbkdd_apbkpert->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->pbkdd_apbkpert->Param, "CustomMsg");
        $this->Fields['pbkdd_apbkpert'] = &$this->pbkdd_apbkpert;

        // risalah_sidang
        $this->risalah_sidang = new DbField('view_pertanggungjawaban_2022_ev', 'view_pertanggungjawaban_2022_ev', 'x_risalah_sidang', 'risalah_sidang', '`risalah_sidang`', '`risalah_sidang`', 200, 200, -1, true, '`risalah_sidang`', false, false, false, 'FORMATTED TEXT', 'FILE');
        $this->risalah_sidang->Sortable = true; // Allow sort
        $this->risalah_sidang->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->risalah_sidang->Param, "CustomMsg");
        $this->Fields['risalah_sidang'] = &$this->risalah_sidang;

        // absen_peserta
        $this->absen_peserta = new DbField('view_pertanggungjawaban_2022_ev', 'view_pertanggungjawaban_2022_ev', 'x_absen_peserta', 'absen_peserta', '`absen_peserta`', '`absen_peserta`', 200, 200, -1, true, '`absen_peserta`', false, false, false, 'FORMATTED TEXT', 'FILE');
        $this->absen_peserta->Sortable = true; // Allow sort
        $this->absen_peserta->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->absen_peserta->Param, "CustomMsg");
        $this->Fields['absen_peserta'] = &$this->absen_peserta;

        // neraca
        $this->neraca = new DbField('view_pertanggungjawaban_2022_ev', 'view_pertanggungjawaban_2022_ev', 'x_neraca', 'neraca', '`neraca`', '`neraca`', 200, 200, -1, true, '`neraca`', false, false, false, 'FORMATTED TEXT', 'FILE');
        $this->neraca->Sortable = true; // Allow sort
        $this->neraca->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->neraca->Param, "CustomMsg");
        $this->Fields['neraca'] = &$this->neraca;

        // lra
        $this->lra = new DbField('view_pertanggungjawaban_2022_ev', 'view_pertanggungjawaban_2022_ev', 'x_lra', 'lra', '`lra`', '`lra`', 200, 200, -1, true, '`lra`', false, false, false, 'FORMATTED TEXT', 'FILE');
        $this->lra->Sortable = true; // Allow sort
        $this->lra->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->lra->Param, "CustomMsg");
        $this->Fields['lra'] = &$this->lra;

        // calk
        $this->calk = new DbField('view_pertanggungjawaban_2022_ev', 'view_pertanggungjawaban_2022_ev', 'x_calk', 'calk', '`calk`', '`calk`', 200, 200, -1, true, '`calk`', false, false, false, 'FORMATTED TEXT', 'FILE');
        $this->calk->Sortable = true; // Allow sort
        $this->calk->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->calk->Param, "CustomMsg");
        $this->Fields['calk'] = &$this->calk;

        // lo
        $this->lo = new DbField('view_pertanggungjawaban_2022_ev', 'view_pertanggungjawaban_2022_ev', 'x_lo', 'lo', '`lo`', '`lo`', 200, 200, -1, true, '`lo`', false, false, false, 'FORMATTED TEXT', 'FILE');
        $this->lo->Sortable = true; // Allow sort
        $this->lo->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->lo->Param, "CustomMsg");
        $this->Fields['lo'] = &$this->lo;

        // lpe
        $this->lpe = new DbField('view_pertanggungjawaban_2022_ev', 'view_pertanggungjawaban_2022_ev', 'x_lpe', 'lpe', '`lpe`', '`lpe`', 200, 200, -1, true, '`lpe`', false, false, false, 'FORMATTED TEXT', 'FILE');
        $this->lpe->Sortable = true; // Allow sort
        $this->lpe->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->lpe->Param, "CustomMsg");
        $this->Fields['lpe'] = &$this->lpe;

        // lpsal
        $this->lpsal = new DbField('view_pertanggungjawaban_2022_ev', 'view_pertanggungjawaban_2022_ev', 'x_lpsal', 'lpsal', '`lpsal`', '`lpsal`', 200, 200, -1, true, '`lpsal`', false, false, false, 'FORMATTED TEXT', 'FILE');
        $this->lpsal->Sortable = true; // Allow sort
        $this->lpsal->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->lpsal->Param, "CustomMsg");
        $this->Fields['lpsal'] = &$this->lpsal;

        // lak
        $this->lak = new DbField('view_pertanggungjawaban_2022_ev', 'view_pertanggungjawaban_2022_ev', 'x_lak', 'lak', '`lak`', '`lak`', 200, 200, -1, true, '`lak`', false, false, false, 'FORMATTED TEXT', 'FILE');
        $this->lak->Sortable = true; // Allow sort
        $this->lak->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->lak->Param, "CustomMsg");
        $this->Fields['lak'] = &$this->lak;

        // laporan_pemeriksaan
        $this->laporan_pemeriksaan = new DbField('view_pertanggungjawaban_2022_ev', 'view_pertanggungjawaban_2022_ev', 'x_laporan_pemeriksaan', 'laporan_pemeriksaan', '`laporan_pemeriksaan`', '`laporan_pemeriksaan`', 200, 200, -1, true, '`laporan_pemeriksaan`', false, false, false, 'FORMATTED TEXT', 'FILE');
        $this->laporan_pemeriksaan->Sortable = true; // Allow sort
        $this->laporan_pemeriksaan->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->laporan_pemeriksaan->Param, "CustomMsg");
        $this->Fields['laporan_pemeriksaan'] = &$this->laporan_pemeriksaan;

        // status
        $this->status = new DbField('view_pertanggungjawaban_2022_ev', 'view_pertanggungjawaban_2022_ev', 'x_status', 'status', '`status`', '`status`', 3, 11, -1, false, '`status`', false, false, false, 'FORMATTED TEXT', 'SELECT');
        $this->status->Nullable = false; // NOT NULL field
        $this->status->Required = true; // Required field
        $this->status->Sortable = true; // Allow sort
        $this->status->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->status->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        switch ($CurrentLanguage) {
            case "en":
                $this->status->Lookup = new Lookup('status', 'view_pertanggungjawaban_2022_ev', false, '', ["","","",""], [], [], [], [], [], [], '', '');
                break;
            default:
                $this->status->Lookup = new Lookup('status', 'view_pertanggungjawaban_2022_ev', false, '', ["","","",""], [], [], [], [], [], [], '', '');
                break;
        }
        $this->status->OptionCount = 3;
        $this->status->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->status->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->status->Param, "CustomMsg");
        $this->Fields['status'] = &$this->status;

        // tanggal_upload
        $this->tanggal_upload = new DbField('view_pertanggungjawaban_2022_ev', 'view_pertanggungjawaban_2022_ev', 'x_tanggal_upload', 'tanggal_upload', '`tanggal_upload`', CastDateFieldForLike("`tanggal_upload`", 1, "DB"), 135, 19, -1, false, '`tanggal_upload`', false, false, false, 'FORMATTED TEXT', 'HIDDEN');
        $this->tanggal_upload->Sortable = true; // Allow sort
        $this->tanggal_upload->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->tanggal_upload->Param, "CustomMsg");
        $this->Fields['tanggal_upload'] = &$this->tanggal_upload;

        // tanggal_update
        $this->tanggal_update = new DbField('view_pertanggungjawaban_2022_ev', 'view_pertanggungjawaban_2022_ev', 'x_tanggal_update', 'tanggal_update', '`tanggal_update`', CastDateFieldForLike("`tanggal_update`", 1, "DB"), 135, 19, -1, false, '`tanggal_update`', false, false, false, 'FORMATTED TEXT', 'HIDDEN');
        $this->tanggal_update->Sortable = true; // Allow sort
        $this->tanggal_update->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->tanggal_update->Param, "CustomMsg");
        $this->Fields['tanggal_update'] = &$this->tanggal_update;
    }

    // Field Visibility
    public function getFieldVisibility($fldParm)
    {
        global $Security;
        return $this->$fldParm->Visible; // Returns original value
    }

    // Set left column class (must be predefined col-*-* classes of Bootstrap grid system)
    public function setLeftColumnClass($class)
    {
        if (preg_match('/^col\-(\w+)\-(\d+)$/', $class, $match)) {
            $this->LeftColumnClass = $class . " col-form-label ew-label";
            $this->RightColumnClass = "col-" . $match[1] . "-" . strval(12 - (int)$match[2]);
            $this->OffsetColumnClass = $this->RightColumnClass . " " . str_replace("col-", "offset-", $class);
            $this->TableLeftColumnClass = preg_replace('/^col-\w+-(\d+)$/', "w-col-$1", $class); // Change to w-col-*
        }
    }

    // Single column sort
    public function updateSort(&$fld)
    {
        if ($this->CurrentOrder == $fld->Name) {
            $sortField = $fld->Expression;
            $lastSort = $fld->getSort();
            if (in_array($this->CurrentOrderType, ["ASC", "DESC", "NO"])) {
                $curSort = $this->CurrentOrderType;
            } else {
                $curSort = $lastSort;
            }
            $fld->setSort($curSort);
            $orderBy = in_array($curSort, ["ASC", "DESC"]) ? $sortField . " " . $curSort : "";
            $this->setSessionOrderBy($orderBy); // Save to Session
        } else {
            $fld->setSort("");
        }
    }

    // Table level SQL
    public function getSqlFrom() // From
    {
        return ($this->SqlFrom != "") ? $this->SqlFrom : "`view_pertanggungjawaban_2022_ev`";
    }

    public function sqlFrom() // For backward compatibility
    {
        return $this->getSqlFrom();
    }

    public function setSqlFrom($v)
    {
        $this->SqlFrom = $v;
    }

    public function getSqlSelect() // Select
    {
        return $this->SqlSelect ?? $this->getQueryBuilder()->select("*");
    }

    public function sqlSelect() // For backward compatibility
    {
        return $this->getSqlSelect();
    }

    public function setSqlSelect($v)
    {
        $this->SqlSelect = $v;
    }

    public function getSqlWhere() // Where
    {
        $where = ($this->SqlWhere != "") ? $this->SqlWhere : "";
        $this->DefaultFilter = "";
        AddFilter($where, $this->DefaultFilter);
        return $where;
    }

    public function sqlWhere() // For backward compatibility
    {
        return $this->getSqlWhere();
    }

    public function setSqlWhere($v)
    {
        $this->SqlWhere = $v;
    }

    public function getSqlGroupBy() // Group By
    {
        return ($this->SqlGroupBy != "") ? $this->SqlGroupBy : "";
    }

    public function sqlGroupBy() // For backward compatibility
    {
        return $this->getSqlGroupBy();
    }

    public function setSqlGroupBy($v)
    {
        $this->SqlGroupBy = $v;
    }

    public function getSqlHaving() // Having
    {
        return ($this->SqlHaving != "") ? $this->SqlHaving : "";
    }

    public function sqlHaving() // For backward compatibility
    {
        return $this->getSqlHaving();
    }

    public function setSqlHaving($v)
    {
        $this->SqlHaving = $v;
    }

    public function getSqlOrderBy() // Order By
    {
        return ($this->SqlOrderBy != "") ? $this->SqlOrderBy : $this->DefaultSort;
    }

    public function sqlOrderBy() // For backward compatibility
    {
        return $this->getSqlOrderBy();
    }

    public function setSqlOrderBy($v)
    {
        $this->SqlOrderBy = $v;
    }

    // Apply User ID filters
    public function applyUserIDFilters($filter)
    {
        return $filter;
    }

    // Check if User ID security allows view all
    public function userIDAllow($id = "")
    {
        $allow = $this->UserIDAllowSecurity;
        switch ($id) {
            case "add":
            case "copy":
            case "gridadd":
            case "register":
            case "addopt":
                return (($allow & 1) == 1);
            case "edit":
            case "gridedit":
            case "update":
            case "changepassword":
            case "resetpassword":
                return (($allow & 4) == 4);
            case "delete":
                return (($allow & 2) == 2);
            case "view":
                return (($allow & 32) == 32);
            case "search":
                return (($allow & 64) == 64);
            default:
                return (($allow & 8) == 8);
        }
    }

    /**
     * Get record count
     *
     * @param string|QueryBuilder $sql SQL or QueryBuilder
     * @param mixed $c Connection
     * @return int
     */
    public function getRecordCount($sql, $c = null)
    {
        $cnt = -1;
        $rs = null;
        if ($sql instanceof \Doctrine\DBAL\Query\QueryBuilder) { // Query builder
            $sqlwrk = clone $sql;
            $sqlwrk = $sqlwrk->resetQueryPart("orderBy")->getSQL();
        } else {
            $sqlwrk = $sql;
        }
        $pattern = '/^SELECT\s([\s\S]+)\sFROM\s/i';
        // Skip Custom View / SubQuery / SELECT DISTINCT / ORDER BY
        if (
            ($this->TableType == 'TABLE' || $this->TableType == 'VIEW' || $this->TableType == 'LINKTABLE') &&
            preg_match($pattern, $sqlwrk) && !preg_match('/\(\s*(SELECT[^)]+)\)/i', $sqlwrk) &&
            !preg_match('/^\s*select\s+distinct\s+/i', $sqlwrk) && !preg_match('/\s+order\s+by\s+/i', $sqlwrk)
        ) {
            $sqlwrk = "SELECT COUNT(*) FROM " . preg_replace($pattern, "", $sqlwrk);
        } else {
            $sqlwrk = "SELECT COUNT(*) FROM (" . $sqlwrk . ") COUNT_TABLE";
        }
        $conn = $c ?? $this->getConnection();
        $rs = $conn->executeQuery($sqlwrk);
        $cnt = $rs->fetchColumn();
        if ($cnt !== false) {
            return (int)$cnt;
        }

        // Unable to get count by SELECT COUNT(*), execute the SQL to get record count directly
        return ExecuteRecordCount($sql, $conn);
    }

    // Get SQL
    public function getSql($where, $orderBy = "")
    {
        return $this->buildSelectSql(
            $this->getSqlSelect(),
            $this->getSqlFrom(),
            $this->getSqlWhere(),
            $this->getSqlGroupBy(),
            $this->getSqlHaving(),
            $this->getSqlOrderBy(),
            $where,
            $orderBy
        )->getSQL();
    }

    // Table SQL
    public function getCurrentSql()
    {
        $filter = $this->CurrentFilter;
        $filter = $this->applyUserIDFilters($filter);
        $sort = $this->getSessionOrderBy();
        return $this->getSql($filter, $sort);
    }

    /**
     * Table SQL with List page filter
     *
     * @return QueryBuilder
     */
    public function getListSql()
    {
        $filter = $this->UseSessionForListSql ? $this->getSessionWhere() : "";
        AddFilter($filter, $this->CurrentFilter);
        $filter = $this->applyUserIDFilters($filter);
        $this->recordsetSelecting($filter);
        $select = $this->getSqlSelect();
        $from = $this->getSqlFrom();
        $sort = $this->UseSessionForListSql ? $this->getSessionOrderBy() : "";
        $this->Sort = $sort;
        return $this->buildSelectSql(
            $select,
            $from,
            $this->getSqlWhere(),
            $this->getSqlGroupBy(),
            $this->getSqlHaving(),
            $this->getSqlOrderBy(),
            $filter,
            $sort
        );
    }

    // Get ORDER BY clause
    public function getOrderBy()
    {
        $orderBy = $this->getSqlOrderBy();
        $sort = $this->getSessionOrderBy();
        if ($orderBy != "" && $sort != "") {
            $orderBy .= ", " . $sort;
        } elseif ($sort != "") {
            $orderBy = $sort;
        }
        return $orderBy;
    }

    // Get record count based on filter (for detail record count in master table pages)
    public function loadRecordCount($filter)
    {
        $origFilter = $this->CurrentFilter;
        $this->CurrentFilter = $filter;
        $this->recordsetSelecting($this->CurrentFilter);
        $select = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlSelect() : $this->getQueryBuilder()->select("*");
        $groupBy = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlGroupBy() : "";
        $having = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlHaving() : "";
        $sql = $this->buildSelectSql($select, $this->getSqlFrom(), $this->getSqlWhere(), $groupBy, $having, "", $this->CurrentFilter, "");
        $cnt = $this->getRecordCount($sql);
        $this->CurrentFilter = $origFilter;
        return $cnt;
    }

    // Get record count (for current List page)
    public function listRecordCount()
    {
        $filter = $this->getSessionWhere();
        AddFilter($filter, $this->CurrentFilter);
        $filter = $this->applyUserIDFilters($filter);
        $this->recordsetSelecting($filter);
        $select = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlSelect() : $this->getQueryBuilder()->select("*");
        $groupBy = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlGroupBy() : "";
        $having = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlHaving() : "";
        $sql = $this->buildSelectSql($select, $this->getSqlFrom(), $this->getSqlWhere(), $groupBy, $having, "", $filter, "");
        $cnt = $this->getRecordCount($sql);
        return $cnt;
    }

    /**
     * INSERT statement
     *
     * @param mixed $rs
     * @return QueryBuilder
     */
    protected function insertSql(&$rs)
    {
        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder->insert($this->UpdateTable);
        foreach ($rs as $name => $value) {
            if (!isset($this->Fields[$name]) || $this->Fields[$name]->IsCustom) {
                continue;
            }
            $type = GetParameterType($this->Fields[$name], $value, $this->Dbid);
            $queryBuilder->setValue($this->Fields[$name]->Expression, $queryBuilder->createPositionalParameter($value, $type));
        }
        return $queryBuilder;
    }

    // Insert
    public function insert(&$rs)
    {
        $conn = $this->getConnection();
        $success = $this->insertSql($rs)->execute();
        if ($success) {
        }
        return $success;
    }

    /**
     * UPDATE statement
     *
     * @param array $rs Data to be updated
     * @param string|array $where WHERE clause
     * @param string $curfilter Filter
     * @return QueryBuilder
     */
    protected function updateSql(&$rs, $where = "", $curfilter = true)
    {
        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder->update($this->UpdateTable);
        foreach ($rs as $name => $value) {
            if (!isset($this->Fields[$name]) || $this->Fields[$name]->IsCustom || $this->Fields[$name]->IsAutoIncrement) {
                continue;
            }
            $type = GetParameterType($this->Fields[$name], $value, $this->Dbid);
            $queryBuilder->set($this->Fields[$name]->Expression, $queryBuilder->createPositionalParameter($value, $type));
        }
        $filter = ($curfilter) ? $this->CurrentFilter : "";
        if (is_array($where)) {
            $where = $this->arrayToFilter($where);
        }
        AddFilter($filter, $where);
        if ($filter != "") {
            $queryBuilder->where($filter);
        }
        return $queryBuilder;
    }

    // Update
    public function update(&$rs, $where = "", $rsold = null, $curfilter = true)
    {
        // If no field is updated, execute may return 0. Treat as success
        $success = $this->updateSql($rs, $where, $curfilter)->execute();
        $success = ($success > 0) ? $success : true;
        return $success;
    }

    /**
     * DELETE statement
     *
     * @param array $rs Key values
     * @param string|array $where WHERE clause
     * @param string $curfilter Filter
     * @return QueryBuilder
     */
    protected function deleteSql(&$rs, $where = "", $curfilter = true)
    {
        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder->delete($this->UpdateTable);
        if (is_array($where)) {
            $where = $this->arrayToFilter($where);
        }
        if ($rs) {
        }
        $filter = ($curfilter) ? $this->CurrentFilter : "";
        AddFilter($filter, $where);
        return $queryBuilder->where($filter != "" ? $filter : "0=1");
    }

    // Delete
    public function delete(&$rs, $where = "", $curfilter = false)
    {
        $success = true;
        if ($success) {
            $success = $this->deleteSql($rs, $where, $curfilter)->execute();
        }
        return $success;
    }

    // Load DbValue from recordset or array
    protected function loadDbValues($row)
    {
        if (!is_array($row)) {
            return;
        }
        $this->kd_satker->DbValue = $row['kd_satker'];
        $this->idd_tahapan->DbValue = $row['idd_tahapan'];
        $this->tahun_anggaran->DbValue = $row['tahun_anggaran'];
        $this->surat_pengantar->Upload->DbValue = $row['surat_pengantar'];
        $this->skd_rqanunpert->Upload->DbValue = $row['skd_rqanunpert'];
        $this->rqanun_apbkpert->Upload->DbValue = $row['rqanun_apbkpert'];
        $this->rperbup_apbkpert->Upload->DbValue = $row['rperbup_apbkpert'];
        $this->pbkdd_apbkpert->Upload->DbValue = $row['pbkdd_apbkpert'];
        $this->risalah_sidang->Upload->DbValue = $row['risalah_sidang'];
        $this->absen_peserta->Upload->DbValue = $row['absen_peserta'];
        $this->neraca->Upload->DbValue = $row['neraca'];
        $this->lra->Upload->DbValue = $row['lra'];
        $this->calk->Upload->DbValue = $row['calk'];
        $this->lo->Upload->DbValue = $row['lo'];
        $this->lpe->Upload->DbValue = $row['lpe'];
        $this->lpsal->Upload->DbValue = $row['lpsal'];
        $this->lak->Upload->DbValue = $row['lak'];
        $this->laporan_pemeriksaan->Upload->DbValue = $row['laporan_pemeriksaan'];
        $this->status->DbValue = $row['status'];
        $this->tanggal_upload->DbValue = $row['tanggal_upload'];
        $this->tanggal_update->DbValue = $row['tanggal_update'];
    }

    // Delete uploaded files
    public function deleteUploadedFiles($row)
    {
        $this->loadDbValues($row);
        $this->surat_pengantar->OldUploadPath = "files/evaluasi/2022/pertanggungjawaban";
        $oldFiles = EmptyValue($row['surat_pengantar']) ? [] : [$row['surat_pengantar']];
        foreach ($oldFiles as $oldFile) {
            if (file_exists($this->surat_pengantar->oldPhysicalUploadPath() . $oldFile)) {
                @unlink($this->surat_pengantar->oldPhysicalUploadPath() . $oldFile);
            }
        }
        $this->skd_rqanunpert->OldUploadPath = "files/evaluasi/2022/pertanggungjawaban";
        $oldFiles = EmptyValue($row['skd_rqanunpert']) ? [] : [$row['skd_rqanunpert']];
        foreach ($oldFiles as $oldFile) {
            if (file_exists($this->skd_rqanunpert->oldPhysicalUploadPath() . $oldFile)) {
                @unlink($this->skd_rqanunpert->oldPhysicalUploadPath() . $oldFile);
            }
        }
        $this->rqanun_apbkpert->OldUploadPath = "files/evaluasi/2022/pertanggungjawaban";
        $oldFiles = EmptyValue($row['rqanun_apbkpert']) ? [] : [$row['rqanun_apbkpert']];
        foreach ($oldFiles as $oldFile) {
            if (file_exists($this->rqanun_apbkpert->oldPhysicalUploadPath() . $oldFile)) {
                @unlink($this->rqanun_apbkpert->oldPhysicalUploadPath() . $oldFile);
            }
        }
        $this->rperbup_apbkpert->OldUploadPath = "files/evaluasi/2022/pertanggungjawaban";
        $oldFiles = EmptyValue($row['rperbup_apbkpert']) ? [] : [$row['rperbup_apbkpert']];
        foreach ($oldFiles as $oldFile) {
            if (file_exists($this->rperbup_apbkpert->oldPhysicalUploadPath() . $oldFile)) {
                @unlink($this->rperbup_apbkpert->oldPhysicalUploadPath() . $oldFile);
            }
        }
        $this->pbkdd_apbkpert->OldUploadPath = "files/evaluasi/2022/pertanggungjawaban";
        $oldFiles = EmptyValue($row['pbkdd_apbkpert']) ? [] : [$row['pbkdd_apbkpert']];
        foreach ($oldFiles as $oldFile) {
            if (file_exists($this->pbkdd_apbkpert->oldPhysicalUploadPath() . $oldFile)) {
                @unlink($this->pbkdd_apbkpert->oldPhysicalUploadPath() . $oldFile);
            }
        }
        $this->risalah_sidang->OldUploadPath = "files/evaluasi/2022/pertanggungjawaban";
        $oldFiles = EmptyValue($row['risalah_sidang']) ? [] : [$row['risalah_sidang']];
        foreach ($oldFiles as $oldFile) {
            if (file_exists($this->risalah_sidang->oldPhysicalUploadPath() . $oldFile)) {
                @unlink($this->risalah_sidang->oldPhysicalUploadPath() . $oldFile);
            }
        }
        $this->absen_peserta->OldUploadPath = "files/evaluasi/2022/pertanggungjawaban";
        $oldFiles = EmptyValue($row['absen_peserta']) ? [] : [$row['absen_peserta']];
        foreach ($oldFiles as $oldFile) {
            if (file_exists($this->absen_peserta->oldPhysicalUploadPath() . $oldFile)) {
                @unlink($this->absen_peserta->oldPhysicalUploadPath() . $oldFile);
            }
        }
        $this->neraca->OldUploadPath = "files/evaluasi/2022/pertanggungjawaban";
        $oldFiles = EmptyValue($row['neraca']) ? [] : [$row['neraca']];
        foreach ($oldFiles as $oldFile) {
            if (file_exists($this->neraca->oldPhysicalUploadPath() . $oldFile)) {
                @unlink($this->neraca->oldPhysicalUploadPath() . $oldFile);
            }
        }
        $this->lra->OldUploadPath = "files/evaluasi/2022/pertanggungjawaban";
        $oldFiles = EmptyValue($row['lra']) ? [] : [$row['lra']];
        foreach ($oldFiles as $oldFile) {
            if (file_exists($this->lra->oldPhysicalUploadPath() . $oldFile)) {
                @unlink($this->lra->oldPhysicalUploadPath() . $oldFile);
            }
        }
        $this->calk->OldUploadPath = "files/evaluasi/2022/pertanggungjawaban";
        $oldFiles = EmptyValue($row['calk']) ? [] : [$row['calk']];
        foreach ($oldFiles as $oldFile) {
            if (file_exists($this->calk->oldPhysicalUploadPath() . $oldFile)) {
                @unlink($this->calk->oldPhysicalUploadPath() . $oldFile);
            }
        }
        $this->lo->OldUploadPath = "files/evaluasi/2022/pertanggungjawaban";
        $oldFiles = EmptyValue($row['lo']) ? [] : [$row['lo']];
        foreach ($oldFiles as $oldFile) {
            if (file_exists($this->lo->oldPhysicalUploadPath() . $oldFile)) {
                @unlink($this->lo->oldPhysicalUploadPath() . $oldFile);
            }
        }
        $this->lpe->OldUploadPath = "files/evaluasi/2022/pertanggungjawaban";
        $oldFiles = EmptyValue($row['lpe']) ? [] : [$row['lpe']];
        foreach ($oldFiles as $oldFile) {
            if (file_exists($this->lpe->oldPhysicalUploadPath() . $oldFile)) {
                @unlink($this->lpe->oldPhysicalUploadPath() . $oldFile);
            }
        }
        $this->lpsal->OldUploadPath = "files/evaluasi/2022/pertanggungjawaban";
        $oldFiles = EmptyValue($row['lpsal']) ? [] : [$row['lpsal']];
        foreach ($oldFiles as $oldFile) {
            if (file_exists($this->lpsal->oldPhysicalUploadPath() . $oldFile)) {
                @unlink($this->lpsal->oldPhysicalUploadPath() . $oldFile);
            }
        }
        $this->lak->OldUploadPath = "files/evaluasi/2022/pertanggungjawaban";
        $oldFiles = EmptyValue($row['lak']) ? [] : [$row['lak']];
        foreach ($oldFiles as $oldFile) {
            if (file_exists($this->lak->oldPhysicalUploadPath() . $oldFile)) {
                @unlink($this->lak->oldPhysicalUploadPath() . $oldFile);
            }
        }
        $this->laporan_pemeriksaan->OldUploadPath = "files/evaluasi/2022/pertanggungjawaban";
        $oldFiles = EmptyValue($row['laporan_pemeriksaan']) ? [] : [$row['laporan_pemeriksaan']];
        foreach ($oldFiles as $oldFile) {
            if (file_exists($this->laporan_pemeriksaan->oldPhysicalUploadPath() . $oldFile)) {
                @unlink($this->laporan_pemeriksaan->oldPhysicalUploadPath() . $oldFile);
            }
        }
    }

    // Record filter WHERE clause
    protected function sqlKeyFilter()
    {
        return "";
    }

    // Get Key
    public function getKey($current = false)
    {
        $keys = [];
        return implode(Config("COMPOSITE_KEY_SEPARATOR"), $keys);
    }

    // Set Key
    public function setKey($key, $current = false)
    {
        $this->OldKey = strval($key);
        $keys = explode(Config("COMPOSITE_KEY_SEPARATOR"), $this->OldKey);
        if (count($keys) == 0) {
        }
    }

    // Get record filter
    public function getRecordFilter($row = null)
    {
        $keyFilter = $this->sqlKeyFilter();
        return $keyFilter;
    }

    // Return page URL
    public function getReturnUrl()
    {
        $referUrl = ReferUrl();
        $referPageName = ReferPageName();
        $name = PROJECT_NAME . "_" . $this->TableVar . "_" . Config("TABLE_RETURN_URL");
        // Get referer URL automatically
        if ($referUrl != "" && $referPageName != CurrentPageName() && $referPageName != "login") { // Referer not same page or login page
            $_SESSION[$name] = $referUrl; // Save to Session
        }
        return $_SESSION[$name] ?? GetUrl("viewpertanggungjawaban2022evlist");
    }

    // Set return page URL
    public function setReturnUrl($v)
    {
        $_SESSION[PROJECT_NAME . "_" . $this->TableVar . "_" . Config("TABLE_RETURN_URL")] = $v;
    }

    // Get modal caption
    public function getModalCaption($pageName)
    {
        global $Language;
        if ($pageName == "viewpertanggungjawaban2022evview") {
            return $Language->phrase("View");
        } elseif ($pageName == "viewpertanggungjawaban2022evedit") {
            return $Language->phrase("Edit");
        } elseif ($pageName == "viewpertanggungjawaban2022evadd") {
            return $Language->phrase("Add");
        } else {
            return "";
        }
    }

    // API page name
    public function getApiPageName($action)
    {
        switch (strtolower($action)) {
            case Config("API_VIEW_ACTION"):
                return "ViewPertanggungjawaban2022EvView";
            case Config("API_ADD_ACTION"):
                return "ViewPertanggungjawaban2022EvAdd";
            case Config("API_EDIT_ACTION"):
                return "ViewPertanggungjawaban2022EvEdit";
            case Config("API_DELETE_ACTION"):
                return "ViewPertanggungjawaban2022EvDelete";
            case Config("API_LIST_ACTION"):
                return "ViewPertanggungjawaban2022EvList";
            default:
                return "";
        }
    }

    // List URL
    public function getListUrl()
    {
        return "viewpertanggungjawaban2022evlist";
    }

    // View URL
    public function getViewUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("viewpertanggungjawaban2022evview", $this->getUrlParm($parm));
        } else {
            $url = $this->keyUrl("viewpertanggungjawaban2022evview", $this->getUrlParm(Config("TABLE_SHOW_DETAIL") . "="));
        }
        return $this->addMasterUrl($url);
    }

    // Add URL
    public function getAddUrl($parm = "")
    {
        if ($parm != "") {
            $url = "viewpertanggungjawaban2022evadd?" . $this->getUrlParm($parm);
        } else {
            $url = "viewpertanggungjawaban2022evadd";
        }
        return $this->addMasterUrl($url);
    }

    // Edit URL
    public function getEditUrl($parm = "")
    {
        $url = $this->keyUrl("viewpertanggungjawaban2022evedit", $this->getUrlParm($parm));
        return $this->addMasterUrl($url);
    }

    // Inline edit URL
    public function getInlineEditUrl()
    {
        $url = $this->keyUrl(CurrentPageName(), $this->getUrlParm("action=edit"));
        return $this->addMasterUrl($url);
    }

    // Copy URL
    public function getCopyUrl($parm = "")
    {
        $url = $this->keyUrl("viewpertanggungjawaban2022evadd", $this->getUrlParm($parm));
        return $this->addMasterUrl($url);
    }

    // Inline copy URL
    public function getInlineCopyUrl()
    {
        $url = $this->keyUrl(CurrentPageName(), $this->getUrlParm("action=copy"));
        return $this->addMasterUrl($url);
    }

    // Delete URL
    public function getDeleteUrl()
    {
        return $this->keyUrl("viewpertanggungjawaban2022evdelete", $this->getUrlParm());
    }

    // Add master url
    public function addMasterUrl($url)
    {
        return $url;
    }

    public function keyToJson($htmlEncode = false)
    {
        $json = "";
        $json = "{" . $json . "}";
        if ($htmlEncode) {
            $json = HtmlEncode($json);
        }
        return $json;
    }

    // Add key value to URL
    public function keyUrl($url, $parm = "")
    {
        if ($parm != "") {
            $url .= "?" . $parm;
        }
        return $url;
    }

    // Render sort
    public function renderSort($fld)
    {
        $classId = $fld->TableVar . "_" . $fld->Param;
        $scriptId = str_replace("%id%", $classId, "tpc_%id%");
        $scriptStart = $this->UseCustomTemplate ? "<template id=\"" . $scriptId . "\">" : "";
        $scriptEnd = $this->UseCustomTemplate ? "</template>" : "";
        $jsSort = " class=\"ew-pointer\" onclick=\"ew.sort(event, '" . $this->sortUrl($fld) . "', 1);\"";
        if ($this->sortUrl($fld) == "") {
            $html = <<<NOSORTHTML
{$scriptStart}<div class="ew-table-header-caption">{$fld->caption()}</div>{$scriptEnd}
NOSORTHTML;
        } else {
            if ($fld->getSort() == "ASC") {
                $sortIcon = '<i class="fas fa-sort-up"></i>';
            } elseif ($fld->getSort() == "DESC") {
                $sortIcon = '<i class="fas fa-sort-down"></i>';
            } else {
                $sortIcon = '';
            }
            $html = <<<SORTHTML
{$scriptStart}<div{$jsSort}><div class="ew-table-header-btn"><span class="ew-table-header-caption">{$fld->caption()}</span><span class="ew-table-header-sort">{$sortIcon}</span></div></div>{$scriptEnd}
SORTHTML;
        }
        return $html;
    }

    // Sort URL
    public function sortUrl($fld)
    {
        if (
            $this->CurrentAction || $this->isExport() ||
            in_array($fld->Type, [128, 204, 205])
        ) { // Unsortable data type
                return "";
        } elseif ($fld->Sortable) {
            $urlParm = $this->getUrlParm("order=" . urlencode($fld->Name) . "&amp;ordertype=" . $fld->getNextSort());
            return $this->addMasterUrl(CurrentPageName() . "?" . $urlParm);
        } else {
            return "";
        }
    }

    // Get record keys from Post/Get/Session
    public function getRecordKeys()
    {
        $arKeys = [];
        $arKey = [];
        if (Param("key_m") !== null) {
            $arKeys = Param("key_m");
            $cnt = count($arKeys);
        } else {
            //return $arKeys; // Do not return yet, so the values will also be checked by the following code
        }
        // Check keys
        $ar = [];
        if (is_array($arKeys)) {
            foreach ($arKeys as $key) {
                $ar[] = $key;
            }
        }
        return $ar;
    }

    // Get filter from record keys
    public function getFilterFromRecordKeys($setCurrent = true)
    {
        $arKeys = $this->getRecordKeys();
        $keyFilter = "";
        foreach ($arKeys as $key) {
            if ($keyFilter != "") {
                $keyFilter .= " OR ";
            }
            $keyFilter .= "(" . $this->getRecordFilter() . ")";
        }
        return $keyFilter;
    }

    // Load recordset based on filter
    public function &loadRs($filter)
    {
        $sql = $this->getSql($filter); // Set up filter (WHERE Clause)
        $conn = $this->getConnection();
        $stmt = $conn->executeQuery($sql);
        return $stmt;
    }

    // Load row values from record
    public function loadListRowValues(&$rs)
    {
        if (is_array($rs)) {
            $row = $rs;
        } elseif ($rs && property_exists($rs, "fields")) { // Recordset
            $row = $rs->fields;
        } else {
            return;
        }
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
    }

    // Render list row values
    public function renderListRow()
    {
        global $Security, $CurrentLanguage, $Language;

        // Call Row Rendering event
        $this->rowRendering();

        // Common render codes

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

        // tanggal_upload
        $this->tanggal_upload->LinkCustomAttributes = "";
        $this->tanggal_upload->HrefValue = "";
        $this->tanggal_upload->TooltipValue = "";

        // tanggal_update
        $this->tanggal_update->LinkCustomAttributes = "";
        $this->tanggal_update->HrefValue = "";
        $this->tanggal_update->TooltipValue = "";

        // Call Row Rendered event
        $this->rowRendered();

        // Save data for Custom Template
        $this->Rows[] = $this->customTemplateFieldValues();
    }

    // Render edit row values
    public function renderEditRow()
    {
        global $Security, $CurrentLanguage, $Language;

        // Call Row Rendering event
        $this->rowRendering();

        // kd_satker
        $this->kd_satker->EditAttrs["class"] = "form-control";
        $this->kd_satker->EditCustomAttributes = "";
        $this->kd_satker->PlaceHolder = RemoveHtml($this->kd_satker->caption());

        // idd_tahapan
        $this->idd_tahapan->EditAttrs["class"] = "form-control";
        $this->idd_tahapan->EditCustomAttributes = "";
        $this->idd_tahapan->PlaceHolder = RemoveHtml($this->idd_tahapan->caption());

        // tahun_anggaran
        $this->tahun_anggaran->EditAttrs["class"] = "form-control";
        $this->tahun_anggaran->EditCustomAttributes = "";
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

        // status
        $this->status->EditAttrs["class"] = "form-control";
        $this->status->EditCustomAttributes = "";
        $this->status->EditValue = $this->status->options(true);
        $this->status->PlaceHolder = RemoveHtml($this->status->caption());

        // tanggal_upload
        $this->tanggal_upload->EditAttrs["class"] = "form-control";
        $this->tanggal_upload->EditCustomAttributes = "";

        // tanggal_update
        $this->tanggal_update->EditAttrs["class"] = "form-control";
        $this->tanggal_update->EditCustomAttributes = "";

        // Call Row Rendered event
        $this->rowRendered();
    }

    // Aggregate list row values
    public function aggregateListRowValues()
    {
    }

    // Aggregate list row (for rendering)
    public function aggregateListRow()
    {
        // Call Row Rendered event
        $this->rowRendered();
    }

    // Export data in HTML/CSV/Word/Excel/Email/PDF format
    public function exportDocument($doc, $recordset, $startRec = 1, $stopRec = 1, $exportPageType = "")
    {
        if (!$recordset || !$doc) {
            return;
        }
        if (!$doc->ExportCustom) {
            // Write header
            $doc->exportTableHeader();
            if ($doc->Horizontal) { // Horizontal format, write header
                $doc->beginExportRow();
                if ($exportPageType == "view") {
                    $doc->exportCaption($this->kd_satker);
                    $doc->exportCaption($this->idd_tahapan);
                    $doc->exportCaption($this->tahun_anggaran);
                    $doc->exportCaption($this->surat_pengantar);
                    $doc->exportCaption($this->skd_rqanunpert);
                    $doc->exportCaption($this->rqanun_apbkpert);
                    $doc->exportCaption($this->rperbup_apbkpert);
                    $doc->exportCaption($this->pbkdd_apbkpert);
                    $doc->exportCaption($this->risalah_sidang);
                    $doc->exportCaption($this->absen_peserta);
                    $doc->exportCaption($this->neraca);
                    $doc->exportCaption($this->lra);
                    $doc->exportCaption($this->calk);
                    $doc->exportCaption($this->lo);
                    $doc->exportCaption($this->lpe);
                    $doc->exportCaption($this->lpsal);
                    $doc->exportCaption($this->lak);
                    $doc->exportCaption($this->laporan_pemeriksaan);
                    $doc->exportCaption($this->status);
                    $doc->exportCaption($this->tanggal_upload);
                    $doc->exportCaption($this->tanggal_update);
                } else {
                    $doc->exportCaption($this->kd_satker);
                    $doc->exportCaption($this->idd_tahapan);
                    $doc->exportCaption($this->tahun_anggaran);
                    $doc->exportCaption($this->surat_pengantar);
                    $doc->exportCaption($this->skd_rqanunpert);
                    $doc->exportCaption($this->rqanun_apbkpert);
                    $doc->exportCaption($this->rperbup_apbkpert);
                    $doc->exportCaption($this->pbkdd_apbkpert);
                    $doc->exportCaption($this->risalah_sidang);
                    $doc->exportCaption($this->absen_peserta);
                    $doc->exportCaption($this->neraca);
                    $doc->exportCaption($this->lra);
                    $doc->exportCaption($this->calk);
                    $doc->exportCaption($this->lo);
                    $doc->exportCaption($this->lpe);
                    $doc->exportCaption($this->lpsal);
                    $doc->exportCaption($this->lak);
                    $doc->exportCaption($this->laporan_pemeriksaan);
                    $doc->exportCaption($this->status);
                    $doc->exportCaption($this->tanggal_upload);
                    $doc->exportCaption($this->tanggal_update);
                }
                $doc->endExportRow();
            }
        }

        // Move to first record
        $recCnt = $startRec - 1;
        $stopRec = ($stopRec > 0) ? $stopRec : PHP_INT_MAX;
        while (!$recordset->EOF && $recCnt < $stopRec) {
            $row = $recordset->fields;
            $recCnt++;
            if ($recCnt >= $startRec) {
                $rowCnt = $recCnt - $startRec + 1;

                // Page break
                if ($this->ExportPageBreakCount > 0) {
                    if ($rowCnt > 1 && ($rowCnt - 1) % $this->ExportPageBreakCount == 0) {
                        $doc->exportPageBreak();
                    }
                }
                $this->loadListRowValues($row);

                // Render row
                $this->RowType = ROWTYPE_VIEW; // Render view
                $this->resetAttributes();
                $this->renderListRow();
                if (!$doc->ExportCustom) {
                    $doc->beginExportRow($rowCnt); // Allow CSS styles if enabled
                    if ($exportPageType == "view") {
                        $doc->exportField($this->kd_satker);
                        $doc->exportField($this->idd_tahapan);
                        $doc->exportField($this->tahun_anggaran);
                        $doc->exportField($this->surat_pengantar);
                        $doc->exportField($this->skd_rqanunpert);
                        $doc->exportField($this->rqanun_apbkpert);
                        $doc->exportField($this->rperbup_apbkpert);
                        $doc->exportField($this->pbkdd_apbkpert);
                        $doc->exportField($this->risalah_sidang);
                        $doc->exportField($this->absen_peserta);
                        $doc->exportField($this->neraca);
                        $doc->exportField($this->lra);
                        $doc->exportField($this->calk);
                        $doc->exportField($this->lo);
                        $doc->exportField($this->lpe);
                        $doc->exportField($this->lpsal);
                        $doc->exportField($this->lak);
                        $doc->exportField($this->laporan_pemeriksaan);
                        $doc->exportField($this->status);
                        $doc->exportField($this->tanggal_upload);
                        $doc->exportField($this->tanggal_update);
                    } else {
                        $doc->exportField($this->kd_satker);
                        $doc->exportField($this->idd_tahapan);
                        $doc->exportField($this->tahun_anggaran);
                        $doc->exportField($this->surat_pengantar);
                        $doc->exportField($this->skd_rqanunpert);
                        $doc->exportField($this->rqanun_apbkpert);
                        $doc->exportField($this->rperbup_apbkpert);
                        $doc->exportField($this->pbkdd_apbkpert);
                        $doc->exportField($this->risalah_sidang);
                        $doc->exportField($this->absen_peserta);
                        $doc->exportField($this->neraca);
                        $doc->exportField($this->lra);
                        $doc->exportField($this->calk);
                        $doc->exportField($this->lo);
                        $doc->exportField($this->lpe);
                        $doc->exportField($this->lpsal);
                        $doc->exportField($this->lak);
                        $doc->exportField($this->laporan_pemeriksaan);
                        $doc->exportField($this->status);
                        $doc->exportField($this->tanggal_upload);
                        $doc->exportField($this->tanggal_update);
                    }
                    $doc->endExportRow($rowCnt);
                }
            }

            // Call Row Export server event
            if ($doc->ExportCustom) {
                $this->rowExport($row);
            }
            $recordset->moveNext();
        }
        if (!$doc->ExportCustom) {
            $doc->exportTableFooter();
        }
    }

    // Get file data
    public function getFileData($fldparm, $key, $resize, $width = 0, $height = 0, $plugins = [])
    {
        $width = ($width > 0) ? $width : Config("THUMBNAIL_DEFAULT_WIDTH");
        $height = ($height > 0) ? $height : Config("THUMBNAIL_DEFAULT_HEIGHT");

        // Set up field name / file name field / file type field
        $fldName = "";
        $fileNameFld = "";
        $fileTypeFld = "";
        if ($fldparm == 'surat_pengantar') {
            $fldName = "surat_pengantar";
            $fileNameFld = "surat_pengantar";
        } elseif ($fldparm == 'skd_rqanunpert') {
            $fldName = "skd_rqanunpert";
            $fileNameFld = "skd_rqanunpert";
        } elseif ($fldparm == 'rqanun_apbkpert') {
            $fldName = "rqanun_apbkpert";
            $fileNameFld = "rqanun_apbkpert";
        } elseif ($fldparm == 'rperbup_apbkpert') {
            $fldName = "rperbup_apbkpert";
            $fileNameFld = "rperbup_apbkpert";
        } elseif ($fldparm == 'pbkdd_apbkpert') {
            $fldName = "pbkdd_apbkpert";
            $fileNameFld = "pbkdd_apbkpert";
        } elseif ($fldparm == 'risalah_sidang') {
            $fldName = "risalah_sidang";
            $fileNameFld = "risalah_sidang";
        } elseif ($fldparm == 'absen_peserta') {
            $fldName = "absen_peserta";
            $fileNameFld = "absen_peserta";
        } elseif ($fldparm == 'neraca') {
            $fldName = "neraca";
            $fileNameFld = "neraca";
        } elseif ($fldparm == 'lra') {
            $fldName = "lra";
            $fileNameFld = "lra";
        } elseif ($fldparm == 'calk') {
            $fldName = "calk";
            $fileNameFld = "calk";
        } elseif ($fldparm == 'lo') {
            $fldName = "lo";
            $fileNameFld = "lo";
        } elseif ($fldparm == 'lpe') {
            $fldName = "lpe";
            $fileNameFld = "lpe";
        } elseif ($fldparm == 'lpsal') {
            $fldName = "lpsal";
            $fileNameFld = "lpsal";
        } elseif ($fldparm == 'lak') {
            $fldName = "lak";
            $fileNameFld = "lak";
        } elseif ($fldparm == 'laporan_pemeriksaan') {
            $fldName = "laporan_pemeriksaan";
            $fileNameFld = "laporan_pemeriksaan";
        } else {
            return false; // Incorrect field
        }

        // Set up key values
        $ar = explode(Config("COMPOSITE_KEY_SEPARATOR"), $key);
        if (count($ar) == 0) {
        } else {
            return false; // Incorrect key
        }

        // Set up filter (WHERE Clause)
        $filter = $this->getRecordFilter();
        $this->CurrentFilter = $filter;
        $sql = $this->getCurrentSql();
        $conn = $this->getConnection();
        $dbtype = GetConnectionType($this->Dbid);
        if ($row = $conn->fetchAssoc($sql)) {
            $val = $row[$fldName];
            if (!EmptyValue($val)) {
                $fld = $this->Fields[$fldName];

                // Binary data
                if ($fld->DataType == DATATYPE_BLOB) {
                    if ($dbtype != "MYSQL") {
                        if (is_resource($val) && get_resource_type($val) == "stream") { // Byte array
                            $val = stream_get_contents($val);
                        }
                    }
                    if ($resize) {
                        ResizeBinary($val, $width, $height, 100, $plugins);
                    }

                    // Write file type
                    if ($fileTypeFld != "" && !EmptyValue($row[$fileTypeFld])) {
                        AddHeader("Content-type", $row[$fileTypeFld]);
                    } else {
                        AddHeader("Content-type", ContentType($val));
                    }

                    // Write file name
                    $downloadPdf = !Config("EMBED_PDF") && Config("DOWNLOAD_PDF_FILE");
                    if ($fileNameFld != "" && !EmptyValue($row[$fileNameFld])) {
                        $fileName = $row[$fileNameFld];
                        $pathinfo = pathinfo($fileName);
                        $ext = strtolower(@$pathinfo["extension"]);
                        $isPdf = SameText($ext, "pdf");
                        if ($downloadPdf || !$isPdf) { // Skip header if not download PDF
                            AddHeader("Content-Disposition", "attachment; filename=\"" . $fileName . "\"");
                        }
                    } else {
                        $ext = ContentExtension($val);
                        $isPdf = SameText($ext, ".pdf");
                        if ($isPdf && $downloadPdf) { // Add header if download PDF
                            AddHeader("Content-Disposition", "attachment; filename=\"" . $fileName . "\"");
                        }
                    }

                    // Write file data
                    if (
                        StartsString("PK", $val) &&
                        ContainsString($val, "[Content_Types].xml") &&
                        ContainsString($val, "_rels") &&
                        ContainsString($val, "docProps")
                    ) { // Fix Office 2007 documents
                        if (!EndsString("\0\0\0", $val)) { // Not ends with 3 or 4 \0
                            $val .= "\0\0\0\0";
                        }
                    }

                    // Clear any debug message
                    if (ob_get_length()) {
                        ob_end_clean();
                    }

                    // Write binary data
                    Write($val);

                // Upload to folder
                } else {
                    if ($fld->UploadMultiple) {
                        $files = explode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $val);
                    } else {
                        $files = [$val];
                    }
                    $data = [];
                    $ar = [];
                    foreach ($files as $file) {
                        if (!EmptyValue($file)) {
                            if (Config("ENCRYPT_FILE_PATH")) {
                                $ar[$file] = FullUrl(GetApiUrl(Config("API_FILE_ACTION") .
                                    "/" . $this->TableVar . "/" . Encrypt($fld->physicalUploadPath() . $file)));
                            } else {
                                $ar[$file] = FullUrl($fld->hrefPath() . $file);
                            }
                        }
                    }
                    $data[$fld->Param] = $ar;
                    WriteJson($data);
                }
            }
            return true;
        }
        return false;
    }

    // Table level events

    // Recordset Selecting event
    public function recordsetSelecting(&$filter)
    {
        // Enter your code here
    }

    // Recordset Selected event
    public function recordsetSelected(&$rs)
    {
        //Log("Recordset Selected");
    }

    // Recordset Search Validated event
    public function recordsetSearchValidated()
    {
        // Example:
        //$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value
    }

    // Recordset Searching event
    public function recordsetSearching(&$filter)
    {
        // Enter your code here
    }

    // Row_Selecting event
    public function rowSelecting(&$filter)
    {
        // Enter your code here
    }

    // Row Selected event
    public function rowSelected(&$rs)
    {
        //Log("Row Selected");
    }

    // Row Inserting event
    public function rowInserting($rsold, &$rsnew)
    {
        // Enter your code here
        // To cancel, set return value to false
        return true;
    }

    // Row Inserted event
    public function rowInserted($rsold, &$rsnew)
    {
        //Log("Row Inserted");
    }

    // Row Updating event
    public function rowUpdating($rsold, &$rsnew)
    {
        // Enter your code here
        // To cancel, set return value to false
        return true;
    }

    // Row Updated event
    public function rowUpdated($rsold, &$rsnew)
    {
        //Log("Row Updated");
    }

    // Row Update Conflict event
    public function rowUpdateConflict($rsold, &$rsnew)
    {
        // Enter your code here
        // To ignore conflict, set return value to false
        return true;
    }

    // Grid Inserting event
    public function gridInserting()
    {
        // Enter your code here
        // To reject grid insert, set return value to false
        return true;
    }

    // Grid Inserted event
    public function gridInserted($rsnew)
    {
        //Log("Grid Inserted");
    }

    // Grid Updating event
    public function gridUpdating($rsold)
    {
        // Enter your code here
        // To reject grid update, set return value to false
        return true;
    }

    // Grid Updated event
    public function gridUpdated($rsold, $rsnew)
    {
        //Log("Grid Updated");
    }

    // Row Deleting event
    public function rowDeleting(&$rs)
    {
        // Enter your code here
        // To cancel, set return value to False
        return true;
    }

    // Row Deleted event
    public function rowDeleted(&$rs)
    {
        //Log("Row Deleted");
    }

    // Email Sending event
    public function emailSending($email, &$args)
    {
        //var_dump($email); var_dump($args); exit();
        return true;
    }

    // Lookup Selecting event
    public function lookupSelecting($fld, &$filter)
    {
        //var_dump($fld->Name, $fld->Lookup, $filter); // Uncomment to view the filter
        // Enter your code here
    }

    // Row Rendering event
    public function rowRendering()
    {
        // Enter your code here
    }

    // Row Rendered event
    public function rowRendered()
    {
        // To view properties of field class, use:
        //var_dump($this-><FieldName>);
    }

    // User ID Filtering event
    public function userIdFiltering(&$filter)
    {
        // Enter your code here
    }
}
