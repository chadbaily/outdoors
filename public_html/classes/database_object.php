<?php
/*
 * This file is part of SocialClub (http://socialclub.sourceforge.net)
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
 * $Id: database_object.php,v 1.6 2005/09/12 01:43:23 bps7j Exp $
 */

include_once("DateTime.php");
include_once("table.php");

class database_object {
    // {{{declarations
    var $c_uid = null;              // The object's primary key in the database.
    var $c_owner = null;            // Member
    var $c_creator = null;          // Member
    var $c_group = null;            // Group
    var $c_unixperms = null;        // String; Unix permissions
    var $c_created_date = null;     // String
    var $c_status = null;           // Status
    var $c_deleted = null;          // Tinyint

    var $GUID;
    var $allowedActions;            // Actions the current user is allowed to do
    var $table;                     // The table this object is stored in
    var $children;                  // Child objects (as defined in the db)
    // }}}

    /* {{{constructor
     *
     */
    function database_object() {
        global $cfg;
        $this->GUID = getRandomString(20);
        $this->table = $cfg['table_prefix'] . get_class($this);
        $this->children = array();
    } //}}}

    /* {{{copy
     * Returns a new object with the same properties, except for the common
     * properties that exist in every object such as owner and creation date;
     * these are set to null.
     */
    function copy() {
        $newObject = $this;
        $newObject->c_uid = null;
        $newObject->c_owner = null;
        $newObject->c_creator = null;
        $newObject->c_created_date = null;
        return $newObject;
    } //}}}

    /* {{{getDatabaseVars
     * This method returns an array of all the variables that correspond
     * directly to some column in the database.
     */
    function getDatabaseVars() {
        $someVariables = array();
        foreach (array_keys(get_class_vars(get_class($this))) as $key) {
            if (substr($key, 0, 2) == "c_") {
                $someVariables[$key] = $key;
            }
        }
        return $someVariables;
    } //}}}

    /* {{{setUpDatabaseDefaults
     * This function, which should be overriden as appropriate, sets up various
     * defaults so the code doesn't always have to call them.  This is in
     * preparation for inserting, updating, and so on.  It is automatically
     * called as appropriate (in other words, you shouldn't need to call it from
     * outside database_object).  Not all things are set here; many defaults are
     * set at a per-table level in the database.
     */
    function setUpDatabaseDefaults() {
        global $cfg;
        if (is_null($this->c_created_date)) {
            $this->c_created_date = date("Y-m-d H:i:s");
        }
        if (is_null($this->c_owner) && isset($cfg['user'])) {
            $this->c_owner = $cfg['user'];
        }
        if (is_null($this->c_creator) && isset($cfg['user'])) {
            $this->c_creator = $cfg['user'];
        }
    } //}}}

    /* {{{insert
     */
    function insert() {
        return $this->doInsert();
    } //}}}

    /* {{{doInsert
     * This is a helper method.  It inserts the object into the database, and
     * then returns the object's new c_uid value.  As a side effect, it also
     * sets the object's $c_uid.  This should be called from a subclass's
     * insert() method, after the subclass has done any necessary setup work to
     * prepare the data for insertion!
     */
    function doInsert() {
        global $obj;

        $this->setUpDatabaseDefaults();

        $vars = $this->getDatabaseVars();
        foreach ($vars as $key => $val) {
            if (is_null($this->$key)) {
                unset($vars[$key]);
            }
        }

        // Make an array suitable for passing to autoExecute(), in the format
        // 'c_foobar' => $this->c_foobar.  This depends on the database columns
        // being named the same as the class variables.
        $array = array();
        foreach ($vars as $key => $value) {
            $array[$key] = $this->$key;
        }

        $cmd = $obj['conn']->createCommand();
        $res = $cmd->autoExecute($this->table, $array, DB_AUTOQUERY_INSERT);
        $this->c_uid = intval($res->identity());
        return $this->c_uid;
    } //}}}

    /* {{{update
     */
    function update() {
        $this->doUpdate();
    } //}}}

