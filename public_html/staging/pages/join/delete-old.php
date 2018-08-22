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
 * $Id: delete-old.php,v 1.2 2005/08/02 03:05:24 bps7j Exp $
 */

$cfg['login_mode'] = 'partial';
include_once("includes/authorize.php");

$template = file_get_contents("templates/join/delete-old.php");

if (isset($_POST['delete']) && is_array($_POST['delete'])) {
    foreach ($_POST['delete'] as $delete) {
        if (is_numeric($delete)) {
            $cmd = $obj['conn']->createCommand();
            $cmd->loadQuery("sql/membership/delete-inactive.sql");
            $cmd->addParameter("membership", intval($delete));
            $cmd->executeNonQuery();
        }
    }
}

# Plug in inactive memberships
$cmd = $obj['conn']->createCommand();
$cmd->loadQuery("sql/membership/select-for-final-instructions.sql");
$cmd->addParameter("inactive", $cfg['status_id']['inactive']);
$cmd->addParameter("member", $cfg['user']);
$result = $cmd->executeReader();

if ($result->numRows()) {
    $template = Template::unhide($template, "some");
    while ($row = $result->fetchRow()) {
        $template = Template::block($template, "membership", $row);
    }
}
else {
    $template = Template::unhide($template, "none");
}

$res['content'] = $template;
$res['title'] = "Delete Unwanted Memberships";

?>
