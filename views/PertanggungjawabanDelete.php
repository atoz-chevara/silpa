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
<?php if ($Page->idd_evaluasi->Visible) { // idd_evaluasi ?>
        <th class="<?= $Page->idd_evaluasi->headerCellClass() ?>"><span id="elh_pertanggungjawaban_idd_evaluasi" class="pertanggungjawaban_idd_evaluasi"><?= $Page->idd_evaluasi->caption() ?></span></th>
<?php } ?>
<?php if ($Page->tanggal->Visible) { // tanggal ?>
        <th class="<?= $Page->tanggal->headerCellClass() ?>"><span id="elh_pertanggungjawaban_tanggal" class="pertanggungjawaban_tanggal"><?= $Page->tanggal->caption() ?></span></th>
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
<?php if ($Page->idd_wilayah->Visible) { // idd_wilayah ?>
        <th class="<?= $Page->idd_wilayah->headerCellClass() ?>"><span id="elh_pertanggungjawaban_idd_wilayah" class="pertanggungjawaban_idd_wilayah"><?= $Page->idd_wilayah->caption() ?></span></th>
<?php } ?>
<?php if ($Page->file_01->Visible) { // file_01 ?>
        <th class="<?= $Page->file_01->headerCellClass() ?>"><span id="elh_pertanggungjawaban_file_01" class="pertanggungjawaban_file_01"><?= $Page->file_01->caption() ?></span></th>
<?php } ?>
<?php if ($Page->file_02->Visible) { // file_02 ?>
        <th class="<?= $Page->file_02->headerCellClass() ?>"><span id="elh_pertanggungjawaban_file_02" class="pertanggungjawaban_file_02"><?= $Page->file_02->caption() ?></span></th>
<?php } ?>
<?php if ($Page->file_03->Visible) { // file_03 ?>
        <th class="<?= $Page->file_03->headerCellClass() ?>"><span id="elh_pertanggungjawaban_file_03" class="pertanggungjawaban_file_03"><?= $Page->file_03->caption() ?></span></th>
<?php } ?>
<?php if ($Page->file_04->Visible) { // file_04 ?>
        <th class="<?= $Page->file_04->headerCellClass() ?>"><span id="elh_pertanggungjawaban_file_04" class="pertanggungjawaban_file_04"><?= $Page->file_04->caption() ?></span></th>
<?php } ?>
<?php if ($Page->file_05->Visible) { // file_05 ?>
        <th class="<?= $Page->file_05->headerCellClass() ?>"><span id="elh_pertanggungjawaban_file_05" class="pertanggungjawaban_file_05"><?= $Page->file_05->caption() ?></span></th>
<?php } ?>
<?php if ($Page->file_06->Visible) { // file_06 ?>
        <th class="<?= $Page->file_06->headerCellClass() ?>"><span id="elh_pertanggungjawaban_file_06" class="pertanggungjawaban_file_06"><?= $Page->file_06->caption() ?></span></th>
<?php } ?>
<?php if ($Page->file_07->Visible) { // file_07 ?>
        <th class="<?= $Page->file_07->headerCellClass() ?>"><span id="elh_pertanggungjawaban_file_07" class="pertanggungjawaban_file_07"><?= $Page->file_07->caption() ?></span></th>
<?php } ?>
<?php if ($Page->file_08->Visible) { // file_08 ?>
        <th class="<?= $Page->file_08->headerCellClass() ?>"><span id="elh_pertanggungjawaban_file_08" class="pertanggungjawaban_file_08"><?= $Page->file_08->caption() ?></span></th>
<?php } ?>
<?php if ($Page->file_09->Visible) { // file_09 ?>
        <th class="<?= $Page->file_09->headerCellClass() ?>"><span id="elh_pertanggungjawaban_file_09" class="pertanggungjawaban_file_09"><?= $Page->file_09->caption() ?></span></th>
<?php } ?>
<?php if ($Page->file_10->Visible) { // file_10 ?>
        <th class="<?= $Page->file_10->headerCellClass() ?>"><span id="elh_pertanggungjawaban_file_10" class="pertanggungjawaban_file_10"><?= $Page->file_10->caption() ?></span></th>
<?php } ?>
<?php if ($Page->file_11->Visible) { // file_11 ?>
        <th class="<?= $Page->file_11->headerCellClass() ?>"><span id="elh_pertanggungjawaban_file_11" class="pertanggungjawaban_file_11"><?= $Page->file_11->caption() ?></span></th>
<?php } ?>
<?php if ($Page->file_12->Visible) { // file_12 ?>
        <th class="<?= $Page->file_12->headerCellClass() ?>"><span id="elh_pertanggungjawaban_file_12" class="pertanggungjawaban_file_12"><?= $Page->file_12->caption() ?></span></th>
<?php } ?>
<?php if ($Page->file_13->Visible) { // file_13 ?>
        <th class="<?= $Page->file_13->headerCellClass() ?>"><span id="elh_pertanggungjawaban_file_13" class="pertanggungjawaban_file_13"><?= $Page->file_13->caption() ?></span></th>
<?php } ?>
<?php if ($Page->file_14->Visible) { // file_14 ?>
        <th class="<?= $Page->file_14->headerCellClass() ?>"><span id="elh_pertanggungjawaban_file_14" class="pertanggungjawaban_file_14"><?= $Page->file_14->caption() ?></span></th>
<?php } ?>
<?php if ($Page->file_15->Visible) { // file_15 ?>
        <th class="<?= $Page->file_15->headerCellClass() ?>"><span id="elh_pertanggungjawaban_file_15" class="pertanggungjawaban_file_15"><?= $Page->file_15->caption() ?></span></th>
<?php } ?>
<?php if ($Page->file_16->Visible) { // file_16 ?>
        <th class="<?= $Page->file_16->headerCellClass() ?>"><span id="elh_pertanggungjawaban_file_16" class="pertanggungjawaban_file_16"><?= $Page->file_16->caption() ?></span></th>
<?php } ?>
<?php if ($Page->file_17->Visible) { // file_17 ?>
        <th class="<?= $Page->file_17->headerCellClass() ?>"><span id="elh_pertanggungjawaban_file_17" class="pertanggungjawaban_file_17"><?= $Page->file_17->caption() ?></span></th>
<?php } ?>
<?php if ($Page->file_18->Visible) { // file_18 ?>
        <th class="<?= $Page->file_18->headerCellClass() ?>"><span id="elh_pertanggungjawaban_file_18" class="pertanggungjawaban_file_18"><?= $Page->file_18->caption() ?></span></th>
<?php } ?>
<?php if ($Page->file_19->Visible) { // file_19 ?>
        <th class="<?= $Page->file_19->headerCellClass() ?>"><span id="elh_pertanggungjawaban_file_19" class="pertanggungjawaban_file_19"><?= $Page->file_19->caption() ?></span></th>
<?php } ?>
<?php if ($Page->file_20->Visible) { // file_20 ?>
        <th class="<?= $Page->file_20->headerCellClass() ?>"><span id="elh_pertanggungjawaban_file_20" class="pertanggungjawaban_file_20"><?= $Page->file_20->caption() ?></span></th>
<?php } ?>
<?php if ($Page->file_21->Visible) { // file_21 ?>
        <th class="<?= $Page->file_21->headerCellClass() ?>"><span id="elh_pertanggungjawaban_file_21" class="pertanggungjawaban_file_21"><?= $Page->file_21->caption() ?></span></th>
<?php } ?>
<?php if ($Page->file_22->Visible) { // file_22 ?>
        <th class="<?= $Page->file_22->headerCellClass() ?>"><span id="elh_pertanggungjawaban_file_22" class="pertanggungjawaban_file_22"><?= $Page->file_22->caption() ?></span></th>
<?php } ?>
<?php if ($Page->file_23->Visible) { // file_23 ?>
        <th class="<?= $Page->file_23->headerCellClass() ?>"><span id="elh_pertanggungjawaban_file_23" class="pertanggungjawaban_file_23"><?= $Page->file_23->caption() ?></span></th>
<?php } ?>
<?php if ($Page->file_24->Visible) { // file_24 ?>
        <th class="<?= $Page->file_24->headerCellClass() ?>"><span id="elh_pertanggungjawaban_file_24" class="pertanggungjawaban_file_24"><?= $Page->file_24->caption() ?></span></th>
<?php } ?>
<?php if ($Page->status->Visible) { // status ?>
        <th class="<?= $Page->status->headerCellClass() ?>"><span id="elh_pertanggungjawaban_status" class="pertanggungjawaban_status"><?= $Page->status->caption() ?></span></th>
<?php } ?>
<?php if ($Page->idd_user->Visible) { // idd_user ?>
        <th class="<?= $Page->idd_user->headerCellClass() ?>"><span id="elh_pertanggungjawaban_idd_user" class="pertanggungjawaban_idd_user"><?= $Page->idd_user->caption() ?></span></th>
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
<?php if ($Page->idd_evaluasi->Visible) { // idd_evaluasi ?>
        <td <?= $Page->idd_evaluasi->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pertanggungjawaban_idd_evaluasi" class="pertanggungjawaban_idd_evaluasi">
<span<?= $Page->idd_evaluasi->viewAttributes() ?>>
<?= $Page->idd_evaluasi->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->tanggal->Visible) { // tanggal ?>
        <td <?= $Page->tanggal->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pertanggungjawaban_tanggal" class="pertanggungjawaban_tanggal">
<span<?= $Page->tanggal->viewAttributes() ?>>
<?= $Page->tanggal->getViewValue() ?></span>
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
<?php if ($Page->idd_wilayah->Visible) { // idd_wilayah ?>
        <td <?= $Page->idd_wilayah->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pertanggungjawaban_idd_wilayah" class="pertanggungjawaban_idd_wilayah">
<span<?= $Page->idd_wilayah->viewAttributes() ?>>
<?= $Page->idd_wilayah->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->file_01->Visible) { // file_01 ?>
        <td <?= $Page->file_01->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pertanggungjawaban_file_01" class="pertanggungjawaban_file_01">
<span<?= $Page->file_01->viewAttributes() ?>>
<?= $Page->file_01->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->file_02->Visible) { // file_02 ?>
        <td <?= $Page->file_02->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pertanggungjawaban_file_02" class="pertanggungjawaban_file_02">
<span<?= $Page->file_02->viewAttributes() ?>>
<?= $Page->file_02->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->file_03->Visible) { // file_03 ?>
        <td <?= $Page->file_03->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pertanggungjawaban_file_03" class="pertanggungjawaban_file_03">
<span<?= $Page->file_03->viewAttributes() ?>>
<?= $Page->file_03->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->file_04->Visible) { // file_04 ?>
        <td <?= $Page->file_04->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pertanggungjawaban_file_04" class="pertanggungjawaban_file_04">
<span<?= $Page->file_04->viewAttributes() ?>>
<?= $Page->file_04->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->file_05->Visible) { // file_05 ?>
        <td <?= $Page->file_05->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pertanggungjawaban_file_05" class="pertanggungjawaban_file_05">
<span<?= $Page->file_05->viewAttributes() ?>>
<?= $Page->file_05->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->file_06->Visible) { // file_06 ?>
        <td <?= $Page->file_06->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pertanggungjawaban_file_06" class="pertanggungjawaban_file_06">
<span<?= $Page->file_06->viewAttributes() ?>>
<?= $Page->file_06->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->file_07->Visible) { // file_07 ?>
        <td <?= $Page->file_07->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pertanggungjawaban_file_07" class="pertanggungjawaban_file_07">
<span<?= $Page->file_07->viewAttributes() ?>>
<?= $Page->file_07->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->file_08->Visible) { // file_08 ?>
        <td <?= $Page->file_08->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pertanggungjawaban_file_08" class="pertanggungjawaban_file_08">
<span<?= $Page->file_08->viewAttributes() ?>>
<?= $Page->file_08->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->file_09->Visible) { // file_09 ?>
        <td <?= $Page->file_09->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pertanggungjawaban_file_09" class="pertanggungjawaban_file_09">
<span<?= $Page->file_09->viewAttributes() ?>>
<?= $Page->file_09->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->file_10->Visible) { // file_10 ?>
        <td <?= $Page->file_10->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pertanggungjawaban_file_10" class="pertanggungjawaban_file_10">
<span<?= $Page->file_10->viewAttributes() ?>>
<?= $Page->file_10->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->file_11->Visible) { // file_11 ?>
        <td <?= $Page->file_11->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pertanggungjawaban_file_11" class="pertanggungjawaban_file_11">
<span<?= $Page->file_11->viewAttributes() ?>>
<?= $Page->file_11->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->file_12->Visible) { // file_12 ?>
        <td <?= $Page->file_12->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pertanggungjawaban_file_12" class="pertanggungjawaban_file_12">
<span<?= $Page->file_12->viewAttributes() ?>>
<?= $Page->file_12->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->file_13->Visible) { // file_13 ?>
        <td <?= $Page->file_13->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pertanggungjawaban_file_13" class="pertanggungjawaban_file_13">
<span<?= $Page->file_13->viewAttributes() ?>>
<?= $Page->file_13->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->file_14->Visible) { // file_14 ?>
        <td <?= $Page->file_14->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pertanggungjawaban_file_14" class="pertanggungjawaban_file_14">
<span<?= $Page->file_14->viewAttributes() ?>>
<?= $Page->file_14->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->file_15->Visible) { // file_15 ?>
        <td <?= $Page->file_15->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pertanggungjawaban_file_15" class="pertanggungjawaban_file_15">
<span<?= $Page->file_15->viewAttributes() ?>>
<?= $Page->file_15->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->file_16->Visible) { // file_16 ?>
        <td <?= $Page->file_16->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pertanggungjawaban_file_16" class="pertanggungjawaban_file_16">
<span<?= $Page->file_16->viewAttributes() ?>>
<?= $Page->file_16->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->file_17->Visible) { // file_17 ?>
        <td <?= $Page->file_17->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pertanggungjawaban_file_17" class="pertanggungjawaban_file_17">
<span<?= $Page->file_17->viewAttributes() ?>>
<?= $Page->file_17->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->file_18->Visible) { // file_18 ?>
        <td <?= $Page->file_18->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pertanggungjawaban_file_18" class="pertanggungjawaban_file_18">
<span<?= $Page->file_18->viewAttributes() ?>>
<?= $Page->file_18->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->file_19->Visible) { // file_19 ?>
        <td <?= $Page->file_19->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pertanggungjawaban_file_19" class="pertanggungjawaban_file_19">
<span<?= $Page->file_19->viewAttributes() ?>>
<?= $Page->file_19->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->file_20->Visible) { // file_20 ?>
        <td <?= $Page->file_20->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pertanggungjawaban_file_20" class="pertanggungjawaban_file_20">
<span<?= $Page->file_20->viewAttributes() ?>>
<?= $Page->file_20->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->file_21->Visible) { // file_21 ?>
        <td <?= $Page->file_21->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pertanggungjawaban_file_21" class="pertanggungjawaban_file_21">
<span<?= $Page->file_21->viewAttributes() ?>>
<?= $Page->file_21->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->file_22->Visible) { // file_22 ?>
        <td <?= $Page->file_22->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pertanggungjawaban_file_22" class="pertanggungjawaban_file_22">
<span<?= $Page->file_22->viewAttributes() ?>>
<?= $Page->file_22->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->file_23->Visible) { // file_23 ?>
        <td <?= $Page->file_23->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pertanggungjawaban_file_23" class="pertanggungjawaban_file_23">
<span<?= $Page->file_23->viewAttributes() ?>>
<?= $Page->file_23->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->file_24->Visible) { // file_24 ?>
        <td <?= $Page->file_24->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pertanggungjawaban_file_24" class="pertanggungjawaban_file_24">
<span<?= $Page->file_24->viewAttributes() ?>>
<?= $Page->file_24->getViewValue() ?></span>
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
<?php if ($Page->idd_user->Visible) { // idd_user ?>
        <td <?= $Page->idd_user->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pertanggungjawaban_idd_user" class="pertanggungjawaban_idd_user">
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