    /* {{{doUpdate
     * Does the heavy lifting for the update operation.  This method assumes
     * that all setup has been done, then automagically generates and executes a
     * query to store the object into the database.
     */
    function doUpdate() {
        global $obj;
        $this->ensureUID(__LINE__, __FILE__);
        $vars = $this->getDatabaseVars();
        # Some special cases: we don't want to try to explicitly set c_uid
        # or anything that's NULL.
        unset($vars['c_uid']);
        foreach ($vars as $key => $val) {
            if (is_null($this->$key)) {
                unset($vars[$key]);
            }
        }

        // Make an array suitable for passing to autoExecute()
        $array = array();
        foreach ($vars as $key => $value) {
            $array[$key] = $this->$key;
        }

        // Now use this to update the object's tuple in the database.
        $cmd = $obj['conn']->createCommand();
        $cmd->autoExecute($this->table, $array, DB_AUTOQUERY_UPDATE,
            "c_uid = " . intval($this->c_uid));
    } //}}}

    /* {{{select
     * Populates $this from the database.  Requires the primary key from the
     * database, obviously.  If $strict is false, it won't complain very hard
     * about there not being a row in the database with that primary key value.
     */
    function select($uid, $strict = true) {
        global $obj;
        if ($strict && !$uid) {
            trigger_error("No UID passed into select() in class "
                    .  $this->table, E_USER_ERROR);
            return false;
        }
        $result = $obj['conn']->query("select * from $this->table "
            . "where c_deleted <> 1 and c_uid = {uid,int}",
            array('uid' => $uid));
        if ($strict && $result->numRows() == 0) {
            trigger_error("There is no row in $this->table with c_uid $uid",
                    E_USER_ERROR);
            return false;
        }
        if ($result->numRows() > 0) {
            $row = $result->fetchRow();
            $this->initFromRow($row);
        }
        return true;
    } //}}}

    /* {{{delete
     * Deletes this item from the database.  Usually the item is just marked
     * with a status as deleted, but if the optional parameter $reallyDelete is
     * TRUE the record will really be deleted.
     */
    function delete($reallyDelete = FALSE, $cascade = FALSE) {
        global $obj;

        # Save a reference to this object to avoid infinite recursion
        if (isset($obj['visited-objects'][$this->GUID])) {
            return;
        }
        else {
            $obj['visited-objects'][$this->GUID] = 1;
        }

        $this->ensureUID(__LINE__, __FILE__);

        if ($cascade) {
            # Iterate through the methods that are defined to return the objects
            # that need to be deleted
            foreach ($this->getForeignKeys() as $key) {
                foreach ($this->getChildren($key['c_child_table'], $key['c_child_col'], true) as $object) {
                    $object->delete($reallyDelete, true);
                }
            }
        }


        # Delete this object itself
        if ($reallyDelete) {
            $obj['conn']->query("delete from $this->table where c_uid = {uid,int}",
                array('uid' => $this->c_uid));
        }
        else {
            $obj['conn']->query("update $this->table set c_deleted = 1 where c_uid = {uid,int}",
                array('uid' => $this->c_uid));
            $this->c_deleted = 1;
        }

    } //}}}

    /* {{{initFromRow
     * Initializes the object from a database resultset.  Normally this will be
     * called from select(), but sometimes you may have a bunch of rows from the
     * database in a resultset and want to make some objects from them.  In that
     * case, this is a fine way to do things.
     *
     * If the value is just numbers and has no "date" in the name, casts it to
     * an integer.
     */
    function initFromRow($row) {
        foreach ($this->getDatabaseVars() as $key) {
            $this->$key = $row[$key];
        }
        $this->GUID = "{$this->table}($row[c_uid])";
    } //}}}

    /* {{{ensureUID
     * Ensures that this object is ready for database operations.  If c_uid is
     * null, anything to do with the database might fail.
     */
    function ensureUID($line, $file) {
        if (!$this->c_uid) {
            trigger_error("Object's c_uid isn't set in class "
                .  get_class($this)
                . " at line $line of file $file", E_USER_ERROR);
        }
    } //}}}

