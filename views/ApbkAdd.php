<?php

namespace PHPMaker2021\silpa;

// Page object
$ApbkAdd = &$Page;
?>
<script>
var currentForm, currentPageID;
var fapbkadd;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "add";
    fapbkadd = currentForm = new ew.Form("fapbkadd", "add");

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "apbk")) ?>,
        fields = currentTable.fields;
    if (!ew.vars.tables.apbk)
        ew.vars.tables.apbk = currentTable;
    fapbkadd.addFields([
        ["tanggal", [fields.tanggal.visible && fields.tanggal.required ? ew.Validators.required(fields.tanggal.caption) : null, ew.Validators.datetime(0)], fields.tanggal.isInvalid],
        ["kd_satker", [fields.kd_satker.visible && fields.kd_satker.required ? ew.Validators.required(fields.kd_satker.caption) : null], fields.kd_satker.isInvalid],
        ["idd_tahapan", [fields.idd_tahapan.visible && fields.idd_tahapan.required ? ew.Validators.required(fields.idd_tahapan.caption) : null, ew.Validators.integer], fields.idd_tahapan.isInvalid],
        ["tahun_anggaran", [fields.tahun_anggaran.visible && fields.tahun_anggaran.required ? ew.Validators.required(fields.tahun_anggaran.caption) : null], fields.tahun_anggaran.isInvalid],
        ["idd_wilayah", [fields.idd_wilayah.visible && fields.idd_wilayah.required ? ew.Validators.required(fields.idd_wilayah.caption) : null, ew.Validators.integer], fields.idd_wilayah.isInvalid],
        ["file_01", [fields.file_01.visible && fields.file_01.required ? ew.Validators.required(fields.file_01.caption) : null], fields.file_01.isInvalid],
        ["file_02", [fields.file_02.visible && fields.file_02.required ? ew.Validators.required(fields.file_02.caption) : null], fields.file_02.isInvalid],
        ["file_03", [fields.file_03.visible && fields.file_03.required ? ew.Validators.required(fields.file_03.caption) : null], fields.file_03.isInvalid],
        ["file_04", [fields.file_04.visible && fields.file_04.required ? ew.Validators.required(fields.file_04.caption) : null], fields.file_04.isInvalid],
        ["file_05", [fields.file_05.visible && fields.file_05.required ? ew.Validators.required(fields.file_05.caption) : null], fields.file_05.isInvalid],
        ["file_06", [fields.file_06.visible && fields.file_06.required ? ew.Validators.required(fields.file_06.caption) : null], fields.file_06.isInvalid],
        ["file_07", [fields.file_07.visible && fields.file_07.required ? ew.Validators.required(fields.file_07.caption) : null], fields.file_07.isInvalid],
        ["file_08", [fields.file_08.visible && fields.file_08.required ? ew.Validators.required(fields.file_08.caption) : null], fields.file_08.isInvalid],
        ["file_09", [fields.file_09.visible && fields.file_09.required ? ew.Validators.required(fields.file_09.caption) : null], fields.file_09.isInvalid],
        ["file_10", [fields.file_10.visible && fields.file_10.required ? ew.Validators.required(fields.file_10.caption) : null], fields.file_10.isInvalid],
        ["file_11", [fields.file_11.visible && fields.file_11.required ? ew.Validators.required(fields.file_11.caption) : null], fields.file_11.isInvalid],
        ["file_12", [fields.file_12.visible && fields.file_12.required ? ew.Validators.required(fields.file_12.caption) : null], fields.file_12.isInvalid],
        ["file_13", [fields.file_13.visible && fields.file_13.required ? ew.Validators.required(fields.file_13.caption) : null], fields.file_13.isInvalid],
        ["file_14", [fields.file_14.visible && fields.file_14.required ? ew.Validators.required(fields.file_14.caption) : null], fields.file_14.isInvalid],
        ["file_15", [fields.file_15.visible && fields.file_15.required ? ew.Validators.required(fields.file_15.caption) : null], fields.file_15.isInvalid],
        ["file_16", [fields.file_16.visible && fields.file_16.required ? ew.Validators.required(fields.file_16.caption) : null], fields.file_16.isInvalid],
        ["file_17", [fields.file_17.visible && fields.file_17.required ? ew.Validators.required(fields.file_17.caption) : null], fields.file_17.isInvalid],
        ["file_18", [fields.file_18.visible && fields.file_18.required ? ew.Validators.required(fields.file_18.caption) : null], fields.file_18.isInvalid],
        ["file_19", [fields.file_19.visible && fields.file_19.required ? ew.Validators.required(fields.file_19.caption) : null], fields.file_19.isInvalid],
        ["file_20", [fields.file_20.visible && fields.file_20.required ? ew.Validators.required(fields.file_20.caption) : null], fields.file_20.isInvalid],
        ["file_21", [fields.file_21.visible && fields.file_21.required ? ew.Validators.required(fields.file_21.caption) : null], fields.file_21.isInvalid],
        ["file_22", [fields.file_22.visible && fields.file_22.required ? ew.Validators.required(fields.file_22.caption) : null], fields.file_22.isInvalid],
        ["file_23", [fields.file_23.visible && fields.file_23.required ? ew.Validators.required(fields.file_23.caption) : null], fields.file_23.isInvalid],
        ["file_24", [fields.file_24.visible && fields.file_24.required ? ew.Validators.required(fields.file_24.caption) : null], fields.file_24.isInvalid],
        ["status", [fields.status.visible && fields.status.required ? ew.Validators.required(fields.status.caption) : null, ew.Validators.integer], fields.status.isInvalid],
        ["idd_user", [fields.idd_user.visible && fields.idd_user.required ? ew.Validators.required(fields.idd_user.caption) : null, ew.Validators.integer], fields.idd_user.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fapbkadd,
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
    fapbkadd.validate = function () {
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
    fapbkadd.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fapbkadd.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    loadjs.done("fapbkadd");
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
<form name="fapbkadd" id="fapbkadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="apbk">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->tanggal->Visible) { // tanggal ?>
    <div id="r_tanggal" class="form-group row">
        <label id="elh_apbk_tanggal" for="x_tanggal" class="<?= $Page->LeftColumnClass ?>"><?= $Page->tanggal->caption() ?><?= $Page->tanggal->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->tanggal->cellAttributes() ?>>
<span id="el_apbk_tanggal">
<input type="<?= $Page->tanggal->getInputTextType() ?>" data-table="apbk" data-field="x_tanggal" name="x_tanggal" id="x_tanggal" placeholder="<?= HtmlEncode($Page->tanggal->getPlaceHolder()) ?>" value="<?= $Page->tanggal->EditValue ?>"<?= $Page->tanggal->editAttributes() ?> aria-describedby="x_tanggal_help">
<?= $Page->tanggal->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->tanggal->getErrorMessage() ?></div>
<?php if (!$Page->tanggal->ReadOnly && !$Page->tanggal->Disabled && !isset($Page->tanggal->EditAttrs["readonly"]) && !isset($Page->tanggal->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fapbkadd", "datetimepicker"], function() {
    ew.createDateTimePicker("fapbkadd", "x_tanggal", {"ignoreReadonly":true,"useCurrent":false,"format":0});
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->kd_satker->Visible) { // kd_satker ?>
    <div id="r_kd_satker" class="form-group row">
        <label id="elh_apbk_kd_satker" for="x_kd_satker" class="<?= $Page->LeftColumnClass ?>"><?= $Page->kd_satker->caption() ?><?= $Page->kd_satker->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->kd_satker->cellAttributes() ?>>
<span id="el_apbk_kd_satker">
<input type="<?= $Page->kd_satker->getInputTextType() ?>" data-table="apbk" data-field="x_kd_satker" name="x_kd_satker" id="x_kd_satker" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->kd_satker->getPlaceHolder()) ?>" value="<?= $Page->kd_satker->EditValue ?>"<?= $Page->kd_satker->editAttributes() ?> aria-describedby="x_kd_satker_help">
<?= $Page->kd_satker->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->kd_satker->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->idd_tahapan->Visible) { // idd_tahapan ?>
    <div id="r_idd_tahapan" class="form-group row">
        <label id="elh_apbk_idd_tahapan" for="x_idd_tahapan" class="<?= $Page->LeftColumnClass ?>"><?= $Page->idd_tahapan->caption() ?><?= $Page->idd_tahapan->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->idd_tahapan->cellAttributes() ?>>
<span id="el_apbk_idd_tahapan">
<input type="<?= $Page->idd_tahapan->getInputTextType() ?>" data-table="apbk" data-field="x_idd_tahapan" name="x_idd_tahapan" id="x_idd_tahapan" size="30" placeholder="<?= HtmlEncode($Page->idd_tahapan->getPlaceHolder()) ?>" value="<?= $Page->idd_tahapan->EditValue ?>"<?= $Page->idd_tahapan->editAttributes() ?> aria-describedby="x_idd_tahapan_help">
<?= $Page->idd_tahapan->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->idd_tahapan->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->tahun_anggaran->Visible) { // tahun_anggaran ?>
    <div id="r_tahun_anggaran" class="form-group row">
        <label id="elh_apbk_tahun_anggaran" for="x_tahun_anggaran" class="<?= $Page->LeftColumnClass ?>"><?= $Page->tahun_anggaran->caption() ?><?= $Page->tahun_anggaran->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->tahun_anggaran->cellAttributes() ?>>
<span id="el_apbk_tahun_anggaran">
<input type="<?= $Page->tahun_anggaran->getInputTextType() ?>" data-table="apbk" data-field="x_tahun_anggaran" name="x_tahun_anggaran" id="x_tahun_anggaran" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->tahun_anggaran->getPlaceHolder()) ?>" value="<?= $Page->tahun_anggaran->EditValue ?>"<?= $Page->tahun_anggaran->editAttributes() ?> aria-describedby="x_tahun_anggaran_help">
<?= $Page->tahun_anggaran->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->tahun_anggaran->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->idd_wilayah->Visible) { // idd_wilayah ?>
    <div id="r_idd_wilayah" class="form-group row">
        <label id="elh_apbk_idd_wilayah" for="x_idd_wilayah" class="<?= $Page->LeftColumnClass ?>"><?= $Page->idd_wilayah->caption() ?><?= $Page->idd_wilayah->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->idd_wilayah->cellAttributes() ?>>
<span id="el_apbk_idd_wilayah">
<input type="<?= $Page->idd_wilayah->getInputTextType() ?>" data-table="apbk" data-field="x_idd_wilayah" name="x_idd_wilayah" id="x_idd_wilayah" size="30" placeholder="<?= HtmlEncode($Page->idd_wilayah->getPlaceHolder()) ?>" value="<?= $Page->idd_wilayah->EditValue ?>"<?= $Page->idd_wilayah->editAttributes() ?> aria-describedby="x_idd_wilayah_help">
<?= $Page->idd_wilayah->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->idd_wilayah->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->file_01->Visible) { // file_01 ?>
    <div id="r_file_01" class="form-group row">
        <label id="elh_apbk_file_01" for="x_file_01" class="<?= $Page->LeftColumnClass ?>"><?= $Page->file_01->caption() ?><?= $Page->file_01->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->file_01->cellAttributes() ?>>
<span id="el_apbk_file_01">
<input type="<?= $Page->file_01->getInputTextType() ?>" data-table="apbk" data-field="x_file_01" name="x_file_01" id="x_file_01" size="30" maxlength="200" placeholder="<?= HtmlEncode($Page->file_01->getPlaceHolder()) ?>" value="<?= $Page->file_01->EditValue ?>"<?= $Page->file_01->editAttributes() ?> aria-describedby="x_file_01_help">
<?= $Page->file_01->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->file_01->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->file_02->Visible) { // file_02 ?>
    <div id="r_file_02" class="form-group row">
        <label id="elh_apbk_file_02" for="x_file_02" class="<?= $Page->LeftColumnClass ?>"><?= $Page->file_02->caption() ?><?= $Page->file_02->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->file_02->cellAttributes() ?>>
<span id="el_apbk_file_02">
<input type="<?= $Page->file_02->getInputTextType() ?>" data-table="apbk" data-field="x_file_02" name="x_file_02" id="x_file_02" size="30" maxlength="200" placeholder="<?= HtmlEncode($Page->file_02->getPlaceHolder()) ?>" value="<?= $Page->file_02->EditValue ?>"<?= $Page->file_02->editAttributes() ?> aria-describedby="x_file_02_help">
<?= $Page->file_02->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->file_02->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->file_03->Visible) { // file_03 ?>
    <div id="r_file_03" class="form-group row">
        <label id="elh_apbk_file_03" for="x_file_03" class="<?= $Page->LeftColumnClass ?>"><?= $Page->file_03->caption() ?><?= $Page->file_03->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->file_03->cellAttributes() ?>>
<span id="el_apbk_file_03">
<input type="<?= $Page->file_03->getInputTextType() ?>" data-table="apbk" data-field="x_file_03" name="x_file_03" id="x_file_03" size="30" maxlength="200" placeholder="<?= HtmlEncode($Page->file_03->getPlaceHolder()) ?>" value="<?= $Page->file_03->EditValue ?>"<?= $Page->file_03->editAttributes() ?> aria-describedby="x_file_03_help">
<?= $Page->file_03->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->file_03->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->file_04->Visible) { // file_04 ?>
    <div id="r_file_04" class="form-group row">
        <label id="elh_apbk_file_04" for="x_file_04" class="<?= $Page->LeftColumnClass ?>"><?= $Page->file_04->caption() ?><?= $Page->file_04->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->file_04->cellAttributes() ?>>
<span id="el_apbk_file_04">
<input type="<?= $Page->file_04->getInputTextType() ?>" data-table="apbk" data-field="x_file_04" name="x_file_04" id="x_file_04" size="30" maxlength="200" placeholder="<?= HtmlEncode($Page->file_04->getPlaceHolder()) ?>" value="<?= $Page->file_04->EditValue ?>"<?= $Page->file_04->editAttributes() ?> aria-describedby="x_file_04_help">
<?= $Page->file_04->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->file_04->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->file_05->Visible) { // file_05 ?>
    <div id="r_file_05" class="form-group row">
        <label id="elh_apbk_file_05" for="x_file_05" class="<?= $Page->LeftColumnClass ?>"><?= $Page->file_05->caption() ?><?= $Page->file_05->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->file_05->cellAttributes() ?>>
<span id="el_apbk_file_05">
<input type="<?= $Page->file_05->getInputTextType() ?>" data-table="apbk" data-field="x_file_05" name="x_file_05" id="x_file_05" size="30" maxlength="200" placeholder="<?= HtmlEncode($Page->file_05->getPlaceHolder()) ?>" value="<?= $Page->file_05->EditValue ?>"<?= $Page->file_05->editAttributes() ?> aria-describedby="x_file_05_help">
<?= $Page->file_05->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->file_05->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->file_06->Visible) { // file_06 ?>
    <div id="r_file_06" class="form-group row">
        <label id="elh_apbk_file_06" for="x_file_06" class="<?= $Page->LeftColumnClass ?>"><?= $Page->file_06->caption() ?><?= $Page->file_06->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->file_06->cellAttributes() ?>>
<span id="el_apbk_file_06">
<input type="<?= $Page->file_06->getInputTextType() ?>" data-table="apbk" data-field="x_file_06" name="x_file_06" id="x_file_06" size="30" maxlength="200" placeholder="<?= HtmlEncode($Page->file_06->getPlaceHolder()) ?>" value="<?= $Page->file_06->EditValue ?>"<?= $Page->file_06->editAttributes() ?> aria-describedby="x_file_06_help">
<?= $Page->file_06->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->file_06->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->file_07->Visible) { // file_07 ?>
    <div id="r_file_07" class="form-group row">
        <label id="elh_apbk_file_07" for="x_file_07" class="<?= $Page->LeftColumnClass ?>"><?= $Page->file_07->caption() ?><?= $Page->file_07->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->file_07->cellAttributes() ?>>
<span id="el_apbk_file_07">
<input type="<?= $Page->file_07->getInputTextType() ?>" data-table="apbk" data-field="x_file_07" name="x_file_07" id="x_file_07" size="30" maxlength="200" placeholder="<?= HtmlEncode($Page->file_07->getPlaceHolder()) ?>" value="<?= $Page->file_07->EditValue ?>"<?= $Page->file_07->editAttributes() ?> aria-describedby="x_file_07_help">
<?= $Page->file_07->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->file_07->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->file_08->Visible) { // file_08 ?>
    <div id="r_file_08" class="form-group row">
        <label id="elh_apbk_file_08" for="x_file_08" class="<?= $Page->LeftColumnClass ?>"><?= $Page->file_08->caption() ?><?= $Page->file_08->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->file_08->cellAttributes() ?>>
<span id="el_apbk_file_08">
<input type="<?= $Page->file_08->getInputTextType() ?>" data-table="apbk" data-field="x_file_08" name="x_file_08" id="x_file_08" size="30" maxlength="200" placeholder="<?= HtmlEncode($Page->file_08->getPlaceHolder()) ?>" value="<?= $Page->file_08->EditValue ?>"<?= $Page->file_08->editAttributes() ?> aria-describedby="x_file_08_help">
<?= $Page->file_08->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->file_08->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->file_09->Visible) { // file_09 ?>
    <div id="r_file_09" class="form-group row">
        <label id="elh_apbk_file_09" for="x_file_09" class="<?= $Page->LeftColumnClass ?>"><?= $Page->file_09->caption() ?><?= $Page->file_09->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->file_09->cellAttributes() ?>>
<span id="el_apbk_file_09">
<input type="<?= $Page->file_09->getInputTextType() ?>" data-table="apbk" data-field="x_file_09" name="x_file_09" id="x_file_09" size="30" maxlength="200" placeholder="<?= HtmlEncode($Page->file_09->getPlaceHolder()) ?>" value="<?= $Page->file_09->EditValue ?>"<?= $Page->file_09->editAttributes() ?> aria-describedby="x_file_09_help">
<?= $Page->file_09->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->file_09->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->file_10->Visible) { // file_10 ?>
    <div id="r_file_10" class="form-group row">
        <label id="elh_apbk_file_10" for="x_file_10" class="<?= $Page->LeftColumnClass ?>"><?= $Page->file_10->caption() ?><?= $Page->file_10->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->file_10->cellAttributes() ?>>
<span id="el_apbk_file_10">
<input type="<?= $Page->file_10->getInputTextType() ?>" data-table="apbk" data-field="x_file_10" name="x_file_10" id="x_file_10" size="30" maxlength="200" placeholder="<?= HtmlEncode($Page->file_10->getPlaceHolder()) ?>" value="<?= $Page->file_10->EditValue ?>"<?= $Page->file_10->editAttributes() ?> aria-describedby="x_file_10_help">
<?= $Page->file_10->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->file_10->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->file_11->Visible) { // file_11 ?>
    <div id="r_file_11" class="form-group row">
        <label id="elh_apbk_file_11" for="x_file_11" class="<?= $Page->LeftColumnClass ?>"><?= $Page->file_11->caption() ?><?= $Page->file_11->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->file_11->cellAttributes() ?>>
<span id="el_apbk_file_11">
<input type="<?= $Page->file_11->getInputTextType() ?>" data-table="apbk" data-field="x_file_11" name="x_file_11" id="x_file_11" size="30" maxlength="200" placeholder="<?= HtmlEncode($Page->file_11->getPlaceHolder()) ?>" value="<?= $Page->file_11->EditValue ?>"<?= $Page->file_11->editAttributes() ?> aria-describedby="x_file_11_help">
<?= $Page->file_11->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->file_11->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->file_12->Visible) { // file_12 ?>
    <div id="r_file_12" class="form-group row">
        <label id="elh_apbk_file_12" for="x_file_12" class="<?= $Page->LeftColumnClass ?>"><?= $Page->file_12->caption() ?><?= $Page->file_12->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->file_12->cellAttributes() ?>>
<span id="el_apbk_file_12">
<input type="<?= $Page->file_12->getInputTextType() ?>" data-table="apbk" data-field="x_file_12" name="x_file_12" id="x_file_12" size="30" maxlength="200" placeholder="<?= HtmlEncode($Page->file_12->getPlaceHolder()) ?>" value="<?= $Page->file_12->EditValue ?>"<?= $Page->file_12->editAttributes() ?> aria-describedby="x_file_12_help">
<?= $Page->file_12->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->file_12->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->file_13->Visible) { // file_13 ?>
    <div id="r_file_13" class="form-group row">
        <label id="elh_apbk_file_13" for="x_file_13" class="<?= $Page->LeftColumnClass ?>"><?= $Page->file_13->caption() ?><?= $Page->file_13->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->file_13->cellAttributes() ?>>
<span id="el_apbk_file_13">
<input type="<?= $Page->file_13->getInputTextType() ?>" data-table="apbk" data-field="x_file_13" name="x_file_13" id="x_file_13" size="30" maxlength="200" placeholder="<?= HtmlEncode($Page->file_13->getPlaceHolder()) ?>" value="<?= $Page->file_13->EditValue ?>"<?= $Page->file_13->editAttributes() ?> aria-describedby="x_file_13_help">
<?= $Page->file_13->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->file_13->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->file_14->Visible) { // file_14 ?>
    <div id="r_file_14" class="form-group row">
        <label id="elh_apbk_file_14" for="x_file_14" class="<?= $Page->LeftColumnClass ?>"><?= $Page->file_14->caption() ?><?= $Page->file_14->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->file_14->cellAttributes() ?>>
<span id="el_apbk_file_14">
<input type="<?= $Page->file_14->getInputTextType() ?>" data-table="apbk" data-field="x_file_14" name="x_file_14" id="x_file_14" size="30" maxlength="200" placeholder="<?= HtmlEncode($Page->file_14->getPlaceHolder()) ?>" value="<?= $Page->file_14->EditValue ?>"<?= $Page->file_14->editAttributes() ?> aria-describedby="x_file_14_help">
<?= $Page->file_14->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->file_14->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->file_15->Visible) { // file_15 ?>
    <div id="r_file_15" class="form-group row">
        <label id="elh_apbk_file_15" for="x_file_15" class="<?= $Page->LeftColumnClass ?>"><?= $Page->file_15->caption() ?><?= $Page->file_15->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->file_15->cellAttributes() ?>>
<span id="el_apbk_file_15">
<input type="<?= $Page->file_15->getInputTextType() ?>" data-table="apbk" data-field="x_file_15" name="x_file_15" id="x_file_15" size="30" maxlength="200" placeholder="<?= HtmlEncode($Page->file_15->getPlaceHolder()) ?>" value="<?= $Page->file_15->EditValue ?>"<?= $Page->file_15->editAttributes() ?> aria-describedby="x_file_15_help">
<?= $Page->file_15->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->file_15->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->file_16->Visible) { // file_16 ?>
    <div id="r_file_16" class="form-group row">
        <label id="elh_apbk_file_16" for="x_file_16" class="<?= $Page->LeftColumnClass ?>"><?= $Page->file_16->caption() ?><?= $Page->file_16->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->file_16->cellAttributes() ?>>
<span id="el_apbk_file_16">
<input type="<?= $Page->file_16->getInputTextType() ?>" data-table="apbk" data-field="x_file_16" name="x_file_16" id="x_file_16" size="30" maxlength="200" placeholder="<?= HtmlEncode($Page->file_16->getPlaceHolder()) ?>" value="<?= $Page->file_16->EditValue ?>"<?= $Page->file_16->editAttributes() ?> aria-describedby="x_file_16_help">
<?= $Page->file_16->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->file_16->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->file_17->Visible) { // file_17 ?>
    <div id="r_file_17" class="form-group row">
        <label id="elh_apbk_file_17" for="x_file_17" class="<?= $Page->LeftColumnClass ?>"><?= $Page->file_17->caption() ?><?= $Page->file_17->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->file_17->cellAttributes() ?>>
<span id="el_apbk_file_17">
<input type="<?= $Page->file_17->getInputTextType() ?>" data-table="apbk" data-field="x_file_17" name="x_file_17" id="x_file_17" size="30" maxlength="200" placeholder="<?= HtmlEncode($Page->file_17->getPlaceHolder()) ?>" value="<?= $Page->file_17->EditValue ?>"<?= $Page->file_17->editAttributes() ?> aria-describedby="x_file_17_help">
<?= $Page->file_17->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->file_17->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->file_18->Visible) { // file_18 ?>
    <div id="r_file_18" class="form-group row">
        <label id="elh_apbk_file_18" for="x_file_18" class="<?= $Page->LeftColumnClass ?>"><?= $Page->file_18->caption() ?><?= $Page->file_18->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->file_18->cellAttributes() ?>>
<span id="el_apbk_file_18">
<input type="<?= $Page->file_18->getInputTextType() ?>" data-table="apbk" data-field="x_file_18" name="x_file_18" id="x_file_18" size="30" maxlength="200" placeholder="<?= HtmlEncode($Page->file_18->getPlaceHolder()) ?>" value="<?= $Page->file_18->EditValue ?>"<?= $Page->file_18->editAttributes() ?> aria-describedby="x_file_18_help">
<?= $Page->file_18->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->file_18->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->file_19->Visible) { // file_19 ?>
    <div id="r_file_19" class="form-group row">
        <label id="elh_apbk_file_19" for="x_file_19" class="<?= $Page->LeftColumnClass ?>"><?= $Page->file_19->caption() ?><?= $Page->file_19->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->file_19->cellAttributes() ?>>
<span id="el_apbk_file_19">
<input type="<?= $Page->file_19->getInputTextType() ?>" data-table="apbk" data-field="x_file_19" name="x_file_19" id="x_file_19" size="30" maxlength="200" placeholder="<?= HtmlEncode($Page->file_19->getPlaceHolder()) ?>" value="<?= $Page->file_19->EditValue ?>"<?= $Page->file_19->editAttributes() ?> aria-describedby="x_file_19_help">
<?= $Page->file_19->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->file_19->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->file_20->Visible) { // file_20 ?>
    <div id="r_file_20" class="form-group row">
        <label id="elh_apbk_file_20" for="x_file_20" class="<?= $Page->LeftColumnClass ?>"><?= $Page->file_20->caption() ?><?= $Page->file_20->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->file_20->cellAttributes() ?>>
<span id="el_apbk_file_20">
<input type="<?= $Page->file_20->getInputTextType() ?>" data-table="apbk" data-field="x_file_20" name="x_file_20" id="x_file_20" size="30" maxlength="200" placeholder="<?= HtmlEncode($Page->file_20->getPlaceHolder()) ?>" value="<?= $Page->file_20->EditValue ?>"<?= $Page->file_20->editAttributes() ?> aria-describedby="x_file_20_help">
<?= $Page->file_20->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->file_20->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->file_21->Visible) { // file_21 ?>
    <div id="r_file_21" class="form-group row">
        <label id="elh_apbk_file_21" for="x_file_21" class="<?= $Page->LeftColumnClass ?>"><?= $Page->file_21->caption() ?><?= $Page->file_21->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->file_21->cellAttributes() ?>>
<span id="el_apbk_file_21">
<input type="<?= $Page->file_21->getInputTextType() ?>" data-table="apbk" data-field="x_file_21" name="x_file_21" id="x_file_21" size="30" maxlength="200" placeholder="<?= HtmlEncode($Page->file_21->getPlaceHolder()) ?>" value="<?= $Page->file_21->EditValue ?>"<?= $Page->file_21->editAttributes() ?> aria-describedby="x_file_21_help">
<?= $Page->file_21->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->file_21->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->file_22->Visible) { // file_22 ?>
    <div id="r_file_22" class="form-group row">
        <label id="elh_apbk_file_22" for="x_file_22" class="<?= $Page->LeftColumnClass ?>"><?= $Page->file_22->caption() ?><?= $Page->file_22->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->file_22->cellAttributes() ?>>
<span id="el_apbk_file_22">
<input type="<?= $Page->file_22->getInputTextType() ?>" data-table="apbk" data-field="x_file_22" name="x_file_22" id="x_file_22" size="30" maxlength="200" placeholder="<?= HtmlEncode($Page->file_22->getPlaceHolder()) ?>" value="<?= $Page->file_22->EditValue ?>"<?= $Page->file_22->editAttributes() ?> aria-describedby="x_file_22_help">
<?= $Page->file_22->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->file_22->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->file_23->Visible) { // file_23 ?>
    <div id="r_file_23" class="form-group row">
        <label id="elh_apbk_file_23" for="x_file_23" class="<?= $Page->LeftColumnClass ?>"><?= $Page->file_23->caption() ?><?= $Page->file_23->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->file_23->cellAttributes() ?>>
<span id="el_apbk_file_23">
<input type="<?= $Page->file_23->getInputTextType() ?>" data-table="apbk" data-field="x_file_23" name="x_file_23" id="x_file_23" size="30" maxlength="200" placeholder="<?= HtmlEncode($Page->file_23->getPlaceHolder()) ?>" value="<?= $Page->file_23->EditValue ?>"<?= $Page->file_23->editAttributes() ?> aria-describedby="x_file_23_help">
<?= $Page->file_23->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->file_23->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->file_24->Visible) { // file_24 ?>
    <div id="r_file_24" class="form-group row">
        <label id="elh_apbk_file_24" for="x_file_24" class="<?= $Page->LeftColumnClass ?>"><?= $Page->file_24->caption() ?><?= $Page->file_24->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->file_24->cellAttributes() ?>>
<span id="el_apbk_file_24">
<input type="<?= $Page->file_24->getInputTextType() ?>" data-table="apbk" data-field="x_file_24" name="x_file_24" id="x_file_24" size="30" maxlength="200" placeholder="<?= HtmlEncode($Page->file_24->getPlaceHolder()) ?>" value="<?= $Page->file_24->EditValue ?>"<?= $Page->file_24->editAttributes() ?> aria-describedby="x_file_24_help">
<?= $Page->file_24->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->file_24->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->status->Visible) { // status ?>
    <div id="r_status" class="form-group row">
        <label id="elh_apbk_status" for="x_status" class="<?= $Page->LeftColumnClass ?>"><?= $Page->status->caption() ?><?= $Page->status->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->status->cellAttributes() ?>>
<span id="el_apbk_status">
<input type="<?= $Page->status->getInputTextType() ?>" data-table="apbk" data-field="x_status" name="x_status" id="x_status" size="30" placeholder="<?= HtmlEncode($Page->status->getPlaceHolder()) ?>" value="<?= $Page->status->EditValue ?>"<?= $Page->status->editAttributes() ?> aria-describedby="x_status_help">
<?= $Page->status->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->status->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->idd_user->Visible) { // idd_user ?>
    <div id="r_idd_user" class="form-group row">
        <label id="elh_apbk_idd_user" for="x_idd_user" class="<?= $Page->LeftColumnClass ?>"><?= $Page->idd_user->caption() ?><?= $Page->idd_user->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->idd_user->cellAttributes() ?>>
<?php if (!$Security->isAdmin() && $Security->isLoggedIn() && !$Page->userIDAllow("add")) { // Non system admin ?>
<span id="el_apbk_idd_user">
<span<?= $Page->idd_user->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->idd_user->getDisplayValue($Page->idd_user->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="apbk" data-field="x_idd_user" data-hidden="1" name="x_idd_user" id="x_idd_user" value="<?= HtmlEncode($Page->idd_user->CurrentValue) ?>">
<?php } else { ?>
<span id="el_apbk_idd_user">
<input type="<?= $Page->idd_user->getInputTextType() ?>" data-table="apbk" data-field="x_idd_user" name="x_idd_user" id="x_idd_user" size="30" placeholder="<?= HtmlEncode($Page->idd_user->getPlaceHolder()) ?>" value="<?= $Page->idd_user->EditValue ?>"<?= $Page->idd_user->editAttributes() ?> aria-describedby="x_idd_user_help">
<?= $Page->idd_user->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->idd_user->getErrorMessage() ?></div>
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
    ew.addEventHandlers("apbk");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
