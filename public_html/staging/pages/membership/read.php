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
 * $Id: read.php,v 1.2 2009/03/12 03:15:58 pctainto Exp $
 */

include_once("membership_type.php");

$template = file_get_contents("templates/membership/read.php");

# Get the membership-type object
$mt = new membership_type();
$mt->select($object->getType());
$template = Template::replace($template, array("TYPE_TITLE" => $mt->getTitle()));

# Get the member for the membership
$member = new member();
$member->select($object->getMember());

$cfg['status_title'] = array_flip($cfg['status_id']);
$template = Template::replace($template, array("STATUS_TITLE" =>
        $cfg['status_title'][$object->getStatus()]));
$res['content'] = $member->insertIntoTemplate(
    $object->insertIntoTemplate($template));
$res['title'] = "View Membership Details";

?>
