<?php

namespace PHPMaker2021\silpa;

use Doctrine\DBAL\ParameterType;

/**
 * Table class for evaluasi
 */
class Evaluasi extends DbTable
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
    public $idd_wilayah;
    public $kd_satker;
    public $idd_tahapan;
    public $tahun_anggaran;
    public $surat_pengantar;
    public $rpjmd;
    public $rkpk;
    public $skd_rkuappas;
    public $kua;
    public $ppas;
    public $skd_rqanun;
    public $nota_keuangan;
    public $pengantar_nota;
    public $risalah_sidang;
    public $bap_apbk;
    public $rq_apbk;
    public $rp_penjabaran;
    public $jadwal_proses;
    public $sinkron_kebijakan;
    public $konsistensi_program;
    public $alokasi_pendidikan;
    public $alokasi_kesehatan;
    public $alokasi_belanja;
    public $bak_kegiatan;
    public $softcopy_rka;
    public $otsus;
    public $qanun_perbup;
    public $tindak_apbkp;
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
        $this->TableVar = 'evaluasi';
        $this->TableName = 'evaluasi';
        $this->TableType = 'TABLE';

        // Update Table
        $this->UpdateTable = "`evaluasi`";
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
        $this->BasicSearch = new BasicSearch($this->TableVar);

        // idd_evaluasi
        $this->idd_evaluasi = new DbField('evaluasi', 'evaluasi', 'x_idd_evaluasi', 'idd_evaluasi', '`idd_evaluasi`', '`idd_evaluasi`', 3, 11, -1, false, '`idd_evaluasi`', false, false, false, 'FORMATTED TEXT', 'NO');
        $this->idd_evaluasi->IsAutoIncrement = true; // Autoincrement field
        $this->idd_evaluasi->IsPrimaryKey = true; // Primary key field
        $this->idd_evaluasi->Sortable = true; // Allow sort
        $this->idd_evaluasi->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->idd_evaluasi->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->idd_evaluasi->Param, "CustomMsg");
        $this->Fields['idd_evaluasi'] = &$this->idd_evaluasi;

        // tanggal
        $this->tanggal = new DbField('evaluasi', 'evaluasi', 'x_tanggal', 'tanggal', '`tanggal`', CastDateFieldForLike("`tanggal`", 0, "DB"), 133, 10, 0, false, '`tanggal`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->tanggal->Nullable = false; // NOT NULL field
        $this->tanggal->Required = true; // Required field
        $this->tanggal->Sortable = true; // Allow sort
        $this->tanggal->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_FORMAT"], $Language->phrase("IncorrectDate"));
        $this->tanggal->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->tanggal->Param, "CustomMsg");
        $this->Fields['tanggal'] = &$this->tanggal;

        // idd_wilayah
        $this->idd_wilayah = new DbField('evaluasi', 'evaluasi', 'x_idd_wilayah', 'idd_wilayah', '`idd_wilayah`', '`idd_wilayah`', 3, 100, -1, false, '`idd_wilayah`', false, false, false, 'FORMATTED TEXT', 'SELECT');
        $this->idd_wilayah->Nullable = false; // NOT NULL field
        $this->idd_wilayah->Required = true; // Required field
        $this->idd_wilayah->Sortable = true; // Allow sort
        $this->idd_wilayah->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->idd_wilayah->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        switch ($CurrentLanguage) {
            case "en":
                $this->idd_wilayah->Lookup = new Lookup('idd_wilayah', 'wilayah', false, 'idd_wilayah', ["nama_wilayah","","",""], [], [], [], [], [], [], '', '');
                break;
            default:
                $this->idd_wilayah->Lookup = new Lookup('idd_wilayah', 'wilayah', false, 'idd_wilayah', ["nama_wilayah","","",""], [], [], [], [], [], [], '', '');
                break;
        }
        $this->idd_wilayah->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->idd_wilayah->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->idd_wilayah->Param, "CustomMsg");
        $this->Fields['idd_wilayah'] = &$this->idd_wilayah;

        // kd_satker
        $this->kd_satker = new DbField('evaluasi', 'evaluasi', 'x_kd_satker', 'kd_satker', '`kd_satker`', '`kd_satker`', 200, 100, -1, false, '`kd_satker`', false, false, false, 'FORMATTED TEXT', 'SELECT');
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
        $this->idd_tahapan = new DbField('evaluasi', 'evaluasi', 'x_idd_tahapan', 'idd_tahapan', '`idd_tahapan`', '`idd_tahapan`', 3, 100, -1, false, '`idd_tahapan`', false, false, false, 'FORMATTED TEXT', 'SELECT');
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
        $this->tahun_anggaran = new DbField('evaluasi', 'evaluasi', 'x_tahun_anggaran', 'tahun_anggaran', '`tahun_anggaran`', '`tahun_anggaran`', 200, 100, -1, false, '`tahun_anggaran`', false, false, false, 'FORMATTED TEXT', 'SELECT');
        $this->tahun_anggaran->Nullable = false; // NOT NULL field
        $this->tahun_anggaran->Required = true; // Required field
        $this->tahun_anggaran->Sortable = true; // Allow sort
        $this->tahun_anggaran->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->tahun_anggaran->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        switch ($CurrentLanguage) {
            case "en":
                $this->tahun_anggaran->Lookup = new Lookup('tahun_anggaran', 'evaluasi', false, '', ["","","",""], [], [], [], [], [], [], '', '');
                break;
            default:
                $this->tahun_anggaran->Lookup = new Lookup('tahun_anggaran', 'evaluasi', false, '', ["","","",""], [], [], [], [], [], [], '', '');
                break;
        }
        $this->tahun_anggaran->OptionCount = 3;
        $this->tahun_anggaran->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->tahun_anggaran->Param, "CustomMsg");
        $this->Fields['tahun_anggaran'] = &$this->tahun_anggaran;

        // surat_pengantar
        $this->surat_pengantar = new DbField('evaluasi', 'evaluasi', 'x_surat_pengantar', 'surat_pengantar', '`surat_pengantar`', '`surat_pengantar`', 200, 200, -1, true, '`surat_pengantar`', false, false, false, 'FORMATTED TEXT', 'FILE');
        $this->surat_pengantar->Nullable = false; // NOT NULL field
        $this->surat_pengantar->Required = true; // Required field
        $this->surat_pengantar->Sortable = true; // Allow sort
        $this->surat_pengantar->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->surat_pengantar->Param, "CustomMsg");
        $this->Fields['surat_pengantar'] = &$this->surat_pengantar;

        // rpjmd
        $this->rpjmd = new DbField('evaluasi', 'evaluasi', 'x_rpjmd', 'rpjmd', '`rpjmd`', '`rpjmd`', 200, 200, -1, true, '`rpjmd`', false, false, false, 'FORMATTED TEXT', 'FILE');
        $this->rpjmd->Nullable = false; // NOT NULL field
        $this->rpjmd->Required = true; // Required field
        $this->rpjmd->Sortable = true; // Allow sort
        $this->rpjmd->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->rpjmd->Param, "CustomMsg");
        $this->Fields['rpjmd'] = &$this->rpjmd;

        // rkpk
        $this->rkpk = new DbField('evaluasi', 'evaluasi', 'x_rkpk', 'rkpk', '`rkpk`', '`rkpk`', 200, 200, -1, true, '`rkpk`', false, false, false, 'FORMATTED TEXT', 'FILE');
        $this->rkpk->Nullable = false; // NOT NULL field
        $this->rkpk->Required = true; // Required field
        $this->rkpk->Sortable = true; // Allow sort
        $this->rkpk->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->rkpk->Param, "CustomMsg");
        $this->Fields['rkpk'] = &$this->rkpk;

        // skd_rkuappas
        $this->skd_rkuappas = new DbField('evaluasi', 'evaluasi', 'x_skd_rkuappas', 'skd_rkuappas', '`skd_rkuappas`', '`skd_rkuappas`', 200, 200, -1, true, '`skd_rkuappas`', false, false, false, 'FORMATTED TEXT', 'FILE');
        $this->skd_rkuappas->Nullable = false; // NOT NULL field
        $this->skd_rkuappas->Required = true; // Required field
        $this->skd_rkuappas->Sortable = true; // Allow sort
        $this->skd_rkuappas->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->skd_rkuappas->Param, "CustomMsg");
        $this->Fields['skd_rkuappas'] = &$this->skd_rkuappas;

        // kua
        $this->kua = new DbField('evaluasi', 'evaluasi', 'x_kua', 'kua', '`kua`', '`kua`', 200, 200, -1, true, '`kua`', false, false, false, 'FORMATTED TEXT', 'FILE');
        $this->kua->Nullable = false; // NOT NULL field
        $this->kua->Required = true; // Required field
        $this->kua->Sortable = true; // Allow sort
        $this->kua->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->kua->Param, "CustomMsg");
        $this->Fields['kua'] = &$this->kua;

        // ppas
        $this->ppas = new DbField('evaluasi', 'evaluasi', 'x_ppas', 'ppas', '`ppas`', '`ppas`', 200, 200, -1, true, '`ppas`', false, false, false, 'FORMATTED TEXT', 'FILE');
        $this->ppas->Nullable = false; // NOT NULL field
        $this->ppas->Required = true; // Required field
        $this->ppas->Sortable = true; // Allow sort
        $this->ppas->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->ppas->Param, "CustomMsg");
        $this->Fields['ppas'] = &$this->ppas;

        // skd_rqanun
        $this->skd_rqanun = new DbField('evaluasi', 'evaluasi', 'x_skd_rqanun', 'skd_rqanun', '`skd_rqanun`', '`skd_rqanun`', 200, 200, -1, true, '`skd_rqanun`', false, false, false, 'FORMATTED TEXT', 'FILE');
        $this->skd_rqanun->Nullable = false; // NOT NULL field
        $this->skd_rqanun->Required = true; // Required field
        $this->skd_rqanun->Sortable = true; // Allow sort
        $this->skd_rqanun->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->skd_rqanun->Param, "CustomMsg");
        $this->Fields['skd_rqanun'] = &$this->skd_rqanun;

        // nota_keuangan
        $this->nota_keuangan = new DbField('evaluasi', 'evaluasi', 'x_nota_keuangan', 'nota_keuangan', '`nota_keuangan`', '`nota_keuangan`', 200, 200, -1, true, '`nota_keuangan`', false, false, false, 'FORMATTED TEXT', 'FILE');
        $this->nota_keuangan->Nullable = false; // NOT NULL field
        $this->nota_keuangan->Required = true; // Required field
        $this->nota_keuangan->Sortable = true; // Allow sort
        $this->nota_keuangan->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->nota_keuangan->Param, "CustomMsg");
        $this->Fields['nota_keuangan'] = &$this->nota_keuangan;

        // pengantar_nota
        $this->pengantar_nota = new DbField('evaluasi', 'evaluasi', 'x_pengantar_nota', 'pengantar_nota', '`pengantar_nota`', '`pengantar_nota`', 200, 200, -1, true, '`pengantar_nota`', false, false, false, 'FORMATTED TEXT', 'FILE');
        $this->pengantar_nota->Nullable = false; // NOT NULL field
        $this->pengantar_nota->Required = true; // Required field
        $this->pengantar_nota->Sortable = true; // Allow sort
        $this->pengantar_nota->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->pengantar_nota->Param, "CustomMsg");
        $this->Fields['pengantar_nota'] = &$this->pengantar_nota;

        // risalah_sidang
        $this->risalah_sidang = new DbField('evaluasi', 'evaluasi', 'x_risalah_sidang', 'risalah_sidang', '`risalah_sidang`', '`risalah_sidang`', 200, 200, -1, true, '`risalah_sidang`', false, false, false, 'FORMATTED TEXT', 'FILE');
        $this->risalah_sidang->Nullable = false; // NOT NULL field
        $this->risalah_sidang->Required = true; // Required field
        $this->risalah_sidang->Sortable = true; // Allow sort
        $this->risalah_sidang->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->risalah_sidang->Param, "CustomMsg");
        $this->Fields['risalah_sidang'] = &$this->risalah_sidang;

        // bap_apbk
        $this->bap_apbk = new DbField('evaluasi', 'evaluasi', 'x_bap_apbk', 'bap_apbk', '`bap_apbk`', '`bap_apbk`', 200, 200, -1, true, '`bap_apbk`', false, false, false, 'FORMATTED TEXT', 'FILE');
        $this->bap_apbk->Nullable = false; // NOT NULL field
        $this->bap_apbk->Required = true; // Required field
        $this->bap_apbk->Sortable = true; // Allow sort
        $this->bap_apbk->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->bap_apbk->Param, "CustomMsg");
        $this->Fields['bap_apbk'] = &$this->bap_apbk;

        // rq_apbk
        $this->rq_apbk = new DbField('evaluasi', 'evaluasi', 'x_rq_apbk', 'rq_apbk', '`rq_apbk`', '`rq_apbk`', 200, 200, -1, true, '`rq_apbk`', false, false, false, 'FORMATTED TEXT', 'FILE');
        $this->rq_apbk->Nullable = false; // NOT NULL field
        $this->rq_apbk->Required = true; // Required field
        $this->rq_apbk->Sortable = true; // Allow sort
        $this->rq_apbk->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->rq_apbk->Param, "CustomMsg");
        $this->Fields['rq_apbk'] = &$this->rq_apbk;

        // rp_penjabaran
        $this->rp_penjabaran = new DbField('evaluasi', 'evaluasi', 'x_rp_penjabaran', 'rp_penjabaran', '`rp_penjabaran`', '`rp_penjabaran`', 200, 200, -1, true, '`rp_penjabaran`', false, false, false, 'FORMATTED TEXT', 'FILE');
        $this->rp_penjabaran->Nullable = false; // NOT NULL field
        $this->rp_penjabaran->Required = true; // Required field
        $this->rp_penjabaran->Sortable = true; // Allow sort
        $this->rp_penjabaran->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->rp_penjabaran->Param, "CustomMsg");
        $this->Fields['rp_penjabaran'] = &$this->rp_penjabaran;

        // jadwal_proses
        $this->jadwal_proses = new DbField('evaluasi', 'evaluasi', 'x_jadwal_proses', 'jadwal_proses', '`jadwal_proses`', '`jadwal_proses`', 200, 200, -1, true, '`jadwal_proses`', false, false, false, 'FORMATTED TEXT', 'FILE');
        $this->jadwal_proses->Nullable = false; // NOT NULL field
        $this->jadwal_proses->Required = true; // Required field
        $this->jadwal_proses->Sortable = true; // Allow sort
        $this->jadwal_proses->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->jadwal_proses->Param, "CustomMsg");
        $this->Fields['jadwal_proses'] = &$this->jadwal_proses;

        // sinkron_kebijakan
        $this->sinkron_kebijakan = new DbField('evaluasi', 'evaluasi', 'x_sinkron_kebijakan', 'sinkron_kebijakan', '`sinkron_kebijakan`', '`sinkron_kebijakan`', 200, 200, -1, true, '`sinkron_kebijakan`', false, false, false, 'FORMATTED TEXT', 'FILE');
        $this->sinkron_kebijakan->Nullable = false; // NOT NULL field
        $this->sinkron_kebijakan->Required = true; // Required field
        $this->sinkron_kebijakan->Sortable = true; // Allow sort
        $this->sinkron_kebijakan->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->sinkron_kebijakan->Param, "CustomMsg");
        $this->Fields['sinkron_kebijakan'] = &$this->sinkron_kebijakan;

        // konsistensi_program
        $this->konsistensi_program = new DbField('evaluasi', 'evaluasi', 'x_konsistensi_program', 'konsistensi_program', '`konsistensi_program`', '`konsistensi_program`', 200, 200, -1, true, '`konsistensi_program`', false, false, false, 'FORMATTED TEXT', 'FILE');
        $this->konsistensi_program->Nullable = false; // NOT NULL field
        $this->konsistensi_program->Required = true; // Required field
        $this->konsistensi_program->Sortable = true; // Allow sort
        $this->konsistensi_program->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->konsistensi_program->Param, "CustomMsg");
        $this->Fields['konsistensi_program'] = &$this->konsistensi_program;

        // alokasi_pendidikan
        $this->alokasi_pendidikan = new DbField('evaluasi', 'evaluasi', 'x_alokasi_pendidikan', 'alokasi_pendidikan', '`alokasi_pendidikan`', '`alokasi_pendidikan`', 200, 200, -1, true, '`alokasi_pendidikan`', false, false, false, 'FORMATTED TEXT', 'FILE');
        $this->alokasi_pendidikan->Nullable = false; // NOT NULL field
        $this->alokasi_pendidikan->Required = true; // Required field
        $this->alokasi_pendidikan->Sortable = true; // Allow sort
        $this->alokasi_pendidikan->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->alokasi_pendidikan->Param, "CustomMsg");
        $this->Fields['alokasi_pendidikan'] = &$this->alokasi_pendidikan;

        // alokasi_kesehatan
        $this->alokasi_kesehatan = new DbField('evaluasi', 'evaluasi', 'x_alokasi_kesehatan', 'alokasi_kesehatan', '`alokasi_kesehatan`', '`alokasi_kesehatan`', 200, 200, -1, true, '`alokasi_kesehatan`', false, false, false, 'FORMATTED TEXT', 'FILE');
        $this->alokasi_kesehatan->Nullable = false; // NOT NULL field
        $this->alokasi_kesehatan->Required = true; // Required field
        $this->alokasi_kesehatan->Sortable = true; // Allow sort
        $this->alokasi_kesehatan->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->alokasi_kesehatan->Param, "CustomMsg");
        $this->Fields['alokasi_kesehatan'] = &$this->alokasi_kesehatan;

        // alokasi_belanja
        $this->alokasi_belanja = new DbField('evaluasi', 'evaluasi', 'x_alokasi_belanja', 'alokasi_belanja', '`alokasi_belanja`', '`alokasi_belanja`', 200, 200, -1, true, '`alokasi_belanja`', false, false, false, 'FORMATTED TEXT', 'FILE');
        $this->alokasi_belanja->Nullable = false; // NOT NULL field
        $this->alokasi_belanja->Required = true; // Required field
        $this->alokasi_belanja->Sortable = true; // Allow sort
        $this->alokasi_belanja->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->alokasi_belanja->Param, "CustomMsg");
        $this->Fields['alokasi_belanja'] = &$this->alokasi_belanja;

        // bak_kegiatan
        $this->bak_kegiatan = new DbField('evaluasi', 'evaluasi', 'x_bak_kegiatan', 'bak_kegiatan', '`bak_kegiatan`', '`bak_kegiatan`', 200, 200, -1, true, '`bak_kegiatan`', false, false, false, 'FORMATTED TEXT', 'FILE');
        $this->bak_kegiatan->Nullable = false; // NOT NULL field
        $this->bak_kegiatan->Required = true; // Required field
        $this->bak_kegiatan->Sortable = true; // Allow sort
        $this->bak_kegiatan->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->bak_kegiatan->Param, "CustomMsg");
        $this->Fields['bak_kegiatan'] = &$this->bak_kegiatan;

        // softcopy_rka
        $this->softcopy_rka = new DbField('evaluasi', 'evaluasi', 'x_softcopy_rka', 'softcopy_rka', '`softcopy_rka`', '`softcopy_rka`', 200, 200, -1, true, '`softcopy_rka`', false, false, false, 'FORMATTED TEXT', 'FILE');
        $this->softcopy_rka->Nullable = false; // NOT NULL field
        $this->softcopy_rka->Required = true; // Required field
        $this->softcopy_rka->Sortable = true; // Allow sort
        $this->softcopy_rka->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->softcopy_rka->Param, "CustomMsg");
        $this->Fields['softcopy_rka'] = &$this->softcopy_rka;

        // otsus
        $this->otsus = new DbField('evaluasi', 'evaluasi', 'x_otsus', 'otsus', '`otsus`', '`otsus`', 200, 200, -1, true, '`otsus`', false, false, false, 'FORMATTED TEXT', 'FILE');
        $this->otsus->Nullable = false; // NOT NULL field
        $this->otsus->Required = true; // Required field
        $this->otsus->Sortable = true; // Allow sort
        $this->otsus->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->otsus->Param, "CustomMsg");
        $this->Fields['otsus'] = &$this->otsus;

        // qanun_perbup
        $this->qanun_perbup = new DbField('evaluasi', 'evaluasi', 'x_qanun_perbup', 'qanun_perbup', '`qanun_perbup`', '`qanun_perbup`', 200, 200, -1, true, '`qanun_perbup`', false, false, false, 'FORMATTED TEXT', 'FILE');
        $this->qanun_perbup->Nullable = false; // NOT NULL field
        $this->qanun_perbup->Required = true; // Required field
        $this->qanun_perbup->Sortable = true; // Allow sort
        $this->qanun_perbup->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->qanun_perbup->Param, "CustomMsg");
        $this->Fields['qanun_perbup'] = &$this->qanun_perbup;

        // tindak_apbkp
        $this->tindak_apbkp = new DbField('evaluasi', 'evaluasi', 'x_tindak_apbkp', 'tindak_apbkp', '`tindak_apbkp`', '`tindak_apbkp`', 200, 200, -1, true, '`tindak_apbkp`', false, false, false, 'FORMATTED TEXT', 'FILE');
        $this->tindak_apbkp->Nullable = false; // NOT NULL field
        $this->tindak_apbkp->Required = true; // Required field
        $this->tindak_apbkp->Sortable = true; // Allow sort
        $this->tindak_apbkp->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->tindak_apbkp->Param, "CustomMsg");
        $this->Fields['tindak_apbkp'] = &$this->tindak_apbkp;

        // status
        $this->status = new DbField('evaluasi', 'evaluasi', 'x_status', 'status', '`status`', '`status`', 3, 11, -1, false, '`status`', false, false, false, 'FORMATTED TEXT', 'SELECT');
        $this->status->Nullable = false; // NOT NULL field
        $this->status->Required = true; // Required field
        $this->status->Sortable = true; // Allow sort
        $this->status->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->status->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        switch ($CurrentLanguage) {
            case "en":
                $this->status->Lookup = new Lookup('status', 'evaluasi', false, '', ["","","",""], [], [], [], [], [], [], '', '');
                break;
            default:
                $this->status->Lookup = new Lookup('status', 'evaluasi', false, '', ["","","",""], [], [], [], [], [], [], '', '');
                break;
        }
        $this->status->OptionCount = 3;
        $this->status->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->status->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->status->Param, "CustomMsg");
        $this->Fields['status'] = &$this->status;

        // idd_user
        $this->idd_user = new DbField('evaluasi', 'evaluasi', 'x_idd_user', 'idd_user', '`idd_user`', '`idd_user`', 3, 100, -1, false, '`idd_user`', false, false, false, 'FORMATTED TEXT', 'SELECT');
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
        return ($this->SqlFrom != "") ? $this->SqlFrom : "`evaluasi`";
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
        global $Security;
        // Add User ID filter
        if ($Security->currentUserID() != "" && !$Security->isAdmin()) { // Non system admin
            $filter = $this->addUserIDFilter($filter);
        }
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
        $this->idd_wilayah->DbValue = $row['idd_wilayah'];
        $this->kd_satker->DbValue = $row['kd_satker'];
        $this->idd_tahapan->DbValue = $row['idd_tahapan'];
        $this->tahun_anggaran->DbValue = $row['tahun_anggaran'];
        $this->surat_pengantar->Upload->DbValue = $row['surat_pengantar'];
        $this->rpjmd->Upload->DbValue = $row['rpjmd'];
        $this->rkpk->Upload->DbValue = $row['rkpk'];
        $this->skd_rkuappas->Upload->DbValue = $row['skd_rkuappas'];
        $this->kua->Upload->DbValue = $row['kua'];
        $this->ppas->Upload->DbValue = $row['ppas'];
        $this->skd_rqanun->Upload->DbValue = $row['skd_rqanun'];
        $this->nota_keuangan->Upload->DbValue = $row['nota_keuangan'];
        $this->pengantar_nota->Upload->DbValue = $row['pengantar_nota'];
        $this->risalah_sidang->Upload->DbValue = $row['risalah_sidang'];
        $this->bap_apbk->Upload->DbValue = $row['bap_apbk'];
        $this->rq_apbk->Upload->DbValue = $row['rq_apbk'];
        $this->rp_penjabaran->Upload->DbValue = $row['rp_penjabaran'];
        $this->jadwal_proses->Upload->DbValue = $row['jadwal_proses'];
        $this->sinkron_kebijakan->Upload->DbValue = $row['sinkron_kebijakan'];
        $this->konsistensi_program->Upload->DbValue = $row['konsistensi_program'];
        $this->alokasi_pendidikan->Upload->DbValue = $row['alokasi_pendidikan'];
        $this->alokasi_kesehatan->Upload->DbValue = $row['alokasi_kesehatan'];
        $this->alokasi_belanja->Upload->DbValue = $row['alokasi_belanja'];
        $this->bak_kegiatan->Upload->DbValue = $row['bak_kegiatan'];
        $this->softcopy_rka->Upload->DbValue = $row['softcopy_rka'];
        $this->otsus->Upload->DbValue = $row['otsus'];
        $this->qanun_perbup->Upload->DbValue = $row['qanun_perbup'];
        $this->tindak_apbkp->Upload->DbValue = $row['tindak_apbkp'];
        $this->status->DbValue = $row['status'];
        $this->idd_user->DbValue = $row['idd_user'];
    }

    // Delete uploaded files
    public function deleteUploadedFiles($row)
    {
        $this->loadDbValues($row);
        $oldFiles = EmptyValue($row['surat_pengantar']) ? [] : [$row['surat_pengantar']];
        foreach ($oldFiles as $oldFile) {
            if (file_exists($this->surat_pengantar->oldPhysicalUploadPath() . $oldFile)) {
                @unlink($this->surat_pengantar->oldPhysicalUploadPath() . $oldFile);
            }
        }
        $oldFiles = EmptyValue($row['rpjmd']) ? [] : [$row['rpjmd']];
        foreach ($oldFiles as $oldFile) {
            if (file_exists($this->rpjmd->oldPhysicalUploadPath() . $oldFile)) {
                @unlink($this->rpjmd->oldPhysicalUploadPath() . $oldFile);
            }
        }
        $oldFiles = EmptyValue($row['rkpk']) ? [] : [$row['rkpk']];
        foreach ($oldFiles as $oldFile) {
            if (file_exists($this->rkpk->oldPhysicalUploadPath() . $oldFile)) {
                @unlink($this->rkpk->oldPhysicalUploadPath() . $oldFile);
            }
        }
        $oldFiles = EmptyValue($row['skd_rkuappas']) ? [] : [$row['skd_rkuappas']];
        foreach ($oldFiles as $oldFile) {
            if (file_exists($this->skd_rkuappas->oldPhysicalUploadPath() . $oldFile)) {
                @unlink($this->skd_rkuappas->oldPhysicalUploadPath() . $oldFile);
            }
        }
        $oldFiles = EmptyValue($row['kua']) ? [] : [$row['kua']];
        foreach ($oldFiles as $oldFile) {
            if (file_exists($this->kua->oldPhysicalUploadPath() . $oldFile)) {
                @unlink($this->kua->oldPhysicalUploadPath() . $oldFile);
            }
        }
        $oldFiles = EmptyValue($row['ppas']) ? [] : [$row['ppas']];
        foreach ($oldFiles as $oldFile) {
            if (file_exists($this->ppas->oldPhysicalUploadPath() . $oldFile)) {
                @unlink($this->ppas->oldPhysicalUploadPath() . $oldFile);
            }
        }
        $oldFiles = EmptyValue($row['skd_rqanun']) ? [] : [$row['skd_rqanun']];
        foreach ($oldFiles as $oldFile) {
            if (file_exists($this->skd_rqanun->oldPhysicalUploadPath() . $oldFile)) {
                @unlink($this->skd_rqanun->oldPhysicalUploadPath() . $oldFile);
            }
        }
        $oldFiles = EmptyValue($row['nota_keuangan']) ? [] : [$row['nota_keuangan']];
        foreach ($oldFiles as $oldFile) {
            if (file_exists($this->nota_keuangan->oldPhysicalUploadPath() . $oldFile)) {
                @unlink($this->nota_keuangan->oldPhysicalUploadPath() . $oldFile);
            }
        }
        $oldFiles = EmptyValue($row['pengantar_nota']) ? [] : [$row['pengantar_nota']];
        foreach ($oldFiles as $oldFile) {
            if (file_exists($this->pengantar_nota->oldPhysicalUploadPath() . $oldFile)) {
                @unlink($this->pengantar_nota->oldPhysicalUploadPath() . $oldFile);
            }
        }
        $oldFiles = EmptyValue($row['risalah_sidang']) ? [] : [$row['risalah_sidang']];
        foreach ($oldFiles as $oldFile) {
            if (file_exists($this->risalah_sidang->oldPhysicalUploadPath() . $oldFile)) {
                @unlink($this->risalah_sidang->oldPhysicalUploadPath() . $oldFile);
            }
        }
        $oldFiles = EmptyValue($row['bap_apbk']) ? [] : [$row['bap_apbk']];
        foreach ($oldFiles as $oldFile) {
            if (file_exists($this->bap_apbk->oldPhysicalUploadPath() . $oldFile)) {
                @unlink($this->bap_apbk->oldPhysicalUploadPath() . $oldFile);
            }
        }
        $oldFiles = EmptyValue($row['rq_apbk']) ? [] : [$row['rq_apbk']];
        foreach ($oldFiles as $oldFile) {
            if (file_exists($this->rq_apbk->oldPhysicalUploadPath() . $oldFile)) {
                @unlink($this->rq_apbk->oldPhysicalUploadPath() . $oldFile);
            }
        }
        $oldFiles = EmptyValue($row['rp_penjabaran']) ? [] : [$row['rp_penjabaran']];
        foreach ($oldFiles as $oldFile) {
            if (file_exists($this->rp_penjabaran->oldPhysicalUploadPath() . $oldFile)) {
                @unlink($this->rp_penjabaran->oldPhysicalUploadPath() . $oldFile);
            }
        }
        $oldFiles = EmptyValue($row['jadwal_proses']) ? [] : [$row['jadwal_proses']];
        foreach ($oldFiles as $oldFile) {
            if (file_exists($this->jadwal_proses->oldPhysicalUploadPath() . $oldFile)) {
                @unlink($this->jadwal_proses->oldPhysicalUploadPath() . $oldFile);
            }
        }
        $oldFiles = EmptyValue($row['sinkron_kebijakan']) ? [] : [$row['sinkron_kebijakan']];
        foreach ($oldFiles as $oldFile) {
            if (file_exists($this->sinkron_kebijakan->oldPhysicalUploadPath() . $oldFile)) {
                @unlink($this->sinkron_kebijakan->oldPhysicalUploadPath() . $oldFile);
            }
        }
        $oldFiles = EmptyValue($row['konsistensi_program']) ? [] : [$row['konsistensi_program']];
        foreach ($oldFiles as $oldFile) {
            if (file_exists($this->konsistensi_program->oldPhysicalUploadPath() . $oldFile)) {
                @unlink($this->konsistensi_program->oldPhysicalUploadPath() . $oldFile);
            }
        }
        $oldFiles = EmptyValue($row['alokasi_pendidikan']) ? [] : [$row['alokasi_pendidikan']];
        foreach ($oldFiles as $oldFile) {
            if (file_exists($this->alokasi_pendidikan->oldPhysicalUploadPath() . $oldFile)) {
                @unlink($this->alokasi_pendidikan->oldPhysicalUploadPath() . $oldFile);
            }
        }
        $oldFiles = EmptyValue($row['alokasi_kesehatan']) ? [] : [$row['alokasi_kesehatan']];
        foreach ($oldFiles as $oldFile) {
            if (file_exists($this->alokasi_kesehatan->oldPhysicalUploadPath() . $oldFile)) {
                @unlink($this->alokasi_kesehatan->oldPhysicalUploadPath() . $oldFile);
            }
        }
        $oldFiles = EmptyValue($row['alokasi_belanja']) ? [] : [$row['alokasi_belanja']];
        foreach ($oldFiles as $oldFile) {
            if (file_exists($this->alokasi_belanja->oldPhysicalUploadPath() . $oldFile)) {
                @unlink($this->alokasi_belanja->oldPhysicalUploadPath() . $oldFile);
            }
        }
        $oldFiles = EmptyValue($row['bak_kegiatan']) ? [] : [$row['bak_kegiatan']];
        foreach ($oldFiles as $oldFile) {
            if (file_exists($this->bak_kegiatan->oldPhysicalUploadPath() . $oldFile)) {
                @unlink($this->bak_kegiatan->oldPhysicalUploadPath() . $oldFile);
            }
        }
        $oldFiles = EmptyValue($row['softcopy_rka']) ? [] : [$row['softcopy_rka']];
        foreach ($oldFiles as $oldFile) {
            if (file_exists($this->softcopy_rka->oldPhysicalUploadPath() . $oldFile)) {
                @unlink($this->softcopy_rka->oldPhysicalUploadPath() . $oldFile);
            }
        }
        $oldFiles = EmptyValue($row['otsus']) ? [] : [$row['otsus']];
        foreach ($oldFiles as $oldFile) {
            if (file_exists($this->otsus->oldPhysicalUploadPath() . $oldFile)) {
                @unlink($this->otsus->oldPhysicalUploadPath() . $oldFile);
            }
        }
        $oldFiles = EmptyValue($row['qanun_perbup']) ? [] : [$row['qanun_perbup']];
        foreach ($oldFiles as $oldFile) {
            if (file_exists($this->qanun_perbup->oldPhysicalUploadPath() . $oldFile)) {
                @unlink($this->qanun_perbup->oldPhysicalUploadPath() . $oldFile);
            }
        }
        $oldFiles = EmptyValue($row['tindak_apbkp']) ? [] : [$row['tindak_apbkp']];
        foreach ($oldFiles as $oldFile) {
            if (file_exists($this->tindak_apbkp->oldPhysicalUploadPath() . $oldFile)) {
                @unlink($this->tindak_apbkp->oldPhysicalUploadPath() . $oldFile);
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
        return $_SESSION[$name] ?? GetUrl("evaluasilist");
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
        if ($pageName == "evaluasiview") {
            return $Language->phrase("View");
        } elseif ($pageName == "evaluasiedit") {
            return $Language->phrase("Edit");
        } elseif ($pageName == "evaluasiadd") {
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
                return "EvaluasiView";
            case Config("API_ADD_ACTION"):
                return "EvaluasiAdd";
            case Config("API_EDIT_ACTION"):
                return "EvaluasiEdit";
            case Config("API_DELETE_ACTION"):
                return "EvaluasiDelete";
            case Config("API_LIST_ACTION"):
                return "EvaluasiList";
            default:
                return "";
        }
    }

    // List URL
    public function getListUrl()
    {
        return "evaluasilist";
    }

    // View URL
    public function getViewUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("evaluasiview", $this->getUrlParm($parm));
        } else {
            $url = $this->keyUrl("evaluasiview", $this->getUrlParm(Config("TABLE_SHOW_DETAIL") . "="));
        }
        return $this->addMasterUrl($url);
    }

    // Add URL
    public function getAddUrl($parm = "")
    {
        if ($parm != "") {
            $url = "evaluasiadd?" . $this->getUrlParm($parm);
        } else {
            $url = "evaluasiadd";
        }
        return $this->addMasterUrl($url);
    }

    // Edit URL
    public function getEditUrl($parm = "")
    {
        $url = $this->keyUrl("evaluasiedit", $this->getUrlParm($parm));
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
        $url = $this->keyUrl("evaluasiadd", $this->getUrlParm($parm));
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
        return $this->keyUrl("evaluasidelete", $this->getUrlParm());
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
        $this->idd_wilayah->setDbValue($row['idd_wilayah']);
        $this->kd_satker->setDbValue($row['kd_satker']);
        $this->idd_tahapan->setDbValue($row['idd_tahapan']);
        $this->tahun_anggaran->setDbValue($row['tahun_anggaran']);
        $this->surat_pengantar->Upload->DbValue = $row['surat_pengantar'];
        $this->surat_pengantar->setDbValue($this->surat_pengantar->Upload->DbValue);
        $this->rpjmd->Upload->DbValue = $row['rpjmd'];
        $this->rpjmd->setDbValue($this->rpjmd->Upload->DbValue);
        $this->rkpk->Upload->DbValue = $row['rkpk'];
        $this->rkpk->setDbValue($this->rkpk->Upload->DbValue);
        $this->skd_rkuappas->Upload->DbValue = $row['skd_rkuappas'];
        $this->skd_rkuappas->setDbValue($this->skd_rkuappas->Upload->DbValue);
        $this->kua->Upload->DbValue = $row['kua'];
        $this->kua->setDbValue($this->kua->Upload->DbValue);
        $this->ppas->Upload->DbValue = $row['ppas'];
        $this->ppas->setDbValue($this->ppas->Upload->DbValue);
        $this->skd_rqanun->Upload->DbValue = $row['skd_rqanun'];
        $this->skd_rqanun->setDbValue($this->skd_rqanun->Upload->DbValue);
        $this->nota_keuangan->Upload->DbValue = $row['nota_keuangan'];
        $this->nota_keuangan->setDbValue($this->nota_keuangan->Upload->DbValue);
        $this->pengantar_nota->Upload->DbValue = $row['pengantar_nota'];
        $this->pengantar_nota->setDbValue($this->pengantar_nota->Upload->DbValue);
        $this->risalah_sidang->Upload->DbValue = $row['risalah_sidang'];
        $this->risalah_sidang->setDbValue($this->risalah_sidang->Upload->DbValue);
        $this->bap_apbk->Upload->DbValue = $row['bap_apbk'];
        $this->bap_apbk->setDbValue($this->bap_apbk->Upload->DbValue);
        $this->rq_apbk->Upload->DbValue = $row['rq_apbk'];
        $this->rq_apbk->setDbValue($this->rq_apbk->Upload->DbValue);
        $this->rp_penjabaran->Upload->DbValue = $row['rp_penjabaran'];
        $this->rp_penjabaran->setDbValue($this->rp_penjabaran->Upload->DbValue);
        $this->jadwal_proses->Upload->DbValue = $row['jadwal_proses'];
        $this->jadwal_proses->setDbValue($this->jadwal_proses->Upload->DbValue);
        $this->sinkron_kebijakan->Upload->DbValue = $row['sinkron_kebijakan'];
        $this->sinkron_kebijakan->setDbValue($this->sinkron_kebijakan->Upload->DbValue);
        $this->konsistensi_program->Upload->DbValue = $row['konsistensi_program'];
        $this->konsistensi_program->setDbValue($this->konsistensi_program->Upload->DbValue);
        $this->alokasi_pendidikan->Upload->DbValue = $row['alokasi_pendidikan'];
        $this->alokasi_pendidikan->setDbValue($this->alokasi_pendidikan->Upload->DbValue);
        $this->alokasi_kesehatan->Upload->DbValue = $row['alokasi_kesehatan'];
        $this->alokasi_kesehatan->setDbValue($this->alokasi_kesehatan->Upload->DbValue);
        $this->alokasi_belanja->Upload->DbValue = $row['alokasi_belanja'];
        $this->alokasi_belanja->setDbValue($this->alokasi_belanja->Upload->DbValue);
        $this->bak_kegiatan->Upload->DbValue = $row['bak_kegiatan'];
        $this->bak_kegiatan->setDbValue($this->bak_kegiatan->Upload->DbValue);
        $this->softcopy_rka->Upload->DbValue = $row['softcopy_rka'];
        $this->softcopy_rka->setDbValue($this->softcopy_rka->Upload->DbValue);
        $this->otsus->Upload->DbValue = $row['otsus'];
        $this->otsus->setDbValue($this->otsus->Upload->DbValue);
        $this->qanun_perbup->Upload->DbValue = $row['qanun_perbup'];
        $this->qanun_perbup->setDbValue($this->qanun_perbup->Upload->DbValue);
        $this->tindak_apbkp->Upload->DbValue = $row['tindak_apbkp'];
        $this->tindak_apbkp->setDbValue($this->tindak_apbkp->Upload->DbValue);
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

        // idd_wilayah

        // kd_satker

        // idd_tahapan

        // tahun_anggaran

        // surat_pengantar

        // rpjmd

        // rkpk

        // skd_rkuappas

        // kua

        // ppas

        // skd_rqanun

        // nota_keuangan

        // pengantar_nota

        // risalah_sidang

        // bap_apbk

        // rq_apbk

        // rp_penjabaran

        // jadwal_proses

        // sinkron_kebijakan

        // konsistensi_program

        // alokasi_pendidikan

        // alokasi_kesehatan

        // alokasi_belanja

        // bak_kegiatan

        // softcopy_rka

        // otsus

        // qanun_perbup

        // tindak_apbkp

        // status

        // idd_user

        // idd_evaluasi
        $this->idd_evaluasi->ViewValue = $this->idd_evaluasi->CurrentValue;
        $this->idd_evaluasi->ViewCustomAttributes = "";

        // tanggal
        $this->tanggal->ViewValue = $this->tanggal->CurrentValue;
        $this->tanggal->ViewValue = FormatDateTime($this->tanggal->ViewValue, 0);
        $this->tanggal->ViewCustomAttributes = "";

        // idd_wilayah
        $curVal = trim(strval($this->idd_wilayah->CurrentValue));
        if ($curVal != "") {
            $this->idd_wilayah->ViewValue = $this->idd_wilayah->lookupCacheOption($curVal);
            if ($this->idd_wilayah->ViewValue === null) { // Lookup from database
                $filterWrk = "`idd_wilayah`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                $sqlWrk = $this->idd_wilayah->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->idd_wilayah->Lookup->renderViewRow($rswrk[0]);
                    $this->idd_wilayah->ViewValue = $this->idd_wilayah->displayValue($arwrk);
                } else {
                    $this->idd_wilayah->ViewValue = $this->idd_wilayah->CurrentValue;
                }
            }
        } else {
            $this->idd_wilayah->ViewValue = null;
        }
        $this->idd_wilayah->ViewCustomAttributes = "";

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
        if (strval($this->tahun_anggaran->CurrentValue) != "") {
            $this->tahun_anggaran->ViewValue = $this->tahun_anggaran->optionCaption($this->tahun_anggaran->CurrentValue);
        } else {
            $this->tahun_anggaran->ViewValue = null;
        }
        $this->tahun_anggaran->ViewCustomAttributes = "";

        // surat_pengantar
        if (!EmptyValue($this->surat_pengantar->Upload->DbValue)) {
            $this->surat_pengantar->ViewValue = $this->surat_pengantar->Upload->DbValue;
        } else {
            $this->surat_pengantar->ViewValue = "";
        }
        $this->surat_pengantar->ViewCustomAttributes = "";

        // rpjmd
        if (!EmptyValue($this->rpjmd->Upload->DbValue)) {
            $this->rpjmd->ViewValue = $this->rpjmd->Upload->DbValue;
        } else {
            $this->rpjmd->ViewValue = "";
        }
        $this->rpjmd->ViewCustomAttributes = "";

        // rkpk
        if (!EmptyValue($this->rkpk->Upload->DbValue)) {
            $this->rkpk->ViewValue = $this->rkpk->Upload->DbValue;
        } else {
            $this->rkpk->ViewValue = "";
        }
        $this->rkpk->ViewCustomAttributes = "";

        // skd_rkuappas
        if (!EmptyValue($this->skd_rkuappas->Upload->DbValue)) {
            $this->skd_rkuappas->ViewValue = $this->skd_rkuappas->Upload->DbValue;
        } else {
            $this->skd_rkuappas->ViewValue = "";
        }
        $this->skd_rkuappas->ViewCustomAttributes = "";

        // kua
        if (!EmptyValue($this->kua->Upload->DbValue)) {
            $this->kua->ViewValue = $this->kua->Upload->DbValue;
        } else {
            $this->kua->ViewValue = "";
        }
        $this->kua->ViewCustomAttributes = "";

        // ppas
        if (!EmptyValue($this->ppas->Upload->DbValue)) {
            $this->ppas->ViewValue = $this->ppas->Upload->DbValue;
        } else {
            $this->ppas->ViewValue = "";
        }
        $this->ppas->ViewCustomAttributes = "";

        // skd_rqanun
        if (!EmptyValue($this->skd_rqanun->Upload->DbValue)) {
            $this->skd_rqanun->ViewValue = $this->skd_rqanun->Upload->DbValue;
        } else {
            $this->skd_rqanun->ViewValue = "";
        }
        $this->skd_rqanun->ViewCustomAttributes = "";

        // nota_keuangan
        if (!EmptyValue($this->nota_keuangan->Upload->DbValue)) {
            $this->nota_keuangan->ViewValue = $this->nota_keuangan->Upload->DbValue;
        } else {
            $this->nota_keuangan->ViewValue = "";
        }
        $this->nota_keuangan->ViewCustomAttributes = "";

        // pengantar_nota
        if (!EmptyValue($this->pengantar_nota->Upload->DbValue)) {
            $this->pengantar_nota->ViewValue = $this->pengantar_nota->Upload->DbValue;
        } else {
            $this->pengantar_nota->ViewValue = "";
        }
        $this->pengantar_nota->ViewCustomAttributes = "";

        // risalah_sidang
        if (!EmptyValue($this->risalah_sidang->Upload->DbValue)) {
            $this->risalah_sidang->ViewValue = $this->risalah_sidang->Upload->DbValue;
        } else {
            $this->risalah_sidang->ViewValue = "";
        }
        $this->risalah_sidang->ViewCustomAttributes = "";

        // bap_apbk
        if (!EmptyValue($this->bap_apbk->Upload->DbValue)) {
            $this->bap_apbk->ViewValue = $this->bap_apbk->Upload->DbValue;
        } else {
            $this->bap_apbk->ViewValue = "";
        }
        $this->bap_apbk->ViewCustomAttributes = "";

        // rq_apbk
        if (!EmptyValue($this->rq_apbk->Upload->DbValue)) {
            $this->rq_apbk->ViewValue = $this->rq_apbk->Upload->DbValue;
        } else {
            $this->rq_apbk->ViewValue = "";
        }
        $this->rq_apbk->ViewCustomAttributes = "";

        // rp_penjabaran
        if (!EmptyValue($this->rp_penjabaran->Upload->DbValue)) {
            $this->rp_penjabaran->ViewValue = $this->rp_penjabaran->Upload->DbValue;
        } else {
            $this->rp_penjabaran->ViewValue = "";
        }
        $this->rp_penjabaran->ViewCustomAttributes = "";

        // jadwal_proses
        if (!EmptyValue($this->jadwal_proses->Upload->DbValue)) {
            $this->jadwal_proses->ViewValue = $this->jadwal_proses->Upload->DbValue;
        } else {
            $this->jadwal_proses->ViewValue = "";
        }
        $this->jadwal_proses->ViewCustomAttributes = "";

        // sinkron_kebijakan
        if (!EmptyValue($this->sinkron_kebijakan->Upload->DbValue)) {
            $this->sinkron_kebijakan->ViewValue = $this->sinkron_kebijakan->Upload->DbValue;
        } else {
            $this->sinkron_kebijakan->ViewValue = "";
        }
        $this->sinkron_kebijakan->ViewCustomAttributes = "";

        // konsistensi_program
        if (!EmptyValue($this->konsistensi_program->Upload->DbValue)) {
            $this->konsistensi_program->ViewValue = $this->konsistensi_program->Upload->DbValue;
        } else {
            $this->konsistensi_program->ViewValue = "";
        }
        $this->konsistensi_program->ViewCustomAttributes = "";

        // alokasi_pendidikan
        if (!EmptyValue($this->alokasi_pendidikan->Upload->DbValue)) {
            $this->alokasi_pendidikan->ViewValue = $this->alokasi_pendidikan->Upload->DbValue;
        } else {
            $this->alokasi_pendidikan->ViewValue = "";
        }
        $this->alokasi_pendidikan->ViewCustomAttributes = "";

        // alokasi_kesehatan
        if (!EmptyValue($this->alokasi_kesehatan->Upload->DbValue)) {
            $this->alokasi_kesehatan->ViewValue = $this->alokasi_kesehatan->Upload->DbValue;
        } else {
            $this->alokasi_kesehatan->ViewValue = "";
        }
        $this->alokasi_kesehatan->ViewCustomAttributes = "";

        // alokasi_belanja
        if (!EmptyValue($this->alokasi_belanja->Upload->DbValue)) {
            $this->alokasi_belanja->ViewValue = $this->alokasi_belanja->Upload->DbValue;
        } else {
            $this->alokasi_belanja->ViewValue = "";
        }
        $this->alokasi_belanja->ViewCustomAttributes = "";

        // bak_kegiatan
        if (!EmptyValue($this->bak_kegiatan->Upload->DbValue)) {
            $this->bak_kegiatan->ViewValue = $this->bak_kegiatan->Upload->DbValue;
        } else {
            $this->bak_kegiatan->ViewValue = "";
        }
        $this->bak_kegiatan->ViewCustomAttributes = "";

        // softcopy_rka
        if (!EmptyValue($this->softcopy_rka->Upload->DbValue)) {
            $this->softcopy_rka->ViewValue = $this->softcopy_rka->Upload->DbValue;
        } else {
            $this->softcopy_rka->ViewValue = "";
        }
        $this->softcopy_rka->ViewCustomAttributes = "";

        // otsus
        if (!EmptyValue($this->otsus->Upload->DbValue)) {
            $this->otsus->ViewValue = $this->otsus->Upload->DbValue;
        } else {
            $this->otsus->ViewValue = "";
        }
        $this->otsus->ViewCustomAttributes = "";

        // qanun_perbup
        if (!EmptyValue($this->qanun_perbup->Upload->DbValue)) {
            $this->qanun_perbup->ViewValue = $this->qanun_perbup->Upload->DbValue;
        } else {
            $this->qanun_perbup->ViewValue = "";
        }
        $this->qanun_perbup->ViewCustomAttributes = "";

        // tindak_apbkp
        if (!EmptyValue($this->tindak_apbkp->Upload->DbValue)) {
            $this->tindak_apbkp->ViewValue = $this->tindak_apbkp->Upload->DbValue;
        } else {
            $this->tindak_apbkp->ViewValue = "";
        }
        $this->tindak_apbkp->ViewCustomAttributes = "";

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

        // idd_wilayah
        $this->idd_wilayah->LinkCustomAttributes = "";
        $this->idd_wilayah->HrefValue = "";
        $this->idd_wilayah->TooltipValue = "";

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
        $this->surat_pengantar->HrefValue = "";
        $this->surat_pengantar->ExportHrefValue = $this->surat_pengantar->UploadPath . $this->surat_pengantar->Upload->DbValue;
        $this->surat_pengantar->TooltipValue = "";

        // rpjmd
        $this->rpjmd->LinkCustomAttributes = "";
        $this->rpjmd->HrefValue = "";
        $this->rpjmd->ExportHrefValue = $this->rpjmd->UploadPath . $this->rpjmd->Upload->DbValue;
        $this->rpjmd->TooltipValue = "";

        // rkpk
        $this->rkpk->LinkCustomAttributes = "";
        $this->rkpk->HrefValue = "";
        $this->rkpk->ExportHrefValue = $this->rkpk->UploadPath . $this->rkpk->Upload->DbValue;
        $this->rkpk->TooltipValue = "";

        // skd_rkuappas
        $this->skd_rkuappas->LinkCustomAttributes = "";
        $this->skd_rkuappas->HrefValue = "";
        $this->skd_rkuappas->ExportHrefValue = $this->skd_rkuappas->UploadPath . $this->skd_rkuappas->Upload->DbValue;
        $this->skd_rkuappas->TooltipValue = "";

        // kua
        $this->kua->LinkCustomAttributes = "";
        $this->kua->HrefValue = "";
        $this->kua->ExportHrefValue = $this->kua->UploadPath . $this->kua->Upload->DbValue;
        $this->kua->TooltipValue = "";

        // ppas
        $this->ppas->LinkCustomAttributes = "";
        $this->ppas->HrefValue = "";
        $this->ppas->ExportHrefValue = $this->ppas->UploadPath . $this->ppas->Upload->DbValue;
        $this->ppas->TooltipValue = "";

        // skd_rqanun
        $this->skd_rqanun->LinkCustomAttributes = "";
        $this->skd_rqanun->HrefValue = "";
        $this->skd_rqanun->ExportHrefValue = $this->skd_rqanun->UploadPath . $this->skd_rqanun->Upload->DbValue;
        $this->skd_rqanun->TooltipValue = "";

        // nota_keuangan
        $this->nota_keuangan->LinkCustomAttributes = "";
        $this->nota_keuangan->HrefValue = "";
        $this->nota_keuangan->ExportHrefValue = $this->nota_keuangan->UploadPath . $this->nota_keuangan->Upload->DbValue;
        $this->nota_keuangan->TooltipValue = "";

        // pengantar_nota
        $this->pengantar_nota->LinkCustomAttributes = "";
        $this->pengantar_nota->HrefValue = "";
        $this->pengantar_nota->ExportHrefValue = $this->pengantar_nota->UploadPath . $this->pengantar_nota->Upload->DbValue;
        $this->pengantar_nota->TooltipValue = "";

        // risalah_sidang
        $this->risalah_sidang->LinkCustomAttributes = "";
        $this->risalah_sidang->HrefValue = "";
        $this->risalah_sidang->ExportHrefValue = $this->risalah_sidang->UploadPath . $this->risalah_sidang->Upload->DbValue;
        $this->risalah_sidang->TooltipValue = "";

        // bap_apbk
        $this->bap_apbk->LinkCustomAttributes = "";
        $this->bap_apbk->HrefValue = "";
        $this->bap_apbk->ExportHrefValue = $this->bap_apbk->UploadPath . $this->bap_apbk->Upload->DbValue;
        $this->bap_apbk->TooltipValue = "";

        // rq_apbk
        $this->rq_apbk->LinkCustomAttributes = "";
        $this->rq_apbk->HrefValue = "";
        $this->rq_apbk->ExportHrefValue = $this->rq_apbk->UploadPath . $this->rq_apbk->Upload->DbValue;
        $this->rq_apbk->TooltipValue = "";

        // rp_penjabaran
        $this->rp_penjabaran->LinkCustomAttributes = "";
        $this->rp_penjabaran->HrefValue = "";
        $this->rp_penjabaran->ExportHrefValue = $this->rp_penjabaran->UploadPath . $this->rp_penjabaran->Upload->DbValue;
        $this->rp_penjabaran->TooltipValue = "";

        // jadwal_proses
        $this->jadwal_proses->LinkCustomAttributes = "";
        $this->jadwal_proses->HrefValue = "";
        $this->jadwal_proses->ExportHrefValue = $this->jadwal_proses->UploadPath . $this->jadwal_proses->Upload->DbValue;
        $this->jadwal_proses->TooltipValue = "";

        // sinkron_kebijakan
        $this->sinkron_kebijakan->LinkCustomAttributes = "";
        $this->sinkron_kebijakan->HrefValue = "";
        $this->sinkron_kebijakan->ExportHrefValue = $this->sinkron_kebijakan->UploadPath . $this->sinkron_kebijakan->Upload->DbValue;
        $this->sinkron_kebijakan->TooltipValue = "";

        // konsistensi_program
        $this->konsistensi_program->LinkCustomAttributes = "";
        $this->konsistensi_program->HrefValue = "";
        $this->konsistensi_program->ExportHrefValue = $this->konsistensi_program->UploadPath . $this->konsistensi_program->Upload->DbValue;
        $this->konsistensi_program->TooltipValue = "";

        // alokasi_pendidikan
        $this->alokasi_pendidikan->LinkCustomAttributes = "";
        $this->alokasi_pendidikan->HrefValue = "";
        $this->alokasi_pendidikan->ExportHrefValue = $this->alokasi_pendidikan->UploadPath . $this->alokasi_pendidikan->Upload->DbValue;
        $this->alokasi_pendidikan->TooltipValue = "";

        // alokasi_kesehatan
        $this->alokasi_kesehatan->LinkCustomAttributes = "";
        $this->alokasi_kesehatan->HrefValue = "";
        $this->alokasi_kesehatan->ExportHrefValue = $this->alokasi_kesehatan->UploadPath . $this->alokasi_kesehatan->Upload->DbValue;
        $this->alokasi_kesehatan->TooltipValue = "";

        // alokasi_belanja
        $this->alokasi_belanja->LinkCustomAttributes = "";
        $this->alokasi_belanja->HrefValue = "";
        $this->alokasi_belanja->ExportHrefValue = $this->alokasi_belanja->UploadPath . $this->alokasi_belanja->Upload->DbValue;
        $this->alokasi_belanja->TooltipValue = "";

        // bak_kegiatan
        $this->bak_kegiatan->LinkCustomAttributes = "";
        $this->bak_kegiatan->HrefValue = "";
        $this->bak_kegiatan->ExportHrefValue = $this->bak_kegiatan->UploadPath . $this->bak_kegiatan->Upload->DbValue;
        $this->bak_kegiatan->TooltipValue = "";

        // softcopy_rka
        $this->softcopy_rka->LinkCustomAttributes = "";
        $this->softcopy_rka->HrefValue = "";
        $this->softcopy_rka->ExportHrefValue = $this->softcopy_rka->UploadPath . $this->softcopy_rka->Upload->DbValue;
        $this->softcopy_rka->TooltipValue = "";

        // otsus
        $this->otsus->LinkCustomAttributes = "";
        $this->otsus->HrefValue = "";
        $this->otsus->ExportHrefValue = $this->otsus->UploadPath . $this->otsus->Upload->DbValue;
        $this->otsus->TooltipValue = "";

        // qanun_perbup
        $this->qanun_perbup->LinkCustomAttributes = "";
        $this->qanun_perbup->HrefValue = "";
        $this->qanun_perbup->ExportHrefValue = $this->qanun_perbup->UploadPath . $this->qanun_perbup->Upload->DbValue;
        $this->qanun_perbup->TooltipValue = "";

        // tindak_apbkp
        $this->tindak_apbkp->LinkCustomAttributes = "";
        $this->tindak_apbkp->HrefValue = "";
        $this->tindak_apbkp->ExportHrefValue = $this->tindak_apbkp->UploadPath . $this->tindak_apbkp->Upload->DbValue;
        $this->tindak_apbkp->TooltipValue = "";

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

        // idd_wilayah
        $this->idd_wilayah->EditAttrs["class"] = "form-control";
        $this->idd_wilayah->EditCustomAttributes = "";
        $this->idd_wilayah->PlaceHolder = RemoveHtml($this->idd_wilayah->caption());

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
        $this->tahun_anggaran->EditValue = $this->tahun_anggaran->options(true);
        $this->tahun_anggaran->PlaceHolder = RemoveHtml($this->tahun_anggaran->caption());

        // surat_pengantar
        $this->surat_pengantar->EditAttrs["class"] = "form-control";
        $this->surat_pengantar->EditCustomAttributes = "";
        if (!EmptyValue($this->surat_pengantar->Upload->DbValue)) {
            $this->surat_pengantar->EditValue = $this->surat_pengantar->Upload->DbValue;
        } else {
            $this->surat_pengantar->EditValue = "";
        }
        if (!EmptyValue($this->surat_pengantar->CurrentValue)) {
            $this->surat_pengantar->Upload->FileName = $this->surat_pengantar->CurrentValue;
        }

        // rpjmd
        $this->rpjmd->EditAttrs["class"] = "form-control";
        $this->rpjmd->EditCustomAttributes = "";
        if (!EmptyValue($this->rpjmd->Upload->DbValue)) {
            $this->rpjmd->EditValue = $this->rpjmd->Upload->DbValue;
        } else {
            $this->rpjmd->EditValue = "";
        }
        if (!EmptyValue($this->rpjmd->CurrentValue)) {
            $this->rpjmd->Upload->FileName = $this->rpjmd->CurrentValue;
        }

        // rkpk
        $this->rkpk->EditAttrs["class"] = "form-control";
        $this->rkpk->EditCustomAttributes = "";
        if (!EmptyValue($this->rkpk->Upload->DbValue)) {
            $this->rkpk->EditValue = $this->rkpk->Upload->DbValue;
        } else {
            $this->rkpk->EditValue = "";
        }
        if (!EmptyValue($this->rkpk->CurrentValue)) {
            $this->rkpk->Upload->FileName = $this->rkpk->CurrentValue;
        }

        // skd_rkuappas
        $this->skd_rkuappas->EditAttrs["class"] = "form-control";
        $this->skd_rkuappas->EditCustomAttributes = "";
        if (!EmptyValue($this->skd_rkuappas->Upload->DbValue)) {
            $this->skd_rkuappas->EditValue = $this->skd_rkuappas->Upload->DbValue;
        } else {
            $this->skd_rkuappas->EditValue = "";
        }
        if (!EmptyValue($this->skd_rkuappas->CurrentValue)) {
            $this->skd_rkuappas->Upload->FileName = $this->skd_rkuappas->CurrentValue;
        }

        // kua
        $this->kua->EditAttrs["class"] = "form-control";
        $this->kua->EditCustomAttributes = "";
        if (!EmptyValue($this->kua->Upload->DbValue)) {
            $this->kua->EditValue = $this->kua->Upload->DbValue;
        } else {
            $this->kua->EditValue = "";
        }
        if (!EmptyValue($this->kua->CurrentValue)) {
            $this->kua->Upload->FileName = $this->kua->CurrentValue;
        }

        // ppas
        $this->ppas->EditAttrs["class"] = "form-control";
        $this->ppas->EditCustomAttributes = "";
        if (!EmptyValue($this->ppas->Upload->DbValue)) {
            $this->ppas->EditValue = $this->ppas->Upload->DbValue;
        } else {
            $this->ppas->EditValue = "";
        }
        if (!EmptyValue($this->ppas->CurrentValue)) {
            $this->ppas->Upload->FileName = $this->ppas->CurrentValue;
        }

        // skd_rqanun
        $this->skd_rqanun->EditAttrs["class"] = "form-control";
        $this->skd_rqanun->EditCustomAttributes = "";
        if (!EmptyValue($this->skd_rqanun->Upload->DbValue)) {
            $this->skd_rqanun->EditValue = $this->skd_rqanun->Upload->DbValue;
        } else {
            $this->skd_rqanun->EditValue = "";
        }
        if (!EmptyValue($this->skd_rqanun->CurrentValue)) {
            $this->skd_rqanun->Upload->FileName = $this->skd_rqanun->CurrentValue;
        }

        // nota_keuangan
        $this->nota_keuangan->EditAttrs["class"] = "form-control";
        $this->nota_keuangan->EditCustomAttributes = "";
        if (!EmptyValue($this->nota_keuangan->Upload->DbValue)) {
            $this->nota_keuangan->EditValue = $this->nota_keuangan->Upload->DbValue;
        } else {
            $this->nota_keuangan->EditValue = "";
        }
        if (!EmptyValue($this->nota_keuangan->CurrentValue)) {
            $this->nota_keuangan->Upload->FileName = $this->nota_keuangan->CurrentValue;
        }

        // pengantar_nota
        $this->pengantar_nota->EditAttrs["class"] = "form-control";
        $this->pengantar_nota->EditCustomAttributes = "";
        if (!EmptyValue($this->pengantar_nota->Upload->DbValue)) {
            $this->pengantar_nota->EditValue = $this->pengantar_nota->Upload->DbValue;
        } else {
            $this->pengantar_nota->EditValue = "";
        }
        if (!EmptyValue($this->pengantar_nota->CurrentValue)) {
            $this->pengantar_nota->Upload->FileName = $this->pengantar_nota->CurrentValue;
        }

        // risalah_sidang
        $this->risalah_sidang->EditAttrs["class"] = "form-control";
        $this->risalah_sidang->EditCustomAttributes = "";
        if (!EmptyValue($this->risalah_sidang->Upload->DbValue)) {
            $this->risalah_sidang->EditValue = $this->risalah_sidang->Upload->DbValue;
        } else {
            $this->risalah_sidang->EditValue = "";
        }
        if (!EmptyValue($this->risalah_sidang->CurrentValue)) {
            $this->risalah_sidang->Upload->FileName = $this->risalah_sidang->CurrentValue;
        }

        // bap_apbk
        $this->bap_apbk->EditAttrs["class"] = "form-control";
        $this->bap_apbk->EditCustomAttributes = "";
        if (!EmptyValue($this->bap_apbk->Upload->DbValue)) {
            $this->bap_apbk->EditValue = $this->bap_apbk->Upload->DbValue;
        } else {
            $this->bap_apbk->EditValue = "";
        }
        if (!EmptyValue($this->bap_apbk->CurrentValue)) {
            $this->bap_apbk->Upload->FileName = $this->bap_apbk->CurrentValue;
        }

        // rq_apbk
        $this->rq_apbk->EditAttrs["class"] = "form-control";
        $this->rq_apbk->EditCustomAttributes = "";
        if (!EmptyValue($this->rq_apbk->Upload->DbValue)) {
            $this->rq_apbk->EditValue = $this->rq_apbk->Upload->DbValue;
        } else {
            $this->rq_apbk->EditValue = "";
        }
        if (!EmptyValue($this->rq_apbk->CurrentValue)) {
            $this->rq_apbk->Upload->FileName = $this->rq_apbk->CurrentValue;
        }

        // rp_penjabaran
        $this->rp_penjabaran->EditAttrs["class"] = "form-control";
        $this->rp_penjabaran->EditCustomAttributes = "";
        if (!EmptyValue($this->rp_penjabaran->Upload->DbValue)) {
            $this->rp_penjabaran->EditValue = $this->rp_penjabaran->Upload->DbValue;
        } else {
            $this->rp_penjabaran->EditValue = "";
        }
        if (!EmptyValue($this->rp_penjabaran->CurrentValue)) {
            $this->rp_penjabaran->Upload->FileName = $this->rp_penjabaran->CurrentValue;
        }

        // jadwal_proses
        $this->jadwal_proses->EditAttrs["class"] = "form-control";
        $this->jadwal_proses->EditCustomAttributes = "";
        if (!EmptyValue($this->jadwal_proses->Upload->DbValue)) {
            $this->jadwal_proses->EditValue = $this->jadwal_proses->Upload->DbValue;
        } else {
            $this->jadwal_proses->EditValue = "";
        }
        if (!EmptyValue($this->jadwal_proses->CurrentValue)) {
            $this->jadwal_proses->Upload->FileName = $this->jadwal_proses->CurrentValue;
        }

        // sinkron_kebijakan
        $this->sinkron_kebijakan->EditAttrs["class"] = "form-control";
        $this->sinkron_kebijakan->EditCustomAttributes = "";
        if (!EmptyValue($this->sinkron_kebijakan->Upload->DbValue)) {
            $this->sinkron_kebijakan->EditValue = $this->sinkron_kebijakan->Upload->DbValue;
        } else {
            $this->sinkron_kebijakan->EditValue = "";
        }
        if (!EmptyValue($this->sinkron_kebijakan->CurrentValue)) {
            $this->sinkron_kebijakan->Upload->FileName = $this->sinkron_kebijakan->CurrentValue;
        }

        // konsistensi_program
        $this->konsistensi_program->EditAttrs["class"] = "form-control";
        $this->konsistensi_program->EditCustomAttributes = "";
        if (!EmptyValue($this->konsistensi_program->Upload->DbValue)) {
            $this->konsistensi_program->EditValue = $this->konsistensi_program->Upload->DbValue;
        } else {
            $this->konsistensi_program->EditValue = "";
        }
        if (!EmptyValue($this->konsistensi_program->CurrentValue)) {
            $this->konsistensi_program->Upload->FileName = $this->konsistensi_program->CurrentValue;
        }

        // alokasi_pendidikan
        $this->alokasi_pendidikan->EditAttrs["class"] = "form-control";
        $this->alokasi_pendidikan->EditCustomAttributes = "";
        if (!EmptyValue($this->alokasi_pendidikan->Upload->DbValue)) {
            $this->alokasi_pendidikan->EditValue = $this->alokasi_pendidikan->Upload->DbValue;
        } else {
            $this->alokasi_pendidikan->EditValue = "";
        }
        if (!EmptyValue($this->alokasi_pendidikan->CurrentValue)) {
            $this->alokasi_pendidikan->Upload->FileName = $this->alokasi_pendidikan->CurrentValue;
        }

        // alokasi_kesehatan
        $this->alokasi_kesehatan->EditAttrs["class"] = "form-control";
        $this->alokasi_kesehatan->EditCustomAttributes = "";
        if (!EmptyValue($this->alokasi_kesehatan->Upload->DbValue)) {
            $this->alokasi_kesehatan->EditValue = $this->alokasi_kesehatan->Upload->DbValue;
        } else {
            $this->alokasi_kesehatan->EditValue = "";
        }
        if (!EmptyValue($this->alokasi_kesehatan->CurrentValue)) {
            $this->alokasi_kesehatan->Upload->FileName = $this->alokasi_kesehatan->CurrentValue;
        }

        // alokasi_belanja
        $this->alokasi_belanja->EditAttrs["class"] = "form-control";
        $this->alokasi_belanja->EditCustomAttributes = "";
        if (!EmptyValue($this->alokasi_belanja->Upload->DbValue)) {
            $this->alokasi_belanja->EditValue = $this->alokasi_belanja->Upload->DbValue;
        } else {
            $this->alokasi_belanja->EditValue = "";
        }
        if (!EmptyValue($this->alokasi_belanja->CurrentValue)) {
            $this->alokasi_belanja->Upload->FileName = $this->alokasi_belanja->CurrentValue;
        }

        // bak_kegiatan
        $this->bak_kegiatan->EditAttrs["class"] = "form-control";
        $this->bak_kegiatan->EditCustomAttributes = "";
        if (!EmptyValue($this->bak_kegiatan->Upload->DbValue)) {
            $this->bak_kegiatan->EditValue = $this->bak_kegiatan->Upload->DbValue;
        } else {
            $this->bak_kegiatan->EditValue = "";
        }
        if (!EmptyValue($this->bak_kegiatan->CurrentValue)) {
            $this->bak_kegiatan->Upload->FileName = $this->bak_kegiatan->CurrentValue;
        }

        // softcopy_rka
        $this->softcopy_rka->EditAttrs["class"] = "form-control";
        $this->softcopy_rka->EditCustomAttributes = "";
        if (!EmptyValue($this->softcopy_rka->Upload->DbValue)) {
            $this->softcopy_rka->EditValue = $this->softcopy_rka->Upload->DbValue;
        } else {
            $this->softcopy_rka->EditValue = "";
        }
        if (!EmptyValue($this->softcopy_rka->CurrentValue)) {
            $this->softcopy_rka->Upload->FileName = $this->softcopy_rka->CurrentValue;
        }

        // otsus
        $this->otsus->EditAttrs["class"] = "form-control";
        $this->otsus->EditCustomAttributes = "";
        if (!EmptyValue($this->otsus->Upload->DbValue)) {
            $this->otsus->EditValue = $this->otsus->Upload->DbValue;
        } else {
            $this->otsus->EditValue = "";
        }
        if (!EmptyValue($this->otsus->CurrentValue)) {
            $this->otsus->Upload->FileName = $this->otsus->CurrentValue;
        }

        // qanun_perbup
        $this->qanun_perbup->EditAttrs["class"] = "form-control";
        $this->qanun_perbup->EditCustomAttributes = "";
        if (!EmptyValue($this->qanun_perbup->Upload->DbValue)) {
            $this->qanun_perbup->EditValue = $this->qanun_perbup->Upload->DbValue;
        } else {
            $this->qanun_perbup->EditValue = "";
        }
        if (!EmptyValue($this->qanun_perbup->CurrentValue)) {
            $this->qanun_perbup->Upload->FileName = $this->qanun_perbup->CurrentValue;
        }

        // tindak_apbkp
        $this->tindak_apbkp->EditAttrs["class"] = "form-control";
        $this->tindak_apbkp->EditCustomAttributes = "";
        if (!EmptyValue($this->tindak_apbkp->Upload->DbValue)) {
            $this->tindak_apbkp->EditValue = $this->tindak_apbkp->Upload->DbValue;
        } else {
            $this->tindak_apbkp->EditValue = "";
        }
        if (!EmptyValue($this->tindak_apbkp->CurrentValue)) {
            $this->tindak_apbkp->Upload->FileName = $this->tindak_apbkp->CurrentValue;
        }

        // status
        $this->status->EditAttrs["class"] = "form-control";
        $this->status->EditCustomAttributes = "";
        $this->status->EditValue = $this->status->options(true);
        $this->status->PlaceHolder = RemoveHtml($this->status->caption());

        // idd_user
        $this->idd_user->EditAttrs["class"] = "form-control";
        $this->idd_user->EditCustomAttributes = "";
        if (!$Security->isAdmin() && $Security->isLoggedIn() && !$this->userIDAllow("info")) { // Non system admin
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
            $this->idd_user->PlaceHolder = RemoveHtml($this->idd_user->caption());
        }

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
                    $doc->exportCaption($this->idd_wilayah);
                    $doc->exportCaption($this->kd_satker);
                    $doc->exportCaption($this->idd_tahapan);
                    $doc->exportCaption($this->tahun_anggaran);
                    $doc->exportCaption($this->surat_pengantar);
                    $doc->exportCaption($this->rpjmd);
                    $doc->exportCaption($this->rkpk);
                    $doc->exportCaption($this->skd_rkuappas);
                    $doc->exportCaption($this->kua);
                    $doc->exportCaption($this->ppas);
                    $doc->exportCaption($this->skd_rqanun);
                    $doc->exportCaption($this->nota_keuangan);
                    $doc->exportCaption($this->pengantar_nota);
                    $doc->exportCaption($this->risalah_sidang);
                    $doc->exportCaption($this->bap_apbk);
                    $doc->exportCaption($this->rq_apbk);
                    $doc->exportCaption($this->rp_penjabaran);
                    $doc->exportCaption($this->jadwal_proses);
                    $doc->exportCaption($this->sinkron_kebijakan);
                    $doc->exportCaption($this->konsistensi_program);
                    $doc->exportCaption($this->alokasi_pendidikan);
                    $doc->exportCaption($this->alokasi_kesehatan);
                    $doc->exportCaption($this->alokasi_belanja);
                    $doc->exportCaption($this->bak_kegiatan);
                    $doc->exportCaption($this->softcopy_rka);
                    $doc->exportCaption($this->otsus);
                    $doc->exportCaption($this->qanun_perbup);
                    $doc->exportCaption($this->tindak_apbkp);
                    $doc->exportCaption($this->status);
                    $doc->exportCaption($this->idd_user);
                } else {
                    $doc->exportCaption($this->idd_evaluasi);
                    $doc->exportCaption($this->tanggal);
                    $doc->exportCaption($this->idd_wilayah);
                    $doc->exportCaption($this->kd_satker);
                    $doc->exportCaption($this->idd_tahapan);
                    $doc->exportCaption($this->tahun_anggaran);
                    $doc->exportCaption($this->surat_pengantar);
                    $doc->exportCaption($this->rpjmd);
                    $doc->exportCaption($this->rkpk);
                    $doc->exportCaption($this->skd_rkuappas);
                    $doc->exportCaption($this->kua);
                    $doc->exportCaption($this->ppas);
                    $doc->exportCaption($this->skd_rqanun);
                    $doc->exportCaption($this->nota_keuangan);
                    $doc->exportCaption($this->pengantar_nota);
                    $doc->exportCaption($this->risalah_sidang);
                    $doc->exportCaption($this->bap_apbk);
                    $doc->exportCaption($this->rq_apbk);
                    $doc->exportCaption($this->rp_penjabaran);
                    $doc->exportCaption($this->jadwal_proses);
                    $doc->exportCaption($this->sinkron_kebijakan);
                    $doc->exportCaption($this->konsistensi_program);
                    $doc->exportCaption($this->alokasi_pendidikan);
                    $doc->exportCaption($this->alokasi_kesehatan);
                    $doc->exportCaption($this->alokasi_belanja);
                    $doc->exportCaption($this->bak_kegiatan);
                    $doc->exportCaption($this->softcopy_rka);
                    $doc->exportCaption($this->otsus);
                    $doc->exportCaption($this->qanun_perbup);
                    $doc->exportCaption($this->tindak_apbkp);
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
                        $doc->exportField($this->idd_wilayah);
                        $doc->exportField($this->kd_satker);
                        $doc->exportField($this->idd_tahapan);
                        $doc->exportField($this->tahun_anggaran);
                        $doc->exportField($this->surat_pengantar);
                        $doc->exportField($this->rpjmd);
                        $doc->exportField($this->rkpk);
                        $doc->exportField($this->skd_rkuappas);
                        $doc->exportField($this->kua);
                        $doc->exportField($this->ppas);
                        $doc->exportField($this->skd_rqanun);
                        $doc->exportField($this->nota_keuangan);
                        $doc->exportField($this->pengantar_nota);
                        $doc->exportField($this->risalah_sidang);
                        $doc->exportField($this->bap_apbk);
                        $doc->exportField($this->rq_apbk);
                        $doc->exportField($this->rp_penjabaran);
                        $doc->exportField($this->jadwal_proses);
                        $doc->exportField($this->sinkron_kebijakan);
                        $doc->exportField($this->konsistensi_program);
                        $doc->exportField($this->alokasi_pendidikan);
                        $doc->exportField($this->alokasi_kesehatan);
                        $doc->exportField($this->alokasi_belanja);
                        $doc->exportField($this->bak_kegiatan);
                        $doc->exportField($this->softcopy_rka);
                        $doc->exportField($this->otsus);
                        $doc->exportField($this->qanun_perbup);
                        $doc->exportField($this->tindak_apbkp);
                        $doc->exportField($this->status);
                        $doc->exportField($this->idd_user);
                    } else {
                        $doc->exportField($this->idd_evaluasi);
                        $doc->exportField($this->tanggal);
                        $doc->exportField($this->idd_wilayah);
                        $doc->exportField($this->kd_satker);
                        $doc->exportField($this->idd_tahapan);
                        $doc->exportField($this->tahun_anggaran);
                        $doc->exportField($this->surat_pengantar);
                        $doc->exportField($this->rpjmd);
                        $doc->exportField($this->rkpk);
                        $doc->exportField($this->skd_rkuappas);
                        $doc->exportField($this->kua);
                        $doc->exportField($this->ppas);
                        $doc->exportField($this->skd_rqanun);
                        $doc->exportField($this->nota_keuangan);
                        $doc->exportField($this->pengantar_nota);
                        $doc->exportField($this->risalah_sidang);
                        $doc->exportField($this->bap_apbk);
                        $doc->exportField($this->rq_apbk);
                        $doc->exportField($this->rp_penjabaran);
                        $doc->exportField($this->jadwal_proses);
                        $doc->exportField($this->sinkron_kebijakan);
                        $doc->exportField($this->konsistensi_program);
                        $doc->exportField($this->alokasi_pendidikan);
                        $doc->exportField($this->alokasi_kesehatan);
                        $doc->exportField($this->alokasi_belanja);
                        $doc->exportField($this->bak_kegiatan);
                        $doc->exportField($this->softcopy_rka);
                        $doc->exportField($this->otsus);
                        $doc->exportField($this->qanun_perbup);
                        $doc->exportField($this->tindak_apbkp);
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

    // Add User ID filter
    public function addUserIDFilter($filter = "")
    {
        global $Security;
        $filterWrk = "";
        $id = (CurrentPageID() == "list") ? $this->CurrentAction : CurrentPageID();
        if (!$this->userIDAllow($id) && !$Security->isAdmin()) {
            $filterWrk = $Security->userIdList();
            if ($filterWrk != "") {
                $filterWrk = '`idd_user` IN (' . $filterWrk . ')';
            }
        }

        // Call User ID Filtering event
        $this->userIdFiltering($filterWrk);
        AddFilter($filter, $filterWrk);
        return $filter;
    }

    // User ID subquery
    public function getUserIDSubquery(&$fld, &$masterfld)
    {
        global $UserTable;
        $wrk = "";
        $sql = "SELECT " . $masterfld->Expression . " FROM `evaluasi`";
        $filter = $this->addUserIDFilter("");
        if ($filter != "") {
            $sql .= " WHERE " . $filter;
        }

        // List all values
        if ($rs = Conn($UserTable->Dbid)->executeQuery($sql)->fetchAll(\PDO::FETCH_NUM)) {
            foreach ($rs as $row) {
                if ($wrk != "") {
                    $wrk .= ",";
                }
                $wrk .= QuotedValue($row[0], $masterfld->DataType, Config("USER_TABLE_DBID"));
            }
        }
        if ($wrk != "") {
            $wrk = $fld->Expression . " IN (" . $wrk . ")";
        } else { // No User ID value found
            $wrk = "0=1";
        }
        return $wrk;
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
        } elseif ($fldparm == 'rpjmd') {
            $fldName = "rpjmd";
            $fileNameFld = "rpjmd";
        } elseif ($fldparm == 'rkpk') {
            $fldName = "rkpk";
            $fileNameFld = "rkpk";
        } elseif ($fldparm == 'skd_rkuappas') {
            $fldName = "skd_rkuappas";
            $fileNameFld = "skd_rkuappas";
        } elseif ($fldparm == 'kua') {
            $fldName = "kua";
            $fileNameFld = "kua";
        } elseif ($fldparm == 'ppas') {
            $fldName = "ppas";
            $fileNameFld = "ppas";
        } elseif ($fldparm == 'skd_rqanun') {
            $fldName = "skd_rqanun";
            $fileNameFld = "skd_rqanun";
        } elseif ($fldparm == 'nota_keuangan') {
            $fldName = "nota_keuangan";
            $fileNameFld = "nota_keuangan";
        } elseif ($fldparm == 'pengantar_nota') {
            $fldName = "pengantar_nota";
            $fileNameFld = "pengantar_nota";
        } elseif ($fldparm == 'risalah_sidang') {
            $fldName = "risalah_sidang";
            $fileNameFld = "risalah_sidang";
        } elseif ($fldparm == 'bap_apbk') {
            $fldName = "bap_apbk";
            $fileNameFld = "bap_apbk";
        } elseif ($fldparm == 'rq_apbk') {
            $fldName = "rq_apbk";
            $fileNameFld = "rq_apbk";
        } elseif ($fldparm == 'rp_penjabaran') {
            $fldName = "rp_penjabaran";
            $fileNameFld = "rp_penjabaran";
        } elseif ($fldparm == 'jadwal_proses') {
            $fldName = "jadwal_proses";
            $fileNameFld = "jadwal_proses";
        } elseif ($fldparm == 'sinkron_kebijakan') {
            $fldName = "sinkron_kebijakan";
            $fileNameFld = "sinkron_kebijakan";
        } elseif ($fldparm == 'konsistensi_program') {
            $fldName = "konsistensi_program";
            $fileNameFld = "konsistensi_program";
        } elseif ($fldparm == 'alokasi_pendidikan') {
            $fldName = "alokasi_pendidikan";
            $fileNameFld = "alokasi_pendidikan";
        } elseif ($fldparm == 'alokasi_kesehatan') {
            $fldName = "alokasi_kesehatan";
            $fileNameFld = "alokasi_kesehatan";
        } elseif ($fldparm == 'alokasi_belanja') {
            $fldName = "alokasi_belanja";
            $fileNameFld = "alokasi_belanja";
        } elseif ($fldparm == 'bak_kegiatan') {
            $fldName = "bak_kegiatan";
            $fileNameFld = "bak_kegiatan";
        } elseif ($fldparm == 'softcopy_rka') {
            $fldName = "softcopy_rka";
            $fileNameFld = "softcopy_rka";
        } elseif ($fldparm == 'otsus') {
            $fldName = "otsus";
            $fileNameFld = "otsus";
        } elseif ($fldparm == 'qanun_perbup') {
            $fldName = "qanun_perbup";
            $fileNameFld = "qanun_perbup";
        } elseif ($fldparm == 'tindak_apbkp') {
            $fldName = "tindak_apbkp";
            $fileNameFld = "tindak_apbkp";
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
