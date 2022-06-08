<?php

namespace PHPMaker2021\silpa;

// Page object
$RapbkView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var frapbkview;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "view";
    frapbkview = currentForm = new ew.Form("frapbkview", "view");
    loadjs.done("frapbkview");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php } ?>
<script>
if (!ew.vars.tables.rapbk) ew.vars.tables.rapbk = <?= JsonEncode(GetClientVar("tables", "rapbk")) ?>;
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
<form name="frapbkview" id="frapbkview" class="form-inline ew-form ew-view-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="rapbk">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->idd_evaluasi->Visible) { // idd_evaluasi ?>
    <tr id="r_idd_evaluasi">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_rapbk_idd_evaluasi"><?= $Page->idd_evaluasi->caption() ?></span></td>
        <td data-name="idd_evaluasi" <?= $Page->idd_evaluasi->cellAttributes() ?>>
<span id="el_rapbk_idd_evaluasi">
<span<?= $Page->idd_evaluasi->viewAttributes() ?>>
<?= $Page->idd_evaluasi->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->tanggal->Visible) { // tanggal ?>
    <tr id="r_tanggal">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_rapbk_tanggal"><?= $Page->tanggal->caption() ?></span></td>
        <td data-name="tanggal" <?= $Page->tanggal->cellAttributes() ?>>
<span id="el_rapbk_tanggal">
<span<?= $Page->tanggal->viewAttributes() ?>>
<?= $Page->tanggal->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->kd_satker->Visible) { // kd_satker ?>
    <tr id="r_kd_satker">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_rapbk_kd_satker"><?= $Page->kd_satker->caption() ?></span></td>
        <td data-name="kd_satker" <?= $Page->kd_satker->cellAttributes() ?>>
<span id="el_rapbk_kd_satker">
<span<?= $Page->kd_satker->viewAttributes() ?>>
<?= $Page->kd_satker->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->idd_tahapan->Visible) { // idd_tahapan ?>
    <tr id="r_idd_tahapan">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_rapbk_idd_tahapan"><?= $Page->idd_tahapan->caption() ?></span></td>
        <td data-name="idd_tahapan" <?= $Page->idd_tahapan->cellAttributes() ?>>
<span id="el_rapbk_idd_tahapan">
<span<?= $Page->idd_tahapan->viewAttributes() ?>>
<?= $Page->idd_tahapan->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->tahun_anggaran->Visible) { // tahun_anggaran ?>
    <tr id="r_tahun_anggaran">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_rapbk_tahun_anggaran"><?= $Page->tahun_anggaran->caption() ?></span></td>
        <td data-name="tahun_anggaran" <?= $Page->tahun_anggaran->cellAttributes() ?>>
<span id="el_rapbk_tahun_anggaran">
<span<?= $Page->tahun_anggaran->viewAttributes() ?>>
<?= $Page->tahun_anggaran->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->idd_wilayah->Visible) { // idd_wilayah ?>
    <tr id="r_idd_wilayah">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_rapbk_idd_wilayah"><?= $Page->idd_wilayah->caption() ?></span></td>
        <td data-name="idd_wilayah" <?= $Page->idd_wilayah->cellAttributes() ?>>
<span id="el_rapbk_idd_wilayah">
<span<?= $Page->idd_wilayah->viewAttributes() ?>>
<?= $Page->idd_wilayah->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->file_01->Visible) { // file_01 ?>
    <tr id="r_file_01">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_rapbk_file_01"><?= $Page->file_01->caption() ?></span></td>
        <td data-name="file_01" <?= $Page->file_01->cellAttributes() ?>>
<span id="el_rapbk_file_01">
<span<?= $Page->file_01->viewAttributes() ?>>
<?= GetFileViewTag($Page->file_01, $Page->file_01->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->file_02->Visible) { // file_02 ?>
    <tr id="r_file_02">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_rapbk_file_02"><?= $Page->file_02->caption() ?></span></td>
        <td data-name="file_02" <?= $Page->file_02->cellAttributes() ?>>
<span id="el_rapbk_file_02">
<span<?= $Page->file_02->viewAttributes() ?>>
<?= GetFileViewTag($Page->file_02, $Page->file_02->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->file_03->Visible) { // file_03 ?>
    <tr id="r_file_03">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_rapbk_file_03"><?= $Page->file_03->caption() ?></span></td>
        <td data-name="file_03" <?= $Page->file_03->cellAttributes() ?>>
<span id="el_rapbk_file_03">
<span<?= $Page->file_03->viewAttributes() ?>>
<?= GetFileViewTag($Page->file_03, $Page->file_03->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->file_04->Visible) { // file_04 ?>
    <tr id="r_file_04">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_rapbk_file_04"><?= $Page->file_04->caption() ?></span></td>
        <td data-name="file_04" <?= $Page->file_04->cellAttributes() ?>>
<span id="el_rapbk_file_04">
<span<?= $Page->file_04->viewAttributes() ?>>
<?= GetFileViewTag($Page->file_04, $Page->file_04->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->file_05->Visible) { // file_05 ?>
    <tr id="r_file_05">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_rapbk_file_05"><?= $Page->file_05->caption() ?></span></td>
        <td data-name="file_05" <?= $Page->file_05->cellAttributes() ?>>
<span id="el_rapbk_file_05">
<span<?= $Page->file_05->viewAttributes() ?>>
<?= GetFileViewTag($Page->file_05, $Page->file_05->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->file_06->Visible) { // file_06 ?>
    <tr id="r_file_06">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_rapbk_file_06"><?= $Page->file_06->caption() ?></span></td>
        <td data-name="file_06" <?= $Page->file_06->cellAttributes() ?>>
<span id="el_rapbk_file_06">
<span<?= $Page->file_06->viewAttributes() ?>>
<?= GetFileViewTag($Page->file_06, $Page->file_06->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->file_07->Visible) { // file_07 ?>
    <tr id="r_file_07">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_rapbk_file_07"><?= $Page->file_07->caption() ?></span></td>
        <td data-name="file_07" <?= $Page->file_07->cellAttributes() ?>>
<span id="el_rapbk_file_07">
<span<?= $Page->file_07->viewAttributes() ?>>
<?= GetFileViewTag($Page->file_07, $Page->file_07->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->file_08->Visible) { // file_08 ?>
    <tr id="r_file_08">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_rapbk_file_08"><?= $Page->file_08->caption() ?></span></td>
        <td data-name="file_08" <?= $Page->file_08->cellAttributes() ?>>
<span id="el_rapbk_file_08">
<span<?= $Page->file_08->viewAttributes() ?>>
<?= GetFileViewTag($Page->file_08, $Page->file_08->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->file_09->Visible) { // file_09 ?>
    <tr id="r_file_09">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_rapbk_file_09"><?= $Page->file_09->caption() ?></span></td>
        <td data-name="file_09" <?= $Page->file_09->cellAttributes() ?>>
<span id="el_rapbk_file_09">
<span<?= $Page->file_09->viewAttributes() ?>>
<?= GetFileViewTag($Page->file_09, $Page->file_09->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->file_10->Visible) { // file_10 ?>
    <tr id="r_file_10">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_rapbk_file_10"><?= $Page->file_10->caption() ?></span></td>
        <td data-name="file_10" <?= $Page->file_10->cellAttributes() ?>>
<span id="el_rapbk_file_10">
<span<?= $Page->file_10->viewAttributes() ?>>
<?= GetFileViewTag($Page->file_10, $Page->file_10->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->file_11->Visible) { // file_11 ?>
    <tr id="r_file_11">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_rapbk_file_11"><?= $Page->file_11->caption() ?></span></td>
        <td data-name="file_11" <?= $Page->file_11->cellAttributes() ?>>
<span id="el_rapbk_file_11">
<span<?= $Page->file_11->viewAttributes() ?>>
<?= GetFileViewTag($Page->file_11, $Page->file_11->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->file_12->Visible) { // file_12 ?>
    <tr id="r_file_12">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_rapbk_file_12"><?= $Page->file_12->caption() ?></span></td>
        <td data-name="file_12" <?= $Page->file_12->cellAttributes() ?>>
<span id="el_rapbk_file_12">
<span<?= $Page->file_12->viewAttributes() ?>>
<?= GetFileViewTag($Page->file_12, $Page->file_12->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->file_13->Visible) { // file_13 ?>
    <tr id="r_file_13">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_rapbk_file_13"><?= $Page->file_13->caption() ?></span></td>
        <td data-name="file_13" <?= $Page->file_13->cellAttributes() ?>>
<span id="el_rapbk_file_13">
<span<?= $Page->file_13->viewAttributes() ?>>
<?= $Page->file_13->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->file_14->Visible) { // file_14 ?>
    <tr id="r_file_14">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_rapbk_file_14"><?= $Page->file_14->caption() ?></span></td>
        <td data-name="file_14" <?= $Page->file_14->cellAttributes() ?>>
<span id="el_rapbk_file_14">
<span<?= $Page->file_14->viewAttributes() ?>>
<?= GetFileViewTag($Page->file_14, $Page->file_14->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->file_15->Visible) { // file_15 ?>
    <tr id="r_file_15">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_rapbk_file_15"><?= $Page->file_15->caption() ?></span></td>
        <td data-name="file_15" <?= $Page->file_15->cellAttributes() ?>>
<span id="el_rapbk_file_15">
<span<?= $Page->file_15->viewAttributes() ?>>
<?= GetFileViewTag($Page->file_15, $Page->file_15->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->file_16->Visible) { // file_16 ?>
    <tr id="r_file_16">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_rapbk_file_16"><?= $Page->file_16->caption() ?></span></td>
        <td data-name="file_16" <?= $Page->file_16->cellAttributes() ?>>
<span id="el_rapbk_file_16">
<span<?= $Page->file_16->viewAttributes() ?>>
<?= GetFileViewTag($Page->file_16, $Page->file_16->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->file_17->Visible) { // file_17 ?>
    <tr id="r_file_17">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_rapbk_file_17"><?= $Page->file_17->caption() ?></span></td>
        <td data-name="file_17" <?= $Page->file_17->cellAttributes() ?>>
<span id="el_rapbk_file_17">
<span<?= $Page->file_17->viewAttributes() ?>>
<?= GetFileViewTag($Page->file_17, $Page->file_17->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->file_18->Visible) { // file_18 ?>
    <tr id="r_file_18">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_rapbk_file_18"><?= $Page->file_18->caption() ?></span></td>
        <td data-name="file_18" <?= $Page->file_18->cellAttributes() ?>>
<span id="el_rapbk_file_18">
<span<?= $Page->file_18->viewAttributes() ?>>
<?= GetFileViewTag($Page->file_18, $Page->file_18->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->file_19->Visible) { // file_19 ?>
    <tr id="r_file_19">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_rapbk_file_19"><?= $Page->file_19->caption() ?></span></td>
        <td data-name="file_19" <?= $Page->file_19->cellAttributes() ?>>
<span id="el_rapbk_file_19">
<span<?= $Page->file_19->viewAttributes() ?>>
<?= GetFileViewTag($Page->file_19, $Page->file_19->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->file_20->Visible) { // file_20 ?>
    <tr id="r_file_20">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_rapbk_file_20"><?= $Page->file_20->caption() ?></span></td>
        <td data-name="file_20" <?= $Page->file_20->cellAttributes() ?>>
<span id="el_rapbk_file_20">
<span<?= $Page->file_20->viewAttributes() ?>>
<?= GetFileViewTag($Page->file_20, $Page->file_20->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->file_21->Visible) { // file_21 ?>
    <tr id="r_file_21">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_rapbk_file_21"><?= $Page->file_21->caption() ?></span></td>
        <td data-name="file_21" <?= $Page->file_21->cellAttributes() ?>>
<span id="el_rapbk_file_21">
<span<?= $Page->file_21->viewAttributes() ?>>
<?= GetFileViewTag($Page->file_21, $Page->file_21->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->file_22->Visible) { // file_22 ?>
    <tr id="r_file_22">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_rapbk_file_22"><?= $Page->file_22->caption() ?></span></td>
        <td data-name="file_22" <?= $Page->file_22->cellAttributes() ?>>
<span id="el_rapbk_file_22">
<span<?= $Page->file_22->viewAttributes() ?>>
<?= GetFileViewTag($Page->file_22, $Page->file_22->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->file_23->Visible) { // file_23 ?>
    <tr id="r_file_23">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_rapbk_file_23"><?= $Page->file_23->caption() ?></span></td>
        <td data-name="file_23" <?= $Page->file_23->cellAttributes() ?>>
<span id="el_rapbk_file_23">
<span<?= $Page->file_23->viewAttributes() ?>>
<?= GetFileViewTag($Page->file_23, $Page->file_23->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->file_24->Visible) { // file_24 ?>
    <tr id="r_file_24">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_rapbk_file_24"><?= $Page->file_24->caption() ?></span></td>
        <td data-name="file_24" <?= $Page->file_24->cellAttributes() ?>>
<span id="el_rapbk_file_24">
<span<?= $Page->file_24->viewAttributes() ?>>
<?= GetFileViewTag($Page->file_24, $Page->file_24->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->status->Visible) { // status ?>
    <tr id="r_status">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_rapbk_status"><?= $Page->status->caption() ?></span></td>
        <td data-name="status" <?= $Page->status->cellAttributes() ?>>
<span id="el_rapbk_status">
<span<?= $Page->status->viewAttributes() ?>>
<?= $Page->status->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->idd_user->Visible) { // idd_user ?>
    <tr id="r_idd_user">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_rapbk_idd_user"><?= $Page->idd_user->caption() ?></span></td>
        <td data-name="idd_user" <?= $Page->idd_user->cellAttributes() ?>>
<span id="el_rapbk_idd_user">
<span<?= $Page->idd_user->viewAttributes() ?>>
<?= $Page->idd_user->getViewValue() ?></span>
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
