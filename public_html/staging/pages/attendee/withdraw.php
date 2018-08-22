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
 * $Id: withdraw.php,v 1.4 2009/03/12 03:15:58 pctainto Exp $
 *
 * Purpose: allows a leader to withdraw an attendee from an adventure.
 */

include_once("JoinAdventure.php");

$adventure = new adventure();
$adventure->select($object->getAdventure());
$member = new member();
$member->select($object->getMember());

$template = file_get_contents("templates/attendee/withdraw.php");
$template = $object->insertIntoTemplate(
    $member->insertIntoTemplate(
        $adventure->insertIntoTemplate($template)));

if (getval('waitlist')) {
    # Before removing someone from the waitlist, delete the attendee so there's
    # room on the adventure for the waitlisted person in case the adventure is
    # full.
    $object->delete(TRUE, TRUE);
    # Send a confirmation email
    JoinAdventure::sendWithdrawalConfirmation($member, $adventure);

    # If specified, move the first person off the waitlist
    if ($object->getStatus() == $cfg['status_id']['default']
        && !$adventure->getWaitlistOnly()
        && getval('waitlist') == "true")
    {
        $moved = JoinAdventure::removeFirstWaitlistedMember($adventure);
        if ($moved) {
            $template = Template::unhide($template, "MOVED");
            $template = Template::replace($template, array(
                "MEMBER_NAME" => $moved->getFullName()));
        }
        else {
            $template = Template::unhide($template, "NO_MOVED");
        }
    }

    # Show confirmation page
    $template = Template::unhide($template, "SUCCESS");
}
else {
    $template = Template::unhide($template, "CONFIRM");
}

$res['content'] = $template;
$res['title'] = "Withdraw Attendee from Adventure";

?>
