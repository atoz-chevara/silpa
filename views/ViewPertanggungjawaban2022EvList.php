<?php

namespace PHPMaker2021\silpa;

// Page object
$ViewPertanggungjawaban2022EvList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var fview_pertanggungjawaban_2022_evlist;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "list";
    fview_pertanggungjawaban_2022_evlist = currentForm = new ew.Form("fview_pertanggungjawaban_2022_evlist", "list");
    fview_pertanggungjawaban_2022_evlist.formKeyCountName = '<?= $Page->FormKeyCountName ?>';
    loadjs.done("fview_pertanggungjawaban_2022_evlist");
});
var fview_pertanggungjawaban_2022_evlistsrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object for search
    fview_pertanggungjawaban_2022_evlistsrch = currentSearchForm = new ew.Form("fview_pertanggungjawaban_2022_evlistsrch");

    // Dynamic selection lists

    // Filters
    fview_pertanggungjawaban_2022_evlistsrch.filterList = <?= $Page->getFilterList() ?>;
    loadjs.done("fview_pertanggungjawaban_2022_evlistsrch");
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
<form name="fview_pertanggungjawaban_2022_evlistsrch" id="fview_pertanggungjawaban_2022_evlistsrch" class="form-inline ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>">
<div id="fview_pertanggungjawaban_2022_evlistsrch-search-panel" class="<?= $Page->SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="view_pertanggungjawaban_2022_ev">
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
<div class="card ew-card ew-grid<?php if ($Page->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> view_pertanggungjawaban_2022_ev">
<form name="fview_pertanggungjawaban_2022_evlist" id="fview_pertanggungjawaban_2022_evlist" class="form-inline ew-form ew-list-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="view_pertanggungjawaban_2022_ev">
<div id="gmp_view_pertanggungjawaban_2022_ev" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit()) { ?>
<table id="tbl_view_pertanggungjawaban_2022_evlist" class="table ew-table"><!-- .ew-table -->
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
<?php if ($Page->kd_satker->Visible) { // kd_satker ?>
        <th data-name="kd_satker" class="<?= $Page->kd_satker->headerCellClass() ?>"><div id="elh_view_pertanggungjawaban_2022_ev_kd_satker" class="view_pertanggungjawaban_2022_ev_kd_satker"><?= $Page->renderSort($Page->kd_satker) ?></div></th>
<?php } ?>
<?php if ($Page->idd_tahapan->Visible) { // idd_tahapan ?>
        <th data-name="idd_tahapan" class="<?= $Page->idd_tahapan->headerCellClass() ?>"><div id="elh_view_pertanggungjawaban_2022_ev_idd_tahapan" class="view_pertanggungjawaban_2022_ev_idd_tahapan"><?= $Page->renderSort($Page->idd_tahapan) ?></div></th>
<?php } ?>
<?php if ($Page->tahun_anggaran->Visible) { // tahun_anggaran ?>
        <th data-name="tahun_anggaran" class="<?= $Page->tahun_anggaran->headerCellClass() ?>"><div id="elh_view_pertanggungjawaban_2022_ev_tahun_anggaran" class="view_pertanggungjawaban_2022_ev_tahun_anggaran"><?= $Page->renderSort($Page->tahun_anggaran) ?></div></th>
<?php } ?>
<?php if ($Page->surat_pengantar->Visible) { // surat_pengantar ?>
        <th data-name="surat_pengantar" class="<?= $Page->surat_pengantar->headerCellClass() ?>"><div id="elh_view_pertanggungjawaban_2022_ev_surat_pengantar" class="view_pertanggungjawaban_2022_ev_surat_pengantar"><?= $Page->renderSort($Page->surat_pengantar) ?></div></th>
<?php } ?>
<?php if ($Page->skd_rqanunpert->Visible) { // skd_rqanunpert ?>
        <th data-name="skd_rqanunpert" class="<?= $Page->skd_rqanunpert->headerCellClass() ?>"><div id="elh_view_pertanggungjawaban_2022_ev_skd_rqanunpert" class="view_pertanggungjawaban_2022_ev_skd_rqanunpert"><?= $Page->renderSort($Page->skd_rqanunpert) ?></div></th>
<?php } ?>
<?php if ($Page->rqanun_apbkpert->Visible) { // rqanun_apbkpert ?>
        <th data-name="rqanun_apbkpert" class="<?= $Page->rqanun_apbkpert->headerCellClass() ?>"><div id="elh_view_pertanggungjawaban_2022_ev_rqanun_apbkpert" class="view_pertanggungjawaban_2022_ev_rqanun_apbkpert"><?= $Page->renderSort($Page->rqanun_apbkpert) ?></div></th>
<?php } ?>
<?php if ($Page->rperbup_apbkpert->Visible) { // rperbup_apbkpert ?>
        <th data-name="rperbup_apbkpert" class="<?= $Page->rperbup_apbkpert->headerCellClass() ?>"><div id="elh_view_pertanggungjawaban_2022_ev_rperbup_apbkpert" class="view_pertanggungjawaban_2022_ev_rperbup_apbkpert"><?= $Page->renderSort($Page->rperbup_apbkpert) ?></div></th>
<?php } ?>
<?php if ($Page->pbkdd_apbkpert->Visible) { // pbkdd_apbkpert ?>
        <th data-name="pbkdd_apbkpert" class="<?= $Page->pbkdd_apbkpert->headerCellClass() ?>"><div id="elh_view_pertanggungjawaban_2022_ev_pbkdd_apbkpert" class="view_pertanggungjawaban_2022_ev_pbkdd_apbkpert"><?= $Page->renderSort($Page->pbkdd_apbkpert) ?></div></th>
<?php } ?>
<?php if ($Page->risalah_sidang->Visible) { // risalah_sidang ?>
        <th data-name="risalah_sidang" class="<?= $Page->risalah_sidang->headerCellClass() ?>"><div id="elh_view_pertanggungjawaban_2022_ev_risalah_sidang" class="view_pertanggungjawaban_2022_ev_risalah_sidang"><?= $Page->renderSort($Page->risalah_sidang) ?></div></th>
<?php } ?>
<?php if ($Page->absen_peserta->Visible) { // absen_peserta ?>
        <th data-name="absen_peserta" class="<?= $Page->absen_peserta->headerCellClass() ?>"><div id="elh_view_pertanggungjawaban_2022_ev_absen_peserta" class="view_pertanggungjawaban_2022_ev_absen_peserta"><?= $Page->renderSort($Page->absen_peserta) ?></div></th>
<?php } ?>
<?php if ($Page->neraca->Visible) { // neraca ?>
        <th data-name="neraca" class="<?= $Page->neraca->headerCellClass() ?>"><div id="elh_view_pertanggungjawaban_2022_ev_neraca" class="view_pertanggungjawaban_2022_ev_neraca"><?= $Page->renderSort($Page->neraca) ?></div></th>
<?php } ?>
<?php if ($Page->lra->Visible) { // lra ?>
        <th data-name="lra" class="<?= $Page->lra->headerCellClass() ?>"><div id="elh_view_pertanggungjawaban_2022_ev_lra" class="view_pertanggungjawaban_2022_ev_lra"><?= $Page->renderSort($Page->lra) ?></div></th>
<?php } ?>
<?php if ($Page->calk->Visible) { // calk ?>
        <th data-name="calk" class="<?= $Page->calk->headerCellClass() ?>"><div id="elh_view_pertanggungjawaban_2022_ev_calk" class="view_pertanggungjawaban_2022_ev_calk"><?= $Page->renderSort($Page->calk) ?></div></th>
<?php } ?>
<?php if ($Page->lo->Visible) { // lo ?>
        <th data-name="lo" class="<?= $Page->lo->headerCellClass() ?>"><div id="elh_view_pertanggungjawaban_2022_ev_lo" class="view_pertanggungjawaban_2022_ev_lo"><?= $Page->renderSort($Page->lo) ?></div></th>
<?php } ?>
<?php if ($Page->lpe->Visible) { // lpe ?>
        <th data-name="lpe" class="<?= $Page->lpe->headerCellClass() ?>"><div id="elh_view_pertanggungjawaban_2022_ev_lpe" class="view_pertanggungjawaban_2022_ev_lpe"><?= $Page->renderSort($Page->lpe) ?></div></th>
<?php } ?>
<?php if ($Page->lpsal->Visible) { // lpsal ?>
        <th data-name="lpsal" class="<?= $Page->lpsal->headerCellClass() ?>"><div id="elh_view_pertanggungjawaban_2022_ev_lpsal" class="view_pertanggungjawaban_2022_ev_lpsal"><?= $Page->renderSort($Page->lpsal) ?></div></th>
<?php } ?>
<?php if ($Page->lak->Visible) { // lak ?>
        <th data-name="lak" class="<?= $Page->lak->headerCellClass() ?>"><div id="elh_view_pertanggungjawaban_2022_ev_lak" class="view_pertanggungjawaban_2022_ev_lak"><?= $Page->renderSort($Page->lak) ?></div></th>
<?php } ?>
<?php if ($Page->laporan_pemeriksaan->Visible) { // laporan_pemeriksaan ?>
        <th data-name="laporan_pemeriksaan" class="<?= $Page->laporan_pemeriksaan->headerCellClass() ?>"><div id="elh_view_pertanggungjawaban_2022_ev_laporan_pemeriksaan" class="view_pertanggungjawaban_2022_ev_laporan_pemeriksaan"><?= $Page->renderSort($Page->laporan_pemeriksaan) ?></div></th>
<?php } ?>
<?php if ($Page->status->Visible) { // status ?>
        <th data-name="status" class="<?= $Page->status->headerCellClass() ?>"><div id="elh_view_pertanggungjawaban_2022_ev_status" class="view_pertanggungjawaban_2022_ev_status"><?= $Page->renderSort($Page->status) ?></div></th>
<?php } ?>
<?php if ($Page->tanggal_upload->Visible) { // tanggal_upload ?>
        <th data-name="tanggal_upload" class="<?= $Page->tanggal_upload->headerCellClass() ?>"><div id="elh_view_pertanggungjawaban_2022_ev_tanggal_upload" class="view_pertanggungjawaban_2022_ev_tanggal_upload"><?= $Page->renderSort($Page->tanggal_upload) ?></div></th>
<?php } ?>
<?php if ($Page->tanggal_update->Visible) { // tanggal_update ?>
        <th data-name="tanggal_update" class="<?= $Page->tanggal_update->headerCellClass() ?>"><div id="elh_view_pertanggungjawaban_2022_ev_tanggal_update" class="view_pertanggungjawaban_2022_ev_tanggal_update"><?= $Page->renderSort($Page->tanggal_update) ?></div></th>
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
        $Page->RowAttrs->merge(["data-rowindex" => $Page->RowCount, "id" => "r" . $Page->RowCount . "_view_pertanggungjawaban_2022_ev", "data-rowtype" => $Page->RowType]);

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
    <?php if ($Page->kd_satker->Visible) { // kd_satker ?>
        <td data-name="kd_satker" <?= $Page->kd_satker->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_view_pertanggungjawaban_2022_ev_kd_satker">
<span<?= $Page->kd_satker->viewAttributes() ?>>
<?= $Page->kd_satker->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->idd_tahapan->Visible) { // idd_tahapan ?>
        <td data-name="idd_tahapan" <?= $Page->idd_tahapan->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_view_pertanggungjawaban_2022_ev_idd_tahapan">
<span<?= $Page->idd_tahapan->viewAttributes() ?>>
<?= $Page->idd_tahapan->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->tahun_anggaran->Visible) { // tahun_anggaran ?>
        <td data-name="tahun_anggaran" <?= $Page->tahun_anggaran->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_view_pertanggungjawaban_2022_ev_tahun_anggaran">
<span<?= $Page->tahun_anggaran->viewAttributes() ?>>
<?= $Page->tahun_anggaran->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->surat_pengantar->Visible) { // surat_pengantar ?>
        <td data-name="surat_pengantar" <?= $Page->surat_pengantar->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_view_pertanggungjawaban_2022_ev_surat_pengantar">
<span<?= $Page->surat_pengantar->viewAttributes() ?>>
<?= GetFileViewTag($Page->surat_pengantar, $Page->surat_pengantar->getViewValue(), false) ?>
</span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->skd_rqanunpert->Visible) { // skd_rqanunpert ?>
        <td data-name="skd_rqanunpert" <?= $Page->skd_rqanunpert->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_view_pertanggungjawaban_2022_ev_skd_rqanunpert">
<span<?= $Page->skd_rqanunpert->viewAttributes() ?>>
<?= GetFileViewTag($Page->skd_rqanunpert, $Page->skd_rqanunpert->getViewValue(), false) ?>
</span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->rqanun_apbkpert->Visible) { // rqanun_apbkpert ?>
        <td data-name="rqanun_apbkpert" <?= $Page->rqanun_apbkpert->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_view_pertanggungjawaban_2022_ev_rqanun_apbkpert">
<span<?= $Page->rqanun_apbkpert->viewAttributes() ?>>
<?= GetFileViewTag($Page->rqanun_apbkpert, $Page->rqanun_apbkpert->getViewValue(), false) ?>
</span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->rperbup_apbkpert->Visible) { // rperbup_apbkpert ?>
        <td data-name="rperbup_apbkpert" <?= $Page->rperbup_apbkpert->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_view_pertanggungjawaban_2022_ev_rperbup_apbkpert">
<span<?= $Page->rperbup_apbkpert->viewAttributes() ?>>
<?= GetFileViewTag($Page->rperbup_apbkpert, $Page->rperbup_apbkpert->getViewValue(), false) ?>
</span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->pbkdd_apbkpert->Visible) { // pbkdd_apbkpert ?>
        <td data-name="pbkdd_apbkpert" <?= $Page->pbkdd_apbkpert->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_view_pertanggungjawaban_2022_ev_pbkdd_apbkpert">
<span<?= $Page->pbkdd_apbkpert->viewAttributes() ?>>
<?= GetFileViewTag($Page->pbkdd_apbkpert, $Page->pbkdd_apbkpert->getViewValue(), false) ?>
</span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->risalah_sidang->Visible) { // risalah_sidang ?>
        <td data-name="risalah_sidang" <?= $Page->risalah_sidang->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_view_pertanggungjawaban_2022_ev_risalah_sidang">
<span<?= $Page->risalah_sidang->viewAttributes() ?>>
<?= GetFileViewTag($Page->risalah_sidang, $Page->risalah_sidang->getViewValue(), false) ?>
</span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->absen_peserta->Visible) { // absen_peserta ?>
        <td data-name="absen_peserta" <?= $Page->absen_peserta->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_view_pertanggungjawaban_2022_ev_absen_peserta">
<span<?= $Page->absen_peserta->viewAttributes() ?>>
<?= GetFileViewTag($Page->absen_peserta, $Page->absen_peserta->getViewValue(), false) ?>
</span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->neraca->Visible) { // neraca ?>
        <td data-name="neraca" <?= $Page->neraca->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_view_pertanggungjawaban_2022_ev_neraca">
<span<?= $Page->neraca->viewAttributes() ?>>
<?= GetFileViewTag($Page->neraca, $Page->neraca->getViewValue(), false) ?>
</span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->lra->Visible) { // lra ?>
        <td data-name="lra" <?= $Page->lra->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_view_pertanggungjawaban_2022_ev_lra">
<span<?= $Page->lra->viewAttributes() ?>>
<?= GetFileViewTag($Page->lra, $Page->lra->getViewValue(), false) ?>
</span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->calk->Visible) { // calk ?>
        <td data-name="calk" <?= $Page->calk->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_view_pertanggungjawaban_2022_ev_calk">
<span<?= $Page->calk->viewAttributes() ?>>
<?= GetFileViewTag($Page->calk, $Page->calk->getViewValue(), false) ?>
</span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->lo->Visible) { // lo ?>
        <td data-name="lo" <?= $Page->lo->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_view_pertanggungjawaban_2022_ev_lo">
<span<?= $Page->lo->viewAttributes() ?>>
<?= GetFileViewTag($Page->lo, $Page->lo->getViewValue(), false) ?>
</span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->lpe->Visible) { // lpe ?>
        <td data-name="lpe" <?= $Page->lpe->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_view_pertanggungjawaban_2022_ev_lpe">
<span<?= $Page->lpe->viewAttributes() ?>>
<?= GetFileViewTag($Page->lpe, $Page->lpe->getViewValue(), false) ?>
</span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->lpsal->Visible) { // lpsal ?>
        <td data-name="lpsal" <?= $Page->lpsal->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_view_pertanggungjawaban_2022_ev_lpsal">
<span<?= $Page->lpsal->viewAttributes() ?>>
<?= GetFileViewTag($Page->lpsal, $Page->lpsal->getViewValue(), false) ?>
</span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->lak->Visible) { // lak ?>
        <td data-name="lak" <?= $Page->lak->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_view_pertanggungjawaban_2022_ev_lak">
<span<?= $Page->lak->viewAttributes() ?>>
<?= GetFileViewTag($Page->lak, $Page->lak->getViewValue(), false) ?>
</span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->laporan_pemeriksaan->Visible) { // laporan_pemeriksaan ?>
        <td data-name="laporan_pemeriksaan" <?= $Page->laporan_pemeriksaan->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_view_pertanggungjawaban_2022_ev_laporan_pemeriksaan">
<span<?= $Page->laporan_pemeriksaan->viewAttributes() ?>>
<?= GetFileViewTag($Page->laporan_pemeriksaan, $Page->laporan_pemeriksaan->getViewValue(), false) ?>
</span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->status->Visible) { // status ?>
        <td data-name="status" <?= $Page->status->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_view_pertanggungjawaban_2022_ev_status">
<span<?= $Page->status->viewAttributes() ?>>
<?= $Page->status->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->tanggal_upload->Visible) { // tanggal_upload ?>
        <td data-name="tanggal_upload" <?= $Page->tanggal_upload->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_view_pertanggungjawaban_2022_ev_tanggal_upload">
<span<?= $Page->tanggal_upload->viewAttributes() ?>>
<?= $Page->tanggal_upload->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->tanggal_update->Visible) { // tanggal_update ?>
        <td data-name="tanggal_update" <?= $Page->tanggal_update->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_view_pertanggungjawaban_2022_ev_tanggal_update">
<span<?= $Page->tanggal_update->viewAttributes() ?>>
<?= $Page->tanggal_update->getViewValue() ?></span>
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
    ew.addEventHandlers("view_pertanggungjawaban_2022_ev");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
