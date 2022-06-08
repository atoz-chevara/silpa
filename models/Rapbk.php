<?php

namespace PHPMaker2021\silpa;

use Doctrine\DBAL\ParameterType;

/**
 * Table class for rapbk
 */
class Rapbk extends DbTable
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
    public $idd_evaluasi;
    public $tanggal;
    public $kd_satker;
    public $idd_tahapan;
    public $tahun_anggaran;
    public $idd_wilayah;
    public $file_01;
    public $file_02;
    public $file_03;
    public $file_04;
    public $file_05;
    public $file_06;
    public $file_07;
    public $file_08;
    public $file_09;
    public $file_10;
    public $file_11;
    public $file_12;
    public $file_13;
    public $file_14;
    public $file_15;
    public $file_16;
    public $file_17;
    public $file_18;
    public $file_19;
    public $file_20;
    public $file_21;
    public $file_22;
    public $file_23;
    public $file_24;
    public $status;
    public $idd_user;

    // Page ID
    public $PageID = ""; // To be overridden by subclass

    // Constructor
    public function __construct()
    {
        global $Language, $CurrentLanguage;
        parent::__construct();

        // Language object
        $Language = Container("language");
        $this->TableVar = 'rapbk';
        $this->TableName = 'rapbk';
        $this->TableType = 'TABLE';

        // Update Table
        $this->UpdateTable = "`rapbk`";
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

        // idd_evaluasi
        $this->idd_evaluasi = new DbField('rapbk', 'rapbk', 'x_idd_evaluasi', 'idd_evaluasi', '`idd_evaluasi`', '`idd_evaluasi`', 3, 11, -1, false, '`idd_evaluasi`', false, false, false, 'FORMATTED TEXT', 'NO');
        $this->idd_evaluasi->IsAutoIncrement = true; // Autoincrement field
        $this->idd_evaluasi->IsPrimaryKey = true; // Primary key field
        $this->idd_evaluasi->Sortable = true; // Allow sort
        $this->idd_evaluasi->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->idd_evaluasi->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->idd_evaluasi->Param, "CustomMsg");
        $this->Fields['idd_evaluasi'] = &$this->idd_evaluasi;

        // tanggal
        $this->tanggal = new DbField('rapbk', 'rapbk', 'x_tanggal', 'tanggal', '`tanggal`', CastDateFieldForLike("`tanggal`", 0, "DB"), 133, 10, 0, false, '`tanggal`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->tanggal->Nullable = false; // NOT NULL field
        $this->tanggal->Required = true; // Required field
        $this->tanggal->Sortable = true; // Allow sort
        $this->tanggal->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_FORMAT"], $Language->phrase("IncorrectDate"));
        $this->tanggal->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->tanggal->Param, "CustomMsg");
        $this->Fields['tanggal'] = &$this->tanggal;

        // kd_satker
        $this->kd_satker = new DbField('rapbk', 'rapbk', 'x_kd_satker', 'kd_satker', '`kd_satker`', '`kd_satker`', 200, 100, -1, false, '`kd_satker`', false, false, false, 'FORMATTED TEXT', 'SELECT');
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
        $this->idd_tahapan = new DbField('rapbk', 'rapbk', 'x_idd_tahapan', 'idd_tahapan', '`idd_tahapan`', '`idd_tahapan`', 3, 100, -1, false, '`idd_tahapan`', false, false, false, 'FORMATTED TEXT', 'SELECT');
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
        $this->tahun_anggaran = new DbField('rapbk', 'rapbk', 'x_tahun_anggaran', 'tahun_anggaran', '`tahun_anggaran`', '`tahun_anggaran`', 200, 100, -1, false, '`tahun_anggaran`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->tahun_anggaran->Nullable = false; // NOT NULL field
        $this->tahun_anggaran->Required = true; // Required field
        $this->tahun_anggaran->Sortable = true; // Allow sort
        $this->tahun_anggaran->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->tahun_anggaran->Param, "CustomMsg");
        $this->Fields['tahun_anggaran'] = &$this->tahun_anggaran;

        // idd_wilayah
        $this->idd_wilayah = new DbField('rapbk', 'rapbk', 'x_idd_wilayah', 'idd_wilayah', '`idd_wilayah`', '`idd_wilayah`', 3, 100, -1, false, '`idd_wilayah`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->idd_wilayah->Nullable = false; // NOT NULL field
        $this->idd_wilayah->Required = true; // Required field
        $this->idd_wilayah->Sortable = true; // Allow sort
        $this->idd_wilayah->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->idd_wilayah->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->idd_wilayah->Param, "CustomMsg");
        $this->Fields['idd_wilayah'] = &$this->idd_wilayah;

        // file_01
        $this->file_01 = new DbField('rapbk', 'rapbk', 'x_file_01', 'file_01', '`file_01`', '`file_01`', 200, 200, -1, true, '`file_01`', false, false, false, 'FORMATTED TEXT', 'FILE');
        $this->file_01->Nullable = false; // NOT NULL field
        $this->file_01->Required = true; // Required field
        $this->file_01->Sortable = true; // Allow sort
        $this->file_01->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->file_01->Param, "CustomMsg");
        $this->Fields['file_01'] = &$this->file_01;

        // file_02
        $this->file_02 = new DbField('rapbk', 'rapbk', 'x_file_02', 'file_02', '`file_02`', '`file_02`', 200, 200, -1, true, '`file_02`', false, false, false, 'FORMATTED TEXT', 'FILE');
        $this->file_02->Nullable = false; // NOT NULL field
        $this->file_02->Required = true; // Required field
        $this->file_02->Sortable = true; // Allow sort
        $this->file_02->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->file_02->Param, "CustomMsg");
        $this->Fields['file_02'] = &$this->file_02;

        // file_03
        $this->file_03 = new DbField('rapbk', 'rapbk', 'x_file_03', 'file_03', '`file_03`', '`file_03`', 200, 200, -1, true, '`file_03`', false, false, false, 'FORMATTED TEXT', 'FILE');
        $this->file_03->Nullable = false; // NOT NULL field
        $this->file_03->Required = true; // Required field
        $this->file_03->Sortable = true; // Allow sort
        $this->file_03->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->file_03->Param, "CustomMsg");
        $this->Fields['file_03'] = &$this->file_03;

        // file_04
        $this->file_04 = new DbField('rapbk', 'rapbk', 'x_file_04', 'file_04', '`file_04`', '`file_04`', 200, 200, -1, true, '`file_04`', false, false, false, 'FORMATTED TEXT', 'FILE');
        $this->file_04->Nullable = false; // NOT NULL field
        $this->file_04->Required = true; // Required field
        $this->file_04->Sortable = true; // Allow sort
        $this->file_04->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->file_04->Param, "CustomMsg");
        $this->Fields['file_04'] = &$this->file_04;

        // file_05
        $this->file_05 = new DbField('rapbk', 'rapbk', 'x_file_05', 'file_05', '`file_05`', '`file_05`', 200, 200, -1, true, '`file_05`', false, false, false, 'FORMATTED TEXT', 'FILE');
        $this->file_05->Nullable = false; // NOT NULL field
        $this->file_05->Required = true; // Required field
        $this->file_05->Sortable = true; // Allow sort
        $this->file_05->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->file_05->Param, "CustomMsg");
        $this->Fields['file_05'] = &$this->file_05;

        // file_06
        $this->file_06 = new DbField('rapbk', 'rapbk', 'x_file_06', 'file_06', '`file_06`', '`file_06`', 200, 200, -1, true, '`file_06`', false, false, false, 'FORMATTED TEXT', 'FILE');
        $this->file_06->Nullable = false; // NOT NULL field
        $this->file_06->Required = true; // Required field
        $this->file_06->Sortable = true; // Allow sort
        $this->file_06->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->file_06->Param, "CustomMsg");
        $this->Fields['file_06'] = &$this->file_06;

        // file_07
        $this->file_07 = new DbField('rapbk', 'rapbk', 'x_file_07', 'file_07', '`file_07`', '`file_07`', 200, 200, -1, true, '`file_07`', false, false, false, 'FORMATTED TEXT', 'FILE');
        $this->file_07->Nullable = false; // NOT NULL field
        $this->file_07->Required = true; // Required field
        $this->file_07->Sortable = true; // Allow sort
        $this->file_07->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->file_07->Param, "CustomMsg");
        $this->Fields['file_07'] = &$this->file_07;

        // file_08
        $this->file_08 = new DbField('rapbk', 'rapbk', 'x_file_08', 'file_08', '`file_08`', '`file_08`', 200, 200, -1, true, '`file_08`', false, false, false, 'FORMATTED TEXT', 'FILE');
        $this->file_08->Nullable = false; // NOT NULL field
        $this->file_08->Required = true; // Required field
        $this->file_08->Sortable = true; // Allow sort
        $this->file_08->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->file_08->Param, "CustomMsg");
        $this->Fields['file_08'] = &$this->file_08;

        // file_09
        $this->file_09 = new DbField('rapbk', 'rapbk', 'x_file_09', 'file_09', '`file_09`', '`file_09`', 200, 200, -1, true, '`file_09`', false, false, false, 'FORMATTED TEXT', 'FILE');
        $this->file_09->Nullable = false; // NOT NULL field
        $this->file_09->Required = true; // Required field
        $this->file_09->Sortable = true; // Allow sort
        $this->file_09->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->file_09->Param, "CustomMsg");
        $this->Fields['file_09'] = &$this->file_09;

        // file_10
        $this->file_10 = new DbField('rapbk', 'rapbk', 'x_file_10', 'file_10', '`file_10`', '`file_10`', 200, 200, -1, true, '`file_10`', false, false, false, 'FORMATTED TEXT', 'FILE');
        $this->file_10->Nullable = false; // NOT NULL field
        $this->file_10->Required = true; // Required field
        $this->file_10->Sortable = true; // Allow sort
        $this->file_10->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->file_10->Param, "CustomMsg");
        $this->Fields['file_10'] = &$this->file_10;

        // file_11
        $this->file_11 = new DbField('rapbk', 'rapbk', 'x_file_11', 'file_11', '`file_11`', '`file_11`', 200, 200, -1, true, '`file_11`', false, false, false, 'FORMATTED TEXT', 'FILE');
        $this->file_11->Nullable = false; // NOT NULL field
        $this->file_11->Required = true; // Required field
        $this->file_11->Sortable = true; // Allow sort
        $this->file_11->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->file_11->Param, "CustomMsg");
        $this->Fields['file_11'] = &$this->file_11;

        // file_12
        $this->file_12 = new DbField('rapbk', 'rapbk', 'x_file_12', 'file_12', '`file_12`', '`file_12`', 200, 200, -1, true, '`file_12`', false, false, false, 'FORMATTED TEXT', 'FILE');
        $this->file_12->Nullable = false; // NOT NULL field
        $this->file_12->Required = true; // Required field
        $this->file_12->Sortable = true; // Allow sort
        $this->file_12->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->file_12->Param, "CustomMsg");
        $this->Fields['file_12'] = &$this->file_12;

        // file_13
        $this->file_13 = new DbField('rapbk', 'rapbk', 'x_file_13', 'file_13', '`file_13`', '`file_13`', 200, 200, -1, false, '`file_13`', false, false, false, 'FORMATTED TEXT', 'SELECT');
        $this->file_13->Nullable = false; // NOT NULL field
        $this->file_13->Required = true; // Required field
        $this->file_13->Sortable = true; // Allow sort
        $this->file_13->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->file_13->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        switch ($CurrentLanguage) {
            case "en":
                $this->file_13->Lookup = new Lookup('file_13', 'satkers', false, 'kode_pemda', ["nama_satker","","",""], [], [], [], [], [], [], '', '');
                break;
            default:
                $this->file_13->Lookup = new Lookup('file_13', 'satkers', false, 'kode_pemda', ["nama_satker","","",""], [], [], [], [], [], [], '', '');
                break;
        }
        $this->file_13->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->file_13->Param, "CustomMsg");
        $this->Fields['file_13'] = &$this->file_13;

        // file_14
        $this->file_14 = new DbField('rapbk', 'rapbk', 'x_file_14', 'file_14', '`file_14`', '`file_14`', 200, 200, -1, true, '`file_14`', false, false, false, 'FORMATTED TEXT', 'FILE');
        $this->file_14->Nullable = false; // NOT NULL field
        $this->file_14->Required = true; // Required field
        $this->file_14->Sortable = true; // Allow sort
        $this->file_14->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->file_14->Param, "CustomMsg");
        $this->Fields['file_14'] = &$this->file_14;

        // file_15
        $this->file_15 = new DbField('rapbk', 'rapbk', 'x_file_15', 'file_15', '`file_15`', '`file_15`', 200, 200, -1, true, '`file_15`', false, false, false, 'FORMATTED TEXT', 'FILE');
        $this->file_15->Nullable = false; // NOT NULL field
        $this->file_15->Required = true; // Required field
        $this->file_15->Sortable = true; // Allow sort
        $this->file_15->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->file_15->Param, "CustomMsg");
        $this->Fields['file_15'] = &$this->file_15;

        // file_16
        $this->file_16 = new DbField('rapbk', 'rapbk', 'x_file_16', 'file_16', '`file_16`', '`file_16`', 200, 200, -1, true, '`file_16`', false, false, false, 'FORMATTED TEXT', 'FILE');
        $this->file_16->Nullable = false; // NOT NULL field
        $this->file_16->Required = true; // Required field
        $this->file_16->Sortable = true; // Allow sort
        $this->file_16->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->file_16->Param, "CustomMsg");
        $this->Fields['file_16'] = &$this->file_16;

        // file_17
        $this->file_17 = new DbField('rapbk', 'rapbk', 'x_file_17', 'file_17', '`file_17`', '`file_17`', 200, 200, -1, true, '`file_17`', false, false, false, 'FORMATTED TEXT', 'FILE');
        $this->file_17->Nullable = false; // NOT NULL field
        $this->file_17->Required = true; // Required field
        $this->file_17->Sortable = true; // Allow sort
        $this->file_17->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->file_17->Param, "CustomMsg");
        $this->Fields['file_17'] = &$this->file_17;

        // file_18
        $this->file_18 = new DbField('rapbk', 'rapbk', 'x_file_18', 'file_18', '`file_18`', '`file_18`', 200, 200, -1, true, '`file_18`', false, false, false, 'FORMATTED TEXT', 'FILE');
        $this->file_18->Nullable = false; // NOT NULL field
        $this->file_18->Required = true; // Required field
        $this->file_18->Sortable = true; // Allow sort
        $this->file_18->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->file_18->Param, "CustomMsg");
        $this->Fields['file_18'] = &$this->file_18;

        // file_19
        $this->file_19 = new DbField('rapbk', 'rapbk', 'x_file_19', 'file_19', '`file_19`', '`file_19`', 200, 200, -1, true, '`file_19`', false, false, false, 'FORMATTED TEXT', 'FILE');
        $this->file_19->Nullable = false; // NOT NULL field
        $this->file_19->Required = true; // Required field
        $this->file_19->Sortable = true; // Allow sort
        $this->file_19->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->file_19->Param, "CustomMsg");
        $this->Fields['file_19'] = &$this->file_19;

        // file_20
        $this->file_20 = new DbField('rapbk', 'rapbk', 'x_file_20', 'file_20', '`file_20`', '`file_20`', 200, 200, -1, true, '`file_20`', false, false, false, 'FORMATTED TEXT', 'FILE');
        $this->file_20->Nullable = false; // NOT NULL field
        $this->file_20->Required = true; // Required field
        $this->file_20->Sortable = true; // Allow sort
        $this->file_20->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->file_20->Param, "CustomMsg");
        $this->Fields['file_20'] = &$this->file_20;

        // file_21
        $this->file_21 = new DbField('rapbk', 'rapbk', 'x_file_21', 'file_21', '`file_21`', '`file_21`', 200, 200, -1, true, '`file_21`', false, false, false, 'FORMATTED TEXT', 'FILE');
        $this->file_21->Nullable = false; // NOT NULL field
        $this->file_21->Required = true; // Required field
        $this->file_21->Sortable = true; // Allow sort
        $this->file_21->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->file_21->Param, "CustomMsg");
        $this->Fields['file_21'] = &$this->file_21;

        // file_22
        $this->file_22 = new DbField('rapbk', 'rapbk', 'x_file_22', 'file_22', '`file_22`', '`file_22`', 200, 200, -1, true, '`file_22`', false, false, false, 'FORMATTED TEXT', 'FILE');
        $this->file_22->Nullable = false; // NOT NULL field
        $this->file_22->Required = true; // Required field
        $this->file_22->Sortable = true; // Allow sort
        $this->file_22->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->file_22->Param, "CustomMsg");
        $this->Fields['file_22'] = &$this->file_22;

        // file_23
        $this->file_23 = new DbField('rapbk', 'rapbk', 'x_file_23', 'file_23', '`file_23`', '`file_23`', 200, 200, -1, true, '`file_23`', false, false, false, 'FORMATTED TEXT', 'FILE');
        $this->file_23->Nullable = false; // NOT NULL field
        $this->file_23->Required = true; // Required field
        $this->file_23->Sortable = true; // Allow sort
        $this->file_23->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->file_23->Param, "CustomMsg");
        $this->Fields['file_23'] = &$this->file_23;

        // file_24
        $this->file_24 = new DbField('rapbk', 'rapbk', 'x_file_24', 'file_24', '`file_24`', '`file_24`', 200, 200, -1, true, '`file_24`', false, false, false, 'FORMATTED TEXT', 'FILE');
        $this->file_24->Nullable = false; // NOT NULL field
        $this->file_24->Required = true; // Required field
        $this->file_24->Sortable = true; // Allow sort
        $this->file_24->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->file_24->Param, "CustomMsg");
        $this->Fields['file_24'] = &$this->file_24;

        // status
        $this->status = new DbField('rapbk', 'rapbk', 'x_status', 'status', '`status`', '`status`', 3, 11, -1, false, '`status`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->status->Nullable = false; // NOT NULL field
        $this->status->Required = true; // Required field
        $this->status->Sortable = true; // Allow sort
        $this->status->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->status->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->status->Param, "CustomMsg");
        $this->Fields['status'] = &$this->status;

        // idd_user
        $this->idd_user = new DbField('rapbk', 'rapbk', 'x_idd_user', 'idd_user', '`idd_user`', '`idd_user`', 3, 100, -1, false, '`idd_user`', false, false, false, 'FORMATTED TEXT', 'SELECT');
        $this->idd_user->Nullable = false; // NOT NULL field
        $this->idd_user->Required = true; // Required field
        $this->idd_user->Sortable = true; // Allow sort
        $this->idd_user->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->idd_user->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        switch ($CurrentLanguage) {
            case "en":
                $this->idd_user->Lookup = new Lookup('idd_user', 'users', false, 'idd_user', ["username","","",""], [], [], [], [], [], [], '', '');
                break;
            default:
                $this->idd_user->Lookup = new Lookup('idd_user', 'users', false, 'idd_user', ["username","","",""], [], [], [], [], [], [], '', '');
                break;
        }
        $this->idd_user->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->idd_user->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->idd_user->Param, "CustomMsg");
        $this->Fields['idd_user'] = &$this->idd_user;
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
        return ($this->SqlFrom != "") ? $this->SqlFrom : "`rapbk`";
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
            // Get insert id if necessary
            $this->idd_evaluasi->setDbValue($conn->lastInsertId());
            $rs['idd_evaluasi'] = $this->idd_evaluasi->DbValue;
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
            if (array_key_exists('idd_evaluasi', $rs)) {
                AddFilter($where, QuotedName('idd_evaluasi', $this->Dbid) . '=' . QuotedValue($rs['idd_evaluasi'], $this->idd_evaluasi->DataType, $this->Dbid));
            }
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
        $this->idd_evaluasi->DbValue = $row['idd_evaluasi'];
        $this->tanggal->DbValue = $row['tanggal'];
        $this->kd_satker->DbValue = $row['kd_satker'];
        $this->idd_tahapan->DbValue = $row['idd_tahapan'];
        $this->tahun_anggaran->DbValue = $row['tahun_anggaran'];
        $this->idd_wilayah->DbValue = $row['idd_wilayah'];
        $this->file_01->Upload->DbValue = $row['file_01'];
        $this->file_02->Upload->DbValue = $row['file_02'];
        $this->file_03->Upload->DbValue = $row['file_03'];
        $this->file_04->Upload->DbValue = $row['file_04'];
        $this->file_05->Upload->DbValue = $row['file_05'];
        $this->file_06->Upload->DbValue = $row['file_06'];
        $this->file_07->Upload->DbValue = $row['file_07'];
        $this->file_08->Upload->DbValue = $row['file_08'];
        $this->file_09->Upload->DbValue = $row['file_09'];
        $this->file_10->Upload->DbValue = $row['file_10'];
        $this->file_11->Upload->DbValue = $row['file_11'];
        $this->file_12->Upload->DbValue = $row['file_12'];
        $this->file_13->DbValue = $row['file_13'];
        $this->file_14->Upload->DbValue = $row['file_14'];
        $this->file_15->Upload->DbValue = $row['file_15'];
        $this->file_16->Upload->DbValue = $row['file_16'];
        $this->file_17->Upload->DbValue = $row['file_17'];
        $this->file_18->Upload->DbValue = $row['file_18'];
        $this->file_19->Upload->DbValue = $row['file_19'];
        $this->file_20->Upload->DbValue = $row['file_20'];
        $this->file_21->Upload->DbValue = $row['file_21'];
        $this->file_22->Upload->DbValue = $row['file_22'];
        $this->file_23->Upload->DbValue = $row['file_23'];
        $this->file_24->Upload->DbValue = $row['file_24'];
        $this->status->DbValue = $row['status'];
        $this->idd_user->DbValue = $row['idd_user'];
    }

    // Delete uploaded files
    public function deleteUploadedFiles($row)
    {
        $this->loadDbValues($row);
        $oldFiles = EmptyValue($row['file_01']) ? [] : [$row['file_01']];
        foreach ($oldFiles as $oldFile) {
            if (file_exists($this->file_01->oldPhysicalUploadPath() . $oldFile)) {
                @unlink($this->file_01->oldPhysicalUploadPath() . $oldFile);
            }
        }
        $oldFiles = EmptyValue($row['file_02']) ? [] : [$row['file_02']];
        foreach ($oldFiles as $oldFile) {
            if (file_exists($this->file_02->oldPhysicalUploadPath() . $oldFile)) {
                @unlink($this->file_02->oldPhysicalUploadPath() . $oldFile);
            }
        }
        $oldFiles = EmptyValue($row['file_03']) ? [] : [$row['file_03']];
        foreach ($oldFiles as $oldFile) {
            if (file_exists($this->file_03->oldPhysicalUploadPath() . $oldFile)) {
                @unlink($this->file_03->oldPhysicalUploadPath() . $oldFile);
            }
        }
        $oldFiles = EmptyValue($row['file_04']) ? [] : [$row['file_04']];
        foreach ($oldFiles as $oldFile) {
            if (file_exists($this->file_04->oldPhysicalUploadPath() . $oldFile)) {
                @unlink($this->file_04->oldPhysicalUploadPath() . $oldFile);
            }
        }
        $oldFiles = EmptyValue($row['file_05']) ? [] : [$row['file_05']];
        foreach ($oldFiles as $oldFile) {
            if (file_exists($this->file_05->oldPhysicalUploadPath() . $oldFile)) {
                @unlink($this->file_05->oldPhysicalUploadPath() . $oldFile);
            }
        }
        $oldFiles = EmptyValue($row['file_06']) ? [] : [$row['file_06']];
        foreach ($oldFiles as $oldFile) {
            if (file_exists($this->file_06->oldPhysicalUploadPath() . $oldFile)) {
                @unlink($this->file_06->oldPhysicalUploadPath() . $oldFile);
            }
        }
        $oldFiles = EmptyValue($row['file_07']) ? [] : [$row['file_07']];
        foreach ($oldFiles as $oldFile) {
            if (file_exists($this->file_07->oldPhysicalUploadPath() . $oldFile)) {
                @unlink($this->file_07->oldPhysicalUploadPath() . $oldFile);
            }
        }
        $oldFiles = EmptyValue($row['file_08']) ? [] : [$row['file_08']];
        foreach ($oldFiles as $oldFile) {
            if (file_exists($this->file_08->oldPhysicalUploadPath() . $oldFile)) {
                @unlink($this->file_08->oldPhysicalUploadPath() . $oldFile);
            }
        }
        $oldFiles = EmptyValue($row['file_09']) ? [] : [$row['file_09']];
        foreach ($oldFiles as $oldFile) {
            if (file_exists($this->file_09->oldPhysicalUploadPath() . $oldFile)) {
                @unlink($this->file_09->oldPhysicalUploadPath() . $oldFile);
            }
        }
        $oldFiles = EmptyValue($row['file_10']) ? [] : [$row['file_10']];
        foreach ($oldFiles as $oldFile) {
            if (file_exists($this->file_10->oldPhysicalUploadPath() . $oldFile)) {
                @unlink($this->file_10->oldPhysicalUploadPath() . $oldFile);
            }
        }
        $oldFiles = EmptyValue($row['file_11']) ? [] : [$row['file_11']];
        foreach ($oldFiles as $oldFile) {
            if (file_exists($this->file_11->oldPhysicalUploadPath() . $oldFile)) {
                @unlink($this->file_11->oldPhysicalUploadPath() . $oldFile);
            }
        }
        $oldFiles = EmptyValue($row['file_12']) ? [] : [$row['file_12']];
        foreach ($oldFiles as $oldFile) {
            if (file_exists($this->file_12->oldPhysicalUploadPath() . $oldFile)) {
                @unlink($this->file_12->oldPhysicalUploadPath() . $oldFile);
            }
        }
        $oldFiles = EmptyValue($row['file_14']) ? [] : [$row['file_14']];
        foreach ($oldFiles as $oldFile) {
            if (file_exists($this->file_14->oldPhysicalUploadPath() . $oldFile)) {
                @unlink($this->file_14->oldPhysicalUploadPath() . $oldFile);
            }
        }
        $oldFiles = EmptyValue($row['file_15']) ? [] : [$row['file_15']];
        foreach ($oldFiles as $oldFile) {
            if (file_exists($this->file_15->oldPhysicalUploadPath() . $oldFile)) {
                @unlink($this->file_15->oldPhysicalUploadPath() . $oldFile);
            }
        }
        $oldFiles = EmptyValue($row['file_16']) ? [] : [$row['file_16']];
        foreach ($oldFiles as $oldFile) {
            if (file_exists($this->file_16->oldPhysicalUploadPath() . $oldFile)) {
                @unlink($this->file_16->oldPhysicalUploadPath() . $oldFile);
            }
        }
        $oldFiles = EmptyValue($row['file_17']) ? [] : [$row['file_17']];
        foreach ($oldFiles as $oldFile) {
            if (file_exists($this->file_17->oldPhysicalUploadPath() . $oldFile)) {
                @unlink($this->file_17->oldPhysicalUploadPath() . $oldFile);
            }
        }
        $oldFiles = EmptyValue($row['file_18']) ? [] : [$row['file_18']];
        foreach ($oldFiles as $oldFile) {
            if (file_exists($this->file_18->oldPhysicalUploadPath() . $oldFile)) {
                @unlink($this->file_18->oldPhysicalUploadPath() . $oldFile);
            }
        }
        $oldFiles = EmptyValue($row['file_19']) ? [] : [$row['file_19']];
        foreach ($oldFiles as $oldFile) {
            if (file_exists($this->file_19->oldPhysicalUploadPath() . $oldFile)) {
                @unlink($this->file_19->oldPhysicalUploadPath() . $oldFile);
            }
        }
        $oldFiles = EmptyValue($row['file_20']) ? [] : [$row['file_20']];
        foreach ($oldFiles as $oldFile) {
            if (file_exists($this->file_20->oldPhysicalUploadPath() . $oldFile)) {
                @unlink($this->file_20->oldPhysicalUploadPath() . $oldFile);
            }
        }
        $oldFiles = EmptyValue($row['file_21']) ? [] : [$row['file_21']];
        foreach ($oldFiles as $oldFile) {
            if (file_exists($this->file_21->oldPhysicalUploadPath() . $oldFile)) {
                @unlink($this->file_21->oldPhysicalUploadPath() . $oldFile);
            }
        }
        $oldFiles = EmptyValue($row['file_22']) ? [] : [$row['file_22']];
        foreach ($oldFiles as $oldFile) {
            if (file_exists($this->file_22->oldPhysicalUploadPath() . $oldFile)) {
                @unlink($this->file_22->oldPhysicalUploadPath() . $oldFile);
            }
        }
        $oldFiles = EmptyValue($row['file_23']) ? [] : [$row['file_23']];
        foreach ($oldFiles as $oldFile) {
            if (file_exists($this->file_23->oldPhysicalUploadPath() . $oldFile)) {
                @unlink($this->file_23->oldPhysicalUploadPath() . $oldFile);
            }
        }
        $oldFiles = EmptyValue($row['file_24']) ? [] : [$row['file_24']];
        foreach ($oldFiles as $oldFile) {
            if (file_exists($this->file_24->oldPhysicalUploadPath() . $oldFile)) {
                @unlink($this->file_24->oldPhysicalUploadPath() . $oldFile);
            }
        }
    }

    // Record filter WHERE clause
    protected function sqlKeyFilter()
    {
        return "`idd_evaluasi` = @idd_evaluasi@";
    }

    // Get Key
    public function getKey($current = false)
    {
        $keys = [];
        $val = $current ? $this->idd_evaluasi->CurrentValue : $this->idd_evaluasi->OldValue;
        if (EmptyValue($val)) {
            return "";
        } else {
            $keys[] = $val;
        }
        return implode(Config("COMPOSITE_KEY_SEPARATOR"), $keys);
    }

    // Set Key
    public function setKey($key, $current = false)
    {
        $this->OldKey = strval($key);
        $keys = explode(Config("COMPOSITE_KEY_SEPARATOR"), $this->OldKey);
        if (count($keys) == 1) {
            if ($current) {
                $this->idd_evaluasi->CurrentValue = $keys[0];
            } else {
                $this->idd_evaluasi->OldValue = $keys[0];
            }
        }
    }

    // Get record filter
    public function getRecordFilter($row = null)
    {
        $keyFilter = $this->sqlKeyFilter();
        if (is_array($row)) {
            $val = array_key_exists('idd_evaluasi', $row) ? $row['idd_evaluasi'] : null;
        } else {
            $val = $this->idd_evaluasi->OldValue !== null ? $this->idd_evaluasi->OldValue : $this->idd_evaluasi->CurrentValue;
        }
        if (!is_numeric($val)) {
            return "0=1"; // Invalid key
        }
        if ($val === null) {
            return "0=1"; // Invalid key
        } else {
            $keyFilter = str_replace("@idd_evaluasi@", AdjustSql($val, $this->Dbid), $keyFilter); // Replace key value
        }
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
        return $_SESSION[$name] ?? GetUrl("rapbklist");
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
        if ($pageName == "rapbkview") {
            return $Language->phrase("View");
        } elseif ($pageName == "rapbkedit") {
            return $Language->phrase("Edit");
        } elseif ($pageName == "rapbkadd") {
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
                return "RapbkView";
            case Config("API_ADD_ACTION"):
                return "RapbkAdd";
            case Config("API_EDIT_ACTION"):
                return "RapbkEdit";
            case Config("API_DELETE_ACTION"):
                return "RapbkDelete";
            case Config("API_LIST_ACTION"):
                return "RapbkList";
            default:
                return "";
        }
    }

    // List URL
    public function getListUrl()
    {
        return "rapbklist";
    }

    // View URL
    public function getViewUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("rapbkview", $this->getUrlParm($parm));
        } else {
            $url = $this->keyUrl("rapbkview", $this->getUrlParm(Config("TABLE_SHOW_DETAIL") . "="));
        }
        return $this->addMasterUrl($url);
    }

    // Add URL
    public function getAddUrl($parm = "")
    {
        if ($parm != "") {
            $url = "rapbkadd?" . $this->getUrlParm($parm);
        } else {
            $url = "rapbkadd";
        }
        return $this->addMasterUrl($url);
    }

    // Edit URL
    public function getEditUrl($parm = "")
    {
        $url = $this->keyUrl("rapbkedit", $this->getUrlParm($parm));
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
        $url = $this->keyUrl("rapbkadd", $this->getUrlParm($parm));
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
        return $this->keyUrl("rapbkdelete", $this->getUrlParm());
    }

    // Add master url
    public function addMasterUrl($url)
    {
        return $url;
    }

    public function keyToJson($htmlEncode = false)
    {
        $json = "";
        $json .= "idd_evaluasi:" . JsonEncode($this->idd_evaluasi->CurrentValue, "number");
        $json = "{" . $json . "}";
        if ($htmlEncode) {
            $json = HtmlEncode($json);
        }
        return $json;
    }

    // Add key value to URL
    public function keyUrl($url, $parm = "")
    {
        if ($this->idd_evaluasi->CurrentValue !== null) {
            $url .= "/" . rawurlencode($this->idd_evaluasi->CurrentValue);
        } else {
            return "javascript:ew.alert(ew.language.phrase('InvalidRecord'));";
        }
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
            if (($keyValue = Param("idd_evaluasi") ?? Route("idd_evaluasi")) !== null) {
                $arKeys[] = $keyValue;
            } elseif (IsApi() && (($keyValue = Key(0) ?? Route(2)) !== null)) {
                $arKeys[] = $keyValue;
            } else {
                $arKeys = null; // Do not setup
            }

            //return $arKeys; // Do not return yet, so the values will also be checked by the following code
        }
        // Check keys
        $ar = [];
        if (is_array($arKeys)) {
            foreach ($arKeys as $key) {
                if (!is_numeric($key)) {
                    continue;
                }
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
            if ($setCurrent) {
                $this->idd_evaluasi->CurrentValue = $key;
            } else {
                $this->idd_evaluasi->OldValue = $key;
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
        $this->file_13->setDbValue($row['file_13']);
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

    // Render list row values
    public function renderListRow()
    {
        global $Security, $CurrentLanguage, $Language;

        // Call Row Rendering event
        $this->rowRendering();

        // Common render codes

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
        $this->tahun_anggaran->ViewValue = $this->tahun_anggaran->CurrentValue;
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
        $curVal = trim(strval($this->file_13->CurrentValue));
        if ($curVal != "") {
            $this->file_13->ViewValue = $this->file_13->lookupCacheOption($curVal);
            if ($this->file_13->ViewValue === null) { // Lookup from database
                $filterWrk = "`kode_pemda`" . SearchString("=", $curVal, DATATYPE_STRING, "");
                $sqlWrk = $this->file_13->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->file_13->Lookup->renderViewRow($rswrk[0]);
                    $this->file_13->ViewValue = $this->file_13->displayValue($arwrk);
                } else {
                    $this->file_13->ViewValue = $this->file_13->CurrentValue;
                }
            }
        } else {
            $this->file_13->ViewValue = null;
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
        $this->status->ViewValue = $this->status->CurrentValue;
        $this->status->ViewValue = FormatNumber($this->status->ViewValue, 0, -2, -2, -2);
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

        // idd_evaluasi
        $this->idd_evaluasi->EditAttrs["class"] = "form-control";
        $this->idd_evaluasi->EditCustomAttributes = "";
        $this->idd_evaluasi->EditValue = $this->idd_evaluasi->CurrentValue;
        $this->idd_evaluasi->ViewCustomAttributes = "";

        // tanggal
        $this->tanggal->EditAttrs["class"] = "form-control";
        $this->tanggal->EditCustomAttributes = "";
        $this->tanggal->EditValue = FormatDateTime($this->tanggal->CurrentValue, 8);
        $this->tanggal->PlaceHolder = RemoveHtml($this->tanggal->caption());

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
        if (!$this->tahun_anggaran->Raw) {
            $this->tahun_anggaran->CurrentValue = HtmlDecode($this->tahun_anggaran->CurrentValue);
        }
        $this->tahun_anggaran->EditValue = $this->tahun_anggaran->CurrentValue;
        $this->tahun_anggaran->PlaceHolder = RemoveHtml($this->tahun_anggaran->caption());

        // idd_wilayah
        $this->idd_wilayah->EditAttrs["class"] = "form-control";
        $this->idd_wilayah->EditCustomAttributes = "";
        $this->idd_wilayah->EditValue = $this->idd_wilayah->CurrentValue;
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

        // file_13
        $this->file_13->EditAttrs["class"] = "form-control";
        $this->file_13->EditCustomAttributes = "";
        $this->file_13->PlaceHolder = RemoveHtml($this->file_13->caption());

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

        // status
        $this->status->EditAttrs["class"] = "form-control";
        $this->status->EditCustomAttributes = "";
        $this->status->EditValue = $this->status->CurrentValue;
        $this->status->PlaceHolder = RemoveHtml($this->status->caption());

        // idd_user
        $this->idd_user->EditAttrs["class"] = "form-control";
        $this->idd_user->EditCustomAttributes = "";
        $this->idd_user->PlaceHolder = RemoveHtml($this->idd_user->caption());

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
                    $doc->exportCaption($this->idd_evaluasi);
                    $doc->exportCaption($this->tanggal);
                    $doc->exportCaption($this->kd_satker);
                    $doc->exportCaption($this->idd_tahapan);
                    $doc->exportCaption($this->tahun_anggaran);
                    $doc->exportCaption($this->idd_wilayah);
                    $doc->exportCaption($this->file_01);
                    $doc->exportCaption($this->file_02);
                    $doc->exportCaption($this->file_03);
                    $doc->exportCaption($this->file_04);
                    $doc->exportCaption($this->file_05);
                    $doc->exportCaption($this->file_06);
                    $doc->exportCaption($this->file_07);
                    $doc->exportCaption($this->file_08);
                    $doc->exportCaption($this->file_09);
                    $doc->exportCaption($this->file_10);
                    $doc->exportCaption($this->file_11);
                    $doc->exportCaption($this->file_12);
                    $doc->exportCaption($this->file_13);
                    $doc->exportCaption($this->file_14);
                    $doc->exportCaption($this->file_15);
                    $doc->exportCaption($this->file_16);
                    $doc->exportCaption($this->file_17);
                    $doc->exportCaption($this->file_18);
                    $doc->exportCaption($this->file_19);
                    $doc->exportCaption($this->file_20);
                    $doc->exportCaption($this->file_21);
                    $doc->exportCaption($this->file_22);
                    $doc->exportCaption($this->file_23);
                    $doc->exportCaption($this->file_24);
                    $doc->exportCaption($this->status);
                    $doc->exportCaption($this->idd_user);
                } else {
                    $doc->exportCaption($this->idd_evaluasi);
                    $doc->exportCaption($this->tanggal);
                    $doc->exportCaption($this->kd_satker);
                    $doc->exportCaption($this->idd_tahapan);
                    $doc->exportCaption($this->tahun_anggaran);
                    $doc->exportCaption($this->idd_wilayah);
                    $doc->exportCaption($this->file_01);
                    $doc->exportCaption($this->file_02);
                    $doc->exportCaption($this->file_03);
                    $doc->exportCaption($this->file_04);
                    $doc->exportCaption($this->file_05);
                    $doc->exportCaption($this->file_06);
                    $doc->exportCaption($this->file_07);
                    $doc->exportCaption($this->file_08);
                    $doc->exportCaption($this->file_09);
                    $doc->exportCaption($this->file_10);
                    $doc->exportCaption($this->file_11);
                    $doc->exportCaption($this->file_12);
                    $doc->exportCaption($this->file_13);
                    $doc->exportCaption($this->file_14);
                    $doc->exportCaption($this->file_15);
                    $doc->exportCaption($this->file_16);
                    $doc->exportCaption($this->file_17);
                    $doc->exportCaption($this->file_18);
                    $doc->exportCaption($this->file_19);
                    $doc->exportCaption($this->file_20);
                    $doc->exportCaption($this->file_21);
                    $doc->exportCaption($this->file_22);
                    $doc->exportCaption($this->file_23);
                    $doc->exportCaption($this->file_24);
                    $doc->exportCaption($this->status);
                    $doc->exportCaption($this->idd_user);
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
                        $doc->exportField($this->idd_evaluasi);
                        $doc->exportField($this->tanggal);
                        $doc->exportField($this->kd_satker);
                        $doc->exportField($this->idd_tahapan);
                        $doc->exportField($this->tahun_anggaran);
                        $doc->exportField($this->idd_wilayah);
                        $doc->exportField($this->file_01);
                        $doc->exportField($this->file_02);
                        $doc->exportField($this->file_03);
                        $doc->exportField($this->file_04);
                        $doc->exportField($this->file_05);
                        $doc->exportField($this->file_06);
                        $doc->exportField($this->file_07);
                        $doc->exportField($this->file_08);
                        $doc->exportField($this->file_09);
                        $doc->exportField($this->file_10);
                        $doc->exportField($this->file_11);
                        $doc->exportField($this->file_12);
                        $doc->exportField($this->file_13);
                        $doc->exportField($this->file_14);
                        $doc->exportField($this->file_15);
                        $doc->exportField($this->file_16);
                        $doc->exportField($this->file_17);
                        $doc->exportField($this->file_18);
                        $doc->exportField($this->file_19);
                        $doc->exportField($this->file_20);
                        $doc->exportField($this->file_21);
                        $doc->exportField($this->file_22);
                        $doc->exportField($this->file_23);
                        $doc->exportField($this->file_24);
                        $doc->exportField($this->status);
                        $doc->exportField($this->idd_user);
                    } else {
                        $doc->exportField($this->idd_evaluasi);
                        $doc->exportField($this->tanggal);
                        $doc->exportField($this->kd_satker);
                        $doc->exportField($this->idd_tahapan);
                        $doc->exportField($this->tahun_anggaran);
                        $doc->exportField($this->idd_wilayah);
                        $doc->exportField($this->file_01);
                        $doc->exportField($this->file_02);
                        $doc->exportField($this->file_03);
                        $doc->exportField($this->file_04);
                        $doc->exportField($this->file_05);
                        $doc->exportField($this->file_06);
                        $doc->exportField($this->file_07);
                        $doc->exportField($this->file_08);
                        $doc->exportField($this->file_09);
                        $doc->exportField($this->file_10);
                        $doc->exportField($this->file_11);
                        $doc->exportField($this->file_12);
                        $doc->exportField($this->file_13);
                        $doc->exportField($this->file_14);
                        $doc->exportField($this->file_15);
                        $doc->exportField($this->file_16);
                        $doc->exportField($this->file_17);
                        $doc->exportField($this->file_18);
                        $doc->exportField($this->file_19);
                        $doc->exportField($this->file_20);
                        $doc->exportField($this->file_21);
                        $doc->exportField($this->file_22);
                        $doc->exportField($this->file_23);
                        $doc->exportField($this->file_24);
                        $doc->exportField($this->status);
                        $doc->exportField($this->idd_user);
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
        if ($fldparm == 'file_01') {
            $fldName = "file_01";
            $fileNameFld = "file_01";
        } elseif ($fldparm == 'file_02') {
            $fldName = "file_02";
            $fileNameFld = "file_02";
        } elseif ($fldparm == 'file_03') {
            $fldName = "file_03";
            $fileNameFld = "file_03";
        } elseif ($fldparm == 'file_04') {
            $fldName = "file_04";
            $fileNameFld = "file_04";
        } elseif ($fldparm == 'file_05') {
            $fldName = "file_05";
            $fileNameFld = "file_05";
        } elseif ($fldparm == 'file_06') {
            $fldName = "file_06";
            $fileNameFld = "file_06";
        } elseif ($fldparm == 'file_07') {
            $fldName = "file_07";
            $fileNameFld = "file_07";
        } elseif ($fldparm == 'file_08') {
            $fldName = "file_08";
            $fileNameFld = "file_08";
        } elseif ($fldparm == 'file_09') {
            $fldName = "file_09";
            $fileNameFld = "file_09";
        } elseif ($fldparm == 'file_10') {
            $fldName = "file_10";
            $fileNameFld = "file_10";
        } elseif ($fldparm == 'file_11') {
            $fldName = "file_11";
            $fileNameFld = "file_11";
        } elseif ($fldparm == 'file_12') {
            $fldName = "file_12";
            $fileNameFld = "file_12";
        } elseif ($fldparm == 'file_14') {
            $fldName = "file_14";
            $fileNameFld = "file_14";
        } elseif ($fldparm == 'file_15') {
            $fldName = "file_15";
            $fileNameFld = "file_15";
        } elseif ($fldparm == 'file_16') {
            $fldName = "file_16";
            $fileNameFld = "file_16";
        } elseif ($fldparm == 'file_17') {
            $fldName = "file_17";
            $fileNameFld = "file_17";
        } elseif ($fldparm == 'file_18') {
            $fldName = "file_18";
            $fileNameFld = "file_18";
        } elseif ($fldparm == 'file_19') {
            $fldName = "file_19";
            $fileNameFld = "file_19";
        } elseif ($fldparm == 'file_20') {
            $fldName = "file_20";
            $fileNameFld = "file_20";
        } elseif ($fldparm == 'file_21') {
            $fldName = "file_21";
            $fileNameFld = "file_21";
        } elseif ($fldparm == 'file_22') {
            $fldName = "file_22";
            $fileNameFld = "file_22";
        } elseif ($fldparm == 'file_23') {
            $fldName = "file_23";
            $fileNameFld = "file_23";
        } elseif ($fldparm == 'file_24') {
            $fldName = "file_24";
            $fileNameFld = "file_24";
        } else {
            return false; // Incorrect field
        }

        // Set up key values
        $ar = explode(Config("COMPOSITE_KEY_SEPARATOR"), $key);
        if (count($ar) == 1) {
            $this->idd_evaluasi->CurrentValue = $ar[0];
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
