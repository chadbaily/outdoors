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
 * $Id: list_all.php,v 1.9 2009/03/12 03:16:00 pctainto Exp $
 */

# Create a template 
$template = file_get_contents("templates/checkout/list_all.php");

$formTemplate = file_get_contents("forms/checkout/list_all.xml");
foreach (array("default", "checked_out", "checked_in") as $status) {
    $formTemplate = Template::block($formTemplate, "STATUS",
        array("id" => $cfg['status_id'][$status], "c_title" => $status));
}
$cmd = $obj['conn']->createCommand();
$cmd->loadQuery("sql/checkout/select-members.sql");
$result = $cmd->executeReader();
while ($row = $result->fetchRow()) {
    $formTemplate = Template::block($formTemplate, "member", $row);
}
$cmd = $obj['conn']->createCommand();
$cmd->loadQuery("sql/item_type/select-by-category.sql");
$result = $cmd->executeReader();
$thisCat = "";
$groupTemplate = Template::extract($formTemplate, "GROUP");
$formTemplate = Template::delete($formTemplate, "GROUP");
$thisGroup = "";
while ($row = $result->fetchRow()) {
    if ($thisCat != $row['cat_title']) {
        $thisCat = $row['cat_title'];
        $formTemplate = Template::replace($formTemplate, array(
            "TYPES" => $thisGroup), 1);
        $thisGroup = Template::replace($groupTemplate, array(
            "cat_title" => $row['cat_title']));
    }
    $thisGroup = Template::block($thisGroup, "TYPE", $row);
}
$formTemplate = Template::replace($formTemplate, array(
    "TYPES" => $thisGroup), 1);

$form = new XmlForm(Template::finalize($formTemplate), true);
$form->setValue("status", $cfg['status_id']['checked_out']);
$form->snatch();
$form->validate();

$cmd = $obj['conn']->createCommand();
$cmd->loadQuery("sql/checkout/list_all.sql");
$cmd->addParameter("checked_out", $cfg['status_id']['checked_out']);
if ($form->getValue("status")) {
    $cmd->addParameter("status", $form->getValue("status"));
}
if ($form->getValue("member")) {
    $cmd->addParameter("member", $form->getValue("member"));
}
if ($form->getValue("begin")) {
    $cmd->addParameter("begin", date("Y-m-d", strtotime($form->getValue("begin"))));
}
if ($form->getValue("end")) {
    $cmd->addParameter("end", date("Y-m-d", strtotime($form->getValue("end"))));
}
if ($form->getValue("due")) {
    $cmd->addParameter("due", date("Y-m-d", strtotime($form->getValue("due"))));
}
if ($form->getValue("type")) {
    $cmd->addParameter("type", $form->getValue("type"));
}
if ($form->getValue("item")) {
    $cmd->addParameter("item", $form->getValue("item"));
}
$result = $cmd->executeReader();

while ($row = $result->fetchRow()) {
    $template = Template::block($template, "checkout", $row);
}

if ($result->numRows()) {
    $template = Template::unhide($template, "SOME");
}
else {
    $template = Template::unhide($template, "NONE");
}

$res['content'] = Template::replace($template, array("FORM" => $form->toString()));
$res['title'] = "Checked-Out Gear";

?>
