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
 * $Id: SqlCommand.php,v 1.2 2005/08/02 23:46:18 bps7j Exp $
 *
 * Represents a command to execute against a SQL database, using a
 * SqlConnection.  There should be no need to subclass SqlCommand, as it's
 * abstract enough that the various SqlConnection subclasses should do the
 * work of translating to whatever format the particular database wants.
 *
 * SQL parameters may be embedded in the SQL text, in the format
 * {name,type,size,scale,nullable}.  Only the name and type are required;
 * nullable is used to specify whether a parameter with no specified value
 * should be replaced with null, and defaults to true.  An array of
 * name => value parameters may be used to replace these parameters in the
 * query with actual values, which will be sanitized according to type.
 */

require_once("SqlConnection.php");

class SqlCommand {

    var $conn = null;
    var $cmdText = "";
    var $cmdType = "Text"; # Possible values: "Text", "StoredProcedure"
    var $params = null;
    var $preparedQuery = "";

    function SqlCommand(&$conn, $cmd = null) {
        $this->cmdText = $cmd;
        $this->conn =& $conn;
        $this->params = array();
    }

    function setConnection(&$value) {
        $this->conn =& $value;
        $this->preparedQuery = "";
    }

    function setCommandText($value) {
        $this->cmdText = $value;
    }

    function addParameter($name, $val) {
        $this->preparedQuery = "";
        $this->params[$name] = $val;
    }

    function addParameters($params) {
        $this->preparedQuery = "";
        foreach ($params as $key => $val) {
            $this->params[$key] = $val;
        }
    }

    function getPreparedQuery() {
        if (!$this->preparedQuery) {
            $this->prepare();
        }
        return $this->preparedQuery;
    }

    function loadQuery($file) {
        $this->cmdText = file_get_contents($file);
    }

    function executeNonQuery($params = array()) {
        if ($params) {
            $this->addParameters($params);
        }
        $this->prepare();
        $res = $this->conn->query($this->preparedQuery);
        return $res->rowsAffected();
    }

    function executeReader($params = null) {
        if ($params) {
            $this->addParameters($params);
        }
        $this->prepare();
        $res = $this->conn->query($this->preparedQuery);
        if ($this->conn->getOption('dump')) {
            echo $res->dumpResults();
        }
        return $res;
    }

    function executeScalar($params = array()) {
        if ($params) {
            $this->addParameters($params);
        }
        $this->prepare();
        $res = $this->conn->query($this->preparedQuery);
        return $res->fetchScalar();
    }

    function autoExecute($table, $values, $mode = DB_AUTOQUERY_INSERT, $where = "") {
        $query = "insert into $table set ";
        if ($mode == DB_AUTOQUERY_UPDATE) {
            $query = "update $table set ";
        }
        foreach ($values as $key => $val) {
            $query .= "$key = '" . addslashes($val) . "', ";
        }
        $query = substr($query, 0, -2);
        if ($where) {
            $query .= " where $where";
        }
        return $this->conn->query($query);
    }

    function prepare() {
        if ($this->preparedQuery && $this->cmdText) {
            return;
        }
        # Check that each parameter exists in the command, as a safeguard
        # against misspelled or wrong parameter replacement.
        foreach ($this->params as $name => $value) {
            if (strpos($this->cmdText, "{" . $name) === false) {
                trigger_error("Parameter '$name' does not exist in query text",
                     $this->conn->getOption("errlevel"));
            }
        }
        $this->preparedQuery = preg_replace(
            "/\{([a-zA-Z0-9_]+),([a-zA-Z]+)"// name, datatype (others optional)
                . "(?:,([0-9]*))?"          // size
                . "(?:,([0-9]*))?"          // scale
                . "(?:,(\w*))?"             // nullable; default yes
                . "(?:,([^}]*))?\}/e",      // default value if not null but no value specified
            "\$this->prepParam('\$0', '\$1', '\$2', '\$3', "
                . "'\$4', '\$5', '\$6');", $this->cmdText);
    }

    function prepParam($fulltext, $name, $type,
                        $size, $scale, $nullable, $nullval) {
        $nullable = ($nullable === '' || (bool) $nullable);
        # If $nullval is '', then the parameter is nullable and $nullval
        # actually becomes the string 'null'.  If it's anything else, the
        # parameter needs to get replaced with whatever that is.
        $size = intval($size);
        $scale = intval($scale);
        if (isset($this->params[$name]) && !is_null($this->params[$name])) {
            switch (strtolower($type)) {
            case "char":
            case "varchar":
                return "'"
                    . addslashes($size
                        ? substr($this->params[$name], 0, $size)
                        : $this->params[$name])
                    . "'";
            case "int":
            case "bigint":
            case "integer":
                return intval($this->params[$name]);
            case "numeric":
            case "decimal":
            case "float":
                return $scale
                    ? number_format(floatval($this->params[$name]), $scale, ".", "")
                    : floatval($this->params[$name]);
            case "date":
            case "datetime":
                return "'" . addslashes($this->params[$name]) . "'";
            case "none":
                return $this->params[$name];
            default:
                trigger_error("Unrecognized parameter type '$type' in $fulltext", E_USER_ERROR);
            }
        }
        if ($nullable) {
            return "null";
        }
        elseif ($nullval !== '') {
            return $nullval;
        }
        else {
            return $fulltext;
        }
    }

}
?>
