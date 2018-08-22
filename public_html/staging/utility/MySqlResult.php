<?php
/*
 * This file is part of PhpSqlConnect, a simple database abstraction layer.
 * Copyright (C) 2004 Baron Schwartz <baron at sequent dot org>
 * 
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 * 
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more
 * details.
 * 
 * You should have received a copy of the GNU General Public License along with
 * this program; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307  USA
 * 
 * $Id: MySqlResult.php,v 1.2 2005/08/02 23:46:18 bps7j Exp $
 *
 * Represents a result-set from a MySQL query.
 */

require_once("SqlResult.php");

class MySqlResult extends SqlResult {

    function MySqlResult($sth, $query, $rowsAffected, $info = null, $identity = null) {
        $this->SqlResult($sth, $query, $rowsAffected, $info, $identity);
    }

    function numRows() {
        if ($this->sth) {
            return mysql_num_rows($this->sth);
        }
        return 0;
    }

    function numCols() {
        if ($this->sth) {
            return mysql_num_fields($this->sth);
        }
        return 0;
    }

    function fetchRow($fetchMode = null) {
        $mode = is_null($fetchMode) ? DB_FETCHMODE_ASSOC : $fetchMode;
        if ($this->sth) {
            switch ($mode) {
            case DB_FETCHMODE_ORDERED:
                return mysql_fetch_row($this->sth);
            default:
                return mysql_fetch_assoc($this->sth);
            }
        }
        return array();
    }

    function fetchScalar() {
        if ($this->numRows() && $this->numCols()) {
            $row = $this->fetchRow(DB_FETCHMODE_ORDERED);
            return $row[0];
        }
        return FALSE;
    }

    function seekRow($row) {
        if ($this->sth) {
            $row = intval($row);
            if ($row < 0 || $row >= $this->numRows()) {
                trigger_error("Cannot move to that row", E_USER_WARNING);
            }
            else {
                return mysql_data_seek($this->sth, $row);
            }
        }
        return false;
    }

}
?>
