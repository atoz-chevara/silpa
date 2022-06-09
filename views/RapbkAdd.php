<?php

namespace PHPMaker2021\silpa;

// Page object
$RapbkAdd = &$Page;
?>
<script>
var currentForm, currentPageID;
var frapbkadd;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "add";
    frapbkadd = currentForm = new ew.Form("frapbkadd", "add");

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "rapbk")) ?>,
        fields = currentTable.fields;
    if (!ew.vars.tables.rapbk)
        ew.vars.tables.rapbk = currentTable;
    frapbkadd.addFields([
        ["tanggal", [fields.tanggal.visible && fields.tanggal.required ? ew.Validators.required(fields.tanggal.caption) : null, ew.Validators.datetime(0)], fields.tanggal.isInvalid],
        ["kd_satker", [fields.kd_satker.visible && fields.kd_satker.required ? ew.Validators.required(fields.kd_satker.caption) : null], fields.kd_satker.isInvalid],
        ["idd_tahapan", [fields.idd_tahapan.visible && fields.idd_tahapan.required ? ew.Validators.required(fields.idd_tahapan.caption) : null], fields.idd_tahapan.isInvalid],
        ["tahun_anggaran", [fields.tahun_anggaran.visible && fields.tahun_anggaran.required ? ew.Validators.required(fields.tahun_anggaran.caption) : null], fields.tahun_anggaran.isInvalid],
        ["idd_wilayah", [fields.idd_wilayah.visible && fields.idd_wilayah.required ? ew.Validators.required(fields.idd_wilayah.caption) : null, ew.Validators.integer], fields.idd_wilayah.isInvalid],
        ["file_01", [fields.file_01.visible && fields.file_01.required ? ew.Validators.fileRequired(fields.file_01.caption) : null], fields.file_01.isInvalid],
        ["file_02", [fields.file_02.visible && fields.file_02.required ? ew.Validators.fileRequired(fields.file_02.caption) : null], fields.file_02.isInvalid],
        ["file_03", [fields.file_03.visible && fields.file_03.required ? ew.Validators.fileRequired(fields.file_03.caption) : null], fields.file_03.isInvalid],
        ["file_04", [fields.file_04.visible && fields.file_04.required ? ew.Validators.fileRequired(fields.file_04.caption) : null], fields.file_04.isInvalid],
        ["file_05", [fields.file_05.visible && fields.file_05.required ? ew.Validators.fileRequired(fields.file_05.caption) : null], fields.file_05.isInvalid],
        ["file_06", [fields.file_06.visible && fields.file_06.required ? ew.Validators.fileRequired(fields.file_06.caption) : null], fields.file_06.isInvalid],
        ["file_07", [fields.file_07.visible && fields.file_07.required ? ew.Validators.fileRequired(fields.file_07.caption) : null], fields.file_07.isInvalid],
        ["file_08", [fields.file_08.visible && fields.file_08.required ? ew.Validators.fileRequired(fields.file_08.caption) : null], fields.file_08.isInvalid],
        ["file_09", [fields.file_09.visible && fields.file_09.required ? ew.Validators.fileRequired(fields.file_09.caption) : null], fields.file_09.isInvalid],
        ["file_10", [fields.file_10.visible && fields.file_10.required ? ew.Validators.fileRequired(fields.file_10.caption) : null], fields.file_10.isInvalid],
        ["file_11", [fields.file_11.visible && fields.file_11.required ? ew.Validators.fileRequired(fields.file_11.caption) : null], fields.file_11.isInvalid],
        ["file_12", [fields.file_12.visible && fields.file_12.required ? ew.Validators.fileRequired(fields.file_12.caption) : null], fields.file_12.isInvalid],
        ["file_13", [fields.file_13.visible && fields.file_13.required ? ew.Validators.fileRequired(fields.file_13.caption) : null], fields.file_13.isInvalid],
        ["file_14", [fields.file_14.visible && fields.file_14.required ? ew.Validators.fileRequired(fields.file_14.caption) : null], fields.file_14.isInvalid],
        ["file_15", [fields.file_15.visible && fields.file_15.required ? ew.Validators.fileRequired(fields.file_15.caption) : null], fields.file_15.isInvalid],
        ["file_16", [fields.file_16.visible && fields.file_16.required ? ew.Validators.fileRequired(fields.file_16.caption) : null], fields.file_16.isInvalid],
        ["file_17", [fields.file_17.visible && fields.file_17.required ? ew.Validators.fileRequired(fields.file_17.caption) : null], fields.file_17.isInvalid],
        ["file_18", [fields.file_18.visible && fields.file_18.required ? ew.Validators.fileRequired(fields.file_18.caption) : null], fields.file_18.isInvalid],
        ["file_19", [fields.file_19.visible && fields.file_19.required ? ew.Validators.fileRequired(fields.file_19.caption) : null], fields.file_19.isInvalid],
        ["file_20", [fields.file_20.visible && fields.file_20.required ? ew.Validators.fileRequired(fields.file_20.caption) : null], fields.file_20.isInvalid],
        ["file_21", [fields.file_21.visible && fields.file_21.required ? ew.Validators.fileRequired(fields.file_21.caption) : null], fields.file_21.isInvalid],
        ["file_22", [fields.file_22.visible && fields.file_22.required ? ew.Validators.fileRequired(fields.file_22.caption) : null], fields.file_22.isInvalid],
        ["file_23", [fields.file_23.visible && fields.file_23.required ? ew.Validators.fileRequired(fields.file_23.caption) : null], fields.file_23.isInvalid],
        ["file_24", [fields.file_24.visible && fields.file_24.required ? ew.Validators.fileRequired(fields.file_24.caption) : null], fields.file_24.isInvalid],
        ["status", [fields.status.visible && fields.status.required ? ew.Validators.required(fields.status.caption) : null], fields.status.isInvalid],
        ["idd_user", [fields.idd_user.visible && fields.idd_user.required ? ew.Validators.required(fields.idd_user.caption) : null], fields.idd_user.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = frapbkadd,
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
    frapbkadd.validate = function () {
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
    frapbkadd.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    frapbkadd.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    frapbkadd.lists.kd_satker = <?= $Page->kd_satker->toClientList($Page) ?>;
    frapbkadd.lists.idd_tahapan = <?= $Page->idd_tahapan->toClientList($Page) ?>;
    frapbkadd.lists.tahun_anggaran = <?= $Page->tahun_anggaran->toClientList($Page) ?>;
    frapbkadd.lists.status = <?= $Page->status->toClientList($Page) ?>;
    frapbkadd.lists.idd_user = <?= $Page->idd_user->toClientList($Page) ?>;
    loadjs.done("frapbkadd");
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
<form name="frapbkadd" id="frapbkadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="rapbk">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->tanggal->Visible) { // tanggal ?>
    <div id="r_tanggal" class="form-group row">
        <label id="elh_rapbk_tanggal" for="x_tanggal" class="<?= $Page->LeftColumnClass ?>"><?= $Page->tanggal->caption() ?><?= $Page->tanggal->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->tanggal->cellAttributes() ?>>
<span id="el_rapbk_tanggal">
<input type="<?= $Page->tanggal->getInputTextType() ?>" data-table="rapbk" data-field="x_tanggal" name="x_tanggal" id="x_tanggal" placeholder="<?= HtmlEncode($Page->tanggal->getPlaceHolder()) ?>" value="<?= $Page->tanggal->EditValue ?>"<?= $Page->tanggal->editAttributes() ?> aria-describedby="x_tanggal_help">
<?= $Page->tanggal->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->tanggal->getErrorMessage() ?></div>
<?php if (!$Page->tanggal->ReadOnly && !$Page->tanggal->Disabled && !isset($Page->tanggal->EditAttrs["readonly"]) && !isset($Page->tanggal->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["frapbkadd", "datetimepicker"], function() {
    ew.createDateTimePicker("frapbkadd", "x_tanggal", {"ignoreReadonly":true,"useCurrent":false,"format":0});
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->kd_satker->Visible) { // kd_satker ?>
    <div id="r_kd_satker" class="form-group row">
        <label id="elh_rapbk_kd_satker" for="x_kd_satker" class="<?= $Page->LeftColumnClass ?>"><?= $Page->kd_satker->caption() ?><?= $Page->kd_satker->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->kd_satker->cellAttributes() ?>>
<span id="el_rapbk_kd_satker">
    <select
        id="x_kd_satker"
        name="x_kd_satker"
        class="form-control ew-select<?= $Page->kd_satker->isInvalidClass() ?>"
        data-select2-id="rapbk_x_kd_satker"
        data-table="rapbk"
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
    var el = document.querySelector("select[data-select2-id='rapbk_x_kd_satker']"),
        options = { name: "x_kd_satker", selectId: "rapbk_x_kd_satker", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.rapbk.fields.kd_satker.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->idd_tahapan->Visible) { // idd_tahapan ?>
    <div id="r_idd_tahapan" class="form-group row">
        <label id="elh_rapbk_idd_tahapan" for="x_idd_tahapan" class="<?= $Page->LeftColumnClass ?>"><?= $Page->idd_tahapan->caption() ?><?= $Page->idd_tahapan->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->idd_tahapan->cellAttributes() ?>>
<span id="el_rapbk_idd_tahapan">
    <select
        id="x_idd_tahapan"
        name="x_idd_tahapan"
        class="form-control ew-select<?= $Page->idd_tahapan->isInvalidClass() ?>"
        data-select2-id="rapbk_x_idd_tahapan"
        data-table="rapbk"
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
    var el = document.querySelector("select[data-select2-id='rapbk_x_idd_tahapan']"),
        options = { name: "x_idd_tahapan", selectId: "rapbk_x_idd_tahapan", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.rapbk.fields.idd_tahapan.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->tahun_anggaran->Visible) { // tahun_anggaran ?>
    <div id="r_tahun_anggaran" class="form-group row">
        <label id="elh_rapbk_tahun_anggaran" for="x_tahun_anggaran" class="<?= $Page->LeftColumnClass ?>"><?= $Page->tahun_anggaran->caption() ?><?= $Page->tahun_anggaran->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->tahun_anggaran->cellAttributes() ?>>
<span id="el_rapbk_tahun_anggaran">
    <select
        id="x_tahun_anggaran"
        name="x_tahun_anggaran"
        class="form-control ew-select<?= $Page->tahun_anggaran->isInvalidClass() ?>"
        data-select2-id="rapbk_x_tahun_anggaran"
        data-table="rapbk"
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
    var el = document.querySelector("select[data-select2-id='rapbk_x_tahun_anggaran']"),
        options = { name: "x_tahun_anggaran", selectId: "rapbk_x_tahun_anggaran", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.rapbk.fields.tahun_anggaran.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->idd_wilayah->Visible) { // idd_wilayah ?>
    <div id="r_idd_wilayah" class="form-group row">
        <label id="elh_rapbk_idd_wilayah" for="x_idd_wilayah" class="<?= $Page->LeftColumnClass ?>"><?= $Page->idd_wilayah->caption() ?><?= $Page->idd_wilayah->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->idd_wilayah->cellAttributes() ?>>
<span id="el_rapbk_idd_wilayah">
<input type="<?= $Page->idd_wilayah->getInputTextType() ?>" data-table="rapbk" data-field="x_idd_wilayah" name="x_idd_wilayah" id="x_idd_wilayah" size="30" placeholder="<?= HtmlEncode($Page->idd_wilayah->getPlaceHolder()) ?>" value="<?= $Page->idd_wilayah->EditValue ?>"<?= $Page->idd_wilayah->editAttributes() ?> aria-describedby="x_idd_wilayah_help">
<?= $Page->idd_wilayah->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->idd_wilayah->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->file_01->Visible) { // file_01 ?>
    <div id="r_file_01" class="form-group row">
        <label id="elh_rapbk_file_01" class="<?= $Page->LeftColumnClass ?>"><?= $Page->file_01->caption() ?><?= $Page->file_01->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->file_01->cellAttributes() ?>>
<span id="el_rapbk_file_01">
<div id="fd_x_file_01">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Page->file_01->title() ?>" data-table="rapbk" data-field="x_file_01" name="x_file_01" id="x_file_01" lang="<?= CurrentLanguageID() ?>"<?= $Page->file_01->editAttributes() ?><?= ($Page->file_01->ReadOnly || $Page->file_01->Disabled) ? " disabled" : "" ?> aria-describedby="x_file_01_help">
        <label class="custom-file-label ew-file-label" for="x_file_01"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<?= $Page->file_01->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->file_01->getErrorMessage() ?></div>
<input type="hidden" name="fn_x_file_01" id= "fn_x_file_01" value="<?= $Page->file_01->Upload->FileName ?>">
<input type="hidden" name="fa_x_file_01" id= "fa_x_file_01" value="0">
<input type="hidden" name="fs_x_file_01" id= "fs_x_file_01" value="200">
<input type="hidden" name="fx_x_file_01" id= "fx_x_file_01" value="<?= $Page->file_01->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_file_01" id= "fm_x_file_01" value="<?= $Page->file_01->UploadMaxFileSize ?>">
</div>
<table id="ft_x_file_01" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->file_02->Visible) { // file_02 ?>
    <div id="r_file_02" class="form-group row">
        <label id="elh_rapbk_file_02" class="<?= $Page->LeftColumnClass ?>"><?= $Page->file_02->caption() ?><?= $Page->file_02->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->file_02->cellAttributes() ?>>
<span id="el_rapbk_file_02">
<div id="fd_x_file_02">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Page->file_02->title() ?>" data-table="rapbk" data-field="x_file_02" name="x_file_02" id="x_file_02" lang="<?= CurrentLanguageID() ?>"<?= $Page->file_02->editAttributes() ?><?= ($Page->file_02->ReadOnly || $Page->file_02->Disabled) ? " disabled" : "" ?> aria-describedby="x_file_02_help">
        <label class="custom-file-label ew-file-label" for="x_file_02"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<?= $Page->file_02->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->file_02->getErrorMessage() ?></div>
<input type="hidden" name="fn_x_file_02" id= "fn_x_file_02" value="<?= $Page->file_02->Upload->FileName ?>">
<input type="hidden" name="fa_x_file_02" id= "fa_x_file_02" value="0">
<input type="hidden" name="fs_x_file_02" id= "fs_x_file_02" value="200">
<input type="hidden" name="fx_x_file_02" id= "fx_x_file_02" value="<?= $Page->file_02->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_file_02" id= "fm_x_file_02" value="<?= $Page->file_02->UploadMaxFileSize ?>">
</div>
<table id="ft_x_file_02" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->file_03->Visible) { // file_03 ?>
    <div id="r_file_03" class="form-group row">
        <label id="elh_rapbk_file_03" class="<?= $Page->LeftColumnClass ?>"><?= $Page->file_03->caption() ?><?= $Page->file_03->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->file_03->cellAttributes() ?>>
<span id="el_rapbk_file_03">
<div id="fd_x_file_03">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Page->file_03->title() ?>" data-table="rapbk" data-field="x_file_03" name="x_file_03" id="x_file_03" lang="<?= CurrentLanguageID() ?>"<?= $Page->file_03->editAttributes() ?><?= ($Page->file_03->ReadOnly || $Page->file_03->Disabled) ? " disabled" : "" ?> aria-describedby="x_file_03_help">
        <label class="custom-file-label ew-file-label" for="x_file_03"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<?= $Page->file_03->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->file_03->getErrorMessage() ?></div>
<input type="hidden" name="fn_x_file_03" id= "fn_x_file_03" value="<?= $Page->file_03->Upload->FileName ?>">
<input type="hidden" name="fa_x_file_03" id= "fa_x_file_03" value="0">
<input type="hidden" name="fs_x_file_03" id= "fs_x_file_03" value="200">
<input type="hidden" name="fx_x_file_03" id= "fx_x_file_03" value="<?= $Page->file_03->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_file_03" id= "fm_x_file_03" value="<?= $Page->file_03->UploadMaxFileSize ?>">
</div>
<table id="ft_x_file_03" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->file_04->Visible) { // file_04 ?>
    <div id="r_file_04" class="form-group row">
        <label id="elh_rapbk_file_04" class="<?= $Page->LeftColumnClass ?>"><?= $Page->file_04->caption() ?><?= $Page->file_04->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->file_04->cellAttributes() ?>>
<span id="el_rapbk_file_04">
<div id="fd_x_file_04">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Page->file_04->title() ?>" data-table="rapbk" data-field="x_file_04" name="x_file_04" id="x_file_04" lang="<?= CurrentLanguageID() ?>"<?= $Page->file_04->editAttributes() ?><?= ($Page->file_04->ReadOnly || $Page->file_04->Disabled) ? " disabled" : "" ?> aria-describedby="x_file_04_help">
        <label class="custom-file-label ew-file-label" for="x_file_04"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<?= $Page->file_04->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->file_04->getErrorMessage() ?></div>
<input type="hidden" name="fn_x_file_04" id= "fn_x_file_04" value="<?= $Page->file_04->Upload->FileName ?>">
<input type="hidden" name="fa_x_file_04" id= "fa_x_file_04" value="0">
<input type="hidden" name="fs_x_file_04" id= "fs_x_file_04" value="200">
<input type="hidden" name="fx_x_file_04" id= "fx_x_file_04" value="<?= $Page->file_04->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_file_04" id= "fm_x_file_04" value="<?= $Page->file_04->UploadMaxFileSize ?>">
</div>
<table id="ft_x_file_04" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->file_05->Visible) { // file_05 ?>
    <div id="r_file_05" class="form-group row">
        <label id="elh_rapbk_file_05" class="<?= $Page->LeftColumnClass ?>"><?= $Page->file_05->caption() ?><?= $Page->file_05->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->file_05->cellAttributes() ?>>
<span id="el_rapbk_file_05">
<div id="fd_x_file_05">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Page->file_05->title() ?>" data-table="rapbk" data-field="x_file_05" name="x_file_05" id="x_file_05" lang="<?= CurrentLanguageID() ?>"<?= $Page->file_05->editAttributes() ?><?= ($Page->file_05->ReadOnly || $Page->file_05->Disabled) ? " disabled" : "" ?> aria-describedby="x_file_05_help">
        <label class="custom-file-label ew-file-label" for="x_file_05"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<?= $Page->file_05->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->file_05->getErrorMessage() ?></div>
<input type="hidden" name="fn_x_file_05" id= "fn_x_file_05" value="<?= $Page->file_05->Upload->FileName ?>">
<input type="hidden" name="fa_x_file_05" id= "fa_x_file_05" value="0">
<input type="hidden" name="fs_x_file_05" id= "fs_x_file_05" value="200">
<input type="hidden" name="fx_x_file_05" id= "fx_x_file_05" value="<?= $Page->file_05->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_file_05" id= "fm_x_file_05" value="<?= $Page->file_05->UploadMaxFileSize ?>">
</div>
<table id="ft_x_file_05" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->file_06->Visible) { // file_06 ?>
    <div id="r_file_06" class="form-group row">
        <label id="elh_rapbk_file_06" class="<?= $Page->LeftColumnClass ?>"><?= $Page->file_06->caption() ?><?= $Page->file_06->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->file_06->cellAttributes() ?>>
<span id="el_rapbk_file_06">
<div id="fd_x_file_06">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Page->file_06->title() ?>" data-table="rapbk" data-field="x_file_06" name="x_file_06" id="x_file_06" lang="<?= CurrentLanguageID() ?>"<?= $Page->file_06->editAttributes() ?><?= ($Page->file_06->ReadOnly || $Page->file_06->Disabled) ? " disabled" : "" ?> aria-describedby="x_file_06_help">
        <label class="custom-file-label ew-file-label" for="x_file_06"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<?= $Page->file_06->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->file_06->getErrorMessage() ?></div>
<input type="hidden" name="fn_x_file_06" id= "fn_x_file_06" value="<?= $Page->file_06->Upload->FileName ?>">
<input type="hidden" name="fa_x_file_06" id= "fa_x_file_06" value="0">
<input type="hidden" name="fs_x_file_06" id= "fs_x_file_06" value="200">
<input type="hidden" name="fx_x_file_06" id= "fx_x_file_06" value="<?= $Page->file_06->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_file_06" id= "fm_x_file_06" value="<?= $Page->file_06->UploadMaxFileSize ?>">
</div>
<table id="ft_x_file_06" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->file_07->Visible) { // file_07 ?>
    <div id="r_file_07" class="form-group row">
        <label id="elh_rapbk_file_07" class="<?= $Page->LeftColumnClass ?>"><?= $Page->file_07->caption() ?><?= $Page->file_07->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->file_07->cellAttributes() ?>>
<span id="el_rapbk_file_07">
<div id="fd_x_file_07">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Page->file_07->title() ?>" data-table="rapbk" data-field="x_file_07" name="x_file_07" id="x_file_07" lang="<?= CurrentLanguageID() ?>"<?= $Page->file_07->editAttributes() ?><?= ($Page->file_07->ReadOnly || $Page->file_07->Disabled) ? " disabled" : "" ?> aria-describedby="x_file_07_help">
        <label class="custom-file-label ew-file-label" for="x_file_07"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<?= $Page->file_07->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->file_07->getErrorMessage() ?></div>
<input type="hidden" name="fn_x_file_07" id= "fn_x_file_07" value="<?= $Page->file_07->Upload->FileName ?>">
<input type="hidden" name="fa_x_file_07" id= "fa_x_file_07" value="0">
<input type="hidden" name="fs_x_file_07" id= "fs_x_file_07" value="200">
<input type="hidden" name="fx_x_file_07" id= "fx_x_file_07" value="<?= $Page->file_07->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_file_07" id= "fm_x_file_07" value="<?= $Page->file_07->UploadMaxFileSize ?>">
</div>
<table id="ft_x_file_07" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->file_08->Visible) { // file_08 ?>
    <div id="r_file_08" class="form-group row">
        <label id="elh_rapbk_file_08" class="<?= $Page->LeftColumnClass ?>"><?= $Page->file_08->caption() ?><?= $Page->file_08->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->file_08->cellAttributes() ?>>
<span id="el_rapbk_file_08">
<div id="fd_x_file_08">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Page->file_08->title() ?>" data-table="rapbk" data-field="x_file_08" name="x_file_08" id="x_file_08" lang="<?= CurrentLanguageID() ?>"<?= $Page->file_08->editAttributes() ?><?= ($Page->file_08->ReadOnly || $Page->file_08->Disabled) ? " disabled" : "" ?> aria-describedby="x_file_08_help">
        <label class="custom-file-label ew-file-label" for="x_file_08"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<?= $Page->file_08->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->file_08->getErrorMessage() ?></div>
<input type="hidden" name="fn_x_file_08" id= "fn_x_file_08" value="<?= $Page->file_08->Upload->FileName ?>">
<input type="hidden" name="fa_x_file_08" id= "fa_x_file_08" value="0">
<input type="hidden" name="fs_x_file_08" id= "fs_x_file_08" value="200">
<input type="hidden" name="fx_x_file_08" id= "fx_x_file_08" value="<?= $Page->file_08->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_file_08" id= "fm_x_file_08" value="<?= $Page->file_08->UploadMaxFileSize ?>">
</div>
<table id="ft_x_file_08" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->file_09->Visible) { // file_09 ?>
    <div id="r_file_09" class="form-group row">
        <label id="elh_rapbk_file_09" class="<?= $Page->LeftColumnClass ?>"><?= $Page->file_09->caption() ?><?= $Page->file_09->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->file_09->cellAttributes() ?>>
<span id="el_rapbk_file_09">
<div id="fd_x_file_09">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Page->file_09->title() ?>" data-table="rapbk" data-field="x_file_09" name="x_file_09" id="x_file_09" lang="<?= CurrentLanguageID() ?>"<?= $Page->file_09->editAttributes() ?><?= ($Page->file_09->ReadOnly || $Page->file_09->Disabled) ? " disabled" : "" ?> aria-describedby="x_file_09_help">
        <label class="custom-file-label ew-file-label" for="x_file_09"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<?= $Page->file_09->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->file_09->getErrorMessage() ?></div>
<input type="hidden" name="fn_x_file_09" id= "fn_x_file_09" value="<?= $Page->file_09->Upload->FileName ?>">
<input type="hidden" name="fa_x_file_09" id= "fa_x_file_09" value="0">
<input type="hidden" name="fs_x_file_09" id= "fs_x_file_09" value="200">
<input type="hidden" name="fx_x_file_09" id= "fx_x_file_09" value="<?= $Page->file_09->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_file_09" id= "fm_x_file_09" value="<?= $Page->file_09->UploadMaxFileSize ?>">
</div>
<table id="ft_x_file_09" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->file_10->Visible) { // file_10 ?>
    <div id="r_file_10" class="form-group row">
        <label id="elh_rapbk_file_10" class="<?= $Page->LeftColumnClass ?>"><?= $Page->file_10->caption() ?><?= $Page->file_10->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->file_10->cellAttributes() ?>>
<span id="el_rapbk_file_10">
<div id="fd_x_file_10">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Page->file_10->title() ?>" data-table="rapbk" data-field="x_file_10" name="x_file_10" id="x_file_10" lang="<?= CurrentLanguageID() ?>"<?= $Page->file_10->editAttributes() ?><?= ($Page->file_10->ReadOnly || $Page->file_10->Disabled) ? " disabled" : "" ?> aria-describedby="x_file_10_help">
        <label class="custom-file-label ew-file-label" for="x_file_10"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<?= $Page->file_10->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->file_10->getErrorMessage() ?></div>
<input type="hidden" name="fn_x_file_10" id= "fn_x_file_10" value="<?= $Page->file_10->Upload->FileName ?>">
<input type="hidden" name="fa_x_file_10" id= "fa_x_file_10" value="0">
<input type="hidden" name="fs_x_file_10" id= "fs_x_file_10" value="200">
<input type="hidden" name="fx_x_file_10" id= "fx_x_file_10" value="<?= $Page->file_10->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_file_10" id= "fm_x_file_10" value="<?= $Page->file_10->UploadMaxFileSize ?>">
</div>
<table id="ft_x_file_10" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->file_11->Visible) { // file_11 ?>
    <div id="r_file_11" class="form-group row">
        <label id="elh_rapbk_file_11" class="<?= $Page->LeftColumnClass ?>"><?= $Page->file_11->caption() ?><?= $Page->file_11->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->file_11->cellAttributes() ?>>
<span id="el_rapbk_file_11">
<div id="fd_x_file_11">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Page->file_11->title() ?>" data-table="rapbk" data-field="x_file_11" name="x_file_11" id="x_file_11" lang="<?= CurrentLanguageID() ?>"<?= $Page->file_11->editAttributes() ?><?= ($Page->file_11->ReadOnly || $Page->file_11->Disabled) ? " disabled" : "" ?> aria-describedby="x_file_11_help">
        <label class="custom-file-label ew-file-label" for="x_file_11"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<?= $Page->file_11->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->file_11->getErrorMessage() ?></div>
<input type="hidden" name="fn_x_file_11" id= "fn_x_file_11" value="<?= $Page->file_11->Upload->FileName ?>">
<input type="hidden" name="fa_x_file_11" id= "fa_x_file_11" value="0">
<input type="hidden" name="fs_x_file_11" id= "fs_x_file_11" value="200">
<input type="hidden" name="fx_x_file_11" id= "fx_x_file_11" value="<?= $Page->file_11->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_file_11" id= "fm_x_file_11" value="<?= $Page->file_11->UploadMaxFileSize ?>">
</div>
<table id="ft_x_file_11" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->file_12->Visible) { // file_12 ?>
    <div id="r_file_12" class="form-group row">
        <label id="elh_rapbk_file_12" class="<?= $Page->LeftColumnClass ?>"><?= $Page->file_12->caption() ?><?= $Page->file_12->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->file_12->cellAttributes() ?>>
<span id="el_rapbk_file_12">
<div id="fd_x_file_12">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Page->file_12->title() ?>" data-table="rapbk" data-field="x_file_12" name="x_file_12" id="x_file_12" lang="<?= CurrentLanguageID() ?>"<?= $Page->file_12->editAttributes() ?><?= ($Page->file_12->ReadOnly || $Page->file_12->Disabled) ? " disabled" : "" ?> aria-describedby="x_file_12_help">
        <label class="custom-file-label ew-file-label" for="x_file_12"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<?= $Page->file_12->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->file_12->getErrorMessage() ?></div>
<input type="hidden" name="fn_x_file_12" id= "fn_x_file_12" value="<?= $Page->file_12->Upload->FileName ?>">
<input type="hidden" name="fa_x_file_12" id= "fa_x_file_12" value="0">
<input type="hidden" name="fs_x_file_12" id= "fs_x_file_12" value="200">
<input type="hidden" name="fx_x_file_12" id= "fx_x_file_12" value="<?= $Page->file_12->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_file_12" id= "fm_x_file_12" value="<?= $Page->file_12->UploadMaxFileSize ?>">
</div>
<table id="ft_x_file_12" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->file_13->Visible) { // file_13 ?>
    <div id="r_file_13" class="form-group row">
        <label id="elh_rapbk_file_13" class="<?= $Page->LeftColumnClass ?>"><?= $Page->file_13->caption() ?><?= $Page->file_13->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->file_13->cellAttributes() ?>>
<span id="el_rapbk_file_13">
<div id="fd_x_file_13">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Page->file_13->title() ?>" data-table="rapbk" data-field="x_file_13" name="x_file_13" id="x_file_13" lang="<?= CurrentLanguageID() ?>"<?= $Page->file_13->editAttributes() ?><?= ($Page->file_13->ReadOnly || $Page->file_13->Disabled) ? " disabled" : "" ?> aria-describedby="x_file_13_help">
        <label class="custom-file-label ew-file-label" for="x_file_13"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<?= $Page->file_13->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->file_13->getErrorMessage() ?></div>
<input type="hidden" name="fn_x_file_13" id= "fn_x_file_13" value="<?= $Page->file_13->Upload->FileName ?>">
<input type="hidden" name="fa_x_file_13" id= "fa_x_file_13" value="0">
<input type="hidden" name="fs_x_file_13" id= "fs_x_file_13" value="200">
<input type="hidden" name="fx_x_file_13" id= "fx_x_file_13" value="<?= $Page->file_13->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_file_13" id= "fm_x_file_13" value="<?= $Page->file_13->UploadMaxFileSize ?>">
</div>
<table id="ft_x_file_13" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->file_14->Visible) { // file_14 ?>
    <div id="r_file_14" class="form-group row">
        <label id="elh_rapbk_file_14" class="<?= $Page->LeftColumnClass ?>"><?= $Page->file_14->caption() ?><?= $Page->file_14->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->file_14->cellAttributes() ?>>
<span id="el_rapbk_file_14">
<div id="fd_x_file_14">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Page->file_14->title() ?>" data-table="rapbk" data-field="x_file_14" name="x_file_14" id="x_file_14" lang="<?= CurrentLanguageID() ?>"<?= $Page->file_14->editAttributes() ?><?= ($Page->file_14->ReadOnly || $Page->file_14->Disabled) ? " disabled" : "" ?> aria-describedby="x_file_14_help">
        <label class="custom-file-label ew-file-label" for="x_file_14"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<?= $Page->file_14->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->file_14->getErrorMessage() ?></div>
<input type="hidden" name="fn_x_file_14" id= "fn_x_file_14" value="<?= $Page->file_14->Upload->FileName ?>">
<input type="hidden" name="fa_x_file_14" id= "fa_x_file_14" value="0">
<input type="hidden" name="fs_x_file_14" id= "fs_x_file_14" value="200">
<input type="hidden" name="fx_x_file_14" id= "fx_x_file_14" value="<?= $Page->file_14->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_file_14" id= "fm_x_file_14" value="<?= $Page->file_14->UploadMaxFileSize ?>">
</div>
<table id="ft_x_file_14" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->file_15->Visible) { // file_15 ?>
    <div id="r_file_15" class="form-group row">
        <label id="elh_rapbk_file_15" class="<?= $Page->LeftColumnClass ?>"><?= $Page->file_15->caption() ?><?= $Page->file_15->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->file_15->cellAttributes() ?>>
<span id="el_rapbk_file_15">
<div id="fd_x_file_15">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Page->file_15->title() ?>" data-table="rapbk" data-field="x_file_15" name="x_file_15" id="x_file_15" lang="<?= CurrentLanguageID() ?>"<?= $Page->file_15->editAttributes() ?><?= ($Page->file_15->ReadOnly || $Page->file_15->Disabled) ? " disabled" : "" ?> aria-describedby="x_file_15_help">
        <label class="custom-file-label ew-file-label" for="x_file_15"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<?= $Page->file_15->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->file_15->getErrorMessage() ?></div>
<input type="hidden" name="fn_x_file_15" id= "fn_x_file_15" value="<?= $Page->file_15->Upload->FileName ?>">
<input type="hidden" name="fa_x_file_15" id= "fa_x_file_15" value="0">
<input type="hidden" name="fs_x_file_15" id= "fs_x_file_15" value="200">
<input type="hidden" name="fx_x_file_15" id= "fx_x_file_15" value="<?= $Page->file_15->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_file_15" id= "fm_x_file_15" value="<?= $Page->file_15->UploadMaxFileSize ?>">
</div>
<table id="ft_x_file_15" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->file_16->Visible) { // file_16 ?>
    <div id="r_file_16" class="form-group row">
        <label id="elh_rapbk_file_16" class="<?= $Page->LeftColumnClass ?>"><?= $Page->file_16->caption() ?><?= $Page->file_16->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->file_16->cellAttributes() ?>>
<span id="el_rapbk_file_16">
<div id="fd_x_file_16">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Page->file_16->title() ?>" data-table="rapbk" data-field="x_file_16" name="x_file_16" id="x_file_16" lang="<?= CurrentLanguageID() ?>"<?= $Page->file_16->editAttributes() ?><?= ($Page->file_16->ReadOnly || $Page->file_16->Disabled) ? " disabled" : "" ?> aria-describedby="x_file_16_help">
        <label class="custom-file-label ew-file-label" for="x_file_16"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<?= $Page->file_16->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->file_16->getErrorMessage() ?></div>
<input type="hidden" name="fn_x_file_16" id= "fn_x_file_16" value="<?= $Page->file_16->Upload->FileName ?>">
<input type="hidden" name="fa_x_file_16" id= "fa_x_file_16" value="0">
<input type="hidden" name="fs_x_file_16" id= "fs_x_file_16" value="200">
<input type="hidden" name="fx_x_file_16" id= "fx_x_file_16" value="<?= $Page->file_16->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_file_16" id= "fm_x_file_16" value="<?= $Page->file_16->UploadMaxFileSize ?>">
</div>
<table id="ft_x_file_16" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->file_17->Visible) { // file_17 ?>
    <div id="r_file_17" class="form-group row">
        <label id="elh_rapbk_file_17" class="<?= $Page->LeftColumnClass ?>"><?= $Page->file_17->caption() ?><?= $Page->file_17->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->file_17->cellAttributes() ?>>
<span id="el_rapbk_file_17">
<div id="fd_x_file_17">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Page->file_17->title() ?>" data-table="rapbk" data-field="x_file_17" name="x_file_17" id="x_file_17" lang="<?= CurrentLanguageID() ?>"<?= $Page->file_17->editAttributes() ?><?= ($Page->file_17->ReadOnly || $Page->file_17->Disabled) ? " disabled" : "" ?> aria-describedby="x_file_17_help">
        <label class="custom-file-label ew-file-label" for="x_file_17"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<?= $Page->file_17->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->file_17->getErrorMessage() ?></div>
<input type="hidden" name="fn_x_file_17" id= "fn_x_file_17" value="<?= $Page->file_17->Upload->FileName ?>">
<input type="hidden" name="fa_x_file_17" id= "fa_x_file_17" value="0">
<input type="hidden" name="fs_x_file_17" id= "fs_x_file_17" value="200">
<input type="hidden" name="fx_x_file_17" id= "fx_x_file_17" value="<?= $Page->file_17->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_file_17" id= "fm_x_file_17" value="<?= $Page->file_17->UploadMaxFileSize ?>">
</div>
<table id="ft_x_file_17" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->file_18->Visible) { // file_18 ?>
    <div id="r_file_18" class="form-group row">
        <label id="elh_rapbk_file_18" class="<?= $Page->LeftColumnClass ?>"><?= $Page->file_18->caption() ?><?= $Page->file_18->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->file_18->cellAttributes() ?>>
<span id="el_rapbk_file_18">
<div id="fd_x_file_18">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Page->file_18->title() ?>" data-table="rapbk" data-field="x_file_18" name="x_file_18" id="x_file_18" lang="<?= CurrentLanguageID() ?>"<?= $Page->file_18->editAttributes() ?><?= ($Page->file_18->ReadOnly || $Page->file_18->Disabled) ? " disabled" : "" ?> aria-describedby="x_file_18_help">
        <label class="custom-file-label ew-file-label" for="x_file_18"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<?= $Page->file_18->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->file_18->getErrorMessage() ?></div>
<input type="hidden" name="fn_x_file_18" id= "fn_x_file_18" value="<?= $Page->file_18->Upload->FileName ?>">
<input type="hidden" name="fa_x_file_18" id= "fa_x_file_18" value="0">
<input type="hidden" name="fs_x_file_18" id= "fs_x_file_18" value="200">
<input type="hidden" name="fx_x_file_18" id= "fx_x_file_18" value="<?= $Page->file_18->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_file_18" id= "fm_x_file_18" value="<?= $Page->file_18->UploadMaxFileSize ?>">
</div>
<table id="ft_x_file_18" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->file_19->Visible) { // file_19 ?>
    <div id="r_file_19" class="form-group row">
        <label id="elh_rapbk_file_19" class="<?= $Page->LeftColumnClass ?>"><?= $Page->file_19->caption() ?><?= $Page->file_19->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->file_19->cellAttributes() ?>>
<span id="el_rapbk_file_19">
<div id="fd_x_file_19">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Page->file_19->title() ?>" data-table="rapbk" data-field="x_file_19" name="x_file_19" id="x_file_19" lang="<?= CurrentLanguageID() ?>"<?= $Page->file_19->editAttributes() ?><?= ($Page->file_19->ReadOnly || $Page->file_19->Disabled) ? " disabled" : "" ?> aria-describedby="x_file_19_help">
        <label class="custom-file-label ew-file-label" for="x_file_19"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<?= $Page->file_19->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->file_19->getErrorMessage() ?></div>
<input type="hidden" name="fn_x_file_19" id= "fn_x_file_19" value="<?= $Page->file_19->Upload->FileName ?>">
<input type="hidden" name="fa_x_file_19" id= "fa_x_file_19" value="0">
<input type="hidden" name="fs_x_file_19" id= "fs_x_file_19" value="200">
<input type="hidden" name="fx_x_file_19" id= "fx_x_file_19" value="<?= $Page->file_19->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_file_19" id= "fm_x_file_19" value="<?= $Page->file_19->UploadMaxFileSize ?>">
</div>
<table id="ft_x_file_19" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->file_20->Visible) { // file_20 ?>
    <div id="r_file_20" class="form-group row">
        <label id="elh_rapbk_file_20" class="<?= $Page->LeftColumnClass ?>"><?= $Page->file_20->caption() ?><?= $Page->file_20->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->file_20->cellAttributes() ?>>
<span id="el_rapbk_file_20">
<div id="fd_x_file_20">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Page->file_20->title() ?>" data-table="rapbk" data-field="x_file_20" name="x_file_20" id="x_file_20" lang="<?= CurrentLanguageID() ?>"<?= $Page->file_20->editAttributes() ?><?= ($Page->file_20->ReadOnly || $Page->file_20->Disabled) ? " disabled" : "" ?> aria-describedby="x_file_20_help">
        <label class="custom-file-label ew-file-label" for="x_file_20"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<?= $Page->file_20->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->file_20->getErrorMessage() ?></div>
<input type="hidden" name="fn_x_file_20" id= "fn_x_file_20" value="<?= $Page->file_20->Upload->FileName ?>">
<input type="hidden" name="fa_x_file_20" id= "fa_x_file_20" value="0">
<input type="hidden" name="fs_x_file_20" id= "fs_x_file_20" value="200">
<input type="hidden" name="fx_x_file_20" id= "fx_x_file_20" value="<?= $Page->file_20->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_file_20" id= "fm_x_file_20" value="<?= $Page->file_20->UploadMaxFileSize ?>">
</div>
<table id="ft_x_file_20" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->file_21->Visible) { // file_21 ?>
    <div id="r_file_21" class="form-group row">
        <label id="elh_rapbk_file_21" class="<?= $Page->LeftColumnClass ?>"><?= $Page->file_21->caption() ?><?= $Page->file_21->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->file_21->cellAttributes() ?>>
<span id="el_rapbk_file_21">
<div id="fd_x_file_21">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Page->file_21->title() ?>" data-table="rapbk" data-field="x_file_21" name="x_file_21" id="x_file_21" lang="<?= CurrentLanguageID() ?>"<?= $Page->file_21->editAttributes() ?><?= ($Page->file_21->ReadOnly || $Page->file_21->Disabled) ? " disabled" : "" ?> aria-describedby="x_file_21_help">
        <label class="custom-file-label ew-file-label" for="x_file_21"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<?= $Page->file_21->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->file_21->getErrorMessage() ?></div>
<input type="hidden" name="fn_x_file_21" id= "fn_x_file_21" value="<?= $Page->file_21->Upload->FileName ?>">
<input type="hidden" name="fa_x_file_21" id= "fa_x_file_21" value="0">
<input type="hidden" name="fs_x_file_21" id= "fs_x_file_21" value="200">
<input type="hidden" name="fx_x_file_21" id= "fx_x_file_21" value="<?= $Page->file_21->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_file_21" id= "fm_x_file_21" value="<?= $Page->file_21->UploadMaxFileSize ?>">
</div>
<table id="ft_x_file_21" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->file_22->Visible) { // file_22 ?>
    <div id="r_file_22" class="form-group row">
        <label id="elh_rapbk_file_22" class="<?= $Page->LeftColumnClass ?>"><?= $Page->file_22->caption() ?><?= $Page->file_22->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->file_22->cellAttributes() ?>>
<span id="el_rapbk_file_22">
<div id="fd_x_file_22">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Page->file_22->title() ?>" data-table="rapbk" data-field="x_file_22" name="x_file_22" id="x_file_22" lang="<?= CurrentLanguageID() ?>"<?= $Page->file_22->editAttributes() ?><?= ($Page->file_22->ReadOnly || $Page->file_22->Disabled) ? " disabled" : "" ?> aria-describedby="x_file_22_help">
        <label class="custom-file-label ew-file-label" for="x_file_22"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<?= $Page->file_22->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->file_22->getErrorMessage() ?></div>
<input type="hidden" name="fn_x_file_22" id= "fn_x_file_22" value="<?= $Page->file_22->Upload->FileName ?>">
<input type="hidden" name="fa_x_file_22" id= "fa_x_file_22" value="0">
<input type="hidden" name="fs_x_file_22" id= "fs_x_file_22" value="200">
<input type="hidden" name="fx_x_file_22" id= "fx_x_file_22" value="<?= $Page->file_22->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_file_22" id= "fm_x_file_22" value="<?= $Page->file_22->UploadMaxFileSize ?>">
</div>
<table id="ft_x_file_22" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->file_23->Visible) { // file_23 ?>
    <div id="r_file_23" class="form-group row">
        <label id="elh_rapbk_file_23" class="<?= $Page->LeftColumnClass ?>"><?= $Page->file_23->caption() ?><?= $Page->file_23->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->file_23->cellAttributes() ?>>
<span id="el_rapbk_file_23">
<div id="fd_x_file_23">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Page->file_23->title() ?>" data-table="rapbk" data-field="x_file_23" name="x_file_23" id="x_file_23" lang="<?= CurrentLanguageID() ?>"<?= $Page->file_23->editAttributes() ?><?= ($Page->file_23->ReadOnly || $Page->file_23->Disabled) ? " disabled" : "" ?> aria-describedby="x_file_23_help">
        <label class="custom-file-label ew-file-label" for="x_file_23"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<?= $Page->file_23->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->file_23->getErrorMessage() ?></div>
<input type="hidden" name="fn_x_file_23" id= "fn_x_file_23" value="<?= $Page->file_23->Upload->FileName ?>">
<input type="hidden" name="fa_x_file_23" id= "fa_x_file_23" value="0">
<input type="hidden" name="fs_x_file_23" id= "fs_x_file_23" value="200">
<input type="hidden" name="fx_x_file_23" id= "fx_x_file_23" value="<?= $Page->file_23->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_file_23" id= "fm_x_file_23" value="<?= $Page->file_23->UploadMaxFileSize ?>">
</div>
<table id="ft_x_file_23" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->file_24->Visible) { // file_24 ?>
    <div id="r_file_24" class="form-group row">
        <label id="elh_rapbk_file_24" class="<?= $Page->LeftColumnClass ?>"><?= $Page->file_24->caption() ?><?= $Page->file_24->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->file_24->cellAttributes() ?>>
<span id="el_rapbk_file_24">
<div id="fd_x_file_24">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Page->file_24->title() ?>" data-table="rapbk" data-field="x_file_24" name="x_file_24" id="x_file_24" lang="<?= CurrentLanguageID() ?>"<?= $Page->file_24->editAttributes() ?><?= ($Page->file_24->ReadOnly || $Page->file_24->Disabled) ? " disabled" : "" ?> aria-describedby="x_file_24_help">
        <label class="custom-file-label ew-file-label" for="x_file_24"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<?= $Page->file_24->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->file_24->getErrorMessage() ?></div>
<input type="hidden" name="fn_x_file_24" id= "fn_x_file_24" value="<?= $Page->file_24->Upload->FileName ?>">
<input type="hidden" name="fa_x_file_24" id= "fa_x_file_24" value="0">
<input type="hidden" name="fs_x_file_24" id= "fs_x_file_24" value="200">
<input type="hidden" name="fx_x_file_24" id= "fx_x_file_24" value="<?= $Page->file_24->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_file_24" id= "fm_x_file_24" value="<?= $Page->file_24->UploadMaxFileSize ?>">
</div>
<table id="ft_x_file_24" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->status->Visible) { // status ?>
    <div id="r_status" class="form-group row">
        <label id="elh_rapbk_status" for="x_status" class="<?= $Page->LeftColumnClass ?>"><?= $Page->status->caption() ?><?= $Page->status->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->status->cellAttributes() ?>>
<span id="el_rapbk_status">
    <select
        id="x_status"
        name="x_status"
        class="form-control ew-select<?= $Page->status->isInvalidClass() ?>"
        data-select2-id="rapbk_x_status"
        data-table="rapbk"
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
    var el = document.querySelector("select[data-select2-id='rapbk_x_status']"),
        options = { name: "x_status", selectId: "rapbk_x_status", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.data = ew.vars.tables.rapbk.fields.status.lookupOptions;
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.rapbk.fields.status.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->idd_user->Visible) { // idd_user ?>
    <div id="r_idd_user" class="form-group row">
        <label id="elh_rapbk_idd_user" for="x_idd_user" class="<?= $Page->LeftColumnClass ?>"><?= $Page->idd_user->caption() ?><?= $Page->idd_user->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->idd_user->cellAttributes() ?>>
<?php if (!$Security->isAdmin() && $Security->isLoggedIn() && !$Page->userIDAllow("add")) { // Non system admin ?>
<span id="el_rapbk_idd_user">
<span<?= $Page->idd_user->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->idd_user->getDisplayValue($Page->idd_user->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="rapbk" data-field="x_idd_user" data-hidden="1" name="x_idd_user" id="x_idd_user" value="<?= HtmlEncode($Page->idd_user->CurrentValue) ?>">
<?php } else { ?>
<span id="el_rapbk_idd_user">
    <select
        id="x_idd_user"
        name="x_idd_user"
        class="form-control ew-select<?= $Page->idd_user->isInvalidClass() ?>"
        data-select2-id="rapbk_x_idd_user"
        data-table="rapbk"
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
    var el = document.querySelector("select[data-select2-id='rapbk_x_idd_user']"),
        options = { name: "x_idd_user", selectId: "rapbk_x_idd_user", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.rapbk.fields.idd_user.selectOptions);
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
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit"><?= $Language->phrase("AddBtn") ?></button>
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
    ew.addEventHandlers("rapbk");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
