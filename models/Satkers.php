<?php

namespace PHPMaker2021\silpa;

use Doctrine\DBAL\ParameterType;

/**
 * Table class for satkers
 */
class Satkers extends DbTable
{
    protected $SqlFrom = "";
    protected $SqlSelect = null;
    protected $SqlSelectList = null;
    protected $SqlWhere = "";
    protected $SqlGroupBy = "";
    protected $SqlHaving = "";
    protected $SqlOrderBy = "";
    public $UseSessionForListSql = true;

    // Column CSS classes
    public $LeftColumnClass = "col-sm-2 col-form-label ew-label";
    public $RightColumnClass = "col-sm-10";
    public $OffsetColumnClass = "col-sm-10 offset-sm-2";
    public $TableLeftColumnClass = "w-col-2";

    // Export
    public $ExportDoc;

    // Fields
    public $idd_satker;
    public $kode_pemda;
    public $kode_satker;
    public $nama_satker;
    public $wilayah;
    public $idd_user;
    public $no_telepon;

    // Page ID
    public $PageID = ""; // To be overridden by subclass

    // Constructor
    public function __construct()
    {
        global $Language, $CurrentLanguage;
        parent::__construct();

        // Language object
        $Language = Container("language");
        $this->TableVar = 'satkers';
        $this->TableName = 'satkers';
        $this->TableType = 'TABLE';

        // Update Table
        $this->UpdateTable = "`satkers`";
        $this->Dbid = 'DB';
        $this->ExportAll = true;
        $this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
        $this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
        $this->ExportPageSize = "a4"; // Page size (PDF only)
        $this->ExportExcelPageOrientation = ""; // Page orientation (PhpSpreadsheet only)
        $this->ExportExcelPageSize = ""; // Page size (PhpSpreadsheet only)
        $this->ExportWordPageOrientation = "portrait"; // Page orientation (PHPWord only)
        $this->ExportWordColumnWidth = null; // Cell width (PHPWord only)
        $this->DetailAdd = false; // Allow detail add
        $this->DetailEdit = false; // Allow detail edit
        $this->DetailView = false; // Allow detail view
        $this->ShowMultipleDetails = false; // Show multiple details
        $this->GridAddRowCount = 5;
        $this->AllowAddDeleteRow = true; // Allow add/delete row
        $this->UserIDAllowSecurity = Config("DEFAULT_USER_ID_ALLOW_SECURITY"); // Default User ID allowed permissions
        $this->BasicSearch = new BasicSearch($this->TableVar);

        // idd_satker
        $this->idd_satker = new DbField('satkers', 'satkers', 'x_idd_satker', 'idd_satker', '`idd_satker`', '`idd_satker`', 3, 100, -1, false, '`idd_satker`', false, false, false, 'FORMATTED TEXT', 'NO');
        $this->idd_satker->IsAutoIncrement = true; // Autoincrement field
        $this->idd_satker->IsPrimaryKey = true; // Primary key field
        $this->idd_satker->Sortable = true; // Allow sort
        $this->idd_satker->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->idd_satker->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->idd_satker->Param, "CustomMsg");
        $this->Fields['idd_satker'] = &$this->idd_satker;

        // kode_pemda
        $this->kode_pemda = new DbField('satkers', 'satkers', 'x_kode_pemda', 'kode_pemda', '`kode_pemda`', '`kode_pemda`', 200, 100, -1, false, '`kode_pemda`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->kode_pemda->Nullable = false; // NOT NULL field
        $this->kode_pemda->Required = true; // Required field
        $this->kode_pemda->Sortable = true; // Allow sort
        $this->kode_pemda->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->kode_pemda->Param, "CustomMsg");
        $this->Fields['kode_pemda'] = &$this->kode_pemda;

        // kode_satker
        $this->kode_satker = new DbField('satkers', 'satkers', 'x_kode_satker', 'kode_satker', '`kode_satker`', '`kode_satker`', 3, 100, -1, false, '`kode_satker`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->kode_satker->Nullable = false; // NOT NULL field
        $this->kode_satker->Required = true; // Required field
        $this->kode_satker->Sortable = true; // Allow sort
        $this->kode_satker->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->kode_satker->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->kode_satker->Param, "CustomMsg");
        $this->Fields['kode_satker'] = &$this->kode_satker;

        // nama_satker
        $this->nama_satker = new DbField('satkers', 'satkers', 'x_nama_satker', 'nama_satker', '`nama_satker`', '`nama_satker`', 200, 200, -1, false, '`nama_satker`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->nama_satker->Nullable = false; // NOT NULL field
        $this->nama_satker->Required = true; // Required field
        $this->nama_satker->Sortable = true; // Allow sort
        $this->nama_satker->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->nama_satker->Param, "CustomMsg");
        $this->Fields['nama_satker'] = &$this->nama_satker;

