<?php

namespace PHPMaker2021\silpa;

// Page object
$EvaluatorsList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var fevaluatorslist;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "list";
    fevaluatorslist = currentForm = new ew.Form("fevaluatorslist", "list");
    fevaluatorslist.formKeyCountName = '<?= $Page->FormKeyCountName ?>';

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "evaluators")) ?>,
        fields = currentTable.fields;
    if (!ew.vars.tables.evaluators)
        ew.vars.tables.evaluators = currentTable;
    fevaluatorslist.addFields([
        ["nip", [fields.nip.visible && fields.nip.required ? ew.Validators.required(fields.nip.caption) : null], fields.nip.isInvalid],
        ["nama_lengkap", [fields.nama_lengkap.visible && fields.nama_lengkap.required ? ew.Validators.required(fields.nama_lengkap.caption) : null], fields.nama_lengkap.isInvalid],
        ["wilayah", [fields.wilayah.visible && fields.wilayah.required ? ew.Validators.required(fields.wilayah.caption) : null], fields.wilayah.isInvalid],
        ["idd_user", [fields.idd_user.visible && fields.idd_user.required ? ew.Validators.required(fields.idd_user.caption) : null], fields.idd_user.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fevaluatorslist,
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
    fevaluatorslist.validate = function () {
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
            var checkrow = (gridinsert) ? !this.emptyRow(rowIndex) : true;
            if (checkrow) {
                addcnt++;

            // Validate fields
            if (!this.validateFields(rowIndex))
                return false;

            // Call Form_CustomValidate event
            if (!this.customValidate(fobj)) {
                this.focus();
                return false;
            }
            } // End Grid Add checking
        }
        if (gridinsert && addcnt == 0) { // No row added
            ew.alert(ew.language.phrase("NoAddRecord"));
            return false;
        }
        return true;
    }

    // Check empty row
    fevaluatorslist.emptyRow = function (rowIndex) {
        var fobj = this.getForm();
        if (ew.valueChanged(fobj, rowIndex, "nip", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "nama_lengkap", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "wilayah", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "idd_user", false))
            return false;
        return true;
    }

    // Form_CustomValidate
    fevaluatorslist.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fevaluatorslist.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fevaluatorslist.lists.wilayah = <?= $Page->wilayah->toClientList($Page) ?>;
    fevaluatorslist.lists.idd_user = <?= $Page->idd_user->toClientList($Page) ?>;
    loadjs.done("fevaluatorslist");
});
var fevaluatorslistsrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object for search
    fevaluatorslistsrch = currentSearchForm = new ew.Form("fevaluatorslistsrch");

    // Dynamic selection lists

    // Filters
    fevaluatorslistsrch.filterList = <?= $Page->getFilterList() ?>;
    loadjs.done("fevaluatorslistsrch");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php } ?>
