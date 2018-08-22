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
 * $Id: check-foreign-keys.php,v 1.2 2005/08/02 03:05:03 bps7j Exp $
 *
 * This page checks referential integrity for the common attributes of all
 * database objects, such as owner and creator.
 */

# Check permissions.  Only members of the "root" groups are allowed to access
# this page.
if (!$obj['user']->isRootUser()) {
    # The user is not allowed to access this page.
    include_once("pages/common/not-permitted.php");
    return false;
}

$wrapper = file_get_contents("templates/admin/check-foreign-keys.php");
$contents = "";
$numBadTables = 0;
$numBadRows = 0;

# Create a command to use repeatedly when checking for bad rows
$cmd = $obj['conn']->createCommand();
$cmd->loadQuery("sql/misc/check-foreign-keys.sql");

# Query the database for a list of all keys to check.
$keys = array();
$result = $obj['conn']->query("select * from [_]foreign_key");
while ($row = $result->fetchRow()) {

    # Check the key
    $cmd->addParameter("parent", $row['c_parent_table']);
    $cmd->addParameter("child", $row['c_child_table']);
    $cmd->addParameter("primary", $row['c_parent_col']);
    $cmd->addParameter("foreign", $row['c_child_col']);
    $result2 = $cmd->executeReader();

    if ($result2->numRows()) {
        $numBadTables++;
        $contents .= "<p>The following rows in <tt>$row[c_child_table]</tt> "
            . "refer to a nonexistent value of <tt>$row[c_parent_col]</tt> in "
            . "the parent table, <tt>$row[c_parent_table]</tt>:</p>";
        while ($row2 = $result2->fetchRow()) {
            $numBadRows++;
            $keys = array_keys($row2);
            $contents .= "\n<div style='padding-left:20px'>"
                . "<input type='checkbox' name='$row[c_child_table][]'"
                . " value='$row2[c_uid]' id='$row[c_child_table]$row2[c_uid]'>"
                . "\n<label for='$row[c_child_table]$row2[c_uid]'>Row $row2[c_uid]:"
                . " <tt>$row[c_child_col]</tt> = {$row2[$row['c_child_col']]}</label></div>";
        }
    }
}

if ($numBadTables > 0) {
    $wrapper = Template::replace($wrapper, array(
        "NUMROWS" => $numBadRows,
        "RESULTS" => $contents,
        "NUMTABLES" => $numBadTables));
    $wrapper = Template::unhide($wrapper, "SOME");
}
else {
    $wrapper = Template::unhide($wrapper, "NONE");
}

$res['content'] = $wrapper;
$res['title'] = "Check Foreign Keys";

?>
