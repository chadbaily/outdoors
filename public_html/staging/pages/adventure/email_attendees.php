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
 * $Id: email_attendees.php,v 1.3 2009/03/12 03:15:58 pctainto Exp $
 */

include_once("Email.php");
include_once("location.php");

$template = file_get_contents("templates/adventure/email_attendees.php");

# Create the form.
$form = new XMLForm("forms/adventure/email_attendees.xml");

# Validate the form
$form->snatch();
$form->validate();

if ($form->isValid()) {
    $who = $form->getValue("who");

    // Insert the email into the DB
    $cmd = $obj['conn']->createCommand();
    $cmd->loadQuery("sql/email/insert.sql");
    $cmd->addParameter("subject", $form->getValue("subject"));
    $cmd->addParameter("message", $form->getValue("message"));
    $cmd->addParameter("what_relates_to", "[_]adventure");
    $cmd->addParameter("related_uid", $cfg['object']);
    $cmd->addParameter("user", $cfg['user']);
    $result = $cmd->executeReader();
    $id = $result->identity();

    $cmd = $obj['conn']->createCommand();
    $cmd->loadQuery("sql/email/insert-adventure-attendees.sql");
    $cmd->addParameter("email", $id);
    $cmd->addParameter("adventure", $cfg['object']);
    $cmd->addParameter("leader", $object->getOwner());
    if ($who == "joined") {
        $cmd->addParameter("status", $cfg['status_id']['default']);
    }
    else if ($who == "waitlisted") {
        $cmd->addParameter("status", $cfg['status_id']['waitlisted']);
    }
    $cmd->executeNonQuery();

    $template = Template::unhide($template, "SUCCESS");
}
else {
    $template = Template::unhide($template, "CONFIRM");
    $template = Template::replace($template, array("FORM" => $form->toString()));
}

$res['content'] = Template::replace($template,
        array("C_TITLE" => $object->getTitle()));
$res['title'] = "Email Adventure Attendees";

?>
