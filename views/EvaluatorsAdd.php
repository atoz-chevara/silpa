<?php

namespace PHPMaker2021\silpa;

// Page object
$EvaluatorsAdd = &$Page;
?>
<script>
var currentForm, currentPageID;
var fevaluatorsadd;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "add";
    fevaluatorsadd = currentForm = new ew.Form("fevaluatorsadd", "add");

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "evaluators")) ?>,
        fields = currentTable.fields;
    if (!ew.vars.tables.evaluators)
        ew.vars.tables.evaluators = currentTable;
    fevaluatorsadd.addFields([
        ["nip", [fields.nip.visible && fields.nip.required ? ew.Validators.required(fields.nip.caption) : null], fields.nip.isInvalid],
        ["nama_lengkap", [fields.nama_lengkap.visible && fields.nama_lengkap.required ? ew.Validators.required(fields.nama_lengkap.caption) : null], fields.nama_lengkap.isInvalid],
        ["alamat", [fields.alamat.visible && fields.alamat.required ? ew.Validators.required(fields.alamat.caption) : null], fields.alamat.isInvalid],
        ["wilayah", [fields.wilayah.visible && fields.wilayah.required ? ew.Validators.required(fields.wilayah.caption) : null], fields.wilayah.isInvalid],
        ["idd_user", [fields.idd_user.visible && fields.idd_user.required ? ew.Validators.required(fields.idd_user.caption) : null], fields.idd_user.isInvalid],
        ["no_telepon", [fields.no_telepon.visible && fields.no_telepon.required ? ew.Validators.required(fields.no_telepon.caption) : null], fields.no_telepon.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fevaluatorsadd,
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
    fevaluatorsadd.validate = function () {
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
    fevaluatorsadd.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fevaluatorsadd.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fevaluatorsadd.lists.wilayah = <?= $Page->wilayah->toClientList($Page) ?>;
    fevaluatorsadd.lists.idd_user = <?= $Page->idd_user->toClientList($Page) ?>;
    loadjs.done("fevaluatorsadd");
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
<form name="fevaluatorsadd" id="fevaluatorsadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="evaluators">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->nip->Visible) { // nip ?>
    <div id="r_nip" class="form-group row">
        <label id="elh_evaluators_nip" for="x_nip" class="<?= $Page->LeftColumnClass ?>"><?= $Page->nip->caption() ?><?= $Page->nip->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->nip->cellAttributes() ?>>
<span id="el_evaluators_nip">
<input type="<?= $Page->nip->getInputTextType() ?>" data-table="evaluators" data-field="x_nip" name="x_nip" id="x_nip" size="30" maxlength="200" placeholder="<?= HtmlEncode($Page->nip->getPlaceHolder()) ?>" value="<?= $Page->nip->EditValue ?>"<?= $Page->nip->editAttributes() ?> aria-describedby="x_nip_help">
<?= $Page->nip->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->nip->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->nama_lengkap->Visible) { // nama_lengkap ?>
    <div id="r_nama_lengkap" class="form-group row">
        <label id="elh_evaluators_nama_lengkap" for="x_nama_lengkap" class="<?= $Page->LeftColumnClass ?>"><?= $Page->nama_lengkap->caption() ?><?= $Page->nama_lengkap->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->nama_lengkap->cellAttributes() ?>>
<span id="el_evaluators_nama_lengkap">
<input type="<?= $Page->nama_lengkap->getInputTextType() ?>" data-table="evaluators" data-field="x_nama_lengkap" name="x_nama_lengkap" id="x_nama_lengkap" size="30" maxlength="200" placeholder="<?= HtmlEncode($Page->nama_lengkap->getPlaceHolder()) ?>" value="<?= $Page->nama_lengkap->EditValue ?>"<?= $Page->nama_lengkap->editAttributes() ?> aria-describedby="x_nama_lengkap_help">
<?= $Page->nama_lengkap->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->nama_lengkap->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->alamat->Visible) { // alamat ?>
    <div id="r_alamat" class="form-group row">
        <label id="elh_evaluators_alamat" for="x_alamat" class="<?= $Page->LeftColumnClass ?>"><?= $Page->alamat->caption() ?><?= $Page->alamat->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->alamat->cellAttributes() ?>>
<span id="el_evaluators_alamat">
<textarea data-table="evaluators" data-field="x_alamat" name="x_alamat" id="x_alamat" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->alamat->getPlaceHolder()) ?>"<?= $Page->alamat->editAttributes() ?> aria-describedby="x_alamat_help"><?= $Page->alamat->EditValue ?></textarea>
<?= $Page->alamat->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->alamat->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->wilayah->Visible) { // wilayah ?>
    <div id="r_wilayah" class="form-group row">
        <label id="elh_evaluators_wilayah" for="x_wilayah" class="<?= $Page->LeftColumnClass ?>"><?= $Page->wilayah->caption() ?><?= $Page->wilayah->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->wilayah->cellAttributes() ?>>
<span id="el_evaluators_wilayah">
    <select
        id="x_wilayah"
        name="x_wilayah"
        class="form-control ew-select<?= $Page->wilayah->isInvalidClass() ?>"
        data-select2-id="evaluators_x_wilayah"
        data-table="evaluators"
        data-field="x_wilayah"
        data-value-separator="<?= $Page->wilayah->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->wilayah->getPlaceHolder()) ?>"
        <?= $Page->wilayah->editAttributes() ?>>
        <?= $Page->wilayah->selectOptionListHtml("x_wilayah") ?>
    </select>
    <?= $Page->wilayah->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->wilayah->getErrorMessage() ?></div>
<?= $Page->wilayah->Lookup->getParamTag($Page, "p_x_wilayah") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='evaluators_x_wilayah']"),
        options = { name: "x_wilayah", selectId: "evaluators_x_wilayah", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.evaluators.fields.wilayah.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->idd_user->Visible) { // idd_user ?>
    <div id="r_idd_user" class="form-group row">
        <label id="elh_evaluators_idd_user" for="x_idd_user" class="<?= $Page->LeftColumnClass ?>"><?= $Page->idd_user->caption() ?><?= $Page->idd_user->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->idd_user->cellAttributes() ?>>
<?php if (!$Security->isAdmin() && $Security->isLoggedIn() && !$Page->userIDAllow("add")) { // Non system admin ?>
<span id="el_evaluators_idd_user">
<span<?= $Page->idd_user->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->idd_user->getDisplayValue($Page->idd_user->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="evaluators" data-field="x_idd_user" data-hidden="1" name="x_idd_user" id="x_idd_user" value="<?= HtmlEncode($Page->idd_user->CurrentValue) ?>">
<?php } else { ?>
<span id="el_evaluators_idd_user">
    <select
        id="x_idd_user"
        name="x_idd_user"
        class="form-control ew-select<?= $Page->idd_user->isInvalidClass() ?>"
        data-select2-id="evaluators_x_idd_user"
        data-table="evaluators"
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
    var el = document.querySelector("select[data-select2-id='evaluators_x_idd_user']"),
        options = { name: "x_idd_user", selectId: "evaluators_x_idd_user", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.evaluators.fields.idd_user.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->no_telepon->Visible) { // no_telepon ?>
    <div id="r_no_telepon" class="form-group row">
        <label id="elh_evaluators_no_telepon" for="x_no_telepon" class="<?= $Page->LeftColumnClass ?>"><?= $Page->no_telepon->caption() ?><?= $Page->no_telepon->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->no_telepon->cellAttributes() ?>>
<span id="el_evaluators_no_telepon">
<input type="<?= $Page->no_telepon->getInputTextType() ?>" data-table="evaluators" data-field="x_no_telepon" name="x_no_telepon" id="x_no_telepon" size="30" maxlength="25" placeholder="<?= HtmlEncode($Page->no_telepon->getPlaceHolder()) ?>" value="<?= $Page->no_telepon->EditValue ?>"<?= $Page->no_telepon->editAttributes() ?> aria-describedby="x_no_telepon_help">
<?= $Page->no_telepon->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->no_telepon->getErrorMessage() ?></div>
</span>
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
    ew.addEventHandlers("evaluators");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
