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
 * $Id: read.php,v 1.3 2005/08/31 00:40:22 bps7j Exp $
 */

$template = file_get_contents("templates/location/read.php");

# Only show the weather forecast link if there is a zip code
if ($object->getZipCode()) {
    $template = Template::unhide($template, "WEATHER");
}

# Show a list of all activity types for this location
$cmd = $obj['conn']->createCommand();
$cmd->loadQuery("sql/location/select-activities.sql");
$cmd->addParameter("location", $cfg['object']);
$result = $cmd->executeReader();

while ($row = $result->fetchRow()) {
    $template = Template::block($template, "ACTIVITY",
        array_change_key_case($row, 1));
}

if ($result->numRows()) {
    $template = Template::unhide($template, "ACTS");
}

# Show a list of adventures at this location
$cmd = $obj['conn']->createCommand();
$cmd->loadQuery("sql/adventure/select-by-location.sql");
$cmd->addParameter("active", $cfg['status_id']['active']);
$cmd->addParameter("destination", $cfg['object']);
$result = $cmd->executeReader();

while ($row = $result->fetchRow()) {
    $template = Template::block($template, "ADVENTURE", $row);
}
if ($result->numRows()) {
    $template = Template::unhide($template, "SOME");
}

$res['content'] = $object->insertIntoTemplate($template);
$res['title'] = "Location : "
    . substr($object->getTitle(), 0, 45)
    . (strlen($object->getTitle()) > 45 ? "..." : "");

?>
