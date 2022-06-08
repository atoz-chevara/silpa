<?php

namespace PHPMaker2021\silpa;

// Page object
$SatkersView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var fsatkersview;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "view";
    fsatkersview = currentForm = new ew.Form("fsatkersview", "view");
    loadjs.done("fsatkersview");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php } ?>
<script>
if (!ew.vars.tables.satkers) ew.vars.tables.satkers = <?= JsonEncode(GetClientVar("tables", "satkers")) ?>;
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
<form name="fsatkersview" id="fsatkersview" class="form-inline ew-form ew-view-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="satkers">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->kode_pemda->Visible) { // kode_pemda ?>
    <tr id="r_kode_pemda">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_satkers_kode_pemda"><?= $Page->kode_pemda->caption() ?></span></td>
        <td data-name="kode_pemda" <?= $Page->kode_pemda->cellAttributes() ?>>
<span id="el_satkers_kode_pemda">
<span<?= $Page->kode_pemda->viewAttributes() ?>>
<?= $Page->kode_pemda->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->kode_satker->Visible) { // kode_satker ?>
    <tr id="r_kode_satker">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_satkers_kode_satker"><?= $Page->kode_satker->caption() ?></span></td>
        <td data-name="kode_satker" <?= $Page->kode_satker->cellAttributes() ?>>
<span id="el_satkers_kode_satker">
<span<?= $Page->kode_satker->viewAttributes() ?>>
<?= $Page->kode_satker->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->nama_satker->Visible) { // nama_satker ?>
    <tr id="r_nama_satker">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_satkers_nama_satker"><?= $Page->nama_satker->caption() ?></span></td>
        <td data-name="nama_satker" <?= $Page->nama_satker->cellAttributes() ?>>
<span id="el_satkers_nama_satker">
<span<?= $Page->nama_satker->viewAttributes() ?>>
<?= $Page->nama_satker->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->wilayah->Visible) { // wilayah ?>
    <tr id="r_wilayah">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_satkers_wilayah"><?= $Page->wilayah->caption() ?></span></td>
        <td data-name="wilayah" <?= $Page->wilayah->cellAttributes() ?>>
<span id="el_satkers_wilayah">
<span<?= $Page->wilayah->viewAttributes() ?>>
<?= $Page->wilayah->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->idd_user->Visible) { // idd_user ?>
    <tr id="r_idd_user">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_satkers_idd_user"><?= $Page->idd_user->caption() ?></span></td>
        <td data-name="idd_user" <?= $Page->idd_user->cellAttributes() ?>>
<span id="el_satkers_idd_user">
<span<?= $Page->idd_user->viewAttributes() ?>>
<?= $Page->idd_user->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->no_telepon->Visible) { // no_telepon ?>
    <tr id="r_no_telepon">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_satkers_no_telepon"><?= $Page->no_telepon->caption() ?></span></td>
        <td data-name="no_telepon" <?= $Page->no_telepon->cellAttributes() ?>>
<span id="el_satkers_no_telepon">
<span<?= $Page->no_telepon->viewAttributes() ?>>
<?= $Page->no_telepon->getViewValue() ?></span>
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
