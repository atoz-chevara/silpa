<?php

namespace PHPMaker2021\silpa;

// Page object
$Pertanggungjawaban2022Delete = &$Page;
?>
<script>
var currentForm, currentPageID;
var fpertanggungjawaban2022delete;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "delete";
    fpertanggungjawaban2022delete = currentForm = new ew.Form("fpertanggungjawaban2022delete", "delete");
    loadjs.done("fpertanggungjawaban2022delete");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<script>
if (!ew.vars.tables.pertanggungjawaban2022) ew.vars.tables.pertanggungjawaban2022 = <?= JsonEncode(GetClientVar("tables", "pertanggungjawaban2022")) ?>;
</script>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<form name="fpertanggungjawaban2022delete" id="fpertanggungjawaban2022delete" class="form-inline ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="pertanggungjawaban2022">
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
        <th class="<?= $Page->idd_evaluasi->headerCellClass() ?>"><span id="elh_pertanggungjawaban2022_idd_evaluasi" class="pertanggungjawaban2022_idd_evaluasi"><?= $Page->idd_evaluasi->caption() ?></span></th>
<?php } ?>
<?php if ($Page->tanggal->Visible) { // tanggal ?>
        <th class="<?= $Page->tanggal->headerCellClass() ?>"><span id="elh_pertanggungjawaban2022_tanggal" class="pertanggungjawaban2022_tanggal"><?= $Page->tanggal->caption() ?></span></th>
<?php } ?>
<?php if ($Page->kd_satker->Visible) { // kd_satker ?>
        <th class="<?= $Page->kd_satker->headerCellClass() ?>"><span id="elh_pertanggungjawaban2022_kd_satker" class="pertanggungjawaban2022_kd_satker"><?= $Page->kd_satker->caption() ?></span></th>
<?php } ?>
<?php if ($Page->idd_tahapan->Visible) { // idd_tahapan ?>
        <th class="<?= $Page->idd_tahapan->headerCellClass() ?>"><span id="elh_pertanggungjawaban2022_idd_tahapan" class="pertanggungjawaban2022_idd_tahapan"><?= $Page->idd_tahapan->caption() ?></span></th>
<?php } ?>
<?php if ($Page->tahun_anggaran->Visible) { // tahun_anggaran ?>
        <th class="<?= $Page->tahun_anggaran->headerCellClass() ?>"><span id="elh_pertanggungjawaban2022_tahun_anggaran" class="pertanggungjawaban2022_tahun_anggaran"><?= $Page->tahun_anggaran->caption() ?></span></th>
<?php } ?>
<?php if ($Page->surat_pengantar->Visible) { // surat_pengantar ?>
        <th class="<?= $Page->surat_pengantar->headerCellClass() ?>"><span id="elh_pertanggungjawaban2022_surat_pengantar" class="pertanggungjawaban2022_surat_pengantar"><?= $Page->surat_pengantar->caption() ?></span></th>
<?php } ?>
<?php if ($Page->skd_rqanunpert->Visible) { // skd_rqanunpert ?>
        <th class="<?= $Page->skd_rqanunpert->headerCellClass() ?>"><span id="elh_pertanggungjawaban2022_skd_rqanunpert" class="pertanggungjawaban2022_skd_rqanunpert"><?= $Page->skd_rqanunpert->caption() ?></span></th>
<?php } ?>
<?php if ($Page->rqanun_apbkpert->Visible) { // rqanun_apbkpert ?>
        <th class="<?= $Page->rqanun_apbkpert->headerCellClass() ?>"><span id="elh_pertanggungjawaban2022_rqanun_apbkpert" class="pertanggungjawaban2022_rqanun_apbkpert"><?= $Page->rqanun_apbkpert->caption() ?></span></th>
<?php } ?>
<?php if ($Page->rperbup_apbkpert->Visible) { // rperbup_apbkpert ?>
        <th class="<?= $Page->rperbup_apbkpert->headerCellClass() ?>"><span id="elh_pertanggungjawaban2022_rperbup_apbkpert" class="pertanggungjawaban2022_rperbup_apbkpert"><?= $Page->rperbup_apbkpert->caption() ?></span></th>
<?php } ?>
<?php if ($Page->pbkdd_apbkpert->Visible) { // pbkdd_apbkpert ?>
        <th class="<?= $Page->pbkdd_apbkpert->headerCellClass() ?>"><span id="elh_pertanggungjawaban2022_pbkdd_apbkpert" class="pertanggungjawaban2022_pbkdd_apbkpert"><?= $Page->pbkdd_apbkpert->caption() ?></span></th>
<?php } ?>
<?php if ($Page->risalah_sidang->Visible) { // risalah_sidang ?>
        <th class="<?= $Page->risalah_sidang->headerCellClass() ?>"><span id="elh_pertanggungjawaban2022_risalah_sidang" class="pertanggungjawaban2022_risalah_sidang"><?= $Page->risalah_sidang->caption() ?></span></th>
<?php } ?>
<?php if ($Page->absen_peserta->Visible) { // absen_peserta ?>
        <th class="<?= $Page->absen_peserta->headerCellClass() ?>"><span id="elh_pertanggungjawaban2022_absen_peserta" class="pertanggungjawaban2022_absen_peserta"><?= $Page->absen_peserta->caption() ?></span></th>
<?php } ?>
<?php if ($Page->neraca->Visible) { // neraca ?>
        <th class="<?= $Page->neraca->headerCellClass() ?>"><span id="elh_pertanggungjawaban2022_neraca" class="pertanggungjawaban2022_neraca"><?= $Page->neraca->caption() ?></span></th>
<?php } ?>
<?php if ($Page->lra->Visible) { // lra ?>
        <th class="<?= $Page->lra->headerCellClass() ?>"><span id="elh_pertanggungjawaban2022_lra" class="pertanggungjawaban2022_lra"><?= $Page->lra->caption() ?></span></th>
<?php } ?>
<?php if ($Page->calk->Visible) { // calk ?>
        <th class="<?= $Page->calk->headerCellClass() ?>"><span id="elh_pertanggungjawaban2022_calk" class="pertanggungjawaban2022_calk"><?= $Page->calk->caption() ?></span></th>
<?php } ?>
<?php if ($Page->lo->Visible) { // lo ?>
        <th class="<?= $Page->lo->headerCellClass() ?>"><span id="elh_pertanggungjawaban2022_lo" class="pertanggungjawaban2022_lo"><?= $Page->lo->caption() ?></span></th>
<?php } ?>
<?php if ($Page->lpe->Visible) { // lpe ?>
        <th class="<?= $Page->lpe->headerCellClass() ?>"><span id="elh_pertanggungjawaban2022_lpe" class="pertanggungjawaban2022_lpe"><?= $Page->lpe->caption() ?></span></th>
<?php } ?>
<?php if ($Page->lpsal->Visible) { // lpsal ?>
        <th class="<?= $Page->lpsal->headerCellClass() ?>"><span id="elh_pertanggungjawaban2022_lpsal" class="pertanggungjawaban2022_lpsal"><?= $Page->lpsal->caption() ?></span></th>
<?php } ?>
<?php if ($Page->lak->Visible) { // lak ?>
        <th class="<?= $Page->lak->headerCellClass() ?>"><span id="elh_pertanggungjawaban2022_lak" class="pertanggungjawaban2022_lak"><?= $Page->lak->caption() ?></span></th>
<?php } ?>
<?php if ($Page->laporan_pemeriksaan->Visible) { // laporan_pemeriksaan ?>
        <th class="<?= $Page->laporan_pemeriksaan->headerCellClass() ?>"><span id="elh_pertanggungjawaban2022_laporan_pemeriksaan" class="pertanggungjawaban2022_laporan_pemeriksaan"><?= $Page->laporan_pemeriksaan->caption() ?></span></th>
<?php } ?>
<?php if ($Page->status->Visible) { // status ?>
        <th class="<?= $Page->status->headerCellClass() ?>"><span id="elh_pertanggungjawaban2022_status" class="pertanggungjawaban2022_status"><?= $Page->status->caption() ?></span></th>
<?php } ?>
<?php if ($Page->idd_user->Visible) { // idd_user ?>
        <th class="<?= $Page->idd_user->headerCellClass() ?>"><span id="elh_pertanggungjawaban2022_idd_user" class="pertanggungjawaban2022_idd_user"><?= $Page->idd_user->caption() ?></span></th>
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
<span id="el<?= $Page->RowCount ?>_pertanggungjawaban2022_idd_evaluasi" class="pertanggungjawaban2022_idd_evaluasi">
<span<?= $Page->idd_evaluasi->viewAttributes() ?>>
<?= $Page->idd_evaluasi->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->tanggal->Visible) { // tanggal ?>
        <td <?= $Page->tanggal->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pertanggungjawaban2022_tanggal" class="pertanggungjawaban2022_tanggal">
<span<?= $Page->tanggal->viewAttributes() ?>>
<?= $Page->tanggal->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->kd_satker->Visible) { // kd_satker ?>
        <td <?= $Page->kd_satker->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pertanggungjawaban2022_kd_satker" class="pertanggungjawaban2022_kd_satker">
<span<?= $Page->kd_satker->viewAttributes() ?>>
<?= $Page->kd_satker->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->idd_tahapan->Visible) { // idd_tahapan ?>
        <td <?= $Page->idd_tahapan->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pertanggungjawaban2022_idd_tahapan" class="pertanggungjawaban2022_idd_tahapan">
<span<?= $Page->idd_tahapan->viewAttributes() ?>>
<?= $Page->idd_tahapan->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->tahun_anggaran->Visible) { // tahun_anggaran ?>
        <td <?= $Page->tahun_anggaran->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pertanggungjawaban2022_tahun_anggaran" class="pertanggungjawaban2022_tahun_anggaran">
<span<?= $Page->tahun_anggaran->viewAttributes() ?>>
<?= $Page->tahun_anggaran->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->surat_pengantar->Visible) { // surat_pengantar ?>
        <td <?= $Page->surat_pengantar->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pertanggungjawaban2022_surat_pengantar" class="pertanggungjawaban2022_surat_pengantar">
<span<?= $Page->surat_pengantar->viewAttributes() ?>>
<?= GetFileViewTag($Page->surat_pengantar, $Page->surat_pengantar->getViewValue(), false) ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($Page->skd_rqanunpert->Visible) { // skd_rqanunpert ?>
        <td <?= $Page->skd_rqanunpert->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pertanggungjawaban2022_skd_rqanunpert" class="pertanggungjawaban2022_skd_rqanunpert">
<span<?= $Page->skd_rqanunpert->viewAttributes() ?>>
<?= GetFileViewTag($Page->skd_rqanunpert, $Page->skd_rqanunpert->getViewValue(), false) ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($Page->rqanun_apbkpert->Visible) { // rqanun_apbkpert ?>
        <td <?= $Page->rqanun_apbkpert->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pertanggungjawaban2022_rqanun_apbkpert" class="pertanggungjawaban2022_rqanun_apbkpert">
<span<?= $Page->rqanun_apbkpert->viewAttributes() ?>>
<?= GetFileViewTag($Page->rqanun_apbkpert, $Page->rqanun_apbkpert->getViewValue(), false) ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($Page->rperbup_apbkpert->Visible) { // rperbup_apbkpert ?>
        <td <?= $Page->rperbup_apbkpert->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pertanggungjawaban2022_rperbup_apbkpert" class="pertanggungjawaban2022_rperbup_apbkpert">
<span<?= $Page->rperbup_apbkpert->viewAttributes() ?>>
<?= GetFileViewTag($Page->rperbup_apbkpert, $Page->rperbup_apbkpert->getViewValue(), false) ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($Page->pbkdd_apbkpert->Visible) { // pbkdd_apbkpert ?>
        <td <?= $Page->pbkdd_apbkpert->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pertanggungjawaban2022_pbkdd_apbkpert" class="pertanggungjawaban2022_pbkdd_apbkpert">
<span<?= $Page->pbkdd_apbkpert->viewAttributes() ?>>
<?= GetFileViewTag($Page->pbkdd_apbkpert, $Page->pbkdd_apbkpert->getViewValue(), false) ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($Page->risalah_sidang->Visible) { // risalah_sidang ?>
        <td <?= $Page->risalah_sidang->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pertanggungjawaban2022_risalah_sidang" class="pertanggungjawaban2022_risalah_sidang">
<span<?= $Page->risalah_sidang->viewAttributes() ?>>
<?= GetFileViewTag($Page->risalah_sidang, $Page->risalah_sidang->getViewValue(), false) ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($Page->absen_peserta->Visible) { // absen_peserta ?>
        <td <?= $Page->absen_peserta->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pertanggungjawaban2022_absen_peserta" class="pertanggungjawaban2022_absen_peserta">
<span<?= $Page->absen_peserta->viewAttributes() ?>>
<?= GetFileViewTag($Page->absen_peserta, $Page->absen_peserta->getViewValue(), false) ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($Page->neraca->Visible) { // neraca ?>
        <td <?= $Page->neraca->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pertanggungjawaban2022_neraca" class="pertanggungjawaban2022_neraca">
<span<?= $Page->neraca->viewAttributes() ?>>
<?= GetFileViewTag($Page->neraca, $Page->neraca->getViewValue(), false) ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($Page->lra->Visible) { // lra ?>
        <td <?= $Page->lra->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pertanggungjawaban2022_lra" class="pertanggungjawaban2022_lra">
<span<?= $Page->lra->viewAttributes() ?>>
<?= GetFileViewTag($Page->lra, $Page->lra->getViewValue(), false) ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($Page->calk->Visible) { // calk ?>
        <td <?= $Page->calk->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pertanggungjawaban2022_calk" class="pertanggungjawaban2022_calk">
<span<?= $Page->calk->viewAttributes() ?>>
<?= GetFileViewTag($Page->calk, $Page->calk->getViewValue(), false) ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($Page->lo->Visible) { // lo ?>
        <td <?= $Page->lo->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pertanggungjawaban2022_lo" class="pertanggungjawaban2022_lo">
<span<?= $Page->lo->viewAttributes() ?>>
<?= GetFileViewTag($Page->lo, $Page->lo->getViewValue(), false) ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($Page->lpe->Visible) { // lpe ?>
        <td <?= $Page->lpe->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pertanggungjawaban2022_lpe" class="pertanggungjawaban2022_lpe">
<span<?= $Page->lpe->viewAttributes() ?>>
<?= GetFileViewTag($Page->lpe, $Page->lpe->getViewValue(), false) ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($Page->lpsal->Visible) { // lpsal ?>
        <td <?= $Page->lpsal->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pertanggungjawaban2022_lpsal" class="pertanggungjawaban2022_lpsal">
<span<?= $Page->lpsal->viewAttributes() ?>>
<?= GetFileViewTag($Page->lpsal, $Page->lpsal->getViewValue(), false) ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($Page->lak->Visible) { // lak ?>
        <td <?= $Page->lak->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pertanggungjawaban2022_lak" class="pertanggungjawaban2022_lak">
<span<?= $Page->lak->viewAttributes() ?>>
<?= GetFileViewTag($Page->lak, $Page->lak->getViewValue(), false) ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($Page->laporan_pemeriksaan->Visible) { // laporan_pemeriksaan ?>
        <td <?= $Page->laporan_pemeriksaan->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pertanggungjawaban2022_laporan_pemeriksaan" class="pertanggungjawaban2022_laporan_pemeriksaan">
<span<?= $Page->laporan_pemeriksaan->viewAttributes() ?>>
<?= GetFileViewTag($Page->laporan_pemeriksaan, $Page->laporan_pemeriksaan->getViewValue(), false) ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($Page->status->Visible) { // status ?>
        <td <?= $Page->status->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pertanggungjawaban2022_status" class="pertanggungjawaban2022_status">
<span<?= $Page->status->viewAttributes() ?>>
<?= $Page->status->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->idd_user->Visible) { // idd_user ?>
        <td <?= $Page->idd_user->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pertanggungjawaban2022_idd_user" class="pertanggungjawaban2022_idd_user">
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