<?php if (!$Page->isExport()) { ?>
<div class="btn-toolbar ew-toolbar">
<?php if ($Page->TotalRecords > 0 && $Page->ExportOptions->visible()) { ?>
<?php $Page->ExportOptions->render("body") ?>
<?php } ?>
<?php if ($Page->ImportOptions->visible()) { ?>
<?php $Page->ImportOptions->render("body") ?>
<?php } ?>
<?php if ($Page->SearchOptions->visible()) { ?>
<?php $Page->SearchOptions->render("body") ?>
<?php } ?>
<?php if ($Page->FilterOptions->visible()) { ?>
<?php $Page->FilterOptions->render("body") ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php
$Page->renderOtherOptions();
?>
<?php if ($Security->canSearch()) { ?>
<?php if (!$Page->isExport() && !$Page->CurrentAction) { ?>
<form name="fevaluatorslistsrch" id="fevaluatorslistsrch" class="form-inline ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>">
<div id="fevaluatorslistsrch-search-panel" class="<?= $Page->SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="evaluators">
    <div class="ew-extended-search">
<div id="xsr_<?= $Page->SearchRowCount + 1 ?>" class="ew-row d-sm-flex">
    <div class="ew-quick-search input-group">
        <input type="text" name="<?= Config("TABLE_BASIC_SEARCH") ?>" id="<?= Config("TABLE_BASIC_SEARCH") ?>" class="form-control" value="<?= HtmlEncode($Page->BasicSearch->getKeyword()) ?>" placeholder="<?= HtmlEncode($Language->phrase("Search")) ?>">
        <input type="hidden" name="<?= Config("TABLE_BASIC_SEARCH_TYPE") ?>" id="<?= Config("TABLE_BASIC_SEARCH_TYPE") ?>" value="<?= HtmlEncode($Page->BasicSearch->getType()) ?>">
        <div class="input-group-append">
            <button class="btn btn-primary" name="btn-submit" id="btn-submit" type="submit"><?= $Language->phrase("SearchBtn") ?></button>
            <button type="button" data-toggle="dropdown" class="btn btn-primary dropdown-toggle dropdown-toggle-split" aria-haspopup="true" aria-expanded="false"><span id="searchtype"><?= $Page->BasicSearch->getTypeNameShort() ?></span></button>
            <div class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item<?php if ($Page->BasicSearch->getType() == "") { ?> active<?php } ?>" href="#" onclick="return ew.setSearchType(this);"><?= $Language->phrase("QuickSearchAuto") ?></a>
                <a class="dropdown-item<?php if ($Page->BasicSearch->getType() == "=") { ?> active<?php } ?>" href="#" onclick="return ew.setSearchType(this, '=');"><?= $Language->phrase("QuickSearchExact") ?></a>
                <a class="dropdown-item<?php if ($Page->BasicSearch->getType() == "AND") { ?> active<?php } ?>" href="#" onclick="return ew.setSearchType(this, 'AND');"><?= $Language->phrase("QuickSearchAll") ?></a>
                <a class="dropdown-item<?php if ($Page->BasicSearch->getType() == "OR") { ?> active<?php } ?>" href="#" onclick="return ew.setSearchType(this, 'OR');"><?= $Language->phrase("QuickSearchAny") ?></a>
            </div>
        </div>
    </div>
</div>
    </div><!-- /.ew-extended-search -->
</div><!-- /.ew-search-panel -->
</form>
<?php } ?>
<?php } ?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<?php if ($Page->TotalRecords > 0 || $Page->CurrentAction) { ?>
<div class="card ew-card ew-grid<?php if ($Page->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> evaluators">
<form name="fevaluatorslist" id="fevaluatorslist" class="form-inline ew-form ew-list-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="evaluators">
<div id="gmp_evaluators" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<?php if ($Page->TotalRecords > 0 || $Page->isAdd() || $Page->isCopy() || $Page->isGridEdit()) { ?>
<table id="tbl_evaluatorslist" class="table ew-table"><!-- .ew-table -->
<thead>
    <tr class="ew-table-header">
<?php
// Header row
$Page->RowType = ROWTYPE_HEADER;

// Render list options
$Page->renderListOptions();

// Render list options (header, left)
$Page->ListOptions->render("header", "left");
?>
<?php if ($Page->nip->Visible) { // nip ?>
        <th data-name="nip" class="<?= $Page->nip->headerCellClass() ?>"><div id="elh_evaluators_nip" class="evaluators_nip"><?= $Page->renderSort($Page->nip) ?></div></th>
<?php } ?>
<?php if ($Page->nama_lengkap->Visible) { // nama_lengkap ?>
        <th data-name="nama_lengkap" class="<?= $Page->nama_lengkap->headerCellClass() ?>"><div id="elh_evaluators_nama_lengkap" class="evaluators_nama_lengkap"><?= $Page->renderSort($Page->nama_lengkap) ?></div></th>
<?php } ?>
<?php if ($Page->wilayah->Visible) { // wilayah ?>
        <th data-name="wilayah" class="<?= $Page->wilayah->headerCellClass() ?>"><div id="elh_evaluators_wilayah" class="evaluators_wilayah"><?= $Page->renderSort($Page->wilayah) ?></div></th>
<?php } ?>
<?php if ($Page->idd_user->Visible) { // idd_user ?>
        <th data-name="idd_user" class="<?= $Page->idd_user->headerCellClass() ?>"><div id="elh_evaluators_idd_user" class="evaluators_idd_user"><?= $Page->renderSort($Page->idd_user) ?></div></th>
<?php } ?>
<?php
// Render list options (header, right)
$Page->ListOptions->render("header", "right");
?>
    </tr>
</thead>
<tbody>
<?php
    if ($Page->isAdd() || $Page->isCopy()) {
        $Page->RowIndex = 0;
        $Page->KeyCount = $Page->RowIndex;
        if ($Page->isCopy() && !$Page->loadRow())
            $Page->CurrentAction = "add";
        if ($Page->isAdd())
            $Page->loadRowValues();
        if ($Page->EventCancelled) // Insert failed
            $Page->restoreFormValues(); // Restore form values

        // Set row properties
        $Page->resetAttributes();
        $Page->RowAttrs->merge(["data-rowindex" => 0, "id" => "r0_evaluators", "data-rowtype" => ROWTYPE_ADD]);
        $Page->RowType = ROWTYPE_ADD;

        // Render row
        $Page->renderRow();

        // Render list options
        $Page->renderListOptions();
        $Page->StartRowCount = 0;
?>
    <tr <?= $Page->rowAttributes() ?>>
<?php
// Render list options (body, left)
$Page->ListOptions->render("body", "left", $Page->RowCount);
?>
    <?php if ($Page->nip->Visible) { // nip ?>
        <td data-name="nip">
<span id="el<?= $Page->RowCount ?>_evaluators_nip" class="form-group evaluators_nip">
<input type="<?= $Page->nip->getInputTextType() ?>" data-table="evaluators" data-field="x_nip" name="x<?= $Page->RowIndex ?>_nip" id="x<?= $Page->RowIndex ?>_nip" size="30" maxlength="200" placeholder="<?= HtmlEncode($Page->nip->getPlaceHolder()) ?>" value="<?= $Page->nip->EditValue ?>"<?= $Page->nip->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->nip->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="evaluators" data-field="x_nip" data-hidden="1" name="o<?= $Page->RowIndex ?>_nip" id="o<?= $Page->RowIndex ?>_nip" value="<?= HtmlEncode($Page->nip->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Page->nama_lengkap->Visible) { // nama_lengkap ?>
        <td data-name="nama_lengkap">
<span id="el<?= $Page->RowCount ?>_evaluators_nama_lengkap" class="form-group evaluators_nama_lengkap">
<input type="<?= $Page->nama_lengkap->getInputTextType() ?>" data-table="evaluators" data-field="x_nama_lengkap" name="x<?= $Page->RowIndex ?>_nama_lengkap" id="x<?= $Page->RowIndex ?>_nama_lengkap" size="30" maxlength="200" placeholder="<?= HtmlEncode($Page->nama_lengkap->getPlaceHolder()) ?>" value="<?= $Page->nama_lengkap->EditValue ?>"<?= $Page->nama_lengkap->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->nama_lengkap->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="evaluators" data-field="x_nama_lengkap" data-hidden="1" name="o<?= $Page->RowIndex ?>_nama_lengkap" id="o<?= $Page->RowIndex ?>_nama_lengkap" value="<?= HtmlEncode($Page->nama_lengkap->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Page->wilayah->Visible) { // wilayah ?>
        <td data-name="wilayah">
<span id="el<?= $Page->RowCount ?>_evaluators_wilayah" class="form-group evaluators_wilayah">
    <select
        id="x<?= $Page->RowIndex ?>_wilayah"
        name="x<?= $Page->RowIndex ?>_wilayah"
        class="form-control ew-select<?= $Page->wilayah->isInvalidClass() ?>"
        data-select2-id="evaluators_x<?= $Page->RowIndex ?>_wilayah"
        data-table="evaluators"
        data-field="x_wilayah"
        data-value-separator="<?= $Page->wilayah->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->wilayah->getPlaceHolder()) ?>"
        <?= $Page->wilayah->editAttributes() ?>>
        <?= $Page->wilayah->selectOptionListHtml("x{$Page->RowIndex}_wilayah") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->wilayah->getErrorMessage() ?></div>
<?= $Page->wilayah->Lookup->getParamTag($Page, "p_x" . $Page->RowIndex . "_wilayah") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='evaluators_x<?= $Page->RowIndex ?>_wilayah']"),
        options = { name: "x<?= $Page->RowIndex ?>_wilayah", selectId: "evaluators_x<?= $Page->RowIndex ?>_wilayah", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.evaluators.fields.wilayah.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<input type="hidden" data-table="evaluators" data-field="x_wilayah" data-hidden="1" name="o<?= $Page->RowIndex ?>_wilayah" id="o<?= $Page->RowIndex ?>_wilayah" value="<?= HtmlEncode($Page->wilayah->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Page->idd_user->Visible) { // idd_user ?>
        <td data-name="idd_user">
<?php if (!$Security->isAdmin() && $Security->isLoggedIn() && !$Page->userIDAllow($Page->CurrentAction)) { // Non system admin ?>
<span id="el<?= $Page->RowCount ?>_evaluators_idd_user" class="form-group evaluators_idd_user">
<span<?= $Page->idd_user->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->idd_user->getDisplayValue($Page->idd_user->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="evaluators" data-field="x_idd_user" data-hidden="1" name="x<?= $Page->RowIndex ?>_idd_user" id="x<?= $Page->RowIndex ?>_idd_user" value="<?= HtmlEncode($Page->idd_user->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?= $Page->RowCount ?>_evaluators_idd_user" class="form-group evaluators_idd_user">
    <select
        id="x<?= $Page->RowIndex ?>_idd_user"
        name="x<?= $Page->RowIndex ?>_idd_user"
        class="form-control ew-select<?= $Page->idd_user->isInvalidClass() ?>"
        data-select2-id="evaluators_x<?= $Page->RowIndex ?>_idd_user"
        data-table="evaluators"
        data-field="x_idd_user"
        data-value-separator="<?= $Page->idd_user->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->idd_user->getPlaceHolder()) ?>"
        <?= $Page->idd_user->editAttributes() ?>>
        <?= $Page->idd_user->selectOptionListHtml("x{$Page->RowIndex}_idd_user") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->idd_user->getErrorMessage() ?></div>
<?= $Page->idd_user->Lookup->getParamTag($Page, "p_x" . $Page->RowIndex . "_idd_user") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='evaluators_x<?= $Page->RowIndex ?>_idd_user']"),
        options = { name: "x<?= $Page->RowIndex ?>_idd_user", selectId: "evaluators_x<?= $Page->RowIndex ?>_idd_user", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.evaluators.fields.idd_user.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<input type="hidden" data-table="evaluators" data-field="x_idd_user" data-hidden="1" name="o<?= $Page->RowIndex ?>_idd_user" id="o<?= $Page->RowIndex ?>_idd_user" value="<?= HtmlEncode($Page->idd_user->OldValue) ?>">
</td>
    <?php } ?>
<?php
// Render list options (body, right)
$Page->ListOptions->render("body", "right", $Page->RowCount);
?>
<script>
loadjs.ready(["fevaluatorslist","load"], function() {
    fevaluatorslist.updateLists(<?= $Page->RowIndex ?>);
});
</script>
    </tr>
<?php
    }
?>
<?php
if ($Page->ExportAll && $Page->isExport()) {
    $Page->StopRecord = $Page->TotalRecords;
} else {
    // Set the last record to display
    if ($Page->TotalRecords > $Page->StartRecord + $Page->DisplayRecords - 1) {
        $Page->StopRecord = $Page->StartRecord + $Page->DisplayRecords - 1;
    } else {
        $Page->StopRecord = $Page->TotalRecords;
    }
}

// Restore number of post back records
if ($CurrentForm && ($Page->isConfirm() || $Page->EventCancelled)) {
    $CurrentForm->Index = -1;
    if ($CurrentForm->hasValue($Page->FormKeyCountName) && ($Page->isGridAdd() || $Page->isGridEdit() || $Page->isConfirm())) {
        $Page->KeyCount = $CurrentForm->getValue($Page->FormKeyCountName);
        $Page->StopRecord = $Page->StartRecord + $Page->KeyCount - 1;
    }
}
$Page->RecordCount = $Page->StartRecord - 1;
if ($Page->Recordset && !$Page->Recordset->EOF) {
    // Nothing to do
} elseif (!$Page->AllowAddDeleteRow && $Page->StopRecord == 0) {
    $Page->StopRecord = $Page->GridAddRowCount;
}

// Initialize aggregate
$Page->RowType = ROWTYPE_AGGREGATEINIT;
$Page->resetAttributes();
$Page->renderRow();
$Page->EditRowCount = 0;
if ($Page->isEdit())
    $Page->RowIndex = 1;
if ($Page->isGridAdd())
    $Page->RowIndex = 0;
if ($Page->isGridEdit())
    $Page->RowIndex = 0;
while ($Page->RecordCount < $Page->StopRecord) {
    $Page->RecordCount++;
    if ($Page->RecordCount >= $Page->StartRecord) {
        $Page->RowCount++;
        if ($Page->isGridAdd() || $Page->isGridEdit() || $Page->isConfirm()) {
            $Page->RowIndex++;
            $CurrentForm->Index = $Page->RowIndex;
            if ($CurrentForm->hasValue($Page->FormActionName) && ($Page->isConfirm() || $Page->EventCancelled)) {
                $Page->RowAction = strval($CurrentForm->getValue($Page->FormActionName));
            } elseif ($Page->isGridAdd()) {
                $Page->RowAction = "insert";
            } else {
                $Page->RowAction = "";
            }
        }

        // Set up key count
        $Page->KeyCount = $Page->RowIndex;

        // Init row class and style
        $Page->resetAttributes();
        $Page->CssClass = "";
        if ($Page->isGridAdd()) {
            $Page->loadRowValues(); // Load default values
            $Page->OldKey = "";
            $Page->setKey($Page->OldKey);
        } else {
            $Page->loadRowValues($Page->Recordset); // Load row values
            if ($Page->isGridEdit()) {
                $Page->OldKey = $Page->getKey(true); // Get from CurrentValue
                $Page->setKey($Page->OldKey);
            }
        }
        $Page->RowType = ROWTYPE_VIEW; // Render view
        if ($Page->isGridAdd()) { // Grid add
            $Page->RowType = ROWTYPE_ADD; // Render add
        }
        if ($Page->isGridAdd() && $Page->EventCancelled && !$CurrentForm->hasValue("k_blankrow")) { // Insert failed
            $Page->restoreCurrentRowFormValues($Page->RowIndex); // Restore form values
        }
        if ($Page->isEdit()) {
            if ($Page->checkInlineEditKey() && $Page->EditRowCount == 0) { // Inline edit
                $Page->RowType = ROWTYPE_EDIT; // Render edit
            }
        }
        if ($Page->isGridEdit()) { // Grid edit
            if ($Page->EventCancelled) {
                $Page->restoreCurrentRowFormValues($Page->RowIndex); // Restore form values
            }
            if ($Page->RowAction == "insert") {
                $Page->RowType = ROWTYPE_ADD; // Render add
            } else {
                $Page->RowType = ROWTYPE_EDIT; // Render edit
            }
        }
        if ($Page->isEdit() && $Page->RowType == ROWTYPE_EDIT && $Page->EventCancelled) { // Update failed
            $CurrentForm->Index = 1;
            $Page->restoreFormValues(); // Restore form values
        }
        if ($Page->isGridEdit() && ($Page->RowType == ROWTYPE_EDIT || $Page->RowType == ROWTYPE_ADD) && $Page->EventCancelled) { // Update failed
            $Page->restoreCurrentRowFormValues($Page->RowIndex); // Restore form values
        }
        if ($Page->RowType == ROWTYPE_EDIT) { // Edit row
            $Page->EditRowCount++;
        }

        // Set up row id / data-rowindex
        $Page->RowAttrs->merge(["data-rowindex" => $Page->RowCount, "id" => "r" . $Page->RowCount . "_evaluators", "data-rowtype" => $Page->RowType]);

        // Render row
        $Page->renderRow();

        // Render list options
        $Page->renderListOptions();

        // Skip delete row / empty row for confirm page
        if ($Page->RowAction != "delete" && $Page->RowAction != "insertdelete" && !($Page->RowAction == "insert" && $Page->isConfirm() && $Page->emptyRow())) {
?>
    <tr <?= $Page->rowAttributes() ?>>
<?php
// Render list options (body, left)
$Page->ListOptions->render("body", "left", $Page->RowCount);
?>
    <?php if ($Page->nip->Visible) { // nip ?>
        <td data-name="nip" <?= $Page->nip->cellAttributes() ?>>
<?php if ($Page->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Page->RowCount ?>_evaluators_nip" class="form-group">
<input type="<?= $Page->nip->getInputTextType() ?>" data-table="evaluators" data-field="x_nip" name="x<?= $Page->RowIndex ?>_nip" id="x<?= $Page->RowIndex ?>_nip" size="30" maxlength="200" placeholder="<?= HtmlEncode($Page->nip->getPlaceHolder()) ?>" value="<?= $Page->nip->EditValue ?>"<?= $Page->nip->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->nip->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="evaluators" data-field="x_nip" data-hidden="1" name="o<?= $Page->RowIndex ?>_nip" id="o<?= $Page->RowIndex ?>_nip" value="<?= HtmlEncode($Page->nip->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Page->RowCount ?>_evaluators_nip" class="form-group">
<input type="<?= $Page->nip->getInputTextType() ?>" data-table="evaluators" data-field="x_nip" name="x<?= $Page->RowIndex ?>_nip" id="x<?= $Page->RowIndex ?>_nip" size="30" maxlength="200" placeholder="<?= HtmlEncode($Page->nip->getPlaceHolder()) ?>" value="<?= $Page->nip->EditValue ?>"<?= $Page->nip->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->nip->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Page->RowCount ?>_evaluators_nip">
<span<?= $Page->nip->viewAttributes() ?>>
<?= $Page->nip->getViewValue() ?></span>
</span>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Page->nama_lengkap->Visible) { // nama_lengkap ?>
        <td data-name="nama_lengkap" <?= $Page->nama_lengkap->cellAttributes() ?>>
<?php if ($Page->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Page->RowCount ?>_evaluators_nama_lengkap" class="form-group">
<input type="<?= $Page->nama_lengkap->getInputTextType() ?>" data-table="evaluators" data-field="x_nama_lengkap" name="x<?= $Page->RowIndex ?>_nama_lengkap" id="x<?= $Page->RowIndex ?>_nama_lengkap" size="30" maxlength="200" placeholder="<?= HtmlEncode($Page->nama_lengkap->getPlaceHolder()) ?>" value="<?= $Page->nama_lengkap->EditValue ?>"<?= $Page->nama_lengkap->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->nama_lengkap->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="evaluators" data-field="x_nama_lengkap" data-hidden="1" name="o<?= $Page->RowIndex ?>_nama_lengkap" id="o<?= $Page->RowIndex ?>_nama_lengkap" value="<?= HtmlEncode($Page->nama_lengkap->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Page->RowCount ?>_evaluators_nama_lengkap" class="form-group">
<input type="<?= $Page->nama_lengkap->getInputTextType() ?>" data-table="evaluators" data-field="x_nama_lengkap" name="x<?= $Page->RowIndex ?>_nama_lengkap" id="x<?= $Page->RowIndex ?>_nama_lengkap" size="30" maxlength="200" placeholder="<?= HtmlEncode($Page->nama_lengkap->getPlaceHolder()) ?>" value="<?= $Page->nama_lengkap->EditValue ?>"<?= $Page->nama_lengkap->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->nama_lengkap->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Page->RowCount ?>_evaluators_nama_lengkap">
<span<?= $Page->nama_lengkap->viewAttributes() ?>>
<?= $Page->nama_lengkap->getViewValue() ?></span>
</span>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Page->wilayah->Visible) { // wilayah ?>
        <td data-name="wilayah" <?= $Page->wilayah->cellAttributes() ?>>
<?php if ($Page->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Page->RowCount ?>_evaluators_wilayah" class="form-group">
    <select
        id="x<?= $Page->RowIndex ?>_wilayah"
        name="x<?= $Page->RowIndex ?>_wilayah"
        class="form-control ew-select<?= $Page->wilayah->isInvalidClass() ?>"
        data-select2-id="evaluators_x<?= $Page->RowIndex ?>_wilayah"
        data-table="evaluators"
        data-field="x_wilayah"
        data-value-separator="<?= $Page->wilayah->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->wilayah->getPlaceHolder()) ?>"
        <?= $Page->wilayah->editAttributes() ?>>
        <?= $Page->wilayah->selectOptionListHtml("x{$Page->RowIndex}_wilayah") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->wilayah->getErrorMessage() ?></div>
<?= $Page->wilayah->Lookup->getParamTag($Page, "p_x" . $Page->RowIndex . "_wilayah") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='evaluators_x<?= $Page->RowIndex ?>_wilayah']"),
        options = { name: "x<?= $Page->RowIndex ?>_wilayah", selectId: "evaluators_x<?= $Page->RowIndex ?>_wilayah", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.evaluators.fields.wilayah.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<input type="hidden" data-table="evaluators" data-field="x_wilayah" data-hidden="1" name="o<?= $Page->RowIndex ?>_wilayah" id="o<?= $Page->RowIndex ?>_wilayah" value="<?= HtmlEncode($Page->wilayah->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Page->RowCount ?>_evaluators_wilayah" class="form-group">
    <select
        id="x<?= $Page->RowIndex ?>_wilayah"
        name="x<?= $Page->RowIndex ?>_wilayah"
        class="form-control ew-select<?= $Page->wilayah->isInvalidClass() ?>"
        data-select2-id="evaluators_x<?= $Page->RowIndex ?>_wilayah"
        data-table="evaluators"
        data-field="x_wilayah"
        data-value-separator="<?= $Page->wilayah->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->wilayah->getPlaceHolder()) ?>"
        <?= $Page->wilayah->editAttributes() ?>>
        <?= $Page->wilayah->selectOptionListHtml("x{$Page->RowIndex}_wilayah") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->wilayah->getErrorMessage() ?></div>
<?= $Page->wilayah->Lookup->getParamTag($Page, "p_x" . $Page->RowIndex . "_wilayah") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='evaluators_x<?= $Page->RowIndex ?>_wilayah']"),
        options = { name: "x<?= $Page->RowIndex ?>_wilayah", selectId: "evaluators_x<?= $Page->RowIndex ?>_wilayah", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.evaluators.fields.wilayah.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Page->RowCount ?>_evaluators_wilayah">
<span<?= $Page->wilayah->viewAttributes() ?>>
<?= $Page->wilayah->getViewValue() ?></span>
</span>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Page->idd_user->Visible) { // idd_user ?>
        <td data-name="idd_user" <?= $Page->idd_user->cellAttributes() ?>>
<?php if ($Page->RowType == ROWTYPE_ADD) { // Add record ?>
<?php if (!$Security->isAdmin() && $Security->isLoggedIn() && !$Page->userIDAllow($Page->CurrentAction)) { // Non system admin ?>
<span id="el<?= $Page->RowCount ?>_evaluators_idd_user" class="form-group">
<span<?= $Page->idd_user->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->idd_user->getDisplayValue($Page->idd_user->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="evaluators" data-field="x_idd_user" data-hidden="1" name="x<?= $Page->RowIndex ?>_idd_user" id="x<?= $Page->RowIndex ?>_idd_user" value="<?= HtmlEncode($Page->idd_user->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?= $Page->RowCount ?>_evaluators_idd_user" class="form-group">
    <select
        id="x<?= $Page->RowIndex ?>_idd_user"
        name="x<?= $Page->RowIndex ?>_idd_user"
        class="form-control ew-select<?= $Page->idd_user->isInvalidClass() ?>"
        data-select2-id="evaluators_x<?= $Page->RowIndex ?>_idd_user"
        data-table="evaluators"
        data-field="x_idd_user"
        data-value-separator="<?= $Page->idd_user->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->idd_user->getPlaceHolder()) ?>"
        <?= $Page->idd_user->editAttributes() ?>>
        <?= $Page->idd_user->selectOptionListHtml("x{$Page->RowIndex}_idd_user") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->idd_user->getErrorMessage() ?></div>
<?= $Page->idd_user->Lookup->getParamTag($Page, "p_x" . $Page->RowIndex . "_idd_user") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='evaluators_x<?= $Page->RowIndex ?>_idd_user']"),
        options = { name: "x<?= $Page->RowIndex ?>_idd_user", selectId: "evaluators_x<?= $Page->RowIndex ?>_idd_user", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.evaluators.fields.idd_user.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<input type="hidden" data-table="evaluators" data-field="x_idd_user" data-hidden="1" name="o<?= $Page->RowIndex ?>_idd_user" id="o<?= $Page->RowIndex ?>_idd_user" value="<?= HtmlEncode($Page->idd_user->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_EDIT) { // Edit record ?>
<?php if (!$Security->isAdmin() && $Security->isLoggedIn() && !$Page->userIDAllow($Page->CurrentAction)) { // Non system admin ?>
<span id="el<?= $Page->RowCount ?>_evaluators_idd_user" class="form-group">
<span<?= $Page->idd_user->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->idd_user->getDisplayValue($Page->idd_user->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="evaluators" data-field="x_idd_user" data-hidden="1" name="x<?= $Page->RowIndex ?>_idd_user" id="x<?= $Page->RowIndex ?>_idd_user" value="<?= HtmlEncode($Page->idd_user->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?= $Page->RowCount ?>_evaluators_idd_user" class="form-group">
    <select
        id="x<?= $Page->RowIndex ?>_idd_user"
        name="x<?= $Page->RowIndex ?>_idd_user"
        class="form-control ew-select<?= $Page->idd_user->isInvalidClass() ?>"
        data-select2-id="evaluators_x<?= $Page->RowIndex ?>_idd_user"
        data-table="evaluators"
        data-field="x_idd_user"
        data-value-separator="<?= $Page->idd_user->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->idd_user->getPlaceHolder()) ?>"
        <?= $Page->idd_user->editAttributes() ?>>
        <?= $Page->idd_user->selectOptionListHtml("x{$Page->RowIndex}_idd_user") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->idd_user->getErrorMessage() ?></div>
<?= $Page->idd_user->Lookup->getParamTag($Page, "p_x" . $Page->RowIndex . "_idd_user") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='evaluators_x<?= $Page->RowIndex ?>_idd_user']"),
        options = { name: "x<?= $Page->RowIndex ?>_idd_user", selectId: "evaluators_x<?= $Page->RowIndex ?>_idd_user", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.evaluators.fields.idd_user.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Page->RowCount ?>_evaluators_idd_user">
<span<?= $Page->idd_user->viewAttributes() ?>>
<?= $Page->idd_user->getViewValue() ?></span>
</span>
<?php } ?>
</td>
    <?php } ?>
<?php
// Render list options (body, right)
$Page->ListOptions->render("body", "right", $Page->RowCount);
?>
    </tr>
<?php if ($Page->RowType == ROWTYPE_ADD || $Page->RowType == ROWTYPE_EDIT) { ?>
<script>
loadjs.ready(["fevaluatorslist","load"], function () {
    fevaluatorslist.updateLists(<?= $Page->RowIndex ?>);
});
</script>
<?php } ?>
<?php
    }
    } // End delete row checking
    if (!$Page->isGridAdd())
        if (!$Page->Recordset->EOF) {
            $Page->Recordset->moveNext();
        }
}
?>
<?php
    if ($Page->isGridAdd() || $Page->isGridEdit()) {
        $Page->RowIndex = '$rowindex$';
        $Page->loadRowValues();

        // Set row properties
        $Page->resetAttributes();
        $Page->RowAttrs->merge(["data-rowindex" => $Page->RowIndex, "id" => "r0_evaluators", "data-rowtype" => ROWTYPE_ADD]);
        $Page->RowAttrs->appendClass("ew-template");
        $Page->RowType = ROWTYPE_ADD;

        // Render row
        $Page->renderRow();

        // Render list options
        $Page->renderListOptions();
        $Page->StartRowCount = 0;
?>
    <tr <?= $Page->rowAttributes() ?>>
<?php
// Render list options (body, left)
$Page->ListOptions->render("body", "left", $Page->RowIndex);
?>
    <?php if ($Page->nip->Visible) { // nip ?>
        <td data-name="nip">
<span id="el$rowindex$_evaluators_nip" class="form-group evaluators_nip">
<input type="<?= $Page->nip->getInputTextType() ?>" data-table="evaluators" data-field="x_nip" name="x<?= $Page->RowIndex ?>_nip" id="x<?= $Page->RowIndex ?>_nip" size="30" maxlength="200" placeholder="<?= HtmlEncode($Page->nip->getPlaceHolder()) ?>" value="<?= $Page->nip->EditValue ?>"<?= $Page->nip->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->nip->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="evaluators" data-field="x_nip" data-hidden="1" name="o<?= $Page->RowIndex ?>_nip" id="o<?= $Page->RowIndex ?>_nip" value="<?= HtmlEncode($Page->nip->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Page->nama_lengkap->Visible) { // nama_lengkap ?>
        <td data-name="nama_lengkap">
<span id="el$rowindex$_evaluators_nama_lengkap" class="form-group evaluators_nama_lengkap">
<input type="<?= $Page->nama_lengkap->getInputTextType() ?>" data-table="evaluators" data-field="x_nama_lengkap" name="x<?= $Page->RowIndex ?>_nama_lengkap" id="x<?= $Page->RowIndex ?>_nama_lengkap" size="30" maxlength="200" placeholder="<?= HtmlEncode($Page->nama_lengkap->getPlaceHolder()) ?>" value="<?= $Page->nama_lengkap->EditValue ?>"<?= $Page->nama_lengkap->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->nama_lengkap->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="evaluators" data-field="x_nama_lengkap" data-hidden="1" name="o<?= $Page->RowIndex ?>_nama_lengkap" id="o<?= $Page->RowIndex ?>_nama_lengkap" value="<?= HtmlEncode($Page->nama_lengkap->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Page->wilayah->Visible) { // wilayah ?>
        <td data-name="wilayah">
<span id="el$rowindex$_evaluators_wilayah" class="form-group evaluators_wilayah">
    <select
        id="x<?= $Page->RowIndex ?>_wilayah"
        name="x<?= $Page->RowIndex ?>_wilayah"
        class="form-control ew-select<?= $Page->wilayah->isInvalidClass() ?>"
        data-select2-id="evaluators_x<?= $Page->RowIndex ?>_wilayah"
        data-table="evaluators"
        data-field="x_wilayah"
        data-value-separator="<?= $Page->wilayah->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->wilayah->getPlaceHolder()) ?>"
        <?= $Page->wilayah->editAttributes() ?>>
        <?= $Page->wilayah->selectOptionListHtml("x{$Page->RowIndex}_wilayah") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->wilayah->getErrorMessage() ?></div>
<?= $Page->wilayah->Lookup->getParamTag($Page, "p_x" . $Page->RowIndex . "_wilayah") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='evaluators_x<?= $Page->RowIndex ?>_wilayah']"),
        options = { name: "x<?= $Page->RowIndex ?>_wilayah", selectId: "evaluators_x<?= $Page->RowIndex ?>_wilayah", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.evaluators.fields.wilayah.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<input type="hidden" data-table="evaluators" data-field="x_wilayah" data-hidden="1" name="o<?= $Page->RowIndex ?>_wilayah" id="o<?= $Page->RowIndex ?>_wilayah" value="<?= HtmlEncode($Page->wilayah->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Page->idd_user->Visible) { // idd_user ?>
        <td data-name="idd_user">
<?php if (!$Security->isAdmin() && $Security->isLoggedIn() && !$Page->userIDAllow($Page->CurrentAction)) { // Non system admin ?>
<span id="el$rowindex$_evaluators_idd_user" class="form-group evaluators_idd_user">
<span<?= $Page->idd_user->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->idd_user->getDisplayValue($Page->idd_user->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="evaluators" data-field="x_idd_user" data-hidden="1" name="x<?= $Page->RowIndex ?>_idd_user" id="x<?= $Page->RowIndex ?>_idd_user" value="<?= HtmlEncode($Page->idd_user->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_evaluators_idd_user" class="form-group evaluators_idd_user">
    <select
        id="x<?= $Page->RowIndex ?>_idd_user"
        name="x<?= $Page->RowIndex ?>_idd_user"
        class="form-control ew-select<?= $Page->idd_user->isInvalidClass() ?>"
        data-select2-id="evaluators_x<?= $Page->RowIndex ?>_idd_user"
        data-table="evaluators"
        data-field="x_idd_user"
        data-value-separator="<?= $Page->idd_user->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->idd_user->getPlaceHolder()) ?>"
        <?= $Page->idd_user->editAttributes() ?>>
        <?= $Page->idd_user->selectOptionListHtml("x{$Page->RowIndex}_idd_user") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->idd_user->getErrorMessage() ?></div>
<?= $Page->idd_user->Lookup->getParamTag($Page, "p_x" . $Page->RowIndex . "_idd_user") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='evaluators_x<?= $Page->RowIndex ?>_idd_user']"),
        options = { name: "x<?= $Page->RowIndex ?>_idd_user", selectId: "evaluators_x<?= $Page->RowIndex ?>_idd_user", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.evaluators.fields.idd_user.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<input type="hidden" data-table="evaluators" data-field="x_idd_user" data-hidden="1" name="o<?= $Page->RowIndex ?>_idd_user" id="o<?= $Page->RowIndex ?>_idd_user" value="<?= HtmlEncode($Page->idd_user->OldValue) ?>">
</td>
    <?php } ?>
<?php
// Render list options (body, right)
$Page->ListOptions->render("body", "right", $Page->RowIndex);
?>
<script>
loadjs.ready(["fevaluatorslist","load"], function() {
    fevaluatorslist.updateLists(<?= $Page->RowIndex ?>);
});
</script>
    </tr>
<?php
    }
?>
</tbody>
</table><!-- /.ew-table -->
<?php } ?>
</div><!-- /.ew-grid-middle-panel -->
<?php if ($Page->isAdd() || $Page->isCopy()) { ?>
<input type="hidden" name="<?= $Page->FormKeyCountName ?>" id="<?= $Page->FormKeyCountName ?>" value="<?= $Page->KeyCount ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<?php } ?>
<?php if ($Page->isGridAdd()) { ?>
<input type="hidden" name="action" id="action" value="gridinsert">
<input type="hidden" name="<?= $Page->FormKeyCountName ?>" id="<?= $Page->FormKeyCountName ?>" value="<?= $Page->KeyCount ?>">
<?= $Page->MultiSelectKey ?>
<?php } ?>
<?php if ($Page->isEdit()) { ?>
<input type="hidden" name="<?= $Page->FormKeyCountName ?>" id="<?= $Page->FormKeyCountName ?>" value="<?= $Page->KeyCount ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<?php } ?>
<?php if ($Page->isGridEdit()) { ?>
<input type="hidden" name="action" id="action" value="gridupdate">
<input type="hidden" name="<?= $Page->FormKeyCountName ?>" id="<?= $Page->FormKeyCountName ?>" value="<?= $Page->KeyCount ?>">
<?= $Page->MultiSelectKey ?>
<?php } ?>
<?php if (!$Page->CurrentAction) { ?>
<input type="hidden" name="action" id="action" value="">
<?php } ?>
</form><!-- /.ew-list-form -->
<?php
// Close recordset
if ($Page->Recordset) {
    $Page->Recordset->close();
}
?>
<?php if (!$Page->isExport()) { ?>
<div class="card-footer ew-grid-lower-panel">
<?php if (!$Page->isGridAdd()) { ?>
<form name="ew-pager-form" class="form-inline ew-form ew-pager-form" action="<?= CurrentPageUrl(false) ?>">
<?= $Page->Pager->render() ?>
</form>
<?php } ?>
<div class="ew-list-other-options">
<?php $Page->OtherOptions->render("body", "bottom") ?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
</div><!-- /.ew-grid -->
<?php } ?>
<?php if ($Page->TotalRecords == 0 && !$Page->CurrentAction) { // Show other options ?>
<div class="ew-list-other-options">
<?php $Page->OtherOptions->render("body") ?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<?php if (!$Page->isExport()) { ?>
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
<?php } ?>
