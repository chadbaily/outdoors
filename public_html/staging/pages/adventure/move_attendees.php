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
 * $Id: check_in.php,v 1.4 2006/03/27 03:46:25 bps7j Exp $
 */

include_once("JoinAdventure.php");

if ($cfg['response_type'] == 'JSON') {
    $response = array();
    $response['response'] = "success";
    $response['msg'] = "";
    $attendees = array();
    if (!JoinAdventure::currentUserCanJoinAttendee($object,$cfg)) {
        $response['response'] = "failure";
        $response['msg'] = "Only the trip leader or the officer can take this action.";
    }
    else if (isset($_GET['attendee_action']) &&
        isset($_GET['attendee']) && is_array($_GET['attendee'])) {
        $att_action = $_GET['attendee_action'];
        
        //Perform action
        foreach ($_GET['attendee'] as $attendee_id) {
            $attendee = new attendee();
            $attendee->select($attendee_id);
            $member = new member();
            $member->select($attendee->getMember());

            $att_response = array('action'=>$att_action, 'attendee'=>$attendee_id, 'response'=>"success", 'msg'=>"");
            if ($att_action == 'join') {
                # Ensure that the member isn't already attending this adventure
                if ($attendee->getStatus() != $cfg['status_id']['waitlisted']) {
                    $att_response['response'] = "failure";
                    $att_response['msg'] = "This member is already attending the adventure";
                    continue;
                }
                //Mark them as joined
                $attendee->setStatus($cfg['status_id']['default']);
                $attendee->update();
                JoinAdventure::sendJoinConfirmation($member, $object,
                    $attendee->getStatus());
            } else if ($att_action == 'waitlist') {
                # Check that the attendee isn't already waitlisted
                if ($attendee->getStatus() == $cfg['status_id']['waitlisted']) {
                    $att_response['msg'] = "This member is already attending the adventure";
                    $att_response['response'] = "failure";
                    continue;
                }
                //Set them as waitlisted
                $attendee->setStatus($cfg['status_id']['waitlisted']);
                if ($_GET['where'] == "back") {
                    $attendee->setJoinedDate(date("Y-m-d h:i:s"));
                }
                $attendee->update();
                # Send a confirmation email
                JoinAdventure::sendWaitlistConfirmation($member, $object);
            } else if ($att_action == 'mark_absent') {
                # Check to see if this member is already marked as absent from this adventure.
                $cmd = $obj['conn']->createCommand();
                $cmd->loadQuery("sql/absence/count-by-attendee.sql");
                $cmd->addParameter("attendee", $cfg['object']);
                $already = $cmd->executeScalar();

            } else if ($att_action == 'withdraw') {
                # Before removing someone from the waitlist, delete the attendee so there's
                # room on the adventure for the waitlisted person in case the adventure is
                # full.
                $attendee->delete(TRUE, TRUE);
                # Send a confirmation email
                JoinAdventure::sendWithdrawalConfirmation($member, $object);
            
                # If specified, move the first person off the waitlist
                if ($attendee->getStatus() == $cfg['status_id']['default']
                    && !$attendee->getWaitlistOnly()
                    && getval('waitlist') == "true")
                {
                    $moved = JoinAdventure::removeFirstWaitlistedMember($attendee);
                    if ($moved) {
                        $template = Template::unhide($template, "MOVED");
                        $template = Template::replace($template, array(
                            "MEMBER_NAME" => $moved->getFullName()));
                    }
                    else {
                        $template = Template::unhide($template, "NO_MOVED");
                    }
                }
            }
            array_push($attendees, $att_response);
        }

        //Get a count for the number joined and the number waitlisted
        $cmd = $obj['conn']->createCommand();
        $cmd->loadQuery("sql/adventure/view_report-summary.sql");
        $cmd->addParameter("adventure", $cfg['object']);
        $result = $cmd->executeReader();
        $row = $result->fetchRow();
        
        $response['num_waitlisted'] = $row['waitlisted'];
        $response['num_joined'] = $row['joined'];;
        $response['attendees'] = $attendees;
        echo json_encode($response);
    }
}

?>
