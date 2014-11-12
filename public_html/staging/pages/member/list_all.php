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
 * $Id: list_all.php,v 1.6 2009/03/12 03:15:59 pctainto Exp $
 */

$template = file_get_contents("templates/member/list_all.php");

# Create the form
$formT = file_get_contents("forms/member/list_all.xml");
if ($obj['user']->isInGroup('root') || $obj['user']->isInGroup('officer')) {
    $formT = Template::unhide($formT, "HIDDEN");
}

$form = new XmlForm(Template::finalize($formT), true);
$form->snatch();

# Show the members in a list.  Don't show information that the user isn't
# supposed to see if it's private (email address, primary phone number).  Also
# don't show members that don't have an active membership.  Create two SQL
# command objects, one for the result set, another to get a count of the results
# that would be returned if there were no pagination.

$cmd = $obj['conn']->createCommand();
$numCmd = $obj['conn']->createCommand();
$cmd->loadQuery("sql/member/list_all.sql");
$numCmd->loadQuery("sql/member/count.sql");

# Add filter criteria.
$nameCrit = $form->getValue("name");
$emailCrit = $form->getValue("email");
if ($nameCrit) {
    $cmd->addParameter("name", "%$nameCrit%");
    $numCmd->addParameter("name", "%$nameCrit%");
}
if ($emailCrit) {
    $cmd->addParameter("email", "%$emailCrit%");
    $numCmd->addParameter("email", "%$emailCrit%");
}
if ($obj['user']->isInGroup('root') || $obj['user']->isInGroup('officer')) {
    if ($form->getValue("view_inactive")) {
        $cmd->addParameter("view_inactive", 1);
        $numCmd->addParameter("view_inactive", 1);
    }
    if ($form->getValue("view_private")) {
        $cmd->addParameter("view_private", 1);
        $numCmd->addParameter("view_private", 1);
    }
}

# Add constants.
$cmd->addParameter("active", $cfg['status_id']['active']);
$numCmd->addParameter("active", $cfg['status_id']['active']);

$form->setValue("limit", max(10, min(100, intval($form->getValue("limit")))));

# Now we can find out how many results there will be, and build form elements
# to allow the user to select which page to show.
$numRows = $numCmd->executeScalar();
$numPages = ceil($numRows / $form->getValue("limit"));
$select = $form->form->getElementByID("offset");
for ($i = 1; $i <= $numPages; ++$i) {
    $option = $select->ownerDocument->createElement("option");
    $text = $select->ownerDocument->createTextNode("page $i of $numPages");
    $option->appendChild($text);
    $option->setAttribute("value", $i);
    $select->appendChild($option);
}
if (isset($_GET['offset'])) {
    $form->setValue("offset", intval($_GET['offset']));
}
$form->setValue("offset", max(1, $form->getValue("offset")));
if (!$form->getValue("sort")) {
    $form->setValue("sort", "last_name");
}

# Add pagination parameters
if ($form->getValue("limit") && $form->getValue("offset")) {
    $cmd->addParameter("offset", $form->getValue("limit")
        * ($form->getValue("offset") - 1));
}
$cmd->addParameter("limit", $form->getValue("limit"));
$cmd->addParameter("orderby", "c_" . $form->getValue("sort"));

$result = $cmd->executeReader();

while ($row = $result->fetchRow()) {
    $template = Template::block($template, "ROW", $row);
}

if ($result->numRows()) {
    $template = Template::unhide($template, "SOME");
    $template = Template::replace($template, array("NUM" => $numRows));
}
else {
    $template = Template::unhide($template, "NONE");
}

$res['title'] = "Member Directory";
$res['content'] = Template::replace($template, array(
    "form" => $form->toString()));

?>
