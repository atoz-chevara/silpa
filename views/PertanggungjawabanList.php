<?php

namespace PHPMaker2021\silpa;

// Page object
$PertanggungjawabanList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var fpertanggungjawabanlist;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "list";
    fpertanggungjawabanlist = currentForm = new ew.Form("fpertanggungjawabanlist", "list");
    fpertanggungjawabanlist.formKeyCountName = '<?= $Page->FormKeyCountName ?>';

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "pertanggungjawaban")) ?>,
        fields = currentTable.fields;
    if (!ew.vars.tables.pertanggungjawaban)
        ew.vars.tables.pertanggungjawaban = currentTable;
    fpertanggungjawabanlist.addFields([
        ["tanggal_upload", [fields.tanggal_upload.visible && fields.tanggal_upload.required ? ew.Validators.required(fields.tanggal_upload.caption) : null], fields.tanggal_upload.isInvalid],
        ["kd_satker", [fields.kd_satker.visible && fields.kd_satker.required ? ew.Validators.required(fields.kd_satker.caption) : null], fields.kd_satker.isInvalid],
        ["idd_tahapan", [fields.idd_tahapan.visible && fields.idd_tahapan.required ? ew.Validators.required(fields.idd_tahapan.caption) : null], fields.idd_tahapan.isInvalid],
        ["tahun_anggaran", [fields.tahun_anggaran.visible && fields.tahun_anggaran.required ? ew.Validators.required(fields.tahun_anggaran.caption) : null], fields.tahun_anggaran.isInvalid],
        ["status", [fields.status.visible && fields.status.required ? ew.Validators.required(fields.status.caption) : null], fields.status.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fpertanggungjawabanlist,
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
    fpertanggungjawabanlist.validate = function () {
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
    fpertanggungjawabanlist.emptyRow = function (rowIndex) {
        var fobj = this.getForm();
        if (ew.valueChanged(fobj, rowIndex, "tanggal_upload", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "kd_satker", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "idd_tahapan", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "tahun_anggaran", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "status", false))
            return false;
        return true;
    }

    // Form_CustomValidate
    fpertanggungjawabanlist.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fpertanggungjawabanlist.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fpertanggungjawabanlist.lists.kd_satker = <?= $Page->kd_satker->toClientList($Page) ?>;
    fpertanggungjawabanlist.lists.idd_tahapan = <?= $Page->idd_tahapan->toClientList($Page) ?>;
    fpertanggungjawabanlist.lists.tahun_anggaran = <?= $Page->tahun_anggaran->toClientList($Page) ?>;
    fpertanggungjawabanlist.lists.status = <?= $Page->status->toClientList($Page) ?>;
    loadjs.done("fpertanggungjawabanlist");
});
var fpertanggungjawabanlistsrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object for search
    fpertanggungjawabanlistsrch = currentSearchForm = new ew.Form("fpertanggungjawabanlistsrch");

    // Dynamic selection lists

    // Filters
    fpertanggungjawabanlistsrch.filterList = <?= $Page->getFilterList() ?>;
    loadjs.done("fpertanggungjawabanlistsrch");
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
<form name="fpertanggungjawabanlistsrch" id="fpertanggungjawabanlistsrch" class="form-inline ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>">
<div id="fpertanggungjawabanlistsrch-search-panel" class="<?= $Page->SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="pertanggungjawaban">
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
<div class="card ew-card ew-grid<?php if ($Page->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> pertanggungjawaban">
<form name="fpertanggungjawabanlist" id="fpertanggungjawabanlist" class="form-inline ew-form ew-list-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="pertanggungjawaban">
<div id="gmp_pertanggungjawaban" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<?php if ($Page->TotalRecords > 0 || $Page->isAdd() || $Page->isCopy() || $Page->isGridEdit()) { ?>
<table id="tbl_pertanggungjawabanlist" class="table ew-table"><!-- .ew-table -->
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
<?php if ($Page->tanggal_upload->Visible) { // tanggal_upload ?>
        <th data-name="tanggal_upload" class="<?= $Page->tanggal_upload->headerCellClass() ?>"><div id="elh_pertanggungjawaban_tanggal_upload" class="pertanggungjawaban_tanggal_upload"><?= $Page->renderSort($Page->tanggal_upload) ?></div></th>
<?php } ?>
<?php if ($Page->kd_satker->Visible) { // kd_satker ?>
        <th data-name="kd_satker" class="<?= $Page->kd_satker->headerCellClass() ?>"><div id="elh_pertanggungjawaban_kd_satker" class="pertanggungjawaban_kd_satker"><?= $Page->renderSort($Page->kd_satker) ?></div></th>
<?php } ?>
<?php if ($Page->idd_tahapan->Visible) { // idd_tahapan ?>
        <th data-name="idd_tahapan" class="<?= $Page->idd_tahapan->headerCellClass() ?>"><div id="elh_pertanggungjawaban_idd_tahapan" class="pertanggungjawaban_idd_tahapan"><?= $Page->renderSort($Page->idd_tahapan) ?></div></th>
<?php } ?>
<?php if ($Page->tahun_anggaran->Visible) { // tahun_anggaran ?>
        <th data-name="tahun_anggaran" class="<?= $Page->tahun_anggaran->headerCellClass() ?>"><div id="elh_pertanggungjawaban_tahun_anggaran" class="pertanggungjawaban_tahun_anggaran"><?= $Page->renderSort($Page->tahun_anggaran) ?></div></th>
<?php } ?>
<?php if ($Page->status->Visible) { // status ?>
        <th data-name="status" class="<?= $Page->status->headerCellClass() ?>"><div id="elh_pertanggungjawaban_status" class="pertanggungjawaban_status"><?= $Page->renderSort($Page->status) ?></div></th>
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
        if ($Page->isAdd())
            $Page->loadRowValues();
        if ($Page->EventCancelled) // Insert failed
            $Page->restoreFormValues(); // Restore form values

        // Set row properties
        $Page->resetAttributes();
        $Page->RowAttrs->merge(["data-rowindex" => 0, "id" => "r0_pertanggungjawaban", "data-rowtype" => ROWTYPE_ADD]);
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
    <?php if ($Page->tanggal_upload->Visible) { // tanggal_upload ?>
        <td data-name="tanggal_upload">
<span id="el<?= $Page->RowCount ?>_pertanggungjawaban_tanggal_upload" class="form-group pertanggungjawaban_tanggal_upload">
<input type="<?= $Page->tanggal_upload->getInputTextType() ?>" data-table="pertanggungjawaban" data-field="x_tanggal_upload" data-format="1" name="x<?= $Page->RowIndex ?>_tanggal_upload" id="x<?= $Page->RowIndex ?>_tanggal_upload" placeholder="<?= HtmlEncode($Page->tanggal_upload->getPlaceHolder()) ?>" value="<?= $Page->tanggal_upload->EditValue ?>"<?= $Page->tanggal_upload->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->tanggal_upload->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="pertanggungjawaban" data-field="x_tanggal_upload" data-hidden="1" name="o<?= $Page->RowIndex ?>_tanggal_upload" id="o<?= $Page->RowIndex ?>_tanggal_upload" value="<?= HtmlEncode($Page->tanggal_upload->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Page->kd_satker->Visible) { // kd_satker ?>
        <td data-name="kd_satker">
<span id="el<?= $Page->RowCount ?>_pertanggungjawaban_kd_satker" class="form-group pertanggungjawaban_kd_satker">
    <select
        id="x<?= $Page->RowIndex ?>_kd_satker"
        name="x<?= $Page->RowIndex ?>_kd_satker"
        class="form-control ew-select<?= $Page->kd_satker->isInvalidClass() ?>"
        data-select2-id="pertanggungjawaban_x<?= $Page->RowIndex ?>_kd_satker"
        data-table="pertanggungjawaban"
        data-field="x_kd_satker"
        data-value-separator="<?= $Page->kd_satker->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->kd_satker->getPlaceHolder()) ?>"
        <?= $Page->kd_satker->editAttributes() ?>>
        <?= $Page->kd_satker->selectOptionListHtml("x{$Page->RowIndex}_kd_satker") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->kd_satker->getErrorMessage() ?></div>
<?= $Page->kd_satker->Lookup->getParamTag($Page, "p_x" . $Page->RowIndex . "_kd_satker") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='pertanggungjawaban_x<?= $Page->RowIndex ?>_kd_satker']"),
        options = { name: "x<?= $Page->RowIndex ?>_kd_satker", selectId: "pertanggungjawaban_x<?= $Page->RowIndex ?>_kd_satker", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.pertanggungjawaban.fields.kd_satker.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<input type="hidden" data-table="pertanggungjawaban" data-field="x_kd_satker" data-hidden="1" name="o<?= $Page->RowIndex ?>_kd_satker" id="o<?= $Page->RowIndex ?>_kd_satker" value="<?= HtmlEncode($Page->kd_satker->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Page->idd_tahapan->Visible) { // idd_tahapan ?>
        <td data-name="idd_tahapan">
<span id="el<?= $Page->RowCount ?>_pertanggungjawaban_idd_tahapan" class="form-group pertanggungjawaban_idd_tahapan">
    <select
        id="x<?= $Page->RowIndex ?>_idd_tahapan"
        name="x<?= $Page->RowIndex ?>_idd_tahapan"
        class="form-control ew-select<?= $Page->idd_tahapan->isInvalidClass() ?>"
        data-select2-id="pertanggungjawaban_x<?= $Page->RowIndex ?>_idd_tahapan"
        data-table="pertanggungjawaban"
        data-field="x_idd_tahapan"
        data-value-separator="<?= $Page->idd_tahapan->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->idd_tahapan->getPlaceHolder()) ?>"
        <?= $Page->idd_tahapan->editAttributes() ?>>
        <?= $Page->idd_tahapan->selectOptionListHtml("x{$Page->RowIndex}_idd_tahapan") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->idd_tahapan->getErrorMessage() ?></div>
<?= $Page->idd_tahapan->Lookup->getParamTag($Page, "p_x" . $Page->RowIndex . "_idd_tahapan") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='pertanggungjawaban_x<?= $Page->RowIndex ?>_idd_tahapan']"),
        options = { name: "x<?= $Page->RowIndex ?>_idd_tahapan", selectId: "pertanggungjawaban_x<?= $Page->RowIndex ?>_idd_tahapan", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.pertanggungjawaban.fields.idd_tahapan.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<input type="hidden" data-table="pertanggungjawaban" data-field="x_idd_tahapan" data-hidden="1" name="o<?= $Page->RowIndex ?>_idd_tahapan" id="o<?= $Page->RowIndex ?>_idd_tahapan" value="<?= HtmlEncode($Page->idd_tahapan->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Page->tahun_anggaran->Visible) { // tahun_anggaran ?>
        <td data-name="tahun_anggaran">
<span id="el<?= $Page->RowCount ?>_pertanggungjawaban_tahun_anggaran" class="form-group pertanggungjawaban_tahun_anggaran">
    <select
        id="x<?= $Page->RowIndex ?>_tahun_anggaran"
        name="x<?= $Page->RowIndex ?>_tahun_anggaran"
        class="form-control ew-select<?= $Page->tahun_anggaran->isInvalidClass() ?>"
        data-select2-id="pertanggungjawaban_x<?= $Page->RowIndex ?>_tahun_anggaran"
        data-table="pertanggungjawaban"
        data-field="x_tahun_anggaran"
        data-value-separator="<?= $Page->tahun_anggaran->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->tahun_anggaran->getPlaceHolder()) ?>"
        <?= $Page->tahun_anggaran->editAttributes() ?>>
        <?= $Page->tahun_anggaran->selectOptionListHtml("x{$Page->RowIndex}_tahun_anggaran") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->tahun_anggaran->getErrorMessage() ?></div>
<?= $Page->tahun_anggaran->Lookup->getParamTag($Page, "p_x" . $Page->RowIndex . "_tahun_anggaran") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='pertanggungjawaban_x<?= $Page->RowIndex ?>_tahun_anggaran']"),
        options = { name: "x<?= $Page->RowIndex ?>_tahun_anggaran", selectId: "pertanggungjawaban_x<?= $Page->RowIndex ?>_tahun_anggaran", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.pertanggungjawaban.fields.tahun_anggaran.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<input type="hidden" data-table="pertanggungjawaban" data-field="x_tahun_anggaran" data-hidden="1" name="o<?= $Page->RowIndex ?>_tahun_anggaran" id="o<?= $Page->RowIndex ?>_tahun_anggaran" value="<?= HtmlEncode($Page->tahun_anggaran->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Page->status->Visible) { // status ?>
        <td data-name="status">
<span id="el<?= $Page->RowCount ?>_pertanggungjawaban_status" class="form-group pertanggungjawaban_status">
    <select
        id="x<?= $Page->RowIndex ?>_status"
        name="x<?= $Page->RowIndex ?>_status"
        class="form-control ew-select<?= $Page->status->isInvalidClass() ?>"
        data-select2-id="pertanggungjawaban_x<?= $Page->RowIndex ?>_status"
        data-table="pertanggungjawaban"
        data-field="x_status"
        data-value-separator="<?= $Page->status->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->status->getPlaceHolder()) ?>"
        <?= $Page->status->editAttributes() ?>>
        <?= $Page->status->selectOptionListHtml("x{$Page->RowIndex}_status") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->status->getErrorMessage() ?></div>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='pertanggungjawaban_x<?= $Page->RowIndex ?>_status']"),
        options = { name: "x<?= $Page->RowIndex ?>_status", selectId: "pertanggungjawaban_x<?= $Page->RowIndex ?>_status", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.data = ew.vars.tables.pertanggungjawaban.fields.status.lookupOptions;
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.pertanggungjawaban.fields.status.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<input type="hidden" data-table="pertanggungjawaban" data-field="x_status" data-hidden="1" name="o<?= $Page->RowIndex ?>_status" id="o<?= $Page->RowIndex ?>_status" value="<?= HtmlEncode($Page->status->OldValue) ?>">
</td>
    <?php } ?>
<?php
// Render list options (body, right)
$Page->ListOptions->render("body", "right", $Page->RowCount);
?>
<script>
loadjs.ready(["fpertanggungjawabanlist","load"], function() {
    fpertanggungjawabanlist.updateLists(<?= $Page->RowIndex ?>);
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
        $Page->RowAttrs->merge(["data-rowindex" => $Page->RowCount, "id" => "r" . $Page->RowCount . "_pertanggungjawaban", "data-rowtype" => $Page->RowType]);

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
    <?php if ($Page->tanggal_upload->Visible) { // tanggal_upload ?>
        <td data-name="tanggal_upload" <?= $Page->tanggal_upload->cellAttributes() ?>>
<?php if ($Page->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Page->RowCount ?>_pertanggungjawaban_tanggal_upload" class="form-group">
<input type="<?= $Page->tanggal_upload->getInputTextType() ?>" data-table="pertanggungjawaban" data-field="x_tanggal_upload" data-format="1" name="x<?= $Page->RowIndex ?>_tanggal_upload" id="x<?= $Page->RowIndex ?>_tanggal_upload" placeholder="<?= HtmlEncode($Page->tanggal_upload->getPlaceHolder()) ?>" value="<?= $Page->tanggal_upload->EditValue ?>"<?= $Page->tanggal_upload->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->tanggal_upload->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="pertanggungjawaban" data-field="x_tanggal_upload" data-hidden="1" name="o<?= $Page->RowIndex ?>_tanggal_upload" id="o<?= $Page->RowIndex ?>_tanggal_upload" value="<?= HtmlEncode($Page->tanggal_upload->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Page->RowCount ?>_pertanggungjawaban_tanggal_upload" class="form-group">
<input type="hidden" data-table="pertanggungjawaban" data-field="x_tanggal_upload" data-hidden="1" name="x<?= $Page->RowIndex ?>_tanggal_upload" id="x<?= $Page->RowIndex ?>_tanggal_upload" value="<?= HtmlEncode($Page->tanggal_upload->CurrentValue) ?>">
</span>
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Page->RowCount ?>_pertanggungjawaban_tanggal_upload">
<span<?= $Page->tanggal_upload->viewAttributes() ?>>
<?= $Page->tanggal_upload->getViewValue() ?></span>
</span>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Page->kd_satker->Visible) { // kd_satker ?>
        <td data-name="kd_satker" <?= $Page->kd_satker->cellAttributes() ?>>
<?php if ($Page->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Page->RowCount ?>_pertanggungjawaban_kd_satker" class="form-group">
    <select
        id="x<?= $Page->RowIndex ?>_kd_satker"
        name="x<?= $Page->RowIndex ?>_kd_satker"
        class="form-control ew-select<?= $Page->kd_satker->isInvalidClass() ?>"
        data-select2-id="pertanggungjawaban_x<?= $Page->RowIndex ?>_kd_satker"
        data-table="pertanggungjawaban"
        data-field="x_kd_satker"
        data-value-separator="<?= $Page->kd_satker->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->kd_satker->getPlaceHolder()) ?>"
        <?= $Page->kd_satker->editAttributes() ?>>
        <?= $Page->kd_satker->selectOptionListHtml("x{$Page->RowIndex}_kd_satker") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->kd_satker->getErrorMessage() ?></div>
<?= $Page->kd_satker->Lookup->getParamTag($Page, "p_x" . $Page->RowIndex . "_kd_satker") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='pertanggungjawaban_x<?= $Page->RowIndex ?>_kd_satker']"),
        options = { name: "x<?= $Page->RowIndex ?>_kd_satker", selectId: "pertanggungjawaban_x<?= $Page->RowIndex ?>_kd_satker", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.pertanggungjawaban.fields.kd_satker.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<input type="hidden" data-table="pertanggungjawaban" data-field="x_kd_satker" data-hidden="1" name="o<?= $Page->RowIndex ?>_kd_satker" id="o<?= $Page->RowIndex ?>_kd_satker" value="<?= HtmlEncode($Page->kd_satker->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Page->RowCount ?>_pertanggungjawaban_kd_satker" class="form-group">
    <select
        id="x<?= $Page->RowIndex ?>_kd_satker"
        name="x<?= $Page->RowIndex ?>_kd_satker"
        class="form-control ew-select<?= $Page->kd_satker->isInvalidClass() ?>"
        data-select2-id="pertanggungjawaban_x<?= $Page->RowIndex ?>_kd_satker"
        data-table="pertanggungjawaban"
        data-field="x_kd_satker"
        data-value-separator="<?= $Page->kd_satker->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->kd_satker->getPlaceHolder()) ?>"
        <?= $Page->kd_satker->editAttributes() ?>>
        <?= $Page->kd_satker->selectOptionListHtml("x{$Page->RowIndex}_kd_satker") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->kd_satker->getErrorMessage() ?></div>
<?= $Page->kd_satker->Lookup->getParamTag($Page, "p_x" . $Page->RowIndex . "_kd_satker") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='pertanggungjawaban_x<?= $Page->RowIndex ?>_kd_satker']"),
        options = { name: "x<?= $Page->RowIndex ?>_kd_satker", selectId: "pertanggungjawaban_x<?= $Page->RowIndex ?>_kd_satker", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.pertanggungjawaban.fields.kd_satker.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Page->RowCount ?>_pertanggungjawaban_kd_satker">
<span<?= $Page->kd_satker->viewAttributes() ?>>
<?= $Page->kd_satker->getViewValue() ?></span>
</span>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Page->idd_tahapan->Visible) { // idd_tahapan ?>
        <td data-name="idd_tahapan" <?= $Page->idd_tahapan->cellAttributes() ?>>
<?php if ($Page->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Page->RowCount ?>_pertanggungjawaban_idd_tahapan" class="form-group">
    <select
        id="x<?= $Page->RowIndex ?>_idd_tahapan"
        name="x<?= $Page->RowIndex ?>_idd_tahapan"
        class="form-control ew-select<?= $Page->idd_tahapan->isInvalidClass() ?>"
        data-select2-id="pertanggungjawaban_x<?= $Page->RowIndex ?>_idd_tahapan"
        data-table="pertanggungjawaban"
        data-field="x_idd_tahapan"
        data-value-separator="<?= $Page->idd_tahapan->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->idd_tahapan->getPlaceHolder()) ?>"
        <?= $Page->idd_tahapan->editAttributes() ?>>
        <?= $Page->idd_tahapan->selectOptionListHtml("x{$Page->RowIndex}_idd_tahapan") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->idd_tahapan->getErrorMessage() ?></div>
<?= $Page->idd_tahapan->Lookup->getParamTag($Page, "p_x" . $Page->RowIndex . "_idd_tahapan") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='pertanggungjawaban_x<?= $Page->RowIndex ?>_idd_tahapan']"),
        options = { name: "x<?= $Page->RowIndex ?>_idd_tahapan", selectId: "pertanggungjawaban_x<?= $Page->RowIndex ?>_idd_tahapan", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.pertanggungjawaban.fields.idd_tahapan.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<input type="hidden" data-table="pertanggungjawaban" data-field="x_idd_tahapan" data-hidden="1" name="o<?= $Page->RowIndex ?>_idd_tahapan" id="o<?= $Page->RowIndex ?>_idd_tahapan" value="<?= HtmlEncode($Page->idd_tahapan->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Page->RowCount ?>_pertanggungjawaban_idd_tahapan" class="form-group">
    <select
        id="x<?= $Page->RowIndex ?>_idd_tahapan"
        name="x<?= $Page->RowIndex ?>_idd_tahapan"
        class="form-control ew-select<?= $Page->idd_tahapan->isInvalidClass() ?>"
        data-select2-id="pertanggungjawaban_x<?= $Page->RowIndex ?>_idd_tahapan"
        data-table="pertanggungjawaban"
        data-field="x_idd_tahapan"
        data-value-separator="<?= $Page->idd_tahapan->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->idd_tahapan->getPlaceHolder()) ?>"
        <?= $Page->idd_tahapan->editAttributes() ?>>
        <?= $Page->idd_tahapan->selectOptionListHtml("x{$Page->RowIndex}_idd_tahapan") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->idd_tahapan->getErrorMessage() ?></div>
<?= $Page->idd_tahapan->Lookup->getParamTag($Page, "p_x" . $Page->RowIndex . "_idd_tahapan") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='pertanggungjawaban_x<?= $Page->RowIndex ?>_idd_tahapan']"),
        options = { name: "x<?= $Page->RowIndex ?>_idd_tahapan", selectId: "pertanggungjawaban_x<?= $Page->RowIndex ?>_idd_tahapan", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.pertanggungjawaban.fields.idd_tahapan.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Page->RowCount ?>_pertanggungjawaban_idd_tahapan">
<span<?= $Page->idd_tahapan->viewAttributes() ?>>
<?= $Page->idd_tahapan->getViewValue() ?></span>
</span>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Page->tahun_anggaran->Visible) { // tahun_anggaran ?>
        <td data-name="tahun_anggaran" <?= $Page->tahun_anggaran->cellAttributes() ?>>
<?php if ($Page->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Page->RowCount ?>_pertanggungjawaban_tahun_anggaran" class="form-group">
    <select
        id="x<?= $Page->RowIndex ?>_tahun_anggaran"
        name="x<?= $Page->RowIndex ?>_tahun_anggaran"
        class="form-control ew-select<?= $Page->tahun_anggaran->isInvalidClass() ?>"
        data-select2-id="pertanggungjawaban_x<?= $Page->RowIndex ?>_tahun_anggaran"
        data-table="pertanggungjawaban"
        data-field="x_tahun_anggaran"
        data-value-separator="<?= $Page->tahun_anggaran->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->tahun_anggaran->getPlaceHolder()) ?>"
        <?= $Page->tahun_anggaran->editAttributes() ?>>
        <?= $Page->tahun_anggaran->selectOptionListHtml("x{$Page->RowIndex}_tahun_anggaran") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->tahun_anggaran->getErrorMessage() ?></div>
<?= $Page->tahun_anggaran->Lookup->getParamTag($Page, "p_x" . $Page->RowIndex . "_tahun_anggaran") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='pertanggungjawaban_x<?= $Page->RowIndex ?>_tahun_anggaran']"),
        options = { name: "x<?= $Page->RowIndex ?>_tahun_anggaran", selectId: "pertanggungjawaban_x<?= $Page->RowIndex ?>_tahun_anggaran", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.pertanggungjawaban.fields.tahun_anggaran.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<input type="hidden" data-table="pertanggungjawaban" data-field="x_tahun_anggaran" data-hidden="1" name="o<?= $Page->RowIndex ?>_tahun_anggaran" id="o<?= $Page->RowIndex ?>_tahun_anggaran" value="<?= HtmlEncode($Page->tahun_anggaran->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Page->RowCount ?>_pertanggungjawaban_tahun_anggaran" class="form-group">
    <select
        id="x<?= $Page->RowIndex ?>_tahun_anggaran"
        name="x<?= $Page->RowIndex ?>_tahun_anggaran"
        class="form-control ew-select<?= $Page->tahun_anggaran->isInvalidClass() ?>"
        data-select2-id="pertanggungjawaban_x<?= $Page->RowIndex ?>_tahun_anggaran"
        data-table="pertanggungjawaban"
        data-field="x_tahun_anggaran"
        data-value-separator="<?= $Page->tahun_anggaran->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->tahun_anggaran->getPlaceHolder()) ?>"
        <?= $Page->tahun_anggaran->editAttributes() ?>>
        <?= $Page->tahun_anggaran->selectOptionListHtml("x{$Page->RowIndex}_tahun_anggaran") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->tahun_anggaran->getErrorMessage() ?></div>
<?= $Page->tahun_anggaran->Lookup->getParamTag($Page, "p_x" . $Page->RowIndex . "_tahun_anggaran") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='pertanggungjawaban_x<?= $Page->RowIndex ?>_tahun_anggaran']"),
        options = { name: "x<?= $Page->RowIndex ?>_tahun_anggaran", selectId: "pertanggungjawaban_x<?= $Page->RowIndex ?>_tahun_anggaran", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.pertanggungjawaban.fields.tahun_anggaran.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Page->RowCount ?>_pertanggungjawaban_tahun_anggaran">
<span<?= $Page->tahun_anggaran->viewAttributes() ?>>
<?= $Page->tahun_anggaran->getViewValue() ?></span>
</span>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Page->status->Visible) { // status ?>
        <td data-name="status" <?= $Page->status->cellAttributes() ?>>
<?php if ($Page->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Page->RowCount ?>_pertanggungjawaban_status" class="form-group">
    <select
        id="x<?= $Page->RowIndex ?>_status"
        name="x<?= $Page->RowIndex ?>_status"
        class="form-control ew-select<?= $Page->status->isInvalidClass() ?>"
        data-select2-id="pertanggungjawaban_x<?= $Page->RowIndex ?>_status"
        data-table="pertanggungjawaban"
        data-field="x_status"
        data-value-separator="<?= $Page->status->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->status->getPlaceHolder()) ?>"
        <?= $Page->status->editAttributes() ?>>
        <?= $Page->status->selectOptionListHtml("x{$Page->RowIndex}_status") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->status->getErrorMessage() ?></div>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='pertanggungjawaban_x<?= $Page->RowIndex ?>_status']"),
        options = { name: "x<?= $Page->RowIndex ?>_status", selectId: "pertanggungjawaban_x<?= $Page->RowIndex ?>_status", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.data = ew.vars.tables.pertanggungjawaban.fields.status.lookupOptions;
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.pertanggungjawaban.fields.status.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<input type="hidden" data-table="pertanggungjawaban" data-field="x_status" data-hidden="1" name="o<?= $Page->RowIndex ?>_status" id="o<?= $Page->RowIndex ?>_status" value="<?= HtmlEncode($Page->status->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Page->RowCount ?>_pertanggungjawaban_status" class="form-group">
    <select
        id="x<?= $Page->RowIndex ?>_status"
        name="x<?= $Page->RowIndex ?>_status"
        class="form-control ew-select<?= $Page->status->isInvalidClass() ?>"
        data-select2-id="pertanggungjawaban_x<?= $Page->RowIndex ?>_status"
        data-table="pertanggungjawaban"
        data-field="x_status"
        data-value-separator="<?= $Page->status->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->status->getPlaceHolder()) ?>"
        <?= $Page->status->editAttributes() ?>>
        <?= $Page->status->selectOptionListHtml("x{$Page->RowIndex}_status") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->status->getErrorMessage() ?></div>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='pertanggungjawaban_x<?= $Page->RowIndex ?>_status']"),
        options = { name: "x<?= $Page->RowIndex ?>_status", selectId: "pertanggungjawaban_x<?= $Page->RowIndex ?>_status", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.data = ew.vars.tables.pertanggungjawaban.fields.status.lookupOptions;
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.pertanggungjawaban.fields.status.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Page->RowCount ?>_pertanggungjawaban_status">
<span<?= $Page->status->viewAttributes() ?>>
<?= $Page->status->getViewValue() ?></span>
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
loadjs.ready(["fpertanggungjawabanlist","load"], function () {
    fpertanggungjawabanlist.updateLists(<?= $Page->RowIndex ?>);
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
        $Page->RowAttrs->merge(["data-rowindex" => $Page->RowIndex, "id" => "r0_pertanggungjawaban", "data-rowtype" => ROWTYPE_ADD]);
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
    <?php if ($Page->tanggal_upload->Visible) { // tanggal_upload ?>
        <td data-name="tanggal_upload">
<span id="el$rowindex$_pertanggungjawaban_tanggal_upload" class="form-group pertanggungjawaban_tanggal_upload">
<input type="<?= $Page->tanggal_upload->getInputTextType() ?>" data-table="pertanggungjawaban" data-field="x_tanggal_upload" data-format="1" name="x<?= $Page->RowIndex ?>_tanggal_upload" id="x<?= $Page->RowIndex ?>_tanggal_upload" placeholder="<?= HtmlEncode($Page->tanggal_upload->getPlaceHolder()) ?>" value="<?= $Page->tanggal_upload->EditValue ?>"<?= $Page->tanggal_upload->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->tanggal_upload->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="pertanggungjawaban" data-field="x_tanggal_upload" data-hidden="1" name="o<?= $Page->RowIndex ?>_tanggal_upload" id="o<?= $Page->RowIndex ?>_tanggal_upload" value="<?= HtmlEncode($Page->tanggal_upload->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Page->kd_satker->Visible) { // kd_satker ?>
        <td data-name="kd_satker">
<span id="el$rowindex$_pertanggungjawaban_kd_satker" class="form-group pertanggungjawaban_kd_satker">
    <select
        id="x<?= $Page->RowIndex ?>_kd_satker"
        name="x<?= $Page->RowIndex ?>_kd_satker"
        class="form-control ew-select<?= $Page->kd_satker->isInvalidClass() ?>"
        data-select2-id="pertanggungjawaban_x<?= $Page->RowIndex ?>_kd_satker"
        data-table="pertanggungjawaban"
        data-field="x_kd_satker"
        data-value-separator="<?= $Page->kd_satker->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->kd_satker->getPlaceHolder()) ?>"
        <?= $Page->kd_satker->editAttributes() ?>>
        <?= $Page->kd_satker->selectOptionListHtml("x{$Page->RowIndex}_kd_satker") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->kd_satker->getErrorMessage() ?></div>
<?= $Page->kd_satker->Lookup->getParamTag($Page, "p_x" . $Page->RowIndex . "_kd_satker") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='pertanggungjawaban_x<?= $Page->RowIndex ?>_kd_satker']"),
        options = { name: "x<?= $Page->RowIndex ?>_kd_satker", selectId: "pertanggungjawaban_x<?= $Page->RowIndex ?>_kd_satker", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.pertanggungjawaban.fields.kd_satker.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<input type="hidden" data-table="pertanggungjawaban" data-field="x_kd_satker" data-hidden="1" name="o<?= $Page->RowIndex ?>_kd_satker" id="o<?= $Page->RowIndex ?>_kd_satker" value="<?= HtmlEncode($Page->kd_satker->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Page->idd_tahapan->Visible) { // idd_tahapan ?>
        <td data-name="idd_tahapan">
<span id="el$rowindex$_pertanggungjawaban_idd_tahapan" class="form-group pertanggungjawaban_idd_tahapan">
    <select
        id="x<?= $Page->RowIndex ?>_idd_tahapan"
        name="x<?= $Page->RowIndex ?>_idd_tahapan"
        class="form-control ew-select<?= $Page->idd_tahapan->isInvalidClass() ?>"
        data-select2-id="pertanggungjawaban_x<?= $Page->RowIndex ?>_idd_tahapan"
        data-table="pertanggungjawaban"
        data-field="x_idd_tahapan"
        data-value-separator="<?= $Page->idd_tahapan->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->idd_tahapan->getPlaceHolder()) ?>"
        <?= $Page->idd_tahapan->editAttributes() ?>>
        <?= $Page->idd_tahapan->selectOptionListHtml("x{$Page->RowIndex}_idd_tahapan") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->idd_tahapan->getErrorMessage() ?></div>
<?= $Page->idd_tahapan->Lookup->getParamTag($Page, "p_x" . $Page->RowIndex . "_idd_tahapan") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='pertanggungjawaban_x<?= $Page->RowIndex ?>_idd_tahapan']"),
        options = { name: "x<?= $Page->RowIndex ?>_idd_tahapan", selectId: "pertanggungjawaban_x<?= $Page->RowIndex ?>_idd_tahapan", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.pertanggungjawaban.fields.idd_tahapan.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<input type="hidden" data-table="pertanggungjawaban" data-field="x_idd_tahapan" data-hidden="1" name="o<?= $Page->RowIndex ?>_idd_tahapan" id="o<?= $Page->RowIndex ?>_idd_tahapan" value="<?= HtmlEncode($Page->idd_tahapan->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Page->tahun_anggaran->Visible) { // tahun_anggaran ?>
        <td data-name="tahun_anggaran">
<span id="el$rowindex$_pertanggungjawaban_tahun_anggaran" class="form-group pertanggungjawaban_tahun_anggaran">
    <select
        id="x<?= $Page->RowIndex ?>_tahun_anggaran"
        name="x<?= $Page->RowIndex ?>_tahun_anggaran"
        class="form-control ew-select<?= $Page->tahun_anggaran->isInvalidClass() ?>"
        data-select2-id="pertanggungjawaban_x<?= $Page->RowIndex ?>_tahun_anggaran"
        data-table="pertanggungjawaban"
        data-field="x_tahun_anggaran"
        data-value-separator="<?= $Page->tahun_anggaran->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->tahun_anggaran->getPlaceHolder()) ?>"
        <?= $Page->tahun_anggaran->editAttributes() ?>>
        <?= $Page->tahun_anggaran->selectOptionListHtml("x{$Page->RowIndex}_tahun_anggaran") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->tahun_anggaran->getErrorMessage() ?></div>
<?= $Page->tahun_anggaran->Lookup->getParamTag($Page, "p_x" . $Page->RowIndex . "_tahun_anggaran") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='pertanggungjawaban_x<?= $Page->RowIndex ?>_tahun_anggaran']"),
        options = { name: "x<?= $Page->RowIndex ?>_tahun_anggaran", selectId: "pertanggungjawaban_x<?= $Page->RowIndex ?>_tahun_anggaran", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.pertanggungjawaban.fields.tahun_anggaran.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<input type="hidden" data-table="pertanggungjawaban" data-field="x_tahun_anggaran" data-hidden="1" name="o<?= $Page->RowIndex ?>_tahun_anggaran" id="o<?= $Page->RowIndex ?>_tahun_anggaran" value="<?= HtmlEncode($Page->tahun_anggaran->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Page->status->Visible) { // status ?>
        <td data-name="status">
<span id="el$rowindex$_pertanggungjawaban_status" class="form-group pertanggungjawaban_status">
    <select
        id="x<?= $Page->RowIndex ?>_status"
        name="x<?= $Page->RowIndex ?>_status"
        class="form-control ew-select<?= $Page->status->isInvalidClass() ?>"
        data-select2-id="pertanggungjawaban_x<?= $Page->RowIndex ?>_status"
        data-table="pertanggungjawaban"
        data-field="x_status"
        data-value-separator="<?= $Page->status->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->status->getPlaceHolder()) ?>"
        <?= $Page->status->editAttributes() ?>>
        <?= $Page->status->selectOptionListHtml("x{$Page->RowIndex}_status") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->status->getErrorMessage() ?></div>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='pertanggungjawaban_x<?= $Page->RowIndex ?>_status']"),
        options = { name: "x<?= $Page->RowIndex ?>_status", selectId: "pertanggungjawaban_x<?= $Page->RowIndex ?>_status", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.data = ew.vars.tables.pertanggungjawaban.fields.status.lookupOptions;
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.pertanggungjawaban.fields.status.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<input type="hidden" data-table="pertanggungjawaban" data-field="x_status" data-hidden="1" name="o<?= $Page->RowIndex ?>_status" id="o<?= $Page->RowIndex ?>_status" value="<?= HtmlEncode($Page->status->OldValue) ?>">
</td>
    <?php } ?>
<?php
// Render list options (body, right)
$Page->ListOptions->render("body", "right", $Page->RowIndex);
?>
<script>
loadjs.ready(["fpertanggungjawabanlist","load"], function() {
    fpertanggungjawabanlist.updateLists(<?= $Page->RowIndex ?>);
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
    ew.addEventHandlers("pertanggungjawaban");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
