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
 * $Id: chown.php,v 1.2 2005/08/02 03:05:06 bps7j Exp $
 */

# Create templates
$template = file_get_contents("templates/common/chown.php");

# If the form's been submitted, update the member's owner
$newOwner = postval('owner');

if ($newOwner) {
    $object->setOwner($newOwner);
    $object->update();
    $template = Template::unhide($template, "SUCCESS");
}

# Get a list of all the members in the database, the non-OO way
$result = $obj['conn']->query("select c_uid, c_first_name, c_last_name "
    . "from [_]member order by c_last_name");

# Plug the members into the template, selecting the correct one
while ($row = $result->fetchRow()) {
    $template = Template::block($template, "OWNER", array(
        "C_UID" => $row['c_uid'],
        "C_FIRST_NAME" => $row['c_first_name'],
        "C_LAST_NAME" => $row['c_last_name'],
        "SELECTED" => ($row['c_uid'] == $object->getOwner())
            ? "selected" 
            : ""));
}

# Plug some other common stuff into the template
$template = Template::replace($template, array(
    "TABLE" => get_class($object)));

# Plug it all into the template
$res['content'] = $object->insertIntoTemplate($template);
$res['title'] = "Change Owner";

?>