        // wilayah
        $this->wilayah = new DbField('satkers', 'satkers', 'x_wilayah', 'wilayah', '`wilayah`', '`wilayah`', 3, 100, -1, false, '`EV__wilayah`', true, true, true, 'FORMATTED TEXT', 'SELECT');
        $this->wilayah->Nullable = false; // NOT NULL field
        $this->wilayah->Required = true; // Required field
        $this->wilayah->Sortable = true; // Allow sort
        $this->wilayah->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->wilayah->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        switch ($CurrentLanguage) {
            case "en":
                $this->wilayah->Lookup = new Lookup('wilayah', 'wilayah', true, 'idd_wilayah', ["nama_wilayah","","",""], [], [], [], [], [], [], '', '');
                break;
            default:
                $this->wilayah->Lookup = new Lookup('wilayah', 'wilayah', true, 'idd_wilayah', ["nama_wilayah","","",""], [], [], [], [], [], [], '', '');
                break;
        }
        $this->wilayah->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->wilayah->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->wilayah->Param, "CustomMsg");
        $this->Fields['wilayah'] = &$this->wilayah;

        // idd_user
        $this->idd_user = new DbField('satkers', 'satkers', 'x_idd_user', 'idd_user', '`idd_user`', '`idd_user`', 3, 100, -1, false, '`idd_user`', false, false, false, 'FORMATTED TEXT', 'SELECT');
        $this->idd_user->Nullable = false; // NOT NULL field
        $this->idd_user->Required = true; // Required field
        $this->idd_user->Sortable = true; // Allow sort
        $this->idd_user->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->idd_user->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        switch ($CurrentLanguage) {
            case "en":
                $this->idd_user->Lookup = new Lookup('idd_user', 'users', false, 'idd_user', ["username","","",""], [], [], [], [], [], [], '', '');
                break;
            default:
                $this->idd_user->Lookup = new Lookup('idd_user', 'users', false, 'idd_user', ["username","","",""], [], [], [], [], [], [], '', '');
                break;
        }
        $this->idd_user->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->idd_user->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->idd_user->Param, "CustomMsg");
        $this->Fields['idd_user'] = &$this->idd_user;

        // no_telepon
        $this->no_telepon = new DbField('satkers', 'satkers', 'x_no_telepon', 'no_telepon', '`no_telepon`', '`no_telepon`', 200, 25, -1, false, '`no_telepon`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->no_telepon->Nullable = false; // NOT NULL field
        $this->no_telepon->Required = true; // Required field
        $this->no_telepon->Sortable = true; // Allow sort
        $this->no_telepon->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->no_telepon->Param, "CustomMsg");
        $this->Fields['no_telepon'] = &$this->no_telepon;
    }

    // Field Visibility
    public function getFieldVisibility($fldParm)
    {
        global $Security;
        return $this->$fldParm->Visible; // Returns original value
    }

    // Set left column class (must be predefined col-*-* classes of Bootstrap grid system)
    public function setLeftColumnClass($class)
    {
        if (preg_match('/^col\-(\w+)\-(\d+)$/', $class, $match)) {
            $this->LeftColumnClass = $class . " col-form-label ew-label";
            $this->RightColumnClass = "col-" . $match[1] . "-" . strval(12 - (int)$match[2]);
            $this->OffsetColumnClass = $this->RightColumnClass . " " . str_replace("col-", "offset-", $class);
            $this->TableLeftColumnClass = preg_replace('/^col-\w+-(\d+)$/', "w-col-$1", $class); // Change to w-col-*
        }
    }

    // Single column sort
    public function updateSort(&$fld)
    {
        if ($this->CurrentOrder == $fld->Name) {
            $sortField = $fld->Expression;
            $lastSort = $fld->getSort();
            if (in_array($this->CurrentOrderType, ["ASC", "DESC", "NO"])) {
                $curSort = $this->CurrentOrderType;
            } else {
                $curSort = $lastSort;
            }
            $fld->setSort($curSort);
            $orderBy = in_array($curSort, ["ASC", "DESC"]) ? $sortField . " " . $curSort : "";
            $this->setSessionOrderBy($orderBy); // Save to Session
            $sortFieldList = ($fld->VirtualExpression != "") ? $fld->VirtualExpression : $sortField;
            $orderBy = in_array($curSort, ["ASC", "DESC"]) ? $sortFieldList . " " . $curSort : "";
            $this->setSessionOrderByList($orderBy); // Save to Session
        } else {
            $fld->setSort("");
        }
    }

    // Session ORDER BY for List page
    public function getSessionOrderByList()
    {
        return Session(PROJECT_NAME . "_" . $this->TableVar . "_" . Config("TABLE_ORDER_BY_LIST"));
    }

    public function setSessionOrderByList($v)
    {
        $_SESSION[PROJECT_NAME . "_" . $this->TableVar . "_" . Config("TABLE_ORDER_BY_LIST")] = $v;
    }

    // Table level SQL
    public function getSqlFrom() // From
    {
        return ($this->SqlFrom != "") ? $this->SqlFrom : "`satkers`";
    }

    public function sqlFrom() // For backward compatibility
    {
        return $this->getSqlFrom();
    }

    public function setSqlFrom($v)
    {
        $this->SqlFrom = $v;
    }

    public function getSqlSelect() // Select
    {
        return $this->SqlSelect ?? $this->getQueryBuilder()->select("*");
    }

    public function sqlSelect() // For backward compatibility
    {
        return $this->getSqlSelect();
    }

    public function setSqlSelect($v)
    {
        $this->SqlSelect = $v;
    }

    public function getSqlSelectList() // Select for List page
    {
        if ($this->SqlSelectList) {
            return $this->SqlSelectList;
        }
        $from = "(SELECT *, (SELECT DISTINCT `nama_wilayah` FROM `wilayah` `TMP_LOOKUPTABLE` WHERE `TMP_LOOKUPTABLE`.`idd_wilayah` = `satkers`.`wilayah` LIMIT 1) AS `EV__wilayah` FROM `satkers`)";
        return $from . " `TMP_TABLE`";
    }

    public function sqlSelectList() // For backward compatibility
    {
        return $this->getSqlSelectList();
    }

    public function setSqlSelectList($v)
    {
        $this->SqlSelectList = $v;
    }

    public function getSqlWhere() // Where
    {
        $where = ($this->SqlWhere != "") ? $this->SqlWhere : "";
        $this->DefaultFilter = "";
        AddFilter($where, $this->DefaultFilter);
        return $where;
    }

    public function sqlWhere() // For backward compatibility
    {
        return $this->getSqlWhere();
    }

    public function setSqlWhere($v)
    {
        $this->SqlWhere = $v;
    }

    public function getSqlGroupBy() // Group By
    {
        return ($this->SqlGroupBy != "") ? $this->SqlGroupBy : "";
    }

    public function sqlGroupBy() // For backward compatibility
    {
        return $this->getSqlGroupBy();
    }

    public function setSqlGroupBy($v)
    {
        $this->SqlGroupBy = $v;
    }

    public function getSqlHaving() // Having
    {
        return ($this->SqlHaving != "") ? $this->SqlHaving : "";
    }

    public function sqlHaving() // For backward compatibility
    {
        return $this->getSqlHaving();
    }

    public function setSqlHaving($v)
    {
        $this->SqlHaving = $v;
    }

    public function getSqlOrderBy() // Order By
    {
        return ($this->SqlOrderBy != "") ? $this->SqlOrderBy : $this->DefaultSort;
    }

    public function sqlOrderBy() // For backward compatibility
    {
        return $this->getSqlOrderBy();
    }

    public function setSqlOrderBy($v)
    {
        $this->SqlOrderBy = $v;
    }

    // Apply User ID filters
    public function applyUserIDFilters($filter)
    {
        return $filter;
    }

    // Check if User ID security allows view all
    public function userIDAllow($id = "")
    {
        $allow = $this->UserIDAllowSecurity;
        switch ($id) {
            case "add":
            case "copy":
            case "gridadd":
            case "register":
            case "addopt":
                return (($allow & 1) == 1);
            case "edit":
            case "gridedit":
            case "update":
            case "changepassword":
            case "resetpassword":
                return (($allow & 4) == 4);
            case "delete":
                return (($allow & 2) == 2);
            case "view":
                return (($allow & 32) == 32);
            case "search":
                return (($allow & 64) == 64);
            default:
                return (($allow & 8) == 8);
        }
    }

    /**
     * Get record count
     *
     * @param string|QueryBuilder $sql SQL or QueryBuilder
     * @param mixed $c Connection
     * @return int
     */
    public function getRecordCount($sql, $c = null)
    {
        $cnt = -1;
        $rs = null;
        if ($sql instanceof \Doctrine\DBAL\Query\QueryBuilder) { // Query builder
            $sqlwrk = clone $sql;
            $sqlwrk = $sqlwrk->resetQueryPart("orderBy")->getSQL();
        } else {
            $sqlwrk = $sql;
        }
        $pattern = '/^SELECT\s([\s\S]+)\sFROM\s/i';
        // Skip Custom View / SubQuery / SELECT DISTINCT / ORDER BY
        if (
            ($this->TableType == 'TABLE' || $this->TableType == 'VIEW' || $this->TableType == 'LINKTABLE') &&
            preg_match($pattern, $sqlwrk) && !preg_match('/\(\s*(SELECT[^)]+)\)/i', $sqlwrk) &&
            !preg_match('/^\s*select\s+distinct\s+/i', $sqlwrk) && !preg_match('/\s+order\s+by\s+/i', $sqlwrk)
        ) {
            $sqlwrk = "SELECT COUNT(*) FROM " . preg_replace($pattern, "", $sqlwrk);
        } else {
            $sqlwrk = "SELECT COUNT(*) FROM (" . $sqlwrk . ") COUNT_TABLE";
        }
        $conn = $c ?? $this->getConnection();
        $rs = $conn->executeQuery($sqlwrk);
        $cnt = $rs->fetchColumn();
        if ($cnt !== false) {
            return (int)$cnt;
        }

        // Unable to get count by SELECT COUNT(*), execute the SQL to get record count directly
        return ExecuteRecordCount($sql, $conn);
    }

    // Get SQL
    public function getSql($where, $orderBy = "")
    {
        return $this->buildSelectSql(
            $this->getSqlSelect(),
            $this->getSqlFrom(),
            $this->getSqlWhere(),
            $this->getSqlGroupBy(),
            $this->getSqlHaving(),
            $this->getSqlOrderBy(),
            $where,
            $orderBy
        )->getSQL();
    }

    // Table SQL
    public function getCurrentSql()
    {
        $filter = $this->CurrentFilter;
        $filter = $this->applyUserIDFilters($filter);
        $sort = $this->getSessionOrderBy();
        return $this->getSql($filter, $sort);
    }

    /**
     * Table SQL with List page filter
     *
     * @return QueryBuilder
     */
    public function getListSql()
    {
        $filter = $this->UseSessionForListSql ? $this->getSessionWhere() : "";
        AddFilter($filter, $this->CurrentFilter);
        $filter = $this->applyUserIDFilters($filter);
        $this->recordsetSelecting($filter);
        if ($this->useVirtualFields()) {
            $select = "*";
            $from = $this->getSqlSelectList();
            $sort = $this->UseSessionForListSql ? $this->getSessionOrderByList() : "";
        } else {
            $select = $this->getSqlSelect();
            $from = $this->getSqlFrom();
            $sort = $this->UseSessionForListSql ? $this->getSessionOrderBy() : "";
        }
        $this->Sort = $sort;
        return $this->buildSelectSql(
            $select,
            $from,
            $this->getSqlWhere(),
            $this->getSqlGroupBy(),
            $this->getSqlHaving(),
            $this->getSqlOrderBy(),
            $filter,
            $sort
        );
    }

    // Get ORDER BY clause
    public function getOrderBy()
    {
        $orderBy = $this->getSqlOrderBy();
        $sort = ($this->useVirtualFields()) ? $this->getSessionOrderByList() : $this->getSessionOrderBy();
        if ($orderBy != "" && $sort != "") {
            $orderBy .= ", " . $sort;
        } elseif ($sort != "") {
            $orderBy = $sort;
        }
        return $orderBy;
    }

    // Check if virtual fields is used in SQL
    protected function useVirtualFields()
    {
        $where = $this->UseSessionForListSql ? $this->getSessionWhere() : $this->CurrentFilter;
        $orderBy = $this->UseSessionForListSql ? $this->getSessionOrderByList() : "";
        if ($where != "") {
            $where = " " . str_replace(["(", ")"], ["", ""], $where) . " ";
        }
        if ($orderBy != "") {
            $orderBy = " " . str_replace(["(", ")"], ["", ""], $orderBy) . " ";
        }
        if (
            $this->wilayah->AdvancedSearch->SearchValue != "" ||
            $this->wilayah->AdvancedSearch->SearchValue2 != "" ||
            ContainsString($where, " " . $this->wilayah->VirtualExpression . " ")
        ) {
            return true;
        }
        if (ContainsString($orderBy, " " . $this->wilayah->VirtualExpression . " ")) {
            return true;
        }
        return false;
    }

    // Get record count based on filter (for detail record count in master table pages)
    public function loadRecordCount($filter)
    {
        $origFilter = $this->CurrentFilter;
        $this->CurrentFilter = $filter;
        $this->recordsetSelecting($this->CurrentFilter);
        $select = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlSelect() : $this->getQueryBuilder()->select("*");
        $groupBy = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlGroupBy() : "";
        $having = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlHaving() : "";
        $sql = $this->buildSelectSql($select, $this->getSqlFrom(), $this->getSqlWhere(), $groupBy, $having, "", $this->CurrentFilter, "");
        $cnt = $this->getRecordCount($sql);
        $this->CurrentFilter = $origFilter;
        return $cnt;
    }

    // Get record count (for current List page)
    public function listRecordCount()
    {
        $filter = $this->getSessionWhere();
        AddFilter($filter, $this->CurrentFilter);
        $filter = $this->applyUserIDFilters($filter);
        $this->recordsetSelecting($filter);
        $select = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlSelect() : $this->getQueryBuilder()->select("*");
        $groupBy = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlGroupBy() : "";
        $having = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlHaving() : "";
        if ($this->useVirtualFields()) {
            $sql = $this->buildSelectSql("*", $this->getSqlSelectList(), $this->getSqlWhere(), $groupBy, $having, "", $filter, "");
        } else {
            $sql = $this->buildSelectSql($select, $this->getSqlFrom(), $this->getSqlWhere(), $groupBy, $having, "", $filter, "");
        }
        $cnt = $this->getRecordCount($sql);
        return $cnt;
    }

    /**
     * INSERT statement
     *
     * @param mixed $rs
     * @return QueryBuilder
     */
    protected function insertSql(&$rs)
    {
        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder->insert($this->UpdateTable);
        foreach ($rs as $name => $value) {
            if (!isset($this->Fields[$name]) || $this->Fields[$name]->IsCustom) {
                continue;
            }
            $type = GetParameterType($this->Fields[$name], $value, $this->Dbid);
            $queryBuilder->setValue($this->Fields[$name]->Expression, $queryBuilder->createPositionalParameter($value, $type));
        }
        return $queryBuilder;
    }

    // Insert
    public function insert(&$rs)
    {
        $conn = $this->getConnection();
        $success = $this->insertSql($rs)->execute();
        if ($success) {
            // Get insert id if necessary
            $this->idd_satker->setDbValue($conn->lastInsertId());
            $rs['idd_satker'] = $this->idd_satker->DbValue;
        }
        return $success;
    }

    /**
     * UPDATE statement
     *
     * @param array $rs Data to be updated
     * @param string|array $where WHERE clause
     * @param string $curfilter Filter
     * @return QueryBuilder
     */
    protected function updateSql(&$rs, $where = "", $curfilter = true)
    {
        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder->update($this->UpdateTable);
        foreach ($rs as $name => $value) {
            if (!isset($this->Fields[$name]) || $this->Fields[$name]->IsCustom || $this->Fields[$name]->IsAutoIncrement) {
                continue;
            }
            $type = GetParameterType($this->Fields[$name], $value, $this->Dbid);
            $queryBuilder->set($this->Fields[$name]->Expression, $queryBuilder->createPositionalParameter($value, $type));
        }
        $filter = ($curfilter) ? $this->CurrentFilter : "";
        if (is_array($where)) {
            $where = $this->arrayToFilter($where);
        }
        AddFilter($filter, $where);
        if ($filter != "") {
            $queryBuilder->where($filter);
        }
        return $queryBuilder;
    }

    // Update
    public function update(&$rs, $where = "", $rsold = null, $curfilter = true)
    {
        // If no field is updated, execute may return 0. Treat as success
        $success = $this->updateSql($rs, $where, $curfilter)->execute();
        $success = ($success > 0) ? $success : true;
        return $success;
    }

    /**
     * DELETE statement
     *
     * @param array $rs Key values
     * @param string|array $where WHERE clause
     * @param string $curfilter Filter
     * @return QueryBuilder
     */
    protected function deleteSql(&$rs, $where = "", $curfilter = true)
    {
        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder->delete($this->UpdateTable);
        if (is_array($where)) {
            $where = $this->arrayToFilter($where);
        }
        if ($rs) {
            if (array_key_exists('idd_satker', $rs)) {
                AddFilter($where, QuotedName('idd_satker', $this->Dbid) . '=' . QuotedValue($rs['idd_satker'], $this->idd_satker->DataType, $this->Dbid));
            }
        }
        $filter = ($curfilter) ? $this->CurrentFilter : "";
        AddFilter($filter, $where);
        return $queryBuilder->where($filter != "" ? $filter : "0=1");
    }

    // Delete
    public function delete(&$rs, $where = "", $curfilter = false)
    {
        $success = true;
        if ($success) {
            $success = $this->deleteSql($rs, $where, $curfilter)->execute();
        }
        return $success;
    }

    // Load DbValue from recordset or array
    protected function loadDbValues($row)
    {
        if (!is_array($row)) {
            return;
        }
        $this->idd_satker->DbValue = $row['idd_satker'];
        $this->kode_pemda->DbValue = $row['kode_pemda'];
        $this->kode_satker->DbValue = $row['kode_satker'];
        $this->nama_satker->DbValue = $row['nama_satker'];
        $this->wilayah->DbValue = $row['wilayah'];
        $this->idd_user->DbValue = $row['idd_user'];
        $this->no_telepon->DbValue = $row['no_telepon'];
    }

    // Delete uploaded files
    public function deleteUploadedFiles($row)
    {
        $this->loadDbValues($row);
    }

    // Record filter WHERE clause
    protected function sqlKeyFilter()
    {
        return "`idd_satker` = @idd_satker@";
    }

    // Get Key
    public function getKey($current = false)
    {
        $keys = [];
        $val = $current ? $this->idd_satker->CurrentValue : $this->idd_satker->OldValue;
        if (EmptyValue($val)) {
            return "";
        } else {
            $keys[] = $val;
        }
        return implode(Config("COMPOSITE_KEY_SEPARATOR"), $keys);
    }

    // Set Key
    public function setKey($key, $current = false)
    {
        $this->OldKey = strval($key);
        $keys = explode(Config("COMPOSITE_KEY_SEPARATOR"), $this->OldKey);
        if (count($keys) == 1) {
            if ($current) {
                $this->idd_satker->CurrentValue = $keys[0];
            } else {
                $this->idd_satker->OldValue = $keys[0];
            }
        }
    }

    // Get record filter
    public function getRecordFilter($row = null)
    {
        $keyFilter = $this->sqlKeyFilter();
        if (is_array($row)) {
            $val = array_key_exists('idd_satker', $row) ? $row['idd_satker'] : null;
        } else {
            $val = $this->idd_satker->OldValue !== null ? $this->idd_satker->OldValue : $this->idd_satker->CurrentValue;
        }
        if (!is_numeric($val)) {
            return "0=1"; // Invalid key
        }
        if ($val === null) {
            return "0=1"; // Invalid key
        } else {
            $keyFilter = str_replace("@idd_satker@", AdjustSql($val, $this->Dbid), $keyFilter); // Replace key value
        }
        return $keyFilter;
    }

    // Return page URL
    public function getReturnUrl()
    {
        $referUrl = ReferUrl();
        $referPageName = ReferPageName();
        $name = PROJECT_NAME . "_" . $this->TableVar . "_" . Config("TABLE_RETURN_URL");
        // Get referer URL automatically
        if ($referUrl != "" && $referPageName != CurrentPageName() && $referPageName != "login") { // Referer not same page or login page
            $_SESSION[$name] = $referUrl; // Save to Session
        }
        return $_SESSION[$name] ?? GetUrl("satkerslist");
    }

    // Set return page URL
    public function setReturnUrl($v)
    {
        $_SESSION[PROJECT_NAME . "_" . $this->TableVar . "_" . Config("TABLE_RETURN_URL")] = $v;
    }

    // Get modal caption
    public function getModalCaption($pageName)
    {
        global $Language;
        if ($pageName == "satkersview") {
            return $Language->phrase("View");
        } elseif ($pageName == "satkersedit") {
            return $Language->phrase("Edit");
        } elseif ($pageName == "satkersadd") {
            return $Language->phrase("Add");
        } else {
            return "";
        }
    }

    // API page name
    public function getApiPageName($action)
    {
        switch (strtolower($action)) {
            case Config("API_VIEW_ACTION"):
                return "SatkersView";
            case Config("API_ADD_ACTION"):
                return "SatkersAdd";
            case Config("API_EDIT_ACTION"):
                return "SatkersEdit";
            case Config("API_DELETE_ACTION"):
                return "SatkersDelete";
            case Config("API_LIST_ACTION"):
                return "SatkersList";
            default:
                return "";
        }
    }

    // List URL
    public function getListUrl()
    {
        return "satkerslist";
    }

    // View URL
    public function getViewUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("satkersview", $this->getUrlParm($parm));
        } else {
            $url = $this->keyUrl("satkersview", $this->getUrlParm(Config("TABLE_SHOW_DETAIL") . "="));
        }
        return $this->addMasterUrl($url);
    }

    // Add URL
    public function getAddUrl($parm = "")
    {
        if ($parm != "") {
            $url = "satkersadd?" . $this->getUrlParm($parm);
        } else {
            $url = "satkersadd";
        }
        return $this->addMasterUrl($url);
    }

    // Edit URL
    public function getEditUrl($parm = "")
    {
        $url = $this->keyUrl("satkersedit", $this->getUrlParm($parm));
        return $this->addMasterUrl($url);
    }

    // Inline edit URL
    public function getInlineEditUrl()
    {
        $url = $this->keyUrl(CurrentPageName(), $this->getUrlParm("action=edit"));
        return $this->addMasterUrl($url);
    }

    // Copy URL
    public function getCopyUrl($parm = "")
    {
        $url = $this->keyUrl("satkersadd", $this->getUrlParm($parm));
        return $this->addMasterUrl($url);
    }

    // Inline copy URL
    public function getInlineCopyUrl()
    {
        $url = $this->keyUrl(CurrentPageName(), $this->getUrlParm("action=copy"));
        return $this->addMasterUrl($url);
    }

    // Delete URL
    public function getDeleteUrl()
    {
        return $this->keyUrl("satkersdelete", $this->getUrlParm());
    }

    // Add master url
    public function addMasterUrl($url)
    {
        return $url;
    }

    public function keyToJson($htmlEncode = false)
    {
        $json = "";
        $json .= "idd_satker:" . JsonEncode($this->idd_satker->CurrentValue, "number");
        $json = "{" . $json . "}";
        if ($htmlEncode) {
            $json = HtmlEncode($json);
        }
        return $json;
    }

    // Add key value to URL
    public function keyUrl($url, $parm = "")
    {
        if ($this->idd_satker->CurrentValue !== null) {
            $url .= "/" . rawurlencode($this->idd_satker->CurrentValue);
        } else {
            return "javascript:ew.alert(ew.language.phrase('InvalidRecord'));";
        }
        if ($parm != "") {
            $url .= "?" . $parm;
        }
        return $url;
    }

    // Render sort
    public function renderSort($fld)
    {
        $classId = $fld->TableVar . "_" . $fld->Param;
        $scriptId = str_replace("%id%", $classId, "tpc_%id%");
        $scriptStart = $this->UseCustomTemplate ? "<template id=\"" . $scriptId . "\">" : "";
        $scriptEnd = $this->UseCustomTemplate ? "</template>" : "";
        $jsSort = " class=\"ew-pointer\" onclick=\"ew.sort(event, '" . $this->sortUrl($fld) . "', 1);\"";
        if ($this->sortUrl($fld) == "") {
            $html = <<<NOSORTHTML
{$scriptStart}<div class="ew-table-header-caption">{$fld->caption()}</div>{$scriptEnd}
NOSORTHTML;
        } else {
            if ($fld->getSort() == "ASC") {
                $sortIcon = '<i class="fas fa-sort-up"></i>';
            } elseif ($fld->getSort() == "DESC") {
                $sortIcon = '<i class="fas fa-sort-down"></i>';
            } else {
                $sortIcon = '';
            }
            $html = <<<SORTHTML
{$scriptStart}<div{$jsSort}><div class="ew-table-header-btn"><span class="ew-table-header-caption">{$fld->caption()}</span><span class="ew-table-header-sort">{$sortIcon}</span></div></div>{$scriptEnd}
SORTHTML;
        }
        return $html;
    }

    // Sort URL
    public function sortUrl($fld)
    {
        if (
            $this->CurrentAction || $this->isExport() ||
            in_array($fld->Type, [128, 204, 205])
        ) { // Unsortable data type
                return "";
        } elseif ($fld->Sortable) {
            $urlParm = $this->getUrlParm("order=" . urlencode($fld->Name) . "&amp;ordertype=" . $fld->getNextSort());
            return $this->addMasterUrl(CurrentPageName() . "?" . $urlParm);
        } else {
            return "";
        }
    }

    // Get record keys from Post/Get/Session
    public function getRecordKeys()
    {
        $arKeys = [];
        $arKey = [];
        if (Param("key_m") !== null) {
            $arKeys = Param("key_m");
            $cnt = count($arKeys);
        } else {
            if (($keyValue = Param("idd_satker") ?? Route("idd_satker")) !== null) {
                $arKeys[] = $keyValue;
            } elseif (IsApi() && (($keyValue = Key(0) ?? Route(2)) !== null)) {
                $arKeys[] = $keyValue;
            } else {
                $arKeys = null; // Do not setup
            }

            //return $arKeys; // Do not return yet, so the values will also be checked by the following code
        }
        // Check keys
        $ar = [];
        if (is_array($arKeys)) {
            foreach ($arKeys as $key) {
                if (!is_numeric($key)) {
                    continue;
                }
                $ar[] = $key;
            }
        }
        return $ar;
    }

    // Get filter from record keys
    public function getFilterFromRecordKeys($setCurrent = true)
    {
        $arKeys = $this->getRecordKeys();
        $keyFilter = "";
        foreach ($arKeys as $key) {
            if ($keyFilter != "") {
                $keyFilter .= " OR ";
            }
            if ($setCurrent) {
                $this->idd_satker->CurrentValue = $key;
            } else {
                $this->idd_satker->OldValue = $key;
            }
            $keyFilter .= "(" . $this->getRecordFilter() . ")";
        }
        return $keyFilter;
    }

    // Load recordset based on filter
    public function &loadRs($filter)
    {
        $sql = $this->getSql($filter); // Set up filter (WHERE Clause)
        $conn = $this->getConnection();
        $stmt = $conn->executeQuery($sql);
        return $stmt;
    }

    // Load row values from record
    public function loadListRowValues(&$rs)
    {
        if (is_array($rs)) {
            $row = $rs;
        } elseif ($rs && property_exists($rs, "fields")) { // Recordset
            $row = $rs->fields;
        } else {
            return;
        }
        $this->idd_satker->setDbValue($row['idd_satker']);
        $this->kode_pemda->setDbValue($row['kode_pemda']);
        $this->kode_satker->setDbValue($row['kode_satker']);
        $this->nama_satker->setDbValue($row['nama_satker']);
        $this->wilayah->setDbValue($row['wilayah']);
        $this->idd_user->setDbValue($row['idd_user']);
        $this->no_telepon->setDbValue($row['no_telepon']);
    }

    // Render list row values
    public function renderListRow()
    {
        global $Security, $CurrentLanguage, $Language;

        // Call Row Rendering event
        $this->rowRendering();

        // Common render codes

        // idd_satker

        // kode_pemda

        // kode_satker

        // nama_satker

        // wilayah

        // idd_user

        // no_telepon

        // idd_satker
        $this->idd_satker->ViewValue = $this->idd_satker->CurrentValue;
        $this->idd_satker->ViewValue = FormatNumber($this->idd_satker->ViewValue, 0, -2, -2, -2);
        $this->idd_satker->ViewCustomAttributes = "";

        // kode_pemda
        $this->kode_pemda->ViewValue = $this->kode_pemda->CurrentValue;
        $this->kode_pemda->ViewCustomAttributes = "";

        // kode_satker
        $this->kode_satker->ViewValue = $this->kode_satker->CurrentValue;
        $this->kode_satker->ViewValue = FormatNumber($this->kode_satker->ViewValue, 0, -2, -2, -2);
        $this->kode_satker->ViewCustomAttributes = "";

        // nama_satker
        $this->nama_satker->ViewValue = $this->nama_satker->CurrentValue;
        $this->nama_satker->ViewCustomAttributes = "";

        // wilayah
        if ($this->wilayah->VirtualValue != "") {
            $this->wilayah->ViewValue = $this->wilayah->VirtualValue;
        } else {
            $curVal = trim(strval($this->wilayah->CurrentValue));
            if ($curVal != "") {
                $this->wilayah->ViewValue = $this->wilayah->lookupCacheOption($curVal);
                if ($this->wilayah->ViewValue === null) { // Lookup from database
                    $filterWrk = "`idd_wilayah`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                    $sqlWrk = $this->wilayah->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->wilayah->Lookup->renderViewRow($rswrk[0]);
                        $this->wilayah->ViewValue = $this->wilayah->displayValue($arwrk);
                    } else {
                        $this->wilayah->ViewValue = $this->wilayah->CurrentValue;
                    }
                }
            } else {
                $this->wilayah->ViewValue = null;
            }
        }
        $this->wilayah->ViewCustomAttributes = "";

        // idd_user
        $curVal = trim(strval($this->idd_user->CurrentValue));
        if ($curVal != "") {
            $this->idd_user->ViewValue = $this->idd_user->lookupCacheOption($curVal);
            if ($this->idd_user->ViewValue === null) { // Lookup from database
                $filterWrk = "`idd_user`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                $sqlWrk = $this->idd_user->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->idd_user->Lookup->renderViewRow($rswrk[0]);
                    $this->idd_user->ViewValue = $this->idd_user->displayValue($arwrk);
                } else {
                    $this->idd_user->ViewValue = $this->idd_user->CurrentValue;
                }
            }
        } else {
            $this->idd_user->ViewValue = null;
        }
        $this->idd_user->ViewCustomAttributes = "";

        // no_telepon
        $this->no_telepon->ViewValue = $this->no_telepon->CurrentValue;
        $this->no_telepon->ViewCustomAttributes = "";

        // idd_satker
        $this->idd_satker->LinkCustomAttributes = "";
        $this->idd_satker->HrefValue = "";
        $this->idd_satker->TooltipValue = "";

        // kode_pemda
        $this->kode_pemda->LinkCustomAttributes = "";
        $this->kode_pemda->HrefValue = "";
        $this->kode_pemda->TooltipValue = "";

        // kode_satker
        $this->kode_satker->LinkCustomAttributes = "";
        $this->kode_satker->HrefValue = "";
        $this->kode_satker->TooltipValue = "";

        // nama_satker
        $this->nama_satker->LinkCustomAttributes = "";
        $this->nama_satker->HrefValue = "";
        $this->nama_satker->TooltipValue = "";

        // wilayah
        $this->wilayah->LinkCustomAttributes = "";
        $this->wilayah->HrefValue = "";
        $this->wilayah->TooltipValue = "";

        // idd_user
        $this->idd_user->LinkCustomAttributes = "";
        $this->idd_user->HrefValue = "";
        $this->idd_user->TooltipValue = "";

        // no_telepon
        $this->no_telepon->LinkCustomAttributes = "";
        if (!EmptyValue($this->no_telepon->CurrentValue)) {
            $this->no_telepon->HrefValue = "https://wa.me/" . (!empty($this->no_telepon->ViewValue) && !is_array($this->no_telepon->ViewValue) ? RemoveHtml($this->no_telepon->ViewValue) : $this->no_telepon->CurrentValue) . "?text=Assalamu'alaikum"; // Add prefix/suffix
            $this->no_telepon->LinkAttrs["target"] = "_blank"; // Add target
            if ($this->isExport()) {
                $this->no_telepon->HrefValue = FullUrl($this->no_telepon->HrefValue, "href");
            }
        } else {
            $this->no_telepon->HrefValue = "";
        }
        $this->no_telepon->TooltipValue = "";

        // Call Row Rendered event
        $this->rowRendered();

        // Save data for Custom Template
        $this->Rows[] = $this->customTemplateFieldValues();
    }

    // Render edit row values
    public function renderEditRow()
    {
        global $Security, $CurrentLanguage, $Language;

        // Call Row Rendering event
        $this->rowRendering();

        // idd_satker
        $this->idd_satker->EditAttrs["class"] = "form-control";
        $this->idd_satker->EditCustomAttributes = "";
        $this->idd_satker->EditValue = $this->idd_satker->CurrentValue;
        $this->idd_satker->EditValue = FormatNumber($this->idd_satker->EditValue, 0, -2, -2, -2);
        $this->idd_satker->ViewCustomAttributes = "";

        // kode_pemda
        $this->kode_pemda->EditAttrs["class"] = "form-control";
        $this->kode_pemda->EditCustomAttributes = "";
        if (!$this->kode_pemda->Raw) {
            $this->kode_pemda->CurrentValue = HtmlDecode($this->kode_pemda->CurrentValue);
        }
        $this->kode_pemda->EditValue = $this->kode_pemda->CurrentValue;
        $this->kode_pemda->PlaceHolder = RemoveHtml($this->kode_pemda->caption());

        // kode_satker
        $this->kode_satker->EditAttrs["class"] = "form-control";
        $this->kode_satker->EditCustomAttributes = "";
        $this->kode_satker->EditValue = $this->kode_satker->CurrentValue;
        $this->kode_satker->PlaceHolder = RemoveHtml($this->kode_satker->caption());

        // nama_satker
        $this->nama_satker->EditAttrs["class"] = "form-control";
        $this->nama_satker->EditCustomAttributes = "";
        if (!$this->nama_satker->Raw) {
            $this->nama_satker->CurrentValue = HtmlDecode($this->nama_satker->CurrentValue);
        }
        $this->nama_satker->EditValue = $this->nama_satker->CurrentValue;
        $this->nama_satker->PlaceHolder = RemoveHtml($this->nama_satker->caption());

        // wilayah
        $this->wilayah->EditAttrs["class"] = "form-control";
        $this->wilayah->EditCustomAttributes = "";
        $this->wilayah->PlaceHolder = RemoveHtml($this->wilayah->caption());

        // idd_user
        $this->idd_user->EditAttrs["class"] = "form-control";
        $this->idd_user->EditCustomAttributes = "";
        $this->idd_user->PlaceHolder = RemoveHtml($this->idd_user->caption());

        // no_telepon
        $this->no_telepon->EditAttrs["class"] = "form-control";
        $this->no_telepon->EditCustomAttributes = "";
        if (!$this->no_telepon->Raw) {
            $this->no_telepon->CurrentValue = HtmlDecode($this->no_telepon->CurrentValue);
        }
        $this->no_telepon->EditValue = $this->no_telepon->CurrentValue;
        $this->no_telepon->PlaceHolder = RemoveHtml($this->no_telepon->caption());

        // Call Row Rendered event
        $this->rowRendered();
    }

    // Aggregate list row values
    public function aggregateListRowValues()
    {
    }

    // Aggregate list row (for rendering)
    public function aggregateListRow()
    {
        // Call Row Rendered event
        $this->rowRendered();
    }

    // Export data in HTML/CSV/Word/Excel/Email/PDF format
    public function exportDocument($doc, $recordset, $startRec = 1, $stopRec = 1, $exportPageType = "")
    {
        if (!$recordset || !$doc) {
            return;
        }
        if (!$doc->ExportCustom) {
            // Write header
            $doc->exportTableHeader();
            if ($doc->Horizontal) { // Horizontal format, write header
                $doc->beginExportRow();
                if ($exportPageType == "view") {
                    $doc->exportCaption($this->kode_pemda);
                    $doc->exportCaption($this->kode_satker);
                    $doc->exportCaption($this->nama_satker);
                    $doc->exportCaption($this->wilayah);
                    $doc->exportCaption($this->idd_user);
                    $doc->exportCaption($this->no_telepon);
                } else {
                    $doc->exportCaption($this->idd_satker);
                    $doc->exportCaption($this->kode_pemda);
                    $doc->exportCaption($this->kode_satker);
                    $doc->exportCaption($this->nama_satker);
                    $doc->exportCaption($this->wilayah);
                    $doc->exportCaption($this->idd_user);
                    $doc->exportCaption($this->no_telepon);
                }
                $doc->endExportRow();
            }
        }

        // Move to first record
        $recCnt = $startRec - 1;
        $stopRec = ($stopRec > 0) ? $stopRec : PHP_INT_MAX;
        while (!$recordset->EOF && $recCnt < $stopRec) {
            $row = $recordset->fields;
            $recCnt++;
            if ($recCnt >= $startRec) {
                $rowCnt = $recCnt - $startRec + 1;

                // Page break
                if ($this->ExportPageBreakCount > 0) {
                    if ($rowCnt > 1 && ($rowCnt - 1) % $this->ExportPageBreakCount == 0) {
                        $doc->exportPageBreak();
                    }
                }
                $this->loadListRowValues($row);

                // Render row
                $this->RowType = ROWTYPE_VIEW; // Render view
                $this->resetAttributes();
                $this->renderListRow();
                if (!$doc->ExportCustom) {
                    $doc->beginExportRow($rowCnt); // Allow CSS styles if enabled
                    if ($exportPageType == "view") {
                        $doc->exportField($this->kode_pemda);
                        $doc->exportField($this->kode_satker);
                        $doc->exportField($this->nama_satker);
                        $doc->exportField($this->wilayah);
                        $doc->exportField($this->idd_user);
                        $doc->exportField($this->no_telepon);
                    } else {
                        $doc->exportField($this->idd_satker);
                        $doc->exportField($this->kode_pemda);
                        $doc->exportField($this->kode_satker);
                        $doc->exportField($this->nama_satker);
                        $doc->exportField($this->wilayah);
                        $doc->exportField($this->idd_user);
                        $doc->exportField($this->no_telepon);
                    }
                    $doc->endExportRow($rowCnt);
                }
            }

            // Call Row Export server event
            if ($doc->ExportCustom) {
                $this->rowExport($row);
            }
            $recordset->moveNext();
        }
        if (!$doc->ExportCustom) {
            $doc->exportTableFooter();
        }
    }

    // Get file data
    public function getFileData($fldparm, $key, $resize, $width = 0, $height = 0, $plugins = [])
    {
        // No binary fields
        return false;
    }

    // Table level events

    // Recordset Selecting event
    public function recordsetSelecting(&$filter)
    {
        // Enter your code here
    }

    // Recordset Selected event
    public function recordsetSelected(&$rs)
    {
        //Log("Recordset Selected");
    }

    // Recordset Search Validated event
    public function recordsetSearchValidated()
    {
        // Example:
        //$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value
    }

    // Recordset Searching event
    public function recordsetSearching(&$filter)
    {
        // Enter your code here
    }

    // Row_Selecting event
    public function rowSelecting(&$filter)
    {
        // Enter your code here
    }

    // Row Selected event
    public function rowSelected(&$rs)
    {
        //Log("Row Selected");
    }

    // Row Inserting event
    public function rowInserting($rsold, &$rsnew)
    {
        // Enter your code here
        // To cancel, set return value to false
        return true;
    }

    // Row Inserted event
    public function rowInserted($rsold, &$rsnew)
    {
        //Log("Row Inserted");
    }

    // Row Updating event
    public function rowUpdating($rsold, &$rsnew)
    {
        // Enter your code here
        // To cancel, set return value to false
        return true;
    }

    // Row Updated event
    public function rowUpdated($rsold, &$rsnew)
    {
        //Log("Row Updated");
    }

    // Row Update Conflict event
    public function rowUpdateConflict($rsold, &$rsnew)
    {
        // Enter your code here
        // To ignore conflict, set return value to false
        return true;
    }

    // Grid Inserting event
    public function gridInserting()
    {
        // Enter your code here
        // To reject grid insert, set return value to false
        return true;
    }

    // Grid Inserted event
    public function gridInserted($rsnew)
    {
        //Log("Grid Inserted");
    }

    // Grid Updating event
    public function gridUpdating($rsold)
    {
        // Enter your code here
        // To reject grid update, set return value to false
        return true;
    }

    // Grid Updated event
    public function gridUpdated($rsold, $rsnew)
    {
        //Log("Grid Updated");
    }

    // Row Deleting event
    public function rowDeleting(&$rs)
    {
        // Enter your code here
        // To cancel, set return value to False
        return true;
    }

    // Row Deleted event
    public function rowDeleted(&$rs)
    {
        //Log("Row Deleted");
    }

    // Email Sending event
    public function emailSending($email, &$args)
    {
        //var_dump($email); var_dump($args); exit();
        return true;
    }

    // Lookup Selecting event
    public function lookupSelecting($fld, &$filter)
    {
        //var_dump($fld->Name, $fld->Lookup, $filter); // Uncomment to view the filter
        // Enter your code here
    }

    // Row Rendering event
    public function rowRendering()
    {
        // Enter your code here
    }

    // Row Rendered event
    public function rowRendered()
    {
        // To view properties of field class, use:
        //var_dump($this-><FieldName>);
    }

    // User ID Filtering event
    public function userIdFiltering(&$filter)
    {
        // Enter your code here
    }
}
