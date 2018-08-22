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
 * $Id: SqlConnection.php,v 1.3 2009/03/12 03:44:44 pctainto Exp $
 *
 * Represents a connection to a SQL database.
 */

define("DB_FETCHMODE_ASSOC", 0);
define("DB_FETCHMODE_ORDERED", 1);
define("DB_AUTOQUERY_INSERT", 0);
define("DB_AUTOQUERY_UPDATE", 1);

require_once("SqlCommand.php");

class SqlConnection {

    var $dbh = null;
    var $options = null;
    var $queries = null;

    function SqlConnection($options = null) {
        $this->queries = array();

        # Explicitly set some of the options; they might get overwritten but
        # need defaults
        $this->options = array(
            "prefix" => "",
            "prefix-placeholder" => "[_]",
            "db" => "",
            "errlevel" => E_USER_NOTICE,
            "debug" => false);

        if ($options && is_array($options)) {
            foreach ($options as $key => $val) {
                $this->options[$key] = $val;
            }
        }
    }

    function open() {
        # Must be overridden in derived class.
    }

    function query($query, $params = null) {
        # Must be overridden in derived class.
    }

    function close() {
        # Must be overridden in derived class.
    }

    function addQuery($text) {
        if ($this->getOption("debug")) {
            $this->queries[] = array('text' => $text);
        }
        return count($this->queries) - 1;
    }

    function setQueryStatus($num, $status, $messages, $info = null) {
        if ($this->getOption("debug")) {
            $this->queries[$num]['status'] = $status;
            $this->queries[$num]['messages'] = $messages;
            $this->queries[$num]['info'] = $info;
        }
    }

    function createCommand() {
        return new SqlCommand($this);
    }

    function getOption($name) {
        return isset($this->options[$name]) ? $this->options[$name] : null;
    }

    function setOption($name, $val) {
        $this->options[$name] = $val;
    }

    function getRawParams($query) {
        $matches = array(null, null);
        preg_match_all("/\{([a-zA-Z0-9_]+)[^}]*}/",
            $query, $matches);
        return $matches[1];
    }

    function getHandle() {
        return $this->dbh;
    }

    function aliasTables($query) {
        return str_replace($this->options["prefix-placeholder"],
            $this->options["prefix"], $query);
    }

}

?>
