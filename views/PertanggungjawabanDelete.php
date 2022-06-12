<?php

namespace PHPMaker2021\silpa;

// Page object
$PertanggungjawabanDelete = &$Page;
?>
<script>
var currentForm, currentPageID;
var fpertanggungjawabandelete;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "delete";
    fpertanggungjawabandelete = currentForm = new ew.Form("fpertanggungjawabandelete", "delete");
    loadjs.done("fpertanggungjawabandelete");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<script>
if (!ew.vars.tables.pertanggungjawaban) ew.vars.tables.pertanggungjawaban = <?= JsonEncode(GetClientVar("tables", "pertanggungjawaban")) ?>;
</script>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<form name="fpertanggungjawabandelete" id="fpertanggungjawabandelete" class="form-inline ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="pertanggungjawaban">
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
<?php if ($Page->tanggal_upload->Visible) { // tanggal_upload ?>
        <th class="<?= $Page->tanggal_upload->headerCellClass() ?>"><span id="elh_pertanggungjawaban_tanggal_upload" class="pertanggungjawaban_tanggal_upload"><?= $Page->tanggal_upload->caption() ?></span></th>
<?php } ?>
<?php if ($Page->kd_satker->Visible) { // kd_satker ?>
        <th class="<?= $Page->kd_satker->headerCellClass() ?>"><span id="elh_pertanggungjawaban_kd_satker" class="pertanggungjawaban_kd_satker"><?= $Page->kd_satker->caption() ?></span></th>
<?php } ?>
<?php if ($Page->idd_tahapan->Visible) { // idd_tahapan ?>
        <th class="<?= $Page->idd_tahapan->headerCellClass() ?>"><span id="elh_pertanggungjawaban_idd_tahapan" class="pertanggungjawaban_idd_tahapan"><?= $Page->idd_tahapan->caption() ?></span></th>
<?php } ?>
<?php if ($Page->tahun_anggaran->Visible) { // tahun_anggaran ?>
        <th class="<?= $Page->tahun_anggaran->headerCellClass() ?>"><span id="elh_pertanggungjawaban_tahun_anggaran" class="pertanggungjawaban_tahun_anggaran"><?= $Page->tahun_anggaran->caption() ?></span></th>
<?php } ?>
<?php if ($Page->status->Visible) { // status ?>
        <th class="<?= $Page->status->headerCellClass() ?>"><span id="elh_pertanggungjawaban_status" class="pertanggungjawaban_status"><?= $Page->status->caption() ?></span></th>
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
<?php if ($Page->tanggal_upload->Visible) { // tanggal_upload ?>
        <td <?= $Page->tanggal_upload->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pertanggungjawaban_tanggal_upload" class="pertanggungjawaban_tanggal_upload">
<span<?= $Page->tanggal_upload->viewAttributes() ?>>
<?= $Page->tanggal_upload->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->kd_satker->Visible) { // kd_satker ?>
        <td <?= $Page->kd_satker->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pertanggungjawaban_kd_satker" class="pertanggungjawaban_kd_satker">
<span<?= $Page->kd_satker->viewAttributes() ?>>
<?= $Page->kd_satker->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->idd_tahapan->Visible) { // idd_tahapan ?>
        <td <?= $Page->idd_tahapan->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pertanggungjawaban_idd_tahapan" class="pertanggungjawaban_idd_tahapan">
<span<?= $Page->idd_tahapan->viewAttributes() ?>>
<?= $Page->idd_tahapan->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->tahun_anggaran->Visible) { // tahun_anggaran ?>
        <td <?= $Page->tahun_anggaran->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pertanggungjawaban_tahun_anggaran" class="pertanggungjawaban_tahun_anggaran">
<span<?= $Page->tahun_anggaran->viewAttributes() ?>>
<?= $Page->tahun_anggaran->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->status->Visible) { // status ?>
        <td <?= $Page->status->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pertanggungjawaban_status" class="pertanggungjawaban_status">
<span<?= $Page->status->viewAttributes() ?>>
<?= $Page->status->getViewValue() ?></span>
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
