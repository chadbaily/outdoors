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
 * $Id: SqlResult.php,v 1.2 2009/03/12 03:13:36 pctainto Exp $
 *
 * Represents a result-set from a SQL query.
 */

class SqlResult {

    var $query = null;
    var $sth = null;
    var $info = null;
    var $identity = null;
    var $rows = null;
    var $next = null;

    function SqlResult($sth, $query, $rowsAffected, $info = null, $identity = null) {
        $this->sth = $sth;
        $this->query = $query;
        $this->info = $info;
        $this->identity = $identity;
        $this->rows = $rowsAffected;
    }

    function numRows() {
        # Must be implemented in subclasses
    }

    function numCols() {
        # Must be implemented in subclasses
    }

    function rowsAffected() {
        return $this->rows;
    }

    function identity() {
        return $this->identity;
    }

    function fetchRow($fetchMode) {
        # Must be implemented in subclasses
    }

    function nextResult() {
        return $this->next;
    }

    function fetchScalar() {
        # Must be implemented in subclasses
    }

    function seekRow($row) {
        # Must be implemented in subclasses
    }

    function getHandle() {
        return $this->sth;
    }

    function dumpResults() {
        if ($this->numRows()) {
            $result = "<table border=1>";
            $header = true;
            while ($row = $this->fetchRow()) {
                if ($header) {# This only happens once... print out a header row
                    $header = false;
                    $result .= "<tr><th>" 
                        . implode("</th><th>", array_keys($row))
                        . "</th></tr>";
                }
                $result .= "<tr><td>" 
                    . implode("</td><td>", $row) 
                    . "</td></tr>";
            }
            $this->seekRow(0);
            return $result;
        }
        return "";
    }

}
?>
