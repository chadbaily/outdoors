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
 * $Id: default.php,v 1.3 2005/08/02 02:46:31 bps7j Exp $
 */

$template = file_get_contents("templates/adventure/default.php");

# Get the next 5 upcoming adventures
$cmd = $obj['conn']->createCommand();
$cmd->loadQuery("sql/adventure/list_all-upcoming.sql");
$cmd->addParameter("active", $cfg['status_id']['active']);
$result = $cmd->executeReader();
if ($result->numRows()) {
    while ($row = $result->fetchRow()) {
        $template = Template::block($template, "ROW",
            array_change_key_case($row, 1));
    }
    $template = Template::unhide($template, "SOME");
}
else {
    $template = Template::unhide($template, "NONE");
}

# Unhide some items depending on who's logged in
$obj['table'] =& new table("$cfg[table_prefix]$cfg[page]");
if ($obj['table']->permits('create')) {
    $template = Template::unhide($template, array("CREATE", "INACTIVE"));
}
if ($obj['table']->permits('list_owned_by')) {
    $template = Template::unhide($template, "LIST_OWNED");
}

$res['content'] = $template;

?>
