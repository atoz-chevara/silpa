<?php

namespace PHPMaker2021\silpa;

// Page object
$SatkersDelete = &$Page;
?>
<script>
var currentForm, currentPageID;
var fsatkersdelete;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "delete";
    fsatkersdelete = currentForm = new ew.Form("fsatkersdelete", "delete");
    loadjs.done("fsatkersdelete");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<script>
if (!ew.vars.tables.satkers) ew.vars.tables.satkers = <?= JsonEncode(GetClientVar("tables", "satkers")) ?>;
</script>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<form name="fsatkersdelete" id="fsatkersdelete" class="form-inline ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="satkers">
<input type="hidden" name="action" id="action" value="delete">
<?php foreach ($Page->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode(Config("COMPOSITE_KEY_SEPARATOR"), $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?= HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="card ew-card ew-grid">
<div class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<table class="table ew-table">
    <thead>
    <tr class="ew-table-header">
<?php if ($Page->kode_pemda->Visible) { // kode_pemda ?>
        <th class="<?= $Page->kode_pemda->headerCellClass() ?>"><span id="elh_satkers_kode_pemda" class="satkers_kode_pemda"><?= $Page->kode_pemda->caption() ?></span></th>
<?php } ?>
<?php if ($Page->kode_satker->Visible) { // kode_satker ?>
        <th class="<?= $Page->kode_satker->headerCellClass() ?>"><span id="elh_satkers_kode_satker" class="satkers_kode_satker"><?= $Page->kode_satker->caption() ?></span></th>
<?php } ?>
<?php if ($Page->nama_satker->Visible) { // nama_satker ?>
        <th class="<?= $Page->nama_satker->headerCellClass() ?>"><span id="elh_satkers_nama_satker" class="satkers_nama_satker"><?= $Page->nama_satker->caption() ?></span></th>
<?php } ?>
<?php if ($Page->wilayah->Visible) { // wilayah ?>
        <th class="<?= $Page->wilayah->headerCellClass() ?>"><span id="elh_satkers_wilayah" class="satkers_wilayah"><?= $Page->wilayah->caption() ?></span></th>
<?php } ?>
<?php if ($Page->idd_user->Visible) { // idd_user ?>
        <th class="<?= $Page->idd_user->headerCellClass() ?>"><span id="elh_satkers_idd_user" class="satkers_idd_user"><?= $Page->idd_user->caption() ?></span></th>
<?php } ?>
<?php if ($Page->no_telepon->Visible) { // no_telepon ?>
        <th class="<?= $Page->no_telepon->headerCellClass() ?>"><span id="elh_satkers_no_telepon" class="satkers_no_telepon"><?= $Page->no_telepon->caption() ?></span></th>
<?php } ?>
    </tr>
    </thead>
    <tbody>
<?php
$Page->RecordCount = 0;
$i = 0;
while (!$Page->Recordset->EOF) {
    $Page->RecordCount++;
    $Page->RowCount++;

    // Set row properties
    $Page->resetAttributes();
    $Page->RowType = ROWTYPE_VIEW; // View

    // Get the field contents
    $Page->loadRowValues($Page->Recordset);

    // Render row
    $Page->renderRow();
?>
    <tr <?= $Page->rowAttributes() ?>>
<?php if ($Page->kode_pemda->Visible) { // kode_pemda ?>
        <td <?= $Page->kode_pemda->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_satkers_kode_pemda" class="satkers_kode_pemda">
<span<?= $Page->kode_pemda->viewAttributes() ?>>
<?= $Page->kode_pemda->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->kode_satker->Visible) { // kode_satker ?>
        <td <?= $Page->kode_satker->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_satkers_kode_satker" class="satkers_kode_satker">
<span<?= $Page->kode_satker->viewAttributes() ?>>
<?= $Page->kode_satker->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->nama_satker->Visible) { // nama_satker ?>
        <td <?= $Page->nama_satker->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_satkers_nama_satker" class="satkers_nama_satker">
<span<?= $Page->nama_satker->viewAttributes() ?>>
<?= $Page->nama_satker->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->wilayah->Visible) { // wilayah ?>
        <td <?= $Page->wilayah->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_satkers_wilayah" class="satkers_wilayah">
<span<?= $Page->wilayah->viewAttributes() ?>>
<?= $Page->wilayah->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->idd_user->Visible) { // idd_user ?>
        <td <?= $Page->idd_user->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_satkers_idd_user" class="satkers_idd_user">
<span<?= $Page->idd_user->viewAttributes() ?>>
<?= $Page->idd_user->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->no_telepon->Visible) { // no_telepon ?>
        <td <?= $Page->no_telepon->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_satkers_no_telepon" class="satkers_no_telepon">
<span<?= $Page->no_telepon->viewAttributes() ?>>
<?= $Page->no_telepon->getViewValue() ?></span>
</span>
</td>
<?php } ?>
    </tr>
<?php
    $Page->Recordset->moveNext();
}
$Page->Recordset->close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit"><?= $Language->phrase("DeleteBtn") ?></button>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
</div>
</form>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
