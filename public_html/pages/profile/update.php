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
 * $Id: update.php,v 1.2 2005/08/02 03:05:26 bps7j Exp $
 */

include_once("JoinClub.php");

# Create templates
$wrapper = file_get_contents("templates/profile/update.php");

# Create the form
$form =& new XMLForm("forms/profile/update.xml");

# Fill the form with the user's data
$form->setValue("firstName", $obj['user']->getFirstName());
$form->setValue("lastName", $obj['user']->getLastName());
$form->setValue("gender", $obj['user']->getGender());
$form->setValue("student", $obj['user']->getIsStudent());
$form->setValue("emailAddress", $obj['user']->getEmail());

# Now overwrite this data with whatever the user submitted
$form->snatch();
$form->validate();

if ($form->isValid()) {
    # Check that the member isn't trying to change his/her email address to one
    # that's already in use
    if ($obj['user']->getEmail() != $form->getValue("emailAddress")
            && JoinClub::checkIfMemberExists($form->getValue("emailAddress"))) {
        $wrapper = Template::unhide($wrapper, "INUSE");
    }
    else {
        # Update the database with the form values
        $emailChanged = ($obj['user']->getEmail() != $form->getValue("emailAddress"));
        $obj['user']->setFirstName($form->getValue("firstName"));
        $obj['user']->setLastName($form->getValue("lastName"));
        $obj['user']->setGender($form->getValue("gender"));
        $obj['user']->setIsStudent($form->getValue("student"));
        $obj['user']->setEmail($form->getValue("emailAddress"));
        $obj['user']->update();
        $wrapper = Template::unhide($wrapper, "SUCCESS");
        if ($emailChanged) {
            $wrapper = Template::unhide($wrapper, "EMAIL");
        }
    }
}

$res['content'] = Template::replace($wrapper, array(
    "FORM" => $form->toString()));
$res['title'] = "Update Member Profile";

?>
