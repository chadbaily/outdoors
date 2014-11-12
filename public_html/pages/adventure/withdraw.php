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
 * $Id: withdraw.php,v 1.2 2005/09/12 01:39:02 bps7j Exp $
 */

$template = file_get_contents("templates/adventure/withdraw.php");
$template = $object->insertIntoTemplate($template);

$leader =& new member();
$leader->select($object->getOwner());

$error = false;

# Ensure that the user is not the adventure's leader
if ($leader->equals($obj['user'])) {
    $template = Template::unhide($template, "LEADER");
    $error = true;
}

# Ensure that the adventure's signup/withdraw date is in the future
if (date("Y-m-d H:i:s") >= $object->getSignupDate()) {
    $template = Template::unhide($template, "DEADLINE");
    $error = true;
}

# Ensure that the member is attending this adventure
if (!JoinAdventure::checkIfMemberIsAttending($object, $obj['user'])) {
    $template = Template::unhide($template, "ALREADY");
    $error = true;
}

if (getval('continue') && !$error) {
    $attendee = JoinAdventure::getAttendee($obj['user'], $object);

    # Before removing someone from the waitlist, delete the attendee so there's
    # room on the adventure for the waitlisted person in case the adventure is
    # full.
    $attendee->delete(FALSE, TRUE);
    if ($attendee->getStatus() == $cfg['status_id']['default']
        && !$object->getWaitlistOnly())
    {
        JoinAdventure::removeFirstWaitlistedMember($object);
    }

    # Send a confirmation email
    JoinAdventure::sendWithdrawalConfirmation($obj['user'], $object);
    # Show confirmation page
    $template = Template::unhide($template, "SUCCESS");

}
elseif (!$error) {
    $template = Template::unhide($template, "CONFIRM");
}

# Plug it all into the templates
$res['content'] = $object->insertIntoTemplate($template);
$res['title'] = "Withdraw From Adventure";

?>
