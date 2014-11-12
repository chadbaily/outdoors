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
 * $Id: default.php,v 1.3 2009/03/12 03:15:59 pctainto Exp $
 *
 * Presents a default list of actions you can take on a table.  If there is a
 * template, it uses that; otherwise it uses a default template.  You can use
 * the same comment-based $res variables as you use in the 'about' section.
 */

include_once("includes/authorize.php");

if (file_exists("templates/$cfg[page]/default.php")) {
    $wrapper = file_get_contents("templates/$cfg[page]/default.php");
}
else {
    $wrapper = file_get_contents("templates/common/default.php");
}

# Plug in allowed actions.  If there is only one, redirect to it.
$obj['table'] = new table("$cfg[table_prefix]$cfg[page]");
$links = 0;
$singleAction = 0;
foreach ($obj['table']->getAllowedActions() as $key => $row) {
    # Check that the action is actually implemented before showing a link to it
    if (file_exists("$cfg[page_path]/$key.php")) {
        $links++;
        $singleAction = $row['c_title'];
        $wrapper = Template::block($wrapper, "actions", $row);
    }
}
if ($links == 1) {
    redirect("$cfg[base_url]/members/$cfg[page]/$singleAction");
}

$res['navbar'] = "Member's Area";
$res['content'] = $wrapper;
$res['title'] = "Choose an Action";

# Extract variables from the template file and use them.
$matches = array();
preg_match_all('/^<!-- (\w+)=(.*?) -->$/m', $res['content'], $matches);
foreach ($matches[1] as $key => $value) {
    $res[$matches[1][$key]] = $matches[2][$key];
}

?>
