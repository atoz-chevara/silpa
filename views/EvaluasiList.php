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
<?php if ($Page->kd_satker->Visible) { // kd_satker ?>
        <th data-name="kd_satker" class="<?= $Page->kd_satker->headerCellClass() ?>"><div id="elh_evaluasi_kd_satker" class="evaluasi_kd_satker"><?= $Page->renderSort($Page->kd_satker) ?></div></th>
<?php } ?>
<?php if ($Page->idd_tahapan->Visible) { // idd_tahapan ?>
        <th data-name="idd_tahapan" class="<?= $Page->idd_tahapan->headerCellClass() ?>"><div id="elh_evaluasi_idd_tahapan" class="evaluasi_idd_tahapan"><?= $Page->renderSort($Page->idd_tahapan) ?></div></th>
<?php } ?>
<?php if ($Page->tahun_anggaran->Visible) { // tahun_anggaran ?>
        <th data-name="tahun_anggaran" class="<?= $Page->tahun_anggaran->headerCellClass() ?>"><div id="elh_evaluasi_tahun_anggaran" class="evaluasi_tahun_anggaran"><?= $Page->renderSort($Page->tahun_anggaran) ?></div></th>
<?php } ?>
<?php if ($Page->idd_wilayah->Visible) { // idd_wilayah ?>
        <th data-name="idd_wilayah" class="<?= $Page->idd_wilayah->headerCellClass() ?>"><div id="elh_evaluasi_idd_wilayah" class="evaluasi_idd_wilayah"><?= $Page->renderSort($Page->idd_wilayah) ?></div></th>
<?php } ?>
<?php if ($Page->file_01->Visible) { // file_01 ?>
        <th data-name="file_01" class="<?= $Page->file_01->headerCellClass() ?>"><div id="elh_evaluasi_file_01" class="evaluasi_file_01"><?= $Page->renderSort($Page->file_01) ?></div></th>
<?php } ?>
<?php if ($Page->file_02->Visible) { // file_02 ?>
        <th data-name="file_02" class="<?= $Page->file_02->headerCellClass() ?>"><div id="elh_evaluasi_file_02" class="evaluasi_file_02"><?= $Page->renderSort($Page->file_02) ?></div></th>
<?php } ?>
<?php if ($Page->file_03->Visible) { // file_03 ?>
        <th data-name="file_03" class="<?= $Page->file_03->headerCellClass() ?>"><div id="elh_evaluasi_file_03" class="evaluasi_file_03"><?= $Page->renderSort($Page->file_03) ?></div></th>
<?php } ?>
<?php if ($Page->file_04->Visible) { // file_04 ?>
        <th data-name="file_04" class="<?= $Page->file_04->headerCellClass() ?>"><div id="elh_evaluasi_file_04" class="evaluasi_file_04"><?= $Page->renderSort($Page->file_04) ?></div></th>
<?php } ?>
<?php if ($Page->file_05->Visible) { // file_05 ?>
        <th data-name="file_05" class="<?= $Page->file_05->headerCellClass() ?>"><div id="elh_evaluasi_file_05" class="evaluasi_file_05"><?= $Page->renderSort($Page->file_05) ?></div></th>
<?php } ?>
<?php if ($Page->file_06->Visible) { // file_06 ?>
        <th data-name="file_06" class="<?= $Page->file_06->headerCellClass() ?>"><div id="elh_evaluasi_file_06" class="evaluasi_file_06"><?= $Page->renderSort($Page->file_06) ?></div></th>
<?php } ?>
<?php if ($Page->file_07->Visible) { // file_07 ?>
        <th data-name="file_07" class="<?= $Page->file_07->headerCellClass() ?>"><div id="elh_evaluasi_file_07" class="evaluasi_file_07"><?= $Page->renderSort($Page->file_07) ?></div></th>
<?php } ?>
<?php if ($Page->file_08->Visible) { // file_08 ?>
        <th data-name="file_08" class="<?= $Page->file_08->headerCellClass() ?>"><div id="elh_evaluasi_file_08" class="evaluasi_file_08"><?= $Page->renderSort($Page->file_08) ?></div></th>
<?php } ?>
<?php if ($Page->file_09->Visible) { // file_09 ?>
        <th data-name="file_09" class="<?= $Page->file_09->headerCellClass() ?>"><div id="elh_evaluasi_file_09" class="evaluasi_file_09"><?= $Page->renderSort($Page->file_09) ?></div></th>
<?php } ?>
<?php if ($Page->file_10->Visible) { // file_10 ?>
        <th data-name="file_10" class="<?= $Page->file_10->headerCellClass() ?>"><div id="elh_evaluasi_file_10" class="evaluasi_file_10"><?= $Page->renderSort($Page->file_10) ?></div></th>
<?php } ?>
<?php if ($Page->file_11->Visible) { // file_11 ?>
        <th data-name="file_11" class="<?= $Page->file_11->headerCellClass() ?>"><div id="elh_evaluasi_file_11" class="evaluasi_file_11"><?= $Page->renderSort($Page->file_11) ?></div></th>
<?php } ?>
<?php if ($Page->file_12->Visible) { // file_12 ?>
        <th data-name="file_12" class="<?= $Page->file_12->headerCellClass() ?>"><div id="elh_evaluasi_file_12" class="evaluasi_file_12"><?= $Page->renderSort($Page->file_12) ?></div></th>
<?php } ?>
<?php if ($Page->file_13->Visible) { // file_13 ?>
        <th data-name="file_13" class="<?= $Page->file_13->headerCellClass() ?>"><div id="elh_evaluasi_file_13" class="evaluasi_file_13"><?= $Page->renderSort($Page->file_13) ?></div></th>
<?php } ?>
<?php if ($Page->file_14->Visible) { // file_14 ?>
        <th data-name="file_14" class="<?= $Page->file_14->headerCellClass() ?>"><div id="elh_evaluasi_file_14" class="evaluasi_file_14"><?= $Page->renderSort($Page->file_14) ?></div></th>
<?php } ?>
<?php if ($Page->file_15->Visible) { // file_15 ?>
        <th data-name="file_15" class="<?= $Page->file_15->headerCellClass() ?>"><div id="elh_evaluasi_file_15" class="evaluasi_file_15"><?= $Page->renderSort($Page->file_15) ?></div></th>
<?php } ?>
<?php if ($Page->file_16->Visible) { // file_16 ?>
        <th data-name="file_16" class="<?= $Page->file_16->headerCellClass() ?>"><div id="elh_evaluasi_file_16" class="evaluasi_file_16"><?= $Page->renderSort($Page->file_16) ?></div></th>
<?php } ?>
<?php if ($Page->file_17->Visible) { // file_17 ?>
        <th data-name="file_17" class="<?= $Page->file_17->headerCellClass() ?>"><div id="elh_evaluasi_file_17" class="evaluasi_file_17"><?= $Page->renderSort($Page->file_17) ?></div></th>
<?php } ?>
<?php if ($Page->file_18->Visible) { // file_18 ?>
        <th data-name="file_18" class="<?= $Page->file_18->headerCellClass() ?>"><div id="elh_evaluasi_file_18" class="evaluasi_file_18"><?= $Page->renderSort($Page->file_18) ?></div></th>
<?php } ?>
<?php if ($Page->file_19->Visible) { // file_19 ?>
        <th data-name="file_19" class="<?= $Page->file_19->headerCellClass() ?>"><div id="elh_evaluasi_file_19" class="evaluasi_file_19"><?= $Page->renderSort($Page->file_19) ?></div></th>
<?php } ?>
<?php if ($Page->file_20->Visible) { // file_20 ?>
        <th data-name="file_20" class="<?= $Page->file_20->headerCellClass() ?>"><div id="elh_evaluasi_file_20" class="evaluasi_file_20"><?= $Page->renderSort($Page->file_20) ?></div></th>
<?php } ?>
<?php if ($Page->file_21->Visible) { // file_21 ?>
        <th data-name="file_21" class="<?= $Page->file_21->headerCellClass() ?>"><div id="elh_evaluasi_file_21" class="evaluasi_file_21"><?= $Page->renderSort($Page->file_21) ?></div></th>
<?php } ?>
<?php if ($Page->file_22->Visible) { // file_22 ?>
        <th data-name="file_22" class="<?= $Page->file_22->headerCellClass() ?>"><div id="elh_evaluasi_file_22" class="evaluasi_file_22"><?= $Page->renderSort($Page->file_22) ?></div></th>
<?php } ?>
<?php if ($Page->file_23->Visible) { // file_23 ?>
        <th data-name="file_23" class="<?= $Page->file_23->headerCellClass() ?>"><div id="elh_evaluasi_file_23" class="evaluasi_file_23"><?= $Page->renderSort($Page->file_23) ?></div></th>
<?php } ?>
<?php if ($Page->file_24->Visible) { // file_24 ?>
        <th data-name="file_24" class="<?= $Page->file_24->headerCellClass() ?>"><div id="elh_evaluasi_file_24" class="evaluasi_file_24"><?= $Page->renderSort($Page->file_24) ?></div></th>
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
    <?php if ($Page->idd_wilayah->Visible) { // idd_wilayah ?>
        <td data-name="idd_wilayah" <?= $Page->idd_wilayah->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_evaluasi_idd_wilayah">
<span<?= $Page->idd_wilayah->viewAttributes() ?>>
<?= $Page->idd_wilayah->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->file_01->Visible) { // file_01 ?>
        <td data-name="file_01" <?= $Page->file_01->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_evaluasi_file_01">
<span<?= $Page->file_01->viewAttributes() ?>>
<?= GetFileViewTag($Page->file_01, $Page->file_01->getViewValue(), false) ?>
</span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->file_02->Visible) { // file_02 ?>
        <td data-name="file_02" <?= $Page->file_02->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_evaluasi_file_02">
<span<?= $Page->file_02->viewAttributes() ?>>
<?= GetFileViewTag($Page->file_02, $Page->file_02->getViewValue(), false) ?>
</span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->file_03->Visible) { // file_03 ?>
        <td data-name="file_03" <?= $Page->file_03->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_evaluasi_file_03">
<span<?= $Page->file_03->viewAttributes() ?>>
<?= GetFileViewTag($Page->file_03, $Page->file_03->getViewValue(), false) ?>
</span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->file_04->Visible) { // file_04 ?>
        <td data-name="file_04" <?= $Page->file_04->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_evaluasi_file_04">
<span<?= $Page->file_04->viewAttributes() ?>>
<?= GetFileViewTag($Page->file_04, $Page->file_04->getViewValue(), false) ?>
</span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->file_05->Visible) { // file_05 ?>
        <td data-name="file_05" <?= $Page->file_05->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_evaluasi_file_05">
<span<?= $Page->file_05->viewAttributes() ?>>
<?= GetFileViewTag($Page->file_05, $Page->file_05->getViewValue(), false) ?>
</span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->file_06->Visible) { // file_06 ?>
        <td data-name="file_06" <?= $Page->file_06->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_evaluasi_file_06">
<span<?= $Page->file_06->viewAttributes() ?>>
<?= GetFileViewTag($Page->file_06, $Page->file_06->getViewValue(), false) ?>
</span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->file_07->Visible) { // file_07 ?>
        <td data-name="file_07" <?= $Page->file_07->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_evaluasi_file_07">
<span<?= $Page->file_07->viewAttributes() ?>>
<?= GetFileViewTag($Page->file_07, $Page->file_07->getViewValue(), false) ?>
</span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->file_08->Visible) { // file_08 ?>
        <td data-name="file_08" <?= $Page->file_08->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_evaluasi_file_08">
<span<?= $Page->file_08->viewAttributes() ?>>
<?= GetFileViewTag($Page->file_08, $Page->file_08->getViewValue(), false) ?>
</span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->file_09->Visible) { // file_09 ?>
        <td data-name="file_09" <?= $Page->file_09->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_evaluasi_file_09">
<span<?= $Page->file_09->viewAttributes() ?>>
<?= GetFileViewTag($Page->file_09, $Page->file_09->getViewValue(), false) ?>
</span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->file_10->Visible) { // file_10 ?>
        <td data-name="file_10" <?= $Page->file_10->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_evaluasi_file_10">
<span<?= $Page->file_10->viewAttributes() ?>>
<?= GetFileViewTag($Page->file_10, $Page->file_10->getViewValue(), false) ?>
</span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->file_11->Visible) { // file_11 ?>
        <td data-name="file_11" <?= $Page->file_11->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_evaluasi_file_11">
<span<?= $Page->file_11->viewAttributes() ?>>
<?= $Page->file_11->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->file_12->Visible) { // file_12 ?>
        <td data-name="file_12" <?= $Page->file_12->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_evaluasi_file_12">
<span<?= $Page->file_12->viewAttributes() ?>>
<?= $Page->file_12->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->file_13->Visible) { // file_13 ?>
        <td data-name="file_13" <?= $Page->file_13->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_evaluasi_file_13">
<span<?= $Page->file_13->viewAttributes() ?>>
<?= $Page->file_13->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->file_14->Visible) { // file_14 ?>
        <td data-name="file_14" <?= $Page->file_14->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_evaluasi_file_14">
<span<?= $Page->file_14->viewAttributes() ?>>
<?= $Page->file_14->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->file_15->Visible) { // file_15 ?>
        <td data-name="file_15" <?= $Page->file_15->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_evaluasi_file_15">
<span<?= $Page->file_15->viewAttributes() ?>>
<?= $Page->file_15->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->file_16->Visible) { // file_16 ?>
        <td data-name="file_16" <?= $Page->file_16->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_evaluasi_file_16">
<span<?= $Page->file_16->viewAttributes() ?>>
<?= $Page->file_16->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->file_17->Visible) { // file_17 ?>
        <td data-name="file_17" <?= $Page->file_17->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_evaluasi_file_17">
<span<?= $Page->file_17->viewAttributes() ?>>
<?= $Page->file_17->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->file_18->Visible) { // file_18 ?>
        <td data-name="file_18" <?= $Page->file_18->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_evaluasi_file_18">
<span<?= $Page->file_18->viewAttributes() ?>>
<?= $Page->file_18->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->file_19->Visible) { // file_19 ?>
        <td data-name="file_19" <?= $Page->file_19->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_evaluasi_file_19">
<span<?= $Page->file_19->viewAttributes() ?>>
<?= $Page->file_19->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->file_20->Visible) { // file_20 ?>
        <td data-name="file_20" <?= $Page->file_20->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_evaluasi_file_20">
<span<?= $Page->file_20->viewAttributes() ?>>
<?= $Page->file_20->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->file_21->Visible) { // file_21 ?>
        <td data-name="file_21" <?= $Page->file_21->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_evaluasi_file_21">
<span<?= $Page->file_21->viewAttributes() ?>>
<?= $Page->file_21->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->file_22->Visible) { // file_22 ?>
        <td data-name="file_22" <?= $Page->file_22->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_evaluasi_file_22">
<span<?= $Page->file_22->viewAttributes() ?>>
<?= $Page->file_22->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->file_23->Visible) { // file_23 ?>
        <td data-name="file_23" <?= $Page->file_23->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_evaluasi_file_23">
<span<?= $Page->file_23->viewAttributes() ?>>
<?= $Page->file_23->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->file_24->Visible) { // file_24 ?>
        <td data-name="file_24" <?= $Page->file_24->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_evaluasi_file_24">
<span<?= $Page->file_24->viewAttributes() ?>>
<?= $Page->file_24->getViewValue() ?></span>
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
