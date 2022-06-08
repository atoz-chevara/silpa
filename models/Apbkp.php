<?php

namespace PHPMaker2021\silpa;

use Doctrine\DBAL\ParameterType;

/**
 * Table class for apbkp
 */
class Apbkp extends DbTable
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
        $this->TableVar = 'apbkp';
        $this->TableName = 'apbkp';
        $this->TableType = 'TABLE';

        // Update Table
        $this->UpdateTable = "`apbkp`";
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
        $this->idd_evaluasi = new DbField('apbkp', 'apbkp', 'x_idd_evaluasi', 'idd_evaluasi', '`idd_evaluasi`', '`idd_evaluasi`', 3, 11, -1, false, '`idd_evaluasi`', false, false, false, 'FORMATTED TEXT', 'NO');
        $this->idd_evaluasi->IsAutoIncrement = true; // Autoincrement field
        $this->idd_evaluasi->IsPrimaryKey = true; // Primary key field
        $this->idd_evaluasi->Sortable = true; // Allow sort
        $this->idd_evaluasi->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->idd_evaluasi->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->idd_evaluasi->Param, "CustomMsg");
        $this->Fields['idd_evaluasi'] = &$this->idd_evaluasi;

        // tanggal
        $this->tanggal = new DbField('apbkp', 'apbkp', 'x_tanggal', 'tanggal', '`tanggal`', CastDateFieldForLike("`tanggal`", 0, "DB"), 133, 10, 0, false, '`tanggal`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->tanggal->Nullable = false; // NOT NULL field
        $this->tanggal->Required = true; // Required field
        $this->tanggal->Sortable = true; // Allow sort
        $this->tanggal->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_FORMAT"], $Language->phrase("IncorrectDate"));
        $this->tanggal->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->tanggal->Param, "CustomMsg");
        $this->Fields['tanggal'] = &$this->tanggal;

        // kd_satker
        $this->kd_satker = new DbField('apbkp', 'apbkp', 'x_kd_satker', 'kd_satker', '`kd_satker`', '`kd_satker`', 200, 100, -1, false, '`kd_satker`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->kd_satker->Nullable = false; // NOT NULL field
        $this->kd_satker->Required = true; // Required field
        $this->kd_satker->Sortable = true; // Allow sort
        $this->kd_satker->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->kd_satker->Param, "CustomMsg");
        $this->Fields['kd_satker'] = &$this->kd_satker;

        // idd_tahapan
        $this->idd_tahapan = new DbField('apbkp', 'apbkp', 'x_idd_tahapan', 'idd_tahapan', '`idd_tahapan`', '`idd_tahapan`', 3, 100, -1, false, '`idd_tahapan`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->idd_tahapan->Nullable = false; // NOT NULL field
        $this->idd_tahapan->Required = true; // Required field
        $this->idd_tahapan->Sortable = true; // Allow sort
        $this->idd_tahapan->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->idd_tahapan->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->idd_tahapan->Param, "CustomMsg");
        $this->Fields['idd_tahapan'] = &$this->idd_tahapan;

        // tahun_anggaran
        $this->tahun_anggaran = new DbField('apbkp', 'apbkp', 'x_tahun_anggaran', 'tahun_anggaran', '`tahun_anggaran`', '`tahun_anggaran`', 200, 100, -1, false, '`tahun_anggaran`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->tahun_anggaran->Nullable = false; // NOT NULL field
        $this->tahun_anggaran->Required = true; // Required field
        $this->tahun_anggaran->Sortable = true; // Allow sort
        $this->tahun_anggaran->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->tahun_anggaran->Param, "CustomMsg");
        $this->Fields['tahun_anggaran'] = &$this->tahun_anggaran;

        // idd_wilayah
        $this->idd_wilayah = new DbField('apbkp', 'apbkp', 'x_idd_wilayah', 'idd_wilayah', '`idd_wilayah`', '`idd_wilayah`', 3, 100, -1, false, '`idd_wilayah`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->idd_wilayah->Nullable = false; // NOT NULL field
        $this->idd_wilayah->Required = true; // Required field
        $this->idd_wilayah->Sortable = true; // Allow sort
        $this->idd_wilayah->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->idd_wilayah->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->idd_wilayah->Param, "CustomMsg");
        $this->Fields['idd_wilayah'] = &$this->idd_wilayah;

        // file_01
        $this->file_01 = new DbField('apbkp', 'apbkp', 'x_file_01', 'file_01', '`file_01`', '`file_01`', 200, 200, -1, false, '`file_01`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->file_01->Nullable = false; // NOT NULL field
        $this->file_01->Required = true; // Required field
        $this->file_01->Sortable = true; // Allow sort
        $this->file_01->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->file_01->Param, "CustomMsg");
        $this->Fields['file_01'] = &$this->file_01;

        // file_02
        $this->file_02 = new DbField('apbkp', 'apbkp', 'x_file_02', 'file_02', '`file_02`', '`file_02`', 200, 200, -1, false, '`file_02`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->file_02->Nullable = false; // NOT NULL field
        $this->file_02->Required = true; // Required field
        $this->file_02->Sortable = true; // Allow sort
        $this->file_02->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->file_02->Param, "CustomMsg");
        $this->Fields['file_02'] = &$this->file_02;

        // file_03
        $this->file_03 = new DbField('apbkp', 'apbkp', 'x_file_03', 'file_03', '`file_03`', '`file_03`', 200, 200, -1, false, '`file_03`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->file_03->Nullable = false; // NOT NULL field
        $this->file_03->Required = true; // Required field
        $this->file_03->Sortable = true; // Allow sort
        $this->file_03->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->file_03->Param, "CustomMsg");
        $this->Fields['file_03'] = &$this->file_03;

        // file_04
        $this->file_04 = new DbField('apbkp', 'apbkp', 'x_file_04', 'file_04', '`file_04`', '`file_04`', 200, 200, -1, false, '`file_04`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->file_04->Nullable = false; // NOT NULL field
        $this->file_04->Required = true; // Required field
        $this->file_04->Sortable = true; // Allow sort
        $this->file_04->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->file_04->Param, "CustomMsg");
        $this->Fields['file_04'] = &$this->file_04;

        // file_05
        $this->file_05 = new DbField('apbkp', 'apbkp', 'x_file_05', 'file_05', '`file_05`', '`file_05`', 200, 200, -1, false, '`file_05`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->file_05->Nullable = false; // NOT NULL field
        $this->file_05->Required = true; // Required field
        $this->file_05->Sortable = true; // Allow sort
        $this->file_05->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->file_05->Param, "CustomMsg");
        $this->Fields['file_05'] = &$this->file_05;

        // file_06
        $this->file_06 = new DbField('apbkp', 'apbkp', 'x_file_06', 'file_06', '`file_06`', '`file_06`', 200, 200, -1, false, '`file_06`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->file_06->Nullable = false; // NOT NULL field
        $this->file_06->Required = true; // Required field
        $this->file_06->Sortable = true; // Allow sort
        $this->file_06->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->file_06->Param, "CustomMsg");
        $this->Fields['file_06'] = &$this->file_06;

        // file_07
        $this->file_07 = new DbField('apbkp', 'apbkp', 'x_file_07', 'file_07', '`file_07`', '`file_07`', 200, 200, -1, false, '`file_07`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->file_07->Nullable = false; // NOT NULL field
        $this->file_07->Required = true; // Required field
        $this->file_07->Sortable = true; // Allow sort
        $this->file_07->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->file_07->Param, "CustomMsg");
        $this->Fields['file_07'] = &$this->file_07;

        // file_08
        $this->file_08 = new DbField('apbkp', 'apbkp', 'x_file_08', 'file_08', '`file_08`', '`file_08`', 200, 200, -1, false, '`file_08`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->file_08->Nullable = false; // NOT NULL field
        $this->file_08->Required = true; // Required field
        $this->file_08->Sortable = true; // Allow sort
        $this->file_08->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->file_08->Param, "CustomMsg");
        $this->Fields['file_08'] = &$this->file_08;

        // file_09
        $this->file_09 = new DbField('apbkp', 'apbkp', 'x_file_09', 'file_09', '`file_09`', '`file_09`', 200, 200, -1, false, '`file_09`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->file_09->Nullable = false; // NOT NULL field
        $this->file_09->Required = true; // Required field
        $this->file_09->Sortable = true; // Allow sort
        $this->file_09->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->file_09->Param, "CustomMsg");
        $this->Fields['file_09'] = &$this->file_09;

        // file_10
        $this->file_10 = new DbField('apbkp', 'apbkp', 'x_file_10', 'file_10', '`file_10`', '`file_10`', 200, 200, -1, false, '`file_10`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->file_10->Nullable = false; // NOT NULL field
        $this->file_10->Required = true; // Required field
        $this->file_10->Sortable = true; // Allow sort
        $this->file_10->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->file_10->Param, "CustomMsg");
        $this->Fields['file_10'] = &$this->file_10;

        // file_11
        $this->file_11 = new DbField('apbkp', 'apbkp', 'x_file_11', 'file_11', '`file_11`', '`file_11`', 200, 200, -1, false, '`file_11`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->file_11->Nullable = false; // NOT NULL field
        $this->file_11->Required = true; // Required field
        $this->file_11->Sortable = true; // Allow sort
        $this->file_11->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->file_11->Param, "CustomMsg");
        $this->Fields['file_11'] = &$this->file_11;

        // file_12
        $this->file_12 = new DbField('apbkp', 'apbkp', 'x_file_12', 'file_12', '`file_12`', '`file_12`', 200, 200, -1, false, '`file_12`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->file_12->Nullable = false; // NOT NULL field
        $this->file_12->Required = true; // Required field
        $this->file_12->Sortable = true; // Allow sort
        $this->file_12->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->file_12->Param, "CustomMsg");
        $this->Fields['file_12'] = &$this->file_12;

        // file_13
        $this->file_13 = new DbField('apbkp', 'apbkp', 'x_file_13', 'file_13', '`file_13`', '`file_13`', 200, 200, -1, false, '`file_13`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->file_13->Nullable = false; // NOT NULL field
        $this->file_13->Required = true; // Required field
        $this->file_13->Sortable = true; // Allow sort
        $this->file_13->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->file_13->Param, "CustomMsg");
        $this->Fields['file_13'] = &$this->file_13;

        // file_14
        $this->file_14 = new DbField('apbkp', 'apbkp', 'x_file_14', 'file_14', '`file_14`', '`file_14`', 200, 200, -1, false, '`file_14`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->file_14->Nullable = false; // NOT NULL field
        $this->file_14->Required = true; // Required field
        $this->file_14->Sortable = true; // Allow sort
        $this->file_14->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->file_14->Param, "CustomMsg");
        $this->Fields['file_14'] = &$this->file_14;

        // file_15
        $this->file_15 = new DbField('apbkp', 'apbkp', 'x_file_15', 'file_15', '`file_15`', '`file_15`', 200, 200, -1, false, '`file_15`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->file_15->Nullable = false; // NOT NULL field
        $this->file_15->Required = true; // Required field
        $this->file_15->Sortable = true; // Allow sort
        $this->file_15->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->file_15->Param, "CustomMsg");
        $this->Fields['file_15'] = &$this->file_15;

        // file_16
        $this->file_16 = new DbField('apbkp', 'apbkp', 'x_file_16', 'file_16', '`file_16`', '`file_16`', 200, 200, -1, false, '`file_16`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->file_16->Nullable = false; // NOT NULL field
        $this->file_16->Required = true; // Required field
        $this->file_16->Sortable = true; // Allow sort
        $this->file_16->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->file_16->Param, "CustomMsg");
        $this->Fields['file_16'] = &$this->file_16;

        // file_17
        $this->file_17 = new DbField('apbkp', 'apbkp', 'x_file_17', 'file_17', '`file_17`', '`file_17`', 200, 200, -1, false, '`file_17`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->file_17->Nullable = false; // NOT NULL field
        $this->file_17->Required = true; // Required field
        $this->file_17->Sortable = true; // Allow sort
        $this->file_17->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->file_17->Param, "CustomMsg");
        $this->Fields['file_17'] = &$this->file_17;

        // file_18
        $this->file_18 = new DbField('apbkp', 'apbkp', 'x_file_18', 'file_18', '`file_18`', '`file_18`', 200, 200, -1, false, '`file_18`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->file_18->Nullable = false; // NOT NULL field
        $this->file_18->Required = true; // Required field
        $this->file_18->Sortable = true; // Allow sort
        $this->file_18->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->file_18->Param, "CustomMsg");
        $this->Fields['file_18'] = &$this->file_18;

        // file_19
        $this->file_19 = new DbField('apbkp', 'apbkp', 'x_file_19', 'file_19', '`file_19`', '`file_19`', 200, 200, -1, false, '`file_19`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->file_19->Nullable = false; // NOT NULL field
        $this->file_19->Required = true; // Required field
        $this->file_19->Sortable = true; // Allow sort
        $this->file_19->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->file_19->Param, "CustomMsg");
        $this->Fields['file_19'] = &$this->file_19;

        // file_20
        $this->file_20 = new DbField('apbkp', 'apbkp', 'x_file_20', 'file_20', '`file_20`', '`file_20`', 200, 200, -1, false, '`file_20`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->file_20->Nullable = false; // NOT NULL field
        $this->file_20->Required = true; // Required field
        $this->file_20->Sortable = true; // Allow sort
        $this->file_20->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->file_20->Param, "CustomMsg");
        $this->Fields['file_20'] = &$this->file_20;

        // file_21
        $this->file_21 = new DbField('apbkp', 'apbkp', 'x_file_21', 'file_21', '`file_21`', '`file_21`', 200, 200, -1, false, '`file_21`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->file_21->Nullable = false; // NOT NULL field
        $this->file_21->Required = true; // Required field
        $this->file_21->Sortable = true; // Allow sort
        $this->file_21->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->file_21->Param, "CustomMsg");
        $this->Fields['file_21'] = &$this->file_21;

        // file_22
        $this->file_22 = new DbField('apbkp', 'apbkp', 'x_file_22', 'file_22', '`file_22`', '`file_22`', 200, 200, -1, false, '`file_22`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->file_22->Nullable = false; // NOT NULL field
        $this->file_22->Required = true; // Required field
        $this->file_22->Sortable = true; // Allow sort
        $this->file_22->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->file_22->Param, "CustomMsg");
        $this->Fields['file_22'] = &$this->file_22;

        // file_23
        $this->file_23 = new DbField('apbkp', 'apbkp', 'x_file_23', 'file_23', '`file_23`', '`file_23`', 200, 200, -1, false, '`file_23`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->file_23->Nullable = false; // NOT NULL field
        $this->file_23->Required = true; // Required field
        $this->file_23->Sortable = true; // Allow sort
        $this->file_23->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->file_23->Param, "CustomMsg");
        $this->Fields['file_23'] = &$this->file_23;

        // file_24
        $this->file_24 = new DbField('apbkp', 'apbkp', 'x_file_24', 'file_24', '`file_24`', '`file_24`', 200, 200, -1, false, '`file_24`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->file_24->Nullable = false; // NOT NULL field
        $this->file_24->Required = true; // Required field
        $this->file_24->Sortable = true; // Allow sort
        $this->file_24->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->file_24->Param, "CustomMsg");
        $this->Fields['file_24'] = &$this->file_24;

        // status
        $this->status = new DbField('apbkp', 'apbkp', 'x_status', 'status', '`status`', '`status`', 3, 11, -1, false, '`status`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->status->Nullable = false; // NOT NULL field
        $this->status->Required = true; // Required field
        $this->status->Sortable = true; // Allow sort
        $this->status->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->status->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->status->Param, "CustomMsg");
        $this->Fields['status'] = &$this->status;

        // idd_user
        $this->idd_user = new DbField('apbkp', 'apbkp', 'x_idd_user', 'idd_user', '`idd_user`', '`idd_user`', 3, 100, -1, false, '`idd_user`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->idd_user->Nullable = false; // NOT NULL field
        $this->idd_user->Required = true; // Required field
        $this->idd_user->Sortable = true; // Allow sort
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
        return ($this->SqlFrom != "") ? $this->SqlFrom : "`apbkp`";
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
        $this->file_01->DbValue = $row['file_01'];
        $this->file_02->DbValue = $row['file_02'];
        $this->file_03->DbValue = $row['file_03'];
        $this->file_04->DbValue = $row['file_04'];
        $this->file_05->DbValue = $row['file_05'];
        $this->file_06->DbValue = $row['file_06'];
        $this->file_07->DbValue = $row['file_07'];
        $this->file_08->DbValue = $row['file_08'];
        $this->file_09->DbValue = $row['file_09'];
        $this->file_10->DbValue = $row['file_10'];
        $this->file_11->DbValue = $row['file_11'];
        $this->file_12->DbValue = $row['file_12'];
        $this->file_13->DbValue = $row['file_13'];
        $this->file_14->DbValue = $row['file_14'];
        $this->file_15->DbValue = $row['file_15'];
        $this->file_16->DbValue = $row['file_16'];
        $this->file_17->DbValue = $row['file_17'];
        $this->file_18->DbValue = $row['file_18'];
        $this->file_19->DbValue = $row['file_19'];
        $this->file_20->DbValue = $row['file_20'];
        $this->file_21->DbValue = $row['file_21'];
        $this->file_22->DbValue = $row['file_22'];
        $this->file_23->DbValue = $row['file_23'];
        $this->file_24->DbValue = $row['file_24'];
        $this->status->DbValue = $row['status'];
        $this->idd_user->DbValue = $row['idd_user'];
    }

    // Delete uploaded files
    public function deleteUploadedFiles($row)
    {
        $this->loadDbValues($row);
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
        return $_SESSION[$name] ?? GetUrl("apbkplist");
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
        if ($pageName == "apbkpview") {
            return $Language->phrase("View");
        } elseif ($pageName == "apbkpedit") {
            return $Language->phrase("Edit");
        } elseif ($pageName == "apbkpadd") {
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
                return "ApbkpView";
            case Config("API_ADD_ACTION"):
                return "ApbkpAdd";
            case Config("API_EDIT_ACTION"):
                return "ApbkpEdit";
            case Config("API_DELETE_ACTION"):
                return "ApbkpDelete";
            case Config("API_LIST_ACTION"):
                return "ApbkpList";
            default:
                return "";
        }
    }

    // List URL
    public function getListUrl()
    {
        return "apbkplist";
    }

    // View URL
    public function getViewUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("apbkpview", $this->getUrlParm($parm));
        } else {
            $url = $this->keyUrl("apbkpview", $this->getUrlParm(Config("TABLE_SHOW_DETAIL") . "="));
        }
        return $this->addMasterUrl($url);
    }

    // Add URL
    public function getAddUrl($parm = "")
    {
        if ($parm != "") {
            $url = "apbkpadd?" . $this->getUrlParm($parm);
        } else {
            $url = "apbkpadd";
        }
        return $this->addMasterUrl($url);
    }

    // Edit URL
    public function getEditUrl($parm = "")
    {
        $url = $this->keyUrl("apbkpedit", $this->getUrlParm($parm));
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
        $url = $this->keyUrl("apbkpadd", $this->getUrlParm($parm));
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
        return $this->keyUrl("apbkpdelete", $this->getUrlParm());
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
        if (!$this->kd_satker->Raw) {
            $this->kd_satker->CurrentValue = HtmlDecode($this->kd_satker->CurrentValue);
        }
        $this->kd_satker->EditValue = $this->kd_satker->CurrentValue;
        $this->kd_satker->PlaceHolder = RemoveHtml($this->kd_satker->caption());

        // idd_tahapan
        $this->idd_tahapan->EditAttrs["class"] = "form-control";
        $this->idd_tahapan->EditCustomAttributes = "";
        $this->idd_tahapan->EditValue = $this->idd_tahapan->CurrentValue;
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
        if (!$this->file_01->Raw) {
            $this->file_01->CurrentValue = HtmlDecode($this->file_01->CurrentValue);
        }
        $this->file_01->EditValue = $this->file_01->CurrentValue;
        $this->file_01->PlaceHolder = RemoveHtml($this->file_01->caption());

        // file_02
        $this->file_02->EditAttrs["class"] = "form-control";
        $this->file_02->EditCustomAttributes = "";
        if (!$this->file_02->Raw) {
            $this->file_02->CurrentValue = HtmlDecode($this->file_02->CurrentValue);
        }
        $this->file_02->EditValue = $this->file_02->CurrentValue;
        $this->file_02->PlaceHolder = RemoveHtml($this->file_02->caption());

        // file_03
        $this->file_03->EditAttrs["class"] = "form-control";
        $this->file_03->EditCustomAttributes = "";
        if (!$this->file_03->Raw) {
            $this->file_03->CurrentValue = HtmlDecode($this->file_03->CurrentValue);
        }
        $this->file_03->EditValue = $this->file_03->CurrentValue;
        $this->file_03->PlaceHolder = RemoveHtml($this->file_03->caption());

        // file_04
        $this->file_04->EditAttrs["class"] = "form-control";
        $this->file_04->EditCustomAttributes = "";
        if (!$this->file_04->Raw) {
            $this->file_04->CurrentValue = HtmlDecode($this->file_04->CurrentValue);
        }
        $this->file_04->EditValue = $this->file_04->CurrentValue;
        $this->file_04->PlaceHolder = RemoveHtml($this->file_04->caption());

        // file_05
        $this->file_05->EditAttrs["class"] = "form-control";
        $this->file_05->EditCustomAttributes = "";
        if (!$this->file_05->Raw) {
            $this->file_05->CurrentValue = HtmlDecode($this->file_05->CurrentValue);
        }
        $this->file_05->EditValue = $this->file_05->CurrentValue;
        $this->file_05->PlaceHolder = RemoveHtml($this->file_05->caption());

        // file_06
        $this->file_06->EditAttrs["class"] = "form-control";
        $this->file_06->EditCustomAttributes = "";
        if (!$this->file_06->Raw) {
            $this->file_06->CurrentValue = HtmlDecode($this->file_06->CurrentValue);
        }
        $this->file_06->EditValue = $this->file_06->CurrentValue;
        $this->file_06->PlaceHolder = RemoveHtml($this->file_06->caption());

        // file_07
        $this->file_07->EditAttrs["class"] = "form-control";
        $this->file_07->EditCustomAttributes = "";
        if (!$this->file_07->Raw) {
            $this->file_07->CurrentValue = HtmlDecode($this->file_07->CurrentValue);
        }
        $this->file_07->EditValue = $this->file_07->CurrentValue;
        $this->file_07->PlaceHolder = RemoveHtml($this->file_07->caption());

        // file_08
        $this->file_08->EditAttrs["class"] = "form-control";
        $this->file_08->EditCustomAttributes = "";
        if (!$this->file_08->Raw) {
            $this->file_08->CurrentValue = HtmlDecode($this->file_08->CurrentValue);
        }
        $this->file_08->EditValue = $this->file_08->CurrentValue;
        $this->file_08->PlaceHolder = RemoveHtml($this->file_08->caption());

        // file_09
        $this->file_09->EditAttrs["class"] = "form-control";
        $this->file_09->EditCustomAttributes = "";
        if (!$this->file_09->Raw) {
            $this->file_09->CurrentValue = HtmlDecode($this->file_09->CurrentValue);
        }
        $this->file_09->EditValue = $this->file_09->CurrentValue;
        $this->file_09->PlaceHolder = RemoveHtml($this->file_09->caption());

        // file_10
        $this->file_10->EditAttrs["class"] = "form-control";
        $this->file_10->EditCustomAttributes = "";
        if (!$this->file_10->Raw) {
            $this->file_10->CurrentValue = HtmlDecode($this->file_10->CurrentValue);
        }
        $this->file_10->EditValue = $this->file_10->CurrentValue;
        $this->file_10->PlaceHolder = RemoveHtml($this->file_10->caption());

        // file_11
        $this->file_11->EditAttrs["class"] = "form-control";
        $this->file_11->EditCustomAttributes = "";
        if (!$this->file_11->Raw) {
            $this->file_11->CurrentValue = HtmlDecode($this->file_11->CurrentValue);
        }
        $this->file_11->EditValue = $this->file_11->CurrentValue;
        $this->file_11->PlaceHolder = RemoveHtml($this->file_11->caption());

        // file_12
        $this->file_12->EditAttrs["class"] = "form-control";
        $this->file_12->EditCustomAttributes = "";
        if (!$this->file_12->Raw) {
            $this->file_12->CurrentValue = HtmlDecode($this->file_12->CurrentValue);
        }
        $this->file_12->EditValue = $this->file_12->CurrentValue;
        $this->file_12->PlaceHolder = RemoveHtml($this->file_12->caption());

        // file_13
        $this->file_13->EditAttrs["class"] = "form-control";
        $this->file_13->EditCustomAttributes = "";
        if (!$this->file_13->Raw) {
            $this->file_13->CurrentValue = HtmlDecode($this->file_13->CurrentValue);
        }
        $this->file_13->EditValue = $this->file_13->CurrentValue;
        $this->file_13->PlaceHolder = RemoveHtml($this->file_13->caption());

        // file_14
        $this->file_14->EditAttrs["class"] = "form-control";
        $this->file_14->EditCustomAttributes = "";
        if (!$this->file_14->Raw) {
            $this->file_14->CurrentValue = HtmlDecode($this->file_14->CurrentValue);
        }
        $this->file_14->EditValue = $this->file_14->CurrentValue;
        $this->file_14->PlaceHolder = RemoveHtml($this->file_14->caption());

        // file_15
        $this->file_15->EditAttrs["class"] = "form-control";
        $this->file_15->EditCustomAttributes = "";
        if (!$this->file_15->Raw) {
            $this->file_15->CurrentValue = HtmlDecode($this->file_15->CurrentValue);
        }
        $this->file_15->EditValue = $this->file_15->CurrentValue;
        $this->file_15->PlaceHolder = RemoveHtml($this->file_15->caption());

        // file_16
        $this->file_16->EditAttrs["class"] = "form-control";
        $this->file_16->EditCustomAttributes = "";
        if (!$this->file_16->Raw) {
            $this->file_16->CurrentValue = HtmlDecode($this->file_16->CurrentValue);
        }
        $this->file_16->EditValue = $this->file_16->CurrentValue;
        $this->file_16->PlaceHolder = RemoveHtml($this->file_16->caption());

        // file_17
        $this->file_17->EditAttrs["class"] = "form-control";
        $this->file_17->EditCustomAttributes = "";
        if (!$this->file_17->Raw) {
            $this->file_17->CurrentValue = HtmlDecode($this->file_17->CurrentValue);
        }
        $this->file_17->EditValue = $this->file_17->CurrentValue;
        $this->file_17->PlaceHolder = RemoveHtml($this->file_17->caption());

        // file_18
        $this->file_18->EditAttrs["class"] = "form-control";
        $this->file_18->EditCustomAttributes = "";
        if (!$this->file_18->Raw) {
            $this->file_18->CurrentValue = HtmlDecode($this->file_18->CurrentValue);
        }
        $this->file_18->EditValue = $this->file_18->CurrentValue;
        $this->file_18->PlaceHolder = RemoveHtml($this->file_18->caption());

        // file_19
        $this->file_19->EditAttrs["class"] = "form-control";
        $this->file_19->EditCustomAttributes = "";
        if (!$this->file_19->Raw) {
            $this->file_19->CurrentValue = HtmlDecode($this->file_19->CurrentValue);
        }
        $this->file_19->EditValue = $this->file_19->CurrentValue;
        $this->file_19->PlaceHolder = RemoveHtml($this->file_19->caption());

        // file_20
        $this->file_20->EditAttrs["class"] = "form-control";
        $this->file_20->EditCustomAttributes = "";
        if (!$this->file_20->Raw) {
            $this->file_20->CurrentValue = HtmlDecode($this->file_20->CurrentValue);
        }
        $this->file_20->EditValue = $this->file_20->CurrentValue;
        $this->file_20->PlaceHolder = RemoveHtml($this->file_20->caption());

        // file_21
        $this->file_21->EditAttrs["class"] = "form-control";
        $this->file_21->EditCustomAttributes = "";
        if (!$this->file_21->Raw) {
            $this->file_21->CurrentValue = HtmlDecode($this->file_21->CurrentValue);
        }
        $this->file_21->EditValue = $this->file_21->CurrentValue;
        $this->file_21->PlaceHolder = RemoveHtml($this->file_21->caption());

        // file_22
        $this->file_22->EditAttrs["class"] = "form-control";
        $this->file_22->EditCustomAttributes = "";
        if (!$this->file_22->Raw) {
            $this->file_22->CurrentValue = HtmlDecode($this->file_22->CurrentValue);
        }
        $this->file_22->EditValue = $this->file_22->CurrentValue;
        $this->file_22->PlaceHolder = RemoveHtml($this->file_22->caption());

        // file_23
        $this->file_23->EditAttrs["class"] = "form-control";
        $this->file_23->EditCustomAttributes = "";
        if (!$this->file_23->Raw) {
            $this->file_23->CurrentValue = HtmlDecode($this->file_23->CurrentValue);
        }
        $this->file_23->EditValue = $this->file_23->CurrentValue;
        $this->file_23->PlaceHolder = RemoveHtml($this->file_23->caption());

        // file_24
        $this->file_24->EditAttrs["class"] = "form-control";
        $this->file_24->EditCustomAttributes = "";
        if (!$this->file_24->Raw) {
            $this->file_24->CurrentValue = HtmlDecode($this->file_24->CurrentValue);
        }
        $this->file_24->EditValue = $this->file_24->CurrentValue;
        $this->file_24->PlaceHolder = RemoveHtml($this->file_24->caption());

        // status
        $this->status->EditAttrs["class"] = "form-control";
        $this->status->EditCustomAttributes = "";
        $this->status->EditValue = $this->status->CurrentValue;
        $this->status->PlaceHolder = RemoveHtml($this->status->caption());

        // idd_user
        $this->idd_user->EditAttrs["class"] = "form-control";
        $this->idd_user->EditCustomAttributes = "";
        $this->idd_user->EditValue = $this->idd_user->CurrentValue;
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
        // No binary fields
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
