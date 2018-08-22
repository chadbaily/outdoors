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
 * $Id: read.php,v 1.2 2009/03/12 03:16:01 pctainto Exp $
 */

include_once("chat_type.php");

$template = file_get_contents("templates/chat/read.php");

# Get the chat's owner
$owner = new member();
$owner->select($object->getOwner());

# Get the type of chat number that this is
$type = new chat_type();
$type->select($object->getType());

# Insert the type's details
$template = Template::block($template, "TYPE",
    $type->getVarArray());

$res['content'] = $owner->insertIntoTemplate(
    $object->insertIntoTemplate($template));
$res['title'] = "View Chat Details";

?>
