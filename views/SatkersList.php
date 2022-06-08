<?php

namespace PHPMaker2021\silpa;

// Page object
$SatkersList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var fsatkerslist;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "list";
    fsatkerslist = currentForm = new ew.Form("fsatkerslist", "list");
    fsatkerslist.formKeyCountName = '<?= $Page->FormKeyCountName ?>';
    loadjs.done("fsatkerslist");
});
var fsatkerslistsrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object for search
    fsatkerslistsrch = currentSearchForm = new ew.Form("fsatkerslistsrch");

    // Dynamic selection lists

    // Filters
    fsatkerslistsrch.filterList = <?= $Page->getFilterList() ?>;
    loadjs.done("fsatkerslistsrch");
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
<form name="fsatkerslistsrch" id="fsatkerslistsrch" class="form-inline ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>">
<div id="fsatkerslistsrch-search-panel" class="<?= $Page->SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="satkers">
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
<div class="card ew-card ew-grid<?php if ($Page->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> satkers">
<form name="fsatkerslist" id="fsatkerslist" class="form-inline ew-form ew-list-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="satkers">
<div id="gmp_satkers" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit()) { ?>
<table id="tbl_satkerslist" class="table ew-table"><!-- .ew-table -->
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
<?php if ($Page->kode_pemda->Visible) { // kode_pemda ?>
        <th data-name="kode_pemda" class="<?= $Page->kode_pemda->headerCellClass() ?>"><div id="elh_satkers_kode_pemda" class="satkers_kode_pemda"><?= $Page->renderSort($Page->kode_pemda) ?></div></th>
<?php } ?>
<?php if ($Page->kode_satker->Visible) { // kode_satker ?>
        <th data-name="kode_satker" class="<?= $Page->kode_satker->headerCellClass() ?>"><div id="elh_satkers_kode_satker" class="satkers_kode_satker"><?= $Page->renderSort($Page->kode_satker) ?></div></th>
<?php } ?>
<?php if ($Page->nama_satker->Visible) { // nama_satker ?>
        <th data-name="nama_satker" class="<?= $Page->nama_satker->headerCellClass() ?>"><div id="elh_satkers_nama_satker" class="satkers_nama_satker"><?= $Page->renderSort($Page->nama_satker) ?></div></th>
<?php } ?>
<?php if ($Page->wilayah->Visible) { // wilayah ?>
        <th data-name="wilayah" class="<?= $Page->wilayah->headerCellClass() ?>"><div id="elh_satkers_wilayah" class="satkers_wilayah"><?= $Page->renderSort($Page->wilayah) ?></div></th>
<?php } ?>
<?php if ($Page->idd_user->Visible) { // idd_user ?>
        <th data-name="idd_user" class="<?= $Page->idd_user->headerCellClass() ?>"><div id="elh_satkers_idd_user" class="satkers_idd_user"><?= $Page->renderSort($Page->idd_user) ?></div></th>
<?php } ?>
<?php if ($Page->no_telepon->Visible) { // no_telepon ?>
        <th data-name="no_telepon" class="<?= $Page->no_telepon->headerCellClass() ?>"><div id="elh_satkers_no_telepon" class="satkers_no_telepon"><?= $Page->renderSort($Page->no_telepon) ?></div></th>
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
        $Page->RowAttrs->merge(["data-rowindex" => $Page->RowCount, "id" => "r" . $Page->RowCount . "_satkers", "data-rowtype" => $Page->RowType]);

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
    <?php if ($Page->kode_pemda->Visible) { // kode_pemda ?>
        <td data-name="kode_pemda" <?= $Page->kode_pemda->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_satkers_kode_pemda">
<span<?= $Page->kode_pemda->viewAttributes() ?>>
<?= $Page->kode_pemda->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->kode_satker->Visible) { // kode_satker ?>
        <td data-name="kode_satker" <?= $Page->kode_satker->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_satkers_kode_satker">
<span<?= $Page->kode_satker->viewAttributes() ?>>
<?= $Page->kode_satker->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->nama_satker->Visible) { // nama_satker ?>
        <td data-name="nama_satker" <?= $Page->nama_satker->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_satkers_nama_satker">
<span<?= $Page->nama_satker->viewAttributes() ?>>
<?= $Page->nama_satker->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->wilayah->Visible) { // wilayah ?>
        <td data-name="wilayah" <?= $Page->wilayah->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_satkers_wilayah">
<span<?= $Page->wilayah->viewAttributes() ?>>
<?= $Page->wilayah->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->idd_user->Visible) { // idd_user ?>
        <td data-name="idd_user" <?= $Page->idd_user->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_satkers_idd_user">
<span<?= $Page->idd_user->viewAttributes() ?>>
<?= $Page->idd_user->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->no_telepon->Visible) { // no_telepon ?>
        <td data-name="no_telepon" <?= $Page->no_telepon->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_satkers_no_telepon">
<span<?= $Page->no_telepon->viewAttributes() ?>>
<?= $Page->no_telepon->getViewValue() ?></span>
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
    ew.addEventHandlers("satkers");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
