<?php

namespace PHPMaker2021\silpa;

// Page object
$EvaluasiView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var fevaluasiview;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "view";
    fevaluasiview = currentForm = new ew.Form("fevaluasiview", "view");
    loadjs.done("fevaluasiview");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php } ?>
<script>
if (!ew.vars.tables.evaluasi) ew.vars.tables.evaluasi = <?= JsonEncode(GetClientVar("tables", "evaluasi")) ?>;
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
<form name="fevaluasiview" id="fevaluasiview" class="form-inline ew-form ew-view-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="evaluasi">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->idd_evaluasi->Visible) { // idd_evaluasi ?>
    <tr id="r_idd_evaluasi">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_evaluasi_idd_evaluasi"><?= $Page->idd_evaluasi->caption() ?></span></td>
        <td data-name="idd_evaluasi" <?= $Page->idd_evaluasi->cellAttributes() ?>>
<span id="el_evaluasi_idd_evaluasi" data-page="1">
<span<?= $Page->idd_evaluasi->viewAttributes() ?>>
<?= $Page->idd_evaluasi->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->tanggal->Visible) { // tanggal ?>
    <tr id="r_tanggal">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_evaluasi_tanggal"><?= $Page->tanggal->caption() ?></span></td>
        <td data-name="tanggal" <?= $Page->tanggal->cellAttributes() ?>>
<span id="el_evaluasi_tanggal" data-page="1">
<span<?= $Page->tanggal->viewAttributes() ?>>
<?= $Page->tanggal->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->idd_wilayah->Visible) { // idd_wilayah ?>
    <tr id="r_idd_wilayah">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_evaluasi_idd_wilayah"><?= $Page->idd_wilayah->caption() ?></span></td>
        <td data-name="idd_wilayah" <?= $Page->idd_wilayah->cellAttributes() ?>>
<span id="el_evaluasi_idd_wilayah" data-page="1">
<span<?= $Page->idd_wilayah->viewAttributes() ?>>
<?= $Page->idd_wilayah->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->kd_satker->Visible) { // kd_satker ?>
    <tr id="r_kd_satker">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_evaluasi_kd_satker"><?= $Page->kd_satker->caption() ?></span></td>
        <td data-name="kd_satker" <?= $Page->kd_satker->cellAttributes() ?>>
<span id="el_evaluasi_kd_satker" data-page="1">
<span<?= $Page->kd_satker->viewAttributes() ?>>
<?= $Page->kd_satker->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->idd_tahapan->Visible) { // idd_tahapan ?>
    <tr id="r_idd_tahapan">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_evaluasi_idd_tahapan"><?= $Page->idd_tahapan->caption() ?></span></td>
        <td data-name="idd_tahapan" <?= $Page->idd_tahapan->cellAttributes() ?>>
<span id="el_evaluasi_idd_tahapan" data-page="1">
<span<?= $Page->idd_tahapan->viewAttributes() ?>>
<?= $Page->idd_tahapan->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->tahun_anggaran->Visible) { // tahun_anggaran ?>
    <tr id="r_tahun_anggaran">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_evaluasi_tahun_anggaran"><?= $Page->tahun_anggaran->caption() ?></span></td>
        <td data-name="tahun_anggaran" <?= $Page->tahun_anggaran->cellAttributes() ?>>
<span id="el_evaluasi_tahun_anggaran" data-page="1">
<span<?= $Page->tahun_anggaran->viewAttributes() ?>>
<?= $Page->tahun_anggaran->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->surat_pengantar->Visible) { // surat_pengantar ?>
    <tr id="r_surat_pengantar">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_evaluasi_surat_pengantar"><?= $Page->surat_pengantar->caption() ?></span></td>
        <td data-name="surat_pengantar" <?= $Page->surat_pengantar->cellAttributes() ?>>
<span id="el_evaluasi_surat_pengantar" data-page="1">
<span<?= $Page->surat_pengantar->viewAttributes() ?>>
<?= GetFileViewTag($Page->surat_pengantar, $Page->surat_pengantar->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->rpjmd->Visible) { // rpjmd ?>
    <tr id="r_rpjmd">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_evaluasi_rpjmd"><?= $Page->rpjmd->caption() ?></span></td>
        <td data-name="rpjmd" <?= $Page->rpjmd->cellAttributes() ?>>
<span id="el_evaluasi_rpjmd" data-page="1">
<span<?= $Page->rpjmd->viewAttributes() ?>>
<?= GetFileViewTag($Page->rpjmd, $Page->rpjmd->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->rkpk->Visible) { // rkpk ?>
    <tr id="r_rkpk">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_evaluasi_rkpk"><?= $Page->rkpk->caption() ?></span></td>
        <td data-name="rkpk" <?= $Page->rkpk->cellAttributes() ?>>
<span id="el_evaluasi_rkpk" data-page="1">
<span<?= $Page->rkpk->viewAttributes() ?>>
<?= GetFileViewTag($Page->rkpk, $Page->rkpk->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->skd_rkuappas->Visible) { // skd_rkuappas ?>
    <tr id="r_skd_rkuappas">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_evaluasi_skd_rkuappas"><?= $Page->skd_rkuappas->caption() ?></span></td>
        <td data-name="skd_rkuappas" <?= $Page->skd_rkuappas->cellAttributes() ?>>
<span id="el_evaluasi_skd_rkuappas" data-page="1">
<span<?= $Page->skd_rkuappas->viewAttributes() ?>>
<?= GetFileViewTag($Page->skd_rkuappas, $Page->skd_rkuappas->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->kua->Visible) { // kua ?>
    <tr id="r_kua">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_evaluasi_kua"><?= $Page->kua->caption() ?></span></td>
        <td data-name="kua" <?= $Page->kua->cellAttributes() ?>>
<span id="el_evaluasi_kua" data-page="1">
<span<?= $Page->kua->viewAttributes() ?>>
<?= GetFileViewTag($Page->kua, $Page->kua->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->ppas->Visible) { // ppas ?>
    <tr id="r_ppas">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_evaluasi_ppas"><?= $Page->ppas->caption() ?></span></td>
        <td data-name="ppas" <?= $Page->ppas->cellAttributes() ?>>
<span id="el_evaluasi_ppas" data-page="1">
<span<?= $Page->ppas->viewAttributes() ?>>
<?= GetFileViewTag($Page->ppas, $Page->ppas->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->skd_rqanun->Visible) { // skd_rqanun ?>
    <tr id="r_skd_rqanun">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_evaluasi_skd_rqanun"><?= $Page->skd_rqanun->caption() ?></span></td>
        <td data-name="skd_rqanun" <?= $Page->skd_rqanun->cellAttributes() ?>>
<span id="el_evaluasi_skd_rqanun" data-page="1">
<span<?= $Page->skd_rqanun->viewAttributes() ?>>
<?= GetFileViewTag($Page->skd_rqanun, $Page->skd_rqanun->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->nota_keuangan->Visible) { // nota_keuangan ?>
    <tr id="r_nota_keuangan">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_evaluasi_nota_keuangan"><?= $Page->nota_keuangan->caption() ?></span></td>
        <td data-name="nota_keuangan" <?= $Page->nota_keuangan->cellAttributes() ?>>
<span id="el_evaluasi_nota_keuangan" data-page="1">
<span<?= $Page->nota_keuangan->viewAttributes() ?>>
<?= GetFileViewTag($Page->nota_keuangan, $Page->nota_keuangan->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->pengantar_nota->Visible) { // pengantar_nota ?>
    <tr id="r_pengantar_nota">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_evaluasi_pengantar_nota"><?= $Page->pengantar_nota->caption() ?></span></td>
        <td data-name="pengantar_nota" <?= $Page->pengantar_nota->cellAttributes() ?>>
<span id="el_evaluasi_pengantar_nota" data-page="1">
<span<?= $Page->pengantar_nota->viewAttributes() ?>>
<?= GetFileViewTag($Page->pengantar_nota, $Page->pengantar_nota->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->risalah_sidang->Visible) { // risalah_sidang ?>
    <tr id="r_risalah_sidang">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_evaluasi_risalah_sidang"><?= $Page->risalah_sidang->caption() ?></span></td>
        <td data-name="risalah_sidang" <?= $Page->risalah_sidang->cellAttributes() ?>>
<span id="el_evaluasi_risalah_sidang" data-page="1">
<span<?= $Page->risalah_sidang->viewAttributes() ?>>
<?= GetFileViewTag($Page->risalah_sidang, $Page->risalah_sidang->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->bap_apbk->Visible) { // bap_apbk ?>
    <tr id="r_bap_apbk">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_evaluasi_bap_apbk"><?= $Page->bap_apbk->caption() ?></span></td>
        <td data-name="bap_apbk" <?= $Page->bap_apbk->cellAttributes() ?>>
<span id="el_evaluasi_bap_apbk" data-page="1">
<span<?= $Page->bap_apbk->viewAttributes() ?>>
<?= GetFileViewTag($Page->bap_apbk, $Page->bap_apbk->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->rq_apbk->Visible) { // rq_apbk ?>
    <tr id="r_rq_apbk">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_evaluasi_rq_apbk"><?= $Page->rq_apbk->caption() ?></span></td>
        <td data-name="rq_apbk" <?= $Page->rq_apbk->cellAttributes() ?>>
<span id="el_evaluasi_rq_apbk" data-page="1">
<span<?= $Page->rq_apbk->viewAttributes() ?>>
<?= GetFileViewTag($Page->rq_apbk, $Page->rq_apbk->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->rp_penjabaran->Visible) { // rp_penjabaran ?>
    <tr id="r_rp_penjabaran">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_evaluasi_rp_penjabaran"><?= $Page->rp_penjabaran->caption() ?></span></td>
        <td data-name="rp_penjabaran" <?= $Page->rp_penjabaran->cellAttributes() ?>>
<span id="el_evaluasi_rp_penjabaran" data-page="1">
<span<?= $Page->rp_penjabaran->viewAttributes() ?>>
<?= GetFileViewTag($Page->rp_penjabaran, $Page->rp_penjabaran->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->jadwal_proses->Visible) { // jadwal_proses ?>
    <tr id="r_jadwal_proses">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_evaluasi_jadwal_proses"><?= $Page->jadwal_proses->caption() ?></span></td>
        <td data-name="jadwal_proses" <?= $Page->jadwal_proses->cellAttributes() ?>>
<span id="el_evaluasi_jadwal_proses" data-page="1">
<span<?= $Page->jadwal_proses->viewAttributes() ?>>
<?= GetFileViewTag($Page->jadwal_proses, $Page->jadwal_proses->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->sinkron_kebijakan->Visible) { // sinkron_kebijakan ?>
    <tr id="r_sinkron_kebijakan">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_evaluasi_sinkron_kebijakan"><?= $Page->sinkron_kebijakan->caption() ?></span></td>
        <td data-name="sinkron_kebijakan" <?= $Page->sinkron_kebijakan->cellAttributes() ?>>
<span id="el_evaluasi_sinkron_kebijakan" data-page="1">
<span<?= $Page->sinkron_kebijakan->viewAttributes() ?>>
<?= GetFileViewTag($Page->sinkron_kebijakan, $Page->sinkron_kebijakan->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->konsistensi_program->Visible) { // konsistensi_program ?>
    <tr id="r_konsistensi_program">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_evaluasi_konsistensi_program"><?= $Page->konsistensi_program->caption() ?></span></td>
        <td data-name="konsistensi_program" <?= $Page->konsistensi_program->cellAttributes() ?>>
<span id="el_evaluasi_konsistensi_program" data-page="1">
<span<?= $Page->konsistensi_program->viewAttributes() ?>>
<?= GetFileViewTag($Page->konsistensi_program, $Page->konsistensi_program->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->alokasi_pendidikan->Visible) { // alokasi_pendidikan ?>
    <tr id="r_alokasi_pendidikan">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_evaluasi_alokasi_pendidikan"><?= $Page->alokasi_pendidikan->caption() ?></span></td>
        <td data-name="alokasi_pendidikan" <?= $Page->alokasi_pendidikan->cellAttributes() ?>>
<span id="el_evaluasi_alokasi_pendidikan" data-page="1">
<span<?= $Page->alokasi_pendidikan->viewAttributes() ?>>
<?= GetFileViewTag($Page->alokasi_pendidikan, $Page->alokasi_pendidikan->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->alokasi_kesehatan->Visible) { // alokasi_kesehatan ?>
    <tr id="r_alokasi_kesehatan">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_evaluasi_alokasi_kesehatan"><?= $Page->alokasi_kesehatan->caption() ?></span></td>
        <td data-name="alokasi_kesehatan" <?= $Page->alokasi_kesehatan->cellAttributes() ?>>
<span id="el_evaluasi_alokasi_kesehatan" data-page="1">
<span<?= $Page->alokasi_kesehatan->viewAttributes() ?>>
<?= GetFileViewTag($Page->alokasi_kesehatan, $Page->alokasi_kesehatan->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->alokasi_belanja->Visible) { // alokasi_belanja ?>
    <tr id="r_alokasi_belanja">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_evaluasi_alokasi_belanja"><?= $Page->alokasi_belanja->caption() ?></span></td>
        <td data-name="alokasi_belanja" <?= $Page->alokasi_belanja->cellAttributes() ?>>
<span id="el_evaluasi_alokasi_belanja" data-page="1">
<span<?= $Page->alokasi_belanja->viewAttributes() ?>>
<?= GetFileViewTag($Page->alokasi_belanja, $Page->alokasi_belanja->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->bak_kegiatan->Visible) { // bak_kegiatan ?>
    <tr id="r_bak_kegiatan">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_evaluasi_bak_kegiatan"><?= $Page->bak_kegiatan->caption() ?></span></td>
        <td data-name="bak_kegiatan" <?= $Page->bak_kegiatan->cellAttributes() ?>>
<span id="el_evaluasi_bak_kegiatan" data-page="1">
<span<?= $Page->bak_kegiatan->viewAttributes() ?>>
<?= GetFileViewTag($Page->bak_kegiatan, $Page->bak_kegiatan->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->softcopy_rka->Visible) { // softcopy_rka ?>
    <tr id="r_softcopy_rka">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_evaluasi_softcopy_rka"><?= $Page->softcopy_rka->caption() ?></span></td>
        <td data-name="softcopy_rka" <?= $Page->softcopy_rka->cellAttributes() ?>>
<span id="el_evaluasi_softcopy_rka" data-page="1">
<span<?= $Page->softcopy_rka->viewAttributes() ?>>
<?= GetFileViewTag($Page->softcopy_rka, $Page->softcopy_rka->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->otsus->Visible) { // otsus ?>
    <tr id="r_otsus">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_evaluasi_otsus"><?= $Page->otsus->caption() ?></span></td>
        <td data-name="otsus" <?= $Page->otsus->cellAttributes() ?>>
<span id="el_evaluasi_otsus" data-page="1">
<span<?= $Page->otsus->viewAttributes() ?>>
<?= GetFileViewTag($Page->otsus, $Page->otsus->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->qanun_perbup->Visible) { // qanun_perbup ?>
    <tr id="r_qanun_perbup">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_evaluasi_qanun_perbup"><?= $Page->qanun_perbup->caption() ?></span></td>
        <td data-name="qanun_perbup" <?= $Page->qanun_perbup->cellAttributes() ?>>
<span id="el_evaluasi_qanun_perbup" data-page="1">
<span<?= $Page->qanun_perbup->viewAttributes() ?>>
<?= GetFileViewTag($Page->qanun_perbup, $Page->qanun_perbup->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->tindak_apbkp->Visible) { // tindak_apbkp ?>
    <tr id="r_tindak_apbkp">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_evaluasi_tindak_apbkp"><?= $Page->tindak_apbkp->caption() ?></span></td>
        <td data-name="tindak_apbkp" <?= $Page->tindak_apbkp->cellAttributes() ?>>
<span id="el_evaluasi_tindak_apbkp" data-page="1">
<span<?= $Page->tindak_apbkp->viewAttributes() ?>>
<?= GetFileViewTag($Page->tindak_apbkp, $Page->tindak_apbkp->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->status->Visible) { // status ?>
    <tr id="r_status">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_evaluasi_status"><?= $Page->status->caption() ?></span></td>
        <td data-name="status" <?= $Page->status->cellAttributes() ?>>
<span id="el_evaluasi_status" data-page="1">
<span<?= $Page->status->viewAttributes() ?>>
<?= $Page->status->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->idd_user->Visible) { // idd_user ?>
    <tr id="r_idd_user">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_evaluasi_idd_user"><?= $Page->idd_user->caption() ?></span></td>
        <td data-name="idd_user" <?= $Page->idd_user->cellAttributes() ?>>
<span id="el_evaluasi_idd_user" data-page="1">
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
