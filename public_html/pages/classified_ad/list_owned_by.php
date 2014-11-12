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
 * $Id: list_owned_by.php,v 1.2 2005/08/02 03:05:05 bps7j Exp $
 */

# Create template
$template = file_get_contents("templates/classified_ad/list_owned_by.php");

$cmd = $obj['conn']->createCommand();
$cmd->loadQuery("sql/classified_ad/list_owned_by.sql");
$cmd->addParameter("member", $cfg['user']);
$result = $cmd->executeReader();

if ($result->numRows()) {
    $template = Template::unhide($template, "SOME");
    while ($row = $result->fetchRow()) {
        $template = Template::block($template, "ROW",
            array_change_key_case($row, 1));
    }

}
else {
    $template = Template::unhide($template, "NONE");
}

$res['content'] = $template;
$res['title'] = "Classified Ads";

?>