    /* {{{getVarArray
     * Method to get an object to return an array of its variables, in
     * a form suitable for sending to Template::replace().
     */
    function getVarArray() {
        $array = array();

        foreach ($this->getDatabaseVars() as $key) {
            $array[strtoupper($key)] = $this->$key;
        }

        // And the UID, which is too common among all classes and needs to be
        // renamed to avoid frequent clashes in templating situations.
        unset($array["C_UID"]);
        $array[strtoupper("t_" . get_class($this))] = $this->c_uid;

        return $array;
    } //}}}

    /* {{{insertIntoTemplate
     */
    function insertIntoTemplate($templateText) {
        return Template::replace($templateText,
            $this->getVarArray());
    } //}}}

    /* {{{getOwner
     *
     */
    function getOwner() {
        return $this->c_owner;
    } //}}}

    /* {{{setOwner
     *
     */
    function setOwner($value) {
        $this->c_owner = $value;
    } //}}}

    /* {{{setCreator
     *
     */
    function setCreator($value) {
        $this->c_creator = $value;
    } //}}}

    /* {{{getCreator
     *
     */
    function getCreator() {
        return $this->c_creator;
    } //}}}

    /* {{{getGroup
     *
     */
    function getGroup() {
        return $this->c_group;
    } //}}}

    /* {{{setGroup
     *
     */
    function setGroup($value) {
        $this->c_group = $value;
    } //}}}

    /* {{{getCreatedDate
     *
     */
    function getCreatedDate() {
        return $this->c_created_date;
    } //}}}

    /* {{{setCreatedDate
     *
     */
    function setCreatedDate($value) {
        $this->c_created_date = date("Y-m-d H:i:s", strtotime($value));
    } //}}}

    /* {{{getDeleted
     *
     */
    function getDeleted() {
        return $this->c_deleted;
    } //}}}

    /* {{{setDeleted
     *
     */
    function setDeleted($value) {
        $this->c_deleted = $value;
    } //}}}

    /* {{{getStatus
     *
     */
    function getStatus() {
        return $this->c_status;
    } //}}}

    /* {{{setStatus
     *
     */
    function setStatus($value) {
        $this->c_status = $value;
    } //}}}

    /* {{{getUID
     *
     */
    function getUID() {
        return intval($this->c_uid);
    } //}}}

    /* {{{getUnixperms
     *
     */
    function getUnixperms() {
        return intval($this->c_unixperms);
    } //}}}

    /* {{{getPerm
     * Returns 1 if the permission bit is set.
     * $permName is the *name* of the permission.
     */
    function getPerm($permName) {
        global $cfg;
        return (($cfg['perm'][$permName] & intval($this->c_unixperms)) != 0 ) ? 1 : 0;
    } //}}}

    /** {{{setPerm
     * Sets the named permission bit true or false
     */
    function setPerm($permName, $value) {
        global $cfg;

        if (!isset($cfg['perm'][$permName])) {
            trigger_error("Permission '$permName' isn't defined", E_USER_NOTICE);
        }

        # Watch out for bitwise operations on null values... nothing happens
        if (!$this->c_unixperms) {
            $this->c_unixperms = 0;
        }

        $this->c_unixperms = $value
            ? $this->c_unixperms | $cfg['perm'][$permName]
            : $this->c_unixperms & (~ $cfg['perm'][$permName]);
    } //}}}

    /* {{{equals
     *
     */
    function equals($otherObject) { //boolean
        return (is_object($otherObject)
            && get_class($otherObject) == get_class($this)
            && $otherObject->GUID == $this->GUID);
    } //}}}

    /* {{{toString
     */
    function toString() {
        return get_class($this) . "($this->c_uid) ["
            . bitmaskString($this->c_status, 'status_id') . "]";
    } //}}}

    /* {{{getDeletionReport
     */
    function getDeletionReport($cascade) {
        global $obj;
        $result = array();

        # Save a reference to this object to avoid infinite recursion
        if (isset($obj['visited-objects'][$this->GUID])) {
            return $result;
        }
        else {
            $obj['visited-objects'][$this->GUID] = 1;
        }

        $this->ensureUID(__LINE__, __FILE__);

        if ($cascade) {
            # Iterate through the methods that are defined to return the objects
            # that need to be deleted when this object is deleted
            foreach ($this->getForeignKeys() as $key) {
                foreach ($this->getChildren($key['c_child_table'], $key['c_child_col'], true) as $object) {
                    $result = array_merge($result,
                            $object->getDeletionReport(TRUE));
                }
            }
        }

        $result[] = get_class($this). "($this->c_uid)";
        return $result;
    } //}}}

