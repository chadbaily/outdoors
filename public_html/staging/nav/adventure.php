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
 * $Id: adventure.php,v 1.2 2005/06/05 16:22:13 bps7j Exp $
 */

include_once("JoinAdventure.php");

$attending = JoinAdventure::checkIfMemberIsAttending(
    $object, $obj['user']);
$now = date("Y-m-d H:i:s");

if ($now > $object->getStartDate()) {
    $obj['tabbed_box']->deleteTab('Edit Questions');
}

if ($attending || $now > $object->getSignupDate()
        || $object->getOwner() == $cfg['user'])
{
    $obj['tabbed_box']->deleteTab('Join');
}

if ($now >= $object->getStartDate()
        || count($object->getChildren("attendee")))
{
    $obj['tabbed_box']->deleteTab('Deactivate');
}

if (!$attending || $now >= $object->getStartDate()) {
    $obj['tabbed_box']->deleteTab('Activate');
}

if (!$attending
        || $object->getOwner() == $cfg['user']
        || $now >= $object->getStartDate()) {
    $obj['tabbed_box']->deleteTab('Withdraw');
}

if (!JoinAdventure::memberIsWaitlisted($object, $obj['user'])) {
    $obj['tabbed_box']->deleteTab('View Waitlist');
}

if ($now >= $object->getSignupDate()) {
    $obj['tabbed_box']->deleteTab('Announce');
}

if ($now < $object->getEndDate() || !$attending) {
    $obj['tabbed_box']->deleteTab('Comment');
}

if ($now >= $object->getStartDate()) {
    $obj['tabbed_box']->deleteTab('Edit');
}

$obj['tabbed_box']->deleteTab('Cancel');
$obj['tabbed_box']->deleteTab('Choose Activities');

?>
