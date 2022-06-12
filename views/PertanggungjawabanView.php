<?php

namespace PHPMaker2021\silpa;

// Page object
$PertanggungjawabanView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var fpertanggungjawabanview;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "view";
    fpertanggungjawabanview = currentForm = new ew.Form("fpertanggungjawabanview", "view");
    loadjs.done("fpertanggungjawabanview");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php } ?>
<script>
if (!ew.vars.tables.pertanggungjawaban) ew.vars.tables.pertanggungjawaban = <?= JsonEncode(GetClientVar("tables", "pertanggungjawaban")) ?>;
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
<form name="fpertanggungjawabanview" id="fpertanggungjawabanview" class="form-inline ew-form ew-view-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="pertanggungjawaban">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->tanggal_upload->Visible) { // tanggal_upload ?>
    <tr id="r_tanggal_upload">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_pertanggungjawaban_tanggal_upload"><?= $Page->tanggal_upload->caption() ?></span></td>
        <td data-name="tanggal_upload" <?= $Page->tanggal_upload->cellAttributes() ?>>
<span id="el_pertanggungjawaban_tanggal_upload">
<span<?= $Page->tanggal_upload->viewAttributes() ?>>
<?= $Page->tanggal_upload->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->tanggal_update->Visible) { // tanggal_update ?>
    <tr id="r_tanggal_update">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_pertanggungjawaban_tanggal_update"><?= $Page->tanggal_update->caption() ?></span></td>
        <td data-name="tanggal_update" <?= $Page->tanggal_update->cellAttributes() ?>>
<span id="el_pertanggungjawaban_tanggal_update">
<span<?= $Page->tanggal_update->viewAttributes() ?>>
<?= $Page->tanggal_update->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->idd_wilayah->Visible) { // idd_wilayah ?>
    <tr id="r_idd_wilayah">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_pertanggungjawaban_idd_wilayah"><?= $Page->idd_wilayah->caption() ?></span></td>
        <td data-name="idd_wilayah" <?= $Page->idd_wilayah->cellAttributes() ?>>
<span id="el_pertanggungjawaban_idd_wilayah">
<span<?= $Page->idd_wilayah->viewAttributes() ?>>
<?= $Page->idd_wilayah->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->kd_satker->Visible) { // kd_satker ?>
    <tr id="r_kd_satker">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_pertanggungjawaban_kd_satker"><?= $Page->kd_satker->caption() ?></span></td>
        <td data-name="kd_satker" <?= $Page->kd_satker->cellAttributes() ?>>
<span id="el_pertanggungjawaban_kd_satker">
<span<?= $Page->kd_satker->viewAttributes() ?>>
<?= $Page->kd_satker->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->idd_tahapan->Visible) { // idd_tahapan ?>
    <tr id="r_idd_tahapan">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_pertanggungjawaban_idd_tahapan"><?= $Page->idd_tahapan->caption() ?></span></td>
        <td data-name="idd_tahapan" <?= $Page->idd_tahapan->cellAttributes() ?>>
<span id="el_pertanggungjawaban_idd_tahapan">
<span<?= $Page->idd_tahapan->viewAttributes() ?>>
<?= $Page->idd_tahapan->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->tahun_anggaran->Visible) { // tahun_anggaran ?>
    <tr id="r_tahun_anggaran">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_pertanggungjawaban_tahun_anggaran"><?= $Page->tahun_anggaran->caption() ?></span></td>
        <td data-name="tahun_anggaran" <?= $Page->tahun_anggaran->cellAttributes() ?>>
<span id="el_pertanggungjawaban_tahun_anggaran">
<span<?= $Page->tahun_anggaran->viewAttributes() ?>>
<?= $Page->tahun_anggaran->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->surat_pengantar->Visible) { // surat_pengantar ?>
    <tr id="r_surat_pengantar">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_pertanggungjawaban_surat_pengantar"><?= $Page->surat_pengantar->caption() ?></span></td>
        <td data-name="surat_pengantar" <?= $Page->surat_pengantar->cellAttributes() ?>>
<span id="el_pertanggungjawaban_surat_pengantar">
<span<?= $Page->surat_pengantar->viewAttributes() ?>>
<?= GetFileViewTag($Page->surat_pengantar, $Page->surat_pengantar->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->skd_rqanunpert->Visible) { // skd_rqanunpert ?>
    <tr id="r_skd_rqanunpert">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_pertanggungjawaban_skd_rqanunpert"><?= $Page->skd_rqanunpert->caption() ?></span></td>
        <td data-name="skd_rqanunpert" <?= $Page->skd_rqanunpert->cellAttributes() ?>>
<span id="el_pertanggungjawaban_skd_rqanunpert">
<span<?= $Page->skd_rqanunpert->viewAttributes() ?>>
<?= GetFileViewTag($Page->skd_rqanunpert, $Page->skd_rqanunpert->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->rqanun_apbkpert->Visible) { // rqanun_apbkpert ?>
    <tr id="r_rqanun_apbkpert">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_pertanggungjawaban_rqanun_apbkpert"><?= $Page->rqanun_apbkpert->caption() ?></span></td>
        <td data-name="rqanun_apbkpert" <?= $Page->rqanun_apbkpert->cellAttributes() ?>>
<span id="el_pertanggungjawaban_rqanun_apbkpert">
<span<?= $Page->rqanun_apbkpert->viewAttributes() ?>>
<?= GetFileViewTag($Page->rqanun_apbkpert, $Page->rqanun_apbkpert->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->rperbup_apbkpert->Visible) { // rperbup_apbkpert ?>
    <tr id="r_rperbup_apbkpert">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_pertanggungjawaban_rperbup_apbkpert"><?= $Page->rperbup_apbkpert->caption() ?></span></td>
        <td data-name="rperbup_apbkpert" <?= $Page->rperbup_apbkpert->cellAttributes() ?>>
<span id="el_pertanggungjawaban_rperbup_apbkpert">
<span<?= $Page->rperbup_apbkpert->viewAttributes() ?>>
<?= GetFileViewTag($Page->rperbup_apbkpert, $Page->rperbup_apbkpert->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->pbkdd_apbkpert->Visible) { // pbkdd_apbkpert ?>
    <tr id="r_pbkdd_apbkpert">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_pertanggungjawaban_pbkdd_apbkpert"><?= $Page->pbkdd_apbkpert->caption() ?></span></td>
        <td data-name="pbkdd_apbkpert" <?= $Page->pbkdd_apbkpert->cellAttributes() ?>>
<span id="el_pertanggungjawaban_pbkdd_apbkpert">
<span<?= $Page->pbkdd_apbkpert->viewAttributes() ?>>
<?= GetFileViewTag($Page->pbkdd_apbkpert, $Page->pbkdd_apbkpert->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->risalah_sidang->Visible) { // risalah_sidang ?>
    <tr id="r_risalah_sidang">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_pertanggungjawaban_risalah_sidang"><?= $Page->risalah_sidang->caption() ?></span></td>
        <td data-name="risalah_sidang" <?= $Page->risalah_sidang->cellAttributes() ?>>
<span id="el_pertanggungjawaban_risalah_sidang">
<span<?= $Page->risalah_sidang->viewAttributes() ?>>
<?= GetFileViewTag($Page->risalah_sidang, $Page->risalah_sidang->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->absen_peserta->Visible) { // absen_peserta ?>
    <tr id="r_absen_peserta">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_pertanggungjawaban_absen_peserta"><?= $Page->absen_peserta->caption() ?></span></td>
        <td data-name="absen_peserta" <?= $Page->absen_peserta->cellAttributes() ?>>
<span id="el_pertanggungjawaban_absen_peserta">
<span<?= $Page->absen_peserta->viewAttributes() ?>>
<?= GetFileViewTag($Page->absen_peserta, $Page->absen_peserta->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->neraca->Visible) { // neraca ?>
    <tr id="r_neraca">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_pertanggungjawaban_neraca"><?= $Page->neraca->caption() ?></span></td>
        <td data-name="neraca" <?= $Page->neraca->cellAttributes() ?>>
<span id="el_pertanggungjawaban_neraca">
<span<?= $Page->neraca->viewAttributes() ?>>
<?= GetFileViewTag($Page->neraca, $Page->neraca->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->lra->Visible) { // lra ?>
    <tr id="r_lra">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_pertanggungjawaban_lra"><?= $Page->lra->caption() ?></span></td>
        <td data-name="lra" <?= $Page->lra->cellAttributes() ?>>
<span id="el_pertanggungjawaban_lra">
<span<?= $Page->lra->viewAttributes() ?>>
<?= GetFileViewTag($Page->lra, $Page->lra->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->calk->Visible) { // calk ?>
    <tr id="r_calk">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_pertanggungjawaban_calk"><?= $Page->calk->caption() ?></span></td>
        <td data-name="calk" <?= $Page->calk->cellAttributes() ?>>
<span id="el_pertanggungjawaban_calk">
<span<?= $Page->calk->viewAttributes() ?>>
<?= GetFileViewTag($Page->calk, $Page->calk->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->lo->Visible) { // lo ?>
    <tr id="r_lo">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_pertanggungjawaban_lo"><?= $Page->lo->caption() ?></span></td>
        <td data-name="lo" <?= $Page->lo->cellAttributes() ?>>
<span id="el_pertanggungjawaban_lo">
<span<?= $Page->lo->viewAttributes() ?>>
<?= GetFileViewTag($Page->lo, $Page->lo->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->lpe->Visible) { // lpe ?>
    <tr id="r_lpe">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_pertanggungjawaban_lpe"><?= $Page->lpe->caption() ?></span></td>
        <td data-name="lpe" <?= $Page->lpe->cellAttributes() ?>>
<span id="el_pertanggungjawaban_lpe">
<span<?= $Page->lpe->viewAttributes() ?>>
<?= GetFileViewTag($Page->lpe, $Page->lpe->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->lpsal->Visible) { // lpsal ?>
    <tr id="r_lpsal">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_pertanggungjawaban_lpsal"><?= $Page->lpsal->caption() ?></span></td>
        <td data-name="lpsal" <?= $Page->lpsal->cellAttributes() ?>>
<span id="el_pertanggungjawaban_lpsal">
<span<?= $Page->lpsal->viewAttributes() ?>>
<?= GetFileViewTag($Page->lpsal, $Page->lpsal->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->lak->Visible) { // lak ?>
    <tr id="r_lak">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_pertanggungjawaban_lak"><?= $Page->lak->caption() ?></span></td>
        <td data-name="lak" <?= $Page->lak->cellAttributes() ?>>
<span id="el_pertanggungjawaban_lak">
<span<?= $Page->lak->viewAttributes() ?>>
<?= GetFileViewTag($Page->lak, $Page->lak->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->laporan_pemeriksaan->Visible) { // laporan_pemeriksaan ?>
    <tr id="r_laporan_pemeriksaan">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_pertanggungjawaban_laporan_pemeriksaan"><?= $Page->laporan_pemeriksaan->caption() ?></span></td>
        <td data-name="laporan_pemeriksaan" <?= $Page->laporan_pemeriksaan->cellAttributes() ?>>
<span id="el_pertanggungjawaban_laporan_pemeriksaan">
<span<?= $Page->laporan_pemeriksaan->viewAttributes() ?>>
<?= GetFileViewTag($Page->laporan_pemeriksaan, $Page->laporan_pemeriksaan->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->status->Visible) { // status ?>
    <tr id="r_status">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_pertanggungjawaban_status"><?= $Page->status->caption() ?></span></td>
        <td data-name="status" <?= $Page->status->cellAttributes() ?>>
<span id="el_pertanggungjawaban_status">
<span<?= $Page->status->viewAttributes() ?>>
<?= $Page->status->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->idd_user->Visible) { // idd_user ?>
    <tr id="r_idd_user">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_pertanggungjawaban_idd_user"><?= $Page->idd_user->caption() ?></span></td>
        <td data-name="idd_user" <?= $Page->idd_user->cellAttributes() ?>>
<span id="el_pertanggungjawaban_idd_user">
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
