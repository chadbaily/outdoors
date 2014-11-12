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
 * $Id: write.php,v 1.3 2009/03/12 03:16:01 pctainto Exp $
 */

include_once("item_note.php");

# Create templates
$template = file_get_contents("templates/item/write.php");
$template = $object->insertIntoTemplate($template);

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

# Create the form and populate the condition menu
$formTemplate = file_get_contents("forms/item/write.xml");

$cmd = $obj['conn']->createCommand();
$cmd->loadQuery("sql/generic-select.sql");
$cmd->addParameter("table", "[_]condition");
$cmd->addParameter("orderby", "c_rank");
$result = $cmd->executeReader();

while ($row = $result->fetchRow()) {
    $formTemplate = Template::block($formTemplate, "OPTION",
        array_change_key_case($row, 1));
}

foreach (array("checked_out", "checked_in", "missing") as $status) {
    $formTemplate = Template::block($formTemplate, "STATUS", array(
        "C_TITLE" => $status,
        "C_UID" => $cfg['status_id'][$status]));
}

$form = new XMLForm(Template::finalize($formTemplate), true);

# Put the item's information into the form
$form->setValue("description", $object->getDescription());
$form->setValue("purchase-date", $object->getPurchaseDate());
$form->setValue("condition", $object->getCondition());
$form->setValue("status", $object->getStatus());
$form->setValue("qty", $object->getQty());

# Validate the form
$form->snatch();
$form->validate();

if ($form->isValid()) {
    # Update the object with the form information, possibly saving a note
    if (trim($form->getValue("note"))
            || $object->getCondition() != $form->getValue("condition")
            || $object->getStatus() != $form->getValue("status")) {
        $note = new item_note();
        $note->setItem($cfg['object']);
        $note->setNote($form->getValue("note"));
        $note->setCondition($form->getValue("condition"));
        $note->setStatus($form->getValue("status"));
        $note->insert();
    }
    $object->setDescription($form->getValue("description"));
    $object->setPurchaseDate($form->getValue("purchase-date"));
    $object->setCondition($form->getValue("condition"));
    $object->setStatus($form->getValue("status"));
    $object->setQty($form->getValue("qty"));
    $object->update();
    if (isset($_GET['mode'])) {
        redirect("$cfg[base_url]/members/item/edit_features/$cfg[object]");
    }
    else {
        redirect("$cfg[base_url]/members/item/read/$cfg[object]");
    }
}

$res['content'] = Template::replace($template,
    array("FORM" => $form->toString()));
$res['title'] = "Edit Item Details";

?>
