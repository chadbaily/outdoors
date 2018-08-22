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
 * $Id: create.php,v 1.4 2009/03/12 03:16:01 pctainto Exp $
 */

# Create templates
$template = file_get_contents("templates/expense/create.php");

# Create the form and populate the category and adventure menus
$formTemplate = file_get_contents("forms/expense/create.xml");

$cmd = $obj['conn']->createCommand();
$cmd->loadQuery("sql/adventure/list_all-past.sql");
$cmd->addParameter("start", date("Y-m-d", strtotime("3 months ago")));
$result = $cmd->executeReader();
while ($row = $result->fetchRow()) {
    $formTemplate = Template::block($formTemplate, "adventure", $row);
}

$cmd = $obj['conn']->createCommand();
$cmd->loadQuery("sql/generic-select.sql");
$cmd->addParameter("table", "[_]expense_category");
$cmd->addParameter("orderby", "c_title");
$result = $cmd->executeReader();
while ($row = $result->fetchRow()) {
    $formTemplate = Template::block($formTemplate, "category", $row);
}

$form = new XMLForm(Template::finalize($formTemplate), true);
$form->snatch();
$form->validate();

if ($form->isValid()) {
    # Add the new expense to the report, then redirect back to the report
    $object = new expense();
    $object->setReport($form->getValue("report"));
    $object->setCategory($form->getValue("category"));
    $object->setAdventure($form->getValue("adventure"));
    $object->setAmount($form->getValue("amount"));
    $object->setMerchant($form->getValue("merchant"));
    $object->setDescription($form->getValue("description"));
    $object->setExpenseDate($form->getValue("date"));
    $object->insert();
    redirect("$cfg[base_url]/members/expense_report/write/" . $form->getValue("report"));
}
else {
    # Re-display the form and force the user to fix the mistake.
}

$res['content'] = Template::replace($template, array(
    "FORM" => $form->toString()));

$res['title'] = "Add an Expense to a Report";

?>
