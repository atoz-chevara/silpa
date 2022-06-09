<?php

namespace PHPMaker2021\silpa;

// Page object
$EvaluasiEdit = &$Page;
?>
<script>
var currentForm, currentPageID;
var fevaluasiedit;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "edit";
    fevaluasiedit = currentForm = new ew.Form("fevaluasiedit", "edit");

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "evaluasi")) ?>,
        fields = currentTable.fields;
    if (!ew.vars.tables.evaluasi)
        ew.vars.tables.evaluasi = currentTable;
    fevaluasiedit.addFields([
        ["idd_evaluasi", [fields.idd_evaluasi.visible && fields.idd_evaluasi.required ? ew.Validators.required(fields.idd_evaluasi.caption) : null], fields.idd_evaluasi.isInvalid],
        ["tanggal", [fields.tanggal.visible && fields.tanggal.required ? ew.Validators.required(fields.tanggal.caption) : null, ew.Validators.datetime(0)], fields.tanggal.isInvalid],
        ["idd_wilayah", [fields.idd_wilayah.visible && fields.idd_wilayah.required ? ew.Validators.required(fields.idd_wilayah.caption) : null], fields.idd_wilayah.isInvalid],
        ["kd_satker", [fields.kd_satker.visible && fields.kd_satker.required ? ew.Validators.required(fields.kd_satker.caption) : null], fields.kd_satker.isInvalid],
        ["idd_tahapan", [fields.idd_tahapan.visible && fields.idd_tahapan.required ? ew.Validators.required(fields.idd_tahapan.caption) : null], fields.idd_tahapan.isInvalid],
        ["tahun_anggaran", [fields.tahun_anggaran.visible && fields.tahun_anggaran.required ? ew.Validators.required(fields.tahun_anggaran.caption) : null], fields.tahun_anggaran.isInvalid],
        ["surat_pengantar", [fields.surat_pengantar.visible && fields.surat_pengantar.required ? ew.Validators.fileRequired(fields.surat_pengantar.caption) : null], fields.surat_pengantar.isInvalid],
        ["rpjmd", [fields.rpjmd.visible && fields.rpjmd.required ? ew.Validators.fileRequired(fields.rpjmd.caption) : null], fields.rpjmd.isInvalid],
        ["rkpk", [fields.rkpk.visible && fields.rkpk.required ? ew.Validators.fileRequired(fields.rkpk.caption) : null], fields.rkpk.isInvalid],
        ["skd_rkuappas", [fields.skd_rkuappas.visible && fields.skd_rkuappas.required ? ew.Validators.fileRequired(fields.skd_rkuappas.caption) : null], fields.skd_rkuappas.isInvalid],
        ["kua", [fields.kua.visible && fields.kua.required ? ew.Validators.fileRequired(fields.kua.caption) : null], fields.kua.isInvalid],
        ["ppas", [fields.ppas.visible && fields.ppas.required ? ew.Validators.fileRequired(fields.ppas.caption) : null], fields.ppas.isInvalid],
        ["skd_rqanun", [fields.skd_rqanun.visible && fields.skd_rqanun.required ? ew.Validators.fileRequired(fields.skd_rqanun.caption) : null], fields.skd_rqanun.isInvalid],
        ["nota_keuangan", [fields.nota_keuangan.visible && fields.nota_keuangan.required ? ew.Validators.fileRequired(fields.nota_keuangan.caption) : null], fields.nota_keuangan.isInvalid],
        ["pengantar_nota", [fields.pengantar_nota.visible && fields.pengantar_nota.required ? ew.Validators.fileRequired(fields.pengantar_nota.caption) : null], fields.pengantar_nota.isInvalid],
        ["risalah_sidang", [fields.risalah_sidang.visible && fields.risalah_sidang.required ? ew.Validators.fileRequired(fields.risalah_sidang.caption) : null], fields.risalah_sidang.isInvalid],
        ["bap_apbk", [fields.bap_apbk.visible && fields.bap_apbk.required ? ew.Validators.fileRequired(fields.bap_apbk.caption) : null], fields.bap_apbk.isInvalid],
        ["rq_apbk", [fields.rq_apbk.visible && fields.rq_apbk.required ? ew.Validators.fileRequired(fields.rq_apbk.caption) : null], fields.rq_apbk.isInvalid],
        ["rp_penjabaran", [fields.rp_penjabaran.visible && fields.rp_penjabaran.required ? ew.Validators.fileRequired(fields.rp_penjabaran.caption) : null], fields.rp_penjabaran.isInvalid],
        ["jadwal_proses", [fields.jadwal_proses.visible && fields.jadwal_proses.required ? ew.Validators.fileRequired(fields.jadwal_proses.caption) : null], fields.jadwal_proses.isInvalid],
        ["sinkron_kebijakan", [fields.sinkron_kebijakan.visible && fields.sinkron_kebijakan.required ? ew.Validators.fileRequired(fields.sinkron_kebijakan.caption) : null], fields.sinkron_kebijakan.isInvalid],
        ["konsistensi_program", [fields.konsistensi_program.visible && fields.konsistensi_program.required ? ew.Validators.fileRequired(fields.konsistensi_program.caption) : null], fields.konsistensi_program.isInvalid],
        ["alokasi_pendidikan", [fields.alokasi_pendidikan.visible && fields.alokasi_pendidikan.required ? ew.Validators.fileRequired(fields.alokasi_pendidikan.caption) : null], fields.alokasi_pendidikan.isInvalid],
        ["alokasi_kesehatan", [fields.alokasi_kesehatan.visible && fields.alokasi_kesehatan.required ? ew.Validators.fileRequired(fields.alokasi_kesehatan.caption) : null], fields.alokasi_kesehatan.isInvalid],
        ["alokasi_belanja", [fields.alokasi_belanja.visible && fields.alokasi_belanja.required ? ew.Validators.fileRequired(fields.alokasi_belanja.caption) : null], fields.alokasi_belanja.isInvalid],
        ["bak_kegiatan", [fields.bak_kegiatan.visible && fields.bak_kegiatan.required ? ew.Validators.fileRequired(fields.bak_kegiatan.caption) : null], fields.bak_kegiatan.isInvalid],
        ["softcopy_rka", [fields.softcopy_rka.visible && fields.softcopy_rka.required ? ew.Validators.fileRequired(fields.softcopy_rka.caption) : null], fields.softcopy_rka.isInvalid],
        ["otsus", [fields.otsus.visible && fields.otsus.required ? ew.Validators.fileRequired(fields.otsus.caption) : null], fields.otsus.isInvalid],
        ["qanun_perbup", [fields.qanun_perbup.visible && fields.qanun_perbup.required ? ew.Validators.fileRequired(fields.qanun_perbup.caption) : null], fields.qanun_perbup.isInvalid],
        ["tindak_apbkp", [fields.tindak_apbkp.visible && fields.tindak_apbkp.required ? ew.Validators.fileRequired(fields.tindak_apbkp.caption) : null], fields.tindak_apbkp.isInvalid],
        ["status", [fields.status.visible && fields.status.required ? ew.Validators.required(fields.status.caption) : null], fields.status.isInvalid],
        ["idd_user", [fields.idd_user.visible && fields.idd_user.required ? ew.Validators.required(fields.idd_user.caption) : null], fields.idd_user.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fevaluasiedit,
            fobj = f.getForm(),
            $fobj = $(fobj),
            $k = $fobj.find("#" + f.formKeyCountName), // Get key_count
            rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1,
            startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
        for (var i = startcnt; i <= rowcnt; i++) {
            var rowIndex = ($k[0]) ? String(i) : "";
            f.setInvalid(rowIndex);
        }
    });

    // Validate form
    fevaluasiedit.validate = function () {
        if (!this.validateRequired)
            return true; // Ignore validation
        var fobj = this.getForm(),
            $fobj = $(fobj);
        if ($fobj.find("#confirm").val() == "confirm")
            return true;
        var addcnt = 0,
            $k = $fobj.find("#" + this.formKeyCountName), // Get key_count
            rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1,
            startcnt = (rowcnt == 0) ? 0 : 1, // Check rowcnt == 0 => Inline-Add
            gridinsert = ["insert", "gridinsert"].includes($fobj.find("#action").val()) && $k[0];
        for (var i = startcnt; i <= rowcnt; i++) {
            var rowIndex = ($k[0]) ? String(i) : "";
            $fobj.data("rowindex", rowIndex);

            // Validate fields
            if (!this.validateFields(rowIndex))
                return false;

            // Call Form_CustomValidate event
            if (!this.customValidate(fobj)) {
                this.focus();
                return false;
            }
        }

        // Process detail forms
        var dfs = $fobj.find("input[name='detailpage']").get();
        for (var i = 0; i < dfs.length; i++) {
            var df = dfs[i],
                val = df.value,
                frm = ew.forms.get(val);
            if (val && frm && !frm.validate())
                return false;
        }
        return true;
    }

    // Form_CustomValidate
    fevaluasiedit.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fevaluasiedit.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fevaluasiedit.lists.idd_wilayah = <?= $Page->idd_wilayah->toClientList($Page) ?>;
    fevaluasiedit.lists.kd_satker = <?= $Page->kd_satker->toClientList($Page) ?>;
    fevaluasiedit.lists.idd_tahapan = <?= $Page->idd_tahapan->toClientList($Page) ?>;
    fevaluasiedit.lists.tahun_anggaran = <?= $Page->tahun_anggaran->toClientList($Page) ?>;
    fevaluasiedit.lists.status = <?= $Page->status->toClientList($Page) ?>;
    fevaluasiedit.lists.idd_user = <?= $Page->idd_user->toClientList($Page) ?>;
    loadjs.done("fevaluasiedit");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<form name="fevaluasiedit" id="fevaluasiedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="evaluasi">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->idd_evaluasi->Visible) { // idd_evaluasi ?>
    <div id="r_idd_evaluasi" class="form-group row">
        <label id="elh_evaluasi_idd_evaluasi" class="<?= $Page->LeftColumnClass ?>"><?= $Page->idd_evaluasi->caption() ?><?= $Page->idd_evaluasi->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->idd_evaluasi->cellAttributes() ?>>
<span id="el_evaluasi_idd_evaluasi">
<span<?= $Page->idd_evaluasi->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->idd_evaluasi->getDisplayValue($Page->idd_evaluasi->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="evaluasi" data-field="x_idd_evaluasi" data-hidden="1" data-page="1" name="x_idd_evaluasi" id="x_idd_evaluasi" value="<?= HtmlEncode($Page->idd_evaluasi->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->tanggal->Visible) { // tanggal ?>
    <div id="r_tanggal" class="form-group row">
        <label id="elh_evaluasi_tanggal" for="x_tanggal" class="<?= $Page->LeftColumnClass ?>"><?= $Page->tanggal->caption() ?><?= $Page->tanggal->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->tanggal->cellAttributes() ?>>
<span id="el_evaluasi_tanggal">
<input type="<?= $Page->tanggal->getInputTextType() ?>" data-table="evaluasi" data-field="x_tanggal" data-page="1" name="x_tanggal" id="x_tanggal" placeholder="<?= HtmlEncode($Page->tanggal->getPlaceHolder()) ?>" value="<?= $Page->tanggal->EditValue ?>"<?= $Page->tanggal->editAttributes() ?> aria-describedby="x_tanggal_help">
<?= $Page->tanggal->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->tanggal->getErrorMessage() ?></div>
<?php if (!$Page->tanggal->ReadOnly && !$Page->tanggal->Disabled && !isset($Page->tanggal->EditAttrs["readonly"]) && !isset($Page->tanggal->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fevaluasiedit", "datetimepicker"], function() {
    ew.createDateTimePicker("fevaluasiedit", "x_tanggal", {"ignoreReadonly":true,"useCurrent":false,"format":0});
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->idd_wilayah->Visible) { // idd_wilayah ?>
    <div id="r_idd_wilayah" class="form-group row">
        <label id="elh_evaluasi_idd_wilayah" for="x_idd_wilayah" class="<?= $Page->LeftColumnClass ?>"><?= $Page->idd_wilayah->caption() ?><?= $Page->idd_wilayah->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->idd_wilayah->cellAttributes() ?>>
<span id="el_evaluasi_idd_wilayah">
    <select
        id="x_idd_wilayah"
        name="x_idd_wilayah"
        class="form-control ew-select<?= $Page->idd_wilayah->isInvalidClass() ?>"
        data-select2-id="evaluasi_x_idd_wilayah"
        data-table="evaluasi"
        data-field="x_idd_wilayah"
        data-page="1"
        data-value-separator="<?= $Page->idd_wilayah->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->idd_wilayah->getPlaceHolder()) ?>"
        <?= $Page->idd_wilayah->editAttributes() ?>>
        <?= $Page->idd_wilayah->selectOptionListHtml("x_idd_wilayah") ?>
    </select>
    <?= $Page->idd_wilayah->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->idd_wilayah->getErrorMessage() ?></div>
<?= $Page->idd_wilayah->Lookup->getParamTag($Page, "p_x_idd_wilayah") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='evaluasi_x_idd_wilayah']"),
        options = { name: "x_idd_wilayah", selectId: "evaluasi_x_idd_wilayah", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.evaluasi.fields.idd_wilayah.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->kd_satker->Visible) { // kd_satker ?>
    <div id="r_kd_satker" class="form-group row">
        <label id="elh_evaluasi_kd_satker" for="x_kd_satker" class="<?= $Page->LeftColumnClass ?>"><?= $Page->kd_satker->caption() ?><?= $Page->kd_satker->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->kd_satker->cellAttributes() ?>>
<span id="el_evaluasi_kd_satker">
    <select
        id="x_kd_satker"
        name="x_kd_satker"
        class="form-control ew-select<?= $Page->kd_satker->isInvalidClass() ?>"
        data-select2-id="evaluasi_x_kd_satker"
        data-table="evaluasi"
        data-field="x_kd_satker"
        data-page="1"
        data-value-separator="<?= $Page->kd_satker->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->kd_satker->getPlaceHolder()) ?>"
        <?= $Page->kd_satker->editAttributes() ?>>
        <?= $Page->kd_satker->selectOptionListHtml("x_kd_satker") ?>
    </select>
    <?= $Page->kd_satker->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->kd_satker->getErrorMessage() ?></div>
<?= $Page->kd_satker->Lookup->getParamTag($Page, "p_x_kd_satker") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='evaluasi_x_kd_satker']"),
        options = { name: "x_kd_satker", selectId: "evaluasi_x_kd_satker", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.evaluasi.fields.kd_satker.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->idd_tahapan->Visible) { // idd_tahapan ?>
    <div id="r_idd_tahapan" class="form-group row">
        <label id="elh_evaluasi_idd_tahapan" for="x_idd_tahapan" class="<?= $Page->LeftColumnClass ?>"><?= $Page->idd_tahapan->caption() ?><?= $Page->idd_tahapan->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->idd_tahapan->cellAttributes() ?>>
<span id="el_evaluasi_idd_tahapan">
    <select
        id="x_idd_tahapan"
        name="x_idd_tahapan"
        class="form-control ew-select<?= $Page->idd_tahapan->isInvalidClass() ?>"
        data-select2-id="evaluasi_x_idd_tahapan"
        data-table="evaluasi"
        data-field="x_idd_tahapan"
        data-page="1"
        data-value-separator="<?= $Page->idd_tahapan->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->idd_tahapan->getPlaceHolder()) ?>"
        <?= $Page->idd_tahapan->editAttributes() ?>>
        <?= $Page->idd_tahapan->selectOptionListHtml("x_idd_tahapan") ?>
    </select>
    <?= $Page->idd_tahapan->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->idd_tahapan->getErrorMessage() ?></div>
<?= $Page->idd_tahapan->Lookup->getParamTag($Page, "p_x_idd_tahapan") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='evaluasi_x_idd_tahapan']"),
        options = { name: "x_idd_tahapan", selectId: "evaluasi_x_idd_tahapan", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.evaluasi.fields.idd_tahapan.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->tahun_anggaran->Visible) { // tahun_anggaran ?>
    <div id="r_tahun_anggaran" class="form-group row">
        <label id="elh_evaluasi_tahun_anggaran" for="x_tahun_anggaran" class="<?= $Page->LeftColumnClass ?>"><?= $Page->tahun_anggaran->caption() ?><?= $Page->tahun_anggaran->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->tahun_anggaran->cellAttributes() ?>>
<span id="el_evaluasi_tahun_anggaran">
    <select
        id="x_tahun_anggaran"
        name="x_tahun_anggaran"
        class="form-control ew-select<?= $Page->tahun_anggaran->isInvalidClass() ?>"
        data-select2-id="evaluasi_x_tahun_anggaran"
        data-table="evaluasi"
        data-field="x_tahun_anggaran"
        data-page="1"
        data-value-separator="<?= $Page->tahun_anggaran->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->tahun_anggaran->getPlaceHolder()) ?>"
        <?= $Page->tahun_anggaran->editAttributes() ?>>
        <?= $Page->tahun_anggaran->selectOptionListHtml("x_tahun_anggaran") ?>
    </select>
    <?= $Page->tahun_anggaran->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->tahun_anggaran->getErrorMessage() ?></div>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='evaluasi_x_tahun_anggaran']"),
        options = { name: "x_tahun_anggaran", selectId: "evaluasi_x_tahun_anggaran", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.data = ew.vars.tables.evaluasi.fields.tahun_anggaran.lookupOptions;
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.evaluasi.fields.tahun_anggaran.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->surat_pengantar->Visible) { // surat_pengantar ?>
    <div id="r_surat_pengantar" class="form-group row">
        <label id="elh_evaluasi_surat_pengantar" class="<?= $Page->LeftColumnClass ?>"><?= $Page->surat_pengantar->caption() ?><?= $Page->surat_pengantar->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->surat_pengantar->cellAttributes() ?>>
<span id="el_evaluasi_surat_pengantar">
<div id="fd_x_surat_pengantar">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Page->surat_pengantar->title() ?>" data-table="evaluasi" data-field="x_surat_pengantar" data-page="1" name="x_surat_pengantar" id="x_surat_pengantar" lang="<?= CurrentLanguageID() ?>"<?= $Page->surat_pengantar->editAttributes() ?><?= ($Page->surat_pengantar->ReadOnly || $Page->surat_pengantar->Disabled) ? " disabled" : "" ?> aria-describedby="x_surat_pengantar_help">
        <label class="custom-file-label ew-file-label" for="x_surat_pengantar"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<?= $Page->surat_pengantar->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->surat_pengantar->getErrorMessage() ?></div>
<input type="hidden" name="fn_x_surat_pengantar" id= "fn_x_surat_pengantar" value="<?= $Page->surat_pengantar->Upload->FileName ?>">
<input type="hidden" name="fa_x_surat_pengantar" id= "fa_x_surat_pengantar" value="<?= (Post("fa_x_surat_pengantar") == "0") ? "0" : "1" ?>">
<input type="hidden" name="fs_x_surat_pengantar" id= "fs_x_surat_pengantar" value="200">
<input type="hidden" name="fx_x_surat_pengantar" id= "fx_x_surat_pengantar" value="<?= $Page->surat_pengantar->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_surat_pengantar" id= "fm_x_surat_pengantar" value="<?= $Page->surat_pengantar->UploadMaxFileSize ?>">
</div>
<table id="ft_x_surat_pengantar" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->rpjmd->Visible) { // rpjmd ?>
    <div id="r_rpjmd" class="form-group row">
        <label id="elh_evaluasi_rpjmd" class="<?= $Page->LeftColumnClass ?>"><?= $Page->rpjmd->caption() ?><?= $Page->rpjmd->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->rpjmd->cellAttributes() ?>>
<span id="el_evaluasi_rpjmd">
<div id="fd_x_rpjmd">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Page->rpjmd->title() ?>" data-table="evaluasi" data-field="x_rpjmd" data-page="1" name="x_rpjmd" id="x_rpjmd" lang="<?= CurrentLanguageID() ?>"<?= $Page->rpjmd->editAttributes() ?><?= ($Page->rpjmd->ReadOnly || $Page->rpjmd->Disabled) ? " disabled" : "" ?> aria-describedby="x_rpjmd_help">
        <label class="custom-file-label ew-file-label" for="x_rpjmd"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<?= $Page->rpjmd->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->rpjmd->getErrorMessage() ?></div>
<input type="hidden" name="fn_x_rpjmd" id= "fn_x_rpjmd" value="<?= $Page->rpjmd->Upload->FileName ?>">
<input type="hidden" name="fa_x_rpjmd" id= "fa_x_rpjmd" value="<?= (Post("fa_x_rpjmd") == "0") ? "0" : "1" ?>">
<input type="hidden" name="fs_x_rpjmd" id= "fs_x_rpjmd" value="200">
<input type="hidden" name="fx_x_rpjmd" id= "fx_x_rpjmd" value="<?= $Page->rpjmd->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_rpjmd" id= "fm_x_rpjmd" value="<?= $Page->rpjmd->UploadMaxFileSize ?>">
</div>
<table id="ft_x_rpjmd" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->rkpk->Visible) { // rkpk ?>
    <div id="r_rkpk" class="form-group row">
        <label id="elh_evaluasi_rkpk" class="<?= $Page->LeftColumnClass ?>"><?= $Page->rkpk->caption() ?><?= $Page->rkpk->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->rkpk->cellAttributes() ?>>
<span id="el_evaluasi_rkpk">
<div id="fd_x_rkpk">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Page->rkpk->title() ?>" data-table="evaluasi" data-field="x_rkpk" data-page="1" name="x_rkpk" id="x_rkpk" lang="<?= CurrentLanguageID() ?>"<?= $Page->rkpk->editAttributes() ?><?= ($Page->rkpk->ReadOnly || $Page->rkpk->Disabled) ? " disabled" : "" ?> aria-describedby="x_rkpk_help">
        <label class="custom-file-label ew-file-label" for="x_rkpk"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<?= $Page->rkpk->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->rkpk->getErrorMessage() ?></div>
<input type="hidden" name="fn_x_rkpk" id= "fn_x_rkpk" value="<?= $Page->rkpk->Upload->FileName ?>">
<input type="hidden" name="fa_x_rkpk" id= "fa_x_rkpk" value="<?= (Post("fa_x_rkpk") == "0") ? "0" : "1" ?>">
<input type="hidden" name="fs_x_rkpk" id= "fs_x_rkpk" value="200">
<input type="hidden" name="fx_x_rkpk" id= "fx_x_rkpk" value="<?= $Page->rkpk->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_rkpk" id= "fm_x_rkpk" value="<?= $Page->rkpk->UploadMaxFileSize ?>">
</div>
<table id="ft_x_rkpk" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->skd_rkuappas->Visible) { // skd_rkuappas ?>
    <div id="r_skd_rkuappas" class="form-group row">
        <label id="elh_evaluasi_skd_rkuappas" class="<?= $Page->LeftColumnClass ?>"><?= $Page->skd_rkuappas->caption() ?><?= $Page->skd_rkuappas->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->skd_rkuappas->cellAttributes() ?>>
<span id="el_evaluasi_skd_rkuappas">
<div id="fd_x_skd_rkuappas">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Page->skd_rkuappas->title() ?>" data-table="evaluasi" data-field="x_skd_rkuappas" data-page="1" name="x_skd_rkuappas" id="x_skd_rkuappas" lang="<?= CurrentLanguageID() ?>"<?= $Page->skd_rkuappas->editAttributes() ?><?= ($Page->skd_rkuappas->ReadOnly || $Page->skd_rkuappas->Disabled) ? " disabled" : "" ?> aria-describedby="x_skd_rkuappas_help">
        <label class="custom-file-label ew-file-label" for="x_skd_rkuappas"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<?= $Page->skd_rkuappas->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->skd_rkuappas->getErrorMessage() ?></div>
<input type="hidden" name="fn_x_skd_rkuappas" id= "fn_x_skd_rkuappas" value="<?= $Page->skd_rkuappas->Upload->FileName ?>">
<input type="hidden" name="fa_x_skd_rkuappas" id= "fa_x_skd_rkuappas" value="<?= (Post("fa_x_skd_rkuappas") == "0") ? "0" : "1" ?>">
<input type="hidden" name="fs_x_skd_rkuappas" id= "fs_x_skd_rkuappas" value="200">
<input type="hidden" name="fx_x_skd_rkuappas" id= "fx_x_skd_rkuappas" value="<?= $Page->skd_rkuappas->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_skd_rkuappas" id= "fm_x_skd_rkuappas" value="<?= $Page->skd_rkuappas->UploadMaxFileSize ?>">
</div>
<table id="ft_x_skd_rkuappas" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->kua->Visible) { // kua ?>
    <div id="r_kua" class="form-group row">
        <label id="elh_evaluasi_kua" class="<?= $Page->LeftColumnClass ?>"><?= $Page->kua->caption() ?><?= $Page->kua->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->kua->cellAttributes() ?>>
<span id="el_evaluasi_kua">
<div id="fd_x_kua">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Page->kua->title() ?>" data-table="evaluasi" data-field="x_kua" data-page="1" name="x_kua" id="x_kua" lang="<?= CurrentLanguageID() ?>"<?= $Page->kua->editAttributes() ?><?= ($Page->kua->ReadOnly || $Page->kua->Disabled) ? " disabled" : "" ?> aria-describedby="x_kua_help">
        <label class="custom-file-label ew-file-label" for="x_kua"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<?= $Page->kua->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->kua->getErrorMessage() ?></div>
<input type="hidden" name="fn_x_kua" id= "fn_x_kua" value="<?= $Page->kua->Upload->FileName ?>">
<input type="hidden" name="fa_x_kua" id= "fa_x_kua" value="<?= (Post("fa_x_kua") == "0") ? "0" : "1" ?>">
<input type="hidden" name="fs_x_kua" id= "fs_x_kua" value="200">
<input type="hidden" name="fx_x_kua" id= "fx_x_kua" value="<?= $Page->kua->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_kua" id= "fm_x_kua" value="<?= $Page->kua->UploadMaxFileSize ?>">
</div>
<table id="ft_x_kua" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->ppas->Visible) { // ppas ?>
    <div id="r_ppas" class="form-group row">
        <label id="elh_evaluasi_ppas" class="<?= $Page->LeftColumnClass ?>"><?= $Page->ppas->caption() ?><?= $Page->ppas->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->ppas->cellAttributes() ?>>
<span id="el_evaluasi_ppas">
<div id="fd_x_ppas">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Page->ppas->title() ?>" data-table="evaluasi" data-field="x_ppas" data-page="1" name="x_ppas" id="x_ppas" lang="<?= CurrentLanguageID() ?>"<?= $Page->ppas->editAttributes() ?><?= ($Page->ppas->ReadOnly || $Page->ppas->Disabled) ? " disabled" : "" ?> aria-describedby="x_ppas_help">
        <label class="custom-file-label ew-file-label" for="x_ppas"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<?= $Page->ppas->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->ppas->getErrorMessage() ?></div>
<input type="hidden" name="fn_x_ppas" id= "fn_x_ppas" value="<?= $Page->ppas->Upload->FileName ?>">
<input type="hidden" name="fa_x_ppas" id= "fa_x_ppas" value="<?= (Post("fa_x_ppas") == "0") ? "0" : "1" ?>">
<input type="hidden" name="fs_x_ppas" id= "fs_x_ppas" value="200">
<input type="hidden" name="fx_x_ppas" id= "fx_x_ppas" value="<?= $Page->ppas->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_ppas" id= "fm_x_ppas" value="<?= $Page->ppas->UploadMaxFileSize ?>">
</div>
<table id="ft_x_ppas" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->skd_rqanun->Visible) { // skd_rqanun ?>
    <div id="r_skd_rqanun" class="form-group row">
        <label id="elh_evaluasi_skd_rqanun" class="<?= $Page->LeftColumnClass ?>"><?= $Page->skd_rqanun->caption() ?><?= $Page->skd_rqanun->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->skd_rqanun->cellAttributes() ?>>
<span id="el_evaluasi_skd_rqanun">
<div id="fd_x_skd_rqanun">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Page->skd_rqanun->title() ?>" data-table="evaluasi" data-field="x_skd_rqanun" data-page="1" name="x_skd_rqanun" id="x_skd_rqanun" lang="<?= CurrentLanguageID() ?>"<?= $Page->skd_rqanun->editAttributes() ?><?= ($Page->skd_rqanun->ReadOnly || $Page->skd_rqanun->Disabled) ? " disabled" : "" ?> aria-describedby="x_skd_rqanun_help">
        <label class="custom-file-label ew-file-label" for="x_skd_rqanun"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<?= $Page->skd_rqanun->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->skd_rqanun->getErrorMessage() ?></div>
<input type="hidden" name="fn_x_skd_rqanun" id= "fn_x_skd_rqanun" value="<?= $Page->skd_rqanun->Upload->FileName ?>">
<input type="hidden" name="fa_x_skd_rqanun" id= "fa_x_skd_rqanun" value="<?= (Post("fa_x_skd_rqanun") == "0") ? "0" : "1" ?>">
<input type="hidden" name="fs_x_skd_rqanun" id= "fs_x_skd_rqanun" value="200">
<input type="hidden" name="fx_x_skd_rqanun" id= "fx_x_skd_rqanun" value="<?= $Page->skd_rqanun->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_skd_rqanun" id= "fm_x_skd_rqanun" value="<?= $Page->skd_rqanun->UploadMaxFileSize ?>">
</div>
<table id="ft_x_skd_rqanun" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->nota_keuangan->Visible) { // nota_keuangan ?>
    <div id="r_nota_keuangan" class="form-group row">
        <label id="elh_evaluasi_nota_keuangan" class="<?= $Page->LeftColumnClass ?>"><?= $Page->nota_keuangan->caption() ?><?= $Page->nota_keuangan->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->nota_keuangan->cellAttributes() ?>>
<span id="el_evaluasi_nota_keuangan">
<div id="fd_x_nota_keuangan">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Page->nota_keuangan->title() ?>" data-table="evaluasi" data-field="x_nota_keuangan" data-page="1" name="x_nota_keuangan" id="x_nota_keuangan" lang="<?= CurrentLanguageID() ?>"<?= $Page->nota_keuangan->editAttributes() ?><?= ($Page->nota_keuangan->ReadOnly || $Page->nota_keuangan->Disabled) ? " disabled" : "" ?> aria-describedby="x_nota_keuangan_help">
        <label class="custom-file-label ew-file-label" for="x_nota_keuangan"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<?= $Page->nota_keuangan->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->nota_keuangan->getErrorMessage() ?></div>
<input type="hidden" name="fn_x_nota_keuangan" id= "fn_x_nota_keuangan" value="<?= $Page->nota_keuangan->Upload->FileName ?>">
<input type="hidden" name="fa_x_nota_keuangan" id= "fa_x_nota_keuangan" value="<?= (Post("fa_x_nota_keuangan") == "0") ? "0" : "1" ?>">
<input type="hidden" name="fs_x_nota_keuangan" id= "fs_x_nota_keuangan" value="200">
<input type="hidden" name="fx_x_nota_keuangan" id= "fx_x_nota_keuangan" value="<?= $Page->nota_keuangan->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_nota_keuangan" id= "fm_x_nota_keuangan" value="<?= $Page->nota_keuangan->UploadMaxFileSize ?>">
</div>
<table id="ft_x_nota_keuangan" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->pengantar_nota->Visible) { // pengantar_nota ?>
    <div id="r_pengantar_nota" class="form-group row">
        <label id="elh_evaluasi_pengantar_nota" class="<?= $Page->LeftColumnClass ?>"><?= $Page->pengantar_nota->caption() ?><?= $Page->pengantar_nota->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->pengantar_nota->cellAttributes() ?>>
<span id="el_evaluasi_pengantar_nota">
<div id="fd_x_pengantar_nota">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Page->pengantar_nota->title() ?>" data-table="evaluasi" data-field="x_pengantar_nota" data-page="1" name="x_pengantar_nota" id="x_pengantar_nota" lang="<?= CurrentLanguageID() ?>"<?= $Page->pengantar_nota->editAttributes() ?><?= ($Page->pengantar_nota->ReadOnly || $Page->pengantar_nota->Disabled) ? " disabled" : "" ?> aria-describedby="x_pengantar_nota_help">
        <label class="custom-file-label ew-file-label" for="x_pengantar_nota"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<?= $Page->pengantar_nota->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->pengantar_nota->getErrorMessage() ?></div>
<input type="hidden" name="fn_x_pengantar_nota" id= "fn_x_pengantar_nota" value="<?= $Page->pengantar_nota->Upload->FileName ?>">
<input type="hidden" name="fa_x_pengantar_nota" id= "fa_x_pengantar_nota" value="<?= (Post("fa_x_pengantar_nota") == "0") ? "0" : "1" ?>">
<input type="hidden" name="fs_x_pengantar_nota" id= "fs_x_pengantar_nota" value="200">
<input type="hidden" name="fx_x_pengantar_nota" id= "fx_x_pengantar_nota" value="<?= $Page->pengantar_nota->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_pengantar_nota" id= "fm_x_pengantar_nota" value="<?= $Page->pengantar_nota->UploadMaxFileSize ?>">
</div>
<table id="ft_x_pengantar_nota" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->risalah_sidang->Visible) { // risalah_sidang ?>
    <div id="r_risalah_sidang" class="form-group row">
        <label id="elh_evaluasi_risalah_sidang" class="<?= $Page->LeftColumnClass ?>"><?= $Page->risalah_sidang->caption() ?><?= $Page->risalah_sidang->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->risalah_sidang->cellAttributes() ?>>
<span id="el_evaluasi_risalah_sidang">
<div id="fd_x_risalah_sidang">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Page->risalah_sidang->title() ?>" data-table="evaluasi" data-field="x_risalah_sidang" data-page="1" name="x_risalah_sidang" id="x_risalah_sidang" lang="<?= CurrentLanguageID() ?>"<?= $Page->risalah_sidang->editAttributes() ?><?= ($Page->risalah_sidang->ReadOnly || $Page->risalah_sidang->Disabled) ? " disabled" : "" ?> aria-describedby="x_risalah_sidang_help">
        <label class="custom-file-label ew-file-label" for="x_risalah_sidang"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<?= $Page->risalah_sidang->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->risalah_sidang->getErrorMessage() ?></div>
<input type="hidden" name="fn_x_risalah_sidang" id= "fn_x_risalah_sidang" value="<?= $Page->risalah_sidang->Upload->FileName ?>">
<input type="hidden" name="fa_x_risalah_sidang" id= "fa_x_risalah_sidang" value="<?= (Post("fa_x_risalah_sidang") == "0") ? "0" : "1" ?>">
<input type="hidden" name="fs_x_risalah_sidang" id= "fs_x_risalah_sidang" value="200">
<input type="hidden" name="fx_x_risalah_sidang" id= "fx_x_risalah_sidang" value="<?= $Page->risalah_sidang->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_risalah_sidang" id= "fm_x_risalah_sidang" value="<?= $Page->risalah_sidang->UploadMaxFileSize ?>">
</div>
<table id="ft_x_risalah_sidang" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->bap_apbk->Visible) { // bap_apbk ?>
    <div id="r_bap_apbk" class="form-group row">
        <label id="elh_evaluasi_bap_apbk" class="<?= $Page->LeftColumnClass ?>"><?= $Page->bap_apbk->caption() ?><?= $Page->bap_apbk->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->bap_apbk->cellAttributes() ?>>
<span id="el_evaluasi_bap_apbk">
<div id="fd_x_bap_apbk">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Page->bap_apbk->title() ?>" data-table="evaluasi" data-field="x_bap_apbk" data-page="1" name="x_bap_apbk" id="x_bap_apbk" lang="<?= CurrentLanguageID() ?>"<?= $Page->bap_apbk->editAttributes() ?><?= ($Page->bap_apbk->ReadOnly || $Page->bap_apbk->Disabled) ? " disabled" : "" ?> aria-describedby="x_bap_apbk_help">
        <label class="custom-file-label ew-file-label" for="x_bap_apbk"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<?= $Page->bap_apbk->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->bap_apbk->getErrorMessage() ?></div>
<input type="hidden" name="fn_x_bap_apbk" id= "fn_x_bap_apbk" value="<?= $Page->bap_apbk->Upload->FileName ?>">
<input type="hidden" name="fa_x_bap_apbk" id= "fa_x_bap_apbk" value="<?= (Post("fa_x_bap_apbk") == "0") ? "0" : "1" ?>">
<input type="hidden" name="fs_x_bap_apbk" id= "fs_x_bap_apbk" value="200">
<input type="hidden" name="fx_x_bap_apbk" id= "fx_x_bap_apbk" value="<?= $Page->bap_apbk->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_bap_apbk" id= "fm_x_bap_apbk" value="<?= $Page->bap_apbk->UploadMaxFileSize ?>">
</div>
<table id="ft_x_bap_apbk" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->rq_apbk->Visible) { // rq_apbk ?>
    <div id="r_rq_apbk" class="form-group row">
        <label id="elh_evaluasi_rq_apbk" class="<?= $Page->LeftColumnClass ?>"><?= $Page->rq_apbk->caption() ?><?= $Page->rq_apbk->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->rq_apbk->cellAttributes() ?>>
<span id="el_evaluasi_rq_apbk">
<div id="fd_x_rq_apbk">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Page->rq_apbk->title() ?>" data-table="evaluasi" data-field="x_rq_apbk" data-page="1" name="x_rq_apbk" id="x_rq_apbk" lang="<?= CurrentLanguageID() ?>"<?= $Page->rq_apbk->editAttributes() ?><?= ($Page->rq_apbk->ReadOnly || $Page->rq_apbk->Disabled) ? " disabled" : "" ?> aria-describedby="x_rq_apbk_help">
        <label class="custom-file-label ew-file-label" for="x_rq_apbk"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<?= $Page->rq_apbk->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->rq_apbk->getErrorMessage() ?></div>
<input type="hidden" name="fn_x_rq_apbk" id= "fn_x_rq_apbk" value="<?= $Page->rq_apbk->Upload->FileName ?>">
<input type="hidden" name="fa_x_rq_apbk" id= "fa_x_rq_apbk" value="<?= (Post("fa_x_rq_apbk") == "0") ? "0" : "1" ?>">
<input type="hidden" name="fs_x_rq_apbk" id= "fs_x_rq_apbk" value="200">
<input type="hidden" name="fx_x_rq_apbk" id= "fx_x_rq_apbk" value="<?= $Page->rq_apbk->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_rq_apbk" id= "fm_x_rq_apbk" value="<?= $Page->rq_apbk->UploadMaxFileSize ?>">
</div>
<table id="ft_x_rq_apbk" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->rp_penjabaran->Visible) { // rp_penjabaran ?>
    <div id="r_rp_penjabaran" class="form-group row">
        <label id="elh_evaluasi_rp_penjabaran" class="<?= $Page->LeftColumnClass ?>"><?= $Page->rp_penjabaran->caption() ?><?= $Page->rp_penjabaran->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->rp_penjabaran->cellAttributes() ?>>
<span id="el_evaluasi_rp_penjabaran">
<div id="fd_x_rp_penjabaran">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Page->rp_penjabaran->title() ?>" data-table="evaluasi" data-field="x_rp_penjabaran" data-page="1" name="x_rp_penjabaran" id="x_rp_penjabaran" lang="<?= CurrentLanguageID() ?>"<?= $Page->rp_penjabaran->editAttributes() ?><?= ($Page->rp_penjabaran->ReadOnly || $Page->rp_penjabaran->Disabled) ? " disabled" : "" ?> aria-describedby="x_rp_penjabaran_help">
        <label class="custom-file-label ew-file-label" for="x_rp_penjabaran"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<?= $Page->rp_penjabaran->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->rp_penjabaran->getErrorMessage() ?></div>
<input type="hidden" name="fn_x_rp_penjabaran" id= "fn_x_rp_penjabaran" value="<?= $Page->rp_penjabaran->Upload->FileName ?>">
<input type="hidden" name="fa_x_rp_penjabaran" id= "fa_x_rp_penjabaran" value="<?= (Post("fa_x_rp_penjabaran") == "0") ? "0" : "1" ?>">
<input type="hidden" name="fs_x_rp_penjabaran" id= "fs_x_rp_penjabaran" value="200">
<input type="hidden" name="fx_x_rp_penjabaran" id= "fx_x_rp_penjabaran" value="<?= $Page->rp_penjabaran->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_rp_penjabaran" id= "fm_x_rp_penjabaran" value="<?= $Page->rp_penjabaran->UploadMaxFileSize ?>">
</div>
<table id="ft_x_rp_penjabaran" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->jadwal_proses->Visible) { // jadwal_proses ?>
    <div id="r_jadwal_proses" class="form-group row">
        <label id="elh_evaluasi_jadwal_proses" class="<?= $Page->LeftColumnClass ?>"><?= $Page->jadwal_proses->caption() ?><?= $Page->jadwal_proses->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->jadwal_proses->cellAttributes() ?>>
<span id="el_evaluasi_jadwal_proses">
<div id="fd_x_jadwal_proses">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Page->jadwal_proses->title() ?>" data-table="evaluasi" data-field="x_jadwal_proses" data-page="1" name="x_jadwal_proses" id="x_jadwal_proses" lang="<?= CurrentLanguageID() ?>"<?= $Page->jadwal_proses->editAttributes() ?><?= ($Page->jadwal_proses->ReadOnly || $Page->jadwal_proses->Disabled) ? " disabled" : "" ?> aria-describedby="x_jadwal_proses_help">
        <label class="custom-file-label ew-file-label" for="x_jadwal_proses"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<?= $Page->jadwal_proses->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->jadwal_proses->getErrorMessage() ?></div>
<input type="hidden" name="fn_x_jadwal_proses" id= "fn_x_jadwal_proses" value="<?= $Page->jadwal_proses->Upload->FileName ?>">
<input type="hidden" name="fa_x_jadwal_proses" id= "fa_x_jadwal_proses" value="<?= (Post("fa_x_jadwal_proses") == "0") ? "0" : "1" ?>">
<input type="hidden" name="fs_x_jadwal_proses" id= "fs_x_jadwal_proses" value="200">
<input type="hidden" name="fx_x_jadwal_proses" id= "fx_x_jadwal_proses" value="<?= $Page->jadwal_proses->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_jadwal_proses" id= "fm_x_jadwal_proses" value="<?= $Page->jadwal_proses->UploadMaxFileSize ?>">
</div>
<table id="ft_x_jadwal_proses" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->sinkron_kebijakan->Visible) { // sinkron_kebijakan ?>
    <div id="r_sinkron_kebijakan" class="form-group row">
        <label id="elh_evaluasi_sinkron_kebijakan" class="<?= $Page->LeftColumnClass ?>"><?= $Page->sinkron_kebijakan->caption() ?><?= $Page->sinkron_kebijakan->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->sinkron_kebijakan->cellAttributes() ?>>
<span id="el_evaluasi_sinkron_kebijakan">
<div id="fd_x_sinkron_kebijakan">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Page->sinkron_kebijakan->title() ?>" data-table="evaluasi" data-field="x_sinkron_kebijakan" data-page="1" name="x_sinkron_kebijakan" id="x_sinkron_kebijakan" lang="<?= CurrentLanguageID() ?>"<?= $Page->sinkron_kebijakan->editAttributes() ?><?= ($Page->sinkron_kebijakan->ReadOnly || $Page->sinkron_kebijakan->Disabled) ? " disabled" : "" ?> aria-describedby="x_sinkron_kebijakan_help">
        <label class="custom-file-label ew-file-label" for="x_sinkron_kebijakan"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<?= $Page->sinkron_kebijakan->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->sinkron_kebijakan->getErrorMessage() ?></div>
<input type="hidden" name="fn_x_sinkron_kebijakan" id= "fn_x_sinkron_kebijakan" value="<?= $Page->sinkron_kebijakan->Upload->FileName ?>">
<input type="hidden" name="fa_x_sinkron_kebijakan" id= "fa_x_sinkron_kebijakan" value="<?= (Post("fa_x_sinkron_kebijakan") == "0") ? "0" : "1" ?>">
<input type="hidden" name="fs_x_sinkron_kebijakan" id= "fs_x_sinkron_kebijakan" value="200">
<input type="hidden" name="fx_x_sinkron_kebijakan" id= "fx_x_sinkron_kebijakan" value="<?= $Page->sinkron_kebijakan->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_sinkron_kebijakan" id= "fm_x_sinkron_kebijakan" value="<?= $Page->sinkron_kebijakan->UploadMaxFileSize ?>">
</div>
<table id="ft_x_sinkron_kebijakan" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->konsistensi_program->Visible) { // konsistensi_program ?>
    <div id="r_konsistensi_program" class="form-group row">
        <label id="elh_evaluasi_konsistensi_program" class="<?= $Page->LeftColumnClass ?>"><?= $Page->konsistensi_program->caption() ?><?= $Page->konsistensi_program->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->konsistensi_program->cellAttributes() ?>>
<span id="el_evaluasi_konsistensi_program">
<div id="fd_x_konsistensi_program">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Page->konsistensi_program->title() ?>" data-table="evaluasi" data-field="x_konsistensi_program" data-page="1" name="x_konsistensi_program" id="x_konsistensi_program" lang="<?= CurrentLanguageID() ?>"<?= $Page->konsistensi_program->editAttributes() ?><?= ($Page->konsistensi_program->ReadOnly || $Page->konsistensi_program->Disabled) ? " disabled" : "" ?> aria-describedby="x_konsistensi_program_help">
        <label class="custom-file-label ew-file-label" for="x_konsistensi_program"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<?= $Page->konsistensi_program->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->konsistensi_program->getErrorMessage() ?></div>
<input type="hidden" name="fn_x_konsistensi_program" id= "fn_x_konsistensi_program" value="<?= $Page->konsistensi_program->Upload->FileName ?>">
<input type="hidden" name="fa_x_konsistensi_program" id= "fa_x_konsistensi_program" value="<?= (Post("fa_x_konsistensi_program") == "0") ? "0" : "1" ?>">
<input type="hidden" name="fs_x_konsistensi_program" id= "fs_x_konsistensi_program" value="200">
<input type="hidden" name="fx_x_konsistensi_program" id= "fx_x_konsistensi_program" value="<?= $Page->konsistensi_program->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_konsistensi_program" id= "fm_x_konsistensi_program" value="<?= $Page->konsistensi_program->UploadMaxFileSize ?>">
</div>
<table id="ft_x_konsistensi_program" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->alokasi_pendidikan->Visible) { // alokasi_pendidikan ?>
    <div id="r_alokasi_pendidikan" class="form-group row">
        <label id="elh_evaluasi_alokasi_pendidikan" class="<?= $Page->LeftColumnClass ?>"><?= $Page->alokasi_pendidikan->caption() ?><?= $Page->alokasi_pendidikan->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->alokasi_pendidikan->cellAttributes() ?>>
<span id="el_evaluasi_alokasi_pendidikan">
<div id="fd_x_alokasi_pendidikan">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Page->alokasi_pendidikan->title() ?>" data-table="evaluasi" data-field="x_alokasi_pendidikan" data-page="1" name="x_alokasi_pendidikan" id="x_alokasi_pendidikan" lang="<?= CurrentLanguageID() ?>"<?= $Page->alokasi_pendidikan->editAttributes() ?><?= ($Page->alokasi_pendidikan->ReadOnly || $Page->alokasi_pendidikan->Disabled) ? " disabled" : "" ?> aria-describedby="x_alokasi_pendidikan_help">
        <label class="custom-file-label ew-file-label" for="x_alokasi_pendidikan"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<?= $Page->alokasi_pendidikan->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->alokasi_pendidikan->getErrorMessage() ?></div>
<input type="hidden" name="fn_x_alokasi_pendidikan" id= "fn_x_alokasi_pendidikan" value="<?= $Page->alokasi_pendidikan->Upload->FileName ?>">
<input type="hidden" name="fa_x_alokasi_pendidikan" id= "fa_x_alokasi_pendidikan" value="<?= (Post("fa_x_alokasi_pendidikan") == "0") ? "0" : "1" ?>">
<input type="hidden" name="fs_x_alokasi_pendidikan" id= "fs_x_alokasi_pendidikan" value="200">
<input type="hidden" name="fx_x_alokasi_pendidikan" id= "fx_x_alokasi_pendidikan" value="<?= $Page->alokasi_pendidikan->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_alokasi_pendidikan" id= "fm_x_alokasi_pendidikan" value="<?= $Page->alokasi_pendidikan->UploadMaxFileSize ?>">
</div>
<table id="ft_x_alokasi_pendidikan" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->alokasi_kesehatan->Visible) { // alokasi_kesehatan ?>
    <div id="r_alokasi_kesehatan" class="form-group row">
        <label id="elh_evaluasi_alokasi_kesehatan" class="<?= $Page->LeftColumnClass ?>"><?= $Page->alokasi_kesehatan->caption() ?><?= $Page->alokasi_kesehatan->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->alokasi_kesehatan->cellAttributes() ?>>
<span id="el_evaluasi_alokasi_kesehatan">
<div id="fd_x_alokasi_kesehatan">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Page->alokasi_kesehatan->title() ?>" data-table="evaluasi" data-field="x_alokasi_kesehatan" data-page="1" name="x_alokasi_kesehatan" id="x_alokasi_kesehatan" lang="<?= CurrentLanguageID() ?>"<?= $Page->alokasi_kesehatan->editAttributes() ?><?= ($Page->alokasi_kesehatan->ReadOnly || $Page->alokasi_kesehatan->Disabled) ? " disabled" : "" ?> aria-describedby="x_alokasi_kesehatan_help">
        <label class="custom-file-label ew-file-label" for="x_alokasi_kesehatan"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<?= $Page->alokasi_kesehatan->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->alokasi_kesehatan->getErrorMessage() ?></div>
<input type="hidden" name="fn_x_alokasi_kesehatan" id= "fn_x_alokasi_kesehatan" value="<?= $Page->alokasi_kesehatan->Upload->FileName ?>">
<input type="hidden" name="fa_x_alokasi_kesehatan" id= "fa_x_alokasi_kesehatan" value="<?= (Post("fa_x_alokasi_kesehatan") == "0") ? "0" : "1" ?>">
<input type="hidden" name="fs_x_alokasi_kesehatan" id= "fs_x_alokasi_kesehatan" value="200">
<input type="hidden" name="fx_x_alokasi_kesehatan" id= "fx_x_alokasi_kesehatan" value="<?= $Page->alokasi_kesehatan->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_alokasi_kesehatan" id= "fm_x_alokasi_kesehatan" value="<?= $Page->alokasi_kesehatan->UploadMaxFileSize ?>">
</div>
<table id="ft_x_alokasi_kesehatan" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->alokasi_belanja->Visible) { // alokasi_belanja ?>
    <div id="r_alokasi_belanja" class="form-group row">
        <label id="elh_evaluasi_alokasi_belanja" class="<?= $Page->LeftColumnClass ?>"><?= $Page->alokasi_belanja->caption() ?><?= $Page->alokasi_belanja->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->alokasi_belanja->cellAttributes() ?>>
<span id="el_evaluasi_alokasi_belanja">
<div id="fd_x_alokasi_belanja">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Page->alokasi_belanja->title() ?>" data-table="evaluasi" data-field="x_alokasi_belanja" data-page="1" name="x_alokasi_belanja" id="x_alokasi_belanja" lang="<?= CurrentLanguageID() ?>"<?= $Page->alokasi_belanja->editAttributes() ?><?= ($Page->alokasi_belanja->ReadOnly || $Page->alokasi_belanja->Disabled) ? " disabled" : "" ?> aria-describedby="x_alokasi_belanja_help">
        <label class="custom-file-label ew-file-label" for="x_alokasi_belanja"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<?= $Page->alokasi_belanja->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->alokasi_belanja->getErrorMessage() ?></div>
<input type="hidden" name="fn_x_alokasi_belanja" id= "fn_x_alokasi_belanja" value="<?= $Page->alokasi_belanja->Upload->FileName ?>">
<input type="hidden" name="fa_x_alokasi_belanja" id= "fa_x_alokasi_belanja" value="<?= (Post("fa_x_alokasi_belanja") == "0") ? "0" : "1" ?>">
<input type="hidden" name="fs_x_alokasi_belanja" id= "fs_x_alokasi_belanja" value="200">
<input type="hidden" name="fx_x_alokasi_belanja" id= "fx_x_alokasi_belanja" value="<?= $Page->alokasi_belanja->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_alokasi_belanja" id= "fm_x_alokasi_belanja" value="<?= $Page->alokasi_belanja->UploadMaxFileSize ?>">
</div>
<table id="ft_x_alokasi_belanja" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->bak_kegiatan->Visible) { // bak_kegiatan ?>
    <div id="r_bak_kegiatan" class="form-group row">
        <label id="elh_evaluasi_bak_kegiatan" class="<?= $Page->LeftColumnClass ?>"><?= $Page->bak_kegiatan->caption() ?><?= $Page->bak_kegiatan->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->bak_kegiatan->cellAttributes() ?>>
<span id="el_evaluasi_bak_kegiatan">
<div id="fd_x_bak_kegiatan">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Page->bak_kegiatan->title() ?>" data-table="evaluasi" data-field="x_bak_kegiatan" data-page="1" name="x_bak_kegiatan" id="x_bak_kegiatan" lang="<?= CurrentLanguageID() ?>"<?= $Page->bak_kegiatan->editAttributes() ?><?= ($Page->bak_kegiatan->ReadOnly || $Page->bak_kegiatan->Disabled) ? " disabled" : "" ?> aria-describedby="x_bak_kegiatan_help">
        <label class="custom-file-label ew-file-label" for="x_bak_kegiatan"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<?= $Page->bak_kegiatan->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->bak_kegiatan->getErrorMessage() ?></div>
<input type="hidden" name="fn_x_bak_kegiatan" id= "fn_x_bak_kegiatan" value="<?= $Page->bak_kegiatan->Upload->FileName ?>">
<input type="hidden" name="fa_x_bak_kegiatan" id= "fa_x_bak_kegiatan" value="<?= (Post("fa_x_bak_kegiatan") == "0") ? "0" : "1" ?>">
<input type="hidden" name="fs_x_bak_kegiatan" id= "fs_x_bak_kegiatan" value="200">
<input type="hidden" name="fx_x_bak_kegiatan" id= "fx_x_bak_kegiatan" value="<?= $Page->bak_kegiatan->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_bak_kegiatan" id= "fm_x_bak_kegiatan" value="<?= $Page->bak_kegiatan->UploadMaxFileSize ?>">
</div>
<table id="ft_x_bak_kegiatan" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->softcopy_rka->Visible) { // softcopy_rka ?>
    <div id="r_softcopy_rka" class="form-group row">
        <label id="elh_evaluasi_softcopy_rka" class="<?= $Page->LeftColumnClass ?>"><?= $Page->softcopy_rka->caption() ?><?= $Page->softcopy_rka->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->softcopy_rka->cellAttributes() ?>>
<span id="el_evaluasi_softcopy_rka">
<div id="fd_x_softcopy_rka">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Page->softcopy_rka->title() ?>" data-table="evaluasi" data-field="x_softcopy_rka" data-page="1" name="x_softcopy_rka" id="x_softcopy_rka" lang="<?= CurrentLanguageID() ?>"<?= $Page->softcopy_rka->editAttributes() ?><?= ($Page->softcopy_rka->ReadOnly || $Page->softcopy_rka->Disabled) ? " disabled" : "" ?> aria-describedby="x_softcopy_rka_help">
        <label class="custom-file-label ew-file-label" for="x_softcopy_rka"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<?= $Page->softcopy_rka->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->softcopy_rka->getErrorMessage() ?></div>
<input type="hidden" name="fn_x_softcopy_rka" id= "fn_x_softcopy_rka" value="<?= $Page->softcopy_rka->Upload->FileName ?>">
<input type="hidden" name="fa_x_softcopy_rka" id= "fa_x_softcopy_rka" value="<?= (Post("fa_x_softcopy_rka") == "0") ? "0" : "1" ?>">
<input type="hidden" name="fs_x_softcopy_rka" id= "fs_x_softcopy_rka" value="200">
<input type="hidden" name="fx_x_softcopy_rka" id= "fx_x_softcopy_rka" value="<?= $Page->softcopy_rka->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_softcopy_rka" id= "fm_x_softcopy_rka" value="<?= $Page->softcopy_rka->UploadMaxFileSize ?>">
</div>
<table id="ft_x_softcopy_rka" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->otsus->Visible) { // otsus ?>
    <div id="r_otsus" class="form-group row">
        <label id="elh_evaluasi_otsus" class="<?= $Page->LeftColumnClass ?>"><?= $Page->otsus->caption() ?><?= $Page->otsus->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->otsus->cellAttributes() ?>>
<span id="el_evaluasi_otsus">
<div id="fd_x_otsus">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Page->otsus->title() ?>" data-table="evaluasi" data-field="x_otsus" data-page="1" name="x_otsus" id="x_otsus" lang="<?= CurrentLanguageID() ?>"<?= $Page->otsus->editAttributes() ?><?= ($Page->otsus->ReadOnly || $Page->otsus->Disabled) ? " disabled" : "" ?> aria-describedby="x_otsus_help">
        <label class="custom-file-label ew-file-label" for="x_otsus"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<?= $Page->otsus->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->otsus->getErrorMessage() ?></div>
<input type="hidden" name="fn_x_otsus" id= "fn_x_otsus" value="<?= $Page->otsus->Upload->FileName ?>">
<input type="hidden" name="fa_x_otsus" id= "fa_x_otsus" value="<?= (Post("fa_x_otsus") == "0") ? "0" : "1" ?>">
<input type="hidden" name="fs_x_otsus" id= "fs_x_otsus" value="200">
<input type="hidden" name="fx_x_otsus" id= "fx_x_otsus" value="<?= $Page->otsus->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_otsus" id= "fm_x_otsus" value="<?= $Page->otsus->UploadMaxFileSize ?>">
</div>
<table id="ft_x_otsus" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->qanun_perbup->Visible) { // qanun_perbup ?>
    <div id="r_qanun_perbup" class="form-group row">
        <label id="elh_evaluasi_qanun_perbup" class="<?= $Page->LeftColumnClass ?>"><?= $Page->qanun_perbup->caption() ?><?= $Page->qanun_perbup->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->qanun_perbup->cellAttributes() ?>>
<span id="el_evaluasi_qanun_perbup">
<div id="fd_x_qanun_perbup">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Page->qanun_perbup->title() ?>" data-table="evaluasi" data-field="x_qanun_perbup" data-page="1" name="x_qanun_perbup" id="x_qanun_perbup" lang="<?= CurrentLanguageID() ?>"<?= $Page->qanun_perbup->editAttributes() ?><?= ($Page->qanun_perbup->ReadOnly || $Page->qanun_perbup->Disabled) ? " disabled" : "" ?> aria-describedby="x_qanun_perbup_help">
        <label class="custom-file-label ew-file-label" for="x_qanun_perbup"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<?= $Page->qanun_perbup->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->qanun_perbup->getErrorMessage() ?></div>
<input type="hidden" name="fn_x_qanun_perbup" id= "fn_x_qanun_perbup" value="<?= $Page->qanun_perbup->Upload->FileName ?>">
<input type="hidden" name="fa_x_qanun_perbup" id= "fa_x_qanun_perbup" value="<?= (Post("fa_x_qanun_perbup") == "0") ? "0" : "1" ?>">
<input type="hidden" name="fs_x_qanun_perbup" id= "fs_x_qanun_perbup" value="200">
<input type="hidden" name="fx_x_qanun_perbup" id= "fx_x_qanun_perbup" value="<?= $Page->qanun_perbup->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_qanun_perbup" id= "fm_x_qanun_perbup" value="<?= $Page->qanun_perbup->UploadMaxFileSize ?>">
</div>
<table id="ft_x_qanun_perbup" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->tindak_apbkp->Visible) { // tindak_apbkp ?>
    <div id="r_tindak_apbkp" class="form-group row">
        <label id="elh_evaluasi_tindak_apbkp" class="<?= $Page->LeftColumnClass ?>"><?= $Page->tindak_apbkp->caption() ?><?= $Page->tindak_apbkp->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->tindak_apbkp->cellAttributes() ?>>
<span id="el_evaluasi_tindak_apbkp">
<div id="fd_x_tindak_apbkp">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Page->tindak_apbkp->title() ?>" data-table="evaluasi" data-field="x_tindak_apbkp" data-page="1" name="x_tindak_apbkp" id="x_tindak_apbkp" lang="<?= CurrentLanguageID() ?>"<?= $Page->tindak_apbkp->editAttributes() ?><?= ($Page->tindak_apbkp->ReadOnly || $Page->tindak_apbkp->Disabled) ? " disabled" : "" ?> aria-describedby="x_tindak_apbkp_help">
        <label class="custom-file-label ew-file-label" for="x_tindak_apbkp"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<?= $Page->tindak_apbkp->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->tindak_apbkp->getErrorMessage() ?></div>
<input type="hidden" name="fn_x_tindak_apbkp" id= "fn_x_tindak_apbkp" value="<?= $Page->tindak_apbkp->Upload->FileName ?>">
<input type="hidden" name="fa_x_tindak_apbkp" id= "fa_x_tindak_apbkp" value="<?= (Post("fa_x_tindak_apbkp") == "0") ? "0" : "1" ?>">
<input type="hidden" name="fs_x_tindak_apbkp" id= "fs_x_tindak_apbkp" value="200">
<input type="hidden" name="fx_x_tindak_apbkp" id= "fx_x_tindak_apbkp" value="<?= $Page->tindak_apbkp->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_tindak_apbkp" id= "fm_x_tindak_apbkp" value="<?= $Page->tindak_apbkp->UploadMaxFileSize ?>">
</div>
<table id="ft_x_tindak_apbkp" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->status->Visible) { // status ?>
    <div id="r_status" class="form-group row">
        <label id="elh_evaluasi_status" for="x_status" class="<?= $Page->LeftColumnClass ?>"><?= $Page->status->caption() ?><?= $Page->status->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->status->cellAttributes() ?>>
<span id="el_evaluasi_status">
    <select
        id="x_status"
        name="x_status"
        class="form-control ew-select<?= $Page->status->isInvalidClass() ?>"
        data-select2-id="evaluasi_x_status"
        data-table="evaluasi"
        data-field="x_status"
        data-page="1"
        data-value-separator="<?= $Page->status->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->status->getPlaceHolder()) ?>"
        <?= $Page->status->editAttributes() ?>>
        <?= $Page->status->selectOptionListHtml("x_status") ?>
    </select>
    <?= $Page->status->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->status->getErrorMessage() ?></div>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='evaluasi_x_status']"),
        options = { name: "x_status", selectId: "evaluasi_x_status", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.data = ew.vars.tables.evaluasi.fields.status.lookupOptions;
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.evaluasi.fields.status.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->idd_user->Visible) { // idd_user ?>
    <div id="r_idd_user" class="form-group row">
        <label id="elh_evaluasi_idd_user" for="x_idd_user" class="<?= $Page->LeftColumnClass ?>"><?= $Page->idd_user->caption() ?><?= $Page->idd_user->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->idd_user->cellAttributes() ?>>
<?php if (!$Security->isAdmin() && $Security->isLoggedIn() && !$Page->userIDAllow("edit")) { // Non system admin ?>
<span id="el_evaluasi_idd_user">
<span<?= $Page->idd_user->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->idd_user->getDisplayValue($Page->idd_user->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="evaluasi" data-field="x_idd_user" data-hidden="1" data-page="1" name="x_idd_user" id="x_idd_user" value="<?= HtmlEncode($Page->idd_user->CurrentValue) ?>">
<?php } else { ?>
<span id="el_evaluasi_idd_user">
    <select
        id="x_idd_user"
        name="x_idd_user"
        class="form-control ew-select<?= $Page->idd_user->isInvalidClass() ?>"
        data-select2-id="evaluasi_x_idd_user"
        data-table="evaluasi"
        data-field="x_idd_user"
        data-page="1"
        data-value-separator="<?= $Page->idd_user->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->idd_user->getPlaceHolder()) ?>"
        <?= $Page->idd_user->editAttributes() ?>>
        <?= $Page->idd_user->selectOptionListHtml("x_idd_user") ?>
    </select>
    <?= $Page->idd_user->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->idd_user->getErrorMessage() ?></div>
<?= $Page->idd_user->Lookup->getParamTag($Page, "p_x_idd_user") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='evaluasi_x_idd_user']"),
        options = { name: "x_idd_user", selectId: "evaluasi_x_idd_user", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.evaluasi.fields.idd_user.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$Page->IsModal) { ?>
<div class="form-group row"><!-- buttons .form-group -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit"><?= $Language->phrase("SaveBtn") ?></button>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
    </div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<script>
// Field event handlers
loadjs.ready("head", function() {
    ew.addEventHandlers("evaluasi");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
