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
 * $Id: check-orphaned-rows.php,v 1.3 2005/08/02 03:05:04 bps7j Exp $
 *
 * This page checks for objects that don't have broken references, but may be
 * "stranded" because nothing points to them.  For example, an Interest that no
 * one loves :-(
 */

# Check permissions.  Only members of the "root" groups are allowed to access
# this page.
if (!$obj['user']->isRootUser()) {
    # The user is not allowed to access this page.
    include_once("pages/common/not-permitted.php");
    return false;
}

$wrapper = file_get_contents("templates/admin/check-orphans.php");
$contents = "";
$queries = array();

$queries["[_]location"] =
        "select lo.c_uid, lo.c_title from [_]location as lo
        left join [_]adventure as ad1 on lo.c_uid = ad1.c_departure
        left join [_]adventure as ad2 on lo.c_uid = ad2.c_destination
        where ad1.c_uid is null and ad2.c_uid is null";

$queries["[_]activity"] =
        "select ac.c_uid, c_title from [_]activity as ac
        left join [_]interest as it on ac.c_uid = it.c_activity
        left join [_]adventure_activity as aa on ac.c_uid = aa.c_activity
        where it.c_uid is null and aa.c_uid is null";

$queries["[_]adventure"] =
        "select ad.c_uid, c_title from [_]adventure as ad
        left join [_]adventure_activity as aa on aa.c_adventure = ad.c_uid
        left join [_]attendee as at on at.c_adventure = ad.c_uid
        left join [_]question as qu on qu.c_adventure = ad.c_uid
        where
        aa.c_uid is null and at.c_uid is null and qu.c_uid is null";

$queries["[_]chat_type"] =
        "select ct.c_uid, c_title from [_]chat_type as ct
        left join [_]chat as ch on ch.c_type = ct.c_uid
        where
        ch.c_uid is null";

$queries["[_]membership_type"] =
        "select mt.c_uid, c_title from [_]membership_type as mt
        left join [_]membership as ms on ms.c_type = mt.c_uid
        where
        ms.c_uid is null";

$queries["[_]phone_number_type"] =
        "select pt.c_uid, pt.c_title from [_]phone_number_type as pt
        left join [_]phone_number as pn on pn.c_type = pt.c_uid
        where pn.c_uid is null";


# Query the database and format each result as a list
$numBadTables = 0;
$numBadRows = 0;
foreach ($queries as $table => $query) {
    $result = $obj['conn']->query($query);

    if ($result->numRows() > 0) {
        $numBadTables++;
        $contents .= "<p>The following rows in <tt>$table</tt> are orphaned:</p>";
        while ($row = $result->fetchRow()) {
            $numBadRows++;
            $contents .= "\n<div style='padding-left:20px'>"
                . "<input type='checkbox' name='$table" . "[]'"
                . " value='$row[c_uid]' id='$table$row[c_uid]'>"
                . "<label for='$table$row[c_uid]'>Row $row[c_uid] "
                . "($row[c_title])</label></div>";
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
$res['title'] = "Check for Orphaned Rows";

?>
