<?php

namespace PHPMaker2021\silpa;

// Page object
$EvaluasiList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var fevaluasilist;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "list";
    fevaluasilist = currentForm = new ew.Form("fevaluasilist", "list");
    fevaluasilist.formKeyCountName = '<?= $Page->FormKeyCountName ?>';
    loadjs.done("fevaluasilist");
});
var fevaluasilistsrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object for search
    fevaluasilistsrch = currentSearchForm = new ew.Form("fevaluasilistsrch");

    // Dynamic selection lists

    // Filters
    fevaluasilistsrch.filterList = <?= $Page->getFilterList() ?>;
    loadjs.done("fevaluasilistsrch");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php } ?>
<?php if (!$Page->isExport()) { ?>
<div class="btn-toolbar ew-toolbar">
<?php if ($Page->TotalRecords > 0 && $Page->ExportOptions->visible()) { ?>
<?php $Page->ExportOptions->render("body") ?>
<?php } ?>
<?php if ($Page->ImportOptions->visible()) { ?>
<?php $Page->ImportOptions->render("body") ?>
<?php } ?>
<?php if ($Page->SearchOptions->visible()) { ?>
<?php $Page->SearchOptions->render("body") ?>
<?php } ?>
<?php if ($Page->FilterOptions->visible()) { ?>
<?php $Page->FilterOptions->render("body") ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php
$Page->renderOtherOptions();
?>
<?php if ($Security->canSearch()) { ?>
<?php if (!$Page->isExport() && !$Page->CurrentAction) { ?>
<form name="fevaluasilistsrch" id="fevaluasilistsrch" class="form-inline ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>">
<div id="fevaluasilistsrch-search-panel" class="<?= $Page->SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="evaluasi">
    <div class="ew-extended-search">
<div id="xsr_<?= $Page->SearchRowCount + 1 ?>" class="ew-row d-sm-flex">
    <div class="ew-quick-search input-group">
        <input type="text" name="<?= Config("TABLE_BASIC_SEARCH") ?>" id="<?= Config("TABLE_BASIC_SEARCH") ?>" class="form-control" value="<?= HtmlEncode($Page->BasicSearch->getKeyword()) ?>" placeholder="<?= HtmlEncode($Language->phrase("Search")) ?>">
        <input type="hidden" name="<?= Config("TABLE_BASIC_SEARCH_TYPE") ?>" id="<?= Config("TABLE_BASIC_SEARCH_TYPE") ?>" value="<?= HtmlEncode($Page->BasicSearch->getType()) ?>">
        <div class="input-group-append">
            <button class="btn btn-primary" name="btn-submit" id="btn-submit" type="submit"><?= $Language->phrase("SearchBtn") ?></button>
            <button type="button" data-toggle="dropdown" class="btn btn-primary dropdown-toggle dropdown-toggle-split" aria-haspopup="true" aria-expanded="false"><span id="searchtype"><?= $Page->BasicSearch->getTypeNameShort() ?></span></button>
            <div class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item<?php if ($Page->BasicSearch->getType() == "") { ?> active<?php } ?>" href="#" onclick="return ew.setSearchType(this);"><?= $Language->phrase("QuickSearchAuto") ?></a>
                <a class="dropdown-item<?php if ($Page->BasicSearch->getType() == "=") { ?> active<?php } ?>" href="#" onclick="return ew.setSearchType(this, '=');"><?= $Language->phrase("QuickSearchExact") ?></a>
                <a class="dropdown-item<?php if ($Page->BasicSearch->getType() == "AND") { ?> active<?php } ?>" href="#" onclick="return ew.setSearchType(this, 'AND');"><?= $Language->phrase("QuickSearchAll") ?></a>
                <a class="dropdown-item<?php if ($Page->BasicSearch->getType() == "OR") { ?> active<?php } ?>" href="#" onclick="return ew.setSearchType(this, 'OR');"><?= $Language->phrase("QuickSearchAny") ?></a>
            </div>
        </div>
    </div>
</div>
    </div><!-- /.ew-extended-search -->
</div><!-- /.ew-search-panel -->
</form>
<?php } ?>
<?php } ?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<?php if ($Page->TotalRecords > 0 || $Page->CurrentAction) { ?>
<div class="card ew-card ew-grid<?php if ($Page->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> evaluasi">
<form name="fevaluasilist" id="fevaluasilist" class="form-inline ew-form ew-list-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="evaluasi">
<div id="gmp_evaluasi" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit()) { ?>
<table id="tbl_evaluasilist" class="table ew-table"><!-- .ew-table -->
<thead>
    <tr class="ew-table-header">
<?php
// Header row
$Page->RowType = ROWTYPE_HEADER;

// Render list options
$Page->renderListOptions();

// Render list options (header, left)
$Page->ListOptions->render("header", "left");
?>
<?php if ($Page->idd_evaluasi->Visible) { // idd_evaluasi ?>
        <th data-name="idd_evaluasi" class="<?= $Page->idd_evaluasi->headerCellClass() ?>"><div id="elh_evaluasi_idd_evaluasi" class="evaluasi_idd_evaluasi"><?= $Page->renderSort($Page->idd_evaluasi) ?></div></th>
<?php } ?>
<?php if ($Page->tanggal->Visible) { // tanggal ?>
        <th data-name="tanggal" class="<?= $Page->tanggal->headerCellClass() ?>"><div id="elh_evaluasi_tanggal" class="evaluasi_tanggal"><?= $Page->renderSort($Page->tanggal) ?></div></th>
<?php } ?>
<?php if ($Page->idd_wilayah->Visible) { // idd_wilayah ?>
        <th data-name="idd_wilayah" class="<?= $Page->idd_wilayah->headerCellClass() ?>"><div id="elh_evaluasi_idd_wilayah" class="evaluasi_idd_wilayah"><?= $Page->renderSort($Page->idd_wilayah) ?></div></th>
<?php } ?>
<?php if ($Page->kd_satker->Visible) { // kd_satker ?>
        <th data-name="kd_satker" class="<?= $Page->kd_satker->headerCellClass() ?>"><div id="elh_evaluasi_kd_satker" class="evaluasi_kd_satker"><?= $Page->renderSort($Page->kd_satker) ?></div></th>
<?php } ?>
<?php if ($Page->idd_tahapan->Visible) { // idd_tahapan ?>
        <th data-name="idd_tahapan" class="<?= $Page->idd_tahapan->headerCellClass() ?>"><div id="elh_evaluasi_idd_tahapan" class="evaluasi_idd_tahapan"><?= $Page->renderSort($Page->idd_tahapan) ?></div></th>
<?php } ?>
<?php if ($Page->tahun_anggaran->Visible) { // tahun_anggaran ?>
        <th data-name="tahun_anggaran" class="<?= $Page->tahun_anggaran->headerCellClass() ?>"><div id="elh_evaluasi_tahun_anggaran" class="evaluasi_tahun_anggaran"><?= $Page->renderSort($Page->tahun_anggaran) ?></div></th>
<?php } ?>
<?php if ($Page->surat_pengantar->Visible) { // surat_pengantar ?>
        <th data-name="surat_pengantar" class="<?= $Page->surat_pengantar->headerCellClass() ?>"><div id="elh_evaluasi_surat_pengantar" class="evaluasi_surat_pengantar"><?= $Page->renderSort($Page->surat_pengantar) ?></div></th>
<?php } ?>
<?php if ($Page->rpjmd->Visible) { // rpjmd ?>
        <th data-name="rpjmd" class="<?= $Page->rpjmd->headerCellClass() ?>"><div id="elh_evaluasi_rpjmd" class="evaluasi_rpjmd"><?= $Page->renderSort($Page->rpjmd) ?></div></th>
<?php } ?>
<?php if ($Page->rkpk->Visible) { // rkpk ?>
        <th data-name="rkpk" class="<?= $Page->rkpk->headerCellClass() ?>"><div id="elh_evaluasi_rkpk" class="evaluasi_rkpk"><?= $Page->renderSort($Page->rkpk) ?></div></th>
<?php } ?>
<?php if ($Page->skd_rkuappas->Visible) { // skd_rkuappas ?>
        <th data-name="skd_rkuappas" class="<?= $Page->skd_rkuappas->headerCellClass() ?>"><div id="elh_evaluasi_skd_rkuappas" class="evaluasi_skd_rkuappas"><?= $Page->renderSort($Page->skd_rkuappas) ?></div></th>
<?php } ?>
<?php if ($Page->kua->Visible) { // kua ?>
        <th data-name="kua" class="<?= $Page->kua->headerCellClass() ?>"><div id="elh_evaluasi_kua" class="evaluasi_kua"><?= $Page->renderSort($Page->kua) ?></div></th>
<?php } ?>
<?php if ($Page->ppas->Visible) { // ppas ?>
        <th data-name="ppas" class="<?= $Page->ppas->headerCellClass() ?>"><div id="elh_evaluasi_ppas" class="evaluasi_ppas"><?= $Page->renderSort($Page->ppas) ?></div></th>
<?php } ?>
<?php if ($Page->skd_rqanun->Visible) { // skd_rqanun ?>
        <th data-name="skd_rqanun" class="<?= $Page->skd_rqanun->headerCellClass() ?>"><div id="elh_evaluasi_skd_rqanun" class="evaluasi_skd_rqanun"><?= $Page->renderSort($Page->skd_rqanun) ?></div></th>
<?php } ?>
<?php if ($Page->nota_keuangan->Visible) { // nota_keuangan ?>
        <th data-name="nota_keuangan" class="<?= $Page->nota_keuangan->headerCellClass() ?>"><div id="elh_evaluasi_nota_keuangan" class="evaluasi_nota_keuangan"><?= $Page->renderSort($Page->nota_keuangan) ?></div></th>
<?php } ?>
<?php if ($Page->pengantar_nota->Visible) { // pengantar_nota ?>
        <th data-name="pengantar_nota" class="<?= $Page->pengantar_nota->headerCellClass() ?>"><div id="elh_evaluasi_pengantar_nota" class="evaluasi_pengantar_nota"><?= $Page->renderSort($Page->pengantar_nota) ?></div></th>
<?php } ?>
<?php if ($Page->risalah_sidang->Visible) { // risalah_sidang ?>
        <th data-name="risalah_sidang" class="<?= $Page->risalah_sidang->headerCellClass() ?>"><div id="elh_evaluasi_risalah_sidang" class="evaluasi_risalah_sidang"><?= $Page->renderSort($Page->risalah_sidang) ?></div></th>
<?php } ?>
<?php if ($Page->bap_apbk->Visible) { // bap_apbk ?>
        <th data-name="bap_apbk" class="<?= $Page->bap_apbk->headerCellClass() ?>"><div id="elh_evaluasi_bap_apbk" class="evaluasi_bap_apbk"><?= $Page->renderSort($Page->bap_apbk) ?></div></th>
<?php } ?>
<?php if ($Page->rq_apbk->Visible) { // rq_apbk ?>
        <th data-name="rq_apbk" class="<?= $Page->rq_apbk->headerCellClass() ?>"><div id="elh_evaluasi_rq_apbk" class="evaluasi_rq_apbk"><?= $Page->renderSort($Page->rq_apbk) ?></div></th>
<?php } ?>
<?php if ($Page->rp_penjabaran->Visible) { // rp_penjabaran ?>
        <th data-name="rp_penjabaran" class="<?= $Page->rp_penjabaran->headerCellClass() ?>"><div id="elh_evaluasi_rp_penjabaran" class="evaluasi_rp_penjabaran"><?= $Page->renderSort($Page->rp_penjabaran) ?></div></th>
<?php } ?>
<?php if ($Page->jadwal_proses->Visible) { // jadwal_proses ?>
        <th data-name="jadwal_proses" class="<?= $Page->jadwal_proses->headerCellClass() ?>"><div id="elh_evaluasi_jadwal_proses" class="evaluasi_jadwal_proses"><?= $Page->renderSort($Page->jadwal_proses) ?></div></th>
<?php } ?>
<?php if ($Page->sinkron_kebijakan->Visible) { // sinkron_kebijakan ?>
        <th data-name="sinkron_kebijakan" class="<?= $Page->sinkron_kebijakan->headerCellClass() ?>"><div id="elh_evaluasi_sinkron_kebijakan" class="evaluasi_sinkron_kebijakan"><?= $Page->renderSort($Page->sinkron_kebijakan) ?></div></th>
<?php } ?>
<?php if ($Page->konsistensi_program->Visible) { // konsistensi_program ?>
        <th data-name="konsistensi_program" class="<?= $Page->konsistensi_program->headerCellClass() ?>"><div id="elh_evaluasi_konsistensi_program" class="evaluasi_konsistensi_program"><?= $Page->renderSort($Page->konsistensi_program) ?></div></th>
<?php } ?>
<?php if ($Page->alokasi_pendidikan->Visible) { // alokasi_pendidikan ?>
        <th data-name="alokasi_pendidikan" class="<?= $Page->alokasi_pendidikan->headerCellClass() ?>"><div id="elh_evaluasi_alokasi_pendidikan" class="evaluasi_alokasi_pendidikan"><?= $Page->renderSort($Page->alokasi_pendidikan) ?></div></th>
<?php } ?>
<?php if ($Page->alokasi_kesehatan->Visible) { // alokasi_kesehatan ?>
        <th data-name="alokasi_kesehatan" class="<?= $Page->alokasi_kesehatan->headerCellClass() ?>"><div id="elh_evaluasi_alokasi_kesehatan" class="evaluasi_alokasi_kesehatan"><?= $Page->renderSort($Page->alokasi_kesehatan) ?></div></th>
<?php } ?>
<?php if ($Page->alokasi_belanja->Visible) { // alokasi_belanja ?>
        <th data-name="alokasi_belanja" class="<?= $Page->alokasi_belanja->headerCellClass() ?>"><div id="elh_evaluasi_alokasi_belanja" class="evaluasi_alokasi_belanja"><?= $Page->renderSort($Page->alokasi_belanja) ?></div></th>
<?php } ?>
<?php if ($Page->bak_kegiatan->Visible) { // bak_kegiatan ?>
        <th data-name="bak_kegiatan" class="<?= $Page->bak_kegiatan->headerCellClass() ?>"><div id="elh_evaluasi_bak_kegiatan" class="evaluasi_bak_kegiatan"><?= $Page->renderSort($Page->bak_kegiatan) ?></div></th>
<?php } ?>
<?php if ($Page->softcopy_rka->Visible) { // softcopy_rka ?>
        <th data-name="softcopy_rka" class="<?= $Page->softcopy_rka->headerCellClass() ?>"><div id="elh_evaluasi_softcopy_rka" class="evaluasi_softcopy_rka"><?= $Page->renderSort($Page->softcopy_rka) ?></div></th>
<?php } ?>
<?php if ($Page->otsus->Visible) { // otsus ?>
        <th data-name="otsus" class="<?= $Page->otsus->headerCellClass() ?>"><div id="elh_evaluasi_otsus" class="evaluasi_otsus"><?= $Page->renderSort($Page->otsus) ?></div></th>
<?php } ?>
<?php if ($Page->qanun_perbup->Visible) { // qanun_perbup ?>
        <th data-name="qanun_perbup" class="<?= $Page->qanun_perbup->headerCellClass() ?>"><div id="elh_evaluasi_qanun_perbup" class="evaluasi_qanun_perbup"><?= $Page->renderSort($Page->qanun_perbup) ?></div></th>
<?php } ?>
<?php if ($Page->tindak_apbkp->Visible) { // tindak_apbkp ?>
        <th data-name="tindak_apbkp" class="<?= $Page->tindak_apbkp->headerCellClass() ?>"><div id="elh_evaluasi_tindak_apbkp" class="evaluasi_tindak_apbkp"><?= $Page->renderSort($Page->tindak_apbkp) ?></div></th>
<?php } ?>
<?php if ($Page->status->Visible) { // status ?>
        <th data-name="status" class="<?= $Page->status->headerCellClass() ?>"><div id="elh_evaluasi_status" class="evaluasi_status"><?= $Page->renderSort($Page->status) ?></div></th>
<?php } ?>
<?php if ($Page->idd_user->Visible) { // idd_user ?>
        <th data-name="idd_user" class="<?= $Page->idd_user->headerCellClass() ?>"><div id="elh_evaluasi_idd_user" class="evaluasi_idd_user"><?= $Page->renderSort($Page->idd_user) ?></div></th>
<?php } ?>
<?php
// Render list options (header, right)
$Page->ListOptions->render("header", "right");
?>
    </tr>
</thead>
<tbody>
<?php
if ($Page->ExportAll && $Page->isExport()) {
    $Page->StopRecord = $Page->TotalRecords;
} else {
    // Set the last record to display
    if ($Page->TotalRecords > $Page->StartRecord + $Page->DisplayRecords - 1) {
        $Page->StopRecord = $Page->StartRecord + $Page->DisplayRecords - 1;
    } else {
        $Page->StopRecord = $Page->TotalRecords;
    }
}
$Page->RecordCount = $Page->StartRecord - 1;
if ($Page->Recordset && !$Page->Recordset->EOF) {
    // Nothing to do
} elseif (!$Page->AllowAddDeleteRow && $Page->StopRecord == 0) {
    $Page->StopRecord = $Page->GridAddRowCount;
}

// Initialize aggregate
$Page->RowType = ROWTYPE_AGGREGATEINIT;
$Page->resetAttributes();
$Page->renderRow();
while ($Page->RecordCount < $Page->StopRecord) {
    $Page->RecordCount++;
    if ($Page->RecordCount >= $Page->StartRecord) {
        $Page->RowCount++;

        // Set up key count
        $Page->KeyCount = $Page->RowIndex;

        // Init row class and style
        $Page->resetAttributes();
        $Page->CssClass = "";
        if ($Page->isGridAdd()) {
            $Page->loadRowValues(); // Load default values
            $Page->OldKey = "";
            $Page->setKey($Page->OldKey);
        } else {
            $Page->loadRowValues($Page->Recordset); // Load row values
            if ($Page->isGridEdit()) {
                $Page->OldKey = $Page->getKey(true); // Get from CurrentValue
                $Page->setKey($Page->OldKey);
            }
        }
        $Page->RowType = ROWTYPE_VIEW; // Render view

        // Set up row id / data-rowindex
        $Page->RowAttrs->merge(["data-rowindex" => $Page->RowCount, "id" => "r" . $Page->RowCount . "_evaluasi", "data-rowtype" => $Page->RowType]);

        // Render row
        $Page->renderRow();

        // Render list options
        $Page->renderListOptions();
?>
    <tr <?= $Page->rowAttributes() ?>>
<?php
// Render list options (body, left)
$Page->ListOptions->render("body", "left", $Page->RowCount);
?>
    <?php if ($Page->idd_evaluasi->Visible) { // idd_evaluasi ?>
        <td data-name="idd_evaluasi" <?= $Page->idd_evaluasi->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_evaluasi_idd_evaluasi">
<span<?= $Page->idd_evaluasi->viewAttributes() ?>>
<?= $Page->idd_evaluasi->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->tanggal->Visible) { // tanggal ?>
        <td data-name="tanggal" <?= $Page->tanggal->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_evaluasi_tanggal">
<span<?= $Page->tanggal->viewAttributes() ?>>
<?= $Page->tanggal->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->idd_wilayah->Visible) { // idd_wilayah ?>
        <td data-name="idd_wilayah" <?= $Page->idd_wilayah->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_evaluasi_idd_wilayah">
<span<?= $Page->idd_wilayah->viewAttributes() ?>>
<?= $Page->idd_wilayah->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->kd_satker->Visible) { // kd_satker ?>
        <td data-name="kd_satker" <?= $Page->kd_satker->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_evaluasi_kd_satker">
<span<?= $Page->kd_satker->viewAttributes() ?>>
<?= $Page->kd_satker->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->idd_tahapan->Visible) { // idd_tahapan ?>
        <td data-name="idd_tahapan" <?= $Page->idd_tahapan->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_evaluasi_idd_tahapan">
<span<?= $Page->idd_tahapan->viewAttributes() ?>>
<?= $Page->idd_tahapan->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->tahun_anggaran->Visible) { // tahun_anggaran ?>
        <td data-name="tahun_anggaran" <?= $Page->tahun_anggaran->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_evaluasi_tahun_anggaran">
<span<?= $Page->tahun_anggaran->viewAttributes() ?>>
<?= $Page->tahun_anggaran->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->surat_pengantar->Visible) { // surat_pengantar ?>
        <td data-name="surat_pengantar" <?= $Page->surat_pengantar->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_evaluasi_surat_pengantar">
<span<?= $Page->surat_pengantar->viewAttributes() ?>>
<?= GetFileViewTag($Page->surat_pengantar, $Page->surat_pengantar->getViewValue(), false) ?>
</span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->rpjmd->Visible) { // rpjmd ?>
        <td data-name="rpjmd" <?= $Page->rpjmd->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_evaluasi_rpjmd">
<span<?= $Page->rpjmd->viewAttributes() ?>>
<?= GetFileViewTag($Page->rpjmd, $Page->rpjmd->getViewValue(), false) ?>
</span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->rkpk->Visible) { // rkpk ?>
        <td data-name="rkpk" <?= $Page->rkpk->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_evaluasi_rkpk">
<span<?= $Page->rkpk->viewAttributes() ?>>
<?= GetFileViewTag($Page->rkpk, $Page->rkpk->getViewValue(), false) ?>
</span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->skd_rkuappas->Visible) { // skd_rkuappas ?>
        <td data-name="skd_rkuappas" <?= $Page->skd_rkuappas->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_evaluasi_skd_rkuappas">
<span<?= $Page->skd_rkuappas->viewAttributes() ?>>
<?= GetFileViewTag($Page->skd_rkuappas, $Page->skd_rkuappas->getViewValue(), false) ?>
</span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->kua->Visible) { // kua ?>
        <td data-name="kua" <?= $Page->kua->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_evaluasi_kua">
<span<?= $Page->kua->viewAttributes() ?>>
<?= GetFileViewTag($Page->kua, $Page->kua->getViewValue(), false) ?>
</span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->ppas->Visible) { // ppas ?>
        <td data-name="ppas" <?= $Page->ppas->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_evaluasi_ppas">
<span<?= $Page->ppas->viewAttributes() ?>>
<?= GetFileViewTag($Page->ppas, $Page->ppas->getViewValue(), false) ?>
</span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->skd_rqanun->Visible) { // skd_rqanun ?>
        <td data-name="skd_rqanun" <?= $Page->skd_rqanun->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_evaluasi_skd_rqanun">
<span<?= $Page->skd_rqanun->viewAttributes() ?>>
<?= GetFileViewTag($Page->skd_rqanun, $Page->skd_rqanun->getViewValue(), false) ?>
</span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->nota_keuangan->Visible) { // nota_keuangan ?>
        <td data-name="nota_keuangan" <?= $Page->nota_keuangan->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_evaluasi_nota_keuangan">
<span<?= $Page->nota_keuangan->viewAttributes() ?>>
<?= GetFileViewTag($Page->nota_keuangan, $Page->nota_keuangan->getViewValue(), false) ?>
</span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->pengantar_nota->Visible) { // pengantar_nota ?>
        <td data-name="pengantar_nota" <?= $Page->pengantar_nota->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_evaluasi_pengantar_nota">
<span<?= $Page->pengantar_nota->viewAttributes() ?>>
<?= GetFileViewTag($Page->pengantar_nota, $Page->pengantar_nota->getViewValue(), false) ?>
</span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->risalah_sidang->Visible) { // risalah_sidang ?>
        <td data-name="risalah_sidang" <?= $Page->risalah_sidang->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_evaluasi_risalah_sidang">
<span<?= $Page->risalah_sidang->viewAttributes() ?>>
<?= GetFileViewTag($Page->risalah_sidang, $Page->risalah_sidang->getViewValue(), false) ?>
</span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->bap_apbk->Visible) { // bap_apbk ?>
        <td data-name="bap_apbk" <?= $Page->bap_apbk->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_evaluasi_bap_apbk">
<span<?= $Page->bap_apbk->viewAttributes() ?>>
<?= GetFileViewTag($Page->bap_apbk, $Page->bap_apbk->getViewValue(), false) ?>
</span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->rq_apbk->Visible) { // rq_apbk ?>
        <td data-name="rq_apbk" <?= $Page->rq_apbk->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_evaluasi_rq_apbk">
<span<?= $Page->rq_apbk->viewAttributes() ?>>
<?= GetFileViewTag($Page->rq_apbk, $Page->rq_apbk->getViewValue(), false) ?>
</span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->rp_penjabaran->Visible) { // rp_penjabaran ?>
        <td data-name="rp_penjabaran" <?= $Page->rp_penjabaran->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_evaluasi_rp_penjabaran">
<span<?= $Page->rp_penjabaran->viewAttributes() ?>>
<?= GetFileViewTag($Page->rp_penjabaran, $Page->rp_penjabaran->getViewValue(), false) ?>
</span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->jadwal_proses->Visible) { // jadwal_proses ?>
        <td data-name="jadwal_proses" <?= $Page->jadwal_proses->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_evaluasi_jadwal_proses">
<span<?= $Page->jadwal_proses->viewAttributes() ?>>
<?= GetFileViewTag($Page->jadwal_proses, $Page->jadwal_proses->getViewValue(), false) ?>
</span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->sinkron_kebijakan->Visible) { // sinkron_kebijakan ?>
        <td data-name="sinkron_kebijakan" <?= $Page->sinkron_kebijakan->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_evaluasi_sinkron_kebijakan">
<span<?= $Page->sinkron_kebijakan->viewAttributes() ?>>
<?= GetFileViewTag($Page->sinkron_kebijakan, $Page->sinkron_kebijakan->getViewValue(), false) ?>
</span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->konsistensi_program->Visible) { // konsistensi_program ?>
        <td data-name="konsistensi_program" <?= $Page->konsistensi_program->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_evaluasi_konsistensi_program">
<span<?= $Page->konsistensi_program->viewAttributes() ?>>
<?= GetFileViewTag($Page->konsistensi_program, $Page->konsistensi_program->getViewValue(), false) ?>
</span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->alokasi_pendidikan->Visible) { // alokasi_pendidikan ?>
        <td data-name="alokasi_pendidikan" <?= $Page->alokasi_pendidikan->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_evaluasi_alokasi_pendidikan">
<span<?= $Page->alokasi_pendidikan->viewAttributes() ?>>
<?= GetFileViewTag($Page->alokasi_pendidikan, $Page->alokasi_pendidikan->getViewValue(), false) ?>
</span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->alokasi_kesehatan->Visible) { // alokasi_kesehatan ?>
        <td data-name="alokasi_kesehatan" <?= $Page->alokasi_kesehatan->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_evaluasi_alokasi_kesehatan">
<span<?= $Page->alokasi_kesehatan->viewAttributes() ?>>
<?= GetFileViewTag($Page->alokasi_kesehatan, $Page->alokasi_kesehatan->getViewValue(), false) ?>
</span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->alokasi_belanja->Visible) { // alokasi_belanja ?>
        <td data-name="alokasi_belanja" <?= $Page->alokasi_belanja->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_evaluasi_alokasi_belanja">
<span<?= $Page->alokasi_belanja->viewAttributes() ?>>
<?= GetFileViewTag($Page->alokasi_belanja, $Page->alokasi_belanja->getViewValue(), false) ?>
</span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->bak_kegiatan->Visible) { // bak_kegiatan ?>
        <td data-name="bak_kegiatan" <?= $Page->bak_kegiatan->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_evaluasi_bak_kegiatan">
<span<?= $Page->bak_kegiatan->viewAttributes() ?>>
<?= GetFileViewTag($Page->bak_kegiatan, $Page->bak_kegiatan->getViewValue(), false) ?>
</span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->softcopy_rka->Visible) { // softcopy_rka ?>
        <td data-name="softcopy_rka" <?= $Page->softcopy_rka->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_evaluasi_softcopy_rka">
<span<?= $Page->softcopy_rka->viewAttributes() ?>>
<?= GetFileViewTag($Page->softcopy_rka, $Page->softcopy_rka->getViewValue(), false) ?>
</span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->otsus->Visible) { // otsus ?>
        <td data-name="otsus" <?= $Page->otsus->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_evaluasi_otsus">
<span<?= $Page->otsus->viewAttributes() ?>>
<?= GetFileViewTag($Page->otsus, $Page->otsus->getViewValue(), false) ?>
</span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->qanun_perbup->Visible) { // qanun_perbup ?>
        <td data-name="qanun_perbup" <?= $Page->qanun_perbup->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_evaluasi_qanun_perbup">
<span<?= $Page->qanun_perbup->viewAttributes() ?>>
<?= GetFileViewTag($Page->qanun_perbup, $Page->qanun_perbup->getViewValue(), false) ?>
</span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->tindak_apbkp->Visible) { // tindak_apbkp ?>
        <td data-name="tindak_apbkp" <?= $Page->tindak_apbkp->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_evaluasi_tindak_apbkp">
<span<?= $Page->tindak_apbkp->viewAttributes() ?>>
<?= GetFileViewTag($Page->tindak_apbkp, $Page->tindak_apbkp->getViewValue(), false) ?>
</span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->status->Visible) { // status ?>
        <td data-name="status" <?= $Page->status->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_evaluasi_status">
<span<?= $Page->status->viewAttributes() ?>>
<?= $Page->status->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->idd_user->Visible) { // idd_user ?>
        <td data-name="idd_user" <?= $Page->idd_user->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_evaluasi_idd_user">
<span<?= $Page->idd_user->viewAttributes() ?>>
<?= $Page->idd_user->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
<?php
// Render list options (body, right)
$Page->ListOptions->render("body", "right", $Page->RowCount);
?>
    </tr>
<?php
    }
    if (!$Page->isGridAdd()) {
        $Page->Recordset->moveNext();
    }
}
?>
</tbody>
</table><!-- /.ew-table -->
<?php } ?>
</div><!-- /.ew-grid-middle-panel -->
<?php if (!$Page->CurrentAction) { ?>
<input type="hidden" name="action" id="action" value="">
<?php } ?>
</form><!-- /.ew-list-form -->
<?php
// Close recordset
if ($Page->Recordset) {
    $Page->Recordset->close();
}
?>
<?php if (!$Page->isExport()) { ?>
<div class="card-footer ew-grid-lower-panel">
<?php if (!$Page->isGridAdd()) { ?>
<form name="ew-pager-form" class="form-inline ew-form ew-pager-form" action="<?= CurrentPageUrl(false) ?>">
<?= $Page->Pager->render() ?>
</form>
<?php } ?>
<div class="ew-list-other-options">
<?php $Page->OtherOptions->render("body", "bottom") ?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
</div><!-- /.ew-grid -->
<?php } ?>
<?php if ($Page->TotalRecords == 0 && !$Page->CurrentAction) { // Show other options ?>
<div class="ew-list-other-options">
<?php $Page->OtherOptions->render("body") ?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<?php if (!$Page->isExport()) { ?>
<script>
// Field event handlers
loadjs.ready("head", function() {
    ew.addEventHandlers("evaluasi");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
