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
 * $Id: view_answers.php,v 1.3 2009/03/12 03:15:58 pctainto Exp $
 * Allows a member to view and edit his/her answers to questions.
 *
 * Two scenarios are possible:  The user chose to view answers for a specific
 * attendee, or the user chose to view his/her own answers for an adventure.
 * Either one will work, because the combination of c_adventure/c_member is a 
 * unique key on the [_]attendee table.
 */

include_once("answer.php");
include_once("JoinAdventure.php");

# Get info about the attendee & adventure
$member = new member();
$adventure = new adventure();
$member->select($object->getMember());
$adventure->select($object->getAdventure());

# Create templates 
$template = file_get_contents("templates/attendee/view_answers.php");
$template = $object->insertIntoTemplate(
    $member->insertIntoTemplate(
        $adventure->insertIntoTemplate($template)));

$error = false;

# Ensure that the adventure's start date is in the future
if (date("Y-m-d H:i:s") >= $adventure->getStartDate()) {
    $template = Template::unhide($template, "DEADLINE");
    $error = true;
}

# Create the form.
$formTemplate = file_get_contents("forms/attendee/view_answers.xml");

# Add the adventure's questions to the form.
$formTemplate = JoinAdventure::addQuestionsToForm($formTemplate, $adventure);
$formTemplate = Template::finalize($formTemplate);
$form = new XMLForm($formTemplate, true);

# Set the answer values in the form, and save the answers for later (rekey them
# by question ID)
$answers = array();
foreach ($object->getChildren("answer") as $answer) {
    $answers[$answer->getQuestion()] = $answer;
    $form->setValue("question" . $answer->getQuestion(), $answer->getAnswerText());
}

# Validate the form
$form->snatch();
$form->validate();

if (!$error && $form->isValid()) {
    # Record the answers to the questions
    foreach ($adventure->getChildren("question") as $key => $q) {
        $answer = new answer();
        # Update existing answers & create new ones
        if (isset($answers[$q->getUID()])) {
            $answers[$q->getUID()]->setAnswerText($form->getValue("question" . $q->getUID()));
            $answers[$q->getUID()]->update();
        }
        else {
            $answer = new answer();
            $answer->setQuestion($q->getUID());
            $answer->setOwner($member->getUID());
            $answer->setAttendee($cfg['object']);
            $answer->setAnswerText($form->getValue("question" . $q->getUID()));
            $answer->insert();
        }
    }

    # Show confirmation message
    $template = Template::unhide($template, "SUCCESS");
}
elseif (!$error) {
    $template = Template::unhide($template, "INSTRUCTIONS");
}

if (!$error) {
    $template = Template::replace($template, array("FORM" => $form->toString()));
}

$res['content'] = Template::replace($template, array(
    "FORM" => $form->toString()));
$res['title'] = "Update Answers";

?>
