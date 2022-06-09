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
<?php if ($Page->idd_wilayah->Visible) { // idd_wilayah ?>
        <th class="<?= $Page->idd_wilayah->headerCellClass() ?>"><span id="elh_pertanggungjawaban_idd_wilayah" class="pertanggungjawaban_idd_wilayah"><?= $Page->idd_wilayah->caption() ?></span></th>
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
<?php if ($Page->surat_pengantar->Visible) { // surat_pengantar ?>
        <th class="<?= $Page->surat_pengantar->headerCellClass() ?>"><span id="elh_pertanggungjawaban_surat_pengantar" class="pertanggungjawaban_surat_pengantar"><?= $Page->surat_pengantar->caption() ?></span></th>
<?php } ?>
<?php if ($Page->skd_rqanunpert->Visible) { // skd_rqanunpert ?>
        <th class="<?= $Page->skd_rqanunpert->headerCellClass() ?>"><span id="elh_pertanggungjawaban_skd_rqanunpert" class="pertanggungjawaban_skd_rqanunpert"><?= $Page->skd_rqanunpert->caption() ?></span></th>
<?php } ?>
<?php if ($Page->rq_apbkpert->Visible) { // rq_apbkpert ?>
        <th class="<?= $Page->rq_apbkpert->headerCellClass() ?>"><span id="elh_pertanggungjawaban_rq_apbkpert" class="pertanggungjawaban_rq_apbkpert"><?= $Page->rq_apbkpert->caption() ?></span></th>
<?php } ?>
<?php if ($Page->bap_apbkpert->Visible) { // bap_apbkpert ?>
        <th class="<?= $Page->bap_apbkpert->headerCellClass() ?>"><span id="elh_pertanggungjawaban_bap_apbkpert" class="pertanggungjawaban_bap_apbkpert"><?= $Page->bap_apbkpert->caption() ?></span></th>
<?php } ?>
<?php if ($Page->risalah_sidang->Visible) { // risalah_sidang ?>
        <th class="<?= $Page->risalah_sidang->headerCellClass() ?>"><span id="elh_pertanggungjawaban_risalah_sidang" class="pertanggungjawaban_risalah_sidang"><?= $Page->risalah_sidang->caption() ?></span></th>
<?php } ?>
<?php if ($Page->absen_peserta->Visible) { // absen_peserta ?>
        <th class="<?= $Page->absen_peserta->headerCellClass() ?>"><span id="elh_pertanggungjawaban_absen_peserta" class="pertanggungjawaban_absen_peserta"><?= $Page->absen_peserta->caption() ?></span></th>
<?php } ?>
<?php if ($Page->neraca->Visible) { // neraca ?>
        <th class="<?= $Page->neraca->headerCellClass() ?>"><span id="elh_pertanggungjawaban_neraca" class="pertanggungjawaban_neraca"><?= $Page->neraca->caption() ?></span></th>
<?php } ?>
<?php if ($Page->lra->Visible) { // lra ?>
        <th class="<?= $Page->lra->headerCellClass() ?>"><span id="elh_pertanggungjawaban_lra" class="pertanggungjawaban_lra"><?= $Page->lra->caption() ?></span></th>
<?php } ?>
<?php if ($Page->calk->Visible) { // calk ?>
        <th class="<?= $Page->calk->headerCellClass() ?>"><span id="elh_pertanggungjawaban_calk" class="pertanggungjawaban_calk"><?= $Page->calk->caption() ?></span></th>
<?php } ?>
<?php if ($Page->lo->Visible) { // lo ?>
        <th class="<?= $Page->lo->headerCellClass() ?>"><span id="elh_pertanggungjawaban_lo" class="pertanggungjawaban_lo"><?= $Page->lo->caption() ?></span></th>
<?php } ?>
<?php if ($Page->lpe->Visible) { // lpe ?>
        <th class="<?= $Page->lpe->headerCellClass() ?>"><span id="elh_pertanggungjawaban_lpe" class="pertanggungjawaban_lpe"><?= $Page->lpe->caption() ?></span></th>
<?php } ?>
<?php if ($Page->lpsal->Visible) { // lpsal ?>
        <th class="<?= $Page->lpsal->headerCellClass() ?>"><span id="elh_pertanggungjawaban_lpsal" class="pertanggungjawaban_lpsal"><?= $Page->lpsal->caption() ?></span></th>
<?php } ?>
<?php if ($Page->lak->Visible) { // lak ?>
        <th class="<?= $Page->lak->headerCellClass() ?>"><span id="elh_pertanggungjawaban_lak" class="pertanggungjawaban_lak"><?= $Page->lak->caption() ?></span></th>
<?php } ?>
<?php if ($Page->laporan_pemeriksaan->Visible) { // laporan_pemeriksaan ?>
        <th class="<?= $Page->laporan_pemeriksaan->headerCellClass() ?>"><span id="elh_pertanggungjawaban_laporan_pemeriksaan" class="pertanggungjawaban_laporan_pemeriksaan"><?= $Page->laporan_pemeriksaan->caption() ?></span></th>
<?php } ?>
<?php if ($Page->softcopy_rqanun->Visible) { // softcopy_rqanun ?>
        <th class="<?= $Page->softcopy_rqanun->headerCellClass() ?>"><span id="elh_pertanggungjawaban_softcopy_rqanun" class="pertanggungjawaban_softcopy_rqanun"><?= $Page->softcopy_rqanun->caption() ?></span></th>
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
<?php if ($Page->idd_wilayah->Visible) { // idd_wilayah ?>
        <td <?= $Page->idd_wilayah->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pertanggungjawaban_idd_wilayah" class="pertanggungjawaban_idd_wilayah">
<span<?= $Page->idd_wilayah->viewAttributes() ?>>
<?= $Page->idd_wilayah->getViewValue() ?></span>
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
<?php if ($Page->surat_pengantar->Visible) { // surat_pengantar ?>
        <td <?= $Page->surat_pengantar->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pertanggungjawaban_surat_pengantar" class="pertanggungjawaban_surat_pengantar">
<span<?= $Page->surat_pengantar->viewAttributes() ?>>
<?= GetFileViewTag($Page->surat_pengantar, $Page->surat_pengantar->getViewValue(), false) ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($Page->skd_rqanunpert->Visible) { // skd_rqanunpert ?>
        <td <?= $Page->skd_rqanunpert->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pertanggungjawaban_skd_rqanunpert" class="pertanggungjawaban_skd_rqanunpert">
<span<?= $Page->skd_rqanunpert->viewAttributes() ?>>
<?= GetFileViewTag($Page->skd_rqanunpert, $Page->skd_rqanunpert->getViewValue(), false) ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($Page->rq_apbkpert->Visible) { // rq_apbkpert ?>
        <td <?= $Page->rq_apbkpert->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pertanggungjawaban_rq_apbkpert" class="pertanggungjawaban_rq_apbkpert">
<span<?= $Page->rq_apbkpert->viewAttributes() ?>>
<?= GetFileViewTag($Page->rq_apbkpert, $Page->rq_apbkpert->getViewValue(), false) ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($Page->bap_apbkpert->Visible) { // bap_apbkpert ?>
        <td <?= $Page->bap_apbkpert->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pertanggungjawaban_bap_apbkpert" class="pertanggungjawaban_bap_apbkpert">
<span<?= $Page->bap_apbkpert->viewAttributes() ?>>
<?= GetFileViewTag($Page->bap_apbkpert, $Page->bap_apbkpert->getViewValue(), false) ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($Page->risalah_sidang->Visible) { // risalah_sidang ?>
        <td <?= $Page->risalah_sidang->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pertanggungjawaban_risalah_sidang" class="pertanggungjawaban_risalah_sidang">
<span<?= $Page->risalah_sidang->viewAttributes() ?>>
<?= GetFileViewTag($Page->risalah_sidang, $Page->risalah_sidang->getViewValue(), false) ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($Page->absen_peserta->Visible) { // absen_peserta ?>
        <td <?= $Page->absen_peserta->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pertanggungjawaban_absen_peserta" class="pertanggungjawaban_absen_peserta">
<span<?= $Page->absen_peserta->viewAttributes() ?>>
<?= GetFileViewTag($Page->absen_peserta, $Page->absen_peserta->getViewValue(), false) ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($Page->neraca->Visible) { // neraca ?>
        <td <?= $Page->neraca->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pertanggungjawaban_neraca" class="pertanggungjawaban_neraca">
<span<?= $Page->neraca->viewAttributes() ?>>
<?= GetFileViewTag($Page->neraca, $Page->neraca->getViewValue(), false) ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($Page->lra->Visible) { // lra ?>
        <td <?= $Page->lra->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pertanggungjawaban_lra" class="pertanggungjawaban_lra">
<span<?= $Page->lra->viewAttributes() ?>>
<?= GetFileViewTag($Page->lra, $Page->lra->getViewValue(), false) ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($Page->calk->Visible) { // calk ?>
        <td <?= $Page->calk->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pertanggungjawaban_calk" class="pertanggungjawaban_calk">
<span<?= $Page->calk->viewAttributes() ?>>
<?= GetFileViewTag($Page->calk, $Page->calk->getViewValue(), false) ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($Page->lo->Visible) { // lo ?>
        <td <?= $Page->lo->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pertanggungjawaban_lo" class="pertanggungjawaban_lo">
<span<?= $Page->lo->viewAttributes() ?>>
<?= GetFileViewTag($Page->lo, $Page->lo->getViewValue(), false) ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($Page->lpe->Visible) { // lpe ?>
        <td <?= $Page->lpe->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pertanggungjawaban_lpe" class="pertanggungjawaban_lpe">
<span<?= $Page->lpe->viewAttributes() ?>>
<?= GetFileViewTag($Page->lpe, $Page->lpe->getViewValue(), false) ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($Page->lpsal->Visible) { // lpsal ?>
        <td <?= $Page->lpsal->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pertanggungjawaban_lpsal" class="pertanggungjawaban_lpsal">
<span<?= $Page->lpsal->viewAttributes() ?>>
<?= GetFileViewTag($Page->lpsal, $Page->lpsal->getViewValue(), false) ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($Page->lak->Visible) { // lak ?>
        <td <?= $Page->lak->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pertanggungjawaban_lak" class="pertanggungjawaban_lak">
<span<?= $Page->lak->viewAttributes() ?>>
<?= GetFileViewTag($Page->lak, $Page->lak->getViewValue(), false) ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($Page->laporan_pemeriksaan->Visible) { // laporan_pemeriksaan ?>
        <td <?= $Page->laporan_pemeriksaan->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pertanggungjawaban_laporan_pemeriksaan" class="pertanggungjawaban_laporan_pemeriksaan">
<span<?= $Page->laporan_pemeriksaan->viewAttributes() ?>>
<?= GetFileViewTag($Page->laporan_pemeriksaan, $Page->laporan_pemeriksaan->getViewValue(), false) ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($Page->softcopy_rqanun->Visible) { // softcopy_rqanun ?>
        <td <?= $Page->softcopy_rqanun->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pertanggungjawaban_softcopy_rqanun" class="pertanggungjawaban_softcopy_rqanun">
<span<?= $Page->softcopy_rqanun->viewAttributes() ?>>
<?= GetFileViewTag($Page->softcopy_rqanun, $Page->softcopy_rqanun->getViewValue(), false) ?>
</span>
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
