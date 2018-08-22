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
 * $Id: write.php,v 1.2 2009/03/12 03:15:58 pctainto Exp $
 */

$template = file_get_contents("templates/email_list/write.php");
$template = $object->insertIntoTemplate($template);

# Create the form.  Initialize it, then overwrite that with form data.
$form = new XmlForm("forms/email_list/write.xml");

$form->setValue("title", $object->getTitle());
$form->setValue("description", $object->getDescription());
$form->setValue("name", $object->getName());
$form->setValue("password", $object->getPassword());
$form->setValue("owner-address", $object->getOwnerAddress());
$form->setValue("mgmt-address", $object->getMgmtAddress());
$form->setValue("list-address", $object->getListAddress());
$form->setValue("type", $object->getType());
$form->setValue("subject-prefix", $object->getSubjectPrefix());

$form->snatch();
$form->validate();

if ($form->isValid()) {

    $object->setTitle($form->getValue("title"));
    $object->setDescription($form->getValue("description"));
    $object->setName($form->getValue("name"));
    $object->setPassword($form->getValue("password"));
    $object->setOwnerAddress($form->getValue("owner-address"));
    $object->setMgmtAddress($form->getValue("mgmt-address"));
    $object->setListAddress($form->getValue("list-address"));
    $object->setType($form->getValue("type"));
    $object->setSubjectPrefix($form->getValue("subject-prefix"));
    $object->update();

    $template = Template::unhide($template, "SUCCESS");
}

$res['content'] = Template::replace($template,
    array("FORM" => $form->toString()));
$res['title'] = "Edit Email List Details";

?>
