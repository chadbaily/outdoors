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
 * $Id: past-adventures.php,v 1.1 2006/03/27 03:46:25 bps7j Exp $
 *
 * Purpose: the member homepage that members see after they log in.
 */

$cfg['login_mode'] = "none";
include_once("includes/authorize.php");

$wrapper = file_get_contents("templates/main/past-adventures.php");

# Get the most popular locations that match the user's interests
$cmd = $obj['conn']->createCommand();
$cmd->loadQuery("sql/adventure/list_all-past.sql");
$cmd->addParameter("active", $cfg['status_id']['active']);
$result = $cmd->executeReader();
$count = 0;
while ($row = $result->fetchRow()) {
    # Plug adventures into the template.
    $wrapper = Template::block($wrapper, "ROW", 
        array_change_key_case($row, 1)
        + array("CLASS" => (($count++ %2) ? "" : " class='odd'")));
}

$res['navbar'] = "Home";
$res['title'] = "$cfg[club_name]'s Past Adventures";
$res['content'] = $wrapper;

?>
