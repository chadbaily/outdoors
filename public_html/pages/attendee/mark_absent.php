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
 * $Id: mark_absent.php,v 1.2 2005/08/02 03:05:04 bps7j Exp $
 *
 * Allows a leader to mark an attendee as absent.
 */

include_once("absence.php");
include_once("Email.php");

# Create templates
$template = file_get_contents("templates/attendee/mark_absent.php");
$template = $object->insertIntoTemplate($template);

# Create the member and adventure for the absence
$member =& new member();
$member->select($object->getMember());
$adventure =& new adventure();
$adventure->select($object->getAdventure());

# Check to see if this member is already marked as absent from this adventure.
$cmd = $obj['conn']->createCommand();
$cmd->loadQuery("sql/absence/count-by-attendee.sql");
$cmd->addParameter("attendee", $cfg['object']);
$already = $cmd->executeScalar();

if ($already) {
    $template = Template::unhide($template, "ALREADY");
}
else {
    # Create the form.
    $form =& new XMLForm("forms/attendee/mark_absent.xml");

    # Validate the form
    $form->snatch();
    $form->validate();
    
    if ($form->isValid()) {
        $absence =& new absence();
        $absence->setAttendee($cfg['object']);
        $absence->setComment($form->getValue("comment"));
        $absence->setSeverity($form->getValue("severity"));
        $absence->insert();
        if ($form->getValue("email") == "1" ) {
            $leader =& new member();
            $leader->select($adventure->getOwner());

            $emailTemplate = file_get_contents("templates/emails/absence.txt");

            # Get a count of this member's absences
            $cmd = $obj['conn']->createCommand();
            $cmd->loadQuery("sql/absence/count-by-member.sql");
            $cmd->addParameter("member", $object->getMember());
            $num = $cmd->executeScalar();

            $email =& new Email();
            $email->addTo($member->getEmail());
            $email->setFrom($cfg['club_admin_email']);
            $email->addCC($leader->getEmail());
            $email->setSubject("Absence for '" . $adventure->getTitle() . "'");
            $email->setBody(Template::replace($emailTemplate, array(
                "LEADER_EMAIL" => $leader->getEmail(),
                "NUM" => $num,
                "TITLE" => $adventure->getTitle(),
                "NAME" => $member->getFirstName(),
                "MEMBER_NAME" => $member->getFullName()
                )));
            $email->setWordWrap(TRUE);
            $email->loadFooter("templates/emails/footer.txt");
            $email->send();
        }
        $template = Template::unhide($template, "SUCCESS");
    }
    else {
        $template = Template::unhide($template, "INSTRUCTIONS");
        $template = Template::replace($template, array("FORM" => $form->toString()));
    }
}

$res['content'] = $member->insertIntoTemplate(
    $adventure->insertIntoTemplate($template));
$res['title'] = "Mark Attendee as Absent";

?>
