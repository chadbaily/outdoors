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
 * $Id: check-referential-integrity-extended.php,v 1.3 2005/08/05 20:40:38 bps7j Exp $
 *
 * This page checks referential integrity for "fake" foreign keys that are built
 * out of a combination of two columns (sometimes three) that specify a table,
 * a row, and a column in another table.  This page checks to see that these
 * columns really refer to rows in the specified tables.
 */

# Check permissions.  Only members of the "root" groups are allowed to access
# this page.

if (!$obj['user']->isRootUser()) {
    # The user is not allowed to access this page.
    include_once("pages/common/not-permitted.php");
    return false;
}

$wrapper = file_get_contents("templates/admin/check-integrity.php");
$contents = "";

# Query the database for a list of all tables to check.
$tables = array();
$result = $obj['conn']->query("select * from [_]table");
while ($row = $result->fetchRow(DB_FETCHMODE_ORDERED)) {
    $tables[$row[0]] = $row[0];
}

# ------------------------------------------------------------------------------
# Step One
# ------------------------------------------------------------------------------
$stepOneErrors = "";

# A multi-dimensional array of tables that have columns that store the name of
# another table.  We need to check these columns to make sure that they actually
# contain valid table names, or the later queries will break.  We also use this
# array to store the name of the column that contains the parent table's uid,
# and finally an array of values for the parent table's name so we know which
# table to join against.  If the "col" entry below is left blank, there is no
# specific row to join against (as in the case where a permission applies to an
# entire table).

$specialTablesColumns = array(
    "[_]privilege" => 
        array(
            "name" => "c_related_table",
            "col" => "",
            "vals" => array()),
);

# Check that each table actually refers correctly to another table, and while
# we're at it, prepare for the next step.
foreach ($specialTablesColumns as $key => $arr) {
    $result = $obj['conn']->query("select distinct $arr[name] from $key");
    while ($row = $result->fetchRow(DB_FETCHMODE_ORDERED)) {
        if (!isset($tables[$row[0]])) {
            $stepOneErrors .= "<li><tt>$key.$arr[name]</tt> contains an "
                . "invalid table name <tt>$row[0]</tt></li>";
        }
        # Put the distinct table names into the (initially empty) arrays we
        # created above.  These are the tables that this table's magical "fake
        # foreign key" needs to join against.
        $specialTablesColumns[$key]["vals"][] = $row[0];
    }
}

if ($stepOneErrors) {
    $contents = "<p class='error'>There were some errors in the tables during"
            . " Step One.  These should be fixed as soon as possible.</p><ol>"
            . $stepOneErrors . "</ol>";
}
else {

    # --------------------------------------------------------------------------
    # Step Two
    # --------------------------------------------------------------------------

    # We are now done verifying that we won't get any bogus tables to query,
    # which would be a bad thing.  Now, for the entries in the array that have a
    # "col" value defined, we need to do the actual foreign-key join.

    # A model query, which we will use to build actual queries later.
    $modelQuery = 
        "select child.c_uid, child.{FOREIGN}
        from {CHILD} as child
        left outer join {PARENT} as parent
        on child.{FOREIGN} = parent.c_uid
        where parent.c_uid is null and child.{PARENTCOL} = '{PARENT}'";

    # Query the database and format each result as a list
    $numBadTables = 0;
    $numBadRows = 0;
    foreach ($specialTablesColumns as $key => $arr) {
        # Skip tables that don't key to a specific row
        if (!$arr["col"]) {
            continue;
        }
        foreach ($arr["vals"] as $valKey => $val) {
            $result = $db->query(Template::replace($modelQuery, array(
                "CHILD" => $key,
                "FOREIGN" => $arr["col"],
                "PARENT" => $val,
                "PARENTCOL" => $arr["name"] )));

            if ($result->numRows() > 0) {
                $numBadTables++;
                $contents .= "The following rows in <tt>$key</tt> are "
                        . "invalid:<blockquote>";
                while ($row = $result->fetchRow()) {
                    $numBadRows++;
                    $keys = array_keys($row);
                    $contents .= "\r\n<br><input type='checkbox' "
                        . "name='$key" . "[]' value='$row[c_uid]' "
                        . "id='$key$row[c_uid]'><label for='$key$row[c_uid]'>"
                        . "Row $row[c_uid]: <tt>$keys[1]</tt> = " 
                        . $row[$keys[1]] . " and <tt>$arr[name]</tt> = "
                        . "<tt>$val</tt></label>";
                }
                $contents .= "</blockquote>";
            }
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
$res['title'] = "Check Extended References";

?>
