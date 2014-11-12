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
 * $Id: delete.php,v 1.2 2005/08/02 03:05:06 bps7j Exp $
 */

$template = file_get_contents("templates/common/delete.php");
$template = $object->insertIntoTemplate($template);

$continue = postval('continue');
$cascade = postval('cascade');
$delete = postval('delete');

if ($continue &&isset($delete) && isset($delete)) {
    $object->delete($delete, $cascade);
    if ($delete) {
        $template = Template::unhide($template, "DELETE");
    }
    else {
        $template = Template::unhide($template, "MARK");
    }
    $res['title'] = "Object Deleted";
}
else {
    $template = Template::unhide($template, "CONFIRM");
    $template = Template::unhide($template, "FORM");
    $objs = $object->getDeletionReport(TRUE);
    $template = Template::replace($template, array(
        "OBJECTS" => implode("</li><li>", $objs)));
    $template = Template::unhide($template, "TODELETE");
    $res['title'] = "Confirm Object Deletion";
}

# Plug it all into the template
$template = $object->insertIntoTemplate($template);
$res['content'] = Template::replace($template, array(
    "ACTION" => $cfg['action']));

?>
