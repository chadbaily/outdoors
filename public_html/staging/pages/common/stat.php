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
 * $Id: stat.php,v 1.3 2009/03/12 03:15:59 pctainto Exp $
 */

# Create templates
$template = file_get_contents("templates/common/stat.php");

# Find out the names of the groups
$cfg['group_name'] = array_flip($cfg['group_id']);

# Automagically insert what data we can
$template = $object->insertIntoTemplate($template);

# Go through the remaining keys and fill them in
$owner = new member();
$creator = new member();
$owner->select($object->getOwner());
$creator->select($object->getCreator());

$template = Template::replace($template, array(
    "TABLE" => $cfg['page'],
    "OWNER_FIRST_NAME" => $owner->getFirstName(),
    "OWNER_LAST_NAME" => $owner->getLastName(),
    "CREATOR_FIRST_NAME" => $creator->getFirstName(),
    "CREATOR_LAST_NAME" => $creator->getLastName(),
    "GROUP" => $cfg['group_name'][$object->getGroup()],
    "OWNER_READ" => $object->getPerm('owner_read') ? "Yes" : "No",
    "GROUP_READ" => $object->getPerm('group_read') ? "Yes" : "No",
    "OTHER_READ" => $object->getPerm('other_read') ? "Yes" : "No",
    "OWNER_WRITE" => $object->getPerm('owner_write') ? "Yes" : "No",
    "GROUP_WRITE" => $object->getPerm('group_write') ? "Yes" : "No",
    "OTHER_WRITE" => $object->getPerm('other_write') ? "Yes" : "No",
    "OWNER_DELETE" => $object->getPerm('owner_delete') ? "Yes" : "No",
    "GROUP_DELETE" => $object->getPerm('group_delete') ? "Yes" : "No",
    "OTHER_DELETE" => $object->getPerm('other_delete') ? "Yes" : "No"
    ));

# Plug it all into the template
$res['content'] = $object->insertIntoTemplate($template);
$res['title'] = "View Properties";

?>
