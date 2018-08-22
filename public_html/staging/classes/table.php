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
 * $Id: table.php,v 1.3 2005/08/02 02:33:27 bps7j Exp $
 */

class table {
    // {{{declarations
    var $name;
    // An array, not an object!
    var $allowedActions;
    // }}}

    /* {{{constructor
     *
     */
    function table($name) {
        $this->name = $name;
    } //}}}

    /* {{{getVarArray
     *
     */
    function getVarArray() {
        return array("T_TABLE" => $this->name);
    } //}}}

    /* {{{getName
     * Gets the table's name.
     */
    function getName() {
        return $this->name;
    } //}}}

    /* {{{setName
     * Sets the table's name.
     */
    function setName($value) {
        $this->name = $value;
    } //}}}

    /* {{{getUID
     */
    function getUID() {
        return $this->getName();
    } //}}}

    function initAllowedActions() {
        global $obj;
        global $cfg;
        $this->allowedActions = array();
        $cmd = $obj['conn']->createCommand();
        $cmd->loadQuery("sql/privilege/select-allowed-table-actions.sql");
        $cmd->addParameter("member", $cfg['user']);
        $cmd->addParameter("groups", $obj['user']->c_group_memberships);
        $cmd->addParameter("table", $this->name);
        $cmd->addParameter("root_group", $cfg['root_uid']);
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

    /* {{{insertIntoTemplate
     * Automagically inserts the object into a template.  
     */
    function insertIntoTemplate($templateText, $repeat = FALSE) {
        $array = $this->getVarArray();
        return Template::replace($templateText, $array, $repeat);
    } //}}}

}
?>
