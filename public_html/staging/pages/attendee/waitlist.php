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
 * $Id: waitlist.php,v 1.2 2009/03/12 03:15:58 pctainto Exp $
 *
 * Purpose: allows a leader to move an attendee from an adventure's roster onto
 * the end of the waitlist.
 */

include_once("JoinAdventure.php");

$adventure = new adventure();
$adventure->select($object->getAdventure());
$member = new member();
$member->select($object->getMember());

# Create templates
$template = file_get_contents("templates/attendee/waitlist.php");
$template = $object->insertIntoTemplate(
    $member->insertIntoTemplate(
        $adventure->insertIntoTemplate($template)));

$error = false;

# Check that the attendee isn't already waitlisted
if ($object->getStatus() == $cfg['status_id']['waitlisted']) {
    $error = true;
    $template = Template::unhide($template, "ALREADY");
}

if (!$error && isset($_GET['where']) && $_GET['where']) {
    $object->setStatus($cfg['status_id']['waitlisted']);
    if ($_GET['where'] == "back") {
        $object->setJoinedDate(date("Y-m-d h:i:s"));
    }
    $object->update();
    # Send a confirmation email
    JoinAdventure::sendWaitlistConfirmation($member, $adventure);
    # Show confirmation page
    $template = Template::unhide($template, "SUCCESS");
}
elseif (!$error) {
    $template = Template::unhide($template, "CONFIRM");
}

$res['content'] = $template;
$res['title'] = "Move Attendee to Waitlist";

?>
