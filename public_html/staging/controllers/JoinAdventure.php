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
 * $Id: JoinAdventure.php,v 1.5 2009/11/15 14:50:13 pctainto Exp $
 */
// {{{require statements
include_once("Email.php");
include_once("location.php");
include_once("privilege.php");
//}}}

class JoinAdventure {

    /* {{{addQuestionsToForm
     *
     */
    function addQuestionsToForm($formTemplate, &$adventure) {
        global $obj;
        # Extract some templates out of the template
        $boolConfig = Template::extract($formTemplate, "BOOL_CONFIG");
        $textConfig = Template::extract($formTemplate, "TEXT_CONFIG");
        $text = Template::extract($formTemplate, "TEXT");
        $bool = Template::extract($formTemplate, "BOOL");
        # Go through the adventure's questions and plug them into the form
        # template
        $cmd = $obj['conn']->createCommand();
        $cmd->loadQuery("sql/question/select-by-adventure.sql");
        $cmd->addParameter("adventure", $adventure->c_uid);
        $result = $cmd->executeReader();
        while ($row = $result->fetchRow()) {
            $whichConfig = "$row[c_type]Config";
            $formTemplate = Template::replace($formTemplate, array(
                "ELEMENTS" => Template::replace($$row['c_type'],
                    array_change_key_case($row, 1))), true);
            $formTemplate = Template::replace($formTemplate, array(
                "CONFIGS" => Template::replace($$whichConfig,
                    array_change_key_case($row, 1))), true);
        }
        return $formTemplate;
    } //}}}

    /* {{{checkIfMemberIsAttending
     *
     */
    function checkIfMemberIsAttending(&$adventure, &$member) {
        $attendees = $adventure->getChildren("attendee");
        foreach (array_keys($attendees) as $key) {
            if ($attendees[$key]->getMember() == $member->getUID()) {
                return true;
            }
        }
        return false;
    } //}}}

    /* {{{sendJoinConfirmation
     *
     */
    function sendJoinConfirmation(&$member, &$adventure, $status) {
        global $cfg;

        $waitlist = ($status == $cfg['status_id']['waitlisted']);

        $leader = new member();
        $leader->select($adventure->getOwner());
        $phone = $leader->getPrimaryPhoneNumber();
        $departure = new location();
        $departure->select($adventure->getDeparture());
        $destination = new location();
        $destination->select($adventure->getDestination());

        $emailTemplate = "";
        if ($waitlist) {
            $emailTemplate =
                file_get_contents("templates/emails/welcome-to-adventure-waitlist.txt");
        }
        else {
            $emailTemplate =
                file_get_contents("templates/emails/welcome-to-adventure.txt");
        }

        $email = new Email();
        $email->addTo($member->getEmail());
        $email->setFrom($leader->getEmail());
        $email->addCC($leader->getEmail());
        if ($waitlist) {
            $email->setSubject("Welcome to the Waitlist for " . $adventure->getTitle());
        }
        else {
            $email->setSubject("Welcome to " . $adventure->getTitle());
        }
        $array = array(
            "LEADER_NAME" => $leader->getFullName(),
            "LEADER_EMAIL" => $leader->getEmail(),
            "LEADER_PHONE" => is_object($phone) ? $phone->toString() : "NONE",
            "C_FULL_NAME" => $member->getFullName(),
            "TO_EMAIL" => $member->getEmail(),
            "DEPARTURE" => $departure->getTitle(),
            "BASEURL" => $cfg['site_url'] . $cfg['base_url']
            );
        $emailTemplate = $adventure->insertIntoTemplate($emailTemplate);
        $email->setBody(Template::replace($emailTemplate, $array));
        $email->setWordWrap(FALSE);
        $email->loadFooter("templates/emails/footer.txt");
        $email->send();
    } //}}}

    /* {{{memberIsWaitlisted
     *
     */
    function memberIsWaitlisted(&$adventure, &$member) {
        global $cfg;
        foreach ($adventure->getChildren("attendee") as $key => $attendee) {
            if ($attendee->getMember() == $member->getUID()) {
                return ($attendee->getStatus() ==
                    $cfg['status_id']['waitlisted']);
            }
        }
        return false;
    } //}}}

