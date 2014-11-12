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
 * $Id: write.php,v 1.5 2009/03/12 03:15:58 pctainto Exp $
 */

include_once("location.php");

$template = file_get_contents("templates/adventure/write.php");

# Ensure that the adventure's start date is in the future and the adventure
# is not cancelled or deleted.
if (date("Y-m-d H:i:s") >= $object->getStartDate()) {
    $template = Template::unhide($template, "DATE");
    $error = true;
}
if ($object->getDeleted()
    || $object->getStatus() === $cfg['status_id']['cancelled'])
{
    $template = Template::unhide($template, "STATUS");
    $error = true;
}

# Create the form.
$formTemplate = file_get_contents("forms/adventure/write.xml");

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

# Turn the form template into a form and XML-parse it
$form = new XMLForm(Template::finalize($formTemplate), true);

# Put the adventure's information into the form
$form->setValue("title", $object->getTitle());
$form->setValue("description", $object->getDescription());
$form->setValue("departure", $object->getDeparture());
$form->setValue("destination", $object->getDestination());
$form->setValue("attendees", $object->getMaxAttendees());
$form->setValue("fee", $object->getFee());
$form->setValue("start", $object->c_start_date);
$form->setValue("end", $object->c_end_date);
$form->setValue("signup", $object->c_signup_date);
if ($object->c_waitlist_only) {
    $form->setValue("waitlist", "1");
}

# Validate the form
$form->snatch();
$form->validate();

$error = false;

# The adventure's size can be reduced, but not if it's full, and not to less
# than the number of people that are already joined.
$newSize = $form->getValue("attendees");
$oldSize = $object->getMaxAttendees();
if ($newSize < $oldSize && $newSize < count($object->getAttendees("default"))) {
    $template = Template::unhide($template, "TOO_SMALL");
    $template = Template::replace($template,
        array("SIZE" => count($object->getAttendees("default"))));
    $error = true;
}

if (!$error && $form->isValid()) {
    # Update the adventure with the form information
    $object->setTitle($form->getValue("title"));
    $object->setDescription($form->getValue("description"));
    $object->setDeparture($form->getValue("departure"));
    $object->setDestination($form->getValue("destination"));
    $object->setMaxAttendees($form->getValue("attendees"));
    $object->setFee($form->getValue("fee"));
    $object->setStartDate($form->getValue("start"));
    $object->setEndDate($form->getValue("end"));
    $object->setSignupDate($form->getValue("signup"));
    $object->setWaitlistOnly($form->getValue("waitlist") ? 1 : 0);

    # Save the modified adventure
    $object->update();

    # If the adventure's size was increased, let some members off the waitlist
    # if necessary
    if (!$object->getWaitlistOnly()
            && $newSize > $oldSize
            && $newSize > count($object->getAttendees("default"))
            && count($object->getAttendees("waitlisted")))
    {
        $added = false;
        for ($i = count($object->getAttendees("default")); $i < $newSize; $i++) {
            $member = JoinAdventure::removeFirstWaitlistedMember($object);
            if (!is_null($member)) {
                $added = true;
                $template = Template::block($template, "MEMBER",
                    $member->getVarArray());
            }
        }
        if ($added) {
            $template = Template::unhide($template, "ADDED");
        }
    }

    $template = Template::unhide($template, "SUCCESS");
}
else {
    $template = Template::replace($template,
        array("FORM" => $form->toString()));
}

$res['content'] = $object->insertIntoTemplate($template);
$res['title'] = "Edit Adventure Details";

?>
