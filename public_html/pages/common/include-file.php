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
 * $Id: include-file.php,v 1.3 2005/08/02 02:51:32 bps7j Exp $
 *
 * This file is included from many pages that have the generic directory
 * structure of files named with the name of the action.  If the action file
 * exists, this file will include it; if not, it will include the
 * not-implemented file.  If there's no action to use when determining a file,
 * default.php is included instead.
 *
 */

if ($cfg['action']) {

    # If the action requires a specific object, create it and call it $object.
    if (isset($cfg['actions'][$cfg['action']])
        && in_array($cfg['action'], $cfg['require_object_actions'])) {
        if (!$cfg['object']) {
            # Error: there's no way to identify the object!
            trigger_error("Object required, referred from $_SERVER[HTTP_REFERER]", E_USER_ERROR);
            $res['content'] = file_get_contents("templates/misc/require-object.php");
            $res['title'] = "Error: Object Required";
            return;
        }
        $object =& new $cfg['page']();
        $object->select($cfg['object'], false);
        if (!$object->getUID()) {
            trigger_error("No row $cfg[object] in $object->table", E_USER_ERROR);
            $res['content'] = "
            <h1>Error Identifying Object</h1>

            <p>We're sorry, but there has been an error.  This action requires
            an object to act upon, but the system could not find the object your
            browser specified.  The object could have been removed from the
            database, or your browser may have sent an identifier incorrectly.</p>";

            $res['title'] = "Error Identifying Object";

            return;
        }

        # Check permissions
        if (!$object->permits($cfg['action'])) {
            trigger_error("User " . $obj['user']->toString() . " (" 
                . $obj['user']->getFullName()
                . ") is not allowed to take action "
                . $cfg['actions'][$cfg['action']]['c_summary']
                . "($cfg[action]) on object " . $object->toString()
                . "; referred from $_SERVER[HTTP_REFERER] on "
                . "$_SERVER[REQUEST_URI]", E_USER_ERROR);
            $res['content'] = "
            <h1>Permission Error</h1>

            <p>Sorry, but you are not allowed to take this action
            <b>({$cfg['actions'][$cfg['action']]['c_summary']})</b>
            on this object.</p>";

            $res['title'] = "Permission Error";
            $res['help'] = "HelpOnPrivileges";

            return;
        }

    }
    elseif (isset($cfg['actions'][$cfg['action']])) {
        # It's assumed that the action is on a table, because the table/object
        # actions are mutually exclusive.   Check permissions:
        $table =& new table("$cfg[table_prefix]$cfg[page]");
        if (!$table->permits($cfg['action'])) {
            mail($cfg['webmaster_email'],
                "Permission Error",
                "User " . $obj['user']->toString() . " (" .  $obj['user']->getFullName()
                    . ") is not allowed to take action {$cfg['actions'][$cfg['action']]['c_summary']} "
                    . "($cfg[action]) on table $cfg[page]; referred from $_SERVER[HTTP_REFERER] "
                    . "on $_SERVER[REQUEST_URI]");
            $res['content'] = "
            <h1>Permission Error</h1>

            <p>Sorry, but you are not allowed to take this action
            <b>({$cfg['actions'][$cfg['action']]['c_summary']})</b> on this table.</p>";

            $res['title'] = "Permission Error";

            return;
        }

    }

    # Include the file identified by the action parameter.
    if (isset($cfg['actions'][$cfg['action']])) {
        # If the action is implemented directly in the page's subdirectiory,
        # include the file
        if (file_exists("$cfg[page_path]/$cfg[action].php")) {
            include_once("$cfg[page_path]/$cfg[action].php");
        }
        # If the action is implemented in the common directory, include the file
        elseif (file_exists("pages/common/$cfg[action].php")) {
            include_once("pages/common/$cfg[action].php");
        }
    } elseif (file_exists("pages/$cfg[page]/$cfg[action].php")) {
        # If the action names the file directly instead of referring to it by
        # number, include that file:
        include_once("pages/$cfg[page]/$cfg[action].php");
    } else {
        # The action isn't implemented at all
        include_once("pages/common/not-implemented.php");
    }
}
elseif (file_exists("$cfg[page_path]/default.php")) {
    # Default action
    include_once("$cfg[page_path]/default.php");
}
else {
    # There isn't even a default action defined!  Use the common default page
    include_once("pages/common/default.php");
}

?>
