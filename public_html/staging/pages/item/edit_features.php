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
 * $Id: edit_features.php,v 1.3 2009/03/12 03:16:01 pctainto Exp $
 */

# Create templates
$template = file_get_contents("templates/item/edit_features.php");
# Grab the nested templates out
$attr = Template::extract($template, "ATTRIBUTE");
$template = Template::delete($template, "ATTRIBUTE");
$option = Template::extract($attr, "OPTION");
$attr = Template::unhide($attr, "OPTION");

# Get the next and last items in the list
$cmd = $obj['conn']->createCommand();
$cmd->loadQuery("sql/item/next-by-type.sql");
$cmd->addParameter("type", $object->getType());
$cmd->addParameter("item", $cfg['object']);
$next = $cmd->executeScalar();
if ($next) {
    $template = Template::unhide($template, "NEXT");
    $template = Template::replace($template,
        array ("NEXT_ID" => $next));
}
$cmd = $obj['conn']->createCommand();
$cmd->loadQuery("sql/item/last-by-type.sql");
$cmd->addParameter("type", $object->getType());
$cmd->addParameter("item", $cfg['object']);
$last = $cmd->executeScalar();
if ($last) {
    $template = Template::unhide($template, "LAST");
    $template = Template::replace($template,
        array ("LAST_ID" => $last));
}

# If the form has been submitted, update and create as necessary
if (postval('edit_features')) {
    # Select the features that this type of item is supposed to have
    $cmd = $obj['conn']->createCommand();
    $cmd->loadQuery("sql/item/select-features.sql");
    $cmd->addParameter("item", $cfg['object']);
    $result = $cmd->executeReader();
    while ($row = $result->fetchRow()) {
        if ($row['c_uid']) {
            $cmd = $obj['conn']->createCommand();
            $cmd->loadQuery("sql/item_feature/update.sql");
            $cmd->addParameter("uid", $row['c_uid']);
            $cmd->addParameter("value", $_POST[$row['c_name']]);
            $cmd->executeNonQuery();
        }
        else {
            $at = new item_feature();
            $at->setName($row['c_name']);
            $at->setValue($_POST[$row['c_name']]);
            $at->setItem($cfg['object']);
            $at->insert();
        }
    }
    $template = Template::unhide($template, "SUCCESS");
}

# Re-select the features that this type of item is supposed to have
$cmd = $obj['conn']->createCommand();
$cmd->loadQuery("sql/item/select-features.sql");
$cmd->addParameter("item", $cfg['object']);
$result = $cmd->executeReader();
while ($row = $result->fetchRow()) {
    $thisAttr = Template::replace($attr, array(
        "C_NAME" => $row['c_name'],
        "C_VALUE" => ($row['c_value'] ? $row['c_value'] : "")));
    # Get a list of possible values for this feature & item type, and
    # pre-populate the select menu
    $cmd = $obj['conn']->createCommand();
    $cmd->loadQuery("sql/item_feature/select-existing-values.sql");
    $cmd->addParameter("item_type", $object->getType());
    $cmd->addParameter("name", $row['c_name']);
    $cmd->addParameter("value", $row['c_value']);
    $result2 = $cmd->executeReader();
    while ($row2 = $result2->fetchRow()) {
        $thisAttr = Template::replace($thisAttr, array(
            "OPTIONS" => "<option>$row2[c_value]</option>"), true);
    }
    $template = Template::replace($template, array("ATTRS" => $thisAttr), true);
}

$res['content'] = $object->insertIntoTemplate($template);
$res['title'] = "Edit Features for Item $cfg[object]";

?>
