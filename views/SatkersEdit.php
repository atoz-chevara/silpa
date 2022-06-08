<?php

namespace PHPMaker2021\silpa;

// Page object
$SatkersEdit = &$Page;
?>
<script>
var currentForm, currentPageID;
var fsatkersedit;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "edit";
    fsatkersedit = currentForm = new ew.Form("fsatkersedit", "edit");

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "satkers")) ?>,
        fields = currentTable.fields;
    if (!ew.vars.tables.satkers)
        ew.vars.tables.satkers = currentTable;
    fsatkersedit.addFields([
        ["kode_pemda", [fields.kode_pemda.visible && fields.kode_pemda.required ? ew.Validators.required(fields.kode_pemda.caption) : null], fields.kode_pemda.isInvalid],
        ["kode_satker", [fields.kode_satker.visible && fields.kode_satker.required ? ew.Validators.required(fields.kode_satker.caption) : null, ew.Validators.integer], fields.kode_satker.isInvalid],
        ["nama_satker", [fields.nama_satker.visible && fields.nama_satker.required ? ew.Validators.required(fields.nama_satker.caption) : null], fields.nama_satker.isInvalid],
        ["wilayah", [fields.wilayah.visible && fields.wilayah.required ? ew.Validators.required(fields.wilayah.caption) : null], fields.wilayah.isInvalid],
        ["idd_user", [fields.idd_user.visible && fields.idd_user.required ? ew.Validators.required(fields.idd_user.caption) : null], fields.idd_user.isInvalid],
        ["no_telepon", [fields.no_telepon.visible && fields.no_telepon.required ? ew.Validators.required(fields.no_telepon.caption) : null], fields.no_telepon.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fsatkersedit,
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
    fsatkersedit.validate = function () {
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
    fsatkersedit.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fsatkersedit.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fsatkersedit.lists.wilayah = <?= $Page->wilayah->toClientList($Page) ?>;
    fsatkersedit.lists.idd_user = <?= $Page->idd_user->toClientList($Page) ?>;
    loadjs.done("fsatkersedit");
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
<form name="fsatkersedit" id="fsatkersedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="satkers">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->kode_pemda->Visible) { // kode_pemda ?>
    <div id="r_kode_pemda" class="form-group row">
        <label id="elh_satkers_kode_pemda" for="x_kode_pemda" class="<?= $Page->LeftColumnClass ?>"><?= $Page->kode_pemda->caption() ?><?= $Page->kode_pemda->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->kode_pemda->cellAttributes() ?>>
<span id="el_satkers_kode_pemda">
<input type="<?= $Page->kode_pemda->getInputTextType() ?>" data-table="satkers" data-field="x_kode_pemda" name="x_kode_pemda" id="x_kode_pemda" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->kode_pemda->getPlaceHolder()) ?>" value="<?= $Page->kode_pemda->EditValue ?>"<?= $Page->kode_pemda->editAttributes() ?> aria-describedby="x_kode_pemda_help">
<?= $Page->kode_pemda->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->kode_pemda->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->kode_satker->Visible) { // kode_satker ?>
    <div id="r_kode_satker" class="form-group row">
        <label id="elh_satkers_kode_satker" for="x_kode_satker" class="<?= $Page->LeftColumnClass ?>"><?= $Page->kode_satker->caption() ?><?= $Page->kode_satker->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->kode_satker->cellAttributes() ?>>
<span id="el_satkers_kode_satker">
<input type="<?= $Page->kode_satker->getInputTextType() ?>" data-table="satkers" data-field="x_kode_satker" name="x_kode_satker" id="x_kode_satker" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->kode_satker->getPlaceHolder()) ?>" value="<?= $Page->kode_satker->EditValue ?>"<?= $Page->kode_satker->editAttributes() ?> aria-describedby="x_kode_satker_help">
<?= $Page->kode_satker->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->kode_satker->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->nama_satker->Visible) { // nama_satker ?>
    <div id="r_nama_satker" class="form-group row">
        <label id="elh_satkers_nama_satker" for="x_nama_satker" class="<?= $Page->LeftColumnClass ?>"><?= $Page->nama_satker->caption() ?><?= $Page->nama_satker->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->nama_satker->cellAttributes() ?>>
<span id="el_satkers_nama_satker">
<input type="<?= $Page->nama_satker->getInputTextType() ?>" data-table="satkers" data-field="x_nama_satker" name="x_nama_satker" id="x_nama_satker" size="30" maxlength="200" placeholder="<?= HtmlEncode($Page->nama_satker->getPlaceHolder()) ?>" value="<?= $Page->nama_satker->EditValue ?>"<?= $Page->nama_satker->editAttributes() ?> aria-describedby="x_nama_satker_help">
<?= $Page->nama_satker->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->nama_satker->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->wilayah->Visible) { // wilayah ?>
    <div id="r_wilayah" class="form-group row">
        <label id="elh_satkers_wilayah" for="x_wilayah" class="<?= $Page->LeftColumnClass ?>"><?= $Page->wilayah->caption() ?><?= $Page->wilayah->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->wilayah->cellAttributes() ?>>
<span id="el_satkers_wilayah">
<div class="input-group flex-nowrap">
    <select
        id="x_wilayah"
        name="x_wilayah"
        class="form-control ew-select<?= $Page->wilayah->isInvalidClass() ?>"
        data-select2-id="satkers_x_wilayah"
        data-table="satkers"
        data-field="x_wilayah"
        data-value-separator="<?= $Page->wilayah->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->wilayah->getPlaceHolder()) ?>"
        <?= $Page->wilayah->editAttributes() ?>>
        <?= $Page->wilayah->selectOptionListHtml("x_wilayah") ?>
    </select>
    <?php if (AllowAdd(CurrentProjectID() . "wilayah") && !$Page->wilayah->ReadOnly) { ?>
    <div class="input-group-append"><button type="button" class="btn btn-default ew-add-opt-btn" id="aol_x_wilayah" title="<?= HtmlTitle($Language->phrase("AddLink")) . "&nbsp;" . $Page->wilayah->caption() ?>" data-title="<?= $Page->wilayah->caption() ?>" onclick="ew.addOptionDialogShow({lnk:this,el:'x_wilayah',url:'<?= GetUrl("wilayahaddopt") ?>'});"><i class="fas fa-plus ew-icon"></i></button></div>
    <?php } ?>
</div>
<?= $Page->wilayah->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->wilayah->getErrorMessage() ?></div>
<?= $Page->wilayah->Lookup->getParamTag($Page, "p_x_wilayah") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='satkers_x_wilayah']"),
        options = { name: "x_wilayah", selectId: "satkers_x_wilayah", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.satkers.fields.wilayah.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->idd_user->Visible) { // idd_user ?>
    <div id="r_idd_user" class="form-group row">
        <label id="elh_satkers_idd_user" for="x_idd_user" class="<?= $Page->LeftColumnClass ?>"><?= $Page->idd_user->caption() ?><?= $Page->idd_user->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->idd_user->cellAttributes() ?>>
<span id="el_satkers_idd_user">
    <select
        id="x_idd_user"
        name="x_idd_user"
        class="form-control ew-select<?= $Page->idd_user->isInvalidClass() ?>"
        data-select2-id="satkers_x_idd_user"
        data-table="satkers"
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
    var el = document.querySelector("select[data-select2-id='satkers_x_idd_user']"),
        options = { name: "x_idd_user", selectId: "satkers_x_idd_user", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.satkers.fields.idd_user.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->no_telepon->Visible) { // no_telepon ?>
    <div id="r_no_telepon" class="form-group row">
        <label id="elh_satkers_no_telepon" for="x_no_telepon" class="<?= $Page->LeftColumnClass ?>"><?= $Page->no_telepon->caption() ?><?= $Page->no_telepon->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->no_telepon->cellAttributes() ?>>
<span id="el_satkers_no_telepon">
<input type="<?= $Page->no_telepon->getInputTextType() ?>" data-table="satkers" data-field="x_no_telepon" name="x_no_telepon" id="x_no_telepon" size="30" maxlength="25" placeholder="<?= HtmlEncode($Page->no_telepon->getPlaceHolder()) ?>" value="<?= $Page->no_telepon->EditValue ?>"<?= $Page->no_telepon->editAttributes() ?> aria-describedby="x_no_telepon_help">
<?= $Page->no_telepon->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->no_telepon->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
    <input type="hidden" data-table="satkers" data-field="x_idd_satker" data-hidden="1" name="x_idd_satker" id="x_idd_satker" value="<?= HtmlEncode($Page->idd_satker->CurrentValue) ?>">
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
    ew.addEventHandlers("satkers");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
