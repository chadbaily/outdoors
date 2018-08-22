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
 * $Id: list_all.php,v 1.3 2009/03/12 03:16:01 pctainto Exp $
 */

$res['title'] = "List All Expenses";
$template = file_get_contents("templates/expense/list_all.php");

# Create the filter for category and type

$formTemplate = file_get_contents("forms/expense/list_all.xml");
$cmd = $obj['conn']->createCommand();
$cmd->loadQuery("sql/generic-select.sql");
$cmd->addParameter("table", "[_]expense_category");
$cmd->addParameter("orderby", "c_title");
$result = $cmd->executeReader();
while ($row = $result->fetchRow()) {
    $formTemplate = Template::block($formTemplate, "cat", $row);
}

# Create the form
$form = new XmlForm(Template::finalize($formTemplate), true);
$form->snatch();
$template = Template::replace($template, array("FORM" => $form->toString()));

if (!$form->getValue("category")) {
    $cmd = $obj['conn']->createCommand();
    $cmd->loadQuery("sql/expense/summarize.sql");
    if ($form->getValue("begin")) {
        $cmd->addParameter("begin", date("Y-m-d", strtotime($form->getValue("begin"))));
    }
    if ($form->getValue("end")) {
        $cmd->addParameter("end", date("Y-m-d", strtotime($form->getValue("end"))));
    }
    if ($form->getValue("reimbursable") !== "") {
        $cmd->addParameter("reimbursable", $form->getValue("reimbursable"));
    }
    if ($form->getValue("status") !== "") {
        $cmd->addParameter("status", $form->getValue("status"));
    }
    $result = $cmd->executeReader();
    if ($result->numRows()) {
        $template = Template::unhide($template, "GENERIC");
        $template = Template::delete($template, "BY_TYPE");
    }
}
else {
    # Exec the query.
    $cmd = $obj['conn']->createCommand();
    $cmd->loadQuery("sql/expense/select-by-category.sql");
    $cmd->addParameter("category", $form->getValue('category'));
    if ($form->getValue("reimbursable") !== "") {
        $cmd->addParameter("reimbursable", $form->getValue("reimbursable"));
    }
    if ($form->getValue("status") !== "") {
        $cmd->addParameter("status", $form->getValue("status"));
    }
    if ($form->getValue("begin")) {
        $cmd->addParameter("begin", date("Y-m-d", strtotime($form->getValue("begin"))));
    }
    if ($form->getValue("end")) {
        $cmd->addParameter("end", date("Y-m-d", strtotime($form->getValue("end"))));
    }
    $result = $cmd->executeReader();
    if ($result->numRows()) {
        $template = Template::unhide($template, "BY_TYPE");
        $template = Template::delete($template, "GENERIC");
    }
}


if ($result->numRows()) {
    while ($row = $result->fetchRow()) {
        $template = Template::block($template, "row", $row);
    }
}
else {
    $template = Template::unhide($template, "NONE");
}

$res['content'] = $template;

?>
