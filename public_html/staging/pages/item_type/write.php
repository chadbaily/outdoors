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
 * $Id: write.php,v 1.3 2009/03/12 03:15:59 pctainto Exp $
 */

# Create templates
$template = file_get_contents("templates/item_type/write.php");
$template = $object->insertIntoTemplate($template);

# Create the form.
$formT = file_get_contents("forms/item_type/write.xml");
$cmd = $obj['conn']->createCommand();
$cmd->loadQuery("sql/generic-select.sql");
$cmd->addParameter("table", "[_]item_category");
$cmd->addParameter("orderby", "c_title");
$result = $cmd->executeReader();
while ($row = $result->fetchRow()) {
    $formT = Template::block($formT, "CAT",
        array_change_key_case($row, 1));
}
foreach ($object->getChildren("item_type_feature") as $attr) {
    $formT = Template::block($formT, "OPTION",
        $attr->getVarArray());
}
$form = new XMLForm(Template::finalize($formT), true);

# Put the object's information into the form
$form->setValue("category", $object->getCategory());
$form->setValue("title", $object->getTitle());
$form->setValue("primary-feature", $object->getPrimaryFeature());
$form->setValue("secondary-feature", $object->getSecondaryFeature());

# Validate the form
$form->snatch();
$form->validate();

if ($form->isValid()) {
    # Update the object with the form information
    $object->setTitle($form->getValue("title"));
    $object->setCategory($form->getValue("category"));
    if ($form->getValue("secondary-feature")) {
        $object->setSecondaryFeature($form->getValue("secondary-feature"));
    }
    if ($form->getValue("primary-feature")) {
        $object->setPrimaryFeature($form->getValue("primary-feature"));
    }

    # Save the modified object
    $object->update();
    $template = Template::unhide($template, "SUCCESS");
}

$res['content'] = Template::replace($template,
    array("FORM" => $form->toString()));
$res['title'] = "Edit Item Type Details";

?>
