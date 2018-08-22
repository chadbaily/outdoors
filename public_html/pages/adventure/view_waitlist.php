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
 * $Id: view_waitlist.php,v 1.2 2005/08/02 03:05:04 bps7j Exp $
 *
 * This page gives a report of your own status as an attendee and where you are
 * on the waitlist, if you are on the waitlist.
 */

# Create templates
$template = file_get_contents("templates/adventure/view_waitlist.php");

# Check to see that the member is attending the adventure
$attendees = $object->getChildren("attendee");
$found = false;
if (count($attendees)) {
    foreach ($attendees as $key => $attendee) {
        if ($attendee->getMember() == $cfg['user']) {
            $found = true;
            break;
        }
    }
}

if ($found) {
    if ($attendee->getStatus() == $cfg['status_id']['waitlisted']) {
        # Get the waitlist stats
        $cmd = $obj['conn']->createCommand();
        $cmd->loadQuery("sql/adventure/waitlist-statistics-select.sql");
        $cmd->addParameter("member", $cfg['user']);
        $cmd->addParameter("waitlisted", $cfg['status_id']['waitlisted']);
        $cmd->addParameter("joined", $attendee->getJoinedDate());
        $cmd->addParameter("adventure", $cfg['object']);
        $result = $cmd->executeReader();
        $row = $result->fetchRow();
        $template = $object->insertIntoTemplate($template);
        $template = Template::replace($template,
            array_change_key_case($row, 1));
        $template = Template::unhide($template, "ON");
    }
    else {
        $template = Template::unhide($template, "OFF");
    }
}
else {
    $template = Template::unhide($template, "ERROR");
}

# Plug it all into the templates
$res['content'] = $object->insertIntoTemplate($template);
$res['title'] = "View Adventure Waitlist";

?>