    function initAllowedActions() {
        global $obj;
        global $cfg;
        $this->ensureUID(__LINE__, __FILE__);
        $this->allowedActions = array();
        $cmd = $obj['conn']->createCommand();
        $cmd->loadQuery("sql/privilege/select-allowed-object-actions.sql");
        $cmd->addParameter("member", $cfg['user']);
        $cmd->addParameter("groups", $obj['user']->c_group_memberships);
        $cmd->addParameter("object", $this->c_uid);
        $cmd->addParameter("table", $this->table);
        $cmd->addParameter("root_group", $cfg['root_uid']);
        foreach ($cfg['perm'] as $name => $bitmask) {
            $cmd->addParameter($name, $bitmask);
        }
        $result = $cmd->executeReader();
        while ($row = $result->fetchRow()) {
            $this->allowedActions[$row['c_title']] = $row;
        }
    }

    function getAllowedActions($refresh = false) {
        if (!isset($this->allowedActions) or $refresh) {
            $this->initAllowedActions();
        }
        return $this->allowedActions;
    }

    function permits(/*int*/ $action) {
        return array_key_exists($action, $this->getAllowedActions());
    }

    function getForeignKeys() {
        global $obj;
        global $cfg;
        $res = array();

        $cmd = $obj['conn']->createCommand();
        $cmd->loadQuery("sql/misc/select-all-foreign-keys.sql");
        $cmd->addParameter("parent", $this->table);
        $result = $cmd->executeReader();
        while ($row = $result->fetchRow()) {
            $row['c_parent_table'] = substr($row['c_parent_table'], strlen($cfg['table_prefix']));
            $row['c_child_table'] = substr($row['c_child_table'], strlen($cfg['table_prefix']));
            $res[] = $row;
        }
        return $res;
    }

    function getChildren($type, $key = null, $refresh = false) {
        if ($refresh || !isset($this->children[$type])) {
            $this->initChildren($type, $key);
        }
        return $this->children[$type];
    }


    function initChildren($type, $key = null) {
        global $obj;
        global $cfg;
        $this->ensureUID(__LINE__, __FILE__);
        require_once("$type.php");

        # See if there is a relationship defined between this object's table and
        # the table passed in.  If so, select the items in the child table and
        # store them in the $children array.  The $col parameter is optional
        # and, in case there is more than one relationship between the tables,
        # tells which to use.  Right now this assumes single-column foreign keys.

        $cmd = $obj['conn']->createCommand();
        $cmd->loadQuery("sql/misc/select-foreign-key.sql");
        $cmd->addParameter("parent", $this->table);
        $cmd->addParameter("child", $cfg['table_prefix'] . $type);
        if (isset($key)) {
            $cmd->addParameter("key", $key);
        }
        $col = $cmd->executeScalar();

        if (!$col) {
            trigger_error("No such key '$key' defined between '$this->table' "
                . "and '$type'", E_USER_ERROR);
        }

        $this->children[$type] = array();
        $result = $obj['conn']->query("select * from $cfg[table_prefix]$type "
            . "where $col = $this->c_uid and c_deleted <> 1");
        while ($row = $result->fetchRow()) {
            $this->children[$type][$row['c_uid']] =& new $type();
            $this->children[$type][$row['c_uid']]->initFromRow($row);
        }

    }

    /* {{{makePrimary
     * Sets this object as primary and unsets it for every
     * other object of this type that this member owns.  WARNING:
     * This function won't work on every object type, only those that
     * have a c_primary column!  It is only in this class because PHP4
     * lacks interfaces, and I don't want to duplicate the code.
     */
    function makePrimary() {
        global $obj;
        $cmd = $obj['conn']->createCommand();
        $cmd->loadQuery("sql/misc/set-primary.sql");
        $cmd->addParameter("table", $this->table);
        $cmd->addParameter("object", $this->c_uid);
        $cmd->addParameter("member", $this->c_owner);
        $cmd->executeNonQuery();
    } //}}}

}
?>
