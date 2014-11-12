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
 * $Id: join.php,v 1.3 2009/03/12 03:15:58 pctainto Exp $
 *
 * Purpose: lets a member join an adventure.
 */

$leader = new member();
$leader->select($object->getOwner());

$template = file_get_contents("templates/adventure/join.php");
$formTemplate = file_get_contents("forms/adventure/join.xml");
$template = $object->insertIntoTemplate($template);
$template = $leader->insertIntoTemplate($template);

$error = false;

# Ensure that the adventure's signup date is in the future
if (date("Y-m-d H:i:s") >= $object->getSignupDate()) {
    $template = Template::unhide($template, "DEADLINE");
    $error = true;
}

# Ensure that the member isn't already attending this adventure
if (JoinAdventure::checkIfMemberIsAttending($object, $obj['user'])) {
    $template = Template::unhide($template, "ALREADY");
    $error = true;
}

# Ensure that either the member is the leader or the adventure is active
if ($obj['user']->getUID() != $object->getOwner() 
        && $object->getStatus() != $cfg['status_id']['active']) {
    $template = Template::unhide($template, "INACTIVE");
    $error = true;
}

$form = null;

if (count($object->getChildren("question"))) {
    # Add the adventure's questions to the form.
    $formTemplate = JoinAdventure::addQuestionsToForm($formTemplate, $object);

    # Create and validate the form
    $form = new XMLForm(Template::finalize($formTemplate), true);
    $form->snatch();
    $form->validate();
}

if (!$error && (($form && $form->isValid()) || !$form)) {
    require_once("answer.php");

    $attendee = new attendee();
    $attendee->setMember($cfg['user']);
    $attendee->setAdventure($cfg['object']);
    $attendee->setJoinedDate(date("Y-m-d H:i:s"));
    if (($object->isFull() || $object->getWaitlistOnly())
        && $obj['user']->getUID() != $object->getOwner())
    {
        $attendee->setStatus($cfg['status_id']['waitlisted']);
    }
    $attendee->setAmountPaid($object->getFee());
    $attendee->insert();

    if ($form) {
        # Record the answers to the questions
        foreach ($object->getChildren("question") as $key => $q) {
            $answer = new answer();
            $answer->setQuestion($q->getUID());
            $answer->setAttendee($attendee->getUID());
            $answer->setAnswerText($form->getValue("question" . $q->getUID()));
            $answer->insert();
        }
    }

    # Send a confirmation email
    JoinAdventure::sendJoinConfirmation($obj['user'], $object,
        $attendee->getStatus());

    # Show confirmation page
    if ($attendee->getStatus() == $cfg['status_id']['waitlisted']) {
        $template = Template::unhide($template, "WAITLIST");
    }
    else {
        $template = Template::unhide($template, "SUCCESS");
    }

    if ($obj['user']->getUID() == $object->getOwner()
        && $object->getStatus() != $cfg['status_id']['active'])
    {
        $template = Template::unhide($template, "ACTIVATE");
    }
}

elseif (!$error) {
    $template = Template::unhide($template, "INSTRUCTIONS");
    $template = Template::replace($template, array("FORM" => $form->toString()));
}

$res['content'] = $template;
$res['title'] = "Join Adventure";

?>
