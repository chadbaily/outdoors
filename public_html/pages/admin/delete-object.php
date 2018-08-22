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
 * $Id: delete-object.php,v 1.2 2005/08/02 03:05:04 bps7j Exp $
 *
 * This page deletes objects from the database.  It expects parameters in arrays
 * of the form [_]table_name[].  It will delete every c_uid from [_]table_name.
 * There is no confirmation or anything, it just does it.
 */

# Check permissions.  Only members of the "root" groups are allowed to access
# this page.

if (!$obj['user']->isRootUser()) {
    # The user is not allowed to access this page.
    include_once("pages/common/not-permitted.php");
    return false;
}

$wrapper = file_get_contents("templates/admin/delete-object-wrapper.php");
$contents = "";

# Query the database for a list of all tables that we can delete things from
$tables = array();
$result = $obj['conn']->query("select * from [_]table");
while ($row = $result->fetchRow(DB_FETCHMODE_ORDERED)) {
    $tables[$row[0]] = $row[0];
}

# Get the POST parameters to delete and delete them from the corresponding
# tables.
foreach ($_POST as $key => $array) {
    # Get a comma-separated list of the rows to delete
    $values = implode(",", $_POST[$key]);
    # Make sure the table exists and the values are only integers
    if (isset($tables[$key]) && preg_match("/^[\d,]+$/", $values)) {
        $obj['conn']->query("delete from $key where c_uid in ($values)"); 
        $contents .= "<p>Deleted the following rows from <tt>$key</tt>:"
            . "</p><blockquote>$values</blockquote>";
    }
}

$res['content'] = Template::replace($wrapper, array("DELETED" => $contents));
$res['title'] = "Delete Objects";

?>
