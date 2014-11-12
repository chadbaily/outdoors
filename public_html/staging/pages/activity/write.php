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
 * $Id: write.php,v 1.3 2009/03/12 03:16:02 pctainto Exp $
 */

# Create templates
$template = file_get_contents("templates/activity/write.php");
$template = $object->insertIntoTemplate($template);

# Create and validate the form.
$formTemplate = file_get_contents("forms/activity/write.xml");
$cmd = $obj['conn']->createCommand();
$cmd->loadQuery("sql/generic-select.sql");
$cmd->addParameter("table", "[_]activity_category");
$cmd->addParameter("orderby", "c_uid");
$result = $cmd->executeReader();
while ($row = $result->fetchRow()) {
    $formTemplate = Template::block($formTemplate, "option", $row);
}

$form = new XmlForm(Template::finalize($formTemplate), true);
$form->snatch();
$form->validate();

# Put the activity's information into the form
$form->setValue("title", $object->getTitle());
$form->setValue("category", $object->getCategory());

# Validate the form
$form->snatch();
$form->validate();

if ($form->isValid()) {
    # Update the activity with the form information
    $object->setTitle($form->getValue("title"));
    $object->setCategory($form->getValue("category"));

    # Save the modified activity
    $object->update();
    $template = Template::unhide($template, "SUCCESS");
}

$res['content'] = Template::replace($template,
    array("FORM" => $form->toString()));
$res['title'] = "Edit Activity Details";

?>
