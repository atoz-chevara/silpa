<?php

namespace PHPMaker2021\silpa;

// Page object
$EvaluasiDelete = &$Page;
?>
<script>
var currentForm, currentPageID;
var fevaluasidelete;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "delete";
    fevaluasidelete = currentForm = new ew.Form("fevaluasidelete", "delete");
    loadjs.done("fevaluasidelete");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<script>
if (!ew.vars.tables.evaluasi) ew.vars.tables.evaluasi = <?= JsonEncode(GetClientVar("tables", "evaluasi")) ?>;
</script>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<form name="fevaluasidelete" id="fevaluasidelete" class="form-inline ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="evaluasi">
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
        <th class="<?= $Page->idd_evaluasi->headerCellClass() ?>"><span id="elh_evaluasi_idd_evaluasi" class="evaluasi_idd_evaluasi"><?= $Page->idd_evaluasi->caption() ?></span></th>
<?php } ?>
<?php if ($Page->tanggal->Visible) { // tanggal ?>
        <th class="<?= $Page->tanggal->headerCellClass() ?>"><span id="elh_evaluasi_tanggal" class="evaluasi_tanggal"><?= $Page->tanggal->caption() ?></span></th>
<?php } ?>
<?php if ($Page->idd_wilayah->Visible) { // idd_wilayah ?>
        <th class="<?= $Page->idd_wilayah->headerCellClass() ?>"><span id="elh_evaluasi_idd_wilayah" class="evaluasi_idd_wilayah"><?= $Page->idd_wilayah->caption() ?></span></th>
<?php } ?>
<?php if ($Page->kd_satker->Visible) { // kd_satker ?>
        <th class="<?= $Page->kd_satker->headerCellClass() ?>"><span id="elh_evaluasi_kd_satker" class="evaluasi_kd_satker"><?= $Page->kd_satker->caption() ?></span></th>
<?php } ?>
<?php if ($Page->idd_tahapan->Visible) { // idd_tahapan ?>
        <th class="<?= $Page->idd_tahapan->headerCellClass() ?>"><span id="elh_evaluasi_idd_tahapan" class="evaluasi_idd_tahapan"><?= $Page->idd_tahapan->caption() ?></span></th>
<?php } ?>
<?php if ($Page->tahun_anggaran->Visible) { // tahun_anggaran ?>
        <th class="<?= $Page->tahun_anggaran->headerCellClass() ?>"><span id="elh_evaluasi_tahun_anggaran" class="evaluasi_tahun_anggaran"><?= $Page->tahun_anggaran->caption() ?></span></th>
<?php } ?>
<?php if ($Page->surat_pengantar->Visible) { // surat_pengantar ?>
        <th class="<?= $Page->surat_pengantar->headerCellClass() ?>"><span id="elh_evaluasi_surat_pengantar" class="evaluasi_surat_pengantar"><?= $Page->surat_pengantar->caption() ?></span></th>
<?php } ?>
<?php if ($Page->rpjmd->Visible) { // rpjmd ?>
        <th class="<?= $Page->rpjmd->headerCellClass() ?>"><span id="elh_evaluasi_rpjmd" class="evaluasi_rpjmd"><?= $Page->rpjmd->caption() ?></span></th>
<?php } ?>
<?php if ($Page->rkpk->Visible) { // rkpk ?>
        <th class="<?= $Page->rkpk->headerCellClass() ?>"><span id="elh_evaluasi_rkpk" class="evaluasi_rkpk"><?= $Page->rkpk->caption() ?></span></th>
<?php } ?>
<?php if ($Page->skd_rkuappas->Visible) { // skd_rkuappas ?>
        <th class="<?= $Page->skd_rkuappas->headerCellClass() ?>"><span id="elh_evaluasi_skd_rkuappas" class="evaluasi_skd_rkuappas"><?= $Page->skd_rkuappas->caption() ?></span></th>
<?php } ?>
<?php if ($Page->kua->Visible) { // kua ?>
        <th class="<?= $Page->kua->headerCellClass() ?>"><span id="elh_evaluasi_kua" class="evaluasi_kua"><?= $Page->kua->caption() ?></span></th>
<?php } ?>
<?php if ($Page->ppas->Visible) { // ppas ?>
        <th class="<?= $Page->ppas->headerCellClass() ?>"><span id="elh_evaluasi_ppas" class="evaluasi_ppas"><?= $Page->ppas->caption() ?></span></th>
<?php } ?>
<?php if ($Page->skd_rqanun->Visible) { // skd_rqanun ?>
        <th class="<?= $Page->skd_rqanun->headerCellClass() ?>"><span id="elh_evaluasi_skd_rqanun" class="evaluasi_skd_rqanun"><?= $Page->skd_rqanun->caption() ?></span></th>
<?php } ?>
<?php if ($Page->nota_keuangan->Visible) { // nota_keuangan ?>
        <th class="<?= $Page->nota_keuangan->headerCellClass() ?>"><span id="elh_evaluasi_nota_keuangan" class="evaluasi_nota_keuangan"><?= $Page->nota_keuangan->caption() ?></span></th>
<?php } ?>
<?php if ($Page->pengantar_nota->Visible) { // pengantar_nota ?>
        <th class="<?= $Page->pengantar_nota->headerCellClass() ?>"><span id="elh_evaluasi_pengantar_nota" class="evaluasi_pengantar_nota"><?= $Page->pengantar_nota->caption() ?></span></th>
<?php } ?>
<?php if ($Page->risalah_sidang->Visible) { // risalah_sidang ?>
        <th class="<?= $Page->risalah_sidang->headerCellClass() ?>"><span id="elh_evaluasi_risalah_sidang" class="evaluasi_risalah_sidang"><?= $Page->risalah_sidang->caption() ?></span></th>
<?php } ?>
<?php if ($Page->bap_apbk->Visible) { // bap_apbk ?>
        <th class="<?= $Page->bap_apbk->headerCellClass() ?>"><span id="elh_evaluasi_bap_apbk" class="evaluasi_bap_apbk"><?= $Page->bap_apbk->caption() ?></span></th>
<?php } ?>
<?php if ($Page->rq_apbk->Visible) { // rq_apbk ?>
        <th class="<?= $Page->rq_apbk->headerCellClass() ?>"><span id="elh_evaluasi_rq_apbk" class="evaluasi_rq_apbk"><?= $Page->rq_apbk->caption() ?></span></th>
<?php } ?>
<?php if ($Page->rp_penjabaran->Visible) { // rp_penjabaran ?>
        <th class="<?= $Page->rp_penjabaran->headerCellClass() ?>"><span id="elh_evaluasi_rp_penjabaran" class="evaluasi_rp_penjabaran"><?= $Page->rp_penjabaran->caption() ?></span></th>
<?php } ?>
<?php if ($Page->jadwal_proses->Visible) { // jadwal_proses ?>
        <th class="<?= $Page->jadwal_proses->headerCellClass() ?>"><span id="elh_evaluasi_jadwal_proses" class="evaluasi_jadwal_proses"><?= $Page->jadwal_proses->caption() ?></span></th>
<?php } ?>
<?php if ($Page->sinkron_kebijakan->Visible) { // sinkron_kebijakan ?>
        <th class="<?= $Page->sinkron_kebijakan->headerCellClass() ?>"><span id="elh_evaluasi_sinkron_kebijakan" class="evaluasi_sinkron_kebijakan"><?= $Page->sinkron_kebijakan->caption() ?></span></th>
<?php } ?>
<?php if ($Page->konsistensi_program->Visible) { // konsistensi_program ?>
        <th class="<?= $Page->konsistensi_program->headerCellClass() ?>"><span id="elh_evaluasi_konsistensi_program" class="evaluasi_konsistensi_program"><?= $Page->konsistensi_program->caption() ?></span></th>
<?php } ?>
<?php if ($Page->alokasi_pendidikan->Visible) { // alokasi_pendidikan ?>
        <th class="<?= $Page->alokasi_pendidikan->headerCellClass() ?>"><span id="elh_evaluasi_alokasi_pendidikan" class="evaluasi_alokasi_pendidikan"><?= $Page->alokasi_pendidikan->caption() ?></span></th>
<?php } ?>
<?php if ($Page->alokasi_kesehatan->Visible) { // alokasi_kesehatan ?>
        <th class="<?= $Page->alokasi_kesehatan->headerCellClass() ?>"><span id="elh_evaluasi_alokasi_kesehatan" class="evaluasi_alokasi_kesehatan"><?= $Page->alokasi_kesehatan->caption() ?></span></th>
<?php } ?>
<?php if ($Page->alokasi_belanja->Visible) { // alokasi_belanja ?>
        <th class="<?= $Page->alokasi_belanja->headerCellClass() ?>"><span id="elh_evaluasi_alokasi_belanja" class="evaluasi_alokasi_belanja"><?= $Page->alokasi_belanja->caption() ?></span></th>
<?php } ?>
<?php if ($Page->bak_kegiatan->Visible) { // bak_kegiatan ?>
        <th class="<?= $Page->bak_kegiatan->headerCellClass() ?>"><span id="elh_evaluasi_bak_kegiatan" class="evaluasi_bak_kegiatan"><?= $Page->bak_kegiatan->caption() ?></span></th>
<?php } ?>
<?php if ($Page->softcopy_rka->Visible) { // softcopy_rka ?>
        <th class="<?= $Page->softcopy_rka->headerCellClass() ?>"><span id="elh_evaluasi_softcopy_rka" class="evaluasi_softcopy_rka"><?= $Page->softcopy_rka->caption() ?></span></th>
<?php } ?>
<?php if ($Page->otsus->Visible) { // otsus ?>
        <th class="<?= $Page->otsus->headerCellClass() ?>"><span id="elh_evaluasi_otsus" class="evaluasi_otsus"><?= $Page->otsus->caption() ?></span></th>
<?php } ?>
<?php if ($Page->qanun_perbup->Visible) { // qanun_perbup ?>
        <th class="<?= $Page->qanun_perbup->headerCellClass() ?>"><span id="elh_evaluasi_qanun_perbup" class="evaluasi_qanun_perbup"><?= $Page->qanun_perbup->caption() ?></span></th>
<?php } ?>
<?php if ($Page->tindak_apbkp->Visible) { // tindak_apbkp ?>
        <th class="<?= $Page->tindak_apbkp->headerCellClass() ?>"><span id="elh_evaluasi_tindak_apbkp" class="evaluasi_tindak_apbkp"><?= $Page->tindak_apbkp->caption() ?></span></th>
<?php } ?>
<?php if ($Page->status->Visible) { // status ?>
        <th class="<?= $Page->status->headerCellClass() ?>"><span id="elh_evaluasi_status" class="evaluasi_status"><?= $Page->status->caption() ?></span></th>
<?php } ?>
<?php if ($Page->idd_user->Visible) { // idd_user ?>
        <th class="<?= $Page->idd_user->headerCellClass() ?>"><span id="elh_evaluasi_idd_user" class="evaluasi_idd_user"><?= $Page->idd_user->caption() ?></span></th>
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
<span id="el<?= $Page->RowCount ?>_evaluasi_idd_evaluasi" class="evaluasi_idd_evaluasi">
<span<?= $Page->idd_evaluasi->viewAttributes() ?>>
<?= $Page->idd_evaluasi->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->tanggal->Visible) { // tanggal ?>
        <td <?= $Page->tanggal->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_evaluasi_tanggal" class="evaluasi_tanggal">
<span<?= $Page->tanggal->viewAttributes() ?>>
<?= $Page->tanggal->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->idd_wilayah->Visible) { // idd_wilayah ?>
        <td <?= $Page->idd_wilayah->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_evaluasi_idd_wilayah" class="evaluasi_idd_wilayah">
<span<?= $Page->idd_wilayah->viewAttributes() ?>>
<?= $Page->idd_wilayah->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->kd_satker->Visible) { // kd_satker ?>
        <td <?= $Page->kd_satker->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_evaluasi_kd_satker" class="evaluasi_kd_satker">
<span<?= $Page->kd_satker->viewAttributes() ?>>
<?= $Page->kd_satker->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->idd_tahapan->Visible) { // idd_tahapan ?>
        <td <?= $Page->idd_tahapan->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_evaluasi_idd_tahapan" class="evaluasi_idd_tahapan">
<span<?= $Page->idd_tahapan->viewAttributes() ?>>
<?= $Page->idd_tahapan->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->tahun_anggaran->Visible) { // tahun_anggaran ?>
        <td <?= $Page->tahun_anggaran->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_evaluasi_tahun_anggaran" class="evaluasi_tahun_anggaran">
<span<?= $Page->tahun_anggaran->viewAttributes() ?>>
<?= $Page->tahun_anggaran->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->surat_pengantar->Visible) { // surat_pengantar ?>
        <td <?= $Page->surat_pengantar->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_evaluasi_surat_pengantar" class="evaluasi_surat_pengantar">
<span<?= $Page->surat_pengantar->viewAttributes() ?>>
<?= GetFileViewTag($Page->surat_pengantar, $Page->surat_pengantar->getViewValue(), false) ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($Page->rpjmd->Visible) { // rpjmd ?>
        <td <?= $Page->rpjmd->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_evaluasi_rpjmd" class="evaluasi_rpjmd">
<span<?= $Page->rpjmd->viewAttributes() ?>>
<?= GetFileViewTag($Page->rpjmd, $Page->rpjmd->getViewValue(), false) ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($Page->rkpk->Visible) { // rkpk ?>
        <td <?= $Page->rkpk->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_evaluasi_rkpk" class="evaluasi_rkpk">
<span<?= $Page->rkpk->viewAttributes() ?>>
<?= GetFileViewTag($Page->rkpk, $Page->rkpk->getViewValue(), false) ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($Page->skd_rkuappas->Visible) { // skd_rkuappas ?>
        <td <?= $Page->skd_rkuappas->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_evaluasi_skd_rkuappas" class="evaluasi_skd_rkuappas">
<span<?= $Page->skd_rkuappas->viewAttributes() ?>>
<?= GetFileViewTag($Page->skd_rkuappas, $Page->skd_rkuappas->getViewValue(), false) ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($Page->kua->Visible) { // kua ?>
        <td <?= $Page->kua->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_evaluasi_kua" class="evaluasi_kua">
<span<?= $Page->kua->viewAttributes() ?>>
<?= GetFileViewTag($Page->kua, $Page->kua->getViewValue(), false) ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($Page->ppas->Visible) { // ppas ?>
        <td <?= $Page->ppas->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_evaluasi_ppas" class="evaluasi_ppas">
<span<?= $Page->ppas->viewAttributes() ?>>
<?= GetFileViewTag($Page->ppas, $Page->ppas->getViewValue(), false) ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($Page->skd_rqanun->Visible) { // skd_rqanun ?>
        <td <?= $Page->skd_rqanun->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_evaluasi_skd_rqanun" class="evaluasi_skd_rqanun">
<span<?= $Page->skd_rqanun->viewAttributes() ?>>
<?= GetFileViewTag($Page->skd_rqanun, $Page->skd_rqanun->getViewValue(), false) ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($Page->nota_keuangan->Visible) { // nota_keuangan ?>
        <td <?= $Page->nota_keuangan->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_evaluasi_nota_keuangan" class="evaluasi_nota_keuangan">
<span<?= $Page->nota_keuangan->viewAttributes() ?>>
<?= GetFileViewTag($Page->nota_keuangan, $Page->nota_keuangan->getViewValue(), false) ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($Page->pengantar_nota->Visible) { // pengantar_nota ?>
        <td <?= $Page->pengantar_nota->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_evaluasi_pengantar_nota" class="evaluasi_pengantar_nota">
<span<?= $Page->pengantar_nota->viewAttributes() ?>>
<?= GetFileViewTag($Page->pengantar_nota, $Page->pengantar_nota->getViewValue(), false) ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($Page->risalah_sidang->Visible) { // risalah_sidang ?>
        <td <?= $Page->risalah_sidang->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_evaluasi_risalah_sidang" class="evaluasi_risalah_sidang">
<span<?= $Page->risalah_sidang->viewAttributes() ?>>
<?= GetFileViewTag($Page->risalah_sidang, $Page->risalah_sidang->getViewValue(), false) ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($Page->bap_apbk->Visible) { // bap_apbk ?>
        <td <?= $Page->bap_apbk->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_evaluasi_bap_apbk" class="evaluasi_bap_apbk">
<span<?= $Page->bap_apbk->viewAttributes() ?>>
<?= GetFileViewTag($Page->bap_apbk, $Page->bap_apbk->getViewValue(), false) ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($Page->rq_apbk->Visible) { // rq_apbk ?>
        <td <?= $Page->rq_apbk->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_evaluasi_rq_apbk" class="evaluasi_rq_apbk">
<span<?= $Page->rq_apbk->viewAttributes() ?>>
<?= GetFileViewTag($Page->rq_apbk, $Page->rq_apbk->getViewValue(), false) ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($Page->rp_penjabaran->Visible) { // rp_penjabaran ?>
        <td <?= $Page->rp_penjabaran->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_evaluasi_rp_penjabaran" class="evaluasi_rp_penjabaran">
<span<?= $Page->rp_penjabaran->viewAttributes() ?>>
<?= GetFileViewTag($Page->rp_penjabaran, $Page->rp_penjabaran->getViewValue(), false) ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($Page->jadwal_proses->Visible) { // jadwal_proses ?>
        <td <?= $Page->jadwal_proses->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_evaluasi_jadwal_proses" class="evaluasi_jadwal_proses">
<span<?= $Page->jadwal_proses->viewAttributes() ?>>
<?= GetFileViewTag($Page->jadwal_proses, $Page->jadwal_proses->getViewValue(), false) ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($Page->sinkron_kebijakan->Visible) { // sinkron_kebijakan ?>
        <td <?= $Page->sinkron_kebijakan->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_evaluasi_sinkron_kebijakan" class="evaluasi_sinkron_kebijakan">
<span<?= $Page->sinkron_kebijakan->viewAttributes() ?>>
<?= GetFileViewTag($Page->sinkron_kebijakan, $Page->sinkron_kebijakan->getViewValue(), false) ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($Page->konsistensi_program->Visible) { // konsistensi_program ?>
        <td <?= $Page->konsistensi_program->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_evaluasi_konsistensi_program" class="evaluasi_konsistensi_program">
<span<?= $Page->konsistensi_program->viewAttributes() ?>>
<?= GetFileViewTag($Page->konsistensi_program, $Page->konsistensi_program->getViewValue(), false) ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($Page->alokasi_pendidikan->Visible) { // alokasi_pendidikan ?>
        <td <?= $Page->alokasi_pendidikan->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_evaluasi_alokasi_pendidikan" class="evaluasi_alokasi_pendidikan">
<span<?= $Page->alokasi_pendidikan->viewAttributes() ?>>
<?= GetFileViewTag($Page->alokasi_pendidikan, $Page->alokasi_pendidikan->getViewValue(), false) ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($Page->alokasi_kesehatan->Visible) { // alokasi_kesehatan ?>
        <td <?= $Page->alokasi_kesehatan->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_evaluasi_alokasi_kesehatan" class="evaluasi_alokasi_kesehatan">
<span<?= $Page->alokasi_kesehatan->viewAttributes() ?>>
<?= GetFileViewTag($Page->alokasi_kesehatan, $Page->alokasi_kesehatan->getViewValue(), false) ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($Page->alokasi_belanja->Visible) { // alokasi_belanja ?>
        <td <?= $Page->alokasi_belanja->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_evaluasi_alokasi_belanja" class="evaluasi_alokasi_belanja">
<span<?= $Page->alokasi_belanja->viewAttributes() ?>>
<?= GetFileViewTag($Page->alokasi_belanja, $Page->alokasi_belanja->getViewValue(), false) ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($Page->bak_kegiatan->Visible) { // bak_kegiatan ?>
        <td <?= $Page->bak_kegiatan->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_evaluasi_bak_kegiatan" class="evaluasi_bak_kegiatan">
<span<?= $Page->bak_kegiatan->viewAttributes() ?>>
<?= GetFileViewTag($Page->bak_kegiatan, $Page->bak_kegiatan->getViewValue(), false) ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($Page->softcopy_rka->Visible) { // softcopy_rka ?>
        <td <?= $Page->softcopy_rka->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_evaluasi_softcopy_rka" class="evaluasi_softcopy_rka">
<span<?= $Page->softcopy_rka->viewAttributes() ?>>
<?= GetFileViewTag($Page->softcopy_rka, $Page->softcopy_rka->getViewValue(), false) ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($Page->otsus->Visible) { // otsus ?>
        <td <?= $Page->otsus->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_evaluasi_otsus" class="evaluasi_otsus">
<span<?= $Page->otsus->viewAttributes() ?>>
<?= GetFileViewTag($Page->otsus, $Page->otsus->getViewValue(), false) ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($Page->qanun_perbup->Visible) { // qanun_perbup ?>
        <td <?= $Page->qanun_perbup->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_evaluasi_qanun_perbup" class="evaluasi_qanun_perbup">
<span<?= $Page->qanun_perbup->viewAttributes() ?>>
<?= GetFileViewTag($Page->qanun_perbup, $Page->qanun_perbup->getViewValue(), false) ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($Page->tindak_apbkp->Visible) { // tindak_apbkp ?>
        <td <?= $Page->tindak_apbkp->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_evaluasi_tindak_apbkp" class="evaluasi_tindak_apbkp">
<span<?= $Page->tindak_apbkp->viewAttributes() ?>>
<?= GetFileViewTag($Page->tindak_apbkp, $Page->tindak_apbkp->getViewValue(), false) ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($Page->status->Visible) { // status ?>
        <td <?= $Page->status->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_evaluasi_status" class="evaluasi_status">
<span<?= $Page->status->viewAttributes() ?>>
<?= $Page->status->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->idd_user->Visible) { // idd_user ?>
        <td <?= $Page->idd_user->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_evaluasi_idd_user" class="evaluasi_idd_user">
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
