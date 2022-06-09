<?php

namespace PHPMaker2021\silpa;

// Page object
$PertanggungjawabanEdit = &$Page;
?>
<script>
var currentForm, currentPageID;
var fpertanggungjawabanedit;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "edit";
    fpertanggungjawabanedit = currentForm = new ew.Form("fpertanggungjawabanedit", "edit");

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "pertanggungjawaban")) ?>,
        fields = currentTable.fields;
    if (!ew.vars.tables.pertanggungjawaban)
        ew.vars.tables.pertanggungjawaban = currentTable;
    fpertanggungjawabanedit.addFields([
        ["idd_evaluasi", [fields.idd_evaluasi.visible && fields.idd_evaluasi.required ? ew.Validators.required(fields.idd_evaluasi.caption) : null], fields.idd_evaluasi.isInvalid],
        ["tanggal", [fields.tanggal.visible && fields.tanggal.required ? ew.Validators.required(fields.tanggal.caption) : null, ew.Validators.datetime(0)], fields.tanggal.isInvalid],
        ["idd_wilayah", [fields.idd_wilayah.visible && fields.idd_wilayah.required ? ew.Validators.required(fields.idd_wilayah.caption) : null], fields.idd_wilayah.isInvalid],
        ["kd_satker", [fields.kd_satker.visible && fields.kd_satker.required ? ew.Validators.required(fields.kd_satker.caption) : null], fields.kd_satker.isInvalid],
        ["idd_tahapan", [fields.idd_tahapan.visible && fields.idd_tahapan.required ? ew.Validators.required(fields.idd_tahapan.caption) : null], fields.idd_tahapan.isInvalid],
        ["tahun_anggaran", [fields.tahun_anggaran.visible && fields.tahun_anggaran.required ? ew.Validators.required(fields.tahun_anggaran.caption) : null], fields.tahun_anggaran.isInvalid],
        ["surat_pengantar", [fields.surat_pengantar.visible && fields.surat_pengantar.required ? ew.Validators.fileRequired(fields.surat_pengantar.caption) : null], fields.surat_pengantar.isInvalid],
        ["skd_rqanunpert", [fields.skd_rqanunpert.visible && fields.skd_rqanunpert.required ? ew.Validators.fileRequired(fields.skd_rqanunpert.caption) : null], fields.skd_rqanunpert.isInvalid],
        ["rq_apbkpert", [fields.rq_apbkpert.visible && fields.rq_apbkpert.required ? ew.Validators.fileRequired(fields.rq_apbkpert.caption) : null], fields.rq_apbkpert.isInvalid],
        ["bap_apbkpert", [fields.bap_apbkpert.visible && fields.bap_apbkpert.required ? ew.Validators.fileRequired(fields.bap_apbkpert.caption) : null], fields.bap_apbkpert.isInvalid],
        ["risalah_sidang", [fields.risalah_sidang.visible && fields.risalah_sidang.required ? ew.Validators.fileRequired(fields.risalah_sidang.caption) : null], fields.risalah_sidang.isInvalid],
        ["absen_peserta", [fields.absen_peserta.visible && fields.absen_peserta.required ? ew.Validators.fileRequired(fields.absen_peserta.caption) : null], fields.absen_peserta.isInvalid],
        ["neraca", [fields.neraca.visible && fields.neraca.required ? ew.Validators.fileRequired(fields.neraca.caption) : null], fields.neraca.isInvalid],
        ["lra", [fields.lra.visible && fields.lra.required ? ew.Validators.fileRequired(fields.lra.caption) : null], fields.lra.isInvalid],
        ["calk", [fields.calk.visible && fields.calk.required ? ew.Validators.fileRequired(fields.calk.caption) : null], fields.calk.isInvalid],
        ["lo", [fields.lo.visible && fields.lo.required ? ew.Validators.fileRequired(fields.lo.caption) : null], fields.lo.isInvalid],
        ["lpe", [fields.lpe.visible && fields.lpe.required ? ew.Validators.fileRequired(fields.lpe.caption) : null], fields.lpe.isInvalid],
        ["lpsal", [fields.lpsal.visible && fields.lpsal.required ? ew.Validators.fileRequired(fields.lpsal.caption) : null], fields.lpsal.isInvalid],
        ["lak", [fields.lak.visible && fields.lak.required ? ew.Validators.fileRequired(fields.lak.caption) : null], fields.lak.isInvalid],
        ["laporan_pemeriksaan", [fields.laporan_pemeriksaan.visible && fields.laporan_pemeriksaan.required ? ew.Validators.fileRequired(fields.laporan_pemeriksaan.caption) : null], fields.laporan_pemeriksaan.isInvalid],
        ["softcopy_rqanun", [fields.softcopy_rqanun.visible && fields.softcopy_rqanun.required ? ew.Validators.fileRequired(fields.softcopy_rqanun.caption) : null], fields.softcopy_rqanun.isInvalid],
        ["status", [fields.status.visible && fields.status.required ? ew.Validators.required(fields.status.caption) : null], fields.status.isInvalid],
        ["idd_user", [fields.idd_user.visible && fields.idd_user.required ? ew.Validators.required(fields.idd_user.caption) : null], fields.idd_user.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fpertanggungjawabanedit,
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
    fpertanggungjawabanedit.validate = function () {
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
    fpertanggungjawabanedit.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fpertanggungjawabanedit.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fpertanggungjawabanedit.lists.idd_wilayah = <?= $Page->idd_wilayah->toClientList($Page) ?>;
    fpertanggungjawabanedit.lists.kd_satker = <?= $Page->kd_satker->toClientList($Page) ?>;
    fpertanggungjawabanedit.lists.idd_tahapan = <?= $Page->idd_tahapan->toClientList($Page) ?>;
    fpertanggungjawabanedit.lists.tahun_anggaran = <?= $Page->tahun_anggaran->toClientList($Page) ?>;
    fpertanggungjawabanedit.lists.status = <?= $Page->status->toClientList($Page) ?>;
    fpertanggungjawabanedit.lists.idd_user = <?= $Page->idd_user->toClientList($Page) ?>;
    loadjs.done("fpertanggungjawabanedit");
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
<form name="fpertanggungjawabanedit" id="fpertanggungjawabanedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="pertanggungjawaban">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->idd_evaluasi->Visible) { // idd_evaluasi ?>
    <div id="r_idd_evaluasi" class="form-group row">
        <label id="elh_pertanggungjawaban_idd_evaluasi" class="<?= $Page->LeftColumnClass ?>"><?= $Page->idd_evaluasi->caption() ?><?= $Page->idd_evaluasi->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->idd_evaluasi->cellAttributes() ?>>
<span id="el_pertanggungjawaban_idd_evaluasi">
<span<?= $Page->idd_evaluasi->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->idd_evaluasi->getDisplayValue($Page->idd_evaluasi->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="pertanggungjawaban" data-field="x_idd_evaluasi" data-hidden="1" name="x_idd_evaluasi" id="x_idd_evaluasi" value="<?= HtmlEncode($Page->idd_evaluasi->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->tanggal->Visible) { // tanggal ?>
    <div id="r_tanggal" class="form-group row">
        <label id="elh_pertanggungjawaban_tanggal" for="x_tanggal" class="<?= $Page->LeftColumnClass ?>"><?= $Page->tanggal->caption() ?><?= $Page->tanggal->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->tanggal->cellAttributes() ?>>
<span id="el_pertanggungjawaban_tanggal">
<input type="<?= $Page->tanggal->getInputTextType() ?>" data-table="pertanggungjawaban" data-field="x_tanggal" name="x_tanggal" id="x_tanggal" placeholder="<?= HtmlEncode($Page->tanggal->getPlaceHolder()) ?>" value="<?= $Page->tanggal->EditValue ?>"<?= $Page->tanggal->editAttributes() ?> aria-describedby="x_tanggal_help">
<?= $Page->tanggal->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->tanggal->getErrorMessage() ?></div>
<?php if (!$Page->tanggal->ReadOnly && !$Page->tanggal->Disabled && !isset($Page->tanggal->EditAttrs["readonly"]) && !isset($Page->tanggal->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fpertanggungjawabanedit", "datetimepicker"], function() {
    ew.createDateTimePicker("fpertanggungjawabanedit", "x_tanggal", {"ignoreReadonly":true,"useCurrent":false,"format":0});
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->idd_wilayah->Visible) { // idd_wilayah ?>
    <div id="r_idd_wilayah" class="form-group row">
        <label id="elh_pertanggungjawaban_idd_wilayah" for="x_idd_wilayah" class="<?= $Page->LeftColumnClass ?>"><?= $Page->idd_wilayah->caption() ?><?= $Page->idd_wilayah->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->idd_wilayah->cellAttributes() ?>>
<span id="el_pertanggungjawaban_idd_wilayah">
    <select
        id="x_idd_wilayah"
        name="x_idd_wilayah"
        class="form-control ew-select<?= $Page->idd_wilayah->isInvalidClass() ?>"
        data-select2-id="pertanggungjawaban_x_idd_wilayah"
        data-table="pertanggungjawaban"
        data-field="x_idd_wilayah"
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
    var el = document.querySelector("select[data-select2-id='pertanggungjawaban_x_idd_wilayah']"),
        options = { name: "x_idd_wilayah", selectId: "pertanggungjawaban_x_idd_wilayah", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.pertanggungjawaban.fields.idd_wilayah.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->kd_satker->Visible) { // kd_satker ?>
    <div id="r_kd_satker" class="form-group row">
        <label id="elh_pertanggungjawaban_kd_satker" for="x_kd_satker" class="<?= $Page->LeftColumnClass ?>"><?= $Page->kd_satker->caption() ?><?= $Page->kd_satker->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->kd_satker->cellAttributes() ?>>
<span id="el_pertanggungjawaban_kd_satker">
    <select
        id="x_kd_satker"
        name="x_kd_satker"
        class="form-control ew-select<?= $Page->kd_satker->isInvalidClass() ?>"
        data-select2-id="pertanggungjawaban_x_kd_satker"
        data-table="pertanggungjawaban"
        data-field="x_kd_satker"
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
    var el = document.querySelector("select[data-select2-id='pertanggungjawaban_x_kd_satker']"),
        options = { name: "x_kd_satker", selectId: "pertanggungjawaban_x_kd_satker", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.pertanggungjawaban.fields.kd_satker.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->idd_tahapan->Visible) { // idd_tahapan ?>
    <div id="r_idd_tahapan" class="form-group row">
        <label id="elh_pertanggungjawaban_idd_tahapan" for="x_idd_tahapan" class="<?= $Page->LeftColumnClass ?>"><?= $Page->idd_tahapan->caption() ?><?= $Page->idd_tahapan->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->idd_tahapan->cellAttributes() ?>>
<span id="el_pertanggungjawaban_idd_tahapan">
    <select
        id="x_idd_tahapan"
        name="x_idd_tahapan"
        class="form-control ew-select<?= $Page->idd_tahapan->isInvalidClass() ?>"
        data-select2-id="pertanggungjawaban_x_idd_tahapan"
        data-table="pertanggungjawaban"
        data-field="x_idd_tahapan"
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
    var el = document.querySelector("select[data-select2-id='pertanggungjawaban_x_idd_tahapan']"),
        options = { name: "x_idd_tahapan", selectId: "pertanggungjawaban_x_idd_tahapan", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.pertanggungjawaban.fields.idd_tahapan.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->tahun_anggaran->Visible) { // tahun_anggaran ?>
    <div id="r_tahun_anggaran" class="form-group row">
        <label id="elh_pertanggungjawaban_tahun_anggaran" for="x_tahun_anggaran" class="<?= $Page->LeftColumnClass ?>"><?= $Page->tahun_anggaran->caption() ?><?= $Page->tahun_anggaran->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->tahun_anggaran->cellAttributes() ?>>
<span id="el_pertanggungjawaban_tahun_anggaran">
    <select
        id="x_tahun_anggaran"
        name="x_tahun_anggaran"
        class="form-control ew-select<?= $Page->tahun_anggaran->isInvalidClass() ?>"
        data-select2-id="pertanggungjawaban_x_tahun_anggaran"
        data-table="pertanggungjawaban"
        data-field="x_tahun_anggaran"
        data-value-separator="<?= $Page->tahun_anggaran->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->tahun_anggaran->getPlaceHolder()) ?>"
        <?= $Page->tahun_anggaran->editAttributes() ?>>
        <?= $Page->tahun_anggaran->selectOptionListHtml("x_tahun_anggaran") ?>
    </select>
    <?= $Page->tahun_anggaran->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->tahun_anggaran->getErrorMessage() ?></div>
<?= $Page->tahun_anggaran->Lookup->getParamTag($Page, "p_x_tahun_anggaran") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='pertanggungjawaban_x_tahun_anggaran']"),
        options = { name: "x_tahun_anggaran", selectId: "pertanggungjawaban_x_tahun_anggaran", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.pertanggungjawaban.fields.tahun_anggaran.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->surat_pengantar->Visible) { // surat_pengantar ?>
    <div id="r_surat_pengantar" class="form-group row">
        <label id="elh_pertanggungjawaban_surat_pengantar" class="<?= $Page->LeftColumnClass ?>"><?= $Page->surat_pengantar->caption() ?><?= $Page->surat_pengantar->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->surat_pengantar->cellAttributes() ?>>
<span id="el_pertanggungjawaban_surat_pengantar">
<div id="fd_x_surat_pengantar">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Page->surat_pengantar->title() ?>" data-table="pertanggungjawaban" data-field="x_surat_pengantar" name="x_surat_pengantar" id="x_surat_pengantar" lang="<?= CurrentLanguageID() ?>"<?= $Page->surat_pengantar->editAttributes() ?><?= ($Page->surat_pengantar->ReadOnly || $Page->surat_pengantar->Disabled) ? " disabled" : "" ?> aria-describedby="x_surat_pengantar_help">
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
<?php if ($Page->skd_rqanunpert->Visible) { // skd_rqanunpert ?>
    <div id="r_skd_rqanunpert" class="form-group row">
        <label id="elh_pertanggungjawaban_skd_rqanunpert" class="<?= $Page->LeftColumnClass ?>"><?= $Page->skd_rqanunpert->caption() ?><?= $Page->skd_rqanunpert->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->skd_rqanunpert->cellAttributes() ?>>
<span id="el_pertanggungjawaban_skd_rqanunpert">
<div id="fd_x_skd_rqanunpert">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Page->skd_rqanunpert->title() ?>" data-table="pertanggungjawaban" data-field="x_skd_rqanunpert" name="x_skd_rqanunpert" id="x_skd_rqanunpert" lang="<?= CurrentLanguageID() ?>"<?= $Page->skd_rqanunpert->editAttributes() ?><?= ($Page->skd_rqanunpert->ReadOnly || $Page->skd_rqanunpert->Disabled) ? " disabled" : "" ?> aria-describedby="x_skd_rqanunpert_help">
        <label class="custom-file-label ew-file-label" for="x_skd_rqanunpert"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<?= $Page->skd_rqanunpert->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->skd_rqanunpert->getErrorMessage() ?></div>
<input type="hidden" name="fn_x_skd_rqanunpert" id= "fn_x_skd_rqanunpert" value="<?= $Page->skd_rqanunpert->Upload->FileName ?>">
<input type="hidden" name="fa_x_skd_rqanunpert" id= "fa_x_skd_rqanunpert" value="<?= (Post("fa_x_skd_rqanunpert") == "0") ? "0" : "1" ?>">
<input type="hidden" name="fs_x_skd_rqanunpert" id= "fs_x_skd_rqanunpert" value="200">
<input type="hidden" name="fx_x_skd_rqanunpert" id= "fx_x_skd_rqanunpert" value="<?= $Page->skd_rqanunpert->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_skd_rqanunpert" id= "fm_x_skd_rqanunpert" value="<?= $Page->skd_rqanunpert->UploadMaxFileSize ?>">
</div>
<table id="ft_x_skd_rqanunpert" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->rq_apbkpert->Visible) { // rq_apbkpert ?>
    <div id="r_rq_apbkpert" class="form-group row">
        <label id="elh_pertanggungjawaban_rq_apbkpert" class="<?= $Page->LeftColumnClass ?>"><?= $Page->rq_apbkpert->caption() ?><?= $Page->rq_apbkpert->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->rq_apbkpert->cellAttributes() ?>>
<span id="el_pertanggungjawaban_rq_apbkpert">
<div id="fd_x_rq_apbkpert">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Page->rq_apbkpert->title() ?>" data-table="pertanggungjawaban" data-field="x_rq_apbkpert" name="x_rq_apbkpert" id="x_rq_apbkpert" lang="<?= CurrentLanguageID() ?>"<?= $Page->rq_apbkpert->editAttributes() ?><?= ($Page->rq_apbkpert->ReadOnly || $Page->rq_apbkpert->Disabled) ? " disabled" : "" ?> aria-describedby="x_rq_apbkpert_help">
        <label class="custom-file-label ew-file-label" for="x_rq_apbkpert"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<?= $Page->rq_apbkpert->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->rq_apbkpert->getErrorMessage() ?></div>
<input type="hidden" name="fn_x_rq_apbkpert" id= "fn_x_rq_apbkpert" value="<?= $Page->rq_apbkpert->Upload->FileName ?>">
<input type="hidden" name="fa_x_rq_apbkpert" id= "fa_x_rq_apbkpert" value="<?= (Post("fa_x_rq_apbkpert") == "0") ? "0" : "1" ?>">
<input type="hidden" name="fs_x_rq_apbkpert" id= "fs_x_rq_apbkpert" value="200">
<input type="hidden" name="fx_x_rq_apbkpert" id= "fx_x_rq_apbkpert" value="<?= $Page->rq_apbkpert->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_rq_apbkpert" id= "fm_x_rq_apbkpert" value="<?= $Page->rq_apbkpert->UploadMaxFileSize ?>">
</div>
<table id="ft_x_rq_apbkpert" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->bap_apbkpert->Visible) { // bap_apbkpert ?>
    <div id="r_bap_apbkpert" class="form-group row">
        <label id="elh_pertanggungjawaban_bap_apbkpert" class="<?= $Page->LeftColumnClass ?>"><?= $Page->bap_apbkpert->caption() ?><?= $Page->bap_apbkpert->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->bap_apbkpert->cellAttributes() ?>>
<span id="el_pertanggungjawaban_bap_apbkpert">
<div id="fd_x_bap_apbkpert">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Page->bap_apbkpert->title() ?>" data-table="pertanggungjawaban" data-field="x_bap_apbkpert" name="x_bap_apbkpert" id="x_bap_apbkpert" lang="<?= CurrentLanguageID() ?>"<?= $Page->bap_apbkpert->editAttributes() ?><?= ($Page->bap_apbkpert->ReadOnly || $Page->bap_apbkpert->Disabled) ? " disabled" : "" ?> aria-describedby="x_bap_apbkpert_help">
        <label class="custom-file-label ew-file-label" for="x_bap_apbkpert"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<?= $Page->bap_apbkpert->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->bap_apbkpert->getErrorMessage() ?></div>
<input type="hidden" name="fn_x_bap_apbkpert" id= "fn_x_bap_apbkpert" value="<?= $Page->bap_apbkpert->Upload->FileName ?>">
<input type="hidden" name="fa_x_bap_apbkpert" id= "fa_x_bap_apbkpert" value="<?= (Post("fa_x_bap_apbkpert") == "0") ? "0" : "1" ?>">
<input type="hidden" name="fs_x_bap_apbkpert" id= "fs_x_bap_apbkpert" value="200">
<input type="hidden" name="fx_x_bap_apbkpert" id= "fx_x_bap_apbkpert" value="<?= $Page->bap_apbkpert->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_bap_apbkpert" id= "fm_x_bap_apbkpert" value="<?= $Page->bap_apbkpert->UploadMaxFileSize ?>">
</div>
<table id="ft_x_bap_apbkpert" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->risalah_sidang->Visible) { // risalah_sidang ?>
    <div id="r_risalah_sidang" class="form-group row">
        <label id="elh_pertanggungjawaban_risalah_sidang" class="<?= $Page->LeftColumnClass ?>"><?= $Page->risalah_sidang->caption() ?><?= $Page->risalah_sidang->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->risalah_sidang->cellAttributes() ?>>
<span id="el_pertanggungjawaban_risalah_sidang">
<div id="fd_x_risalah_sidang">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Page->risalah_sidang->title() ?>" data-table="pertanggungjawaban" data-field="x_risalah_sidang" name="x_risalah_sidang" id="x_risalah_sidang" lang="<?= CurrentLanguageID() ?>"<?= $Page->risalah_sidang->editAttributes() ?><?= ($Page->risalah_sidang->ReadOnly || $Page->risalah_sidang->Disabled) ? " disabled" : "" ?> aria-describedby="x_risalah_sidang_help">
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
<?php if ($Page->absen_peserta->Visible) { // absen_peserta ?>
    <div id="r_absen_peserta" class="form-group row">
        <label id="elh_pertanggungjawaban_absen_peserta" class="<?= $Page->LeftColumnClass ?>"><?= $Page->absen_peserta->caption() ?><?= $Page->absen_peserta->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->absen_peserta->cellAttributes() ?>>
<span id="el_pertanggungjawaban_absen_peserta">
<div id="fd_x_absen_peserta">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Page->absen_peserta->title() ?>" data-table="pertanggungjawaban" data-field="x_absen_peserta" name="x_absen_peserta" id="x_absen_peserta" lang="<?= CurrentLanguageID() ?>"<?= $Page->absen_peserta->editAttributes() ?><?= ($Page->absen_peserta->ReadOnly || $Page->absen_peserta->Disabled) ? " disabled" : "" ?> aria-describedby="x_absen_peserta_help">
        <label class="custom-file-label ew-file-label" for="x_absen_peserta"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<?= $Page->absen_peserta->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->absen_peserta->getErrorMessage() ?></div>
<input type="hidden" name="fn_x_absen_peserta" id= "fn_x_absen_peserta" value="<?= $Page->absen_peserta->Upload->FileName ?>">
<input type="hidden" name="fa_x_absen_peserta" id= "fa_x_absen_peserta" value="<?= (Post("fa_x_absen_peserta") == "0") ? "0" : "1" ?>">
<input type="hidden" name="fs_x_absen_peserta" id= "fs_x_absen_peserta" value="200">
<input type="hidden" name="fx_x_absen_peserta" id= "fx_x_absen_peserta" value="<?= $Page->absen_peserta->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_absen_peserta" id= "fm_x_absen_peserta" value="<?= $Page->absen_peserta->UploadMaxFileSize ?>">
</div>
<table id="ft_x_absen_peserta" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->neraca->Visible) { // neraca ?>
    <div id="r_neraca" class="form-group row">
        <label id="elh_pertanggungjawaban_neraca" class="<?= $Page->LeftColumnClass ?>"><?= $Page->neraca->caption() ?><?= $Page->neraca->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->neraca->cellAttributes() ?>>
<span id="el_pertanggungjawaban_neraca">
<div id="fd_x_neraca">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Page->neraca->title() ?>" data-table="pertanggungjawaban" data-field="x_neraca" name="x_neraca" id="x_neraca" lang="<?= CurrentLanguageID() ?>"<?= $Page->neraca->editAttributes() ?><?= ($Page->neraca->ReadOnly || $Page->neraca->Disabled) ? " disabled" : "" ?> aria-describedby="x_neraca_help">
        <label class="custom-file-label ew-file-label" for="x_neraca"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<?= $Page->neraca->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->neraca->getErrorMessage() ?></div>
<input type="hidden" name="fn_x_neraca" id= "fn_x_neraca" value="<?= $Page->neraca->Upload->FileName ?>">
<input type="hidden" name="fa_x_neraca" id= "fa_x_neraca" value="<?= (Post("fa_x_neraca") == "0") ? "0" : "1" ?>">
<input type="hidden" name="fs_x_neraca" id= "fs_x_neraca" value="200">
<input type="hidden" name="fx_x_neraca" id= "fx_x_neraca" value="<?= $Page->neraca->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_neraca" id= "fm_x_neraca" value="<?= $Page->neraca->UploadMaxFileSize ?>">
</div>
<table id="ft_x_neraca" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->lra->Visible) { // lra ?>
    <div id="r_lra" class="form-group row">
        <label id="elh_pertanggungjawaban_lra" class="<?= $Page->LeftColumnClass ?>"><?= $Page->lra->caption() ?><?= $Page->lra->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->lra->cellAttributes() ?>>
<span id="el_pertanggungjawaban_lra">
<div id="fd_x_lra">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Page->lra->title() ?>" data-table="pertanggungjawaban" data-field="x_lra" name="x_lra" id="x_lra" lang="<?= CurrentLanguageID() ?>"<?= $Page->lra->editAttributes() ?><?= ($Page->lra->ReadOnly || $Page->lra->Disabled) ? " disabled" : "" ?> aria-describedby="x_lra_help">
        <label class="custom-file-label ew-file-label" for="x_lra"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<?= $Page->lra->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->lra->getErrorMessage() ?></div>
<input type="hidden" name="fn_x_lra" id= "fn_x_lra" value="<?= $Page->lra->Upload->FileName ?>">
<input type="hidden" name="fa_x_lra" id= "fa_x_lra" value="<?= (Post("fa_x_lra") == "0") ? "0" : "1" ?>">
<input type="hidden" name="fs_x_lra" id= "fs_x_lra" value="200">
<input type="hidden" name="fx_x_lra" id= "fx_x_lra" value="<?= $Page->lra->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_lra" id= "fm_x_lra" value="<?= $Page->lra->UploadMaxFileSize ?>">
</div>
<table id="ft_x_lra" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->calk->Visible) { // calk ?>
    <div id="r_calk" class="form-group row">
        <label id="elh_pertanggungjawaban_calk" class="<?= $Page->LeftColumnClass ?>"><?= $Page->calk->caption() ?><?= $Page->calk->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->calk->cellAttributes() ?>>
<span id="el_pertanggungjawaban_calk">
<div id="fd_x_calk">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Page->calk->title() ?>" data-table="pertanggungjawaban" data-field="x_calk" name="x_calk" id="x_calk" lang="<?= CurrentLanguageID() ?>"<?= $Page->calk->editAttributes() ?><?= ($Page->calk->ReadOnly || $Page->calk->Disabled) ? " disabled" : "" ?> aria-describedby="x_calk_help">
        <label class="custom-file-label ew-file-label" for="x_calk"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<?= $Page->calk->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->calk->getErrorMessage() ?></div>
<input type="hidden" name="fn_x_calk" id= "fn_x_calk" value="<?= $Page->calk->Upload->FileName ?>">
<input type="hidden" name="fa_x_calk" id= "fa_x_calk" value="<?= (Post("fa_x_calk") == "0") ? "0" : "1" ?>">
<input type="hidden" name="fs_x_calk" id= "fs_x_calk" value="200">
<input type="hidden" name="fx_x_calk" id= "fx_x_calk" value="<?= $Page->calk->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_calk" id= "fm_x_calk" value="<?= $Page->calk->UploadMaxFileSize ?>">
</div>
<table id="ft_x_calk" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->lo->Visible) { // lo ?>
    <div id="r_lo" class="form-group row">
        <label id="elh_pertanggungjawaban_lo" class="<?= $Page->LeftColumnClass ?>"><?= $Page->lo->caption() ?><?= $Page->lo->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->lo->cellAttributes() ?>>
<span id="el_pertanggungjawaban_lo">
<div id="fd_x_lo">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Page->lo->title() ?>" data-table="pertanggungjawaban" data-field="x_lo" name="x_lo" id="x_lo" lang="<?= CurrentLanguageID() ?>"<?= $Page->lo->editAttributes() ?><?= ($Page->lo->ReadOnly || $Page->lo->Disabled) ? " disabled" : "" ?> aria-describedby="x_lo_help">
        <label class="custom-file-label ew-file-label" for="x_lo"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<?= $Page->lo->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->lo->getErrorMessage() ?></div>
<input type="hidden" name="fn_x_lo" id= "fn_x_lo" value="<?= $Page->lo->Upload->FileName ?>">
<input type="hidden" name="fa_x_lo" id= "fa_x_lo" value="<?= (Post("fa_x_lo") == "0") ? "0" : "1" ?>">
<input type="hidden" name="fs_x_lo" id= "fs_x_lo" value="200">
<input type="hidden" name="fx_x_lo" id= "fx_x_lo" value="<?= $Page->lo->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_lo" id= "fm_x_lo" value="<?= $Page->lo->UploadMaxFileSize ?>">
</div>
<table id="ft_x_lo" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->lpe->Visible) { // lpe ?>
    <div id="r_lpe" class="form-group row">
        <label id="elh_pertanggungjawaban_lpe" class="<?= $Page->LeftColumnClass ?>"><?= $Page->lpe->caption() ?><?= $Page->lpe->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->lpe->cellAttributes() ?>>
<span id="el_pertanggungjawaban_lpe">
<div id="fd_x_lpe">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Page->lpe->title() ?>" data-table="pertanggungjawaban" data-field="x_lpe" name="x_lpe" id="x_lpe" lang="<?= CurrentLanguageID() ?>"<?= $Page->lpe->editAttributes() ?><?= ($Page->lpe->ReadOnly || $Page->lpe->Disabled) ? " disabled" : "" ?> aria-describedby="x_lpe_help">
        <label class="custom-file-label ew-file-label" for="x_lpe"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<?= $Page->lpe->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->lpe->getErrorMessage() ?></div>
<input type="hidden" name="fn_x_lpe" id= "fn_x_lpe" value="<?= $Page->lpe->Upload->FileName ?>">
<input type="hidden" name="fa_x_lpe" id= "fa_x_lpe" value="<?= (Post("fa_x_lpe") == "0") ? "0" : "1" ?>">
<input type="hidden" name="fs_x_lpe" id= "fs_x_lpe" value="200">
<input type="hidden" name="fx_x_lpe" id= "fx_x_lpe" value="<?= $Page->lpe->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_lpe" id= "fm_x_lpe" value="<?= $Page->lpe->UploadMaxFileSize ?>">
</div>
<table id="ft_x_lpe" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->lpsal->Visible) { // lpsal ?>
    <div id="r_lpsal" class="form-group row">
        <label id="elh_pertanggungjawaban_lpsal" class="<?= $Page->LeftColumnClass ?>"><?= $Page->lpsal->caption() ?><?= $Page->lpsal->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->lpsal->cellAttributes() ?>>
<span id="el_pertanggungjawaban_lpsal">
<div id="fd_x_lpsal">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Page->lpsal->title() ?>" data-table="pertanggungjawaban" data-field="x_lpsal" name="x_lpsal" id="x_lpsal" lang="<?= CurrentLanguageID() ?>"<?= $Page->lpsal->editAttributes() ?><?= ($Page->lpsal->ReadOnly || $Page->lpsal->Disabled) ? " disabled" : "" ?> aria-describedby="x_lpsal_help">
        <label class="custom-file-label ew-file-label" for="x_lpsal"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<?= $Page->lpsal->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->lpsal->getErrorMessage() ?></div>
<input type="hidden" name="fn_x_lpsal" id= "fn_x_lpsal" value="<?= $Page->lpsal->Upload->FileName ?>">
<input type="hidden" name="fa_x_lpsal" id= "fa_x_lpsal" value="<?= (Post("fa_x_lpsal") == "0") ? "0" : "1" ?>">
<input type="hidden" name="fs_x_lpsal" id= "fs_x_lpsal" value="200">
<input type="hidden" name="fx_x_lpsal" id= "fx_x_lpsal" value="<?= $Page->lpsal->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_lpsal" id= "fm_x_lpsal" value="<?= $Page->lpsal->UploadMaxFileSize ?>">
</div>
<table id="ft_x_lpsal" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->lak->Visible) { // lak ?>
    <div id="r_lak" class="form-group row">
        <label id="elh_pertanggungjawaban_lak" class="<?= $Page->LeftColumnClass ?>"><?= $Page->lak->caption() ?><?= $Page->lak->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->lak->cellAttributes() ?>>
<span id="el_pertanggungjawaban_lak">
<div id="fd_x_lak">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Page->lak->title() ?>" data-table="pertanggungjawaban" data-field="x_lak" name="x_lak" id="x_lak" lang="<?= CurrentLanguageID() ?>"<?= $Page->lak->editAttributes() ?><?= ($Page->lak->ReadOnly || $Page->lak->Disabled) ? " disabled" : "" ?> aria-describedby="x_lak_help">
        <label class="custom-file-label ew-file-label" for="x_lak"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<?= $Page->lak->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->lak->getErrorMessage() ?></div>
<input type="hidden" name="fn_x_lak" id= "fn_x_lak" value="<?= $Page->lak->Upload->FileName ?>">
<input type="hidden" name="fa_x_lak" id= "fa_x_lak" value="<?= (Post("fa_x_lak") == "0") ? "0" : "1" ?>">
<input type="hidden" name="fs_x_lak" id= "fs_x_lak" value="200">
<input type="hidden" name="fx_x_lak" id= "fx_x_lak" value="<?= $Page->lak->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_lak" id= "fm_x_lak" value="<?= $Page->lak->UploadMaxFileSize ?>">
</div>
<table id="ft_x_lak" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->laporan_pemeriksaan->Visible) { // laporan_pemeriksaan ?>
    <div id="r_laporan_pemeriksaan" class="form-group row">
        <label id="elh_pertanggungjawaban_laporan_pemeriksaan" class="<?= $Page->LeftColumnClass ?>"><?= $Page->laporan_pemeriksaan->caption() ?><?= $Page->laporan_pemeriksaan->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->laporan_pemeriksaan->cellAttributes() ?>>
<span id="el_pertanggungjawaban_laporan_pemeriksaan">
<div id="fd_x_laporan_pemeriksaan">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Page->laporan_pemeriksaan->title() ?>" data-table="pertanggungjawaban" data-field="x_laporan_pemeriksaan" name="x_laporan_pemeriksaan" id="x_laporan_pemeriksaan" lang="<?= CurrentLanguageID() ?>"<?= $Page->laporan_pemeriksaan->editAttributes() ?><?= ($Page->laporan_pemeriksaan->ReadOnly || $Page->laporan_pemeriksaan->Disabled) ? " disabled" : "" ?> aria-describedby="x_laporan_pemeriksaan_help">
        <label class="custom-file-label ew-file-label" for="x_laporan_pemeriksaan"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<?= $Page->laporan_pemeriksaan->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->laporan_pemeriksaan->getErrorMessage() ?></div>
<input type="hidden" name="fn_x_laporan_pemeriksaan" id= "fn_x_laporan_pemeriksaan" value="<?= $Page->laporan_pemeriksaan->Upload->FileName ?>">
<input type="hidden" name="fa_x_laporan_pemeriksaan" id= "fa_x_laporan_pemeriksaan" value="<?= (Post("fa_x_laporan_pemeriksaan") == "0") ? "0" : "1" ?>">
<input type="hidden" name="fs_x_laporan_pemeriksaan" id= "fs_x_laporan_pemeriksaan" value="200">
<input type="hidden" name="fx_x_laporan_pemeriksaan" id= "fx_x_laporan_pemeriksaan" value="<?= $Page->laporan_pemeriksaan->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_laporan_pemeriksaan" id= "fm_x_laporan_pemeriksaan" value="<?= $Page->laporan_pemeriksaan->UploadMaxFileSize ?>">
</div>
<table id="ft_x_laporan_pemeriksaan" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->softcopy_rqanun->Visible) { // softcopy_rqanun ?>
    <div id="r_softcopy_rqanun" class="form-group row">
        <label id="elh_pertanggungjawaban_softcopy_rqanun" class="<?= $Page->LeftColumnClass ?>"><?= $Page->softcopy_rqanun->caption() ?><?= $Page->softcopy_rqanun->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->softcopy_rqanun->cellAttributes() ?>>
<span id="el_pertanggungjawaban_softcopy_rqanun">
<div id="fd_x_softcopy_rqanun">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Page->softcopy_rqanun->title() ?>" data-table="pertanggungjawaban" data-field="x_softcopy_rqanun" name="x_softcopy_rqanun" id="x_softcopy_rqanun" lang="<?= CurrentLanguageID() ?>"<?= $Page->softcopy_rqanun->editAttributes() ?><?= ($Page->softcopy_rqanun->ReadOnly || $Page->softcopy_rqanun->Disabled) ? " disabled" : "" ?> aria-describedby="x_softcopy_rqanun_help">
        <label class="custom-file-label ew-file-label" for="x_softcopy_rqanun"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<?= $Page->softcopy_rqanun->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->softcopy_rqanun->getErrorMessage() ?></div>
<input type="hidden" name="fn_x_softcopy_rqanun" id= "fn_x_softcopy_rqanun" value="<?= $Page->softcopy_rqanun->Upload->FileName ?>">
<input type="hidden" name="fa_x_softcopy_rqanun" id= "fa_x_softcopy_rqanun" value="<?= (Post("fa_x_softcopy_rqanun") == "0") ? "0" : "1" ?>">
<input type="hidden" name="fs_x_softcopy_rqanun" id= "fs_x_softcopy_rqanun" value="200">
<input type="hidden" name="fx_x_softcopy_rqanun" id= "fx_x_softcopy_rqanun" value="<?= $Page->softcopy_rqanun->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_softcopy_rqanun" id= "fm_x_softcopy_rqanun" value="<?= $Page->softcopy_rqanun->UploadMaxFileSize ?>">
</div>
<table id="ft_x_softcopy_rqanun" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->status->Visible) { // status ?>
    <div id="r_status" class="form-group row">
        <label id="elh_pertanggungjawaban_status" for="x_status" class="<?= $Page->LeftColumnClass ?>"><?= $Page->status->caption() ?><?= $Page->status->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->status->cellAttributes() ?>>
<span id="el_pertanggungjawaban_status">
    <select
        id="x_status"
        name="x_status"
        class="form-control ew-select<?= $Page->status->isInvalidClass() ?>"
        data-select2-id="pertanggungjawaban_x_status"
        data-table="pertanggungjawaban"
        data-field="x_status"
        data-value-separator="<?= $Page->status->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->status->getPlaceHolder()) ?>"
        <?= $Page->status->editAttributes() ?>>
        <?= $Page->status->selectOptionListHtml("x_status") ?>
    </select>
    <?= $Page->status->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->status->getErrorMessage() ?></div>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='pertanggungjawaban_x_status']"),
        options = { name: "x_status", selectId: "pertanggungjawaban_x_status", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.data = ew.vars.tables.pertanggungjawaban.fields.status.lookupOptions;
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.pertanggungjawaban.fields.status.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->idd_user->Visible) { // idd_user ?>
    <div id="r_idd_user" class="form-group row">
        <label id="elh_pertanggungjawaban_idd_user" for="x_idd_user" class="<?= $Page->LeftColumnClass ?>"><?= $Page->idd_user->caption() ?><?= $Page->idd_user->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->idd_user->cellAttributes() ?>>
<?php if (!$Security->isAdmin() && $Security->isLoggedIn() && !$Page->userIDAllow("edit")) { // Non system admin ?>
<span id="el_pertanggungjawaban_idd_user">
<span<?= $Page->idd_user->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->idd_user->getDisplayValue($Page->idd_user->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="pertanggungjawaban" data-field="x_idd_user" data-hidden="1" name="x_idd_user" id="x_idd_user" value="<?= HtmlEncode($Page->idd_user->CurrentValue) ?>">
<?php } else { ?>
<span id="el_pertanggungjawaban_idd_user">
    <select
        id="x_idd_user"
        name="x_idd_user"
        class="form-control ew-select<?= $Page->idd_user->isInvalidClass() ?>"
        data-select2-id="pertanggungjawaban_x_idd_user"
        data-table="pertanggungjawaban"
        data-field="x_idd_user"
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
    var el = document.querySelector("select[data-select2-id='pertanggungjawaban_x_idd_user']"),
        options = { name: "x_idd_user", selectId: "pertanggungjawaban_x_idd_user", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.pertanggungjawaban.fields.idd_user.selectOptions);
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
    ew.addEventHandlers("pertanggungjawaban");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
