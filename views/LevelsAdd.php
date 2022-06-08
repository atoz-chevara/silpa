<?php

namespace PHPMaker2021\silpa;

// Page object
$LevelsAdd = &$Page;
?>
<script>
var currentForm, currentPageID;
var flevelsadd;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "add";
    flevelsadd = currentForm = new ew.Form("flevelsadd", "add");

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "levels")) ?>,
        fields = currentTable.fields;
    if (!ew.vars.tables.levels)
        ew.vars.tables.levels = currentTable;
    flevelsadd.addFields([
        ["id_level", [fields.id_level.visible && fields.id_level.required ? ew.Validators.required(fields.id_level.caption) : null, ew.Validators.userLevelId, ew.Validators.integer], fields.id_level.isInvalid],
        ["level_name", [fields.level_name.visible && fields.level_name.required ? ew.Validators.required(fields.level_name.caption) : null, ew.Validators.userLevelName('id_level')], fields.level_name.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = flevelsadd,
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
    flevelsadd.validate = function () {
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
    flevelsadd.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    flevelsadd.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    loadjs.done("flevelsadd");
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
<form name="flevelsadd" id="flevelsadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="levels">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->id_level->Visible) { // id_level ?>
    <div id="r_id_level" class="form-group row">
        <label id="elh_levels_id_level" for="x_id_level" class="<?= $Page->LeftColumnClass ?>"><?= $Page->id_level->caption() ?><?= $Page->id_level->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->id_level->cellAttributes() ?>>
<span id="el_levels_id_level">
<input type="<?= $Page->id_level->getInputTextType() ?>" data-table="levels" data-field="x_id_level" name="x_id_level" id="x_id_level" size="30" placeholder="<?= HtmlEncode($Page->id_level->getPlaceHolder()) ?>" value="<?= $Page->id_level->EditValue ?>"<?= $Page->id_level->editAttributes() ?> aria-describedby="x_id_level_help">
<?= $Page->id_level->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->id_level->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->level_name->Visible) { // level_name ?>
    <div id="r_level_name" class="form-group row">
        <label id="elh_levels_level_name" for="x_level_name" class="<?= $Page->LeftColumnClass ?>"><?= $Page->level_name->caption() ?><?= $Page->level_name->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->level_name->cellAttributes() ?>>
<span id="el_levels_level_name">
<input type="<?= $Page->level_name->getInputTextType() ?>" data-table="levels" data-field="x_level_name" name="x_level_name" id="x_level_name" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->level_name->getPlaceHolder()) ?>" value="<?= $Page->level_name->EditValue ?>"<?= $Page->level_name->editAttributes() ?> aria-describedby="x_level_name_help">
<?= $Page->level_name->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->level_name->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
    <!-- row for permission values -->
    <div id="rp_permission" class="form-group row">
        <label id="elh_permission" class="<?= $Page->LeftColumnClass ?>"><?= HtmlTitle($Language->phrase("Permission")) ?></label>
        <div class="<?= $Page->RightColumnClass ?>">
            <div class="custom-control custom-checkbox custom-control-inline">
                <input type="checkbox" class="custom-control-input" name="x__AllowAdd" id="Add" value="<?= Config("ALLOW_ADD") ?>"><label class="custom-control-label" for="Add"><?= $Language->phrase("PermissionAdd") ?></label>
            </div>
            <div class="custom-control custom-checkbox custom-control-inline">
                <input type="checkbox" class="custom-control-input" name="x__AllowDelete" id="Delete" value="<?= Config("ALLOW_DELETE") ?>"><label class="custom-control-label" for="Delete"><?= $Language->phrase("PermissionDelete") ?></label>
            </div>
            <div class="custom-control custom-checkbox custom-control-inline">
                <input type="checkbox" class="custom-control-input" name="x__AllowEdit" id="Edit" value="<?= Config("ALLOW_EDIT") ?>"><label class="custom-control-label" for="Edit"><?= $Language->phrase("PermissionEdit") ?></label>
            </div>
            <div class="custom-control custom-checkbox custom-control-inline">
                <input type="checkbox" class="custom-control-input" name="x__AllowList" id="List" value="<?= Config("ALLOW_LIST") ?>"><label class="custom-control-label" for="List"><?= $Language->phrase("PermissionList") ?></label>
            </div>
            <div class="custom-control custom-checkbox custom-control-inline">
                <input type="checkbox" class="custom-control-input" name="x__AllowLookup" id="Lookup" value="<?= Config("ALLOW_LOOKUP") ?>"><label class="custom-control-label" for="Lookup"><?= $Language->phrase("PermissionLookup") ?></label>
            </div>
            <div class="custom-control custom-checkbox custom-control-inline">
                <input type="checkbox" class="custom-control-input" name="x__AllowView" id="View" value="<?= Config("ALLOW_VIEW") ?>"><label class="custom-control-label" for="View"><?= $Language->phrase("PermissionView") ?></label>
            </div>
            <div class="custom-control custom-checkbox custom-control-inline">
                <input type="checkbox" class="custom-control-input" name="x__AllowSearch" id="Search" value="<?= Config("ALLOW_SEARCH") ?>"><label class="custom-control-label" for="Search"><?= $Language->phrase("PermissionSearch") ?></label>
            </div>
            <div class="custom-control custom-checkbox custom-control-inline">
                <input type="checkbox" class="custom-control-input" name="x__AllowImport" id="Import" value="<?= Config("ALLOW_IMPORT") ?>"><label class="custom-control-label" for="Import"><?= $Language->phrase("PermissionImport") ?></label>
            </div>
<?php if (IsSysAdmin()) { ?>
            <div class="custom-control custom-checkbox custom-control-inline">
                <input type="checkbox" class="custom-control-input" name="x__AllowAdmin" id="Admin" value="<?= Config("ALLOW_ADMIN") ?>"><label class="custom-control-label" for="Admin"><?= $Language->phrase("PermissionAdmin") ?></label>
            </div>
<?php } ?>
        </div>
    </div>
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
    ew.addEventHandlers("levels");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
