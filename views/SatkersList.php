<?php

namespace PHPMaker2021\silpa;

// Page object
$SatkersList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var fsatkerslist;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "list";
    fsatkerslist = currentForm = new ew.Form("fsatkerslist", "list");
    fsatkerslist.formKeyCountName = '<?= $Page->FormKeyCountName ?>';

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "satkers")) ?>,
        fields = currentTable.fields;
    if (!ew.vars.tables.satkers)
        ew.vars.tables.satkers = currentTable;
    fsatkerslist.addFields([
        ["kode_pemda", [fields.kode_pemda.visible && fields.kode_pemda.required ? ew.Validators.required(fields.kode_pemda.caption) : null], fields.kode_pemda.isInvalid],
        ["kode_satker", [fields.kode_satker.visible && fields.kode_satker.required ? ew.Validators.required(fields.kode_satker.caption) : null, ew.Validators.integer], fields.kode_satker.isInvalid],
        ["nama_satker", [fields.nama_satker.visible && fields.nama_satker.required ? ew.Validators.required(fields.nama_satker.caption) : null], fields.nama_satker.isInvalid],
        ["idd_user", [fields.idd_user.visible && fields.idd_user.required ? ew.Validators.required(fields.idd_user.caption) : null], fields.idd_user.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fsatkerslist,
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
    fsatkerslist.validate = function () {
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
    fsatkerslist.emptyRow = function (rowIndex) {
        var fobj = this.getForm();
        if (ew.valueChanged(fobj, rowIndex, "kode_pemda", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "kode_satker", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "nama_satker", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "idd_user", false))
            return false;
        return true;
    }

    // Form_CustomValidate
    fsatkerslist.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fsatkerslist.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fsatkerslist.lists.idd_user = <?= $Page->idd_user->toClientList($Page) ?>;
    loadjs.done("fsatkerslist");
});
var fsatkerslistsrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object for search
    fsatkerslistsrch = currentSearchForm = new ew.Form("fsatkerslistsrch");

    // Dynamic selection lists

    // Filters
    fsatkerslistsrch.filterList = <?= $Page->getFilterList() ?>;
    loadjs.done("fsatkerslistsrch");
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
<form name="fsatkerslistsrch" id="fsatkerslistsrch" class="form-inline ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>">
<div id="fsatkerslistsrch-search-panel" class="<?= $Page->SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="satkers">
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
<div class="card ew-card ew-grid<?php if ($Page->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> satkers">
<form name="fsatkerslist" id="fsatkerslist" class="form-inline ew-form ew-list-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="satkers">
<div id="gmp_satkers" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<?php if ($Page->TotalRecords > 0 || $Page->isAdd() || $Page->isCopy() || $Page->isGridEdit()) { ?>
<table id="tbl_satkerslist" class="table ew-table"><!-- .ew-table -->
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
<?php if ($Page->kode_pemda->Visible) { // kode_pemda ?>
        <th data-name="kode_pemda" class="<?= $Page->kode_pemda->headerCellClass() ?>"><div id="elh_satkers_kode_pemda" class="satkers_kode_pemda"><?= $Page->renderSort($Page->kode_pemda) ?></div></th>
<?php } ?>
<?php if ($Page->kode_satker->Visible) { // kode_satker ?>
        <th data-name="kode_satker" class="<?= $Page->kode_satker->headerCellClass() ?>"><div id="elh_satkers_kode_satker" class="satkers_kode_satker"><?= $Page->renderSort($Page->kode_satker) ?></div></th>
<?php } ?>
<?php if ($Page->nama_satker->Visible) { // nama_satker ?>
        <th data-name="nama_satker" class="<?= $Page->nama_satker->headerCellClass() ?>"><div id="elh_satkers_nama_satker" class="satkers_nama_satker"><?= $Page->renderSort($Page->nama_satker) ?></div></th>
<?php } ?>
<?php if ($Page->idd_user->Visible) { // idd_user ?>
        <th data-name="idd_user" class="<?= $Page->idd_user->headerCellClass() ?>"><div id="elh_satkers_idd_user" class="satkers_idd_user"><?= $Page->renderSort($Page->idd_user) ?></div></th>
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
        $Page->RowAttrs->merge(["data-rowindex" => 0, "id" => "r0_satkers", "data-rowtype" => ROWTYPE_ADD]);
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
    <?php if ($Page->kode_pemda->Visible) { // kode_pemda ?>
        <td data-name="kode_pemda">
<span id="el<?= $Page->RowCount ?>_satkers_kode_pemda" class="form-group satkers_kode_pemda">
<input type="<?= $Page->kode_pemda->getInputTextType() ?>" data-table="satkers" data-field="x_kode_pemda" name="x<?= $Page->RowIndex ?>_kode_pemda" id="x<?= $Page->RowIndex ?>_kode_pemda" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->kode_pemda->getPlaceHolder()) ?>" value="<?= $Page->kode_pemda->EditValue ?>"<?= $Page->kode_pemda->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->kode_pemda->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="satkers" data-field="x_kode_pemda" data-hidden="1" name="o<?= $Page->RowIndex ?>_kode_pemda" id="o<?= $Page->RowIndex ?>_kode_pemda" value="<?= HtmlEncode($Page->kode_pemda->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Page->kode_satker->Visible) { // kode_satker ?>
        <td data-name="kode_satker">
<span id="el<?= $Page->RowCount ?>_satkers_kode_satker" class="form-group satkers_kode_satker">
<input type="<?= $Page->kode_satker->getInputTextType() ?>" data-table="satkers" data-field="x_kode_satker" name="x<?= $Page->RowIndex ?>_kode_satker" id="x<?= $Page->RowIndex ?>_kode_satker" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->kode_satker->getPlaceHolder()) ?>" value="<?= $Page->kode_satker->EditValue ?>"<?= $Page->kode_satker->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->kode_satker->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="satkers" data-field="x_kode_satker" data-hidden="1" name="o<?= $Page->RowIndex ?>_kode_satker" id="o<?= $Page->RowIndex ?>_kode_satker" value="<?= HtmlEncode($Page->kode_satker->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Page->nama_satker->Visible) { // nama_satker ?>
        <td data-name="nama_satker">
<span id="el<?= $Page->RowCount ?>_satkers_nama_satker" class="form-group satkers_nama_satker">
<input type="<?= $Page->nama_satker->getInputTextType() ?>" data-table="satkers" data-field="x_nama_satker" name="x<?= $Page->RowIndex ?>_nama_satker" id="x<?= $Page->RowIndex ?>_nama_satker" size="30" maxlength="200" placeholder="<?= HtmlEncode($Page->nama_satker->getPlaceHolder()) ?>" value="<?= $Page->nama_satker->EditValue ?>"<?= $Page->nama_satker->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->nama_satker->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="satkers" data-field="x_nama_satker" data-hidden="1" name="o<?= $Page->RowIndex ?>_nama_satker" id="o<?= $Page->RowIndex ?>_nama_satker" value="<?= HtmlEncode($Page->nama_satker->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Page->idd_user->Visible) { // idd_user ?>
        <td data-name="idd_user">
<?php if (!$Security->isAdmin() && $Security->isLoggedIn() && !$Page->userIDAllow($Page->CurrentAction)) { // Non system admin ?>
<span id="el<?= $Page->RowCount ?>_satkers_idd_user" class="form-group satkers_idd_user">
<span<?= $Page->idd_user->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->idd_user->getDisplayValue($Page->idd_user->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="satkers" data-field="x_idd_user" data-hidden="1" name="x<?= $Page->RowIndex ?>_idd_user" id="x<?= $Page->RowIndex ?>_idd_user" value="<?= HtmlEncode($Page->idd_user->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?= $Page->RowCount ?>_satkers_idd_user" class="form-group satkers_idd_user">
    <select
        id="x<?= $Page->RowIndex ?>_idd_user"
        name="x<?= $Page->RowIndex ?>_idd_user"
        class="form-control ew-select<?= $Page->idd_user->isInvalidClass() ?>"
        data-select2-id="satkers_x<?= $Page->RowIndex ?>_idd_user"
        data-table="satkers"
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
    var el = document.querySelector("select[data-select2-id='satkers_x<?= $Page->RowIndex ?>_idd_user']"),
        options = { name: "x<?= $Page->RowIndex ?>_idd_user", selectId: "satkers_x<?= $Page->RowIndex ?>_idd_user", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.satkers.fields.idd_user.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<input type="hidden" data-table="satkers" data-field="x_idd_user" data-hidden="1" name="o<?= $Page->RowIndex ?>_idd_user" id="o<?= $Page->RowIndex ?>_idd_user" value="<?= HtmlEncode($Page->idd_user->OldValue) ?>">
</td>
    <?php } ?>
<?php
// Render list options (body, right)
$Page->ListOptions->render("body", "right", $Page->RowCount);
?>
<script>
loadjs.ready(["fsatkerslist","load"], function() {
    fsatkerslist.updateLists(<?= $Page->RowIndex ?>);
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
        $Page->RowAttrs->merge(["data-rowindex" => $Page->RowCount, "id" => "r" . $Page->RowCount . "_satkers", "data-rowtype" => $Page->RowType]);

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
    <?php if ($Page->kode_pemda->Visible) { // kode_pemda ?>
        <td data-name="kode_pemda" <?= $Page->kode_pemda->cellAttributes() ?>>
<?php if ($Page->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Page->RowCount ?>_satkers_kode_pemda" class="form-group">
<input type="<?= $Page->kode_pemda->getInputTextType() ?>" data-table="satkers" data-field="x_kode_pemda" name="x<?= $Page->RowIndex ?>_kode_pemda" id="x<?= $Page->RowIndex ?>_kode_pemda" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->kode_pemda->getPlaceHolder()) ?>" value="<?= $Page->kode_pemda->EditValue ?>"<?= $Page->kode_pemda->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->kode_pemda->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="satkers" data-field="x_kode_pemda" data-hidden="1" name="o<?= $Page->RowIndex ?>_kode_pemda" id="o<?= $Page->RowIndex ?>_kode_pemda" value="<?= HtmlEncode($Page->kode_pemda->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Page->RowCount ?>_satkers_kode_pemda" class="form-group">
<input type="<?= $Page->kode_pemda->getInputTextType() ?>" data-table="satkers" data-field="x_kode_pemda" name="x<?= $Page->RowIndex ?>_kode_pemda" id="x<?= $Page->RowIndex ?>_kode_pemda" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->kode_pemda->getPlaceHolder()) ?>" value="<?= $Page->kode_pemda->EditValue ?>"<?= $Page->kode_pemda->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->kode_pemda->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Page->RowCount ?>_satkers_kode_pemda">
<span<?= $Page->kode_pemda->viewAttributes() ?>>
<?= $Page->kode_pemda->getViewValue() ?></span>
</span>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Page->kode_satker->Visible) { // kode_satker ?>
        <td data-name="kode_satker" <?= $Page->kode_satker->cellAttributes() ?>>
<?php if ($Page->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Page->RowCount ?>_satkers_kode_satker" class="form-group">
<input type="<?= $Page->kode_satker->getInputTextType() ?>" data-table="satkers" data-field="x_kode_satker" name="x<?= $Page->RowIndex ?>_kode_satker" id="x<?= $Page->RowIndex ?>_kode_satker" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->kode_satker->getPlaceHolder()) ?>" value="<?= $Page->kode_satker->EditValue ?>"<?= $Page->kode_satker->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->kode_satker->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="satkers" data-field="x_kode_satker" data-hidden="1" name="o<?= $Page->RowIndex ?>_kode_satker" id="o<?= $Page->RowIndex ?>_kode_satker" value="<?= HtmlEncode($Page->kode_satker->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Page->RowCount ?>_satkers_kode_satker" class="form-group">
<input type="<?= $Page->kode_satker->getInputTextType() ?>" data-table="satkers" data-field="x_kode_satker" name="x<?= $Page->RowIndex ?>_kode_satker" id="x<?= $Page->RowIndex ?>_kode_satker" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->kode_satker->getPlaceHolder()) ?>" value="<?= $Page->kode_satker->EditValue ?>"<?= $Page->kode_satker->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->kode_satker->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Page->RowCount ?>_satkers_kode_satker">
<span<?= $Page->kode_satker->viewAttributes() ?>>
<?= $Page->kode_satker->getViewValue() ?></span>
</span>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Page->nama_satker->Visible) { // nama_satker ?>
        <td data-name="nama_satker" <?= $Page->nama_satker->cellAttributes() ?>>
<?php if ($Page->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Page->RowCount ?>_satkers_nama_satker" class="form-group">
<input type="<?= $Page->nama_satker->getInputTextType() ?>" data-table="satkers" data-field="x_nama_satker" name="x<?= $Page->RowIndex ?>_nama_satker" id="x<?= $Page->RowIndex ?>_nama_satker" size="30" maxlength="200" placeholder="<?= HtmlEncode($Page->nama_satker->getPlaceHolder()) ?>" value="<?= $Page->nama_satker->EditValue ?>"<?= $Page->nama_satker->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->nama_satker->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="satkers" data-field="x_nama_satker" data-hidden="1" name="o<?= $Page->RowIndex ?>_nama_satker" id="o<?= $Page->RowIndex ?>_nama_satker" value="<?= HtmlEncode($Page->nama_satker->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Page->RowCount ?>_satkers_nama_satker" class="form-group">
<input type="<?= $Page->nama_satker->getInputTextType() ?>" data-table="satkers" data-field="x_nama_satker" name="x<?= $Page->RowIndex ?>_nama_satker" id="x<?= $Page->RowIndex ?>_nama_satker" size="30" maxlength="200" placeholder="<?= HtmlEncode($Page->nama_satker->getPlaceHolder()) ?>" value="<?= $Page->nama_satker->EditValue ?>"<?= $Page->nama_satker->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->nama_satker->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Page->RowCount ?>_satkers_nama_satker">
<span<?= $Page->nama_satker->viewAttributes() ?>>
<?= $Page->nama_satker->getViewValue() ?></span>
</span>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Page->idd_user->Visible) { // idd_user ?>
        <td data-name="idd_user" <?= $Page->idd_user->cellAttributes() ?>>
<?php if ($Page->RowType == ROWTYPE_ADD) { // Add record ?>
<?php if (!$Security->isAdmin() && $Security->isLoggedIn() && !$Page->userIDAllow($Page->CurrentAction)) { // Non system admin ?>
<span id="el<?= $Page->RowCount ?>_satkers_idd_user" class="form-group">
<span<?= $Page->idd_user->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->idd_user->getDisplayValue($Page->idd_user->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="satkers" data-field="x_idd_user" data-hidden="1" name="x<?= $Page->RowIndex ?>_idd_user" id="x<?= $Page->RowIndex ?>_idd_user" value="<?= HtmlEncode($Page->idd_user->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?= $Page->RowCount ?>_satkers_idd_user" class="form-group">
    <select
        id="x<?= $Page->RowIndex ?>_idd_user"
        name="x<?= $Page->RowIndex ?>_idd_user"
        class="form-control ew-select<?= $Page->idd_user->isInvalidClass() ?>"
        data-select2-id="satkers_x<?= $Page->RowIndex ?>_idd_user"
        data-table="satkers"
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
    var el = document.querySelector("select[data-select2-id='satkers_x<?= $Page->RowIndex ?>_idd_user']"),
        options = { name: "x<?= $Page->RowIndex ?>_idd_user", selectId: "satkers_x<?= $Page->RowIndex ?>_idd_user", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.satkers.fields.idd_user.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<input type="hidden" data-table="satkers" data-field="x_idd_user" data-hidden="1" name="o<?= $Page->RowIndex ?>_idd_user" id="o<?= $Page->RowIndex ?>_idd_user" value="<?= HtmlEncode($Page->idd_user->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_EDIT) { // Edit record ?>
<?php if (!$Security->isAdmin() && $Security->isLoggedIn() && !$Page->userIDAllow($Page->CurrentAction)) { // Non system admin ?>
<span id="el<?= $Page->RowCount ?>_satkers_idd_user" class="form-group">
<span<?= $Page->idd_user->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->idd_user->getDisplayValue($Page->idd_user->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="satkers" data-field="x_idd_user" data-hidden="1" name="x<?= $Page->RowIndex ?>_idd_user" id="x<?= $Page->RowIndex ?>_idd_user" value="<?= HtmlEncode($Page->idd_user->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?= $Page->RowCount ?>_satkers_idd_user" class="form-group">
    <select
        id="x<?= $Page->RowIndex ?>_idd_user"
        name="x<?= $Page->RowIndex ?>_idd_user"
        class="form-control ew-select<?= $Page->idd_user->isInvalidClass() ?>"
        data-select2-id="satkers_x<?= $Page->RowIndex ?>_idd_user"
        data-table="satkers"
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
    var el = document.querySelector("select[data-select2-id='satkers_x<?= $Page->RowIndex ?>_idd_user']"),
        options = { name: "x<?= $Page->RowIndex ?>_idd_user", selectId: "satkers_x<?= $Page->RowIndex ?>_idd_user", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.satkers.fields.idd_user.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Page->RowCount ?>_satkers_idd_user">
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
loadjs.ready(["fsatkerslist","load"], function () {
    fsatkerslist.updateLists(<?= $Page->RowIndex ?>);
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
        $Page->RowAttrs->merge(["data-rowindex" => $Page->RowIndex, "id" => "r0_satkers", "data-rowtype" => ROWTYPE_ADD]);
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
    <?php if ($Page->kode_pemda->Visible) { // kode_pemda ?>
        <td data-name="kode_pemda">
<span id="el$rowindex$_satkers_kode_pemda" class="form-group satkers_kode_pemda">
<input type="<?= $Page->kode_pemda->getInputTextType() ?>" data-table="satkers" data-field="x_kode_pemda" name="x<?= $Page->RowIndex ?>_kode_pemda" id="x<?= $Page->RowIndex ?>_kode_pemda" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->kode_pemda->getPlaceHolder()) ?>" value="<?= $Page->kode_pemda->EditValue ?>"<?= $Page->kode_pemda->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->kode_pemda->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="satkers" data-field="x_kode_pemda" data-hidden="1" name="o<?= $Page->RowIndex ?>_kode_pemda" id="o<?= $Page->RowIndex ?>_kode_pemda" value="<?= HtmlEncode($Page->kode_pemda->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Page->kode_satker->Visible) { // kode_satker ?>
        <td data-name="kode_satker">
<span id="el$rowindex$_satkers_kode_satker" class="form-group satkers_kode_satker">
<input type="<?= $Page->kode_satker->getInputTextType() ?>" data-table="satkers" data-field="x_kode_satker" name="x<?= $Page->RowIndex ?>_kode_satker" id="x<?= $Page->RowIndex ?>_kode_satker" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->kode_satker->getPlaceHolder()) ?>" value="<?= $Page->kode_satker->EditValue ?>"<?= $Page->kode_satker->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->kode_satker->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="satkers" data-field="x_kode_satker" data-hidden="1" name="o<?= $Page->RowIndex ?>_kode_satker" id="o<?= $Page->RowIndex ?>_kode_satker" value="<?= HtmlEncode($Page->kode_satker->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Page->nama_satker->Visible) { // nama_satker ?>
        <td data-name="nama_satker">
<span id="el$rowindex$_satkers_nama_satker" class="form-group satkers_nama_satker">
<input type="<?= $Page->nama_satker->getInputTextType() ?>" data-table="satkers" data-field="x_nama_satker" name="x<?= $Page->RowIndex ?>_nama_satker" id="x<?= $Page->RowIndex ?>_nama_satker" size="30" maxlength="200" placeholder="<?= HtmlEncode($Page->nama_satker->getPlaceHolder()) ?>" value="<?= $Page->nama_satker->EditValue ?>"<?= $Page->nama_satker->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->nama_satker->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="satkers" data-field="x_nama_satker" data-hidden="1" name="o<?= $Page->RowIndex ?>_nama_satker" id="o<?= $Page->RowIndex ?>_nama_satker" value="<?= HtmlEncode($Page->nama_satker->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Page->idd_user->Visible) { // idd_user ?>
        <td data-name="idd_user">
<?php if (!$Security->isAdmin() && $Security->isLoggedIn() && !$Page->userIDAllow($Page->CurrentAction)) { // Non system admin ?>
<span id="el$rowindex$_satkers_idd_user" class="form-group satkers_idd_user">
<span<?= $Page->idd_user->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->idd_user->getDisplayValue($Page->idd_user->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="satkers" data-field="x_idd_user" data-hidden="1" name="x<?= $Page->RowIndex ?>_idd_user" id="x<?= $Page->RowIndex ?>_idd_user" value="<?= HtmlEncode($Page->idd_user->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_satkers_idd_user" class="form-group satkers_idd_user">
    <select
        id="x<?= $Page->RowIndex ?>_idd_user"
        name="x<?= $Page->RowIndex ?>_idd_user"
        class="form-control ew-select<?= $Page->idd_user->isInvalidClass() ?>"
        data-select2-id="satkers_x<?= $Page->RowIndex ?>_idd_user"
        data-table="satkers"
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
    var el = document.querySelector("select[data-select2-id='satkers_x<?= $Page->RowIndex ?>_idd_user']"),
        options = { name: "x<?= $Page->RowIndex ?>_idd_user", selectId: "satkers_x<?= $Page->RowIndex ?>_idd_user", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.satkers.fields.idd_user.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<input type="hidden" data-table="satkers" data-field="x_idd_user" data-hidden="1" name="o<?= $Page->RowIndex ?>_idd_user" id="o<?= $Page->RowIndex ?>_idd_user" value="<?= HtmlEncode($Page->idd_user->OldValue) ?>">
</td>
    <?php } ?>
<?php
// Render list options (body, right)
$Page->ListOptions->render("body", "right", $Page->RowIndex);
?>
<script>
loadjs.ready(["fsatkerslist","load"], function() {
    fsatkerslist.updateLists(<?= $Page->RowIndex ?>);
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
    ew.addEventHandlers("satkers");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
