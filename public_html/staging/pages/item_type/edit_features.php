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
 * $Id: edit_features.php,v 1.3 2009/03/12 03:15:59 pctainto Exp $
 */

include_once("item_type_feature.php");

# Create templates
$template = file_get_contents("templates/item_type/edit_features.php");

# Reserved names so there are no conflicts with item/list_all's generated
# queries.
$reserved = array("id", "qty", "condition", "status");
# Add a form to the page so the user can add an feature
$form = new XmlForm("forms/item_type_feature/create.xml");
$form->snatch();
$form->validate();
if (in_array(strtolower($form->getValue("name")), $reserved)) {
    $template = Template::replace($template, array("reserved" => "error"));
}
elseif ($form->isValid()) {
    # Add a new feature to the object
    $attr = new item_type_feature();
    $attr->setType($cfg['object']);
    $attr->setName($form->getValue("name"));
    $attr->insert();
}

$template = Template::replace($template, array(
    "reserved" => "notice",
    "FORM" => $form->toString()));

# Delete features if necessary
if (postval("delete")) {
    $attr = new item_type_feature();
    $attr->select(postval("delete"));
    $attr->delete(true);
    # Delete the features from all the items of this type, too
    $cmd = $obj['conn']->createCommand();
    $cmd->loadQuery("sql/item_feature/delete-by-item-type.sql");
    $cmd->addParameter("item_type", $cfg['object']);
    $cmd->addParameter("attr_name", $attr->getName());
    $cmd->executeNonQuery();
}

# Add all the existing features to the template
foreach ($object->getChildren("item_type_feature") as $attr) {
    $template = Template::block($template, "ATTR", array(
        "C_NAME" => $attr->getName(),
        "C_UID" => $attr->getUID(),
        "CLASS" => ($attr->getUID() == $object->getPrimaryFeature())
            ? " style='font-weight:bold'"
            : ""));
}

$res['content'] = $object->insertIntoTemplate($template);
$res['title'] = "Edit Features for " . $object->getTitle();

?>
