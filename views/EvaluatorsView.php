<?php

namespace PHPMaker2021\silpa;

// Page object
$EvaluatorsView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var fevaluatorsview;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "view";
    fevaluatorsview = currentForm = new ew.Form("fevaluatorsview", "view");
    loadjs.done("fevaluatorsview");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php } ?>
<script>
if (!ew.vars.tables.evaluators) ew.vars.tables.evaluators = <?= JsonEncode(GetClientVar("tables", "evaluators")) ?>;
</script>
<?php if (!$Page->isExport()) { ?>
<div class="btn-toolbar ew-toolbar">
<?php $Page->ExportOptions->render("body") ?>
<?php $Page->OtherOptions->render("body") ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<form name="fevaluatorsview" id="fevaluatorsview" class="form-inline ew-form ew-view-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="evaluators">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->nip->Visible) { // nip ?>
    <tr id="r_nip">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_evaluators_nip"><?= $Page->nip->caption() ?></span></td>
        <td data-name="nip" <?= $Page->nip->cellAttributes() ?>>
<span id="el_evaluators_nip">
<span<?= $Page->nip->viewAttributes() ?>>
<?= $Page->nip->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->nama_lengkap->Visible) { // nama_lengkap ?>
    <tr id="r_nama_lengkap">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_evaluators_nama_lengkap"><?= $Page->nama_lengkap->caption() ?></span></td>
        <td data-name="nama_lengkap" <?= $Page->nama_lengkap->cellAttributes() ?>>
<span id="el_evaluators_nama_lengkap">
<span<?= $Page->nama_lengkap->viewAttributes() ?>>
<?= $Page->nama_lengkap->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->alamat->Visible) { // alamat ?>
    <tr id="r_alamat">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_evaluators_alamat"><?= $Page->alamat->caption() ?></span></td>
        <td data-name="alamat" <?= $Page->alamat->cellAttributes() ?>>
<span id="el_evaluators_alamat">
<span<?= $Page->alamat->viewAttributes() ?>>
<?= $Page->alamat->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->wilayah->Visible) { // wilayah ?>
    <tr id="r_wilayah">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_evaluators_wilayah"><?= $Page->wilayah->caption() ?></span></td>
        <td data-name="wilayah" <?= $Page->wilayah->cellAttributes() ?>>
<span id="el_evaluators_wilayah">
<span<?= $Page->wilayah->viewAttributes() ?>>
<?= $Page->wilayah->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->idd_user->Visible) { // idd_user ?>
    <tr id="r_idd_user">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_evaluators_idd_user"><?= $Page->idd_user->caption() ?></span></td>
        <td data-name="idd_user" <?= $Page->idd_user->cellAttributes() ?>>
<span id="el_evaluators_idd_user">
<span<?= $Page->idd_user->viewAttributes() ?>>
<?= $Page->idd_user->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->no_telepon->Visible) { // no_telepon ?>
    <tr id="r_no_telepon">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_evaluators_no_telepon"><?= $Page->no_telepon->caption() ?></span></td>
        <td data-name="no_telepon" <?= $Page->no_telepon->cellAttributes() ?>>
<span id="el_evaluators_no_telepon">
<span<?= $Page->no_telepon->viewAttributes() ?>>
<?php if (!EmptyString($Page->no_telepon->getViewValue()) && $Page->no_telepon->linkAttributes() != "") { ?>
<a<?= $Page->no_telepon->linkAttributes() ?>><?= $Page->no_telepon->getViewValue() ?></a>
<?php } else { ?>
<?= $Page->no_telepon->getViewValue() ?>
<?php } ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
</table>
</form>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<?php if (!$Page->isExport()) { ?>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
