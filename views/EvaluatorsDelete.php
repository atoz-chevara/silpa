<?php

namespace PHPMaker2021\silpa;

// Page object
$EvaluatorsDelete = &$Page;
?>
<script>
var currentForm, currentPageID;
var fevaluatorsdelete;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "delete";
    fevaluatorsdelete = currentForm = new ew.Form("fevaluatorsdelete", "delete");
    loadjs.done("fevaluatorsdelete");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<script>
if (!ew.vars.tables.evaluators) ew.vars.tables.evaluators = <?= JsonEncode(GetClientVar("tables", "evaluators")) ?>;
</script>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<form name="fevaluatorsdelete" id="fevaluatorsdelete" class="form-inline ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="evaluators">
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
<?php if ($Page->nip->Visible) { // nip ?>
        <th class="<?= $Page->nip->headerCellClass() ?>"><span id="elh_evaluators_nip" class="evaluators_nip"><?= $Page->nip->caption() ?></span></th>
<?php } ?>
<?php if ($Page->nama_lengkap->Visible) { // nama_lengkap ?>
        <th class="<?= $Page->nama_lengkap->headerCellClass() ?>"><span id="elh_evaluators_nama_lengkap" class="evaluators_nama_lengkap"><?= $Page->nama_lengkap->caption() ?></span></th>
<?php } ?>
<?php if ($Page->wilayah->Visible) { // wilayah ?>
        <th class="<?= $Page->wilayah->headerCellClass() ?>"><span id="elh_evaluators_wilayah" class="evaluators_wilayah"><?= $Page->wilayah->caption() ?></span></th>
<?php } ?>
<?php if ($Page->idd_user->Visible) { // idd_user ?>
        <th class="<?= $Page->idd_user->headerCellClass() ?>"><span id="elh_evaluators_idd_user" class="evaluators_idd_user"><?= $Page->idd_user->caption() ?></span></th>
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
<?php if ($Page->nip->Visible) { // nip ?>
        <td <?= $Page->nip->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_evaluators_nip" class="evaluators_nip">
<span<?= $Page->nip->viewAttributes() ?>>
<?= $Page->nip->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->nama_lengkap->Visible) { // nama_lengkap ?>
        <td <?= $Page->nama_lengkap->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_evaluators_nama_lengkap" class="evaluators_nama_lengkap">
<span<?= $Page->nama_lengkap->viewAttributes() ?>>
<?= $Page->nama_lengkap->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->wilayah->Visible) { // wilayah ?>
        <td <?= $Page->wilayah->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_evaluators_wilayah" class="evaluators_wilayah">
<span<?= $Page->wilayah->viewAttributes() ?>>
<?= $Page->wilayah->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->idd_user->Visible) { // idd_user ?>
        <td <?= $Page->idd_user->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_evaluators_idd_user" class="evaluators_idd_user">
<span<?= $Page->idd_user->viewAttributes() ?>>
<?= $Page->idd_user->getViewValue() ?></span>
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
