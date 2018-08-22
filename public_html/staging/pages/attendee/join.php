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
 * $Id: join.php,v 1.3 2009/11/15 14:49:33 pctainto Exp $
 *
 * Purpose: lets a leader join a waitlisted attendee onto an adventure.  Follows 
 * the same logic as the process for joining from the waitlist...
 */

include_once("JoinAdventure.php");

$adventure = new adventure();
$adventure->select($object->getAdventure());
$member = new member();
$member->select($object->getMember());
$current_user = new member();
$current_user->select($cfg['user']);

$template = file_get_contents("templates/attendee/join.php");
$template = $member->insertIntoTemplate(
    $adventure->insertIntoTemplate($template));

$error = currentUserCanJoinAttendee($adventure);

# Ensure that the member isn't already attending this adventure
if ($object->getStatus() != $cfg['status_id']['waitlisted']) {
    $template = Template::unhide($template, "ALREADY");
    $error = true;
}

if (!$error && getval('continue')) {
    $object->setStatus($cfg['status_id']['default']);
    $object->update();
    JoinAdventure::sendJoinConfirmation($member, $adventure,
        $object->getStatus());
    $template = Template::unhide($template, "SUCCESS");
}
elseif (!$error) {
    $template = Template::unhide($template, "CONFIRM");
}

$res['content'] = $object->insertIntoTemplate($template);
$res['title'] = "Move Attendee Off Waitlist";

?>
