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
 * $Id: create.php,v 1.5 2009/03/12 03:15:57 pctainto Exp $
 */

include_once("location.php");
include_once("adventure_activity.php");

# Create the form and the template
$formTemplate = file_get_contents("forms/adventure/create.xml");
$template = file_get_contents("templates/adventure/create.php");

# Ensure that the user's contact info isn't private 
if ($obj['user']->getHidden()) {
    $template = Template::unhide($template, "ERROR");
    $res['content'] = $template;
}
else {

    $template = Template::unhide($template, "INSTRUCTIONS");

    # Get a list of all locations in the DB.
    $cmd = $obj['conn']->createCommand();
    $cmd->loadQuery("sql/generic-select.sql");
    $cmd->addParameter("table", "[_]location");
    $cmd->addParameter("orderby", "c_title");
    $result = $cmd->executeReader();

    # Add the locations to the drop-down menus.
    while ($row = $result->fetchRow()) {
        $formTemplate = Template::block(
            $formTemplate, array("DEPART","DEST"), 
            array_change_key_case($row, 1));
    }

    # Get a list of all activities in the DB.
    $cmd = $obj['conn']->createCommand();
    $cmd->loadQuery("sql/generic-select.sql");
    $cmd->addParameter("table", "[_]activity");
    $cmd->addParameter("orderby", "c_title");
    $result = $cmd->executeReader();

    # Add the activities in $cols columns to the page
    $cols = 3;
    $rowTemplate = Template::extract($formTemplate, "ACTIVITY_ROW");
    for ($i = 0; $i < $result->numRows();) {
        $thisRow = $rowTemplate;
        for ($j = 0; $j < $cols && $row = $result->fetchRow(); ++$j, ++$i) {
            $thisRow = Template::block($thisRow, "ACTIVITY",
                array_change_key_case($row, 1)
                + array("WIDTH" => (int) (100 / $cols)));
        }

        $formTemplate = Template::replace($formTemplate,
            array("ACTIVITY_ROW" => $thisRow), true);
    }

    # Turn the form template into a form and XML-parse it
    $form = new XMLForm(Template::finalize($formTemplate), true);
    $form->snatch();
    $form->validate();

    if ($form->isValid()) {
        # Make sure the adventure's signup deadline is in the future (the form
        # validation has already made sure that signup <= start < end)
        if (date("Y-m-d H:i:s") >= $form->getValue("signup")) {
            $template = Template::unhide($template, "FUTURE");
            $res['content'] = Template::replace($template, array(
                "FORM" => $form->toString()));
        }
        else {
            # Save the adventure in the database
            $object = new adventure();
            $object->setFee($form->getValue("fee"));
            $object->setTitle($form->getValue("title"));
            $object->setDescription($form->getValue("description"));
            $object->setMaxAttendees($form->getValue("attendees"));
            $object->setDestination($form->getValue("destination"));
            $object->setDeparture($form->getValue("departure"));
            $object->setStartDate($form->getValue("start"));
            $object->setEndDate($form->getValue("end"));
            $object->setSignupDate($form->getValue("signup"));
            $object->setStatus($cfg['status_id']['inactive']);
            if ($form->getValue("waitlist")) {
                $object->setWaitlistOnly(1);
            }
            $object->insert();

            # Create and save adventure-activity associations
            foreach ($form->getValue("activity") as $uid => $selected) {
                if ($selected) {
                    $advAct = new adventure_activity();
                    $advAct->setAdventure($object->getUID());
                    $advAct->setActivity($uid);
                    $advAct->insert();
                }
            }
            
            # Display instructions & links for the next step.
            redirect("$cfg[base_url]/members/adventure/edit_questions/$object->c_uid");
        }
    }
    else {
        $res['content'] = Template::replace($template, array(
           "FORM" => $form->toString()));
    }
}

$res['title'] = "Create Adventure";

?>
