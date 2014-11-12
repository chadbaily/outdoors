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
 * $Id: write.php,v 1.2 2005/08/02 03:05:05 bps7j Exp $
 */

$template = file_get_contents("templates/chat/write.php");
$formTemplate = file_get_contents("forms/chat/write.xml");

# Add all chat types to the form
$cmd = $obj['conn']->createCommand();
$cmd->loadQuery("sql/generic-select.sql");
$cmd->addParameter("table", "[_]chat_type");
$result = $cmd->executeReader();
while ($row = $result->fetchRow()) {
    $formTemplate = Template::block($formTemplate, "TYPE",
        array_change_key_case($row, 1));
}

# Create the form
$form =& new XmlForm(Template::finalize($formTemplate), true);

# Put the chat's information into the form
$form->setValue("type", $object->getType());
$form->setValue("screen-name", $object->getScreenName());

# Validate the form
$form->snatch();
$form->validate();

if ($form->isValid()) {
    # Update the chat with the form information
    $object->setType($form->getValue("type"));
    $object->setScreenName($form->getValue("screen-name"));
    # Save the modified chat
    $object->update();
    $template = Template::unhide($template, "SUCCESS");
}

$template = Template::replace($template, array(
    "FORM" => $form->toString()));

$res['content'] = $object->insertIntoTemplate($template);
$res['title'] = "Edit Chat Identity";

?>