    /* {{{getAttendee
     */
    function getAttendee(&$member, &$adventure) {
        foreach ($adventure->getChildren("attendee") as $key => $att) {
            if ($att->getMember() == $member->getUID()) {
                return $att;
            }
        }
        return null;
    } //}}}

    /* {{{sendWithdrawalConfirmation
     */
    function sendWithdrawalConfirmation(&$member, &$adventure) {
        $leader = new member();
        $leader->select($adventure->getOwner());
        $emailTemplate = 
            file_get_contents("templates/emails/adventure-withdraw.txt");

        $email = new Email();
        $email->addTo($member->getEmail());
        $email->setFrom('"' . $leader->getFullName() . '" <' . $leader->getEmail() . ">");
        $email->addHeader("Reply-To",  $leader->getEmail());
        $email->addHeader("Return-Path",  $leader->getEmail());
        $email->addCC($leader->getEmail());
        $email->setSubject("Withdrawal Confirmation"); 
        $array = array(
            "LEADER_NAME" => $leader->getFullName(),
            "TO_NAME" => $member->getFullName()
            );
        $emailTemplate = $adventure->insertIntoTemplate($emailTemplate);
        $email->setBody(Template::replace($emailTemplate, $array));
        $email->setWordWrap(TRUE);
        $email->loadFooter("templates/emails/footer.txt");
        $email->send();
    } //}}}

    /* {{{removeFirstWaitlistedMember
     * Joins the first waitlisted member and returns that object.  Does NOT
     * remove any members if the adventure is already full.  This is to prevent
     * the following undesirable scenario: The adventure fills and the leader
     * removes someone from the waitlist manually.  Now the adventure is
     * overfull.  A member withdraws, and the first person on the waitlist gets
     * un-waitlisted.  This is BAD.  We don't want the adventure to stay
     * overfull.
     */
    function removeFirstWaitlistedMember(&$adventure) {
        global $cfg;
        if (count($adventure->getAttendees("waitlisted"))
            && count($adventure->getAttendees("default")) < $adventure->getMaxAttendees())
        {
            $attendee = array_shift($adventure->getAttendees("waitlisted"));
            $attendee->setStatus($cfg['status_id']['default']);
            $attendee->setJoinedDate(date("Y-m-d H:i:s"));
            $attendee->update();
            $member = new member();
            $member->select($attendee->getMember());
            JoinAdventure::sendJoinConfirmation($member, $adventure,
                $attendee->getStatus());
            return $member;
        }
        return null;
    } //}}}

    /* {{{sendWaitlistConfirmation
     */
    function sendWaitlistConfirmation(&$member, &$adventure) {
        $leader = new member();
        $leader->select($adventure->getOwner());
        $emailTemplate = 
            file_get_contents("templates/emails/waitlist-email.txt");

        $email = new Email();
        $email->addTo($member->getEmail());
        $email->setFrom($leader->getEmail());
        $email->addCC($leader->getEmail());
        $email->setSubject("Waitlist Confirmation"); 
        $array = array(
            "LEADER_NAME" => $leader->getFullName(),
            "TO_NAME" => $member->getFullName()
            );
        $emailTemplate = $adventure->insertIntoTemplate($emailTemplate);
        $email->setBody(Template::replace($emailTemplate, $array));
        $email->setWordWrap(TRUE);
        $email->loadFooter("templates/emails/footer.txt");
        $email->send();
    } //}}}

    /* {{{checkIfMemberCommented
     */
    function checkIfMemberCommented(&$adventure, &$member) {
        foreach ($adventure->getChildren("adventure_comment", "c_adventure") as $key => $comment) {
            if ($comment->getOwner() == $member->getUID()) {
                return true;
            }
        }
        return false;
    } //}}}

    /* {{{currentUserCanJoinAttendee
     */
    function currentUserCanJoinAttendee(&$adventure, &$cfg) {
        $current_user = new member();
        $current_user->select($cfg[user]);
        # Ensure that the person joining the attendee is either the leader
        # of the trip or is an officer (or root)
        if ($cfg[user] != $adventure->getOwner() &&
            (!isset($cfg['group_id']['officer']) || !$current_user->isInGroup($cfg['group_id']['officer'])) &&
             !$current_user->isRootUser()) {
            return false;
        }
        return true;
    }    
}
?>
