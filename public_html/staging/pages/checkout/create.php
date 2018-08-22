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
 * $Id: create.php,v 1.4 2009/03/12 03:16:00 pctainto Exp $
 */

$formT = file_get_contents("forms/checkout/create.xml");
$cmd = $obj['conn']->createCommand();
$cmd->loadQuery("sql/member/select-active.sql");
$cmd->addParameter("active", $cfg['status_id']['active']);
$result = $cmd->executeReader();
while ($row = $result->fetchRow()) {
    $formT = Template::block($formT, "member", $row);
}

$cmd = $obj['conn']->createCommand();
$cmd->loadQuery("sql/generic-select.sql");
$cmd->addParameter("table", "[_]activity_category");
$cmd->addParameter("orderby", "c_uid");
$result = $cmd->executeReader();
while ($row = $result->fetchRow()) {
    $formT = Template::block($formT, "option", $row);
}

$cmd->loadQuery("sql/member/select-active.sql");
$form = new XmlForm(Template::finalize($formT), true);
$form->snatch();
$form->validate();

if ($form->isValid()) {
    $object = new checkout();
    $object->setMember($form->getValue("member"));
    $object->setActivity($form->getValue("activity"));
    $object->setDueDate($form->getValue("due"));
    $object->insert();
    redirect("$cfg[base_url]/members/checkout/write/$object->c_uid");
}
else {
    $template = file_get_contents("templates/checkout/create.php");
    $res['content'] = template::replace($template, array("form" => $form->toString()));
    $res['title'] = "Choose a Member and Category";
}

?>
