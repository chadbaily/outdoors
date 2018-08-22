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
 * $Id: list_all.php,v 1.3 2009/03/12 03:16:02 pctainto Exp $
 */

# Create a template 
$template = file_get_contents("templates/expense_submission/list_all.php");

$formTemplate = file_get_contents("forms/expense_submission/list_all.xml");
foreach (array("default", "submitted", "paid") as $status) {
    $formTemplate = Template::block($formTemplate, "STATUS",
        array("id" => $cfg['status_id'][$status], "c_title" => $status));
}
$form = new XmlForm(Template::finalize($formTemplate), true);
$form->snatch();

$cmd = $obj['conn']->createCommand();
$cmd->loadQuery("sql/expense_submission/list_all.sql");
if ($form->getValue("begin")) {
    $cmd->addParameter("begin", date("Y-m-d", strtotime($form->getValue("begin"))));
}
if ($form->getValue("end")) {
    $cmd->addParameter("end", date("Y-m-d", strtotime($form->getValue("end"))));
}
if ($form->getValue("status")) {
    $cmd->addParameter("status", $form->getValue("status"));
}
$result = $cmd->executeReader();

while ($row = $result->fetchRow()) {
    $template = Template::block($template, "report", $row);
}

if ($result->numRows()) {
    $template = Template::unhide($template, "SOME");
}
else {
    $template = Template::unhide($template, "NONE");
}

$res['content'] = Template::replace($template, array("FORM" => $form->toString()));
$res['title'] = "List All Expense Submissions";

?>
