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
 * $Id: write.php,v 1.3 2009/03/12 03:15:58 pctainto Exp $
 */

$template = file_get_contents("templates/phone_number/write.php");
$formTemplate = file_get_contents("forms/phone_number/write.xml");

# Create the owner for the phone number
$owner = new member();
$owner->select($object->getOwner());

# Add all phone number types to the form
$cmd = $obj['conn']->createCommand();
$cmd->loadQuery("sql/generic-select.sql");
$cmd->addParameter("table", "[_]phone_number_type");
$result = $cmd->executeReader();
while ($row = $result->fetchRow()) {
    $formTemplate = Template::block($formTemplate, "TYPE",
        array_change_key_case($row, 1));
}

# Create the form
$form = new XmlForm(Template::finalize($formTemplate), true);

# Put the phone's information into the form
$form->setValue("type", $object->getType());
$form->setValue("title", $object->getTitle());
$form->setValue("area-code", $object->getAreaCode());
$form->setValue("exchange", $object->getExchange());
$form->setValue("number", $object->getNumber());
$form->setValue("extension", $object->getExtension());

# Validate the form
$form->snatch();
$form->validate();

if ($form->isValid()) {
    # Update the phone with the form information
    $object->setType($form->getValue("type"));
    $object->setTitle($form->getValue("title"));
    $object->setAreaCode($form->getValue("area-code"));
    $object->setExchange($form->getValue("exchange"));
    $object->setNumber($form->getValue("number"));
    $object->setExtension($form->getValue("extension"));
    # Save the modified phone
    $object->update();
    $template = Template::unhide($template, "SUCCESS");
}

$template = Template::replace($template, array(
    "FORM" => $form->toString()));

$res['content'] = $object->insertIntoTemplate($template);
$res['title'] = "Edit Phone Number";

?>
