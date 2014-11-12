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
 * $Id: MySqlConnection.php,v 1.4 2009/03/12 03:44:44 pctainto Exp $
 *
 * Represents a connection to a MySQL database.
 */

require_once("SqlConnection.php");
require_once("MySqlResult.php");

class MySqlConnection extends SqlConnection {

    function MySqlConnection($options = null) {
        $this->SqlConnection($options);
    }

    function open() {
        $portSock = $this->getOption("port")
            ? (":" .  $this->getOption("port"))
            : "";
        $portSock = (!$portSock && $this->getOption("sock"))
            ? (":" .  $this->getOption("sock"))
            : "";
        if ($this->getOption("persistent")) {
            $this->dbh = mysql_pconnect($this->getOption("host") . $portSock,
                $this->getOption("user"),
                $this->getOption("pass"));
        }
        else {
            $this->dbh = mysql_connect($this->getOption("host") . $portSock,
                $this->getOption("user"),
                $this->getOption("pass"));
        }
        if (!$this->dbh) {
            trigger_error("Could not connect to database: " . mysql_error(),
                $this->getOption("errlevel"));
            return false;
        }
        if ($this->getOption("db")
                && !mysql_select_db($this->getOption("db"), $this->dbh)) {
            trigger_error("Could not select database: " . mysql_error(),
                $this->getOption("errlevel"));
            return false;
        }
        return true;
    }

    function query($query, $params = null) {
        # Array of errors that happen while executing the query
        $errors = array();

        if (!$this->dbh) {
            trigger_error("Connection is not open", $this->getOption("errlevel"));
            return;
        }

        # If the user sent parameter replacement values, the query should be
        # prepared before sending to the database.  The correct way to do this
        # is to create a command and execute it with the parameters.  That
        # object will prepare the query text and actually call this function
        # again with the result, which this function will pass right through to
        # the database.
        if ($params) {
            $cmd = $this->createCommand();
            $cmd->setCommandText($query);
            return $cmd->executeReader($params);
        }

        $result = null;
        $firstResult = null;

        # Split the query into multiple queries if required
        $queries = explode("\n-- DIVIDER\n", $query);
        foreach ($queries as $query) {
            $select = false; # Whether this query should return a rowset
            # Remove comment lines from the query, if there are multiple queries
            if (count($queries) > 1) {
                $query = trim(preg_replace('/^ *-- .*\n/m', "", $query));
            }
            if (strtolower(substr($query, 0, 6)) == "select") {
                $select = true;
            }

            # Check for parameters that haven't yet been replaced in the query text
            $raw = $this->getRawParams($query);
            if (count($raw)) {
                $rawErrors = "You have not replaced all parameters in your "
                    . "query.  The following parameters remain: {"
                    . implode(", ", $raw) . "}";
                $errors[] = $rawErrors;
                trigger_error($rawErrors, $this->getOption("errlevel"));
            }

            $queryNum = $this->addQuery($query);
            $sth = mysql_query($this->aliasTables($query), $this->dbh);
            # Possibly trigger an error
            if (!$sth) {
                $smtErr = mysql_error($this->dbh);
                $errors[] = $smtErr;
                if ($this->getOption("errlevel")) {
                    trigger_error("SQL Error $smtErr in '$query'",
                        $this->getOption("errlevel"));
                }
            }
            $ident = mysql_insert_id($this->dbh);
            $rows = mysql_affected_rows($this->dbh);
            $info = mysql_info($this->dbh);
            $this->setQueryStatus($queryNum, mysql_errno($this->dbh), $errors);
            $thisResult = new MySqlResult($sth, $query, $rows, $info, $ident);
            if ($select || count($queries) == 1) {
                # Chain the result into the chain
                if ($result == null) {
                    $result = $thisResult;
                    $firstResult = $thisResult;
                }
                else {
                    $result->next = $thisResult;
                    $result = $thisResult;
                }
            }
        }
        return $firstResult;
    }

    function close() {
        if ($this->dbh && !$this->getOption("persistent")) {
            mysql_close($this->dbh);
            $this->dbh = null;
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

}

?>
